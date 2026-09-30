# temporal-and-bitemporal-persistence

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `T=<T_occurrence,...,T_validity>`; `x=<Content,VT,TT>` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0067, scope THEORY-LEVEL: "Multi-dimensional temporal tuple and bitemporal (valid-time/transaction-time) storage, with a warning that two time dimensions are not automatically a complete temporal ontology."

No `single_candidate_flags` recorded.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2782 §"T = <T_occurrence,T_observation,T_recording,T_publication,T_acquisition,T_decision,T_validity> ... AsOf_valid(t) != AsOf_knowledge(t)"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2782 §"T = <T_occurrence,T_observation,T_recording,T_publication,T_acquisition,T_decision,T_validity> ... AsOf_valid(t) != AsOf_knowledge(t)"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2782. Candidate lifecycle: ACTIVE.
Evidence: `lifecycle_evidence` is entirely empty (no retracted_by, no superseded_by, no contested_by_own_contradiction_type). This ACTIVE classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2782 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2782 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (`rationale_evidence` is empty; `rationale_truncated_count` is 0).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S2782] types=[FORMALIZATION, DISTINCTION] scope=THEORY-LEVEL — "19.14-19.16: time cannot always be one timestamp -- proposes a 7-dimension temporal tuple T=<T_occurrence,T_observation,T_recording,T_publication,T_acquisition,T_decision,T_validity>, illustrated by an event occurring Jan1 but being learned Jan10 (T_occurrence≠T_knowledge, both must be preservable); recommends bitemporal storage x=<Content,VT,TT> (valid time vs transaction/knowledge time) enabling AsOf_valid(t)≠AsOf_knowledge(t) queries, but warns bitemporal storage (two time dimensions) is not automatically a complete temporal ontology -- additional dimensions (occurrence/publication/observation/decision/execution/model-version/contract-validity time) may still be needed depending on the contract." (anchor: "T = <T_occurrence,T_observation,T_recording,T_publication,T_acquisition,T_decision,T_validity> ... AsOf_valid(t) != AsOf_knowledge(t)")

## Notes for P3
Single-row label with a clean, self-contained formalization and an explicit self-warning (two time dimensions ≠ complete temporal ontology) inside the same row — no internal tension. Evidentiary base is thin (one source) but internally COMPLETE per its own completeness field. No group_ids, so no cross-label relationship to flag.
