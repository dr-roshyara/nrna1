# PKS Phase II — M5: Strategic Domain Classification

| | |
|---|---|
| **Kind** | Strategic Modeling artifact (WP M5) — classifies the strategic role of every validated concept. Strategic DDD only: **no bounded contexts, no context mapping, no tactical DDD, no architecture.** |
| **Status** | **ACCEPTED WITH REFINEMENTS — the consolidated M5 strategic baseline.** |
| **Disposition History** | 2026-07-28: M5 plan approved (Plan Mode) with DDD-review refinements (K6, F6, strategic significance test). Same date: M5 Execution Commission issued (verbatim in session log); refined execution prompt delivered in-session — session-boundary routing superseded by the direct commission (recorded, not silent; M0/M2 precedent). Executed same date. Same date: PA adjusted cadence to a per-milestone M5 checkpoint (Critical Review, T-2 declared): **ACCEPT WITH REFINEMENTS** recommended (F-M5R-1..3); dispositions **confirmed by the PA via the Consolidation & M6-Preparation Commission** ("three approved refinements" + Phase-B fold authorization — interpretation recorded in the session-log intake entry); three additive folds applied (see Checkpoint Amendments); status transitioned EXECUTION COMPLETE → ACCEPTED WITH REFINEMENTS. |
| **Commission** | The M5 Execution Commission + approved plan (`.claude/plans/shiny-hopping-nest.md`). Baseline: M2+M3+M4 CONFIRMED and consolidated — all M4-derived inputs used below are **re-grounded on the confirmed baseline** (the plan's "conditioned" marks are discharged). |
| **Method** | Per concept: characterize (evidence from M0–M4 only) → strategic responsibility (what carried · why required · what is lost if removed) → framework evaluation → competing classifications → falsification → confidence (M0 rubric). Epistemic classes inline. |
| **Placement** | `docs/implementation/`, beside M0–M4. |

---

## 1. Executive Summary

**Framework outcome (evaluated, not assumed):** the classification uses a **composition of F6 (domain semantic responsibility) as the primary lens with F1 (Core/Supporting/Generic) as the significance grading** — two orthogonal questions per concept: *what responsibility?* and *how differentiating?* Of the six candidates: **F3 REJECTED** (redundant — restates M1/M4 without new discrimination), **F4 REJECTED for concept classification** (near-empty class — ceremonial), **F2 NARROWED to an attribute** (its knowledge/governance ambiguity is *dissolved* by F6: Ruling's responsibility is *recording* governance, not being it), **F5 ABSORBED as dependency structure** inside the classification. The anti-ceremony bar is met: two rejections, one narrowing, one absorption, all evidence-grounded.

**Classification outcome:** all 21 items classified (17 concepts + 2 projection patterns + 2 derived assessments): **8 Core · 9 Supporting · 2 Generic**, responsibilities distributed **expresses 3 · governs 5 · records 7 · derives 6**. The Work-item M1 promise is discharged: **Generic within the PKS view + adjacent-domain-candidate recommendation routed to M6/ARB — boundary NOT decided.** The strategic-significance verification passes: the classification explains *why the PKS exists* (§8). Two emergent observations recorded, neither resolved. OQ-2/OQ-7 did not bite (plan prediction holds, four-for-four).

## 2. Strategic Classification Method

As commissioned (header row Method) — with the framework evaluation preceding all concept classification, and the **strategic significance test** applied to every candidate before selection: *does this framework explain why PKS exists as a domain, or merely organize concepts?*

## 3. Framework Evaluation (independent, then compared)

| # | Candidate | Significance test | Discriminability | Verdict |
|---|---|---|---|---|
| F1 Core/Supporting/Generic | Partial — identifies the differentiating heart but not the domain's mechanics | Partitions 8/9/2 and 2/2 across hard members — non-trivial | **SELECTED as significance grading** (Medium-High) |
| F2 Knowledge/Governance | Weak as partition — the hard case (Ruling) is *both* | Fails on hard members | **NARROWED to attribute**; its ambiguity dissolves under F6 (Ruling = *records* governance) — evidence of F6's discriminating power |
| F3 Identity-bearing/Derived | Fails — reorganizes what M1/M4 already state | Tautological with the accepted baseline; adds nothing | **REJECTED** (preserved: would matter only in a corpus without an M4-equivalent) |
| F4 Operational/Constitutional | Fails at *kind* level — constitutional class near-empty (the four constitutional policies are Policy *instances*, not kinds) | Ceremonial for kinds | **REJECTED for concept classification** (preserved: real as an instance-level axis) |
| F5 Foundational/Derived | Partial — explains dependency, not purpose | Partial order, not partition | **ABSORBED as structure**: the dependency ordering (Observation → criterion → Verdict → …) annotates the table, frames nothing |
| F6 Semantic responsibility (expresses/governs/records/derives) | **Passes** — the domain exists to make engineering knowledge governed, evidenced, and memory-bearing; the responsibility classes name exactly those mechanics | Partitions all 21 with one honest tie handled by competing-classification discipline | **SELECTED as primary lens** (Medium-High) |

**Falsification of the selection (strongest counter, written before adoption):** *"F6 was reviewer-proposed, not corpus-derived — selecting it imports taxonomy."* **Answer from evidence:** F6's four verbs are observed corpus practice, not imports — "documents **record** governance; decisions create it" (Observed rule) · Rules/Invariants **govern** with distinct violation semantics (Observed) · derived-artifacts-regenerated-never-hand-edited (**derives**, Observed) · the UL and decision registers carry the domain's **expressed** meaning (Observed). The *labels* arrived via review; the *classes* were already operating. Partially answerable in the same sense as M3's — the composition choice remains judgment → **framework confidence Medium-High, not High.**

## 4. Concept-by-Concept Classification

*(Responsibility = F6 · Significance = F1 · evidence anchors are the accepted M0/M1 rows; F5 ordering noted where load-bearing. "Lost if removed" states the strategic-responsibility test.)*

| Item | Responsibility | Significance | Strategic responsibility — and what is lost if removed | Conf. | Competing classification (preserved) |
|---|---|---|---|---|---|
| Decision (G-1) | **expresses** | **Core** | Carries the domain's paradigmatic content: choices-with-rationale, permanent (P-1). Removed → the domain loses its primary object; P-1/P-2 unservable | High | *records* (a decision record records an act) — rejected: the Ruling records the act; the Decision IS the knowledge |
| Rule (G-2) | **governs** | **Core** | Binding norms with canonical homes. Removed → nothing binds; drift ungovernable (P-2) | High | *expresses* — rejected: violation semantics (governance violation) mark it normative, not descriptive |
| Invariant (G-3) | **governs** | **Core** | Must-hold conditions; violation = defect. Removed → verification loses its criteria class (P-3) | High | Merge with Rule — rejected in M2 (C-3, distinct violation semantics); stands |
| Ruling (G-4) | **records** | **Core** | Records authority acts — the domain's constitutional mechanism ("documents record governance; decisions create it"). Removed → authority becomes unauditable (P-8/P-9) | High | *governs* — **dissolved by F6**: the act governs; the Ruling records it (this resolution is what falsified F2-as-partition) |
| Finding (G-5) | **records** | Supporting | Records evidenced defects from reviews. Removed → review loses its output form; verification degrades | High | — |
| Question (G-6) | **records** | Supporting | Records owned, routed unknowns. Removed → uncertainty becomes untracked (the surfacing discipline dies) | High | *governs* (routing is governance-ish) — rejected: routing is an attribute; the responsibility is recording the unknown |
| Term (G-7) | **expresses** | **Core** | The UL itself; term change is a first-class governed event (ADR-UL). Removed → no shared meaning; every other concept's expression degrades | High | — |
| Contract (G-8) | **governs** | Supporting | Binds expectations at boundaries (validated scope: event contracts). Removed → boundary drift. Boundary specs exist in any system; the governed versioning is notable but not the domain's differentiator | Medium-High | Core — rejected on portability: generic capability, locally well-governed |
| Model element (G-9) | **expresses** | Supporting | Precise, citable elements of governed models. Removed → models become opaque blobs (frozen-input chains break). Not Core: it expresses *models'* meaning, in service of Decisions/Invariants | Medium | Core (frozen-chain discipline is distinctive) — preserved as plausible; grain question still open (M4) |
| Observation (G-10) | **records** | Supporting | Records evidence with epistemic class. **F5-foundational — everything presupposes it — yet Supporting in significance: K6's distinction demonstrated** (foundational ≠ expressing the differentiator; evidence-recording exists everywhere; the epistemic labeling is the local excellence) | High | Core (evidence-first is the program's soul) — rejected via K6: the *discipline* differentiates, the *concept* is universal |
| Risk (G-11) | **records** | **Generic** | Risk registers are universal practice; nothing PKS-specific is lost that a generic register would not restore | High | — |
| Candidate (G-12) | **governs** | **Core** | Probationary status + human-gated promotion — the evidence-driven-promotion discipline that pervades this domain (18-capture inbox as living instance). Removed → momentum-promotion returns; the AKB/EKP failure shape re-enters | Medium-High | Supporting — rejected: the promotion ladder is among the most differentiating observed behaviors |
| Work item (G-13) | **records** | **Generic — adjacent-domain candidate** | Records work state (lifecycle ≠ progress). **M1-promise discharged: recommended as adjacent work-management-domain candidate; the boundary decision routes to M6/ARB — NOT decided here.** Within the PKS view its role is generic | Medium | PKS-member Supporting — preserved: the lifecycle≠progress rule is PKS-relevant even if the concept is not |
| Guide step (G-14) | **derives** | Supporting | Projects knowledge into teachable form (P-6). Removed → onboarding degrades; knowledge stays but stops transferring | Medium | — (representational-identity liability rides from M4) |
| Verdict (G-15) | **derives** | **Core** | Judgment derived from evidence against criteria, issued by gate/review never author — the verification purpose (P-3) made concrete. Removed → assessment becomes opinion | High | — |
| Exception record (G-16) | **records** | Supporting | Records approved deviations with owner/expiry semantics. Removed → deviations go dark (conformance evidence lost) | Medium-High | — |
| Charter grant (G-17) | **governs** | **Core** | Authorization as bounded permission ("construction permit, not a design") — the one-authority-per-stage mechanics. Removed → scope creep ungovernable | Medium-High | Supporting — rejected: the authorization chain is constitutive of how this domain operates |
| ADR (pattern) | **derives** | Supporting | Projects Decision(+context+alternatives) into a reviewable serialization. Patterns serve concepts | High | — |
| Policy (pattern) | **derives** | Supporting | Projects Invariants+Rules+scope into named constitutional packages (four-policies evidence) | Medium-High | — |
| Completeness (derived assessment) | **derives** | Supporting | Sufficiency judgment over the knowledge system; state-assessed (M2). Supporting: valuable, but the domain operated (imperfectly) without it | Medium | Core — preserved: Q8 called it potentially "the heart"; graded Supporting on operational evidence (Absent) |
| Conformance (derived assessment) | **derives** | **Core** | Adherence judgment — addresses the domain's own named failure mode (rules-written-instances-don't-follow), the phenomenon that motivated the entire discovery. Classification ≠ operationality: strategically Core **while operationally Absent** — the three-orthogonal-tests discipline applied | Medium | Supporting (symmetric with completeness) — rejected: conformance names the failure mode the domain's differentiation depends on countering; asymmetry is evidence-based, not oversight |

**Distribution check (anti-ceremony):** expresses 3 · governs 5 · records 7 · derives 6 — no class empty, no class trivially-everything. Core 8 · Supporting 9 · Generic 2 — the Core is broad, and honestly so (→ observation O-M5-1) rather than forced narrow.

## 5. Falsification Results (concept level, most load-bearing)

- **Conformance-as-Core** (strongest single classification claim): counter — *"a concept with zero operating instances cannot be Core."* Answer: significance classifies *strategic role*, operationality classifies *maturity* — the accepted three-orthogonal-tests discipline separates them, and the domain's differentiation (governed knowledge that stays conformant to its own rules) is precisely what this concept names. Confidence held at Medium, not raised.
- **Observation-as-Supporting**: counter — *"evidence-first is the program's soul; its carrier must be Core."* Answer: K6 — the *discipline* is differentiating; the *concept* is universal. What would be lost if removed is recording, not differentiation; the epistemic labeling would be lost with the discipline, which lives in Rules, not in the Observation concept. Sustained.
- **Broad-Core worry**: counter — *"8 of 17 Core suggests a non-discriminating grading."* Answer: the partition rejected 9 concepts from Core on stated grounds (incl. two candidates argued *for* Core and denied); breadth is a *finding about meta-domains*, recorded as O-M5-1, not a grading failure.

## 6. Remaining Open Questions & Emergent Observations *(recorded, NOT resolved — per the commission)*

- **O-M5-1 (observation):** meta-domains appear core-dense — a knowledge-governance domain's differentiators are mechanisms, and mechanisms are concepts; ~47% Core may be structural for domains-about-knowledge. Single-corpus; not generalized.
- **O-M5-2 (observation):** F6's *governs* class coincides with concepts whose relationships are **authoritative** under the observed polarity — suggestive of a deep link between responsibility class and relationship polarity. **OQ-PKS-4 territory: recorded only; resolving it is the ARB's, and M7 will see the polarity evidence again.**
- **F-BCP-4 discharge (UL note, as routed):** this artifact uses "register (document kind)" vs "identity namespace (M4 sense)" wording where ambiguity threatened; the *canonicalization* of "register" is Term maintenance under ER-06 — flagged for the UL, not performed.
- Open for M6: whether responsibility classes or significance grades better seed boundary discovery (explicitly NOT pre-decided — plan constraint honored).

## 7. Threats to Validity (T-1..T-11 carry; new)

| # | Threat | Mitigation | Residual |
|---|---|---|---|
| T-12 *(new)* | **Composed-framework overfit** — F6×F1 selected by the same rater who then applied it | F6's classes grounded in observed corpus verbs; two frameworks genuinely rejected; competing classifications preserved per concept | A different rater might compose differently; single program (T-1/T-5 compound) |
| T-13 *(new)* | **Significance-grading circularity risk** — "differentiating" judged against purposes the same discovery derived | Purposes P-1..P-9 are Observed (stated/enforced by the corpus), not this-session constructs | The purpose *weighting* (which purposes differentiate) is reviewer inference, marked |

## 8. Strategic DDD Verification

*Does the classification explain why PKS exists as a strategic domain?* **Yes, and testably:** read the Core row-set together — Decision · Term · Rule · Invariant · Ruling · Candidate · Charter grant · Verdict · Conformance — and the domain's reason-for-being emerges as a sentence: **the PKS exists to keep engineering knowledge expressed (Decision, Term), governed (Rule, Invariant, Candidate, Charter grant), accountable (Ruling), judged (Verdict), and conformant to its own rules (Conformance) — because its primary collaborator has no memory and its decisions must outlive their sessions.** That is an explanation of existence, not an organization of terminology. The Supporting set serves this; the Generic set is replaceable commodity. Verification **PASS**.

## 9. Recommendation for M6 Inputs

*(Stated as inputs, not boundaries — M6 discovers; nothing here draws.)* The evidence M6 should weigh: the responsibility distribution (§4) · the authority seam + polarity (item 1 Q3, untouched) · the Work-item adjacent-domain recommendation (decision: M6/ARB) · O-M5-2's polarity coincidence · the M4 seams (identity modes; register-namespace) · the expected surfacing of OQ-2 (incumbent) and OQ-7 (three roots) — **still unbitten after five work packages, and M6 is where the plan predicts they bite.**

## 10. Self-Assessment (commission's final verification)

Strategic responsibilities classified, not implementation responsibilities ✅ · entirely Strategic DDD (no BC/aggregate/entity/service/API content as design) ✅ · accepted baseline preserved (no M0–M4 artifact modified; all inputs re-grounded on the confirmed baseline) ✅ · evidence separated from interpretation (epistemic classes inline; reviewer inference marked at T-13) ✅ · uncertainty documented (per-row confidence; two preserved pro-Core rejections; O-observations unresolved) ✅ · no architecture introduced ✅ · stronger strategic understanding than post-M4 — the domain now has an evidence-based answer to *what each concept is for* and *why the domain exists* (§8), which M4 could not state ✅ · every validated item classified (21/21) ✅ · ≥1 framework rejected (two) ✅ · Work-item promise discharged with boundary routed ✅ · emergent questions recorded as observations only ✅ · methodology observations → retrospective inbox, not promoted ✅.

---

## Checkpoint Amendments (additive; authority-confirmed 2026-07-28)

**1. F-M5R-1 — O-M5-1 elevated to explicit grading assumption.** Checkpoint finding (quoted): *"the sustain [of Charter grant's and Candidate's Core standing] visibly rests on the meta-domain property (O-M5-1)… it must be stated as an explicit grading assumption, so a future corpus where the meta-domain property fails knows to re-grade."* **Amendment (additive; §4 rows stand unedited):** the significance grading operates under the explicit assumption — *governance mechanics are identity-constitutive in a domain about governed knowledge (the meta-domain property, O-M5-1)*. This assumption is load-bearing specifically for the **Charter grant** and **Candidate** Core gradings; in a corpus where the property fails, those rows re-grade first.

**2. F-M5R-2 — threat T-14 added.** Checkpoint finding (quoted): *"Narrative-investment bias: the program coined Knowledge-System Conformance and repeatedly featured it; grading it Core flatters the program's own arc… the bias channel is structural: the same lineage discovered, named, kind-decided, and now graded the concept. Only a program-external reviewer or a second corpus can fully discharge it."* **Amendment:** T-14 joins this artifact's threat table (extending §7's T-12/T-13); the Conformance-as-Core confidence remains Medium — already capped in anticipation of exactly this channel.

**3. F-M5R-3 — §8 independence caveat.** Checkpoint finding (quoted): *"the independent significance test's agreement is simultaneously validation and a weak-independence signal (same lineage produced both answers; T-2)."* **Amendment:** §8's verification PASS carries the caveat that its independent reproduction was same-lineage; a program-external reproduction remains the stronger validation.

**Authority chain:** M5 Critical Review recommendations (2026-07-28) → PA confirmation via the Consolidation & M6-Preparation Commission (same date; intake interpretation recorded in session log) → these amendments.

---

*Traceability: executes WP M5 under the M5 Execution Commission (session log 2026-07-28, post-close append 3 + refined execution prompt, same date) · plan: `.claude/plans/shiny-hopping-nest.md` (APPROVED, DDD-refined: K6/F6/significance test) · inputs: M0 glossary · M1 register · M2 dispositions · M3 composition · M4 identity/lifecycle (confirmed baseline, amendments folded) · item 1 P-1..P-9/Q3 · F-BCP-4 UL note discharged as §6 · session-boundary supersession recorded in Disposition History. **STOP — M5+M6 batch checkpoint per plan cadence; M6 opens only on its own commission.***
