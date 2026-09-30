# step185-temporal-difference-not-causal-and-ai-retrieval

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Architecture_2024 / Architecture_2026,planned / Architecture_current kept distinct, not merged, P_t=X, P_{t+1}=Y does not imply X->Y causally, nor that X was wrong earlier · **Aliases:** temporal difference is not causal; AI must not merge historical documents
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0033 · scope THEORY-LEVEL: Step 185 restates the statistician's warning that a temporal difference (P_t=X, later P_{t+1}=Y) does not imply causal influence X->Y, and observing Y later does not prove X was wrong earlier. Applies this concretely to AI retrieval: given three historical documents about Nexus architecture (2024 architecture, 2026 migration proposal, current infrastructure state), a naive AI merging them into one answer is wrong; KnowledgeOS should instead construct and keep distinct Architecture_2024, Architecture_2026,planned, and Architecture_current, then explicitly explain their relationships -- requiring TruthStatus(P,t) (temporally qualified) rather than a timeless TruthStatus(P).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1379 §"we cannot infer: X→Y without evidence. Likewise: Y observed later doesn't prove: X was wrong earlier. ... Architecture_2024 Architecture_2026,planned Architecture_current. Then explicitly explain their relationships. ... TruthStatus(P,t) rather than merely: TruthStatus(P)."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1379. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This heuristic status (DORMANT) is based only on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1379 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1379 |
| warnings | PRESENT | S1379 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1379] types=[WARNING, EXAMPLE] scope=THEORY-LEVEL — "Restates that a temporal difference (P_t=X, later P_{t+1}=Y) neither implies causal influence nor proves the earlier value was wrong. Applies this to AI retrieval: given three historical Nexus-architecture documents (2024, a 2026 migration proposal, current state), a naive AI must not merge them into one answer; KnowledgeOS should keep Architecture_2024, Architecture_2026,planned, and Architecture_current distinct and explain their relationships -- requiring a temporally-qualified TruthStatus(P,t) rather than a timeless TruthStatus(P)." (anchor: "we cannot infer: X→Y without evidence. Likewise: Y observed later doesn't prove: X was wrong earlier. ... Architecture_2024 Architecture_2026,planned Architecture_current. Then explicitly explain their relationships. ... TruthStatus(P,t) rather than merely: TruthStatus(P).")

## Notes for P3
(none beyond what is captured above)
