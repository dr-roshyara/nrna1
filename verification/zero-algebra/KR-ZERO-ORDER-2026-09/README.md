# `KR-ZERO-ORDER-2026-09`
## The interaction order of eliminability

**Commissioned question:** *what is the smallest `k` such that the Zero status of `S` cannot be
determined from all subsets of `S` of size ≤ k?*

**Theory v1.2 unchanged · no v1.3 · kernel NOT SELECTED · no algebra introduced · no regime
concluded.** Subset-level definition remains **`[PROP]`, not `[DEF]`**.

---

## Headline

```
k = 1        90.0 %      singleton data suffices
k ≥ 2         2.1 %      genuinely higher-order interaction — observed up to k = 3
irreducible   7.9 %      NO proper-subset information, at ANY order, determines the group
```

*(cancelling contract excluded; see robustness below)*

> ### And the result that matters most, because it contrasts with the last experiment
>
> **`KR-ZERO-GROUP` found that case J was an artefact of the executor-added cancelling contract.
> The interaction-order phenomenon is NOT.** Excluding that contract, irreducibility is **8.49 %
> (127/1 496) — slightly HIGHER**, and `k = 3` cases become **more** frequent (15 vs 6), not fewer.
>
> **Higher-order interaction is general. Cancellation is not its cause.**

## The design decision that makes the question answerable

Determination is tested **within a fixed context `(D, T, Π)`**. `KR-ZERO-GROUP` already established
that Zero is context-dependent, so a cross-context test would re-answer a settled question and report
`k = ∞` trivially. **The cross-context variant is run separately (`O4`) and labelled as the strictly
stronger requirement it is** — its ambiguity falls with `k` (17 → 10 → 2) but never reaches zero.

## Contents

```
README.md  results.md  property-results.json  seeds.json  witnesses/  code/
```

Reproduce: `cd code && python3 run.py` — deterministic, seed `20260902`, 1 500 cases, `n ≤ 6`.
