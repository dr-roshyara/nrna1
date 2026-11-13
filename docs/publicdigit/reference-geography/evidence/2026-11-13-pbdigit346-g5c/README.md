# Evidence: G-5c, the publication feed (PBDIGIT-346), 2026-11-13

Design: `…/2026-11-13-reference-geography-g5-read-models-design.md` §3.4, §5. Guide: `developer_guide/reference_geography/23_g5c_publication_feed.md`.

| File | Shows |
|---|---|
| `g5c-01-red-domain.txt` → `g5c-02-green-domain.txt` | `PublicationSummaryTest`: RED (no class), then GREEN, 3 tests. `g5c-02a-test-defect-sort-expectation.txt`: **a test defect.** The expectation was sorted by letter label, not by unit id; the code was right, and the test was corrected |
| `g5c-03-red-application.txt` → `g5c-04-green-application.txt` | the feed in `PublishedGeographyTest`: RED (no `publicationsSince`), then GREEN, 15 tests |
| `g5c-05-red-contract.txt` | the published-contract guard RED by itself: `PublicationEntry` not yet on the published-language list |
| `g5c-06-red-postgres*` → `g5c-07-green-postgres*` | the isolated cluster: RED (the adapter lacked `publicationsBetween`), then GREEN, 12 tests |
| `g5c-08-isolated-cluster-all/` | **the full cluster on the final code: exit 0** (see `suite.txt`) |
| `g5c-09-db-free.txt` | database-free: 282 tests GREEN |
| `g5c-10-architecture-full.txt`, `.failures` | 341 tests; the same 4 pre-existing failures |
