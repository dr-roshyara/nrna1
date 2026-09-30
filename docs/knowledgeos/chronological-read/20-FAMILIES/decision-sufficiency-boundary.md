# decision-sufficiency-boundary

**Scope(s):** OBJECT · **Row count:** 3 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `DSB(P,D,C)` · **Aliases:** `Decision Sufficiency Boundary`
**Candidate group membership (NOT an identity claim):**
- **G0112** [`decision-readiness-stop-condition-model` · `decision-sufficiency-boundary`] — explicit agent-stated uncertainty: 'decision-sufficiency-boundary' POSSIBLY relates to 'decision-readiness-stop-condition-model' (batch B0020). Note: The minimum conditions under which a decision may responsibly be made, introduced to correct Q23's over-strong 'all critical values known' sufficiency requirement; DecisionReady ⟺ K_t ⊨ DSB(P,D,C).

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0020, scope OBJECT): The minimum conditions under which a decision may responsibly be made, introduced to correct Q23's over-strong 'all critical values known' sufficiency requirement; DecisionReady ⟺ K_t ⊨ DSB(P,D,C).

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0804] §"DSB(P,D,C)"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0804] §"DSB(P,D,C)"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0804. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | PRESENT | S0804 |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S0804 |
| Type signature | PRESENT | S0804 |
| Invariants | PRESENT | S0804 |
| Dependencies | PRESENT | S0804 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S0804 |
| Examples | PRESENT | S0804 |
| Warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| Experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
The Arjuna example is corrected: readiness must be asked 'sufficient for which decision?' Decision A ('Who is on the opposing side?') can become ready quickly; Decision B ('Should I fight?') requires much richer understanding; Decision C ('What is the morally correct action?') requires another Ideal Knowledge/Understanding model. Establishes Ready(S_t,D_1) ⇏ Ready(S_t,D_2) as a fundamental property of the theory [S0804].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0804] types=[FORMALIZATION, CORRECTION] scope=OBJECT — "A revised, decision-relative definition: DecisionReady(S_t,P) ⟺ MeetsDecisionCriteria(S_t,P). Sufficiency is not intrinsic to knowledge — Sufficient(K_t,P_1) ≠ Sufficient(K_t,P_2) in general — so sufficiency should be made explicit in a decision D: Sufficient(S_t,P,D). The Ideal State (I^K_t, I^U_t, I^D_t) should define a minimum, not perfection; otherwise investigation becomes potentially infinite. Introduces a Decision Sufficiency Boundary DSB(P,D,C): the minimum conditions under which a decision may responsibly be made, giving DecisionReady ⟺ K_t ⊨ DSB(P,D,C)." (anchor: "DSB(P,D,C)")
- [S0804] types=[EXAMPLE, ARGUMENT] scope=THEORY-LEVEL — "The Arjuna example is corrected: readiness must be asked 'sufficient for which decision?' Decision A ('Who is on the opposing side?') can become ready quickly; Decision B ('Should I fight?') requires much richer understanding; Decision C ('What is the morally correct action?') requires another Ideal Knowledge/Understanding model. Establishes Ready(S_t,D_1) ⇏ Ready(S_t,D_2) as a fundamental property of the theory." (anchor: "Ready(S_t,D_1) \not\Rightarrow Ready(S_t,D_2)")
- [S0804] types=[RESTATEMENT, CORRECTION] scope=THEORY-LEVEL — "Final recommended definition: 'A decision-ready state is a KnowledgeOS state in which the knowledge and understanding relevant to a specific decision satisfy the decision's mandatory epistemic, coherence, contextual, and domain constraints, while all remaining deficiencies are either decision-irrelevant or explicitly acceptable under the governing policy.' Formalized as DecisionReady(S_t,D,P,C) ⟺ HardGates(S_t,D,P,C) ∧ AcceptableRemainingUncertainty(S_t,D,P,C). Also states StopInvestigation ≠ DecisionReady as a separate operational decision." (anchor: "I would replace the current definition with")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
