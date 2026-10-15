# PBDIGIT-286 P5 evidence (G-ISO), 2026-10-14

**Authorisation:** G-ISO and P5 only (Principal, 2026-10-14 18:54 UTC). Nothing else authorised. The review-findings task (F1–F7, same day) authorised the database-free runs below and one more run of the P5 isolated file.

**P5 is not accepted.** It must not be released without P6 (plan §8, review F1).

## Database-backed (G-ISO, `pbdigit274_isolated_test` only)

**Target verification before each run:**
- the credentials file `.pbdigit274.env` exists, is git-ignored and untracked; it was sourced in a subshell, and values were never printed (set/unset checks only);
- `PBDIGIT274_TEST_DB` accepted by `DatabaseIdentityGuard::approvedName()` as `pbdigit274_isolated_test`;
- no config cache.

| File | Command | Outcome |
|---|---|---|
| `01-isolated-run.txt` | `. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml tests/Isolated/Pbdigit274/Database/VoterAttestationEvidenceTest.php` | 19:01 UTC. **OK, but there were issues:** 7 tests, 8 assertions, **3 deprecations**, exit 0. Guard line: `current_database() = 'pbdigit274_isolated_test' confirmed`. Secret scan clean. |
| `06-isolated-run-f4.txt` | same command, after adding the F4 unknown-status case | 20:50:56–20:51:07 UTC. **OK, but there were issues:** 8 tests, 12 assertions (the earlier 8 + 4 statuses), **3 deprecations**, exit 0. Guard line confirmed `pbdigit274_isolated_test`. Secret scan clean. A first attempt (~22:45 local) was denied by the tool's permission classifier and executed nothing; it was retried after the Principal re-issued the brief. |

## Database-free (C-1 command, no PHPUnit configuration, dead-socket database targets)

Command form: `vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <path>`.

| File | Test | Outcome |
|---|---|---|
| *(not saved)* | `TrustPolicyAttestationEvidenceTest`, during implementation | RED 6 errors + 1 failure → GREEN 7 tests, 17 assertions. Recorded by the author; **no raw output exists**. |
| `02-dbfree-p5-attestation-evidence.txt` | `TrustPolicyAttestationEvidenceTest` with the F4 adapter-failure test | **OK, but there were issues:** 8 tests, 18 assertions, **3 deprecations**, exit 0 |
| `03-dbfree-network-restriction-regression.txt` | `TrustPolicyEvaluatorNetworkRestrictionTest` (regression) | **OK:** 5 tests, 8 assertions, exit 0 |
| `04-dbfree-laravel-boot-guard-regression.txt` | `tests/Architecture/LaravelBootIsGuardedTest.php` (regression) | **OK, but there were issues:** 18 tests, 30 assertions, **3 deprecations**, exit 0 |
| `05-dbfree-p5-deprecations-listed.txt` | `02` re-run with `--display-deprecations` | same result; lists the 3 deprecations |

**The 3 deprecations** (`05`): `Constant PDO::MYSQL_ATTR_SSL_CA is deprecated since 8.5`, at `vendor/laravel/framework/config/database.php:61` and `:81` and `config/database.php:66`. They come from configuration loading under PHP 8.5, not from P5 code.

**What the F4 tests can and cannot prove:**
- The adapter-failure test passed against the real adapter: with every database target at a dead socket, the call threw instead of returning false.
- No mutation run shows the new tests failing against a weakened adapter (a `try/catch` returning false, or `status != 'revoked'`). The temporary mutation was denied by the tool's permission classifier, so this rests on reading the source.

## Not run
- **`TrustPolicyEvaluatorTest`, `TrustPolicyEvaluatorD4Test`:** they extend `Tests\TestCase` (D4 also uses `RefreshDatabase`), so they are not database-free and must not run with `--no-configuration`. Through `phpunit.xml` they run on `nrna_test`, so they need **G-AUTH-T** (plan §4, decision record §6), which isn't granted.
- **The admission suite and the `nrna_test` feature tests** (`VotingSecurityVerdictAdapterTest` and others): they need G-AUTH-T, which isn't granted. The admission code has no diff.
- **`IpVoteLimitFlowTest`** (correction 2026-10-15, second review N1; an earlier version of this list grouped it with the `nrna_test` tests): it is an **isolated** test (`tests/Isolated/Pbdigit274/Database/IpVoteLimitFlowTest.php`, `pbdigit274_isolated_test`). Running it needs a G-ISO authorisation beyond P5. Not run.
- **Behaviour gaps, not covered by any executed test:** no caller-level test shows that code entry or the vote is refused when the verification store fails (the callers' catch blocks were only read); no test shows the System B decision changing for an attested voter (only `remainingVotes` is checked); no mutation proof (above).
- The full suite, and any test outside P5.
