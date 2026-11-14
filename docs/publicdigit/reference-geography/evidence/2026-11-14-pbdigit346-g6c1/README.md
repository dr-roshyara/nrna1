# Evidence: G-6c-1, the D-90 notice schema and the person index (PBDIGIT-346), 2026-11-14

| File | Shows |
|---|---|
| `g6c1-01-red-postgres/` | RED: `notice_kind` and `refgeo_publication_persons` don't exist (6 errors) |
| `g6c1-02-green-postgres/` | GREEN: 6 tests, 27 assertions (refusals checked only as "some QueryException") |
| `g6c1-03-reasons/` | **tightened: each refusal must name its intended constraint.** One failure: a test defect (several CHECKs apply to "a revision row with a notice kind"; PostgreSQL reported the finding-shape check). The case was kept with a shape-check marker, and a new case isolates the kind rule |
| `g6c1-04-reasons/` | GREEN: 6 tests, 28 assertions. **All 17 forbidden shapes refused by their intended constraint or trigger** |
| `g6c1-05-isolated-cluster-all/` | **the full cluster on the final code: 285 tests, exit 0** |
| `g6c1-06-architecture-full.txt`, `.failures` | 343; the same 4 pre-existing failures |

**Not code yet:** nothing writes notices (G-6c-3). **No production migration** (G-4 condition).
