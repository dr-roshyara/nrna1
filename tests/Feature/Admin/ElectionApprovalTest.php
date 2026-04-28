<?php

namespace Tests\Feature\Admin;

use App\Models\Election;
use App\Models\ElectionStateTransition;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionApprovalTest extends TestCase
{
    use RefreshDatabase;

    private User $superAdmin;
    private User $orgAdmin;
    private User $regularUser;
    private Organisation $org;
    private Election $pendingElection;

    protected function setUp(): void
    {
        parent::setUp();

        // Super admin — platform-level, no organisation scope
        $this->superAdmin = User::factory()->create([
            'is_super_admin' => true,
        ]);

        // Organisation and its admin
        $this->org = Organisation::factory()->create();
        $this->orgAdmin = User::factory()->create();
        UserOrganisationRole::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->orgAdmin->id,
            'role'            => 'admin',
        ]);

        // Regular user (no special roles)
        $this->regularUser = User::factory()->create();

        // An election in pending_approval state (simulates > 40 expected voters)
        $this->pendingElection = Election::factory()->create([
            'organisation_id'           => $this->org->id,
            'state'                     => 'pending_approval',
            'expected_voter_count'      => 50,
            'submitted_for_approval_at' => now(),
            'submitted_by'              => $this->orgAdmin->id,
        ]);
    }

    /** @test */
    public function super_admin_can_view_pending_elections(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->get('/platform/elections/pending');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page->component('Admin/Elections/Pending')
        );
    }

    /** @test */
    public function non_super_admin_cannot_view_pending_elections(): void
    {
        $this->actingAs($this->orgAdmin)
            ->get('/platform/elections/pending')
            ->assertStatus(403);

        $this->actingAs($this->regularUser)
            ->get('/platform/elections/pending')
            ->assertStatus(403);
    }

    /** @test */
    public function super_admin_can_approve_pending_election(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post("/platform/elections/{$this->pendingElection->id}/approve", [
                'notes' => 'Looks good, approved.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('administration', $this->pendingElection->fresh()->state);
    }

    /** @test */
    public function super_admin_can_reject_pending_election(): void
    {
        $response = $this->actingAs($this->superAdmin)
            ->post("/platform/elections/{$this->pendingElection->id}/reject", [
                'reason' => 'Voter count exceeds platform policy limit.',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $fresh = $this->pendingElection->fresh();
        $this->assertEquals('draft', $fresh->state);
        $this->assertNotNull($fresh->rejected_at);
        $this->assertEquals('Voter count exceeds platform policy limit.', $fresh->rejection_reason);
    }

    /** @test */
    public function org_admin_cannot_approve_election(): void
    {
        $response = $this->actingAs($this->orgAdmin)
            ->post("/platform/elections/{$this->pendingElection->id}/approve");

        $response->assertStatus(403);

        // State should NOT have changed
        $this->assertEquals('pending_approval', $this->pendingElection->fresh()->state);
    }

    /** @test */
    public function unauthenticated_user_cannot_access_admin_routes(): void
    {
        $this->get('/platform/elections/pending')->assertRedirect('/login');
        $this->post("/platform/elections/{$this->pendingElection->id}/approve")->assertRedirect('/login');
        $this->post("/platform/elections/{$this->pendingElection->id}/reject")->assertRedirect('/login');
    }

    /** @test */
    public function approval_creates_audit_record_with_super_admin_actor(): void
    {
        $this->actingAs($this->superAdmin)
            ->post("/platform/elections/{$this->pendingElection->id}/approve", [
                'notes' => 'Platform approval',
            ]);

        $transition = ElectionStateTransition::where('election_id', $this->pendingElection->id)
            ->latest()
            ->first();

        $this->assertEquals('pending_approval', $transition->from_state);
        $this->assertEquals('administration', $transition->to_state);
        $this->assertEquals('manual', $transition->trigger);
        $this->assertEquals($this->superAdmin->id, $transition->actor_id);
    }

    /** @test */
    public function cannot_approve_election_not_in_pending_state(): void
    {
        // Move election to administration first
        $this->pendingElection->update(['state' => 'administration']);

        $response = $this->actingAs($this->superAdmin)
            ->post("/platform/elections/{$this->pendingElection->id}/approve");

        $response->assertRedirect();
        $response->assertSessionHas('error');
    }
}
