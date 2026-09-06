---
artifact: D285-7 · KERNEL CONSEQUENCE MATRIX
status: RESEARCH · `K → 𝓘 → 𝒪 → δ → Kernel` traced per candidate
format: 7-section D285-x template (HPA review, 2026-08-31) · equality specified per the frozen protocol
---

# D285-7 · Kernel Consequence Matrix

The dependency the mandate fixes: **`K → 𝓘 → 𝒪 → δ → Kernel`.**

| Candidate `K` | `𝓘` invariants | `𝒪` operations | `δ` | Kernel verdict |
|---|---|---|---|---|
| **K-1 `K_t`, 8 primitives** (ratified) | `I-1…I-12` **ratified** | `Action` is a **primitive**, so operations are *inside* the vocabulary | `δ` over 8 primitives; replay expressible | ✅ **replay-capable · governance-anchored** · ⚠️ `𝒪` never enumerated *against these primitives* |
| **K-2 `(𝒜,ℛ)`** | `StructuralValid` + 3 others — **derived, unratified** | `𝒪_sem` 19 candidates, 5 families — **not minimal** (272A §27) | `δ(K,e)`; **commit case has no carrier** (`Γ` derived) | 🔴 **replay NOT expressible** (needs `Action`/`Event`) · 🔴 commit is a no-op |
| **K-3 `(A,R,Σ,E_L)`** | as K-2 | as K-2 | as K-2 | ⚠️ `Σ` **stored** contradicts the retract/OR-merge proof ⇒ `Σ` must be derived ⇒ collapses toward K-2 |
| **K-6 `𝒦=(K,H)`** | + replay invariants | + `Replay` (class-4) | `fold(δ,H,K_0)` | ✅ **restores replay** — the minimal repair to K-2 |
| **K-7 Zero layering** `Ω/D_t/K_t/Z_t` | + non-collapse laws | + `Ask`, dimension recognition | as K-2 + `D_t` | ✅ **only candidate expressing missingness**; ⚠️ presupposes `Ω`, undefined |

## The consequence that matters

> **Under the projection result (D285-6), this matrix is not a menu — it is a layering.**
>
> ```
> K_t (ratified, 8 primitives)          ← governance anchor, replay-capable
>   │  π_K   [DEFINABLE, NOT COMPUTABLE — blocked on Qualify]
>   ▼
> (𝒜, ℛ)                                ← epistemic sub-state, replay-INcapable
>   + H     → 𝒦 = (K,H)                 restores replay
>   + D_t   → expresses missingness
>   + Σ derived (never stored)          proven, not chosen
> ```
>
> **`𝒪` cannot be settled at either level until it is enumerated against a *stated* primitive set.**
> 272A enumerated 19 operations against the verification lane's vocabulary; **nobody has enumerated
> `𝒪` against the ratified 8 primitives** — and that, not minimality, is why `𝒪_core` cannot close.
> **New finding of this step.**

---

## Scope of the layering claim (reviewer A, 2026-08-31)

```
semantic state projection   = ESTABLISHED
operational equivalence     = NOT ESTABLISHED
observational equivalence   = REFUTED
computable projection       = BLOCKED (Qualify)
```

> ### **Minimality has NOT been demonstrated, because the operation space has not been enumerated against the ratified 8-primitive state model.**
>
> `𝒪_sem`'s 19 operations were enumerated against the **verification-lane** vocabulary only.
> **Against the ratified 8 primitives, `𝒪` is UNKNOWN.** Therefore `(𝒜,ℛ)` must **not** be concluded
> to be the minimal operational kernel — and `𝒪_core` cannot honestly be declared closed.

## Template conformance (HPA mandate, 2026-08-31)

### 1 · Property Statement
For each candidate `K`, the chain `K → 𝓘 → 𝒪 → δ → Kernel` has determinate consequences; the candidates form a **layering**, not a menu.

### 2 · Trivial vs Substantive
**Trivial:** that different `K`s imply different kernels. **Substantive:** that `𝒪` has never been enumerated against the **ratified 8 primitives**.

### 4 · Qualification
The matrix is a **dependency trace**, not a selection. It ranks nothing and selects nothing.

### 5 · Equality Specification ⭐
**NONE REQUIRED** — no equality is asserted. The matrix relates *candidates to consequences*, which is an implication, not an identity.

### 6 · Independence
**No Gītā input.** `INDEPENDENT`.

### 7 · Classification (7-way, per the frozen protocol)
**mathematical:** `R5` on the traces · **architectural:** `R6` — the layering `K_t → π_K → (𝒜,ℛ) → +H → +D_t` · **DDD:** none · **analogy:** none · **corroboration:** none · **unresolved:** ⚠️ **`𝒪` never enumerated against the ratified 8 primitives — new finding, and the real reason `𝒪_core` cannot close** · **governance:** none
