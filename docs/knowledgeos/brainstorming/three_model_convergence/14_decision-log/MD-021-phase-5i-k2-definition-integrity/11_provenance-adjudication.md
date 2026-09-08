# Phase 5I — Provenance Adjudication (every edge typed, per the authorization's §15)

| Edge | Type | Evidence | Confidence |
|---|---|---|---|
| D285-1 §2 → `t285_reconcile.py`'s `ASSERTION_CONTAINS` | `IMPLEMENTS` (the code encodes the prose's own field set) | Exact set match, re-verified this phase | High |
| D285-6 §3 → `t285_equality.py`'s `UNPACK` | `IMPLEMENTS` | Exact set match (for the 3 named conceptual fields), re-verified this phase | High |
| D285-6 → `t285_equality.py`'s own docstring ("D285-6's own claim... not well-formed") | `CONTRADICTS` (a **self**-contradiction: the script contradicts the very document it implements) | Direct quote, re-verified this phase | High |
| `t285_reconcile.py` T-A → `t285_reconcile.py` T-C | `CONTRADICTS` (intra-file) | Re-executed set arithmetic vs. hardcoded `pi` dict, this phase (`03`) | High |
| seq 0630 §49.76 → `e_equality.py`'s `e` parameter | `REFERENCES` (thematically related — both concern Observation/Evidence) — **NOT `IMPLEMENTS`**, since no code path in `e_equality.py` actually derives `e` from an `Observation` via a `Qualify`-shaped function | Direct code read, this phase | High (for the negative finding: no implementation link exists) |
| seq 0795 → seq 0630 §49.76 | `UNKNOWN` — both state a compatible `Observation→Evidence` conceptual claim, but neither cites the other | This phase's own search found no cross-reference between the two | Medium (absence-of-citation is itself evidence, not proof of independence) |
| D1 (D285-1) vs. D2 (D285-6) | `CONTRADICTS` | Field-set mismatch, re-verified (`01`, `02`) | High |
| D3 (`t285_reconcile.py`) vs. D4 (`t285_equality.py`) | `CONTRADICTS` | Field-set mismatch, re-verified (`01`, `06`) | High |

## Discipline notes

- **No `DERIVES_FROM` edge is asserted anywhere in this table from sequence order or co-location
  alone** — every edge above is grounded in either an exact-match comparison (code implementing prose)
  or an explicit textual statement (the self-contradiction in `t285_equality.py`'s own docstring).
- **`t285_equality.py`'s relationship to D285-6 is `IMPLEMENTS` and `CONTRADICTS` simultaneously** —
  it implements D285-6's own field set (`UNPACK` matches D2) while explicitly contradicting D285-6's
  own *unqualified equality claim* (the "WRONG... CORRECT" self-correction). These are different
  aspects of the same document pair and are not treated as a single, collapsed relationship.
