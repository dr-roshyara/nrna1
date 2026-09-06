# Selection & Population Audit — **before** `KR-ZOOM-OUT-02` is pre-registered

**Date:** 2026-09-05 · **Status:** `[EXP]` methodological audit · **NOT a re-analysis of `KR-ZOOM-OUT-01`**
**Commissioned by:** the research owner — *"audit the causal/population logic before ratifying the new estimand."*

> ### ⚠️ **No verdict of `KR-ZOOM-OUT-01` is revised by this document.** Its result stands: `E5` BORDERLINE, `M1` withdrawn, calibration gate failed. The output here is a **recommendation about which population `KR-ZOOM-OUT-02` should pre-register.**

---

## 1. The question

Does conditioning on *"a determination was reached"* introduce **selection bias**?

$$K_t \xrightarrow{\text{investigate}} E \xrightarrow{\text{Determine}} D \xrightarrow{\text{zoom\_out}(K_t, D)} K_{t+1} \longrightarrow \{\text{class},\ E_5\}$$

## 2. The structural answer — `D` is an **ancestor**, not a collider

`zoom_out` **consumes** $D$. So $D$ lies on the causal path *into* the outcome:

$$D \longrightarrow K_{t+1} \longrightarrow \text{outcome}$$

Conditioning on $D$ is therefore **conditioning on the exposure**, not collider-conditioning on a
descendant of the outcome. **The classic selection-bias objection does not apply in its strong
form.**

**But a backdoor remains possible:** graph structure could cause **both** $D$ **and**, via the root
dimension, the detail claims that drive `M0`.

$$\text{structure} \to D \qquad\text{and}\qquad \text{structure} \to \text{root} \to \text{detail claims} \to M0$$

**That is testable, so it was tested rather than argued.**

---

## 3. Measurement

### 3.1 The backdoor's first leg is **essentially absent**

| | determined | not determined |
|---|---|---|
| mean evidence-graph size (train) | **10.755** | **11.052** |
| mean evidence-graph size (test) | **10.931** | **10.961** |

$D$ is barely associated with graph size. **The suspected common cause is weak.**

### 3.2 Stratifying on structure does **not** move the estimate

| population | n | crude `M0` | **stratified `M0`** | crude `M1` |
|---|---|---|---|---|
| `P_all` | 1500 | 0.181 / 0.165 | 0.181 / 0.165 | 0.634 / 0.635 |
| **`P_det`** | 550 / 552 | **0.495 / 0.448** | **0.498 / 0.448** | 0.002 / 0.007 |
| `P_reach` | 1500 | 0.181 / 0.165 | 0.181 / 0.165 | 0.634 / 0.635 |

> ### `[EXP]` **The `M0` inversion survives structural stratification unchanged** (0.4945 → 0.4977; 0.4475 → 0.4476). **It is not a structural-selection artifact.**

`M0` is stable across strata (0.44–0.56 at every graph size), so no single stratum is driving it.

### 3.3 ⚠️ `P_reach` is **degenerate** — and that is itself a finding

$$P_{\text{reach}} = P_{\text{all}} \quad\text{exactly: every case's root is reachable within budget.}$$

The "pre-investigation" population I had hoped would sidestep the conditioning problem
**selects nothing.** And:

$$P(\text{determined} \mid \text{reachable}) = 0.367 \,/\, 0.368$$

> ### `[EXP]` **Determination fails at the CONTRACT level, not the reachability level.** 63 % of cases are fully reachable and still not determined — the Option 3 uniqueness and margin conditions are what bind, not the search.

---

## 4. The remaining, genuine caveat

`[REC]` **`P_det` is not a property of the domain. It is a property of (domain × contract).**

Change $\tau_s$, $\tau_m$, or the uniqueness requirement and the population changes. Any estimand
defined on `P_det` is therefore **contract-relative**, and must be reported as such — never as
*"what zoom-out does"* simpliciter.

**The audit tested one structural proxy (evidence-graph size). Root depth, detail count and
coverage were not stratified.** That is a stated limitation, not a clean bill of health.

---

## 5. Recommendation for `KR-ZOOM-OUT-02`

### `[REC]` **Do not FILTER. STRATIFY, and report both strata.**

$$\text{determination reached} \;\in\; \{\text{yes},\ \text{no}\} \quad\text{— a declared STRATIFICATION VARIABLE, not a population filter}$$

This dissolves the entire question:

| | filtering (`P_det`) | **stratifying (recommended)** |
|---|---|---|
| conditions on an outcome-adjacent variable | yes | **no — both strata are reported** |
| can hide the null stratum | yes — and it did the opposite here, hiding the *substantive* one | **no** |
| permits a post-hoc population switch | yes | **no — both are pre-registered** |
| `M1`-as-artifact detectable | only by accident | **structurally: the null stratum is reported separately** |

**Concretely, `KR-ZOOM-OUT-02` should pre-register:**

1. **The primary estimand inside the `determined = yes` stratum**, declared in advance.
2. **The `determined = no` stratum reported in full**, never dropped.
3. **The stratum sizes themselves as a reported quantity** — had that been done in `KR-ZOOM-OUT-01`,
   the `M1` artifact would have been visible immediately rather than at audit.
4. **Every estimand labelled contract-relative**, per §4.
5. **Calibration as a SEPARATE pre-analysis pass**, whose output is inspected and gated **before**
   the analysis code is run at all. *(`KR-ZOOM-OUT-01`'s gate failure was invisible until after every
   outcome had been seen — a process defect, not a design defect.)*

### `[NEG]` What is **not** recommended

- **Not** re-running with the conditional numbers as the target. The conditional inversion is a
  diagnostic; adopting it as the estimand because it cleared the floor is exactly the move the
  pre-registration exists to prevent.
- **Not** `P_reach` — degenerate in this domain.
- **Not** treating `M0 ≈ 0.47` as a finding about zoom-out. It is a finding about **this zoom-out
  implementation, under this contract, in the determined stratum**, from a run whose **calibration
  gate failed.**

---

## 6. Status

**Theory v1.2 FROZEN · kernel NOT SELECTED · no definition of Zoom-out added · `KR-ZOOM-OUT-01`'s
verdict unchanged · `KR-ZOOM-OUT-02` NOT pre-registered and NOT authorized.**
