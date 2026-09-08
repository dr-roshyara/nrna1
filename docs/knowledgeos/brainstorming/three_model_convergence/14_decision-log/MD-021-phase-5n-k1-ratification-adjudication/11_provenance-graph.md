# Phase 5N — Provenance Graph (typed, per the authorization's §11)

| Source | Target | Edge type | Evidence |
|---|---|---|---|
| seq 0630 (step-049) | M₄₉ (K-1's own derivation) | `DERIVES_FROM` | Direct, seq 0630 §49.29/49.30 |
| M₄₉ | seq 0757's own "EXP-02" report | `TESTS` | seq 0757's own text, "survived 50 attack classes" |
| M₄₉ | seq 0764's D-FA-4 | `REFERENCES` (not `RATIFIES`) | D-FA-4's own "M₄₉ = L2 candidate," explicitly not a full ratification |
| `K_t` (the name) | seq 0764's D-FA-6 | `NAMES` then `ACCEPTS` | D-FA-6's own "qualified naming... is adopted" |
| seq 0764 | "the naming register" | `RATIFIES` | D-FA-6's own "the naming register is authoritative" |
| seq 0764 | M₄₉'s own object-level standing | **`UNRESOLVED`** — explicitly, not merely by omission | D-FA-4's own "membership at the object level remains open (OQ-2)" |
| `FA-4-concept-terminology-reconciliation.md` (missing) | D285-1's own citation | `REFERENCES` (target not locatable) | Multiple downstream citations, consistent content, `05` |
| seq 0757 | seq 0764 | **No edge found** | No cross-citation between the two, confirmed via targeted search |
| D285-1 | K-1's own citation row | `CONFIRMS` (inaccurately, per Phase 5M/5N's own findings) | D285-1's own text, bundling multiple sources under one inaccurate identifier |

## Discipline

**No `REFERENCES` edge is upgraded to `RATIFIES` without direct textual support.** The M₄₉→D-FA-4 edge
is the clearest case where this discipline matters: the natural temptation (given D-FA-4's own
`RULING: ACCEPT`) would be to call this edge `RATIFIES` — but the ruling's own text, read precisely,
accepts the *layered structure as a whole* (three kernel traditions, non-forced-unification) while
explicitly leaving M₄₉'s own object-level membership open. The correct edge type is therefore
`REFERENCES`, with the object-level question separately marked `UNRESOLVED`.
