# Plan: Phase 3 — Runtime Authority Purification & Sovereignty Convergence

**Plan ID:** phase3-runtime-purification  
**Branch:** postgressql  
**Status:** Revised v3 — Incorporating third 10-point architectural correction  
**Approach:** TDD-first. All tests written before implementation. `--env=testing` only.  
**Principle:** Stabilize → Purify → Formalize → Federate (Phase 3 = Purify)

---

## Strategic Principle (Correction #10)

Phase 3 is not fixing organisation settings. It is stabilizing **constitutional participation authority convergence**.

Every implementation decision must preserve:
- **Sovereignty** — election snapshot is sole runtime authority after SetupAdministration
- **Runtime convergence** — all code paths reading org boolean inside election context are eliminated
- **Semantic centralization** — `isParticipationLocked()` is the single source of freeze semantics; no duplication in controllers or queries
- **Overlay extensibility** — future overlay states (Suspended, Emergency) must not require changes to controller code; they inherit freeze semantics through the lifecycle runtime

---

## Phase Separation Reference (Correction #5)

| Work | Phase | Status |
|------|-------|--------|
| Fix org creation bug (`uses_full_membership` ignored) | Phase 1 | ✅ Complete |
| Constitutional snapshot introduction (`voter_source_strategy` column + atomic creation) | Phase 2 | ✅ Complete |
| Participation freeze semantics (`isParticipationLocked()`) | Phase 2 | ✅ Complete |
| Org change guard (lifecycle-runtime-based) | Phase 2 | ✅ Complete |
| Backfill command for pre-Phase-2 elections | Phase 3A | ⬜ Plan |
| Sovereignty violations purified (election-scoped code reading org boolean) | Phase 3A | ⬜ Plan |
| Fallback observability (structured warning in `fromElection()`) | Phase 3A | ⬜ Plan |
| Behavioral convergence tests | Phase 3A | ⬜ Plan |
| Fallback removal (`LogicException` on null) | Phase 3B | ⬜ Plan |
| NOT NULL migration | Phase 3B | ⬜ Plan |
| Vocabulary convergence (`ElectionMode` → `VoterSourceStrategy`) | Phase 4 | Future |
| Overlay/suspension formal semantics | Phase 4 | Future |
| Federation, external registries | Phase 5 | Future |

---

## What Phase 3 Must Accomplish

Phase 2 established constitutional snapshot sovereignty: elections own their voter-source authority. However, the runtime is still in a transitional state:

1. **Fallback path still exists** — `ElectionMode::fromElection()` falls back to org boolean if snapshot is null
2. **Sovereignty violations remain** — election-scoped code reads `organisation->uses_full_membership` directly
3. **Architecture invariant test is documentation-only** — does not programmatically enforce the rule
4. **voter_source_strategy is still nullable** — DB allows null despite Phase 2 requirement

Phase 3 has two sub-phases:

- **Phase 3A** — Transitional purification with observability: telemetry on fallback path, idempotent backfill, sovereignty violations fixed, behavioral (not string-scan) tests
- **Phase 3B** — Enforcement: fallback removed, LogicException on null, NOT NULL migration after verification

---

## Runtime Authority Sovereignty Model

```
Organisation Governance Policy (uses_full_membership)
    ↓  legitimate use: governance default for NEW election creation
    ↓  legitimate use: backfill command for PRE-PHASE-2 elections
    ↓  VIOLATION: any election-runtime code reading this directly

Election Constitutional Snapshot (voter_source_strategy)
    ↓  sovereign authority — established atomically at creation
    ↓  immutable after SetupAdministration
    ↓  read ONLY via ElectionMode::fromElection($election)
    ↓
VoterEligibilityPolicy / VoterImportService / ElectionVoterController
    ↓  must receive ElectionMode as parameter — never derive it internally
    ↓
Runtime enforcement
```

---

## Single-Source Freeze Semantics — `isParticipationLocked()` (Corrections #1, #2, #4)

**Rule:** The question "is voter participation constitutionally frozen for this election?" has exactly ONE answer location: `ElectionLifecycleSnapshot::isParticipationLocked()`.

No controller, no query, no migration may interpret lifecycle states directly. All freeze decisions route through this method.

**Current implementation (Phase 2):**
```php
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

**Phase 3A required change — Step 0 (see Implementation Sequence):** Replace `default => true` with explicit governance documentation. The method must communicate *why* each state is frozen. This is NOT optional documentation — it is a governance contract:

```php
public function isParticipationLocked(): bool
{
    return match($this->state) {
        // Pre-setup: voter identity not yet constitutionally relevant
        ElectionLifecycleState::Draft,
        ElectionLifecycleState::SubmittedForApproval,
        ElectionLifecycleState::Approved,
        ElectionLifecycleState::Rejected
            => false,

        // Archival: election is complete; unlock allows org policy to be updated
        // Note: Archived is unlocked because no further voter import is possible
        // and org governance policy may need to change for future elections
        ElectionLifecycleState::Archived
            => false,

        // Active lifecycle: voter import can begin or is in progress
        // Overlay states (Suspended, Emergency): preserve the freeze to maintain
        // constitutional consistency — overlays do not retroactively unlock strategy
        // Default covers: SetupAdministration, SetupNomination, ReadyForVoting,
        //   VotingActive, Counting, ResultsPublished, Suspended, and any future states
        default
            => true,
    };
}
```

**Why `default => true` with explicit Archived carve-out:**
- Archived elections are complete; org policy changes should be allowed for future elections
- Suspended/Emergency states are overlays — they preserve freeze (orthogonal concern)
- New lifecycle states added in future are frozen by default (safe default)

**Governance invariant:** The `isParticipationLocked()` method is the single authority. The Archived case being unlocked must be ONLY here, not duplicated in `whereNotIn()` arrays or controller logic.

---

## Organisation Settings Guard — Lifecycle-Runtime Pattern (Correction #3)

**Violation:** Using `->whereNotIn('state', [...])` in `OrganisationSettingsController` duplicates constitutional semantics outside the lifecycle runtime. It violates single-source freeze semantics and breaks overlay extensibility.

**Phase 2 correct implementation (already in place — verify it is this pattern):**

```php
$lockedElectionExists = Election::withoutGlobalScopes()
    ->where('organisation_id', $organisation->id)
    ->get()
    ->contains(fn($election) => ElectionLifecycle::of($election)->isParticipationLocked());
```

This is correct. It:
- Loads candidate elections broadly (infrastructure concern)
- Delegates constitutional freeze evaluation to lifecycle runtime (governance concern)
- Does NOT interpret state values in the controller

**Phase 3A required fix — Step 2E (see Implementation Sequence):** The actual `OrganisationSettingsController::validateModeChangeConstraints()` in the codebase contains `->whereNotIn('state', [...])`. This MUST be replaced with the lifecycle-runtime pattern as part of Phase 3A Step 2E. This is not a verification — it is a mandatory correction before Phase 3A is complete.

The `whereNotIn` implementation:
```php
// CURRENT (WRONG — duplicates governance semantics in controller):
->whereNotIn('state', [Draft, SubmittedForApproval, Approved, Rejected, Archived])
->exists()
```

The correct pattern:
```php
// REQUIRED (delegates to lifecycle runtime — single-source semantics):
->get()
->contains(fn($election) => ElectionLifecycle::of($election)->isParticipationLocked())
```

**Performance note:** The `->get()->contains()` approach loads all elections. For very large orgs this can be optimized later. Correctness first; optimize when the load is measured.

---

**Classification rule (correction #6):**
- `$org->uses_full_membership` in `ElectionManagementController::store()` → **governance-default derivation** (legitimate)
- `$org->uses_full_membership` in `VoterImportController::preview()` → **election-runtime violation** (must be purified)
- `ElectionMode::fromOrganisation()` in backfill command → **legitimate** (backfill uses org as source)
- `ElectionMode::fromOrganisation()` in `ElectionVoterController::store()` → **election-runtime violation**

---

## Sovereignty Violations to Purify (Correction #6 Classification)

| File | Line | Category | Action |
|------|------|----------|--------|
| `VoterImportController::preview()` | 86 | Election-runtime violation | Phase 3A fix |
| `VoterImportController::import()` | 118 | Election-runtime violation | Phase 3A fix |
| `ElectionVoterController::store()` | 125 | Election-runtime violation | Phase 3A fix |
| `ElectionVoterController::bulkStore()` | 156 | Election-runtime violation | Phase 3A fix |
| `VoterEligibilityService::isEligibleVoter()` | 35 | Election-runtime violation | Phase 3A fix (after call graph audit) |
| `VoterEligibilityService::unassignedEligibleQuery()` | 53 | Election-runtime violation | Phase 3A fix (after call graph audit) |
| `VoterImportService::downloadTemplate()` | 35 | Election-runtime violation | Phase 3A fix |
| `ElectionManagementController::store()` | — | Governance-default derivation | **KEEP — legitimate** |
| `BackfillVoterSourceStrategy` command | — | Governance-default derivation | **KEEP — legitimate** |

Also: `VoterStrategyInvariantTest.php` line 128 asserts `true` without checking behavior — must become a behavioral convergence test.

---

## Phase 3A — Transitional Purification

### Phase 3A Objective

Fix sovereignty violations. Add observability. Make fallback path visible (not silent). Do NOT yet remove the fallback path or make the column NOT NULL.

**Phase 3A success:** All violations fixed. Fallback path emits warning. Backfill command available and idempotent. Behavioral tests all green.

---

### Group A — Backfill Command Tests (Phase 3A)

**File:** `tests/Feature/Commands/BackfillVoterSourceStrategyTest.php`  
**Write FIRST. Confirm ALL FAIL. Implement. Confirm ALL PASS.**

```
test_audit_only_reports_elections_missing_snapshot
  → Create election with voter_source_strategy=null
  → Run with --audit-only → no DB write
  → assertDatabaseHas('elections', ['voter_source_strategy' => null]) (unchanged)

test_backfill_sets_election_only_from_election_only_org
  → org uses_full_membership=false, election voter_source_strategy=null
  → Run command → assertDatabaseHas('elections', ['voter_source_strategy' => 'election_only'])

test_backfill_sets_full_membership_from_full_membership_org
  → org uses_full_membership=true, election voter_source_strategy=null
  → Run command → assertDatabaseHas('elections', ['voter_source_strategy' => 'full_membership'])

test_backfill_skips_elections_already_having_snapshot
  → election voter_source_strategy='election_only'
  → Run command → DB unchanged, no update query issued

test_backfill_reports_count_of_updated_elections
  → 3 elections with null snapshot, 2 with non-null
  → Run command → output includes "3 elections backfilled"

[IDEMPOTENCY — correction #3]

test_backfill_is_idempotent_when_run_twice
  → 3 elections with null snapshot
  → Run command twice
  → After second run: same 3 elections have snapshot; no error; output says "0 elections backfilled"

test_backfill_resumes_correctly_after_partial_failure
  → 5 elections with null snapshot; simulate interrupt after 2 processed
  → Run command again (fresh)
  → Remaining 3 elections are filled; already-filled 2 are skipped
  → assertDatabaseHas for all 5 elections having non-null snapshot

test_audit_only_does_not_affect_idempotency
  → election voter_source_strategy='election_only'
  → Run command with --audit-only
  → assertDatabaseHas still shows election_only (audit-only is a no-op even on filled records)
```

---

### Group B — Sovereignty Violation Tests (Phase 3A)

**File:** `tests/Feature/Election/VoterStrategySovereigntyTest.php`  
**Write FIRST. Confirm ALL FAIL. Implement. Confirm ALL PASS.**

```
test_voter_import_preview_routes_to_election_only_via_snapshot
  → org uses_full_membership=true, election voter_source_strategy='election_only'
  → POST /preview → routes to previewElectionOnly() not full-membership path

test_voter_import_import_routes_to_election_only_via_snapshot
  → org uses_full_membership=true, election voter_source_strategy='election_only'
  → POST /import → routes to importElectionOnly() not full-membership path

test_election_voter_controller_store_uses_election_snapshot_mode
  → org uses_full_membership=true, election voter_source_strategy='election_only'
  → AssignVoterCommand receives ElectionMode::ElectionOnly

test_election_voter_controller_bulk_store_uses_election_snapshot_mode
  → same scenario, BulkAssignVotersCommand receives ElectionMode::ElectionOnly

test_voter_eligibility_service_respects_mode_parameter_not_org_boolean
  → Call isEligibleVoter($org, $user, ElectionMode::ElectionOnly) where org has uses_full_membership=true
  → Result is governed by ElectionMode::ElectionOnly, not org boolean
  [NOTE: Requires call graph audit before implementation — see Implementation Step 2C]

test_unassigned_eligible_query_respects_mode_parameter
  → Call unassignedEligibleQuery($org, [], ElectionMode::ElectionOnly) where org has uses_full_membership=true
  → Eligibility query uses ElectionOnly semantics, not org boolean
  [NOTE: Requires call graph audit before implementation]

test_voter_import_service_download_template_uses_election_snapshot
  → election voter_source_strategy='election_only', org uses_full_membership=true
  → downloadTemplate() returns election-only CSV headers (firstname;lastname;email)

test_org_boolean_change_does_not_alter_import_behavior_for_existing_election
  → election voter_source_strategy='election_only'; org changes to full_membership
  → downloadTemplate() still returns election-only headers (snapshot is authority)
```

---

### Group C — Fallback Observability Tests (Phase 3A)

**File:** `tests/Feature/Election/VoterStrategyFallbackObservabilityTest.php`  
**Write FIRST. Confirm ALL FAIL. Implement. Confirm ALL PASS.**

These tests verify that the Phase 3A fallback path is visible, not silent.

```
test_from_election_returns_snapshot_when_present
  → election voter_source_strategy='election_only'
  → ElectionMode::fromElection($election) === ElectionMode::ElectionOnly ✅

test_from_election_logs_warning_when_snapshot_is_null
  → election voter_source_strategy=null
  → ElectionMode::fromElection($election) returns a value (does NOT throw yet — Phase 3A)
  → Log::channel('...')->warning(...) was called
  → Warning message contains election ID and "voter_source_strategy is null"

test_from_election_falls_back_to_org_mode_when_snapshot_is_null
  → election voter_source_strategy=null, org uses_full_membership=true
  → ElectionMode::fromElection($election) === ElectionMode::FullMembership (fallback still works in 3A)

test_from_election_logs_warning_with_structured_data_when_fallback_used
  → election voter_source_strategy=null
  → Use Log::spy() — assert Log::warning() was called with keys: election_id, organisation_id, strategy_derived, message
  → The structured log IS the observable metric at this phase — no separate counter needed
```

---

### Group D — Behavioral Convergence Tests (Phase 3A — replaces string-scan)

**File:** `tests/Architecture/GovernanceRuntime/VoterStrategyConvergenceTest.php`  
**Write tests. Confirm they document runtime behavior, not file contents.**

Correction #2: Do NOT use `file_get_contents()` as primary enforcement. Use behavioral tests.

```
test_election_voter_import_uses_snapshot_not_org_setting_when_strategies_diverge
  → org uses_full_membership=true, election voter_source_strategy='election_only'
  → Drive the voter import preview through the actual request path
  → Assert that election-only behavior was exhibited (e.g., only non-member voters eligible)
  [This is behavioral — it catches violations without brittle string matching]

test_election_voter_controller_eligibility_uses_snapshot_not_org_setting
  → org uses_full_membership=true, election voter_source_strategy='election_only'
  → Drive voter assignment through the actual controller path
  → Assert that eligibility was evaluated using ElectionMode::ElectionOnly semantics

test_voter_eligibility_service_mode_parameter_governs_output
  → Call isEligibleVoter with explicit ElectionMode::FullMembership vs ElectionMode::ElectionOnly
  → Confirm results differ when org boolean and mode disagree
  [Documents that mode parameter is the governing authority, not org boolean]

test_governance_default_read_is_isolated_to_election_creation_and_backfill
  → Confirm that fromOrganisation() usage in election creation results in correct snapshot
  → Confirm that snapshot, once set, governs all subsequent reads independently
  [Documents the boundary: org governance default → snapshot → runtime authority]

[NO-NEW-NULL-SNAPSHOT INVARIANT — correction #8]

test_newly_created_elections_always_have_non_null_snapshot
  → Create election via ElectionManagementController::store() with valid org
  → assertDatabaseHas('elections', [...]) where voter_source_strategy IS NOT null
  → assertNotNull($election->voter_source_strategy)
  [This test verifies the Phase 2 atomic creation guarantee. Must remain green throughout Phase 3]

test_election_factory_produces_non_null_snapshot
  → Election::factory()->create(['organisation_id' => $org->id])
  → assertNotNull($election->voter_source_strategy)
  [Factory must default to setting snapshot — audits the factory definition]

test_is_participation_locked_uses_semantic_method_not_state_array
  → snapshot with state=SetupAdministration → isParticipationLocked() === true
  → snapshot with state=Suspended → isParticipationLocked() === true (overlay preserves freeze)
  → snapshot with state=Archived → isParticipationLocked() === false (archival unlocks)
  [Confirms the explicit Archived carve-out and overlay preservation — documents overlay semantics]
```

**Note on string-scan tests (correction #2):**  
String-scan tests (`assertStringNotContainsString` on file contents) are brittle — a rename or refactor breaks them without a corresponding governance violation. Reserve string-scan as a secondary guard only, after behavioral tests. If behavioral tests pass but string-scan fails, the behavioral test should be the authoritative signal.

---

## Phase 3B — Enforcement (After Phase 3A Verified)

**Prerequisite:** All elections backfilled (`SELECT COUNT(*) FROM elections WHERE voter_source_strategy IS NULL = 0`).  
**Prerequisite:** Phase 3A behavioral tests all green for 2+ deployment cycles.  
**Prerequisite:** Fallback warning has not fired in observability logs (confirms backfill complete).

### Group E — Fallback Removal Tests (Phase 3B)

**File:** `tests/Feature/Election/VoterStrategyFallbackRemovalTest.php`  
**Write FIRST. Confirm ALL FAIL. Implement. Confirm ALL PASS.**

```
test_from_election_returns_snapshot_when_present
  → election voter_source_strategy='election_only'
  → ElectionMode::fromElection($election) === ElectionMode::ElectionOnly ✅ (unchanged from 3A)

test_from_election_throws_when_snapshot_is_null
  → election voter_source_strategy=null
  → ElectionMode::fromElection($election) throws LogicException
  → message contains: "voter_source_strategy is null — run backfill:voter-source-strategy"
  [This test FAILS in 3A — it is the signal that 3B has begun]

test_voter_source_strategy_column_is_not_nullable
  → Attempt Election::create([..., 'voter_source_strategy' => null])
  → Throws QueryException (NOT NULL constraint violation)
  [This test requires the NOT NULL migration to be applied]
```

---

## Implementation Sequence

---

### Phase 3A — Step 0: Harden `isParticipationLocked()` with Explicit Overlay Semantics

**File:** `app/Domain/Election/ValueObjects/ElectionLifecycleSnapshot.php`

**This step has no new tests** (behavioral tests in Group D already exercise it). It is a governance-contract change to the implementation only.

Replace the implicit `default => true` with documented semantics:

```php
public function isParticipationLocked(): bool
{
    return match($this->state) {
        // Pre-setup: voter identity is not yet constitutionally relevant
        ElectionLifecycleState::Draft,
        ElectionLifecycleState::SubmittedForApproval,
        ElectionLifecycleState::Approved,
        ElectionLifecycleState::Rejected
            => false,

        // Archival: election is complete; org policy may change for future elections
        ElectionLifecycleState::Archived
            => false,

        // Active lifecycle (SetupAdministration → ResultsPublished): frozen
        // Overlay states (Suspended, Emergency, future): preserve the freeze
        //   — overlays are orthogonal to constitutional authority, not a reset of it
        // Unknown future states: frozen by default (safe governance default)
        default
            => true,
    };
}
```

**Why Step 0 (before everything else):** All subsequent steps rely on `isParticipationLocked()` being the correct single source. The org guard fix (Step 2E) can only be correct if this method is correct first.

---

### Phase 3A — Step 1: Backfill Command (Group A → Green)

**New file:** `app/Console/Commands/BackfillVoterSourceStrategy.php`

Follow `BackfillElectionState.php` as pattern (at `app/Console/Commands/BackfillElectionState.php`).

```
Signature: app:backfill-voter-source-strategy {--audit-only} {--chunk=100}

Logic (BATCHED — correction #2):
- Election::withoutGlobalScopes()
    ->whereNull('voter_source_strategy')
    ->chunkById(100, function ($elections) use ($auditOnly, &$processed, &$skipped, &$failed) {
        foreach ($elections as $election) {
            try {
                $org = $election->organisation;
                $strategy = ElectionMode::fromOrganisation($org)->value;
                if ($auditOnly) {
                    // log + output, no write
                } else {
                    $election->update(['voter_source_strategy' => $strategy]);
                    $processed++;
                }
            } catch (\Throwable $e) {
                Log::error('Backfill failed for election', ['election_id' => $election->id, 'error' => $e->getMessage()]);
                $failed++;
                // continue — do not abort entire batch
            }
        }
    });
- Report: "$processed backfilled, $failed failed — re-run to retry failures"
```

**Idempotency guarantee:** Querying only `whereNull('voter_source_strategy')` means already-filled elections are never touched. Running the command N times always produces the same result.

**Resumability guarantee:** Each chunk is independent. Interrupting mid-run leaves already-processed elections filled (correct). Re-running processes only remaining null elections. No rollback, no double-write.

**Transaction boundary per election (correction #3):**
- Each election update is a single atomic `$election->update([...])` call
- If it fails, that election remains null and will be retried on next run
- Previously processed elections are already committed — not affected by later failures
- No cross-election transaction wrapping (unnecessary and would block large datasets)

---

### Phase 3A — Step 2: Fix Sovereignty Violations (Group B → Green)

**Pre-step: Call Graph Audit for VoterEligibilityService (correction #5)**

Before changing `VoterEligibilityService::isEligibleVoter()` or `::unassignedEligibleQuery()`, audit ALL callers:

- Find every call site using Grep for `isEligibleVoter` and `unassignedEligibleQuery`
- For each call site, determine: does it have an election in scope? Can it pass ElectionMode?
- If a call site exists that has NO election context (e.g., called directly from org-level feature), it must receive a default or use the governance-default derivation legitimately
- Confirm the signature change is safe before implementing

**A. VoterImportController::preview() and import()**

File: `app/Http/Controllers/Election/VoterImportController.php`

```php
// preview() line 86 — BEFORE:
if (!$organisation->uses_full_membership) {

// AFTER:
if (ElectionMode::fromElection($election)->isElectionOnly()) {
```

Same pattern for `import()` line 118.

**B. ElectionVoterController::store() and bulkStore()**

File: `app/Http/Controllers/ElectionVoterController.php`

```php
// store() — capture $election in validation closure, pass to eligibility:
function ($attribute, $value, $fail) use ($organisation, $election) {
    $user = \App\Models\User::find($value);
    $mode = ElectionMode::fromElection($election);
    if (! $user || ! $this->eligibilityService->isEligibleVoter($organisation, $user, $mode)) {

// store() line 125:
mode: ElectionMode::fromElection($election),

// bulkStore() line 156:
mode: ElectionMode::fromElection($election),
```

Also update `index()` line 73-74:

```php
$unassignedMembers = $this->eligibilityService
    ->unassignedEligibleQuery($organisation, $assignedUserIds, ElectionMode::fromElection($election))
    ->get();
```

**C. VoterEligibilityService (after call graph audit)**

Add `ElectionMode $mode` as required parameter to `isEligibleVoter()`:

```php
// BEFORE:
public function isEligibleVoter(Organisation $org, User $user): bool {
    return $this->policy->isEligible($user->id, $org->id, ElectionMode::fromOrganisation($org));
}

// AFTER:
public function isEligibleVoter(Organisation $org, User $user, ElectionMode $mode): bool {
    return $this->policy->isEligible($user->id, $org->id, $mode);
}
```

Add `ElectionMode $mode` parameter to `unassignedEligibleQuery()`:

```php
// BEFORE:
public function unassignedEligibleQuery(Organisation $org, array $excludeUserIds = []): Builder {
    if (!$org->uses_full_membership) {

// AFTER:
// NOTE: The default ElectionMode::FullMembership is a TEMPORARY shim pending call graph audit.
// It must be removed once the audit confirms all callers have an election in scope.
// A remaining default is a warning that a call site was missed — not a feature.
public function unassignedEligibleQuery(Organisation $org, array $excludeUserIds = [], ElectionMode $mode = ElectionMode::FullMembership): Builder {
    if ($mode->isElectionOnly()) {
```

**`publicTutorial()` governance justification (correction #6):**

`VoterImportController::publicTutorial()` hardcodes `'uses_full_membership' => true`. This is intentional and correct:

- The public tutorial has no election in scope — it is a generic instructional page accessible before any election context exists
- It cannot derive from an election snapshot because no election is selected
- Full-membership mode is the safe, generic default for a context-free tutorial
- This is NOT a sovereignty violation — it is a context-free presentation concern

This must be explicitly documented in the code (comment) so future developers do not cargo-cult or "fix" it:

```php
// publicTutorial() is context-free: no election exists in this scope.
// Full-membership is the generic default for a context-free instructional view.
// Do NOT derive from election snapshot — there is no election here.
'uses_full_membership' => true,
```

---

**D. VoterImportService::downloadTemplate()**

File: `app/Services/VoterImportService.php`

```php
// BEFORE:
$isElectionOnly = !$organisation->uses_full_membership;

// AFTER:
$isElectionOnly = ElectionMode::fromElection($this->election)->isElectionOnly();
```

**E. OrganisationSettingsController::validateModeChangeConstraints() — REQUIRED FIX**

File: `app/Http/Controllers/OrganisationSettingsController.php`

The current implementation uses `whereNotIn('state', [...])` which duplicates freeze semantics in the controller. Replace with lifecycle-runtime delegation:

```php
// BEFORE (WRONG — duplicates constitutional semantics in controller):
$lockedElectionExists = \App\Models\Election::withoutGlobalScopes()
    ->where('organisation_id', $organisation->id)
    ->whereNotIn('state', [
        ElectionLifecycleState::Draft->value,
        ElectionLifecycleState::SubmittedForApproval->value,
        ElectionLifecycleState::Approved->value,
        ElectionLifecycleState::Rejected->value,
        ElectionLifecycleState::Archived->value,
    ])
    ->exists();

// AFTER (CORRECT — delegates to lifecycle runtime):
$lockedElectionExists = \App\Models\Election::withoutGlobalScopes()
    ->where('organisation_id', $organisation->id)
    ->get()
    ->contains(fn($election) => \App\Domain\Election\ElectionLifecycle::of($election)->isParticipationLocked());
```

**Why this matters:** With `whereNotIn`, adding a new lifecycle state (e.g., `Suspended`) requires modifying the controller. With the lifecycle-runtime pattern, `isParticipationLocked()` is the single authority — new states are automatically handled by Step 0's `default => true` governance contract.

Group B sovereign test `test_org_boolean_change_does_not_alter_import_behavior_for_existing_election` will implicitly verify this is working correctly. Add a dedicated test if needed:

```
test_org_settings_guard_uses_lifecycle_runtime_not_state_array
  → election in Suspended state (overlay)
  → PATCH org settings membership-mode → assertSessionHasErrors (Suspended preserves freeze)
  [This test would FAIL with whereNotIn since Suspended is not in the exclusion list by accident]
```

---

### Phase 3A — Step 3: Add Fallback Observability (Group C → Green)

**File:** `app/Domain/Election/Enum/ElectionMode.php`

**Current state:** The Phase 2 `fromElection()` fallback is SILENT — it returns a value with no log, no signal, no migration pressure. This makes Phase 3A quarantine-mode blind.

**Required change:** Replace the silent `return self::fromOrganisation(...)` with a structured governance warning. This is a NEW addition — it does not yet exist:

Before:
```php
// Phase 2 implementation (SILENT fallback):
public static function fromElection(Election $election): self
{
    if ($election->voter_source_strategy !== null) {
        return self::from($election->voter_source_strategy);
    }
    // @deprecated Phase 2 compatibility
    return self::fromOrganisation($election->organisation);
}
```

After (Phase 3A — structured governance observability):

```php
public static function fromElection(Election $election): self
{
    if ($election->voter_source_strategy !== null) {
        return self::from($election->voter_source_strategy);
    }

    // Phase 3A quarantine: Transitional fallback with governance telemetry.
    // This path is visible, measurable, and converging toward elimination.
    // Phase 3B replaces this with LogicException once all elections are backfilled.
    $derived = self::fromOrganisation($election->organisation);

    \Illuminate\Support\Facades\Log::warning('ElectionMode::fromElection fallback activated', [
        'event'            => 'fromElection_fallback_activated',
        'election_id'      => $election->id,
        'organisation_id'  => $election->organisation_id,
        'strategy_derived' => $derived->value,
        'org_uses_full'    => $election->organisation->uses_full_membership,
        'snapshot_era'     => $election->created_at->lt(\Carbon\Carbon::parse('2026-05-25')) ? 'legacy' : 'post_phase2_bug',
        'caller'           => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'] ?? 'unknown',
        'message'          => 'voter_source_strategy is null — run app:backfill-voter-source-strategy',
    ]);

    return $derived;
}
```

**Quarantine observability purpose:**  
The structured log event `fromElection_fallback_activated` is the primary convergence signal. `snapshot_era: 'post_phase2_bug'` distinguishes legacy elections (expected during backfill) from new code defects (unexpected). See "Governance-Runtime Metrics Model" section for full interpretation guide.

---

### Phase 3A — Step 4: Behavioral Convergence Tests (Group D)

Write the four behavioral tests in `tests/Architecture/GovernanceRuntime/VoterStrategyConvergenceTest.php`.

These tests drive actual request paths, not file content. They confirm runtime behavior, not code structure.

Also: **replace `VoterStrategyInvariantTest.php` line 128 explicitly** — the `assertTrue(true, 'Architecture documentation...')` at that line must become the behavioral divergence test:

```php
// BEFORE (line 128):
$this->assertTrue(true, 'Architecture documentation: ...');

// AFTER:
// Create org=full_membership but election snapshot=election_only
// Drive the actual voter import path
// Assert election-only behavior was exhibited (snapshot governed, not org boolean)
```

This is a required deliverable of Step 4 — it is not optional cleanup.

---

### Phase 3B — Step 5: Remove Fallback (Group E — after verification)

**Deployment sequencing (correction #4):**

```
Deploy A (Phase 3A complete):
  - All violations fixed
  - Backfill command available
  - Fallback warning active

Operations step:
  - Run: php artisan app:backfill-voter-source-strategy --audit-only
  - Verify: SELECT COUNT(*) FROM elections WHERE voter_source_strategy IS NULL = 0
  - Monitor: No fallback warnings in logs for 2+ deployment cycles

Deploy B (Phase 3B):
  - Apply NOT NULL migration
  - Replace fallback with LogicException
  - All Group E tests written and passing

Deploy C (cleanup):
  - Remove fromOrganisation() usage from contexts where it was only called from fallback
  - [fromOrganisation() itself stays — correction #8]
```

**File:** `app/Domain/Election/Enum/ElectionMode.php`

```php
// PHASE 3B — replaces Phase 3A warning fallback:
public static function fromElection(Election $election): self
{
    if ($election->voter_source_strategy === null) {
        throw new \LogicException(
            "Election {$election->id} has no voter_source_strategy. Run: php artisan app:backfill-voter-source-strategy"
        );
    }
    return self::from($election->voter_source_strategy);
}
```

---

### Phase 3B — Step 6: NOT NULL Migration

**New file:** `database/migrations/2026_05_25_000001_make_voter_source_strategy_not_null_on_elections.php`

**Applied only after Deploy B — after verified backfill (correction #4).**

```php
Schema::table('elections', function (Blueprint $table) {
    $table->string('voter_source_strategy', 30)
          ->nullable(false)
          ->change();
});
```

---

## fromOrganisation() Status (Correction #8)

`ElectionMode::fromOrganisation()` is **NOT removed** in Phase 3.

Its purpose is documented here:

| Call Site | Purpose | Status |
|-----------|---------|--------|
| `ElectionManagementController::store()` | Governance-default derivation for new election creation | **Legitimate — KEEP** |
| `BackfillVoterSourceStrategy` command | Governance-default derivation for pre-Phase-2 elections | **Legitimate — KEEP** |
| `ElectionMode::fromElection()` (Phase 3A fallback) | Transitional compatibility | Emits warning — remove in Phase 3B |

The method's documentation should be updated to clarify its governance-default purpose:

```php
/**
 * Derives voter-source strategy from organisation governance policy.
 *
 * Legitimate uses:
 * - Election creation (governance default → election snapshot)
 * - Backfill command (migrate pre-Phase-2 elections to snapshot)
 *
 * NOT for election-runtime resolution. Use fromElection() in election contexts.
 */
public static function fromOrganisation(Organisation $organisation): self
```

---

## Critical Files Summary

| File | Change | Phase |
|------|--------|-------|
| `app/Domain/Election/ValueObjects/ElectionLifecycleSnapshot.php` | Explicit overlay + Archived semantics in `isParticipationLocked()` | 3A Step 0 |
| `app/Console/Commands/BackfillVoterSourceStrategy.php` | New command (batched, idempotent) | 3A Step 1 |
| `app/Http/Controllers/Election/VoterImportController.php:86,118` | Fix preview(), import() | 3A Step 2A |
| `app/Http/Controllers/ElectionVoterController.php:73,111,125,156` | Fix index(), store(), bulkStore() | 3A Step 2B |
| `app/Services/VoterEligibilityService.php:30,49` | Add ElectionMode param (after call graph audit) | 3A Step 2C |
| `app/Services/VoterImportService.php:35` | Fix downloadTemplate() | 3A Step 2D |
| `app/Http/Controllers/OrganisationSettingsController.php` | Replace `whereNotIn` with lifecycle-runtime pattern | 3A Step 2E |
| `app/Domain/Election/Enum/ElectionMode.php` | Replace silent fallback with structured governance warning | 3A Step 3 |
| `app/Domain/Election/Enum/ElectionMode.php` | Update `fromOrganisation()` docs | 3A Step 3 |
| `app/Domain/Election/Enum/ElectionMode.php` | Remove fallback, add LogicException | 3B Step 5 |
| `database/migrations/2026_05_25_000001_make_voter_source_strategy_not_null.php` | NOT NULL constraint | 3B Step 6 |
| `tests/Architecture/GovernanceRuntime/VoterStrategyInvariantTest.php:128` | Behavioral test (not string scan) | 3A Step 4 |

## What Is NOT Changed in Phase 3

| Item | Reason |
|------|--------|
| `ElectionMode::fromOrganisation()` | Still legitimate for new election creation + backfill |
| `ElectionMode` enum name/values | Phase 4 vocabulary concern |
| `Organisation.uses_full_membership` column | Still the org governance default — Phase 5 concern |
| Overlay/Suspension semantics | Phase 4 concern |
| Federation, delegation | Phase 5 concern |

---

## Governance-Runtime Metrics Model (Correction #1 — Metrics, Not Just Logs)

Phase 3A observability must be modeled as **governance-runtime metrics**, not merely log lines. Logs are passive; metrics create operational pressure toward convergence.

**Metric categories:**

| Signal | Purpose | How Detected |
|--------|---------|--------------|
| Fallback activation count | Detect legacy elections with null snapshot | Structured log event: `fromElection_fallback_activated` |
| Null snapshot count | Migration completeness | DB query: `SELECT COUNT(*) FROM elections WHERE voter_source_strategy IS NULL` |
| Org/election divergence | Sovereignty drift visibility | Elections where `org.uses_full_membership` ≠ snapshot value |
| Fallback by controller context | Hidden runtime paths | Include `caller` field in structured log |

**Structured log format (with controller context):**

```php
$derived = self::fromOrganisation($election->organisation);

\Illuminate\Support\Facades\Log::warning('ElectionMode::fromElection fallback activated', [
    'event'            => 'fromElection_fallback_activated',  // queryable event key
    'election_id'      => $election->id,
    'organisation_id'  => $election->organisation_id,
    'strategy_derived' => $derived->value,
    'org_uses_full'    => $election->organisation->uses_full_membership,
    'caller'           => debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2)[1]['function'] ?? 'unknown',
    'message'          => 'voter_source_strategy is null — run app:backfill-voter-source-strategy',
]);

return $derived;
```

**Operational interpretation:**
- `grep "fromElection_fallback_activated" storage/logs/*.log` → must converge to 0 after backfill
- If this fires after backfill is confirmed → signals a new null-snapshot creation bug (correction #4 detection)
- `org_uses_full` vs `strategy_derived` divergence in the log → shows elections where snapshot and org intentionally differ (expected) or unexpectedly differ (bug)

**Phase 3B go/no-go criteria:**
- Zero fallback events in logs for 2+ consecutive deployments
- `SELECT COUNT(*) FROM elections WHERE voter_source_strategy IS NULL = 0`
- All Phase 3A behavioral tests green

---

## No-New-Null-Snapshot Guarantee (Correction #4 + #7)

Phase 3A fixes legacy null snapshots. It must also guarantee no NEW null snapshots can appear.

**All snapshot creation paths (must be audited before Phase 3A complete):**

| Source | Protected? | Action |
|--------|-----------|--------|
| `ElectionManagementController::store()` | ✅ Yes — Phase 2 added atomic snapshot | Verify in Group B tests |
| Model factories (in tests) | ⚠️ Unclear | Audit factory definition — add `voter_source_strategy` default |
| Database seeders | ⚠️ Unclear | Audit — may need explicit strategy value |
| Test helpers that create elections directly | ⚠️ Unclear | Audit — ensure `voter_source_strategy` is always set |
| Any queued jobs that create elections | ⚠️ Unclear | Grep for `Election::create` in job classes |
| Imports or data migration scripts | ⚠️ Unclear | Audit all import paths |

**Detection strategy for post-Phase-2 null snapshots (correction #4):**

If fallback log fires after backfill is confirmed complete, the election's `created_at` timestamp identifies whether it was created before or after Phase 2 deployment:
- `created_at` before Phase 2 deploy date → expected legacy election, backfill missed it
- `created_at` after Phase 2 deploy date → new bug — election creation path does not set snapshot

Add this detection to the structured log:
```php
'snapshot_era' => $election->created_at->lt($phase2DeployDate) ? 'legacy' : 'post_phase2_bug',
```

**Consequence:** If any post-Phase-2 elections have null snapshots, it means the atomic snapshot creation has a code path that bypasses it. The Phase 3A observability will surface this immediately.

---

## Hybrid Enforcement Model (Correction #5)

Behavioral tests are authoritative. String-scan tests are secondary heuristics.

| Test Type | Role | When to Use |
|-----------|------|------------|
| Behavioral convergence tests | **Authoritative** — drives real request paths; confirms runtime behavior | Primary enforcement |
| Structural string scans | **Heuristic** — detects unreachable code, rename drift, dead fallbacks | Secondary only |
| Architecture invariant tests | **Governance contracts** — encode explicit constitutional rules | Supplementary |

**Why keep string scans as secondary (not removed):**  
Behavioral tests confirm output is correct. They do not detect shadow paths — duplicate logic that is unreachable via current tests but still compiles and runs in edge cases. A string scan that finds `uses_full_membership` in `VoterImportController.php` after Phase 3A is complete is a legitimate signal, even if behavioral tests pass.

**Rule for interpreting conflicts:**
- Behavioral test fails → governance violation confirmed; this is authoritative
- String scan fails but behavioral test passes → investigate shadow path (may be dead code, not active violation); do not assume behavioral test is sufficient
- Both fail → confirmed active violation

**What string scans cannot detect that behavioral tests can:**
- Org boolean read is abstracted behind a private method that behavioral tests exercise
- New code path added that re-introduces violation but is covered by behavioral tests

**What behavioral tests cannot detect that string scans can:**
- Dead code that re-introduces violation when triggered by edge case behavioral tests miss
- Parallel shadow path that happens to produce correct output but reads wrong authority

---

## Call Graph Audit Artifact (Correction #6)

Before implementing VoterEligibilityService signature changes, produce:

**File:** `claude/plans/phase3-call-graph-audit.md`

Contents must include:
- All callers of `isEligibleVoter()` with file:line
- All callers of `unassignedEligibleQuery()` with file:line
- Classification: `election-context` (has election in scope) or `org-only-context` (no election)
- Migration status per caller: `ready` / `needs-election-resolution` / `unresolved`
- Any ambiguous callers that need architectural decision before proceeding

This audit is a **blocking pre-condition** for Step 2C. Do not implement the VoterEligibilityService signature change without a completed audit artifact.

---

## Mixed-Version Deployment Analysis (Correction #8 — Extended Deployment Model)

The 3-deploy sequence (A → B → C) assumes clean serial deploys. Real deployments have overlap.

**Risks during Phase 3A → 3B transition:**

| Risk | Scenario | Mitigation |
|------|----------|------------|
| Old workers creating null snapshots | Horizon/queue workers running old code during rolling deploy | Backfill command is idempotent — re-run after full rollout |
| Stale cached class definitions | PHP opcache serving old `ElectionMode::fromElection()` | Opcache flush during deploy |
| Blue/green overlap | Old blue instance serving requests while green deploys | Fallback warning in 3A absorbs this safely; 3B removes fallback only after confirmed zero nulls |
| Queued jobs from old code | Jobs dispatched before Phase 3B reading election without snapshot | Phase 3A fallback handles this; Phase 3B delayed until queue drains |

**Phase 3B deployment gate:** Before replacing fallback with LogicException:
1. Drain all queued jobs that may call `fromElection()` on pre-Phase-2 elections
2. Restart all workers (no stale code running)
3. Confirm zero fallback warnings for 2+ deploy cycles on the SAME code version
4. Then and only then: apply NOT NULL migration and deploy Phase 3B

---

## Governance Divergence Interpretation Model (Correction #9)

`org.uses_full_membership != election.voter_source_strategy` is NOT always a bug.

| Divergence Type | Meaning | Response |
|----------------|---------|----------|
| Org changed after election creation, snapshot preserved | ✅ Expected — constitutional sovereignty working correctly | No action |
| Org policy changed, snapshot intentionally different | ✅ Expected — this is the designed behavior | No action |
| Post-Phase-2 election with null snapshot | ⚠️ Bug — election creation path bypassed snapshot | Investigate election creation path |
| Post-backfill election with unexpected strategy value | ⚠️ Possible org state changed after Phase 2 but before backfill | Document in audit output |

The structured log's `strategy_derived` vs `org_uses_full` fields provide this interpretation signal.

**The rule:** Divergence between org setting and election snapshot is evidence of constitutional sovereignty working. Only divergence between `created_at > Phase2DeployDate` AND `voter_source_strategy IS NULL` is evidence of a bug.

---

## Quarantine-Mode Framing (Correction #10)

Phase 3A is **runtime quarantine mode**, not temporary compatibility code.

The distinction matters for operational discipline:

| Temporary compatibility | Runtime quarantine |
|------------------------|-------------------|
| Silently continues working | Visibly signals it is running |
| No convergence pressure | Creates measurable pressure toward elimination |
| May persist indefinitely | Has explicit observable exit criteria |
| Developer convenience | Constitutional discipline |

Phase 3A quarantine characteristics:
- All transitional authority paths are **observable** (structured log events)
- All fallback activations are **measurable** (grep-countable)
- All null snapshots are **auditable** (DB query)
- All paths are **converging** toward elimination (backfill reduces count)

**Exit from quarantine = entry into Phase 3B.**  
Exit criteria are defined by metrics (zero fallback events, zero null snapshots), not by calendar or developer judgment alone.

---

## Phase 3 Success Criteria

| Metric | Phase 3A Target | Phase 3B Target |
|--------|-----------------|-----------------|
| Elections with null snapshot | 0 (after backfill) | 0 (enforced by NOT NULL) |
| Fallback path in fromElection() | Emits structured warning | Deleted |
| voter_source_strategy column | Nullable (backfilled) | NOT NULL |
| Election-scoped code reading org boolean | 0 violations | 0 violations |
| Architecture test enforcement | Behavioral (primary) + structural (secondary) | Same |
| Test groups passing | A + B + C + D green | A + B + C + D + E green |
| Governance metrics | All signals observable | All signals converged |
| Call graph audit artifact | Completed (`phase3-call-graph-audit.md`) | Verified |
| Snapshot creation paths | All audited (factories, seeders, jobs) | All protected |
| Quarantine exit criteria | Metrics defined | Metrics achieved |
| isParticipationLocked() | Explicit overlay + Archived semantics documented | Unchanged |
| Org settings guard | Lifecycle-runtime pattern verified | Same |
| publicTutorial() | Justified in code comment | Same |
| No-new-null-snapshot test | Green | Green |

---

## Verification Commands

```bash
# Phase 3A: Backfill command tests
php artisan test tests/Feature/Commands/BackfillVoterSourceStrategyTest.php --env=testing

# Phase 3A: Sovereignty violation tests
php artisan test tests/Feature/Election/VoterStrategySovereigntyTest.php --env=testing

# Phase 3A: Fallback observability tests
php artisan test tests/Feature/Election/VoterStrategyFallbackObservabilityTest.php --env=testing

# Phase 3A: Behavioral convergence tests
php artisan test tests/Architecture/GovernanceRuntime/ --env=testing

# Phase 3B: Fallback removal tests
php artisan test tests/Feature/Election/VoterStrategyFallbackRemovalTest.php --env=testing

# Full Phase 3 regression
php artisan test tests/Feature/Election/ tests/Feature/Organisation/ tests/Architecture/ --env=testing

# Run backfill (audit-only first, then real)
php artisan app:backfill-voter-source-strategy --audit-only --env=testing
php artisan app:backfill-voter-source-strategy --env=testing
```

---

## Phase 4 Prerequisites (Must not proceed without)

- [ ] All elections backfilled (`SELECT COUNT(*) FROM elections WHERE voter_source_strategy IS NULL = 0`)
- [ ] Fallback deleted from `ElectionMode::fromElection()` (Phase 3B complete)
- [ ] NOT NULL migration applied and tested
- [ ] All test groups (A-E) green
- [ ] Behavioral convergence tests pass in CI
- [ ] No fallback warning events in observability logs for 2+ consecutive deployments
- [ ] Call graph audit artifact complete (`claude/plans/phase3-call-graph-audit.md`)
- [ ] All snapshot creation paths audited and protected
- [ ] Mixed-version deployment risk analysis completed
- [ ] Quarantine-mode exit criteria formally met
- [ ] `isParticipationLocked()` updated with explicit overlay + Archived semantics and documentation
- [ ] Org settings guard verified to use lifecycle-runtime pattern (no `whereNotIn`)
- [ ] `publicTutorial()` documented in code comment
- [ ] No-new-null-snapshot invariant test green
- [ ] Election factory audited and produces non-null snapshot by default
