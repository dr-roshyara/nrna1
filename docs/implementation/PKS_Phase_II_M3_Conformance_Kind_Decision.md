# PKS Phase II — M3: Knowledge-System Conformance Kind Decision (A / B / C)

| | |
|---|---|
| **Kind** | Strategic Modeling artifact (WP M3) — determines the *kind* of the Knowledge-System Conformance concept from evidence. **Strategic knowledge-model level only: no mechanism designed, no assessor assigned, no architecture, no tactical DDD.** |
| **Authority** | Generated — never authoritative without human review. The outcome is a **model recommendation**; the checkpoint disposes. |
| **Status** | **ACCEPTED — M3 checkpoint disposition (ARB chair, 2026-07-28): "ACCEPT WITH NO REQUIRED CHANGES… The recommendation that Knowledge-System Conformance be modeled as a derived assessment producing a Verdict is accepted as the working strategic model for subsequent work. The recorded reversal condition remains active… M4 is authorized to begin."** All five review dimensions Excellent; remaining observations classified by the chair as natural limits of the evidence, not deficiencies. *(Supersedes the pre-disposition status below, which is preserved as history:)* EXECUTION COMPLETE — awaiting checkpoint disposition. Pre-checkpoint analytical refinement round applied (chair, 2026-07-28; conclusion unchanged by instruction): Step 5 restructured to compare the **competing Derived interpretations head-to-head** before recommending · parsimony explicitly demoted to tie-breaker · rhetorical "killed" replaced with "falsified under the current evidence." *(Timing note, for the record: the chair's review was framed pre-write; the draft already existed — refinements applied to the produced draft, consistent with the M0–M2 refinement pattern.)* **Second review round (chair, same date): READY FOR CHECKPOINT REVIEW WITHOUT FURTHER ANALYTICAL CHANGES — author self-refinement stops here (diminishing returns); the next valuable step is independent review.** |
| **Commission** | `docs/plans/20260728-1624-pks-strategic-modeling-plan.md` (APPROVED) WP M3 + the PA's M3 execution commission (Plan Mode required; 8-step structure; falsification discipline) + five PA refinements at work-plan approval (Step 3.5 classification checkpoint · strongest-argument-against rule · abstraction checks · No-False-Symmetry rule · KE-leads role order). Work plan: `.claude/plans/shiny-hopping-nest.md` (approved in Plan Mode). |
| **Role order** | Senior Knowledge Engineer leads; Chief Strategic Domain Architect constrains. The phenomenon is classified as knowledge before any domain-model implication is drawn. |
| **Placement** | `docs/implementation/`, beside M0–M2. |

---

## Executive Summary

**Recommendation: B — Knowledge-System Conformance is a *derived assessment producing a Verdict*, computed over per-object conformance observations.** *(The summary states the outcome; the argument that carries it is Steps 3.5–5, including an explicit comparison of the two competing Derived interpretations — the recommendation rests on that comparison, not on any single inference.)* Confidence: **Medium** (reduced from Medium-High by the strongest-argument-against rule — the ontology/epistemology objection is only partially answerable from accepted evidence). A is **falsified under the current evidence** on the register's own admission standard (the Runbook precedent: zero operating instances as an identity-bearing object). C is **partially absorbed**: its per-object grain is real and Measured, and survives as B's substrate — expressed entirely through already-validated concepts (Observation, K-10 · criterion = Rule/Invariant, K-2/K-3 · Verdict, K-15), so **no new first-class concept enters the model** (a parsimony property, which functions as a **tie-breaker, not evidence**). The M2-handed completeness/conformance boundary is settled at kind level: both are sibling derived assessments distinguished by criteria family (sufficiency vs adherence). A falsifiable reversal condition is recorded. The assessment's *owner* is deliberately not named — that is OQ-PKS-3, ARB-owned, reinforced not resolved.

---

## Step 1 — The Question, Restated

Phase I discovered a recurring phenomenon — *rules written, instances don't follow* — across three independent contexts, coined **Knowledge-System Conformance** for it ("the degree to which the operational knowledge system conforms to its declared governance, lifecycle, and structural rules"), and deliberately left its **kind** open because all three candidate kinds explained the evidence. M2 resolved the neighboring completeness question and handed M3 one boundary input. The question M3 answers, and only that question:

> **What kind of concept is Knowledge-System Conformance?**

*Abstraction check: question restated at knowledge-model level; no drift.*

## Step 2 — The Competing Hypotheses

| | Hypothesis | What it would mean for the model |
|---|---|---|
| **A** | A **first-class concept** — an identity-bearing knowledge object like Decision or Rule | Conformance instances would be governed objects with their own identity and lifecycle |
| **B** | A **derived assessment producing a Verdict** — an evaluative act/result against declared rules | Conformance exists as knowledge when an assessment is performed; its result is a Verdict (K-15) |
| **C** | An **emergent per-object property aggregating to system level** — each object conforms or not; the system's conformance is the aggregate | Conformance would be a property carried by every governed object, with system-level conformance emerging from the population |

Search space constrained to these three. A fourth kind may be proposed **only if accepted evidence positively falsifies all three**; similarity, discomfort, or preference are not grounds.

## Step 3 — Evidence Assessment

**Supporting evidence (per hypothesis, gathered before any evaluation):**

*The phenomenon itself* — **Observed/Measured (Phase I):** AKB: recommended reorg never executed; maturity model scored once (~10–15%), never re-scored. EKP: 36 carded of self-claimed ~2,500; 0 `reviewed_by` against 24 `approved`; flagship duplicate-authority lint rule a structural no-op; CI warn-only. Platform enforcement asymmetry (third context). Definition coined in the accepted synthesis; 3-instance evidence standard met there.

*Per-object conformance facts that already exist* — **Measured (Phase I / M1):** guide-convention conformance 6 of 31 areas (G-14 datum) · exception-record expiry declared (`max_duration_days: 90`) but every record `expires: null, status: "permanent"` (G-16) · 105/105 archived files lacking the mandated `status: archived, authority: historical` markers · the FROZEN Blueprint still encoding superseded ADR-T17. Each is *an object's state relative to its declared rules* — and each was measurable object-by-object.

*Assessment machinery that operates* — **Observed:** Verdict (K-15/G-15) validated — gates and reviews issue PASS/FAIL/…/CERTIFIED against criteria, "issued by a gate or review, never by the author" · the closure-verification instrument executed this program (R-26 shape: run → capture → verdict → stop) · fitness tests · the AKB maturity model as a *specified whole-system assessment executed exactly once*.

**Contradicting/negative evidence:** **Measured (Phase I):** *no mechanism observes conformance anywhere* — operational status **Absent** (the accepted three-tests table: High confidence · portable · Absent). No identity-bearing "conformance record" exists in the corpus. **Observed:** the corpus's documented failure mode is exactly what *unobserved* conformance produces — stale boards, orphaned handbook, count mismatch inside a FROZEN catalog.

**Neutral:** M2 defined whole-system completeness as a "state-assessed derived assessment." Under the **No-False-Symmetry rule this is not evidence for B** — it is recorded here only so its pressure is visible and discounted.

*Abstraction check: all evidence at knowledge-model level; no drift.*

## Step 3.5 — Concept Classification (mandatory checkpoint, before A/B/C fit)

Classifying the observed phenomenon on its own terms, without the hypotheses in view:

1. **At object grain**, the evidence presents as a **relational property**: an object's state *relative to its declared rules* (conformant / non-conformant / partially). Not intrinsic — it exists only relative to a declared criterion. Property-like. **(Observed basis: the four Measured per-object facts.)**
2. **At system grain**, the evidence presents as a **characteristic knowable only through an assessment act**: every system-level conformance statement in the accepted record — the three founding instances, the 6/31 measurement, the 105/105 count — came into existence *when someone ran an assessment* (the discovery sweeps, the audits, the one-time AKB scoring). **No system-level conformance statement exists in the corpus without an assessment act behind it — no counterexample found. (Derived, from an exhaustive scan of where such statements occur.)**
3. The phenomenon is therefore classified as: **a relational property at object grain, whose system-level form is an assessed magnitude** ("the *degree* to which…" — the accepted definition itself is degree-shaped, and a degree is a measured/assessed quantity, not a free-standing object).

This classification was produced before hypothesis evaluation and is not forced into A/B/C; the evaluations below test each hypothesis against it.

*Abstraction check: classification is epistemological; no mechanism, no owner, no architecture entered.*

## Step 4 — Independent Evaluations

### Hypothesis A — first-class concept

**For:** stable name and definition; 3-instance evidence standard met; the discovery mused that this territory "could become the heart of the domain" — central concerns often merit first-class standing.
**Against:** **zero operating instances as an identity-bearing object** — nothing in the corpus is a governed Conformance record with identity and lifecycle. The M1 register's admission standard requires operating evidence, and its own precedent is exact: **Runbook was rejected for being declared-with-zero-instances.** Could the three founding instances count as instances *of the concept*? No — they are instances of the *phenomenon* (states of divergence), observed through analysis; they are not governed objects. **(Derived.)** Deeper: admitting A now would *specify an object before any instance operates* — structurally the very AKB/EKP move (specify first, instances never follow) that the concept was coined to name. A first-class Conformance adopted without an operating instance would begin life non-conformant to the register's own standard.
**Fit to Step 3.5:** poor — the classification found a relational property + assessed magnitude, not an object.
**Falsification outcome: A is falsified under the current evidence** — by the register's own standard, unless phenomenon-instances are accepted as object-instances, a reading the evidence does not support. (Also the Step 3.5 fit-test: the classification found no object.)

### Hypothesis B — derived assessment producing a Verdict

**For:** every system-level conformance statement in the record arose from an assessment act (Step 3.5 §2 — no counterexample); the output type already operates (Verdict, K-15); the instrument shape already operates (R-26; the closure verification run this program); the accepted definition is degree-shaped, and a degree is an assessed magnitude; and what discovery flagged as missing is precisely *"no mechanism observes conformance"* — **the absent thing is an assessment**, so the kind that names it is assessment. **(Observed + Derived.)**
**Against:** B alone gives the per-object facts no home — the 6/31 and 105/105 facts exist object-by-object regardless of whether any whole-system assessment ever runs. An assessment-only reading floats above its substrate.
**Operationality guard:** B classifies the **kind**, not the status. The operational status of whole-system conformance assessment remains **Absent** (per the accepted three-orthogonal-tests discipline); choosing B asserts nothing into operation.
**Fit to Step 3.5:** strong at system grain; incomplete at object grain unless the substrate is named.
**Falsification outcome: B survives, with a required nuance** — it must be defined *over* per-object conformance observations.

### Hypothesis C — emergent per-object property aggregating to system level

**For:** the per-object grain is real and **Measured** — objects demonstrably carry conformance states relative to their declared rules; aggregation to system level is exactly what discovery performed (per-object measurements → system-level claims).
**Against:** "emergent … aggregating" without an assessment act is unobservable — **properties do not report themselves**, and the corpus's documented failure mode is precisely that unobserved conformance rots silently (stale boards; the frozen-carrier/superseded-concept divergence sat undetected until an audit read it). In this domain's own constitution, system-level knowledge exists as knowledge only when captured by a recorded act ("the repository is the authoritative memory"; observations are first-class *as recorded entries*, K-10; "documents record governance"). An unassessed aggregate is, in this domain's terms, not yet knowledge. **(Derived from the domain's recorded constitution — this is the load-bearing inference and is marked as such.)**
**Fit to Step 3.5:** strong at object grain; fails at system grain — the classification found the system-level form to be an *assessed* magnitude, and C provides no assessor.
**Falsification outcome: C is not falsified at object grain, and is falsified at system grain** — it names the substrate but not the knowability.

*Abstraction check (Step 4): evaluations reference no mechanism design, no assessor, no storage, no tooling; the R-26/lint mentions are evidence citations, not designs.*

## Step 5 — Comparative Analysis

**A vs the field:** A is the only hypothesis falsified outright under the current evidence, and by the model's *own* admission standard rather than by preference — the strongest available grounds. Rejected; preserved below with its trigger.

### 5.1 The real contest: two competing Derived interpretations, compared head-to-head

*(Chair refinement: B and C each rest on a Derived interpretation of the same Observed facts. Neither interpretation is itself Observed. The recommendation must come from comparing them — Observed → Derived-B vs Derived-C → comparison → recommendation — not from moving directly from one Derived reading to a conclusion.)*

| | **Derived-B:** "System-level conformance exists *as knowledge* only via a recorded assessment act" | **Derived-C:** "Conformance *is* the property; assessment merely reveals it — the property exists whether or not measured" |
|---|---|---|
| **Grounding** | Corpus-internal, stated norms: "The repository is the authoritative memory" (Observed rule) · Observations are first-class *as recorded entries* (K-10, validated form) · "documents record governance; decisions create it" (Observed rule) · zero counterexample in the record: no system-level conformance statement exists without an assessment act behind it (Derived from scan, Step 3.5 §2) | A general realist reading, supported by one genuine fact: the per-object states pre-existed their measurement (the 105 files lacked markers *before* anyone counted — Observed) |
| **Evidence coverage** | Grounded in **stated corpus law** — norms the domain itself declares and operates | Grounded in a **philosophical stance imported from outside the corpus** — no corpus rule states it; its support is the object-grain fact, which Derived-B also accommodates (as substrate) |
| **The degree test** | The accepted definition is degree-shaped ("the *degree* to which…" — Observed). A degree over a population of per-object states exists only when computed — an act. Derived-B accounts for the degree's mode of existence | Derived-C has **no account of how the degree exists uncomputed**: it explains the states, not the magnitude the definition names |
| **The consequences test** | Under Derived-B, the three founding instances entered the domain's registers (citation, governance, correction) exactly when assessed — which is what the record shows | Under Derived-C, those instances "existed" system-level before discovery — yet nothing in the corpus could cite, govern, or correct them until assessed. In every practical register this domain operates, they began to function at assessment (Derived, checkable against the record) |
| **What it concedes** | Must absorb the object-grain priority: the states are real before measurement → C's grain as substrate | Must concede system-level knowability requires the act it declines to include |

**Comparison verdict:** **Within this evidence-first methodology, interpretations grounded in explicit corpus evidence are preferred over interpretations that require external philosophical assumptions** — a methodological selection rule of this program, not a claim that the realist reading is *wrong*; it is simply outside the accepted evidence base. On that rule, and because Derived-B survives the degree test and the consequences test where Derived-C fails both, **Derived-B is preferred**. Scope note: **the degree test discriminates only between these two hypotheses** — it is a fit-test against this concept's degree-shaped definition, not a universal epistemological argument. This remains an evidence-coverage argument between two inferences — not proof; both remain Derived; the selection is itself a judgment. That is precisely why confidence is **Medium** and cannot honestly be higher.

### 5.2 Composition and parsimony

B *selected*, with C's grain recorded as B's substrate — expressible without a fourth kind. Under this reading, conformance requires **no new first-class concept**: a conformance assessment evaluates **Observations** (K-10) against **criteria** (K-2/K-3/declared conventions) producing a **Verdict** (K-15). **Parsimony status (chair refinement): this is a tie-breaker, not evidence.** It played no role in the §5.1 comparison; it is invoked only after Derived-B won on evidence coverage, as the reason to express the winner compositionally rather than by minting a concept. Had §5.1 tied, parsimony alone could legitimately have tipped expression, never truth.

### 5.3 Strongest argument against the preferred hypothesis *(written before selection)*

*"B mistakes epistemology for ontology. Conformance IS the property (C); assessment is merely how we come to know it. Selecting B because assessments are how we found the phenomenon commits the measurement fallacy."*

**Answer from accepted evidence:** the §5.1 grounding row — in *this* domain the recorded constitution makes capture-by-recorded-act the mode of existence of system-level knowledge, so here the epistemology is part of the ontology for system-level claims. **But this answer is itself the Derived-B interpretation restated — it cannot fully answer an objection to that interpretation without circularity, and it does not dissolve the objection at object grain, where C's property reading stands.** Per the rule: partially answerable → **confidence reduced from Medium-High to Medium.**

### 5.4 No-False-Symmetry audit

The outcome mirrors M2's completeness decision (both land on derived assessment). Verified: no step above cites M2's decision as support — §5.1's discriminators are the definition's degree shape, corpus-internal norms, and the consequences test, all independent of M2. The symmetry is an *outcome*, recorded as bias risk T-8, not a ground.

*Abstraction check: comparison stayed at kind level; the completeness/conformance boundary below is settled at kind level only.*

## Step 6 — Selected Recommendation

> **Recommendation:** Knowledge-System Conformance is modeled as **(B) a derived assessment producing a Verdict**, defined **over per-object conformance observations** (C's grain as substrate), composed entirely from already-validated concepts: Observation (K-10) + declared criterion (K-2/K-3/conventions) + Verdict (K-15). **A is rejected** (register's own admission standard; Runbook precedent). **No new first-class concept enters the model.**

- **Confidence: Medium** (reduced per the strongest-argument rule; the load-bearing inference — recorded-act-as-mode-of-existence — is Derived, not Observed).
- **Assumptions:** (1) the domain's recorded-knowledge constitution extends to conformance claims (Derived, no counterexample); (2) per-object conformance facts can serve as assessment inputs without new machinery (supported: discovery already used them exactly so).
- **Residual uncertainty:** a second corpus might natively reify conformance objects (audit/compliance domains plausibly do) — there, A could be the right kind. This selection is evidence-bound to this corpus (T-1).
- **Portability:** assessment-against-declared-rules is portable as a shape; the **selection** among kinds is local-evidence-bound. The trichotomy-plus-falsification instrument is the portable part (consistent with the methodology's portability posture).
- **Boundary settled (M2 §5 input):** **completeness and conformance are sibling derived assessments** distinguished by criteria family — completeness assesses *sufficiency* (is the knowledge enough?), conformance assesses *adherence* (does the system follow its own declared rules?). Same kind; different criteria; both produce Verdicts. M2's adherence-flavored dimension (Lifecycle Hygiene) is thereby classified as a conformance criterion feeding a sibling assessment — a re-labeling of one dimension's *family*, changing none of M2's dispositions.
- **Downstream implications (stated, not performed):** M4 — no new identity scheme needed for conformance (it rides Observation/Verdict identity; M4 should verify this holds). M6 — conformance is an assessment capability, not a boundary candidate by itself. The invalidation need-evidence (M2 §2) lands coherently: automatic invalidation, if ever admitted, would be a *consumer* of conformance verdicts — noted as coherence, not designed.
- **Governance guard:** **who performs/owns the assessment is not named here** — that is **OQ-PKS-3** (cross-artifact consistency ownership + cadence), ARB-owned, surfaced since M1 and *reinforced* by this decision: choosing B makes the ownership vacuum the concept's single most consequential open dependency. Surfaced, not resolved.

## Step 7 — Reversal Condition (mandatory, SR-4; per the M2 §10 trigger standard)

| Part | Content |
|---|---|
| **Condition** | Evidence that conformance operates as an identity-bearing governed object (records with their own ids and lifecycle, governed as first-class), **or** an operating conformance mechanism whose semantics demonstrably cannot be expressed as Observation + criterion + Verdict |
| **Evidence required** | The first **rule-governed precedent** (the recorded canon-admission standard — cited as a standard, not as symmetry-evidence), captured in the repository |
| **Reopening authority** | The modeling checkpoint reviewer for the kind decision itself; **the ARB** where reopening intersects OQ-PKS-3 (assessment ownership) or any other surfaced governance question |

## Step 8 — Lineage

- **A — rejected, preserved:** full reasoning in Step 4; reconsideration trigger = the reversal condition above (its first limb is precisely A's revival path).
- **C — partially absorbed, preserved:** its object-grain reading survives *inside* B as the substrate; its emergent-aggregation-without-assessor reading is rejected at system grain with rationale (properties don't report themselves; the domain's failure mode is silent rot). If a future model wants per-object conformance as a modeled property attribute, that is an M4-or-later question riding this record.
- **Fourth-kind check:** not triggered — B survived; the composition was expressible as B-with-substrate, not a new kind.
- Nothing deleted; the deliberately-open kind question (synthesis §4) is now **closed by recommendation, pending checkpoint disposition**.

## Knowledge Engineering Notes (the commission's six questions)

| Question | Answer |
|---|---|
| Observable phenomenon? | Yes — divergence between declared rules and operating instances; 3 contexts + 4 per-object Measured facts |
| Evidence demonstrating it? | Measured counts (6/31 · 105/105 · 0-vs-24 · 36/~2,500) + Observed stall narratives |
| What is being assessed? | Objects' states relative to their declared rules; at system level, the population of such states |
| What is produced? | A Verdict (K-15) — degree-shaped, criteria-referenced |
| Intrinsic or derived? | **Derived** — relational at object grain (object vs its rules); assessed at system grain |
| Independent existence? | The per-object facts: yes (they exist unmeasured). The system-level *knowledge* of conformance: no — it exists as knowledge only via a recorded assessment act |

## Threats to Validity (T-1..T-7 carry; new)

| # | Threat | Mitigation | Residual |
|---|---|---|---|
| T-8 *(new)* | **Symmetry bias toward B** — M2 had just defined completeness as a derived assessment | No-False-Symmetry rule applied; comparison audited for M2-as-support citations (none) | Unconscious pattern-matching by a single rater not fully excludable (compounds T-5) |
| T-9 *(new)* | **Measurement-fallacy risk** — inferring ontology from epistemology | The objection was written as the strongest counter *before* selection; answered only partially; confidence reduced accordingly | The Derived constitutional reading could be wrong; the reversal condition's second limb specifically covers it |

## Self-Assessment (commission completion rule)

Commissioned question answered — one recommendation, falsifiable ✅ · rejected hypotheses traceable with rationale and triggers ✅ · uncertainty explicit (Medium confidence, reduction reasoned; residuals per threat) ✅ · no governance decision made (OQ-3 reinforced-surfaced; no assessor named) ✅ · no tactical DDD introduced (abstraction checks passed per section) ✅ · recommendation falsifiable with recorded reversal condition (3-part standard) ✅ · Step 3.5 classification performed before hypothesis fit ✅ · strongest-argument-against written before selection and honored in the confidence grade ✅ · methodology observations: one candidate (the Step 3.5 classify-before-fit checkpoint proved discriminating — it falsified A's fit before comparison) → **routed to the retrospective inbox, not promoted** ✅.

---

## Checkpoint Amendment — F-BCP-2 (additive; authority-confirmed 2026-07-28)

**Checkpoint finding (quoted):** *"M3's KE notes state the assessment produces 'a Verdict (K-15) — degree-shaped.' But K-15's observed semantics are categorical (PASS · FAIL · WARN · INCONCLUSIVE · EMERGENT · CERTIFIED); the one observed degree-valued output (the AKB's ~10–15% score) behaved as a measured value — a derived Observation — not a Verdict token."* (`PKS_Phase_II_M2_M3_M4_Batch_Checkpoint_Report.md`, F-BCP-2.)

**Amendment (additive — the historical wording above stands unedited):** the KE-notes row "What is produced? → A Verdict (K-15) — degree-shaped, criteria-referenced" is refined to: **the assessment produces derived Observation(s) — the measured degree — plus a Verdict (K-15) — the categorical judgment against criteria.** The composition strengthens (the degree finds its natural home in K-10; Verdict keeps its observed categorical semantics); **the kind decision (B) and its Medium confidence are untouched.**

**Authority chain:** batch checkpoint recommendation (2026-07-28) → PA/DA confirmation ("yes", intake record in session log 2026-07-28) → this amendment. *Not a reopening — reopening-condition 3 in its mildest, checkpoint-directed additive form.*

---

*Traceability: executes WP M3 of `docs/plans/20260728-1624-pks-strategic-modeling-plan.md` (APPROVED) under the PA's M3 commission + five plan-approval refinements (work plan `.claude/plans/shiny-hopping-nest.md`, approved in Plan Mode) · inputs: synthesis §3.3/§4 (definition, 3 instances, A/B/C, Absent status) · item 1 Q5/Q7/§2 (per-object Measured facts, failure modes) · M1 (G-14/G-15/G-16, admission standard, Runbook precedent, T-1..T-5) · M2 (§2 invalidation need-evidence · §5 boundary input · §10 trigger standard · T-6/T-7) · brainstorming assessment (EKP-shape risk note) · presentation per M2 §10 convention. **STOP — checkpoint review before any M4 work.***
