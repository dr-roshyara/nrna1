<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational;

use App\Contexts\Membership\Application\Organisational\Ports\AuthorityCheckPort;
use App\Contexts\Membership\Application\Organisational\Ports\MemberRepositoryPort;
use App\Contexts\Membership\Application\Organisational\Ports\MembershipAct;
use App\Contexts\Membership\Domain\Organisational\AuthorityEvidence;
use App\Contexts\Membership\Domain\Organisational\FactId;
use App\Contexts\Membership\Domain\Organisational\Instant;
use App\Contexts\Membership\Domain\Organisational\Member;
use App\Contexts\Membership\Domain\Organisational\MemberId;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Suspends a member's rights (PBDIGIT-354 S2-2; D-163, D-177 §4, §8; the pull shape: an office holder's act).
 * Authority comes from AuthorityCheckPort for MembershipAct::Suspend (Governance's `suspend-membership`), asked again
 * on every attempt; the returned evidence is recorded on the fact. Nothing is taken from the caller.
 */
final class SuspendMembershipHandler
{
    private DecideAndAppend $loop;

    public function __construct(private readonly MemberRepositoryPort $repository, AuthorityCheckPort $authority)
    {
        $this->loop = new DecideAndAppend($repository, $authority);
    }

    public function handle(SuspendMembership $command): CommandOutcome
    {
        $tenant = TenantId::fromString($command->tenantId);
        $memberId = MemberId::fromString($command->memberId);

        return $this->loop->run(
            $tenant,
            IdempotencyKey::fromString($command->idempotencyKey),
            $command->fingerprint(),
            $command->actor,
            MembershipAct::Suspend,
            fn (): Member|ApplicationRefusal => $this->repository->load($tenant, $memberId) ?? ApplicationRefusal::MemberNotFound,
            static fn (Member $member, AuthorityEvidence $evidence) => $member->decideSuspend(
                FactId::fromString($command->factId),
                TimePoint::of($command->effectiveAt),
                TimePoint::of($command->decisionDate),
                $command->until === null ? TimePoint::infinity() : TimePoint::of($command->until),
                Provenance::decision($command->actor, $evidence),
                $command->decidedAt === null ? null : Instant::of($command->decidedAt),
            ),
        );
    }
}
