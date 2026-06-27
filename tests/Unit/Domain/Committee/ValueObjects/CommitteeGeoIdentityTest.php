<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\ValueObjects;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeGeoIdentity;
use DomainException;
use PHPUnit\Framework\TestCase;

final class CommitteeGeoIdentityTest extends TestCase
{
    public function test_can_be_constructed_with_valid_geo_unit_id(): void
    {
        $identity = new CommitteeGeoIdentity(42);

        $this->assertSame(42, $identity->geoUnitId);
    }

    public function test_throws_for_negative_geo_unit_id(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('geoUnitId must be a positive integer');

        new CommitteeGeoIdentity(-1);
    }

    public function test_throws_for_zero_geo_unit_id(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('geoUnitId must be a positive integer');

        new CommitteeGeoIdentity(0);
    }

    public function test_two_identical_identities_are_equal(): void
    {
        $a = new CommitteeGeoIdentity(42);
        $b = new CommitteeGeoIdentity(42);

        $this->assertTrue($a->equals($b));
    }

    public function test_two_different_identities_are_not_equal(): void
    {
        $a = new CommitteeGeoIdentity(42);
        $b = new CommitteeGeoIdentity(99);

        $this->assertFalse($a->equals($b));
    }
}
