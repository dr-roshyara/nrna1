# justification-cycle-acyclicity-invariant

**Scope(s):** THEORY-LEVEL · **Row count:** 2 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `G_justification should be acyclic`
**Aliases:** "circular justification"
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0034, scope THEORY-LEVEL: "Step 194's warning against circular justification (Decision->Claim->Evidence->Decision) and proposed rule that the Justifies-relation graph should be acyclic unless an explicit domain rule permits cycles."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1397 §"Decision \rightarrow Claim \rightarrow Evidence \rightarrow Decision. That can create circular justification."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1397. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S1397), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1397 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | PRESENT | S1397 |
| warnings | PRESENT | S1397 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — no row in this label's family is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1397] types=[WARNING, EXAMPLE] scope=THEORY-LEVEL — "Warns against circular justification (Decision->Claim->Evidence->Decision), e.g. 'we approved the architecture because the architecture was already approved'; the architecture should require provenance to expose such cycles, since a Justifies edge must not silently point to an artifact whose existence depends on the very decision it purports to justify." (anchor: "Decision \rightarrow Claim \rightarrow Evidence \rightarrow Decision. That can create circular justification.")
- [S1397] types=[INVARIANT, CONSTRAINT] scope=THEORY-LEVEL — "Proposes a new consistency rule/candidate invariant: the justification graph G_justification (built from Justifies edges) should be acyclic unless an explicit domain rule permits cycles; cycles are not automatically invalid in all knowledge graphs, but justification cycles need special handling." (anchor: "G_{justification} should be acyclic unless an explicit domain rule permits cycles.")

## Notes for P3

- This is my own observation: nothing unusual noticed beyond what is already recorded above; evidence base is internally consistent for what it covers.
