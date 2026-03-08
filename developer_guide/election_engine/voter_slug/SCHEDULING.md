# Background Job Scheduling Guide

## Voter Slug Cleanup Command

### Command Name
voting:clean-expired-slugs

### Purpose
Remove old, expired voter slug records from database to maintain performance.

### Command Signature
php artisan voting:clean-expired-slugs {--hours=24} {--detailed}

### Parameters

--hours=24 (Optional)
- Default: 24 hours
- Meaning: Remove slugs that expired more than 24 hours ago
- Example: --hours=7 removes slugs older than 7 days

--detailed (Optional)
- Default: Not shown
- Shows: Detailed removal information per table
- Example: Lists count of soft-deleted real slugs removed, demo slugs removed

### Usage Examples

Default cleanup (remove slugs older than 24 hours):
php artisan voting:clean-expired-slugs

Remove slugs older than 7 days:
php artisan voting:clean-expired-slugs --hours=168

Show detailed output:
php artisan voting:clean-expired-slugs --detailed

Detailed cleanup for 7 days:
php artisan voting:clean-expired-slugs --hours=168 --detailed

---

## Scheduling in Production

### Using Laravel Scheduler

Edit: app/Console/Kernel.php

Add to protected function schedule(Schedule $schedule):

$schedule->command('voting:clean-expired-slugs --hours=24')
    ->dailyAt('02:00')
    ->withoutOverlapping();

This runs:
- Daily (every 24 hours)
- At 2:00 AM
- Only if previous run completed (withoutOverlapping)

### Scheduler Details

More aggressive cleanup (run twice daily):
$schedule->command('voting:clean-expired-slugs --hours=12')
    ->twiceDaily(02, 14);  // 2 AM and 2 PM

Weekly cleanup of old records:
$schedule->command('voting:clean-expired-slugs --hours=168')
    ->weekly()
    ->mondays()
    ->at('03:00');

Custom cleanup with logging:
$schedule->command('voting:clean-expired-slugs --hours=24 --detailed')
    ->dailyAt('02:00')
    ->sendOutputTo(storage_path('logs/cleanup.log'))
    ->emailOutputOnFailure('admin@example.com');

---

## What Gets Cleaned

### Real Elections (voter_slugs table)

Soft-deleted records only:
- WHERE deleted_at IS NOT NULL
- AND expires_at < (now - hours)

These are preserved for audit trail until old enough.

### Demo Elections (demo_voter_slugs table)

Any records matching:
- WHERE expires_at < (now - hours)

Demo records are not kept for audit, so deleted immediately.

### Database Impact

Average cleanup (24 hours):
- Real elections: Delete old soft-deletes (audit trail preserved)
- Demo elections: Delete old demo records

Database size reduced by removing stale records.

---

## Monitoring Cleanup Job

### Check Job Execution

In Laravel's logs:
storage/logs/laravel.log

Look for:
[TIMESTAMP] INFO Voter slugs cleanup completed

Example log output:
[2026-03-08 02:00:15] local.INFO: Voter slugs cleanup completed
{
  "real_deleted": 42,
  "demo_deleted": 127,
  "total_deleted": 169,
  "cutoff_time": "2026-03-07 02:00:15"
}

### Enable Detailed Logging

In .env:
LOG_LEVEL=info

Then run with --detailed flag:
php artisan voting:clean-expired-slugs --detailed --verbose

Output shows:
- Found N soft-deleted real voter slugs
- Permanently deleted N real voter slugs
- Found N demo voter slugs
- Deleted N demo voter slugs

---

## Troubleshooting Scheduler

### Job Not Running

Check cron daemon:
crontab -l  # List cron jobs
sudo service cron status  # Check if running

If not running, restart:
sudo service cron restart

### Manual Testing

Run command manually to verify:
php artisan voting:clean-expired-slugs --detailed

Check output matches expected behavior.

### Schedule List

View all scheduled commands:
php artisan schedule:list

Shows:
- Command name
- Frequency (daily, weekly, etc.)
- Time
- Last run status

### Run Scheduler in Foreground (Testing)

For development/testing:
php artisan schedule:work

This runs scheduler in foreground and executes commands as scheduled.

Useful for:
- Testing schedule timing
- Verifying command output
- Development/debugging

---

## Performance Considerations

### Database Load

Cleanup queries:
- SELECT * FROM voter_slugs WHERE deleted_at IS NOT NULL AND expires_at < ?
- DELETE FROM voter_slugs WHERE id IN (...)

For large tables:
- Consider running at off-peak hours (2 AM)
- Use transaction protection (built-in)

### Index Recommendation

Create index for cleanup query:
ALTER TABLE voter_slugs 
ADD INDEX idx_cleanup (deleted_at, expires_at);

Speeds up finding expired soft-deletes.

### Long-Running Elections

If elections run longer than cleanup period:
- Increase --hours parameter
- Or run cleanup less frequently

Example: For month-long election:
php artisan voting:clean-expired-slugs --hours=720  # 30 days

---

## Production Deployment

### Pre-Deployment Verification

1. Test command runs:
   php artisan voting:clean-expired-slugs --detailed

2. Check expected deletions:
   SELECT COUNT(*) FROM voter_slugs 
   WHERE deleted_at IS NOT NULL AND expires_at < (NOW() - INTERVAL 24 HOUR);

3. Verify no active elections will be affected:
   SELECT COUNT(*) FROM voter_slugs 
   WHERE is_active = true AND expires_at < (NOW() - INTERVAL 24 HOUR);

### Deployment Steps

1. Deploy code with updated Kernel.php

2. Verify scheduler is running on production:
   Check crontab entries for Laravel scheduler

3. Monitor first run:
   tail -f storage/logs/laravel.log | grep cleanup

4. Set log alerts:
   Alert if cleanup fails (via emailOutputOnFailure)

### Rollback

If cleanup causes issues:

1. Disable in Kernel.php:
   // $schedule->command('voting:clean-expired-slugs')...

2. Re-enable after fix:
   $schedule->command('voting:clean-expired-slugs --hours=24')...

---

## Configuration

### Environment Variables (Optional)

Add to .env if custom defaults needed:
VOTING_CLEANUP_HOURS=24

Then use in Kernel.php:
$schedule->command('voting:clean-expired-slugs --hours=' . config('voting.cleanup_hours'))
    ->dailyAt('02:00');

### Command Configuration

All defaults are built into command.

Override via command line:
php artisan voting:clean-expired-slugs --hours=168 --detailed

---

## Monitoring Dashboard

### Key Metrics

Track in your monitoring system:
- Count of active voter_slugs
- Count of soft-deleted voter_slugs
- Age of oldest soft-deleted slug
- Cleanup command runtime

Query to monitor:
```sql
SELECT 
  'active' as status,
  COUNT(*) as count,
  MAX(updated_at) as last_activity
FROM voter_slugs
WHERE deleted_at IS NULL

UNION

SELECT 
  'soft_deleted' as status,
  COUNT(*) as count,
  MAX(deleted_at) as last_delete
FROM voter_slugs
WHERE deleted_at IS NOT NULL;
```

---

## Related Documentation

- README.md: Overview and quick start
- ARCHITECTURE.md: System design and background job layer
- SECURITY.md: Data retention and audit trails
- TROUBLESHOOTING.md: Common issues

