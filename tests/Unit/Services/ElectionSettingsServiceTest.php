<?php

namespace Tests\Unit\Services;

use App\Models\Election;
use App\Models\Organisation;
use App\Services\ElectionSettingsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class ElectionSettingsServiceTest extends TestCase
{
    use RefreshDatabase;

    private ElectionSettingsService $service;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(ElectionSettingsService::class);
        $this->election = Election::factory()
            ->for(Organisation::getDefaultPlatform())
            ->create();
    }

    /** @test */
    public function it_returns_database_value_over_env(): void
    {
        $this->election->update(['ip_restriction_enabled' => true]);
        putenv('CONTROL_IP_ADDRESS=false');

        $result = $this->service->get($this->election, 'control_ip_address');

        $this->assertTrue($result);
    }

    /** @test */
    public function it_caches_settings_with_configurable_ttl(): void
    {
        config()->set('election.settings_cache_ttl', 600);

        // Clear cache first
        Cache::flush();

        // First call should cache the value
        $result1 = $this->service->get($this->election, 'control_ip_address', 'default');

        // Value should now be cached
        $this->assertTrue(Cache::has("election:{$this->election->id}:setting:control_ip_address"));

        // Second call should return cached value immediately without hitting callback
        $result2 = $this->service->get($this->election, 'control_ip_address', 'different_default');

        // Both results should be identical (same cached value)
        $this->assertEquals($result1, $result2);
    }

    /** @test */
    public function it_maps_selection_constraint_exact_to_yes(): void
    {
        $this->election->update(['selection_constraint_type' => 'exact']);

        $result = $this->service->get($this->election, 'select_all_required');

        $this->assertEquals('yes', $result);
    }

    /** @test */
    public function it_maps_selection_constraint_range_to_yes(): void
    {
        $this->election->update(['selection_constraint_type' => 'range']);

        $result = $this->service->get($this->election, 'select_all_required');

        $this->assertEquals('yes', $result);
    }

    /** @test */
    public function it_maps_selection_constraint_minimum_to_yes(): void
    {
        $this->election->update(['selection_constraint_type' => 'minimum']);

        $result = $this->service->get($this->election, 'select_all_required');

        $this->assertEquals('yes', $result);
    }

    /** @test */
    public function it_maps_selection_constraint_any_to_no(): void
    {
        $this->election->update(['selection_constraint_type' => 'any']);

        $result = $this->service->get($this->election, 'select_all_required');

        $this->assertEquals('no', $result);
    }

    /** @test */
    public function it_maps_selection_constraint_maximum_to_no(): void
    {
        $this->election->update(['selection_constraint_type' => 'maximum']);

        $result = $this->service->get($this->election, 'select_all_required');

        $this->assertEquals('no', $result);
    }

    /** @test */
    public function it_handles_invalid_key_with_default(): void
    {
        $result = $this->service->get($this->election, 'invalid_key', 'default_value');

        $this->assertEquals('default_value', $result);
    }

    /** @test */
    public function it_provides_ip_whitelist_setting(): void
    {
        $whitelist = ['192.168.1.0/24', '10.0.0.1'];
        $this->election->update(['ip_whitelist' => $whitelist]);

        $result = $this->service->get($this->election, 'ip_whitelist');

        $this->assertEquals($whitelist, $result);
    }

    /** @test */
    public function it_provides_no_vote_enabled_setting(): void
    {
        $this->election->update(['no_vote_option_enabled' => true]);

        $result = $this->service->get($this->election, 'no_vote_enabled');

        $this->assertTrue($result);
    }

    /** @test */
    public function it_provides_no_vote_label_setting(): void
    {
        $this->election->update(['no_vote_option_label' => 'Abstain']);

        $result = $this->service->get($this->election, 'no_vote_label');

        $this->assertEquals('Abstain', $result);
    }

    /** @test */
    public function it_invalidates_cache_on_election_update(): void
    {
        Cache::put("election:{$this->election->id}:setting:control_ip_address", true, 3600);

        $this->assertTrue(Cache::has("election:{$this->election->id}:setting:control_ip_address"));

        $this->election->update(['ip_restriction_enabled' => false]);

        $this->assertFalse(Cache::has("election:{$this->election->id}:setting:control_ip_address"));
    }
}
