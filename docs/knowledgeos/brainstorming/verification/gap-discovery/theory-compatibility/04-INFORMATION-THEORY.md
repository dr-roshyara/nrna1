# 04 — Information Theory

---

## 1. The external theory `[EXT]`

**Shannon.** Over a probability space, for a discrete random variable `X`:

$$H(X) = -\sum_x p(x)\log p(x), \qquad I(X;Y)=H(X)-H(X\mid Y), \qquad D_{KL}(P\|Q)=\sum_x p(x)\log\tfrac{p(x)}{q(x)}$$

**Every one of these requires a distribution.** `H` is a functional *of `p`*, not of a set.

**The measure-free layer `[EXT]`.** Shannon's framework also has a purely *structural* component: a
random variable induces a **partition** of the sample space, and partitions form a **lattice** under
refinement (`π₁ ⪯ π₂` iff `π₁` refines `π₂`), with meet and join. Conditioning coarsens; observation
refines. **This lattice exists without any measure**; entropy is a *measure on top of it*.

**Semantic information `[EXT]`.** Bar-Hillel–Carnap and Dretske: information as *exclusion of
possibilities*; Floridi: information as well-formed, meaningful, **truthful** data — the veridicality
thesis.

---

## 2. Correspondences

| KnowledgeOS | Information-theoretic counterpart | Kind |
|---|---|---|
| **Zero** | a **coverage gap in a requirement lattice** | **partial, measure-free** — §2.1 |
| **Observation** | a refinement of a partition | **analogical** |
| **`Σ`** | *(no entropy counterpart)* | ABSENT — §3.2 |
| **Evidence** | channel output / a signal carrying information about a source | **analogical only** — needs a channel |
| **`K_t`** | *(none)* | ABSENT |
| **Ideal State** | a **target partition** | **`[PROP]` only** — §2.2 |
| **Determination / Decision / Action / Proposal** | *(none)* | ABSENT — Shannon has no agent |
| **Revision** | *(none)* | ABSENT |

### 2.1 `Zero` is a lattice difference, not an entropy `[INF]`

`[CORPUS]` `Zero(K,G,EC)` is evaluated over a **finite requirement set** `R_G = {r₁…rₙ}` with
sufficiency rules `Γ`, returning one of 10 statuses per requirement; boxed
**`Zero ≠ simple subtraction`**.

`[EXT]` The measure-free structure that fits is the **partition/coverage lattice**: requirements
generate a finite meet-semilattice; `Zero` is the set of requirements not yet in the satisfied
down-set.

$$\boxed{\text{Zero} \;\leftrightarrow\; \text{an antichain of unsatisfied requirements in a finite lattice — an ORDER, not a NUMBER}}$$

`[INF]` And the corpus's own boxed `Zero ≠ simple subtraction` is **exactly the statement that the
lattice is not a measure**: `|R_G| − |satisfied|` would be simple subtraction, and 025d rejects it
because the 10 statuses are not equivalent. **The corpus states the measure-theoretic caution
correctly without the vocabulary.**

`[INF]` **Corroboration from executed evidence.** `zero_reference.py`, which I ran in the first-order
pass, reports: *"`|gaps(A)| = |gaps(B)| = 2`, yet `A ≠ B` as work plans — any count/scalar conflates
them."* **That is a demonstration that `Zero` is not a cardinality, i.e. not a measure.** Independent
of any information theory, and agreeing with it.

### 2.2 The Ideal State as a target partition — `[PROP]`, stated only to enable IT-H3

`[PROP]` If `I_t` induced a target partition `π_I` and `K_t` an achieved partition `π_K`, then
"distance to the ideal" would be a lattice distance. `[CORPUS]` Q12 asks exactly *"how do we measure
distance to the ideal state?"*. **But `[CORPUS]` `07` G-12 and `05`'s Roberts analysis: no empirical
relational structure has ever been established for any epistemic quantity.** **Not proposed for
adoption; stated so IT-H3 below is falsifiable.**

---

## 3. Incompatibilities

### 3.1 No distribution ⟹ no entropy `[INF]`, decisive

`[EXT]` `H`, `I`, `D_KL` are all functionals of a probability distribution.
`[CORPUS]` `(Ω,𝓕,P)` as a triple: **0 occurrences in both lanes**; T-3 rules probability **not
required**.

$$\boxed{\text{Shannon's quantitative layer is unavailable to KnowledgeOS by the same ruling that declined probability.}}$$

`[INF]` **Only the measure-free lattice layer is available.** That is not a small residue — it
supports refinement, coverage, and gap structure — but it supports **no number**.

### 3.2 `entropy` appears 87 times in the corpus, and cannot mean Shannon entropy `[INF]`

`[CORPUS]` Measured: `entropy H(` / `entropy` = **87** corpus occurrences, **0** ratified.
`[INF]` Since no distribution exists anywhere in either lane, **every one of those 87 uses is either
(a) metaphorical, (b) about an external regime not adopted, or (c) an error.** This is a
mechanically checkable claim and I have not audited all 87 — recorded as **IT-H1** below rather than
asserted.

`[INF]` This is the same defect class as `07` MT-3 and `07` MT-4: a quantitative vocabulary used
without the structure that licenses it. **The corpus's own Shannon extraction guards against it** —
`[CORPUS]` *"information is not meaning is not knowledge."*

### 3.3 Floridi's veridicality thesis contradicts a boxed corpus result `[INF]`

`[EXT]` Floridi: semantic information is **necessarily truthful** — false "information" is not
information.
`[CORPUS]` Closure-03: **"Admission is not a truth function"**; `K` retains refuted assertions
deliberately.

`[INF]` **`𝒜` is not a set of Floridi-information.** An assertion with `Σ=(0,1) Refuted` is a
first-class member of `𝒜` and, on the veridicality thesis, carries no semantic information at all.
**Adopting Floridi would make `Refuted` inexpressible** — the same shape of error as adopting S5
in `01` §3.1.

### 3.4 Dretske requires a channel with a source distribution `[INF]`

`[EXT]` Dretske: a signal `r` carries the information that `s` is `F` iff the conditional probability
of `s` being `F`, given `r` and the receiver's prior knowledge `k`, is **1**.
`[CORPUS]` `Evidence(O,P,C,R)` is a **relevance relation under a rule**, with `[CORPUS]` TG-14
recording that `Qualify` **has no body**.

`[INF]` Dretske's definition is strictly stronger — it requires (i) a conditional probability, (ii)
its value to be exactly 1, and (iii) a background-knowledge index `k`. KnowledgeOS supplies none.
**`Evidence` is not Dretskean information-carrying**, and cannot be without reversing T-3.

---

## 4. Missing primitives

### On the information-theory side

| Missing | Needed for |
|---|---|
| a way to distinguish *not-asked* from *asked-and-empty* | `Q_t` / M1 vs M2 — `[INF]` a partition of a sample space has no "unasked" cell, exactly as in `01` §3.3 |
| tolerance of contradictory signals as a *state* | `Conflict` |
| provenance / authority | governed admission |
| a goal state | `Ideal State` |
| non-veridical carriers | `Refuted` assertions (§3.3) |

### On the KnowledgeOS side

| Missing | Note |
|---|---|
| **a source distribution** | 0 occurrences; declined by T-3 |
| **a channel** | no `P(O\|φ)` anywhere |
| **an empirical relational structure** | `[CORPUS]` G-12 — blocks *any* numeric information measure, independently of T-3 |
| **a code / compression notion** | absent, and `[INF]` not obviously needed |

---

## 5. Falsifiable hypotheses

| # | Hypothesis | Falsifier |
|---|---|---|
| **IT-H1** | All 87 corpus uses of "entropy" are metaphorical, regime-external, or erroneous — **none** computes a Shannon entropy over a declared distribution | exhibit one that declares `(Ω,𝓕,P)` and computes `H` over it |
| **IT-H2** | `Zero` is a **lattice antichain**, not a measure: no scalar summary preserves the 10-status distinction | exhibit a scalar `f(Zero)` that separates every pair of the 10 statuses under a mandated operation. *(`zero_reference.py` already exhibits a counterexample to the cardinality candidate)* |
| **IT-H3** | No "distance to the Ideal State" satisfying Q12 is definable without either a metric on `I_t`'s carrier or a distribution — neither of which exists | define one and show it meaningful under Roberts admissibility (`07` §4) |
| **IT-H4** | Adopting Floridi veridicality would make `Refuted` inexpressible | show a Floridi-consistent reading under which a refuted assertion still carries semantic information |
| **IT-H5** | `Evidence(O,P,C,R)` is strictly weaker than Dretskean information-carrying | derive Dretske's conditional-probability-1 condition from the corpus's `Qualify` — currently impossible, since `Qualify` has no body (TG-14) |

---

## 6. Verdict for this theory

$$\boxed{\textbf{THE WEAKEST FIT OF THE FIVE — the quantitative layer is unavailable and the semantic layer contradicts a boxed corpus result}}$$

- **No entropy, no mutual information, no KL** — all require the distribution T-3 declined.
- **The measure-free partition/coverage lattice does fit `Zero`**, and the corpus's boxed
  `Zero ≠ simple subtraction` is that point stated in its own vocabulary.
- **Floridi's veridicality directly contradicts** `Admission is not a truth function` — adopting it
  would delete `Refuted`.
- **Dretske needs a channel** KnowledgeOS has declined and an independence/prior structure it lacks.
- `[CORPUS]` The corpus's own extraction — *"information is not meaning is not knowledge"* — is the
  correct verdict, reached 2026-08-25. **`[INF]` What this analysis adds is that the *residue* is a
  lattice, and that `Zero` is already sitting in it.**
