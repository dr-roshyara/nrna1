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
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/** Ends a membership period of an existing Member in the given tenant. */
final class EndMembershipHandler
{
    private DecideAndAppend $loop;

    public function __construct(private readonly MemberRepositoryPort $repository, AuthorityCheckPort $authority)
    {
        $this->loop = new DecideAndAppend($repository, $authority);
    }

    public function handle(EndMembership $command): CommandOutcome
    {
        $tenant = TenantId::fromString($command->tenantId);
        $memberId = MemberId::fromString($command->memberId);

        $kind = EndKind::tryFromString($command->endKind);

        return $this->loop->run(
            $tenant,
            IdempotencyKey::fromString($command->idempotencyKey),
            $command->fingerprint(),
            // S2-3a (D-238; D-208 §2): the act asked depends on the kind. Refused at the boundary, before anyone is
            // asked: an unrecognised kind (D-3 = E1); an exclusion (pushed by Governance with its Resolution part,
            // C-1b, S2-6: no office exercises `exclude-member`); `legacy` (attested migration history only, never a
            // decision through this command: the same refusal the kernel gives).
            static fn (?Member $member): AuthorityRequest|RefusalReason => match (true) {
                $kind === null => RefusalReason::EndKindNotRecognised,
                $kind->value() === EndKind::EXCLUSION => RefusalReason::ExclusionIsPushOnly,
                $kind->value() === EndKind::LEGACY => RefusalReason::LegacyEndRequiresAttestedHistory,
                $kind->value() === EndKind::RESIGNATION => new AuthorityRequest(MembershipAct::RecordResignation, $command->actor, $member?->holder(),
                    PeriodId::fromString($command->periodId), TimePoint::of($command->decisionDate), TimePoint::of($command->effectiveAt), $command->idempotencyKey),
                $kind->value() === EndKind::DEATH => new AuthorityRequest(MembershipAct::RecordDeath, $command->actor, $member?->holder(),
                    PeriodId::fromString($command->periodId), TimePoint::of($command->decisionDate), null, $command->idempotencyKey),
            },
            fn (): ?Member => $this->repository->load($tenant, $memberId),
            static fn (Member $member, AuthorityEvidence $evidence): Decision => $member->decideEnd(
                FactId::fromString($command->factId),
                PeriodId::fromString($command->periodId),
                TimePoint::of($command->effectiveAt),
                TimePoint::of($command->decisionDate),
                $kind,
                Provenance::decision($command->actor, $evidence),
                $command->decidedAt === null ? null : Instant::of($command->decidedAt),
            ),
        );
    }
}
