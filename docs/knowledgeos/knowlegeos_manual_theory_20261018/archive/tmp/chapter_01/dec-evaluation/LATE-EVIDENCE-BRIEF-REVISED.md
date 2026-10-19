# Revised research brief: late evidence. Escalation and interim pause (2026-10-18)

**Supersedes for research purposes** `LATE-EVIDENCE-ESCALATION-BRIEF.md` (the P-2 recommendation) and consolidates `ESCALATE-AND-PAUSE-BRIEF.md`. Both earlier files are kept unchanged as history.

**Labels:**
- **[FACT-SRC]** an authoritative source, read at its location this session;
- **[FACT-RV]** a finding verified by the independent reviewer (review 2), read in the verbatim record;
- **[INT]** my interpretation;
- **[REC]** my recommendation;
- **[DEC]** a decision required from an authority.

## 1. The Principal's business preferences (BUSINESS INPUT, NOT APPROVED POLICY)

| | Preference (verbatim in substance) | What it settles | What it does not settle |
|---|---|---|---|
| **A** | "When new evidence could change the outcome of an earlier KnowledgeOS decision, escalate the consequences to the ARB." | that **escalation** is wanted, and that its trigger is "**could** change" | what counts as "could change" (P-1); who receives the case and how fast (P-5); the scope (P-6) |
| **B** | "While the ARB reviews a case involving new evidence, temporarily pause the affected action until the ARB decides." | that the interim effect is a **pause** (not "continue as normal"), lasting **until the ARB decides** | what "affected action" includes (dependents?); who performs the pause; exceptions; what happens if the ARB is slow |

## 2. Correcting P-2 (the interim effect)

**The earlier brief recommended P-2 (ii):** no new decisions rely on the disputed decision, while existing actions continue. **That recommendation is withdrawn** [INT]. It lets the affected action continue, which Preference B rejects.

**P-2 re-framed under Preference B.** The options now differ only in scope:

| Option | What is paused | Business meaning | Risk |
|---|---|---|---|
| **B-1** | **only the directly affected action** | the narrowest reading of "the affected action" | work already built on it continues on a possibly doubtful basis |
| **B-2** | the affected action **plus new dependent actions** (nothing new may rely on it) | contains the problem without stopping existing work | requires a defined "dependent action" (T1-24) |
| **B-3** | the affected action **plus all dependent actions**, existing ones included | the maximum containment | the widest disruption. Needs T1-24, and the harm of pausing becomes the main risk (T1-29) |

**The Principal's preference supports B-1 explicitly. B-2 and B-3 are my interpretations** of how far "affected" might reach [INT]. **Choosing among them is [DEC].**

**Four properties hold under every option** (from existing rules, [FACT-SRC]):
- **A pause is not a ruling.** The decision text stays unchanged (R-41 / ES-004.3: "Decision text immutable; status annotations may evolve"). The pause is recorded as a **status annotation**, "PAUSED: REVIEW PENDING; decision not invalidated", plus a **STOP transition**. It is never an edit.
- **Changing the decision itself needs an explicit act.** R-77: "supersession requires an EXPLICIT ACT, NEVER INFERENCE".
- **Release.** The pause ends with **CONTINUATION**, which the mechanism accepts only from `governance` or `human` (Inv E: S0078 E-8 [FACT-SRC]). It can also end with the outcome the ARB rules. **The CONTINUATION path has never been exercised** (0 of 274 transitions): an operational risk [FACT, census 2026-10-18].
- **If the ARB has not ruled by a deadline:** no rule exists. A-6's lesson is that a trigger or review without a date may never conclude. **[DEC]** a not-to-exceed time, and whether expiry means a reminder or a default.

## 3. The authorization boundary (verified)

| Question | Answer | Basis |
|---|---|---|
| **1. What KnowledgeOS may do now, under approved rules** | record new evidence at arrival (never back-dated); keep the original decision and its basis intact; assess the impact; **escalate**; attach a factual status annotation ("review requested"); **stop its *own* session's work** when required answers are missing or contradictory | no back-dating: corpus practice, n ≥ 5 [FACT-SRC]. R-41 / ES-004.3 [FACT-SRC]. **A-1.3** (adopted D-4): "STOP · remain read-only · report and escalate · never infer authority" [FACT-SRC]. ES-001.3: Governance must seek authority proactively ([FACT-SRC]; the hosting standard ES-001 is PROPOSED) |
| **2. What AI may recommend but not enact** | a pause of **another lane's or actor's** action; any outcome (confirm, revoke, void, ratify); treatment of dependents | **A-8** (PO/ARB 2026-08-15): "A lane's discovery of a problem in another lane's area **confers no authority to act on it**"; forbidden without explicit commission: modifying another lane's work or records [FACT-SRC]. Phase-02 §7: AI **recommends** wherever the outcome is a judgment [FACT-SRC]. ES-001.3: never execute its own recommendation [FACT-SRC] |
| **3. What needs a new recorded ARB or designated-authority act** | the pause rule (trigger, scope, who performs it, release, deadline, exceptions); the outcome vocabulary beyond the precedents; the remedy for dependents | R-34: rulings exist only by explicit adoption [FACT-SRC] |
| **4. Can a standing rule later authorize automatic pauses?** | **Yes, in principle.** Phase-02 §7 lets AI **decide** "derived facts of an executable check". If the ARB adopts an **executable** trigger, an automatic STOP within its defined scope is consistent with the matrix. **Caution:** automatic pausing is enforcement, and the platform's stance (R-37: authority deliberately not automated) means the ARB should adopt it **explicitly**, preferably on evidence | [FACT-SRC] + [INT] |
| **5. Exceptions, emergency release, delegation, alternative interim treatment** | **none authorized; none found.** The closest analogue: "Handle a constitutional incident": AI escalates only; **ARB-only + Sponsor-only** (value judgment) | the search covered the rulings register, governance proposal A-1…A-8, ES-001…006 and the implementation protocol. Phase-02 §7 [FACT-SRC]. *Not found ≠ proven absent* |

**Correction to my earlier wording:**
- "AI may not suspend an action" is **too broad**.
- It is correct for **other lanes' actions** (A-8).
- A session **must** stop *its own* work under A-1.3.
- The mechanism itself would not prevent a STOP: the recorder class is unconstrained except on COMPLETE and CONTINUATION (S0078 E-7). **The boundary is a governance rule, not a mechanical guard.**

**The Principal's preference does not grant KnowledgeOS authority to pause anything today.**

## 4. The full lifecycle, with both preferences together

| Step | What happens | Status of the rule |
|---|---|---|
| 1. New evidence arrives | recorded at its arrival position; never back-dated | **authoritative** (corpus practice; R-41) |
| 2. The original decision stays intact | the decision text and evidence basis are unchanged | **authoritative** (R-41 / ES-004.3) |
| 3. Impact assessment | "could the outcome change?" | **research proposal** (Stage 1 events; Th-S4 unproved). The trigger definition is [DEC] (P-1) |
| 4. Escalation | an A-7 decision-ready package, typed RECORDED / DERIVED / HYPOTHETICAL / POLICY | the package format is **authoritative** (A-7). The trigger is **Preference A** (not policy) |
| 5. Interim pause | STOP on the affected scope plus the annotation "PAUSED: REVIEW PENDING; not invalidated" | **Preference B** (not policy). Under current rules: **recommended by AI, performed by a human or a commissioned lane**. Automatic only under a future ARB rule |
| 6. "Authority not established on the records" | never presented as "unauthorized" | **[FACT-RV]:** under λ⁻ = ∞ no F verdict is produced; Th-5 restated as existence: late records can always supply a missing grant. Practice: S0058 L49–51 ("does not make its content wrong") |
| 7. ARB ruling | recorded as a **new act**, with a status annotation pointing to it, then CONTINUATION or the ruled outcome | **authoritative pattern** (R-50/R-53/R-94; G-2/R5b; R-77) |
| 8. Dependents afterwards | per the ruling | **no rule** (T1-24, T1-26) |

## 5. P-1 to P-6 reassessed

| | Business question | Options | Consequences | Evidence / precedent | Narrowed by the preferences? | Authority | TODO |
|---|---|---|---|---|---|---|---|
| **P-1** | What evidence triggers escalation (and a pause)? | (a) any relevant new record · (b) the outcome changed **or** became undeterminable · (c) proven change only. Plus: (d) an extra reliability threshold for pausing | (a) noisy and safe; (b) matches "could"; (c) can never fire for "unauthorized" [FACT-RV]. A pause trigger stricter than the escalation trigger reduces disruption | A-6 trigger template (scope A5) | **A excludes (c)** ("could"). Between (a) and (b), and on (d): open | PO/ARB | T1-23 |
| **P-2** | What is paused pending review? | **B-1 / B-2 / B-3** (§2) | §2 | Phase-02 §7; A-8; STOP/CONTINUATION mechanism | **B excludes "continue as normal"** and the earlier (ii). Supports B-1; B-2/B-3 open | PO/ARB (Sponsor co-responsible if harm trade-offs arise: the incident pattern) | T1-25, T1-28 |
| **P-3** | What can the ARB rule? | confirm/annotate · prospective revoke or supersede · void from the start · late ratification · undetermined stays | all are new acts; the history is unchanged | R-50/R-53/R-94; R-77 | not narrowed | ARB (void-from-start = RD 2; ratification = C11, both open) | T1-26 |
| **P-4** | What happens to work that relied on the earlier decision? | continue · re-examine each · redo · unwind | cost versus integrity | **none found** | not narrowed. *(B-3 would pause them during review, which is not the same as deciding their fate)* | ARB | T1-24, T1-26 |
| **P-5** | Who receives a case, who may raise one, and the deadline? | ARB directly or a delegated owner · "any participant" may raise (A-6.3 analogue) · a not-to-exceed time with reminder or default | without a date, a pause may last indefinitely (A-6.5 lesson). **Under Preference B this becomes critical:** the pause holds "until the ARB decides" | A-6 / A-6.8 | **B makes a deadline urgent** but does not set it | PO/ARB. Which body: T1-30 | T1-27, T1-30 |
| **P-6** | Which decisions are covered? | KnowledgeOS autonomous decisions only · also human and ARB decisions | wider scope means more reviews; precedent R-53 concerned an ARB decision | R-50/R-53 | **A says "earlier KnowledgeOS decision"**: narrows to KnowledgeOS decisions [INT: whether recorded human decisions handled by KnowledgeOS count is open] | PO/ARB | T1-27 |
| **Exceptions** | May an action continue if pausing it causes serious harm, breaches a legal duty or disrupts an essential service? | E-0 none · E-1 named emergency authority with after-the-fact ARB review · E-2 pre-approved exempt classes | E-0's harm is unbounded without a review deadline. E-2 can be gamed | the incident row: ARB + Sponsor. Legal obligations undefined (T1-15) | **B is silent** | ARB + Sponsor [INT: the analogue] | T1-29, T1-15 |

**Recommendations [REC]; not decisions:**
- P-1 (b), with **no** extra pause threshold initially;
- P-2 **B-1 now**, with B-2 as a candidate once "dependent action" is defined;
- P-3 the full vocabulary as explicit acts;
- P-4 case by case;
- **P-5 a not-to-exceed review time, with expiry raising an escalation reminder, not an automatic release;**
- P-6 KnowledgeOS decisions;
- exceptions E-1 only with a guaranteed review time.

## 6. TODO coverage

**TODOs inspected** in `06-theory/manuscript/chapter_01/TODO.md`: **T1-22 to T1-30 are present** (lines 42–50).
- **Statuses:** T1-22 Open; T1-23 Awaiting Human Decision; T1-24 Open; T1-25 to T1-29 Awaiting Human Decision; T1-30 Open.
- **Every undefined concept in this brief is already covered:**

| Concept | TODO |
|---|---|
| late evidence | T1-22 |
| trigger | T1-23 |
| dependent action | T1-24 |
| interim status | T1-25 |
| outcomes and remedy | T1-26 |
| routing, timing and scope | T1-27 |
| pause, and its mapping to STOP | T1-28 |
| exceptions | T1-29 |
| which authority decides what | T1-30 |
| external legal obligations | T1-15 |

- **No new TODO is needed, and none was added** in this revision.

## 7. Independent oracle and the Stage 1 exit gate

**Independent oracle: available and inspected** (`STAGE1-TRACEABILITY-AND-EVIDENCE.md` §C2).
- 118/118 self-tests rerun by me; standard library only; no reference to any ALM implementation.
- F-36 reproduced: the spec's V layer tests a target's *existence* instead of its *availability*, which breaks soundness for Auth.
- 47 spec findings, of which 4 are false theorems or claims.
- **What it establishes:** the ALM-r2 V-layer specification, which Stage 1 reuses, **has defects that must be corrected before implementation.**
- **What it does not establish:** anything about Stage 1's own rules.

**Stage 1 exit gate: NOT MET.** Of the six criteria, none has been executed, because the round is specification-only.

**Blockers:**
1. correct the V-layer specification (oracle F-36, F-37, F-02, F-06, F-07, F-22, F-24);
2. a separate authorization for the implementation round;
3. a Stage-1-specific independent oracle;
4. **for business value:** data prerequisites R-Q1 to R-Q5 (an ARB architecture question).

**No repair cycle has been started.**

## 8. What the Principal is asked to decide next (business language)

1. **How far should a pause reach?** Only the action directly challenged, or also work that depends on it (new work only, or existing work as well)? *Your preference covers the first.*
2. **How long may a pause last** before the ARB must rule, and what happens if it hasn't (a reminder, or something else)? *Under your preference, without a time limit a pause could last indefinitely.*
3. **Should anyone be able to lift a pause in an emergency** (e.g. serious harm or a legal duty), and if so, who? *This may need legal input.*
4. **Who formally adopts these rules**, and which body (PO/ARB, ARB, ARB Chief, Decision Authority, Sponsor) decides which part? *The record uses all of these labels without a mapping.*
5. *(Optional later)* **Should KnowledgeOS be allowed to pause automatically** once the rule exists, or should a human perform each pause?

Technical details (the trigger computation, event semantics, annotation format) are **not** being asked of you. Research resolves them once the rules above exist.

---

**RESEARCH ONLY — BUSINESS PREFERENCES RECORDED — NO GOVERNANCE POLICY ADOPTED — NO IMPLEMENTATION AUTHORIZED.**
