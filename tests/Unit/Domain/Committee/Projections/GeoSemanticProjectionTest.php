<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Projections;

use App\Contexts\Membership\Domain\Committee\Projections\GeoSemanticProjection;
use DomainException;
use PHPUnit\Framework\TestCase;

final class GeoSemanticProjectionTest extends TestCase
{
    public function test_can_be_constructed_with_all_fields(): void
    {
        $projection = new GeoSemanticProjection(
            geoUnitId: 42,
            adminLevel: 2,
            regionCode: 'asia',
            countryCode: 'NP',
        );

        $this->assertSame(42, $projection->geoUnitId);
        $this->assertSame(2, $projection->adminLevel);
        $this->assertSame('asia', $projection->regionCode);
        $this->assertSame('NP', $projection->countryCode);
    }

    public function test_throws_for_invalid_geo_unit_id(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('geoUnitId must be a positive integer');

        new GeoSemanticProjection(
            geoUnitId: 0,
            adminLevel: 1,
            regionCode: '',
            countryCode: 'NP',
        );
    }

    public function test_throws_for_invalid_admin_level(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('adminLevel must be between 0 and 10');

        new GeoSemanticProjection(
            geoUnitId: 42,
            adminLevel: 11,
            regionCode: '',
            countryCode: 'NP',
        );
    }

    public function test_two_projections_with_same_values_are_equal(): void
    {
        $a = new GeoSemanticProjection(42, 2, 'asia', 'NP');
        $b = new GeoSemanticProjection(42, 2, 'asia', 'NP');

        $this->assertTrue($a->equals($b));
    }

    public function test_two_projections_with_different_values_are_not_equal(): void
    {
        $a = new GeoSemanticProjection(42, 2, 'asia', 'NP');
        $b = new GeoSemanticProjection(99, 3, 'europe', 'DE');

        $this->assertFalse($a->equals($b));
    }

    public function test_can_accept_empty_region_code(): void
    {
        $projection = new GeoSemanticProjection(1, 0, '', 'NP');

        $this->assertSame('', $projection->regionCode);
    }

    public function test_admin_level_zero_is_valid(): void
    {
        $projection = new GeoSemanticProjection(1, 0, 'asia', '');
        $this->assertSame(0, $projection->adminLevel);
    }

    public function test_admin_level_ten_is_valid(): void
    {
        $projection = new GeoSemanticProjection(1, 10, '', '');
        $this->assertSame(10, $projection->adminLevel);
    }
}
