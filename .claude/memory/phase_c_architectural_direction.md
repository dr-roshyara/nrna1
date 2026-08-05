---
name: phase-c-architectural-direction
description: Senior architecture review guiding Phase C.2.1 and beyond — moving from state machine to constitutional governance engine
metadata: 
  node_type: memory
  type: project
  originSessionId: c58c462c-c198-465e-89b3-69a6bfe193fb
---

# Phase C — Constitutional Governance Consolidation Direction

**Current Status:** Phase C.2.0 complete and verified. System is now in constitutional consolidation phase, not feature velocity phase.

**Core Architectural Achievement:**
Separation of lifecycle truth from operational overlays:
```
VotingActive + Suspended (orthogonal dimensions)
NOT: Suspended replaces VotingActive (sequential)
```

This preserves temporal continuity and enables event sourcing.

**Why:** Resume re-derives from facts, not cached state. Time continues during suspension. That guarantees recomputability and resilience.

---

## Critical Strategic Insights

### 1. Authority Consolidation Target
The next center of gravity is:
```
ElectionCapabilityResolver
```

This becomes the single behavioral authority that all systems flow through:
- Controllers consult it
- Vue props consume its output
- Policies feed it decisions
- Overlays modify its results

### 2. allowedActions String Array Is Transitional
Current:
```php
['resume', 'suspend']
```

Limit reached around Phase C.3-D when you need:
```php
[
  'resume' => [
    'allowed' => true,
    'reason' => null,
    'source' => 'OverlayPolicy'
  ],
  'publish_results' => [
    'allowed' => false,
    'reason' => 'Election suspended',
    'source' => 'SuspensionOverlay'
  ]
]
```

Frontend will eventually need NOT just "can I?" but "why can't I?" — not for UI inference, but for explanation and audit.

### 3. Operational Overlay Explosion Risk
Suspended is only the first. Future overlays will include:
- legal_hold
- fraud_investigation
- disputed
- emergency_freeze
- readonly
- compliance_review

If added carelessly (if/else chains inside engine), you recreate distributed authority.

**Prevention:** Establish OperationalOverlayPolicyChain abstraction NOW, even if only one overlay exists. Better to add overlay types to enum than to add if statements to engine later.

### 4. Precondition Default Throw Was Critical
Replacing `default => true` with `throw new LogicException` eliminated silent governance bypass.

Unknown precondition names now fail visibly, not implicitly.

This prevents:
- Typo actions slipping through
- Partial migrations causing holes
- Future actions losing governance enforcement

**Preserve this invariant:** Every constitutional rule must be explicitly defined. Never default to "allowed."

### 5. Frontend Transitional, Not Constitutional
Frontend now reads from backend snapshot (good).

But eventually:
- Frontend should not know action names semantically
- Frontend should only know capability.allowed, capability.reason, capability.visible
- Frontend never derives "why blocked"
- Frontend never creates alternate governance logic

This becomes critical for:
- Localization (explaining in user's language)
- Policy transparency (audit UI showing governance decisions)
- Accessibility (explaining constraints to assistive tech)

### 6. Capability Audit Trail Missing
You audit suspension events.

You do NOT yet audit: "Why was capability denied?"

This becomes governance gold later:
```
User attempted publish_results
Denied by SuspensionOverlayPolicy at 2026-05-22 14:35:12
Suspension reason: fraud_investigation
```

Not needed now, but important future direction.

---

## Recommended Architecture Evolution

**Now (Phase C.2.1):**
```
ElectionLifecycleEngine
    ↓ derives lifecycle truth
    
ElectionCapabilityResolver
    ↓ orchestrates policies
    
[Policy1, Policy2, Policy3]
    ↓ each owns one concern
    
Frontend
    ↓ renders snapshots only
```

**Later (Phase C.3-D):**
```
ElectionLifecycleEngine
    ↓ 
    
ElectionCapabilityResolver
    ↓
    
[Overlay Policies] + [Constitutional Policies] + [Precondition Policies]
    ↓
    
ElectionCapabilitySnapshot
    ↓
    per-action rich metadata
    
Frontend Consumption Layer
    ↓ maps to localized explanations
    
Audit Trail Builder
    ↓ records denial reasons
```

---

## System Classification (Refined)

No longer:
- State machine
- Workflow engine
- CRUD system

Now:
**Constitutional Governance Execution Engine**

With:
- Recomputable lifecycle truth from facts
- Operational overlays orthogonal to progression
- Capability sovereignty (backend-only derivation)
- Temporal correctness (time flows during suspension)
- Centralized behavioral authority
- Audit-grade governance semantics

---

## Remaining Risk (Post C.2.0)

**OLD RISK (Solved):** Lifecycle corruption, state mutation pollution  
**NEW RISK (Watch):** Capability authority fragmentation

Prevention:
1. All capability decisions flow through ElectionCapabilityResolver
2. No local capability inference in controllers
3. No local governance logic in Vue components
4. Frontend reads snapshot only, never derives

---

## Phase C.2.1 Purpose

Build the ElectionCapabilityResolver as the irreplaceable center.

This is NOT optional refactoring. This is the next load-bearing structural wall.

Without it, you will eventually recreate the distributed authority problem you just fixed.

With it, all future overlays, policies, and governance layers snap into place cleanly.
