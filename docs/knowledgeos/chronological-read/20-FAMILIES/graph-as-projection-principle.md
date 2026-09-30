# graph-as-projection-principle

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `G = P(K)`; `Graph = Projection(KnowledgeOS)`
**Aliases:** "graph must not silently become authoritative"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0033, scope OBJECT: "Step 160's principle that any knowledge graph built from KnowledgeOS should be a derived projection G=P(K) of canonical domain records K, not the source of truth itself, because K->G is safe only if G can be rebuilt while G->K may not be possible; graph must not silently become authoritative unless explicitly decided otherwise."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1352 §"DomainModel → PersistenceModel not: PersistenceModel → DomainModel. We first establish semantics. Only afterward should we determine whether the appropriate persistence mechanism is: relational; document; graph; event log; object store; combination. ... G = P(K) where K is canonical knowledge and P is a projection function ... K → G is safe if G can be rebuilt. But G → K may not be possible. Therefore: Graph should not silently become authoritative."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1352 §"DomainModel → PersistenceModel not: PersistenceModel → DomainModel. We first establish semantics. Only afterward should we determine whether the appropriate persistence mechanism is: relational; document; graph; event log; object store; combination. ... G = P(K) where K is canonical knowledge and P is a projection function ... K → G is safe if G can be rebuilt. But G → K may not be possible. Therefore: Graph should not silently become authoritative."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1352. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S1352), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1352 |
| type_signature | PRESENT | S1352 |
| invariants | PRESENT | S1352 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1352 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE). `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1352] types=[PRINCIPLE, FORMALIZATION] scope=THEORY-LEVEL — "Domain modelling must precede persistence choice (DomainModel -> PersistenceModel, never the reverse); only after semantics are fixed should persistence technology (relational/document/graph/event-log/object-store/combination) be chosen. Formalizes any derived knowledge graph as G=P(K), a projection of canonical knowledge K; K->G is safe only if G is rebuildable, while G->K may not be recoverable, so the graph must not silently become authoritative unless explicitly decided otherwise." (anchor: "DomainModel → PersistenceModel not: PersistenceModel → DomainModel. We first establish semantics. Only afterward should we determine whether the appropriate persistence mechanism is: relational; document; graph; event log; object store; combination. ... G = P(K) where K is canonical knowledge and P is a projection function ... K → G is safe if G can be rebuilt. But G → K may not be possible. Therefore: Graph should not silently become authoritative.")

## Notes for P3

- No internal tension, unknown-candidate marker, or contested-lifecycle discrepancy was observed in this label's own rows; evidentiary base is straightforward for its row count.
