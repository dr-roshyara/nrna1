# **Estimand Non-Degeneracy Preflight** (`O-F*`)

**Status:** **`[REC]` ACCEPTED — research-governance control** (adjudicated 2026-09-05). **NOT a Theory v1.3 amendment.**
**Retrospective validation: PASS.** · **`O-F*` is NECESSARY, NOT SUFFICIENT.**
**Date:** 2026-09-05 · Theory v1.2 FROZEN · kernel UNTOUCHED
**Origin:** `KR-ZOOM-OUT-03` — the primary estimand was degenerate and the existing control did not
catch it.

---

## 1. The gap

$$\boxed{\text{Class reachability} \;\not\Rightarrow\; \text{Estimand variability}}$$

`O-F` verified that every **classification class** was reachable. It did **not** verify that the
quantities used to **adjudicate** the experiment could take more than one value.

`KR-ZOOM-OUT-03` passed `O-F` and then produced `A5 = 0/783` and `0/776` — its **primary estimand,
structurally constant.** The frozen rule mechanically returned $\Delta_{\text{loss}} = 0.000$, which
**looks like a negligible effect and is not one.**

---

## 2. The rule

### 2.0 ⚠️ The NORMATIVE definition is the PIECEWISE rule

The universal form $\forall e\,\exists\theta,\theta' : e(\theta) \neq e(\theta')$ **contradicts the
legitimate constant controls** and is therefore **not** the rule. The normative definition is:

$$\boxed{O\text{-}F^{*}(e) = \begin{cases} \text{PASS}, & e \text{ declared CONSTANT and is constant} \\ \text{PASS}, & e \text{ declared VARIABLE and has a witness} \\ \textbf{FAIL}, & e \text{ declared VARIABLE and has NO witness} \end{cases}}$$

with the witness required **under the frozen computation — not merely in principle.** A witness that
requires code the experiment does not run is not a witness.

*(The universal form survives only as the intuition behind the VARIABLE branch, never as the rule.)*

### 2.1 Scope — every quantity that supports a conclusion

primary estimands · secondary estimands · classification outputs · interaction and stratification
components · **controls that are subsequently interpreted** · **any quantity appearing in an
adjudication rule.**

### 2.2 The refinement this artifact's own run forced

Running `O-F*` against `KR-ZOOM-OUT-03` immediately flagged the `O-A` controls and the null-round-trip
gain — **which are legitimately constant. Their constancy IS the check.**

$$\boxed{\text{Each quantity is DECLARED in advance as expected-VARIABLE or expected-CONSTANT.}}$$

$$O\text{-}F^{*} \text{ FAILS} \iff \text{a quantity declared VARIABLE has no witness.}$$

A quantity declared **constant** passes by being constant. `[REC]` **The declaration is part of the
pre-registration** — otherwise "it was meant to be constant" becomes available after the fact.

---

## 3. Retrospective validation against `KR-ZOOM-OUT-03`

**`KR-ZOOM-OUT-03` was NOT re-run and was NOT changed.** The preflight was evaluated on its **frozen
27-point grid**, on the **calibration seed** (never train/test), n = 400 per point — a variation
check, not an estimate.

| estimand | declared | distinct values | `O-F*` |
|---|---|---|---|
| `A1_M0` | variable | 18 | PASS |
| `A1_M1` | variable | 7 | PASS |
| `A1_MX` | variable | 18 | PASS |
| `A3_P_det` | variable | 12 | PASS |
| **`A5_loss_given_det_pre`** | variable | **1** | **FAIL** (const 0.0) |
| **`DELTA_loss`** | variable | **1** | **FAIL** (const 0.0) |
| `A4_gain` | variable | 27 | PASS |
| `S_Sprime_i_shift` | variable | 27 | PASS |
| **`S_Sprime_ii_diff`** | variable | **1** | **FAIL** (const 0.0) |
| `A4_null_gain` | **constant** | 1 | PASS (0.0) |
| `OA_structural` / `observable` / `contract_semantic` / `historical_provenance` | **constant** | 1 | PASS (1.0) |

$$\boxed{\text{O-}F^{*} \text{ VERDICT: FAIL — } \texttt{KR-ZOOM-OUT-03} \text{ would NOT have been authorized.}}$$

### 3.1 It catches **both** degeneracies execution revealed — **before** execution

| degeneracy found at execution | caught by `O-F*`? |
|---|---|
| `A5` structurally zero ⟹ $\Delta_{\text{loss}}$ uninterpretable | **YES** — 1 distinct value across 27 grid points |
| Decision 4(ii) invariant, because the classification is not a function of $S$ | **YES** — 1 distinct value |

**The control is validated on a known case, which is the only honest way to validate a new control.**

### 3.2 ⚠️ Two KINDS of failure — and they are not equally strong

| kind | detected by | strength |
|---|---|---|
| **STRUCTURAL non-functionality** — the frozen computation **cannot** differ | **inspection** of the frozen code; the sweep merely confirms it | stronger: no regime could rescue it |
| **EMPIRICAL constancy** — the computation *could* differ, but does not across the grid | **only** by the sweep | weaker: another grid or carrier might vary it |

| failure | kind |
|---|---|
| **`S_Sprime_ii_diff`** | **STRUCTURAL.** The frozen protocol specified a difference between two quantities that are **the same function** — the classification depends on `Determine(Q_focus)` with $\tau_m$ fixed at 1 and is not a function of $S$. The preflight code computes `abs(m0(inter) - m0(inter))`, **syntactically zero**. That is not an observation of invariance; it is a **structural non-functionality diagnosis**, and it faithfully reflects the protocol's own definition. |
| `A5_loss_given_det_pre` · `DELTA_loss` | **EMPIRICAL.** The computation could have varied; it returned one distinct value across all 27 grid points. |

`[REC]` **Record which kind.** A structural failure means the estimand is mis-specified; an empirical
one may mean only that the grid or carrier is wrong.

### 3.3 The three preflight questions are DISTINCT

$$\boxed{\text{Class Reachability}} \;\not\Rightarrow\; \boxed{\text{Estimand Non-Degeneracy}} \;\not\Rightarrow\; \boxed{\text{Estimand Adequacy}}$$

| | asks | control |
|---|---|---|
| **Class reachability** | can each classification class occur? | `O-F` |
| **Estimand non-degeneracy** | can each declared-variable quantity take two values? | **`O-F*`** |
| **Estimand adequacy** | does the quantity answer the question actually asked? | **none yet — open** |

`KR-ZOOM-OUT-03` passed the first and failed the second. **Nothing in the programme yet tests the
third.**

### 3.4 What it does **not** do

`[REC]` `O-F*` shows a quantity **can** vary. It says nothing about **power**, about whether the
variation is in the interesting direction, or about whether the estimand answers the question asked.
**It is a necessary condition, not a sufficient one.**

---

## 4. Where this sits

`[REC]` **Research governance, not theory.** It joins:

$$\text{Observed diagnostic} \not\Rightarrow \text{new primary estimand} \qquad \text{Observed convergence} \not\Rightarrow \text{theory promotion}$$

$$\boxed{\text{A calibration grid must contain a parameter the GATED QUANTITY is sensitive to}}$$

$$\boxed{\text{Every DECLARED-VARIABLE quantity must have a pre-execution variation witness}}$$

**Theory v1.2 unchanged · kernel untouched · no Zoom-out definition · `FR-004` still a candidate
whose prediction failed.**

---

## 5. `[REC]` The lifecycle, amended

$$\text{Hypothesis} \to \text{Estimand} \to \textbf{O-F}^{*} \to \text{Population} \to \text{Calibration} \to \text{Control} \to \text{Freeze} \to \text{Execution} \to \text{Adjudication}$$

**`O-F*` runs immediately after the estimands are written and before anything is frozen** — because
its failure is a reason to **rewrite the estimand**, and that must happen while rewriting is still
legitimate.

## 6. Artifacts

`code/preflight.py` · `preflight-KR-ZOOM-OUT-03.json`
