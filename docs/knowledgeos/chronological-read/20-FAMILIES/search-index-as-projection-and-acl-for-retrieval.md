# search-index-as-projection-and-acl-for-retrieval

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `I = f(K)`, `Index != CanonicalKnowledgeState` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope THEORY-LEVEL): Candidate retrieval architecture chain, ACL requirement for external search results, search index as a rebuildable projection, and index drift as a semantic fitness property.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2783 §"Index != CanonicalKnowledgeState. ... Rebuild(K) -> I' ... Sem(I') ≡_Q Sem(I) ... I_t not≈ f(K_t) [index drift]"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2783. Candidate lifecycle: ACTIVE.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S2783) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
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
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S2783]` types=[DISTINCTION, CONSTRAINT] scope=THEORY-LEVEL — "20.74-20.78: proposes a candidate retrieval architecture chain QueryContext->QueryInterpreter->CandidateRetrievers->Ranker->EvidenceSelector->EvidenceContext->InferenceContext->DeterminationContext (candidates, not mandated separate services); requires an Anti-Corruption Layer for external search results (ExternalSearchResult=<url,score,snippet>) since KnowledgeOS must not automatically interpret 'score' as evidence quality, mapping explicitly to a KnowledgeOS RetrievalResult; a search index I=f(K) is a projection that may omit history/conflicts/provenance/temporal-semantics/authorization, so Index≠CanonicalKnowledgeState; a rebuilt index Rebuild(K)->I' should satisfy Sem(I')≡_Q Sem(I) under the retrieval contract (subject to model/version changes), and index drift I_t≉f(K_t) (from failed updates, stale embeddings, orphaned/missing documents, schema mismatch) makes index health a semantic fitness property." (anchor: "Index != CanonicalKnowledgeState. ... Rebuild(K) -> I' ... Sem(I') ≡_Q Sem(I) ... I_t not≈ f(K_t) [index drift]")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
