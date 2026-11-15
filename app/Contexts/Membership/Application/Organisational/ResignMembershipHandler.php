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
 * Resign now (PBDIGIT-354 S2-3b; D-194, D-201, D-203; D-208 §2 `record-resignation`, GC-1, GC-2).
 * - Asks `record-resignation` for the member concerned (subject), the current period at the declaration date, and the
 *   declared effective date (= the declaration date). Self-service is Governance's `ownAct` answer; Membership accepts
 *   that only when the actor IS the member (DecideAndAppend, S2-3a). A recorder needs their own authority.
 * - Records `End(resignation)` effective on the declaration date.
 */
final class ResignMembershipHandler
{
    private DecideAndAppend $loop;

    public function __construct(private readonly MemberRepositoryPort $repository, AuthorityCheckPort $authority)
    {
        $this->loop = new DecideAndAppend($repository, $authority);
    }

    public function handle(ResignMembership $command): CommandOutcome
    {
        $tenant = TenantId::fromString($command->tenantId);
        $memberId = MemberId::fromString($command->memberId);
        $on = TimePoint::of($command->declarationDate);

        return $this->loop->run(
            $tenant,
            IdempotencyKey::fromString($command->idempotencyKey),
            $command->fingerprint(),
            static fn (?Member $member): AuthorityRequest => new AuthorityRequest(MembershipAct::RecordResignation, $command->actor, $member?->holder(),
                $member?->resolve()->currentPeriod($on), $on, $on, $command->idempotencyKey),
            fn (): ?Member => $this->repository->load($tenant, $memberId),
            static function (Member $member, AuthorityEvidence $evidence) use ($command, $on): Decision {
                $period = $member->resolve()->currentPeriod($on);
                if ($period === null) {
                    return Decision::refused(RefusalReason::PeriodNotFound);   // before admission, or already ended
                }

                return $member->decideEnd(FactId::fromString($command->factId), $period, $on, $on, EndKind::fromString(EndKind::RESIGNATION),
                    Provenance::decision($command->actor, $evidence), $command->decidedAt === null ? null : Instant::of($command->decidedAt));
            },
        );
    }
}
