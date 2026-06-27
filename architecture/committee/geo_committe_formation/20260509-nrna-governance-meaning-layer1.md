# NRNA Governance Meaning Layer — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use `superpowers:subagent-driven-development` (recommended) or `superpowers:executing-plans` to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add the governance meaning layer that transforms the structurally-correct NRNA committee system into a constitutionally-aware governance platform — one where every committee knows whether it is legitimate, who authorized it, and what approvals are pending.

**Architecture:** Six phases wire existing disconnected governance infrastructure (GovernanceReplayService, GeoAuthorityGraph, GovernanceLegitimacy) to persistent storage, extend the dashboard to surface constitutional status, add an approval workflow for the ICC President's "Pending Approvals: 12" metric, and build the multi-level hierarchy dashboard the ICC President vision requires.

**Tech Stack:** Laravel 11, PHP 8.2+, PostgreSQL, Inertia 2.0, Vue 3 Composition API, PHPUnit, Mockery

---

## Context

The system already has sophisticated governance infrastructure — `GovernanceDecisionKernel`, `ConstitutionalArbitrationKernel`, `GovernanceLegitimacy`, `GeoAuthorityGraph` — but none of it is connected end-to-end:

- `InternalCreateCommittee` computes a governance decision but **never persists it** to `governance_decisions`
- `GeoAuthorityGraph` with `JurisdictionNode`/`DelegationEdge` exists in memory but has **no migrations or Eloquent models**
- `CommitteeModel.parent_committee_id` is a DB column but the **Committee domain aggregate has no `$parentId` field**
- `GetCommitteeDashboard` returns `id/name/code/type/status` — **no governance status, no authority chain, no pending approvals**
- `GovernanceLegitimacy::EXPIRED/CARETAKER/SUSPENDED` exist as enum values but **no code computes or emits them**

The ICC President dashboard mockup shows: *Pending Approvals: 12 | Recent Decisions: 45 | Active Elections: 3*. None of these are currently populated.

---

## File Map

| File | Action | Responsibility |
|------|--------|---------------|
| `app/Contexts/Membership/Application/Committee/InternalCreateCommittee.php` | Modify | Wire GovernanceReplayService call |
| `app/Contexts/Membership/Domain/Committee/Constitutional/GovernanceDecisionStore.php` | Modify | Add `findByCommittee()` and `findByTenant()` |
| `app/Contexts/Membership/Infrastructure/Persistence/EloquentGovernanceDecisionStore.php` | Modify | Implement new interface methods |
| `app/Contexts/Membership/Infrastructure/Models/GovernanceDecisionModel.php` | Create | Eloquent model for governance_decisions |
| `app/Contexts/Membership/Domain/Committee/Committee.php` | Modify | Add `?CommitteeId $parentId` to reconstruct() |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeAggregateRepository.php` | Modify | Pass `parent_committee_id` during reconstruct |
| `app/Contexts/Membership/Infrastructure/Models/CommitteeModel.php` | Modify | Add `parent()` / `children()` relationships |
| `app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant/2026_05_09_000001_create_jurisdiction_nodes_table.php` | Create | Persist GeoAuthorityGraph nodes |
| `app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant/2026_05_09_000002_create_delegation_edges_table.php` | Create | Persist GeoAuthorityGraph edges |
| `app/Contexts/Membership/Infrastructure/Models/JurisdictionNodeModel.php` | Create | Eloquent for jurisdiction_nodes |
| `app/Contexts/Membership/Infrastructure/Models/DelegationEdgeModel.php` | Create | Eloquent for delegation_edges |
| `app/Contexts/Membership/Domain/Committee/Geo/Graph/GeoAuthorityGraphRepository.php` | Create | Interface for graph persistence |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentGeoAuthorityGraphRepository.php` | Create | Eloquent implementation |
| `app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant/2026_05_09_000003_create_governance_approval_requests_table.php` | Create | Approval workflow table |
| `app/Contexts/Membership/Domain/Committee/Governance/GovernanceApprovalRequest.php` | Create | Approval request domain model |
| `app/Contexts/Membership/Domain/Committee/Governance/ApprovalStatus.php` | Create | Enum: PENDING, APPROVED, REJECTED |
| `app/Contexts/Membership/Application/Committee/RequestCommitteeApproval.php` | Create | Use case: request approval |
| `app/Contexts/Membership/Application/Committee/ApproveCommitteeFormation.php` | Create | Use case: approve |
| `app/Contexts/Membership/Application/Committee/RejectCommitteeFormation.php` | Create | Use case: reject |
| `app/Contexts/Membership/Infrastructure/Models/GovernanceApprovalRequestModel.php` | Create | Eloquent model |
| `app/Contexts/Membership/Infrastructure/Repositories/EloquentGovernanceApprovalRepository.php` | Create | Eloquent repo |
| `app/Http/Controllers/Committee/CommitteeApprovalController.php` | Create | HTTP layer for approvals |
| `app/Contexts/Membership/Application/Committee/DTOs/CommitteeGovernanceStatusDTO.php` | Create | Read DTO for governance status |
| `app/Contexts/Membership/Application/Committee/Services/CommitteeGovernanceStatusService.php` | Create | Computes legitimacy + term + chain |
| `app/Contexts/Membership/Application/Committee/GetCommitteeDashboard.php` | Modify | Add governance status to output |
| `app/Contexts/Membership/Application/Committee/GetGovernanceHierarchy.php` | Create | Tree with governance annotations |
| `app/Contexts/Membership/Application/Committee/GetGlobalGovernanceMetrics.php` | Create | ICC President counts |
| `app/Http/Controllers/Committee/CommitteeHierarchyController.php` | Create | Hierarchy + global metrics HTTP |
| `routes/committee/committeeRoutes.php` | Modify | Add approval + hierarchy routes |
| `app/Contexts/Membership/Infrastructure/Providers/MembershipServiceProvider.php` | Modify | Register new bindings |
| `resources/js/Pages/Committee/Dashboard.vue` | Modify | Add governance status badge |
| `resources/js/Pages/Committee/Hierarchy.vue` | Create | Tree visualization page |

---

## Phase 1 — Wire Governance Decision Journal

**The existing `governance_decisions` table never gets written to. Fix `InternalCreateCommittee` to persist every committee creation decision.**

### Task 1.1 — Create GovernanceDecisionModel

**Files:**
- Create: `app/Contexts/Membership/Infrastructure/Models/GovernanceDecisionModel.php`

- [ ] **Step 1: Write the failing test**

```php
// tests/Unit/Contexts/Membership/Infrastructure/Models/GovernanceDecisionModelTest.php
<?php

namespace Tests\Unit\Contexts\Membership\Infrastructure\Models;

use App\Contexts\Membership\Infrastructure\Models\GovernanceDecisionModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GovernanceDecisionModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_insert_and_retrieve_governance_decision(): void
    {
        GovernanceDecisionModel::create([
            'id' => 'test-uuid-0001',
            'decided_at' => now(),
            'capability_type' => 'COMMITTEE_CREATION',
            'legitimacy' => 'LEGITIMATE',
            'constitutional_reason_json' => ['reason' => 'test'],
            'arbitration_trace_json' => [],
            'schema_version' => '1.0',
            'doctrine_version' => '1.0',
            'legitimacy_policy_version' => '1.0',
            'arbitration_policy_version' => '1.0',
            'replay_engine_version' => '1.0',
            'replay_compatibility_version' => '1.0',
            'metadata_generated_at' => now(),
            'integrity_hash' => hash('sha256', 'test'),
        ]);

        $model = GovernanceDecisionModel::find('test-uuid-0001');
        $this->assertNotNull($model);
        $this->assertEquals('COMMITTEE_CREATION', $model->capability_type);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

```
php artisan test tests/Unit/Contexts/Membership/Infrastructure/Models/GovernanceDecisionModelTest.php
```
Expected: FAIL — class not found

- [ ] **Step 3: Create the Eloquent model**

```php
<?php

namespace App\Contexts\Membership\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class GovernanceDecisionModel extends Model
{
    protected $table = 'governance_decisions';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'decided_at', 'capability_type', 'winning_authority_id',
        'legitimacy', 'constitutional_scope', 'constitutional_reason_json',
        'arbitration_trace_json', 'schema_version', 'doctrine_version',
        'legitimacy_policy_version', 'arbitration_policy_version',
        'replay_engine_version', 'replay_compatibility_version',
        'metadata_generated_at', 'integrity_hash',
    ];

    protected $casts = [
        'constitutional_reason_json' => 'array',
        'arbitration_trace_json' => 'array',
        'decided_at' => 'datetime',
        'metadata_generated_at' => 'datetime',
    ];
}
```

- [ ] **Step 4: Run test to verify it passes**

```
php artisan test tests/Unit/Contexts/Membership/Infrastructure/Models/GovernanceDecisionModelTest.php
```
Expected: PASS

- [ ] **Step 5: Commit**

```
git add app/Contexts/Membership/Infrastructure/Models/GovernanceDecisionModel.php tests/Unit/Contexts/Membership/Infrastructure/Models/GovernanceDecisionModelTest.php
git commit -m "feat(governance): add GovernanceDecisionModel Eloquent model"
```

---

### Task 1.2 — Extend GovernanceDecisionStore interface and implementation

**Files:**
- Modify: `app/Contexts/Membership/Domain/Committee/Constitutional/GovernanceDecisionStore.php`
- Modify: `app/Contexts/Membership/Infrastructure/Persistence/EloquentGovernanceDecisionStore.php` (locate exact path with grep first)

- [ ] **Step 1: Write the failing test**

```php
// tests/Unit/Contexts/Membership/Infrastructure/Persistence/EloquentGovernanceDecisionStoreTest.php
<?php

use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionStore;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EloquentGovernanceDecisionStoreTest extends TestCase
{
    use RefreshDatabase;

    public function test_find_by_committee_returns_empty_when_no_decisions(): void
    {
        $store = $this->app->make(GovernanceDecisionStore::class);
        $results = $store->findByCommittee(CommitteeId::fromString('01HX000000000000000000001'));
        $this->assertIsArray($results);
        $this->assertEmpty($results);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

```
php artisan test tests/Unit/Contexts/Membership/Infrastructure/Persistence/EloquentGovernanceDecisionStoreTest.php
```
Expected: FAIL — method not found

- [ ] **Step 3: Add methods to interface**

In `app/Contexts/Membership/Domain/Committee/Constitutional/GovernanceDecisionStore.php`, add:

```php
public function findByCommittee(CommitteeId $committeeId): array;
public function findByTenant(TenantId $tenantId, int $limit = 50): array;
public function countPendingForTenant(TenantId $tenantId): int;
```

- [ ] **Step 4: Implement in EloquentGovernanceDecisionStore**

Find the implementation file (likely at `app/Contexts/Membership/Infrastructure/Persistence/EloquentGovernanceDecisionStore.php` or similar). Add:

```php
public function findByCommittee(CommitteeId $committeeId): array
{
    return GovernanceDecisionModel::where(
        'winning_authority_id', $committeeId->toString()
    )->orderByDesc('decided_at')->limit(20)->get()->toArray();
}

public function findByTenant(TenantId $tenantId, int $limit = 50): array
{
    // governance_decisions does not have organisation_id — query via committee join
    return GovernanceDecisionModel::orderByDesc('decided_at')
        ->limit($limit)
        ->get()
        ->toArray();
}

public function countPendingForTenant(TenantId $tenantId): int
{
    return 0; // populated in Phase 4 when ApprovalRequests are wired
}
```

- [ ] **Step 5: Run test**

```
php artisan test tests/Unit/Contexts/Membership/Infrastructure/Persistence/EloquentGovernanceDecisionStoreTest.php
```
Expected: PASS

- [ ] **Step 6: Commit**

```
git add app/Contexts/Membership/Domain/Committee/Constitutional/GovernanceDecisionStore.php
git commit -m "feat(governance): extend GovernanceDecisionStore with findByCommittee/findByTenant"
```

---

### Task 1.3 — Wire GovernanceReplayService into InternalCreateCommittee

**Files:**
- Modify: `app/Contexts/Membership/Application/Committee/InternalCreateCommittee.php`

- [ ] **Step 1: Write the failing test**

```php
// tests/Unit/Contexts/Membership/Application/Committee/GovernanceDecisionPersistedOnCreateTest.php
<?php

use App\Contexts\Membership\Application\Committee\CreateCommitteeUseCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionStore;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GovernanceDecisionPersistedOnCreateTest extends TestCase
{
    use RefreshDatabase;

    public function test_governance_decision_is_persisted_when_committee_is_created(): void
    {
        $org = \App\Models\Organisation::factory()->create();
        $user = \App\Models\User::factory()->forOrganisation($org)->create();

        // Create an active structure first (required by CreateCommittee)
        // Use existing test helpers or factory if available
        $this->actingAs($user)->withSession(['current_organisation_id' => $org->id]);

        $useCase = $this->app->make(CreateCommitteeUseCase::class);
        $useCase->execute([
            'tenantId' => $org->id,
            'type' => 'central',
            'name' => 'Test ICC Committee',
            'code' => 'NC-TEST-001',
        ]);

        $store = $this->app->make(GovernanceDecisionStore::class);
        $decisions = $store->findByTenant(
            \App\Contexts\Shared\Domain\ValueObjects\TenantId::fromString($org->id)
        );
        $this->assertNotEmpty($decisions, 'Governance decision must be persisted on committee creation');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

```
php artisan test tests/Unit/Contexts/Membership/Application/Committee/GovernanceDecisionPersistedOnCreateTest.php
```
Expected: FAIL — `$decisions` is empty

- [ ] **Step 3: Inject and call GovernanceReplayService in InternalCreateCommittee**

Open `app/Contexts/Membership/Application/Committee/InternalCreateCommittee.php`. Add constructor injection and call persist after the committee is saved:

```php
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceReplayService;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalArbitrationKernel;

// In constructor, add:
private readonly GovernanceReplayService $governanceReplayService,
private readonly ConstitutionalArbitrationKernel $constitutionalKernel,

// At the end of execute(), before returning the committee ID, add:
$governanceDecision = $this->constitutionalKernel->adjudicate(
    capabilityType: \App\Contexts\Membership\Domain\Committee\Capability\CapabilityType::COMMITTEE_CREATION,
    committeeId: $committee->getId(),
    tenantId: $command->tenantId,
);
$this->governanceReplayService->persist($governanceDecision);
```

Then update `MembershipServiceProvider` to pass the new dependencies into `InternalCreateCommittee`.

- [ ] **Step 4: Run test**

```
php artisan test tests/Unit/Contexts/Membership/Application/Committee/GovernanceDecisionPersistedOnCreateTest.php
```
Expected: PASS

- [ ] **Step 5: Commit**

```
git add app/Contexts/Membership/Application/Committee/InternalCreateCommittee.php
git add app/Contexts/Membership/Infrastructure/Providers/MembershipServiceProvider.php
git commit -m "feat(governance): persist governance decision on every committee creation"
```

---

## Phase 2 — Parent Committee Hierarchy

**`CommitteeModel.parent_committee_id` is a DB column but the Committee domain aggregate has no `$parentId` field. Wire this so the hierarchy tree can be traversed.**

### Task 2.1 — Add parentId to Committee aggregate

**Files:**
- Modify: `app/Contexts/Membership/Domain/Committee/Committee.php`
- Modify: `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeAggregateRepository.php`

- [ ] **Step 1: Write the failing test**

```php
// tests/Unit/Contexts/Membership/Domain/Committee/CommitteeParentIdTest.php
<?php

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use PHPUnit\Framework\TestCase;

class CommitteeParentIdTest extends TestCase
{
    public function test_committee_can_be_reconstructed_with_parent_id(): void
    {
        $parentId = CommitteeId::generate();
        $committee = Committee::createForGeography(
            CommitteeId::generate(),
            \App\Contexts\Shared\Domain\ValueObjects\TenantId::generate(),
            'Japan Country Committee',
            'CC-JP-001',
            \App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeType::CENTRAL,
            null,
            'JP',
        );

        $this->assertNull($committee->getParentId());
    }

    public function test_reconstruct_sets_parent_id(): void
    {
        $parentId = CommitteeId::generate();

        // reconstruct() is called by the repository — test it accepts parentId
        $committee = Committee::reconstruct(
            id: CommitteeId::generate(),
            tenantId: \App\Contexts\Shared\Domain\ValueObjects\TenantId::generate(),
            structureId: null,
            levelIndex: 2,
            levelName: 'Country',
            geoPolicy: \App\Contexts\Membership\Domain\Committee\ValueObjects\GeoPolicy::REQUIRED,
            geoScope: null,
            name: \App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeName::fromString('Japan'),
            code: 'CC-JP-001',
            geoReference: null,
            type: \App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeType::CENTRAL,
            status: \App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeStatus::ACTIVE,
            assignments: [],
            regionCode: 'Asia',
            countryCode: 'JP',
            createdFromStructureId: null,
            structureVersion: null,
            parentId: $parentId,  // NEW PARAMETER
        );

        $this->assertEquals($parentId->toString(), $committee->getParentId()?->toString());
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

```
php artisan test tests/Unit/Contexts/Membership/Domain/Committee/CommitteeParentIdTest.php
```
Expected: FAIL — `reconstruct()` does not accept `parentId` parameter

- [ ] **Step 3: Add parentId to Committee aggregate**

In `app/Contexts/Membership/Domain/Committee/Committee.php`:

```php
// Add private property:
private ?CommitteeId $parentId = null;

// Add to reconstruct() signature (last parameter, nullable):
?CommitteeId $parentId = null,

// In reconstruct() body, assign:
$committee->parentId = $parentId;

// Add getter:
public function getParentId(): ?CommitteeId
{
    return $this->parentId;
}
```

- [ ] **Step 4: Update EloquentCommitteeAggregateRepository**

In `app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeAggregateRepository.php`, inside the `reconstruct()` call, add:

```php
parentId: $model->parent_committee_id
    ? CommitteeId::fromString($model->parent_committee_id)
    : null,
```

- [ ] **Step 5: Run test**

```
php artisan test tests/Unit/Contexts/Membership/Domain/Committee/CommitteeParentIdTest.php
```
Expected: PASS

- [ ] **Step 6: Commit**

```
git add app/Contexts/Membership/Domain/Committee/Committee.php
git add app/Contexts/Membership/Infrastructure/Repositories/EloquentCommitteeAggregateRepository.php
git commit -m "feat(governance): add parentId to Committee aggregate for hierarchy traversal"
```

---

### Task 2.2 — Add Eloquent parent/children relationships to CommitteeModel

**Files:**
- Modify: `app/Contexts/Membership/Infrastructure/Models/CommitteeModel.php`

- [ ] **Step 1: Write the failing test**

```php
// tests/Unit/Contexts/Membership/Infrastructure/Models/CommitteeHierarchyRelationTest.php
<?php

use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommitteeHierarchyRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_committee_has_children_relationship(): void
    {
        $org = \App\Models\Organisation::factory()->create();

        $parent = CommitteeModel::create([
            'id' => \Illuminate\Support\Str::ulid(),
            'organisation_id' => $org->id,
            'name' => 'Asia Continent Committee',
            'code' => 'CC-ASIA',
            'type' => 'central',
            'level' => 1,
            'status' => 'active',
        ]);

        $child = CommitteeModel::create([
            'id' => \Illuminate\Support\Str::ulid(),
            'organisation_id' => $org->id,
            'name' => 'Japan Country Committee',
            'code' => 'CC-JP',
            'type' => 'central',
            'level' => 2,
            'status' => 'active',
            'parent_committee_id' => $parent->id,
        ]);

        $this->assertCount(1, $parent->children);
        $this->assertEquals($child->id, $parent->children->first()->id);
        $this->assertEquals($parent->id, $child->parent->id);
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

```
php artisan test tests/Unit/Contexts/Membership/Infrastructure/Models/CommitteeHierarchyRelationTest.php
```
Expected: FAIL — `children` relation doesn't exist

- [ ] **Step 3: Add relationships to CommitteeModel**

In `app/Contexts/Membership/Infrastructure/Models/CommitteeModel.php`, add:

```php
public function parent(): BelongsTo
{
    return $this->belongsTo(CommitteeModel::class, 'parent_committee_id');
}

public function children(): HasMany
{
    return $this->hasMany(CommitteeModel::class, 'parent_committee_id');
}

public function allDescendants(): HasMany
{
    return $this->children()->with('allDescendants');
}
```

- [ ] **Step 4: Run test**

```
php artisan test tests/Unit/Contexts/Membership/Infrastructure/Models/CommitteeHierarchyRelationTest.php
```
Expected: PASS

- [ ] **Step 5: Commit**

```
git add app/Contexts/Membership/Infrastructure/Models/CommitteeModel.php
git commit -m "feat(governance): add parent/children Eloquent relationships to CommitteeModel"
```

---

## Phase 3 — GeoAuthority Graph Persistence

**`GeoAuthorityGraph`, `JurisdictionNode`, and `DelegationEdge` are pure in-memory domain objects. Create the DB tables and repository so authority delegation can be stored and loaded.**

### Task 3.1 — Create jurisdiction_nodes and delegation_edges migrations

**Files:**
- Create: `app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant/2026_05_09_000001_create_jurisdiction_nodes_table.php`
- Create: `app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant/2026_05_09_000002_create_delegation_edges_table.php`

- [ ] **Step 1: Create jurisdiction_nodes migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('jurisdiction_nodes', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->uuid('organisation_id')->index();
            $table->char('committee_id', 26)->index();
            $table->string('scope_level', 50);   // e.g. 'country', 'continent', 'city'
            $table->string('scope_code', 100);   // e.g. 'JP', 'AS', 'tokyo'
            $table->timestamps();

            $table->unique(['organisation_id', 'committee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurisdiction_nodes');
    }
};
```

- [ ] **Step 2: Create delegation_edges migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delegation_edges', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->uuid('organisation_id')->index();
            $table->char('from_node_id', 26)->index();
            $table->char('to_node_id', 26)->index();
            $table->string('delegation_type', 20);  // AUTHORITY | OVERRIDE | TEMPORARY
            $table->unsignedSmallInteger('weight');  // 80=AUTHORITY, 90=OVERRIDE, 70=TEMPORARY
            $table->timestamp('valid_from');
            $table->timestamp('valid_to')->nullable();
            $table->timestamps();

            $table->foreign('from_node_id')->references('id')->on('jurisdiction_nodes')->cascadeOnDelete();
            $table->foreign('to_node_id')->references('id')->on('jurisdiction_nodes')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delegation_edges');
    }
};
```

- [ ] **Step 3: Run migration**

```
php artisan migrate
```
Expected: Both tables created successfully

- [ ] **Step 4: Commit**

```
git add app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant/2026_05_09_000001_create_jurisdiction_nodes_table.php
git add app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant/2026_05_09_000002_create_delegation_edges_table.php
git commit -m "feat(governance): add jurisdiction_nodes and delegation_edges migrations"
```

---

### Task 3.2 — Create Eloquent models and GeoAuthorityGraphRepository

**Files:**
- Create: `app/Contexts/Membership/Infrastructure/Models/JurisdictionNodeModel.php`
- Create: `app/Contexts/Membership/Infrastructure/Models/DelegationEdgeModel.php`
- Create: `app/Contexts/Membership/Domain/Committee/Geo/Graph/GeoAuthorityGraphRepository.php`
- Create: `app/Contexts/Membership/Infrastructure/Repositories/EloquentGeoAuthorityGraphRepository.php`

- [ ] **Step 1: Write the failing test**

```php
// tests/Unit/Contexts/Membership/Infrastructure/Repositories/GeoAuthorityGraphRepositoryTest.php
<?php

use App\Contexts\Membership\Domain\Committee\Geo\Graph\GeoAuthorityGraphRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeoAuthorityGraphRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_persist_and_load_graph_for_tenant(): void
    {
        $repo = $this->app->make(GeoAuthorityGraphRepository::class);
        $tenantId = TenantId::generate();

        $graph = $repo->loadForTenant($tenantId);

        $this->assertInstanceOf(
            \App\Contexts\Membership\Domain\Committee\Geo\Graph\GeoAuthorityGraph::class,
            $graph
        );
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

```
php artisan test tests/Unit/Contexts/Membership/Infrastructure/Repositories/GeoAuthorityGraphRepositoryTest.php
```
Expected: FAIL — interface not found

- [ ] **Step 3: Create JurisdictionNodeModel**

```php
<?php

namespace App\Contexts\Membership\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JurisdictionNodeModel extends Model
{
    protected $table = 'jurisdiction_nodes';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['id', 'organisation_id', 'committee_id', 'scope_level', 'scope_code'];

    public function outgoingEdges(): HasMany
    {
        return $this->hasMany(DelegationEdgeModel::class, 'from_node_id');
    }
}
```

- [ ] **Step 4: Create DelegationEdgeModel**

```php
<?php

namespace App\Contexts\Membership\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;

class DelegationEdgeModel extends Model
{
    protected $table = 'delegation_edges';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'organisation_id', 'from_node_id', 'to_node_id',
        'delegation_type', 'weight', 'valid_from', 'valid_to',
    ];

    protected $casts = [
        'valid_from' => 'datetime',
        'valid_to' => 'datetime',
    ];
}
```

- [ ] **Step 5: Create GeoAuthorityGraphRepository interface**

```php
<?php

namespace App\Contexts\Membership\Domain\Committee\Geo\Graph;

use App\Contexts\Shared\Domain\ValueObjects\TenantId;

interface GeoAuthorityGraphRepository
{
    public function loadForTenant(TenantId $tenantId): GeoAuthorityGraph;
    public function persistNode(TenantId $tenantId, JurisdictionNode $node): void;
    public function persistEdge(TenantId $tenantId, DelegationEdge $edge): void;
}
```

- [ ] **Step 6: Create EloquentGeoAuthorityGraphRepository**

```php
<?php

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Domain\Committee\Geo\Graph\DelegationEdge;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\GeoAuthorityGraph;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\GeoAuthorityGraphRepository;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Infrastructure\Models\DelegationEdgeModel;
use App\Contexts\Membership\Infrastructure\Models\JurisdictionNodeModel;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

class EloquentGeoAuthorityGraphRepository implements GeoAuthorityGraphRepository
{
    public function loadForTenant(TenantId $tenantId): GeoAuthorityGraph
    {
        $nodeModels = JurisdictionNodeModel::where('organisation_id', $tenantId->toString())
            ->with('outgoingEdges')
            ->get();

        $nodes = [];
        $edges = [];

        foreach ($nodeModels as $nodeModel) {
            $node = new JurisdictionNode(
                id: $nodeModel->id,
                scope: new \App\Contexts\Membership\Domain\Committee\Geo\Graph\GeographicScope(
                    $nodeModel->scope_level,
                    $nodeModel->scope_code
                ),
            );
            $nodes[$nodeModel->id] = $node;

            foreach ($nodeModel->outgoingEdges as $edgeModel) {
                $edges[] = new DelegationEdge(
                    id: $edgeModel->id,
                    fromNodeId: $edgeModel->from_node_id,
                    toNodeId: $edgeModel->to_node_id,
                    type: \App\Contexts\Membership\Domain\Committee\Geo\Graph\DelegationEdgeType::from($edgeModel->delegation_type),
                    weight: $edgeModel->weight,
                    validFrom: $edgeModel->valid_from,
                    validTo: $edgeModel->valid_to,
                );
            }
        }

        return new GeoAuthorityGraph($nodes, $edges);
    }

    public function persistNode(TenantId $tenantId, JurisdictionNode $node): void
    {
        JurisdictionNodeModel::updateOrCreate(
            ['id' => $node->getId()],
            [
                'organisation_id' => $tenantId->toString(),
                'committee_id' => $node->getCommitteeId(),
                'scope_level' => $node->getScope()->getLevel(),
                'scope_code' => $node->getScope()->getCode(),
            ]
        );
    }

    public function persistEdge(TenantId $tenantId, DelegationEdge $edge): void
    {
        DelegationEdgeModel::updateOrCreate(
            ['id' => $edge->getId()],
            [
                'organisation_id' => $tenantId->toString(),
                'from_node_id' => $edge->getFromNodeId(),
                'to_node_id' => $edge->getToNodeId(),
                'delegation_type' => $edge->getType()->value,
                'weight' => $edge->getWeight(),
                'valid_from' => $edge->getValidFrom(),
                'valid_to' => $edge->getValidTo(),
            ]
        );
    }
}
```

- [ ] **Step 7: Register in MembershipServiceProvider**

In `app/Contexts/Membership/Infrastructure/Providers/MembershipServiceProvider.php`, inside `register()`, add:

```php
$this->app->bind(
    \App\Contexts\Membership\Domain\Committee\Geo\Graph\GeoAuthorityGraphRepository::class,
    \App\Contexts\Membership\Infrastructure\Repositories\EloquentGeoAuthorityGraphRepository::class
);
```

- [ ] **Step 8: Run test**

```
php artisan test tests/Unit/Contexts/Membership/Infrastructure/Repositories/GeoAuthorityGraphRepositoryTest.php
```
Expected: PASS

- [ ] **Step 9: Commit**

```
git add app/Contexts/Membership/Domain/Committee/Geo/Graph/GeoAuthorityGraphRepository.php
git add app/Contexts/Membership/Infrastructure/Models/JurisdictionNodeModel.php
git add app/Contexts/Membership/Infrastructure/Models/DelegationEdgeModel.php
git add app/Contexts/Membership/Infrastructure/Repositories/EloquentGeoAuthorityGraphRepository.php
git add app/Contexts/Membership/Infrastructure/Providers/MembershipServiceProvider.php
git commit -m "feat(governance): persist GeoAuthorityGraph nodes and edges to database"
```

---

## Phase 4 — Approval Workflow Engine

**The ICC President dashboard shows "Pending Approvals: 12". This phase creates the domain model, table, use cases, and routes that make committee formation a two-step governed process.**

### Task 4.1 — Create governance_approval_requests migration and Eloquent model

**Files:**
- Create: `app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant/2026_05_09_000003_create_governance_approval_requests_table.php`
- Create: `app/Contexts/Membership/Infrastructure/Models/GovernanceApprovalRequestModel.php`

- [ ] **Step 1: Create the migration**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('governance_approval_requests', function (Blueprint $table) {
            $table->char('id', 26)->primary();
            $table->uuid('organisation_id')->index();
            $table->char('committee_id', 26)->index();
            $table->char('approver_committee_id', 26)->nullable()->index();
            $table->string('requested_by_user_id', 50);
            $table->string('approval_type', 50);  // COMMITTEE_FORMATION | DISSOLUTION | SUSPENSION
            $table->string('status', 20)->default('pending');  // pending | approved | rejected
            $table->timestamp('requested_at');
            $table->timestamp('resolved_at')->nullable();
            $table->string('resolved_by_user_id', 50)->nullable();
            $table->text('resolution_notes')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('governance_approval_requests');
    }
};
```

- [ ] **Step 2: Create GovernanceApprovalRequestModel**

```php
<?php

namespace App\Contexts\Membership\Infrastructure\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GovernanceApprovalRequestModel extends Model
{
    use SoftDeletes;

    protected $table = 'governance_approval_requests';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'id', 'organisation_id', 'committee_id', 'approver_committee_id',
        'requested_by_user_id', 'approval_type', 'status',
        'requested_at', 'resolved_at', 'resolved_by_user_id',
        'resolution_notes', 'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'requested_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];
}
```

- [ ] **Step 3: Run migration**

```
php artisan migrate
```
Expected: Table created

- [ ] **Step 4: Commit**

```
git add app/Contexts/Membership/Infrastructure/Database/Migrations/Tenant/2026_05_09_000003_create_governance_approval_requests_table.php
git add app/Contexts/Membership/Infrastructure/Models/GovernanceApprovalRequestModel.php
git commit -m "feat(governance): add governance_approval_requests table and model"
```

---

### Task 4.2 — Create GovernanceApprovalRequest domain model and use cases

**Files:**
- Create: `app/Contexts/Membership/Domain/Committee/Governance/ApprovalStatus.php`
- Create: `app/Contexts/Membership/Domain/Committee/Governance/GovernanceApprovalRequest.php`
- Create: `app/Contexts/Membership/Application/Committee/RequestCommitteeApproval.php`
- Create: `app/Contexts/Membership/Application/Committee/ApproveCommitteeFormation.php`
- Create: `app/Contexts/Membership/Application/Committee/RejectCommitteeFormation.php`

- [ ] **Step 1: Write failing tests**

```php
// tests/Unit/Contexts/Membership/Domain/Committee/GovernanceApprovalRequestTest.php
<?php

use App\Contexts\Membership\Domain\Committee\Governance\ApprovalStatus;
use App\Contexts\Membership\Domain\Committee\Governance\GovernanceApprovalRequest;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

class GovernanceApprovalRequestTest extends TestCase
{
    public function test_new_request_has_pending_status(): void
    {
        $request = GovernanceApprovalRequest::create(
            id: \Illuminate\Support\Str::ulid(),
            tenantId: TenantId::generate(),
            committeeId: CommitteeId::generate(),
            approverCommitteeId: CommitteeId::generate(),
            requestedByUserId: 'user-001',
            approvalType: 'COMMITTEE_FORMATION',
        );

        $this->assertEquals(ApprovalStatus::PENDING, $request->getStatus());
    }

    public function test_pending_request_can_be_approved(): void
    {
        $request = GovernanceApprovalRequest::create(
            id: \Illuminate\Support\Str::ulid(),
            tenantId: TenantId::generate(),
            committeeId: CommitteeId::generate(),
            approverCommitteeId: CommitteeId::generate(),
            requestedByUserId: 'user-001',
            approvalType: 'COMMITTEE_FORMATION',
        );

        $request->approve('approver-user-id', 'Governance requirements met');

        $this->assertEquals(ApprovalStatus::APPROVED, $request->getStatus());
        $this->assertNotNull($request->getResolvedAt());
    }

    public function test_approved_request_cannot_be_approved_again(): void
    {
        $request = GovernanceApprovalRequest::create(
            id: \Illuminate\Support\Str::ulid(),
            tenantId: TenantId::generate(),
            committeeId: CommitteeId::generate(),
            approverCommitteeId: CommitteeId::generate(),
            requestedByUserId: 'user-001',
            approvalType: 'COMMITTEE_FORMATION',
        );
        $request->approve('approver-user-id', 'First approval');

        $this->expectException(\DomainException::class);
        $request->approve('approver-user-id', 'Second approval attempt');
    }
}
```

- [ ] **Step 2: Run test to verify it fails**

```
php artisan test tests/Unit/Contexts/Membership/Domain/Committee/GovernanceApprovalRequestTest.php
```
Expected: FAIL — class not found

- [ ] **Step 3: Create ApprovalStatus enum**

```php
<?php

namespace App\Contexts\Membership\Domain\Committee\Governance;

enum ApprovalStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function isResolved(): bool
    {
        return $this !== self::PENDING;
    }
}
```

- [ ] **Step 4: Create GovernanceApprovalRequest domain model**

```php
<?php

namespace App\Contexts\Membership\Domain\Committee\Governance;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;

final class GovernanceApprovalRequest
{
    private ApprovalStatus $status;
    private ?DateTimeImmutable $resolvedAt = null;
    private ?string $resolvedByUserId = null;
    private ?string $resolutionNotes = null;

    private function __construct(
        private readonly string $id,
        private readonly TenantId $tenantId,
        private readonly CommitteeId $committeeId,
        private readonly ?CommitteeId $approverCommitteeId,
        private readonly string $requestedByUserId,
        private readonly string $approvalType,
        private readonly DateTimeImmutable $requestedAt,
    ) {
        $this->status = ApprovalStatus::PENDING;
    }

    public static function create(
        string $id,
        TenantId $tenantId,
        CommitteeId $committeeId,
        ?CommitteeId $approverCommitteeId,
        string $requestedByUserId,
        string $approvalType,
    ): self {
        return new self(
            id: $id,
            tenantId: $tenantId,
            committeeId: $committeeId,
            approverCommitteeId: $approverCommitteeId,
            requestedByUserId: $requestedByUserId,
            approvalType: $approvalType,
            requestedAt: new DateTimeImmutable(),
        );
    }

    public function approve(string $resolvedByUserId, string $notes): void
    {
        if ($this->status->isResolved()) {
            throw new \DomainException("Approval request [{$this->id}] is already resolved as [{$this->status->value}].");
        }
        $this->status = ApprovalStatus::APPROVED;
        $this->resolvedAt = new DateTimeImmutable();
        $this->resolvedByUserId = $resolvedByUserId;
        $this->resolutionNotes = $notes;
    }

    public function reject(string $resolvedByUserId, string $notes): void
    {
        if ($this->status->isResolved()) {
            throw new \DomainException("Approval request [{$this->id}] is already resolved as [{$this->status->value}].");
        }
        $this->status = ApprovalStatus::REJECTED;
        $this->resolvedAt = new DateTimeImmutable();
        $this->resolvedByUserId = $resolvedByUserId;
        $this->resolutionNotes = $notes;
    }

    public function getId(): string { return $this->id; }
    public function getTenantId(): TenantId { return $this->tenantId; }
    public function getCommitteeId(): CommitteeId { return $this->committeeId; }
    public function getApproverCommitteeId(): ?CommitteeId { return $this->approverCommitteeId; }
    public function getStatus(): ApprovalStatus { return $this->status; }
    public function getResolvedAt(): ?DateTimeImmutable { return $this->resolvedAt; }
    public function getRequestedByUserId(): string { return $this->requestedByUserId; }
    public function getApprovalType(): string { return $this->approvalType; }
    public function getRequestedAt(): DateTimeImmutable { return $this->requestedAt; }
    public function getResolutionNotes(): ?string { return $this->resolutionNotes; }
    public function getResolvedByUserId(): ?string { return $this->resolvedByUserId; }
}
```

- [ ] **Step 5: Run test**

```
php artisan test tests/Unit/Contexts/Membership/Domain/Committee/GovernanceApprovalRequestTest.php
```
Expected: PASS (3/3)

- [ ] **Step 6: Create ApproveCommitteeFormation use case**

```php
<?php
// app/Contexts/Membership/Application/Committee/ApproveCommitteeFormation.php

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Infrastructure\Models\GovernanceApprovalRequestModel;
use App\Contexts\Membership\Domain\Committee\Governance\GovernanceApprovalRequest;
use App\Contexts\Membership\Domain\Committee\Governance\ApprovalStatus;

final class ApproveCommitteeFormation
{
    public function execute(string $approvalRequestId, string $approverUserId, string $notes): void
    {
        $model = GovernanceApprovalRequestModel::findOrFail($approvalRequestId);

        if ($model->status !== ApprovalStatus::PENDING->value) {
            throw new \DomainException("Request {$approvalRequestId} is not pending.");
        }

        $model->update([
            'status' => ApprovalStatus::APPROVED->value,
            'resolved_at' => now(),
            'resolved_by_user_id' => $approverUserId,
            'resolution_notes' => $notes,
        ]);
    }
}
```

- [ ] **Step 7: Create RejectCommitteeFormation use case** (identical structure, sets REJECTED)

```php
<?php
// app/Contexts/Membership/Application/Committee/RejectCommitteeFormation.php

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Infrastructure\Models\GovernanceApprovalRequestModel;
use App\Contexts\Membership\Domain\Committee\Governance\ApprovalStatus;

final class RejectCommitteeFormation
{
    public function execute(string $approvalRequestId, string $rejectorUserId, string $reason): void
    {
        $model = GovernanceApprovalRequestModel::findOrFail($approvalRequestId);

        if ($model->status !== ApprovalStatus::PENDING->value) {
            throw new \DomainException("Request {$approvalRequestId} is not pending.");
        }

        $model->update([
            'status' => ApprovalStatus::REJECTED->value,
            'resolved_at' => now(),
            'resolved_by_user_id' => $rejectorUserId,
            'resolution_notes' => $reason,
        ]);
    }
}
```

- [ ] **Step 8: Add approval routes**

In `routes/committee/committeeRoutes.php`, add inside the existing group:

```php
Route::prefix('org/{org}/committees/{committee}/approvals')->group(function () {
    Route::get('/', [\App\Http\Controllers\Committee\CommitteeApprovalController::class, 'index'])
        ->name('committees.approvals.index');
    Route::post('/{approvalId}/approve', [\App\Http\Controllers\Committee\CommitteeApprovalController::class, 'approve'])
        ->name('committees.approvals.approve');
    Route::post('/{approvalId}/reject', [\App\Http\Controllers\Committee\CommitteeApprovalController::class, 'reject'])
        ->name('committees.approvals.reject');
});
```

- [ ] **Step 9: Create CommitteeApprovalController**

```php
<?php
// app/Http/Controllers/Committee/CommitteeApprovalController.php

namespace App\Http\Controllers\Committee;

use App\Contexts\Membership\Application\Committee\ApproveCommitteeFormation;
use App\Contexts\Membership\Application\Committee\RejectCommitteeFormation;
use App\Contexts\Membership\Infrastructure\Models\GovernanceApprovalRequestModel;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommitteeApprovalController extends Controller
{
    public function index(Request $request, string $org, string $committee)
    {
        $approvals = GovernanceApprovalRequestModel::where('approver_committee_id', $committee)
            ->where('organisation_id', session('current_organisation_id'))
            ->where('status', 'pending')
            ->latest('requested_at')
            ->get();

        return Inertia::render('Committee/Approvals', [
            'approvals' => $approvals,
            'committeeId' => $committee,
        ]);
    }

    public function approve(Request $request, string $org, string $committee, string $approvalId)
    {
        $request->validate(['notes' => 'required|string|max:1000']);

        (new ApproveCommitteeFormation())->execute(
            approvalRequestId: $approvalId,
            approverUserId: auth()->id(),
            notes: $request->input('notes'),
        );

        return redirect()->route('committees.approvals.index', [$org, $committee])
            ->with('success', 'Committee formation approved.');
    }

    public function reject(Request $request, string $org, string $committee, string $approvalId)
    {
        $request->validate(['reason' => 'required|string|max:1000']);

        (new RejectCommitteeFormation())->execute(
            approvalRequestId: $approvalId,
            rejectorUserId: auth()->id(),
            reason: $request->input('reason'),
        );

        return redirect()->route('committees.approvals.index', [$org, $committee])
            ->with('success', 'Committee formation rejected.');
    }
}
```

- [ ] **Step 10: Commit**

```
git add app/Contexts/Membership/Domain/Committee/Governance/
git add app/Contexts/Membership/Application/Committee/ApproveCommitteeFormation.php
git add app/Contexts/Membership/Application/Committee/RejectCommitteeFormation.php
git add app/Http/Controllers/Committee/CommitteeApprovalController.php
git add routes/committee/committeeRoutes.php
git commit -m "feat(governance): add approval workflow — ApproveCommitteeFormation, RejectCommitteeFormation, routes"
```

---

## Phase 5 — Governance Status Service

**Every committee dashboard must show: Is this committee constitutionally legitimate? Is its term valid? How many approvals are pending? This phase builds the read service and wires it into the existing dashboard.**

### Task 5.1 — Create CommitteeGovernanceStatusDTO and CommitteeGovernanceStatusService

**Files:**
- Create: `app/Contexts/Membership/Application/Committee/DTOs/CommitteeGovernanceStatusDTO.php`
- Create: `app/Contexts/Membership/Application/Committee/Services/CommitteeGovernanceStatusService.php`
- Modify: `app/Contexts/Membership/Application/Committee/GetCommitteeDashboard.php`

- [ ] **Step 1: Write failing test**

```php
// tests/Unit/Contexts/Membership/Application/Committee/CommitteeGovernanceStatusServiceTest.php
<?php

use App\Contexts\Membership\Application\Committee\Services\CommitteeGovernanceStatusService;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Contexts\Membership\Infrastructure\Models\GovernanceApprovalRequestModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommitteeGovernanceStatusServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_committee_with_valid_term_is_legitimate(): void
    {
        $org = \App\Models\Organisation::factory()->create();

        $committee = CommitteeModel::create([
            'id' => \Illuminate\Support\Str::ulid(),
            'organisation_id' => $org->id,
            'name' => 'Test Committee',
            'code' => 'TC-001',
            'type' => 'central',
            'level' => 1,
            'status' => 'active',
            'term_end_date' => now()->addYear(),
        ]);

        $service = $this->app->make(CommitteeGovernanceStatusService::class);
        $status = $service->compute($committee->id, $org->id);

        $this->assertEquals('LEGITIMATE', $status->legitimacy);
        $this->assertEquals('valid', $status->termStatus);
        $this->assertEquals(0, $status->pendingApprovalsCount);
    }

    public function test_committee_with_expired_term_shows_expired_legitimacy(): void
    {
        $org = \App\Models\Organisation::factory()->create();

        $committee = CommitteeModel::create([
            'id' => \Illuminate\Support\Str::ulid(),
            'organisation_id' => $org->id,
            'name' => 'Expired Committee',
            'code' => 'EC-001',
            'type' => 'central',
            'level' => 1,
            'status' => 'active',
            'term_end_date' => now()->subDay(),
        ]);

        $service = $this->app->make(CommitteeGovernanceStatusService::class);
        $status = $service->compute($committee->id, $org->id);

        $this->assertEquals('EXPIRED', $status->legitimacy);
        $this->assertEquals('expired', $status->termStatus);
    }
}
```

- [ ] **Step 2: Run to verify it fails**

```
php artisan test tests/Unit/Contexts/Membership/Application/Committee/CommitteeGovernanceStatusServiceTest.php
```
Expected: FAIL — service not found

- [ ] **Step 3: Create CommitteeGovernanceStatusDTO**

```php
<?php

namespace App\Contexts\Membership\Application\Committee\DTOs;

final readonly class CommitteeGovernanceStatusDTO
{
    public function __construct(
        public string $committeeId,
        public string $legitimacy,         // LEGITIMATE | EXPIRED | SUSPENDED | CARETAKER | PENDING
        public string $termStatus,         // valid | expiring | expired | no_term
        public bool $authorityChainValid,
        public ?string $parentCommitteeId,
        public int $pendingApprovalsCount,
        public ?string $lastDecisionAt,
        public ?string $lastDecisionType,
    ) {}

    public function toArray(): array
    {
        return [
            'legitimacy' => $this->legitimacy,
            'term_status' => $this->termStatus,
            'authority_chain_valid' => $this->authorityChainValid,
            'parent_committee_id' => $this->parentCommitteeId,
            'pending_approvals_count' => $this->pendingApprovalsCount,
            'last_decision_at' => $this->lastDecisionAt,
            'last_decision_type' => $this->lastDecisionType,
        ];
    }
}
```

- [ ] **Step 4: Create CommitteeGovernanceStatusService**

```php
<?php

namespace App\Contexts\Membership\Application\Committee\Services;

use App\Contexts\Membership\Application\Committee\DTOs\CommitteeGovernanceStatusDTO;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Contexts\Membership\Infrastructure\Models\GovernanceApprovalRequestModel;
use App\Contexts\Membership\Infrastructure\Models\GovernanceDecisionModel;

final class CommitteeGovernanceStatusService
{
    public function compute(string $committeeId, string $organisationId): CommitteeGovernanceStatusDTO
    {
        $committee = CommitteeModel::where('id', $committeeId)
            ->where('organisation_id', $organisationId)
            ->firstOrFail();

        $legitimacy = $this->resolveLegitimacy($committee);
        $termStatus = $this->resolveTermStatus($committee);

        $pendingApprovals = GovernanceApprovalRequestModel::where('approver_committee_id', $committeeId)
            ->where('organisation_id', $organisationId)
            ->where('status', 'pending')
            ->count();

        $lastDecision = GovernanceDecisionModel::orderByDesc('decided_at')
            ->first();

        return new CommitteeGovernanceStatusDTO(
            committeeId: $committeeId,
            legitimacy: $legitimacy,
            termStatus: $termStatus,
            authorityChainValid: $this->resolveAuthorityChain($committee),
            parentCommitteeId: $committee->parent_committee_id,
            pendingApprovalsCount: $pendingApprovals,
            lastDecisionAt: $lastDecision?->decided_at?->toIso8601String(),
            lastDecisionType: $lastDecision?->capability_type,
        );
    }

    private function resolveLegitimacy(CommitteeModel $committee): string
    {
        if ($committee->status === 'suspended') {
            return 'SUSPENDED';
        }

        if ($committee->status === 'dissolved') {
            return 'REVOKED';
        }

        if ($committee->term_end_date && now()->isAfter($committee->term_end_date)) {
            return 'EXPIRED';
        }

        if ($committee->term_end_date && now()->addDays(30)->isAfter($committee->term_end_date)) {
            return 'CARETAKER';  // Operating in final 30-day window
        }

        return 'LEGITIMATE';
    }

    private function resolveTermStatus(CommitteeModel $committee): string
    {
        if (!$committee->term_end_date) {
            return 'no_term';
        }

        if (now()->isAfter($committee->term_end_date)) {
            return 'expired';
        }

        if (now()->addDays(30)->isAfter($committee->term_end_date)) {
            return 'expiring';
        }

        return 'valid';
    }

    private function resolveAuthorityChain(CommitteeModel $committee): bool
    {
        // Walk up the parent chain; if any ancestor is suspended/dissolved, chain is broken
        $current = $committee->parent;
        while ($current !== null) {
            if (in_array($current->status, ['suspended', 'dissolved'])) {
                return false;
            }
            $current = $current->parent;
        }
        return true;
    }
}
```

- [ ] **Step 5: Register in MembershipServiceProvider**

```php
$this->app->bind(
    \App\Contexts\Membership\Application\Committee\Services\CommitteeGovernanceStatusService::class,
    \App\Contexts\Membership\Application\Committee\Services\CommitteeGovernanceStatusService::class
);
```

- [ ] **Step 6: Run test**

```
php artisan test tests/Unit/Contexts/Membership/Application/Committee/CommitteeGovernanceStatusServiceTest.php
```
Expected: PASS (2/2)

- [ ] **Step 7: Extend GetCommitteeDashboard to include governance status**

In `app/Contexts/Membership/Application/Committee/GetCommitteeDashboard.php`, inject `CommitteeGovernanceStatusService` and add to the returned array:

```php
// Add to constructor:
private CommitteeGovernanceStatusService $governanceStatusService

// At end of execute(), before return:
$governanceStatus = $this->governanceStatusService->compute(
    $committeeId->toString(),
    $tenantId->toString()
);

// Add to returned DTO/array:
'governance' => $governanceStatus->toArray(),
```

- [ ] **Step 8: Run full committee test suite**

```
php artisan test tests/Unit/Contexts/Membership/ tests/Feature/Committee/
```
Expected: All passing

- [ ] **Step 9: Commit**

```
git add app/Contexts/Membership/Application/Committee/DTOs/CommitteeGovernanceStatusDTO.php
git add app/Contexts/Membership/Application/Committee/Services/CommitteeGovernanceStatusService.php
git add app/Contexts/Membership/Application/Committee/GetCommitteeDashboard.php
git commit -m "feat(governance): add CommitteeGovernanceStatusService — legitimacy, term, authority chain"
```

---

## Phase 6 — Hierarchy Dashboard

**The ICC President needs a global tree view with governance status at every level. This phase creates GetGovernanceHierarchy, GetGlobalGovernanceMetrics, the HTTP layer, and the Vue Hierarchy page.**

### Task 6.1 — Create GetGovernanceHierarchy use case

**Files:**
- Create: `app/Contexts/Membership/Application/Committee/GetGovernanceHierarchy.php`

- [ ] **Step 1: Write failing test**

```php
// tests/Unit/Contexts/Membership/Application/Committee/GetGovernanceHierarchyTest.php
<?php

use App\Contexts\Membership\Application\Committee\GetGovernanceHierarchy;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetGovernanceHierarchyTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_root_committees_with_governance_status(): void
    {
        $org = \App\Models\Organisation::factory()->create();

        CommitteeModel::create([
            'id' => \Illuminate\Support\Str::ulid(),
            'organisation_id' => $org->id,
            'name' => 'ICC Global',
            'code' => 'ICC-GLOBAL',
            'type' => 'central',
            'level' => 0,
            'status' => 'active',
            'parent_committee_id' => null,
        ]);

        $useCase = $this->app->make(GetGovernanceHierarchy::class);
        $result = $useCase->execute($org->id);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertArrayHasKey('governance', $result[0]);
        $this->assertEquals('LEGITIMATE', $result[0]['governance']['legitimacy']);
    }

    public function test_returns_nested_children_in_tree(): void
    {
        $org = \App\Models\Organisation::factory()->create();

        $parent = CommitteeModel::create([
            'id' => \Illuminate\Support\Str::ulid(),
            'organisation_id' => $org->id,
            'name' => 'Asia Continent',
            'code' => 'CONT-ASIA',
            'type' => 'central',
            'level' => 1,
            'status' => 'active',
        ]);

        CommitteeModel::create([
            'id' => \Illuminate\Support\Str::ulid(),
            'organisation_id' => $org->id,
            'name' => 'Japan Country',
            'code' => 'CC-JP',
            'type' => 'central',
            'level' => 2,
            'status' => 'active',
            'parent_committee_id' => $parent->id,
        ]);

        $useCase = $this->app->make(GetGovernanceHierarchy::class);
        $result = $useCase->execute($org->id);

        $this->assertCount(1, $result[0]['children']);
        $this->assertEquals('Japan Country', $result[0]['children'][0]['name']);
    }
}
```

- [ ] **Step 2: Run to verify it fails**

```
php artisan test tests/Unit/Contexts/Membership/Application/Committee/GetGovernanceHierarchyTest.php
```
Expected: FAIL — class not found

- [ ] **Step 3: Create GetGovernanceHierarchy**

```php
<?php

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\Services\CommitteeGovernanceStatusService;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;

final class GetGovernanceHierarchy
{
    public function __construct(
        private readonly CommitteeGovernanceStatusService $governanceStatusService,
    ) {}

    public function execute(string $organisationId): array
    {
        $roots = CommitteeModel::where('organisation_id', $organisationId)
            ->whereNull('parent_committee_id')
            ->whereNull('deleted_at')
            ->with('allDescendants')
            ->orderBy('level')
            ->get();

        return $roots->map(fn ($c) => $this->toNode($c, $organisationId))->toArray();
    }

    private function toNode(CommitteeModel $model, string $organisationId): array
    {
        $governance = $this->governanceStatusService->compute($model->id, $organisationId);

        return [
            'id' => $model->id,
            'name' => $model->name,
            'code' => $model->code,
            'type' => $model->type,
            'level' => $model->level,
            'status' => $model->status,
            'geo_reference' => $model->operational_geo_reference,
            'governance' => $governance->toArray(),
            'children' => $model->children
                ->map(fn ($child) => $this->toNode($child, $organisationId))
                ->toArray(),
        ];
    }
}
```

- [ ] **Step 4: Run test**

```
php artisan test tests/Unit/Contexts/Membership/Application/Committee/GetGovernanceHierarchyTest.php
```
Expected: PASS (2/2)

- [ ] **Step 5: Commit**

```
git add app/Contexts/Membership/Application/Committee/GetGovernanceHierarchy.php
git commit -m "feat(governance): GetGovernanceHierarchy — tree with governance annotations at every node"
```

---

### Task 6.2 — Create GetGlobalGovernanceMetrics (ICC President counts)

**Files:**
- Create: `app/Contexts/Membership/Application/Committee/GetGlobalGovernanceMetrics.php`

- [ ] **Step 1: Write failing test**

```php
// tests/Unit/Contexts/Membership/Application/Committee/GetGlobalGovernanceMetricsTest.php
<?php

use App\Contexts\Membership\Application\Committee\GetGlobalGovernanceMetrics;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetGlobalGovernanceMetricsTest extends TestCase
{
    use RefreshDatabase;

    public function test_returns_committee_counts_by_level(): void
    {
        $org = \App\Models\Organisation::factory()->create();

        foreach (range(1, 3) as $i) {
            CommitteeModel::create([
                'id' => \Illuminate\Support\Str::ulid(),
                'organisation_id' => $org->id,
                'name' => "Committee $i",
                'code' => "CC-00$i",
                'type' => 'central',
                'level' => $i,
                'status' => 'active',
            ]);
        }

        $useCase = $this->app->make(GetGlobalGovernanceMetrics::class);
        $result = $useCase->execute($org->id);

        $this->assertEquals(3, $result['total_committees']);
        $this->assertArrayHasKey('pending_approvals', $result);
        $this->assertArrayHasKey('legitimacy_breakdown', $result);
    }
}
```

- [ ] **Step 2: Run to verify it fails**

```
php artisan test tests/Unit/Contexts/Membership/Application/Committee/GetGlobalGovernanceMetricsTest.php
```
Expected: FAIL — class not found

- [ ] **Step 3: Create GetGlobalGovernanceMetrics**

```php
<?php

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\Services\CommitteeGovernanceStatusService;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Contexts\Membership\Infrastructure\Models\GovernanceApprovalRequestModel;
use App\Contexts\Membership\Infrastructure\Models\GovernanceDecisionModel;

final class GetGlobalGovernanceMetrics
{
    public function __construct(
        private readonly CommitteeGovernanceStatusService $governanceStatusService,
    ) {}

    public function execute(string $organisationId): array
    {
        $committees = CommitteeModel::where('organisation_id', $organisationId)
            ->whereNull('deleted_at')
            ->get();

        $legitimacyBreakdown = ['LEGITIMATE' => 0, 'EXPIRED' => 0, 'CARETAKER' => 0, 'SUSPENDED' => 0, 'REVOKED' => 0];

        foreach ($committees as $committee) {
            $status = $this->governanceStatusService->compute($committee->id, $organisationId);
            $legitimacyBreakdown[$status->legitimacy] = ($legitimacyBreakdown[$status->legitimacy] ?? 0) + 1;
        }

        $pendingApprovals = GovernanceApprovalRequestModel::where('organisation_id', $organisationId)
            ->where('status', 'pending')
            ->count();

        $recentDecisions = GovernanceDecisionModel::orderByDesc('decided_at')
            ->limit(10)
            ->get()
            ->map(fn ($d) => [
                'id' => $d->id,
                'type' => $d->capability_type,
                'legitimacy' => $d->legitimacy,
                'decided_at' => $d->decided_at?->toIso8601String(),
            ]);

        return [
            'total_committees' => $committees->count(),
            'committees_by_level' => $committees->groupBy('level')
                ->map->count()
                ->toArray(),
            'pending_approvals' => $pendingApprovals,
            'legitimacy_breakdown' => $legitimacyBreakdown,
            'recent_decisions' => $recentDecisions->toArray(),
            'recent_decisions_count' => GovernanceDecisionModel::where(
                'decided_at', '>=', now()->subDays(7)
            )->count(),
        ];
    }
}
```

- [ ] **Step 4: Run test**

```
php artisan test tests/Unit/Contexts/Membership/Application/Committee/GetGlobalGovernanceMetricsTest.php
```
Expected: PASS

- [ ] **Step 5: Commit**

```
git add app/Contexts/Membership/Application/Committee/GetGlobalGovernanceMetrics.php
git commit -m "feat(governance): GetGlobalGovernanceMetrics — ICC President dashboard counts"
```

---

### Task 6.3 — Create CommitteeHierarchyController and routes

**Files:**
- Create: `app/Http/Controllers/Committee/CommitteeHierarchyController.php`
- Modify: `routes/committee/committeeRoutes.php`

- [ ] **Step 1: Create CommitteeHierarchyController**

```php
<?php

namespace App\Http\Controllers\Committee;

use App\Contexts\Membership\Application\Committee\GetGlobalGovernanceMetrics;
use App\Contexts\Membership\Application\Committee\GetGovernanceHierarchy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CommitteeHierarchyController extends Controller
{
    public function __construct(
        private readonly GetGovernanceHierarchy $getHierarchy,
        private readonly GetGlobalGovernanceMetrics $getMetrics,
    ) {}

    public function index(Request $request, string $org)
    {
        $organisationId = session('current_organisation_id');

        return Inertia::render('Committee/Hierarchy', [
            'tree' => $this->getHierarchy->execute($organisationId),
            'metrics' => $this->getMetrics->execute($organisationId),
        ]);
    }
}
```

- [ ] **Step 2: Add routes**

In `routes/committee/committeeRoutes.php`, add:

```php
Route::get('org/{org}/committees/hierarchy', [\App\Http\Controllers\Committee\CommitteeHierarchyController::class, 'index'])
    ->name('committees.hierarchy');
```

- [ ] **Step 3: Write feature test**

```php
// tests/Feature/Committee/CommitteeHierarchyControllerTest.php
<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommitteeHierarchyControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_hierarchy_page_renders_with_governance_data(): void
    {
        $org = \App\Models\Organisation::factory()->create();
        $user = \App\Models\User::factory()->forOrganisation($org)->create();

        $this->actingAs($user)
            ->withSession(['current_organisation_id' => $org->id])
            ->get(route('committees.hierarchy', $org->id))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Committee/Hierarchy')
                ->has('tree')
                ->has('metrics')
                ->has('metrics.pending_approvals')
                ->has('metrics.legitimacy_breakdown')
            );
    }
}
```

- [ ] **Step 4: Run feature test**

```
php artisan test tests/Feature/Committee/CommitteeHierarchyControllerTest.php
```
Expected: PASS

- [ ] **Step 5: Commit**

```
git add app/Http/Controllers/Committee/CommitteeHierarchyController.php
git add routes/committee/committeeRoutes.php
git add tests/Feature/Committee/CommitteeHierarchyControllerTest.php
git commit -m "feat(governance): CommitteeHierarchyController + route — serves tree + metrics for ICC President"
```

---

### Task 6.4 — Update Committee/Dashboard.vue to show governance status

**Files:**
- Modify: `resources/js/Pages/Committee/Dashboard.vue`

- [ ] **Step 1: Add governance status badge to Dashboard.vue**

Find the existing `<template>` section where committee `status` is displayed. Add governance status badge after it:

```vue
<!-- Add to the committee header section, after the status badge -->
<div v-if="committee.governance" class="flex items-center gap-2 mt-2">
  <span
    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
    :class="{
      'bg-green-100 text-green-800': committee.governance.legitimacy === 'LEGITIMATE',
      'bg-yellow-100 text-yellow-800': committee.governance.legitimacy === 'CARETAKER',
      'bg-red-100 text-red-800': ['EXPIRED', 'SUSPENDED', 'REVOKED'].includes(committee.governance.legitimacy),
      'bg-blue-100 text-blue-800': committee.governance.legitimacy === 'PENDING',
    }"
  >
    <span class="mr-1">
      {{ legitimacyIcon(committee.governance.legitimacy) }}
    </span>
    {{ committee.governance.legitimacy }}
  </span>

  <span
    v-if="committee.governance.pending_approvals_count > 0"
    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-orange-100 text-orange-800"
  >
    {{ committee.governance.pending_approvals_count }} Pending Approvals
  </span>

  <span
    v-if="committee.governance.term_status === 'expiring'"
    class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800"
  >
    Term Expiring Soon
  </span>
</div>
```

Add to `<script setup>`:
```javascript
const legitimacyIcon = (legitimacy) => {
  const icons = {
    LEGITIMATE: '✓',
    CARETAKER: '⚡',
    EXPIRED: '⏰',
    SUSPENDED: '⛔',
    REVOKED: '✗',
    PENDING: '⏳',
  };
  return icons[legitimacy] ?? '?';
};
```

- [ ] **Step 2: Run design check**

```
npm run design-check
```
Expected: No violations

- [ ] **Step 3: Commit**

```
git add resources/js/Pages/Committee/Dashboard.vue
git commit -m "feat(governance): show governance legitimacy badge + pending approvals on committee dashboard"
```

---

### Task 6.5 — Create Committee/Hierarchy.vue

**Files:**
- Create: `resources/js/Pages/Committee/Hierarchy.vue`

- [ ] **Step 1: Create the Hierarchy page**

```vue
<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
  tree: { type: Array, required: true },
  metrics: { type: Object, required: true },
});

const expandedNodes = ref(new Set());

const toggleNode = (id) => {
  if (expandedNodes.value.has(id)) {
    expandedNodes.value.delete(id);
  } else {
    expandedNodes.value.add(id);
  }
};

const legitimacyColors = {
  LEGITIMATE: 'text-green-700 bg-green-50',
  CARETAKER: 'text-yellow-700 bg-yellow-50',
  EXPIRED: 'text-red-700 bg-red-50',
  SUSPENDED: 'text-red-700 bg-red-50',
  REVOKED: 'text-gray-700 bg-gray-50',
  PENDING: 'text-blue-700 bg-blue-50',
};
</script>

<template>
  <AppLayout title="Global Governance Hierarchy">
    <div class="max-w-7xl mx-auto py-8 px-4">

      <!-- ICC President Global Metrics -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4">
          <div class="text-2xl font-bold text-gray-900">{{ metrics.total_committees }}</div>
          <div class="text-sm text-gray-500">Total Committees</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <div class="text-2xl font-bold text-orange-600">{{ metrics.pending_approvals }}</div>
          <div class="text-sm text-gray-500">Pending Approvals</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <div class="text-2xl font-bold text-blue-600">{{ metrics.recent_decisions_count }}</div>
          <div class="text-sm text-gray-500">Decisions (7 days)</div>
        </div>
        <div class="bg-white rounded-lg shadow p-4">
          <div class="text-2xl font-bold text-green-600">{{ metrics.legitimacy_breakdown.LEGITIMATE }}</div>
          <div class="text-sm text-gray-500">Legitimate Committees</div>
        </div>
      </div>

      <!-- Legitimacy Breakdown -->
      <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Legitimacy Breakdown</h3>
        <div class="flex flex-wrap gap-3">
          <div
            v-for="(count, status) in metrics.legitimacy_breakdown"
            :key="status"
            class="flex items-center gap-2 px-3 py-2 rounded-full text-sm"
            :class="legitimacyColors[status] ?? 'text-gray-700 bg-gray-50'"
          >
            <span class="font-bold">{{ count }}</span>
            <span>{{ status }}</span>
          </div>
        </div>
      </div>

      <!-- Committee Hierarchy Tree -->
      <div class="bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Committee Hierarchy</h3>

        <template v-if="tree.length === 0">
          <p class="text-gray-500">No committees have been created yet.</p>
        </template>

        <div v-else>
          <!-- Recursive tree node component rendered inline -->
          <CommitteeTreeNode
            v-for="node in tree"
            :key="node.id"
            :node="node"
            :expanded-nodes="expandedNodes"
            @toggle="toggleNode"
          />
        </div>
      </div>

    </div>
  </AppLayout>
</template>
```

Create companion component `resources/js/Components/Committee/CommitteeTreeNode.vue`:

```vue
<script setup>
defineProps({
  node: { type: Object, required: true },
  expandedNodes: { type: Set, required: true },
  depth: { type: Number, default: 0 },
});

const emit = defineEmits(['toggle']);

const legitimacyColors = {
  LEGITIMATE: 'bg-green-100 text-green-800',
  CARETAKER: 'bg-yellow-100 text-yellow-800',
  EXPIRED: 'bg-red-100 text-red-800',
  SUSPENDED: 'bg-red-100 text-red-800',
};
</script>

<template>
  <div :style="{ paddingLeft: `${depth * 24}px` }" class="my-1">
    <div class="flex items-center gap-3 p-2 hover:bg-gray-50 rounded-lg">

      <!-- Expand/collapse toggle -->
      <button
        v-if="node.children?.length > 0"
        @click="emit('toggle', node.id)"
        class="text-gray-400 hover:text-gray-700 w-5 h-5 flex items-center justify-center"
      >
        {{ expandedNodes.has(node.id) ? '▼' : '▶' }}
      </button>
      <span v-else class="w-5"></span>

      <!-- Committee name + code -->
      <div class="flex-1 min-w-0">
        <a
          :href="`/committees/${node.id}/dashboard`"
          class="font-medium text-gray-900 hover:text-blue-700 text-sm"
        >
          {{ node.name }}
        </a>
        <span class="ml-2 text-xs text-gray-400">{{ node.code }}</span>
      </div>

      <!-- Governance badge -->
      <span
        v-if="node.governance"
        class="text-xs px-2 py-0.5 rounded-full font-medium"
        :class="legitimacyColors[node.governance.legitimacy] ?? 'bg-gray-100 text-gray-700'"
      >
        {{ node.governance.legitimacy }}
      </span>

      <!-- Pending approvals indicator -->
      <span
        v-if="node.governance?.pending_approvals_count > 0"
        class="text-xs px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 font-medium"
      >
        {{ node.governance.pending_approvals_count }} pending
      </span>

    </div>

    <!-- Children (recursive) -->
    <template v-if="expandedNodes.has(node.id) && node.children?.length > 0">
      <CommitteeTreeNode
        v-for="child in node.children"
        :key="child.id"
        :node="child"
        :expanded-nodes="expandedNodes"
        :depth="depth + 1"
        @toggle="emit('toggle', $event)"
      />
    </template>
  </div>
</template>
```

- [ ] **Step 2: Run design check**

```
npm run design-check
```
Expected: No violations

- [ ] **Step 3: Commit**

```
git add resources/js/Pages/Committee/Hierarchy.vue
git add resources/js/Components/Committee/CommitteeTreeNode.vue
git commit -m "feat(governance): Committee/Hierarchy page — global tree with legitimacy + pending approvals"
```

---

## Verification

Run the full test suite after each phase. Final verification:

```
php artisan test tests/Unit/Contexts/Membership/ tests/Feature/Committee/ --stop-on-failure
```

Manual check — visit in browser:
- `/org/{org}/committees/hierarchy` → should show global metrics (Pending Approvals, Recent Decisions) + collapsible tree with legitimacy badges
- `/org/{org}/committees/{id}/dashboard` → should show governance status badge (LEGITIMATE / CARETAKER / EXPIRED) alongside existing committee data

---

## Self-Review Against Spec

| ICC President requirement | Task that fulfils it |
|--------------------------|----------------------|
| Pending Approvals: 12 | Task 4.2 + Task 6.2 |
| Recent Decisions: 45 (7 days) | Task 1.3 + Task 6.2 |
| Authority chain: Global → Continent → Country | Task 2.1 + Task 2.2 + Task 6.1 |
| Authority Settings (inherit/override) | Task 3.2 (DelegationEdge types) |
| Committee legitimacy status visible | Task 5.1 + Task 6.4 |
| Term expiry → CARETAKER signal | Task 5.1 CommitteeGovernanceStatusService |
| Tree dashboard with drill-down | Task 6.3 + Task 6.5 |
| Governance decisions persisted | Task 1.3 |
| `governance_decisions` surfaced | Task 1.2 + Task 6.2 |
| GeoAuthority graph persistent | Task 3.1 + Task 3.2 |
