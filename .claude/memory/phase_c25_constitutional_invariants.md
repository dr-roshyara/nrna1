---
name: phase-c25-constitutional-invariants
description: Phase C.2.5 immutable constitutional engineering laws and strategic positioning
metadata: 
  node_type: memory
  type: reference
  originSessionId: 94035d89-d58a-4291-be69-415431680e24
---

# Phase C.2.5: Constitutional Invariants

**Established:** 2026-05-25  
**Status:** Immutable unless entire governance model changes

---

## THE CENTRAL INVARIANT

```
State machine orchestrates runtime.
Resolver determines authority.
```

This is a **constitutional engineering law**, not a design guideline.

Immutability: ABSOLUTE unless the governance model fundamentally changes.

---

## What This Means

| Layer | Responsibility |
|-------|-----------------|
| **Resolver** | Constitutional authority interpretation (SSOT) |
| **State Machine** | Runtime orchestration sequencing |
| **Everything Else** | Authority consumer (reader of resolver decisions) |

---

## What Must NEVER Happen

Authority interpretation must NEVER hide inside:

- Transition guards
- Lifecycle wrappers  
- Orchestration helpers
- State-machine "canX" helpers
- Transition timing checks
- Lifecycle enum implications

This is drift risk #1.

---

## Step 4 Danger Zones (Critical)

When decontaminating state machine, aggressively inspect for:

| Pattern | Why Dangerous |
|---------|--------------|
| Transition guards infer permissions | Distributed sovereignty |
| Lifecycle wrappers compute access | Authority duplication |
| Orchestration helpers derive capability | Hidden resolver bypass |
| State-machine "canX" methods | Sovereignty leakage |
| Transition timing becomes authorization | Runtime reinterpretation |
| Lifecycle enums imply permission | Constitutional drift |

All of these are **sovereignty leakage dressed as orchestration**.

---

## Architectural Reframing

This project operates at a different maturity level now:

NOT: CRUD, workflows, UI flows  
NOW: **Constitutional runtime integrity**

That means:

- Telemetry is governance infrastructure
- Vocabulary is constitutional law
- Projections are authority assertions
- Orchestration is runtime sequencing
- Lifecycle semantics are governance encoding
- Capabilities are constitutional facts

---

## The Core Separation

At end of Step 4, state machine becomes:

```
Runtime orchestration infrastructure ONLY
NOT
Constitutional authority infrastructure
```

Everything else becomes:

```
Authority consumer
NOT
Authority interpreter
```

---

## Immutable Truth

**ElectionCapabilityResolver is the ONLY constitutional authority interpreter.**

Everything else:
- Middleware
- Vue
- State machine
- Wrappers
- Projections
- Orchestration

must be authority consumers.

---

## Step 4 Success Definition

✅ State machine is orchestration-only  
✅ Zero authority interpretation in state machine  
✅ All authority queries go through resolver  
✅ Transition guards check state, not permission  
✅ Lifecycle metadata is visualization-only  
✅ No capability inference from state  

---

## After Step 4

Authority flow is unambiguous:

```
ElectionConstitution (sovereign rules)
    ↓
ElectionCapabilityResolver (derives authority)
    ↓
Everything else (consumes authority)
```

No branching, no interpretation, no escape routes.

