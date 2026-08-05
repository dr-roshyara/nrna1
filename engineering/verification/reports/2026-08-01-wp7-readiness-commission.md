# WP-7 Readiness Commission

**Date:** 2026-08-01 · **Role:** Senior Principal Architect · **Commission:** *are we architecturally authorized to open WP-7?* — readiness only, no implementation, no revisiting WP-6.
**Repository Integrity Gate:** ✅ **PASSED** — working tree == index == HEAD, no operation in progress. All evidence below is sourced from the **working tree at HEAD**, which are identical.

---

## 1. Program state

| Prerequisite | Status |
|---|---|
| WP-6 acceptance package exists | ✅ `.claude/plans/WP-6-temporal-machinery.md` §Closure |
| Repository Integrity Gate passed | ✅ and now recorded as a permanent rule |
| Structural integrity accepted | ✅ pass — no drift in six dimensions |
| Governance integrity accepted | ✅ pass after two corrections applied *before* acceptance |
| Governance gaps explicitly recorded | ✅ DC-1 · AT-EVT-001 · Q-2's five parameters |
| Implementation defects remaining | ✅ **none** |
| **WP-6 SLICE ACCEPTANCE** | ⛔ **PENDING** — see §2 |

## 2. Authority status

| Open item | Classification | Owner | Blocks WP-7? |
|---|---|---|---|
| **WP-6 slice acceptance** | **Requires ARB** | ARB | ⛔ **YES — see §3's rule** |
| WP-3A · WP-4 slice acceptance | Requires ARB | ARB | **No** — WP-7 has no dependency on either |
| **AT-EVT-001** evolution | **Requires ADR** (or a recorded ruling) | ARB | No |
| **DC-1** invalid-MAD case | **Requires Product/Business Decision** (Q-2) | ARB / Q-2 | No — the code fails closed |
| Q-2's five numeric parameters | Requires Product Decision | ARB | **No for MAD** · ⚠️ **partly YES for two others — see §3** |
| Evidence-demand deadlines · finality evaluator | Future Work | ARB (modelling / integration) | No |
| Contestation consumer of `AdjudicationExpired` | Future Work (WP-6B) | next slice | No |

## 3. Roadmap continuity — WP-7 is the correct successor, with two findings

**Sequence confirmed:** the roadmap states `WP-1 → … → WP-6 → WP-7 → WP-8`, with *"WP-7 (retention) — after the WP-6 config exists; otherwise independent."* No work package is skipped, and WP-7 depends on nothing from WP-3A/WP-4.

### ⛔ Finding R-1 — an explicit rule blocks opening WP-7 today

The roadmap states it in its own words:

> *"Each WP = one slice = one ARB review (the established rhythm; **no slice starts before its predecessor's acceptance**)."*

**WP-6's acceptance is pending, so WP-7 may not open yet.** This is not a judgement call — it is a documented programme rule, and the same rhythm that has governed WP-1 → WP-6.

### ⚠️ Finding R-2 — WP-7's arithmetic needs three parameters; WP-6 supplied one

Constitutional Policy 2 (EPIC-004K §142):

> **`EPW(election) = Contestation Window + Maximum Adjudication Duration + Legal Safety Margin`**

Verified against the code:

| Term | Present? | Evidence |
|---|---|---|
| Maximum Adjudication Duration | ✅ **yes** | `config/adjudication.php` — WP-6 |
| **Contestation Window** | ⛔ **absent** | grep for `contestation_window` across `config/` and `app/`: **no match** |
| **Legal Safety Margin** | ⛔ **absent** | grep for `legal_safety_margin`: **no match** |
| Any EPW concept | ⛔ **absent** | grep for `evidence_preservation` / `preservation_window`: **no match** |

**Consequence:** the roadmap's phrase *"per-election arithmetic from the same config"* presumes a config that carries **all three** terms. **WP-7 must therefore add two parameters, not merely consume existing ones.** Both are **Q-2 business policy** (§187 lists them), so WP-7 opens with the same INTERIM-marked treatment WP-6 used for MAD — *the arithmetic is architecture; the numbers are the ARB's*.

**This is a scope finding, not a blocker:** the roadmap pre-authorizes INTERIM bootstraps, and the Q-2 resolution package already ratified values for both (CW 30/30d · LSM 30d · EPW 120d, per MEMORY). **It is recorded now so WP-7's plan does not discover it mid-implementation.**

### ✅ Finding R-3 — WP-7's premise holds

`audit:cleanup` **exists** (`app/Console/Commands/AuditCleanup.php`) and today takes a flat `--days=30` with `now()->subDays($days)`. So WP-7 is genuinely a *change* to an existing command — replacing a flat global window with per-election EPW arithmetic — and not a new capability disguised as one.

## 4. Strategic DDD verification — unchanged

| Property | Status |
|---|---|
| Bounded contexts | ✅ unchanged — WP-7 touches **retention/audit**, creating no new context |
| Context map | ✅ valid — **WP-7 introduces no crossing**; it reads configuration and election data |
| Published language | ✅ unaffected — no event added, changed or retired |
| Upstream/downstream relationships | ✅ unchanged — no producer or consumer is added |

**WP-7 is the least strategically disruptive slice in the roadmap:** it changes *what an operational command deletes*, not how contexts collaborate.

## 5. Tactical readiness

**No tactical model is designed here** (that belongs to WP-7's own EP-01 plan). Verified only that nothing in WP-7's stated scope contradicts accepted architecture:

- the **arithmetic is architecture, the numbers are the ARB's** — so the shape is already decided and needs no new modelling;
- retention is a **policy applied to stored artifacts**, touching no aggregate invariant;
- **one open question to resolve in WP-7's plan, flagged not answered:** *EPW is per-election, so where does the command obtain each election's window?* That may require reading election data, and if it needs another context's state it becomes an **integration** question — the same shape that kept the finality evaluator out of WP-6. **Recorded now precisely so it is answered in planning rather than discovered in GREEN.**

## 6. Business capability readiness

| Role | Owner |
|---|---|
| Business objective | **Nothing the Retention Invariant covers is deleted inside its window** (Constitutional Policy 2) |
| Business owner | **ARB / Q-2** (the durations) · the constitutional policy (the invariant) |
| Architectural owner | the retention arithmetic's shape — EPIC-004K §142 |
| Implementation owner | audit/retention command (`AuditCleanup`) |
| Acceptance owner | **ARB** (slice acceptance) |

⚠️ **One ownership item to settle in planning:** WP-7 is one of only **two externally visible behaviour changes** in the whole roadmap (*"the first externally visible behavior change is WP-6's finality transitions and WP-7's retention change — both flagged for operational announcement"*). **Operational announcement has an owner that is not the implementation team.** Recorded as a readiness item.

## 7. Carry-forward practices (reusable engineering only, no implementation details)

| Practice | Carry forward? |
|---|---|
| **Repository Integrity Gate** | ✅ **Yes** — run before any WP-7 review; `git status` unabridged |
| **Review framework, unchanged** (8 dimensions, 2 groups) | ✅ Yes — WP-7 is its **first cross-slice test**; refinement stays closed |
| **Operational record at closure** | ✅ Yes — WP-7 files the **second** record; the first genuinely independent evidence |
| INTERIM-marked parameters naming their pending authority | ✅ Yes — directly applicable to CW and LSM (R-2) |
| **Fail closed on untrusted configuration** | ✅ Yes — the AP-1 lesson: an adapter must never *substitute* a business value |
| Keystones that assert **absences** | ✅ Yes — WP-7's own keystone is an absence: *nothing inside an open EPW is deleted* |
| Structural encoding of decisions where possible | ✅ Yes — the ADAPR finding: prose-only decisions need continuous interpretation |
| WP-5 allowlist checkpoint | ✅ Yes — carried, though it likely resolves in WP-8, not WP-7 |
| WP-6 implementation details (MAD, expiry, hydrators) | ❌ No — slice-specific |

## 8. Readiness risks

| Risk | Class | Owner | Mitigation | Blocking? |
|---|---|---|---|---|
| WP-6 not yet accepted | **Governance** | ARB | Grant or withhold slice acceptance | ⛔ **YES** |
| CW and LSM absent from config (R-2) | **Dependency** | ARB (values) · WP-7 (wiring) | Add both as INTERIM in WP-7, values already ratified | No |
| EPW is per-election — the source of each election's window is undecided | **Architectural** | WP-7's EP-01 plan | Resolve in planning; **stop and report if it needs another context's state** | No, if planned |
| Retention change is externally visible | **Business** | ops/product | Operational announcement, per the roadmap | No |
| Deleting audit data is irreversible | **Repository/Operational** | WP-7 | Keystone asserts the **absence** of deletion inside an open window; prefer dry-run evidence before enabling | No |
| Framework's first cross-slice application may expose a gap | Governance | ARB | That is the intended experiment; reopening needs cross-slice evidence | No |

## 9. Final recommendation

> ### **READY AFTER NAMED GOVERNANCE DECISIONS**

**The single blocking item is WP-6's slice acceptance**, required by the roadmap's own rule that *no slice starts before its predecessor's acceptance*. Everything else is satisfied: repository integrity verified · structural and governance integrity accepted · no implementation defect outstanding · every open item classified with a named owner · roadmap continuity confirmed with no skipped package · strategic design unchanged.

**Named decisions:**
1. ⛔ **WP-6 slice acceptance** — the only blocker.
2. ⚪ AT-EVT-001 (ADR/ruling) — **not** blocking.
3. ⚪ DC-1 (rule or decline) — **not** blocking.

## 10. Authorized first action for WP-7 (on acceptance)

> **EP-03 readiness review, then the EP-01 plan for `.claude/plans/WP-7-retention-alignment.md` — plan only, no code.**

The plan must resolve, before any RED:
1. **CW and LSM as INTERIM parameters** (R-2) — with the authority each awaits named;
2. **where per-election EPW data comes from** — and whether that constitutes a crossing (§5);
3. the keystone set around WP-7's stated acceptance criterion: *nothing inside an open EPW is deleted; deletion resumes after closure*;
4. who owns the **operational announcement** of an externally visible retention change.

**Not authorized yet:** any change to `AuditCleanup`, any new config key, any test. **Planning only, after WP-6 acceptance.**
