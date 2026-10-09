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
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\TimePoint;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Admits a person: uses the person's existing Member (re-entry), or creates one.
 * Creation is atomic per (tenant, person); a lost creation race continues with
 * the winner's Member (INV-M).
 */
final class AdmitMemberHandler
{
    private DecideAndAppend $loop;

    public function __construct(private readonly MemberRepositoryPort $repository, AuthorityCheckPort $authority)
    {
        $this->loop = new DecideAndAppend($repository, $authority);
    }

    public function handle(AdmitMember $command): CommandOutcome
    {
        $tenant = TenantId::fromString($command->tenantId);
        $person = PersonRef::fromString($command->personRef);
        $until = $command->initialTermUnlimited
            ? TimePoint::infinity()
            : ($command->initialTermUntil === null ? null : TimePoint::of($command->initialTermUntil));

        return $this->loop->run(
            $tenant,
            IdempotencyKey::fromString($command->idempotencyKey),
            $command->fingerprint(),
            $command->actor,
            MembershipAct::Admit,
            fn (): Member|ApplicationRefusal => $this->memberOf($tenant, $person, MemberId::fromString($command->memberIdIfNew)),
            static fn (Member $member, AuthorityEvidence $evidence) => $member->decideAdmit(
                FactId::fromString($command->factId),
                TimePoint::of($command->effectiveAt),
                $until,
                Provenance::decision($command->actor, $evidence, $command->declarationRef),
                $command->decidedAt === null ? null : Instant::of($command->decidedAt),
            ),
        );
    }

    private function memberOf(TenantId $tenant, PersonRef $person, MemberId $idIfNew): Member|ApplicationRefusal
    {
        $existing = $this->repository->findByHolder($tenant, $person);
        if ($existing !== null) {
            return $existing;
        }
        $created = $this->repository->create($tenant, $idIfNew, $person);
        if ($created->isCreated()) {
            return $created->member();
        }

        return $this->repository->load($tenant, $created->existingMemberId()) ?? ApplicationRefusal::MemberNotFound;
    }
}
