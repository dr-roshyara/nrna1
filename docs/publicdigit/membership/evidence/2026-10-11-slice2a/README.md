# Slice 2a (PBDIGIT-271) — implementation evidence, 2026-10-11

Authority: Human PO / ARB, 2026-10-11 (S2-G approved, S2-E closed, Slice 2a authorised; confirmed in session). Outputs (`.out`) and exit codes (`.exit`, from `$?`) are copied unchanged. **Database / connection configuration intentionally omitted; no suite here connects to a database.** No `.env` or environment variables were read or captured. Nothing is committed.

| Stage | File prefix | Result |
|---|---|---|
| 0 baseline (HEAD `cedef2dc4`) | `00-baseline-*` | Domain 89, Application 55, DateOrdinal 5, architecture 4 + 3 + 5 + 1, C-1 + G6 2 tests / 5 786 assertions; all exit 0 |
| 1a D-1 first failure | `01a-red-basis-test` | exit 1: 2 assertion failures (refusal, precedence over the invariants) |
| 1a predicted C-1 divergence (guard, fixtures unchanged) | `01a-c1-divergence`, `01a-property-divergence` | C-1 exit 1: exactly 5 mismatches, all depth-1 admissions `admission_basis_missing`; **the "5 decisions explored" prediction is not observable** (the count assertion is not reached). Property run fails at seed 1, step 0 |
| 1b fixtures corrected | `01b-*` | C-1 + G6 5 786 assertions (identical to the baseline, G6 hash asserted by the golden test); Domain 94, Application 55 |
| 1b overlap sensitivity (scratch copy) | `1b-sensitivity-control`, `1b-sensitivity-mutant`, `1b-mutant-vs-repo.diff`, `1b-invariantset-sha-before.txt` | control exit 0; mutant (INV-9 disabled in the copy only) exit 1, assertion failure with `overlapping_membership_periods / kernel: ok` mismatches; working tree unchanged; `InvariantSet.php` checksum unchanged |
| 2 D-1 at the command | `02-command-basis` | exit 0 (passes at once: the domain guard already existed) |
| 3 closed `EndKind` | `03-red-endkind`, `03-green-endkind`, `03-domain`, `03-application` | first failure exit 2 (8 errors: missing `tryFromString`; 1 assertion failure); then exit 0 |
| 4 unknown kind → refusal | `04-red-endkind-command`, `04-green-*`, `04-application`, `04-arch-1a` | first failure: the exception being replaced; then exit 0 |
| 5 decision date | `05-red-futuredating`, `05-green-futuredating`, `05-c1-g6`, `05-domain`, `05-application`, `05-command-future` | first failure exit 2 (5 `TypeError`s); then exit 0; C-1 + G6 still 5 786 |
| 6 interim authority | `06-red-authority`, `06-green-authority`, `06-arch-*` | first failure: classes absent; then exit 0 (End always denied) |
| 7–8b fitness tests | `07-red-ledger`, `08-red-creation`, `07-green-ledger`, `08-green-creation` | first failure: scanner absent; then exit 0, planted violations detected exactly, real tree clean |
| 9 IDEM-4 | `09-idem4` | exit 0 (characterisation) |
| 10 regression | `10-regression-*`, `env-regression.txt` | Domain 109, Application 60, Infrastructure 13, architecture 4 + 3 + 5 + 1 + 2 + 4, C-1 + G6 5 786; all exit 0 |

**Not covered:** PostgreSQL. The held PostgreSQL test files were edited for the new signatures only and were **not executed**.
