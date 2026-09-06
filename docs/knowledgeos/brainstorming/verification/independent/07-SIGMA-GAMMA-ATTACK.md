---
artifact: 07-SIGMA-GAMMA-ATTACK
date: 2026-08-30
status: **Σ ⊥ Γ SUSTAINED · Σ's VALUE SET REFUTED — the corpus has ten executable statuses, the theory has a pair**
---

# 07 · Attack on Σ and Γ

## 1. Orthogonality — **SUSTAINED**, on better evidence than was previously offered

All ten mandated cases were constructed (`attack.py` §C). **Every one is a meaningful, distinct
state**, and no single scalar carries both dimensions:

| Σ | Γ | Meaning |
|---|---|---|
| Supported | Uncommitted | strong evidence, no authority act yet |
| Supported | Committed | the ordinary case |
| **Refuted** | **Committed** | authority committed a claim the evidence refutes |
| Conflicted | Committed | committed while evidence conflicts |
| **Supported** | **Contested** | accepted and procedurally contested — the PF-6 residue |
| Unknown | Committed | committed with no evidence either way |
| Unknown | Uncommitted | the initial state |
| **Supported** | **Rejected** | epistemically supported, administratively rejected |
| Refuted | Uncommitted | ordinary refutation |
| Conflicted | Contested | conflict plus contest |

A lossless single vocabulary would need **≥16 values**. **The separation is necessary.**

**Independent corpus corroboration, band A (pre-programme):** step 271.20 states the requirement in
the corpus's own words — *"Epistemic and Governance status must be tested as orthogonal
dimensions"* — with the same worked case (*"well-supported but administratively rejected"*).

### 1.1 One prior proof is withdrawn

The previously-cited executed witness for `Σ ⊥ Γ` is **tautological**:

```python
def commit(self, purpose, authority_act=None, evidence_volume=0):
    if authority_act is None: return False       # the whole test
```
`grep evidence_volume` returns exactly two lines — the declaration and the call site `10**6`.
**The body never reads it.** The `10^6 evidence, no authority act : PASS` line would be identical for
`evidence_volume = 0` and would also "pass" if the law were false.

> **Withdrawn as evidence. The orthogonality claim survives anyway** — on the derivation, on
> `authorities.yaml`'s declaration, and now on the ten constructed cases and 271.20. **It never
> needed the tautology.**

## 2. Is Σ primitive, derived, a projection, or context-dependent? — **DERIVED and POLICY-RELATIVE**

Executed:

```
identical evidence (2 supporting), policy(min_support=1) -> Supported
identical evidence (2 supporting), policy(min_support=2) -> Supported
identical evidence (2 supporting), policy(min_support=3) -> Unknown
```

**The same assertion is `Supported` under one policy and `Unknown` under another.**

> **Therefore `Σ(a)` is ill-typed. Only `Σ(a, policy)` is well-typed.**
>
> **And this refutes a field of the corpus's own relation model**: `r = (E₁,E₂,T,R,Q,E,Σ,τ)` carries
> a **bare `Σ`** with no policy parameter. That field is ill-typed in the corpus too, and it is what
> creates the cycle `Policy → K → ℛ → Σ → Policy` (`03` §3.1). *This pass records a defect in the
> corpus model, not only in the claimed theory.*

## 3. Σ's **value set** — REFUTED

The claimed `Σ = (dir ∈ {Refuting,Neutral,Supporting}, str ∈ {None,Weak,Moderate,Strong,VeryStrong})`.

The corpus, at `025d §25D.4 + §25D.7`, defines and **`zero_reference.py` executes**:

```
Satisfied · PartiallySatisfied · Unknown · Insufficient · Conflicted ·
Stale · Invalid · Prohibited · NotApplicable · Missing
```

with an explicit code-level discrimination:
```python
return "Missing"   # expected governed artifact absent  (25D.7 case B)
return "Unknown"   # no evidence either way             (25D.7 case A)
```
**Executed this pass: exit 0, 8/8 falsification tests PASS.**

**`(dir,str)` cannot express `Missing`, `Stale`, `Prohibited`, `NotApplicable`, `Invalid` or
`Insufficient` — six of ten.**

⚠️ **Stated precisely, because the distinction matters:** `Sat(K_t, r_i)` grades a **requirement
against a state**; `Σ` grades an **assertion**. They are *different subjects of predication* and are
**not the same function**. The refutation is therefore not *"Σ should be the ten-set"* — it is:

> **The distinctions declared inexpressible by the claimed theory are executable, tested, passing
> code in this very corpus.** Whatever `Σ` should be, *"the theory cannot express Missing"* is false.

The script also names the loss itself:
> *"'Insufficient' is expressible ONLY because Γ is carried; the ratified four-arm summary
> (unknown/conflicting/missing/invalid) has no arm for it, nor for Stale or Prohibited — the PF-1
> loss, demonstrated here as executable semantics, not as prose."*

## 4. Σ must not be a scalar — independently confirmed by execution

`exp01_recheck.py`, run this pass (exit 0), re-derives the EXP-01 matrix and concludes:

> *"the negative conclusion is **CONFIRMED and is in fact provable**"* — no simple scalar operator is
> sufficient as the epistemic foundation, because **no scalar can retain `(S⁺,S⁻)`**.

And `zero_reference.py`'s scalar-collapse test:
```
|gaps(A)| = |gaps(B)| = 2, yet A != B as work plans (PASS)
vecA = {"r1":"Missing","r2":"Unknown"}   vecB = {"r1":"Unknown","r2":"Missing"}
```
**Same count, different states.** Zero's output is a **vector over requirements**, not a scalar.

> **Two independent executions agree: epistemic state is irreducibly multi-dimensional.**
> `(dir,str)` is a **pair**, so it is not refuted as a scalar — but its `str` component is an ordinal
> with **no order-preserving rule defined anywhere**, which makes the pair half-uncomputable.

## 5. Verdict

| Claim | Verdict |
|---|---|
| `Σ ⊥ Γ` | ✅ **SUSTAINED** — ten constructed cases, plus corpus 271.20; one prior proof withdrawn as tautological |
| Σ is derived, not stored | ✅ **SUSTAINED**, and sharpened: **policy-relative**, so `Σ(a)` is ill-typed |
| `Σ = (dir, str)` is the right value set | 🔴 **REFUTED** — six of the corpus's ten executable statuses are inexpressible in it |
| `str` is computable | 🔴 **NO** — no evidence→ordinal rule exists in the corpus |
| One status vocabulary suffices | 🔴 **REFUTED** — would need ≥16 values |
