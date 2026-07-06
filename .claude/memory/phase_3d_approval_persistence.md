---
name: Phase 3D approval persistence investigation
description: Root cause analysis and findings for 3 failing approval tests
type: project
originSessionId: 0b8d9422-360e-4388-83e6-9972ffe33c53
---
## Current Status: 11/14 Tests Passing (79%)

### ✅ Passing (11 tests):
- Guest cannot submit application
- Existing member cannot apply again
- User with pending cannot apply again
- Valid application creates record with submitted status ← **NOW WORKING**
- Admin can view all applications
- Member cannot view applications index
- Reject sets status to rejected with reason
- Reject fires membership application rejected event
- Approved application cannot be approved again
- Concurrent approval handled gracefully
- Expired application rejected by daily job

### ⚠️ Failing (3 tests):
1. **approve_creates_organisation_user_and_member** - Application status not persisting as 'approved'
2. **approve_creates_pending_membership_fee** - membership_fees table is empty (no fees created)
3. **approve_fires_membership_application_approved_event** - Event not being dispatched

---

## Key Discovery: Submission Works, Approval Doesn't

**This is critical:**
- `SubmitMembershipApplication` use case **IS working** - applications are created with status='submitted'
- `ApproveApplication` use case **IS NOT working** - application status remains 'submitted' instead of changing to 'approved'

This tells us:
1. ✅ ApplicationContextModel and EloquentApplicationRepository work for INSERT
2. ❌ They don't work for UPDATE (approval flow)
3. ✅ The aggregate layer works (approve() method called, status changes in memory)
4. ❌ The persistence layer isn't capturing the UPDATE

---

## Root Cause Hypothesis (High Confidence)

The problem is **Transaction Rollback** or **Missing Record Lookup**:

### Scenario A: find() Not Finding the Record
- Controller loads application via legacy model (MembershipApplication)
- Use case calls find() via DDD repository
- If find() returns null, firstOrCreate() creates a **NEW** record with approved status
- But the **OLD** legacy record stays submitted
- Test queries and finds the old record
- Result: Status still 'submitted'

### Scenario B: Transaction Rollback
- Approval succeeds for application
- Member aggregate creation succeeds  
- Fee aggregate creation **FAILS** (silently?)
- Entire transaction rolls back
- Application status reverts to submitted
- **Evidence**: "membership_fees table is empty" - no Fee was persisted

### Scenario C: Attribute Not Being Saved
- Model.save() returns true
- But 'status' field specifically isn't being updated
- Could indicate:
  - Mutator override
  - Column mismatch
  - Casting issue

---

## What We Know About Persistence

**Working (SubmitMembershipApplication):**
```php
$model = $this->model
    ->withoutGlobalScopes()
    ->where('id', $appId)
    ->where('organisation_id', $orgId)
    ->firstOrCreate(['id' => $appId, 'organisation_id' => $orgId]);

$model->status = 'submitted';
$model->save(); // ✅ Works
```

**Not Working (ApproveApplication):**
```php
$model = $this->model
    ->withoutGlobalScopes()
    ->where('id', $appId)
    ->where('organisation_id', $orgId)
    ->firstOrCreate(['id' => $appId, 'organisation_id' => $orgId]);

$model->status = 'approved'; // Should change from 'submitted'
$model->save(); // ❌ Not persisting
```

The ONLY difference is the initial status. This suggests:
- Either firstOrCreate() finds existing record but doesn't update it
- Or save() is being called but status column specifically isn't updating

---

## Next Debug Steps

1. **Verify find() is actually finding the record:**
   - Add logging in EloquentApplicationRepository.find()
   - Log: "Finding app {id}/{org_id}: found={result} status={result->status}"

2. **Check firstOrCreate() behavior:**
   - Log: "firstOrCreate returned wasRecentlyCreated={model->wasRecentlyCreated}"
   - If false = found existing record, true = created new

3. **Verify save() actually updates:**
   - Before save: Log model->getDirty() (attributes with changes)
   - After save: Fresh query from DB and log actual status

4. **Check for transaction issues:**
   - Verify Member and Fee saves aren't throwing exceptions
   - Check if transaction is rolling back silently

---

## Critical Files to Review

- `app/Contexts/Membership/Infrastructure/Repositories/EloquentApplicationRepository.php` - save() method
- `app/Contexts/Membership/Application/Application/UseCases/ApproveMembershipApplication.php` - execute() method
- `app/Http/Controllers/Membership/MembershipApplicationController.php` - approve() method (transaction wrapper)

---

## Why Rejection Works But Approval Doesn't

**Rejection flow:**
- Application loaded
- Application.reject() called
- save() persists status = 'rejected'
- ✅ Works

**Approval flow:**
- Application loaded  
- Application.approve() called
- save() supposed to persist status = 'approved'
- ❌ Fails

The ONLY difference is that approval also creates Member and Fee aggregates. If Member or Fee creation is failing and rolling back the transaction, that would explain why approval fails but rejection works.

---

## Investigation Priority

1. **HIGH**: Check if Member.save() is throwing an error
2. **HIGH**: Check if Fee.save() is throwing an error  
3. **MEDIUM**: Add logging to confirm find() finds the record
4. **MEDIUM**: Verify firstOrCreate() isn't creating duplicates

The fee table being empty strongly suggests the transaction is rolling back after Member/Fee creation fails.

