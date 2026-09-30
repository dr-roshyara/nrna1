---
source_track: SEMANTIC-NEUTRAL (design only, not executed)
cross_track_dependency: none
---

# KSME-15 — ML Discovery Design (not executed this pass)

Per the commission's §12/§15: ML is permitted only for candidate discovery, never as proof, and only where
exhaustive computation is infeasible. Every regime validated this pass (three synthetic systems, the
Lane-B `C6`/`C7` reconstruction, the minimality test) had `|E|` small enough for exhaustive exact
computation — the commission's own preferred route ("do not use ML where exhaustive computation is
feasible," §17). **No ML was invoked this pass.** This document records the design for future use, not a
result.

## Design, for when a future regime's `|E|` exceeds exhaustive reach

```
documents / candidate state-component descriptions
        ↓
embeddings / lexical+structural features
        ↓
candidate dependency edges  (P(edge_ij) via gradient-boosted trees)
        ↓
candidate equivalence clusters
        ↓
deterministic validator (BSE's congruence_test / sufficiency_test / necessity_test)
        ↓
accepted / rejected, with a counterexample certificate on rejection
```

Candidate features (never implemented, named only): source-identity match, citation overlap, text
similarity, timestamp proximity, document lineage, transformation lineage, semantic-embedding similarity,
graph distance — matching the commission's own list.

## Binding discipline (unchanged from every prior KSME pass)

`ML → Candidate`, never `ML → Truth`. No ML output may establish semantic identity, equivalence,
congruence, minimality, or Kernel membership — `BSE`'s exact validators remain the sole authority. This
document exists so a future pass does not need to re-derive the design, but it commits to nothing being
built until a regime actually requires it.
