# WP-6 Remediation — Completion Report

**Date:** 2026-08-01 · **Authorized by:** **R-52** (ARB · Delivery Governance · Approval) · **Track:** WP-6 remediation, **independent of WP-7**
**Repository Integrity Gate:** ✅ PASSED.

---

> # ✅ **`composer merge-gate` PASSES — for the first time since 2026-07-31.**
>
> ```
> Tests: 255 · Assertions: 650 · Failures: 0 · Errors: 0
> Architecture fitness ✔ · Deptrac ✔ · greenfield PHPStan ✔ · widened regression ✔
> ```
>
> **The merge gate is restored.** Every slice — 7B included — can now present a complete gate at acceptance, which none could while F-7A-1 stood.

---

## 1. RED baseline — the existing failures, not a manufactured one

```
Tests: 4 · Assertions: 0 · Errors: 4
ArgumentCountError: Too few arguments to AdjudicationProcessManager::__construct(),
2 passed ... and exactly 5 expected
```

**The commission's verification strategy called for a RED step that "exposes the constructor mismatch." The mismatch already produced four erroring tests, so that RED existed.** Writing an additional failing test would have added ceremony without adding discrimination — the standard this programme applies to its own criteria (**Methodological Fitness Rule**). **The four errors are recorded as the baseline.**

## 2. GREEN — the repair

**Exactly one file changed:** `tests/Unit/Contexts/Adjudication/Process/AdjudicationProcessManagerTest.php`.

WP-6 widened the manager's constructor from 2 collaborators to 5. The test now supplies all five:

| Collaborator | Supplied as |
|---|---|
| `AdjudicationProcessStore` | existing `InMemoryAdjudicationProcessStore` |
| `ClockInterface` | existing `FrozenClock` |
| **`AdjudicationDurations`** | **local anonymous class** — fixed `P60D` |
| **`EventOutbox`** | existing `InMemoryEventOutbox` |
| **`IdentityGenerator`** | **local anonymous class** — deterministic UUIDs |

**Why anonymous classes rather than new shared doubles:** these keystones exercise the **conduct** (PM-1/PM-4/PM-5/PM-6), not the horizon — the two new collaborators are **inert stand-ins that nothing asserts on**. The horizon is covered where it belongs, in `AdjudicationHorizonTest`. **R-52 authorized repairing this file, not widening the shared test-support surface**, and two trivial collaborators used by one test do not justify new support classes.

```
Tests: 4 · Assertions: 7 — OK
```

## 3. Sweep — no other instance of the same staleness

| Check | Result |
|---|---|
| Other constructions of `AdjudicationProcessManager` | **none** outside this file and the service provider |
| Other implementations of `AdjudicationProcessStore` in tests | **one** — `InMemoryAdjudicationProcessStore`, already repaired |

**The defect class is closed, not just this instance.**

## 4. Acceptance criteria

| # | Criterion | Result |
|---|---|---|
| 1 | The test constructs the manager correctly | ✅ |
| 2 | All 4 tests restored | ✅ 4 tests, 7 assertions |
| 3 | `composer merge-gate` completes | ✅ **PASS** |
| 4 | **No production code changed** | ✅ **one test file, nothing else** |
| 5 | Track kept independent of WP-7 | ✅ no WP-7 file touched |

## 5. ⚠️ New finding — **F-WP6R-1**: 99 risky tests, previously invisible

The passing gate reports **99 risky tests** *(risky ≠ failure; the gate passes)*:

```
* Test code or tested code removed error handlers other than its own
* Test code or tested code removed exception handlers other than its own
```

| Question | Answer |
|---|---|
| Introduced by this repair? | ❌ **No** — **zero** risky tests in the repaired file |
| Localised to one context? | ❌ **No** — **every** context: Adjudication, Contestation, Election, Shared |
| Confined to old code? | ❌ **No** — **7A's own `EvidencePreservationDurationsTest` is among them** |
| Why unseen until now? | **The suite never ran to completion.** A blocked gate reports nothing about the tests it never reached |

**Assessment:** a global Feature-test condition — consistent with a PHPUnit 11 / framework error-handler interaction — **not a defect in any one slice.** **Recorded, not acted on:** R-52's scope is the constructor repair, and pursuing this would widen the package without authorization.

> **The observation worth keeping:** *restoring a gate does not only fix what was broken — it makes visible what the breakage was hiding.* **99 risky tests have been accumulating unseen since the gate stopped completing.**

## 6. Constraint compliance

| Constraint | Honored |
|---|---|
| ⛔ Merge WP-6 repair into WP-7 | ✅ no WP-7 file touched |
| ⛔ Modify production code | ✅ **one test file only** |
| ⛔ Touch 7B or 7C | ✅ |
| ⛔ Amend R-43 | ✅ governance untouched |
| ⛔ Reopen the reproduction report | ✅ evidence frozen |

## 7. State and next

| Item | Before | **After** |
|---|---|---|
| WP-6 remediation | OPEN, not started | ✅ **DELIVERED — awaiting ARB acceptance (queue item 8)** |
| `composer merge-gate` | ⛔ red since 2026-07-31 | ✅ **PASS** |
| Slice 7B preparation | ACTIVE (R-54) | ✅ unchanged — **still the other authorized track** |
| Slice 7C | unauthorized | ⬜ unchanged |

**Queue item 8 — *deliver + accept WP-6 remediation* — is delivered. Acceptance is the ARB's.**

---

**Traceability:** **R-52** (this authorization) · **R-50** reproduction evidence (the finding this repairs) · F-7A-1 (original discovery, during slice 7A) · WP-6 GREEN `22d604844` (which widened the constructor) · `AdjudicationHorizonTest` (where the horizon is actually covered). **No production code changed · no governance modified · tracks kept separate · one file touched.**
