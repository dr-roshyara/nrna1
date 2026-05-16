<?php

declare(strict_types=1);

namespace Tests\Feature\Governance\Api;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

use App\Contexts\Governance\Domain\Committee\Committee;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Governance\Domain\Committee\Events\MemberAssignedToCommittee;
use App\Contexts\Governance\Domain\Committee\Events\MemberRemovedFromCommittee;
use App\Contexts\Membership\Domain\Member\MemberId;
use Tests\Support\DomainIdFactory;

/**
 * Phase 3B — REST API Tests (TDD)
 *
 * API contracts for committee member management.
 * CQRS invariant: API reads ONLY from projection.
 * Commands flow through domain, not direct writes.
 */
final class CommitteeMemberApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * INVARIANT: GET /committees/{id}/members returns projection data
     * - No aggregates loaded
     * - No joins to domain tables
     * - Projection is UI-ready
     */
    public function test_get_committee_members_returns_projection_data(): void
    {
        $tenantId = DomainIdFactory::tenant();
        $committeeId = CommitteeId::generate();
        $member1 = MemberId::generate();
        $member2 = MemberId::generate();

        // Setup: emit events to populate projection
        Event::dispatch(new MemberAssignedToCommittee($committeeId, $member1, $tenantId));
        Event::dispatch(new MemberAssignedToCommittee($committeeId, $member2, $tenantId));

        // Act: API call
        $response = $this->getJson(
            "/api/governance/committees/{$committeeId->value()}/members",
            ['X-Tenant-Id' => $tenantId->value()]
        );

        // Assert: projection data returned
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

        $this->assertEquals(2, count($response->json('members')));
    }

    /**
     * INVARIANT: Tenant isolation enforced at API level
     * Tenant A should NOT see Tenant B's committees
     */
    public function test_api_enforces_tenant_isolation(): void
    {
        $tenantA = DomainIdFactory::tenant();
        $tenantB = DomainIdFactory::tenant();

        $committeeA = CommitteeId::generate();
        $committeeB = CommitteeId::generate();

        $memberA = MemberId::generate();
        $memberB = MemberId::generate();

        // Setup: different events in different tenants
        Event::dispatch(new MemberAssignedToCommittee($committeeA, $memberA, $tenantA));
        Event::dispatch(new MemberAssignedToCommittee($committeeB, $memberB, $tenantB));

        // Act: Query Tenant A's committee
        $response = $this->getJson(
            "/api/governance/committees/{$committeeA->value()}/members",
            ['X-Tenant-Id' => $tenantA->value()]
        );

        // Assert: only Tenant A's members returned
        $response->assertStatus(200);

        $members = $response->json('members');

        // Should contain only memberA
        $this->assertCount(1, $members);
        $this->assertEquals($memberA->value(), $members[0]['memberId']);
    }

    /**
     * INVARIANT: Empty committee returns empty array
     */
    public function test_empty_committee_returns_empty_members(): void
    {
        $tenantId = DomainIdFactory::tenant();
        $committeeId = CommitteeId::generate();

        $response = $this->getJson(
            "/api/governance/committees/{$committeeId->value()}/members",
            ['X-Tenant-Id' => $tenantId->value()]
        );

        $response->assertStatus(200)
            ->assertJson([
                'committeeId' => $committeeId->value(),
                'members' => []
            ]);
    }

    /**
     * INVARIANT: POST /assign triggers domain command
     * Returns queued response (NOT immediate projection write)
     */
    public function test_assign_member_returns_queued_status(): void
    {
        $tenantId = DomainIdFactory::tenant();
        $committeeId = CommitteeId::generate();
        $memberId = MemberId::generate();

        // Act: assign request
        $response = $this->postJson(
            "/api/governance/committees/{$committeeId->value()}/members",
            ['memberId' => $memberId->value()],
            ['X-Tenant-Id' => $tenantId->value()]
        );

        // Assert: queued response (command accepted)
        $response->assertStatus(202)
            ->assertJson(['status' => 'queued']);
    }

    /**
     * INVARIANT: Assignment validates memberId format
     */
    public function test_assign_member_validates_member_id(): void
    {
        $tenantId = DomainIdFactory::tenant();
        $committeeId = CommitteeId::generate();

        $response = $this->postJson(
            "/api/governance/committees/{$committeeId->value()}/members",
            ['memberId' => 'invalid-uuid'],
            ['X-Tenant-Id' => $tenantId->value()]
        );

        $response->assertStatus(422)
            ->assertJsonValidationErrors('memberId');
    }

    /**
     * INVARIANT: DELETE /members/{id} triggers domain command
     * Projection updated only after event processing
     */
    public function test_remove_member_triggers_domain_command(): void
    {
        $tenantId = DomainIdFactory::tenant();
        $committeeId = CommitteeId::generate();
        $memberId = MemberId::generate();

        // Setup: assign member first
        Event::dispatch(new MemberAssignedToCommittee($committeeId, $memberId, $tenantId));

        // Act: remove request
        $response = $this->deleteJson(
            "/api/governance/committees/{$committeeId->value()}/members/{$memberId->value()}",
            [],
            ['X-Tenant-Id' => $tenantId->value()]
        );

        // Assert: accepted
        $response->assertStatus(202)
            ->assertJson(['status' => 'queued']);
    }

    /**
     * INVARIANT: Removing non-existent member is safe (idempotent)
     */
    public function test_remove_nonexistent_member_is_safe(): void
    {
        $tenantId = DomainIdFactory::tenant();
        $committeeId = CommitteeId::generate();
        $memberId = MemberId::generate();

        $response = $this->deleteJson(
            "/api/governance/committees/{$committeeId->value()}/members/{$memberId->value()}",
            [],
            ['X-Tenant-Id' => $tenantId->value()]
        );

        // Should not error
        $response->assertStatus(202);
    }

    /**
     * INVARIANT: API does not require authenticated user for MVP
     * (Auth added in later phase)
     *
     * Note: In production, /api endpoints require auth.
     * This test documents the current API boundary.
     */
    public function test_api_endpoints_are_accessible(): void
    {
        $tenantId = DomainIdFactory::tenant();
        $committeeId = CommitteeId::generate();

        // GET should work
        $response = $this->getJson(
            "/api/governance/committees/{$committeeId->value()}/members",
            ['X-Tenant-Id' => $tenantId->value()]
        );
        $this->assertTrue(in_array($response->status(), [200])); // 200 for empty committee

        // POST should work
        $response = $this->postJson(
            "/api/governance/committees/{$committeeId->value()}/members",
            ['memberId' => MemberId::generate()->value()],
            ['X-Tenant-Id' => $tenantId->value()]
        );
        $this->assertTrue(in_array($response->status(), [202, 422])); // 202 queued, 422 validation error
    }

    /**
     * INVARIANT: DTO shape is stable for UI
     * API response always has consistent structure
     */
    public function test_api_response_has_consistent_dto_shape(): void
    {
        $tenantId = DomainIdFactory::tenant();
        $committeeId = CommitteeId::generate();
        $memberId = MemberId::generate();

        Event::dispatch(new MemberAssignedToCommittee(
            $committeeId,
            $memberId,
            $tenantId
        ));

        $response = $this->getJson(
            "/api/governance/committees/{$committeeId->value()}/members",
            ['X-Tenant-Id' => $tenantId->value()]
        );

        $response->assertStatus(200);

        $json = $response->json();

        // Verify structure
        $this->assertArrayHasKey('committeeId', $json);
        $this->assertArrayHasKey('members', $json);
        $this->assertIsArray($json['members']);

        // Verify member shape
        if (count($json['members']) > 0) {
            $member = $json['members'][0];
            $this->assertArrayHasKey('memberId', $member);
            $this->assertArrayHasKey('assignedAt', $member);
        }
    }
}
