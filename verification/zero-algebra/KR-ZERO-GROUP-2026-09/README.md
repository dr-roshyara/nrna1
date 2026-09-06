# `KR-ZERO-GROUP-2026-09`
## The minimal mathematical structure of group-level eliminability

**Commissioned by** the `KR-ZERO-ALGEBRA` review. **Central question:** what is the minimal
mathematical structure of `Zero(S; D)` for subsets `S ⊆ D`?

**Definition under test — `[PROP]`, NOT `[DEF]`:**

```
Zero_{T,Π}(S; D)   iff   Π(T(D)) = Π(T(D \ S))          S ⊆ D
```

The element-level form is the special case `S = {x}`.

**Theory v1.2 unchanged · no v1.3 · kernel NOT SELECTED · no algebra introduced · no regime concluded
to be *the* correct framework.**

---

> # ⚠️ The headline is a CORRECTION to the previous experiment's report
>
> `KR-ZERO-ALGEBRA` concluded *"Zero is not element-wise in **either** direction."* **That was
> accurate as measured and misleading as stated.** The two directions do **not** have the same
> empirical status:
>
> | | status |
> |---|---|
> | **Case I** — individual Zero **⇏** group Zero | **GENERAL** — 267 / 746 cases with the added contract **excluded** |
> | **Case J** — group Zero **⇏** individual Zero | **CONTRACT-CONDITIONAL** — **0 occurrences** without a *cancelling* contract |
>
> **Every one of the 19 emergent (case-J) minimal subsets came from `P9_balance` — the cancelling
> contract the executor added.** None of the eight original contracts produces one.

## What survives, and what does not

| structure | all contracts | **excluding the cancelling contract** |
|---|---|---|
| minimal Zero subsets of size ≥ 2 | 19 | **0** — all 798 are singletons |
| intersection-closure fails | 2 | **0 / 763** |
| matroid circuit exchange fails | 2 | **0** |
| downward-closure fails | 40 | **26** — robust |
| **Z not generated upward by its minimal elements** | 286 | **267 / 746** — **robust** |

> ## The one structural fact that survives everything
> ```
> A SUPERSET of an eliminable set need not be eliminable.
> ```
> That is case I, and it is the single robust obstruction. Under non-cancelling contracts the family
> is otherwise **well-behaved** — intersection-closed and matroid-consistent in every tested case.

## Contents

```
README.md   results.md   counterexamples.md
property-results.json   seeds.json   witnesses/   code/
```

Reproduce: `cd code && python3 run.py` — deterministic, base seed `20260902`, 1 200 cases/family,
powerset bound `n ≤ 7`.
