# Phase 1: Foundation & Dual-Mode Architecture

**Status:** ✅ Complete  
**Date:** 2026-05-15  
**Scope:** Establish multi-tenancy + election-only mode bifurcation  

---

## Overview

Phase 1 established the fundamental architecture for supporting two distinct election modes:
- **Election-Only Mode**: Simplified, lightweight — requires only `organisation_users` table
- **Full Membership Mode**: Complex, governance-heavy — requires `members` + fees + membership types

This bifurcation is the architectural foundation for everything that follows.

---

## Key Architectural Decisions

### 1. Two-Mode Governance Policy

**Domain Concept:** `ElectionMode` enum with two values
- `FullMembership` (default, backward-compatible)
- `ElectionOnly` (new, lightweight)

**Implementation Location:** `app/Domain/Election/Enum/ElectionMode.php`

```php
enum ElectionMode: string
{
    case FullMembership = 'full_membership';
    case ElectionOnly = 'election_only';
    
    // Helper methods for mode detection
    public function isElectionOnly(): bool { ... }
    public function isFullMembership(): bool { ... }
}
```

**Why This Approach:**
- Explicit, type-safe representation
- Single source of truth for mode definition
- Enables DDD domain language (replaces magic booleans)

---

### 2. Dual Voter Eligibility Rules

**Policy Layer:** `VoterEligibilityPolicy` (Domain)

Routes decision-making based on mode:

```php
public function isEligible(string $userId, string $orgId, ElectionMode $mode): bool
{
    return match($mode) {
        ElectionMode::ElectionOnly => $this->isEligibleInElectionMode(...),
        ElectionMode::FullMembership => $this->isEligibleInMembershipMode(...),
    };
}
```

**Election-Only Eligibility:**
- ✅ Active `OrganisationUser` record exists
- ✅ User status = 'active'
- ✅ Not soft-deleted

**Full Membership Eligibility:**
- ✅ Active `Member` record exists
- ✅ Fees status = 'paid' or 'exempt'
- ✅ Membership type grants voting rights (if set)
- ✅ Membership not expired
- ✅ Member status = 'active'
- ✅ Not soft-deleted

---

### 3. Dual Import Strategies

**Service:** `VoterImportService`

Detects mode and routes to correct import path:

```php
if ($mode->isElectionOnly()) {
    return $this->importElectionOnly($file);  // Create users + org_users
} else {
    return $this->import($file);              // Validate against members table
}
```

**Election-Only Import Flow:**
1. Parse CSV: `firstname, lastname, email`
2. Create `User` if not exists
3. Create `OrganisationUser` link
4. Assign to election

**Full Membership Import Flow:**
1. Parse CSV: `email` only
2. User must already exist
3. User must have active `Member` record
4. Assign to election

---

## Database Schema Phase 1

**Key Tables:**
- `organisations`: `uses_full_membership` boolean (governance default)
- `organisation_users`: Lightweight org participation
- `members`: Heavy governance with fees/types
- `elections`: Election records (snapshot field added in Phase 2)

**Critical Design:**
- No `voter_source_strategy` column yet (Phase 1 reads `organisation->uses_full_membership` at runtime)
- Elections derive authority from org's current setting (will be fixed in Phase 2)

---

## Testing Strategy Phase 1

### Unit Tests
- `VoterEligibilityPolicyTest`: Election-only vs full membership paths
- `ElectionModeTest`: Enum behavior and helper methods

### Feature Tests
- `VoterImportTest`: Both import paths (CSV parsing, validation)
- `ElectionVoterControllerTest`: Voter assignment in both modes

### Test Fixtures
- `ElectionFactory`: Creates elections with `uses_full_membership` override
- `OrganisationFactory`: Defaults to full membership, allows override
- `ElectionScenarioFactory`: Pre-configured test scenarios

**Test Coverage:** 45+ tests validating mode bifurcation

---

## Key Files (Phase 1)

| File | Purpose |
|------|---------|
| `ElectionMode.php` | Domain enum defining modes |
| `VoterEligibilityPolicy.php` | Policy layer with dual rules |
| `VoterEligibilityService.php` | Service orchestrating policy decisions |
| `VoterImportService.php` | Dual import strategies |
| `ElectionVoterController.php` | HTTP layer for voter management |
| `OrganisationController.php` | Org creation with mode selection |

---

## Critical Constraints (Phase 1)

### ⚠️ Runtime Authority is Mutable

**The Problem:**
```
Organisation::uses_full_membership changes at runtime
        ↓
Election's voter eligibility rules change retroactively
```

**Example Scenario:**
1. Create org with `uses_full_membership = false` (election-only)
2. Create election — voters eligible based on `organisation_users`
3. Admin changes org to `uses_full_membership = true` (full membership)
4. SAME election now requires `members` with fees — existing voters disqualified!

**Phase 1 Status:** ⚠️ **This is a bug** — will be fixed in Phase 2

---

## How Phase 1 Works

### Creating an Election-Only Election

```bash
# Step 1: Create organisation with election-only mode
POST /organisations
{
    "name": "Local Club",
    "uses_full_membership": false
}

# Step 2: Create election (inherits org's mode)
POST /organisations/{org}/elections
{
    "name": "Board Election",
    "type": "real"
}

# Step 3: Assign voters (only requires OrganisationUser)
POST /organisations/{org}/elections/{election}/voters
{
    "user_id": "uuid"
}
```

### Under the Hood

1. **Election Creation**: `ElectionManagementController` creates election
   - No snapshot field yet
   - Election records will derive mode from org at runtime

2. **Voter Assignment**: `ElectionVoterController` validates eligibility
   - Calls `VoterEligibilityService::isEligibleVoter()`
   - Derives `ElectionMode` from `organisation->uses_full_membership`
   - Routes through `VoterEligibilityPolicy` for final decision

3. **Voter Import**: `VoterImportController` routes to correct handler
   - Detects `organisation->uses_full_membership`
   - Election-only: creates users on-the-fly
   - Full membership: validates against existing members

---

## Anti-Patterns to Avoid (Phase 1)

### ❌ Reading org mode directly in election context

```php
// NO - This is tight coupling
if ($organisation->uses_full_membership) { ... }
```

### ❌ Creating magic booleans

```php
// NO - What does true/false mean?
$isElectionOnly = !$organisation->uses_full_membership;
```

### ✅ Correct approach

```php
// YES - Explicit, semantic
$mode = ElectionMode::fromOrganisation($organisation);
if ($mode->isElectionOnly()) { ... }
```

---

## Known Limitations (Phase 1)

1. **No Snapshot**: Elections don't own their mode authority — derived from org at runtime
2. **Mutable Authority**: Org changes affect elections retroactively
3. **Split Brain Risk**: Org mode can diverge from election's actual voter population
4. **No Audit Trail**: No record of what mode an election was created in

**Resolution:** Phase 2 implements constitutional snapshots to fix all of these

---

## Testing Phase 1

### Running Tests

```bash
# All Phase 1 tests
php artisan test tests/Feature/Election/VoterEligibilityTest.php --env=testing

# Election-only specific
php artisan test --filter "election_only" --env=testing

# Import both modes
php artisan test tests/Feature/VoterImportTest.php --env=testing
```

### Test Organization

```
tests/
  Feature/
    Election/
      VoterEligibilityTest.php        # 25+ tests
      VoterImportTest.php             # 18+ tests
      ElectionVoterControllerTest.php # 15+ tests
  Unit/
    Domain/
      Election/
        ElectionModeTest.php          # 8+ tests
```

---

## Integration Points

### Phase 1 → Phase 2
- Snapshot field added to `elections` table
- `voter_source_strategy` populated at election creation
- `ElectionMode::fromElection()` reads snapshot, not org

### Phase 1 → Frontend
- Election-only mode selector in org creation
- Different voter import templates per mode
- Conditional UI based on `organisation->uses_full_membership`

---

## Glossary (Phase 1)

| Term | Meaning |
|------|---------|
| **ElectionMode** | Enum representing election's voter source authority |
| **uses_full_membership** | Boolean on `Organisation` — governance default |
| **Election-Only Mode** | Lightweight participation via `organisation_users` |
| **Full Membership Mode** | Governed participation via `members` + fees |
| **Voter Eligibility Policy** | Domain layer determining who can vote |

---

## Recommended Reading Order

1. **Architecture Overview**: Read this file (you are here)
2. **Code Tour**: Navigate `ElectionMode.php` → `VoterEligibilityPolicy.php` → `VoterImportService.php`
3. **Tests**: Run `VoterEligibilityTest.php` and examine test scenarios
4. **Integration**: See how `ElectionVoterController` orchestrates these components

---

## Next Phase

See `PHASE_2_CONSOLIDATION.md` for how Phase 2 solves the mutable authority problem through constitutional snapshots.
