# Evidence: G-5 closure (PBDIGIT-346), 2026-11-13

Acceptance record: `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g5-acceptance-review.md`.

| File | Shows |
|---|---|
| `c-01-pg-trgm-differential/` | `PlaceMatcherPgTrgmDifferentialTest` on the isolated cluster: **20 pairs, `PlaceMatcher::similarity` = PostgreSQL `pg_trgm` `similarity()` within 1e-6** (ASCII, case, punctuation, spacing, short strings, digits, ties, band edges, diacritics, Devanagari). **A verification test:** it passed on its first run |
| `mutation-check-g5.php`, `c-02-mutation-check-g5.out`, `mutants/*/suite.txt` | copy-loaded mutants (no source file modified): **9 of 9 killed by assertion failures, 0 errors**, each by its intended test (R1 boundary · R2 merge knowledge · R3 cleared or withdrawn dispute · R4 fuzzy codes · R5 existence at t · R6 feed beyond the knowledge · R7 unpublished slots · R8 exact τ · R9 ordering by text) |
| `c-03-isolated-cluster-all/` | **the full cluster on the final code: exit 0** (includes the 20 differential pairs) |
| `c-04-db-free.txt` | database-free: 282 tests GREEN |
| `c-05-architecture-full.txt`, `.failures` | 341 tests; the same 4 pre-existing failures |

**Code change in this step:** documentation only. The `publicationsSince` docblock now states the consumer obligations.
**Not verified:** CI (nothing pushed; `gh` not authenticated; branch protection isn't visible from the repository).
