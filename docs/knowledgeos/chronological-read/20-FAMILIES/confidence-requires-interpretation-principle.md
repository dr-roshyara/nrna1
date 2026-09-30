# confidence-requires-interpretation-principle

**Scope(s):** THEORY-LEVEL · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `P(Y=1|C~=0.9)~=0.9`; `c_AI=0.97`
**Aliases:** "confidence vs calibration"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 192's AI-assurance principle that a model confidence score requires interpretation (calibration, population, procedure, model version) before it can be read as a probability of truth; includes the model-drift example (same evidence, different model, different score)."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1395 §"Confidence requires interpretation before it becomes probability."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1395. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S1395), not a confirmed retirement or confirmed ongoing status.

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
| semantics | PRESENT | S1395 (×2) |
| examples | PRESENT | S1395 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE). `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1395] types=[PRINCIPLE] scope=THEORY-LEVEL — "An AI confidence score c_AI=0.97 should not be interpreted as P(Truth)=0.97 unless calibration and semantics justify it; proposed as an AI assurance principle. A calibrated classifier under defined conditions can achieve P(Y=1|C~=0.9)~=0.9, but that calibration meaning depends on population, procedure, distribution, model version, and evaluation period, all of which the architecture should preserve." (anchor: "Confidence requires interpretation before it becomes probability.")
- [S1395] types=[EXAMPLE, PRINCIPLE] scope=OBJECT — "Model drift example: the same evidence E scored by M1 (0.92) versus M2 (0.61) does not mean the evidence changed -- the model changed; therefore model identity must be treated as part of provenance." (anchor: "Assessment = f(E,M,A). Model identity is part of provenance.")

## Notes for P3

- No internal tension, unknown-candidate marker, or contested-lifecycle discrepancy was observed in this label's own rows; evidentiary base is straightforward for its row count.
