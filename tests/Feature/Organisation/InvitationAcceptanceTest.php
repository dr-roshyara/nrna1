<?php

namespace Tests\Feature\Organisation;

use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationInvitation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class InvitationAcceptanceTest extends TestCase
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

    private function createInvitation(array $overrides = []): OrganisationInvitation
    {
        return OrganisationInvitation::create(array_merge([
            'organisation_id' => $this->organisation->id,
            'email'           => 'invited@example.com',
            'role'            => 'member',
            'token'           => $this->token,
            'status'          => 'pending',
            'invited_by'      => $this->inviter->id,
            'expires_at'      => now()->addDays(7),
        ], $overrides));
    }

    // ── RED tests (written before implementation) ─────────────────────────────

    public function test_accepting_invitation_creates_organisation_user_record(): void
    {
        $this->createInvitation();
        $acceptingUser = User::factory()->create(['email' => 'invited@example.com']);

        $this->actingAs($acceptingUser)
             ->get(route('organisations.invitations.accept', $this->token));

        $this->assertDatabaseHas('organisation_users', [
            'organisation_id' => $this->organisation->id,
            'user_id'         => $acceptingUser->id,
        ]);
    }

    public function test_accepting_invitation_creates_member_record(): void
    {
        $this->createInvitation();
        $acceptingUser = User::factory()->create(['email' => 'invited@example.com']);

        $this->actingAs($acceptingUser)
             ->get(route('organisations.invitations.accept', $this->token));

        $orgUser = OrganisationUser::withoutGlobalScopes()
            ->where('organisation_id', $this->organisation->id)
            ->where('user_id', $acceptingUser->id)
            ->first();

        $this->assertNotNull($orgUser, 'OrganisationUser must exist before Member can be created');

        $this->assertDatabaseHas('members', [
            'organisation_id'      => $this->organisation->id,
            'organisation_user_id' => $orgUser->id,
            'status'               => 'active',
            'fees_status'          => 'unpaid',
        ]);
    }

    public function test_accepting_invitation_creates_user_organisation_role(): void
    {
        $this->createInvitation();
        $acceptingUser = User::factory()->create(['email' => 'invited@example.com']);

        $this->actingAs($acceptingUser)
             ->get(route('organisations.invitations.accept', $this->token));

        $this->assertDatabaseHas('user_organisation_roles', [
            'organisation_id' => $this->organisation->id,
            'user_id'         => $acceptingUser->id,
            'role'            => 'member',
        ]);
    }

    public function test_accepting_invitation_marks_invitation_as_accepted(): void
    {
        $invitation    = $this->createInvitation();
        $acceptingUser = User::factory()->create(['email' => 'invited@example.com']);

        $this->actingAs($acceptingUser)
             ->get(route('organisations.invitations.accept', $this->token));

        $this->assertDatabaseHas('organisation_invitations', [
            'id'          => $invitation->id,
            'status'      => 'accepted',
            'accepted_by' => $acceptingUser->id,
        ]);
    }

    public function test_accepting_invitation_twice_does_not_create_duplicate_records(): void
    {
        $this->createInvitation();
        $acceptingUser = User::factory()->create(['email' => 'invited@example.com']);

        // Accept once
        $this->actingAs($acceptingUser)
             ->get(route('organisations.invitations.accept', $this->token));

        // Create a second pending invitation (simulate resend)
        $secondToken = Str::random(64);
        $this->createInvitation(['token' => $secondToken]);

        // Accept again
        $this->actingAs($acceptingUser)
             ->get(route('organisations.invitations.accept', $secondToken));

        // Only one OrganisationUser and one Member should exist
        $this->assertSame(
            1,
            OrganisationUser::withoutGlobalScopes()
                ->where('organisation_id', $this->organisation->id)
                ->where('user_id', $acceptingUser->id)
                ->count()
        );

        $orgUser = OrganisationUser::withoutGlobalScopes()
            ->where('organisation_id', $this->organisation->id)
            ->where('user_id', $acceptingUser->id)
            ->first();

        $this->assertSame(
            1,
            Member::withoutGlobalScopes()
                ->where('organisation_id', $this->organisation->id)
                ->where('organisation_user_id', $orgUser->id)
                ->count()
        );
    }

    public function test_expired_invitation_cannot_be_accepted(): void
    {
        $this->createInvitation(['expires_at' => now()->subDay()]);
        $acceptingUser = User::factory()->create(['email' => 'invited@example.com']);

        $this->actingAs($acceptingUser)
             ->get(route('organisations.invitations.accept', $this->token))
             ->assertStatus(404);

        $this->assertDatabaseMissing('members', [
            'organisation_id' => $this->organisation->id,
        ]);
    }
}
