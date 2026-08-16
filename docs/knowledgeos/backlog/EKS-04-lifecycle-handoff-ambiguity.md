# EKS-04 — Four-Session Lifecycle Handoff Ambiguity

**Status:** BACKLOG — recorded on the PO/ARB act 2026-08-16 (*"Record the four-session handoff ambiguity as a candidate architecture/governance problem, but do not change the current Architecture work"*). **Not commissioned; starting requires a human authorization act.**
**Class:** four-session operating-model problem (candidate EKS / governance architecture problem).

## Problem — the PO's business statement, verbatim

> **The operating model does not clearly distinguish "work finished and handed to the next role" from "session formally closed." This can delay or prevent independent verification even when the producing role has completed its work.**

## Business consequence

> A completed Architecture result can exist while the independent verification cannot start, because the lifecycle interpretation is unclear.

## Evidence (2026-08-16, `KOS-ATTR-ARCH-001`)

Architecture finished its Stage-1 design, prepared the independent-review package, and declared itself disqualified from reviewing its own work. Governance created the Verification assignment. The handover then stalled on a wrong interpretation — *"Architecture is ACTIVE, therefore it must COMPLETE before the work can go to Verification"* — until the Architecture session itself checked the workflow and found the correct reading:

| Transition | Business meaning |
|---|---|
| **HANDOFF** | *"I have finished my part; the next role should take over."* Session state → `HANDED_OFF`. The lane may have future work (here: Stage 2, gated on Phase A acceptance). |
| **COMPLETE** | *"This session is formally closed."* |

The verification lane sat CREATED — *"the work exists, but nobody has officially handed it to me yet"* — until the handoff was recorded (seq 5).

**Also evidence FOR the model, recorded for fairness:** the stall was the separation *working* — Architecture could not verify itself, Verification could not start without the handover and the Human START, and the Human stayed in control. The defect is in the *legibility* of the lifecycle, not in its gates.

## Relation to EKS-01 — recorded, not merged

Both are examples of *governed knowledge and governed work not being reliably transferred into the next role's working context.* **Deliberately not bundled into EKS-01 yet** (PO instruction): later Architecture determines whether this is a separate problem or another instance of the same underlying distribution/lifecycle problem.

## Not in scope

No change to the running workflow engine · no change to `KOS-ATTR-ARCH-001` or its verification (evidence preservation: *current design → independent verification → Human/ARB decision → only later improve the four-session workflow*) · no fix designed by this ticket.

## Dependencies / relations

EKS-01 (candidate same-class) · `workflow-state.php` HANDOFF/COMPLETE semantics (`:130–152`) · the `KOS-ATTR-ARCH-001` seq-5 handoff token, which now carries the correct lifecycle meaning in the record itself.
