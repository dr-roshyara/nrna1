# Phase 3.2: Graduated Strict Activation — Active Constraint System for Dependency Revelation
**Type:** Progressive enforcement escalation with dependency discovery under controlled degradation
**Scope:** Transition from Level 0 (warning) → Level 4 (full strict) via constraint-driven coupling revelation
**Date:** 2026-05-20 (activation start)
**Objective:** Discover and reveal hidden dependencies by progressively constraining execution paths, generating Phase 4 extraction blueprint automatically from real violations

---

## Phase 3.2 Mission Statement

> **Phase 3.2 is not passive observation — it is active constraint discovery under controlled enforcement pressure.**

Unlike traditional "sensor calibration," this system **shapes system behavior to reveal hidden coupling**. We escalate enforcement pressure in four graduated stages. Each stage:
1. Introduces new constraints at one enforcement layer
2. **Actively degrades permitted execution paths** to force dependency visibility
3. Records all violations via ConstitutionalMetrics with dependency context
4. **Automatically generates Phase 4 extraction blueprint** from real, not theoretical, coupling patterns

This transforms Phase 4 from "design an event-driven architecture" into "implement extraction paths discovered and validated under enforcement pressure — as if chaos engineering revealed the system's true dependencies."

**Key Insight:** Each level answers a different question about the system:
- **Level 1:** What is used? (frequency analysis)
- **Level 2:** What is queried? (query patterns)
- **Level 3:** What is directly coupled? (access patterns)
- **Level 4:** What survives full constraint? (irreducible dependencies)

---

## Four Enforcement Layers (STRICT_LEVEL 0-4)

| Level | Name | Active Enforcement | Metrics Behavior | Phase Context |
|-------|------|-------------------|------------------|---------------|
| **0** | Warning Mode | None | Observe only | Current (stabilized) |
| **1** | Metrics Strict | ConstitutionalMetrics records all violations | Records without blocking | 12-24h observation |
| **2** | Query Guard Strict | QueryPolicyGuard throws on deprecated fields | Records + blocks queries | 12-24h observation |
| **3** | Lifecycle Strict | DeprecationAccessGuard throws on field access | Records + blocks access | 12-24h observation |
| **4** | Full Strict | All guards throw; zero tolerance | Records + blocks all | Long-term stable state |

---

## Activation Schedule

### Level 0 → Level 1: Metrics Strict (24h observation window)
**When:** 2026-05-20 09:00 UTC  
**Duration:** 24 hours (until 2026-05-21 09:00 UTC)  
**Action:** Change `STRICT_LEVEL = 0` to `STRICT_LEVEL = 1` in DeprecationPolicy

**What happens:**
- ConstitutionalMetrics records all violations in canonical format
- No queries are blocked, no field access throws exceptions
- Metrics layer becomes the primary observability signal
- Violations accumulate in 24h window for analysis

**Success Criteria:**
- `php artisan election:constitution:health` shows recorded violations
- No test failures (level 1 only records, does not enforce)
- Metrics data reveals actual usage patterns

**Analysis During Window:**
- Which deprecated fields are accessed most frequently?
- What is the call pattern (query vs field access vs lifecycle)?
- Are violations correlated or scattered?
- Any patterns in organisation context?

**Transition Decision:**
- If violation patterns clear: proceed to Level 2 at 24h mark
- If unexpected violations: hold Level 1 longer, analyze root causes, adjust DeprecationPolicy

---

### Level 1 → Level 2: Query Guard Strict (24-48h window)
**When:** 2026-05-21 09:00 UTC  
**Duration:** 24 hours (until 2026-05-22 09:00 UTC)  
**Action:** Change `STRICT_LEVEL = 1` to `STRICT_LEVEL = 2` in DeprecationPolicy

**What happens:**
- QueryPolicyGuard enforcement activates
- Queries using deprecated fields now throw DeprecatedQueryException
- Metrics still record all violations
- Application-layer field access still permitted (no Level 3 yet)

**What breaks:**
- Any code directly querying deprecated fields via `assertQueryAllowed()`
- ElectionRepository or other read models using deprecated field criteria
- Expected: 0 breakages if Phase 3.1 migration complete

**Success Criteria:**
- Zero test failures (queries protected, field access still allowed)
- Metrics show query violations now blocked
- No runtime exceptions in production loads

**Analysis During Window:**
- Are queries that were warned about actually being called?
- Which repositories/query services trigger violations?
- Can we identify extraction points from query patterns?

**Transition Decision:**
- If no violations: proceed to Level 3 at 24h mark
- If violations appear: either fix code or adjust timing of enforcement

---

### Level 2 → Level 3: Lifecycle Strict (24-72h window)
**When:** 2026-05-22 09:00 UTC  
**Duration:** 24-48 hours (until 2026-05-23 09:00 UTC)  
**Action:** Change `STRICT_LEVEL = 2` to `STRICT_LEVEL = 3` in DeprecationPolicy

**What happens:**
- DeprecationAccessGuard enforcement activates
- Direct field access (e.g., `$election->status`) now throws DeprecatedFieldException
- Queries blocked (Level 2 still active)
- Metrics record all access attempts

**What breaks:**
- Any code accessing `$election->status` or `$election->is_active` directly
- Expected: 0 breakages if ElectionLifecycle facade fully adopted
- Possible breakages: direct Eloquent field access in controllers, caches, serializers

**Success Criteria:**
- All application and controller layer code uses ElectionLifecycle facade
- Metrics show no field access violations (or only from tests/debug code)
- All property access flows through SSOT

**Analysis During Window:**
- Which code sites attempt direct field access?
- Are there hidden coupling points we missed in Phase 3.1?
- What is the distribution of access types (controller vs service vs model)?

**Transition Decision:**
- If violations show code sites: fix them before Level 4
- If violations only in tests: update tests to use facade
- If clean: proceed to Level 4

---

### Level 3 → Level 4: Full Strict (Permanent)
**When:** 2026-05-23 09:00 UTC  
**Duration:** Permanent  
**Action:** Change `STRICT_LEVEL = 3` to `STRICT_LEVEL = 4` in DeprecationPolicy

**What happens:**
- All enforcement layers active simultaneously
- Queries throw on deprecated fields
- Field access throws on deprecated properties
- Zero tolerance for SSOT violations

**What breaks:**
- Any remaining deprecated field accesses
- Expected: Nothing (all issues should be resolved by Level 3)

**Success Criteria:**
- System passes all Phase 3.1 test suite (192 → 0 failures)
- System passes all Phase 3.2 escalation steps without new failures
- `php artisan election:constitution:health` shows READY FOR PHASE 4

**Transition to Phase 4:**
- Metrics history from Levels 1-4 becomes Phase 4 extraction blueprint
- Real violations under enforcement pressure drive event-driven design
- Implementation plan for dependency extraction is driven by actual coupling patterns, not theoretical architecture

---

## Observation Protocol

### Each 24h Window Includes:

**Hour 0-1 (Activation):**
- Activate next enforcement level
- Run full test suite to confirm no immediate failures
- Record baseline metrics

**Hour 1-12 (Steady State):**
- Monitor metrics dashboards for violations
- Collect violation patterns by type, source, organisation
- Watch for cascading failures

**Hour 12-24 (Analysis):**
- Aggregate violations by severity and category
- Identify code sites responsible for top violations
- Assess whether to proceed or hold level

**Hour 24 (Transition):**
- Review health report: `php artisan election:constitution:health`
- Decision: proceed to next level or hold/revise

### Metrics to Track at Each Level:

1. **Violation Count** (total per severity)
2. **Violation Sources** (which code paths trigger)
3. **Organisations Affected** (demo vs live, scoping issues)
4. **Time Distribution** (when violations occur in cycle)
5. **Successful Transitions** (features working under new constraints)

---

## Dependency Capture Schema (Phase 3.2 → Phase 4 Bridge)

To transform Phase 3.2 metrics into Phase 4 extraction blueprint automatically, violations must capture structural context, not just counts.

### Extended Metrics Structure

Each violation recorded to ConstitutionalMetrics must include:

```php
[
    'violation_id' => 'uuid',
    'level_recorded' => 2,
    'timestamp' => 'ISO8601',
    
    // DEPENDENCY CONTEXT (NEW)
    'source_system' => 'controller' | 'service' | 'repository' | 'model' | 'test',
    'source_file' => 'app/Http/Controllers/Election/ElectionManagementController.php',
    'source_line' => 67,
    'source_method' => 'listElections',
    
    'dependency_type' => 'field_access' | 'query_filter' | 'lifecycle_check',
    'field_accessed' => 'status',
    'replacement_api' => 'ElectionLifecycle::of($e)->state()->value',
    
    // CALL CONTEXT (NEW)
    'call_chain' => [
        'ElectionManagementController::listElections',
        'ElectionRepository::findActiveByOrganisation',
        'Query::where("status", "active")'
    ],
    
    // SCOPE CONTEXT (NEW)
    'organisation_id' => 'uuid|null',  // null = demo scope
    'election_context' => 'uuid|null',
    
    // EXECUTION CONTEXT (NEW)
    'request_type' => 'GET|POST|PUT|DELETE',
    'request_path' => '/api/elections',
    'frequency_in_window' => 47,  // How many times in 24h
]
```

### Phase 4 Blueprint Extraction Algorithm

From Phase 3.2 violations, extract:

```
1. FREQUENCY ANALYSIS (Level 1 → Level 4)
   - Which code sites are most coupled?
   - Which execution paths are heaviest?
   - Which are one-off vs structural?
   
2. EXTRACTION POINT IDENTIFICATION (Level 2 → Level 4)
   - Which queries can be decomposed?
   - Which read models need splitting?
   - Which aggregate boundaries are wrong?
   
3. EVENT EMISSION POINTS (Level 3 → Level 4)
   - Where should lifecycle events be emitted?
   - What event names from call chains?
   - What handlers needed for extraction?
   
4. MIGRATION SLICE ORDERING (Level 4)
   - Which extractions are safe first?
   - Which have minimum transitive dependency?
   - Which unblock other extractions?
```

### Using Metrics for Extraction Blueprint

**Example: Status Field Violations**

If Level 2 discovers:
```
field_accessed: 'status'
source_system: 'controller'
frequency: 47 in 24h
call_chain: [
    ElectionManagementController::listElections,
    ElectionRepository::findActiveByOrganisation,
    Query::where("status", "active")
]
```

Phase 4 immediately knows:
1. Extract query: `ElectionRepository::findActiveByOrganisation` → `findByLifecycleState(ACTIVE_VOTING)`
2. Emit event: `ElectionStateChanged` with payload `{election_id, state: ACTIVE_VOTING}`
3. Create read model: `ElectionStateReadModel` for queries
4. Update handler: `ListElectionsHandler` to use event stream instead of status field

**This is what "automatic" extraction blueprint means** — violations directly translate to:
- Code sites to refactor
- Events to emit
- Read models to create
- Migration order

---

## Commands for Observability

```bash
# Check current system health (run at each level boundary)
php artisan election:constitution:health

# View constitutional metrics in detail (if dashboard built in Phase 4)
php artisan election:metrics:report --level=2  # Show violations at Level 2

# Run tests under current enforcement level
php artisan test tests/Feature/Election/ --no-coverage

# Manually trigger a specific enforcement level for testing
# (update DeprecationPolicy::STRICT_LEVEL and run tests)
```

---

## Failure Scenarios and Fallback Strategy

### Scenario 1: Unexpected Violations at Level 1
**Symptom:** ConstitutionalMetrics shows violations from code we thought was clean  
**Diagnosis:**
1. Which field? (status, is_active, other)
2. Which code path? (controller, repository, model)
3. Can it be fixed or must DeprecationPolicy be revised?

**Action:**
- If fixable: fix code, re-test, proceed to Level 2
- If pervasive: extend Level 1 window by 24h, collect more data
- If architectural mismatch: revert Level 1, revise Phase 3.1 scope

### Scenario 2: Test Failures at Level 2 (QueryPolicyGuard)
**Symptom:** Tests fail with DeprecatedQueryException  
**Diagnosis:**
1. Which test file?
2. Which query (which field)?
3. Is there a legitimate reason for the query or was it missed in Phase 3.1?

**Action:**
- If missed in migration: fix query to use SSOT path
- If legitimate edge case: update DeprecationPolicy to warn instead of strict for that field
- If test artifact: update test to avoid deprecated field in WHERE clause

### Scenario 3: Production Errors at Level 3
**Symptom:** Live elections report DeprecatedFieldException  
**Diagnosis:**
1. Which customer organisation?
2. Which field access?
3. Did we miss a code path in Phase 3.1 review?

**Action:**
- Immediately revert to Level 2 (roll back STRICT_LEVEL)
- Identify and fix code site
- Resume at Level 3 once fixed

### Global Fallback: Revert to Level 0
**If escalation becomes blocked for >48h at any level:**
- Revert STRICT_LEVEL to 0 (warning mode)
- Conduct deeper analysis of why enforcement can't advance
- Consider if Phase 3.1 migration was incomplete
- Schedule extended debugging before resuming Level 1

---

## Phase 4 Blueprint Generation

As we progress through each level, violations feed directly into **Phase 4: Event-Driven Extraction Design.**

**Level 1 Violations** → Frequency analysis: which dependencies are most exercised?  
**Level 2 Violations** → Query patterns: what extraction paths are needed for query services?  
**Level 3 Violations** → Access patterns: what lifecycle events should we emit to decouple direct access?  
**Level 4 Success** → Enforcement proves all paths protected; Phase 4 can design events with confidence in the old paths being constrained.

---

## Success Definition: Phase 3.2 Complete

✅ **Level 0 → Level 1:** Metrics recorded, no test failures  
✅ **Level 1 → Level 2:** Queries protected, no query exceptions  
✅ **Level 2 → Level 3:** Field access protected, no access exceptions  
✅ **Level 3 → Level 4:** Full enforcement stable, all tests green  
✅ **Health Report:** `php artisan election:constitution:health` shows READY FOR PHASE 4  
✅ **Phase 4 Input:** Metrics history and violation patterns drive extraction blueprint  

---

## Timeline Summary

| Event | Date/Time | Action | Duration |
|-------|-----------|--------|----------|
| **L0 → L1** | 2026-05-20 09:00 | Activate metrics strict | 24h |
| **L1 → L2** | 2026-05-21 09:00 | Activate query guard strict | 24h |
| **L2 → L3** | 2026-05-22 09:00 | Activate lifecycle strict | 24-48h |
| **L3 → L4** | 2026-05-23 09:00 | Activate full strict (permanent) | Permanent |
| **Phase 4 Start** | 2026-05-24 (est.) | Begin extraction blueprint from metrics | — |

**Total Phase 3.2 Duration:** 72-96 hours (3-4 days) for full activation  
**Phase 3.1 to Phase 3.2:** Immediate (tests green, health ready)  
**Phase 3.2 to Phase 4:** Conditioned on Level 4 stability (48h baseline)

---

## Notes for Implementers

- **DeprecationPolicy::STRICT_LEVEL** is the single control point for all escalation
- **ConstitutionalMetrics** must persist violations across deployments for analysis
- **ElectionConstitutionHealth** command shows readiness at each level
- **No manual code changes needed** between levels (only STRICT_LEVEL constant)
- **Guards check enforcement levels atomically** — no race conditions or timing issues
- **Metrics always record** — enforcement level only controls whether exceptions are thrown

---

## Post-Phase 4: Permanent Architecture

Once Phase 4 event-driven extraction is built and deployed:
- `STRICT_LEVEL` remains at 4 (full strict)
- Deprecated fields become legacy code paths (warnings only)
- New code uses event-driven lifecycle API exclusively
- Old paths gradually fade as events replace direct dependencies
- Metrics track adoption curve of new patterns

This creates a **controlled migration path** rather than a binary big-bang refactor.
