<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Factories;

use App\Contexts\Membership\Domain\Committee\Factories\CommitteeGeoIdentityFactory;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeGeoIdentity;
use DomainException;
use PHPUnit\Framework\TestCase;

final class CommitteeGeoIdentityFactoryTest extends TestCase
{
    private CommitteeGeoIdentityFactory $factory;

    protected function setUp(): void
    {
        $this->factory = new CommitteeGeoIdentityFactory();
    }

    public function test_create_with_valid_id_returns_identity(): void
    {
        $identity = $this->factory->create(42);

        $this->assertInstanceOf(CommitteeGeoIdentity::class, $identity);
        $this->assertSame(42, $identity->geoUnitId);
    }

    public function test_create_with_invalid_id_forwards_exception(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('geoUnitId must be a positive integer');

        $this->factory->create(0);
    }
}
