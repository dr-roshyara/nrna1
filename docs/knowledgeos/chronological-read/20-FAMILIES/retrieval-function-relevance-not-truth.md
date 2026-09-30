# retrieval-function-relevance-not-truth

**Scope(s):** `THEORY-LEVEL` · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Relevance != Truth != EvidenceStrength != Determination`, `Ret: K x Q x Gamma -> P(X)` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope THEORY-LEVEL): Formal retrieval function, ranking-as-model, and the boxed relevance/truth/evidence/determination separation, plus precision/recall not establishing truth.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2783] §"s(x,Q) != TruthScore(x). ... Relevance != Truth != EvidenceStrength != Determination. ... HighRecall ⇏ HighTruthfulness."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2783] §"s(x,Q) != TruthScore(x). ... Relevance != Truth != EvidenceStrength != Determination. ... HighRecall ⇏ HighTruthfulness."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: `S2783`. Candidate lifecycle: **ACTIVE**.
Evidence: none recorded (no retraction, supersession, or internal contradiction found). The **ACTIVE** classification is a heuristic based on how recently (by source_id ordering) this label was last used in the captured contributions, not a confirmed retirement or a confirmed ongoing status.

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
- `[S2783]` types=[FORMALIZATION, DISTINCTION] scope=THEORY-LEVEL — "20.5-20.9: defines Ret:K×Q×Γ->P(X) with per-object scores s(x_i,Q)≠TruthScore(x); Relevance(d,Q)=0.97 implies neither Truth(d)=1 nor EvidenceStrength(d,Q)=0.97 nor Determined(Q), giving the boxed Relevance≠Truth≠EvidenceStrength≠Determination; a ranking function Rank:X×Q->R (lexical/BM25/vector/hybrid/graph/learned) is itself a model M_rank whose output must not be silently promoted to epistemic standing; Precision/Recall are retrieval metrics that do not establish truth (HighRecall⇏HighTruthfulness, HighPrecision⇏Truth); even retrieval evaluation needs a declared reference relevance set Rel*(Q) which is itself context/purpose/temporal/participant/contract-dependent." (anchor: "s(x,Q) != TruthScore(x). ... Relevance != Truth != EvidenceStrength != Determination. ... HighRecall ⇏ HighTruthfulness.")

## Notes for P3
- Single-row label — thin evidentiary base by construction; any relationship claims beyond this one row would be unsupported.
