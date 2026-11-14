# Evidence: PBDIGIT-345 task 6, HTTP entry E-1 (2026-11-14)

| File | Shows |
|---|---|
| `t6-01-red.txt` | RED: `IdentityBindings` and `EntryRefusal` don't exist |
| `t6-02-green.txt` | GREEN: Platform unit tests, 82 (every refusal reason, T-15 revoked after selection, another account, Identity unavailable) |
| `t6-03-red-pg/` | RED: `OrganisationEntry` doesn't exist (the test cannot load) |
| `t6-04-red-adapters.txt`, `t6-05-green-unit.txt` | the production adapters: RED, then 84 GREEN |
| `t6-06a-route-hidden-by-stale-local-route-cache/` | the selection route was invisible: the developer's local `bootstrap/cache/routes-v7.php` (19 Oct, stale) replaced the routes. The route is now loaded via `loadRoutesFrom`, and the isolated harness ignores any local route cache |
| `t6-06b-legacy-route-model-binder-owns-organisation-parameter/` | 404: the legacy global `Route::model('organisation', …)` resolved the test route's `{organisation}` in the legacy table. Registry routes never use that name; the route check enforces it (T-10 part 3) |
| `t6-06-green-pg/` | the Platform PostgreSQL suite: **49 GREEN** (T-1, T-15, T-10 part 3, 503 on outage) |
| `t6-07-mutation-run.txt`, `mutation-check-t6.php`, `mutants/` | 9 copy-loaded mutants (no source file modified): **all KILLED by assertions, 0 survivors** |
| `t6-08-db-free.txt` | database-free: **454 GREEN** |
| `t6-09-isolated-cluster-all/` | the full cluster: **354, exit 0**, now with routes loaded from the route files |
| `t6-10-architecture-full.txt`, `.failures` | 355; **the same 4 pre-existing failures**; not passing |
