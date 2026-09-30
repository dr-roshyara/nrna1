# llm-confidence-vs-calibrated-probability

**Scope(s):** THEORY-LEVEL · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** LLMConfidence != Probability · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 189's AI-assurance invariant that an LLM's stated confidence is not automatically a calibrated statistical posterior, and the resulting architectural rule that EpistemicState should not itself carry a bare 'confidence' field.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1390] §"LLMConfidence\neq Probability."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1390. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1390 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1390 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1390 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
[S1390] (ARGUMENT/CONSTRAINT): Architectural conclusion: EpistemicState should not itself contain a bare 'confidence' field; EpistemicState and InferenceAssessment (which may contain P(H|E,M,A)) should be kept as separate objects.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- [S1390] types=['INVARIANT', 'WARNING'] scope=THEORY-LEVEL — "An LLM's stated confidence (e.g. 'I am 92% confident') is not automatically a calibrated probability distribution; LLMConfidence must be distinguished from StatisticalPosterior unless explicitly calibrated and defined under a statistical interpretation -- framed as an important AI assurance invariant." (anchor: "LLMConfidence\neq Probability.")
- [S1390] types=['ARGUMENT', 'CONSTRAINT'] scope=OBJECT — "Architectural conclusion: EpistemicState should not itself contain a bare 'confidence' field; EpistemicState and InferenceAssessment (which may contain P(H|E,M,A)) should be kept as separate objects." (anchor: "We should avoid: KnowledgeState { confidence: 0.92 } as the universal representation.")

## Notes for P3
- Very thin evidentiary base (row_count=2) — this family file is necessarily short; not evidence the object is unimportant, only that capture so far is sparse.
