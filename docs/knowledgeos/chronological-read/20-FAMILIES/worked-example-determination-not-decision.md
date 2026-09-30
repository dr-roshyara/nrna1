# worked-example-determination-not-decision

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Delta_Decision={ManagerApproval(S)}`
**Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- G1047: links this to `worked-example-determination-reached` — working_label token overlap Jaccard=0.50 (shared tokens: ['determination', 'example', 'worked'])
- G1048: links this to `worked-example-evidence-not-yet-determination` — working_label token overlap Jaccard=0.57 (shared tokens: ['determination', 'example', 'not', 'worked'])

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0067, scope OBJECT: "The example's extension with a value-threshold managerial-approval policy showing Determination is reached while Decision/Authorization remain outstanding."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S2786 §"Shipments above 50,000 require managerial approval even when all release conditions are satisfied. ... Determination ⇏ Authorization. ... Release(S) only if ReleasePermitted(S) and ManagerApproval(S). ... Determination != Decision != Authorization."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S2786. Candidate lifecycle: ACTIVE.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). ACTIVE is a heuristic based on how recently (by source_id) this label was last used (last_seen: S2786), not a confirmed retirement or confirmed ongoing status.

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
| semantics | PRESENT | S2786 |
| examples | PRESENT | S2786 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE). `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S2786] types=[EXAMPLE, PRINCIPLE] scope=OBJECT — "21A.18-21A.19: introduces an additional organizational policy (shipments over EUR50,000 require managerial approval regardless of release-condition satisfaction) -- with ShipmentValue(S)=EUR75,000, the release determination stands but ManagerApprovalRequired(S) also holds, so Determination⇏Authorization; the decision layer has alternatives {Release,Hold} with decision rule Release(S) only if ReleasePermitted(S)∧ManagerApproval(S); since ManagerApproval(S) is currently absent, Delta_Decision={ManagerApproval(S)}, so the correct system answer is 'the shipment satisfies the operational release criteria, but the decision contract still requires managerial authorization' rather than a bare 'release it' -- exemplifying Determination≠Decision≠Authorization." (anchor: "Shipments above 50,000 require managerial approval even when all release conditions are satisfied. ... Determination ⇏ Authorization. ... Release(S) only if ReleasePermitted(S) and ManagerApproval(S). ... Determination != Decision != Authorization.")

## Notes for P3

- No internal tension, unknown-candidate marker, or contested-lifecycle discrepancy was observed in this label's own rows; evidentiary base is straightforward for its row count.
