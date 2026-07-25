# EPIC-004B Adjudication Aggregate Discovery

**Kind:** Tactical DDD artifact №1 of 9 (per the ARB execution rule: one artifact → review → refine → freeze → next). **This artifact answers exactly one question: WHICH aggregates does the extended Adjudication context need, and why.** Aggregate *boundaries* (artifact 2), *responsibilities* (3), *invariants* (4), value objects, events, commands, repositories, and services are downstream artifacts — deliberately not elaborated here.
**Authorization:** ARB, 2026-07-25 — Tactical Discovery closed; Aggregate Discovery authorized as the first Tactical DDD artifact.
**Binding constraints:** the frozen work package (EPIC-004, incl. subset→extend, no rename) · the four Constitutional Policies · K1/P2 (adjudication never automatic) · ADR-T1 (one transaction = one aggregate root) · ADR-T11 (anonymity) · ADR-T14 (Determination is the sole aggregate written by issuance) · ADR-T19 (state-based aggregate + event-as-ruling-record). Q-1 (authority) and Q-2 (window terms) remain open; nothing below forecloses either.

---

## Discovery method

An aggregate exists where the business has a **consistency obligation with its own lifecycle** — a cluster of facts that must change together, under one guardian, at one pace. Each candidate below is tested against: (a) does the business evidence show such an obligation? (b) does it have its own lifecycle and volatility? (c) would merging it into another aggregate force two paces of change under one guardian? Candidates that fail become non-aggregates (recorded, with reasons).

## Aggregate 1 — Determination *(existing; retained unchanged)*

- **What it guards:** the exactly-once, content-fixed-at-issue, binding ruling record for one challenge.
- **Reason to exist (evidence):** proven in production-grade code and tests — Draft→Issued→Final lifecycle, one-determination-per-challenge (service guard + DB uniqueness), ruling content fixed once in the immutable event (ADR-T19 Model B). Its consistency obligation is *record integrity*, not judgment.
- **Discovery decision:** **retained as-is.** The subset→extend ruling means the extension happens *around* this aggregate, not inside it. Nothing discovered in EPIC-003/004A gives any reason to reshape a record-keeping aggregate that already satisfies its obligations.

## Aggregate 2 — AdjudicationProceeding *(new — the judgment half)*

- **What it guards:** the deliberative process for one routed challenge — from the moment the contested matter is taken up for judgment, through evidence assembly and sufficiency assessment, to exactly one of two outcomes: a ruling is issued (via Aggregate 1), or **failure is declared** (insufficient evidence — the COL-1 customer's refusal power exercised).
- **Reason to exist (evidence):**
  - The judgment process has its **own lifecycle**, distinct from the ruling record's: a proceeding is *open* long before any ruling exists and may end *without* one (declare-failure) — a lifecycle the Determination aggregate cannot and should not express (its docblock: ruling content is decided upstream).
  - It has its **own consistency obligation**: what evidence has been admitted to *this* judgment, what has been demanded and not yet supplied, and whether the proceeding may conclude — facts that must change together under one guardian, at review-time pace (versus the record's issue-instant pace).
  - It is where **K1/P2 becomes structural**: the proceeding can assemble, track, and demand — but concluding it is a decision the aggregate must *receive from* an authority, never compute. (How the authority is modeled awaits Q-1; the proceeding refers to it opaquely meanwhile, exactly as `IssuedByAuthority` does today.)
  - The strategic record's differentiated-scrutiny echo (AL/CL) and declare-failure obligation (ES) both attach *here* — to the process — not to the ruling record.
- **Working name:** **AdjudicationProceeding** (judicial register, consistent with the context's existing vocabulary — Determination, Jurisdiction, IssuedByAuthority). Alternatives considered: *AdjudicationCase* (workable; "case" collides with safety-assurance "case" vocabulary from the strategic record), *Judgment* (names the outcome, not the process). Name is review-open.
- **Relationship to Aggregate 1 (discovery-level only):** one proceeding concerns one challenge; a concluded-with-ruling proceeding leads to exactly one Determination. Per ADR-T1/T14, they are separate transactional units — the proceeding never writes the ruling record in its own transaction. *Precise boundary and interaction shape: artifact 2.*

## Considered and NOT discovered as aggregates (recorded, with reasons)

- **Declare-failure** — an *outcome* of the proceeding, not a thing with its own consistency obligation. It is recorded as a fact when the proceeding concludes that way. Making it an aggregate would manufacture a guardian with nothing to guard.
- **Evidence body / evidence envelope** — the proceeding *references* evidence (opaquely today, per `EvidenceEnvelopeRef`); the evidence itself is owned upstream (Collection & Aggregation, strategically). An Adjudication-side evidence aggregate would re-own another context's subject matter across the COL-1 boundary — exactly what the Customer–Supplier pattern exists to prevent.
- **Integrity Quarantine** — Policy 4's controlled pending state lives where publication lives, not in Adjudication; the quarantine's *consequence* (a challenge, then a proceeding) arrives through the collaboration path. Per the ARB's Q-3 reclassification, this is Tactical Collaboration material — out of this context's aggregate set.
- **Authority** — deferred pending Q-1. Until ruled, authority remains an opaque reference (as today); if Q-1 later yields an authority model, its home (Governance donor vs. local) is decided then — nothing here forecloses it.
- **Superseding Constitutional Publication** — Policy 1's mechanism is a *consumer* of this context's output (COL-5b/publication side), not an Adjudication aggregate.

## What this discovery hands to artifact 2 (Aggregate Boundaries)

The open boundary questions this artifact deliberately leaves: where exactly the proceeding's evidence-tracking stops and Collection's supply begins (the COL-1 contract line) · whether proceeding-conclusion and determination-issuance are one business moment in two transactions or two moments (ADR-T1 shape) · what "one proceeding per challenge" means for re-raised or conflicting challenges (interacts with the existing one-determination-per-challenge rule) · the proceeding's terminal states.

---

**Self-review:** exactly one question answered (which aggregates, why) ✅ · two aggregates discovered, one retained + one new, each with an evidence-based consistency obligation ✅ · five non-aggregates recorded with reasons ✅ · no boundaries drawn, no responsibilities assigned, no invariants formalized, no VOs/events/commands/repositories/services designed ✅ · Q-1/Q-2 unforeclosed; Q-3's reclassification respected (quarantine excluded as collaboration material) ✅ · K1/P2 honored structurally (the proceeding receives conclusions, never computes them) ✅.

**Stop condition: STOP.** Artifact 1 of 9 complete. Await ARB review → refine → freeze; artifact 2 (Aggregate Boundaries) begins only after this freeze.

---
*Frozen charter: `EPIC-004_Adjudication_Tactical_Work_Package.md` · Closed discovery: `EPIC-004A_Adjudication_Tactical_Discovery.md` (accepted with rulings) · Constitutional inputs: EPIC-002 baseline, EPIC-003 §THE FOUR DECISIONS, ADR-T1/T11/T14/T17/T19.*
