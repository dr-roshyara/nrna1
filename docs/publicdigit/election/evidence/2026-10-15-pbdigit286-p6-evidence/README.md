# PBDIGIT-286 P6 boundary step: evidence (2026-10-15)

**Scope:** D7 (`>=` in `NetworkTrustEvidence::exceedsLimit`) and D7a (`NetworkThresholdInterpreter` Initial minimum 1). **Not in scope:** the D6 count step, P7, P8. **Database:** none. Every run used `vendor/bin/phpunit --no-configuration --bootstrap vendor/autoload.php <file>` on plain-PHPUnit or `DatabaseFreeTest` files only. These files are the raw output.

| File | What | Result |
|---|---|---|
| `01-dbfree-baseline-before-p6.txt` | 23 files touching the IP limit, before any change | all OK; `TrustPolicyAttestationEvidenceTest` 8/18 with 3 deprecations |
| `02-dbfree-red.txt` | the 4 changed test files, before the production change | **RED:** `NetworkTrustEvidenceTest` 7 failures, `NetworkBindingPolicyTest` 2, `NetworkThresholdInterpreterTest` 2, `TrustPolicyAttestationEvidenceTest` 1; every failure is "at L" or the minimum |
| `03-dbfree-green-and-regression.txt` | the same 23 files + `LaravelBootIsGuardedTest`, after the change (UTC timestamp on line 1) | **GREEN:** all exit 0; `NetworkTrustEvidenceTest` 17/36, `NetworkBindingPolicyTest` 9/14, interpreter 9/18, `TrustPolicyAttestationEvidenceTest` 9/23 |

**The deprecations (3)** are `PDO::MYSQL_ATTR_SSL_CA` under PHP 8.5, in `config/database.php:66` and the framework's copy. They're not from P6.

## D6 and the isolated runs (G-ISO for P6 items 1–4, authorised by the Principal 2026-10-15)
**Pre-run checks, each passed:**
- the env file is present, git-ignored and untracked;
- no `bootstrap/cache/config.php`;
- `approvedName` = `pbdigit274_isolated_test`;
- the `PBDIGIT274_*` variables are set (checked set/unset only).

Every isolated file shows `current_database() = 'pbdigit274_isolated_test' confirmed`. Command prefix: `. ./.pbdigit274.env && vendor/bin/phpunit -c phpunit.pbdigit274.xml`.

| File | What | Result |
|---|---|---|
| `04a-…-first-draft-observation-error.txt` | first RED attempt, before the fix | **Not valid RED:** 4 errors from the test reading a security event that isn't reliably there (allowed events are sampled ~10% and written on their own connection; and until PBDIGIT-42 the event INSERT itself fails, see `21`) + 1 failure "Unverified, at L = 3" (the real defect). The test was changed to observe the decision; the assertions were unchanged |
| `04b-isolated-d6-red.txt` | `SystemBNetworkCountTest`, before the fix | **RED 4 failures, all the D6 defect:** same /64 not counted; IPv4-mapped spelling not counted; with no tenant context even an exact-IP vote isn't counted; grouped boundary never reached. Every "must not count" check passed |
| `05-isolated-d6-green.txt` | same, after the fix | **GREEN** 5 tests, 14 assertions, exit 0 |
| `06a-…-demo-fixture-not-reached.txt` | `VerificationStoreFailureTest`, first run | control, code entry, real vote passed. Demo: the test's own precondition failed (store never asked): the fixture's demo election wasn't visible through `ElectionMiddleware`'s tenant-scoped `Election::find`. Fixture fixed (organisation + session); assertions unchanged |
| `06b-isolated-caller-fail-closed.txt` | same, after the fixture fix | 4 tests, 25 assertions, exit 0 |
| `07-isolated-TrustPolicyEvaluatorTest.txt` | gap 2 (`Tests\TestCase`, resets the isolated database after its name check) | 5 tests, 9 assertions, exit 0 |
| `08-isolated-TrustPolicyEvaluatorD4Test.txt` | gap 2 | 3 tests, 7 assertions, exit 0 |
| `09-isolated-IpVoteLimitFlowTest.txt` | gap 6 | 6 tests, 47 assertions, exit 0 |
| `10-isolated-IpVoteLimitAtCastTest.txt` | System A at the cast | 8 tests, 34 assertions, exit 0 |
| `11-isolated-VoterAttestationEvidenceTest.txt` | P5 | 8 tests, 12 assertions, exit 0 |
| `12-dbfree-architecture-and-regression-after-d6.txt` | 4 architecture tests touching Elections/Services, `LaravelBootIsGuardedTest`, the 23 IP files | 28 files, all exit 0 |

Every file reports the same 3 deprecations (`PDO::MYSQL_ATTR_SSL_CA`, PHP 8.5).

**Not run:**
- the full isolated suite (needs its own authorisation);
- anything on `nrna_test` (G-AUTH-T not granted).

The plan's `nrna_test` P6 tests and the gap 1 mutation proof ran on the isolated database instead (B′ and A, below).

## Gap closure: Authorisations C, A, B′ (Principal, 2026-10-15)
**Baseline before execution:** HEAD `fddc9bb1e`; 134 working-tree entries; nothing staged; 1 stash (untouched); adapter sha256 `9e6bd98e721b224e26ff0d40449b4706d38d1afd125474446e9c0717aa1118ee`.

**C, test and comment corrections (`14-authorisation-c-test-diff.patch`, 3 files, +20/−18):**
- `VotingSecurityVerdictAdapterTest`:
  - the oracle and `densityOf` use `IpVoteLimit::votesFrom` (D6);
  - `a02` = 2 votes → Allowed;
  - new `a02b` = 3 votes → Denied (D7).
- Comments only: `CodeEntryAuthorizationTest:39-41`, `:334`; `SecurityPolicySurfaceCharacterizationTest:70`.
- No production code.

**B′, the eight files on `pbdigit274_isolated_test`:**
- **Pre-run checks:** env file present, git-ignored, untracked; no config cache; `approvedName` correct; variables set.
- **Guard:** confirmed in every file.

| File | Result |
|---|---|
| `15-…SecurityWhitelistPolicyTest` | 10/13, exit 0 |
| `16-…VotingSecurityVerdictAdapterTest` | 11/40, exit 0 (including `a02`, `a02b`) |
| `17-…CodeEntryAuthorizationTest` | 12/64, exit 0 |
| `18-…SecurityPolicySurfaceCharacterizationTest` | 9/20, exit 0 |
| `19-…SecurityAssessmentConsolidationTest` | 12/44, exit 0 (also 2 PHPUnit deprecations, test-side) |
| `20-…ConstitutionalImmutabilityTest` | 13/52, exit 0 |
| `21-…SecurityEventRecorderTest` | **5 tests, 3 failures, exit 1:** no `election_security_events` row written (`test_deny_result_always_recorded`, `…recorded_at_timestamp`, `…policy_evaluation_sequence`) |
| `22-…CastDecisionEnforcementTest` | 6/34, exit 0 |

**`SecurityEventRecorderTest`: not a P5/P6 defect; probable cause PBDIGIT-42 (code reading, not runtime-confirmed).**
- **Probable cause:** on a freshly migrated schema the recorder's INSERT leaves NOT NULL, no-default columns empty:
  - `overlay_influence_chain` and `final_constitutional_outcome` (create migration `2026_05_26_000001…:23,25`);
  - `constitutional_outcome` (ensure migration `2026_05_27_120001…:29`).

  The recorder swallows the error, so no row appears. This is the known state recorded in ADR_20260806_1340 §7, pending **PBDIGIT-42** (OPEN).
- **Why not P5/P6:** the test calls `SecurityEventRecorder` directly with a hand-built context. No P5/P6 code is on its path, and the recorder, the event model, `config/database.php`, the test and `tests/TestCase.php` have no working-tree changes (recorder and model last changed `2ef38297a`, 2026-08-06).
- **Safety:** the event model writes on `pgsql_audit`. The isolated bootstrap confines `DB_AUDIT_*` too (`DatabaseIdentityGuard::PREFIXES`), so the target was `pbdigit274_isolated_test`.
- **Why the cause is unknown:** the recorder swallows its error (by design, ADR Audit_Event_Transaction_Boundary) and logs a warning. Reading that warning from `storage/logs` was **denied by the permission classifier**; not retried.
- **Never run before:** this file has never run under `phpunit.pbdigit274.xml`, and its `nrna_test` result is not available (G-AUTH-T not granted).

**A, mutation proof:**
- **Original saved:** copy taken; both hashes `9e6bd98e…18ee`.

| File | Mutation | Target | Result | Restored sha256 |
|---|---|---|---|---|
| `23` | M1: `catch (\Throwable) { return false; }` | `test_the_database_adapter_does_not_swallow_a_read_failure` (database-free) | **failed as expected:** `'returned false'` ≠ `'propagated'` | `9e6bd98e…18ee` ✔ |
| `24` | M2: no `status` filter | `test_a_revoked_row_does_not_count`, `test_a_row_with_an_unknown_status_does_not_count` (isolated, guard confirmed) | **2 failures as expected** | `9e6bd98e…18ee` ✔ |
| `25` | M3: no `election_id` filter | `test_evidence_of_another_election_or_another_voter_does_not_count` (isolated, guard confirmed) | **failed as expected** | `9e6bd98e…18ee` ✔ |
| `26` | restored adapter | `TrustPolicyAttestationEvidenceTest` + `VoterAttestationEvidenceTest` | 9/23 and 8/12, exit 0, guard confirmed | `9e6bd98e…18ee` ✔ |

**Final database-free regression (`27`):** 30 files (architecture, admission authorisation, the IP set, boot guard), all exit 0.

**Production code is unchanged since the isolated P6 runs `05`–`11`**, which are not repeated.

## Unreadable-IP proof (Authorisations T1, T2, Principal, 2026-10-15)
- **Pre-run checks passed;** guard confirmed.

| File | Run | Result |
|---|---|---|
| `28-isolated-unreadable-ip.txt` | `--filter test_an_unreadable_ip_counts_only_by_exact_value tests/Isolated/Pbdigit274/Database/SystemBNetworkCountTest.php` | 1 test, 2 assertions, exit 0: a different unreadable value doesn't count; the same value counts |

- **Not a RED/GREEN cycle:** this verifies `votesFrom`'s exact-match fallback, which was already implemented.
- **Committed state:** P5 + P6 are in `2f998b8ae` (not pushed). The test added here and these documentation updates are uncommitted.
