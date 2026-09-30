# unknown-ambiguous-conflicted-taxonomy

**Scope(s):** THEORY-LEVEL · **Row count:** 10 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `E_5={Supported,Refuted,Unknown,Ambiguous,Conflicted}` · **Aliases:** `five-valued epistemic logic`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 199's five-state epistemic taxonomy replacing binary truth, separating Unknown (information insufficient) from Ambiguous (multiple surviving hypotheses) from Conflicted (evidence actively disagrees), plus the Probability!=Truth and Confidence!=Probability invariants and the richer per-assessment tuple this taxonomy motivates."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1417 §"Unknown\neq False. This is not merely philosophical. It is a critical software invariant."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1417 §"\mathbb{E}_3=\{T,F,U\} ... But it is not enough."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1417. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no own-row contradiction trigger fired; the DORMANT classification is a heuristic based on how recently (by source_id order) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1417 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1417 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1417 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1417 |
| examples | PRESENT | S1417 |
| warnings | PRESENT | S1417 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Argues one scalar confidence value is epistemically insufficient because strong-evidence-with-competing-explanations, near-no-evidence, conflicting-evidence, and a highly-uncertain probabilistic model can all produce the same number (e.g. 0.7); proposes a richer assessment tuple A=(Status,EvidenceSet,Model,Uncertainty,Assumptions,Scope,Time) instead. [S1417]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1417] types=[INVARIANT, WARNING] scope=THEORY-LEVEL — "Establishes False/True/Unknown as three fundamentally different epistemic states for a proposition, warning against a naive Boolean model that forces 'no evidence exists' into isCause=false when the mathematically correct state is Unknown." (anchor: "Unknown\neq False. This is not merely philosophical. It is a critical software invariant.")
- [S1417] types=[FORMALIZATION, EXTENSION] scope=OBJECT — "Proposes a three-valued epistemic logic E_3={Supported,Refuted,Unknown} as a first, safer model, but flags it as still insufficient, leading to further distinctions in this step." (anchor: "\mathbb{E}_3=\{T,F,U\} ... But it is not enough.")
- [S1417] types=[DEFINITION, DISTINCTION] scope=OBJECT — "Distinguishes Unknown (InformationInsufficient, the proposition cannot currently be determined) from Ambiguous (MultipleCompatibleInterpretations, e.g. two configurations both compatible with the evidence); formalizes ambiguity as underdetermination -- multiple hypotheses surviving the same evidence, |H_compatible(E)|>1." (anchor: "Unknown\neq Ambiguous. ... Ambiguity = Multiple surviving hypotheses.")
- [S1417] types=[DEFINITION, DISTINCTION] scope=OBJECT — "Distinguishes Conflict (E1 supports H, E2 supports not-H, evidence actively disagrees) from Unknown (no determination possible); together with Supported, Refuted, and Ambiguous, yields a five-state epistemic set E_5={Supported,Refuted,Unknown,Ambiguous,Conflicted}, each requiring a precise semantic definition rather than an arbitrary enum." (anchor: "Conflict\neq Unknown. We know something—but the available evidence disagrees.")
- [S1417] types=[INVARIANT] scope=OBJECT — "A posterior probability P(H|E) expresses relative model-and-evidence-conditional plausibility among hypotheses, never that the more probable hypothesis is simply True." (anchor: "Probability\neq Truth. ... P(H_1|E)=0.8 ... does not mean: H_1=True.")
- [S1417] types=[INVARIANT, WARNING] scope=OBJECT — "A reported 'confidence' number can mean many different things (model certainty, classification margin, calibration estimate, human confidence, evidence quality, decision confidence) and must never be treated as a probability unless formally defined as one." (anchor: "Confidence\neq Probability unless formally defined as such. ... Confidence can refer to many things: model certainty; classification margin; calibration estimate; human confidence; evidence quality; d…")
- [S1417] types=[FORMALIZATION, ARGUMENT] scope=OBJECT — "Argues one scalar confidence value is epistemically insufficient because strong-evidence-with-competing-explanations, near-no-evidence, conflicting-evidence, and a highly-uncertain probabilistic model can all produce the same number (e.g. 0.7); proposes a richer assessment tuple A=(Status,EvidenceSet,Model,Uncertainty,Assumptions,Scope,Time) instead." (anchor: "A = (Status,EvidenceSet,Model,Uncertainty,Assumptions,Scope,Time). Where: Status\in\mathbb{E}.")
- [S1417] types=[INVARIANT, EXAMPLE] scope=OBJECT — "Probability is model-relative, not evidence-alone-derived: P(H|E,M1) can legitimately differ from P(H|E,M2); two analysts using different justified priors/models can reasonably produce different posteriors, and KnowledgeOS must preserve the model/assumptions rather than present the number as absolute truth." (anchor: "P(H\mid E,M_1) \neq P(H\mid E,M_2) in general. ... Probability must be interpreted relative to its declared model.")
- [S1417] types=[EXAMPLE, EXTENSION] scope=OBJECT — "For repeated-process (frequentist) estimates, a point estimate p-hat should be represented together with its uncertainty (e.g. a confidence interval), which is more informative than a bare percentage; the estimate must retain Estimate->Method->Dataset->Assumptions->Uncertainty provenance or become detached from its conditions." (anchor: "\hat p=0.12, 95%\,CI=[0.06,0.20]. This is more informative than: failureProbability = 12%")
- [S1417] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_65: a quantitative estimate must retain sufficient provenance to reconstruct the data, method, assumptions, and uncertainty under which it was produced." (anchor: "I_{65}: A quantitative estimate must retain sufficient provenance to reconstruct the data, method, assumptions, and uncertainty under which it was produced.")

## Notes for P3
- No additional tension, oddity, or priority flag observed while compiling this file beyond what is already recorded above.
