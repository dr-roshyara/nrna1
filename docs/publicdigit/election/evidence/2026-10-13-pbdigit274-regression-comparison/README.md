# PBDIGIT-274 / 273 regression comparison: every test that passed before must pass now, 2026-10-13

**Authority:** Human PO ("run step 1"; "all tests which were passing before you started to develop must pass now again"; decision "Restore 403" for an election-type mismatch; test fixes approved).

## Method
- **Revisions:**
  - **baseline** `6e477cab3`: the parent of the first PBDIGIT-273 code commit. Every production change since is 273/274 (the stray `118193320` was restored byte-identically by `4d83dffc0`).
  - **HEAD** `fcde1b76b`: then the main checkout with the fixes below.
- **Suites:** the default suites (`tests/Unit`, `Replay`, `Feature`, `Architecture`), with the default exclusion `OverlaySignalCategoryTest`.
- **Isolation:**
  - each revision ran in its own git worktree (scratchpad) with a worktree-local Composer autoloader; `vendor/` packages were symlinked, not copied. Resolved class paths were verified per worktree;
  - no `.env` in either worktree;
  - the isolated bootstrap confirmed `current_database() = pbdigit274_isolated_test` before each run, and pins every PostgreSQL connection.
- **Identical settings:** `-d memory_limit=2G`, the same for both. The default 128 MB ran out after 4,697 tests.
- **`SecurityAssessmentConsolidationTest`:** fails to load in **both** revisions (PHP fatal error, a stale override signature since PBDIGIT-247), so it was excluded from both comparison runs. It was **repaired** (below) and included in the final run.
- **Outcomes:** recorded per test with `--log-events-text`, which survives a fatal error. Compared by `compare.php` (in this folder).
- **Rule:** passed on the baseline and fails now = **regression**. Each regression file was then run **on its own** in both revisions, to separate code effects from test-order effects (the PostgreSQL suite has no per-test rollback, so data persists between tests).

## First comparison (HEAD `fcde1b76b`)
`01-first-comparison-summary.txt`: 21 regressions, 1,925 failing in both, 5 fixed, 152 new tests (147 pass).
- **7 were test-order effects:** they pass on their own in both revisions, or fail on their own equally in both.
- **14 were real, plus 5 failing new tests.** All were fixed:

| Tests | Cause | Fix |
|---|---|---|
| `VoterSlugExpirationTest` (5), `AdminRecoverySlugTest` (4, new), `LegacyVotingFlagsBoundaryTest` (1), `VotingEntitlementUnificationTest` (1) | the classes repeat `use RefreshDatabase;`, which puts back a wrapping test transaction, inside which `VoterSlugIssuer` deliberately refuses | the repeated trait was removed (assertions unchanged) |
| `AdminRecoverySlugTest` | `withoutMiddleware()` with no argument also removed `SubstituteBindings`, so the voter id landed in `$election` | only `EnsureCommitteeMember` is skipped |
| `LegacyVotingFlagsBoundaryTest` | the recovery request lacked the required `reason`, so validation failed before the issuer | `reason` added |
| `VerifyVoterSlugRequestPathTest` (1, new) | a revoked link is now a plain 404 from the lookup (approved plan invariant L-13) | decision-driven expectation |
| `CodeControllerRealElectionBoundaryTest` (4), `CodeEntryAuthorizationTest` (1) | the 273 guard refuses a real link for a demo election before the controller, as a 302 redirect (web) / 500 (JSON) | **product fix (PO: restore 403):** `ElectionMismatchException` answers 403, and web gets the standard 403 page |
| `AtomicParticipationConsumptionTest` r6 (1) | the same mismatch no longer reaches `store()` at all | decision-driven: asserts the 403 refusal before `store()`; the no-consumption assertions are unchanged |
| `SecurityAssessmentConsolidationTest` (whole file) | its recording subclass of `TrustPolicyEvaluator` lacked the PBDIGIT-247 parameter `$ipBindingSatisfied` | the parameter was added and forwarded to the parent: 12 / 12 |

## Final comparison (main checkout with the fixes)
`02-final-comparison-summary.txt`: **all 21 earlier regressions are resolved; all 164 new tests pass** (`02-final-new-tests.tsv`). 11 tests are flagged (`02-final-comparison-flagged.tsv`). Each was run on its own in both revisions:
- **Test-order effects:** pass on their own, identically, in both revisions: `CandidacyPhotoManagementTest`, `ElectionCreationTest`, `StateMachineTransitionAuditTest`, `OrganisationGeographyControllerTest`, `MembershipRenewalTest`, `ExecutionSequenceAuditTest`.
- **Unstable in both revisions:** `VoterEligibilityTest` (the same 4 errors and 3 failures on their own in both) and `CommitteeSlugAvailabilityServiceTest` (3–7 errors per run in both).
- **Not caused by the code:** `MembershipDecisionCreationTest::test_the_named_exclusion_still_holds_only_an_unparsable_file`. It fails because `app/Http/Middleware/VoterSlugStep.php` is **deleted in the working tree (uncommitted, the PO's change)**, and this test lists that file as a known unparsable exclusion (PBDIGIT-272). It passed on committed HEAD. When the deletion is committed, remove the entry from `UNPARSABLE_KNOWN`.

**Result: no test that passed on the baseline fails because of PBDIGIT-273/274.**

**Election unit tests** (`tests/Unit/**election**`, final run):
- 1,199 pass in both revisions;
- 0 regressions;
- 7 new tests, all passing;
- 160 failing in both revisions (old tests, not current);
- 13 skipped.

## Not covered
- **The 1,924 tests failing in both revisions** are old or environment-dependent tests, outside this work.
- **`nrna_test`** was not used. The default configuration's database was replaced by the isolated database for these runs.
