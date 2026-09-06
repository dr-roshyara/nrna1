# 02 — Cycle Reconstruction (mandate §4)

## The four cycles — **independently reconstructed, NOT inherited from Step 288**

$$\text{EXECUTED: } \texttt{exec/t289\_bootstrap\_v2.py} \rightarrow \textbf{4 elementary cycles}$$

| Z | cycle | edges | necessary or contingent? |
|---|---|---|---|
| **Z-1** | `≈ → ≡ → ≈` | `261.5`/`261.21` define `≈` and `≡_K` by **one formula**; `261.1`/`246` list them **distinct** | ⚠️ **CONTINGENT — it exists only because of the `N-1` definitional conflict.** Resolve `N-1` and this cycle disappears |
| **Z-2** | `congruence → ≡ → congruence` | `258.9` states congruence *in terms of* `≡`; and *"if it fails, `≡_K` is too coarse"* — `≡`'s adequacy is judged *by* congruence | ✅ **NECESSARY** — both directions are explicit in one corpus section |
| **Z-3** | `≈ → ≡ → bindings → ≈` | `261.19`: bindings need the relations; which relation each operation needs feeds back | ✅ **NECESSARY** given `261.19` |
| **Z-4** | `congruence → ≡ → δ → congruence` | `δ`'s postconditions need `≡`; congruence is a property of `δ`; `≡`'s adequacy judged by congruence | ✅ **NECESSARY** — **this is Step 288's Z-1 hypothesis, CONFIRMED** |

## 🔴 The finding that refutes Step 288

$$\boxed{\textbf{ALL FOUR cycles contain } \equiv. \textbf{ NOT ONE contains } \mathcal O \textbf{ or } \mathcal T.}$$

**EXECUTED probe:**
```
cut ['O']            -> acyclic? False
cut ['T']            -> acyclic? False
cut ['O', 'T']       -> acyclic? False     <-- Step 288's claimed bootstrap does NOT cut
cut ['equiv']        -> acyclic? True      <-- unique minimal cut, size 1
cut ['delta']        -> acyclic? False
```

**Step 288 reported *"three dependency cycles, all through one node; the bootstrap boundary is `𝒪`/`𝒯`."*
Both halves fail:** there are **four** cycles (not three), and **`𝒪`/`𝒯` are not members of any of them**.
`𝒪` and `𝒯` are **upstream sources** — they gate derivation downstream, they do not participate in the
feedback structure.

> ⚠️ **How the error happened, since it matters methodologically:** Step 288 traced each cycle to a node
> it *depended on* and concluded that node was *in* the cycle. **Being a prerequisite of a cycle is not
> being a member of it.** The distinction is exactly the mandate's §16 point, applied one level up.

## §4 Robustness — does each cycle survive deletion?

| Cycle | philosophical material deleted (§21) | verification-lane material deleted | historical/non-normative definitions deleted |
|---|---|---|---|
| **Z-1** | ✅ survives — `261`/`246` are research-lane | ✅ survives | 🔴 **DIES** — it is *made of* the historical duplication (`258.8` vs `261.1`) |
| **Z-2** | ✅ survives — `258.9` alone | ✅ survives | ✅ survives — `258.9` is a single normative-intent section |
| **Z-3** | ✅ survives — `261.19` alone | ✅ survives | ✅ survives |
| **Z-4** | ✅ survives — `258.9` + `δ` typing | ✅ survives | ✅ survives |

$$\boxed{\textbf{Three of four cycles survive every deletion. The dependency structure is NOT an artifact of the philosophical programme or the verification lane.}}$$

⚠️ **Z-1 is different in kind:** it is a **documentary defect** masquerading as a dependency. It should
be repaired (`N-1`), not cut.

## STATUS
**EMPIRICALLY VERIFIED** 4 cycles, enumerated by exhaustive DFS · **ESTABLISHED** all 4 contain `≡`;
none contains `𝒪`/`𝒯`; 3 of 4 survive all three deletions · **REFUTED** Step 288's *"all cycles through
`𝒪`/`𝒯`"* · **DERIVED** Z-1 is contingent on the `N-1` conflict · **TECHNICALLY OPEN** whether other
edges exist that this list omits
