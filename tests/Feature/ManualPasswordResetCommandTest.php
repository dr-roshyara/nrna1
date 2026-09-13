<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * Operational fallback command (app/Console/Commands/ManualPasswordResetCommand.php)
 * for when outbound mail is not working — see
 * docs/election/admin-area/how_to_export_voting_codes.md's neighbour tutorial
 * for the matching how-to.
 */
class ManualPasswordResetCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_prints_a_valid_reset_link_by_default(): void
    {
        // Console-output assertions on the full URL are flaky here: Symfony
        // Console wraps long lines at the terminal width, and the random
        // token's length means the wrap point (and therefore which
        // substring ends up on which "line") varies from run to run. Assert
        // the underlying mechanism instead: a real, usable reset token must
        // exist for this email afterward — the same table
        // Password::broker()->createToken() (and therefore the email flow)
        // writes to.
        User::factory()->create(['email' => 'voter@example.test']);

        $this->artisan('user:reset-link', ['email' => 'voter@example.test'])
            ->assertExitCode(0)
            ->expectsOutputToContain('/reset-password/');

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'voter@example.test',
        ]);
    }

    public function test_set_password_option_sets_password_directly(): void
    {
        $user = User::factory()->create(['email' => 'voter@example.test']);

        $this->artisan('user:reset-link', [
            'email' => 'voter@example.test',
            '--set-password' => 'MyNewPass123',
        ])->assertExitCode(0);

        $this->assertTrue(Hash::check('MyNewPass123', $user->fresh()->password));
    }

    public function test_random_password_option_sets_and_prints_a_password(): void
    {
        $user = User::factory()->create(['email' => 'voter@example.test']);
        $originalHash = $user->password;

        $this->artisan('user:reset-link', [
            'email' => 'voter@example.test',
            '--random-password' => true,
        ])->assertExitCode(0)
          ->expectsOutputToContain('New password:');

        $this->assertNotSame($originalHash, $user->fresh()->password);
    }

    public function test_unknown_email_fails_gracefully(): void
    {
        $this->artisan('user:reset-link', ['email' => 'nobody@example.test'])
            ->assertExitCode(1);
    }
}
