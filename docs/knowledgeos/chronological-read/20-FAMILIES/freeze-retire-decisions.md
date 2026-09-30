# freeze-retire-decisions

**Scope(s):** OBJECT (node-level); the single row itself is scoped THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0002, scope OBJECT: "The proposed FREEZE/RETIRE decision types missing from the Engineering Decision Model."

No `single_candidate_flags` recorded.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0041 §"FREEZE / RETIRE as missing decisions | FREEZE n≥3 undeclared · PM-6 open"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0041, same anchor]

## Lifecycle
last_seen: S0041. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is entirely empty. This DORMANT classification is a heuristic based on recency of last use (by source_id), not a confirmed retirement — note the row itself is explicitly an OPEN-QUESTION type, so "dormant" here plausibly reflects that this open question has not been revisited in the captured data since 2026-08-03 (its `explicit_date`), not that it was resolved or abandoned.

## Completeness roll-up

Note: this row's own `completeness` field is `"N/A"` (distinct from the per-dimension roll-up below, which is a separate derived structure).

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0041 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE (`rationale_evidence` is empty; `rationale_truncated_count` is 0).

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S0041] types=[OPEN-QUESTION, GOVERNANCE] scope=THEORY-LEVEL, explicit_date=2026-08-03 — "FREEZE and RETIRE are submitted as insufficiency evidence for missing decision types in the Engineering Decision Model: FREEZE has occurred undeclared at least 3 times and PM-6 (retirement) remains open; the EDM's own ladder would resolve it, ruled by the ARB." (anchor: "FREEZE / RETIRE as missing decisions | FREEZE n≥3 undeclared · PM-6 open"). Also carries a second label in the source data, `decision-model` (not one of this batch's 21 assigned labels — noted for completeness, not processed here).

## Notes for P3
Single-row label carrying an explicit dated open-governance question (2026-08-03) about FREEZE/RETIRE decision types missing from the Engineering Decision Model, with an explicit resolution path named ("the EDM's own ladder ... ruled by the ARB") but no evidence in this row that the ARB has ruled. P3 should check whether this open question was later resolved elsewhere in the corpus (outside this label's captured rows). Note the row also carries a second label `decision-model`, outside this batch's 21 assigned labels — flagging only, not processed.
