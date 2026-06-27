<?php

declare(strict_types=1);

namespace Tests\Unit\Models;

use App\Contexts\Geography\Domain\ValueObjects\GeographicStructure;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganisationGeographicLevelsTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_geographic_structure_returns_nepal_default_when_null(): void
    {
        $org = Organisation::factory()->create(['geographic_levels' => null]);

        $structure = $org->getGeographicStructure();

        $this->assertInstanceOf(GeographicStructure::class, $structure);
        $this->assertEquals(4, $structure->getMaxDepth());
    }

    public function test_set_geographic_levels_persists_json(): void
    {
        // Create org with geographic_levels set during creation
        $structure = GeographicStructure::forNepal();
        $org = Organisation::factory()->create(['geographic_levels' => $structure->toArray()]);

        $reloaded = Organisation::find($org->id);
        $this->assertIsArray($reloaded->geographic_levels);
        $this->assertCount(4, $reloaded->geographic_levels);
    }

    public function test_get_geographic_structure_deserialises_from_db(): void
    {
        $nepalDefault = [
            ['index' => 1, 'db_level' => 1, 'label' => 'Province', 'local_label' => 'प्रदेश', 'required' => true],
            ['index' => 2, 'db_level' => 2, 'label' => 'District', 'local_label' => 'जिल्ला', 'required' => true],
        ];

        $org = Organisation::factory()->create(['geographic_levels' => $nepalDefault]);

        $structure = $org->getGeographicStructure();

        $this->assertInstanceOf(GeographicStructure::class, $structure);
        $this->assertEquals(2, $structure->getMaxDepth());
        $this->assertEquals('Province', $structure->getLevels()[0]->label);
    }

    public function test_geographic_levels_cast_as_array(): void
    {
        $org = Organisation::factory()->create(['geographic_levels' => null]);

        $org->setGeographicLevels(GeographicStructure::forNepal());
        $org->save();

        $reloaded = Organisation::find($org->id);

        $this->assertIsArray($reloaded->geographic_levels);
    }
}
