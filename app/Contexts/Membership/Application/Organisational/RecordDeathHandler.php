<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\AuthorityRequest;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\Decision;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Record a death (PBDIGIT-354 S2-3b; D-208 §2 `record-death`, GC-4; M-TDD-01 §5.1).
 * - **No evidence reference → refused before anyone is asked** (DeathEvidenceRequired).
 * - Asks `record-death` with the evidence reference, evaluated for the recording date. `ownAct` never authorises it.
 * - Records `End(death)` effective on the date of death, not after the recording date (D-205 (3)). The evidence
 *   reference is kept on the fact as its provenance `declarationRef`: the document the record rests on (RA line 72).
 */
final class RecordDeathHandler
{
    private DecideAndAppend $loop;

    public function __construct(private readonly MemberRepositoryPort $repository, AuthorityCheckPort $authority)
    {
        $this->loop = new DecideAndAppend($repository, $authority);
    }

    public function handle(RecordDeath $command): CommandOutcome
    {
        $tenant = TenantId::fromString($command->tenantId);
        $memberId = MemberId::fromString($command->memberId);
        $died = TimePoint::of($command->dateOfDeath);
        $recorded = TimePoint::of($command->recordingDate);
        $evidence = $command->evidenceRef === null ? null : trim($command->evidenceRef);

        return $this->loop->run(
            $tenant,
            IdempotencyKey::fromString($command->idempotencyKey),
            $command->fingerprint(),
            static fn (?Member $member): AuthorityRequest|RefusalReason => $evidence === null || $evidence === ''
                ? RefusalReason::DeathEvidenceRequired
                : new AuthorityRequest(MembershipAct::RecordDeath, $command->actor, $member?->holder(), $member?->resolve()->currentPeriod($died),
                    $recorded, null, $command->idempotencyKey, $evidence),
            fn (): ?Member => $this->repository->load($tenant, $memberId),
            static function (Member $member, AuthorityEvidence $authority) use ($command, $died, $recorded, $evidence): Decision {
                $period = $member->resolve()->currentPeriod($died);
                if ($period === null) {
                    return Decision::refused(RefusalReason::PeriodNotFound);
                }

                return $member->decideEnd(FactId::fromString($command->factId), $period, $died, $recorded, EndKind::fromString(EndKind::DEATH),
                    Provenance::decision($command->actor, $authority, $evidence), $command->decidedAt === null ? null : Instant::of($command->decidedAt));
            },
        );
    }
}
