# epistemic-lineage-object

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Epistemic Lineage, InferenceFrame, search-path provenance · **Aliases:** investigation record
**Candidate group membership (NOT an identity claim):**
- G0078: [`analytical-lineage-object` · `epistemic-lineage-object`] — explicit agent-stated uncertainty: 'epistemic-lineage-object' POSSIBLY relates to 'analytical-lineage-object' (batch B0012). Note: Extends provenance with search-path/analytical-path lineage (question, hypotheses, search paths, sources, evidence, transformations, analysis, alternatives, selection, conclusion, confidence) so that an AI agent's hidden path-selection process (which queries/documents/interpretations were tried and rejected) is preserved, not just the final answer; paired with InferenceFrame (target, conditioning variables, population, time window, assumptions, perspective, purpose) so a claim under one conditioning frame is not automatically equivalent to the same claim under another -- 'no context-free inference.'
- G0963: [`analytical-lineage-object` · `epistemic-lineage-object`] — working_label token overlap Jaccard=0.50 (shared tokens: ['lineage', 'object'])
- G0964: [`epistemic-lineage-concept` · `epistemic-lineage-object`] — working_label token overlap Jaccard=0.50 (shared tokens: ['epistemic', 'lineage'])

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0012, scope OBJECT): Extends provenance with search-path/analytical-path lineage (question, hypotheses, search paths, sources, evidence, transformations, analysis, alternatives, selection, conclusion, confidence) so that an AI agent's hidden path-selection process (which queries/documents/interpretations were tried and rejected) is preserved, not just the final answer; paired with InferenceFrame (target, conditioning variables, population, time window, assumptions, perspective, purpose) so a claim under one conditioning frame is not automatically equivalent to the same claim under another -- 'no context-free inference.' _[relation_to_existing: POSSIBLY:analytical-lineage-object]_

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0456] §"Regression as a 'principle of relativity for statistical analysis': asking the question from a different standpoint can produce a different answer ... KnowledgeOS must preserve the question frame. ... No context-free inference."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0456] §"Regression as a 'principle of relativity for statistical analysis': asking the question from a different standpoint can produce a different answer ... KnowledgeOS must preserve the question frame. ... No context-free inference."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0456] §"Deterministic verification should verify the epistemic process, not only the final artifact. ... AI-generated knowledge must preserve material alternative paths considered during investigation when those paths affect evidential interpretation or conclusion selection."

## Lifecycle
last_seen: S0457. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0456, S0457 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0456 |
| dependencies | PRESENT | S0456, S0457 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0456 |
| examples | PRESENT | S0456 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- [S0456] types=['FORMALIZATION', 'EXAMPLE'] scope=OBJECT — "InferenceFrame object (target, conditioning variables, population, time window, assumptions, perspective, purpose) formalizes that differently-conditioned questions ('why did the system fail?' vs '...given deployment succeeded?' vs '...given network was normal?' vs '...for this tenant but not others?') are not interchangeable, so a claim under Frame F1 is not automatically equivalent to the same claim under Frame F2 -- a major anti-hallucination principle against LLMs asserting 'X causes Y' when evidence only supports an association conditioned on specific variables." (anchor: "Regression as a 'principle of relativity for statistical analysis': asking the question from a different standpoint can produce a different answer ... KnowledgeOS must preserve the question frame. ... No context-free inference.")
- [S0456] types=['FORMALIZATION', 'INVARIANT'] scope=OBJECT — "Epistemic Lineage / search-path provenance (Question, Hypotheses, Search paths, Sources, Evidence, Transformations, Analysis, Alternatives, Selection, Conclusion, Confidence) proposed as a fourth provenance axis beyond Source/Acquisition/Analytical provenance, directly derived from Stigler's 'garden of forking paths', applied to an AI agent that searches 100 paths but exposes only the 3 successful ones; framed as possibly more important than Bayesian inference itself since Bayesian updating assumes the considered evidence is fully known." (anchor: "garden of forking paths: conclusions can emerge after many choices concerning data, direction, question, grouping, analysis. ... Epistemic lineage is part of knowledge identity. ... A conclusion whose selection path materially influenced its evidential status must retain the path information necessary to assess that influence.")
- [S0456] types=['GOVERNANCE', 'PRINCIPLE'] scope=CROSS-OBJECT — "Proposes that deterministic verification/assurance gates must check the epistemic process (competing explanations inspected? selection accounted for? residuals inspected? evidence independently generated? search path bias? assumptions explicit?), not just the final artifact; and that the agent should not become the permanent owner of knowledge -- KnowledgeOS should own the epistemic record." (anchor: "Deterministic verification should verify the epistemic process, not only the final artifact. ... AI-generated knowledge must preserve material alternative paths considered during investigation when those paths affect evidential interpretation or conclusion selection.")
- [S0457] types=['FORMALIZATION', 'GOVERNANCE'] scope=CROSS-OBJECT — "Optimal Agent Architecture: agents query KnowledgeOS Resolution (relevant knowledge/scope/standing/evidence/authority/uncertainty/contradictions/residuals) then submit candidates back through KnowledgeOS Admission (accept/refuse/require evidence/require authority/mark unknown); paired with a 16-step recommended AI Investigation Protocol (state question, scope, competing hypotheses, acquire evidence, record provenance, assess quality, identify assumptions, infer, compare alternatives, record uncertainty, inspect residuals, record prediction, determine identifiability, abstain where required, submit candidate determination, preserve investigation lineage) called 'the most useful combined technique from all four books.'" (anchor: "AI agents should not carry the authoritative KnowledgeOS state in prompts or local files. ... The agent is a consumer and producer of candidates, not the owner of epistemic truth. ... Recommended AI Investigation Protocol: 1. State the question ... 16. Preserve investigation lineage.")

## Notes for P3
- No internal tension, evidentiary anomaly, or lifecycle-flag discrepancy observed in this label's own rows beyond what the completeness roll-up above already shows.
