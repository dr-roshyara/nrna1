# Troubleshooting Guide

## Common Issues and Solutions

### Issue: "Call to undefined method wants_to_vote"

**Symptom:**
```
BadMethodCallException: Call to undefined method Illuminate\Database\Eloquent\Builder::wants_to_vote()
```

**Cause:** Using `wants_to_vote` as a scope when it's a column

**Solution:**
```php
// ❌ WRONG
$users = User::wants_to_vote(true)->get();

// ✅ CORRECT - Use scope
$users = User::where('wants_to_vote', true)->get();

// ✅ CORRECT - Use predefined scope
$customers = User::customers()->get();
```

---

### Issue: "Column 'wants_to_vote' doesn't exist"

**Symptom:**
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'wants_to_vote'
```

**Cause:** Migration hasn't been run yet

**Solution:**
```bash
# Check migration status
php artisan migrate:status

# Run pending migrations
php artisan migrate

# Verify column exists
php artisan tinker
>>> DB::getSchemaBuilder()->hasColumn('users', 'wants_to_vote')
// Should return: true
```

---

### Issue: Elections table empty after migration

**Symptom:**
```
Election count: 0
No demo or real election exists
```

**Cause:** Migration ran but seeder wasn't executed

**Solution:**
```bash
# Run seeder
php artisan db:seed --class=ElectionSeeder

# Verify
php artisan tinker
>>> App\Models\Election::count()  // Should be 2
```

---

### Issue: "User wants_to_vote always false"

**Symptom:**
```
$user->wants_to_vote  // returns 0 or false
$user->isCustomer()   // returns true, but should be false
```

**Cause:** Column was added but data wasn't populated, or mass assignment is blocking

**Solution:**
```bash
# Check if data needs population
php artisan tinker
>>> User::where('is_voter', 1)->where('can_vote', 1)->count()
>>> User::where('wants_to_vote', true)->count()
# Compare - if different, data needs migration

# Manually populate (run migration again or manually update)
>>> DB::table('users')
    ->where('is_voter', 1)
    ->where('can_vote', 1)
    ->update(['wants_to_vote' => true]);
```

---

### Issue: "Class Election not found"

**Symptom:**
```
Fatal error: Class 'App\Models\Election' not found
```

**Cause:** Model file not created or namespace issue

**Solution:**
```bash
# Check file exists
ls app/Models/Election.php

# Check namespace
cat app/Models/Election.php | grep "namespace"
// Should show: namespace App\Models;

# Clear autoloader
composer dump-autoload

# Try again
php artisan tinker
>>> App\Models\Election::count()
```

---

### Issue: "VoterRegistration model not working"

**Symptom:**
```
Error: Call to undefined method voterRegistrations()
```

**Cause:** Relationship not defined in User model

**Solution:**
```bash
# Check User model has relationship
grep -n "voterRegistrations" app/Models/User.php
// Should show: public function voterRegistrations()

# If not found, add it
# See user-registration-system.md for code
```

---

### Issue: "User can register twice for same election"

**Symptom:**
```
$user->registerForDemoElection(1)  // First time
$user->registerForDemoElection(1)  // Second time - should prevent
// Both succeed, creating 2 registrations
```

**Cause:** Unique constraint not enforced, or check missing

**Solution:**
```bash
# Check unique constraint exists
php artisan tinker
>>> $indexes = DB::connection()
    ->getDoctrineSchemaManager()
    ->listTableIndexes('voter_registrations');
>>> foreach($indexes as $idx) { echo $idx->getName() . "\n"; }
// Should see: unique_user_election

# If not found, verify migration ran
php artisan migrate:status

# Or add check in code:
$existing = VoterRegistration::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->first();
if ($existing) {
    return $existing;  // Return existing instead of creating new
}
```

---

### Issue: "Queries returning no results"

**Symptom:**
```
$pending = User::pendingVoters()->get()  // Empty result
$approved = User::approvedVoters()->get() // Empty result
// But users exist
```

**Cause:** Data doesn't match scope criteria

**Debug:**
```php
// Check actual values
$user = User::first();
dd([
    'wants_to_vote' => $user->wants_to_vote,
    'is_voter' => $user->is_voter,
    'can_vote' => $user->can_vote,
    'is_committee_member' => $user->is_committee_member,
]);

// Check scope logic
dd($user->isCustomer());     // Should match your expectation
dd($user->isPendingVoter()); // Should match your expectation
dd($user->isApprovedVoter());// Should match your expectation
```

**Fix:** Update user state if needed
```php
// Set as pending voter
$user->wants_to_vote = true;
$user->is_voter = 0;
$user->can_vote = 0;
$user->save();
```

---

### Issue: "Voter approval not updating database"

**Symptom:**
```
$registration->approve('Admin')  // No error
$registration->status  // Still 'pending'
// Not updated
```

**Cause:** Not using action method correctly, or not calling fresh()

**Solution:**
```php
// ❌ WRONG
$registration->approve('Admin');
echo $registration->status;  // Still pending (old instance)

// ✅ CORRECT - Call fresh()
$registration->approve('Admin');
echo $registration->fresh()->status;  // 'approved'

// ✅ CORRECT - Store result
$registration = $registration->fresh();
echo $registration->status;  // 'approved'
```

---

### Issue: "Index too long" error during migration

**Symptom:**
```
SQLSTATE[HY000]: General error: 1071
Specified key was too long; max key length is 3072 bytes
```

**Cause:** Columns in index are too large

**Solution:**
This shouldn't happen with our schema, but if it does:

```bash
# Check column definitions
DESCRIBE voter_registrations;

# If varchar columns are too large, reduce size or use prefix index
ALTER TABLE voter_registrations
ADD INDEX idx_user_type (user_id, election_type(10));

# Or drop and recreate with proper definitions
DROP TABLE voter_registrations;
# Then re-run migration with corrected column sizes
```

---

### Issue: "Foreign key constraint fails"

**Symptom:**
```
SQLSTATE[HY000]: General error: 1452
Cannot add or update a child row: a foreign key constraint fails
```

**Cause:** Trying to insert voter_registrations with non-existent user_id or election_id

**Solution:**
This shouldn't happen as we don't use foreign keys. If it does:

```bash
# Check for orphaned references
php artisan tinker

# Find orphaned user_ids
>>> DB::table('voter_registrations')
    ->whereNotIn('user_id', DB::table('users')->pluck('id'))
    ->get();

# Find orphaned election_ids
>>> DB::table('voter_registrations')
    ->whereNotIn('election_id', DB::table('elections')->pluck('id'))
    ->get();

# Delete orphans
>>> DB::table('voter_registrations')
    ->whereNotIn('user_id', DB::table('users')->pluck('id'))
    ->delete();
```

---

### Issue: "Pagination not working with scopes"

**Symptom:**
```
$pending = User::pendingVoters()->paginate(20)
// Error or unexpected results
```

**Cause:** Scope might interfere with pagination

**Solution:**
```php
// ✅ CORRECT - Scope first, then paginate
$pending = User::pendingVoters()
    ->paginate(20);

// If having issues, try explicit query builder
$pending = User::where('wants_to_vote', true)
    ->where('is_voter', 0)
    ->where('can_vote', 0)
    ->where('is_committee_member', 0)
    ->paginate(20);
```

---

### Issue: "Metadata JSON field not saving"

**Symptom:**
```
$registration->metadata = ['ip' => '192.168.1.1'];
$registration->save();
// Metadata is null or not saved
```

**Cause:** JSON field needs explicit casting

**Solution:**
Check VoterRegistration model has casting:

```php
// app/Models/VoterRegistration.php
protected $casts = [
    'metadata' => 'array',  // This is needed
    'registered_at' => 'datetime',
    'approved_at' => 'datetime',
    'voted_at' => 'datetime'
];
```

If casting is there:
```php
// ✅ CORRECT
$registration->metadata = ['ip' => '192.168.1.1'];
$registration->save();

// ✅ ALSO WORKS
$registration->update(['metadata' => ['ip' => '192.168.1.1']]);
```

---

### Issue: "Mass assignment error on wants_to_vote"

**Symptom:**
```
Add [wants_to_vote] to fillable property to allow mass assignment on [App\Models\User]
```

**Cause:** `wants_to_vote` is in `$guarded`, not `$fillable`

**Solution:**
```php
// ❌ WRONG - Can't mass assign
User::create(['wants_to_vote' => true, ...]);

// ✅ CORRECT - Use direct assignment
$user = User::create([...]);
$user->wants_to_vote = true;
$user->save();

// ✅ CORRECT - Use update
$user->update(['wants_to_vote' => true]);

// ✅ CORRECT - Use query
User::find($id)->update(['wants_to_vote' => true]);
```

---

### Issue: "Scope not defined" error

**Symptom:**
```
BadMethodCallException: Call to undefined method Illuminate\Database\Eloquent\Builder::customers()
```

**Cause:** Scope method not in model

**Solution:**
```bash
# Check scope exists in User model
grep -n "scopeCustomers" app/Models/User.php

# If not found, add it
# See voter-registration-system.md for code

# Clear Laravel cache
php artisan cache:clear
php artisan config:clear
```

---

### Issue: "Timestamps not updating"

**Symptom:**
```
$registration->approve('Admin')
$registration->approved_at  // Still null
// Should have timestamp
```

**Cause:** Approved_at not being set by approve() method

**Solution:**
Check VoterRegistration::approve() method:

```php
public function approve(string $approvedBy, array $metadata = []): self
{
    $this->update([
        'status' => 'approved',
        'approved_at' => now(),  // This line must be there
        'approved_by' => $approvedBy,
        ...
    ]);
    return $this;
}
```

If method is correct:
```php
// ✅ Verify it's being called
$registration->approve('Admin');
$registration->fresh()->approved_at;  // Check with fresh()
```

---

### Issue: "N+1 Query Problem"

**Symptom:**
```
Debugbar showing 1000+ queries when loading 100 registrations
Performance very slow
```

**Cause:** Not using eager loading with relationships

**Solution:**
```php
// ❌ SLOW: N+1 queries
foreach (VoterRegistration::all() as $reg) {
    echo $reg->user->name;      // +1 query per registration
    echo $reg->election->name;  // +1 query per registration
}

// ✅ FAST: Eager load
foreach (VoterRegistration::with('user', 'election')->get() as $reg) {
    echo $reg->user->name;      // No extra queries
    echo $reg->election->name;  // No extra queries
}

// ✅ EVEN BETTER: With pagination and limit
$registrations = VoterRegistration::with('user', 'election')
    ->paginate(20);
```

---

### Issue: "Deleted election still referenced"

**Symptom:**
```
Election was deleted
voter_registrations still have election_id pointing to it
Queries fail
```

**Cause:** No foreign key constraint to cascade delete

**Solution:**
```bash
# Check for orphaned records
php artisan tinker
>>> DB::table('voter_registrations')
    ->whereNotIn('election_id', DB::table('elections')->pluck('id'))
    ->count();

# Manually clean up
>>> DB::table('voter_registrations')
    ->whereNotIn('election_id', DB::table('elections')->pluck('id'))
    ->delete();

# Or use cascade delete in code before deleting election
>>> $election = Election::find($id);
>>> $election->voterRegistrations()->delete();
>>> $election->delete();
```

---

### Issue: "User in multiple states simultaneously"

**Symptom:**
```
$user->isCustomer()      // true
$user->isPendingVoter()  // true (both shouldn't be true!)
$user->isApprovedVoter() // false
```

**Cause:** Inconsistent data - wants_to_vote doesn't match is_voter/can_vote

**Solution:**
```bash
# Check actual values
php artisan tinker
>>> $user = User::find($id);
>>> dd([
    'wants_to_vote' => $user->wants_to_vote,
    'is_voter' => $user->is_voter,
    'can_vote' => $user->can_vote,
    'is_committee_member' => $user->is_committee_member,
]);

# Fix inconsistency
# Determine what state user should be in, then update:
>>> if ($approved) {
    $user->update(['wants_to_vote' => true, 'is_voter' => 1, 'can_vote' => 1]);
} else {
    $user->update(['wants_to_vote' => false, 'is_voter' => 0, 'can_vote' => 0]);
}
```

---

## Performance Debugging

### Check Query Performance

```bash
php artisan tinker

# Enable query logging
>>> DB::enableQueryLog();

# Run query
>>> $results = User::pendingVoters()->get();

# Check queries and timing
>>> foreach (DB::getQueryLog() as $query) {
    echo $query['query'] . " (" . $query['time'] . "ms)\n";
}
```

### Check Index Usage

```sql
-- See if query uses index
EXPLAIN SELECT * FROM voter_registrations
WHERE election_id = 1 AND status = 'pending';

-- Check index cardinality
SHOW INDEX FROM voter_registrations;

-- Optimize table
OPTIMIZE TABLE voter_registrations;
```

---

## Database Debugging

### Check Table Integrity

```bash
php artisan tinker

# Check for orphaned data
>>> $orphanedRegs = DB::table('voter_registrations')
    ->whereNotIn('user_id', DB::table('users').pluck('id'))
    ->count();
>>> echo "Orphaned registrations: " . $orphanedRegs;

# Check for duplicate registrations
>>> $duplicates = DB::table('voter_registrations')
    ->selectRaw('user_id, election_id, COUNT(*) as count')
    ->groupBy('user_id', 'election_id')
    ->having('count', '>', 1)
    ->count();
>>> echo "Duplicate registrations: " . $duplicates;
```

---

## Memory & Performance Issues

### If Running Out of Memory

```php
// ❌ WRONG: Loads everything into memory
$all = VoterRegistration::all();

// ✅ CORRECT: Use chunk
VoterRegistration::chunk(1000, function($registrations) {
    foreach ($registrations as $reg) {
        // Process
    }
});
```

### If Queries Too Slow

```php
// ❌ WRONG: Fetches all columns
$registrations = VoterRegistration::get();

// ✅ CORRECT: Select only needed
$registrations = VoterRegistration::select('id', 'user_id', 'status')
    ->get();
```

---

## Recovery Procedures

### Restore from Backup

```bash
# Export backup before making changes
mysqldump -u user -p database > backup_$(date +%Y%m%d_%H%M%S).sql

# Restore if needed
mysql -u user -p database < backup_YYYYMMDD_HHMMSS.sql
```

### Rebuild Indexes

```sql
REPAIR TABLE voter_registrations;
OPTIMIZE TABLE voter_registrations;
ANALYZE TABLE voter_registrations;
```

### Reset to Clean State

```bash
# Rollback all migrations
php artisan migrate:refresh

# Restart with clean database
php artisan migrate
php artisan db:seed --class=ElectionSeeder
```

---

## Getting Help

### Gather Debug Information

```bash
# Create debug report
php artisan tinker

echo "=== PHP VERSION ===" . PHP_VERSION;
echo "=== LARAVEL VERSION ===" . App::VERSION();
echo "=== DATABASE ===" . DB::connection()->getDatabaseName();

# Check all tables
echo "=== TABLES ==="
DB::getSchemaBuilder()->getTables();

# Check migrations
php artisan migrate:status
```

### Enable Debug Mode

```bash
# In .env
APP_DEBUG=true

# In config/app.php
'debug' => env('APP_DEBUG', false),
```

### Check Logs

```bash
# View recent errors
tail -f storage/logs/laravel.log

# Search for specific errors
grep "wants_to_vote" storage/logs/laravel.log
```

---

## Quick Reference

| Problem | Solution |
|---------|----------|
| "Column not found" | Run `php artisan migrate` |
| "Class not found" | Run `composer dump-autoload` |
| "Scope not working" | Check model has scope method |
| "Slow queries" | Use `with()` for eager loading |
| "Wrong results" | Verify data with `dd()` |
| "Mass assignment error" | Use `update()` instead of `create()` |
| "Always getting false" | Check `$casts` array in model |
| "Too many queries" | Enable query logging, check N+1 |

---

## References

- Laravel Troubleshooting
- Database Schema (see `database-schema.md`)
- Query Examples (see `query-examples.md`)
- Election System (see `election-system.md`)
- Voter System (see `voter-registration-system.md`)
