# retrieval-vs-answer-reproducibility

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** RetrievalReproducibility != AnswerReproducibility · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0067, scope THEORY-LEVEL: Requirements for reproducible retrieval, its distinctness from reproducible generation, and deterministic vs stochastic retrieval.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2783 §"SameQuery ⇏ SameRetrieval [without version provenance]. ... RetrievalReproducibility != AnswerReproducibility."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2783. Candidate lifecycle: ACTIVE.
Evidence: no retraction/supersession/contradiction evidence recorded — the ACTIVE classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

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

## All rows
- [S2783] types=['DISTINCTION'] scope=THEORY-LEVEL — "20.79-20.81: retrieval reproducibility Replay(RO)≈Results_original requires corpus snapshot, index/embedding/ranking-model/query-transformation versions, filters, permissions and time, without which SameQuery⇏SameRetrieval; even with identical retrieval R1=R2, generation may still differ by model version, stochastic decoding, system prompt, temperature or context ordering, so RetrievalReproducibility≠AnswerReproducibility and both must be specified separately; distinguishes deterministic retrieval R(Q)=R from stochastic retrieval R(Q,omega) requiring seed/configuration preservation for reproducibility." (anchor: "SameQuery ⇏ SameRetrieval [without version provenance]. ... RetrievalReproducibility != AnswerReproducibility.")

## Notes for P3
None beyond what is recorded above.
