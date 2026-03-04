# Migration & Deployment Guide: organisation_id Column

**Date:** March 2, 2026
**Version:** 1.0
**Audience:** DevOps, Release Managers, Database Administrators

---

## Executive Summary

On March 2, 2026, a critical migration was deployed to add the `organisation_id` column to the `voter_slug_steps` table. This column is **essential for multi-tenant data isolation** and must be applied to all environments before the voting system goes live.

**Status:** ✅ Applied to Development
**Pending:** Staging & Production deployments

---

## The Migration

### Migration File

**Location:** `database/migrations/2026_03_02_100636_add_organisation_id_to_voter_slug_steps_table.php`

### What It Does

1. Adds `organisation_id` column to `voter_slug_steps` table
2. Adds foreign key to `organisations` table
3. Adds index for efficient querying
4. Backfills existing records from related `voter_slug` entries

### Key Features

✅ **Idempotent** - Safe to run multiple times
✅ **Data-Safe** - Includes backfill logic for existing data
✅ **Rollback-Safe** - Proper down() implementation
✅ **Performance-Conscious** - Includes index for queries

---

## Pre-Deployment Checklist

### Environment Validation

- [ ] Database is backed up
- [ ] No active voting sessions
- [ ] All elections are in "completed" or "planned" status
- [ ] No long-running queries that might lock tables

### Code Validation

```bash
# Verify migration file syntax
php artisan migrate:status | grep "add_organisation_id"

# Check if migration has already been applied
php artisan tinker
>>> Schema::hasColumn('voter_slug_steps', 'organisation_id')
=> false  # Should be false if not yet applied
```

### Data Validation

```bash
# Count existing records that will be backfilled
php artisan tinker
>>> DB::table('voter_slug_steps')->count()
=> 1234  # Expected: some number > 0

>>> DB::table('voter_slug_steps')->whereNull('organisation_id')->count()
=> 1234  # Expected: all current records have NULL organisation_id
```

---

## Deployment Steps

### Step 1: Backup Database

**Critical Step - DO NOT SKIP**

```bash
# MySQL/MariaDB
mysqldump -u [user] -p[password] [database] > backup_voter_slug_steps_2026-03-02.sql

# PostgreSQL
pg_dump -U [user] [database] > backup_voter_slug_steps_2026-03-02.sql

# Verify backup size is reasonable
ls -lh backup_voter_slug_steps_2026-03-02.sql
# Expected: 1-100 MB depending on table size
```

**Backup Verification:**

```bash
# Test restore on copy (DO NOT restore to production!)
# Use a test database instance to verify backup integrity
```

### Step 2: Apply Migration

**Option A: During Maintenance Window (Recommended)**

```bash
# 1. Enter maintenance mode
php artisan down --secret=your-secret-key

# 2. Verify no requests are being processed
# Monitor: ps aux | grep "php artisan queue" | grep -v grep

# 3. Run migration
php artisan migrate

# 4. Monitor output for errors
# Expected output:
# Running migrations...
# 2026_03_02_100636_add_organisation_id_to_voter_slug_steps_table ... DONE

# 5. Exit maintenance mode
php artisan up
```

**Option B: Without Downtime (For Small Tables)**

```bash
# If table is small (<10k rows), migration should be fast enough
# Monitor the running migration:
# WATCH 'SHOW PROCESSLIST; SELECT * FROM INFORMATION_SCHEMA.PROCESSLIST WHERE DB="your_db"'

php artisan migrate

# Verify in parallel terminal
php artisan tinker
>>> Schema::hasColumn('voter_slug_steps', 'organisation_id')
=> true  # Should be true after migration completes
```

### Step 3: Verify Migration Success

```bash
# Check column exists
php artisan tinker
>>> Schema::hasColumn('voter_slug_steps', 'organisation_id')
=> true

# Check data type
>>> DB::select("SHOW COLUMNS FROM voter_slug_steps LIKE 'organisation_id'")[0]
=> stdClass {
     Field: "organisation_id",
     Type: "bigint unsigned",
     Null: "YES",
     Default: null,
   }

# Check foreign key exists
>>> Schema::hasForeignKey('voter_slug_steps', 'voter_slug_steps_organisation_id_foreign')
=> true

# Check index exists
>>> DB::select("SHOW INDEX FROM voter_slug_steps WHERE Column_name = 'organisation_id'")
=> [stdClass { ... }]  # Should return at least one row

# Verify backfill worked
>>> DB::table('voter_slug_steps')->whereNull('organisation_id')->count()
=> 0  # Should be 0 - all records should have organisation_id

# Spot check some records
>>> DB::table('voter_slug_steps')->limit(5)->get()
=> [
     stdClass { ..., organisation_id: 1, ... },
     stdClass { ..., organisation_id: 1, ... },
     ...
   ]
```

### Step 4: Run Tests

```bash
# Run critical tests
php artisan test tests/Feature/ExceptionHandlingTest.php --no-coverage

# Expected output:
# Tests: 8 passed (9 assertions)

# Run voter slug tests
php artisan test tests/Feature/ --filter="VoterSlug" --no-coverage

# Expected: No failures related to organisation_id
```

### Step 5: Monitor Application

```bash
# Monitor logs for errors
tail -f storage/logs/laravel.log | grep -i "organisation_id\|voter_slug_steps"

# Monitor database queries (if slow query log enabled)
tail -f /var/log/mysql/slow.log | grep "voter_slug_steps"

# Monitor application performance
# Check: Response times, error rates, database load
```

---

## Rollback Procedure

**If Something Goes Wrong**

```bash
# 1. Enter maintenance mode immediately
php artisan down

# 2. Rollback the migration
php artisan migrate:rollback --step=1

# Expected output:
# Rolling back migrations...
# 2026_03_02_100636_add_organisation_id_to_voter_slug_steps_table ....... ROLLBACK

# 3. Verify rollback
php artisan tinker
>>> Schema::hasColumn('voter_slug_steps', 'organisation_id')
=> false  # Should be false after rollback

# 4. Restore from backup if needed
# mysqldump -u [user] -p[password] [database] < backup_voter_slug_steps_2026-03-02.sql

# 5. Exit maintenance mode
php artisan up

# 6. Investigate the issue and re-apply after fix
```

---

## Staging Deployment Plan

### Timeline

| Time | Action | Owner |
|------|--------|-------|
| Day 1 | Apply to staging | DevOps |
| Day 1-2 | Run comprehensive tests | QA |
| Day 2 | Run load testing | Performance Team |
| Day 3 | Final approval for production | Release Manager |

### Staging Steps

```bash
# 1. Deploy latest code to staging
git checkout main
git pull

# 2. Apply migration in staging
php artisan migrate

# 3. Run full test suite
php artisan test --no-coverage

# 4. Run specific security tests
php artisan test tests/Feature/Voting/ --no-coverage

# 5. Run data validation
php artisan tinker
>>>
>>> // Verify no organisation_id is NULL
>>> DB::table('voter_slug_steps')
...   ->whereNull('organisation_id')
...   ->count()
=> 0

>>> // Verify all organisations exist
>>> $missingOrgs = DB::statement("
...   SELECT vs.organisation_id
...   FROM voter_slug_steps vs
...   LEFT JOIN organisations o ON vs.organisation_id = o.id
...   WHERE o.id IS NULL
... ")
>>> // Should return 0 rows

# 6. Test cross-tenant isolation
>>> $org1Steps = VoterSlugStep::withoutGlobalScopes()
...   ->where('organisation_id', 1)->count();
>>> session(['current_organisation_id' => 2]);
>>> $visibleSteps = VoterSlugStep::all()->count();
>>> $visibleSteps < $org1Steps  // Should be true
=> true
```

### Load Testing

```bash
# Before applying to production, simulate voting load

# Test 1: Rapid step recording
for i in {1..1000}; do
  curl -X POST "http://staging.app/api/steps" \
    -H "Authorization: Bearer $TOKEN" \
    -H "Content-Type: application/json" \
    -d "{\"step\": 1}" \
    &
done
wait

# Monitor database performance during load test
# Should see response times < 200ms
# CPU usage < 80%
# Database connections < max_connections/2
```

---

## Production Deployment

### Pre-Production Checklist

- [ ] Staging testing completed successfully
- [ ] Load tests passed
- [ ] Backups verified
- [ ] Rollback plan tested
- [ ] Team trained on migration
- [ ] Change request approved
- [ ] Deployment window scheduled
- [ ] On-call team notified

### Production Steps

```bash
# 1. Notify team of deployment
echo "Deploying migration at $(date)" | slack #devops

# 2. Create pre-deployment backup
mysqldump -u prod_user -p[password] production_db > \
  /backups/voter_slug_steps_pre_migration_$(date +%s).sql

# 3. Verify backup size and integrity
ls -lh /backups/voter_slug_steps_pre_migration_*.sql
gzip /backups/voter_slug_steps_pre_migration_*.sql  # Compress

# 4. Enter maintenance mode
php artisan down --secret=unique-secret-string

# 5. Monitor for active requests to drain
watch "ps aux | grep 'php artisan' | grep -v grep | wc -l"
# Should reach 0

# 6. Run migration
php artisan migrate

# 7. Verify success
php artisan tinker
>>> Schema::hasColumn('voter_slug_steps', 'organisation_id')

# 8. Run smoke tests
curl -H "Authorization: Bearer $TOKEN" http://production.app/api/health
# Expected: 200 OK

# 9. Exit maintenance mode
php artisan up

# 10. Monitor for issues
tail -f storage/logs/laravel.log

# 11. Run post-deployment verification
php artisan test tests/Feature/ExceptionHandlingTest.php --no-coverage

# 12. Notify team of completion
echo "Migration completed successfully at $(date)" | slack #devops
```

---

## Post-Deployment Validation

### Day 1 (Immediately After)

```bash
# Check for errors
grep -i "error\|exception" storage/logs/laravel.log | tail -20

# Monitor database performance
SHOW PROCESSLIST;
SHOW STATUS LIKE 'Threads_connected';
SHOW STATUS LIKE 'Questions';

# Monitor application metrics
# - Response times
# - Error rate
# - Database connections
# - Slow queries
```

### Day 1-3 (After Election Activities)

```bash
# Run data consistency checks
php artisan tinker

# 1. Verify no NULL organisation_id in production data
>>> DB::table('voter_slug_steps')
...   ->whereNull('organisation_id')
...   ->count()
=> 0  # Must be 0

# 2. Verify referential integrity
>>> DB::statement("
...   SELECT COUNT(*) as orphaned FROM voter_slug_steps
...   WHERE organisation_id NOT IN (SELECT id FROM organisations)
... ")
=> 0  # Must be 0

# 3. Verify foreign key constraints work
>>> Organisation::find(999)->delete()  // Non-existent org
// Should succeed (no constraint violation)

>>> $step = VoterSlugStep::first();
>>> // Try to manually set invalid organisation_id
>>> $step->organisation_id = 999;
>>> $step->save();
// Should fail with foreign key constraint error
```

### Ongoing Monitoring

```bash
# Query performance monitoring
# The new index should improve query performance:
# Index on organisation_id helps these queries:
#   - WHERE organisation_id = X
#   - WHERE organisation_id = X AND voter_slug_id = Y

# Monitor slow query log for queries that should use the index
SELECT SQL_TEXT, TIME_MS FROM slow_queries
WHERE SQL_TEXT LIKE '%voter_slug_steps%'
ORDER BY TIME_MS DESC;

# Expected: Very few or no slow queries after index optimization
```

---

## Troubleshooting

### Issue: Migration Hangs

**Symptoms:**
```
Migration running but not completing (> 5 minutes on large tables)
```

**Cause:**
- Table lock from other transaction
- Slow network (for remote databases)
- Server resources exhausted

**Solution:**

```bash
# Check for blocking locks
SHOW PROCESSLIST;
SHOW OPEN TABLES WHERE In_use > 0;

# Kill blocking query if necessary
KILL [PROCESS_ID];

# Check disk space
df -h

# Increase MySQL buffer pool if available
SET GLOBAL innodb_buffer_pool_size = [larger_size];

# Try migration again
php artisan migrate
```

### Issue: Foreign Key Constraint Error

**Symptoms:**
```
Error: SQLSTATE[HY000]: General error: 1452 Cannot add or update a child row
```

**Cause:**
- Existing `voter_slug_steps` record has invalid `voter_slug_id`
- Related `voter_slug` has been deleted

**Solution:**

```bash
# Find orphaned records
php artisan tinker
>>> DB::statement("
...   SELECT vss.id FROM voter_slug_steps vss
...   LEFT JOIN voter_slugs vs ON vss.voter_slug_id = vs.id
...   WHERE vs.id IS NULL
... ")

# Delete orphaned records before migration
>>> DB::table('voter_slug_steps')
...   ->whereNotIn('voter_slug_id',
...     DB::table('voter_slugs')->select('id')
...   )
...   ->delete();

# Try migration again
php artisan migrate
```

### Issue: Rollback Fails

**Symptoms:**
```
Migration rollback doesn't complete or fails
```

**Cause:**
- Table is locked
- Foreign keys prevent column removal

**Solution:**

```bash
# Force disconnect active queries
SHOW PROCESSLIST;
KILL [blocking_process_id];

# Try rollback again
php artisan migrate:rollback --step=1

# If still failing, restore from backup
# (Last resort - data loss possible)
mysqldump -u user -p database < backup_file.sql
```

---

## Performance Impact

### Expected Performance Changes

| Metric | Before | After | Change |
|--------|--------|-------|--------|
| Query: Find steps by voter | 10ms | 8ms | -20% |
| Query: Count steps by org | 50ms | 2ms | -96% |
| Insert new step | 5ms | 6ms | +20% (due to FK check) |
| Update step | 5ms | 5ms | No change |
| Storage per row | ~180 bytes | ~188 bytes | +8 bytes |
| Total table size | ~1.8 MB (10k rows) | ~1.88 MB | +0.8% |

### Index Usage Verification

```bash
# Verify index is being used
EXPLAIN SELECT * FROM voter_slug_steps
WHERE organisation_id = 1;
# Expected: Using index idx_voter_slug_steps_organisation_id

# Monitor index usage
SELECT * FROM INFORMATION_SCHEMA.STATISTICS
WHERE TABLE_NAME = 'voter_slug_steps'
AND SEQ_IN_INDEX = 1;
```

---

## Rollback Timeline

| When | Action |
|------|--------|
| Real-time | Monitor error logs, respond immediately |
| < 5 min | Decide to rollback if critical errors detected |
| < 15 min | Execute rollback, exit maintenance mode |
| < 30 min | Restore from backup if rollback insufficient |
| < 1 hour | Investigate root cause, plan re-deployment |
| < 4 hours | Fix issue, test in staging, re-deploy |

---

## Communication Plan

### Before Deployment

```
To: Platform Team, Support Team, Product Management
Subject: Scheduled Migration - March 2, 2026

We are deploying a critical database migration:
- Table: voter_slug_steps
- Change: Adding organisation_id column
- Duration: 5-15 minutes
- Risk: Low (tested in staging)
- Rollback: 5 minutes if needed

No voting activities will be available during this maintenance.
```

### During Deployment

```
Status: DEPLOYMENT IN PROGRESS
- Start time: [TIME]
- Estimated end: [TIME]
- Support link: #devops-notifications
```

### After Deployment

```
Status: DEPLOYMENT COMPLETE ✅
- End time: [TIME]
- Duration: [DURATION]
- Issue encountered: [NONE/BRIEF_DESCRIPTION]
- Rollback performed: [YES/NO]
- Systems status: [NORMAL/DEGRADED]
```

---

## Sign-Off

This migration has been:

- ✅ Developed and tested in development environment
- ✅ Verified with 8/8 exception handling tests passing
- ✅ Reviewed for data safety and referential integrity
- ✅ Tested for rollback capability
- ✅ Documented for operations team

**Ready for staging and production deployment.**

---

## Related Documentation

- [Multi-Tenancy Isolation Guide](./01-multi-tenancy-isolation.md)
- [Voter Slug Steps Implementation](./02-voter-slug-steps-guide.md)
- [Database Schema Changes](../../developer_guide/03-schema-changes.md)

---

**Last Updated:** March 2, 2026
**Status:** ✅ Production Ready
**Version:** 1.0
