# 🐛 Vote Submission Redirect Loop - Root Cause Analysis

**Date**: February 2026
**Status**: Identified & Solution Provided
**Severity**: Critical

---

## Problem Statement

When a voter clicks **"Submit Vote"** on the `/vote/create` page, they are redirected back to `/code/create` instead of proceeding to the verification page. Additionally, a verification code is sent via email again, suggesting the voting workflow is restarting.

---

## Root Cause Analysis

### Issue Flow Diagram

```
User Flow → User Submits Vote
           ↓
           first_submission() called (line 420)
           ↓
           vote_pre_check($code) called (line 460)
           ↓
           Line 2487-2489: if($code->is_code1_usable)
                           return "code.create"  ← REDIRECT BACK!
           ↓
           Voter redirected to /code/create
           ↓
           Code generation triggered again
           ↓
           Email sent with new code
```

### Code Location: `app/Http/Controllers/Demo/DemoVoteController.php`

**Method**: `vote_pre_check()` (lines 2448-2510)

```php
public function vote_pre_check(&$code){
    // ... other checks ...

    // Line 2487-2489: THIS IS THE PROBLEMATIC CHECK
    if($code->is_code1_usable ){
        return   $return_to ="code.create";  // ❌ REDIRECTS BACK TO CODE CREATION
    }

    // ... time check ...
    return $return_to;
}
```

### Why This Happens

1. **Code1 is issued with `is_code1_usable = 1`** (marked as usable)
2. **User enters Code1** at `/code/create` page
3. **User is redirected to `/vote/create`** to submit their vote
4. **User submits vote** from `/vote/create` page
5. **`vote_pre_check()` is called** to validate before storing vote
6. **Line 2487: `if($code->is_code1_usable)`** checks if code is STILL usable
   - **If TRUE**: Assumes user hasn't used Code1 yet → Redirects to `'code.create'`
   - **This is WRONG** because user HAS already used Code1 to enter the voting page
7. **Redirect loop occurs** because after redirect, the form reset triggers code re-issuance

### The Logic Bug

The current code has **inverted logic**:

```php
// Current (WRONG): Checks if code IS STILL usable (= 1)
if($code->is_code1_usable ){                    // If true (= 1)
    return "code.create";                        // Assumes NOT used, redirect
}

// Correct Logic: Should check if code was ALREADY used (= 0)
if($code->is_code1_usable ){                    // If still = 1 (unused)
    // This means user went from code page directly to vote page
    // without properly verifying Code1 first
    // BUT: if they're here in vote_pre_check, they DID verify it!
}
```

---

## Code State Transitions (Current Broken Flow)

| Step | Method | `is_code1_usable` | `code1_used_at` | `can_vote_now` | Action |
|------|--------|-------------------|-----------------|----------------|--------|
| 1 | Code issued | `1` (true) | NULL | `0` (false) | Email sent with Code1 |
| 2 | User enters Code1 | `0` (false) → should be set | NOW | `1` (true) → should be set | User proceeds to `/vote/create` |
| 3 | User submits vote | `0` (false) | TIME set | `1` (true) | `vote_pre_check()` called |
| 4 | `vote_pre_check()` | Checks value... | | | **Issue**: Logic broken here |

### The Missing Logic

The code doesn't properly track that **Code1 was already used for entry**. When we get to `vote_pre_check()`, we need to know:
- ✅ Code1 was already verified and used to enter voting page
- ✅ User is NOW submitting their votes
- ✅ Code1 should be asked AGAIN at verification step (per your requirement)
- ❌ But we can't reject them here because they already used Code1 to get here

---

## Your New Requirement

You stated: **"The first code must be given two times: 1) when opening the voting form 2) when verifying the voted persons or candidates. Same code $code->code1 should do two works now."**

This changes the entire flow:

### New Correct Flow

```
Step 1: User requests code
├─ Code1 generated and emailed
├─ is_code1_usable = 1

Step 2: User enters Code1 at /code/create
├─ Code1 verified and marked as used
├─ code1_used_at = NOW
├─ is_code1_usable = 0  ← Mark as used
├─ Redirect to /vote/create

Step 3: User submits vote at /vote/create
├─ vote_pre_check() validates timing (NOT code state)
├─ Store vote in session
├─ Redirect to /vote/verify

Step 4: User enters Code1 AGAIN at /vote/verify (second use of same code)
├─ Code1 verified again
├─ Check hasn't been used more than 2 times
├─ Save final vote
├─ Show confirmation
```

---

## Why Code is Sent Again

In `first_submission()` method (line 460), when `vote_pre_check()` returns `'code.create'`:

```php
// Line 470-481 in first_submission()
if ($pre_check_route && $pre_check_route != "") {
    if ($pre_check_route === 'code.create') {
        $pre_check_route = $voterSlug ? 'slug.code.create' : 'vote.create';
        $routeParams = $voterSlug ? ['vslug' => $voterSlug->slug] : [];
        return redirect()->route($pre_check_route, $routeParams);  // ← Redirects to code creation
    }
}
```

When user is redirected to `slug.code.create` route, the code creation process is triggered again, which causes:
1. New code to be generated
2. Email to be sent with the new code
3. `is_code1_usable` reset to 1
4. Loop continues

---

## Required Fix

### Fix Step 1: Correct `vote_pre_check()` Logic

The method should **NOT check if Code1 is still usable** when we're in the vote submission phase.

**Current broken code (lines 2487-2489):**
```php
if($code->is_code1_usable ){
    return   $return_to ="code.create";
}
```

**Should be removed or changed** because:
- If user is in `vote_pre_check()`, they have ALREADY verified Code1
- The check was meant for blocking direct access to voting without code entry
- But the routing already prevents this

### Fix Step 2: Track Code Usage State

Add a new flag to track **how many times Code1 was used**:

```php
// In DemoCode model/migration
- code1_usage_count: 0 → 1 (first use) → 2 (second use)
- OR: has_used_code1_for_entry: boolean
- OR: has_used_code1_for_verification: boolean
```

### Fix Step 3: Update Verification Flow

In the verification step (around line 2700), require Code1 again:

```php
// Before saving vote, ask for Code1 again
if ($request->input('verification_code') !== $code->code1) {
    return error: "Invalid verification code";
}

// Mark code as used twice
$code->code1_usage_count = 2;
$code->save();
```

---

## Implementation Changes Needed

### File: `app/Http/Controllers/Demo/DemoVoteController.php`

#### Change 1: Fix `vote_pre_check()` method

**Remove lines 2487-2489:**
```php
// DELETE THIS:
if($code->is_code1_usable ){
    return   $return_to ="code.create";
}
```

**Replace with proper validation:**
```php
// Only check if code1 was ALREADY USED for entry
// This is tracked by code1_used_at being set
if(!$code->code1_used_at){
    // User somehow got here without entering Code1 first
    return "code.create";
}
```

#### Change 2: Update code state when Code1 is verified

In the code entry method (around `/code/create`), when user enters Code1:

**Current flow:**
```php
$code->can_vote_now = 1;  // Allow voting
// Missing: $code->code1_used_at = NOW;
// Missing: $code->is_code1_usable = 0;
```

**Should be:**
```php
$code->code1_used_at = Carbon::now();      // Track when used
$code->is_code1_usable = 0;                 // Mark as used
$code->can_vote_now = 1;                    // Allow voting
$code->save();
```

#### Change 3: Update verification flow

When user submits verification code, require Code1 again:

```php
// In vote verification handler
$verification_code = $request->input('verification_code');

if ($verification_code !== $code->code1) {
    return error: "Invalid verification code. Please enter the code from your email.";
}

// Mark as verified for second time
$code->code1_usage_count = ($code->code1_usage_count ?? 0) + 1;
$code->save();

// Save vote
// ...
```

---

## Database Migration Required

Add tracking for Code1 usage:

```php
// In DemoCode table migration
Schema::table('demo_codes', function (Blueprint $table) {
    // Add after existing columns
    $table->unsignedTinyInteger('code1_usage_count')->default(0)->after('code1_used_at');
    // Or add these flags:
    $table->boolean('has_used_code1_for_entry')->default(false)->after('is_code1_usable');
    $table->boolean('has_used_code1_for_verification')->default(false)->after('has_used_code1_for_entry');
    $table->timestamp('code1_used_for_verification_at')->nullable()->after('code1_used_at');
});
```

---

## Testing Scenarios

### Test 1: Correct Flow (Should Work)
```
1. Request code → Email sent
2. Enter Code1 at /code/create
3. ✅ Redirected to /vote/create
4. Submit vote selection
5. ✅ Redirected to /vote/verify
6. Enter Code1 again at /vote/verify
7. ✅ Vote saved, confirmation shown
```

### Test 2: Code Timeout (Should Reset)
```
1. Request code
2. Enter Code1 at time = 00:00
3. Wait > voting_time_in_minutes
4. At /vote/create, vote_pre_check() should reset code
5. ✅ Redirected to /code/create
6. New code sent
```

### Test 3: Direct Access (Should Block)
```
1. Try to access /vote/create without entering Code1
2. ✅ Should be blocked (check middleware)
3. ✅ Redirected to /code/create
```

---

## Summary of the Fix

| Issue | Cause | Fix |
|-------|-------|-----|
| **Redirect loop** | `vote_pre_check()` checks if Code1 is still usable after user entered it | Remove/fix the `is_code1_usable` check on line 2487 |
| **Code sent again** | Redirect to code.create triggers re-issuance | Fix vote_pre_check() to not redirect in this case |
| **Two-step code entry** | Current code only uses Code1 once | Add tracking for Code1 usage count (0→1→2) |
| **No second verification** | Code2 flow is broken, Code1 not reused | Update verification step to ask Code1 again |

---

## Files to Modify

1. **`app/Http/Controllers/Demo/DemoVoteController.php`**
   - Fix `vote_pre_check()` method (lines 2448-2510)
   - Update verification code handling

2. **`database/migrations/xxxx_create_demo_codes_table.php`** (or add new migration)
   - Add `code1_usage_count` column
   - Or add `has_used_code1_for_entry` and `has_used_code1_for_verification` flags

3. **Tests**
   - Add test for two-step code verification
   - Add test for redirect loop prevention
   - Add test for code timeout handling

---

**Status**: Ready for Implementation ✅
**Complexity**: Medium
**Breaking Changes**: Yes (vote verification flow changes)
**Backward Compatibility**: No (Code2 removed, Code1 used twice instead)
