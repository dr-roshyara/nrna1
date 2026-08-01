## 🧠 **Brainstorming: Two-Person Suspension Workflow**

This is an **excellent security requirement** - requiring two committee members to suspend a voter prevents abuse and ensures checks and balances.

---

## 📋 **Current Problem**

| Current State | Problem |
|---------------|---------|
| Single committee member can suspend | ❌ No oversight, potential abuse |
| No pending/suspension proposal state | ❌ Can't track who initiated |
| No audit trail of suspension requests | ❌ Can't see history |
| Immediate suspension | ❌ No grace period for review |

---

## 🎯 **Proposed Solution: Two-Person Suspension Workflow**

```
Committee Member A
    ↓
Proposes Suspension
    ↓
Status changes to "suspension_pending"
    ↓
Committee Member B reviews
    ↓
Confirms Suspension
    ↓
Status changes to "inactive"
    ↓
Suspension is applied
```

---

## 🏗️ **Database Changes**

### **Add to `election_memberships` table**

```php
Schema::table('election_memberships', function (Blueprint $table) {
    // Suspension proposal tracking
    $table->string('suspension_proposed_by')->nullable()->after('suspended_by');
    $table->timestamp('suspension_proposed_at')->nullable()->after('suspension_proposed_by');
    $table->string('suspension_status')->default('none')->after('status');
    // values: 'none', 'proposed', 'confirmed'
});
```

---

## 📊 **State Machine**

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         VOTER STATUS FLOW                                   │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌─────────┐     ┌─────────┐     ┌─────────┐     ┌─────────┐              │
│  │ INVITED │ ──→ │ ACTIVE  │ ──→ │ PENDING │ ──→ │INACTIVE │              │
│  └─────────┘     └─────────┘     └─────────┘     └─────────┘              │
│       │              │              │               │                      │
│       │              │              │               │                      │
│   Assign        Approve       Propose         Confirm                    │
│   Voter         Voter         Suspension     Suspension                 │
│   (1 person)    (1 person)    (1 person)     (2nd person)              │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 🎨 **UI Design: Voter Row Actions**

### **Current Row (Single Action)**
```
┌─────────────────────────────────────────────────────────────────────────────┐
│ John Doe | john@email.com | Active | [Approve] [Suspend] [Remove]          │
└─────────────────────────────────────────────────────────────────────────────┘
```

### **Proposed Row (Two-Person Suspension)**

```
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│ John Doe | john@email.com | Active | [Approve] [🗳️ Propose Suspension] [Remove]           │
└─────────────────────────────────────────────────────────────────────────────────────────────┘

After Proposal:
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│ John Doe | john@email.com | PENDING SUSPENSION (proposed by Admin at 10:30)                │
│                                                                                             │
│ [✓ Confirm Suspension] [✗ Cancel Proposal]                                                  │
│ ⚠️ Requires second committee member to confirm                                             │
└─────────────────────────────────────────────────────────────────────────────────────────────┘

After Confirmation:
┌─────────────────────────────────────────────────────────────────────────────────────────────┐
│ John Doe | john@email.com | Suspended (confirmed by Jane at 10:35)                         │
│ Suspension proposed by Admin, confirmed by Jane                                            │
└─────────────────────────────────────────────────────────────────────────────────────────────┘
```

---

## 📝 **Updated Vue Component (Voter Row)**

```vue
<template>
  <tr class="reg-row" :class="{ 'reg-row--suspension-pending': membership.suspension_status === 'proposed' }">
    
    <!-- Voter Info -->
    <td class="reg-td">
      <div class="voter-cell">
        <div class="voter-avatar">{{ (membership.user?.name ?? '?').charAt(0).toUpperCase() }}</div>
        <div>
          <p class="voter-name">{{ membership.user?.name ?? '—' }}</p>
          <p class="voter-email">{{ membership.user?.email ?? '—' }}</p>
        </div>
      </div>
    </td>

    <!-- Status -->
    <td class="reg-td">
      <!-- Active State -->
      <span v-if="membership.status === 'active' && membership.suspension_status !== 'proposed'" 
            class="status-pill status-pill--active">
        <span class="status-dot"></span>
        Active
      </span>
      
      <!-- Suspension Pending -->
      <span v-else-if="membership.suspension_status === 'proposed'" 
            class="status-pill status-pill--warning">
        <span class="status-dot status-dot--warning"></span>
        Pending Suspension
      </span>
      
      <!-- Suspended -->
      <span v-else-if="membership.status === 'inactive'" 
            class="status-pill status-pill--inactive">
        <span class="status-dot"></span>
        Suspended
      </span>
      
      <!-- Invited -->
      <span v-else class="status-pill status-pill--invited">
        <span class="status-dot"></span>
        Invited
      </span>
    </td>

    <!-- Suspension Info (if pending or suspended) -->
    <td class="reg-td">
      <div v-if="membership.suspension_status === 'proposed'" class="suspension-info">
        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <span class="text-xs">
          Proposed by {{ membership.suspension_proposed_by }}<br>
          {{ formatDate(membership.suspension_proposed_at) }}
        </span>
      </div>
      <div v-else-if="membership.status === 'inactive' && membership.suspended_by" class="suspension-info">
        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
        <span class="text-xs">
          Suspended by {{ membership.suspended_by }}<br>
          {{ formatDate(membership.suspended_at) }}
        </span>
      </div>
      <span v-else class="text-xs text-slate-400">—</span>
    </td>

    <!-- Actions -->
    <td class="reg-td reg-td--actions">
      <div class="action-row">
        
        <!-- Approve (for invited voters) -->
        <button
          v-if="membership.status === 'invited'"
          @click="approveVoter(membership)"
          class="act-btn act-btn--approve"
        >
          Approve
        </button>

        <!-- PROPOSE SUSPENSION (for active voters, not already proposed) -->
        <button
          v-if="membership.status === 'active' && membership.suspension_status !== 'proposed'"
          @click="proposeSuspension(membership)"
          class="act-btn act-btn--propose"
        >
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
          </svg>
          Propose Suspension
        </button>

        <!-- CONFIRM SUSPENSION (for pending proposals, by another committee member) -->
        <button
          v-if="membership.suspension_status === 'proposed' && canConfirm(membership)"
          @click="confirmSuspension(membership)"
          class="act-btn act-btn--confirm"
        >
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          Confirm Suspension
        </button>

        <!-- CANCEL PROPOSAL (for the proposer or admin) -->
        <button
          v-if="membership.suspension_status === 'proposed' && (canCancel(membership) || isAdmin)"
          @click="cancelProposal(membership)"
          class="act-btn act-btn--cancel"
        >
          Cancel
        </button>

        <!-- Remove (for any non-voted voter) -->
        <button
          v-if="membership.status !== 'removed' && !membership.has_voted"
          @click="removeVoter(membership)"
          class="act-btn act-btn--remove"
        >
          Remove
        </button>

        <!-- Already voted lock -->
        <span v-if="membership.has_voted" class="act-voted-lock" title="Vote cast — no changes permitted">
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
          </svg>
        </span>
      </div>
    </td>
  </tr>
</template>

<script setup>
// ... imports

const canConfirm = (membership) => {
  // Another committee member (not the proposer)
  return membership.suspension_proposed_by !== authUser.name && isCommitteeMember.value
}

const canCancel = (membership) => {
  // The proposer or admin can cancel
  return membership.suspension_proposed_by === authUser.name || isAdmin.value
}

const proposeSuspension = (membership) => {
  if (confirm(`Propose to suspend ${membership.user?.name}? This requires confirmation from another committee member.`)) {
    router.post(route('elections.voters.propose-suspension', {
      organisation: props.organisation.slug,
      election: props.election.id,
      membership: membership.id
    }), {}, { preserveScroll: true })
  }
}

const confirmSuspension = (membership) => {
  if (confirm(`Confirm suspension of ${membership.user?.name}? This action requires your confirmation as a second committee member.`)) {
    router.post(route('elections.voters.confirm-suspension', {
      organisation: props.organisation.slug,
      election: props.election.id,
      membership: membership.id
    }), {}, { preserveScroll: true })
  }
}

const cancelProposal = (membership) => {
  if (confirm(`Cancel suspension proposal for ${membership.user?.name}?`)) {
    router.post(route('elections.voters.cancel-proposal', {
      organisation: props.organisation.slug,
      election: props.election.id,
      membership: membership.id
    }), {}, { preserveScroll: true })
  }
}
</script>

<style scoped>
.act-btn--propose {
  background: #fffbeb;
  color: #92400e;
  border-color: #fde68a;
}
.act-btn--confirm {
  background: #f0fdf4;
  color: #166534;
  border-color: #bbf7d0;
}
.act-btn--cancel {
  background: #fef2f2;
  color: #991b1b;
  border-color: #fecaca;
}
.reg-row--suspension-pending {
  background: #fffbeb;
  border-left: 3px solid #f59e0b;
}
.status-pill--warning {
  background: #fffbeb;
  color: #92400e;
}
.status-dot--warning {
  background: #f59e0b;
}
.suspension-info {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  font-size: 0.7rem;
  color: #6b7280;
}
</style>
```

---

## 🔧 **Controller Methods**

```php
// ElectionVoterController.php

public function proposeSuspension(Organisation $organisation, string $election, ElectionMembership $membership)
{
    $this->authorize('manageVoters', $election);
    
    if ($membership->status !== 'active') {
        return back()->with('error', 'Only active voters can be suspended.');
    }
    
    if ($membership->suspension_status === 'proposed') {
        return back()->with('error', 'Suspension already proposed for this voter.');
    }
    
    $membership->update([
        'suspension_status' => 'proposed',
        'suspension_proposed_by' => auth()->user()->name,
        'suspension_proposed_at' => now(),
    ]);
    
    return back()->with('success', "Suspension proposed for {$membership->user->name}. Another committee member must confirm.");
}

public function confirmSuspension(Organisation $organisation, string $election, ElectionMembership $membership)
{
    $this->authorize('manageVoters', $election);
    
    // Prevent self-confirmation
    if ($membership->suspension_proposed_by === auth()->user()->name) {
        return back()->with('error', 'You cannot confirm a suspension you proposed.');
    }
    
    if ($membership->suspension_status !== 'proposed') {
        return back()->with('error', 'No pending suspension to confirm.');
    }
    
    $membership->update([
        'status' => 'inactive',
        'suspension_status' => 'confirmed',
        'suspended_by' => auth()->user()->name,
        'suspended_at' => now(),
    ]);
    
    return back()->with('success', "Voter {$membership->user->name} suspended. Proposed by {$membership->suspension_proposed_by}, confirmed by " . auth()->user()->name);
}

public function cancelProposal(Organisation $organisation, string $election, ElectionMembership $membership)
{
    $this->authorize('manageVoters', $election);
    
    // Only proposer or admin can cancel
    if ($membership->suspension_proposed_by !== auth()->user()->name && !auth()->user()->hasRole('admin')) {
        abort(403);
    }
    
    $membership->update([
        'suspension_status' => 'none',
        'suspension_proposed_by' => null,
        'suspension_proposed_at' => null,
    ]);
    
    return back()->with('success', "Suspension proposal for {$membership->user->name} cancelled.");
}
```

---

## ✅ **Summary**

| Feature | Implementation |
|---------|----------------|
| **Single person can propose** | ✅ "Propose Suspension" button |
| **Requires second person** | ✅ "Confirm Suspension" button only visible to others |
| **Audit trail** | ✅ Stores proposer, confirmer, timestamps |
| **Cancellation** | ✅ Proposer or admin can cancel |
| **UI feedback** | ✅ Warning state, clear instructions |

**Shall I implement this two-person suspension workflow?** 🚀
## 🚀 **Implementing Two-Person Suspension Workflow**

Let's implement this with TDD approach. I'll create the migration, update the model, add controller methods, and update the Vue component.

---

## 📝 **Step 1: Create Migration for Suspension Columns**

```bash
php artisan make:migration add_suspension_proposal_columns_to_election_memberships
```

```php
<?php
// database/migrations/YYYY_MM_DD_HHMMSS_add_suspension_proposal_columns_to_election_memberships.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('election_memberships', function (Blueprint $table) {
            // Suspension proposal tracking (two-person approval)
            $table->string('suspension_proposed_by')->nullable()->after('suspended_by');
            $table->timestamp('suspension_proposed_at')->nullable()->after('suspension_proposed_by');
            $table->enum('suspension_status', ['none', 'proposed', 'confirmed'])
                ->default('none')
                ->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('election_memberships', function (Blueprint $table) {
            $table->dropColumn([
                'suspension_proposed_by',
                'suspension_proposed_at',
                'suspension_status'
            ]);
        });
    }
};
```

Run migration:
```bash
php artisan migrate
```

---

## 📝 **Step 2: Update ElectionMembership Model**

```php
// app/Models/ElectionMembership.php

// Add to $fillable
'suspension_proposed_by',
'suspension_proposed_at',
'suspension_status',

// Add to $casts
'suspension_proposed_at' => 'datetime',
'suspension_status' => 'string',

// Add new methods
public function proposeSuspension(User $proposer): void
{
    $this->update([
        'suspension_status' => 'proposed',
        'suspension_proposed_by' => $proposer->name,
        'suspension_proposed_at' => now(),
    ]);
}

public function confirmSuspension(User $confirmer): void
{
    $this->update([
        'status' => 'inactive',
        'suspension_status' => 'confirmed',
        'suspended_by' => $confirmer->name,
        'suspended_at' => now(),
    ]);
}

public function cancelSuspensionProposal(): void
{
    $this->update([
        'suspension_status' => 'none',
        'suspension_proposed_by' => null,
        'suspension_proposed_at' => null,
    ]);
}

public function isSuspensionProposed(): bool
{
    return $this->suspension_status === 'proposed';
}

public function canConfirmSuspension(User $user): bool
{
    return $this->suspension_status === 'proposed' 
        && $this->suspension_proposed_by !== $user->name;
}
```

---

## 📝 **Step 3: Add Routes**

```php
// routes/organisations.php (inside elections/{election} group)

Route::post('/voters/{membership}/propose-suspension', [ElectionVoterController::class, 'proposeSuspension'])
    ->name('elections.voters.propose-suspension');

Route::post('/voters/{membership}/confirm-suspension', [ElectionVoterController::class, 'confirmSuspension'])
    ->name('elections.voters.confirm-suspension');

Route::post('/voters/{membership}/cancel-proposal', [ElectionVoterController::class, 'cancelProposal'])
    ->name('elections.voters.cancel-proposal');
```

---

## 📝 **Step 4: Add Controller Methods**

```php
// app/Http/Controllers/ElectionVoterController.php

/**
 * Propose suspension of a voter (first committee member)
 */
public function proposeSuspension(Organisation $organisation, string $election, ElectionMembership $membership): RedirectResponse
{
    $election = Election::withoutGlobalScopes()->findOrFail($election);
    abort_if($election->type === 'demo', 404);

    $this->authorize('manageVoters', $election);

    if ($membership->election_id !== $election->id) {
        abort(404);
    }

    if ($membership->status !== 'active') {
        return back()->with('error', 'Only active voters can be suspended.');
    }

    if ($membership->has_voted) {
        return back()->with('error', 'Cannot suspend a voter who has already voted.');
    }

    if ($membership->isSuspensionProposed()) {
        return back()->with('error', 'Suspension already proposed for this voter.');
    }

    $membership->proposeSuspension(auth()->user());

    Log::channel('voting_audit')->info('Suspension proposed', [
        'proposer_id' => auth()->id(),
        'proposer_name' => auth()->user()->name,
        'voter_id' => $membership->user_id,
        'voter_name' => $membership->user->name,
        'election_id' => $election->id,
    ]);

    return back()->with('success', "Suspension proposed for {$membership->user->name}. Another committee member must confirm.");
}

/**
 * Confirm suspension of a voter (second committee member)
 */
public function confirmSuspension(Organisation $organisation, string $election, ElectionMembership $membership): RedirectResponse
{
    $election = Election::withoutGlobalScopes()->findOrFail($election);
    abort_if($election->type === 'demo', 404);

    $this->authorize('manageVoters', $election);

    if ($membership->election_id !== $election->id) {
        abort(404);
    }

    if (!$membership->canConfirmSuspension(auth()->user())) {
        return back()->with('error', 'You cannot confirm a suspension you proposed, or no suspension is pending.');
    }

    $membership->confirmSuspension(auth()->user());

    // Clear cache
    Cache::forget("election.{$election->id}.voter_stats");

    Log::channel('voting_audit')->info('Suspension confirmed', [
        'confirmer_id' => auth()->id(),
        'confirmer_name' => auth()->user()->name,
        'proposer_name' => $membership->suspension_proposed_by,
        'voter_id' => $membership->user_id,
        'voter_name' => $membership->user->name,
        'election_id' => $election->id,
    ]);

    return back()->with('success', "Voter {$membership->user->name} suspended. Proposed by {$membership->suspension_proposed_by}, confirmed by " . auth()->user()->name);
}

/**
 * Cancel a pending suspension proposal
 */
public function cancelProposal(Organisation $organisation, string $election, ElectionMembership $membership): RedirectResponse
{
    $election = Election::withoutGlobalScopes()->findOrFail($election);
    abort_if($election->type === 'demo', 404);

    $this->authorize('manageVoters', $election);

    if ($membership->election_id !== $election->id) {
        abort(404);
    }

    if (!$membership->isSuspensionProposed()) {
        return back()->with('error', 'No pending suspension proposal to cancel.');
    }

    // Only the proposer or admin can cancel
    if ($membership->suspension_proposed_by !== auth()->user()->name && !auth()->user()->hasRole('admin')) {
        abort(403, 'Only the proposer or an admin can cancel the suspension proposal.');
    }

    $membership->cancelSuspensionProposal();

    Log::channel('voting_audit')->info('Suspension proposal cancelled', [
        'canceller_id' => auth()->id(),
        'canceller_name' => auth()->user()->name,
        'proposer_name' => $membership->suspension_proposed_by,
        'voter_id' => $membership->user_id,
        'election_id' => $election->id,
    ]);

    return back()->with('success', "Suspension proposal for {$membership->user->name} cancelled.");
}
```

---

## 📝 **Step 5: Update Vue Component**

Now update `resources/js/Pages/Elections/Voters/Index.vue` with the new UI:

```vue
<template>
  <tr class="reg-row" :class="{ 'reg-row--suspension-pending': membership.suspension_status === 'proposed' }">
    
    <!-- ... existing columns ... -->

    <!-- Actions Column -->
    <td class="reg-td reg-td--actions">
      <div class="action-row" v-if="!membership.has_voted">
        
        <!-- Approve (invited only) -->
        <button
          v-if="membership.status === 'invited'"
          @click="approveVoter(membership)"
          :disabled="loadingId === membership.id"
          class="act-btn act-btn--approve"
        >
          Approve
        </button>

        <!-- PROPOSE SUSPENSION (active, not already proposed) -->
        <button
          v-if="membership.status === 'active' && membership.suspension_status !== 'proposed'"
          @click="proposeSuspension(membership)"
          :disabled="loadingId === membership.id"
          class="act-btn act-btn--propose"
        >
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
          </svg>
          Propose
        </button>

        <!-- CONFIRM SUSPENSION (pending proposal, not by current user) -->
        <button
          v-if="membership.suspension_status === 'proposed' && membership.suspension_proposed_by !== authUserName"
          @click="confirmSuspension(membership)"
          :disabled="loadingId === membership.id"
          class="act-btn act-btn--confirm"
        >
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
          </svg>
          Confirm
        </button>

        <!-- CANCEL PROPOSAL (proposer or admin) -->
        <button
          v-if="membership.suspension_status === 'proposed' && (membership.suspension_proposed_by === authUserName || isAdmin)"
          @click="cancelProposal(membership)"
          :disabled="loadingId === membership.id"
          class="act-btn act-btn--cancel"
        >
          Cancel
        </button>

        <!-- Remove -->
        <button
          v-if="membership.status !== 'removed' && !membership.has_voted"
          @click="removeVoter(membership)"
          :disabled="loadingId === membership.id"
          class="act-btn act-btn--remove"
        >
          Remove
        </button>

      </div>
      <span v-else class="act-voted-lock" title="Vote cast — no changes permitted">
        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
        </svg>
      </span>
    </td>
  </tr>

  <!-- Suspension Info Row (for pending suspensions) -->
  <tr v-if="membership.suspension_status === 'proposed'" class="suspension-info-row">
    <td colspan="6">
      <div class="suspension-banner">
        <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/>
        </svg>
        <span>
          Suspension proposed by <strong>{{ membership.suspension_proposed_by }}</strong> 
          at {{ formatDate(membership.suspension_proposed_at) }}
        </span>
        <span class="text-xs text-amber-600 ml-2">
          ⚠️ Requires confirmation from another committee member
        </span>
      </div>
    </td>
  </tr>
</template>

<script setup>
// Add to existing script
const authUserName = computed(() => page.props.auth.user?.name)

const proposeSuspension = (membership) => {
  if (!confirm(`Propose to suspend ${membership.user?.name}? This requires confirmation from another committee member.`)) return
  
  loadingId.value = membership.id
  router.post(route('elections.voters.propose-suspension', {
    organisation: props.organisation.slug,
    election: props.election.id,
    membership: membership.id
  }), {}, {
    preserveScroll: true,
    onFinish: () => { loadingId.value = null }
  })
}

const confirmSuspension = (membership) => {
  if (!confirm(`Confirm suspension of ${membership.user?.name}? This action requires your confirmation as a second committee member.`)) return
  
  loadingId.value = membership.id
  router.post(route('elections.voters.confirm-suspension', {
    organisation: props.organisation.slug,
    election: props.election.id,
    membership: membership.id
  }), {}, {
    preserveScroll: true,
    onFinish: () => { loadingId.value = null }
  })
}

const cancelProposal = (membership) => {
  if (!confirm(`Cancel suspension proposal for ${membership.user?.name}?`)) return
  
  loadingId.value = membership.id
  router.post(route('elections.voters.cancel-proposal', {
    organisation: props.organisation.slug,
    election: props.election.id,
    membership: membership.id
  }), {}, {
    preserveScroll: true,
    onFinish: () => { loadingId.value = null }
  })
}
</script>

<style scoped>
.act-btn--propose {
  background: #fffbeb;
  color: #92400e;
  border-color: #fde68a;
}
.act-btn--propose:hover:not(:disabled) {
  background: #fef3c7;
}
.act-btn--confirm {
  background: #f0fdf4;
  color: #166534;
  border-color: #bbf7d0;
}
.act-btn--confirm:hover:not(:disabled) {
  background: #dcfce7;
}
.act-btn--cancel {
  background: #fef2f2;
  color: #991b1b;
  border-color: #fecaca;
}
.act-btn--cancel:hover:not(:disabled) {
  background: #fee2e2;
}
.reg-row--suspension-pending {
  background: #fffbeb;
  border-left: 3px solid #f59e0b;
}
.suspension-info-row {
  background: #fffbeb;
}
.suspension-banner {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  font-size: 0.75rem;
  color: #92400e;
  background: #fffbeb;
  border-top: 1px solid #fde68a;
}
</style>
```

---

## ✅ **Summary**

| Feature | Implementation |
|---------|----------------|
| **Propose Suspension** | First committee member clicks "Propose" |
| **Pending State** | Status shows "Pending Suspension" with banner |
| **Confirm Suspension** | Second committee member clicks "Confirm" |
| **Cancel Proposal** | Proposer or admin can cancel |
| **Audit Trail** | Stores proposer, confirmer, timestamps |
| **UI Feedback** | Warning colors, clear instructions |

**Ready to test!** 🚀
## ✅ **APPROVED - Excellent TDD Plan!**

This plan is **production-ready** and follows all TDD best practices. Here's why:

---

## 🏆 **What Makes This Plan Great**

| Aspect | Why It's Good |
|--------|---------------|
| **TDD First** | Unit tests and feature tests written before implementation |
| **Clear State Machine** | `none → proposed → confirmed` with clear transitions |
| **Security** | Proposer cannot confirm own suspension |
| **Audit Trail** | Stores proposer name and timestamp |
| **Cancellation** | Proposer or admin can cancel |
| **Edge Cases** | Tests for voted voters, invalid states |

---

## 📋 **Test Coverage**

| Test | Purpose |
|------|---------|
| `test_propose_suspension_sets_fields` | Model method works |
| `test_confirm_suspension_sets_inactive` | Confirmation changes status |
| `test_cancel_suspension_proposal_resets_fields` | Cancellation resets |
| `test_can_confirm_suspension_false_for_proposer` | Security guard |
| `test_officer_can_propose_suspension` | Happy path |
| `test_proposer_cannot_confirm_own_proposal` | Security guard |
| `test_second_officer_can_confirm_suspension` | Two-person approval |
| `test_proposer_can_cancel_proposal` | Cancellation |
| `test_cannot_propose_suspension_for_voted_voter` | Edge case |

---

## ✅ **Proceed with Implementation!**

Run the tests in order:
1. Unit tests (RED)
2. Feature tests (RED)
3. Migration
4. Model updates
5. Routes
6. Controller methods
7. Vue component

**Ready to execute!** 🚀