<?php

declare(strict_types=1);

namespace Tests\Integration\Contexts\Governance\Infrastructure\Repositories;

use App\Contexts\Governance\Application\DTOs\CommitteeHierarchyRecord;
use App\Contexts\Governance\Infrastructure\Projections\CommitteeGovernanceProjectionModel;
use App\Contexts\Governance\Infrastructure\Repositories\CommitteeHierarchyRepository;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Models\Organisation;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CommitteeHierarchyRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private function createProjection(string $committeeId): void
    {
        CommitteeGovernanceProjectionModel::create([
            'committee_id' => $committeeId,
            'operational_state' => 'ACTIVE',
            'temporal_state' => 'VALID',
            'legitimacy' => 'LEGITIMATE',
            'can_act' => true,
            'is_fully_operational' => true,
            'projection_version' => 1,
            'evaluated_at' => new DateTimeImmutable('2026-05-10T12:00:00Z'),
            'rebuilt_at' => new DateTimeImmutable('2026-05-10T12:00:00Z'),
        ]);
    }

    public function test_fetches_all_committee_records_with_governance_state(): void
    {
        $org = Organisation::factory()->create();

        CommitteeModel::create([
            'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
            'organisation_id' => $org->id,
            'name' => 'ICC Global',
            'code' => 'ICC-001',
            'type' => 'central',
            'level' => 0,
            'status' => 'active',
        ]);

        $this->createProjection('01ARZ3NDEKTSV4RRFFQ69G5FAV');

        CommitteeModel::create([
            'id' => '01ARZ3NDEKTSV4RRFFQ69G5FBW',
            'organisation_id' => $org->id,
            'name' => 'Asia Continent',
            'code' => 'CONT-ASIA',
            'type' => 'central',
            'level' => 1,
            'status' => 'active',
        ]);

        $this->createProjection('01ARZ3NDEKTSV4RRFFQ69G5FBW');

        $repo = new CommitteeHierarchyRepository();
        $records = $repo->getAll(TenantId::fromString($org->id));

        $this->assertCount(2, $records);
        $this->assertContainsOnlyInstancesOf(CommitteeHierarchyRecord::class, $records);

        // Verify governance state is read from projection table
        $this->assertSame('ACTIVE', $records[0]->operationalState);
        $this->assertSame('VALID', $records[0]->temporalState);
        $this->assertSame('LEGITIMATE', $records[0]->legitimacy);
        $this->assertTrue($records[0]->canAct);
        $this->assertTrue($records[0]->isFullyOperational);
        $this->assertSame(1, $records[0]->projectionVersion);
    }

    public function test_sets_defaults_when_no_projection_exists(): void
    {
        $org = Organisation::factory()->create();

        CommitteeModel::create([
            'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
            'organisation_id' => $org->id,
            'name' => 'No Projection',
            'code' => 'NOPROJ',
            'type' => 'central',
            'level' => 0,
            'status' => 'active',
        ]);

        $repo = new CommitteeHierarchyRepository();
        $records = $repo->getAll(TenantId::fromString($org->id));

        $this->assertCount(1, $records);
        $this->assertSame('UNKNOWN', $records[0]->operationalState);
        $this->assertSame('UNAUTHORIZED', $records[0]->legitimacy);
        $this->assertFalse($records[0]->canAct);
        $this->assertFalse($records[0]->isFullyOperational);
    }

    public function test_returns_empty_array_when_no_committees(): void
    {
        $org = Organisation::factory()->create();

        $repo = new CommitteeHierarchyRepository();
        $records = $repo->getAll(TenantId::fromString($org->id));

        $this->assertIsArray($records);
        $this->assertEmpty($records);
    }

    public function test_filters_by_tenant_id(): void
    {
        $org1 = Organisation::factory()->create();
        $org2 = Organisation::factory()->create();

        CommitteeModel::create([
            'id' => '01ARZ3NDEKTSV4RRFFQ69G5FAV',
            'organisation_id' => $org1->id,
            'name' => 'Org1 Committee',
            'code' => 'O1-001',
            'type' => 'central',
            'level' => 0,
            'status' => 'active',
        ]);

        $this->createProjection('01ARZ3NDEKTSV4RRFFQ69G5FAV');

        CommitteeModel::create([
            'id' => '01ARZ3NDEKTSV4RRFFQ69G5FBW',
            'organisation_id' => $org2->id,
            'name' => 'Org2 Committee',
            'code' => 'O2-001',
            'type' => 'central',
            'level' => 0,
            'status' => 'active',
        ]);

        $this->createProjection('01ARZ3NDEKTSV4RRFFQ69G5FBW');

        $repo = new CommitteeHierarchyRepository();
        $records = $repo->getAll(TenantId::fromString($org1->id));

        $this->assertCount(1, $records);
        $this->assertSame('Org1 Committee', $records[0]->name);
    }
}
