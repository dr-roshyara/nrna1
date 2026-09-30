# value-of-information-concept

**Scope(s):** OBJECT · **Row count:** 8 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Value of Information, Wisdom-driven evidence acquisition · **Aliases:** none recorded

**Candidate group membership (NOT an identity claim):**
- **G0077** [`evidence-value-concept` · `value-of-information-concept`] — explicit agent-stated uncertainty: 'evidence-value-concept' POSSIBLY relates to 'value-of-information-concept' (batch B0012). Note: Stigler/Peirce-derived object (expected uncertainty reduction, decision relevance, acquisition cost/time, reliability, independence, discriminative power) for ranking candidate evidence by value/cost; paired with SearchMultiplicity (hypotheses considered, evidence sources, queries, paths explored, alternatives rejected) capturing that a final conclusion's confidence cannot be interpreted as if it were the only hypothesis considered, grounded in Stigler's 'garden of forking paths' (data/direction/question/grouping/analysis choices) and its high-dimensional-data/multiple-comparisons 'eighth pillar' problem.
- **G0079** [`inquiry-stopping-policy` · `value-of-information-concept`] — explicit agent-stated uncertainty: 'inquiry-stopping-policy' POSSIBLY relates to 'value-of-information-concept' (batch B0012). Note: Proposed policy object with stopping reasons RESOLVED/NOT_PROVEN/COST_EXCEEDS_VALUE/EVIDENCE_UNAVAILABLE/IDENTIFICATION_IMPOSSIBLE/DECISION_ALREADY_SUFFICIENT/AUTHORITY_REQUIRED/TIME_BOUND_EXPIRED, grounded in Peirce's claim that inquiry exists because of doubt and stops once doubt is settled; connected to the prior Value-of-Information research.
- **G0235** [`economics-resource-constraints-optimization-model` · `value-of-information-concept`] — explicit agent-stated uncertainty: 'economics-resource-constraints-optimization-model' POSSIBLY relates to 'value-of-information-concept' (batch B0024). Note: Step 98: resources are finite, so rigor means risk-proportional allocation of verification/computation, not proving everything. Covers 5-level assurance scale, VOI-driven investigation, human attention as a finite resource (DecisionValuePerUnitAttention over NumberOfOutputs), compression-requires-provenance, ranked/verified retrieval, adaptive verification with explicit stop conditions and budget-exhaustion!=success, graceful degradation, resource-bounded AI planning, hard-constraint-vs-optimization-objective separation (max f(x) s.t. invariants), assurance-aware AI model routing, automation-bias risk, knowledge/cache lifecycle and retention, the provenance-vs-hidden-reasoning architectural boundary, resource-exhaustion-as-attack, resource isolation, and priority inversion. Closely related to the pre-existing value-of-information-concept.
- **G1044** [`information-gain-value-of-evidence` · `value-of-information-concept`] — working_label token overlap Jaccard=0.50 (shared tokens: ['information', 'of', 'value'])
- **G1480** [`economics-resource-constraints-optimization-model` · `value-of-information-concept`] — labels co-occur in the same contribution's labels[] 3 separate times across the corpus

## Sources (how this label entered the ledger)

- **OBJECT-INDEX**, batch B0012, scope OBJECT: Principle that evidence acquisition should be directed by expected information value relative to a pending decision (would this evidence change the decision?) rather than indiscriminate collection; if additional evidence cannot change a decision, do not spend resources acquiring it.

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S0453 §"Collect evidence that has high information value relative to the current uncertainty. ... Which observation would most reduce uncertainty? rather than: What documents can I find?"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0982 §"82.46 — Value of information returns ... VOI=ExpectedImprovement-CostOfInformation. This links Step 75 and Step 82. KnowledgeOS can prioritize evidence acquisition based on its expected decision impact."]
- CANDIDATE-OPERATIONAL-BIRTH: [S0982 §"82.47 — Experiment 23 ... Investigation A costs €1,000, expected value €10,000 decision change. Investigation B costs €1,000 but almost never changes the decision. Expected: A has higher information value. Result: PASS"]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S0998. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/contradiction rows found). This DORMANT classification is a heuristic based on how recently (by source_id, last_seen=S0998) this label was last used in the captured contribution set, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0982, S0989, S0998 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0453 |
| dependencies | PRESENT | S0453 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0453, S0982, S0989 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S0453 |
| experiments | PRESENT | S0982, S0998 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE

## Assumption register

NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S0453]` types=[PRINCIPLE, EXTENSION] scope=CROSS-OBJECT — "Evidence acquisition should be directed by expected information value relative to current uncertainty (active-learning-like), grounded in the book's expert-perception discussion that experts know where useful information will appear; called possibly one of the most important design principles for future KnowledgeOS agents." (anchor: "Collect evidence that has high information value relative to the current uncertainty. ... Which observation would most reduce uncertainty? rather than: What documents can I find?")
- `[S0453]` types=[WARNING, INVARIANT] scope=THEORY-LEVEL — "Chinese-lens warning against confusing movement with progress: high-volume AI agent activity can reduce uncertainty by almost nothing; the correct test is whether evidence changed justified understanding of the situation, not how much evidence was processed." (anchor: "An AI agent can search 500 documents, extract 10,000 facts, generate 50 claims, and still reduce uncertainty by almost nothing. ... Activity ≠ Learning ... More evidence ≠ More knowledge.")
- `[S0982]` types=[FORMALIZATION, RESTATEMENT] scope=OBJECT — "Restates Value of Information VOI = ExpectedImprovement - CostOfInformation, explicitly linking Step 75's VOI concept to Step 82's uncertainty-propagation framework for prioritizing evidence acquisition." (anchor: "82.46 — Value of information returns ... VOI=ExpectedImprovement-CostOfInformation. This links Step 75 and Step 82. KnowledgeOS can prioritize evidence acquisition based on its expected decision impact.")
- `[S0982]` types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 23: comparing two equal-cost investigations, A (high expected decision-change value) correctly has higher information value than B (rarely changes the decision); result PASS." (anchor: "82.47 — Experiment 23 ... Investigation A costs €1,000, expected value €10,000 decision change. Investigation B costs €1,000 but almost never changes the decision. Expected: A has higher information value. Result: PASS")
- `[S0989]` types=[FORMALIZATION, PRINCIPLE] scope=OBJECT — "Defines computational economics: compute further only when VOI>C_compute, or when governance mandates the computation regardless of economic value." (anchor: "90.37 — Computational economics ... C_compute. B_decision. VOI=ExpectedValueOfInformation. Compute further only when VOI>C_compute or when governance requires the computation regardless of economic value.")
- `[S0998]` types=[FORMALIZATION, DEFINITION] scope=OBJECT — "Restates Value of Information VOI(I)=ExpectedDecisionImprovement-Cost(I) as the criterion for seeking more information." (anchor: "98.13 — Value of information ... VOI(I)=ExpectedDecisionImprovement-Cost(I). If VOI(I)>0, additional information may be worthwhile.")
- `[S0998]` types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 6: a cheap, high-uncertainty-reducing check has VOI>0 and should be performed; result PASS." (anchor: "98.14 — Experiment 6 ... Decision uncertainty is high. A 5-minute automated check can significantly reduce uncertainty. Expected: VOI>0. KnowledgeOS should consider performing it. Result: PASS")
- `[S0998]` types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=OBJECT — "Experiment 7: an expensive investigation for a tiny uncertainty reduction in a low-impact decision has VOI<0 and may be unjustified; result PASS." (anchor: "98.15 — Experiment 7 ... A two-week investigation is required to reduce a tiny uncertainty in a low-impact decision. Expected: VOI<0. The investigation may not be justified. Result: PASS")

## Notes for P3

This label sits in 5 candidate groups (G0077, G0079, G0235, G1044, G1480) — a busy grouping signal. Per the P2a preface this can reflect genuine relatedness or just a heavily-reused naming/notation convention; worth a priority look in P3 rather than assuming either reading.
