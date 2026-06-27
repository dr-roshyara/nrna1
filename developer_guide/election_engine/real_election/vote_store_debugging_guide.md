# Vote Submission Debugging Guide
# `VoteController::store()` — Real Election Flow

**Branch:** `postgressql`
**Session Date:** 2026-05-12
**Author:** Debugging session with Claude

---

## Overview

This guide documents the exact debugging methodology and root causes found when
`POST /v/{vslug}/vote/verify` (`slug.vote.store`) was looping back to the election
start page instead of completing the vote.

**Symptoms:**
- Voter at step 4 (VoteVerify page) submits verification code
- Browser redirects to `http://localhost/elections/{election-slug}` (election start)
- Laravel logs show `DASHBOARD_DEBUG` immediately after the POST
- `voter_slugs.current_step` stays at `4`, `has_voted` stays `false`
- No error visible to the user

---

## Quick Diagnosis Flowchart

```
POST /v/{vslug}/vote/verify redirects unexpectedly?
│
├─ Do Laravel logs show DASHBOARD_DEBUG after the POST?
│  └─ YES → EnsureElectionVoter middleware is blocking
│           (redirects to election.dashboard → DASHBOARD_DEBUG → elections.show)
│
├─ Does a dd() at the TOP of store() fire?
│  ├─ NO  → Middleware is blocking before store() (see Layer-by-Layer below)
│  └─ YES → Issue is INSIDE store() (continue tracing with dd())
│
└─ Does save_vote() complete?
   ├─ NO  → Exception caught → handleVoteError() → redirect()->back()
   └─ YES → Check redirect target (should be vote.verify_to_show)
```

---

## The dd() Tracing Method

When logs alone are insufficient, place `dd()` calls at key checkpoints.
Work top-down through `store()`. Each dd() confirms execution reached that line.

### Checkpoint 1 — Did store() even run?

```php
public function store(Request $request)
{
    dd('STORE_REACHED', [
        'user_id'       => auth()->id(),
        'user_org_id'   => auth()->user()?->organisation_id,
        'vslug'         => $request->route('vslug'),
        'voter_slug'    => $request->attributes->get('voter_slug')?->slug,
        'election'      => $request->attributes->get('election')?->id,
        'election_type' => $request->attributes->get('election')?->type,
        'session_keys'  => array_keys($request->session()->all()),
    ]);
```

**If no dump appears** → middleware is blocking (skip to Layer-by-Layer section).
**If dump appears** → continue to Checkpoint 2.

### Checkpoint 2 — Is the org check blocking?

Add expanded diagnostics to see org match:

```php
$__election = $request->attributes->get('election');
$__user     = auth()->user();
dd('STORE_DIAGNOSTICS', [
    'user_org_id'     => $__user?->organisation_id,
    'election_org_id' => $__election?->organisation_id,
    'org_match'       => $__user?->organisation_id === $__election?->organisation_id,
    'election_type'   => $__election?->type,
    'voter_slug_step' => $request->attributes->get('voter_slug')?->current_step,
    'all_input_keys'  => array_keys($request->all()),
    'session_keys'    => array_keys($request->session()->all()),
]);
```

**If `org_match => false`** → see Bug #1 below.

### Checkpoint 3 — Is vote_data loaded from session?

```php
$session_name = $code->session_name ?: ('vote_data_' . $auth_user->id);
$vote_data    = $request->session()->get($session_name);
dd('SESSION_CHECK', [
    'code_session_name'    => $code->session_name,
    'resolved_session_key' => $session_name,
    'vote_data_is_null'    => is_null($vote_data),
    'vote_data_keys'       => is_array($vote_data) ? array_keys($vote_data) : 'NOT_ARRAY',
]);
```

**If `vote_data_is_null => true`** → see Bug #2 below.

### Checkpoint 4 — Did save_vote() complete?

```php
$this->save_vote($vote_data, $vote_hashed_key, $election, $auth_user, $private_key);
dd('SAVE_VOTE_COMPLETED', [
    'out_code'    => $this->out_code,   // UUID of saved vote record
    'private_key' => substr($private_key, 0, 8) . '...',
]);
```

**If no dump** → `save_vote()` threw an exception → caught by the outer
`catch (\Exception $e)` → `handleVoteError()` → `redirect()->back()`.
Check `storage/logs/laravel.log` for `Vote submission failed`.

---

## Bug #1 — Organisation Mismatch Blocking Legitimate Voters (FIXED 2026-05-12)

### Location
`VoteController::store()` — PHASE 3 VALIDATION block (lines ~1462–1480 before fix)

### Symptom
`org_match => false` in the diagnostic dump. The controller was checking
`$auth_user->organisation_id !== $election->organisation_id` and redirecting to
`route('dashboard')` if they differed.

```
user_org_id:     a1c19bfb-9f09-4808-bbff-83018dc05a4e
election_org_id: a1b2c20e-fecc-41c8-a166-e1f851ff5145
org_match:       false  ← BLOCKER
```

### Root Cause
The `user.organisation_id` field on the `users` table is a profile convenience
field. It is NOT the authoritative voter registry. A voter's eligibility for a
specific election is stored in `election_memberships` (role=voter, status=active).

`EnsureElectionVoter` middleware (Layer 5) already validates voter membership
against `election_memberships` before `store()` is reached. The redundant
`organisation_id` check inside `store()` created false negatives for any voter
whose profile `organisation_id` differed from the election's `organisation_id`
(e.g. voters added cross-org, or when `users.organisation_id` was not updated
when accepting an invitation).

### Fix Applied

Removed the redundant org_id check from `store()`. `ElectionMembership` is the
authoritative voter registry; if the middleware passed, the voter is legitimate.

```php
// REMOVED (was lines ~1462-1480):
// if ($election->type === 'real') {
//     if ($auth_user->organisation_id !== $election->organisation_id) {
//         DB::rollBack();
//         return redirect()->route('dashboard')->withErrors([...]);
//     }
// }

// REPLACED WITH comment explaining the architectural decision:
// Voter eligibility is already enforced by EnsureElectionVoter middleware
// and ensureVoterMembership() above. ElectionMembership is the authoritative
// voter registry. user.organisation_id is a profile field, not an eligibility gate.
```

### Future Prevention
- Never gate vote submission on `user.organisation_id` directly.
- Use `ElectionMembership::where('user_id', ...)->where('election_id', ...)->where('role', 'voter')->where('status', 'active')->exists()` for eligibility checks.
- When accepting org invitations, always update `users.organisation_id` to keep the field consistent (prevents confusion even if not used as a gate).

---

## Bug #2 — session_name Null Fallback Missing (FIXED 2026-05-12)

### Location
`VoteController::store()` line ~1560 (before fix)

### Symptom
`vote_data_is_null => true` even though session had `vote_data_{user_id}` key.

### Root Cause
`first_submission()` stores vote data using:
```php
// first_submission() line ~603:
$session_name = $code->session_name ?: ('vote_data_' . $auth_user->id);
$request->session()->put($session_name, $vote_data);
```

If `code->session_name` is null at that point, it falls back to `vote_data_{user_id}`.
But `store()` read the session WITHOUT the same fallback:

```php
// store() BEFORE fix — no fallback:
$session_name = $code->session_name;         // null if not set
$vote_data    = $request->session()->get($session_name);  // always null
```

### Fix Applied

```php
// store() AFTER fix — mirrors first_submission() fallback:
$session_name = $code->session_name ?: ('vote_data_' . $auth_user->id);
$vote_data    = $request->session()->get($session_name);
```

### Future Prevention
Always use the same session key derivation logic in both the write
(`first_submission`) and the read (`store`) side. Consider extracting to a
shared method:

```php
private function resolveSessionName(Code $code, User $user): string
{
    return $code->session_name ?: ('vote_data_' . $user->id);
}
```

---

## Layer-by-Layer Middleware Debugging

When `store()` is never reached (no dd() output), work through the 9 middleware
layers in order. Each layer logs a START entry — find where the logs stop.

```
Layer 1  SubstituteBindings          → Route model binding
Layer 2  VerifyVoterSlug             → Slug exists and is_active=1
Layer 3  ValidateVoterSlugWindow     → Slug not expired
Layer 4  VerifyVoterSlugConsistency  → Sets election on request attributes
Layer 5  EnsureElectionVoter         → ElectionMembership check → redirects to election.dashboard
Layer 6  EnsureVoterStepOrder        → current_step matches target step
Layer 7  VoteEligibility             → Bypassed when voter_slug present
Layer 8  ValidateVotingIp            → IP restriction check
Layer 9  EnsureRealVoteOrganisation  → org_id check (redundant but exists)
```

### Layer 5 Tinker Check (most common blocker)

```bash
php artisan tinker
$user = \App\Models\User::find('USER_ID');
$election = \App\Models\Election::where('slug', 'ELECTION_SLUG')->first();

echo "user.organisation_id:     " . $user->organisation_id . "\n";
echo "election.organisation_id: " . $election->organisation_id . "\n";

$m = \App\Models\ElectionMembership
    ::where('user_id', $user->id)
    ->where('election_id', $election->id)
    ->where('role', 'voter')
    ->where('status', 'active')
    ->first();

echo "membership: " . ($m ? $m->status : 'NOT FOUND') . "\n";
```

**No membership found** → add voter via admin or directly:
```php
\App\Models\ElectionMembership::create([
    'user_id'     => $user->id,
    'election_id' => $election->id,
    'role'        => 'voter',
    'status'      => 'active',
    'assigned_at' => now(),
]);
$user->invalidateVoterCache($election->id);
```

### Layer 4 — Election on request attributes

`VerifyVoterSlugConsistency` sets the election on `$request->attributes`. If it
does not run or the voter slug has no `election_id`, all downstream middleware
will fail to resolve the election. Check:

```bash
php artisan tinker
$slug = \App\Models\VoterSlug::where('slug', 'YOUR_SLUG')->first();
echo "election_id: " . $slug->election_id . "\n";
echo "is_active: "   . $slug->is_active . "\n";
echo "expires_at: "  . $slug->expires_at . "\n";
```

---

## Common Bug Table

| Symptom | Root Cause | Fix |
|---------|-----------|-----|
| Redirects to `elections/{slug}` with `DASHBOARD_DEBUG` in log | `EnsureElectionVoter` → no active `ElectionMembership` | Add voter to `election_memberships` |
| `org_match => false` in diagnostic dump | Redundant `user.organisation_id` check inside `store()` | Fixed: removed that check (2026-05-12) |
| `vote_data_is_null => true` | `code->session_name` null with no fallback in `store()` | Fixed: added `?: 'vote_data_' . $auth_user->id` fallback |
| Redirects to `/dashboard` with "already voted" error | `code->has_voted` is already `true` for a fresh attempt | Clear the code record in DB or use a new slug |
| `voting_code` validation fails | User entered wrong 8-char code | Show `debug_code` in local env (`$showDebugCode` in `verify()`) |
| `save_vote()` exception → `redirect()->back()` | DB constraint, null vote_data, or missing column | Check `laravel.log` for `Vote submission failed` entry |
| Vue prop error on VoteVerify page | `VotingLayout` validator required numeric `election.id` | Fixed: `Election` uses UUID strings (HasUuids trait) |

---

## The DASHBOARD_DEBUG Trail Explained

```
POST /v/{slug}/vote/verify
    │
    ├─ EnsureElectionVoter::handle()
    │      isVoterInElection() → false
    │      return redirect()->route('election.dashboard')
    │
    └─ GET /election  [election.dashboard route]
           ElectionManagementController::dashboard()
           Log::info('DASHBOARD_DEBUG')    ← appears in logs
           return redirect()->route('elections.show', $election->slug)
                │
                └─ GET /elections/{slug}  ← "election start page" the user sees
                       On mount, ElectionController::startDemo() or show()
                       creates a new voter slug if none exists
                       → user appears to "start over"
```

This trail is deterministic. If `DASHBOARD_DEBUG` appears in logs after a vote
POST, the cause is ALWAYS `EnsureElectionVoter` rejecting the user.

---

## Verification After Fix

```bash
# Clear log and test full flow
rm -f storage/logs/laravel.log && touch storage/logs/laravel.log

# Run through all 5 steps as the voter
# Then check log for clean progression:
grep -E "(EnsureElectionVoter|DASHBOARD_DEBUG|save_vote|vote_confirmed)" storage/logs/laravel.log
```

Expected output:
```
✅ [EnsureElectionVoter] Voter verified - proceeding
✅ Vote submission validated at controller level
✅ [vote_confirmed] event logged
```

---

**Last Updated:** 2026-05-12
**Author:** Debugging session (Claude)
**Related Files:**
- `app/Http/Controllers/VoteController.php` — `store()` method
- `app/Http/Middleware/EnsureElectionVoter.php`
- `app/Http/Middleware/VerifyVoterSlugConsistency.php`
- `developer_guide/election_engine/real_election/VOTER_ACCESS_DEBUGGING.md`
