<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use App\Contexts\Geography\Application\Services\GeoReferenceBuilder;
use App\Contexts\Geography\Domain\ValueObjects\GeographicStructure;
use PHPUnit\Framework\TestCase;

final class GeoReferenceBuilderTest extends TestCase
{
    private GeoReferenceBuilder $builder;

    protected function setUp(): void
    {
        parent::setUp();
        $this->builder = new GeoReferenceBuilder();
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function build_composite_from_valid_selections(): void
    {
        $structure = GeographicStructure::forWorldwideOrg();

        $selections = [
            'region' => 'europe',
            'country' => 'de',
            'geo' => [3, 15],
        ];

        $ref = $this->builder->build($selections, $structure);

        $this->assertSame('europe', $ref->region);
        $this->assertSame('DE', $ref->getCountryCode());
        $this->assertSame([3, 15], $ref->geoPath->toArray());
        $this->assertFalse($ref->isLegacy());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function build_legacy_when_only_country_and_geo_provided(): void
    {
        $structure = GeographicStructure::forNepal();

        $selections = [
            'country' => 'np',
            'geo' => [3, 15],
        ];

        $ref = $this->builder->build($selections, $structure);

        $this->assertSame('NP', $ref->getCountryCode());
        $this->assertSame([3, 15], $ref->geoPath->toArray());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function throws_when_required_region_missing(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Required level 'Region' (region) not selected");

        $structure = GeographicStructure::forWorldwideOrg();

        $selections = [
            'country' => 'de',
            'geo' => [3],
        ];

        $this->builder->build($selections, $structure);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function throws_when_required_country_missing(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage("Required level 'Country' (country) not selected");

        $structure = GeographicStructure::forWorldwideOrg();

        $selections = [
            'region' => 'europe',
            'geo' => [3],
        ];

        $this->builder->build($selections, $structure);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function build_accepts_partial_for_optional_levels(): void
    {
        $structure = GeographicStructure::forWorldwideOrg();

        $selections = [
            'region' => 'europe',
            'country' => 'de',
            // geo is optional in worldwide structure (level 4, required=false)
        ];

        $ref = $this->builder->build($selections, $structure);

        $this->assertSame('europe', $ref->region);
        $this->assertSame('DE', $ref->getCountryCode());
        $this->assertTrue($ref->geoPath->isEmpty());
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function built_reference_toString_matches_expected(): void
    {
        $structure = GeographicStructure::forWorldwideOrg();

        $selections = [
            'region' => 'europe',
            'country' => 'de',
            'geo' => [3, 15],
        ];

        $ref = $this->builder->build($selections, $structure);

        $this->assertSame('region:europe.country:DE.geo:3.15', $ref->toString());
    }
}
