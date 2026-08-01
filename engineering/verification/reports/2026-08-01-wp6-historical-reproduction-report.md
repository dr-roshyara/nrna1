# WP-6 Historical Reproduction — Evidence Report

**Date:** 2026-08-01 · **Authorized by:** **R-50** (ARB · Delivery Governance · Authorization) · **Strategy:** R-49, Option B
**Scope:** reproduce `composer merge-gate` at the recorded WP-6 closure commit and **report only the reproduced evidence**.
**Constraints:** ⛔ no production changes · ⛔ no test repairs · ⛔ no governance edits · **evidence collection only**
**Repository Integrity Gate:** ✅ PASSED — the main working tree was **never modified**.

---

> # OUTCOME: **REPRODUCED RED**
>
> One of the three outcomes recorded in advance under R-50. **No other classification is claimed.**

---

## 1. Reproduction Protocol

**Documented so the reproduction is itself reproducible.** Steps were executed in this order; the **Validity Gate** is a hard precondition — execution was not permitted before it passed.

| # | Step | Result |
|---|---|---|
| 1 | **Identify the target commit** | `22d604844` — *"feat(adjudication): WP-6 GREEN — MAD-aware horizon, expiry announcement, late-decision conflict"* |
| 2 | **Create an isolated worktree** | `git worktree add <tmp> 22d604844`, detached, **outside the repository** |
| 3 | **Verify the main worktree is unaffected** | ✅ never modified; 23 unpushed commits untouched throughout |
| 4 | **Verify dependency and suite drift** | ✅ see §2 |
| 5 | **🚦 VALIDITY GATE** | ✅ see §3 — **all five checks passed** |
| 6 | **Execute the historical gate, once** | `php vendor/phpunit/phpunit/phpunit --testsuite=GreenfieldCore` from the worktree |
| 7 | **Capture output; destroy the worktree** | §4; removed after capture |

## 2. Environment and dependency strategy

| Input | Closure commit vs HEAD | Consequence |
|---|---|---|
| `composer.json` | **byte-identical** | dependency definition unchanged |
| `composer.lock` | **byte-identical** | resolved version graph unchanged |
| `phpunit.xml` | **byte-identical** | **suite composition unchanged** — GreenfieldCore covered the same paths |
| `app/` · `tests/` | **at the closure commit** | the only variable — and the one under investigation |

**Dependency strategy:** `composer install` was run **inside the worktree** from the historical `composer.lock`. The shared-`vendor` approach was **rejected** (§5).

> ### ⚠️ Limitation — stated precisely
>
> An identical lockfile establishes that **the dependency set matches the historical dependency *definition***. It does **not** establish that the installed `vendor` bytes are identical to those present on the machine on 2026-07-31. **The reproduction reproduces the historical source tree and the historical dependency definition — not a verified byte-for-byte historical dependency environment.**
>
> **Bearing on this result:** the observed failure is a **PHP class-resolution error between two first-party files** (`tests/Support/...` against `app/Contexts/...`). **No third-party package participates in it**, so the limitation does not weaken *this* finding — but it is recorded because it would matter for a different one.

## 3. 🚦 Validity Gate — execution was gated on all five

| # | Check | Result |
|---|---|---|
| 1 | Historical **application** sources loaded | ✅ `App\…` resolved inside the worktree |
| 2 | Historical **test** sources loaded | ✅ `Tests\…` resolved inside the worktree |
| 3 | Dependency set validated against the historical lockfile | ✅ installed from `composer.lock` unchanged |
| 4 | Bootstrap validated | ✅ worktree `phpunit.xml`, unchanged from the closure commit |
| 5 | **No mixed-source autoload** | ✅ — **this check failed on the first attempt and blocked it** (§5) |

**Check 5 is the one that earns its place.** Without it, the first attempt's output would have been reported as history.

## 4. Observed evidence

```
PHPUnit 11.5.6 · PHP 8.3.24
Configuration: <worktree>/phpunit.xml
Suite: GreenfieldCore

...............................................................  63 / 247 ( 25%)
...................   ....PHP Fatal error:

Class Tests\Support\Adjudication\InMemoryAdjudicationProcessStore contains 1 abstract
method and must therefore be declared abstract or implement the remaining methods
(App\Contexts\Adjudication\Application\Port\AdjudicationProcessStore::latestForChallenge)
  in <worktree>/tests/Support/Adjudication/InMemoryAdjudicationProcessStore.php on line 24
```

**Both participating files resolved inside the worktree.** The suite **terminated**; it did not complete.

**`composer merge-gate` runs GreenfieldCore as its final step**, so a fatal there means the gate could not complete at this commit. *(Stated as the mechanical consequence of the gate's composition, which is itself byte-identical to the closure commit.)*

## 5. Rejected run — disclosed, not silently discarded

The **first** attempt shared the main checkout's `vendor` through a Windows junction. It produced a fatal — **a different one**:

```
Cannot redeclare App\Helpers\voter_log() (previously declared in <main>/app/Helpers/ElectionAudit.php:49)
  in <worktree>/app/Helpers/ElectionAudit.php on line 49
```

**This was an artifact of the experimental setup, not an observation about history:** Composer's `files` autoload loaded the **main** checkout's helpers, and the worktree's copy then redeclared them. **Validity Gate check 5 rejected the run.**

*(Cause, for anyone repeating this on Windows: a junction resolves `__DIR__` to its **target**, so Composer's runtime-relative `$baseDir` pointed at the main checkout. A junctioned `vendor` cannot host a second source tree.)*

**A reproduction must reject invalid experiments rather than report them.** The junction was removed and the protocol restarted from step 4.

## 6. What is established — and what is not

| Claim | Status |
|---|---|
| The GreenfieldCore suite **fatals at the closure commit** | ✅ **REPRODUCED** |
| `composer merge-gate` **could not have completed** at that commit | ✅ follows from the gate's composition |
| The failure is the **same** one observed today | ✅ same class, same method, same line |
| **The gate was executed at closure and observed to fail** | ❌ **NOT established** — this shows what the gate *would* have done, not what anyone *did* |
| **R-43's evidence line was false rather than unsupported** | ❌ **NOT established** — distinguishing the two requires evidence that is *human*, not repository |

## 7. Constraint compliance

| Constraint | Honored |
|---|---|
| No production changes | ✅ nothing outside the disposable worktree |
| No test repairs | ✅ **the broken double was left broken** — repairing it would have destroyed the measurement |
| No governance edits | ✅ **R-43 untouched**; no ruling amended |
| Evidence collection only | ✅ **no recommendation on remediation appears in this report** |
| Main working tree unaffected | ✅ verified before and after |
| Worktree destroyed | ✅ removed after capture |

**Queue items 5 (*open WP-6 remediation?*) and 6 (*note against R-43?*) were blocked pending this evidence and are now unblocked.** Both remain the ARB's, and **this report offers no position on either.**

---

**Traceability:** **R-50** (this authorization) · **R-49** (Option B) · F-7A-1 · **R-43** (WP-6 acceptance — **not amended**) · closure commit `22d604844` · WP-2 double `ac5b34042`. **No production change · no test repair · no governance edit · no interpretation beyond the observed outcome.**
