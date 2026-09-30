# kos-design-pattern-catalogue

**Scope(s):** METHODOLOGICAL · **Row count:** 5 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Tier 1..Tier 4` · **Aliases:** `KnowledgeOS Pattern Architecture`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0004, scope METHODOLOGICAL): A four-tier GoF+DDD design-pattern selection catalogue for KnowledgeOS (S0155), with per-pattern rationale and an explicit prohibition on Singleton in the domain layer.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0155] §"Use a design pattern only when it expresses a real KnowledgeOS domain or architectural problem. Do not build KnowledgeOS as a catalogue of patterns."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0155] §"Tier 1 — Mandatory architectural/domain patterns ... Tier 2 — Frequently useful GoF patterns ... Tier 3 — Selective patterns ... Tier 4 — Avoid by default"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0155. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0155 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0155 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0155 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0155 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Rejects naming KnowledgeOS's historical-reconstruction domain requirement (Knowledge/Evidence/Governance History) after the Memento pattern, arguing Event Sourcing (immutable events, snapshots) is the stronger architectural mechanism for that requirement [S0155].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0155] types=[PRINCIPLE] scope=METHODOLOGICAL — "States the governing pattern-selection principle for KnowledgeOS: a pattern is adopted only when it expresses a real, demonstrated domain or architectural problem; KnowledgeOS itself should never be built as a showcase catalogue of patterns." (anchor: "Use a design pattern only when it expresses a real KnowledgeOS domain or architectural problem. Do not build KnowledgeOS as a catalogue of patterns.")
- [S0155] types=[FORMALIZATION] scope=METHODOLOGICAL — "A four-tier design-pattern catalogue: Tier 1 mandatory (DDD, Bounded Context, Aggregate Root, Value Object, Repository, Domain Event, Domain Service, Specification, Policy, Hexagonal Architecture); Tier 2 frequently useful (Adapter, Strategy, Decorator, Command, Factory, State, Facade, Composite); Tier 3 selective (Chain of Responsibility, Proxy, Builder, Observer, Memento, Bridge, CQRS, Event Sourcing, Saga, Outbox); Tier 4 avoid by default (Singleton, Flyweight, Visitor, Template Method) — 'not because these patterns are bad, but because they don't currently solve a central KnowledgeOS problem.'" (anchor: "Tier 1 — Mandatory architectural/domain patterns ... Tier 2 — Frequently useful GoF patterns ... Tier 3 — Selective patterns ... Tier 4 — Avoid by default")
- [S0155] types=[CONSTRAINT] scope=METHODOLOGICAL — "Explicitly prohibits the Singleton pattern in the KnowledgeOS domain layer, citing hidden global state, poor testability, hidden dependencies, and concurrency problems, and mandates dependency injection through a Composition Root instead." (anchor: "Singleton is prohibited in the domain. Why? It creates: hidden global state; poor testability; hidden dependencies; concurrency problems. Use dependency injection and a Composition Root instead.")
- [S0155] types=[ALTERNATIVE, ANALYSIS] scope=METHODOLOGICAL — "Rejects naming KnowledgeOS's historical-reconstruction domain requirement (Knowledge/Evidence/Governance History) after the Memento pattern, arguing Event Sourcing (immutable events, snapshots) is the stronger architectural mechanism for that requirement." (anchor: "Memento is conceptually related, but Event Sourcing is probably the stronger architectural mechanism for KnowledgeOS where historical reconstruction is a real requirement.")
- [S0155] types=[CONSTRAINT] scope=METHODOLOGICAL — "States a constraint on Decorator usage in KnowledgeOS: decorators (authorization, audit, caching, metrics, evidence) may wrap a core port to add cross-cutting responsibilities but must never secretly change domain meaning or authority (e.g. a decorator must not silently 'make this knowledge approved')." (anchor: "A decorator should not secretly change domain meaning. ... The domain remains authoritative.")

## Notes for P3
Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.
