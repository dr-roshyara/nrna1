# Governance Decision Report — Two Acceptance Gates

**Type:** Governance decision report (Session 2) · **Date:** 2026-08-16 · **Commission:** PO/ARB, *"Two Acceptance Gates Before Counting and Result Publication"*
**⛔ Business/governance language only. No architecture, code, technical representation. No majority threshold, representative-selection rule, objection-resolution mechanism or fraud definition invented. Architecture ⏸️ · Session 3 🛑 · Session 1 independent.**

**Labels as required:** **A** = supported by existing adopted rules · **B** = new policy requiring adoption · **C** = unresolved · **D** = consequence that follows only after adoption.

---

## 1 · Naming — adopted as instructed, and it avoids a real collision

**They are *acceptance gates* / governed transition decisions, not phases.** ✅ **A — and the instruction earns its keep:** calling them phases would drag in `EM-GOV-009` *(the complete schedule is fixed at application — gates would then need windows nobody has specified)* and `EM-GOV-010` *(a phase is non-actionable until its scheduled conditions are met — gates have no schedule)*. **Naming them gates keeps both rules intact.**

## 2 · Where a gate sits in the adopted progression model — **A, derived**

`EM-GOV-010` makes a phase non-actionable until **① scheduled conditions · ② predecessor legitimately completed · ③ phase rules permit · ④ authorized request where required.**

> **An acceptance gate is a phase-specific rule under ③ — a precondition of the NEXT phase — not a component of the previous phase's completion.** **This follows from the commission's own distinction *"voting completed ≠ voting accepted"*.**
>
> **Consequence (A):** voting that has finished **has legitimately completed** even if acceptance is refused. **Counting is then blocked by a different condition.** The two must not be merged, or the protocol would record voting as incomplete when it was not.

## 3 · Several of the commission's questions are ALREADY ANSWERED

| Question | Status |
|---|---|
| *Does non-acceptance automatically halt progression?* | ✅ **A — YES.** A mandatory condition for the next phase is unsatisfied, so **`EM-GOV-011` applies unchanged: progression is halted at that point, the event, evaluated conditions, failed condition and reason are recorded, and no downstream phase is executed or given an artificial outcome.** **No new rule is needed for the failure path.** |
| *Who may resolve a disputed or failed acceptance?* | ✅ **A/C — it is the RECOVERY question, not a new one.** A halted election enters governed recovery (`EM-GOV-014`, pending) and the permitted recovery actions are **`EM-OPEN-042`**. **Answering it here would decide `EM-OPEN-042` for one case.** |
| *May result publication occur without the required acceptance?* | ✅ **A — NO, if the gate is adopted.** `EM-GOV-008`/`EM-VOT-004`: **no individual authority may override a mandatory Election condition**, and the Chief's publication power is an operational authority, not a sovereignty. |
| *Must every material fact be recorded?* | ✅ **A — YES.** `EM-GOV-005` and its negative half: everything material is recorded, **and nothing is manufactured for what did not happen.** **The commission's own list is consistent with it, and the instruction that the protocol records rather than decides restates `EM-VOC-008`.** |
| *Objection ≠ non-acceptance ≠ fraud* | ✅ **A — the shape is already in the record.** It is `P-2H` applied one level up: **a recorded disagreement is an entry in the decision history, not a termination.** **No fraud determination is created here.** |

## 4 · ⛔ The blocking finding: the required participants do not exist in the governance record

**Both gates turn on *"which representatives must participate"*. Governance searched the adopted material and reports as fact:**

* **the only election bodies in the record are the Chief and the Deputy** *(the Constitution's referenced rule set: "only committees (chief, deputy) may administer elections")*;
* **there is no adopted concept of an election representative, observer, scrutineer, or acceptance participant** — no definition, no selection rule, no appointment authority, no eligibility criterion;
* the one adjacent open item, **`EM-OPEN-014`** *(which organisation role is the "Organisation Chief")*, sits in the **frozen** Full Membership area.

> ## **C — `Q2`–`Q5` of both gates cannot be answered, because the participants are not governed objects. This is the `SCB-8` pattern again: a rule cannot be written about an object the record does not define.**
>
> ⛔ **Governance will not invent a participant class.** **What is missing, stated precisely: who these representatives are, who appoints them, when they are determined, and whether they are election-scoped or organisation-scoped.** **Everything downstream — quorum, threshold, abstention, non-participation, unavailability — is unanswerable until that exists, because each of them counts participants.**

## 5 · Gate-by-gate

### Gate 1 — Pre-counting acceptance

**Business meaning (B, as proposed):** *acceptance of the completed voting process for the purpose of permitting counting to begin.* **Expressly not acceptance of a result — none exists yet.**
**Status: B — genuinely new policy; not adopted.** **Mandatory for every election? C.** **Participants / representatives / predetermination / Commission's own participation: C — blocked by §4.** **Quorum · what constitutes acceptance · unanimity · threshold · abstentions · non-participation · unavailability: C — all count participants, so all blocked by §4.** **Consequence of non-acceptance: A — progression halted (§3).** **Authority responsible for determining the outcome: C — and note the distinction the commission preserves: *who participates* and *who determines that acceptance was achieved* are different roles, and neither exists yet.** **Protocol facts: A — the commission's list is consistent with `EM-GOV-005`; recording it requires no new rule.**

### Gate 2 — Post-counting acceptance

**Business meaning (B, as proposed):** *acceptance of the completed counting process and its tabulation for the purpose of permitting result publication.*
**Everything in Gate 1's line applies identically.** **Two things are specific to this gate:**

> ⚠️ **(i) A — publication is irreversible, which is why this gate can only sit BEFORE it.** The PO's own ruling on record (`PBDIGIT-60`): **publication is an immutable constitutional fact; visibility is a separate control — hide/show, never "unpublish".** **There is no undo. A gate placed after publication would be decorative.**
>
> ⚠️ **(ii) A — binding constraint on any acceptance process: it must be possible without any voter↔vote linkage.** **`ADR-T11` is constitutional and build-breaking.** **Whatever participants must examine to accept a tabulation, it cannot be anything that links a voter to a vote.** **Governance states this now because it constrains the design of the gate itself, not merely its implementation.**

## 6 · Conflicts checked — one apparent, none real

**Checked:** *"only the chief may open voting or publish results"* **vs** a gate that conditions publication. **No conflict:** the Chief still performs publication; **acceptance is a precondition, exactly as `EM-VOT-004` already has conditions gating an act the Chief performs.** **`EM-GOV-009`/`010`** — no conflict, given §1's naming. **`EM-GOV-011`** — **reinforced, not strained** (§3). **`EM-VOC-005`/`EM-VOT-005`** — untouched: a gate creates no schedule change and no new opportunity.

## 7 · One question the commission did not ask — **C**

**Is there any time limit on making an acceptance decision?** The gates have **no schedule** (§1), so nothing bounds them. **An election could sit indefinitely awaiting an acceptance that nobody convenes.** **Same shape as `EM-OPEN-046`** *(is anyone obliged to act on a halted election?)* — **and it should be decided with it.** Registered as **`EM-OPEN-053`**.

## 8 · Summary

| | Gate 1 | Gate 2 |
|---|---|---|
| **Status** | **B — proposed, NOT adopted; BLOCKED on §4** | **B — proposed, NOT adopted; BLOCKED on §4** |
| **Failure path** | **A — halted (`EM-GOV-011`)** | **A — halted** |
| **Protocol** | **A — `EM-GOV-005` suffices** | **A — plus `ADR-T11` constraint** |
| **Participants** | **C — object does not exist** | **C — object does not exist** |

**D — consequences that follow only after adoption:** counting acquires a mandatory precondition it does not have today · result publication acquires one · both gates become material protocol events · a refused acceptance becomes a halt with a recorded reason, entering governed recovery.

## 9 · Open Governance questions from this commission

**`EM-OPEN-053`** *(time limit on an acceptance decision — decide with `EM-OPEN-046`)* · **the participant-class definition** *(blocking, §4)* · **mandatory-for-every-election?** · **quorum, threshold, abstention, non-participation, unavailability** *(all downstream of §4)* · **who determines the outcome as distinct from who participates.**

## 10 · Status

**Nothing adopted by this report. Architecture ⏸️ — nothing is sent to it. Session 3 🛑. Session 1 must not verify unauthorized implementation.** **The consolidated adoption act of 2026-08-15 remains unsigned.**

**Traceability.** `EM-GOV-005`/`008`/`009`/`010`/`011` · `EM-VOT-004`/`005` · `EM-VOC-005`/`008` · `P-2H` · `ADR-T11` · `PBDIGIT-60` · `EM-OPEN-014`/`042`/`046` · `SCB-8` *(same pattern)*.
