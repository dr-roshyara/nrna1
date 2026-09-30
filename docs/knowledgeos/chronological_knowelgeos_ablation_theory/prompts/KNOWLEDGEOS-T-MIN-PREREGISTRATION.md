# T-min: a minimal event-centred transition theory (pre-registration; not another M-number)

| | |
|---|---|
| Status | **Frozen before `tmin_check.py` exists.** Not canonical. Authority: none. It supersedes nothing: M0–M3 remain as instruments; T-min is the *consolidation target* |
| Data | development events already coded, F-LOG-0102…0128. **No new corpus read in this phase** |

## 1. Candidate structure (MODEL-ASSUMPTION; every component is on trial)
- **An event** is `e = (o, r, a, k, σ, ε, x, Δ⁺, Δ⁻, outcome)`. The components are:
  - operation o;
  - route r;
  - authority a;
  - object kind k;
  - prior-state descriptor σ (the coarse state the guard reads);
  - evidence level ε ∈ {0, 1, 2+, NR};
  - exception x;
  - the stated changes Δ⁺ and the explicit non-changes Δ⁻;
  - the outcome ∈ {PERFORMED, REFUSED}.
- **Legality:** `Legal(e) = f(o, r, a, k, σ, ε, x)`. Which arguments are necessary is the question.
- **State:** a map (target, coordinate) → value, plus a **finite history summary** H (the registry and the text window) where it is required.
- **Frame:** Δ⁺(e) ⊆ Frame⁺(o), and Δ⁺ ∩ Δ⁻ = ∅. Direct effects only; derived guard truth is not a change.

## 2. Tests
1. **Variable necessity (minimal pairs).** A variable x is **NECESSARY** if two coded events agree on every *known* other variable, differ in x, and have different outcomes. It is **UNDETERMINED** if no such pair exists. Rules for comparison:
   - an UNKNOWN or NR value never matches, so it cannot form a pair;
   - equality is exact on coded values;
   - the target's *identity* is not a variable.
2. **Markov sufficiency.** For each coded history pair with an equal apparent state, are the enabled futures equal? If not, what finite summary restores equality?
3. **Frame bookkeeping.** Tabulate the frame results so far (ADOPT and ACCEPT falsified and repaired; no Δ⁺ ∩ Δ⁻ clash). **Frame⁺ is not expanded here.**
4. **Report** each variable as NECESSARY or UNDETERMINED, never "redundant". Absence of a minimal pair in selected data does not show redundancy.
