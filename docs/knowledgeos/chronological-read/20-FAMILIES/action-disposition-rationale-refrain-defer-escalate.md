# action-disposition-rationale-refrain-defer-escalate

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Authority_agent<Authority_required⇒ESCALATE`; `Evidence obtainable later⇒DEFER`; `InsufficientEvidence⇒REFRAIN`; `δ(E,R,Auth,C)→{ACT,REFRAIN,DEFER,ESCALATE,...}`
**Aliases:** "why REFRAIN/DEFER/ESCALATE matter"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0033, scope OBJECT: "Step 161's worked justification for three of the ActionDisposition values: REFRAIN is the correct (not failure) outcome when evidence is insufficient and unlikely to improve; DEFER is correct when evidence is currently insufficient but additional evidence can reasonably be obtained; ESCALATE is correct when the acting agent's authority is less than the authority required. Models a non-deterministic disposition function delta(E,R,Auth,C) -> {ACT,REFRAIN,DEFER,ESCALATE,...} as a semantic model only (not yet implemented), paired with the invariant that the system must not represent an uncertain conclusion as certain merely because an AI generated fluent text ('fluency is not evidence')."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1354 §"Consider: Evidence = insufficient. A naive AI workflow might still produce: Decision → ACT. Our architecture should allow: InsufficientEvidence ⇒ REFRAIN. This is not failure. It can be the correct epistemic outcome. ... Why DEFER matters ... Why ESCALATE matters ... Authority_agent < Authority_required ⇒ ESCALATE."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1354. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S1354), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1354 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1354 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1354 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

Worked argument for three ActionDisposition values: REFRAIN is correct (not a failure) when evidence is insufficient (InsufficientEvidence => REFRAIN), countering a naive AI workflow that would still ACT; DEFER is correct when evidence is currently insufficient but additional evidence can reasonably be obtained (distinct from REFRAIN); ESCALATE is correct when the acting agent's authority is less than the authority required (Authority_agent < Authority_required => ESCALATE) [S1354].

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1354] types=[ARGUMENT, EXAMPLE] scope=OBJECT — "Worked argument for three ActionDisposition values: REFRAIN is correct (not a failure) when evidence is insufficient (InsufficientEvidence => REFRAIN), countering a naive AI workflow that would still ACT; DEFER is correct when evidence is currently insufficient but additional evidence can reasonably be obtained (distinct from REFRAIN); ESCALATE is correct when the acting agent's authority is less than the authority required (Authority_agent < Authority_required => ESCALATE)." (anchor: "Consider: Evidence = insufficient. A naive AI workflow might still produce: Decision → ACT. Our architecture should allow: InsufficientEvidence ⇒ REFRAIN. This is not failure. It can be the correct epistemic outcome. ... Why DEFER matters ... Why ESCALATE matters ... Authority_agent < Authority_required ⇒ ESCALATE.")

## Notes for P3

- This is my own observation: singleton label (1 row) — evidence base is thin by construction; no internal corroboration is possible from this label alone.
