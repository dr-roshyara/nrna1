# Plan: Voter Source Strategy — Constitutional Snapshot & Organisation Creation Fix

**Plan ID:** snappy-foraging-codd  
**Branch:** postgressql  
**Scope:** Fix org creation bug + election constitutional snapshot + governance freeze point  
**Status:** Pre-implementation (TDD-first, --env=testing only)

---

## Context: The Problem

**User-reported bug:** `OrganisationController::store()` ignores `uses_full_membership` from the form. The form sends it; the controller discards it. Every new org defaults to Full Membership.

**Architectural defect exposed:** Elections derive voter-source strategy from `organisation->uses_full_membership` at runtime. If org policy changes after election creation, the election's voter eligibility is retroactively altered — a constitutional integrity violation.

**Correction**: Elections must own their own voter-source authority via a constitutional snapshot, created atomically at election creation time.

---

## Sovereignty Model

```
Organisation Governance Policy       ←  default, changes freely
         ↓  (at election creation, atomic)
Election Constitutional Snapshot     ←  sovereign authority, never retroactively changed
         ↓  (at eligibility resolution)
ElectionMode::fromElection($election) ←  always reads snapshot first
         ↓
VoterEligibilityPolicy               ←  enforcement
```

**The invariant:** Organisation governance policy changes after election creation have zero effect on that election's voter authority.

---

## 1. Participation Freeze Point Analysis

**Question:** When does voter identity become constitutionally relevant?

**Answer:** `SetupAdministration` — this is when voter import begins (per `ElectionLifecycleState` docblock: *"Governance infrastructure: posts, voters, chief"*).

| State | Voter Import Possible? | Strategy Mutable? |
|---|---|---|
| `Draft` | ❌ No | ✅ Yes |
| `SubmittedForApproval` | ❌ No | ✅ Yes |
| `Approved` | ❌ No | ✅ Yes |
| `Rejected` | ❌ No | ✅ Yes |
| **`SetupAdministration`** | **✅ Yes (import starts here)** | **❌ FROZEN** |
| `SetupNomination` | ✅ Active | ❌ Frozen |
| `ReadyForVoting` | ✅ Active | ❌ Frozen |
| `VotingActive` | ✅ Active | ❌ Frozen |
| `Counting` and beyond | ✅ Historical | ❌ Frozen |
| `Suspended` | ✅ Operational overlay | ❌ Frozen |

**Semantic method to add to `ElectionLifecycleSnapshot`:**

```php
// Answers: "Is the voter-source strategy constitutionally frozen for this election?"
// True once voter import can begin (SetupAdministration and beyond)
public function isParticipationLocked(): bool
{
    return match($this->state) {
        ElectionLifecycleState::Draft,
        ElectionLifecycleState::SubmittedForApproval,
        ElectionLifecycleState::Approved,
        ElectionLifecycleState::Rejected => false,
        default => true, // SetupAdministration and beyond
    };
}
```

This method replaces all raw `->where('state', 'voting_active')` governance checks.

---

## 2. Constitutional Snapshot Classification

The `voter_source_strategy` column on `elections` is a **constitutional snapshot fact**:
- Established **atomically** at election creation (part of the `create()` array — no post-creation update)
- Represents the election's own governance authority over voter sourcing
- Read via `ElectionMode::fromElection($election)` — snapshot is sovereign, org setting is irrelevant
- Mutable only while strategy is NOT participation-locked (before SetupAdministration)

---

## 3. Aggregate Ownership

Voter source strategy belongs to the `Election` aggregate as a constitutional snapshot property.

It does NOT belong to:
- `ElectionConstitution.php` — that is a static transition-rule registry (different bounded concern)
- Organisation runtime — org holds governance default only

It does NOT require:
- A new `ParticipationRuntime` service class (premature — no duplication, no orchestration complexity justifies extraction yet)
- New service providers or binding infrastructure

The **existing** `VoterEligibilityPolicy` + `ElectionMode::fromElection()` is sufficient.

---

## 4. Ubiquitous Language Analysis (No Rename Yet)

Current: `ElectionMode` with values `FullMembership` / `ElectionOnly`

**Problem:** "ElectionMode" implies the election has two modes. The actual domain concept is *where voters derive from*, not what mode the election is in.

**Better domain terms (for Phase 3 consideration):**
- `VoterSourceStrategy` — clearest DDD term
- `VoterAuthoritySource` — emphasizes constitutional authority
- `ParticipationAuthorityStrategy` — most formal

**Plan:** Do NOT rename now. Continue using `ElectionMode`. Document `VoterSourceStrategy` as the Phase 3 ubiquitous-language alignment target.

---

## 5. Projection Vocabulary Migration Direction

**Current leak:** `VoterImportController` (lines 30, 45) passes `uses_full_membership: true/false` to frontend. This exposes anti-corruption layer vocabulary in projection runtime.

**Target vocabulary:** Expose `voter_source_strategy` (the constitutional value) to frontend.

**This plan:** Change the Inertia prop from org boolean to election snapshot value. Keep key name compatible for now:
```php
// Before:
'uses_full_membership' => $organisation->uses_full_membership ?? true,

// After (election snapshot is authority):
'uses_full_membership' => ElectionMode::fromElection($election)->isFullMembership(),
```

**Phase 3:** Rename prop to `voter_source_strategy` and update frontend components.

---

## 6. Runtime Convergence Criteria & Drift Detection

### Migration Phases

| Phase | Runtime Authority | Verification |
|---|---|---|
| **1 (current)** | `organisation->uses_full_membership` | grep: all consumers read org boolean |
| **2 (this plan)** | Election snapshot preferred; org as legacy fallback | `fromElection()` prefers snapshot |
| **3 (future)** | Election snapshot only; fallback removed | `voter_source_strategy IS NOT NULL` for all elections |
| **4 (future)** | `uses_full_membership` column deprecated | Column dropped after migration |

### Drift Detection

During Phase 2, `organisation.uses_full_membership` and `election.voter_source_strategy` legitimately diverge (that is the constitutional goal). This is NOT an error.

**Architecture-level audit:** Any code path that reads `organisation->uses_full_membership` inside an **election context** (within an election-scoped controller or service) is a convergence violation. Document and resolve in Phase 3.

**Grep audit command** (run before Phase 3):
```bash
grep -rn "uses_full_membership" app/Http/Controllers/Election/ --include="*.php"
# Expected after Phase 2: VoterImportController should show 0 results
```

---

## 7. Capability Runtime Impact (Current State)

| Capability | Mode-Gated Today? | Location |
|---|---|---|
| Voting eligibility | ✅ Yes | `VoterEligibilityPolicy` |
| Voter import CSV format | ✅ Yes | `VoterImportService:35` |
| Membership fees access | ✅ Yes | `MembershipFeeController:112` |
| **Nominations** | ❌ No | Future constitutional extension |
| **Candidacy** | ❌ No | Future constitutional extension |
| Observer permissions | ❌ No | Future |

**Current state is acceptable.** Nominations/candidacy gating by voter-source strategy is a Phase 3+ constitutional extension — out of scope for this plan.

---

## 8. TDD-FIRST Test Strategy (--env=testing ONLY)

All tests: `RefreshDatabase` + `--env=testing`. PostgreSQL `nrna_test`. Never main DB.

### Group A — Organisation Creation Bug [Step 1]
**File:** `tests/Feature/Organisation/OrganisationCreationMembershipTest.php`

```
test_creating_org_with_election_only_mode_persists_voter_source_strategy
  → POST /organisations with uses_full_membership=false
  → assertDatabaseHas('organisations', ['uses_full_membership' => false])

test_creating_org_with_full_membership_mode_persists_voter_source_strategy
  → POST with uses_full_membership=true
  → assertDatabaseHas('organisations', ['uses_full_membership' => true])

test_organisation_defaults_to_full_membership_when_mode_omitted
  → POST without uses_full_membership key
  → assertDatabaseHas('organisations', ['uses_full_membership' => true])

test_creator_becomes_owner_regardless_of_voter_source_mode
  → POST with uses_full_membership=false
  → assertDatabaseHas('user_organisation_roles', ['role' => 'owner'])
```

### Group B — Election Constitutional Snapshot [Step 3–4]
**File:** `tests/Feature/Election/VoterStrategySnapshotTest.php`

```
test_election_creation_atomically_snaps_voter_source_from_election_only_org
  → org uses_full_membership=false → create election
  → assertDatabaseHas('elections', ['voter_source_strategy' => 'election_only'])

test_election_creation_atomically_snaps_voter_source_from_full_membership_org
  → org uses_full_membership=true → create election
  → assertDatabaseHas('elections', ['voter_source_strategy' => 'full_membership'])

test_org_strategy_change_after_election_creation_does_not_alter_election_snapshot
  → org=election_only → create election → org changes to full_membership
  → assertDatabaseHas('elections', ['voter_source_strategy' => 'election_only'])

test_election_mode_from_election_uses_snapshot_when_present
  → election has voter_source_strategy='election_only', org has uses_full_membership=true
  → ElectionMode::fromElection($election) === ElectionMode::ElectionOnly

test_election_mode_from_election_falls_back_to_org_when_snapshot_null
  → election has voter_source_strategy=null, org has uses_full_membership=true
  → ElectionMode::fromElection($election) === ElectionMode::FullMembership
```

### Group C — Participation Freeze + Org Guard [Step 5]
**File:** `tests/Feature/Governance/ParticipationFreezeTest.php`

```
test_org_strategy_change_blocked_when_election_is_in_setup_administration
  → election in SetupAdministration (ElectionScenarioFactory::setupAdministration())
  → PATCH org settings membership-mode → assertSessionHasErrors

test_org_strategy_change_blocked_when_election_is_voting_active
  → election in VotingActive → PATCH org settings → assertSessionHasErrors

test_org_strategy_change_allowed_when_elections_are_in_draft
  → election in Draft state → PATCH org settings → assertRedirect (success)

test_org_strategy_change_allowed_when_no_elections_exist
  → no elections → PATCH org settings → assertRedirect (success)

test_org_strategy_change_allowed_after_election_archived
  → Archived election → PATCH org settings → assertRedirect (success)

test_is_participation_locked_returns_false_for_pre_setup_states
  → snapshot with state=Draft → isParticipationLocked() === false
  → snapshot with state=Approved → isParticipationLocked() === false

test_is_participation_locked_returns_true_from_setup_administration
  → snapshot with state=SetupAdministration → isParticipationLocked() === true
  → snapshot with state=VotingActive → isParticipationLocked() === true
```

### Group D — Migration Integrity [Step 4]
**File:** `tests/Feature/Election/VoterStrategyMigrationIntegrityTest.php`

```
test_legacy_org_true_maps_correctly_via_from_organisation
  → ElectionMode::fromOrganisation(org with uses_full_membership=true) === FullMembership

test_legacy_org_false_maps_correctly_via_from_organisation
  → ElectionMode::fromOrganisation(org with uses_full_membership=false) === ElectionOnly

test_legacy_election_null_snapshot_falls_back_to_org
  → Election with voter_source_strategy=null
  → ElectionMode::fromElection($election) uses org mode (fallback path)
```

### Group E — Runtime Architecture Invariants [Step 6]
**File:** `tests/Architecture/GovernanceRuntime/VoterStrategyInvariantTest.php`

```
test_canvote_takes_no_user_parameter
  → reflection: ElectionLifecycleSnapshot::canVote has no parameters
  → Confirms lifecycle gate carries zero voter identity

test_snapshot_created_atomically_not_via_post_update
  → Create election → immediately read from DB (no second update)
  → voter_source_strategy is already set (not null) after create()

test_snapshot_survives_direct_org_mutation
  → Create election (snapshot='election_only')
  → DB::table('organisations')->update(['uses_full_membership' => true])
  → ElectionMode::fromElection($election->fresh()) still === ElectionOnly

test_voter_import_controller_uses_election_snapshot_not_org_mode
  → org=full_membership, election snapshot='election_only'
  → GET /organisations/{org}/elections/{election}/voters/import
  → assertInertia prop 'uses_full_membership' === false (from election snapshot)

test_no_election_scoped_controller_reads_org_boolean_directly
  → architecture guard: grep for org->uses_full_membership in Election controllers
  → result count = 0 (after Step 5 fix)
```

---

## 9. Implementation Sequence (TDD-first, ordered)

### Step 1: Fix Organisation Creation Bug

**Write Group A tests → confirm ALL FAIL → implement → confirm ALL PASS**

**File:** `app/Http/Controllers/OrganisationController.php:275`

```diff
// In validation (add after 'logo' validation):
+ 'uses_full_membership' => 'nullable|boolean',

// In Organisation::create() array (line 304–312):
  'logo'           => $logoPath,
+ 'uses_full_membership' => $request->boolean('uses_full_membership', true),
```

Two lines. Nothing else in this step.

---

### Step 2: Migration — Add `voter_source_strategy` Column

**New file:** `database/migrations/[YYYY_MM_DD]_000001_add_voter_source_strategy_to_elections.php`

```php
Schema::table('elections', function (Blueprint $table) {
    $table->string('voter_source_strategy', 30)
          ->nullable()  // null = legacy election (Phase 2 fallback applies)
          ->after('type');
});
```

Add to `app/Models/Election.php`:
- `'voter_source_strategy'` to `$fillable`
- `'voter_source_strategy' => 'string'` to `$casts`

---

### Step 3: Add `ElectionMode::fromElection()` 

**File:** `app/Domain/Election/Enum/ElectionMode.php`

```php
/**
 * Resolve election's authoritative voter-source strategy.
 * Election snapshot is sovereign runtime authority.
 *
 * @deprecated fallback branch — remove after Phase 3 backfill
 * @see https://[your-docs]/voter-source-strategy-migration
 */
public static function fromElection(\App\Models\Election $election): self
{
    if ($election->voter_source_strategy !== null) {
        return self::from($election->voter_source_strategy);
    }
    // @deprecated Phase 2 compatibility: pre-snapshot elections only
    // Remove once: SELECT COUNT(*) FROM elections WHERE voter_source_strategy IS NULL = 0
    return self::fromOrganisation($election->organisation);
}
```

---

### Step 4: Atomic Snapshot at Election Creation

**Write Group B + D tests → confirm FAIL → implement → confirm PASS**

**File:** `app/Http/Controllers/Election/ElectionManagementController.php:147`

Add `voter_source_strategy` directly inside the `Election::create([...])` array (line 147–167):

```diff
  $election = Election::create([
      'id'              => (string) Str::uuid(),
      'organisation_id' => $organisation->id,
      'name'            => $validated['name'],
+     'voter_source_strategy' => ElectionMode::fromOrganisation($organisation)->value,
      // ... rest of fields
  ]);
```

**No post-creation `update()`.** The snapshot is established atomically as part of the create transaction.

---

### Step 5: Add `isParticipationLocked()` + Expand Org Guard

**Write Group C tests → confirm FAIL → implement → confirm PASS**

**File:** `app/Domain/Election/ValueObjects/ElectionLifecycleSnapshot.php`

Add after `isActive()`:

```php
public function isParticipationLocked(): bool
{
    return match($this->state) {
        ElectionLifecycleState::Draft,
        ElectionLifecycleState::SubmittedForApproval,
        ElectionLifecycleState::Approved,
        ElectionLifecycleState::Rejected => false,
        default => true,
    };
}
```

**File:** `app/Http/Controllers/OrganisationSettingsController.php:121`

In `validateModeChangeConstraints()`, add as FIRST check (before pending-fees check):

```php
$lockedElectionExists = \App\Models\Election::withoutGlobalScopes()
    ->where('organisation_id', $organisation->id)
    ->whereNotIn('state', [
        \App\Domain\Election\Enum\ElectionLifecycleState::Draft->value,
        \App\Domain\Election\Enum\ElectionLifecycleState::SubmittedForApproval->value,
        \App\Domain\Election\Enum\ElectionLifecycleState::Approved->value,
        \App\Domain\Election\Enum\ElectionLifecycleState::Rejected->value,
        \App\Domain\Election\Enum\ElectionLifecycleState::Archived->value,
    ])
    ->exists();

if ($lockedElectionExists) {
    throw new \Illuminate\Validation\ValidationException(
        \Illuminate\Validation\Validator::make([], ['membership_mode' => 'required'], [
            'membership_mode.required' => __('organisations.errors.participation_locked_by_active_election'),
        ])
    );
}
```

Add translation key to `resources/lang/en/organisations.php`:
```php
'participation_locked_by_active_election' => 'Voter source strategy cannot be changed while an election is in setup, nomination, voting, or counting phase.',
```

---

### Step 6: Fix Projection Runtime (VoterImportController)

**File:** `app/Http/Controllers/Election/VoterImportController.php`

```diff
// create() line 30:
- 'uses_full_membership' => $organisation->uses_full_membership ?? true,
+ 'uses_full_membership' => ElectionMode::fromElection($election)->isFullMembership(),

// tutorial() line 45:
- 'uses_full_membership' => $organisation->uses_full_membership ?? true,
+ 'uses_full_membership' => ElectionMode::fromElection($election)->isFullMembership(),

// publicTutorial() line 56 — no election context, keep as:
  'uses_full_membership' => true,
```

---

### Step 7: Architecture Invariant Tests

**Write Group E tests → confirm PASS (they document existing correctness + new invariants)**

Create `tests/Architecture/GovernanceRuntime/VoterStrategyInvariantTest.php`.

---

## Critical Files Summary

| File | Change | Step |
|---|---|---|
| `app/Http/Controllers/OrganisationController.php:275, 312` | +2 lines | 1 |
| `database/migrations/[date]_add_voter_source_strategy_to_elections.php` | New | 2 |
| `app/Models/Election.php` | +1 fillable, +1 cast | 2 |
| `app/Domain/Election/Enum/ElectionMode.php` | +1 static method | 3 |
| `app/Http/Controllers/Election/ElectionManagementController.php:152` | +1 field in create() | 4 |
| `app/Domain/Election/ValueObjects/ElectionLifecycleSnapshot.php` | +1 method | 5 |
| `app/Http/Controllers/OrganisationSettingsController.php:121` | +guard block | 5 |
| `resources/lang/en/organisations.php` | +1 key | 5 |
| `app/Http/Controllers/Election/VoterImportController.php:30, 45` | 2 line changes | 6 |

## What Is NOT Changed

| Item | Reason |
|---|---|
| `ElectionLifecycle::canVote()` | Already correct pure temporal gate |
| `ElectionConstitution.php` | Transition rules only — correct bounded concern |
| `EloquentVoterEligibilityQueryService` | Already handles both modes — no change needed |
| `ElectionMode` enum name/values | Correct for Phase 2 — Phase 3 rename to `VoterSourceStrategy` |
| `uses_full_membership` column | Phase 2 anti-corruption layer — retained |
| `ParticipationRuntime` class | NOT introduced — no duplication or complexity justifies it yet |
| Backfill artisan command | Phase 3 concern |
| Nominations/candidacy gating | Future constitutional extension |

---

## Verification

```bash
# Step 1 verification
php artisan test tests/Feature/Organisation/OrganisationCreationMembershipTest.php --env=testing

# Step 3-4 verification
php artisan test tests/Feature/Election/VoterStrategySnapshotTest.php --env=testing
php artisan test tests/Feature/Election/VoterStrategyMigrationIntegrityTest.php --env=testing

# Step 5 verification
php artisan test tests/Feature/Governance/ParticipationFreezeTest.php --env=testing

# Step 7 architecture invariants
php artisan test tests/Architecture/GovernanceRuntime/ --env=testing

# Regression suite
php artisan test tests/Feature/Organisation/ --env=testing
php artisan test tests/Feature/Election/VoterEligibilityTest.php --env=testing
php artisan test --env=testing
```

**Manual verification:**
1. Create org with "Election-Only" → DB: `organisations.uses_full_membership = false`
2. Create election for that org → DB: `elections.voter_source_strategy = 'election_only'`
3. Change org to Full Membership → election snapshot unchanged
4. Voter import page → shows election-only format (not org's current setting)
5. Advance election to SetupAdministration → try org settings change → blocked
6. After election archived → org settings change succeeds

---

## Phase 2 Completion Status

✅ **COMPLETE** — All steps implemented, all 24 tests passing (Groups A-E)

**Commits:**
- `744c7e2a6` — Phase 1: Organization creation bug fix (4 tests)
- `ebf3d1a5a` — Phase 2: Constitutional snapshot & participation freeze (20 tests)

**Key Achievement:** Election voter authority is now snapshotted constitutionally instead of dynamically inherited from organisation runtime state.

---

## Phase 3+ Strategic Direction

**Principle:** Architectural evolution must follow the sequence:
```
stabilize → purify → formalize → federate
```

### Phase 3 (Consolidation & Purification)

**DO NOT expand yet.** Complete foundational purification first.

**Priority 1 — Snapshot Backfill (CRITICAL)**
- Backfill all existing elections with voter_source_strategy values
- Make fallback path unreachable
- Success: all elections have non-null voter_source_strategy

**Priority 2 — Remove Legacy Fallback**
- Delete ElectionMode::fromOrganisation() fallback branch
- Make voter_source_strategy NOT NULL in database
- Enforce: election snapshot is SOLE authority

**Priority 3 — Vocabulary Convergence**
- ElectionMode → VoterSourceStrategy (eventual rename)
- Remove uses_full_membership from election contexts
- Keep org.uses_full_membership as governance default only

**Priority 4 — Runtime Audit & Invariant Hardening**
- No election runtime may read org.uses_full_membership
- No projection may derive authority from org settings
- No controller may bypass snapshot resolution
- Encode as architecture tests

**Priority 5 — Overlay Runtime Formalization (Lightweight)**
- Document suspension authority semantics
- Clarify emergency governance behavior
- Clarify whether overlays preserve freeze state

**Why Phase 3 is consolidation, NOT expansion:**
- Sovereignty just established (fallback still exists = transitional)
- Vocabulary still mixed
- Invariants documented but not enforced
- Architecture not ready for federation yet

### Phase 4 (Participation Authority Modeling)

Start ONLY after Phase 3 is complete.

**Topics:**
- Voter authority value objects
- Hybrid participation semantics
- Delegation authority
- Capability coupling

### Phase 5 (Federation & External Registries)

Start ONLY after Phase 4 is complete.

**Topics:**
- External voter authorities
- Federated identity
- Delegated participation authority
- Cross-tenant election participation

---

## Most Important Rule

**DO NOT federate before purifying.**  
**DO NOT expand before stabilizing.**  
**DO NOT formalize before converging.**

This discipline prevents governance-runtime instability.

See `memory/phase3_consolidation_strategy.md` for detailed roadmap.
