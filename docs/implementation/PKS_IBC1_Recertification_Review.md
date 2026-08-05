# PKS IBC-1 — **Architecture Review RE-CERTIFICATION against the current governed baseline**

| | |
|---|---|
| **Object** | `PKS_IBC-1_Architecture_Review.md` — **the review, not IBC-1** |
| **Governing question** *(the Authority's, verbatim)* | ***Does the existing IBC-1 Architecture Review faithfully evaluate the Implementation Boundary Contract against the current governed baseline, without preserving findings that have been superseded by later Authority decisions, governance refinements, or editorial applications?*** |
| **Family / emphasis** | **Consequence family · AUTHORITY-IMPACT emphasis** *(AIA-1's existing slot — it traces earlier findings against later Authority acts, which is precisely this act)*. **Count remains TEN. No new family, emphasis or category minted.** |
| **Status** | **RE-CERTIFICATION DELIVERED — and it is CONDITIONAL for a structural reason (§2). Nothing applied, disposed, or reopened.** |

---

## 1. EXECUTIVE SUMMARY

> ## ⚠️ **The re-certification cannot be completed as specified, and the reason is not a limitation of effort — it is that THE OBJECT OF THE ORIGINAL REVIEW IS NOT IN THE REPOSITORY.**

**The review's own header states it: *"Object of review | IBC-1 Sections 1–13 **as presented inline in the review request**."*** **A repository search for IBC-1 returns exactly one file — this review.** ***IBC-1 itself has no artifact.***

**Consequence, stated before any finding is touched:**

| Can be re-certified | **Cannot be re-certified** |
|---|---|
| **Whether each finding's GOVERNING BASIS still holds** — the standards are all in the repository | **Whether each finding still APPLIES to IBC-1** — *the text it was made against is gone* |

> ### ***Every status below is therefore CONDITIONAL: "if IBC-1's text is unchanged since 2026-07-28, then…". No status is unconditional, and none can be.***

**Headline results:**

1. **ZERO findings should be retired as *wrong*. TWO are SUPERSEDED BY EXECUTION** — retired because the world moved to the state the contract described, not because the finding erred.
2. **THREE are STRENGTHENED** by later acts.
3. **ONE new finding against the review** (§6) — its §8.2 reproducibility claim is false.
4. **The review had ALREADY performed the commission's boundary check 3** (architecture vs transmission) on 2026-07-28, reaching **zero architecture defects**. §4.

---

## 2. CONSTITUTIONAL SCOPE — why the object's absence is the governing fact

**§8.2 of the review classifies findings by origin: 6 *artifact-local* · 15 *cross-artifact* · 0 *reviewer knowledge*, and concludes: *"the review is reproducible by anyone with repository access."***

**That classification measures where the STANDARD lives. It does not measure where the OBJECT lives — and for reproducibility the object is binding.**

| | |
|---|---|
| **All 21 findings are findings about IBC-1's CONTENT** | *"omits X" · "declares Y" · "does not name Z"* |
| **IBC-1's content is not in the repository** | So **no** finding — artifact-local or cross-artifact — is checkable against its object by repository access |
| **What repository access DOES permit** | Verifying whether the **governing standard** each finding cites still holds |

***The distinction matters constitutionally: a finding whose standard changed is SUPERSEDED; a finding whose object is missing is INDETERMINATE. Those are different states, and the commission's vocabulary (confirmed · superseded · dissolved · narrowed · strengthened) contains no term for the second. That gap is reported, not filled — minting a status would breach the terminology discipline.***

---

## 3. VERIFICATION MATRIX — findings on which later Authority acts bear

*Coverage declared honestly: the eight findings below are those where a later Authority act, editorial application, or promotion changes the analysis. The remaining thirteen have no later act bearing on their governing basis and are recorded as **basis unchanged** (§3.9). All statuses are conditional per §1.*

### 3.1 **IBC-C1** — *AD-1 and C4-1 declared "certified baseline" while carrying unapplied corrections; AD-1 is Governance Review Required*

| | |
|---|---|
| **Current evidence** | **AD-1 and C4-1 are both PROMOTED (2026-07-30).** AFV-F4 was disposed; Packages A+B and C were applied; the *Governance Review Required* classification is discharged |
| **Status** | ✅ **RESOLVED — and resolved exactly as the review predicted.** *§7.2 recorded the resolving act as "resolves automatically once the disposition is taken and packages applied." It was, and it did* |
| **Disposition** | **RETIRE.** *A finding that names its own resolving condition and whose condition then occurs is discharged by the record, not by a reviewer's judgment* |

### 3.2 **IBC-C3** — *"AD-1 governs on conflict" inverts the record's rule that the decision record governs until applications are made*

| | |
|---|---|
| **Current evidence** | **The applications HAVE been made.** Packages A+B applied; AD-1 promoted |
| **Status** | ✅ **SUPERSEDED BY EXECUTION** |
| **Reasoning** | ***The finding was correct when made. Its premise was a condition — "until applications are made" — and that condition has since been satisfied, so the contract's statement is now the accurate one.*** |
| **Disposition** | **RETIRE as superseded, NOT as dissolved.** ***The distinction is load-bearing: dissolved means the reasoning failed; superseded means the world moved. Recording this as dissolved would misattribute a correct finding as an error*** |

### 3.3 **IBC-M1** — *Certified Baseline omits DAR-1, the M6 record set, the Consolidation record, the CDR, and the verification records*

| | |
|---|---|
| **Current evidence** | Every omitted artifact still exists and still governs — **and the baseline has since GROWN**: AFV-1, AIA-1, KBI-1, ERV-1, CCP-1+§11, **SDM v1.1 / EOP v1.1**, CDR-R1 |
| **Status** | ⬆️ **CONFIRMED and STRENGTHENED.** *The omission is now larger than when recorded* |

### 3.4 **IBC-M3** — *"Certified" used for boundaries, conflating it with Provisionally Certified; boundaries are ACCEPTED*

| | |
|---|---|
| **Current evidence** | Boundaries remain **ACCEPTED**; the methodology remains **PROVISIONALLY CERTIFIED**. **And a THIRD status now exists that did not on 2026-07-28: PROMOTED** |
| **Status** | ⬆️ **CONFIRMED and STRENGTHENED.** *There is now one more status available to conflate, and the vocabulary IBC-1 used contains none of the three correctly* |

### 3.5 **IBC-M4** — *The three contested memberships are never named, so FA-6 is unconformable* ⚠️ **cross-links an open finding**

| | |
|---|---|
| **Current evidence** | The three (Risk · Question · Exception record) remain **unassigned** in the governed record |
| **Status** | ✅ **CONFIRMED** — ⛔ *corrected 2026-07-31 from* ~~*"CONFIRMED and STRENGTHENED"*~~ **under the Authority's classification ladder: STRENGTHENED requires NEW INDEPENDENT EVIDENCE for THIS claim, and CON-F2 is evidence about **M7's** content, not IBC-1's. It shows a cross-artifact PATTERN, not added support for this finding.** ***Treating a cross-case pattern as support for one case is the second failure mode the ladder exists to prevent*** |
| **⚠️ Recorded as an OBSERVATION, not absorbed** | **CON-F2 (2026-07-31) independently found that these same three memberships appear NOWHERE in promoted M7** — zero occurrences. ***IBC-M4 and CON-F2 are the same underlying fact observed at two different points in the chain, three days apart, by reviews that did not know of each other: the contested memberships are not being carried forward.*** **CON-F2 is open under commission M7-CF2 and is NOT adjudicated here — a review's scope is determined by its commission, not by everything it can observe** |

### 3.6 **IBC-M7** — *"Verdicts | PKS" hides that AFV-F1 found the issuing act's placement undetermined*

| | |
|---|---|
| **Current evidence** | **AFV-1 is PROMOTED.** *Its findings AFV-F1..F5 were disposed; AFV-F4 was the Major* |
| **Status** | ⚠️ **BASIS PARTLY CHANGED — and NOT classified, because classification would require verifying AFV-F1's disposition outcome, which this act did not do.** ***Recorded as requiring verification rather than assigned a status: the programme's own rule is that verification precedes classification, and asserting a status here would be exactly the error CON-F1 made*** |

### 3.7 **IBC-M11** — *Five of eight dependency rules absent, including DR-8*

| | |
|---|---|
| **Current evidence** | **DR-7 was restated as a reopening trigger under AD-R1 and marked NOT DERIVED.** *So one of the five named rules has materially changed since* |
| **Status** | ↔️ **NARROOWED.** *The finding was already reclassified Major → Recommendation by the review's own §6.2, and one of its five instances now has a different character* |

### 3.8 **IBC-S1** — *IBC-1 lacks the provenance apparatus carried by every other baseline artifact*

| | |
|---|---|
| **Current evidence** | ⬆️ **STRENGTHENED, and it is now the most consequential item in the set** — *because §2 establishes that the missing apparatus includes the artifact itself* |
| **Reasoning** | ***A contract with no header, no traceability and no Disposition History is, in the limit, a contract with no repository presence — which is exactly what was found. IBC-S1 was classified a "Suggestion"; the re-certification finds it names the condition that makes every other finding unre-verifiable*** |
| **Disposition recommended** | **Re-rank.** *Not because the finding changed, but because its consequence is now demonstrated* |

### 3.9 The remaining thirteen — **basis unchanged**

**IBC-C2 · M2 · M5 · M6 · M8 · M9 · M10 · m1 · m2 · m3 · m4 · m5 · S2: no later Authority act, editorial application or promotion bears on the governing standard each cites.** *Their governing bases hold as recorded. Their application to IBC-1 remains unverifiable per §2.*

---

## 4. Boundary check 3 was ALREADY PERFORMED — by the review itself

**The commission requires: *"architecture defects ≠ transmission defects. Verify every finding again under that distinction."***

**The review performed exactly that on 2026-07-28, in §7.1:** *"Classifying all 21 findings produces: **0 architecture defects** · 12 governance defects · 1 methodology observation · 6 improvement recommendations · 1 question."*

> ***"Nothing in IBC-1 asserts wrong architecture. Every defect is a failure of TRANSMISSION — the contract misstates the baseline's status, omits recorded content, or states as settled what the record left open."***

**Re-certified: the distinction holds against the current baseline, and no finding re-examined above changes category.** **The two retirements (C1, C3) were transmission defects that transmission-side events resolved.** ***A set of findings that contains zero architecture defects cannot be invalidated by architectural change — which is why the baseline's substantial evolution retired only two of twenty-one.***

---

## 5. Authority-boundary and DDD checks

| Check | Result |
|---|---|
| **Authority boundary** — does every criticism reflect post-MCA/CDR/AFV state? | ✅ **PASS.** The review's *"Standard of review"* row lists MCA · CDR · AFV-1 · AIA-1 · KBI-1 · ERV-1 · CCP-1+§11 explicitly. *It was written against the post-CDR baseline, which is why so little of it lapsed* |
| **Decision traceability** | ✅ **PASS.** §7.2 gives every finding a Rule 8 authority basis and a Rule 11 resolving act |
| **DDD boundary** — does it redesign contexts, relationships, UL, ownership or governance? | ✅ **PASS. It redesigns nothing.** *Every finding is of the form "the contract omits / misstates / does not name X" — a transmission complaint, never a substitution of the reviewer's model* |
| **Scope discipline** | ✅ **PASS.** §8.1 declares the review scope and scopes the verdict to it |
| **Terminology** | ✅ **PASS.** *Its central Major (M3) is itself a terminology-freeze complaint* |

---

## 6. **IBC-REC-F1 — MODERATE** *(new; against the review, not IBC-1)*

**§8.2 concludes: *"Zero findings rest on reviewer knowledge. Every one cites a governed artifact, so **the review is reproducible by anyone with repository access** — which is the property Rule 16 exists to establish, and it holds."***

⛔ **That claim is FALSE, and it is falsified by the review's own header.** **The object — *"IBC-1 Sections 1–13 as presented inline in the review request"* — is not in the repository. A reader with repository access can verify every STANDARD and no single FINDING.**

**⚠️ THE DEFECT RESTATED (Authority refinement, 2026-07-31 — adopted in place of the reviewer's narrower framing): the problem is NOT merely that the artifact is absent. It is that §8.2 CONFLATES TWO DISTINCT REPRODUCIBILITY PROPERTIES.**

| Property | Definition | §8.2's standing |
|---|---|---|
| **REASONING reproducibility** | *Another reviewer can inspect every governing source and understand **why** the conclusions were reached* | ✅ **ESTABLISHED — and genuinely so** |
| **APPLICATION reproducibility** | *Another reviewer can inspect the **reviewed artifact** and independently determine whether the conclusions still follow* | ⛔ **NOT ESTABLISHED, and not establishable from the repository** |

> ### ***That distinction is the real architectural lesson: the origin taxonomy is sound, and the inference drawn from it treats reasoning reproducibility as if it entailed application reproducibility. It does not — a finding can be fully explicable and wholly uncheckable at the same time.***

**Superseded framing, retained:** ~~*"the problem is that the object is absent"*~~ — **true but narrower; it describes THIS case rather than the property that failed, and a rule drawn from it would not generalize to an object that is present but altered.**

**Remedy: annotation. The review is frozen historical evidence, and its §8.2 analysis is correct except in its final inference.** **Recommended annotation text: *the review is reproducible in its STANDARDS and not in its OBJECT, which is absent from the repository.***

**Recorded with credit where due: §8.2 came closer than any other section of the corpus to finding this — it identified that *"only 6 of 21 findings are artifact-local"* and drew a real consequence for Section 11. It stopped one step short of asking whether the artifact was available at all.**

---

## 7. Dissolved · Superseded · Observations

| | |
|---|---|
| **DISSOLVED (reasoning failed)** | **NONE.** *No finding was retired for error* |
| **SUPERSEDED (world moved)** | **IBC-C1** (resolved by promotion, as predicted) · **IBC-C3** (premise condition satisfied) |
| **STRENGTHENED** | **IBC-M1 · M3 · M4** *(and IBC-S1 re-ranked)* |
| **NARROWED** | **IBC-M11** |
| **REQUIRING VERIFICATION, not classified** | **IBC-M7** |
| **Observations** | **IBC-M4 ↔ CON-F2 are the same fact at two chain points** *(not absorbed)* · **the commission's five-status vocabulary lacks a term for "object absent"** *(reported, not filled)* |

---

## 8. FINAL VERDICT

> ## **The review REMAINS CONSTITUTIONALLY CORRECT. 19 of 21 findings retain their governing basis; 2 are superseded by execution; 0 are dissolved. ONE new Moderate against the review (§6).**
>
> ### ⚠️ **But the re-certification is CONDITIONAL, and the condition cannot be discharged from the repository: IBC-1 has no artifact.**

**The most consequential recommendation is therefore not about any finding:**

> **No remediation or conformance assessment of IBC-1 can be COMPLETED unless there is a governed instance of IBC-1 against which those activities are performed.**

*(Authority phrasing, 2026-07-31, adopted in place of* ~~*"IBC-1 must EXIST as a governed artifact"*~~*.)* ***The softer form states a PREREQUISITE without implying that creating the artifact is itself commissioned work — which the stronger form did by implication, and which no act has authorized.***

**The underlying fact is unchanged: every remediation of a 21-finding review presupposes a text to remediate, and there is none.**

**Recorded, not commissioned.** *Whether to commission IBC-1's establishment is the Authority's; a review's scope is determined by its commission, not by everything it can observe.*

---

*Traceability: **IBC-1 Architecture Review RE-CERTIFICATION** (Authority commission, 2026-07-31) · **consequence family · authority-impact emphasis** (AIA-1's existing slot); no family, emphasis or category minted · **⚠️ STRUCTURAL RESULT: the object of the original review — "IBC-1 Sections 1–13 as presented inline in the review request" — IS NOT IN THE REPOSITORY, so every status is CONDITIONAL on IBC-1's text being unchanged; governing BASES are re-certifiable, APPLICATION is not** · the commission's five-status vocabulary lacks a term for *object absent*; **gap reported, not filled** · **19 of 21 bases hold · IBC-C1 RESOLVED exactly as its own §7.2 predicted · IBC-C3 SUPERSEDED BY EXECUTION (premise condition satisfied) — retired as superseded and NOT dissolved, because *dissolved means the reasoning failed while superseded means the world moved* · M1/M3/M4 STRENGTHENED · M11 NARROWED · M7 recorded as REQUIRING VERIFICATION rather than classified, per verification-before-classification · IBC-S1 re-ranked because it names the condition that makes every other finding unre-verifiable · ZERO findings dissolved** · **boundary check 3 was already performed by the review itself on 2026-07-28 (zero architecture defects) and re-certifies — *a finding set containing zero architecture defects cannot be invalidated by architectural change, which is why substantial baseline evolution retired only two of twenty-one*** · **IBC-REC-F1 (Moderate, against the review): §8.2's claim that "the review is reproducible by anyone with repository access" is FALSE — citing a governed standard makes a finding AUDITABLE in reasoning and leaves it UNREPRODUCIBLE in application, and §8.2 collapsed the two; credit recorded for coming one step short** · **IBC-M4 ↔ CON-F2 recorded as the same fact at two chain points, NOT absorbed (M7-CF2 owns it)** · **recommendation recorded not commissioned: IBC-1 must EXIST as a governed artifact before any remediation of a 21-finding review can proceed.***

---

## 9. IBC-REC-F1 and CDR-O1 DISPOSED — **ACCEPT both** (Authority act, 2026-07-31)

### 9.1 **IBC-REC-F1 — ACCEPT** · remedy **ANNOTATION**

| | |
|---|---|
| **Artifact class** | **Frozen historical evidence** — an executed review, never amended |
| **Remedy** | **ANNOTATION adjacent to §8.2's conclusion**, where a reader meets the false claim. **§8.2's origin taxonomy is NOT altered — it is sound** |
| **Applied** | The annotation records the two-property distinction and that only the first was established |

**Operationalized in the Authority's terms, because these are the two questions a later reviewer actually asks:**

| Question | Still answerable from the record? |
|---|---|
| ***"Why did the reviewer reach this conclusion?"*** | ✅ **YES** — reasoning reproducibility holds |
| ***"Would I reach the same conclusion from the reviewed artifact?"*** | ⛔ **NO** — application reproducibility does not |

### 9.2 **CDR-O1 — ACCEPT as an observation; CARRIED, not remediated**

**The CDR's *"working name"* stands. No remediation — *an observation is never remediated*.** **CDR-1 closes with no finding against its artifact and one carried observation.**

### 9.3 Boundaries

**Nothing else changes: IBC-1 is not created · the CDR is not amended · CON-F2 stays with M7-CF2 · certification and governance state unchanged · no methodology moved, and no candidate was raised by either disposition.**

---

## 10. **PRECONDITION OF REVIEWABILITY** — the result the Authority identifies as the most fundamental

> ### **A review is reproducible only if BOTH its governing sources AND its reviewed object remain available.**
>
> **Governing sources alone are insufficient. The reviewed object alone is insufficient.**

**This is a statement of FACT about review evidence, not a prescription about conduct — which is why it is recorded directly and raises no methodology candidate.**

### 10.1 The structural split, in the Authority's stronger wording

**Superseded** *(retained)*: ~~*"the governing basis is re-certifiable; the reviewed object is not"*~~ — a repository fact.

> ### **The review's NORMATIVE BASIS remains independently verifiable, while its APPLICATION EVIDENCE is no longer independently reproducible.**

***The stronger form aligns the structural split with the reproducibility distinction that explains it, rather than leaving them as two separate observations about the same failure.***

### 10.2 The subtle error this ordering prevented — **named by the Authority**

> ### ***Treating the continued existence of the REVIEW DOCUMENT as evidence that the REVIEWED OBJECT was still available.***

**That inference is invisible from the governing sources: a reviewer starting from them would have verified twenty-one standards, found nineteen intact, and published a confident re-certification of findings checkable against nothing.**

**Precise formulation** *(Authority refinement, 2026-07-31, adopted — the reviewer's version was too absolute and understated the evidentiary value of historical review records)*:

> ### **A surviving review IS evidence that the review occurred and records the reasoning applied at that time. It is NOT, by itself, evidence that the reviewed object remains available, or that the review's conclusions remain independently reproducible.**

**Superseded, retained:** ~~*"A surviving review is evidence that a review happened, and evidence of nothing else."*~~ ***Withdrawn: a surviving review is also evidence of what was reviewed, what conclusions were reached, and what reasoning was recorded — which is precisely why 19 of 21 governing bases could be re-certified at all. The absolute form would have made this act impossible to justify.***

***Evidence governs classification — not continuity of documentation.***

### 10.3 ⭐ A SECOND vocabulary gap, of the same shape *(Authority observation, 2026-07-31)*

**The AFV-F4 commission offered three outcomes — ACCEPT · REJECT · DEFER. The correct outcome was none of them:**

> ### **No disposition is constitutionally available, because the requested Authority act has already been performed.**

**That is a legitimate governance outcome in its own right, and the disposition vocabulary has no term for it.**

| Vocabulary | Gap found | Shape of the gap |
|---|---|---|
| **Review statuses** *(confirmed · superseded · dissolved · narrowed · strengthened)* | no term for **object absent** | *presumes the object exists* |
| **Disposition outcomes** *(accept · reject · defer)* | no term for **act already performed** | *presumes a decision is still available* |

> ### ***Both vocabularies presume that the act they describe is POSSIBLE. Neither can express the finding that it is not.***

**Both gaps are REPORTED and NEITHER is minted, on the same admission condition: evidence from multiple cases, not a single instance.** ***And the symmetry is itself only one datum — two gaps of one shape found in two days is a pattern worth watching and not yet a pattern established.***

### 10.3 The missing status — condition for its future admission

**The taxonomy has no term for *object unavailable*, and none was minted.** **Authority determination recorded: *if a future methodology commission concludes it deserves first-class status, it can introduce one with evidence from MULTIPLE CASES rather than this single instance.***

***That is the admission ladder applied to a status rather than a control — and the same threshold: one case is an observation, repetition is a candidate.***

---

*Disposition traceability: **IBC-REC-F1 + CDR-O1 ACCEPTED** (Authority, 2026-07-31) · **IBC-REC-F1 remedied by ANNOTATION adjacent to §8.2's conclusion; the origin taxonomy is NOT altered because it is sound — only the inference drawn from it failed** · operationalized as the two questions a later reviewer asks: *"why did the reviewer conclude this?"* (answerable) vs *"would I reach the same conclusion from the reviewed artifact?"* (not) · **CDR-O1 carried, not remediated; CDR-1 closes with no finding against its artifact** · **§10: PRECONDITION OF REVIEWABILITY recorded as a FACT about review evidence, not a prescription — a review is reproducible only if BOTH its governing sources AND its reviewed object remain available; neither alone suffices** · **structural split restated in the Authority's stronger form: the NORMATIVE BASIS remains independently verifiable while the APPLICATION EVIDENCE is no longer independently reproducible** · **the prevented error named: treating the survival of the REVIEW DOCUMENT as evidence that the REVIEWED OBJECT survived — *a surviving review is evidence that a review happened, and evidence of nothing else*** · **the missing status remains unminted, with its admission condition recorded: evidence from MULTIPLE cases, not this single instance** · nothing else changed; no candidate raised.*
