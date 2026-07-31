# PKS — Methodology Refinement Candidates (post-certification inbox)

| | |
|---|---|
| **Kind** | **Candidate register — an inbox, not a decision record.** Holds methodology refinement candidates raised *after* the CDR froze SDM v1 / EOP v1 and declared Process Under Configuration Control. Every entry is **NOT ADOPTED** until an MCA-class assessment evaluates it and a CDR-class decision disposes it. |
| **Authority** | Generated — never authoritative without human review. **Nothing in this file changes the methodology.** The frozen baseline is unaffected by the existence of any candidate here. |
| **Why this file exists** | Configuration control (CDR §4.3) admits methodology change only via **execution evidence → MCA-class assessment → CDR-class decision**. Candidates raised between cycles need a governed holding place, or they either get adopted informally (bypassing control) or lost. Precedent: the Phase-I retrospective inbox (`Strategic_Discovery_Methodology_Candidate.md`) and MCR-5's deferred instrument with its recorded trigger. |
| **Namespace declaration** (M4 identity discipline — collisions occur exactly where register discipline lapses) | This register mints **`PMR-n`** = *Post-certification Methodology Refinement candidate*. Deliberately distinct from **`MCR-n`**, which denotes the six refinements **adopted at CDR** — a `PMR` is a proposal, an `MCR` is baseline. The register structure is itself a candidate convention, pending MCA/CDR acknowledgment. |
| **Status** | **5 candidates open (PMR-1 … PMR-5), all raised 2026-07-28. None adopted, none assessed.** PMR-3, PMR-4, and PMR-5 were raised by the **reviewing Authority itself**, with the ARB attaching its own evidence condition to PMR-3 — recorded there as a datum. **PMR-5 carries the strongest evidence base in the register: three observed instances** (one violation, one near-miss, one weaker cousin). |
| **Placement** | `docs/implementation/`, beside the Phase II record set. |

---

## PMR-1 — Explicit taxonomy of finding classes

**Raised by:** PA, 2026-07-28, following the M7 Relationship Validation Review.

**Observed evidence (why this is evidence-gated, not speculative):** across the M6→M7 cycle, findings were routed by class **consistently but without a declared taxonomy** — editorial folds applied by the executing commission (Critical Review §12 folds, the M7 wording refinements); methodological observations routed to MCA/CDR (Ledger B B-1..B-9, the review's B-10); architectural findings routed to Authority disposition (F-M6CR-1, VF-1/VF-2/VF-3); governance findings routed to CDR (the recognition question); structural observations routed to the checkpoint (SI-1..SI-5, the stage-compensation property). **Five distinct routings, applied correctly five times, with the rule existing nowhere but in the executor's judgment.**

**Root cause:** the SDM/EOP defines *who disposes what* but never defines *what class of finding this is* — so routing is re-derived from first principles at every finding.

**Candidate refinement (PA's draft, recorded as drafted):**

| Class | Meaning | May change baseline? | Requires Authority? |
|---|---|---|---|
| Editorial | Wording/clarity only | Yes | No |
| Interpretive | Improves explanation | No | No |
| Architectural | Changes model/classification | No | Yes |
| Methodological | Changes the process | No | Yes (MCA/CDR) |
| Governance | Changes governance rules | No | Yes |

**Expected benefit:** every future review classifies each finding *before* deciding its handling, removing ambiguity from routing and making mis-routing visible as a category error rather than an unnoticed shortcut.

**Two questions this candidate must answer at assessment (raised here rather than silently fixed — the drafting is the PA's, and amending it inline would be exactly the informal adoption this register exists to prevent):**

1. **The Editorial row's "may change baseline: Yes" is ambiguous in a way that matters.** Under the CDR's adopted fold-class discipline, editorial refinements are *evidence-neutral*: they may change **wording within an artifact**, never **what the baseline asserts**. Read the other way, the row could license baseline edits under an editorial label — precisely what the frozen M6 record set's edit-freeze guards against. Recommended reformulation for assessment: *"may change wording within an artifact; may never change what the baseline asserts."*
2. **Is "Interpretive" genuinely Authority-free?** Two acts in this cycle were interpretive: the evidence matrix (§6.1) added a *new* per-relationship ownership assessment, and M7 §7.3's reclassification changed how a finding may be *used* downstream. Neither changed a classification, yet both changed what an artifact implies. The class boundary between Interpretive and Architectural needs a test, or Interpretive becomes the seam through which substantive change leaks without disposition.

**Status: NOT ADOPTED.** Routed to the next MCA-class assessment.

---

## PMR-2 — Composite architectural claims must not inherit their strongest component's confidence

**Raised by:** PA, 2026-07-28, generalizing from the evidence matrix.

**Observed evidence:** the M7 evidence matrix (review §6.1) decomposed each relationship claim into **dependency · ownership · pattern name** and measured them separately. The result was asymmetric in every row: the structural layer graded Strong-or-Moderate throughout, the pattern layer Strong in exactly one row of six. Before the decomposition, M7 §5 carried **one grade per relationship** — and that single grade tracked the best-supported component, so three relationships whose pattern names were Low/Weak had been presented at Medium/Medium-High. **The over-claim was real, present in a delivered artifact, and invisible until the claim was decomposed.**

**Root cause:** the Confidence Model composes a grade per *relationship* (or per *boundary*), not per *claim-dimension*, and offers no rule against a composite claim being cited at its strongest component's strength.

**Candidate principle (PA's formulation, preserved verbatim):**

> **Architectural claims are multi-dimensional and should not inherit the confidence of their strongest supporting component.**

**Operational form already demonstrated (M7 review §6.1/§8):** decompose the claim, grade each dimension, and constrain downstream citation per dimension — *cite the structural layer freely; cite pattern names only where the pattern column supports them.*

**Interaction with the adopted baseline (for the assessment):** this extends **MCR-5**, which already requires every grade to state its *independence basis*. PMR-2 would additionally require every composite grade to state its *dimensional decomposition*. The two are compatible and arguably belong together — a grade would then carry both what it is *based on* and what it is a grade *of*.

**One precision to settle at assessment (a stronger rule the evidence does not yet support):** PMR-2 as stated forbids **inheriting the strongest** component's confidence. It does **not** establish that a composite claim is only as strong as its **weakest** component — that would be a different and stronger aggregation rule, and the matrix does not evidence it (a claim citing only the structural layer is legitimately Strong even where the pattern layer is Weak). The evidenced rule is **per-dimension citation**, not weakest-link aggregation. Assessment should adopt the narrower, evidenced form or produce evidence for the stronger one.

**Status: NOT ADOPTED.** Routed to the next MCA-class assessment.

---

## PMR-3 — Distinguish permanent governance capabilities from situational execution controls

**Raised by:** ARB, 2026-07-28, in the "Approve with commendation" verdict on the Option B decision.

**Observed evidence — and it is deliberately asymmetric, because that is the honest state:**

- **Evidence that the controls added value *here*:** ERV-1 found **two Major defects in CCP-1** that no artifact-level review would have caught (three change items requiring editorial choice; one omitted propagation step). AIA-1 found that **REJECT was the larger governance act than ACCEPT** — a finding that would have inverted the Authority's likely default. AFV-1 found **four fidelity defects** including one Governance-class presupposition of an unresolved open question. **Each control earned its place in this cycle.**
- **Evidence about simpler contexts: NONE. n = 0.** Nothing in the record speaks to whether CCP-class planning or ERV-class plan-verification adds value where the change set is small, the baseline is uncertified, or no Authority-owned finding exists.

**Root cause the candidate addresses:** the chain now runs Discovery → Model → Architecture → Representation → Baseline → AFV → AIA → CCP → ERV → Execution → Authority → Promotion. **Governance has a cost** — maintenance, traceability, onboarding, review effort, cognitive load — and nothing in the methodology distinguishes capabilities that must always run from controls that were activated because *this* change was high-risk. **Absent that distinction, every future project inherits every historical safeguard whether or not its risk warrants it.**

**Candidate refinement (the ARB's classification, recorded as offered):**

| Category | Meaning | ARB's indicative placement |
|---|---|---|
| **A — permanent governance capabilities** | Define the methodology; always required | Discovery · Architecture · Verification · Authority · Handover. **AFV** reads as core |
| **B — situational execution controls** | Activated when risk warrants | **CCP · ERV.** *"Excellent controls for high-risk changes, but I would be cautious about declaring them universally mandatory … without evidence that they add value in simpler contexts"* |

**Two questions this candidate must answer at assessment — raised here rather than resolved:**

1. **Who decides that a change is "high-risk" enough to activate a Category-B control, and on what evidence?** If the executing party decides, the methodology has re-created the problem RET-1 recorded at **0-for-2**: self-verification is blind to its producer's defect class. **A situational control whose activation is judged by the party it would constrain is not reliably situational.** This is the candidate's central unresolved design question.
2. **Is "core versus situational" the right axis at all**, or is the better axis *what triggers a control* (e.g. *any Authority-owned finding activates AIA; any multi-artifact change set activates CCP*)? A trigger-based formulation would be evidence-testable in a way a category label is not.

**Note on the ARB's own framing, recorded because it is methodologically significant:** the ARB attached the condition *"without evidence that they add value in simpler contexts."* **That is the evidence-gate applying itself to the Authority's own recommendation** — the same discipline the program applies to executor proposals, now applied by the Authority to itself. **Derived:** this is the strongest available indication that the evidence-gated change rule has been internalized rather than merely enforced.

**Status: NOT ADOPTED.** Routed to the next MCA-class assessment.

---

## PMR-4 — Elevate the Strategic/Tactical boundary rule to a core methodology principle

**Raised by:** ARB, 2026-07-28 — *"I would probably elevate that to one of the core principles of the methodology."*

**The rule in question:** **Strategic DDD ends with *what the implementation must respect*. It does not end with *how implementation must realize it*** — with the handover inclusion list (certified baseline · assumptions · unresolved questions · implementation constraints · inheritance contract · explicit non-assumptions) and exclusion list (aggregates · repositories · services · APIs · infrastructure · technologies).

**Observed evidence:**

- The rule was **derived from a real boundary decision** (the Option B / Implementation Handover Governance naming refinement) rather than asserted in the abstract.
- It **has already done work**: it is the constraint that distinguishes *Knowledge Methodology → transition contract → Implementation Methodology* from *Knowledge Methodology → Implementation*, and the ARB identified the mis-naming as a real risk to a later reader.
- **Corroborating instances across Phase II:** AD-1 excluded tactical content and recorded six open questions rather than answering them; C4-1 refused to render actors, mechanism, or component structure; AFV-1 found that where AD-1 *did* over-reach, three of four cases were pre-flagged by the strategic model's own characterization columns. **Every instance is the same rule holding or failing.**

**Current standing:** recorded in `.claude/MEMORY.md` as a **durable governance distinction** — a *record*, not a methodology amendment. **Elevating it to a core SDM principle is a methodology change** and therefore requires the PMR → MCA → CDR path under configuration control; the record does not confer principle status by itself.

**One precision to settle at assessment:** the rule is currently stated for the *strategic/tactical* boundary. **Its corroborating instances suggest a more general form** — *a governed transformation states what the next stage must respect, never how that stage must realize it* — which would also describe Model→Architecture and Architecture→Representation. **The general form is not yet evidenced as such** and should not be adopted in place of the specific one without assessment.

**Status: NOT ADOPTED.** Routed to the next MCA-class assessment.

---

## PMR-5 — The Assembly Fidelity Rule

**Raised by:** the reviewer (ARB Chief / Principal Knowledge Architect) during the M8 review, 2026-07-28, following the disposition of Finding 1.

**Candidate rule (the reviewer's formulation, preserved):**

> **Assembly Fidelity Rule** — An assembly artifact shall not modify normative wording inherited from an authoritative source. Improvements to inherited normative language must be proposed against the authoritative source and propagated through the established publication chain.

**Observed evidence — three instances, which is the strongest evidence base of any candidate in this register:**

| # | Instance | Outcome |
|---|---|---|
| 1 | **VR-2 (the rule violated).** C4-1's Knowledge Structure View wrote *"never as a judgment"* where AD-1 says *"never a verdict"* — a governed concept name replaced by a generic word in a downstream artifact, by ordinary prose-smoothing | Found by C4-2, classified **Moderate**, correction still pending |
| 2 | **M8 Finding 1 (the rule nearly violated).** A reviewer proposed improving *"feed authority"* in M8 — where the phrase is M6 §7.4's frozen wording, faithfully quoted. **Applying it would have made M8 misquote its own frozen source** | Caught before application; routed to the source as held item H-1 |
| 3 | **VR-1 (the rule's weaker cousin).** Three C4-1 labels were sourced directly from M6/M8 rather than routed through AD-1 — inherited content taken outside the declared publication chain | Found by C4-2, classified Minor |

**Root cause the candidate addresses:** the methodology has rules about *who may change what* (CI-1: no editor-authored substance; CI-3: no vocabulary drift; AP-2/DP-7: a view is subordinate to what it renders) but **no rule about where an improvement to inherited normative wording must originate.** Instance 1 shows the gap is exploitable by well-intentioned editing; instance 2 shows it is exploitable by well-intentioned *reviewing*.

**Why it generalizes beyond this program (the reviewer's claim, assessed):** the rule is stated in terms of *assembly artifacts*, *authoritative sources*, and a *publication chain* — none of which is PKS-specific. **Every documentation set with a source-of-truth relationship has this defect class**, and the failure mode is invisible in isolation: the downstream text reads *better*, which is exactly why it survives review of the downstream artifact alone.

**Refinement folded (Chief Architect, 2026-07-28) — a three-level formulation, which resolves BOTH questions this candidate originally raised:**

| Level | Content class | Rule |
|---|---|---|
| **1** | **Governed terms** (UL concept names: *Verdict · Observation · Finding · criterion · candidate seam …*) | **Never paraphrase.** Any occurrence, in any artifact |
| **2** | **Normative rules** (stated rules, purpose statements, dispositions, principles) | **Never paraphrase independently — the source must be amended**, then propagated |
| **3** | **Explanatory prose** | **May be paraphrased**, provided meaning is preserved **and the paraphrase is marked** |

**Why the refinement matters, and what it closes:**
- **Original question 1 (scope of "normative wording") is ANSWERED.** VR-2 was a **Level 1** violation (*Verdict* → *judgment*); M8 Finding 1 concerned a **Level 2** item (a purpose statement's verb), which is why source amendment is the right route rather than either rewriting or refusing. **The two instances sit at different levels, which the single-level formulation could not express.**
- **Original question 2 (marked paraphrase) is ANSWERED by Level 3** — fidelity by **disclosure** rather than by prohibition, which this program already practises (*"verbatim-in-substance"*).
- **The over-restriction risk the candidate flagged is removed:** without Level 3, the rule would forbid the compression an assembly artifact legitimately needs; M8's own existence depends on being allowed to summarize.

**Remaining question for assessment (one, and narrower than the two it replaces):** **who classifies a given passage's level, and when?** A passage's level is not self-evident from its text — *"issue verdicts that feed authority"* contains a Level-1 term inside a Level-2 statement. **Recommendation for the assessment: level is a property of the source artifact's own marking, assigned when the source is written, not inferred by a downstream reader** — otherwise the rule relocates the judgment it exists to remove.

**Interaction with the adopted baseline:** this would sit alongside **CI-1** and **CI-3** as a configuration-integrity rule, and it would give **C4-2's DP-1 and DP-4** a textual counterpart — those govern *visual* precision and silence; this governs *inherited wording*.

**Status: NOT ADOPTED.** Routed to the next MCA-class assessment. *(Recorded here rather than adopted because elevating it to a governance principle is a methodology change, admissible only via PMR → MCA-class assessment → CDR-class decision under configuration control — the same path applied to PMR-1..PMR-4, two of which the Authority itself raised.)*

---

## PMR-6 — A per-finding confidence dimension *(assessed against the Authority's own standard and NOT adopted)*

**Raised by:** the Chief Architect, 2026-07-28, as proposed Rule 15 — *"every finding shall declare confidence: High / Medium / Low. Confidence reflects certainty that the finding is correct, not its severity."*

**Assessed against the standard the same instruction set:** *"New review dimensions should be added only when they repeatedly distinguish meaningful classes of findings that were previously conflated … additional dimensions (such as confidence or evidence origin) should likewise be adopted only if they demonstrably improve review quality over multiple review cycles."*

**Result of the assessment: the standard is not met. n = 0.**

**Observed evidence — every case that looked like a confidence problem was resolved by a different dimension:**

| Case | Looked like | Actually resolved by |
|---|---|---|
| **IBC-M11** — "five of eight dependency rules absent" | Low confidence that it was a real defect | **Rule 8 (authority basis)** — the requirement was *proposed*, not governed |
| **IBC-S2** — the "audit material" claim | Uncertainty whether it was a finding at all | **Rule 11 (resolving act)** — indeterminable, therefore a *question* |
| **IBC-m3** — the T-17 limb | Uncertainty about weight | **Rule 9 (category)** — a methodology observation in the wrong channel |
| **AFV-F1 · AFV-F3** | Reviewer judgment against a live alternative | **The strategic model's own (b)/(c) characterization columns** — external evidence, not internal confidence |

**Derived: across four review cycles (AFV-1 · C4-2 · KBI-1 · ERV-1) and three self-audits, there is not one instance where a confidence label would have changed a classification, a severity, or a routing.** Each apparent case had a *structural* cause that a structural dimension exposed.

**The theoretical case, recorded fairly:** a reviewer uncertain whether a finding is correct currently has no way to express that except by lowering **severity** — which is precisely the conflation Rule 9 was adopted to prevent. **So the case is real in principle.** It is simply unevidenced in practice.

**One duplication risk for the assessment to weigh:** the program **already** carries a confidence apparatus — M0's rubric, the Boundary Confidence composition, and MCR-5's mandatory independence basis — applied to **claims about the domain**. Extending confidence to **claims about artifacts** is a different object, and it may duplicate the *severity* and *authority-basis* dimensions rather than adding to them. **A finding's correctness is largely determined by its authority basis**: an authoritative-basis finding is correct or it is not, and there is little room for a middle grade.

**Trigger for re-assessment (recorded so the candidate is not merely shelved):** the first review cycle in which a reviewer cannot express a real uncertainty using authority basis, category, resolving act, or evidence origin. **At that point the dimension has its first instance.**

**Status: NOT ADOPTED.** Routed to the next MCA-class assessment. *(Recorded distinctly: this is the first candidate in this register that was **assessed and declined on the Authority's own evidential standard**, rather than merely held pending assessment.)*

---

## Register discipline

1. **Nothing here is baseline.** SDM v1 / EOP v1 as frozen at CDR remain the operative methodology; a PMR is a proposal with an evidence chain.
2. **Every PMR states its observed evidence.** A candidate without an execution-evidence trace does not belong in this register — the constitutional rule (*no methodology changes without execution evidence*) applies to candidates' admissibility, not only to adoptions.
3. **Adoption path:** MCA-class assessment (evaluate: confirmed / rejected / insufficient evidence / deferred) → CDR-class decision (adopt / adopt in part / decline / defer). On adoption a PMR is restated as an `MCR-n` in the baseline; the `PMR-n` entry remains as history.
4. **Additions are appends.** No entry is edited to change its meaning; supersession is forward-only.

---

*Traceability: opened 2026-07-28 under configuration control (CDR `PKS_Phase_II_CDR_Decision.md` §4.3) to hold post-certification methodology candidates without bypassing the evidence-gated change path · PMR-1 (finding-class taxonomy) and PMR-2 (no confidence inheritance in composite claims) raised by the PA following the M7 Relationship Validation Review, each with an observed-evidence trace from the M6→M7 cycle · both **NOT ADOPTED**; routed to the next MCA-class assessment · this register changes no methodology and no artifact.*

---

## PMR-7 — **A change item must specify the OBLIGATIONS an edit incurs, not only the WORDS it writes** *(raised 2026-07-30 by the CCP-1 controlled-change-integrity review; NOT adopted)*

**Statement (methodology level — the altitude matters):** ***the Controlled Change methodology must explicitly define OPERATIONAL DETERMINISM.*** *Not: "a future plan must contain these five clauses" — **a clause list would encode this execution's particular gaps; a defined property generalizes to gaps not yet encountered.***

**Operational form, for testing a specific plan:** *a controlled-change item is deterministic only when it specifies both the replacement wording **and** every obligation the edit incurs — counts a new row invalidates, history rows an amendment owes, supersession records a frozen artifact requires, and any sentence the supplied fragment leaves unfinished.*

**Evidence — five instances from one execution (CCP-1's, 2026-07-30), each producing a downstream defect:**

| Item | Obligation not specified | Downstream defect |
|---|---|---|
| **C-06** | The actual prefix list; the plan supplied a *form*, not a wording | marginal **CI-1** breach |
| **C-12** | The remainder of the provenance sentence | **PD-F2** (the executor's completion was false) |
| **C-13** | The omission **count** its new row invalidated | **C4R-2** |
| **C-19..C-21** | A **history row** (§11.6 covered C-09/C-15/C-16 only) | **PUB-7** |
| The amendment record | **Retention of superseded wordings** | **PD-F1 — Major, promotion-blocking** |

### The vocabulary the candidate carries *(ARB refinement, 2026-07-30 — the real architectural contribution)*

| Level | Property | Status |
|---|---|---|
| **Level 1 — TEXTUAL determinism** | **Editors never invent wording.** | **What CCP-1 set out to guarantee — and did.** All five gaps had their wording supplied or supplyable |
| **Level 2 — OPERATIONAL determinism** | **Editors never infer obligations that are not explicitly specified.** | **What execution actually tested — and CCP-1 did not guarantee** |

> ***Satisfying Level 1 does NOT guarantee Level 2.***

**And the refinement locates precisely which existing rule was insufficient: CI-1 forbids *"editor-authored substance"* — it prevented ARCHITECTURAL INVENTION and did NOT prevent CONTEXTUAL INFERENCE.** *Execution exposed that distinction. An editor who writes no new architecture but must work out that a new row invalidates a count has invented nothing and inferred something — and CI-1 is silent on the second.*

**This vocabulary is what makes the candidate usable without rewriting CCP-1: future change plans can be tested at Level 2 explicitly, and CCP-1 stands as the execution evidence that Level 1 alone is insufficient.**

**Why it is a methodology candidate and not a review conclusion: it refines the determinism test that ERV-F1 introduced** (*pre-supplied is not the same as determined*) — **a methodology change, admissible only via MCA-class assessment → CDR-class decision under Process Under Configuration Control.**

**Standing: CANDIDATE — ROUTED TO MCA 2026-07-30** by Authority disposition of CCP-R1. **Not adopted, not assessed.**

**Objective review questions the candidate supplies, one per level** *(this is what makes it testable rather than explanatory)*: **Level 1 — *"Did any editor author text?"*** · **Level 2 — *"Did any editor infer an unstated obligation?"*** *Both are answerable from an execution record, and a plan can pass the first while failing the second.*

**Framing constraint carried with the candidate: the assessment must NOT conclude that CI-1 failed.** ***CI-1 completely protects Level 1 determinism; Level 2 was never its subject.*** *A rule that fully achieves what it was written to achieve has not failed, and faulting it would rewrite history.* *n = 5 instances but **one execution lineage** — the same limit that caps every other grade in this programme. Its promotion evidence would be a second execution in which the obligation gaps do not recur.*

**Second datum added 2026-07-30 (ERV-R1): the VERIFICATION stage needs the definition as much as the PLANNING stage does.** **ERV-1's stated question was operational determinism** — *"can this be executed… without engineering judgment during execution?"* — **while its executed method verified textual determinism**, pushed from *existence* to *uniqueness* (ERV-F1). ***A verification cannot verify a property nobody has defined***, so defining operational determinism serves both stages or neither.

*Raised by: `PKS_Phase_II_CCP1_Controlled_Change_Integrity_Review.md` §3 (CCP-R1); second datum from `PKS_Phase_II_ERV1_Controlled_Change_Integrity_Review.md` §4 (ERV-R1).*

---

## Register act — **PMR-1 … PMR-7 ROUTED to MCA-class assessment** (Authority instruction, 2026-07-30)

**All seven candidates are routed as written.** *Nothing was added, narrowed, merged or reworded. Append-only discipline (§4) observed.*

**Input package: `PKS_Phase_II_MCA_Input_Package_PMR1-7.md`. Assessment label: MCA-R1** — the first MCA-class assessment convened against this register. *Deliberately not "MCA-1": earlier records refer to an "MCA-class assessment" in the Phase II process chain and no artifact of that name exists in the repository. The ambiguity is named, not resolved.*

**Four cross-candidate facts were surfaced for the assessment and NONE was resolved by the routing act:**

1. **⚠️ PMR-1 may already be discharged** — the finding vocabulary was relocated into the Review Method (FW-1's disposition), which now also owns Observations vs Findings. **Whether the Method supplies what PMR-1 proposed is an ASSESSMENT question.** ***And the sharper governance question: a framework repair closed a gap a methodology candidate was queued to close — MCA-R1 should record whether that is a lawful route or an accidental bypass of PMR → MCA → CDR.***
2. **⚠️ PMR-3 ⟂ PMR-7** — the same CCP/ERV-class controls from opposite directions: PMR-3 asks about their **SCOPE** (permanent or situational), PMR-7 about their **CONTENT** (is the key term defined). **Adopting PMR-7 strengthens a control whose permanence PMR-3 has not established.** Raised two days apart by different parties; neither cites the other.
3. **PMR-6 is routed as PRECEDENT, not as a proposal** — the register's only completed assessment (not adopted at n=0) and therefore MCA-R1's **only calibration for the strengthened filter.** ***A register whose sole rejection were withheld would present six proposals and no evidence that rejection is a real outcome.***
4. **⚠️ PMR-7's binding framing constraint** — **the assessment must NOT conclude that CI-1 failed** (CI-1 completely protects Level 1 textual determinism and was never Level 2's guardian); **Level 2 was not nameable in advance**, so treating it as a control failure would apply hindsight as a standard; and ***acceptance of the evidence is not acceptance of the lesson*** — PMR-7 gained no standing from ERV-R1's acceptance beyond the datum.

**The boundary the act observed:** it carried **each candidate's evidence COUNT** (a matter of record) and withheld **whether the count SUFFICES** (a matter of judgment). **No verdict, no priority order, no new candidate, no baseline change — SDM v1 / EOP v1 remain operative.**

***The temptation specifically declined: PMR-1 was the one candidate that could have been quietly dropped as "already handled." Dropping it would have been a disposition performed by a routing act.***

**Register status: 7 candidates, all ROUTED, none assessed, none adopted. Awaiting MCA-R1 → CDR-class decision.**

---

## Register act — **MCA-R1 ASSESSED all seven candidates** (2026-07-30) · *appended, no entry edited*

**Assessment: `PKS_Phase_II_MCA_R1_Assessment.md`. Nothing adopted — adoption is CDR-class.**

| # | Verdict |
|---|---|
| **PMR-1** | **INSUFFICIENT EVIDENCE** — partially discharged (Method now owns the finding vocabulary); residue = routing classes only. ***Its own evidence tells against it: routing was "consistent", i.e. the absence let NO defect escape*** |
| **PMR-2** | **CONFIRMED as a defect class · INSUFFICIENT for promotion.** n=1 verified (M7 §9 passed three unsupported pattern claims). **Discharge condition: verify whether AFV-1's four over-strengthenings are instances — one verification decides it** |
| **PMR-3** | **INSUFFICIENT (STRUCTURALLY) · DEFER recommended on a DATA condition (n ≥ 2 simpler contexts), not a date.** *A programme with one context cannot establish which of its controls are context-dependent* |
| **PMR-4** | **CONFIRMED** — the rule has already DECIDED cases (ADR-001's framing · IBC-1's relocation · DR-7). ***A rule that has decided cases is operating as a principle whether or not it is labelled one.*** ⚠️ **Precedence consequence UNASSESSED — no collision case exists** |
| **PMR-5** | **✅ CONFIRMED — FILTER MET.** M8 Finding 1 · **the eleven-site KC-13/KC-17 ADR cascade** · §13's duplication of §7. ***The cost of assembly-time restatement is one defect PER RESTATEMENT SITE*** |
| **PMR-6** | **REJECTED — now on stronger grounds than n=0.** The gap was real but was filled by a **structural distinction** (Observation vs Finding; directly-supported vs speculative), not a scale |
| **PMR-7** | **CONFIRMED as to the DEFINITION · INSUFFICIENT as to the CONTROLS.** ***The two data are ONE defect at TWO stages supporting TWO different controls at n=1 each.*** Framing constraint honoured — the vocabulary **BOUNDS** CI-1 rather than faulting it |

**Assessment findings about the path itself:**
- **§3.1 Bypass question ANSWERED: NOT a bypass** (FW-1 touched the framework, not SDM/EOP). **But the gap it exposes is recorded: the framework and the methodology have OVERLAPPING SCOPE and no rule assigns ownership of finding vocabulary — and where two lawful paths have different gates, *the weaker gate governs in practice*.**
- **§3.2 The register can silently UNDERSTATE a candidate:** discipline §2 requires evidence at entry, nothing requires later evidence to be appended. **PMR-5's support grew from one case to three and none of the growth reached its entry — the register's strongest candidate was carrying its weakest evidence.**
- **§3.3 Pass rate 1-of-7 recorded with BOTH readings** (the filter is working ⟂ the filter may be narrow, since PMR-1 failed *because* the practice it would codify was performed correctly). **Neither adopted — choosing would revise the filter, which is not this act's material.**

**Register status: 7 assessed · 0 adopted · SDM v1 / EOP v1 operative. Awaiting CDR-class decision.**

---

## PMR-8 — **An MCA-class assessment must establish FILTER APPLICABILITY before evaluating evidence** *(raised 2026-07-30; NOT adopted)*

**Raised by:** the ARB Chair / Decision Authority, 2026-07-30, on reading MCA-R1 — *"The strengthened filter only evaluates controls… That seems obvious after reading it, but it isn't… The applicability test therefore belongs before evidence evaluation. I think that's the right order."*

**⚠️ Why this is a CANDIDATE and not an adopted rule, despite the Authority having endorsed it:** ***it prescribes how FUTURE ASSESSMENTS must be performed, which makes it a methodology change — and methodology changes are admissible only via PMR → MCA-class assessment → CDR.*** **An endorsement is not an adoption. Recording it here is the only lawful way to carry it forward.** *The same discipline the programme applied when it refused to let review recommendations become architecture applies to Authority observations about method.*

**Candidate rule:**

> **Before evaluating any candidate's evidence, an MCA-class assessment shall classify the candidate by KIND — control · vocabulary/definition · taxonomy · status elevation — and determine which filter is applicable. Evidence is then evaluated against the applicable filter only.**

**Observed evidence (n = 1, and it is MCA-R1 itself):** **the strengthened filter — *a control becomes GOVERNED only after repeated operational evidence that its absence let defects escape* — is stated for CONTROLS. Three of the seven candidates assessed proposed no control.** **Without the applicability step, an escape-based test would have systematically and falsely rejected:**

| Candidate kind | Why the escape test misfires | Instance |
|---|---|---|
| **Vocabulary / definition** | *A definition mandates no activity, so its absence cannot let a defect escape* | **PMR-7's two-level determinism vocabulary** |
| **Taxonomy** | *Its evidence is that practice was already correct — which the filter reads as evidence AGAINST it* | **PMR-1** |
| **Status elevation** | *Nothing is added, so nothing can be absent* | **PMR-4** |

***The failure mode is silent: each rejection would have been individually defensible and collectively systematic — the filter would have been rejecting a whole CLASS of candidate while appearing to judge them one at a time.***

**Interaction with the register, for the assessment:** **PMR-8 is NOT within MCA-R1's scope.** *MCA-R1 assessed PMR-1..PMR-7 and is closed as to those; PMR-8 awaits a future MCA-class assessment. Assessing a rule about assessments inside the assessment that generated it would be self-certification.*

**Related open question, recorded not resolved:** MCA-R1 §1 already records that the filter *"measures ONE kind of value — defect prevention"* and that a candidate whose value is comprehension, onboarding or scale is invisible to it. ***PMR-8 makes the filter's scope explicit; it does not widen it. Whether the filter SHOULD be widened is a separate candidate nobody has raised.***

**Status: NOT ADOPTED. Awaiting a future MCA-class assessment → CDR.**

**PMR-8 — SCOPE MARKING** *(appended 2026-07-31 under the CDR-R1 readiness verification; no entry edited)*: **PMR-8 is expressly OUT OF SCOPE for CDR-R1 as well as for MCA-R1.** *It has not been assessed, and a CDR cannot decide an unassessed candidate — the register's adoption path runs MCA-class assessment → CDR-class decision, with no route that skips the first.* **PMR-8 awaits a FUTURE MCA-class assessment, then a future CDR.**

---

## Backlog item (NOT a PMR) — **"AFV" terminology normalization**

**Recorded per Authority ruling (2026-07-31): kept on the backlog, NOT prioritized over the current queue, and expressly NOT raised as a methodology candidate.**

**Three legitimate interpretations share one token:** **AFV-1** (a promoted artifact) · **AFV-class verification** (an activity type) · **PMR-2's discharge verification** (a specific act).

**Why it belongs on a backlog at all — the Authority's ground: *"not because the names are aesthetically poor, but because you've already encountered an operational consequence. One recommendation became impossible to execute because three legitimate interpretations existed."*** ***That is governance ambiguity, not stylistic ambiguity.***

**Trigger condition, stated so this does not sit here indefinitely without a criterion: *if similar collisions begin appearing elsewhere.*** *One collision is a backlog item; a second makes it a candidate.*

**Not a PMR because it changes no future practice — it renames existing objects, and renaming is constrained by the identifier-stability rule (clarify → alias → annotate, never renumber).** ***So any normalization must proceed by ALIAS, not by rename — which means the collision can be made navigable but never erased.***

---

## PMR-9 — **Source verification must precede CLASSIFICATION, not merely assertion** *(raised 2026-07-31; NOT adopted)*

**Raised by:** the ARB Chair / Decision Authority, 2026-07-31, on the CON-F1 reframing — *"Source verification changed the ontology of the finding… That demonstrates why source verification belongs before classification whenever classification depends on what the governing source actually says."*

**⚠️ CANDIDATE, not adopted, despite Authority endorsement: it prescribes how FUTURE REVIEWS are performed.** *An endorsement is not an adoption (Discipline Amendment 4).*

**Candidate rule:**

> **Where a finding's CLASS depends on what a governing source actually says, the source shall be verified before the finding is classified — not merely before it is asserted.**

**Observed evidence (n = 1):** **CON-F1.** *Verification did not adjust severity; it changed the defect CATEGORY — from "the consolidation minted an obligation" to "the consolidation staged a contradiction between two items."* ***The remedy proposed under the first classification (cite it, or scope it) would not have touched the actual defect. A severity error is a mis-measurement; an ontology error is a mis-identification.***

**Related but DISTINCT prior datum, recorded so the assessment can judge whether it counts:** the **RET-3** lesson — *verify support before calling something a defect* — is verification before **assertion**. **PMR-9 is the stronger claim: verification before CLASSIFICATION.** *Whether RET-3 supplies a second datum for the stronger claim is an assessment question, not a register question.*

**⚠️ Deliberately NOT bundled with PMR-8.** *Both are ordering rules, and both were raised by the same party on the same shape of insight — PMR-8: applicability before evidence, for MCA-class assessments; PMR-9: source verification before classification, for reviews.* ***They are separated because CDR-R1 established that bundling distinct propositions under one identifier distorts the evidence count — PMR-7 had to be split into 7a/7b/7c for exactly that reason.***

**Status: NOT ADOPTED. Awaiting a future MCA-class assessment → CDR. Out of scope for MCA-R1 (closed) and CDR-R1 (issued).**

**MCA CIRCULARITY — RESOLVED for PMR-8 and PMR-9** *(Authority determination, 2026-07-31; appended, no entry edited)*

**The circularity raised: an MCA assessing PMR-8 or PMR-9 would have to decide whether to USE the proposed procedure while assessing it.**

**Authority resolution, adopted:** ***"An MCA assessing PMR-8 or PMR-9 doesn't necessarily have to USE those proposed rules as binding procedure. It can evaluate them under the existing certified methodology, treating them as HYPOTHESES."***

> ### **The assessment's question becomes: *"Would adopting PMR-9 improve the methodology, based on evidence gathered under the CURRENT methodology?"***

**That avoids the candidate having to justify itself.** *Consistent with CDR-R1 §1's determination that MCA-R1 INTERPRETED an existing control rather than applying an unadopted one — the same separation, applied prospectively.*

**⚠️ One residual noted, not resolved: the evidence for both candidates was GATHERED in acts that already applied the proposed ordering** (MCA-R1 established applicability before evidence; CON-1's reframing came from verifying before classifying). ***So the evidence is not procedure-neutral even if the assessment is. A future MCA should state how it weighs evidence produced by the very practice under assessment.***

---

## PMR-10 — **An identifier must be checked for collision before it is minted** *(raised 2026-07-31; NOT adopted)*

**Raised by:** the reviewer, 2026-07-31, on discovering that **"MCA-R1" collided with an acronym already in use across four artifacts** (`MCA` = **Method Certification Assessment**).

**Candidate rule:**

> **Before an identifier or acronym is minted, the corpus shall be searched for existing use of that token. Where the token is taken, a distinct one is chosen.**

**Observed evidence — n = 2, and the SECOND datum meets the trigger already recorded on the AFV backlog item** (*"one collision is a backlog item; a second makes it a candidate"*):

| n | Collision | Consequence |
|---|---|---|
| 1 | **"AFV"** — a promoted artifact · an activity class · a specific discharge verification | **An Authority recommendation became IMPOSSIBLE TO EXECUTE because three legitimate readings existed** |
| 2 | **"MCA"** — Method Certification Assessment (pre-existing, four artifacts) · the methodology-change assessment labelled "MCA-R1" (minted 2026-07-30) | **A false claim was published** (*"no artifact of that name exists"*) **and a permanent collision was created** |

**⚠️ The second collision was MINTED BY THIS PROGRAMME, not inherited — and it was minted while the programme was actively recording the rule that names cannot be withdrawn.** ***That is the strongest available evidence for a pre-mint check: the party most alert to the cost of naming still incurred it, because the check was a habit rather than a step.***

**Why the escape test is satisfied here, unlike PMR-1's:** **both collisions produced a defect that ESCAPED** — the AFV collision blocked an act, and the MCA collision published a false statement. *Neither was caught by the act that caused it.*

**Distinguished from the AFV BACKLOG item, which remains separate:** *the backlog item is BACKWARD-looking (normalize existing collisions, necessarily by alias since renaming is forbidden). **PMR-10 is FORWARD-looking (prevent new ones) — and only the forward-looking rule changes future practice, which is why only it is a PMR.***

**Status: NOT ADOPTED. Awaiting a future MCA-class assessment → CDR.** *Out of scope for MCA-R1 (closed) and CDR-R1 (issued).*

**REGISTER NOTE — AVOID TREATING TEMPORAL CLUSTERING AS EVIDENTIAL CLUSTERING** *(Authority sharpening, 2026-07-31; supersedes the reviewer's weaker "don't infer a trend too quickly")*

> ### **These candidates appeared close together because several reviews happened close together. That alone says nothing about whether they support one another.**

**Standing counts, never aggregated: PMR-8 n=1 · PMR-9 n=2 (see below) · PMR-10 n=2.**

**⚠️ THE DISTINCTION THIS SHARPENING MAKES OPERATIONAL, and it cuts both ways:**

| Not evidence | **Is evidence** |
|---|---|
| **Temporal clustering of DIFFERENT candidates** — three proposals arriving from three consecutive reviews | **Repeated instances of ONE candidate's proposition** — the same claim occurring twice on independent occasions |

***Consequence, and it is not a relaxation of the caution but an application of it: PMR-9's proposition — "verification before classification changes the finding" — now has TWO independent instances: CON-1 (the ontology shifted from "new obligation" to "internally inconsistent staged contract") and MC-1 (a candidate Major finding dissolved). PMR-9 therefore stands at n=2, while PMR-8 remains at n=1.***

**Whether n=2 MEETS the strengthened filter is expressly NOT decided here — that is the assessment's judgment.** *The register records counts; it never rules on sufficiency.*

**⚠️ Also recorded so the count is not read as stronger than it is: both PMR-9 instances arose in reviews conducted by the same party, and the residual already logged applies — *the evidence is not procedure-neutral even if a future assessment is.***

---

**REGISTER NOTE — a cluster is not a corroboration** *(Authority caution, 2026-07-31; appended, no entry edited)*

**PMR-8, PMR-9 and PMR-10 arose from three consecutive reviews. The Authority's caution, adopted:** *"Three distinct methodological candidates arising from three reviews is useful evidence, but **each still needs to stand on its own empirical support.**"*

> **This caution is PMR-2's own prohibition applied at REGISTER level.** *PMR-2 forbids a composite claim from inheriting its strongest component's confidence; reading "three candidates from three reviews" as support for any one of them is that same error, one level up.*

**Standing counts, stated so they are never aggregated: PMR-8 n=1 · PMR-9 n=1 · PMR-10 n=2.**

***The candidates share an ORIGIN, not an EVIDENCE BASE — and shared origin is the weakest possible correlation to mistake for independent support.*** **Confirms register discipline §2 rather than extending it; nothing is adopted.**

**REGISTER ACT — PMR-8 · PMR-9 · PMR-10 ROUTED to MCA-R2** *(Authority instruction, 2026-07-31; appended, no entry edited)*

**Commission: `PKS_Phase_II_MCA_R2_Commission.md`. Label MCA-R2** — the second MCA-class assessment against this register, **collision-checked before minting** *(practising PMR-10 while it is unassessed; offered as practice, not compliance)*.

**Counts carried and never aggregated: PMR-8 n=1 · PMR-9 n=2 · PMR-10 n=2.**

**All three are CONTROLS** *(each mandates an activity)*, **so unlike MCA-R1's candidates the escape-based filter applies as written with no applicability adaptation.**

**⚠️ The central question posed to MCA-R2 and NOT resolved here: ESCAPE vs COUNTERFACTUAL.** *The filter requires evidence that a control's absence let defects **escape**. PMR-8's datum is a prevented false rejection; PMR-9's are one caught by a later stage and one caught within the act; PMR-10's are two that escaped — one blocked an Authority recommendation, one published a false claim.* ***The register's counts do not record which instances escaped the stage that owned the check, and the filter's wording turns on exactly that.***

**Circularity resolved: evaluate as HYPOTHESES under the existing certified methodology. MCA-R2 may INTERPRET the filter; it may not APPLY PMR-8 TO PMR-8.**

**⚠️ Residual carried: the ASSESSMENT can be procedure-neutral while the EVIDENCE cannot — all three candidates' evidence was gathered in acts that already applied the proposed ordering.**

**Register status: PMR-1..7 assessed and decided at CDR-R1 · PMR-8/9/10 ROUTED to MCA-R2, unassessed · SDM v1.1 / EOP v1.1 operative.**

**REGISTER ACT — MCA-R2 ASSESSED PMR-8 · PMR-9 · PMR-10** *(2026-07-31; appended, no entry edited)*

**Assessment: `PKS_Phase_II_MCA_R2_Assessment.md`. Nothing adopted — adoption is CDR-class.**

| # | Verdict | Escapes |
|---|---|---|
| **PMR-8** | **INSUFFICIENT EVIDENCE** | **0** — *its sole datum is its own successful voluntary application; structurally self-defeating in the exact way PMR-1's was* |
| **PMR-9** | ✅ **CONFIRMED** | **3** — CON-1 · M7-CF2 · IBC-1 re-certification |
| **PMR-10** | ✅ **CONFIRMED** | **2** — the AFV collision · the MCA collision |

**⚠️ THIS REGISTER'S COUNT RE-DERIVED AGAINST THE CRITERIA: PMR-9's recorded n=2 was INCONSISTENT WITH THE ASSESSMENT CRITERIA. It counted MC-1, which was self-caught within the act and is therefore NOT an escape, and omitted two escapes (M7-CF2, IBC-1 re-certification).** ***The undercount is itself a datum for the candidate: the register recorded instances by NOTABILITY rather than by the filter's own test — which is what happens when classification precedes verification.***

**ESCAPE determination (a reading of the existing filter, not a new rule):** *escape means the defect **left the stage that owned the check**, grounded in RET-1 §5.3's operative sense. A defect **prevented** or **caught within** its own act is not an escape.*

**The procedure-taint residual RESOLVED as a consequence:** *evidence **produced by** the practice yields counterfactuals; evidence of **escape** is procedure-**independent**, because an escape is an observation of failure.*

**Register status: PMR-1..7 decided at CDR-R1 · PMR-8 insufficient · PMR-9 and PMR-10 CONFIRMED, awaiting a CDR-class decision · 10 candidates, none added · SDM v1.1 / EOP v1.1 operative.**

**REGISTER ACT — CDR-R2 DECIDED PMR-8 · PMR-9 · PMR-10** *(2026-07-31; appended, no entry edited)*

**Decision: `PKS_Phase_II_CDR_R2_Decision.md`. 2 ADOPT · 1 DEFER. Baseline NOT amended — SDM v1.1 / EOP v1.1 remain operative until an amended baseline is issued.**

| # | Decision |
|---|---|
| **PMR-9** | ✅ **ADOPTED VERBATIM.** *Three escapes at different times, on different artifacts, producing different error types (ontology · severity · grading)* — **more convincing than three repetitions of one mistake, because distinct failure shapes indicate a missing step.** Candidate → **GOVERNED**, prospective |
| **PMR-10** | ✅ **ADOPTED VERBATIM.** Two escapes. ⚠️ **Prospective scope matters especially here: *the two existing collisions CANNOT be cured — identifier stability forbids renaming, so adoption prevents the THIRD collision and does not repair the first two.*** Renaming `AFV-1` / `MCA-R1` remains **forbidden**; the AFV normalization stays on the backlog, **by ALIAS only** |
| **PMR-8** | ⏸ **DEFERRED, not declined** — *the assessment showed the CURRENT ADMISSION CRITERION unsatisfied, not the candidate valueless; declining would answer a question the assessment expressly did not answer.* **Discharge: one instance where absent applicability let a false verdict ESCAPE into a decision.** ⚠️ *Structural difficulty recorded: its discharge requires a governance FAILURE the programme is actively preventing* |

**Neither adopted rule was improved during adoption** — *the Assembly Fidelity Rule is GOVERNED, and ISV-F1 established that paraphrasing while asserting verbatim adoption breaches it at the moment of enactment.*

**NOT decided: the escape filter's BLIND SPOT** *(it measures only defect prevention — revising it would amend the framework's admission ladder, not a PMR)* **· an alternative admission path for PMR-8 · the AFV normalization · any CDR-R1 entry · certification state.**

**Register status: 10 candidates, none added · PMR-9 and PMR-10 ADOPTED (awaiting baseline issuance) · PMR-8 deferred · PMR-1..7 as decided at CDR-R1.**

**REGISTER ACT — SDM v1.2 / EOP v1.2 ISSUED** *(2026-07-31; appended, no entry edited)*

**`PKS_Phase_II_Methodology_Baseline_v1_2.md`. PMR-9 and PMR-10 are now GOVERNED and OPERATIVE, prospective.**

**⚠️ Verbatim verification was PERFORMED BEFORE the verbatim claim was made** — both clauses extracted mechanically from this register and carried unmodified (174 and 156 characters), then **diffed post-issuance: both IDENTICAL.** ***ISV-F1 established that asserting verbatim reproduction while carrying a paraphrase breaches the Assembly Fidelity Rule at the moment of enacting it; the check is the only thing distinguishing a verbatim claim from a hopeful one.***

**Carried INTO the baseline for PMR-10 so it cannot be lost in later citation: *the two collisions that evidenced the rule cannot be cured by it — renaming remains FORBIDDEN and no retroactive collision audit is authorized.***

**Register status: PMR-9 · PMR-10 GOVERNED (v1.2) · PMR-8 DEFERRED · PMR-1..7 as decided at CDR-R1 · 10 candidates, none added · the escape filter and admission ladder UNCHANGED, blind spot open.**
