# Evidence: PBDIGIT-354 S2-5a, Correct and Void as use cases, and D-240 for a corrected End (2026-11-16)

**Decisions:** D-206, D-208 §2 (GC-14), D-238, D-240; the ratified correction algebra C-1 / C-2 / C-4 (unchanged). **Guide:** `developer_guide/membership/34_correct_and_void_use_cases.md`.
**Author:** Claude (Session A), implementation-agent testing, not an independent review. **Throwaway PostgreSQL 15 cluster only.** No migration.
**Baseline:** `46cf8cc8c` (S2-5 preflight; S2-4 done at `84e13998c`).

| File | Shows |
|---|---|
| `s25a-01-red-unit.txt` | **RED:** 9 tests; the commands, handlers, act kinds and refusal did not exist |
| `s25a-02-red-interim.txt` | **RED:** the interim adapter's `match` had no answer for the new acts (`UnhandledMatchError`) |
| `s25a-03-red-pg.txt`, `s25a-03-red-pg-cluster/` | **RED (PostgreSQL):** 4 tests, **2 failures showing the real gap:** a Correct moved an End onto / after a pending scheduled date (D-240 bypassed); persistence and the allowed cases already passed |
| `s25a-04-green-pg/` | Correct / Void + all scheduled resignation + correction PostgreSQL tests: **71 OK** |
| `s25a-11-routing/` | `MembershipThroughRoutingTest`: **14 OK** (1 new: A's own database, B untouched, both handlers unbuildable in production). **Written after the handlers: no RED run** |
| `s25a-07-full-cluster/`, `s25a-07-cluster-junit.xml` | **full cluster: 527 OK** (522 + 5) |
| `s25a-08-db-free.txt` | **1085 OK** (1074 + 11) |
| `s25a-09-architecture-full.*` | 367 tests: **the same 4 known failures**; nothing new. **They are failures, not passes.** |
| `s25a-10-membership-unit-full.*` | legacy Membership unit: only the known flaky `CommitteeSlugAvailabilityServiceTest` differs |
| `s25a-05-mutation-run.txt`, `s25a-06-mutation-rerun-i1.txt`, `mutants-run*/`, `mutation-check-s25a.php` | 9 mutants (below) |
| `s25a-12-code.diff` | the code and test change of this step |

**Secret sweep:** no hits (passwords, secrets, tokens, API keys, private keys) in this directory.

## Mutants (9, final)

| Class | Mutants |
|---|---|
| assertion kill | M2 (the request names no fact), M3 (the Void keeps no evidence), M4 (the Correct keeps no authority answer), R1, R2 (request validation), **I1 (the interim adapter grants a correction: it SURVIVED the first run; the denial test was missing and was added, then killed)**, **P1 (a Correct never judged by D-240)**, **P2 (D-240 judges the original date, not the corrected one)** = **8** |
| killed by error, by design | M1 (evidence check removed in the handler: the request's own validation throws first; defence in depth) = **1** |
| survivor / equivalent / not run | 0 |

## Not in this step

- **S2-5b:** D-237 (a Renew's `until`, with its migration) and a Restore's date (row 14).
- **Open:**
  - Q-6 (a Suspend's dates);
  - Q-7 (a Renew's start);
  - Q-8 (a Void's report);
  - **Q-9** (a correction after the declaration was superseded; found here): plan §7e.
- **Concurrency:** the D-240 check for a Correct runs at the same point and under the same locks as the S2-4d-Q5 check, whose race tests and Q6 mutant prove them. **No separate race test for a Correct was added:** with a pending declaration and an earlier End, the confirming step supersedes the declaration, which is exactly Q-9 and undecided.
