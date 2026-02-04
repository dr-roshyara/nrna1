# Demo Election Re-Voting Fix

## Problem

**User Issue**: Could not vote twice in demo election
- First vote: ✅ Works fine, vote saved
- Second vote attempt: ❌ "Code already used" error even with new code

**Root Cause**: Voting flags (`has_voted`, `is_code1_usable`) were not being reset for demo elections

---

## Solution Implemented

### 1. Modified `getOrCreateCode()` Method

**Location**: `app/Http/Controllers/CodeController.php` line 505

**Change**: Added demo election detection to reset voting flags:

```php
// DEMO ELECTIONS: Allow re-voting by resetting flags
if ($code && $code->has_voted && $election->type === 'demo') {
    Log::info('🔄 Demo election - resetting code for re-voting', [
        'user_id' => $user->id,
        'code_id' => $code->id,
    ]);

    // Reset voting flags for demo to allow new vote
    $code->update([
        'has_voted' => false,           // Allow voting again
        'vote_submitted' => false,
        'can_vote_now' => 0,
        'is_code1_usable' => 1,         // Code is usable again
        'code1' => $this->generateCode(),
        'code1_sent_at' => now(),
        'has_code1_sent' => 1,
    ]);

    // Send new code via email
    if ($user->email && filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
        try {
            $user->notify(new SendFirstVerificationCode($user, $code->code1));
        } catch (\Exception $e) {
            Log::error('Failed to send demo voting code', [...]);
        }
    }

    return $code;
}
```

**When it triggers**: When user has already voted in demo election and requests code page again

### 2. Added Real Election Protection

**Location**: `app/Http/Controllers/CodeController.php` line 187

**Change**: Added check to prevent double-voting in real elections:

```php
// REAL ELECTIONS: Prevent double voting
if ($election->type === 'real' && $code->has_voted) {
    Log::warning('Real election - double vote attempt prevented', [
        'user_id' => $user->id,
        'election_id' => $election->id,
    ]);
    return back()->withErrors([
        'voting_code' => 'You have already voted in this election. Each voter can only vote once.'
    ]);
}
```

**When it triggers**: When user tries to vote again in real election

---

## Behavior After Fix

### Demo Elections ✅

**First Vote**:
1. User gets code
2. Submits code and votes
3. Vote saved successfully
4. `code.has_voted = true`

**Second Vote**:
1. User requests code page again
2. System detects: `has_voted = true` AND `type = 'demo'`
3. **Resets flags**:
   - `has_voted = false`
   - `is_code1_usable = 1`
   - `vote_submitted = false`
   - Generates new code
4. Sends new code via email
5. User can vote again ✅

### Real Elections ✅

**First Vote**:
1. User gets code
2. Submits code and votes
3. Vote saved successfully
4. `code.has_voted = true`

**Second Vote Attempt**:
1. User tries to submit code again
2. System checks: `has_voted = true` AND `type = 'real'`
3. **Blocks with error**: "You have already voted in this election"
4. Cannot vote again ❌ (by design)

---

## Testing the Fix

### Test Demo Re-Voting

```bash
# 1. Start first voting session
curl -X GET "http://localhost:8000/v/{slug}/code/create"

# 2. Get the code from email/logs
# Code: 123456

# 3. Submit first code
curl -X POST "http://localhost:8000/v/{slug}/code/verify" \
  -d "voting_code=123456"

# 4. Complete voting flow (Agreement → Vote → Verify → Submit)

# 5. Go back to code page
curl -X GET "http://localhost:8000/v/{slug}/code/create"

# 6. Should see NEW code
# Code: 654321

# 7. Submit new code - should work! ✅
curl -X POST "http://localhost:8000/v/{slug}/code/verify" \
  -d "voting_code=654321"

# 8. Vote again and complete
```

### Verify in Database

```php
php artisan tinker

// Check code history
$code = \App\Models\Code::where('user_id', $user->user_id)
    ->where('election_id', 1)  // Demo election
    ->first();

echo "First vote at: " . $code->created_at;
echo "Last updated: " . $code->updated_at;
echo "Has voted: " . $code->has_voted;
echo "Is usable: " . $code->is_code1_usable;

// Check votes
$votes = \App\Models\DemoVote::where('election_id', 1)->count();
echo "Total demo votes: $votes";  // Should be 2 now
```

---

## Files Modified

```
app/Http/Controllers/CodeController.php
  └── getOrCreateCode() - Reset flags for demo elections
  └── store() - Block double voting for real elections
```

## Configuration

No configuration needed. The fix automatically:
- Detects election type from `Election.type`
- For `type = 'demo'`: Allows re-voting
- For `type = 'real'`: Prevents re-voting

## Logs to Monitor

```bash
# Watch for demo re-voting
grep "Demo election - resetting code for re-voting" storage/logs/laravel.log

# Watch for prevented real election re-voting
grep "Real election - double vote attempt prevented" storage/logs/laravel.log
```

---

## Summary

| Scenario | Before | After |
|----------|--------|-------|
| Demo: Vote once | ✅ Works | ✅ Works |
| Demo: Vote twice | ❌ Blocked | ✅ Works (by design) |
| Real: Vote once | ✅ Works | ✅ Works |
| Real: Vote twice | ❌ Error | ❌ Error (by design) |

**Key Insight**: Demo elections are for testing, so they should allow multiple votes. Real elections prevent double voting.

---

Last Updated: **2026-02-04**
