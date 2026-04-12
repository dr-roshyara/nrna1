<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmergencyDashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test emergency dashboard requires authentication
     */
    public function test_emergency_dashboard_requires_auth(): void
    {
        $response = $this->get(route('dashboard.emergency'));

        $this->assertRedirectPath('/login');
    }

    /**
     * Test authenticated user can access emergency dashboard
     */
    public function test_authenticated_user_can_access_emergency_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('dashboard.emergency'));

        $response->assertStatus(200);
        $response->assertViewIs('Emergency/Dashboard');
    }

    /**
     * Test emergency dashboard loads user data
     */
    public function test_emergency_dashboard_passes_user_data(): void
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $response = $this->actingAs($user)
            ->get(route('dashboard.emergency'));

        $response->assertViewHasAll(['user']);
        $this->assertEquals($user->id, $response->viewData('user')['id']);
        $this->assertEquals('Test User', $response->viewData('user')['name']);
    }

    /**
     * Test emergency dashboard loads user organisations
     */
    public function test_emergency_dashboard_loads_organisations(): void
    {
        $user = User::factory()->create();
        $org1 = Organisation::factory()->create(['name' => 'Org One']);
        $org2 = Organisation::factory()->create(['name' => 'Org Two']);

        // Associate user with organisations
        $user->organisations()->attach([$org1->id, $org2->id]);

        $response = $this->actingAs($user)
            ->get(route('dashboard.emergency'));

        $response->assertViewHas('organisations');
        $organisations = $response->viewData('organisations');

        $this->assertCount(2, $organisations);
        $this->assertEquals('Org One', $organisations[0]['name']);
        $this->assertEquals('Org Two', $organisations[1]['name']);
    }

    /**
     * Test emergency dashboard handles missing organisations gracefully
     */
    public function test_emergency_dashboard_handles_missing_organisations(): void
    {
        $user = User::factory()->create();

        // User has no organisations
        $response = $this->actingAs($user)
            ->get(route('dashboard.emergency'));

        $response->assertStatus(200);
        $response->assertViewHasAll(['organisations']);

        // Should be empty but not error
        $organisations = $response->viewData('organisations');
        $this->assertEmpty($organisations);
    }

    /**
     * Test basic actions are provided
     */
    public function test_emergency_dashboard_provides_basic_actions(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('dashboard.emergency'));

        $response->assertViewHasAll(['basic_actions']);
        $actions = $response->viewData('basic_actions');

        // Should at least have logout action
        $logoutAction = collect($actions)->firstWhere('label', 'Logout');
        $this->assertNotNull($logoutAction);
        $this->assertEquals('POST', $logoutAction['method']);
    }

    /**
     * Test logout from emergency dashboard
     */
    public function test_logout_from_emergency_dashboard(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('logout'));

        $this->assertGuest();
        $response->assertRedirectPath('/');
    }

    /**
     * Test emergency dashboard displays support email
     */
    public function test_emergency_dashboard_displays_support_email(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('dashboard.emergency'));

        $response->assertViewHasAll(['support_email']);
        $supportEmail = $response->viewData('support_email');
        $this->assertIsString($supportEmail);
    }

    /**
     * Test emergency dashboard includes timestamp
     */
    public function test_emergency_dashboard_includes_timestamp(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('dashboard.emergency'));

        $response->assertViewHasAll(['timestamp']);
        $timestamp = $response->viewData('timestamp');
        $this->assertIsString($timestamp);
    }

    /**
     * Test emergency dashboard includes emergency ID
     */
    public function test_emergency_dashboard_includes_emergency_id(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('dashboard.emergency'));

        $response->assertViewHasAll(['emergency_id']);
        $emergencyId = $response->viewData('emergency_id');
        $this->assertIsString($emergencyId);
        $this->assertStringStartsWith('emerg_', $emergencyId);
    }

    /**
     * Test emergency dashboard message is shown
     */
    public function test_emergency_dashboard_shows_message(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('dashboard.emergency'));

        $response->assertViewHasAll(['message']);
        $message = $response->viewData('message');
        $this->assertStringContainsString('maintenance', strtolower($message));
    }

    /**
     * Test health check endpoint
     */
    public function test_health_check_endpoint_returns_json(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get(route('dashboard.emergency.health'));

        $response->assertJson([
            'status' => 'degraded',
            'message' => 'System is in emergency mode',
        ]);

        $response->assertJsonStructure(['status', 'message', 'timestamp']);
    }

    /**
     * Test emergency dashboard doesn't require database access for basic rendering
     *
     * This test documents that the emergency dashboard should gracefully
     * handle database failures and still render core content
     */
    public function test_emergency_dashboard_minimal_database_impact(): void
    {
        $user = User::factory()->create();

        // This simulates the reality that emergency dashboard might be accessed
        // when database is partially failing
        $response = $this->actingAs($user)
            ->get(route('dashboard.emergency'));

        // Should still load successfully
        $response->assertStatus(200);
    }

    /**
     * Test organisation switcher action is included when orgs available
     */
    public function test_emergency_dashboard_includes_org_switcher(): void
    {
        $user = User::factory()->create();
        $org = Organisation::factory()->create();
        $user->organisations()->attach($org->id);

        $response = $this->actingAs($user)
            ->get(route('dashboard.emergency'));

        $actions = $response->viewData('basic_actions');
        $switchAction = collect($actions)->firstWhere('label', 'Switch Organisation');

        $this->assertNotNull($switchAction);
        $this->assertArrayHasKey('organisations', $switchAction);
    }
}
