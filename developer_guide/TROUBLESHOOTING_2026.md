# Troubleshooting Guide - Demo Election Voting System 2026

## Common Issues & Solutions

### Issue 1: "No demo candidates showing"

**Symptoms**:
- Vote creation page is empty
- No candidates to select from
- List of posts but no names

**Diagnosis**:
```php
// Check in tinker
$candidates = \App\Models\DemoCandidate::where('election_id', 1)->count();
echo "Demo candidates: $candidates";  // Should be 10+

$posts = \App\Models\Post::count();
echo "Posts: $posts";  // Should be 5+
```

**Root Causes**:
- [ ] Migration not run: `demo_candidacies` table doesn't exist
- [ ] Seeder not executed: No candidate data
- [ ] Posts not created: No positions to vote for
- [ ] election_id mismatch: Candidates belong to different election

**Solution**:
```bash
# 1. Run migrations
php artisan migrate

# 2. Seed demo data
php artisan db:seed --class=DemoCandidateSeeder

# 3. Create posts manually if needed
php artisan tinker
> \App\Models\Post::create(['post_id' => 'president', 'post_name' => 'President']);
> \App\Models\Post::create(['post_id' => 'vp', 'post_name' => 'Vice President']);
# ... etc for all positions

# 4. Verify
> \App\Models\DemoCandidate::count()  # Should be > 0
> \App\Models\Post::count()  # Should be > 0
```

---

### Issue 2: "User stuck in code verification loop"

**Symptoms**:
- Submit code, page redirects back to code entry
- Cannot proceed to agreement page
- Logs show "Step 1 not recorded" or similar

**Diagnosis**:
```php
// Check if step was recorded
$voterSlug = \App\Models\VoterSlug::where('slug', 'your_slug')->first();
$steps = $voterSlug->steps;
echo "Step 1 recorded: " . ($steps->where('step', 1)->count() > 0 ? 'YES' : 'NO');

// Check election_id set
echo "voter_slug election_id: " . $voterSlug->election_id;  // Should be 1 or 2

// Check middleware
echo "Expected election: demo (1) or real (2)";
```

**Root Causes**:
- [ ] Step 1 not being recorded in CodeController::store()
- [ ] voter_slug.election_id is NULL
- [ ] Exception thrown but caught silently in step recording
- [ ] Middleware blocking access

**Solution**:
```php
// 1. Check CodeController::store() method
// Make sure this code exists:
$stepTrackingService = new VoterStepTrackingService();
$stepTrackingService->completeStep(
    $voterSlug, $election, 1,
    ['code_verified' => true, 'verified_at' => now()->toIso8601String()]
);

// 2. Set election_id on voter_slug
$voterSlug->election_id = 1;  // Demo election
$voterSlug->save();

// 3. Manually record the step
$tracker = new \App\Services\VoterStepTrackingService();
$tracker->completeStep($voterSlug, $election, 1, ['manual_fix' => true]);

// 4. Check logs for exceptions
tail -f storage/logs/laravel.log

// 5. Try code verification again
```

---

### Issue 3: "Code shows negative minutes (-2 minutes)"

**Symptoms**:
- Code verification page shows "-2 minutes remaining"
- Cannot use code after 30 minutes
- "Auto-resend didn't work"

**Diagnosis**:
```php
// Check code age
$code = \App\Models\Code::latest()->first();
$minutes = now()->diffInMinutes($code->created_at);
echo "Code age: $minutes minutes";

echo "Voting window: " . $code->voting_time_in_minutes . " minutes";
```

**Root Causes**:
- [ ] Code created 30+ minutes ago
- [ ] Auto-resend logic not working in CodeController::create()
- [ ] voting_time_in_minutes not set correctly

**Solution**:
```php
// 1. Verify auto-resend logic in CodeController::create()
$minutesSinceSent = now()->diffInMinutes($code->created_at);
if ($minutesSinceSent >= $code->voting_time_in_minutes) {
    // Generate new code automatically
    $newCode = Code::create([
        'user_id' => $code->user_id,
        'code1' => rand(100000, 999999),
        'code2' => rand(100000, 999999),
        'voting_time_in_minutes' => 30
    ]);
}

// 2. Create fresh code
$code = \App\Models\Code::create([
    'user_id' => $user->user_id,
    'code1' => '123456',
    'code2' => '654321',
    'voting_time_in_minutes' => 30,
    'created_at' => now()
]);

// 3. Start voting again
```

---

### Issue 4: "Cannot proceed from agreement page"

**Symptoms**:
- Click "I agree" button
- Page reloads or shows error
- Error: "Step not yet available" or similar
- Stuck on agreement page

**Diagnosis**:
```php
// Check if Step 2 was recorded
$voterSlug = \App\Models\VoterSlug::where('slug', 'slug')->first();
echo "Step 1: " . ($voterSlug->steps->where('step', 1)->count() > 0 ? 'YES' : 'NO');
echo "Step 2: " . ($voterSlug->steps->where('step', 2)->count() > 0 ? 'YES' : 'NO');

// Check middleware configuration
// Should allow Step 2 access after Step 1 done
```

**Root Causes**:
- [ ] Step 1 not completed (requires Step 1 first)
- [ ] Step 2 not being recorded in CodeController::submitAgreement()
- [ ] Middleware blocking Step 2 access
- [ ] CSRF token missing in form

**Solution**:
```php
// 1. Ensure Step 1 is done first
$tracker = new \App\Services\VoterStepTrackingService();
$highest = $tracker->getHighestCompletedStep($voterSlug, $election);
echo "Highest step: $highest";  // Should be >= 1

// 2. Check CodeController::submitAgreement() has step recording
// Should have:
$stepTracker->completeStep(
    $voterSlug, $election, 2,
    ['agreement_accepted' => true, 'accepted_at' => now()->toIso8601String()]
);

// 3. Manually record Step 2
$tracker->completeStep($voterSlug, $election, 2, ['manual' => true]);

// 4. Try agreement submission again
```

---

### Issue 5: "Vote not saving / Cannot submit vote"

**Symptoms**:
- Reached final verification page
- Click "Submit & Complete Voting"
- Error: "Call to protected method getVoteModel()"
- Or: Vote doesn't appear in database
- Or: Silent failure (no error, no vote saved)

**Diagnosis**:
```php
// Check method visibility
$service = new \App\Services\DemoVotingService($election);
echo $service->getVoteModel();  // Should return: App\Models\DemoVote

// Check demo_votes table exists
php artisan migrate:status | grep demo_votes

// Check for errors
tail -f storage/logs/laravel.log
```

**Root Causes**:
- [ ] getVoteModel() or getResultModel() is protected (not public)
- [ ] demo_votes table doesn't exist
- [ ] DemoVote model has errors
- [ ] Exception thrown in save_vote() method

**Solution**:
```php
// 1. Fix method visibility in DemoVotingService.php
// Change from:
protected function getVoteModel(): string

// Change to:
public function getVoteModel(): string

// 2. Do same for getResultModel()
public function getResultModel(): string

// 3. Run migrations if needed
php artisan migrate

// 4. Check demo_votes table
// CREATE TABLE demo_votes (
//     id BIGINT UNSIGNED PRIMARY KEY,
//     election_id BIGINT UNSIGNED,
//     voting_code VARCHAR,
//     candidate_01 JSON, ... candidate_60 JSON,
//     created_at TIMESTAMP
// );

// 5. Try vote submission again
```

---

### Issue 6: "Step 4 and Step 5 not recorded"

**Symptoms**:
- Only Steps 1-3 showing in voter_slug_steps
- Step 4 missing (verification page doesn't record)
- Step 5 missing (final submission doesn't record)
- Vote saves but no Step 5 record

**Diagnosis**:
```php
$voterSlug = \App\Models\VoterSlug::where('slug', 'slug')->first();
foreach ([1,2,3,4,5] as $s) {
    $exists = $voterSlug->steps->where('step', $s)->count() > 0;
    echo "Step $s: " . ($exists ? 'YES' : 'NO') . "\n";
}
```

**Root Causes**:
- [ ] Step 4 recording code not added to VoteController::verify()
- [ ] Step 5 recording code not added to VoteController::store()
- [ ] Exception in step recording (caught silently)
- [ ] Voting flow completed before Step 4/5 code added

**Solution**:
```php
// 1. Add Step 4 recording to VoteController::verify()
// After page validations, before Inertia::render():
try {
    $stepTrackingService = new \App\Services\VoterStepTrackingService();
    $stepTrackingService->completeStep(
        $voterSlug, $election, 4,
        ['vote_verified' => true, 'verified_at' => now()->toIso8601String()]
    );
} catch (\Exception $e) {
    Log::error('Failed to record Step 4', ['error' => $e->getMessage()]);
}

// 2. Add Step 5 recording to VoteController::store()
// After save_vote() call, before markUserAsVoted():
try {
    $stepTrackingService = new \App\Services\VoterStepTrackingService();
    $stepTrackingService->completeStep(
        $voterSlug, $election, 5,
        [
            'vote_submitted_final' => true,
            'submitted_final_at' => now()->toIso8601String(),
            'vote_id' => $this->out_code
        ]
    );
} catch (\Exception $e) {
    Log::error('Failed to record Step 5', ['error' => $e->getMessage()]);
}

// 3. Run fresh voting flow to test
```

---

### Issue 7: "All 5 steps not recorded"

**Symptoms**:
- Completed full voting flow
- voter_slug_steps doesn't have all 5 steps
- Some steps missing randomly

**Diagnosis**:
```php
$voterSlug = \App\Models\VoterSlug::where('slug', 'slug')->first();
$steps = $voterSlug->steps->sortBy('step');
echo "Steps recorded: " . $steps->count() . " / 5\n";

foreach ($steps as $s) {
    echo "Step {$s->step}: {$s->completed_at}\n";
}
```

**Root Causes**:
- [ ] User didn't complete all 5 steps
- [ ] Some steps skipped (middleware allowed it)
- [ ] Exception in step recording code
- [ ] Database transaction rolled back

**Solution**:
```php
// 1. Verify each step in turn:
$tracker = new \App\Services\VoterStepTrackingService();

echo "Step 1: ";
echo ($tracker->hasCompletedStep($voterSlug, $election, 1) ? 'YES' : 'NO') . "\n";

echo "Step 2: ";
echo ($tracker->hasCompletedStep($voterSlug, $election, 2) ? 'YES' : 'NO') . "\n";

// ... etc for steps 3-5

// 2. Manually complete missing steps
$tracker->completeStep($voterSlug, $election, 1, ['step' => 1]);
$tracker->completeStep($voterSlug, $election, 2, ['step' => 2]);
// ... etc

// 3. Run fresh complete voting flow
```

---

### Issue 8: "Cannot skip steps - always blocked"

**Symptoms**:
- Middleware returns 403 for every step
- Even for completed steps
- Cannot proceed to next step after completion

**Diagnosis**:
```php
// Check middleware calculation
$tracker = new \App\Services\VoterStepTrackingService();
$highest = $tracker->getHighestCompletedStep($voterSlug, $election);
echo "Highest completed: $highest";

$next = $tracker->getNextStep($voterSlug, $election);
echo "Next allowed: $next";

// Check requested step
// What step is user trying to access?
```

**Root Causes**:
- [ ] getHighestCompletedStep() returning 0 incorrectly
- [ ] Middleware calculation wrong
- [ ] voter_slug_steps table empty or not queried

**Solution**:
```php
// 1. Verify steps are actually recorded
$steps = \App\Models\VoterSlugStep::where('voter_slug_id', $voterSlug->id)->get();
echo "Steps in DB: " . $steps->count();

// 2. Check highest completed calculation
$highest = \App\Models\VoterSlugStep::where('voter_slug_id', $voterSlug->id)
    ->max('step');
echo "Max step: $highest";  // Should match getHighestCompletedStep()

// 3. Debug middleware logic
// In EnsureVoterStepOrder middleware, add logging:
Log::info('Step access check', [
    'requested_step' => $requestedStep,
    'highest_completed' => $highestCompletedStep,
    'next_allowed' => $nextAllowedStep,
    'access_allowed' => $requestedStep <= $nextAllowedStep
]);

// 4. Clear cache if using caching
php artisan cache:clear
```

---

## Quick Debug Commands

### Tinker Session

```php
php artisan tinker

// Setup
$slug = 'your_slug_here';
$voterSlug = \App\Models\VoterSlug::where('slug', $slug)->first();
$election = \App\Models\Election::find($voterSlug->election_id);

// Check steps
echo "Steps: " . $voterSlug->steps->count();
foreach ($voterSlug->steps->sortBy('step') as $s) {
    echo "  {$s->step}: {$s->completed_at}\n";
}

// Check tracker
$tracker = new \App\Services\VoterStepTrackingService();
echo "Highest: " . $tracker->getHighestCompletedStep($voterSlug, $election);
echo "Next: " . $tracker->getNextStep($voterSlug, $election);

// Check votes
$votes = \App\Models\DemoVote::where('election_id', $election->id)->count();
echo "Votes: $votes";

// Check results
$results = \App\Models\DemoResult::where('election_id', $election->id)->count();
echo "Results: $results";
```

### Database Verification

```sql
-- Check voter_slug_steps table exists
SHOW TABLES LIKE 'voter_slug_steps';

-- Check records
SELECT * FROM voter_slug_steps ORDER BY created_at DESC LIMIT 10;

-- Check uniqueness
SELECT voter_slug_id, election_id, step, COUNT(*)
FROM voter_slug_steps
GROUP BY voter_slug_id, election_id, step
HAVING COUNT(*) > 1;
-- Should return 0 rows (no duplicates)

-- Check demo votes
SELECT * FROM demo_votes ORDER BY created_at DESC LIMIT 5;

-- Check demo results
SELECT * FROM demo_results ORDER BY created_at DESC LIMIT 10;
```

### Log Review

```bash
# View recent errors
tail -n 50 storage/logs/laravel.log | grep -i "error\|exception\|step"

# Follow logs in real-time
tail -f storage/logs/laravel.log

# Search for specific step
grep "Step 1 recorded" storage/logs/laravel.log
grep "Failed to record Step" storage/logs/laravel.log
```

---

## Contact & Escalation

**Before reporting issue**:
1. Check logs: `storage/logs/laravel.log`
2. Run tinker verification
3. Check database directly
4. Review this guide

**When reporting**:
- Include: Error message, logs, tinker output
- Include: What step fails, browser/client info
- Include: What you've already tried

---

Last Updated: **2026-02-04**
