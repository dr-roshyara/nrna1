# Constitutional Governance Engine

**Date:** 2026-05-24  
**Phase:** C.2 (Capability Authority Consolidation)  
**Status:** Complete and verified  
**Audience:** Backend engineers, system architects, API integrators

---

## Table of Contents

1. [Overview](#overview)
2. [12-State Lifecycle](#12-state-lifecycle)
3. [Constitutional Rules Registry](#constitutional-rules-registry)
4. [Constitutional Transition Guard](#constitutional-transition-guard)
5. [Dual-Axis Model: Lifecycle + Operational Overlay](#dual-axis-model-lifecycle--operational-overlay)
6. [4-Layer Capability Resolution](#4-layer-capability-resolution)
7. [State Derivation Engine (SSOT)](#state-derivation-engine-ssot)
8. [Controlled Write Barrier](#controlled-write-barrier)
9. [ElectionAction Enum](#electionaction-enum)
10. [ElectionLifecycleState Enum](#electionlifecyclestate-enum)
11. [Controller Patterns](#controller-patterns)
12. [Frontend Integration](#frontend-integration)
13. [Testing Strategy](#testing-strategy)
14. [Key File Reference](#key-file-reference)

---

## Overview

The Constitutional Governance Engine is a **domain-driven election lifecycle management system**. It replaces the legacy ad-hoc `status`/`is_active` field approach with a deterministic, rules-driven state machine.

### Core Principles

```
1. State is derived from business facts, NOT stored in a column
2. All transitions are validated against a constitutional rules registry
3. Suspension is an operational overlay, NOT a lifecycle state
4. Capabilities are resolved through a 4-layer policy pipeline
5. The constitution is the single source of truth for all rules
```

### Architecture Layers

```
┌──────────────────────────────────────────────────────────────┐
│  FACADE LAYER                                                │
│  ElectionLifecycle facade — simplified API for controllers   │
├──────────────────────────────────────────────────────────────┤
│  CAPABILITY RESOLUTION LAYER                                 │
│  4-layer policy pipeline: Overlay → Lifecycle → Preconditions│
│  → Authorization                                             │
├──────────────────────────────────────────────────────────────┤
│  CONSTITUTIONAL GOVERNANCE LAYER                             │
│  ElectionConstitution::RULES + ConstitutionalTransitionGuard │
├──────────────────────────────────────────────────────────────┤
│  STATE DERIVATION LAYER (SSOT)                               │
│  ElectionLifecycleEngineImpl — derives state from facts      │
├──────────────────────────────────────────────────────────────┤
│  WRITE BARRIER LAYER                                         │
│  ElectionStateWriteContext — controls state column mutations │
└──────────────────────────────────────────────────────────────┘
```

---

## 12-State Lifecycle

The election progresses through 12 states. Suspended is **not** a lifecycle state — it is an operational overlay (see [Dual-Axis Model](#dual-axis-model-lifecycle--operational-overlay)).

### State Transition Diagram

```
DRAFT
  │  action: submit_for_approval (precondition: timezone_set)
  ▼
SUBMITTED_FOR_APPROVAL
  ├── action: approve → APPROVED
  └── action: reject  → REJECTED
                              │
                              ▼
                         REJECTED
                              │  action: revise_and_resubmit
                              ▼
                         SUBMITTED_FOR_APPROVAL

APPROVED
  │  action: begin_setup (→ complete_administration → SetupAdministration)
  ▼
SETUP_ADMINISTRATION
  │  precondition: has_posts, has_voters, has_chief
  │  action: complete_administration
  ▼
SETUP_NOMINATION
  │  precondition: has_approved_candidates
  │  action: complete_nomination
  ▼
READY_FOR_VOTING
  │  precondition: voting_window_defined, timezone_set
  │  action: open_voting
  ▼
VOTING_ACTIVE
  │  action: close_voting
  ▼
COUNTING
  │  action: publish_results
  ▼
RESULTS_PUBLISHED
  │  action: archive
  ▼
ARCHIVED (terminal)
```

### State Descriptions

| State | Label | Description | Terminal? |
|-------|-------|-------------|-----------|
| `Draft` | Draft Setup | Initial state, configuration in progress | No |
| `SubmittedForApproval` | Submitted for Approval | Awaiting platform review | No |
| `Approved` | Approved | Passed approval, ready for setup | No |
| `Rejected` | Rejected | Denied; can revise and resubmit | No |
| `SetupAdministration` | Setup Administration | Admin phase (posts, voters, officers) | No |
| `SetupNomination` | Setup Nomination | Candidate nomination phase | No |
| `ReadyForVoting` | Ready for Voting | Awaiting voting window | No |
| `VotingActive` | Voting Active | Voting currently open | No |
| `Counting` | Counting | Votes being tallied | No |
| `ResultsPublished` | Results Published | Results visible to all | No |
| `Archived` | Archived | Historical record | Yes |
| `Suspended` | Suspended | Operational freeze (overlay) | No |

### Helper Methods

```php
enum ElectionLifecycleState: string
{
    case Draft = 'draft';
    case SubmittedForApproval = 'submitted_for_approval';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case SetupAdministration = 'setup_administration';
    case SetupNomination = 'setup_nomination';
    case ReadyForVoting = 'ready_for_voting';
    case VotingActive = 'voting_active';
    case Counting = 'counting';
    case ResultsPublished = 'results_published';
    case Archived = 'archived';
    case Suspended = 'suspended';

    // Human-readable labels
    public function label(): string;

    // Terminal states (Archived only)
    public function isTerminal(): bool;

    // Awaiting approval states
    public function isAwaitingApproval(): bool;  // SubmittedForApproval

    // Setup states
    public function isInSetup(): bool;  // SetupAdministration, SetupNomination

    // Voting states
    public function isVotingPhase(): bool;  // ReadyForVoting, VotingActive
}
```

---

## Constitutional Rules Registry

The heart of the governance engine is `ElectionConstitution::RULES` — a static rules registry that defines every action's constraints.

### Location

`app/Domain/Election/Constitution/ElectionConstitution.php`

### Structure

```php
const RULES = [
    'submit_for_approval' => [
        'allowed_states' => ['draft'],
        'allowed_roles' => ['chief', 'deputy'],
        'preconditions' => ['timezone_set'],
        'target_state' => 'submitted_for_approval',
        'description' => 'Submit election for platform approval',
    ],
    // ... 9 more actions
];
```

### Each Action Entry

| Field | Type | Description |
|-------|------|-------------|
| `allowed_states` | `string[]` | Lifecycle states from which this action is permitted |
| `allowed_roles` | `string[]` | Officer roles authorized to perform this action |
| `preconditions` | `string[]` | Business conditions that must be satisfied |
| `target_state` | `string` | The resulting lifecycle state after successful transition |
| `description` | `string` | Human-readable description |

### Complete Rules Table

| Action | Allowed States | Roles | Preconditions | Target State |
|--------|---------------|-------|---------------|-------------|
| `submit_for_approval` | draft | chief, deputy | timezone_set | submitted_for_approval |
| `approve` | submitted_for_approval | platform_admin | capacity_eligibility | approved |
| `reject` | submitted_for_approval | platform_admin | — | rejected |
| `auto_submit` | any active | system | — | (auto-computed) |
| `begin_setup` | approved | chief, deputy | — | setup_administration |
| `revise_and_resubmit` | rejected | chief, deputy | — | draft |
| `complete_administration` | setup_administration | chief, deputy | has_posts, has_voters, has_chief | setup_nomination |
| `complete_nomination` | setup_nomination | chief, deputy | has_approved_candidates | ready_for_voting |
| `open_voting` | ready_for_voting | chief, deputy | voting_window_defined, timezone_set, capacity_eligibility | voting_active |
| `close_voting` | voting_active | chief, deputy | — | counting |
| `publish_results` | counting | chief | — | results_published |
| `archive` | results_published | chief, deputy | — | archived |
| `suspend` | all except suspended, archived | chief, platform_admin | — | suspended |
| `resume` | suspended | chief, platform_admin | — | suspended |

### Preconditions

| Precondition | Meaning | Implementation |
|-------------|---------|---------------|
| `has_posts` | At least one post (position) exists | `$election->posts()->withoutGlobalScopes()->exists()` |
| `has_voters` | At least one voter registered | Checks both `election_memberships` and `voters` tables |
| `has_chief` | At least one active chief officer | `ElectionOfficer` with `role=chief`, `status=active` |
| `has_approved_candidates` | At least one approved candidate | `Candidacy` with `status=approved` |
| `voting_window_defined` | Voting start and end times set | Both `voting_starts_at` and `voting_ends_at` not null |
| `timezone_set` | Election timezone configured | `timezone` field not empty |
| `capacity_eligibility` | Meets plan requirements | ≤40 voters auto-approve, >40 requires payment |

### Query API

```php
// Get all rules for an action
ElectionConstitution::getRulesForAction('open_voting');

// Check if action is allowed in current state
ElectionConstitution::isActionAllowedInState('open_voting', ElectionLifecycleState::ReadyForVoting);
// → true

// Get allowed roles for an action
ElectionConstitution::getAllowedRolesForAction('publish_results');
// → ['chief']

// Get preconditions for an action
ElectionConstitution::getPreconditionsForAction('complete_administration');
// → ['has_posts', 'has_voters', 'has_chief']

// Get target state after transition
ElectionConstitution::getTargetStateForAction('close_voting');
// → 'counting'

// Get all valid target states
ElectionConstitution::getValidTargetStates();
```

---

## Constitutional Transition Guard

The `ConstitutionalTransitionGuard` performs a **4-check enforcement** before any state transition.

### Location

`app/Application/Election/Services/ConstitutionalTransitionGuard.php`

### The 4 Checks

```
┌─────────────────────────────────────────────────────────────┐
│  ConstitutionalTransitionGuard::assertAllowed()              │
│                                                              │
│  Check 1: Action is DEFINED in the constitution              │
│  ───────────────────────────────────────────────────────     │
│  → Is this action registered in ElectionConstitution::RULES? │
│  → If no: throw InvalidTransitionException                   │
│                                                              │
│  Check 2: State ALLOWS this action                           │
│  ───────────────────────────────────────────────────────     │
│  → Is current state in allowed_states for this action?       │
│  → If no: throw InvalidTransitionException                   │
│                                                              │
│  Check 3: User has REQUIRED role                             │
│  ───────────────────────────────────────────────────────     │
│  → Does user have one of allowed_roles via ElectionOfficer?  │
│  → Special role 'system' bypasses for automatic transitions  │
│  → If no: throw InvalidTransitionException                   │
│                                                              │
│  Check 4: Preconditions SATISFIED                            │
│  ───────────────────────────────────────────────────────     │
│  → Are all listed preconditions met?                         │
│  → If no: throw InvalidTransitionException with details      │
│                                                              │
│  ✅ All checks pass → Transition is allowed                  │
└─────────────────────────────────────────────────────────────┘
```

### Implementation

```php
final class ConstitutionalTransitionGuard
{
    public static function assertAllowed(
        Election $election,
        string $action,
        User $user
    ): void {
        // Check 1: Action defined in constitution
        $rules = ElectionConstitution::getRulesForAction($action);
        if ($rules === null) {
            throw new InvalidTransitionException(
                "Action '{$action}' is not defined in the election constitution."
            );
        }

        // Check 2: State allows action
        $currentState = app(ElectionLifecycleEngineImpl::class)
            ->getState($election)->value;
        if (!in_array($currentState, $rules['allowed_states'])) {
            throw new InvalidTransitionException(
                "Cannot {$action} from current state '{$currentState}'."
            );
        }

        // Check 3: User has required role
        if (!in_array('system', $rules['allowed_roles'])) {
            $hasRole = ElectionOfficer::where('user_id', $user->id)
                ->where('election_id', $election->id)
                ->whereIn('role', $rules['allowed_roles'])
                ->where('status', 'active')
                ->exists();
            if (!$hasRole) {
                throw new InvalidTransitionException(
                    "User lacks required role for '{$action}'."
                );
            }
        }

        // Check 4: Preconditions met
        foreach ($rules['preconditions'] as $precondition) {
            if (!self::checkPrecondition($precondition, $election)) {
                throw new InvalidTransitionException(
                    "Precondition '{$precondition}' not satisfied for '{$action}'."
                );
            }
        }
    }
}
```

### Exception: InvalidTransitionException

```php
throw new InvalidTransitionException(
    "Cannot transition from 'draft' to 'voting'. Valid transitions: [submit_for_approval]"
);
```

This is a **DomainException** subclass — messages are user-facing.

---

## Dual-Axis Model: Lifecycle + Operational Overlay

A key architectural distinction: **suspension is NOT a lifecycle state**. It's an operational governance overlay.

### The Two Axes

```
LIFECYCLE AXIS (constitutional progression)
──────────────────────────────────────────
Draft → Submitted → Approved → Setup → Ready → Voting → Counting → Results → Archived
                                                                              
OPERATIONAL AXIS (governance intervention)                                    
──────────────────────────────────────────                                     
Normal Operation ←──────────────────────────→ Suspended (freeze)              
  (all capabilities available)                 (only resume allowed)          
```

### Why This Matters

| Aspect | Lifecycle State (e.g., VotingActive) | Operational Overlay (Suspended) |
|--------|--------------------------------------|----------------------------------|
| **Progression** | Moves forward linearly | Not part of progression |
| **State derivation** | Derived from business facts | Derived from `suspended_at` column (checked FIRST) |
| **Capability impact** | Defines what actions are available | Short-circuits ALL capabilities except resume |
| **Recovery** | Cannot go backward | Can resume to pre-suspension state |
| **Architecture pattern** | Constitutional state machine | Governance intervention |

### Suspension Flow

```
NORMAL OPERATION
  │
  ├── Chief clicks "Suspend Election"
  │   ↓
  ├── Governance modal: reason required, category optional
  │   ↓
  ├── POST /elections/{election}/suspend
  │   ↓
  ├── ElectionManagementController::suspend()
  │   ↓
  ├── SuspendElection gate (chief only — NOT manageSettings)
  │   ↓
  ├── transitionTo(Transition::manual('suspend', ...))
  │   ↓
  ├── ConstitutionalTransitionGuard::assertAllowed()
  │   ├── Check 1: 'suspend' in RULES?                    ✅ YES
  │   ├── Check 2: state allows?                           ✅ YES
  │   ├── Check 3: user has chief role?                   ✅ YES
  │   └── Check 4: preconditions met?                      ✅ YES (none)
  │   ↓
  ├── Election::applySideEffectsForSuspend()
  │   ├── suspended_at = now
  │   ├── suspended_by = auth()->id()
  │   ├── suspended_reason = reason
  │   ├── suspension_category = category
  │   └── suspended_lifecycle_context = pre-suspension state
  │   ↓
  ├── ElectionStateChangedEvent dispatched
  │   ↓
  ├── Engine re-derives state: Suspended (checked FIRST)
  │   ↓
  └── OverlayCapabilityPolicy activates:
      ALL capabilities EXCEPT resume → DENIED

SUSPENDED
  │
  ├── Chief clicks "Resume Election" (in suspension banner)
  │   ↓
  ├── POST /elections/{election}/resume
  │   ↓
  ├── ElectionManagementController::resume()
  │   ↓
  ├── Election::applySideEffectsForResume()
  │   ├── suspended_at = null (clears all suspension flags)
  │   ├── resumed_at = now
  │   ├── resumed_by = auth()->id()
  │   └── state = pre-suspension state (from suspended_lifecycle_context)
  │   ↓
  └── Engine re-derives state: returns to pre-suspension lifecycle state

NORMAL OPERATION RESUMED
```

### State Derivation Priority (Suspension Checked FIRST)

```php
public function getState(Election $election): ElectionLifecycleState
{
    // Layer 0: Suspended (operational overlay — checked FIRST)
    if ($election->suspended_at !== null) {
        return ElectionLifecycleState::Suspended;
    }

    // Layer 1: Archived (terminal)
    // Layer 2: ResultsPublished
    // Layer 3: Counting (time-based)
    // Layer 4: VotingActive (temporal window)
    // Layer 5: ReadyForVoting
    // Layer 6: SetupNomination
    // Layer 7: SetupAdministration
    // Layer 8: Approved
    // Layer 9: SubmittedForApproval
    // Layer 10: Rejected
    // Layer 11: Draft (default)
}
```

---

## 4-Layer Capability Resolution

Capabilities are resolved through a **policy composition pipeline** that evaluates 4 layers in priority order.

### Location

`app/Application/Election/Services/ElectionCapabilityResolver.php`

### The Policy Layers

| Priority | Layer | Class | Behavior |
|----------|-------|-------|----------|
| 1 | **Overlay** | `OverlayCapabilityPolicy` | If suspended: deny all except resume. Otherwise: abstain |
| 2 | **Lifecycle** | `LifecycleCapabilityBaselinePolicy` | If state matches allowed_states: grant. Otherwise: deny |
| 3 | **Preconditions** | `PreconditionCapabilityPolicy` | If all preconditions met: grant. Otherwise: deny |
| 4 | **Authorization** | `AuthorizationPolicy` | If user has required role: grant. Otherwise: deny |

### Evaluation Flow

```
CapabilityContext
├── election: Election
├── user: User
├── action: string (e.g., 'open_voting')
├── actionMetadata: array (from ElectionConstitution::RULES)
└── state: ElectionLifecycleState

CapabilityResolver::evaluate(context)
  │
  ├── Sort policies by priority (1 → 4)
  │
  ├── Evaluate each policy in order:
  │
  │   Policy 1: OverlayCapabilityPolicy
  │   ├── If election is suspended AND action ≠ resume:
  │   │   → RETURN Decision::deny(Suspended)
  │   └── Otherwise:
  │       → RETURN Decision::abstain()
  │
  │   Policy 2: LifecycleCapabilityBaselinePolicy
  │   ├── If state in allowed_states:
  │   │   → RETURN Decision::grant()
  │   └── Otherwise:
  │       → RETURN Decision::deny(InvalidLifecycle)
  │
  │   Policy 3: PreconditionCapabilityPolicy
  │   ├── Preconditions empty:
  │   │   → RETURN Decision::abstain()
  │   ├── All preconditions met:
  │   │   → RETURN Decision::grant()
  │   └── Some preconditions fail:
  │       → RETURN Decision::deny(PreconditionFailed, detail)
  │
  │   Policy 4: AuthorizationPolicy
  │   ├── User has required role:
  │   │   → RETURN Decision::grant()
  │   └── User lacks required role:
  │       → RETURN Decision::deny(Unauthorized)
  │
  └── First denial (including short-circuit) is FINAL
      First grant returns immediately
      If all abstain → default grant
```

### Decision Model

```php
class CapabilityDecision
{
    public function allows(): bool;     // True if granted
    public bool $isGrant;               // Explicit grant
    public bool $isDenial;              // Explicit denial
    public bool $isAbstention;          // No opinion (pass to next policy)
    
    public ?CapabilityDenialReason $reason;  // Why denied
    public ?string $detail;                   // Human-readable detail
}

enum CapabilityDenialReason: string
{
    case Suspended = 'suspended';
    case InvalidLifecycle = 'invalid_lifecycle';
    case PreconditionFailed = 'precondition_failed';
    case Unauthorized = 'unauthorized';
}
```

### Policy Contract

Every policy implements:

```php
interface CapabilityPolicyLayer
{
    public function evaluate(CapabilityContext $context): CapabilityDecision;
    
    public function priority(): int;  // 1-4
}
```

### Frontend Payload

Capabilities are serialized to the frontend as:

```json
{
  "capabilities": {
    "submit_for_approval": { "allowed": true, "denial_reason": null, "denial_detail": null },
    "open_voting": { "allowed": false, "denial_reason": "precondition_failed", "denial_detail": "has_approved_candidates" },
    "suspend": { "allowed": true, "denial_reason": null, "denial_detail": null },
    "resume": { "allowed": false, "denial_reason": "suspended", "denial_detail": null }
  }
}
```

---

## State Derivation Engine (SSOT)

The state derivation engine (`ElectionLifecycleEngineImpl`) computes the current state from business facts in priority order.

### Documented Separately

The SSOT engine is comprehensively documented in:
- **[03-ssot-engine-reference.md](./03-ssot-engine-reference.md)** — Complete derivation logic, fact tables, priority order
- **[API_REFERENCE.md](./API_REFERENCE.md)** — Full ElectionLifecycle facade API

### Key Addition: Suspension Check

The 03 reference was written before the suspension system. The suspension check is now **Layer 0** — checked before everything else:

```php
public function getState(Election $election): ElectionLifecycleState
{
    // Layer 0: Suspended (operational overlay — checked FIRST)
    if ($election->suspended_at !== null) {
        return ElectionLifecycleState::Suspended;
    }
    // ... rest of derivation logic unchanged
}
```

---

## Controlled Write Barrier

The write barrier (`ElectionStateWriteContext`) controls direct mutations to the `state` column.

### Documented Separately

Comprehensively documented in **[04-controlled-write-barrier.md](./04-controlled-write-barrier.md)**.

### Key Points

- `ElectionStateWriteContext::authorize()` wraps state column writes
- `Election::setStateAttribute()` mutator checks `isAuthorized()` before writes
- Three levels of enforcement: Metrics (Level 1), Strict (Level 4)
- All violations recorded to `constitutional_integrity` log channel
- The `transitionTo()` method automatically uses the write barrier

---

## ElectionAction Enum

The `ElectionAction` enum defines all constitutional actions.

### Location

`app/Domain/Election/Enum/ElectionAction.php`

### Cases

```php
enum ElectionAction: string
{
    case SubmitForApproval = 'submit_for_approval';
    case AutoSubmit = 'auto_submit';
    case Approve = 'approve';
    case Reject = 'reject';
    case ReviseAndResubmit = 'revise_and_resubmit';
    case BeginSetup = 'begin_setup';
    case CompleteAdministration = 'complete_administration';
    case CompleteNomination = 'complete_nomination';
    case OpenVoting = 'open_voting';
    case CloseVoting = 'close_voting';
    case PublishResults = 'publish_results';
    case Archive = 'archive';
    case Suspend = 'suspend';
    case Resume = 'resume';
}
```

### Frontend Isomorphism

The frontend mirrors these actions in `ElectionActions.ts`:

```typescript
export const ELECTION_ACTIONS = {
    SUBMIT_FOR_APPROVAL: 'submit_for_approval',
    APPROVE: 'approve',
    REJECT: 'reject',
    AUTO_SUBMIT: 'auto_submit',
    BEGIN_SETUP: 'begin_setup',
    REVISE_AND_RESUBMIT: 'revise_and_resubmit',
    COMPLETE_ADMINISTRATION: 'complete_administration',
    COMPLETE_NOMINATION: 'complete_nomination',
    OPEN_VOTING: 'open_voting',
    CLOSE_VOTING: 'close_voting',
    PUBLISH_RESULTS: 'publish_results',
    ARCHIVE: 'archive',
    SUSPEND: 'suspend',
    RESUME: 'resume',
} as const;
```

---

## ElectionLifecycleState Enum

### Location

`app/Domain/Election/Enum/ElectionLifecycleState.php`

### Full Enum

```php
enum ElectionLifecycleState: string
{
    case Draft = 'draft';
    case SubmittedForApproval = 'submitted_for_approval';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case SetupAdministration = 'setup_administration';
    case SetupNomination = 'setup_nomination';
    case ReadyForVoting = 'ready_for_voting';
    case VotingActive = 'voting_active';
    case Counting = 'counting';
    case ResultsPublished = 'results_published';
    case Archived = 'archived';
    case Suspended = 'suspended';
}
```

### Helper Methods

```php
// Human-readable label for UI display
$state->label();
// Draft → "Draft Setup"
// VotingActive → "Voting Active"
// Suspended → "Suspended"

// Terminal state check
$state->isTerminal();  // true for Archived only

// Awaiting approval
$state->isAwaitingApproval();  // true for SubmittedForApproval

// Setup phase
$state->isInSetup();  // true for SetupAdministration, SetupNomination

// Voting phase
$state->isVotingPhase();  // true for ReadyForVoting, VotingActive

// Active check (any non-terminal state that allows operations)
$state->isActive();

// Suspended check
$state->isSuspended();
```

---

## Controller Patterns

### Transition via transitionTo()

The recommended pattern uses `$election->transitionTo()` which automatically handles all 4 guard checks + side effects + events:

```php
public function closeVoting(Election $election): RedirectResponse
{
    $this->authorize('manageSettings', $election);

    try {
        $election->transitionTo(
            Transition::manual(
                action: 'close_voting',
                actorId: auth()->id(),
                reason: 'Manual close by election officer',
                metadata: ['ip' => request()->ip()]
            )
        );
        return back()->with('success', 'Voting closed.');
    } catch (InvalidTransitionException $e) {
        return back()->with('error', $e->getMessage());
    } catch (DomainException $e) {
        return back()->with('error', $e->getMessage());
    }
}
```

### Transition Object

```php
Transition::manual(
    action: 'open_voting',      // Constitutional action name
    actorId: auth()->id(),       // Who performed the action
    reason: 'string',            // Why (recorded in audit log)
    metadata: [                  // Optional context
        'ip' => request()->ip(),
        'suspension_category' => 'general',
    ]
);
```

### Suspend (Governance Overlay)

```php
public function suspend(Election $election): RedirectResponse
{
    // Uses suspendElection gate (chief only), NOT manageSettings
    $this->authorize('suspendElection', $election);

    // Guard against double-suspension
    if ($election->suspended_at !== null) {
        return back()->with('error', __('This election is already suspended.'));
    }

    // Reason is REQUIRED (min 10 chars)
    $validated = request()->validate([
        'reason' => ['required', 'string', 'min:10', 'max:1000'],
        'suspension_category' => [
            'nullable', 'string',
            Rule::in(Election::SUSPENSION_CATEGORIES)
        ],
    ]);

    // Uses ElectionStateWriteContext for state column write
    ElectionStateWriteContext::authorize(function () use ($election, $validated) {
        $snapshot = ElectionLifecycle::of($election)->snapshot();
        $election->suspended_at = now();
        $election->suspended_by = auth()->id();
        $election->suspended_reason = $validated['reason'];
        $election->suspension_category = $validated['suspension_category'] ?? 'general';
        $election->suspended_lifecycle_context = $snapshot->state->value;
        $election->state = 'suspended';
        $election->save();
    });

    // Record governance audit trail
    ElectionAuditLog::record(
        $election, 'suspend', null,
        ['reason' => $validated['reason'],
         'suspension_category' => $validated['suspension_category'] ?? 'general'],
        request()->user(), request()
    );

    return back()->with('success', __('Election suspended.'));
}
```

### Resume (Remove Governance Overlay)

```php
public function resume(Election $election): RedirectResponse
{
    $this->authorize('suspendElection', $election);

    ElectionStateWriteContext::authorize(function () use ($election) {
        $election->suspended_at = null;
        $election->suspended_by = null;
        $election->suspended_reason = null;
        $election->suspension_category = null;
        $election->resumed_at = now();
        $election->resumed_by = auth()->id();

        // Restore pre-suspension lifecycle state
        $snapshot = ElectionLifecycle::of($election)->snapshot();
        $election->state = $snapshot->state->value;
        $election->suspended_lifecycle_context = null;

        $election->save();
    });

    return back()->with('success', 'Election resumed.');
}
```

### Publish Results (With Integrity Verification)

```php
public function publish(Election $election): RedirectResponse
{
    $this->authorize('publishResults', $election);

    // Verify vote-result integrity before publishing
    foreach ($election->votes as $vote) {
        $integrity = $vote->verifyResultsIntegrity();
        if (!$integrity['is_valid']) {
            $vote->syncResults();  // Auto-correct drift
        }
    }

    try {
        $election->transitionTo(
            Transition::manual(
                action: 'publish_results',
                actorId: auth()->id(),
                reason: 'Results published by election officer',
                metadata: ['ip' => request()->ip()]
            )
        );
        return back()->with('success', 'Results published.');
    } catch (InvalidTransitionException | DomainException $e) {
        return back()->with('error', $e->getMessage());
    }
}
```

### Controller Pattern Summary

| Method | Action | Gate | Route | Validation |
|--------|--------|------|-------|-----------|
| `activate()` | begin_setup | manageSettings | POST /elections/{election}/activate | — |
| `submitForApproval()` | submit_for_approval | manageSettings | POST /elections/{election}/submit-for-approval | — |
| `completeAdministration()` | complete_administration | manageSettings | POST /.../complete-administration | reason required |
| `completeNomination()` | complete_nomination | manageSettings | POST /.../complete-nomination | reason required |
| `openVoting()` | open_voting | manageSettings | POST /elections/{election}/open-voting | — |
| `closeVoting()` | close_voting | manageSettings | POST /elections/{election}/close-voting | — |
| `publish()` | publish_results | publishResults | POST /elections/{election}/publish | — |
| `unpublish()` | — | publishResults | POST /elections/{election}/unpublish | — |
| `suspend()` | suspend | **suspendElection** | POST /elections/{election}/suspend | reason required, min:10 |
| `resume()` | resume | **suspendElection** | POST /elections/{election}/resume | — |

### Route Structure

```php
// All management routes are under:
Route::prefix('elections/{election:slug}')
    ->middleware(['auth', 'verified', 'tenant', 'can:view,election'])
    ->group(function () {

    Route::post('/activate', [ElectionManagementController::class, 'activate'])
        ->name('elections.activate')
        ->can('manageSettings', 'election');

    Route::post('/submit-for-approval', [ElectionManagementController::class, 'submitForApproval'])
        ->name('elections.submit-for-approval')
        ->can('manageSettings', 'election');

    Route::post('/open-voting', [ElectionManagementController::class, 'openVoting'])
        ->name('elections.open-voting')
        ->can('manageSettings', 'election');

    Route::post('/close-voting', [ElectionManagementController::class, 'closeVoting'])
        ->name('elections.close-voting')
        ->can('manageSettings', 'election');

    Route::post('/publish', [ElectionManagementController::class, 'publish'])
        ->name('elections.publish')
        ->can('publishResults', 'election');

    // Governance overlay routes — suspendElection gate, NOT manageSettings
    Route::post('/suspend', [ElectionManagementController::class, 'suspend'])
        ->name('elections.suspend')
        ->can('suspendElection', 'election');

    Route::post('/resume', [ElectionManagementController::class, 'resume'])
        ->name('elections.resume')
        ->can('suspendElection', 'election');
});
```

### Middleware Order

The bootstrap configuration sets middleware priority:

```
StartSession → TenantContext → SubstituteBindings
```

This means `auth`, `verified`, `tenant`, and `can` middleware run **after** route model binding. The `election:slug` binding in `resolveRouteBinding()` bypasses global scopes to find elections by slug.

---

## Frontend Integration

### Key Files

| File | Purpose |
|------|---------|
| `ElectionActions.ts` | Action constants (mirrors PHP ElectionAction enum) |
| `ElectionLifecycleStates.ts` | State constants including SUSPENDED |
| `useElectionCapabilities.ts` | Composable: exposes `canSuspend`, `canResume`, etc. |
| `ElectionPhaseService.ts` | State-to-phase mapping for UI display |
| `Management.vue` | Main management page with action zone |
| `StateMachinePanel.vue` | Legacy panel (being deprecated) |
| `StateBadge.vue` | State badge component |
| `Show.vue` | Public election view page |
| `Viewboard.vue` | Read-only view for commissioners |

### Capabilities Composable

The composable wraps `stateMachine.capabilities[action].allowed` into named computed refs:

```typescript
const {
    canSubmitForApproval, canApprove, canReject,
    canBeginSetup, canCompleteAdministration,
    canCompleteNomination, canOpenVoting, canCloseVoting,
    canPublishResults, canArchive,
    canSuspend,           // Governance overlay
    canResume,            // Governance overlay
    openVotingBlockedReason,
    isOpenVotingDisabled,
} = useElectionCapabilities();
```

### Suspension Banner

```html
<!-- In Management.vue — shown when Suspended -->
<div v-if="currentState === 'suspended'" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg mb-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-red-800 font-semibold">Election Suspended</p>
            <p class="text-red-600 text-sm mt-1">Reason: {{ election.suspended_reason }}</p>
        </div>
        <ActionButton v-if="canResume" variant="success" @click="handleResume">
            Resume Election
        </ActionButton>
    </div>
</div>
```

### Governance Modal (Suspend)

```html
<DialogModal :show="showSuspendModal" @close="showSuspendModal = false">
    <template #title>Suspend Election</template>
    <template #content>
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded">
            Governance Warning: This freezes all operations except resume.
        </div>
        <textarea v-model="suspendReason" rows="4" placeholder="Reason for suspension..."
            data-testid="suspend-reason" />
        <select v-model="suspendCategory" data-testid="suspend-category">
            <option value="general">General</option>
            <option value="misconduct">Misconduct</option>
            <option value="emergency">Emergency</option>
            <option value="investigation">Investigation</option>
            <option value="other">Other</option>
        </select>
    </template>
    <template #footer>
        <button @click="showSuspendModal = false">Cancel</button>
        <ActionButton variant="danger" :disabled="suspendReason.length < 10"
            data-testid="suspend-confirm" @click="handleSuspendConfirm">
            Suspend Election
        </ActionButton>
    </template>
</DialogModal>
```

---

## Testing Strategy

### Test Files

| File | Tests | Coverage |
|------|-------|----------|
| `tests/Feature/Election/ElectionSuspensionTest.php` | 26 | Suspend/resume HTTP flow, auth, audit, overlay |
| `tests/Feature/Election/ElectionActivationTest.php` | — | Activation flow |
| `tests/Feature/Election/VotingButtonsStateMachineTest.php` | — | Voting button visibility |
| `tests/Unit/Domain/Election/ElectionConstitutionTargetStateTest.php` | — | Constitution target state |
| `tests/Unit/Domain/Election/ElectionLifecycleEngineTest.php` | — | State derivation |
| `tests/Unit/Domain/Election/ElectionLifecycleSnapshotTest.php` | — | Snapshot value object |
| `tests/Unit/Domain/Election/ElectionLifecycleStateTest.php` | — | State enum |
| `tests/Unit/Application/Election/ConstitutionalTransitionGuardTest.php` | — | Guard 4-check enforcement |
| `tests/Unit/Application/Election/ConstitutionalTransitionGuardPreconditionsTest.php` | — | Precondition checks |
| `tests/Architecture/ElectionStateMachineConsistencyTest.php` | 12+ | Architecture invariants |
| `tests/js/Pages/Election/Management.spec.ts` | 4 | Frontend suspend UI |

### Key Test Patterns

**HTTP flow test:**
```php
public function test_suspend_sets_suspended_at()
{
    [$election, $chief] = $this->createVotingActiveWithChief();

    $this->actingAs($chief->user)
        ->from(route('elections.management', $election->slug))
        ->post(route('elections.suspend', $election->slug), [
            'reason' => 'Governance intervention: investigation required',
            'suspension_category' => 'investigation',
        ])
        ->assertRedirect();

    $this->assertNotNull($election->fresh()->suspended_at);
}
```

**Architecture invariant test:**
```php
public function test_suspend_action_exists_in_constitution()
{
    $this->assertArrayHasKey('suspend', ElectionConstitution::RULES);
}

public function test_suspend_enum_case_exists()
{
    $this->assertContains('suspend', array_map(fn($c) => $c->value, ElectionAction::cases()));
}
```

### Running Tests

```bash
# Backend tests
php artisan test --env=testing tests/Feature/Election/ElectionSuspensionTest.php

# Architecture invariant tests
php artisan test --env=testing tests/Architecture/ElectionStateMachineConsistencyTest.php

# Frontend tests
npx vitest run tests/js/Pages/Election/Management.spec.ts

# Full regression
php artisan test --env=testing
```

---

## Key File Reference

| File | Purpose |
|------|---------|
| `app/Domain/Election/Constitution/ElectionConstitution.php` | Constitutional rules registry |
| `app/Domain/Election/Enum/ElectionAction.php` | Constitutional action enum (11 cases) |
| `app/Domain/Election/Enum/ElectionLifecycleState.php` | Lifecycle state enum (12 cases) |
| `app/Domain/Election/ValueObjects/ElectionLifecycleSnapshot.php` | Immutable state snapshot |
| `app/Domain/Election/Projection/ElectionLifecycleProjection.php` | Lifecycle projection |
| `app/Application/Election/Services/ElectionLifecycleEngineImpl.php` | State derivation engine (12-step) |
| `app/Application/Election/Services/ConstitutionalTransitionGuard.php` | 4-check transition guard |
| `app/Application/Election/Services/ElectionCapabilityResolver.php` | 4-layer policy composition |
| `app/Application/Election/Capabilities/Policy/OverlayCapabilityPolicy.php` | Suspension overlay (priority 1) |
| `app/Application/Election/Capabilities/Policy/LifecycleCapabilityBaselinePolicy.php` | Lifecycle baseline (priority 2) |
| `app/Application/Election/Capabilities/Policy/PreconditionCapabilityPolicy.php` | Precondition checks (priority 3) |
| `app/Application/Election/Capabilities/Policy/AuthorizationPolicy.php` | Authorization checks (priority 4) |
| `app/Application/Election/Governance/ElectionStateWriteContext.php` | Write barrier authority |
| `app/Application/Election/Facades/ElectionLifecycle.php` | Public facade |
| `app/Application/Election/Exceptions/InvalidTransitionException.php` | Transition exception |
| `app/Policies/ElectionPolicy.php` | Laravel authorization gates |
| `app/Models/Election.php` | Election model (side effects, mutator, casts) |
| `routes/election/electionRoutes.php` | Election management routes |
| `app/Http/Controllers/Election/ElectionManagementController.php` | Management controller |
| `resources/js/Composables/useElectionCapabilities.ts` | Frontend capability composable |
| `resources/js/Constants/ElectionActions.ts` | Frontend action constants |
| `resources/js/Constants/ElectionLifecycleStates.ts` | Frontend state constants |
| `resources/js/Pages/Election/Management.vue` | Management page |
| `tests/Support/ElectionScenarioFactory.php` | Test scenario factory |
| `tests/Feature/Election/ElectionSuspensionTest.php` | Suspension feature tests |
| `tests/Architecture/ElectionStateMachineConsistencyTest.php` | Architecture invariant tests |

---

## Related Documentation

| Document | Content |
|----------|---------|
| **[README.md](./README.md)** | Phase 3 SSOT migration overview |
| **[01-state-machine-overview.md](./01-state-machine-overview.md)** | State machine architecture (10-state version) |
| **[02-capacity-based-approval.md](./02-capacity-based-approval.md)** | Capacity-based approval rules |
| **[03-ssot-engine-reference.md](./03-ssot-engine-reference.md)** | SSOT engine reference (fact derivation) |
| **[04-controlled-write-barrier.md](./04-controlled-write-barrier.md)** | Write barrier implementation |
| **[API_REFERENCE.md](./API_REFERENCE.md)** | ElectionLifecycle facade API |
| **[PATTERNS.md](./PATTERNS.md)** | Code patterns and migration examples |
| **[USER_GUIDE.md](./USER_GUIDE.md)** | User-facing documentation |
| **[testing.md](./testing.md)** | Testing guide |
| **[TROUBLESHOOTING.md](./TROUBLESHOOTING.md)** | Troubleshooting |
