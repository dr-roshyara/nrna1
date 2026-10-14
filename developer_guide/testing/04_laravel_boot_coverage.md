# 04: Every Laravel boot goes through `CreatesApplication` and has a cover (PBDIGIT-283 R4)

**Story:** `PBDIGIT-283` remediation, step R4 · **Plan:** `docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md` (criterion 3: enforced coverage)

**Test:** `tests/Architecture/LaravelBootIsGuardedTest.php` (plain PHPUnit; reads sources and reflects classes, never runs them)

**Changed tests** (boot path only; assertions unchanged):
- `tests/Feature/Routes/VotingEligibilityMiddlewareRegressionTest.php`
- `tests/Unit/Application/Election/ConstitutionalTransitionGuardSystemRoleBypassTest.php`
- `tests/Unit/Application/Election/Security/TrustPolicyEvaluatorNetworkRestrictionTest.php`
- `tests/Unit/Contexts/Adjudication/Infrastructure/Outbox/OutboxEventAdapterTest.php`

## Purpose
R1 (marker) and R3 (pre-provider check) both live in `Tests\CreatesApplication::createApplication()`. **A test that boots Laravel some other way gets neither:** under `--no-configuration` it boots on the `.env` database (incident 2026-10-13). R4 makes that impossible to add silently.

## Why direct bootstraps bypassed R1 and R3
Both protections are code **inside** `Tests\CreatesApplication::createApplication()`:
- the R1 marker neutralises the environment before `require bootstrap/app.php`;
- the R3 check is registered on the application right after that `require`.

A test that writes `require bootstrap/app.php` + `Kernel::bootstrap()` itself never enters that method. So it boots on whatever environment the run has: under `--no-configuration`, that's `.env`.

## What the architecture test enforces
1. **Only `tests/CreatesApplication.php` requires `bootstrap/app.php`.** There is one exception, the isolated concurrency worker `tests/Isolated/Pbdigit274/Concurrency/g6-worker.php`, a separate process.
   - **Detection uses PHP's tokenizer, not a regex.** It looks for an executable `require`/`require_once`/`include`/`include_once` whose **string-literal parts**, joined, contain `bootstrap/app.php`.
   - So it catches `$app = require …`, `$this->app = …`, `static::$app = …`, parenthesised forms, `include`, double-quoted paths and paths split across literals.
   - It ignores comments, doc comments, plain string mentions and other `require`s.
   - Twelve snippet cases pin this (`test_the_detector_finds_executable_bootstraps_only`).
   - **Limit:** a path built **only** from variables or constants, with no literal naming `bootstrap/app.php`, isn't detected.
2. **That worker requires the isolated identity guard** (`tests/bootstrap-isolated-database.php`) **before** it boots.
3. **Every Laravel test case** (a subclass of `Illuminate\Foundation\Testing\TestCase`) gets `createApplication()` from `Tests\CreatesApplication`, checked by reflection on the declaring file. No override bypasses the marker and the check.
4. **Every Laravel test case has a cover:** `Tests\TestCase`, `IsolatedDatabaseTestCase`, `IsolatedCommittedDatabaseTestCase`, or `implements DatabaseFreeTest`. The scan must find more than 100 classes.
5. **Every anonymous class using `CreatesApplication` implements `DatabaseFreeTest`.** These can't be reflected by name, so the source is checked. There is one deliberate exception, `tests/Unit/Testing/PreProviderHookTest.php`, whose unmarked fixture proves the R3 check on its own and boots only against non-existent sockets.

## What R4 found and changed
Four plain PHPUnit tests booted Laravel in `setUpBeforeClass()` with `require bootstrap/app.php`. R1's classification had missed them, because they don't use `CreatesApplication`.
- **Three are database-free by design** (routes, auth state, evaluator logic).
- **`OutboxEventAdapterTest`'s last case attempts a real INSERT** and tolerates any failure. Under `phpunit.xml` it wrote to `nrna_test`; under `--no-configuration` it would have tried the `.env` database. The weak assertion is backlog `PBDIGIT-291`.

All four now boot as database-free tests (Principal decision, option A):

```php
// PBDIGIT-283 R4: boot through Tests\CreatesApplication as a database-free test (marker + pre-provider check).
$app = (new class implements DatabaseFreeTest {
    use CreatesApplication;
})->createApplication();
```

## How to boot Laravel in a test
- **Database-backed:** extend `Tests\TestCase`.
- **Database-free, as a Laravel test case:** extend `Illuminate\Foundation\Testing\TestCase`, `use CreatesApplication;` and `implements DatabaseFreeTest` (guide 01).
- **Database-free, from plain PHPUnit** (e.g. once in `setUpBeforeClass()`): the anonymous marked class above.
- **Never** `require bootstrap/app.php` in a test, and never write your own `createApplication()`.

## Testing
**Command (G-RUN-DF, C-1), every run:**

```bash
vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <path>
```

Never `phpunit.pbdigit274.xml`.

- **RED:**
  - check 1 failed, listing exactly the four tests. My first pattern also matched the test's own strings; it was narrowed.
  - The review then showed that the regex **missed** `$this->app =`, `static::$app =`, `include` and split-literal paths. Demonstrated by running the old pattern over the snippet cases: 4 of 8 executable forms missed. It was replaced by the tokenizer detector, and check 5 was added.
  - Checks 2–4 already passed.
- **GREEN:**
  - `LaravelBootIsGuardedTest` 17 tests (12 detector snippets + 5 checks);
  - the four migrated tests: 16, 3, 5 and 4 tests, all green. The outbox INSERT now fails at the dead socket, which the case tolerates.
- **Regression:** the 12 marked tests, all of `tests/Unit/Testing` (72 tests), `DatabaseIdentityGuardTest` 71 and `DatabaseResetIsGuardedTest` 1, all green.
- **What this proves:**
  - the boot paths are guarded **as written in the source**;
  - the migrated tests still pass database-free.
- **What it doesn't prove:** anything about runs against `nrna_test` (R6, G-RUN-POS).

## Pitfalls
- **Anonymous boot sites** are checked by source pattern (check 5), not by reflection. The pattern needs `use CreatesApplication;` as the **first** statement in the anonymous class body. A site written differently escapes check 5, but it still goes through `CreatesApplication`, so the R3 check applies.
- **Known limits of the checks (recorded at acceptance):**
  - a path built only from variables or constants isn't detected (check 1);
  - the literal scan runs to the next `;`, so a `require` nested in a larger statement may over-match (the safe direction);
  - checks 3–4 read the first class declared in each file;
  - check 2 compares text positions, not tokens.
  - The checks don't detect every conceivable dynamically built bootstrap path.
- **A database-free boot in plain PHPUnit leaves the application in the container** for the rest of the process, as before R4. These tests never flushed it.
- **The worker exception is narrow:** it holds only while the worker requires the isolated guard first (check 2).

**Traceability:** `PBDIGIT-283` remediation R4, 2026-10-14, under G-RUN-DF (C-1 command); **accepted by the Principal 2026-10-14** after a final source review (no blocking defect). Builds on R1 `164f74066`, R2 `6d394e6e1`, R3 `17167dec3`. Follow-up `PBDIGIT-291`.
