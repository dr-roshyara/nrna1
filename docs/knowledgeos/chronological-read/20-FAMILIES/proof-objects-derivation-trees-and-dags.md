# proof-objects-derivation-trees-and-dags

**Scope(s):** OBJECT · **Row count:** 1 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** pi=<Conclusion,Steps,Premises,Rules,Assumptions,Dependencies,Context,Versions,Provenance> · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0067, scope OBJECT): Formal proof-object and typed-proof-step tuples, and the tree/DAG representation of derivations with the cycles-invalid-unless-declared rule.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2785] §"pi = <Conclusion,Steps,Premises,Rules,Assumptions,Dependencies,Context,Versions,Provenance> ... s_i = <Input,Rule,Output,Context,Status>. ... Cycles are invalid for a proof object unless the declared formalism explicitly defines and verifies their semantics."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2785] §"pi = <Conclusion,Steps,Premises,Rules,Assumptions,Dependencies,Context,Versions,Provenance> ... s_i = <Input,Rule,Output,Context,Status>. ... Cycles are invalid for a proof object unless the declared formalism explicitly defines and verifies their semantics."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S2785. Candidate lifecycle: ACTIVE.
Evidence: no retraction/supersession/contradiction evidence recorded; this status is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2785 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
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

- [S2785] types=['FORMALIZATION', 'CONSTRAINT'] scope=OBJECT — "21.22-21.24: formalizes a machine-readable proof object pi=<Conclusion,Steps,Premises,Rules,Assumptions,Dependencies,Context,Versions,Provenance> with each typed proof step s_i=<Input,Rule,Output,Context,Status>; simple derivations are trees (giving premise/rule traceability, dependency visibility, proof checking, impact analysis), but shared premises make a directed acyclic graph D=(V,E) more appropriate, admitting a TopologicalOrder(D) when grounded and acyclic; states the general rule that cycles are invalid for a proof object unless the declared formalism explicitly defines and verifies their semantics (some formalisms permit recursive definitions/cyclic proofs/coinduction/fixed-point semantics)." (anchor: "pi = <Conclusion,Steps,Premises,Rules,Assumptions,Dependencies,Context,Versions,Provenance> ... s_i = <Input,Rule,Output,Context,Status>. ... Cycles are invalid for a proof object unless the declared formalism explicitly defines and verifies their semantics.")

## Notes for P3
- Very thin evidentiary base (row_count=1) — this family file is necessarily short; not evidence the object is unimportant, only that capture so far is sparse.
