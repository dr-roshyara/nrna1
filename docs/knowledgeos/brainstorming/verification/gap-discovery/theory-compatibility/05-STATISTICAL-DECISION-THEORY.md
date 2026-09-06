> 🔴 **CLAIMS WITHDRAWN 2026-09-02** — see [`gap-update-2026-09-02/08`](../gap-update-2026-09-02/08-SCOPE-ERROR-AND-CORRECTIONS.md). This document's executable probe covered **564 of 3 136+ `brainstorming/` files** and used **glyph-literal patterns over a LaTeX corpus**. Withdrawn: *"`(Ω,𝓕,P)` = 0 in both lanes"* (a **measurable Knowledge Space `KS=(𝒳,𝒜)` with a filtration is DEFINED**; only the probability law is deferred); *"`Θ` has never been raised"* (**it is `𝒜_Q`, the adequately scoped alternative space, a REQUIREMENT of `Determine`**); **`DT-H4`**; and *"statistical decision theory is materially empty"* → **materially thinner than the frame requires**.

# 05 — Statistical Decision Theory

---

## 1. The external theory `[EXT]`

**Wald.** A state space `Θ`, an action space `A`, a **loss** `L : Θ × A → ℝ`, data `X ∼ P_θ`, a
decision rule `δ : 𝒳 → A`, and **risk**

$$R(\theta,\delta) = \mathbb E_{\theta}\!\left[L(\theta,\delta(X))\right]$$

Criteria: **Bayes** `δ* = argmin_δ ∫ R(θ,δ)\,π(dθ)` · **minimax** `argmin_δ sup_θ R(θ,δ)` ·
**admissibility** (`δ` is inadmissible if some `δ′` has `R(θ,δ′) ≤ R(θ,δ)` for all `θ`, strict
somewhere).

**Savage / vNM `[EXT]`.** Expected-utility representation requires the preference relation `≽` to be
a **complete** weak order (plus continuity/independence). **Completeness is the axiom that does the
work**: without it no utility function represents `≽`.

**Decision under incomplete preference `[EXT]`.** If `≽` is a *partial* order:
- **Bewley (Knightian) decision theory** — `≽` incomplete, represented by a *set* of priors;
  `f ≽ g` iff `E_π[f] ≥ E_π[g]` for **all** `π` in the set.
- **Maximality / E-admissibility** (Levi, Walley, Seidenfeld) under imprecise probability.
- **Statewise dominance** — the only criterion requiring no probability at all, and it is
  **very incomplete**.

---

## 2. Correspondences

### 2.1 The corpus builds Wald's frame and then declines its engine `[INF]`

| KnowledgeOS `[CORPUS]` | Wald `[EXT]` | Kind |
|---|---|---|
| `D = {Migrate, Postpone, ChangePlan, Reject}` (§25H.1) | **action space `A`** | **exact** |
| `U(o)` over outcomes (§25H.4) | **negative loss `−L`** | **exact in role** |
| `EU(d\|K) = Σ_o P(o\|d,K)U(o)` (§25H.5) | **Bayes risk** | **exact — when `P` exists** |
| `d* = argmax_d EU(d\|K)` (§25H.5) | **Bayes rule** | **exact — when `P` exists** |
| **`Preference(d₁,d₂)` / `Dominates(d₁,d₂)`** (§25H.6) | **incomplete preference** | **exact — §2.2** |
| "governance and safety constraints" (§25H.5) | **constrained action space `A(K) ⊆ A`** | **exact in role** |
| **action ≠ decision** (§25H.1) | `δ` selects; execution is separate | **exact** |
| `Ideal State I_t` | *(not `Θ`)* — see §2.3 | **partial** |
| `Determination` | an **estimator** / inference stage, prior to `δ` | **partial** |
| `Proposal` (`LLM proposes; Lord evaluates`) | *(none)* | **ABSENT** — §3.4 |
| `Θ` (states of nature) | *(none in the corpus)* | **ABSENT** — §3.1 |

### 2.2 Dominance-only decision is a named framework `[INF]`

`[CORPUS]` §25H.6: *"Sometimes `P(o|d,K)` cannot reasonably be established. **We must not invent
it.**"* Fallback: `Preference` / `Dominates`. §25H.20 is titled **"Risk without probabilities."**

`[EXT]` A decision theory over an **incomplete** preference relation is exactly **Bewley/Knightian**
decision theory and the **maximality / E-admissibility** family.

$$\boxed{\text{25H is a Knightian decision theory, and it is coherent as such.}}$$

`[INF]` **This is a real correspondence and the corpus does not know it.** `prior`/`posterior` = 0
occurrences in the ratified surface; `AGM`-style literature references are absent; the corpus reached
"utility without probability" from first principles. **It is not a defect — it is a recognised
position in the literature, with known theorems.**

### 2.3 The Ideal State is a loss anchor, not a state of nature `[INF]`

`[CORPUS]` `I_t` is boxed **`≠ Knowledge State`** and **`≠ Truth`** — *"the Knower's model of the
**desired** knowledge state."*
`[EXT]` `Θ` is what is *unknown and true*; the loss `L(θ,a)` is indexed by it.

`[INF]` `I_t` is **not** `Θ` — it is a **target**, so it belongs in `L`, not in `Θ`:

$$L(\cdot, a) \;=\; d\!\left(K_{t+1}(a),\, I_t\right) \qquad \textbf{[PROP]}$$

`[CORPUS]` Q12 — *"how do we measure distance to the ideal state?"* — is exactly the demand for `d`.
**`[CORPUS]` And `d` does not exist**: `G-12`, no empirical relational structure for any epistemic
quantity. **`[PROP]` stated only to make DT-H3 falsifiable; not proposed for adoption.**

---

## 3. Incompatibilities

### 3.1 There is no state space `Θ` `[INF]`, structural

`[EXT]` Every object in Wald's framework is indexed by `θ ∈ Θ`: loss, risk, admissibility, minimax.
`[CORPUS]` KnowledgeOS has `K_t` (what is *believed*) and `I_t` (what is *desired*) — **no set of
possible true world-states.** And `[CORPUS]` the Gärdenfors extraction declines possible worlds
explicitly.

$$\boxed{\text{Without }\Theta,\; R(\theta,\delta) \text{ is not well-formed — and }R(\theta,\delta)\text{ has 0 occurrences in both lanes.}}$$

`[INF]` **So risk, admissibility and minimax are all unavailable**, not by choice but because their
index set is absent. **This is a stronger obstruction than the missing `P`.**

### 3.2 No loss function exists `[CORPUS]`, measured

`loss` ≈ **0 ratified, 2 corpus**; `R(θ,δ)` **0 both**. And `[CORPUS]` §25H.4 states of its own `U`:
*"These numbers are **illustrative only**. They must never be invented by the AI and presented as
organizational truth."*

`[INF]` **The corpus has an action space and no objective function** — and forbids inventing one.
That is a defensible governance stance and it means **`argmax` is not computable**: `argmax` = 0
occurrences, consistent with §25H.6's fallback.

### 3.3 Dominance is very incomplete `[INF]`, and this is the sharpest practical finding

`[EXT]` Statewise dominance is a **strict partial order**. On realistic problems it leaves most pairs
unranked; with `n` actions it typically yields a large maximal set, not a singleton.

`[CORPUS]` §25H.5 nonetheless writes `d* = argmax_d EU(d|K)` — a **unique** selection.

$$\boxed{\text{When probability is unavailable, }\textbf{Dominates}\text{ generally yields a SET of maximal actions, not a unique } d^*.}$$

`[INF]` **The corpus's decision layer therefore has two modes with different output types** — a point
(`argmax`, when `P` exists) and a set (maximality, when it does not) — **and §25H does not say which
one `DecisionResult` carries.** `[CORPUS]` §25H.11 defines `DecisionResult`; the type question is not
addressed there. **Recorded as DT-H2.**

### 3.4 Authority and proposal have no counterpart `[INF]`

`[EXT]` Wald's `δ` is chosen by the analyst; there is no notion of *permission to act*, and no
separation between *who proposes* and *who authorizes*.
`[CORPUS]` `LLM proposes; Lord evaluates; Governance authorizes; Executor acts` — a **four-role
separation**.

`[INF]` The nearest external expressible fragment is the **constrained action space** `A(K)` (§2.1) —
which captures *feasibility* but **not** the role separation. **Nothing in statistical decision theory
distinguishes "may propose" from "may authorize."** Missing primitive on the external side.

### 3.5 `Determination ≠ Decision`, and the corpus is right `[INF]`

`[CORPUS]` §25H.1 boxes **action ≠ decision**; `Determination ≠ Decision` is stated 19× (GN-84).
`[EXT]` Statistical decision theory does separate **inference** (estimation) from **decision**
(action under loss) — Wald unified them but the distinction survives as estimator vs decision rule.

`[INF]` **This is a correspondence in the corpus's favour**: the three-way `Determination /
Decision / Action` split is *finer* than Wald's two-way, and the extra cut — decision vs execution —
is the one governance needs.

---

## 4. Missing primitives

### On the decision-theory side

| Missing | Needed for |
|---|---|
| **an authority / permission predicate** | governed action (§3.4) |
| **a proposer/authorizer role split** | `Proposal` |
| **provenance on the decision** | `[CORPUS]` §25H.12 *decision provenance* has no Wald counterpart |
| **a goal-state index** | `Ideal State` as loss anchor rather than `Θ` |
| **tolerance of `Conflict` in the evidence** | `Σ=(1,1)` |

### On the KnowledgeOS side

| Missing | Note |
|---|---|
| **`Θ`** | **the deepest gap here** — blocks risk, admissibility, minimax, all at once |
| **a loss function** | ≈0 occurrences, and §25H.4 forbids inventing values |
| **`P(o\|d,K)`** | declined by T-3 |
| **an output type for `DecisionResult`** | point or set? (§3.3) |
| **a metric `d(K,I)`** | Q12 asks for it; `G-12` says it does not exist |

---

## 5. Falsifiable hypotheses

| # | Hypothesis | Falsifier |
|---|---|---|
| **DT-H1** | 25H is a **Knightian / maximality** decision theory: its `Dominates` fallback coincides with statewise dominance, and its `EU` branch with Bayes | exhibit a 25H decision rule that is neither |
| **DT-H2** | `Dominates` alone does **not** yield a unique `d*` on any problem with ≥3 non-comparable actions — so `DecisionResult` must be **set-valued** in the no-probability mode | exhibit a corpus rule that returns a unique action from dominance alone without a tie-break; **or** find a `DecisionResult` type declaration that settles it |
| **DT-H3** | No `L` satisfying Q12 is definable without a metric on `I_t`'s carrier | define one and show it Roberts-admissible (`07` §4) |
| **DT-H4** | `R(θ,δ)`, admissibility and minimax are **inexpressible** in KnowledgeOS as it stands, because `Θ` is absent — and this is **independent** of T-3 | construct `R(θ,δ)` from corpus constructs; you must first exhibit `Θ` |
| **DT-H5** | The `Determination / Decision / Action` three-way split is **strictly finer** than any two-way split in Wald's framework | exhibit a Wald-framework distinction that separates decision from execution |

---

## 6. Verdict for this theory

$$\boxed{\textbf{STRUCTURALLY COMPATIBLE, MATERIALLY EMPTY}}$$

- **The frame matches almost perfectly**: action space, utility-as-negative-loss, Bayes rule,
  constrained actions, and a decision/execution split *finer* than Wald's.
- **The engine is absent.** `Θ` = absent · `L` ≈ absent · `P` = declined · `R(θ,δ)` = 0 occurrences.
  **`argmax` is not computable, and the corpus knows it** — hence §25H.6.
- **The fallback is a recognised theory.** Decision under incomplete preferences is Knightian /
  maximality decision theory. `[INF]` The corpus arrived there independently and **should be told it
  has company**, not that it has a gap.
- **The sharpest open item is small and precise**: in the no-probability mode, is `DecisionResult` a
  point or a set? Dominance says set; §25H.5 writes `argmax`. **`[CORPUS]` §25H.11 does not resolve
  it.**
- **The deepest obstruction is `Θ`, not `P`** — and unlike `P`, `Θ` has never been declined. It has
  simply never been raised.
