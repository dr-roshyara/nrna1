# METHOD — independent implementation of SPEC-G2-r2

Implementation: `verifier_g2.py` (Python 3, standard library only). Run: `python3 verifier_g2.py results.json`.

## 1. Method

For every (model, instance, axiom set Σ) the program builds:

1. **The explicit finite state space.** Components are p ∈ {gen, der}, s ∈ {auth, prov, hist}, e ∈ E, g ∈ {0,1}, u ∈ Bars, plus the model's extra components (b ∈ Bars for M0b; x, v ∈ {0,1} for M1; m ∈ {NONE, RULE, EXC}, ad ∈ {0,1} for M2; au, ad ∈ {0,1} for M3a/M3b). Bars = the up-sets of E if A0 ∈ Σ, otherwise all subsets of E. The state space is the full Cartesian product of these domains.
2. **The maximal step relation** R_Σ = every triple (x, κ, x′) with x, x′ in the state space, κ ∈ {GOV, EVID, EVIDREF, WORK, COMP}, that satisfies every axiom in Σ. Each axiom is coded directly as a predicate on (source, kind, target). Targets are enumerated component by component with early pruning: an axiom is checked as soon as all the target components it reads have values. Pruning only drops partial assignments that already break an axiom, so the set of generated successors is exactly the set of targets that satisfy Σ. This is the same as filtering the whole product; it is just faster.
3. **Deciding properties** with exhaustive graph algorithms over R_Σ: multi-source breadth-first search (BFS), a full scan of states/edges, and BFS over the product of states with small flag automata (for "trajectory contains a step of kind …"). A BFS whose sources are all the start states, and which stops at the first goal node it dequeues, returns a **shortest** witness (fewest steps). Ties are broken deterministically by state enumeration order and kind order (GOV, EVID, EVIDREF, WORK, COMP).

Details for each property (Σ-maximal relation, "all kinds" = all five kinds, and A4 is what forbids COMP):

- **Fresh state**: extras at their initial values (0 / NONE; b = u), with p, s, e, g, u arbitrary. **Fresh start**: a fresh state with e ∉ u, g = 0 and ¬N. **Reachable**: BFS from all fresh states using all kinds.
- **D1**: sources = all states with e ∉ u; edges = GOV only; goal e ∈ u. **D2**: sources = all states with g = 0; edges = EVID/EVIDREF/WORK; goal g = 1. **D6**: scan every edge for p′ ≠ p. **D5[N]**: scan every EVID edge out of every N-state (whole state space) for ¬N′.
- **D3-history[X]** (X = N or PS): BFS over (state, flags) from the fresh starts. flag bit 0 = "an EVID or EVIDREF step has occurred", bit 1 = "a GOV step has occurred". Goal: X holds and the flags are not both set. **D3+[N]**: the same, but bit 0 is set only by EVID. The GOV requirement is kept (reading: "same" means the definition is unchanged except that "EVID-or-EVIDREF" becomes "EVID").
- **NV[N]**: BFS from the fresh starts to any N-state.
- **PromotionEvents**: every GOV edge x → x′ with x reachable, ¬N(x) and N(x′). The witness is the BFS path to x plus the event step; the event with the shortest prefix is chosen.
- **bar/floor event and inv**, **P-BAR**: scan the reachable states / events in BFS order. The first violation gives a shortest witness.
- **P-GUARD**: BFS from the fresh starts with e = ⊥, using all kinds except EVID, to any N-state.
- **P-PERSIST**: scan the EVIDREF edges out of the reachable N-states.
- **Scenarios** (§4): multi-source BFS. Sources are the fresh states with the given e, u = {n2}, g = 0, and every p and s. Every state on the path must satisfy the path requirement (node filter). "≥ 1 GOV step" (S2) uses a 1-bit flag automaton.
- **Ablation**: rebuild the system with Σ = full \ {a} for every base and added axiom a (A0 included; removing it enlarges the state space to all subsets), then re-evaluate every §3 property. The output records its class and the counts.
- **Minimal sets**: evaluate all 2^k subsets of the model's added axioms (base axioms always kept). For each property whose value is true under the full set, the inclusion-minimal subsets with value true are listed. **Redundant added axioms** = added axioms that appear in no listed minimal set.

## 2. Exactness argument

- Every state space is finite and is enumerated completely: the largest one is 12,288 states (M0b on diamond with A0 ablated). Every step relation is computed completely, as argued above. Nothing is sampled, and no abstraction or symmetry reduction is used. p and s are kept explicit even though they are nearly inert.
- **Why the maximal relation decides "holds for every step relation whose steps all satisfy Σ".** Every universal property here (D1, D2, D6, D3-*, D5, D3-state-*, P-GUARD, P-BAR, P-PERSIST) is the negation of "there exists a finite path or step in the relation with pattern π". Those existence statements are monotone in the relation: a subset of R_Σ has fewer paths. So such a property holds for every admissible relation exactly when it holds for R_Σ. A violation found in R_Σ is a violation for the relation R_Σ itself, which is admissible. The fresh-state, fresh-start and state-space sets do not depend on the relation.
- BFS over a finite graph is complete (it visits every reachable node) and finds shortest paths. Product constructions with finite flag sets preserve both. Scans are over explicit finite sets.
- The program is deterministic: fixed enumeration orders, and no hashing-dependent iteration affects the output.

## 3. Interpretation choices (declared)

1. **Existential items** (NV[N]; every "reachable?" scenario query; S4) are evaluated on the maximal relation R_Σ, i.e. read as "possible under Σ". Read literally as "for every relation", they would be trivially false because of the empty relation.
2. **Bars and A0.** With A0, u (and b in M0b) range over the up-sets in every state, both sources and targets. Without A0 (ablation), they range over all subsets. A0 is therefore coded as a restriction on the state space, not as a step predicate.
3. **"Any state"** in D1, D2 and D5 (and "any state" in S4) means every state of the state space, not only the reachable ones. For M0b this includes states with b ≠ u.
4. **Missing ⊥** (antichain2): "e ≠ ⊥" is taken to be true for every e, and "e = ⊥" is false. So P-GUARD is VACUOUS there (it has no start states), and the floor properties reduce to "no violation possible". M1, M2 and M3b are NOT_APPLICABLE on antichain2, as the spec states. For those, all properties are output with class NOT_APPLICABLE and nothing is computed.
5. **Classes.** A property of the form "for all o ∈ R: φ(o)" is VACUOUS (value true) if R is empty, FAILS (with a shortest witness) if some o violates φ, and HOLDS otherwise. The relevant set R for each property:
   - D1: states with e ∉ u.
   - D2: states with g = 0.
   - D6: all steps.
   - D3-history[X] and D3+[N]: trajectories from fresh starts that reach X. So the property is VACUOUS when X is unreachable from the fresh starts.
   - D5: EVID steps out of N-states.
   - bar-event: reachable PromotionEvents.
   - floor-event: reachable PromotionEvents with e′ ∉ u′.
   - bar-inv: reachable N-states.
   - floor-inv: reachable N-states with e ∉ u.
   - P-GUARD: fresh starts with e = ⊥.
   - P-PERSIST: EVIDREF steps out of reachable N-states.
   - P-BAR, record models (M1, M2): reachable states with N ∧ e ∉ u.
   - P-BAR, models without records: following the spec's explicit rule ("holds iff no such state is reachable"), it is HOLDS or FAILS and never VACUOUS.
   - NV[N] is existential, so it is HOLDS (with an example trajectory as its witness) or FAILS (no witness, because none exists).
6. **"Holds" for minimal sets** means the value is true, so HOLDS and VACUOUS both count. The candidate properties are those whose value is true under the full set, including NV[N] where it holds.
7. **Scenario start g.** The spec fixes only e and u and leaves p and s free. It does not say what g is. I take **g = 0** at the start, in line with the fresh-start convention. The extras are fresh (b = u = {n2}).
8. **Scenario path requirements** are enforced on **every state** of the path, the start included: "u constant" means u = {n2} throughout, and "b constant" means b = {n2} throughout (M0b only; it is meaningless elsewhere). "No exception record is ever set" means x = 0 throughout (M1) and m ≠ EXC throughout (M2). The other models have no record, so the requirement is vacuous. S2 has two queries, N and PS, each with ≥ 1 GOV step on the path.
9. **S4** is answered on the full axiom set. It asks whether any step from any state changes u (and, for M0b, b), and gives one step as the witness if so.
10. **S4-T.** For M0 the system is rebuilt without A6, u is free and there is no u filter. For M0b it is rebuilt without B6, b is free and the path must keep u = {n2}. For the other models the output is "NOT_RUN". **S5**: only M1 is evaluated; the others output "NOT_REPRESENTABLE".
11. **Unprimed variables in added axioms** (X2, Y2, Y2b, Y3, W2) refer to the source state (for example, e ∈ u in Y3 means the source's e ∈ u), as §2 states.
12. **Kinds.** Every step carries exactly one kind, and all five kinds exist in every model. COMP is excluded only by A4, so removing A4 in ablation admits COMP steps constrained only by the remaining axioms. The X1/Y1/W1 premises compare target with source for the named components only.
13. **Self-loops** (x → x under a kind that permits it) are ordinary steps. They never create PromotionEvents and never shorten witnesses.
14. **State count** in the output is the size of the full-axiom-set state space (the product over admissible bars). Reachable counts are with respect to the fresh states. The ablation entries report the corresponding counts for each reduced axiom set.
15. **Witness format**: `{length, states[0..length], kinds[0..length-1]}`. Each state lists p, s, e, g, u (as a list of element names) and then the extras.

## 4. Independence declaration

> "I, Claude Opus 5.5 (model id: claude-opus-5-5), headless Claude Code session, non-git directory (SECONDARY_REVIEW), implemented SPEC-G2-r2.md without access to any other implementation, result, report or discussion of it. My interpretation choices are listed in METHOD.md."
