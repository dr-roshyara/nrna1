# PBDIGIT-303: `CompleteVotingFlowIntegrationTest::test_middleware_order_critical` expects `TenantContext` in the `web` group

**Type:** Test infrastructure · **Epic:** none · **Created:** 2026-10-16 · **Raised by:** Claude Code session (self-declared, not attestable), from verifying the election-flow WIP on Laravel 12. **Index row:** added 2026-10-16.

| | |
|---|---|
| **Status** | `OPEN` · not authorised · creation approved by the PO 2026-10-16 |
| **Classification** | **observed:** the test fails; the application configuration is deliberate. Not caused by the Laravel 12 upgrade |
| **Scope** | `tests/Integration/CompleteVotingFlowIntegrationTest.php` only; no application change |

## Finding
- `test_middleware_order_critical` (`tests/Integration/CompleteVotingFlowIntegrationTest.php:237–271`) asserts that `SetLocale`, `HandleInertiaRequests` **and `TenantContext`** are in the `web` middleware group. It fails with "TenantContext should be in web middleware".
- `bootstrap/app.php:39` documents the deliberate design: *"TenantContext is registered as route middleware, NOT web prepend"* (alias `tenant`, line 121). Its order is enforced by middleware **priority** (StartSession → TenantContext → SubstituteBindings, lines 125–126).
- `tests/Integration` is **not** in the default `phpunit.xml` suites. The test therefore never runs in normal runs, and the failure went unnoticed. The other 10 tests in the file pass on Laravel 12.

## Proposed direction (nothing decided)
1. Rewrite the test to assert the actual guarantee: the middleware **priority** order (StartSession before TenantContext before SubstituteBindings), and `tenant` on the routes that need it.
2. Decide whether `tests/Integration` should join a configured suite, so it runs at all.

## Acceptance criteria
1. The test asserts the documented design and passes.
2. The decision about `tests/Integration` and the suites is recorded.
