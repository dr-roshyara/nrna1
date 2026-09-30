# causal-identification-gate

**Scope(s):** OBJECT · **Row count:** 9 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `CausalIdentificationAssessment`, `[CAUSAL-INFERENCE-GATE]` · **Aliases:** `Identification Gate`
**Candidate group membership (NOT an identity claim):**
- **G0210**: candidate group with `kos-causal-identifiability-invariants` — explicit agent-stated uncertainty: 'kos-causal-identifiability-invariants' POSSIBLY relates to 'causal-identification-gate' (batch B0023). Note: Step 63's new contribution beyond the Step 43 causal-reasoning-assurance-layer re-derivation: the NotIdentified-vs-Unknown epistemic-state distinction, ComputationalDifficulty≠EpistemicNonIdentifiability, the seven-item non-conflatable epistemic-object taxonomy, and four new named causal invariants (I_Causal, I_Intervention, I_Counterfactual, I_Identification) plus the causal provenance chain. (mechanical signal only; relationship not yet decided, P3).
- **G1454**: candidate group with `causal-reasoning-assurance-layer` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus (mechanical signal only; relationship not yet decided, P3).



## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0012, scope OBJECT): Proposed AI-assurance gate/assessment checking mechanism/confounders/rival pathways/research-design adequacy before a causal claim is allowed, yielding IDENTIFIED/PARTIALLY_IDENTIFIED/NOT_IDENTIFIED/UNKNOWN, else BLOCK or DOWNGRADE CLAIM to an associational statement.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0446] §"[CAUSAL-INFERENCE-GATE] ... If the answers aren't available: BLOCK or DOWNGRADE CLAIM to 'X is associated with Y in the observed data.'"
- CANDIDATE-CONCEPTUAL-BIRTH: [S0938] §"Confounding example: architecture review, incidents, and team quality"
- CANDIDATE-FORMAL-BIRTH: [S0446] §"causal relationships cannot simply be inferred by running regressions without substantial prior knowledge about the mechanisms generating the data, and that few causal pathways can be excluded a priori."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S0446] §"[CAUSAL-INFERENCE-GATE] ... If the answers aren't available: BLOCK or DOWNGRADE CLAIM to 'X is associated with Y in the observed data.'"

## Lifecycle
last_seen: S0960. Candidate lifecycle: DORMANT. Evidence: none recorded (no retraction/supersession/contradiction signal) — this lifecycle label is a heuristic based on how recently (by source_id) this label was last used, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source ids |
|---|---|---|
| Purpose / rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| Informal meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| Formal definition | PRESENT | S0446, S0449, S0938 |
| Type signature | PRESENT | S0446 |
| Invariants | PRESENT | S0446, S0938 |
| Dependencies | PRESENT | S0446, S0449, S0960 |
| Assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| Semantics | PRESENT | S0449 |
| Examples | PRESENT | S0938 |
| Warnings | PRESENT | S0446, S0938 |
| Experiments | PRESENT | S0938, S0960 |
| Open questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0446] types=[GOVERNANCE, CONSTRAINT] scope=CROSS-OBJECT — "Proposed AI Engineering Platform guard: a [CAUSAL-INFERENCE-GATE] assurance check asks nine questions (research design, mechanism, assumptions, confounders, rival explanations, identifiability, observational-vs-experimental data, validation, scope) before allowing a causal claim like 'Regression analysis confirms that X causes Y'; missing answers trigger BLOCK or DOWNGRADE to an associational claim, framed as 'an excellent deterministic assurance rule.'" (anchor: "[CAUSAL-INFERENCE-GATE] ... If the answers aren't available: BLOCK or DOWNGRADE CLAIM to 'X is associated with Y in the observed data.'")
- [S0446] types=[FORMALIZATION] scope=OBJECT — "CausalIdentificationAssessment decision flow: Question -> Is causal inference requested? -> NO: ordinary evidence assessment; YES -> Mechanism specified? -> Confounders addressed? -> Rival pathways excluded? -> Research design adequate? -> Identification established?, yielding IDENTIFIED/PARTIALLY_IDENTIFIED/NOT_IDENTIFIED/UNKNOWN, attributed to the book's Chapter 15." (anchor: "causal relationships cannot simply be inferred by running regressions without substantial prior knowledge about the mechanisms generating the data, and that few causal pathways can be excluded a priori.")
- [S0446] types=[WARNING, CONSTRAINT] scope=CROSS-OBJECT — "KnowledgeOS should enforce a semantic distinction among ASSOCIATION/CORRELATION/TEMPORAL_ASSOCIATION/MECHANISTIC_INFERENCE/CAUSAL_CLAIM, with promotion between levels requiring evidence, to prevent an LLM from inventing causality where only co-occurrence was observed." (anchor: "LLMs are naturally prone to language such as 'A leads to B.' when the evidence only supports 'A and B were observed together.'")
- [S0446] types=[INVARIANT] scope=THEORY-LEVEL — "Invariant 4: A statistical association does not establish causation." (anchor: "A statistical association does not establish causation.")
- [S0449] types=[DISTINCTION, FORMALIZATION] scope=OBJECT — "Identification Lens (called 'the single most important architectural contribution'): distinguishes identification from estimation; a claim flows Observed evidence -> Can the claim be identified? -> YES: Estimate / NO: ABSTAIN -> IDENTIFIABILITY -> ESTIMATION -> UNCERTAINTY -> ROBUSTNESS -> EPISTEMIC STATUS, explicitly contrasted with 'model produced p<0.05 -> therefore knowledge.'" (anchor: "An effect is identifiable only when the assumptions imply a single value of the causal effect compatible with the observed data. Otherwise multiple causal effects remain compatible with the same observations.")
- [S0938] types=[EXAMPLE, CONCEPT] scope=OBJECT — "Worked example: P(Incident|Review) < P(Incident|NoReview) tempts the conclusion Review->FewerIncidents, but HighQualityTeams may cause both Review and FewerIncidents, making team quality a confounder; depicted as a causal graph Team Quality -> {Review, Incident}, Review -> Incident. The observed association between Review and Incident may therefore not equal the causal effect of Review." (anchor: "Confounding example: architecture review, incidents, and team quality")
- [S0938] types=[FORMALIZATION, WARNING] scope=OBJECT — "Formalizes a confounder Z as a variable affecting both A and Y (graph Z->A, Z->Y), noting the observed association may be biased. Gives the adjustment formula P(Y|do(A=a)) = sum_z P(Y|A=a,Z=z) P(Z=z) as one way to estimate the interventional quantity 'if appropriate assumptions hold', explicitly warning this formula is not universally valid -- the adjustment set must satisfy causal assumptions. States the rule: a statistical correlation must not automatically become a causal edge." (anchor: "Confounder Z formalized; backdoor-style adjustment formula with non-universal-validity warning")
- [S0938] types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Runs twelve falsification tests, all PASS: (1) A precedes B => ObservedSequence(A,B) recorded but not automatically Cause(A,B); (2) a third variable explains both action and outcome => confounding detected; (3) two causal models fit observations equally => both retained; (4) an intervention distinguishes competing models => model probabilities/validity updated from the intervention outcome; (5) a causal claim based only on correlation => remains a CausalHypothesis, not validated causation; (6) a causal effect exists for one subgroup but not another => heterogeneous effect represented; (7) a causal model's key assumption becomes false => dependent causal claims re-evaluated; (8) an outcome contradicts the predicted causal effect => model error recorded, history not rewritten; (9) ten agents infer the same causal relationship from the same source => not treated as ten independent confirmations; (10) a causal claim applies only to production systems => not automatically generalized to test environments; (11) a counterfactual predicts an unobserved action's result => marked model-derived, not observed fact; (12) a randomized intervention demonstrates a causal effect => causal assurance stronger than purely observational association, with study limitations still recorded." (anchor: "Twelve falsification experiments for Step 43 causal-reasoning model (all PASS)")
- [S0960] types=[EXPERIMENTAL-RESULT] scope=THEORY-LEVEL — "Runs fourteen experiments, all PASS: (1) Corr(X,Y)>0 does not license AI concluding X->Y -- Rejected; (2) an RCT's E[Y|X=1]-E[Y|X=0] can estimate ATE under the experiment's assumptions -- Accepted; (3) an AI-produced unsupported counterfactual Y(0)=6 with no causal model support -- UnsupportedCounterfactual; (4) an agent interpreting Observed(X=1) as Intervention(X=1) -- Rejected; (5) omitted-confounder demonstration: P(Y|X) vs Z-adjusted estimate differ materially, demonstrating confounding; (6) an ambiguous causal claim ('training improves performance') without population/metric/horizon/comparator specification remains underspecified; (7) an ATE estimated from population P1 automatically applied to P2 triggers a GeneralizationWarning; (8) X always preceding Y does not license X->Y -- InsufficientCausalEvidence; (9) a mediation structure X->M->Y is preserved with total/direct/indirect effects distinguished rather than collapsed to X->Y; (10) an AI-generated causal graph based on semantic plausibility alone is stored as HypothesizedCausalRelation, not ValidatedCausalRelation; (11) a positive causal effect (+10) with higher cost (15, net utility -5) is not automatically chosen -- CausalEffect≠DecisionUtility; (12) an individual counterfactual Y_i(0)=6 claimed from observed X_i=1,Y_i=10 is not directly observable without identification assumptions; (13) a constructed non-identifiable causal question (two models fit the same observed distribution) correctly returns NotIdentified rather than a unique P(Y|do(X)); (14) storing an ObservedFact and a CausalHypothesis under the same generic status field ('verified=true') making them indistinguishable is Rejected." (anchor: "Fourteen causal-reasoning experiments re-run within the DDD architecture (all PASS)")

## Notes for P3
None — this label's evidence is internally consistent within the rows captured for this batch.
