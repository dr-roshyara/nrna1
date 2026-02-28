<?php

namespace Tests\Feature;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\URL;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_email_verification_screen_can_be_rendered()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $response = $this->actingAs($user)->get('/email/verify');

        $response->assertStatus(200);
    }

    public function test_email_can_be_verified()
    {
        Event::fake();

        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        Event::assertDispatched(Verified::class);

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        // First-time user with no roles should redirect to welcome dashboard
        $response->assertRedirect('/dashboard/welcome');
    }

    public function test_email_can_not_verified_with_invalid_hash()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1('wrong-email')]
        );

        $this->actingAs($user)->get($verificationUrl);

        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function first_time_user_redirected_to_welcome_dashboard_after_email_verification()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // Verify user has no roles
        $this->assertEmpty($user->getDashboardRoles());

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        // First-time users should redirect to welcome dashboard
        $response->assertRedirect('/dashboard/welcome');
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }

    /** @test */
    public function existing_user_with_roles_redirected_to_roles_dashboard_after_email_verification()
    {
        $user = User::factory()->create([
            'email_verified_at' => null,
        ]);

        // Create an organization
        $orgSlug = 'test-org-' . time();
        $organizationId = \DB::table('organizations')->insertGetId([
            'name' => 'Test Organization',
            'slug' => $orgSlug,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Assign admin role to give user an organization role
        \DB::table('user_organization_roles')->insert([
            'user_id' => $user->id,
            'organization_id' => $organizationId,
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Verify user has roles
        $this->assertNotEmpty($user->fresh()->getDashboardRoles());

        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        $response = $this->actingAs($user)->get($verificationUrl);

        // Organization admin should redirect to their organization dashboard
        $response->assertRedirect(route('organizations.show', $orgSlug));
        $this->assertTrue($user->fresh()->hasVerifiedEmail());
    }
}
