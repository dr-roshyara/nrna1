<?php

declare(strict_types=1);

namespace Tests\Feature\Governance\Ui;

use App\Contexts\Governance\Domain\Committee\Committee;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Governance\Domain\Committee\Events\MemberAssignedToCommittee;
use App\Contexts\Membership\Domain\Member\MemberId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use Tests\Support\DomainIdFactory;

/**
 * Phase 3C — Vue Component Integration Tests
 *
 * Tests that CommitteeMemberManager component can communicate
 * with the REST API created in Phase 3B.
 */
final class CommitteeMemberManagerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * INVARIANT: Component can fetch committee members via API
     * Tests the GET endpoint that the Vue component relies on
     */
    public function test_component_can_fetch_committee_members(): void
    {
        $tenantId = DomainIdFactory::tenant();
        $committeeId = CommitteeId::generate();
        $member1 = MemberId::generate();
        $member2 = MemberId::generate();

        // Setup: emit events to populate projection
        Event::dispatch(new MemberAssignedToCommittee($committeeId, $member1, $tenantId));
        Event::dispatch(new MemberAssignedToCommittee($committeeId, $member2, $tenantId));

        // Act: Simulate component API call
        $response = $this->getJson(
            "/api/governance/committees/{$committeeId->value()}/members",
            ['X-Tenant-Id' => $tenantId->value()]
        );

        // Assert: Component receives correct data structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'committeeId',
                'members' => [
                    '*' => [
                        'memberId',
                        'assignedAt',
                    ]
                ]
            ]);

        // Component can render member list
        $this->assertCount(2, $response->json('members'));
    }

    /**
     * INVARIANT: Component can add members via API
     * Tests the POST endpoint that add-member form uses
     */
    public function test_component_can_add_member_via_api(): void
    {
        $tenantId = DomainIdFactory::tenant();
        $committeeId = CommitteeId::generate();
        $memberId = MemberId::generate();

        // Act: Component posts new member assignment
        $response = $this->postJson(
            "/api/governance/committees/{$committeeId->value()}/members",
            ['memberId' => $memberId->value()],
            ['X-Tenant-Id' => $tenantId->value()]
        );

        // Assert: API accepts the request with 202 (queued)
        $response->assertStatus(202)
            ->assertJson(['status' => 'queued']);
    }

    /**
     * INVARIANT: Component can remove members via API
     * Tests the DELETE endpoint used by remove buttons
     */
    public function test_component_can_remove_member_via_api(): void
    {
        $tenantId = DomainIdFactory::tenant();
        $committeeId = CommitteeId::generate();
        $memberId = MemberId::generate();

        // Setup: assign member first
        Event::dispatch(new MemberAssignedToCommittee($committeeId, $memberId, $tenantId));

        // Act: Component sends delete request
        $response = $this->deleteJson(
            "/api/governance/committees/{$committeeId->value()}/members/{$memberId->value()}",
            [],
            ['X-Tenant-Id' => $tenantId->value()]
        );

        // Assert: API processes the deletion request
        $response->assertStatus(202)
            ->assertJson(['status' => 'queued']);
    }

    /**
     * INVARIANT: Component validates member ID format
     * Tests error handling in add-member form
     */
    public function test_component_shows_validation_error_for_invalid_member_id(): void
    {
        $tenantId = DomainIdFactory::tenant();
        $committeeId = CommitteeId::generate();

        // Act: Component receives validation error from API
        $response = $this->postJson(
            "/api/governance/committees/{$committeeId->value()}/members",
            ['memberId' => 'not-a-uuid'],
            ['X-Tenant-Id' => $tenantId->value()]
        );

        // Assert: API returns validation error status
        $response->assertStatus(422)
            ->assertJsonValidationErrors('memberId');
    }

    /**
     * INVARIANT: Component respects tenant isolation
     * Component from Tenant A cannot access Tenant B's data
     */
    public function test_component_enforces_tenant_isolation(): void
    {
        $tenantA = DomainIdFactory::tenant();
        $tenantB = DomainIdFactory::tenant();

        $committeeA = CommitteeId::generate();
        $memberA = MemberId::generate();

        // Setup: Create data in Tenant A
        Event::dispatch(new MemberAssignedToCommittee($committeeA, $memberA, $tenantA));

        // Act: Component from Tenant B tries to access Tenant A's data
        $response = $this->getJson(
            "/api/governance/committees/{$committeeA->value()}/members",
            ['X-Tenant-Id' => $tenantB->value()]
        );

        // Assert: Tenant B sees empty members list (isolation enforced)
        $response->assertStatus(200)
            ->assertJson([
                'members' => []
            ]);
    }

    /**
     * INVARIANT: Component handles network errors gracefully
     * Vue component should not crash on API failures
     */
    public function test_component_handles_missing_tenant_context(): void
    {
        $committeeId = CommitteeId::generate();

        // Act: Component without tenant header (simulating missing context)
        $response = $this->getJson(
            "/api/governance/committees/{$committeeId->value()}/members"
        );

        // Assert: API returns 400 error (component catches and shows error)
        $response->assertStatus(400)
            ->assertJson(['error' => 'Missing tenant context']);
    }
}
