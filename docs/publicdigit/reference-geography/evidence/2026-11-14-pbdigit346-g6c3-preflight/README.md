# Evidence: G-6c-3 preflight (PBDIGIT-346), 2026-11-14

| File | Shows |
|---|---|
| `c3-00-guide-check-before.txt` | the guide check falsely reports `database, reference_geography` although both guides were updated today: it read only the Write/Edit hook log, and those guides were written by shell |
| `c3-01-guide-check-after.txt` | empty after the fix (it also reads today's commits and uncommitted changes) |
| `c3-02-guide-check-real-gap.txt` | **the safeguard still fires** for a code area with no guide (simulated in a scratch repository) |
| `c3-03-red-migration-safety/` | RED: a finding resolved twice was refused only by a raw index error, naming neither the finding nor the rule |
| `c3-04-green-migration-safety/` | GREEN: the migration stops naming the positions and changes nothing; schema tests 10, 36 assertions |
