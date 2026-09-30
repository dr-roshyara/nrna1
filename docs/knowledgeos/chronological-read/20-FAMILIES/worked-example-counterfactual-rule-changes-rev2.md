# worked-example-counterfactual-rule-changes-rev2

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** HistoricalDecision != CurrentPolicyCompliance · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope OBJECT): New counterfactual extending the rule-version-change scenario to the decision level, distinguishing the historical decision from current policy compliance.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2787] §"rho_release^v3 to rho_release^v4 [adding] InsuranceVerified(S). ... Proof_v3 is insufficient to establish current compliance with rho_release^v4. The system therefore produces RequiresReevaluation. ... HistoricalDecision != CurrentPolicyCompliance."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S2787] §"rho_release^v3 to rho_release^v4 [adding] InsuranceVerified(S). ... Proof_v3 is insufficient to establish current compliance with rho_release^v4. The system therefore produces RequiresReevaluation. ... HistoricalDecision != CurrentPolicyCompliance."
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2787. Candidate lifecycle: ACTIVE.
Evidence: No retraction/supersession/contradiction evidence recorded. The ACTIVE label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S2787 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2787 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- `[S2787]` types=[EXAMPLE/EXPERIMENT] scope=OBJECT — "21A.29 (new counterfactual, extending S2786's rule-version counterfactual to the decision level): after a v3->v4 rule change adding InsuranceVerified(S), Proof_v3 remains reconstructible but insufficient for current v4 compliance, yielding RequiresReevaluation, while the historical decision Decision_t1(S)=Release stands unchanged and the current policy state may require Reassessment(S) -- giving HistoricalDecision≠CurrentPolicyCompliance, distinct from S2786's determination-level-only version of this counterfactual." (anchor: "rho_release^v3 to rho_release^v4 [adding] InsuranceVerified(S). ... Proof_v3 is insufficient to establish current compliance with rho_release^v4. The system therefore produces RequiresReevaluation. ... HistoricalDecision != CurrentPolicyCompliance.")

## Notes for P3
- Thin evidentiary base (1 row(s) captured) — classification here should be treated as provisional pending further corpus passes.
