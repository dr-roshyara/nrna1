# prioritization-formalization

**Scope(s):** OBJECT · **Row count:** 7 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `PrioritizedPlan=ConstrainedOrder(O,G_D,pi,Gamma)`, `pi(o,S,P,C,Gamma)` · **Aliases:** `Question 22`
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0019, scope OBJECT): The closing question of the phase_measure_theory investigation: how KnowledgeOS orders candidate resolution opportunities (not raw deficiencies) for action, corrected from a compensable weighted-sum scalar to a policy-governed, dependency-graph-constrained ordering over Resolution Opportunity objects, with Finding != Action as the key structural distinction.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0803] §"Priority != Severity Alone ... pi(d) = w_S*S_e(d) + w_U*U_r(d) + w_I*I_m(d) + w_F*F_e(d) + w_D*D_e(d) + w_C*C_o(d) + w_R*R_i(d) ... Order = Sort(Delta_t, pi, descending)"
- CANDIDATE-CONCEPTUAL-BIRTH: [S0803] §"Prioritization should produce an action plan, not merely an ordered list... A finding is not necessarily actionable... O=(Target,Action,ExpectedEffect,Cost,Prerequisites,Context) ... Finding != Action ... Priority(Action) != Priority(Finding)."
- CANDIDATE-FORMAL-BIRTH: [S0803] §"Priority != Severity Alone ... pi(d) = w_S*S_e(d) + w_U*U_r(d) + w_I*I_m(d) + w_F*F_e(d) + w_D*D_e(d) + w_C*C_o(d) + w_R*R_i(d) ... Order = Sort(Delta_t, pi, descending)"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0803. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0803 |
| type_signature | PRESENT | S0803 |
| invariants | PRESENT | S0803 |
| dependencies | PRESENT | S0803 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0803 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S0803 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0803] types=[DEFINITION, FORMALIZATION] scope=OBJECT — "Defines Prioritization as ordering deficiencies by a seven-factor weighted priority function (Severity, Urgency, Impact, Feasibility, Dependencies, Cost, Risk), with five named strategies (Criticality-first, Impact-first, Feasibility-first, Balanced, Policy-driven) and a simple descending sort as the resulting action order." (anchor: "Priority != Severity Alone ... pi(d) = w_S*S_e(d) + w_U*U_r(d) + w_I*I_m(d) + w_F*F_e(d) + w_D*D_e(d) + w_C*C_o(d) + w_R*R_i(d) ... Order = Sort(Delta_t, pi, descending)")
- [S0803] types=[CORRECTION] scope=OBJECT — "Corrects Q22's framing away from prioritizing 'deficiencies' (a category the Q19/Q21 revision had already narrowed to a strict subset of discrepancy), generalizing the priority target to any finding type produced by discrepancy classification (gap, conflict, coherence violation, epistemic uncertainty, acceptable deviation, or no-action-needed)." (anchor: "Following our revised Q19: Discrepancy != Deficiency. Therefore Q22 should not begin with... how do we prioritize which deficiencies to address first... Priority Target in {Gap, Conflict, Finding, Question, Investigation, Action,...}")
- [S0803] types=[CORRECTION, LIMITATION] scope=OBJECT — "Rejects the weighted-sum priority formula as the fundamental definition of priority, since it silently assumes every factor is numerically commensurable and freely compensable (a critical governance violation could be numerically outweighed by high feasibility), demoting the weighted sum to one possible policy implementation of a more general policy-defined priority function." (anchor: "Should SecurityRisk=1.0 be compensable by Feasibility=1.0? Obviously not necessarily. A critical governance violation may have to dominate all other factors. pi = F_policy(factors) is the fundamental formulation. A weighted sum is merely one implementation.")
- [S0803] types=[CORRECTION, DISTINCTION] scope=OBJECT — "Splits two conflated scalar factors into their real components: Dependencies into DependencyBlock (cannot proceed until a prerequisite is resolved) versus DependencyLeverage (resolving this unlocks many others), and Impact into Consequence (cost of inaction), Benefit (gain from resolution), and Leverage (how many downstream decisions become possible) -- three distinct concepts a single 'Impact' scalar had been hiding." (anchor: "Dependency burden (cannot be addressed yet because it depends on another) vs Dependency leverage (addressing this unlocks many others)... DependencyBlock, DependencyLeverage. Impact != Benefit != Leverage.")
- [S0803] types=[CORRECTION, FORMALIZATION] scope=OBJECT — "Replaces the simple descending-sort ordering with a dependency-graph-constrained ordering problem: a hard prerequisite edge d1->d2 must be respected even when the priority score alone would rank d2 above d1, so Order=Sort(Delta,pi) is corrected to PrioritizedPlan=ConstrainedOrder(Opportunities,DependencyGraph,pi,Constraints)." (anchor: "Dependencies require a graph, not just a number... G_D=(V,E) ... pi(d1)>pi(d2) does not imply d1 must be addressed before d2. If d1->d2 then d1 must precede d2 even if pi(d1)<pi(d2). PrioritizedPlan = ConstrainedOrder(O_t,G_D,pi,Gamma).")
- [S0803] types=[EXTENSION, CONCEPT] scope=OBJECT — "Introduces the Resolution Opportunity object O=(Target,Action,ExpectedEffect,Cost,Prerequisites,Context) as the true unit that gets prioritized, since a single finding (e.g. 'evidence for X is insufficient') generates several distinct candidate actions (acquire evidence, ask for clarification, observe, consult another source, defer, accept uncertainty) each with its own priority -- establishing Finding != Action and Priority(Action) != Priority(Finding) as fundamental invariants, and reframing Sarathi as selecting the next action from prioritized resolution opportunities rather than merely 'the highest-priority deficiency'." (anchor: "Prioritization should produce an action plan, not merely an ordered list... A finding is not necessarily actionable... O=(Target,Action,ExpectedEffect,Cost,Prerequisites,Context) ... Finding != Action ... Priority(Action) != Priority(Finding).")
- [S0803] types=[RESTATEMENT, OPEN-QUESTION] scope=THEORY-LEVEL — "Closes the entire phase_measure_theory 22-question investigation with a seven-stage synthesis chain (State->Discrepancy->Finding->ResolutionOpportunity->Priority->Action->NewState) as more fundamental than any specific weighted formula, and defers to a future Question 23: how does KnowledgeOS know when to stop investigating and present a decision-ready state to the Knower." (anchor: "State -> Discrepancy -> Finding -> Resolution Opportunity -> Priority -> Action -> New State. That chain is more fundamental than the weighted priority formula. It also sets up Question 23 - when KnowledgeOS should stop investigating and declare a decision-ready state.")

## Notes for P3
Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.
