## 📋 Frontend Analysis & Implementation Plan

Let me help you analyze the current frontend state and plan the implementation based on the state machine architecture.

---

## Step 1: Audit Current Frontend Components

First, let's identify what frontend code already exists:

```bash
# List all Vue components related to elections
find resources/js -name "*.vue" | grep -i "election\|vote\|management" | sort

# List pages directory structure
ls -la resources/js/Pages/

# Check Election management components
ls -la resources/js/Pages/Election/

# Check for state machine related frontend code
grep -r "current_state\|stateMachine\|transitionTo" resources/js --include="*.vue" --include="*.js" | head -20
```

---

## Step 2: Current Frontend Components (Likely Existing)

Based on your codebase, you likely have:

| Component | Path | Purpose | Status |
|-----------|------|---------|--------|
| `Management.vue` | `Pages/Election/Management.vue` | Election management dashboard | ⚠️ Needs update |
| `StateMachinePanel.vue` | `Pages/Election/Partials/StateMachinePanel.vue` | Timeline visualization | ⚠️ Needs update |
| `Timeline.vue` | `Pages/Election/Timeline.vue` | Timeline configuration | ✅ Working |
| `TimelineView.vue` | `Pages/Election/TimelineView.vue` | Read-only timeline | ✅ Working |
| `Create.vue` | `Pages/Organisations/Elections/Create.vue` | Election creation | ✅ Working |
| `Index.vue` | `Pages/Organisations/Elections/Index.vue` | Election list | ✅ Working |

---

## Step 3: What Needs to Be Developed

### Priority 1: Update Existing Components for State Awareness

| Component | Missing Feature | Impact |
|-----------|----------------|--------|
| `Management.vue` | Button visibility based on `current_state` | High |
| `StateMachinePanel.vue` | Real-time state updates via events | High |
| `StateMachinePanel.vue` | Permission-based action buttons | High |

### Priority 2: New Components

| Component | Purpose | Priority |
|-----------|---------|----------|
| `Admin/Elections/Pending.vue` | Admin approval queue | High |
| `ApprovalModal.vue` | Submit for approval confirmation | Medium |
| `RejectionModal.vue` | Rejection reason input | Medium |
| `TransitionHistory.vue` | Audit trail viewer | Low |
| `StateBadge.vue` | Reusable state badge component | Medium |

---

## Step 4: Detailed Implementation Plan

### Phase 1: Update Management.vue (2-3 hours)

**Current issues to fix:**

```vue
<!-- Current (likely uses old status field) -->
<button v-if="election.status === 'planned'">Activate</button>

<!-- Should be state-based -->
<button v-if="canSubmitForApproval">Submit for Approval</button>
<button v-if="canCompleteAdministration">Complete Administration</button>
<button v-if="canCompleteNomination">Complete Nomination</button>
<button v-if="canOpenVoting">Open Voting</button>
<button v-if="canCloseVoting">Close Voting</button>
<button v-if="canPublishResults">Publish Results</button>
```

**Computed properties to add:**

```javascript
// In Management.vue script
const currentState = computed(() => props.election.current_state)

const canSubmitForApproval = computed(() => 
    currentState.value === 'draft' && !props.election.submitted_for_approval_at
)

const isPendingApproval = computed(() => 
    currentState.value === 'pending_approval'
)

const canCompleteAdministration = computed(() => 
    currentState.value === 'administration'
)

const canCompleteNomination = computed(() => 
    currentState.value === 'nomination'
)

const canOpenVoting = computed(() => 
    currentState.value === 'nomination'
)

const canCloseVoting = computed(() => 
    currentState.value === 'voting'
)

const canPublishResults = computed(() => 
    currentState.value === 'results_pending'
)

const canEdit = computed(() => 
    ['draft', 'pending_approval'].includes(currentState.value)
)
```

---

### Phase 2: Create Admin Approval Components (2-3 hours)

#### `Admin/Elections/Pending.vue`

```vue
<template>
  <div class="admin-pending-elections">
    <h1>Pending Elections ({{ elections.length }})</h1>
    
    <div v-for="election in elections" :key="election.id" class="election-card">
      <div class="election-info">
        <h3>{{ election.name }}</h3>
        <p>Submitted by: {{ election.submitted_by_user?.name }}</p>
        <p>Submitted at: {{ formatDate(election.submitted_for_approval_at) }}</p>
      </div>
      
      <div class="election-actions">
        <button @click="openApproveModal(election)" class="btn-success">
          Approve
        </button>
        <button @click="openRejectModal(election)" class="btn-danger">
          Reject
        </button>
        <Link :href="route('admin.elections.preview', election.slug)" class="btn-secondary">
          Preview
        </Link>
      </div>
    </div>
    
    <!-- Approve Modal -->
    <Modal v-model="showApproveModal" title="Approve Election">
      <textarea v-model="approveNotes" placeholder="Approval notes (optional)"></textarea>
      <button @click="confirmApprove">Confirm Approval</button>
    </Modal>
    
    <!-- Reject Modal -->
    <Modal v-model="showRejectModal" title="Reject Election">
      <textarea v-model="rejectReason" placeholder="Reason for rejection" required></textarea>
      <button @click="confirmReject">Confirm Rejection</button>
    </Modal>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const elections = ref(props.elections)

const approveElection = (election) => {
  router.post(route('admin.elections.approve', election.slug), {
    notes: approveNotes.value
  })
}

const rejectElection = (election) => {
  router.post(route('admin.elections.reject', election.slug), {
    reason: rejectReason.value
  })
}
</script>
```

---

### Phase 3: Create Reusable Components (1-2 hours)

#### `Components/StateBadge.vue`

```vue
<template>
  <span :class="badgeClasses" class="state-badge">
    <span class="state-icon">{{ icon }}</span>
    <span class="state-name">{{ label }}</span>
  </span>
</template>

<script setup>
const props = defineProps({
  state: {
    type: String,
    required: true,
    validator: (v) => [
      'draft', 'pending_approval', 'administration', 
      'nomination', 'voting', 'results_pending', 'results'
    ].includes(v)
  }
})

const stateConfig = {
  draft: { icon: '📝', label: 'Draft', color: 'gray' },
  pending_approval: { icon: '⏳', label: 'Pending Approval', color: 'yellow' },
  administration: { icon: '⚙️', label: 'Administration', color: 'blue' },
  nomination: { icon: '📋', label: 'Nomination', color: 'purple' },
  voting: { icon: '🗳️', label: 'Voting', color: 'green' },
  results_pending: { icon: '📊', label: 'Counting', color: 'orange' },
  results: { icon: '✅', label: 'Results', color: 'emerald' },
}

const icon = computed(() => stateConfig[props.state]?.icon)
const label = computed(() => stateConfig[props.state]?.label)
const badgeClasses = computed(() => `state-${stateConfig[props.state]?.color}`)
</script>
```

---

### Phase 4: Add Real-time Updates (1-2 hours)

#### Using Laravel Echo for WebSocket events

```javascript
// In resources/js/bootstrap.js
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

window.Pusher = Pusher

window.Echo = new Echo({
  broadcaster: 'pusher',
  key: import.meta.env.VITE_PUSHER_APP_KEY,
  cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
  forceTLS: true
})

// In Management.vue
onMounted(() => {
  Echo.private(`election.${election.value.id}`)
    .listen('ElectionStateChangedEvent', (e) => {
      // Refresh election data
      refreshElection()
      toast.success(`Election moved to ${e.toState} phase`)
    })
    .listen('VotingOpened', () => {
      toast.info('Voting period has opened!')
      refreshElection()
    })
    .listen('VotingClosed', () => {
      toast.info('Voting period has closed')
      refreshElection()
    })
})
```

---

### Phase 5: Step-by-Step Frontend Implementation

#### Step 1: Audit Current Frontend (30 min)

```bash
# Run this and share output
echo "=== Vue Components ==="
find resources/js -name "*.vue" -type f | wc -l

echo "=== Election Related Components ==="
find resources/js -name "*election*" -o -name "*Election*" | sort

echo "=== Current State References ==="
grep -r "current_state\|status" resources/js --include="*.vue" | head -30
```

#### Step 2: Create StateBadge Component (30 min)

```bash
# Create the component
touch resources/js/Components/StateBadge.vue
```

#### Step 3: Update Election Factory to Include State (15 min)

```php
// database/factories/ElectionFactory.php
'state' => $this->faker->randomElement([
    'draft', 'pending_approval', 'administration', 
    'nomination', 'voting', 'results_pending', 'results'
]),
```

#### Step 4: Update Management.vue Computed Properties (1 hour)

#### Step 5: Create Admin Approval Components (2 hours)

#### Step 6: Add Real-time Events (Optional, 1 hour)

---

## Step 5: Immediate Next Actions

To proceed, I need you to run these commands and share the output:

```bash
# 1. List all Vue components
find resources/js -name "*.vue" -type f | sort

# 2. Show current Management.vue (first 100 lines)
head -100 resources/js/Pages/Election/Management.vue

# 3. Check if StateMachinePanel exists
cat resources/js/Pages/Election/Partials/StateMachinePanel.vue | head -50

# 4. Check for approval-related components
find resources/js -name "*approval*" -o -name "*Approval*"
```

---

## Summary: Frontend Implementation Roadmap

| Phase | Task | Time | Priority |
|-------|------|------|----------|
| 0 | Audit current frontend | 30 min | 📋 Prerequisite |
| 1 | Create StateBadge component | 30 min | High |
| 2 | Update Management.vue buttons | 1 hour | High |
| 3 | Update StateMachinePanel.vue | 1 hour | High |
| 4 | Create Admin approval components | 2 hours | High |
| 5 | Add submission modals | 1 hour | Medium |
| 6 | Add real-time events | 1 hour | Low |
| 7 | Add audit trail viewer | 1 hour | Low |

**Total estimated time:** 6-8 hours for complete frontend implementation

---

## Which Component Should We Start With?

1. **Audit current frontend** - Run the commands above and share output
2. **Create StateBadge component** - Reusable across all pages
3. **Update Management.vue** - Most critical user-facing page
4. **Admin approval components** - Required for approval workflow

**Tell me which one to prioritize, or share the audit output first.** 🚀