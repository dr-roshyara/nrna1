# EPIC-002 Domain Collaboration Discovery Report

**Kind:** discovery artifact — Strategic DDD Phase 3, the bridge between the Canonical Domain Model Decision and Context Mapping. **Authority:** generated; never authoritative without ARB review.
**ARB ruling authorizing this report (2026-07-25):** the Canonical Domain Model Decision is itself the ARB decision — no further ruling is needed to begin controlled architectural elaboration. This phase answers a different question than Context Mapping: not "what are the boundaries' relationships (patterns)?" but **"what business interactions exist between them?"** — a domain activity, not a software activity.
**Inputs (only):** `EPIC-002_Cross_Disciplinary_Evidence_Consolidation.md`, `EPIC-002_Strategic_Domain_Discovery.md`, `EPIC-002_Bounded_Context_Discovery.md`, `EPIC-002_Domain_Decomposition_Evaluation.md`, `EPIC-002_Canonical_Domain_Model_Decision.md`, `EPIC-002_Evidence_Family_Independence_Audit.md`. No new literature, no implementation knowledge, no external assumptions.
**Explicitly out of scope:** Context Map, relationship patterns (Partnership/ACL/Shared Kernel/Published Language/Customer-Supplier), APIs, events, aggregates, repositories, services, implementation architecture.

**Baseline (per the Canonical Domain Model Decision):** four candidate positions — CB-1 Collection & Aggregation · CB-2/CB-2-Alt Custodial vs. Self-Verifying Integrity (unresolved alternatives) · CB-3/CB-3-Alt Adjudication vs. possible Authority-Validity split (unresolved) · CB-4 Contemporaneous Record-Fixing (open merger question with CB-1) — plus Separability (K2) as a cross-cutting constraint. All collaborations below are described per that baseline; where a deferred decision changes the collaboration shape, scenarios are given rather than one presumed answer.

---

## 1. Business Collaboration Catalogue

Each collaboration: what business information crosses, why, when, who triggers it, and whether it is mandatory or optional. All are drawn from the Responsibility Relationship Matrix and the underlying evidence — none is invented for architectural convenience.

### COL-1 — Collection supplies the evidentiary body to Adjudication (CB-1 → CB-3)
- **What crosses:** the aggregated, heterogeneous body of evidence (multi-kind, per P1).
- **Why:** adjudication cannot occur without prior aggregated evidence — the one-directional dependency observed in every discipline examined, never reversed.
- **When:** before any determination; the evidence base treats collection as strictly prior.
- **Who triggers:** the business event requiring a determination (a contested outcome, a certification obligation, a challenge) — the trigger belongs to the adjudicative side; collection responds to a demand for a usable evidence body.
- **Mandatory?** **Mandatory.** No discipline shows adjudication proceeding on an empty or unaggregated evidence base; ES's declare-failure obligation exists precisely for when this collaboration under-delivers.

### COL-2 — Record-Fixing preserves the basis that Adjudication consumes (CB-4 → CB-3)
- **What crosses:** the contemporaneously-fixed record (or the fact that no such record exists).
- **Why:** a record fixed at act-time is what later adjudication acts upon; if fixing fails, adjudication operates on a reconstruction — which is exactly the D2 question (is that a violation or a different kind of artifact?).
- **When:** fixing happens at or near the original act; consumption happens later, at determination time — the volatility/actor-difference finding (act-time vs. review-time, per-transaction vs. policy-paced).
- **Who triggers:** the original act triggers fixing; the adjudicative demand triggers consumption. Two different business moments, two different triggering parties.
- **Mandatory?** **Mandatory where contemporaneity is required at all** (P3's scope is itself narrow and genuinely ambiguous) — recorded as mandatory-within-scope, with the scope contested.

### COL-3 — Integrity-preservation validates what Collection produced before Adjudication consumes it (CB-2 or CB-2-Alt, between CB-1 and CB-3)
- **What crosses:** an integrity assurance over the evidence body — either a custody attestation (CB-2: "an unbroken, attributable possession trail exists") or a public self-verification result (CB-2-Alt: "anyone can verify no undetected tampering occurred").
- **Why:** in every discipline that names custody explicitly (ES, DF), it sits structurally between collection and adjudication; ES is explicit that certification over an unestablished trail is "theater."
- **When:** continuously from the moment evidence exists until determination — integrity is not a one-shot handoff but a maintained state.
- **Who triggers:** evidence coming into existence triggers the obligation; the adjudicative act consumes the assurance.
- **Mandatory?** **The obligation is mandatory; the mechanism is the unresolved T1 contest.** What crosses differs by scenario (§4) — this is the collaboration most affected by a deferred decision.

### COL-4 — Record-Fixing conditions whether Integrity-preservation is meaningful (CB-4 → CB-2/CB-2-Alt)
- **What crosses:** the fixed-at-act-time record as the object over which integrity is maintained.
- **Why:** the discovered pattern (Responsibility Relationship Matrix): custody of a record never contemporaneously fixed is a materially different concern than custody of one that was. Integrity-preservation presupposes there is a determinate object to preserve.
- **When:** at record creation — this collaboration is the first link in the chain.
- **Who triggers:** the original act, via CB-4.
- **Mandatory?** **Mandatory in the custodial scenario** (CB-2 needs an object). **Partially transformed in the self-verifying scenario** (CB-2-Alt's public commitment — e.g., E2E-VV's append-only bulletin-board commitment at cast time — arguably *fuses* record-fixing and integrity-onset into one business act; see §4, Scenario B).

### COL-5 — Adjudication feeds correction back into the evidentiary landscape (CB-3 → CB-1/CB-4, conditional)
- **What crosses:** the determination itself, and any correction obligation it imposes (a record found deficient, evidence found insufficient, a declare-failure outcome).
- **Why:** P2's full name is "adjudication/**correction**" — the literature consistently pairs determination with a correction consequence (ICSID revision/annulment, audit remediation, declare-failure). A determination that evidence was insufficient creates a business demand back on collection; a determination that a record was deficient bears on future record-fixing practice.
- **When:** after determination.
- **Who triggers:** the adjudicative outcome.
- **Mandatory?** **Optional/conditional** — occurs only when the determination finds a deficiency. This is the only discovered collaboration flowing "backwards," and it is event-conditional, not continuous. **OPEN QUESTION carried forward:** whether correction-of-the-record is the same business activity as correction-of-authority (the CB-3-Alt question) — not resolved here.

### COL-6 — Authority-Validity gates whether an instrument can participate at all (CB-3-Alt → all, scenario-dependent)
- **What crosses:** the validity status of a governing instrument or record (in force / lapsed).
- **Why:** iteration 4's sunset-clause candidate — an instrument's authority can end without any adjudicative act. If CB-3-Alt exists, every other position's activity is conditioned on the instruments they operate under still being in force.
- **When:** on a schedule or expiry condition, independent of any dispute.
- **Who triggers:** the passage of time / a pre-set expiry — notably, **no business actor triggers it**, which is exactly what made it a P2 boundary-case candidate.
- **Mandatory?** **Exists only in the split scenario** (§4, Scenario D). In the no-split scenario this is simply an input condition inside CB-3, not a cross-boundary collaboration.

### Cross-cutting: Separability (K2) as a constraint on COL-1, COL-3, COL-4
Not a collaboration — no information crosses on its behalf — but a constraint every crossing must respect: what crosses as "evidence" must remain distinguishable from any interpretive judgment attached to it, to the extent feasible (feasibility itself bounded by the documented collapse-under-compromise finding). Recorded here so no collaboration silently absorbs it.

## 2. Information Dependency Matrix

| Producer → Consumer | Information | Business reason | Collaboration |
|---|---|---|---|
| CB-1 → CB-3 | Aggregated multi-kind evidence body | Determinations require a prior evidence base | COL-1 |
| CB-4 → CB-3 | Contemporaneously-fixed record | Adjudication acts on the original, not a reconstruction | COL-2 |
| CB-1 → CB-2/Alt | Evidence objects requiring integrity | Integrity obligations begin when evidence exists | COL-3 |
| CB-2/Alt → CB-3 | Integrity assurance (custody attestation *or* self-verification result) | Certification over an unestablished trail is "theater" | COL-3 |
| CB-4 → CB-2/Alt | The fixed record as integrity's object | No determinate object, no meaningful integrity | COL-4 |
| CB-3 → CB-1 | Insufficiency finding / correction demand | Declare-failure creates a renewed collection obligation | COL-5 (conditional) |
| CB-3 → CB-4 | Record-deficiency finding | Bears on record-fixing practice | COL-5 (conditional) |
| CB-3-Alt → all | Instrument validity status | Lapsed authority removes the basis for action | COL-6 (scenario-dependent) |

**What no position produces for any other (notable negatives, evidence-grounded):**
- CB-3 does not produce evidence — the no-canonical-verifier finding and the attestation-as-input-not-decision finding both keep determination downstream of, never a source of, the evidence body. (A determination is a business *outcome*; treating it as new "evidence" for a later adjudication was never observed as a named practice in the reviewed corpus and is left as an unexplored question, not assumed either way.)
- CB-1 does not consume anything from CB-2/CB-3/CB-4 in the discovered evidence — collection is the one position with no discovered upstream dependency, consistent with its decision-light profile.

## 3. Boundary Interaction Analysis

- **COL-1 strengthens autonomy on both sides.** Collection can aggregate without knowing what determination will be sought; adjudication consumes a body of evidence without owning its assembly. The one-directional dependency is clean — no hidden coupling discovered.
- **COL-2 strengthens autonomy but exposes the CB-1/CB-4 merger question from a new angle.** Record-fixing and collection *both* feed adjudication, both are decision-light, and their outputs travel together in every discipline examined (a fixed record IS collected evidence in ES/AL practice). **Collaboration analysis mildly increases the case for examining the merger** — two separate boundaries whose outputs always arrive together and whose consumers never distinguish their origins is a hidden-coupling signal. Recorded as a signal for the deferred decision, **not** as a resolution of it.
- **COL-3 is the least autonomous collaboration — by design of the domain, not by modeling error.** Whichever integrity mechanism applies, it must observe collection's outputs continuously and be consulted by adjudication. This "in-between" position is what the sources themselves describe (custody sits structurally between collection and certification); the coupling is domain-inherent, not accidental.
- **COL-4 in the self-verifying scenario suggests possible over-fragmentation.** If public commitment at act-time both fixes the record *and* begins its integrity protection (the E2E-VV bulletin-board pattern), then CB-4 and CB-2-Alt partially fuse in that scenario — a boundary distinction that exists in the custodial reading may not survive in the self-verifying one. This is a genuine discovery of this analysis: **the CB-2 decision and the CB-1/CB-4 merger question are not independent** — resolving custody toward CB-2-Alt would itself reshape the record-fixing boundary.
- **COL-5's backward flow is the only place hidden coupling could grow.** A correction loop from determination back into collection/record-fixing is event-conditional today, but if elaborated carelessly later it could make adjudication a de-facto controller of upstream positions. Flagged as a watch-item for Context Mapping, not a present defect.
- **COL-6, if it exists, is unlike every other collaboration** — untriggered by any business actor, schedule-driven, and universally consumed. That structural oddity is itself weak corroborating evidence for the split reading (it doesn't behave like the other adjudicative interactions) — but one structural observation cannot outweigh the thinness of its evidentiary base. Recorded without resolving CB-3/CB-3-Alt.

## 4. Deferred Decision Impact Assessment

**Scenario A — Custodial reading (CB-2):** COL-3 carries custody attestations; COL-4 is a clean handoff (fixed record → custodial trail). All six collaborations remain distinct. Collaboration count: 5 mandatory-or-conditional + 1 scenario-dependent.

**Scenario B — Self-verifying reading (CB-2-Alt):** COL-3 carries public-verifiability results; COL-4 partially fuses with record-fixing (public commitment = fixing + integrity-onset in one act). **Knock-on effect:** the CB-1/CB-4 merger question changes shape — the natural cluster in this scenario may be "commit-and-verify" rather than "collect-and-fix." The deferred decisions interact (§3, COL-4 finding).

**Scenario C — Both integrity mechanisms coexist for different evidentiary streams** (the hybrid deployed-systems pattern: STAR-Vote, Wombat): COL-3 splits into two parallel collaborations with different assurance semantics feeding the same adjudicative consumer — the most collaboration-complex scenario, and the one real deployments exhibit. Its plausibility is a business observation, not an architectural recommendation.

**Scenario D — Authority-Validity split (CB-3-Alt exists):** COL-6 becomes a real cross-boundary collaboration consumed by every position; COL-5's correction flow bifurcates (correcting a record ≠ re-validating an instrument). **If the split is rejected:** COL-6 collapses into an internal precondition of CB-3 and the collaboration catalogue shrinks by one.

**CB-1/CB-4 merger scenarios:** merged — COL-2 becomes internal, COL-1 carries the fixed record within the evidence body, catalogue shrinks by one; unmerged — status quo. Either way, no *other* collaboration changes shape, **except** via the Scenario B interaction noted above.

## 5. Collaboration Stability Assessment

| Collaboration | Stability | Basis |
|---|---|---|
| COL-1 (Collection → Adjudication) | **Stable** | Holds identically in every scenario; the strongest-evidenced dependency in the program |
| COL-2 (Record-Fixing → Adjudication) | **Provisional** | Holds unless CB-1/CB-4 merge (then internalized); content unchanged either way |
| COL-3 (Integrity between Collection and Adjudication) | **Dependent on deferred decision** (CB-2 vs. Alt vs. both) | The obligation is stable; the mechanism and what-crosses are not |
| COL-4 (Record-Fixing → Integrity) | **Dependent on deferred decisions, doubly** | Shape differs between custodial/self-verifying scenarios AND interacts with the merger question |
| COL-5 (Adjudication → upstream correction) | **Provisional** | Conditional flow, stable in trigger but open on whether it bifurcates (CB-3-Alt) |
| COL-6 (Authority-Validity gating) | **Uncertain** | Exists only in the split scenario, which rests on the program's thinnest evidence |

## 6. Readiness Assessment for Context Mapping

- **Ready now, in scenario-independent form:** COL-1 is fully stable; COL-2 and COL-5 are stable in substance with only their boundary-placement open. A Context Mapping exercise could safely begin from these.
- **Not ready without either an ARB ruling or explicit scenario-branching:** COL-3 and COL-4 — the custody decision determines what actually crosses, and it interacts with the merger question. Mapping these today means mapping Scenarios A/B/C side by side, not one map.
- **Not ready, and possibly never a mapping input:** COL-6 — until CB-3/CB-3-Alt is disposed of, it is unknown whether this is a collaboration at all.
- **New information for the ARB from this phase (discovered, not decided):** (1) the CB-2 decision and the CB-1/CB-4 merger question are coupled — they should not be ruled on in isolation; (2) the collaboration angle mildly strengthens the case for examining the CB-1/CB-4 merger (outputs always travel together; consumers never distinguish their origins); (3) the hybrid scenario (C) is what real deployments in the evidence base actually exhibit and deserves explicit consideration rather than treatment as an edge case.
- **Advisory readiness verdict (decision is ARB's):** business collaboration is now sufficiently understood to begin Context Mapping **in a scenario-explicit form** — a map that carries the open alternatives as labeled branches. A single-answer Context Map remains premature for the reasons above.

---

**Stop condition:** this report discovers business collaboration; it does not map contexts or select patterns. **STOP.** The ARB uses this report to determine whether formal Context Mapping begins — and in which form (scenario-explicit vs. after ruling on the deferred items). No Context Map, relationship pattern, or tactical design is authorized by this document.

---
*Charter: `EPIC-002_Problem_Statement.md` · Inputs: the six EPIC-002 artifacts listed in the header · No new sources consulted.*
