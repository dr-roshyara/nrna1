# kr-zoom01-traversal-order-noncommutativity

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** 0/518, ~30% observable match · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0067, scope OBJECT: "KR-ZOOM-01's finding that traversal order changes internal state (0/518 matching) even when contract observables agree in ~30% of cases; second independent non-commutativity observation after KR-ZERO-ALGEBRA H2."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2807 §"Traversal order never produced the same final state -- 0 of 518 admissible pairs. But about 30% produced the same contract observable. ... same observable ⇏ same internal state. ... This is the second independent observation of non-commutativity, after KR-ZERO-ALGEBRA H2."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2807. Candidate lifecycle: ACTIVE. Evidence: `retracted_by` and `superseded_by` are both empty, `contested_by_own_contradiction_type` is false. This is a heuristic based on how recently (by source_id) this label was last used — its only occurrence (S2807, batch B0067) — not a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2807 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (family.rationale_evidence is empty for this label; rationale_truncated_count = 0).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE (family.assumption_register is empty for this label).

## All rows (source_id order)

- [S2807] types=[EXPERIMENTAL-RESULT] scope=OBJECT — "In the KR-ZOOM-01 experiment, traversal order never produced the same final internal state in 0 of 518 admissible pairs, while about 30% produced the same contract-observable output — same-observable does not imply same-internal-state; flagged as the second independent observation of non-commutativity (after KR-ZERO-ALGEBRA H2); warns a test looking only at the observable could have wrongly concluded T1∘T2 = T2∘T1." (anchor: "Traversal order never produced the same final state -- 0 of 518 admissible pairs. ...")

Experiment record (from family.candidate_births / the row's own `experiment` field): hypothesis = traversal order is interchangeable (commutative) at the level of internal state; setup = 518 admissible traversal-order pairs tested for final-state equality and contract-observable equality; method = compare final internal states and final contract-observables across order permutations; result = 0 of 518 pairs had identical final internal state, ~30% had identical observable; interpretation = internal-state non-commutativity is real and can be masked by observable-level equivalence; conclusion = same observable does not imply same internal state, semantic-equivalence definitions must specify exactly what is observed.

## Notes for P3
- This is a genuine single-row, single-source (S2807) label reporting a completed experimental result (0/518, ~30%), not a hypothesis or proposal — the strongest-evidenced kind of single-row label in this batch.
- The row explicitly cross-references a separate prior finding, "KR-ZERO-ALGEBRA H2," as the first independent observation of the same non-commutativity phenomenon this experiment reports as the second — no group_id currently links this label to a `kr-zero-algebra` or `H2`-named label, so this cross-reference is visible only in the row text. Flagging for P3 as a strong candidate for a real relationship (convergent, independent confirmation of the same underlying phenomenon), though not asserted as an identity here.
