# Plan: Phase 3 Consolidation — Semantic Sovereignty & Constitutional Vocabulary Design

**Plan ID:** snappy-foraging-codd  
**Branch:** postgressql  
**Scope:** Transitional semantic bridge, persistence boundary enforcement, quarantine API vocabulary  
**Status:** Ready for implementation (TDD-first, --env=testing only)

---

## Context

Runtime sovereignty is complete (Phase 2). Elections own their `voter_source_strategy` snapshot. Exception enforcement is active. One active sovereignty violation exists.

This phase is **constitutional language stabilization through semantic sovereignty**, not mechanical cleanup. Vocabulary itself becomes governance infrastructure here. Poor semantic boundaries now will propagate into APIs, federation, and constitutional-runtime evolution for years.

**Strategic position:** This plan uses **transitional semantic bridge vocabulary** (`ImportedVoterRegistry` / `MembershipRegistry`). These are deliberate improvements over `ElectionOnly` / `FullMembership`, but they describe **operational registry mechanisms**, NOT final constitutional authorities. The distinction matters: these are not merely "better names" — they represent a shift in governance conceptual model. Phase 4 performs rigorous governance-language work, modeling delegation, federation, hybrid sovereignty, and constitutional treaty participation, to determine final authority vocabulary (which may differ substantially from these transitional registry concepts).

---

## 1. Vocabulary Classification: Three Tiers

### Tier 1: Legacy Infrastructure Vocabulary (what we're moving away from)

| Name | Problem |
|---|---|
| `ElectionMode` | Implies election has a "mode" — models behavior, not authority |
| `ElectionOnly` | Implementation detail (no members) — not constitutional meaning |
| `FullMembership` | Describes data join — not constitutional authority |

### Tier 2: Transitional Semantic Bridge Vocabulary (Phase 3 — what we use now)

| Name | Role | Explicitly NOT |
|---|---|---|
| `ImportedVoterRegistry` | Semantic improvement over `ElectionOnly` | Final domain vocabulary |
| `MembershipRegistry` | Semantic improvement over `FullMembership` | Final domain vocabulary |

**These are classified as transitional semantic bridge vocabulary** — explicitly not final. They must carry `@deprecated` docblocks naming their classification and Phase 4 finalization.

### Tier 3: Constitutional Authority Vocabulary (Phase 4 — exploratory, not pre-approved)

The Phase 4 vocabulary must be determined through proper governance-language work, considering:
- delegation semantics
- federation requirements
- external registry integration
- hybrid participation authority
- constitutional treaty participation

**Phase 4 name candidates are exploratory.** Examples like `ImportedVoterAuthority` / `MembershipAuthority` are directional signals, NOT pre-approved final names. Phase 4 must evaluate these rigorously before adoption.

---

## 1.5. Authority vs Registry Semantic Taxonomy (CRITICAL)

### The Distinction

**Registry vocabulary** describes the operational mechanism — what data sources voters come from, how participation is governed operationally.

**Authority vocabulary** describes the constitutional legitimacy — who has legitimate power to determine voter participation rules, what makes participation "officially correct," how sovereign authority flows.

### Current Names Are Registry Vocabulary (NOT Authority)

| Term | What It Means | What It Does NOT Mean |
|---|---|---|
| `ImportedVoterRegistry` | Voters obtained via direct org enrollment (registry mechanism) | Final name for constitutional voter authority; legitimate sovereign authority source; permanent participation model |
| `MembershipRegistry` | Voters obtained via formal membership governance (registry mechanism) | Final name for constitutional membership authority; eternal authority structure; immutable governance |

### Why This Distinction Matters

- **Federation safety**: When external authorities participate (different jurisdictions, treaty signatories, delegated governance bodies), authority vocabulary changes fundamentally. Registry vocabulary (how voters are operationally sourced) remains stable across different authority arrangements.

- **Delegation modeling**: A delegated electoral body operates differently than a self-governing one, but both might use the same registry source (members table). The registry is constant; the authority is the variable.

- **Hybrid sovereignty**: An election might derive voter authority from multiple sources (org members + external partner members + appointed delegates). Registry vocabulary cannot express this; authority vocabulary must.

- **Constitutional treaties**: Federated systems with constitutional treaties require authority vocabulary to express legitimacy, not just operational sourcing.

**Phase 3 decision**: Use registry vocabulary as transitional bridge because we're not yet modeling authority conceptually. Phase 4 governance-language work will determine if these names transition to authority vocabulary or get replaced entirely.

### Phase 4 Vocabulary Determination (Exploratory)

Phase 4 MUST answer:
- Is "registry" a durable concept in distributed, federated, or delegated elections?
- Does "membership" authority differ meaningfully from "enrollment" authority in treaty contexts?
- How do external sovereign authorities express themselves in multi-party elections?
- Should vocabulary distinguish between "participation source" (registry) and "governance authority" (who decides rules)?

**None of these are pre-determined.** ImportedVoterAuthority, MembershipAuthority, or completely different names may emerge from this analysis.

---

## 2. Domain-vs-Persistence Vocabulary Boundaries

### Five-Layer Separation with Semantic Non-Equivalence

| Layer | Vocabulary | Method | Stability | Semantic Meaning |
|---|---|---|---|---|
| **Domain runtime** | `VoterSourceStrategy::ImportedVoterRegistry` | PHP case names | Phase 3 (transitional bridge) | Registry mechanism at runtime |
| **Persistence** | `'election_only'`, `'full_membership'` | `->toPersistenceValue()` | Legacy — DB only, never external | What is recorded in database schema |
| **Observability/telemetry** | Stable semantic keys | `->telemetryKey()` | Stable, immutable, governance-safe | What is traced for audit trails and constitutional disputes |
| **API/Inertia quarantine** | Quarantined transitional strings | `->toApiValue()` | Phase 3 temporary quarantine | What frontend contracts depend on (QUARANTINED) |
| **UX/human display** | Translated labels | `->label()` | Localized, unstable, user-language | What humans read on screen |

### CRITICAL: Semantic Non-Equivalence

**Even if two values are identical strings, they are NOT semantically equivalent.**

```
'election_only' (persistence) ≠ 'election_only' (hypothetical API)
  ↑ means: stored in database                    ↑ means: contract with frontend
  ↑ scope: infrastructure only                   ↑ scope: public interface
  ↑ authority: schema design                     ↑ authority: distributed contracts
  ↑ mutable by: migration                        ↑ mutable by: governance + federation
```

This non-equivalence is WHY each layer has its own method. Treating these as equivalent ("just use ->value everywhere") causes:
- Frontend fossilization (raw persistence tokens become historical contracts)
- Audit trail corruption (UX labels in logs instead of governance keys)
- Federation breaks (external parties receive infrastructure vocabulary instead of constitutional vocabulary)

### Method Responsibilities (No Mixing)

| Method | For | NOT For | Why Separate |
|---|---|---|---|
| `->toPersistenceValue()` | DB writes, migration, backfill | API, logs, telemetry, UX | Persistence is infrastructure; others are contracts |
| `->toApiValue()` | API/Inertia boundary (Phase 3: quarantined) | DB, telemetry | API contracts are distributed; DB is local |
| `->telemetryKey()` | Logs, metrics, structured observability | UX, API, DB | Telemetry must survive localization changes |
| `->label()` | Human-readable display in UI only | Telemetry, API, DB | Labels are localized; governance keys must not be |

**`->value` is NEVER called outside `->toPersistenceValue()` and `::from()` hydration.** Any other use collapses semantic boundaries.

---

## 3. Quarantine API Vocabulary Strategy

### The Problem with `toApiValue() → toPersistenceValue()`

A direct delegation creates **hidden semantic collapse**:

```php
// DANGEROUS — persistence and API vocabulary collapse:
public function toApiValue(): string {
    return $this->toPersistenceValue(); // ← 'election_only' leaks into API
}
```

Once `'election_only'` appears in frontend responses and is consumed by Vue props, it becomes a de facto stable contract — even with TODO comments. This creates the fossilization risk we're trying to prevent.

### The Quarantine Solution

Phase 3 `toApiValue()` returns **explicitly-prefixed quarantine strings** that signal transitionality to any consuming system:

```php
/**
 * Returns the API-facing voter source strategy identifier.
 *
 * PHASE 3 QUARANTINE: These values are temporary transitional tokens.
 * They are NOT stable API vocabulary. Frontend and API consumers MUST NOT
 * treat these as permanent contracts.
 *
 * Sunset criteria: Replace in Phase 4 when governance-vocabulary finalization completes.
 * Phase 4 will return stable constitutional-authority strings (TBD via governance review).
 *
 * @see VoterSourceStrategy::toPersistenceValue() for DB persistence (separate concern)
 */
public function toApiValue(): string
{
    return match($this) {
        self::ImportedVoterRegistry => 'transitional_imported_voter_registry',
        self::MembershipRegistry    => 'transitional_membership_registry',
    };
}
```

**Why explicit prefixed tokens instead of raw persistence values:**
- Frontend developers see `transitional_` prefix and know not to treat this as final
- Any hardcoded string comparison against these values in tests will show up as smell
- Makes Phase 4 replacement visible — when the prefix disappears, the contract is stable

### API Quarantine Governance Rules (Non-Negotiable)

**Why these rules exist:** Distributed contracts are historically sticky. Once frontend code hardcodes against these values, analytics systems track them, cached snapshots preserve them, and integrations depend on them — even years later. These rules prevent that fossilization.

#### Rule 1: Migration Ownership
- **Owner**: Backend team (infrastructure layer)
- **Responsibility**: Provide explicit migration path from `'transitional_*'` to stable vocabulary
- **Consequence of breach**: Frontend team left supporting deprecated vocabulary indefinitely

#### Rule 2: Sunset Enforcement Criteria
- **Precondition**: Phase 4 governance-language work COMPLETE (not just planned)
- **Precondition**: Constitutional authority vocabulary finalized (not exploratory)
- **Precondition**: All Phase 3 `transitional_` prefixes mapped to Phase 4 equivalents
- **Action**: Remove prefix ONLY when Phase 4 vocabulary is stable
- **Consequence of breach**: Temporary contracts become permanent; frontend investments increase 10-100x

#### Rule 3: Compatibility Duration
- **Phase 3 → Phase 4 overlap**: Both old (`transitional_*`) and new (stable) vocabulary available simultaneously
- **Overlap duration**: Minimum 2 minor Laravel releases, OR 8 weeks, whichever is longer
- **During overlap**: Frontend can migrate incrementally; no hard cutoff
- **Post-overlap**: `'transitional_*'` values removed; hardcoded consumers break (intentionally)
- **Consequence of breach**: Distributed systems forced to update all at once; operational instability

#### Rule 4: Distributed Contract Protection
- **Frontend stores**: Cannot cache `toApiValue()` as permanent identity
- **Analytics systems**: Must track BOTH `toApiValue()` (transient) AND `telemetryKey()` (stable)
- **Exports/snapshots**: Must include `telemetryKey()` for audit trail stability
- **Integrations**: Must NOT hardcode against `toApiValue()` without explicit compatibility duration agreement
- **Consequence of breach**: External parties locked into temporary vocabulary; federation impossible

---

## 4. Telemetry Vocabulary Separation

### Why `->label()` Must NOT Be Used for Logs/Telemetry

UX labels are:
- Localized (translations change; content improves)
- Human-oriented (marketing messaging)
- Unstable across versions (UI copy is not a contract)
- NOT governance-safe identifiers

Logs and metrics require:
- Stable semantic keys (survives localization changes)
- Machine-readable (structured observability)
- Consistent across deployments (no variation)
- Governance-safe for audit trails (unchangeable without governance decision)

### Telemetry Vocabulary Must Be Immutable & Governance-Safe

The `->telemetryKey()` method returns vocabulary that MUST be immutable and governance-safe because it is the foundation for:

1. **Audit Trails**: Constitutional dispute resolution requires stable identifiers that show historical decision patterns over years, not changing due to translations or UI rewording
2. **Constitutional Analysis**: When a federation of electoral bodies reviews one another's voting patterns, they rely on stable telemetry keys to understand governance decisions — "how many times did this authority use X governance model?"
3. **Federation Diagnostics**: External parties (future treaty partners, delegated authorities, oversight bodies) must interpret telemetry without needing translation or interpretation — the telemetry keys ARE the governance contract
4. **Governance Audit**: Internal governance reviews of "when and why did we switch models" must not be obscured by incidental UI changes

### Immutability Rules for telemetryKey()

| Scenario | Action | Why |
|---|---|---|
| UI translation improves | Use new `->label()` only | Telemetry must not change with UX copy |
| Concept is renamed in domain | GOVERNANCE DECISION required | Breaking change; all parties must coordinate |
| Registry mechanism changes | Phase 4 vocabulary substitution needed | Different semantic meaning; different telemetry key |
| External partner joins federation | Must support their telemetry keys AND ours | Bridge vocabulary if needed; never reuse old keys |

### Required Method

```php
/**
 * Returns the IMMUTABLE stable observability identifier.
 * 
 * GOVERNANCE-SAFE IDENTIFIER: These values are immutable constitutional markers.
 * Used for audit trails, federation diagnostics, constitutional dispute analysis.
 * NEVER change these values incidentally. Changes require explicit governance decision
 * and coordination with all consuming systems (logs, analytics, external partners).
 * 
 * These values are stable semantic keys — NOT persistence tokens, NOT UX labels.
 * Change ONLY through explicit governance-vocabulary revision, never incidentally.
 * 
 * @see Rule: Telemetry vocabulary changes = governance decision, not implementation detail
 */
public function telemetryKey(): string
{
    return match($this) {
        self::ImportedVoterRegistry => 'imported_voter_registry',
        self::MembershipRegistry    => 'membership_registry',
    };
}
```

### Usage Patterns

```php
// ✅ CORRECT: logs, metrics, audit trails
Log::info('Voter eligibility check', [
    'voter_strategy' => $mode->telemetryKey(),        // ← immutable, governance-safe
    'step' => 'eligibility_evaluation',               // ← other stable semantic keys
]);

// ✅ CORRECT: exported audit trails
$audit->record([
    'authority_model' => $mode->telemetryKey(),       // ← preserved for dispute resolution
    'timestamp' => now(),
]);

// ✅ CORRECT: federation diagnostic
foreach ($federatedElections as $election) {
    $strategies[] = [
        'org' => $election->organisation->name,
        'voter_authority' => $election->strategy->telemetryKey(),  // ← stable across deployments
    ];
}

// ❌ WRONG: UI labels in telemetry
Log::info('Election mode', [
    'mode' => $mode->label(),  // ← changes with translation; breaks audit trails
]);

// ❌ WRONG: persistence tokens in telemetry
Log::info('Voter strategy', [
    'value' => $mode->toPersistenceValue(),  // ← infrastructure token; not governance identifier
]);
```

---

## 5. Factory Anti-Corruption Documentation

### Classification: Factories Are Testing Utilities, NOT Domain Authorities

Factory defaults are **test convenience hydration only**, NOT domain construction authority.

**Why this distinction matters:** If a factory default accidentally becomes the "expected" behavior for a domain concept, tests will pass even when the actual application layer (controllers, commands, services) is broken. This masks production bugs where the authoritative creation path has drifted from what factories assume.

**The anti-corruption principle:** When testing "does the domain produce the right snapshot," the test MUST verify the snapshot originates from the authoritative path (e.g., `ElectionManagementController::store()`), NOT from the convenient factory default.

```php
// database/factories/ElectionFactory.php

/**
 * Default voter_source_strategy for test convenience hydration.
 *
 * TESTING ANTI-CORRUPTION UTILITY — NOT DOMAIN CONSTRUCTION AUTHORITY.
 * 
 * This value is a test scaffolding convenience for hydrating factory-built
 * elections with a plausible snapshot. It is NOT representative of how
 * voter_source_strategy is established in the actual domain.
 * 
 * The authoritative creation path is:
 *   ElectionManagementController::store()
 *       ↓
 *   ElectionManagementService::createElection()
 *       ↓
 *   VoterSourceStrategy::fromOrganisation() [at creation time only]
 *
 * Tests that verify creation-flow correctness MUST NOT rely on this default.
 * Tests MUST verify that snapshots originate from the authoritative path,
 * not from the factory. See invariant test below.
 *
 * Consequence of ignoring this anti-corruption principle:
 *   - Factory tests pass ✓
 *   - HTTP creation tests pass ✓
 *   - But production creation might use wrong logic, undetected
 *   - Factories mask application-layer bugs
 *
 * @see ElectionManagementController::store() for the authoritative creation path
 * @see VoterStrategyConvergenceTest::test_snapshot_originates_from_creation_flow_not_factory_default()
 */
'voter_source_strategy' => 'full_membership',
```

### Factory Invariant Test (Required — Prevents Masking)

```php
public function test_snapshot_originates_from_creation_flow_not_factory_default(): void
{
    // Use election-only org so factory default ('full_membership') would be WRONG
    $org = Organisation::factory()->electionOnly()->create();

    // Create election via HTTP flow (not factory) using actingAs admin
    $this->actingAs($this->createOrgAdmin($org))
         ->post(route('elections.store', $org), ['name' => 'Test', 'type' => 'real']);

    $election = $org->elections()->latest()->first();

    // Must be 'election_only' from creation flow, NOT 'full_membership' from factory default
    $this->assertEquals(
        'election_only',
        $election->voter_source_strategy,
        'Snapshot must derive from org mode at creation, not factory default'
    );
}
```

---

## 6. Enforcement Hierarchy: Heuristic vs Authoritative

### Three Levels, Distinct Trust & Coverage

| Level | Mechanism | Role | When to Use | What It Can't Catch | Explicit Classification? |
|---|---|---|---|---|---|
| **HEURISTIC** | Grep scans, `str_contains` file scanning, static pattern detection | Fast detection of obvious violations; catches ~80% of mistakes | During development, CI gates, quick validation | Dynamic calls, reflection, late binding, aliased imports, string interpolation | ✅ YES — label test as HEURISTIC |
| **BEHAVIORAL** | PHPUnit domain tests, behavioral invariants in actual test code | Authoritative: if test passes, behavior IS correct (for covered paths) | Verifying core domain invariants, creation flows, sovereignty | Untested code paths, configuration errors, integration mismatches | ✅ YES — label test as BEHAVIORAL |
| **GOVERNANCE** | Architecture tests, runtime enforcement, exception throwing | Tests the rules of the architecture itself; fail-fast at runtime | Enforcing boundaries that cannot be tested behaviorally (e.g., "all elections MUST have snapshot") | Exceptions can be caught and swallowed; architecture changes break tests; false confidence in stable boundaries | ✅ YES — label test as GOVERNANCE |

### Trust Levels (Decreasing Confidence)

```
Heuristic (80% confidence)
    ↓
Behavioral (100% confidence for covered paths only)
    ↓
Governance (high confidence in architecture; brittle if fundamentals change)
```

**Key insight:** A passing heuristic grep scan does NOT mean the rule is enforced. It means "no obvious violations detected on this scan." A passing behavioral test means "the domain behaves correctly on this path." A governance test means "the architecture structure is correct, but assumptions about forever-stability may be wrong."

### Explicit Test Classification Rules

**Every enforcement test MUST declare its level in the docblock:**

```php
// ========================================
// LEVEL: HEURISTIC
// ========================================
// Grep-based scanning for obvious violations.
// Fast, but cannot detect: reflection, late binding, dynamic string construction.
//
// If this test fails: Something is obviously wrong; fix immediately.
// If this test passes: Might still have subtle violations; see BEHAVIORAL tests.
public function test_from_organisation_is_not_called_outside_approved_contexts(): void
{
    // This uses grep to scan app/ and routes/ for direct calls to
    // VoterSourceStrategy::fromOrganisation() outside the 2 approved callers
    ...
}

// ========================================
// LEVEL: BEHAVIORAL
// ========================================
// Authoritative: verifies actual runtime behavior is correct.
// Only covers paths WITH test coverage; untested paths are unknown.
//
// If this test passes: Behavior is definitely correct on this path.
// If this test fails: Actual domain logic is broken.
public function test_snapshot_originates_from_creation_flow_not_factory_default(): void
{
    // Creates an election via HTTP (not factory) and verifies snapshot
    // matches org mode at creation time, not factory default
    ...
}

// ========================================
// LEVEL: GOVERNANCE
// ========================================
// Architecture structural tests; fail-fast enforcement at runtime.
// Protects against repeated violations; assumes boundaries remain stable.
//
// If this test passes: Architecture structure is correct RIGHT NOW.
// If the architecture fundamentally changes: This test may become obsolete.
public function test_missing_snapshot_throws_exception_enforcing_sovereignty(): void
{
    // Verifies that ElectionMode::fromElection() throws if snapshot is null.
    // This is a governance rule: elections MUST have voter_source_strategy.
    // Database constraint prevents this in production, but test guards against
    // code that might try to construct elections without going through
    // the proper creation path.
    ...
}
```

### Using Enforcement Levels

- **Heuristic tests**: Run early and often (pre-commit, CI gate)
- **Behavioral tests**: Part of main test suite; must not decrease count
- **Governance tests**: Part of architecture test suite; guards invariants
- **Never use heuristic result to claim behavioral correctness**: "Grep found no violations" does NOT mean "behavior is correct"

---

## 7. Internal vs External API Boundary Governance

### Three Distinct Contract Layers (Not Mixed)

| Boundary | Type | Vocabulary Policy | Mutability | Phase 3 Action | Phase 4 Action | Phase 5+ |
|---|---|---|---|---|---|---|
| **Internal Inertia props** (Vue SPA) | Distributed but internal | Transitional allowed — `toApiValue()` returns quarantine prefix; backward-compat alias | High (frontend state changes frequently) | Add `voter_source_strategy` alongside `uses_full_membership` | Remove old alias when Vue migrated; update to stable vocab | Vue caches, stores, computed props update |
| **Public API contracts** (REST/GraphQL) | External, versioned | Stable vocabulary only | Very low (contract changes = version bump) | No new public API endpoints this phase | Define stable vocabulary AFTER governance-language finalization | External clients depend on versioned APIs |
| **External integrations** (federation, treaty partners) | Fully external, sovereign | Formally versioned with compatibility terms | Extremely low (other sovereign authorities depend) | Not in scope Phase 3 | Not in scope Phase 4 | Constitutional treaties may regulate changes |

### Governance Rules by Boundary

#### Layer 1: Internal Inertia Props (Vue SPA)
- **Scope**: Routes, controllers, Inertia responses delivered to first-party Vue app
- **Vocabulary**: May use `->toApiValue()` with `transitional_` prefix during Phase 3
- **Backward compatibility**: May include old `uses_full_membership` alongside new `voter_source_strategy` during migration
- **Migration path**: Vue code migrates incrementally; old prop deprecated (but present) during overlap
- **Risk level**: Medium (cached in user browsers, frontend stores, CDN snapshots)
- **Phase 3 rule**: ✅ Allowed; add prop with quarantine prefix
- **Phase 4 rule**: Replace `'transitional_*'` values with stable vocabulary; keep old prop for 2+ releases
- **Phase 5+ rule**: Complete removal of old prop after deprecation period

#### Layer 2: Public API Contracts (REST/GraphQL endpoints)
- **Scope**: Endpoints explicitly designed for external consumption, documented in API docs
- **Vocabulary**: STABLE ONLY — no transitional prefixes
- **Backward compatibility**: Versioning required for ANY change (v1, v2, v3...)
- **Migration path**: Old version continues working; consumers migrate at their own pace
- **Risk level**: Very high (partner integrations, third-party apps, published contracts)
- **Phase 3 rule**: ❌ FORBIDDEN — no new public API endpoints
- **Phase 4 rule**: Create only after governance vocabulary is final; document stability promise
- **Phase 5+ rule**: Break existing API versions ONLY with major version bump and deprecation period

#### Layer 3: External Integrations (Federation, Treaty Partners)
- **Scope**: Systems outside our organization that integrate with our elections
- **Vocabulary**: Formally versioned with explicit compatibility commitments
- **Backward compatibility**: Governed by constitutional treaties or SLAs
- **Migration path**: Coordination with external parties; no unilateral changes
- **Risk level**: Extreme (violating terms breaks federations; legal consequences)
- **Phase 3 rule**: ❌ FORBIDDEN — federation not in scope yet
- **Phase 4 rule**: ❌ FORBIDDEN — federation design comes later
- **Phase 5+ rule**: Treaty-governed vocabulary with explicit sunset/version dates

### Layering Violations (What NOT to Do)

| Violation | Example | Impact |
|---|---|---|
| **Internal → Public** | Expose `toApiValue()` as public API | Transitional vocabulary becomes distributed contract; breaks federation |
| **Internal → External** | Send Inertia prop to external system | Partner locked into temporary vocab; breaks when Phase 4 changes |
| **Public → Internal** | Use versioned API value in controller logic | API version couples to internal logic; refactoring breaks API |
| **External → Public** | Expose federation contract as REST API | Multi-party rules embedded in single-party versioning; impossible to update |

### Phase 3 Strict Rule

**Only touch internal Inertia props. Do NOT create any new:**
- ❌ Public REST/GraphQL API endpoints
- ❌ Documented API contracts
- ❌ Federation integrations
- ❌ External data exports that assume this vocabulary
- ❌ Analytics schema using `toApiValue()` as permanent identifier

All such surfaces must wait for Phase 4 governance-vocabulary stabilization.

---

## 8. Vocabulary Convergence Matrix (Final for Phase 3)

| Current PHP Name | Phase 3 PHP Name | DB Value (UNCHANGED) | telemetryKey() | toApiValue() (quarantine) |
|---|---|---|---|---|
| `ElectionMode` | `VoterSourceStrategy` | N/A | N/A | N/A |
| `ElectionMode::ElectionOnly` | `VoterSourceStrategy::ImportedVoterRegistry` | `'election_only'` | `'imported_voter_registry'` | `'transitional_imported_voter_registry'` |
| `ElectionMode::FullMembership` | `VoterSourceStrategy::MembershipRegistry` | `'full_membership'` | `'membership_registry'` | `'transitional_membership_registry'` |
| `->isElectionOnly()` | `->isImportedVoterRegistry()` | N/A | | |
| `->isFullMembership()` | `->isMembershipRegistry()` | N/A | | |
| `ElectionMode::fromOrganisation()` | `VoterSourceStrategy::fromOrganisation()` | N/A | | Restricted to 2 callers |
| `ElectionMode::fromElection()` | `VoterSourceStrategy::fromElection()` | N/A | | Sovereign path |
| (new) `->toPersistenceValue()` | sole `->value` consumer | returns `->value` | | |
| (new) `->toApiValue()` | quarantine API boundary | returns transitional string | | |
| (new) `->telemetryKey()` | stable observability | returns stable semantic key | | |
| `->label()` | UX/human display only | returns localized string | | |

---

## 9. Active Sovereignty Violation (Step 0 — Priority Zero)

### The Bug

```php
// app/Services/VoterEligibilityService.php line 54
if (!$org->uses_full_membership) { // ← reads org boolean; ignores passed ElectionMode

// Caller (ElectionVoterController.php:74) passes 3rd arg but method only accepts 2:
->unassignedEligibleQuery($organisation, $assignedUserIds, ElectionMode::fromElection($election))
// 3rd arg silently dropped by PHP
```

### The Fix

```php
public function unassignedEligibleQuery(
    Organisation $org,
    array $excludeUserIds = [],
    ?ElectionMode $mode = null      // ← add (current names; renamed in Step 2)
): Builder {
    $mode ??= ElectionMode::FullMembership;
    if ($mode->isElectionOnly()) { /* election-only path */ }
    else { /* full membership path */ }
}
```

---

## 10. VoterSourceStrategy.php — New Enum File Design

```php
enum VoterSourceStrategy: string
{
    /**
     * TRANSITIONAL SEMANTIC BRIDGE VOCABULARY.
     * Governance meaning: voter authority derives from org enrollment (no membership filtering).
     *
     * @deprecated Case name is transitional. Phase 4 will determine final constitutional name
     *   through proper governance-language review. Do not treat 'ImportedVoterRegistry' as final.
     * @see Phase 4 exploration: candidates include ImportedVoterAuthority (not pre-approved)
     */
    case ImportedVoterRegistry = 'election_only';

    /**
     * TRANSITIONAL SEMANTIC BRIDGE VOCABULARY.
     * Governance meaning: voter authority derives from formal membership governance (fees, status, type).
     *
     * @deprecated Case name is transitional. Phase 4 will determine final constitutional name
     *   through proper governance-language review. Do not treat 'MembershipRegistry' as final.
     * @see Phase 4 exploration: candidates include MembershipAuthority (not pre-approved)
     */
    case MembershipRegistry = 'full_membership';

    // === PERSISTENCE BOUNDARY ===
    /** @see toPersistenceValue() for DB writes — do NOT use ->value directly outside this method */
    public function toPersistenceValue(): string { return $this->value; }
    public static function fromPersistenceValue(string $value): self { return self::from($value); }

    // === SOVEREIGN RESOLUTION ===
    public static function fromElection(Election $election): self { /* throws if null */ }
    /**
     * @internal Approved callers ONLY: BackfillVoterSourceStrategy, ElectionManagementController::store()
     */
    public static function fromOrganisation(Organisation $org): self { ... }

    // === API QUARANTINE BOUNDARY ===
    /**
     * PHASE 3 QUARANTINE — not stable vocabulary.
     * Sunset: replace in Phase 4 after governance-language finalization.
     */
    public function toApiValue(): string
    {
        return match($this) {
            self::ImportedVoterRegistry => 'transitional_imported_voter_registry',
            self::MembershipRegistry    => 'transitional_membership_registry',
        };
    }

    // === OBSERVABILITY ===
    /** Stable semantic key for logs, metrics, audit — never changes incidentally */
    public function telemetryKey(): string
    {
        return match($this) {
            self::ImportedVoterRegistry => 'imported_voter_registry',
            self::MembershipRegistry    => 'membership_registry',
        };
    }

    // === UX DISPLAY ===
    public function label(): string
    {
        return match($this) {
            self::ImportedVoterRegistry => 'Election-Only',
            self::MembershipRegistry    => 'Full Membership',
        };
    }

    // === HELPERS ===
    public function isImportedVoterRegistry(): bool { return $this === self::ImportedVoterRegistry; }
    public function isMembershipRegistry(): bool    { return $this === self::MembershipRegistry; }
}
```

---

## 11. Implementation Sequence

| Step | Focus | Key Files | Gate |
|---|---|---|---|
| **0** | Fix active sovereignty violation | `VoterEligibilityService.php` | TDD: write failing test first |
| **1** | Factory & seeder alignment + anti-corruption docs | 5 files | TDD: factory + invariant tests |
| **2a** | Pre-rename safety audit (grep, serialization, container scan) | Read-only | All checks pass |
| **2b** | Create `VoterSourceStrategy.php` (new file, both coexist temporarily) | 1 new file | Full test suite passes |
| **2c** | Incremental: update 25 PHP app files (import + type hints) | 25 files | Suite after each batch |
| **2d** | Update 16 test files + rename `ElectionModeTest.php` | 16 files | Suite passes |
| **2e** | Delete `ElectionMode.php`, run final safety audit | 1 delete | 194+ tests, 0 grep hits |
| **3** | `fromOrganisation()` heuristic enforcement test (classified explicitly) | 1 test | Test passes |
| **4** | API contract stabilization (quarantine vocabulary, backward-compat aliases) | 3 controller/route files | Inertia prop tests pass |
| **5** | Translation content review (key NAMES unchanged; content may improve) | 3 lang files | Settings test passes |

---

## 12. Critical Files Summary

| File | Step | Change |
|---|---|---|
| `app/Services/VoterEligibilityService.php` | 0 | Add `?ElectionMode $mode` param; remove org boolean read |
| `database/factories/OrganisationFactory.php` | 1 | Add `electionOnly()`, `fullMembership()` states |
| `database/factories/ElectionFactory.php` | 1 | Add `voter_source_strategy` default (with anti-corruption doc) + 2 states |
| `database/seeders/ElectionSeeder.php` + 2 | 1 | Add `voter_source_strategy` field |
| `app/Domain/Election/Enum/VoterSourceStrategy.php` | 2b | NEW — full design per Section 10 |
| `app/Domain/Election/Enum/ElectionMode.php` | 2e | DELETE |
| ~25 PHP application files | 2c | Import + type hint + method call updates |
| ~16 test files | 2d | Import + assertion method name updates |
| `tests/Unit/Domain/Election/VoterSourceStrategyTest.php` | 2d | RENAME + update |
| `tests/Architecture/GovernanceRuntime/SovereigntyInvariantTest.php` | 3 | Heuristic enforcement + factory invariant |
| `app/Http/Controllers/Election/VoterImportController.php` | 4 | Add `->toApiValue()` prop; keep backward-compat |
| `app/Http/Controllers/ElectionVoterController.php` | 4 | Add `voter_source_strategy` prop |
| `routes/web.php:123` | 4 | Add `voter_source_strategy` |
| `resources/lang/{en,de,np}/organisations.php` | 5 | Review content; key NAMES unchanged |

## What Is NOT Changed

| Item | Reason |
|---|---|
| DB string values `'election_only'`, `'full_membership'` | PHP class rename only — no DB migration |
| `Organisation.uses_full_membership` | Org owns this |
| i18n JSON keys in JS locale files | Tied to Vue migration; Phase 4 |
| Vue `.vue` files | Phase 4 frontend migration |
| Route names | Tied to Vue; Phase 4 |
| PHP lang key NAMES | UX vocabulary ≠ domain vocabulary |
| Any public API endpoints | Stability requires governance-vocabulary finalization first |

---

---

## 13. Constitutional Language Stabilization (NOT Simple Renaming)

### What This Phase Actually Is

This is NOT a simple mechanical renaming of `ElectionMode` → `VoterSourceStrategy`. It is **constitutional language stabilization** — establishing semantic sovereignty for how elections govern their own voter participation authority.

### The Three Semantic Shifts

1. **From "Mode" (behavior) to "Strategy" (authority mechanism)**
   - Old: `ElectionMode` implied the election *has different modes* — a configuration property
   - New: `VoterSourceStrategy` implies the election *owns authoritative strategy for voter sourcing*
   - Consequence: This establishes elections as sovereign over "who participates" decisions

2. **From Implementation Detail (no members vs has members) to Registry Classification**
   - Old: `ElectionOnly` / `FullMembership` were about data schema (presence/absence of members table)
   - New: `ImportedVoterRegistry` / `MembershipRegistry` classify the *governance source* of voter authority
   - Consequence: Enables federation — external authorities can understand governance source without seeing our schema

3. **From Mutable Configuration to Immutable Constitutional Snapshot**
   - Old: Phase 1 read org boolean at runtime (org changes affected election rules retroactively)
   - New: Election snapshots voter_source_strategy atomically at creation (immutable authority)
   - Consequence: Elections own their authority; cannot be changed by org mutations

### Why Language Stabilization Matters for Federation

When multiple electoral authorities federate (e.g., state + national election, or multi-party parliament):

**Without stabilized language:**
```
Party A: "We use election_only mode" (implementation detail)
Party B: "We use full_membership mode" (different schema)
→ Cannot coordinate participation rules
→ Cannot verify one another's voter eligibility
→ Cannot audit cross-party participation
```

**With stabilized language:**
```
Party A: "We use ImportedVoterRegistry strategy" (governance source)
Party B: "We use MembershipRegistry strategy" (governance source)
→ Both understand we have different participation authorities
→ Can establish cross-party validation rules
→ Can audit against our respective authorities
→ Can plan future treaty participation
```

### Phase 4 Will Revisit These Names

Phase 4 governance-language work will determine if:
- "ImportedVoterRegistry" is the right *permanent* name or if "ImportedVoterAuthority" better expresses constitutional legitimacy
- Registry/Authority distinction itself is the right model or if different taxonomy is needed
- Federation, delegation, and hybrid sovereignty require entirely different vocabulary

**Phase 3 names are explicitly transitional — a bridge from implementation detail to proper constitutional language.**

---

## 14. Semantic Sovereignty Protection Rules (Non-Negotiable)

These rules enforce constitutional language stabilization. Violations undermine the sovereignty we're establishing.

1. **Persistence boundary** — `->value` is NEVER called outside `->toPersistenceValue()` and `::from()` hydration
2. **API quarantine** — `->toApiValue()` returns quarantine-prefixed strings; never raw persistence tokens
3. **UX-telemetry separation** — `->label()` is ONLY for UX; `->telemetryKey()` is ONLY for audit/governance
4. **Telemetry immutability** — `->telemetryKey()` changes ONLY via governance decision, never incidentally
5. **fromOrganisation() restriction** — Called ONLY from the 2 approved callers (BackfillVoterSourceStrategy, ElectionManagementController)
6. **Phase 4 openness** — Constitutional names remain exploratory; not pre-approved; federation may require rethinking
7. **Public API quarantine** — No public API endpoints created in Phase 3; all must wait for Phase 4 vocabulary finalization
8. **Internal prop transitionality** — Inertia props may carry transitional vocabulary with explicit `transitional_` markers during Phase 3
9. **Test count protection** — Test count cannot decrease after rename; 194+ must pass (prevents masking bugs)
10. **Semantic non-equivalence** — Even if two values are identical strings, their semantic meanings in different layers are NOT equivalent

---

## 15. Phase 4+ Evolution Path (Exploratory, Not Pre-Approved)

| Milestone | Content |
|---|---|
| **Phase 4** | Governance-language review for constitutional case names. Remove `transitional_` prefix from `toApiValue()`. Vue migration to stable API vocabulary. Final names to be determined — NOT pre-approved. |
| **Phase 5+** | DB migration for persistence values if warranted. External registry federation. Delegation authority. Constitutional treaty participation. |

---

## Verification

```bash
# Step 0: Sovereignty fix
php artisan test tests/Architecture/GovernanceRuntime/ --env=testing

# Step 1: Factory + invariant
php artisan test tests/Unit/Factories/ --env=testing

# Step 2a: Safety audit (before rename)
grep -rn "ElectionMode" app/ tests/ config/ routes/ --include="*.php"
grep -rn "'ElectionMode'" app/ tests/ --include="*.php"
grep -rn "ElectionMode::class" app/ tests/ --include="*.php"
grep -rn "ElectionMode" app/Jobs/ app/Listeners/ --include="*.php"

# Step 2e: Post-rename regression (count must not decrease)
php artisan test --env=testing

# Step 3: Architecture heuristics
php artisan test tests/Architecture/ --env=testing

# Step 4: API quarantine props
php artisan test tests/Feature/Election/ --env=testing

# Final
php artisan test --env=testing
# Expected: 194+ passing, 0 failures
```
