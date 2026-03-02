# Troubleshooting Guide

Common issues and solutions for Verifiable Anonymity voting system.

---

## Test Database Issues

### Issue 1: "Table not found" Error

**Error Message:**
```
SQLSTATE[42S02]: Table or view not found: 1146 Table 'publicdigit_testing.votes' doesn't exist
```

**Cause:** Test database tables haven't been created (migrations not run)

**Solutions:**

```bash
# Option 1: Refresh test database (recommended)
php artisan migrate:fresh --env=testing

# Option 2: Run migrations only
php artisan migrate --env=testing

# Option 3: Check migration status
php artisan migrate:status --env=testing

# Option 4: Reset everything
php artisan cache:clear
php artisan config:clear
php artisan migrate:reset --env=testing
php artisan migrate:fresh --env=testing
```

**Verification:**
```bash
# Check if tables exist
php artisan schema:show --database=testing

# Should see: votes, demo_votes, results, demo_results, etc.
```

---

### Issue 2: "Column not found" Error

**Error Message:**
```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'user_id' in 'votes'
```

**Cause:** Code is trying to access user_id column that doesn't exist (by design)

**Solution:** Remove user_id references from code

```php
// ❌ WRONG - user_id doesn't exist!
$vote->user_id = auth()->id();
$votes = Vote::where('user_id', $userId)->get();

// ✅ CORRECT - Use vote_hash for verification
$vote->vote_hash = hash('sha256', $code->user_id . ...);
$votes = Vote::where('election_id', $election->id)->get();
```

---

### Issue 3: "No such table" Error (SQLite)

**Error Message:**
```
SQLSTATE[HY000]: General error: 1 no such table: votes
```

**Cause:** SQLite in-memory database not initialized

**Solutions:**

```bash
# Use MySQL/PostgreSQL for testing instead
# In .env.testing:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_DATABASE=publicdigit_testing

# Then refresh
php artisan migrate:fresh --env=testing
```

**OR** if you must use SQLite:

```bash
# Create file-based SQLite database
touch database/testing.sqlite

# Update .env.testing
DB_CONNECTION=sqlite
DB_DATABASE=database/testing.sqlite

# Run migrations
php artisan migrate --env=testing
```

---

### Issue 4: "Cannot add or update a child row" Error

**Error Message:**
```
SQLSTATE[HY000]: General error: 1452 Cannot add or update a child row: a foreign key constraint fails
```

**Cause:** Creating result without valid vote reference

**Solution:**

```php
// ❌ WRONG - vote doesn't exist
Result::create([
    'vote_id' => 999,  // This vote doesn't exist!
    'candidate_id' => 5,
]);

// ✅ CORRECT - Create vote first
$vote = Vote::factory()->create();
Result::create([
    'vote_id' => $vote->id,
    'candidate_id' => 5,
]);
```

---

## Migration Issues

### Issue 1: "Migration Already Exists"

**Error Message:**
```
Nothing to migrate.
```

**Cause:** Migrations already applied

**Solution:**

```bash
# Check status
php artisan migrate:status

# If you need to rollback
php artisan migrate:rollback

# Then run again
php artisan migrate
```

---

### Issue 2: "Duplicate Column"

**Error Message:**
```
SQLSTATE[HY000]: General error: 1060 Duplicate column name 'vote_hash'
```

**Cause:** Column already exists (migration run twice)

**Solution:**

```bash
# Rollback the migration
php artisan migrate:rollback

# Or drop and recreate (be careful!)
php artisan migrate:reset
php artisan migrate
```

---

### Issue 3: "Cannot Drop Column"

**Error Message:**
```
SQLSTATE[HY000]: General error: 3780 Referencing column
```

**Cause:** Column is referenced by foreign key

**Solution:**

```php
// In migration down() method:
Schema::table('votes', function (Blueprint $table) {
    // Drop foreign key first
    $table->dropForeign(['user_id']);

    // Then drop column
    $table->dropColumn('user_id');
});
```

---

## Schema Issues

### Issue 1: vote_hash Column Missing

**Error Message:**
```
Column 'vote_hash' doesn't exist in votes table
```

**Cause:** Migration not applied or failed

**Verification:**
```bash
# Check schema
php artisan schema:show

# Should show vote_hash VARCHAR column

# If missing, run migration
php artisan migrate:fresh --env=testing
```

---

### Issue 2: user_id Still in Votes Table

**Error Message:**
```
Tests fail because user_id column exists (anonymity violated)
```

**Cause:** Old migration or schema not updated

**Verification:**
```sql
-- Check votes table structure
SHOW COLUMNS FROM votes;

-- Should NOT show user_id
-- Should show vote_hash

-- If user_id exists, it's a critical security issue!
```

**Solution:**

```bash
# Remove user_id column manually (if needed)
php artisan tinker

# Run in tinker
Schema::table('votes', function ($table) {
    $table->dropColumn('user_id');
});

# Or use migration
# Create migration
php artisan make:migration remove_user_id_from_votes_table

# In migration file:
public function up()
{
    Schema::table('votes', function (Blueprint $table) {
        $table->dropForeign(['user_id']);
        $table->dropColumn('user_id');
    });
}
```

---

### Issue 3: candidacy_id vs candidate_id Confusion

**Error Message:**
```
Column 'candidacy_id' doesn't exist in results table
OR
Column 'candidate_id' has NULL values (no candidates linked)
```

**Cause:** Mixed use of old (candidacy_id) and new (candidate_id) field names

**Solution:**

```php
// ❌ WRONG - Old field name
$result->candidacy_id = 5;

// ✅ CORRECT - New field name
$result->candidate_id = 5;

// Check in code:
grep -r "candidacy_id" app/
# Should show 0 results (only in old comments/documentation)
```

**Verify in Database:**
```sql
DESCRIBE results;
-- Should show: candidate_id (not candidacy_id)

-- If candidacy_id exists, it's a bug!
-- Create migration to fix it:
ALTER TABLE results CHANGE COLUMN candidacy_id candidate_id BIGINT UNSIGNED;
```

---

## Vote Creation Issues

### Issue 1: "vote_hash is NULL"

**Error Message:**
```
SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'vote_hash' cannot be null
```

**Cause:** Not generating vote_hash before creating vote

**Solution:**

```php
// ❌ WRONG - No vote_hash generated
$vote = Vote::create([
    'election_id' => $election->id,
    'candidate_01' => 5,
]);

// ✅ CORRECT - Generate vote_hash first
$vote_hash = hash('sha256',
    $code->user_id .
    $election->id .
    $code->code1 .
    now()->timestamp
);

$vote = Vote::create([
    'election_id' => $election->id,
    'vote_hash' => $vote_hash,
    'candidate_01' => 5,
    'cast_at' => now(),
]);
```

---

### Issue 2: "election_id is NULL"

**Error Message:**
```
Vote rejected: NULL election_id
OR
SQLSTATE[23000]: Integrity constraint violation: 1048 Column 'election_id' cannot be null
```

**Cause:** Not providing election_id when creating vote

**Solution:**

```php
// ❌ WRONG - No election_id
$vote = Vote::create([
    'vote_hash' => $vote_hash,
    'candidate_01' => 5,
]);

// ✅ CORRECT - Include election_id
$vote = Vote::create([
    'election_id' => $election->id,
    'vote_hash' => $vote_hash,
    'candidate_01' => 5,
]);
```

---

### Issue 3: "organisation_id Mismatch"

**Error Message:**
```
Vote organisation_id does not match election organisation_id
```

**Cause:** Vote and election have different organisations

**Solution:**

```php
// ❌ WRONG - Mismatched organisations
$election = Election::where('organisation_id', 1)->first();
$vote = Vote::create([
    'election_id' => $election->id,
    'organisation_id' => 2,  // Wrong!
]);

// ✅ CORRECT - Match election's organisation
$vote = Vote::create([
    'election_id' => $election->id,
    'organisation_id' => $election->organisation_id,
]);
```

---

### Issue 4: "Duplicate Vote Hash"

**Error Message:**
```
Duplicate entry 'a2f4b8c3...' for key 'vote_hash'
```

**Cause:** Two votes have same vote_hash (shouldn't happen)

**Reasons:**
- Same user, election, code, timestamp (exact duplicate)
- Bug in hash generation

**Solution:**

```php
// Ensure unique timestamp
$timestamp = now(); // Gets exact time including microseconds

// OR use microseconds for uniqueness
$timestamp = microtime(true);

// Generate unique hash
$vote_hash = hash('sha256',
    $code->user_id .
    $election->id .
    $code->code1 .
    $timestamp
);

// Verify uniqueness before creating
if (Vote::where('vote_hash', $vote_hash)->exists()) {
    // Generate with new timestamp
    $timestamp = now()->addMicrosecond(1);
    $vote_hash = hash(...);
}
```

---

## Vote Verification Issues

### Issue 1: "Vote Verification Always Fails"

**Error Message:**
```
Verified: false
Message: No vote found for this election
```

**Cause:** Hash mismatch during verification

**Reasons:**
1. Timestamp changed
2. Code changed
3. Hash algorithm different

**Solution:**

```php
// When CREATING vote, store exact timestamp
$timestamp = now();
$vote_hash = hash('sha256',
    $code->user_id .
    $election->id .
    $code->code1 .
    $timestamp->timestamp
);

$vote = Vote::create([
    'vote_hash' => $vote_hash,
    'cast_at' => $timestamp,  // Must match!
]);

// When VERIFYING, use exact same timestamp
$expectedHash = hash('sha256',
    $code->user_id .
    $election->id .
    $code->code1 .
    $vote->cast_at->timestamp  // Use stored timestamp!
);

$this->assertTrue(hash_equals($vote->vote_hash, $expectedHash));
```

---

### Issue 2: "Cannot Verify with Wrong Code"

**Error Message:**
```
Verified: false
Message: No vote found
```

**Expected Behavior:** This is correct! Verification should only work with the correct code.

**To Debug:**

```php
// Log hash computation
$code1 = $code->code1;
$user_id = $code->user_id;
$election_id = $code->election_id;
$timestamp = $vote->cast_at->timestamp;

$input = $user_id . $election_id . $code1 . $timestamp;
$computed_hash = hash('sha256', $input);

\Log::info('Verification Debug', [
    'computed_hash' => $computed_hash,
    'stored_hash' => $vote->vote_hash,
    'match' => hash_equals($computed_hash, $vote->vote_hash),
    'code1' => $code1,
    'timestamp' => $timestamp,
]);
```

---

## Result Aggregation Issues

### Issue 1: "candidate_id is NULL in Results"

**Error Message:**
```
Results show NULL candidate_id instead of candidate IDs
```

**Cause:** Using wrong field name or not storing candidate_id

**Solution:**

```php
// ❌ WRONG
$result->candidacy_id = $candidate_id;  // Wrong field!
// Result: candidate_id stays NULL

// ✅ CORRECT
$result->candidate_id = $candidate_id;  // Right field!
// Result: candidate_id properly populated
```

**Check in Database:**
```sql
SELECT id, candidate_id, candidacy_id FROM results LIMIT 5;
-- Should show candidate_id populated
-- candidacy_id should not exist
```

---

### Issue 2: "Results Not Aggregating"

**Error Message:**
```
Query returns individual vote records instead of aggregated counts
```

**Cause:** Missing GROUP BY in aggregation query

**Solution:**

```php
// ❌ WRONG - No aggregation
$results = Result::where('election_id', $election->id)
                 ->get();  // Returns all records

// ✅ CORRECT - Aggregated
$results = Result::where('election_id', $election->id)
                 ->selectRaw('candidate_id, COUNT(*) as vote_count')
                 ->groupBy('candidate_id')
                 ->orderByDesc('vote_count')
                 ->get();  // Returns aggregated data
```

---

### Issue 3: "Abstention Results Not Created"

**Error Message:**
```
no_vote_posts array exists but no results created for abstained posts
```

**Cause:** Not creating result records for abstained posts

**Solution:**

```php
// After creating vote, create abstention results
foreach ($vote->no_vote_posts as $post_id) {
    Result::create([
        'vote_id' => $vote->id,
        'election_id' => $election->id,
        'organisation_id' => $election->organisation_id,
        'post_id' => $post_id,
        'candidate_id' => null,  // NULL indicates abstention
        'vote_hash' => $vote->vote_hash,
        'vote_count' => 1,
    ]);
}
```

---

## API Response Issues

### Issue 1: "vote_hash_prefix Not in Response"

**Error Message:**
```
API response missing vote_hash_prefix
```

**Cause:** Old response format used

**Solution:**

```php
// ❌ OLD RESPONSE
return response()->json([
    'voting_code_used' => $code->code1,  // Wrong!
]);

// ✅ NEW RESPONSE
return response()->json([
    'vote_hash_prefix' => substr($vote->vote_hash, 0, 8) . '...',  // Right!
]);
```

---

### Issue 2: "no_vote_option Still in Response"

**Error Message:**
```
API returning no_vote_option boolean instead of no_vote_posts array
```

**Solution:**

```php
// ❌ OLD
return response()->json([
    'no_vote_option' => true,  // Boolean
]);

// ✅ NEW
return response()->json([
    'abstained_from_posts' => [3, 5, 7],  // Array
]);

// In Model:
protected $hidden = ['vote_hash'];  // Never expose hash in API

// In response:
return [
    'vote_hash_prefix' => substr($vote->vote_hash, 0, 8) . '...',  // Safe
];
```

---

### Issue 3: "user_id Exposed in API"

**Error Message:**
```
API response includes user_id (SECURITY ISSUE!)
```

**Solution:**

```php
// ❌ WRONG - Violates anonymity
return response()->json([
    'user_id' => auth()->id(),
    'vote' => $vote,
]);

// ✅ CORRECT
return response()->json([
    'vote' => $vote,  // $vote->user_id is never exposed
    // vote_hash is hidden via $hidden
]);

// Verify in Vote model
protected $hidden = ['vote_hash', 'metadata'];
```

---

## Performance Issues

### Issue 1: "Slow Vote Queries"

**Symptom:** Vote lookup takes >1 second

**Cause:** Missing index on election_id or organisation_id

**Solution:**

```sql
-- Check indexes
SHOW INDEXES FROM votes;

-- Should show indexes on:
-- - election_id
-- - organisation_id
-- - composite key (election_id, organisation_id)

-- If missing, add them:
ALTER TABLE votes ADD INDEX idx_election_id (election_id);
ALTER TABLE votes ADD INDEX idx_organisation_id (organisation_id);
```

---

### Issue 2: "Slow Result Aggregation"

**Symptom:** GROUP BY queries timeout

**Cause:** Missing index on results table

**Solution:**

```sql
-- Add indexes to results table
ALTER TABLE results ADD INDEX idx_election_id (election_id);
ALTER TABLE results ADD INDEX idx_candidate_id (candidate_id);
ALTER TABLE results ADD INDEX idx_post_id (post_id);

-- Composite index for common queries
ALTER TABLE results ADD INDEX idx_election_candidate (election_id, candidate_id);
```

---

## Security Issues

### Issue 1: "vote_hash Exposed in Database Dumps"

**Problem:** vote_hash could be extracted from database backups

**Mitigation:**
- Restrict database access (don't give everyone backup access)
- Encrypt database backups
- Use encryption at rest for database
- vote_hash itself is safe (irreversible), but combined with codes table it could reveal participation

---

### Issue 2: "user_id in votes Table" (CRITICAL!)

**Problem:** Votes table accidentally has user_id column

**Solution:**
```bash
# This is a CRITICAL SECURITY BUG!

# 1. Immediately verify
SHOW COLUMNS FROM votes;

# 2. Remove user_id immediately
php artisan migrate
# Create migration:
Schema::table('votes', function ($table) {
    $table->dropForeign(['user_id']);
    $table->dropColumn('user_id');
});

# 3. Verify removal
SHOW COLUMNS FROM votes;

# 4. Audit database for this issue
```

---

## Logging Issues

### Issue 1: "No Voting Logs"

**Problem:** Voting activity not being logged

**Solution:**

```php
// Enable logging in controller
\Log::channel('voting_security')->info('Vote created', [
    'vote_id' => $vote->id,
    'election_id' => $vote->election_id,
    'organisation_id' => $vote->organisation_id,
    'timestamp' => now(),
    'ip' => request()->ip(),
]);

// In config/logging.php:
'channels' => [
    'voting_security' => [
        'driver' => 'daily',
        'path' => storage_path('logs/voting_security.log'),
        'level' => 'info',
    ],
],

// Check logs
tail -f storage/logs/voting_security.log
```

---

## Getting Help

### Before Asking for Help

1. Check this troubleshooting guide
2. Check the test logs: `php artisan test`
3. Check application logs: `storage/logs/laravel.log`
4. Check database logs: `mysql.log` or `postgresql.log`
5. Verify schema: `php artisan schema:show`

### Information to Provide

When asking for help, provide:

```
1. Error message (exact text)
2. Steps to reproduce
3. Test output: php artisan test --filter=...
4. Schema check: php artisan schema:show
5. Code snippet showing the issue
6. Laravel version: php artisan --version
7. PHP version: php --version
8. Database: echo $DB_CONNECTION
```

### Debug Command

```bash
# Run diagnostic check
php artisan tinker

# Check schema
>>> DB::getSchemaBuilder()->getColumnListing('votes')
>>> DB::getSchemaBuilder()->getColumnListing('results')

# Check indexes
>>> DB::statement("SHOW INDEXES FROM votes")

# Test vote creation
>>> $v = Vote::factory()->create()
>>> $v->vote_hash  # Should show SHA256 hash (64 chars)
>>> $v->user_id  # Should be NULL
```

---

## Prevention Checklist

To avoid these issues:

- [ ] Always test with `php artisan test` before committing
- [ ] Never store user_id in votes table
- [ ] Always generate vote_hash before creating vote
- [ ] Always include election_id in votes
- [ ] Always include organisation_id in votes
- [ ] Use candidate_id (not candidacy_id) in results
- [ ] Never expose vote_hash in API responses
- [ ] Never expose user_id in API responses
- [ ] Always use exact timestamp for vote_hash generation and verification
- [ ] Run migrations before testing
- [ ] Check schema matches expectations
- [ ] Test vote verification works correctly

---

## Next Steps

- **Still stuck?** → Check [README.md](./README.md) for links to other docs
- **Understand testing?** → Read [06-testing-guide.md](./06-testing-guide.md)
- **Understand architecture?** → Read [02-verifiable-anonymity.md](./02-verifiable-anonymity.md)

---

**Summary:** This guide covers common issues related to database schema, migrations, vote creation, verification, results aggregation, API responses, and security. Most issues stem from forgetting to remove user_id, generate vote_hash, or using old field names (voting_code, candidacy_id, no_vote_option).
