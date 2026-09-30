# kr-context-integration-01-proposed

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** none recorded · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G1070: `kr-context-integration-01-proposed` · `kr-integration-proposed-theory-extension` — working_label token overlap Jaccard=0.50 (shared tokens: integration, kr, proposed). Relationship not yet decided (P3).

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0067, scope OBJECT: "A newly proposed experiment testing local-to-global knowledge-state integration, replacing further symmetric Zoom-out testing."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2813 §"KR-CONTEXT-INTEGRATION-01 -- Local Determination -> Global Knowledge-State Integration. ... test whether the resulting K_{t+1}: 1. preserves unrelated context, 2. incorporates the new determination, 3. preserves provenance/history, 4. permits later revision, 5. changes the answer to the original bro"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2813 (this label's only row). Candidate lifecycle: ACTIVE. Evidence: no `retracted_by`, no `superseded_by`, `contested_by_own_contradiction_type: false`. Single-source recency heuristic only — not confirmed as an ongoing or completed experiment.

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
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

Every dimension is NOT-EVIDENCED-IN-CAPTURE per the mechanical completeness roll-up, despite the row's own `statement` field describing a fairly detailed 7-point test design — see Notes for P3.

## Rationale
NOT-EVIDENCED-IN-CAPTURE (`rationale_evidence` is empty in the family data). The row's own statement gives a comparative rationale in passing ("argued to be a more faithful test of the original Nexus intuition, and doable without touching Theory v1.2 or reopening kernel selection" [S2813]), i.e. it is proposed as a replacement for further symmetric Zoom-in/Zoom-out testing because it is more faithful and lower-cost — reported here from the row's own statement text, not from a separate `rationale_evidence` entry.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE (`assumption_register` is empty in the family data).

## All rows (source_id order)
- [S2813] types=[EXTENSION] scope=OBJECT — "Proposes a new experiment, KR-CONTEXT-INTEGRATION-01, to replace further symmetric Zoom-in/Zoom-out testing: given (K_t,Q)→K_t^focus→Investigation→D_t→Integration→K_{t+1}, test whether K_{t+1} preserves unrelated context, incorporates the new determination, preserves provenance/history, permits later revision, changes the answer to the original broader inquiry, does not require an inverse operation, and distinguishes local graph traversal from global epistemic state change — argued to be a more faithful test of the original Nexus intuition, and doable without touching Theory v1.2 or reopening kernel selection." (anchor: "KR-CONTEXT-INTEGRATION-01 -- Local Determination -> Global Knowledge-State Integration...")

## Notes for P3
- Observation: the mechanical `completeness` roll-up marks every dimension NOT-EVIDENCED-IN-CAPTURE, even though the row's own `statement` describes a concrete seven-point test design (a de facto formal/operational sketch: preserve-context, incorporate-determination, preserve-provenance, permit-revision, answer-original-inquiry, no-inverse-operation-required, local-vs-global distinction). This looks like a case where the completeness classifier under-counted a single dense EXTENSION-typed row — flagging as a possible pipeline gap, not correcting it myself.
- Observation: this is a still-open proposal (an experiment proposed as a replacement for prior testing, not yet reported as run or completed in this row) — the family gives no result/experiment record, unlike some sibling proposal-type labels elsewhere in this batch that do carry a full `experiment` block.
- Observation: G1070's sibling label `kr-integration-proposed-theory-extension` is plausibly the "theory extension" companion to this "proposed experiment" — worth a priority look at P3 given the shared batch (B0067) and topical proximity (both about KR/context/integration), though no identity claim is made here.
