<?php

declare(strict_types=1);

namespace Tests\Feature\CommitteeStructure;

use Tests\TestCase;
use Illuminate\Database\QueryException;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Contexts\Membership\Infrastructure\Persistence\Models\CommitteeStructureModel;

final class DatabaseConstraintTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function test_database_prevents_multiple_active_structures_per_tenant(): void
    {
        $tenantId = (string)Str::uuid();

        // Insert first ACTIVE structure
        CommitteeStructureModel::create([
            'id' => (string)Str::ulid(),
            'organisation_id' => $tenantId,
            'name' => 'First Active',
            'status' => 'active',
            'version' => 1,
        ]);

        // Attempt to insert second ACTIVE structure for same tenant
        // This MUST fail due to unique partial index constraint
        $this->expectException(QueryException::class);

        CommitteeStructureModel::create([
            'id' => (string)Str::ulid(),
            'organisation_id' => $tenantId,
            'name' => 'Second Active',
            'status' => 'active',
            'version' => 1,
        ]);
    }

    public function test_database_allows_multiple_draft_structures_per_tenant(): void
    {
        $tenantId = (string)Str::uuid();

        // Multiple DRAFT structures should be allowed
        CommitteeStructureModel::create([
            'id' => (string)Str::ulid(),
            'organisation_id' => $tenantId,
            'name' => 'Draft 1',
            'status' => 'draft',
            'version' => 1,
        ]);

        CommitteeStructureModel::create([
            'id' => (string)Str::ulid(),
            'organisation_id' => $tenantId,
            'name' => 'Draft 2',
            'status' => 'draft',
            'version' => 1,
        ]);

        // Both should exist
        $count = CommitteeStructureModel::where('organisation_id', $tenantId)
            ->where('status', 'draft')
            ->count();

        $this->assertEquals(2, $count, 'Both DRAFT structures should be created successfully');
    }

    public function test_database_allows_multiple_deprecated_structures_per_tenant(): void
    {
        $tenantId = (string)Str::uuid();

        // Multiple DEPRECATED structures should be allowed
        CommitteeStructureModel::create([
            'id' => (string)Str::ulid(),
            'organisation_id' => $tenantId,
            'name' => 'Old Active 1',
            'status' => 'deprecated',
            'version' => 1,
        ]);

        CommitteeStructureModel::create([
            'id' => (string)Str::ulid(),
            'organisation_id' => $tenantId,
            'name' => 'Old Active 2',
            'status' => 'deprecated',
            'version' => 2,
        ]);

        // Both should exist
        $count = CommitteeStructureModel::where('organisation_id', $tenantId)
            ->where('status', 'deprecated')
            ->count();

        $this->assertEquals(2, $count, 'Both DEPRECATED structures should be created successfully');
    }

    public function test_constraint_is_per_tenant(): void
    {
        $tenant1 = (string)Str::uuid();
        $tenant2 = (string)Str::uuid();

        // First tenant can have an ACTIVE structure
        CommitteeStructureModel::create([
            'id' => (string)Str::ulid(),
            'organisation_id' => $tenant1,
            'name' => 'Tenant 1 Active',
            'status' => 'active',
            'version' => 1,
        ]);

        // Second tenant can also have an ACTIVE structure (different org_id)
        CommitteeStructureModel::create([
            'id' => (string)Str::ulid(),
            'organisation_id' => $tenant2,
            'name' => 'Tenant 2 Active',
            'status' => 'active',
            'version' => 1,
        ]);

        // Both should exist
        $count = CommitteeStructureModel::where('status', 'active')->count();
        $this->assertEquals(2, $count, 'Each tenant should be able to have one ACTIVE structure');
    }
}
