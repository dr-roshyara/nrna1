# Testing Guide - Demo Election Voting System

## Test Environment Setup

### Prerequisites

```bash
# Run migrations
php artisan migrate

# Seed demo data
php artisan db:seed --class=DemoCandidateSeeder

# Verify in tinker
php artisan tinker
```

---

## Unit Tests

### VoterStepTrackingService Tests

```php
// Test: Record step
$voterSlug = VoterSlug::factory()->create();
$election = Election::find(1);
$tracker = new VoterStepTrackingService();

$step = $tracker->completeStep($voterSlug, $election, 1, ['test' => true]);
assert($step->step === 1);
assert($step->completed_at !== null);
```

### Middleware Tests

```php
// Test: Cannot skip steps
$voterSlug = VoterSlug::factory()->create(['election_id' => 1]);
VoterSlugStep::create([
    'voter_slug_id' => $voterSlug->id,
    'election_id' => 1,
    'step' => 1,
    'completed_at' => now()
]);

$response = $this->get("/v/{$voterSlug->slug}/vote/verify");
// Should be 403 (cannot skip to step 4)
```

### Model Tests

```php
// Test: VoterSlugStep relationships
$voterSlug = VoterSlug::factory()->create();
$step = VoterSlugStep::factory()
    ->create(['voter_slug_id' => $voterSlug->id]);

assert($step->voterSlug->id === $voterSlug->id);
assert($voterSlug->steps->contains($step));
```

---

## Integration Tests

### Complete Voting Flow

```php
// Setup
$election = Election::find(1);
$user = User::factory()->create();
$voterSlug = (new VoterSlugService())->generateSlugForUser($user, 1);

// Create code
$code = Code::create([
    'user_id' => $user->user_id,
    'code1' => '123456',
    'code2' => '654321',
    'voting_time_in_minutes' => 30
]);
$user->code_id = $code->id;
$user->save();

// STEP 1: Code Verification
$response = $this->post("/v/{$voterSlug->slug}/code/verify", [
    'voting_code' => '123456'
]);
assert($response->status() === 302); // Redirect to agreement

// Verify step recorded
$steps = $voterSlug->steps;
assert($steps->where('step', 1)->count() === 1);

// STEP 2: Agreement
$response = $this->post("/v/{$voterSlug->slug}/code/agreement");
assert($response->status() === 302); // Redirect to vote

// Verify step recorded
$voterSlug->refresh();
assert($voterSlug->steps->where('step', 2)->count() === 1);

// STEP 3: Vote Submission
$voteData = [
    'post_1' => ['candidacy_001'],
    'post_2' => ['candidacy_002'],
    // ... all posts
];
$response = $this->post("/v/{$voterSlug->slug}/vote/create", $voteData);
assert($response->status() === 302); // Redirect to verify

// Verify step recorded
$voterSlug->refresh();
assert($voterSlug->steps->where('step', 3)->count() === 1);

// STEP 4: Vote Verification (GET request)
$response = $this->get("/v/{$voterSlug->slug}/vote/verify");
assert($response->status() === 200);

// Verify step recorded
$voterSlug->refresh();
assert($voterSlug->steps->where('step', 4)->count() === 1);

// STEP 5: Final Submission
$response = $this->post("/v/{$voterSlug->slug}/vote/store", [
    'voting_code' => '654321'
]);
assert($response->status() === 302); // Redirect to thank you

// Verify step recorded
$voterSlug->refresh();
assert($voterSlug->steps->where('step', 5)->count() === 1);

// Verify vote saved
$vote = DemoVote::where('election_id', 1)->latest()->first();
assert($vote !== null);
assert($vote->voting_code !== null);

// Verify results recorded
$results = DemoResult::where('vote_id', $vote->id)->count();
assert($results > 0);
```

---

## Manual Testing Checklist

### Pre-Flight Checks

- [ ] Migrations run: `php artisan migrate:status`
- [ ] Demo election exists: `select * from elections where type='demo'`
- [ ] Demo candidates exist: `select count(*) from demo_candidacies`
- [ ] Posts created: `select count(*) from posts`
- [ ] No migration errors in logs

### Step 1: Code Verification

**Route**: `POST /v/{slug}/code/create`

**Test Steps**:
1. Generate voter slug
2. Create code with code1='123456'
3. Send request:
   ```
   POST /v/{slug}/code/verify
   voting_code=123456
   ```
4. Expected: Redirect to agreement page
5. **Verify**:
   - Check logs for Step 1 recorded
   - Query: `SELECT * FROM voter_slug_steps WHERE step=1 AND voter_slug_id=X`
   - Should have one row with completed_at

### Step 2: Agreement Acceptance

**Route**: `POST /v/{slug}/code/agreement`

**Test Steps**:
1. After Step 1 complete
2. Send request:
   ```
   POST /v/{slug}/code/agreement
   _token={csrf_token}
   ```
3. Expected: Redirect to vote creation page
4. **Verify**:
   - Check logs for Step 2 recorded
   - Query: `SELECT * FROM voter_slug_steps WHERE step=2 AND voter_slug_id=X`

### Step 3: Vote Selection

**Route**: `POST /v/{slug}/vote/create`

**Test Steps**:
1. After Step 2 complete
2. Get vote/create page to see all candidates
3. Select candidates for each post:
   ```
   POST /v/{slug}/vote/create
   post_1=candidacy_001
   post_2=candidacy_002
   ...
   ```
4. Expected: Redirect to verification page (Step 4)
5. **Verify**:
   - Check logs for Step 3 recorded
   - Session contains vote data
   - Query: `SELECT * FROM voter_slug_steps WHERE step=3`

### Step 4: Vote Verification

**Route**: `GET /v/{slug}/vote/verify`

**Test Steps**:
1. After Step 3 complete, auto-redirected here
2. Page should display:
   - Voter information
   - Selected candidates for each post
   - Remaining voting time
   - "Submit & Complete Voting" button
3. Expected: Page renders successfully
4. **Verify**:
   - Check logs for Step 4 recorded
   - Query: `SELECT * FROM voter_slug_steps WHERE step=4`

### Step 5: Final Vote Submission

**Route**: `POST /v/{slug}/vote/store`

**Test Steps**:
1. On verification page, click "Submit & Complete Voting"
2. Enter code2 when prompted
3. Send request:
   ```
   POST /v/{slug}/vote/store
   voting_code=654321
   _token={csrf_token}
   ```
4. Expected: Redirect to thank you page
5. **Verify**:
   - Check logs for Step 5 recorded
   - Vote saved: `SELECT * FROM demo_votes WHERE election_id=1 ORDER BY created_at DESC LIMIT 1`
   - Results recorded: `SELECT * FROM demo_results WHERE vote_id=X`
   - Query: `SELECT * FROM voter_slug_steps WHERE voter_slug_id=X`
   - Should show all 5 steps with timestamps

### Vote Anonymity Verification

**Test**:
```sql
-- Verify vote has NO user_id
SELECT id, user_id, voting_code, election_id
FROM demo_votes
WHERE election_id=1
LIMIT 1;
-- Should have NULL user_id, hashed voting_code

-- Verify results recorded
SELECT vote_id, post_id, candidacy_id
FROM demo_results
WHERE vote_id=1;
-- Should have multiple rows for selected candidates
```

---

## Tinker Commands for Testing

### Full Test Workflow

```php
php artisan tinker

// 1. Setup
$election = \App\Models\Election::where('type', 'demo')->first();
$user = \App\Models\User::factory()->create();
$voterSlugService = new \App\Services\VoterSlugService();
$voterSlug = $voterSlugService->generateSlugForUser($user, 1);

// 2. Create code
$code = \App\Models\Code::create([
    'user_id' => $user->user_id,
    'code1' => '123456',
    'code2' => '654321',
    'voting_time_in_minutes' => 30
]);
$user->code_id = $code->id;
$user->save();

// 3. Verify step tracker works
$tracker = new \App\Services\VoterStepTrackingService();
echo "Initial highest step: " . $tracker->getHighestCompletedStep($voterSlug, $election);

// 4. Record steps manually (simulating controller)
$tracker->completeStep($voterSlug, $election, 1, ['test' => 'code']);
$tracker->completeStep($voterSlug, $election, 2, ['test' => 'agreement']);
$tracker->completeStep($voterSlug, $election, 3, ['test' => 'vote']);
$tracker->completeStep($voterSlug, $election, 4, ['test' => 'verify']);

// 5. Check progress
echo "Highest step: " . $tracker->getHighestCompletedStep($voterSlug, $election);
echo "Next step: " . $tracker->getNextStep($voterSlug, $election);

// 6. View timeline
$timeline = $tracker->getStepTimeline($voterSlug, $election);
foreach ($timeline as $entry) {
    echo "Step {$entry['step']}: {$entry['completed_at']}\n";
}

// 7. Record final step
$tracker->completeStep($voterSlug, $election, 5, ['vote_id' => '12345']);

// 8. Verify all steps
$voterSlug->refresh();
echo "Total steps recorded: " . $voterSlug->steps->count();
foreach ($voterSlug->steps->sortBy('step') as $s) {
    echo "  Step {$s->step}: {$s->completed_at}\n";
}

exit;
```

### Verification Queries

```php
// Check demo election
\App\Models\Election::where('type', 'demo')->first();

// Check candidates count
\App\Models\DemoCandidate::where('election_id', 1)->count();

// Check votes
\App\Models\DemoVote::where('election_id', 1)->count();

// Check results
\App\Models\DemoResult::where('election_id', 1)->count();

// Get last voter slug with steps
\App\Models\VoterSlug::with('steps')
    ->where('election_id', 1)
    ->orderBy('created_at', 'desc')
    ->first();

// Check specific voter progress
$slug = \App\Models\VoterSlug::latest()->first();
echo "Slug: " . $slug->slug;
echo "Steps: " . $slug->steps->count();
foreach ($slug->steps->sortBy('step') as $s) {
    echo "  Step {$s->step}: {$s->completed_at}\n";
}
```

---

## Performance Testing

### Database Query Performance

```php
// Measure: Get highest completed step
$start = microtime(true);
for ($i = 0; $i < 1000; $i++) {
    $tracker->getHighestCompletedStep($voterSlug, $election);
}
$time = microtime(true) - $start;
echo "1000 queries in " . $time . " seconds";
// Should be < 1 second (indexes working)
```

### Load Testing

```bash
# Using Apache Bench
ab -n 100 -c 10 http://localhost:8000/v/{slug}/code/create

# Using wrk
wrk -t4 -c100 -d30s http://localhost:8000/v/{slug}/code/create
```

---

## Error Scenarios

### Scenario 1: Skip Steps

**Test**: Try to access Step 4 without completing Steps 1-3

```
GET /v/{slug}/vote/verify
# Expected: 403 Forbidden (middleware blocks)
```

### Scenario 2: Code Expires

**Test**: Try to verify code after 30 minutes

```php
// Create old code
$code->created_at = now()->subMinutes(35);
$code->save();

// Try to verify
POST /v/{slug}/code/verify
# Expected: 422 Code expired
```

### Scenario 3: Double Vote

**Test**: Try to vote twice with same voter slug

```php
// After Step 5 complete:
$code->has_voted = true;
$code->save();

// Try to vote again
POST /v/{slug}/vote/create
# Expected: Error - Already voted
```

### Scenario 4: Invalid Elections

**Test**: Mix demo and real elections

```php
// Create voter slug for demo
$voterSlug1 = generateSlugForUser($user, 1);

// Try to access real election vote page
GET /v/{voterSlug1->slug}/vote/create  # With real election in session
# Expected: Error or correct handling
```

---

## Database Verification

### Verify Schema

```sql
-- Check voter_slug_steps table
DESCRIBE voter_slug_steps;

-- Check indexes
SHOW INDEXES FROM voter_slug_steps;

-- Verify unique constraint
SELECT COUNT(*) FROM voter_slug_steps
GROUP BY voter_slug_id, election_id, step
HAVING COUNT(*) > 1;
-- Should return 0 rows (no duplicates)
```

### Verify Data Integrity

```sql
-- Check all steps linked to valid voters
SELECT COUNT(*) FROM voter_slug_steps vss
WHERE NOT EXISTS (
    SELECT 1 FROM voter_slugs vs WHERE vs.id = vss.voter_slug_id
);
-- Should return 0 rows

-- Check all steps linked to valid elections
SELECT COUNT(*) FROM voter_slug_steps vss
WHERE NOT EXISTS (
    SELECT 1 FROM elections e WHERE e.id = vss.election_id
);
-- Should return 0 rows

-- Check no step > 5
SELECT COUNT(*) FROM voter_slug_steps WHERE step > 5;
-- Should return 0 rows

-- Check no null timestamps
SELECT COUNT(*) FROM voter_slug_steps WHERE completed_at IS NULL;
-- Should return 0 rows
```

---

## Test Results Template

| Test | Expected | Actual | Status |
|------|----------|--------|--------|
| Demo election exists | ID=1 | ✅ | PASS |
| Demo candidates loaded | 10+ | 10 | PASS |
| Step 1 records | Step 1 in DB | ✅ | PASS |
| Step 2 records | Step 2 in DB | ✅ | PASS |
| Step 3 records | Step 3 in DB | ✅ | PASS |
| Step 4 records | Step 4 in DB | ✅ | PASS |
| Step 5 records | Step 5 in DB | ✅ | PASS |
| Vote saved anonymously | NO user_id | ✅ | PASS |
| Results recorded | 4+ results | 4 | PASS |
| Cannot skip steps | 403 error | ✅ | PASS |
| Cannot vote twice | Error/redirect | ✅ | PASS |
| Middleware enforces order | Only +1 step | ✅ | PASS |

---

## Continuous Integration

### GitHub Actions / CI Pipeline

```yaml
name: Voting System Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    services:
      postgres:
        image: postgres:13
        env:
          POSTGRES_PASSWORD: postgres
    steps:
      - uses: actions/checkout@v2
      - uses: php-actions/composer@v6
      - run: php artisan migrate
      - run: php artisan test --testsuite=Unit
      - run: php artisan test --testsuite=Integration
```

---

Last Updated: **2026-02-04**
