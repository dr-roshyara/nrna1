# PKS Phase I ARB Rulings — **Authority Fidelity Review**

| | |
|---|---|
| **Commission** | **ARB Decision Record Fidelity Review** — a NEW commission with a NEW record, issued by the Authority 2026-07-30. *(Not an extension of the closed PKS-ADR-001 knowledge-contract commission; per that closure's §14.9, a new review is a new record.)* |
| **Artifact under review** | `PKS_Phase_I_ARB_Rulings.md` |
| **Artifact kind** | **DECISION RECORD** — not a knowledge artifact. **This is why a Knowledge Contract Review was not applied:** its criteria are built for architectural knowledge, and would have evaluated this document against the wrong contract. |
| **Governing question** | ***Does this document faithfully represent the human authority's rulings and only the consequences that were actually authorized?*** |
| **Primary source of truth** | `PKS_Phase_I_ARB_Review_Dossier.md` **§3 Decision Register** (+ §4/§4a) — the register that was before the board when the rulings were issued |
| **Method** | Each recorded consequence was compared **line by line** against the register option the Authority actually chose. **No consequence was accepted on plausibility.** |
| **Expressly OUT of scope** | Whether DR-3 was the right decision · whether Strategic Modeling should have been authorized · whether DR-2 should have been mandatory · whether the DA judged well. **The review concerns fidelity of recording, nothing else.** |
| **Status** | **REVIEW COMPLETE · CONTRACT PROVISIONALLY RECOGNIZED (§14) · FINDINGS DISPOSED (§15).** Formerly: findings delivered and held. Marking-only updates applied to the reviewed artifact (§10); **no recorded ruling or consequence was altered, added, or removed.** **AF-6 WITHDRAWN by the review on verification (§8).** **AF-1/2/3/4/5/7 are NOT disposed — held pending the recognition decision at §13**, per the Authority's sequencing instruction. |

---

## 1. Verdict

**FIDELITY: SOUND IN STRUCTURE, DEFECTIVE IN ATTRIBUTION.**

**The rulings themselves are recorded verbatim and are faithful — six lines, unedited, unglossed.** The document's defects are entirely in the *consequence* layer: **it does not distinguish what the register said from what the recorder inferred.** Every finding below is of that one shape.

**The strongest evidence that the document knows the right practice: DR-3 carries an explicit `(Interpretation note: …)` marking the recorder's rendering, and it is correct to do so.** The defect is that **this discipline is applied once and nowhere else** — six further recorder derivations stand unmarked. *A neutrality practice applied to one statement in seven is not a practice; it is an instance.*

| Objective | Result |
|---|---|
| 1 Authority Fidelity | **REVISE** — classes present but unmarked; execution records interleaved with consequences |
| 2 Decision Boundary | **REVISE** — one statement allocates decision rights that no ruling conferred (AF-2) |
| 3 Consequence Fidelity | **REVISE** — **one** addition (AF-2), one recorder-constructed consequence (AF-3), two omissions (AF-5). *AF-6's second "addition" was WITHDRAWN on verification — see §8* |
| 4 Traceability Integrity | **REVISE** — consequences cited collectively, not individually; **the one per-item citation (*"per dossier §4a"*) both proves the pattern achievable AND is what allowed AF-6 to be tested and withdrawn** |
| 5 Recorder Neutrality | **REVISE** — applied once (DR-3), absent six times |
| 6 Decision Lifecycle | **REVISE (most consequential)** — the record's forward-looking statement is now false (AF-1) |
| 7 Governance Separation | **REVISE** — recording, execution, methodology and project planning share one section |
| 8 DDD Responsibility | **REVISE** — four responsibilities in a document whose charter names one |

---

## 2. What VERIFIED as faithful — reported first, because a fidelity review that reports only defects is not a fidelity review

**Two of the three statements the Authority flagged for careful verification are SUPPORTED, and one is doubly supported.**

**(a) DR-1's *"plain Accept — not accept-with-findings"* — VERIFIED SUPPORTED.** The register offers **Accept** and **Accept with findings** as *distinct options with distinct consequences* — the latter adding *"board findings recorded against specific artifacts."* The Authority chose **Accept**. **The inference that the staged observations therefore remain review observations rather than adopted board findings follows directly from the register's own option structure**, not from recorder preference. *Structurally sound.*

**(b) DR-3's *"unconditional"* — VERIFIED SUPPORTED TWICE, by independent routes.** *(i)* The register's DR-3 dependencies read *"DR-2 (**if the Pass is made mandatory**)"* — DR-2 was ruled **Recommended**, not Mandatory, so **the register itself establishes that no gate exists.** *(ii)* The DA/PA confirmed the reading explicitly on post-ruling review. **The recorder's rendering is therefore corroborated by the register independently of the confirmation** — which is the stronger of the two supports, because it does not depend on a later conversation.

**(c) DR-6's *"does not extinguish the path"* — VERIFIED SUPPORTED, verbatim.** The register's *Do not request it* consequence states: *"The Principal Architect may still commission it post-disposition as a next-phase input — **the hold binds sequencing, not the requester**."* The recorder's rendering is faithful. *(Its citation is vague where the register is precise — see AF-7.)*

**Also verified sound:** the six rulings are reproduced **verbatim**; the *Kind* field correctly states *"The decisions belong to the human authority; this document records them"*; and the advisory inputs are expressly marked **non-constitutive** — *"None of the advisory inputs constituted the act; the act is the six lines below."* **That sentence is the single best piece of authority-fidelity discipline in the artifact.**

---

## 3. AF-1 — MAJOR · Decision Lifecycle · the record's forward-looking statement is now FALSE

*Objective 6 · Evidence origin: cross-artifact*

The document closes: ***"The next PKS act is the opening of Strategic Modeling under EP-01, in a fresh session."*** and carries **Status: RULINGS IN FORCE (2026-07-28)** as its only lifecycle statement.

**Verified against the program record: that has not been the next act since 2026-07-28.** Strategic Modeling executed **M0→M8**; Phase II.B (AD-1), II.C (C4-1/C4-2), the MCA, the CDR, and a full governance chain followed; the PKS-ADR-001 review commission closed **today**.

**Why this is the most consequential finding in the review: a reader arriving at this record — the authoritative record of what was authorized — is told the authorization is still pending execution.** The document conflates five lifecycle states the commission requires to be distinguished (*issued · recorded · in force · executed · future work*) into one undated *"IN FORCE"*.

**Per-ruling lifecycle state, established by inspection and absent from the record:**

| Ruling | Issued | Current state (2026-07-30) |
|---|---|---|
| **DR-1** Accept | 2026-07-28 | **DISCHARGED** — Phase I closed; the accepted baseline was consumed by Phase II |
| **DR-2** Recommended | 2026-07-28 | **COMMISSIONED, NOT EXECUTED.** No Capabilities Pass artifact exists. **Verified: nothing in the repository performs it.** Non-gating, so it blocked nothing — but it is an outstanding commissioned deliverable that no record tracks |
| **DR-3** Authorize now | 2026-07-28 | **CONSUMED** — the phase grant was exercised in full (M0–M8) |
| **DR-4** Confirm as recorded | 2026-07-28 | **IN FORCE, unchanged** — still in the retrospective inbox; the second-run evidence bar is still unmet |
| **DR-5** Defer to DR-2 | 2026-07-28 | **CONTINGENT AND DORMANT** — it depends on a Pass that has not executed |
| **DR-6** Do not request | 2026-07-28 | **IN FORCE**; the PA commission path remains open and unexercised. The held assessment still exists |

**Recommended resolution:** a per-ruling lifecycle column, and the closing sentence marked **as of its date** with a pointer to current state. ***A decision record may state where things stood; it must not state where things stand unless it is maintained.***

---

## 4. AF-2 — MAJOR · Decision Boundary · a consequence that allocates decision rights no ruling conferred

*Objective 2 · Evidence origin: cross-artifact*

DR-2's entry states: ***"Execution scheduling rests with the PA (it may run before, alongside, or after modeling begins)."***

**Verified: the register's *Recommended* consequence reads, in full — *"The Pass is commissioned but Strategic Modeling entry is not gated on it."* It says nothing about who schedules it.**

**The clause is therefore recorder-added, and it does not merely describe — it allocates authority.** *"Scheduling rests with the PA"* is a statement about **who holds a decision right**. The commission's Decision Boundary objective is explicit: this document may record, classify, trace and reference; **it may never authorize.** **Assigning a decision right is an act of authorization**, even when the assignment is sensible and even when the assignee is the same person who issued the ruling.

**Why this is the sharpest instance rather than a technicality:** the non-gating ruling makes scheduling genuinely free — **which is exactly why the record must not silently fix it.** *(Compare IBC-M11's lesson: citing a governed identifier does not make the requirement governed. Here: recording a ruling does not make the recorder's inference part of it.)*

**Recommended resolution:** mark as recorder derivation, or route to the PA for confirmation as a separate act. **Do not delete it** — deletion would alter the record.

---

## 5. AF-3 — MAJOR · Consequence Fidelity · DR-5's ruling was OUTSIDE the register's option set, and the record does not say so

*Objective 3 · Evidence origin: cross-artifact*

The register offered DR-5 exactly two options: **Take up as its own agenda item** · **Decline** (*"the proposal remains supporting material; it may resurface only through a future explicit commission"*).

**The Authority ruled *"Defer to DR-2"* — which is neither.** It is a third outcome: decline-as-standalone **combined with routing into another decision's scope.**

**Consequence: the recorded consequence for DR-5 has NO register basis, because no register option matches the ruling.** *"Considered within the Capabilities Pass scope if and when that Pass executes. No standalone standing granted; silent inheritance remains excluded"* is **entirely recorder-constructed** — reasonable, faithful to the ruling's evident intent, and **wholly unsourced**.

**This is not a criticism of the Authority.** An authority may rule outside the menu it was offered; the menu is an aid, not a constraint on authority. **The defect is that the record presents a constructed consequence in a section headed *"consequences as recorded in the dossier's decision register"* — where, for DR-5, the register records nothing.**

**Recommended resolution:** mark DR-5's consequence as **recorder-constructed against a ruling outside the offered option set**, and route for Authority confirmation. **The phrase *"silent inheritance remains excluded"* additionally has no located source** and should name one.

---

## 6. AF-4 — MINOR · Consequence Fidelity · an inference imported from a REJECTED option

*Objective 3, 5 · Evidence origin: cross-artifact*

DR-4's entry states: *"**R-39's exception remains available-but-not-invoked.**"*

**Verified: R-39 appears in the register only under *Early promotion* — the option the Authority did NOT choose** (*"Would require an explicit exception to the multi-context evidence bar (the register carries an R-39 single-context-exception precedent)"*).

**The recorder has taken a fact from a rejected option and asserted its continuing availability under the chosen one.** The inference is probably true — a precedent does not vanish because an option was declined — **but "the precedent still exists" and "the exception remains available" are different claims, and only the first is supported.** Availability is a governance status; asserting it is an inference about what remains open.

*Recommended: mark as recorder derivation. This is the subtlest finding in the review, and the one most likely to be reproduced elsewhere — **importing content from unchosen options is a natural recorder reflex.***

---

## 7. AF-5 — MINOR · Consequence Fidelity · two OMITTED consequences

*Objective 3 (*"no missing consequences"*) · Evidence origin: cross-artifact*

**(a) DR-6's register consequence includes: *"the board forms its readiness judgment from the package alone."*** The rulings record omits it entirely. **That clause is the substantive half of the ruling** — it states the evidential basis on which readiness was judged. Its absence leaves the record showing what was declined but not what was relied on instead.

**(b) DR-1's *Accept with findings* option names *"editorial items E-1..E-7"* as foldable.** Plain **Accept** was chosen, so they were **not** folded — **and E-1..E-7 appear in no disposition record anywhere in the repository.** Verified: they occur only in the dossier. **They are therefore undisposed and untracked** — and the rulings document's *"Items the rulings did NOT dispose (recorded so nothing falls silently)"* section, whose entire purpose is to prevent this, **does not list them.**

**The second is the more serious: a completeness register that is incomplete is worse than no register**, because it licenses the inference that anything absent from it was disposed.

---

## 8. AF-6 — **WITHDRAWN on verification (2026-07-30). The recorder was right; the finding was wrong.**

*Objective 3 · Withdrawn per the Authority's instruction to distinguish **expansion** from **aggregation** and to verify the governance timeline before disposing*

**AF-6 as originally raised:** DR-1's consequence names *"the 12 OQs and 4 collisions"*, while the record's inventory adds *"the Conformance kind question · OQ-CM-1..4 per dossier §4a"* — read as an unmarked recorder extension.

**Verified, and it does not hold. The fold is AGGREGATION, not expansion — on two independent grounds, of which the first does not require the timeline at all.**

**Ground 1 (decisive, timeline-independent): §4's scope claim, not its enumeration, is what DR-1 accepted.** §4 opens: ***"All open questions in the package"*** — a **scope claim**. §4a is explicitly an **erratum against that claim**: *"this register **omitted** the four … an **erratum** in this dossier's original 'all open questions in the package' claim, **corrected here additively**."* **§4a therefore corrects the ENUMERATION of a set whose SCOPE was already accepted; it does not add to the set.** Folding OQ-CM-1..4 into the accepted inventory is faithful to what DR-1's consequence actually designates.

**Ground 2 (corroborating): §4a was before the board.** It is dated *2026-07-28, ARB review round 2*, and the rulings record's *Decision basis before the board* names *"ARB Review Dossier (incl. §4a)."* **Recorded with its own caveat: that line is the recorder's, and this review treats recorder statements with care — which is why ground 1, which is independent of it, is the one relied on.**

**And the verification produced a reversal worth recording in its own right: the REGISTER undercounts ITSELF, and the recorder was the more accurate of the two.** §4's table carries **17 rows** — OQ-PKS-1..12 (12) · C-1..C-4 (4) · **plus the unnumbered *Knowledge-System Conformance* row**. DR-1's option text says *"the 12 OQs and 4 collisions"* — **16, omitting the Conformance row that §4's own table already contains.** The rulings record's inventory reproduces **§4's actual contents**. **So the record deviates from DR-1's option *summary* precisely by being faithful to the register's *table*.**

**Observation OBS-AF-1 (replacing AF-6, and it is about the register, not the record):** DR-1's option text is an **imprecise summary of §4** — it enumerates where §4 claims scope, and its count omits an entry §4 lists. *No action is proposed against the dossier: it is a closed decision-basis artifact, and this is recorded so that a future reader comparing the two does not re-raise AF-6.*

**Why this withdrawal is recorded in full rather than quietly deleted:** **a fidelity review that cannot correct itself on verification has no standing to demand verification of others.** The Authority's caution — *distinguish expansion from aggregation; verify the governance timeline* — was correct, and it changed the finding from Minor-valid to withdrawn. **AF-6 is withdrawn; nothing about it awaits disposition.**

## 9. AF-7 — MINOR · Traceability & Separation · collective citation, and four responsibilities in one section

*Objectives 4, 7, 8 · Evidence origin: artifact-local*

**(a) Traceability.** The section heading cites *"consequences as recorded in the dossier's decision register"* **collectively**. No individual consequence names its source, so **a reader cannot separate register text from recorder inference without doing this review.** **AF-6's *"per dossier §4a"* proves the per-item pattern is achievable in this document's own style.**

**(b) Governance separation — four classes share one section.** *"Effect of each ruling"* contains:

| Class | Instance |
|---|---|
| Recorded consequence | *"Phase I closes"* |
| **Execution record** | ***"Status transitions executed: all four package artifacts re-marked ACCEPTED"*** — **an action performed, not a consequence recorded**, sitting under a heading that claims only the latter |
| Recorder derivation | AF-2, AF-3, AF-4, AF-6 |
| **Project planning** | *"Authorization is a phase grant, not a plan: work begins through the normal EP-01 discipline in a fresh session"* — process framing, unsourced |
| **Methodology governance** | DR-4's R-39 clause |

**(c) Cohesion (objective 8).** The charter names one responsibility — *record ARB rulings and their governed consequences.* The document also **executes** status transitions, **plans** the next act, and carries **methodology** notes. *Recording and executing are different acts, and the same document doing both is how an execution failure becomes invisible: nothing outside this file states whether the transitions actually happened.*

---

## 10. Marking-only updates APPLIED to the reviewed artifact

**Discipline observed, and it is the whole point of a fidelity review:** **the recorded rulings and consequences were NOT altered, added to, or removed.** Only **classification, sourcing and temporal marking** were applied — changes that make the existing content's status legible without changing the content.

**Verified, not asserted — the claim was checked against `git show HEAD:` before being made:**

| Check | Result |
|---|---|
| The six ruling lines byte-identical | ✅ **Not one was touched** |
| Every recorded consequence present verbatim | ✅ **11/11 substance checks pass.** Seven original sentences no longer match as whole sentences **because markers were inserted mid-sentence — every substantive clause survives**, confirmed clause by clause |
| Any recorded content deleted | ✅ **None** |

**One exception, stated precisely rather than left inside the general claim: the `Status` metadata field WAS reworded** — *"**RULINGS IN FORCE** (2026-07-28)"* → *"**RULINGS IN FORCE as issued 2026-07-28**"* plus the lifecycle pointer. **That field is the recorder's own metadata, not an authority statement and not a register consequence**, so it falls inside marking scope — but *"no recorded content was altered"* would have overstated it, and the distinction between *the record's content* and *the recorder's metadata about the record* is exactly the kind of line this review exists to police.

| Applied | Nature |
|---|---|
| A **statement-class legend** and per-statement markers — `[AUTHORITY ACT]` · `[RECORDED CONSEQUENCE]` · `[RECORDER DERIVATION]` · `[EXECUTION RECORD]` · `[CONTEXT]` | Classification only |
| **Per-ruling lifecycle column** (AF-1) and the closing sentence marked **as of 2026-07-28** with a current-state pointer | Temporal marking only |
| **`⚑ AF-n` flags in situ** on the six unmarked derivations and the two omissions | Annotation only |
| A **reviewer addendum** noting E-1..E-7's undisposed status — placed as a marked addendum, **not inserted into the original numbered list** | Addition kept outside the record |

**What was deliberately NOT done, and why each would have been a fidelity breach:**
- **AF-2's scheduling clause was not deleted** — deletion alters the record. It is flagged for confirmation.
- **AF-3's DR-5 consequence was not rewritten** — the recorder cannot repair a consequence whose ruling exceeded the register; only the Authority can confirm it.
- **AF-5's omitted consequences were not inserted into the ruling entries** — adding to a recorded consequence is the exact act this review exists to detect.
- **E-1..E-7 were not added to the *"did NOT dispose"* list** — that list is part of the record; a reviewer completing it would be editing the record under cover of improving it.

---

## 11. Recommended dispositions — ACCEPT / REJECT / DEFER *(advisory; the Authority disposes)*

| # | Sev | Recommendation | Act required |
|---|---|---|---|
| **AF-1** | Major | **ACCEPT** | Lifecycle column + date-marking — **applied as marking**; the DR-2 outstanding-deliverable question needs a decision (execute, cancel, or record as superseded) |
| **AF-2** | Major | **ACCEPT** | Authority confirms or withdraws *"scheduling rests with the PA"*. **Recorder cannot do either** |
| **AF-3** | Major | **ACCEPT** | Authority confirms DR-5's constructed consequence, and sources *"silent inheritance remains excluded"* |
| **AF-4** | Minor | **ACCEPT** | Authority confirms whether R-39's exception is *available* or merely *precedented* |
| **AF-5** | Minor | **ACCEPT** | Authority disposes **E-1..E-7** (undisposed for three days) and decides whether DR-6's omitted clause is restored |
| **AF-6** | — | **WITHDRAWN by the review on verification (§8)** | **None. Nothing awaits disposition.** Replaced by **OBS-AF-1**, which concerns the *dossier's* imprecision, not the record's |
| **AF-7** | Minor | **ACCEPT** | Per-consequence citation; **move the execution record out of the consequence section** |

**One question, not a finding. Q-AF-1:** should a decision record be **maintained** (lifecycle kept current) or **frozen at issuance** (a snapshot, with currency held elsewhere)? **AF-1 is an instance either way** — under *maintained*, it is stale; under *frozen*, its forward-looking sentence should never have been written. **The program has both patterns in use and no rule.** Recorded as a **methodology candidate**, not decided here.

---

## 12. The pattern, stated once

**Five of six surviving findings are the same defect: the recorder's inferences are indistinguishable from the authority's decisions.** None is a misstatement of a ruling — **the rulings are verbatim and faithful.** The record's weakness is that it **speaks in one voice** where two voices are present.

**And the artifact already contains the remedy, twice: DR-3's `(Interpretation note: …)` and DR-1's `per dossier §4a`.** *The practice needed was not invented by this review; it was already in the document and applied once each.*

---

*Traceability: ARB Decision Record Fidelity Review, commissioned by the Authority 2026-07-30 · artifact `PKS_Phase_I_ARB_Rulings.md` · primary source `PKS_Phase_I_ARB_Review_Dossier.md` §3 (+§4/§4a), compared option-by-option against the ruling actually issued · lifecycle states established by repository inspection (no Capabilities Pass artifact exists; E-1..E-7 appear only in the dossier) · scope expressly excluded the merits of DR-1..DR-6 · findings AF-1..AF-7 + Q-AF-1 delivered as recommendations; **marking-only updates applied to the reviewed artifact, with no recorded ruling or consequence altered, added, or removed** · dispositions are the Authority's and none is recorded here.*

---

## 13. SEQUENCING — dispositions are HELD pending one prior architectural decision *(Authority instruction, 2026-07-30)*

**The Authority directed: do NOT dispose AF-1…AF-7 yet.** One decision comes first:

> ***Is the Authority Fidelity Review now recognized as the canonical review contract for Decision Record artifacts?***

**Why the sequencing is right and not merely tidy:** disposing findings under a review model that has not been recognized would make the **dispositions depend on a contract with no standing** — and if the contract were later declined or altered, every disposition made under it would be of uncertain authority. **It is the same discipline the program has applied throughout: establish the governing contract for an artifact kind, then use that contract to make authoritative dispositions.** *(Compare: the ADR's §3.1.2 defined "implementation-agnostic" before §5 relied on it; the CDR froze SDM v1 before M7 executed under it.)*

**Status of the findings, therefore: AF-1, AF-2, AF-3, AF-4, AF-5, AF-7 are DELIVERED AND HELD — not disposed, not withdrawn, not applied.** AF-6 is withdrawn by the review itself (§8), which required no contract recognition because a review may always retract its own finding.

### 13.1 The recognition question, prepared for a one-line answer

| | |
|---|---|
| **Question** | Recognize the **Authority Fidelity Review** as the canonical review contract for **Decision Record** artifacts? |
| **What recognition would establish** | The eight objectives (authority fidelity · decision boundary · consequence fidelity · traceability integrity · recorder neutrality · decision lifecycle · governance separation · single responsibility) become the standing contract for this artifact kind |
| **Evidence available** | **n = 1 execution** (this review). It produced 7 findings, of which **1 was withdrawn on verification** and **3 are Major**, including two — AF-2, AF-3 — that **no knowledge-contract objective would have surfaced**, because neither asks *"does this consequence exist in the register?"* |
| **Honest counter-consideration** | **n = 1 is the bar this program has repeatedly declined** (Rule 15 declined at n = 1; Rule 16 held **provisional** at n = 1; the methodology itself is only **provisionally certified** on one lineage). **Consistency argues for PROVISIONAL recognition, not full adoption** |
| **Recommended** | **RECOGNIZE AS PROVISIONAL** — usable and authoritative for disposing this review, with promotion to canonical on a second independent decision-record review. **This is exactly the rung the admission ladder defines for n = 1** |

**Recorded so the recommendation is not mistaken for modesty: provisional recognition is sufficient to dispose AF-1…AF-7.** A provisional contract is a contract; what it lacks is generality, not authority over the case that produced it.

### 13.2 The review taxonomy — recognized as a PRINCIPLE, with two proposed contracts DECLINED at n = 0

**The Authority's observation, adopted:** there is no longer one universal review method but a **review taxonomy** — *different governance artifact kinds should not be evaluated by a single universal checklist*, which is the DDD idea that **different bounded contexts require different models**, applied to review contracts.

| Artifact kind | Review contract | Status |
|---|---|---|
| Architecture knowledge | **Knowledge Contract Review** | **In use** — n = 1 completed commission (PKS-ADR-001, closed) |
| **Decision record** | **Authority Fidelity Review** | **n = 1 — recognition pending (§13.1)** |
| Governance process | *Governance Integrity Review* | **CANDIDATE — n = 0. NOT created.** |
| Methodology | *Methodology Review* | **CANDIDATE — n = 0. NOT created.** *(Note: the program already reviews methodology through **MCA → CDR under Configuration Control**; a new contract may be unnecessary rather than merely unevidenced — which is a stronger reason to decline)* |

**The two candidates are declined on the strengthened admission filter, not deferred for convenience: n = 0 — no artifact of either kind has been reviewed and found to need a contract the existing ones cannot supply.** **PMR-6 was declined at n = 0 on exactly this basis, and the ARB itself declined Rule 15 at n = 1.** *Creating two review contracts because a taxonomy has two occupied rows would be filling in a table, not answering a need — and a taxonomy that grows by symmetry is the accretion the filter exists to prevent.*

**The principle itself stands at n = 2** (a knowledge artifact reviewed under a knowledge contract; a decision record reviewed under a fidelity contract, which found two Major defects the other contract would have missed). **Under the ladder that is "repeated" — the principle is admissible; the empty rows are not.**

### 13.3 Q-AF-1 — the Authority's DDD answer recorded, question kept OPEN, and it indicts a fix this review already applied

**The Authority's proposed model, recorded in full because it is the most structurally complete answer offered:**

| Concept | Mutability | Home |
|---|---|---|
| **Decision** | **Immutable** — never changes | The authority act |
| **Decision Record** | **Immutable**, except filing corrections | This artifact |
| **Decision State** | **MUTABLE** — proposed · issued · executed · superseded · consumed | **A Decision Status Register — NOT inside the historical decision record** |

**Its warrant, adopted as stated: otherwise one artifact mixes *historical truth* with *operational truth*, and those evolve differently.** The analogy given is exact — **event immutable, projection mutable.** *(It is also the same separation the program already enforces elsewhere: `CONTEXT.md` holds execution state precisely so governed artifacts need not, and temporal-integrity quality D exists to keep the classes apart.)*

**The self-implication, reported because failing to report it would be the exact defect this review exists to catch: under the Authority's model, the per-ruling lifecycle table I added to the rulings record in remediation of AF-1 is IN THE WRONG PLACE.** It is **mutable Decision State** installed **inside an immutable Decision Record**. **My fix for AF-1 is an instance of the problem Q-AF-1 names.**

**It is not withdrawn, for two reasons, and both are recorded rather than assumed:** *(i)* it is **marked as observational** (*"this table records observable state; it decides nothing and changes no ruling"*), so it does not corrupt the record's authority; and *(ii)* **removing it would restore AF-1's live defect — a reader would again be told Strategic Modeling is the next act.** **A misplaced true statement is better than a stale false one, but it is not the right answer.**

**The right answer depends on Q-AF-1, which is the Authority's to decide.** **If a Decision Status Register is introduced, the lifecycle table MOVES there and the rulings record keeps only its date-marked pointer** — recorded here as the pre-committed consequence, so the decision does not have to be re-derived later. **Q-AF-1 remains OPEN as a governance design question.**

---

# §14 — REVIEW CONTRACT RECOGNITION COMMISSION *(a DISTINCT commission; executed 2026-07-30)*

| | |
|---|---|
| **Commission** | **Review Contract Recognition Commission** — **not** another Authority Fidelity Review. Issued by the Authority (Senior Knowledge Architect · Chief Domain Architect · ARB Chair), 2026-07-30. |
| **Constitutional principle** | ***Recognition precedes disposition.*** Findings are not decided under an unrecognized governance contract. |
| **Placement note** | Recorded here rather than as a new document: **the act concerns THIS review's standing**, and creating a separate artifact for a single recognition decision would be the accretion the admission filter exists to prevent. **It is a distinct commission with its own authority line, not an extension of the review above.** |
| **Instruction conflict, recorded rather than silently resolved** | The Authority's covering message opens *"Recognize the Authority Fidelity Review as provisional — dispose AF-1 through AF-7"*, while the commission's **Explicit Non-Scope** states *"Do not: dispose AF-1…AF-7."* **Resolved by sequence, not by choosing one over the other:** this commission performs recognition only and **disposes nothing**; the disposition is executed as a **separate act at §15**, which the covering instruction authorizes. **Both instructions are honoured in full, in the order both texts demand.** |

## 14.1 — Deliverable 1: Recognition Assessment

**Outcome: PROVISIONALLY RECOGNIZE.** *(Permitted outcomes were DECLINE · PROVISIONALLY RECOGNIZE · FULLY RECOGNIZE.)*

| Assessment factor | Finding |
|---|---|
| **Operational evidence produced** | **n = 1 execution**, yielding 7 findings: 3 Major, 3 Minor surviving, **1 withdrawn on verification** |
| **Defect classes uniquely detected** | **ONE capability, not five — see §14.2.** Precision matters here more than volume |
| **Repeatability** | **Untested.** No second decision-record review exists. The method is written down and its source (§3 Decision Register) is stable, so repeatability is *plausible*; **plausible is not demonstrated** |
| **Admission ladder** | **n = 1 → Provisional.** See §14.3 |
| **Proportionality** | **Strongly favourable.** Recognition costs nothing structural: **no document, no new stage, no framework change.** The eight objectives already exist as written text |

## 14.2 — Deliverable 2: Evidence Analysis — the unique capability is ONE thing, and the honest count is smaller than the finding count

**A recognition decision must not be inflated by counting findings as capabilities.** Tested individually against the Knowledge Contract Review's eleven objectives:

| Finding | Could an existing review contract have found it? |
|---|---|
| **AF-1** (lifecycle conflation, stale forward statement) | **YES** — KCR objective 7 (temporal integrity) covers exactly this; it is the KC-9b class. **Not unique** |
| **AF-7** (mixed classes, collective citation) | **YES** — KCR objectives 5, 10, 11 cover class mixing and responsibility creep. **Not unique** |
| **AF-2** (a clause allocating a decision right) | **PARTIALLY** — KCR objective 4 asks whether wording accidentally performs an Authority act, so it might have been reached. **But only the register comparison makes it *necessarily* detectable**, by showing the clause absent from the chosen option's consequence |
| **AF-3** (ruling outside the offered option set) | **NO** — requires knowing the option set. **Unique** |
| **AF-4** (inference imported from a rejected option) | **NO** — requires knowing which option was rejected. **Unique** |
| **AF-5** (omitted register consequences) | **NO** — an omission is invisible without the source enumerating what should be present. **Unique** |

**The distinct governance capability, stated exactly: *line-by-line comparison of each recorded consequence against the decision register — both the option set OFFERED and the option actually CHOSEN.*** **AF-3, AF-4 and AF-5 depend on it entirely; AF-2 is materially strengthened by it. Nothing in any existing review contract requires it.**

**Do those defects materially affect governance? Yes, and demonstrably:**
- **AF-2** — a record **creating a delegation of authority** that no ruling conferred. *A recorder that can create authority makes every record it produces unreliable in the one respect that matters.*
- **AF-3** — a consequence with **no register basis**, presented under a heading that claims register provenance. **Provenance loss is the specific failure mode a decision record exists to prevent.**
- **AF-5(b)** — **E-1..E-7 undisposed and untracked**, absent from the very list built to stop that. A live governance gap, not a documentation blemish.

**Recognition ≠ extraction ≠ standardization — distinguished explicitly, as the commission requires:**

| Act | What it would mean | Status |
|---|---|---|
| **RECOGNITION** | The contract has standing for reviews of this artifact kind | **GRANTED, provisionally** |
| **EXTRACTION** | A governed framework document for the AFR, alongside the Integrity Model / Method / Discipline | **NOT DONE, and not sought.** The four framework layers are **frozen for stabilization**; the AFR's objectives live in this review record where they were authored |
| **STANDARDIZATION** | Mandatory application to every decision record in the corpus, with conformance expectations | **NOT DONE.** Would require repeatability evidence that does not exist |

## 14.3 — Deliverable 3: Admission Ladder Evaluation

| Rung | Bar | Does the AFR meet it? |
|---|---|---|
| Candidate | n = 0 or hypothesis | Exceeded |
| **Provisional** | **One** named instance of value | **MET — n = 1, with a named unique capability and three material findings** |
| Governed | **Repeated** across independent occasions | **NOT MET — one execution, one lineage, one reviewer** |
| Declined | Insufficient evidence | Not applicable |

**Governance precedent, and it is uniform:** **Rule 15 DECLINED at n = 1** · **Rule 16 held PROVISIONAL at n = 1** · **PMR-6 declined at n = 0** · **the methodology itself PROVISIONALLY CERTIFIED on one execution lineage** · **quality E flagged as the Integrity Model's thinnest rung at n = 2.** **Full recognition at n = 1 would be the first exception this program has ever made to its own bar — and no argument for the exception exists beyond the review having gone well.**

**One evidence question resolved rather than left implicit: does AF-6's withdrawal count as additional evidence?** **No — it is not a second execution.** It is evidence of the *method's self-correction*, which raises confidence in the surviving findings **but adds nothing to the repeatability count.** *Confidence in a result and evidence for a method are different quantities; conflating them is how n = 1 becomes "well-validated."*

## 14.4 — Deliverable 4: Taxonomy Assessment — **NO EXPANSION**

| Proposed contract | Evidence | Decision |
|---|---|---|
| **Governance Integrity Review** | **n = 0** — no governance-process artifact has been reviewed and found to need a contract the existing ones cannot supply | **DECLINED** |
| **Methodology Review** | **n = 0 — and the case is stronger than absent evidence:** the program **already** reviews methodology through **MCA → CDR under Process Under Configuration Control**, exercised once end-to-end. **A new contract may be *unnecessary*, not merely unproven** | **DECLINED** |

**The taxonomy PRINCIPLE — *different governance artifact kinds require different review contracts* — stands at n = 2** (a knowledge artifact under a knowledge contract; a decision record under a fidelity contract that found what the first could not). **Under the ladder, the principle is admissible; the empty rows are not.**

***Symmetry is not evidence.*** A four-row table with two occupants creates an impression of two gaps. **There are no gaps — there are two artifact kinds that have been reviewed and two hypothetical kinds that have not.** *Filling the table would be answering a diagram instead of a need.*

## 14.5 — Deliverable 5: Recognition Recommendation

> **The Authority Fidelity Review is PROVISIONALLY RECOGNIZED as the review contract for Decision Record artifacts, effective 2026-07-30.**
>
> **Scope of the recognition:** its eight objectives are the governing contract when a **decision record** is reviewed. **Usable and authoritative; not yet standard, not extracted, not mandatory across the corpus.**

## 14.6 — Deliverable 6: Governance Consequences

| Question the commission mandates | Answer |
|---|---|
| **May AF-1…AF-7 now be dispositioned?** | **YES — authorized.** A provisional contract is a contract. **What provisional status withholds is generality, not authority over the case that produced it.** Executed at §15 as a separate act |
| **Does provisional recognition govern this commission only?** | **No — it governs any review of a decision record from today, but as a *usable* contract rather than a *mandatory* one.** A future reviewer may apply it without a fresh recognition act; **no reviewer is yet obliged to** |
| **What evidence would justify promotion to GOVERNED?** | **A second, independent decision-record review in which the register comparison again produces a finding no other contract would have surfaced.** *Stated as a falsifiable condition: if a second review yields only findings the Knowledge Contract Review could have found, the unique capability claimed at §14.2 is disconfirmed and the AFR should be **demoted**, not promoted* |

**Recorded because the commission asks for proportionality: recognition changes nothing structural.** No document created, no framework layer touched, no stage added to any process. **The whole act is a status grant over text that already exists** — which is why the proportionality test is passed rather than argued.

## 14.7 — Deliverable 7: Formal ARB Recommendation

> **RECOMMENDATION — issued.**
>
> **1.** The **Authority Fidelity Review** is **PROVISIONALLY RECOGNIZED** as the review contract for **Decision Record** artifacts, on the evidence of one execution with one named distinct capability — *line-by-line comparison against the decision register's offered and chosen options*.
> **2.** Recognition is **not extraction and not standardization.** No framework document is created; the four framework layers remain **frozen for stabilization**.
> **3.** **No taxonomy expansion.** *Governance Integrity Review* and *Methodology Review* are **DECLINED at n = 0**; methodology remains governed by MCA → CDR under Configuration Control.
> **4.** **Disposition of AF-1…AF-7 is AUTHORIZED** under the provisionally recognized contract, as a separate act.
> **5.** **Promotion to GOVERNED requires a second independent decision-record review**, with a **demotion condition** recorded and falsifiable.
> **6. Q-AF-1 is NOT resolved and no Decision Status Register is created** — expressly out of this commission's scope, and the observational lifecycle table remains the least-bad interim placement.

**Commission CLOSED. It disposed no finding.**

---

# §15 — FINDING DISPOSITION *(separate act, executed 2026-07-30 under the provisionally recognized contract)*

| | |
|---|---|
| **Authority** | The Authority's covering instruction: *"dispose AF-1 through AF-7."* **Performed only after §14's recognition closed** — the sequence both instructions require. |
| **Contract applied** | Authority Fidelity Review, **provisionally recognized** (§14.5). |
| **Vocabulary** | **ACCEPT · REJECT · DEFER.** |

## 15.1 — Dispositions

| # | Sev | **DECISION** | Basis, and what the decision settles |
|---|---|---|---|
| **AF-1** | Major | **ACCEPT** | The forward statement was false and one undated *"IN FORCE"* concealed six different states. **Settled: the lifecycle marking stands; the stale sentence stays date-marked, per forward-only.** **Routed, not decided: DR-2's never-executed Pass** — that is the PA's call (execute / cancel / record as superseded), not the ARB's |
| **AF-2** | Major | **ACCEPT** | Verified absent from the register's *Recommended* consequence. **Settled by the ARB, and this is the part the ARB CAN settle: *"Execution scheduling rests with the PA" is NOT an authority statement — it is recorder derivation, and it confers nothing.*** The clause stands marked. **Only the DA/PA can convert it into a delegation; that route is available and NOT required** — the non-gating ruling already leaves scheduling free, so nothing depends on the conversion |
| **AF-3** | Major | **ACCEPT** | DR-5's ruling was outside the offered option set, so its consequence has no register basis. **Settled: the consequence stands as *recorder-constructed*, marked as such, and is NOT presented as register-derived.** *"Silent inheritance remains excluded"* is marked as unsourced. **The ruling itself is untouched and remains fully in force** — an authority may rule outside its menu; only the *provenance claim* was defective |
| **AF-4** | Minor | **ACCEPT** | R-39 appears only under the rejected *Early promotion* option. **Settled: *"the precedent exists"* is sourced; *"the exception remains available"* is recorder derivation and marked.** No governance status is asserted either way |
| **AF-5(a)** | Minor | **ACCEPT — remedy applied** | DR-6's register consequence *"the board forms its readiness judgment from the package alone"* was omitted. **Restoration is authorized and applied WITH CITATION: restoring a verbatim register consequence adds nothing and invents nothing — it repairs an omission.** *(This is the one AF-5 half the ARB can discharge itself.)* |
| **AF-5(b)** | Minor | **ACCEPT — ROUTED** | **E-1..E-7 remain undisposed and untracked.** Their disposition is a **DR-1-adjacent act belonging to the DA/PA**, not to the ARB. **Accepted as a real gap and routed; the reviewer addendum stays outside the record's own list** |
| **AF-6** | — | **WITHDRAWN by the review (§8)** | Nothing to dispose |
| **AF-7** | Minor | **ACCEPT — remedy applied** | **Settled: the execution record is separated out of the consequence section**, and per-consequence class markers already carry provenance. The heading no longer claims register provenance for content that is not register-derived |
| **Q-AF-1** | — | **DEFER** | Expressly out of §14's scope and untouched here. **No Decision Status Register is created.** The observational lifecycle table remains the least-bad interim placement, with its migration consequence pre-recorded |

**Zero rejections. Six accepts, one withdrawal, one deferral.**

## 15.2 — The boundary the ARB could not cross, stated because it is the substance of these dispositions

**Three findings (AF-2, AF-3, AF-4) concern statements the RECORDER made about what the AUTHORITY decided.** The ARB can settle **what status those statements have** — *recorder derivation, conferring nothing* — because status is a governance question. **The ARB cannot convert a recorder's inference into an authority statement, because that would require having decided the underlying matter.** Only the DA/PA who issued DR-1..DR-6 can do that.

**Consequence, and it is the reason no clause was deleted or rewritten:** these dispositions **settle provenance, not content.** *A defective provenance claim is repaired by correcting the claim — never by deleting the content it mis-described.*

## 15.3 — Applied changes, each naming its authorizing finding

| Change | Authorized by |
|---|---|
| DR-6 gains the omitted register consequence, quoted with citation | **AF-5(a)** |
| The execution record moved out of *"Effect of each ruling"* into its own **Execution record** section | **AF-7** |
| AF-2/AF-3/AF-4 markers upgraded from *flagged* to **disposed**, with the settled status stated | **AF-2 · AF-3 · AF-4** |
| AF-1's lifecycle table and date-marking confirmed as standing; DR-2's execution question marked **routed to the PA** | **AF-1** |
| E-1..E-7 addendum marked **ACCEPTED AND ROUTED to the DA/PA** | **AF-5(b)** |

**Unchanged, and verified again after these edits: the six rulings are byte-identical, and no recorded consequence was deleted or reworded.** The only content *added* is a verbatim register quotation (AF-5a) — **an addition that increases fidelity to the source rather than to the recorder.**

---

*Traceability: recognition commission executed and closed §14 (PROVISIONALLY RECOGNIZE; no extraction, no standardization, no taxonomy expansion) · findings disposed §15 under that contract, as a separate act, per the Authority's covering instruction and the commission's own sequencing · AF-6 withdrawn by the review on verification · AF-2/AF-3/AF-4 settled as recorder derivations conferring nothing, with DA/PA conversion available and not required · AF-5(a) remedied by restoring a cited register consequence · AF-5(b) and DR-2's unexecuted Pass ROUTED to the DA/PA · Q-AF-1 deferred, no Decision Status Register created · promotion of the AFR to GOVERNED requires a second independent decision-record review, with a recorded demotion condition.*

