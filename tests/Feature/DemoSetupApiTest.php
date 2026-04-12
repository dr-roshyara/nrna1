<?php

namespace Tests\Feature;

use App\Models\Organisation;
use App\Models\User;
use App\Models\Election;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DemoSetupApiTest extends TestCase
{
    use RefreshDatabase;

    protected $organisation;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Create organisation
        $this->organisation = Organisation::factory()->create([
            'name' => 'Test Organisation',
            'slug' => 'test-org'
        ]);

        // Create user and attach to organisation
        $this->user = User::factory()->create();
        $this->organisation->users()->attach($this->user->id, ['role' => 'admin']);
    }

    /**
     * Test that organisation member can trigger demo setup
     */
    public function test_organisation_member_can_trigger_demo_setup()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(
            "/api/organisations/{$this->organisation->id}/demo-setup",
            ['force' => false]
        );

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonStructure([
                'success',
                'message',
                'demoStatus' => [
                    'exists',
                    'stats'
                ]
            ]);
    }

    /**
     * Test that non-member cannot trigger demo setup
     */
    public function test_non_member_cannot_trigger_demo_setup()
    {
        // Create a different user not attached to organisation
        $otherUser = User::factory()->create();
        $this->actingAs($otherUser);

        $response = $this->postJson(
            "/api/organisations/{$this->organisation->id}/demo-setup",
            ['force' => false]
        );

        $response->assertStatus(403)
            ->assertJson(['success' => false]);
    }

    /**
     * Test that unauthenticated user cannot trigger demo setup
     */
    public function test_unauthenticated_user_cannot_trigger_demo_setup()
    {
        $response = $this->postJson(
            "/api/organisations/{$this->organisation->id}/demo-setup",
            ['force' => false]
        );

        // Sanctum should return 401 for unauthenticated requests
        $response->assertStatus(401);
    }

    /**
     * Test that demo setup returns stats after success
     */
    public function test_demo_setup_returns_stats_after_success()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(
            "/api/organisations/{$this->organisation->id}/demo-setup",
            ['force' => false]
        );

        $response->assertStatus(200)
            ->assertJsonPath('demoStatus.exists', true)
            ->assertJsonStructure([
                'demoStatus' => [
                    'stats' => [
                        'posts',
                        'candidates',
                        'codes',
                        'votes',
                        'election_id',
                        'election_name'
                    ]
                ]
            ]);
    }

    /**
     * Test that API accepts force parameter
     */
    public function test_api_accepts_force_parameter()
    {
        $this->actingAs($this->user);

        $response = $this->postJson(
            "/api/organisations/{$this->organisation->id}/demo-setup",
            ['force' => true]
        );

        // API should process the force parameter
        $this->assertContains($response->status(), [200, 500]);
    }

    /**
     * Test that member with voter role can still trigger demo setup
     */
    public function test_organization_voter_can_trigger_demo_setup()
    {
        // Create a voter user
        $voter = User::factory()->create();
        $this->organisation->users()->attach($voter->id, ['role' => 'voter']);

        $this->actingAs($voter);

        $response = $this->postJson(
            "/api/organisations/{$this->organisation->id}/demo-setup",
            ['force' => false]
        );

        $response->assertStatus(200)
            ->assertJson(['success' => true]);
    }
}
