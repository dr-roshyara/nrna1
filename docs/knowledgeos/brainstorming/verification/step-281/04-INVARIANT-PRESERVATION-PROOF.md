# 04 — Invariant Preservation Proof
**`exec/test_invariant_preservation.py` → `OUT-INVARIANTS.txt`**

| Invariant | Status | Basis |
|---|---|---|
| Identity | **PRESERVED** | `id = H(P,e,c,t,Π)`; `Q_t` is not an input |
| Equality | **PRESERVED** | `K = (𝒜,ℛ)`; `Q_t` is not a component |
| Lineage | **PRESERVED** | `ℛ` untouched; `Lineage(b2) = {a1}` unchanged |
| Replay | **PRESERVED** | `Replay` folds over `K` only |
| Transformation | **PRESERVED** | `T`'s signature unchanged; `Ask` is a separate operation |
| K-minimality | **PRESERVED** | `K` still has exactly two components |
| StructuralValid | **PRESERVED** | no new conjunct required |
| Σ derivation | **PRESERVED** | `Σ` remains a function of `e` alone |

**8/8 PRESERVED. No invariant required revision.**

## Why preservation is structural, not coincidental
> Repair B adds `Q_t` **alongside** `K`, not inside it. **Because no component of `K` changed, every
> invariant defined over `K` is preserved by construction** rather than by re-proof. This is precisely the
> property that A and C2 lack: A mutates `𝒜` and `V_D`; C2 mutates `Σ`.

## The cost, stated rather than hidden
`Q_t` must itself be **replayable and serializable**. `Ask(p)` becomes a **recorded event in History** —
**not** a `K`-transformation. This is an addition to the **event vocabulary**, not to `K`.
**It is a real cost and it is outside the invariant set.**
