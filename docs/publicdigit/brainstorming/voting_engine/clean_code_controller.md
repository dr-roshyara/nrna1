# TASK — Separate Real Voting from Demo Voting

## Objective

We have a dedicated:

```text
DemoCodeController.php
```

for demo voting.

The architectural goal is to make:

```text
CodeController.php
```

the controller for **real/production voting only**, while:

```text
DemoCodeController.php
```

owns **all demo voting behavior**.

Before changing anything, prove the current boundary and identify exactly which code in `CodeController.php` exists only because of demo voting.

---

# 1. ABSOLUTE RULES

This is a controlled TDD/refactoring task.

Do NOT make broad changes.

Do NOT redesign voting.

Do NOT change:

- final-cast authorization
- VotingAuthorization
- VotingEntitlement
- Trust → Legitimacy
- CAS participation consumption
- Vote persistence
- anonymity model
- schema
- migrations
- real voting business rules

Do NOT touch `DemoCodeController.php` until its current responsibilities and routes are understood.

Do NOT delete code merely because it "looks unused".

Every deletion must be supported by evidence.

---

# 2. FIRST: READ-ONLY DISCOVERY

Before editing anything, inspect:

```text
CodeController.php
DemoCodeController.php
routes/web.php
routes/api.php
all routes referencing CodeController
all routes referencing DemoCodeController
all tests referencing either controller
all methods called by either controller
all frontend callers of those routes
```

Search globally for:

```text
CodeController
DemoCodeController
demo
demoVoting
demo_code
demo_codes
election->type
type === 'real'
type !== 'real'
type == 'real'
is_demo
demo_mode
```

Build a responsibility matrix:

| Responsibility | CodeController | DemoCodeController | Shared | Unknown |
|---|---|---|---|---|

---

# 3. DEFINE THE ARCHITECTURAL INVARIANT

The target invariant is:

> `CodeController` contains only real-voting code lifecycle behavior.

and:

> `DemoCodeController` contains only demo-voting code lifecycle behavior.

Therefore `CodeController` should not contain demo-specific branches whose only purpose is to support demo elections.

Examples of suspicious code:

```php
if ($election->type !== 'real') {
    ...
}
```

```php
if ($election->type === 'demo') {
    ...
}
```

```php
if ($demoVoting) {
    ...
}
```

But DO NOT delete these automatically.

First establish what they actually protect.

---

# 4. TDD CHARACTERIZATION FIRST

Before deleting demo branches, create or identify tests proving the required separation.

## Test A — Real voting still works

Characterize the real CodeController path.

Expected:

```text
real election
→ CodeController
→ real code lifecycle
→ existing behavior preserved
```

This must be GREEN before refactoring.

---

## Test B — Demo voting uses DemoCodeController

Characterize the demo route.

Expected:

```text
demo election/session
→ DemoCodeController
→ demo code lifecycle
```

The test should demonstrate that the demo path does NOT require CodeController.

---

## Test C — No production CodeController dependency on demo behavior

Where practical, create a structural/architecture test that proves:

```text
CodeController
```

does not reference demo-only classes, methods, flags, or persistence mechanisms.

Do not create brittle tests based on line numbers.

Prefer architectural intent.

---

# 5. BUILD THE DEMO RESPONSIBILITY INVENTORY

For every demo-related method/branch in CodeController, record:

```text
method:
reason it exists:
caller:
route:
real voting required?:
demo voting required?:
equivalent DemoCodeController responsibility:
safe to remove?:
evidence:
```

Do not delete until every candidate has been classified.

---

# 6. IMPORTANT — DISTINGUISH SHARED CODE

Some code may look demo-related but actually represent shared infrastructure.

Examples:

```text
code generation
code formatting
common validation
shared DTO
shared value object
shared mail abstraction
shared persistence
```

Do NOT duplicate or delete shared infrastructure merely to achieve controller separation.

If shared behavior exists, recommend extracting it only if necessary.

Do not perform such extraction in this task unless explicitly approved.

---

# 7. DELETE ONLY DEMO-ONLY CODE

After characterization tests are GREEN and the responsibility matrix proves the boundary:

Remove from `CodeController.php` only code whose sole responsibility is demo voting.

Examples may include:

```text
demo-specific branches
demo-specific methods
demo-specific redirects
demo-specific state handling
demo-specific flags
demo-specific database operations
demo-specific validation
```

Do NOT remove real-election logic merely because it contains:

```php
if ($election->type === 'real')
```

First prove whether that condition is still required.

---

# 8. REMOVE CONDITIONAL COMPLEXITY WHERE JUSTIFIED

If the controller previously contained:

```php
if ($election->type === 'real') {
    REAL_CODE
} else {
    DEMO_CODE
}
```

and demo routing is now exclusively handled by `DemoCodeController`, the target should normally become:

```php
REAL_CODE
```

rather than retaining:

```php
if ($election->type === 'real') {
    REAL_CODE
}
```

However, only make this simplification when route/controller ownership proves that non-real elections cannot legitimately reach this controller.

---

# 9. VERIFY ROUTING

After removal, prove:

```text
Real election
    ↓
real route
    ↓
CodeController

Demo election
    ↓
demo route
    ↓
DemoCodeController
```

There must be no accidental path:

```text
Demo
 ↓
CodeController
```

unless there is an explicitly documented shared reason.

---

# 10. VERIFY DATABASE BOUNDARIES

Inspect whether CodeController touches:

```text
codes
demo_codes
```

or other demo-specific persistence.

Expected target:

```text
CodeController
    → real voting persistence

DemoCodeController
    → demo voting persistence
```

Do not change schema.

Do not migrate data.

Do not rename tables.

Only remove obsolete controller responsibilities.

---

# 11. VERIFY TEST SUITE

Before refactoring:

Run the relevant baseline tests.

Record:

```text
baseline:
pass:
fail:
error:
skip:
```

Then perform the minimal refactoring.

Run:

```text
real voting tests
demo voting tests
CodeController tests
DemoCodeController tests
architecture tests
Phase 4 E1
Phase 4 E2
Phase 4 E3
Phase 4 E4
```

No previously passing test may regress.

---

# 12. SEARCH FOR ORPHANS

After the refactoring, search globally for references to removed CodeController demo methods.

Examples:

```bash
rg "removedMethodName" .
rg "CodeController" .
rg "demoVoting" .
rg "demo_code" .
```

Every remaining reference must be classified.

No orphan route.

No orphan test.

No orphan frontend call.

No dead production dependency.

---

# 13. ARCHITECTURAL ACCEPTANCE CRITERIA

The refactoring is successful only if:

### AC1

`CodeController` has no demo-only business behavior.

### AC2

`DemoCodeController` remains the explicit owner of demo code behavior.

### AC3

Real voting behavior is unchanged.

### AC4

Demo voting behavior is unchanged.

### AC5

No Phase 4 final-cast authority is changed.

### AC6

No anonymity behavior is changed.

### AC7

No schema/migration is introduced.

### AC8

No new security authority is introduced.

### AC9

All relevant tests remain GREEN.

### AC10

No dead references remain.

---

# 14. REQUIRED OUTPUT BEFORE EDITING

STOP and report:

```text
DEMO/REAL CONTROLLER BOUNDARY REPORT

1. Current CodeController responsibilities
2. Current DemoCodeController responsibilities
3. Routes
4. Callers
5. Demo-only methods in CodeController
6. Shared methods
7. Ambiguous methods
8. Tests protecting real behavior
9. Tests protecting demo behavior
10. Proposed minimal deletion set
11. Risks
12. Expected test impact
```

Then STOP.

Wait for explicit approval.

---

# 15. AFTER APPROVAL — TDD REFACTOR

Only after approval:

```text
GREEN baseline
    ↓
minimal deletion
    ↓
run tests
    ↓
GREEN
    ↓
global reference search
    ↓
architecture verification
```

If tests fail:

STOP.

Do not automatically repair unrelated behavior.

---

# 16. GIT RULE

Do not commit automatically.

Show:

```bash
git diff -- CodeController.php
git diff -- tests/
git status --short
```

Explain every deleted block.

Wait for explicit approval before commit.

---

# FINAL PRINCIPLE

The objective is not merely:

> "make CodeController smaller."

The architectural objective is:

> **Establish an explicit bounded responsibility: CodeController owns real voting; DemoCodeController owns demo voting.**

Use evidence and TDD to prove that the deletion does not remove real-voting behavior.

No speculative cleanup.
No unrelated refactoring.
No production changes before approval.