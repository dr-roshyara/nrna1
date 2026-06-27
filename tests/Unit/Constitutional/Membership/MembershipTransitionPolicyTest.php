<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership;

use App\Contexts\Membership\Domain\Membership\MembershipTransitionPolicy;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Membership\Domain\Membership\ValueObjects\TransitionReason;
use App\Contexts\Shared\Domain\ValueObjects\ActorId;
use App\Contexts\Membership\Domain\Membership\Exceptions\InvalidMembershipConstructionException;
use PHPUnit\Framework\TestCase;

final class MembershipTransitionPolicyTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function it_allows_active_to_suspended(): void
    {
        $allowed = MembershipTransitionPolicy::canTransition(
            MembershipStatus::ACTIVE,
            MembershipStatus::SUSPENDED
        );
        $this->assertTrue($allowed);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_allows_active_to_terminated(): void
    {
        $allowed = MembershipTransitionPolicy::canTransition(
            MembershipStatus::ACTIVE,
            MembershipStatus::TERMINATED
        );
        $this->assertTrue($allowed);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_allows_suspended_to_active(): void
    {
        $allowed = MembershipTransitionPolicy::canTransition(
            MembershipStatus::SUSPENDED,
            MembershipStatus::ACTIVE
        );
        $this->assertTrue($allowed);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_allows_suspended_to_terminated(): void
    {
        $allowed = MembershipTransitionPolicy::canTransition(
            MembershipStatus::SUSPENDED,
            MembershipStatus::TERMINATED
        );
        $this->assertTrue($allowed);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_blocks_terminated_to_active(): void
    {
        $allowed = MembershipTransitionPolicy::canTransition(
            MembershipStatus::TERMINATED,
            MembershipStatus::ACTIVE
        );
        $this->assertFalse($allowed);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_blocks_terminated_to_suspended(): void
    {
        $allowed = MembershipTransitionPolicy::canTransition(
            MembershipStatus::TERMINATED,
            MembershipStatus::SUSPENDED
        );
        $this->assertFalse($allowed);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_blocks_active_to_active(): void
    {
        $allowed = MembershipTransitionPolicy::canTransition(
            MembershipStatus::ACTIVE,
            MembershipStatus::ACTIVE
        );
        $this->assertFalse($allowed);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_rejects_suspended_without_actor_id(): void
    {
        $this->expectException(InvalidMembershipConstructionException::class);
        MembershipTransitionPolicy::assertAuditRequirementsMet(
            MembershipStatus::SUSPENDED,
            null,
            TransitionReason::fromString('test'),
            new \DateTimeImmutable()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_rejects_suspended_without_reason(): void
    {
        $this->expectException(InvalidMembershipConstructionException::class);
        MembershipTransitionPolicy::assertAuditRequirementsMet(
            MembershipStatus::SUSPENDED,
            ActorId::fromString('actor-001'),
            null,
            new \DateTimeImmutable()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_rejects_suspended_without_timestamp(): void
    {
        $this->expectException(InvalidMembershipConstructionException::class);
        MembershipTransitionPolicy::assertAuditRequirementsMet(
            MembershipStatus::SUSPENDED,
            ActorId::fromString('actor-001'),
            TransitionReason::fromString('test'),
            null
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_rejects_terminated_without_actor_id(): void
    {
        $this->expectException(InvalidMembershipConstructionException::class);
        MembershipTransitionPolicy::assertAuditRequirementsMet(
            MembershipStatus::TERMINATED,
            null,
            TransitionReason::fromString('test'),
            new \DateTimeImmutable()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_rejects_terminated_without_reason(): void
    {
        $this->expectException(InvalidMembershipConstructionException::class);
        MembershipTransitionPolicy::assertAuditRequirementsMet(
            MembershipStatus::TERMINATED,
            ActorId::fromString('actor-001'),
            null,
            new \DateTimeImmutable()
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_rejects_terminated_without_timestamp(): void
    {
        $this->expectException(InvalidMembershipConstructionException::class);
        MembershipTransitionPolicy::assertAuditRequirementsMet(
            MembershipStatus::TERMINATED,
            ActorId::fromString('actor-001'),
            TransitionReason::fromString('test'),
            null
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function it_accepts_active_with_no_audit_fields(): void
    {
        MembershipTransitionPolicy::assertAuditRequirementsMet(
            MembershipStatus::ACTIVE,
            null,
            null,
            null
        );
        $this->assertTrue(true); // If we got here without exception, test passed
    }
}
