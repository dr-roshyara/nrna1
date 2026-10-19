# Principal decision brief: late evidence, pauses, emergency exceptions and ARB review time (2026-10-18)

**This file consolidates:**
- `LATE-EVIDENCE-ESCALATION-BRIEF.md`;
- `ESCALATE-AND-PAUSE-BRIEF.md`;
- `LATE-EVIDENCE-BRIEF-REVISED.md`.

All three stay unchanged as history.

**Labels:**
- **[FACT]** verified at its source in this session;
- **[INT]** my interpretation;
- **[REC]** my recommendation;
- **[DEC]** a decision only an authorized human can make.

---

## 1. Executive summary

Sometimes KnowledgeOS decides on the records it has, and later records show the decision may have been wrong. You have told us what you want:
- escalate such cases to the ARB;
- pause the affected action while the ARB reviews;
- keep the original decision as history;
- allow a tightly controlled emergency exception;
- give the ARB a maximum review time.

**Most of the machinery already exists in the governance record.**
- The record is never rewritten.
- Decisions change only by explicit acts.
- The ARB has handled a late-evidence case before by annotating, not rewriting.
- A "stop" mechanism exists in the workflow engine.

**Three things do not exist yet, and they are your decisions:**
1. how far a pause reaches;
2. how fast the ARB must rule, and what happens if it doesn't;
3. who may lift a pause in an emergency, and under what controls.

There is also one **governance gap you should know about:** the record uses six different names for deciding bodies, and **no document says which body decides what.**

**What I recommend:**
- pause only the directly affected work, for now;
- a **5-business-day** standard review time, **2 business days** when a pause is blocking urgent work;
- an emergency exception held by **one named role**, single-use per case, with ARB review within 2 business days;
- a missed deadline **never** releases anything automatically. It triggers a recorded "overdue" alert and a defined escalation.

**Nothing here is approved policy, and nothing is implemented.**

## 2. Your business inputs (ALL UNAPPROVED; recorded, not adopted)

| # | Input | Status |
|---|---|---|
| 1 | Escalate when new evidence could change an earlier KnowledgeOS outcome | business input |
| 2 | Pause the affected action until the ARB decides | business input |
| 3 | Every ARB proposal states: problem, supporting evidence (with sources), missing information, possible solutions, consequences, AI recommendation (with uncertainty), decision required | business input |
| 4 | Authority depends on the individual rule; no universal hierarchy | business input |
| 5 | Preserve the original decision as history, record the correction separately, pause the affected action, inform the ARB | business input |
| 6 | **E-1:** a designated person or body may lift a pause in an emergency, with a recorded justification and mandatory retrospective ARB review | business input |
| 7 | A defined maximum ARB review time | business input |

## 3. Verified findings (inspected at source in this session)

| Finding | Source | Meaning in plain language |
|---|---|---|
| The **record is never rewritten**: "Decision text immutable; status annotations may evolve" | `engineering/governance/ES-004-Documentation.md` L29ff (ES-004.3, ruling R-41; the host standard is marked PROPOSED) | the original decision always stays visible |
| Corrections are **additive errata**: "this erratum is additive, per ES-004.3" | corpus S0099 L59, S0100 L140 | a correction is a new entry, never an edit |
| A decision changes **only by an explicit act**: "supersession requires an EXPLICIT ACT, NEVER INFERENCE" | rulings register R-77 | no one (and no AI) may treat a decision as changed by implication |
| **Precedent:** late evidence about a past acceptance led to "AN ANNOTATION, NOT AN AMENDMENT … the acceptance STANDS"; the open question left "UNDETERMINED"; every possible outcome was declared legitimate **in advance** | rulings R-50, R-53 | the ARB has already handled exactly this kind of case |
| **Precedent:** "the historical acceptance remains correct; superseding would blur chronology"; engineering "selected no outcome" | ruling R-94 (ARB Chief); report `2026-08-04-delivery-governance-pb006-scope-boundary-finding.md` §7 | the person who finds the problem does not choose the outcome |
| **The stop mechanism (code):** a STOP needs a registered session and **a recorded reason**, and may be recorded by **anyone** (no recorder check). It **sets the whole work item to STOPPED**; afterwards every transition is refused except CONTINUATION | `.claude/scripts/workflow-state.php` L143–145, L183–185, L250–256 | a pause stops **all** work in that work item, not just one action |
| **The release mechanism (code):** CONTINUATION is accepted only from **governance** or **human**. It reactivates **whichever registered session it names** and makes that session the owner. It does **not** check for an ARB ruling | `workflow-state.php` L138–141, L240–247 | release is a governance act, but the system cannot check that the ARB actually ruled |
| **Release has never been used:** 0 CONTINUATIONs among 274 recorded transitions (one STOP) | census of `.claude/runtime/workflow/*.json`, 2026-10-18 | the "un-pause" path is untested in practice |
| **A lane may not act in another lane's area:** "A lane's discovery of a problem in another lane's area confers no authority to act on it" | governance proposal, Amendment A-8 (PO/ARB, 2026-08-15) | KnowledgeOS may **not** pause someone else's work on its own initiative |
| **A session must stop its own work** when required information is missing or contradictory: "STOP · remain read-only · report and escalate · never infer authority" | governance proposal, Amendment A-1.3 (convention D-4) | self-pause is already required; pausing *others* is not authorized |
| **AI decides only results of executable checks; it recommends judgments.** For incidents, AI "escalates only", with the decision ARB-only plus Sponsor-only (value judgment) | `engineering/architecture/baseline/Phase-02-Domain-Model.md` §7 (FROZEN baseline, ARB-accepted) | an automatic pause is possible only under an explicitly adopted rule; emergencies involve value judgments |
| "Authority is deliberately not automated" is a **description**, not a rule. The **rule** is I-9: "Governance precedes automation; mechanisms only after evidence of insufficiency" (owner: ARB; source R-37). **R-37 itself is a structural freeze.** ADR-C6 is a **candidate** ADR, not an existing one | `docs/publicdigit/reviews/2026-08-15-KOS-ARCH-BASELINE-001-phase-a-current-architecture-baseline.md` L30, L223; rulings register R-37; S0062 | **corrects my earlier wording.** Mechanizing pauses needs an explicit ARB decision, grounded in I-9 |
| **Decision-ready format:** the A-7 standing duty, with 8 questions and "fact → interpretation → recommendation → decision required" | governance proposal, Amendment A-7 (PO/ARB, 2026-08-15) | **your format (input 3) is largely already required** (see §7) |
| **Escalation template:** threshold, owner, review point, a not-to-exceed date; "Governance … does not decide"; a trigger without a date "can never arrive" | governance proposal, A-6 / A-6.5 / A-6.8 | a precedent for deadlines and for who monitors |
| **Deciding bodies differ:** the rulings register labels issuers ARB (36), ARB CHIEF (9), ARB CHIEF (PREPARED) (4), DECISION AUTHORITY (2), ACCEPTING AUTHORITY (1), unlabelled (18). Rulings R-81 to R-85 became governing only when the **Decision Authority** adopted them (R-86). The ARB Chief issues dispositions and closures directly (R-94, R-99). The governance amendments are issued by **PO/ARB**. Phase-02 §7 assigns ADR approval to **Chief Architect / ARB** and value judgments to the **Sponsor** | rulings register (census of row labels); R-86; R-94; R-99; Phase-02 §7 | **the bodies are not interchangeable, and no document maps them** |

## 4. How far should a pause reach? (B-1 / B-2 / B-3)

**Your input:** "pause the affected action". That clearly means B-1. B-2 and B-3 are my extensions [INT].

| Option | In business terms | Benefit | Risk / disruption | Information gap |
|---|---|---|---|---|
| **B-1** | stop only the work directly challenged | least disruption; matches your words | other work already relying on the challenged decision continues on a doubtful basis | none |
| **B-2** | B-1, plus no *new* work may start relying on the challenged decision | stops the problem from spreading | some new work must wait | needs a definition of "relies on" (TODO T1-24). That is research, not your decision |
| **B-3** | B-1, plus all existing dependent work stops too | maximum caution | the widest disruption. The pause itself may cause harm (§6) | needs T1-24, and a list of the dependent work |

**A practical fact [FACT]:** today's stop mechanism works **per work item**. Pausing "one action" therefore pauses **everything in its work item**. Dependent work in *other* work items needs its own pause. B-1 may thus already be broader than it sounds.

**Recommendation [REC]:** **B-1 now.** Revisit B-2 once "relies on" is defined (research). **[DEC] Please confirm B-1.**

## 5. Maximum ARB review time

**Evidence and its limits:**
- The record shows ARB decisions taken **the same day or within about 2 days** of a request:
  - A-6's trigger was adopted and its date set the same day (2026-08-15);
  - R-72 (2026-08-02) was followed by R-86 (2026-08-04);
  - KnowledgeOS commissions and acceptances happened the same day (2026-08-17).
- A-6 used a **15-day** evaluation window, for gathering evidence rather than for ruling.
- **Limits:** a small sample, an intense programme, apparently one human decision-maker, and almost no timestamps in the workflow store (2 of 274 transitions carry dates). **No source sets a review time.** The durations below are **proposed operational choices, not established rules.**

| Option | Standard | Urgent (the pause blocks essential work, or a harm signal exists) | Benefit | Risk |
|---|---|---|---|---|
| T-a | 2 business days | same business day | fastest relief | unrealistic when evidence is complex or the ARB is unavailable |
| **T-b** | **5 business days** | **2 business days** | consistent with observed practice plus a margin; bounds the pause harm | still tight for complex cases (hence one extension) |
| T-c | 10 business days | 3 business days | room for complex cases | longer pauses; more pressure to use the emergency exception |

**Recommendation [REC]: T-b**, with the following handling.

| Aspect | Proposal |
|---|---|
| **The clock starts** | when Governance registers a **complete** decision package (your input 3), **not** when the evidence first arrives |
| **Incomplete package** | the clock does not start. Governance must complete it within 1 business day, or report why it cannot |
| **More evidence requested** | the ARB may stop the clock once, by a recorded request |
| **Extension** | one extension by a recorded ARB act, up to the same duration again |
| **Who monitors** | the **Governance role**. This follows its existing standing duties to present evidence proactively (A-6.4) and in decision-ready form (A-7) [FACT for the duties; INT for applying them here] |
| **Reminders** | at 50% of the time and on the last day |
| **When the deadline is missed** | an **"OVERDUE" event is recorded**; the ARB is notified daily; **escalation to a second authorized body** follows, which body is undefined (§8). **The pause stays in force. Nothing is released, approved or transferred automatically** |
| **Recording** | every missed deadline is recorded, so the ARB's own timeliness becomes visible evidence |

## 6. E-1: controlled emergency exception (proposal)

| Safeguard | Proposal [REC] | Basis |
|---|---|---|
| **Who** | **one named role**, designated by a recorded ARB act, plus a named deputy for absence. Candidates: the **Sponsor** (holds value judgments for incidents in Phase-02 §7 [FACT]); the **ARB Chief** (issues dispositions [FACT]); a named PO/ARB member | **I do not designate anyone** [DEC] |
| **What counts as an emergency** | (1) imminent **serious harm** to people or the organisation; (2) disruption of an **essential service** on an ARB-approved list; (3) a **legal obligation** with a deadline | (3) **requires external legal input**: legal obligations are undefined in this theory (TODO T1-15). (2) requires the list to exist first |
| **Scope** | lifts the pause **only for the named action, only as far as necessary, only until the ARB rules**, with a hard stop after the urgent review time (2 business days) | prevents open-ended bypass |
| **Before release** | a recorded justification: the category, the evidence, the specific action, the scope, the expected duration, the named person | an emergency act is recorded as a new act; history is untouched (ES-004.3) |
| **Notification** | the ARB is informed **the same business day** | the A-6.4 / A-7 pattern |
| **Retrospective review** | **mandatory, within the urgent review time** | your input 6 |
| **If the ARB disagrees** | the ARB rules on the consequences: re-pause, remedy, or accept. **Nothing is undone automatically** | R-77 (explicit acts only) |
| **Reuse, delegation, expiry** | **single use per case**; **no delegation** without a recorded ARB act; the designation itself **expires** after a fixed term (e.g. 12 months) unless renewed | prevents the exception becoming a routine approval route |
| **Abuse check** | use is reported at every ARB review. **More than a small number of uses in a period automatically puts the exception itself on the ARB agenda** | the A-6 "harm/dispute" arm, as a pattern |
| **Technical caveat** [FACT] | the system **cannot verify** that the person lifting the pause is the designated one: release accepts any "human" recorder, and identity is declared, not attested (INV-ATTR-2) | the control is **governance**, not mechanical, so the recorded justification and the retrospective review are essential |

## 7. Your proposal format (input 3) versus the existing A-7 duty

| Your element | Already required by A-7? |
|---|---|
| Problem | **yes** (Q1, Q2) |
| Supporting evidence, with sources | **partly**: A-7 requires "fact" to be distinguished, but **does not require source locations** |
| Missing information | **partly**: "SHALL NOT obscure uncertainty", but **no explicit section** |
| Possible solutions | **yes** (Q5) |
| Consequences | **yes** (Q3, Q7, Q8) |
| AI recommendation with uncertainty | recommendation **yes** (Q6); uncertainty **only implied** |
| Decision required | **yes** (Q4) |

**Recommendation [REC]:** do **not** create a second rule. Extend **A-7** by a recorded clarification adding three things:
- source locations for evidence;
- an explicit "missing information" section;
- the recommendation's uncertainty.

This follows the repository's own rule: extend the existing home, never copy (ES-005.4). **[DEC]** adopt the clarification.

## 8. Who decides what (responsibility matrix)

**Your input 4 is respected: each line is settled by the rule that governs it, not by a hierarchy.**

| # | Responsibility | Verified authority [FACT] | Interpretation / gap |
|---|---|---|---|
| 1 | Adopt the late-evidence and pause policy | **no rule assigns this.** The **precedent:** KnowledgeOS workflow governance rules (A-1…A-8) were adopted by **PO/ARB** | [INT] PO/ARB, by the same route as A-6. **Unresolved:** confirm the body |
| 2 | Define the escalation trigger | the same precedent (A-6's trigger was PO/ARB) | [INT] PO/ARB |
| 3 | Authorize the pause scope | no rule | [INT] PO/ARB, as part of 1 |
| 4 | Designate the emergency authority | **no rule.** The precedent for value judgments in incidents: **ARB + Sponsor** (Phase-02 §7) | **unresolved:** ARB alone, or ARB with the Sponsor? |
| 5 | Set the review deadline / monitor it | setting: precedent **PO/ARB** (A-6.8 set a date). Monitoring: **Governance role** standing duties (A-6.4, A-7) | [INT] as stated. **Unresolved:** the overdue escalation body |
| 6 | Issue the final ruling | **depends on the decision's subject** (your input 4). KnowledgeOS workflow: PO/ARB (A-1…A-8). Architecture: ARB / Chief Architect (Phase-02 §7). Programme dispositions: ARB Chief (R-94, R-99). Adopting prepared rulings: Decision Authority (R-86) | **unresolved in general.** Each case must cite its rule |
| 7 | Record the release (CONTINUATION) | mechanism: **governance or human** recorder only (code). G-2: Governance registers recorded human acts | [INT] Governance records the release **after** a human ruling or emergency act. The mechanism cannot check that link |
| 8 | Review emergency exceptions retrospectively | no rule | [REC] the body that adopted the rule (1) |
| 9 | Notify, and execute the ruling | notify: **Governance role** (A-6.4, A-7). Execute: **the owning lane** (A-8: "the owning lane moves its own") | verified |

**The underlying gap (TODO T1-30):**
- **What the record shows:** six labels (PO/ARB, ARB, ARB Chief, Decision Authority, Chief Architect, Sponsor) are used with **different observed roles**.
- **What it does not show:** whether some are the same people.
- **Proposed governance act:** a short **mapping of decision areas to bodies**, issued by the body that holds that authority today.

## 9. The proposed end-to-end lifecycle

| Step | What happens | Allowed today? |
|---|---|---|
| 1 | New evidence is recorded **when it arrives**, never back-dated | **yes** (verified practice) |
| 2 | The original decision and its evidence basis stay intact | **yes** (ES-004.3) |
| 3 | KnowledgeOS assesses whether the evidence **could change** the outcome | **yes, as analysis** (research specification; not implemented) |
| 4 | A decision-ready package goes to the ARB (input 3 / A-7) | **yes** (A-7 duty). The trigger itself (input 1) needs adoption |
| 5 | The affected action is paused | **only** by an authorized human, or under a future adopted rule. A session may stop **its own** work (A-1.3). **Pausing another lane's work requires authorization** (A-8). The engine's ability to record a STOP **grants no authority to use it** |
| 6 | An emergency authority may lift the pause (E-1) | **no**: needs the designation act (§6) |
| 7 | The ARB rules explicitly | **yes**, by the body the governing rule names |
| 8 | The ruling is recorded as **a new act**; the original gets only a status note pointing to it | **yes** (R-50/R-53/R-94; ES-004.3) |
| 9 | The release or the resulting action is recorded | **yes** (CONTINUATION by governance or human), once a ruling exists |
| 10 | Dependent work is handled per the ruling | **case by case**: no standing rule (TODO T1-24, T1-26) |

**Four kinds of "correction"** (input 5), kept distinct:

| Kind | Meaning | Status |
|---|---|---|
| correcting a record | an erratum or annotation; the decision itself is unchanged | allowed (ES-004.3; S0099, S0100) |
| changing a decision from now on | supersession or revocation by an explicit act | allowed (R-77) |
| declaring an earlier act invalid from the start | annulment | **open** (register RD 2) |
| ratifying an earlier act | late approval | **open** (register C11) |

**A correction is never proof that the original was invalid from the beginning.**

## 10. Unresolved concepts: all already covered by existing TODOs

**Checked in `06-theory/manuscript/chapter_01/TODO.md`:** T1-15 (line 35) and T1-22 to T1-30 (lines 42–50) are present.

| Concept | TODO |
|---|---|
| external legal obligations | T1-15 |
| late evidence | T1-22 |
| the trigger | T1-23 |
| dependent work | T1-24 |
| interim status | T1-25 |
| outcomes; correction kinds | T1-26 (with RD 2, C11; T1-18) |
| **review time and overdue handling** | T1-27 |
| pause scope and mapping to STOP (the per-work-item granularity is new evidence) | T1-28 |
| emergency exception | T1-29 |
| who decides what | T1-30 |

**No new TODO is needed, and the TODO file was not changed.**

## 11. Decision sheet (only genuine human decisions)

| # | Decision | Options | My recommendation | Missing evidence or authority |
|---|---|---|---|---|
| D-1 | **How far a pause reaches** | B-1 only the challenged work · B-2 also no new reliance · B-3 also existing dependent work | **B-1** (your words); B-2 later | none for B-1. "Relies on" must be defined for B-2/B-3 |
| D-2 | **Maximum ARB review time** | 2 / **5** / 10 business days (urgent: same day / **2** / 3) | **5 standard, 2 urgent**; one extension; a missed deadline triggers an alert and escalation, **never** automatic release | very little timing data; the escalation body (D-4) |
| D-3 | **Who holds the emergency exception, and under what limits** | Sponsor · ARB Chief · a named PO/ARB member; with the §6 safeguards | **one named role plus a deputy, designated by a recorded ARB act**; single use; 2-day review; the designation expires after 12 months | **external legal input** for "legal obligation" emergencies |
| D-4 | **Which body adopts these rules, and which receives overdue cases** | per the observed roles (§8) | PO/ARB adopts (the A-6 precedent); overdue cases go to the Sponsor *or* the Decision Authority (**you choose**) | **the mapping of bodies (T1-30)** |
| D-5 | **Extend A-7 with sources, missing information and uncertainty** | extend A-7 · leave it as is | **extend A-7** (no second rule) | adoption by the body that owns A-7 (PO/ARB, per its registration) |

**Not asked of you (research resolves these):** the trigger computation, event and annotation formats, the STOP/CONTINUATION mechanics, impact-assessment logic, and the definition of "relies on".

## 12. Limitations and research-gate status

- **Independent oracle (ALM-r2 specification):** inspected this session. 118/118 self-tests rerun. F-36 reproduced. **The validity rules that Stage 1 reuses have defects that must be corrected.**
- **There is no Stage-1-specific oracle yet**, and the ALM oracle cannot substitute for it.
- **The Stage 1 exit gate is NOT MET.**
- **Blockers:**
  1. correct the validity specification;
  2. a separate implementation authorization;
  3. a Stage 1 oracle;
  4. data prerequisites R-Q1 to R-Q5 (an ARB architecture question).
- **No repair cycle or implementation has been started.**
- **Timing evidence for D-2 is thin:** the workflow store has almost no timestamps.
- **Legal questions are outside this research** (T1-15).
- **Authority labels:** the observed roles come from ruling texts. Whether the labels denote the same people is **not established**.

---

**RESEARCH ONLY — BUSINESS INPUTS RECORDED — GOVERNANCE POLICY NOT ADOPTED — IMPLEMENTATION NOT AUTHORIZED.**
