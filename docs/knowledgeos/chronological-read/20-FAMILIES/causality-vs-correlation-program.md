# causality-vs-correlation-program

**Scope(s):** THEORY-LEVEL · **Row count:** 7 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Cause != Evidence != Correlation != Sequence`, `P(Y|X) vs P(Y|do(X))` · **Aliases:** `Step 194`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 193's closing research program, taken up as Step 194: testing whether KnowledgeOS can distinguish causal order from mere temporal/observed sequence, using the interventionist P(Y|do(X)) vs observational P(Y|X) distinction."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1396 §"Cause \neq Evidence \neq Correlation \neq Sequence. ... P(Y\mid X) versus: P(Y\mid do(X))."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1397 §"P(Y\mid X) does not answer the causal question. The causal question is closer to: P(Y\mid do(X))."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1397. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1397 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1397 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1397 |
| examples | PRESENT | S1397 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S1396 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1396] types=[FUTURE-RESEARCH, OPEN-QUESTION] scope=THEORY-LEVEL — "Opens Step 194: having established TemporalOrder!=CausalOrder, the next test moves from a temporal graph to a causal graph, distinguishing Cause, Evidence, Correlation, and Sequence, and bringing in the P(Y|X) vs P(Y|do(X)) distinction to test whether KnowledgeOS can separate 'what preceded an event' from 'what actually caused it' -- framed as critical for AI-generated knowledge and deterministic assurance." (anchor: "Cause \neq Evidence \neq Correlation \neq Sequence. ... P(Y\mid X) versus: P(Y\mid do(X)).")
- [S1397] types=[DEFINITION, DISTINCTION] scope=THEORY-LEVEL — "Distinguishes four relations between events X and Y -- Precedes (mere T(X)<T(Y)), Correlates, Supports, Causes -- as semantically different, warning that an AI can easily observe X->Y and wrongly conclude X causes Y without an appropriate basis." (anchor: "Precedes(X,Y) ... Correlates(X,Y) ... Supports(X,Y) ... Causes(X,Y). These are different semantic relations.")
- [S1397] types=[INVARIANT, EXAMPLE] scope=OBJECT — "Precedence is the weakest relation and never implies causation on its own; worked example: 'the deployment occurred before the outage' does not establish that the deployment caused it." (anchor: "Precedes(X,Y)\not\Rightarrow Causes(X,Y)")
- [S1397] types=[FORMALIZATION, DISTINCTION] scope=THEORY-LEVEL — "Introduces Pearl-style interventionist causality: observational P(Y|X) (statistical dependence) is fundamentally different from interventional P(Y|do(X=x)) (distribution of Y under a forced intervention on X); the observational quantity alone cannot answer the causal question." (anchor: "P(Y\mid X) does not answer the causal question. The causal question is closer to: P(Y\mid do(X)).")
- [S1397] types=[EXAMPLE, COUNTEREXAMPLE] scope=OBJECT — "Worked confounding example: an AI reporting 'deployments cause outages' from P(Outage|Deployment)=0.25 may be wrong if a common cause (system instability) drives both deployment and outage, so Deployment does not necessarily imply Outage causally -- illustrated with a confounder causal graph." (anchor: "SystemInstability \rightarrow Deployment and: SystemInstability \rightarrow Outage. Deployment and outage correlate because of a confounder.")
- [S1397] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_45: a causal claim must remain distinguishable from the temporal/statistical association that motivated it." (anchor: "I_45: A causal claim must be distinguishable from the temporal or statistical association that motivated it.")
- [S1397] types=[RESTATEMENT, VALIDATION] scope=THEORY-LEVEL — "Step 194 verdict: the causal test strengthens the architecture, restating three mutually consistent results -- Sequence!=Correlation!=Evidence!=Causation, EpistemicStrength!=GovernanceStatus, Decision!=Outcome -- as consistent with Steps 191-193 and the Chapter 1-4 lenses." (anchor: "Sequence\neq Correlation\neq Evidence\neq Causation. And: EpistemicStrength\neq GovernanceStatus. And: Decision\neq Outcome.")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
