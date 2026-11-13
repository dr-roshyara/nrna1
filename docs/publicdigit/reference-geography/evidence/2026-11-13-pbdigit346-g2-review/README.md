# G-2 acceptance review evidence (PBDIGIT-346, 2026-11-13)

**Report:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g2-acceptance-review.md`. **No production code was changed in this review.**

| File | Shows |
|---|---|
| `verification-tests.txt` | UndeterminedPositionTest: 4 verification tests (null positions; scheme isolation). They passed on their first run, because they verify existing behaviour; they are not RED tests |
| `probe-cross-country-move.php` / `.out` | **Finding F-1:** moving a province into another country validates with no violation, although its child district's derived position now sits above it |
| `g2-domain.txt` | 123 domain tests pass |
| `db-free-all.txt` | 148 database-free tests pass (Reference Geography, Identity, purity and architecture guards) |
| `isolated-cluster/` | the PostgreSQL suites (Membership, Platform, Identity), 154 tests pass. Geography has no database code yet |
| `architecture-full.txt` / `.failures` / `reasons.txt` | 316 tests, 4 failures: names identical to the slice 1 baseline (before Geography), messages identical to G-2d-4 |
