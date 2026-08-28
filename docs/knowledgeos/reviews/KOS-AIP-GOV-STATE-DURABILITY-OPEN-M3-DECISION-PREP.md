# `OPEN-M3` — `KOS_MECHANISM_PATH` boundary classification · **DECISION-PREP, NOT THE DECISION**

**Work item:** `KOS-AIP-GOV-STATE-DURABILITY-ADR` · **Prepared by:** Governance (`b64828fe`) · 2026-08-19
> ⛔ **The act carries no selection.** Governance recommends; it does not decide. **One letter settles it.**

---

## 1 · ⚠️ The premise needs one correction — this is not new evidence

**The act states:** *"New evidence from migration planning: `KOS_MECHANISM_PATH` selects the mechanism used to interpret the governance record."*

**Verified: the CONDITION was recorded two days earlier**, in `2026-08-17-KOS-ATTR-ARCH-001-stage2-bounded-context-confirmation.md` as coupling problem **`C-2`**:

> **"Interpretation authority is environment-swappable — `KOS_MECHANISM_PATH` selects which AST-015 interprets the records … a context whose *authority* can be redirected by an env var has an unhardened perimeter."**
> **Class: `Declared` (documented as not hardened) · medium.**

⭐ **What the migration plan added is PRECISION, not discovery** — the two-axis formulation (`Axis 1` record location / `Axis 2` interpreting mechanism) and the line-addressed evidence `session-resolve.php:90`.

⭐ **And `C-2` already carries a route:** that document states *"`R-37` (governance precedes automation; mechanisms only after evidence of insufficiency) governs any remedy … Whether that evidence is sufficient is the ARB's call (`ADR-C6`)."*
⇒ **A named owner already exists for the remedy, and the migration plan has now supplied exactly the measured evidence of insufficiency `R-37` requires.**

## 2 · ⭐ The finding that most affects this decision — the delivered plan already presumes `A`

**Migration plan, Phase 5:** *"switch **all** writers and readers to the §3 resolver — `P-1`, `P-2`, `P-3a/b`, **`P-4`**."*
**`P-4` is `KOS_MECHANISM_PATH`.**

| Artifact | Position on the boundary question |
|---|---|
| **the migration plan** | **operationally assumes `A`** — it brings `P-4` under the resolver in Phase 5 |
| **the Governance review** | *"⛔ **NOT DETERMINED — and Governance must not determine it.**"* |

> ### ⚠️ **The two delivered artifacts are not aligned: the plan implements an answer the review says is not yet given.**
> **Consequence: if `B` or `C` is selected, Phase 5 must be revised** — it currently switches an axis whose governance would then be undecided or separately owned.

## 3 · The options, tested

| | Assessment |
|---|---|
| **A — same boundary** | ✅ **purposively right.** The Single Authority Resolver exists so that *"no hidden second authority source"* remains — and **a swappable interpreter IS a second authority source: the same bytes yield a different fold.** The plan's own phrase is the argument: ***"the right bytes read by the wrong mechanism."*** ⚠️ **But the adopted invariant's TEXT governs LOCATION** — *"resolve the authority evidence **location** through one governed resolution mechanism."* Reading interpretation into it silently is exactly the failure the v3 commission's Category B rule forbids: ***"their scope must not silently expand."*** |
| **B — new architectural boundary** | ⚠️ **overstates novelty.** `C-2` has existed since 2026-08-17 with a named remedy route (`ARB` / `ADR-C6` / `R-37`). **Declaring a *new* boundary risks creating a second owner for one problem** (`ES-005.4`) |
| **C — defer** | ⚠️ **defensible but costly here.** It fits *"record explicit owner and follow-up"* — and the owner already exists. **But deferring leaves Phase 5 invalid as written**, and leaves the interpreter axis ungoverned while the record axis is hardened |

## 4 · Recommendation

> ### **Recommended: `A` — but recorded as an EXPLICIT SCOPE EXTENSION, not as an application of the existing invariant.**

**The wording that makes `A` sound:**

> *"The Single Authority Resolver invariant is **extended** from one axis to two: it governs both the authority-record **location** and the **mechanism that interprets** that record. No component may embed an independent authority path **or an independent interpreter selection.**"*

**Why this form and not plain `A`:** plain `A` asserts the mechanism *was already inside* the decided boundary — which the adopted text does not support, and asserting it would be the silent expansion the estate has prohibited. **Extending it deliberately gets the same protection with none of the drift.**

**Why `A` over `B`/`C`:** it keeps the delivered Phase 5 valid, it closes the axis that would otherwise make byte-preservation worthless, and it avoids a second owner for a two-day-old declared problem.

⚠️ **One consequence to record either way:** whichever option is chosen, **`C-2`'s disposition must be reconciled** — either this decision **discharges** `C-2`'s mechanism-selection aspect, or `C-2` remains open in parallel under `ADR-C6`. ⛔ **Two owners for one coupling is the outcome to avoid.**

## 5 · Not decided here

⛔ **the selection** · migration execution · new repository location · ledger architecture · `C-10` · `D2` · `D6` · `D7` · `C-2`'s disposition · whether Phase 5 is revised.

**Traceability:** PO/ARB `OPEN-M3` act 2026-08-19 · migration plan `3817eb2b` §1.5 / §3 `INV-R3` / §4 Phase 5 / §11 · migration plan review `13bcfb49` (`OPEN-M3` four sub-questions; *"Governance must not determine it"*) · **`C-2` in `2026-08-17-KOS-ATTR-ARCH-001-stage2-bounded-context-confirmation.md` (`Declared`, `R-37`, `ADR-C6`)** · `session-resolve.php:90` · `G-KOS-GOV-STATE-DURABILITY-MIGRATION-PLAN-AMD2` (the Single Authority Resolver invariant, location-scoped) · `ES-005.4` · v3 commission `-AMD1` Category B rule

---
---

# APPENDED 2026-08-19 — **`OPEN-M3` DECIDED**

**Registered by:** Governance (`b64828fe`) on the PO/ARB DECISION ACT · grant **`G-KOS-GOV-STATE-DURABILITY-OPEN-M3-DECISION`**
**Nothing above rewritten.** ⛔ Governance records; it did not decide.

## D1 · The decision

> # ✅ **OPTION A — SAME BOUNDARY, EXPLICIT SCOPE EXTENSION** · **STATUS: DECIDED**

**Decision statement, verbatim:**

> **The Single Authority Resolver invariant is explicitly extended from governance-evidence record location to also govern the mechanism that interprets those records.**
>
> **No component may embed:**
> - **an independent authority-record path**
> - **an independent interpreter selection**
>
> **Under one governed authority boundary: ① where governance evidence is stored · ② which mechanism is authorized to interpret it.**
>
> **This is an explicit scope extension of the existing invariant. It does NOT create a new bounded context, a new governance owner, or a second authority domain.**

**Rationale, as given:** ⭐ **`right bytes + wrong interpreter = wrong authority`** — *"a governance problem, not just a runtime configuration problem."*

## D2 · Why not B, why not C — as decided

| | |
|---|---|
| **B rejected** | too strong. `C-2` already routes remediation through `R-37`/`ADR-C6`; a separate authority boundary **would create another owner for the same coupling** |
| **C rejected** | it would leave Phase 5 carrying **an architectural assumption nobody had adopted** — *"exactly the kind of hidden architecture decision we have been removing"* |

## D3 · ✅ Consequence for the delivered plan — Phase 5 stands

The prep's §2 flagged that Phase 5 switched **`P-4`** (`KOS_MECHANISM_PATH`, `session-resolve.php:90`) through the resolver **while the boundary question was undecided.**

> **The presumption is now ADOPTED. Phase 5 requires no revision on this point, and the plan's `INV-R3` now has decided backing on BOTH axes.**

⭐ **And the scope-expansion discipline is satisfied:** extending an adopted invariant **required its own act and received one.** The invariant was **not** expanded by interpretation — which is precisely what the v3 commission's Category B rule forbids.

## D4 · `C-2` / `ADR-C6` reconciliation — recorded

**`C-2` is an existing architectural problem** (2026-08-17, `KOS-ATTR-ARCH-001` stage-2, *"interpretation authority is environment-swappable"*, class `Declared`).
**This decision supplies its explicit remediation boundary.** **`ADR-C6` / `R-37` lineage remains authoritative.** ⛔ **No second owner, no competing governance mechanism.**

> ### ⚠️ **Governance precision: BOUNDARY DECIDED ≠ PROBLEM REMEDIATED.**
> This act decides **which boundary governs the fix**; it does not perform it. **`C-2` remains OPEN as a problem until the migration actually brings `P-4` under the resolver at Phase 5.** Decision is not implementation.

## D5 · Verification boundary — two separate gates

| Gate | Status |
|---|---|
| **`OPEN-M3` architecture-scope decision** | ✅ **CLOSED by this act** |
| **migration-plan independent completeness/technical review** | 🟡 **OUTSTANDING** |

**The existing review is a SELF-REVIEW** by the plan's own producer `1c8b041b`; it disclosed that limitation first and even found a defect in its own artifact. ⛔ **It remains non-independent and must never be cited as independent verification.**

> **Execution is gated on the outstanding independent review — not on `OPEN-M3`.**

## D6 · Non-decisions

⛔ migration execution · implementation details · `C-10` · `D2` · `D6` · `D7` · a ledger · KnowledgeOS bounded contexts · **and whether the independent review is now commissioned.**

**Traceability:** PO/ARB `OPEN-M3` decision act 2026-08-19 · this prep §1–§5 · `G-KOS-GOV-STATE-DURABILITY-OPEN-M3-DECISION` · `-MIGRATION-PLAN-AMD2` (the location-scoped invariant now extended) · plan `3817eb2b` §3 `INV-R3`, Phase 5 · review `13bcfb49` (self-review, `INFO-3`) · `C-2` / `R-37` / `ADR-C6` · `session-resolve.php:90`
