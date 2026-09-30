# epistemic-entropy-volatility-metrics

**Scope(s):** OBJECT · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** H(Z_t), H(Z_t+1|Z_t), epistemic momentum M_E = d/dt P(DesiredState) · **Aliases:** epistemic volatility
**Candidate group membership (NOT an identity claim):**
- **G0208** [`epistemic-entropy-volatility-metrics` · `kos-information-gain-and-epistemic-quality`] — explicit agent-stated uncertainty: 'kos-information-gain-and-epistemic-quality' POSSIBLY relates to 'epistemic-entropy-volatility-metrics' (batch B0023). Note: Step 61's information-theoretic and epistemic-quality layer: worked entropy/information-gain calculations, InformationGain≠TruthGain, the Confidence/InformationGain/Accuracy trichotomy, evidence-quantity≠evidence-independence, the proposition-relative typed evidence-quality vector, calibration (Brier score/log loss), epistemic-quality-as-partial-order with Pareto incomparability, Epistemic-value≠Decision-value (EVPI), the anti-God-Model no-universal-score principle and Epistemic Quality Contract, and the three-transformation/epistemic-learning-cycle structure with non-circular calibration. Builds on but substantially extends epistemic-entropy-volatility-metrics (B0012), which introduced only the entropy/volatility/momentum measures.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0012, scope OBJECT): Proposes quantitative measures of state ambiguity (entropy H(Z_t) over current state probabilities), transition unpredictability (transition entropy H(Z_t+1|Z_t)), state-change frequency (epistemic volatility = #transitions/time), and directional trend (epistemic momentum, the time-derivative of P(desired state)) to distinguish, e.g., two systems both at P(Assured)=0.9 but trending in opposite directions -- explicitly Entropy != Truth.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0466 §"A system at P(Assured)=0.9 with M_E=-0.08/month is not equivalent to another system with P(Assured)=0.9 and M_E=+0.01/month. Static score misses the trajectory."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0466 §"A system at P(Assured)=0.9 with M_E=-0.08/month is not equivalent to another system with P(Assured)=0.9 and M_E=+0.01/month. Static score misses the trajectory."]
- CANDIDATE-FORMAL-BIRTH: [S0957 §"Binary entropy and a worked information-gain calculation; realized vs expected information gain"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0957. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0957 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0466 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S0466, S0957 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- **[S0466]** types=[EXAMPLE, CONCEPT] scope=OBJECT — "Epistemic momentum example: two systems with identical current probability but opposite trends are not epistemically equivalent, motivating tracking the derivative of state probability (momentum) alongside entropy (state ambiguity) and transition entropy (predictability of transitions) rather than a single static confidence score." (anchor: "A system at P(Assured)=0.9 with M_E=-0.08/month is not equivalent to another system with P(Assured)=0.9 and M_E=+0.01/month. Static score misses the trajectory.")
- **[S0957]** types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Worked example: for p='System A is healthy', P(p)=0.5 gives binary entropy H(p)=1 bit (maximal uncertainty); evidence E updates P(p|E)=0.8 giving H(p|E)~=0.722 bits, so InformationGain~=0.278 bits for this realization. Distinguishes this realized reduction from the formally expected quantity IG(E)=H(P)-E_E[H(P|E)], defined over possible evidence outcomes -- 'this distinction matters'." (anchor: "Binary entropy and a worked information-gain calculation; realized vs expected information gain")

## Notes for P3
- Own observation: only 2 row(s) touch this label — a thin evidentiary base; treat any generalization from it with caution.
- Own observation: completeness is thin — only formal_definition, dependencies, examples is PRESENT; most dimensions are NOT-EVIDENCED-IN-CAPTURE, consistent with a thin or narrowly-scoped source base rather than a claim that the object lacks these properties.
- Own observation: no rationale-bearing (EXPLANATION/ARGUMENT/ANALYSIS/ALTERNATIVE) row was found for this label — its purpose/motivation, if any, is carried only in DEFINITION/FORMALIZATION-typed rows.
