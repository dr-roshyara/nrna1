<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Application\Committee\Services;

use App\Contexts\Membership\Application\Committee\Services\CommitteeCategoryDerivationService;
use App\Contexts\Membership\Domain\Committee\Policies\CommitteeClassificationPolicy;
use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdiction;
use App\Contexts\Membership\Domain\Committee\Ports\GeographicJurisdictionProvider;
use App\Contexts\Membership\Domain\Committee\Services\GeoSemanticProjectionBuilder;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeCategory;
use PHPUnit\Framework\TestCase;

final class CommitteeCategoryDerivationServiceTest extends TestCase
{
    public function test_derive_with_explicit_type_returns_that_type(): void
    {
        $service = $this->createService(null, null);

        $category = $service->derive('central', 42);

        $this->assertTrue($category->isCentral());
    }

    public function test_derive_with_explicit_wing_type_returns_wing(): void
    {
        $service = $this->createService(null, null);

        $category = $service->derive('youth', 42);

        $this->assertTrue($category->isWingType());
        $this->assertSame(CommitteeCategory::YOUTH, $category);
    }

    public function test_derive_with_geo_unit_id_derives_from_admin_level(): void
    {
        $jurisdiction = new GeographicJurisdiction(42, 2, 'asia', 'NP');
        $service = $this->createService($jurisdiction, 42);

        $category = $service->derive(null, 42);

        $this->assertSame(CommitteeCategory::PROVINCE, $category);
    }

    public function test_derive_with_geo_unit_id_and_explicit_type_prefers_type(): void
    {
        // Provider should NOT be called when explicit type is given
        $provider = $this->createMock(GeographicJurisdictionProvider::class);
        $provider->expects($this->never())->method('resolve');

        $builder = new GeoSemanticProjectionBuilder($provider);
        $policy = new CommitteeClassificationPolicy();
        $service = new CommitteeCategoryDerivationService($builder, $policy);

        $category = $service->derive('women', 42);

        $this->assertSame(CommitteeCategory::WOMEN, $category);
    }

    public function test_derive_with_geo_unit_id_resolve_fails_defaults_to_central(): void
    {
        $service = $this->createService(null, 99);

        $category = $service->derive(null, 99);

        $this->assertTrue($category->isCentral());
    }

    public function test_derive_with_no_type_no_geo_defaults_to_central(): void
    {
        $service = $this->createService(null, null);

        $category = $service->derive(null, null);

        $this->assertTrue($category->isCentral());
    }

    public function test_derive_with_admin_level_4_returns_ward(): void
    {
        $jurisdiction = new GeographicJurisdiction(50, 4, 'asia', 'NP');
        $service = $this->createService($jurisdiction, 50);

        $category = $service->derive(null, 50);

        $this->assertSame(CommitteeCategory::WARD, $category);
    }

    /**
     * Create a service with an optionally configured provider.
     *
     * When $expectedJurisdiction is non-null, the provider is stubbed to return
     * it for $expectedGeoUnitId. When null, the provider returns null for any input.
     */
    private function createService(
        ?GeographicJurisdiction $expectedJurisdiction,
        ?int $expectedGeoUnitId,
    ): CommitteeCategoryDerivationService {
        $provider = $this->createMock(GeographicJurisdictionProvider::class);
        $provider->method('resolve')
            ->with($expectedGeoUnitId)
            ->willReturn($expectedJurisdiction);

        $builder = new GeoSemanticProjectionBuilder($provider);
        $policy = new CommitteeClassificationPolicy();

        return new CommitteeCategoryDerivationService($builder, $policy);
    }
}
