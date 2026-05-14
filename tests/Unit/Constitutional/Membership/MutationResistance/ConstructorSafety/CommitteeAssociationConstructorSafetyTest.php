<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership\MutationResistance\ConstructorSafety;

use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\Exceptions\InvalidMembershipConstructionException;
use App\Contexts\Membership\Domain\Membership\ValueObjects\AssociationId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use PHPUnit\Framework\TestCase;

final class CommitteeAssociationConstructorSafetyTest extends TestCase
{
    /** @test */
    public function it_rejects_construction_of_suspended_without_actor_id(): void
    {
        // ATTACK VECTOR: Create SUSPENDED status without actorId
        // THREAT: Audit trail becomes incomplete - cannot identify who suspended
        // CURRENT: Constructor allows null actorId on SUSPENDED (NO VALIDATION)
        // CONSTITUTIONAL GUARANTEE: "SUSPENDED status requires institutional actor identification"

        $this->expectException(InvalidMembershipConstructionException::class);
        $this->expectExceptionMessage('SUSPENDED status requires actorId');

        new CommitteeAssociation(
            associationId: AssociationId::generate(),
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable(),
            status: MembershipStatus::SUSPENDED,
            actorId: null,  // ← ATTACK: Missing required audit field
            transitionReason: 'Disciplinary action',
            transitionedAt: new \DateTimeImmutable(),
        );
    }

    /** @test */
    public function it_rejects_construction_of_terminated_without_termination_reason(): void
    {
        // ATTACK VECTOR: Create TERMINATED status without transitionReason
        // THREAT: Governance loses justification - invalid terminations cannot be detected
        // CURRENT: Constructor allows null transitionReason on TERMINATED (NO VALIDATION)
        // CONSTITUTIONAL GUARANTEE: "TERMINATED status requires institutional justification"

        $this->expectException(InvalidMembershipConstructionException::class);
        $this->expectExceptionMessage('TERMINATED status requires transitionReason');

        new CommitteeAssociation(
            associationId: AssociationId::generate(),
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable(),
            status: MembershipStatus::TERMINATED,
            actorId: 'actor-uuid-001',
            transitionReason: null,  // ← ATTACK: Missing justification
            transitionedAt: new \DateTimeImmutable(),
        );
    }

    /** @test */
    public function it_prevents_direct_instantiation_of_invalid_status_combinations(): void
    {
        // ATTACK VECTOR: Call constructor directly instead of transition methods
        // THREAT: Bypass factory methods and guards that validate state machine rules
        // CURRENT: Constructor is public and accepts any status/metadata combination
        // CONSTITUTIONAL GUARANTEE: "SUSPENDED status must have complete audit context"

        $this->expectException(InvalidMembershipConstructionException::class);
        $this->expectExceptionMessage('SUSPENDED status requires transitionReason');

        // SUSPENDED status without all required audit fields
        new CommitteeAssociation(
            associationId: AssociationId::generate(),
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable(),
            status: MembershipStatus::SUSPENDED,
            actorId: 'actor-uuid-001',  // ← actorId IS provided
            transitionReason: null,     // ← ATTACK: reason missing
            transitionedAt: new \DateTimeImmutable(),
        );
    }

    /** @test */
    public function it_rejects_active_status_with_unexpected_audit_fields(): void
    {
        // ATTACK VECTOR: Create TERMINATED with missing actorId
        // THREAT: Governance audit loses accountability - no actor record
        // CURRENT: Constructor allows null actorId on TERMINATED
        // CONSTITUTIONAL GUARANTEE: "TERMINATED status requires institutional actor"

        $this->expectException(InvalidMembershipConstructionException::class);
        $this->expectExceptionMessage('TERMINATED status requires actorId');

        new CommitteeAssociation(
            associationId: AssociationId::generate(),
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable(),
            status: MembershipStatus::TERMINATED,
            actorId: null,  // ← ATTACK: Missing actor
            transitionReason: 'Some reason',
            transitionedAt: new \DateTimeImmutable(),
        );
    }

    /** @test */
    public function it_rejects_transition_without_timestamp_when_status_changes(): void
    {
        // ATTACK VECTOR: Create SUSPENDED without transitionedAt timestamp
        // THREAT: Temporal truth becomes unreliable - cannot order transitions chronologically
        // CURRENT: Constructor allows null transitionedAt even when status changed
        // CONSTITUTIONAL GUARANTEE: "All status transitions must be timestamped"

        $this->expectException(InvalidMembershipConstructionException::class);
        $this->expectExceptionMessage('transitionedAt is required when status changes');

        new CommitteeAssociation(
            associationId: AssociationId::generate(),
            memberId: MemberId::generate(),
            committeeId: CommitteeId::generate(),
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable(),
            status: MembershipStatus::SUSPENDED,
            actorId: 'actor-uuid-001',
            transitionReason: 'Disciplinary action',
            transitionedAt: null,  // ← ATTACK: Missing required timestamp
        );
    }
}
