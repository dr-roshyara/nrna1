<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership;

use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use Tests\Unit\Constitutional\PureDomainTestCase;

/**
 * Constitutional Specification: Accepting a Member into Committee Membership
 *
 * Describes the moment when a constitutional relationship is established between a member
 * and a governing body (committee), granting them governance rights and responsibilities.
 *
 * This is not data persistence.
 * This is institutional legitimacy.
 */
final class AcceptMemberTest extends PureDomainTestCase
{
    /**
     * Constitutional Specification: A member enters a constitutional relationship with a committee
     *
     * When an application is approved, the institution recognizes the member as a legitimate
     * participant in the committee's governance. This creates an immutable record that:
     *
     * - Establishes the member's legitimacy within the committee
     * - Creates a traceable institutional relationship
     * - Grants voting and governance rights
     * - Becomes part of the constitutional record
     *
     * This is not merely "adding a row to a database."
     * This is constitutional recognition.
     */
    public function test_committee_accepts_member_into_constitutional_relationship(): void
    {
        // ARRANGE: Pure domain objects (no database access)
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();

        // ACT: The institution recognizes the member's legitimate membership
        $relationship = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: new \DateTimeImmutable('2026-05-14 10:00:00'),
            status: MembershipStatus::ACTIVE,
        );

        // ASSERT: The constitutional relationship has been established
        $this->assertEquals(MembershipStatus::ACTIVE, $relationship->status);
        $this->assertEquals($member->value(), $relationship->memberId->value());
        $this->assertEquals($committee->value(), $relationship->committeeId->value());

        // ASSERT: The relationship has a permanent institutional identity
        $this->assertNotNull($relationship->associationId);
        $this->assertNotEmpty($relationship->associationId->value());

        // ASSERT: The relationship is timestamped for institutional record
        $this->assertInstanceOf(\DateTimeImmutable::class, $relationship->associatedAt);

        // IMPLIED EVENT: MembershipAccepted
        // (future: will be emitted to notify Elections context)
    }

    /**
     * Constitutional Specification: Each constitutional relationship is uniquely identifiable
     *
     * Every institutional relationship must have a permanent, immutable identity that:
     *
     * - Persists across the lifetime of the relationship
     * - Allows unambiguous reference in governance actions
     * - Enables audit reconstruction
     * - Distinguishes new relationships from re-established relationships
     */
    public function test_each_constitutional_relationship_has_permanent_identity(): void
    {
        // ARRANGE: Pure domain objects
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // ACT: Create two constitutional relationships
        $relationship1 = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
            status: MembershipStatus::ACTIVE,
        );

        $relationship2 = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
            status: MembershipStatus::ACTIVE,
        );

        // ASSERT: Each relationship has unique institutional identity
        $this->assertNotEquals(
            $relationship1->associationId->value(),
            $relationship2->associationId->value(),
            'Each constitutional relationship must have unique permanent identity'
        );
    }

    /**
     * Constitutional Specification: The relationship origin is recorded
     *
     * The institutional record must capture:
     *
     * - How the member became associated (residence, exception, manual)
     * - When the relationship began
     * - The governing authority that recognized it
     *
     * This enables governance reconstruction and legitimacy verification.
     */
    public function test_constitutional_relationship_records_its_origin(): void
    {
        // ARRANGE: Pure domain objects
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        // ACT: The institution establishes the relationship
        $relationship = CommitteeAssociation::create(
            memberId: $member,
            committeeId: $committee,
            associationType: ApplicationReason::RESIDENCE,
            associatedAt: $now,
            status: MembershipStatus::ACTIVE,
        );

        // ASSERT: The origin is permanently recorded
        $this->assertEquals(ApplicationReason::RESIDENCE, $relationship->associationType);
        $this->assertInstanceOf(\DateTimeImmutable::class, $relationship->associatedAt);

        // NOTE: Future phases will add actor information (who approved, timestamp of approval)
        // for full constitutional auditability
    }
}
