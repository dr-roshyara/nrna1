# decision-experiment-object

**Scope(s):** OBJECT · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `DecisionExperiment`, `LearningExperiment` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G1465** [`decision-experiment-object` · `kos-decision-theory-model`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0012`, scope `OBJECT`: Proposed domain object (Question, Hypothesis, Intervention, Comparator, Population, SuccessCriteria, ObservationPlan, RiskConstraints, TimeHorizon, ExpectedEvidence, DecisionRule) transforming the target-trial pattern into something KnowledgeOS can govern: how the organization learns whether something works, not merely what it believes.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0450 §"the target-trial framework provides a common language for specifying interventions, comparators, eligibility and time zero. DDD + Wisdom could transform that into: Decision Experiment ... KnowledgeOS isn't just recording what the organization believes. It can govern how the organization learns whether something works."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0450 §"the target-trial framework provides a common language for specifying interventions, comparators, eligibility and time zero. DDD + Wisdom could transform that into: Decision Experiment ... KnowledgeOS isn't just recording what the organization believes. It can govern how the organization learns whether something works."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0971. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0450, S0938, S0958, S0971 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0450, S0958 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S0971 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0450] types=['FORMALIZATION', 'EXTENSION'] scope=OBJECT — "Wisdom Lens #6 Experiment before belief transforms the target-trial pattern into a Decision Experiment/LearningExperiment (Question -> Hypothesis -> Target intervention -> Expected outcome -> Risk -> Small reversible experiment -> Observation -> Causal assessment -> Decision) fielded as Question/Hypothesis/Intervention/Comparator/Population/SuccessCriteria/ObservationPlan/RiskConstraints/TimeHorizon/ExpectedEvidence/DecisionRule, reframing KnowledgeOS as governing organizational learning rather than a 'causal analytics service.'" (anchor: "the target-trial framework provides a common language for specifying interventions, comparators, eligibility and time zero. DDD + Wisdom could transform that into: Decision Experiment ... KnowledgeOS isn't just recording what the organization believes. It can …")
- [S0938] types=['FORMALIZATION', 'EXTENSION'] scope=CROSS-OBJECT — "An intervention on A can distinguish competing models (e.g. M1: A->B vs M2: B->A) when observational data are insufficient. This reconnects experiments to Step 34: the best next experiment I* = argmax_I ExpectedInformationGain(I)/Cost(I), now applied to CausalModelUncertainty; defines causal Value of Information VOI(I) = ExpectedDecisionUtility(after I) - ExpectedDecisionUtility(now), and an intervention-planning pipeline: Competing Causal Models -> Candidate Interventions -> Expected Information Gain -> Decision Value -> Select Experiment." (anchor: "Intervention resolves model ambiguity; causal VOI reuses Step 34's information-gain-per-cost formula")
- [S0958] types=['FORMALIZATION', 'EXTENSION'] scope=OBJECT — "Defines Expected Value of Sample Information EVSI = EU_with-sample - EU_current, and asks whether EVSI > cost(E) before acquiring additional measurement E, making information acquisition itself a decision. Formalizes the resulting loop K_t -> D_info -> E_new -> K_{t+1} as 'not a problematic cycle... a controlled decision-learning loop' giving KnowledgeOS active-learning capability. Experiment 10 (EVSI=0, positive acquisition cost): rational policy may choose not to acquire the information -- PASS, reinforcing Step 61: information can have IG>0 but EVSI=0, so not all knowledge is decision-relevant. Experiment 11: modest IG but large EVSI (an information source changes the optimal action despite modest uncertainty reduction) -- PASS." (anchor: "EVSI makes information acquisition itself a governed decision; a controlled decision-learning loop; not all knowledge is decision-relevant")
- [S0971] types=['FORMALIZATION', 'EXPERIMENTAL-RESULT'] scope=OBJECT — "Restates EVPI = EU(decision with perfect information) - EU(decision now); if EVPI>CostOfInformation, acquiring more information may be worthwhile. Experiment 11: a 100-cost measurement with 2,000 expected decision improvement correctly yields CollectInformation -- PASS. Reformulates the pipeline as Knowledge -> InformationAcquisition -> UpdatedKnowledge -> Decision, 'an active learning loop'. Experiment 12: KnowledgeOS unable to confidently distinguish M1 from M2 identifying a discriminating measurement correctly yields InvestigationRecommendation -- PASS, letting the system ask 'what should we learn next' alongside 'what should we do now'." (anchor: "EVPI/active-learning loop reiterated; decision can produce InformationAcquisition rather than immediate action")

## Notes for P3
- No unusual tensions or evidentiary anomalies were observed for this label within the captured rows.
