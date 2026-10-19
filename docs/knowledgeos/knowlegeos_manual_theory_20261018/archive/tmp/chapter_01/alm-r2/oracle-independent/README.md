# ALM-r2 independent reference oracle

This oracle was built **only** from `../ALM-R2-SPEC.md`. It is meant for differential testing of another implementation.
It uses Python 3 and the standard library only.

## Files

| File | Content |
|---|---|
| `oracle.py` | the oracle |
| `selftest.py` | 118 hand-derived checks; each derivation is in a comment |
| `SPEC-FINDINGS.md` | 47 findings: ambiguities, inconsistencies, and counterexamples to theorems |

## Run

```
python3 -B selftest.py          # expect: "118 passed, 0 failed"
```

```python
import oracle as O
O.valid(history, theta)                      # {id: bool}
O.auth(history, theta)                       # {id: "T"|"F"|"NA"}
O.current(history, theta, cid, t)            # bool
O.elig(history, theta, eid, purpose, work)   # grade (elig_full also returns flags)
O.rec(entries, k, q, theta, rparams, now)    # "T"|"F"|"U"; now = number or callable(k)
O.rec_at_date(entries, d, wit_pos, wit_neg, q, theta, rparams)
# extras:
O.rec_explain(...)  # (verdict, reason, worlds counted)
O.count_worlds(...)
O.eff(...)
O.eff_obs(...)
O.usable_from(...)
```

The data formats are those of Appendix I:
- RD1 keys are written `"decision|expiry"`;
- `"inf"` stands for ∞;
- `"never"` stands for an unrecorded ρ.

**No defaults:**
- every key of Θ and Ρ, `now`, `Proc`, and both witness lists are required;
- a missing key raises `SpecError`.

## Design

### V layer

- **Valid** is found by **brute-force search over all 2ⁿ truth assignments** to the equations `Valid(x) = Form(x) ∧ Auth(x) = T`.
  - Everything else (Basis, Alive, Parent, Endings, Restr) is evaluated relative to the candidate assignment.
  - Exactly one solution must exist; otherwise the oracle raises `SpecError`.
- **Current** at t is solved the same way, over the ADOPT acts. This covers the recursion in condition 6.
- **Above 14 acts or adoptions**, the oracle switches to a stratified pass along prec, then re-checks the result as a fixpoint.
  - The self-test cross-checks brute force against the stratified pass on 450 random histories.

### R layer

Worlds are built literally from S-6.1:
1. choose a variant;
2. assign the ⊥ fields: kind, then τ, then the other fields (incl. ρ under a_*), filtered by the axioms, then scope over `N_w`;
3. enumerate every admissible order within each equal-instant group (C10 = given);
4. take the υ-subsets of `X_w`.

S-6.2 is then applied. Once more than B worlds are constructed, the result is `U` with the reason "bound". Worlds are counted as raw constructions.

### Choices

Where the spec was silent, the choice is tagged `[F-nn]` in the code and documented in `SPEC-FINDINGS.md`. The most consequential choices are:

| Finding | Choice |
|---|---|
| F-08 | contradictions: choose one variant literally |
| F-10 | the ⊥ party domain is "that role only" |
| F-12, F-15, F-16, F-17 | domains for kind, cperiod, booleans and bars |
| F-18 | `N_w` excludes the needs of ⊥-scope grants |
| F-19 | a_rho orders are grouped by ρ |

## Limits

- The cost is exponential by design: 2ⁿ per world times the number of worlds. Keep R-layer cases small (≲ 8 acts, few ⊥ fields).
- Out of scope, as in S-11: C2, suspension, C4, C5, C13, IND-4 and IND-6. Only static Root (C12) is modelled.
- `elig` returns the grade only, per Appendix I. Flags (`DISCLOSURE_REQUIRED`) are available through `elig_full`.
- The V layer does not check AX-NoBackdate on the input history.
