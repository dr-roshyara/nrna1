# negative-test-propositions-p1-p10

**Scope(s):** METHODOLOGICAL · **Row count:** 6 · **Lifecycle (candidate):** ACTIVE · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `P1: Situation=KnowledgeOS state`, `P3: Reiter's delta completely defines KnowledgeOS delta`, `P5: Reiter resolves KnowledgeOS equality`, `P9: Golog is directly usable as the KnowledgeOS workflow model`
**Aliases:** `mandatory negative tests`
**Candidate group membership (NOT an identity claim):**
- G1825: [`negative-test-propositions-p1-p10` · `reiter-knowledgeos-derivation-mandate`] — labels co-occur in the same contribution's labels[] 4 separate times across the corpus

## Sources (how this label entered the ledger)
- **OBJECT-INDEX**, batch B0061, scope METHODOLOGICAL: Ten propositions the mandate requires attempting to FALSIFY rather than confirm: P1 Situation=state, P2 Action=event, P3 Reiter's delta completely defines KnowledgeOS delta, P4 Reiter solves Qualify, P5 Reiter resolves KnowledgeOS equality, P6 Reiter makes K_t history-complete, P7 Reiter makes provenance unnecessary, P8 Reiter's knowledge operator equals KnowledgeOS Knowledge, P9 Golog is directly usable as the KnowledgeOS workflow model, P10 progression is sufficient for KnowledgeOS state evolution; explicitly 'a failed correspondence is a valuable result'.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S2538 §"P1: Situation = KnowledgeOS state ... P3: Reiter's delta completely defines KnowledgeOS delta ... P5: Reiter resolves KnowledgeOS equality ... P9: Golog is directly usable as the KnowledgeOS workflow model ... P10: progression is sufficient for KnowledgeOS state evolution. A failed correspondence is a valuable result."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: [S2538 §"P1: Situation = KnowledgeOS state ... P3: Reiter's delta completely defines KnowledgeOS delta ... P5: Reiter resolves KnowledgeOS equality ... P9: Golog is directly usable as the KnowledgeOS workflow model ... P10: progression is sufficient for KnowledgeOS state evolution. A failed correspondence is a valuable result."]
- CANDIDATE-GOVERNANCE-BIRTH: [S2566 §"verdict: Situation = K_t REFUTED -- and refuted by KnowledgeOS's OWN prior evidence, not by Reiter. 9 of 10 negative tests refuted. One BOUNDED candidate (delta as an SSA), one SUPPORTED mechanism (regression), one method, one naming. NO open item closed. Theory v1.2 unchanged; no governance act."]

## Lifecycle
last_seen: S2566. Candidate lifecycle: ACTIVE.
Evidence: none recorded (retracted_by and superseded_by both empty, no own-contradiction trigger). Since lifecycle_candidate is ACTIVE, this is a heuristic based on how recently (by source_id) this label was last used (S2566), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S2566 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | PRESENT | S2561 |
| invariants | PRESENT | S2566 |
| dependencies | PRESENT | S2561, S2562, S2566 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S2539, S2566 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S2538, S2539, S2561, S2566 |
| open_questions | PRESENT | S2562 |

## Rationale
- [S2566] (ANALYSIS, EXPERIMENTAL-RESULT): Meta-level interpretation of the 9-of-10 negative-test refutation: no test fails because Reiter's formalism is wrong: each fails because the relevant Reiter construct is well-defined only relative to assumptions KnowledgeOS does not (yet) satisfy -- causal completeness, consistent effect axioms, factive knowledge, and an existing action theory. Reiter's machinery is therefore available exactly to the extent KnowledgeOS supplies the assumptions it requires, and those assumptions are precisely the programme's remaining open items.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)
- [S2538] types=['EXPERIMENT'] scope=METHODOLOGICAL — "Full list of ten mandatory falsification-target propositions (P1-P10) spanning situation-identity, action-event correspondence, delta-completeness, Qualify-resolution, equality-resolution, history-completeness, provenance-necessity, knowledge-operator identity, Golog-as-workflow, and progression-sufficiency, with the explicit framing that a failed correspondence is itself a valuable research result." (anchor: "P1: Situation = KnowledgeOS state ... P3: Reiter's delta completely defines KnowledgeOS delta ... P5: Reiter resolves KnowledgeOS equality ... P9: Golog is directly usable as the KnowledgeOS workflow model ... P10: progression is sufficient for KnowledgeOS state evolution. A failed correspondence is a valuable result.")
- [S2539] types=['RESTATEMENT', 'EXPERIMENT'] scope=METHODOLOGICAL — "Restates the ten P1-P10 falsification propositions from S2538, each pre-annotated [TEST] with a one-line expected-evidence hint (e.g. P1: History!=state; P3: missing authority/evidence; P8: different semantics; P9: composition only; P10: non-monotonic issues) -- these are hints guiding the test, not yet the executed test results." (anchor: "P1: Situation = KnowledgeOS state | [TEST] | History \neq state ... P3: Reiter's delta completely defines KnowledgeOS delta | [TEST] | Missing authority, evidence ... P9: Golog is directly usable as KnowledgeOS workflow | [TEST] | Composition only")
- [S2561] types=['EXPERIMENTAL-RESULT', 'CORRECTION'] scope=CROSS-OBJECT — "Negative test P4 (Reiter solves Qualify) is applied and Qualify-as-precondition is REFUTED: Reiter's Poss(a,s) gates whether an action may occur, while Qualify concerns whether a qualification holds of a knowledge item -- a different type occupying a different pipeline position, so the correspondence fails." (anchor: "Is `Qualify` a **precondition**? | **REFUTED** -- `P4`. `Poss(a,s)` gates *whether an action may occur*; `Qualify` concerns *whether a qualification holds of a knowledge item*. Different type, different pipeline position | **REFUTED**")
- [S2562] types=['OPEN-QUESTION', 'LIMITATION'] scope=CROSS-OBJECT — "Of the six candidate components tested for inside/outside placement relative to the transition mechanism, four (Identity/Equality, Observation, Qualification, plus one more per the table) remain UNRESOLVED and cannot be placed without deciding open questions (equiv_sem OPEN, Observation OPEN in v1.2, Qualification's Poss-identification already refuted by P4) by implication; recording the four as unplaced is treated as the correct result, not a gap to be guessed shut." (anchor: "Identity / Equality | UNRESOLVED -- cannot be placed | equiv_sem is OPEN; placing it now would decide it by implication ... Observation | UNRESOLVED | OPEN in v1.2; see 07 ... Qualification | UNRESOLVED | P4 refuted the Poss identification ... Four of six cannot be placed. Recording that is the result; guessing would be the error.")
- [S2566] types=['RESTATEMENT', 'GOVERNANCE'] scope=THEORY-LEVEL — "Top-line verdict of the whole Reiter/KnowledgeOS derivation programme: Situation=K_t is REFUTED, and refuted by KnowledgeOS's own prior evidence rather than by anything in Reiter; 9 of 10 negative tests (P1-P10) are refuted; the programme yields one BOUNDED candidate (delta as a successor-state-axiom shape), one SUPPORTED mechanism (regression), one CORROBORATED method, and one NEW-SYNTHESIS naming; no open item is closed, Theory v1.2 is unchanged, and no governance act was taken." (anchor: "verdict: Situation = K_t REFUTED -- and refuted by KnowledgeOS's OWN prior evidence, not by Reiter. 9 of 10 negative tests refuted. One BOUNDED candidate (delta as an SSA), one SUPPORTED mechanism (regression), one method, one naming. NO open item closed. Theory v1.2 unchanged; no governance act.")
- [S2566] types=['ANALYSIS', 'EXPERIMENTAL-RESULT'] scope=THEORY-LEVEL — "Meta-level interpretation of the 9-of-10 negative-test refutation: no test fails because Reiter's formalism is wrong: each fails because the relevant Reiter construct is well-defined only relative to assumptions KnowledgeOS does not (yet) satisfy -- causal completeness, consistent effect axioms, factive knowledge, and an existing action theory. Reiter's machinery is therefore available exactly to the extent KnowledgeOS supplies the assumptions it requires, and those assumptions are precisely the programme's remaining open items." (anchor: "Negative tests -- 9 of 10 refuted ... None fails because Reiter is wrong. Each fails because a construct is well-defined relative to assumptions KnowledgeOS does not satisfy -- causal completeness, consistent effect axioms, factive knowledge, an existing action theory. Reiter's machinery is available exactly to the extent that KnowledgeOS supplies the assumptions it requires -- and those assumptions are precisely the programme's open items.")

## Notes for P3
(none beyond what is noted above)
