<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Geography\Domain\ValueObjects\GeographicLevelConfig;
use App\Contexts\Geography\Domain\ValueObjects\GeographicStructure;
use PHPUnit\Framework\TestCase;

class GeoReferenceValidUnitIdsTest extends TestCase
{
    public function test_get_valid_unit_ids_returns_only_positive_integers(): void
    {
        $geo = GeoReference::fromString('np.1.2.3');

        $ids = $geo->getValidUnitIds();

        $this->assertEquals([1, 2, 3], $ids);
        foreach ($ids as $id) {
            $this->assertGreaterThan(0, $id);
        }
    }

    public function test_get_valid_unit_ids_skips_zero_and_nulls(): void
    {
        $geo = GeoReference::fromString('np.1.0.3');

        $ids = $geo->getValidUnitIds();

        $this->assertEquals([1, 3], $ids);
        $this->assertNotContains(0, $ids);
    }

    public function test_is_complete_for_structure_true_when_all_required_levels_filled(): void
    {
        $geo = GeoReference::fromString('np.1.2.3.4');
        $structure = GeographicStructure::forNepal();

        $this->assertTrue($geo->isCompleteForStructure($structure));
    }

    public function test_is_complete_for_structure_false_when_required_level_missing(): void
    {
        $geo = GeoReference::fromString('np.1.0.3.4');
        $structure = GeographicStructure::forNepal();

        $this->assertFalse($geo->isCompleteForStructure($structure));
    }
}
