# METHOD — independent implementation of SPEC-G25-r1

Implementation: `verifier_g25.py` (Python 3, standard library only). Run: `python3 verifier_g25.py results.json`. The run is deterministic: states, kinds and search orders are fixed, and there is no randomness.

## 1. Method

1. **Instances.** For each instance, ≤ is the reflexive-transitive closure of the stated covers (Warshall), and ⊥ is the unique minimal element (asserted). Bars are bitmasks. Under A0 the bar domain is the set of up-sets of ≤; otherwise it is all subsets.
2. **State space.** The state space is the full product p × s × e × g × u × au × ad × rv over the admissible bar domain. The "state count" is the size of this product.
3. **Maximal step relation.** For each axiom set Σ, every triple (x, κ, x′) with κ ∈ {GOV, EVID, EVIDREF, WORK, COMP} is tested against every axiom in Σ, and the relation keeps exactly the triples that satisfy all of them. The code splits the test into two parts:
   - a base part over (p, s, e, g, u) using A1–A6;
   - an added part over (e, u, au, ad, rv) using the T-axioms.

   Base axioms never mention the flags, and T-axioms mention only e, u, the flags and κ, so the conjunction of the two parts is exactly the full test. The only thing candidate generation skips is p′ ≠ p when A1 ∈ Σ, and u′ ≠ u when A6 ∈ Σ. Those candidates would be rejected by the same axiom anyway.
4. **Reachability.** Reachability uses a multi-source BFS from all fresh states (au = ad = rv = 0, with p, s, e, g, u arbitrary), using all kinds.
5. **Universal step properties** (AUTH-\*, EVENT-\*, AUTO-INVAL, REVOC-EXPLICIT):
   - Every step whose source is reachable is examined, visiting sources in BFS order.
   - FAILS: the first violating step found is a shortest violation. The witness is the BFS path to the source plus the violating step.
   - VACUOUS: no reachable step is in the relevant set.
   - HOLDS: otherwise.
6. **Existential and pattern items** (TOCTOU, REVAL-a/b, §4 scenarios) use a 0-1 BFS on the product of states × pattern phase:
   - Step elements advance the phase when a step matches.
   - State elements advance the phase at zero cost when the current state satisfies them.
   - Advancing is optional (nondeterministic), so the search finds the shortest trajectory, counted in steps, among all trajectories that realise the pattern.
7. **Other searches.**
   - PERSIST: plain BFS.
   - REP and P-GUARD: BFS from their source sets with restricted kinds.
   - D1 and D2: BFS from all states of the state space that satisfy the premise, with restricted kinds.
   - D6: scans every step of the relation.
8. **Ablation.** Each single axiom (base or added) is removed from the model's full set, and everything in §3 is recomputed from scratch, including the state space when A0 is removed.
9. **Minimal sets.** For every subset T of the model's added axioms, Σ = base ∪ T is evaluated from scratch. For each property whose full-model class is HOLDS or VACUOUS, the code collects every T under which the property still holds and keeps the inclusion-minimal ones. It does not assume monotonicity; the minimal sets come from brute force over all 2^|added| subsets. An added axiom is redundant if it appears in no minimal set of any property for that model and instance.

## 2. Exactness argument

- **Finite and fully enumerated.** All domains are finite. The state space is enumerated completely, at most 6144 states (diamond without A0). The step relation is decided by testing every candidate triple against every axiom in Σ. Nothing is sampled, and there are no heuristics or bounds on trajectory length. BFS explores the entire reachable graph (or product graph), so "not reachable" results are exhaustive.
- **Universal properties are decided on the maximal relation.** Here is why that is exact:
  - For "holds under Σ" (§1), every step relation R satisfying Σ is a subset of the maximal relation R\* on the same state space.
  - So R's reachable states and reachable steps are subsets of R\*'s. If the property holds on R\*, it holds on every R.
  - Conversely, R\* itself satisfies Σ (the axioms constrain single steps). A violation in R\* is therefore a violation in an admissible relation.
  - D1, D2 and D6 quantify over all states or steps, and the same subset argument applies.
  - Existential items are evaluated on R\*, as §1 prescribes.
- **Shortest witnesses.** BFS, and 0-1 BFS with zero-cost state tests, return minimum-step witnesses. Ties are broken deterministically by the enumeration order.

## 3. Interpretation choices (declared)

1. **A0 domain.** A0 restricts the state space: every state, including every reached state, has an up-set bar. Without A0 the bar domain is all subsets. The state count therefore depends on Σ.
2. **Unconstrained components.** Components not constrained by Σ may take any value. p and s are part of the state and range freely in fresh and start states ("p and s free").
3. **Multi-clause axioms.** T-P1 and T-P0 (three clauses each), and A3m (two clauses), are each treated as one axiom for ablation and minimal sets.
4. **Events.** An AuthEvent is a step with au 0→1, and a PromEvent is a step with ad 0→1. EF, E0 and EB are evaluated at the source state of the event step, as in §3.
5. **VACUOUS sets.** A universal item is VACUOUS when its relevant set of reachable steps is empty. The relevant sets are:
   - AuthEvents (or PromEvents);
   - for the -scoped variants, AuthEvents (PromEvents) with ¬EB at the source;
   - AUTO-INVAL: reachable steps from ad = 1 into a ¬EF target;
   - REVOC-EXPLICIT: reachable steps with ad 1→0.

   D1, D2 and P-GUARD would be VACUOUS only if their start set were empty.
6. **TOCTOU.** This asks for an AuthEvent at step i, a state x_j with j ≥ i+1 satisfying ¬EF, and a PromEvent at step k ≥ j. The ¬EF state may be the source of the PromEvent.
7. **AUTO-INVAL** quantifies over reachable steps x → x′ with ad(x) = 1 and ¬EF(x′), and requires ad(x′) = 0.
8. **REVAL-a/b.** The start state y is any reachable state with au = 1, ad = 0 and ¬EF. The PromEvent may be the first step out of y.
   - REVAL-a forbids EVID steps from y onward, including the PromEvent step itself. Steps before y are unrestricted.
   - The reported witness is the shortest full trajectory from a fresh state through y to the PromEvent. `pattern_positions` gives y's index and the PromEvent step index.
9. **REP** is evaluated for chain3 only (other instances: "N/A"). "u constant" is enforced by only using steps with u′ = u, even when A6 is ablated. p and s are free in the start states.
10. **P-GUARD.** Start states are fresh states with e = ⊥ and ⊥ ∉ u (p, s, g free). The allowed kinds are all except EVID. HOLDS iff no state with ad = 1 is reachable.
11. **D1 and D2** quantify from every state of the state space satisfying the premise, not only reachable or fresh states. Trajectories use only the stated kinds; a zero-step trajectory does not count. **D6** quantifies over every step of the maximal relation, from any state.
12. **Existential classes.** Existential items are reported as REACHABLE or NOT_REACHABLE, with a shortest witness. P-GUARD is reported as HOLDS or FAILS, per its "(HOLDS iff not)" clause.
13. **Scenarios.** All scenarios use chain3 and each model's full axiom set (T5: full minus A6). Start states are fresh, with the given e, g = 0, u = {n2}, and p and s free. The ordered pattern is realised by distinct, strictly ordered steps (i < j < k; T1: i < k). This is reported as `strict_order`. As a supplementary reading, `same_step_allowed` also lets one step satisfy consecutive pattern elements.
    - T2's "raising e" means e < e′ strictly, on an EVID step.
    - T3 and T4: an EVIDREF step whose target has the stated e.
    - T5: any step with u′ ≠ u.
    - T6: an EVID step whose target satisfies e ∈ u, with no PromEvent step at any index before that EVID step.
    - T1's "no EVID/EVIDREF step in between" is implied by allowed kinds = {GOV, WORK}.

    At t_a and t_p the file reports e, u, E0, EB (and EF) of the event step's source state, plus the step indices.
14. **Ablation output.** Ablation entries report counts plus each property's class and witness length. Full witnesses are given only for the unablated models.
15. **Minimal sets and redundancy.**
    - Minimal sets are computed for universal items (including P-GUARD, D1, D2, D6) that hold (HOLDS or VACUOUS) in the full model.
    - As a clearly labelled supplement, `minimal_sets_existential_not_reachable_supplementary` gives minimal sets for existential items that are NOT_REACHABLE in the full model, where the goal is to keep them not reachable.
    - `redundant_added_axioms` uses only the primary minimal sets. A second list also counts the supplementary sets.
16. **"Reachable count with ad = 1"** is the number of reachable states with ad = 1.

## 4. Independence declaration

> "I, Claude Opus 5.5 (model id: claude-opus-5-5), headless Claude Code session, non-git directory (SECONDARY_REVIEW), implemented SPEC-G25-r1.md without access to any other implementation, result, report or discussion of it. My interpretation choices are listed in METHOD.md."
