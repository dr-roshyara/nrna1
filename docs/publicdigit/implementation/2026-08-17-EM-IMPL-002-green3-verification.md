# `EM-IMPL-002` GREEN-3 — Governance verification record (UC-2)

**Date:** 2026-08-17 · **Commit 3:** `cdc45f99` (body + docblock, one file) · **Verified by Governance's own runs and scans.**

## 1 · Observed

| Check | Result |
|---|---|
| Footprint | ✅ **one modified file**; no new file, no test touched, no abstraction |
| Domain core | ✅ **byte-identical** |
| Other bodies | ✅ **seven still throw** (3 handlers + 4 queries) |
| Application suite | **28/24 → 20/32** — the **8** that moved are UC-2's six + its two refusal rows. Nothing else moved. |
| Structural guards | ✅ **16/16** (1073 assertions) |
| Frozen core | ✅ **42 passed / 2434** |
| **RISK 3 — non-retroactive clock** | ✅ **no time arithmetic at all** — no instant subtraction, no `->diff`, no `elapsed`, no reconstruction; the only time call is `instants->now()` for the recording instant. **Every interval is computed inside AG-3, from the policy's returned onset.** |
| **RISK 1 — lifecycle leakage** | ✅ **no comparison operator, no `count()`, no threshold, no `ceil`/`intdiv` anywhere in the file** — so no lifecycle predicate could have formed. It asks the aggregate and the policy and records their answers. |
| **RISK 2 — I-15 one allowance** | ✅ **by repository lookup, never a counter**: absent → start once + append; present → `resume()` **the same instance**, no start fact, no policy re-binding. |
| A-5's caller-error half | ✅ **first executable confirmation** — `unknown committee seat is a caller error and never recorded` now passes: the protocol holds no entry about a subject that never existed. |

## 2 · ⚠️ TWO ITEMS FOR THE PO — registered, not decided

### 2a · The divergence the lane flagged: the denominator lookup

`RecordVacancyEventCommand` carries **no gate designation**, so there is no single named AG-2 to read. The lane scans `GateDesignation::cases()` and takes the first established decision, justified by `EM-GOV-057` (both designations of one election bind the same constituted denominator).

**Governance's assessment:** ✅ **it is a lookup, not arithmetic** — no forbidden shape is present, and the rejected alternative (calling `ThresholdRule::twoThirdsOfCommitteeVotes()` in the handler) would indeed have put **a second rule surface in the application (G-5)**, so the lane rejected the worse option. ⚠️ **But it remains a choice the authorized record does not spell out, and the lane is right to ask.** **A ruling is wanted before GREEN-4, since UC-3/4/5 add lookup sites.**

### 2b · 🔴 Governance's own finding: the extra guard is UNPINNED

The lane added a guard the record implies but **no test names** — the consequence path runs only if the condition **was not already begun** (`$conditionAlreadyBegun = $committee->unableToFunction($required)` before the act, then early return). **Governance verified independently: no committed test pins this** (searched for already-begun / third-vacancy / duplicate-onset semantics — **nothing**).

**Why this matters more than a style note:** the lane states its own correctness argument rests on this guard — *"`pause()`/`resume()` are safe without asking whether a period is accruing because `RecoveryProcess` exposes no such accessor, so the correctness rests on this guard, not on a probe."* **So the increment's safety currently depends on behaviour that RED does not protect: a later refactor could delete it and the suite would stay green.** The guard itself looks *right* (a third vacancy while already Inoperative would otherwise append a duplicate onset and resume an already-accruing period — `EM-GOV-061`(b)/`065`).

> **Recommendation (Governance's, not a decision): pin it in the next RED slice** — one test, *third vacancy while already Inoperative appends no second onset and starts no second period.* **This is a RED amendment, so it lands as its own commit before any further GREEN**, exactly as the ordering discipline requires. **Condition 9's spirit (no stored idempotence flags) is honoured — this is a verdict comparison, not a flag — but an unpinned invariant is a regression waiting to happen.**

## 3 · Condition 3 re-raised, as instructed

The lane **did not** invent a new exception type (which would create a second competing precedent) and **did not** silently copy UC-1. It isolated the shape behind **one named private method per handler** (`unresolvedReference()`), so closing the taxonomy is a mechanical edit at named sites rather than a hunt. **Candidates for the PO: a domain `UnknownElectionReference` · an application `ApplicationException` subtype · keeping the built-in permanently.** The lane recommends closing before GREEN-4; **Governance concurs on timing** — each further use case adds sites.

**Traceability.** Commits `1f4b4c5f` → `d2a0fe7c` → `8ea13835` → `cdc45f99` · the ten gate conditions · the five registered conditions (`230a604a`) · `EM-GOV-057`/`061`(b)/`065` · this record's own scans.
