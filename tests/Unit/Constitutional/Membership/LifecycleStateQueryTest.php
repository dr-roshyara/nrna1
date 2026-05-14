<?php

declare(strict_types=1);

namespace Tests\Unit\Constitutional\Membership;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Tests\Unit\Constitutional\PureDomainTestCase;

/**
 * Constitutional Specification: Lifecycle State Query (Current State Only)
 *
 * Verifies that MembershipLineage provides honest state-query methods that report
 * ONLY current membership status, without fake temporal semantics.
 *
 * CRITICAL: These methods do NOT accept time parameters. They report facts as-is.
 * If temporal reconstruction is needed in future, add stateAt() using existing episodes.
 */
final class LifecycleStateQueryTest extends PureDomainTestCase
{
    /**
     * Constitutional Specification: Active lineage reports ACTIVE status
     *
     * CRITICAL: isActive() returns true ONLY when currentStatus() is ACTIVE.
     * This fixes a bug: previous implementation returned !isTerminated(),
     * which made SUSPENDED members appear active.
     */
    public function test_active_lineage_is_active(): void
    {
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $this->assertTrue($lineage->isActive());
    }

    /**
     * Constitutional Specification: Active lineage is not suspended
     */
    public function test_active_lineage_is_not_suspended(): void
    {
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $this->assertFalse($lineage->isSuspended());
    }

    /**
     * Constitutional Specification: Active lineage is not terminated
     */
    public function test_active_lineage_is_not_terminated(): void
    {
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $this->assertFalse($lineage->isTerminated());
    }

    /**
     * Constitutional Specification: Established lineage exists
     *
     * CRITICAL: exists() returns true if lineage has any episodes.
     * Used to distinguish "no membership" from "terminated membership".
     */
    public function test_established_lineage_exists(): void
    {
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $this->assertTrue($lineage->exists());
    }

    /**
     * Constitutional Specification: Suspended lineage reports SUSPENDED status
     *
     * CRITICAL: isSuspended() returns true when currentStatus() is SUSPENDED.
     */
    public function test_suspended_lineage_is_suspended(): void
    {
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $lineage->suspend('actor-1', 'Suspension reason', $now->modify('+1 day'));

        $this->assertTrue($lineage->isSuspended());
    }

    /**
     * Constitutional Specification: Suspended lineage is not active
     *
     * CRITICAL: This verifies the bug fix. Suspended members must return isActive() = false.
     */
    public function test_suspended_lineage_is_not_active(): void
    {
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $lineage->suspend('actor-1', 'Suspension reason', $now->modify('+1 day'));

        // CRITICAL BUG FIX: This was true with old implementation (!isTerminated()),
        // now it correctly returns false
        $this->assertFalse($lineage->isActive());
    }

    /**
     * Constitutional Specification: Terminated lineage reports TERMINATED status
     *
     * CRITICAL: isTerminated() returns true when currentStatus() is TERMINATED.
     */
    public function test_terminated_lineage_is_terminated(): void
    {
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $lineage->terminate('actor-1', 'Termination reason', $now->modify('+1 day'));

        $this->assertTrue($lineage->isTerminated());
    }

    /**
     * Constitutional Specification: Restored lineage becomes active again
     *
     * CRITICAL: After SUSPENDED → ACTIVE transition, isActive() returns true again.
     */
    public function test_restored_lineage_is_active_again(): void
    {
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        $lineage->suspend('actor-1', 'Suspension', $now->modify('+1 day'));
        $lineage->restore('actor-1', $now->modify('+2 days'));

        $this->assertTrue($lineage->isActive());
        $this->assertFalse($lineage->isSuspended());
        $this->assertFalse($lineage->isTerminated());
    }

    /**
     * Constitutional Specification: Status queries are mutually exclusive
     *
     * CRITICAL: For any status, exactly ONE of the three state queries returns true.
     * Status is singular and deterministic.
     */
    public function test_status_mutual_exclusivity_exactly_one_true(): void
    {
        $lineageId = LineageId::generate();
        $member = $this->createMemberId();
        $committee = $this->createCommitteeId();
        $tenant = $this->createTenantId();
        $now = new \DateTimeImmutable('2026-05-14 10:00:00');

        $lineage = MembershipLineage::establish(
            lineageId: $lineageId,
            memberId: $member,
            committeeId: $committee,
            tenantId: $tenant,
            reason: ApplicationReason::RESIDENCE,
            at: $now,
        );

        // Count true results for ACTIVE status
        $activeCount = (int)$lineage->isActive()
            + (int)$lineage->isSuspended()
            + (int)$lineage->isTerminated();
        $this->assertEquals(1, $activeCount, 'ACTIVE: exactly one status is true');

        // Suspend
        $lineage->suspend('actor-1', 'Suspension', $now->modify('+1 day'));
        $suspendedCount = (int)$lineage->isActive()
            + (int)$lineage->isSuspended()
            + (int)$lineage->isTerminated();
        $this->assertEquals(1, $suspendedCount, 'SUSPENDED: exactly one status is true');

        // Terminate
        $lineage->terminate('actor-1', 'Termination', $now->modify('+2 days'));
        $terminatedCount = (int)$lineage->isActive()
            + (int)$lineage->isSuspended()
            + (int)$lineage->isTerminated();
        $this->assertEquals(1, $terminatedCount, 'TERMINATED: exactly one status is true');
    }
}
