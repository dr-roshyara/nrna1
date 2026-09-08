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
| [EKS-14](EKS-14-research-construct-named-as-a-product-component.md) | A research construct is named as if it were a KnowledgeOS component, and 50 documents use it | **BACKLOG · NAMING & ATTRIBUTION EXPOSURE** | naming / attribution risk |
| [EKS-15](EKS-15-out-of-root-evidence-admission-mechanism.md) | No formal mechanism for admitting out-of-corpus-root evidence into a research programme — three instances so far, each reinvented from scratch | BACKLOG | operating-model problem |
| [EKS-16](EKS-16-derivation-without-consulting-existing-theory.md) | A research lane repeatedly derives conclusions without first checking whether the estate already answered the question — five consecutive occurrences | **BACKLOG · OPERATIONAL EXPOSURE** | operating-model problem (research search discipline) |
| [EKS-17](EKS-17-duplicate-research-roots-split-one-programme.md) | Two folders share the same name at different levels, and one research programme is split across both with no overlap | **BACKLOG · NAVIGATION HAZARD** | operating-model problem (repository navigation / discoverability) |

Created 2026-08-16 on the PO/ARB act *"record this as problem and write EKS- tickets"*, from the placement-drift and knowledge-distribution incidents (`../reviews/2026-08-16-knowledge-placement-requirement-registration.md`).


**EKS-09/10/11 added 2026-08-24** on the PO/ARB follow-up-disposition act, from the `AST-019` `REPAIR-001` re-verification findings (`RV-F1`, `RV-F2`, `F-5`/`RV-O3`). All three were classified **NON-BLOCKING** and are **carried by**, not contradicted by, the `AST-019` adoption and authorization of the same date. `EKS-09` is adjacent to `EKS-07` and records first-hand corroborating evidence for it; it is kept separate because the remedy space differs (atomicity vs. cross-process awareness) — see `EKS-09` §4 for the `ES-005.4` check.

**EKS-15 added 2026-09-08** (filed as `EKS-12`, renumbered three times — to `EKS-13`, `EKS-14`, and
finally `EKS-15` — after the same concurrent session independently filed its own unrelated ticket at
each of `EKS-12`/`EKS-13`/`EKS-14` in turn; see rows above; no external reference to any superseded
number existed yet) from the `three_model_convergence` research programme's own MD-034
characterization study — a recurring operating-model gap (no reusable admission procedure for
out-of-corpus-root evidence), independently converged on across three separate instances (`03`/`04`,
`06`, and a recommended `12-randomized-results.md`). Checked against `EKS-06` before filing
(`ES-005.4`, never a copy) — a different problem in a different subsystem (a research-corpus
admission workflow vs. `EKS-06`'s Track-2 intra-document reference checker), not a duplicate. **Three
independent numbering collisions with the same concurrent session, on the same day, are recorded as
first-hand corroborating evidence for `EKS-07`** (multi-process coordination) — see that ticket's
own note, updated accordingly.


**EKS-12 added 2026-09-08** by Lane T (theory extraction) under the operating model's own **§37** — *"when a deeper requirement is discovered: record it as a follow-up and STOP"*. It arose from the `P-42`–`P-45` adoption/authority audits, which found a real and exercised adoption mechanism but **no authority with evidenced scope over KnowledgeOS theory**. ⛔ **It proposes no authority and may legitimately be closed as *working as designed*** — see `EKS-12` §5.

**EKS-12 CLOSED 2026-09-08** on the human research owner's act — *"It is a deliberate boundary — close EKS-12 as working as designed"*. The absence of a governance scope over KnowledgeOS theory is **intended**, joining `ES-006`'s deliberate exclusion of Project Knowledge as a second reasoned boundary. ⭐ **Consequence: KnowledgeOS theory is permanent research by design; the governed boundary sits at the specification/asset line.** ⛔ The closure commissions nothing and makes no statement about the theory's correctness.

**EKS-13 added 2026-09-08** from `P-47`. A load-bearing conclusion in the persistence theory depends on a single sentence in a document owned by the *other* research lane, and the intake record's own `X3` warning — *"no mechanism propagates that to Lane T"* — is currently the only safeguard. ⭐ The lanes' independence is deliberate and must be preserved; ⛔ this item proposes **no** mechanism.

**EKS-14 added 2026-09-08** from `P-50`, which measured that *"persistence kernel"* appears **zero** times in the source corpus while `the Kernel` appears in 610 files — the object is the research lane's own assembly of corpus-witnessed obligations. ⭐ The eleven obligations are evidence-backed; the **name** reads as a product component. ⛔ No rename is proposed; urgency is low today but rises with the first downstream artifact that cites it.

**EKS-16 added 2026-09-08** from `P-54`. Registered for a **pattern, not an incident**: five consecutive theory-extraction reviews reached conclusions and only afterwards found that the estate already held material bearing directly on the question — most seriously a **3 768-line** estate document on *"what information must survive persistence"*, dated **one day before** the two-day persistence derivation began and never opened. ⭐ Three of the five were caught by the human research owner or by chance, so **detection is currently by luck**. ⛔ The item proposes **no** procedure and questions **no** finding; the candidate direction is only that a research derivation should record the result — including a negative result and its scope — of having searched the estate first, mirroring the canonical-discovery step the engineering operating model already requires. Checked against `EKS-13`, `EKS-14`, `EKS-15` and `EKS-07` before filing (`ES-005.4`, never a copy) — distinct on all four counts; see `EKS-16` §6.

**EKS-17 added 2026-09-09** from `P-55`. Two folders are both named `research` — one at the repository root (253 files), one under the knowledge area (69) — and the **kernel-reduction** programme is split across both with **zero files in common**: the executable study on one side, the twenty written documents on the other. ⭐ A search of the wrong half does not fail; it returns results, so nothing signals that anything is missing, and *"check the research folder"* has two correct readings. ⚠️ It fired once already — `P-54` published a coverage statement narrower than it read — with the conclusion unaffected only because the missed material fell outside its date window. ⛔ The item claims **neither location is wrong** and proposes **no move**; the candidate direction is only that each half should say the other exists. Checked against `EKS-16`, `EKS-02`, `EKS-03` and `EKS-06` before filing (`ES-005.4`, never a copy) — distinct on all four; see `EKS-17` §5.
