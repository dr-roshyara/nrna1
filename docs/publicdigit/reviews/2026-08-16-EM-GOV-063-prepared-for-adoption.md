# `EM-GOV-063` — PREPARED FOR ADOPTION: the election-level consequence of recovery-period expiry

**Type:** Rule preparation (Session 2) · **Date:** 2026-08-16 · **Basis:** PO decision, 2026-08-16 — *"Resolve `EM-OPEN-042` Cell ② in favour of a bounded election-level terminal consequence after expiry of the applicable service-policy recovery period, while explicitly separating that consequence from any sanction, removal or replacement of the Chief."*
**⛔ PREPARED, NOT ADOPTED — one line adopts it.** *(The decision on substance is the PO's and is recorded; the rule TEXT did not exist until now, so there was nothing to have adopted. "Ready to adopt" is not "adopted".)*

---

## 1 · The prepared rule

> ### **`EM-GOV-063` — Expiry of the halted-election recovery period**
>
> ***"Where an election's progression is halted and the applicable recovery period defined by service policy expires without recovery having succeeded, the election shall reach a terminal election-level state. The protocol shall record the expiry, the fact that recovery did not succeed, and the resulting terminal state.***
>
> ***This consequence is an election-lifecycle consequence only. It is NOT a finding that the Election Chief acted improperly, NOT a finding that the halted condition was impossible to satisfy, and it confers no authority to sanction, remove or replace the Election Chief. It does not transfer the Chief's recovery responsibility to any other person or body."***
>
> ⚠️ **THE TERMINAL STATE IS DELIBERATELY UNNAMED — its vocabulary is `EM-OPEN-110`.**

## 2 · What each clause is doing, and why it is worded that way

| Clause | Purpose |
|---|---|
| *"and the applicable recovery period … expires WITHOUT RECOVERY HAVING SUCCEEDED"* | **Three facts, not one — this is what keeps `EM-GOV-012` intact.** **The election-level outcome follows from a halt PLUS an expired governed period PLUS failed recovery, never from *"a halted progression by itself."*** |
| *"the applicable recovery period defined by service policy"* | **Matches `EM-GOV-058`'s existing phrasing exactly, and consumes `EM-GOV-059`(a)'s parameter** — the halted-phase period, distinct from the Committee-restoration one, with its configured value and policy version recorded. **The Service Provider still sets DURATION and never CONSEQUENCE.** |
| *(expiry as governed by the clocks)* | **`EM-GOV-060`/`061`/`062` are consumed, not restated: the halted clock runs only while the election is operative and halted, pauses under Inoperative, and no failure creates fresh time.** **So this rule cannot fire while the election is Inoperative.** |
| **the recording sentence** | ⚠️ **The one genuinely NEW obligation. `EM-GOV-011` records the failed condition AT THE HALT; nothing yet records EXPIRY and the failure of recovery. Without it the terminal state would appear in the record without its cause.** |
| **the whole second paragraph** | **The PO's constitutional distinction, made express rather than left to inference — because inference is exactly how an election-lifecycle consequence would drift into an officer finding.** |

## 3 · ✅ It fulfils an ADOPTED rule that was waiting for it

**`EM-OPEN-047` (ADOPTED, act v2 item 4):** *"Expiry of the recovery period is recorded as an event. The meaning and consequence of that event are determined by Election Governance rules and must not be silently converted into an automatic election outcome by service configuration."*

> **That rule REQUIRED an Election Governance rule to supply the meaning and left the slot empty. `EM-GOV-063` fills it.** ✅ **And it satisfies `047`'s reason exactly: the meaning is fixed by an adopted Election Rule, identical in every deployment — not by service configuration.**

## 4 · 🔴 A consequence of this decision that changes two other open questions

### 4.1 · Option B alone resolves ALL FOUR CELLS — the condition-axis rule is no longer needed for termination

| Cell | Under `EM-GOV-063` |
|---|---|
| ① `C-1` + acting, recovery succeeds | **continues** |
| ② `C-1` + not acting | **terminal at expiry** |
| ③ `C-2`/`C-3` + acting *(diligent but futile)* | **terminal at expiry** |
| ④ `C-2`/`C-3` + not acting | **terminal at expiry** |

> **Every path now terminates or continues. The earlier candidate rule — *cancel only where no further governed recovery action is available* — is REDUNDANT FOR TERMINATION and should not also be adopted, or two rules would govern one consequence.**

### 4.2 · ✅ `EM-OPEN-109`'s stakes have just DROPPED — by this decision, not by any new analysis

**The `109` analysis recorded: *if expiry is terminal, `C-2` authority is the power to SHORTEN A WAIT; if expiry is not terminal, it is the power to END AN ELECTION.*** **The PO has chosen the first branch.**

> **So the classification's remaining function is ACCELERATION, not termination: a `C-2`/`C-3` finding could end a futile wait early rather than sitting out a period that cannot help.** **A wrong finding can no longer create a termination that would not otherwise have occurred — it can only make it arrive sooner.**
> ⚠️ **The coupling I flagged against the commissioned sequence has therefore resolved itself favourably. `109` remains open, and is now a materially smaller question.**

## 5 · ⛔ What is NOT resolved, and one implementability note

* **`EM-OPEN-110` — the terminal state has no name.** ⚠️ **ADOPTION is not blocked (the substance is complete); IMPLEMENTATION is, because the protocol must record a named state.**
* **`EM-OPEN-042`③ — *cancel* vs *abandon* — still unanswered beneath `110`.**
* **`EM-OPEN-109`** — open, smaller (§4.2). · **`EM-OPEN-049`** — external, unresolved; the proportionate officer-directed remedy remains unavailable **by design, not by oversight.**
* **`EM-OPEN-102`'s other two limbs** *(acceptance-decision time limit; unfilled Committee vacancy)* are **not** answered by this rule.

## 6 · Adoption line, if the PO so decides

> *"I adopt `EM-GOV-063` as prepared, with the terminal state's vocabulary reserved to `EM-OPEN-110`."*

**Traceability.** `EM-OPEN-042` Cell ②/③ · `EM-OPEN-047` *(adopted; slot filled)* · `EM-OPEN-049`/`102`/`109`/`110` · `EM-GOV-011`/`012`/`014`/`058`/`059`(a)/`060`/`061`/`062` · A-3.
