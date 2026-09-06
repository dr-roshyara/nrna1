---
artifact: 4 · POLICY-EXECUTION-EXPERIMENT
mandate: 20260830_1931 §5
date: 2026-08-30
status: EXECUTED — **including two failed experiments and their corrections, retained**
---

# Policy-Dependence Experiment

## Experiment 1 — synthetic input, two genuinely different policies

```
P_A "lenient" = gates{Pre, Invariant, Authorization},                          Unknown→Allow
P_B "strict"  = gates{Pre, Invariant, Authorization, JustifStrength≥2,
                      TwoSource≥2, TrustedOrigin},                             Unknown→Block

  D1 strong        P_A=True   P_B=True    DIFFER=False
  D2 weak          P_A=True   P_B=False   DIFFER=True
  D3 unknown-inv   P_A=True   P_B=None    DIFFER=True

  P_B detail on D2: {Pre:True, Invariant:True, Authorization:True,
                     JustifStrength:False, TwoSource:False, TrustedOrigin:False}
```
> **`Assessment(K, P_A) ≠ Assessment(K, P_B)` — CONFIRMED, twice, on two different mechanisms
> (a failing gate, and an Unknown resolved differently).**

## Experiment 2 — the converse

```
P_B(D1) = True    P_B(D4) = True     D1 ≠ D4
```
> **`Assessment(K1, Policy) = Assessment(K2, Policy)` for distinct states — CONFIRMED.**
> **Assessment is a function, not an injection.** Distinct knowledge states may share an assessment, so
> **an assessment result can never be used to recover the state that produced it.**

## Experiment 3 — REAL EKP DATA. **This one FAILED, and the failure is the finding.**

37 real governed documents, two policies:
```
Policy=lenient  ->  {True: 24, False: 13}
Policy=strict   ->  {True: 24, False: 13}
documents where the two policies differ: 0
```

**I initially printed "SAME K, DIFFERENT POLICY, DIFFERENT ASSESSMENT". That was FALSE — my own data
contradicted my own conclusion.** Retained here rather than deleted.

**Diagnosis, executed:**
```
gates that ever fail:  lenient={Authorization}   strict={Authorization, JustifStrength}
(status, authority) co-occurrence in the real EKP:
   (frozen,authoritative):1  (baseline,authoritative):1  (approved,authoritative):13
   (draft,provisional):12    (approved,derived):9        (approved,generated):1
```

> **CORRECTED CLAIM: the two policies are EXTENSIONALLY EQUAL on this dataset.** Every document whose
> `authority` is authoritative/derived also has an approved-class `status`, so `JustifStrength` never fails
> independently of `Authorization`. **The differing gate is never exercised.**
>
> **This is a genuine empirical finding, and a better one than what I claimed:**
> **`status` and `authority` are declared independent in `authorities.yaml` but are almost perfectly
> COLLINEAR in the actual data.** Orthogonality is a *design* property of the EKP; it is *not* an observed
> property of its current content.
>
> **Methodological consequence: policy-dependence is only observable when the data exercises the
> differing gate. A policy comparison on non-discriminating data proves nothing** — and would have been
> reported as a PASS if I had not checked the numbers against the prose.

## Experiment 4 — the Σ⊥Γ quadrants on real data. **Also corrected.**

```
observed:  (Supporting, Committed)=24   (Neutral, Uncommitted)=12   (Neutral, Committed)=1
           (Supporting, Uncommitted)=0   ← NOT OBSERVED
the single off-diagonal document: GRAPH-FULL (status=approved, authority=generated)
```

**I claimed both off-diagonal quadrants occur. Only one does.**

> **CORRECTED CLAIM: orthogonality is supported by ONE off-diagonal cell, not two.** One counterexample
> still **refutes "Σ determines Γ"** — that is enough for the logical point. But **the empirical support is
> weaker than I stated**, and the strong evidence for `Σ ⊥ Γ` remains (i) `authorities.yaml`'s explicit
> declaration and (ii) the corpus's executed `10^6-evidence-cannot-commit` experiment.

## Summary

| # | Experiment | Result |
|---|---|---|
| 1 | synthetic, two policies | **CONFIRMED** — policy-dependence real |
| 2 | converse | **CONFIRMED** — assessment is non-injective |
| 3 | real EKP data | **FAILED to demonstrate** — dataset non-discriminating. **Corrected.** |
| 4 | Σ⊥Γ quadrants on real data | **PARTIALLY CONFIRMED** — 1 of 2 off-diagonals. **Corrected.** |

**Two of four experiments produced conclusions I had to withdraw. Both are retained above with their
original wording, per §5.**
