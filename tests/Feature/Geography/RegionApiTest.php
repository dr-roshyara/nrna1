<?php

declare(strict_types=1);

namespace Tests\Feature\Geography;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class RegionApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seedTestCountries();
    }

    private function seedTestCountries(): void
    {
        DB::table('countries')->insert([
            ['code' => 'DE', 'code_alpha3' => 'DEU', 'code_numeric' => '276', 'name_en' => 'Germany', 'name_local' => json_encode(['de' => 'Deutschland']), 'admin_levels' => json_encode([]), 'is_active' => true, 'is_supported' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'FR', 'code_alpha3' => 'FRA', 'code_numeric' => '250', 'name_en' => 'France', 'name_local' => json_encode(['fr' => 'France']), 'admin_levels' => json_encode([]), 'is_active' => true, 'is_supported' => true, 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'NP', 'code_alpha3' => 'NPL', 'code_numeric' => '524', 'name_en' => 'Nepal', 'name_local' => json_encode(['np' => 'नेपाल']), 'admin_levels' => json_encode([]), 'is_active' => true, 'is_supported' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function regions_index_returns_active_regions(): void
    {
        $this->artisan('db:seed', ['--class' => 'RegionSeeder']);

        $response = $this->getJson('/api/geography/regions');

        $response->assertStatus(200);
        $this->assertGreaterThan(0, count($response->json('data')));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function regions_index_correct_fields(): void
    {
        $this->artisan('db:seed', ['--class' => 'RegionSeeder']);

        $response = $this->getJson('/api/geography/regions');

        $data = $response->json('data');
        $this->assertIsArray($data);
        $this->assertNotEmpty($data);

        $region = $data[0];
        $this->assertArrayHasKey('id', $region);
        $this->assertArrayHasKey('code', $region);
        $this->assertArrayHasKey('name', $region);
        $this->assertIsString($region['code']);
        $this->assertIsString($region['name']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function regions_countries_returns_countries_for_region(): void
    {
        $this->artisan('db:seed', ['--class' => 'RegionSeeder']);

        $response = $this->getJson('/api/geography/regions/europe/countries');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertIsArray($data);
        $this->assertGreaterThan(0, count($data));

        // Verify country fields
        if (!empty($data)) {
            $country = $data[0];
            $this->assertArrayHasKey('code', $country);
            $this->assertArrayHasKey('name_en', $country);
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function regions_countries_404_for_unknown_region(): void
    {
        $response = $this->getJson('/api/geography/regions/unknown-region/countries');

        $response->assertStatus(404);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function regions_countries_empty_when_no_pivot_rows(): void
    {
        $this->artisan('db:seed', ['--class' => 'RegionSeeder']);

        // Create a new region with no countries
        \DB::table('regions')->insert([
            'code' => 'test_region',
            'name' => 'Test Region',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->getJson('/api/geography/regions/test_region/countries');

        $response->assertStatus(200);
        $data = $response->json('data');
        $this->assertIsArray($data);
        $this->assertEmpty($data);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function regions_index_throttled_at_100_req_per_minute(): void
    {
        $this->artisan('db:seed', ['--class' => 'RegionSeeder']);

        // Throttle limit is 100 requests per 60 seconds
        // Make 101 requests to exceed limit
        for ($i = 0; $i < 101; $i++) {
            $response = $this->getJson('/api/geography/regions');
            if ($i < 100) {
                $this->assertTrue($response->status() === 200, "Request {$i} should succeed");
            } else {
                $this->assertTrue($response->status() === 429, "Request {$i} should be throttled (429)");
                break;
            }
        }
    }
}
