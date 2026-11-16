# Evidence: PBDIGIT-349 I-3 review corrections (2026-11-15)

**Status:** corrections implemented; **awaiting Principal review.** I-3 is not accepted, not merged and not pushed.
**Where:** worktree `nrna1-349-i3`, branch `identity/pbdigit-349-i3`. Base commit `fcaba62ca` (see `base-commit.txt`); `f372518cd` is an ancestor.
**Who ran it:** Session B, in this session. Nothing here repeats results reported by earlier sessions, except where marked.

## Environment
- **Database:** isolated PostgreSQL 15.19 in a throwaway container (`scripts/testing/membership-pg.sh`, image pinned by digest), on a random port, with its own `platform_pg_*` databases (`07b-…`).
- **Never touched:** the development database, `.env` and `bootstrap/cache` (scratch `APP_SERVICES_CACHE` / `APP_PACKAGES_CACHE`).
- **`09-…`:** the whole `tests/Architecture` directory was run once *outside* the harness. Its 72 database-backed tests could not connect (`fe_sendauth: no password supplied`, test database `nrna_test`). Those are environment errors, not product failures, and no test ran against any database in that run. The database-free guards in it passed.

## Files
| File | What it shows |
|---|---|
| `01a-…` | **RED:** new tests against the unchanged code. 18 errors were `Unknown named parameter $clientKeySecret` (the mandatory secret), plus R-3's failure. *Recorded side effect:* the old `down()` ran during this RED inside the throwaway container, dropped tables and then hit a foreign-key error. That is itself evidence for R-3. |
| `01b-…` | **No RED for R-4 (TDD deviation, recorded).** The new guard was green on its first run: the tree has no outside writer and the fixtures were detected. Its ability to fail is shown by `03-mutant-R4`. |
| `01c-…` | **RED:** the throttle tests (R-2 / R-6) and the race tests, with the secret parameter optional (a second, specific RED). |
| `01d-…` | **RED R-1:** a racing INV-BND-2 conflict surfaced as `IdentityUnavailable (UniqueConstraintViolationException)`. |
| `01e-…` | **RED R-8:** the `BindingRefused` aggregate was R, not the claim. This test was added after that code change, so RED was produced by restoring the old argument. |
| `02-…` | **GREEN:** Identity PostgreSQL (53 tests) after the corrections. |
| `03-mutant-*` | **Each mutant applied alone, then reverted.** All were killed: R1, R2a, R2b, R3, R4, R6, N1, N3. *N1 was not caught by the PI-15 tests (the claim-row lock alone serialises replace against redeem).* N4 and N6 were **not** re-run individually; the original I-3 run applied N1, N3, N4 and N6 together. |
| `04-…` | Final: Identity unit tests, 17 tests and 54 assertions, OK. |
| `05-…` | Final: single-writer guard, 2 tests and 8 assertions, OK. |
| `06-…` | Final: event-registry guard in the isolated cluster, 2 tests and 14 assertions, OK. |
| `07-…` | Final: full isolated cluster, **513 tests, 17,980 assertions, exit 0**, 1 deprecation (pre-existing, not Identity). |
| `08-…` | Final: `RedeemClaimTest` with testdox, 20 tests and 77 assertions. |
| `10-worktree-audit.txt` | Branches, HEADs and status of the Identity worktree and the primary folder. |
| `R-9-proposed-decision-entry.md` | **PROPOSED only, not inserted.** |

## Residuals (reported, not fixed)
- **Purge timing:** throttle rows are purged only when a failure is recorded, so the last expired rows can remain.
- **Constraint matching:** the R-1 constraint is recognised from the exception message, not from the driver's error details.
- **Guard coverage:** the guard is a text scan with known misses (listed in its docblock and in guide 04).
- **Throttle values:** 5 / 10 / 900 s are not ratified.
- **Aggregate choice:** the `BindingRefused` aggregate is now the claim. The design names the Binding as the aggregate for INV-BND-2, so this is a modelling choice to note.
- **Load testing:** no PI-34 load test was performed.
- **Bootstrap claims:** PI-48 is shown for ordinary claims only; bootstrap claims are I-6.

**Separate-context review:** a Claude subagent reviewed the diff (**not an external review**). Its findings and their disposition are in the session log for 2026-11-15.

`SHA256SUMS` covers every file in this folder except itself.
