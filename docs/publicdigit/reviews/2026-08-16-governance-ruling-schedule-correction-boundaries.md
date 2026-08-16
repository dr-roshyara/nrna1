# Governance Ruling Report — Schedule Correction Boundaries `SCB-1` … `SCB-9`

**Type:** Governance ruling report (Session 2) · **Date:** 2026-08-16 · **Commission:** PO/ARB, *"Schedule Correction Boundaries"*
**⛔ Business/governance language only. No code, architecture, data model, event, persistence, scheduler, API or UI decision. Architecture ⏸️ · Session 3 🛑 · Session 1 must not verify unauthorized implementation.**

**Provenance labels:** **ADOPTED** *(already binding before this commission)* · **RULED HERE** *(position stated in this commission, registered as the intended business decision)* · **DERIVED** *(follows from an adopted rule)* · **OPEN** · **BLOCKED**.

---

## ⚠️ 0 · Two collisions must be settled before the set can close

### ✅ **0.1 · RESOLVED 2026-08-16 — the reading Governance proposed is CONFIRMED**

> **PO ruling direction:** *"The Chief cannot arbitrarily move a scheduled window forward. However, when a phase has elapsed and the governed recovery mechanism requires the election to continue, the elapsed-window recovery rule may establish a new future window."*

**Both principles survive: ordinary forward movement is prohibited; elapsed-window correction under GOVERNED RECOVERY is not ordinary schedule movement at all.** **The business distinction that carries it:**

```
Normal schedule      → the Chief cannot arbitrarily change it
Halted election      → governed recovery → a new future opportunity/window
```

⛔ **`SCB-1` and `SCB-9` no longer cancel each other. The boundary set is UNBLOCKED.** ✅ **CONSEQUENCE: `EM-GOV-013` IS UNBLOCKED** — it needed `SCB-1`'s scope confirmed *(rescheduling a halted phase IS a forward movement)*, and §4 of this report already recorded that `SCB-7`, `SCB-8` and `SCB-9`(a)(b)(c) constrain **particular corrections** rather than the recovery mechanism. **With `SCB-5` ruled and `SCB-1` scoped, `EM-GOV-013` is ready for adoption.**

### ~~0.1 (original) · `SCB-1` as written contradicts an ADOPTED rule~~

**`SCB-1` says a window may not be moved forward. `EM-GOV-004` (ADOPTED) says an elapsed window may be corrected.** **A correction to an elapsed window can only place it later — a window cannot be rescheduled into the past.** **So every elapsed-window correction is a forward movement.**

**Governance cannot let a commission position override an adopted rule**, and the commission instructs that adopted rules are not to be reopened. **`SCB-1` is therefore recorded in the only reading that is consistent with the record:**

> **`SCB-1` prohibits moving a window forward AT WILL — i.e. a window that has not elapsed and where no governed recovery is under way. It does not prohibit the rescheduling of an elapsed or halted phase through the governed recovery path, which `EM-GOV-004` expressly permits.**

⛔ **One line confirms or corrects this reading. Without it, `SCB-1` and `SCB-9` cancel each other and no correction is possible at all.**

### **0.2 · `SCB-4` answers a different question from the one it names**

**`SCB-4` is *"may a window be moved backward?"* The commission's text rules that the LIFECYCLE may not return to a completed phase.** **Those are two different things, and the commission itself says so.** **The lifecycle half is ruled; the schedule half — may a window's time be moved EARLIER than published — is left unanswered.** Recorded as such below.

---

## 1 · Boundary-by-boundary

### `SCB-1` — Forward movement · **RULED HERE, scope flagged (§0.1)**

**Question:** may a scheduled window be moved later? **Ruling:** **no — not at will; the published start time remains authoritative.** **Provenance:** position stated in this commission. **Lifecycle consequence:** a published start is a commitment, not a default. **Opportunity consequence:** none directly — no new opportunity arises because no correction occurs. **Protocol:** a refused correction request is itself material (`EM-GOV-005`). **Dependency:** ⚠️ **`SCB-9` — see §0.1.**

### `SCB-2` — Extension · **RULED HERE, with two consequences**

**Question:** may the end move later while the start is kept? **Ruling:** **permitted in the normal governed case.** **Provenance:** position stated in this commission. **Consequences:**
* ⚠️ **`SCB-7` is the carve-out from this rule, not a separate subject:** extension is permitted *normally*, and **once votes exist the ordinary authority ends.** The two are consistent only when read that way.
* ⚠️ **`EM-OPEN-027` is substantially answered.** That question asked whether an "end tolerance" is a window extension by another name. **With extension permitted, an end tolerance would be a *pre-authorized* extension** — it stops being a new power and becomes a scheduling of an existing one. **Flagged, not closed.**
* **Not stated and not assumed:** whether extension applies equally to non-voting phases. **The reason for restricting voting extension (votes exist) has no analogue in administration.**

### `SCB-3` — Shortening · **RULED HERE**

**Question:** may the end move earlier? **Ruling:** **no — not normally.** **Provenance:** position stated in this commission. ⚠️ **The word *"normally"* implies an exception path that is nowhere defined.** *(Compare `SCB-7`, where the exception is named.)* **Recorded as an open edge, not invented.**

> ⚠️ **Consequence of `SCB-3` + `SCB-5` together, and it is significant: delays can never be absorbed.** Because a later phase may not be shortened, **a delay in one phase cannot be compensated by compressing a subsequent one — every delay propagates forward through the whole schedule.** **Neither rule says this on its own; together they say it.**

### `SCB-4` — Backward movement · **PART RULED, PART OPEN (§0.2)**

**Ruled:** **the election lifecycle may not return to a phase that has legitimately completed.** **Provenance:** position stated in this commission. ⚠️ **Cross-consequence: this REMOVES one of `EM-OPEN-042`'s candidate recovery paths** — *"restart from an earlier phase"* is excluded. **Recorded so that option is not later re-offered.**
**Still OPEN:** **may a window's time be moved EARLIER than published?** *(Distinct from lifecycle rollback; the commission separates the two and rules only one.)* **Governance's earlier candidate derivation stands unadopted: a corrected schedule beginning before its own correction would be a commitment made after the time it commits to.**

### `SCB-5` — Dependent phases · **RULED HERE under express delegation** *(the commission invites Governance to state it as a rule)*

> **A schedule correction affects only those opportunities whose validity or timing is actually affected by the correction. Later phases that are not affected keep their published schedules and their existing opportunities. No later opportunity is superseded merely because an earlier phase was corrected.**

**Provenance: NEW POLICY, adopted under the commission's express invitation.** ⛔ **The circularity is broken in the correct direction: this ruling COMPLETES `EM-GOV-013`'s dependent-phase clause; `EM-GOV-013` was not used as evidence for it.**

**Applied to the commission's example:** Administration 1–5 → 1–8; Candidacy 6–10 **now overlaps days 6–8**, so its **timing is actually affected** → it must be rescheduled. Voting 11–12 is affected **only if** the rescheduled Candidacy reaches into it. ⚠️ **And because `SCB-3` forbids shortening, Candidacy cannot be compressed to absorb the delay — so in practice the cascade usually does reach Voting.**

**Criterion offered for confirmation, not assumed:** a later phase is *affected* when **its window overlaps the corrected window**, or **its validity depends on the completion of the corrected phase**. **Anything narrower would need stating; anything broader would supersede opportunities for no reason.**
**Each affected phase receives its own new schedule and its own new opportunity** (`EM-VOC-005`); **unaffected phases are untouched.**

### `SCB-6` — Completed phases · **DERIVED, and confirmed**

**A legitimately completed phase remains historical: a later correction does not rewrite its schedule, its completion, or its protocol history.** **Provenance: DERIVED** from `EM-GOV-005` and `D-2` — **this confirms rather than creates.** **Governance states it explicitly, as instructed, so Architecture does not have to conclude it.**

### `SCB-7` — Votes already cast · **RULED HERE — and it raises the two hardest questions in the set**

**Ruled:** **once voting has started or votes exist, the Election Chief has NO ordinary authority to extend the voting period.** An **exceptional** extension may proceed through a Service Provider exception process: *Chief requests → Service Provider evaluates → approves or rejects → if approved, the extension is governed and fully protocolled.* **The protocol must distinguish: the Chief's request · the reason · the Service Provider's decision · approval or rejection · the resulting schedule · the affected opportunity · the resulting authorization and progression consequences.**

> ### ⚠️ **7.1 · This inverts the principle adopted on 2026-08-15.**
> `EM-OPEN-047` settled that **the service provides operational parameters and reports facts; Election Governance decides electoral meaning.** **`SCB-7` gives the Service Provider an approve/reject power over an electoral act** — and the commission expressly forbids inventing its criteria, **so an actor outside the election, on unstated grounds, could permit or block an extension of voting.**
> **Two readings, and they are very different:** **(i)** the Service Provider decision is a **service gate** *(feasibility, contract, operational capacity)* and electoral authority still rests inside the election; **(ii)** the Service Provider holds a genuine **electoral veto**. **Governance does not choose. One line settles it.**

> ### ⚠️ **7.2 · What happens to the votes already cast?**
> **`EM-VOC-005` (ADOPTED): a correction to a published schedule creates a NEW opportunity; the previous is superseded.** **An extension is a correction. So an approved exceptional extension would supersede the opportunity under which votes were already cast** — **and no adopted rule says those votes remain valid in the successor opportunity.** `EM-VOT-005` says decisions never carry across; **votes are not decisions, and nothing addresses them.**
> **Either the extension must be an express exception to `EM-VOC-005` (the same opportunity, extended), or the status of votes already cast must be ruled.** ⛔ **This must not be left to Architecture: it decides whether an election's votes survive its own extension.**

### `SCB-8` — Credentials · **OPEN — and Governance states exactly what is missing**

**The commission's question — *"which credentials?"* — cannot be answered from governance material.** Search result, reported as fact:
* the Manifesto **references** credentials **three times and defines them nowhere**: §2 requires that *"entitlement, ElectionMembership, suspension, credentials and voter-participation rules remain OUTSIDE `ElectionConstitution`"*; **`EM-OPEN-006`** asks whether suspension must *"prevent credential issuance and invalidate an existing credential"* — **itself an open question**; and the tenancy ruling **`Q-TEN-2`** speaks of *"no voting credential/context → deny"*.
* **No adopted rule defines what a credential is, who issues one, when it becomes valid, or what invalidates it.**

> **`SCB-8` therefore stays OPEN, and what is missing is stated precisely: a governed definition of the credential(s) at issue — at minimum whether *voter* credentials, *officer* credentials, *candidate* entitlements, or vote-submission credentials are distinct objects with distinct rules.** ⛔ **Governance will not invent credential semantics, and `EM-OPEN-006` must be resolved with or before `SCB-8`.**

### `SCB-9` — Elapsed window · **AUTHORITY ADOPTED · conditions OPEN**

**Not reopened: `EM-GOV-004` permits correcting an elapsed window.** The twelve-step recovery model in the commission is **consistent with everything adopted** and Governance confirms the checks: *time elapsed ≠ cancellation · time elapsed ≠ automatic progression · elapsed → event → evaluation → halted progression → recorded reason → governed recovery.* **The protocol records; it does not decide.**
**Still OPEN — the three parts identified on 2026-08-15, none addressed by this commission:** **(a) notice** — nothing requires a corrected elapsed window to leave participants time to act *(visibility is not notice — `EM-OPEN-036`)* · **(b) staleness** — a window elapsed six months ago and one elapsed yesterday are the same act · **(c) cause** — a window that elapsed **because progression was halted** carries a recorded evaluation and reason; one that elapsed **because nobody acted** may carry no trace at all *(`EM-OPEN-031`)*.

---

## 2 · Final proposed wording — ready for adoption

> **`SCB-5` (RULED HERE, delegated):** *A schedule correction affects only those opportunities whose validity or timing is actually affected by the correction. Later phases that are not affected keep their published schedules and their existing opportunities. No later opportunity is superseded merely because an earlier phase was corrected.*
>
> **`SCB-6` (DERIVED, stated explicitly):** *A legitimately completed phase remains historical. A later schedule correction does not rewrite its schedule, its completion or its protocol history.*
>
> **`SCB-1`/`SCB-2`/`SCB-3`/`SCB-4`(lifecycle half)/`SCB-7`:** the commission's own wording, **subject to §0.1, §7.1 and §7.2.**

## 3 · Remaining open Governance questions

**`SCB-1` scope** *(§0.1 — blocks the whole set)* · **`SCB-3`** the undefined *"normally"* exception · **`SCB-4`** schedule moved earlier · **`SCB-7.1`** service gate or electoral veto · **`SCB-7.2`** the status of votes already cast · **`SCB-8`** + `EM-OPEN-006` credential definition · **`SCB-9`(a)(b)(c)** notice, staleness, cause · **`EM-OPEN-027`** end tolerance *(substantially answered by `SCB-2`)* · **`EM-OPEN-035`** four-eyes · **`EM-OPEN-036`** informed vs visible · **`EM-OPEN-042`** recovery actions *(now with "restart from an earlier phase" excluded by `SCB-4`)*.

## 4 · What `EM-GOV-013` still needs

**`SCB-5` completes its dependent-phase clause.** **It additionally needs `SCB-1`'s scope confirmed (§0.1), because rescheduling a halted phase IS a forward movement.** `SCB-2`, `SCB-3`, `SCB-6` and `SCB-9`'s authority are settled enough for it. **`SCB-7`, `SCB-8` and `SCB-9`(a)(b)(c) do not block it**, since they constrain particular corrections rather than the recovery mechanism itself.

## 5 · Session status

**Architecture — ⏸️ PAUSED.** **Session 3 — 🛑 STOPPED, no implementation authority.** **Session 1 — must not verify unauthorized implementation.** **No technical representation decision was made in this report.**

**Traceability.** `EM-GOV-004`/`005`/`007`/`009`/`010`/`011` · `EM-GOV-013`/`014` *(pending)* · `EM-VOC-004`/`005` · `EM-VOT-004`/`005` · `D-2` · `P-2H` · `Q-TEN-2` · `EM-OPEN-006`/`027`/`031`/`035`/`036`/`042`.
