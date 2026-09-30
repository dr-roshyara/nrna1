# model-validity-state

**Scope(s):** OBJECT · **Row count:** 6 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `ModelValidity`, `conditional validity`, `hyperprior` · **Aliases:** `model uncertainty vs parameter uncertainty`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0012`, scope `OBJECT`: Distinguishes Level 1 parameter uncertainty ('what is the probability?') from Level 2 model uncertainty ('are we using the right model at all?', hyperpriors), proposing a ModelValidity state (active/degrading/contradicted/superseded/context-bound/unknown) so KnowledgeOS can detect 'we may not have a bad implementation, we may have an outdated model', and represent conditional validity rather than universal truth.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0453 §"hyperpriors ... uncertainty about what world/model you are actually in ... repeated evidence may indicate that the environment itself has changed. ... KnowledgeOS should be able to represent: Model A: 65% / Model B: 25% / Model C: 10%."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0453 §"hyperpriors ... uncertainty about what world/model you are actually in ... repeated evidence may indicate that the environment itself has changed. ... KnowledgeOS should be able to represent: Model A: 65% / Model B: 25% / Model C: 10%."]
- CANDIDATE-FORMAL-BIRTH: [S0453 §"repeated observations can indicate that the underlying world has changed, requiring a different model rather than merely adjusting the old one. ... Model change must be detectable. ... ModelValidity: active, degrading, contradicted, superseded, context-bound, unknown."]
- CANDIDATE-OPERATIONAL-BIRTH: [S0982 §"82.37 — Experiment 18 ... Model M_1: P(A)=0.9. Model M_2: P(A)=0.2. Both plausible. Expected: OverallUncertainty must reflect model uncertainty. Result: PASS"]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0982. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0453, S0982 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0453, S0453 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0453, S0982 |
| examples | PRESENT | S0453 |
| warnings | PRESENT | S0982 |
| experiments | PRESENT | S0982, S0982 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0453] types=['CONCEPT', 'DISTINCTION'] scope=OBJECT — "Two levels of uncertainty distinguished: parameter uncertainty ('what is the probability?') versus model uncertainty ('are we using the right model at all?', hyperpriors); KnowledgeOS should represent a distribution over candidate models rather than silently committing to one, since AI systems typically hide the assumption 'my model of the problem is correct.'" (anchor: "hyperpriors ... uncertainty about what world/model you are actually in ... repeated evidence may indicate that the environment itself has changed. ... KnowledgeOS should be able to represent: Model A: 65% / Model B: 25% / Model C: 10%.")
- [S0453] types=['FORMALIZATION', 'EXAMPLE'] scope=OBJECT — "ModelValidity state proposed, illustrated by an architecture decision (synchronous integration, valid under <50 req/s single-region) whose prediction errors increase after 8x traffic growth, concluding 'we may not have a bad implementation, we may have an outdated model' -- generalized into representing conditional validity rather than universal truth." (anchor: "repeated observations can indicate that the underlying world has changed, requiring a different model rather than merely adjusting the old one. ... Model change must be detectable. ... ModelValidity: active, degrading, contradicted, superseded, context-bound, unknown.")
- [S0982] types=['DEFINITION', 'FORMALIZATION'] scope=OBJECT — "Defines model uncertainty as uncertainty over which model (M_1,M_2,M_3,...) is correct, formalized via model averaging P(D)=Σ_m P(D|M_m)P(M_m), distinct from parameter uncertainty." (anchor: "82.36 — Model uncertainty ... uncertain not only about parameters θ, but about the model itself M_1,M_2,M_3. P(D)=Σ_m P(D|M_m)P(M_m). This is model uncertainty.")
- [S0982] types=['EXPERIMENT', 'EXPERIMENTAL-RESULT'] scope=OBJECT — "Experiment 18: two plausible models giving wildly different P(A) (0.9 vs 0.2) means OverallUncertainty must reflect this model-level disagreement; result PASS." (anchor: "82.37 — Experiment 18 ... Model M_1: P(A)=0.9. Model M_2: P(A)=0.2. Both plausible. Expected: OverallUncertainty must reflect model uncertainty. Result: PASS")
- [S0982] types=['DISTINCTION', 'WARNING'] scope=OBJECT — "Warns that conflating ParameterUncertainty with ModelUncertainty can cause the system to substantially underestimate total uncertainty." (anchor: "82.38 — Parameter uncertainty versus model uncertainty ... ParameterUncertainty from ModelUncertainty. Otherwise the system can underestimate uncertainty substantially.")
- [S0982] types=['EXPERIMENT', 'EXPERIMENTAL-RESULT'] scope=OBJECT — "Experiment 19: a reported Risk=2% that accounts only for parameter uncertainty and ignores model-structure uncertainty is an IncompleteRiskEstimate; result PASS." (anchor: "82.39 — Experiment 19 ... System reports Risk=2%. But this includes parameter uncertainty and ignores uncertainty over model structure. Expected: IncompleteRiskEstimate. Result: PASS")

## Notes for P3
(none beyond what is noted above)
