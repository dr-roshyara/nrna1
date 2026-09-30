# four-level-architecture-hierarchy

**Scope(s):** THEORY-LEVEL · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Domain objects / Aggregates / Bounded Contexts / Processes` · **Aliases:** `unit of knowledge != unit of consistency != unit of process != unit of bounded context`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 205's four-level candidate architecture hierarchy and its capstone three-level distinction (knowledge unit, consistency unit, process unit, and bounded-context unit are all potentially different), framed as preventing a large class of common DDD design errors.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1424 §"No single aggregate owns the entire sequence. Therefore a process manager/application service can coordinate it. ... ProcessManager: Event \rightarrow Command. This is orchestration, not aggregate ownership."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1424 §"Owns(A_i,B_j)\in\{0,1\}. Normally: \sum_j Owns(A_i,B_j)=1. ... 1\rightarrow N is natural: BoundedContext\rightarrow Aggregates. ... A_i \xrightarrow{reference/event} A_j rather than: A_i \supset A_j."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1424. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S1424) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1424 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1424 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S1424]` types=[DEFINITION, DISTINCTION] scope=OBJECT — "Cross-aggregate sequences (e.g. EvidenceValidated->AssessmentRequested->AssessmentCompleted->DecisionRequested) are not owned by any single aggregate; a process manager/application service translating Event->Command coordinates them -- orchestration, not aggregate ownership." (anchor: "No single aggregate owns the entire sequence. Therefore a process manager/application service can coordinate it. ... ProcessManager: Event \rightarrow Command. This is orchestration, not aggregate ownership.")
- `[S1424]` types=[DEFINITION, EXTENSION] scope=THEORY-LEVEL — "Proposes a four-level candidate architecture hierarchy: Level 1 Domain objects (Entity, ValueObject), Level 2 Aggregates (invariant boundaries), Level 3 Bounded Contexts (model boundaries), Level 4 Processes (cross-context orchestration)." (anchor: "Level 1 — Domain objects ... Level 2 — Aggregates ... Level 3 — Bounded Contexts ... Level 4 — Processes.")
- `[S1424]` types=[FORMALIZATION] scope=OBJECT — "Formalizes the aggregate-to-bounded-context ownership relation (normally exactly one owning context per aggregate, one context may own many aggregates) and the preferred aggregate-to-aggregate relationship (reference/event, not containment, which is reserved for true ownership)." (anchor: "Owns(A_i,B_j)\in\{0,1\}. Normally: \sum_j Owns(A_i,B_j)=1. ... 1\rightarrow N is natural: BoundedContext\rightarrow Aggregates. ... A_i \xrightarrow{reference/event} A_j rather than: A_i \supset A_j.")
- `[S1424]` types=[PRINCIPLE, RESTATEMENT] scope=THEORY-LEVEL — "States the most important architectural result of Step 205: the unit of knowledge, the unit of transactional consistency, the unit of process, and the unit of bounded context are all potentially different units, and conflating any two of them is a major, common DDD design error." (anchor: "The unit of knowledge is not necessarily the unit of consistency. And: The unit of consistency is not necessarily the unit of process. And: The unit of process is not necessarily the unit of bounded context. This three-level distinction prevents a huge number of DDD design errors.")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
