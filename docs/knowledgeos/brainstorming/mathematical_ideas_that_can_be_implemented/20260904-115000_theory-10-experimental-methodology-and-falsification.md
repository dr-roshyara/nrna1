# Theory 10 — **Experimental Methodology and Falsification Engineering**

**Document 10 of 15** · 2026-09-04

> A negative result is worth exactly what its methodology is worth. This document is the
> methodology — assembled from what actually caught errors, not from what sounded rigorous.
> **Every rule below exists because a specific failure produced it.**

---

## 1. Pre-registration, and what a label is

`[REC]` **Declare hypotheses, transformations, expected behaviours, seeds and analysis before
execution.** No post-hoc filtering.

> ### Amendment A1 — **labels are pre-registered classifications, not established facts.**
>
> A transformation labelled "lossy" is *claimed* to be lossy. After execution the label is
> **verified against observed behaviour**, and a mismatch is a recorded **finding** —
> the transformation is **never silently redefined to match its label.**

**Origin:** `KR-BRIDGE-01`'s `T_B` label mismatch. The temptation was to adjust the label. The
rule forbids it.

> ### Amendment A2 — **do not tune the generator to balance cells. Natural imbalance is evidence.**

Cells are **reported with their structural cause**, never engineered. **Origin:** the four-way
table was badly imbalanced; balancing it would have destroyed the information that *the
imbalance itself* was the finding.

---

## 2. Controls that must exist, and what each catches

| Control | Requirement | Catches |
|---|---|---|
| **A** preserving | an information-preserving $T$ must be adequate | a broken adequacy test |
| **B** destroying | a constant map must be inadequate | a test that always says "adequate" |
| **C** invertible recoding | $v \mapsto v + 100$ must give **exactly** the same adequacy | accidental value-dependence |
| **D** four-way non-degeneracy | all four cells of the $2\times2$ non-empty | a design that cannot see the relationship |
| **E** separation | the test must separate A from B by a wide margin | an insensitive instrument |
| **F** Zero fires selectively | `Zero` must fire for some $T$ and not others | a vacuous or saturating predicate |
| **G** the gate bites | the admissibility gate must block something | a gate that is decorative |
| **H** $\Pi$ is not inert | different $\Pi$ must produce different `Zero` behaviour | a factor that is not a factor |
| **I** $Q$ is not inert | different $Q$ must produce different adequacy | same |
| **A1** provenance | the provenance map must be **verified by perturbation** | a hand-written map that is wrong |

`[EXP]` **Control A1 is the one that earned its place this cycle.** It failed twice on maps I
had written by reading the code. Code review caught neither failure.

---

## 3. The four rules that came from actual errors

### 3.1 `[REC]` Adjudicate the **within-stratum** effect, never the marginal

**Origin:** the `KR-BRIDGE-02` runner reported *"H2 SUPPORTED, 27/27 replicate"* on the marginal
RD — which was re-finding a confounder that `KR-BRIDGE-01` had already identified.

$$\text{marginal } RD \ne 0 \;\;\not\Rightarrow\;\; \text{relationship}$$

Use Mantel–Haenszel across declared strata. Declare the stratification variable **and the
minimum stratum size** before inspection.

### 3.2 `[REC]` Admissibility is a property of the **triple**, verified empirically

Document 07 §3. Two hand-written provenance maps were wrong. **Declaration is not verification.**

### 3.3 `[REC]` Replication must constrain **magnitude**, not only sign

**Origin:** a sign-only rule certified 14 cells whose effects were $|RD| \approx 0.001$ and whose
CIs all covered zero. Require the effect to clear the substantive floor on **both** splits.

$$\text{same sign} \;\;\not\Rightarrow\;\; \text{replicated}$$

### 3.4 `[REC]` Distinguish *structurally inapplicable* from *empirically refuted*

**Origin:** `H-C`. A hypothesis that the design's mathematics forbids was recorded as "refuted."
It was **ill-posed**, and crediting the experiment with refuting it would have been a false
result in the negative direction.

---

## 4. Effect-size floors, and declaring them honestly

`[REC]` At $n \sim 10^5$ observations, an $|RD|$ of $0.001$ is **detectable and meaningless.**
A substantive floor is required.

> **When a floor is declared *after* first inspection, say so in the artifact.** In
> `KR-BRIDGE-02` the 0.01 floor was declared post-inspection and this was **recorded rather
> than concealed**, with the floor used as a **reporting tier**, not a filter — every surviving
> cell was listed at both tiers.

---

## 5. Design distinctions that are permanent, not incidental

| Distinction | Why it is permanent |
|---|---|
| **sequential** vs **parallel** transformation families | the DPI applies to one and not the other (document 04 §3) |
| **typed** vs **untyped** elimination | $D \setminus S$ is invalid wherever a field is relative (document 01 §2) |
| $\Pi$ reads $T(D)$; $Q$ reads $D$ | the anti-circularity requirement lives here |
| **adequacy** (population) vs **realization** (case) | different index sets, different quantifiers |

---

## 6. The provenance check, generalized

> **Every structural finding must be tested against the provenance of the contracts,
> transformations, generators and assumptions that make the phenomenon observable.**

`[EXP]` **The check discriminates; it does not merely dissolve.** The same provenance check
that **dissolved** the `KR-ZERO-GROUP` "case J" finding (revealing it as a property of
*cancelling contracts*, not of `Zero`) **confirmed and strengthened** the interaction-order
finding, which came out *higher* without the cancelling contract.

**That contrast is the evidence that the check is a real instrument** rather than a way of
disbelieving inconvenient results.

---

## 7. Identity and reproducibility hygiene

| Rule | Origin |
|---|---|
| One canonical experiment ID; collisions **eliminated**, not documented; retired IDs survive as **legacy aliases** | `KR-CONTR-2026-09` was issued twice, both legitimately |
| Train/test splits with independent, recorded seeds | throughout |
| Full population persisted, not just summaries | enables re-analysis after a defect is found — which happened repeatedly |
| Falsification tests (`FT-1..FT-n`) run **before** any result | `KR-REP-REDUCTION` |
| Positive control proving the experiment was not blind | `KR-REP-REDUCTION`: $N_{\text{viol}} > 0$ at three levels |

---

## 7a. The experimental protocol — **frozen 2026-09-05**

$$\boxed{\;\text{Hypothesis} \to \text{Estimand} \to \mathbf{O\text{-}F^{*}} \to \text{Population} \to \text{Calibration} \to \text{Control} \to \text{Freeze} \to \text{Execution} \to \text{Adjudication}\;}$$

**`O-F*` — Estimand Non-Degeneracy Preflight** (`verification/zero-algebra/METHODOLOGY-2026-09/`):
every quantity **declared variable** must have a pre-execution **variation witness** under the
frozen computation. **Class reachability ⇏ estimand variability.** It runs *before* the freeze,
because its failure is a reason to **rewrite the estimand** — and that must happen while
rewriting is still legitimate.

*(Short form: Design → Pre-register → Calibration → Freeze → Analysis.)*

> ### `[REC]` **No analysis-dependent choice of estimand or population. Ever.**

**Calibration is a SEPARATE PASS with its own gate**, inspected **before** the analysis code is run
at all. Its output may change the generator; it may **not** be seen alongside outcomes.

### The two safeguards

$$\boxed{\;\text{Observed diagnostic} \;\not\Rightarrow\; \text{new primary estimand}\;}$$
$$\boxed{\;\text{Observed convergence} \;\not\Rightarrow\; \text{theory promotion}\;}$$

> `[REC]` **These are RESEARCH-GOVERNANCE SAFEGUARDS, not KnowledgeOS theory claims.** They are
> stated here rather than in the theory register precisely because they govern how the register may
> be added to. **They matter more, not less, as the experiments get sophisticated enough to generate
> convincing-looking artifacts.**

**Origin — two failures, each of which cost a result:**

| experiment | what happened |
|---|---|
| `KR-ZOOM-03` | the **directed** diagnostic (+0.123/+0.134, above floor) appeared only *after* the undirected estimand had returned BORDERLINE. Could not be adopted. |
| `KR-ZOOM-OUT-01` | the **conditional population** (`E5` = 0.182/0.185, above floor; `M1` collapsing 0.634 → 0.002) appeared only *after* the unconditional result. Could not be adopted. **And the calibration gate failure (0.294 vs 0.30) was invisible until every outcome had been seen** — because calibration and analysis ran in one pass. |

`[REC]` **Stratify; do not conditionally filter.** A population filter can hide the stratum that
explains the result. In `KR-ZOOM-OUT-01` the *null* stratum (63 % no-determination) silently became
the "winner" `M1`. **Had the stratum sizes been a reported quantity, the artifact would have been
visible immediately rather than at audit.**

---

## 8. The governance order, which is part of the method

$$\text{EXPERIMENT} \to \text{AUDIT} \to \text{ADJUDICATION} \to \text{THEORY v1.3}$$

**No shortcuts.** An experiment does not adjudicate itself. **Engineering supplies evidence and
never accepts its own work** — evidence, recommendation and authority stay separate.
