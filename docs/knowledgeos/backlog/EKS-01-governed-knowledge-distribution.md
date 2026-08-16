# EKS-01 — Governed-Knowledge Distribution

**Status:** BACKLOG — recorded on the PO/ARB act 2026-08-16 (*"record this as problem and write EKS- tickets"*). **Not commissioned; starting requires a human authorization act.**
**Class:** EKS architecture problem (knowledge engineering) · **Source:** two same-day incidents, ARB-classified.

## Problem

The programme has governance knowledge, but the runtime does not consistently propagate the current governed knowledge to every execution role:

```
Governance knowledge → stored in repository → ? discovery ? → role/session working context
```

The `?` is unreliable. **Recording a rule is not sufficient. The responsible session must reliably discover and apply the current rule** (ARB wording, 2026-08-16).

## Evidence

1. **Placement-rule recurrence (2026-08-16):** the fix-forward placement convention was approved, registered and ARB-strengthened — and the very next Architecture lane wrote four documents to the old location within the hour, taught the old path by its workflow record's `tokenRef`. The convention lived in a README the lane had no reason to open.
2. **Governance-window divergence (2026-08-16):** two concurrent Governance processes created a duplicate work item for one commission (same grant ID issued twice), and later double-registered one approval act — different windows operating on different views of the same governed state.
3. Registered analysis: `docs/knowledgeos/reviews/2026-08-16-knowledge-placement-requirement-registration.md` (incl. addendum).

## Requirement (candidate — what a solution must satisfy)

> **Operational rules that affect how a session operates must be discoverable by the responsible session at startup and must not depend on inherited precedent.** Inherited path precedent must never override derived placement.

Applies to: workflow rules · business language · role separation · approval semantics · attribution rules · assurance requirements · documentation placement.

## Scope when commissioned

Architecture-first: determine how governed rules reach a session's working context (startup injection? a rule manifest the startup check reads? record-carried routing like `AMD2`?) — consistent with P-5 (advisory/factual-prerequisite boundary) and the zero-new-hooks posture unless evidence justifies otherwise.

## Not in scope

No mechanism is designed by this ticket · no enforcement built · no change to the running workflow engine (qualified and closed; own authorization path) · not bundled into `KOS-ATTR-ARCH-001` (different question; that lane continues undisturbed).

## Dependencies / relations

`KOS-GOV-ATTRIBUTION-001` (the root actor-identity gap is adjacent, not identical) · EKS-02 (placement is one instance of this general problem) · ES-001.3 (Governance's detection duty is the current manual mitigation).
