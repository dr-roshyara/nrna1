# Election SSOT Architecture — Quick Reference

**Last Updated:** 2026-05-18  
**Status:** Ready for Phase 1 Implementation  
**Scope:** Complete Single Source of Truth + Constitutional Guard

---

## 🎯 The Problem We're Solving

```
Current System: 3 Competing Lifecycles
├─ state = 'draft'
├─ status = 'active'
└─ is_active = true

Result: Election can be in multiple conflicting states simultaneously
→ Non-deterministic behavior
→ Voting engine confused
→ UI shows wrong information
```

---

## ✅ The Solution: SSOT + Guard Architecture

```
SSOT (Single Source of Truth)
├─ Reads: ElectionLifecycleEngine
│  └─ Computes: ElectionLifecycleSnapshot
│     └─ Answers: "What is the current state?"
│
Guard (Constitutional Enforcement)
├─ Writes: TransitionGuard
│  └─ Enforces: ConstitutionalRules
│     └─ Answers: "What transitions are allowed?"
│
Result: One truth, enforceable rules, consistent system
```

---

## 📚 Documentation Files

| File | Purpose | Read When |
|------|---------|-----------|
| **SSOT_Architecture_Analysis.md** | Complete design with code examples | Need deep understanding |
| **SSOT-Refactoring-Plan.md** | 7-phase rollout plan with timeline | Planning implementation |
| **Constitutional_Transition_Guard_Layer.md** | Guard layer enforcement details | Understanding write rules |
| **IMPLEMENT_SSOT_PLAN.md** | Phase 1 step-by-step instructions | Ready to code |

---

## 🧩 Core Components (Phase 1)

### 1. ElectionLifecycleState (Enum)

```php
enum ElectionLifecycleState: string {
    case Draft = 'draft';
    case Setup = 'setup';
    case ReadyForVoting = 'ready_for_voting';
    case VotingActive = 'voting_active';
    case Counting = 'counting';
    case ResultsPublished = 'results_published';
    case Archived = 'archived';
}
```

**Purpose:** Single enum for all lifecycle states (replaces `status` + `is_active`)

---

### 2. ElectionLifecycleSnapshot (DTO)

```php
final class ElectionLifecycleSnapshot {
    public readonly ElectionLifecycleState $state;
    public readonly bool $canEdit;
    public readonly bool $canVote;
    public readonly bool $canManageVoters;
    public readonly bool $canPublishResults;
    public readonly bool $isLocked;
    public readonly ?string $blockedReason;
    public readonly array $allowedActions;
}
```

**Purpose:** Immutable read model answered by engine

**Usage:** 
```php
$snapshot = $engine->compute($election);
// Use snapshot for ALL UI/logic decisions
```

---

### 3. ElectionLifecycleEngine (Service)

```php
interface ElectionLifecycleEngine {
    public function compute(Election $election): ElectionLifecycleSnapshot;
    public function getState(Election $election): ElectionLifecycleState;
}
```

**Purpose:** Single source of truth

**State Derivation Logic:**
1. Terminal states (results published)
2. Time windows (voting active now)
3. Counting (voting ended)
4. Setup complete (ready for voting)
5. Default to Setup

---

### 4. ElectionConstitution (Rules Registry)

```php
final class ElectionConstitution {
    public const RULES = [
        'open_voting' => [
            'allowed_states' => ['ready_for_voting'],
            'requires' => [
                'nomination_completed',
                'voting_window_defined',
                'has_approved_candidates',
            ],
        ],
        // ... other transitions
    ];
}
```

**Purpose:** Centralized definition of all constitutional rules

---

### 5. TransitionGuard (Enforcement)

```php
interface TransitionGuard {
    public function assertAllowed(
        Election $election,
        string $action,
        ElectionLifecycleSnapshot $snapshot
    ): void;
}
```

**Purpose:** Mandatory gate before any state change

**Checks:**
1. Action is defined
2. Current state allows action
3. All prerequisites met
4. Snapshot agrees

---

## 🔄 Flow Example: Opening Voting

```
User clicks "Open Voting"
    ↓
Controller calls:
  $snapshot = $engine->compute($election);
  $guard->assertAllowed($election, 'open_voting', $snapshot);
    ↓
Guard checks:
  ✓ state = 'ready_for_voting' (allowed)
  ✓ nomination_completed = true (required)
  ✓ voting_window_defined = true (required)
  ✓ has_approved_candidates = true (required)
    ↓
All checks pass → Mutation allowed
    ↓
$election->transitionTo('open_voting');
$election->save();
    ↓
✅ Voting opened
```

---

## 🚨 Blocked Flow Example

```
User tries to open voting in SETUP state
    ↓
$guard->assertAllowed($election, 'open_voting', $snapshot);
    ↓
Guard checks:
  ✗ state = 'setup' (NOT in allowed_states)
    ↓
🛑 InvalidTransitionException thrown:
   "Action 'open_voting' not allowed in state 'setup'.
    Allowed states: ready_for_voting"
    ↓
No mutation happens
System remains consistent
```

---

## 📊 Database (Unchanged in Phase 1)

Existing columns keep being used:
- `state` — state machine value
- `voting_starts_at`, `voting_ends_at` — time windows
- `administration_completed` — boolean
- `nomination_completed` — boolean
- `results_published_at` — timestamp
- `status` — DEPRECATED (removed Phase 4)
- `is_active` — DEPRECATED (removed Phase 4)

---

## 🧪 Testing Strategy

### Unit Tests
```
tests/Unit/Domain/Election/
├── ElectionLifecycleStateTest.php
├── ElectionLifecycleSnapshotTest.php
├── ElectionConstitutionTest.php
└── ElectionLifecycleEngineImplTest.php

tests/Unit/Application/Election/
└── ConstitutionalTransitionGuardTest.php
```

### Integration Tests
```
tests/Feature/Election/
├── StateMachine/CurrentBehaviorTest.php (Phase 0 — baseline)
└── ConstitutionalGuardIntegrationTest.php
```

---

## 🔍 Key Principles

1. **SSOT = Read Truth**
   - Engine computes current state from signals
   - Always returns same result for same input
   - Used everywhere for decisions

2. **Guard = Write Law**
   - Constitution defines what's allowed
   - Guard enforces rules
   - Prevents illegal transitions

3. **No Direct Field Access**
   ```php
   // ❌ DON'T:
   if ($election->is_active) { ... }
   if ($election->status === 'active') { ... }
   
   // ✅ DO:
   $snapshot = $engine->compute($election);
   if ($snapshot->isActive()) { ... }
   ```

4. **Guard is Mandatory**
   ```php
   // ❌ DON'T:
   $election->state = 'voting';
   $election->save();
   
   // ✅ DO:
   $guard->assertAllowed($election, 'open_voting', $snapshot);
   $election->transitionTo('open_voting');
   ```

---

## 📋 Phase 1 Checklist

- [ ] Read SSOT_Architecture_Analysis.md
- [ ] Read Constitutional_Transition_Guard_Layer.md
- [ ] Read IMPLEMENT_SSOT_PLAN.md
- [ ] Create ElectionLifecycleState enum
- [ ] Create ElectionLifecycleSnapshot DTO
- [ ] Create ElectionLifecycleEngine interface + impl
- [ ] Create ElectionConstitution rules registry
- [ ] Create TransitionGuard interface + impl
- [ ] Add unit tests (all GREEN)
- [ ] Register services in AppServiceProvider
- [ ] Add inResultsState() factory method
- [ ] Run Phase 0 tests (must stay GREEN)
- [ ] Run full test suite (zero regressions)
- [ ] Commit with clear message

---

## 🚀 What Happens After Phase 1

| Phase | What | Duration |
|-------|------|----------|
| 1 | Introduce SSOT + Guard | 4h |
| 2 | Mark legacy columns deprecated | 1h |
| 3 | Migrate all consumers to use Engine | 4h |
| 4 | Remove legacy columns | 1h |
| 5 | Demote state machine | 1h |
| 6 | Add timezone support | 3h |
| 7 | Add verification system | 2h |

**Total: ~20 hours (safe, zero downtime)**

---

## 💡 Why This Architecture is Different

### Old System (Broken)
- Multiple sources claim authority
- Queries return different answers
- Inconsistent UI state
- Race conditions possible

### New System (Fixed)
- One engine computes truth
- One guard enforces rules
- Snapshot immutable
- Impossible to have illegal state

---

## 🎯 The Outcome

```
BEFORE:
  is_active = true
  status = 'active'
  state = 'draft'
  → Confused system

AFTER:
  ElectionLifecycleSnapshot {
    state: Draft,
    canVote: false,
    canEdit: true,
    blockedReason: "Setup not started"
  }
  → Clear, deterministic, enforced
```

---

## ⚡ Quick Commands

```bash
# Run Phase 0 tests (baseline)
php artisan test tests/Feature/Election/StateMachine/CurrentBehaviorTest.php

# Run Phase 1 tests (implementation)
php artisan test tests/Unit/Domain/Election/
php artisan test tests/Unit/Application/Election/

# Full suite (zero regressions)
php artisan test --no-coverage
```

---

## 📞 Questions?

- **"How is state computed?"** → See SSOT_Architecture_Analysis.md section 1.3
- **"What are constitutional rules?"** → See Constitutional_Transition_Guard_Layer.md
- **"How do I implement Phase 1?"** → See IMPLEMENT_SSOT_PLAN.md
- **"Why remove is_active?"** → Because Engine computes everything better

---

**Status:** Ready for Phase 1 Implementation  
**Next Step:** Follow IMPLEMENT_SSOT_PLAN.md

---

## 📚 Reading Order (Recommended)

1. **This file** (5 min) — Get the overview
2. **SSOT_Architecture_Analysis.md** (20 min) — Understand the design
3. **Constitutional_Transition_Guard_Layer.md** (15 min) — Understand enforcement
4. **IMPLEMENT_SSOT_PLAN.md** (10 min) — Start coding

---

**Created:** 2026-05-18  
**Architecture Type:** DDD + SSOT + Constitutional Guard  
**Production Ready:** Yes (after Phase 1 complete)
