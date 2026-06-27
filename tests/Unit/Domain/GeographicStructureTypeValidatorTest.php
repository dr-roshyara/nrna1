<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Contexts\Geography\Domain\Services\GeographicStructureTypeValidator;
use App\Contexts\Geography\Domain\ValueObjects\GeographicLevelConfig;
use App\Contexts\Geography\Domain\ValueObjects\GeographicStructure;
use PHPUnit\Framework\TestCase;

final class GeographicStructureTypeValidatorTest extends TestCase
{
    private GeographicStructureTypeValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new GeographicStructureTypeValidator();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function valid_worldwide_structure_passes(): void
    {
        $structure = GeographicStructure::forWorldwideOrg();

        // Should not throw
        $this->validator->validate($structure);
        $this->assertTrue(true);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function valid_nepal_all_geo_unit_passes(): void
    {
        $structure = GeographicStructure::forNepal();

        // Should not throw
        $this->validator->validate($structure);
        $this->assertTrue(true);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function throws_on_region_after_country(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('region type cannot appear before country type');

        $structure = new GeographicStructure([
            new GeographicLevelConfig(1, 'country', null, 'Country', null, true),
            new GeographicLevelConfig(2, 'region', null, 'Region', null, true),
        ]);

        $this->validator->validate($structure);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function throws_on_region_after_geo_unit(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('region type cannot appear before geo_unit type');

        $structure = new GeographicStructure([
            new GeographicLevelConfig(1, 'geo_unit', 1, 'Province', null, true),
            new GeographicLevelConfig(2, 'region', null, 'Region', null, true),
        ]);

        $this->validator->validate($structure);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function throws_on_static_not_at_index_1(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('static type must be at index 1');

        $structure = new GeographicStructure([
            new GeographicLevelConfig(1, 'region', null, 'Region', null, true),
            new GeographicLevelConfig(2, 'static', null, 'Worldwide', null, true),
        ]);

        $this->validator->validate($structure);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function constructor_prevents_geo_unit_without_db_level(): void
    {
        // Constructor validates this, so validator doesn't need to
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('geo_unit type requires non-null db_level');

        new GeographicLevelConfig(4, 'geo_unit', null, 'Province', null, true);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function throws_on_non_sequential_indices(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Level indices must be sequential starting at 1');

        $structure = new GeographicStructure([
            new GeographicLevelConfig(1, 'static', null, 'Worldwide', null, true),
            new GeographicLevelConfig(3, 'region', null, 'Region', null, true),
        ]);

        $this->validator->validate($structure);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function constructor_prevents_unknown_type(): void
    {
        // Constructor validates this, so validator doesn't need to
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type: invalid_type');

        new GeographicLevelConfig(1, 'invalid_type', null, 'Test', null, true);
    }
}
