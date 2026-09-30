# PBDIGIT-73 — **`GREEN-5` labels two different slices**, and the EM-IMPL-002 status picture is stale

**Type:** Finding (traceability / planning defect) · **Epic:** `PBDIGIT-EPIC-03` Election Management · **Work item:** `EM-IMPL-002`
**Created:** 2026-09-30 · **Raised by:** `claude-code-session:84c0f6f6-795e-4c89-a382-733f2c7b7caf` — self-declared, not attestable (`INV-ATTR-1/2`, `G-2`)
**Placement derived:** `php scripts/doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0)
**Status:** 🔴 **OPEN** · finding recorded, **no repair authorized, no slice implemented**

> ⛔ **This ticket records a measurement and a naming conflict. It implements nothing, authorizes nothing, and resolves neither `BND-1` nor `BND-3`.** `EM-IMPL-002` GREEN-5/6/7 remain unauthorized.

---

## 1 · The finding

**The label `GREEN-5` denotes two different slices in two authoritative sources, and they have different blockers.**

| Source | What it calls `GREEN-5` | Its stated blocker |
|---|---|---|
| **The code** — `ReportPeriodExpiryHandler.php:33` | **UC-4 `ReportPeriodExpiry` behaviour** | none stated; the handler is a GREEN-1 surface awaiting its slice |
| **The Act-B design map** — `2026-08-18-EM-DOM-001-phase2a-act-b-domain-design-map.md:276` | **the `ADR-1` §6(c) normalization slice** | 🔴 `BND-1`, gate `G-6` |

Both are load-bearing. The roadmap in circulation carries the design map's reading (*"GREEN-5 🔴 Blocked — BND-1 / AcceptanceDecision normalization remains unresolved"*), which means **a planner reading the roadmap concludes UC-4 is blocked by a lifecycle-phase ownership question that UC-4's handler does not reference.**

## 2 · Evidence — measured 2026-09-30, not inherited

**The code's own GREEN numbering**, read from the pending-behaviour guards:

```
Handler/ReportPeriodExpiryHandler.php:33          GREEN-5 pending: UC-4 behaviour
Handler/RecordCommitteeConstitutionHandler.php:29 GREEN-6 pending: UC-5 behaviour
Query/GateIntervalStateQuery.php:29               GREEN-7 pending: UQ-1
Query/ProgressionEligibilityQuery.php:28          GREEN-7 pending: UQ-2
Query/ClockReadingQuery.php:28                    GREEN-7 pending: UQ-3
Query/RecoveryPeriodStatusQuery.php:27            GREEN-7 pending: UQ-4
```

So the code's arc is **GREEN-5 = UC-4 · GREEN-6 = UC-5 · GREEN-7 = UQ-1…UQ-4**. This is corroborated independently by guide 02, which schedules the `Q-UC5` STOP "at GREEN-6", and by `ReportPeriodExpiryHandler`'s own docblock: *"UC-4 handler (GREEN-1 surface; behaviour arrives in GREEN-5)"*.

**Where the `ADR-1` absence-semantics concern actually lives.** `AcceptanceDecision` appears in **exactly one** file across the whole OperatingCore application layer:

```
app/Contexts/Election/Application/OperatingCore/Handler/FillCommitteeSeatHandler.php
```

— that is **UC-1**, whose behaviour landed as **GREEN-2** (`8ea13835a`, 2026-08-17). And the failing normalization pin names its two dereference sites explicitly, **both in that same file**:

```
FillCommitteeSeatHandler.php: $committee (from a repository find())          :105
FillCommitteeSeatHandler.php: $decision  (from establishedAcceptanceDecision()) :106, helper at :206
```

⚠️ **`ReportPeriodExpiryHandler` (UC-4) does inject `AcceptanceGateDecisionRepository`**, so its behaviour may still have to decide what an absent gate decision means — which would pull `ADR-1` in after all. **That is exactly what this ticket does NOT settle.** The label collision is proven; whether UC-4 is genuinely `BND-1`-free is a design question for the UC-4 slice's own analysis, and must not be asserted from the injection list alone.

## 3 · Two companion corrections to the circulating status picture

**(a) `H-2` is COMPLETE, not open.** The roadmap lists `H-2` as 🟡 Open, twice (its "Immediate" item 1 and its "Technical quality" item 7), and names it *"the immediate small step"*. It was delivered on 2026-08-19 as RED → GREEN → independent verification, exactly as proposed:

| | |
|---|---|
| RED | `4da898886` — fail-first shown by mutation in a throwaway export |
| GREEN | `3da3053d4` — passes on the unmutated tree; no production change required |
| Independent verification | `33f7316e7` — **VERIFIED, mutation reproduced** |
| Completion + honest scope | `29b8051b6` — records `O-3` |
| Artefacts | `…-H2-w8-regression-lock-authorization.md` · `…-H2-w8-lock-independent-verification.md` · guide `05_step_h2_w8_regression_lock.md` |
| **Re-run 2026-09-30** | `RestorationWithoutPriorHaltRegressionLockTest` — **OK, 3 tests, 21 assertions** |

⇒ **Commissioning a fresh `H-2` lane would redo verified work.** The D2/`w8` decision is already protected.

**(b) The Election suite's REDs are not "unrelated pre-existing failures".** Measured: `tests/Unit/Contexts/Election/` → **136 tests · 3,884 assertions · 15 errors · 1 failure**. Every one maps to an accounted-for planned slice:

| Count | Tests | Slice |
|---:|---|---|
| 4 | `ReportPeriodExpiryHandlerRedTest` | **GREEN-5** (UC-4) |
| 3 | `RecordCommitteeConstitutionHandlerRedTest` | **GREEN-6** (UC-5) |
| 5 | `QueryServicesRedTest` | **GREEN-7** (UQ-1…UQ-4 + purity) |
| 2 | `RefusalTaxonomyRedTest` | cross-cutting, pending the above |
| 1 | `HistoryKindAssignmentRedTest` | cross-cutting, pending the above |
| **1 failure** | `AbsentAggregateReferenceRedTest` | the **`ADR-1` §6(c) normalization pin** — `BND-1`-blocked, and it says so in its own message: *"the normalization slice that closes this pin is authorized there, never improvised here"* |

⇒ **15 + 1 = 16, all accounted for. The suite is exactly as RED as the plan says it should be.** Describing it as "existing unrelated failures" invites someone to "fix" deliberate pins.

## 4 · Impact

- **Planning reads the wrong blocker.** `BND-1` is a genuine open architecture decision (lifecycle-phase ownership, deferred by `D3` 2026-08-18 and never revisited). Attaching it to UC-4 makes UC-4 look gated on a strategic-DDD question, which may be depressing the priority of a slice that could be separately authorizable.
- **Wasted re-work risk** on `H-2`, which is verified and green.
- **Deliberate REDs risk being "repaired".** The normalization pin explicitly forbids improvising its outcome; a cleanup pass reading "1 failure" as a defect would breach `ADR-1` §6(b) (*a missing contract is a DOMAIN-MODEL GAP, not permission to interpret*).
- **`EM-GOV-063` reachability is affected.** `PBDIGIT-72` records two independent causes, one being *"UC-4 itself is not implemented"*. If UC-4 is not in fact `BND-1`-gated, one of those two causes is cheaper to clear than the roadmap implies — **a possibility to test, not a conclusion.**

## 5 · Recommendation

1. **Rename, do not renumber.** Keep the code's arc authoritative (`GREEN-5` = UC-4), and refer to the other thing by its own name — **`ADR-1` §6(c) normalization slice** — everywhere, never as "GREEN-5". Renumbering the code's guards would invalidate committed guides 02–05.
2. **Correct the status picture** for `H-2` (COMPLETE, verified, green) and for the suite's 16 REDs (planned, not unrelated).
3. **Commission a bounded UC-4 (`GREEN-5`) fit analysis** whose first question is exactly: *does UC-4 behaviour require deciding what an absent `AcceptanceGateDecision` means — and therefore `BND-1` — or not?* **Design only; no implementation.** If the answer is no, UC-4 becomes separately authorizable and `PBDIGIT-72`'s second cause narrows.
4. **Do not reopen `BND-1`** for this. The roadmap's own judgement — handle `BND-1` only when the normalization path is deliberately unlocked — is sound and unaffected.

## 6 · Explicit non-goals

❌ Not implementing UC-4, UC-5 or the queries · ❌ not resolving `BND-1` or `BND-3` · ❌ not touching the frozen Domain core, `ADR-1` or `ADR-2` · ❌ not repairing the deliberate RED pins · ❌ not renumbering the `GREEN-*` guards · ❌ not reopening Act B · ❌ not extending `PBDIGIT-72`.

## 7 · Related

`PBDIGIT-68` (parent ruling) · `PBDIGIT-72` (`EM-GOV-063` unreachable — shares the UC-4 cause) · `PBDIGIT-69-operating-core-post-verification-items` · Act-B design map §7 · decision-recording surface `D1`–`D4` (2026-08-18) · guides `election_operating_core/02`–`05`.

> ⚠️ **Housekeeping observed, not fixed:** the backlog contains **two** `PBDIGIT-69` files (`-operating-core-post-verification-items` and `-voter-eligibility-cache-is-tenant-unaware`). A duplicate id breaks traceability. Recorded here; **not renamed** — renumbering someone else's ticket is not this ticket's business.

**Traceability:** `app/Contexts/Election/Application/OperatingCore/Handler/ReportPeriodExpiryHandler.php:33` · `Handler/RecordCommitteeConstitutionHandler.php:29` · `Query/{GateIntervalState,ProgressionEligibility,ClockReading,RecoveryPeriodStatus}Query.php` · `Handler/FillCommitteeSeatHandler.php:105-106,206` · `tests/Unit/Contexts/Election/OperatingCore/RestorationWithoutPriorHaltRegressionLockTest.php` · `tests/Unit/Contexts/Election/OperatingCoreApplication/AbsentAggregateReferenceRedTest.php:84` · `docs/publicdigit/architecture/2026-08-18-EM-DOM-001-phase2a-act-b-domain-design-map.md:276` · `docs/publicdigit/reviews/2026-08-18-EM-DOM-001-decision-recording-surface.md` (`D3`) · commits `4da898886` `3da3053d4` `33f7316e7` `29b8051b6` `8ea13835a`

---

## 8 · §5.3's first question — ANSWERED from evidence (2026-09-30)

> **Question:** does UC-4 behaviour require deciding what an absent `AcceptanceGateDecision` means — and therefore `BND-1`?

### Answer: **No for its pinned scope — but the absence path is UNPINNED, and that is the real gate on `GREEN-5`.**

**Evidence.** All four UC-4 RED tests seed a **present** gate decision. `seedGate()` (`OperatingCoreApplicationTestCase.php:128-138`) constructs `AcceptanceGateDecision::establish(...)` and calls `$this->gates->seed($decision)`:

| UC-4 RED test | seeds a gate? |
|---|---|
| `test_an_expired_halted_recovery_report_appends_the_terminal_consequence` | ✅ `seedGate(3)` |
| `test_an_expired_restoration_report_appends_the_election_level_cancellation` | ✅ |
| `test_q3_an_unexpired_report_is_refused_and_recorded_as_a_refusal_never_a_fact` | ✅ |
| `test_a_halted_branch_report_while_inoperative_is_refused` | ✅ |

**Not one exercises `gates->find()` returning `null`.** The suite pins recovery-period expiry, the terminal consequence (`RecoveryPeriodExpired` with `recoveryFailed`, rendering *"Election Discontinued"* per `EM-GOV-069`), election-level cancellation on restoration expiry, and two refusal paths — plus `saveCount === 0` throughout (§4: UC-4 mutates no aggregate).

⚠️ **Correction to §2's caveat, recorded additively (`ES-004.3`).** §2 said the injection of `AcceptanceGateDecisionRepository` left open whether UC-4 needs the absence decision, and that it *"must not be asserted from the injection list alone."* That caution was right, and the test evidence now settles it: **the pinned behaviour needs a present gate, never an absent one.** §2's wording stands as written; this section supersedes its open question.

### What this changes

**`GREEN-5` is not gated on lifecycle-phase ownership.** It is gated on a much narrower scoping decision:

> **May UC-4 assume an established `AcceptanceGateDecision` exists — or must the slice pin what an absent one means first?**

| | |
|---|---|
| If **may assume** | a one-line scoping ruling; `GREEN-5` becomes separately authorizable with its four REDs unchanged. `BND-1` stays deferred and untouched. |
| If **must pin** | the absent case enters `ADR-1` §6(a) row-2-vs-row-3 territory ⇒ `BND-1` after all, and `GREEN-5` waits. |

**This is a materially cheaper question than `BND-1`**, and it is the one that actually stands between the current state and the next GREEN. Whoever rules it should note that `ADR-1` §6(b) forbids improvising the absence meaning, so *"assume present"* must be **recorded as a scope boundary**, not left implicit in an implementation.

**Consequence for `PBDIGIT-72` / `EM-GOV-063`.** That ticket records two independent causes, one being *"UC-4 itself is not implemented"*. UC-4's first RED asserts the terminal consequence directly (`recoveryFailed` + *"Election Discontinued"*). So **if the scoping ruling is "may assume present", that cause is clearable by an ordinary authorized slice** — the other cause (no authorized producer for the required `HaltedAtGate`) is untouched and remains `PBDIGIT-72`'s. ⚠️ **Stated as a consequence to test, not a claim that `EM-GOV-063` becomes reachable.**

### Still not done, and still not authorized

⛔ No implementation of UC-4 · ⛔ no scoping ruling made here — **this section supplies evidence, not the decision** (`R-34`: engineering never accepts its own work) · ⛔ `BND-1`/`BND-3` untouched · ⛔ no RED added or changed · ⛔ no production file modified.

### Next actor

**PO/ARB** — rule the scoping question above. Then, if *"may assume present"*: authorize the `GREEN-5` slice (four existing REDs → GREEN → independent verification → STOP), which is the pattern `EM-IMPL-002` already ran four times for GREEN-1…GREEN-4.

---

## 9 · ⚠️ ADDITIVE CORRECTION (`ES-004.3`) — four claims above are wrong; the central finding survives

**Date:** 2026-09-30 · **Trigger:** a commissioned independent re-verification of this ticket's own evidence, before preparing the PO/ARB scoping decision.
**Full brief:** `docs/publicdigit/reviews/2026-09-30-UC-4-GREEN-5-scoping-decision-brief.md` §2.6.

> **Nothing above is rewritten.** The corrections are recorded here so a later reader meets them with the claims they correct.

| | This ticket claimed | Correct position |
|---|---|---|
| **C-1** | §2: *"`AcceptanceDecision` appears in **exactly one** application file"* | ❌ **Wrong as stated.** The bare string appears once, but the **actual domain type is `AcceptanceGateDecision`**, present in **six** application files — UC-1, UC-2, UC-3, **UC-4**, UQ-1, UQ-2. The original grep searched the wrong token. |
| **C-2** | §2: *"`FillCommitteeSeatHandler` (**UC-1**, already GREEN-2)"* | ❌ **Wrong.** Per `ADR-1` §1.1 it is **UC-3** (GREEN-4). **UC-1 is `ExpressCommitteePositionHandler`.** |
| **C-3** | §8: *"UC-4's pinned behaviour does not require `BND-1`"* | ⚠️ **True but over-read.** No UC-4 RED exercises an absent gate — that stands. But **the pin cannot flag an unimplemented body**, so its silence about UC-4 is evidence UC-4 is unwritten, not that it is absence-safe. The REDs also do not establish that UC-4 *needs* the gate (test 2 seeds one but asserts nothing on it). |
| **C-4** | §8: *"materially better news"*, *"far cheaper"* | ⚠️ **Leaning.** Cost is not an argument engineering may convert into a recommendation. **Withdrawn**; the brief states both options without preference. |

### The omission that mattered most

**This ticket cited neither `ADR-1`'s ruling nor its Rule-8 gate**, and framed the scoping question as though absence semantics were still undecided. In fact:

- **`ADR-1` is ✅ DECIDED** (PO/ARB 2026-08-18) — governed composite absence semantics, §6(a) giving `AcceptanceDecision` **two** meanings.
- **`ADR-1` §6(c) authorizes the normalization slice for UC-1/UC-2/UC-3 only** — which **independently confirms this ticket's central finding**: UC-4 is outside that slice, so the two things called `GREEN-5` really are different slices.
- **The `ADR-1` Rule-8 gate is ⛔ BLOCKED** — *"a separate DOMAIN slice is required first"*, because the contract §6(b) requires does not exist. Its **Q2** — no contract discriminating *required-but-absent* from *not-yet-reached-phase* — **is `BND-1`**.
- That gate's §5 records a governing sequence in which **`GREEN-5` is the LAST of four steps**. So "may UC-4 assume present?" is **not free-standing**: answering yes amends a recorded sequence.

### Net effect on this ticket

✅ **The central finding STANDS and is strengthened** — `GREEN-5` labels two different slices, now corroborated by `ADR-1` §6(c)'s own scope statement.
✅ **The `H-2`-is-complete correction STANDS** (re-verified: OK, 3 tests / 21 assertions).
✅ **The 16-RED mapping STANDS.**
⚠️ **§8's framing is superseded** by the brief: the question is real and bounded, but it is not merely "cheap", and this ticket was not entitled to imply which way it should go.

**Status unchanged:** 🔴 OPEN · no repair authorized · no slice implemented · `BND-1`/`BND-3` untouched.

---

## 10 · ⚠️ SECOND ADDITIVE CORRECTION (`ES-004.3`) — from an independent-evidence cross-check

**Date:** 2026-09-30. **Authoritative detail:** `docs/publicdigit/reviews/2026-09-30-UC-4-GREEN-5-decision-record.md` §11.

| | This ticket said | Corrected |
|---|---|---|
| **`m-5`** | §1: the Act-B design map *"says `GREEN-5` = the `ADR-1` §6(c) normalization slice"* (citing `:276`) | **Overstated.** `:276` is a **table row listing two items that share a blocker**, not an equation, and the same document treats them as **distinct** at `:240` and `:308`. ⇒ **The collision is real in planning prose, but the design map does not itself define `GREEN-5` as the normalization slice.** |
| **`m-6`** | §2 line 44 and the traceability line cite commit **`8ea13835a`** for `FillCommitteeSeatHandler` | **Wrong commit.** That file is UC-3/GREEN-4 and landed in **`4651a3e76`**; `8ea13835a` is GREEN-2/UC-1. (§9 `C-2` fixed the UC number but left the commit.) |
| **`m-4`** | *"all four UC-4 REDs"* | **Seven** UC-4 exercises exist suite-wide. All seven seed a **present** gate; none pins absence. |
| **`M-1`** | (not addressed) | ➕ **UC-4 is authorized-but-HELD, not unauthorized** — inside the `EM-IMPL-002` grant and named in its bounding proposal. This ticket never claimed otherwise, but the brief built on that assumption; recorded here so the ticket and the brief stay consistent. |

> ✅ **The ticket's central finding stands after two rounds of correction.** `GREEN-5` **is** overloaded: the code defines it as **UC-4**, while `ADR-1` §6(c) scopes the normalization slice to **UC-1/UC-2/UC-3** — so the two are genuinely different slices. That now rests on the **ADR's own scope statement** rather than on the design-map row this ticket originally cited.
