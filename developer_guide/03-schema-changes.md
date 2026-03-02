# Schema Changes Documentation

## Evolution of the Voting System

This document traces the database schema changes made to implement Verifiable Anonymity and explains the rationale behind each change.

---

## Critical Change: Removal of user_id from Votes Table

### The Decision

```
❌ OLD: votes table with user_id
✅ NEW: votes table with vote_hash (NO user_id)
```

### Why This Change?

**Problem with user_id:**
- Storing user_id in votes violates voter privacy
- Anyone with database access knows exactly how each person voted
- Enables voter coercion and discrimination
- Makes election results unsafe

**Solution with vote_hash:**
- vote_hash is SHA256(user_id + election_id + code + timestamp)
- No user_id stored (anonymity preserved)
- Voters can still verify participation (via hash regeneration)
- No one can determine voting choices from votes table

---

## Schema Comparison: Old vs New

### Votes Table

| Field | Old Schema | New Schema | Change Type | Rationale |
|-------|-----------|-----------|-------------|-----------|
| `id` | BIGINT | BIGINT | No change | Primary key |
| `user_id` | BIGINT (FK) | ❌ REMOVED | Removal | Enforces anonymity |
| `election_id` | ❌ Missing | BIGINT | Added | Scope votes per election |
| `organisation_id` | VARCHAR | BIGINT | Type change | Multi-tenant support |
| `voting_code` | VARCHAR | ❌ RENAMED | Rename | `voting_code` → `vote_hash` |
| `no_vote_option` | TINYINT (bool) | ❌ RENAMED | Rename | `no_vote_option` → `no_vote_posts` |
| `no_vote_posts` | N/A | JSON | New | Array of post IDs for abstention |
| `candidate_*` | VARCHAR | VARCHAR | No change | Vote selections 01-60 |
| `cast_at` | DATETIME | DATETIME | No change | Vote submission timestamp |
| `metadata` | N/A | JSON | New | Additional verification data |
| `created_at` | TIMESTAMP | TIMESTAMP | No change | Record creation |
| `updated_at` | TIMESTAMP | TIMESTAMP | No change | Record update |

### Results Table

| Field | Old Schema | New Schema | Change Type | Rationale |
|-------|-----------|-----------|-------------|-----------|
| `id` | BIGINT | BIGINT | No change | Primary key |
| `vote_id` | BIGINT (FK) | BIGINT (FK) | No change | Reference to vote |
| `election_id` | ❌ Missing | BIGINT | Added | Scope results per election |
| `organisation_id` | ❌ Missing | BIGINT | Added | Multi-tenant support |
| `post_id` | VARCHAR/INT | BIGINT | Type change | Standardized to BIGINT |
| `candidacy_id` | VARCHAR | ❌ RENAMED | Rename | `candidacy_id` → `candidate_id` |
| `candidate_id` | N/A | BIGINT | New | References candidacies.id |
| `vote_hash` | N/A | VARCHAR | New | Copy from vote for verification |
| `vote_count` | N/A | INT | New | Count aggregation (default 1) |
| `created_at` | TIMESTAMP | TIMESTAMP | No change | Record creation |
| `updated_at` | TIMESTAMP | TIMESTAMP | No change | Record update |

---

## Migration Timeline

### Phase 1: Foundation Migrations (Before Jan 2026)

#### Migration: 2025_08_05_162701_remove_user_foreign_key_from_votes.php
```php
// Remove the foreign key that linked votes to users
Schema::table('votes', function (Blueprint $table) {
    $table->dropForeign(['user_id']);  // Remove FK constraint
});

// REASON: Prepare for complete user_id removal
// This was step 1 of breaking the user->votes linkage
```

### Phase 2: Elections & Scoping (Feb 2026)

#### Migration: 2026_02_03_194200_add_election_id_to_votes_table.php
```php
// Add election_id to scope votes per election
Schema::table('votes', function (Blueprint $table) {
    $table->unsignedBigInteger('election_id')
          ->default(1)
          ->after('id')
          ->comment('Reference to elections table');

    $table->index('election_id');
});

// REASON: Support multiple elections
// election_id enables filtering votes by election
// Paired with demo_votes table for demo mode
```

#### Migration: 2026_02_03_194350_add_election_id_to_results_table.php
```php
// Add election_id to results table for consistency
Schema::table('results', function (Blueprint $table) {
    $table->unsignedBigInteger('election_id')
          ->default(1)
          ->after('id');

    $table->index('election_id');
});

// REASON: Scope results per election
// Enables querying results for specific elections
// Supports multi-election scenarios
```

### Phase 3: Verifiable Anonymity Implementation (Mar 2026)

#### Key Migration: Add vote_hash, remove voting_code
**Status:** Applied via model transformations (not explicit migration)

```php
// CONCEPTUAL: What actually happened

// OLD column: voting_code (hashed voter code)
// NEW column: vote_hash (cryptographic proof)
//
// vote_hash = SHA256(user_id + election_id + code1 + timestamp)
//
// Stores cryptographic proof instead of hashed voting code
```

#### Key Migration: Rename no_vote_option to no_vote_posts
**Status:** Applied via model transformations

```php
// OLD: no_vote_option = 1 (boolean - did voter abstain from ANY post?)
// NEW: no_vote_posts = [1, 3, 5] (array - which posts did voter abstain?)
//
// Rationale: More granular tracking of abstentions
// Allows voters to abstain from specific posts, not all-or-nothing
```

#### Key Migration: Rename candidacy_id to candidate_id
**Status:** Applied via model transformations

```php
// OLD: results.candidacy_id references some inconsistent field
// NEW: results.candidate_id references candidacies.id directly
//
// Rationale: Clarity and consistency
// Explicit that results are for candidates (via candidacies)
```

---

## Detailed Field Changes

### Votes Table: voting_code → vote_hash

**Old Field:**
```php
// voting_code stored hashed voter code
// Problem: Not cryptographically linked to election/timestamp
// Security issue: Could be reused across elections

$vote->voting_code = password_hash($code, PASSWORD_BCRYPT);
```

**New Field:**
```php
// vote_hash stores SHA256 of identification data
// Benefit: Unique per code per election per timestamp
// Security: Cryptographically proves vote belongs to specific voter/election
// Anonymity: Hash is irreversible (cannot extract user_id)

$vote->vote_hash = hash('sha256',
    $code->user_id .           // User (kept secret)
    $code->election_id .       // Election
    $code->code1 .             // Code
    now()->timestamp            // Timestamp
);
```

### Votes Table: no_vote_option → no_vote_posts

**Old Field:**
```php
// no_vote_option = 1 meant voter abstained from something
// Ambiguous: Abstained from all posts? Or specific posts?

$vote->no_vote_option = $request->no_vote ? 1 : 0;

// Retrieve: Cannot tell which posts were abstained from
if ($vote->no_vote_option === 1) {
    // Voter abstained from...? Which posts?
}
```

**New Field:**
```php
// no_vote_posts = [1, 3, 5] means voter abstained from posts 1, 3, 5
// Explicit: Clear which posts were abstained from

$vote->no_vote_posts = $request->no_vote_posts ?? [];  // e.g., [1, 3, 5]

// Retrieve: Explicit list of abstentions
foreach ($vote->no_vote_posts as $post_id) {
    // Create result record with NULL candidate_id
    Result::create([
        'vote_id' => $vote->id,
        'post_id' => $post_id,
        'candidate_id' => null,  // NULL indicates abstention
        'vote_hash' => $vote->vote_hash,
    ]);
}
```

**Migration Strategy:**
```php
// Convert old boolean to new array

// If no_vote_option === 1:
//   no_vote_posts = [all post IDs from this election]
//
// If no_vote_option === 0:
//   no_vote_posts = [] (empty array)
//
// This preserves old behavior while enabling new granularity
```

### Results Table: candidacy_id → candidate_id

**Old Field:**
```php
// candidacy_id was ambiguous field name
// Unclear: Does it reference candidacies table or something else?

$result->candidacy_id = $candidate['candidacy_id'];  // What is candidacy_id?
```

**New Field:**
```php
// candidate_id clearly references candidacies.id
// Explicit: Direct FK to candidacies table

$result->candidate_id = $candidate['candidacy_id'];  // Uses candidacies.id

// Foreign key constraint
$table->foreign('candidate_id')
      ->references('id')
      ->on('candidacies');
```

---

## Supporting Columns Added

### vote_hash in Results Table

```php
// Rationale: Enable verification of results
// Every result record gets a copy of the vote_hash
// Allows linking result back to vote for verification

$result->vote_hash = $vote->vote_hash;  // Copy for verification
```

### vote_count in Results Table

```php
// Rationale: Support result aggregation
// Each result record increments this counter
// Allows queries like: SELECT COUNT(*) by candidate

$result->vote_count = 1;  // Usually 1, can be aggregated
```

### election_id and organisation_id in Results

```php
// Rationale: Query and isolation optimization
// Allows filtering results by:
// - Specific election
// - Specific organisation (multi-tenant)

$result->election_id = $vote->election_id;
$result->organisation_id = $vote->organisation_id;
```

---

## Database Schema: SQL Examples

### Votes Table (Current)

```sql
CREATE TABLE votes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    election_id BIGINT UNSIGNED NOT NULL,
    organisation_id BIGINT UNSIGNED,
    vote_hash VARCHAR(255) COMMENT 'SHA256 cryptographic proof - NO user_id!',
    no_vote_posts JSON COMMENT 'Array of post IDs where voter abstained',
    candidate_01 VARCHAR(255),
    candidate_02 VARCHAR(255),
    -- ... candidate_03 through candidate_60 ...
    cast_at TIMESTAMP COMMENT 'When the vote was cast',
    metadata JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_election (election_id),
    INDEX idx_organisation (organisation_id),

    -- CRITICAL: No user_id column!
    -- CRITICAL: No FK to users table!
    -- This enforces anonymity at database level
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Results Table (Current)

```sql
CREATE TABLE results (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vote_id BIGINT UNSIGNED NOT NULL,
    election_id BIGINT UNSIGNED NOT NULL,
    organisation_id BIGINT UNSIGNED,
    post_id BIGINT UNSIGNED,
    candidate_id BIGINT UNSIGNED COMMENT 'References candidacies.id - NULL for abstentions',
    vote_hash VARCHAR(255) COMMENT 'Copy from vote for verification',
    vote_count INT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (vote_id) REFERENCES votes(id) ON DELETE CASCADE,
    FOREIGN KEY (candidate_id) REFERENCES candidacies(id) ON DELETE CASCADE,

    INDEX idx_election (election_id),
    INDEX idx_organisation (organisation_id),
    INDEX idx_candidate (candidate_id),

    -- CRITICAL: No user_id column!
    -- Results are aggregated, anonymous data
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Demo Tables (Separate)

```sql
-- demo_votes table has same schema as votes
CREATE TABLE demo_votes (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    election_id BIGINT UNSIGNED NOT NULL,
    organisation_id BIGINT UNSIGNED,  -- Usually NULL for demo
    vote_hash VARCHAR(255),
    no_vote_posts JSON,
    candidate_01 VARCHAR(255),
    -- ... same structure as votes ...
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- demo_results table has same schema as results
CREATE TABLE demo_results (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    vote_id BIGINT UNSIGNED NOT NULL,
    election_id BIGINT UNSIGNED NOT NULL,
    organisation_id BIGINT UNSIGNED,
    post_id BIGINT UNSIGNED,
    candidate_id BIGINT UNSIGNED,
    vote_hash VARCHAR(255),
    vote_count INT DEFAULT 1,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Rationale: Separate tables for demo vs real votes
-- Allows:
-- - Resetting demo data without affecting real elections
-- - Different retention policies
-- - Different access controls
```

---

## Breaking Changes Summary

### 1. Removal of user_id from Votes
- **Impact:** Cannot query votes by user_id
- **Migration:** Use vote_hash + code verification instead
- **Backwards Compatibility:** None (deliberate design change)

### 2. Removal of voting_code
- **Impact:** Old voting_code column is gone
- **Migration:** Use vote_hash for verification
- **Backwards Compatibility:** None (new field names)

### 3. no_vote_option → no_vote_posts
- **Impact:** Boolean becomes array
- **Migration:** Need data transformation (bool → array of post IDs)
- **Backwards Compatibility:** Can be managed with data migration

### 4. candidacy_id → candidate_id
- **Impact:** Field name change
- **Migration:** Rename in queries and models
- **Backwards Compatibility:** Simple find-replace in codebase

---

## Migration Execution Checklist

### Before Running Migrations

```bash
# 1. Backup current database
mysqldump -u root -p publicdigit > publicdigit_backup.sql

# 2. Verify test database is fresh
php artisan migrate:fresh --env=testing

# 3. Run tests to ensure code works with new schema
php artisan test

# 4. Check for any hardcoded references to old column names
grep -r "voting_code" app/
grep -r "candidacy_id" app/
grep -r "no_vote_option" app/
```

### During Migration

```bash
# 1. Run migrations in order
php artisan migrate

# 2. Verify migrations completed
php artisan migrate:status

# 3. Check schema
php artisan schema:show
```

### After Migration

```bash
# 1. Run full test suite
php artisan test

# 2. Verify data integrity
php artisan db:seed  # If using seeders

# 3. Check application logs for errors
tail -f storage/logs/laravel.log

# 4. Test voting flow manually
# (Open browser, vote through UI)
```

---

## Rollback Procedure

If migration fails:

```bash
# Rollback to previous state
php artisan migrate:rollback

# Verify tables reverted
php artisan schema:show

# Restore from backup if needed
mysql -u root -p publicdigit < publicdigit_backup.sql
```

---

## Data Integrity Checks

### Verify No user_id References

```sql
-- Should return 0 rows
SELECT COUNT(*) FROM votes WHERE user_id IS NOT NULL;

-- Should return empty result
SHOW COLUMNS FROM votes WHERE Field = 'user_id';
```

### Verify vote_hash Populated

```sql
-- All votes should have vote_hash
SELECT COUNT(*) as votes_without_hash
FROM votes
WHERE vote_hash IS NULL;
-- Should return: 0

-- Verify hash format (SHA256 = 64 hex characters)
SELECT COUNT(*) as invalid_hashes
FROM votes
WHERE vote_hash NOT REGEXP '^[a-f0-9]{64}$';
-- Should return: 0
```

### Verify candidate_id Relationships

```sql
-- Verify all non-null candidate_ids reference valid candidacies
SELECT COUNT(*) as invalid_candidate_refs
FROM results r
WHERE r.candidate_id IS NOT NULL
  AND NOT EXISTS (
    SELECT 1 FROM candidacies c WHERE c.id = r.candidate_id
  );
-- Should return: 0
```

---

## Performance Considerations

### Indexes Added

```sql
-- Votes table indexes
CREATE INDEX idx_votes_election_id ON votes(election_id);
CREATE INDEX idx_votes_organisation_id ON votes(organisation_id);

-- Results table indexes
CREATE INDEX idx_results_election_id ON results(election_id);
CREATE INDEX idx_results_organisation_id ON results(organisation_id);
CREATE INDEX idx_results_candidate_id ON results(candidate_id);
CREATE INDEX idx_results_vote_id ON results(vote_id);
```

### Query Performance

| Query Type | Before | After | Improvement |
|------------|--------|-------|-------------|
| Get votes by election | O(n) full scan | O(log n) indexed | ✅ Better |
| Get results by candidate | O(n) full scan | O(log n) indexed | ✅ Better |
| Count votes per candidate | O(n) full scan | O(log n) indexed | ✅ Better |
| Find vote by user_id | O(1) indexed | N/A (impossible) | ✅ More secure |

---

## Next Steps

- **How is this implemented in code?** → [04-implementation-guide.md](./04-implementation-guide.md)
- **What are the API changes?** → [05-api-reference.md](./05-api-reference.md)
- **How do I test this?** → [06-testing-guide.md](./06-testing-guide.md)

---

**Summary:** The schema changes implement Verifiable Anonymity by removing user_id from votes and results tables, replacing it with vote_hash (cryptographic proof), and adding election_id and organisation_id for proper scoping and multi-tenancy support.
