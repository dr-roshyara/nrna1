# Phase 2: Constitutional Snapshot & Participation Freeze

**Status:** ✅ Complete  
**Date:** 2026-05-20  
**Scope:** Establish election constitutional sovereignty + participation freeze point  

---

## Overview

Phase 2 solves Phase 1's critical architectural defect: **runtime authority mutability**.

**The Problem from Phase 1:**
```
Organisation.uses_full_membership changes at runtime
        ↓
Election's voter eligibility rules change retroactively
        ↓
Constitutional integrity violation
```

**The Solution (Phase 2):**
```
Election.voter_source_strategy snapshot (atomic)
        ↓ (never retroactively changes)
Election owns its own voter authority
        ↓
Organisation changes have zero impact on existing elections
```

This bifurcation is the governance foundation for everything that follows.

---

## Key Architectural Decisions

### 1. Constitutional Snapshot Field

**Field:** `elections.voter_source_strategy` (string, stores ElectionMode value)

**Semantic Meaning:**
- NOT a cached copy of org's current setting
- A **constitutional snapshot** — the election's declared governance authority at creation time
- Immutable once election reaches participation-locked state
- Read via `ElectionMode::fromElection($election)` — snapshot is sovereign

**Why Constitutional Snapshot:**
- Answers the question: "What voter-source authority did THIS election establish?"
- Separates election sovereignty from organisation governance
- Provides non-repudiation: later queries prove what the election declared
- Enables audit trail: `elect_created_at` + `voter_source_strategy` = historical evidence

**Implementation Location:** `app/Models/Election.php`
- `'voter_source_strategy' => 'string'` cast
- `'voter_source_strategy'` in `$fillable`

---

### 2. Participation Freeze Point

**Question:** When does voter identity become constitutionally relevant?

**Answer:** `SetupAdministration` — this is when voter import begins (per `ElectionLifecycleState` docblock: *"Governance infrastructure: posts, voters, chief"*).

**Semantic Method:** `ElectionLifecycleSnapshot::isParticipationLocked(): bool`

```php
public function isParticipationLocked(): bool
{
    return match($this->state) {
        ElectionLifecycleState::Draft,
        ElectionLifecycleState::SubmittedForApproval,
        ElectionLifecycleState::Approved,
        ElectionLifecycleState::Rejected,
        ElectionLifecycleState::Archived => false,
        default => true, // SetupAdministration and beyond
    };
}
```

**Freeze Semantics:**
| State | Locked? | Meaning |
|---|---|---|
| `Draft` | ❌ No | Strategy mutable, no voters yet |
| `SubmittedForApproval` | ❌ No | Strategy mutable, awaiting approval |
| `Approved` | ❌ No | Strategy mutable, prep phase |
| `Rejected` | ❌ No | Strategy mutable, can reset |
| **`SetupAdministration`** | **✅ YES** | **FROZEN — voter import starts** |
| `SetupNomination` | ✅ Yes | Frozen — active participation |
| `ReadyForVoting` | ✅ Yes | Frozen — pre-voting prep |
| `VotingActive` | ✅ Yes | Frozen — votes being cast |
| `Counting` | ✅ Yes | Frozen — historical |
| `Archived` | ❌ No | Historical, no ops possible |
| `Suspended` | ✅ Yes | Overlay — preserves freeze |
| `Emergency` | ✅ Yes | Overlay — preserves freeze |

**Why Overlays Preserve Freeze:**
Suspended/Emergency states are operational overlays. They don't unlock the election for governance changes. The underlying participation authority remains frozen.

**Why Archived Returns False:**
Archived elections are historical. No operational constraint makes sense; changes are impossible anyway.

---

### 3. Atomic Snapshot at Election Creation

**Pattern:**

```php
$election = Election::create([
    'id'                    => (string) Str::uuid(),
    'organisation_id'       => $organisation->id,
    'name'                  => $validated['name'],
    'voter_source_strategy' => ElectionMode::fromOrganisation($organisation)->value,
    // ... rest of fields
]);
```

**Key Principle:** `voter_source_strategy` is set **inside the create() array**, not via post-creation `update()`.

**Why Atomic:**
- Single INSERT statement contains the snapshot
- No separate UPDATE that could fail
- Database transaction wraps creation
- Snapshot is established with the election in one atomic operation

**Anti-Pattern to Avoid:**
```php
// ❌ WRONG - Post-update pattern
$election = Election::create([...]);
$election->update([
    'voter_source_strategy' => ElectionMode::fromOrganisation($organisation)->value
]);

// This has a window where snapshot doesn't exist
// And makes it appear the snapshot is derived, not declared
```

---

### 4. Sovereignty Validation

**File:** `app/Domain/Election/Enum/ElectionMode.php`

**Method:** `ElectionMode::fromElection(Election $election): self`

```php
public static function fromElection(Election $election): self
{
    if ($election->voter_source_strategy === null) {
        throw new \RuntimeException(
            sprintf(
                'Election %s (%s) missing voter_source_strategy snapshot. ' .
                'Run "php artisan app:backfill-voter-source-strategy" to populate missing elections.',
                $election->id,
                $election->slug
            )
        );
    }

    return self::from($election->voter_source_strategy);
}
```

**Semantics:**
- Election snapshot is **REQUIRED**
- NULL is a critical defect (legacy election from Phase 1)
- Exception forces remediation (backfill command)
- No silent fallback — failures are explicit

**Why No Fallback:**
A missing snapshot means the election's voter authority is undefined. This is a data integrity problem that must be surfaced, not hidden with a fallback to organisation mode.

---

### 5. Participation Guard

**File:** `app/Http/Controllers/OrganisationSettingsController.php`

**Pattern:**

```php
protected function validateModeChangeConstraints(Organisation $organisation): void
{
    // Check: Is there an active election with participation locked?
    $lockedElectionExists = Election::withoutGlobalScopes()
        ->where('organisation_id', $organisation->id)
        ->whereNotIn('state', [
            ElectionLifecycleState::Draft->value,
            ElectionLifecycleState::SubmittedForApproval->value,
            ElectionLifecycleState::Approved->value,
            ElectionLifecycleState::Rejected->value,
            ElectionLifecycleState::Archived->value,
        ])
        ->exists();

    if ($lockedElectionExists) {
        throw ValidationException::withMessages([
            'membership_mode' => __('organisations.errors.participation_locked_by_active_election'),
        ]);
    }
}
```

**Semantics:**
- Organisation can change governance policy only if:
  - No elections exist, OR
  - All elections are in pre-participation states (Draft through Rejected), OR
  - All elections are archived (historical)
- Once any election reaches SetupAdministration, org is locked
- Lock persists until election is archived

**Guard Location:**
- First check in mode-change handler
- Before business logic, before fees validation
- Fails fast with user-facing error message

---

## Database Schema Phase 2

**New/Modified Columns:**
- `elections.voter_source_strategy` (string, 30 chars) — constitutional snapshot

**Constraints:**
- `NOT NULL` on `voter_source_strategy` — enforced in Phase 3.2 after backfill
- Foreign key on `organisation_id` — existing, unchanged

**Migration:**
```
database/migrations/[YYYY_MM_DD]_000001_add_voter_source_strategy_to_elections.php
```

**Retention:**
- `organisations.uses_full_membership` — kept as governance default
- All Phase 1 columns unchanged
- Anti-corruption layer preserved (for future federation)

---

## Testing Strategy Phase 2

### Unit Tests
- `ElectionModeTest` — `fromElection()` sovereignty validation
- `ElectionLifecycleSnapshotTest` — `isParticipationLocked()` semantics

### Feature Tests
- `OrganisationCreationMembershipTest` — election-only org creation persists mode
- `VoterStrategySnapshotTest` — atomic snapshot creation, org mutation resilience
- `VoterStrategyMigrationIntegrityTest` — legacy fallback compatibility
- `ParticipationFreezeTest` — org guard prevents mode changes when elections locked
- `VoterStrategyInvariantTest` — architecture integrity checks

### Test Coverage
- 24+ tests validating snapshot sovereignty
- 5+ convergence invariant tests
- All tests use `RefreshDatabase` (PostgreSQL nrna_test)
- Zero tests run against main database

---

## Architecture Invariants (Phase 2)

### Invariant 1: Snapshot Atomicity
```
The snapshot is established in the same transaction as election creation.
The snapshot is never null after creation (Phase 3.2 enforces this).
No post-creation update modifies the snapshot.
```

### Invariant 2: Snapshot Sovereignty
```
ElectionMode::fromElection($election) reads snapshot.
Snapshot value is never derived from organisation at runtime.
If snapshot is null, exception is thrown (no fallback).
```

### Invariant 3: Mutation Resilience
```
Organisation changes after election creation have zero effect.
Changing org.uses_full_membership does not affect election.voter_source_strategy.
Election maintains its declared authority regardless of org mode changes.
```

### Invariant 4: Participation Freeze
```
Once election reaches SetupAdministration:
  - voter_source_strategy cannot change (immutable)
  - organisation cannot change membership mode (guard enforces)
  - Election's governance authority is constitutionally frozen
```

### Invariant 5: No Election-Scoped Org Reads
```
No controller/service in election context reads org.uses_full_membership.
All elections use ElectionMode::fromElection() for authority.
Org boolean exists only for:
  - New election creation (to derive snapshot)
  - Backfill command (to populate legacy elections)
  - Governance dashboard (org-scoped, not election-scoped)
```

---

## How Phase 2 Works

### Creating an Election (Now with Snapshot)

```bash
# Step 1: Create organisation
POST /organisations
{
    "name": "Local Club",
    "uses_full_membership": false
}

# Step 2: Create election (atomically snaps voter strategy)
POST /organisations/{org}/elections
{
    "name": "Board Election",
    "type": "real"
}

# Database result:
# elections.voter_source_strategy = 'election_only' (from org mode at creation)
# Never changes, even if org mode changes later
```

### Checking Voter Eligibility (Now Using Snapshot)

```php
// BEFORE (Phase 1 - reads org at runtime):
$mode = ElectionMode::fromOrganisation($election->organisation);
$eligible = $this->policy->isEligible($user, $mode);

// AFTER (Phase 2 - reads election snapshot):
$mode = ElectionMode::fromElection($election);
$eligible = $this->policy->isEligible($user, $mode);
```

**Key Change:** Mode is read from election, not organisation.

### Voter Import (Now With Snapshot Authority)

```php
// VoterImportService::downloadTemplate()
$isElectionOnly = ElectionMode::fromElection($this->election)->isElectionOnly();

if ($isElectionOnly) {
    return $this->csvTemplate(['firstname', 'lastname', 'email']);
} else {
    return $this->csvTemplate(['email']); // Validates against members
}
```

**Key Change:** Decision is based on election's snapshot, not org's current mode.

### Organisation Mode Change (Now Protected)

```php
// OrganisationSettingsController::update()
$this->validateModeChangeConstraints($organisation);
// If election is in SetupAdministration or beyond:
// throws ValidationException("Voter source strategy cannot be changed...")
// Otherwise, mode change succeeds
```

**Key Change:** Explicit guard prevents retroactive changes to participating elections.

---

## Critical Files (Phase 2)

| File | Purpose | Change from Phase 1 |
|------|---------|---|
| `app/Models/Election.php` | Eloquent model | Added `voter_source_strategy` fillable + cast |
| `app/Domain/Election/Enum/ElectionMode.php` | Mode enum | Added `fromElection()` method |
| `app/Domain/Election/ValueObjects/ElectionLifecycleSnapshot.php` | Lifecycle snapshot | Added `isParticipationLocked()` method |
| `app/Http/Controllers/Election/ElectionManagementController.php` | Election creation | Added snapshot to create() array |
| `app/Http/Controllers/OrganisationSettingsController.php` | Org governance | Added `validateModeChangeConstraints()` |
| `app/Http/Controllers/Election/VoterImportController.php` | Voter import | Changed to read election snapshot |
| `app/Services/VoterImportService.php` | Import logic | Changed to read election snapshot |
| `app/Services/VoterEligibilityService.php` | Eligibility routing | Changed to accept ElectionMode parameter |
| `app/Http/Controllers/ElectionVoterController.php` | Voter assignment | Changed to pass ElectionMode from snapshot |
| `database/migrations/[date]_add_voter_source_strategy_to_elections.php` | Schema | New migration adding column |

---

## Anti-Patterns to Avoid (Phase 2)

### ❌ Reading org mode inside election context

```php
// NO - Breaks sovereignty
if ($election->organisation->uses_full_membership) { ... }
```

### ❌ Deriving mode from org at runtime in election code

```php
// NO - Creates dependency on org state
$mode = ElectionMode::fromOrganisation($election->organisation);
```

### ✅ Correct approach

```php
// YES - Reads election's declared authority
$mode = ElectionMode::fromElection($election);
```

### ❌ Allowing org mode changes without checking elections

```php
// NO - Retroactively mutates election sovereignty
$organisation->update(['uses_full_membership' => !$organisation->uses_full_membership]);
```

### ✅ Correct approach

```php
// YES - Guard validates before allowing change
$this->validateModeChangeConstraints($organisation);
// Then safe to update
```

---

## Migration Path (Phase 1 → Phase 2)

### For Existing Elections (Legacy)
- Snapshot field added with default NULL (backwards compatible)
- Elections continue to work via fallback during Phase 2
- Fallback: `ElectionMode::fromElection()` uses snapshot if present, org mode if null
- Phase 3 backfill command populates all legacy elections

### For New Elections (Post-Phase 2)
- Snapshot is set atomically at creation
- Snapshot is never null (immediately set)
- No fallback needed; snapshot is authoritative

### Timeline
```
Phase 2 ← you are here
├─ All elections get voter_source_strategy field (nullable)
├─ New elections have snapshot set at creation
├─ Legacy elections still work via fallback
└─ Org guard prevents mode changes during active elections

Phase 3 (Consolidation) ← next
├─ Backfill command populates all legacy elections
├─ Make voter_source_strategy NOT NULL
├─ Remove fallback path
└─ Election snapshot is sole authority
```

---

## Known Limitations (Phase 2)

1. **Legacy Elections Without Snapshots:** Elections created in Phase 1 have NULL snapshot; fallback path uses org mode temporarily
2. **Org Mode Still Mutable:** Organisation can change uses_full_membership, though guard prevents it for active elections
3. **Anti-Corruption Layer Exposed:** Inertia props still expose `uses_full_membership` boolean (Phase 3 will use snapshot)

**Resolution:** Phase 3 implements complete purification (backfill, no fallback, vocabulary alignment)

---

## Testing Phase 2

### Running Tests

```bash
# All Phase 2 tests
php artisan test tests/Feature/Election/VoterStrategySnapshotTest.php --env=testing
php artisan test tests/Feature/Governance/ParticipationFreezeTest.php --env=testing
php artisan test tests/Architecture/GovernanceRuntime/VoterStrategyInvariantTest.php --env=testing

# Verify snapshot sovereignty
php artisan test --filter "snapshot" --env=testing

# Verify participation freeze
php artisan test --filter "participation_locked" --env=testing

# Full suite
php artisan test --env=testing
```

### Key Test Scenarios

**Scenario 1: Snapshot Survives Org Mutation**
```
1. Create org with uses_full_membership=false
2. Create election → voter_source_strategy='election_only'
3. Change org to uses_full_membership=true
4. Verify election.voter_source_strategy still='election_only'
5. Verify ElectionMode::fromElection() returns ElectionOnly
```

**Scenario 2: Org Guard Prevents Mode Change**
```
1. Create election in SetupAdministration state
2. Try to change org.uses_full_membership
3. Verify ValidationException thrown
4. Archive election
5. Try to change org.uses_full_membership again
6. Verify success (archived elections don't lock)
```

**Scenario 3: Snapshot Priority Over Org**
```
1. Create org with uses_full_membership=true (full membership)
2. Create election with voter_source_strategy='election_only' (different!)
3. Call ElectionMode::fromElection()
4. Verify returns ElectionOnly (snapshot wins)
```

---

## Integration Points

### Phase 2 → Phase 3
- Backfill command populates NULL snapshots from current org mode
- Make voter_source_strategy NOT NULL enforcing snapshot presence
- Remove fallback path in `ElectionMode::fromElection()`

### Phase 2 → Frontend
- Inertia props now derive `uses_full_membership` from election snapshot
- Voter import page uses election's declared authority (not org's)
- Org settings page shows warning when elections are locked

### Phase 2 → Org Governance
- Org still holds `uses_full_membership` as governance default
- New elections inherit org mode (but own their snapshot)
- Changing org mode affects only future elections
- Active elections are protected by freeze guard

---

## Glossary (Phase 2)

| Term | Meaning |
|------|---------|
| **Constitutional Snapshot** | `voter_source_strategy` value recorded at election creation |
| **Participation Lock** | State when voter identity becomes constitutionally relevant |
| **Freeze Semantics** | Elections in SetupAdministration+ cannot have voter strategy changed |
| **Sovereignty** | Election owns its own voter-source authority (not derived from org) |
| **Fallback (Phase 2 only)** | Temporary path that uses org mode if snapshot is NULL |
| **Anti-Corruption Layer** | Persistence of `uses_full_membership` for federation readiness |

---

## Recommended Reading Order

1. **This File** (you are here) — Architecture overview
2. **Code Tour:** `ElectionMode::fromElection()` → `isParticipationLocked()` → `validateModeChangeConstraints()`
3. **Tests:** Run `VoterStrategySnapshotTest.php` and examine test scenarios
4. **Integration:** See how `ElectionManagementController` atomically creates snapshot

---

## Next Phase

See `PHASE_3_RUNTIME_PURIFICATION.md` for how Phase 3 completes sovereignty enforcement through backfill, fallback removal, and vocabulary convergence.
