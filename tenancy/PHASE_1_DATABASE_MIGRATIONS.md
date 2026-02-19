# 🔒 Phase 1: Real Voting Enforcement - Database Layer

**Status**: ✅ MIGRATIONS CREATED (Ready for Review)
**Date**: 2026-02-20
**Approval Needed**: Yes (before running)

---

## Overview

Phase 1 establishes the **hard database boundary** for REAL voting enforcement by making `organisation_id` NOT NULL on both `votes` and `results` tables, then adding composite foreign keys to prevent cross-organisation references.

---

## Three Migrations Created

### Migration 1️⃣: Make votes.organisation_id NOT NULL

**File**: `database/migrations/2026_02_20_000001_make_votes_organisation_id_not_null.php`

**What It Does**:
- Changes `votes.organisation_id` from NULLABLE to NOT NULL
- Forces every vote to have a valid organisation context
- Prevents accidental creation of "orphan" votes without tenant assignment

**Business Impact**:
- Any application code trying to create a vote without organisation_id will receive a database constraint error
- This is GOOD - it forces application code to handle organisation context correctly

**Rollback**: Restores nullable for development/testing

---

### Migration 2️⃣: Make results.organisation_id NOT NULL

**File**: `database/migrations/2026_02_20_000002_make_results_organisation_id_not_null.php`

**What It Does**:
- Changes `results.organisation_id` from NULLABLE to NOT NULL
- Forces every vote result to have valid organisation context
- Maintains data integrity: results must match their parent vote's organisation

**Business Impact**:
- Any application code trying to create a result without organisation_id will fail
- Prevents accidental insertion of orphan results

**Rollback**: Restores nullable for development/testing

---

### Migration 3️⃣: Add Composite Foreign Keys

**File**: `database/migrations/2026_02_20_000003_add_composite_foreign_keys_to_voting_tables.php`

**What It Does**:

#### For votes table:
```sql
FOREIGN KEY (election_id, organisation_id)
REFERENCES elections (id, organisation_id)
ON DELETE CASCADE
ON UPDATE CASCADE
```

- Ensures a vote cannot reference an election from a DIFFERENT organisation
- Prevents: User from org 1 creating vote in org 2's election
- Example of violation:
  ```
  elections table: id=5, organisation_id=2
  votes table: election_id=5, organisation_id=1  ← REJECTED by FK
  ```

#### For results table:
```sql
FOREIGN KEY (vote_id, organisation_id)
REFERENCES votes (id, organisation_id)
ON DELETE CASCADE
ON UPDATE CASCADE
```

- Ensures a result cannot reference a vote from a DIFFERENT organisation
- Prevents: Result mistakenly linked to wrong vote in wrong org
- Example of violation:
  ```
  votes table: id=10, organisation_id=2
  results table: vote_id=10, organisation_id=1  ← REJECTED by FK
  ```

**Business Impact**:
- Database physically prevents IMPOSSIBLE states
- No application code can accidentally create cross-organisation votes/results
- Data integrity is GUARANTEED by the database itself

---

## Security Enforcement Layers

After Phase 1, the enforcement stack looks like:

```
┌─────────────────────────────────────────────────────────┐
│ Layer 4: Middleware (Pre-Request Boundary)              │ ← Phase 4
├─────────────────────────────────────────────────────────┤
│ Layer 3: Controller (Application Boundary)              │ ← Phase 3
├─────────────────────────────────────────────────────────┤
│ Layer 2: Model (Soft Boundary - Validation)             │ ← Phase 2
├─────────────────────────────────────────────────────────┤
│ Layer 1: Database (HARD Boundary - NOT NULL + FK)       │ ← Phase 1 ✅
└─────────────────────────────────────────────────────────┘
```

---

## Prerequisites for Running Migrations

Before running Phase 1 migrations, verify:

### ✅ Check 1: No NULL organisation_ids in production data
```sql
SELECT COUNT(*) as null_votes FROM votes WHERE organisation_id IS NULL;
SELECT COUNT(*) as null_results FROM results WHERE organisation_id IS NULL;
```

Expected: Both queries should return 0. If they return > 0, those rows must be handled first.

### ✅ Check 2: Elections table has NOT NULL organisation_id
```sql
DESCRIBE elections;
-- organisation_id column should be: unsignedBigInteger, NOT NULL
```

### ✅ Check 3: No orphan votes (every vote_id must exist in results)
```sql
SELECT COUNT(*) FROM results
WHERE vote_id NOT IN (SELECT id FROM votes);
```

Expected: 0 (no orphans)

---

## Migration Execution Plan

### Step 1: Review Migrations
- [ ] Verify migration files are created correctly
- [ ] Check syntax is valid (no PHP errors)
- [ ] Confirm rollback logic is sound

### Step 2: Database Backup
- [ ] Create backup of production database before running
- [ ] Verify backup is valid and restorable

### Step 3: Run Migrations
```bash
# Test on development database first
php artisan migrate --database=testing

# If successful, run on production
php artisan migrate

# Verify migrations ran
php artisan migrate:status | grep "2026_02_20_00000"
```

### Step 4: Verify Database State
```sql
-- Verify NOT NULL constraints
DESCRIBE votes;     -- organisation_id should show NOT NULL
DESCRIBE results;   -- organisation_id should show NOT NULL

-- Verify foreign keys
SHOW CREATE TABLE votes;     -- Should have composite FK
SHOW CREATE TABLE results;   -- Should have composite FK

-- Test FK enforcement
INSERT INTO votes (election_id, organisation_id, voting_code)
VALUES (1, 999, 'test');  -- Should FAIL if election doesn't have org 999
```

### Step 5: Document Success
- [ ] Migration executed without errors
- [ ] All constraints are active
- [ ] Application code handles constraints correctly

---

## Testing Strategy

After Phase 1 is deployed, test the enforcement:

### Test 1: Database Prevents NULL organisation_id
```php
// This MUST fail with database constraint error
$vote = Vote::create([
    'election_id' => 1,
    'voting_code' => 'test',
    // organisation_id intentionally missing
]);
// Expected: DatabaseException - NOT NULL constraint violation
```

### Test 2: Composite FK Prevents Cross-Org References
```php
// Election from org 2
$election = Election::find(5);  // organisation_id = 2

// Try to create vote for org 1
// This MUST fail with foreign key constraint error
$vote = Vote::create([
    'election_id' => $election->id,
    'organisation_id' => 1,  // Different from election's org
    'voting_code' => 'test'
]);
// Expected: DatabaseException - FK constraint violation
```

### Test 3: Result Cannot Cross Organisations
```php
// Vote from org 1
$vote = Vote::find(10);  // organisation_id = 1

// Try to create result for org 2
// This MUST fail with foreign key constraint error
$result = Result::create([
    'vote_id' => $vote->id,
    'organisation_id' => 2,  // Different from vote's org
    'candidacy_id' => '123'
]);
// Expected: DatabaseException - FK constraint violation
```

---

## Phase Completion Checklist

- [ ] All 3 migrations created in `database/migrations/`
- [ ] Migration file names follow Laravel convention
- [ ] `up()` methods implement NOT NULL and FK constraints
- [ ] `down()` methods provide proper rollback
- [ ] Prerequisites checked (no NULL data, proper FK targets)
- [ ] Database backup created
- [ ] Migrations executed successfully
- [ ] Database constraints verified with DESCRIBE and SHOW
- [ ] Application tests updated to expect constraint violations
- [ ] Documentation updated

---

## Next Phases

Once Phase 1 is verified:

- **Phase 2**: Model-level validation hooks in Vote/Result models
- **Phase 3**: Controller-level enforcement in VoteController.store()
- **Phase 4**: Middleware enforcement with EnsureRealVoteOrganisation
- **Phase 5**: Comprehensive test suite (RealVoteOrganisationEnforcementTest)

---

## Rollback Instructions

If migrations need to be rolled back:

```bash
# Rollback last 3 migrations
php artisan migrate:rollback --step=3

# Or rollback to specific migration
php artisan migrate:rollback --path=database/migrations/2026_02_20_000003_add_composite_foreign_keys_to_voting_tables.php
```

---

## Summary

**Phase 1 creates an IMPENETRABLE database boundary:**

| Layer | Enforcement | Status |
|-------|-------------|--------|
| NOT NULL on votes.organisation_id | Database prevents NULL | ✅ Created |
| NOT NULL on results.organisation_id | Database prevents NULL | ✅ Created |
| Composite FK (votes → elections) | Database prevents cross-org votes | ✅ Created |
| Composite FK (results → votes) | Database prevents cross-org results | ✅ Created |

**Result**: No vote or result can ever be saved without a valid organisation context AND must match their parent record's organisation.

This is the foundation for the entire 4-layer enforcement system.

---

**Status**: ✅ Phase 1 migrations complete and ready for review/execution
