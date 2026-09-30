# semantic-reference-object

**Scope(s):** OBJECT · **Row count:** 4 · **Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `EpistemicDistribution`, `SemanticReference` · **Aliases:** `a probability distribution is not itself an epistemic state`
**Candidate group membership (NOT an identity claim):**
- G1026: [`reference-class-object` · `semantic-reference-object`] — working_label token overlap Jaccard=0.50 (shared tokens: ['object', 'reference'])

## Sources (how this label entered the ledger)
- **OBJECT-INDEX** (batch B0012, scope OBJECT): Rescorla's distinction between a Credal State and a bare Probability Distribution/PDF (the same mathematical object could represent speed, size, distance, etc.) motivates a Kernel-level SemanticReference requirement: no probability value stored without its represented variable, hypothesis space, evidence, model, time and purpose; formalized as EpistemicDistribution{variable, hypothesis-space, representation, distribution, context, provenance}.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0464] §"the same mathematical distribution can represent different things depending on what it is about. A PDF could represent speed, size, distance, etc. ... We must never store: probability = 0.73 without knowing 0.73 of WHAT?"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S0464] §"Bayesian models often postulate priors without explaining their etiology, and that explaining where priors come from remains an important open issue. ... PriorBelief: hypothesisSpace, distribution, origin, calibrationContext, timestamp, provenance. ... Every material prior should ideally have provenance."
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0464. Candidate lifecycle: DORMANT.
Evidence: none recorded (no retraction/supersession/self-contradiction flagged in this label's rows). This DORMANT classification is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0464 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S0464 |
| dependencies | PRESENT | S0464 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0464 |
| examples | PRESENT | S0464 |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S0464] types=[DISTINCTION, INVARIANT] scope=OBJECT — "Credal State vs bare Probability Distribution/PDF distinction: the mathematical object alone does not identify its semantic content, so a stored probability must always carry its hypothesis space, evidence, model, time and purpose (NoProbabilityWithoutSemanticReference)." (anchor: "the same mathematical distribution can represent different things depending on what it is about. A PDF could represent speed, size, distance, etc. ... We must never store: probability = 0.73 without knowing 0.73 of WHAT?")
- [S0464] types=[FORMALIZATION, EXAMPLE] scope=OBJECT — "PriorBelief made a first-class epistemic object with explicit origin/provenance (example: prior 'most services are REST' with origin=historical project data, scope, last-calibrated date, evidence count, known exceptions), because Bayesian models often postulate priors without explaining etiology; introduces 'assumption provenance' as a new category alongside evidence provenance." (anchor: "Bayesian models often postulate priors without explaining their etiology, and that explaining where priors come from remains an important open issue. ... PriorBelief: hypothesisSpace, distribution, origin, calibrationContext, timestamp, provenance. ... Every material prior should ideally have provenance.")
- [S0464] types=[CONSTRAINT, EXAMPLE] scope=CROSS-OBJECT — "New AI governance rule following directly from the credal-state/mathematical-representation distinction: a bare AI-reported confidence number is not itself KnowledgeOS evidence unless backed by an explicit hypothesis/evidence/method/context/calibration/provenance package, illustrated by an inspectable 'Architecture Constitution compliance' worked example (prior 0.65 -> posterior 0.91 with evidence, likelihood model, assurance, decision) contrasted with a bare 'I am 91% confident.'" (anchor: "AI confidence without an explicit epistemic object is not KnowledgeOS evidence. ... A model-generated confidence = 0.91 is merely a claim about its own confidence unless backed by hypothesis, evidence, method, context, calibration, provenance.")
- [S0464] types=[PRINCIPLE] scope=THEORY-LEVEL — "Three principles called 'probably the most important architectural additions from this book': epistemic quantities are meaningless without semantic identity; epistemic state is a stateful object, not merely a scalar assessment; an epistemic transition must be distinguishable from the mathematical mechanism used to calculate it." (anchor: "Epistemic quantities are meaningless without semantic identity. ... Epistemic state is a stateful object, not merely a scalar assessment. ... An epistemic transition must be distinguishable from the mathematical mechanism used to calculate it.")

## Notes for P3
Carries 1 candidate group membership(s); P3 should prioritize resolving whether these reflect the same underlying object. Lifecycle is DORMANT on recency heuristics only — no explicit retraction/supersession was found in this label's own rows.
