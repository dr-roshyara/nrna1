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
use App\Contexts\Membership\Domain\Organisational\PersonRef;
use App\Contexts\Membership\Domain\Organisational\Provenance;
use App\Contexts\Membership\Domain\Organisational\RefusalReason;
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
            // D-163, D-177 §3 (S2-1): a release-1 admission states its term, finite or open-ended; no platform
            // default. Refused before anyone is asked and before any record exists. (The kernel keeps "no term" for
            // attested history only, which never comes through this command.) The subject is the person admitted.
            static fn (?Member $existing): AuthorityRequest|RefusalReason => $until === null
                ? RefusalReason::AdmissionTermRequired
                : new AuthorityRequest(MembershipAct::Admit, $command->actor, $person, null, TimePoint::of($command->decisionDate), null, $command->idempotencyKey),
            fn (): ?Member => $this->repository->findByHolder($tenant, $person),
            static function (Member $member, AuthorityEvidence $evidence) use ($command, $until): Decision {
                return $member->decideAdmit(
                    FactId::fromString($command->factId),
                    TimePoint::of($command->effectiveAt),
                    TimePoint::of($command->decisionDate),
                    $until,
                    Provenance::decision($command->actor, $evidence, $command->declarationRef),
                    $command->decidedAt === null ? null : Instant::of($command->decidedAt),
                );
            },
            // only after the authorisation, and only when the person has no Member yet
            fn (): Member|ApplicationRefusal => $this->created($tenant, $person, MemberId::fromString($command->memberIdIfNew)),
        );
    }

    /** INV-M: creation is atomic per (tenant, person); a lost race continues with the winner's Member. */
    private function created(TenantId $tenant, PersonRef $person, MemberId $idIfNew): Member|ApplicationRefusal
    {
        $created = $this->repository->create($tenant, $idIfNew, $person);
        if ($created->isCreated()) {
            return $created->member();
        }

        return $this->repository->load($tenant, $created->existingMemberId()) ?? ApplicationRefusal::MemberNotFound;
    }
}
