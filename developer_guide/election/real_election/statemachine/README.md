# Election State Machine Developer Guide

## Overview

This guide documents the complete implementation of the **Election State Machine** — a production-grade, TDD-first system for managing election lifecycle transitions with immutable audit trails, concurrent safety, and real-time observability.

**Version:** 2.0 (Action-Based Refactoring) | **Built:** April 2026 | **Status:** Production Ready ✅ | **Tests:** 67/67 passing

---

## Quick Links

- **[Action-Based State Machine](./08_ACTION_BASED_STATE_MACHINE.md)** ⭐ **[NEW]** — v2.0 refactoring, action-based architecture, TransitionMatrix, API guide
- **[Architecture](./01_ARCHITECTURE.md)** — System design, state diagram, data flow
- **[API Reference](./02_API_REFERENCE.md)** — Election::transitionTo(), controller methods, events
- **[Testing Guide](./03_TESTING.md)** — Unit tests, integration tests, TDD patterns
- **[Frontend Integration](./04_FRONTEND.md)** — Vue.js button visibility, component usage
- **[Common Patterns](./05_COMMON_PATTERNS.md)** — How to use in your code, examples
- **[Troubleshooting](./06_TROUBLESHOOTING.md)** — Debugging, error messages, recovery
- **[Voting Button Implementation](./07_VOTING_BUTTON_IMPLEMENTATION.md)** — Complete voting button system (Phase 4)

---

## What Problem Does This Solve?

### Before (Legacy Code)
Direct DB updates with no validation, audit trail, or idempotency.

### After (State Machine)
Validates state, creates immutable audit record, locks voting, fires events.

---

## Key Features

- **Action-Based Architecture** (v2.0) — Actions describe what happens (open_voting, approve, reject)
- **TransitionMatrix** — Two-constant system (ALLOWED_ACTIONS + ACTION_RESULTS)
- **Single Responsibility** — transitionTo() is the only place that sets state
- **Side Effects Separation** — Business logic split from state transitions
- **State Validation** — Prevents invalid transitions using action rules
- **Immutable Audit Trail** — Every transition recorded in ElectionStateTransition
- **Cache Lock** — Prevents concurrent transitions (10s TTL, 5s block wait)
- **Rollback on Failure** — Original flags restored if transaction fails
- **Event Broadcasting** — Real-time UI updates via action-based events
- **Double-Lock Guard** — Prevents closing already-ended voting
- **TDD Coverage** — 100% test coverage; 67 tests passing (23 TransitionMatrix + 35 ElectionStateMachine + 9 VotingButtons)
- **Voting Button System** — Complete Phase 4 implementation of Open/Close voting with full audit trail

---

## Files Modified

### Core Implementation (v2.0 Action-Based Complete)
- `app/Domain/Election/StateMachine/TransitionMatrix.php` — REWRITTEN with ALLOWED_ACTIONS + ACTION_RESULTS
- `app/Models/Election.php` — transitionTo() refactored to action-based (1383–1520)
  - `transitionTo(action, trigger, reason, actorId)` replaces state-based API
  - `applySideEffectsForOpenVoting()` renamed from `applyVotingTransition()`
  - `applySideEffectsForCloseVoting()` renamed from `applyResultsPendingTransition()`
  - Side effects use `DB::table()->update()` (no state changes)
- `app/Http/Controllers/Election/ElectionManagementController.php` — Updated to use action names
  - `openVoting()` calls `transitionTo('open_voting', ...)`
  - `closeVoting()` calls `transitionTo('close_voting', ...)`
- `app/Http/Controllers/Admin/AdminElectionController.php` — NEW, handles approval workflow
- `app/Domain/Election/Events/` — Event classes (VotingOpened, VotingClosed, ElectionApproved, ElectionRejected, ElectionSubmittedForApproval)

### Tests (67/67 Passing)
- `tests/Unit/Domain/Election/TransitionMatrixTest.php` — 23 action-based tests (NEW)
- `tests/Feature/Election/ElectionStateMachineTest.php` — 35 regression tests (all passing)
- `tests/Feature/Election/VotingButtonsStateMachineIntegrationTest.php` — 9 voting button integration tests (all passing)

### Frontend
- `resources/js/Pages/Election/Management.vue` — Button visibility based on election state

### Documentation
- `developer_guide/election/real_election/statemachine/07_VOTING_BUTTON_IMPLEMENTATION.md` — Complete voting button system guide

---

## Getting Started

### Run Tests
```bash
php artisan test tests/Feature/Election/VotingButtonsStateMachineTest.php
php artisan test tests/Feature/ElectionStateMachineTest.php
```

### View Implementation
- Core bridge: `app/Models/Election.php` lines 1064-1139
- Controller: `app/Http/Controllers/Election/ElectionManagementController.php` lines 808-847
- Tests: `tests/Feature/Election/VotingButtonsStateMachineTest.php`

---

## Production Deployment

- [ ] Run: `php artisan test`
- [ ] Verify: 35 tests pass
- [ ] Check: ElectionStateTransition table exists
- [ ] Test: State transitions in staging
- [ ] Monitor: ElectionStateChangedEvent logs
- [ ] Brief: Support team

---

**Last Updated:** April 26, 2026 (v2.0 Action-Based Refactoring Complete) | **Status:** Production Ready ✅ | **Tests:** 67/67 passing
