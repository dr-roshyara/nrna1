# EPIC-004C Adjudication Aggregate Evaluation

**Kind:** Tactical DDD artifact №2 (one artifact → ARB review → refine → freeze → next). **Structure per ARB process refinement (2026-07-25):** the previously separate "Evaluation & Selection" and "Boundaries" steps are **merged** — you cannot evaluate a candidate whose boundary is unknown, and you don't evaluate a vacuum. For every candidate: **Section A — Tentative Boundary Sketch** (a hypothesis, not a finalized boundary), then **Section B — Evaluation** against the six ARB criteria.
**Governance wording (binding, per ARB):** this artifact **recommends** aggregate selection. **The ARB decides.** Nothing below is a declaration.
**Predecessor:** `EPIC-004B_Adjudication_Aggregate_Discovery.md` — FROZEN as refined (ARB re-review 9.6/10; remaining concerns were process-level and are implemented by this artifact's structure).
**Refinement (ARB review, 2026-07-25):** **Criterion 7 — Natural Business Decision Ownership** — added; every candidate re-evaluated against all seven criteria; confidence recalibrated; where multiple realizations remain equally plausible, both are preserved as explicit alternatives rather than forced into one recommendation. The original draft let Criterion 1 dominate — consistency alone does not settle *where* consistency is enforced (dedicated aggregate · existing aggregate · transactional application service · optimistic concurrency · event sourcing · process manager); Criterion 7 asks the question that discriminates: *is this the business's natural owner of the decision?*
**Binding constraints:** frozen work package (subset→extend, no rename) · the four Constitutional Policies · K1/P2 · ADR-T1/T11/T14/T19 · Q-1/Q-2 open and unforeclosed.

---

## Candidate 1 — Determination

### A. Tentative Boundary Sketch *(hypothesis)*

**Inside:** determination identity · lifecycle state (Draft → Issued → Final) · the opaque references the ruling attaches to (challenge, evidence envelope, contested outcome, issuing authority, jurisdiction). Ruling content (outcome, legitimacy, reason) **passes through at issue-time into the immutable event and is not retained as aggregate state** (ADR-T19 Model B — an existing, proven boundary choice).
**Outside:** challenge lifecycle (Contestation's) · evidence content (upstream) · authority validity (Q-1) · the correction and its publication (downstream consumers).

### B. Evaluation

| # | Criterion | Assessment |
|---|---|---|
| 1 | What consistency does it protect? | The ruling record's integrity: exactly-once issuance per challenge; content fixed at the instant of issue; lawful lifecycle transitions only |
| 2 | What invariants does it own? | One determination per challenge · no event after Final · issue happens once, from Draft only · required references non-empty |
| 3 | Can it change independently? | Yes — it evolves for record/catalog/schema reasons (proven: the v1→v2 payload evolution touched nothing else) |
| 4 | Can it fail independently? | Yes — issuance can be refused (duplicate) without any other state corrupting |
| 5 | Must it commit atomically? | Yes — the state transition and the ruling-bearing outbox event commit in one transaction (implemented; ADR-T1/T14) |
| 6 | Does it own transactional consistency? | Yes — it is the sole aggregate written in its transaction, by ADR ruling and by code |
| 7 | **Is this the natural business decision owner?** | **Yes, strongly** — "the binding ruling on a contested outcome" is canonical vocabulary (Canonical Event Catalog; determinations table; every consumer names it); domain experts in the evidence base speak of determinations as first-class decisions with identity; users reason about "the determination" independently |

**Recommendation: AGGREGATE — confirm the existing implementation unchanged. Confidence: HIGH.** All seven criteria answered affirmatively by production and vocabulary evidence, not speculation. *(ARB decides.)*

## Candidate 2 — AdjudicationProceeding *(working name)*

### A. Tentative Boundary Sketch *(hypothesis — explicitly a sketch)*

**Inside:** proceeding identity · the one challenge it concerns · the set of evidence *admissions* (opaque references to upstream-owned evidence, admitted into *this* judgment) · demands issued and outstanding (evidence demands; the declare-failure demand when exercised) · proceeding state (opened → under review → concluded) · the conclusion — exactly one of *ruling-requested* or *failure-declared* — together with the authority reference that made it.
**Outside:** evidence content and its custody (upstream, COL-1/COL-3a) · the ruling record itself (Candidate 1) · challenge lifecycle (Contestation) · authority validity and appointment (Q-1) · scrutiny standards as policy content (they govern the proceeding; they are not its state).

### B. Evaluation

| # | Criterion | Assessment |
|---|---|---|
| 1 | What consistency does it protect? | The deliberation's integrity: **a conclusion must atomically fix the evidence-set-as-considered** — what was concluded and what it was concluded *from* must never be separable or revisable after the fact (this is contemporaneity/P3 applied to the judgment itself, and the audit basis for any later challenge *to* a determination — TargetType includes Determination) |
| 2 | What invariants does it own? | No conclusion without an authority's decision (K1 made structural — the proceeding receives, never computes) · no admission after conclusion · exactly one conclusion, of exactly one kind · one active proceeding per challenge (candidate — mirrors and upholds Candidate 1's uniqueness rule from the process side) |
| 3 | Can it change independently? | Yes — procedural rules evolve at policy pace, independent of the record format and of upstream evidence mechanics |
| 4 | Can it fail independently? | Yes — a proceeding can conclude in declare-failure, or lapse, with no determination ever existing |
| 5 | Must it commit atomically? | The conclusion must — conclusion + fixed-evidence-set + authority-ref are one atomic fact. Admissions and demands are individually small but must be consistent with state (no admission after conclusion) |
| 6 | Does it own transactional consistency? | **An honest tension.** FOR: criterion 1's conclude-atomically-fixes-considered-evidence is a genuine multi-fact atomic rule that only a guarded consistency boundary can enforce. AGAINST: if admissions are mere appends of opaque refs, a Process Manager coordinating simple steps (with an event log) could arguably suffice |
| 7 | **Is this the natural business decision owner?** | **Partially, and honestly uncertain — this criterion changed the recommendation.** The deliberative process is real in the wider domain (AL/CL's judicial-review family names cases and proceedings as first-class concepts). But **this project's own recorded ubiquitous language does not name it**: the vocabulary has Challenge, Determination, Authority, evidence — "proceeding" was coined in artifact №1, not discovered in the business language. Decisive sub-question (the ARB's dichotomy): the recorded evidence — `IssuedByAuthority`, "decided upstream," the EAC third-party-review finding — leans toward **"the constitutional authority issues a determination"** rather than "the proceeding itself concludes." On that reading the proceeding is a *record of deliberation serving the authority's decision*, not the decision's owner |

**Recommendation (recalibrated): AGGREGATE CANDIDATE — Confidence: MEDIUM. The Process-Manager alternative REMAINS VIABLE — Confidence: MEDIUM. Both preserved as explicit alternatives; neither forced.** Criterion 1 still argues for a guarded boundary (the conclude-time fixation needs *some* enforcement home); Criterion 7 now argues the decision's natural owner may be the Authority, making the proceeding record-of-deliberation rather than decision-owner — a different character even if it stays an aggregate. **Discriminator identified: Q-1.** The open authority-model question is precisely what settles Criterion 7 here — when the business defines who the authority is and how it relates to deliberation, the evidence will say whether the proceeding is a named business concept or an internal record. The weaker alternatives (Policy / Saga / Domain Service / inside-Determination) remain weaker for the reasons already recorded. *(ARB decides — and may reasonably defer this specific decision until Q-1 is answered.)*

## Deferred candidates — sketched and evaluated *(none silently dropped)*

- **Declare-failure.** *Sketch:* no state beyond the conclusion-kind and the outbound demand (COL-5a). *Evaluation:* protects no consistency of its own; its invariants live inside Candidate 2. *C7:* the business speaks of it as an **obligation** ("never certify on insufficient evidence"), not an independent decision owner. **Recommendation: NOT an aggregate — a conclusion kind. Confidence: HIGH.** *(ARB decides.)*
- **Evidence body / envelope.** *Sketch:* inside Adjudication, only opaque admission references exist — content, custody, and integrity are upstream-owned (COL-1/COL-3a). *Evaluation:* an Adjudication-side evidence aggregate would own no consistency that admission-tracking doesn't already cover, and would re-own Collection's subject across the C–S line. *C7:* the business names evidence, but its decision ownership sits upstream. **Recommendation: NOT an Adjudication aggregate. Confidence: HIGH.** *(ARB decides.)*
- **Integrity Quarantine.** *Sketch:* the quarantine state attaches to a *publication* — outside this context. *Evaluation:* whatever guards quarantine belongs where publication lives; its consequence arrives via collaboration (per the Q-3 reclassification). *C7:* notably, Policy 4 DOES name quarantine as a business concept — it will deserve first-class treatment *wherever publication is tackled*; just not here. **Recommendation: NOT an Adjudication aggregate — out of context. Confidence: HIGH.** *(ARB decides.)*
- **Authority.** *Sketch:* impossible without Q-1's business answer — that is itself the finding. *Evaluation:* deferred; remains an opaque reference (as today). *C7:* **Criterion 7 IS Q-1 for this candidate** — the business has not yet said who owns the decision; nothing can be evaluated until it does. If Q-1 yields a model, evidence points to Governance as donor/home. **Recommendation: DEFER pending Q-1. Confidence: n/a by construction.** *(ARB decides.)*
- **Superseding Constitutional Publication.** *Sketch:* a consumer of this context's output on the publication side. *Evaluation:* no Adjudication-owned consistency. *C7:* a genuinely named business concept (Policy 1) — first-class *on the publication side*, whenever that is tackled. **Recommendation: NOT an Adjudication aggregate. Confidence: HIGH.** *(ARB decides.)*

## Recommendation summary (for ARB decision — nothing here is decided)

| Candidate | Recommendation | Confidence | Ground in one line |
|---|---|---|---|
| Determination | **Aggregate (confirm existing, unchanged)** | **High** | All seven criteria met by production and vocabulary evidence |
| AdjudicationProceeding | **Aggregate Candidate** | **Medium** | C1 argues for a guarded boundary; C7 leans toward the Authority owning the decision — discriminator is Q-1 |
| — Process-Manager alternative | **Remains viable** | **Medium** | Preserved as an explicit alternative; neither forced |
| Declare-failure | Not an aggregate | High | An obligation and a conclusion kind, not a decision owner |
| Evidence body | Not an Adjudication aggregate | High | Upstream-owned; admission-tracking folds into the proceeding concern |
| Integrity Quarantine | Not an Adjudication aggregate | High | Publication-side (though Policy 4 names it — first-class there, later) |
| Authority | Defer (Q-1) | n/a | Criterion 7 IS Q-1 here |
| Superseding Publication | Not an Adjudication aggregate | High | Consumer; first-class on the publication side, later |

---

**Self-review (post-refinement):** every candidate carries a boundary sketch *before* its evaluation ✅ · all SEVEN criteria applied to every candidate ✅ · no single criterion dominates — Criterion 7 was allowed to *change* a recommendation (Candidate 2: Aggregate → Aggregate Candidate, Medium, with the Process-Manager alternative co-equal), which is the test that the criteria are peers ✅ · confidence recalibrated per candidate ✅ · equally-plausible realizations preserved as explicit alternatives, neither forced ✅ · the discriminating dependency (Q-1 ↔ Criterion 7 for Candidate 2) is named, giving the ARB a concrete reason to answer Q-1 ✅ · every verdict remains a recommendation; the ARB decides ✅ · Q-1/Q-2 unforeclosed; Q-3 respected ✅ · no VOs, events, commands, repositories, or services designed ✅.

**Stop condition: STOP.** Artifact №2 complete in refined (seven-criteria) form. Await ARB decision on the recommendations — noting the ARB may reasonably decide Determination now and **defer the Candidate-2 decision until Q-1 is answered**, since Q-1 is its named discriminator. The next artifact (Aggregate Responsibilities, for whichever candidates the ARB accepts) begins only after decision → refine → freeze.

---
*Predecessors: `EPIC-004_Adjudication_Tactical_Work_Package.md` (frozen) · `EPIC-004A` (closed, accepted with rulings) · `EPIC-004B` (frozen as refined) · Constitutional inputs: EPIC-002 baseline, EPIC-003 §THE FOUR DECISIONS, ADR-T1/T11/T14/T17/T19.*
