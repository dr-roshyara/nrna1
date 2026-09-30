# step165-cross-aggregate-consistency-and-god-aggregate-warning

**Scope(s):** THEORY-LEVEL · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `God Aggregate warning`, `aggregate-per-noun warning`, `eventual consistency test: does the domain permit the temporary discrepancy?`, `reference identity not internal state` · **Aliases:** `local vs cross-aggregate consistency`, `ownership vs reference`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope THEORY-LEVEL): Step 165's warnings against both extremes: a single 'God Aggregate' combining Evidence+Determination+Decision+Authorization into one transaction (creating organization-wide transaction scope, high coupling, long transactions, concurrency problems) and an aggregate-per-noun explosion (ObservationAggregate, ClaimAggregate, etc., producing excessive distributed-coordination overhead) -- 'neither one aggregate nor aggregate per noun.' States the rule that across aggregate/context boundaries one references identity, not internal state (e.g. Knowledge references EvidenceID, not embedded Evidence objects); distinguishes ownership (who maintains an object's invariants) from mere reference (referencing K.v7 does not mean owning Knowledge). Gives the eventual-consistency test: the key question is whether the domain permits a temporary discrepancy between aggregates (e.g. Evidence validated before Knowledge is updated), not whether eventual consistency is fashionable, illustrated by a statistical analogy to an asynchronously-updated estimator theta-hat. Also gives a cross-aggregate invariant test: a strong instantaneous invariant (AuthorizationGranted iff DecisionApproved at every instant) may argue for tighter coupling or process coordination, but a weaker invariant (AuthorizationGranted => DecisionExists, tolerant of async creation) does not require one aggregate -- and even a strong invariant should first be checked against a higher-level coordinating process (Saga/ProcessManager) before merging aggregates. Establishes the conceptual hierarchy BoundedContext ⊃ Aggregates ⊃ Entities/ValueObjects, with Processes coordinating aggregates, Events communicating facts, Commands requesting actions, Policies constraining transitions.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1358] §"We should never construct: KnowledgeAggregate containing: EvidenceAggregate + DeterminationAggregate + DecisionAggregate + AuthorizationAggregate. That creates the dreaded: God Aggregate ... The other extreme is: ObservationAggregate EvidenceAggregate KnowledgeAggregate ... AggregateCount → excessive. ... Neither one aggregate nor aggregate per noun."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1358. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S1358 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S1358 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | PRESENT | S1358 |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Warns against two opposite aggregate-design failures: a 'God Aggregate' merging Evidence+Determination+Decision+Authorization into one transaction (organization-wide transaction scope, high coupling, long transactions, concurrency problems, unclear ownership, difficult evolution, and a contradiction of the semantic distinctions already established), and an aggregate-per-noun explosion (one aggregate for every candidate concept, producing excessive distributed-coordination overhead for every small state change) -- 'neither one aggregate nor aggregate per noun.' [S1358].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1358] types=[WARNING, ANALYSIS] scope=THEORY-LEVEL — "Warns against two opposite aggregate-design failures: a 'God Aggregate' merging Evidence+Determination+Decision+Authorization into one transaction (organization-wide transaction scope, high coupling, long transactions, concurrency problems, unclear ownership, difficult evolution, and a contradiction of the semantic distinctions already established), and an aggregate-per-noun explosion (one aggregate for every candidate concept, producing excessive distributed-coordination overhead for every small state change) -- 'neither one aggregate nor aggregate per noun.'" (anchor: "We should never construct: KnowledgeAggregate containing: EvidenceAggregate + DeterminationAggregate + DecisionAggregate + AuthorizationAggregate. That creates the dreaded: God Aggregate ... The other extreme is: ObservationAggregate EvidenceAggregate KnowledgeAggregate ... AggregateCount → excessive. ... Neither one aggregate nor aggregate per noun.")
- [S1358] types=[PRINCIPLE, DISTINCTION] scope=THEORY-LEVEL — "Cross-boundary reference principle: reference identity (e.g. EvidenceID) across aggregate/context boundaries rather than embedding internal state. Ownership is redefined as 'who is responsible for maintaining the invariants of the object' -- referencing K.v7 from a Determination does not mean the Determination owns Knowledge, nor does Decision->D.v3 mean Governance owns Determination. The test for whether eventual consistency is acceptable between aggregates is domain-driven ('does the domain permit the temporary discrepancy?'), never fashion-driven, illustrated by a statistical analogy to an asynchronously-updated estimator (an observation is not invalid merely because a derived estimator has not yet incorporated it)." (anchor: "Across aggregate/context boundaries: reference identity, not internal state. ... Ownership means: Who is responsible for maintaining the invariants of the object? This is the correct DDD interpretation. ... Does the domain permit the temporary discrepancy? Not: Is eventual consistency fashionable?")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
