# formal-sarathi-decision-algebra

**Scope(s):** OBJECT · **Row count:** 50 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** DecisionModel=(Outcomes,Constraints,Preferences,Utilities,Uncertainty,Policy), S(K,G,D,M,C)->DecisionResult, Sarathi(K,D,DecisionModel)->DecisionResult, d^*=argmax_d EU(d|K) · **Aliases:** Formal Sarathi Algebra, Sarathi Decision Function

**Candidate group membership (NOT an identity claim):**
- **G0157** [`decision-theory-action-selection-sarathi` · `formal-sarathi-decision-algebra`] — explicit agent-stated uncertainty: 'formal-sarathi-decision-algebra' POSSIBLY relates to 'decision-theory-action-selection-sarathi' (batch B0022). Note: S0900's Step 25H: the first fully formal, falsification-tested treatment of Sarathi specifically as a governed decision operator distinct from Lord, contrasted in an explicit Lord-vs-Sarathi comparison table; extends B0021's broader decision-theory-action-selection-sarathi (S0871/Step 15) object.
- **G0165** [`decision-theory-value-of-information-algebra` · `formal-sarathi-decision-algebra`] — explicit agent-stated uncertainty: 'decision-theory-value-of-information-algebra' POSSIBLY relates to 'formal-sarathi-decision-algebra' (batch B0022). Note: S0910's Step 25R: the formal Value-of-Information theory giving Zero and Lord's information-acquisition behavior a rigorous decision-theoretic foundation; extends formal-sarathi-decision-algebra (S0900) and formal-lord-action-selection-algebra (S0899) with EU, tail risk, VOI, regret, and robustness.
- **G0187** [`decision-theory-action-selection-sarathi` · `formal-sarathi-decision-algebra`] — explicit agent-stated uncertainty: 'decision-theory-action-selection-sarathi' POSSIBLY relates to 'formal-sarathi-decision-algebra' (batch B0022). Note: Recurring label across S0898/S0899 for the Lord (action-selection) / Sarathi (action-execution/conversion of selected action into actual behavior) architecture and its end-to-end diagram; closely related to, and possibly identical with, the already-registered formal-lord-action-selection-algebra and formal-sarathi-decision-algebra labels. Added here to satisfy the batch's own label-registration requirement; a future merge review should determine whether this collapses into those two existing objects.
- **G0880** [`decision-theory-action-selection-sarathi` · `formal-sarathi-decision-algebra`] — labels share the alias 'Formal Sarathi Algebra'
- **G1437** [`formal-lord-action-selection-algebra` · `formal-sarathi-decision-algebra`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)

- **PROPOSAL**, batch B0022, scope OBJECT: S0900's Step 25H: the first fully formal, falsification-tested treatment of Sarathi specifically as a governed decision operator distinct from Lord, contrasted in an explicit Lord-vs-Sarathi comparison table; extends B0021's broader decision-theory-action-selection-sarathi (S0871/Step 15) object.

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0900 §"Lord = selects the next useful action ... Sarathi = determines what decision should be made"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0900 §"Stability(d,K) as sensitivity to plausible changes in the knowledge state"]
- CANDIDATE-FORMAL-BIRTH: [S0900 §"Lord: (K,Z,G,C)\rightarrow A ... Sarathi: (K,G,D,C,P)\rightarrow d"]
- CANDIDATE-OPERATIONAL-BIRTH: [S0900 §"Test A: all requirements satisfied and one decision dominates ... S\rightarrow d. PASS"]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S0918. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction rows found). This DORMANT classification is a heuristic based on how recently (by source_id, last_seen=S0918) this label was last used in the captured contribution set, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0900 |
| informal_meaning | PRESENT | S0900, S0918 |
| formal_definition | PRESENT | S0900 |
| type_signature | PRESENT | S0900 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0900 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0900 |
| examples | PRESENT | S0900 |
| warnings | PRESENT | S0900 |
| experiments | PRESENT | S0900 |
| open_questions | PRESENT | S0900, S0909 |

## Rationale

When probability cannot reasonably be established, prohibits inventing it and instead allows qualitative Preference(d1,d2) or Dominates(d1,d2) (e.g. 'if migration is unauthorized, postponement dominates migration' — no probability necessary) [S0900]. Offers a qualitative RiskLevel enum {Low, Medium, High, Critical} as acceptable when defined by the organization's risk framework — 'Risk can be quantitative or qualitative.' [S0900]. States 'perhaps the most important finding in 25H': the system need not always produce a decision, only determine which of DecisionAvailable / DecisionNotComputableFromCurrentKnowledge / HumanDecisionRequired applies — more rigorous than pretending every question has an AI answer [S0900]. Lists Sarathi's computational primitives (constraint satisfaction, rule evaluation, expected utility, preference ordering, Pareto analysis, graph traversal, threshold evaluation, provenance, versioned state) as ordinary operations runnable on a normal workstation even for moderately complex decision models [S0900]. Contrasts vague 'the AI is uncertain' with a structured, itemized 'decision unavailable because...' explanation citing the specific unresolved reasons — 'vastly more useful,' arguing 'AI uncertainty' is too vague a category [S0900]. Strengthens the running computability conclusion ('Yes: the core mathematical architecture is computable on a normal PC ... not merely theoretically'), presented with a 13-row mapping table from each KnowledgeOS concept (Evidence, Provenance, KnowledgeState, Evidence Graph, Contract, Zero, Lord, Sarathi, Governance, LLM, Internet, Human, Observation) to a conventional implementation technology (records/objects, metadata+hashes, versioned database, graph/relational relations, rules+structured data, deterministic evaluation, rule/planning engine, decision engine, policy/rule engine, optional inference service, external connector, external authority, event/input pipeline) — 'engineering-realizable, not dependent on exotic mathematics or hardware.' [S0900].

## Assumption register

NOT-EVIDENCED-IN-CAPTURE

## All rows (grouped into themes; row_count = 50)

This label's 50 rows are condensed into 15 themes below (verified to sum to exactly 50); each bullet is one distinct row/claim, never merged with another — grouping is for readability only, per-row citations are preserved.

### Lord vs Sarathi: the basic distinction (3 rows)

- `[S0900]` types=[DISTINCTION, DEFINITION] — Opens by distinguishing Lord ('selects the next useful action,' e.g. 'perform a restore test') from Sarathi ('determines what decision should be made,' e.g. migrate/postpone/change plan/stop given resulting knowledge).
- `[S0900]` types=[DEFINITION, DISTINCTION] — Defines the decision set D (e.g. {Migrate, Postpone, ChangePlan, Reject}) as distinct from the executable action set A (e.g. a single decision d=Migrate may require several actions {Backup, RestoreTest, FirewallValidation, Approval, Migration}) — Decision ≠ Action.
- `[S0900]` types=[FORMALIZATION, DISTINCTION] — States the precise operator distinction: Lord: (K,Z,G,C)->A (chooses what to do next to advance the process); Sarathi: (K,G,D,C,P)->d (chooses which decision is justified given current knowledge and decision model P).

### Decision reasoning primitives: consequences, utility, expected utility (4 rows)

- `[S0900]` types=[DEFINITION] — Decision reasoning requires knowing Consequences(d) (e.g. for d1=Migrate: Success, Failure, DataLoss, Downtime, Rollback), formalized as requiring an OutcomeModel(d,K).
- `[S0900]` types=[DEFINITION, WARNING] — Introduces a utility function U(o) over outcomes with illustrative example values, explicitly warning these numbers 'must never be invented by the AI and presented as organizational truth.'
- `[S0900]` types=[FORMALIZATION] — Defines expected utility EU(d|K)=sum_o P(o|d,K)U(o) and d*=argmax_d EU(d|K), explicitly subject to governance and safety constraints — 'a mathematically well-defined decision mechanism.'
- `[S0900]` types=[ALTERNATIVE, EXAMPLE] — When probability cannot reasonably be established, prohibits inventing it and instead allows qualitative Preference(d1,d2) or Dominates(d1,d2) (e.g. 'if migration is unauthorized, postponement dominates migration' — no probability necessary).

### Feasibility and hard constraints vs preferences (3 rows)

- `[S0900]` types=[DEFINITION, EXAMPLE] — Before utility calculation, Sarathi must determine Feasible(d,K,C); e.g. Migrate requiring RollbackVerified=True is Feasible=False when RollbackVerified=Unknown, regardless of economic attractiveness.
- `[S0900]` types=[DISTINCTION, PRINCIPLE] — Distinguishes hard constraints (must hold, e.g. ProductionChangeAuthorized) from preferences among permissible decisions (e.g. prefer lower expected downtime): 'Constraints filter; Utility/preferences select.'
- `[S0900]` types=[EXAMPLE, PRINCIPLE] — Worked example showing higher expected utility (Migrate=95 vs Postpone=60) does not matter once Authorized(Migrate)=False eliminates it before optimization — 'Utility cannot override governance.'

### The DecisionModel, auditable DecisionResult, provenance and reproducibility (4 rows)

- `[S0900]` types=[FORMALIZATION, DEFINITION] — Defines a six-component DecisionModel=(Outcomes, Constraints, Preferences, Utilities, Uncertainty, Policy) and Sarathi(K,D,DecisionModel)->DecisionResult.
- `[S0900]` types=[EXAMPLE] — Worked full auditable DecisionResult record for a POSTPONE decision containing reason, blocking requirements, alternative feasibility, evidence basis, decision model version, and authorization status — contrasted with returning a bare 'MIGRATE' label.
- `[S0900]` types=[DEFINITION, PRINCIPLE] — Requires DecisionProvenance(d) tracing K_t+Contract+Policy+Model+Evidence -> d, so 'Decision must be reproducible.'
- `[S0900]` types=[PRINCIPLE, EXAMPLE] — A historical decision (e.g. today's Postpone) must not be rewritten when tomorrow's new evidence would produce Migrate; Decision_t stays tied to K_t, and Decision_{t+1} is separately computed from K_{t+1} — 'Decision is time-indexed.'

### Decision stability under evidence change (3 rows)

- `[S0900]` types=[DEFINITION, CONCEPT] — Defines decision Stability(d,K) conceptually as sensitivity to plausible changes in the knowledge state, motivated by the concern that small evidence changes flipping Migrate<->Postpone indicate an unstable decision.
- `[S0900]` types=[EXAMPLE, WARNING] — Worked example: a decision that barely clears its threshold (P(Success)=0.91 vs threshold 0.90) but could plausibly drop to 0.88 under small uncertainty should be reported as DecisionFragility=High rather than simply 'Migrate.'
- `[S0900]` types=[FORMALIZATION, WARNING] — Formalizes threshold decisions P(Success)>=theta (e.g. theta=0.95: P=0.97=>Migrate, P=0.72=>Postpone), explicitly requiring theta to come from governance/domain policy — 'the AI cannot invent it.'

### Handling missing/unknown probability; absence of evidence is not evidence of failure (2 rows)

- `[S0900]` types=[PRINCIPLE, WARNING] — When P(Success) cannot be estimated, prohibits substituting an arbitrary P=0.5 as 'unjustified'; instead records Probability=Unknown and lets the decision policy handle uncertainty (e.g. Unknown+CriticalRisk -> Postpone).
- `[S0900]` types=[PRINCIPLE, DISTINCTION] — States a named KnowledgeOS principle: Absence of evidence ≠ evidence of failure, distinct from Required evidence absent => decision may be blocked — 'these are different.'

### Risk vs uncertainty; qualitative risk; dominance without full utilities (3 rows)

- `[S0900]` types=[FORMALIZATION, DISTINCTION] — Distinguishes Risk (consequences under uncertainty) from Uncertainty (what we do not know), giving a common quantitative formalization Risk(d)=P(Loss|d,K) x Impact(Loss), qualified as appropriate only where the probabilistic model is justified.
- `[S0900]` types=[ALTERNATIVE, DEFINITION] — Offers a qualitative RiskLevel enum {Low, Medium, High, Critical} as acceptable when defined by the organization's risk framework — 'Risk can be quantitative or qualitative.'
- `[S0900]` types=[FORMALIZATION, EXAMPLE] — Defines decision dominance: if d1 is more expensive, more risky, and no more beneficial than d2, then d2 ⪰ d1 without requiring precise utility numbers — 'valuable when data is incomplete.'

### Multi-objective decisions and the HumanChoiceRequired terminal state (3 rows)

- `[S0900]` types=[FORMALIZATION, EXAMPLE, PRINCIPLE] — For multi-objective enterprise decisions (Cost, Risk, Time, Quality, Compliance, Architecture) that cannot honestly be reduced to one scalar, defines a Pareto set P(D) of non-dominated decisions; Sarathi can report multiple Pareto-optimal decisions requiring organizational preference — 'far better than arbitrary AI selection.'
- `[S0900]` types=[DEFINITION, PRINCIPLE] — Defines the terminal state HumanChoiceRequired, triggered by five listed conditions, explicitly framed as itself 'a computed result,' not a failure.
- `[S0900]` types=[ARGUMENT, PRINCIPLE] — States 'perhaps the most important finding in 25H': the system need not always produce a decision, only determine which of DecisionAvailable / DecisionNotComputableFromCurrentKnowledge / HumanDecisionRequired applies — more rigorous than pretending every question has an AI answer.

### Three Sarathi operating modes; worked scenarios; LLM proposes, Sarathi validates (3 rows)

- `[S0900]` types=[DEFINITION, DISTINCTION] — Defines three Sarathi operating modes: Mode 1 deterministic-rule decision, Mode 2 probability/utility model-based decision, Mode 3 human escalation.
- `[S0900]` types=[EXAMPLE] — Three parallel worked scenarios producing three different Sarathi outcomes (Migrate; Postpone due to blocking unknown rollback; HumanDecisionRequired due to unresolved conflicting policy with no authority precedence) — 'three different outcomes from three different epistemic conditions.'
- `[S0900]` types=[PRINCIPLE] — The LLM may propose CandidateDecisionReasoning, but Sarathi validates it against Contract/Constraints/Evidence/Policy/Authorization; 'the LLM cannot manufacture Utility or Authority.'

### Lord/Sarathi comparison table; the full agent loop, feedback path, architecture diagram (4 rows)

- `[S0900]` types=[DISTINCTION] — Presents a clean eight-row comparison table distinguishing Lord vs Sarathi across Main question, Input, Main concern, Output, Information acquisition, Utility, Governance, and Human escalation — 'this separation is architecturally valuable.'
- `[S0900]` types=[RESTATEMENT, CONCEPT] — Composes the complete agent loop: an inner Knowledge->Zero->Lord->Evidence/Action->Knowledge cycle runs until sufficient knowledge exists, then Knowledge->Sarathi->Decision->Authorization->Execution follows — Lord and Sarathi 'are not competing agents. They form a sequence.'
- `[S0900]` types=[EXTENSION, CONCEPT] — Identifies a feedback path: when Sarathi discovers a decision cannot yet be made, it can hand back to Lord (Sarathi -> KnowledgeGap -> Lord), giving the architecture a genuine feedback loop rather than a one-directional pipeline.
- `[S0900]` types=[CONCEPT, RESTATEMENT] — Presents the full architecture diagram integrating Lord and Sarathi as parallel downstream branches from Knowledge State (Epistemic Contract->Zero->Lord vs Decision Model), converging at Sarathi with three exits (Decide/Escalate/More Knowledge), then Authorization->Execution->WORLD — 'now the loop is almost complete.'

### Computational primitives, optional expensive add-ons, genuinely non-computable decisions (3 rows)

- `[S0900]` types=[ANALYSIS] — Lists Sarathi's computational primitives (constraint satisfaction, rule evaluation, expected utility, preference ordering, Pareto analysis, graph traversal, threshold evaluation, provenance, versioned state) as ordinary operations runnable on a normal workstation even for moderately complex decision models.
- `[S0900]` types=[DISTINCTION, LIMITATION] — Identifies potentially expensive optional add-ons (large-scale Monte Carlo, complex optimization, large causal models) as specialized engines the Sarathi abstraction itself does not require.
- `[S0900]` types=[EXAMPLE, PRINCIPLE] — Worked example of a genuinely non-computable decision: when the utility function depends on an unspecified human value judgment (e.g. accepting a politically sensitive strategic risk), Sarathi should return ValueModelUnderspecified rather than force an answer — 'a correct result.'

### Four reasons for no automatic decision; structured decision-unavailable explanation (2 rows)

- `[S0900]` types=[DISTINCTION, EXTENSION] — Distinguishes four fundamentally different reasons KnowledgeOS may fail to produce an automatic decision: missing knowledge, conflicting knowledge, missing decision model, and irreducible human authority — described as 'the deepest result so far.'
- `[S0900]` types=[EXAMPLE, ARGUMENT] — Contrasts vague 'the AI is uncertain' with a structured, itemized 'decision unavailable because...' explanation citing the specific unresolved reasons — 'vastly more useful,' arguing 'AI uncertainty' is too vague a category.

### The formal S(K,G,D,M,C)->DecisionResult operator and its six falsification tests (8 rows)

- `[S0900]` types=[FORMALIZATION, DEFINITION] — Defines the formal 25H operator S(K,G,D,M,C) -> DecisionResult with a five-value result enum {Decision, HumanDecisionRequired, InsufficientKnowledge, GovernanceBlocked, ModelUnderspecified} — 'computationally explicit.'
- `[S0900]` types=[EXPERIMENT, VALIDATION] — Falsification Test A: when all requirements are satisfied and one decision dominates, S->d — PASS.
- `[S0900]` types=[EXPERIMENT, VALIDATION] — Falsification Test B: blocking knowledge missing yields S->InsufficientKnowledge — PASS.
- `[S0900]` types=[EXPERIMENT, VALIDATION] — Falsification Test C: unresolved governance conflict yields S->GovernanceBlocked — PASS.
- `[S0900]` types=[EXPERIMENT, VALIDATION] — Falsification Test D: two equally valid decisions with no preference yields S->HumanDecisionRequired — PASS.
- `[S0900]` types=[EXPERIMENT, VALIDATION] — Falsification Test E: a necessary but absent utility model yields S->ModelUnderspecified — PASS.
- `[S0900]` types=[EXPERIMENT, VALIDATION] — Falsification Test F: an LLM recommending an unauthorized action does not result in S selecting it — PASS.
- `[S0900]` types=[VALIDATION, DEFINITION] — Step 25H self-verdict: PASS, with a computable definition of Sarathi as the governed decision function evaluating feasible alternatives against knowledge/constraints/consequences/decision model, returning either a defensible decision or an explicit reason automatic decision is not justified.

### Step 25H verdict: Epistemic Control System boundary crossed; next frontier named (3 rows)

- `[S0900]` types=[RESTATEMENT, CONCEPT] — Claims the architecture has crossed a major boundary from 'KnowledgeManagement' to 'Epistemic Control System,' listing ten now-covered capabilities (acquire observations; construct evidence; maintain knowledge; derive requirements; calculate Zero; choose knowledge-gathering actions; evaluate decisions; execute authorized actions; observe resulting world; update knowledge).
- `[S0900]` types=[VALIDATION, ANALYSIS] — Strengthens the running computability conclusion ('Yes: the core mathematical architecture is computable on a normal PC ... not merely theoretically'), presented with a 13-row mapping table from each KnowledgeOS concept (Evidence, Provenance, KnowledgeState, Evidence Graph, Contract, Zero, Lord, Sarathi, Governance, LLM, Internet, Human, Observation) to a conventional implementation technology (records/objects, metadata+hashes, versioned database, graph/relational relations, rules+structured data, deterministic evaluation, rule/planning engine, decision engine, policy/rule engine, optional inference service, external connector, external authority, event/input pipeline) — 'engineering-realizable, not dependent on exotic mathematics or hardware.'
- `[S0900]` types=[OPEN-QUESTION] — Closes by naming the next unsolved frontier — Atma/Knowledge Identity — posing the question of what makes two knowledge objects the same/different/a refinement/mere alternate representations, explicitly invoking 'your earlier distinction between Knowledge Atma and Knower Atma (human)' as now mathematically important, and transitioning to Step 25I: Knowledge Identity Algebra (Document1≡Document2?, LLMOutput≡HumanAssertion?, Observation≡Evidence?, Evidence≡Knowledge?, and 'what is the identity of a Knowledge Atma?').

### Step 25Q/25Z follow-ons: probability-alone insufficiency; Sarathi's specialized role (2 rows)

- `[S0909]` types=[OPEN-QUESTION] — Closes by posing the next problem — probability alone cannot determine whether a risk is acceptable without knowing cost/utility ('probability alone does not determine rational action') — transitioning to Step 25R (Decision Theory, Utility, Risk, Value of Information), introducing VOI(E)=ExpectedUtility(with E)-ExpectedUtility(now) as potentially explaining why Zero->Lord->Sarathi is 'a principled decision-making system,' not merely an AI workflow.
- `[S0918]` types=[CORRECTION, DEFINITION] — Refines Sarathi's role to specialize in Reasoning/Planning/Interpretation/OptionGeneration, with deterministic infrastructure separately verifying six listed properties.

## Notes for P3

This label sits in 5 candidate groups (G0157, G0165, G0187, G0880, G1437) — a busy grouping signal. Per the P2a preface this can reflect genuine relatedness or just a heavily-reused naming/notation convention; worth a priority look in P3 rather than assuming either reading.
