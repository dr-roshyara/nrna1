# 02 — Observation Inventory (mandate §6)

**The strongest corpus-supported inventory of OBSERVATIONS. No observation is invented (§21).**

| Candidate observation | Locus | Kind | Typed? | Evidence it is a permitted observation |
|---|---|---|---|---|
| **`TraceOrigin(x)`** | `258.15` | provenance | 🔴 | ✅ used to distinguish two states with equal visible content — **the corpus's own congruence counterexample** |
| **`ExplainRevision(x)`** | `258.16` | history | 🔴 | ✅ same role, for revision history |
| `Assess(K,x)` | `259.7/8` | assessment | ✅ `K × X → Assessment` | ⚠️ **an operation of kind 2**, given a *determination* test — whether it is an *observation* is undetermined |
| `Authorize(...)` | `259.7/8` | governance | ✅ | ⚠️ kind 3 |
| `Compare(P_1,P_2,C,Ω)` | `012 §46` | comparison | ✅ `→ 𝒬` | ⚠️ over **propositions**, not states |
| `orphan(a) ⟺ deg_ℛ(a)=0` | `281`/EKP lint | structural | 🟡 | ✅ computable, total, cheap |
| `circular_dependency` | EKP lint | structural | 🟡 | ✅ implemented |
| `Lineage = Π ∘ ℛ_der*` | `47`/`I-Π` | provenance | 🟡 | ✅ derived |
| the 5 `Σ` axis readings `π_A…π_C` | Q4A | epistemic label | ✅ | ⚠️ **`Σ`-level; and no `π : K → Σ` exists** (`289 §5`) |
| `deg_ℛ`, graph queries | `38.87` `G_I`/`G_K`/`G_P` | structural | 🔴 | 🟡 three graphs, *"must not be collapsed"* |

## The classification, with evidence

$$\boxed{\textbf{CASE C — semantically bounded, not formally closed}} \qquad \text{schema question at } \boxed{\textbf{E}}$$

| Why not A | nothing is enumerated anywhere; the list above is **assembled by me**, not declared by the corpus |
|---|---|
| Why not B | `259.7` **names** kind 5 *"observation operations"* and gives **no schema for them** — unlike kinds 2 and 3, which get signatures |
| Why C | `261.21` asserts *"the **closed set** of permitted state observations"* — an intended boundary with no exhaustive definition |
| Why not D | a boundary is asserted to exist |
| Why E for the schema | the corpus gives no type for an observation, so *"what could be observed"* is undetermined |

⚠️ **The strongest single fact in this inventory:** the two observations the corpus actually *uses* to
do work — **`TraceOrigin` and `ExplainRevision`** — appear **only** as counterexample devices in
`258.15`/`258.16`. **They are untyped, unregistered, and load-bearing.** They are what make
`Decision 3`'s technical half decidable in principle (`258.31`), and they are not in any registry.

$$\boxed{\text{The observation universe is not merely unclosed. Its two working members are unregistered.}}$$

## STATUS
**CORPUS** 10 candidate observations; `261.21`'s boundary assertion · **MEASURED** 0 of 10 are declared
*permitted* by the corpus; 2 of 10 are load-bearing and unregistered · **DERIVED** case **C**, schema at
**E** · **TYPE-THEORETIC** no observation type exists · **NORMATIVE** `𝒪_K`'s extension ·
**UNKNOWN** whether kinds 2/3 count as observations
