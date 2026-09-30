# step178-decision-as-constrained-optimization

**Scope(s):** THEORY-LEVEL · **Row count:** 1 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `A_allowed = {a in A | Constraints(a)=true}`, `Authority(a,Action)=false => Action not in A_allowed`, `a* = argmax_{a in A} U(a|K,C,P) subject to Constraints(a)=true` · **Aliases:** `governance as constrained optimization model`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0033`, scope `THEORY-LEVEL`: Step 178 offers a mathematical model of governance decision-making as constrained optimization: a*=argmax_{a in A} U(a|K,C,P) subject to Constraints(a)=true, explicitly not claiming real governance literally runs an optimization algorithm but as a model demonstrating why knowledge K alone cannot determine the action -- different utility functions (U_cost favoring migration vs U_availability favoring postponement) over the same K yield different optimal a*. Formalizes governance constraints as restricting the feasible action set A_allowed = {a in A | Constraints(a)=true} (e.g. MigrationFeasible=true but ProductionChangeAuthorized=false means Migration is excluded from A_allowed), with Authority itself modeled as a constraint (Authority(a,Action)=false => Action not in A_allowed, so Capability alone is not enough). Yields the governance pipeline Knowledge->ApplicablePolicies->Constraints->Alternatives->Evaluation->Decision->Authorization, more rigorous than 'Knowledge->Approval'. Explicitly notes organizations do not always maximize one numerical utility (political considerations, strategic commitments, fairness, legal obligations, ethics, risk appetite may override optimization), so the real architectural invariant is: 'a legitimate decision must be explainable against its applicable governance context', not that it is mathematically optimal.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1371 §"a* = argmax_{a∈A} U(a|K,C,P) subject to: Constraints(a)=true. ... A_allowed = {a∈A | Constraints(a)=true}. ... Authority(a,Action)=false, then: Action ∉ A_allowed. ... Knowledge → ApplicablePolicies → Constraints → Alternatives → Evaluation → Decision → Authorization. ... A legitimate decision must be explainable against its applicable governance context."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1371 §"a* = argmax_{a∈A} U(a|K,C,P) subject to: Constraints(a)=true. ... A_allowed = {a∈A | Constraints(a)=true}. ... Authority(a,Action)=false, then: Action ∉ A_allowed. ... Knowledge → ApplicablePolicies → Constraints → Alternatives → Evaluation → Decision → Authorization. ... A legitimate decision must be explainable against its applicable governance context."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1371. Candidate lifecycle: **DORMANT**. Evidence: no retraction/supersession/contradiction evidence recorded; the DORMANT classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S1371 |
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
Models governance as constrained optimization a*=argmax_{a in A} U(a|K,C,P) subject to Constraints(a)=true, not claiming real governance literally optimizes but demonstrating why K alone cannot fix the action (different utility functions over the same K yield different optima). Formalizes A_allowed={a in A | Constraints(a)=true} with Authority itself as a constraint (Authority(a,Action)=false excludes Action from A_allowed, so Capability alone is insufficient), yielding a richer governance pipeline Knowledge->ApplicablePolicies->Constraints->Alternatives->Evaluation->Decision->Authorization. Notes real organizations do not always maximize a single utility (politics, strategic commitments, fairness, legal/ethical obligations, risk appetite), so the true architectural invariant is 'a legitimate decision must be explainable against its applicable governance context', not mathematical optimality. [S1371]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S1371] types=['FORMALIZATION', 'ANALYSIS'] scope=THEORY-LEVEL — "Models governance as constrained optimization a*=argmax_{a in A} U(a|K,C,P) subject to Constraints(a)=true, not claiming real governance literally optimizes but demonstrating why K alone cannot fix the action (different utility functions over the same K yield different optima). Formalizes A_allowed={a in A | Constraints(a)=true} with Authority itself as a constraint (Authority(a,Action)=false excludes Action from A_allowed, so Capability alone is insufficient), yielding a richer governance pipeline Knowledge->ApplicablePolicies->Constraints->Alternatives->Evaluation->Decision->Authorization. Notes real organizations do not always maximize a single utility (politics, strategic commitments, fairness, legal/ethical obligations, risk appetite), so the true architectural invariant is 'a legitimate decision must be explainable against its applicable governance context', not mathematical optimality." (anchor: "a* = argmax_{a∈A} U(a|K,C,P) subject to: Constraints(a)=true. ... A_allowed = {a∈A | Constraints(a)=true}. ... Authority(a,Action)=false, then: Action ∉ A_allowed. ... Knowledge → ApplicablePolicies → Constraints → Alternatives → Evaluation → Decision → Author…")

## Notes for P3
- Single-source label (n=1 row) — evidence base is thin, no internal cross-corroboration within this capture.
