# METHOD.md — Independent Verification of H-F2-1-R

## Independence declaration

"I, DeepSeek (DeepSeek-V3), implemented SPEC.md independently without access to any other implementation, result, report, or discussion of H-F2-1-R. The implementation was not executed because execution was unavailable; therefore no results are claimed. The interpretation choices are documented in METHOD.md."

## The central logical point

The specification quantifies each proposition over **every step relation R whose steps all satisfy the axiom set Σ**. This is a universal quantifier over a family of relations. I do not enumerate that family. I show, for each proposition separately, that the truth value of the proposition over the whole family equals its truth value on a single canonical element of the family — the maximal admissible relation R_max (all atomic steps satisfying every axiom in Σ). The proposition-by-proposition argument is below.

R_max is finite: it is a subset of `states × KINDS × states`, and `states` has at most 768 elements per instance. Constructing R_max is therefore a finite, exact operation.

## Per-proposition reduction to R_max

Throughout, let Σ be an axiom set, R_max the maximal relation whose steps satisfy Σ, and R any sub-relation of R_max whose steps satisfy Σ.

**D1** — "from any state with e ∉ u, no trajectory of GOV steps only reaches a state with e ∈ u."
The property is: the set of states reachable from a state with e ∉ u via GOV-only steps contains no state with e ∈ u. Reachability is monotone in the edge set: if R ⊆ R_max, every GOV-only trajectory of R is a GOV-only trajectory of R_max. Therefore, if R_max has no e ∈ u reachable from an e ∉ u start via GOV-only steps, then neither does R. Conversely, if R_max does have such a trajectory, take R = R_max. So D1 holds for every R iff D1 holds for R_max.

**D2** — "from any state with g = 0, no trajectory of {EVID,EVIDREF,WORK} steps only reaches g = 1."
Identical monotonicity argument, with the edge set restricted to the three kinds. If R_max has no g = 1 reachable from a g = 0 start via these kinds, no sub-relation does. So D2 holds for every R iff it holds for R_max.

**D3** — "every trajectory from a state with e ∉ u and g = 0 that reaches a Promote state contains ≥ 1 step of kind EVID or EVIDREF and ≥ 1 GOV step."
The property is: every trajectory that reaches Promote has two "marks." Adding edges can only add trajectories; it cannot remove a mark from an existing trajectory. So if R_max has no Promote-reaching trajectory missing a mark, no sub-relation does either. The negation of "contains ≥1 EVID/EVIDREF and ≥1 GOV" is "contains 0 EVID/EVIDREF, or contains 0 GOV." A countermodel is therefore a trajectory to Promote entirely inside the edge set `KINDS \ {EVID, EVIDREF}`, or entirely inside `KINDS \ {GOV}`. I test both avoid-sets by BFS on R_max restricted to that kind set; if neither reaches Promote, D3 holds for every R. If either reaches Promote, that BFS path is a countermodel in R_max, and choosing R = R_max makes D3 fail for that R.

**D3+** — same statement with "≥1 EVID" replacing "≥1 EVID or EVIDREF." The avoid-sets become `KINDS \ {EVID}` and `KINDS \ {GOV}`. Same monotonicity argument, same exactness.

**D5** — "if Promote(x) and x →EVID x′, then Promote(x′)."
This is a universal statement over the EVID edges of R. For any sub-relation R ⊆ R_max, the set of EVID edges of R is a subset of the EVID edges of R_max. If every EVID edge of R_max starting at a Promote state ends in a Promote state, then the same is true of every subset of those edges. Conversely, if some EVID edge of R_max violates the property, then R = R_max witnesses the failure. So D5 holds for every R iff it holds for R_max.

**D6** — "every trajectory keeps p constant."
A trajectory keeps p constant iff every one of its steps keeps p constant. So D6 holds for R iff no step of R changes p. If no step of R_max changes p, no subset of steps changes p, and D6 holds for every R. If some step of R_max changes p, then R = R_max witnesses the failure. So D6 holds for every R iff it holds for R_max.

**NV** — "there exists R satisfying Σ and a trajectory in R from some state with e ∉ u, g = 0 to a Promote state."
This is existential over R. If the property holds for some R, then because R ⊆ R_max, the witnessing trajectory is also a trajectory in R_max, so the property holds for R_max. Conversely, if it holds for R_max, take R = R_max. So NV holds iff it holds for R_max.

**Conclusion.** For every one of D1, D2, D3, D3+, D5, D6, NV, the truth value of the proposition over the entire family of admissible R equals its truth value on R_max. Checking R_max is therefore not an approximation; it is logically equivalent to the specification's quantification. No sampling is involved.

## Constructing R_max

For each state `x = (p, s, e, g, u)`, each kind `κ`, and each axiom set Σ, the function `succ` in `verifier.py` enumerates the set of states `x′` such that the atomic step `x →κ x′` satisfies every axiom in Σ. This enumeration is a finite product: the domains of `p′`, `s′`, `e′`, `g′`, `u′` are determined by which axioms are present, and each candidate is tested against the constraints of `A2e`, `A3m`, `A5e`, and `A6` as appropriate. The union over κ and x is R_max.

## Checking the propositions on R_max

- **D1** — multi-source BFS on GOV edges from all `x` with `e ∉ u`; failure when a reached state has `e ∈ u`.
- **D2** — multi-source BFS on `{EVID, EVIDREF, WORK}` edges from all `x` with `g = 0`; failure when a reached state has `g = 1`.
- **D3** — multi-source BFS on each avoid-set (`KINDS \ {EVID, EVIDREF}`, `KINDS \ {GOV}`) from all `x` with `e ∉ u` and `g = 0`; failure when a reached state is Promote.
- **D3+** — same with avoid-sets `KINDS \ {EVID}` and `KINDS \ {GOV}`.
- **D5** — direct enumeration of all EVID edges of R_max starting at a Promote state.
- **D6** — direct enumeration of all edges of R_max.
- **NV** — multi-source BFS on all kinds from all `x` with `e ∉ u` and `g = 0`; success when a reached state is Promote.

Countermodels are the shortest BFS paths, reconstructed from parent pointers. Each emitted step is an element of R_max and therefore satisfies every axiom in Σ by construction.

## Minimal sets

For each proposition in {D1, D2, D3, D3+, D5, D6}, `minimal_sets` enumerates all 2¹¹ = 2048 subsets of the eleven axioms, evaluates the proposition under each subset, and returns the inclusion-minimal subsets among those under which the proposition holds. No "relevant axiom" shortcut is used, so the result is complete by construction.

## Interpretation choices

1. **"Every step relation R"** is read as: every subset of the maximal relation R_max whose steps satisfy Σ. This is the natural reading of "for every step relation R whose steps all satisfy Σ", and it is what makes the proposition-level monotonicity arguments above valid.
2. **Admissible bars.** Without A0, 𝒰 = all subsets of E. With A0, 𝒰 = the up-sets of the poset.
3. **State encoding.** A state is the tuple `(p, s, e, g, u)` with `u` a `frozenset`. JSON output uses `{"p","s","e","g","u"}` with `u` sorted.
4. **Countermodel trajectory.** A list of `{"from", "kind", "to"}` records. If a proposition holds (no countermodel exists), the field is `null`. If NV fails, no trajectory exists at all, and the field is `null`.
5. **"Shortest countermodel."** BFS gives a shortest path; for D3/D3+ the shortest of the two avoid-set BFS paths is returned.
6. **"Inclusion-minimal."** A subset Σ makes a proposition hold minimally if the proposition holds under Σ and fails under every proper subset of Σ.
7. **Axiom A4.** Interpreted as removing all COMP steps from R_max when present, per the table ("κ ≠ COMP").
8. **Axioms A3g / A3s.** Applied only to EVID and EVIDREF steps, exactly as the table states.
9. **Axioms A5e / A5g / A5s.** Applied only to WORK steps, exactly as the table states.
10. **Axiom A6.** Applied to every step kind.

No ambiguity in SPEC.md was resolved in a way that changes a truth value; the choices above are the direct reading of the axiom table.

## Runtime

Not reported. The implementation was not executed in this environment, and no runtime is therefore claimed.
