# step178-decision-provenance-and-alternatives

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** AlternativesConsidered, Decision=<Determination,Policy,Objective,Constraints,Authority,Time>, DecisionProvenance != KnowledgeProvenance · **Aliases:** decision structure and considered alternatives

**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- **OBJECT-INDEX**, batch B0033, scope THEORY-LEVEL: Step 178 gives Decision a provenance tuple Decision=<Determination,Policy,Objective,Constraints,Authority,Time>, potentially plus AlternativesConsidered, framed as 'decision structure' not a full transcript -- the semantic requirement is 'preserve enough information to understand why the decision was legitimate at the time' (worked example: knowing that A1=Proceed, A2=Postpone, A3=Reject were Considered and A1 was Rejected because of Risk, before A2 was selected, makes the decision far more intelligible). States DecisionProvenance != KnowledgeProvenance -- both are required, neither substitutes for the other. Also corrects modeling Decision as a bare status field (status=APPROVED); Decision represents an act of organizational determination whose outcome/status is Approved, and Approval != Authorization (Board approval of an architecture does not itself authorize a specific production change), motivating a provisional four-level governance stack Policy -> DecisionRule, Decision -> Approval, Approval -> Authorization, with the exact decomposition left as a later experiment.

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1371 §"Decision = <Determination, Policy, Objective, Constraints, Authority, Time>. Potentially also: AlternativesConsidered. ... Preserve enough information to understand why the decision was legitimate at the time. ... Considered(A1,A2,A3). and perhaps: Rejected(A1) because of: Risk. ... status = APPROVED [rejected as complete concept] ... Approval ≠ Authorization."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1371 §"Decision = <Determination, Policy, Objective, Constraints, Authority, Time>. Potentially also: AlternativesConsidered. ... Preserve enough information to understand why the decision was legitimate at the time. ... Considered(A1,A2,A3). and perhaps: Rejected(A1) because of: Risk. ... status = APPROVED [rejected as complete concept] ... Approval ≠ Authorization."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1371. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction rows found). This DORMANT classification is a heuristic based on how recently (by source_id, last_seen=S1371) this label was last used in the captured contribution set, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1371 |
| type_signature | PRESENT | S1371 |
| invariants | PRESENT | S1371 |
| dependencies | PRESENT | S1371 |
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

- `[S1371]` types=[FORMALIZATION, CORRECTION] scope=THEORY-LEVEL — "Gives Decision a provenance tuple <Determination,Policy,Objective,Constraints,Authority,Time>, potentially plus AlternativesConsidered, framed as 'decision structure' not a transcript, with the requirement to preserve enough information to understand why the decision was legitimate at the time (worked example: recording that A1/A2/A3 were Considered and A1 was Rejected because of Risk before A2 was selected). Corrects modeling Decision as a bare status=APPROVED field -- Decision is an act of organizational determination whose outcome may be Approved, and Approval != Authorization (Board approval of an architecture is not itself authorization for a specific production change), motivating a provisional four-level governance stack Policy->DecisionRule, Decision->Approval, Approval->Authorization, left for later decomposition." (anchor: "Decision = <Determination, Policy, Objective, Constraints, Authority, Time>. Potentially also: AlternativesConsidered. ... Preserve enough information to understand why the decision was legitimate at the time. ... Considered(A1,A2,A3). and perhaps: Rejected(A1) because of: Risk. ... status = APPROVED [rejected as complete concept] ... Approval ≠ Authorization.")

## Notes for P3

Single-row, single-source label — a thin evidentiary base; treat any classification here as provisional.
