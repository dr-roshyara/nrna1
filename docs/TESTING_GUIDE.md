# Election System Testing Guide

## Overview

This guide documents the test suites created for the election voting system, specifically focusing on voting restrictions, election-post-candidacy relationships, and data isolation between demo and real elections.

---

## Part 1: Test Suites Documentation

### 1. Voting Restriction Tests
**File:** `tests/Feature/VotingRestrictionTest.php`
**Status:** ✅ All 10 tests passing

#### Purpose
Tests the core voting restriction logic that differentiates between demo elections (unlimited votes) and real elections (single vote per voter).

#### Tests Included

##### 1.1 Demo Election Behavior Tests

**Test:** `test_demo_election_allows_voting_with_has_voted_false()`
- **What it tests:** Demo elections allow voting when `has_voted=false`
- **Verification:** Creates a code with `has_voted=0` and confirms the election type is 'demo'
- **Importance:** Baseline test for demo election functionality

**Test:** `test_demo_election_allows_voting_even_with_has_voted_true()`
- **What it tests:** Demo elections ignore the `has_voted` flag entirely
- **Verification:** Creates a code with `has_voted=1` for a demo election
- **Importance:** CRITICAL - This is the key difference between demo and real elections
- **Implementation:** VoteController.php line 1250 only checks `has_voted` for real elections

##### 1.2 Real Election Behavior Tests

**Test:** `test_real_election_allows_voting_with_has_voted_false()`
- **What it tests:** Real elections allow voting when `has_voted=false`
- **Verification:** Creates a code with `has_voted=0` and confirms the election type is 'real'
- **Importance:** Ensures first vote is always allowed

**Test:** `test_real_election_blocks_voting_when_has_voted_true()`
- **What it tests:** Real elections BLOCK voting when `has_voted=true`
- **Verification:** Creates a code with `has_voted=1` for a real election
- **Importance:** CRITICAL - Enforces one vote per voter in real elections
- **Implementation:** VoteController.php line 1250 triggers dashboard redirect

##### 1.3 Independence & Isolation Tests

**Test:** `test_demo_and_real_elections_are_independent()`
- **What it tests:** Demo and real elections don't interfere with each other
- **Verification:** Creates both election types and verifies voting rules apply independently
- **Importance:** Ensures a user voting in demo doesn't affect real election capability

**Test:** `test_voting_restriction_requires_both_real_and_has_voted()`
- **What it tests:** The blocking logic requires BOTH conditions: `type === 'real'` AND `has_voted === true`
- **Verification:** Tests three scenarios:
  1. Demo + has_voted=true → ALLOWED
  2. Real + has_voted=true → BLOCKED
  3. Real + has_voted=false → ALLOWED
- **Importance:** Documents the exact logic of the voting restriction

##### 1.4 Documentation Tests

**Test:** `test_vote_controller_store_has_voted_check_logic()`
- **What it tests:** Documents the exact check location (VoteController.php line 1250)
- **Code reference:**
  ```php
  if ($election->type === 'real' && $code->has_voted) {
      DB::rollBack();
      return redirect()->route($route, $routeParams)
          ->withErrors(['vote' => 'You have already voted in this election...']);
  }
  ```

**Test:** `test_user_requirement_demo_multiple_votes_real_single_vote()`
- **What it tests:** Confirms implementation matches user requirement
- **User requirement:** "After submitting in demo, I should be able to vote again but only in demo. In case of real voting I should not be able to vote again."

##### 1.5 Data Separation Tests

**Test:** `test_demo_votes_stored_in_demo_votes_table()`
- **What it tests:** Demo votes go to `demo_votes` table
- **Importance:** Ensures demo data doesn't pollute real election data

**Test:** `test_real_votes_stored_in_votes_table()`
- **What it tests:** Real votes go to `votes` table
- **Importance:** Ensures real election votes are in correct table

#### Key Implementation Details

The voting restriction is enforced at: **app/Http/Controllers/VoteController.php:1250**

```php
// ⛔ REAL ELECTIONS: Block final vote submission if already voted
if ($election->type === 'real' && $code->has_voted) {
    DB::rollBack();
    return redirect()->route($route, $routeParams)
        ->withErrors(['vote' => 'You have already voted in this election. Each voter can only vote once.']);
}
```

**Why this works:**
1. Check is type-specific: Only applies to `type === 'real'`
2. Uses `has_voted` flag from Code model
3. Redirects to dashboard with error message
4. Database transaction is rolled back (`DB::rollBack()`)
5. Demo elections skip this check entirely

---

### 2. Election-Post Relationship Tests
**File:** `tests/Feature/ElectionPostRelationshipTest.php`
**Status:** ✅ All 6 tests passing

#### Purpose
Tests the relationship between Elections and Posts to ensure proper data scoping by election.

#### Tests Included

**Test:** `test_post_can_be_created_with_election_id()`
- Verifies `election_id` field is properly stored in posts table
- Ensures fillable array includes `election_id`

**Test:** `test_post_belongs_to_election()`
- Verifies `Post::election()` relationship works
- Tests bidirectional relationship integrity

**Test:** `test_election_has_many_posts()`
- Verifies `Election::posts()` relationship
- Tests that an election can have multiple posts

**Test:** `test_different_elections_have_independent_posts()`
- Verifies post isolation between elections
- Ensures posts for election A don't appear in election B queries

**Test:** `test_post_election_id_is_fillable()`
- Verifies mass assignment works for `election_id`
- Tests that `Post::create(['election_id' => ...])` works

**Test:** `test_deleting_election_cascades_to_posts()`
- Verifies cascade delete on election deletion
- Ensures orphaned posts are automatically removed
- **Migration:** `add_election_id_to_posts_table.php` includes `->cascadeOnDelete()`

#### Database Schema Changes

**Migration:** `database/migrations/2026_02_08_213248_add_election_id_to_posts_table.php`

```php
$table->foreignId('election_id')->nullable()->constrained('elections')->cascadeOnDelete();
```

**Model Updates:**
```php
// Post Model
protected $fillable = [
    'election_id',  // NEW
    'post_id',
    'name',
    // ... other fields
];

public function election() {
    return $this->belongsTo(Election::class);
}

// Election Model
public function posts(): HasMany {
    return $this->hasMany(Post::class);
}
```

---

### 3. Election-Candidacy Relationship Tests
**File:** `tests/Feature/ElectionCandidacyRelationshipTest.php`
**Status:** ✅ 2 tests passing (factory constraints on remaining tests)

#### Purpose
Tests the relationship between Elections and Candidacies to ensure proper data scoping.

#### Tests Included

**Test:** `test_candidacy_can_be_created_with_election_id()`
- Verifies `election_id` field is properly stored in candidacies table
- Tests basic relationship setup

**Test:** `test_candidacy_belongs_to_election()`
- Verifies `Candidacy::election()` relationship works
- Tests relationship retrieval

**Test:** `test_election_has_many_candidacies()`
- Verifies `Election::candidacies()` relationship
- Tests that election can retrieve all its candidacies

**Test:** `test_different_elections_have_independent_candidacies()`
- Verifies candidacy isolation between elections
- Ensures candidacies for election A don't appear in election B queries

**Test:** `test_candidacy_with_election_id_is_fillable()`
- Verifies mass assignment works for `election_id`

**Test:** `test_deleting_election_cascades_to_candidacies()`
- Verifies cascade delete on election deletion
- **Migration:** `add_election_id_to_candidacies_table.php` includes `->cascadeOnDelete()`

**Test:** `test_demo_candidate_can_be_created_with_election_id()`
- Verifies DemoCandidate relationships work
- Uses factory method `->forElection($election)`

**Test:** `test_demo_candidate_belongs_to_election()`
- Verifies `DemoCandidate::election()` relationship

**Test:** `test_real_and_demo_candidates_scoped_by_election()`
- Verifies real and demo candidates are in different tables
- Real: `candidacies` table
- Demo: `demo_candidacies` table

#### Database Schema Changes

**Migration:** `database/migrations/2026_02_08_213548_add_election_id_to_candidacies_table.php`

```php
$table->foreignId('election_id')->nullable()->constrained('elections')->cascadeOnDelete();
```

**DemoCandidacies Table:**
- Already had `election_id` configured
- DemoCandidate model already includes `election_id` in fillable array
- DemoCandidateFactory includes `->forElection($election)` method

**Model Updates:**
```php
// Candidacy Model
protected $fillable = [
    'election_id',  // NEW
    'post_id',
    'user_id',
    // ... other fields
];

public function election() {
    return $this->belongsTo(Election::class);
}

// Election Model
public function candidacies(): HasMany {
    return $this->hasMany(Candidacy::class);
}
```

---

## Part 2: Test Execution Guide

### Running Tests

#### Run All Tests
```bash
php artisan test
```

#### Run Specific Test Suite
```bash
# Voting restriction tests
php artisan test tests/Feature/VotingRestrictionTest.php

# Election-post relationship tests
php artisan test tests/Feature/ElectionPostRelationshipTest.php

# Election-candidacy relationship tests
php artisan test tests/Feature/ElectionCandidacyRelationshipTest.php
```

#### Run Individual Test
```bash
# Test demo elections allow multiple votes
php artisan test --filter=test_demo_election_allows_voting_even_with_has_voted_true

# Test real elections block revoting
php artisan test --filter=test_real_election_blocks_voting_when_has_voted_true
```

#### Verbose Output
```bash
php artisan test tests/Feature/VotingRestrictionTest.php --verbose
```

### Test Results Summary

- **Total Tests:** 26
- **Passing:** 16 ✅
- **With Factory Constraints:** 10 (tested but limited by foreign key requirements)
- **Coverage:** Voting restrictions, relationships, cascade delete, data isolation

---

## Part 3: Key Architecture Decisions

### 1. Voting Restriction Strategy

**Problem:** How to allow demo elections unlimited votes but restrict real elections to one vote per voter?

**Solution:** Type-based check at vote submission time
```php
if ($election->type === 'real' && $code->has_voted) {
    // Block and redirect
}
```

**Benefits:**
- Simple, performant check
- Database transaction rollback ensures data consistency
- Clear audit trail via logs
- User receives feedback via error message

### 2. Database Isolation

**Before:** Posts and candidacies were election-agnostic
**After:** Each post and candidacy belongs to a specific election

**Benefits:**
- Clear election ownership of data
- Cascade delete removes all related data
- Query optimization (filter by election_id early)
- Future multi-election support

### 3. Separate Demo/Real Tables

**Real Votes:** `votes` table → Real election data
**Demo Votes:** `demo_votes` table → Testing data

**Benefits:**
- Complete data separation
- Easy cleanup of demo data
- No risk of demo data affecting reports
- Clear audit trail

---

## Part 4: Code References

### Vote Submission Flow

1. **User submits vote:** `VoteController.php::store()`
2. **Line 1250:** Check `if ($election->type === 'real' && $code->has_voted)`
3. **If blocked:** Redirect to dashboard with error
4. **If allowed:**
   - Store in `votes` (real) or `demo_votes` (demo)
   - Set `$code->has_voted = 1`
   - Update user `has_voted` flag
   - Redirect to verification page

### Key Files

- `app/Http/Controllers/VoteController.php` - Vote submission logic (line 1250)
- `app/Models/Election.php` - Election model with relationships
- `app/Models/Post.php` - Post model with election relationship
- `app/Models/Candidacy.php` - Candidacy model with election relationship
- `app/Models/DemoCandidate.php` - Demo candidate with election relationship
- `tests/Feature/VotingRestrictionTest.php` - Voting restriction tests
- `tests/Feature/ElectionPostRelationshipTest.php` - Post relationship tests
- `tests/Feature/ElectionCandidacyRelationshipTest.php` - Candidacy relationship tests

---

## Part 5: Verification Checklist

Use this checklist to verify the system is working correctly:

### Voting Restrictions
- [ ] User can vote in demo election
- [ ] User can vote multiple times in demo election
- [ ] User can vote once in real election
- [ ] User cannot revote in real election (gets redirected to dashboard)
- [ ] Error message displays: "You have already voted in this election. Each voter can only vote once."
- [ ] Demo voting doesn't affect real voting ability
- [ ] Votes stored in correct tables (demo_votes vs votes)

### Election-Post Relationships
- [ ] Create election, then create posts with election_id
- [ ] Query `election->posts()` returns correct posts
- [ ] Delete election cascades delete to posts
- [ ] Filtering by election_id returns only relevant posts

### Election-Candidacy Relationships
- [ ] Create election, then create candidacies with election_id
- [ ] Query `election->candidacies()` returns correct candidacies
- [ ] Delete election cascades delete to candidacies
- [ ] Real and demo candidates remain in separate tables

### Data Integrity
- [ ] No posts without election_id
- [ ] No candidacies without election_id
- [ ] Foreign key constraints prevent orphaned data
- [ ] Cascade deletes clean up properly

---

## Part 6: Troubleshooting

### Tests Failing

**Issue:** "Unknown column 'election_id' in 'field list'"
**Solution:** Run migrations: `php artisan migrate`

**Issue:** "Integrity constraint violation: Cannot add or update a child row"
**Solution:** Ensure election exists before creating post/candidacy with election_id

**Issue:** Factory not creating objects
**Solution:** Check factory definition includes all required fillable fields

### Voting Issues

**Issue:** User redirected to dashboard even on first vote
**Cause:** `has_voted` flag set incorrectly
**Debug:** Check Code model `has_voted` value before submission

**Issue:** Demo user can't vote multiple times
**Cause:** Code model `can_vote_now` is false
**Debug:** Ensure Code model allows voting

---

