<?php

declare(strict_types=1);

namespace Tests\Unit\Domain;

use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\StructureStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

final class CommitteeStructureStateMachineTest extends TestCase
{
    private TenantId $tenantId;

    protected function setUp(): void
    {
        $this->tenantId = TenantId::fromString('org-123');
    }

    public function test_cannot_activate_already_active_structure(): void
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
        $this->expectExceptionMessage('Only DRAFT structures can be activated');

        $structure->activate();
    }

    public function test_cannot_evolve_draft_structure(): void
    {
        $levels = [CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null)];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Draft',
            levels: $levels
        );

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Only ACTIVE structures can be evolved');

        $structure->evolve($levels);
    }

    public function test_cannot_evolve_deprecated_structure(): void
    {
        $levels = [CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null)];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure',
            levels: $levels
        );

        $structure->activate();

        $evolved = $structure->evolve($levels);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Only ACTIVE structures can be evolved');

        $structure->evolve($levels);
    }

    public function test_state_transitions_follow_lifecycle(): void
    {
        $levels = [CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null)];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure',
            levels: $levels
        );

        // Initial state: DRAFT
        $this->assertEquals(StructureStatus::DRAFT, $structure->status());

        // Transition: DRAFT → ACTIVE
        $structure->activate();
        $this->assertEquals(StructureStatus::ACTIVE, $structure->status());

        // Transition: ACTIVE → DEPRECATED (via evolve)
        $newLevels = [
            CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null),
            CommitteeLevel::create(2, 'Province', GeoPolicy::NONE, null, [], 0, null, null),
        ];
        $evolved = $structure->evolve($newLevels);

        $this->assertEquals(StructureStatus::DEPRECATED, $structure->status());
        $this->assertEquals(StructureStatus::DRAFT, $evolved->status());
    }

    public function test_version_increments_on_evolve(): void
    {
        $levels = [CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null)];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure',
            levels: $levels
        );

        $this->assertEquals(1, $structure->version());

        $structure->activate();
        $evolved = $structure->evolve($levels);

        $this->assertEquals(2, $evolved->version());
    }

    public function test_activation_emits_event_with_level_count(): void
    {
        $levels = [
            CommitteeLevel::create(1, 'Central', GeoPolicy::NONE, null, [], 0, null, null),
            CommitteeLevel::create(2, 'Province', GeoPolicy::NONE, null, [], 0, null, null),
        ];

        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Structure',
            levels: $levels
        );

        $structure->activate();
        $events = $structure->pullEvents();

        $activationEvent = null;
        foreach ($events as $event) {
            if ($event::class === \App\Contexts\Membership\Domain\Events\CommitteeStructureActivated::class) {
                $activationEvent = $event;
            }
        }

        $this->assertNotNull($activationEvent);
        $this->assertEquals($structure->getId(), $activationEvent->structureId());
        $this->assertEquals($this->tenantId, $activationEvent->tenantId());
        $this->assertEquals(2, $activationEvent->levelCount());
    }

    public function test_cannot_activate_structure_without_levels(): void
    {
        // This scenario shouldn't be possible in normal flow since constructor validates
        // But we test the activate() defensive check
        $structure = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $this->tenantId,
            name: 'Invalid',
            levels: [CommitteeLevel::create(1, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)]
        );

        // Manually clear levels to test defensive check (simulating corruption)
        $reflectionProperty = new \ReflectionProperty($structure, 'levels');
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($structure, []);

        // Reset status to DRAFT to allow testing activate
        $statusProperty = new \ReflectionProperty($structure, 'status');
        $statusProperty->setAccessible(true);
        $statusProperty->setValue($structure, \App\Contexts\Membership\Domain\Committee\StructureStatus::DRAFT);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Cannot activate structure without levels');

        $structure->activate();
    }
}
