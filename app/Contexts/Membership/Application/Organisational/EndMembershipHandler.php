<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\EndKind;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\PeriodId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
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

        return $this->loop->run(
            $tenant,
            IdempotencyKey::fromString($command->idempotencyKey),
            $command->fingerprint(),
            $command->actor,
            MembershipAct::End,
            fn (): Member|ApplicationRefusal => $this->repository->load($tenant, $memberId) ?? ApplicationRefusal::MemberNotFound,
            static fn (Member $member, AuthorityEvidence $evidence) => $member->decideEnd(
                FactId::fromString($command->factId),
                PeriodId::fromString($command->periodId),
                TimePoint::of($command->effectiveAt),
                EndKind::fromString($command->endKind),
                Provenance::decision($command->actor, $evidence),
                $command->decidedAt === null ? null : Instant::of($command->decidedAt),
            ),
        );
    }
}
