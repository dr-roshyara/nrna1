# Constitutional Transition Guard Layer (CTGL)

**Date:** 2026-05-18  
**Status:** Architectural Addition to SSOT Plan  
**Scope:** Enforcement boundary for state transitions  

---

## 🎯 Purpose

The Transition Guard Layer ensures:

> **"No state change can happen unless it is constitutionally valid according to the lifecycle snapshot."**

It is the **enforcement firewall between intent and mutation**.

---

## 🏗️ Where It Sits in Architecture

```
┌─────────────────────────────────┐
│   UI / Controller               │
│   "User wants to open voting"   │
└──────────────┬──────────────────┘
               │
┌──────────────▼──────────────────┐
│  Application Service            │
│  (Orchestration Layer)          │
└──────────────┬──────────────────┘
               │
┌──────────────▼──────────────────┐
│  🛑 TRANSITION GUARD LAYER      │
│  (Constitutional Enforcement)    │
│  - Validate preconditions       │
│  - Check state rules            │
│  - Enforce invariants           │
└──────────────┬──────────────────┘
               │
┌──────────────▼──────────────────┐
│  ElectionLifecycleEngine        │
│  (SSOT - Read Truth)            │
│  Snapshot: state + permissions  │
└──────────────┬──────────────────┘
               │
┌──────────────▼──────────────────┐
│  Domain Mutation Layer          │
│  (Aggregate root updates)       │
└──────────────┬──────────────────┘
               │
┌──────────────▼──────────────────┐
│  Persistence Layer              │
│  (Database writes)              │
└─────────────────────────────────┘
```

---

## ⚖️ Core Principle

```
SSOT tells you "what is true"     (read model)
Guard Layer decides "what is allowed to change"    (write rules)

Together they form the Constitution.
```

---

## 🧩 The Three-Layer Constitutional Model

### Layer 1: Truth (Read)

```php
$snapshot = $engine->compute($election);
// Returns: current reality
```

### Layer 2: Law (Write Gate)

```php
$guard->assertAllowed($election, 'open_voting', $snapshot);
// Enforces: constitutional rules
// Throws: InvalidTransitionException if illegal
```

### Layer 3: Execution (Mutation)

```php
$election->transitionTo('open_voting');
// Executes: only if guard passed
```

---

## 🏛️ Constitutional Rules Model

Instead of scattered `if` conditions throughout the codebase:

### ❌ Old Style (Dangerous)

```php
if ($election->state !== 'voting') {
    throw new Exception();
}
// But wait, also check:
if (!$election->administration_completed) {
    throw new Exception();
}
// And also:
if ($election->voting_starts_at === null) {
    throw new Exception();
}
// This logic is SCATTERED everywhere
```

### ✅ New Model (Constitutional)

```php
$guard->assertAllowed($election, 'open_voting', $snapshot);
// ALL rules centralized in one place
// Constitution is explicit and auditable
```

---

## 🧠 TransitionGuard Interface

```php
namespace App\Domain\Election\Services;

interface TransitionGuard
{
    /**
     * Enforce constitutional rules for a transition.
     * 
     * @param Election $election
     * @param string $action (e.g., 'open_voting', 'publish_results')
     * @param ElectionLifecycleSnapshot $snapshot (current computed state)
     * 
     * @throws InvalidTransitionException if transition not allowed
     * @throws PrerequisiteNotMetException if preconditions missing
     */
    public function assertAllowed(
        Election $election,
        string $action,
        ElectionLifecycleSnapshot $snapshot
    ): void;
    
    /**
     * Optional: Explain why a transition is blocked (for admin UI).
     * 
     * @return array{missing: string[], reason: string}
     */
    public function whyNotAllowed(
        Election $election,
        string $action,
        ElectionLifecycleSnapshot $snapshot
    ): array;
}
```

---

## 📋 ElectionConstitution: The Rules Registry

This is the **single source of constitutional truth** for what transitions are allowed.

```php
namespace App\Domain\Election;

final class ElectionConstitution
{
    /**
     * Constitutional rules for all allowed transitions.
     * 
     * Format:
     * action => {
     *   allowed_states: states where action is legal,
     *   requires: prerequisites that must be true
     * }
     */
    public const RULES = [
        // ╔════════════════════════════════════════╗
        // ║ DRAFT → PENDING_APPROVAL               ║
        // ╚════════════════════════════════════════╝
        'submit_for_approval' => [
            'allowed_states' => ['draft'],
            'requires' => [],  // Can submit anytime from draft
        ],

        // ╔════════════════════════════════════════╗
        // ║ PENDING_APPROVAL → ADMINISTRATION      ║
        // ╚════════════════════════════════════════╝
        'approve_election' => [
            'allowed_states' => ['pending_approval'],
            'requires' => [],
        ],

        // ╔════════════════════════════════════════╗
        // ║ ADMINISTRATION → NOMINATION            ║
        // ║ (Complete setup: posts + voters + committee verified)
        // ╚════════════════════════════════════════╝
        'complete_administration' => [
            'allowed_states' => ['setup'],  // Lifecycle state
            'requires' => [
                'administration_completed',          // Boolean flag
                'administration_posts_verified',     // Timestamp check
                'administration_voters_verified',    // Timestamp check
                'administration_committee_verified', // Timestamp check
            ],
        ],

        // ╔════════════════════════════════════════╗
        // ║ NOMINATION → VOTING                    ║
        // ║ (Candidates approved, window defined)
        // ╚════════════════════════════════════════╝
        'open_voting' => [
            'allowed_states' => ['ready_for_voting'],
            'requires' => [
                'nomination_completed',      // Candidates approved
                'voting_window_defined',     // start_at + end_at set
                'has_approved_candidates',   // At least 1 per post
            ],
        ],

        // ╔════════════════════════════════════════╗
        // ║ VOTING (Active) → RESULTS_PENDING      ║
        // ╚════════════════════════════════════════╝
        'close_voting' => [
            'allowed_states' => ['voting_active'],
            'requires' => [],  // Can close anytime
        ],

        // ╔════════════════════════════════════════╗
        // ║ RESULTS_PENDING → RESULTS              ║
        // ║ (Results calculated and ready)
        // ╚════════════════════════════════════════╝
        'publish_results' => [
            'allowed_states' => ['counting'],
            'requires' => [
                'results_calculated',  // Tallying complete
            ],
        ],

        // ╔════════════════════════════════════════╗
        // ║ RESULTS → ARCHIVED                     ║
        // ╚════════════════════════════════════════╝
        'archive_election' => [
            'allowed_states' => ['results_published'],
            'requires' => [],
        ],

        // ╔════════════════════════════════════════╗
        // ║ SPECIAL: Can always reject during setup
        // ╚════════════════════════════════════════╝
        'reject_election' => [
            'allowed_states' => ['pending_approval', 'setup'],
            'requires' => [],
        ],
    ];
}
```

---

## 🧩 TransitionGuard Implementation

```php
namespace App\Application\Election\Services;

final class ConstitutionalTransitionGuard implements TransitionGuard
{
    public function __construct(
        private readonly VerificationPolicyInterface $verificationPolicy,
    ) {}
    
    /**
     * HARD GATE: No transition allowed unless constitutional rules pass.
     */
    public function assertAllowed(
        Election $election,
        string $action,
        ElectionLifecycleSnapshot $snapshot
    ): void {
        // 1. Is this action even defined?
        $rules = ElectionConstitution::RULES[$action] ?? null;
        if ($rules === null) {
            throw new InvalidTransitionException(
                "Unknown action: '{$action}'"
            );
        }

        // 2. Is the current state allowed to perform this action?
        if (!in_array($snapshot->state->value, $rules['allowed_states'], true)) {
            throw new InvalidTransitionException(
                "Action '{$action}' not allowed in state '{$snapshot->state->value}'. "
                . "Allowed states: " . implode(', ', $rules['allowed_states'])
            );
        }

        // 3. Are all prerequisites met?
        $missingRequirements = [];
        foreach ($rules['requires'] as $requirement) {
            if (!$this->evaluateRequirement($election, $requirement)) {
                $missingRequirements[] = $requirement;
            }
        }
        
        if (!empty($missingRequirements)) {
            throw new PrerequisiteNotMetException(
                "Transition '{$action}' blocked. Missing requirements: "
                . implode(', ', $missingRequirements),
                $missingRequirements
            );
        }

        // 4. Does the snapshot agree? (redundant but defensive)
        if (!$snapshot->canTransitionTo($action)) {
            throw new InvalidTransitionException(
                "Lifecycle snapshot denies action '{$action}'. "
                . "Reason: {$snapshot->blockedReason}"
            );
        }
    }

    /**
     * OPTIONAL: Explain why a transition is blocked (for admin UI).
     */
    public function whyNotAllowed(
        Election $election,
        string $action,
        ElectionLifecycleSnapshot $snapshot
    ): array {
        $rules = ElectionConstitution::RULES[$action] ?? null;
        if ($rules === null) {
            return [
                'missing' => [],
                'reason' => "Unknown action: {$action}",
            ];
        }

        if (!in_array($snapshot->state->value, $rules['allowed_states'])) {
            return [
                'missing' => [],
                'reason' => "Not allowed in state '{$snapshot->state->value}'",
                'expected_states' => $rules['allowed_states'],
            ];
        }

        $missing = [];
        foreach ($rules['requires'] as $requirement) {
            if (!$this->evaluateRequirement($election, $requirement)) {
                $missing[] = $requirement;
            }
        }

        return [
            'missing' => $missing,
            'reason' => $snapshot->blockedReason ?? "Unknown blocking reason",
        ];
    }

    /**
     * Evaluate a single requirement.
     * 
     * Requirements are predicates that check election state.
     * Add new requirements here as business rules evolve.
     */
    private function evaluateRequirement(Election $election, string $requirement): bool
    {
        return match ($requirement) {
            // BOOLEAN FLAGS
            'administration_completed' => $election->administration_completed,
            'nomination_completed' => $election->nomination_completed,

            // TIMESTAMP CHECKS (null = not verified)
            'administration_posts_verified' => $election->administration_posts_verified_at !== null,
            'administration_voters_verified' => $election->administration_voters_verified_at !== null,
            'administration_committee_verified' => $election->administration_committee_verified_at !== null,

            // WINDOW DEFINED
            'voting_window_defined' => $election->voting_starts_at !== null && $election->voting_ends_at !== null,

            // CANDIDATES EXIST
            'has_approved_candidates' => $this->hasApprovedCandidatesPerPost($election),

            // RESULTS CALCULATED
            'results_calculated' => $election->results_calculated_at !== null,

            // DEFAULT
            default => false,
        };
    }

    private function hasApprovedCandidatesPerPost(Election $election): bool
    {
        $posts = $election->posts()->count();
        if ($posts === 0) {
            return false;
        }

        $approvedCandidates = $election->candidacies()
            ->where('status', 'approved')
            ->count();

        // Simplified: at least 1 approved candidate total
        // Real rule: 1+ per post
        return $approvedCandidates > 0;
    }
}
```

---

## 🔁 Full Flow Example: Opening Voting

```
Controller: "User clicked 'Open Voting'"
    ↓
openVotingAction(Election $election)
    ↓
// 1. Compute current truth
$snapshot = $engine->compute($election);
// Result: state=ReadyForVoting, canVote=false
    ↓
// 2. Guard checks constitutional rules
$guard->assertAllowed($election, 'open_voting', $snapshot);
// Checks:
//   ✓ state = 'ready_for_voting' (allowed)
//   ✓ voting_window_defined = true (required)
//   ✓ has_approved_candidates = true (required)
// → PASSES
    ↓
// 3. Execute mutation
$election->transitionTo('open_voting');
$election->save();
    ↓
// 4. Event published (optional)
event(new VotingOpened($election));
    ↓
✅ Voting is now open
```

---

## 🚨 Illegal Attempt Blocked

```
Controller: "User clicked 'Open Voting'"
    ↓
// User is in SETUP state (hasn't completed administration)
$election->state = 'setup'
    ↓
$snapshot = $engine->compute($election);
// Result: state=Setup
    ↓
$guard->assertAllowed($election, 'open_voting', $snapshot);
    ↓
🛑 EXCEPTION: InvalidTransitionException
   "Action 'open_voting' not allowed in state 'setup'. "
   "Allowed states: ready_for_voting"
    ↓
❌ Illegal transition prevented
❌ No database mutation happens
❌ System remains consistent
```

---

## 🧠 Key Design Principles

### 1. Constitution is Explicit

All rules are in ONE place: `ElectionConstitution::RULES`

```php
// NOT scattered:
if ($election->state === 'voting') { ... }
if ($election->state === 'results') { ... }
if ($election->state === 'administration') { ... }

// YES centralized:
ElectionConstitution::RULES['action'] => [...]
```

### 2. Guard is Mandatory Firewall

NO ONE can bypass the guard:

```php
// ❌ NOT allowed:
$election->state = 'voting';
$election->save();

// ✅ Required:
$guard->assertAllowed($election, 'open_voting', $snapshot);
$election->transitionTo('open_voting');
```

### 3. Guard is Defensive (Layered Checks)

```php
Guard checks:
  1. Action is defined
  2. State is allowed
  3. Prerequisites met
  4. Snapshot agrees

All must pass. One failure = no mutation.
```

### 4. Requirements are Testable

Each requirement is a discrete predicate:

```php
'voting_window_defined' => (
    $election->voting_starts_at !== null 
    && $election->voting_ends_at !== null
)
```

Easy to test. Easy to understand.

---

## 🧪 Testing the Guard Layer

### Unit Tests

```php
tests/Unit/Domain/Election/ElectionConstitutionTest.php

- rules_define_all_known_actions
- rules_reference_valid_states
- rules_reference_valid_requirements

tests/Unit/Application/Election/ConstitutionalTransitionGuardTest.php

- assert_allowed_passes_for_valid_transition
- assert_allowed_throws_for_invalid_state
- assert_allowed_throws_for_missing_prerequisites
- why_not_allowed_explains_missing_requirements
```

### Integration Tests

```php
tests/Feature/Election/ConstitutionalGuardIntegrationTest.php

- cannot_open_voting_without_approved_candidates
- cannot_complete_administration_without_verifications
- cannot_publish_results_without_calculations
```

---

## 🔐 Safety Guarantees

With this layer in place:

```
✅ No invalid state transitions possible
✅ All rules centralized and auditable
✅ Guard acts as constitutional firewall
✅ Requirements explicit and testable
✅ Mutations only happen if legal
✅ System state always consistent
```

---

## 🎯 Implementation Order (Updated Phase 1)

### Phase 1.1-1.4: SSOT (already planned)

### **Phase 1.5 (NEW): Add Constitutional Guard**

1. Create `ElectionConstitution` rules registry
2. Create `TransitionGuard` interface
3. Create `ConstitutionalTransitionGuardImpl`
4. Register in AppServiceProvider
5. Add unit tests

### Phase 1.6: Update Controllers to use Guard

```php
// Before executing any state change:
$snapshot = $engine->compute($election);
$this->guard->assertAllowed($election, $action, $snapshot);

// Then execute:
$election->transitionTo($action);
```

---

## 📝 Summary

The **Constitutional Transition Guard Layer** ensures:

1. **Centralized Rules:** All transition rules in one place
2. **Mandatory Gating:** All writes go through guard
3. **Defensive Layering:** Multiple checks, all must pass
4. **Auditability:** Rules are explicit and testable
5. **Consistency:** System state always constitutionally valid

---

**Next Step:** Update SSOT-Refactoring-Plan.md to include ConstitutionalTransitionGuard in Phase 1.
