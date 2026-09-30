# hetvabhasa-fallacy-invariants

**Scope(s):** OBJECT · **Row count:** 5 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `H-KOS-Competing-Reasoning-001`, `H-KOS-Contradictory-Reasoning-001`, `H-KOS-Evidence-Override-001`, `H-KOS-Inference-Consistency-001`, `H-KOS-Premise-Grounding-001` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0960**: [`hetvabhasa-fallacy-invariants` · `hetvabhasa-fallacy-taxonomy`] — working_label token overlap Jaccard=0.50 (shared tokens: ['fallacy', 'hetvabhasa'])
- **G1160**: [`hetvabhasa-fallacy-invariants` · `knowledge-error-state-lattice`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0006, scope OBJECT): The five named invariants S0226 derives by mapping each of the five classical Nyaya Hetvabhasa (reasoning fallacies) to an AI/architecture reasoning-corruption pattern.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0226] §"Every inference SHALL preserve validated relation strength between premise and conclusion."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0226. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows) — this DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S0226, S0226, S0226, S0226, S0226 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | NOT-EVIDENCED-IN-CAPTURE | — |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S0226, S0226, S0226, S0226, S0226 |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | PRESENT | S0226 |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Savyabhicara (invalid generalization; Nyaya example 'Hill has fire because Hill is knowable') is mapped to an AI hallucination pattern: observing 'System uses Kubernetes' and over-inferring 'therefore it is cloud-native' (false, since Kubernetes can be on-premise/hybrid). Derived invariant H-KOS-Inference-Consistency-001: 'Every inference SHALL preserve validated relation strength between premise and conclusion.' [S0226] Viruddha (reason proves the opposite; Nyaya example 'Sound is eternal because it is produced' but 'produced' implies non-eternal) is mapped to an architecture decision example: 'Microservices improve independence' justified by evidence that 'every service requires shared database', which contradicts the claim. Derived invariant H-KOS-Contradictory-Reasoning-001. [S0226] Satpratipaksa (competing reasoning: two equally valid-looking arguments) is warned to be a case current AI systems fail at, because they resolve to 'highest probability answer' instead of surfacing conflict. Example: 'Technology X is secure' with Evidence A (audit passed) vs Evidence B (critical vulnerability discovered) should yield KnowledgeState=CONFLICTED, not KnowledgeState=FALSE. Derived invariant H-KOS-Competing-Reasoning-001. [S0226] Asiddha (unproven premise; Nyaya example a non-existent 'sky-lotus' being fragrant 'because it is a lotus') is called 'the foundation of hallucination', mapped to an LLM inferring 'Framework X has performance issue Y' from 'the company uses Framework X' when that premise was never established. Requires a Premise -> Evidence -> Existence verification -> Inference chain. Derived invariant H-KOS-Premise-Grounding-001. [S0226] Badhita (overridden by stronger evidence; Nyaya example 'Fire is cold because it is a substance', defeated by direct perception) is called 'perhaps the most important for temporal knowledge', mapped to: old knowledge 'Version 1.0 is secure' overridden by new evidence 'Critical CVE discovered'; the old statement is not deleted but transitions Knowledge State VALID -> SUPERSEDED_BY_EVIDENCE. Derived invariant H-KOS-Evidence-Override-001. [S0226]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0226] types=[ANALYSIS, INVARIANT] scope=THEORY-LEVEL — "Savyabhicara (invalid generalization; Nyaya example 'Hill has fire because Hill is knowable') is mapped to an AI hallucination pattern: observing 'System uses Kubernetes' and over-inferring 'therefore it is cloud-native' (false, since Kubernetes can be on-premise/hybrid). Derived invariant H-KOS-Inference-Consistency-001: 'Every inference SHALL preserve validated relation strength between premise and conclusion.'" (anchor: "Every inference SHALL preserve validated relation strength between premise and conclusion.")
- [S0226] types=[ANALYSIS, INVARIANT] scope=THEORY-LEVEL — "Viruddha (reason proves the opposite; Nyaya example 'Sound is eternal because it is produced' but 'produced' implies non-eternal) is mapped to an architecture decision example: 'Microservices improve independence' justified by evidence that 'every service requires shared database', which contradicts the claim. Derived invariant H-KOS-Contradictory-Reasoning-001." (anchor: "A justification SHALL NOT support a conclusion whose semantic consequence contradicts the premise.")
- [S0226] types=[ANALYSIS, INVARIANT, WARNING] scope=THEORY-LEVEL — "Satpratipaksa (competing reasoning: two equally valid-looking arguments) is warned to be a case current AI systems fail at, because they resolve to 'highest probability answer' instead of surfacing conflict. Example: 'Technology X is secure' with Evidence A (audit passed) vs Evidence B (critical vulnerability discovered) should yield KnowledgeState=CONFLICTED, not KnowledgeState=FALSE. Derived invariant H-KOS-Competing-Reasoning-001." (anchor: "Equally supported contradictory inferences SHALL remain unresolved until additional evidence changes the epistemic state.")
- [S0226] types=[ANALYSIS, INVARIANT] scope=THEORY-LEVEL — "Asiddha (unproven premise; Nyaya example a non-existent 'sky-lotus' being fragrant 'because it is a lotus') is called 'the foundation of hallucination', mapped to an LLM inferring 'Framework X has performance issue Y' from 'the company uses Framework X' when that premise was never established. Requires a Premise -> Evidence -> Existence verification -> Inference chain. Derived invariant H-KOS-Premise-Grounding-001." (anchor: "No inference SHALL operate on an unverified entity, property, or relation.")
- [S0226] types=[ANALYSIS, INVARIANT] scope=THEORY-LEVEL — "Badhita (overridden by stronger evidence; Nyaya example 'Fire is cold because it is a substance', defeated by direct perception) is called 'perhaps the most important for temporal knowledge', mapped to: old knowledge 'Version 1.0 is secure' overridden by new evidence 'Critical CVE discovered'; the old statement is not deleted but transitions Knowledge State VALID -> SUPERSEDED_BY_EVIDENCE. Derived invariant H-KOS-Evidence-Override-001." (anchor: "Higher authority evidence may invalidate a conclusion while preserving historical lineage.")

## Notes for P3
Carries 2 candidate group memberships (G0960, G1160); P3 should prioritize resolving whether these reflect the same underlying object — multiple memberships here only means more surface signal touched this label, not that it is more likely to be a duplicate. Lifecycle (DORMANT) rests on recency heuristics only — no explicit retraction, supersession, or self-contradiction signal was found in this label's own rows.
