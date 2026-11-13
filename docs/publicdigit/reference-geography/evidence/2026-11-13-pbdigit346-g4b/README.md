# G-4b evidence (PBDIGIT-346, 2026-11-13)

| File | Shows |
|---|---|
| `red-invalid-fixture.txt` | **invalid first RED:** my fixture used 'official' for a set-valued name type; fixed |
| `red.txt` | valid RED: 7 errors (missing behaviour) |
| `mutation-check-1-r5-survived.out` | **the mutation check found a gap:** the R-1 overlap refusal on rebuild was untested |
| `mutation-check.php` / `.out` | after adding that test: 5 of 5 mutants killed (no source file modified) |
| `db-free-all.txt` | 203 database-free tests pass (incl. the purity and architecture guards) |
| `architecture-full.txt` / `.failures` | 316 tests, the same 4 pre-Geography failures by name |
