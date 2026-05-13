<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Ports;

use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdiction;
use PHPUnit\Framework\TestCase;

final class GeographicJurisdictionTest extends TestCase
{
    public function test_can_be_constructed_with_valid_values(): void
    {
        $jurisdiction = new GeographicJurisdiction(
            geoUnitId: 42,
            adminLevel: 2,
            regionCode: 'asia',
            countryCode: 'NP',
        );

        $this->assertSame(42, $jurisdiction->geoUnitId);
        $this->assertSame(2, $jurisdiction->adminLevel);
        $this->assertSame('asia', $jurisdiction->regionCode);
        $this->assertSame('NP', $jurisdiction->countryCode);
    }

    public function test_is_readonly_cannot_be_modified(): void
    {
        $jurisdiction = new GeographicJurisdiction(
            geoUnitId: 1,
            adminLevel: 3,
            regionCode: 'europe',
            countryCode: 'DE',
        );

        $this->assertTrue(property_exists($jurisdiction, 'geoUnitId'));
        $this->assertTrue(property_exists($jurisdiction, 'adminLevel'));
        $this->assertTrue(property_exists($jurisdiction, 'regionCode'));
        $this->assertTrue(property_exists($jurisdiction, 'countryCode'));
    }

    public function test_two_instances_with_same_values_are_equal(): void
    {
        $a = new GeographicJurisdiction(1, 2, 'asia', 'NP');
        $b = new GeographicJurisdiction(1, 2, 'asia', 'NP');

        $this->assertEquals($a, $b);
    }

    public function test_two_instances_with_different_values_are_not_equal(): void
    {
        $a = new GeographicJurisdiction(1, 2, 'asia', 'NP');
        $b = new GeographicJurisdiction(2, 3, 'europe', 'DE');

        $this->assertNotEquals($a, $b);
    }
}
