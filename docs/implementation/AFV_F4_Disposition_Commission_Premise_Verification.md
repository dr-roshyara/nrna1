# AFV-F4 Disposition Commission — **PREMISE VERIFICATION** *(the disposition is NOT issued, and why)*

| | |
|---|---|
| **Commission** | *"Execute AFV-F4 Authority Disposition. Decide ACCEPT / REJECT / DEFER."* (2026-07-31) |
| **⛔ Outcome** | **THE DISPOSITION IS NOT ISSUED. AFV-F4 WAS ALREADY DISPOSED ON 2026-07-30, AND ITS ENTIRE DOWNSTREAM QUEUE HAS EXECUTED.** |
| **Why this record exists instead** | *Producing a document titled `AFV_F4_Disposition_Decision.md` would create a SECOND disposition of one finding. This record is named for what it is* |
| **Act performed** | **Premise verification only.** *No disposition, no edit, no promotion, no re-opening* |

---

## 1. The commission's premise, and the evidence against it

**The commission's Constitutional Context asserts: *"AFV-F4 · ⏳ Pending — Authority disposition"*, with *"YOU ARE HERE"* placed before Packages A+B.**

| Verified fact | Source |
|---|---|
| **AFV-F4 was DISPOSED — decision ACCEPT** | `PKS_Phase_II_Execution_Record.md` §154: *"**AFV-F4 AUTHORITY DISPOSITION + CONTROLLED EXECUTION (2026-07-30)** … Issued by the Authority (DA / PA / ARB Chair), 2026-07-30 — **'Dispose AFV-F4 — accept, proceed with the execution queue.'** **DECISION: ACCEPT**"* |
| **The full downstream queue EXECUTED** | Execution Record maintained header: *"🏁 **EXECUTION COMPLETE AND CLOSED.** All packages executed — Amendment 1 · Package E (C-16) · **Package A+B · C-18 · Package C · Package D**"* |
| **The artifacts the queue was to promote ARE PROMOTED** | AD-1 · C4-1 · AFV-1 · M7 · M8 · RET-1 — **six promotion acts, 2026-07-30** |
| **The asymmetry was already acknowledged in the executed disposition** | *"What ACCEPT means here, stated because AIA-F1 warned that the two outcomes are not symmetric: **ACCEPT is the NARROW act**"* |
| **The conditional change items resolved correctly** | *"C-01 and C-14 (both `Conditional — ACCEPT only`) become executable; C-17 (`REJECT only`) is not applicable and was not executed"* |

> ## **The premise is DISCHARGED, not merely stale. Every gate the commission places downstream of the decision has been passed.**

---

## 2. Why re-issuing would be a governance defect, not a harmless duplication

| | |
|---|---|
| **Two dispositions of one finding** | ***Nothing would establish which governs.*** *A finding with two dispositions is worse than one with none: the second creates ambiguity where the first created certainty* |
| **Implicit re-opening of a promoted baseline** | **AD-1 and C4-1 are PROMOTED under change control.** *A fresh disposition of the finding that gated their correction reaches behind a promotion without a change-control act* |
| **Destruction of a load-bearing record** | ***The 2026-07-30 disposition is the EXPLANATION of why Packages A+B, C-18 and C executed.*** *Under the programme's archival principle, an artifact that explains a later act must remain as it was when that act was taken* |
| **The conditional items are already spent** | *C-01 and C-14 executed on ACCEPT; C-17 was correctly not executed. A second decision has nothing left to release* |

---

## 3. ⚠️ But the commission is NOT redundant — it carries ONE genuinely new proposition

***This is the finding that matters, and it would have been lost by simply reporting "already done."***

**The executed disposition qualified AR-1's placement with *"the contingency the model already states."*** **The new commission proposes a DIFFERENT rationale:**

| Executed 2026-07-30 | Proposed by this commission |
|---|---|
| AR-1's placement qualified by **the model's own recorded contingency** | AR-1 is inside the boundary **because *"the repository is the PKS domain"*** — with `./docs/`, `./engineering/`, `./app/`, `./.claude/` in and **`./architecture/` excluded** |

> ### **A repository-scope determination has never been decided by any Authority act on the record. It is not AFV-F4's disposition, and it cannot be issued as one.**

**Why the distinction is constitutional, not pedantic** *(Authority refinement, 2026-07-31)*:

> **The repository-scope rationale has materially BROADER CONSTITUTIONAL IMPLICATIONS than the executed disposition, because it determines REPOSITORY MEMBERSHIP rather than relying on a contingency already present in the model. As such it EXCEEDS THE SCOPE of AFV-F4 and requires independent Authority consideration.**

**Superseded, retained:** ~~*"That is closer to the REJECT-side breadth the commission's own asymmetry warning exists to prevent."*~~ ***Withdrawn as imprecise: it associated a scope question with one of the three disposition OUTCOMES. Breadth of constitutional implication and choice among ACCEPT/REJECT/DEFER are different axes — a rationale can exceed a finding's scope while supporting any of the three.***

**Recorded, not commissioned:** *if the Authority wants a repository-scope determination — and the `./architecture/` exclusion in particular — it requires its own commission, its own evidence, and its own "what is NOT decided" section.* **It also could not be applied to AD-1 or C4-1 without a change-control act against promoted artifacts.**

---

## 4. ⭐ This commission is ER-F1's demonstrated consequence

**The commission's Constitutional Context table reproduces, nearly verbatim, the Execution Record's SUPERSEDED Segment-1 status** — the text now labelled *"SUPERSEDED, retained verbatim (as at 2026-07-28, describing SEGMENT 1 only)."*

**ER-F1 (Moderate, accepted 2026-07-31) found exactly this risk: a header describing the first segment's world while four segments followed. Its remedy was to maintain the header and retain the superseded text with an as-at marker.**

> ### ***A commission premised on a discharged state is what an unqualified stale header produces downstream.***

**⚠️ Precision recorded at the Authority's instruction: this is an OBSERVATION ABOUT GOVERNANCE PROCESS, NOT evidence that ER-F1 was incorrectly classified.** *ER-F1 was rated Moderate on the risk, and a risk occurring once does not retroactively re-rank the finding that named it.* ***A severity is assigned on the evidence available at assessment; later realization is a datum about the process, not a correction of the rating.***

**Recorded as vindication of the remediation, and of the reachability principle** *(candidate, n=3)*: **the maintained header now states execution is complete, and the superseded row carries its as-at scope — so the next reader of that record cannot repeat this.**

---

## 5. The commission-design refinements — **all four ADOPTED for future commissions**

**The Authority's own review of its own commission, adopted in full. Recorded here because each one would have improved this commission and applies to every future one.**

| # | Refinement | Assessment |
|---|---|---|
| **1** | **Recommendations must be CONDITIONED on evidence, not embed factual assertions** — *"ACCEPT, **provided** the repository evidence supports the stated repository context…"* | ✅ **The most important of the four, and §3 is its proof: the embedded assertions (*"PublicDigit and PKS are the same project"*, *"the repository is the PKS domain"*) are exactly the unverified facts that would have been adopted by acting on the recommendation** |
| **2** | **Repository cleanup belongs OUTSIDE the disposition** — a follow-up governance action, not embedded operational work | ✅ **Adopted. *A disposition that carries operational instructions makes execution a consequence of deciding, which is the collapse the programme separates most carefully*** |
| **3** | **Soften the asymmetry to something to VERIFY** — *"the Authority should consider whether…"* rather than *"REJECT effectively adopts the one-corpus reading"* | ✅ **Adopted — and note the executed disposition already met the stricter standard: it stated the asymmetry as AIA-F1's warning, attributed, rather than as its own conclusion** |
| **4** | **Mandatory EVIDENCE VERIFICATION section before Decision** | ✅ **Adopted, and this act is its first application: verifying the premise came before deciding, and it changed the outcome from a disposition to a refusal to dispose** |

**⚠️ Relationship to the register, noted without minting:** *refinement 4 is the disposition-side sibling of **PMR-9** (verification before classification, for reviews). **Not raised as a candidate** — the Authority stated these as refinements to a commission, not as rules for all future commissions, and under Amendment 4 that is recognition. Recorded so a future assessment can decide whether the two share one proposition.*

---

## 5A. The outcome was NOT one of the three offered *(Authority observation, 2026-07-31)*

**The commission offered ACCEPT · REJECT · DEFER. The correct outcome was none of them:**

> ### **No disposition is constitutionally available, because the requested Authority act has already been performed.**

***That is a legitimate governance outcome in its own right — and the disposition vocabulary contains no term for it, exactly as the review-status vocabulary contains no term for "object absent" (IBC-1 re-certification §2). Both vocabularies presume the act they describe is POSSIBLE; neither can express the finding that it is not.***

**Reported, not minted. Two gaps of one shape in two days is a pattern worth watching, not a pattern established.**

**And the evidentiary basis of this act is stated plainly, per the Authority's confidence separation: every factual claim above — the disposition's existence and decision, the packages' execution, the artifacts' promotion — is quoted directly from the repository, not inferred.** *The governance reasoning is mine; the facts are the record's.*

## 6. What this act did and did not do

| Did | Did NOT |
|---|---|
| Verified the commission's premise against the record | **Issue a disposition** |
| Located and cited the executed disposition | **Edit AD-1, C4-1, or any artifact** |
| Identified one genuinely new proposition (§3) | **Decide the repository scope or the `./architecture/` exclusion** |
| Adopted four commission-design refinements | **Promote anything · execute any package · re-open any finding** |
| Recorded ER-F1's realized consequence | **Commission the repository cleanup** |

**Governance state · certification state · promotion state: UNCHANGED. OQ-PKS-7, CBC-3's candidate-seam status, seam promotion and partition re-entry all remain exactly as the 2026-07-30 disposition left them — untouched by this act.**

---

*Traceability: **AFV-F4 disposition commission — PREMISE VERIFICATION ONLY; the disposition is NOT issued** · **AFV-F4 was DISPOSED 2026-07-30 (decision ACCEPT, Execution Record §154) and the full queue — Package A+B · C-18 · Package C · Package D — has EXECUTED, with six artifacts PROMOTED** · **re-issuing would create two dispositions of one finding with nothing establishing which governs, reach behind a promotion without change control, and destroy a load-bearing record** · **⚠️ §3: the commission is NOT redundant — it proposes a DIFFERENT rationale (a repository-scope determination excluding `./architecture/`) which no Authority act has ever decided and which is BROADER than the executed narrow ACCEPT; it requires its own commission and could not touch promoted AD-1/C4-1 without change control** · **⭐ §4: this commission reproduces the Execution Record's SUPERSEDED Segment-1 status — it is ER-F1's risk REALIZED, and vindicates both the remediation and the reachability principle** · **§5: all four Authority commission-design refinements ADOPTED; refinement 4 (evidence verification before decision) had its first application in this very act and changed the outcome; its relationship to PMR-9 noted without minting a candidate** · nothing edited, promoted, decided or commissioned; OQ-PKS-7 · CBC-3 · seam promotion · partition re-entry untouched.*
