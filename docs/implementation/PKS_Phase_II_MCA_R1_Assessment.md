# PKS Phase II — **MCA-R1: Methodology Change Assessment, PMR-1 … PMR-7**

| | |
|---|---|
| **Act** | **MCA-class ASSESSMENT** (Authority instruction, 2026-07-30: *"Convene MCA-R1 and assess all seven candidates"*) |
| **Input** | `PKS_Phase_II_MCA_Input_Package_PMR1-7.md` |
| **Verdict vocabulary — fixed by register discipline §3** | **confirmed · rejected · insufficient evidence · deferred** |
| **⚠️ What this act does NOT do** | ***It adopts nothing.*** **Adoption is a CDR-class decision (adopt / adopt in part / decline / defer). Even the candidate that fully clears the filter is NOT adopted by this act** |
| **Baseline** | **SDM v1 / EOP v1 remain operative and are unchanged by this assessment** |
| **Status — as at assessment (2026-07-30)** | **ASSESSED. 2 confirmed · 1 confirmed-in-part · 1 rejected · 3 insufficient evidence. Awaiting CDR-class decision.** |
| **⚠️ CURRENCY — read with the row above** | **CLOSED (Authority act, 2026-07-31). The awaited CDR-class decision was taken — CDR-R1, 2026-07-31 — and its adoptions were issued as SDM v1.1 / EOP v1.1.** *Verdicts above are unchanged by closure; see the closure record at the end of this document. Annotated, not amended — this is a frozen assessment record.* |

---

## 1. A finding about the filter itself, which had to be settled before any candidate could be assessed

**The strengthened filter reads:**

> **A control becomes GOVERNED only after REPEATED operational evidence that its absence let defects escape.**

**It is stated for CONTROLS. Three of the seven candidates do not propose a control**, and applying an escape-based test to them would have produced a false rejection:

| Candidate kind | Examples | Why the filter needs adapting |
|---|---|---|
| **New control** — adds a required activity | PMR-2 · PMR-5 · PMR-6 · PMR-7's rule | ✅ **Filter applies as written** |
| **Vocabulary / definition** — makes an existing distinction stateable | **PMR-7's two-level vocabulary** | **A definition mandates no activity, so its "absence" cannot let a defect escape. The right test is whether the distinction it names is REAL and was previously unstateable** |
| **Taxonomy** — declares what is already practised | **PMR-1** | **Its own evidence is that practice was already correct. See §2.1 — this cuts against the candidate, not for it** |
| **Status elevation** — changes precedence, not activity | **PMR-4** | **Nothing is added, so nothing can be absent. The right test is whether the rule is ALREADY functioning as a principle** |

***Recorded for the CDR, and not resolved here: the filter as written measures ONE kind of value — defect prevention. A candidate whose value is comprehension, onboarding or scale is invisible to it. Whether that is a correct narrowing or an unintended one is a methodology-of-methodology question this assessment has no authority to settle.***

---

## 2. Candidate assessments

### 2.1 PMR-1 — Explicit taxonomy of finding classes → **INSUFFICIENT EVIDENCE**

**First question, per the input package: is the candidate still live?** **PARTIALLY DISCHARGED.** The Review Method now owns the finding vocabulary (relocated by FW-1's disposition) and the **Observations vs Findings** distinction with its discriminating tests. **What PMR-1 additionally proposed — a declared taxonomy of *routing* classes (editorial fold · governance decision · Authority act) — is NOT supplied by the Method**, though it is visibly practised: KBI-1 §11 carries a *"Correction class"* column, and Packages A–E classify every change item by remedy type.

**So a residue is live. Assessed on its merits, and the result is uncomfortable for the candidate:**

> ***PMR-1's own supporting evidence is evidence against it under this filter.*** **The register records that routing was *"consistent but without a declared taxonomy."*** **Consistency is precisely the observation that the control's absence has NOT let a defect escape.**

**Across M6 → M7 → the ADR commission → Packages A–E, no finding is on the record as mis-routed.** **n = 0 escapes.**

**VERDICT: INSUFFICIENT EVIDENCE.** *Not rejected — the candidate is coherent and its residue is real. It fails a filter that measures escape, and the value it claims is re-derivation cost, which this filter does not measure (§1).*

**For the CDR:** *if adopted at all, the adoptable object is now narrower than the entry proposes — routing classes only, the finding vocabulary having been supplied elsewhere.* ***Narrowing is an assessment output; whether to adopt the narrowed form is the CDR's.***

---

### 2.2 PMR-2 — Composite claims must not inherit their strongest component's confidence → **CONFIRMED AS A DEFECT CLASS · INSUFFICIENT EVIDENCE FOR PROMOTION**

**The defect class is real and an escape is on the record.** **M7's relationship claims decomposed into *dependency · ownership · pattern name* and the components were evidenced asymmetrically. M7 §9 self-verification PASSED three unsupported pattern claims**, which were caught only by the later M7 Validation Review (VF-1..VF-3) and withdrawn by DAR-1.

**That is escape in the meaningful sense — escape from the stage that owned the check — and it is the same measure RET-1 §5.3 already quantifies: *self-verification by the executing stage is 0-for-2.***

**n = 1 verified.** **A second datum is IDENTIFIED BUT NOT VERIFIED: AFV-1's four interpretive over-strengthenings at the Model → Architecture link.** *If those are instances of a derived claim carrying more confidence than its basis, the filter is met. This assessment did not verify them, and does not assert them.*

**VERDICT: INSUFFICIENT EVIDENCE for promotion, with a specific and cheap discharge condition** — **verify whether AFV-1's four over-strengthenings are instances of composite-confidence inheritance.** *One verification decides this candidate.*

**The open precision the register flagged is NOT resolved here and must not be:** PMR-2 forbids inheriting the **strongest** component's confidence; it does not establish that a composite is only as strong as its **weakest**. ***The evidence supports the prohibition, not the stronger positive rule.***

---

### 2.3 PMR-3 — Permanent governance capabilities vs situational execution controls → **INSUFFICIENT EVIDENCE (structurally) · DEFER RECOMMENDED**

**Evidence for value in a complex context is strong: ERV-1 found two Major defects in CCP-1 that no artifact-level review would have caught.** **Evidence about simpler contexts remains n = 0.**

**The decisive point is that this is not an incidental gap but a structural one.** **PMR-3 asks for a *distinction*, and a distinction requires COMPARATIVE data — at least one simpler context in which the controls were applied and found unnecessary. This programme has one context.**

***This is the same shape as the Operational Evidence dimension, and the principle recorded 2026-07-30 governs it: a judgment derives credibility from being outside the unilateral control of the programme making it. A programme with one context cannot establish which of its controls are context-dependent.***

**VERDICT: INSUFFICIENT EVIDENCE.** **Recommended CDR disposition: DEFER — with the deferral condition stated as a DATA REQUIREMENT (n ≥ 2 simpler contexts in which CCP/ERV-class controls were applied), NOT as a date.** ***A deferral to a date expires without producing knowledge; a deferral to a data condition cannot be discharged by the passage of time.***

---

### 2.4 PMR-4 — Elevate the Strategic/Tactical boundary rule to a core methodology principle → **CONFIRMED**

**The escape-based filter does not apply (§1): elevation mandates no new activity. The applicable test is whether the rule is ALREADY functioning as a core principle. It is, and repeatedly — it has DECIDED cases:**

| Case | How the rule determined the outcome |
|---|---|
| **ADR-001** | Titled and framed as the *Implementation Agnostic Strategic Baseline* — the rule is the artifact's organising constraint |
| **IBC-1's relocation** | Moved from upstream to downstream of the ADR **because** Strategic DDD ends at *what must be respected* |
| **AD-1 / DR-7** | The handover inclusion list, and DR-7's forward-reaching clause restated as a reopening trigger rather than a realization instruction |

> ***A rule that has already decided multiple cases is operating as a principle whether or not it is labelled one. Elevation records reality; it does not change practice.***

**VERDICT: CONFIRMED.**

**⚠️ One consequence is UNASSESSED, and the CDR must not receive this verdict without it: elevation to a *core* principle changes CONFLICT RESOLUTION — it grants precedence when principles collide. No case of collision is on the record.** ***So the rule's operative status is confirmed; the precedence it would acquire is untested.*** *That is an adopt-in-part shape, but the shape is the CDR's to choose.*

---

### 2.5 PMR-5 — The Assembly Fidelity Rule → **CONFIRMED · FILTER MET**

> *"An assembly artifact shall not modify normative wording inherited from an authoritative source. Improvements to inherited normative language must be proposed and disposed, not applied in assembly."*

**⚠️ MISQUOTATION — annotated 2026-07-31 under ISV-F1 (MCA-R1 — where the paraphrase ENTERED). This is NOT the register's authoritative wording.** **The authoritative text reads: *"…must be proposed AGAINST THE AUTHORITATIVE SOURCE and PROPAGATED THROUGH THE ESTABLISHED PUBLICATION CHAIN."*** ***Annotated, not corrected: this record states what this act actually quoted, and rewriting it would erase the evidence of the cascade. The operative baseline (SDM v1.1) carries the authoritative wording.***

**This is the only candidate in the register that clears the strengthened filter as written.**

| Datum | Escape |
|---|---|
| **M8 assembly, Finding 1** | The originating case — assembly modified inherited normative wording |
| **ADR-001's KC-13 / KC-17 cascade** | **One normative clause restated across ELEVEN sites in an assembly artifact, every one requiring correction** |
| **ADR-001 §13** | Duplicated §7's normative text; the remedy was to reduce §13 to a reference |

> ***The eleven-site cascade is the strongest demonstration available: the cost of assembly-time restatement is not one defect — it is one defect PER RESTATEMENT SITE, and each site must be found before it can be fixed.*** **Nine of the eleven were initially miscounted twice by the party correcting them, which is itself evidence of the search cost the rule prevents.**

**VERDICT: CONFIRMED. Repeated operational evidence of escape is on the record.** ***Adoption remains the CDR's decision — a filter met is a gate passed, not an act performed.***

**⚠️ Register-discipline finding raised by this assessment — see §3.2: none of the ADR cascade evidence was ever appended to the PMR-5 entry.** **The strongest candidate in the register was carrying its weakest evidence.**

---

### 2.6 PMR-6 — A per-finding confidence dimension (proposed Rule 15) → **REJECTED**

**Routed as precedent, with MCA-R1 asked to decide nothing. It nonetheless requires a verdict, because the evidence position has CHANGED since the entry was written — and the change strengthens the rejection.**

**The need PMR-6 identified was REAL: the programme has since developed repeated practice of qualifying findings by evidential strength.** *ERV-R1: "DIRECTLY SUPPORTED in all three limbs, none speculative." AIA-R2 reclassified from finding to observation. RET-3 required verification before being called a defect. Verdicts issued as CONFIRMED vs PLAUSIBLE.*

**But the programme met that need with a DIFFERENT INSTRUMENT: a structural distinction (Observation vs Finding; directly-supported vs speculative) rather than a three-point High/Medium/Low scale.**

> ***PMR-6 is therefore rejected on stronger grounds than n = 0: the gap it aimed at has been filled by a construct that does not require a confidence scale — and a scale added now would grade findings that are already sorted by kind.***

**VERDICT: REJECTED.** **Recorded as an APPEND to the register; the original entry is not edited (discipline §4).** *The proposer's own standard — "added only when they repeatedly distinguish meaningful classes of findings that were previously conflated" — is still the operative test, and it is now failed for a second, independent reason.*

---

### 2.7 PMR-7 — Obligations, not words / operational determinism → **CONFIRMED AS TO THE DEFINITION · INSUFFICIENT EVIDENCE AS TO THE CONTROLS**

**The framing constraint is honoured, and the assessment's reasoning does the opposite of breaching it: the two-level vocabulary BOUNDS CI-1 correctly. CI-1 completely protects Level 1 (textual determinism — *"did any editor author text?"*) and was never Level 2's guardian (operational determinism — *"did any editor infer an unstated obligation?"*).** **Level 2 was not nameable in advance; treating an unnameable distinction as a control failure would apply hindsight as a standard.**

**A) The DEFINITION — CONFIRMED.** *The two levels name a real and previously unstateable distinction, and their immediate effect is to state the scope of an existing control precisely rather than to add one. Satisfying Level 1 does not guarantee Level 2 — that sentence was not available before the vocabulary existed.*

**B) The CONTROLS — INSUFFICIENT EVIDENCE, on a ground not previously recorded:**

> ***The two data are ONE DEFECT observed at TWO STAGES, and they support TWO DIFFERENT controls — with n = 1 each.***

| Datum | Stage | The control it supports |
|---|---|---|
| **CCP-R1** | Planning | ***A plan must specify the obligations an edit incurs*** |
| **ERV-R1** | Verification | ***A plan-verification must test for those obligations*** |

**CCP-1 contained the gap; ERV-1 failed to catch it. Neither datum is a second instance of the other's proposition** — *and the input package's own note applies with force here: **acceptance of the evidence is not acceptance of the lesson.***

**VERDICT: CONFIRMED as to the definition; INSUFFICIENT EVIDENCE as to either control.** ***The register entry bundles a definition and two controls under one identifier, which is why it could appear to carry two data for one proposition.*** **Recommended for the CDR's consideration: the entry may need to be split before it can be cleanly disposed — but splitting a register entry is a decision, not an assessment.**

---

## 3. Findings of the assessment about the GOVERNANCE PATH itself

### 3.1 The PMR-1 bypass question — **ANSWERED: not a bypass, but it exposes a real gap**

**The input package required MCA-R1 to record whether a framework repair closing a methodology candidate's gap is a lawful route or an accidental bypass of PMR → MCA → CDR.**

**Determination: NOT A BYPASS.** **FW-1's disposition relocated vocabulary between layers of the KNOWLEDGE-GOVERNANCE FRAMEWORK (Integrity Model · Method · Discipline). It did not touch SDM v1 / EOP v1. The methodology baseline is untouched, so no methodology change occurred and no gate was passed without authority.**

**But the effect overlapped a methodology candidate's scope, and that yields the real finding:**

> ### ⚠️ **The framework and the methodology have OVERLAPPING SCOPE, and no rule states which of them owns finding vocabulary.**

**Consequence, stated plainly: the same substantive improvement can lawfully enter through EITHER path, and the two paths have different evidence gates** — the framework path applies the admission ladder; the methodology path applies PMR → MCA → CDR. ***Where two lawful paths with different gates lead to the same object, the weaker gate governs in practice.***

**MCA-R1 records this and does not resolve it.** *Resolving it would require deciding a boundary between two governed objects, which is neither an assessment nor within this act's authority.*

### 3.2 Register-discipline finding — **the register can silently understate a candidate**

**Discipline §2 requires every PMR to state its observed evidence at entry. Nothing requires LATER evidence to be appended to the entry it supports.**

**Demonstrated by PMR-5: its evidence grew from one case (M8 Finding 1) to three — including the eleven-site ADR cascade — and none of the growth reached the entry, because it was recorded in the ADR review instead.** ***The register's strongest candidate was presented to this assessment carrying its weakest evidence, and only cross-reading the corpus recovered the rest.***

**Recorded, not remedied.** *A fix would be a register-discipline change, which is itself subject to the path this act sits inside.*

### 3.3 The filter's pass rate — recorded as a fact, with both readings stated

**One candidate of seven clears the strengthened filter as written.**

| Reading | Argument |
|---|---|
| **The filter is working** | It was strengthened *precisely* to prevent control accretion. A register in which most proposals pass is not being filtered |
| **The filter may be narrow** | **PMR-1 failed BECAUSE the practice it would codify has been performed correctly.** *A filter that rejects a control on the grounds that nothing has yet gone wrong cannot distinguish "unnecessary" from "not yet tested"* |

***Both readings are on the record; MCA-R1 adopts neither. Choosing between them would revise the filter, and the filter is part of the framework this assessment operates under — not part of the material it assesses.***

---

## 4. Assessment summary

| # | Candidate | **MCA-R1 verdict** | Discharge condition, where one exists |
|---|---|---|---|
| **PMR-1** | Finding-class taxonomy | **INSUFFICIENT EVIDENCE** *(partially discharged; residue = routing classes only)* | Evidence of an actual mis-routing |
| **PMR-2** | Composite confidence non-inheritance | **CONFIRMED as a defect class · INSUFFICIENT EVIDENCE for promotion** | **Verify AFV-1's four over-strengthenings — one verification decides it** |
| **PMR-3** | Capabilities vs situational controls | **INSUFFICIENT EVIDENCE (structural)** · **DEFER recommended** | **n ≥ 2 simpler contexts — a DATA condition, not a date** |
| **PMR-4** | Elevate the Strategic/Tactical boundary | **CONFIRMED** *(precedence consequence UNASSESSED)* | — |
| **PMR-5** | Assembly Fidelity Rule | **✅ CONFIRMED — FILTER MET** *(M8 Finding 1 · the eleven-site ADR cascade · §13's duplication)* | — |
| **PMR-6** | Per-finding confidence dimension | **REJECTED** *(now on stronger grounds than n = 0 — superseded by Observations/Findings)* | — |
| **PMR-7** | Obligations not words / operational determinism | **CONFIRMED as to the DEFINITION · INSUFFICIENT EVIDENCE as to the CONTROLS** *(one defect at two stages → two controls at n = 1 each)* | A second instance **per control**, separately |

**Nothing is adopted. SDM v1 / EOP v1 remain operative in full.**

**What the CDR now has to decide, and this assessment deliberately leaves all of it open:** whether to adopt PMR-5 · whether PMR-4's confirmed status warrants a core-principle label given its untested precedence · whether to adopt PMR-1's narrowed residue or decline it · whether to split PMR-7 · whether PMR-3's deferral takes a data condition · and whether the framework/methodology scope overlap in §3.1 requires its own act.

---

*Traceability: **MCA-R1 ASSESSMENT** (Authority instruction, 2026-07-30) against `PKS_Phase_II_MCA_Input_Package_PMR1-7.md` · verdict vocabulary per register discipline §3; **nothing adopted — adoption is CDR-class** · **filter applicability differentiated by candidate kind (control · vocabulary · taxonomy · status elevation) BEFORE any candidate was assessed** · **2 confirmed (PMR-4, PMR-5) · 1 confirmed-in-part (PMR-7, definition only) · 1 rejected (PMR-6) · 3 insufficient (PMR-1, PMR-2, PMR-3)** · **PMR-1 fails because its own evidence — consistent routing — is evidence of NON-escape** · **PMR-5 is the sole candidate meeting the strengthened filter, on the eleven-site cascade** · **PMR-6's rejection strengthened: the gap was filled by a structural distinction, not a scale** · **PMR-7's two data resolved as ONE defect at TWO stages supporting TWO controls at n=1 each; framing constraint honoured — the vocabulary BOUNDS CI-1 rather than faulting it** · **§3.1 bypass question ANSWERED (not a bypass) and the underlying gap RECORDED: framework and methodology have overlapping scope, no rule assigns ownership of finding vocabulary, and where two lawful paths have different gates the weaker gate governs in practice** · **§3.2 register can silently understate a candidate — PMR-5 carried its weakest evidence** · **§3.3 pass rate 1-of-7 recorded with both readings, neither adopted** · SDM v1 / EOP v1 unchanged.*

---

## MCA-R1 **CLOSED** (Authority act, 2026-07-31)

**Closed on the Authority's own ground:** *"I agree this is ceremonial rather than gating. **But ceremonies matter because they make governance state explicit.**"*

| | |
|---|---|
| **Substantive completeness** | Verified at the CDR-R1 readiness check: all seven candidates assessed, every verdict and discharge condition delivered, no question directed back at the assessment |
| **Consumed by** | **CDR-R1** (`PKS_Phase_II_CDR_R1_Decision.md`, issued 2026-07-31) |
| **Verdicts** | **Unchanged by closure.** *Closure records that the assessment is finished; it does not ratify or revise a single verdict* |
| **PMR-8** | **Untouched. Still unassessed, and expressly out of scope for both MCA-R1 and CDR-R1** |
| **Status** | **CLOSED** |

***Recorded because the readiness check found its absence: MCA-R1 was the only completed instrument in the corpus without an explicit closure statement, while every closed review carried one. A governance state that must be inferred is not explicit, however obvious the inference.***

**This closure was RESERVED to the Authority by the readiness verification and NOT self-issued.** *An assessment that closed itself would be the same defect class as one that certified itself.*
