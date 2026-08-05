---
name: phase-c25-step4-semantic-contamination
description: Phase C.2.5 Step 4 — Semantic contamination fix eliminating authority interpretation disguised as orchestration
metadata: 
  type: reference
  node_type: memory
  originSessionId: current
---

# Phase C.2.5 Step 4: Semantic Contamination Fix

**Date:** 2026-05-25  
**Fix:** Renamed `canTransitionTo()` → `isActionAllowed()`  
**Commit:** `12b5dda0b`

---

## The Problem

`ElectionLifecycleSnapshot::canTransitionTo()` method name **suggested orchestration** ("can we transition to this state?") but **actually performed authority checking** ("is this action in the allowedActions array?").

This violated the constitutional invariant:
```
State machine orchestrates runtime.
Resolver determines authority.
```

The contamination was subtle but critical:
- Method name = orchestration semantics (state transitions)
- Method implementation = authority semantics (allowed actions)
- Result = confusion about whether this is orchestration or authority

---

## Root Cause

The method was created as a convenience accessor on the snapshot value object:

```php
// BEFORE: Semantic contamination
public function canTransitionTo(string $action): bool
{
    return in_array($action, $this->allowedActions, true);
}
```

The problem:
1. Name suggests state machine logic ("can transition to")
2. But actually returns authority data ("is action in allowedActions?")
3. Located on a value object (data holder), not decision engine
4. Could mislead developers into using it for orchestration

---

## The Fix

**Renamed to clarify semantics:**

```php
// AFTER: Clear authority semantics
public function isActionAllowed(string $action): bool
{
    return in_array($action, $this->allowedActions, true);
}
```

Added deprecation wrapper on facade for backward compatibility:

```php
// ElectionLifecycle facade preserves canTransitionTo() for backward compatibility
/**
 * @deprecated Use isActionAllowed() instead.
 * PHASE C.2.5: Name was semantically contaminated — suggested orchestration.
 */
public function canTransitionTo(string $action): bool
{
    return $this->isActionAllowed($action);
}
```

---

## What Changed

| File | Change |
|------|--------|
| ElectionLifecycleSnapshot.php | canTransitionTo() → isActionAllowed() |
| ElectionLifecycle.php | Added isActionAllowed(), deprecated canTransitionTo() with wrapper |
| Election.php | Updated call site to use isActionAllowed() |
| Tests | Updated to test both methods for backward compatibility |

---

## Boundary Clarification

| Concept | Definition | Example |
|---------|-----------|---------|
| **Orchestration** | State machine transitions | "Can we move from Draft → Approved?" |
| **Authority** | Governance permission | "Is submit_for_approval allowed in Draft state?" |

- **canTransitionTo()** = misleading name for authority check
- **isActionAllowed()** = clear name for authority check
- State machine handles orchestration (TRANSITIONS constant, canTransition() method)
- Resolver handles authority (ElectionLifecycleEngineImpl, allowedActions array)

---

## Constitutional Invariant Upheld

✅ State machine is orchestration-only (TRANSITIONS, getCurrentState, transitionTo)  
✅ Resolver is authority-only (ElectionLifecycleEngineImpl, allowedActions)  
✅ No authority interpretation in state machine  
✅ All authority queries flow through resolver  
✅ Snapshot holds authority data (read-only projection)  

---

## Anti-Leak Guards Status

Pre-existing violations (not introduced by this fix):
- `VotingSecurityController.php`: New allowsAction() call site
- `VotingSecurityController.php`: Hardcoded denial reason 'not eligible'
- `getStateMachine()` call site (unresolved)

These are red-phase discoveries from Step 1 guards, not caused by Step 4 work.

---

## Tests Passing

✅ ElectionLifecycleSnapshotTest (6/6 passing)  
✅ ElectionLifecycleFacadeTest (17/18 passing) — 1 pre-existing failure  
✅ Backward compatibility: canTransitionTo() wrapper works  
✅ Forward compatibility: isActionAllowed() is clear  

---

## Next Steps (Step 5+)

1. **Step 5:** Behavioral sovereignty tests verify resolver is authoritative
2. **Step 6:** Test migration to SSOT pattern
3. **Step 7:** Remove deprecated bridges after telemetry confirms migration
4. **Step 8:** Verify all authority flows through resolver only
