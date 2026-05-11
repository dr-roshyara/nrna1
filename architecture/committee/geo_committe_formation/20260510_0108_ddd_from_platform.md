# 🧠 PHASE 6 - Remaining Implementation (Pseudo-Algorithmic Instructions)

## Current Status

```yaml
COMPLETED:
  Step 6.1: ✅ CommitteeHierarchyRecord (DTO)
  Step 6.2: ✅ CommitteeTreeNode (Immutable DTO)
  Step 6.3: ✅ CommitteeHierarchyRepository (Read model)
  Step 6.4: ✅ CommitteeHierarchyBuilder (Tree assembly with cycle detection)

REMAINING:
  Step 6.5: CommitteeGovernanceProjector (Event-driven projection)
  Step 6.6: GetCommitteeHierarchy (Use case with caching)
  Step 6.7: Architecture Fitness Tests (7 new tests)
  Step 6.8: API Endpoint + Vue UI (Optional)
```

---

## 🧪 STEP 6.5: CommitteeGovernanceProjector (Event-Driven, Replay-Safe)

### RED Phase - Write Failing Test

```yaml
FILE: tests/Integration/Contexts/Governance/Infrastructure/Projections/CommitteeGovernanceProjectorTest.php

PSEUDO-ALGORITHM:
  1. CREATE test class CommitteeGovernanceProjectorTest extends TestCase
  2. USE RefreshDatabase trait
  
  3. ADD test_rebuilds_projection_for_committee():
      - CREATE committee record in database
      - CREATE AuthorityChain and governance projection
      - CALL $projector->rebuild($committeeId, $now, $eventId, $eventOccurredAt)
      - ASSERT projection table has record with:
          committee_id = $committeeId
          projection_version = 1
          last_event_id = $eventId
          operational_state = 'ACTIVE'
          temporal_state = 'VALID'
          legitimacy = 'LEGITIMATE'
  
  4. ADD test_idempotent_event_processing():
      - PROCESS same event twice
      - ASSERT second call does NOT update projection
      - ASSERT last_event_id unchanged
  
  5. ADD test_replay_from_event_works():
      - DELETE projection record
      - CALL $projector->replayFromEvent($eventId)
      - ASSERT projection rebuilt correctly
  
  6. ADD test_rebuild_all_rebuilds_all_committees:
      - CREATE multiple committees
      - CALL $projector->rebuildAll($now)
      - ASSERT all committees have projections
  
  7. ADD test_projection_updates_on_committee_created_event:
      - DISPATCH CommitteeCreated event
      - CALL $projector->onEvent($event)
      - ASSERT projection created
  
  8. ADD test_projection_updates_on_committee_lifecycle_changed:
      - DISPATCH CommitteeLifecycleChanged with newState = 'SUSPENDED'
      - CALL $projector->onEvent($event)
      - ASSERT operational_state updated to 'SUSPENDED'
  
  9. ADD test_projection_updates_on_authority_delegated:
      - DISPATCH AuthorityDelegated event
      - CALL $projector->onEvent($event)
      - ASSERT legitimacy may change based on new authority
  
  10. ADD test_projection_is_deterministic:
      - PROCESS same event sequence twice
      - ASSERT identical projection results
  
  11. RUN test → EXPECT RED

RATIONALE:
  - Projector must be replay-safe (can rebuild from events)
  - Versioning enables migration and debugging
  - Idempotent processing prevents duplicates
  - Projections are disposable (not source of truth)
```

### GREEN Phase - Implement

```yaml
FILE: app/Contexts/Governance/Infrastructure/Projections/CommitteeGovernanceProjector.php

PSEUDO-ALGORITHM:
  1. CREATE final class CommitteeGovernanceProjector
  
  2. INJECT dependencies:
      - CommitteeGovernanceInterpreter $interpreter
      - CommitteeRepository $committeeRepo
      - AuthorityAssignmentRepository $authorityRepo
      - ProjectionDatabase $db
      - ProcessedEventStore $processedEventStore
  
  3. CREATE public function rebuild(
        CommitteeId $committeeId,
        DateTimeImmutable $now,
        string $eventId,
        DateTimeImmutable $eventOccurredAt
    ): void
      - LOAD CommitteeFacts from repository
      - LOAD AuthorityChain (if exists via AuthorityResolver)
      - $projection = $this->interpreter->interpret($facts, $now, $authorityChain)
      - UPSERT into committee_governance_projections:
          SET committee_id = $committeeId
              projection_version = 1
              last_event_id = $eventId
              last_event_occurred_at = $eventOccurredAt
              operational_state = $projection->operationalState->value
              temporal_state = $projection->temporalState->value
              legitimacy = $projection->legitimacy->value
              updated_at = $now
  
  4. CREATE public function onEvent(DomainEvent $event): void
      - IF $this->processedEventStore->exists($event->getId()):
          RETURN  // Idempotency check
      - $this->rebuild(
          committeeId: $event->getCommitteeId(),
          now: $event->getOccurredAt(),
          eventId: $event->getId(),
          eventOccurredAt: $event->getOccurredAt()
      )
      - $this->processedEventStore->markProcessed($event->getId())
  
  5. CREATE public function replayFromEvent(string $fromEventId): void
      - LOAD all events after $fromEventId from event store
      - FOREACH event: $this->onEvent($event)
  
  6. CREATE public function rebuildAll(DateTimeImmutable $now): void
      - LOAD all committee IDs from repository
      - FOREACH committeeId: $this->rebuild($committeeId, $now)
  
  7. RUN test → EXPECT GREEN

VERIFICATION:
  10 passing tests
```

---

## 🧪 STEP 6.6: GetCommitteeHierarchy Use Case

### RED Phase - Write Failing Test

```yaml
FILE: tests/Unit/Contexts/Governance/Application/UseCases/GetCommitteeHierarchyTest.php

PSEUDO-ALGORITHM:
  1. CREATE test class GetCommitteeHierarchyTest extends TestCase
  
  2. MOCK dependencies:
      - CommitteeHierarchyRepository $repository
      - CommitteeGovernanceInterpreter $interpreter
      - CommitteeHierarchyBuilder $builder
      - CacheInterface $cache
  
  3. ADD test_returns_hierarchy_tree():
      - MOCK repository->getAll() returns CommitteeHierarchyRecord[]
      - MOCK interpreter->interpret() returns CommitteeGovernanceProjection
      - MOCK builder->buildTree() returns CommitteeTreeNode[]
      - CALL $useCase->execute($tenantId, $now)
      - ASSERT returns array<CommitteeTreeNode>
      - ASSERT tree correctly assembled
  
  4. ADD test_caches_records_NOT_trees():
      - CALL execute() twice
      - ASSERT repository called only once (records cached)
      - ASSERT builder called each time (cheap assembly)
      - ASSERT tree nodes NOT cached
  
  5. ADD test_cache_invalidation_on_committee_event:
      - CALL execute() → cache populated
      - DISPATCH CommitteeLifecycleChanged event
      - CALL cache invalidation
      - CALL execute() → repository called again (cache miss)
  
  6. ADD test_respects_tenant_isolation:
      - CALL execute() with tenant A
      - CALL execute() with tenant B
      - ASSERT separate cache keys used
  
  7. ADD test_handles_empty_tenant:
      - MOCK repository->getAll() returns []
      - ASSERT execute() returns []
  
  8. RUN test → EXPECT RED

RATIONALE:
  - Cache projection RECORDS, not assembled trees
  - Tree assembly is cheap (O(n)) and deterministic
  - Prevents cache coherency issues with subtree changes
  - Tenant isolation prevents cross-tenant cache leakage
```

### GREEN Phase - Implement

```yaml
FILE: app/Contexts/Governance/Application/UseCases/GetCommitteeHierarchy.php

PSEUDO-ALGORITHM:
  1. CREATE final class GetCommitteeHierarchy
  
  2. INJECT dependencies:
      - CommitteeHierarchyRepository $repository
      - CommitteeGovernanceInterpreter $interpreter
      - CommitteeHierarchyBuilder $builder
      - CacheInterface $cache (optional, with TTL 300 seconds)
  
  3. CREATE public function execute(TenantId $tenantId, DateTimeImmutable $now): array
      - $cacheKey = "hierarchy_records_{$tenantId->value()}"
      
      // Cache records, NOT trees
      - $records = $this->cache->get($cacheKey)
      - IF $records === null:
          $records = $this->repository->getAll($tenantId)
          $this->cache->set($cacheKey, $records, 300)
      
      - $governanceMap = []
      - FOREACH $records as $record:
          // Convert record to CommitteeFacts for interpreter
          $facts = $this->toCommitteeFacts($record)
          $authorityChain = $this->resolveAuthorityChain($record->id, $now)
          $projection = $this->interpreter->interpret($facts, $now, $authorityChain)
          $governanceMap[$record->id->value()] = $projection
      
      - $roots = $this->builder->buildTree($records, $governanceMap)
      
      - RETURN $roots
  
  4. CREATE private function toCommitteeFacts(CommitteeHierarchyRecord $record): CommitteeFacts
      - MAP record to CommitteeFacts (domain translation)
      - HANDLE required fields (id, operationalState, term, parentId)
  
  5. CREATE private function resolveAuthorityChain(CommitteeId $id, DateTimeImmutable $now): ?AuthorityChain
      - USE AuthorityResolver::resolveActiveAt($id, $now, $authorityRepo)
      - RETURN AuthorityChain if found, null otherwise
  
  6. CREATE public function invalidateCache(TenantId $tenantId): void
      - $this->cache->delete("hierarchy_records_{$tenantId->value()}")
  
  7. RUN test → EXPECT GREEN

VERIFICATION:
  7 passing tests
```

---

## 🧪 STEP 6.7: Architecture Fitness Tests (Hardened)

### Append to Existing Fitness Test

```yaml
FILE: tests/Architecture/GovernanceDomainPurityTest.php

PSEUDO-ALGORITHM:
  ADD test_projection_read_model_does_not_import_domain_aggregates():
      - SCAN CommitteeHierarchyRecord class
      - ASSERT NO import of CommitteeAggregate, CommitteeFacts (domain)
      - ASSERT ONLY value objects allowed
  
  ADD test_tree_node_has_no_setters():
      - REFLECT CommitteeTreeNode
      - ASSERT NO methods named set*, addChild, removeChild, update*, change*
      - ASSERT all properties readonly
      - ASSERT hasChildren() is the only method besides constructor/getters
  
  ADD test_hierarchy_repository_returns_read_model_not_domain():
      - REFLECT CommitteeHierarchyRepository::getAll()
      - ASSERT return type is array<CommitteeHierarchyRecord>
      - ASSERT NOT CommitteeFacts, NOT CommitteeAggregate
  
  ADD test_builder_has_cycle_detection():
      - VERIFY detectCycle() private method exists
      - VERIFY throws DomainException on cycle
      - VERIFY orphan quarantine logging
  
  ADD test_projector_is_replay_safe():
      - VERIFY replayFromEvent() method exists
      - VERIFY rebuildAll() method exists
      - VERIFY idempotent event processing (processed_events table check)
  
  ADD test_projection_schema_has_version_fields():
      - VERIFY migration includes: projection_version, last_event_id, last_event_occurred_at
  
  ADD test_no_eloquent_models_outside_infrastructure():
      - SCAN app/Contexts/Governance/Application
      - SCAN app/Contexts/Governance/Domain
      - ASSERT NO extends Model, NO use Illuminate\Database
  
  ADD test_use_case_caches_records_not_trees():
      - REFLECT GetCommitteeHierarchy
      - VERIFY cache key uses 'hierarchy_records_' prefix
      - VERIFY builder called after cache retrieval
  
  ADD test_builder_uses_iterative_not_recursive():
      - REFLECT CommitteeHierarchyBuilder
      - VERIFY no recursive method calls (detectCycle is recursive but depth-limited)
      - VERIFY assembly uses loops, not recursion

EXPECT: All 9 new fitness tests pass
```

---

## 🧪 STEP 6.8: API Endpoint (Optional)

### Create Controller

```yaml
FILE: app/Http/Controllers/Committee/CommitteeHierarchyController.php

PSEUDO-ALGORITHM:
  1. CREATE final class CommitteeHierarchyController extends Controller
  
  2. INJECT GetCommitteeHierarchy $useCase
  
  3. CREATE public function index(Request $request): JsonResponse
      - $tenantId = TenantId::fromString($request->user()->organisation_id)
      - $now = new DateTimeImmutable()
      - $roots = $this->useCase->execute($tenantId, $now)
      - RETURN response()->json($this->serializeTree($roots))
  
  4. CREATE private function serializeTree(array $nodes): array
      - RETURN array_map(fn($node) => [
          'id' => $node->id->value(),
          'name' => $node->name,
          'level' => $node->level,
          'governance' => [
              'operational_state' => $node->governance->operationalState->value,
              'temporal_state' => $node->governance->temporalState->value,
              'legitimacy' => $node->governance->legitimacy->value,
              'can_act' => $node->governance->canAct(),
          ],
          'children' => $this->serializeTree($node->children),
      ], $nodes)
```

### Create Route

```yaml
FILE: routes/api.php

PSEUDO-ALGORITHM:
  1. ADD Route::get('/nrna/committee/hierarchy', [CommitteeHierarchyController::class, 'index'])
      ->middleware(['auth:sanctum', 'tenant'])
      ->name('committee.hierarchy')
```

---

## 📊 Phase 6 Completion Criteria

```yaml
MUST HAVE:
  Infrastructure:
    ✅ CommitteeHierarchyRepository (returns read model)
    ✅ CommitteeGovernanceProjector (replay-safe, versioned, idempotent)
  
  Application:
    ✅ CommitteeHierarchyRecord (read model DTO)
    ✅ CommitteeTreeNode (immutable, no addChild)
    ✅ CommitteeHierarchyBuilder (iterative, cycle detection, orphan quarantine)
    ✅ GetCommitteeHierarchy (caches records, not trees)
  
  Architecture Fitness:
    ✅ 9 new tests (total 19+ fitness tests)
  
  API (Optional):
    ⏳ CommitteeHierarchyController
    ⏳ API route

TOTAL TESTS: ~30 new tests (Step 6.5-6.7)
GRAND TOTAL: ~265 tests

QUALITY GATES:
  ✅ All tests GREEN
  ✅ No domain structures leaked to read side
  ✅ Tree DTOs fully immutable
  ✅ Cycle detection prevents infinite traversal
  ✅ Orphan quarantine (no silent root attachment)
  ✅ Projector replay-safe, versioned, idempotent
  ✅ Cache invalidation works
  ✅ Caches records, not trees
```

---

## 🚀 Execution Command Sequence

```bash
# Step 6.5: Projector (Integration test)
php artisan test tests/Integration/Contexts/Governance/Infrastructure/Projections/CommitteeGovernanceProjectorTest.php --no-coverage

# Step 6.6: Use Case
php artisan test tests/Unit/Contexts/Governance/Application/UseCases/GetCommitteeHierarchyTest.php --no-coverage

# Step 6.7: Architecture Fitness
php artisan test tests/Architecture/GovernanceDomainPurityTest.php --no-coverage

# Step 6.8: Full regression
php artisan test tests/Unit/Contexts/Governance/ tests/Integration/ tests/Architecture/ --no-coverage

EXPECTED: ~265 tests passing
```

---

## 🎯 Success Message

```yaml
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
              PHASE 6 COMPLETE - HIERARCHY PROJECTION
                    (PRODUCTION-GRADE) ✅
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

Infrastructure:
  ✅ CommitteeHierarchyRepository (returns read model, NOT domain)
  ✅ CommitteeGovernanceProjector (replay-safe, versioned, idempotent)

Application:
  ✅ CommitteeHierarchyRecord (read model decoupled from domain)
  ✅ CommitteeTreeNode (fully immutable, NO addChild)
  ✅ CommitteeHierarchyBuilder (O(n), cycle detection, orphan quarantine)
  ✅ GetCommitteeHierarchy (caches records, NOT trees)

Architecture:
  ✅ 9 new fitness tests
  ✅ No domain leakage to read side
  ✅ Projection versioning enforced
  ✅ Consistency contract documented

Operational Resilience:
  ✅ Replay-safe projector
  ✅ Idempotent event processing
  ✅ Cycle detection prevents infinite loops
  ✅ Orphan quarantine with logging

Final System Status:
  ✅ Phases 1-6 COMPLETE
  ✅ ~265 tests passing
  ✅ Production-ready constitutional governance platform
  ✅ Event-driven, replay-safe, scalable
```

---

**Start with STEP 6.5 - Create CommitteeGovernanceProjectorTest.php** 🚀