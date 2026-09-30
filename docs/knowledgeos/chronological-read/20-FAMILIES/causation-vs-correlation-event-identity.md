# causation-vs-correlation-event-identity

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** G_L=(V,E_L), causationId, correlationId · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope OBJECT): Step 204's canonical event-identity schema separating causationId from correlationId and modeling lineage as a causal graph, re-applying temporal-vs-causal ordering at the event level; introduces (unfrozen) ObservedCause vs InferredCause as a candidate distinction.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1423] §"E=(eventId,eventType,aggregateId,context,timestamp,causationId,correlationId,payload,schemaVersion). ... Causation\neq Correlation."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1423] §"G_L=(V,E_L) where: u\rightarrow v means: u contributed causally or derivationally to v. ... This is stronger than simply storing timestamps."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1423. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1423 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1423 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1423 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S1423]` types=[DEFINITION/DISTINCTION] scope=OBJECT — "Proposes a canonical event schema (eventId, eventType, aggregateId, context, timestamp, causationId, correlationId, payload, schemaVersion) explicitly separating causationId (what directly caused this event) from correlationId (which larger process it belongs to) -- important for lineage reconstruction." (anchor: "E=(eventId,eventType,aggregateId,context,timestamp,causationId,correlationId,payload,schemaVersion). ... Causation\neq Correlation.")
- `[S1423]` types=[FORMALIZATION/EXTENSION] scope=OBJECT — "Models lineage as a causal graph G_L=(V,E_L) where edges denote causal/derivational contribution (Observation->Evidence->Assessment->Decision->Action), stronger than merely storing timestamps." (anchor: "G_L=(V,E_L) where: u\rightarrow v means: u contributed causally or derivationally to v. ... This is stronger than simply storing timestamps.")
- `[S1423]` types=[INVARIANT/RESTATEMENT] scope=THEORY-LEVEL — "Re-applies temporal-vs-causal-order and correlation-vs-causation distinctions specifically at the transition/event level, requiring KnowledgeOS to preserve the distinction between observed sequence, dependency, causal claim, and inferred relationship, feeding directly into the Proposition/Assessment model." (anchor: "t_a<t_b does not necessarily imply: a\rightarrow b. ... Corr(A,B)\neq0 does not prove: A\rightarrow B. ... observed sequence; dependency; causal claim; inferred relationship.")
- `[S1423]` types=[EXTENSION/LIMITATION] scope=OBJECT — "Proposes distinguishing ObservedCause from InferredCause as a candidate future vocabulary extension, explicitly not yet frozen -- both are propositions but differ in their evidence classes." (anchor: "ObservedCause from: InferredCause. Both are propositions, but their evidence classes differ. This is a candidate extension for later, not yet a new frozen vocabulary term.")

## Notes for P3
- No unusual internal tension observed across this label's 4 captured row(s); evidentiary base is proportionate to row count.
