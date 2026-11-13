# G-4a evidence (PBDIGIT-346, 2026-11-13)

**Guide:** `developer_guide/reference_geography/13_…`. **Interpretation:** `docs/publicdigit/architecture/reviews/2026-11-13-reference-geography-g4-implementation-interpretation.md`.

| File | Shows |
|---|---|
| `red-1-vacuous-refusal-helper.txt` | **invalid RED:** one test passed vacuously (the helper accepted "table missing"); the helper was tightened |
| `red-2-valid.txt` | valid RED: 6 tests fail on the missing schema |
| `red-3-revised-design.txt` | valid RED after revising the tests to the trigger-advanced design (before any GREEN) |
| `green-1-test-defect-w-unset.txt` | one failure from a **test defect** (W unset); its fix exposed the missing future-W guard |
| `red-4-future-w.txt` | RED: the future-W guard missing (the unguarded update also blocked later publications) |
| `green.txt` | GREEN: 6 tests, 28 assertions |
| `isolated-cluster-all/` | every cluster suite: 160 tests pass |
| `architecture-full.txt` / `.failures` | 316 tests, the same 4 pre-Geography failures by name |
