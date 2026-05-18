
## Claude CLI Prompt Instructions

```prompt
I need a comprehensive analysis of the Election State Machine before refactoring.

## Context

The election state machine controls the lifecycle of elections:
- draft → active → closed → archived

Currently, there are reported inconsistencies in how transitions work. Some transitions may be allowed when they shouldn't be, validation may be scattered, and events may not be dispatched properly.

## Step 1: Read the Developer Guide

First, read all documents in:
```
C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu\developer_guide\election\real_election\statemachine
```

Look for:
- Architecture decisions
- Current state definitions
- Transition rules
- Known issues

## Step 2: Analyze Current Implementation

### 2.1 Find the Election Model
```bash
find app -name "Election.php" -type f
```

Read the Election model. Identify:
- Current status/state field
- Any existing state machine logic
- Any transition methods (start, close, archive, etc.)

### 2.2 Find the Controller
```bash
find app/Http/Controllers -name "*Election*Controller.php" -type f
```

Read controllers that handle election state changes. Identify:
- How elections are started
- How elections are closed
- How elections are archived
- What validation exists

### 2.3 Find Routes
```bash
php artisan route:list | grep -i "election.*start\|election.*close\|election.*archive"
```

### 2.4 Find Tests
```bash
find tests -name "*Election*State*" -o -name "*Election*Transition*" -o -name "*Election*Lifecycle*"
```

Run the tests to see what's passing/failing:
```bash
php artisan test --filter="Election.*state\|Election.*transition" --no-coverage
```

### 2.5 Find Events
```bash
find app -name "*Election*Event*.php" -type f
```

Check if events are dispatched for state transitions.

### 2.6 Find Cache Invalidation
```bash
grep -r "Cache::\|ElectionCacheService" app/Models/Election.php app/Http/Controllers/*Election*
```

## Step 3: Document Current Behavior

Create a report with the following sections:

### 3.1 Current State Machine (As-Is)

```yaml
STATES DEFINED:
  - [list all states found]

TRANSITIONS:
  - [list all transitions found]

ALLOWED TRANSITIONS (current):
  - [document what currently works]

FORBIDDEN TRANSITIONS (current gaps):
  - [document what should be forbidden but isn't]
```

### 3.2 Controller Analysis

For each controller action that changes election state:

| Action | File | Line | Validation | Event | Cache |
|--------|------|------|------------|-------|-------|
| start | ... | ... | ... | ... | ... |
| close | ... | ... | ... | ... | ... |
| archive | ... | ... | ... | ... | ... |

### 3.3 Test Coverage

```yaml
PASSING TESTS:
  - [list]

FAILING TESTS:
  - [list]

MISSING TESTS:
  - [list expected transitions not tested]
```

### 3.4 Issues Identified

```yaml
CRITICAL (blocking):
  - [state transitions that don't work]

HIGH (important):
  - [missing validation]

MEDIUM (should fix):
  - [missing events, cache issues]

LOW (nice to have):
  - [documentation, code clarity]
```

## Step 4: Propose Target Architecture

Based on the analysis and the successful pattern from voter assignment (Phase C.2-C.4), propose:

### 4.1 Target State Machine

```yaml
STATES:
  - draft (initial, editable)
  - active (voting open)
  - closed (voting ended)
  - archived (read-only)

TRANSITION RULES:
  draft → active: requires start_date <= now, end_date > now
  active → closed: requires now >= end_date or manual override
  closed → archived: requires results published
  FORBIDDEN: any backward transitions
```

### 4.2 Proposed Architecture

```yaml
DOMAIN LAYER:
  - ElectionState enum
  - Election aggregate with state machine methods
  - ElectionRepositoryInterface

APPLICATION LAYER:
  - StartElectionCommand
  - StartElectionHandler
  - CloseElectionCommand
  - CloseElectionHandler
  - ArchiveElectionCommand
  - ArchiveElectionHandler

INFRASTRUCTURE LAYER:
  - EloquentElectionRepository

EVENTS:
  - ElectionStarted
  - ElectionClosed
  - ElectionArchived

CACHE:
  - Update ElectionCacheService to handle election state
```

### 4.3 File Change List

```yaml
NEW FILES:
  - app/Domain/Election/Enum/ElectionState.php
  - app/Contexts/Elections/Domain/Election.php (aggregate)
  - app/Contexts/Elections/Domain/Repositories/ElectionRepositoryInterface.php
  - app/Contexts/Elections/Application/Commands/StartElectionCommand.php
  - app/Contexts/Elections/Application/Commands/CloseElectionCommand.php
  - app/Contexts/Elections/Application/Commands/ArchiveElectionCommand.php
  - app/Contexts/Elections/Application/Handlers/StartElectionHandler.php
  - app/Contexts/Elections/Application/Handlers/CloseElectionHandler.php
  - app/Contexts/Elections/Application/Handlers/ArchiveElectionHandler.php
  - app/Contexts/Elections/Infrastructure/Repositories/EloquentElectionRepository.php
  - app/Domain/Election/Events/ElectionStarted.php
  - app/Domain/Election/Events/ElectionClosed.php
  - app/Domain/Election/Events/ElectionArchived.php

MODIFIED FILES:
  - app/Http/Controllers/ElectionController.php (or relevant controller)
  - app/Services/ElectionCacheService.php (add election state keys)
  - app/Providers/AppServiceProvider.php (add bindings)
```

## Step 5: Provide Execution Order

```yaml
Phase 1: Domain Layer (2 hours)
  - Create ElectionState enum
  - Create Election aggregate
  - Write unit tests for state transitions

Phase 2: Repository Layer (1 hour)
  - Create ElectionRepositoryInterface
  - Create EloquentElectionRepository
  - Write repository tests

Phase 3: Application Layer (3 hours)
  - Create commands
  - Create handlers
  - Write handler unit tests (mocked)

Phase 4: Controller Wiring (1 hour)
  - Update controller to use handlers
  - Add event dispatch
  - Add cache invalidation

Phase 5: Integration Tests (2 hours)
  - Write feature tests for full flow
  - Verify all transitions work

Total: ~9 hours
```

## Output Expected

After completing Steps 1-4, provide:

1. **Analysis Report** — current state, issues, gaps
2. **Target Architecture Proposal** — as-is vs to-be
3. **Implementation Plan** — file list, order, estimates

Do NOT start implementation yet. Only analyze and plan.

Proceed.
```

---

# Critical Missing Analysis Areas

## 1. Find ALL Election Status Writes

Right now your prompt looks for controllers and routes.

That is not enough.

In legacy Laravel systems, state changes are often hidden in:

* observers
* jobs
* listeners
* services
* traits
* scheduled commands
* seeders
* policies
* blade actions
* Livewire actions
* admin panels
* bulk actions

Add this section:

### 2.7 Find ALL State Mutations

Search for every place election status/state is written directly.

```bash
grep -R "status.*=" app/
grep -R "->status" app/
grep -R "update.*status" app/
grep -R "'status'" app/
grep -R "draft" app/
grep -R "active" app/
grep -R "closed" app/
grep -R "archived" app/
```

Identify:

* Direct model mutations
* Mass assignment updates
* Query builder updates
* Hidden transitions in jobs/listeners
* Admin panel actions
* Scheduled auto-close logic

Create a full write-path inventory.

Goal:
Every election state mutation must be discovered before refactor.

This is extremely important.

Otherwise:

* one old controller keeps writing raw status
* aggregate becomes bypassed
* state machine becomes fake architecture

---

# 2. Add Transition Matrix Discovery

Right now Claude will likely describe transitions narratively.

Force a matrix.

Add:

### 3.1.1 Transition Matrix (Current Reality)

Build a matrix of ACTUAL current behavior:

| From   | To       | Allowed? | Where Enforced | Validation | Event | Cache | Tested |
| ------ | -------- | -------- | -------------- | ---------- | ----- | ----- | ------ |
| draft  | active   | ?        | ...            | ...        | ...   | ...   | ...    |
| active | closed   | ?        | ...            | ...        | ...   | ...   | ...    |
| closed | archived | ?        | ...            | ...        | ...   | ...   | ...    |
| active | draft    | ?        | ...            | ...        | ...   | ...   | ...    |

This matrix must reflect REAL runtime behavior, not intended behavior.

This becomes your migration constitution.

---

# 3. Require Invariant Discovery

This is the most important missing piece.

A state machine is NOT states.

A state machine is INVARIANTS.

Claude must identify hidden business invariants.

Add:

### 3.5 Business Invariant Discovery

Identify all hidden election invariants currently enforced or implicitly assumed.

Examples:

* Election cannot start without candidates
* Election cannot close while vote counting running
* Election cannot archive before results published
* Election dates cannot overlap
* Election must belong to active organisation
* Active election must have at least one voter
* Closed election becomes immutable

For each invariant:

* Where enforced
* How enforced
* Whether tested
* Whether bypassable
* Severity if violated

Without this, you risk implementing a technically correct but governance-invalid system.

---

# 4. Add Concurrency / Race Condition Analysis

This is absolutely essential for election systems.

Add:

### 3.6 Concurrency & Race Conditions

Analyze race conditions around election state transitions.

Check for:

* Two admins starting election simultaneously
* Closing election while votes are still processing
* Double archive requests
* Background jobs mutating state after closure
* Missing DB transactions
* Missing optimistic locking

Document:

* Current protections
* Missing protections
* Recommended hardening

Election systems are highly concurrency-sensitive.

---

# 5. Add Authorization Boundary Mapping

Very important.

Who is allowed to transition state?

Controller middleware alone is insufficient.

Add:

### 3.7 Authorization Boundary Analysis

For each transition:

* Who can trigger it
* Where authorization is enforced
* Whether authorization is duplicated
* Whether authorization can be bypassed

Check:

* Policies
* Gates
* Middleware
* Controller checks
* Service-level checks

Goal:
Authorization must move with the state machine, not remain in controllers.

---

# 6. Add Event Flow Discovery

Right now you only ask whether events exist.

You need the full event graph.

Add:

### 3.8 Event Flow Mapping

For each election transition:

* Which events are dispatched
* Which listeners consume them
* Which side effects occur

Examples:

* Notifications
* Audit logging
* Cache invalidation
* Metrics
* Webhooks
* Queue jobs
* Result calculation

Create a dependency graph.

Goal:
Prevent accidental side-effect loss during refactor.

This prevents “silent system death” after refactor.

---

# Biggest Architectural Correction

This is VERY important:

You currently propose:

```yaml
DOMAIN LAYER:
  - Election aggregate
```

But your Laravel `Election` model already exists.

Do NOT create a second parallel Election domain object immediately.

That is extremely risky during strangler migration.

Instead:

# Recommended Safer Path

## Phase 1

Use:

* `ElectionState` enum
* transition policy/service
* handlers
* repository abstraction

BUT KEEP:

* existing Eloquent Election model

## Phase 2

Gradually extract aggregate behavior later.

Otherwise you create:

* dual truth systems
* model vs aggregate divergence
* hydration mismatch
* event duplication
* serialization chaos

So I would modify your target architecture:

```yaml
DOMAIN LAYER:
  - ElectionState enum
  - ElectionTransitionPolicy
  - Transition invariants
  - Domain events

APPLICATION LAYER:
  - StartElectionHandler
  - CloseElectionHandler
  - ArchiveElectionHandler

INFRASTRUCTURE:
  - Existing Eloquent Election model retained initially
```

Then later:

* evolve toward true aggregate

This is much safer.

---

# Another Critical Recommendation

Add a requirement:

> "Do not replace existing logic until all write paths are discovered."

That single rule prevents catastrophic regressions.

---
