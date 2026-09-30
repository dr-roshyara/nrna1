# step164-domain-event-derivation-method

**Scope(s):** THEORY-LEVEL · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Command→Invariant Evaluation→State Transition→Domain Event`, `Event = Past Tense + Domain Fact`, `Invariant + State transition + Domain significance ⇒ Candidate Event`
**Aliases:** `event derivation rule`, `event vs command vs state vs relationship`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0033, scope THEORY-LEVEL: Step 164's disciplined method for deriving domain events rather than creating one per pipeline arrow: a domain event represents a meaningful fact that already occurred (never a command, method call, API endpoint, database update, or intention); Command!=Event (a command may or may not produce its requested event), Event!=State (state may be derived from events without committing to event sourcing), and not every relationship needs an event. Derivation rule: Invariant + State transition + Domain significance => Candidate Event. Naming rule: Event = Past Tense + Domain Fact (good: KnowledgeConfirmed/DecisionMade; bad, because they are commands: ConfirmKnowledge/MakeDecision). Distinguishes domain events (meaningful to the domain model/invariants) from audit events/technical telemetry (file opened, HTTP request received) which should not automatically become domain events, else 'the domain model disappears inside telemetry'.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1357 §"A domain event represents a meaningful fact that has already occurred. It is not: a command; a method call; an API endpoint; a database update; an intention. ... Command ≠ Event ... Command → possibly Event. Not every command produces the requested event. ... Event ≠ State ... we must not assume event sourcing simply because events exist. ... Not every relationship needs an event."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1357 §"Invariant + State transition + Domain significance ⇒ Candidate Event. This is our event derivation method."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1357. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is DORMANT, this is a heuristic based on how recently (by source_id) this label was last used (S1357), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1357 |
| type_signature | PRESENT | S1357 |
| invariants | PRESENT | S1357 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1357 |
| examples | PRESENT | S1357 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1357] types=['DEFINITION', 'DISTINCTION'] scope=THEORY-LEVEL — "Defines a domain event strictly as a meaningful fact that has already occurred (never a command, method call, API endpoint, database update, or intention). Distinguishes Command (request for an action, e.g. ApproveArchitectureChange) from Event (fact that the requested thing occurred, e.g. ArchitectureChangeApproved) -- Command != Event, and a command may not produce its requested event. Distinguishes Event ('something happened', e.g. KnowledgeConfirmed) from State ('what is true now', e.g. Knowledge.status=CONFIRMED) -- state may be derived from events without committing to event sourcing. Also notes not every relationship (e.g. EvidenceSupportsKnowledge) needs a corresponding event unless the transition is itself a meaningful domain occurrence." (anchor: "A domain event represents a meaningful fact that has already occurred. It is not: a command; a method call; an API endpoint; a database update; an intention. ... Command ≠ Event ... Command → possibly Event. Not every command produces the requested event. ... Event ≠ State ... we must not assume event sourcing simply because events exist. ... Not every relationship needs an event.")
- [S1357] types=['PRINCIPLE', 'FORMALIZATION'] scope=METHODOLOGICAL — "States the event derivation rule: Invariant + State transition + Domain significance implies Candidate Event -- used to walk through candidate lifecycles/events for Evidence (Captured/Validated/Accepted/Rejected/Superseded), Knowledge (Proposed/Supported/Confirmed/Contested/Superseded/Rejected), Determination (Candidate/Evaluated/Established/Superseded), Decision (Proposed/Reviewed/Made/Rejected/Deferred/Escalated), Authorization (Requested/Evaluated/Granted/Denied/Expired), and Action/Execution (Created/Executed with Success/Failure/Partial/Unknown outcomes), each candidate model explicitly not yet frozen." (anchor: "Invariant + State transition + Domain significance ⇒ Candidate Event. This is our event derivation method.")
- [S1357] types=['PRINCIPLE', 'EXAMPLE'] scope=THEORY-LEVEL — "States the DDD relationship Aggregate -> protects invariants -> emits events: an event is a consequence of a valid state transition, never the reverse. Worked example: command ConfirmKnowledge(K17) checked against ConfirmationCriteriaSatisfied(K17) -- if false, Command->Rejected with no event; if true, StateTransition SUPPORTED->CONFIRMED emits KnowledgeConfirmed. Where the confirmation rule is deterministic (f(K,E,R) in {true,false}), the chain Deterministic rule -> Deterministic verification -> Evidence holds, and AI must not be allowed to replace an explicitly deterministic rule (it may only assist interpretation). Also restates Confidence != Probability unless statistical semantics are actually defined, warning specifically against interpreting an LLM's 'confidence' field as a calibrated probability." (anchor: "Aggregate → protects invariants → emits events. The event is a consequence of a valid state transition. Not the other way around. ... If false: Command → Rejected. No: KnowledgeConfirmed event should be emitted. If true: StateTransition: SUPPORTED → CONFIRMED then: KnowledgeConfirmed. ... Deterministic rule → Deterministic verification → Evidence. AI can assist interpretation, but should not be allowed to replace a deterministic rule when the rule is explicitly deterministic.")
- [S1357] types=['CONSTRAINT', 'PRINCIPLE'] scope=METHODOLOGICAL — "Distinguishes Event log (preserving meaningful events for audit/history) from Event Sourcing (event stream as the authoritative persistence model) -- EventLog != EventSourcing, and neither should be mandated technology-first. Restrains scope: Step 164 has NOT established that every concept is an aggregate, every transition is an event, all events must be persisted forever, or that Kafka/Event Sourcing/CQRS/microservices are required -- these remain later architecture decisions (DomainRequirement -> ArchitectureDecision, never the reverse). Distinguishes domain events (matter to the domain model/invariants) from audit events/technical telemetry (file opened, HTTP request received), which should not automatically become domain events lest the domain model 'disappear inside telemetry'. States the governing architectural principle for a future constitution: 'KnowledgeOS should derive its event model from domain invariants and legitimate state transitions, rather than deriving its domain model from an infrastructure messaging technology.'" (anchor: "EventLog ≠ EventSourcing. We should not make the latter an architectural requirement unless the evidence and domain justify it. ... DomainRequirement → ArchitectureDecision. ... Does this fact matter to the domain model or its invariants? If not, it belongs in telemetry/logging rather than necessarily in the domain event model. ... We have NOT yet decided to use Event Sourcing. ... that every concept is an aggregate; that every transition is an event; that all events must be persisted forever; that Kafka is required; that Event Sourcing is required; that CQRS is required; that microservices are required. Those remain architecture decisions. ... KnowledgeOS should derive its event model from domain invariants and legitimate state transitions, rather than deriving its domain model from an infrastructure messaging technology.")

## Notes for P3
(none beyond what is noted above)
