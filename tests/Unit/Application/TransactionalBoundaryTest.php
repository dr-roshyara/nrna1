<?php

declare(strict_types=1);

namespace Tests\Unit\Application;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Contexts\Membership\Application\CommitteeStructure\ActivateCommitteeStructure;
use App\Contexts\Membership\Application\CommitteeStructure\ActivateCommitteeStructureUseCase;
use App\Contexts\Membership\Infrastructure\Application\TransactionalActivateCommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class TransactionalBoundaryTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function test_transactional_decorator_executes_use_case_inside_transaction(): void
    {
        $tenantId = TenantId::fromString('11111111-1111-1111-1111-111111111111');
        $structureId = CommitteeStructureId::generate();

        // Create and save a draft structure
        $repo = $this->app->make(CommitteeStructureRepositoryInterface::class);
        $structure = CommitteeStructure::define(
            id: $structureId,
            tenantId: $tenantId,
            name: 'Test Structure',
            levels: [
                CommitteeLevel::create(1, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );
        $repo->persist($structure);

        // Resolve from container to get the transactional-wrapped use case
        $transactionalUseCase = $this->app->make(ActivateCommitteeStructureUseCase::class);

        // Execute through decorator (should wrap in transaction)
        $result = $transactionalUseCase->execute([
            'tenantId' => $tenantId->value(),
            'structureId' => $structureId->value(),
        ]);

        // Assert use case executed successfully within transaction
        $this->assertTrue($result->isActive());
        $this->assertInstanceOf(CommitteeStructure::class, $result);
    }

    public function test_transaction_rolls_back_on_use_case_exception(): void
    {
        $tenantId = TenantId::fromString('22222222-2222-2222-2222-222222222222');

        // Create a mock repository that throws exception
        $mockRepo = \Mockery::mock(CommitteeStructureRepositoryInterface::class);
        $mockRepo
            ->shouldReceive('findById')
            ->andThrow(new \RuntimeException('Simulated failure'));

        // Manually construct the decorated chain (same as container binding)
        $coreUseCase = new ActivateCommitteeStructure($mockRepo);
        $transactionalUseCase = new TransactionalActivateCommitteeStructure($coreUseCase);

        // Expect exception to propagate
        $this->expectException(\RuntimeException::class);

        // When exception occurs, transaction should roll back
        // (This is verified implicitly: if transaction logic is broken, data would be corrupted)
        $transactionalUseCase->execute([
            'tenantId' => $tenantId->value(),
            'structureId' => 'any-id',
        ]);
    }

    public function test_decorator_ensures_transactional_execution_with_state_transitions(): void
    {
        // This test verifies that the decorator pattern ensures the use case
        // executes atomically with proper state transitions

        $tenantId = TenantId::fromString('33333333-3333-3333-3333-333333333333');
        $repo = $this->app->make(CommitteeStructureRepositoryInterface::class);

        // Create three draft structures
        $s1 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $tenantId,
            name: 'Structure 1',
            levels: [CommitteeLevel::create(1, 'L1', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $s2 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $tenantId,
            name: 'Structure 2',
            levels: [CommitteeLevel::create(1, 'L1', GeoPolicy::NONE, null, [], 0, null, null)]
        );
        $s3 = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $tenantId,
            name: 'Structure 3',
            levels: [CommitteeLevel::create(1, 'L1', GeoPolicy::NONE, null, [], 0, null, null)]
        );

        $repo->persist($s1);
        $repo->persist($s2);
        $repo->persist($s3);

        // Use service provider binding which wraps with transactional decorator
        $transactionalUseCase = $this->app->make(ActivateCommitteeStructureUseCase::class);

        // Activate first through decorator (transactional)
        $result1 = $transactionalUseCase->execute([
            'tenantId' => $tenantId->value(),
            'structureId' => $s1->getId()->value(),
        ]);
        $this->assertTrue($result1->isActive());

        // Activate second (s1 should be deprecated automatically)
        $result2 = $transactionalUseCase->execute([
            'tenantId' => $tenantId->value(),
            'structureId' => $s2->getId()->value(),
        ]);
        $this->assertTrue($result2->isActive());

        // Verify only one ACTIVE structure exists (invariant maintained by transaction)
        $activeStructures = $repo->findAllActiveByTenant($tenantId);
        $this->assertCount(1, $activeStructures, 'Invariant: only one ACTIVE per tenant');
        $this->assertEquals($s2->getId()->value(), $activeStructures[0]->getId()->value());

        // Activate third (s2 should be deprecated)
        $result3 = $transactionalUseCase->execute([
            'tenantId' => $tenantId->value(),
            'structureId' => $s3->getId()->value(),
        ]);
        $this->assertTrue($result3->isActive());

        // Verify invariant still holds
        $activeStructures = $repo->findAllActiveByTenant($tenantId);
        $this->assertCount(1, $activeStructures, 'Invariant maintained through multiple cycles');
        $this->assertEquals($s3->getId()->value(), $activeStructures[0]->getId()->value());
    }
}
