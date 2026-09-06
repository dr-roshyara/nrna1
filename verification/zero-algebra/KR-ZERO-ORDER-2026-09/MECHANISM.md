# MECHANISM — is irreducibility explained by relational interaction?

**The decisive question, asked of all 481 irreducible witnesses and then answered by a fully-crossed
design.** `mechanism-2x2.json` · `witnesses/ALL_irreducible_witnesses.json`.

---

# 1. My hypothesis was FALSIFIED in the form I stated it

I wrote:

> ~~"Element-wise transformations yield `k = 1`."~~

**That is wrong, and the witnesses show it directly.** **7 of the 481 irreducible witnesses occur
under element-wise transformations** — `T5_reference` ×3, `T3_normalize` ×2, `T6_meta_preserving` ×1,
`T1_stopword` ×1.

**But they share an exact property that I had not looked for:**

> ## All 7 have `Π = P9_balance`. Every one. And all are class `R4`, `m = 2`.

`P9_balance` computes `#positive − #negative` — **a numeric aggregate over the multiset that discards
position and admits cancellation.** It is **relational in the contract** rather than in the
transformation.

---

# 2. The fully-crossed 2×2 — 617 contexts, every `T × Π` pair

|  | **cancelling `Π`** | **non-cancelling `Π`** |
|---|---|---|
| **relational `T`** | 196 / 2 538 = **7.72 %** | 4 503 / 20 304 = **22.18 %** |
| **element-wise `T`** | 141 / 3 384 = **4.17 %** | **0 / 33 840 = 0.00 %** |

> ## `[EXP]` The element-wise × non-cancelling cell is EXACTLY EMPTY over 33 840 tests.

**And neither factor alone is necessary:**

- element-wise `T` **+** cancelling `Π` → **4.17 %** — so a relational **contract** suffices on its own
- relational `T` **+** non-cancelling `Π` → **22.18 %** — so a relational **transformation** suffices on its own

## The corrected mechanism

```
Irreducibility requires relational structure SOMEWHERE IN THE PIPELINE —
in the transformation, or in the contract.
When BOTH are element-wise, it does not occur.
```

## The CANONICAL formulation — this is the wording to freeze

> **Transformation choice materially affects the observed determination-order distribution.
> Relational transformations are strong generators in this corpus, but relationality is NEITHER
> NECESSARY NOR SUFFICIENT.**

**Not necessary:** the 7 element-wise witnesses under a cancelling contract (4.17 %).
**Not sufficient:** relational `T` with a cancelling `Π` yields 7.72 %, far below the 22.18 % it
reaches with a non-cancelling one — so relationality alone does not fix the rate.

⚠️ **Any earlier phrasing of the form "element-wise transformations yield `k = 1`" or "relational
transformations produce higher order; element-wise ones do not" is SUPERSEDED by this sentence** and
must not be quoted from the earlier documents.

`[EXP]` for the empty cell (0 / 33 840) and the four rates.
`[PROP]` for the general claim — **8 transformations and 9 contracts is a small design**, and the
classification `relational` / `element-wise` is mine.

**This is a better hypothesis than the one it replaces**, because it survived the test that killed the
original: **the witnesses that falsified "element-wise ⟹ k=1" are exactly the ones the new statement
predicts.**

---

# 3. An observation that runs against intuition

Under a **relational** transformation, the **cancelling** contract has a **LOWER** irreducibility rate
(7.72 %) than the non-cancelling ones (22.18 %).

**Consistent with the paired re-analysis** (P9 5.83 % vs non-P9 7.96 %). **A plausible reading, not
tested:** `P9` collapses the representation to a single integer and is therefore a very **coarse**
contract — coarse contracts make more subsets Zero and leave fewer distinctions available to violate.
**`[PROP]`, mechanism untested.**

> **This is why the cancelling contract could never have been "the cause" of higher-order behaviour.
> It mostly suppresses it.**

---

# 4. Wording corrections adopted

| ❌ withdrawn | ✅ recorded |
|---|---|
| "Element-wise transformations yield `k = 1`" | **falsified** — 4.17 % under a cancelling contract |
| "Transformations reading relations yield higher order" | *In the tested families, relational transformations have much higher rates of `k>1`/irreducibility* — **and the empty cell is the sharp result** |
| "the mechanism is `T`" | **the mechanism is relational structure in `T` OR `Π`; `T` dominates in magnitude** |

**On "irreducible" — a clarification that matters:** it does **not** mean infinite-order. It means
**no proper-subset Zero-status information suffices**. That is a **finite-information statement about
a specific level**, not a claim about an abstract interaction order.

---

# 5. `[EXP/METHOD]` A methodological result worth keeping

> **A common base seed is not sufficient to establish a paired comparison when downstream random
> consumption differs.**

Drawing from pools of different size desynchronizes the stream, so two "same-seed" arms can differ in
factors nobody varied deliberately. **This is what defeated my `O2` comparison**, and it is a general
trap for factorial designs built on a shared RNG.

---

# 6. The candidate concept — **not** proposed for Theory v1.2

```
arity_T,Π(S)  =  the smallest order of relational information required to determine
                 the effect of T on S under Π
```

`arity = 1` locally decomposable · `2` pair-dependent · `3` triple-dependent ·
`= |S|` irreducible **at the tested definition**.

**`[PROP]`. Not introduced into the theory, not a primitive, not a kernel concept.** The next
experiment that would earn it is a **controlled arity experiment**: hold `D` and `Π` fixed and vary
**only the interaction arity of `T`** — turning the observational association into a causal one.

---

# 7. Status after this analysis

| claim | status |
|---|---|
| irreducibility never occurs with element-wise `T` **and** non-cancelling `Π` | **`[EXP]` — 0 / 33 840** |
| relational `T` alone produces it | **`[EXP]` — 22.18 %** |
| cancelling `Π` alone produces it | **`[EXP]` — 4.17 %** |
| ~~element-wise `T` ⟹ `k=1`~~ | **`[NEG]` — falsified by 7 witnesses** |
| relational structure *causes* higher order | **`[PROP]`** — associational; the controlled arity experiment is not yet run |
| cancelling `Π` suppresses irreducibility under relational `T` | **`[PROP]`** — observed twice, mechanism untested |
| `Zero` inherently higher-order | **`[NEG]`** |
| an algebra / carrier / kernel identified | **No** |

**Nothing frozen · Theory v1.2 unchanged · no v1.3 · no kernel · no algebra · `arity` not adopted.**
