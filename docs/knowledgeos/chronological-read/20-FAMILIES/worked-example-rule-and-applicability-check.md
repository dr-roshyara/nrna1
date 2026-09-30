# worked-example-rule-and-applicability-check

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Applicable(rho_release,K,Gamma)`, `rho_release` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope OBJECT): The instantiated release rule and its 9-check applicability table.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2786 §"rho_release: PaymentConfirmed(S) and QualityPassed(S) and AddressValid(S) and not ComplianceHold(S) => ReleasePermitted(S). ... Version(rho_release)=v3. ... Applicable(rho_release,K,Gamma) [9-check table] = true."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2786 §"rho_release: PaymentConfirmed(S) and QualityPassed(S) and AddressValid(S) and not ComplianceHold(S) => ReleasePermitted(S). ... Version(rho_release)=v3. ... Applicable(rho_release,K,Gamma) [9-check table] = true."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2786. Candidate lifecycle: ACTIVE.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S2786) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2786 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S2786 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S2786]` types=[EXAMPLE, FORMALIZATION] scope=OBJECT — "21A.10-21A.11: instantiates the release rule as a formal tuple rho_release=<P,C,Cond,Exc,L,A,V,pi> with P={PaymentConfirmed,QualityPassed,AddressValid,¬ComplianceHold}, C=ReleasePermitted, Version=v3, Authority=OperationsPolicy; runs the Applicable(rho_release,K,Gamma) check against a 9-row table (correct shipment, rule active, rule version authorized, four premise-availability checks, temporal validity, compliance exception) all passing, yielding Applicable=true." (anchor: "rho_release: PaymentConfirmed(S) and QualityPassed(S) and AddressValid(S) and not ComplianceHold(S) => ReleasePermitted(S). ... Version(rho_release)=v3. ... Applicable(rho_release,K,Gamma) [9-check table] = true.")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
