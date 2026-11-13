# Evidence: G-5a, the published read model (PBDIGIT-346), 2026-11-13

Design: `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g5-read-models-design.md`. Guide: `developer_guide/reference_geography/20_g5a_published_read_model.md`.

| File | Shows |
|---|---|
| `g5a-01-red-domain.txt` → `g5a-02-green-domain.txt` | `PlaceBeliefTest`: RED (no `PlaceBelief`, no `SlotHistory::latest`), then GREEN, 8 tests |
| `g5a-03-red-application.txt` → `g5a-04-green-application.txt` | `PublishedGeographyTest` (fakes): RED, then GREEN, 9 tests. Before GREEN, one expectation was corrected: the place list is sorted by id |
| `g5a-05-red-contract.txt` | the published-contract test: RED on the published-language list. **Deviation:** its signature guard passed on the first run, because the contract was already written |
| `g5a-06-green-architecture.txt` | **the accepted no-bypass guard caught the read side depending on `PublicationLedger`** → the `TimeBoundary` port (interface segregation) |
| `g5a-07-green-architecture-and-unit.txt` | GREEN after that: 246 tests |
| `g5a-08-red-postgres*` → `g5a-09-green-postgres*` | the isolated cluster, `PostgresGeographyReadModelTest`: RED (no adapter, no binding), then GREEN, 6 tests. `-final` is on the final code: after the adapter was changed to build histories through `PostgresPublishedAtlas`, because the no-bypass guard refused its use of `PostgresRevisionRepository::claimOf` |
| `g5a-10-isolated-cluster-all/` | **the full cluster on the final code: 226 tests, exit 0** (the earlier 220 plus 6). `…-before-adapter-change/` is a run that began before that change (also 226, exit 0); kept, but not the evidence of record |
| `g5a-11-db-free.txt` | database-free: 259 tests GREEN (Geography and Identity units, purity, no-bypass, published contract, ratchet) |
| `g5a-12-architecture-full.txt`, `.failures` | 339 tests; **the same 4 pre-existing failures** |
