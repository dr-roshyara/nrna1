# Phase 3: Audit Cleanup Command — COMPLETE ✅

**Status:** ✅ RED (Tests Written) | ✅ GREEN (Command Implemented) | ✅ SCHEDULED

**Date Completed:** 2026-04-15

---

## What Was Built

### Command: `audit:cleanup`

**File:** `app/Console/Commands/AuditCleanup.php`

Deletes election audit folders older than specified days (default: 30 days).

---

## Usage

### Manual Execution

```bash
# Delete folders older than 30 days (default)
php artisan audit:cleanup

# Delete folders older than 60 days (custom retention)
php artisan audit:cleanup --days=60
```

### Automatic Scheduling

**File:** `routes/console.php`

```php
Schedule::command('audit:cleanup')->dailyAt('03:00');
```

Runs automatically every day at 3:00 AM.

---

## Test Suite: 6 Tests ✅

**File:** `tests/Feature/Audit/AuditCleanupTest.php`

| Test | Purpose | Status |
|------|---------|--------|
| `test_it_deletes_folders_older_than_specified_days()` | Verify old folders deleted, recent kept | ✅ |
| `test_it_handles_empty_audit_directory()` | Graceful handling of empty directory | ✅ |
| `test_it_respects_custom_retention_days()` | Custom --days flag works | ✅ |
| `test_it_keeps_folders_within_retention_window()` | Recent folders NOT deleted | ✅ |
| `test_it_reports_deletion_count()` | Output reports correct count | ✅ |
| `test_it_handles_nonexistent_audit_directory()` | Graceful when audit dir doesn't exist | ✅ |

---

## How It Works

### Folder Structure

```
storage/logs/audit/
├── election-2026-03-01_20260301_1200/  ← 40 days old (DELETED)
│   ├── election.jsonl
│   ├── voters.jsonl
│   └── committee.jsonl
└── election-2026-04-10_20260410_1200/  ← 5 days old (KEPT)
    ├── election.jsonl
    ├── voters.jsonl
    └── committee.jsonl
```

### Algorithm

```php
1. Scan storage/logs/audit/ directory
2. For each folder:
   - Get modification timestamp
   - Compare to cutoff: now() - N days
   - If older than cutoff:
     - Delete entire folder recursively
     - Log deletion message
3. Report total deleted count
```

---

## Compliance & Data Retention

**Default Retention:** 30 days
- Allows audit trail for dispute resolution
- Complies with typical organizational audit requirements
- Configurable via --days flag

**Production Deployment:**

```bash
# Keep audit logs for 90 days instead of 30
php artisan audit:cleanup --days=90
```

Then update the schedule in `routes/console.php`:

```php
Schedule::command('audit:cleanup', ['--days' => '90'])->dailyAt('03:00');
```

---

## Command Output

### Successful Cleanup

```
$ php artisan audit:cleanup --days=30

Deleted: election-test_20260301_0900
Deleted: election-demo_20260305_1400
Deleted: election-old_20260310_2300
Cleanup complete. 3 folder(s) deleted.
```

### Empty Directory

```
$ php artisan audit:cleanup

No audit logs found.
```

### With Custom Retention

```
$ php artisan audit:cleanup --days=60

Cleanup complete. 0 folder(s) deleted.
```

---

## Integration with ElectionAuditService

**Service Creates:** `storage/logs/audit/{slug}_{YYYYMMDD}_{HHmm}/`

**Cleanup Deletes:** Folders older than --days (default 30)

**Timeline:**
```
Day 1:  ElectionAuditService logs events → folder created
Day 30: Folder 30 days old (within retention)
Day 31: Folder 31 days old → audit:cleanup DELETES it
```

---

## Database-Independent

**Critical:** This command operates on **filesystem only**.

- ✅ No database queries
- ✅ No models/migrations required
- ✅ Works when MySQL is unavailable
- ✅ Ready to deploy immediately

---

## Testing

### Run All Cleanup Tests

```bash
php artisan test tests/Feature/Audit/AuditCleanupTest.php --env=testing
```

### Run with Output

```bash
php artisan test tests/Feature/Audit/AuditCleanupTest.php -v --env=testing
```

---

## Scheduling Overview

### Daily Execution

Command runs at 03:00 AM (3 AM) daily.

**Why 3 AM?**
- Low traffic period
- After overnight audit logs are accumulated
- Before morning business hours
- No user impact

### Modifying Schedule

**File:** `routes/console.php`

```php
// Change time (2 AM instead of 3 AM)
Schedule::command('audit:cleanup')->dailyAt('02:00');

// Change frequency (twice daily)
Schedule::command('audit:cleanup')->twiceDaily(02, 14);

// Custom cron expression
Schedule::command('audit:cleanup')->cron('0 3 * * *');
```

---

## Production Checklist

- ✅ Command implemented
- ✅ Tests written (6/6 passing)
- ✅ Scheduled in routes/console.php
- ✅ Database-independent
- ✅ Handles edge cases (empty dir, nonexistent dir)
- ✅ Configurable retention (--days flag)
- ✅ Outputs deletion report
- ✅ Ready for deployment

---

## Deployment Instructions

1. **Deploy Code**
   ```bash
   git add app/Console/Commands/AuditCleanup.php routes/console.php
   git commit -m "feat: add audit:cleanup command with daily schedule"
   git push
   ```

2. **No Migrations Needed** ✅
   - Filesystem operation only

3. **Verify Scheduled Command**
   ```bash
   php artisan schedule:list
   # Should show: audit:cleanup ........... 03:00 ════════ daily
   ```

4. **Test Manual Execution**
   ```bash
   php artisan audit:cleanup --days=30
   ```

---

## Future Enhancements

**Optional (not in current spec):**

- Email notification when cleanup runs
- Archive to S3 instead of deleting
- Granular logging (how many bytes freed)
- Separate retention per election type

---

## Om Gam Ganapataye Namah 🪔🐘

*"The audit system preserves memory. Old logs are recycled. The chain continues."*

Phase 3 is complete and production-ready. When MySQL comes back, implement Phase 2 GREEN (controller wiring) to complete the full audit logging system.
