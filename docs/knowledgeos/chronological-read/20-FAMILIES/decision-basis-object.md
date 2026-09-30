# decision-basis-object

**Scope(s):** OBJECT · **Row count:** 5 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** B_D=(A_v,P_v,R_v,C,t) · **Aliases:** Decision Basis
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.


## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch `B0034`, scope `OBJECT`: Step 203's new formal composite (not a new domain entity) recording an Assessment's/Decision's versioned dependencies for statistical/governance reproducibility and traceable justifiability.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1422] §"Version(P), Version(E), Version(M), Version(U), Context. ... DecisionBasis=(AssessmentVersion,PolicyVersion,AuthorityVersion,DecisionContext)."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1422] §"D = delta(Assessment,Policy,Authority,Context) subject to: Pre_D=True. Then: Action= alpha(D,Authority,Policy)."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1424. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used (S1424), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1422, S1422, S1422, S1424 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1424 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1424 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1422] types=[DEFINITION, EXTENSION] scope=OBJECT — "Requires an Assessment to retain versioned references to its Proposition, Evidence, Model, Uncertainty, and Context (statistical reproducibility requirement) and a Decision to retain a DecisionBasis (AssessmentVersion, PolicyVersion, AuthorityVersion, DecisionContext), making decisions traceably justifiable even when not computationally reproducible in every case." (anchor: "Version(P), Version(E), Version(M), Version(U), Context. ... DecisionBasis=(AssessmentVersion,PolicyVersion,AuthorityVersion,DecisionContext).")
- [S1422] types=[DEFINITION, CONSTRAINT] scope=OBJECT — "Introduces the formal composite Decision Basis B_D=(AssessmentVersion,PolicyVersion,AuthorityVersion,Context,Time), explicitly not a new domain entity but a formal composite describing a decision's grounds -- a distinction deliberately made to prevent vocabulary inflation." (anchor: "B_D=(A_v,P_v,R_v,C,t). This is not necessarily a new domain entity. It is a formal composite describing what a decision relied upon. This distinction prevents vocabulary inflation.")
- [S1422] types=[FORMALIZATION] scope=OBJECT — "Formalizes the Decision equation D=delta(Assessment,Policy,Authority,Context) subject to a decision precondition, and Action=alpha(D,Authority,Policy), consolidating the complete formal pipeline O_t->E_t->A_t->D_t->X_t->Y_{t+1}->O_{t+1} with Lineage and Identity cross-cutting throughout." (anchor: "D = delta(Assessment,Policy,Authority,Context) subject to: Pre_D=True. Then: Action= alpha(D,Authority,Policy).")
- [S1424] types=[DEFINITION, EXTENSION] scope=OBJECT — "Concretizes the Decision Basis B_D as a set of four versioned references (Assessment, Policy, Authority, Context), so a decision's explanation never depends on today's mutable world -- a major architecture property." (anchor: "B_D = \{AssessmentRef,PolicyRef,AuthorityRef,ContextRef\}. ... Decision is not dependent on today's mutable world to explain yesterday's decision.")
- [S1424] types=[INVARIANT, EXAMPLE] scope=THEORY-LEVEL — "Restates temporal consistency for decision bases: a decision made under Policy_v1 must never be reinterpreted as having used a later Policy_v2 -- historical semantic references are immutable, applying to CurrentPolicy/CurrentAuthority/CurrentModel all differing from their historical counterparts." (anchor: "Decision_t.Basis.PolicyRef=Policy_{v1}. ... Historical semantic references are immutable.")

## Notes for P3
- No unusual internal tensions or notable evidentiary anomalies observed while compiling this file.
