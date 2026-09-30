# retrieval-knowledge-gap-zero-and-open-world

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Delta_R`, `Zero_R(K,RC)` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope THEORY-LEVEL): Retrieval knowledge gap and retrieval-zero completeness notion (not truth), open-world default, closed-world-as-contract-property, negative results, and retrieval failure vs no-evidence.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2783] §"Delta_R = {r in Req(RC): not Sat(K,r)} ... Zero_R(K,RC) iff Delta_R = empty ... != Truth ... NotRetrieved != Nonexistent ... NoResult [does not imply] False(Q) ... RetrievalProbability != TruthProbability"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2783] §"Delta_R = {r in Req(RC): not Sat(K,r)} ... Zero_R(K,RC) iff Delta_R = empty ... != Truth ... NotRetrieved != Nonexistent ... NoResult [does not imply] False(Q) ... RetrievalProbability != TruthProbability"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2783. Candidate lifecycle: ACTIVE.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows) — this ACTIVE classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S2783 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S2783 |
| Examples | NOT-EVIDENCED-IN-CAPTURE | — |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2783] types=[FORMALIZATION, DISTINCTION] scope=THEORY-LEVEL — "20.41-20.48: defines a retrieval knowledge gap Delta_R (source coverage unknown, temporal coverage incomplete, provenance missing, inaccessible documents, unvalidated ranking, unknown recall, unresolved contradictions) and Retrieval Zero Zero_R(K,RC) iff Delta_R=∅, meaning only that the explicit retrieval contract is satisfied -- not that all real-world relevant information was found, nor truth, nor a correct final answer (Zero_R≠Truth); retrieval completeness Rel(Q)⊆Ret(Q) is generally unknown in practice and estimated against Rel*(Q); KnowledgeOS defaults to an open-world assumption unless the contract states otherwise, so NotRetrieved≠Nonexistent (absence may reflect retrieval failure/inaccessible source/indexing failure/ranking exclusion/query formulation, not nonexistence), while a closed-world universe is a contract property, not a universal default; an empty result R=∅ means NoResult under the configuration, not automatically False(Q) or Unknown(Q); retrieval failure (source/index/query/permission/embedding/timeout/ranking failure) must be represented as an operational RetrievalFailure state, not NoEvidenceExists; a retrieval confidence estimate P(Rel|Q,x) is a model output distinct from P(Truth(x)|Q) (RetrievalProbability≠TruthProbability)." (anchor: "Delta_R = {r in Req(RC): not Sat(K,r)} ... Zero_R(K,RC) iff Delta_R = empty ... != Truth ... NotRetrieved != Nonexistent ... NoResult [does not imply] False(Q) ... RetrievalProbability != TruthProbability")

## Notes for P3
Lifecycle (ACTIVE) rests on recency heuristics only — no explicit retraction, supersession, or self-contradiction signal was found in this label's own rows. Single-row label: the evidentiary base is thin by construction (one contribution) — treat every dimension marked NOT-EVIDENCED-IN-CAPTURE above as simply unobserved in this capture, not as absent from the underlying idea.
