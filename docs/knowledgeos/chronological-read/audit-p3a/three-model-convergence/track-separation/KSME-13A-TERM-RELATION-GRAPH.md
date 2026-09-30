---
source_track: TRACK-A-PHASE-MEASURE
input_artifacts: [KSME-12-TERM-RELATION-GRAPH]
derived_from: [all KSME-13/13A fork reports]
cross_track_dependency: none
---

# KSME-13A — Term Relation Graph (extends KSME-12's)

See `KSME-13A-DEPENDENCY-GRAPH.md` for the full consolidated edge list — this file records only the
relationships that are genuinely new symbol-identity findings (not already captured as dependency edges),
per the requested `is-equivalent-to`/`is-distinct-from`/`instantiates`/`generalizes` vocabulary.

- `Authorize_277.20 --instantiates-in-context--> Authorize_277.25` (a real refinement, same document)
- `Authorize_Step32 --is-distinct-from--> Authorize_Step259 --is-distinct-from--> Authorize_277.20/.25`
  (three-way disjunction, confirmed by bidirectional search)
- `Conflict_Step32algebra --is-distinct-from--> ConflictSet/ConflictRecord --is-distinct-from-->
  ConflictStatus_Step32state --is-distinct-from--> Conflicted --is-distinct-from--> A+¬A` (five-way
  disjunction within Step 32 alone)
- `Conflict_Step60predicate --is-distinct-from--> Conflict(p,t)` (arity mismatch, same document)
- `Contr_step292 --is-distinct-from--> Contr_theory08` (`merely-analogizes` only, confirmed by direct
  re-read of both primary sources)
- `⊕_Step32 --is-distinct-from--> ⊕_Step279 --is-distinct-from--> ⊕_20260826arch` (three-way disjunction)
- `Revision --is-distinct-from--> Promote` (a real, easy-to-conflate pair — `Promote` is fully typed,
  `Revision` is not)
- `research/knowledgeos-sim (BC-02.14-20) --generalizes-into-question--> whether prior-session findings
  may inform current KSME dependency closure` [GOVERNANCE-DEPENDENT, not resolved]

## What this graph does not do

Does not merge any of the disjoint families above. Does not resolve the `research/knowledgeos-sim`
admissibility question. Extends, does not replace, `KSME-12-TERM-RELATION-GRAPH.md`.
