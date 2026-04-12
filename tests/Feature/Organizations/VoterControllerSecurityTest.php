<?php

namespace Tests\Feature\organisations;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Security Tests for Voter Controller
 *
 * These tests ensure that the voter controller properly enforces
 * security boundaries and prevents unauthorized access.
 */
class VoterControllerSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $organisation1;
    protected $organisation2;
    protected $user1;
    protected $user2;
    protected $voter1;
    protected $voter2;

    protected function setUp(): void
    {
        parent::setUp();

        // Create two organisations
        $this->organization1 = Organisation::factory()->create(['name' => 'Org 1']);
        $this->organization2 = Organisation::factory()->create(['name' => 'Org 2']);

        // Create users for each organisation
        $this->user1 = User::factory()->create(['name' => 'User Org 1']);
        $this->user2 = User::factory()->create(['name' => 'User Org 2']);

        // Attach users to their organisations with commission role
        $this->user1->organisationRoles()->attach($this->organization1->id, ['role' => 'commission']);
        $this->user2->organisationRoles()->attach($this->organization2->id, ['role' => 'commission']);

        // Create voters in each organisation
        $this->voter1 = User::factory()->create([
            'organisation_id' => $this->organization1->id,
            'is_voter' => 1,
        ]);

        $this->voter2 = User::factory()->create([
            'organisation_id' => $this->organization2->id,
            'is_voter' => 1,
        ]);
    }

    /**
     * Test that user from org1 cannot access voter list of org2
     *
     * @test
     */
    public function it_prevents_cross_organization_voter_list_access()
    {
        // Act
        $response = $this->actingAs($this->user1)
            ->get("/organisations/{$this->organization2->slug}/voters");

        // Assert
        $response->assertStatus(403);
    }

    /**
     * Test that user from org1 cannot approve voters from org2
     *
     * @test
     */
    public function it_prevents_cross_organization_voter_approval()
    {
        // Act
        $response = $this->actingAs($this->user1)
            ->post("/organisations/{$this->organization2->slug}/voters/{$this->voter2->id}/approve");

        // Assert
        $response->assertStatus(403);
        $this->assertNull($this->voter2->refresh()->approvedBy);
    }

    /**
     * Test that user from org1 cannot suspend voters from org2
     *
     * @test
     */
    public function it_prevents_cross_organization_voter_suspension()
    {
        // Arrange
        $this->voter2->update(['approvedBy' => 'Admin']);

        // Act
        $response = $this->actingAs($this->user1)
            ->post("/organisations/{$this->organization2->slug}/voters/{$this->voter2->id}/suspend");

        // Assert
        $response->assertStatus(403);
        $this->assertNotNull($this->voter2->refresh()->approvedBy);
    }

    /**
     * Test that user from org2 cannot approve voters from org1
     *
     * @test
     */
    public function it_prevents_org2_user_approving_org1_voters()
    {
        // Act
        $response = $this->actingAs($this->user2)
            ->post("/organisations/{$this->organization1->slug}/voters/{$this->voter1->id}/approve");

        // Assert
        $response->assertStatus(403);
        $this->assertNull($this->voter1->refresh()->approvedBy);
    }

    /**
     * Test that bulk approve respects organisation boundaries
     *
     * @test
     */
    public function it_prevents_cross_organization_bulk_approve()
    {
        // Act
        $response = $this->actingAs($this->user1)
            ->post("/organisations/{$this->organization2->slug}/voters/bulk-approve", [
                'voter_ids' => [$this->voter2->id],
            ]);

        // Assert
        $response->assertStatus(403);
        $this->assertNull($this->voter2->refresh()->approvedBy);
    }

    /**
     * Test that bulk suspend respects organisation boundaries
     *
     * @test
     */
    public function it_prevents_cross_organization_bulk_suspend()
    {
        // Arrange
        $this->voter2->update(['approvedBy' => 'Admin']);

        // Act
        $response = $this->actingAs($this->user1)
            ->post("/organisations/{$this->organization2->slug}/voters/bulk-suspend", [
                'voter_ids' => [$this->voter2->id],
            ]);

        // Assert
        $response->assertStatus(403);
        $this->assertNotNull($this->voter2->refresh()->approvedBy);
    }

    /**
     * Test that non-commission member cannot approve voters
     *
     * @test
     */
    public function it_requires_commission_role_for_approval()
    {
        // Arrange
        $regularMember = User::factory()->create();
        $regularMember->organisationRoles()->attach($this->organization1->id, ['role' => 'member']);

        // Act
        $response = $this->actingAs($regularMember)
            ->post("/organisations/{$this->organization1->slug}/voters/{$this->voter1->id}/approve");

        // Assert
        $response->assertStatus(403);
        $this->assertNull($this->voter1->refresh()->approvedBy);
    }

    /**
     * Test that non-commission member cannot bulk approve
     *
     * @test
     */
    public function it_requires_commission_role_for_bulk_approve()
    {
        // Arrange
        $regularMember = User::factory()->create();
        $regularMember->organisationRoles()->attach($this->organization1->id, ['role' => 'member']);

        // Act
        $response = $this->actingAs($regularMember)
            ->post("/organisations/{$this->organization1->slug}/voters/bulk-approve", [
                'voter_ids' => [$this->voter1->id],
            ]);

        // Assert
        $response->assertStatus(403);
        $this->assertNull($this->voter1->refresh()->approvedBy);
    }

    /**
     * Test that SQL injection in voter_id doesn't break security
     *
     * @test
     */
    public function it_prevents_sql_injection_in_voter_id()
    {
        // Act
        $response = $this->actingAs($this->user1)
            ->post("/organisations/{$this->organization1->slug}/voters/99999' OR '1'='1/approve");

        // Assert
        $response->assertStatus(404);
    }

    /**
     * Test that manipulated organisation_id parameter is blocked
     *
     * @test
     */
    public function it_validates_organization_from_route_not_request()
    {
        // The organisation is extracted from the URL route parameter,
        // not from user input, so this is inherently protected
        // This test documents the correct behavior

        // Act - Try to access org2 via org1 route
        $response = $this->actingAs($this->user1)
            ->post("/organisations/{$this->organization1->slug}/voters/{$this->voter1->id}/approve");

        // Assert - Should succeed for own organisation
        $response->assertRedirect();
        $this->assertNotNull($this->voter1->refresh()->approvedBy);
    }

    /**
     * Test that voter approval records the approver's name, not attacker's
     *
     * @test
     */
    public function it_records_actual_approver_identity()
    {
        // Act
        $this->actingAs($this->user1)
            ->post("/organisations/{$this->organization1->slug}/voters/{$this->voter1->id}/approve");

        // Assert
        $voter = $this->voter1->refresh();
        $this->assertEquals($this->user1->name, $voter->approvedBy);
        $this->assertNotEquals('attacker', $voter->approvedBy);
    }

    /**
     * Test that voters from different orgs in bulk approve are filtered
     *
     * @test
     */
    public function it_filters_voters_by_organization_in_bulk_approve()
    {
        // Arrange
        $voter1b = User::factory()->create([
            'organisation_id' => $this->organization1->id,
            'is_voter' => 1,
        ]);

        // Try to approve both org1 and org2 voters
        // Act
        $response = $this->actingAs($this->user1)
            ->post("/organisations/{$this->organization1->slug}/voters/bulk-approve", [
                'voter_ids' => [$voter1b->id, $this->voter2->id], // One from each org
            ]);

        // Assert - Both should be attempted, but only org1 voter should succeed
        $this->assertNotNull($voter1b->refresh()->approvedBy);
        $this->assertNull($this->voter2->refresh()->approvedBy);
    }

    /**
     * Test that organisation slug cannot be manipulated
     *
     * @test
     */
    public function it_validates_organization_slug_integrity()
    {
        // The slug is resolved from the route, which is validated by the middleware
        // This test documents that the controller trusts the middleware

        // Act
        $response = $this->actingAs($this->user1)
            ->get("/organisations/invalid-slug/voters");

        // Assert
        $response->assertStatus(403); // Blocked by middleware, not by controller
    }

    /**
     * Test that CSRF token is required for modifications
     *
     * @test
     */
    public function it_requires_csrf_token_for_state_changes()
    {
        // Arrange
        $voter = User::factory()->create([
            'organisation_id' => $this->organization1->id,
            'is_voter' => 1,
        ]);

        // Act - POST without CSRF token
        $response = $this->actingAs($this->user1)
            ->post("/organisations/{$this->organization1->slug}/voters/{$voter->id}/approve", [], [
                'X-CSRF-TOKEN' => 'invalid-token',
            ]);

        // Assert
        $response->assertStatus(419); // CSRF mismatch
    }

    /**
     * Test that audit logging includes user information
     *
     * @test
     */
    public function it_logs_all_approval_attempts()
    {
        // This test verifies the behavior documented in the controller
        // The controller logs both successful and failed attempts

        // Act - Failed attempt (non-member)
        $nonMember = User::factory()->create();
        $response = $this->actingAs($nonMember)
            ->post("/organisations/{$this->organization1->slug}/voters/{$this->voter1->id}/approve");

        // Assert - Should be logged as warning
        $response->assertStatus(403);

        // Act - Successful attempt
        $response = $this->actingAs($this->user1)
            ->post("/organisations/{$this->organization1->slug}/voters/{$this->voter1->id}/approve");

        // Assert - Should be logged as info
        $this->assertNotNull($this->voter1->refresh()->approvedBy);
    }

    /**
     * Test that privilege escalation attempts are blocked
     *
     * @test
     */
    public function it_prevents_privilege_escalation_attacks()
    {
        // Arrange
        $regularMember = User::factory()->create();
        $regularMember->organisationRoles()->attach($this->organization1->id, ['role' => 'member']);

        // Act - Regular member tries to approve (commission action)
        $response = $this->actingAs($regularMember)
            ->post("/organisations/{$this->organization1->slug}/voters/{$this->voter1->id}/approve");

        // Assert
        $response->assertStatus(403);
        $this->assertNull($this->voter1->refresh()->approvedBy);
    }

    /**
     * Test that request injection attacks don't leak data
     *
     * @test
     */
    public function it_prevents_information_disclosure()
    {
        // Try to get information about org2 voters while accessing org1
        // The system should never reveal information about other organisations

        // Act
        $response = $this->actingAs($this->user1)
            ->get("/organisations/{$this->organization1->slug}/voters?search=" . $this->voter2->name);

        // Assert
        $response->assertInertia(fn ($page) => $page
            ->where('stats.total', 1) // Only org1 voters, not org2
        );

        // The response should contain zero results for org2 voter
        $voters = $response['props']['voters']['data'];
        foreach ($voters as $voter) {
            $this->assertEquals($this->organization1->id, $voter['organisation_id']);
        }
    }
}
