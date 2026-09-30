# lord-algebra-025g

**Scope(s):** THEORY-LEVEL · **Row count:** 5 ·
**Lifecycle (candidate):** CONTESTED · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** Lord(K_t,Z_t,G,C)->a_t, Prefer(a1,a2|K,G,C), a*=argprefer · **Aliases:** step-025g Formal Lord Algebra
**Candidate group membership (NOT an identity claim):**
- G0331: [`lord-algebra-025g` · `zero-lord-sarathi-guidance-cycle`] — explicit agent-stated uncertainty: 'lord-algebra-025g' POSSIBLY relates to 'zero-lord-sarathi-guidance-cycle' (batch B0036). Note: Step 025g (2026-08-27 18:37): formalizes Lord(K_t,Z_t,G,C)->a_t via action utility vector A=(Purpose,ExpectedGain,Cost,Risk,Urgency,Reversibility,Authorization,Dependencies,Deadline), a two-stage feasibility-then-optimization algorithm, a preference relation Prefer(a1,a2|K,G,C) deliberately replacing a scalar Score(a), Capability!=Authority, a default Lord policy ('prefer the least risky authorized action that maximally reduces decision-critical Zero'), a termination algebra {Completed,Blocked,Escalated,Abandoned,Expired}, DoNothing in ActionSpace, and six falsification PASSes including 'LLM recommendation cannot override Zero/Contract'; commissions Sarathi (step-025H) as the next research item, delivered the following day outside this batch.
- G0867: [`formal-lord-action-selection-algebra` · `lord-algebra-025g`] — labels share the notation 'Lord(K_t,Z_t,G,C)->a_t'
- G0868: [`formal-lord-action-selection-algebra` · `lord-algebra-025g`] — labels share the notation 'Prefer(a1,a2|K,G,C)'

## Sources (how this label entered the ledger)
- **PROPOSAL** (batch B0036, scope THEORY-LEVEL): Step 025g (2026-08-27 18:37): formalizes Lord(K_t,Z_t,G,C)->a_t via action utility vector A=(Purpose,ExpectedGain,Cost,Risk,Urgency,Reversibility,Authorization,Dependencies,Deadline), a two-stage feasibility-then-optimization algorithm, a preference relation Prefer(a1,a2|K,G,C) deliberately replacing a scalar Score(a), Capability!=Authority, a default Lord policy ('prefer the least risky authorized action that maximally reduces decision-critical Zero'), a termination algebra {Completed,Blocked,Escalated,Abandoned,Expired}, DoNothing in ActionSpace, and six falsification PASSes including 'LLM recommendation cannot override Zero/Contract'; commissions Sarathi (step-025H) as the next research item, delivered the following day outside this batch. _[relation_to_existing: POSSIBLY:zero-lord-sarathi-guidance-cycle]_

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1486] §"D15. step-025g (183746) -- Formal Lord Algebra ... section6 action utility vector A=(Purpose,ExpectedGain,Cost,Risk,Urgency,Reversibility,Authorization,Dependencies,Deadline) ... section19 Prefer(a1,a2|K,G,C) rather than Score(a) ... section24 the default Lord policy ... section54 commissions 025H (Sarathi)"
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1486] §"D15. step-025g (183746) -- Formal Lord Algebra ... section6 action utility vector A=(Purpose,ExpectedGain,Cost,Risk,Urgency,Reversibility,Authorization,Dependencies,Deadline) ... section19 Prefer(a1,a2|K,G,C) rather than Score(a) ... section24 the default Lord policy ... section54 commissions 025H (Sarathi)"
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle
last_seen: S1500. Candidate lifecycle: CONTESTED.
Evidence: contested_by_own_contradiction_type: true

## Completeness roll-up
| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1486 |
| type_signature | PRESENT | S1486 |
| invariants | PRESENT | S1486 |
| dependencies | PRESENT | S1486, S1499, S1500 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | PRESENT | S1486 |

## Rationale
NOT-EVIDENCED-IN-CAPTURE

## Assumption register
NOT-EVIDENCED-IN-CAPTURE

## All rows (source_id order)

- [S1486] types=['FORMALIZATION', 'CORRECTION'] scope=THEORY-LEVEL — "Step-025g (file 183746) formalizes Lord(K_t,Z_t,G,C) -> a_t, splitting Action into EpistemicAction (q: K_t -> K_{t+1}) and OperationalAction (a: W_t -> W_{t+1}) under the rule 'Lord selects; Governance authorizes; Executor executes'; rejects a^*=argmax VOI(a) as insufficient and instead defines a nine-dimension action utility vector A=(Purpose,ExpectedGain,Cost,Risk,Urgency,Reversibility,Authorization,Dependencies,Deadline), explicitly refusing a collapsed scalar Score(A) that 'would hide semantics'; uses a two-stage feasibility-then-optimization algorithm and root-gap analysis (graph reasoning, not LLM intuition); offers an optional statistical expected-utility engine but favors a qualitative preference relation Prefer(a1,a2|K,G,C) (deliberately arg-prefer, not arg-max-Score, 'we have not yet established that these dimensions can legitimately be collapsed'); states Capability != Authority; states the default Lord policy verbatim ('prefer the least risky authorized action that maximally reduces decision-critical Zero, subject to cost, dependencies and deadlines'); defines a termination algebra {Completed,Blocked,Escalated,Abandoned,Expired} with DoNothing in ActionSpace and 'no action is preferable to an unjustified action'; runs five experiments and six falsification passes, including the finding that high information value cannot override governance and that an LLM recommendation cannot override Zero/Contract; and commissions Sarathi (step-025H) as the next research item, delivered the following day outside this batch." (anchor: "D15. step-025g (183746) -- Formal Lord Algebra ... section6 action utility vector A=(Purpose,ExpectedGain,Cost,Risk,Urgency,Reversibility,Authorization,Dependencies,Deadline) ... section19 Prefer(a1,a2|K,G,C) rather than Score(a) ... section24 the default Lord policy ... section54 commissions 025H (Sarathi)")
- [S1486] types=['LIMITATION', 'OPEN-QUESTION'] scope=THEORY-LEVEL — "Lists seven gaps remaining at the end of the batch: no aggregation operator selected; evidence identity unresolved; circular derivation handling unresolved; the EC naming issue (Epistemic-Governance Contract vs AssuranceContract) left open; Zero's contract-construction machinery (ContractDerivation, RequirementGraph) handed through 025e/025f to a chain that terminates in an acknowledged human-governance boundary rather than a computed resolution; no executed property-based test of the knowledge-state machine itself (only the evidence algebra was empirically tested, and that CSV lives at a non-repository sandbox path); and Sarathi remains entirely unformalised in this batch, commissioned at step-025g section 54 as step-025H but actually delivered the following day (2026-08-28), meaning any Sarathi record must be sourced from batch B5, not B4." (anchor: "GAPS AT BATCH END ... 4. EC naming issue open ... 5. Zero's EC construction ... The chain terminates in an acknowledged human boundary, not a computed one. ... 7. Sarathi is unformalised in this batch -- commissioned at 025g section54 as step-025H, delivered on 2026-08-28.")
- [S1499] types=['CONTRADICTION', 'LIMITATION'] scope=THEORY-LEVEL — "Identifies the sharpest intra-band contradiction (VA-2): step-025g's action-selection operator a* = arg-prefer over a six-dimensional codomain (GapReduction, Safety, Authorization, Cost, Urgency, Reversibility) is not a well-defined operator -- no order, lexicographic priority, Pareto rule, or aggregation is given, which the source itself concedes ('we have not yet established that these dimensions can legitimately be collapsed'); yet the very next section's worked ranking step implicitly counts gaps (GapReduction(a3) > GapReduction(a1) because a3 closes two gaps versus one), directly contradicting step-025d's own explicit claim that gaps 'are not necessarily comparable' and that 'Zero is primarily a structured object, not a scalar.' The result is that Lord's central ranking mechanism depends on exactly the operation Zero's own formalization declares undefined or forbidden." (anchor: "25G.24 (verbatim): a* = arg prefer_{a in A} [GapReduction, Safety, Authorization, Cost, Urgency, Reversibility] -- NOT a well-defined operator. ... 25G.36 step5 "Rank by decision-critical gap reduction" ... implicitly counting gaps, which directly contradicts 25D.17 ("the six missing items are not necessarily comparable... Zero is primarily a structured object, not a scalar"). This is the sharpest intra-band contradiction (VA-2).")
- [S1499] types=['CONTRADICTION'] scope=THEORY-LEVEL — "Catalogues operator signature drift within the same 14-file band: the knowledge-state transition operator surfaces in seven distinct forms (Update, Revise, Revision, F, T, a second Revision, a second Update) never citing the base corpus's delta:K x O_K -harpoon-> K; Lord's output type drifts from a generated set of possibilities (step-025b) to an optimizer's argmax ratio (step-025c-3) to a policy-governed single-action selector emitting a DecisionState record (step-025g); Sarathi is given two incompatible arities within the single file step-025b and is never formalized anywhere in the band; the Commit predicate and the Assessment/evidence-state object each surface in multiple incompatible shapes (Assessment alone has seven distinct shapes); and the same symbol (direct-sum) denotes evidence combination in one file and 'structured collection, not numerical addition' in another." (anchor: "(b) Operator signature drift ... Revise/Revision/F/T/Update: seven surface forms for the transition operator; base delta: K x O_K -harpoon-> K never cited. ... Lord ... Output type drifts set->scalar-choice->record.")
- [S1500] types=['CONTRADICTION'] scope=THEORY-LEVEL — "Documents contradiction VB-1: the 19-file band opens (step-025h) with Sarathi as the deciding agent (a five-way DecisionResult codomain including Decision itself) and Lord as the next-action selector, but closes (step-025z) with Lord explicitly named 'Epistemic Decision and Action Orchestrator' owning Decide among its options, while Sarathi is demoted to specializing in Reasoning/Planning/Interpretation/OptionGeneration only -- a full role reversal relative to the band's own opening file, pivoting through step-025r's remark that the Lord/Sarathi boundary 'is a DDD design decision', with no file ever acknowledging the swap occurred." (anchor: "VB-1 (Lord/Sarathi role swap): 025h section28 vs 025z sections68-69, pivot at 025r section35 -- the batch's opening decision-owner (Sarathi) ends as an option generator, with Lord owning Decide; never flagged.")

## Notes for P3
- No internal tension, evidentiary anomaly, or lifecycle-flag discrepancy observed in this label's own rows beyond what the completeness roll-up above already shows.
