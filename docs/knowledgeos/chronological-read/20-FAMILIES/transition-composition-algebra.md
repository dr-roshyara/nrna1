# transition-composition-algebra

**Scope(s):** OBJECT · **Row count:** 4 ·
**Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** K_{t+2}=delta(delta(K_t,e1),e2), delta_e2 o delta_e1, sequence;choice|;iteration* · **Aliases:** Golog-style transition composition, KR-ACT-COMP
**Candidate group membership (NOT an identity claim):**
Ungrouped — no mechanical signal connected this label to any other in P2a.

## Sources (how this label entered the ledger)
- OBJECT-INDEX · batch B0061 · scope OBJECT — "Candidate KnowledgeOS transition-composition law derived from Golog's complex-action composition (sequence, choice, iteration), explicitly proposed as a research item (KR-ACT-COMP) investigating preconditions/partiality/determinism/provenance/authority/failure/boundary/temporal semantics, not adopted as primitives; explicitly distinguished as TransitionComposition != EvaluationComposition."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2526 §"Do(\delta_1;\delta_2,s,s') ... \delta_{e_2}\circ\delta_{e_1} ... K_{t+2}=\delta(\delta(K_t,e_1),e_2) ... Sequence \delta_1;\delta_2, Choice \delta_1\mid\delta_2, Iteration \delta^* ... Do not add Golog operators to Theory v1.3 as KnowledgeOS primitives. Instead add KR-ACT-COMP"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S2526 §"Do(\delta_1;\delta_2,s,s') ... \delta_{e_2}\circ\delta_{e_1} ... K_{t+2}=\delta(\delta(K_t,e_1),e_2) ... Sequence \delta_1;\delta_2, Choice \delta_1\mid\delta_2, Iteration \delta^* ... Do not add Golog operators to Theory v1.3 as KnowledgeOS primitives. Instead add KR-ACT-COMP"]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S2526 §"DK.1 State and History [DERIVED] ... DK.3 Successor-State Semantics [PROP — EXPERIMENT REQUIRED] ... DK.5 Executable History [PROP] ... DK.6 Transition Composition [DERIVED], semantics of choice and iteration remain [OPEN] ... DK.8 Epistemic Update [PROP] ... DK.9 Temporal and Concurrent Transitions [PROP — REQUIRES TIME/FRAME DECISION]"]

## Lifecycle
last_seen: S2536. Candidate lifecycle: ACTIVE.
Evidence: no retraction/supersession/contradiction evidence recorded. Since no retraction/supersession/contradiction evidence is present, this lifecycle label is a heuristic based on how recently (by source_id) this label was last used in the corpus, not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S2526, S2527, S2536 |
| type_signature | PRESENT | S2526 |
| invariants | PRESENT | S2536 |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2526 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2526] types=[FORMALIZATION, EXTENSION] scope=OBJECT — "Derives a sequential transition-composition law K_{t+2} = delta(delta(K_t,e1),e2) from Golog's Do(delta1;delta2,s,s') semantics, giving a formal foundation for the open Composition lane; notes Golog also supplies choice and iteration operators, explicitly declining to adopt them as KnowledgeOS primitives and instead proposing research item KR-ACT-COMP to investigate preconditions/partiality/determinism/provenance/authority/failure/boundary/temporal semantics of composition." (anchor: "Do(\delta_1;\delta_2,s,s') ... \delta_{e_2}\circ\delta_{e_1} ... K_{t+2}=\delta(\delta(K_t,e_1),e_2) ... Sequence \delta_1;\delta_2, Choice \delta_1\mid\delta_2, Iteration \delta^* ... Do not add Golog operators to Theory v1.3 as KnowledgeOS primitives. Instead add KR-ACT-COMP")
- [S2526] types=[GOVERNANCE, RESTATEMENT] scope=THEORY-LEVEL — "Proposes a 9-section 'KNOWLEDGEOS — DYNAMIC KNOWLEDGE AND TRANSITION SEMANTICS' research-draft addition (DK.1 State/History [DERIVED], DK.2 Transition Applicability [DERIVED], DK.3 Successor-State Semantics [PROP-EXPERIMENT REQUIRED], DK.4 Boundary/Persistence [DERIVED+PROP], DK.5 Executable History [PROP], DK.6 Transition Composition [DERIVED, choice/iteration OPEN], DK.7 Reasoning Over Transitions [DERIVED], DK.8 Epistemic Update [PROP], DK.9 Temporal/Concurrent Transitions [PROP-REQUIRES TIME/FRAME DECISION]), explicitly not yet called Theory v1.3, with per-section status tags distinct from the KR.1-KR.10 DL-thread tags in S2523/S2524." (anchor: "DK.1 State and History [DERIVED] ... DK.3 Successor-State Semantics [PROP — EXPERIMENT REQUIRED] ... DK.5 Executable History [PROP] ... DK.6 Transition Composition [DERIVED], semantics of choice and iteration remain [OPEN] ... DK.8 Epistemic Update [PROP] ... DK.9 Temporal and Concurrent Transitions [PROP — REQUIRES TIME/FRAME DECISION]")
- [S2527] types=[FORMALIZATION] scope=OBJECT — "Gives the complete Golog Do-macro definitions underlying the composition algebra: primitive action, sequence (via an intermediate situation s''), choice (disjunction), and iteration (a second-order least-fixpoint-style formula quantifying over all inductive predicates P), plus a Test operator phi?; composition classified as Composition ⊇ {Sequence, Choice, Iteration, Condition}, framed as macro expansions over the basic transition semantics. Status: [DERIVED] -- composition semantics fully specified by Situation Calculus (though KnowledgeOS adoption of choice/iteration as primitives remains a separate, undecided question per S2526)." (anchor: "Do(a,s,s') \equiv Poss(a[s],s) \land s'=do(a[s],s) ... Do(\delta_1;\delta_2,s,s')\equiv(\exists s'')Do(\delta_1,s,s'')\land Do(\delta_2,s'',s') ... Do(\delta_1|\delta_2,s,s')\equiv Do(\delta_1,s,s')\lor Do(\delta_2,s,s') ... Do(\delta^*,s,s')\equiv(\forall P).\{...\}\supset P(s,s')")
- [S2536] types=[FORMALIZATION, VALIDATION] scope=OBJECT — "Extends the Golog Do-macro family beyond sequence/choice/iteration (already captured elsewhere) with a test-action macro (Do(phi?,s,s')) and a nondeterministic choice-of-arguments macro (Do((pi x)delta(x),s,s')); states the theorem that every successful Golog program evaluation leads to an executable situation, plus formal correctness, termination, and a while-loop induction principle for proving program properties." (anchor: "Do(\phi?,s,s')\stackrel{def}{=}\phi[s]\land s=s' ... Do((\pi x)\delta(x),s,s')\stackrel{def}{=}(\exists x)Do(\delta(x),s,s') ... Every successful program evaluation leads to an executable situation. ... Correctness: Axioms\models(\forall s).Do(\delta,S_0,s)\supset P(s) ... Termination ... Induction Principle for While Loops")

## Notes for P3
(Own observation.) Caution on a possible false cognate: S2527/S2536 tag several DK.x sections "[DERIVED]" in their own internal status vocabulary, but that tag means "already follows from Situation Calculus axioms" within this label's own three-way DERIVED/PROP/OPEN scheme — it is not evidence for this file's separate primary_layer taxonomy (FOUNDATIONAL/DERIVED/OPERATIONAL/META-THEORETICAL), so primary_layer is left LAYER-UNRESOLVED here despite the surface word match; P3 should watch for this same false-cognate risk on other Golog/DK.x-tagged labels in this corpus. Substantively, this label is explicit and consistent about scope: KnowledgeOS borrows Golog's *formal machinery* (sequence, choice, iteration, test, nondeterministic-argument-choice Do-macros, an executability theorem, and a correctness/termination/induction principle) while explicitly declining, twice (S2526 "Do not add Golog operators... as KnowledgeOS primitives", S2527 "adoption... as primitives remains a separate, undecided question"), to adopt choice/iteration as KnowledgeOS primitives — the corpus treats this as a research item (KR-ACT-COMP) rather than a settled design decision.
