<?php

/**
 * TDD — Phase 3: OrganisationParticipant management (staff/guest/election_committee)
 *
 * The OrganisationParticipant model is complete, but there is NO web interface
 * for creating, listing, or removing participants via routes/UI.
 *
 * Routes needed:
 *   GET    /organisations/{org}/membership/participants
 *   POST   /organisations/{org}/membership/participants
 *   DELETE /organisations/{org}/membership/participants/{participant}
 *
 * All tests MUST FAIL before the controller + routes are created (Red).
 */

namespace Tests\Feature\Membership;

use App\Models\Organisation;
use App\Models\OrganisationParticipant;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ParticipantManagementTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $owner;
    private User $admin;
    private User $commission;
    private User $targetUser;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->owner      = $this->makeOrgUser('owner');
        $this->admin      = $this->makeOrgUser('admin');
        $this->commission = $this->makeOrgUser('commission');
        $this->targetUser = User::factory()->create();
    }

    private function makeOrgUser(string $role): User
    {
        $user = User::factory()->create();
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => $role,
        ]);
        return $user;
    }

    private function participantsRoute(): string
    {
        return route('organisations.membership.participants.index', $this->org->slug);
    }

    private function participantsStoreRoute(): string
    {
        return route('organisations.membership.participants.store', $this->org->slug);
    }

    private function participantDestroyRoute(OrganisationParticipant $p): string
    {
        return route('organisations.membership.participants.destroy', [$this->org->slug, $p->id]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 1 — Access control
    // ══════════════════════════════════════════════════════════════════════════

    public function test_owner_can_view_participants_list(): void
    {
        $response = $this->actingAs($this->owner)->get($this->participantsRoute());
        $response->assertOk();
    }

    public function test_admin_can_view_participants_list(): void
    {
        $response = $this->actingAs($this->admin)->get($this->participantsRoute());
        $response->assertOk();
    }

    public function test_commission_member_cannot_create_participant(): void
    {
        $response = $this->actingAs($this->commission)->post($this->participantsStoreRoute(), [
            'email'            => $this->targetUser->email,
            'participant_type' => 'staff',
        ]);
        $response->assertForbidden();
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 2 — Create participants
    // ══════════════════════════════════════════════════════════════════════════

    public function test_admin_can_create_staff_participant(): void
    {
        $this->actingAs($this->admin)->post($this->participantsStoreRoute(), [
            'email'            => $this->targetUser->email,
            'participant_type' => 'staff',
            'role'             => 'coordinator',
        ]);

        $this->assertDatabaseHas('organisation_participants', [
            'organisation_id'  => $this->org->id,
            'user_id'          => $this->targetUser->id,
            'participant_type' => 'staff',
            'role'             => 'coordinator',
        ]);
    }

    public function test_creating_guest_with_expires_at_is_not_expired_initially(): void
    {
        $expiresAt = now()->addDays(7)->toDateTimeString();

        $this->actingAs($this->admin)->post($this->participantsStoreRoute(), [
            'email'            => $this->targetUser->email,
            'participant_type' => 'guest',
            'expires_at'       => $expiresAt,
        ]);

        $participant = OrganisationParticipant::where('user_id', $this->targetUser->id)->first();

        $this->assertNotNull($participant);
        $this->assertFalse($participant->isExpired(),
            'Newly created guest with future expiry should not be expired');
    }

    public function test_admin_can_create_election_committee_participant(): void
    {
        $this->actingAs($this->admin)->post($this->participantsStoreRoute(), [
            'email'            => $this->targetUser->email,
            'participant_type' => 'election_committee',
            'role'             => 'scrutineer',
        ]);

        $this->assertDatabaseHas('organisation_participants', [
            'organisation_id'  => $this->org->id,
            'user_id'          => $this->targetUser->id,
            'participant_type' => 'election_committee',
        ]);
    }

    public function test_cannot_create_participant_with_invalid_type(): void
    {
        $response = $this->actingAs($this->admin)->post($this->participantsStoreRoute(), [
            'email'            => $this->targetUser->email,
            'participant_type' => 'invalid_type',
        ]);

        $response->assertSessionHasErrors(['participant_type']);
    }

    public function test_cannot_create_participant_for_nonexistent_user_email(): void
    {
        $response = $this->actingAs($this->admin)->post($this->participantsStoreRoute(), [
            'email'            => 'doesnotexist@nowhere.test',
            'participant_type' => 'staff',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 3 — Scopes appear correctly after creation
    // ══════════════════════════════════════════════════════════════════════════

    public function test_created_staff_appears_in_staff_scope(): void
    {
        OrganisationParticipant::create([
            'id'               => (string) Str::uuid(),
            'organisation_id'  => $this->org->id,
            'user_id'          => $this->targetUser->id,
            'participant_type' => 'staff',
            'assigned_at'      => now(),
        ]);

        $this->assertCount(1, $this->org->staff);
        $this->assertCount(0, $this->org->guests);
    }

    public function test_created_election_committee_appears_in_election_committee_scope(): void
    {
        OrganisationParticipant::create([
            'id'               => (string) Str::uuid(),
            'organisation_id'  => $this->org->id,
            'user_id'          => $this->targetUser->id,
            'participant_type' => 'election_committee',
            'assigned_at'      => now(),
        ]);

        $this->assertCount(1, $this->org->electionCommittee);
        $this->assertCount(0, $this->org->staff);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 4 — Destroy
    // ══════════════════════════════════════════════════════════════════════════

    public function test_owner_can_remove_participant(): void
    {
        $participant = OrganisationParticipant::create([
            'id'               => (string) Str::uuid(),
            'organisation_id'  => $this->org->id,
            'user_id'          => $this->targetUser->id,
            'participant_type' => 'staff',
            'assigned_at'      => now(),
        ]);

        $this->actingAs($this->owner)
            ->delete($this->participantDestroyRoute($participant));

        // Soft deleted — not hard deleted
        $this->assertSoftDeleted('organisation_participants', ['id' => $participant->id]);
    }

    // ══════════════════════════════════════════════════════════════════════════
    //  Group 5 — Tenant isolation
    // ══════════════════════════════════════════════════════════════════════════

    public function test_cannot_see_other_orgs_participants(): void
    {
        $otherOrg  = Organisation::factory()->create(['type' => 'tenant']);
        $otherUser = User::factory()->create();

        OrganisationParticipant::create([
            'id'               => (string) Str::uuid(),
            'organisation_id'  => $otherOrg->id,
            'user_id'          => $otherUser->id,
            'participant_type' => 'staff',
            'assigned_at'      => now(),
        ]);

        $response = $this->actingAs($this->owner)->get($this->participantsRoute());
        $response->assertOk();

        // The page data must not contain the other org's participant
        $response->assertInertia(fn ($page) =>
            $page->where('participants.total', 0)
        );
    }
}
