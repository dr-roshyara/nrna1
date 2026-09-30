# METHOD — independent implementation of SPEC-G2-r2.md

Implementation: `verifier_g2.py` (Python 3, standard library only). Run: `python3 verifier_g2.py results.json`. The output is deterministic: two runs gave byte-identical `results.json`.

## 1. Method

For every (model, instance, axiom set) the program builds the **complete finite labelled transition graph**:

- **States:** the full Cartesian product P × S × E × G × Bars × (added component domains). Bars = the up-sets of E when A0 is in the axiom set, otherwise all subsets of E. For M0b the component `b` ranges over the same Bars domain. Bars are stored as bitmasks.
- **Steps:** for every state x and every kind κ ∈ {GOV, EVID, EVIDREF, WORK, COMP}, the set of all targets x′ such that (x, κ, x′) satisfies every axiom in the set. This gives the **maximal** step relation R_Σ allowed by Σ.
- **Properties** are decided on R_Σ by exhaustive enumeration: over all states, all steps, or all reachable states and steps, or by multi-source breadth-first search (BFS) restricted to the kinds each property allows.
- **Shortest witnesses** come from BFS, since the first target BFS discovers is at minimum distance. Ties are broken deterministically by state enumeration order and the kind order GOV, EVID, EVIDREF, WORK, COMP. For event and successor properties (x →κ x′ with x reachable), the witness is a shortest path from a fresh state to x plus the step. States are scanned in BFS order, so the witness minimises the length of that prefix, which also minimises the total length.

The successor sets are built as a product. Each base component gets its own candidate set, taken directly from the base axioms that constrain it (A1, A2e, A3g, A3s, A3m, A5e, A5g, A5s, A6; A4 empties COMP). The added-component tuples come from filtering every tuple against the active added axioms. This is exact because every base axiom constrains one primed base component at a time, and every added axiom reads only the source state and the primed *added* components (checked by reading each predicate). As an independent check, the program compares these successor sets with a brute-force enumeration over all (x, κ, x′) triples using one full-axiom predicate. It does this for 21 configurations on chain3: all 6 models under their full sets, all 11 single-axiom ablations of M0, and the 4 added-axiom ablations of M1. All 21 matched; the list is recorded in results.json under `selftest_successor_generation_vs_bruteforce`.

## 2. Exactness argument

All domains are finite and small, with at most 12 288 states in one configuration. Every state is enumerated, every step allowed by Σ is materialised, and every quantifier in §3/§4 becomes a finite loop or a BFS that runs to a fixpoint over a finite graph. No sampling, randomisation or bounded-depth cut-off is used. BFS explores the complete reachable subgraph, so "no path exists" answers are exact. The universally quantified properties (D1, D2, D6, D3-*, D5, P-*) are safety or "every trajectory" conditions. They are preserved when steps are removed, so deciding them on the maximal relation R_Σ is equivalent to deciding them for **every** step relation whose steps satisfy Σ (the §1 definition). §5 enumerates all 2^k subsets of the added axioms exhaustively. It does not assume the properties are monotone in the axioms.

## 3. Interpretation choices (declared)

1. **"Holds under Σ" / existential items.** Universal properties are evaluated on the maximal relation R_Σ (equivalent, see §2). NV[N] and the §4 reachability queries are existential. Under "every relation" they would trivially fail because of the empty relation, so they are read as "some trajectory exists in R_Σ", i.e. some relation satisfying Σ admits it.
2. **A0** is a state-space condition: it applies to both endpoints of every step and to all initial, fresh and reached states. Without A0, u (and b in M0b) range over all subsets. The other axioms are step predicates, and removing one only removes that predicate.
3. **"Any state"** (D1, D2, D5, D6, S4) means any state of the configuration's full state space, with added components arbitrary (not necessarily fresh or reachable).
4. **Reachable** = reachable, in zero or more steps of any allowed kind (COMP only if A4 is removed), from any fresh state with any p, s, e, g, u. For M0b, fresh means b = u.
5. **Fresh starts** = fresh states with e ∉ u, g = 0 and N false. p and s are arbitrary.
6. **Trajectory kinds.** "Trajectory" without a restriction may use every kind the axiom set allows (COMP only when A4 is ablated). "GOV-only" and "{EVID, EVIDREF, WORK}-only" exclude every other kind, including COMP. P-GUARD's "without an EVID step" allows all kinds except EVID.
7. **D3-history[T]** fails iff some trajectory from a fresh start reaches T with no EVID/EVIDREF step, or reaches T with no GOV step. It is enough to check prefixes that end at the first T-state. **D3+[N]** is read as "≥ 1 EVID step **and** ≥ 1 GOV step", i.e. only the EVID-or-EVIDREF requirement becomes EVID. The witness is the shorter of the two violating kinds of path (the "missing EVID(/REF)" kind wins ties).
8. **VACUOUS** = the universally quantified collection is empty:
   - D1: no state with e ∉ u.
   - D2: no state with g = 0.
   - D6: the step relation is empty.
   - D3-history / D3+: no trajectory from a fresh start reaches the notion (or no fresh start exists).
   - D5: no N-state has an EVID successor.
   - bar-event: no reachable PromotionEvent.
   - floor-event: no reachable PromotionEvent with e′ ∉ u′.
   - bar-inv: no reachable N-state.
   - floor-inv: no reachable N-state with e ∉ u.
   - P-GUARD: no fresh start with e = ⊥.
   - P-PERSIST: no (reachable N-state, EVIDREF successor) pair.
   - P-BAR, models with a record (M1, M2): no reachable N ∧ e ∉ u state.
   - P-BAR, models without a record: the spec's explicit rule applies ("holds iff no such state is reachable"), so it is reported as HOLDS with a note, never VACUOUS.
   - In results.json, `holds` is true for both HOLDS and VACUOUS.
9. **NV[N]** is HOLDS or FAILS. If no fresh start existed it would be FAILS (an empty existential), with a note. When it holds, a shortest reaching trajectory is given as `example`.
10. **⊥ on antichain2.** ⊥ does not exist there, so "e ≠ ⊥" is true for every e. The floor properties therefore cannot fail there, and P-GUARD is VACUOUS (no start with e = ⊥). M1, M2 and M3b are reported NOT_APPLICABLE on antichain2, for every property.
11. **A PromotionEvent is reachable** iff its source state x is reachable. Such events are GOV steps x → x′ with N(x) false and N(x′) true.
12. **Scenarios §4** (chain3):
    - Start states are fresh, with the given e, u = {n2}, **g = 0**, and p, s free (all 6 combinations are sources). g is not stated; g = 0 is taken from the fresh-start convention, so that a query is never answered by the start state alone. For M0b, b = u = {n2}.
    - "u constant" / "b constant" are already enforced by A6 / B6 under the full set.
    - **S1**: every state on the path must have x = 0 (M1) or m ≠ EXC (M2). The start is included.
    - **S2**: searched on (state, GOV-seen) pairs; the target must be reached after ≥ 1 GOV step. Answered separately for N and for PS.
    - **S4**: any step (any state, any kind) of the full relation that changes u. For M0b, also one that changes b.
    - **S4-T**: M0 uses full minus A6, keeping A0 so u stays an up-set, and u may change along the path. M0b uses full minus B6 (b is free within admissible bars; A6 is kept). Other models: `NOT_RUN`.
    - **S5**: M1 only; the others are `NOT_REPRESENTABLE`.
    - **S6**: target N ∧ e ∉ u.
    - Every positive answer carries one shortest witness.
13. **Witnesses** are given for every failing property under the full axiom set **and** under every single-axiom ablation. Evaluations used only for the minimal-set computation record only the truth value (`added_axiom_subsets_truth`).
14. **Ablation** removes each single axiom (all 11 base axioms, including A0 and A4, plus each added axiom) and recomputes all §3 properties and counts.
15. **Minimal sets.** "Holds" means class HOLDS or VACUOUS, both under the full set and under subsets. For each property that holds under the full set, all 2^k subsets of the model's added axioms are evaluated with the base kept, and the inclusion-minimal subsets where it still holds are listed. For M0 (no added axioms) the only candidate is ∅. **Redundant added axioms** = added axioms that appear in no minimal set of any property that holds under the full set.
16. **State count** = size of the configuration's state space. "Reachable states" and "reachable N-states" are counted as defined in item 4.

## 4. Declaration

> "I, Claude Opus 5.5 (model id `claude-opus-5-5`), headless Claude Code session, non-git directory (SECONDARY_REVIEW), implemented SPEC-G2-r2.md without access to any other implementation, result, report or discussion of it. My interpretation choices are listed in METHOD.md."
