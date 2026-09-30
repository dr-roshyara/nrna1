# polyglot-persistence-and-canonical-projections

**Scope(s):** `THEORY-LEVEL` · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Loss_Q = Dist(K)\Dist(R_Q(K))` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope THEORY-LEVEL): Neutrality between graph/relational storage, the requirement for one canonical state under polyglot persistence, and the formal projection-loss acceptability test.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2782] §"Multiple physical stores must not create multiple incompatible semantic truths. ... Loss_Q = Dist(K)\Dist(R_Q(K)). This is acceptable when Loss_Q ∩ Dist_EC(K) = ∅."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2782] §"Multiple physical stores must not create multiple incompatible semantic truths. ... Loss_Q = Dist(K)\Dist(R_Q(K)). This is acceptable when Loss_Q ∩ Dist_EC(K) = ∅."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S2782`. Candidate lifecycle: **ACTIVE**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **ACTIVE** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2782 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2782 |
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
19.27-19.31: a graph database is a persistence technology, not an epistemology (Graph≠KnowledgeState, may omit contracts/uncertainty/temporal/assumptions/authority/revision semantics); relational storage is often equally suitable, so the theory implies neither KnowledgeGraph>RelationalDatabase nor the reverse -- choice is contract/workload-dependent; KnowledgeOS may legitimately use polyglot persistence (relational for aggregates, object store for artifacts, graph store for traversal, search index, event store, vector index) but must define a canonical semantic state K with projections R1(K),R2(K),R3(K) and an authority rule for which representation answers which semantic question (a vector index may answer similarity, not determination -- SimilarityResult≠Determination); formalizes projection Loss_Q=Dist(K)\Dist(R_Q(K)) as acceptable exactly when Loss_Q∩Dist_EC(K)=∅, giving a rigorous read-model design test. [S2782]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S2782]` types=[ARGUMENT, FORMALIZATION] scope=THEORY-LEVEL — "19.27-19.31: a graph database is a persistence technology, not an epistemology (Graph≠KnowledgeState, may omit contracts/uncertainty/temporal/assumptions/authority/revision semantics); relational storage is often equally suitable, so the theory implies neither KnowledgeGraph>RelationalDatabase nor the reverse -- choice is contract/workload-dependent; KnowledgeOS may legitimately use polyglot persistence (relational for aggregates, object store for artifacts, graph store for traversal, search ind…" (anchor: "Multiple physical stores must not create multiple incompatible semantic truths. ... Loss_Q = Dist(K)\Dist(R_Q(K)). This is acceptable when Loss_Q ∩ Dist_EC(K) = ∅.")

## Notes for P3
- Thin evidentiary base (1 row) — any relationship claims beyond what is listed here would be unsupported.
- Ungrouped: no mechanical cross-link signal connected this label to any other label in P2a.
