# lineage-quality-and-null-semantics

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `LineageGraph`, `MissingReason={NotMeasured,Unavailable,Withheld,Unknown,NotApplicable}`
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0067, scope THEORY-LEVEL: Typed lineage graph, multi-dimensional data quality (not a single score), the five-way missing/zero/false/unknown/not-applicable distinction, and ReferentialIntegrity != EpistemicIntegrity.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2782 §"LineageGraph rather than merely created_from_id ... Quality(Data) should not automatically be reduced to QualityScore in [0,1] ... NULL semantics must be domain-defined."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2782 §"LineageGraph rather than merely created_from_id ... Quality(Data) should not automatically be reduced to QualityScore in [0,1] ... NULL semantics must be domain-defined."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2782. Candidate lifecycle: ACTIVE.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is ACTIVE, this is a heuristic based on how recently (by source_id) this label was last used (S2782), not a confirmed retirement or a confirmed ongoing status.

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
| warnings | PRESENT | S2782 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2782] types=['FORMALIZATION', 'DISTINCTION', 'WARNING'] scope=THEORY-LEVEL — "19.59-19.64: proposes preserving a full lineage chain Source->Document->Extraction->Proposition->EvidenceEvaluation->Determination->Decision as a semantically-typed LineageGraph rather than a bare created_from_id; data quality (completeness, accuracy, consistency, timeliness, validity, uniqueness, provenance completeness, semantic adequacy) should not collapse into one QualityScore∈[0,1] without explicit dimensions/calibration, since a dataset can be complete-but-inaccurate, accurate-but-incomplete, etc.; persistence must distinguish Missing from Zero from False from Unknown from NotApplicable (a NULL temperature does not reveal which applies) -- NULL is a technical representation and must not automatically become 'Unknown' without an explicit MissingReason mapping {NotMeasured, Unavailable, Withheld, Unknown, NotApplicable}; database constraints (uniqueness, FKs, non-null, checks) give ReferentialIntegrity, not EpistemicIntegrity -- a perfectly normalized DB can store a false proposition and a messy DB can hold valid evidence; normalization/denormalization are neither inherently epistemically superior, the choice depends on Performance+Consistency+QueryRequirements+History+SemanticPreservation." (anchor: "LineageGraph rather than merely created_from_id ... Quality(Data) should not automatically be reduced to QualityScore in [0,1] ... NULL semantics must be domain-defined.")

## Notes for P3
(none beyond what is noted above)
