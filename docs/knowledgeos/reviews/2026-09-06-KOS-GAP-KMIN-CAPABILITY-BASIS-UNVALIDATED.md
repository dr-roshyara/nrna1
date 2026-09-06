# GAP RECORD — `𝒦_min` is a **stipulated** capability basis, and a rival with better standing exists

**Date:** 2026-09-06 · **Status:** `[OPEN]` · **Analysis only. Nothing computed, nothing frozen, nothing ratified.**
**Blocks:** `D-1A` (the operation-necessity proof) — **`D-1A` must not run yet.**
**Amends:** `2026-09-06-KOS-SURVIVING-BLOCKER-DEPENDENCY-ANALYSIS.md` · `…-GAP-OCORE-NECESSITY-PROOF.md` §3.


> ### ⚠️ **AMENDED 2026-09-06** by [`…-CAPABILITY-BASIS-LEVEL-A-FALSIFICATION.md`](2026-09-06-KOS-CAPABILITY-BASIS-LEVEL-A-FALSIFICATION.md)
> **Two corrections against this document.** ① Its claim that `GN-77` was *"independently re-verified
> by `exec/verify_readiness_claims.py`"* is **FALSE** — that script checks C1–C4 (vocabulary
> disjointness, 0 operation signatures, postcondition and precondition counts); **none is the nine.**
> ② Its framing of `𝒦₄` and `𝒦₉` as **rivals** is withdrawn: they are **INCOMPARABLE, at different
> levels** — `𝒦₄` machine-shaped, `𝒦₉` domain-shaped — with one **type contradiction** (`Replay` is a
> capability in `𝒦₄` and a *property* in `𝒦₉`). **`𝒦₄`'s stipulated status stands.**

---

## 1. The challenge, and it is correct

$$\mathcal K_{\min} = \{\text{HoldState},\ \text{TransitionLegally},\ \text{RejectIllegally},\ \text{Replay}\}$$

**Where did that come from?** If it is a *proposed decomposition*, the necessity proof is circular:

$$\text{proposed capabilities} \to \text{test operations against them} \to \text{declare operations necessary}$$

— which establishes **minimality relative to the chosen capability model**, not minimality of the
KnowledgeOS kernel. **The corpus has already demonstrated that different minimality predicates
produce different registries.**

---

## 2. ⭐ Verified: `𝒦_min` is **stipulated, not derived**

`readiness/07-MINIMUM-IMPLEMENTABLE-KNOWLEDGEOS.md`, opening **Method** paragraph, verbatim:

> **"A reference kernel *must be able to* (1) hold a knowledge state, (2) transition it legally,
> (3) reject illegally, (4) replay from history. Take the transitive dependency closure of those four
> capabilities over the construct graph. **Everything outside the closure is excluded by a dependency
> fact, not by judgement.**"**

| | |
|---|---|
| **No citation** accompanies the four | verified — the paragraph cites nothing |
| **No derivation** from Theory v1.2 | verified — none appears in the document |
| the phrase *"must be able to"* | **a stipulation**, in a Method paragraph |

> ### `[NEG]` **The guarantee *"excluded by a dependency fact, not by judgement"* covers only the CLOSURE of the four. It does not cover the four.**
>
> The document is internally honest — it says *"take the closure **of those four**"* — but the four
> themselves entered by judgement, and every downstream exclusion inherits that.

---

## 3. ⭐⭐ A rival capability basis exists — and it has **better standing**

`readiness/03-OPERATION-TRANSFORMATION-READINESS.md`, `GN-77`, independently re-verified by
`exec/verify_readiness_claims.py`:

$$\boxed{\textbf{9 capabilities are canonically REQUIRED. 0 operations are canonically DEFINED.}}$$
$$\text{99 contract cells } (9 \times 11): \quad \textbf{2 fixed} \cdot \textbf{9 partial} \cdot \textbf{88 empty}$$

$$\boxed{\mathcal K_{\text{GN-77}} \;(\lvert\cdot\rvert = 9,\ \textbf{"canonically REQUIRED"}) \;\;\neq\;\; \mathcal K_{\min} \;(\lvert\cdot\rvert = 4,\ \textbf{stipulated in a Method paragraph})}$$

> ### `[EXP]` **This is exactly the predicted $\mathcal K_1 \neq \mathcal K_2$ situation — and the two do not have equal standing. The nine carry the word *canonically REQUIRED*; the four carry *"must be able to"*.**

**Both live in the same document family**, `readiness/01` and `readiness/03` and `readiness/07`, and
**neither cites the other on this point.** The 4-vs-9 divergence appears never to have been raised.

---

## 4. ⚠️ What this does to MY OWN dependency conclusion

My dependency analysis concluded:

> *"`Qualify`'s only edge is `Qualify → projection`; the closure computes Qualification **out** of the
> kernel; therefore `Qualify` does not block the canonical kernel."*

`[NEG]` **That conclusion is RELATIVE TO the 4-capability stipulation.** The closure it rests on is
`exec/minimum_implementable.py`'s closure **of those four**. Under $\mathcal K_{\text{GN-77}}$ the
closure has never been computed, and **whether `Qualify` remains outside it is untested.**

**I am not claiming `Qualify` re-enters.** I am recording that **my exclusion of it is
model-relative and its robustness is unknown** — which is the same error, one level up, that the
re-verification caught one level down.

| my claim | standing now |
|---|---|
| `Qualify` is not on the kernel's critical path | **`[OPEN]` — holds under $\mathcal K_{\min}$; untested under $\mathcal K_{\text{GN-77}}$** |
| `𝒪_core` is the root | **unaffected** — `𝒪 → kernel` is an edge in the step-289 graph, independent of the closure computation |
| 5 blocking / 3 parallel / 1 future scope | **`[OPEN]`** — the partition is model-relative |

---

## 5. The two nested necessity proofs

$$\boxed{\text{Theory} \to \text{necessary capabilities} \to \text{minimal operation classes} \to \text{canonical vocabulary} \to \text{freeze}}$$

**not**

$$\text{candidate capabilities} \to \text{candidate operations} \to \text{freeze}$$

### Level A — **capability** necessity *(must come first)*

Prove or falsify that $\mathcal K_{\min}$ is a necessary and sufficient semantic basis for **frozen
Theory v1.2**. Per capability:

- Is it **derivable** from Theory v1.2, or merely an implementation convenience?
- Can two capabilities be **combined** without loss?
- Can one be **eliminated** while preserving all required theory-level behaviour?
- **`Replay`** — genuinely primitive, or a consequence of **lineage + transition history**?
- **`RejectIllegally`** — a capability, or merely a **property of `δ`**?
- **`HoldState`** — primitive, or simply **the existence of `K_t`**?
- **`TransitionLegally`** — one capability, or several?

### Level B — **operation** necessity *(only if Level A survives)*

Compute $\operatorname{Adeq}_K(\mathcal O)$ and then $\mathfrak M_K$ **against the surviving basis**.

### And the recursion must be checked, not assumed

If several capability bases survive, then

$$\mathcal K_1 \neq \mathcal K_2 \qquad\text{yet possibly}\qquad \operatorname{Cap}(\mathcal O; \mathcal K_1) = \operatorname{Cap}(\mathcal O; \mathcal K_2)$$

— **representation-relative minimality again, at the capability level.** `[REC]` **Level A must
report whether the bases are equivalent, refining, or genuinely rival** — the same A–G classification
the carrier re-audit used. **Otherwise the problem simply moves up one floor.**

---

## 6. Status register

| item | status |
|---|---|
| previous 7-blocker conclusion | **SUPERSEDED** |
| canonical `K_t` | **RESOLVED / RATIFIED** |
| `Contr` / non-explosion | **RESOLVED** — adopted |
| `⪰` multiplicity | **RESOLVED** — level / complementarity |
| `dedup` | **mechanism layer, not kernel** |
| `Σ` multiplicity | **RESOLVED by factorisation** |
| **`Qualify`** | **still a genuine formal blocker** — *for what it gates*; kernel-relevance now `[OPEN]` (§4) |
| **`𝒪_core`** | **genuine unresolved kernel-selection problem** |
| five-operation nucleus | **CANDIDATE ONLY** — ⊇, never = |
| **`𝒦_min`** | 🔴 **CANDIDATE adequacy basis — UNVALIDATED. A rival with better standing exists (`GN-77`, 9 capabilities, "canonically REQUIRED").** |
| `D-1A` (compute $\mathfrak M_K$) | **BLOCKED — premature until Level A** |
| canonical operation freeze | **PREMATURE** |
| implementation | **NOT YET** |

---

## 7. The distinction that now governs the lane

> **We have discovered that *operation minimality* is a genuine research question.
> We have NOT proved that the proposed TEST for operation minimality is itself canonical.**

`[REC]` **Next task is a falsification, not a computation:**

$$\boxed{\text{Is } \mathcal K_{\min} \text{ DERIVED from the corpus, or merely another candidate model?}}$$

**§2 already answers half of it: it is stipulated.** What remains for Level A is whether a *derived*
basis exists, whether `GN-77`'s nine are it, and how the two relate.

**Theory v1.2 FROZEN · kernel NOT SELECTED · `𝒪_core` NOT FROZEN · `𝒦_min` NOT VALIDATED · no code.**
