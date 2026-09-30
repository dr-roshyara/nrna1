# step187-decision-under-uncertainty-and-two-state-machines

**Scope(s):** THEORY-LEVEL · **Row count:** 1 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `D=(DecisionContent,EvidenceState,Authority,Uncertainty,Rationale,Time,Context)`; `DecisionUnderUncertainty=True recorded field; Unknown does not imply NoDecision`; `ES (epistemic state machine) and AS (authority state machine) kept separate, intersecting via DecisionAllowed=F(ES,AS,Rule)`
**Aliases:** "decisions can legitimately occur under uncertainty; epistemic and authority state machines are distinct"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0033, scope THEORY-LEVEL: "Step 187 requires EvidenceCondition for a decision to mean 'EvidenceRequirementSatisfied', not 'CompleteEvidence' -- an authorized decision-maker may legitimately decide Proceed while E(p)=Unknown, with the record explicitly carrying DecisionUnderUncertainty=True, rejecting the implicit assumption Unknown=>NoDecision as false (grounded in Chapter 3 and organizational experience). Expands the decision tuple to D=(DecisionContent,EvidenceState,Authority,Uncertainty,Rationale,Time,Context) -- the decision does not erase uncertainty, it records the decision relative to the uncertainty that existed at the time. Formalizes two separate state machines that must not be merged: an Epistemic state machine ES (Unknown->Observed->Supported->Determined with Conflict/Refuted/Superseded branches) and an Authority state machine AS (None->Granted->Delegated->Expired/Revoked, itself requiring the recursive witness principle for each transition), intersecting only at governance transitions via DecisionAllowed=F(ES,AS,Rule)."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1383 §"EvidenceCondition should not necessarily mean: CompleteEvidence. It may mean: EvidenceRequirementSatisfied. ... DecisionUnderUncertainty=True. ... Unknown ⇒ NoDecision [is false]. ... D = (DecisionContent,EvidenceState,Authority,Uncertainty,Rationale,Time,Context). ... Epistemic state machine ... Authority state machine ... These should not be merged. ... DecisionAllowed = F(ES,AS,Rule)."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1383 §"EvidenceCondition should not necessarily mean: CompleteEvidence. It may mean: EvidenceRequirementSatisfied. ... DecisionUnderUncertainty=True. ... Unknown ⇒ NoDecision [is false]. ... D = (DecisionContent,EvidenceState,Authority,Uncertainty,Rationale,Time,Context). ... Epistemic state machine ... Authority state machine ... These should not be merged. ... DecisionAllowed = F(ES,AS,Rule)."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1383. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S1383), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1383 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1383 |
| dependencies | PRESENT | S1383 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — no row in this label's family is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1383] types=[INVARIANT, FORMALIZATION] scope=THEORY-LEVEL — "Requires EvidenceCondition for a decision to mean EvidenceRequirementSatisfied, not CompleteEvidence -- an authorized decision-maker can legitimately decide Proceed while E(p)=Unknown, with the record explicitly containing DecisionUnderUncertainty=True, rejecting the implicit assumption Unknown=>NoDecision as false. Expands the decision tuple to D=(DecisionContent,EvidenceState,Authority,Uncertainty,Rationale,Time,Context). Formalizes two separate, never-merged state machines: Epistemic (ES: Unknown->Observed->Supported->Determined with Conflict/Refuted/Superseded branches) and Authority (AS: None->Granted->Delegated->Expired/Revoked), intersecting at governance transitions only via DecisionAllowed=F(ES,AS,Rule)." (anchor: "EvidenceCondition should not necessarily mean: CompleteEvidence. It may mean: EvidenceRequirementSatisfied. ... DecisionUnderUncertainty=True. ... Unknown ⇒ NoDecision [is false]. ... D = (DecisionContent,EvidenceState,Authority,Uncertainty,Rationale,Time,Context). ... Epistemic state machine ... Authority state machine ... These should not be merged. ... DecisionAllowed = F(ES,AS,Rule).")

## Notes for P3

- This is my own observation: singleton label (1 row) — evidence base is thin by construction; no internal corroboration is possible from this label alone.
