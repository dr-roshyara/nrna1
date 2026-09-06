> 🔴 **CLAIMS WITHDRAWN 2026-09-02** — see [`gap-update-2026-09-02/08`](../gap-update-2026-09-02/08-SCOPE-ERROR-AND-CORRECTIONS.md). This document's executable probe covered **564 of 3 136+ `brainstorming/` files** and used **glyph-literal patterns over a LaTeX corpus**. Withdrawn: *"`(Ω,𝓕,P)` = 0 in both lanes"* (a **measurable Knowledge Space `KS=(𝒳,𝒜)` with a filtration is DEFINED**; only the probability law is deferred); *"`Θ` has never been raised"* (**it is `𝒜_Q`, the adequately scoped alternative space, a REQUIREMENT of `Determine`**); **`DT-H4`**; and *"statistical decision theory is materially empty"* → **materially thinner than the frame requires**.

# 03 — Probability Theory (and Dempster–Shafer)

---

## 1. The external theory `[EXT]`

**Kolmogorov.** A triple `(Ω, 𝓕, P)`: sample space, σ-algebra, measure with `P(Ω)=1`, `P ≥ 0`,
countable additivity. Consequences: `P(φ) + P(¬φ) = 1`; conditioning `P(A|B) = P(A∩B)/P(B)`; Bayes.

**Dempster–Shafer `[EXT]`.** A frame `Θ`, a mass function `m : 2^Θ → [0,1]` with `m(∅)=0`,
`Σ_A m(A) = 1`. Then

$$\mathrm{Bel}(A)=\!\!\sum_{B\subseteq A}\!\! m(B), \qquad \mathrm{Pl}(A)=\!\!\sum_{B\cap A\neq\emptyset}\!\! m(B), \qquad \mathrm{Bel}(A) \le \mathrm{Pl}(A)$$

`m(Θ)=1` is the **vacuous** belief function — total ignorance. Dempster's rule combines independent
sources, normalizing by the **conflict mass** `κ = Σ_{B∩C=∅} m₁(B)m₂(C)`.

**Two properties of Dempster's rule that matter below:** it **requires source independence**, and it
is **not idempotent** — `m ⊕ m ≠ m` in general.

---

## 2. Correspondences

### 2.1 The strongest exact correspondence in this document `[INF]`

`[CORPUS]` `Σ ≅ 𝒫({Support, Refute})` with four values.
`[EXT]` Take `Θ = {φ, ¬φ}`. A mass function on `2^Θ` has three non-empty cells: `m({φ})`, `m({¬φ})`,
`m(Θ)`. Define `S = [m({φ})>0]`, `R = [m({¬φ})>0]`.

| `Σ` | mass-function support | DS reading |
|---|---|---|
| `(0,0)` **Unknown** | `m(Θ)=1` | **vacuous belief function** — total ignorance |
| `(1,0)` **Supported** | `m({φ})>0`, `m({¬φ})=0` | `Bel(φ)>0`, `Bel(¬φ)=0` |
| `(0,1)` **Refuted** | `m({¬φ})>0`, `m({φ})=0` | symmetric |
| `(1,1)` **Conflict** | `m({φ})>0` **and** `m({¬φ})>0` | **`κ > 0`** — Dempster's conflict mass |

$$\boxed{\Sigma \;\cong\; \operatorname{supp}(m) \text{ on } 2^{\{\varphi,\neg\varphi\}} \text{, modulo the numeric values}}$$

`[INF]` This is **exact as a correspondence of supports** and explains three otherwise-separate corpus
facts at once:

1. **Why `Unknown ≠ Refuted`** — `[CORPUS]` §275.8 insists on it; `[EXT]` `m(Θ)=1` and `m({¬φ})=1` are
   different mass functions. **Kolmogorov cannot make this distinction**: `P(φ)=0` is `P(φ)=0`.
2. **Why `Conflict` is a normal state** — `[EXT]` `κ>0` is an ordinary condition of two mass
   functions, not an inconsistency.
3. **Why `Σ` needs two bits rather than one number** — `[EXT]` `(Bel, Pl)` is an interval, and
   `Bel(φ)+Bel(¬φ) ≤ 1` with slack `m(Θ)`. **The slack is precisely `Unknown`.**

`[CORPUS]` **Provenance — this is not new to the corpus.** Step 025c-1 §§9–11 already evaluated
Dempster–Shafer and boxed: *"Useful for epistemic uncertainty"* **but** *"Not sufficient as the
KnowledgeOS foundation by itself."* `[INF]` **My contribution is the precision of the map, not the
observation that DS is relevant.**

### 2.2 The rest

| KnowledgeOS | Probability counterpart | Kind |
|---|---|---|
| **`K_t`** | *(none)* | ABSENT — a state is not a distribution |
| **Observation** | a realization of a random variable | **analogical only** — requires `(Ω,𝓕,P)` |
| **Evidence** | a likelihood `P(O\|φ)` | **partial**; needs a model the corpus declines |
| **Determination** | a posterior / an estimator | **partial** |
| **Ideal State** | *(none)* | ABSENT |
| **Zero** | *(none)* | ABSENT — see `04` |
| **Revision** | Bayesian conditioning | **REFUTED** — §3.3 |
| **Decision** | expected utility | see `05` |

---

## 3. Incompatibilities

### 3.1 There is no probability space, in either lane `[CORPUS]`, measured

`(Ω,𝓕,P)` **as a triple: 0 occurrences in the ratified surface and 0 in 564 corpus files** — despite
179 mentions of "probability".

`[CORPUS]` And this is now a **ruling**, not an omission: Step 282 T-3 —
**`T-3 = NOT REQUIRED FOR THE CORE THEORY`**. `[INF]` So the correct classification is **not** "gap"
but **declared out of scope**: the theory has chosen not to take probability as a primitive.

### 3.2 `Conflict` is not a coherent probability `[INF]`

`[EXT]` `P(φ) + P(¬φ) = 1` — high support for both is incoherent, and a Dutch book follows.
`[CORPUS]` `Conflict (1,1)` is normal and persistent.
`[INF]` **`Σ` cannot be a probability measure.** It can be a *support pattern of a mass function*
(§2.1), where `κ>0` is ordinary. **This is the precise sense in which the corpus's refusal of
probability is not a deficiency but a modelling choice with a named alternative.**

### 3.3 OR-merge is not Dempster's rule `[INF]`, and it is testable

`[CORPUS]` Step 272B's merge law: `σ₁ ⊔ σ₂ = (s₁∨s₂, r₁∨r₂)` — **idempotent**, associative,
commutative.
`[EXT]` Dempster's rule is **not idempotent**: combining a source with itself *sharpens* belief.

$$\boxed{\text{OR-merge} \neq \oplus. \quad \text{OR-merge is a join on the support lattice; } \oplus \text{ is a normalized product on masses.}}$$

`[INF]` **Consequence, and it favours the corpus.** Idempotence is exactly the property you want when
the same evidence may be counted twice — and `[CORPUS]` TG-11 records that KnowledgeOS **has no
`dedup` operator** and that *"corroboration ≡ duplication"*. **Under Dempster's rule that defect would
silently inflate belief; under OR-merge it cannot.** The corpus's weaker rule is *safer* given its own
known gap.

### 3.4 Dempster's rule needs independence, which KnowledgeOS cannot express `[INF]`

`[EXT]` `⊕` is only justified for **independent** sources.
`[CORPUS]` TG-02: *"`Evidence` has `source` but **no independence relation**."* And `11` PL-4:
absence of a `derives` edge is **not** evidence of independence.
`[CORPUS]` The corpus found this itself: 025c-1 §10 is titled *"Dempster–Shafer problem — source
dependence."*

`[INF]` **So even the strong §2.1 correspondence stops at the support level.** The *numeric* DS layer
is unavailable to KnowledgeOS **not** because probability was declined, but because the independence
primitive is missing. **Two independent blockers, and only one of them is a choice.**

### 3.5 Revision is not conditioning `[INF]`

`[EXT]` Bayesian conditioning is defined only for `P(B)>0`, is **commutative** in the order of
independent evidence, and cannot recover from `P(B)=0`.
`[CORPUS]` `Revision = EventAddition + StateReDerivation` over six `RevisionType`s including
`Retraction` and `ModelRevision`. `[INF]` Conditioning has **no counterpart for retraction** (a
zero-probability event cannot be un-conditioned) and none for model change. **Jeffrey conditioning
generalizes the first partially; neither addresses the second.**

---

## 4. Missing primitives

### On the probability side

| Missing | Needed for |
|---|---|
| a distinction between **`m(Θ)=1`** and **`P(φ)=0`** | `Unknown ≠ Refuted` — Kolmogorov cannot; DS can |
| tolerance of `κ>0` as a *state* | `Conflict` |
| retraction | `RevisionType::Retraction` |
| interpretation change | `ModelRevision` |
| provenance / authority | governed admission |

### On the KnowledgeOS side

| Missing | Note |
|---|---|
| **`(Ω,𝓕,P)`** | 0 occurrences — **declared out of scope by T-3, not an accident** |
| **an independence relation** | TG-02 — blocks the numeric DS layer independently of T-3 |
| **a `dedup` operator** | TG-11 — why idempotent merge is currently the safe choice |
| **mass values** | `Σ` carries support only; `[CORPUS]` TG-12: `Σ.str` has no rule |

---

## 5. Falsifiable hypotheses

| # | Hypothesis | Falsifier |
|---|---|---|
| **PR-H1** | `Σ ≅ supp(m)` on `2^{φ,¬φ}` is exact: every `Σ` value is the support of some mass function and conversely | exhibit a `Σ` value with no mass-function support, or a support pattern `Σ` cannot express |
| **PR-H2** | OR-merge is the **join on the support lattice**, hence idempotent; Dempster's `⊕` is not | show `⊕` idempotent, or OR-merge non-idempotent |
| **PR-H3** | Adopting `⊕` would **worsen** KnowledgeOS while TG-11 is open, by inflating belief on duplicated evidence | exhibit a `dedup` operator in the corpus, which would remove the objection |
| **PR-H4** | No Kolmogorov measure distinguishes `Unknown` from `Refuted` | exhibit one — it would need `P(φ)=0` to differ from `P(φ)=0` |
| **PR-H5** | The numeric DS layer is blocked by TG-02 **independently** of T-3 — so reversing T-3 alone would not unlock it | derive `⊕`'s justification without an independence assumption |

---

## 6. Verdict for this theory

$$\boxed{\textbf{KOLMOGOROV: INCOMPATIBLE AND DECLINED · DEMPSTER–SHAFER: EXACT AT THE SUPPORT LEVEL, BLOCKED AT THE NUMERIC LEVEL}}$$

- `Σ` **cannot** be a probability — `Conflict` is incoherent under additivity.
- `Σ` **is** the support of a mass function on `{φ,¬φ}`, and that map explains `Unknown ≠ Refuted`,
  the normality of `Conflict`, and why two bits are needed. **Strongest exact correspondence in this
  package.**
- **Two independent blockers on going further:** T-3 declines probability *(a choice)*, and TG-02's
  missing independence relation blocks Dempster's rule *(a gap)*. **Only the second is a defect.**
- `[CORPUS]` The corpus reached the qualitative version of this in 2026-08-27 —
  *"useful … not sufficient as the foundation by itself."* **`[INF]` The precise reason it is not
  sufficient is that `⊕` needs an independence relation KnowledgeOS does not have.**
