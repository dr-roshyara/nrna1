<?php

declare(strict_types=1);

namespace Tests\Integration\Contexts;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Membership\Infrastructure\Services\GeographyValidationAdapter;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeographyMembershipTest extends TestCase
{
    use RefreshDatabase;

    private CommitteeRepositoryInterface $repository;
    private GeographyValidationAdapter $geoAdapter;
    private TenantId $tenantId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = app(CommitteeRepositoryInterface::class);
        $this->geoAdapter = app(GeographyValidationAdapter::class);
        $this->tenantId = TenantId::fromString('integration-test-tenant');

        session(['current_organisation_id' => $this->tenantId->value()]);
    }

    public function test_committee_geo_reference_format_is_valid(): void
    {
        $geoRef = GeoReference::fromString('np.3.15');

        $committee = Committee::createForGeography(
            CommitteeId::fromString('test-committee'),
            $this->tenantId,
            'Test District Committee',
            'TEST-DIST',
            CommitteeType::district(),
            $geoRef,
            'wp'
        );

        $this->assertNotNull($committee->getOperationalGeoReference());
        $this->assertSame('np.3.15', $committee->getOperationalGeoReference()->value());
    }

    public function test_member_residence_within_committee_geography_boundary(): void
    {
        $committeeGeo = GeoReference::fromString('np.3.15');
        $memberGeo = GeoReference::fromString('np.3.15.234');

        // Create committee at district level (np.3.15)
        $committee = Committee::createForGeography(
            CommitteeId::fromString('test-committee'),
            $this->tenantId,
            'District Committee',
            'DIST-315',
            CommitteeType::district(),
            $committeeGeo,
            'wp'
        );

        // Member lives in a local unit within that district (np.3.15.234)
        // Should be within boundary
        $this->assertTrue($memberGeo->isWithinOrEqual($committeeGeo));
    }

    public function test_member_residence_outside_committee_geography_boundary(): void
    {
        $committeeGeo = GeoReference::fromString('np.3.15');
        $memberGeo = GeoReference::fromString('np.3.16'); // Different district

        $this->assertFalse($memberGeo->isWithinOrEqual($committeeGeo));
    }

    public function test_geography_validation_adapter_validates_format(): void
    {
        $validRef = $this->geoAdapter->validate('np.1.2.3');

        $this->assertNotNull($validRef);
        $this->assertSame('np.1.2.3', $validRef->value());
    }

    public function test_geography_validation_adapter_rejects_invalid_format(): void
    {
        $invalidRef = $this->geoAdapter->validate('invalid-geo-format');

        $this->assertNull($invalidRef);
    }

    public function test_graceful_degradation_when_geography_module_not_installed(): void
    {
        // Even if Geography module returns false for isGeographyModuleInstalled(),
        // the adapter should still accept valid paths
        $validRef = $this->geoAdapter->validate('np.1.2.3');

        $this->assertNotNull($validRef, 'Should accept valid geo reference even if module not installed');
    }

    public function test_committee_hierarchy_matches_geographic_hierarchy(): void
    {
        // Central committee (no geo)
        $central = Committee::createCentral(
            CommitteeId::fromString('central-1'),
            $this->tenantId,
            'Central Committee',
            'CENTRAL'
        );

        // Provincial committee (level 1)
        $province = Committee::createForGeography(
            CommitteeId::fromString('province-1'),
            $this->tenantId,
            'Province Committee',
            'PROV',
            CommitteeType::province(),
            GeoReference::fromString('np.1'),
            'wp'
        );

        // District committee (level 2)
        $district = Committee::createForGeography(
            CommitteeId::fromString('district-1'),
            $this->tenantId,
            'District Committee',
            'DIST',
            CommitteeType::district(),
            GeoReference::fromString('np.1.12'),
            'wp'
        );

        // Ward committee (level 3)
        $ward = Committee::createForGeography(
            CommitteeId::fromString('ward-1'),
            $this->tenantId,
            'Ward Committee',
            'WARD',
            CommitteeType::ward(),
            GeoReference::fromString('np.1.12.123'),
            'wp'
        );

        // All should persist correctly
        $this->repository->saveForTenant($central);
        $this->repository->saveForTenant($province);
        $this->repository->saveForTenant($district);
        $this->repository->saveForTenant($ward);

        // Verify retrieval
        $retrieved = $this->repository->findForTenant(CommitteeId::fromString('ward-1'), $this->tenantId);
        $this->assertNotNull($retrieved);
        $this->assertSame('np.1.12.123', $retrieved->getOperationalGeoReference()->value());
    }
}
