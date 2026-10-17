# `GREEN-7` (UQ-1…UQ-4) — **FEASIBILITY AUDIT** · read-only

**Work item:** `EM-IMPL-002` · **Date:** 2026-09-30 · **Type:** feasibility investigation, no decision, no implementation
**By:** `claude-code-session:84c0f6f6-795e-4c89-a382-733f2c7b7caf` — self-declared, not attestable (`INV-ATTR-1/2`, `G-2`)
**Placement derived:** `doc-placement.php --scope=product-specific --domain=publicdigit` → `docs/publicdigit` (exit 0). `reviews/` is the established home for audits (six prior `*-audit.md`).

> ⛔ **READ-ONLY. No production code, no test, no ADR, no governance record changed.** UC-4's Option-A hold release and its escalation are untouched and unreinterpreted.
>
> 🏷️ **Label note:** the commissioning prompt said *"GREEN-7 (UC-5 queries)"*. Per the code's own guards, **`GREEN-7` = UQ-1…UQ-4 (queries)** and **`GREEN-6` = UC-5 `RecordCommitteeConstitution`**. This audit covers `GREEN-7` — the four queries.

---

## 1 · Verified facts

**The four surfaces exist as GREEN-1 stubs**, each throwing `BadMethodCallException` as its entire body:

| Query | UQ | File:line | Injected collaborators | Return type |
|---|---|---|---|---|
| `GateIntervalStateQuery` | UQ-1 | `:29` | `AcceptanceGateDecisionRepository`, `ElectionCommitteeRepository` | `GateIntervalState` (enum, **non-nullable**) |
| `ProgressionEligibilityQuery` | UQ-2 | `:28` | `AcceptanceGateDecisionRepository`, `ElectionCommitteeRepository` | `bool` |
| `ClockReadingQuery` | UQ-3 | `:28` | `RecoveryProcessRepository` | `ClockReading` (**non-nullable**) |
| `RecoveryPeriodStatusQuery` | UQ-4 | `:27` | `RecoveryProcessRepository` | `bool` |

**All three repositories return nullable:**
`AcceptanceGateDecisionRepository::find(…): ?AcceptanceGateDecision` (`:20`) · `ElectionCommitteeRepository::find(…): ?ElectionCommittee` (`:20`) · `RecoveryProcessRepository::find(…): ?RecoveryProcess` (`:21`).

**Every required domain derivation is reachable** — this is the decisive positive finding:

| Needed | Source | Obtainable? |
|---|---|---|
| `GateIntervalState` | `AcceptanceGateDecision::intervalState(ElectionCommittee $committee)` (`:171`) | ✅ both arguments come from injected repositories |
| Meaning-2 answer (UQ-2) | `intervalState()` + committee's `unableToFunction(RequiredVotes)` — the UC-3 pattern | ✅ |
| `ClockReading` | `RecoveryProcess::readingAt(RecordedInstant)` (`:113`) | ✅ |
| expiry answer | `RecoveryProcess::isExpiredAt(RecordedInstant)` (`:124`) | ✅ |

> ### ✅ **There is NO `HaltedAtGate`-class blocker in `GREEN-7`.** No query requires a fact that has no producer. Unlike UC-4, nothing here is technically unreachable.

**The five RED tests (`QueryServicesRedTest`) seed present aggregates throughout. None exercises absence.**

| RED test | Seeds | Absence exercised? |
|---|---|---|
| `test_uq1_returns_the_derived_gate_interval_state` | committee + gate (2 × Accept) → asserts `DecidedPass` | ❌ no |
| `test_uq2_progression_eligibility_defers_while_inoperative_and_holds_when_operative` | committee + gate; then 2 vacancies → Inoperative | ❌ no |
| `test_uq3_clock_reading_is_computed_over_the_recorded_intervals` | restoration process, paused at 600 | ❌ no |
| `test_uq4_period_status_answers_expiry_as_a_question` | halted-recovery process | ❌ no |
| `test_queries_record_nothing_and_mutate_nothing` | all of the above | ❌ no |

**No query requires `HaltedAtGate`, `ElectionOperationalStatus`, `RecordedOperationalStatus`, Act C or Act D.** Verified: none of those symbols appears in any of the four query files.

**The architecture hold covers `GREEN-5/6/7`** (`architecture-hold.md:3` — *"⏸️ GREEN-5/6/7 ON HOLD until three items close"*). **The ARB's Option A released *"the existing EM-IMPL-002 / GREEN-5 implementation hold"* — `GREEN-5` only** (decision record `:113-114`). **`GREEN-7` remains held.**

---

## 2 · ⚠️ My own prior statement, verified and PART-RETRACTED

I previously said *"`GREEN-7` looks like the least-encumbered remaining slice"*, reasoning in part that the absence pin excludes queries.

| | Verdict |
|---|---|
| *"No `HaltedAtGate`-class wall"* | ✅ **CONFIRMED** — §1 |
| *"The pin excludes queries, therefore queries are safer"* | ❌ **RETRACTED — the inference is backwards.** |

The pin states **why** it excludes them (`:47-50`, verbatim):

> *"Scope: command handlers only. Queries are excluded — they are unimplemented, and **read-side absence (an answer about a thing that does not exist) is a distinct question the ADR does not cover.**"*

⇒ **The exclusion is a statement that the question is UNGOVERNED, not that it is settled.** For command handlers `ADR-1` §6(a) at least supplies governed meanings; for the read side there is **no ADR to appeal to at all**. That is a gap, not a clearance — and the commissioning prompt's warning against exactly this inference was correct.

**Corroborating:** every row of `ADR-1` §6(a) is framed *"**the command** requires…"* (`:199-202`). The table is command-side by its own wording. Extending any row — including `RecoveryProcess` = *"legitimate lifecycle state"* — to the read side **would itself be an interpretation**, which `§6(b)` and the `6-guard` forbid the application layer from making.

**The concrete consequence.** Each query must call a nullable `find()` and each returns a **non-nullable** type. So on absence every query must either throw or return a value, and **choosing either invents read-side absence semantics**.

---

## 3 · Query-by-query analysis

| Query | Current state | RED tests | Dependencies | Absence question? | BND-1? | BND-3? | Act C/D? | New domain concept needed? | Implementable now? |
|---|---|---|---|---|---|---|---|---|---|
| **UQ-1** `GateIntervalStateQuery` | stub, throws (`:29`) | `test_uq1…`, purity test | AG-2 gate + AG-1 committee; `intervalState()` `:171` | **YES** — 2 nullable sites; `intervalState()` needs a **non-null** `ElectionCommittee` | **NO** — §6(a) is command-framed; read side uncovered, so no discriminator is *appealed to*. ⚠️ Not BND-1, but equally not governed | **NO** — no symbol reference | **NO** | **NO** new concept; but an **answer for absence** is undefined | **NO** |
| **UQ-2** `ProgressionEligibilityQuery` | stub, throws (`:28`) | `test_uq2…`, purity test | AG-2 + AG-1; `intervalState()` + `unableToFunction(requiredVotes())` | **YES** — 2 nullable sites | **NO** — as UQ-1 | **NO** | **NO** | **NO** new concept; absence answer undefined | **NO** |
| **UQ-3** `ClockReadingQuery` | stub, throws (`:28`) | `test_uq3…`, purity test | AG-3 recoveries; `readingAt()` `:113` | **YES** — 1 nullable site; returns non-nullable `ClockReading` | **NO** | **NO** | **NO** | **NO** new concept; absence answer undefined | **NO** |
| **UQ-4** `RecoveryPeriodStatusQuery` | stub, throws (`:27`) | `test_uq4…`, purity test | AG-3 recoveries; `isExpiredAt()` `:124` | **YES** — 1 nullable site; returns `bool` | **NO** | **NO** | **NO** | **NO** new concept; absence answer undefined | **NO** |

**Evidence for every "NO" on Act C/D and BND-3:** `grep` for `HaltedAtGate`, `ElectionOperationalStatus`, `RecordedOperationalStatus` across the four query files → **zero hits**.
**Evidence for every "YES" on absence:** the three `find(): ?X` signatures above, against four non-nullable return types.

### What UQ-1/UQ-2 do when the gate EXISTS vs when it does NOT

**Exists:** `AcceptanceGateDecision::intervalState($committee)` classifies via `GateIntervalClassification` into `DecidedPass` · `DecidedFailure` · `Open` · `Unachievable` (`:45-81`). UQ-2 then combines `DecidedPass` with the operative condition per Meaning-2 (`EM-GOV-071`).

**Does not exist:** **there is no defined behaviour.** No RED pins it, the return types admit no absence value, and `ADR-1` does not cover the read side. ⛔ **Per the commissioning instruction, both are therefore classified BLOCKED, not implementable — and no absence semantics are invented here.**

---

## 4 · Governance classification

| Query | Classification |
|---|---|
| **UQ-1** | **BLOCKED — GOVERNANCE** |
| **UQ-2** | **BLOCKED — GOVERNANCE** |
| **UQ-3** | **BLOCKED — GOVERNANCE** |
| **UQ-4** | **BLOCKED — GOVERNANCE** |

**Primary blocker, identical for all four and dispositive on its own:** the `GREEN-5/6/7` architecture hold was released **for `GREEN-5` only**. **No authorization to implement `GREEN-7` exists.**

**Second blocker, which any `GREEN-7` authorization must also address:** read-side absence has **no governed meaning** — architectural in nature, and **not** `BND-1` or `BND-3`. Recorded here rather than given its own category, since the scheme permits exactly one classification per query and the missing authorization is dispositive.

---

## 5 · `GREEN-7` feasibility

> **None of the four is currently implementable.** Not ranked.

**But the blockers are decisions, not missing machinery.** Every domain derivation `GREEN-7` needs already exists and is reachable from the collaborators the GREEN-1 surface already injects. There is no `GREEN-7` equivalent of UC-4's missing `HaltedAtGate` producer.

---

## 6 · Governance implications — questions for PO/ARB, not answered here

1. **Does the `GREEN-5/6/7` hold get released for `GREEN-7`?** Its three closing items were command-handler-shaped (absence taxonomy · `Q-RESTORE` causal provenance · GREEN-3's guard · the denominator rule) — whether they bear on queries at all is the PO/ARB's reading, not mine.
2. **What does an absent aggregate mean on the READ side?** `ADR-1` does not cover it, by its own scope and the pin's statement. Options visible in the corpus — *not* evaluated, *not* ranked here: extend `ADR-1` to the read side · apply a UC-4-style *"established X exists"* command/query precondition · define a query-specific answer shape. Each is a governance act.
3. **Is `ADR-1` §4's open shape question in scope** if option 3 above is taken?

⛔ **This audit answers none of the three.**

---

## 7 · Recommended next action for the human PO/ARB

**A decision is required before any `GREEN-7` work:** whether to release the hold for `GREEN-7`, and if so, how read-side absence is to be governed. **These are two distinct acts** — releasing the hold without settling absence would leave the slice unable to implement four non-nullable returns without inventing meaning.

⛔ **No recommendation between the options in §6 is offered, and no option is ranked.**

## 8 · Non-actions — confirmed

✅ **No production code changed** · ✅ **no tests changed, added or deleted** · ✅ **no ADR changed** · ✅ **no `BND-1`/`BND-3` decision made** · ✅ **no Act C/D work started** · ✅ **no UC-4 scope changed, and Option A untouched** · ✅ no `HaltedAtGate` created · ✅ no `RecordedOperationalStatus` wired · ✅ no restoration-only UC-4 branch implemented · ✅ no choice made between UC-4 options (a)/(b)/(c) · ✅ no unrelated KnowledgeOS or newsletter work touched · ✅ no existing governance record edited.

**Files changed by this audit:** this file only (new). `git status --porcelain app/ tests/` → empty.

**Traceability:** `Query/{GateIntervalState,ProgressionEligibility,ClockReading,RecoveryPeriodStatus}Query.php` · `QueryServicesRedTest.php` · `Repository/{AcceptanceGateDecision,ElectionCommittee,RecoveryProcess}Repository.php` · `Gate/AcceptanceGateDecision.php:171` · `Policy/GateIntervalClassification.php:45-81` · `Recovery/RecoveryProcess.php:113,:124` · `AbsentAggregateReferenceRedTest.php:47-50` · `ADR_20260817_2145` §6(a) `:199-202`, §6(b), `6-guard`, §4 · `architecture-hold.md:3` · decision record `:113-114`
