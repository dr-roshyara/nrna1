# event-driven-domain-loop

**Scope(s):** THEORY-LEVEL · **Row count:** 10 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `ArchitectureJudgmentRequired`, `Assurance Context`
**Aliases:** `mechanical/semantic assurance split`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0004, scope THEORY-LEVEL: The event-driven reframing of the KnowledgeOS operating loop (S0158, S0159) introducing an Assurance Context that splits Mechanical Assurance (automated) from Semantic Escalation requiring human ArchitectureJudgmentRequired; elevates Transactional Outbox and idempotent consumers to platform invariants.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0158 §"ArchitectureJudgmentRequired ... This does NOT mean: Architecture is wrong. It means: The system cannot determine the answer mechanically and requires an authority-bearing human judgment."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0159 §"The conflict history becomes a domain event stream rather than simply an overwritten database row. That gives KnowledgeOS much stronger reconstruction capability."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0159. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is DORMANT, this is a heuristic based on how recently (by source_id) this label was last used (S0159), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0158, S0159 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0159 |
| dependencies | PRESENT | S0158, S0159 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0158, S0159 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0158, S0159 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0158] types=['DEFINITION'] scope=OBJECT — "Defines 'ArchitectureJudgmentRequired' as a first-class domain concept in a new Assurance Context, meaning specifically that a system cannot mechanically determine the answer and needs authority-bearing human judgment — explicitly distinguished from 'architecture is wrong.'" (anchor: "ArchitectureJudgmentRequired ... This does NOT mean: Architecture is wrong. It means: The system cannot determine the answer mechanically and requires an authority-bearing human judgment.")
- [S0158] types=['CONSTRAINT', 'DISTINCTION'] scope=OBJECT — "Introduces a new bounded context, Assurance, splitting Mechanical Assurance (automated, produces 'mechanical invariants satisfied' or escalates) from Semantic Escalation (human, produces ArchitectureJudgmentRequired), and explicitly forbids Assurance from asserting architectural correctness itself ('Assurance must NOT become Governance')." (anchor: "But Assurance must NOT become Governance... Correct: Assurance ↓ 'Mechanical invariants satisfied' or 'Unable to mechanically establish correctness' ↓ Architecture Judgment Required")
- [S0158] types=['DEFINITION', 'RESTATEMENT'] scope=THEORY-LEVEL — "Refines KnowledgeOS's own definition to add an architectural clause: it is a governed knowledge operating loop where domain changes produce explicit knowledge facts, deterministic assurance separates mechanical from judgment-requiring conditions, evidence is durably preserved, and bounded contexts react to approved facts without sharing implementation." (anchor: "KnowledgeOS is a governed knowledge operating loop in which domain changes produce explicit knowledge facts, deterministic assurance separates mechanically verifiable conditions from matters requiring human judgment, evidence is durably preserved, and bounded contexts react to approved facts without sharing internal implementation.")
- [S0158] types=['DISTINCTION', 'WARNING'] scope=THEORY-LEVEL — "Warns against turning the event bus into the KnowledgeOS database: the durable evidence/state, not the event, is the source of truth; the event is only the fact that lets other contexts react — a distinction meant to protect the durable-evidence-boundary architecture already established for the governance-state work." (anchor: "the event itself is NOT the durable truth. The durable evidence/state is. The event is the fact that allows other contexts to react to that state change.")
- [S0159] types=['PRINCIPLE'] scope=THEORY-LEVEL — "Adopts as KnowledgeOS's core collaboration principle (from an external article): the producing bounded context owns the meaning of a domain event and must not depend on any consumer's internal implementation; consuming contexts own their own interpretation and reaction." (anchor: "The producer owns the business fact; the consumer owns the reaction.")
- [S0159] types=['PRINCIPLE', 'DISTINCTION'] scope=THEORY-LEVEL — "Classifies consistency requirements into strong (synchronous, for invariant-critical concerns like authority changes and approval legality) versus eventual (asynchronous, for search projections, notifications, analytics), stating that which classification applies is a domain decision, not a technology decision." (anchor: "Consistency requirements are domain decisions, not technology decisions.")
- [S0159] types=['EXTENSION', 'PRINCIPLE'] scope=THEORY-LEVEL — "Elevates the Transactional Outbox pattern to core KnowledgeOS infrastructure, arguing that because KnowledgeOS is fundamentally about trustworthy history, it cannot allow a committed knowledge-state change whose corresponding event is lost; ties this directly to the durability/evidence architecture." (anchor: "I would elevate Transactional Outbox to a core KnowledgeOS infrastructure pattern. ... We cannot allow: Database committed + Event lost.")
- [S0159] types=['EXTENSION'] scope=METHODOLOGICAL — "Proposes adding a new verification category, Event Scenario Tests (alongside Domain/Aggregate/Contract/Integration/Architecture tests), which test a Given/When/Then event flow across multiple bounded contexts rather than a single method, testing the architecture itself." (anchor: "We should therefore introduce 'Architecture Scenario Tests' ... Event Scenario Tests ← NEW")
- [S0159] types=['WARNING'] scope=THEORY-LEVEL — "Explicitly rejects adopting microservices, Kafka, Event Sourcing, and CQRS wholesale as a default technology stack merely because event-driven architecture is now favoured, prescribing instead a disciplined decision sequence: DDD boundary → aggregate → domain event → determine consistency requirement → sync/async → add Outbox if needed → add idempotency/retry → verify event scenario." (anchor: "I would NOT make KnowledgeOS: Microservices + Kafka + Event Sourcing + CQRS just because we now like event-driven architecture.")
- [S0159] types=['EXTENSION', 'FORMALIZATION'] scope=CROSS-OBJECT — "Reframes the R-CONFLICT concept as an event stream (EvidenceQualified events from two sources triggering EvidenceConflictDetected, then Conflict Resolution, then ConflictResolved), arguing this gives much stronger reconstruction capability than treating the conflict as an overwritten database row." (anchor: "The conflict history becomes a domain event stream rather than simply an overwritten database row. That gives KnowledgeOS much stronger reconstruction capability.")

## Notes for P3
(none beyond what is noted above)
