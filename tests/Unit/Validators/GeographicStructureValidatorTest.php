<?php

declare(strict_types=1);

namespace Tests\Unit\Validators;

use App\Contexts\Geography\Domain\ValueObjects\GeographicStructure;
use App\Contexts\Geography\Infrastructure\Validators\GeographicStructureValidator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

final class GeographicStructureValidatorTest extends TestCase
{
    use RefreshDatabase;

    private GeographicStructureValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new GeographicStructureValidator();

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
    public function throws_when_no_geography_data_exists_for_country(): void
    {
        $structure = GeographicStructure::forNepal();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("No geography data found for country 'XX'");

        $this->validator->assertValidForCountry($structure, 'XX');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function throws_when_db_level_exceeds_available(): void
    {
        // Setup: Create geography data with max level 3
        for ($i = 1; $i <= 3; $i++) {
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

        // Nepal structure has 4 levels, but geography data only has 3
        $structure = GeographicStructure::forNepal();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('db_level 4 exceeds available levels (max 3)');

        $this->validator->assertValidForCountry($structure, 'NP');
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function passes_when_db_levels_within_available_range(): void
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

        $structure = GeographicStructure::forNepal();

        // Should not throw
        $this->validator->assertValidForCountry($structure, 'NP');
        $this->assertTrue(true);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function case_insensitive_country_code(): void
    {
        // Setup with all 4 levels of Nepal geography data
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

        $structure = GeographicStructure::forNepal();

        // Should work with uppercase
        $this->validator->assertValidForCountry($structure, 'NP');
        $this->assertTrue(true);
    }
}
