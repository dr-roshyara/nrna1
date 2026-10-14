# PBDIGIT-290: Resolving the committee-creation handler queries the database, so constructing its controller does too

**Type:** Defect (container wiring / Membership committee creation) · **Epic:** none · **Created:** 2026-10-14 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), from PBDIGIT-283 R1 (the database-free marker turned the query into a loud failure). **Index row:** added 2026-10-14.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the Principal 2026-10-14 (option (b)) |
| **Classification** | **verified in source and observed in a test run** (a query against a non-existent socket, PBDIGIT-283 R1). Production behaviour **not measured** |
| **Scope** | **outside PBDIGIT-283.** PBDIGIT-283 fixed only its test (`VerifyVoterSlugTest` now gathers middleware for `{vslug}` routes only, option (a)); this item changes production code |

## Finding (verified in source)
- **The container binding queries when resolved.** `AppServiceProvider` binds `CreateCommitteeHandler` to a closure that runs `DB::table('governance_level_definitions')->select('level', 'is_active')->get()` **inside the binding** (`app/Providers/AppServiceProvider.php:58-75`, the query at `:61-65`).
- **Its controller takes it in the constructor.** `CreateCommitteeController::__construct(private readonly CreateCommitteeHandler $handler, …)` (`app/Http/Controllers/Committee/CreateCommitteeController.php:18-19`).
- **So any code that constructs that controller queries the database**, whether or not a committee is being created. In particular:
  - Laravel's `Route::gatherMiddleware()` constructs a route's controller to ask for its middleware (`Route::controllerMiddleware()`, the `getMiddleware` branch; the base `App\Http\Controllers\Controller` extends `Illuminate\Routing\Controller`, which has `getMiddleware()`).
  - So does any route-listing or middleware-inspection code that iterates all routes.
- **Observed:** `VerifyVoterSlugTest::test_every_slug_route_agrees_on_demo_type_between_the_binding_and_the_middleware` called `gatherMiddleware()` on every route. Under the PBDIGIT-283 marker the query failed at the non-existent socket (`SQLSTATE[08006] … (SQL: select "level", "is_active" from "governance_level_definitions")`, trace through `AppServiceProvider.php:63`). Before the marker it read whatever database was configured: `nrna_test` under `phpunit.xml`, or the `.env` database under `--no-configuration`.

## Impact
- **Hidden database dependency at construction time:** code that only inspects routes or resolves the controller reaches the database.
- **A missing or unreachable table breaks unrelated code paths** (route inspection, controller construction), not only committee creation.
- **The governance matrix is read once per resolution** and frozen into the handler. Whether that freshness is intended is **not checked** [U].
- **Request-time behaviour:** an HTTP request to the committee route resolves the controller anyway, so the query cost there is unchanged [I].

## Proposed direction (for this item's own analysis; nothing decided)
1. **Read the matrix when the handler runs, not when it's built.** For example, inject a small port (e.g. a governance-matrix reader) and call it inside `handle()`, or make `GovernancePolicy` take a lazy matrix source.
2. **Keep the layer rules:** the port is an Application-layer interface, and its Eloquent/DB implementation lives in Infrastructure (`.claude/CLAUDE.md`, Rules 1–2).
3. **Test first:** resolving `CreateCommitteeHandler` (and constructing `CreateCommitteeController`) runs no query, which a query listener can assert; creating a committee still applies the matrix.
4. **Optional sweep:** check other `AppServiceProvider` bindings for queries at resolution time (read-only first).

## Related
- `PBDIGIT-283`: R1 database-free marker (plan `docs/plans/20261014-1347-pbdigit283-test-database-safety-remediation-plan.md`); the test-side fix in `tests/Unit/Http/Middleware/VerifyVoterSlugTest.php`.
- Production `VerifyVoterSlug::isDemoRoute()` also calls `gatherMiddleware()` (`app/Http/Middleware/VerifyVoterSlug.php:130`). It only reaches the controllers of `{vslug}` routes, which are **not** affected [V for the routes PBDIGIT-283 tests exercise].
