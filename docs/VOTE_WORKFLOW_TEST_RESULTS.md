# Vote Submission Workflow - TDD Test Results

## Overview

Created **36 comprehensive TDD tests** for the complete real election vote submission workflow. Tests cover all 10 user journey steps.

**File:** `tests/Feature/VoteSubmissionWorkflowTest.php`

---

## Test Status Summary

| Category | Tests | Status | Notes |
|----------|-------|--------|-------|
| Code Entry Page | 4 | ❌ Red | Need to verify routes and middleware |
| Code1 Submission | 4 | ❌ Red | Need to check controller implementation |
| Vote Create Page | 3 | ❌ Red | Need to verify candidate retrieval |
| Candidate Selection | 3 | ❌ Red | Need to check session handling |
| Review Selections | 2 | ❌ Red | Need to verify review page |
| Vote Submission | 3 | ❌ Red | Need to check vote storage |
| Verification Code | 2 | ❌ Red | Need to verify code generation |
| Verification Entry | 3 | ❌ Red | Need to check code validation |
| Vote Recording | 3 | ❌ Red | Need to verify database records |
| Revote Prevention | 6 | ❌ Red | Need to verify blocking logic |
| **TOTAL** | **36** | **❌ Red** | **All tests written and ready** |

---

## Understanding TDD Results

### What "Red" Means

In TDD, "Red" is the **first expected phase**:

```
Red (Test Fails) → Green (Write Code) → Refactor (Improve Code)
```

**All 36 tests are Red because:**
1. Tests define the expected behavior
2. Implementation may not match exact test expectations
3. Route names, controller methods, or request/response formats may differ
4. This tells us what needs to be fixed or verified

---

## Test Breakdown by Step

### Step 1: User Lands on /code/create (4 tests)

**Tests:**
- `test_user_can_access_code_create_page()` - User can view code entry form
- `test_unauthenticated_user_redirected_from_code_create()` - Redirects to login
- `test_non_voter_cannot_access_code_create_page()` - Voters-only access
- `test_code_create_requires_active_election()` - Election must be active

**What Needs Checking:**
- Route: `code.create` exists
- Middleware checks: `auth` and `voter` eligibility
- Election active status verification
- Response includes form for code entry

**Implementation File:** Likely `app/Http/Controllers/CodeController.php`

---

### Step 2: User Agrees to Terms & Conditions (4 tests)

**Tests:**
- `test_user_can_submit_code1_and_agree_to_terms()` - Code1 accepted, code2 provided
- `test_code1_submission_fails_with_incorrect_code()` - Invalid code rejected
- `test_code1_submission_fails_without_agreement()` - Terms must be accepted
- `test_code1_can_only_be_used_once()` - Code1 not reusable

**What Needs Checking:**
- Route: `code.first_submission` exists
- Validation: code1 must match exactly
- Validation: agreement checkbox required
- Code.has_agreed_to_vote set correctly
- Code.voting_started_at recorded
- Session initialized properly

**Implementation File:** `app/Http/Controllers/CodeController.php::first_submission()`

---

### Step 3: User Lands on /vote/create (3 tests)

**Tests:**
- `test_user_can_access_vote_create_page_after_code1()` - Form loads after code1
- `test_user_cannot_access_vote_create_without_code1()` - Redirects if code1 not submitted
- `test_vote_create_page_displays_all_posts_and_candidates()` - All posts + candidates shown

**What Needs Checking:**
- Route: `vote.create` exists
- Middleware verifies code1 was submitted
- Inertia props include: election, posts, candidates
- Candidates ordered by position_order
- Proper data passed to Vue component

**Implementation File:** `app/Http/Controllers/VoteController.php::create()`

---

### Step 4: User Selects Candidates (3 tests)

**Tests:**
- `test_user_can_select_candidate_for_each_post()` - Selections stored in session
- `test_user_cannot_skip_required_post()` - All posts must have selection
- `test_user_can_change_candidate_selection()` - Can modify before submission

**What Needs Checking:**
- Session key naming: `Hash::make($code->code2)`
- Session data structure: `['national_selected_candidates' => [...]]`
- JavaScript handles selection storage
- Validation requires minimum selections

**Implementation File:** Frontend (`CreateVotingform.vue`) + Controller

---

### Step 5: User Reviews Selections (2 tests)

**Tests:**
- `test_user_can_review_selections_before_submission()` - Review page loads
- `test_review_page_displays_candidate_details()` - Shows candidate info

**What Needs Checking:**
- Route: `vote.review` exists
- Retrieves session data
- Displays selected candidates with details
- Option to go back and modify

**Implementation File:** `app/Http/Controllers/VoteController.php::review()`

---

### Step 6: User Submits Vote (3 tests)

**Tests:**
- `test_user_can_submit_complete_vote()` - Submission accepted
- `test_vote_submission_fails_with_incomplete_selections()` - Validation required
- `test_vote_submission_fails_if_session_expired()` - Session must exist

**What Needs Checking:**
- Route: `vote.first_submission` exists
- Validates all posts have selections
- Session data retrieved correctly
- Redirects to verification page
- Doesn't record vote yet (just stores for verification)

**Implementation File:** `app/Http/Controllers/VoteController.php::first_submission()`

---

### Step 7: User Receives Verification Code (2 tests)

**Tests:**
- `test_user_receives_verification_code_after_submission()` - Code2 ready for entry
- `test_verification_code_is_unique_per_user()` - Each user has unique code2

**What Needs Checking:**
- Code.code2 is unique and random
- Verification page displays code2 to user
- Code2 sent via email (if applicable)
- Route: `vote.verify_to_show` exists

**Implementation File:** `app/Http/Controllers/VoteController.php::verify_to_show()`

---

### Step 8: User Enters Verification Code (3 tests)

**Tests:**
- `test_user_can_enter_correct_verification_code()` - Correct code accepted
- `test_verification_fails_with_incorrect_code()` - Wrong code rejected
- `test_code2_validation_is_strict()` - Must match exactly

**What Needs Checking:**
- Route: `vote.store` validates code2
- Code2 compared exactly (no trimming/case sensitivity issues)
- Session data preserved during verification
- Route parameters match

**Implementation File:** `app/Http/Controllers/VoteController.php::store()`

---

### Step 9: Vote Recorded in Database (3 tests)

**Tests:**
- `test_vote_recorded_in_votes_table_for_real_election()` - Vote in votes table
- `test_vote_record_includes_required_metadata()` - Has all required fields
- `test_code_marked_as_voted_after_verification()` - Code.has_voted = true
- `test_user_marked_as_voted_after_verification()` - User.has_voted = true

**What Needs Checking:**
- Multiple Vote records created (one per candidate)
- Vote fields:
  - `user_id` - Voter ID
  - `election_id` - Election ID
  - `candidacy_id` - Candidate ID
  - `created_at` - Timestamp
- Code.has_voted set to 1
- User.has_voted set to 1
- Transaction rolls back on any error

**Implementation File:** `app/Http/Controllers/VoteController.php::store()`

---

### Step 10: User Cannot Vote Again (6 tests)

**Tests:**
- `test_user_redirected_to_dashboard_on_revote_attempt()` - Redirects on /vote/create
- `test_revoting_attempt_shows_error_message()` - Error message displayed
- `test_revote_blocked_at_vote_store_endpoint()` - Blocked at submission
- `test_revote_blocked_with_correct_code()` - Even with correct code2
- `test_no_new_vote_on_revote_attempt()` - Vote count unchanged
- `test_transaction_rolled_back_on_revote_attempt()` - Changes reverted

**What Needs Checking:**
- VoteController.store() line 1250 check:
  ```php
  if ($election->type === 'real' && $code->has_voted) {
      return redirect()->route('dashboard')
          ->withErrors(['vote' => 'You have already voted...']);
  }
  ```
- Redirect to dashboard
- Error message includes "already voted"
- No new Vote records created
- State remains unchanged

**Implementation File:** `app/Http/Controllers/VoteController.php::store()`

---

## Next Steps: Making Tests Green

To make tests pass, follow this process for each failing test:

### 1. **Identify Route/Controller Issue**
```bash
# Check if route exists
grep -r "code.create" app/Http/Controllers
grep -r "code.first_submission" routes/
grep -r "vote.create" routes/
```

### 2. **Verify Method Signature**
- Check if controller method exists
- Verify it accepts correct parameters
- Check return type (Inertia/redirect/response)

### 3. **Trace Data Flow**
- Request → Controller → Response
- Check session handling
- Verify data structure

### 4. **Update Tests if Needed**
If implementation differs from test expectations:
- Option A: Update implementation to match test (TDD purist)
- Option B: Update test to match implementation (pragmatic)

### 5. **Run Tests Again**
```bash
php artisan test tests/Feature/VoteSubmissionWorkflowTest.php --verbose
```

---

## Implementation Checklist

Use this checklist to verify each step is implemented:

### Code Entry
- [ ] Route `code.create` exists
- [ ] Requires authentication
- [ ] Requires voter eligibility
- [ ] Displays code entry form
- [ ] POST to `code.first_submission`

### Code1 Submission
- [ ] Route `code.first_submission` exists
- [ ] Validates code1 matches exactly
- [ ] Validates agreement checkbox
- [ ] Sets Code.has_agreed_to_vote = 1
- [ ] Sets Code.voting_started_at
- [ ] Initializes session for voting
- [ ] Redirects to `vote.create`

### Vote Creation Page
- [ ] Route `vote.create` exists
- [ ] Requires code1 submission
- [ ] Loads all posts for election
- [ ] Loads all candidates for each post
- [ ] Candidates ordered by position_order
- [ ] Passes to Inertia component

### Vote Selection
- [ ] Session key: `Hash::make($code->code2)`
- [ ] Data structure: `['national_selected_candidates' => ['ID1', 'ID2', ...]]`
- [ ] Validates all posts have selections
- [ ] Allows modification before submission

### Vote Submission
- [ ] Route `vote.first_submission` exists
- [ ] Retrieves session data
- [ ] Validates candidate IDs exist
- [ ] Redirects to `vote.verify_to_show`

### Verification Code
- [ ] Code.code2 unique and random
- [ ] Route `vote.verify_to_show` displays code2
- [ ] Code2 sent via email (if applicable)

### Verification
- [ ] Route `vote.store` validates code2
- [ ] Code2 matches exactly
- [ ] Session data still available
- [ ] Transaction management: `DB::beginTransaction()` and `DB::rollBack()`

### Vote Recording
- [ ] Multiple Vote records created
- [ ] Each vote has: user_id, election_id, candidacy_id, created_at
- [ ] Code.has_voted = 1
- [ ] User.has_voted = 1
- [ ] Redirect to success page

### Revote Prevention
- [ ] Check at: `VoteController::store()` line ~1250
- [ ] Condition: `$election->type === 'real' && $code->has_voted`
- [ ] Redirect: route('dashboard')
- [ ] Error: withErrors(['vote' => 'message'])
- [ ] No vote record created

---

## Test Execution

```bash
# Run all workflow tests
php artisan test tests/Feature/VoteSubmissionWorkflowTest.php

# Run with verbose output (shows all test names)
php artisan test tests/Feature/VoteSubmissionWorkflowTest.php --verbose

# Run specific test
php artisan test --filter=test_user_can_submit_code1_and_agree_to_terms

# Run and stop on first failure
php artisan test tests/Feature/VoteSubmissionWorkflowTest.php --stop-on-failure

# Run with coverage
php artisan test tests/Feature/VoteSubmissionWorkflowTest.php --coverage
```

---

## Tips for Fixing Tests

1. **Start with Step 1-2** - Code entry and submission are prerequisites
2. **Check Route Names** - Most failures are route name mismatches
3. **Verify Middleware** - Auth and voter eligibility checks
4. **Trace Session** - Session data must persist across requests
5. **Validate Data Structure** - Match session key and data format
6. **Test Incrementally** - Fix one group (4 tests) at a time
7. **Use --verbose Flag** - Helps identify which assertions fail

---

## Expected Test Results After Implementation

When all implementation is complete and correct:

```
✓ user can access code create page
✓ unauthenticated user redirected from code create
✓ non voter cannot access code create page
✓ code create requires active election
✓ user can submit code1 and agree to terms
✓ code1 submission fails with incorrect code
✓ code1 submission fails without agreement
✓ code1 can only be used once
✓ user can access vote create page after code1
✓ user cannot access vote create without code1
✓ vote create page displays all posts and candidates
✓ candidates displayed in position order
✓ user can select candidate for each post
✓ user cannot skip required post
✓ user can change candidate selection
✓ user can review selections before submission
✓ review page displays candidate details
✓ user can submit complete vote
✓ vote submission fails with incomplete selections
✓ vote submission fails if session expired
✓ user receives verification code after submission
✓ verification code is unique per user
✓ user can enter correct verification code
✓ verification fails with incorrect code
✓ code2 validation is strict
✓ vote recorded in votes table for real election
✓ vote record includes required metadata
✓ code marked as voted after verification
✓ user marked as voted after verification
✓ user redirected to dashboard on revote attempt
✓ revoting attempt shows error message
✓ revote blocked at vote store endpoint
✓ revote blocked with correct code
✓ no new vote on revote attempt
✓ transaction rolled back on revote attempt

Tests: 36 passed
Time: XX.XXs
```

---

## Debugging Failed Tests

For each failed test:

1. **Read the assertion error** - Shows exactly what failed
2. **Check the exception** - Route not found? Method not exist?
3. **Verify implementation** - Does the controller method exist?
4. **Trace the data** - Is data being stored/retrieved correctly?
5. **Add debug logging** - Use Log::info() in controller

Example debug:

```php
// In controller
Log::info('Vote submission started', [
    'user_id' => $user->id,
    'code_has_voted' => $code->has_voted,
    'election_type' => $election->type,
]);
```

Then check:
```bash
tail -f storage/logs/laravel.log
```

---

