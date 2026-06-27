<?php

declare(strict_types=1);

namespace Tests\Feature\CommitteeStructure;

use Tests\TestCase;
use Illuminate\Support\Str;
use App\Contexts\Membership\Application\CommitteeStructure\ActivateCommitteeStructureUseCase;
use App\Contexts\Membership\Domain\Committee\CommitteeStructure;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureId;
use App\Contexts\Membership\Domain\Committee\CommitteeLevel;
use App\Contexts\Membership\Domain\Committee\GeoPolicy;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class ActivateCommitteeStructureStressTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function test_stress_activation_keeps_only_one_active_structure_under_sequential_load(): void
    {
        $tenantId = TenantId::fromString((string)Str::uuid());
        $repo = app(CommitteeStructureRepositoryInterface::class);
        $useCase = app(ActivateCommitteeStructureUseCase::class);

        // Arrange: seed 10 draft structures
        $structureIds = [];
        foreach (range(1, 10) as $i) {
            $structure = CommitteeStructure::define(
                id: CommitteeStructureId::generate(),
                tenantId: $tenantId,
                name: "Stress Structure {$i}",
                levels: [
                    CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
                ]
            );

            $repo->persist($structure);
            $structureIds[] = $structure->getId()->value();
        }

        // Act: attempt sequential activations (simulating high-frequency requests)
        $activationAttempts = 0;
        $successfulActivations = 0;

        foreach ($structureIds as $structureId) {
            try {
                $useCase->execute([
                    'tenantId' => $tenantId->value(),
                    'structureId' => $structureId,
                ]);
                $successfulActivations++;
            } catch (\DomainException $e) {
                // Expected: only first activation succeeds, rest fail because structure not DRAFT
            }
            $activationAttempts++;
        }

        // Assert: ONLY ONE ACTIVE structure should exist (THE CRITICAL INVARIANT)
        $active = $repo->findAllActiveByTenant($tenantId);

        $this->assertCount(
            1,
            $active,
            'INVARIANT VIOLATED: more than one ACTIVE structure exists for tenant. ' .
            'This indicates transaction isolation or locking failure under activation load.'
        );

        // The last activation wins (all others are deprecated)
        $this->assertEquals(10, $successfulActivations, 'All 10 activations should succeed');
        $this->assertEquals(10, $activationAttempts, 'All 10 structures should be attempted');
        $this->assertEquals($structureIds[9], $active[0]->getId()->value(), 'Last structure should be ACTIVE');
    }

    public function test_activation_maintains_idempotence_under_failed_attempts(): void
    {
        $tenantId = TenantId::fromString((string)Str::uuid());
        $repo = app(CommitteeStructureRepositoryInterface::class);
        $useCase = app(ActivateCommitteeStructureUseCase::class);

        // Arrange: create an already-active structure
        $original = CommitteeStructure::define(
            id: CommitteeStructureId::generate(),
            tenantId: $tenantId,
            name: 'Original Active',
            levels: [
                CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
            ]
        );
        $original->activate();
        $repo->persist($original);

        // Create 5 draft structures to attempt activation
        $draftIds = [];
        foreach (range(1, 5) as $i) {
            $draft = CommitteeStructure::define(
                id: CommitteeStructureId::generate(),
                tenantId: $tenantId,
                name: "Draft {$i}",
                levels: [
                    CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
                ]
            );
            $repo->persist($draft);
            $draftIds[] = $draft->getId()->value();
        }

        // Act: attempt concurrent-like activations (sequential but with transaction semantics)
        $activationResults = [];

        foreach ($draftIds as $draftId) {
            try {
                $result = $useCase->execute([
                    'tenantId' => $tenantId->value(),
                    'structureId' => $draftId,
                ]);
                $activationResults[$draftId] = ['status' => 'success', 'id' => $result->getId()->value()];
            } catch (\DomainException $e) {
                $activationResults[$draftId] = ['status' => 'failed', 'reason' => $e->getMessage()];
            }
        }

        // Assert: INVARIANT — still only ONE active
        $activeStructures = $repo->findAllActiveByTenant($tenantId);

        $this->assertCount(
            1,
            $activeStructures,
            'State corruption: expected exactly 1 active structure after concurrent activation attempts'
        );

        // The last draft activation succeeds and becomes ACTIVE (original was deprecated)
        $successful = array_filter($activationResults, fn ($r) => $r['status'] === 'success');
        $this->assertCount(5, $successful, 'All 5 draft activations should succeed');

        // Verify the active one is one of the drafts (not the original)
        $activeId = $activeStructures[0]->getId()->value();
        $this->assertContains($activeId, $draftIds, 'Active structure should be one of the draft structures');
    }

    public function test_multiple_activation_cycles_respect_invariant(): void
    {
        $tenantId = TenantId::fromString((string)Str::uuid());
        $repo = app(CommitteeStructureRepositoryInterface::class);
        $useCase = app(ActivateCommitteeStructureUseCase::class);

        // Simulate multiple activation cycles (like multiple election rounds)
        foreach (range(1, 3) as $cycle) {
            // Create draft
            $draft = CommitteeStructure::define(
                id: CommitteeStructureId::generate(),
                tenantId: $tenantId,
                name: "Cycle {$cycle}",
                levels: [
                    CommitteeLevel::create(1, null, 'Level 1', GeoPolicy::NONE, null, [], 0, null, null)
                ]
            );
            $repo->persist($draft);

            // Activate
            $useCase->execute([
                'tenantId' => $tenantId->value(),
                'structureId' => $draft->getId()->value(),
            ]);

            // Verify invariant after each cycle
            $activeCount = count($repo->findAllActiveByTenant($tenantId));
            $this->assertEquals(
                1,
                $activeCount,
                "Cycle {$cycle}: Invariant violated - expected 1 active, found {$activeCount}"
            );
        }
    }
}
