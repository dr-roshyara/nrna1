<?php

namespace Tests\Feature\Auth;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class VoterInvitationSetPasswordTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private Election $election;
    private User $user;
    private VoterInvitation $invitation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
        session(['current_organisation_id' => $this->organisation->id]);

        $this->election = Election::factory()
            ->for($this->organisation)
            ->real()
            ->create(['slug' => 'test-election']);

        $this->user = User::factory()->unverified()->create(['email' => 'voter@test.com']);

        $this->invitation = VoterInvitation::factory()->create([
            'election_id' => $this->election->id,
            'user_id' => $this->user->id,
            'organisation_id' => $this->organisation->id,
            'token' => Str::random(40),
            'expires_at' => now()->addDays(7),
        ]);
    }

    /** @test */
    public function voter_can_set_password_and_redirects_to_election(): void
    {
        $response = $this->post(route('invitation.store-password', $this->invitation->token), [
            'password' => 'TestPassword123',
            'password_confirmation' => 'TestPassword123',
        ]);

        $this->user->refresh();
        $this->assertTrue(password_verify('TestPassword123', $this->user->password));
        $this->assertNotNull($this->user->email_verified_at);

        // Check that the redirect route has the election slug
        $response->assertRedirect(route('elections.show', $this->election->slug));
    }

    /** @test */
    public function redirect_fails_if_election_slug_is_missing(): void
    {
        // Create an election without a slug
        $election = Election::factory()
            ->for($this->organisation)
            ->real()
            ->create(['slug' => null]);

        $invitation = VoterInvitation::factory()->create([
            'election_id' => $election->id,
            'user_id' => $this->user->id,
            'organisation_id' => $this->organisation->id,
            'token' => Str::random(40),
            'expires_at' => now()->addDays(7),
        ]);

        $response = $this->post(route('invitation.store-password', $invitation->token), [
            'password' => 'TestPassword123',
            'password_confirmation' => 'TestPassword123',
        ]);

        // This should fail or handle the missing slug gracefully
        // Currently it might throw an error
        $response->assertStatus(200); // or check for error
    }
}
