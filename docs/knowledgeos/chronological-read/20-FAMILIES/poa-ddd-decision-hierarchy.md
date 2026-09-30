# poa-ddd-decision-hierarchy

**Scope(s):** METHODOLOGICAL · **Row count:** 5 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** POA · **Aliases:** Pattern-Oriented Architecture
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0004, scope METHODOLOGICAL — "The decision hierarchy placing DDD (domain boundaries) above POA (architecture-pattern selection) (S0156), recommending Modular Monolith first, Hexagonal by default, and selective CQRS/Event Sourcing."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0156 §"DDD discovers and protects the domain boundaries. POA selects the architectural patterns used to implement and connect those boundaries."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0156 §"DDD discovers and protects the domain boundaries. POA selects the architectural patterns used to implement and connect those boundaries."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0156. Candidate lifecycle: DORMANT.
Evidence: no retracted_by, no superseded_by, and contested_by_own_contradiction_type is false. All five rows trace to the single source_id S0156; DORMANT is a heuristic based on how long ago that source was last used, not a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0156 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0156 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0156 (x2) |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0156 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
This object addresses the architectural question of how Domain-Driven Design (DDD) and Pattern-Oriented Architecture (POA) relate when designing KnowledgeOS. Its core argument is that DDD and POA are not parallel/sibling layers but operate at different dimensions: DDD discovers and protects domain boundaries (what must be protected), while POA selects the architectural patterns that implement and connect those boundaries (which mechanisms protect it) [S0156]. It explicitly rejects a naive nested-layer model ("KnowledgeOS → DDD Layer → POA Layer") as conceptually wrong [S0156]. As the first concrete POA decision under this hierarchy, it argues for a Modular Monolith (kernel, knowledge-product, governance, evidence, semantic, execution, delivery modules, all initially in one process with enforced boundaries) rather than microservices, drawing an explicit analogy to the Linux-kernel philosophy of modular boundaries preceding physical distribution [S0156].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S0156]` types=[FORMALIZATION, DISTINCTION] scope=METHODOLOGICAL — "Establishes a decision hierarchy for KnowledgeOS: DDD sits above POA — DDD determines domain purpose, boundaries, and invariants; POA (Pattern-Oriented Architecture) selects which proven architectural patterns implement and connect those boundaries (e.g. Hexagonal, Event-driven, CQRS)." (anchor: "DDD discovers and protects the domain boundaries. POA selects the architectural patterns used to implement and connect those boundaries.")
- `[S0156]` types=[WARNING, CORRECTION] scope=METHODOLOGICAL — "Explicitly rejects modelling POA as a second architectural layer alongside DDD, arguing they operate at different dimensions (DDD tells us what must be protected; POA tells us which mechanisms protect it), not as parallel/sibling layers." (anchor: "I would NOT create: KnowledgeOS | +-- DDD Layer | +-- POA Layer. That would be conceptually wrong. DDD and POA operate at different dimensions.")
- `[S0156]` types=[ARGUMENT, ALTERNATIVE] scope=METHODOLOGICAL — "Recommends a Modular Monolith (kernel, knowledge-product, governance, evidence, semantic, execution, delivery modules, all initially in one process with enforced boundaries) over microservices as the first POA deployment decision, comparing it to the Linux-kernel philosophy of modular boundaries preceding distribution." (anchor: "I would NOT start with microservices. I would start with: Modular KnowledgeOS Kernel ... All can initially run in one process. But their boundaries are enforced.")
- `[S0156]` types=[CONSTRAINT] scope=METHODOLOGICAL — "For a future plugin/extension architecture, mandates a strictly one-directional dependency (Kernel → Extension API → Extension) and forbids an extension ever reaching into kernel internals." (anchor: "Kernel ↓ Extension API ↓ Extension. Never: Extension ↓ Kernel internals.")
- `[S0156]` types=[CONSTRAINT, DISTINCTION] scope=METHODOLOGICAL — "Distinguishes 'history matters' from 'everything must be event sourced': recommends Event Sourcing selectively, only for domains where historical reconstruction is itself a requirement (governance decisions, authority changes, evidence conflict resolution, critical lifecycle history), not for user preferences, search indexes, caches, or UI state." (anchor: "History matters ≠ everything must be event sourced. I would use event sourcing only where reconstruction is itself a domain requirement.")

## Notes for P3
Every row of this label comes from a single source document (S0156, `docs/knowledgeos/brainstorming/20260820-115444-poa-vs-ddd-decision-hierarchy.md`) — the evidentiary base is broad in content (5 distinct sub-decisions: layering, monolith-vs-microservices, extension-API direction, event-sourcing selectivity) but narrow in provenance (one author, one document, one point in time). No group_ids connect it to other labels in this P2a pass; P3 may want to check whether the DDD-side counterpart concepts referenced here (domain boundaries, bounded contexts) were captured as separate labels this batch (e.g. `bounded-context`, also in this same LB0007 batch) for cross-referencing, though no identity or dependency claim is made here.
