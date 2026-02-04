# Voting Security Fixes - Demo vs Real Elections

## Overview

Implementation of dual voting logic:
- **Demo Elections**: Allow multiple voting sessions (for testing)
- **Real Elections**: Prevent double voting at all entry points

---

## Three-Layer Protection for Real Elections

### Layer 1: Code Create Page (Block Entry)

**File**: `app/Http/Controllers/CodeController.php::create()` line 77

**When**: User tries to access `/code/create` page after already voting

```php
// ⛔ REAL ELECTIONS: Block access to code page if already voted
if ($election->type === 'real' && $existingCode && $existingCode->has_voted) {
    Log::warning('⛔ Real election - blocking code page access', [
        'user_id' => $user->id,
        'election_id' => $election->id,
    ]);
    return $this->redirectToDashboard(
        'You have already voted in this election. Each voter can only vote once.'
    );
}
```

**Result**: User cannot even see the code page after voting in real election ✅

---

### Layer 2: Code Submission (Block Processing)

**File**: `app/Http/Controllers/CodeController.php::store()` line 195

**When**: User tries to submit code after already voting

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

**Result**: Even if they bypass Layer 1, submission is rejected ✅

---

### Layer 3: Code Reset Prevention

**File**: `app/Http/Controllers/CodeController.php::getOrCreateCode()` line 507

**When**: System generates codes

For **Real Elections**:
```php
// REAL: Do NOT reset voting flags
// If has_voted = true, keep it true
// User cannot get new code to vote again
```

For **Demo Elections**:
```php
// DEMO: Reset voting flags to allow re-voting
if ($code && $code->has_voted && $election->type === 'demo') {
    $code->update([
        'has_voted' => false,           // Allow voting again
        'vote_submitted' => false,
        'is_code1_usable' => 1,         // Code is usable again
        'code1' => $this->generateCode(),  // Generate new code
        'code1_sent_at' => now(),
    ]);
    // Send new code
}
```

**Result**: Real election voters cannot get new codes; demo voters can ✅

---

## Complete Flow Comparison

### Real Election - First Vote ✅

```
1. User visits /code/create
   ├── Layer 1: has_voted = false → ALLOW
   └── Sees code page

2. Submits code
   ├── Layer 2: has_voted = false → ALLOW
   └── Code verified, proceeds to agreement

3. Completes voting
   └── vote.save()
   └── code.has_voted = true (SET)
```

### Real Election - Second Vote Attempt ❌

```
1. User tries /code/create again
   ├── Layer 1: has_voted = true AND type = 'real' → BLOCK
   └── "You have already voted" error
   └── Redirected to dashboard

   (Even if they bypass Layer 1)

2. Tries to submit code
   ├── Layer 2: has_voted = true AND type = 'real' → BLOCK
   └── "You have already voted" error
   └── Cannot proceed

   (Even if they bypass Layer 2)

3. Tries to get new code
   ├── Layer 3: has_voted = true AND type = 'real' → NO RESET
   └── Code remains marked as voted
   └── Cannot vote again
```

### Demo Election - First Vote ✅

```
1. User visits /code/create
   ├── Layer 1: has_voted = false → ALLOW
   └── Sees code page

2. Submits code
   ├── Layer 2: type = 'demo' → SKIP
   └── Code verified, proceeds

3. Completes voting
   └── vote.save()
   └── code.has_voted = true (SET)
```

### Demo Election - Second Vote ✅

```
1. User tries /code/create again
   ├── Layer 1: type = 'demo' → SKIP (only for 'real')
   └── Sees code page

2. System detects: has_voted = true AND type = 'demo'
   ├── Layer 3: RESET VOTING FLAGS
   ├── has_voted = false (RESET)
   ├── is_code1_usable = 1 (RESET)
   ├── Generate new code
   └── Send via email

3. Submits new code
   ├── Layer 2: type = 'demo' → SKIP
   └── Code verified, proceeds

4. Completes second vote
   └── vote.save() again
   └── New vote recorded ✅
```

---

## Database State Tracking

### Real Election User After Voting

```sql
SELECT id, user_id, election_id, has_voted, is_code1_usable, vote_submitted
FROM codes
WHERE user_id = 'real_voter' AND election_id = 2;

-- Result:
id | user_id    | election_id | has_voted | is_code1_usable | vote_submitted
1  | real_voter | 2           | 1         | 0               | 1

-- User CANNOT change these flags
-- Blocks all re-voting attempts
```

### Demo Election User After Second Vote

```sql
SELECT id, user_id, election_id, has_voted, is_code1_usable, vote_submitted
FROM codes
WHERE user_id = 'demo_voter' AND election_id = 1;

-- After first vote:
id | user_id    | election_id | has_voted | is_code1_usable | vote_submitted
1  | demo_voter | 1           | 1         | 0               | 1

-- After requesting second vote:
id | user_id    | election_id | has_voted | is_code1_usable | vote_submitted
1  | demo_voter | 1           | 0         | 1               | 0

-- Flags are RESET
-- User can vote again
```

---

## Logs Generated

### Real Election Blocking

```
⛔ Real election - blocking code page access for voter who already voted
   user_id: real_voter
   election_id: 2
   code_id: 123

Real election - double vote attempt prevented
   user_id: real_voter
   election_id: 2
```

### Demo Election Re-Voting

```
🔄 Demo election - resetting code for re-voting
   user_id: demo_voter
   code_id: 456

✅ New demo voting code sent
   user_id: demo_voter
   code_id: 456
   code: ABC123
```

---

## Testing Checklist

### Real Election Security

- [ ] First vote works normally
- [ ] After voting, code page shows redirect message
- [ ] Cannot access code page: "You have already voted"
- [ ] Cannot submit old code again: "You have already voted"
- [ ] No new codes generated after voting
- [ ] Logs show blocking attempts

### Demo Election Re-Voting

- [ ] First vote works normally
- [ ] After voting, code page still accessible
- [ ] New code generated automatically
- [ ] New code sent via email
- [ ] Can submit new code and vote again
- [ ] Second vote saved successfully
- [ ] Logs show "Demo election - resetting code"

---

## SQL Verification

```sql
-- Check real election votes (should be 1 or 0)
SELECT user_id, COUNT(*) as vote_count
FROM votes
WHERE election_id = 2
GROUP BY user_id
HAVING COUNT(*) > 1;
-- Should return 0 rows (no double voting)

-- Check demo election votes (can be multiple)
SELECT user_id, COUNT(*) as vote_count
FROM demo_votes
WHERE election_id = 1
GROUP BY user_id
HAVING COUNT(*) > 0;
-- Can return multiple per user (allowed for testing)
```

---

## Configuration

No configuration needed. Checks are automatic:

```php
// Type check:
if ($election->type === 'real')  // Blocks re-voting
if ($election->type === 'demo')  // Allows re-voting
```

Based on `elections.type` column:
- `'demo'` → Multiple votes allowed
- `'real'` → Single vote only

---

## Summary Table

| Feature | Real Election | Demo Election |
|---------|---------------|---------------|
| **Access code page (1st vote)** | ✅ Allowed | ✅ Allowed |
| **Submit code (1st vote)** | ✅ Allowed | ✅ Allowed |
| **Complete voting (1st vote)** | ✅ Saved | ✅ Saved |
| **Access code page (2nd attempt)** | ❌ Blocked | ✅ Allowed |
| **Submit code (2nd attempt)** | ❌ Blocked | ✅ Allowed |
| **Get new code (after vote)** | ❌ Blocked | ✅ Reset + New |
| **Complete voting (2nd vote)** | ❌ Impossible | ✅ Saved |

---

## Files Modified

```
app/Http/Controllers/CodeController.php
  ├── create() - Layer 1: Block real election access
  ├── store() - Layer 2: Block real election submission
  └── getOrCreateCode() - Layer 3: Reset flags for demo only
```

---

## Security Benefits

✅ **Real Elections**:
- Cannot vote twice
- Cannot bypass at any layer
- Cannot trick system into resetting flags
- Each voter = 1 vote

✅ **Demo Elections**:
- Can test multiple voting scenarios
- Fresh code each session
- Flags reset automatically
- No manual intervention needed

---

Last Updated: **2026-02-04**
