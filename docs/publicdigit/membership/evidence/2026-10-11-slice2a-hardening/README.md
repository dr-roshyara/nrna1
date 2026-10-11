# Slice 2a hardening (PBDIGIT-271: H1, H1-b, H2): evidence, 2026-10-11

**Authority:** Human PO / ARB, 2026-10-11, confirmed in session:
- H1 = A, with the infinite-effective-date exception restored (H1-b);
- H2 = nikic/php-parser with its name resolver;
- documentation D1 … D5 in scope;
- implementation authorised;
- later in the run: a named exclusion plus `PBDIGIT-272` for the unparsable `VoterSlugStep.php`.

**How the files were made:**
- Outputs (`.out`) and exit codes (`.exit`, from `$?`) are copied unchanged.
- **No suite here connects to a database.** No `.env` file or environment variable was read or captured.
- Nothing is committed. HEAD stays `bf605092c` (see `env.txt`); the Slice 2a and hardening changes are in the working tree.

| Stage | Files | Result |
|---|---|---|
| 0 baseline | `00-baseline-*` | Domain 109, Application 60, Infrastructure 13, architecture 5 + 4 + 3 + 1 + 2 + 4, C-1 + G6 2 tests / 5 786 assertions; all exit 0 |
| 1 H1 RED | `01-red-h1-futuredating` | exit 1: **5 assertion failures** ("exception of type InvalidArgumentException is thrown"), no errors |
| 2 H1 GREEN | `02-green-h1-futuredating`, `02-domain`, `02-application`, `02-c1-g6` | exit 0: FutureDating 10 tests; Domain 114; Application 60; C-1 + G6 5 786 |
| 3 command boundary | `03-command-boundary-characterisation` | exit 0 straight away (a characterisation: `effectiveAt` / `decisionDate` are non-nullable `int`) |
| 4 H2 RED (literal-scan stub) | `04-red-h2-creation` | exit 1: **22 assertion failures**, no errors:<br>• 15 forbidden forms missed (P4–P7, P11–P18, P20–P22);<br>• 6 permitted forms reported (N1–N5, N7);<br>• the unparsable source not rejected.<br>Already detected: P1–P3, P8–P10, P19, P23 (the literal substring occurs) |
| 5 H2 GREEN, first run | `05-green-h2-creation` | exit 2: the matrix passes; the **real-tree scan stopped**: `Cannot parse app/Http/Middleware/VoterSlugStep.php` (stop condition, escalated) |
| 6 named exclusion | `06-green-h2-creation`, `06-php-lint-voterslugstep.txt`, `06-php-lint-app-count.txt` | exit 0: 34 tests, real tree clean.<br>• `php -l`: 1 753 of 1 754 files in `app/` are clean; only `VoterSlugStep.php` fails (line 279, Markdown after the code).<br>• **Correction:** a first lint run aborted at that file (xargs stops on exit 255) and only became a verified count after it was rerun without the abort |
| 10 regression | `10-regression-*`, `env.txt` | Domain 114 / 112 541 assertions; Application 61; Infrastructure 13 (DateOrdinal 5 included and also run alone); architecture 5 + 4 + 3 + 1 + 2 + 34; C-1 + G6 2 tests / 5 786 assertions; all exit 0 |

**C-1 / G6:** the oracle (`Support/G6CorrectionOracle.php`, sha256 `57f818fa…`) and the golden fixture (`Fixtures/g6_c1_golden.json`, sha256 `7533ae1b…`, `stream_sha256` `fa62f0d5…`) have no diff. The golden test asserts the stream hash. 0 mismatches.

**Known "issues" lines:** `MembershipDecisionCreationTest` reports "OK, but there were issues! … Deprecations: 3". They come from php-parser 5.4.0 under PHP 8.5 (`SplObjectStorage::attach()` deprecated, `vendor/nikic/php-parser/lib/PhpParser/Parser/Php8.php:2580`). This is library code, not project code; upgrading the dependency is out of scope.

**Not covered:** PostgreSQL (held). The prediction from the earlier Slice 2a evidence that 5 decisions would be explored remains **not observable**; see `../2026-10-11-slice2a/`.
