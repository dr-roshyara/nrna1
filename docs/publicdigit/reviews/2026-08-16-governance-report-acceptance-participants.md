# Governance Decision Report — Acceptance Participant and Decision Rule Model

**Type:** Governance decision report (Session 2) · **Date:** 2026-08-16 · **Commission:** PO/ARB, *"Acceptance Participant and Decision Rule Model"*
**⛔ Business/governance language only. No architecture, entity, API, UI or mechanism. No threshold, representative number, appointment authority, replacement authority or legal validity inferred. Architecture ⏸️ · Session 3 🛑 · Session 1 independent.**

**Classification used throughout: ADOPTED · DERIVED · OPEN · BLOCKED · OUT OF SCOPE.**

---

## ⛔ 0 · The blocking finding, now larger than it was

The previous report found **one** undefined object. Working §1 and §2 finds **three**, and one more that may be a fourth.

| Actor named in the commission | Status in the governance record |
|---|---|
| **Election Chief** | ✅ **exists** — `EM-GOV-008` and throughout |
| **Deputy Election Officer** | ✅ **exists** — `EM-GOV-008` |
| **Service Provider** | ✅ **exists as a boundary** — `EM-GOV-006`, `EM-GOV-014` Part 2, `EM-OPEN-047` |
| **Election Commission** | ⛔ **DOES NOT EXIST.** The only bodies on record are chief and deputy *("only committees (chief, deputy) may administer elections")*. **Nothing defines an Election Commission, its membership, or its powers.** |
| **Acceptance Representative** | ⛔ **DOES NOT EXIST.** No definition, no class, no appointment authority, no eligibility rule. |
| **Party** *(implied by "candidate-specific, party-specific")* | ⛔ **DOES NOT APPEAR** anywhere in the adopted governance material. **A party-based representation model would introduce a fourth undefined object.** |

> **BLOCKED — `Q4`–`Q7`, `Q11`–`Q15` of §2 and `Q1`–`Q9` of §3 cannot be answered.** **Every one of them either names an undefined body or counts undefined participants.** ⛔ **Governance will not define an electoral body; that is a constitutional-level business decision about who holds electoral standing.**

## 1 · What IS established — DERIVED, needing no new adoption

### 1.1 · The Service Provider boundary is **already in force** *(§9 restates it rather than creating it)*

**`EM-GOV-006` (ADOPTED):** a governed period exists **only where an Election Rule establishes it**; no scheduler behaviour, timeout, retry interval **or implementation convenience** constitutes one. **`EM-OPEN-047`'s resolution:** the service **reports**; **Election Governance decides electoral meaning**. **`EM-GOV-008`:** no individual authority overrides a mandatory Election Rule.

> **DERIVED: the Service Provider may configure and enforce; it may not decide how many representatives an election requires, what the threshold is, who is entitled to representation, whether an objection is legitimate, or whether an election is valid.** **§9 needs no adoption — only application.**
>
> ⚠️ **One place the Service Provider does hold a gate, and why it does not breach this:** the pending `EM-GOV-015` exceptional voting extension. **That is a trust-and-service check on exceptional use of the software, not an electoral decision** — and a rejection changes nothing electorally, since voting simply ends at the published time. **It is the single exception, and it is bounded.**

### 1.2 · The rules that governed a decision stay bound to that decision — **DERIVED**

**§7's requirement — *"do not silently allow later changes to rewrite earlier decisions; the protocol must preserve the participant and decision rules that governed each acceptance decision"* — is already binding** under `EM-GOV-005` *(material events recorded, original history preserved, nothing manufactured)* and `D-2`.

> ⚠️ **And it is the SAME problem as `EM-OPEN-050`(b), with the same answer.** There, a service-policy version binds when a recovery period begins and a later change is not retroactive. **Here, the participant and threshold rules in force when an acceptance decision was made remain bound to that decision.** **One principle, two applications — Governance states the cross-link so they are not solved twice and differently.**

### 1.3 · Objection, failure and fraud — **DERIVED**

`P-2H` one level up: **a recorded disagreement is an entry in the decision history, not a termination.** **The commission adds a sharper case and it is also derived: a representative may object even when the threshold IS achieved — so the record must hold objections that did not change the outcome.** **This is exactly `P-2H`'s *"a successful request never erases the refusals that preceded it"*, applied to acceptance.** **No fraud determination is created.**

### 1.4 · Acceptance failure — **DERIVED, with one gap**

**Non-acceptance leaves a mandatory condition unsatisfied, so `EM-GOV-011` applies unchanged: progression is HALTED, the event, evaluated conditions, failed condition and reason are recorded, no downstream phase is executed or given an artificial outcome.**

> ⚠️ **GAP, and it is a concrete cost of the unsigned act:** §6 requires that failure **must not** be assumed to mean cancellation, suspension, rescheduling, fraud or abandonment. **The rule that secures exactly that is `EM-GOV-012` — *"a halted progression does not by itself define the election-level outcome"* — which is PENDING and unadopted.** **Until it is adopted, §6's protection rests on nothing but this report.**

## 2 · Genuinely new policy required — **B**

**`B-1` · Participant rules are fixed during the election application.** ⚠️ **This is NOT derivable from `EM-GOV-009`.** That rule fixes the **schedule** at application; **it says nothing about participants.** **The analogy is available and strong — plan first, publish, then govern change — but the extension is new policy and must be adopted, not inherited.** *(If adopted, `EM-VOC-007`'s publication logic would then apply to participant rules by the same route: an official commitment that cannot be silently changed.)*

**`B-2` · The participant class itself** — who has the business right to participate, and how that right is established. ⛔ **Blocked by §0; this is the commission's own subject and it cannot be answered without a decision on electoral standing.**

**`B-3` · The decision rule** — quorum, threshold, abstention and absence handling, and **whether the two gates may carry different thresholds.** **All OPEN; none inferred.**

## 3 · ⚠️ Three consequences that must be visible before any threshold is chosen

**`C-1` · Making the Chief a participant partially undoes the separation this programme has protected.** The adopted model is **the Chief REQUESTS; the Election Rules DECIDE** (`EM-VOT-004`, `EM-GOV-008`). **If acceptance is a rule-condition and the Chief votes in it, the Chief partly decides whether their own request is permitted.** **Not a prohibition — a consequence.** **`Q1` of §2 should be answered knowing it.**

**`C-2` · "Equal representation" makes the outcome depend on how many candidates stood.** If representation is per candidate, **a post with twenty candidates yields twenty representatives and a post with two yields two** — so under any counting threshold **the acceptance outcome varies with the size of the field.** **Neither good nor bad; it must be intended.**

**`C-3` · Non-nomination and quorum can deadlock.** **If quorum counts *required* participants, a candidate who never nominates a representative can prevent quorum indefinitely** — and `EM-OPEN-053` already notes that nothing bounds when an acceptance decision must be made. **§2's `Q12` and §3's `Q1` must be answered together, or the gate can be blocked by inaction.**

## 4 · One question the commission does not ask — **OPEN**

**Does an election with several posts have ONE acceptance, or one per post?** The record's elections carry **multiple posts, national and regional**. **An acceptance of "the completed voting process" is naturally election-wide; an acceptance by *candidate representatives* is naturally post-scoped.** **Nothing decides it, and the participant model cannot be specified without it.** Registered as **`EM-OPEN-054`**.

## 5 · Required closing report

**① What is now established:** the Service Provider boundary *(derived, already in force — §9 needs no adoption)* · the binding of participant and threshold rules to the decisions they governed *(derived; same principle as `EM-OPEN-050`(b))* · objection ≠ failure ≠ fraud, **including objections that did not change the outcome** *(derived, `P-2H`)* · the failure path *(derived, `EM-GOV-011`)*.

**② What remains blocked:** **the Election Commission, the acceptance-representative class and any party concept do not exist in the governance record.** Every participant, quorum and threshold question depends on them.

**③ What must be resolved before the gates can become mandatory:** the participant class and electoral standing · whether the Chief participates *(with `C-1` in view)* · the decision rule and quorum, jointly with non-nomination *(`C-3`)* · scope per election or per post *(`EM-OPEN-054`)* · `B-1` adoption *(participant rules fixed at application)* · **and `EM-GOV-012`'s adoption, without which §6's protection is unsecured.**

**④ Must any existing Governance rule be amended?** ⛔ **No amendment is required by this report.** `EM-GOV-009` is **extended in scope only if `B-1` is adopted**, and that is an addition rather than an amendment. **Nothing here reopens an adopted rule.**

**⑤ What the next Governance question should be:** **electoral standing — who holds the business right to participate in an acceptance decision, and from what source.** **Everything else in this commission is downstream of it, and none of it can be answered first.**

## 6 · Status

**Nothing adopted by this report. Nothing sent to Architecture. Architecture ⏸️ · Session 3 🛑 · Session 1 must not verify unauthorized implementation.** **The consolidated adoption act of 2026-08-15 remains unsigned, and §1.4 shows one concrete cost of that.**

**Traceability.** `EM-GOV-005`/`006`/`008`/`009`/`011` · `EM-GOV-012`/`014`/`015` *(pending)* · `EM-VOC-007` · `EM-VOT-004` · `P-2H` · `D-2` · `EM-OPEN-047`/`050`(b)/`053` · `ADR-T11` · previous report `b82cf056`.
