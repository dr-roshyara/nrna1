# three-layer-epistemic-architecture

**Scope(s):** OBJECT · **Row count:** 7 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Epistemic Kernel / Epistemic Mechanisms / Institutional-Operational` · **Aliases:** none recorded
**Candidate group membership (NOT an identity claim):**
- **G0080** [`epistemic-bounded-context-map-v1` · `three-layer-epistemic-architecture`] — explicit agent-stated uncertainty: 'three-layer-epistemic-architecture' POSSIBLY relates to 'epistemic-bounded-context-map-v1' (batch B0012). Note: A three-layer architecture (distinct in shape from the eight-bounded-context map): Epistemic Kernel (Claim, Evidence, Basing, Provenance, Epistemic Standing, Input/Transition Rules, Inference, Reliability relation, Revision, Challenge, Temporal identity); Epistemic Mechanisms (Deduction, Induction, Bayesian methods, Search, Perception, Memory, LLM reasoning, Retrieval, Simulation, Testing, Statistical inference); Institutional/Operational (Governance, Authority, Risk, Decision, Human review, Regulatory policy, Workflow, Agent orchestration, Organizational roles).

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0012, scope OBJECT): A three-layer architecture (distinct in shape from the eight-bounded-context map): Epistemic Kernel (Claim, Evidence, Basing, Provenance, Epistemic Standing, Input/Transition Rules, Inference, Reliability relation, Revision, Challenge, Temporal identity); Epistemic Mechanisms (Deduction, Induction, Bayesian methods, Search, Perception, Memory, LLM reasoning, Retrieval, Simulation, Testing, Statistical inference); Institutional/Operational (Governance, Authority, Risk, Decision, Human review, Regulatory policy, Workflow, Agent orchestration, Organizational roles). _(relation_to_existing: POSSIBLY:epistemic-bounded-context-map-v1)_

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S0463 §"Otto stores information in a notebook and consults it functionally like another person's biological memory. ... the specific process matters. ... Extended Mind should NOT become a Kernel entity. The Kernel should say: epistemically relevant processes may cross system boundaries."]
- CANDIDATE-CONCEPTUAL-BIRTH: [S0463 §"Otto stores information in a notebook and consults it functionally like another person's biological memory. ... the specific process matters. ... Extended Mind should NOT become a Kernel entity. The Kernel should say: epistemically relevant processes may cross system boundaries."]
- CANDIDATE-FORMAL-BIRTH: [S0463 §"If premises are true and the inference is valid ... but the conclusion cannot add information beyond the premises. ... Induction: the conclusion can extend beyond the premises, but only probabilistically. ... DeductiveInference, InductiveInference should be distinct mechanism types."]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S0464. Candidate lifecycle: DORMANT.
Evidence: No retraction/supersession/contradiction lineage found. This lifecycle value is a heuristic based on how recently (by source_id, last_seen=S0464) this label was last used in the ledger, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S0463 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S0463 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S0463, S0464 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S0463 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
Deepest finding: the Kernel should answer what-is-a-claim/what-counts-as-evidence/what-is-basing/what-transformations/what-is-provenance/what-is-standing questions, never algorithm-choice or threshold questions; final Kernel formulation is a governed epistemic substrate preserving claims/evidence/basing/provenance/epistemic processes/admissible inputs/admissible transitions/reliability conditions/assessments/challenges/revisions, with everything else (LLMs, Bayesian inference, search, memory stores, RAG, tests, agents, reviewers, statistical methods, workflows, decision policies) operating on top without becoming the substrate. [S0463]

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- `[S0463]` types=[CONCEPT, LIMITATION] scope=THEORY-LEVEL — "Clark & Chalmers' Otto/Notebook extended-mind example supports the principle that epistemically relevant processes may cross system boundaries (human cognition, tool-assisted cognition, external artifact, environment), but Extended Mind itself must not become a Kernel entity -- only the principle is promoted, concrete tools (Notebook/Git/IDE/Database/LLM/Calculator/search engine/CI) stay outside." (anchor: "Otto stores information in a notebook and consults it functionally like another person's biological memory. ... the specific process matters. ... Extended Mind should NOT become a Kernel entity. The Kernel should say: epistemically relevant processes may cross system boundaries.")
- `[S0463]` types=[DISTINCTION, FORMALIZATION] scope=OBJECT — "Deduction (no information beyond premises) vs Induction (extends beyond premises probabilistically) should remain distinct mechanism types under one Inference vocabulary (Deductive/Inductive/Abductive-candidate/Probabilistic/Other) with only Inference/InferenceType/Validity-Support in the Kernel, algorithms outside." (anchor: "If premises are true and the inference is valid ... but the conclusion cannot add information beyond the premises. ... Induction: the conclusion can extend beyond the premises, but only probabilistically. ... DeductiveInference, InductiveInference should be distinct mechanism types.")
- `[S0463]` types=[DISTINCTION, LIMITATION] scope=OBJECT — "KnowledgeObject vocabulary distinction (Proposition / Procedure-Know-how / Entity-Referential knowledge) kept as vocabulary only, not yet promoted to separate Kernel aggregates, pending DDD scenario proof of ownership." (anchor: "KNOW-HOW, KNOWLEDGE-WH, KNOWLEDGE-THAT ... some procedural know-how is not simply propositional knowledge. ... a Kernel vocabulary distinction, with the concrete models outside Kernel until DDD scenarios prove ownership.")
- `[S0463]` types=[FORMALIZATION] scope=OBJECT — "Full three-layer architecture with explicit membership lists for each layer, and the governing rule that the Kernel records that a process occurred and its epistemic role, never how the process is implemented." (anchor: "EPISTEMIC KERNEL / EPISTEMIC MECHANISMS / INSTITUTIONAL-OPERATIONAL ... This book strongly supports this separation. ... The Kernel should know that a process occurred and what epistemic role it played, not how every process is implemented.")
- `[S0463]` types=[PRINCIPLE, ANALYSIS] scope=THEORY-LEVEL — "Deepest finding: the Kernel should answer what-is-a-claim/what-counts-as-evidence/what-is-basing/what-transformations/what-is-provenance/what-is-standing questions, never algorithm-choice or threshold questions; final Kernel formulation is a governed epistemic substrate preserving claims/evidence/basing/provenance/epistemic processes/admissible inputs/admissible transitions/reliability conditions/assessments/challenges/revisions, with everything else (LLMs, Bayesian inference, search, memory stores, RAG, tests, agents, reviewers, statistical methods, workflows, decision policies) operating on top without becoming the substrate." (anchor: "the Kernel should govern epistemic relationships, not epistemic algorithms ... it should not answer: Should Bayesian inference be used? ... Which decision threshold should the business use? ... The KnowledgeOS Kernel is not a database of knowledge and not a truth engine.")
- `[S0463]` types=[LIMITATION, CORRECTION] scope=THEORY-LEVEL — "Explicit list of six overextensions the book does NOT justify, guarding against over-reading any single lens (externalism, reliabilism, coherentism, contextualism, extended mind, testimony-trust) as a complete theory." (anchor: "The book does not justify: 'Externalism is universally correct.' ... 'Knowledge = reliable output.' ... 'Truth = coherence.' ... 'High stakes change truth.' ... 'Everything external to the human is part of the mind.' ... 'Every source should simply be trusted.'")
- `[S0464]` types=[LIMITATION, CORRECTION] scope=THEORY-LEVEL — "Predictive coding, despite being discussed at length, is explicitly classified only as a supporting-research/mechanism candidate, not a Kernel principle, because Rescorla himself treats its empirical support as equivocal -- an explicit application of the discipline against over-promoting book content." (anchor: "empirical support remains equivocal and that he sees no current reason to think approximate Bayesian inference is typically implemented through predictive processing in the relevant sense. ... PredictiveCoding = Supporting Research / Mechanism Candidate, NOT KnowledgeOS Kernel Principle.")

## Notes for P3
NOT-EVIDENCED-IN-CAPTURE — no reviewer-added observation for this label beyond what appears above.
