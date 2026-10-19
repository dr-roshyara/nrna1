# Principal decision brief: late evidence, pauses, emergency exceptions and ARB review time (revision 2, 2026-10-18)

**Revision 1** is preserved as `LATE-EVIDENCE-PRINCIPAL-DECISION-BRIEF.v1.md`. This revision incorporates the Principal's clarifications.

**Labels:**
- **[FACT]** verified at source in this session;
- **[INT]** my interpretation;
- **[REC]** my recommendation;
- **[INPUT]** the Principal's business input (**not approved policy**);
- **[DEC]** a decision only an authorized human can make.

---

## 1. Executive summary

**You have now set the business shape of the process:**
- escalate;
- pause **only the directly affected action**;
- the ARB has **two days at most**;
- if it does not decide, **three warnings** follow;
- then **AI recommends and a human decides**;
- in an emergency **a human may lift a pause**, with name, identity and justification recorded and an ARB review afterwards.

**Research findings that matter for these choices:**
1. **The current stop mechanism cannot pause a single action.** It stops a whole work item. Your chosen scope (B-1) is therefore **not technically supported today**. This is a **blocker for implementation**, not a reason to change your choice.
2. **"Any human may decide" conflicts with existing rules if taken literally.** Some decisions are reserved to named bodies, and people who did the work may not approve it. I propose the narrowest rule that keeps your intent: a case never stays unresolved, AI recommends, and a human decides.
3. **The record cannot today store a person's name and attested identity.** Releases are recorded only as "human". The emergency exception therefore needs a recording change.
4. **No calendar of business days exists anywhere in the governance record.** "Two days" is easiest to enforce as **48 elapsed hours**.

**Nothing here is approved policy or implemented.**

## 2. Your business inputs (ALL UNAPPROVED, recorded as given)

| # | Input [INPUT] |
|---|---|
| 1 | Escalate when new evidence could change an earlier KnowledgeOS outcome |
| 2 | **Pause only the directly affected action.** Do not automatically pause dependent actions |
| 3 | Every ARB proposal states the problem, supporting evidence (with sources), missing information, solutions, consequences, the AI recommendation, and the decision required |
| 4 | Authority depends on the individual rule; no universal hierarchy |
| 5 | Preserve the original decision as history, record the correction separately, pause the affected action, inform the ARB |
| 6 | **Emergency:** a **human** may lift a pause. Record the person's **name and identity**, the justification, and the action affected. Mandatory retrospective ARB review (E-1) |
| 7 | **Maximum ARB review time: two days** |
| 8 | **Missed deadline:** a first, second and third warning |
| 9 | **After the third warning: AI recommends a solution but does not decide. Any human may decide** |

**Superseded interpretation:** an intermediate instruction read input 9 as "AI may decide". **The Principal corrected this: AI recommends; a human decides.**

## 3. Verified findings used in this brief [FACT]

| Finding | Source |
|---|---|
| A STOP needs a registered session and a recorded reason; any recorder is accepted. **It sets the whole work item to STOPPED**; afterwards only CONTINUATION is accepted | `.claude/scripts/workflow-state.php` L143–145, L183–185, L250–256 |
| CONTINUATION is accepted only from `governance` or `human`. It reactivates the named session; it does not check for an ARB ruling | same file, L138–141, L240–247 |
| The workflow store has **no field for a person's name or identity**. The recorder is a class (`governance`, `human`, …). There are 0 CONTINUATIONs among 274 transitions | census of `.claude/runtime/workflow/*.json` (2026-10-18) |
| "Self-declared identity must never be represented as independently attested" (INV-ATTR-2); "process identity is evidential only and never an authority input" (INV-ATTR-1) | `docs/publicdigit/reviews/2026-08-15-KOS-GOV-ATTRIBUTION-001-decision-registration.md` L19 |
| "A process that implemented a work item may NOT independently verify that same implementation" (A-1.4); "engineering never accepts its own work" (I-8) | governance proposal A-1.4 L277; corpus S0067 |
| AI **decides** only "derived facts of an executable check"; it **recommends** judgments; humans hold approval gates. Approval roles are named: Chief Architect, ARB, Sponsor, owner | `engineering/architecture/baseline/Phase-02-Domain-Model.md` §7 (FROZEN, ARB-accepted) |
| A lane may not act in another lane's area without commission (A-8); a session must stop its own work when information is missing (A-1.3) | governance proposal A-8, A-1.3 |
| Decision text immutable; status annotations may evolve; errata are additive | ES-004.3 (R-41); corpus S0099 L59 |
| Decisions change only by an explicit act (R-77). Precedent: late evidence led to an annotation; the decision stood (R-50/R-53, R-94) | rulings register |
| Governance must proactively compile and present evidence (A-6.4) in decision-ready form (A-7) | governance proposal A-6.4, A-7 |
| **No definition** of business days, working days or a holiday calendar | a search of `engineering/governance/`, the rulings register, `docs/architecture/governance/`, the implementation protocol and Implementation Process v1.1 (no matches) |
| Deciding bodies are distinct and **unmapped**: PO/ARB (governance amendments), ARB (most rulings), ARB Chief (dispositions; R-94, R-99), Decision Authority (adopted prepared rulings; R-86), Chief Architect (ADR/IDD approval), Sponsor (value judgments) | rulings register; Phase-02 §7 |

## 4. The two-day maximum, made operational

| Question | Proposal [REC] | Reason |
|---|---|---|
| **48 elapsed hours or two business days?** | **48 elapsed hours** | no business-day calendar exists [FACT]. A business-day reading would first need a governed calendar (weekends, holidays, jurisdiction), which is a new definition. Elapsed hours are unambiguous and keep a pause genuinely short. **Cost:** a case escalated on Friday afternoon falls due on Sunday afternoon. **[DEC] confirm** |
| **When the clock starts** | when Governance **registers the escalation case**: the first recorded entry of the case | prevents delay before the clock starts |
| **Incomplete submissions** | **The clock does not wait.** The package is registered with what is known, and missing items appear in its required "missing information" section (input 3). The ARB may request more evidence, but the clock keeps running | "incomplete" can never become an indefinite delay |
| **Extensions** | **no routine extension.** An ARB may issue an **explicit recorded ruling to defer** (e.g. "keep the pause; decide by date X"). That is a human decision within the time limit, and it is recorded and reported, not an automatic extension | respects the maximum. *Risk:* repeated deferrals; each one is visible in the record |
| **Who tracks the clock** | the **Governance role** | its existing standing duties: proactive evidence presentation (A-6.4) and decision-ready communication (A-7) [FACT for the duties; INT for applying them] |
| **When 48 hours expire** | the **third warning** is issued, the AI recommendation is presented, and the human-decision rule applies (§6). **The pause stays in force. Nothing is released, approved, invalidated or transferred automatically** | your input; the explicit-act rule (R-77) |

## 5. The three warnings, within the two days

| Warning | When | Means | Goes to |
|---|---|---|---|
| **First** | **24 h** after registration (half the time) | "half the review time has passed; the case is open" | the ARB members who own the case; Governance |
| **Second** | **40 h** (8 h before the deadline) | "the deadline is close; a decision is needed today" | the same, **plus** the overdue-escalation body (§8: not yet designated) |
| **Third** | **48 h** (the deadline reached) | "the deadline is missed; the case moves to the human-decision rule" | the same, **plus** the eligible human deciders (§6), with the AI recommendation attached |

**Warnings are facts computed by a clock**, so issuing them automatically fits the existing rule that AI may decide "derived facts of an executable check" [FACT: Phase-02 §7].

**Every warning is recorded**, so missed deadlines become part of the evidence about the ARB's own timeliness.

## 6. After the third warning: AI recommends, a human decides

**Permitted to AI today [FACT]:** report facts from executable checks (the clock, the warnings), and recommend a solution with evidence, uncertainty, alternatives and consequences (Phase-02 §7; A-7).

**Not permitted to AI:** deciding. This matches your input.

**The conflict in "any human may decide," taken literally [FACT + INT]:**
- **(a) Reserved decisions:** some decisions are reserved to named bodies, such as ADR approval or constitutional matters (Phase-02 §7). A literal "any human" would override them, which contradicts your own input 4 ("authority depends on the individual rule").
- **(b) Independence rules:** the person who did the work may not approve or independently verify it (I-8, A-1.4). "Any human" would include them.
- **(c) Identity:** the record cannot show *who* the human was, only "human" (§3). Accountability needs that.

**The narrowest workable rule [REC], a "fallback decision rule" for overdue cases:**
1. **Who may decide:** any human, **except** a person who produced, implemented or is otherwise a party to the challenged work. This keeps your "any human" while honouring the independence rules.
2. **What they may decide:** the **operational disposition** of the paused action: keep it paused, release it, or release it with conditions. They may also decide **the case outcome itself, unless the governing rule reserves it** to a named body. In that case their decision is **interim**, and the reserved body rules afterwards.
3. **How the decision is made:**
   - it is recorded as a new act, with the decider's **name and identity**, the reasoning, and a reference to the AI recommendation;
   - it states whether it followed the recommendation;
   - the original decision is never rewritten.
4. **Afterwards:** every fallback decision goes to the ARB for **review at its next session** (as with emergencies). The ARB may confirm it or rule differently by an explicit act.
5. **Challenge:** any participant may challenge a fallback decision to the ARB (modelled on A-6's "raisable by any participant").
6. **If the evidence is incomplete or contradictory:** the decider sees this in the package's "missing information" section. **They may still decide, but the decision must state that it was taken on incomplete evidence.**

**This needs a recorded governance act to become valid**, by the body that adopts the policy (§8). Without it, an overdue case can only continue to wait.

## 7. B-1: pause only the directly affected action. Feasibility

**Your choice (B-1) is recorded.** Under B-1:
- dependent actions are **not** paused automatically;
- their treatment follows an explicit ruling;
- the original decision is unchanged, and the pause is **not** a ruling that the decision was invalid;
- another lane's work cannot be paused without authority (A-8);
- every release is recorded.

**Feasibility [FACT]:** today's STOP mechanism **pauses an entire work item**, every lane in it, not a single action.
- B-1 is exactly achievable today **only when the affected action is the only active work in its work item**.
- Otherwise a STOP pauses more than you chose.

**This is a blocker for implementing B-1 faithfully.** It is reported, not worked around.

**Ways to remove it** (architecture choices for the ARB later; not decided here):
- a mechanism change allowing an action-level or session-level pause. This is gated by the "governance precedes automation" rule I-9 (ARB-owned);
- a working practice in which each pausable action has its own work item.

**Until then, a pause can be *recorded* as applying to the single action** (an annotation and an escalation case). **Physically stopping it may require stopping its whole work item.** That over-reach must be named in every such case.

## 8. E-1: a human may lift a pause in an emergency

| Requirement | Proposal [REC] | Feasibility / gap |
|---|---|---|
| **Name and identity** of the person | recorded with the release | **The record cannot do this today** (no name field; "human" is a class). Identity can only be *declared*, not attested (INV-ATTR-2). **Needs a recording change** (like data prerequisite R-Q3) and a way to attest identity |
| **Who may act** | **not established by any rule** [FACT]. Options: any human not a party to the work (consistent with §6); or a designated role (Sponsor, by analogy with the incident row of Phase-02 §7) | **[DEC]** |
| **Evidence and justification** | the category (serious harm · essential service · legal obligation), the evidence, the reason | **legal-obligation emergencies need external legal input** (TODO T1-15) |
| **Scope** | **only the named action, only as far as necessary**, with the time of release and the expected duration | — |
| **Notification** | the ARB and Governance **immediately** | — |
| **Retrospective review** | **mandatory**, using **the same 48-hour clock and three warnings** | the emergency cannot bypass the ARB indefinitely: it restarts the normal review |
| **If the ARB disagrees** | the ARB rules by an explicit act (re-pause, remedy, or accept). Nothing is undone automatically | R-77 |
| **Against misuse** | each release applies to one case; every use is reported; repeated use puts the exception itself on the ARB's agenda | the A-6 pattern |

## 9. Who may do what (responsibility matrix)

| Responsibility | Verified authority [FACT] | Proposal / gap |
|---|---|---|
| Adopt the policy | **no rule assigns it.** Precedent: KnowledgeOS workflow rules (A-1…A-8) were adopted by **PO/ARB** | [REC] PO/ARB. **[DEC] confirm** |
| Issue warnings | AI may report executable-check facts (Phase-02 §7). Governance has presentation duties (A-6.4, A-7) | [REC] issued automatically, recorded by Governance |
| Decide after the third warning | **no rule** | §6 fallback rule (needs adoption) |
| Act as the emergency human | **no rule.** The record cannot attest identity | §8. **[DEC]** |
| Lift a pause (record the release) | mechanism: `governance` or `human` recorder only (code) | the authorizing human act must be referenced. *The mechanism cannot check this* |
| Final ARB ruling | **depends on the governing rule** (your input 4): PO/ARB (workflow), ARB / Chief Architect (architecture), ARB Chief (dispositions), Decision Authority (adoption of prepared rulings) | case by case; general mapping **unresolved** (T1-30) |
| Review emergency and fallback decisions | **no rule** | [REC] the ARB, within 48 hours |
| Record the release and notify | Governance registers human acts (G-2) and presents (A-6.4, A-7); the owning lane executes (A-8) | verified |
| Receive overdue escalations | **no rule** | **[DEC]** |

## 10. The complete process in plain language

| Step | What happens | Status |
|---|---|---|
| 1 | New evidence is recorded when it arrives, never back-dated | **supported now** |
| 2 | The original decision stays unchanged | **supported now** (ES-004.3) |
| 3 | KnowledgeOS checks whether the evidence could change the outcome | **proposed** (research specification) |
| 4 | A decision-ready package goes to the ARB (input 3), and the 48-hour clock starts | package: **supported** (A-7). Trigger and clock: **need adoption** |
| 5 | The directly affected action is paused | **needs adoption**, plus a **mechanism change** for single-action pauses (§7) |
| 6 | Warnings at 24 h, 40 h and 48 h | **needs adoption**. Automatic issuing is compatible with existing rules |
| 7 | After the third warning: AI recommends; an eligible human decides under the fallback rule | **needs adoption** (§6) |
| 8 | In an emergency, a human may lift the pause (E-1) | **needs adoption** and a **recording change** (identity) |
| 9 | The ARB's ruling, or an authorized fallback or emergency decision, is recorded as a **new act**; the original is untouched | **supported now** (pattern R-50/R-53/R-94) |
| 10 | The release or resulting action is recorded, with an audit trail | mechanism exists (CONTINUATION, never used); identity recording **needs a change** |
| 11 | Dependent actions follow an explicit ruling (not paused under B-1) | **case by case** |

## 11. The remaining human decisions (only genuine ones)

| # | Decision | Options | Recommendation [REC] | Missing |
|---|---|---|---|---|
| **D-1** | **What "two days" means** | 48 elapsed hours · 2 business days | **48 elapsed hours**: no business-day calendar exists, and it keeps pauses short. Business days would first need a calendar to be defined | your confirmation |
| **D-2** | **Who counts as "any human" for overdue cases** | literally anyone · **anyone not a party to the challenged work, with name and identity recorded, interim where the decision is reserved** · a designated pool | **the middle option**: keeps your intent while respecting the independence rules and reserved decisions | adoption by the policy body |
| **D-3** | **Who adopts this policy** | PO/ARB · ARB · Decision Authority | **PO/ARB** (adopted all KnowledgeOS workflow amendments) | the authority mapping (T1-30) |
| **D-4** | **Who receives overdue escalations** (2nd and 3rd warnings) | Sponsor · Decision Authority · ARB Chief | **Sponsor** (holds value judgments for incidents in the frozen matrix) or the Decision Authority. **Your call** | the mapping (T1-30) |
| **D-5** | **Who may exercise the emergency release** | any human not a party to the work · a designated role (e.g. Sponsor) | **any non-party human**, consistent with D-2 and your input, provided identity can be recorded | the identity-recording change; legal input (T1-15) |
| **D-6** | **Clarify A-7** to require source locations, missing information, and the recommendation's uncertainty | clarify · leave | **clarify** (one rule, not two) | adoption by A-7's owner (PO/ARB) |

**Already settled by your inputs, so no longer asked:**
- the pause scope (B-1);
- the maximum review time (two days);
- the three-warning sequence;
- **AI recommends and a human decides.**

## 12. TODOs, research gates and permitted next steps

**TODOs:** T1-15 and T1-22 to T1-30 (lines 35 and 42–50 of `06-theory/manuscript/chapter_01/TODO.md`) already cover every open concept:

| TODO | Covers |
|---|---|
| T1-27 | timing; warnings; the overdue fallback |
| T1-28 | the pause granularity blocker |
| T1-29 | emergency release; identity |
| T1-30 | who decides what |
| T1-15 | legal obligations |

**No new TODO was added, and the TODO file was not changed.**

**Stage 1 (unchanged):**
- **the exit gate is NOT MET**;
- the defects in the reused validity specification are unresolved (independent oracle; F-36 reproduced);
- **a Stage-1-specific oracle is still needed**;
- data prerequisites R-Q1 to R-Q5 are open (now also **identity recording** for E-1 and the fallback rule);
- **implementation needs a separate authorization**;
- no repair cycle has started.

**Permitted next steps** (each needs your instruction):
- (a) take D-1…D-6;
- (b) refer the policy and the two blockers (single-action pause; identity recording) to the adopting body.

---

## Completion report

- **Updated:** `tmp/chapter_01/dec-evaluation/LATE-EVIDENCE-PRINCIPAL-DECISION-BRIEF.md` (revision 2). **Preserved:** revision 1 as `LATE-EVIDENCE-PRINCIPAL-DECISION-BRIEF.v1.md`.
- **Changes from revision 1:**
  - the review time is now **2 days (48 elapsed hours recommended)**, not 5;
  - a three-warning schedule (24 h / 40 h / 48 h);
  - **after the third warning, AI recommends and a human decides**, with a fallback rule narrowed for independence, reserved decisions and identity;
  - B-1 recorded, and its **feasibility blocker** stated;
  - E-1 now names **a human**, with identity recording identified as a gap;
  - the decision sheet is reduced to six genuine decisions;
  - settled items were removed.
- **Sources inspected this session:** the workflow engine code; the workflow store census; the attribution decision registration (INV-ATTR-1/2); the governance amendments A-1.3, A-1.4, A-6, A-7, A-8; the rulings register (R-37, R-50, R-53, R-77, R-86, R-94, R-99, label census); the Phase-02 §7 matrix; ES-004.3; the Phase A baseline; the TODO file; and a search for a business-day definition (none found).
- **Unresolved:**
  - the authority mapping (T1-30);
  - single-action pause (mechanism);
  - identity recording and attestation;
  - legal obligations (T1-15);
  - the Stage 1 gate.
- **Files changed outside `tmp/`: none.**

**RESEARCH ONLY — BUSINESS INPUTS RECORDED — GOVERNANCE POLICY NOT ADOPTED — IMPLEMENTATION NOT AUTHORIZED.**
