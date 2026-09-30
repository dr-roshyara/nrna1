# conformance-classes-c1-c4

**Scope(s):** OBJECT · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "C1 semantic", "C2 structural", "C3 behavioral", "C4 epistemic" · **Aliases:** "four conformance classes"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0033, scope OBJECT: "Step 158's four conformance classes for judging KnowledgeOS implementation conformance: C1 Semantic (correct concepts), C2 Structural (correct code boundaries), C3 Behavioral (runtime enforcement), C4 Epistemic (can the system justify claims via provenance/evidence); combined via an unspecified C_overall=f(C_semantic,C_structural,C_behavioral,C_epistemic), with the rule that a system should not be called fully conformant if one dimension is catastrophically weak, and a caution against false numerical precision absent a defined measurement model."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1349 §"C1 — Semantic conformance ... C2 — Structural conformance ... C3 — Behavioral conformance ... C4 — Epistemic conformance ... C_overall = f(C_semantic, C_structural, C_behavioral, C_epistemic). A system should not be called fully conformant if one dimension is catastrophically weak."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1349 §"C1 — Semantic conformance ... C2 — Structural conformance ... C3 — Behavioral conformance ... C4 — Epistemic conformance ... C_overall = f(C_semantic, C_structural, C_behavioral, C_epistemic). A system should not be called fully conformant if one dimension is catastrophically weak."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1349. Candidate lifecycle: DORMANT. Evidence: retracted_by and superseded_by are both empty and no row is self-typed as a contradiction; this is a heuristic based on how recently (by source_id order) this label was last used (S1349), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1349 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1349 |
| type_signature | PRESENT | S1349 |
| invariants | PRESENT | S1349 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1349, S1349 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Step 158 verdict: the conceptual KnowledgeOS architecture remains coherent but must be treated as a target model until implementation evidence establishes conformance; conformance is redefined from a single structural question ('does the code have the right modules?') to four questions -- is the meaning correct, is the structure correct, does the runtime enforce it, and can the system justify what it claims -- with the fourth (epistemic) framed as the major new addition contributed by the Chapter 4 (Gita) review. [S1349]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1349] types=[FORMALIZATION, PRINCIPLE] scope=THEORY-LEVEL — "Defines four conformance classes -- C1 Semantic (correct concepts used), C2 Structural (correct code boundaries), C3 Behavioral (runtime enforcement), C4 Epistemic (system can justify claims via provenance/evidence, 'newly strengthened by Chapter 4') -- combined via an unspecified function C_overall=f(C_semantic,C_structural,C_behavioral,C_epistemic), with the rule that a system must not be called fully conformant if any one dimension is catastrophically weak (worked example: C_structural=1 but C_epistemic=0.4 means 'the software is cleanly modular, but its knowledge claims are poorly governed'). Warns against false numerical precision (e.g. '82.7% compliant') absent a defined measurement model; prefers a qualitative HIGH/MEDIUM/LOW/GAP scale." (anchor: "C1 — Semantic conformance ... C2 — Structural conformance ... C3 — Behavioral conformance ... C4 — Epistemic conformance ... C_overall = f(C_semantic, C_structural, C_behavioral, C_epistemic). A system should not be called fully conformant if one dimension is catastrophically weak.")
- [S1349] types=[RESTATEMENT, ANALYSIS] scope=THEORY-LEVEL — "Step 158 verdict: the conceptual KnowledgeOS architecture remains coherent but must be treated as a target model until implementation evidence establishes conformance; conformance is redefined from a single structural question ('does the code have the right modules?') to four questions -- is the meaning correct, is the structure correct, does the runtime enforce it, and can the system justify what it claims -- with the fourth (epistemic) framed as the major new addition contributed by the Chapter 4 (Gita) review." (anchor: "The conceptual KnowledgeOS architecture remains coherent, but it must now be treated as a target model until implementation evidence establishes conformance. ... 1. Is the meaning correct? 2. Is the structure correct? 3. Does the runtime enforce it? 4. Can the system justify what it claims? That fourth question is the major epistemic addition.")

## Notes for P3
- This label is ungrouped in P2a — no mechanical signal (token overlap, co-occurrence, or explicit cross-reference) connected it to any other label in this batch's normalization pass.
