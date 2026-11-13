# PBDIGIT-346 G-2d-4, and the complete G-2 evidence (2026-11-13)

| File | Result |
|---|---|
| `red.txt` | **RED** Tests: 7, Assertions: 7, Failures: 5.: the 5 cases needing G-6 (2 "allowed" cases passed vacuously until the rule existed) |
| `green.txt` | **GREEN** OK (119 tests, 1055 assertions) (whole domain suite; no earlier test changed) |
| `db-free-all.txt` | **G-0 … G-2 regression:** OK (144 tests, 1065 assertions) |
| `architecture-full.txt` / `.failures` / `reasons.txt` | Tests: 316, Assertions: 964, Failures: 4, Deprecations: 4, PHPUnit Deprecations: 5, Skipped: 1. The 4 pre-existing failures, identical **by name and by message** to G-2d-3 |
| `isolated-cluster/` | all PostgreSQL suites (Membership, Platform, Identity): exit 0, Tests: 154, Assertions: 16203, Deprecations: 1. |

## G-2 steps and their evidence

| Step | Commit | Evidence |
|---|---|---|
| G-2a Revision aggregate | `4e0dece98` | `../2026-11-13-pbdigit346-g2a/` |
| G-2b before-images, dependencies | `7016ddbe1` | `../2026-11-13-pbdigit346-g2b/` |
| G-2c validator (recorded reads, parents, admission, I-1, structural) | `e9b5c0d39` | `../2026-11-13-pbdigit346-g2c/` (incl. the requirement → test index) |
| G-2d-1 endings and succession (G-5, D-228) | `c9b21b464` | `../2026-11-13-pbdigit346-g2d1/` |
| G-2d-2 competence and evidence (D-82, D-227) | `ebffaeb44` | `../2026-11-13-pbdigit346-g2d2/` |
| G-2d-3 tiers, D-83, G-4 | `5b41b69a8` | `../2026-11-13-pbdigit346-g2d3/` |
| G-2d-4 profile changes (G-6) | this commit | here |

## Open for the Principal

**D-82_BASIS** (guide 06): a territorial claim rests on an instrument, with v3.11's exceptions. A direct reading of D-82, unchanged since G-2d-2; **for decision**.
