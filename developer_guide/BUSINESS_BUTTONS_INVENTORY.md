# Business Buttons Inventory

**Date:** 2026-04-27  
**Purpose:** Complete map of all user-clickable business actions in the system  
**Status:** Production Ready

---

## 📋 Executive Summary

| Category | Count | Pages | Controller |
|----------|-------|-------|-----------|
| State Machine Actions | 6 | Management.vue | ElectionManagementController |
| Admin Actions | 2 | Admin/Pending.vue | AdminElectionController |
| Candidacy Actions | 2 | Candidacy/Applications.vue | CandidacyReviewController |
| Voter Management | 4+ | Voters/Index.vue | ElectionVoterController |
| Membership Actions | 4 | Membership/Applications | MembershipApplicationController |
| **TOTAL** | **18+** | - | - |

---

## 🎯 State Machine Actions (Election Workflow)

These buttons advance the election through the state machine.

### Location: `/elections/{slug}/management`

**File:** `resources/js/Pages/Election/Management.vue`

#### 1. Submit for Approval

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Submit for Approval" |
| **Action** | `submit_for_approval` |
| **Visible When** | State = `draft` |
| **Route** | `elections.submit-for-approval` |
| **HTTP Method** | POST |
| **Controller** | `ElectionManagementController@submitForApproval` |
| **Code** | `router.post(route('elections.submit-for-approval', { election: props.election.slug }), {}, {...})` |

```javascript
// Management.vue line ~450
const submitForApproval = () => {
  isLoading.value = true
  router.post(route('elections.submit-for-approval', { election: props.election.slug }), {}, {
    preserveScroll: true,
    onFinish: () => {
      isLoading.value = false
    },
    onSuccess: () => {
      showSubmitApprovalModal.value = false
      setTimeout(() => {
        window.location.reload()
      }, 500)
    },
  })
}
```

#### 2. Complete Administration

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Complete Administration" |
| **Action** | `complete_administration` |
| **Visible When** | State = `administration` |
| **Route** | `organisations.elections.complete-administration` |
| **HTTP Method** | POST |
| **Controller** | `ElectionManagementController@completeAdministration` |
| **Component** | `StateMachinePanel.vue` |
| **Requires** | Modal with "Reason" input |

```javascript
// StateMachinePanel.vue
const completePhase = (state) => {
  const reasonValue = reason.value.trim()
  if (!reasonValue) {
    reasonError.value = 'Please provide a reason'
    return
  }
  
  router.post(route('organisations.elections.complete-' + state), { reason: reasonValue }, {
    preserveScroll: true,
    onSuccess: () => {
      setTimeout(() => {
        window.location.reload()
      }, 500)
    },
  })
}
```

#### 3. Complete Nomination

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Complete Nomination" |
| **Action** | `complete_nomination` |
| **Visible When** | State = `nomination` |
| **Route** | `organisations.elections.complete-nomination` |
| **HTTP Method** | POST |
| **Controller** | `ElectionManagementController@completeNomination` |
| **Component** | `StateMachinePanel.vue` |
| **Requires** | Modal with "Reason" input |

---

#### 4. Open Voting

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Open Voting" |
| **Action** | `open_voting` |
| **Visible When** | State = `nomination` |
| **Route** | `elections.open-voting` |
| **HTTP Method** | POST |
| **Controller** | Not found in current routes (likely delegated) |
| **Code** | `router.post(route('elections.open-voting', { election: props.election.slug }), {}, {...})` |

```javascript
// Management.vue line ~500
const openVoting = () => {
  if (!confirm(t.value.confirm.open_voting)) return
  isLoading.value = true
  router.post(route('elections.open-voting', { election: props.election.slug }), {}, {
    preserveScroll: true,
    onFinish: () => { isLoading.value = false },
  })
}
```

#### 5. Close Voting

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Close Voting" |
| **Action** | `close_voting` |
| **Visible When** | State = `voting` |
| **Route** | `elections.close-voting` |
| **HTTP Method** | POST |
| **Code** | `router.post(route('elections.close-voting', { election: props.election.slug }), {}, {...})` |

```javascript
// Management.vue line ~515
const closeVoting = () => {
  if (!confirm(t.value.confirm.close_voting)) return
  isLoading.value = true
  router.post(route('elections.close-voting', { election: props.election.slug }), {}, {
    preserveScroll: true,
    onFinish: () => { isLoading.value = false },
  })
}
```

#### 6. Publish Results

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Publish Results" |
| **Action** | `publish_results` |
| **Visible When** | State = `results_pending` |
| **Route** | `elections.publish` |
| **HTTP Method** | POST |
| **Code** | `router.post(route('elections.publish', { election: props.election.slug }), {}, {...})` |

```javascript
// Management.vue line ~530
const publishResults = () => {
  if (!confirm(t.value.confirm.publish)) return
  isLoading.value = true
  router.post(route('elections.publish', { election: props.election.slug }), {}, {
    preserveScroll: true,
    onFinish: () => { isLoading.value = false },
  })
}
```

---

## 🔐 Admin Actions

### Location: `/admin/elections/pending`

**File:** `resources/js/Pages/Admin/Elections/Pending.vue`

#### 1. Approve Election

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Approve" |
| **Action** | `approve` |
| **Visible When** | State = `pending_approval` |
| **Route** | `admin.elections.approve` |
| **HTTP Method** | POST |
| **Controller** | `AdminElectionController@approve` |
| **Modal** | ApprovalModal.vue |

```javascript
// Admin/Pending.vue
const approve = () => {
  router.post(
    route('admin.elections.approve', selectedElection.value.slug),
    { approval_notes: approvalNotes.value },
    {
      onSuccess: () => {
        showApprovalModal.value = false
        loadElections()
      },
    }
  )
}
```

#### 2. Reject Election

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Reject" |
| **Action** | `reject` |
| **Visible When** | State = `pending_approval` |
| **Route** | `admin.elections.reject` |
| **HTTP Method** | POST |
| **Controller** | `AdminElectionController@reject` |
| **Modal** | RejectionModal.vue |

```javascript
// Admin/Pending.vue
const reject = () => {
  router.post(
    route('admin.elections.reject', selectedElection.value.slug),
    { rejection_reason: rejectionReason.value },
    {
      onSuccess: () => {
        showRejectionModal.value = false
        loadElections()
      },
    }
  )
}
```

---

## 👥 Voter Management Actions

### Location: `/elections/{slug}/voters`

**File:** `resources/js/Pages/Elections/Voters/Index.vue`

#### 1. Approve Voter

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Approve" (inline button) |
| **Action** | `approve` |
| **Route** | `elections.voters.approve` |
| **HTTP Method** | POST |
| **Controller** | `ElectionVoterController@approve` |
| **Context** | Per-voter inline action |

```javascript
const approveVoter = (membership) => {
  router.post(
    route('elections.voters.approve', {
      organisation: props.organisation.slug,
      election: props.election.slug,
      membership: membership.id
    }),
    {},
    { preserveScroll: true, onFinish: () => { loadingId.value = null } }
  )
}
```

#### 2. Suspend Voter

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Suspend" (inline button) |
| **Action** | `suspend` |
| **Route** | `elections.voters.suspend` |
| **HTTP Method** | POST |
| **Controller** | `ElectionVoterController@suspend` |

#### 3. Propose Suspension

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Propose Suspension" |
| **Route** | `elections.voters.propose-suspension` |
| **HTTP Method** | POST |
| **Controller** | `ElectionVoterController@proposeSuspension` |

#### 4. Confirm Suspension

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Confirm Suspension" |
| **Route** | `elections.voters.confirm-suspension` |
| **HTTP Method** | POST |
| **Controller** | `ElectionVoterController@confirmSuspension` |

---

## 🎤 Candidacy Management Actions

### Location: `/elections/{slug}/candidacies`

**File:** `resources/js/Pages/Election/Candidacy/Applications.vue`

#### 1. Approve Candidacy Application

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Approve" |
| **Action** | `approve` |
| **Route** | (dynamic via `reviewUrl()`) |
| **HTTP Method** | PATCH |
| **Controller** | `CandidacyReviewController@review` |
| **Data** | `{ action: 'approve' }` |

```javascript
// Candidacy/Applications.vue
const approveCandidacy = (app) => {
  router.patch(reviewUrl(app.id), { action: 'approve' }, {
    onSuccess: () => {
      refreshData()
    },
  })
}
```

#### 2. Reject Candidacy Application

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Reject" |
| **Action** | `reject` |
| **Route** | (dynamic via `reviewUrl()`) |
| **HTTP Method** | PATCH |
| **Controller** | `CandidacyReviewController@review` |
| **Data** | `{ action: 'reject', rejection_reason: reason }` |

```javascript
const rejectCandidacy = (app) => {
  router.patch(reviewUrl(app.id), {
    action: 'reject',
    rejection_reason: rejectionReason.value
  }, {
    onSuccess: () => {
      refreshData()
    },
  })
}
```

---

## 📋 Membership Application Actions

### Location: `/organisations/{slug}/membership/applications`

**File:** `resources/js/Pages/Organisations/Membership/Applications/Show.vue`

#### 1. Approve Membership Application

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Approve" |
| **Route** | `organisations.membership.applications.approve` |
| **HTTP Method** | PATCH |
| **Controller** | `MembershipApplicationController@approve` |

```javascript
router.patch(
  route('organisations.membership.applications.approve', [
    props.organisation.slug,
    props.application.id
  ]),
  { approval_notes: approvalNotes.value },
  { onSuccess: () => { /* ... */ } }
)
```

#### 2. Reject Membership Application

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Reject" |
| **Route** | `organisations.membership.applications.reject` |
| **HTTP Method** | PATCH |
| **Controller** | `MembershipApplicationController@reject` |

```javascript
router.patch(
  route('organisations.membership.applications.reject', [
    props.organisation.slug,
    props.application.id
  ]),
  { rejection_reason: rejectionReason.value },
  { onSuccess: () => { /* ... */ } }
)
```

---

## 🏢 Election Setup Actions

### Location: `/organisations/{slug}/elections/{election}/posts`

**File:** `resources/js/Pages/Election/Posts/Index.vue`

#### 1. Create Post

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Add Post" |
| **Component** | PostForm.vue (modal) |
| **Route** | `organisations.elections.posts.store` |
| **HTTP Method** | POST |
| **Controller** | `PostManagementController@store` |

#### 2. Update Post

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Edit" (inline) |
| **Component** | PostForm.vue (modal) |
| **Route** | `organisations.elections.posts.update` |
| **HTTP Method** | PATCH |
| **Controller** | `PostManagementController@update` |

#### 3. Delete Post

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Delete" (inline) |
| **Route** | `organisations.elections.posts.destroy` |
| **HTTP Method** | DELETE |
| **Controller** | `PostManagementController@destroy` |

---

## 🗳️ Candidate Management Actions

### Location: `/elections/{slug}/posts/{post}/candidates`

**File:** `resources/js/Pages/Election/Candidacy/Index.vue`

#### 1. Approve Candidate

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Approve" (status update) |
| **Route** | (dynamic `updateUrl(post, candidate)`) |
| **HTTP Method** | PATCH |
| **Controller** | `CandidacyManagementController@update` |
| **Data** | `{ status: 'approved' }` |

#### 2. Reject Candidate

| Attribute | Value |
|-----------|-------|
| **Button Label** | "Reject" (status update) |
| **Route** | (dynamic `updateUrl(post, candidate)`) |
| **HTTP Method** | PATCH |
| **Data** | `{ status: 'rejected' }` |

---

## 📊 Button Visibility Control

All buttons use `allowedActions` from the backend:

```javascript
// Management.vue
const allowedActions = computed(() => props.stateMachine?.allowedActions ?? [])

// Then for each button:
v-if="allowedActions.includes('action_name')"
```

### Backend Source

**File:** `app/Http/Controllers/Election/ElectionManagementController.php`

```php
// In getStateMachineData()
'allowedActions' => $election->getAllowedActionsForUser(auth()->id()),
```

**File:** `app/Models/Election.php`

```php
public function getAllowedActionsForUser(string|int|null $userId = null): array
{
    $userId = $userId ?? auth()->id();
    if (!$userId) return [];
    
    $actorRole = $this->resolveActorRole((string) $userId);
    $stateActions = TransitionMatrix::getAllowedActions($this->state ?? '');
    
    return array_values(array_filter($stateActions, fn($action) =>
        TransitionMatrix::actionRequiresRole($action, $actorRole)
    ));
}
```

---

## 🔗 Routes Reference

### Election Management Routes

```php
// app/Providers/RouteServiceProvider or routes/organisations.php

Route::post('/complete-administration', [ElectionManagementController::class, 'completeAdministration'])
    ->name('organisations.elections.complete-administration');

Route::post('/complete-nomination', [ElectionManagementController::class, 'completeNomination'])
    ->name('organisations.elections.complete-nomination');

Route::post('/elections/{election:slug}/approve', [AdminElectionController::class, 'approve'])
    ->name('admin.elections.approve');

Route::post('/elections/{election:slug}/reject', [AdminElectionController::class, 'reject'])
    ->name('admin.elections.reject');

Route::post('/voters/{membership}/approve', [ElectionVoterController::class, 'approve'])
    ->name('elections.voters.approve');

Route::post('/voters/{membership}/suspend', [ElectionVoterController::class, 'suspend'])
    ->name('elections.voters.suspend');
```

---

## 🏛️ Architecture

### Execution Flow

```
Button Click
    ↓
Frontend (Management.vue, etc.)
    ├─ Validate input
    ├─ Show confirmation (if needed)
    └─ router.post/patch/delete
            ↓
Controller (ElectionManagementController, etc.)
    ├─ Validate request
    ├─ Check authorization
    └─ Call domain method or execute action
            ↓
Domain Model (Election.php)
    ├─ Apply business rules
    ├─ Validate state/guards
    └─ Transition state or update data
            ↓
Event Dispatch
    ├─ Fire domain events
    └─ Listeners handle side effects
            ↓
Response
    ├─ Redirect with success message
    └─ Or error message + form re-render
```

---

## 📱 Button Styling

All buttons use consistent components:

| Component | Purpose | File |
|-----------|---------|------|
| `<ActionButton>` | Primary actions | `components/ActionButton.vue` |
| `<DangerButton>` | Destructive actions | `components/Jetstream/DangerButton.vue` |
| `<SecondaryButton>` | Cancel/secondary | `components/Jetstream/SecondaryButton.vue` |
| `<FormButton>` | Form submissions | `components/Jetstream/FormButton.vue` |

---

## ✅ Testing Each Button

```bash
# 1. Verify button is visible
# Check: allowedActions includes the action

# 2. Click button
# Check: Confirmation modal appears (if configured)

# 3. Confirm action
# Check: Router.post/patch/delete executes

# 4. Wait for response
# Check: Redirect or success message appears

# 5. Verify state change
# Check: Election state in database changed
```

---

## 📝 Adding a New Business Button

### Step 1: Define Action in TransitionMatrix

```php
// app/Domain/Election/StateMachine/TransitionMatrix.php
public const ALLOWED_ACTIONS = [
    'state_name' => ['new_action'],
];
```

### Step 2: Create Route

```php
// routes/organisations.php
Route::post('/new-action', [ElectionManagementController::class, 'newAction'])
    ->name('organisations.elections.new-action');
```

### Step 3: Create Controller Method

```php
// app/Http/Controllers/Election/ElectionManagementController.php
public function newAction(Election $election): RedirectResponse
{
    $this->authorize('manage', $election);
    
    try {
        $election->transitionTo(Transition::manual('new_action', auth()->id(), 'User triggered'));
        return back()->with('success', 'Action completed.');
    } catch (\DomainException $e) {
        return back()->with('error', $e->getMessage());
    }
}
```

### Step 4: Add Frontend Button

```vue
<!-- Management.vue -->
<ActionButton
  v-if="allowedActions.includes('new_action')"
  @click="performNewAction"
>
  New Action
</ActionButton>

<script setup>
const performNewAction = () => {
  router.post(route('organisations.elections.new-action', { election: props.election.slug }), {}, {
    onSuccess: () => window.location.reload(),
  })
}
</script>
```

---

## 🎯 Summary

| Metric | Value |
|--------|-------|
| Total Business Buttons | 18+ |
| Pages with Buttons | 8+ |
| Controllers Used | 7+ |
| State Transitions | 6 |
| Admin Actions | 2 |
| Voter Actions | 4+ |
| Candidacy Actions | 2 |
| Membership Actions | 2 |

---

**Last Updated:** 2026-04-27  
**Status:** Production Ready ✅  
**All buttons tested:** Yes ✅

