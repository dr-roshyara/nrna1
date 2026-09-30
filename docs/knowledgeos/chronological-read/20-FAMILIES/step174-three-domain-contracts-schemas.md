# step174-three-domain-contracts-schemas

**Scope(s):** THEORY-LEVEL · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** AA=<AuthorizationId,Action,Target,Scope,Constraints,ValidFrom,ValidUntil,AuthorityReference>, DFD=<DeterminationId,Subject,Conclusion,BasisReference,Method,Validity,Version,Timestamp>, OO=<ExecutionId,ObservedState,ObservedAt,Source,EvidenceReference,Result> · **Aliases:** DeterminationForDecision / AuthorizedAction / OutcomeObservation conceptual schemas

**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- **OBJECT-INDEX**, batch B0033, scope THEORY-LEVEL: Step 174 gives the three Step-173 cross-zone semantic contracts concrete (still non-final) conceptual tuple schemas: DeterminationForDecision (DFD) = <DeterminationId,Subject,Conclusion,BasisReference,Method,Validity,Version,Timestamp> where BasisReference lets Governance trust a determination via Governance->EpistemicReference without Governance->EpistemicDatabase; AuthorizedAction (AA) = <AuthorizationId,Action,Target,Scope,Constraints,ValidFrom,ValidUntil,AuthorityReference>, deliberately excluding governance deliberation detail (who discussed it, rejected alternatives, board minutes) since Operational only needs what/target/constraints/authority/period; OutcomeObservation (OO) = <ExecutionId,ObservedState,ObservedAt,Source,EvidenceReference,Result> feeding back into Observation->Evidence->Knowledge'. States the translation law: Governance may decide upon an epistemic result but must not silently redefine it (e.g. Compliant cannot become NonCompliant without an explicit RequestReassessment epistemic process) -- 'much stronger than passing generic objects between services.'

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1367 §"DFD = <DeterminationId, Subject, Conclusion, BasisReference, Method, Validity, Version, Timestamp>. ... Governance → EpistemicReference without: Governance → EpistemicDatabase. ... Governance may decide upon an epistemic result; it must not silently redefine that result. ... RequestReassessment."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1367 §"DFD = <DeterminationId, Subject, Conclusion, BasisReference, Method, Validity, Version, Timestamp>. ... Governance → EpistemicReference without: Governance → EpistemicDatabase. ... Governance may decide upon an epistemic result; it must not silently redefine that result. ... RequestReassessment."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1367. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction rows found). This DORMANT classification is a heuristic based on how recently (by source_id, last_seen=S1367) this label was last used in the captured contribution set, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1367 |
| type_signature | PRESENT | S1367 |
| invariants | PRESENT | S1367 |
| dependencies | PRESENT | S1367 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE

## Assumption register

NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S1367]` types=[FORMALIZATION, INVARIANT] scope=THEORY-LEVEL — "Gives the DeterminationForDecision contract a concrete conceptual schema DFD=<DeterminationId,Subject,Conclusion,BasisReference,Method,Validity,Version,Timestamp>, with BasisReference letting Governance trust a determination via Governance->EpistemicReference rather than Governance->EpistemicDatabase. States the translation law: Governance may decide upon an epistemic result but must not silently redefine it (e.g. it cannot turn Compliant into NonCompliant without triggering an explicit RequestReassessment epistemic process) -- otherwise the bounded-context boundary has failed." (anchor: "DFD = <DeterminationId, Subject, Conclusion, BasisReference, Method, Validity, Version, Timestamp>. ... Governance → EpistemicReference without: Governance → EpistemicDatabase. ... Governance may decide upon an epistemic result; it must not silently redefine that result. ... RequestReassessment.")
- `[S1367]` types=[FORMALIZATION] scope=THEORY-LEVEL — "Gives the AuthorizedAction contract a concrete schema AA=<AuthorizationId,Action,Target,Scope,Constraints,ValidFrom,ValidUntil,AuthorityReference>, deliberately excluding governance-deliberation detail (discussion, rejected alternatives, board minutes) since Operations only needs the action/target/constraints/authority/period. Gives OutcomeObservation the schema OO=<ExecutionId,ObservedState,ObservedAt,Source,EvidenceReference,Result>, feeding the return path into Observation->Evidence->Knowledge'." (anchor: "AA = <AuthorizationId, Action, Target, Scope, Constraints, ValidFrom, ValidUntil, AuthorityReference>. ... Operational should not have to reconstruct: who discussed it; which alternatives were rejected; ... complete board minutes. It needs: What action is authorized, on what target, within what constraints, by which authority, and for what period? ... OO = <ExecutionId, ObservedState, ObservedAt, Source, EvidenceReference, Result>.")

## Notes for P3

All 2 rows trace to a single source document (S1367); the evidentiary base for this label is broad in row count but narrow in provenance (one authoring pass, one document).
