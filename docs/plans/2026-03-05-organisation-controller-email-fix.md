# OrganisationController Email Fix Implementation Plan

> **For Claude:** REQUIRED SUB-SKILL: Use superpowers:executing-plans to implement this plan task-by-task.

**Goal:** Fix OrganisationController@store method to remove organisation created email and queue representative invitation email, resolving 500 errors and missing column issues.

**Problem Analysis:**
1. **Missing Mail Class**: `OrganizationCreatedMail` (American spelling) referenced but class is `OrganisationCreatedMail` (British spelling)
2. **Missing Database Column**: `assigned_at` column doesn't exist in `user_organisation_roles` table
3. **Blocking Email**: Email sends synchronously, causing delays and 500 errors

**Architecture:** Laravel 11 + Vue 3 + Inertia 2.0, custom multi-tenancy with organisation isolation.

---

## Current State Analysis

### File: `app/Http/Controllers/Api/OrganisationController.php`

**Problems identified:**
1. **Lines 124-135**: Organisation created email using `OrganizationCreatedMail` (typo) - causes 500 error
2. **Lines 56-59 & 97-100**: `assigned_at` column in `attach()` calls - column doesn't exist in pivot table
3. **Lines 107-118**: Representative invitation email sends synchronously - blocks response

**Existing tables:**
- `organisations` - organisation records
- `user_organisation_roles` - pivot table with columns: `user_id`, `organisation_id`, `role`, `created_at`, `updated_at`
  - **NO `assigned_at` column** - this is the root cause of database errors

---

## Design Decisions

### Approach: **Option B** (User-approved)
1. **Remove organisation created email** completely (lines 124-135)
2. **Keep representative invitation email** (lines 107-118)
3. **Queue representative email** using `afterResponse()` to make non-blocking
4. **Remove `assigned_at`** from all `attach()` calls (lines 56-59, 97-100)
5. **Fix spelling typo** not needed since email block is removed entirely

### Why This Approach:
- ✅ Eliminates 500 error from missing mail class
- ✅ Fixes database error from missing column
- ✅ Maintains important representative invitation functionality
- ✅ Non-blocking email improves user experience
- ✅ Minimal code changes, maximum impact

---

## Implementation Tasks

### Task 1: Remove Organisation Created Email Block

**Files:**
- Modify: `app/Http/Controllers/Api/OrganisationController.php`

**Steps:**
1. Delete lines 124-135 (entire try-catch block for `OrganizationCreatedMail`)
2. Remove related import `use App\Mail\OrganisationCreatedMail;` if no longer used
3. Verify no other references to `OrganizationCreatedMail` or `OrganisationCreatedMail` in controller

**Code Changes:**
```php
// BEFORE (lines 124-135):
try {
    Mail::to($organisation->email)->send(
        new OrganizationCreatedMail($organisation, $user)
    );
} catch (\Exception $e) {
    \Log::warning('Failed to send organisation created email', [
        'organisation_id' => $organisation->id,
        'organisation_email' => $organisation->email,
        'error' => $e->getMessage(),
    ]);
    // Continue even if email fails
}

// AFTER: Entire block removed
```

### Task 2: Remove assigned_at from User Attachment

**Files:**
- Modify: `app/Http/Controllers/Api/OrganisationController.php`

**Steps:**
1. Find line 56-59 (admin user attachment)
2. Remove `'assigned_at' => now(),` from array
3. Find line 97-100 (representative user attachment)
4. Remove `'assigned_at' => now(),` from array
5. Verify no other `assigned_at` references in controller

**Code Changes:**
```php
// BEFORE (line 56-59):
$organisation->users()->attach($user->id, [
    'role' => 'admin',
    'assigned_at' => now(),
]);

// AFTER:
$organisation->users()->attach($user->id, [
    'role' => 'admin'
]);

// BEFORE (line 97-100):
$organisation->users()->attach($representativeUser->id, [
    'role' => 'voter',
    'assigned_at' => now(),
]);

// AFTER:
$organisation->users()->attach($representativeUser->id, [
    'role' => 'voter'
]);
```

### Task 3: Queue Representative Invitation Email

**Files:**
- Modify: `app/Http/Controllers/Api/OrganisationController.php`

**Steps:**
1. Wrap existing representative email block (lines 107-118) in `app()->terminating()` callback
2. Keep existing error handling and logging
3. Add comment explaining non-blocking behavior

**Code Changes:**
```php
// BEFORE (lines 107-118):
try {
    Mail::to($representativeEmail)->send(
        new \App\Mail\RepresentativeInvitationMail($representativeUser, $organisation, $user)
    );
} catch (\Exception $e) {
    \Log::warning('Failed to send representative invitation email', [
        'organisation_id' => $organisation->id,
        'representative_email' => $representativeEmail,
        'error' => $e->getMessage(),
    ]);
    // Continue even if email fails
}

// AFTER:
// Queue representative invitation email (after response)
app()->terminating(function () use ($representativeEmail, $representativeUser, $organisation, $user) {
    try {
        Mail::to($representativeEmail)->send(
            new \App\Mail\RepresentativeInvitationMail($representativeUser, $organisation, $user)
        );
    } catch (\Exception $e) {
        \Log::warning('Failed to send representative invitation email', [
            'organisation_id' => $organisation->id,
            'representative_email' => $representativeEmail,
            'error' => $e->getMessage(),
        ]);
        // Continue even if email fails
    }
});
```

### Task 4: Clean Up and Verification

**Steps:**
1. Remove unused imports (`use App\Mail\OrganisationCreatedMail;` if not used elsewhere)
2. Verify controller compiles without errors
3. Check for any leftover `fastcgi_finish_request();` call (line 145) - should be removed as it's invalid syntax
4. Ensure `X-Inertia` header is properly formatted (line 144 has stray semicolon)

**Fix syntax issues:**
```php
// Line 144 has invalid syntax:
], 201)->header('X-Inertia', 'true'); // Important: Add this header for Inertia;
// Should be:
], 201)->header('X-Inertia', 'true'); // Important: Add this header for Inertia

// Line 145 has invalid function:
fastcgi_finish_request (); // Ensure response is sent before any further processing (like logging)
// Should be removed entirely (not valid in Laravel context)
```

### Task 5: Testing

**Test Scenarios:**
1. **Create organisation without representative** - should succeed, no emails sent
2. **Create organisation with representative (different email)** - should succeed, queued email
3. **Create organisation with self as representative** - should succeed, no extra email
4. **Verify response time** - under 1 second even with queued email
5. **Check database** - no `assigned_at` errors in logs

**Manual Testing:**
```bash
# Test organisation creation via API
curl -X POST http://localhost/organisations \
  -H "Content-Type: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -d '{"name":"Test Org","email":"test@example.com","address":"Test Address"}'
```

**Check logs for:**
- No "assigned_at" column errors
- No "Class OrganizationCreatedMail not found" errors
- Representative invitation queued successfully

---

## Expected Behavior After Implementation

### Successful Organisation Creation:
1. **Database**: Organisation record created with correct slug
2. **User Attachment**: Current user attached as admin (no `assigned_at`)
3. **Representative Handling**:
   - If different email: user created/invited, email queued
   - If self: no action needed (already admin)
4. **Response**: JSON returned within 500ms with redirect URL
5. **Email**: Representative invitation sent after response (non-blocking)

### Error Handling:
- **Missing mail class**: Not applicable (organisation email removed)
- **Email failures**: Logged but don't affect user experience
- **Database errors**: Transaction rolled back, user sees error message
- **Validation errors**: Handled by `StoreOrganisationRequest`

### Performance:
- **Response time**: < 1 second (emails don't block)
- **Memory**: No large objects retained
- **Database**: Clean transactions, proper rollback

---

## Rollback Plan

If issues arise:
1. **Revert controller changes** to previous working state
2. **No database changes** needed (only removing non-existent column reference)
3. **Email behavior**: Falls back to no emails (removed block)

**Quick fix**: Re-add `assigned_at` column to `user_organisation_roles` table if absolutely necessary:
```sql
ALTER TABLE user_organisation_roles ADD COLUMN assigned_at TIMESTAMP NULL;
```

---

## Files to Modify

1. **Primary**: `app/Http/Controllers/Api/OrganisationController.php`
   - Remove organisation email block
   - Remove `assigned_at` from attachments
   - Queue representative email
   - Fix syntax issues

2. **No changes needed**:
   - `app/Mail/OrganisationCreatedMail.php` (kept for future use)
   - `app/Mail/RepresentativeInvitationMail.php` (works as-is)
   - Database schema (no migrations needed)
   - Frontend components (unchanged API contract)

---

## Success Criteria

- [ ] Organisation creates successfully (no 500 errors)
- [ ] No "assigned_at column not found" errors
- [ ] Representative invitations work (queued)
- [ ] Response time under 1 second
- [ ] All existing tests pass
- [ ] No breaking changes to API contract

---

## Plan complete and saved to `docs/plans/2026-03-05-organisation-controller-email-fix.md`.

**Execution method:** Direct implementation following the plan above.
