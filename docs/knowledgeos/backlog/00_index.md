# KnowledgeOS / EKS Backlog — Index

Backlog items for the EKS / KnowledgeOS platform. **A backlog item records a problem and a candidate requirement; it commissions nothing** — starting any item requires a Human/PO/ARB authorization act, routed through Governance.

| ID | Title | Status | Class |
|---|---|---|---|
| [EKS-01](EKS-01-governed-knowledge-distribution.md) | Governed-Knowledge Distribution — rules must reach the session at startup | BACKLOG | architecture problem |
| [EKS-02](EKS-02-subject-derived-placement-enforcement.md) | Subject-Derived Placement, machine-enforceable (Knowledge Space) | BACKLOG | architecture capability |
| [EKS-03](EKS-03-historical-document-relocation.md) | Governed relocation of historical misplaced documents | BACKLOG · low urgency | governed cleanup |
| [EKS-04](EKS-04-lifecycle-handoff-ambiguity.md) | Four-Session Lifecycle Handoff Ambiguity — HANDOFF vs COMPLETE | BACKLOG | operating-model problem |
| [EKS-05](EKS-05-knowledge-evidence-boundary.md) | Knowledge Evidence Boundary — separating Execution State from Governance Evidence | **FUTURE ARCHITECTURE EXPLORATION** | strategic-DDD boundary problem |
| [EKS-06](EKS-06-reference-register-identifier-families.md) | Reference resolution bound to a fixed token list, not to declared identifier families | BACKLOG | assurance-coverage problem |
| [EKS-07](EKS-07-multi-process-coordination.md) | Multi-Process Coordination & Shared Work-State Integrity for AI Engineering | **FUTURE ARCHITECTURE EXPLORATION / OBSERVED PROBLEM** | platform-capability / coordination-boundary problem |
| [EKS-08](EKS-08-representation-semantics-separation.md) | Representation–Semantics Separation & Measurement Independence — KnowledgeOS invariants evaluated against semantic structure, not representation heuristics | **FUTURE ARCHITECTURE EXPLORATION** (P4/P5 research observation) | candidate constitutional invariant (research) |
| [EKS-09](EKS-09-activation-write-atomicity.md) | Activation write atomicity — a multi-append governed sequence has no transaction boundary | BACKLOG · ARCHITECTURE | architecture problem (transaction boundary) |
| [EKS-10](EKS-10-activation-failure-path-coverage.md) | Activation failure-path coverage — the live-owner combination is unprotected by a committed test | BACKLOG · ENGINEERING | assurance-coverage problem |
| [EKS-11](EKS-11-sole-writer-environment-override.md) | The "sole writer" is redirectable by environment — an asymmetric, unbounded test seam | **BACKLOG · HELD BY STANDING DECISION** | architecture problem (assurance boundary) |
| [EKS-12](EKS-12-theory-governance-scope-gap.md) | No governance authority has evidenced scope over **KnowledgeOS theory** | ⛔ **CLOSED · WORKING AS DESIGNED** (2026-09-08) | governance-scope problem |
| [EKS-13](EKS-13-cross-lane-dependency-without-change-notification.md) | A conclusion in one lane depends on another lane's document, with no change notification | **BACKLOG · OPERATIONAL EXPOSURE** | cross-lane dependency / notification gap |
| [EKS-13](EKS-13-out-of-root-evidence-admission-mechanism.md) | No formal mechanism for admitting out-of-corpus-root evidence into a research programme — three instances so far, each reinvented from scratch | BACKLOG | operating-model problem |

Created 2026-08-16 on the PO/ARB act *"record this as problem and write EKS- tickets"*, from the placement-drift and knowledge-distribution incidents (`../reviews/2026-08-16-knowledge-placement-requirement-registration.md`).


**EKS-09/10/11 added 2026-08-24** on the PO/ARB follow-up-disposition act, from the `AST-019` `REPAIR-001` re-verification findings (`RV-F1`, `RV-F2`, `F-5`/`RV-O3`). All three were classified **NON-BLOCKING** and are **carried by**, not contradicted by, the `AST-019` adoption and authorization of the same date. `EKS-09` is adjacent to `EKS-07` and records first-hand corroborating evidence for it; it is kept separate because the remedy space differs (atomicity vs. cross-process awareness) — see `EKS-09` §4 for the `ES-005.4` check.

**EKS-13 added 2026-09-08** (filed as `EKS-12`, renumbered same day after a concurrent session
independently filed its own, unrelated `EKS-12` — see row above — using the same next-available-
number convention at the same time; no external reference to the old number existed yet) from the
`three_model_convergence` research programme's own MD-034 characterization study — a recurring
operating-model gap (no reusable admission procedure for out-of-corpus-root evidence), independently
converged on across three separate instances (`03`/`04`, `06`, and a recommended
`12-randomized-results.md`). Checked against `EKS-06` before filing (`ES-005.4`, never a copy) — a
different problem in a different subsystem (a research-corpus admission workflow vs. `EKS-06`'s
Track-2 intra-document reference checker), not a duplicate.


**EKS-12 added 2026-09-08** by Lane T (theory extraction) under the operating model's own **§37** — *"when a deeper requirement is discovered: record it as a follow-up and STOP"*. It arose from the `P-42`–`P-45` adoption/authority audits, which found a real and exercised adoption mechanism but **no authority with evidenced scope over KnowledgeOS theory**. ⛔ **It proposes no authority and may legitimately be closed as *working as designed*** — see `EKS-12` §5.

**EKS-12 CLOSED 2026-09-08** on the human research owner's act — *"It is a deliberate boundary — close EKS-12 as working as designed"*. The absence of a governance scope over KnowledgeOS theory is **intended**, joining `ES-006`'s deliberate exclusion of Project Knowledge as a second reasoned boundary. ⭐ **Consequence: KnowledgeOS theory is permanent research by design; the governed boundary sits at the specification/asset line.** ⛔ The closure commissions nothing and makes no statement about the theory's correctness.

**EKS-13 added 2026-09-08** from `P-47`. A load-bearing conclusion in the persistence theory depends on a single sentence in a document owned by the *other* research lane, and the intake record's own `X3` warning — *"no mechanism propagates that to Lane T"* — is currently the only safeguard. ⭐ The lanes' independence is deliberate and must be preserved; ⛔ this item proposes **no** mechanism.
