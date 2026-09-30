# SPEC-G2 r2 — six finite transition models and their properties (results-free)

This specification states **what** to compute, never **what the answer is**. Implement it independently, deterministically and exactly (exhaustive; no sampling). Where it is ambiguous, choose a reading, **declare it**, and continue.

## 1. Base system

- **P = {gen, der}; S = {auth, prov, hist}; G = {0, 1}.**
- **Instances (posets E; ≤ is the reflexive-transitive closure of the listed pairs):**
  - chain3: n0 < n1 < n2;
  - V: bot < a, bot < b;
  - diamond: bot < a < top, bot < b < top;
  - antichain2: {a, b}, no order.
- **⊥** = the unique minimal element (chain3: n0; V and diamond: bot). antichain2 has none.
- **Bars:** under A0, the admissible bars are the up-sets of E; without A0, all subsets. **Every state's bar(s) must be admissible**, including reached states.
- **Base state:** (p, s, e, g, u). **Kinds:** GOV, EVID, EVIDREF, WORK, COMP.
- **Base axioms** (each constrains one step x →κ x′; any component not restricted may change freely, including the added components of §2 unless an added axiom restricts them):
  - A0: bars admissible;
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
- **PS(x) ⟺ e ∈ u ∧ g = 1.**
- A property "holds under axiom set Σ" iff it holds for every step relation whose steps all satisfy Σ.

## 2. Models: added components [initial value], notion N, added axioms

In added axioms, unprimed = source state, primed = target state.

| Model | Added components | N(x) | Added axioms |
|---|---|---|---|
| M0 | — | PS | — |
| M0b | b ∈ admissible bars [initial b = u] | e ∈ b ∧ g = 1 | **B6:** b′ = b |
| M1 | x ∈ {0,1} [0], v ∈ {0,1} [0] | PS ∨ (x = 1 ∧ g = 1) | **X1:** (x′ ≠ x ∨ v′ ≠ v) ⇒ κ = GOV · **X2:** (x = 0 ∧ x′ = 1) ⇒ (e ≠ ⊥ ∧ e ∉ u) · **X3:** (x = 0 ∧ x′ = 1) ⇒ v′ = 1 · **X4:** x′ ≥ x ∧ v′ ≥ v |
| M2 | m ∈ {NONE, RULE, EXC} [NONE], ad ∈ {0,1} [0] | ad = 1 | **Y1:** (m′ ≠ m ∨ ad′ ≠ ad) ⇒ κ = GOV · **Y2:** (m = NONE ∧ m′ = EXC) ⇒ (e ≠ ⊥ ∧ e ∉ u) · **Y2b:** (m = NONE ∧ m′ = RULE) ⇒ e ∈ u · **Y3:** (ad = 0 ∧ ad′ = 1) ⇒ ((m′ = RULE ∧ e ∈ u) ∨ m′ = EXC) · **Y4:** ad′ ≥ ad ∧ (m ≠ NONE ⇒ m′ = m) |
| M3a | au ∈ {0,1} [0], ad ∈ {0,1} [0] | ad = 1 | **W1:** (au′ ≠ au ∨ ad′ ≠ ad) ⇒ κ = GOV · **W3:** (ad = 0 ∧ ad′ = 1) ⇒ au′ = 1 · **W4:** au′ ≥ au ∧ ad′ ≥ ad |
| M3b | as M3a | ad = 1 | W1, W3, W4, plus **W2:** (au = 0 ∧ au′ = 1) ⇒ (e ∈ u ∨ e ≠ ⊥) |

- On antichain2 (no ⊥), models M1, M2 and M3b are **NOT_APPLICABLE**. Run M0, M0b and M3a on all four instances.
- **Full axiom set** of a model = base + its added axioms.
- **Fresh state** = added components at their initial values (for M0b: b = u).
- **Exception record** = x = 1 (M1) or m = EXC (M2). Other models have none.

## 3. Properties

**Fresh starts:** fresh states with e ∉ u, g = 0 and N false. **Reachable** = reachable from any fresh state (any e, g, u) using all kinds.

A **PromotionEvent** is a step x →GOV x′ with N(x) false and N(x′) true.

| Id | Definition |
|---|---|
| D1 | from any state with e ∉ u, no GOV-only trajectory reaches e ∈ u |
| D2 | from any state with g = 0, no {EVID, EVIDREF, WORK}-only trajectory reaches g = 1 |
| D6 | no step changes p |
| D3-history[N], D3-history[PS] | every trajectory from a fresh start reaching the notion (N, resp. PS) contains ≥ 1 EVID-or-EVIDREF step and ≥ 1 GOV step |
| D3+[N] | same, with ≥ 1 EVID step |
| D5[N] | for all states: N(x) ∧ x →EVID x′ ⇒ N(x′) |
| NV[N] | some trajectory from a fresh start reaches N |
| D3-state-bar-event | every reachable PromotionEvent has e′ ∈ u′ |
| D3-state-bar-inv | every reachable state with N has e ∈ u |
| D3-state-floor-event | every reachable PromotionEvent with e′ ∉ u′ has e′ ≠ ⊥ |
| D3-state-floor-inv | every reachable state with N and e ∉ u has e ≠ ⊥ |
| P-GUARD | from fresh starts with e = ⊥ (and e ∉ u), no trajectory without an EVID step reaches N |
| P-BAR | every reachable state with N ∧ e ∉ u has an exception record (models without records: holds iff no such state is reachable) |
| P-PERSIST | for every reachable state x with N, every EVIDREF successor x′ has N |

**Also report:**
- the number of reachable states and of reachable N-states;
- for each property: HOLDS / FAILS / VACUOUS (no relevant start, event or state exists) / NOT_APPLICABLE;
- for each failing property, one shortest witness.

## 4. Scenarios (chain3 only; start u = {n2}; fresh; p and s free; full axiom set unless stated)

| Id | Start e | Allowed kinds | Path requirement | Query |
|---|---|---|---|---|
| S0 | n2 | all | u constant | N reachable? |
| S1 | n1 | GOV, WORK | u and b constant; no exception record is ever set; M2: m never EXC | N reachable? |
| S2 | n1 | GOV, WORK | u and b constant; ≥ 1 GOV step | N reachable? PS reachable? |
| S3 | n0 | GOV, WORK | u and b constant | N reachable? |
| S4 | any state | all | — | does any step change u? (M0b: also b?) |
| S4-T | n1 | GOV, WORK | M0: A6 removed, u free · M0b: B6 removed, b free, u constant · others: not run | N reachable? |
| S5 | n1 | GOV, WORK | u constant | M1 only: N ∧ v = 1 reachable? Other models: NOT_REPRESENTABLE |
| S6 | n2 | all | u constant | is a state with N ∧ e ∉ u reachable? |

For each reachable query, give one shortest witness.

## 5. Ablation and minimal sets

- **Ablation:** for each model and instance, remove each single axiom (base and added) and recompute every §3 property.
- **Minimal sets:** for each property that holds under the full set, all inclusion-minimal subsets **of the model's added axioms** (base kept) under which it still holds.
- **Redundant added axioms:** those in no minimal set of any property that holds.

## 6. Output

`results.json` with, per model and instance:
- state count, reachable counts;
- every §3 value and class;
- the §4 answers (chain3) with witnesses;
- ablation, minimal sets, redundant axioms.

Plus `METHOD.md` (method, exactness argument, interpretation choices) and the sha256 of both.
