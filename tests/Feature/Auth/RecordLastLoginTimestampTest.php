<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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
}
