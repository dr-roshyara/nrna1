<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Contexts\Membership\Domain\Committee\CommitteeCreationPolicy;
use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\GeoScope;
use App\Contexts\Membership\Domain\Committee\Ports\GeoContextPort;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CommitteeCreationPolicyTest extends TestCase
{
    private CommitteeCreationPolicy $policy;
    private MockObject&GeoContextPort $geoPort;
    private TenantId $tenantId;

    protected function setUp(): void
    {
        $this->geoPort = $this->createMock(GeoContextPort::class);
        $this->policy = new CommitteeCreationPolicy($this->geoPort);
        $this->tenantId = TenantId::fromString('org-123');
    }

    public function test_passes_for_active_structure_valid_level_and_correct_geo(): void
    {
        $levels = [
            CommitteeLevel::create(
                index: 1,
                name: 'Central',
                geoPolicy: GeoPolicy::NONE,
                geoScope: null,
                roleLimits: [],
                minMembershipYears: 0,
                ageRange: null,
                genderRequirement: null
            ),
        ];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure',
            levels: $levels
        );

        $structure->activate();

        // Should not throw
        $this->policy->assertCanCreate($structure, 1, null);
        $this->assertTrue(true);
    }

    public function test_throws_for_draft_structure(): void
    {
        $levels = [CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null)];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Draft',
            levels: $levels
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Can only create committees against ACTIVE structures');

        $this->policy->assertCanCreate($structure, 1, null);
    }

    public function test_throws_for_unknown_level_index(): void
    {
        $levels = [CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null)];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure',
            levels: $levels
        );

        $structure->activate();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Level index 5 not found');

        $this->policy->assertCanCreate($structure, 5, null);
    }

    public function test_throws_when_required_geo_is_null(): void
    {
        $levels = [
            CommitteeLevel::create(
                index: 1,
                name: 'Province',
                geoPolicy: GeoPolicy::REQUIRED,
                geoScope: new GeoScope('province'),
                roleLimits: [],
                minMembershipYears: 0,
                ageRange: null,
                genderRequirement: null
            ),
        ];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure',
            levels: $levels
        );

        $structure->activate();

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('GeoPolicy requires a GeoReference');

        $this->policy->assertCanCreate($structure, 1, null);
    }

    public function test_passes_when_optional_geo_is_null(): void
    {
        $levels = [
            CommitteeLevel::create(
                index: 1,
                name: 'Optional Geo Level',
                geoPolicy: GeoPolicy::OPTIONAL,
                geoScope: null,
                roleLimits: [],
                minMembershipYears: 0,
                ageRange: null,
                genderRequirement: null
            ),
        ];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure',
            levels: $levels
        );

        $structure->activate();

        // Should not throw
        $this->policy->assertCanCreate($structure, 1, null);
        $this->assertTrue(true);
    }
}
