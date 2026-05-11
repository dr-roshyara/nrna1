<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Contexts\Geography\Domain\ValueObjects\GeographicStructure;
use PHPUnit\Framework\TestCase;

final class GeographicStructureWorldwideTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function forWorldwideOrg_returns_4_levels_with_correct_types(): void
    {
        $structure = GeographicStructure::forWorldwideOrg();

        $levels = $structure->getLevels();
        $this->assertCount(4, $levels);

        // Level 1: static (Worldwide)
        $this->assertSame(1, $levels[0]->index);
        $this->assertSame('static', $levels[0]->type);
        $this->assertNull($levels[0]->dbLevel);
        $this->assertSame('Worldwide', $levels[0]->label);
        $this->assertTrue($levels[0]->required);

        // Level 2: region
        $this->assertSame(2, $levels[1]->index);
        $this->assertSame('region', $levels[1]->type);
        $this->assertNull($levels[1]->dbLevel);

        // Level 3: country
        $this->assertSame(3, $levels[2]->index);
        $this->assertSame('country', $levels[2]->type);
        $this->assertNull($levels[2]->dbLevel);

        // Level 4: geo_unit with db_level=1
        $this->assertSame(4, $levels[3]->index);
        $this->assertSame('geo_unit', $levels[3]->type);
        $this->assertSame(1, $levels[3]->dbLevel);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function null_db_levels_allowed_for_non_geo_unit_types(): void
    {
        $structure = GeographicStructure::forWorldwideOrg();

        // Should validate successfully despite static/region/country having null db_levels
        $levels = $structure->getLevels();
        $this->assertNull($levels[0]->dbLevel);
        $this->assertNull($levels[1]->dbLevel);
        $this->assertNull($levels[2]->dbLevel);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function getLevelsArray_includes_type_in_output(): void
    {
        $structure = GeographicStructure::forWorldwideOrg();

        $array = $structure->getLevelsArray();

        $this->assertCount(4, $array);
        $this->assertArrayHasKey('type', $array[0]);
        $this->assertSame('static', $array[0]['type']);
        $this->assertSame('region', $array[1]['type']);
        $this->assertSame('country', $array[2]['type']);
        $this->assertSame('geo_unit', $array[3]['type']);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function fromArray_roundtrips_worldwide_structure(): void
    {
        $structure = GeographicStructure::forWorldwideOrg();
        $array = $structure->toArray();

        $restored = GeographicStructure::fromArray($array);

        $this->assertCount(4, $restored->getLevels());
        $this->assertSame('static', $restored->getLevels()[0]->type);
        $this->assertSame('region', $restored->getLevels()[1]->type);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function forNepal_regression_all_geo_unit(): void
    {
        $structure = GeographicStructure::forNepal();

        $levels = $structure->getLevels();
        foreach ($levels as $level) {
            $this->assertSame('geo_unit', $level->type);
            $this->assertNotNull($level->dbLevel);
        }
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function extend_appends_geo_unit_level(): void
    {
        $structure = GeographicStructure::forWorldwideOrg();

        // Extend with a new geo_unit level
        $extended = $structure->extend(type: 'geo_unit', dbLevel: 2, label: 'District', localLabel: null, required: false);

        $levels = $extended->getLevels();
        $this->assertCount(5, $levels);
        $this->assertSame(5, $levels[4]->index);
        $this->assertSame('geo_unit', $levels[4]->type);
        $this->assertSame(2, $levels[4]->dbLevel);
    }
}
