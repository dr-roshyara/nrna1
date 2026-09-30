# four-valued-transition-validity-model

**Scope(s):** THEORY-LEVEL · **Row count:** 2 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `T_tau=(Semantic,Authority,Policy,Execution)` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0034, scope THEORY-LEVEL): Step 204's four-valued transition-validity model distinguishing semantic, authority, policy, and execution validity, showing execution failure never invalidates a preceding decision.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1423] §"Valid(\tau)= SemanticValid(\tau) \land AuthorityValid(\tau) \land PolicyValid(\tau). ... T_\tau=(Semantic, Authority, Policy, Execution)."
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1423] §"Valid(\tau)= SemanticValid(\tau) \land AuthorityValid(\tau) \land PolicyValid(\tau). ... T_\tau=(Semantic, Authority, Policy, Execution)."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1423. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S1423 |
| Type signature | NOT-EVIDENCED-IN-CAPTURE | — |
| Invariants | PRESENT | S1423 |
| Dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| Examples | PRESENT | S1423 |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1423] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "Extends transition validity to a four-valued model (Semantic, Authority, Policy, Execution); e.g. (1,1,1,0) means validly requested/authorized/policy-compliant but execution failed, far more informative than a bare FAILED status which loses the why." (anchor: "Valid(\tau)= SemanticValid(\tau) \land AuthorityValid(\tau) \land PolicyValid(\tau). ... T_\tau=(Semantic, Authority, Policy, Execution).")
- [S1423] types=[INVARIANT] scope=THEORY-LEVEL — "Restates that an approved decision followed by failed execution must preserve Decision.Valid=true alongside Execution.Success=false, or the architecture silently rewrites history." (anchor: "Failure of execution\neq invalidity of preceding decision. ... Decision.Valid=true while: Execution.Success=false.")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
