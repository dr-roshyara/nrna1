# assumption-object

**Scope(s):** OBJECT · **Row count:** 11 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Assumption` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G1390**: [`assumption-ledger` · `assumption-object`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- **G1470**: [`assumption-object` · `causal-reasoning-assurance-layer`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0012, scope OBJECT: "Proposed first-class object attached to Model, with fields statement/type/justification/evidence/testability/status/consequence_if_false/scope and states SUPPORTED/PARTIALLY_SUPPORTED/UNSUPPORTED/UNTESTED/UNTESTABLE/CONTRADICTED."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0446 §"Freedman repeatedly attacks hidden assumptions. ... assumptions are frequently not stated or tested, and when assumptions fail, the mathematical guarantees of the method no longer apply."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0446 §"Observation, Evidence, Inference, Claim, Assumption, Model, ResearchQuestion, ResearchDesign, Mechanism, RivalExplanation, Confounder, Validation, IdentificationAssessment, Anomaly, Uncertainty ... I would not automatically create duplicates."]
- CANDIDATE-FORMAL-BIRTH: [S0446 §"Freedman repeatedly attacks hidden assumptions. ... assumptions are frequently not stated or tested, and when assumptions fail, the mathematical guarantees of the method no longer apply."]
- CANDIDATE-OPERATIONAL-BIRTH: [S0982 §"82.51 — Experiment 25 ... Causal estimate assumes NoUnmeasuredConfounding. But that assumption is unverified. Expected: AssumptionRisk must be exposed. Result: PASS"]
- CANDIDATE-GOVERNANCE-BIRTH: [S0446 §"Observation, Evidence, Inference, Claim, Assumption, Model, ResearchQuestion, ResearchDesign, Mechanism, RivalExplanation, Confounder, Validation, IdentificationAssessment, Anomaly, Uncertainty ... I would not automatically create duplicates."]

## Lifecycle
last_seen: S0983. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S0446, S0982 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0446, S0449, S0982, S0983 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0446 |
| dependencies | PRESENT | S0446, S0449 |
| assumptions | PRESENT | S0446 |
| semantics | PRESENT | S0982 |
| examples | PRESENT | S0446 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0982, S0983 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
AI must not hide assumptions: for any recommendation, the system should elicit why/what-assumptions/what-evidence-supports/what-evidence-contradicts/what-alternatives/what-would-make-it-wrong, restructuring AI output as Recommendation{Evidence, Assumptions, Reasoning, Alternatives, Contradictions, Scope, Failure Conditions} rather than a bare confidence score. [S0446] States that because a change in an Assumption can propagate through Model→Prediction→Decision, assumptions require explicit dependency lineage tracking. [S0982]

## Assumption register
| Statement | Stated | source_id | Anchor |
|---|---|---|---|
| Assumptions are frequently not stated or tested, and when assumptions fail, the mathematical guarantees of the method no longer apply. | EXPLICIT | S0446 | "section 5, attributed to the book" |

## All rows (source_id order)
- [S0446] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Proposes Assumption as a first-class object under Model (statement/type/justification/evidence/testability/status/consequence_if_false/scope) with states SUPPORTED/PARTIALLY_SUPPORTED/UNSUPPORTED/UNTESTED/UNTESTABLE/CONTRADICTED, illustrated with the example assumption 'The observations are independent' (status: unverified, testability: limited, consequence_if_false: confidence_intervals_invalid, inference_weakened); introduces an AssumptionLedger chaining Research Question -> Model -> per-assumption evidence/status." (anchor: "Freedman repeatedly attacks hidden assumptions. ... assumptions are frequently not stated or tested, and when assumptions fail, the mathematical guarantees of the method no longer apply.")
- [S0446] types=[EXTENSION, ARGUMENT] scope=CROSS-OBJECT — "AI must not hide assumptions: for any recommendation, the system should elicit why/what-assumptions/what-evidence-supports/what-evidence-contradicts/what-alternatives/what-would-make-it-wrong, restructuring AI output as Recommendation{Evidence, Assumptions, Reasoning, Alternatives, Contradictions, Scope, Failure Conditions} rather than a bare confidence score." (anchor: "the AI output becomes: Recommendation / Evidence / Assumptions / Reasoning / Alternatives / Contradictions / Scope / Failure Conditions. That is much more trustworthy than a generic 'confidence score.…")
- [S0446] types=[CONCEPT, GOVERNANCE] scope=THEORY-LEVEL — "Enumerates the most important new candidate domain objects to add to the KnowledgeOS conceptual model (Observation, Evidence, Inference, Claim, Assumption, Model, ResearchQuestion, ResearchDesign, Mechanism, RivalExplanation, Confounder, Validation, IdentificationAssessment, Anomaly, Uncertainty), with an explicit caution that some may already exist under different names and duplicates should not automatically be created." (anchor: "Observation, Evidence, Inference, Claim, Assumption, Model, ResearchQuestion, ResearchDesign, Mechanism, RivalExplanation, Confounder, Validation, IdentificationAssessment, Anomaly, Uncertainty ... I …")
- [S0446] types=[INVARIANT] scope=THEORY-LEVEL — "Invariant 3: A model does not validate its own assumptions." (anchor: "A model does not validate its own assumptions.")
- [S0449] types=[FORMALIZATION, CONCEPT] scope=OBJECT — "Assumption Lens: exchangeability, positivity, and consistency are identifying assumptions (not mathematical details but claims about validity conditions); Inference should store evidence/method/assumptions/assumption_provenance/assumption_status/conclusion, described as a 'huge architectural fit' with existing evidence/assurance/governance emphasis." (anchor: "data alone are insufficient; identifying assumptions must be supplied. ... Exchangeability / Positivity / Consistency ... claims about the conditions under which an inference is valid.")
- [S0982] types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 25: a causal estimate resting on an unverified NoUnmeasuredConfounding assumption must have that AssumptionRisk exposed; result PASS." (anchor: "82.51 — Experiment 25 ... Causal estimate assumes NoUnmeasuredConfounding. But that assumption is unverified. Expected: AssumptionRisk must be exposed. Result: PASS")
- [S0982] types=[DEFINITION, CONCEPT] scope=OBJECT — "Proposes Assumption(A) as a first-class object carrying a Status in {Supported, Unverified, Contradicted}." (anchor: "82.52 — Assumptions are first-class ... Assumption(A) with Status: Supported or Unverified or Contradicted.")
- [S0982] types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 26: a decision that depended on assumption A must be flagged for reconsideration when A becomes Contradicted; result PASS." (anchor: "82.53 — Experiment 26 ... Decision depends on assumption A. A becomes contradicted. Expected: DependentDecision must be reconsidered. Result: PASS")
- [S0982] types=[PRINCIPLE, ARGUMENT] scope=OBJECT — "States that because a change in an Assumption can propagate through Model→Prediction→Decision, assumptions require explicit dependency lineage tracking." (anchor: "82.54 — This is a major KnowledgeOS property ... A change in Assumption can propagate through Model→Prediction→Decision. Therefore assumptions need dependency lineage.")
- [S0983] types=[DEFINITION, EXTENSION] scope=OBJECT — "Names four standard causal-inference assumptions as first-class knowledge objects with evidence status: A1 NoUnmeasuredConfounding, A2 StableTreatmentDefinition (SUTVA-related), A3 NoInterference, A4 CorrectTemporalOrdering." (anchor: "84.31 — Causal assumptions become first-class knowledge ... A1: NoUnmeasuredConfounding. A2: StableTreatmentDefinition. A3: NoInterference. A4: CorrectTemporalOrdering. Each assumption has evidence st…")
- [S0983] types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 15: when evidence later contradicts an assumption (A1) a dependent counterfactual relied on, the counterfactual must undergo CounterfactualInvalidation; result PASS." (anchor: "84.32 — Experiment 15 ... Counterfactual depends on A1. Later evidence contradicts A1. Expected: CounterfactualInvalidation. Result: PASS")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
