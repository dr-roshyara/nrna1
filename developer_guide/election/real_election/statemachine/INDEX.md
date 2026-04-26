# Developer Guide Index

## Quick Start
Start here if you're new to the election state machine.

1. **[README.md](./README.md)** — Overview and key features
2. **[01_ARCHITECTURE.md](./01_ARCHITECTURE.md)** — System design and data flow
3. **[02_API_REFERENCE.md](./02_API_REFERENCE.md)** — Method signatures and endpoints
4. **[05_COMMON_PATTERNS.md](./05_COMMON_PATTERNS.md)** — Real-world usage examples

---

## Complete Documentation

### Overview
- **[README.md](./README.md)** - Project overview, key features, state diagram

### Architecture
- **[01_ARCHITECTURE.md](./01_ARCHITECTURE.md)**
  - System design (DDD layers)
  - Data flow diagrams
  - Cache lock mechanism
  - Transaction rollback
  - Validation flow
  - File locations

### API Reference
- **[02_API_REFERENCE.md](./02_API_REFERENCE.md)**
  - Election::transitionTo() method
  - Controller methods (openVoting, closeVoting)
  - Events (ElectionStateChangedEvent)
  - Accessors (current_state)
  - Helper methods
  - Error messages

### Testing
- **[03_TESTING.md](./03_TESTING.md)**
  - How to run tests
  - Test structure (RED/GREEN/REFACTOR)
  - Model tests (2)
  - Controller tests (8)
  - Regression tests (25)
  - Adding new tests
  - Common test failures

### Frontend
- **[04_FRONTEND.md](./04_FRONTEND.md)**
  - Management.vue changes
  - Computed properties
  - Button visibility logic
  - State styling
  - Props flow
  - Integration points

### Common Patterns
- **[05_COMMON_PATTERNS.md](./05_COMMON_PATTERNS.md)**
  - Safe state transitions
  - Double-lock guard
  - Querying by state
  - Event listeners
  - WebSocket broadcast
  - Audit trail reports
  - Preventing changes during windows
  - Automated transitions
  - Pre-flight validation
  - Dashboard summaries

### Troubleshooting
- **[06_TROUBLESHOOTING.md](./06_TROUBLESHOOTING.md)**
  - Common issues and solutions
  - Debugging checklist
  - Error message reference

### Voting Button Implementation
- **[07_VOTING_BUTTON_IMPLEMENTATION.md](./07_VOTING_BUTTON_IMPLEMENTATION.md)**
  - Complete voting button architecture
  - Open voting / Close voting flows
  - Phase 4 critical fixes (updateQuietly, nomination_completed)
  - Testing strategy (9 integration tests)
  - Common pitfalls and solutions
  - Integration patterns
  - Database schema
  - Deployment checklist

### Action-Based State Machine (NEW - Refactoring v2.0)
- **[08_ACTION_BASED_STATE_MACHINE.md](./08_ACTION_BASED_STATE_MACHINE.md)**
  - Overview of action-based architecture (vs state-based)
  - TransitionMatrix: ALLOWED_ACTIONS + ACTION_RESULTS constants
  - transitionTo(action, trigger, reason, actorId) method
  - Single responsibility: only place that sets state
  - Side effects separation (no state changes in apply* methods)
  - Events dispatched by action name
  - Data flow diagrams
  - Testing examples (unit + feature)
  - Migration guide (old way vs new way)
  - Common patterns and troubleshooting
  - File locations and performance notes

---

## By Role

### Developer (Adding Features)
1. Read: [README.md](./README.md) - understand what this does
2. Read: [01_ARCHITECTURE.md](./01_ARCHITECTURE.md) - how it works
3. Reference: [02_API_REFERENCE.md](./02_API_REFERENCE.md) - method signatures
4. Follow: [05_COMMON_PATTERNS.md](./05_COMMON_PATTERNS.md) - how to use it
5. Debug: [06_TROUBLESHOOTING.md](./06_TROUBLESHOOTING.md) - if something breaks

### QA (Testing)
1. Read: [03_TESTING.md](./03_TESTING.md) - how tests work
2. Run: `php artisan test tests/Feature/Election/`
3. Check: [06_TROUBLESHOOTING.md](./06_TROUBLESHOOTING.md) - if tests fail

### DevOps (Deployment)
1. Check: [README.md](./README.md) - deployment checklist
2. Verify: Database migrations applied
3. Verify: Tests passing: `php artisan test`
4. Monitor: [05_COMMON_PATTERNS.md](./05_COMMON_PATTERNS.md) - logging patterns

### Frontend (UI Integration)
1. Read: [04_FRONTEND.md](./04_FRONTEND.md) - Vue.js changes
2. Reference: [02_API_REFERENCE.md](./02_API_REFERENCE.md) - API endpoints
3. Use: [05_COMMON_PATTERNS.md](./05_COMMON_PATTERNS.md) - Vue patterns

---

## Key Files in Codebase

### Core Implementation
- `app/Models/Election.php` - transitionTo() bridge method
- `app/Http/Controllers/Election/ElectionManagementController.php` - controller methods
- `app/Events/ElectionStateChangedEvent.php` - NEW: event class
- `app/Domain/Election/StateMachine/ElectionStateMachine.php` - domain service

### Tests
- `tests/Feature/Election/VotingButtonsStateMachineTest.php` - 10 new assertions
- `tests/Feature/ElectionStateMachineTest.php` - 25 regression tests

### Frontend
- `resources/js/Pages/Election/Management.vue` - button visibility logic

---

## Running Tests

```bash
# All election tests
php artisan test tests/Feature/Election/ --no-coverage

# Just voting buttons
php artisan test tests/Feature/Election/VotingButtonsStateMachineTest.php --no-coverage

# Just regression
php artisan test tests/Feature/ElectionStateMachineTest.php --no-coverage

# With filter
php artisan test tests/Feature/Election/ --filter "open_voting" --no-coverage

# Expected result: 35 tests passing
```

---

## Quick Commands

```bash
# See all files modified
git log --oneline | head -5

# Run tests
php artisan test tests/Feature/Election/ --no-coverage

# Check database
php artisan tinker
> Election::first()->current_state

# Monitor logs
tail -f storage/logs/laravel.log | grep "ElectionStateChanged"
```

---

## Support

- **Bug?** → [06_TROUBLESHOOTING.md](./06_TROUBLESHOOTING.md)
- **How do I...?** → [05_COMMON_PATTERNS.md](./05_COMMON_PATTERNS.md)
- **What does this do?** → [02_API_REFERENCE.md](./02_API_REFERENCE.md)
- **Why is it designed this way?** → [01_ARCHITECTURE.md](./01_ARCHITECTURE.md)

---

**Last Updated:** April 26, 2026
**Status:** Production Ready ✅ (Action-Based Refactoring Complete)
**Test Coverage:** 67/67 passing
  - TransitionMatrixTest: 23 tests
  - ElectionStateMachineTest: 35 tests
  - VotingButtonsStateMachineIntegrationTest: 9 tests
