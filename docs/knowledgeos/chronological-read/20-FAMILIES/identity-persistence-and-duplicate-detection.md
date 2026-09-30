# identity-persistence-and-duplicate-detection

**Scope(s):** `THEORY-LEVEL` · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `D(x,y)`, `PrimaryKey != DomainIdentity` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope THEORY-LEVEL): Database keys give only physical identity; duplicate detection is a domain operation with a five-valued outcome, not a boolean.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2782] §"PrimaryKey ≠ DomainIdentity ... D(x,y) in {Same, Equivalent, Distinct, Unknown, Conflict}"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S2782`. Candidate lifecycle: **ACTIVE**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **ACTIVE** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

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
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S2782]` types=[DEFINITION, DISTINCTION] scope=THEORY-LEVEL — "19.7-19.10: identity distinctions (structural =, semantic ≡_sem, inquiry-relative ≈_{Q,Gamma}, provenance-sensitive ≅_prov) must be explicitly persisted since a database primary key gives only PhysicalIdentity, not automatically SemanticIdentity (PrimaryKey≠DomainIdentity); two distinct row ids cannot by themselves distinguish two assertions of the same proposition from two genuinely different entities, requiring PersistenceId, DomainIdentity, ProvenanceIdentity and SemanticEquivalence as separate concepts; reframes duplicate detection D(x,y) as a domain operation returning {Same, Equivalent, Distinct, Unknown, Conflict} rather than a boolean, since UnknownIdentity≠DistinctIdentity and StructuralDifference≠SemanticDifference." (anchor: "PrimaryKey ≠ DomainIdentity ... D(x,y) in {Same, Equivalent, Distinct, Unknown, Conflict}")

## Notes for P3
- Single-row label — thin evidentiary base by construction; any relationship claims beyond this one row would be unsupported.
