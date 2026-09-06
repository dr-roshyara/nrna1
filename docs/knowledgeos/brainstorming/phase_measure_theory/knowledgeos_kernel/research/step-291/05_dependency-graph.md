# 05 — Dependency Graph, Recomputed (mandate §9)

**Witness** `exec/t291_bootstrap.py` · **transcript** `exec/t291_transcript.txt`
**29 nodes · 44 edges** — `𝒪`, `𝒯`, `𝒪_K` **separated** for the first time (`01`).

## Measured results

| | Step 289 | **Step 291** |
|---|---|---|
| cycles | 4 | **3** |
| `{≡}` acyclic? | ✅ | ✅ |
| unique size-1 cut? | ✅ | ✅ **confirmed over 13 resolvable nodes** |
| `𝒪` in a cycle? | 0 of 4 | **0 of 3** |
| `𝒯` in a cycle? | 0 of 4 | **0 of 3** |
| **`𝒪_K` in a cycle?** | *(not separated)* | **0 of 3** |
| `≡` in a cycle? | 4 of 4 | **3 of 3** |

**The three cycles:**
```
congruence -> equiv -> congruence
congruence -> equiv -> delta -> congruence
approx -> equiv_cand -> equiv -> bindings -> approx
```

**Probes:**
```
cut ['O']              -> False      cut ['equiv']      -> True
cut ['T']              -> False      cut ['delta']      -> False
cut ['O_K']            -> False      cut ['congruence'] -> False
cut ['O','T']          -> False      cut ['equiv_cand'] -> False
cut ['O','O_K','T']    -> False      cut ['approx']     -> False
```

$$\boxed{\text{Cutting ALL THREE of } \mathcal O,\ \mathcal T,\ \mathcal O_K \text{ still leaves the graph cyclic.}}$$

✅ **Step 290's prediction confirmed independently:** it forecast 3 cycles after Z-1's weakening
(`≈ → ≡` was a *candidate* edge, remodelled here as `≈ → equiv_cand → ≡`). **Measured: 3.**

## §18 Degeneracy check on the zero-results

> *"Could the property be vacuously true because the test space is empty, universal, or degenerate?"*

| Check | Result |
|---|---|
| is the graph non-empty and non-trivial? | ✅ 29 nodes, 44 edges, **3 cycles found** |
| do membership counts **differ** across nodes? | ✅ `𝒪`/`𝒯`/`𝒪_K` = 0 of 3; `≡` = **3 of 3** |
| could `cut {𝒪} → False` be vacuous? | 🔴 **NO** — the graph *has* cycles, and the differing counts show the test discriminates |

$$\boxed{\text{The result is SUBSTANTIVE, not vacuous. Recorded because the } \approx_\emptyset \text{ incident makes this mandatory.}}$$

## §9's last question: does cutting `𝒪`/`𝒯` change the *derivability* graph?

✅ **YES — and that is the whole point of the two-boundary distinction.** Cutting `{𝒪,𝒯,𝒪_K}` leaves the
equality cycles intact **but** unblocks: `𝒯 → δ`, `𝒪_K → ≈`, `𝒪 → Π`, `𝒪 → bindings`, `𝒪 → assess_det`,
`𝒯 → congruence`, `𝒯 → ≡_𝒯`. **Seven edges become traversable.**

$$\boxed{\begin{array}{ll}\{\equiv\} & \text{breaks the CYCLES}\\ \{\mathcal O, \mathcal T, \mathcal O_K\} & \text{unblocks the DERIVATION}\end{array}} \qquad \textbf{Different structural roles, neither substitutable.}$$

## STATUS
**MEASURED** 3 cycles; `{≡}` the unique size-1 cut over 13 nodes; `𝒪`/`𝒯`/`𝒪_K` each in 0 of 3; the
zero-results are non-vacuous · **DERIVED** the two boundaries are non-substitutable · **CORPUS** every
edge cited · **UNKNOWN** whether edges this list omits exist
