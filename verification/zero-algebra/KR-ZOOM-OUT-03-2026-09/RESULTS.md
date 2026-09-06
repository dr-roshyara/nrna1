# `KR-ZOOM-OUT-03` — RESULTS

Executed 2026-09-05 against the frozen pre-registration, after **GO**. **Nothing was modified in
response to calibration or to outcomes.** n = 1500 × 2 independent splits · budget 5 ·
params `n_dims=4, subjects=(1,2), value_alphabet=6` (calibration-selected, frozen).

---

## 1. Verdict as frozen — and why it must not be read as evidence of absence

$$\Delta_{\text{loss}} \text{ (stratified, MH)} \;=\; \mathbf{0.000} \;/\; \mathbf{0.000} \qquad \text{floor } 0.10$$

> ### **PRIMARY VERDICT (ADJUDICATED 2026-09-05):**
> ### `[NEG]` **THE PRIMARY ESTIMAND WAS NON-IDENTIFIABLE / DEGENERATE IN THE EXECUTED REGIME AND CANNOT SUPPORT THE INTENDED PRESERVATION-OF-ANSWERABILITY CLAIM.**
>
> ⚠️ **This supersedes "negligible effect."** The frozen rule mechanically returns 0.000, but the
> estimand has **no variation**, so "negligible" would misdescribe it as a measured near-zero effect.

> ### ⚠️ **BUT THE PRIMARY ESTIMAND WAS DEGENERATE.** $A_5 = 0/783$ and $0/776$ — **exactly zero on both splits.** $\mathrm{Avail}(Q_{\text{broad}})$ was **never** lost. $\Delta_{\text{loss}}$ is a difference of two loss rates that are both structurally 0, so **0.000 is forced, not observed.**
>
> **This is NOT evidence that zoom-out preserves answerability.** It is evidence that **`A5` as
> frozen cannot vary at the calibration-selected parameter point.**

**Mechanism.** $\mathrm{Avail}(Q_{\text{broad}}, K) \iff \left|\{(\text{dim},\text{value})\}\right| \ge 2$
— the contract is *applicable*. At `n_dims=4, subjects=(1,2)` a state retains several distinct
`(dim, value)` pairs after zoom-out, so applicability never fails. **`A5` and the classification's
`M0` are different notions of loss**: `M0` = some `(dim, subj)` observable became unavailable;
`A5` = the whole `Q_broad` inquiry became inapplicable. The second is far stronger and far rarer.

`[REC]` **No metric was changed and no re-run was performed.** Reported and carried forward.

---

## 2. ⚠️ DEGENERATE METRICS *(mandatory section)*

| metric | value | mechanism that forces it |
|---|---|---|
| **`A5`** | **0.000 / 0.000** | `Avail(Q_broad)` requires only ≥2 distinct `(dim,value)` pairs; the chosen point always retains them. **The primary estimand could not vary.** |
| **$\Delta_{\text{loss}}$** | **0.000 / 0.000** | a difference of two rates that are both structurally 0 |
| $\Delta_{\text{loss}}$ strata | 16 / 19, **all sparse** | small `P_det ∩ determined` cells at this point |
| `S`/`S'` **component (ii)** | structurally 0 | **the classification depends on `Determine(Q_focus)`, which uses $\tau_m$ fixed at 1 — it is NOT a function of `S`.** The frozen difference $\lvert P(M_0\mid\cap,S)-P(M_0\mid\cap,S')\rvert$ **cannot vary.** |
| `O-A` all four equalities | 1.000 | the null round-trip returns $K_0$ identically — the control working, and `[DEF]` |
| `A4` null-round-trip gain | 0.000 | no determination ⟹ no change ⟹ no gain |

### `[NEG]` **THE PRINCIPAL RESULT OF THIS EXPERIMENT** — `O-F` is insufficient

`O-F` verified that all six **classification classes** were reachable. **It did not verify that the
PRIMARY ESTIMAND could vary**, and `A5` could not.

$$\boxed{\text{The degeneracy pre-check must cover EVERY reported estimand, not only the classification classes.}}$$

**Third distinct degeneracy in the programme, and the first the existing control was not designed
to catch.** The repair — `O-F*`, the **Estimand Non-Degeneracy Preflight** — is specified and
**retrospectively validated against this experiment** in
`../METHODOLOGY-2026-09/ESTIMAND-NON-DEGENERACY-PREFLIGHT-2026-09.md`.
**`O-F*` FAILS on `KR-ZOOM-OUT-03`: this experiment would not have been authorized.**

---

## 3. What the run DID establish

### 3.1 ✅ The `stratify-don't-filter` correction WORKED

| | `KR-ZOOM-OUT-01` | **`KR-ZOOM-OUT-03`** |
|---|---|---|
| `M1` (unconditional) | **0.634** — the "winner", an artifact | — |
| **`M1` within $\mathcal P_{\text{det}}$** | 0.002 (diagnostic only) | **0.000 / 0.000** |
| `O-G` stratum artifacts detected | — | **0** |

> `[EXP]` **The earlier `M1` dominance was a POPULATION-COMPOSITION phenomenon that disappears
> under the declared stratify-don't-filter analysis.**
>
> ⚠️ The conclusion is **not** that Zoom-out changed from `M1` to non-`M1`. It is a **methodological
> replication**: the artifact was in the population handling, not in the phenomenon.

### 3.2 `[EXP]` `M0` replicates on fresh data under a **different generator parameterisation**

| class within $\mathcal P_{\text{det}}$ | train | test |
|---|---|---|
| **`M0`** | **0.497** | **0.520** |
| `M3b` revision | 0.243 | 0.261 |
| **`MX` none fits** | **0.219** | **0.195** |
| `M2` | 0.033 | 0.022 |
| `M3a` | 0.009 | 0.002 |
| `M1` | **0.000** | **0.000** |

`KR-ZOOM-OUT-01`'s conditional `M0` was 0.495 / 0.448. **Here 0.497 / 0.520 — on different seeds
and a different parameter point.**

⚠️ **`A1` is a DESCRIPTIVE classification, not an adjudicated effect** (role table). It **may not**
be stated as *"zoom-out destroys answerability"*. What replicated is a **phenotype distribution**.

### 3.3 No single winner — as expected and as declared

`M0` at 0.497 / 0.520 does not reach **0.60** on both splits.

> `[EXP]` **No single meaning describes Zoom-out in the tested regime.** Declared in advance as the
> probable and legitimate outcome. **`MX` at ~0.21 / 0.19 is a substantial share in its own right.**

### 3.4 The declared `FR-004` prediction is **NOT met**

$$\text{(i) total shift} = 0.0311 \,/\, 0.0562 \qquad \text{predicted } \ge 0.10$$

`[NEG]` **The pre-registered contract-sensitivity prediction fails.** Declared before execution, and
**not** reinterpreted after. It supplies **no seventh instance** for `FR-004`.

Component **(ii)**: intersection $n = 95 / 94$ (as the §7.1 limitation predicted), `M0` = 0.232 /
0.277. **Underpowered as declared — and additionally degenerate as a difference (§2).**

---

## 4. Controls

| | train | test |
|---|---|---|
| `O-A` Structural · Observable · Contract-semantic · Historical/provenance | 1.000 ×4 ⚠️`[DEF]` | 1.000 ×4 |
| `O-B` determined nothing | 0.613 | 0.607 |
| `O-C` decoy share of determined | 0.286 | 0.299 |
| **`O-G` stratum artifacts** | **0** | **0** |
| `O-F` classes reachable | pass | pass ⚠️ but see §2 |

---

## 5. Register

| statement | status |
|---|---|
| $\Delta_{\text{loss}}$ — loss depends on what was determined | **`[NEG]` NON-IDENTIFIABLE / DEGENERATE** — cannot support the intended claim. **Not "negligible".** |
| Decision 4(ii) tested the `S`/`S'` phenotype contrast | **`[NEG]` STRUCTURALLY INVARIANT** — the classification depends on `Determine(Q_focus)` with $\tau_m$ fixed at 1 and is **not a function of `S`**. A difference of 0 would **not** mean "no standard effect"; the phenotype lacks the required dependence on the manipulated dimension. |
| The `M1` change means Zoom-out changed | **`[NEG]`** — **`[EXP]`** the earlier `M1` dominance was a **population-composition phenomenon** that disappears under stratify-don't-filter |
| `A5` can vary at the frozen parameter point | **`[NEG]`** — 0.000 exactly, both splits |
| The `M1` artifact survives stratification | **`[NEG]`** — eliminated, 0.000 |
| `M0` is the modal phenotype within $\mathcal P_{\text{det}}$ | **`[EXP]`** — 0.497/0.520, replicating across generators; **descriptive only** |
| A single meaning describes Zoom-out | **`[NEG]`** at the 0.60 rule |
| `FR-004` gains a seventh instance | **`[NEG]`** — prediction (i) not met |
| `O-F` as specified is sufficient | **`[NEG]`** — it checks classes, not estimands |
| Zoom-out definition | **UNCHANGED — none is added** |

## 6. Governance

**Theory v1.2 FROZEN · kernel UNTOUCHED · Zoom-out UNDEFINED · `FR-004` CANDIDATE, no new instance.**
**`GO` meant only that the experiment passed its pre-registered conditions for execution. It did
not mean the hypothesis was supported, and it was not.**
