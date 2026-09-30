# compensation-not-erasure-invariant

**Scope(s):** THEORY-LEVEL · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** I_59 · **Aliases:** Compensation != Erasure
**Candidate group membership (NOT an identity claim):** Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 198's invariant that compensating a transition (e.g. Saga-style) must preserve the historical fact that the compensated transition occurred, never rolling back to as-if it never happened.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1416 §"Compensation \neq Erasure. ... Approved \xrightarrow{revoked} Revoked. History remains."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1416. Candidate lifecycle: DORMANT.
Evidence: none recorded (retracted_by/superseded_by empty, contested flag false). The DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the ledger, not a confirmed retirement and not a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1416 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1416 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1416] types=[INVARIANT, EXAMPLE] scope=THEORY-LEVEL — "Compensation is not rollback/erasure: revoking a previously approved decision after discovering a problem does not mean the approval never happened; the correct model is Approved-revoked->Revoked, preserving history -- consistent with Step 197's conservation principle." (anchor: "Compensation \neq Erasure. ... Approved \xrightarrow{revoked} Revoked. History remains.")
- [S1416] types=[INVARIANT] scope=THEORY-LEVEL — "New invariant I_59: compensation must preserve the historical fact that the compensated transition occurred; worked example -- a failed provisioning after an approved architecture must retain ArchitectureApproved/ProvisioningStarted/ProvisioningFailed (and an explicit ApprovalRevoked event if governance requires it) rather than silently flipping approval to false." (anchor: "I_{59}: Compensation must preserve the historical fact that the compensated transition occurred.")

## Notes for P3
(Own observation) Nothing unusual noticed while drafting this file beyond what is already recorded above.
