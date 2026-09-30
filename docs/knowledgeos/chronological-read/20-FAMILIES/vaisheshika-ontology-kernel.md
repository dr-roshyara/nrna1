# vaisheshika-ontology-kernel

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** H-KOS-Ontology-001, KOS-ONTOLOGY-001, KnowledgeEntity · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0006, scope OBJECT): The shared invariant (independently derived in S0227 and S0228) that every knowledge object must carry an explicit ontological category (entity/property/relation/event/absence), plus the KnowledgeEntity schema (identity, category, qualities, relationships, causal_links).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0227] §"Every knowledge object SHALL have an explicit ontological category describing what kind of entity, property, relation, event, or absence it represents."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0228] §"Knowledge cannot exist without a defined category of existence."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0228. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0227, S0228 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0227, S0228 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- [S0227] types=['DEFINITION', 'INVARIANT'] scope=THEORY-LEVEL — "Extracts from Tarka-Sangraha's Vaisesika ontology (substances, qualities, categories, relations) the lesson 'Before reasoning, define what kind of thing exists', proposing a Reality->Entity->Property->Relation->Context->Evidence structure instead of a Document->Text->Embedding stack. Worked example: 'Database is slow' becomes a structured record with Entity/Property/Relation/Context/Temporal/Evidence fields. Derived invariant H-KOS-Ontology-001." (anchor: "Every knowledge object SHALL have an explicit ontological category describing what kind of entity, property, relation, event, or absence it represents.")
- [S0228] types=['DEFINITION', 'INVARIANT', 'FORMALIZATION'] scope=THEORY-LEVEL — "From Keith's exposition of Vaisesika categories (substance, quality, motion/activity, generality, particularity, inherence, causality, non-existence), proposes a KnowledgeEntity schema (identity, category[entity/property/event/relation/absence], qualities, relationships, causal_links) replacing a Document->Chunk->Embedding->Answer pipeline. Derived invariant KOS-ONTOLOGY-001, with worked example (Payment API: Entity/Quality/Observation/Cause/Temporal state)." (anchor: "Knowledge cannot exist without a defined category of existence.")

## Notes for P3
- Very thin evidentiary base (row_count=2) — this family file is necessarily short; not evidence the object is unimportant, only that capture so far is sparse.
