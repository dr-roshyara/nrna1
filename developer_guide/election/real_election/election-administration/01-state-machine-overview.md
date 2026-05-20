# Phase 3.2-3.3: Election State Machine Architecture

**Date:** 2026-05-20  
**Phase:** 3.2 (Graduated Strict Activation) + 3.3 (Controlled Write Barrier)  
**Status:** Complete and verified  
**Audience:** Backend engineers, system architects

---

## 📋 What Was Built

### The Problem: Split-Brain State Machine

Before Phase 3.2, the election state machine suffered from **dual authority**:

1. **Old System** (`status`, `is_active`, `state` columns)
   - Directly mutable via any code path
   - No validation, no authorization
   - Could diverge from business facts

2. **New System** (SSOT Engine)
   - Derives state from business facts (timestamps, completion flags)
   - Authoritative but advisory (no enforcement)
   - Could be ignored by legacy code

**Result:** Elections got stuck in invalid states (e.g., `state='import_voters'`) while the SSOT engine computed a different state. Management page returned 403 "Unknown state" errors.

### The Solution: Constitutional Governance

Implemented a **three-layer hardening strategy**:

1. **Phase 3.2:** Graduated Strict Activation
   - Level 1 (Metrics Strict): Record all violations silently
   - Level 2-3: Progressive blocking of queries/access
   - Level 4 (Full Strict): All unauthorized mutations throw exceptions

2. **Phase 3.2:** Approval Workflow Architecture
   - Added 3 new states: SubmittedForApproval, Approved, Rejected
   - Capacity-based approval (free/paid plan rules)
   - Timezone validation precondition

3. **Phase 3.3:** Controlled Write Barrier
   - `ElectionStateWriteContext`: Only guard can authorize mutations
   - Model mutator enforces sovereignty
   - All direct mutations recorded for audit trail

---

## 🏛️ Architecture: 10-State Lifecycle

The election now flows through this canonical state machine:

```
1. DRAFT
   └─ precondition: timezone_set required
   └─ action: submit_for_approval

2. SUBMITTED_FOR_APPROVAL
   └─ awaiting platform review
   ├─ action: approve (if free plan ≤40 voters)
   └─ action: reject

3a. APPROVED (if accepted)
   └─ action: begin_setup

3b. REJECTED (if denied)
   └─ action: revise_and_resubmit

4. SETUP
   └─ administration + nomination phases
   ├─ action: complete_administration (if has_posts, has_voters, has_committee_members)
   └─ action: complete_nomination (if has_approved_candidates)

5. READY_FOR_VOTING
   └─ awaiting voting window
   └─ action: open_voting (if voting_window_defined, timezone_set)

6. VOTING_ACTIVE
   └─ voters casting ballots
   └─ action: close_voting

7. COUNTING
   └─ votes being tallied
   └─ action: publish_results

8. RESULTS_PUBLISHED
   └─ final results visible
   └─ action: archive

9. ARCHIVED
   └─ terminal state, no further transitions
```

---

## 🔑 Key Concepts

### Single Source of Truth (SSOT)

**Old approach:** State stored in database column, derived from multiple flags
```php
// Inconsistent - column can diverge from facts
$election->state;  // 'import_voters'
$engine->getState($election);  // 'draft'
```

**New approach:** State computed from immutable business facts
```php
// Always consistent - facts are authoritative
$election->administration_completed = true;  // Fact
$election->nomination_completed = false;      // Fact
$election->voting_starts_at = '2026-06-01';   // Fact

$engine->getState($election);  // → 'setup' (derived)
```

**Business facts that derive state:**
- `administration_completed` / `administration_completed_at`
- `nomination_completed` / `nomination_completed_at`
- `voting_starts_at` / `voting_ends_at`
- `results_published_at`
- `rejected_at` / `approved_at` / `submitted_for_approval_at`
- Candidate approval count (via existence check)

### Constitutional Authority

**Before Phase 3.3:**
```
Console Command
    ↓
$election->update(['state' => 'administration'])  ← BYPASS (no authorization)
    ↓
State changed in database
```

**After Phase 3.3:**
```
Console Command
    ↓
ElectionStateWriteContext::authorize(function() {
    $election->update(['state' => 'administration']);
})
    ↓
Model mutator (setStateAttribute):
  • Check if authorized
  • Record violation to metrics (Level 1+)
  • Throw exception (Level 4+)
    ↓
State changed in database (only if authorized OR enforcement < 4)
```

### Graduated Enforcement Levels

| Level | Name | Behavior | Use Case |
|-------|------|----------|----------|
| 0 | Warning Mode | Log warnings, allow all | Baseline, no enforcement |
| 1 | Metrics Strict | Record violations, allow writes | Observe real coupling |
| 2 | Query Guard Strict | Block deprecated queries | Force individual routing |
| 3 | Lifecycle Strict | Block field access | Force SSOT usage |
| 4 | Full Strict | Throw exceptions | Production enforcement |

**Current state:** Level 1 (2026-05-20 11:00 UTC)

---

## 📁 Files Changed

### New Files Created

```
app/Application/Election/Governance/
  └─ ElectionStateWriteContext.php         [NEW] Write barrier authorization
  
app/Exceptions/
  └─ UnauthorizedStateMutationException.php [NEW] Thrown at Level 4

app/Domain/Election/Enum/
  └─ ElectionLifecycleState.php            [UPDATED] Added 3 new states
```

### Core Logic Files

```
app/Application/Election/Services/
  ├─ ElectionLifecycleEngineImpl.php        [UPDATED] Derives new states
  └─ ConstitutionalTransitionGuard.php     [UPDATED] Validates transitions

app/Domain/Election/Constitution/
  └─ ElectionConstitution.php              [UPDATED] 5 new actions

app/Models/
  └─ Election.php                          [UPDATED] setStateAttribute() mutator

app/Application/Election/Monitoring/
  ├─ ConstitutionalMetricsContract.php    [UPDATED] New violation type
  └─ ConstitutionalMetrics.php             [UPDATED] Records mutations
```

### Console Commands

```
app/Console/Commands/
  ├─ ActivateElectionCommand.php           [UPDATED] Uses ElectionStateWriteContext::authorize()
  └─ BackfillElectionState.php             [UPDATED] Dual-mode (audit/repair)
```

---

## ✅ Verification Checklist

Before using the state machine in production:

- [ ] Architecture tests passing: `php artisan test tests/Architecture/ElectionControllerArchitectureTest.php --no-coverage`
- [ ] Constitutional health green: `php artisan election:constitution:health` → "READY FOR STRICT MODE"
- [ ] Management page loads for draft election without 403 errors
- [ ] BackfillElectionState audit mode finds no divergences: `php artisan app:backfill-election-state --audit-only`
- [ ] ActivateElectionCommand routes through authorize context
- [ ] Metrics recording violations to ConstitutionalMetrics

---

## 🚀 Quick Start

### Check Election State (Correct Way)

```php
use App\Application\Election\Facades\ElectionLifecycle;

$election = Election::find($id);

// ✅ CORRECT - Uses SSOT engine
$lifecycle = ElectionLifecycle::of($election);
$state = $lifecycle->state();           // ElectionLifecycleState enum
$label = $state->label();                // "Draft Setup"
$isLocked = $lifecycle->isLocked();      // Boolean

// ❌ WRONG - Reads stale cached column
$state = $election->state;  // May diverge from SSOT
```

### Record Violation (For Audit Trail)

```php
$metrics = app(\App\Application\Election\Monitoring\ConstitutionalMetricsContract::class);
$metrics->recordUnauthorizedStateMutation('state', 'ActivateElectionCommand');

// Later, view health report:
$health = $metrics->getHealth();
echo $health['violations_24h'];  // Count of violations
```

### Transition Election State (Authorized)

```php
use App\Application\Election\Governance\ElectionStateWriteContext;

// Only way to change state:
ElectionStateWriteContext::authorize(function() use ($election, $newState) {
    $election->update(['state' => $newState]);
});

// This records the mutation AND allows it (at Level 1-3)
// At Level 4, mutator throws exception if not authorized
```

---

## 🔗 Related Documentation

- **Approval Workflow Rules:** See `02-capacity-based-approval.md`
- **State Derivation Logic:** See `03-ssot-engine-reference.md`
- **Write Barrier Implementation:** See `04-controlled-write-barrier.md`
- **Troubleshooting:** See `troubleshooting.md`
- **Testing:** See `testing.md`
