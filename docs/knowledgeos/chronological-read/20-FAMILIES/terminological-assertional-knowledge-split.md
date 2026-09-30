# terminological-assertional-knowledge-split

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `K_t=(K_t^T,K_t^A)`
**Aliases:** "terminological vs assertional knowledge"
**Candidate group membership (NOT an identity claim):**
- G1794: links this to `consistency-operator-vs-contradiction-distinction` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- G1798: links this to `expressiveness-tractability-tradeoff-principle` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- G1801: links this to `requirement-subsumption-preceq-r-candidate` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- G1803: links this to `successor-state-semantics-candidate` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- G1805: links this to `zero-vs-closed-world-assumption-comparator` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0061, scope OBJECT: "Candidate KnowledgeOS state split into terminological/schema-level knowledge K_t^T and assertional/domain-instance knowledge K_t^A, adopting the DL TBox/ABox distinction as a formal analogy without adopting the identity (K_t^T != TBox)."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S2523 §"K_t=(K_t^{T},K_t^{A}) ... K_t^{T}\neq K_t^{A} because they answer different questions ... Status: Can be integrated into the theory as [PROP]"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2523 §"K_t=(K_t^{T},K_t^{A}) ... K_t^{T}\neq K_t^{A} because they answer different questions ... Status: Can be integrated into the theory as [PROP]"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2523 §"KR.1 Representation Layers ... KR.6 Open-World Constraint ... KR.9 Transition ... KR.10 Expressiveness and Tractability"]

## Lifecycle

last_seen: S2524. Candidate lifecycle: ACTIVE.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). ACTIVE is a heuristic based on how recently (by source_id) this label was last used (last_seen: S2524), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2523 |
| type_signature | PRESENT | S2523 |
| invariants | PRESENT | S2523 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2523, S2524 (×2) |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE). `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S2523] types=[FORMALIZATION, EXTENSION] scope=OBJECT, completeness PARTIAL (missing: exact KnowledgeOS data model) — "Proposes KnowledgeOS adopt the terminological/assertional distinction (not the DL identity): K_t=(K_t^T,K_t^A), K_t^T != K_t^A, giving a formal reason to separate conceptual vocabulary from instance-level content in a theory that currently mixes requirements, concepts, facts, observations, propositions and relations. Status: [PROP], strong external formal support, does not yet determine the exact KnowledgeOS data model." (anchor: "K_t=(K_t^{T},K_t^{A}) ... K_t^{T}\neq K_t^{A} because they answer different questions ... Status: Can be integrated into the theory as [PROP]")
- [S2523] types=[GOVERNANCE, RESTATEMENT] scope=THEORY-LEVEL, also labeled `requirement-subsumption-preceq-r-candidate`, also labeled `consistency-operator-vs-contradiction-distinction`, also labeled `zero-vs-closed-world-assumption-comparator`, also labeled `successor-state-semantics-candidate`, also labeled `expressiveness-tractability-tradeoff-principle` — "Proposes inserting a 10-section 'KNOWLEDGE REPRESENTATION AND STRUCTURED REASONING' theory addition (KR.1 Representation Layers, KR.2 Reasoning Cn_S, KR.3 Subsumption, KR.4 Classification, KR.5 Consistency, KR.6 Open-World Constraint, KR.7 Evaluation via reasoning-service family, KR.8 Explanation/Missing Assumptions, KR.9 Transition/successor-state with Boundary!=FrameAxiom retained, KR.10 Expressiveness/Tractability), consolidating all the file's individually-derived candidates into one proposed section." (anchor: "KR.1 Representation Layers ... KR.6 Open-World Constraint ... KR.9 Transition ... KR.10 Expressiveness and Tractability")
- [S2524] types=[RESTATEMENT, GOVERNANCE] scope=CROSS-OBJECT, also labeled `description-logic-handbook-extraction` — "Seven DL results are given chapter/section citations and formally marked [ESTABLISHED]: TBox/ABox distinction (Ch.2 S2.2), subsumption C⊑_T D (Ch.2 S2.2.4), classification (Ch.2 S2.2.4), consistency Cons_S(K) (Ch.2 S2.2.4), instance checking (Ch.2 S2.2.4), open-world semantics (Ch.2 S2.2.4), and the expressiveness/tractability tradeoff (Ch.3 S3.1) -- each restating a candidate already introduced in S2522/S2523 but now with source citation and a firmer [ESTABLISHED] tag applied to the DL-native fact (not yet to its KnowledgeOS application, which stays [PROP])." (anchor: "1.1 TBox/ABox Distinction ... Source: DL Handbook, Chapter 2, Section 2.2 ... Status: [ESTABLISHED] — This is a formal distinction in DL that KnowledgeOS can adopt as a structural hypothesis.") — lineage claim: SOURCE-CLAIMED-CONTINUATION of 20260902-180005_extraction-description-logic-handbook.md and 20260902-180011_review-yes1.md — review_flag: TYPE-QUESTION
- [S2524] types=[RESTATEMENT, GOVERNANCE] scope=THEORY-LEVEL, also labeled `requirement-subsumption-preceq-r-candidate`, also labeled `consistency-operator-vs-contradiction-distinction`, also labeled `zero-vs-closed-world-assumption-comparator`, also labeled `successor-state-semantics-candidate`, also labeled `expressiveness-tractability-tradeoff-principle`, also labeled `prime-implicate-minimal-gap-hypothesis` — "Restates the ten-section KR.1-KR.10 theory insertion verbatim from S2523 with an explicit status tag assigned to each section: KR.1 Representation [PROP], KR.2 Reasoning [PROP], KR.3 Subsumption [PROP], KR.4 Classification [PROP], KR.5 Consistency [PROP], KR.6 Open-World Constraint promoted to [ESTABLISHED], KR.7 Evaluation [PROP], KR.8 Explanation/Gap [PROP], KR.9 Transition [PROP] (Boundary != FrameAxiom retained), KR.10 Expressiveness/Tractability promoted to [ESTABLISHED] as a KnowledgeOS methodological principle -- the only two sections elevated above [PROP]." (anchor: "KR.1 Representation Layers [PROP] ... KR.6 Open-World Constraint [ESTABLISHED] — Supported by DL's open-world semantics ... KR.10 Expressiveness and Tractability [ESTABLISHED] — Now a KnowledgeOS methodological principle") — lineage claim: SOURCE-CLAIMED-CONTINUATION of 20260902-180011_review-yes1.md KR.1-KR.10 proposal

## Notes for P3

- This label sits in 5 candidate groups — a relatively high cross-reference count for this batch, worth prioritizing in P3's reconciliation queue.
