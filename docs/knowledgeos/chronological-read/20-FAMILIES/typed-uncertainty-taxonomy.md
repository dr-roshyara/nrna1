# typed-uncertainty-taxonomy

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Uncertainty_measurement, _model, _identity, _causal, _temporal, _semantic, _governance` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0959**: [`typed-uncertainty-taxonomy` · `uncertainty-taxonomy-typed-propagation`] — working_label token overlap Jaccard=0.75 (shared tokens: ['taxonomy', 'typed', 'uncertainty'])
- **G1024**: [`typed-uncertainty-taxonomy` · `uncertainty-typed-object-u-h`] — working_label token overlap Jaccard=0.50 (shared tokens: ['typed', 'uncertainty'])

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0034`, scope `OBJECT`: Step 199's seven-member typed uncertainty taxonomy plus the DDD argument that 'uncertainty' should not become one universal domain object but a shared conceptual kernel expressed differently per bounded context.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1417 §"RootCauseUncertainty. ... ArchitectureRisk. ... DecisionUncertainty. ... PredictionUncertainty. ... UncertaintyConcept but avoid forcing all contexts into one enormous aggregate."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1429. Candidate lifecycle: **CONTESTED**. Evidence: contested_by_own_contradiction_type: true — the corpus itself argues both sides; representative contradiction row(s): [S1429] "K2: at least five mutually distinct, never-reconciled uncertainty taxonomies exist across the raw statistical track, including two different taxonomies within the same file (step-027) and the seven-member taxonomy this batch itself extracted at Step 199 (S1417) as merely one of the five, not a settled resolution."

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1417 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1417, S1429 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1417] types=['DISTINCTION', 'EXTENSION'] scope=METHODOLOGICAL — "DDD implication: 'uncertainty' should not become one universal domain object; different bounded contexts (Incident Management's RootCauseUncertainty, Architecture's ArchitectureRisk, Governance's DecisionUncertainty, ML's PredictionUncertainty) share a mathematical foundation but retain distinct domain semantics, resolved via a shared conceptual kernel rather than one enormous aggregate." (anchor: "RootCauseUncertainty. ... ArchitectureRisk. ... DecisionUncertainty. ... PredictionUncertainty. ... UncertaintyConcept but avoid forcing all contexts into one enormous aggregate.")
- [S1417] types=['DEFINITION', 'EXTENSION'] scope=OBJECT — "Uncertainty is sometimes structural rather than numerical (e.g. IdentityAmbiguity, where two incomplete records might refer to the same person, not well captured by a scalar probability); proposes a seven-member typed uncertainty taxonomy (measurement, model, identity, causal, temporal, semantic, governance), each belonging naturally to a different domain analysis and never to be conflated." (anchor: "IdentityAmbiguity. No single scalar probability is necessarily the right representation. ... Uncertainty_measurement ... Uncertainty_model ... Uncertainty_identity ... Uncertainty_causal ... Uncertainty_temporal ... Uncertainty_semantic ... Uncertainty_governance.")
- [S1429] types=['RESTATEMENT'] scope=THEORY-LEVEL — "Confirms the raw statistical track's repeated central thesis (across step-027 and Step 199) against collapsing Truth/Evidence/Probability/Belief/Confidence/Uncertainty into one scalar, naming a UniversalConfidenceScore explicitly an architectural anti-pattern." (anchor: "do not collapse Truth / Evidence / Probability / Belief / Confidence / Uncertainty into one scalar (step-027 §27.1, §27.14 UniversalConfidenceScore = ArchitecturalAntiPattern; Step 199 §199.19).")
- [S1429] types=['CONTRADICTION'] scope=OBJECT — "K2: at least five mutually distinct, never-reconciled uncertainty taxonomies exist across the raw statistical track, including two different taxonomies within the same file (step-027) and the seven-member taxonomy this batch itself extracted at Step 199 (S1417) as merely one of the five, not a settled resolution." (anchor: "K2 Five different uncertainty taxonomies (027 §27.13 eight-way; 027 §27.52 five-way; 199 §199.53 seven-way incl. governance; 082 epistemic/aleatory; SNF three-way) — none reconciled.")

## Notes for P3
- This label participates in 2 candidate group(s) (G0959, G1024) — per R5/R12 this is not an identity claim; P3 should review whether any group member denotes the same underlying object as this label.
- Lifecycle is flagged CONTESTED by the mechanical heuristic (the label's own captured rows include a row typed CONTRADICTION); worth prioritizing in reconciliation since it signals the corpus arguing both sides of something tied to this label.
- Despite 4 captured rows, only 2/12 completeness dimensions are PRESENT (formal_definition, semantics) — the evidentiary base is narrow in kind even where it is not narrow in volume.
