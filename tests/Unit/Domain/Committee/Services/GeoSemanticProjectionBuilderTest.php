<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Services;

use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdiction;
use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdictionProvider;
use App\Contexts\Membership\Domain\Committee\Projections\GeoSemanticProjection;
use App\Contexts\Membership\Domain\Committee\Services\GeoSemanticProjectionBuilder;
use PHPUnit\Framework\TestCase;

final class GeoSemanticProjectionBuilderTest extends TestCase
{
    public function test_build_with_valid_id_returns_projection(): void
    {
        $provider = $this->createMock(GeographicJurisdictionProvider::class);
        $provider->expects($this->once())
            ->method('resolve')
            ->with(42)
            ->willReturn(new GeographicJurisdiction(
                geoUnitId: 42,
                adminLevel: 2,
                regionCode: 'asia',
                countryCode: 'NP',
            ));

        $builder = new GeoSemanticProjectionBuilder($provider);
        $projection = $builder->build(42);

        $this->assertInstanceOf(GeoSemanticProjection::class, $projection);
        $this->assertSame(42, $projection->geoUnitId);
        $this->assertSame(2, $projection->adminLevel);
        $this->assertSame('asia', $projection->regionCode);
        $this->assertSame('NP', $projection->countryCode);
    }

    public function test_build_with_unresolvable_id_returns_null(): void
    {
        $provider = $this->createMock(GeographicJurisdictionProvider::class);
        $provider->expects($this->once())
            ->method('resolve')
            ->with(999)
            ->willReturn(null);

        $builder = new GeoSemanticProjectionBuilder($provider);
        $projection = $builder->build(999);

        $this->assertNull($projection);
    }

    public function test_build_delegates_to_provider(): void
    {
        $provider = $this->createMock(GeographicJurisdictionProvider::class);
        $provider->expects($this->once())
            ->method('resolve')
            ->with(1);

        $builder = new GeoSemanticProjectionBuilder($provider);
        $builder->build(1);
    }

    public function test_build_maps_all_fields_correctly(): void
    {
        $provider = $this->createMock(GeographicJurisdictionProvider::class);
        $provider->method('resolve')
            ->with(7)
            ->willReturn(new GeographicJurisdiction(
                geoUnitId: 7,
                adminLevel: 4,
                regionCode: 'europe',
                countryCode: 'DE',
            ));

        $builder = new GeoSemanticProjectionBuilder($provider);
        $projection = $builder->build(7);

        $expected = new GeoSemanticProjection(7, 4, 'europe', 'DE');
        $this->assertTrue($projection->equals($expected));
    }
}
