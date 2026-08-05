# EPIC-004B Adjudication Aggregate Discovery

**Kind:** Tactical DDD artifact №1 (per the ARB execution rule: one artifact → review → refine → freeze → next). **This artifact identifies aggregate CANDIDATES — it declares no aggregates.** *(Refined per ARB review, 2026-07-25: the original draft prematurely declared "discovered aggregates"; every such claim is corrected to "candidate." The declaration itself belongs to the next artifact.)*
**Sequence amendment (ARB, 2026-07-25):** the artifact sequence gains one step — **Aggregate Discovery → Aggregate Evaluation & Selection → Aggregate Boundaries → Responsibilities → Invariants → VOs → Domain Events → Commands → Repositories → Domain Services.** Artifact №2 is now *Evaluation & Selection*: it tests every candidate against DDD aggregate criteria and decides which become aggregates.
**Authorization:** ARB, 2026-07-25 — Tactical Discovery closed; Aggregate Discovery authorized as the first Tactical DDD artifact.
**Binding constraints:** the frozen work package (EPIC-004, incl. subset→extend, no rename) · the four Constitutional Policies · K1/P2 (adjudication never automatic) · ADR-T1/T11/T14/T19. Q-1 (authority) and Q-2 (window terms) remain open; nothing below forecloses either.

---

## Discovery rule (corrected per ARB)

> **An aggregate exists where the business requires one consistency boundary that must be protected atomically.** Lifecycle, ownership, and volatility are *evidence*; the consistency boundary is the *decision* — and that decision is artifact №2's to make, not this document's.

Accordingly, this artifact records, per candidate: the candidate · its evidence · the reasons it may warrant aggregate status · its open questions. Nothing is retained, nothing is rejected, no collaboration is described, no guardianship or transaction shape is assigned.

## Candidate 1 — Determination

- **The candidate:** the exactly-once, content-fixed-at-issue, binding ruling record for one challenge.
- **Evidence:** operates as an aggregate in production-grade code today — Draft→Issued→Final lifecycle, one-determination-per-challenge enforced at service and database level, ruling content fixed once in the immutable event (ADR-T19 Model B); thoroughly tested and architecture-qualified.
- **Reasons it may warrant aggregate status:** the uniqueness rule and the fixed-at-issue property look like a genuine atomic-consistency obligation over the ruling record; the existing implementation treats them as exactly that.
- **Open questions (for Evaluation & Selection):** whether the extension of the context changes what this record must protect atomically; whether its existing shape survives the arrival of the judgment concern unchanged. Its production standing is strong evidence, not a verdict.

## Candidate 2 — AdjudicationProceeding *(working name; review-open)*

- **The candidate:** the deliberative process for one routed challenge — from the contested matter being taken up for judgment, through evidence assembly and sufficiency assessment, to one of two endings: a ruling is issued, or failure is declared (insufficient evidence — the COL-1 refusal power exercised).
- **Evidence:** the judgment process demonstrably has its own lifecycle, distinct from the ruling record's — a proceeding is open long before any ruling exists and may end *without* one; it changes at review-time pace versus the record's issue-instant pace; the strategic record's declare-failure obligation (ES) and differentiated-scrutiny echo (AL/CL) attach to the *process*, not the record; K1/P2 demand the process receive its conclusion from an authority, never compute it.
- **Reasons it may warrant aggregate status:** the set of facts "what evidence has been admitted to this judgment, what has been demanded and not supplied, whether the proceeding may conclude" *may* constitute a consistency boundary requiring atomic protection.
- **Open questions (for Evaluation & Selection — explicitly undecided):** whether this concern is an **aggregate at all** — it could instead prove to be a Policy, a Process Manager, a Saga, a Domain Service, a workflow, **or remain inside Determination**. Lifecycle evidence alone does not settle this; the consistency test does. Also open: its relationship to Candidate 1 in every respect (including transactional shape) — deliberately not described here.
- **Naming evidence (for whenever the concept is ratified in any form):** *AdjudicationProceeding* (judicial register, consistent with Determination/Jurisdiction/IssuedByAuthority) · *AdjudicationCase* (workable; collides with safety-assurance "case" vocabulary in the strategic record) · *Judgment* (names the outcome, not the process).

## Further candidates — deferred for evaluation (NOT rejected; evidence recorded, verdicts withheld)

*(Refined per ARB: the original draft classified these as non-aggregates — too early. They have not failed; they have not yet been evaluated. The observations below are evaluation inputs, not conclusions.)*

- **Declare-failure** — evidence suggests it is an *outcome* of the proceeding concern rather than a thing with its own consistency obligation; whether it needs any independent protected state is Evaluation's question.
- **Evidence body / evidence envelope** — evidence suggests upstream ownership (Collection & Aggregation, strategically; the COL-1 Customer–Supplier line); whether Adjudication needs any locally-consistent evidence-tracking state of its own — and whether that state belongs to Candidate 2 or elsewhere — is Evaluation's question.
- **Integrity Quarantine** — per the ARB's Q-3 reclassification, its *arrival path* is collaboration material; whether any quarantine-adjacent state lives inside this context at all is Evaluation's question, with current evidence pointing toward the publication side.
- **Authority** — deferred pending Q-1 by ARB ruling; unforecloseable here by construction.
- **Superseding Constitutional Publication** — current evidence places it as a consumer of this context's output; Evaluation may confirm or complicate that.

## What this discovery hands to artifact №2 (Aggregate Evaluation & Selection)

For **every** candidate above, Evaluation & Selection answers the ARB's six criteria before anything is declared an aggregate:

1. What consistency does it protect?
2. What invariants does it own?
3. Can it change independently?
4. Can it fail independently?
5. Must it commit atomically?
6. Does it own transactional consistency?

Accepted and rejected candidates are recorded there with rationale; alternatives are preserved until sufficient evidence exists.

---

**Self-review (post-refinement):** no aggregate is declared anywhere — every concept is a candidate ✅ · no candidate is rejected — the five deferred items carry evidence, not verdicts ✅ · no collaboration, guardianship, or transaction shape is described (the original draft's "proceeding never writes the ruling record" statement is withdrawn to artifact-№2/№3 territory) ✅ · the discovery rule is the consistency rule, with lifecycle demoted to evidence ✅ · Q-1/Q-2 unforeclosed; Q-3's reclassification respected ✅ · restraint preserved (no Evidence/Authority/Quarantine aggregates invented) ✅.

**Stop condition: STOP.** Artifact №1 complete in refined form. Await ARB review → freeze; artifact №2 (**Aggregate Evaluation & Selection**) begins only after this freeze.

---
*Frozen charter: `EPIC-004_Adjudication_Tactical_Work_Package.md` · Closed discovery: `EPIC-004A_Adjudication_Tactical_Discovery.md` (accepted with rulings) · Constitutional inputs: EPIC-002 baseline, EPIC-003 §THE FOUR DECISIONS, ADR-T1/T11/T14/T17/T19 · Refinement record: ARB review 2026-07-25 (8.2/10 → candidates-not-aggregates correction; sequence amended to insert Evaluation & Selection).*
