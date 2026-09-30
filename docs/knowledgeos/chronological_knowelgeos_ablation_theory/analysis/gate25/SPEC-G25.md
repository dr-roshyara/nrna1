# SPEC-G25 — a temporal promotion model family (results-free)

This specification states **what** to compute, never the answer. Implement it independently, deterministically and exhaustively. Where it is ambiguous, choose a reading, **declare it**, and continue.

## 1. Base system

- **P = {gen, der}; S = {auth, prov, hist}; G = {0, 1}.**
- **Instances:** chain3 (n0 < n1 < n2); V (bot < a, bot < b); diamond (bot < a < top, bot < b < top). ≤ is the reflexive-transitive closure.
- **⊥** = the unique minimal element.
- **Bars:** under A0, the admissible bars u are the up-sets (every state, including reached states); without A0, all subsets.
- **Base state:** (p, s, e, g, u). **Kinds:** GOV, EVID, EVIDREF, WORK, COMP.
- **Base axioms**, each constraining one step x →κ x′; unrestricted components may change freely:
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
- **F(e, u) ⟺ e ∈ u ∨ e ≠ ⊥.**
- A property "holds under Σ" iff it holds for every step relation whose steps satisfy Σ. Existential items are evaluated on the maximal relation.

## 2. Added state and axioms

**Added state:** au, ad, rv ∈ {0, 1}. A **fresh** state has au = ad = rv = 0. Unprimed = source, primed = target.

| Axiom | Constraint on a step x →κ x′ |
|---|---|
| T-G1 | (au′ ≠ au ∨ rv′ ≠ rv ∨ (ad = 0 ∧ ad′ = 1)) ⇒ κ = GOV |
| T-AUTH | (au = 0 ∧ au′ = 1) ⇒ F(e, u) |
| T-AM | au′ ≥ au |
| T-PROM | (ad = 0 ∧ ad′ = 1) ⇒ au′ = 1 |
| T-EVENT | (ad = 0 ∧ ad′ = 1) ⇒ F(e, u) |
| T-P1 | (ad = 1 ∧ ad′ = 0) ⇒ (κ = GOV ∧ rv′ = 1) · (rv = 0 ∧ rv′ = 1) ⇒ (ad = 1 ∧ ad′ = 0) · rv′ ≥ rv |
| T-P0 | ad′ = 1 ⇒ F(e′, u′) · (ad = 1 ∧ ad′ = 0) ⇒ ¬F(e′, u′) · rv′ = rv = 0 |

**Models (full axiom set = base + the listed added axioms):**

| Model | Added axioms |
|---|---|
| MT1-P0 | T-G1, T-AUTH, T-AM, T-PROM, T-P0 |
| MT1-P1 | T-G1, T-AUTH, T-AM, T-PROM, T-P1 |
| MT2-P0 | T-G1, T-AUTH, T-AM, T-PROM, T-EVENT, T-P0 |
| MT2-P1 | T-G1, T-AUTH, T-AM, T-PROM, T-EVENT, T-P1 |

**Event definitions:**
- **AuthEvent:** a step with au = 0 ∧ au′ = 1.
- **PromEvent:** a step with ad = 0 ∧ ad′ = 1.
- **Reachable:** reachable from any fresh state (any e, g, u) using all kinds.

## 3. Properties (per model and instance)

**Classes:**
- **HOLDS** / **FAILS** (one shortest witness trajectory) / **VACUOUS** (a universal property whose relevant set is empty; for existential items, report reachable / not reachable).
- Convention: for "for all o in R: φ(o)", R empty ⇒ VACUOUS; this includes D3-type history properties with no reachable target.

| Id | Definition |
|---|---|
| AUTH-SAFETY | every reachable AuthEvent has F(e, u) at its source |
| EVENT-SAFETY | every reachable PromEvent has F(e, u) at its source |
| TOCTOU | is there a trajectory from a fresh state containing an AuthEvent, then a state with ¬F, then a PromEvent, with au never returning to 0 in between? |
| PERSIST | is a state with ad = 1 ∧ ¬F reachable? |
| AUTO-INVAL | every reachable step from a state with ad = 1 into a state with ¬F ends with ad = 0 |
| REVOC-EXPLICIT | every reachable step with ad = 1 → ad′ = 0 is a GOV step with rv′ = 1 |
| REVAL-a | from some reachable state with au = 1 ∧ ad = 0 ∧ ¬F, is a PromEvent reachable with no EVID step? |
| REVAL-b | from some reachable state with au = 1 ∧ ad = 0 ∧ ¬F, is a PromEvent reachable at all? |
| REP (chain3 only) | from fresh states with e = n1, g = 0, u = {n2}, using GOV and WORK only with u constant, is ad = 1 reachable? |
| P-GUARD | from fresh states with e = ⊥ and e ∉ u, is ad = 1 reachable without any EVID step? (HOLDS iff not) |
| D1 | from any state with e ∉ u, no GOV-only trajectory reaches e ∈ u |
| D2 | from any state with g = 0, no {EVID, EVIDREF, WORK}-only trajectory reaches g = 1 |
| D6 | no step changes p |

**Also report:** the state count, reachable count, and reachable count with ad = 1.

## 4. Scenarios (chain3; start u = {n2}; fresh; g = 0; p and s free; full axiom set unless stated)

Each scenario asks whether a trajectory exists with the stated **ordered pattern**. If so, give one shortest witness, the (e, u) **at the AuthEvent**, and the (e, u) **at the PromEvent**.

| Id | Start e | Ordered pattern | Allowed kinds |
|---|---|---|---|
| T1 | n1 | AuthEvent, then PromEvent, with no EVID/EVIDREF step in between | GOV, WORK |
| T2 | n1 | AuthEvent, then an EVID step raising e, then PromEvent | all |
| T3 | n2 | AuthEvent, then an EVIDREF step ending at e = n1, then PromEvent | all |
| T4 | n1 | AuthEvent, then an EVIDREF step ending at e = n0, then PromEvent | all |
| T5 | n1 | AuthEvent, then a step with u′ ≠ u, then PromEvent — **A6 removed** | all |
| T6 | n1 | AuthEvent, then an EVID step ending with e ∈ u, then PromEvent, with no PromEvent before that EVID step | all |

## 5. Ablation and minimal sets

- **Ablation:** for each model and instance, remove each single axiom (base and added) and recompute every §3 property.
- **Minimal sets:** for each property that holds (HOLDS or VACUOUS), all inclusion-minimal subsets **of the model's added axioms** (base kept) under which it still holds.
- **Redundant added axioms:** those in no minimal set.

## 6. Output

`results.json` (per model and instance: counts, §3 classes and witnesses, §4 answers with auth/promotion states, ablation, minimal sets, redundant axioms), `METHOD.md` (method, exactness argument, interpretation choices) and the sha256 of both.
