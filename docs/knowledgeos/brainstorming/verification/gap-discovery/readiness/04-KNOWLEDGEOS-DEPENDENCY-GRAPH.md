# 04 — KnowledgeOS Readiness Dependency Graph

Answers mandate **§3**. Executable: `exec/minimum_implementable.py`.
**The mandate warns: do not assume every construct passes through every node identically. Verified —
they do not.**

---

## 1. The graph, as it actually is

```
        ┌─ Identity ──── Equality ─────────────────────────┐
        │   [D? TG-06]     [D]                             │
Proposition ── Assertion ── Relation ──── K ──── History   │
  [D-6]          │           [D-5]      [A: two rival K]   │
                 │                        │                │
             Provenance              ┌────┴────┐           │
             RelationType         Lineage   Orphan         │
                                  (clear)   (clear)        │
                                                           │
        Evidence ── Sigma ─────────┐                       │
          [D TG-08]  [D TG-10/12]  │                       │
             │                     │                       │
        Qualification          InvariantReg ◀──────────────┘
          [D TG-14]             (ℐ · G-67 · NEVER ENUMERATED)
             │                     │
        Assessment              O_core   [O · NOT FROZEN · NOT RATIFIED]
          [D TG-13]                │
             │                  Operations  [O · 0 canonical]
        Measurement                │
          [D]                  Transformation  [T · δ no body · 0 postconditions]
                                ┌──┴──┐
        Policy ✓RATIFIED     Rejection  Replay
          [G GC-1]           [T CONTRADICTORY · AF-F-31]
        Authority
          [D TG-01/07]
        Authorization ── Γ
          [I runtime absent]

        Q_t ── Missingness          Determination
        [EC]     [EC]                  [D]
```

## 2. The chain is not uniform — three shapes

| Shape | Constructs | Consequence |
|---|---|---|
| **Clear through every node** | Lineage · Orphan | implementable now; both are `ℛ`-derived |
| **Blocked at Architecture** | 16 of 25 — every construct absent from the ratified vocabulary (C1) | a governance act, not derivation |
| **Severed at Operations→Transformations** | `O_core` · Operations · Transformation · Rejection · Replay | **the only strictly linear segment in the graph** |

$$\boxed{\mathrm{InvariantReg}\;\rightarrow\;\mathcal O_{core}\;\rightarrow\;\mathrm{Operations}\;\rightarrow\;\mathrm{Transformation}\;\rightarrow\;\{\mathrm{Rejection},\ \mathrm{Replay}\}}$$

**There is no parallel route to a transition function.** Every construct downstream of
`InvariantReg` inherits its status, and `InvariantReg` is `ℐ` — **never enumerated**.

## 3. The dependency nobody has recorded

Two independent obligations turn out to need the **same missing object**:

| Obligation | Requires |
|---|---|
| the **operation-necessity test** — `o primitive ⟺ ∃r ∈ R_mandatory : r ∉ Closure(𝒯_{-o})` (Step 277; *"never run by anyone"*, GN-75) | `R_mandatory` |
| **`Sufficient(K, 𝒪, ℐ)`** — the expressibility conjunct (`step-272/12`) | `ℐ` |

`R_mandatory` **is** `ℐ`. Neither lane records that these are the same dependency.

> **`ℐ` is the single object on which both the operation registry and the state-sufficiency criterion
> depend, and it has never been enumerated.** `BLOCKED — REQUIRES DERIVATION.`

## 4. Node-by-node reachability

Computed (`exec/minimum_implementable.py`):

```
reference-kernel transitive requirement set : 18 constructs (or 15 — see 07 §3)
of those, unblocked                         : 3   (Provenance, RelationType, Assertion)
of those, blocked                           : 15
```

**Three of eighteen.** And the three unblocked are all upstream of the severance.

## 5. Root blockers — those with no blocked dependency

| Construct | Class | Status |
|---|---|---|
| **Identity** | `D?` | clear in all lanes, **but TG-06 contests `id=H(…e…)`** |
| **Policy** | `G` | **RATIFIED** — blocked only by GC-1's rival closure |
| **Proposition** | `D` | `(E,D,V)` vs `(S,ρ,O,Γ)` — D-6 |
| **Evidence** | `D` | TG-08: no identity |
| **Relation** | `D` | D-5: 3-field vs 8-tuple |

**These five are where work can legitimately begin.** Everything else is downstream.
