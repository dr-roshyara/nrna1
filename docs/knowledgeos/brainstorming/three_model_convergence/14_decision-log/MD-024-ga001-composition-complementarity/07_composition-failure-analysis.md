# Composition Failure Analysis — Exact Obstructions

| Pair | Obstruction | Fundamental or implementation-level? |
|---|---|---|
| 1 (`Validate` ↔ P-3) | `Validate`'s own effect (input type, checking rule, failure behavior) is never specified anywhere in B's legitimate 151-file evidence base | **Fundamental to the current evidence base** — not a simulator/implementation artifact; the specification itself does not exist |
| 2 (`S^epi` ↔ A's tuple) | The `Context` argument (`C`) has no counterpart anywhere in A's own 8-field tuple | **Partially fundamental** — A's own evidence never names a "context" concept for this specific candidate (though A's broader evidence base, outside this specific tuple, does discuss context extensively, §C of A's own register, itself CT-1's own unresolved 4-way dispute) |
| 3 (B's operators ↔ C2's `Θ`) | Neither side specifies enough to attempt any test | **Fundamental**, both directions |
| 4 (B's §J ↔ `ConflictRecord`) | `ConflictRecord`'s own internal structure (if any) is never specified; no map from a single field to a 4-component structure can be attempted without inventing the missing decomposition | **Fundamental to the current evidence base's own C1-side content** — this is exactly the reconstruction MD-023/MD-024 both forbid |

**No obstruction found in this study is an artifact of the composition method used** (e.g. a poor
choice of representation) — every obstruction traces to a genuine absence of specification in the
underlying frozen source, confirmed by the raw-source check in `00`. This distinguishes this study's
own negative results from a "the test was performed badly" failure — the honest finding is that **the
corpus itself has not yet supplied enough operational content on the operator side to test composition
rigorously**, independent of how carefully this study attempted it.
