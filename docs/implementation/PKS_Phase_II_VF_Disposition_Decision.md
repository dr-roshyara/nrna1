# PKS Phase II — VF Disposition Decision Record (DAR-1)

| | |
|---|---|
| **Kind** | **Authority disposition — governance only.** Decides whether the M7 Validation Review's three recommendations enter the governed baseline. **It decides nothing about which Strategic DDD pattern is correct** — that question is architecture design and is outside this commission by construction. |
| **Authority** | Issued under the **VF Disposition Authority Review commission (DAR-1)** (PA, 2026-07-28), ARB acting in its Authority capacity. **This commission records the Authority's decisions; it is not itself the source of authority** — PA override supersedes forward. |
| **Status** | **DECIDED — VF-1 ACCEPT · VF-2 ACCEPT · VF-3 ACCEPT (on one of two grounds; the second expressly not adjudicated). Decisions effective on issuance. STOP.** No M8, no checkpoint re-assessment, no methodology change, no retrospective, no PMR assessment performed. |
| **Decision space (binding)** | SDM v1 (frozen) · EOP v1 (frozen) · Configuration Control · CDR Decision · adopted MCR-1..6 · M7 Strategic Relationship Model · M7 Relationship Validation Review. |
| **Placement** | `docs/implementation/`, beside the M7 record set. |
| **Disposition History** | 2026-07-28: issued (VF-1 ACCEPT · VF-2 ACCEPT · VF-3 ACCEPT on ground 1). · 2026-07-28: **PA refinement folded** — §4(a)'s decision rule restated in the narrower, better-evidenced form (*"when a claim is unsupported under the adopted evidentiary standard, withdrawing that unsupported claim does not require establishing an alternative claim"*), **explicitly scoped to this disposition and expressly not adopted as an SDM/EOP principle**; any elevation runs the PMR → MCA → CDR path. Dispositions unchanged. |

---

## 1. Executive Summary

**All three findings are ACCEPTED. The outcomes agree; the warrants do not — each was tested independently against the four decision criteria, and the alternatives were rejected with reasons rather than passed over.**

| Finding | Decision | Warrant class | Baseline impact |
|---|---|---|---|
| **VF-1** — R-1's Customer/Supplier name | **ACCEPT** | Absence of the pattern's necessary condition | Pattern name withdrawn; dependency, direction, ownership, evidence **unchanged** |
| **VF-2** — "Separate Ways in the authority direction" | **ACCEPT** | **Definitional contradiction** (strongest of the three) | Pattern label replaced by a constraint statement; the underlying rule **unchanged** |
| **VF-3** — R-4's cross-edge Customer/Supplier name | **ACCEPT on ground 1 only** | Absence of the pattern's necessary condition | Pattern name withdrawn; cross-edge dependency and U-2 **unchanged** |

**The single most important consequence for M8, stated plainly: no dependency, direction, ownership statement, or piece of evidence in the relationship model is altered by any of these decisions.** Only pattern *names* change. The structural layer the Validation Review graded Strong-or-Moderate throughout enters M8 exactly as recorded; the pattern layer enters it with three surviving named patterns (R-2, R-3, R-5) and two relationships carried as dependencies without a pattern name.

---

## 2. Commission

**Authorized:** exactly one of ACCEPT / REJECT / DEFER per finding, for VF-1, VF-2, VF-3.

**Not authorized, and not done:** naming which pattern is correct · proposing replacement classifications beyond the restatements the review already justified · redesigning the relationship model or rewriting M7 · introducing new evidence · modifying SDM, EOP, or the methodology · reopening M6, M7, or the Validation Review · assessing PMR-1/PMR-2.

**On the boundary that made this commission executable:** the Authority can accept a restatement without designing one only because the Validation Review §3 already carries each restatement *with its justification*. This record verified that precondition before deciding. Had any finding lacked a justified restatement, the correct disposition would have been DEFER — not the invention of one at this table.

## 3. Inputs

`PKS_Phase_II_M7_Relationship_Validation_Review.md` §§3, 5, 6, 6.1, 8 (the findings, their evidence, the two-class separation, the evidence matrix) · `PKS_Phase_II_M7_Strategic_Relationship_Model.md` §§5, 7, 8 (the classifications under review, read-only) · CDR Decision (fold-class discipline; configuration control) · the frozen M6 dispositions (checked for conflict only).

## 4. Decision Principles Applied

Each finding was tested against the four commissioned questions. Two principles governed how the answers were weighed, and both are recorded because they did real work below:

**(a) A decision rule scoped to this disposition (PA refinement — deliberately *not* stated as a general methodological principle):**

> **When a claim is unsupported under the adopted evidentiary standard, withdrawing that unsupported claim does not require establishing an alternative claim.**

Two findings rest on the *absence* of a pattern's necessary condition. An absence cannot establish what a relationship *is* — but it is sufficient to establish that a name asserting that condition is unsupported. The recommendations withdraw names; they assert no replacements. The warrant matches the act.

**Scope of this rule, stated explicitly:** it is applied here **within this disposition**, where the Validation Review had already demonstrated against the adopted evidentiary standard that the named patterns' necessary conditions were unevidenced. It is **not** adopted as an SDM/EOP principle, and this record does not elevate it to one — that would be a methodology change, admissible only via the PMR → MCA-class assessment → CDR-class decision path under configuration control. Any future generalization should start from the narrow formulation above rather than a broader one.

**(b) A definitional contradiction is not resolvable by more evidence.** Where a finding shows two mutually exclusive patterns applied to one pair, criterion 4 ("is more evidence required?") answers itself: no quantity of corpus evidence can make a pair simultaneously related and unrelated. DEFER is unavailable in principle for that class.

---

## 5. VF-1 Disposition — R-1's Customer/Supplier classification

**Recommendation (review §3):** restate R-1 as *"upstream/downstream dependency — criteria supply; pattern undetermined between Customer/Supplier and ACL-shaped consumption; the C/S negotiation condition is unevidenced."* Direction, evidence, and the U-1 marker retained unchanged.

**Evidence summarized:** Customer/Supplier's definition makes it load-bearing that the downstream's needs enter the upstream's planning (negotiated requirements, joint validation). The corpus records the dependency and its direction verbatim (*"criterion … used here, owned elsewhere"*; violation semantics defined norm-side) and records **nothing** about accommodation, negotiation, or joint validation of the interface. The upstream demonstrably succeeds without accommodating the downstream — the condition Customer/Supplier exists to *remedy*.

**Criterion 1 — reasoning internally consistent?** Yes. The argument is a single step: the pattern carries a necessary condition; the condition is unevidenced; therefore the name overclaims. No hidden premise.

**Criterion 2 — does the evidence support the recommendation?** Yes, under principle (a). The recommendation's content is a *withdrawal plus a recorded uncertainty*, and an unevidenced necessary condition is exactly the warrant a withdrawal needs. Note what the recommendation does **not** claim: it does not assert ACL, and it does not assert that accommodation is absent in fact — only that it is absent from the record.

**Criterion 3 — conflict with a frozen disposition?** None. CBC-1's and CBC-3's dispositions, memberships, grades, and triggers are untouched; the M6 record set is not edited; the relationship's direction, evidence citations, and U-1 marker persist.

**Criterion 4 — is more execution evidence required first?** No — for this act. Evidence would be required to *name* the pattern (C/S versus ACL-shaped), which the recommendation deliberately declines to do. Requiring evidence before withdrawing an unsupported name would leave a known overclaim standing in the governed model, which is the worse state.

**DECISION: ACCEPT.**

**Rationale.** The finding identifies a real gap between what the pattern name asserts and what the record contains, and the remedy is proportionate: it removes the unsupported assertion and preserves everything the evidence does support. **DEFER was considered and rejected** — deferral would preserve the overclaim while waiting for evidence that only bears on a question the recommendation does not attempt to answer. **REJECT was considered and rejected** — it would require finding the accommodation condition evidenced somewhere in the record, and the Authority introduces no new evidence (nor did any exist to introduce).

**Baseline impact:** **Baseline updated by adopting the review recommendation.** Scope of the update: R-1's *pattern name only*. Unchanged: the dependency, its direction, the ownership statement (*"owned elsewhere"* — graded Strong in the evidence matrix), every evidence citation, the confidence in the dependency (Medium-High), and U-1.

**Follow-up:** one **authorized editorial application** — bringing M7 §5's R-1 entry and §1's summary line into conformance with this decision. That is an administrative act under the CDR's editorial fold class, not a new decision; this commission's STOP forbids reopening M7, so it is not performed here. **Until applied, this record governs on conflict** (documents record governance; decisions create it).

---

## 6. VF-2 Disposition — "Separate Ways in the authority direction"

**Recommendation (review §3):** restate as *"R-3 constraint: the relationship is unidirectional by constitutional rule — no return path exists in the authority/evidence direction."* Reserve *Separate Ways* for R-5. Evidence and significance unchanged; only the name changes.

**Evidence summarized:** Separate Ways applies to a pair with **no significant relationship**, integration being uneconomic. M7 classifies this same pair as **Conformist** (R-3) on four verbatim corpus rules. The prohibition itself (*"nothing may cite a view as authority"*) is verbatim and verified. A pair cannot be simultaneously Conformist and Separate Ways.

**Criterion 1 — consistent?** Yes, and this is the only finding whose consistency is *formal*: the two labels are mutually exclusive by definition, and M7 applies both to one pair.

**Criterion 2 — evidence supports?** Yes, and more strongly than the other two findings. VF-1 and VF-3 rest on the absence of a condition; VF-2 rests on a **present contradiction** between two definitions and one verbatim rule. The recommendation also preserves the finding's substance intact: the prohibition remains the most consequential relationship fact in the model — it is renamed, not diminished.

**Criterion 3 — conflict with a frozen disposition?** None. CBC-2's disposition, membership, and grade are untouched; R-3's Conformist classification (validated) is untouched; the constitutional rule is untouched.

**Criterion 4 — more evidence required?** **No — and unavailable in principle**, per principle (b). No additional corpus evidence can reconcile "related" with "unrelated" for one pair. DEFER would be a category error here, not a cautious choice.

**DECISION: ACCEPT.**

**Rationale.** This is a pattern-usage defect rather than a judgment call, the corrected statement is fully justified in the review, and the correction *strengthens* the record: a constitutional prohibition acquires an accurate name in place of a borrowed one. **DEFER rejected** as unavailable in principle. **REJECT rejected** — sustaining the label would require holding that one pair is both Conformist and Separate Ways.

**Baseline impact:** **Baseline updated by adopting the review recommendation.** Scope: the *label* for the authority-direction fact. Unchanged: the verbatim rule, its evidence, its significance, R-3's Conformist classification, and *Separate Ways* as R-5's correct classification.

**Follow-up:** one **authorized editorial application** (M7 §5's R-3 entry and §1's summary line, plus the context-map annotation), same class and same deferral as §5. No further commission required.

---

## 7. VF-3 Disposition — R-4's cross-edge Customer/Supplier classification

**Recommendation (review §3):** restate R-4 as *"cross-edge dependency: work items carry obligations toward knowledge; interchange terms = the DoD's five knowledge boxes; translation obligation unassigned (U-2); no coordination pattern asserted across the domain edge."* Direction, evidence, and grade retained.

**Evidence summarized — the review advances two grounds, and the Authority distinguishes them:**
- **Ground 1 (corpus-evidential):** the same unevidenced accommodation condition as VF-1 — nothing records PKS knowledge kinds being shaped by work-management's needs, nor any negotiated interface.
- **Ground 2 (definitional-interpretive):** that Evans' relationship patterns should not assert coordination across a *disposed domain edge* whose interior the program deliberately does not model.

**Criterion 1 — consistent?** Yes, on both grounds independently.

**Criterion 2 — evidence supports?** **Yes, on ground 1** — identical in form to VF-1, and accepted on the same warrant (principle (a)). **Ground 2 is expressly not adjudicated:** it is an argument about the *scope of applicability of a Strategic DDD pattern*, which is a methodology/interpretation question, not a claim about this corpus. Ruling on it would exceed this commission (the Authority may not modify or interpret the methodology here). **The decision does not depend on it:** ground 1 alone fully warrants the recommendation, and ground 1 is being accepted for VF-1 on identical reasoning.

**Criterion 3 — conflict with a frozen disposition?** None — and the recommendation in fact *increases* conformance with a frozen disposition: CBC-4 was disposed **adjacent, outside PKS**, and withdrawing an asserted coordination pattern across that edge asserts less about a domain the program holds no design authority over. The DoD relationship M7 was obligated to model (Disposition §2.4 condition 2) remains modeled, as a dependency with named interchange terms.

**Criterion 4 — more evidence required?** No, for the withdrawal (same as VF-1). Evidence would be required to *assign* U-2's translation obligation — which the recommendation does not do, and which the review validated as correctly left unassigned.

**DECISION: ACCEPT — on ground 1. Ground 2 is not adjudicated by this record.**

**Rationale.** The corpus-evidential ground is sufficient and is already being accepted in identical form at VF-1; consistency of treatment requires accepting it here. Declining to rule on ground 2 keeps this disposition inside its authorized boundary while leaving the substantive question available to the body that owns it. **DEFER rejected** — the withdrawal needs no further evidence, and deferring would leave both an overclaim and an assertion across a boundary the program disposed as outside its authority. **REJECT rejected** — no evidence of the accommodation condition exists.

**Baseline impact:** **Baseline updated by adopting the review recommendation.** Scope: R-4's *pattern name only*. Unchanged: the cross-edge dependency, its direction, the DoD's five knowledge boxes as interchange terms, the Medium grade, and **U-2** (the translation obligation remains recorded and unassigned).

**Follow-up (two, both recorded rather than performed):**
1. One **authorized editorial application** (M7 §5's R-4 entry, §1's summary line, and the context-map annotation) — same class and deferral as §5.
2. **Recommended routing, not a decision:** ground 2 — *whether Strategic DDD relationship patterns may be asserted across a disposed domain edge whose interior is unmodeled* — is a methodology question with an execution-evidence trace. If the program wants it settled, its governed home is the `PMR-n` candidate register → next MCA-class assessment. **This record does not add it**; adding a candidate is beyond a disposition commission's authority.

---

## 8. Decision Summary

| Finding | Criterion 1 (consistent) | Criterion 2 (evidence supports) | Criterion 3 (no frozen conflict) | Criterion 4 (more evidence needed) | **Decision** |
|---|---|---|---|---|---|
| VF-1 | ✅ | ✅ (absence warrant) | ✅ | ❌ not for withdrawal | **ACCEPT** |
| VF-2 | ✅ (formal) | ✅ (definitional contradiction) | ✅ | ❌ unavailable in principle | **ACCEPT** |
| VF-3 | ✅ (both grounds) | ✅ ground 1; ground 2 **not adjudicated** | ✅ (increases conformance) | ❌ not for withdrawal | **ACCEPT (ground 1)** |

**Every disposition traces to recorded evidence in the Validation Review; no new evidence was introduced; no pattern was designed, named, or redesigned at this table.**

## 9. Baseline Impact

**What changed (pattern layer only):** R-1 and R-4 carry **no pattern name** — each is a recorded dependency with direction, ownership, evidence, and grade intact. The authority-direction fact of R-3 is a **constraint statement**, not a pattern instance. **Surviving named patterns: three** — R-2 (Customer/Supplier over a narrow Published Language), R-3 (Conformist), R-5 (Separate Ways).

**What did not change — everything else:** every dependency and its direction · every ownership statement · every evidence citation · every confidence grade on the structural layer · U-1, U-2, U-3, U-4 · the evidence matrix (§6.1) and its downstream citation rule · all four frozen M6 dispositions (CBC-1, CBC-2, CBC-4-as-outer-edge, CBC-3-as-candidate-seam) with their triggers · SDM v1, EOP v1, configuration control, MCR-1..6 · the Validation Review itself, which stands as issued.

**Effectivity:** decisions are effective on issuance of this record. Three authorized editorial applications to M7's text are pending as administrative acts; **until they are applied, this record governs on conflict.**

## 10. Inputs to M8

1. **The relationship model as disposed:** the structural layer (dependency · direction · ownership · authority boundaries) unchanged and validated at Medium/Medium-High — **M8 may cite it freely.**
2. **Three named patterns available for citation** (R-2, R-3, R-5) and **two dependencies to be cited without a pattern name** (R-1, R-4), plus **R-3's unidirectionality as a constraint**. This discharges the Validation Review's first M8 condition: no downstream artifact can now inherit a name the review found unsupported, because those names no longer exist in the baseline.
3. **The Validation Review's second M8 condition is discharged:** VF disposition is complete, so pattern names in the baseline are stable input.
4. **Carried uncertainties, unchanged:** U-1 (R-1/R-2 terminate on a candidate seam) · U-2 (translation obligation unassigned) · U-3 (the core is a region) · U-4 (OQ-PKS-2/OQ-PKS-7 contingencies).
5. **Not M8's business, recorded so it is not mistaken for M8 input:** the pending editorial applications · the ground-2 routing recommendation · PMR-1/PMR-2 · the M7/M8 checkpoint's certification questions.

---

*Traceability: executes the VF Disposition Authority Review commission DAR-1 (PA, 2026-07-28) at the narrowness the Authority prescribed (ACCEPT / REJECT / DEFER only) · disposes VF-1, VF-2, VF-3 from `PKS_Phase_II_M7_Relationship_Validation_Review.md` §3 against the four commissioned criteria · all three ACCEPTED, VF-3 on ground 1 with ground 2 expressly not adjudicated and routed as a recommendation · no pattern designed or named · no new evidence introduced · no methodology, SDM, EOP, M6, M7, or Validation Review modification · three editorial applications authorized, none performed (STOP honored) · PA override supersedes forward.*

> **VF Disposition Authority Review complete. VF-1 ACCEPT · VF-2 ACCEPT · VF-3 ACCEPT (ground 1). The relationship model's structural layer is unchanged; two pattern names are withdrawn and one pattern label is replaced by a constraint statement. The baseline is unambiguous for M8. The next act is M8 by reference. No further execution is authorized within this commission.**

**STOP.**
