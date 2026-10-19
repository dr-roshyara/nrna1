# Decision brief: late evidence that could change an earlier KnowledgeOS decision (2026-10-18)

**Business input under evaluation (Principal):**

> "When late evidence could change the outcome of an earlier KnowledgeOS decision, escalate the consequences to the ARB whenever that new evidence could change the outcome."

**Status:** **BUSINESS INPUT, NOT AN APPROVED GOVERNANCE POLICY.** This brief is research. It decides nothing. No frozen or canonical document was changed.

**Principle evaluated:** new evidence may trigger a review of an earlier decision. It must not silently rewrite the historical record, nor let AI decide consequences reserved for human governance.

---

## 1. Situation (A-7 Q1–Q3)

KnowledgeOS can only decide on the records it has. Records sometimes arrive late: for example, a cancellation made on Sunday is entered on Wednesday.

- **[Reviewer-verified, ALM review 2]** Late records can change what the records establish about an earlier decision (Th-4). No record-based system can prevent this.
- **What is at stake:**
  - the trustworthiness of KnowledgeOS decisions;
  - the integrity of the historical record;
  - the ARB's exclusive right to decide what happens to work already done.

## 2. Existing rules that already govern this (verified at their own location)

| Rule | Text (excerpt) | Status | What it settles |
|---|---|---|---|
| **R-41 / ES-004.3** | "Decision text immutable; status annotations may evolve" · "history is never rewritten to manufacture consistency" | R-41 **issued ruling** (2026-07-30). Its host ES-004 is a **PROPOSED** standard | **the historical record is never rewritten.** Only a status annotation may change |
| **No back-dating** | "no START or HANDOFF manufactured retroactively" · "nothing is back-dated" · a late transcription records an act "that was given" (S0095 L141) | **corpus practice**, n ≥ 5 (S0079, S0091, S0092, S0095, S0103) | late evidence enters as **new entries at its arrival position** |
| **R-77** | "supersession requires an EXPLICIT ACT, NEVER INFERENCE" | **issued ruling** (ARB, 2026-08-03) | an earlier decision changes only through a new authorized act |
| **R-50 / R-53** (the strongest precedent) | historical reproduction "evidence collection only"; **all outcomes recorded in advance as legitimate, including "historical uncertainty remains"**; then "**THIS IS AN ANNOTATION, NOT AN AMENDMENT** … the acceptance **STANDS**"; the question left "**UNDETERMINED** from repository evidence"; a forward-pointer annotation under ES-004.3 | **issued rulings** (ARB, 2026-08-01) | **late evidence about a past decision leads to an ARB-attached annotation; the decision stands unless an explicit act changes it; "undetermined" is a legitimate outcome** |
| **R-94 / PB-006** | **"the historical acceptance remains correct, superseding would blur chronology"**, so the row is annotated with its accepted scope. "Engineering supplied the evidence and selected no outcome" | **issued ruling** (ARB Chief, 2026-08-04) | the same pattern; **the producer supplies the evidence, and the authority selects the outcome** |
| **A-6** (OB-1 trigger) | threshold / owner / review point; "raisable by any participant; adjudicated by the PO/ARB"; "Governance SHALL proactively compile and present … does not decide the resulting policy outcome" | **adopted** (PO/ARB, 2026-08-15). **Scope: A5 attribution only** | **a governed template** for an escalation trigger. Not itself a rule for late evidence |
| **A-7 / GOV-HUMAN-01** | the 8 questions; "fact → interpretation → recommendation → decision required" | **adopted standing duty** (PO/ARB, 2026-08-15) | **the format of the escalation package** |
| **A-1.3** | "missing or contradictory → STOP · read-only · escalate · never infer authority" | adopted convention (D-4) | the interim behaviour of *sessions* (not of decisions) |
| **ES-001.2 / ES-001.3** | documents record governance and do not create it; Governance "must never turn its own recommendation into an authorization" | rules attributed to ARB 2026-07-11, hosted in a **PROPOSED** standard | AI and Governance recommend; they do not decide |
| **ARCH-INV-1** | "the mechanism records authority; it never grants it" | adopted by the ARB **for T3** (product knowledge space) | supports the same separation (scope caveat) |

**Not found** in the record (the search covered the rulings register R-1…R-100+, the governance proposal A-1…A-8, ES-001…ES-006, and the corpus S0001–S0104):
- any rule for **what happens to dependent actions** taken under a decision later undermined;
- any **interim status** while a review is pending;
- any **general** late-evidence trigger beyond A5.

*Not found ≠ proven absent.*

**Research results bearing on this:**
- [Reviewer-verified] Th-4: recognition can change both ways as late records arrive.
- [Reviewer-verified] Late evidence can never *prove* that an act was unauthorized under an open world. It can only show that authority is "no longer established on the records".
- [Proposed, unreviewed] Stage 1 events: `DecisionRecord`, `RecognitionChange`, `PolicyNarrowed` (`STAGE1-SPEC.md` §7).

## 3. Minimum technical mechanism (specification only; not authorized for implementation)

| Element | Purpose | Rule it implements |
|---|---|---|
| `DecisionRecord` (immutable) | the decision, its evidence basis (record position k, a hash of the records at k), open policies, reason codes, time | R-41 decision text immutable |
| `EvidenceArrival` (append-only) | a new entry at position k′, recorded at its arrival; never back-dated | no back-dating |
| `ImpactAssessment` | re-evaluates every DecisionRecord whose relevance set the new entry *could* touch (the wildcard rule). Classifies the result as `UNCHANGED` / `BASIS_CHANGED_OUTCOME_SAME` / `OUTCOME_POSSIBLY_CHANGED` (now undeterminable) / `OUTCOME_CHANGED` | Th-4; Stage 1 Th-S4 (to prove) |
| `DependentActions` | lists later acts whose own basis used the decision (a dependency walk over recorded bases) | — (concept undefined: **TODO**) |
| `EscalationCase` | an A-7 package: situation, evidence (each statement typed RECORDED / DERIVED / HYPOTHETICAL / POLICY), dependent actions, options, recommendation, **what the ARB must decide**, and the "do-nothing" consequence | A-7; ES-001.3 |
| `StatusAnnotation: REVIEW REQUESTED` | a forward pointer on the DecisionRecord, **saying that** a review is requested. It states a fact, not a ruling | ES-004.3 (status annotations may evolve); R-53 mechanics |
| `ARBRuling` (a new act) | the ARB's outcome, recorded with its human-act reference. Then a status annotation pointing to it | G-2/R5b; R-77 |

**What AI may do:** assess, compile, recommend, and attach "review requested".

**What AI may not do:** set any outcome or interim effect (suspend, void, ratify), or edit the decision.

## 4. Unresolved policy choices (require the ARB or a designated authority)

| # | Choice | Options | Business consequences |
|---|---|---|---|
| **P-1** | **Trigger:** what counts as "could change the outcome" | **(a)** any relevant new entry · **(b)** the outcome changed **or** became undeterminable · **(c)** only a proven changed outcome | (a) safest, noisiest; (b) matches the business input's "could"; (c) quietest, but misses cases where the records now say "cannot determine". **[Research]** (b) is computable under the Stage 1 semantics; (c) can never fire for "not authorized", because late evidence cannot prove unauthorization |
| **P-2** | **Interim effect while the review is pending** | **(i)** the decision stays in force, flagged · **(ii)** no *new* decisions rely on it (escalated instead), existing actions continue · **(iii)** the decision and its dependent actions are halted | (i) no disruption, but work may build on a doubtful basis; (ii) contains the risk without stopping work; (iii) maximum safety, maximum disruption. **An interim effect is itself a governance act**: without an ARB standing rule, only (i) is available, since AI may not suspend |
| **P-3** | **Outcome vocabulary for the ARB's ruling** | **confirm / annotate** (R-53 pattern) · **prospective revocation or supersession** (R-77) · **void from the start** (annulment with an earlier effective time: **RD 2**, open) · **late ratification** (**C11**, open) · **undetermined remains** (R-50 precedent) | all keep the history: each is a *new act* plus a status annotation |
| **P-4** | **What happens to dependent actions** after the ruling | continue · re-examine each · redo · unwind | **no rule exists** (TODO). This is the "consequences" in the business input |
| **P-5** | **Routing and timing** | who receives the case (ARB, or a delegated owner); a not-to-exceed review time (the A-6 analogue); who may raise a case ("any participant", the A-6.3 analogue) | without a time limit, a pending review can last indefinitely (the A-6.5 lesson) |
| **P-6** | **Scope** | KnowledgeOS's autonomous decisions only · also human or ARB decisions (R-53 was an ARB decision) | wider scope means more reviews, and is consistent with the precedents |

**Interaction with earlier briefs:** P-1 and P-2 overlap the reversal-remedy question in `RECORDING-DEADLINE-BRIEF.md`. A recording deadline reduces how often P-1 fires; it does not remove the need for P-2 to P-4.

## 5. What happens, step by step, under the evaluated principle

1. **Before review:** the new evidence is recorded at its arrival. The original decision and its basis are untouched. KnowledgeOS assesses the impact and, if P-1 fires, opens an escalation case and annotates "review requested". **The interim effect follows P-2, as set by the ARB.** Until it is set, the decision stays in force, flagged.
2. **While pending:** AI compiles the A-7 package. It researches evidence gaps by finding records, never by assuming. It recommends an outcome, labelled as a recommendation.
3. **After the ARB rules:** the ruling is recorded as a new act. The decision receives a status annotation pointing to it, and is never rewritten. Dependent actions are handled per P-4. KnowledgeOS re-evaluates with the ruling as a new input. If the ruling also fixes a policy, the set of open policies narrows.

**The principle is consistent with every existing rule found.** It extends the R-50/R-53 pattern from one ARB-initiated case to a standing trigger.

## 6. Recommended approach (research opinion; not a decision)

- **Adopt the principle as a governance rule** modelled on the R-50/R-53 pattern and the A-6 template, with:
  - **P-1 = (b)**, as matching the business input;
  - **P-2 = (ii)** as a standing interim rule (contains risk, keeps work flowing);
  - **P-3** = the full vocabulary, each item an explicit act;
  - **P-4** decided case by case by the ARB until experience supports a standing rule;
  - **P-5** with a not-to-exceed review time;
  - **P-6** starting with KnowledgeOS's autonomous decisions.
- **Mechanism (§3):** specify inside the authorized Stage 1 work; implement only after a separate authorization.

## 7. Required authority

- **Adopting the principle and P-1…P-6:** the PO/ARB, by a recorded act. This is the same pattern as A-6/A-7, and it extends governance rules, so it is **not** the researcher's or AI's to adopt.
- **Implementing the mechanism:** a separate implementation authorization.
- **Writing to the authority store:** gated by R-37 / ADR-C6 (ARB).

## 8. TODOs (concepts lacking an authoritative definition; added to `06-theory/manuscript/chapter_01/TODO.md`)

| TODO | Concept |
|---|---|
| T1-22 | "late evidence" |
| T1-23 | "could change the outcome" (trigger) |
| T1-24 | "dependent actions" / "consequences" |
| T1-25 | interim status pending review |
| T1-26 | the post-ruling outcome vocabulary and the remedy for dependent actions |
| T1-27 | escalation routing, timing and scope |
