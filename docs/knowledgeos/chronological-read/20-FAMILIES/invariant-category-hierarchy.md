# invariant-category-hierarchy

**Scope(s):** THEORY-LEVEL · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `I = I_I u I_T u I_E u I_C u I_G u I_A u I_L` · **Aliases:** `seven invariant categories`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0034`, scope `THEORY-LEVEL`: Step 197's classification of the accumulated invariant set into seven categories (Identity/Temporal/Epistemic/Causal/Governance/Security-Authority/Lineage), used as a discovery method for DDD aggregate boundaries via which invariants must change atomically together.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1415 §"I_I ... I_T ... I_E ... I_C ... I_G ... I_A ... I_L. Then: I= I_I\cup I_T\cup I_E\cup I_C\cup I_G\cup I_A\cup I_L."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1415. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S1415 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1415 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Explains that Uses the invariant-category hierarchy as a discovery method for DDD aggregate boundaries: invariants that must always change together are candidates for one consistency boundary, while independently-evolving invariants (I_a parallel I_b) should not be forced into one aggregate; explicitly rejects designing aggregates from nouns (Evidence aggregate, Knowledge aggregate, Governance aggregate) in favor of deriving boundaries from Invariants+Consistency+Transitions. [S1415]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1415] types=['DEFINITION', 'EXTENSION'] scope=THEORY-LEVEL — "Classifies the accumulated invariant set into seven categories (Identity, Temporal, Epistemic, Causal, Governance, Security/Authority, Lineage invariants) forming the full invariant set I as their union." (anchor: "I_I ... I_T ... I_E ... I_C ... I_G ... I_A ... I_L. Then: I= I_I\cup I_T\cup I_E\cup I_C\cup I_G\cup I_A\cup I_L.")
- [S1415] types=['EXPLANATION', 'ARGUMENT'] scope=METHODOLOGICAL — "Uses the invariant-category hierarchy as a discovery method for DDD aggregate boundaries: invariants that must always change together are candidates for one consistency boundary, while independently-evolving invariants (I_a parallel I_b) should not be forced into one aggregate; explicitly rejects designing aggregates from nouns (Evidence aggregate, Knowledge aggregate, Governance aggregate) in favor of deriving boundaries from Invariants+Consistency+Transitions." (anchor: "Which invariants must hold atomically? If I_a,I_b,I_c must always change together, they are candidates for the same consistency boundary. ... We should not design aggregates from nouns")

## Notes for P3
- Thin evidence base (n=2 rows) — treat conclusions here as provisional.
- Completeness is sparse even relative to its row count (only 2/12 dimensions PRESENT) — most of this object's shape is NOT-EVIDENCED-IN-CAPTURE.
