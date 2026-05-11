<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Contexts\Geography\Domain\ValueObjects\GeographicLevelConfig;
use App\Contexts\Geography\Domain\ValueObjects\GeographicStructure;
use PHPUnit\Framework\TestCase;

class GeographicStructureTest extends TestCase
{
    public function test_can_create_for_nepal_with_4_default_levels(): void
    {
        $structure = GeographicStructure::forNepal();

        $this->assertCount(4, $structure->getLevels());
        $this->assertEquals(4, $structure->getMaxDepth());
    }

    public function test_levels_are_immutable_value_objects(): void
    {
        $structure = GeographicStructure::forNepal();
        $levels = $structure->getLevels();

        $this->assertIsArray($levels);
        $this->assertInstanceOf(GeographicLevelConfig::class, $levels[0]);
    }

    public function test_get_max_depth_returns_level_count(): void
    {
        $structure = GeographicStructure::forNepal();

        $this->assertEquals(4, $structure->getMaxDepth());
    }

    public function test_extend_appends_new_level_returns_new_instance(): void
    {
        $original = GeographicStructure::forNepal();
        $extended = $original->extend(type: 'geo_unit', dbLevel: 5, label: 'Region', localLabel: 'क्षेत्र', required: false);

        $this->assertCount(4, $original->getLevels());
        $this->assertCount(5, $extended->getLevels());
        $this->assertEquals(5, $extended->getMaxDepth());
    }

    public function test_original_instance_unchanged_after_extend(): void
    {
        $original = GeographicStructure::forNepal();
        $originalDepth = $original->getMaxDepth();

        $extended = $original->extend(type: 'geo_unit', dbLevel: 5, label: 'Region', localLabel: 'क्षेत्र', required: false);

        $this->assertEquals(4, $original->getMaxDepth());
        $this->assertEquals(5, $extended->getMaxDepth());
    }

    public function test_cannot_add_level_with_duplicate_index(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Duplicate level indexes');

        new GeographicStructure([
            new GeographicLevelConfig(1, 'geo_unit', 1, 'Province', null, true),
            new GeographicLevelConfig(1, 'geo_unit', 2, 'District', null, true),
        ]);
    }

    public function test_cannot_add_level_with_duplicate_db_level(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Duplicate db_level values');

        new GeographicStructure([
            new GeographicLevelConfig(1, 'geo_unit', 1, 'Province', null, true),
            new GeographicLevelConfig(2, 'geo_unit', 1, 'District', null, true),
        ]);
    }

    public function test_to_array_excludes_count_field(): void
    {
        $structure = GeographicStructure::forNepal();
        $array = $structure->toArray();

        $this->assertIsArray($array);
        $this->assertNotEmpty($array);

        foreach ($array as $level) {
            $this->assertArrayNotHasKey('count', $level);
        }
    }

    public function test_to_array_includes_db_level_field(): void
    {
        $structure = GeographicStructure::forNepal();
        $array = $structure->toArray();

        foreach ($array as $level) {
            $this->assertArrayHasKey('db_level', $level);
            $this->assertIsInt($level['db_level']);
        }
    }

    public function test_from_array_deserialises_correctly(): void
    {
        $data = [
            ['index' => 1, 'db_level' => 1, 'label' => 'Province', 'local_label' => 'प्रदेश', 'required' => true],
            ['index' => 2, 'db_level' => 2, 'label' => 'District', 'local_label' => 'जिल्ला', 'required' => true],
        ];

        $structure = GeographicStructure::fromArray($data);

        $this->assertCount(2, $structure->getLevels());
        $this->assertEquals('Province', $structure->getLevels()[0]->label);
        $this->assertEquals('District', $structure->getLevels()[1]->label);
    }

    public function test_get_levels_array_returns_only_index_db_level_label(): void
    {
        $structure = GeographicStructure::forNepal();
        $levelsArray = $structure->getLevelsArray();

        foreach ($levelsArray as $level) {
            $this->assertArrayHasKey('index', $level);
            $this->assertArrayHasKey('type', $level);
            $this->assertArrayHasKey('db_level', $level);
            $this->assertArrayHasKey('label', $level);
            $this->assertArrayNotHasKey('local_label', $level);
            $this->assertArrayNotHasKey('required', $level);
        }
    }
}
