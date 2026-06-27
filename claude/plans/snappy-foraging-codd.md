# Plan: Phase C.2.4 — Frontend Constitutional Runtime Projection Stabilization

**Plan ID:** snappy-foraging-codd  
**Branch:** postgressql  
**Scope:** Migrate remaining Vue components from legacy vocabulary to constitutional runtime projection  
**Status:** 90% complete — one component remaining  
**Supersedes:** Phase 3 Consolidation (complete)

---

## Current Completion Status (as of 2026-05-25)

### ✅ Already Done
- `StateMachineContract.ts` created and in use
- Architecture tests written (7/7 passing, 3 acceptable false-positive failures)
- Lifecycle visualization: 7 Vue components migrated to `ElectionLifecycleStates` constants
  - `Show.vue`, `Viewboard.vue`, `Apply.vue`, `Organisations/Elections/Index.vue`, `Organisations/Show.vue`, `Commission/Dashboard.vue`, `Organisations/Posts.vue`

### 🔴 Remaining: ElectionDashboard.vue flat prop vocabulary

**File:** `resources/js/Pages/Dashboard/ElectionDashboard.vue`  
**Architecture tests failing:**
- `Dashboard ElectionDashboard.vue does not use can_vote_now prop`
- `Dashboard ElectionDashboard.vue does not use can_access prop`

**Context:** This component is currently **orphaned** — not rendered by any active controller. The modern path uses `Election/Show.vue` via `ElectionVotingController`. However, architecture tests still enforce constitutional vocabulary on it.

---

## Phase C.2.4 Completion Plan (REVISED — DDD-CORRECTED)

**Revision rationale:** Original plan proposed renaming `can_access` → `isEligible` and `can_vote_now` → `hasActiveSession`. DDD critique correctly identified that this fixes the vocabulary but preserves the architectural problem: a second authority path exists. Dead code should be deleted, not refactored. Renaming gives false confidence while the violation remains structurally.

---

### Decision: Delete `ElectionDashboard.vue`

`resources/js/Pages/Dashboard/ElectionDashboard.vue` is **confirmed dead code**:
- Not rendered by any controller (`grep -r 'Dashboard/ElectionDashboard' app/` returns no matches)
- Not referenced in any route file
- Only referenced in `resources/js/i18n.js` (locale imports) and old `.vue.txt` cache files

**Why delete instead of rename:**
- Dead code that passes architecture tests gives false confidence — the architectural violation remains
- Re-activating the component in the future would reintroduce a second authority path alongside `useElectionCapabilities()`
- `ballotAccess` and `votingStatus` props duplicate the resolver's authority derivation
- The frontend should only receive voter facts and resolver-computed capabilities, not raw eligibility predicates

**Domain boundary clarification:**

| Data category | Example | Source | Pattern |
|---|---|---|---|
| Lifecycle visualization | `election.state` | Election model | `ElectionLifecycleStates.*` constant |
| Constitutional authority | `canVote`, `canEdit` | `ElectionCapabilityResolver` | `useElectionCapabilities()` composable |
| Voter facts | `hasVoted`, `votedAt` | Query side (not resolver) | Plain props, named as **facts** (nouns/timestamps) |

`ballotAccess.can_access` and `votingStatus.can_vote_now` are **authority predicates disguised as voter facts**. They belong in the resolver, not as separate props. Deletion removes the temptation to re-derive authority from them.

---

### Step 1: Delete the orphaned component

```
DELETE: resources/js/Pages/Dashboard/ElectionDashboard.vue
```

Also remove locale imports in `resources/js/i18n.js` that reference the deleted component (lines ~69-71):
```javascript
// DELETE these 3 lines:
import electionDashboardDe from './locales/pages/Dashboard/ElectionDashboard/de.json';
import electionDashboardEn from './locales/pages/Dashboard/ElectionDashboard/en.json';
import electionDashboardNp from './locales/pages/Dashboard/ElectionDashboard/np.json';
```

Also remove the locale registrations in the i18n messages object where these imports are used.

---

### Step 2: Update architecture tests

The current tests check that the file's contents don't contain `can_vote_now`/`can_access`. Once the file is deleted, these tests will throw file-not-found errors (failing instead of passing).

**Replace** the two failing test cases in `tests/js/Architecture/ElectionFrontendArchitectureTest.spec.ts`:

```typescript
// REPLACE these two tests:
it('Dashboard ElectionDashboard.vue does not use can_vote_now prop', () => {
  const src = readFile('Pages/Dashboard/ElectionDashboard.vue')
  expect(src).not.toContain('can_vote_now')
})

it('Dashboard ElectionDashboard.vue does not use can_access prop', () => {
  const src = readFile('Pages/Dashboard/ElectionDashboard.vue')
  expect(src).not.toContain('can_access')
})

// WITH this single stronger test:
it('Dashboard/ElectionDashboard.vue does not exist (deleted dead code — re-entry forbidden)', () => {
  const { existsSync } = require('fs')
  const { resolve } = require('path')
  const filePath = resolve(__dirname, '../../../resources/js/Pages/Dashboard/ElectionDashboard.vue')
  expect(existsSync(filePath)).toBe(false)
})
```

This is a **stronger** enforcement: it proves the dead code was deleted AND prevents it from ever returning.

---

### Step 3: Clean up backend `determineBallotAccess()`

`ElectionManagementController::determineBallotAccess()` (lines ~302-361) still builds arrays with `can_access`. Since the component that consumed this data is deleted, check whether this private method is called anywhere else:

```bash
grep -rn "determineBallotAccess\|ballotAccess" app/Http/Controllers/Election/ElectionManagementController.php
```

If `determineBallotAccess()` is only called from within this controller and never flows to any active page, mark it `@deprecated` or remove it. Do NOT rename `can_access` — the method itself should be deprecated/removed since its data consumer is gone.

**If `ballotAccess` is passed to any still-active Inertia render,** that call site must be identified and its data contract migrated separately (not in this phase).

---

### Verification

```bash
# 1. Architecture tests — all should be GREEN
npx vitest run tests/js/Architecture/ElectionFrontendArchitectureTest.spec.ts --reporter=verbose

# 2. Verify file is gone
ls resources/js/Pages/Dashboard/ElectionDashboard.vue
# Expected: No such file or directory

# 3. Verify no remaining can_access/can_vote_now violations in Pages
grep -rn "can_access\|can_vote_now" resources/js/Pages/ --include="*.vue"
# Expected: No matches (or only in comments)

# 4. PHP tests — verify no regressions
php artisan test tests/Feature/Election/ --env=testing --no-coverage
```

### Pass criteria
- Architecture test: `Dashboard/ElectionDashboard.vue does not exist` → GREEN
- All previously passing architecture tests remain GREEN
- Zero PHP test regressions
- `grep -rn "can_access\|can_vote_now" resources/js/Pages/` returns zero matches

---

## Voter Fact Projection Pattern (For Future Reference)

When voter-specific facts must flow to Vue components, they are **read-only domain facts**, not authority predicates.

**Use timestamps, not boolean predicates:**

```typescript
// ✅ CORRECT: timestamp is an unambiguous fact
interface VoterFacts {
  votedAt: string | null    // ISO8601 or null — client derives "hasVoted" if needed
}
// NOT:
// hasVoted: boolean  — boolean predicate risks being misinterpreted as authority

// ✅ CORRECT: authority from resolver only
const { canDo } = useElectionCapabilities(computed(() => props.stateMachine))
const voterCanCastBallot = canDo(ElectionActions.CAST_VOTE)  // NOT from VoterFacts

// ❌ FORBIDDEN: eligibility predicate as prop
interface BallotAccess {
  can_access: boolean    // Authority predicate — belongs in resolver
  isEligible: boolean   // Still authority predicate — same violation, different name
}
```

**Why timestamp > boolean:** A frontend developer seeing `hasVoted: true` might incorrectly derive `canVote = false` instead of checking the resolver's `capabilities.cast_vote.allowed`. A timestamp forces the client to interpret, not assume.

Facts are named as nouns/timestamps. Authority decisions come only from `useElectionCapabilities()`.

---

## Architectural Refinements (DDD Review — Required Before C.2.5+)

These do not block Phase C.2.4 completion but MUST be addressed before Phase C.2.5 expansion.

---

### Refinement 1: Decompose `StateMachineContract` — Prevent Frontend Constitutional Monolith

`StateMachineContract` is accumulating: lifecycle runtime, authority snapshots, metadata, telemetry, debugging, and federation precursor semantics. This risks **frontend constitutional protocol inflation** — everything collapses into one type, making decomposition harder over time.

**Required conceptual decomposition (implementation may remain transitional):**

```typescript
// Future target: explicit concern separation
interface ConstitutionalRuntimeProjection {
  lifecycle: LifecycleProjection          // visualization only (state, completedStates)
  authority: CapabilityProjection         // resolver-derived permission snapshot
  metadata: ProjectionIntegrityMetadata   // hash, version, timestamp
}

// DebugProjection: completely external — NEVER inside ConstitutionalRuntimeProjection
// Use debug-only endpoint, feature-flagged tooling, or isolated diagnostics transport
```

**Migration path:** `StateMachineContract` remains in use transitionally. Its internal fields must map cleanly to one of the four concerns above. No new fields may be added without identifying which concern they belong to.

---

### Refinement 2: Remove `capabilities_trace` From `StateMachineContract` Entirely

**Current status:** Field exists in type with `@deprecated` or "forbidden in production" note.  
**Why this is insufficient:** Production components can accidentally read it, tests can depend on it, telemetry can serialize it, creating shadow constitutional semantics.

**Required action:**
- Remove `capabilities_trace?` from `StateMachineContract` type definition
- Use a debug-only endpoint, feature-flag tooling, or isolated transport instead
- If capabilities_trace data is needed during development, it must travel via a completely separate channel — never mixed into the production projection contract

```typescript
// BEFORE (dangerous):
interface StateMachineContract {
  capabilities_trace?: unknown[] | null  // ← remove entirely
}

// AFTER (correct):
// capabilities_trace is not part of any production type
// It exists only in debug tooling, never in pages/ components
```

---

### Refinement 3: `constitution_hash` — Narrow the Frontend's Authority

**Current risk:** The statement "if hash mismatches, capabilities cannot be trusted" implies frontend is making a sovereignty determination.

**Required precise definition:**
- `constitution_hash` detects **stale projection lineage** — it does NOT determine authority validity
- A mismatch means: snapshot was derived from an older constitution than the server currently uses
- Frontend behavior on mismatch: request fresh projection from server, show stale-warning to admin user
- Frontend behavior is NEVER: reinterpret, invalidate, or supplement authority based on hash state

**Boundary statement (non-negotiable):**
```
Frontend may: detect projection staleness via constitution_hash.
Frontend may NOT: determine constitutional validity, reinterpret authority, or bypass the resolver.
```

---

### Refinement 4: `resolver_version` — Remove Over-Ceremonial Internal Governance

`resolver_version` governs an **internal Inertia transitional boundary**, not a public API or federation contract. Applying semver ADR ceremony to internal runtime evolution adds overhead without proportional benefit.

**Simplified governance (replace current table):**
| Boundary | Governance discipline |
|---|---|
| Internal Inertia runtime | Compatibility: no silent breaking changes; document in CHANGELOG |
| Public REST API | Semver: v1, v2 — breaking changes require major version bump |
| Federation protocol | Treaty governance: partner-negotiated, externally versioned |

Do NOT require ADRs, formal deprecation windows, or public version announcements for internal runtime version changes.

---

### Refinement 5: Add `ProjectionConsistencyBoundary` Semantics

The plan forbids stale `localStorage` snapshots but does not define behavior for concurrent state changes. This gap becomes dangerous under overlays, suspension, and emergency freezes.

**Required rules (conceptual — no implementation needed now):**

| Situation | Required behavior |
|---|---|
| Admin suspends election mid-session | Invalidate snapshot immediately; voter sees stale-warning |
| Overlay activates while user is on action page | Force capability refresh before rendering action buttons |
| Stale-tab voter submits an action | Backend re-verifies snapshot at server; never trust client-side authority |
| Long-lived tab (>10 min idle) | Periodic freshness check before rendering action buttons |
| Replayed action from expired session | Authority recomputed server-side; stale projection is irrelevant |

**Key principle:** Projection freshness is detected client-side. Authority is always verified server-side before any mutation.

---

### Refinement 6: `useElectionCapabilities()` — Formal Composable Invariants

The composable must be governed by formal invariants, not just convention.

**Non-negotiable composable law:**
```
The composable MAY:  expose, normalize, safely read, ergonomically wrap capabilities.
The composable MUST NOT: derive, infer, reinterpret, elevate, combine, cache, or synthesize authority.
```

**God-composable trigger rules:** If the composable grows to include any of the following, it has become governance middleware and must be decomposed:
- Lifecycle reasoning (state machine logic)
- Overlay handling
- Role transforms
- Federation logic
- Audit event emission
- Denial text formatting beyond tooltip-key lookup

---

### Refinement 7: Add `ProjectionHydrationRules` (Conceptual — No Implementation Yet)

The architecture now distributes constitutional-runtime projections. SSR/hydration introduces new stale-projection risks that must be acknowledged architecturally.

**Required conceptual boundary statements:**
- Hydration MUST NOT reuse stale snapshots from server-render time
- Lazy-loaded components requesting authority MUST re-request from current props, not from hydrated snapshot
- Partial renders MUST NOT proceed with partial authority data
- After hydration, capabilities must be considered stale until first client-side prop reconciliation completes

No implementation required now. Architecture must acknowledge the projection lifecycle for future SSR work.

---

### Refinement 8: Explicit Projection Ownership Boundaries

**Non-negotiable layer ownership table:**
| Layer | Ownership | Forbidden |
|---|---|---|
| `ElectionCapabilityResolver` | Constitutional authority semantics | Any rendering or transport concern |
| Projection assemblers (controllers/resources) | Transport shape — what gets serialized | Authority derivation or interpretation |
| Controllers | Orchestration — route to page, pass projections | Interpreting projection semantics |
| Vue components | Rendering only — read capabilities, show UI | Deriving authority, augmenting snapshots |

**Enforcement:** If projection semantics (e.g., authority field definitions) appear in a controller, the controller is doing projection assembly work. Extract to a projection assembler.

---

### Refinement 9: Strengthen Behavioral vs Grep Test Hierarchy Language

**Formal test hierarchy statement (non-negotiable):**
```
BEHAVIORAL TESTS (PHPUnit, Vitest component tests) → AUTHORITATIVE
  Prove that the system behaves constitutionally at runtime.
  A passing behavioral test PROVES constitutional correctness.

ARCHITECTURE GREP TESTS (Vitest grep/readFileSync) → SMOKE ALARMS ONLY
  Detect obvious, easily-visible violations.
  A passing grep test proves almost nothing about runtime behavior.
  An absent grep match does NOT mean the violation is absent.
```

**The critical danger of grep-test overconfidence:** Computed patterns, aliased constants, and runtime string construction all produce violations that grep tests cannot detect. Never use a passing grep test as evidence of constitutional correctness.

---

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
