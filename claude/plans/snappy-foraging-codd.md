# Plan: Phase C.2.4 — Frontend Constitutional Runtime Projection Stabilization

**Plan ID:** snappy-foraging-codd  
**Branch:** postgressql  
**Scope:** Migrate remaining Vue components from legacy vocabulary to constitutional runtime projection  
**Status:** Ready for implementation (TDD-first, --env=testing only)  
**Supersedes:** Phase 3 Consolidation (complete)

---

## Strategic Principle (NON-NEGOTIABLE)

The frontend has evolved from stateful UI rendering into **distributed constitutional-runtime projection**.

Vue components now participate in constitutional-runtime integrity. That is a fundamentally different engineering responsibility.

```
BEFORE Phase C.2.4:
  Frontend interprets governance.
  Vue components derive authority from state/status/role.
  Governance logic is distributed.

AFTER Phase C.2.4:
  Frontend renders sovereign authority snapshots.
  Vue components read capabilities — they never derive them.
  Governance logic lives exclusively in ElectionCapabilityResolver.
```

Internal Inertia `stateMachine` contracts are **INTERNAL runtime projections** — NOT federation-safe constitutional protocols. Federation uses treaty-governed contracts. Inertia uses internal transitional contracts.

---

## System Taxonomy

| Artifact | Role |
|---|---|
| `ElectionConstitution` | Sovereignty authority source (all rules) |
| `ElectionCapabilityResolver` | Runtime derivation engine (derives authority from facts + policies) |
| `StateMachineContract` | Distributed constitutional runtime protocol (internal Inertia boundary) |
| `useElectionCapabilities()` | Frontend constitutional-runtime access boundary |

---

## Contract Boundary Classification

| Boundary | Stability | Versioning | Governance |
|---|---|---|---|
| Internal Inertia (`stateMachine` prop) | Transitional | `resolver_version` semver | Backend team ownership |
| Public REST API | Semver governed | v1, v2, etc. | Architecture review |
| Federation protocol | Treaty governed | Partner-negotiated | Constitutional treaty |

The `stateMachine` Inertia prop is INTERNAL. It must NEVER be exposed as a public API or federation contract. Internal transitional semantics must not leak externally.

---

## 1. Migration Taxonomy

| Category | Purpose | Mechanism | Allowed Sources |
|---|---|---|---|
| **Lifecycle Visualization** | Render the election's current constitutional phase | `election.state` + `ElectionLifecycleStates.*` constants | `election.state` prop |
| **Constitutional Authority Guards** | Control action availability based on sovereign permission | `useElectionCapabilities()` only | `stateMachine.capabilities` prop |

**Lifecycle state ≠ constitutional authority.**

`election.state === VOTING_ACTIVE` tells you where the election is in progression. It does NOT tell you what the current user is permitted to do.

Why they differ:
- Suspension overlay: state remains `VOTING_ACTIVE`, all actions except `resume` are denied
- Role restrictions: two users in same state have different permitted actions
- Preconditions: action constitutional for state but blocked by unmet conditions
- Future overlays: `legal_hold`, `emergency_freeze`, `compliance_review` — all orthogonal to lifecycle

**Rule: Lifecycle constants for visualization only. Capabilities for all permission decisions.**

---

## 2. `StateMachineContract` — Distributed Constitutional Runtime Protocol

This is NOT merely a frontend TypeScript type. It is a **distributed constitutional runtime protocol** flowing from `ElectionCapabilityResolver` to every page that makes governance decisions.

### TypeScript Contract

Create `resources/js/types/StateMachineContract.ts`:

```typescript
interface CapabilityEntry {
  allowed: boolean;
  /**
   * Stable machine-readable governance identifier.
   * GOVERNANCE VOCABULARY: changes require ADR + deprecation window.
   * Used by composable logic and test assertions.
   * NEVER branched on for display text.
   */
  denial_reason: string | null;
  /**
   * Localized UX explanation only.
   * NEVER parsed, branched on, or used in logic.
   * Display in UI tooltips/messages only.
   */
  denial_detail: string | null;
}

interface CapabilitiesMap {
  submit_for_approval: CapabilityEntry;
  approve: CapabilityEntry;
  reject: CapabilityEntry;
  auto_submit: CapabilityEntry;
  begin_setup: CapabilityEntry;
  revise_and_resubmit: CapabilityEntry;
  complete_administration: CapabilityEntry;
  complete_nomination: CapabilityEntry;
  apply_candidacy: CapabilityEntry;
  open_voting: CapabilityEntry;
  close_voting: CapabilityEntry;
  publish_results: CapabilityEntry;
  archive: CapabilityEntry;
  suspend: CapabilityEntry;
  resume: CapabilityEntry;
}

interface CapabilitiesMetadata {
  resolver_version: string;   // semver — governs distributed-runtime compatibility
  generated_at: string;       // ISO8601 — snapshot derivation timestamp
  constitution_hash: string;  // MD5 of ElectionConstitution::RULES — drift detection
}

export interface StateMachineContract {
  currentState: string;             // ElectionLifecycleStates value
  completedStates: string[];        // ordered progression for visualization
  projectionAvailable: boolean;
  capabilities: CapabilitiesMap;    // constitutional authority snapshot
  capabilities_metadata: CapabilitiesMetadata;
  /**
   * EPHEMERAL — debug only.
   * FORBIDDEN: production components, test assertions, telemetry, federation.
   * Not a stable API. Never depend on this structure.
   */
  capabilities_trace?: unknown;
}
```

### Contract Schema Governance Rules

| Concern | Rule |
|---|---|
| `capabilities` keys | Must match `ElectionConstitution::RULES` exactly — tested by `ElectionStateMachineCapabilitiesTest` |
| `denial_reason` | Stable governance vocabulary — renames require ADR + compatibility window |
| `denial_detail` | UX text only — NEVER parsed, branched on, or used in logic |
| `allowed` boolean | Constitutional authority snapshot — single source of truth |
| `capabilities_trace` | Ephemeral debug data — forbidden in production code, tests, telemetry |

---

## 3. `constitution_hash` — Runtime Constitutional Drift Detection

The `constitution_hash` field is NOT optional metadata. It is **runtime constitutional drift detection infrastructure**.

| Concern | Rule |
|---|---|
| Hash mismatch | Backend constitution changed; frontend received stale snapshot |
| Frontend behavior on mismatch | Display stale-runtime warning to admin; do not silently trust capabilities |
| Telemetry | Emit governance drift event when hash mismatches between requests |
| Future federation | External authorities verify constitution compatibility via hash |

Frontend must not treat this as decorative. If `constitution_hash` mismatches a known reference, capabilities cannot be trusted as current.

---

## 4. `resolver_version` — Semver Governance Policy

| Change Type | Version Impact | Rationale |
|---|---|---|
| Add capability | MINOR | Additive; existing consumers unaffected |
| Remove capability | MAJOR | Breaking; distributed consumers must update |
| Rename `denial_reason` key | MAJOR | Stable governance vocabulary — breaking change |
| Add metadata field to `capabilities_metadata` | MINOR | Additive |
| `denial_detail` UX text change | PATCH | UX copy; no logic impact |
| Add new overlay type | MINOR | New denial_reason keys; additive |

Frontend must NOT depend on `resolver_version` for logic branching. It is for compatibility verification and telemetry only.

---

## 5. `denial_reason` vs `denial_detail` — Hard Semantic Separation

| Field | Meaning | Example | Consumed By |
|---|---|---|---|
| `denial_reason` | Stable machine-readable governance identifier | `'voting_window_not_defined'`, `'suspended'` | Composable logic, test assertions, tooltip key lookup |
| `denial_detail` | Localized human-readable UX text | `'Voting dates must be set before opening the ballot'` | UI display only — never in logic |

### Governance vocabulary ownership (`denial_reason`)

- All `denial_reason` values are constitutional governance vocabulary
- Renames require ADR
- Deprecation window: minimum 8 weeks with old + new key both present
- Telemetry uses `denial_reason` for audit continuity — changes break audit trails

### FORBIDDEN patterns

```typescript
// ❌ branching on denial_detail text
if (capability.denial_detail?.includes('ballot')) { ... }

// ❌ comparing denial_detail to literal
if (capability.denial_detail === 'Some UX message') { ... }

// ❌ using denial_detail as a key
const key = `tooltips.${capability.denial_detail}`;
```

### ALLOWED patterns

```typescript
// ✅ display denial_detail as UX text (no branching)
<span>{{ capability.denial_detail }}</span>

// ✅ branch on denial_reason (stable governance key)
if (denialReason.value === 'suspended') { showSuspensionNote.value = true; }

// ✅ denial_reason as i18n key lookup
const tooltipKey = `tooltips.capability_denied.${denialReason.value}`;
```

---

## 6. Frontend Constitutional Invariants + Enforcement Ownership

### Invariant 1: Vue renders authority — Vue does not derive authority

```typescript
// ❌ FORBIDDEN — frontend deriving from state + role
if (election.state === VOTING_ACTIVE && user.role === 'chief') { showButton = true; }

// ✅ CORRECT — render pre-derived authority
const { canCloseVoting } = useElectionCapabilities(computed(() => props.stateMachine));
```

**Enforced by:** Behavioral Vitest component tests (authoritative)

### Invariant 2: `useElectionCapabilities()` is the only authorized authority access point

No alternative composables. No page-local capability logic. No capability wrappers.

**Enforced by:** Architecture grep test + behavioral tests

### Invariant 3: `ElectionLifecycleStates` for visualization only — NEVER for permission decisions

```typescript
// ✅ state badge, label, progress
:class="`phase-badge--${election.state}`"

// ❌ action button guard (overlays diverge authority from state)
v-if="election.state === ElectionLifecycleStates.VOTING_ACTIVE"
```

**Enforced by:** Vitest architecture grep test

### Invariant 4: No capability reconstruction from lifecycle constants + role

```typescript
// ❌ FORBIDDEN — distributed sovereignty using constants
const canPublish = computed(() => 
  election.state === ElectionLifecycleStates.COUNTING && userIsChief
);
```

This is the subtle violation Vitest grep tests must catch.

**Enforced by:** Architecture grep test detecting lifecycle constant + role/permission combination

### Invariant 5: `stateMachine` prop never cached or persisted client-side

Stale capability cache = stale constitutional authority.

**Enforced by:** Code review + architecture test scanning for localStorage/sessionStorage usage with stateMachine

### Invariant 6: `capabilities_trace` forbidden in all production paths

**Enforced by:** Architecture test scanning for `capabilities_trace` references in component code

---

## 7. `useElectionCapabilities()` — Boundary Constraints

This composable is the **frontend constitutional-runtime access boundary**.

### Critical evolutionary constraint

`useElectionCapabilities()` **may expose authority snapshots** from the backend-derived `stateMachine`.  
`useElectionCapabilities()` **may NOT derive constitutional authority itself**.

It projects. It does not compute governance decisions.

Any logic inside the composable that combines `election.state` + role + conditions = **constitutional monolith risk**. Future overlays must flow through `ElectionCapabilityResolver` (backend), not accumulate in the composable.

### God composable prevention rule

If the composable grows to include:
- lifecycle reasoning
- overlay handling
- role transforms
- federation logic
- audit event emission
- denial formatting beyond tooltip key lookup

...it has become a god composable and must be decomposed.

---

## 8. `ElectionLifecycleStates` — Permitted vs Forbidden Usage

| Use Case | Allowed? | Rationale |
|---|---|---|
| Visual state badge (CSS class) | ✅ | Pure visualization |
| Informational section rendering | ✅ | Shows context, not permission |
| Progress step indicator | ✅ | Visualization of constitutional progression |
| Action button `v-if` guard | ❌ | Overlays diverge authority from state |
| Content permission gate | ❌ | State cannot express suspension/role |
| Computed `canSomething` derived from state constant | ❌ | Distributed sovereignty |
| State constant + role combination in `computed` | ❌ | Reconstructing authority |

---

## 9. Enforcement Trust Hierarchy

| Level | Mechanism | Trust | Purpose |
|---|---|---|---|
| **Behavioral** | PHPUnit: Inertia prop shape assertions | AUTHORITATIVE | Capabilities object is actually correct |
| **Behavioral** | Vitest: component button visibility tests | AUTHORITATIVE | Component renders authority correctly |
| **Governance** | PHP architecture tests (class purity, boundary) | Structural | Domain layer does not leak to view |
| **Heuristic** | Vitest `expect(src).not.toContain()` grep | SMOKE ALARM ONLY | Obvious violations detected |

**Critical rule:** Absence of a grep match ≠ absence of authority reconstruction. Heuristic tests cannot detect computed patterns, aliased constants, or runtime string construction. Behavioral tests are the source of truth.

---

## 10. Files to Migrate

| File | Current Pattern | Category | Notes |
|---|---|---|---|
| `Show.vue` L30, L182 | `election.status` class, raw state string | Lifecycle Visualization | Display only |
| `Viewboard.vue` L213 | `election.state === 'voting_active'` | Lifecycle Visualization | Replace with constant |
| `Admin/Elections/All.vue` L109 | `election.state` class/label | Lifecycle Visualization | Display only |
| `Organisations/Elections/Index.vue` L65 | `election.status` class | Lifecycle Visualization | Display only |
| `Organisations/ElectionCommission.vue` L72 | `:status="election.status"` | Lifecycle Visualization | StatusBadge prop vocabulary |
| `Organisations/Posts.vue` L88 | `election.status === 'active'` | **Case-by-case** | Evaluate per guard |
| `Commission/Dashboard.vue` L63, L74 | `election.status === 'active'` | **Case-by-case** | Evaluate per guard |
| `Election/Candidacy/Apply.vue` L61, L165 | `election.status === 'active'` | Lifecycle Visualization | Badge + contextual text |

### Case-by-case decision rule

**Lifecycle Visualization** if the guard answers: "Is the election in state X right now?"  
→ `election.state === ElectionLifecycleStates.*`

**Constitutional Authority** if the guard answers: "Is this user permitted to do X?"  
→ Must use `useElectionCapabilities()` — overlays and roles diverge from state

Write a one-line written decision in code comment before changing each guard.

---

## 11. Implementation Sequence

| Step | Focus | Gate |
|---|---|---|
| **0** | Safety audit — grep all legacy patterns | Documented inventory |
| **1** | Create `StateMachineContract.ts` | TypeScript compiles |
| **2** | TDD: add failing architecture tests | Tests FAIL (red) — correct TDD gate |
| **3** | Migrate Lifecycle Visualization components (6 files) | Tests green |
| **4** | Evaluate Posts.vue + Commission/Dashboard.vue (case-by-case) | Written decision per guard |
| **5** | Constitutional Authority migration where required (PHP test first) | PHP test passes, then Vue migrated |
| **6** | Final verification — all behavioral + heuristic tests pass | Zero legacy pattern matches |

---

## Step 0: Safety Audit Commands

```bash
# election.status comparisons
grep -rn "election\.status" resources/js/ --include="*.vue"

# Hardcoded lifecycle string literals
grep -rn "'voting_active'\|'results_published'\|'archived'\|'counting'\|'setup_\|'draft'\|'approved'" resources/js/ --include="*.vue"

# Lifecycle constant + role/permission combination (distributed sovereignty risk)
grep -rn "ElectionLifecycleStates\.[A-Z_]*.*&&\|&& .*ElectionLifecycleStates" resources/js/ --include="*.vue"

# can_* flat props
grep -rn "\.can_vote\b\|\.can_access\b\|\.can_vote_now\b" resources/js/ --include="*.vue"

# capabilities_trace references (must be zero in components)
grep -rn "capabilities_trace" resources/js/Pages/ --include="*.vue"

# Already migrated (baseline)
grep -rn "useElectionCapabilities" resources/js/ --include="*.vue"
```

---

## Step 2: TDD Architecture Tests (Write Failing First)

Add to `tests/js/Architecture/ElectionFrontendArchitectureTest.spec.ts`:

```typescript
// Lifecycle Visualization violations
it('Show.vue does not use election.status', () => {
  const src = readFile('resources/js/Pages/Election/Show.vue');
  expect(src).not.toMatch(/election\.status/);
});

it('Viewboard.vue uses ElectionLifecycleStates constant not raw string', () => {
  const src = readFile('resources/js/Pages/Election/Viewboard.vue');
  expect(src).not.toContain("'voting_active'");
  expect(src).toContain('ElectionLifecycleStates');
});

it('Candidacy Apply.vue does not use election.status', () => {
  const src = readFile('resources/js/Pages/Election/Candidacy/Apply.vue');
  expect(src).not.toMatch(/election\.status/);
});

// Constitutional authority fragmentation
it('no Vue page derives capability from lifecycle constant + role combination', () => {
  const files = glob.sync('resources/js/Pages/**/*.vue');
  const violations = files.filter(f => {
    const src = readFile(f);
    // Detect: lifecycle constant used with && (role/condition combination)
    return /ElectionLifecycleStates\.[A-Z_]+.*&&/.test(src) ||
           /&&.*ElectionLifecycleStates\.[A-Z_]+/.test(src);
  });
  expect(violations).toEqual([]); // Management.vue already migrated — should pass
});

// capabilities_trace contamination
it('no component reads capabilities_trace', () => {
  const files = glob.sync('resources/js/Pages/**/*.vue');
  const violations = files.filter(f => readFile(f).includes('capabilities_trace'));
  expect(violations).toEqual([]);
});
```

Run: `npx vitest run tests/js/Architecture/ --reporter=verbose`  
**Expected: FAIL (red)**

---

## Step 3: Lifecycle Visualization Pattern

```typescript
// Import
import { ElectionLifecycleStates } from '@/Constants/ElectionLifecycleStates'

// BEFORE: :class="`esp-status--${election.status}`"
// AFTER:  :class="`esp-status--${election.state}`"

// BEFORE: v-if="election.state === 'results_published'"
// AFTER:  v-if="election.state === ElectionLifecycleStates.RESULTS_PUBLISHED"

// StatusBadge: pass election.state not election.status
// BEFORE: <StatusBadge :status="election.status" />
// AFTER:  <StatusBadge :status="election.state" />
```

After each file: `npx vitest run tests/js/Architecture/ --reporter=verbose`

---

## Step 5: Constitutional Authority Migration (PHP Test First)

```php
// Write PHP test BEFORE updating controller
public function test_page_receives_stateMachine_capabilities_prop(): void
{
    $response->assertInertia(fn ($page) => $page->has('stateMachine.capabilities'));
}
```

Run: `php artisan test tests/Feature/Election/TheSpecificTest.php --env=testing`  
Only after test passes: add `stateMachine` to controller render data.  
Only after controller updated: migrate Vue component to `useElectionCapabilities`.

---

## Step 6: Final Verification

```bash
# All Vitest tests (architecture + component + composable)
npx vitest run tests/js/ --reporter=verbose

# PHP regressions
php artisan test tests/Feature/Election/ tests/Architecture/ --env=testing --no-coverage

# Regression grep — must return zero
grep -rn "election\.status" resources/js/Pages/ --include="*.vue"
grep -rn "'voting_active'\|'results_published'" resources/js/Pages/ --include="*.vue"
grep -rn "capabilities_trace" resources/js/Pages/ --include="*.vue"
```

**Pass criteria:** All Vitest green, zero PHP regressions, zero legacy pattern matches.

---

## What Is NOT Changed

| Item | Reason |
|---|---|
| `Management.vue`, `StateMachinePanel.vue` | Already fully migrated ✅ |
| `Elections/Voters/Index.vue` voter suspension | Distinct voter-membership sub-system |
| `useElectionCapabilities.ts` internals | Extend only via explicit backend-first policy addition |
| `ElectionConstitution.php` | Backend sovereignty source — no changes |
| `electionUiMapper.js` | Legacy — leave until explicit ADR for removal |
| DB schema | No schema changes in this phase |

---

## Vocabulary Reference

| Legacy `election.status` | Canonical `ElectionLifecycleStates.*` |
|---|---|
| `'active'` | `VOTING_ACTIVE` |
| `'voting_open'` | `VOTING_ACTIVE` |
| `'published'` | `RESULTS_PUBLISHED` |
| `'archived'` | `ARCHIVED` |
| `'pending_approval'` | `SUBMITTED_FOR_APPROVAL` |
| `'approved'` | `APPROVED` |
