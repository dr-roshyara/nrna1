<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityRequest;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Void a membership record (PBDIGIT-354 S2-5a; D-206; D-208 §2; GC-14), through DecideAndAppend (D-238 order).
 * - Boundary: evidence is required, refused before anyone is asked (CorrectionEvidenceRequired).
 * - Asks `void-membership-record` with (fact ref, evidence ref); the subject is the member.
 * - The kernel decides (`Member::decideVoid`, C-1, unchanged).
 * - Provenance: the recorder, the authority answer and the evidence reference: the record is "recorded from evidence"
 *   and challengeable (D-148, D-159). Its effect reaches the organisation through C1. A dedicated report: Q-8.
 */
final class VoidMembershipRecordHandler
{
    private DecideAndAppend $loop;

    public function __construct(private readonly MemberRepositoryPort $repository, AuthorityCheckPort $authority)
    {
        $this->loop = new DecideAndAppend($repository, $authority);
    }

    public function handle(VoidMembershipRecord $command): CommandOutcome
    {
        $tenant = TenantId::fromString($command->tenantId);
        $memberId = MemberId::fromString($command->memberId);
        $on = TimePoint::of($command->decisionDate);
        $evidence = $command->evidenceRef === null ? '' : trim($command->evidenceRef);

        return $this->loop->run(
            $tenant,
            IdempotencyKey::fromString($command->idempotencyKey),
            $command->fingerprint(),
            static fn (?Member $member): AuthorityRequest|RefusalReason => $evidence === ''
                ? RefusalReason::CorrectionEvidenceRequired
                : new AuthorityRequest(MembershipAct::VoidMembershipRecord, $command->actor, $member?->holder(), null, $on, null, $command->idempotencyKey,
                    $evidence, null, $command->targetFactId),
            fn (): ?Member => $this->repository->load($tenant, $memberId),
            static fn (Member $member, AuthorityEvidence $authority): Decision => $member->decideVoid(FactId::fromString($command->factId),
                FactId::fromString($command->targetFactId), Provenance::decision($command->actor, $authority, $evidence),
                $command->decidedAt === null ? null : Instant::of($command->decidedAt)),
        );
    }
}
