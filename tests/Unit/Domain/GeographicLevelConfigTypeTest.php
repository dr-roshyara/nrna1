<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Contexts\Geography\Domain\ValueObjects\GeographicLevelConfig;
use PHPUnit\Framework\TestCase;

final class GeographicLevelConfigTypeTest extends TestCase
{
    #[\PHPUnit\Framework\Attributes\Test]
    public function can_create_static_level_with_null_db_level(): void
    {
        $level = new GeographicLevelConfig(
            index: 1,
            type: 'static',
            dbLevel: null,
            label: 'Worldwide',
            localLabel: null,
            required: true
        );

        $this->assertSame('static', $level->type);
        $this->assertNull($level->dbLevel);
        $this->assertSame('Worldwide', $level->label);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function can_create_region_level_with_null_db_level(): void
    {
        $level = new GeographicLevelConfig(
            index: 2,
            type: 'region',
            dbLevel: null,
            label: 'Region',
            localLabel: null,
            required: true
        );

        $this->assertSame('region', $level->type);
        $this->assertNull($level->dbLevel);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function can_create_country_level_with_null_db_level(): void
    {
        $level = new GeographicLevelConfig(
            index: 3,
            type: 'country',
            dbLevel: null,
            label: 'Country',
            localLabel: null,
            required: true
        );

        $this->assertSame('country', $level->type);
        $this->assertNull($level->dbLevel);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function geo_unit_type_requires_non_null_db_level(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('geo_unit type requires non-null db_level');

        new GeographicLevelConfig(
            index: 4,
            type: 'geo_unit',
            dbLevel: null,
            label: 'Province',
            localLabel: null,
            required: false
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function throws_on_invalid_type_string(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid type: invalid_type');

        new GeographicLevelConfig(
            index: 1,
            type: 'invalid_type',
            dbLevel: null,
            label: 'Test',
            localLabel: null,
            required: false
        );
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function fromArray_defaults_type_to_geo_unit_when_key_missing(): void
    {
        $level = GeographicLevelConfig::fromArray([
            'index' => 1,
            'db_level' => 1,
            'label' => 'Province',
            'local_label' => null,
            'required' => false,
        ]);

        $this->assertSame('geo_unit', $level->type);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function toArray_includes_type_field(): void
    {
        $level = new GeographicLevelConfig(
            index: 2,
            type: 'region',
            dbLevel: null,
            label: 'Region',
            localLabel: null,
            required: true
        );

        $array = $level->toArray();

        $this->assertArrayHasKey('type', $array);
        $this->assertSame('region', $array['type']);
    }
}
