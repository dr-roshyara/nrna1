# epistemic-resource-allocation-attention-scheduling-portfolio-optimization-algebra

**Scope(s):** OBJECT · **Row count:** 74 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** DWC(u)=sum_d Criticality(d)*Impact(u,d), EP=(U,D,I,R,C,V), P(u)=(Impact,Risk,VOI,Urgency,Dependency,Freshness,Cost,Authority), Synergy(I1,I2)=Value(I1,I2)-Value(I1)-Value(I2) · **Aliases:** Epistemic Resource Allocation, Attention Scheduling, Knowledge Triage and Portfolio Optimization
**Candidate group membership (NOT an identity claim):**
- G0180: [`epistemic-resource-allocation-attention-scheduling-portfolio-optimization-algebra` · `information-acquisition-value-of-information-active-learning-algebra`] — explicit agent-stated uncertainty: 'epistemic-resource-allocation-attention-scheduling-portfolio-optimization-algebra' POSSIBLY relates to 'information-acquisition-value-of-information-active-learning-algebra' (batch B0022). Note: S0929's Step 35: generalizes the single-decision Next-Best-Epistemic-Action into an organization-wide epistemic portfolio optimization across many competing uncertainties, introducing epistemic debt, bottleneck value, synergy/redundancy, Pareto frontiers, human-expert scheduling, and a four-level (Claim/Investigation/Decision/Portfolio) optimization hierarchy, plus an explicit anti-Goodhart's-Law objective correction.
- G1440: [`epistemic-resource-allocation-attention-scheduling-portfolio-optimization-algebra` · `information-acquisition-value-of-information-active-learning-algebra`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus
- G1442: [`adversarial-epistemology-integrity-trust-manipulation-algebra` · `epistemic-resource-allocation-attention-scheduling-portfolio-optimization-algebra`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- G1446: [`cross-context-consistency-contradiction-reconciliation-authority-algebra` · `epistemic-resource-allocation-attention-scheduling-portfolio-optimization-algebra`] — labels co-occur in the same contribution's labels[] 2 separate times across the corpus
- G1448: [`epistemic-resource-allocation-attention-scheduling-portfolio-optimization-algebra` · `epistemic-sufficiency-decision-preconditions-assurance-composition-algebra`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus

## Sources (how this label entered the ledger)
- PROPOSAL · batch B0022 · scope OBJECT: S0929's Step 35: generalizes the single-decision Next-Best-Epistemic-Action into an organization-wide epistemic portfolio optimization across many competing uncertainties, introducing epistemic debt, bottleneck value, synergy/redundancy, Pareto frontiers, human-expert scheduling, and a four-level (Claim/Investigation/Decision/Portfolio) optimization hierarchy, plus an explicit anti-Goodhart's-Law objective correction.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0929 §"An enterprise does not have only one uncertainty ... U_1,...,U_n ... resources are finite ... How should KnowledgeOS allocate limited epistemic resources across competing uncertainties and decisions?"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0929 §"mathcal U={u_1,...,u_n} unresolved knowledge problems ... mathcal R={r_1,...,r_m} available resources ... Allocate(mathcal R,mathcal U) to maximize decision quality"]
- CANDIDATE-OPERATIONAL-BIRTH: [S0929 §"Experiments 1-6: high-VOI/high-risk forbidden by threshold, moderate-VOI/low-risk selected instead PASS; two identical-evidence investigations->RedundancyDetected PASS; uncertainty affecting five critical decisions weighted over one trivial-decision uncertainty PASS; cheap investigation unlocking th"]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0936. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction evidence recorded. This heuristic status (DORMANT) is based only on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0929 |
| informal_meaning | PRESENT | S0929, S0934 |
| formal_definition | PRESENT | S0929, S0934 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0929, S0931, S0933, S0934, S0936 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0929, S0931, S0936 |
| examples | PRESENT | S0929 |
| warnings | PRESENT | S0929 |
| experiments | PRESENT | S0929 |
| open_questions | PRESENT | S0929 |

## Rationale
Analogizes epistemic debt to technical debt and formalizes it as deferred knowledge work with future decision consequences [S0929]. States portfolio selection can be NP-hard in general, requiring approximation/heuristics at scale, while remaining feasible on ordinary hardware for smaller cases [S0929]. Analyzes computational feasibility, concluding the architecture does not inherently require a supercomputer given standard approximation/caching/heuristic techniques [S0929].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
Rows 0-65 originate from S0929 (Step 35, the main formalization); rows 66-73 are later reuse/extension across S0931 (adversarial robustness), S0933 (identity resolution), S0934 (conflict triage), and S0936 (evidence-assurance budgeting). Grouped below into 11 content themes in reading order.

### Theme: Framing the allocation problem; rejecting scalar collapse (8 rows condensed; source_ids: S0929)
Generalizes NextBestEpistemicAction (a single decision) to organization-wide allocation across many competing uncertainties, formalizes the central problem Allocate(R,U), rejects simple High/Medium/Low prioritization once cost varies dramatically, defines epistemic utility and a first (insufficient) cost-efficiency ratio, formalizes a multi-dimensional priority function while warning against premature scalar collapse (Score != Explanation), defines the epistemic priority vector as the preserved representation, and states decision criticality is domain/governance-owned information, not KnowledgeOS-decided.
Representative: [S0929] types=['OPEN-QUESTION', 'EXTENSION'] (anchor: "An enterprise does not have only one uncertainty ... U_1,...,U_n ... resources are finite ... How should KnowledgeOS allocate limited epistemic resources across competing uncertainties and decisions?")

### Theme: Uncertainty impact, dependency centrality, urgency, freshness (7 rows condensed; source_ids: S0929)
Defines uncertainty impact on a specific decision (worked via a backup-restorability example) and dependency centrality of an uncertainty influencing multiple decisions, warns raw graph-theoretic centrality is not automatically epistemic importance, formalizes decision-weighted centrality combining criticality and per-decision impact, and defines urgency (time-dependent, sharply rising near deadlines) and knowledge freshness/half-life as domain-specific (fast-decaying infrastructure state vs. slow-decaying architectural principles).
Representative: [S0929] types=['DEFINITION', 'EXAMPLE'] (anchor: "Impact(u,d) ... unknown backup restorability may have Impact(u_1,d_migration)=High")

### Theme: Epistemic debt and bottlenecks (7 rows condensed; source_ids: S0929)
Introduces Epistemic Debt as accumulated unresolved uncertainty impairing future decisions (five worked examples), analogizes it to technical debt and formalizes it as deferred knowledge work, proposes a conceptual (explicitly non-universal) debt-accumulation recurrence, states unresolved uncertainty can compound once embedded as an assumption downstream, and defines an epistemic bottleneck and its formalized bottleneck value (which can matter more than raw uncertainty magnitude), worked through a four-downstream-activity-class example.
Representative: [S0929] types=['DEFINITION', 'EXAMPLE'] (anchor: "EpistemicDebt is accumulated unresolved uncertainty that can impair future decisions ... undocumented infrastructure; stale architecture decisions; unverified assumptions; unresolved conflicting requirements; missing provenance")

### Theme: Portfolio selection as constrained combinatorial optimization (9 rows condensed; source_ids: S0929)
Introduces multi-resource constraints (Budget/HumanHours/Compute/Time) forming a constrained optimization problem, formalizes basic epistemic portfolio selection as 0/1-knapsack-like, notes NP-hardness in general requiring heuristics at scale while remaining feasible on ordinary hardware for realistic sizes, distinguishes exact from heuristic/greedy optimization, formalizes a greedy allocation heuristic and its non-global-optimality, gives a worked counterexample where greedy allocation misses a complementary (synergistic) investigation pair, and defines information synergy and its negative form, redundancy (common for same-source investigations), concluding portfolio optimization needs dependency information chaining provenance/dependency graphs into redundancy/synergy detection.
Representative: [S0929] types=['FORMALIZATION'] (anchor: "Budget<=B, HumanHours<=H, Compute<=C, Time<=T. We now have a constrained optimization problem")

### Theme: Human-expert scheduling and judgment provenance (7 rows condensed; source_ids: S0929)
Defines a skill-match requirement for human-expert scheduling and expertise as an admissibility constraint (not mere availability), requires human review be represented as a structured epistemic event rather than a boolean approval flag, defines required human-judgment provenance fields for auditability, requires preserving expert disagreement rather than averaging conflicting judgments, formalizes expert reliability as domain/task-specific (warning against over-generalizing it), and rejects reducing expertise to a single permanent score.
Representative: [S0929] types=['DEFINITION', 'EXAMPLE'] (anchor: "Expert_A infrastructure, Expert_B governance. I_1 requires A. I_2 requires B. We therefore need SkillMatch(I,Expert)")

### Theme: Multi-resource cost, Pareto frontier, general portfolio objective (7 rows condensed; source_ids: S0929)
Gives a worked compute-cost-driven allocation-preference example, formalizes multi-resource cost as a vector quantity, defines Pareto dominance between investigations over vector costs and the epistemic Pareto frontier as a pre-filter before expensive optimization, formalizes the general portfolio objective (accounting for interaction, redundancy, decision impact and risk reduction), extends the static portfolio to dynamic re-optimization after each new observation, and proposes a receding-horizon strategy as a practical alternative to full-future prediction.
Representative: [S0929] types=['EXAMPLE'] (anchor: "M_1 requires 1s, M_2 requires 10h. If both have similar decision value, M_1 wins on resource efficiency")

### Theme: Scheduling loop, stopping condition, governance-owned constraints (8 rows condensed; source_ids: S0929)
Presents a thirteen-stage epistemic scheduling loop as the operational heart of an active KnowledgeOS, defines the portfolio-level stopping condition, gives a worked example/principle that organizational/governance constraints can override pure VOI-optimal selection, distinguishes hard constraints from soft optimization objectives, states governance/domain ownership of constraint definition (KnowledgeOS only evaluates satisfaction, preserving DDD boundaries), reframes the optimization function to include decisions/constraints/resources (not knowledge alone), defines an organizational risk-budget constraint, and distinguishes valuable from merely admissible investigations via that risk budget.
Representative: [S0929] types=['RESTATEMENT'] (anchor: "Current Knowledge -> Identify Uncertainty -> Identify Decisions -> Calculate Impact -> Generate Investigations -> Estimate VOI/Cost/Risk -> Build Candidate Portfolio -> Apply Resource Constraints -> Select Next Action(s) -> Execute/Authorize -> Acquire Evidence -> Update Knowledge -> Recalculate ...")

### Theme: Falsification tests and Step 35 verdict (3 rows condensed; source_ids: S0929)
Runs falsification tests 1-6 (all PASS: a risk-inadmissible high-VOI investigation is excluded in favor of a moderate-VOI/low-risk one; identical-evidence investigations trigger RedundancyDetection; and others), runs falsification tests 7-12 (six PASS, two marked "PASS conceptually": stale prioritization is recalculated downward on new evidence; urgency correctly elevates near-deadline items; and others), and records the Step 35 self-verdict PASS, extending KnowledgeOS into a potential epistemic resource allocator.
Representative: [S0929] types=['EXPERIMENT', 'VALIDATION'] (anchor: "Experiments 1-6: high-VOI/high-risk forbidden by threshold, moderate-VOI/low-risk selected instead PASS; two identical-evidence investigations->RedundancyDetected PASS; uncertainty affecting five critical decisions weighted over one trivial-decision uncertainty PASS; cheap investigation unlocking th")

### Theme: Formal EpistemicPortfolio object, control architecture, and corrected objective (10 rows condensed; source_ids: S0929)
Formally defines the EpistemicPortfolio object EP=(U,D,I,R,C,V) and its constrained optimization problem (called a major mathematical result), formalizes a four-level optimization hierarchy (claim validation / investigation selection / decision-making / portfolio-level allocation), presents a seven-stage closed epistemic control-system architecture, warns of an anti-gaming failure mode where the system optimizes for easy rather than important information, states the corrected optimization objective privileging decision quality over raw information volume, warns of a Goodhart's-Law-style failure if a proxy metric (claim count, investigation count) is optimized instead of the true objective, states the corrected ultimate objective replacing "maximize knowledge," states the philosophical result that the system's epistemic objective is sufficiency (not omniscience), records seven boxed Step 35 principles, and analyzes computational feasibility (no inherent supercomputer requirement given standard approximation/caching/heuristics).
Representative: [S0929] types=['FORMALIZATION', 'DEFINITION'] (anchor: "EpistemicPortfolio EP=(U,D,I,R,C,V) where U=unresolved uncertainties, D=affected decisions, I=candidate information actions, R=available resources, C=constraints, V=value model ... max_{S subseteq I} Value(S) subject to Resource(S)<=R and Constraints(S)=True")

### Theme: Adversarial robustness follow-up (S0931) (2 rows condensed; source_ids: S0931)
Restates Goodhart's Law and requires distinguishing proxy metrics from actual objectives (directly connecting back to Step 35), and presents a further-updated end-to-end architecture diagram surrounded by an explicit AdversarialEnvironment box affecting every layer.
Representative: [S0931] types=['PRINCIPLE'] (anchor: "If a metric becomes a target Metric->Target, participants may optimize the metric rather than the underlying objective ... distinguish proxy metrics from the actual objective. This directly connects with Step 35")

### Theme: Downstream reuse: identity resolution, conflict triage, evidence-assurance budgeting (6 rows condensed; source_ids: S0933, S0934, S0936)
Shows Step 35's resource-allocation/decision-theoretic reasoning being directly reused elsewhere: a four-option (Confirm/Reject/Investigate/Abstain) identity-resolution decision via expected loss (S0933); conflict-triage priority formalized by directly reusing Step 35's prioritization, plus a Conflict Debt analogue of epistemic debt with its own conceptual accumulation recurrence (S0934); and, reusing the resource-allocation framing again, why evidence-set minimality matters, an assurance budget framing evidence acquisition as resource allocation, and a unification of Steps 34/35/41 into one Acquire->Allocate->Stop epistemic-action cycle (S0936).
Representative: [S0933] types=['EXTENSION'] (anchor: "choose among Confirm, Reject, Investigate, Abstain. based on expected loss. This is another direct application of Steps 34 and 35")

(Full text of all 74 rows is in 03-CONTRIBUTIONS.jsonl. Theme boundaries above are content-based and verified to sum to the full row_count.)

## Notes for P3
Internally coherent single-arc label (Step 35 core plus four short downstream reuse episodes, all consistent extensions rather than revisions). The label's own content already performs several internal self-corrections (the "corrected optimization objective" and "corrected ultimate objective" in the ninth theme visibly revise earlier framing within the same source S0929) -- this is the source material's own iterative refinement, not a cross-row contradiction, and is noted here so P3 does not mistake it for CONTESTED lifecycle evidence. The five-way group membership (G0180, G1440, G1442, G1446, G1448) suggests this label sits at a hub connecting resource-allocation, conflict-debt, and cross-context-reconciliation labels in the same batch (e.g. cross-context-consistency-contradiction-reconciliation-authority-algebra shares G1446) -- worth priority attention in P3 given how much downstream reuse (S0931/S0933/S0934/S0936) this label's rows 66-73 already document.
