<?php

declare(strict_types=1);

namespace Tests\Unit\Application\CommitteeStructure;

use Tests\TestCase;
use App\Contexts\Membership\Application\CommitteeStructure\ActivateCommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Mockery;

final class ActivateCommitteeStructureTest extends TestCase
{
    private $repo;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repo = Mockery::mock(CommitteeStructureRepositoryInterface::class);
    }

    public function test_it_activates_a_draft_structure(): void
    {
        // Arrange
        $tenantId = TenantId::fromString('123e4567-e89b-12d3-a456-426614174000');
        $structureId = CommitteeStructureId::generate();

        $draft = CommitteeStructure::define(
            id: $structureId,
            tenantId: $tenantId,
            name: 'Test Structure',
            levels: [
                CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );

        $this->repo
            ->shouldReceive('findById')
            ->once()
            ->andReturn($draft);

        $this->repo
            ->shouldReceive('persist')
            ->once();

        $this->repo
            ->shouldReceive('findActiveByTenantForUpdate')
            ->once()
            ->andReturn(null);

        $useCase = new ActivateCommitteeStructure($this->repo);

        // Act
        $result = $useCase->execute([
            'tenantId' => $tenantId->value(),
            'structureId' => $structureId->value()
        ]);

        // Assert
        $this->assertTrue($result->isActive());
    }

    public function test_it_deprecates_existing_active_structure_before_activation(): void
    {
        // Arrange
        $tenantId = TenantId::fromString('123e4567-e89b-12d3-a456-426614174000');
        $newStructureId = CommitteeStructureId::generate();

        // Create existing active structure
        $existingActive = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $tenantId,
            name: 'Old Structure',
            levels: [
                CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );
        $existingActive->activate('test-user');

        // Create new draft structure
        $newDraft = CommitteeStructure::define(
            id: $newStructureId,
            tenantId: $tenantId,
            name: 'New Structure',
            levels: [
                CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );

        $this->repo
            ->shouldReceive('findActiveByTenantForUpdate')
            ->once()
            ->andReturn($existingActive);

        $this->repo
            ->shouldReceive('findById')
            ->once()
            ->andReturn($newDraft);

        $this->repo
            ->shouldReceive('persist')
            ->times(2); // once for deprecated, once for active

        $useCase = new ActivateCommitteeStructure($this->repo);

        // Act
        $useCase->execute([
            'tenantId' => $tenantId->value(),
            'structureId' => $newStructureId->value()
        ]);

        // Assert
        $this->assertTrue(true); // expectations verified by mocks
    }

    public function test_it_fails_if_structure_is_not_draft(): void
    {
        // Arrange
        $tenantId = TenantId::fromString('123e4567-e89b-12d3-a456-426614174000');
        $structureId = CommitteeStructureId::generate();

        // Create an already active structure
        $active = CommitteeStructure::define(
            id: $structureId,
            tenantId: $tenantId,
            name: 'Active Structure',
            levels: [
                CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );
        $active->activate('test-user');

        $this->repo
            ->shouldReceive('findById')
            ->andReturn($active);

        $useCase = new ActivateCommitteeStructure($this->repo);

        // Assert
        $this->expectException(\DomainException::class);

        // Act
        $useCase->execute([
            'tenantId' => $tenantId->value(),
            'structureId' => $structureId->value()
        ]);
    }
}
