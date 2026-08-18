# `EM-DOM-001` — Domain lane briefing (Phase 1 only)

**Prepared by:** Session 2 — Governance · 2026-08-18 · **Authorization:** PO/ARB, recorded verbatim in `2026-08-18-EM-DOM-001-authorization-request.md`
**Lane status:** ⏸️ **NOT DESIGNATED, NOT STARTED.** This briefing waits for the lane the PO/ARB designates. **It is not a START.**

> ⚠️ **Governance authored this briefing and deliberately authored NO MODEL.** No candidate concept · no class name · no aggregate proposal · no field · no signature · no enum. **If you find one in this document, treat it as a defect and report it** — a Governance-suggested shape would pre-empt the very ownership analysis the slice exists to perform.

## Read these first, in this order

| # | Artifact | Why |
|---|---|---|
| 1 | `docs/publicdigit/adr/ADR_20260817_2145_Aggregate_Absence_Semantics.md` §6 | ADR-1, DECIDED — governed composite absence semantics; **binding constraint ⑥** |
| 2 | `docs/publicdigit/adr/ADR_20260817_2300_Recovery_Origin_Provenance_Ownership.md` §6 | ADR-2, DECIDED — the **causal-model** ruling, (a)–(h). **It is not an Option A/B/C/D choice; do not read it as one** |
| 3 | `docs/publicdigit/reviews/2026-08-18-EM-IMPL-002-rule8-gate-adr2.md` | the gate + the **A/B/C matrix** (A=2 · B=6 · C=6). This is the evidence, already accepted |
| 4 | `docs/publicdigit/reviews/2026-08-18-EM-IMPL-002-rule8-gate-adr1.md`-equivalent record at `7514f145` | the ADR-1 gate |
| 5 | the authorization request/record, §§1–10 + **PO/ARB Amendment 1** + both annotations | your scope and your obligations. ⚠️ **Obligation (9) as signed is SUPERSEDED — read Amendment 1; `DEP-10` is excluded from the prohibition** |
| 6 | `ELECTION_MANIFESTO.md` — `EM-GOV-059(c)`, `060`, `062`, `EM-GOV-064…066`, `EM-GOV-068` | the adopted business rules the invariants must serve |

## Your mission

**Resolve DEP-1 … DEP-6 as ONE bounded domain-model slice.** All six are class **B** — missing domain concepts or missing domain reachability. **None of them has a legitimate Application-layer solution**; that was the gate's finding, and it is settled.

## Phase 1 deliverables — and Phase 1 stops

**Deliverable 1 — the domain decision map.** For each of DEP-1…DEP-6 answer, in this order: ① what is the invariant? ② which bounded context **owns** it? ③ what domain concept expresses it? ④ what are the **legitimate** states? ⑤ what is **impossible**? ⑥ what must be **captured at the transition**? ⑦ what representation makes those states **explicit**? ⑧ what does UC-3 **consume** afterwards?

It must render the two discriminations in §9 of the authorization record explicitly — absent-reference (*required by invariant* vs *legitimate lifecycle*) and restoration (*Recovery existed* vs *no `RecoveryProcess`*).

**Deliverable 2 — the minimum RED-test slice** that proves the missing invariants.

**Then STOP and report.** ⛔ **No GREEN. No implementation. Reporting the map and the RED proposal is where Phase 1 ends** — review precedes implementation.

## Hard constraints

⛔ **Do not modify:** the Application layer · UC-1/UC-2/UC-3/UC-4 · repositories · protocol access · anything under `app/Contexts/Election/Application/`.
⛔ **Do not add a protocol read.** ⛔ **Do not reconstruct provenance.** ⛔ **Do not invent a technical mechanism.**
⛔ **Do not design around `FillCommitteeSeatHandler`'s current structure.** Its shape is the symptom, not the specification.
⛔ **Do not aim at `AbsentAggregateReferenceRedTest`.** It goes green later as a **consequence**, never as your target (Obligation 5). **You are not asked to make existing tests pass; you are asked to make the approved invariants explicit and testable.**
⛔ **Do not recreate or duplicate P-7** `ResumptionTarget::resolve(HaltedAtGate): GateDesignation` — it exists, it is authorized, you **consume** it (`ES-005.4`; ADR-2 (g): *reference is not ownership*).
⛔ **Do not touch** `if ($restoration === null) { return; }` — **DEP-10 is class A**, grounded in `EM-GOV-062`, and **PO/ARB Amendment 1 (2026-08-18) governs: obligation (9) explicitly EXCLUDES `DEP-10`, and its existing legitimate `RecoveryProcess` absence semantics must be preserved.** ⚠️ **The guard exists at TWO sites, not one** — `FillCommitteeSeatHandler.php:174` **and** `RecordVacancyEventHandler.php:174`, both in `pauseAccruingRestorationAllowance()` (verified 2026-08-18; the gate's `UC-3:172` citation has drifted by two lines). **Preserve both. Normalization means semantic conformity, not syntactic uniformity — protecting one site and normalizing the other would produce exactly the uniformity §7 forbids.**
⛔ **New scope discovered mid-slice → a backlog item and a report, never an extension** (Obligation 6).
⛔ **The domain core is currently byte-identical to `1f4b4c5f`.** Phase 1 changes none of it.

## The three facts most likely to be mis-modelled

1. **DEP-5b is a producer gap, not a concept gap.** `HaltedAtGate` exists; nothing in `app/` produces or persists one; no port or repository carries one; P-7's only call site in the repository is a domain unit test. **The chain exists, is authorized, and is unreachable.**
2. **"Where does restoration return to?" and "why is restoration permitted?" are different questions.** P-7 answers the first. **Nothing answers the second.** Keeping them distinct is a permanent boundary (ADR-2 (a)).
3. **`w8` is not hypothetical.** ⚠️ **CORRECTED 2026-08-18 (finding `V-11`) — the original sentence here was FACTUALLY WRONG and is struck.** It said restoration follows an unachievable condition being resolved *"with no `RecoveryProcess` ever having existed."* **The pinned fixture contradicts that:** `FillCommitteeSeatHandlerRedTest::test_w8_…` **seeds `RecoveryProcess(PeriodKind::CommitteeRestoration, 20)`.** ⇒ **`w8` lacks the prior HALT, not the `RecoveryProcess`** — exactly as **D2** records it. **What stands:** `Restoration → RecoveryProcess → originatingGate` **is still not the universal causal model**, and `ElectionRestored.$returnsToGate` being **non-nullable** is, per ADR-2 (h), **the domain-model gap** — decided *with* this slice, not deferred. ⛔ **Do not build on the struck sentence.** *(A restoration with no `RecoveryProcess` of either kind is structurally reachable but pinned by no test — unpinned, never an accepted case.)*

## Process obligations

Domain **RED committed and verified failing-by-absence BEFORE** GREEN, as **separate commits**, so ordering is git-provable (the `EM-IMPL-002` two-phase discipline) · **independent verification** — you supply evidence and never accept your own work (`EP-02`, `R-34`) · a **developer guide** with the step (repo Definition of Done) · **one story → one commit**, subject carrying its ID.

**Traceability:** the `EM-DOM-001` authorization record · ADR-1 §6 · ADR-2 §6 · `7514f145` · `74fcf5e5` · `c4828cdd` · `b78c50ab` · `f53469cb` · `1f4b4c5f`.

---

# ADDENDUM · **PHASE 2A** briefing — added 2026-08-18 after D1–D4 were recorded

> ⚠️ **Everything above was written for PHASE 1, before the PO/ARB decisions existed. Read this addendum as controlling wherever the two differ.**
> ⏸️ **Still NOT STARTED. No lane designated. This addendum is not a START and confers nothing.**

## What changed

**D1 places Act B IN SCOPE:** creation and definition of a **Domain-owned identity and retrieval contract** for the operational overlay. **D2** clarified `w8` (`Restoration ≠ Resumption`) **and selected no representation**. **D3** deferred `BND-1`. **D4** deferred `BND-3`. **Appendix Q's negative list is LIVE.** Gate result: `2026-08-18-EM-DOM-001-rule8-gate-post-decisions.md` — 🟡 **PARTIALLY UNBLOCKED**.

## Your mission — Phase 2A, **analysis/design + RED-test preparation ONLY**

> ### **Define the Domain-owned identity/retrieval contract required by Act B — without deciding `BND-1` or `BND-3`, and without implementing persistence, adapters, Application consumption, or GREEN-5.**

⛔ **Phase 2A is NOT coding.** The authorization states the lane *"may begin only with domain analysis/design and RED-test preparation."* **Produce the analysis and the RED plan, then STOP for review.**

## The six questions you must answer

1. What **domain meaning** must the contract expose?
2. What facts must be **retrievable** to represent that meaning?
3. What is the **smallest** contract satisfying it?
4. **How does the contract avoid encoding an aggregate/boundary assumption?** *(see the hazard below — this is the hard one)*
5. What **RED test** demonstrates the missing invariant?
6. What remains **impossible** because `BND-1` and `BND-3` are still open?

## The seven questions you must NOT answer

⛔ who owns lifecycle **phase** → `BND-1`, **deferred** · ⛔ what **aggregate** owns the overlay → `BND-3`, **deferred** · ⛔ how **persistence** works → act C · ⛔ how the **Application consumes** it → act D · ⛔ what `ElectionRestored`'s representation ultimately becomes → D2 selects none · ⛔ how **GREEN-5** is repaired · ⛔ whether **existing repositories** should change → act A.

> **You may answer *"what domain contract expresses the already-approved meaning?"* You may NOT answer *"what aggregate should own this?"* Those are different questions, and D1 + D4 together give you only the first.**

## 🔴 The naming / placement hazard — gate finding **G-2a**

**All three files in `app/Contexts/Election/Domain/OperatingCore/Repository/` carry aggregate semantics (`AG-1`/`AG-2`/`AG-3`) in their docblocks.** ⛔ **A fourth `…Repository` placed there would inherit that vocabulary and thereby ASSERT aggregate standing — which D1 ("not … selection of the overlay's aggregate or persistence boundary") and D4 ("must not use D1 as authorization to select the overlay's aggregate boundary") both forbid.**

> ## **The contract must not be named or placed so as to encode a boundary claim.**

**Why it is nonetheless possible:** all three `BND-3` candidates — distinct aggregate · part of a lifecycle aggregate · projection over recorded facts — **share the same retrieval key (`ElectionId`) and the same already-frozen return type.** *(Consistent with ADR-1 §6(c), which deliberately does not prescribe "a repository method".)* **Naming and placement are therefore part of your analysis, not an afterthought.**

## What Act B does and does not reach

| ✅ In | ⛔ Out |
|---|---|
| defining the domain contract | modifying an existing repository interface *(act A)* |
| its domain vocabulary and placement | persistence / adapter *(act C)* |
| a RED test for the missing invariant | Application call sites *(act D)* |
| naming what must be retrievable | a new identity/persistence **model** *(act E = `BND-3`)* |
| | `R-1`'s final form · GREEN-5 · any mechanism beyond the contract |

## Carried forward from Phase 1, unchanged

⛔ **Do not recreate or duplicate P-7** — consume it; never loosen it to accept `null`. ⛔ **Do not touch DEP-10's four guarded sites** *(`FillCommitteeSeat` 174/192 · `RecordVacancy` 161/174)* — class A, `EM-GOV-062`, **and its exclusion from the prohibited set is now ratified by Amendment 1.** ⛔ **Do not aim at `AbsentAggregateReferenceRedTest`** — a consequence, never a target. ⛔ **Do not design around `FillCommitteeSeatHandler`'s shape.** ⛔ **New scope mid-slice → a backlog item and a report, never an extension.**

## ⚠️ Know this before you plan: Act B does not reach GREEN-5

**`ADR-1` §6(c) authorizes ONE normalization slice whose criterion is *"each handler conforms to the governed domain meaning of EVERY absent reference it encounters"*, and it names UC-2 and UC-3 as requiring `AcceptanceDecision` conformance.** That is `DEP-2` → `BND-1` → **deferred.** ⇒ **Completing Act B perfectly still leaves the normalization slice shut and GREEN-5 stopped.** **Plan for a contract, not for a fix.**

## Lane independence — strengthened

⛔ **Obligation 4 now reads more strictly: the sessions that produced the decisions, the ADRs, the architecture reviews and this record must NOT become the lane that interprets those decisions into a model.** **A fresh, independent session.** *(Rationale: the session that made a decision is the worst-placed one to discover what the decision "obviously" implies.)*

## Process obligations

Domain **RED before GREEN**, separate commits, verified failing-by-absence *(git-provable)* · **independent verification** — you supply evidence and never accept your own work (`EP-02`, `R-34`) · a **developer guide** with the step · **one story → one commit** carrying its ID.

**Traceability:** decision surface D1–D4 *(verbatim)* · Appendix Q *(live)* · post-decision Rule-8 gate G-1…G-8 · `EM-DOM-001` authorization + Annotations A/B + Amendment 1 · ADR-1 §6(c) + ⑥ · ADR-2 §6(b)/(f)/(h) · Phase-1 map §§0–5/7/8 *(accepted as analysis)* · `1f4b4c5f`.
