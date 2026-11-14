# Evidence: PBDIGIT-345 task 5b, founding step 3 in its own process (2026-11-14)

| File | Shows |
|---|---|
| `t5b-01-red.txt` | RED: the `OrganisationProvisioner` port doesn't exist (9 errors) |
| `t5b-02-green.txt` | GREEN: Platform unit tests, 53 (step 3, T-9 crash and resume, no routing before step 5, another locator refused) |
| `t5b-03-red-pg/` | RED: `ProcessOrganisationProvisioner` doesn't exist (5 errors) |
| `t5b-04-green-pg/` | the Platform PostgreSQL suite GREEN: **36** (step 3 in a child process, repeat changes nothing, preparer refused in-process, another organisation's database refused, failing child with no password) |
| `t5b-05-arch-process.txt` | the process-boundary architecture test: 4 GREEN |
| `t5b-06-arch-mutant-killed.txt` | mutant: an extra `app/` class using the migration set, the preparer and `enter()`: **3 failures (killed)**; the mutant was removed |
| `t5b-07-isolated-cluster-all/` | the full cluster: **341, exit 0** |
| `t5b-08a-…`, `t5b-09a-…` | T-10's parser read `ON CONFLICT (…)` as a table named "conflict"; `conflict` was added to its SQL-keyword list (like `or`, `of`) |
| `t5b-08-db-free.txt` | database-free: **419 GREEN** |
| `t5b-09-architecture-full.txt`, `.failures` | 351; **the same 4 pre-existing failures**; not passing |
| `t5b-10-guard-mutant-foreign-table-killed.txt` | mutant: the trigger inserting into `users`: T-10 fails (killed); original restored byte for byte |
