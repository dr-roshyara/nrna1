<!-- Created 2026-09-28: a dedicated context log for PublicDigit-specific engineering
work (the product itself, distinct from KnowledgeOS governance/theory-research work,
which continues to live in CONTEXT.md). Started empty deliberately -- the existing
CONTEXT.md and its archive (CONTEXT-ARCHIVE-2026-07-08.md) contain no separable
PublicDigit-only history worth migrating (checked directly: 0 purely-PublicDigit
blocks in the active period, 8 small blocks / ~106 lines in the archived period,
judged not worth splitting out after this file was created empty by explicit choice).
Log new PublicDigit-specific engineering entries here going forward. -->

# Current Working State — PublicDigit

**Updated:** 2026-09-30 *(additive — the **UC-4/`GREEN-5` PO/ARB scoping decision brief PREPARED, decision PENDING; `PBDIGIT-73` corrected additively** block is the newest; the `PBDIGIT-73` block below stands as history with its §9 corrections)*

---

## 📍 UPDATE (2026-09-30, **UC-4 / `GREEN-5` PO/ARB SCOPING DECISION BRIEF PREPARED — decision PENDING; `PBDIGIT-73` corrected additively (4 wrong claims, central finding survives); ⛔ nothing implemented, no option chosen** — newest block)

| | |
|---|---|
| 📄 **Brief** | `docs/publicdigit/reviews/2026-09-30-UC-4-GREEN-5-scoping-decision-brief.md` (placement derived, exit 0) — 12 sections; **no option selected** (`R-34`). |
| ❓ **The one decision** | **May UC-4 assume that an established `AcceptanceGateDecision` exists?** Governance/scoping only — not implementation, not `BND-1`, not aggregate ownership, not persistence. |
| 🔴 **What re-verification changed** | **`ADR-1` is ✅ DECIDED** (2026-08-18, governed composite absence semantics) and **its Rule-8 gate is ⛔ BLOCKED** — *"a separate DOMAIN slice is required first"*, because the contract §6(b) requires **does not exist**. `PBDIGIT-73` had cited neither. Gate **Q2** (no contract discriminating *required-but-absent* from *not-yet-reached-phase*) **is `BND-1`**. |
| ⚠️ **The sequence this decision would amend** | The gate's §5 records: `ADR-1 signed → Rule-8 gate → PO/ARB authorizes a DOMAIN slice → domain RED/impl/verify → normalization slice (UC-1/2/3) → pin GREEN → **GREEN-5**`. **`GREEN-5` is the LAST of four steps.** So the question is **not free-standing** — "yes" amends a recorded governance sequence. That is stated for the PO, not argued. |
| ✅ **Central finding CONFIRMED independently** | `ADR-1` **§6(c) authorizes the normalization slice for UC-1/UC-2/UC-3 only** ⇒ UC-4 is outside it ⇒ the two things called `GREEN-5` genuinely are different slices. The ADR corroborates `PBDIGIT-73` on its own terms. |
| ✅ **Also re-verified** | all four UC-4 REDs seed a **present** gate via `seedGate()`; **none** exercises `find()` → `null` · code's arc `GREEN-5`=UC-4 · `GREEN-6`=UC-5 · `GREEN-7`=UQ-1…4 · `H-2` **COMPLETE** (re-run: OK, 3/21) · pin still **RED** (1 test, 1 failure, both sites `FillCommitteeSeatHandler`) · `BND-1` **DEFERRED** (`D3`) · `BND-3` **DEFERRED** (`D4`) · `GREEN-5` **unauthorized** (no record names it) · `ADR-2` DECIDED, its Rule-8 gate also 🔴 BLOCKED (*"six B-class dependencies"*). |
| ⚠️ **`PBDIGIT-73` corrected (§9, `ES-004.3`)** | **C-1** `AcceptanceGateDecision` is in **six** application files, not one (the earlier grep searched the bare string `AcceptanceDecision`) · **C-2** `FillCommitteeSeatHandler` is **UC-3/GREEN-4**, not UC-1/GREEN-2 · **C-3** the pin **cannot flag an unimplemented body**, so its silence about UC-4 proves UC-4 is unwritten, not absence-safe · **C-4** *"far cheaper"* framing **withdrawn** — cost is not a recommendation engineering may make. Central finding, the `H-2` correction and the 16-RED mapping all **stand**. |
| ⚠️ **Repository contradiction found, NOT repaired** | `AbsentAggregateReferenceRedTest`'s docblock still says the absence ADR's *"decision block is blank"* — **stale since 2026-08-18**. Recommended as a separate one-line doc fix; the pin is binding and out of scope here. |
| 🧷 **Independence caveat disclosed** | The brief was prepared by the **same process** that wrote `PBDIGIT-73`. It re-derived from the repository and found four of its own errors (§2.6) — offered as the test of whether the re-check was genuine. A fresh process would be stronger evidence. |
| ▶️ **Next (human PO/ARB)** | Choose Option A (*may assume present*) or Option B (*absence must be defined first*). §11 carries proposed decision-record wording for each, to accept, amend or reject. If **A**: UC-4 needs its own authorization, then existing REDs → GREEN → independent verification → STOP. If **B**: the next act is PO/ARB authorization of a **domain slice** for the missing contract, which runs into `BND-1`. |
| 🚫 **Non-actions** | no option selected · no implementation · no RED added/changed/removed · **`app/` and `tests/` byte-untouched** · `BND-1`/`BND-3` untouched · `ADR-1`/`ADR-2` unamended · Act B not reopened · `GREEN-*` not renumbered · no domain contract designed/named/placed · stale pin docblock not repaired · `PBDIGIT-72` not extended (its `HaltedAtGate`-producer cause is **independent** and unaffected). |

---

## 📍 UPDATE (2026-09-30, **`PBDIGIT-73` RAISED — `GREEN-5` labels two different slices; `H-2` found already COMPLETE; the real gate on `GREEN-5` narrowed from `BND-1` to a one-line scoping question** — evidence recorded, ⛔ nothing implemented, no ruling made)

| | |
|---|---|
| 🎫 **Ticket** | `docs/publicdigit/backlog/PBDIGIT-73-green-5-labels-two-different-slices.md` (placement derived, exit 0) |
| 🔀 **The label collision** | **the code** says `GREEN-5` = **UC-4 `ReportPeriodExpiry` behaviour** (`ReportPeriodExpiryHandler.php:33`); **the Act-B design map** says `GREEN-5` = the **`ADR-1` §6(c) normalization slice**, blocked by `BND-1`/gate `G-6` (`…act-b-domain-design-map.md:276`). Two different slices, two different blockers, one label. The circulating roadmap carries the design map's reading. |
| 📐 **Code's real arc** | `GREEN-5` = UC-4 · `GREEN-6` = UC-5 · `GREEN-7` = UQ-1…UQ-4 — read from the six `BadMethodCallException` pending-guards, corroborated by guide 02's `Q-UC5` STOP "at GREEN-6". |
| 🎯 **Where the normalization concern actually lives** | `AcceptanceDecision` appears in **exactly one** application file — `FillCommitteeSeatHandler` (**UC-1**, already GREEN-2, `8ea13835a`). The failing pin names its two dereference sites, **both in that file** (`:105`, `:106`, helper `:206`). Not UC-4. |
| ✅ **§5.3 ANSWERED from evidence** | **All four UC-4 REDs seed a *present* gate** (`seedGate()` → `gates->seed()`, `OperatingCoreApplicationTestCase.php:128-138`); **none** exercises `find()` returning `null`. ⇒ **UC-4's pinned behaviour does not require `BND-1`.** The absence path is **unpinned**, so the real gate is the narrow question: **may UC-4 assume an established `AcceptanceGateDecision` exists, or must the slice pin absence first?** Far cheaper than `BND-1`. |
| ⚠️ **`H-2` is COMPLETE, not open** | The roadmap lists it 🟡 Open **twice** and calls it *"the immediate small step"*. Delivered 2026-08-19: RED `4da898886` → GREEN `3da3053d4` → **independently VERIFIED** `33f7316e7` → complete `29b8051b6`, with authorization + verification docs + guide 05. **Re-run today: OK, 3 tests / 21 assertions.** Commissioning a fresh `H-2` lane would redo verified work. |
| 🧪 **The suite is not broken** | `tests/Unit/Contexts/Election/` → **136 tests · 3,884 assertions · 15 errors · 1 failure**, and **all 16 map to planned slices**: UC-4 ×4 (`GREEN-5`) · UC-5 ×3 (`GREEN-6`) · queries ×5 (`GREEN-7`) · cross-cutting ×3 · **1 failure = the `ADR-1` §6(c) normalization pin** (`BND-1`-blocked; its own message forbids improvising the outcome). **"Existing unrelated failures" is wrong** — and invites someone to "fix" deliberate pins. |
| 🧭 **Roadmap steer accepted** | Do **not** reopen `BND-1` merely because Act B is complete; keep operational-status (B→C→D), normalization (`BND-1`→normalization slice) and `EM-GOV-063` reachability as separate tracks. That judgement is sound and unaffected by this finding. |
| ▶️ **Next (PO/ARB)** | Rule the scoping question. If *"may assume present"* → authorize the `GREEN-5` slice (four existing REDs → GREEN → independent verification → STOP), the pattern `EM-IMPL-002` already ran for GREEN-1…4. `PBDIGIT-72`'s UC-4 cause may then be clearable by an ordinary slice; its other cause (no authorized `HaltedAtGate` producer) is untouched. |
| 🚫 **Non-actions** | no UC-4/UC-5/query implementation · no scoping ruling (evidence only, `R-34`) · `BND-1`/`BND-3` untouched · no RED added or changed · **`app/` and `tests/` byte-untouched** · Act B not reopened · `ADR-1`/`ADR-2` not touched · `GREEN-*` guards not renumbered · `PBDIGIT-72` not extended · deliberate pins not "repaired". |
| 🗒️ **Housekeeping observed, not fixed** | the backlog holds **two `PBDIGIT-69` files** (`-operating-core-post-verification-items`, `-voter-eligibility-cache-is-tenant-unaware`). Duplicate id breaks traceability; recorded, **not renamed**. |

---
