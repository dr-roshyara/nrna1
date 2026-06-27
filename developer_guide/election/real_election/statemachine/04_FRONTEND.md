# Frontend Integration Guide

## Updated Management.vue

Location: resources/js/Pages/Election/Management.vue

---

## Changes Made

### Before
```vue
const isVotingActive = computed(() => props.election.status === 'active')

<button v-if="!isVotingActive" @click="openVoting">Open Voting</button>
<button v-if="isVotingActive" @click="closeVoting">Close Voting</button>
```

### After
```vue
const currentState = computed(() => props.election.current_state)
const canOpenVoting = computed(() => currentState.value === 'nomination')
const canCloseVoting = computed(() => currentState.value === 'voting')
const isVotingActive = computed(() => canCloseVoting.value)

<button v-if="canOpenVoting" @click="openVoting">Open Voting</button>
<button v-if="canCloseVoting" @click="closeVoting">Close Voting</button>
```

---

## Computed Properties

### currentState
Reads election.current_state directly from backend
```vue
const currentState = computed(() => props.election.current_state)
// Returns: 'administration', 'nomination', 'voting', 'results_pending', 'results'
```

### canOpenVoting
True only in nomination phase
```vue
const canOpenVoting = computed(() => currentState.value === 'nomination')
// Shows "Open Voting" button only in nomination
```

### canCloseVoting
True only in voting phase
```vue
const canCloseVoting = computed(() => currentState.value === 'voting')
// Shows "Close Voting" button only in voting
```

### isVotingActive
Backward compatible, equals canCloseVoting for styling
```vue
const isVotingActive = computed(() => canCloseVoting.value)
// Used for styling voting control section
```

---

## Button Visibility Logic

### "Open Voting" Button
```vue
<ActionButton
  v-if="canOpenVoting"
  variant="success"
  @click="openVoting"
>
  Open Voting
</ActionButton>
```

Shows when: election.current_state === 'nomination'
Hides when: in any other state

### "Close Voting" Button
```vue
<ActionButton
  v-if="canCloseVoting"
  variant="danger"
  @click="closeVoting"
>
  Close Voting
</ActionButton>
```

Shows when: election.current_state === 'voting'
Hides when: in any other state

---

## State Styling

The voting control section header updates based on state:

```vue
<div :class="isVotingActive ? 'bg-emerald-100' : 'bg-slate-100'">
  <p :class="isVotingActive ? 'text-emerald-600 font-medium' : 'text-slate-400'">
    {{ isVotingActive ? t.sections.voting_control.currently_active : t.sections.voting_control.currently_inactive }}
  </p>
</div>
```

- Emerald (active) when: voting is happening
- Gray (inactive) when: nomination or other phases

---

## Button Click Handlers

### openVoting()
```vue
const openVoting = () => {
  if (!confirm(t.value.confirm.open_voting)) return
  isLoading.value = true
  router.post(route('elections.open-voting', { election: props.election.slug }), {}, {
    preserveScroll: true,
    onFinish: () => { isLoading.value = false },
  })
}
```

Posts to: POST /elections/{slug}/open-voting
Uses: Inertia router.post() (not raw fetch)

### closeVoting()
```vue
const closeVoting = () => {
  if (!confirm(t.value.confirm.close_voting)) return
  isLoading.value = true
  router.post(route('elections.close-voting', { election: props.election.slug }), {}, {
    preserveScroll: true,
    onFinish: () => { isLoading.value = false },
  })
}
```

Posts to: POST /elections/{slug}/close-voting
Uses: Inertia router.post() (not raw fetch)

---

## Why Use election.current_state?

### Before (Legacy)
```
election.status = 'active' / 'completed' / 'planned'
```
Single field, doesn't capture all state information
Button logic based on status field

### After (State Machine)
```
election.current_state = 'nomination' / 'voting' / 'results_pending' / etc
```
Derived from multiple database flags
Buttons respect state machine logic
Always in sync with backend validation

---

## Props Flow

```
ElectionManagementController
├─ Loads election from database
├─ Passes election prop to Management.vue
│  └─ election.current_state is computed on backend
└─ Frontend reads current_state accessor

Management.vue
├─ Receives election prop
├─ Reads election.current_state
├─ Computes button visibility
└─ User clicks button
    ├─ Confirm dialog
    └─ router.post() to controller
        ├─ Validates state
        ├─ Calls transitionTo()
        └─ Redirects with flash message
```

---

## Integration Points

### 1. Server → Client
Controller passes election with current_state:
```php
return inertia('Election/Management', [
    'election' => $election,  // current_state is accessor
]);
```

### 2. Client → Server
Vue sends form data via router.post():
```vue
router.post(route('elections.open-voting', { election: props.election.slug }), {})
```

### 3. Server → Client (Response)
Controller redirects with flash message:
```php
return back()->with('success', 'Voting opened successfully.');
```

### 4. Client → Display
Flash messages shown in component:
```vue
<div v-if="page.props.flash?.success" class="bg-emerald-50">
  {{ page.props.flash.success }}
</div>
```

---

## Testing Frontend

### Manual Testing
1. Open election management page
2. Verify "Open Voting" button shows in nomination phase
3. Click "Open Voting" → Confirm dialog
4. Button should change to "Close Voting"
5. Click "Close Voting" → Confirm dialog
6. Button should disappear

### Automated Testing
Frontend logic is tested via Inertia routes:
```php
$this->actingAs($officer)->post(
    route('elections.open-voting', ['election' => $election->slug])
);
```

---

**Last Updated:** April 24, 2026
