# Evidence: G-5b, PlaceLookup (PBDIGIT-346), 2026-11-13

Design: `…/2026-11-13-reference-geography-g5-read-models-design.md` §4. Guide: `developer_guide/reference_geography/21_g5b_place_lookup.md`.

| File | Shows |
|---|---|
| `g5b-00-test-defect-final-method-name.txt` | a test-file defect: the helper `name()` collided with PHPUnit's final method. Renamed to `placeName()` |
| `g5b-01-red-domain.txt` → `g5b-02-green-domain.txt` | `PlaceMatcherTest`: RED (no classes), then GREEN, 9 tests. Includes the `pg_trgm` value `similarity('word', 'two words')` = 0.363636 |
| `g5b-03-red-application.txt` → `g5b-04-green-application.txt` | lookup in `PublishedGeographyTest`: RED (no `lookup`). The first GREEN attempt **failed one test: places were ordered by matched text, contrary to the design** (score, whole, then id). The code was fixed, not the test. GREEN: 21 tests (with the matcher) |
| `g5b-05-red-contract.txt` | the published-contract guard went RED **by itself:** `LookupMatch` was not yet on the published-language list |
| `g5b-06-red-postgres*` → `g5b-07-green-postgres*` | the isolated cluster: RED (the adapter lacked `slotsOfKind`), then GREEN, 11 tests |
| `g5b-09-db-free-1-purity-guard-name-variable.txt`, `g5b-10-architecture-full-1-…` | **the accepted domain-purity guard refused a `$name` variable** in `PlaceMatcher` (a personal-data safeguard). Renamed; the guard was not weakened |
| `g5b-09-db-free.txt` | database-free: 276 tests GREEN |
| `g5b-10-architecture-full.txt`, `.failures` | 341 tests; the same 4 pre-existing failures |
| `g5b-08-isolated-cluster-all/` | **the full cluster on the final code: 231 tests, exit 0.** `…-before-rename/` is the same suite before the variable rename (also exit 0), kept but not of record |
