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
 * Renews a term of an existing Member (PBDIGIT-354 S2-1; D-163, D-177 §4, §8; the pull shape: an office holder's
 * act). Authority comes from AuthorityCheckPort for MembershipAct::Renew (Governance's `renew-membership`), asked
 * again on every attempt; the returned evidence is recorded on the fact. Nothing is taken from the caller.
 */
final class RenewMembershipHandler
{
    private DecideAndAppend $loop;

    public function __construct(private readonly MemberRepositoryPort $repository, AuthorityCheckPort $authority)
    {
        $this->loop = new DecideAndAppend($repository, $authority);
    }

    public function handle(RenewMembership $command): CommandOutcome
    {
        $tenant = TenantId::fromString($command->tenantId);
        $memberId = MemberId::fromString($command->memberId);

        return $this->loop->run(
            $tenant,
            IdempotencyKey::fromString($command->idempotencyKey),
            $command->fingerprint(),
            // The term must be stated: an end date, or open-ended; a missing one is never read as unlimited. Refused
            // before anyone is asked. The subject is the member found (null when unknown: nothing is revealed).
            static fn (?Member $member): AuthorityRequest|RefusalReason => !$command->unlimited && $command->until === null
                ? RefusalReason::RenewalTermRequired
                : new AuthorityRequest(MembershipAct::Renew, $command->actor, $member?->holder(), null, TimePoint::of($command->decisionDate), null, $command->idempotencyKey),
            fn (): ?Member => $this->repository->load($tenant, $memberId),
            static function (Member $member, AuthorityEvidence $evidence) use ($command): Decision {
                return $member->decideRenew(
                    FactId::fromString($command->factId),
                    TimePoint::of($command->effectiveAt),
                    TimePoint::of($command->decisionDate),
                    $command->unlimited ? TimePoint::infinity() : TimePoint::of($command->until),
                    Provenance::decision($command->actor, $evidence),
                    $command->decidedAt === null ? null : Instant::of($command->decidedAt),
                );
            },
        );
    }
}
