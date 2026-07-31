# PKS Phase II — Retrospective (RET-1)

| | |
|---|---|
| **Kind** | **Retrospective** — meta-level learning. Evaluates *what the program learned about itself*, never *whether the architecture is correct*. Converts execution history into reusable organizational knowledge, held distinct from the frozen SDM/EOP baseline until a future MCA/CDR cycle evaluates it. |
| **Authority** | Generated — never authoritative without human review. **This retrospective changes nothing.** It records, classifies, and recommends; it adopts, decides, and admits nothing. |
| **Status at execution (retained as history — TRUE WHEN WRITTEN, superseded by promotion 2026-07-30)** | **EXECUTED. STOP.** No artifact promotion, no PMR assessment, no MCA, no CDR, no governance change, no implementation planning performed. | *(Renamed per the extension of change-control item M7R-1 to this artifact: the row is renamed, not rewritten — the governing Status is the promotion row. Any "STOP" below was an instruction to the executing commission, not to a reader.)*
| **Commission** | Phase II Retrospective Commission RET-1 (PA, 2026-07-28) — Parts A–G; epistemic class on every conclusion. |
| **Closed and not reopened** | M6 · M7 · DAR-1 · M8 · the checkpoint · SDM · EOP · PMR admission · Surfacing Register items · the strategic model. |
| **Placement** | `docs/implementation/`, closing the Phase II record set. |
| **Status** | **🏁 PROMOTED (Authority act, 2026-07-30).** **Promoting a retrospective does NOT promote its patterns: P-1..P-11 remain CANDIDATES, not rules** — §9's header governs, and pattern promotion requires an MCA-class assessment then a CDR-class decision, neither of which has occurred. **No PMR admitted; §11's inputs remain ungraded facts with relative strength left to the MCA; P-11's prescriptive form remains a Recommendation.** **Now governing — no longer freely editable.** |
| **Disposition History** | **2026-07-30: change-control item M7R-1 extended to this artifact** — the execution-era `Status` row renamed to *"Status at execution (retained as history…)"*, removing the duplicate-key ambiguity created by the promotion act. **Content-neutral: renamed, not rewritten; nothing else touched, nothing re-verified.** · **2026-07-30: PROMOTED by Authority act (record: `PKS_Phase_II_Execution_Record.md`).** · **2026-07-30: RET-1a/1b/2/3/4 disposed ACCEPT (Knowledge Contract Review, retrospective emphasis) and remedies applied.** **RET-1a (empirical over-generalization → ADD SCOPE):** P-2, P-4, P-9 and §12's claim scoped to their evidence in the P-10 form. **RET-1b (normative over-generalization → CHANGE CLASS, since scoping a prescription does not repair it):** P-11 restated as its observation, and the two control-adequacy maxims (§1, §7) reclassified from *Derived* to **Recommendation**, routing to MCA→CDR. **RET-2:** §11 item 1's candidate ranking removed; the method recommendation and every per-pattern *n* retained as facts. **RET-3:** narrowed on verification — the support exists (§1's defect-yield metric, §5.3, §5.4), so only the *Observed* label was wrong; relabelled *Derived*. **RET-4:** four prose superlatives removed; **§4.4's and §5.2's commission-mandated rankings deliberately RETAINED.** No observation, classification, routing, or model element was changed. · **2026-07-30 (same day, second pass): the review-derived governance REASONING was stripped from this document's body and left only in this row and in the review record**, on the Authority's caution that *"the refinements are products of the review of RET-1, not of Phase II execution — they belong to the governance framework, not to the retrospective's historical record."* **The corrections stand; their justifications moved.** ***A retrospective preserves what the program learned; it must not accumulate what its reviewers learned.*** |

**Epistemic classes used on every conclusion below** (commission-mandated; no conclusion moves class without stated justification): **Observed** — demonstrated directly by execution · **Derived** — logical synthesis across governed artifacts · **Recommendation** — future consideration only · **Out of Scope** — intentionally not addressed.

**Layering discipline maintained throughout** (evidence → interpretation → recommendation → governance → baseline → assembly → certification): this document sits at the *interpretation and recommendation* layers. It does not touch governance, baseline, or certification.

---

## 1. Executive Summary

**Phase II demonstrated that a strategic modelling program can be governed like an engineering system — and what it learned is where its own failures live.** *(RET-4: superlative removed.)*

**Derived** *(RET-4: "central" removed — the retrospective does not rank its own findings)*: across four separate defect events, **not one defect occurred in the corpus-evidence layer.** Every defect was in an *interpretive* layer — a recording error over correct data (F-M6CR-1), two pattern names asserting unevidenced conditions (VF-1, VF-3), a pattern label contradicting its own definition (VF-2), and a self-verification instrument that tested scope but not definitional conformance (§4.4 of the checkpoint). The observed rules quoted from the corpus never failed. **What failed, repeatedly, was the layer where the program named things.**

**Observed (the second-order finding):** every one of those defects was caught by a *later, differently-instrumented stage* — and none by the stage that produced it. Self-verification by the executing stage is **0-for-2** on defects that later stages found.

**Observed:** the four highest-value acts by defect yield (Critical Review, Evidence Restatement, Validation Review, DAR-1) each *reduced* what the model asserted. **Derived** *(per RET-3)*: each left it more trustworthy, and confidence rose fastest when claims were **withdrawn** — supported by the defect-yield measure above, §5.3's per-mechanism yields, and §5.4.

**Observed:** configuration control was tested three times by things that wanted to become methodology — PMR-1, PMR-2, and the checkpoint's §4.4 observation — and held all three times, including against suggestions from the Authority itself. **Recommendation** *(per RET-1b)*: **that resistance to principal-originated change be assessed as a candidate criterion for control adequacy.**

**Observed:** the two-dimensional certification model earned its adoption within one cycle, twice: at the CDR (a refinement's adoption moved a design-side condition while leaving the evidence dimension untouched) and at the checkpoint (five further acts moved volume while leaving independence at zero).

**Observed, and unflattering:** two of the six refinements adopted at the CDR (MCR-3, MCR-6) have never been exercised, and the one that gates certification (MCR-1) has never been tested in its own defect domain. **Derived:** the program adopted six refinements on one cycle's evidence, and one cycle later a third of them remain unexercised — a calibration datum about adoption appetite, recorded without a recommendation attached.

---

## 2. Commission

**Authorized:** evaluate process performance · evaluate knowledge-engineering behavior · evaluate Strategic DDD's contribution · evaluate governance behavior · classify accumulated methodology observations · identify durable patterns demonstrated by execution · state remaining boundaries · prepare promotion inputs.

**Not authorized, and not done:** no architectural conclusion introduced · no governance decision made · no methodology change · no PMR admitted or assessed · no Surfacing Register item resolved · no model redesigned · nothing reopened.

## 3. Scope

**Evaluated:** the thirteen governance acts of Phase II (M0–M5 baseline work · M6 discovery · Critical Review · Authority Disposition · Evidence Restatement · CBC-3 Re-Disposition · Consolidation · MCA · CDR · M7 · Validation Review · DAR-1 · M8 · Checkpoint) as *process*, together with their recorded observations.

**Explicitly out of scope (Out of Scope, stated so their absence is not read as oversight):** whether the bounded contexts are correct · whether CBC-3 should be a context · whether the relationship classifications are right · whether any Surfacing Register question has an answer · whether SDM/EOP should change.

---

## 4. Process Assessment (Part A)

### 4.1 Which stages worked as intended

**Observed — all thirteen executed and produced their commissioned deliverable, and no stage collapsed into another.** Each artifact carries an explicit "what this did NOT do" section, and every one held on inspection. The differentiating detail:

| Stage | Worked as intended? | What execution showed |
|---|---|---|
| Discovery (M6) | ✅ | Discriminated: a candidate demoted by its own probe, a tidy fourth context refused, two framework-aligned partitions falsified, no High grade issued |
| Critical Review | ✅ **high value** | Found the major defect execution missed; independent re-derivation of citations succeeded |
| Authority Disposition | ✅ | Exercised genuine discrimination — three accepts of different character plus one **Return** |
| **Evidence Restatement** | ✅ **not designed — emerged** | The designed sequence had no evidence-repair stage; one was required and was absorbed without redesign |
| CBC-3 Re-Disposition | ✅ *(procedurally necessary, informationally thin)* | Confirmed the conclusion on the corrected record. Produced little new information — but without it CBC-3's status would have stood Authority-unfixed |
| Consolidation | ✅ | Stabilization-only held; evidence untouched; froze exactly what existed including the gaps |
| MCA | ✅ | Empirical framing held; eight execution-revealed facts; no speculative refinement |
| CDR | ✅ | Differentiated adoption (one refinement adopted only in part); declared the control it then had to live under |
| M7 | ⚠️ **produced defects its own check passed** | Model sound at the structural layer; three pattern names unsupported and not self-detected |
| **Validation Review** | ✅ **highest defect yield per unit of effort** | A deliberately small commission found three defects a full modelling act had missed |
| DAR-1 | ✅ | Narrow scope held; no design performed at a disposition table |
| M8 | ✅ | Assembly discipline held; the `Assembly:` marker made the constraint operational |
| Checkpoint | ✅ | Produced a valuable **negative** result and refused to let volume imply progress |

### 4.2 Unexpected strengths

**Observed:** three capabilities appeared that the design did not anticipate.

1. **Stage elasticity.** The governance wrapper absorbed an entirely new stage (Evidence Restatement) mid-cycle, at full rigor, without redesigning anything upstream — and the act of absorbing it produced a rule ("consolidation never modifies evidence") that then became structural.
2. **Small commissions with high yield.** The Validation Review was scoped as "a much smaller one, not another Critical Review" and returned three accepted findings. **Derived:** defect yield tracked *instrumentation specificity*, not commission size.
3. **The candidate register as a pressure valve.** Opening a `PMR` register let three methodology ideas be recorded with full evidence chains *without* any of them touching the baseline. **Derived:** the alternative to informal adoption is not refusal — it is a governed holding place.

### 4.3 Which stages prevented errors

**Observed, each with the specific error it prevented:**

| Mechanism | Error prevented |
|---|---|
| Critical Review | A demotion decided by a probe whose record did not support itself would have entered the baseline as settled structure |
| The disposition's **Return** option | CBC-3 would have been either wrongly fixed by authority act or wrongly rejected; Return was the only option matching the evidence state |
| Consolidation's stabilization-only rule | Evidence repair was about to be absorbed into consolidation, where it would have been invisible |
| Validation Review | Three unsupported pattern names would have entered M8 and been inherited downstream as stable architecture |
| DAR-1's narrowness | The Authority would have become an architecture designer, choosing replacement patterns at a governance table |
| The PMR register | Three methodology changes would have been adopted informally, one cycle after configuration control was declared |
| MCR-5's independence statement | Grades would have been readable as independently confirmed |

### 4.4 Which stages generated the highest architectural confidence

**Derived — and the answer inverts the intuitive one: the stages that *removed* claims.** The Critical Review, Evidence Restatement, Validation Review, and DAR-1 collectively withdrew one probe verdict, one confidence grade, two pattern names, and one pattern label. After each withdrawal the model asserted *less* and was *more* trustworthy. The stages that added content (M6, M7) generated the model; the stages that subtracted from it generated confidence in it.

**Observed counter-case, recorded for balance:** subtraction was not automatically valuable. The CBC-3 re-disposition subtracted nothing and added little, yet was procedurally necessary. Value tracked *what the stage was instrumented to detect*, not the direction of its effect on claim volume.

---

## 5. Knowledge Engineering Assessment (Part B)

### 5.1 How knowledge evolved

**Observed:** a strict consume-frozen-inputs chain — M0 glossary → M1 register → M2 canon → M3 kind decision → M4 identity and lifecycle → M5 classification → M6 boundaries → M7 relationships → M8 assembly. Each stage treated its predecessor as immutable premise; every amendment anywhere was additive with originals preserved. **Observed:** across thirteen acts, **no upstream artifact was ever edited to accommodate a downstream finding** — corrections were always appended as amendments, and the record of what was originally claimed survives everywhere.

### 5.2 Which evidence classes proved most valuable

**Observed, in measured order of productivity:**

1. **Negative-space Observed evidence — the most productive instrument in the entire program.** Rules that would be meaningless if a boundary did not exist ("nothing may cite a view as authority" · "recomputed from sources, never hand-edited to disagree" · "criterion — used here, owned elsewhere" · "lifecycle ≠ progress applies to these only") discovered CBC-2, anchored CBC-1, and later *stated* four of the five relationships almost verbatim. **Derived:** the program's best evidence was always a rule that presupposed the structure being sought.
2. **Measured structural data (M4's identity modes).** Did cohesion work at M6, was independently verified by the Critical Review, and did decisive work again at the Evidence Restatement — the only evidence class that was load-bearing in three separate acts.
3. **Pre-recorded corpus collisions.** OQ-PKS-11 ("Qualification", recorded before this discovery existed) landed exactly on a discovered boundary — corroboration the program did not manufacture.
4. **Derived steps — consistently the weakest link.** CBC-4's adjacency is M5-Derived and graded Medium accordingly; the pattern names were interpretive and produced all three VF defects; M3's load-bearing step is Derived and carries an active reversal condition. **Derived:** every place the program had to infer rather than quote became a place it later had to weaken, qualify, or withdraw.

### 5.3 Which review mechanisms produced measurable improvements

**Observed:**

| Mechanism | Yield |
|---|---|
| **Independent re-derivation of citations** | Found the K-15/G-15 mis-attribution and, via recount, the §8 tally error |
| **Definitional conformance checking** | Found VF-1, VF-2, VF-3 — three of the program's five substantive defects |
| **Claim decomposition** (the evidence matrix) | Exposed an over-claim present in a delivered artifact: three relationships whose pattern names graded Low/Weak had been presented at Medium/Medium-High |
| **Pre-declared pass/fail conditions** | Made a FAIL honorable and auditable; and made a *mis-enumerated* FAIL detectable |
| **Self-verification checklists by the executing stage** | **0 for 2.** M6 §12 passed the member-set drift; M7 §9 passed three unsupported pattern names |

**Derived:** the productive mechanisms share one property — **they apply an instrument the producing stage did not use.** Re-derivation checks the source rather than the citation; definitional conformance checks the definition rather than the plausibility; decomposition checks the parts rather than the whole. Self-verification, by construction, re-applies the producing stage's own instrument, and caught nothing either time.

### 5.4 Did confidence follow evidence?

**Observed — yes at the artifact level, and no at the claim level until corrected.**

At the *boundary* level confidence tracked evidence rigorously: no High grade was ever issued, the R-M6-6 ceiling was applied in-run and stated as an execution finding, and grades moved when evidence moved (CBC-3's re-derivation to Low-Medium).

At the *relationship* level it did not: M7 §5 carried one grade per relationship, and that grade tracked the **best-supported** component, so weakly-named relationships were presented at their dependency strength. This was invisible until the evidence matrix decomposed the claims, and it is now PMR-2's evidence. **Derived:** the confidence rubric was disciplined about *how strong* a grade could be and silent about *what the grade was a grade of.*

---

## 6. Strategic DDD Assessment (Part C)

### 6.1 How Strategic DDD contributed

**Derived:** Strategic DDD supplied the **questions and the falsification instruments**; the corpus supplied the **answers**. The removal test, linguistic probe, and cohesion probe are Evans-descended discovery instruments, and all three discriminated. But no boundary was drawn from a pattern — each was read out of stated rules, and the two partitions the program's own framework suggested (F6 responsibility classes, F1 significance grades) were tested and **falsified**.

### 6.2 Which Evans concepts proved operationally useful

**Observed:**
- **Bounded Context** — the organizing concept throughout; self-evidencing from the corpus.
- **Ubiquitous Language** — performed without needing governance support at any point *(RET-4: "the strongest performer" replaced with the observation that supports it)*. A UL collision recorded *before* the discovery (OQ-PKS-11) sat exactly on a discovered boundary; the observed term beat the elegant term every time it was tested ("Term" over "Definition"; "register" disambiguated in use rather than canonicalized); context-local senses were assignable without breaking a terminology freeze.
- **Conformist** — validated cleanly and to an unusual degree: the downstream holds *no independent semantic identity at all*, exceeding what the pattern requires.
- **Separate Ways** — validated where it belongs (a pair-level absence).

### 6.3 Which concepts required governance support

**Observed: the *relationship* patterns needed governance support that the *boundary* patterns did not.** *(RET-4: "the sharpest Strategic DDD finding of the phase" removed.)*

- **Customer/Supplier** failed twice on the same necessary condition (the downstream's needs entering upstream planning). **Derived:** the pattern's defining condition is stronger than common practice assumes, and applying it by resemblance to its one-line summary is a reproducible error — it happened twice in one artifact.
- **Separate Ways** was misapplied as a *directional* label on an existing relationship, contradicting its own definition.
- **Anti-Corruption Layer** hovered over two relationships as the closest live alternative and could be assigned to neither, because no translation locus exists strategically.
- **Published Language** applied, but narrowly — one seam rather than general publication.

Resolving these took a definitional validation *plus* an Authority disposition. Bounded Context and UL needed neither. **Derived:** boundary patterns were evidenced by the corpus; relationship patterns had to be adjudicated against definitions.

### 6.4 Which concepts remained intentionally unresolved

**Observed — five, each with recorded triggers:** the interior of the expressed-knowledge core (unpartitioned by choice) · the candidate seam's status as a context (preserved, not promoted) · two relationships with no pattern name · three contested memberships · the translation obligation at the domain edge (U-2).

**Derived: completion is not closure.** Phase II produced a *complete governed artifact* containing five deliberate openings. The model is finished; the domain is not.

---

## 7. Governance Assessment (Part D)

**Configuration Control — Observed: tested three times, held three times.** PMR-1, PMR-2, and the checkpoint's §4.4 observation each had an evidence chain and a plausible case for immediate adoption; none touched the baseline. **Derived:** two of those three arrived as suggestions *from the Authority itself*. **Recommendation** *(per RET-1b)*: that *resistance to principal-originated change* be assessed as a control-adequacy criterion.

**Authority disposition — Observed:** the four-option space did real work. **Return** was the only disposition matching CBC-3's evidence state, and it existed to be used. **Observed:** the record's wording matured mid-phase (from "the Authority decides" to "this commission records the Authority's disposition") — a correction that cost nothing and closed a real inconsistency between a document and its own header.

**Review/decision separation — Observed: held at all thirteen acts**, and became *operational* rather than aspirational through the **two-output-class discipline**: editorial refinements applied by the executing commission, substantive changes recommended only. That discipline was reused four times after its first appearance (Validation Review, DAR-1, the checkpoint's routing, the PMR register's admission rule).

**Structural evidence vs interpretation — Observed:** the separation did not exist at the start of M7 and existed by its end. Introduced as an evidence matrix, it immediately exposed an over-claim in a delivered artifact and produced a downstream citation rule that DAR-1 then made structural by withdrawing the unsupported names.

**Two-dimensional certification — Observed: proved itself twice within one cycle.** At the CDR, MCR-1's adoption discharged a design-side condition while leaving the evidence dimension untouched. At the checkpoint, five further acts increased volume while independence stayed at zero. **Derived:** a single composite grade would have moved on both occasions for the wrong reason.

**One governance weakness, Observed:** adoption outpaced exercise. Six refinements were adopted on one cycle's evidence; one cycle later MCR-3 and MCR-6 have never been used and MCR-1 has never been tested in its defect domain. Recorded as a calibration datum. **Out of Scope:** whether the CDR should have adopted fewer.

---

## 8. Methodology Observations — classified (Part E)

**No observation is admitted, resolved, or adopted here.** Classification only.

| # | Observation | Class | Basis |
|---|---|---|---|
| **B-1** | Lens convergence is correlated agreement; no independence instrument exists | **Strengthened** | MCR-5 adopted the *statement* but the instrument still does not exist; M7's relationship lens produced one more same-corpus agreement (§7.3), correctly discounted |
| **B-2** | Phase-A ordering collision (two "first acts") | **Unchanged** | No new instance arose |
| **B-3** | The unnamed band between not-falsified and not-recommendable | **Confirmed and strengthened** | MCR-2 named it; the formal state then carried CBC-3 through re-disposition and M8 without improvised vocabulary |
| **B-4** | No re-entry route for Phase-D-born partitions | **Strengthened** | MCR-3 defined the route; the preserved partition was then cited three times and remains unreachable — the gap's consequence is more visible, not less |
| **B-5** | Pre-identified surfacing triggers discriminate | **Unchanged** | No new prediction was made or tested |
| **B-6** | The Boundary Stability Test is a genuine discriminator | **Strengthened** | Decisive again at the Evidence Restatement: the item-1 removal failure is what moved CBC-3's confidence to Low-Medium |
| **B-7** | Session-log convention vs record-closure seal | **Strengthened** | Recurred repeatedly within one working day, handled by precedent each time — MCR-6's rule remains untested |
| **B-8** | Three-orthogonal-tests discipline is load-bearing | **Unchanged** | Still carried (T-17); no new instance |
| **B-9** | F6 not boundary-preserving; F1 boundary-orthogonal | **Unchanged** | No new test; the calibration stands |
| **B-10** | No member-set fixation rule between naming and probing | **Confirmed, addressed, untested** | MCR-1 adopted; no probing act has occurred under it (checkpoint §4.3) |
| **SI-1** | The frozen baseline observably constrained decisions | **Strengthened** | Four constrained decisions at M7, plus DAR-1's refusal to design and the checkpoint's refusal to upgrade |
| **SI-2** | MCR-1 exercised only by analogy | **Confirmed as the gating fact** | Became the checkpoint's controlling finding |
| **SI-3** | MCR-5's cost ≈ zero | **Strengthened** | Applied again in the Validation Review, DAR-1, and M8 without friction |
| **SI-4** | By-reference consumption works | **Strengthened** | Four further acts consumed the frozen record with zero reopening requests |
| **SI-5** | The independence datum remains unclaimed and precisely defined | **Strengthened** | The checkpoint additionally *closed* the assumption that M7-by-reference would supply it |
| **New (checkpoint §4.4)** | SDM self-verification does not test definitional conformance | **New — unclassified pending MCA** | Recommended for PMR admission; not admitted |
| **Property** | Specialized downstream stages detect defect classes upstream stages are not designed to detect | **Unchanged at n=2** | Two distinct events, three viewings; still not generalized |

---

## 9. Durable Patterns (Part F) — demonstrated by execution only

**Observed patterns, each with its demonstration. None is proposed as a rule; all are candidates for future promotion.**

| # | Pattern | Demonstrated by |
|---|---|---|
| **P-1** | **Conclusion ≠ argument.** A conclusion can survive the repair of its own deciding argument, and testing them independently is possible | The CBC-3 chain: the cohesion probe flipped FAIL → PASS on restatement while the demotion held on structural grounds |
| **P-2** | **Interpretive layers failed; evidence layers did not** *(scoped: in Phase II, across five substantive defects, one lineage)*. Defects clustered where the program named things, not where it quoted them | All five substantive defects were interpretive; zero were in quoted corpus evidence |
| **P-3** | **Stage compensation.** Specialized downstream stages detect defect classes upstream stages are not instrumented for | Critical Review → member-set drift; Validation Review → pattern names (n=2) |
| **P-4** | **Self-verification did not catch its producer's defect class** *(scoped: n=2)*. A stage re-applying its own instrument did not catch what that instrument does not test | M6 §12 and M7 §9, both passed defects later stages found (0-for-2) |
| **P-5** | **Two-class folds.** Separating evidence-neutral edits (apply now) from assertion-changing edits (recommend, dispose separately) makes review/decision separation operational | Critical Review folds · Validation Review outputs · DAR-1 · the checkpoint's routing |
| **P-6** | **Two-dimensional certification.** Method quality and evidence strength move independently and must be graded separately | CDR and checkpoint, twice in one cycle |
| **P-7** | **Assembly completeness ≠ knowledge completeness** | M8: a complete assembly of explicitly incomplete knowledge |
| **P-8** | **Candidate registers prevent informal adoption** without requiring refusal | PMR-1, PMR-2, §4.4 — three ideas held with full evidence chains, zero baseline impact |
| **P-9** | **Negative-space evidence was the highest-yielding discovery instrument observed** *(scoped: no other instrument's yield was measured)* | Four corpus rules discovered CBC-2 and later stated four of five relationships |
| **P-10** | **Withdrawal requires less warrant than assertion** *(scoped — see §11)* | DAR-1's acceptance of two withdrawals on absence-of-condition evidence |
| **P-11** | **Configuration control held three times against suggestions originating from the Authority itself** *(restated per RET-1b; the prescriptive form is carried as a Recommendation at §1 and §7)* | Configuration control held against Authority-originated suggestions three times |

---

## 10. Remaining Boundaries (Part G)

| Boundary | Items |
|---|---|
| **What remains unknown** | The interior of the expressed-knowledge core (T-16: it may be several contexts — the honest failure direction) · whether the corpus is one domain or three (OQ-PKS-7) · which designed system is the incumbent (OQ-PKS-2) · whether the authoritative/informational polarity is a rule (OQ-PKS-4) · whether "Qualification" is one concept or two (OQ-PKS-11) · the correct pattern names for R-1 and R-4 |
| **What remains intentionally undecided** | The candidate seam's promotion (preserved, not promoted) · three contested memberships (Risk · Question · Exception record) · the finer Norm-Custody ∥ Authorization partition (preserved, unreachable without MCR-3) · U-2's translation obligation |
| **What requires another execution lineage** | The **entire Operational Evidence certification dimension.** Nothing this program can do alone moves it — this is the single hardest boundary in the record, and it is structural, not a matter of effort |
| **What requires Authority** | An MCR-3 bounded re-entry collection · assignment of U-2 · ARB resolution of any Surfacing Register item · admission of the §4.4 observation to the PMR register · per-artifact promotion decisions |
| **What requires MCA** | Substantive assessment of PMR-1, PMR-2, and (after admission) the §4.4 observation · assessment of the P-1..P-11 patterns for promotion · whether the untested refinements (MCR-3, MCR-6) should remain adopted |
| **What requires CDR** | Any certification status change · any SDM/EOP amendment arising from an MCA · adoption or rejection of any promoted pattern |

---

## 11. Inputs to Promotion

**Recommendation (all items; nothing here is a decision):**

1. **Eleven candidate patterns P-1..P-11** (§9), each with its demonstration. **Recommendation (method only):** promote by *evidence strength*, not by appeal. **Evidence supplied as facts, ungraded** *(per RET-2)*: **P-3 n=2 · P-10 one scoped instance · P-2 five defects · P-4 n=2 · P-6 twice in one cycle · P-11 three instances.** **Relative strength is for the MCA to determine.**
2. **P-10's scope caveat, carried explicitly:** the DAR-1 formulation is deliberately narrow (*"when a claim is unsupported under the adopted evidentiary standard, withdrawing that unsupported claim does not require establishing an alternative claim"*) and was **expressly not adopted** as a methodology principle. Any promotion must start from that wording, not a broader one.
3. **The methodology observation classifications** (§8) — five strengthened, six unchanged, two confirmed-and-addressed, one new and unclassified.
4. **The §4.4 observation**, recommended for PMR admission with the checkpoint as its evidence trace.
5. **Two calibration data, offered without recommendations attached:** adoption outpaced exercise (a third of the adopted refinements unused after one cycle) · self-verification's 0-for-2 record.
6. **The recognition decision** (the governance layer as a distinct framework, Strategic DDD as the discovery technique within it) — recognized forward-looking at the CDR; naming and institutionalization remain promotion candidates.
7. **The deferred MCR-5 instrument**, with its trigger unchanged: the first independent lens, corpus, or executor.

## 12. Retrospective Conclusions

**Observed:** Phase II executed thirteen governance acts, produced eleven artifacts, detected and repaired five substantive defects, and changed its certification status zero times. All four statements are true simultaneously, and **Derived** *(scoped per RET-1a)*: that combination — high activity, high defect detection, zero certification drift — is what this program's governance process produced over one cycle.

**Derived — what the program learned about itself, in one sentence:** *its evidence is more reliable than its interpretations, its later stages are better instruments than its self-checks, and its confidence is only as good as its willingness to withdraw claims.*

**Observed — what it has not learned, and cannot learn alone:** whether any of this is repeatable by anyone else. Every act in Phase II shares one lineage. The program's own central research question remains untouched by thirteen acts of internal rigor.

**Recommendation (the only one this retrospective offers about sequencing):** the next act with genuinely new *information* value is one that changes something structural about who or what is executing — a second lineage, an external review, or an act that finally exercises MCR-1 in its defect domain. Further internal analysis of Phase II's outputs has reached diminishing returns; the record is complete, assembled, disposed, certified-provisionally, and now retrospected.

---

*Traceability: executes the Phase II Retrospective Commission RET-1 (PA, 2026-07-28) · evaluates the thirteen Phase II governance acts as process, with Parts A–G addressed in §§4–10 · every conclusion carries an epistemic class; no class migration occurred · classifies seventeen accumulated methodology observations without admitting any · identifies eleven durable patterns, all with recorded demonstrations · states remaining boundaries by owner · prepares promotion inputs · introduces no architectural conclusion, no governance decision, no methodology change; admits no PMR; resolves no Surfacing Register item; reopens nothing · same-lineage act (T-2). **STOP.***

> **Phase II Retrospective complete. Durable lessons extracted, methodology observations classified, and eleven demonstrated patterns identified as promotion candidates. No governance was reopened, no methodology changed, and no candidate admitted. The next act is per-artifact promotion, separately commissioned.**
