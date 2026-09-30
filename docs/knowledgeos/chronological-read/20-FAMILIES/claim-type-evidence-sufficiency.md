# claim-type-evidence-sufficiency

**Scope(s):** THEORY-LEVEL · **Row count:** 6 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Assessment=f(Evidence,ClaimType,Model,Assumptions)` · **Aliases:** `ClaimType determines EvidenceSufficiency`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0034`, scope `THEORY-LEVEL`: Step 194's principle that evidential sufficiency depends on the semantic type of the claim being made (Precedes vs Correlates vs Causes require increasingly strong evidence); includes the candidate nine-member relation taxonomy R and invariant I_46.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1397 §"R=\{Precedes,Follows,Correlates,Supports,Explains,Causes,Enables,Prevents,Contradicts\}. But these relations have very different evidential requirements."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1397 §"R=\{Precedes,Follows,Correlates,Supports,Explains,Causes,Enables,Prevents,Contradicts\}. But these relations have very different evidential requirements."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1397. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1397, S1397, S1397 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1397, S1397, S1397 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1397, S1397 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1397] types=['DEFINITION', 'FORMALIZATION'] scope=THEORY-LEVEL — "Defines a nine-member candidate relation taxonomy (Precedes, Follows, Correlates, Supports, Explains, Causes, Enables, Prevents, Contradicts), explicitly noting each carries very different evidential requirements -- a critical extension of the corpus's earlier relation-algebra proposals." (anchor: "R=\{Precedes,Follows,Correlates,Supports,Explains,Causes,Enables,Prevents,Contradicts\}. But these relations have very different evidential requirements.")
- [S1397] types=['PRINCIPLE', 'INVARIANT'] scope=THEORY-LEVEL — "A causal claim requires substantially stronger justification (controlled intervention, natural experiment, causal model, domain mechanism, quasi-experimental evidence, or strong assumptions) than a mere sequence claim, which a timestamp can satisfy." (anchor: "EvidenceRequirement(Cause) > EvidenceRequirement(Sequence) in general.")
- [S1397] types=['EXTENSION', 'FORMALIZATION'] scope=THEORY-LEVEL — "Refines Step 192's Evidence=>Assessment into Assessment=f(Evidence,ClaimType,Model,Assumptions): the same evidence (e.g. deployment.log + restart.log) may establish a Precedes claim but not a Causes claim -- ClaimType determines EvidenceSufficiency, proposed as part of the formal architecture." (anchor: "Assessment = f(Evidence,ClaimType,Model,Assumptions). ... ClaimType determines EvidenceSufficiency.")
- [S1397] types=['FORMALIZATION', 'INVARIANT'] scope=OBJECT — "Defines a causal-effect estimate C_{X->Y}=P(Y|do(X))-P(Y|do(not X)); this number is conditional on the causal model and assumptions, not metaphysical truth -- extends Data->StatisticalModel->Estimate->Interpretation, requiring the knowledge object to retain Model, Assumptions, Population, Sample, Method alongside any point estimate." (anchor: "C_{X\rightarrow Y} = P(Y\mid do(X))-P(Y\mid do(\neg X)). ... the number is conditional on the causal model and assumptions. It is not metaphysical truth.")
- [S1397] types=['DISTINCTION', 'VALIDATION'] scope=OBJECT — "An AI-generated causal claim must initially be a CandidateCausalClaim, not an EstablishedCausalClaim; AIInference != EstablishedKnowledge until validation conditions are satisfied -- follows directly from the earlier epistemic architecture." (anchor: "CandidateCausalClaim. Not: EstablishedCausalClaim. ... AIInference\neqEstablishedKnowledge.")
- [S1397] types=['INVARIANT'] scope=THEORY-LEVEL — "New invariant I_46: evidential requirements for a claim must depend on the claim's semantic type (Precedes/Correlates/Causes etc.), not be uniform." (anchor: "I_46: The evidential requirements for a claim must depend on the semantic type of the claim.")

## Notes for P3
- No unusual tensions or evidentiary anomalies were observed for this label within the captured rows.
