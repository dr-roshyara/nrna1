# UC-4 / `GREEN-5` — **PO/ARB scoping decision brief**

**Work item:** `EM-IMPL-002` · **Epic:** `PBDIGIT-EPIC-03` · **Subject:** the UC-4 `ReportPeriodExpiry` slice (the code's `GREEN-5`)
**Date:** 2026-09-30 · **Prepared by:** `claude-code-session:84c0f6f6-795e-4c89-a382-733f2c7b7caf` — self-declared, not attestable (`INV-ATTR-1/2`, `G-2`)
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)

> ⛔ **THIS DOCUMENT DECIDES NOTHING.** It prepares one bounded decision for the human PO/ARB. No option is selected, no implementation is authorized, `BND-1`/`BND-3` are untouched, and `app/` and `tests/` were not modified. **Evidence is not a decision; analysis is not authorization** (`R-34`/`EP-02`).

> ⚠️ **Independence disclosure, stated because it is material.** This is the **same process** that produced `PBDIGIT-73` (commit `7ee452ee0`). It was asked to verify that report's evidence independently, and it did — against the repository, not against its own conclusions. **It found four contradictions in its own prior report (§2.6) and one in the repository (§2.7).** A brief prepared by the author of the report it re-checks is weaker evidence than one prepared by a fresh process; the PO/ARB should weigh that, and §2.6 is offered as the test of whether the re-check was genuine.

---

## 1 · Current state

| Item | State, verified 2026-09-30 |
|---|---|
| `ADR-1` `ADR_20260817_2145_Aggregate_Absence_Semantics` | ✅ **DECIDED** (PO/ARB 2026-08-18) — *governed composite absence semantics*. **Implementation authority NOT granted.** |
| `ADR-2` `ADR_20260817_2300_Recovery_Origin_Provenance_Ownership` | ✅ **DECIDED** (PO/ARB 2026-08-18) — causal-model ruling. **Implementation authority NOT granted.** |
| **Rule-8 gate on `ADR-1`** | ⛔ **BLOCKED** — *"THE NORMALIZATION SLICE IS BLOCKED. A SEPARATE DOMAIN SLICE IS REQUIRED FIRST."* |
| **Rule-8 gate on `ADR-2`** | 🔴 **BLOCKED** — *"Six B-class dependencies. Zero of them are application work."* |
| `AbsentAggregateReferenceRedTest` (the binding pin) | 🔴 **RED today** — 1 test, 2 assertions, 1 failure. Two sites, both `FillCommitteeSeatHandler` |
| `BND-1` (lifecycle-phase ownership) | ⏸️ **DEFERRED** (`D3`, PO/ARB 2026-08-18, verbatim) — *"open, not resolved, rejected, or merged"* |
| `BND-3` (overlay boundary) | ⏸️ **DEFERRED** (`D4`) — *"not before `BND-1`"* |
| **UC-4 / `GREEN-5`** | 🔴 **NOT IMPLEMENTED, NOT AUTHORIZED** — no authorization record names it; handler throws `BadMethodCallException` at `:33` |
| `H-2` w8 regression lock | ✅ **COMPLETE + independently verified** (2026-08-19). Re-run today: **OK, 3 tests / 21 assertions** |
| `PBDIGIT-73` | ✅ committed `7ee452ee0` |

---

## 2 · Evidence verified independently

### 2.1 · `GREEN-5`'s meaning in the code — six pending guards, re-read

```
Handler/ReportPeriodExpiryHandler.php:33            GREEN-5 pending: UC-4 behaviour
Handler/RecordCommitteeConstitutionHandler.php:29   GREEN-6 pending: UC-5 behaviour
Query/GateIntervalStateQuery.php:29                 GREEN-7 pending: UQ-1
Query/ProgressionEligibilityQuery.php:28            GREEN-7 pending: UQ-2
Query/ClockReadingQuery.php:28                      GREEN-7 pending: UQ-3
Query/RecoveryPeriodStatusQuery.php:27              GREEN-7 pending: UQ-4
```

⇒ **In the code, `GREEN-5` = UC-4 `ReportPeriodExpiry`.** Confirmed by the handler's own docblock: *"UC-4 handler (GREEN-1 surface; behaviour arrives in GREEN-5)"*.

### 2.2 · The four UC-4 RED tests, and the gate in each

| UC-4 RED test | Gate seeded? | Positions expressed on it? | Exercises an **absent** gate? |
|---|:--:|:--:|:--:|
| `test_an_expired_halted_recovery_report_appends_the_terminal_consequence` | ✅ `seedGate(3)` | ✅ two `Object` → halt by decision | ❌ **no** |
| `test_an_expired_restoration_report_appends_the_election_level_cancellation` | ✅ `seedGate(3)` | ❌ none | ❌ **no** |
| `test_q3_an_unexpired_report_is_refused_and_recorded_as_a_refusal_never_a_fact` | ✅ | — | ❌ **no** |
| `test_a_halted_branch_report_while_inoperative_is_refused` | ✅ | — | ❌ **no** |

`seedGate()` (`OperatingCoreApplicationTestCase.php:128-138`) builds `AcceptanceGateDecision::establish(...)` and calls `$this->gates->seed($decision)`.

> ✅ **CONFIRMED: no current UC-4 RED test exercises `AcceptanceGateDecisionRepository::find()` returning `null`.** All four establish a present gate.

⚠️ **And a limit on what that proves.** In test 2 the gate is seeded but **no positions are expressed on it** and no assertion depends on it — the branch is determined by `PeriodKind::CommitteeRestoration` plus committee vacancies. So the fixtures do **not** establish that UC-4's behaviour *needs* the gate; only that when UC-4 runs, one is present. **Whether UC-4's implementation must read the gate at all is undetermined by the REDs** and belongs to the UC-4 design, not to this brief.

### 2.3 · Where `AcceptanceGateDecision` is actually used

**Application layer — six files**, not one:

```
Handler/ExpressCommitteePositionHandler.php   (UC-1)
Handler/RecordVacancyEventHandler.php         (UC-2)
Handler/FillCommitteeSeatHandler.php          (UC-3)
Handler/ReportPeriodExpiryHandler.php         (UC-4 — surface only)
Query/GateIntervalStateQuery.php              (UQ-1)
Query/ProgressionEligibilityQuery.php         (UQ-2)
```

Domain: `Repository/AcceptanceGateDecisionRepository.php` · `Gate/AcceptanceGateDecision.php`.

### 2.4 · What the pin reports, and why its silence about UC-4 proves nothing

The pin analyses **per method body**, **command handlers only**, and flags a possibly-absent reference only where it is **dereferenced without an explicit decision**. Today it reports exactly:

```
FillCommitteeSeatHandler.php: $committee (from a repository find())          :105
FillCommitteeSeatHandler.php: $decision  (from establishedAcceptanceDecision()) :106, helper :206
```

> ⚠️ **`ReportPeriodExpiryHandler` cannot appear in that list, because its `handle()` body only throws.** There is no `find()` call to flag. **The pin's silence about UC-4 is evidence that UC-4 is unimplemented — not evidence that UC-4 is absence-safe.** Any brief that reads it the other way is wrong, and §2.6(d) records that this brief's predecessor came close to doing so.

### 2.5 · What `ADR-1` already decides, and what it leaves open

**§6(a) governed composite absence semantics** gives `AcceptanceDecision` **two** meanings:

| Referenced concept | Governing condition | Meaning of absence |
|---|---|---|
| `AcceptanceDecision` | the command requires an already-established constitutional decision | **violation** of the required-decision invariant |
| `AcceptanceDecision` | the election has not yet reached the lifecycle phase in which an acceptance decision exists | **legitimate lifecycle state** |

**§6(b) + `6-guard`:** the Application *may detect* absence but *may not classify its cause* unless that classification *"is already represented by an authorized domain contract."* And: *"If the required domain contract does not exist, that is a DOMAIN-MODEL GAP, not permission for the Application layer to invent the meaning."*

**Binding constraint ⑥:** *"if the required domain contract does not exist, implementation stops and a separate domain slice is required."*

**§6(c) normalization scope:** *"Authorized: ONE bounded normalization slice covering **UC-1, UC-2 and UC-3** — subject to the Rule-8 authorization/dependency gate."* ⇒ **UC-4 is outside the authorized normalization scope.**

**The Rule-8 gate on `ADR-1` then found the required contract does not exist:**

| | Question | Answer |
|---|---|---|
| **Q1** | a consumable contract for Committee required-existence? | ⛔ **NONE** |
| **Q2** | a contract **discriminating the two `AcceptanceDecision` meanings**? | ⛔ **NONE** — *"§6(a) now names two distinct meanings for the same reference, so something must distinguish them, and nothing does"* |
| **Q3** | do `P-1…P-7` accept or return an absence/existence concept? | ⛔ **No** — not one |
| **Q4** | is `RecoveryProcess` absence already conformant? | ✅ **Yes** — must not be changed for symmetry |
| **Q5** | what does the pin still report? | both `FillCommitteeSeatHandler` sites, unresolved |

> **Q2 *is* `BND-1`.** The discriminator `ADR-1` §6(a) now requires is exactly the lifecycle-phase owner that `D3` deferred.

### 2.6 · ⚠️ Contradictions with `PBDIGIT-73` — recorded, not silently corrected

| | `PBDIGIT-73` said | Verified 2026-09-30 |
|---|---|---|
| **(a)** | *"`AcceptanceDecision` appears in **exactly one** application file"* | ❌ **Wrong as stated.** The bare string `AcceptanceDecision` appears in one file, but the **actual domain type is `AcceptanceGateDecision`**, present in **six** application files including UC-4's (§2.3). The original grep searched the wrong token. |
| **(b)** | *"`FillCommitteeSeatHandler` (**UC-1**, already GREEN-2)"* | ❌ **Wrong.** Per `ADR-1` §1.1, `FillCommitteeSeatHandler` is **UC-3** (GREEN-4). **UC-1 is `ExpressCommitteePositionHandler`.** |
| **(c)** | (not addressed) | ➕ **`ADR-1` is DECIDED and its Rule-8 gate is BLOCKED.** `PBDIGIT-73` did not cite either, and framed the scoping question as if the absence ruling were still open. **This is the most consequential omission** — see §2.8. |
| **(d)** | *"UC-4's pinned behaviour does not require `BND-1`"* | ⚠️ **True but over-read.** Correct that no UC-4 RED exercises absence. **Incorrect to treat that as UC-4 being absence-safe** — the pin cannot see an unimplemented body (§2.4), and the REDs do not establish that UC-4 needs the gate either way (§2.2). |
| **(e)** | *"materially better news"* · *"far cheaper"* | ⚠️ **Leaning.** That framing edges toward recommending the cheaper option, which this commission forbids. **Withdrawn from this brief.** §4 and §5 are stated without preference. |

**`PBDIGIT-73`'s central finding survives all five corrections:** `GREEN-5` does label two different slices, and `ADR-1` §6(c) now **independently confirms** it — the normalization slice's authorized scope is **UC-1/UC-2/UC-3**, which does not include UC-4.

### 2.7 · ⚠️ A contradiction inside the repository, not of my making

`AbsentAggregateReferenceRedTest`'s docblock states the absence-semantics ADR is one *"whose decision block is blank and is the PO's."* **That is stale.** `ADR-1` §6 records the PO/ARB ruling of 2026-08-18, and its header reads *"✅ DECIDED"*. The pin's prose still describes the pre-decision world.

⛔ **Not repaired here** — the pin is a binding artifact and modifying it is outside this task's scope. **Recommended as a separate one-line doc fix**, because a reader who trusts the docblock will believe the ruling is still open.

### 2.8 · The recorded sequence this decision would touch

The `ADR-1` Rule-8 gate (2026-08-18, §5) records a **governing sequence**:

```
ADR-1 signed ✅
   → Rule-8 gate: CONTRACT ABSENT
   → PO/ARB authorization for a DOMAIN slice      ← next act, and it is the PO's
        → domain RED → domain implementation → verification
   → the bounded normalization slice (UC-1/UC-2/UC-3, per §6(c))
        → AbsentAggregateReferenceRedTest becomes GREEN
   → GREEN-5
```

> ⚠️ **`GREEN-5` is recorded as the LAST step of a four-step chain.** So the question in §3 is **not** free-standing: answering it "yes" would place UC-4 **earlier** than this record sequences it, and that is an amendment to a recorded governance sequence — cheap or not, it is not a neutral act. **The PO/ARB should decide it knowing that.** `PBDIGIT-73` did not surface this, because it had not read this gate.

---

## 3 · The exact decision required

> # **May UC-4 assume that an established `AcceptanceGateDecision` exists?**

**This is a governance/scoping decision.** It is **not** an implementation decision · **not** a decision about `BND-1` · **not** about aggregate ownership · **not** about persistence architecture · **not** permission to implement UC-4.

---

## 4 · Option A — **YES**, UC-4 may assume an established `AcceptanceGateDecision`

**Consequences if chosen:**

- The assumption becomes an **explicit recorded scope boundary** of the UC-4 slice — not an implicit implementation habit. `ADR-1` §6(b) and `6-guard` still forbid the handler *classifying* a `null`; "assume present" must be recorded as a **command precondition**, never written as `if ($gate === null) { /* decide */ }`.
- `BND-1` **remains deferred**, exactly as `D3` left it. Nothing about lifecycle-phase ownership is settled.
- **No absence semantics are invented.** `ADR-1` §6(a) continues to govern absence wherever it is actually encountered.
- The **existing four RED tests remain authoritative** and unchanged; they already match this scope.
- UC-4 may then be **separately commissioned** through the normal `authorization → RED (existing) → GREEN → independent verification → STOP` path — the pattern `EM-IMPL-002` already ran for GREEN-1…GREEN-4.
- ⚠️ **It amends the recorded sequence in §2.8**, moving `GREEN-5` ahead of the normalization slice. That amendment should be recorded as such.
- ⚠️ **Residual risk to state plainly:** if UC-4's implementation turns out to need the gate on a path where absence is genuinely reachable, the slice re-enters `ADR-1` §6(b)/constraint ⑥ and **stops mid-slice**. The scope boundary is a bet on UC-4's design that only the UC-4 design can confirm (§2.2).

⛔ **Do not implement this consequence now.**

---

## 5 · Option B — **NO**, the absence case must be defined first

**Consequences if chosen:**

- **UC-4 cannot proceed under the current scope.** `GREEN-5` stays blocked.
- The missing absence semantics are handled **per `ADR-1`** — which is already decided; what is missing is the **contract that exposes the meaning**, not the meaning itself.
- **Does this enter the `ADR-1` §6(a) problem?** ✅ **Yes, and specifically its Q2 half.** The Rule-8 gate found **no contract discriminating** *required-but-absent* (violation) from *not-yet-reached-phase* (legitimate lifecycle state). **That discriminator is `BND-1`.**
- **The required separate decision is therefore:** a **PO/ARB authorization for a DOMAIN slice** to create the missing contract — the act the Rule-8 gate §5 names as *"next act, and it is the PO's"*. And because the discriminator is `BND-1`, that slice cannot be designed without either resolving `BND-1` or bounding the slice so it does not need the discriminator.
- ⛔ **No fallback value, sentinel, `Unknown`, exception meaning or lifecycle interpretation may be invented** — `ADR-1` §6(b), `6-guard`, constraints ①–⑨ all forbid it, and `§4`'s shape question is still open.

⛔ **Do not resolve that issue during this task.**

---

## 6 · The two `GREEN-5` meanings — use descriptive names

> **`GREEN-5` is currently overloaded.**

| Descriptive name | What it is | Authorized scope |
|---|---|---|
| **UC-4 / `ReportPeriodExpiry` slice** | the code's `GREEN-5`; handler at `ReportPeriodExpiryHandler.php:33` | **none** — not authorized |
| **`ADR-1` §6(c) `AcceptanceDecision` normalization slice** | what the Act-B design map called `GREEN-5` | scope authorized for **UC-1/UC-2/UC-3**; ⛔ **Rule-8 gate BLOCKED** |

⛔ **Do not renumber committed RED guards or alter historical artifacts.** The collision is a **traceability problem**, not authorization to rewrite the implementation plan. Guides `02`–`05` are committed against the code's numbering.

---

## 7 · `BND-1` protection

> **This decision does not resolve `BND-1`.**

If the PO/ARB chooses *"assume present"*, `BND-1` remains **exactly as `D3` deferred it** on 2026-08-18 — open, not resolved, not rejected, not merged with `EM-OPEN-055`. ⛔ **Do not infer that because UC-4 may proceed, lifecycle-phase ownership has been solved.** `D3`'s own terms forbid a provisional solution for the missing discriminator.

---

## 8 · `PBDIGIT-72` / `EM-GOV-063` stays separate

`PBDIGIT-72` records **two independent causes** of unreachability:

1. **UC-4 is not implemented** — touched by this decision;
2. **no authorized producer exists for the required `HaltedAtGate`** — **not** touched by this decision.

> ⛔ **Authorizing or implementing UC-4 would NOT automatically make `EM-GOV-063` reachable.** The second cause is independent and remains `PBDIGIT-72`'s. **Do not merge the two causes into this decision.**

---

## 9 · Explicit non-decisions

⛔ No option selected · no implementation authorized · `BND-1` not resolved · `BND-3` not resolved · `ADR-1`/`ADR-2` not amended or reinterpreted · Act B not reopened · `GREEN-*` numbering unchanged · no RED test added, changed or removed · no new abstraction created · no domain contract designed, named or placed · the stale pin docblock (§2.7) not repaired · `PBDIGIT-72` not extended · `app/` and `tests/` **byte-untouched**.

## 10 · Recommended governance action

> ## **PO/ARB must choose. Engineering must not.**

This brief supplies evidence and the two option shapes. It deliberately offers **no recommendation between A and B**, and §2.6(e) withdraws the leaning language its predecessor used. Cost is not an argument this process is entitled to convert into a recommendation.

**Next actor: the human PO/ARB.**

---

## 11 · Proposed decision-record wording — to accept, amend or reject

> ### PROPOSED WORDING — Option A · NOT DECIDED
>
> *"UC-4 (`ReportPeriodExpiry`) MAY assume that an established `AcceptanceGateDecision` exists. This assumption is recorded as an explicit scope boundary of the UC-4 slice and as a command precondition — it is NOT authority for the Application layer to classify an absent reference, which `ADR-1` §6(b) and the 6-guard continue to forbid.*
>
> *`BND-1` remains DEFERRED exactly as recorded in `D3`; nothing here resolves lifecycle-phase ownership, and no provisional discriminator is authorized. `BND-3` remains DEFERRED. `ADR-1` and `ADR-2` are unamended. The `ADR-1` §6(c) normalization slice (UC-1/UC-2/UC-3) remains BLOCKED at its Rule-8 gate and is unaffected.*
>
> *I acknowledge that this places `GREEN-5` earlier than the sequence recorded in the `ADR-1` Rule-8 gate §5, and I amend that sequence to that extent only.*
>
> *This wording is NOT implementation authorization. The UC-4 slice requires its own authorization, and must run existing REDs → GREEN → independent verification → STOP. If UC-4's design proves to require the gate on a path where absence is reachable, the slice STOPS and returns for a further decision."*

> ### PROPOSED WORDING — Option B · NOT DECIDED
>
> *"UC-4 (`ReportPeriodExpiry`) may NOT assume a present `AcceptanceGateDecision`. The absence case must be defined first.*
>
> *This enters the `ADR-1` §6(a) absence-semantics problem at its Q2 half: no domain contract discriminates 'required-but-absent' from 'not-yet-reached-phase'. That discriminator is `BND-1`.*
>
> *The required next act is a PO/ARB authorization for a DOMAIN slice to create the missing contract, as the `ADR-1` Rule-8 gate §5 records. No fallback value, sentinel, `Unknown`, exception meaning or lifecycle interpretation is authorized, and `ADR-1` §4's shape question remains open.*
>
> *`GREEN-5` remains blocked. `BND-1` and `BND-3` remain DEFERRED unless separately and explicitly addressed."*

---

## 12 · Outcome

> ### **Decision brief prepared; PO/ARB decision pending.**

**STOP.** No implementation · no application-code change · no test change · no RED created · no GREEN claimed · UC-4 **not** authorized.

**Traceability:** `ADR_20260817_2145_Aggregate_Absence_Semantics.md` §1.1/§4/§4a/§6(a)(b)(c)(d)/`6-guard`/`6a`/`6b`/constraints ①–⑨ · `ADR_20260817_2300_Recovery_Origin_Provenance_Ownership.md` (DECIDED) · `2026-08-18-EM-IMPL-002-rule8-gate-adr1.md` §1–§5 · `2026-08-18-EM-IMPL-002-rule8-gate-adr2.md` · `2026-08-18-EM-DOM-001-decision-recording-surface.md` (`D1`–`D4`) · `2026-08-18-EM-DOM-001-phase2a-act-b-domain-design-map.md:276` · `PBDIGIT-73` (`7ee452ee0`) · `PBDIGIT-72` · `ReportPeriodExpiryHandler.php:33` · `ReportPeriodExpiryHandlerRedTest.php` · `AbsentAggregateReferenceRedTest.php` · `OperatingCoreApplicationTestCase.php:128-138` · `R-34`/`EP-02` · Rule 8

---

## 13 · ⚠️ SUPERSEDED IN PART (`ES-004.3`) — see the decision record §11

**Date:** 2026-09-30, after a fresh-context cross-check whose findings were each re-verified from source.
**Authoritative corrections:** `2026-09-30-UC-4-GREEN-5-decision-record.md` **§11**.

| | This brief said | Corrected |
|---|---|---|
| **`M-1`** | §1: *"UC-4 / `GREEN-5` — 🔴 NOT AUTHORIZED — no authorization record names it"* | ❌ **Wrong.** UC-4 is **authorized-but-HELD** — inside the `EM-IMPL-002` grant (`…boundary-authorization-registration.md:19`), named in the bounding proposal (`:61, :114, :251, :281`), and *"ON HOLD"* / *"NOT YET"* per the hold records. ⇒ **§2.8's "amendment" framing is reopened: amendment vs hold release is now part of what the PO decides, and this brief was not entitled to settle it.** |
| **`M-2`** | §4 bullet 7 framed the pin only as a *residual risk* on *"a path where absence is genuinely reachable"* | ⚠️ **Incomplete.** The pin globs **every** handler file (`:54`, `:99`) and triggers on a `find()`-plus-dereference **shape**, not on reachability. It is a **fifth** binding test and UC-4 enters its scope the moment it has a body. All three guard shapes lead somewhere constrained — see §11 `M-2`. |
| **`M-3`** | §8 said the `HaltedAtGate` cause is *"not touched by this decision"* | ⚠️ **True but the converse was missed, and it is the operative one.** `ExpiryConsequence::onHaltedRecoveryExpiry()` **requires** `HaltedAtGate`, and UC-3's handler records that **no authorized port can supply one**. ⇒ **UC-4's own first RED cannot go GREEN**, so §4's *"existing four REDs → GREEN"* path is not achievable as written. |
| **`m-4`** | *"all four UC-4 RED tests"* | **Seven** UC-4 exercises exist suite-wide (plus `HistoryKindAssignmentRedTest` scenarios E/F and `RefusalTaxonomyRedTest`). **All seven seed a present gate; none pins absence** — conclusion strengthens, count was wrong. |
| **`m-5`** | §6: *"what the Act-B design map called `GREEN-5`"* | **Overstated.** `…design-map.md:276` is a table row with two items **sharing a blocker**, not an equation; `:240`/`:308` treat them as distinct. The collision is real in **planning prose**; the design map does not define `GREEN-5` as the normalization slice. |

**What survives:** every other verified item in §2, and the corrected record in §2.6. **§3's decision question is unchanged.**
