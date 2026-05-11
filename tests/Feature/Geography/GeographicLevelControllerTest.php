<?php

declare(strict_types=1);

namespace Tests\Feature\Geography;

use App\Contexts\Geography\Domain\ValueObjects\GeographicStructure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class GeographicLevelControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed countries table for FK constraints
        DB::table('countries')->insert([
            'code' => 'NP',
            'code_alpha3' => 'NPL',
            'code_numeric' => '524',
            'name_en' => 'Nepal',
            'name_local' => json_encode(['np' => 'नेपाल']),
            'admin_levels' => json_encode([
                1 => ['name' => 'Province', 'local_name' => 'प्रदेश', 'count' => 7],
                2 => ['name' => 'District', 'local_name' => 'जिल्ला', 'count' => 77],
                3 => ['name' => 'Local Level', 'local_name' => 'स्थानीय तह', 'count' => 753],
                4 => ['name' => 'Ward', 'local_name' => 'वडा', 'count' => 6743],
            ]),
            'is_active' => true,
            'is_supported' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function defaults_endpoint_returns_nepal_levels(): void
    {
        $response = $this->getJson('/api/geography/levels/NP');

        $response->assertOk();
        $response->assertJsonStructure([
            'levels' => [
                '*' => ['index', 'db_level', 'label'],
            ],
        ]);

        $levels = $response->json('levels');
        $this->assertCount(4, $levels);
        $this->assertEquals(1, $levels[0]['index']);
        $this->assertEquals('Province', $levels[0]['label']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function defaults_endpoint_returns_404_for_unknown_country(): void
    {
        $response = $this->getJson('/api/geography/levels/XX');

        $response->assertNotFound();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function available_endpoint_returns_max_level_for_country(): void
    {
        // Setup: Create geography data with max level 4
        for ($i = 1; $i <= 4; $i++) {
            DB::table('geo_administrative_units')->insert([
                'country_code' => 'NP',
                'admin_level' => $i,
                'admin_type' => 'province',
                'parent_id' => null,
                'code' => "NP-{$i}",
                'name_local' => json_encode(['en' => "Unit {$i}"]),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $response = $this->getJson('/api/geography/levels/NP/available');

        $response->assertOk();
        $response->assertJsonStructure([
            'max_db_level',
            'available_db_levels',
        ]);

        $this->assertEquals(4, $response->json('max_db_level'));
        $this->assertEquals([1, 2, 3, 4], $response->json('available_db_levels'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function available_endpoint_returns_empty_array_when_no_data(): void
    {
        $response = $this->getJson('/api/geography/levels/XX/available');

        $response->assertOk();
        $this->assertNull($response->json('max_db_level'));
        $this->assertEquals([], $response->json('available_db_levels'));
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function available_endpoint_case_insensitive(): void
    {
        // Setup with lowercase code stored
        DB::table('geo_administrative_units')->insert([
            'country_code' => 'NP',
            'admin_level' => 1,
            'admin_type' => 'province',
            'parent_id' => null,
            'code' => 'NP-1',
            'name_local' => json_encode(['en' => 'Unit 1']),
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Query with uppercase
        $response = $this->getJson('/api/geography/levels/NP/available');

        $response->assertOk();
        $this->assertEquals(1, $response->json('max_db_level'));
    }
}
