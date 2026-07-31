# PKS Phase II — M7 Relationship Validation Review

| | |
|---|---|
| **Kind** | **Focused validation** (not a Critical Review): does each M7 relationship classification follow from the **Strategic DDD definition** of the pattern and the cited evidence — or from architectural intuition? Validates classifications only. |
| **Authority** | Generated — never authoritative without human review. **This review recommends; it does not reclassify.** Two output classes are kept strictly separate (§5): **editorial/language refinements = APPLIED** (PA-directed, evidence-neutral) · **substantive reclassification findings = RECOMMENDED ONLY**, awaiting disposition. Applying a substantive reclassification here would collapse review into decision — the one thing this program's governance has never done. |
| **Status** | **EXECUTED. Outcome: 3 of 5 classifications VALIDATED · 2 require REFINEMENT · 1 pattern-usage defect found (VF-2). Relationship model: READY FOR M8 with recorded refinements pending. STOP.** No M8, no discovery, no governance act. |
| **Commission** | M7 Relationship Validation Review Commission (PA, 2026-07-28) — eight-step per-relationship validation; the seam-observation classification; language refinement. |
| **Not authorized (honored)** | No boundary rediscovery · no governance reopening · no methodology change · no BC or seam reclassification · no M8. |
| **Placement** | `docs/implementation/`, beside the M7 model. |
| **Disposition History** | 2026-07-28: executed (3 validated · 2 refinement-required · 1 pattern-usage defect). · 2026-07-28: **PA-suggested evidence matrix added (§6.1, additive)** — separates evidence for *dependency* · *ownership* · *pattern name* per relationship, making the §6 asymmetry visible per row. Dependency and pattern grades re-present §6; the ownership column is assessed from M7 §8's recorded ownership statements. **No classification changed; VF-1/2/3 remain recommended-only.** |

**Pattern definitions used as the validation standard** (Evans, *Domain-Driven Design*, Part IV — Strategic Design; the definitions the commission tabulates). Where a definition carries a **necessary condition** the commission's one-line summary omits, that condition is named explicitly below — it is where two findings live.

---

## 1. Executive Summary

**Validation is discriminating, not confirmatory: three classifications hold against the pattern definitions, two do not fully hold, and one non-pattern usage is defective.**

| # | Relationship | M7 classification | **Validation verdict** |
|---|---|---|---|
| R-1 | Seam (Norm Custody) → CBC-1 | Customer/Supplier | ⚠️ **REFINEMENT REQUIRED** (VF-1) — the definition's *negotiation/accommodation* condition is unevidenced |
| R-2 | CBC-1 → Seam (Authorization Acts) | C/S over a Published Language | ✅ **VALIDATED** (with a scope note on "published") |
| R-3 | Sources → CBC-2 | Conformist (one-way) | ✅ **VALIDATED — the strongest fit in the model** |
| R-3b | — the authority direction of R-3 | "Separate Ways in the authority direction" | ⚠️ **PATTERN-USAGE DEFECT** (VF-2) — Separate Ways presupposes *no* relationship; these contexts are related |
| R-4 | PKS ↔ CBC-4 (outer edge) | C/S across the domain edge | ⚠️ **REFINEMENT REQUIRED** (VF-3) — same unevidenced condition as VF-1, plus a pattern-applicability question |
| R-5 | CBC-2 ↔ CBC-4 | Separate Ways | ✅ **VALIDATED — the correct use of the pattern** (and the contrast that exposes VF-2) |

**The single most useful result:** R-5 and R-3b use the *same pattern name* for structurally different situations — one correctly (no relationship at all), one incorrectly (a constraint on an existing relationship). The validation caught this only because it checked the pattern's **necessary condition** rather than its one-line summary. **Derived:** the defect class is "pattern applied by resemblance to its summary rather than by satisfaction of its definition" — precisely the architectural-intuition risk this review was commissioned to test for. It was present, and it was found.

**Seam observation (§4):** the PA's classification is confirmed and now folded into M7 — **architectural evidence supporting future re-assessment**, an independent *lens* on the same corpus, never independent evidence. This validation adds the sharper reason: treating it as confirmation would repeat exactly the error R-M6-6/B-1 confirmed in execution.

---

## 2. Validation Method

Per relationship, the eight commissioned steps: quote the classification · state the pattern definition **including its necessary conditions** · identify M7's cited evidence · verify the evidence is *consistent with* the pattern (never "proves") · identify alternatives considered · verify the rejection rationale · record confidence · record residual uncertainty.

**Falsification stance:** each classification was tested by asking *what would have to be true for this pattern to be the right name, and is that recorded anywhere?* — not by asking whether the pattern is plausible. A pattern that merely fits loosely fails.

**Boundary respected throughout:** the validation tests the *naming of relationships*. It does not test, revisit, or comment on whether CBC-1/CBC-2/CBC-4 are correctly bounded or whether CBC-3 is correctly a seam — those are disposed and frozen.

---

## 3. Relationship Validations

### R-1 — Seam (Norm Custody) → CBC-1 · classified **Customer/Supplier** · ⚠️ REFINEMENT REQUIRED

**Classification quoted:** *"CUSTOMER/SUPPLIER (seam upstream) … CBC-1's own UL statement imports its criteria: 'criterion (a Rule or Invariant, used here, owned elsewhere)'."*

**Pattern definition, with its necessary condition stated:** Customer/Supplier establishes an upstream *supplier* and downstream *customer* in which — this is the condition Evans makes load-bearing — **the downstream's needs enter the upstream's planning**: requirements are negotiated, downstream priorities are budgeted for, and joint acceptance tests validate the interface. The pattern exists precisely to *fix* the case where an upstream can succeed while ignoring downstream.

**Evidence cited in M7:** the UL import statement (verbatim, M6 §7.4) · violation semantics defined norm-side (L2-5).

**Verification:** the evidence is consistent with an **upstream/downstream dependency** — that much is solid and verbatim. It is **not consistent with, and nowhere addresses, the negotiation/accommodation condition.** Nothing in the corpus records norms being defined with assessment's needs in view, any negotiation over criteria, or any joint validation of the interface. **The upstream in this relationship demonstrably can and does succeed without accommodating the downstream** — which is the situation Customer/Supplier is the *remedy* for, not a description of.

**Alternatives, re-examined (M7 rejected two; this review finds the rejections sound but the survivor wrong):**
- *Conformist* — M7's rejection holds: CBC-1 does not adopt the norm model; it wraps criteria in its own vocabulary (degree · Verdict · Finding). Confirmed rejected.
- *Shared Kernel* — M7's rejection holds: ownership is explicitly one-sided ("owned elsewhere"). Confirmed rejected.
- *Anti-Corruption Layer* — **not considered by M7, and it is the closest live candidate.** CBC-1 consumes an upstream model it cannot influence and **translates it into its own vocabulary** — definitionally ACL-shaped (a downstream translation layer preserving the consumer's model integrity). What is missing for a clean ACL is an identified *layer*: the translation is performed by assessment acts, not by a named isolating component — and naming one would be design, not validation.

**Recommendation (advisory):** restate R-1 as **"upstream/downstream dependency — criteria supply; pattern undetermined between Customer/Supplier and ACL-shaped consumption; the C/S negotiation condition is unevidenced."** Retain the direction, the evidence, and the U-1 marker unchanged. **Confidence in the direction: Medium-High. Confidence in the pattern name: Low** — this is the finding.

**Residual uncertainty:** U-1 (upstream partner is a candidate seam) · the ACL question cannot resolve without evidence of a translation locus, which does not exist strategically today.

### R-2 — CBC-1 → Seam (Authorization Acts) · classified **C/S over a Published Language** · ✅ VALIDATED

**Classification quoted:** *"CUSTOMER/SUPPLIER over a PUBLISHED LANGUAGE (CBC-1 upstream supplier of evidence; authority downstream customer)."*

**Definitions:** Customer/Supplier (as above, including accommodation) · Published Language: a **well-documented shared language** used as the common medium of interchange between contexts.

**Evidence cited:** P-8 (*"knowledge as input to authority, never as authority"* — verified) · *"issued by a gate or review, never by the author"* (M0 G-15) · the Verdict token set: PASS · PASS AFTER CORRECTION · WARN · FAIL · INCONCLUSIVE · EMERGENT · CERTIFIED (item 1 K-15).

**Verification — the accommodation condition, which failed at R-1, is satisfied here:** P-8 states the upstream's *purpose* is to serve the downstream's decisions — assessment exists to feed authority. An upstream constitutionally defined as input-to-authority is one whose output is shaped by the customer's need, which is what the pattern requires. **Consistent.** And the token set satisfies Published Language: enumerated, documented in the corpus (K-15), and the medium through which outcomes cross the line — the interchange vocabulary, not an internal one.

**Alternatives:** *Open Host Service* — correctly rejected (a token vocabulary is a language, not a service protocol; no context builds on a CBC-1 API). *Partnership* — correctly rejected (asymmetric: the supplier informs, the customer decides; no coordinated change). Both rejections sound.

**Scope note (not a defect):** Evans' Published Language typically implies publication for *wider* consumption; here the language serves one seam. The usage is defensible — the set is documented and closed — but it is a **narrow** Published Language, and calling it that should not imply general publication.

**Confidence: Medium-High** (shared-corpus/shared-lineage basis, per MCR-5). **Residual uncertainty:** U-1 (the downstream partner is the candidate seam) · the recorded weakest edge (Ruling/Candidate mode-2 affinity) sits on this relationship.

### R-3 — {CBC-1 · Seam · Core} → CBC-2 · classified **Conformist** · ✅ VALIDATED (strongest fit)

**Definition:** the downstream **adopts the upstream model wholesale**, forgoing translation, because it cannot influence upstream and translation is not worth its cost.

**Evidence cited:** *"recomputed from sources, never hand-edited to disagree"* · *"if a diagram and a frozen artifact ever disagree, the artifact wins and the diagram is corrected"* · **no independent semantic identity** (L4-8) · *"authority: derived"*.

**Verification:** every element of the definition is satisfied verbatim, and one is satisfied **more strongly than the pattern requires**: a typical Conformist keeps an internal model shaped by upstream, whereas CBC-2's members hold *no independent semantic identity at all*. Zero influence upstream is stated as rule ("the artifact wins"). **Consistent — the least interpretive classification in the model.**

**Observation, explicitly not a reopening:** the degree of conformance is extreme enough to raise the theoretical question whether a projection *context* conforms or merely *renders*. That question bears on CBC-2's nature as a bounded context, which is **disposed and frozen** — out of this review's scope, recorded here only so the reader knows it was considered and set aside, not missed.

**Alternatives:** *Open Host Service* correctly rejected (renderings serve humans; no context may consume them as authority). *Shared Kernel* correctly rejected implicitly (nothing co-owned; L4-8 forecloses it).

**Confidence: Medium-High** — the highest-justified classification here. **Residual uncertainty:** OQ-PKS-2 conditions CBC-2's interior semantics (U-4), which does not disturb the pattern.

### R-3b — the authority direction of R-3 · labeled **"Separate Ways in the authority direction"** · ⚠️ PATTERN-USAGE DEFECT (VF-2)

**Quoted:** *"the return path is constitutionally closed … 'nothing may cite a view as authority' … (= Separate Ways in the authority direction)."*

**Definition:** Separate Ways applies when two contexts have **no significant relationship at all** — no integration, because its cost exceeds its benefit. It is a statement about a *pair*, not about a direction within a relationship.

**Verification: the underlying fact is real and correctly evidenced; the pattern name is misapplied.** These contexts *are* related — R-3 classifies them as Conformist. A pair cannot be simultaneously Conformist and Separate Ways; the label describes a **prohibition on one direction of an existing relationship**, which is a *constraint*, not a pattern instance. The rule ("nothing may cite a view as authority") is arguably the most important relationship fact in the model — which is exactly why it deserves accurate naming rather than a borrowed one.

**Recommendation (advisory):** restate as **"R-3 constraint: the relationship is unidirectional by constitutional rule — no return path exists in the authority/evidence direction"**, and reserve *Separate Ways* for R-5, where it is correct. Evidence and significance unchanged; only the name changes.

**Confidence in the underlying fact: Medium-High (verbatim rule). Confidence in the pattern label: rejected.**

### R-4 — {CBC-1 · Seam} ↔ CBC-4 · classified **C/S across the domain edge** · ⚠️ REFINEMENT REQUIRED (VF-3)

**Evidence cited:** the 14-box DoD's five knowledge obligations as conditions of work-item completion (K-13) · asymmetry (no knowledge kind depends on a work item) · guarded homonyms "Approved"/"Verified" (L-5 typed as a work-item status vocabulary).

**Verification — two problems, one shared with VF-1 and one new:**
1. **The accommodation condition is again unevidenced.** Nothing records PKS knowledge kinds being shaped by work-management's needs, nor any negotiated interface. The dependency and its direction are well-evidenced; the *pattern* adds an unrecorded coordination claim.
2. **Pattern applicability across the disposed domain edge.** Evans' relationship patterns describe relations between bounded contexts within a modeling effort. CBC-4 is disposed **adjacent — outside PKS**, and M7 itself (correctly) declines to model its interior. Naming a coordination pattern that implies expectations of a context we hold no design authority over asserts more than the disposition supports.

**What survives fully:** the dependency, its direction, the DoD boxes as interchange terms, and **U-2** — the unassigned translation obligation for the guarded homonyms, which M7 correctly refused to place. This review notes that U-2 is itself the honest form of the finding: an unowned translation need across an edge is an **ACL-shaped gap**, and M7's refusal to assign it was right.

**Recommendation (advisory):** restate R-4 as **"cross-edge dependency: work items carry obligations toward knowledge; interchange terms = the DoD's five knowledge boxes; translation obligation unassigned (U-2); no coordination pattern asserted across the domain edge."** Retain direction, evidence, and grade.

**Confidence in the dependency: Medium (M5-Derived adjacency, as accepted). Confidence in the pattern name: Low.**

### R-5 — CBC-2 ↔ CBC-4 · classified **Separate Ways** · ✅ VALIDATED

**Definition:** no significant relationship; integration cost exceeds benefit.

**Verification:** consistent. Boards, epic files, and WBS rows are CBC-4's own carriers; no evidence routes them through CBC-2's projection discipline; the work-side derived-progress rule is not a CBC-2 membership fact. This is a **pair-level absence** — the situation the pattern is for. Graded Medium as an absence claim, which is the correct epistemic treatment.

**This validation also supplies the contrast that exposes VF-2:** R-5 is Separate Ways because *there is no relationship*; R-3b was labeled Separate Ways although *there is one*. Same name, incompatible situations.

**Residual uncertainty:** none beyond the general absence-claim caveat (an unobserved relationship is not a proven non-relationship).

---

## 4. Seam Observation Validation (M7 §7.3)

**Quoted:** *"the two seam-touching flows attach to different halves of the preserved Norm-Custody ∥ Authorization-Acts partition … the relationship structure reproduces the partition line."*

**Classification: architectural evidence supporting future re-assessment — CONFIRMED, not evidence supporting the seam.**

**Reasoning:** relationship analysis is a **new lens over the same corpus, applied by the same lineage**. Under the confirmed R-M6-6/B-1 property, agreement among lenses drawing on one corpus measures internal coherence, not independent confirmation — the program has already *measured* this, so treating §7.3 as corroboration would contradict its own recorded finding. Under MCR-5's adopted statement, the observation's independence basis is *shared-corpus/shared-lineage*.

**Verified as folded into M7 §7.3** with that classification and reasoning stated in place. The observation's routing is unchanged and correct: checkpoint/ARB input; any MCR-3 re-entry remains a separate Authority act; the seam's reopening trigger is **assessed against** this material, never discharged by it.

**One precision this review adds:** the alignment is *architecturally interesting* for a reason that survives the epistemic discount — it was produced by a lens that did not exist when the partition was preserved, so it is at minimum evidence that the partition line is **stable under a change of analytical perspective**. That is a weaker claim than confirmation and a stronger one than coincidence. Recorded as such.

---

## 5. Language Refinements — the two output classes, kept separate

**APPLIED (editorial/PA-directed, evidence-neutral — the "editorial fold" class established at CDR):**

| # | Change | Location |
|---|---|---|
| 1 | **Standing epistemic qualifier** added: every pattern assignment is a *recommended relationship classification*, Recommendation-class, never an established relationship | M7 header block |
| 2 | "Five strategic relationships are **modeled**" → "**are classified (recommended classifications, per the standing qualifier)**" | M7 §1 |
| 3 | "The seam is the **most-depended-upon element** in the model" → "**The current strategic model indicates** the seam is the most depended-upon **candidate** element" | M7 §7.1 |
| 4 | §7.3 reclassified as **architectural evidence supporting future re-assessment**, with the independent-lens ≠ independent-evidence reasoning and the R-M6-6/MCR-5 grounding stated in place | M7 §7.3 |
| 5 | Validation line: evidence a classification is *"consistent with (never 'proven by')"* | M7 §9 |
| 6 | Disposition History row added recording folds 1–5 | M7 header |

*Checked and found unnecessary:* M7 contained no "relationship established" or "evidence supports" phrasings to convert — the assertive-language risk was concentrated in items 2–3, which are now corrected.

**RECOMMENDED ONLY — not applied (substantive; disposition is the Authority's):** VF-1 (R-1 pattern name), VF-2 (R-3b Separate Ways misuse), VF-3 (R-4 pattern name). Each changes what a relationship *is called* and therefore what the model asserts; applying them here would make this review a decision-maker. They are recorded with their restatements ready to fold on disposition — the same two-class discipline the CDR adopted as MCR-4.

---

## 6. Confidence Summary

| Relationship | Direction/dependency | Pattern name | Independence basis (MCR-5) |
|---|---|---|---|
| R-1 | Medium-High | **Low** (VF-1) | shared-corpus / shared-lineage |
| R-2 | Medium-High | Medium-High | shared-corpus / shared-lineage |
| R-3 | Medium-High | Medium-High (strongest) | shared-corpus / shared-lineage |
| R-3b | Medium-High (the rule) | **rejected as a pattern** (VF-2) | shared-corpus / shared-lineage |
| R-4 | Medium | **Low** (VF-3) | shared-corpus / shared-lineage |
| R-5 | Medium (absence claim) | Medium | shared-corpus / shared-lineage |

**Pattern (Derived):** every classification's **direction and dependency** grade is at or above the grade of its **pattern name**. The evidence supports *that these elements depend on each other, how, and which way* considerably better than it supports *what the relationship should be called*. That asymmetry is the honest state of the relationship model, and no grade anywhere exceeds Medium-High (R-M6-6 ceiling holds).

### 6.1 Evidence matrix — structural evidence vs pattern evidence (PA-suggested; additive)

*Separates the three things a relationship claim asserts, so the asymmetry above is visible per row rather than only in aggregate. The **dependency** and **pattern** columns re-present §6's grades; the **ownership** column is assessed here from the ownership statements already recorded at M7 §8. No classification changes.*

| Relationship | Evidence for **dependency** (that it exists, and its direction) | Evidence for **ownership** (who owns what) | Evidence for the **pattern name** |
|---|---|---|---|
| **R-1** seam → CBC-1 | **Strong** — verbatim UL import; direction unambiguous | **Strong** — *"used here, owned elsewhere"* states ownership explicitly; violation semantics defined norm-side | **Moderate-to-Weak** — the C/S accommodation condition is unevidenced (VF-1); ACL-shaped alternative live |
| **R-2** CBC-1 → seam | **Strong** — P-8 + G-15, both verified verbatim | **Moderate** — a genuine subtlety: the Verdict is a **CBC-1 member** (M6 §8) while the *issuing act* belongs to a gate or review at the seam. Not a contradiction (concept vs act), but it is the model's recorded weakest edge and it lands exactly here | **Moderate-to-Strong** — the accommodation condition **is** satisfied (P-8); Published Language satisfied but narrow |
| **R-3** sources → CBC-2 | **Strong** — four corpus rules, each meaningless without the relationship | **Strong** — L4-8 is categorical: CBC-2 owns the rendering process, never the content | **Strong** — every element of the Conformist definition satisfied verbatim; the only classification in the model at this level |
| **R-3b** the authority direction | **Strong** — the prohibition is a verbatim standing rule | **Strong** — the prohibition is corpus-constitutional, owned by no single context | **Rejected** — Separate Ways presupposes no relationship (VF-2); the fact needs a constraint statement, not a pattern name |
| **R-4** PKS ↔ CBC-4 | **Moderate** — the DoD coupling is Measured, but the adjacency it crosses is M5-**Derived** | **Moderate** — obligations are PKS-side and work items CBC-4-side, but CBC-4's interior is deliberately unmodeled, so ownership beyond the edge is unstated (U-2) | **Weak** — unevidenced accommodation **plus** a pattern-applicability problem across a disposed domain edge (VF-3) |
| **R-5** CBC-2 ↔ CBC-4 | **Moderate** (absence claim — an unobserved relationship is not a proven non-relationship) | *n/a* — nothing is jointly owned; that is the finding | **Moderate** — correct pair-level use of Separate Ways |

**What the matrix makes visible (Derived):** the model's **structural** layer (dependency + ownership) is Strong or Moderate everywhere and Strong in the majority of cells; its **pattern** layer is Strong in exactly one row. **Recommendation for downstream use:** cite the structural layer freely; cite pattern names only where the pattern column reads Strong or Moderate-to-Strong (R-2, R-3) until VF-1/VF-2/VF-3 are disposed. This is the operational form of §8's first M8 condition.

## 7. Residual Uncertainties — status

| # | Uncertainty | Status after validation |
|---|---|---|
| **U-1** | R-1/R-2 patterns terminate on a candidate seam | **Unchanged and now doubly relevant:** VF-1 already weakens R-1's pattern name independently of U-1 |
| **U-2** | R-4 translation obligation unassigned | **Unchanged; validated as the correct treatment.** An ACL-shaped gap across an edge we hold no design authority over is properly left unassigned and routed |
| **U-3** | The unpartitioned core participates but holds no pattern | **Unchanged and correct** — a region cannot hold a relationship pattern; T-16 carried |
| **U-4** | OQ-PKS-7 / OQ-PKS-2 contingencies | **Unchanged, unresolved.** OQ-7 could relocate R-1's upstream end across a domain boundary — which would make VF-1's pattern question moot in a different way |

## 8. M8 Readiness

**READY — with the three refinement findings recorded and pending disposition.**

**Basis:** M8 consumes the relationship model's **directions, dependencies, ownership, and authority boundaries** — all validated at Medium/Medium-High — plus its uncertainty markers. The three findings attach to **pattern names**, not to any direction, dependency, or piece of evidence; no finding invalidates a dependency M8 would rely on. Readiness is therefore not blocked.

**Two conditions recommended for M8's commission:**
1. **M8 must cite directions and dependencies, not pattern names, wherever a finding is open** (R-1, R-3b, R-4) — so no downstream artifact inherits a name this review found unsupported.
2. **VF-1/VF-2/VF-3 disposition should precede any artifact that treats the pattern names as stable input** — M8 by reference does not require it; a later published context map would.

**Not readiness-relevant but recorded:** the validation itself is a same-lineage act (T-2 unbroken) — it contributes no structural-independence datum.

**Observed property, recorded for the M7/M8 checkpoint (PA wording; routed to the methodology track, not resolved here):**

> **Specialized downstream stages can detect defect classes that upstream stages are not designed to detect.**

**Instances (two, not generalized):** (i) MCA §3/§5 — execution's self-verification passed over the Phase-C→E member-set drift; the Critical Review, whose method is independent re-derivation, found it. (ii) This review — M7's self-verification (§9) passed its own pattern names; a validation whose method is definitional conformance found three defects in them. **What the property does *not* claim:** that later stages are generally better, more rigorous, or authoritative over earlier ones. The mechanism is narrower and more useful — each stage is *instrumented for its own question*, and a defect invisible to one instrument can be visible to another. **n=2; recorded as an observed property, not a law.**

---

*Traceability: executes the M7 Relationship Validation Review Commission (PA, 2026-07-28) · validates the five classifications of `PKS_Phase_II_M7_Strategic_Relationship_Model.md` §5 against Evans' Strategic Design pattern definitions including their necessary conditions · findings VF-1 (R-1 pattern name), VF-2 (R-3b Separate Ways usage), VF-3 (R-4 pattern name) — **recommended only, not applied** · six editorial/PA-directed refinements **applied** to M7 with a Disposition History row · seam-observation classification confirmed and folded (independent lens ≠ independent evidence; R-M6-6/B-1 + MCR-5 grounding) · no boundary rediscovered, no disposition reopened, no BC/seam reclassified, no governance act, no M8 · same-lineage act (T-2 declared). **STOP.***

> **M7 Relationship Validation Review complete. Every relationship classification has been validated against Strategic DDD definitions — three validated, two requiring refinement, and one pattern-usage defect found. Editorial refinements are applied; substantive reclassification findings are recorded for disposition. The relationship model is ready for M8 by reference, with directions and dependencies validated and open pattern names flagged. No further execution is authorized within this commission.**
