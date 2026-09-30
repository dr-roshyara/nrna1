# actor-projection-of-system-history

**Scope(s):** THEORY-LEVEL · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `K_a(t) subset K_system(t)`, `pi_a: K_system -> K_a` · **Aliases:** `systemic memory exceeds local actor memory`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 197's architectural reinterpretation of the corpus's recurring 'new state doesn't know the old state / Krishna knows continuity' lens as actor-specific projections of a larger, non-omniscient system history; grounds the OperationalCompleteness != HistoricalCompleteness distinction and the DDD bounded-context knowledge-subset principle.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1415] §"Systemic memory can exceed local actor memory. ... K_a(t)\subseteq K_{system}(t) without assuming that the system itself is omniscient."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1415] §"AgentMemory is a projection of: SystemHistory. ... \pi_a: K_{system} \rightarrow K_a. Different actors receive different projections"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1415. Candidate lifecycle: DORMANT. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S1415 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S1415 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S1415 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Reinterprets the corpus's recurring 'Krishna knows continuity' lens architecturally, without personifying it as an AI oracle: the safe abstraction is HistoricalLineage plus GlobalContext -- systemic memory can exceed any individual current actor's local memory (K_a(t) subseteq K_system(t)), without claiming the system itself is omniscient, only that it has a larger preserved lineage [S1415]. Turns the corpus's recurring Chapter-4 'new state doesn't know the old state' observation into a precise, answerable architectural test: yes, provided the transition's own contract is self-sufficient and the historical lineage remains separately recoverable -- distinguishing OperationalCompleteness (minimal context needed for the current transition) from HistoricalCompleteness (context needed for audit/reconstruction), realized as two separate projections pi_op and pi_hist [S1415].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1415] types=[PRINCIPLE, ARGUMENT] scope=THEORY-LEVEL — "Reinterprets the corpus's recurring 'Krishna knows continuity' lens architecturally, without personifying it as an AI oracle: the safe abstraction is HistoricalLineage plus GlobalContext -- systemic memory can exceed any individual current actor's local memory (K_a(t) subseteq K_system(t)), without claiming the system itself is omniscient, only that it has a larger preserved lineage." (anchor: "Systemic memory can exceed local actor memory. ... K_a(t)\subseteq K_{system}(t) without assuming that the system itself is omniscient.")
- [S1415] types=[FORMALIZATION, DEFINITION] scope=OBJECT — "Formalizes actor-specific projections pi_a: K_system -> K_a (e.g. pi_architect, pi_developer, pi_AI); an AI agent starting a new session should not assume it 'remembers everything' -- its memory is one projection of a larger system history, not the whole of it. Projection is not deletion: K_AI subset K_system does not mean the withheld information doesn't exist, enabling least-privilege and context management without destroying provenance." (anchor: "AgentMemory is a projection of: SystemHistory. ... \pi_a: K_{system} \rightarrow K_a. Different actors receive different projections")
- [S1415] types=[ANALYSIS, DISTINCTION] scope=THEORY-LEVEL — "Turns the corpus's recurring Chapter-4 'new state doesn't know the old state' observation into a precise, answerable architectural test: yes, provided the transition's own contract is self-sufficient and the historical lineage remains separately recoverable -- distinguishing OperationalCompleteness (minimal context needed for the current transition) from HistoricalCompleteness (context needed for audit/reconstruction), realized as two separate projections pi_op and pi_hist." (anchor: "Can a new state safely operate without knowing all previous states? Answer: Yes, if the transition contract contains everything required for the current transition and the historical lineage remains recoverable. ... OperationalCompleteness \neq HistoricalCompleteness.")
- [S1415] types=[PRINCIPLE, RESTATEMENT] scope=METHODOLOGICAL — "Restates the DDD bounded-context principle in the projection formalism: a bounded context needs only the knowledge required for its own model and invariants (BoundedContextKnowledge subseteq EnterpriseKnowledge), with cross-context information exchanged only through explicit contracts (BC_A -contract-> BC_B), never uncontrolled internal-model access." (anchor: "A bounded context owns the knowledge required to maintain its model and invariants. It does not have to know everything about the entire enterprise. ... BoundedContextKnowledge \subseteq EnterpriseKnowledge.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
