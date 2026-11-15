<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityRequest;
use App\Contexts\Membership\Application\Organisational\Ports\DeclarationWriteOutcome;
use App\Contexts\Membership\Application\Organisational\Ports\ScheduledResignationStorePort;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * The command loop for scheduled resignation declarations (PBDIGIT-354 S2-4b). The same order as DecideAndAppend
 * (D-238), writing to the declaration store instead of the ledger (D-204):
 *
 *   idempotency pre-check, repeated when the domain refuses
 *   repeat ≤ MAX_ATTEMPTS:  find (read-only) → request (or a boundary refusal, before anyone is asked) → authorise
 *     (DecideAndAppend::authorisationRefusal: not authorised / undetermined BEFORE "not found", no existence leak;
 *     ownAct only for the member) → member / declaration found? → decide (pure domain) → write
 *     on Conflict: discard the decision and start again from find (R-1a-2: never reuse)
 *   attempts exhausted: ConcurrentModification
 */
final class DecideAndRecordDeclaration
{
    public const MAX_ATTEMPTS = 3;

    public function __construct(
        private readonly ScheduledResignationStorePort $store,
        private readonly AuthorityCheckPort $authority,
    ) {
    }

    /**
     * @param \Closure(): array{0: ?Member, 1: ?ScheduledResignation} $find  read-only, fresh for each attempt
     * @param \Closure(?Member, ?ScheduledResignation): (AuthorityRequest|RefusalReason) $request
     * @param \Closure(Member, ?ScheduledResignation, AuthorityEvidence): (ScheduledResignation|RefusalReason|ApplicationRefusal) $decide
     * @param \Closure(Member, ScheduledResignation): DeclarationWriteOutcome $write
     */
    public function run(TenantId $tenant, IdempotencyKey $key, string $fingerprint, \Closure $find, \Closure $request, \Closure $decide, \Closure $write): DeclarationOutcome
    {
        $seen = $this->answerFromKey($tenant, $key, $fingerprint);
        if ($seen !== null) {
            return $seen;
        }

        for ($attempt = 1; $attempt <= self::MAX_ATTEMPTS; $attempt++) {
            [$member, $declaration] = $find();
            $asked = $request($member, $declaration);
            if ($asked instanceof RefusalReason) {
                return DeclarationOutcome::refusedByDomain($asked);
            }
            $authorisation = $this->authority->check($tenant, $asked);
            $refusal = DecideAndAppend::authorisationRefusal($asked, $authorisation);
            if ($refusal !== null) {
                return DeclarationOutcome::refused($refusal);
            }
            if ($member === null) {
                return DeclarationOutcome::refused(ApplicationRefusal::MemberNotFound);
            }
            $decided = $decide($member, $declaration, $authorisation->evidence());
            if ($decided instanceof ApplicationRefusal) {
                return DeclarationOutcome::refused($decided);
            }
            if ($decided instanceof RefusalReason) {
                // The same command may have been applied after the pre-check (the refusal is then caused by it).
                return $this->answerFromKey($tenant, $key, $fingerprint) ?? DeclarationOutcome::refusedByDomain($decided);
            }
            // Exhaustive, no default: an unmapped outcome fails loudly. Only a conflict is decided again (null).
            $outcome = match ($write($member, $decided)) {
                DeclarationWriteOutcome::Written => DeclarationOutcome::recorded($decided),
                DeclarationWriteOutcome::AlreadyApplied => $this->answerFromKey($tenant, $key, $fingerprint)
                    ?? throw new \LogicException('The store reported the key applied, but has no record of it.'),
                DeclarationWriteOutcome::IdempotencyKeyConflict => DeclarationOutcome::refused(ApplicationRefusal::IdempotencyKeyConflict),
                DeclarationWriteOutcome::MemberNotFound => DeclarationOutcome::refused(ApplicationRefusal::MemberNotFound),
                DeclarationWriteOutcome::Conflict => null,
            };
            if ($outcome !== null) {
                return $outcome;
            }
        }

        return DeclarationOutcome::refused(ApplicationRefusal::ConcurrentModification);
    }

    /** The declaration an already-applied key wrote (as stored now), or null if the key is unused. */
    private function answerFromKey(TenantId $tenant, IdempotencyKey $key, string $fingerprint): ?DeclarationOutcome
    {
        $seen = $this->store->appliedRequest($tenant, $key);
        if ($seen === null) {
            return null;
        }
        if ($seen->fingerprint !== $fingerprint) {
            return DeclarationOutcome::refused(ApplicationRefusal::IdempotencyKeyConflict);
        }

        return DeclarationOutcome::recorded($this->store->find($tenant, $seen->memberId, $seen->declarationId)
            ?? throw new \LogicException('An applied key names a declaration the store does not have.'));
    }
}
