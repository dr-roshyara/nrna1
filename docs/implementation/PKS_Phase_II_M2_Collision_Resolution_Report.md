# PKS Phase II — M2: Collision Resolution Report

| | |
|---|---|
| **Kind** | Strategic Modeling artifact (WP M2) — resolves the commissioned conceptual collisions. Conceptual clarification only: **no tactical DDD, no architecture, no governance decision.** |
| **Authority** | Generated — never authoritative without human review. Resolutions are the model's recommendations; the checkpoint review disposes. |
| **Status** | **ACCEPTED WITH REFINEMENTS — M2 checkpoint disposition (ARB chair, 2026-07-28): scope PASS · governance PASS · strategic-DDD PASS · zero critical/major findings · "M2 is accepted as the strategic baseline for M3. M3 is authorized to begin."** Verified by the chair against the plan (exact scope match, no creep) and against M1 (all three deferred candidates disposed with lineage intact). Refinements (methodological/editorial only) incorporated same date — §10. *(Supersedes EXECUTION COMPLETE — awaiting disposition.)* Structural-consistency verification run at the chair's request: all five sections **analytically equivalent** (every commissioned analytical element present in each); C-1..C-3 additionally **structurally identical** (discrete steps 1–6); §4–§5 use explicitly-labeled merged step headings because **the analytical object differs** — they analyze an operation inventory and a dimension set, not two-sided collisions — so the presentation compresses while the analysis does not. One genuine gap found and fixed same date (§4 KE fields lacked `assumptions` + `residual uncertainty`). *(Equivalence/identity distinction + analytical-object wording: chair refinements, 2026-07-28.)* |
| **Commission** | `docs/plans/20260728-1624-pks-strategic-modeling-plan.md` (APPROVED) WP M2 + the PA's M2 execution commission (2026-07-28: 6-step method · collision taxonomy · knowledge-engineering fields · scientific-discipline rules). Fresh-session note superseded by the direct commission — recorded, not silent. |
| **Scope corrections vs advisory paraphrase (record wins)** | The advisory material's example list included "C-2 Qualification" — per the record C-2 is **Merge/Invalidation**; Qualification is **OQ-PKS-11, ARB-owned, already surfaced (M1 Part D)** and does not enter modeling. "C-4: Conformance" is likewise a mislabel — C-4 is the **completeness-dimensions** collision; the Conformance *kind* decision is **M3's** commissioned work and is not performed here. |
| **Method** | Per collision: the commissioned 6 steps (restate → evidence → classify via taxonomy → evaluate alternatives → select resolution → preserve lineage), with the full knowledge-engineering field set. Confidence per the M0 rubric; portability per the M1 rubric. |

---

## 0. Collision Taxonomy (adopted before any resolution)

| Type | Meaning |
|---|---|
| **Terminology** | Different terms for the same concept |
| **Concept boundary** | Overlap or ambiguity in where one concept ends and another begins |
| **Representation** | The *function* is evidenced but its reification as a first-class concept is contested |
| **Status** (operational vs hypothetical) | Whether a proposed element actually operates or is only specified/imagined |
| **Granularity** | Same phenomenon described at different levels (per-unit vs whole-system) |
| **Governance** | Authority or decision-ownership ambiguity — *never resolved here; surfaced* |
| **Evidence insufficiency** | The corpus cannot decide the question |

*(Two types beyond the commissioned examples — Representation, Status — were needed by the actual collisions; added to the taxonomy rather than forcing misfits. Taxonomy extension is itself lineage: recorded here, reusable by future runs.)*

---

## 1. C-1 — Requirement

**Step 1 · Restate.** Evidence discovery: *"Evidence not found"* as an identified unit — no `REQ-nn` scheme exists product-side; operating forms are invariant, acceptance criterion, conformance test (item 1, §1.2-negative). Candidate model: presents `Requirement/Goal` as a node carrying an authoritative `implements` relationship (item 2, §3). The collision exists because the candidate imports a concept general practice expects, while the corpus demonstrably operates without it. The accepted trichotomy: **(a)** missing concept · **(b)** represented via invariants + criteria + conformance tests · **(c)** outside the boundary.

**Step 2 · Evidence.** Supporting (b): the requirement *function* — binding expectations and verifying against them — is performed: "every proposed Value Object must trace back to one or more frozen business invariants" (frozen-input discipline); IDD §14 acceptance criteria; per-decision conformance-test columns; the 14-box DoD. Supporting (a): none found — **no recorded finding attributes any defect to the absence of unified requirement objects.** Supporting (c): fails immediately — purposes P-2/P-3 (bind implementation to approved design; enable verification) put the function squarely inside the domain. Contradicting (b): single-corpus; other products may operate reified Requirements.

**Step 3 · Nature.** **Representation collision** (function evidenced; reification contested).

**Step 4 · Alternatives.** (a) *missing concept* — strength: matches general practice; weakness: requires evidence of harm from absence, and none exists; adopting it would model a gap the corpus does not exhibit. (b) *represented otherwise (distributed)* — strength: directly evidenced, all three carrier forms operate with identities and lifecycles; weakness: distribution means no single requirement-traceability anchor, a *potential* liability with no recorded instance. (c) *outside boundary* — eliminated on purpose-evidence.

**Step 5 · Resolution: (b) VALIDATED — Requirement is represented, distributed, across Invariant + acceptance criterion + conformance test.** The candidate `Requirement` node is **not adopted** as a first-class concept for this PKS; the candidate `implements` relationship re-terminates on the evidenced carriers. Confidence: **Medium-High** (absence Measured/High; the sufficiency of distributed representation is Derived — it rests on the absence of harm evidence, which is weaker than presence evidence).

**Step 6 · Lineage.** Candidate Requirement preserved (M1 Part B already carries it as deferred; disposition now recorded here). **Reconsideration trigger:** any future finding attributing a defect to the absence of unified requirement objects (orphaned acceptance criteria; an invariant nobody can trace to a need) reopens the trichotomy at (a).

*KE fields — origin: item 2 §3 / item 1 §1.2-negative · assumptions: absence-of-harm is detectable in this corpus's audit discipline (plausible — its audits are thorough) · residual uncertainty: portability of (b) is unknown; the trichotomy itself is the portable instrument, the (b) selection is local-evidence-bound · affected: G-3 (Invariant gains the requirement-carrier note) · M1 Part B row Requirement → resolved · governance implications: none.*

---

## 2. C-2 — Merge and Invalidation

**Step 1 · Restate.** Candidate model lists Merge and Invalidation among five "discovered" evolution patterns (item 2, §4.1). Evidence discovery checked for both and found **neither** (item 1, Q4-negative: no two identified objects ever merged under a rule; no mechanism marks dependents stale). The collision exists because general-practice plausibility met a measured absence. The accepted worked example already splits the compound: "merge has no repository evidence" = Measured/High; "therefore merge is unnecessary" = Interpreted/Low.

**Step 2 · Evidence.** Merge: the corpus **splits** (D-12 → 6 ADRs) and **consolidates** (STANDARDS_INDEX; "the convention lives once, here") — but consolidation collapses *restatements of text* into one canonical home + pointers; it never merged two *identified knowledge objects* into one successor. Adjacent evidence: when two objects should become one, the observed move is *create successor + supersede both* — no counterexample found. Invalidation: absent as mechanism, and the corpus's documented failure modes (stale boards; FROZEN Blueprint still encoding superseded ADR-T17) are **exactly what absent invalidation produces** — the *need* is evidenced even though the *operation* is not.

**Step 3 · Nature.** **Status collision** (operational vs hypothetical) — for both.

**Step 4 · Alternatives.** Admit both to the canon as operations — rejected: would classify unperformed mechanisms as operational, the precise false-operationality failure the three-tests discipline exists to block. Reject both outright — rejected: conflates "never observed" with "cannot exist" (the accepted nuance), and for Invalidation the need-evidence is strong. Preserve as hypothetical with admission triggers — fits all evidence.

**Step 5 · Resolution: both recorded as HYPOTHETICAL operations, excluded from the operational canon (→ §4), each with an admission trigger.**
- **Merge** — admission trigger: the first rule-governed merge of two identified knowledge objects. Derived absence-rationale (answering OQ-5's "deliberate or unneeded?"): the supersede+consolidate pair appears to cover the merge need in this corpus — **Derived, Medium confidence**, explicitly not upgraded to "merge is unnecessary."
- **Invalidation** — admission trigger: a *designed and operating* staleness mechanism. The need-evidence routes forward: **M3 input** (the discovery named conformance observation as the missing mechanism class this belongs to). OQ-CM-3 (invalidation thresholds) remains alive but conditioned on this hypothetical becoming real.

**Step 6 · Lineage.** Candidate positions preserved verbatim in the synthesis (C-2 row) and here; the candidate model's "discovered" labeling for these two stands corrected by the accepted synthesis position ("proposed, not discovered") — no artifact edited.

*KE fields — assumptions: consolidation≠merge by object-identity criterion (stated, inspectable) · residual uncertainty: whether other corpora need first-class merge — unknown, single corpus (T-1) · affected: evolution canon §4 · candidate model §4.1 standing · governance implications: none new.*

---

## 3. C-3 — Constraint vs Rule vs Invariant (+ deferred candidates Constraint, Policy)

**Step 1 · Restate.** Evidence discovery: the corpus **does not separate** Constraint from Rule/Invariant — "Constraints that bite" (MEMORY) is a hint list pointing at rules and ADRs (item 1, §1.2-negative). Candidate model: Constraint and Invariant as distinct primitives, Constraint defined as "non-negotiable boundary or threshold imposed on performance, security, or structure" (item 2, §1.2). The collision exists because a plausible general-practice distinction lacks local instantiation.

**Step 2 · Evidence.** Rule (K-2) operates: ER/EP/ES identities, canonical-home discipline, violation = **governance violation**. Invariant (K-3) operates: CI/BI/INV identities, violation = **defect**. The two are separately identified, separately homed, and carry different violation semantics — the Rule↔Invariant boundary is *evidenced*. "Constraint" as used in the corpus: an umbrella word whose referents resolve to Rules, Invariants, or ADRs on inspection. First-class threshold objects (performance budgets, security thresholds with own identity and lifecycle): **no instance measured**. The nearest phenomena — the Q-2 bootstrap values (CW 30/30d, MAD 60d…) — are ratified *policy values*, carried inside decisions, not threshold objects.

**Step 3 · Nature.** **Terminology + semantic-overlap collision** (Constraint umbrella) sitting over a **validated concept boundary** (Rule↔Invariant).

**Step 4 · Alternatives.** Adopt Constraint as a third primitive — rejected: no operating instance; would import taxonomy. Merge Rule and Invariant under Constraint — rejected: destroys an *evidenced* distinction (different violation semantics, homes, identities). Keep Rule↔Invariant, treat "constraint" as colloquial umbrella — fits all observations.

**Step 5 · Resolution.**
- **Rule ↔ Invariant distinction: VALIDATED** (High — multiple independent id schemes, homes, and violation semantics; no contradiction).
- **Constraint as a distinct primitive: NOT ADOPTED** (Medium-High). The word remains legitimate colloquial usage; the glossary needs no entry. **Reconsideration trigger:** the first first-class threshold object with its own identity and lifecycle.
- **Deferred candidate Policy: VALIDATED as a projection pattern** — unblocked by this resolution. Operating evidence *found*: the **four constitutional policies** (ARB 2026-07-25 — Superseding Constitutional Publication · Evidence Preservation Window · Phase-1 Custodial Constraint · Quarantine Pending Determination), each a *named packaging of invariant-conditions + rules + scope*, plus the "temporal business policy" (Q-2 finality). Composition corrected from the candidate's {Invariants + Constraints + Scope} to the evidenced **{Invariants + Rules + scope statements}**. Confidence: **Medium-High**. Joins ADR in the projection-pattern family (M1 Part B).

**Step 6 · Lineage.** Candidate Constraint preserved with rationale (M1 Part B row → resolved-not-adopted); candidate Policy's original composition preserved beside the corrected one.

*KE fields — assumptions: the four constitutional policies are representative of a pattern, not four one-offs (supported by the additional temporal-business-policy instance) · residual uncertainty: single corpus; products with heavy regulatory load may genuinely need threshold objects (T-1) · affected: G-2/G-3 notes · M1 Part B rows Constraint + Policy · governance implications: none.*

---

## 4. OQ-5 — The Evolution Operation Canon

**Step 1–3.** The analytical object differs here: not a two-sided collision but an **operation inventory** under the commissioned canon question ("what is the canonical set, and are the absences deliberate?"). The presentation compresses; the analysis does not. Nature: **status classification** across the whole inventory.

**Step 4–5 · Resolution: a two-tier canon.**
- **Operational canon (evidence-admitted, 14):** supersede · version/re-issue · amend-additively · split · consolidate · refine · promote · demote/void/reject-with-record · reclassify · retire · archive · migrate/relocate · generalize · derive/regenerate. Each carries its precedent from item 1 Q4; admission rule going forward: **an operation enters the canon on its first rule-governed precedent, never by specification alone.**
- **Hypothetical tier (specified or proposed, unperformed):** merge (trigger: §2) · automatic invalidation (trigger: §2) · **expiry/aging** — the mechanism *exists* (`max_duration_days: 90` in the exceptions schema) but every record carries `expires: null, status: permanent` — **specified-but-unperformed**, the G-16 evidence datum; trigger: first record that actually expires.
- **Absence rationale (the OQ's second half):** for merge — covered by supersede+consolidate (Derived, Medium, §2); for expiry — Derived, Medium: nothing ages out *by time* because authority in this corpus is revoked only **forward** (supersession/void); time-based invalidation would contradict the observed authority model. Recorded as derived rationale, not as proof of deliberateness.

**Step 6 · Lineage.** The candidate model's five-operation list (refine/split/merge/supersede/invalidate) maps: 3 into the canon, 2 into the hypothetical tier — mapping preserved.

*KE fields — portability: the two-tier structure (canon + hypothetical-with-triggers) is the portable instrument; the current membership is local evidence · confidence: High on tier assignments (each is a Measured presence/absence), Medium on both absence rationales · assumptions: item 1 Q4's operation inventory is exhaustive for this corpus (its four sweeps ground the assumption; unverifiable absolutely — an unswept operation would be invisible, cf. T-4) · residual uncertainty: whether the 14-member canon is a general starter set or partly local cannot be known until a second corpus is evaluated (T-1) · affected: candidate model §4.1 standing · governance implications: none.*

---

## 5. C-4 + OQ-6 — Whole-System Completeness (resolved together, as commissioned)

**Step 1 · Restate.** Evidence: per-unit completeness richly defined (14-box DoD · per-slice DoD · session rules · 8 document quality gates); whole-system completeness "operates nowhere" — the strongest asymmetry in the domain, and Q8's candidate for "the domain's first genuinely new concept." Candidate: five qualitative dimensions (Domain Coverage · Decision Traceability · Contract Completeness · Lifecycle Hygiene · Verifiability), discriminative power indicated once (EKP lifecycle-hygiene ≈ 0). Collision: a candidate answer exists for a question the corpus never posed operationally.

**Step 2–3 · Evidence + nature.** **Granularity collision** (event-triggered per-unit vs state-assessed whole-system — the discovery's own Derived observation) compounded by **evidence insufficiency** for two dimensions. Per-dimension grounding against observed mechanisms:

| Dimension | Grounding in observed mechanisms | Standing |
|---|---|---|
| Decision Traceability | Traceability matrix · blueprint binding rule · P-2/P-3 | **VALIDATED** (High) |
| Verifiability | Conformance-test columns · fitness tests · ER-02 | **VALIDATED** (High) |
| Contract Completeness | Event catalog versioning — but M1 narrowed Contract to event scope | **VALIDATED — NARROWED** to event-contract completeness (Medium-High) |
| Lifecycle Hygiene | Statuses ubiquitous, but 8 vocabularies with no kind→vocabulary mapping | **VALIDATED — CONDITIONED on OQ-9** (ARB-owned): the dimension is *definable* now, *measurable* only after the mapping exists (Medium) |
| Domain Coverage | Requires a reference inventory of what should be covered — the bounded contexts, which are **M6's** output | **VALIDATED — SEQUENCED after M6** (Medium) |

**Step 4–5 · Resolution.**
- **C-4: the five-dimension set is VALIDATED as the candidate answer, with the per-dimension standings above** — not as a uniform block. None rejected; discriminative power already demonstrated once and re-demonstrable (any dimension that never scores low on any real system would be presumed ceremonial).
- **OQ-6: whole-PKS completeness is DEFINED (model recommendation) as a state-assessed, derived, multi-dimensional assessment over the validated dimension set** — distinct from every existing event-triggered per-unit mechanism, which is precisely why nothing existing performs it. Confidence: **Medium** (Synthesized-adjacent — integrates the dimension evidence with the event/state distinction).
- **Boundary handed to M3 (input, not decided here):** the dimension set mixes *sufficiency* flavor (Domain Coverage — is the knowledge enough?) with *adherence* flavor (Lifecycle Hygiene — does the system follow its own rules?). The adherence flavor is Knowledge-System Conformance territory. **How completeness and conformance relate is part of M3's kind decision (A/B/C)** — resolving it here would perform M3's work.

**Step 6 · Lineage.** Item 2 §6 preserved as origin; per-dimension dispositions recorded; the sufficiency/adherence observation recorded as M3 input.

*KE fields — assumptions: the EKP ≈0 score generalizes as discriminative-power evidence (one instance — thin, flagged) · residual uncertainty: dimension completeness itself (are five enough?) is unknowable pre-M6 · affected: G-15/G-16 notes · M3 inputs · governance implications: measurability of Lifecycle Hygiene depends on OQ-9 — reinforces an already-surfaced item, adds nothing new.*

---

## 6. Updated Collision Register (final M2 dispositions)

| # | Collision | Disposition | Confidence |
|---|---|---|---|
| C-1 | Requirement | **Resolved — represented-otherwise (distributed: Invariant + acceptance criterion + conformance test)**; candidate node not adopted; reconsideration trigger recorded | Medium-High |
| C-2 | Merge / Invalidation | **Resolved — both hypothetical, excluded from the operational canon, admission triggers recorded**; invalidation's need-evidence routed to M3 | High (tier assignment) / Medium (rationales) |
| C-3 | Constraint ≠ Invariant | **Resolved — Rule↔Invariant distinction validated; Constraint-as-primitive not adopted (umbrella usage); trigger recorded.** Unblocked: Policy validated as projection pattern (four constitutional policies) | High / Medium-High |
| C-4 | Completeness dimensions | **Resolved — dimension set validated with per-dimension standing** (2 grounded · 1 narrowed · 1 OQ-9-conditioned · 1 M6-sequenced) | Per-dimension, above |
| OQ-5 | Evolution canon | **Resolved — two-tier canon: 14 operational + 3 hypothetical-with-triggers; absence rationales derived** | High / Medium |
| OQ-6 | Whole-system completeness | **Resolved (model recommendation) — state-assessed derived assessment over the validated dimensions**; completeness/conformance boundary → M3 input | Medium |
| — | Deferred candidates | Constraint → not adopted (C-3) · Policy → **validated, projection pattern** (C-3) · Requirement → not adopted as first-class (C-1) | — |

**Also updated (from the M1 checkpoint review round):** the two candidate collision-register additions recorded in dossier §4a — *lifecycle consolidation* and *concept abstraction* — were both effectively addressed by M1's register (the abstraction was evaluated concept-by-concept; the single-lifecycle proposal remains unadopted with OQ-9 surfaced). Recorded here so they do not float.

## 7. Surfacing Register update

**No new governance questions.** M2's two contacts with governance territory both reinforce items already surfaced in M1 Part D rather than adding entries: Lifecycle-Hygiene measurability → **OQ-9** (already surfaced) · whole-system assessment implies an assessment owner → **OQ-PKS-3** (already ARB-routed). OQ-2 and OQ-7 again did not bite — consistent with the plan's M6 prediction. *(An update that honestly reports "no new entries" is itself the deliverable.)*

## 8. Threats to Validity (continuing M1's appendix)

| # | Threat | Mitigation | Residual |
|---|---|---|---|
| T-6 *(new)* | **Resolution-order coupling** — C-3's outcome changed Policy's disposition; collisions are not independent, and a different resolution order could have framed the evidence differently | Dependencies recorded explicitly at each affected step; every resolution cites evidence that is order-independent | Unverified — only a re-run in a different order would confirm order-independence |
| T-7 *(new)* | **Taxonomy fit by the same rater** — the collision-type classifications (step 3) were assigned by the same agent that then resolved them; two types were added mid-run | Taxonomy extensions recorded as lineage; classifications carry rationale | Inter-rater reliability of the taxonomy unknown (compounds T-5) |
| T-1..T-5 | Carried unchanged from M1 | — | T-2 note: this report again produced by the same agent lineage |

## 9. M2 Self-Assessment (commission terms)

No tactical DDD (no entity/aggregate/repository/service/event-design/API/database appears as design) ✅ · no architecture ✅ · evidence preserved — every step-2 citation traces to accepted artifacts; no new discovery performed ✅ · lineage preserved — every rejected/not-adopted interpretation retained with rationale and reconsideration trigger ✅ · uncertainty preserved — two Derived rationales explicitly capped at Medium; per-dimension standings instead of a blanket verdict; "never observed ≠ cannot exist" applied throughout ✅ · governance boundaries respected — zero governance questions answered; two reinforcements routed to existing surfaced items; OQ-11 kept out of scope despite the advisory paraphrase ✅ · every commissioned collision evaluated ✅ · the model is more precise without new detail — net concept count *decreased* (two candidates not adopted; one validated as pattern, not concept) ✅.

---

## 10. Methodology notes for reuse (checkpoint refinements, incorporated 2026-07-28)

*Per the ACCEPT-WITH-REFINEMENTS disposition. All four are candidate methodology refinements with **one operational instance each** (this report); per the evidence-driven-promotion principle this review process itself established, they are **routed to the retrospective inbox beside the methodology candidate — recorded, not promoted**. Qualifying evidence: successful reuse in a second work package or corpus.*

1. **The collision taxonomy is extensible by evidence, not fixed.** Two types (Representation; Status) were added mid-run because the actual collisions required them; extensions are recorded as lineage. Future runs extend the same way — a taxonomy that cannot grow forces misclassification; one that grows silently loses comparability.
2. **Reconsideration-trigger standard.** Every resolution's trigger should carry three parts: *(a) the trigger condition · (b) the evidence that would satisfy it · (c) the reopening authority.* For all M2 triggers, (c) is: the modeling checkpoint reviewer for concept-level reopenings; the ARB where the item touches a surfaced governance question.
3. **"Analytically equivalent ≠ structurally identical"** (chair's distinction, M2 verification round) — candidate methodology-glossary entry: sections analyzing different object kinds may compress presentation without compressing analysis.
4. **Presentation convention (chair's substantive observation):** the observed-mechanism vs synthesized-recommendation distinction should be *visually* consistent, not only correctly labeled. Convention adopted for future artifacts in this workstream: claims carry their epistemic class inline at first use (**Observed:** · **Derived:** · **Synthesized:** · **Recommendation:**), continuing R-36/M0-rubric vocabulary rather than inventing new markers.

---

*Traceability: executes WP M2 of `docs/plans/20260728-1624-pks-strategic-modeling-plan.md` (APPROVED) · method: PA M2 commission (6-step · taxonomy · KE fields · scientific discipline), 2026-07-28 · inputs: item 1 Q4/Q8/§1.2 · item 2 §1.2/§3/§4/§6 · synthesis §4 · M0 glossary + rubric · M1 register + portability rubric + Part D · four-constitutional-policies evidence: MEMORY/EPIC-003 record (ARB 2026-07-25) · scope corrections vs advisory paraphrase recorded in header. **STOP — M2–M4 batch checkpoint per the plan's cadence: M3 (Conformance kind) opens only on explicit confirmation.***
