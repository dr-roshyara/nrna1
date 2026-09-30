# rival-explanation-object

**Scope(s):** OBJECT · **Row count:** 7 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** RivalExplanation · **Aliases:** confounder analysis
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0012, scope OBJECT): Proposed first-class object recording a hypothesis alongside confounders and alternative explanations, with fields hypothesis/supporting_evidence/contradicting_evidence/tests/status/unresolved_questions.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0446 §"causal reasoning as requiring multiple convergent lines of evidence, consideration of confounders, and exhaustive testing of alternative explanations."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0446 §"Observation, Evidence, Inference, Claim, Assumption, Model, ResearchQuestion, ResearchDesign, Mechanism, RivalExplanation, Confounder, Validation, IdentificationAssessment, Anomaly, Uncertainty ... I would not automatically create duplicates."]
- CANDIDATE-FORMAL-BIRTH: [S0446 §"causal reasoning as requiring multiple convergent lines of evidence, consideration of confounders, and exhaustive testing of alternative explanations."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0446 §"Observation, Evidence, Inference, Claim, Assumption, Model, ResearchQuestion, ResearchDesign, Mechanism, RivalExplanation, Confounder, Validation, IdentificationAssessment, Anomaly, Uncertainty ... I would not automatically create duplicates."]

## Lifecycle
last_seen: S0457. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by/superseded_by empty, contested flag false). The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the ledger, not a confirmed retirement and not a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0446, S0449, S0453, S0457 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0446 |
| dependencies | PRESENT | S0451, S0453 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0457 |
| examples | PRESENT | S0449, S0451, S0453 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0446] types=[FORMALIZATION] scope=OBJECT — "RivalExplanation proposed as a first-class object attached to a Hypothesis alongside Confounder A/B and alternative explanations, with fields hypothesis/supporting_evidence/contradicting_evidence/tests/status/unresolved_questions, replacing a bare Evidence->hypothesis link." (anchor: "causal reasoning as requiring multiple convergent lines of evidence, consideration of confounders, and exhaustive testing of alternative explanations.")
- [S0446] types=[CONCEPT, GOVERNANCE] scope=THEORY-LEVEL — "Enumerates the most important new candidate domain objects to add to the KnowledgeOS conceptual model (Observation, Evidence, Inference, Claim, Assumption, Model, ResearchQuestion, ResearchDesign, Mechanism, RivalExplanation, Confounder, Validation, IdentificationAssessment, Anomaly, Uncertainty), with an explicit caution that some may already exist under different names and duplicates should not automatically be created." (anchor: "Observation, Evidence, Inference, Claim, Assumption, Model, ResearchQuestion, ResearchDesign, Mechanism, RivalExplanation, Confounder, Validation, IdentificationAssessment, Anomaly, Uncertainty ... I would not automatically create duplicates.")
- [S0446] types=[INVARIANT] scope=THEORY-LEVEL — "Invariant 6: Unresolved alternatives reduce claim strength." (anchor: "Unresolved alternatives reduce claim strength.")
- [S0449] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Confounding Lens as a first-class anti-false-inference mechanism, illustrated by 'experienced teams' confounding an architecture-review-vs-quality relationship; a CAUSAL CLAIM REVIEW output lists observed association, potential confounders (team maturity, project size, engineering leadership), exchangeability status, and a resulting ABSTAIN conclusion." (anchor: "confounding as arising when treatment and outcome share common causes. ... CAUSAL CLAIM REVIEW ... Exchangeability: NOT ESTABLISHED ... Causal conclusion: ABSTAIN")
- [S0451] types=[CONCEPT, EXAMPLE] scope=OBJECT — "Achinstein's drug example motivates AlternativeExplanation as a first-class concept in evidence acquisition: design observations that discriminate between a Hypothesis and its Alternatives, rather than an AI agent merely searching for '10 documents supporting X.'" (anchor: "the procedure could check whether another drug produces the same effect and potentially blocks the effect attributed to the target drug. ... AlternativeExplanation as a first-class concept.")
- [S0453] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "HypothesisSet proposed (H1..H4 with prior/posterior per hypothesis), explicitly contrasted with a bad agent pattern (fixate on H1, search only for H1 evidence, confirm H1) and combined with the prior book's Evidence Acquisition/Alternative Explanation model." (anchor: "we should not evaluate one hypothesis in isolation ... Evidence should update all four. ... Evidence should update a hypothesis space, not just a claim.")
- [S0457] types=[FORMALIZATION, PRINCIPLE] scope=OBJECT — "Disagreement Is Evidence: mechanism disagreement (mechanisms, inputs, assumptions, outputs, confidence, disagreement dimensions) is recorded rather than resolved by automatic majority vote, and triggers investigation when material." (anchor: "If two mechanisms disagree: Reasoner A -> H1, Reasoner B -> H2. do not automatically majority-vote. Record: MechanismDisagreement ... Disagreement should trigger investigation when material.")

## Notes for P3
(Own observation) Nothing unusual noticed while drafting this file beyond what is already recorded above.
