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
 * Correct a membership record (PBDIGIT-354 S2-5a; D-206; D-208 §2; GC-14), through DecideAndAppend (D-238 order).
 * - Boundary: evidence is required, refused before anyone is asked (CorrectionEvidenceRequired).
 * - Asks `correct-membership-record` with (fact ref, corrected value, evidence ref); the subject is the member.
 * - The kernel decides (`Member::decideCorrect`, C-2, unchanged): which facts are correctable and the dating rules.
 * - Provenance: the recorder, the authority answer, and the evidence reference ("recorded from evidence", D-148).
 * - D-240: a Correct that moves an End onto or after a pending scheduled resignation's date is refused at the append.
 */
final class CorrectMembershipRecordHandler
{
    private DecideAndAppend $loop;

    public function __construct(private readonly MemberRepositoryPort $repository, AuthorityCheckPort $authority)
    {
        $this->loop = new DecideAndAppend($repository, $authority);
    }

    public function handle(CorrectMembershipRecord $command): CommandOutcome
    {
        $tenant = TenantId::fromString($command->tenantId);
        $memberId = MemberId::fromString($command->memberId);
        // S2-5b (D-237): the value is a date or open-ended, exactly one; otherwise refused at the boundary.
        $stated = ($command->correctedDate !== null) !== $command->openEnded;
        $value = $command->openEnded ? TimePoint::infinity() : TimePoint::of($command->correctedDate ?? 0);
        $on = TimePoint::of($command->decisionDate);
        $evidence = $command->evidenceRef === null ? '' : trim($command->evidenceRef);

        return $this->loop->run(
            $tenant,
            IdempotencyKey::fromString($command->idempotencyKey),
            $command->fingerprint(),
            static fn (?Member $member): AuthorityRequest|RefusalReason => match (true) {
                !$stated => RefusalReason::CorrectedValueRequired,
                $evidence === '' => RefusalReason::CorrectionEvidenceRequired,
                default => new AuthorityRequest(MembershipAct::CorrectMembershipRecord, $command->actor, $member?->holder(), null, $on, null, $command->idempotencyKey,
                    $evidence, null, $command->targetFactId, $value),
            },
            fn (): ?Member => $this->repository->load($tenant, $memberId),
            static fn (Member $member, AuthorityEvidence $authority): Decision => $member->decideCorrect(FactId::fromString($command->factId),
                FactId::fromString($command->targetFactId), $value, $on, Provenance::decision($command->actor, $authority, $evidence),
                $command->decidedAt === null ? null : Instant::of($command->decidedAt)),
        );
    }
}
