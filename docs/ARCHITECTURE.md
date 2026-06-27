# Governance Architecture — Projection Consistency Model

**Last Updated:** 2026-05-10  
**Phase:** 6 — Hierarchy Projection  
**Status:** Final

---

## Table of Contents

1. [Projection Lifecycle](#projection-lifecycle)
2. [RULE-H6-02: Eventual Consistency](#rule-h6-02-eventual-consistency)
3. [RULE-H6-03: Projections are Rebuildable](#rule-h6-03-projections-are-rebuildable)
4. [RULE-H6-04: Query Path Purity](#rule-h6-04-query-path-purity)
5. [RULE-H6-05/H6-07: Rebuild Locking](#rule-h6-05h6-07-rebuild-locking)
6. [RULE-H6-06: Deterministic Rebuild](#rule-h6-06-deterministic-rebuild)
7. [RULE-H6-08: Failure Isolation](#rule-h6-08-failure-isolation)
8. [RULE-H6-09: Generation Monotonicity](#rule-h6-09-generation-monotonicity)
9. [RULE-H6-10: Rebuild Consistency Visibility](#rule-h6-10-rebuild-consistency-visibility)
10. [Corruption Recovery](#corruption-recovery)
11. [Projection Metrics (Future)](#projection-metrics-future)
12. [Scheduler Readiness (Future)](#scheduler-readiness-future)

---

## Projection Lifecycle

```
Domain Event
  │
  ▼
Projector.onEvent()
  │
  ├─ alreadyProcessed()? ──yes──▶ (skip, idempotent)
  │
  ▼ no
Interpreter.interpret(facts, now)
  │
  ▼
upsertProjection()
  │
  ├─ committee_governance_projections table
  │     ├─ operational_state    (pre-computed)
  │     ├─ temporal_state       (pre-computed)
  │     ├─ legitimacy           (pre-computed)
  │     ├─ can_act              (pre-computed)
  │     ├─ is_fully_operational (pre-computed)
  │     ├─ projection_generation
  │     └─ projection_schema_version
  │
  ▼
Cache Generation Bump
  │
  ▼
Query → LEFT JOIN on projections table
  │
  ├─ CommitteeHierarchyRecord (read model, scalar fields)
  │
  ▼
CommitteeHierarchyBuilder.buildTree()
  │
  ▼
CommitteeTreeNode[] (immutable response)
```

### Lifecycle Stages

| Stage | Trigger | Description |
|-------|---------|-------------|
| **Creation** | Domain event | `onEvent()` processes single event → interprets → upserts projection |
| **Incremental update** | Subsequent events | Same path, idempotency check prevents double-processing |
| **Full rebuild** | `rebuildAll()` or CLI | Iterates all committees, re-interprets from current facts |
| **Rebuild per tenant** | CLI `--tenant=` | Scoped rebuild for a single tenant |
| **Cache invalidation** | Generation change | Cache key includes `projection_generation`, so rebuilds invalidate cache |
| **Rollback** | Corruption detected | `rollbackToGeneration()` deletes current projections, triggers rebuild |

---

## RULE-H6-02: Eventual Consistency

> All governance projections are **eventually consistent**.
> Queries **must tolerate** temporarily stale projection data.

### What This Means

- After a domain event is processed, the projection table is updated **within the same transaction** for event-driven updates
- However, during full rebuilds (via CLI or `rebuildAll`), projections may be in a mixed state
- The UI and API consumers **must not assume** that projection state reflects the current domain state
- There is **no SLA** on projection freshness — the system guarantees convergence, not recency

### Staleness Scenarios

| Scenario | Expected Behavior |
|----------|-------------------|
| Normal event processing | Projection updated within milliseconds |
| CLI rebuild (all tenants) | Mixed state during rebuild — each committee updated sequentially |
| CLI rebuild (single tenant) | Tenant isolated — other tenants unaffected |
| Projector idle | Projections reflect last processed event — may lag behind domain |

### Code Enforcement

Every read-boundary entry point carries the annotation:

```
// Projection fields are eventually consistent — NOT authoritative domain truth. See docs/ARCHITECTURE.md
```

This comment appears at:
- Repository `getAll()` and `getProjectionGeneration()` methods
- Use case `execute()` method and `cacheKey()` computation
- Projector `upsertProjection()` persistence logic
- `CommitteeHierarchyRecord` DTO
- `CommitteeGovernanceProjection` ViewModel
- `CommitteeGovernanceProjectionModel` Eloquent model

---

## RULE-H6-03: Projections are Rebuildable

> Projection tables are **not sources of truth**.
> They are **disposable**, **rebuildable**, and **optimized for query performance only**.

### Properties

```yaml
Projection tables are:
  - non-authoritative:     domain state is the source of truth
  - disposable:            can be dropped and rebuilt at any time
  - rebuildable:           from domain events or current committee facts
  - eventually consistent: may lag behind the write model
  - query-optimized:       structured for read model performance
```

### Rebuild Command

```bash
# Full rebuild (all tenants)
php artisan governance:rebuild-projections

# Tenant-scoped rebuild
php artisan governance:rebuild-projections --tenant=org-123

# Preview only
php artisan governance:rebuild-projections --dry-run

# Force rebuild (skip lock)
php artisan governance:rebuild-projections --force
```

---

## RULE-H6-04: Query Path Purity

> Query handlers must **consume projections only**.
> NO governance interpretation, policy evaluation, aggregate reconstruction,
> or domain event replay may execute at request time.

### Enforced Operations

```yaml
Query path may:
  ✅ Read from projection tables (via LEFT JOIN)
  ✅ Hydrate enums from stored string values (data mapping, not interpretation)
  ✅ Cache read model records
  ✅ Assemble tree structures from flat records

Query path must NOT:
  ❌ Execute OperationalStatePolicy.evaluate()
  ❌ Execute TemporalGovernancePolicy.evaluate()
  ❌ Execute ConstitutionalLegitimacyPolicy.evaluate()
  ❌ Call CommitteeGovernanceInterpreter.interpret()
  ❌ Reconstruct domain aggregates
  ❌ Replay domain events
```

### Enforcement

Architecture fitness test `test_query_path_does_not_import_domain_policies()` uses **reflection** on constructors to verify that query-path classes (`GetCommitteeHierarchy`, `CommitteeHierarchyBuilder`, `CommitteeHierarchyRepository`, `CommitteeHierarchyRecord`) do not depend on policy or interpreter classes.

---

## RULE-H6-05/H6-07: Rebuild Locking

> Only one projection rebuild per tenant may execute concurrently.
> Locks must be **tenant-scoped** and **time-bounded**.

### Implementation

```php
Lock key format:  projection_rebuild_lock:{tenantId}
                 projection_rebuild_lock:global  (full rebuild)

Lock mechanism:  Cache::lock($key, $ttl)
TTL:             600 seconds (auto-released on crash)
Release:         finally { $this->lock->release($key); }
```

### Lock Behavior

| Lock State | Action |
|------------|--------|
| Lock acquired | Rebuild executes |
| Lock held by another process | Rebuild skipped with status "skipped: lock held" |
| Lock expired (TTL) | New rebuild can acquire lock |
| Process crash | Lock auto-releases after TTL |

---

## RULE-H6-06: Deterministic Rebuild

> Projection rebuilds must be **deterministic**.
> Same event stream + same timestamp context must produce the same projection state.

### Deterministic Inputs

```yaml
Deterministic Inputs:
  - ordered event stream           (same sequence → same result)
  - fixed evaluation timestamp     (via GovernanceClock, injected)
  - stable timezone                (UTC everywhere)
  - deterministic event ordering   (consistent sort order)
  - pure interpreter logic         (no side effects)
```

### Prohibited Inside Projection Evaluation

```yaml
No usage of:
  - time(), now(), microtime()     (use injected GovernanceClock)
  - random_bytes(), Str::uuid()    (use injected generation)
  - non-deterministic DB ordering  (explicit ORDER BY required)
```

### Enforcement

Integration test `test_same_event_stream_produces_deterministic_projection()` verifies that replaying the same events produces identical projection state, including `operational_state`, `temporal_state`, `legitimacy`, `can_act`, and `is_fully_operational`.

---

## RULE-H6-08: Failure Isolation

> Projection rebuild failures must **isolate per aggregate**.
> A single committee rebuild failure **must not abort** the entire rebuild.

### Implementation

```php
foreach ($committees as $committee) {
    try {
        $this->projector->rebuild(...);
        $rebuilt++;
    } catch (\Throwable $e) {
        $failed++;
        // Continue to next committee — one failure does not abort
    }
}
```

### Rebuild Summary

After completion, the CLI reports:
```
Rebuilt: 523
Failed: 3
Skipped: 0
```

Exit code: `0` if all succeeded, `1` if any failures.

---

## RULE-H6-09: Generation Monotonicity

> Projection generations must be **monotonic** and **rebuild-scoped**.

### Generation Strategy

- **Format:** UUIDv7 (timestamp-prefixed, 32 hex chars)
- **Scope:** One generation per rebuild session
- **Storage:** Written to every upserted projection row
- **Uniqueness:** Each `rebuildAll()` call generates a unique generation
- **Tracking:** `projection_rebuild_runs` table records every rebuild session

### `projection_rebuild_runs` Table

| Column | Description |
|--------|-------------|
| `generation` | UUIDv7, primary key |
| `tenant_id` | Tenant scope (nullable = global rebuild) |
| `started_at` | When rebuild started |
| `completed_at` | When rebuild completed |
| `status` | running, completed, failed, rolled_back |
| `total_committees` | Count at start |
| `rebuilt_count` | Successful rebuilds |
| `failed_count` | Failed rebuilds |
| `error_log` | Failure details |

### Cache Key Integration

```
hierarchy_records_{tenantId}_{projectionGeneration}
```

When a rebuild completes, the `projection_generation` value changes, causing all cache keys to miss — effectively invalidating the cache without explicit invalidation.

---

## RULE-H6-10: Rebuild Consistency Visibility

> Reads during rebuild may observe **mixed generations**.
> OR rebuilds must use **atomic generation switching**.

### Current Approach

The current implementation does **not** use shadow tables or atomic generation switching. Reads during rebuild observe a mix of old and new projection rows as each committee is updated sequentially.

### Impact

| Read Timing | Observation |
|-------------|-------------|
| Before rebuild | All projections from previous generation |
| During rebuild (not yet visited) | Old projection |
| During rebuild (just visited) | New projection |
| After rebuild | All projections from current generation |

### Future Enhancement

For zero-downtime rebuilds, implement shadow generation switching:

```yaml
1. Build into NEW generation (with generation_id = new UUIDv7)
2. Mark generation complete
3. Atomically switch active generation pointer
4. Old generation expires naturally
```

This is deferred — not yet implemented.

---

## Corruption Recovery

### Orphan Quarantine

The `CommitteeHierarchyBuilder` detects nodes whose `parentId` references a non-existent committee. These orphans are **quarantined** (not attached to any parent, tracked in `orphanLog`).

```yaml
Detection:   parentId does not match any committee record
Action:      Quarantine — node exists but has no parent
Visibility:  Not included in tree output; logged for investigation
Recovery:    Rebuild after parent committee is created
```

### Cycle Detection

The builder runs a DFS-based cycle detector before tree assembly:

```yaml
Detection:   DFS with recursion stack — child references parent transitively
Response:    DomainException with "Cycle detected in committee hierarchy"
Protection:  Prevents infinite recursion during tree traversal
```

### Rollback

If corruption is detected in production projections:

```bash
# The GovernanceProjectionRebuilder supports rollback:
php artisan governance:rebuild-projections --tenant=org-123
```

For more severe corruption, rollback to a known-good generation is available programmatically via `GovernanceProjectionRebuilder::rollbackToGeneration()`.

### Recovery Strategy

| Scenario | Recovery |
|----------|----------|
| Stale projection | Full rebuild via CLI |
| Single committee corruption | Full rebuild overwrites with `updateOrCreate` |
| Orphan quarantine | Fix parent references, then full rebuild |
| Cycle detected | Fix parent references, then full rebuild |
| Generation mismatch | Cache automatically invalidated by generation change |

---

## Projection Metrics (Future)

These metrics are documented as extension points for future monitoring:

```yaml
rebuild_duration:         Time to complete full rebuild
rebuild_failures:         Count of failed committee rebuilds
stale_projection_count:   Projections not updated within threshold
generation_mismatch_count: Projections with different generations
lock_contention_count:    Times rebuild was skipped due to held lock
```

---

## Scheduler Readiness (Future)

The `GovernanceProjectionRebuilder` service is designed for scheduler integration:

```yaml
Future CLI commands:
  governance:rebuild-projections --stale-only   (incremental rebuild)
  governance:rebuild-projections --schedule      (cron mode)

Scheduler (Laravel):
  $schedule->command('governance:rebuild-projections --stale-only')
           ->hourly()
           ->withoutOverlapping();
```

No scheduler integration is implemented yet — the service is ready for it.
