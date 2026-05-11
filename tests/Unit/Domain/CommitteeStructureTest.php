<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\GeoScope;
use App\Contexts\Membership\Domain\Committee\StructureStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

final class CommitteeStructureTest extends TestCase
{
    private TenantId $tenantId;

    protected function setUp(): void
    {
        $this->tenantId = TenantId::fromString('org-123');
    }

    public function test_can_create_structure_in_draft_status(): void
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
            name: 'Default Structure',
            levels: $levels
        );

        $this->assertEquals(StructureStatus::DRAFT, $structure->status());
        $this->assertEquals('Default Structure', $structure->name());
    }

    public function test_rejects_empty_levels(): void
    {
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Structure must have at least one level');

        CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Invalid',
            levels: []
        );
    }

    public function test_rejects_duplicate_level_indexes(): void
    {
        $levels = [
            CommitteeLevel::create(1, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null),
            CommitteeLevel::create(1, 'Duplicate', GeoPolicy::NONE, null, [], 0, null, null),
        ];

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Duplicate level indexes detected');

        CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Bad Structure',
            levels: $levels
        );
    }

    public function test_rejects_non_contiguous_level_indexes(): void
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
            name: 'Broken Structure',
            levels: $levels
        );
    }

    public function test_get_level_returns_correct_level(): void
    {
        $level1 = CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null);
        $level2 = CommitteeLevel::create(2, 'Province', GeoPolicy::REQUIRED, new GeoScope('province'), [], 0, null, null);

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure',
            levels: [$level1, $level2]
        );

        $retrieved = $structure->getLevel(2);
        $this->assertEquals('Province', $retrieved->name);
    }

    public function test_get_level_throws_for_unknown_index(): void
    {
        $levels = [CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null)];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure',
            levels: $levels
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Level index 5 not found');

        $structure->getLevel(5);
    }

    public function test_activate_changes_status_to_active(): void
    {
        $levels = [CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null)];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure',
            levels: $levels
        );

        $structure->activate();

        $this->assertEquals(StructureStatus::ACTIVE, $structure->status());
    }

    public function test_evolve_deprecates_current_and_returns_new_draft(): void
    {
        $levels = [CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null)];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Original',
            levels: $levels
        );

        $structure->activate();

        $newLevels = [
            CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null),
            CommitteeLevel::create(2, 'Province', GeoPolicy::REQUIRED, new GeoScope('province'), [], 0, null, null),
        ];

        $evolved = $structure->evolve($newLevels);

        $this->assertEquals(StructureStatus::DRAFT, $evolved->status());
        $this->assertEquals(2, $evolved->version());
        $this->assertEquals(StructureStatus::DEPRECATED, $structure->status());
    }

    public function test_cannot_create_committee_against_draft_structure(): void
    {
        $levels = [CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null)];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Draft Structure',
            levels: $levels
        );

        $this->assertFalse($structure->isActive());
    }

    public function test_cannot_create_committee_against_deprecated_structure(): void
    {
        $levels = [CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null)];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure',
            levels: $levels
        );

        $structure->activate();

        $newLevels = [$levels[0]];
        $evolved = $structure->evolve($newLevels);

        $this->assertFalse($structure->isActive());
    }

    public function test_domain_event_emitted_on_define(): void
    {
        $levels = [CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null)];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'New Structure',
            levels: $levels
        );

        $events = $structure->pullEvents();

        $this->assertCount(1, $events);
        $this->assertEquals('CommitteeStructureDefined', class_basename($events[0]));
    }
}
