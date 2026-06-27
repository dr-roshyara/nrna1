<?php

declare(strict_types=1);

namespace Tests\Feature\Governance\Projection;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

use App\Contexts\Governance\Domain\Committee\Committee;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Governance\Domain\Committee\Enums\CommitteeRole;
use App\Contexts\Governance\Domain\Committee\Events\MemberAssignedToCommittee;
use App\Contexts\Governance\Domain\Committee\Events\MemberRemovedFromCommittee;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Models\CommitteeMemberProjection;

/**
 * Phase 3A — Projection Layer Tests (TDD)
 *
 * Validates that events reliably transform into UI-ready projection state.
 * These tests verify CQRS correctness before any API or UI layer exists.
 */
final class CommitteeMemberProjectionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * INVARIANT: MemberAssignedToCommittee creates projection record
     */
    public function test_member_assignment_creates_projection_record(): void
    {
        $committeeId = CommitteeId::generate();
        $memberId = MemberId::generate();
        $tenantId = $this->createTenant();

        $event = new MemberAssignedToCommittee(
            $committeeId,
            $memberId,
            $tenantId,
            CommitteeRole::MEMBER
        );

        Event::dispatch($event);

        $this->assertDatabaseHas('committee_member_projection', [
            'committee_id' => $committeeId->value(),
            'member_id' => $memberId->value(),
            'tenant_id' => $tenantId->value(),
        ]);
    }

    /**
     * INVARIANT: Duplicate assignment is idempotent
     * Processing same event twice = one projection record
     */
    public function test_duplicate_event_processing_is_idempotent(): void
    {
        $committeeId = CommitteeId::generate();
        $memberId = MemberId::generate();
        $tenantId = $this->createTenant();

        $event = new MemberAssignedToCommittee(
            $committeeId,
            $memberId,
            $tenantId,
            CommitteeRole::MEMBER
        );

        // Process same event twice (simulates outbox retry)
        Event::dispatch($event);
        Event::dispatch($event);

        $count = CommitteeMemberProjection::where('committee_id', $committeeId->value())
            ->where('member_id', $memberId->value())
            ->count();

        $this->assertEquals(1, $count, 'Idempotency violated: multiple records created');
    }

    /**
     * INVARIANT: Tenant isolation is enforced
     * Tenant A assignment does NOT affect Tenant B's projections
     */
    public function test_tenant_isolation_is_enforced(): void
    {
        $tenantA = $this->createTenant();
        $tenantB = $this->createTenant();

        $committeeA = CommitteeId::generate();
        $committeeB = CommitteeId::generate();
        $memberId = MemberId::generate();

        // Assign same member to committees in different tenants
        Event::dispatch(new MemberAssignedToCommittee($committeeA, $memberId, $tenantA, CommitteeRole::MEMBER));
        Event::dispatch(new MemberAssignedToCommittee($committeeB, $memberId, $tenantB, CommitteeRole::MEMBER));

        // Tenant A should see only its assignment
        $tenantARecords = CommitteeMemberProjection::where('tenant_id', $tenantA->value())->count();
        $tenantBRecords = CommitteeMemberProjection::where('tenant_id', $tenantB->value())->count();

        $this->assertEquals(1, $tenantARecords, 'Tenant A isolation violated');
        $this->assertEquals(1, $tenantBRecords, 'Tenant B isolation violated');
    }

    /**
     * INVARIANT: Projection carries all UI-required data
     * No secondary DB lookups needed after projection creation
     */
    public function test_projection_contains_all_ui_required_fields(): void
    {
        $committeeId = CommitteeId::generate();
        $memberId = MemberId::generate();
        $tenantId = $this->createTenant();

        $event = new MemberAssignedToCommittee(
            $committeeId,
            $memberId,
            $tenantId,
            CommitteeRole::MEMBER
        );

        Event::dispatch($event);

        $projection = CommitteeMemberProjection::where('committee_id', $committeeId->value())
            ->where('member_id', $memberId->value())
            ->first();

        $this->assertNotNull($projection);
        $this->assertNotNull($projection->id);
        $this->assertNotNull($projection->committee_id);
        $this->assertNotNull($projection->member_id);
        $this->assertNotNull($projection->tenant_id);
        $this->assertNotNull($projection->assigned_at);
    }

    /**
     * INVARIANT: Member removal deletes projection record
     */
    public function test_member_removal_deletes_projection(): void
    {
        $committeeId = CommitteeId::generate();
        $memberId = MemberId::generate();
        $tenantId = $this->createTenant();

        // First assign
        Event::dispatch(new MemberAssignedToCommittee(
            $committeeId,
            $memberId,
            $tenantId,
            CommitteeRole::MEMBER
        ));

        $this->assertDatabaseHas('committee_member_projection', [
            'committee_id' => $committeeId->value(),
            'member_id' => $memberId->value(),
        ]);

        // Then remove
        Event::dispatch(new MemberRemovedFromCommittee(
            $committeeId,
            $memberId,
            $tenantId
        ));

        $this->assertDatabaseMissing('committee_member_projection', [
            'committee_id' => $committeeId->value(),
            'member_id' => $memberId->value(),
        ]);
    }

    /**
     * INVARIANT: Replay produces deterministic result
     * Full event sequence replayed = same final state
     */
    public function test_event_replay_produces_deterministic_state(): void
    {
        $committeeId = CommitteeId::generate();
        $member1 = MemberId::generate();
        $member2 = MemberId::generate();
        $member3 = MemberId::generate();
        $tenantId = $this->createTenant();

        $events = [
            new MemberAssignedToCommittee($committeeId, $member1, $tenantId, CommitteeRole::MEMBER),
            new MemberAssignedToCommittee($committeeId, $member2, $tenantId, CommitteeRole::MEMBER),
            new MemberAssignedToCommittee($committeeId, $member3, $tenantId, CommitteeRole::MEMBER),
            new MemberRemovedFromCommittee($committeeId, $member2, $tenantId),
        ];

        // First replay
        foreach ($events as $event) {
            Event::dispatch($event);
        }

        $state1 = CommitteeMemberProjection::where('committee_id', $committeeId->value())
            ->orderBy('member_id')
            ->pluck('member_id')
            ->toArray();

        // Clear and replay
        CommitteeMemberProjection::where('committee_id', $committeeId->value())->delete();

        foreach ($events as $event) {
            Event::dispatch($event);
        }

        $state2 = CommitteeMemberProjection::where('committee_id', $committeeId->value())
            ->orderBy('member_id')
            ->pluck('member_id')
            ->toArray();

        $this->assertEquals($state1, $state2, 'Replay produced different state');
    }

    /**
     * INVARIANT: Outbox-simulated retry (duplicate processing)
     * When outbox processor retries the same event, projection remains correct
     */
    public function test_outbox_retry_scenario_remains_consistent(): void
    {
        $committeeId = CommitteeId::generate();
        $memberId = MemberId::generate();
        $tenantId = $this->createTenant();

        $event = new MemberAssignedToCommittee(
            $committeeId,
            $memberId,
            $tenantId,
            CommitteeRole::MEMBER
        );

        // Simulate: event processed, then outbox retries
        Event::dispatch($event);

        $count1 = CommitteeMemberProjection::where('committee_id', $committeeId->value())
            ->where('member_id', $memberId->value())
            ->count();

        Event::dispatch($event); // retry

        $count2 = CommitteeMemberProjection::where('committee_id', $committeeId->value())
            ->where('member_id', $memberId->value())
            ->count();

        $this->assertEquals(1, $count1);
        $this->assertEquals(1, $count2, 'Outbox retry created duplicate');
    }

    /**
     * INVARIANT: Multiple committees with same member
     * Member can be assigned to multiple committees without collision
     */
    public function test_same_member_in_multiple_committees(): void
    {
        $committee1 = CommitteeId::generate();
        $committee2 = CommitteeId::generate();
        $committee3 = CommitteeId::generate();
        $memberId = MemberId::generate();
        $tenantId = $this->createTenant();

        Event::dispatch(new MemberAssignedToCommittee($committee1, $memberId, $tenantId, CommitteeRole::MEMBER));
        Event::dispatch(new MemberAssignedToCommittee($committee2, $memberId, $tenantId, CommitteeRole::MEMBER));
        Event::dispatch(new MemberAssignedToCommittee($committee3, $memberId, $tenantId, CommitteeRole::MEMBER));

        $count = CommitteeMemberProjection::where('member_id', $memberId->value())
            ->where('tenant_id', $tenantId->value())
            ->count();

        $this->assertEquals(3, $count, 'Member should appear in 3 committees');
    }
}
