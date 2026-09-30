# aggregate-sizing-symmetry-trap

**Scope(s):** METHODOLOGICAL · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `six-test checklist`
**Aliases:** `one concept = one aggregate is a trap`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0034, scope METHODOLOGICAL: Step 205's six-test aggregate sizing checklist and its explicit warning against the 'symmetry trap' of assuming every distinct concept deserves its own aggregate; includes the conditional KnowledgeArtifact aggregate example.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1424 §"Test A: Does it have an identity? ... Test F: Can other aggregates reference it without owning it? If: A+B+C are true, we have strong evidence for an aggregate."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1424. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is DORMANT, this is a heuristic based on how recently (by source_id) this label was last used (S1424), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1424 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1424 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1424 |
| examples | PRESENT | S1424 |
| warnings | PRESENT | S1424 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S1424] (ANALYSIS, RESTATEMENT): Produces the current candidate-confidence table across eleven concepts (Proposition/Evidence/Assessment/Model/Policy High, Authority Medium-High, Decision Very High, Action High, Outcome/Uncertainty/Lineage Low), explicitly warning against forcing the low-confidence items into aggregates just for diagram symmetry.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1424] types=['EXTENSION', 'DEFINITION'] scope=METHODOLOGICAL — "Proposes a six-test aggregate sizing checklist (identity, invariant ownership, atomicity requirement, independent lifecycle, contention avoidance, referenceable-without-ownership) with the first three (identity+invariant+atomicity) together constituting strong evidence for an aggregate." (anchor: "Test A: Does it have an identity? ... Test F: Can other aggregates reference it without owning it? If: A+B+C are true, we have strong evidence for an aggregate.")
- [S1424] types=['ANALYSIS', 'RESTATEMENT'] scope=OBJECT — "Produces the current candidate-confidence table across eleven concepts (Proposition/Evidence/Assessment/Model/Policy High, Authority Medium-High, Decision Very High, Action High, Outcome/Uncertainty/Lineage Low), explicitly warning against forcing the low-confidence items into aggregates just for diagram symmetry." (anchor: "| Candidate | Aggregate confidence | Reason | ... Decision | Very High | ... Outcome | Low | externally determined | ... Lineage | Low as aggregate | cross-cutting concern |")
- [S1424] types=['WARNING', 'PRINCIPLE'] scope=METHODOLOGICAL — "Names the 'symmetry trap': assuming one concept should always map to one aggregate is aesthetically attractive but architecturally dangerous; DDD optimizes for cohesion plus consistency minus coupling, not symmetry -- architectural asymmetry is explicitly acceptable." (anchor: "OneConcept=OneAggregate. That is aesthetically attractive but architecturally dangerous. DDD does not optimize for symmetry. It optimizes for: Cohesion+Consistency-Coupling. ... Architectural asymmetry is acceptable.")
- [S1424] types=['DEFINITION', 'EXAMPLE'] scope=OBJECT — "KnowledgeArtifact becomes a legitimate aggregate only if it owns its own invariant, e.g. publication requiring an approved assessment plus complete lineage (I_KA); it may represent the point where epistemic material becomes an organizationally reusable artifact, but Assessment->KnowledgeArtifact is never automatic -- publication is itself a domain decision." (anchor: "I_{KA}: Published \Rightarrow ApprovedAssessment \land CompleteLineage. Now it has a legitimate aggregate boundary. ... Assessment \rightarrow KnowledgeArtifact but not automatically. Publication itself is a domain decision.")

## Notes for P3
(none beyond what is noted above)
