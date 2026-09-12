# 11 — Independent Verification of `Closure(𝒦₉)`

**2026-09-06.** Target: `docs/knowledgeos/reviews/2026-09-06-KOS-K9-CLOSURE-CONDITIONAL-DEPENDENCY-COMPUTATION.md`
(review lane, 09:34 today).

**The authorized act had already been performed by another lane before this session reached it.**
This lane's function is verification, so this document **re-executes it independently** rather than
performing it a second time.

> ⛔ **Guardrail carried forward verbatim.** This is a **conditional dependency computation**, not a
> proof of kernel minimality. `Theory v1.2` FROZEN · kernel NOT SELECTED · `𝒪_core` NOT FROZEN ·
> no carrier declared. **Nothing here is adjudicated, and no result is "the minimum kernel."**

**Programs:** [`exec/verify_k9_closure.py`](./exec/verify_k9_closure.py) ·
[`exec/extend_k9_worlds.py`](./exec/extend_k9_worlds.py) · transcripts `OUT-*.txt`.
**Independence:** both **import `G` and `closure()` directly from the source program**
`readiness/exec/minimum_implementable.py` — not from the review lane's copy.

---

## 1. Provenance check — is the transcription faithful?

The review lane states its graph was *"reproduced verbatim"* from the source. **Checked, not
assumed:** both `G` literals parsed and compared node by node.

```
source nodes 29   ·   review-lane nodes 29
only in source: []        only in theirs: []
nodes with DIFFERENT tuple text: 0
```

$$\boxed{\textbf{Byte-identical across all 29 nodes. The transcription claim is CONFIRMED.}}$$

## 2. Every published number, re-executed

| claim | published | recomputed | |
|---|---|---|:--:|
| control `\|Closure(𝒦₄)\|` | **18**, 15 blocked | **18**, 15 blocked | ✅ |
| `\|Closure(𝒦₉)\|` | **20** | **20**, 17 blocked | ✅ |
| `Closure(𝒦₄) ⊆ Closure(𝒦₉)` | FALSE | FALSE | ✅ |
| `Closure(𝒦₉) ⊆ Closure(𝒦₄)` | FALSE | FALSE | ✅ |
| `𝒦₄ ∖ 𝒦₉` | `History, Rejection, Replay` | identical | ✅ |
| `𝒦₉ ∖ 𝒦₄` | `Assessment, Authority, Authorization, Determination, Qualification` | identical | ✅ |
| reached by neither | `Gamma, Lineage, Measurement, Missingness, Orphan, Q_t` | identical | ✅ |
| C-1→`Sigma` | 16, drops `Equality, O_core, Operations, Transformation` | identical | ✅ |
| C-2→`Authority` | 20, no change | identical | ✅ |
| C-4→`Gamma` | 21, +`Gamma` | identical | ✅ |
| C-5→`Assessment` | 20, no change | identical | ✅ |
| C-6→`Missingness` | 22, +`Missingness`, `Q_t` | identical | ✅ |
| §E: 4 of 8 "justified exclusions" fail | `Determination, Assessment, Qualification, Authorization` | identical | ✅ |
| triply-robust blocked set | `Identity, InvariantReg, K, Proposition, Relation` | identical | ✅ |
| range over 14 worlds | **15 … 22** | **15 … 22** | ✅ |

$$\boxed{\textbf{15 of 15 reproduce. Nothing in the computation is overstated.}}$$

## 3. The degeneracy warning is correct — and I checked it the hard way

The review lane warns that `Qualification ∈ Closure(𝒦₉)` is *definitional*, since `C-7`'s seed **is**
`Qualification`. **Tested by removing `C-7` and recomputing:**

```
Qualification ∈ Closure(𝒦₉)                        True
Qualification ∈ Closure(𝒦₉ ∖ {C-7})                False
```

**It is reached by no other path.** ✅ **The warning is not merely prudent — it is exactly right,
and the lane flagged its own strongest-looking result as evidentially empty.** That is the behaviour
the discipline is for.

## 4. Two results that STRENGTHEN under a wider world set

Their design: **14 worlds** = 2 `InvariantReg`-edge variants × (`𝒦₄` + 6 one-at-a-time mappings).
Mine extends it to the **full product** over the 5 perturbable rows: **66 worlds**, everything else
identical.

### 4.1 The range widens

| | worlds | range |
|---|---:|---|
| review lane | 14 | **15 … 22** of 29 |
| **extended** | **66** | **15 … 23** of 29 |

`[EXP]` **Their negative claim understates itself.** *"The size of the kernel's requirement set is
not determined by the corpus"* holds over a **wider** interval than measured — **9 of 29 constructs
of spread**, from mapping and edge choices alone, with no new evidence and no new definition.

### 4.2 The triply-robust core is unchanged at 4.7× the world count

$$\boxed{\{\,Identity,\ \mathbf{InvariantReg},\ K,\ Proposition,\ Relation\,\}\qquad\textbf{identical over 66 worlds}}$$

`[EXP]` **This is the strongest positive result in the package**, and it survived the harder test.
Their `[REC]` — that **`InvariantReg` is the one item blocked under every reading of every axis** —
holds under the extended set too. It is the register `G-67` has never enumerated.

### 4.3 A new number: `O_core` is a coin flip

$$\boxed{\mathcal O_{core}\in Closure \text{ in } \mathbf{34\ of\ 66}\ \text{worlds} = \mathbf{51\,\%}}$$

`[EXP]` **New in this verification.** The review lane established that `𝒪_core` enters through *one
seed of one row* and is therefore mapping-fragile. Quantified over the full product, its presence is
**indistinguishable from a coin toss on modelling choices nobody has made.**

⚠️ **Read this precisely.** It is not *"`𝒪_core` is 51 % likely to be in the kernel."* There is no
probability distribution over worlds and none is claimed. It is: **half the admissible readings put
it in and half leave it out, and the corpus does not choose.**

## 5. The one discrepancy, and why it is not one

My first pass reported **16 … 23 over 32 worlds** against their published 15 … 22, and I did not
report that as a mismatch. **The designs differ:** theirs crosses the mapping perturbations with the
**structural-only `InvariantReg` variant** (the source program's own §F); my first pass held the
edge at *epistemic*. The structural variant is what reaches 15. **Both are correct for their own
design**, and the union is §4.1.

`[INF]` **Recorded because it is the failure mode this lane keeps hitting:** a number that disagrees
is a difference of *design or scope* until proven otherwise. **The first move on a mismatch is to
read the other design, not to report a contradiction.**

## 6. What this settles, and what it does not

| ✅ settled by execution | 🔴 untouched |
|---|---|
| the two bases are **incomparable** — neither contains the other | which reading is right — **a governance act** (`𝒦₄` over-stipulates vs GN-77 under-derives) |
| `𝒦₄` stipulates 2 seeds no `C`-row forces (`Rejection`, `Replay`) | **sufficiency of either basis** — still untested; 88 of 99 contract cells empty |
| the `C`-rows force 8 seeds `𝒦₄` never names | `RejectIllegally` ↔ `I-12` ↔ Article 8 contradiction |
| 4 of 8 §E "justified exclusions" were relative to `𝒦₄`, not absolute | `Replay`'s type-level contradiction (capability in `𝒦₄`, property in GN-77) |
| `InvariantReg` is blocked in **every** world (66/66) | `Qualify`'s closure under `𝒦₉` — **still uncomputed**; its four competing signatures (`10` `CR-5`) are prior to it |
| the requirement-set **size** is undetermined, over **15…23** | anything about **minimality** |

$$\boxed{\textbf{The next act was authorized, was already performed, and now REPRODUCES independently.}}$$

## 7. `[REC]` What this authorizes — and what it does not

**Does not authorize:** calling `𝒦₉` the kernel · freezing `𝒪_core` · opening Theory v1.3 · treating
15…23 as a bound on anything but the tested worlds.

`[REC]` **Two consequences worth carrying forward, both of them small:**

1. **`InvariantReg` (`G-67`) is now the single most evidenced blocker in the estate** — blocked under
   66 of 66 worlds, across two bases, six mappings and both register readings. **It is also the one
   my own package named as the keystone and then mis-diagnosed as merely unenumerated.** Enumerating
   it is a derivation, not a decision.
2. **`𝒪_core`'s 51 % is a reason NOT to run a minimality argument next.** Any minimality result
   computed now inherits a coin flip. **This is independent evidence for the estate's own ruling that
   kernel selection comes last.**

**Neither is a ruling. Both are recommendations, and the second agrees with the guardrail under
which the act was authorized.**
