<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\AppendOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityDecision;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityRequest;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Domain\Organisational\AuthorityOutcome;
use App\Contexts\Membership\Domain\Organisational\CompetenceKind;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * The shared command loop of Slice 1a (contract §4):
 *
 *   idempotency pre-check (IDEM-1), repeated when the domain refuses (an identical command may
 *   have been applied meanwhile; found by the PostgreSQL race test, PBDIGIT-260)
 *   repeat ≤ MAX_ATTEMPTS:  find → request → authorise → (create) → decide (guards + G*) → append(expected version)
 *     on ConcurrencyConflict: discard the decision and start again from load (R-1a-2: never reuse)
 *     on RecordTimeRefused: the same, a fresh attempt with a fresh clock reading (D-209; PBDIGIT-343 G-1d)
 *     on ValidTimeNotStorable: refuse at once; the valid times come from the command (R-1a-6)
 *   attempts exhausted: refused with the last attempt's reason (ConcurrentModification or RecordTimeUnavailable)
 */
final class DecideAndAppend
{
    public const MAX_ATTEMPTS = 3;

    public function __construct(
        private readonly MemberRepositoryPort $repository,
        private readonly AuthorityCheckPort $authority,
    ) {
    }

    /**
     * Per attempt (PBDIGIT-354 S2-3a, D-238): find (read-only) → request → authorise → create if admitting → decide.
     * - The request names the subject, so the member is looked up first, READ-ONLY: nothing is created before the
     *   answer (a refused admission leaves no Member record).
     * - A refusal at the command boundary (`$request` returns a RefusalReason) comes before any authority call.
     * - Not authorised → NotAuthorised; Undetermined → AuthorityUndetermined (≠ No). Both come BEFORE "member not
     *   found", so an unauthorised actor cannot probe whether a member exists.
     * - An `ownAct` answer counts only for record-resignation with actor = subject (D-203); otherwise refused.
     *
     * @param \Closure(?Member): (AuthorityRequest|RefusalReason) $request what to ask, from the member found (or null)
     * @param \Closure(): ?Member $find                                       read-only lookup, fresh for each attempt
     * @param \Closure(Member, AuthorityEvidence): Decision $decide           the pure domain decision
     * @param (\Closure(): (Member|ApplicationRefusal))|null $create          admission only: create the Member when none
     *                                                                        was found (after the authorisation)
     */
    public function run(TenantId $tenant, IdempotencyKey $key, string $fingerprint, \Closure $request, \Closure $find, \Closure $decide, ?\Closure $create = null): CommandOutcome
    {
        $seen = $this->answerFromKey($tenant, $key, $fingerprint);
        if ($seen !== null) {
            return $seen;
        }

        $exhausted = ApplicationRefusal::ConcurrentModification;
        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            $member = $find();
            $asked = $request($member);
            if ($asked instanceof RefusalReason) {
                return CommandOutcome::refusedByDomain($asked);
            }
            $authorisation = $this->authority->check($tenant, $asked);
            $refusal = self::authorisationRefusal($asked, $authorisation);
            if ($refusal !== null) {
                return CommandOutcome::refused($refusal);
            }
            if ($member === null) {
                $member = $create === null ? ApplicationRefusal::MemberNotFound : $create();
            }
            if ($member instanceof ApplicationRefusal) {
                return CommandOutcome::refused($member);
            }
            $decision = $decide($member, $authorisation->evidence());
            if (!$decision->isAccepted()) {
                // IDEM-1 / IDEM-3: the same command may have been applied after the pre-check (the refusal
                // is then caused by its own fact). The key row commits atomically with that fact.
                return $this->answerFromKey($tenant, $key, $fingerprint)
                    ?? CommandOutcome::refusedByDomain($decision->refusal());
            }
            $result = $this->repository->append($tenant, $member->id(), $decision, $key, $fingerprint);
            // Exhaustive, no default: an unmapped outcome fails loudly (UnhandledMatchError) instead of
            // being retried. Only a concurrency conflict or a record-time refusal is re-decided (null).
            $outcome = match ($result->outcome()) {
                AppendOutcome::Appended, AppendOutcome::AlreadyApplied => CommandOutcome::ofAppended($result->appended()),
                AppendOutcome::IdempotencyKeyConflict => CommandOutcome::refused(ApplicationRefusal::IdempotencyKeyConflict),
                AppendOutcome::MemberNotFound => CommandOutcome::refused(ApplicationRefusal::MemberNotFound),
                AppendOutcome::ValidTimeNotStorable => CommandOutcome::refused(ApplicationRefusal::ValidTimeNotStorable),
                AppendOutcome::RefusedByDomain => CommandOutcome::refusedByDomain($result->refusal()
                    ?? throw new \LogicException('A domain refusal at record time names its reason.')),   // D-240
                AppendOutcome::ConcurrencyConflict, AppendOutcome::RecordTimeRefused => null,
            };
            if ($outcome !== null) {
                return $outcome;
            }
            $exhausted = $result->outcome() === AppendOutcome::RecordTimeRefused
                ? ApplicationRefusal::RecordTimeUnavailable
                : ApplicationRefusal::ConcurrentModification;
        }

        return CommandOutcome::refused($exhausted);
    }

    /**
     * The one reading of an authority answer (D-238), shared with the declaration use cases (S2-4b): null = may act;
     * not authorised and undetermined are distinct refusals (≠ No); `ownAct` is checked (D-203).
     */
    public static function authorisationRefusal(AuthorityRequest $asked, AuthorityDecision $authorisation): ?ApplicationRefusal
    {
        return match ($authorisation->outcome()) {
            AuthorityOutcome::Authorised => self::ownActMisapplied($asked, $authorisation) ? ApplicationRefusal::OwnActNotApplicable : null,
            AuthorityOutcome::NotAuthorised => ApplicationRefusal::NotAuthorised,
            AuthorityOutcome::Undetermined => ApplicationRefusal::AuthorityUndetermined,
        };
    }

    /** D-203: `ownAct` is the member's own right to resign; for any other act, or another actor, it authorises nothing. */
    private static function ownActMisapplied(AuthorityRequest $request, AuthorityDecision $authorisation): bool
    {
        if ($authorisation->evidence()->basis()?->competenceKind() !== CompetenceKind::OwnAct) {
            return false;
        }

        return $request->act !== MembershipAct::RecordResignation
            || $request->subject === null
            || $request->subject->value() !== $request->actor;
    }

    /** The original outcome of an already-applied key, or null if the key is unused. */
    private function answerFromKey(TenantId $tenant, IdempotencyKey $key, string $fingerprint): ?CommandOutcome
    {
        $seen = $this->repository->appliedCommand($tenant, $key);
        if ($seen === null) {
            return null;
        }

        return $seen->fingerprint() === $fingerprint
            ? CommandOutcome::ofAppended($seen->appended())
            : CommandOutcome::refused(ApplicationRefusal::IdempotencyKeyConflict);
    }
}
