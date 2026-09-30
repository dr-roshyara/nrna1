# step172-six-experiments-results

**Scope(s):** THEORY-LEVEL · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Experiments A-F all Pass, Separation of Duties is a policy constraint, not a universal domain invariant, ValidVerifier(v,c) depends on Method,Scope,Risk,Evidence,Policy · **Aliases:** separation-of-duties falsification experiment results

**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- **OBJECT-INDEX**, batch B0033, scope THEORY-LEVEL: Step 172 runs the six Step-171 scenarios (A: one human does everything -- passes, since Valid(A)=f(DomainRisk,GovernancePolicy) and RequiredIndependence=High would invalidate it, yielding 'Separation of Duties is a policy constraint, not a universal domain invariant'; B: AI produces+human verifies/decides -- passes, AI remains a producer of an epistemic artifact, not automatically its authority; C: AI produces AND verifies, human authorizes -- passes, with the refined test ValidVerifier(v,c) depending on Method/Scope/Risk/Evidence/Policy rather than a blanket 'AI verifier = invalid'; D: automated deterministic verification (hash comparison) with governance deciding -- passes and demonstrates Verification!=HumanApproval, VerificationMechanism and DecisionAuthority are independent dimensions; E: emergency execution -- passes only when modeled as EmergencyCondition->EmergencyAuthorization->Execution->RetrospectiveReview rather than an unexplained bypass, i.e. Exception!=AbsenceOfRule, Governance_normal vs Governance_emergency; F: later evidence contradicts earlier knowledge -- passes and is judged the most important experiment). All six pass, but each refines the model rather than simply confirming it.

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1365 §"Producer=Verifier=DecisionMaker=Authorizer=Executor=A may be valid for a sufficiently low-risk domain. However: RequiredIndependence=High would make that configuration invalid. Therefore: Valid(A) = f(DomainRisk,GovernancePolicy). ... Separation of Duties is a policy constraint, not a universal domain invariant."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1365. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction rows found). This DORMANT classification is a heuristic based on how recently (by source_id, last_seen=S1365) this label was last used in the captured contribution set, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1365 |
| dependencies | PRESENT | S1365 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1365 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE

## Assumption register

NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S1365]` types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Experiment A (one human performs every pipeline stage) passes: no architectural violation by itself, since Valid(A)=f(DomainRisk,GovernancePolicy) -- a low-risk domain may legitimately allow Producer=Verifier=DecisionMaker=Authorizer=Executor=A, while RequiredIndependence=High would invalidate the same configuration. Finding: 'Separation of Duties is a policy constraint, not a universal domain invariant.'" (anchor: "Producer=Verifier=DecisionMaker=Authorizer=Executor=A may be valid for a sufficiently low-risk domain. However: RequiredIndependence=High would make that configuration invalid. Therefore: Valid(A) = f(DomainRisk,GovernancePolicy). ... Separation of Duties is a policy constraint, not a universal domain invariant.")
- `[S1365]` types=[EXPERIMENTAL-RESULT, CORRECTION] scope=THEORY-LEVEL — "Experiments C and D pass and correct a would-be over-broad rule: rather than blanket-rejecting AI as a verifier (AI verifier=invalid, called arbitrary), a valid verifier is contextual, ValidVerifier(v,c) depending on Method/Scope/Risk/Evidence/Policy -- an automated deterministic check (e.g. hash comparison) can be the preferred assurance mechanism and can provide verification without exercising governance authority, demonstrating Verification!=HumanApproval and that VerificationMechanism and DecisionAuthority are independent dimensions (a machine can verify while a human/governance body separately decides)." (anchor: "We must not encode: AI verifier = invalid. That would be arbitrary. Instead: ValidVerifier(v,c) depends on: Method, Scope, Risk, Evidence, Policy. ... A deterministic machine check can provide verification without exercising governance authority. ... VerificationMechanism and DecisionAuthority ... are independent dimensions.")
- `[S1365]` types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Experiment E (emergency execution bypassing normal authorization) passes only when modeled as an explicit alternate governed path (EmergencyCondition->EmergencyAuthorization->Execution->RetrospectiveReview), never as an unexplained bypass; emergency governance is 'Governance_emergency', a different governed path from 'Governance_normal', not an absence of governance -- Exception != AbsenceOfRule." (anchor: "EmergencyCondition → EmergencyAuthorization → Execution → RetrospectiveReview. Now the chain remains governed. ... Emergency is therefore not 'no governance'. It is: Governance_normal versus: Governance_emergency. ... Exception ≠ AbsenceOfRule.")

## Notes for P3

All 3 rows trace to a single source document (S1365); the evidentiary base for this label is broad in row count but narrow in provenance (one authoring pass, one document).
