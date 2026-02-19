# 🚀 Phase 1 Execution Checklist

**Status**: Ready to Execute
**Date**: 2026-02-20
**Migrations**: 3 files created & syntax verified

---

## Pre-Execution Checklist

### 📋 Review
- [ ] Read `PHASE_1_DATABASE_MIGRATIONS.md` (detailed technical doc)
- [ ] Read `PHASE_1_COMPLETION_SUMMARY.md` (overview)
- [ ] Understand what each migration does

### 🔍 Verify Prerequisites

Run these queries on production database:

```sql
-- Check 1: No NULL organisation_ids
SELECT 'votes_null_count' as check_name, COUNT(*) as count FROM votes WHERE organisation_id IS NULL
UNION ALL
SELECT 'results_null_count', COUNT(*) FROM results WHERE organisation_id IS NULL;

-- Expected: Both should be 0

-- Check 2: No orphan results
SELECT COUNT(*) as orphan_results FROM results
WHERE vote_id NOT IN (SELECT id FROM votes);

-- Expected: 0

-- Check 3: Elections have organisation_id
SELECT COUNT(*) as elections_without_org FROM elections
WHERE organisation_id IS NULL;

-- Expected: 0

-- Check 4: No vote/election org mismatches
SELECT COUNT(*) as mismatches FROM votes v
JOIN elections e ON v.election_id = e.id
WHERE v.organisation_id != e.organisation_id;

-- Expected: 0
```

If ANY check fails, STOP and investigate before continuing.

### 💾 Backup Database

```bash
# MySQL/MariaDB
mysqldump -u root -p publicdigit > publicdigit_backup_2026_02_20.sql

# PostgreSQL
pg_dump publicdigit > publicdigit_backup_2026_02_20.sql

# Store backup securely
ls -lh publicdigit_backup_*.sql
```

- [ ] Backup created
- [ ] Backup verified (can restore if needed)
- [ ] Backup location documented

---

## Execution

### Step 1: List Migrations to Verify They're Detected

```bash
php artisan migrate:status | grep "2026_02_20"

# Expected output:
# 2026_02_20_000001_make_votes_organisation_id_not_null        Pending
# 2026_02_20_000002_make_results_organisation_id_not_null      Pending
# 2026_02_20_000003_add_composite_foreign_keys_to_voting_tables Pending
```

- [ ] All 3 migrations show as "Pending"

### Step 2: Run Migrations

```bash
# Option A: Run all pending migrations
php artisan migrate

# Option B: Run specific migration file
php artisan migrate --path=database/migrations/2026_02_20_000001_make_votes_organisation_id_not_null.php
php artisan migrate --path=database/migrations/2026_02_20_000002_make_results_organisation_id_not_null.php
php artisan migrate --path=database/migrations/2026_02_20_000003_add_composite_foreign_keys_to_voting_tables.php

# Watch for any errors - if error occurs, STOP and investigate
```

- [ ] Migrations executed successfully (no errors)
- [ ] Output shows "migrated successfully"

### Step 3: Verify Migrations Ran

```bash
php artisan migrate:status | grep "2026_02_20"

# Expected output:
# 2026_02_20_000001_make_votes_organisation_id_not_null        Migrated
# 2026_02_20_000002_make_results_organisation_id_not_null      Migrated
# 2026_02_20_000003_add_composite_foreign_keys_to_voting_tables Migrated
```

- [ ] All 3 migrations show as "Migrated"

---

## Post-Execution Verification

### 🔐 Verify Database Constraints

```sql
-- Check 1: Verify NOT NULL on votes.organisation_id
DESCRIBE votes;
-- Look for: organisation_id | bigint unsigned | NO (was: YES before)

-- Check 2: Verify NOT NULL on results.organisation_id
DESCRIBE results;
-- Look for: organisation_id | bigint unsigned | NO (was: YES before)

-- Check 3: Verify composite FK on votes
SHOW CREATE TABLE votes;
-- Look for: FOREIGN KEY (`election_id`, `organisation_id`)
--           REFERENCES `elections` (`id`, `organisation_id`)

-- Check 4: Verify composite FK on results
SHOW CREATE TABLE results;
-- Look for: FOREIGN KEY (`vote_id`, `organisation_id`)
--           REFERENCES `votes` (`id`, `organisation_id`)
```

- [ ] NOT NULL constraint on votes.organisation_id verified
- [ ] NOT NULL constraint on results.organisation_id verified
- [ ] Composite FK on votes verified
- [ ] Composite FK on results verified

### ✅ Test Constraint Enforcement

```bash
# Run these tests to verify constraints work
php artisan tinker

# Test 1: Try to create vote without organisation_id
>>> $vote = Vote::create(['election_id' => 1, 'voting_code' => 'TEST']);
# Expected: EXCEPTION - Integrity constraint violation
# This is GOOD - constraint is working!

# Test 2: Try to create result without organisation_id
>>> $result = Result::create(['vote_id' => 1, 'candidacy_id' => 'C1']);
# Expected: EXCEPTION - Integrity constraint violation
# This is GOOD - constraint is working!

# Exit tinker
>>> exit
```

- [ ] Test 1 threw exception (NOT NULL constraint working)
- [ ] Test 2 threw exception (NOT NULL constraint working)

### 🧪 Run Full Test Suite

```bash
# Run all tests
php artisan test

# Run specific voting tests
php artisan test tests/Feature/DemoVoteOrganisationTest.php
php artisan test tests/Feature/DemoModeTest.php

# Watch for failures related to constraints
# Some tests may fail if they weren't expecting constraint violations
# This is NORMAL and expected - tests will be updated in Phase 2
```

- [ ] Test suite runs (some may fail if not expecting constraints - OK)
- [ ] No PHP syntax errors or connection failures
- [ ] Document any test failures for Phase 2 updates

---

## Rollback (If Needed)

If something goes wrong:

```bash
# Rollback last 3 migrations
php artisan migrate:rollback --step=3

# Verify rollback worked
php artisan migrate:status | grep "2026_02_20"
# Expected: All 3 show as "Pending"

# Restore database from backup if needed
mysql -u root -p publicdigit < publicdigit_backup_2026_02_20.sql
```

- [ ] Rollback successful if needed
- [ ] Database restored from backup if needed

---

## Documentation & Handoff

After execution, complete these for handoff:

- [ ] Document execution date and time
- [ ] Verify all constraints are active
- [ ] Update deployment notes
- [ ] Inform team about new database constraints

### Example Documentation:

```
[Deployment Log]
Date: 2026-02-20 HH:MM:SS
Phase: 1 - Real Voting Enforcement Database Layer
Status: ✅ SUCCESSFUL

Migrations Applied:
1. ✅ 2026_02_20_000001_make_votes_organisation_id_not_null
2. ✅ 2026_02_20_000002_make_results_organisation_id_not_null
3. ✅ 2026_02_20_000003_add_composite_foreign_keys_to_voting_tables

Verification:
- NOT NULL constraints active on votes and results tables
- Composite foreign keys enforcing organisation_id consistency
- Database prevents cross-organisation references
- Constraint violations throw QueryException in application

Next Phase: Phase 2 - Model-level validation hooks
```

---

## Critical Notes

### ⚠️ Important

1. **These migrations are REVERSIBLE** - if you rollback, constraints are removed
2. **Database ENFORCES rules** - application code cannot bypass constraints
3. **Tests may need updates** - if they expect NULL organisation_id, they'll fail (expected)
4. **Backup is mandatory** - always backup before constraint changes
5. **Run in order** - execute migrations in numerical sequence (000001, 000002, 000003)

### 🚨 If Constraint Violations Occur

If migrations fail with constraint violations:

1. **STOP** - do not continue
2. **Backup is your safety net** - restore if needed
3. **Find NULL data**:
   ```sql
   SELECT * FROM votes WHERE organisation_id IS NULL;
   SELECT * FROM results WHERE organisation_id IS NULL;
   ```
4. **Update NULL records** before retrying migrations:
   ```sql
   -- Example: Set to organisation_id = 1
   UPDATE votes SET organisation_id = 1 WHERE organisation_id IS NULL;
   UPDATE results SET organisation_id = 1 WHERE organisation_id IS NULL;
   ```
5. **Retry migrations**

### 📊 After Successful Execution

The database will:

```
✅ PREVENT: Any vote without organisation_id
✅ PREVENT: Any result without organisation_id
✅ PREVENT: Vote referencing election from different org
✅ PREVENT: Result referencing vote from different org
✅ ENFORCE: Cascade deletes when elections/votes deleted
```

---

## Completion

Once all checks pass:

- [ ] All migrations executed
- [ ] All constraints verified
- [ ] Database backup preserved
- [ ] Tests run (expect some failures until Phase 2)
- [ ] Documentation updated
- [ ] Ready for Phase 2

**Phase 1 Status**: ✅ COMPLETE

**Next**: Phase 2 - Model-level validation hooks

---

## Quick Command Reference

```bash
# List migrations
php artisan migrate:status | grep "2026_02_20"

# Run migrations
php artisan migrate

# Check database changes
DESCRIBE votes;
DESCRIBE results;
SHOW CREATE TABLE votes;
SHOW CREATE TABLE results;

# Rollback if needed
php artisan migrate:rollback --step=3

# Run tests
php artisan test
```

---

**Questions?** Review PHASE_1_DATABASE_MIGRATIONS.md for detailed explanations.
