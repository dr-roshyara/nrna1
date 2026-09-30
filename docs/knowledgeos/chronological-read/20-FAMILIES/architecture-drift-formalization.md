# architecture-drift-formalization

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `D_A = A_observed △ A_declared`, `D_A = ExpectedVariation + UnauthorizedDrift + Unknown` · **Aliases:** `architecture drift vs evolution`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0033, scope OBJECT): Step 158's formalization of architecture drift D_A as the symmetric difference between observed and declared architecture, decomposed into ExpectedVariation+UnauthorizedDrift+Unknown; distinguishes drift from evolution (a registry version change that implementation correctly follows is Evolution, not Drift), requiring the audit to be version-aware.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1349 §"D_A = A_observed △ A_declared ... D_A = ExpectedVariation + UnauthorizedDrift + Unknown ... If Registry v1 says 'Assurance owns Verification' and Registry v2 changes it to 'Assurance owns Determination' then the implementation moving accordingly is Evolution. Not Drift. Therefore the audit must be version-aware."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1349 §"D_A = A_observed △ A_declared ... D_A = ExpectedVariation + UnauthorizedDrift + Unknown ... If Registry v1 says 'Assurance owns Verification' and Registry v2 changes it to 'Assurance owns Determination' then the implementation moving accordingly is Evolution. Not Drift. Therefore the audit must be version-aware."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1349. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S1349) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1349 |
| type_signature | PRESENT | S1349 |
| invariants | PRESENT | S1349 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1349 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S1349]` types=[FORMALIZATION, DISTINCTION] scope=THEORY-LEVEL — "Architecture drift D_A is formalized as the symmetric difference between observed and declared architecture (A_observed triangle A_declared), decomposed as D_A = ExpectedVariation + UnauthorizedDrift + Unknown. A registry version change that the implementation correctly follows is Evolution, not Drift -- so the audit must be version-aware rather than treating every implementation/declaration mismatch as a violation." (anchor: "D_A = A_observed △ A_declared ... D_A = ExpectedVariation + UnauthorizedDrift + Unknown ... If Registry v1 says 'Assurance owns Verification' and Registry v2 changes it to 'Assurance owns Determination' then the implementation moving accordingly is Evolution. Not Drift. Therefore the audit must be version-aware.")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
