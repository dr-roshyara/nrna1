<?php

namespace Tests\Feature\Organisation;

use App\Models\Organisation;
use App\Models\OrganisationInvitation;
use App\Models\OrganisationParticipant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ParticipantInvitationAcceptanceTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private User $inviter;
    private string $token;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
        $this->inviter      = User::factory()->create();
        $this->token        = Str::random(64);
    }

    private function createParticipantInvitation(array $overrides = []): OrganisationInvitation
    {
        return OrganisationInvitation::create(array_merge([
            'organisation_id'  => $this->organisation->id,
            'email'            => 'participant@example.com',
            'role'             => 'staff',
            'invitation_type'  => 'participant',
            'participant_type' => 'staff',
            'token'            => $this->token,
            'status'           => 'pending',
            'invited_by'       => $this->inviter->id,
            'expires_at'       => now()->addDays(7),
        ], $overrides));
    }

    // ── RED tests (written before implementation) ─────────────────────────────

    public function test_accepting_participant_invitation_creates_participant_record(): void
    {
        $this->createParticipantInvitation();
        $acceptingUser = User::factory()->create(['email' => 'participant@example.com']);

        $this->actingAs($acceptingUser)
             ->get(route('organisations.participant-invitations.accept', $this->token));

        $this->assertDatabaseHas('organisation_participants', [
            'organisation_id'  => $this->organisation->id,
            'user_id'          => $acceptingUser->id,
            'participant_type' => 'staff',
        ]);
    }

    public function test_accepting_participant_invitation_marks_invitation_as_accepted(): void
    {
        $invitation    = $this->createParticipantInvitation();
        $acceptingUser = User::factory()->create(['email' => 'participant@example.com']);

        $this->actingAs($acceptingUser)
             ->get(route('organisations.participant-invitations.accept', $this->token));

        $this->assertDatabaseHas('organisation_invitations', [
            'id'          => $invitation->id,
            'status'      => 'accepted',
            'accepted_by' => $acceptingUser->id,
        ]);
    }

    public function test_accepting_participant_invitation_twice_does_not_create_duplicate(): void
    {
        $this->createParticipantInvitation();
        $acceptingUser = User::factory()->create(['email' => 'participant@example.com']);

        // Accept once
        $this->actingAs($acceptingUser)
             ->get(route('organisations.participant-invitations.accept', $this->token));

        // Create second pending invitation (simulate resend)
        $secondToken = Str::random(64);
        $this->createParticipantInvitation(['token' => $secondToken]);

        // Accept again
        $this->actingAs($acceptingUser)
             ->get(route('organisations.participant-invitations.accept', $secondToken));

        $this->assertSame(
            1,
            OrganisationParticipant::withoutGlobalScopes()
                ->where('organisation_id', $this->organisation->id)
                ->where('user_id', $acceptingUser->id)
                ->count()
        );
    }

    public function test_expired_participant_invitation_cannot_be_accepted(): void
    {
        $this->createParticipantInvitation(['expires_at' => now()->subDay()]);
        $acceptingUser = User::factory()->create(['email' => 'participant@example.com']);

        $this->actingAs($acceptingUser)
             ->get(route('organisations.participant-invitations.accept', $this->token))
             ->assertStatus(404);

        $this->assertDatabaseMissing('organisation_participants', [
            'organisation_id' => $this->organisation->id,
        ]);
    }

    public function test_non_admin_cannot_send_participant_invitation(): void
    {
        $member = User::factory()->create();

        // Give them 'member' role (not admin/owner)
        \App\Models\UserOrganisationRole::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $member->id,
            'role'            => 'member',
        ]);

        $this->actingAs($member)
             ->post(route('organisations.membership.participant-invitations.store', $this->organisation->slug), [
                 'email'            => 'someone@example.com',
                 'participant_type' => 'staff',
             ])
             ->assertStatus(403);
    }

    public function test_admin_can_send_participant_invitation(): void
    {
        $admin = User::factory()->create();

        \App\Models\UserOrganisationRole::create([
            'organisation_id' => $this->organisation->id,
            'user_id'         => $admin->id,
            'role'            => 'admin',
        ]);

        $this->actingAs($admin)
             ->post(route('organisations.membership.participant-invitations.store', $this->organisation->slug), [
                 'email'            => 'newparticipant@example.com',
                 'participant_type' => 'staff',
                 'message'          => null,
             ])
             ->assertRedirect();

        $this->assertDatabaseHas('organisation_invitations', [
            'organisation_id'  => $this->organisation->id,
            'email'            => 'newparticipant@example.com',
            'invitation_type'  => 'participant',
            'participant_type' => 'staff',
            'status'           => 'pending',
        ]);
    }
}
