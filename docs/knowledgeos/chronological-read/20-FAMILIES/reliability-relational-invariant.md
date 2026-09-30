# reliability-relational-invariant

**Scope(s):** `THEORY-LEVEL` · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `RELIABILITY IS RELATIONAL`, `ReliabilityAssessment(process, environment, conditions, population, time)` · **Aliases:** `stopped-clock Gettier case`
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0012, scope THEORY-LEVEL): Using Shieber's Gettier stopped-clock example, process reliability cannot be an intrinsic mechanism property (PROCESS.reliable=true) -- the same process (reading a clock) is reliable in one environment and unreliable in another (a stopped-clock environment); invariant TRUE_OUTCOME_BY_ACCIDENT != KNOWLEDGE, requiring Correct(result)+Traceable(basis)+Valid(process)+Reliable(process,environment) jointly.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0463] §"the person has a true belief, has evidence, bases the belief on the evidence, has no reason to suspect the evidence is defective, yet the belief is true by accident because the clock is broken. ... TRUE_OUTCOME_BY_ACCIDENT != KNOWLEDGE."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0464] §"A Bayesian computation can be mathematically correct while the model is epistemically inappropriate. ... MathematicalValidity and EpistemicValidity must remain separate. ... Mathematical / Model / Environmental Calibration -> Epistemic Assessment."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S0464`. Candidate lifecycle: **DORMANT**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **DORMANT** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0464 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0463, S0463, S0464 |
| dependencies | PRESENT | S0463, S0463, S0464 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0463, S0464 |
| examples | PRESENT | S0463, S0464 |
| warnings | PRESENT | S0464 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S0463]` types=[EXAMPLE, INVARIANT] scope=THEORY-LEVEL — "Gettier's stopped-clock case motivates TRUE_OUTCOME_BY_ACCIDENT != KNOWLEDGE, requiring Correct(result)+Traceable(basis)+Valid(process)+Reliable(process,environment) jointly, distinguished from bare Correct(result); called a major reinforcement of the existing deterministic-assurance philosophy." (anchor: "the person has a true belief, has evidence, bases the belief on the evidence, has no reason to suspect the evidence is defective, yet the belief is true by accident because the clock is broken. ......")
- `[S0463]` types=[PRINCIPLE] scope=THEORY-LEVEL — "Reliability must be modeled as a relation ReliabilityAssessment(process, environment, conditions, population, time) rather than a fixed mechanism property -- 'RELIABILITY IS RELATIONAL' belongs partly in the Kernel as a principle." (anchor: "the same mechanism can be reliable in one environment and unreliable in another. ... KnowledgeOS cannot model process reliability as an intrinsic property of a mechanism alone. ... RELIABILITY IS R...")
- `[S0464]` types=[EXAMPLE, WARNING] scope=OBJECT — "A prior-dominance AI failure mode is described: a strong prior ('most implementations use REST') can dominate over weak/noisy evidence of an unusual architecture (GraphQL), so evidence strength relative to the prior must be recorded, not just the evidence itself." (anchor: "the same PDF can represent speed, size, distance, etc. ... when evidence is noisy, the posterior remains closer to the prior; when evidence is reliable, it pulls the posterior more strongly. ... Kn...")
- `[S0464]` types=[DISTINCTION, FORMALIZATION] scope=OBJECT — "MathematicalValidity != EpistemicValidity: a correctly-computed P(H|E) can still be poor if the prior is badly calibrated or the likelihood model is inappropriate; a new assurance model splits Inference Result into Mathematical Validity, Model Validity, and Environmental Calibration before Epistemic Assessment." (anchor: "A Bayesian computation can be mathematically correct while the model is epistemically inappropriate. ... MathematicalValidity and EpistemicValidity must remain separate. ... Mathematical / Model / ...")

## Notes for P3
- No additional observations beyond what is captured above; nothing about this label's own rows struck this reviewer as unusual relative to its evidentiary base.
