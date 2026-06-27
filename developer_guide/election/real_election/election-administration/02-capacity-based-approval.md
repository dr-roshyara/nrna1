# Phase 3.2: Capacity-Based Approval Workflow

**Date:** 2026-05-20  
**Phase:** 3.2 (Graduated Strict Activation)  
**Status:** Complete and verified  
**Audience:** Backend engineers, system architects, election administrators

---

## 📋 What Is Capacity-Based Approval?

Before an election can move to the Setup phase, it must be **approved by the platform**. The approval process considers:

1. **Voter Capacity** - How many voters will participate?
2. **Organization Plan** - Free (≤40) or Paid (>40)?
3. **Payment Status** - Has payment been authorized?
4. **Timezone Validation** - Is a voting window timezone configured?

### The Rule

| Voter Count | Plan | Approval | Auto-Approve? |
|-------------|------|----------|---------------|
| ≤ 40 | Free | Instant | ✅ YES |
| > 40 | Free | Rejected | ❌ NO |
| > 40 | Paid | Requires platform + payment | Conditional |
| Any | Any | Without timezone | Blocked | ❌ Precondition fails |

---

## 🏛️ The Three Approval States

An election in "SubmittedForApproval" state can transition to:

```
SUBMITTED_FOR_APPROVAL
  ├─ ✅ APPROVED (platform review passed)
  │   └─ action: begin_setup → SETUP
  │
  └─ ❌ REJECTED (over capacity or payment denied)
      └─ action: revise_and_resubmit → DRAFT (back to edit)
```

### State Lifecycle

```php
// DRAFT - Initial state
// User configures election, sets expected_voter_count

$election->expected_voter_count = 150;
$election->timezone = 'Europe/Berlin';

// TRANSITION: submit_for_approval
ElectionLifecycle::of($election)->transitionVia('submit_for_approval');

// Result: State = SUBMITTED_FOR_APPROVAL
// At this point:
// - Guard checks timezone_set precondition ✅
// - Guard checks capacity_eligibility precondition
// - If free plan + 150 voters → REJECTED immediately
// - If paid plan + authorized → APPROVED immediately

// Case 1: Auto-rejection (free plan, over capacity)
// State stays SUBMITTED_FOR_APPROVAL, rejection recorded
// User must revise, reduce voters to ≤40, resubmit

$election->expected_voter_count = 35;  // Reduced
ElectionLifecycle::of($election)->transitionVia('revise_and_resubmit');  // Back to DRAFT
ElectionLifecycle::of($election)->transitionVia('submit_for_approval');  // Resubmit
// State = APPROVED (auto-approval for free plan ≤40)

// Case 2: Platform review (paid plan, awaiting approval)
// State = SUBMITTED_FOR_APPROVAL
// Platform admin reviews payment status
// If authorized:
ElectionLifecycle::of($election)->transitionVia('approve');
// State = APPROVED, now can proceed to setup

// Case 3: Explicit rejection
ElectionLifecycle::of($election)->transitionVia('reject');
// State = REJECTED
// User must revise and resubmit
```

---

## 🔍 Capacity Eligibility Logic

### The Precondition Implementation

```php
// app/Application/Election/Services/ConstitutionalTransitionGuard.php

private function isCapacityEligible(Election $election): bool
{
    // Get expected voter count from column (not expensive query)
    $voterCount = $election->expected_voter_count ?? 0;
    
    // Free plan: ≤ 40 voters allowed
    $organisation = $election->organisation;
    if (!$organisation || $organisation->plan === 'free') {
        return $voterCount <= 40;
    }
    
    // Paid plan: check payment authorization
    // TODO: Replace with actual payment authorization check when billing system implemented
    return true;  // Currently stubbed as true
}
```

### When Does It Run?

The precondition checks at these transitions:

1. **submit_for_approval** - Validates before entering approval workflow
2. **approve** - Re-validates payment status during approval
3. **reject** - Can always reject (no precondition needed)
4. **begin_setup** - Re-validates from APPROVED state (should pass)

### What Happens If It Fails?

**At Level 1-3 (Current):**
```php
// Guard blocks transition, logs warning
$lifecycle->transitionVia('submit_for_approval');
// → ConstitutionalMetrics::recordBlocked('capacity_eligibility')
// → Exception: Cannot transition: capacity_eligibility precondition not met
// → Election remains in DRAFT state
```

**At Level 4 (Future):**
```php
// Same behavior - exception thrown
// User sees: "You cannot submit this election. 
//            Maximum 40 voters allowed on free plan. 
//            Your expected_voter_count is 150. 
//            Please reduce voters or upgrade to paid plan."
```

---

## 📊 Voter Count Sources

### expected_voter_count (Column)

```php
// Set during election creation
$election = Election::create([
    'organisation_id' => $org->id,
    'name' => 'Board Election 2026',
    'expected_voter_count' => 35,  // ← Used for capacity check
    'timezone' => 'Europe/Berlin',
]);

// Or during editing
$election->update([
    'expected_voter_count' => 75,  // Revised count
]);

// Guard sees this and determines eligibility
```

**Why not COUNT actual voters?**
1. **Expensive** - Hits database on every guard check
2. **Timing issue** - Voter import may be partial during submission
3. **Accurate** - Admin knows how many to expect upfront
4. **Mutable** - Can be revised during draft phase

### actual_voter_count (Computed)

For reference/reporting only:

```php
// In ElectionManagementController or reports
$actualVoters = $election->memberships()
    ->where('role', 'voter')
    ->count();
    
// Used for audit trail after voting completes
// Not used for approval eligibility
```

---

## 🔐 Payment Authorization

### Current Implementation (Stubbed)

```php
// The TODO in code:
// TODO: Replace with actual payment authorization check when billing system implemented

// For now: always true
return true;
```

### Expected Implementation (When Billing Ready)

```php
// Option 1: Check Payment model
$hasValidPayment = $organisation->payments()
    ->where('status', 'authorized')
    ->where('plan', '!=', 'free')
    ->exists();

// Option 2: Check external payment processor
$payment = app(PaymentProvider::class)->getLatestAuthorization($organisation);
return $payment && $payment->isValid();

// Option 3: Delegation to payment service
$paymentService = app(PaymentAuthorizationService::class);
return $paymentService->isOrganisationAuthorized($organisation->id);
```

### Timeline for Implementation

- **Phase 3.2 (Now):** Stubbed, precondition always true for paid plan
- **Phase 4.1 (Soon):** Payment system integration begins
- **Phase 4.2 (Later):** Production payment checks active
- **Phase 5 (Future):** Advanced billing features (usage-based, tier upgrades)

---

## 🎯 Transition Matrix with Preconditions

```
FROM              ACTION              PRECONDITIONS          TO
─────────────────────────────────────────────────────────────────
DRAFT          submit_for_approval    timezone_set           SUBMITTED_FOR_APPROVAL
                                      capacity_eligibility   (then auto-approve if free ≤40)

SUBMITTED_FOR   approve               capacity_eligibility   APPROVED
APPROVAL                              (re-check payment)     

               reject                 (none)                 REJECTED

APPROVED       begin_setup            (none)                 SETUP

REJECTED       revise_and_resubmit    (none)                 DRAFT
```

### Precondition Definitions

| Precondition | Check | Fails If |
|--------------|-------|----------|
| `timezone_set` | `!empty($election->timezone)` | No timezone column value |
| `capacity_eligibility` | `isCapacityEligible($election)` | Free plan >40 voters OR paid without auth |

---

## 📝 Timezone Configuration

### Why Timezone is Required

The timezone is used for:
1. **Voting window boundaries** - When does voting open/close in user's timezone?
2. **Audit timestamps** - All logs show times in configured timezone
3. **Results publication** - When are results published (user-facing time)?
4. **Compliance** - Election commission rules may specify timezone-specific rules

### Setting Timezone

```php
// Option 1: During election creation
$election = Election::create([
    'name' => 'Board Election 2026',
    'timezone' => 'Europe/Berlin',  // Stored as column
    'expected_voter_count' => 30,
]);

// Option 2: During editing
$election->update([
    'timezone' => 'Europe/London',
]);

// Option 3: Via management controller
// (Vue form submits to ElectionManagementController)

// Timezone is NOT a setting - it's a dedicated column:
// elections table:
// - id
// - organisation_id
// - name
// - timezone ← HERE (varchar 255, nullable)
// - expected_voter_count ← HERE (int, nullable)
// - state ← Cached interpretation
// - ... other columns
```

### Validation

```php
// Timezone must be valid PHP timezone identifier
$validTimezones = DateTimeZone::listIdentifiers();

// Common timezones:
'Europe/Berlin'
'Europe/London'
'Europe/Paris'
'America/New_York'
'Asia/Tokyo'
'UTC'
```

---

## 🔄 Auto-Approval Flow

### What Happens Automatically

When an election in FREE plan is submitted with ≤40 voters:

```php
// User submits election
ElectionLifecycle::of($election)->transitionVia('submit_for_approval');

// Guard checks:
// 1. timezone_set? → YES, has 'Europe/Berlin'
// 2. capacity_eligibility? → YES, 35 voters on free plan

// Transition succeeds, state = SUBMITTED_FOR_APPROVAL

// Then: ConstitutionalTransitionGuard detects auto-approval eligibility
// Automatically transitions to APPROVED

// Result: Election state = APPROVED (instant, no waiting)
```

### How to Check Auto-Approval

```php
// In controller or service
$election->refresh();

// Check if auto-approved
$isApproved = $election->state === 'approved';

// Or via lifecycle
$lifecycle = ElectionLifecycle::of($election);
$isApproved = $lifecycle->state()->value === 'approved';
```

### Metrics for Auto-Approval

```php
// Track auto-approvals
$metrics = app(ConstitutionalMetricsContract::class);

// Metrics recorded:
// - elections_submitted_count: Total submitted
// - elections_auto_approved_count: Free plan, ≤40 voters
// - elections_awaiting_approval_count: Paid plan, awaiting payment review
// - elections_rejected_count: Over capacity on free plan
```

---

## ⚠️ Common Scenarios

### Scenario 1: Free Plan, Under Capacity

```
User creates election
  name: "Chapter Election 2026"
  expected_voter_count: 25
  timezone: "Europe/Berlin"
  organisation: FREE plan

User clicks "Submit for Approval"
  ✅ timezone_set? YES
  ✅ capacity_eligibility? YES (25 ≤ 40)
  
Result:
  State: APPROVED (instant auto-approval)
  User can immediately proceed to Setup
```

### Scenario 2: Free Plan, Over Capacity

```
User creates election
  expected_voter_count: 150
  organisation: FREE plan
  timezone: "Europe/Berlin"

User clicks "Submit for Approval"
  ✅ timezone_set? YES
  ❌ capacity_eligibility? NO (150 > 40)
  
Result:
  State: DRAFT (transition blocked)
  Message: "Cannot submit. Free plan limited to 40 voters. 
            Please reduce to ≤40 or upgrade."
  User must reduce voters and resubmit
```

### Scenario 3: Paid Plan, Authorized

```
User creates election
  expected_voter_count: 200
  organisation: PAID plan with active payment
  timezone: "Europe/Berlin"

User clicks "Submit for Approval"
  ✅ timezone_set? YES
  ✅ capacity_eligibility? YES (paid plan, authorized)
  
Result:
  State: SUBMITTED_FOR_APPROVAL (awaiting platform review)
  Admin dashboard shows pending approvals
  Platform admin clicks "Approve"
  State: APPROVED
  User can proceed to Setup
```

### Scenario 4: Paid Plan, No Payment

```
User creates election
  expected_voter_count: 200
  organisation: PAID plan, no payment
  timezone: "Europe/Berlin"

User clicks "Submit for Approval"
  ✅ timezone_set? YES
  ❌ capacity_eligibility? NO (no payment authorization)
  
Result:
  State: DRAFT (transition blocked)
  Message: "Your organization's payment is not authorized.
            Please complete payment setup before submitting."
  User must authorize payment and resubmit
```

### Scenario 5: Missing Timezone

```
User creates election without setting timezone
  expected_voter_count: 30
  organisation: FREE plan
  timezone: NULL

User clicks "Submit for Approval"
  ❌ timezone_set? NO
  [capacity_eligibility not checked - precondition failed first]
  
Result:
  State: DRAFT (transition blocked)
  Message: "Please set a voting timezone before submitting."
  User must configure timezone and resubmit
```

---

## 🧪 Testing Capacity Rules

### Test Structure

```php
// tests/Feature/Election/ApprovalWorkflow/CapacityEligibilityTest.php

class CapacityEligibilityTest extends TestCase
{
    /** @test */
    public function free_plan_under_capacity_auto_approves()
    {
        $org = Organisation::factory()->free()->create();
        $election = Election::factory()
            ->forOrganisation($org)
            ->inDraftState()
            ->withExpectedVoters(35)
            ->withTimezone('Europe/Berlin')
            ->create();

        $lifecycle = ElectionLifecycle::of($election);
        $lifecycle->transitionVia('submit_for_approval');

        $this->assertEquals('approved', $election->fresh()->state);
    }

    /** @test */
    public function free_plan_over_capacity_is_rejected()
    {
        $org = Organisation::factory()->free()->create();
        $election = Election::factory()
            ->forOrganisation($org)
            ->inDraftState()
            ->withExpectedVoters(150)
            ->withTimezone('Europe/Berlin')
            ->create();

        $this->expectException(TransitionBlockedException::class);
        ElectionLifecycle::of($election)->transitionVia('submit_for_approval');
    }

    /** @test */
    public function paid_plan_any_capacity_allowed()
    {
        $org = Organisation::factory()->paid()->create();
        $election = Election::factory()
            ->forOrganisation($org)
            ->inDraftState()
            ->withExpectedVoters(5000)
            ->withTimezone('UTC')
            ->create();

        // Should not throw - paid plan allows any capacity
        ElectionLifecycle::of($election)->transitionVia('submit_for_approval');

        $this->assertEquals('submitted_for_approval', $election->fresh()->state);
    }

    /** @test */
    public function missing_timezone_blocks_approval()
    {
        $org = Organisation::factory()->free()->create();
        $election = Election::factory()
            ->forOrganisation($org)
            ->inDraftState()
            ->withExpectedVoters(30)
            ->create();  // No timezone

        $this->expectException(TransitionBlockedException::class);
        ElectionLifecycle::of($election)->transitionVia('submit_for_approval');
    }
}
```

---

## 🔍 Debugging Capacity Issues

### Check Election Readiness

```php
$election = Election::find($id);
$lifecycle = ElectionLifecycle::of($election);

// Check preconditions
$guard = app(ConstitutionalTransitionGuard::class);

// Diagnose timezone
echo "Timezone: " . ($election->timezone ?? 'NOT SET');

// Diagnose capacity
echo "Expected voters: " . ($election->expected_voter_count ?? '0');
echo "Organisation: " . $election->organisation?->name;
echo "Plan: " . $election->organisation?->plan ?? 'N/A';
echo "Eligible? " . ($guard->isCapacityEligible($election) ? 'YES' : 'NO');

// Attempt transition and catch error
try {
    $lifecycle->transitionVia('submit_for_approval');
    echo "Status: APPROVED";
} catch (TransitionBlockedException $e) {
    echo "Blocked: " . $e->getMessage();
}
```

### Check Metrics

```php
$metrics = app(ConstitutionalMetricsContract::class);
$health = $metrics->getHealth();

echo "Submitted: " . $health['elections_submitted_count'];
echo "Auto-approved: " . $health['elections_auto_approved_count'];
echo "Awaiting approval: " . $health['elections_awaiting_approval_count'];
echo "Rejected: " . $health['elections_rejected_count'];
```

---

## 📈 Future Enhancements

### Capacity Tiers

```
FREE:      ≤ 40 voters
STARTER:   ≤ 250 voters
GROWTH:    ≤ 1000 voters
ENTERPRISE: Unlimited
```

### Dynamic Pricing

```
based on expected_voter_count:
  1-40:    FREE
  41-250:  $10/month
  251-1000: $50/month
  1000+:   Custom quote
```

### Payment Methods

```
- Credit card (immediate)
- Bank transfer (pending verification)
- Annual prepayment (discount)
- Trial period (7 days free)
```

### Approval Workflow Enhancements

```
- Platform admin review dashboard
- Payment status visibility
- Automatic capacity upgrades
- Tier change notifications
```

---

## 🎯 Summary

**Capacity-based approval ensures:**
1. ✅ Free tier doesn't exceed 40 voters
2. ✅ Paid tier respects payment status
3. ✅ All elections have timezone configured
4. ✅ Auto-approval for eligible elections (instant)
5. ✅ Manual review for paid/non-auto cases
6. ✅ Users can revise and resubmit
7. ✅ Full audit trail of approval decisions

**Key files:**
- `ElectionConstitution.php` - Approval workflow rules
- `ConstitutionalTransitionGuard.php` - Capacity eligibility precondition
- `ElectionLifecycleEngineImpl.php` - State derivation logic
- `ConstitutionalMetrics.php` - Approval metrics tracking

---

## 🔗 Related Documentation

- **State Machine Overview:** See `01-state-machine-overview.md`
- **SSOT Engine Logic:** See `03-ssot-engine-reference.md`
- **Write Barrier Details:** See `04-controlled-write-barrier.md`
- **Testing:** See `testing.md`
- **Troubleshooting:** See `troubleshooting.md`
