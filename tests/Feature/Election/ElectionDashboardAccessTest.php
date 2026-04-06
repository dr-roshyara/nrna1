<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionDashboardAccessTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private User $chief;
    private User $deputy;
    private User $commissioner;
    private User $pendingChief;
    private User $nonOfficer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->election = Election::factory()->forOrganisation($this->org)->real()->create([
            'status'            => 'active',
            'results_published' => false,
        ]);

        $this->chief       = $this->makeOfficer('chief', 'active');
        $this->deputy      = $this->makeOfficer('deputy', 'active');
        $this->commissioner = $this->makeOfficer('commissioner', 'active');
        $this->pendingChief = $this->makeOfficer('chief', 'pending');

        $this->nonOfficer = User::factory()->create(['organisation_id' => $this->org->id]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $this->nonOfficer->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);
    }

    private function makeOfficer(string $role, string $status): User
    {
        $user = User::factory()->create([
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
        ]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);
        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $user->id,
            'role'            => $role,
            'status'          => $status,
            'appointed_by'    => $user->id,
            'appointed_at'    => now(),
            'accepted_at'     => $status === 'active' ? now() : null,
        ]);
        return $user;
    }

    private function orgSession(): array
    {
        return ['current_organisation_id' => $this->org->id];
    }

    // ─── Management dashboard ───────────────────────────────────────────────

    public function test_chief_can_access_management_dashboard(): void
    {
        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->get(route('elections.management', $this->election))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Election/Management'));
    }

    public function test_deputy_can_access_management_dashboard(): void
    {
        $this->actingAs($this->deputy)
            ->withSession($this->orgSession())
            ->get(route('elections.management', $this->election))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Election/Management'));
    }

    public function test_commissioner_cannot_access_management_dashboard(): void
    {
        $this->actingAs($this->commissioner)
            ->withSession($this->orgSession())
            ->get(route('elections.management', $this->election))
            ->assertForbidden();
    }

    public function test_pending_officer_cannot_access_management_dashboard(): void
    {
        $this->actingAs($this->pendingChief)
            ->withSession($this->orgSession())
            ->get(route('elections.management', $this->election))
            ->assertForbidden();
    }

    public function test_non_officer_cannot_access_management_dashboard(): void
    {
        $this->actingAs($this->nonOfficer)
            ->withSession($this->orgSession())
            ->get(route('elections.management', $this->election))
            ->assertForbidden();
    }

    // ─── Viewboard ──────────────────────────────────────────────────────────

    public function test_commissioner_can_access_viewboard(): void
    {
        $this->actingAs($this->commissioner)
            ->withSession($this->orgSession())
            ->get(route('elections.viewboard', $this->election))
            ->assertOk()
            ->assertInertia(fn ($page) => $page->component('Election/Viewboard'));
    }

    public function test_chief_can_access_viewboard(): void
    {
        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->get(route('elections.viewboard', $this->election))
            ->assertOk();
    }

    public function test_non_officer_cannot_access_viewboard(): void
    {
        $this->actingAs($this->nonOfficer)
            ->withSession($this->orgSession())
            ->get(route('elections.viewboard', $this->election))
            ->assertForbidden();
    }

    // ─── Publish results ────────────────────────────────────────────────────

    public function test_chief_can_publish_results(): void
    {
        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.publish', $this->election))
            ->assertRedirect();

        $this->assertTrue($this->election->fresh()->results_published);
    }

    public function test_deputy_cannot_publish_results(): void
    {
        $this->actingAs($this->deputy)
            ->withSession($this->orgSession())
            ->post(route('elections.publish', $this->election))
            ->assertForbidden();
    }

    // ─── Voting period control ──────────────────────────────────────────────

    public function test_chief_can_open_and_close_voting(): void
    {
        // Close voting first
        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.close-voting', $this->election))
            ->assertRedirect();

        $this->assertEquals('completed', $this->election->fresh()->status);

        // Re-open voting
        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.open-voting', $this->election))
            ->assertRedirect();

        $this->assertEquals('active', $this->election->fresh()->status);
    }

    // ─── Cross-org isolation ────────────────────────────────────────────────

    public function test_officer_from_different_org_cannot_access_election(): void
    {
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);
        $outsiderChief = User::factory()->create(['organisation_id' => $otherOrg->id]);
        ElectionOfficer::create([
            'organisation_id' => $otherOrg->id,
            'user_id'         => $outsiderChief->id,
            'role'            => 'chief',
            'status'          => 'active',
            'appointed_by'    => $outsiderChief->id,
            'appointed_at'    => now(),
            'accepted_at'     => now(),
        ]);

        // Logged in to org A, trying to access org B's election.
        // BelongsToTenant global scope filters the election by session org,
        // so the outsider gets a 404 (model not found) rather than 403.
        // Both 403 and 404 correctly block cross-org access.
        $response = $this->actingAs($outsiderChief)
            ->withSession(['current_organisation_id' => $otherOrg->id])
            ->get(route('elections.management', $this->election));

        $this->assertContains($response->status(), [403, 404],
            'Cross-org access must be blocked (403 or 404)');
    }
}
