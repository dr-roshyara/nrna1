# Theory 11 — **Statistical Inference Standards**

**Document 11 of 15** · 2026-09-04
*Written in the statistician's role. These are the standards the programme's claims are held to.*

---

## 1. The estimand must be named before it is estimated

The programme's central error was estimating the **wrong quantity** competently.

| | quantity | what it answers |
|---|---|---|
| marginal | $P(A \mid Z) - P(A \mid \neg Z)$ | is `Zero` *associated* with adequacy? |
| **within-stratum** | $\sum_k w_k\!\left[P(A \mid Z, k) - P(A \mid \neg Z, k)\right]$ | is it associated **given the confounder**? |

`[REC]` **The estimand for any bridge claim is the within-stratum effect.** The marginal was
already known to be confounded before `KR-BRIDGE-02` ran; re-estimating it produced a false
"H2 SUPPORTED."

---

## 2. Stratification

**Estimator: Mantel–Haenszel.**

$$
\widehat{OR}_{MH} = \frac{\sum_k a_k d_k / n_k}{\sum_k b_k c_k / n_k}
\qquad
\widehat{RD}_{MH} = \frac{\sum_k w_k \left(\frac{a_k}{a_k+b_k} - \frac{c_k}{c_k+d_k}\right)}{\sum_k w_k},
\quad w_k = \frac{(a_k+b_k)(c_k+d_k)}{n_k}
$$

**Declared before inspection:** the stratification variable, and $n_k \ge 200$.

### `[DEF]` A stratum is **informative** iff

$$(a_k + b_k) > 0 \;\wedge\; (c_k + d_k) > 0 \;\wedge\; (a_k + c_k) > 0 \;\wedge\; (b_k + d_k) > 0$$

— both `Zero` and non-`Zero` occur, **and** both Adequate and Inadequate occur.

> `[EXP]` **Strata at deterministic extremes contribute nothing.** This is why flattening the
> redundancy distribution failed (document 07 §4): it moved mass into strata that cannot be
> informative by construction. **Balance is not power.**

### `[REC]` Choose the stratification variable for **resolution**, not convenience

Redundancy *count* is coarse. The multiplicity **partition** is finer ($r = 2$ is either $2{+}2$
or $3{+}1$), and $(\text{partition}, |S|)$ finer still. Finer strata gave **13× the informative
strata** and better confounder control at once.

---

## 3. Replication

`[REC]` A finding replicates only if, on an independently seeded held-out split:

1. the sign agrees, **and**
2. the magnitude clears the substantive floor on **both** splits, **and**
3. at least one stratum's 95 % CI excludes zero.

> `[NEG]` **Sign agreement alone is not replication.** Under a sign-only rule, 14 cells
> "replicated" whose effects were $|RD| \approx 0.001$ and of which **12 had no stratum whose CI
> excluded zero.** All 14 collapsed at higher power.

**Per-stratum CI** (the diagnostic that exposed them):

$$
\widehat{SE}(RD_k) = \sqrt{\frac{p_{1k}(1-p_{1k})}{n_{1k}} + \frac{p_{2k}(1-p_{2k})}{n_{2k}}}
$$

`[REC]` **Report per-stratum RDs, not only the pooled MH estimate.** A pooled estimate whose
per-stratum signs disagree is not an effect; MH assumes homogeneity, and the assumption must be
shown, not invoked.

---

## 4. Effect-size floors

`[REC]` Declare a substantive floor. At $n \sim 10^5$, statistical significance is not evidence
of anything.

`[REC]` **If the floor is declared after first inspection, record that fact in the artifact**,
and use the floor as a **reporting tier** rather than a filter: publish every surviving cell at
both tiers so the reader can apply their own threshold.

---

## 5. Estimator hygiene

| Statistic | Standing |
|---|---|
| $N_{\text{viol}}$ — conflicting $Q$-pairs in a fiber | **exact, bias-free.** Authoritative for "is adequacy exactly zero?" |
| $\hat H$ plug-in | biased downward in small samples |
| Miller–Madow $\hat H_{\text{MM}} = \hat H + \frac{K-1}{2N\ln 2}$ | the correction used |
| Odds ratio with Haldane $+0.5$; Woolf CI on $\log OR$ | for sparse $2\times2$ tables |
| **Bootstrap CI on $\hat H$** | **`[DEFECT]` invalid here** — resampling duplicates cases, shrinks fiber diversity, depresses conditional entropy. No verdict rests on it. |

> `[REC]` **Prefer the exact combinatorial statistic to the estimated information-theoretic one
> whenever the question is combinatorial.** "Is the fiber homogeneous?" is a counting question;
> answer it by counting.

---

## 6. Confounding, and the standard that came out of it

`[EXP]` The programme's one substantive causal finding is a **confounding** finding:

> **Within the tested deduplication regime, redundancy behaves as a common cause of `Zero`
> occurrence and of inadequacy.**

Scope-bound deliberately: one informative stratum at the time, one carrier, one $\Pi$, one $Q$,
one principal Zero-producing transformation.

`[REC]` **Never state a regime-specific empirical finding as a general causal law.** The
tightening of this exact sentence is why this standard exists.

`[NEG]` And a single-mechanism explanation of it was **withdrawn**: the Zero-count is constant
at $r = 1$ but varies at $r = 2$. **The exact $RD = 0$ had two different causes.**

---

## 7. Power

`[REC]` **Power is a property of the informative region, not of $n$.**

| lever | effect on informative strata |
|---|---|
| population size $n$ | **none** (73 / 74 / 70 across $1500 / 4000 / 8000$) |
| flattening the confounder's distribution | **none** ($73 \to 75$) |
| **record count** ($4 \to 6$) | $73 \to 233$ |
| **finer stratification variable** | $\sim 30 \to 388$ |

`[EXP]` **Two of four plausible levers do nothing.** Both were measured rather than assumed —
including population size, which had to be checked because adequacy is population-dependent by
construction.

`[REC]` When power stops responding to sampling, **the constraint has moved into the design.**
The 67 dead cells were invariant across nine generator regimes; their cause is the adequacy
*definition* saturating, and no amount of sampling can reach them.
