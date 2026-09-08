# Phase 5J — Provenance Graph (extended from Phase 5I, typed per the authorization's §6)

| Source | Target | Edge type | Evidence | Confidence |
|---|---|---|---|---|
| Step 271 | Step 272A | `REFERENCES` | Step 272A's own header names it as "Predecessor" | High |
| Step 272A | Step 272B | `UNKNOWN` (plausible continuity, not confirmed citation) | Shared, continuous section numbering (272A.x continues into 272B without resetting) — suggestive of single-session authorship, but no explicit textual citation found | Medium |
| Step 272A | D285-7's own "19 candidates, 5 families" claim | `DERIVES_FROM` (content match, not textual citation) | D285-7's own operation-count claim exactly matches Step 272A's own `𝒪_sem^{candidate}` construction; **no citation string names Step 272A**, but the content match is exact and specific enough to exceed coincidence | Medium-High |
| D285-1 | `t285_reconcile.py` | `IMPLEMENTS` | Exact set match (`ASSERTION_CONTAINS`), Phase 5I | High |
| D285-6 | `t285_equality.py` | `IMPLEMENTS` + `CONTRADICTS` (self) | Exact set match (`UNPACK`) + the script's own "WRONG... CORRECT" self-correction, Phase 5I | High |
| D285-1 | D285-6 | `CONTRADICTS` | Field-set mismatch, Phase 5I | High |
| `t285_reconcile.py` | `t285_reconcile.py` (self) | `CONTRADICTS` | T-A vs. T-C, Phase 5I | High |
| `t285_reconcile.py` | D285-1 (its own nominal source) | `CONTRADICTS` — **new edge type this phase** | The script's own T-A computation reproduces the pre-revision framing D285-1's own text explicitly retracted; i.e., the code contradicts the *current* state of the very document it implements | High |
| Step 272A | K-2's `Assertion` field structure | **No edge — explicitly absent** | Confirmed via full-document search, 0 hits | High (absence confirmed) |
| Step 272B | K-2's `Assertion` field structure | **No edge — explicitly absent** | Same | High (absence confirmed) |
| `e_equality.py` | "Reviewer B's mandate E3/E4/E5... Step 246" | `REFERENCES` | Direct quote in the script's own docstring | High |

## Discipline notes (per the authorization's own explicit prohibitions)

- **No `DERIVES_FROM` edge is used merely because one document is dated later** — the Step-272A→
  D285-7 edge above is graded `DERIVES_FROM` specifically because of an *exact content match* (the
  same operation count and family structure), not date order alone.
- **No `SUPERSEDES` edge appears anywhere in this graph** — no evidence of supersession between any
  two corpus research documents was found anywhere across Phase 5I or 5J.
- **No `IMPLEMENTS` edge is used merely because code resembles prose** — every `IMPLEMENTS` edge above
  is backed by an exact set/field match, verified by direct comparison.
