# retrieval-provenance-and-temporal-participant-relativity

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `RP=<Query,Retriever,RetrieverVersion,...>`, `R_A(Q) != R_B(Q)` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope THEORY-LEVEL): Retrieval provenance tuple, temporal retrieval variability, snapshot retrieval, and participant-relative retrieval via knowledge views.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2783] §"SameQuery ⇏ SameResult [without retrieval provenance]. ... R_{t_1}(Q) != R_{t_2}(Q) ... R_A(Q) != R_B(Q) may be legitimate."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2783] §"SameQuery ⇏ SameResult [without retrieval provenance]. ... R_{t_1}(Q) != R_{t_2}(Q) ... R_A(Q) != R_B(Q) may be legitimate."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2783. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This ACTIVE classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2783 |
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
- [S2783] types=[FORMALIZATION, DISTINCTION] scope=THEORY-LEVEL — "20.10-20.14: search is Query->CandidateGeneration->Ranking->Filtering->Presentation, and the displayed result requires retrieval provenance RP=<Query,Retriever,RetrieverVersion,Index,Timestamp,Filters,Ranking,Candidates,SelectionRule> (and RR=<Object,Score,Rank,RP>) since without it SameQuery⇏SameResult; retrieval is temporal (R_{t1}(Q)≠R_{t2}(Q) from new/retracted documents, changed ranking/index/permissions/embeddings/knowledge), motivating snapshot retrieval R(Q,K_t) vs R(Q,K_now) for historical decisions/audits/experiments/legal review/reproducibility; because participants may have different knowledge views View_A(K), retrieval may legitimately differ R_A(Q)≠R_B(Q) reflecting authorization/context rather than inconsistency." (anchor: "SameQuery ⇏ SameResult [without retrieval provenance]. ... R_{t_1}(Q) != R_{t_2}(Q) ... R_A(Q) != R_B(Q) may be legitimate.")

## Notes for P3
Very thin evidentiary base (1-2 rows) — classification here is provisional and should be revisited if more contributions surface.
