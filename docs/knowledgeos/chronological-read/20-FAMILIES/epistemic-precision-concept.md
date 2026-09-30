# epistemic-precision-concept

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** EpistemicPrecision, NumericalPrecision · **Aliases:** false precision warning
**Candidate group membership (NOT an identity claim):**
- G0944: links `epistemic-precision-concept` with `epistemic-lineage-concept` — working_label token overlap Jaccard=0.50 (shared tokens: ['concept', 'epistemic'])

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0012, scope OBJECT): Warns that a probability reported to many decimal places (e.g. P(H)=0.913742) can be false precision if evidence is incomplete, the hypothesis space is incomplete, the model is mis-specified, the prior is arbitrary, the environment changed, observations are correlated, or the model is non-identifiable; proposes tracking NumericalPrecision separately from EpistemicPrecision = f(EvidenceQuality, Coverage, ModelValidity, Calibration, Identifiability, Applicability), and separating math error/model error/evidence error/semantic error components of total error.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0465] §"P(H)=0.913742 ... Then six decimals are false precision. ... EP = f(EvidenceQuality, Coverage, ModelValidity, Calibration, Identifiability, Applicability). Then: Probability = 0.914, Epistemic precision = LOW is entirely possible."
- CANDIDATE-CONCEPTUAL-BIRTH: [S0465] §"Probability distributions describe uncertainty inside the model. They do not automatically capture: unknown model class, unknown variable, unknown causal factor, unknown regime change. ... it should be represented as a risk category, not fake probability."
- CANDIDATE-FORMAL-BIRTH: [S0465] §"P(H)=0.913742 ... Then six decimals are false precision. ... EP = f(EvidenceQuality, Coverage, ModelValidity, Calibration, Identifiability, Applicability). Then: Probability = 0.914, Epistemic precision = LOW is entirely possible."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0465. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. The DORMANT label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0465 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0465 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0465 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S0465]` types=[WARNING/FORMALIZATION] scope=OBJECT — "'Probably the most important warning from the entire exercise': a high-decimal-precision probability can be false precision when evidence/hypothesis-space/model/prior/environment/identifiability are deficient; proposes EpistemicPrecision as a function of those factors, tracked separately from NumericalPrecision, and decomposes total error into mathematical/model/evidence/semantic components that can diverge independently (near-zero math error with large semantic error)." (anchor: "P(H)=0.913742 ... Then six decimals are false precision. ... EP = f(EvidenceQuality, Coverage, ModelValidity, Calibration, Identifiability, Applicability). Then: Probability = 0.914, Epistemic precision = LOW is entirely possible.")
- `[S0465]` types=[WARNING/CONCEPT] scope=OBJECT — "Wisdom-Lens 'unknown unknowns' requirement: ModelUncertainty/ParameterUncertainty/ObservationUncertainty/StructuralUncertainty/UnknownUnknownIndicator must be tracked as risk categories, since probability distributions describe only in-model uncertainty and cannot capture unknown model classes/variables/causal factors/regime changes; paired with regime-change/concept-drift detection techniques (change-point detection, CUSUM, Page-Hinkley, Bayesian online change detection, HMMs) for long-lived models." (anchor: "Probability distributions describe uncertainty inside the model. They do not automatically capture: unknown model class, unknown variable, unknown causal factor, unknown regime change. ... it should be represented as a risk category, not fake probability.")

## Notes for P3
- Thin evidentiary base (2 row(s) captured) — classification here should be treated as provisional pending further corpus passes.
