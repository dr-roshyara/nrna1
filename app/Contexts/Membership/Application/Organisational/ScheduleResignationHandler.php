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
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\ScheduledResignation;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Schedule a resignation from a later date (PBDIGIT-354 S2-4b; D-201, D-204; D-208 §2 `record-resignation`, resign
 * shape; GC-1, GC-2; M-TDD-01 §5.1).
 * - **Boundary:** the effective date must be after the declaration date, refused before anyone is asked (the same day
 *   is resign now, S2-3b).
 * - Asks `record-resignation` for the member (subject), their current period at the declaration date and the declared
 *   effective date. The answer is kept with the declaration: its execution uses it (M-TDD-01 §5.1).
 * - Records a `scheduled` declaration; **no ledger fact** (the membership is unchanged until executed, S2-4c).
 */
final class ScheduleResignationHandler
{
    private DecideAndRecordDeclaration $loop;

    public function __construct(private readonly MemberRepositoryPort $members, private readonly ScheduledResignationStorePort $store, AuthorityCheckPort $authority)
    {
        $this->loop = new DecideAndRecordDeclaration($store, $authority);
    }

    public function handle(ScheduleResignation $command): DeclarationOutcome
    {
        $tenant = TenantId::fromString($command->tenantId);
        $memberId = MemberId::fromString($command->memberId);
        $on = TimePoint::of($command->declarationDate);
        $from = TimePoint::of($command->effectiveDate);
        $key = IdempotencyKey::fromString($command->idempotencyKey);

        return $this->loop->run(
            $tenant,
            $key,
            $command->fingerprint(),
            fn (): array => [$this->members->load($tenant, $memberId), null],
            static fn (?Member $member): AuthorityRequest|RefusalReason => $on->isBefore($from)
                ? new AuthorityRequest(MembershipAct::RecordResignation, $command->actor, $member?->holder(), $member?->resolve()->currentPeriod($on), $on, $from, $command->idempotencyKey)
                : RefusalReason::ScheduledDateNotAfterDeclaration,
            fn (Member $member, ?ScheduledResignation $none, AuthorityEvidence $evidence): ScheduledResignation|RefusalReason => ScheduledResignation::schedule(
                DeclarationId::fromString($command->declarationId), $member->resolve(), $this->store->forMember($tenant, $memberId), $on, $from,
                Provenance::decision($command->actor, $evidence), $command->decidedAt === null ? null : Instant::of($command->decidedAt)),
            fn (Member $member, ScheduledResignation $scheduled) => $this->store->record($tenant, $memberId, $member->version(), $scheduled, $key, $command->fingerprint()),
        );
    }
}
