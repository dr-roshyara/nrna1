# EPIC-004C Adjudication Aggregate Evaluation

**Kind:** Tactical DDD artifact №2 (one artifact → ARB review → refine → freeze → next). **Structure per ARB process refinement (2026-07-25):** the previously separate "Evaluation & Selection" and "Boundaries" steps are **merged** — you cannot evaluate a candidate whose boundary is unknown, and you don't evaluate a vacuum. For every candidate: **Section A — Tentative Boundary Sketch** (a hypothesis, not a finalized boundary), then **Section B — Evaluation** against the six ARB criteria.
**Governance wording (binding, per ARB):** this artifact **recommends** aggregate selection. **The ARB decides.** Nothing below is a declaration.
**Predecessor:** `EPIC-004B_Adjudication_Aggregate_Discovery.md` — FROZEN as refined (ARB re-review 9.6/10; remaining concerns were process-level and are implemented by this artifact's structure).
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

**Recommendation: AGGREGATE — confirm the existing implementation unchanged.** All six criteria answered affirmatively by production evidence, not speculation. *(ARB decides.)*

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
| 6 | Does it own transactional consistency? | **This is the honest tension.** FOR: criterion 1's conclude-atomically-fixes-considered-evidence is a genuine multi-fact atomic rule that only a guarded consistency boundary can enforce. AGAINST: if admissions are mere appends of opaque refs, a Process Manager coordinating simple steps (with an event log) could arguably suffice — the alternative artifact №1 was required to preserve |

**Recommendation: AGGREGATE — on the strength of criterion 1.** The conclude-time atomic fixation of the considered-evidence set is a consistency obligation, not a coordination step; a process manager coordinates *other* aggregates' consistency and owns none of its own, which would leave criterion 1's rule with no guardian. **Preserved alternative (for the ARB to weigh):** Process Manager + event-sourced admission log, viable only if the ARB judges conclude-time fixation enforceable by other means; the Policy/Saga/Domain-Service/inside-Determination alternatives are weaker (no lifecycle home, or two paces under one guardian — the exact condition that separated the candidates in artifact №1). *(ARB decides.)*

## Deferred candidates — sketched and evaluated *(none silently dropped)*

- **Declare-failure.** *Sketch:* no state beyond the proceeding's conclusion-kind and the outbound demand (COL-5a). *Evaluation:* protects no consistency of its own; its invariants live inside Candidate 2. **Recommendation: NOT an aggregate — a conclusion kind of the proceeding.** *(ARB decides.)*
- **Evidence body / envelope.** *Sketch:* inside Adjudication, only opaque admission references exist — content, custody, and integrity are upstream-owned (COL-1/COL-3a). *Evaluation:* an Adjudication-side evidence aggregate would own no consistency that Candidate 2's admission-tracking doesn't already cover, and would re-own Collection's subject across the C–S line. **Recommendation: NOT an Adjudication aggregate — admission-tracking folds into Candidate 2.** *(ARB decides.)*
- **Integrity Quarantine.** *Sketch:* the quarantine state attaches to a *publication* — which lives outside this context. *Evaluation:* whatever aggregate guards quarantine belongs where publication lives; its consequence (a challenge, then a proceeding) arrives via collaboration (per the Q-3 reclassification). **Recommendation: NOT an Adjudication aggregate — out of context.** *(ARB decides.)*
- **Authority.** *Sketch:* impossible without Q-1's business answer — that is itself the finding. *Evaluation:* deferred; remains an opaque reference (as today). If Q-1 yields an authority model, the evidence points to Governance as donor/home, not Adjudication. **Recommendation: DEFER pending Q-1; no aggregate here, foreclosure of nothing.** *(ARB decides.)*
- **Superseding Constitutional Publication.** *Sketch:* a consumer of this context's output on the publication side. *Evaluation:* no Adjudication-owned consistency. **Recommendation: NOT an Adjudication aggregate — its home is decided when the publication side is tackled.** *(ARB decides.)*

## Recommendation summary (for ARB decision — nothing here is decided)

| Candidate | Recommendation | Ground in one line |
|---|---|---|
| Determination | **Aggregate (confirm existing, unchanged)** | All six criteria met by production evidence |
| AdjudicationProceeding | **Aggregate (new)** — Process-Manager alternative preserved | Conclude-time atomic fixation of the considered-evidence set needs a guardian |
| Declare-failure | Not an aggregate | A conclusion kind; invariants live in the proceeding |
| Evidence body | Not an Adjudication aggregate | Upstream-owned; admission-tracking folds into the proceeding |
| Integrity Quarantine | Not an Adjudication aggregate | Publication-side; arrives via collaboration |
| Authority | Defer (Q-1) | Cannot sketch what the business hasn't defined |
| Superseding Publication | Not an Adjudication aggregate | Consumer of output |

---

**Self-review:** every candidate carries a boundary sketch *before* its evaluation — nothing evaluated in a vacuum ✅ · every sketch labeled hypothesis; no boundary finalized ✅ · every verdict is a **recommendation**, with the ARB named as decider at each one ✅ · the genuine tension on Candidate 2 (aggregate vs. process manager) is stated honestly with the alternative preserved, not smoothed over ✅ · Q-1/Q-2 unforeclosed; Q-3 reclassification respected ✅ · no VOs, events, commands, repositories, or services designed ✅.

**Stop condition: STOP.** Artifact №2 complete. Await ARB decision on the seven recommendations → refine → freeze. The next artifact (Aggregate Responsibilities, for whichever candidates the ARB accepts as aggregates) begins only after that.

---
*Predecessors: `EPIC-004_Adjudication_Tactical_Work_Package.md` (frozen) · `EPIC-004A` (closed, accepted with rulings) · `EPIC-004B` (frozen as refined) · Constitutional inputs: EPIC-002 baseline, EPIC-003 §THE FOUR DECISIONS, ADR-T1/T11/T14/T17/T19.*
