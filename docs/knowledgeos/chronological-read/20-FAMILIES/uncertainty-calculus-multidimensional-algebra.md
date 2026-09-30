# uncertainty-calculus-multidimensional-algebra

**Scope(s):** OBJECT · **Row count:** 64 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** EvidenceStatus enum, P(H)|C,M,E,t, U(H)=(U_measurement,U_epistemic,U_aleatoric,U_model,U_semantic,U_identity,U_temporal,U_causal), Unknown!=0.5 · **Aliases:** Uncertainty Calculus, Probability, Confidence, Belief, Evidence Weighting and the Mathematics of Unknown
**Candidate group membership (NOT an identity claim):**
- G0172 [`uncertainty-calculus-multidimensional-algebra` · `uncertainty-taxonomy-typed-propagation`] — explicit agent-stated uncertainty (POSSIBLY related, batch B0022); the source note itself states this document "extends B0021's uncertainty-taxonomy-typed-propagation (S0877/Step 20)" with formal Bayes/likelihood/confidence-vs-credible-interval machinery.

## Sources (how this label entered the ledger)
- **PROPOSAL**, batch B0022, scope OBJECT (relation_to_existing: POSSIBLY:uncertainty-taxonomy-typed-propagation): S0920's Step 27: the fully worked multidimensional uncertainty calculus (eight uncertainty types, Unknown≠0.5, imprecise/interval probability, Dempster-Shafer ignorance) explicitly rejecting a universal confidence score; extends B0021's uncertainty-taxonomy-typed-propagation (S0877/Step 20) with formal Bayes/likelihood/confidence-vs-credible-interval machinery and missing-data-mechanism treatment.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0920 §"probably true, high confidence, evidence is weak, we don't know, two plausible explanations, probability is 70% ... not interchangeable ... do not introduce one universal confidence number"]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0920 §"Infrastructure may use AvailabilityProbability. Governance may use EvidenceStatus. Identity may use MatchProbability. Architecture may use ApplicabilityStatus ... should not all be forced into ConfidenceScore"]
- CANDIDATE-FORMAL-BIRTH: [S0920 §"U(H)=(U_measurement,U_epistemic,U_aleatoric,U_model,U_semantic,U_identity,U_temporal,U_causal) ... not every proposition needs every component"]
- CANDIDATE-OPERATIONAL-BIRTH: [S0920 §"Falsification tests A-I: Unknown status->no automatic probability PASS; high-quality but off-target evidence->Relevance=False PASS; same-source reports->IndependentEvidenceCount=1 PASS; probability outside model applicability->ProbabilityStatus=NotApplicable PASS; measurement uncertainty not auto-co..."]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0920. Candidate lifecycle: DORMANT.
Evidence: no retraction/supersession/contradiction evidence recorded — the DORMANT classification is a heuristic based on how recently (by source_id) this label was last used, not a confirmed ongoing status or a confirmed retirement.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0920 |
| informal_meaning | PRESENT | S0920 |
| formal_definition | PRESENT | S0920 |
| type_signature | PRESENT | S0920 |
| invariants | PRESENT | S0920 |
| dependencies | PRESENT | S0920 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0920 |
| examples | PRESENT | S0920 |
| warnings | PRESENT | S0920 |
| experiments | PRESENT | S0920 |
| open_questions | PRESENT | S0920 |

(All source_ids collapse to S0920 — one document throughout.)

## Rationale
- [S0920] (ARGUMENT/WARNING) Argues a bare confidence score is ambiguous across at least five distinct meanings — states `UniversalConfidenceScore = ArchitecturalAntiPattern`. This is the document's central organizing argument: nearly every theme below (the eight uncertainty types, the enums, the intervals, Dempster-Shafer, the decision decomposition) is a distinct alternative to collapsing everything into one number.
- [S0920] (FORMALIZATION/ALTERNATIVE) Defines a robust/minimax decision criterion for unreliable probability estimates, explicitly not universally appropriate — offered as one of several situational alternatives once a single confidence number has been rejected as the default.

`rationale_truncated_count` is 0 — no further rationale-bearing rows exist beyond the 2 shown.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows

All 64 rows come from a single source, **S0920** ("Step 27"), which the source note describes as extending a prior uncertainty-taxonomy document (S0877/Step 20). Grouped into themes; full text of every row is in `03-CONTRIBUTIONS.jsonl` under S0920.

**Theme 1 — Motivation: uncertainty is not one scalar (3 rows: 0-2).** Lists six common uncertainty expressions ("probably true," "high confidence," "evidence is weak," etc.) as not interchangeable, recommending against one universal confidence number; separates six distinct concepts (Truth, Evidence, Probability, Belief, Confidence, Uncertainty) with three contrasting worked examples, none collapsible into one scalar; states `P(H)=0.7 ≠ Truth(H)=0.7` — probability describes uncertainty, not fractional truth.

**Theme 2 — The eight uncertainty types (10 rows: 3-12).** Defines each of eight distinct uncertainty types in turn, each with a distinguishing worked example: epistemic (lack of sufficient evidence, reducible by more evidence); aleatoric (inherent process variability, not eliminable by more observation, network-traffic example); a worked contrast showing the same `P(Failure)=0.1` admits both epistemic and aleatoric interpretations requiring different responses; measurement (distinct from outcome uncertainty); model (choice among plausible models, distinct from sampling uncertainty); parameter (via a regression-coefficient standard error, distinct from model uncertainty); semantic (ambiguous term meaning, not resolvable by a probability over the technical outcome); identity (a probability over entity sameness, distinct from outcome probability); temporal (about an event's exact time, warning against arbitrary point selection); causal (distinct from probability-distribution uncertainty).

**Theme 3 — The formal eight-component uncertainty vector (2 rows: 13-14).** Defines a formal eight-component uncertainty vector `U(H)`, not every proposition requiring every component; argues a bare confidence score is ambiguous across at least five distinct meanings — `UniversalConfidenceScore = ArchitecturalAntiPattern`.

**Theme 4 — Probability requires explicit semantics and context (3 rows: 15-17).** Defines probability formally, requiring clear semantics before assignment; requires probability to carry context, formalized as `P(H|C,M,E,t)`; prefers conditional probability over unconditional probability for meaningfulness.

**Theme 5 — Bayesian updating (4 rows: 18-21).** Worked base-rate-neglect example: a positive detector signal does not directly give the posterior incident probability without Bayesian correction; states Bayes' theorem as giving a principled prior+evidence→posterior update fitting KnowledgeOS naturally; defines a KnowledgeOS Bayesian-update form, explicitly not required for every inference — "one tool among several"; states `EvidenceAssessment ≠ Probability` — evidence strength depends on six listed factors, distinct from a model-dependent probability.

**Theme 6 — Likelihood, Bayes factors, and evidence independence (5 rows: 22-26).** Defines likelihood `L(H;E)=P(E|H)`, distinct from the posterior `P(H|E)`; worked example showing evidence favoring one hypothesis via likelihood alone, but posteriors requiring priors that aren't given; defines Bayes factors for hypothesis comparison; worked example warning against treating four commonly-sourced reports as four independent observations — `EvidenceCount ≠ IndependentEvidenceCount`, connecting to Step 25X; requires an independence graph preserving source lineage to determine plausible pairwise independence.

**Theme 7 — Confidence intervals vs. credible intervals (4 rows: 27-30).** Warns against the common misinterpretation of a frequentist confidence interval, requiring explicit statistical semantics before casual "95% Confidence" language; formally defines a confidence interval as a long-run-coverage procedure, not a subjective probability distribution; defines the Bayesian credible interval as a genuinely different statement — `ConfidenceInterval ≠ CredibleInterval`; prefers an interval estimate over false-precision point estimates when data are sparse.

**Theme 8 — Imprecise/bounded probability and its limits (4 rows: 31-34).** Defines bounded probability `P(H)∈[L,U]` for cases where a single distribution cannot be justified; defines imprecise probability as a set of plausible distributions, useful for heterogeneous KnowledgeOS evidence; explicitly declines to make imprecise probability the universal representation — "mathematical sophistication ≠ architectural quality," introduce advanced formalisms only where needed; states uncertainty semantics belong to the bounded context, worked with four domain-specific examples rather than one forced `ConfidenceScore`.

**Theme 9 — Categorical status enums as alternatives to numbers (3 rows: 35-37).** Defines a five-value categorical `EvidenceStatus` enum for governance, preferred over a confidence number; defines an eight-value `EpistemicStatus` enum as semantic states, not probabilities; worked example showing probability and epistemic status coexisting in one richer record.

**Theme 10 — Unknown ≠ 0.5 (3 rows: 38-40).** Defines Unknown as distinct from `P(H)=0.5`, since 0.5 is itself a substantive probabilistic claim requiring justification; states the invariant `Unknown ≠ 0.5`, called "one of the strongest conclusions of Step 27"; warns against encoding missing data as a default zero or a default 0.5 probability.

**Theme 11 — Missing data, null semantics, and observation bias (6 rows: 41-46).** Defines a six-value null-semantics taxonomy that a single SQL NULL cannot express; introduces the statistical missing-data-mechanism taxonomy (MCAR/MAR/MNAR) as potentially relevant to KnowledgeOS analytics; worked missing-not-at-random example warning that missing evidence must not be assumed neutral; worked selection-bias example: evidence quality depends on the observation-generating process, not only on what was observed; models an explicit `ObservationProcess` that can itself introduce bias; worked AI-corpus example: documentation bias toward unusual failures can cause the system to overestimate failure rates.

**Theme 12 — Evidence quality, aggregation, and Dempster-Shafer ignorance (4 rows: 47-50).** Defines a six-dimension evidence-quality vector, explicitly not itself a probability; lists six evidence-aggregation methods, with the bounded context determining the appropriate one; introduces a Dempster-Shafer-style representation assigning support to a set of hypotheses without distinguishing among them, worked with a Network-vs-Database cause example, explicitly aligned with `Unknown≠50%`; restates the caution against over-formalizing uncertainty — introduce advanced formalisms only where they solve a demonstrated problem.

**Theme 13 — Decision-uncertainty decomposition and value of information (4 rows: 51-54).** Defines a five-term decision-uncertainty decomposition, explicitly not required to be numerically additive — valued for diagnostic separation; maps each decomposition component to a distinct remedial action; defines `NextBestInformation` as the value-of-information-maximizing observation, connecting to Steps 25Z and 26; restates expected-utility decision-making subject to governance constraints.

**Theme 14 — Decision governance and robustness (3 rows: 55-57).** Restates that risk constraints can override raw expected-utility ranking, giving `Decision = Optimization + Constraints + Governance`; defines a robust/minimax decision criterion for unreliable probability estimates, explicitly not universally appropriate; worked sensitivity-analysis example identifying a decision-flipping probability threshold, making the recommendation transparent and conditional rather than a bare directive.

**Theme 15 — Falsification tests and self-verdict (2 rows: 58-59).** Runs nine falsification tests (A-I, all PASS) against the uncertainty-calculus model — covering unknown-status non-assignment, off-target-evidence relevance, same-source independence counting, out-of-applicability probability status, measurement-vs-outcome uncertainty separation, semantic-ambiguity handling, underdetermined/set-valued belief instead of forced 50/50, VOI-driven dominant observation, and historically-accessible revised probabilities; Step 27 self-verdict: PASS, with seven boxed non-equivalences/principles.

**Theme 16 — Rich epistemic-state record and design principle (3 rows: 60-62).** Presents a fully worked rich epistemic-state record spanning thirteen fields, vastly more expressive than a bare confidence percentage; defines a consolidated three-layer state decomposition — KnowledgeState, DecisionState, ActionState; states the design principle that KnowledgeOS should never expose a naked ambiguous number, requiring every number to carry five contextual components.

**Theme 17 — Closing open question (1 row: 63).** Closes by posing the multi-authority conflict question (competing probability, agent, human, and rule-based claims), transitioning to Step 28 (Epistemic Conflict, Belief Revision, Multiple Authorities, Contradiction Management, Knowledge Reconciliation), introducing the expected invariant "Conflict is information," not to be automatically destroyed through premature reconciliation.

Theme row-counts: 3+10+2+3+4+5+4+4+3+3+6+4+4+3+2+3+1 = 64, matching `family.row_count`.

## Notes for P3
- This label explicitly hands off to "Step 28 (Epistemic Conflict, Belief Revision, Multiple Authorities, Contradiction Management, Knowledge Reconciliation)" (row 63), and this batch (LB0020) separately contains `knowledge-consistency-constraint-satisfiability-algebra` (Step 29, S0922), which in turn is the source note's own description of extending "pairwise conflict detection (Step 28)." This suggests a Step 27 → Step 28 → Step 29 sequential chain (S0920 → [Step 28, source unclear/not in this batch] → S0922) worth checking against the corpus's full step numbering — an observation about sequencing, not an identity claim.
- The document's own relation_to_existing note says it "extends B0021's uncertainty-taxonomy-typed-propagation (S0877/Step 20)" — P3 should check whether `uncertainty-taxonomy-typed-propagation` exists as a working_label elsewhere in the corpus and treat this as a strong, source-stated (not merely mechanical) candidate lineage link, consistent with group G0172.
- This is an unusually rich single-source theory document (12 of 12 completeness dimensions PRESENT except `assumptions`) — worth flagging as a strong evidentiary base if P3 is prioritizing which candidate objects to formalize first.
- No internal contradiction found among these 64 rows; they read as one continuous, single-author theory document.
