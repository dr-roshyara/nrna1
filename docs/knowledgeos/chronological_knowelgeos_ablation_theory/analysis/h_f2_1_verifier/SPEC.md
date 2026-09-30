# H-F2-1-R — verification specification (results-free)

This specification is given to a fresh verifier. It states **what** must be checked, never **what the answer is**. The verifier chooses its own method and must justify that the method decides each proposition exactly.

## Sets

- P = {gen, der}
- S = {auth, prov, hist}
- (E, ≤) is a finite poset (instances below).
- G = {0, 1}
- A **bar** is a subset u ⊆ E. 𝒰 is the family of **admissible** bars:
  - 𝒰 = all subsets of E, unless axiom A0 is in force;
  - under A0, 𝒰 = the up-sets of (E, ≤): u such that a ∈ u ∧ a ≤ b ⇒ b ∈ u.

## State and steps

- **State:** x = (p, s, e, g, u) with p ∈ P, s ∈ S, e ∈ E, g ∈ G, u ∈ 𝒰. **Every state's bar must be admissible**, including states reached by steps.
- **Atomic step:** x →κ x′ with kind κ ∈ {GOV, EVID, EVIDREF, WORK, COMP}.
- **Trajectory:** a finite sequence of atomic steps.
- **Promote(x)** ⟺ e ∈ u ∧ g = 1.

## Axioms (each is a constraint on a single atomic step x →κ x′)

| Id | Constraint |
|---|---|
| A0 | the admissible bars are up-sets (see above) |
| A1 | p′ = p |
| A2e | κ = GOV ⇒ e′ = e |
| A3g | κ ∈ {EVID, EVIDREF} ⇒ g′ = g |
| A3s | κ ∈ {EVID, EVIDREF} ⇒ s′ = s |
| A3m | κ = EVID ⇒ e ≤ e′; κ = EVIDREF ⇒ e′ ≤ e |
| A4 | κ ≠ COMP (no atomic composite step) |
| A5e | κ = WORK ⇒ e′ = e |
| A5g | κ = WORK ⇒ g′ = g |
| A5s | κ = WORK ⇒ s′ = s |
| A6 | u′ = u |

A step not restricted by an axiom in force may change any component.

## Propositions

"Holds under axiom set Σ" means: for **every** step relation R whose steps all satisfy Σ, and every admissible bar.

| Id | Statement |
|---|---|
| D1 | from any state with e ∉ u, no trajectory of GOV steps only reaches a state with e ∈ u (u = the bar of the reached state) |
| D2 | from any state with g = 0, no trajectory of {EVID, EVIDREF, WORK} steps only reaches g = 1 |
| D3 | every trajectory from a state with e ∉ u and g = 0 that reaches a Promote state contains ≥ 1 step of kind EVID or EVIDREF **and** ≥ 1 GOV step |
| D3+ | as D3, but the evidential steps must include ≥ 1 **EVID** (non-refutation) step |
| D5 | if Promote(x) and x →EVID x′, then Promote(x′) |
| D6 | every trajectory keeps p constant |
| NV (non-vacuity) | **there exists** R satisfying Σ and a trajectory in R from some state with e ∉ u, g = 0 to a Promote state |

## Instances

| Instance | Elements | Order (only the strict relations are listed; ≤ is their reflexive-transitive closure) |
|---|---|---|
| chain3 | n0, n1, n2 | n0 < n1 < n2 |
| V | bot, a, b | bot < a, bot < b |
| diamond | bot, a, b, top | bot < a < top, bot < b < top |
| antichain2 | a, b | none |

## Required outputs (JSON)

For each instance:

1. the number of states under the full axiom set, and without A0;
2. for the full axiom set {A0, …, A6}: the truth value of D1, D2, D3, D3+, D5, D6 and NV;
3. for each single-axiom removal: the truth value of each proposition, plus **one shortest countermodel trajectory** for each proposition that fails;
4. for each of D1, D2, D3, D3+, D5, D6: **all inclusion-minimal subsets** of the 11 axioms under which the proposition holds.
   - **Do not assume uniqueness.**
   - Brute force over all 2^11 subsets is acceptable.
5. a short statement of the verification method, and why it decides "for every R" exactly.

## Rules

- Standard library only.
- Deterministic.
- Do not read any other file in this repository's `analysis/` or `prompts/` directories.
