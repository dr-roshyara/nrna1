# canonical-dependency-graph-29node-five-closures-second-order

**Scope(s):** OBJECT · **Row count:** 6 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** CLOSED/DERIVED, CLOSED/NORMATIVE, CLOSED/PARAMETRIC, CONTRADICTORY, OPEN/DERIVABLE, OPEN/EMPIRICAL, OPEN/NORMATIVE · **Aliases:** SO-EXP-06, five closures computed separately
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0043, scope OBJECT): SO-EXP-06 re-marks the first-order 29-node canonical dependency graph with second-order findings, using the mandate's seven node states, several of which reverse first-order marks (Authority now CLOSED/NORMATIVE with no outgoing edge, breaking the 7-node cycle Assertion->Evidence->Rule->Policy->Authority->Assertion; K, Operation, T_algebra, Equivalence now CLOSED/PARAMETRIC). Computes five closure measures separately, never averaged: semantic 20/29=69.0%, computational 20/29=69.0%, evidential 16/29=55.2%, governance 2/3=66.7% (regime level; open at the individual-act level per G-57), implementation correspondence 15/29=51.7%. The deficit is localised to the Evidence->Assessment->Sigma chain plus Policy/Rule inputs.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1764] §"CLOSED / DERIVED -- 10 (34.5 %) ... CLOSED / PARAMETRIC -- 5 (17.2 %) ... CLOSED / NORMATIVE -- 2 (6.9 %) ... OPEN / DERIVABLE -- 10 (34.5 %) ... OPEN / EMPIRICAL -- 2 (6.9 %) ... OPEN / NORMATIVE -- 0 (0.0 %)"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1764] §"CLOSED / DERIVED -- 10 (34.5 %) ... CLOSED / PARAMETRIC -- 5 (17.2 %) ... CLOSED / NORMATIVE -- 2 (6.9 %) ... OPEN / DERIVABLE -- 10 (34.5 %) ... OPEN / EMPIRICAL -- 2 (6.9 %) ... OPEN / NORMATIVE -- 0 (0.0 %)"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1773. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S1764, S1765 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1764 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1764 |
| experiments | PRESENT | S1764 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
- [S1764] (FORMALIZATION/ANALYSIS) 29-node dependency-graph node marks: CLOSED/DERIVED (10: Observation, Time, Source, Entity, Dimension, Proposition, Provenance, RelationType, History, Lineage), CLOSED/PARAMETRIC (5: Value, K, Operation, T_algebra, Equivalence), CLOSED/NORMATIVE (2: Authority, Governance), OPEN/DERIVABLE (10: Context, Assertion, Relation, Policy, Rule, Evidence, Assessment, Sigma, Measurement, K*), OPEN/EMPIRICAL (2: AuthorityAct, Determination), OPEN/NORMATIVE (0), CONTRADICTORY (0).
- [S1764] (ANALYSIS) Localises the theory's overall deficit to a single chain (Evidence -> Assessment -> Sigma with Policy/Rule as inputs), contrasted against the corrected first-order closure statements (semantic/computational closure previously read as 'NOT achieved' due to O being thought unenumerated; governance previously read 'PARTIAL' due to the now-withdrawn contradiction claim).
- [S1765] (EXPLANATION) Implements Tarjan's strongly-connected-components algorithm in pure Python to test the 29-node re-marked dependency graph for cycles, and separately computes five closure fractions (semantic, computational, evidential, governance, implementation) over five explicitly disjoint node-subset definitions, printed with the explicit caution that they must never be averaged.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S1764]` types=[FORMALIZATION/ANALYSIS] scope=THEORY-LEVEL — "29-node dependency-graph node marks: CLOSED/DERIVED (10: Observation, Time, Source, Entity, Dimension, Proposition, Provenance, RelationType, History, Lineage), CLOSED/PARAMETRIC (5: Value, K, Operation, T_algebra, Equivalence), CLOSED/NORMATIVE (2: Authority, Governance), OPEN/DERIVABLE (10: Context, Assertion, Relation, Policy, Rule, Evidence, Assessment, Sigma, Measurement, K*), OPEN/EMPIRICAL (2: AuthorityAct, Determination), OPEN/NORMATIVE (0), CONTRADICTORY (0)." (anchor: "CLOSED / DERIVED -- 10 (34.5 %) ... CLOSED / PARAMETRIC -- 5 (17.2 %) ... CLOSED / NORMATIVE -- 2 (6.9 %) ... OPEN / DERIVABLE -- 10 (34.5 %) ... OPEN / EMPIRICAL -- 2 (6.9 %) ... OPEN / NORMATIVE -- 0 (0.0 %)")
- `[S1764]` types=[EXPERIMENTAL-RESULT/WARNING] scope=THEORY-LEVEL — "The re-marked graph is acyclic (0 SCCs of size >1): the first-order 7-node cycle Assertion->Evidence->Rule->Policy->Authority->Assertion is broken because Authority's Authority->Assertion edge does not survive (the recorded basis is a reference to an act outside the system). Two cautions stated: acyclicity is purchased by the two-level termination and would return if a deployment internalised authority; and acyclicity does not imply semantic completeness (9 nodes still lack coherent meanings within the acyclic DAG)." (anchor: "strongly-connected components with >1 node: 0 ... is broken because Authority now has no outgoing edge ... Acyclicity is purchased by that termination.")
- `[S1764]` types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Five closures computed separately over the 29-node graph, explicitly never averaged: semantic closure 69.0% (9 nodes lack coherent non-overloaded meaning: Context, Assertion, Relation, Policy, Rule, Evidence, Assessment, Sigma, Determination), computational closure 69.0%, evidential closure 55.2% (16 nodes supported by execution/implementation), governance closure 66.7% at regime level (open at individual-act level per G-57), implementation correspondence 51.7% (15 nodes with a real running instance)." (anchor: "Semantic ... 20/29 = 69.0 % ... Computational ... 20/29 = 69.0 % ... Evidential ... 16/29 = 55.2 % ... Governance ... 2/3 = 66.7 % ... Implementation correspondence ... 15/29 = 51.7 %")
- `[S1764]` types=[ANALYSIS] scope=THEORY-LEVEL — "Localises the theory's overall deficit to a single chain (Evidence -> Assessment -> Sigma with Policy/Rule as inputs), contrasted against the corrected first-order closure statements (semantic/computational closure previously read as 'NOT achieved' due to O being thought unenumerated; governance previously read 'PARTIAL' due to the now-withdrawn contradiction claim)." (anchor: "The theory is empirically strong and semantically weak in exactly one region -- the Evidence -> Assessment -> Sigma chain and its Policy/Rule inputs.")
- `[S1765]` types=[EXPLANATION] scope=METHODOLOGICAL — "Implements Tarjan's strongly-connected-components algorithm in pure Python to test the 29-node re-marked dependency graph for cycles, and separately computes five closure fractions (semantic, computational, evidential, governance, implementation) over five explicitly disjoint node-subset definitions, printed with the explicit caution that they must never be averaged." (anchor: "def strong(v): index[v] = low[v] = idx[0]; idx[0] += 1; stk.append(v); onstk.add(v) ...")
- `[S1773]` types=[VALIDATION] scope=OBJECT — "Raw execution output validating the 29-node mark counts (10/5/2/10/2/0/0 across the seven states) and the five closure percentages (69.0/69.0/55.2/66.7/51.7) reported in doc 05." (anchor: "CLOSED/DERIVED  (10) ... TOTAL                 29 ... FIVE CLOSURES, computed separately (never collapsed) ... 5 IMPLEMENTATION corresp.     15/29  =  51.7%")

## Notes for P3
- No unusual internal tension observed across this label's 6 captured row(s); evidentiary base is proportionate to row count.
