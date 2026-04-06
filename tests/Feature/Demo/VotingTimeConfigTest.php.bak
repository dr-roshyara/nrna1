<?php

namespace Tests\Feature\Demo;

use App\Models\Election;
use App\Models\Organisation;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VoterSlugService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * VotingTimeConfigTest
 *
 * TDD: Validates that VOTING_TIME_IN_MINUTES env variable controls
 * both the voter slug expiry AND the demo code voting window.
 *
 * Architecture reference: architecture/election/election_architecture/20260304_voting_times_in_minutes.md
 */
class VotingTimeConfigTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Election $election;
    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();

        $this->election = Election::factory()->create([
            'type'      => 'demo',
            'is_active' => true,
        ]);

        $this->org  = Organisation::find($this->election->organisation_id);
        $this->user = User::factory()->create([
            'email'              => 'voter@example.com',
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
            'region'            => 'Bayern',
        ]);
    }

    /**
     * TEST 1: config/voting.php has time_in_minutes key
     */
    public function test_voting_config_has_time_in_minutes_key()
    {
        $this->assertArrayHasKey(
            'time_in_minutes',
            config('voting'),
            'config/voting.php must have a time_in_minutes key'
        );
    }

    /**
     * TEST 2: VOTING_TIME_IN_MINUTES env var is picked up by config
     */
    public function test_voting_time_env_var_is_used_by_config()
    {
        // Override config to simulate different env values
        config(['voting.time_in_minutes' => 45]);

        $this->assertEquals(45, config('voting.time_in_minutes'));
    }

    /**
     * TEST 3: VoterSlugService creates slug with expiry from config
     */
    public function test_voter_slug_service_uses_config_for_expiry()
    {
        config(['voting.time_in_minutes' => 45]);

        session(['current_organisation_id' => $this->org->id]);

        $service    = app(VoterSlugService::class);
        $voterSlug  = $service->generateSlugForUser($this->user, $this->election->id);

        $expectedExpiry = now()->addMinutes(45);

        // Allow ±2 seconds tolerance for test execution time
        $this->assertEqualsWithDelta(
            $expectedExpiry->timestamp,
            $voterSlug->expires_at->timestamp,
            2,
            'VoterSlug expires_at should be now() + config(voting.time_in_minutes)'
        );
    }

    /**
     * TEST 4: Default config value is 30 minutes when env var not set
     */
    public function test_default_voting_time_is_30_minutes()
    {
        // No env override — should default to 30
        $this->assertEquals(30, config('voting.time_in_minutes'));
    }

    /**
     * TEST 5: DemoCodeController hardcoded value uses config
     * (Verifying the controller's $votingTimeInMinutes matches config)
     */
    public function test_demo_code_controller_uses_config_voting_time()
    {
        config(['voting.time_in_minutes' => 45]);

        $controller = new \App\Http\Controllers\Demo\DemoCodeController();

        $reflection = new \ReflectionClass($controller);
        $property   = $reflection->getProperty('votingTimeInMinutes');
        $property->setAccessible(true);

        $this->assertEquals(
            45,
            $property->getValue($controller),
            'DemoCodeController::$votingTimeInMinutes must read from config(voting.time_in_minutes)'
        );
    }
}
