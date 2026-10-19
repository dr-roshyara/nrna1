# Decision brief: escalate and pause when new evidence challenges an earlier decision (2026-10-18)

**Business inputs under evaluation (Principal). Both are NOT approved governance policy:**

1. **Escalate:** "When late evidence could change the outcome of an earlier KnowledgeOS decision, escalate the consequences to the ARB whenever that new evidence could change the outcome."
2. **Pause:** "When new evidence challenges an earlier KnowledgeOS decision, temporarily pause the affected action while the ARB reviews the case."

**Principle:** a pause is *not* an invalidation. The history and the evidence are preserved, the new information is recorded, and the final ruling belongs to the authorized human decision-maker.

**This brief supersedes nothing.** It extends `LATE-EVIDENCE-ESCALATION-BRIEF.md` (escalation only), which stays as written.

---

## 1. What the existing framework already provides (verified at source)

| Element | Source and status | Bearing on "pause" |
|---|---|---|
| **A pause mechanism exists:** BC-7 `STOP` is **sticky**, and the only exit is `CONTINUATION` | S0071 I-3, DP-5 ("a stopped assignment does not silently continue"); S0078 E-8: CONTINUATION accepted only from `governance` or `human` (rule "Inv E"). **Observed:** STOP ×1; **CONTINUATION ×0** (never exercised: OQ-5) | **"Pause" can map to the existing STOP/CONTINUATION** (consume before create: ES-005.4; R-80 forbids new governance vocabulary without demonstrated ambiguity). **The release path has never run** |
| **Decision-rights pattern** | Phase-02 §7 (FROZEN Baseline v1.0, ARB-accepted): "the AI **decides** wherever the outcome is a *derived fact of an executable check*; it **recommends** wherever the outcome is a *judgment*; humans hold every gate where authority, value, or entrenchment…" | an automatic pause is acceptable **only if** the pause trigger is an ARB-adopted, executable rule. Otherwise AI only recommends |
| **The closest analogue: incidents** | Phase-02 §7 row "Handle a constitutional incident": AI "**escalates only**"; **ARB-only** ✔; **Sponsor-only** ✔ (value judgment) | serious-harm and exception questions are **value judgments**. The framework places them with the **ARB and the Sponsor**, not with AI |
| **Escalation template** | A-6 (adopted 2026-08-15, scope A5): threshold · owner · review point · "raisable by any participant" · **a not-to-exceed date** (the OB-2 lesson: without a date a trigger may never fire) · Governance compiles, does not decide | the template for the trigger, review timing and release deadline |
| **Escalation package** | A-7 GOV-HUMAN-01 (adopted): the 8 questions, including "**what happens if we do nothing?**" | the package format |
| **History is never rewritten** | R-41 / ES-004.3 (decision text immutable; status annotations may evolve); no-back-dating practice (n ≥ 5) | the pause is a **new transition** plus a status annotation, never an edit |
| **A decision changes only by an explicit act** | R-77 | a pause cannot change the decision itself |
| **Late evidence about a past decision** | R-50 / R-53 / R-94: annotation, not amendment; the decision **stands**; "undetermined" is legitimate; the authority selects the outcome | the final consequences belong to the authority |
| **Enforcement caution** | R-37 context ("authority deliberately not automated"; S0067); P-5 (machines report, never gate; scope: attribution) | **an automatic pause is enforcement.** It needs an explicit ARB act, and per R-37 an evidence-based one |
| **Notification and accountability** | A-6.4 (Governance proactively compiles and presents); ES-001.3 (Governance must seek authority and recommend, never execute its own recommendation); A-8 ("the owning lane moves its own"); G-2 (Governance registers human acts) | Governance notifies and registers; the owning lane acts on the ruling |
| **Emergency or exception rule** | **none found** (rulings register R-1…R-100+, governance proposal A-1…A-8, ES-001…006, the implementation protocol). *Not found ≠ proven absent* | a gap |

## 2. The seven authorization areas: who holds the authority under the existing framework?

| # | Area | Authority, per the framework (with source) | Unresolved |
|---|---|---|---|
| 1 | **Pause trigger** (sufficiency, reliability threshold) | **PO/ARB** by an explicit act. Rulings exist only by explicit adoption (R-34); new rules extend governance (ES-001.2) | which trigger (P-1 of the escalation brief), and whether an extra reliability threshold applies (e.g. recorded by a known holder, not a ⊥ field) |
| 2 | **"Affected action"** (only the disputed action, or its dependents too) | **PO/ARB**: a definition with policy effect. *Note:* R-80 requires demonstrated ambiguity for any new execution-governance term, so prefer the existing STOP scope (assignment or work item) | the dependency definition (T1-24) |
| 3 | **Immediate authority** (automatic pause under a rule, or a human per pause) | Phase-02 §7 pattern: **automatic only if the trigger is an ARB-adopted executable check**. R-37: authority not automated without an evidence ruling. **Without an ARB act: AI escalates; a human (Governance registering a human act, or a human recorder) performs the STOP** | whether the ARB pre-authorizes automatic pause |
| 4 | **Exceptions** (harm, legal obligation, essential service) | **no rule.** The closest analogue (incidents): **ARB + Sponsor** (value judgment). Legal obligations are **undefined** in the theory (T1-15) | everything (T1-29) |
| 5 | **Duration and release** | release = `CONTINUATION`, which only `governance` or `human` may record (Inv E). **Policy on release: PO/ARB.** Not-to-exceed timing: the A-6 template | who may release; what happens if the ARB cannot decide in time; **the CONTINUATION path is untested** |
| 6 | **Final consequences** (the decision stands, is corrected or is revoked; actions already taken) | **ARB** (R-77 explicit act; R-50/R-53/R-94 precedents) | the outcome vocabulary (RD 2, C11) and the remedy for dependent actions (T1-26) |
| 7 | **Notifications and accountability** | **Governance role** notifies, compiles and registers (A-6.4, A-7, G-2); **the owning lane** acts (A-8) | the recipients list; the accountable owner for executing a ruling |

*Who is "the ARB"?* The record uses several authority labels: **PO/ARB**, **ARB**, **ARB Chief**, **Decision Authority**, **Chief Architect**, **Sponsor**. Each of the seven areas must name its authority explicitly. **TODO T1-30.**

## 3. Minimum technical mechanism (specification only)

This extends §3 of the escalation brief:

| Element | Function |
|---|---|
| `DecisionRecord` (immutable) | the original decision plus its evidence basis |
| `EvidenceArrival` (append-only) | new evidence at its arrival position; never back-dated |
| `ImpactAssessment` | could the outcome change? (the P-1 trigger) |
| `AffectedActions` | the disputed action, plus dependents per the adopted definition |
| `PauseRequest` | a **recommendation** to STOP, with its reasons. **Not a pause** |
| `STOP` (existing transition) | performed **only** by an authorized recorder: a human, or Governance registering a human act. Automatic only if the ARB has adopted an executable trigger rule |
| `StatusAnnotation: PAUSED — REVIEW PENDING` | the decision text is unchanged |
| `EscalationCase` (A-7 package) | includes "what happens if we do nothing" and **the harm of pausing** |
| `ARBRuling` (new act) → `CONTINUATION` or other outcome | release only by an authorized recorder |

**Required tests (in a future authorized implementation round):**
- (a) no decision text is ever edited;
- (b) no STOP without either an ARB-adopted rule match or a human act;
- (c) **no CONTINUATION without a referenced ARB ruling or an authorized release**, including an untested-path test, since the path has run 0 times;
- (d) arrivals are never back-dated;
- (e) every pause has an escalation case;
- (f) a not-to-exceed time raises a reminder (the A-6 pattern), never an automatic release, unless the ARB rules otherwise.

## 4. The minimum policy needed to make both preferences operational

**One ARB act** (an A-6-style amendment) that sets:

| Item | Content |
|---|---|
| **M-1 trigger** | the escalation brief's P-1, plus any reliability threshold |
| **M-2 scope** | the disputed action; whether new dependents are blocked; whether existing dependents are paused |
| **M-3 immediate authority** | automatic STOP under the rule: yes or no |
| **M-4 release** | who may record CONTINUATION; the not-to-exceed review time; the behaviour at expiry (reminder versus default) |
| **M-5 exceptions** | allowed or not; if allowed, who authorizes, with what record, and with what after-the-fact review |
| **M-6 notification** | recipients; the accountable owner for executing rulings |

Final consequences (area 6) and the remedy for dependent actions can stay **case by case** until experience supports a rule.

## 5. The emergency-exception question (researched now; your choice later)

| Option | Behaviour | Implications |
|---|---|---|
| **E-0: no exceptions** | a pause holds until the ARB rules | simplest and strongest protection of governance. **Risk:** the pause itself causes harm (e.g. halting an operational process in the product domain). The harm must then be absorbed or the ARB must act fast. **This needs a guaranteed review time** |
| **E-1: a named emergency authority** | a designated person or body may lift or skip a pause, with a recorded justification and mandatory after-the-fact ARB review | matches the incident pattern (ARB + Sponsor for value judgments). Needs the authority named (T1-30), a recording duty, and an abuse check |
| **E-2: pre-approved exempt classes** | the ARB defines in advance which kinds of action are never auto-paused (e.g. essential services) | predictable. **But the class definitions are themselves policy, and can be gamed** |

**Considerations:**
- **Legal:** external obligations (law, regulation) are **undefined** in the current theory (T1-15). Whether a legal duty can override a pause **cannot be answered from the record**. It needs legal input outside this research.
- **Operational:** the CONTINUATION path has **never been exercised** (0 of 274 transitions), so release under pressure is untested.
- **Architectural:** exceptions need a recorded override act (G-2 style), or they become silent bypasses.

**Research recommendation, not a decision:** **E-1**, with mandatory after-the-fact review, only if the ARB also sets a guaranteed review time. Otherwise E-0's harm risk is unbounded.

## 6. What requires authorization

| Area | Who |
|---|---|
| **M-1…M-6** (§4) and the emergency choice (§5) | **PO/ARB** by a recorded act. The Sponsor is likely co-responsible for exceptions (value judgment, per the incident pattern). **Confirm which authority labels apply (T1-30)** |
| implementing the mechanism | a separate authorization |
| any change to BC-7 STOP/CONTINUATION semantics or the authority store | ARB (R-37 / ADR-C6) |

**Nothing here is approved policy. No frozen or canonical document was changed.**

## 7. TODOs (in `06-theory/manuscript/chapter_01/TODO.md`)

| TODO | Topic |
|---|---|
| **T1-28** | "pause": whether it is the existing STOP, and at which scope |
| **T1-29** | the emergency exception and its authority |
| **T1-30** | the authority labels: which body decides which area (PO/ARB · ARB · ARB Chief · Decision Authority · Chief Architect · Sponsor) |

Carried: T1-22 to T1-27 from the escalation brief; T1-15 (external obligations).
