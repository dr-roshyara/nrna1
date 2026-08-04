# ENG-012 — diagnosis: the risky notices are framework app-boot behaviour, not PublicDigit code

**Produced by:** engineering, 2026-08-04. **ENG-012 opened by R-71 (2026-08-02) with cause unestablished.**
**Status: cause LOCATED to framework app-boot handler management. PublicDigit code is not implicated. The exact framework line that removes the extra handler is NOT established** — see §4.

---

## 1. What ENG-012 recorded

R-71 opened it because risky notices rose **101 → 105** when four tests were added, with *"the four new tests demonstrably NOT among the risky entries, so the cause is unestablished."* It has since tracked to **112**.

The notice text, in every case: **`Test code or tested code removed error handlers other than its own`** — plus its exception-handler twin.

## 2. Established: the notice tracks APP BOOT, not our code

**Evidence from this session's own runs, which form a clean natural experiment:**

| Test file | Base class | Boots Laravel? | Risky? |
|---|---|---|---|
| `ConcludeToIssuanceSeamTest` | `PHPUnit\Framework\TestCase` | no | **no** — 8 passed clean |
| `AdjudicationFailureDeclaredHydratorTest` | `PHPUnit\Framework\TestCase` | no | **no** |
| `AdjudicationExpiredHydratorTest` | `PHPUnit\Framework\TestCase` | no | **no** |
| `CoordinatorIssuanceRequestTest` | `PHPUnit\Framework\TestCase` | no | **no** |
| `EventHydratorRegistryWiringTest` | `Tests\TestCase` | **yes** | **yes** |
| `IssuanceContextRoundTripTest` | `Tests\TestCase` | **yes** | **yes** |

**Determination: the notice appears on app-booting tests and only on app-booting tests.** **EVIDENCE.**

**And it is not database-related:** `EventHydratorRegistryWiringTest` uses no database and is still flagged, so `RefreshDatabase` is excluded as a cause. **EVIDENCE.**

**This explains ENG-012's original puzzle.** R-71 noted the *new* tests were not among the risky entries — correct, because the count is **one notice per app-booting test**, and which tests carry the notice depends on execution order and which are app-booting. **Adding N app-booting tests adds N notices; adding unit tests adds none.** The 101 → 105 → 108 → 111 → 112 progression matches Feature tests being added.

## 3. Established: where the handlers are installed

`Illuminate\Foundation\Bootstrap\HandleExceptions::bootstrap()`:

```php
47:    set_error_handler($this->forwardsTo('handleError'));
49:    set_exception_handler($this->forwardsTo('handleException'));
```

**Every app boot installs both.** **EVIDENCE.**

## 4. DISPROVEN hypothesis — recorded because a disproven hypothesis is a result

**Hypothesis:** `HandleExceptions::flushHandlersState()` — which loops `restore_error_handler()` **until the stack is empty**, not merely until Laravel's own handlers are gone — drains PHPUnit's handler during test teardown.

**It fits the notice text exactly** (*"removed handlers other than its own"*) and would explain everything.

**Disproven:** `grep -rln flushHandlersState vendor/laravel/framework/src/` returns **only its own definition**. **It is never called** — not by the framework, not by Laravel's `TestCase`, not by our tests.

> **So the most plausible mechanism is not the actual one.** Recorded rather than quietly replaced, because the next person to investigate would otherwise re-derive and re-test the same dead end.

## 5. What remains unestablished

**Which framework code removes a handler it did not install.** Candidates not investigated: Symfony's `ErrorHandler` integration (referenced at `HandleExceptions.php:353`) · PHPUnit's own handler bookkeeping across `setUp`/`tearDown` · the application-flush path in `Illuminate\Foundation\Testing\TestCase`.

**Not pursued further**, because §2 and §6 make the practically useful determination available without it.

## 6. Practical determination

| | |
|---|---|
| **Is PublicDigit code implicated?** | **No.** Unit tests that avoid app boot are clean; the handlers are installed by the framework's own bootstrapper |
| **Is it a defect in our tests?** | **No.** Every affected test is a legitimate Feature test that must boot the application |
| **Does the count indicate degradation?** | **No.** It is a linear function of app-booting test count. **A rising number is a sign of more Feature tests, not worse ones** |
| **Can it be suppressed?** | Not investigated. Would be a `phpunit.xml` change, and **`phpunit.xml` has no `beStrict*` settings at all** — the behaviour is PHPUnit's default |

**Consequence for reading the merge gate:** *"risky: 112"* should be read as **112 app-booting tests**, not 112 problems. **The number is close to meaningless as a quality signal, and it has been reported alongside genuine results all week as though it were one.**

## 7. Evidence status

| Claim | Label |
|---|---|
| The notice appears only on app-booting tests | **EVIDENCE** — six files, this session's runs |
| `RefreshDatabase` is not the cause | **EVIDENCE** — a DB-free Feature test is still flagged |
| `HandleExceptions::bootstrap()` installs both handlers | **EVIDENCE** — lines 47, 49 |
| `flushHandlersState()` is never called, so it is not the cause | **EVIDENCE** — grep of the framework source |
| The count grows one per app-booting test | **INFERENCE** — consistent with 101→112 and the six-file split; not proven by a controlled count |
| PublicDigit code is not implicated | **INFERENCE** from the above |
| Which framework line removes the extra handler | **UNESTABLISHED** |

## 8. Traceability

**R-71** (ENG-012 opened) · `vendor/laravel/framework/src/Illuminate/Foundation/Bootstrap/HandleExceptions.php:47, 49, 327-351, 353` · `phpunit.xml` (no `beStrict*` settings) · this session's test runs · WP-4C-1 acceptance evidence §6 (ENG-012 recorded as open).
