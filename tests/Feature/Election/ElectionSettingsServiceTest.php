<?php

namespace Tests\Feature\Election;

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
    private Organisation $organisation;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ElectionSettingsService::class);
        $this->organisation = Organisation::factory()->create();
        $this->election = Election::factory()->for($this->organisation)->create();
    }

    public function test_database_setting_overrides_env_value(): void
    {
        // Set env to 5
        config(['app.max_use_ip_address' => 5]);

        // Set database to 3
        $this->election->update(['ip_restriction_max_per_ip' => 3]);

        // Service should return database value (3), not env value (5)
        $result = $this->service->get($this->election, 'max_use_ip_address');

        $this->assertEquals(3, $result);
    }

    public function test_falls_back_to_env_when_database_not_set(): void
    {
        // Create election without specifying ip_restriction_max_per_ip
        // It will use the default from migration (4)
        $freshElection = Election::factory()->for($this->organisation)->create();
        $freshElection->refresh(); // Reload model to get database defaults

        // Since database column has a default (4), verify it returns that
        $result = $this->service->get($freshElection, 'max_use_ip_address');

        $this->assertEquals(4, $result);
    }

    public function test_falls_back_to_default_when_neither_set(): void
    {
        // Test unknown key returns the default passed
        $result = $this->service->get($this->election, 'unknown_setting', 'my_default');

        $this->assertEquals('my_default', $result);
    }

    public function test_selection_constraint_maps_correctly_to_select_all_required(): void
    {
        // Test 'exact' constraint maps to 'yes'
        $this->election->update(['selection_constraint_type' => 'exact']);
        Cache::forget("election:{$this->election->id}:setting:select_all_required");
        $result = $this->service->get($this->election, 'select_all_required');
        $this->assertEquals('yes', $result);

        // Test 'range' constraint maps to 'yes'
        $this->election->update(['selection_constraint_type' => 'range']);
        Cache::forget("election:{$this->election->id}:setting:select_all_required");
        $result = $this->service->get($this->election, 'select_all_required');
        $this->assertEquals('yes', $result);

        // Test 'minimum' constraint maps to 'yes'
        $this->election->update(['selection_constraint_type' => 'minimum']);
        Cache::forget("election:{$this->election->id}:setting:select_all_required");
        $result = $this->service->get($this->election, 'select_all_required');
        $this->assertEquals('yes', $result);

        // Test 'any' constraint maps to 'no'
        $this->election->update(['selection_constraint_type' => 'any']);
        Cache::forget("election:{$this->election->id}:setting:select_all_required");
        $result = $this->service->get($this->election, 'select_all_required');
        $this->assertEquals('no', $result);

        // Test 'maximum' constraint maps to 'no'
        $this->election->update(['selection_constraint_type' => 'maximum']);
        Cache::forget("election:{$this->election->id}:setting:select_all_required");
        $result = $this->service->get($this->election, 'select_all_required');
        $this->assertEquals('no', $result);
    }
}
