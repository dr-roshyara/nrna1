<?php

namespace Tests\Feature\Auth;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterInvitation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Covers the new users.last_login_at fact, recorded by
 * App\Listeners\RecordLastLoginTimestamp on Illuminate\Auth\Events\Login —
 * fired by every login entry point (password, Google/social, voter
 * invitation auto-login) via the standard SessionGuard, so one listener
 * covers all of them. Records successful authentication only.
 */
class RecordLastLoginTimestampTest extends TestCase
{
    use RefreshDatabase;

    public function test_successful_login_records_last_login_at(): void
    {
        $user = User::factory()->create([
            'email' => 'voter@example.test',
            'password' => Hash::make('correct-password'),
        ]);

        $this->assertNull($user->last_login_at);

        $response = $this->post('/login', [
            'email' => 'voter@example.test',
            'password' => 'correct-password',
        ]);

        $response->assertSessionHasNoErrors();
        $this->assertNotNull($user->fresh()->last_login_at);
    }

    public function test_failed_login_does_not_record_last_login_at(): void
    {
        $user = User::factory()->create([
            'email' => 'voter@example.test',
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'voter@example.test',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertNull($user->fresh()->last_login_at);
    }

    // PBDIGIT-307: users.current_ip (the last-login IP the "Voter's IP Verification"
    // capture reads) is recorded by the same Login listener, so every login path records it.

    public function test_voter_invitation_login_records_current_ip(): void
    {
        $organisation = Organisation::factory()->create();
        session(['current_organisation_id' => $organisation->id]);
        $election = Election::factory()->for($organisation)->real()->create();
        $user = User::factory()->unverified()->create(['email' => 'voter@example.test']);
        $invitation = VoterInvitation::factory()->create([
            'election_id' => $election->id,
            'user_id' => $user->id,
            'organisation_id' => $organisation->id,
            'token' => Str::random(40),
            'expires_at' => now()->addDays(7),
        ]);

        $this->assertNull($user->current_ip);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.7'])
            ->post(route('invitation.store-password', $invitation->token), [
                'password' => 'Secret123',
                'password_confirmation' => 'Secret123',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertAuthenticatedAs($user);
        $this->assertSame('203.0.113.7', $user->fresh()->current_ip);
    }

    public function test_password_login_records_current_ip(): void
    {
        $user = User::factory()->create([
            'email' => 'voter@example.test',
            'password' => Hash::make('correct-password'),
        ]);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.4'])
            ->post('/login', [
                'email' => 'voter@example.test',
                'password' => 'correct-password',
            ]);

        $response->assertSessionHasNoErrors();
        $this->assertSame('198.51.100.4', $user->fresh()->current_ip);
    }

    public function test_failed_login_does_not_record_current_ip(): void
    {
        $user = User::factory()->create([
            'email' => 'voter@example.test',
            'password' => Hash::make('correct-password'),
            'current_ip' => '192.0.2.1',
        ]);

        $response = $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.4'])
            ->post('/login', [
                'email' => 'voter@example.test',
                'password' => 'wrong-password',
            ]);

        $response->assertSessionHasErrors();
        $this->assertSame('192.0.2.1', $user->fresh()->current_ip);
    }

    public function test_latest_login_ip_replaces_the_previous_one(): void
    {
        $user = User::factory()->create([
            'email' => 'voter@example.test',
            'password' => Hash::make('correct-password'),
        ]);
        $credentials = ['email' => 'voter@example.test', 'password' => 'correct-password'];

        $this->withServerVariables(['REMOTE_ADDR' => '198.51.100.4'])->post('/login', $credentials);
        $this->assertSame('198.51.100.4', $user->fresh()->current_ip);

        Auth::logout();

        $this->withServerVariables(['REMOTE_ADDR' => '203.0.113.9'])->post('/login', $credentials);
        $this->assertSame('203.0.113.9', $user->fresh()->current_ip);
    }
}
