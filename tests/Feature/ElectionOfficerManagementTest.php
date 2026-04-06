<?php

namespace Tests\Feature;

use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionOfficerManagementTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $admin;
    private User $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->admin = User::factory()->create(['organisation_id' => $this->org->id]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $this->admin->id,
            'organisation_id' => $this->org->id,
            'role'            => 'admin',
        ]);

        $this->member = User::factory()->create(['organisation_id' => $this->org->id]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $this->member->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);
    }

    // ── Appointment (store) ───────────────────────────────────────────────────

    public function test_admin_can_appoint_officer(): void
    {
        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('organisations.election-officers.store', $this->org->slug), [
                'user_id' => $this->member->id,
                'role'    => 'commissioner',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('election_officers', [
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'commissioner',
            'status'          => 'pending',  // Officers start pending, not active
        ]);
    }

    public function test_member_cannot_appoint_officer(): void
    {
        $other = User::factory()->create(['organisation_id' => $this->org->id]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $other->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);

        $response = $this->actingAs($this->member)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('organisations.election-officers.store', $this->org->slug), [
                'user_id' => $other->id,
                'role'    => 'commissioner',
            ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('election_officers', ['user_id' => $other->id]);
    }

    public function test_cannot_appoint_non_member(): void
    {
        $outsider = User::factory()->create(['organisation_id' => $this->org->id]);
        // Note: outsider has no UserOrganisationRole for $this->org

        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('organisations.election-officers.store', $this->org->slug), [
                'user_id' => $outsider->id,
                'role'    => 'commissioner',
            ]);

        $response->assertSessionHasErrors('user_id');
    }

    public function test_cannot_appoint_duplicate_officer(): void
    {
        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'commissioner',
            'status'          => 'pending',
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('organisations.election-officers.store', $this->org->slug), [
                'user_id' => $this->member->id,
                'role'    => 'commissioner',
            ]);

        $response->assertSessionHasErrors('user_id');
    }

    public function test_can_reappoint_soft_deleted_officer(): void
    {
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'commissioner',
            'status'          => 'resigned',
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
        ]);
        $officer->delete();
        $this->assertSoftDeleted('election_officers', ['id' => $officer->id]);

        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('organisations.election-officers.store', $this->org->slug), [
                'user_id' => $this->member->id,
                'role'    => 'chief',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $officer->refresh();
        $this->assertNull($officer->deleted_at);
        $this->assertEquals('chief', $officer->role);
        $this->assertEquals('pending', $officer->status);
        $this->assertNull($officer->accepted_at);
    }

    public function test_cannot_appoint_active_officer_again(): void
    {
        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'commissioner',
            'status'          => 'active',
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
            'accepted_at'     => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('organisations.election-officers.store', $this->org->slug), [
                'user_id' => $this->member->id,
                'role'    => 'chief',
            ]);

        $response->assertSessionHasErrors('user_id');
    }

    // ── Acceptance (accept) ───────────────────────────────────────────────────

    public function test_officer_can_accept_own_appointment(): void
    {
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'commissioner',
            'status'          => 'pending',
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
        ]);

        $response = $this->actingAs($this->member)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('organisations.election-officers.accept', [
                'organisation' => $this->org->slug,
                'officer'      => $officer->id,
            ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertDatabaseHas('election_officers', [
            'id'     => $officer->id,
            'status' => 'active',
        ]);
    }

    public function test_user_cannot_accept_another_users_appointment(): void
    {
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'commissioner',
            'status'          => 'pending',
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
        ]);

        // Admin tries to accept on behalf of member
        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->post(route('organisations.election-officers.accept', [
                'organisation' => $this->org->slug,
                'officer'      => $officer->id,
            ]));

        $response->assertForbidden();
        $this->assertDatabaseHas('election_officers', [
            'id'     => $officer->id,
            'status' => 'pending',
        ]);
    }

    // ── Removal (destroy) ─────────────────────────────────────────────────────

    public function test_admin_can_remove_officer(): void
    {
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'commissioner',
            'status'          => 'active',
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->delete(route('organisations.election-officers.destroy', [
                'organisation' => $this->org->slug,
                'officer'      => $officer->id,
            ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');
        $this->assertSoftDeleted('election_officers', ['id' => $officer->id]);
    }

    public function test_member_cannot_remove_officer(): void
    {
        $officer = ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'commissioner',
            'status'          => 'active',
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
        ]);

        $response = $this->actingAs($this->member)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->delete(route('organisations.election-officers.destroy', [
                'organisation' => $this->org->slug,
                'officer'      => $officer->id,
            ]));

        $response->assertForbidden();
        $this->assertNotSoftDeleted('election_officers', ['id' => $officer->id]);
    }

    // ── OrganisationController prop ───────────────────────────────────────────

    public function test_organisation_show_includes_officers_prop(): void
    {
        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $this->member->id,
            'role'            => 'chief',
            'status'          => 'active',
            'appointed_by'    => $this->admin->id,
            'appointed_at'    => now(),
        ]);

        $response = $this->actingAs($this->admin)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('organisations.show', $this->org->slug));

        $response->assertInertia(fn ($page) => $page->has('officers'));
    }
}
