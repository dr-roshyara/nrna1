# SPEC-G2 — four finite transition models and their properties (results-free)

This specification states **what** to compute, never **what the answer is**. Implement it independently, in any language, deterministically and exactly (exhaustive, no sampling). Where it is ambiguous, choose a reading, **declare it**, and continue.

## 1. Base system (common to all models)

- **P = {gen, der}, S = {auth, prov, hist}**; **E = chain3**: n0 < n1 < n2; G = {0, 1}.
- **Bars:** under axiom A0, the admissible bars u are the **up-sets** of E: {}, {n2}, {n1, n2}, {n0, n1, n2}. Without A0, all subsets.
- **Base state:** (p, s, e, g, u). **Step kinds:** GOV, EVID, EVIDREF, WORK, COMP.
- **Base axioms** (each constrains one step x →κ x′; a component not restricted may change freely):
  - A0: bars admissible (every state's u, including states reached by steps);
  - A1: p′ = p;
  - A2e: κ = GOV ⇒ e′ = e;
  - A3g: κ ∈ {EVID, EVIDREF} ⇒ g′ = g;
  - A3s: κ ∈ {EVID, EVIDREF} ⇒ s′ = s;
  - A3m: κ = EVID ⇒ e ≤ e′; κ = EVIDREF ⇒ e′ ≤ e;
  - A4: κ ≠ COMP;
  - A5e: κ = WORK ⇒ e′ = e;
  - A5g: κ = WORK ⇒ g′ = g;
  - A5s: κ = WORK ⇒ s′ = s;
  - A6: u′ = u.
- **PromoteState(x) ⟺ e ∈ u ∧ g = 1.** Eligible(x) ⟺ e ∈ u.
- **Semantics:** a property "holds under axiom set Σ" iff it holds for every step relation whose steps all satisfy Σ. A trajectory is a finite sequence of steps.

## 2. Models: extra state components (their initial value in brackets), promotion notion N, and extra axioms

| Model | Extra components | Notion N(x) | Extra axioms |
|---|---|---|---|
| **M0** | none | PromoteState | none |
| **M1** | x ∈ {0,1} [0], v ∈ {0,1} [0] | PromoteState ∨ (x = 1 ∧ g = 1) | **X1:** (x′ ≠ x ∨ v′ ≠ v) ⇒ κ = GOV · **X2:** (x = 0 ∧ x′ = 1) ⇒ (n1 ≤ e ∧ e ∉ u), evaluated in the source state · **X3:** (x = 0 ∧ x′ = 1) ⇒ v′ = 1 · **X4:** x′ ≥ x ∧ v′ ≥ v |
| **M2** | m ∈ {NONE, RULE, EXC} [NONE], a ∈ {0,1} [0] | a = 1 | **Y1:** (m′ ≠ m ∨ a′ ≠ a) ⇒ κ = GOV · **Y2:** (m = NONE ∧ m′ = EXC) ⇒ (n1 ≤ e ∧ e ∉ u) · **Y2b:** (m = NONE ∧ m′ = RULE) ⇒ e ∈ u · **Y3:** (a = 0 ∧ a′ = 1) ⇒ ((m′ = RULE ∧ e ∈ u) ∨ m′ = EXC) · **Y4:** a′ ≥ a ∧ (m ≠ NONE ⇒ m′ = m) |
| **M3** | a ∈ {0,1} [0] | a = 1 | **Z1:** a′ ≠ a ⇒ κ = GOV · **Z2:** a′ ≥ a |

- A **fresh item** is a state whose extra components have their initial values.
- An **exception record** is x = 1 (M1) or m = EXC (M2). M0 and M3 have none.
- In Y2/Y2b/Y3, e and u are evaluated in the source state; primed values are target values.

## 3. Properties (compute each for each model under its full axiom set = base + extra)

**Starts for §3 items 3–8:** all fresh-item states with e ∉ u and g = 0. Items 3–8 are evaluated **twice**: with the promotion notion **PS = PromoteState** and with **N**.

| # | Property |
|---|---|
| 1 | **D1:** from any state with e ∉ u, no trajectory of GOV steps only reaches a state with e ∈ u |
| 2 | **D2:** from any state with g = 0, no trajectory of {EVID, EVIDREF, WORK} steps only reaches g = 1 |
| 3 | **D3:** every trajectory from a start reaching the notion contains ≥ 1 EVID-or-EVIDREF step and ≥ 1 GOV step |
| 4 | **D3+:** as D3, with ≥ 1 EVID step |
| 5 | **D5:** if the notion holds in x and x →EVID x′, then it holds in x′ (all states) |
| 6 | **D6:** every step keeps p (all states) |
| 7 | **NV:** there exists a trajectory from a start to the notion |
| 8 | **D3-scope:** D3 with the notion N, restricted to starts with e = n0 |
| 9 | **P-GUARD:** from every fresh-item state with e = n0 and N false, no trajectory without an EVID step reaches N |
| 10 | **P-BAR:** in every state reachable from a fresh-item state, N ∧ e ∉ u ⇒ an exception record exists (report N/A if the model has no exception record and N ⇒ e ∈ u) |

## 4. Scenario queries (full axiom set unless stated; u = {n2} at the start)

| Id | Start | Allowed kinds | Path requirement | Query |
|---|---|---|---|---|
| **S-T** | e = n1, g = 0, fresh item | GOV, WORK | u constant; ≥ 1 GOV step | N reachable? (M1: additionally with v = 1 at the end.) Also: PS reachable? |
| **S0** | e = n2, g = 0, fresh | all | u constant | N reachable? |
| **S1** | e = n1, g = 0, fresh | GOV, WORK | u constant; no exception record is ever set | N reachable? |
| **S3** | e = n0, g = 0, fresh | GOV, WORK | u constant | N reachable? |
| **S4** | any admissible state | all | — | does any step with u′ ≠ u exist? |
| **S4-T** | as S-T, but **A6 removed** and **no u-constant requirement** | GOV, WORK | ≥ 1 GOV step | N reachable? |
| **S5** | — | — | — | in every state reachable in S-T: u = {n2}? |

For every reachable query, report one **shortest** witness trajectory. p and s range over all values at the start.

## 5. Ablation and minimal sets

- **Ablation:** for each model, remove each single axiom (base and extra) and recompute every §3 property. Report the truth values and one shortest countermodel for each property that fails.
- **Minimal sets:** for each property that holds under the full set, report all inclusion-minimal subsets **of the model's extra axioms**, with all base axioms kept, under which it still holds.

## 6. Output

Produce `results.json` with, per model:
- the state count;
- every §3 value (both notions where required);
- every §4 answer with its witness;
- the ablation table;
- the minimal sets.

Also produce `METHOD.md` (method, exactness argument, every interpretation choice) and the sha256 of both files.
