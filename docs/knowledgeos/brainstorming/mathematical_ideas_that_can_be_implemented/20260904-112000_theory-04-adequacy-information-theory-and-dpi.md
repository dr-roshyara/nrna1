# Theory 04 — **Adequacy, Information Theory, and the One Theorem**

**Document 04 of 15** · 2026-09-04
**Sources:** `KR-REP-REDUCTION-2026-09` + its audit

---

## 1. Definition

`[DEF]` A representation $R = T(D)$ is **adequate for $Q$** iff $Q$ is determined by $R$:

$$\hat H\!\left(Q \mid R\right) = 0$$

`[DEF]` **Operationally**, adequacy is evaluated as **fiber $Q$-homogeneity over a population**:
$R$ is adequate at $D$ iff every case sharing $D$'s representation has the same $Q$-value.

$$\mathrm{Adequate}(D) \iff \left|\{\,Q(D') : T(D') = T(D)\,\}\right| = 1$$

> ⚠️ `[DEFECT]` **Adequacy so defined is population-dependent by construction.** It is a
> property of $(T, Q, \text{population})$, not of $(T, Q)$. This was checked rather than assumed:
> informativeness was flat across $n = 1500 / 4000 / 8000$, so the dependence did not drive any
> reported result — but the dependence is real and any transfer must re-establish it.

---

## 2. The exact, bias-free companion statistic

`[DEF]` $N_{\text{viol}}$ = the number of **conflicting $Q$-pairs within a representation fiber**.

$$N_{\text{viol}} \;=\; \#\{(D_1,D_2) : T(D_1) = T(D_2),\ Q(D_1) \neq Q(D_2)\}$$

`[EXP]` **$N_{\text{viol}}$ is exact and bias-free**, where $\hat H$ is a plug-in estimate with
known small-sample bias. Where the two disagree, $N_{\text{viol}}$ is authoritative for the
question *"is adequacy exactly zero?"* — because that question is combinatorial, not estimative.

**Bias correction used where $\hat H$ is reported:** Miller–Madow,

$$\hat H_{\text{MM}} = \hat H_{\text{plug}} + \frac{K-1}{2N\ln 2}$$

---

## 3. `[THM]` The one theorem in the whole theory

> ### **Data-Processing Inequality.** For a **deterministic sequential** chain $R_2 = T_2(R_3)$, $R_3 = T_3(R_4)$, $R_4 = T_4(R_5)$:
> $$H(Q \mid R_5) \;\le\; H(Q \mid R_4) \;\le\; H(Q \mid R_3) \;\le\; H(Q \mid R_2)$$

**Proof: standard, cited, not re-derived here.** This is the *only* `[THM]` in the theory, and
it is not ours. Stating that plainly is the point of the status vocabulary.

### `[COR]` Adequacy cannot be non-monotone along a deterministic sequential chain

Information relevant to $Q$ **cannot spontaneously reappear** after being destroyed. Therefore
a hypothesis of the form *"adequacy is lost at step $k$ and regained at step $k+j$"* is
**structurally inapplicable** to a sequential chain.

> ⚠️ **The correction that matters.** This was first recorded as *"refuted a priori."* That was
> wrong, and the distinction is not pedantry:
>
> $$\text{a priori impossible} \;\neq\; \text{empirically falsified}$$
>
> Hypothesis `H-C` was **unfalsifiable in the negative direction from the start**. It was not
> defeated by data; it was ill-posed against a sequential design. Recording it as "refuted"
> would have credited the experiment with a result it could not have produced.

`[EXP]` The empirical check was still run and is still worth having: it confirms the
**implementation obeys the DPI**, which is a correctness check on the code, not a test of the
hypothesis.

### The scope condition, which is doing real work

The DPI applies to **sequential** families. It does **not** apply to a **parallel** family where
every $R_k = T_k(D)$ is computed from $D$ directly. In a parallel family non-monotone adequacy
is perfectly possible.

`[EXP]` **Sequential vs parallel is therefore a permanent distinction in the theory**, not a
detail of one design. KR-BRIDGE-01/02/03 deliberately used a **parallel** family for exactly
this reason.

---

## 4. Reduction is genuinely multi-dimensional

`[EXP]` Measured across the reduction chain:

| level | bytes | fields | **distinct values** | $\hat H(R)$ |
|---|---|---|---|---|
| `R5` | 37.0 | 2 | **26 826** | 15.042 |
| `R4` | 37.0 | 2 | **5 696** | 12.307 |

> ### `[EXP]` **Bytes are FLAT across `R5→R3` while cardinality falls 8×. Fields never change.**

`[NEG]` **Reduction dimensions cannot be treated as independent, and cannot be collapsed into a
single "size" scalar.** `R5 = R4 = R3` on bytes while cardinality fell eightfold. A design that
measures "how much was reduced" with one number is measuring nothing.

`[EXP]` $\hat H(R) \ge \hat H(Q)$ at **every** level — $\hat H(Q) = 5.0098$ bits over 39
distinct answers, against 15.04 bits at `R5`. Representation entropy never dropped below inquiry
entropy in the tested chain, so the chain never approached the information-theoretic floor.

---

## 5. Estimator hygiene — a defect, contained

`[DEFECT]` **The bootstrap confidence interval on $\hat H$ is invalid in this design.**
Resampling with replacement duplicates cases, which shrinks fiber diversity and **depresses
conditional entropy downward**. The reported bootstrap CIs are therefore biased toward
"adequate."

**Containment:** every adequacy *verdict* in the programme rests on $N_{\text{viol}}$, which is
exact, and on the train/test split — not on the bootstrap CI. The defect is recorded, its blast
radius is stated, and no conclusion depends on it.

`[REC]` A valid interval here requires resampling at the level of the **fiber structure**, not
the case list. Not attempted; `[OPEN]`.

---

## 6. Register

| Statement | Status |
|---|---|
| $\mathrm{Adequate} \iff \hat H(Q\mid R) = 0$ | `[DEF]` |
| Fiber $Q$-homogeneity operationalizes it | `[DEF]` |
| Adequacy so defined is population-dependent | `[DEFECT]`, contained |
| $N_{\text{viol}}$ is exact and bias-free | `[EXP]` |
| $H(Q\mid R_5)\le H(Q\mid R_4)\le H(Q\mid R_3)\le H(Q\mid R_2)$ for deterministic sequential chains | **`[THM]`** — DPI, cited |
| Adequacy cannot be non-monotone along such a chain | **`[COR]`** |
| "adequacy lost then regained" was empirically refuted | **`[NEG]`** — it was *structurally inapplicable*, which is a different thing |
| The DPI applies to parallel families | **`[NEG]`** — it does not |
| Reduction dimensions are independent / collapsible to one scalar | **`[NEG]`** |
| $\hat H(R) \ge \hat H(Q)$ at every tested level | `[EXP]` |
| Bootstrap CI on $\hat H$ is valid here | **`[NEG]`** — `[DEFECT]`, contained |
