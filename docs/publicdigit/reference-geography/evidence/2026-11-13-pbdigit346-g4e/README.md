# G-4e evidence (PBDIGIT-346, 2026-11-13)

| File | Shows |
|---|---|
| `red-pg.txt` | RED: 12 errors (missing PublishRevision, PostgresPublicationLedger); the 24 earlier tests pass with the before-image column and updated fixtures |
| `green-1-sql-type-defect.txt` | 35 of 36; one error from **my SQL** (an untyped parameter in close-through) |
| `green-pg.txt` | GREEN: 38 PostgreSQL tests, including two forked races and two deterministic lock-wait tests |
| `mutation-check-pg.php`, `mutation-check-pg-1.out`, `-2.out` | 7 of 7 mutants killed, each by its intended test; L1 (no lock) killed by the deterministic wait test |
| `db-free-all.txt` | 212 database-free tests pass |
| `isolated-cluster-all/` | every cluster suite: 192 tests pass |
| `architecture-full.txt` / `.failures` | 316 tests, the same 4 pre-Geography failures by name |

**Order note:** the before-image migration and repository change were written before the RED run. RED was still genuine (missing classes), but it did not isolate the before-image behaviour; test T10 covers it at GREEN.
