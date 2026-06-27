# Voter Access Debugging Guide

## Overview

When a voter cannot access the voting page (receives 302 redirect, access denied, or other blocking), follow this systematic debugging guide to identify the root cause.

**Golden Rule:** The voter must pass **9 middleware layers** AND **3 database validation checks** before reaching the voting controller.

---

## Quick Diagnosis Flowchart

```
Voter cannot access voting page?
│
├─ Check browser console / HTTP status
│  ├─ 302 Found / Redirect? → Middleware is blocking
│  ├─ 403 Forbidden? → Permission/authorization issue
│  ├─ 404 Not Found? → Resource missing
│  └─ 200 OK but page blank? → Controller issue
│
├─ Check storage/logs/laravel.log for "middleware START" logs
│  ├─ Logs stop at specific middleware? → That middleware is blocking
│  └─ No logs at all? → Authentication failed first
│
└─ If logs show voting page was reached:
   └─ Check controller logic (CodeController, VoteController, etc.)
```

---

## Step 1: Capture Complete Logs

### Clear and capture fresh logs
```bash
# Clear the log file
rm -f storage/logs/laravel.log
touch storage/logs/laravel.log

# Have voter attempt access
# Then immediately capture:
tail -100 storage/logs/laravel.log | grep -E "Middleware|START|ENTER|allowed|blocking|error"
```

### What to look for:
```
✅ PASS: 
  ✅ [VerifyVoterSlug] Verification passed
  ✅ [ValidateVoterSlugWindow] Slug valid
  ✅ [VerifyVoterSlugConsistency] All checks passed
  ✅ [EnsureElectionVoter] Voter verified - proceeding
  ✅ [VoteEligibility] Voter slug present - bypassing legacy check
  ✅ [ValidateVotingIp] User has no IP restriction - allowing

❌ FAIL:
  ❌ [VerifyVoterSlug] Verification failed
  ⚠️ [ValidateVoterSlugWindow] Slug expired
  ❌ [EnsureElectionVoter] NOT voter
  ⚠️ [ValidateVotingIp] IP mismatch detected
```

**Red Flag:** If logs stop abruptly after a middleware START without a PASS or FAIL log, the middleware is redirecting silently (check for redirect/back() calls).

---

## Step 2: The 9-Layer Middleware Stack

For slug-based voting routes (`/v/{slug}/*`), these middleware run in order:

```
1. SubstituteBindings        → Route model binding
2. VerifyVoterSlug           → Slug exists & valid
3. ValidateVoterSlugWindow   → Slug not expired
4. VerifyVoterSlugConsistency→ Slug matches election
5. EnsureElectionVoter       → User is registered voter
6. EnsureVoterStepOrder      → User at correct step
7. VoteEligibility           → Legacy can_vote check
8. ValidateVotingIp          → IP restriction check
9. EnsureRealVoteOrganisation→ Organisation match (❌ FOUND THIS IN DEBUGGING)
```

**If logs show one middleware but NOT the next:**
- The current middleware is blocking
- Check its file in `app/Http/Middleware/`
- Look for `return back()`, `abort()`, or `redirect()` statements

---

## Step 3: Check Each Middleware Layer

### Layer 1-3: Voter Slug Validation
```bash
# Check if voter slug exists and is valid
php artisan tinker <<'EOF'
$slug = \App\Models\VoterSlug::where('slug', 'YOUR_SLUG_HERE')->first();

if (!$slug) {
    echo "❌ FAIL: Slug not found\n";
} else {
    echo "✅ Slug exists\n";
    echo "   is_active: {$slug->is_active}\n";
    echo "   expires_at: {$slug->expires_at}\n";
    echo "   expired: " . ($slug->expires_at->isPast() ? "YES ❌" : "NO ✅") . "\n";
    echo "   election_id: {$slug->election_id}\n";
}
EOF
```

**Common Issues:**
- Slug doesn't exist → User never created voting session
- is_active = 0 → Slug was marked inactive
- expires_at is in past → Voting window closed

### Layer 4: Election Consistency Check
```bash
php artisan tinker <<'EOF'
$slug = \App\Models\VoterSlug::where('slug', 'YOUR_SLUG_HERE')->first();
$election = \App\Models\Election::find($slug->election_id);

if ($slug->election_id !== $election->id) {
    echo "❌ FAIL: Election ID mismatch\n";
} else {
    echo "✅ Election matches\n";
    echo "   Election slug: {$election->slug}\n";
    echo "   Election org: {$election->organisation_id}\n";
}
EOF
```

### Layer 5: Voter Registration Check
```bash
php artisan tinker <<'EOF'
$user = auth()->user(); // or: \App\Models\User::find('USER_ID')
$election = \App\Models\Election::find('ELECTION_ID');

$membership = \App\Models\ElectionMembership::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->where('role', 'voter')
    ->first();

if (!$membership) {
    echo "❌ FAIL: User not registered as voter\n";
} else {
    echo "✅ Voter registration found\n";
    echo "   status: {$membership->status}\n";
    echo "   role: {$membership->role}\n";
    echo "   assigned_at: {$membership->assigned_at}\n";
}
EOF
```

**Common Issues:**
- No ElectionMembership record → User was never assigned as voter
- status = 'inactive' → Voter was suspended
- status = 'pending' → Voter awaiting approval

### Layer 6: Voter Step Order Check
```bash
php artisan tinker <<'EOF'
$slug = \App\Models\VoterSlug::where('slug', 'YOUR_SLUG_HERE')->first();
$steps = \App\Models\VoterSlugStep::where('voter_slug_id', $slug->id)->get();

echo "Voter slug steps:\n";
foreach ($steps as $step) {
    echo "  Step {$step->step}: completed at {$step->completed_at}\n";
}
EOF
```

**Common Issues:**
- Trying to access step 3 before completing step 2
- Steps table has no records → step tracking failed

### Layer 7: Legacy Vote Eligibility (demo elections only)
```bash
# This is only checked if NO voter slug is present
# For real elections with voter slug, this is bypassed

$user = \App\Models\User::find('USER_ID');
echo "Legacy can_vote: {$user->can_vote}\n"; // Should be 1 for legacy flow
```

### Layer 8: IP Restriction Check ⚠️
```bash
php artisan tinker <<'EOF'
$user = \App\Models\User::find('USER_ID');

echo "User voting_ip: " . ($user->voting_ip ?? 'NULL') . "\n";
echo "Current IP: " . request()->ip() . "\n";

if ($user->voting_ip && $user->voting_ip !== request()->ip()) {
    echo "❌ FAIL: IP mismatch - voter is restricted to {$user->voting_ip}\n";
} else {
    echo "✅ IP check passed\n";
}
EOF
```

**Common Issues:**
- User has voting_ip set (from a previous vote verification)
- User's current IP differs from registered voting_ip
- Solution: Verify IP with admin or reset voting_ip if needed

### Layer 9: Organisation Match ❌ **THIS WAS THE BUG WE FOUND**
```bash
php artisan tinker <<'EOF'
$user = \App\Models\User::find('USER_ID');
$election = \App\Models\Election::find('ELECTION_ID');

echo "User organisation_id:     " . ($user->organisation_id ?? 'NULL') . "\n";
echo "Election organisation_id: " . ($election->organisation_id ?? 'NULL') . "\n";

if ($user->organisation_id !== $election->organisation_id) {
    echo "❌ FAIL: Organisation mismatch\n";
    echo "   User belongs to: " . (\App\Models\Organisation::find($user->organisation_id)?->name ?? 'UNKNOWN') . "\n";
    echo "   Election belongs to: " . (\App\Models\Organisation::find($election->organisation_id)?->name ?? 'UNKNOWN') . "\n";
} else {
    echo "✅ Organisation matches\n";
}
EOF
```

**Critical Issue:** This is the MOST COMMON issue we found. When someone accepts an organisation invitation:
- If the code doesn't update `users.organisation_id` → User is blocked
- The user IS a member (OrganisationUser record exists)
- But the EnsureRealVoteOrganisation middleware checks the `organisation_id` field directly
- **FIX:** Ensure `users.organisation_id` is set when invitation is accepted

---

## Step 4: Database Validation Checks

### Check 1: User exists and authenticated
```bash
php artisan tinker <<'EOF'
$user = auth()->user();

if (!$user) {
    echo "❌ FAIL: User not authenticated\n";
} else {
    echo "✅ User authenticated: {$user->name} ({$user->email})\n";
    echo "   user_id: {$user->id}\n";
    echo "   organisation_id: " . ($user->organisation_id ?? 'NULL') . "\n";
}
EOF
```

### Check 2: Election exists and is active
```bash
php artisan tinker <<'EOF'
$election = \App\Models\Election::find('ELECTION_ID');

if (!$election) {
    echo "❌ FAIL: Election not found\n";
} else {
    echo "✅ Election found: {$election->name}\n";
    echo "   type: {$election->type}\n";
    echo "   is_active: {$election->is_active}\n";
    echo "   status: {$election->status}\n";
    
    if (!$election->is_active) {
        echo "   ❌ ISSUE: Election is not active\n";
    }
}
EOF
```

### Check 3: Voter membership exists
```bash
php artisan tinker <<'EOF'
$membership = \App\Models\ElectionMembership::where('user_id', 'USER_ID')
    ->where('election_id', 'ELECTION_ID')
    ->first();

if (!$membership) {
    echo "❌ FAIL: No voter membership found\n";
} else {
    echo "✅ Voter membership found\n";
    echo "   status: {$membership->status}\n";
    echo "   role: {$membership->role}\n";
    echo "   assigned_at: {$membership->assigned_at}\n";
    
    if ($membership->status !== 'active') {
        echo "   ❌ ISSUE: Membership status is {$membership->status}, not 'active'\n";
    }
}
EOF
```

---

## Step 5: Controller Logic Check

If logs show all middleware passed but voter still can't vote, check the controller:

### CodeController::create() (Step 1 - Code Entry)
```bash
# Look for logs:
# 🔴 [CREATE] ENTERED create() method
# 🟣 [CREATE] Code create page accessed

# If logs show these, controller was reached
# Check for:
# ⚠️ [CREATE] User already has verified code - redirecting
# This means a Code record with can_vote_now=1 exists from before
```

### VoteController::create() (Step 3 - Vote Selection)
```bash
# Look for logs showing election/posts loaded
# If missing, check:
# - Election has posts assigned
# - User can see all posts (regional filtering)
# - Candidates are assigned to posts
```

---

## Common Issues & Quick Fixes

| Issue | Symptom | Check | Fix |
|-------|---------|-------|-----|
| Slug expired | Redirect immediately | `expires_at < now()` | Create fresh slug |
| Not a voter | Access denied message | No ElectionMembership record | Assign as voter in admin |
| Voter suspended | Access denied | ElectionMembership status='inactive' | Unsuspend voter |
| Organisation mismatch | 302 redirect | `user.organisation_id ≠ election.organisation_id` | Update user.organisation_id |
| IP restriction | IP mismatch error | `user.voting_ip` is set & differs | Reset or verify IP |
| Invitation not accepted | Can't access as member | invitation.status ≠ 'accepted' | Resend invitation |
| Missing Code record | Form not shown | Code::where(user, election)->first() = null | Manually create Code |

---

## Systematic Debugging Checklist

Use this checklist when debugging voter access issues:

```
□ Step 1: Capture logs
  □ Clear laravel.log
  □ Voter attempts access
  □ Grep for middleware logs
  □ Identify where logs stop

□ Step 2: Check middleware stack
  □ Identify which middleware is blocking
  □ Read middleware file
  □ Understand the blocking condition

□ Step 3: Database validation
  □ VoterSlug exists and valid?
  □ ElectionMembership exists?
  □ User.organisation_id matches election?
  □ Election is_active?
  □ Voter slug not expired?

□ Step 4: Permission checks
  □ User authenticated?
  □ Voter status = 'active'?
  □ No IP restrictions preventing access?
  □ Invitation status = 'accepted'?

□ Step 5: Controller/Business Logic
  □ Can controller reach database?
  □ Are posts/candidates loaded?
  □ Does election have voting window open?
  □ Are regional filters applied correctly?

□ Step 6: Fix & Test
  □ Apply fix
  □ Clear logs
  □ Test again
  □ Verify logs show ✅ progression
```

---

## Real-World Example: The Bug We Fixed

**Scenario:** User accepted organisation invitation but couldn't vote

**Debugging Steps:**
1. ✅ All middleware logs passed
2. ✅ Voter slug valid
3. ✅ ElectionMembership record exists
4. ❌ But 302 redirect still happening
5. 🔍 Checked EnsureRealVoteOrganisation middleware
6. 🐛 Found: `user.organisation_id ≠ election.organisation_id`
7. 📊 Root cause: Invitation acceptance didn't update `users.organisation_id`
8. ✅ Fix: Added `$user->update(['organisation_id' => $invitation->organisation_id])`

**Lesson:** The middleware does its job perfectly—it caught an **architectural inconsistency** (user not properly assigned to organisation). The fix wasn't in the middleware, but in the invitation acceptance flow.

---

## Advanced: Enable Debug Mode for More Logs

### Temporary Enhanced Logging
Add to middleware/controller:
```php
Log::emergency('🔴 [DEBUG] Variable state', [
    'user_id' => $user?->id,
    'user_org' => $user?->organisation_id,
    'election_org' => $election?->organisation_id,
    'match' => $user?->organisation_id === $election?->organisation_id,
]);
```

### Monitor Specific Conditions
```php
if ($user->organisation_id !== $election->organisation_id) {
    Log::channel('voting_security')->error('Organisation mismatch', [
        'user_id' => $user->id,
        'user_org' => $user->organisation_id,
        'election_org' => $election->organisation_id,
    ]);
}
```

---

## When to Escalate

If after following all steps the issue persists:

1. **Check recent migrations** - Did a schema change affect queries?
2. **Check global scopes** - Is BelongsToTenant filtering wrong data?
3. **Check relationship definitions** - Are foreign keys correct?
4. **Check middleware registration** - Is middleware registered in bootstrap?
5. **Ask:** Is this a NEW issue or REGRESSION?
   - New: Likely config/permission issue
   - Regression: Likely code/migration issue

---

## References

- Middleware stack: `routes/election/electionRoutes.php` line 417
- Middleware files: `app/Http/Middleware/`
- Controller files: `app/Http/Controllers/`
- Models: `app/Models/Election.php`, `app/Models/ElectionMembership.php`, `app/Models/VoterSlug.php`
- Database: Check `elections`, `voter_slugs`, `election_memberships`, `users` tables

---

**Last Updated:** 2026-04-13  
**Author:** Claude (Debugging Session)  
**Related Issues:** #voter-access, #organisation-mismatch
