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

---

## 9. Architectural learning — one lesson, and it is not "a handler was removed"

> **Merge-gate metrics must distinguish FRAMEWORK-GENERATED runtime signals from PRODUCT-QUALITY signals. A metric that mixes them cannot be read.**

**And the consequence is sharper than "the number is meaningless."**

The gate reports `risky: 112`. All 112 are framework app-boot artefacts. **So if a genuinely risky test appeared tomorrow — one leaking global state, or depending on execution order — it would arrive as `risky: 113`.** Indistinguishable from adding one Feature test.

> **The defect is not that the number carries no information. It is that a real signal cannot be seen against it.** A metric that only ever grows for benign reasons provides **detection blindness**, not merely noise — and it is worse than having no metric, because it looks like coverage of a risk that is in fact unmonitored.

**Evidenced by my own behaviour, not hypothesised:** for a week I reported *"risky 108 → 111, consistent with ENG-012"* beside genuine results. **The phrase was true, said nothing, and read as diligence** — which is exactly how a blind spot survives review.

## 10. Capability impact

| Capability | State after this investigation |
|---|---|
| **Adjudication seam verification** | **MATURE** — keystones, real-database round trips, mutation-checked, adapter covered |
| **Framework-integration understanding** | **IMPROVED** — the app-boot handler mechanism is now located and one plausible cause eliminated |
| **Engineering verification SIGNAL QUALITY** | **WEAKEST** — the gate emits one aggregate `risky` count blending framework and product signals, with **no baseline** against which a change would be visible |

## 11. Capability gap — stated without a solution

**Revised 2026-08-04 at the ARB's refinement.** An earlier version of this section named a *"verification-signal classification"* and the files it would touch. **Both were solution design wearing the label of assessment**, and the second put implementation surface inside a capability report. The gap is stated below **technology-independently**; how it is closed is not this report's to say.

> ### The gap
>
> **Engineering currently cannot distinguish, from its verification output:**
>
> - **framework runtime signals** — artefacts of how the application is booted for testing;
> - **application failures** — defects in product code;
> - **infrastructure failures** — environment, database, transport;
> - **business verification failures** — an invariant or rule genuinely violated.
>
> **All four arrive in one aggregate count.** **Any future solution must eliminate that ambiguity.**

**Why it matters, in capability terms:** verification exists to make failure *legible*. A verification capability that cannot separate a framework artefact from a violated business invariant **cannot discharge its purpose**, however many tests it runs. **The maturity of the seam-verification capability (§10) is therefore bounded by this gap, not by its own test coverage** — it can prove behaviour and cannot prove that a new problem would be noticed.

**What is observably missing:** the ability to answer *"did the verification signal change because the product changed?"* — today unanswerable, because benign growth and genuine regression are the same number moving.

**Deliberately not stated here:** any mechanism, file, configuration, tool or classification scheme. **Those are solution design, and no implementation is authorized.**

## 12. Authorization boundary

**Am I describing a capability gap?** §11, yes. **Am I describing a solution?** Deliberately not — removed at revision. **Has implementation been authorized?** **No.**

| | |
|---|---|
| Governance domain for any future work | **Execution Governance** — authorization of implementation work |
| Responsible authority | the **ARB**, on an execution-governance act |
| Status | **stopped at assessment.** No design, no plan, no files named |

**Also withdrawn at this revision:** the recommendation that `enforceHorizon()`'s isolation defect *not* be next. **That was still roadmap sequencing, which is not this report's business either.** The defect stands recorded under ER-08; **what comes next is a planning decision, taken elsewhere.**
