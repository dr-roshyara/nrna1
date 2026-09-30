# lexical-semantic-hybrid-search-and-embedding-fallacy

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `sim(x,y) ~= 1 => x =_sem y` [invalid]
**Aliases:** "Embedding Equivalence Fallacy"
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0067, scope THEORY-LEVEL: "Lexical vs semantic search distinctions, the embedding equivalence fallacy, embedding/retrieval model drift, and hybrid retrieval."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S2783 §"LexicalMatch != SemanticEquivalence. ... sim(x,y) ≈ 1 ⇒ x ≡_sem y [is generally invalid]. ... RetrievalModelDrift."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S2783 (single row/single source). Candidate lifecycle: ACTIVE.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). ACTIVE is a recency heuristic only.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2783 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S2783 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty. `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S2783] types=[DISTINCTION, WARNING] scope=THEORY-LEVEL — "20.15-20.19: lexical search Ret_L(Q,D) preserves exact terminology/identifiers/legal-phrases/version-numbers but LexicalMatch≠SemanticEquivalence; semantic search uses an embedding φ and similarity sim(φ(x),φ(q)) that is model-relative — sim(x,q)=0.91 means only that the chosen representation assigns high similarity, not x≡_sem q; names the Embedding Equivalence Fallacy sim(x,y)≈1⇒x≡_sem y as generally invalid since embeddings compress information and different concepts can map to nearby regions (EmbeddingSpace is a retrieval representation, not a complete semantic ontology); if embedding model E1 is replaced by E2, φ_E1(x) may differ substantially from φ_E2(x), producing RetrievalModelDrift, so embedding versions must be part of retrieval provenance; recommends hybrid retrieval R=R_lexical∪R_semantic∪R_structured∪R_graph combining methods that preserve different distinctions." (anchor: "LexicalMatch != SemanticEquivalence. ... sim(x,y) ≈ 1 ⇒ x ≡_sem y [is generally invalid]. ... RetrievalModelDrift.")

## Notes for P3

- This is a thin, single-row/single-source label, but the content is a clean, self-contained formal warning (the "Embedding Equivalence Fallacy") that reads as a reusable engineering caution independent of the rest of this batch — no evident internal tension.
- Worth flagging: `RetrievalModelDrift` (embedding-version-as-provenance) is conceptually adjacent to the `adversarial-epistemology-integrity-trust-manipulation-algebra` label's temporal-trust and provenance material elsewhere in this batch, though no mechanical group_id connects them. P3 may want to check whether retrieval/embedding provenance should eventually be modeled under the same provenance framework as source/evidence provenance.
