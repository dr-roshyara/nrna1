<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Events\CommitteeFormed;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

class CommitteeTest extends TestCase
{
    private TenantId $tenantId;
    private CommitteeId $committeeId;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenantId = TenantId::fromString('test-tenant-123');
        // Valid ULID format: 26 chars, base32 (0-9, A-Z except I,L,O,U)
        $this->committeeId = CommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV');
    }

    public function test_geographic_committee_requires_geo_reference(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Geographic committees must have an operational geography');

        Committee::createForGeography(
            $this->committeeId,
            $this->tenantId,
            'Ward Committee',
            'WARD-001',
            CommitteeType::ward(),
            null, // No geography — should throw
            'wp'
        );
    }

    public function test_central_committee_rejects_geo_reference(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Central committees must not have an operational geography');

        Committee::createCentral(
            $this->committeeId,
            $this->tenantId,
            'Central Committee',
            'CENTRAL-001',
            GeoReference::fromString('np.1.2.3') // Should not allow geo for central
        );
    }

    public function test_central_committee_created_without_geography(): void
    {
        $committee = Committee::createCentral(
            $this->committeeId,
            $this->tenantId,
            'Central Committee',
            'CENTRAL-001'
        );

        $this->assertSame('CENTRAL-001', $committee->code());
        $this->assertSame('central', $committee->type()->value());
        $this->assertNull($committee->getOperationalGeoReference());
    }

    public function test_geographic_committee_created_with_geo_reference(): void
    {
        $geoRef = GeoReference::fromString('np.3.15');

        $committee = Committee::createForGeography(
            $this->committeeId,
            $this->tenantId,
            'District Committee',
            'DISTRICT-315',
            CommitteeType::district(),
            $geoRef,
            'wp'
        );

        $this->assertSame('DISTRICT-315', $committee->code());
        $this->assertSame('district', $committee->type()->value());
        $this->assertNotNull($committee->getOperationalGeoReference());
        $this->assertTrue($committee->getOperationalGeoReference()->equals($geoRef));
    }

    public function test_committee_formation_records_domain_event(): void
    {
        $committee = Committee::createCentral(
            $this->committeeId,
            $this->tenantId,
            'Central Committee',
            'CENTRAL-001'
        );

        $events = $committee->pullEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(CommitteeFormed::class, $events[0]);
    }

    public function test_role_limit_enforced_by_strategy(): void
    {
        $committee = Committee::createCentral(
            $this->committeeId,
            $this->tenantId,
            'Central Committee',
            'CENTRAL-001'
        );

        $limits = $committee->roleLimits();

        $this->assertSame(1, $limits['chairperson']);
        $this->assertSame(2, $limits['vice_chairperson']);
        $this->assertSame(1, $limits['secretary']);
        $this->assertSame(1, $limits['treasurer']);
        $this->assertNull($limits['member']); // Unlimited
    }

    public function test_assignment_lifecycle_consistency(): void
    {
        $committee = Committee::createCentral(
            $this->committeeId,
            $this->tenantId,
            'Central Committee',
            'CENTRAL-001'
        );

        $this->assertTrue($committee->isValid());
    }
}
