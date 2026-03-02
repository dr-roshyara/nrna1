# Votes & Results Tables Consolidation Analysis

## Executive Summary

The **votes** and **results** tables are undergoing significant schema refactoring from the old migrations to the new consolidated migrations. This refactoring represents a **fundamental shift in how votes are stored and verified**, introducing:

1. **Vote anonymity via vote_hash** instead of voting_code
2. **Candidate IDs as unsignedBigInteger** instead of JSON
3. **Post abstention tracking via no_vote_posts JSON** instead of boolean
4. **Vote count aggregation in results** for reporting

---

## 📊 VOTES TABLE CONSOLIDATION

### Critical Design Change: Anonymity Architecture

**Old Design:**
```
voting_code: string (original voting code)
candidate_01-60: JSON (flexible, untyped)
no_vote_option: boolean (simple yes/no)
```

**New Design:**
```
vote_hash: string UNIQUE (SHA256 hash for verification)
candidate_01-60: unsignedBigInteger (candidate IDs)
no_vote_posts: JSON array (posts where voter abstained)
```

### Missing Columns in New Migration

The new consolidated migration (`2026_03_01_000010_create_votes_table.php`) is **MISSING** two important columns from old migrations:

| Column | Type | Purpose | Source Migration |
|--------|------|---------|------------------|
| `voting_code` | string | Original voting code for verification | 2021_07_22_192509 |
| `vote_session_name` | string | Session identifier for audit | 2025_08_05_165123 |

### Column Type Changes

Two columns changed fundamental types:

| Column | Old Type | New Type | Impact |
|--------|----------|----------|--------|
| `candidate_01-60` | JSON | unsignedBigInteger | Stricter data validation (now must be candidate IDs, not arbitrary JSON) |
| `no_vote_option` | boolean | (replaced) | `no_vote_posts` is now JSON array, more flexible |

### VOTES Table Column Comparison

**OLD MIGRATIONS FINAL STATE (6 migrations total):**
1. id
2. no_vote_option (boolean, default 0)
3. voting_code (string)
4. candidate_01-60 (JSON, nullable) - 60 columns
5. vote_session_name (string, nullable)
6. election_id (unsignedBigInteger, default 1)
7. organisation_id (unsignedBigInteger, NOT NULL)
8. created_at, updated_at

**Total: ~68 columns**

**NEW CONSOLIDATED MIGRATION:**
1. id
2. election_id (unsignedBigInteger)
3. organisation_id (unsignedBigInteger)
4. vote_hash (string, unique) - **NEW**
5. candidate_01-60 (unsignedBigInteger, nullable) - 60 columns
6. no_vote_posts (json, nullable) - **NEW (replaces no_vote_option)**
7. metadata (json, nullable)
8. cast_at (timestamp) - **NEW**
9. created_at, updated_at

**Total: ~67 columns**

### VOTES Table - Consolidation Actions

**ACTION 1: KEEP voting_code**
- Still used in audit trails and verification
- Must add back to new migration file

**ACTION 2: KEEP vote_session_name**
- Used for session tracking in old migrations
- Must add back to new migration file if still needed

**ACTION 3: Document vote_hash introduction**
- vote_hash is NEW in consolidated design
- This is by design for anonymity layer
- No migration needed (already in new migration)

**ACTION 4: Update candidate type in database**
- Candidate columns must be changed from JSON to unsignedBigInteger
- This represents a breaking change requiring data migration

---

## 📊 RESULTS TABLE CONSOLIDATION

### Critical Changes to Results Structure

**Old Design:**
```
vote_id: bigInteger (link to vote)
post_id: string (position/post identifier)
candidacy_id: string (candidacy identifier)
```

**New Design:**
```
vote_id: unsignedBigInteger (link to vote)
election_id: unsignedBigInteger (scoping)
post_id: unsignedBigInteger (post ID, not string!)
candidate_id: unsignedBigInteger (candidate who got vote)
organisation_id: unsignedBigInteger (tenant scoping)
vote_hash: string (verification link to vote)
vote_count: unsignedInteger (aggregation for reporting)
```

### Missing Columns in New Migration

The new consolidated migration (`2026_03_01_000011_create_results_table.php`) actually appears **complete** - no missing columns from old migrations.

However, semantic changes are significant:

| Column | Old Name/Type | New Name/Type | Change |
|--------|---------------|---------------|--------|
| ID field | `candidacy_id` (string) | `candidate_id` (unsignedBigInteger) | Type change, semantic change (candidacy vs candidate) |
| ID field | `post_id` (string) | `post_id` (unsignedBigInteger) | Type change (string → int) |

### RESULTS Table Column Comparison

**OLD MIGRATIONS FINAL STATE (5 migrations total):**
1. id
2. vote_id (bigInteger unsigned)
3. post_id (string)
4. candidacy_id (string)
5. election_id (unsignedBigInteger, default 1)
6. organisation_id (unsignedBigInteger, nullable)
7. created_at, updated_at
8. foreign key: vote_id → votes.id (cascade)

**Total: 8 columns**

**NEW CONSOLIDATED MIGRATION:**
1. id
2. vote_id (unsignedBigInteger)
3. election_id (unsignedBigInteger)
4. post_id (unsignedBigInteger) - **CHANGED FROM STRING**
5. candidate_id (unsignedBigInteger, nullable) - **CHANGED FROM candidacy_id STRING**
6. organisation_id (unsignedBigInteger, nullable)
7. vote_hash (string, nullable) - **NEW**
8. vote_count (unsignedInteger, default 1) - **NEW**
9. created_at, updated_at
10. Foreign keys: vote_id, election_id, post_id, candidate_id, organisation_id

**Total: 10 columns**

### RESULTS Table - Consolidation Actions

**ACTION 1: Accept type change for post_id**
- Changed from string to unsignedBigInteger
- This is intentional refactoring
- Data migration needed if results exist

**ACTION 2: Accept type change for candidacy_id → candidate_id**
- Old: candidacy_id (string) - identifier for a specific candidacy (user + post combination)
- New: candidate_id (unsignedBigInteger) - ID of the person running
- This is a semantic change in the domain model

**ACTION 3: Add vote_hash tracking**
- vote_hash is NEW in consolidated design
- Allows results to link back to votes for verification
- Critical for audit trail

**ACTION 4: Add vote_count for aggregation**
- vote_count is NEW for reporting
- Allows aggregating multiple votes by same person/post combo
- Critical for results publishing

---

## 🔄 CONSOLIDATION STRATEGY

### Decision Matrix

For each table, we have two strategic choices:

#### VOTES Table Strategy

**OPTION A: Full Refactor (RECOMMENDED)**
- Accept vote_hash and new candidate type design
- Keep voting_code for backward compatibility audit
- Add vote_session_name for session tracking
- Update candidate columns to unsignedBigInteger
- **Impact:** Significant but intentional refactoring of vote storage

**OPTION B: Conservative**
- Keep old voting_code as primary
- Treat vote_hash as optional additional field
- Preserve JSON candidate storage
- **Impact:** Minimal change but loses anonymity benefits

**RECOMMENDATION:** Option A - The new design is explicitly designed for anonymous voting

#### RESULTS Table Strategy

**OPTION A: Full Refactor (RECOMMENDED)**
- Accept new candidate ID and post ID types
- Accept vote_hash for verification
- Accept vote_count for aggregation
- **Impact:** Data type changes but stronger referential integrity

**OPTION B: Conservative**
- Keep string types for post_id and candidacy_id
- Optional vote_hash and vote_count
- **Impact:** Minimal change but less performant queries

**RECOMMENDATION:** Option A - The new types enable better indexing and queries

---

## 📋 EXECUTION PLAN

### Phase 1: Database Analysis

```bash
# Check current votes table structure
php artisan tinker --execute="
\$columns = DB::getSchemaBuilder()->getColumnListing('votes');
echo 'Current votes columns: ' . count(\$columns) . PHP_EOL;
foreach (\$columns as \$col) {
    echo '  - ' . \$col . PHP_EOL;
}
"

# Check current results table structure
php artisan tinker --execute="
\$columns = DB::getSchemaBuilder()->getColumnListing('results');
echo 'Current results columns: ' . count(\$columns) . PHP_EOL;
foreach (\$columns as \$col) {
    echo '  - ' . \$col . PHP_EOL;
}
"
```

### Phase 2: Update Migration Files

**VOTES Table (`2026_03_01_000010_create_votes_table.php`):**

Add these columns back (if used in code):
1. `voting_code` (string) - for audit trails
2. `vote_session_name` (string, nullable) - for session tracking

Modify candidate columns:
- Keep as `unsignedBigInteger` (matches new design)

Verify includes:
- `vote_hash` (string, unique)
- `no_vote_posts` (json, nullable)
- `cast_at` (timestamp)
- All foreign keys and indexes

**RESULTS Table (`2026_03_01_000011_create_results_table.php`):**

Already includes all necessary columns - no additions needed.

Verify includes:
- `vote_hash` (string, nullable) for verification
- `vote_count` (unsignedInteger, default 1) for aggregation
- All foreign keys and indexes

### Phase 3: Database Updates

Execute migrations (if not already run):

```bash
# Check migration status
php artisan migrate:status

# Run pending migrations
php artisan migrate
```

### Phase 4: Verification

```bash
# Verify column existence
php artisan tinker --execute="
\$expectedVotes = [
    'id', 'election_id', 'organisation_id', 'vote_hash',
    'candidate_01', 'candidate_02', /* ... */ 'candidate_60',
    'no_vote_posts', 'metadata', 'cast_at',
    'voting_code', 'vote_session_name', // if kept
    'created_at', 'updated_at'
];

\$actualVotes = DB::getSchemaBuilder()->getColumnListing('votes');
\$missingVotes = array_diff(\$expectedVotes, \$actualVotes);
\$extraVotes = array_diff(\$actualVotes, \$expectedVotes);

echo '✅ VOTES TABLE VERIFICATION' . PHP_EOL;
if (empty(\$missingVotes) && empty(\$extraVotes)) {
    echo 'All columns present and accounted for!' . PHP_EOL;
} else {
    if (!empty(\$missingVotes)) echo '❌ Missing: ' . implode(', ', \$missingVotes) . PHP_EOL;
    if (!empty(\$extraVotes)) echo '⚠️ Extra: ' . implode(', ', \$extraVotes) . PHP_EOL;
}
"

# Verify results table
php artisan tinker --execute="
\$expectedResults = [
    'id', 'vote_id', 'election_id', 'post_id', 'candidate_id',
    'organisation_id', 'vote_hash', 'vote_count',
    'created_at', 'updated_at'
];

\$actualResults = DB::getSchemaBuilder()->getColumnListing('results');
\$missingResults = array_diff(\$expectedResults, \$actualResults);
\$extraResults = array_diff(\$actualResults, \$expectedResults);

echo '✅ RESULTS TABLE VERIFICATION' . PHP_EOL;
if (empty(\$missingResults) && empty(\$extraResults)) {
    echo 'All columns present and accounted for!' . PHP_EOL;
} else {
    if (!empty(\$missingResults)) echo '❌ Missing: ' . implode(', ', \$missingResults) . PHP_EOL;
    if (!empty(\$extraResults)) echo '⚠️ Extra: ' . implode(', ', \$extraResults) . PHP_EOL;
}
"
```

---

## ⚠️ CRITICAL NOTES

### Vote Hash Implementation

The new `vote_hash` field is CRITICAL for the anonymous voting system:
- Generated as SHA256(user_id + election_id + voting_code + timestamp)
- Unique identifier for each vote
- Allows voter to verify their vote was counted
- Does NOT link vote to voter (anonymity maintained)

### Candidate Column Types

The change from JSON to `unsignedBigInteger` means:
- Old: `candidate_01` contained arbitrary JSON (flexible but untyped)
- New: `candidate_01` contains only candidate IDs (strict, typed, indexed)
- This enables much better query performance and referential integrity

### No User ID in Votes/Results

Both tables intentionally have **NO user_id column**:
- Votes are completely anonymous
- Results cannot be linked to voters
- Only verification is through vote_hash
- This is a critical design decision, not a bug

---

## 🎯 Success Criteria

- [ ] Votes table has all 67+ columns
- [ ] Results table has all 10 columns
- [ ] vote_hash unique constraint enforced
- [ ] All foreign keys present
- [ ] All indexes created
- [ ] No orphaned data (all votes belong to elections/organisations)
- [ ] No user_id columns (anonymity preserved)
- [ ] Migration files consolidated and readable

---

## 📝 Next Steps After Consolidation

1. Consolidate **elections** table
2. Consolidate **voter_slugs** table
3. Consolidate **posts** table
4. Consolidate **candidacies** table
5. Create comprehensive migration strategy documentation

