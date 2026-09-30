# knowledge-stability-property

**Scope(s):** OBJECT · **Row count:** 3 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `KnowledgeStability`; `stability analysis`
**Aliases:** "reproducibility under perturbation"
**Candidate group membership (NOT an identity claim):**
- G1391: co-occurs with `knowledgeos-statistical-technique-catalog` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0012, scope OBJECT: "Proposed core epistemic property measuring whether an inference/conclusion remains the same when evidence subset, retrieved documents, temporal window, model, or reasoning path are perturbed; rated among the highest-value extractions (4.6/5) and proposed as an architectural invariant that a result changing under small perturbations is epistemically weaker than a stable one."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0448 §"Does this reasoning continue to pass when the evidence set is perturbed? ... RobustnessByResampling"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0448 §"Does this reasoning continue to pass when the evidence set is perturbed? ... RobustnessByResampling"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S0448. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S0448), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0448 (×2) |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0448 |
| dependencies | PRESENT | S0448 |
| assumptions | PRESENT | S0448 |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S0448 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — no row in this label's family is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE.

## Assumption register

| Statement | Stated | source_id | anchor |
|---|---|---|---|
| Reproducibility can be as important as accuracy when humans must interpret the result. | EXPLICIT | S0448 | "section 9, attributed to the book" |

## All rows (source_id order)

- [S0448] types=[FORMALIZATION, EXTENSION] scope=OBJECT — also labeled `knowledgeos-statistical-technique-catalog` — "KOS-06 Cross-Validation/Resampling generalized into RobustnessByResampling: perturb the evidence set into subsets (E1..E4), rerun inference on each, and measure Stability, rather than asking merely whether reasoning passed once." (anchor: "Does this reasoning continue to pass when the evidence set is perturbed? ... RobustnessByResampling")
- [S0448] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "KOS-08 Stability Analysis (called 'a major discovery for KnowledgeOS'): KnowledgeStability measured by perturbing retrieved documents/evidence subset/temporal window/model/reasoning path and computing agreement (example: stability=0.80 for a mostly-repeated conclusion vs a low-stability sequence A/B/C/A/D); a KnowledgeAssessment object (correctness, evidence_strength, reliability, stability, freshness, coverage, contradiction_level) is proposed to replace a single collapsed confidence number." (anchor: "different samples can produce different feature sets despite similar predictive accuracy ... reproducibility can be as important as accuracy when humans must interpret the result.")
- [S0448] types=[INVARIANT] scope=THEORY-LEVEL — also labeled `knowledgeos-statistical-technique-catalog` — "Three named constitutional invariants combining this book with the prior Freedman analysis: A) a KnowledgeOS inference may abstain when evidence/reliability/risk thresholds are not satisfied; B) correct predictions do not establish calibrated or trustworthy probabilities; C) a result that changes substantially under small perturbations of the evidence is epistemically weaker than a stable result." (anchor: "Invariant A - Never force classification ... Invariant B - Never equate performance with reliability ... Invariant C - Never treat stability as optional")

## Notes for P3

- This is my own observation: nothing unusual noticed beyond what is already recorded above; evidence base is internally consistent for what it covers.
