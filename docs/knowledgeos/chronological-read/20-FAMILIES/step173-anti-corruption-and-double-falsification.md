# step173-anti-corruption-and-double-falsification

**Scope(s):** THEORY-LEVEL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Bounded contexts communicate through domain contracts, not accidental sharing of internal models`, `one-big-context vs eight-microservices both rejected` · **Aliases:** `exchange meanings not tables`, `translation boundary between zones`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0033`, scope `THEORY-LEVEL`: Step 173 warns against contexts accidentally coupling through shared persistence (Governance->KnowledgeDatabase; Execution->DecisionDatabase would undermine bounded contexts), stating the principle 'bounded contexts should communicate through domain contracts, not through accidental sharing of internal models' (candidate contract kinds: command/event/query/decision-request/evidence-package/authorization-token). Runs a double falsification against both architectural extremes: a single giant 'KnowledgeOS' context (creates enormous semantic coupling -- an authorization change could contaminate epistemic structures) and eight microservices, one per concept (introduces distributed complexity before independent consistency boundaries are proven) -- both explicitly rejected in favor of 'a small number of semantically coherent bounded contexts, connected through explicit domain contracts.' Also states AI capability != Architecture: the domain model must remain meaningful if the underlying AI model (GPT/Claude/etc.) is replaced, else the architecture is too tightly coupled to technology; AI is 'an actor/capability crossing contexts', never itself a bounded context, validating the earlier Agent->Tools->KnowledgeOS harness symmetry (the agent consumes/produces governed artifacts but never owns the organization's knowledge model).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1366 §"Bounded contexts should communicate through domain contracts, not through accidental sharing of internal models. ... Governance → KnowledgeDatabase. Then governance becomes coupled to epistemic internals. Or: Execution → DecisionDatabase. Then operations becomes coupled to governance persistence."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1366. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1366 |
| dependencies | PRESENT | S1366 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1366, S1366, S1366 |
| examples | PRESENT | S1366 |
| warnings | PRESENT | S1366 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1366] types=['PRINCIPLE', 'WARNING'] scope=THEORY-LEVEL — "States the principle 'bounded contexts should communicate through domain contracts, not through accidental sharing of internal models' (candidate contract kinds: command/event/query/decision-request/evidence-package/authorization-token), warning that Governance directly reading the Knowledge database, or Execution directly reading the Decision database, would couple contexts through accidental persistence sharing and undermine the bounded-context model." (anchor: "Bounded contexts should communicate through domain contracts, not through accidental sharing of internal models. ... Governance → KnowledgeDatabase. Then governance becomes coupled to epistemic internals. Or: Execution → DecisionDatabase. Then operations becom…")
- [S1366] types=['COUNTEREXAMPLE', 'RESTATEMENT'] scope=THEORY-LEVEL — "Runs a double falsification: (1) a single giant 'KnowledgeOS' bounded context is technically possible but creates enormous semantic coupling (an authorization change could contaminate epistemic modeling, a governance policy change could affect evidence modeling); (2) eight microservices, one per concept, introduces distributed complexity before independent consistency boundaries are proven. Both extremes are rejected in favor of 'a small number of semantically coherent bounded contexts, connected through explicit domain contracts' rather than a noun-driven microservice architecture." (anchor: "Could everything be one bounded context called: KnowledgeOS. Yes. Technically. But then ... This creates enormous semantic coupling. ... 8 concepts → 8 microservices. This is equally unjustified. ... A small number of semantically coherent bounded contexts, co…")
- [S1366] types=['PRINCIPLE'] scope=THEORY-LEVEL — "States AI capability != Architecture: the domain model must remain meaningful even if the underlying AI model (GPT/Claude/etc.) is replaced -- if replacement destroys the domain model, the architecture is judged too tightly coupled to technology. AI participates at several points (Observation->Classification, Evidence->Synthesis/Hypothesis, Knowledge->Recommendation, Outcome->AnomalyDetection) as 'an actor/capability crossing contexts', never itself a bounded context, validating the Agent->Tools->KnowledgeOS harness symmetry where the agent consumes/produces governed artifacts but never owns the organization's knowledge model." (anchor: "AI capability ≠ Architecture. The architecture must remain meaningful if the AI model is replaced. If replacing GPT/Claude/etc. destroys the domain model, the architecture is too tightly coupled to the technology. ... Agent → Tools → KnowledgeOS. The agent sho…")

## Notes for P3
- Thin evidence base (n=3 rows) — treat conclusions here as provisional.
