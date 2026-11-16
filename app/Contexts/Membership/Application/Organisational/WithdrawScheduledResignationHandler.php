<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityRequest;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Application\Organisational\Ports\ScheduledResignationStorePort;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\DeclarationId;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignation;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Withdraw a scheduled resignation (PBDIGIT-354 S2-4b; D-201, D-204; D-208 §2 `record-resignation`, withdraw shape;
 * GC-12; M-TDD-01 §5.1–§5.2).
 * - Asks `record-resignation` with the member (subject), the declaration's period and **the declaration's reference**
 *   (`declarationRef`), no effective date. A declaration that does not exist is asked about with no period and told
 *   only after authorisation (DeclarationNotFound), so an unauthorised actor learns nothing.
 * - Only while `scheduled` and before its date. The stated date is checked first; then the STORE decides again at
 *   ledger time with the organisation's own date (S2-4d, D-239), and writes by compare-and-set, so withdrawal and
 *   execution cannot both succeed (MI-2, proved on PostgreSQL with concurrent processes in S2-4d).
 */
final class WithdrawScheduledResignationHandler
{
    private DecideAndRecordDeclaration $loop;

    public function __construct(private readonly MemberRepositoryPort $members, private readonly ScheduledResignationStorePort $store, AuthorityCheckPort $authority)
    {
        $this->loop = new DecideAndRecordDeclaration($store, $authority);
    }

    public function handle(WithdrawScheduledResignation $command): DeclarationOutcome
    {
        $tenant = TenantId::fromString($command->tenantId);
        $memberId = MemberId::fromString($command->memberId);
        $declarationId = DeclarationId::fromString($command->declarationId);
        $on = TimePoint::of($command->withdrawalDate);
        $key = IdempotencyKey::fromString($command->idempotencyKey);

        return $this->loop->run(
            $tenant,
            $key,
            $command->fingerprint(),
            function () use ($tenant, $memberId, $declarationId): array {
                $member = $this->members->load($tenant, $memberId);

                return [$member, $member === null ? null : $this->store->find($tenant, $memberId, $declarationId)];
            },
            static fn (?Member $member, ?ScheduledResignation $declaration): AuthorityRequest => new AuthorityRequest(MembershipAct::RecordResignation, $command->actor,
                $member?->holder(), $declaration?->period(), $on, null, $command->idempotencyKey, null, $command->declarationId),
            static fn (Member $member, ?ScheduledResignation $declaration, AuthorityEvidence $evidence): ScheduledResignation|RefusalReason|ApplicationRefusal => $declaration === null
                ? ApplicationRefusal::DeclarationNotFound
                : $declaration->withdraw($on, Provenance::decision($command->actor, $evidence)),
            // S2-4d: the decision above is the early check on the stated date; the store decides again at ledger time,
            // with the organisation's own date under the ledger-clock lock (M-TDD-01 §5.2; D-239), and writes by
            // compare-and-set.
            fn (Member $member, ScheduledResignation $withdrawn) => $this->store->withdraw($tenant, $memberId, $declarationId, $on, $withdrawn->withdrawnBy()
                ?? throw new \LogicException('A withdrawal records who withdrew it.'), $key, $command->fingerprint()),
        );
    }
}
