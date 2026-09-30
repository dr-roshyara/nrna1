# selection-procedure-object

**Scope(s):** OBJECT · **Row count:** 5 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `SelectionProcedure`, `selection audit` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0073**: candidate group with `situational-provenance` — explicit agent-stated uncertainty: 'situational-provenance' POSSIBLY relates to 'selection-procedure-object' (batch B0012). Note: Chinese-philosophical-lens extension of provenance beyond source (who/when/where) and acquisition (how) to situation: conditions, environment, relation to surrounding context, phase, actors, constraints, what changed, what was absent/excluded; a number itself (e.g. a 95% success rate) is said to be insufficient without its relations. (mechanical signal only; relationship not yet decided, P3).



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0012, scope OBJECT): Achinstein's rule determining how observations are obtained/tested for a hypothesis; proposed as first-class epistemic provenance (distinct from ordinary source provenance) because a biased selection procedure can guarantee its own result (raven example) and must be auditable via an eight-question Selection Audit.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0451] §"Selection Procedure ... a rule determining how to test, or obtain evidence for or against, a hypothesis. ... Evidence is partly determined by how you obtained the observation."
- CANDIDATE-CONCEPTUAL-BIRTH: [S0451] §"Selection Procedure ... a rule determining how to test, or obtain evidence for or against, a hypothesis. ... Evidence is partly determined by how you obtained the observation."
- CANDIDATE-FORMAL-BIRTH: [S0451] §"SOURCE PROVENANCE (who/when/where/version/source) ... EPISTEMIC PROVENANCE (why selected, how generated, under what conditions, what selection procedure, what alternatives excluded)."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0451] §"SELECTION AUDIT: 1. What is the target population? ... 8. Is the procedure appropriate to the hypothesis?"

## Lifecycle
last_seen: S0452. Candidate lifecycle: DORMANT. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S0451 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S0451 |
| Dependencies | PRESENT | S0451, S0452 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S0451, S0452 |
| Examples | PRESENT | S0451 |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0451] types=[DEFINITION, CONCEPT] scope=OBJECT — "Introduces Selection Procedure as the most important extracted concept: evidence status is partly determined by how the observation was obtained, so KnowledgeOS must preserve not just what was observed but how it was selected, measured, under what conditions, why, from what population, what was excluded, and what alternatives were considered -- described as much deeper than ordinary data provenance." (anchor: "Selection Procedure ... a rule determining how to test, or obtain evidence for or against, a hypothesis. ... Evidence is partly determined by how you obtained the observation.")
- [S0451] types=[EXAMPLE, INVARIANT] scope=OBJECT — "Raven example demonstrating that a biased selection procedure guarantees its result and thereby produces no genuine evidence, motivating the invariant: 'No observation intended to support a knowledge claim shall be treated as evidence without preserving the procedure by which the observation was selected or generated, whenever such a procedure exists.'" (anchor: "Select ravens only from cages containing black birds. Result: All observed ravens are black. ... such a strongly biased selection procedure does not generate genuine evidence for the hypothesis.")
- [S0451] types=[FORMALIZATION, DISTINCTION] scope=OBJECT — "Evidence provenance is two-dimensional: Source Provenance (who/when/where/version) versus Acquisition/Epistemic Provenance (why selected, how generated, conditions, selection procedure, excluded alternatives), called a major architectural insight; KnowledgeOS SHALL treat selection procedure as epistemic provenance, not merely operational metadata." (anchor: "SOURCE PROVENANCE (who/when/where/version/source) ... EPISTEMIC PROVENANCE (why selected, how generated, under what conditions, what selection procedure, what alternatives excluded).")
- [S0451] types=[GOVERNANCE, FORMALIZATION] scope=OBJECT — "Proposes a concrete eight-question Selection Audit capability to run before accepting a collection as evidence, on the basis that different selection procedures can turn essentially the same observation into strong, weak, or non-evidence." (anchor: "SELECTION AUDIT: 1. What is the target population? ... 8. Is the procedure appropriate to the hypothesis?")
- [S0452] types=[CONCEPT, DISTINCTION] scope=OBJECT — "Explicitly non-historical use of the Chinese concept 'li' as a lens for SelectionProcedure/ObservationProtocol/MeasurementProtocol/EvidenceProtocol -- asking whether an observation was acquired according to a pattern appropriate to the question, echoing Achinstein's Hertz example that the observation itself was not enough without an epistemically relevant procedure." (anchor: "li can be used as a conceptual lens for: the appropriate pattern, form, procedure or ordering by which something is done. ... Was the observation acquired according to an appropriate pattern for the question being investigated?")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
