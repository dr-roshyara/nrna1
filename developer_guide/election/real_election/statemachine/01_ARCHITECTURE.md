# Architecture Guide

## System Design

The Election State Machine uses **Domain-Driven Design (DDD)** patterns with three layers:

1. **Domain Layer** - ElectionStateMachine (validation only)
2. **Application Layer** - Election::transitionTo() bridge (orchestration)
3. **Presentation Layer** - Controllers + Frontend (UI)

---

## Core Components

### 1. Election::transitionTo() Bridge Method
- Location: app/Models/Election.php lines 1064-1072
- Acquires cache lock (30s TTL)
- Uses DB transaction for atomicity
- Captures original flags for rollback
- Fires ElectionStateChangedEvent

### 2. Helper Methods
- applyVotingTransition() - Sets voting dates, locks voting
- applyResultsPendingTransition() - Ends voting period

### 3. Controllers
- openVoting() - Validates nomination state
- closeVoting() - Validates voting state + double-lock guard

### 4. Frontend
- Management.vue uses current_state for button visibility
- canOpenVoting computed property
- canCloseVoting computed property

---

## State Diagram

administration → nomination → voting → results_pending → results

Each state is derived from database flags:
- administration_completed
- nomination_completed  
- voting_starts_at, voting_ends_at
- results_published_at

---

## Cache Lock Mechanism

Prevents concurrent transitions:

```
Request 1: Click "Open Voting"
├─ Acquire lock (key: "election_transition:{id}")
├─ Hold for 30 seconds
└─ Release when done

Request 2: Double-click (100ms later)
├─ Try to acquire same lock
├─ BLOCKED: Lock already held
└─ Exception: "Another transition in progress"
```

---

## Transaction Rollback

If ANY step fails:
1. Event fire fails
2. Flag updates fail
3. Audit record fails

→ Entire transaction rolled back to original state
→ No partial updates left behind

---

## Validation

### Domain Level (ElectionStateMachine)
- Valid transitions: only allowed paths
- Invalid: voting → nomination raises exception

### Application Level (Controllers)
- State validation: must be in correct phase
- Double-lock guard: prevent closing already-ended voting

---

## Test Coverage

- 2 model tests: transitionTo() core behavior
- 8 controller tests: state validation, error handling, double-lock
- 25 regression tests: existing state machine tests

Total: 35 tests passing ✅

---

## Files Modified

- app/Models/Election.php (added 3 methods)
- app/Http/Controllers/Election/ElectionManagementController.php (rewrote 2 methods)
- app/Events/ElectionStateChangedEvent.php (NEW)
- resources/js/Pages/Election/Management.vue (updated button logic)
- tests/Feature/Election/VotingButtonsStateMachineTest.php (10 new tests)

---

**Status:** Production Ready ✅
