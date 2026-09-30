# unified-runtime-human-binding-invariant

**Scope(s):** THEORY-LEVEL · **Row count:** 5 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** "Human declares intended responsibility; runtime declares process identity; the governed bootstrap binds the two" · **Aliases:** unified binding model
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0009, scope THEORY-LEVEL — "PO/ARB's corrected invariant replacing the earlier absolute rule 'a session must never register itself': a fresh session may self-register only when role and work context are already established by a human business instruction or an existing governed commission; it must never self-choose role, scope, work item, or authority."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0349 §"The old statement 'A session must never register itself' was too strong. The correct rule: A fresh session may register itself only when the desired role and work context are already established by the human's business instruction or an existing governed commission."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0349 §"Human declares intended responsibility; runtime declares process identity; the governed bootstrap binds the two."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0355. Candidate lifecycle: DORMANT.
Evidence: no retracted_by, no superseded_by, and contested_by_own_contradiction_type is false. DORMANT is a heuristic based on how long ago (by source_id) S0355 was last used — not a confirmed retirement. Note that this label's own content is itself a stated CORRECTION/REPLACEMENT of a prior rule ("a session must never register itself") — that replacement is recorded in the row-level lineage_claims (SOURCE-CLAIMED-REPLACEMENT), not in the mechanical `lifecycle_evidence` fields, which are all empty/false for this label.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0349, S0355 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0349 (x2) |
| type_signature | PRESENT | S0349 (x2) |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0349 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0349 (x3), S0355 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
This invariant addresses a governance gap in the platform's session-bootstrap rules. The prior absolute rule ("a session must never register itself") was found too strong after observing a fresh verifier correctly refuse to proceed, and PO/ARB replaced it with a conditional rule: a fresh session may register itself only when the desired role and work context are already established by a human's business instruction or an existing governed commission [S0349]. The replacement is unified into a single mechanism — "human declares intended responsibility; runtime declares process identity; the governed bootstrap binds the two" — that covers both the very first session ever registered and every subsequent fresh session [S0349]. The gap this closed: previously the same gate-refusal scenario (a human ordering work with no pre-existing lane for the session) was treated purely as a defect to keep refusing, whereas under the corrected model it becomes the very capability the platform should add — a pre-commissioned session binding its own discovered runtime identity to its already-authorized role, then entering REGISTER → HANDOFF → human START [S0349]. A concrete case realizing this fix is recorded: a human order to "Start the Governance adoption review of KOS-OPERATING-MODEL-001" must not produce "no lane therefore refuse" [S0355], tying the invariant to the concrete capability AST-019 that was built to satisfy it.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S0349]` types=[CORRECTION, PRINCIPLE] scope=THEORY-LEVEL, explicit_date=2026-08-22 — "PO/ARB corrects the platform's prior absolute rule against self-registration: after watching a correctly-refusing fresh verifier, concludes the rule was too strong and replaces it with a conditional one permitting self-registration only when role and work context are already human-established, never self-chosen." Lineage claim: SOURCE-CLAIMED-REPLACEMENT targeting "'a session must never register itself'" — quote: "The old statement ... was too strong." (anchor: "The old statement 'A session must never register itself' was too strong. The correct rule: A fresh session may register itself only when the desired role and work context are already established by the human's business instruction or an existing governed commission.")
- `[S0349]` types=[PRINCIPLE, FORMALIZATION] scope=THEORY-LEVEL, explicit_date=2026-08-22 — "The unified binding model: one single mechanism serves both the very first session ('I want Governance Engineer') and every subsequent fresh session ('I want a Verification session for X'), because in both cases the human supplies the intended responsibility in business language, the runtime supplies the process identity, and the governed bootstrap validates and binds the two." Type signature: domain=(human-declared responsibility, runtime-declared identity), codomain=REGISTER{session, role}, arity=2, total=PARTIAL, deterministic=YES. (anchor: "Human declares intended responsibility; runtime declares process identity; the governed bootstrap binds the two.")
- `[S0349]` types=[FORMALIZATION, CONSTRAINT] scope=OBJECT, explicit_date=2026-08-22 — "The proposed ActivateCommissionedFreshSession contract requires the role a fresh session claims to match the authoritative commissioned work-item/role, not merely whatever a prompt happens to assert; any mismatch between prompt-claimed role and the workflow's own commission must STOP rather than self-register." Type signature: domain=(runtime identity, commissioned work item, commissioned role, prompt-claimed role, eligibility), codomain=REGISTER | MISMATCH-STOP, arity=5, total=TOTAL, deterministic=YES. Dependency listed: `unified-runtime-human-binding-invariant` (self-referential). (anchor: "A fresh session may self-bind identity; it may never self-choose role, scope, work item, or authority. ... prompt says Verification + workflow commission says Verification + runtime identity = me + eligibility passes → self-registration ALLOWED; prompt says Architecture / workflow says Verification → MISMATCH → STOP")
- `[S0349]` types=[ANALYSIS, RESTATEMENT] scope=CROSS-OBJECT (co-labeled with `start-gate-refusal-precedent`), explicit_date=2026-08-22 — "The fresh verifier's earlier gate refusal is re-interpreted: it was correct behaviour under the old (self-registration-prohibited) flow, but under the newly proposed flow the same scenario becomes exactly the capability the platform should now add, rather than a defect to keep refusing forever." (anchor: "But under the improved operating model, this is precisely the capability we should add: a genuinely fresh, pre-commissioned session may bind its own discovered runtime identity to its already-authorized role, then enter the normal REGISTER → HANDOFF → human START sequence.")
- `[S0355]` types=[RESTATEMENT, ANALYSIS] scope=CROSS-OBJECT (co-labeled with `activate-commissioned-fresh-session-ast019`), explicit_date=2026-08-23 — "The exact scenario that motivated AST-019's design (a human ordering the Governance adoption review with no pre-existing lane for the reviewer) is now the live case this appointment sets up — showing the newly built capability's success criterion applied to the concrete blocking case that produced it." (anchor: "the capability whose success criterion is verbatim this scenario: the human order 'Start the Governance adoption review of KOS-OPERATING-MODEL-001' must not produce 'no lane therefore refuse'")

## Notes for P3
Row 3 (S0349) lists `unified-runtime-human-binding-invariant` as its own dependency — a self-referential dependency edge, likely a P2a artifact of the contract formalization referring back to the invariant it implements; P3 should not treat this as evidence of a separate label. `family.files_touching` includes S0353, which does not appear among the four rows' source_ids (S0349 x4, S0355 x1) — a minor bookkeeping discrepancy worth checking. This label is co-labeled in the corpus with two other candidate objects not in this batch (`start-gate-refusal-precedent`, `activate-commissioned-fresh-session-ast019`) — likely closely related capability/precedent objects for P3 to cross-reference, though no identity claim is made here. The evidentiary base is strong: two source documents, an explicit correction lineage claim, and two distinct formal type signatures.
