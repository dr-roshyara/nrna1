---
name: phase-c25-step4-constraints
description: Critical architectural constraints for Phase C.2.5 Step 4 — State-Machine Decontamination
metadata: 
  node_type: memory
  type: reference
  originSessionId: 94035d89-d58a-4291-be69-415431680e24
---

# Phase C.2.5 Step 4 — State-Machine Decontamination: Critical Constraints

**Authority:** User architectural guidance, 2026-05-25  
**Urgency:** CRITICAL — State machine is the highest remaining sovereignty risk

---

## The Core Invariant (ABSOLUTE)

```
State machine orchestrates runtime.
Resolver determines authority.
```

This distinction must remain absolute throughout Step 4 and beyond.

---

## What ElectionStateMachine May Do

| Allowed | Purpose |
|---------|---------|
| `canTransition(state)` | Check if orchestration transition is valid |
| `getCurrentState()` | Return current lifecycle state |
| `transitionTo(state)` | Perform state transition |
| `getValidTransitions()` | Return valid next states |
| State metadata queries | Provide visualization hints |
| Orchestration sequencing | Manage lifecycle progression |

---

## What ElectionStateMachine MUST NEVER Do

| Forbidden | Why |
|-----------|-----|
| `canApprove(user)` | Permission inference |
| `isAuthorized(user)` | Authority derivation |
| `deriveCapability(state)` | Capability computation |
| `reconstructPermission(state)` | Governance interpretation |
| Permission logic in transition guards | Authority in orchestration |
| Role-based capability inference | Distributed sovereignty |

**Cardinal sin:** Asking state machine "who may act?"  
State machine only answers: "what transition exists?"

---

## Danger Zones to Block in Step 4

DO NOT accidentally move authority logic into:

1. **Transition guards** — Authority checks in `canTransition()`
2. **Helper traits** — Permission inference in shared traits
3. **Orchestration services** — Authority computation in sequencing logic
4. **Lifecycle adapters** — Capability derivation in state adapters

**All of these would merely RELOCATE distributed sovereignty instead of ELIMINATING it.**

---

## Everything Must Still Resolve Through

```
ElectionCapabilityResolver ONLY
```

Not through:
- State machine methods
- Orchestration helpers
- Lifecycle adapters
- Transition guards
- State evaluation services

---

## Step 4 Success Criteria

✅ ElectionStateMachine is orchestration-only  
✅ Zero permission logic in state machine  
✅ All authority decisions still go through resolver  
✅ Transition guards check state, not permission  
✅ State metadata is visualization-only  
✅ No capability inference from state  

---

## Related Completed Work (Foundation)

- **Step 0:** Authority-path audit — mapped sovereignty reconstruction
- **Step 1:** Anti-leak guards — froze new distributed sovereignty
- **Step 2:** Middleware migration — transformed from interpreter to consumer
- **Step 3:** Deprecated bridge quarantine — telemetry for archaeology

All groundwork is solid. Step 4 is the most sensitive remaining work.

---

## Why This Is Dangerous

State machine wrappers historically accumulate:

- Hidden authorization
- Transition assumptions  
- Lifecycle-derived permissions
- Orchestration authority confusion

This is where Phase C.2.5 can **accidentally regress** sovereignty convergence.

---

## After Step 4

Then Step 5 (Behavioral Sovereignty Tests):

Cannot rely on grep to prove convergence. Need behavioral guarantees:
- Overlays respected
- Middleware matches resolver
- Deprecated bridges emit telemetry
- Lifecycle alone grants no authority

---

## Step 7 Hard Deletion

Once telemetry confirms:
- No callers
- No hidden dependencies  
- No bridge usage

THEN: Hard-delete deprecated sovereignty bridges.

Do NOT leave permanent "deprecated forever" runtime shells — that becomes constitutional ambiguity infrastructure.

