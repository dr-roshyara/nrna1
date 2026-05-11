<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\GeoScope;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

final class CommitteeStructureInvariantsTest extends TestCase
{
    private TenantId $tenantId;

    protected function setUp(): void
    {
        $this->tenantId = TenantId::fromString('org-123');
    }

    public function test_cannot_skip_level_indexes(): void
    {
        $levels = [
            CommitteeLevel::create(1, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null),
            CommitteeLevel::create(3, 'Level 3', GeoPolicy::NONE, null, [], 0, null, null),
        ];

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Level indexes must be contiguous');

        CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Bad',
            levels: $levels
        );
    }

    public function test_cannot_create_level_with_index_0(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Level index must be between 1 and 10');

        CommitteeLevel::create(0, 'Level 0', GeoPolicy::NONE, null, [], 0, null, null);
    }

    public function test_index_range_constraint_enforced_at_level_creation(): void
    {
        // Maximum 10 levels is enforced at CommitteeLevel creation time via index bounds (1-10)
        // CommitteeLevel::create(11, ...) will throw InvalidArgumentException
        // This test documents that constraint enforcement happens at CommitteeLevel, not CommitteeStructure

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Level index must be between 1 and 10');

        CommitteeLevel::create(11, 'Level 11', GeoPolicy::NONE, null, [], 0, null, null);
    }

    public function test_structure_must_have_at_least_one_level(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Structure must have at least one level');

        CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Empty',
            levels: []
        );
    }

    public function test_all_levels_must_have_unique_indexes(): void
    {
        $levels = [
            CommitteeLevel::create(1, 'Level 1A', GeoPolicy::NONE, null, [], 0, null, null),
            CommitteeLevel::create(1, 'Level 1B', GeoPolicy::NONE, null, [], 0, null, null),
        ];

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Duplicate level indexes detected');

        CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Duplicates',
            levels: $levels
        );
    }

    public function test_valid_structure_with_all_10_levels(): void
    {
        $levels = [];
        for ($i = 1; $i <= 10; $i++) {
            $levels[] = CommitteeLevel::create($i, "Level $i", GeoPolicy::NONE, null, [], 0, null, null);
        }

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Full Structure',
            levels: $levels
        );

        $this->assertCount(10, $structure->levels());
    }

    public function test_get_level_with_valid_index(): void
    {
        $levels = [
            CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null),
            CommitteeLevel::create(2, 'Province', GeoPolicy::NONE, null, [], 0, null, null),
            CommitteeLevel::create(3, 'District', GeoPolicy::NONE, null, [], 0, null, null),
        ];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure',
            levels: $levels
        );

        $level = $structure->getLevel(2);
        $this->assertEquals('Province', $level->name);
    }

    public function test_assert_can_create_committee_validates_status(): void
    {
        $levels = [CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null)];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Draft Structure',
            levels: $levels
        );

        // DRAFT structure cannot create committees
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Can only create committees against ACTIVE structures');

        $structure->assertCanCreateCommittee(1);
    }

    public function test_assert_can_create_committee_validates_level_exists(): void
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

        $structure->assertCanCreateCommittee(5);
    }

    public function test_assert_can_create_committee_validates_geo_policy(): void
    {
        $levels = [
            CommitteeLevel::create(
                index: 1,
                name: 'District',
                geoPolicy: GeoPolicy::REQUIRED,
                geoScope: new GeoScope('district'),
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

        // Should not throw - policy is validated
        try {
            $structure->assertCanCreateCommittee(1);
            $this->assertTrue(true); // No exception thrown
        } catch (\Exception $e) {
            $this->fail("assertCanCreateCommittee should not throw: {$e->getMessage()}");
        }
    }

}
