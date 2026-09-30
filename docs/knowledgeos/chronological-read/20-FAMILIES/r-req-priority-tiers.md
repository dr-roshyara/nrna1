# r-req-priority-tiers

**Scope(s):** `THEORY-LEVEL` · **Row count:** 5 · **Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `P1 Core Invariant`, `P2 Required`, `P3 Extended Domain-Specific` · **Aliases:** `R_req priority tiers`
**Candidate group membership (NOT an identity claim):**
- **G1838**: linked with `r-req-question-relative-correction-package` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0061, scope THEORY-LEVEL): Three-tier prioritization: P1 (Epistemic Status, Justification Mode, Temporal Currency, Absence-vs-Evidence-of-Absence) must be preserved by all core kernels/projections; P2 (Allen relations, Resolution Status, Scope Boundary, Intensional Identity) must be natively supported by the top-level KR language; P3 (deontic/normative distinctions, fine-grained probability) is extended/domain-specific.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2551] §"Tier P1 (Core Invariants): Epistemic Status (1.1), Justification Mode (1.2), Currency (2.1), Absence vs. Evidence of Absence (3.2). Must be preserved by all core kernels and projections. Tier P2 (Required): Allen Relations (2.2), Resolution Status (4.1), Scope Boundary (4.2), Intensional Identity (1.3). Must be supported natively by the top-level KR language. Tier P3 (Extended Domain-Specific): Deontic/Normative distinctions (Can vs Should), Fine-grained probability distributions."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2551] §"Tier P1 (Core Invariants): Epistemic Status (1.1), Justification Mode (1.2), Currency (2.1), Absence vs. Evidence of Absence (3.2). Must be preserved by all core kernels and projections. Tier P2 (Required): Allen Relations (2.2), Resolution Status (4.1), Scope Boundary (4.2), Intensional Identity (1.3). Must be supported natively by the top-level KR language. Tier P3 (Extended Domain-Specific): Deontic/Normative distinctions (Can vs Should), Fine-grained probability distributions."

## Lifecycle
last_seen: `S2570`. Candidate lifecycle: **CONTESTED**.
Evidence: contested_by_own_contradiction_type: true.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2567, S2570 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S2570 |
| dependencies | PRESENT | S2567, S2567, S2570, S2570 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2567, S2570 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Overall compliance verdict across the four Tier-P1 test vectors: unaugmented FDE FAILs the R_req P1 suite (PARTIAL/FAIL/FAIL/PASS). Pure 4-valued FDE is judged necessary but insufficient as a standalone KnowledgeOS kernel: it perfectly handles Contradictory and Absence-of-Evidence-vs-Evidence-of-Absence, but collapses Underdetermined into Unknown, carries no justification lineage, and lacks temporal validity tracking. Proposes wrapping FDE in an Annotated/Graded Labeled Frame Space combining FDE truth values with justification graphs and temporal validity intervals to become R_req-compliant. [S2567] Splits the document's ten proposed additional distinctions into Group A (strong candidates deserving further KnowledgeOS work: truth-vs-validity, satisfiability-vs-unsatisfiability, soundness-vs-completeness, decidability-vs-undecidability, local-vs-global scope, intensional-vs-extensional, de-dicto-vs-de-re) and Group B (probably outside core R_req: analytic-vs-synthetic, a-priori-vs-a-posteriori, necessary-vs-contingent), recommending Group B remain external research candidates rather than Tier 2 requirements. [S2570]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S2551]` types=[GOVERNANCE] scope=THEORY-LEVEL — "Final prioritization: P1 core invariants (Epistemic Status, Justification Mode, Currency, Absence-vs-Evidence-of-Absence) must be preserved by every kernel/projection; P2 required distinctions (Allen relations, Resolution Status, Scope Boundary, Intensional Identity) must be natively supported by the top-level KR language; P3 extended domain-specific distinctions (deontic Can-vs-Should, fine-grained probability) are lower priority." (anchor: "Tier P1 (Core Invariants): Epistemic Status (1.1), Justification Mode (1.2), Currency (2.1), Absence vs. Evidence of Absence (3.2). Must be preserved by all core kernels and projections. Tier P2 (Requ…")
- `[S2567]` types=[RESTATEMENT] scope=METHODOLOGICAL — "Restates the R_req two-part verification suite (Separation Test: distinct-value pairs must map to distinct encodings or verification FAILS; Round-Trip Transformation Test: g-after-f must preserve every R_req distinction) and the three priority tiers (P1 Core Invariants: Epistemic Status, Justification Mode, Currency, Absence-vs-Evidence-of-Absence; P2 Required: Allen Relations, Resolution Status, Scope Boundary, Intensional Identity; P3 Extended: deontic distinctions, fine-grained probability)." (anchor: "Verification Criteria: The Separation Test ... The Round-Trip Transformation Test ... Prioritization: Tier P1 (Core Invariants) ... Tier P2 (Required) ... Tier P3 (Extended Domain-Specific)")
- `[S2567]` types=[ANALYSIS, LIMITATION, EXTENSION] scope=THEORY-LEVEL — "Overall compliance verdict across the four Tier-P1 test vectors: unaugmented FDE FAILs the R_req P1 suite (PARTIAL/FAIL/FAIL/PASS). Pure 4-valued FDE is judged necessary but insufficient as a standalone KnowledgeOS kernel: it perfectly handles Contradictory and Absence-of-Evidence-vs-Evidence-of-Absence, but collapses Underdetermined into Unknown, carries no justification lineage, and lacks temporal validity tracking. Proposes wrapping FDE in an Annotated/Graded Labeled Frame Space combining FDE…" (anchor: "Overall Suite Result: FAIL (Unaugmented FDE) ... Pure 4-valued First-Degree Entailment (FDE) is necessary but insufficient as a standalone KnowledgeOS kernel ... Required Architectural Enhancement: To…")
- `[S2570]` types=[ANALYSIS, DISTINCTION] scope=THEORY-LEVEL — "Splits the document's ten proposed additional distinctions into Group A (strong candidates deserving further KnowledgeOS work: truth-vs-validity, satisfiability-vs-unsatisfiability, soundness-vs-completeness, decidability-vs-undecidability, local-vs-global scope, intensional-vs-extensional, de-dicto-vs-de-re) and Group B (probably outside core R_req: analytic-vs-synthetic, a-priori-vs-a-posteriori, necessary-vs-contingent), recommending Group B remain external research candidates rather than Tie…" (anchor: "A. Strong candidates: 1. Truth vs. validity ... 2. Satisfiability vs. unsatisfiability ... 3. Soundness vs. completeness ... 4. Decidability vs. undecidability ... 5. Local vs. global scope ... 6. Int…")
- `[S2570]` types=[CORRECTION, CONTRADICTION] scope=THEORY-LEVEL — "Rejects the rule 'Tier 1 must always be preserved' as too rigid: even a Tier-1 distinction may be legitimately discarded by a representation used only for a task whose D_Q does not require it. Proposes Tier should govern governance priority, not automatically determine semantic preservation, i.e. Requiredness(Q,Gamma) takes precedence over Tier." (anchor: "I disagree with 'Tier 1 must always be preserved' ... A Tier-1 distinction d3 could be irrelevant to that question. Then R_1 may legitimately discard d3. So Tier(d) should influence governance priorit…")

## Notes for P3
- Nothing unusual observed while compiling this file.
