# step178-decision-legitimacy-and-hindsight-leakage

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `DecisionCorrectness != DecisionLegitimacy`; `DecisionQuality(t) evaluated relative to information/policy state at t`; `HindsightLeakage = Use(K_{t+1},Decision_t) when K_{t+1} not in InformationSet_t`
**Aliases:** "hindsight leakage formalized"; "legitimacy vs correctness"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0033, scope THEORY-LEVEL: "Step 178 distinguishes DecisionCorrectness from DecisionLegitimacy: a decision that later proves to be a bad outcome (Decision=Proceed, later Outcome=Failure) is not automatically DecisionWasIllegitimate. Draws a physician-treatment-choice analogy to state the principle DecisionQuality(t) must be evaluated relative to the appropriate information and policy state at t, not later information. Formally defines HindsightLeakage = Use(K_{t+1},Decision_t) whenever K_{t+1} is not in InformationSet_t, and requires KnowledgeOS preserve enough temporal information to prevent accidental hindsight reasoning against a past decision -- one of the series' sharpest named formalizations of the ex-ante/ex-post principle."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1371 §"DecisionCorrectness from: DecisionLegitimacy. ... Decision=Proceed. Later: Outcome=Failure. That does not necessarily mean: DecisionWasIllegitimate. ... DecisionQuality(t) must be evaluated relative to the appropriate information and policy state at t. ... HindsightLeakage= Use(K_{t+1},Decision_t) when: K_{t+1} ∉ InformationSet_t."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1371 §"DecisionCorrectness from: DecisionLegitimacy. ... Decision=Proceed. Later: Outcome=Failure. That does not necessarily mean: DecisionWasIllegitimate. ... DecisionQuality(t) must be evaluated relative to the appropriate information and policy state at t. ... HindsightLeakage= Use(K_{t+1},Decision_t) when: K_{t+1} ∉ InformationSet_t."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1371. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S1371), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1371 |
| type_signature | PRESENT | S1371 |
| invariants | PRESENT | S1371 |
| dependencies | PRESENT | S1371 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1371 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — no row in this label's family is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1371] types=[DISTINCTION, FORMALIZATION] scope=THEORY-LEVEL — "Distinguishes DecisionCorrectness from DecisionLegitimacy: a decision followed by a bad outcome does not automatically mean the decision was illegitimate. Via a physician-treatment analogy states DecisionQuality(t) must be evaluated relative to the information and policy state available at t, formally defining HindsightLeakage = Use(K_{t+1},Decision_t) whenever K_{t+1} is not in InformationSet_t -- requiring KnowledgeOS preserve enough temporal information to prevent accidental hindsight reasoning against a past decision." (anchor: "DecisionCorrectness from: DecisionLegitimacy. ... Decision=Proceed. Later: Outcome=Failure. That does not necessarily mean: DecisionWasIllegitimate. ... DecisionQuality(t) must be evaluated relative to the appropriate information and policy state at t. ... HindsightLeakage= Use(K_{t+1},Decision_t) when: K_{t+1} ∉ InformationSet_t.")

## Notes for P3

- This is my own observation: singleton label (1 row) — evidence base is thin by construction; no internal corroboration is possible from this label alone.
