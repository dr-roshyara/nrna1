# regression-progression-reasoning-mechanisms

**Scope(s):** OBJECT · **Row count:** 8 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Progress(K,e)`, `R[W] regression`, `Regress(K,r)` · **Aliases:** `backward vs forward transition reasoning`
**Candidate group membership (NOT an identity claim):**
- **G0595** [`progression-strips-formalization` · `regression-progression-reasoning-mechanisms`] — explicit agent-stated uncertainty: 'progression-strips-formalization' POSSIBLY relates to 'regression-progression-reasoning-mechanisms' (batch B0061). Note: Formal progression definition (a set of S_alpha-uniform sentences such that every model of the progressed theory has a same-future-behavior model of the original theory), with progression not always first-order definable (finite progression is second-order definable); STRIPS operators (precondition/delete-list/add-list) formalized as a special case of progression with a correctness theorem equating STRIPS plans to situation-calculus plans for suitable basic action theories.
- **G1806** [`regression-progression-reasoning-mechanisms` · `successor-state-semantics-candidate`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** batch `B0061`, scope `OBJECT`: Two candidate reasoning mechanisms for Eval_c/delta reasoning: regression (reduce a later-situation query to the initial situation) and progression (forward-simulate state change), explicitly NOT identified with Eval_c itself (Reason_S(K,r) -> EvidenceForEvaluation, not Regression=Eval); progression flagged as typically harder than regression, with no universal superiority assumed.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2526 §"R[W] reducing a query about a later situation to a query about the initial situation ... Eval_c \neq ReasoningAlgorithm ... Reason_S(K,r) \rightarrow EvidenceForEvaluation rather than claiming Regression = Eval"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2526 §"R[W] reducing a query about a later situation to a query about the initial situation ... Eval_c \neq ReasoningAlgorithm ... Reason_S(K,r) \rightarrow EvidenceForEvaluation rather than claiming Regression = Eval"]
- CANDIDATE-OPERATIONAL-BIRTH: [S2548 §"class Situation: """A situation IS a history: a finite sequence of actions.  NOT a state.""" ... def successor(self, a, state): """Successor state axiom:  F' = gamma+ ∨ (F ∧ ¬gamma-)""" ... def regress(self, fluent, sit): """REGRESSION: rewrite a query about sit into a query about S0, by unwinding the successor-state axiom backwards.""""]
- CANDIDATE-GOVERNANCE-BIRTH: [S2526 §"DK.1 State and History [DERIVED] ... DK.3 Successor-State Semantics [PROP — EXPERIMENT REQUIRED] ... DK.5 Executable History [PROP] ... DK.6 Transition Composition [DERIVED], semantics of choice and iteration remain [OPEN] ... DK.8 Epistemic Update [PROP] ... DK.9 Temporal and Concurrent Transitions [PROP — REQUIRES TIME/FRAME DECISION]"]

## Lifecycle
last_seen: S2563. Candidate lifecycle: **ACTIVE**. Evidence: no retraction/supersession/contradiction evidence recorded; the ACTIVE classification is a heuristic based on how recently (by source_id ordering) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source(s) |
|---|---|---|
| purpose_rationale | PRESENT | S2552 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2526, S2527 |
| type_signature | PRESENT | S2526, S2563 |
| invariants | PRESENT | S2526, S2548 |
| dependencies | PRESENT | S2527, S2552, S2563 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2526 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2543, S2563 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Analysis: New synthesis: regression and progression serve complementary, not competing, roles for KnowledgeOS -- progression advances the state (satisfying the kernel's 0-read behavior) while history must still be retained to satisfy a separate write obligation established by KR-HISTORY, and regression is what makes that retained history useful for audit/verification rather than merely dutiful storage; P10 (progression sufficient alone) is refuted on exactly this basis. The regression theorem's formal guarantee is unavailable to KnowledgeOS (no basic action theory exists yet) -- only the mechanism's shape transfers. [S2552]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2526] types=['FORMALIZATION', 'CORRECTION'] scope=CROSS-OBJECT — "Regression R[W] is offered as a candidate reasoning mechanism feeding Eval_c, explicitly distinguished as Eval_c != ReasoningAlgorithm: Reason_S(K,r) -> EvidenceForEvaluation, rejecting the identification Regression = Eval." (anchor: "R[W] reducing a query about a later situation to a query about the initial situation ... Eval_c \neq ReasoningAlgorithm ... Reason_S(K,r) \rightarrow EvidenceForEvaluation rather than claiming Regression = Eval")
- [S2526] types=['EXTENSION', 'LIMITATION'] scope=OBJECT — "Introduces two candidate reasoning mechanisms Regress(K,r) and Progress(K,e) with no universal superiority assumed (BackwardReasoning != ForwardSimulation), noting progression's typically higher computational cost, and use-case guidance (query answering favors regression, state evolution favors progression, planning may require both)." (anchor: "progression can be significantly more computationally difficult ... BackwardReasoning \neq ForwardSimulation ... Regress(K,r) and Progress(K,e) with no assumption that one is universally superior ... query answering may favor regression; state evolution natura…")
- [S2526] types=['GOVERNANCE', 'RESTATEMENT'] scope=THEORY-LEVEL — "Proposes a 9-section 'KNOWLEDGEOS — DYNAMIC KNOWLEDGE AND TRANSITION SEMANTICS' research-draft addition (DK.1 State/History [DERIVED], DK.2 Transition Applicability [DERIVED], DK.3 Successor-State Semantics [PROP-EXPERIMENT REQUIRED], DK.4 Boundary/Persistence [DERIVED+PROP], DK.5 Executable History [PROP], DK.6 Transition Composition [DERIVED, choice/iteration OPEN], DK.7 Reasoning Over Transitions [DERIVED], DK.8 Epistemic Update [PROP], DK.9 Temporal/Concurrent Transitions [PROP-REQUIRES TIME/FRAME DECISION]), explicitly not yet called Theory v1.3, with per-section status tags distinct from the KR.1-KR.10 DL-thread tags in S2523/S2524." (anchor: "DK.1 State and History [DERIVED] ... DK.3 Successor-State Semantics [PROP — EXPERIMENT REQUIRED] ... DK.5 Executable History [PROP] ... DK.6 Transition Composition [DERIVED], semantics of choice and iteration remain [OPEN] ... DK.8 Epistemic Update [PROP] ... …")
- [S2527] types=['FORMALIZATION', 'LIMITATION'] scope=OBJECT — "States the formal Regression Theorem (D |= W iff D_S0 ∪ D_una |= R[W], reducing entailment over the full domain axiomatization to entailment over the initial-situation axioms plus unique-names axioms via regression R), and notes progression may not be first-order definable, making it strictly harder than regression -- both retained as [DERIVED] legitimate reasoning mechanisms." (anchor: "R[F(x,do(a,s))] = R[Phi_F(x,a,s)] ... The Regression Theorem: D \models W \iff D_{S_0}\cup D_{una}\models R[W] ... Progression is harder than regression—it may not be first-order definable.")
- [S2543] types=['EXPERIMENTAL-RESULT', 'VALIDATION'] scope=OBJECT — "Executed equivalence check: for 6 action sequences (including empty, single, and multi-fluent-affecting sequences) crossed with 2 fluents, backward regression and forward progression agree on all 12 resulting truth values, confirming the regression theorem holds in this toy basic action theory." (anchor: ""claim": "regression and progression answer the same queries", ... "n_checks": 12, "all_agree": true")
- [S2548] types=['IMPLEMENTATION'] scope=OBJECT — "A minimal but faithful executable model of Reiter's situation calculus: a Situation as an immutable action tuple (explicitly a history, not a state), an ActionTheory with Poss/successor(SSA)/progress/executable/regress methods, used to generate all five A1-A5 experimental results in this batch; module docstring explicitly frames these as verification tests of Reiter's machinery, not a KnowledgeOS implementation, and states nothing here promotes any Reiter concept to a KnowledgeOS primitive." (anchor: "class Situation: """A situation IS a history: a finite sequence of actions.  NOT a state.""" ... def successor(self, a, state): """Successor state axiom:  F' = gamma+ ∨ (F ∧ ¬gamma-)""" ... def regress(self, fluent, sit): """REGRESSION: rewrite a query about s…")
- [S2552] types=['ANALYSIS', 'EXTENSION'] scope=CROSS-OBJECT — "New synthesis: regression and progression serve complementary, not competing, roles for KnowledgeOS -- progression advances the state (satisfying the kernel's 0-read behavior) while history must still be retained to satisfy a separate write obligation established by KR-HISTORY, and regression is what makes that retained history useful for audit/verification rather than merely dutiful storage; P10 (progression sufficient alone) is refuted on exactly this basis. The regression theorem's formal guarantee is unavailable to KnowledgeOS (no basic action theory exists yet) -- only the mechanism's shape transfers." (anchor: "Regression and progression agree on 12/12 checks across 6 action sequences x 2 fluents (A3). Executed, not asserted. ... Progression computes A_{t+1} and discards the history. KR-HISTORY established that the kernel writes history (and reads it 0 times). Progre…")
- [S2563] types=['EXPERIMENTAL-RESULT', 'LIMITATION'] scope=CROSS-OBJECT — "IC-2: regression (R[phi] unwinding the successor-state axioms to S0) is proposed as a verification mechanism, motivated by the corpus finding (KR-HISTORY) that history is written and never read -- regression is what would make it readable for audit. Terminates on regressable queries, deterministic, and A3 confirms regression agrees with progression 12/12. But KnowledgeOS has no basic action theory, so the regression theorem's formal guarantee is unavailable -- only the mechanism's shape transfers. Status: SUPPORTED as the most transferable item, yet still not adoptable." (anchor: "IC-2 Regression as a verification mechanism | Corpus basis: KR-HISTORY: history is written and never read -- regression is what would make it readable for audit ... Current evidence: A3: agrees with progression 12/12 ... Missing evidence: KnowledgeOS has no ba…")

## Notes for P3
- No unusual tensions or evidentiary anomalies were observed for this label within the captured rows.
