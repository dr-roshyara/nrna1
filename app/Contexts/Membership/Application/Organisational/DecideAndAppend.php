<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\AppendOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * The shared command loop of Slice 1a (contract §4):
 *
 *   idempotency pre-check (IDEM-1), repeated when the domain refuses (an identical command may
 *   have been applied meanwhile; found by the PostgreSQL race test, PBDIGIT-260)
 *   repeat ≤ MAX_ATTEMPTS:  load → authorise → decide (guards + G*) → append(expected version)
 *     on ConcurrencyConflict: discard the decision and start again from load (R-1a-2: never reuse)
 *     on ValidTimeNotStorable: refuse at once; the valid times come from the command (R-1a-6)
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
     * @param \Closure(): (Member|ApplicationRefusal) $load                fresh state for each attempt
     * @param \Closure(Member, AuthorityEvidence): Decision $decide          the pure domain decision
     */
    public function run(TenantId $tenant, IdempotencyKey $key, string $fingerprint, string $actor, MembershipAct $act, \Closure $load, \Closure $decide): CommandOutcome
    {
        $seen = $this->answerFromKey($tenant, $key, $fingerprint);
        if ($seen !== null) {
            return $seen;
        }

        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            $authorisation = $this->authority->check($tenant, $actor, $act);
            if (!$authorisation->isGranted()) {
                return CommandOutcome::refused(ApplicationRefusal::NotAuthorised);
            }
            $member = $load();
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
            // being retried into ConcurrentModification. Only a concurrency conflict is re-decided (null).
            $outcome = match ($result->outcome()) {
                AppendOutcome::Appended, AppendOutcome::AlreadyApplied => CommandOutcome::ofAppended($result->appended()),
                AppendOutcome::IdempotencyKeyConflict => CommandOutcome::refused(ApplicationRefusal::IdempotencyKeyConflict),
                AppendOutcome::MemberNotFound => CommandOutcome::refused(ApplicationRefusal::MemberNotFound),
                AppendOutcome::ValidTimeNotStorable => CommandOutcome::refused(ApplicationRefusal::ValidTimeNotStorable),
                AppendOutcome::ConcurrencyConflict => null,
            };
            if ($outcome !== null) {
                return $outcome;
            }
        }

        return CommandOutcome::refused(ApplicationRefusal::ConcurrentModification);
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
