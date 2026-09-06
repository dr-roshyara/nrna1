# R — Can Effective Complexity Be Defined Through Distinguishability? · `KR-DIST-2026-09-02`

**Commissioned question:** *can effective hypothesis complexity be defined through
distinguishability / equivalence of hypotheses under a specified evidence-and-observation regime Λ?*
— with the hope that it would connect `N_eff` to the still-open `≡_sem`.

**Status** `[EXP]` — **FROZEN 2026-09-02** by supervisory review · **Baseline** v1.2, unchanged ·
**No v1.3** · **no further `N_eff` formula hunt authorized.**

## Answer, up front

> **No — not as posed.** The construction fails at its first step, and the standard repair fails at
> its second. **Two independent obstructions**, and the second is the more informative.

---

# 1. Obstruction 1 — `~_Λ` is **not transitive**, so `H/~_Λ` is not well defined `[NEG]`

Regime `Λ`: `m = 40`, `σ = 1.0`, `α = 0.05` → standard error `0.1581`, resolution on a score
difference `δ = 0.3099`.

**The chain (sorites) construction.** Place 12 hypotheses with means spaced at `0.9 × δ`:

| | |
|---|---|
| adjacent pairs indistinguishable | **true** |
| **endpoints distinguishable** | **true** (span 3.068) |

```
H₁ ~ H₂ ~ H₃ ~ … ~ H₁₂        pairwise indistinguishable
H₁ ≁ H₁₂                      endpoints distinguishable
```

> `[NEG]` **Λ-indistinguishability is a tolerance relation, not an equivalence relation.**
> It is reflexive and symmetric but **not transitive**. Therefore `H/~_Λ` **does not exist**, and
> `|H/~_Λ|` cannot serve as effective complexity.

**An honest detail about where non-transitivity lives.** A *direct* search for a transitivity witness
inside the equicorrelated space returned **none** — because there every pair has the *same*
separation, so indistinguishability is all-or-nothing and vacuously transitive. **Non-transitivity
appears only when the space contains a range of separations**, which is the general case. The
homogeneous space hides the defect.

---

# 2. The standard repair — and Obstruction 2 `[NEG]`

When indistinguishability is a tolerance relation, the textbook replacement for a quotient is a
**δ-packing number**: the largest set of pairwise-*distinguishable* hypotheses. It needs no
transitivity and is always well defined.

**Does it predict the measured burden?**

| space | `sd(Sᵢ−Sⱼ)` | pairwise distinguishable? | **packing** | **measured `N_eff`** | ratio |
|---|---|---|---|---|---|
| grouped, 50 groups | 1.414 | yes | 50 | 51.1 | **0.98** |
| grouped, 200 groups | 1.414 | yes | 200 | 201.9 | **0.99** |
| independent | 1.414 | yes | 1 000 | 940.6 | **1.06** |
| correlated ρ=0.5 | 1.000 | yes | 1 000 | 200.6 | **4.99** |
| **correlated ρ=0.9** | 0.447 | **yes** | **1 000** | **9.0** | **111** |
| **correlated ρ=0.95** | 0.316 | **yes** | **1 000** | **4.5** | **222** |

> **Packing matches duplication almost exactly (ratios 0.98–1.06) and fails on correlation by up to
> 222×.**

## Why — and this is the substantive finding

Under equicorrelation every pair remains **pairwise distinguishable** (`sd = 0.447 > δ = 0.310` at
ρ=0.9), yet the *maximum* of 1 000 such scores behaves like ~9 independent draws.

> `[EXP]` **The multiplicity burden is driven by the JOINT extreme-value behaviour of the candidate
> scores, not by pairwise separability.** A shared component moves the candidates together, so the
> maximum is far less extreme than 1 000 independent draws would give — while every pair stays
> individually resolvable.

**Pairwise distinguishability is the wrong invariant.** It is a property of *pairs*; the burden is a
property of the *family*.

---

# 3. What this means for the `N_eff` ↔ `≡_sem` connection

The commissioned hope was that effective complexity might be `|H/~_Λ|`, connecting it to semantic
equivalence. The result is more interesting than a yes:

| | |
|---|---|
| **duplication / block structure** | **is** captured by pairwise distinguishability — packing ≈ `N_eff` to within 6 % |
| **correlation / shared structure** | is **not** captured — off by up to 222× |

> `[PROP]` **At least two empirically distinguishable mechanisms appear in the tested regimes**, and
> they need different mathematics. **"At least" is load-bearing:** the experiment has not shown these
> are exhaustive or ontologically distinct *components*. Others may exist — hierarchy, geometry,
> common latent causes, adaptive-search dependence, selection-induced dependence, temporal
> dependence. Two observed mechanisms must not become the ontology of hypothesis complexity.
>
> * **Redundancy** — candidates that *are* the same under the regime. A pairwise, `≡_sem`-shaped
>   notion handles this, and the connection to semantic equivalence is real.
> * **Coupling** — candidates that are distinct but *move together*. This is a joint/extremal
>   property and `≡_sem` does not reach it.

**So the two research threads meet only halfway.** `≡_sem` plausibly formalizes the redundancy
component. It does **not** appear to formalize the coupling component, and the coupling component is
the larger effect in every correlated space tested.

> **This does NOT refute `≡_sem`.** It shows only that `≡_sem` must not be asked to do a job it was
> never designed for. Semantic equivalence answers *"are these two representations semantically
> interchangeable?"*; it does not answer *"how does this family behave as a joint statistical
> object?"* **`≡_sem` and family-level effective complexity should remain separate concepts** unless
> a future experiment establishes a formal bridge — which is a good outcome, because it prevents
> forcing everything into one equivalence relation.

## 3.1 The three-level distinction this experiment clarifies `[EXP]`

| level | question | relevant to |
|---|---|---|
| **1 Identity / semantic equivalence** `H_i ≡_sem H_j` | are two hypotheses the same under a semantic contract? | **redundancy** |
| **2 Pairwise distinguishability** `H_i ≁_Λ H_j` | can the evidence regime tell them apart? | a **measurement** property |
| **3 Family structure** | how does the whole collection behave *jointly*? | **coupling** — where levels 1–2 are insufficient |

`[PROP]` A more mature representation might eventually be
`𝔥_Λ = (𝓗, ℛ_sem, Λ, Σ_Λ)` — **a research candidate, not a proposed Theory v1.3.**

---

# 4. Status register

| finding | status |
|---|---|
| `~_Λ` is a tolerance relation, not an equivalence relation | **`[NEG]`** — chain witness, 12 hypotheses |
| `H/~_Λ` is ill-defined; `\|H/~_Λ\|` cannot be effective complexity | **`[NEG]`** |
| Non-transitivity is invisible in a homogeneous space | `[EXP]` — an artefact-of-design caution |
| δ-packing is well defined and needs no transitivity | `[EXP]` |
| Packing predicts `N_eff` for **duplication** (0.98–1.06) | **`[EXP]`** |
| Packing fails for **correlation** (up to 222×) | **`[NEG]`** |
| Burden is a joint/extremal property, not a pairwise one | **`[EXP]`** |
| Two distinct sources of complexity reduction: redundancy and coupling | `[PROP]` |
| `≡_sem` reaches redundancy but not coupling | `[PROP]` |
| `N_eff` as a canonical object | **still not established** |

# 5. Next

> **Do not run a third formula hunt.** Two have now failed for the same underlying reason:
> `n(1−ρ)²` (fitted, then refuted on a wider grid) and packing (structural, but pairwise).

The result points somewhere specific instead:

**`[PROP]` Effective complexity has at least two components, and only one of them is a
distinguishability notion.** The next question is therefore not *"what is `N_eff`?"* but:

> **Is the coupling component expressible at all in the vocabulary of hypothesis equivalence — or
> does it require an explicitly joint object (a covariance/dependence structure carried alongside the
> hypothesis set)?**

**Scope corrected.** This originally read *"a hypothesis space … is not a set but a structured
family `(𝓗, Σ)`"*. **Too strong — the experiment does not establish that.** What it establishes is:

> `[EXP]` **A set of hypotheses alone is insufficient for this particular notion of family-level
> burden.**

The missing object *could* be `(𝓗, Σ)`; it could equally be some other joint structure. Recorded as:

> `[OPEN]` **A family-level epistemic object may require explicit dependence structure.**

**Queue unchanged:** `Factivity → Contr → ⪰`. Per the review's correction, `N_eff` and `⪰` remain
**related but independent**; this experiment does not couple them, and it adds no item that displaces
the ordering.

> **`N_eff` must NOT become the next central theory problem.** We do not yet know whether effective
> complexity is a KnowledgeOS primitive **at all** — it may be a property of a particular *evidential
> regime*, useful for statistical assessment and outside the kernel. That possibility stays open.

## 5.1 Kernel implication `[EXP]`

This result reinforces a pattern the programme has now seen repeatedly:

> **Do not put statistical mechanisms into the kernel merely because KnowledgeOS can use them.**

Candidate generation · hypothesis selection · multiplicity control · correlation modelling ·
validation · Bayesian updating — all may be important **capabilities** without being **irreducible
kernel operators**. The kernel stays at the level of the smallest invariant epistemic powers.

## 5.2 When this is revisited

Not *"what formula gives `N_eff`?"* but:

> **What is the minimal joint structure required to represent family-level epistemic dependence?**
