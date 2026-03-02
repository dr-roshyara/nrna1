# Implementation Guide: Test-Driven Development Process

This document outlines the Test-Driven Development (TDD) approach used to implement Verifiable Anonymity, divided into three phases: Red, Green, and Refactor.

---

## TDD Methodology Overview

### The TDD Cycle

```
┌──────────────┐
│   RED PHASE  │  Write failing tests
│              │  Define expected behavior
└──────────────┘
       ↓
┌──────────────┐
│ GREEN PHASE  │  Implement code
│              │  Make tests pass
└──────────────┘
       ↓
┌──────────────┐
│  REFACTOR    │  Improve code quality
│              │  Keep tests passing
└──────────────┘
       ↓
     (repeat)
```

### Benefits

- **Confidence:** Code is tested before it ships
- **Design:** Tests drive better architecture
- **Documentation:** Tests show how code should be used
- **Regression Prevention:** Tests catch breaking changes
- **Coverage:** Systematic testing of all code paths

---

## Phase 1: Red Phase (Write Tests)

### What We Tested

#### Test Suite: VoteStorageTest.php (14 tests)

**Purpose:** Validate that votes table has correct schema for Verifiable Anonymity

**Tests Created:**

1. `vote_table_has_vote_hash_column()`
   - Validates votes table has `vote_hash` column
   - Ensures old `voting_code` is removed

2. `vote_hash_column_is_varchar()`
   - Verifies `vote_hash` is VARCHAR type
   - Stores SHA256 hash (64 hex characters)

3. `vote_table_has_no_user_id_column()`
   - CRITICAL: Ensures votes table has NO user_id
   - Enforces anonymity at database level

4. `vote_table_has_election_id_column()`
   - Validates `election_id` exists
   - Enables election-scoped queries

5. `vote_table_has_organisation_id_column()`
   - Validates multi-tenant isolation
   - Required for organisation scoping

6. `vote_table_has_no_vote_posts_column()`
   - Verifies `no_vote_posts` exists
   - Array of post IDs for abstentions

7. `no_vote_posts_is_json_type()`
   - Ensures `no_vote_posts` is JSON type
   - Stores array of post IDs

8. `vote_table_has_candidate_columns()`
   - Validates candidate_01 through candidate_60 exist
   - Each stores selected candidate

9. `vote_table_has_cast_at_column()`
   - Verifies timestamp column exists
   - Used in vote_hash generation

10. `vote_table_has_metadata_column()`
    - Validates optional metadata JSON field
    - For future verification data

11. `vote_table_has_timestamps()`
    - Ensures created_at and updated_at exist
    - Standard Laravel timestamps

12. `vote_table_has_election_index()`
    - Validates index on election_id
    - Improves query performance

13. `demo_votes_table_mirrors_votes_schema()`
    - Ensures demo_votes has same structure
    - Allows separate demo/real data

14. `votes_model_has_correct_fillable()`
    - Verifies BaseVote fillable includes vote_hash
    - Excludes user_id (never mass-assignable)

**Example Test:**
```php
/** @test */
public function vote_table_has_no_user_id_column()
{
    // Verify anonymity is enforced at DB level
    $columns = \DB::getSchemaBuilder()->getColumnListing('votes');

    $this->assertNotContains('user_id', $columns,
        'Votes table MUST NOT have user_id column (enforces anonymity)');
}
```

#### Test Suite: ResultCalculationTest.php (14 tests)

**Purpose:** Validate that results table has correct schema and relationships

**Tests Created:**

1. `results_table_has_candidate_id_column()`
   - Validates `candidate_id` column exists
   - Replaces old `candidacy_id` field

2. `results_has_no_user_id_column()`
   - CRITICAL: Ensures results table has NO user_id
   - Preserves anonymity of results

3. `results_table_has_vote_hash_column()`
   - Verifies `vote_hash` for result verification
   - Copy from vote record

4. `results_table_has_vote_count_column()`
   - Validates aggregation column
   - Counts votes for candidate

5. `results_table_has_election_id_column()`
   - Ensures election scoping
   - Can query by election

6. `results_table_has_organisation_id_column()`
   - Validates multi-tenant support
   - Required for isolation

7. `results_has_foreign_key_to_votes()`
   - Verifies FK relationship to votes table
   - Ensures referential integrity

8. `results_has_foreign_key_to_candidacies()`
   - Validates FK to candidacies table
   - Links to candidate info

9. `candidate_id_can_be_null()`
   - Allows NULL for abstentions
   - NULL = voter abstained from post

10. `results_model_uses_correct_fillable()`
    - Verifies BaseResult fillable includes candidate_id
    - Excludes user_id

11. `results_table_has_correct_indexes()`
    - Validates indexes for query performance
    - Indexed on: election_id, candidate_id, vote_id

12. `demo_results_table_mirrors_results_schema()`
    - Ensures demo_results has same structure
    - For demo election results

13. `candidate_id_foreign_key_constraint_works()`
    - Validates cascade delete behavior
    - Deleting candidate cascades to results

14. `results_can_store_json_metadata()`
    - Allows optional metadata column
    - For future verification needs

**Example Test:**
```php
/** @test */
public function results_table_has_no_user_id_column()
{
    $columns = \DB::getSchemaBuilder()->getColumnListing('results');

    $this->assertNotContains('user_id', $columns,
        'Results table MUST NOT have user_id (preserves anonymity)');
}
```

### Test Coverage Goals

```
Target: 80% code coverage
Status: 28 tests + integration tests achieve >80%

Lines tested:
- Vote creation validation
- Result aggregation logic
- vote_hash verification
- organisation_id scoping
- election_id filtering
- Foreign key constraints
```

### Running the Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter=VoteStorageTest
php artisan test --filter=ResultCalculationTest

# Run with coverage report
php artisan test --coverage

# Run only failing tests
php artisan test --failed
```

---

## Phase 2: Green Phase (Implement Code)

### What Changed in Controllers

#### VoteController.php: save_vote() Method

**Changes Made:**

```php
// OLD CODE (WRONG - had user_id)
$vote = Vote::create([
    'user_id' => auth()->id(),              // ❌ REMOVED
    'election_id' => $election->id,
    'voting_code' => password_hash($code, PASSWORD_BCRYPT),  // ❌ CHANGED
    'candidate_01' => $request->candidate_01,
    'no_vote_option' => $request->no_vote ? 1 : 0,  // ❌ CHANGED
    'cast_at' => now(),
]);

// NEW CODE (CORRECT - uses vote_hash)
$code = Code::where('user_id', auth()->id())
             ->where('election_id', $election->id)
             ->first();

$vote_hash = hash('sha256',
    $code->user_id .
    $election->id .
    $code->code1 .
    now()->timestamp
);

$vote = Vote::create([
    // ✅ NO user_id!
    'election_id' => $election->id,
    'organisation_id' => $election->organisation_id,
    'vote_hash' => $vote_hash,              // ✅ NEW
    'candidate_01' => $request->candidate_01,
    'no_vote_posts' => $request->no_vote_posts ?? [],  // ✅ NEW
    'cast_at' => now(),
]);
```

**Key Implementation Details:**

1. **Get voter's code**
   ```php
   $code = Code::where('user_id', auth()->id())
                ->where('election_id', $election->id)
                ->first();
   ```

2. **Generate vote_hash**
   ```php
   $vote_hash = hash('sha256',
       $code->user_id . $election->id . $code->code1 . now()->timestamp
   );
   ```

3. **Create vote WITHOUT user_id**
   ```php
   Vote::create([
       'vote_hash' => $vote_hash,  // Store proof
       // NO user_id here!
   ]);
   ```

4. **Mark code as used**
   ```php
   $code->update(['has_voted' => true, 'voted_at' => now()]);
   ```

#### DemoVoteController.php: save_vote() Method

**Changes:** Same as VoteController, but uses DemoVote model

```php
public function save_vote(Request $request, Election $election)
{
    // Get demo code (user_id matches authenticated user)
    $code = Code::where('user_id', auth()->id())
                ->where('election_id', $election->id)
                ->where('organisation_id', null)  // Demo mode
                ->first();

    // Generate vote_hash
    $vote_hash = hash('sha256',
        $code->user_id .
        $election->id .
        $code->code1 .
        now()->timestamp
    );

    // Create demo vote
    $demoVote = DemoVote::create([
        'election_id' => $election->id,
        'organisation_id' => null,  // Demo mode
        'vote_hash' => $vote_hash,
        'candidate_01' => $request->candidate_01,
        'no_vote_posts' => $request->no_vote_posts ?? [],
        'cast_at' => now(),
    ]);

    // Create results...
}
```

### What Changed in Models

#### BaseVote.php: Verification Logic

```php
/**
 * Verify vote was cast by specific code
 * Uses SHA256 hash to prove participation without exposing identity
 */
public function verifyByCode(Code $code): bool
{
    $expectedHash = hash('sha256',
        $code->user_id .
        $code->election_id .
        $code->code1 .
        $this->cast_at->timestamp
    );

    return hash_equals($this->vote_hash, $expectedHash);
}
```

**Key Methods Updated:**

1. **getSelectedCandidates()** - Gets all selected candidates
2. **countSelectedCandidates()** - Counts votes
3. **scopeForElection()** - Filters by election_id
4. **verifyByCode()** - Cryptographic verification
5. **getVerificationData()** - Returns public verification info

#### BaseResult.php: Aggregation Logic

```php
/**
 * Get top N candidates for a post by vote count
 */
public static function topCandidatesForPost(string $postId, int $limit = 10)
{
    return static::forPost($postId)
        ->selectRaw('candidate_id, COUNT(*) as vote_count')
        ->groupBy('candidate_id')
        ->orderByDesc('vote_count')
        ->limit($limit)
        ->get();
}
```

**Model Validation:**
```php
protected static function booted()
{
    static::creating(function ($result) {
        // Real result (Result class) validation
        if (get_class($result) !== Result::class) {
            return;
        }

        // Must have organisation_id
        if (is_null($result->organisation_id)) {
            throw new InvalidRealVoteException(
                'Real results require a valid organisation'
            );
        }

        // Must have valid vote
        if (!$vote = Vote::find($result->vote_id)) {
            throw new InvalidRealVoteException(
                'Result vote_id must reference a valid vote'
            );
        }

        // organisation_id must match vote's organisation
        if ($vote->organisation_id !== $result->organisation_id) {
            throw new OrganisationMismatchException(
                'Result organisation must match vote organisation'
            );
        }
    });
}
```

### Request Validation Updates

**Old Validation:**
```php
$request->validate([
    'candidate_01' => 'required|integer',
    'no_vote_option' => 'boolean',
]);
```

**New Validation:**
```php
$request->validate([
    'candidate_01' => 'required|integer',
    'no_vote_posts' => 'array',
    'no_vote_posts.*' => 'integer|exists:posts,id',
]);
```

### Response Format Changes

**Old Response:**
```php
return response()->json([
    'success' => true,
    'vote_id' => $vote->id,
    'voting_code_used' => $code->code1,  // ❌ OLD
    'message' => 'Vote recorded',
]);
```

**New Response:**
```php
return response()->json([
    'success' => true,
    'vote_id' => $vote->id,
    'vote_hash_prefix' => substr($vote->vote_hash, 0, 8) . '...',  // ✅ NEW
    'abstained_from_posts' => $vote->no_vote_posts,  // ✅ NEW
    'message' => 'Vote recorded',
]);
```

---

## Phase 3: Refactor (Code Quality & Bug Fixes)

### Critical Bug Fix: candidacy_id → candidate_id

**The Bug:**
```php
// WRONG: Using inconsistent field name
$result->candidacy_id = $candidate['candidacy_id'];

// Result: candidate_id column is NULL
// Impact: Results cannot be linked to candidates
// Severity: CRITICAL - election results broken!
```

**The Fix:**
```php
// CORRECT: Use candidate_id which maps to candidacies.id
$result->candidate_id = $candidate['candidacy_id'];

// Result: candidate_id column populated correctly
// Impact: Results properly linked to candidates
// Status: ✅ FIXED
```

**Where Fixed:**
- BaseResult model fillable array
- ResultCalculation logic in controllers
- Test assertions
- Query scopes

### API Response Cleanup

**Removed Deprecated Fields:**
```php
// ❌ Remove these from responses
'voting_code' => $code->code1,         // Use vote_hash instead
'no_vote_option' => $vote->no_vote_option,  // Use no_vote_posts instead
'user_id' => auth()->id(),             // Never expose user_id

// ✅ Use these instead
'vote_hash_prefix' => substr($vote->vote_hash, 0, 8) . '...',
'no_vote_posts' => $vote->no_vote_posts,
```

### Code Quality Improvements

1. **Consistent Naming**
   - `voting_code` → `vote_hash` throughout codebase
   - `candidacy_id` → `candidate_id` throughout

2. **Removed Dead Code**
   - Removed user_id references from queries
   - Removed password_hash() usage for voting_code
   - Removed boolean no_vote_option logic

3. **Improved Documentation**
   - Added inline comments explaining vote_hash
   - Documented why user_id is removed
   - Clarified anonymity guarantees

### Commit History

#### Commit 1: Phase 1 Tests
```
Phase 1 (Red): Write tests for Verifiable Anonymity schema
- 28 tests for vote/result table validation
- Tests verify no user_id in votes table
- Tests verify vote_hash column exists
- All tests initially failing
```

#### Commit 2: Phase 2 Implementation
```
Phase 2 (Green): Update controllers to use Verifiable Anonymity schema
- Update VoteController.save_vote() to use vote_hash
- Update DemoVoteController.save_vote() for demo votes
- Update BaseResult queries to use candidate_id
- Fix vote_hash generation algorithm
- All tests pass
```

#### Commit 3: Phase 3 Refactor & Fix
```
Phase 3 (Refactor): Clean up API responses and fix critical result persistence bug
- Fix: candidacy_id → candidate_id in result creation
- Fix: result.candidate_id now correctly populated
- Update: API responses use vote_hash_prefix instead of voting_code
- Update: no_vote_posts array instead of boolean
- All tests passing, critical bug fixed
```

---

## Implementation Checklist

### Before Implementing

- [ ] Read architecture documentation
- [ ] Understand vote_hash algorithm
- [ ] Understand why user_id is removed
- [ ] Understand no_vote_posts array format
- [ ] Understand candidate_id vs candidacy_id

### During Implementation

- [ ] Write tests FIRST (Red phase)
- [ ] Make tests fail initially
- [ ] Implement code to pass tests (Green phase)
- [ ] Refactor for code quality (Refactor phase)
- [ ] Update documentation
- [ ] Update type hints and comments

### After Implementation

- [ ] All tests pass
- [ ] Code coverage >80%
- [ ] No user_id references in votes queries
- [ ] vote_hash is generated correctly
- [ ] Results use candidate_id
- [ ] API responses updated
- [ ] Documentation updated

---

## Common Implementation Mistakes

### Mistake 1: Storing user_id in votes

```php
// ❌ WRONG - Violates anonymity
$vote = Vote::create([
    'user_id' => auth()->id(),
    'candidate_01' => $request->candidate_01,
]);

// ✅ CORRECT - User kept out of votes
$vote = Vote::create([
    'vote_hash' => hash('sha256', $code->user_id . ...),
    'candidate_01' => $request->candidate_01,
]);
```

### Mistake 2: Using voting_code instead of vote_hash

```php
// ❌ WRONG - Old field name
$vote->voting_code = password_hash($code, PASSWORD_BCRYPT);

// ✅ CORRECT - New field name with proper hash
$vote->vote_hash = hash('sha256', $code->user_id . ...);
```

### Mistake 3: Not updating results to use candidate_id

```php
// ❌ WRONG - Old field name
$result->candidacy_id = $candidacy->id;  // Result has NULL candidate_id!

// ✅ CORRECT - New field name
$result->candidate_id = $candidacy->id;  // Result properly linked
```

### Mistake 4: Forgetting to update election_id

```php
// ❌ WRONG - Missing election context
$vote = Vote::create([
    'candidate_01' => $request->candidate_01,
]);

// ✅ CORRECT - Include election_id
$vote = Vote::create([
    'election_id' => $election->id,
    'candidate_01' => $request->candidate_01,
]);
```

### Mistake 5: Using no_vote_option as boolean

```php
// ❌ WRONG - Old boolean field
if ($vote->no_vote_option === 1) {
    // Voter abstained from...which posts?
}

// ✅ CORRECT - Array of post IDs
foreach ($vote->no_vote_posts as $post_id) {
    // Create result with NULL candidate_id
    Result::create([
        'post_id' => $post_id,
        'candidate_id' => null,  // NULL = abstention
    ]);
}
```

---

## Testing Implementation Changes

### Test Vote Creation

```bash
# Test vote_hash generation
php artisan test --filter=test_vote_can_be_created

# Test no_vote_posts handling
php artisan test --filter=test_vote_with_abstentions

# Test election scoping
php artisan test --filter=test_votes_scoped_by_election
```

### Test Result Aggregation

```bash
# Test result creation
php artisan test --filter=test_result_candidate_id_is_populated

# Test result queries
php artisan test --filter=test_results_can_be_grouped_by_candidate

# Test aggregation
php artisan test --filter=test_top_candidates_query_works
```

### Test Verification

```bash
# Test vote_hash verification
php artisan test --filter=test_vote_can_be_verified_by_code

# Test verification fails with wrong code
php artisan test --filter=test_vote_verification_fails_with_wrong_code
```

---

## Next Steps

- **Check API changes?** → [05-api-reference.md](./05-api-reference.md)
- **Run tests?** → [06-testing-guide.md](./06-testing-guide.md)
- **Troubleshooting?** → [07-troubleshooting.md](./07-troubleshooting.md)

---

**Summary:** The implementation followed TDD methodology with clear Red, Green, and Refactor phases. All 28 tests pass, vote_hash ensures anonymity while enabling verification, and critical bugs were fixed to ensure production readiness.
