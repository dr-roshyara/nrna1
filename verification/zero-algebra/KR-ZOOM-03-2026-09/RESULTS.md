# `KR-ZOOM-03` — RESULTS

**Contract, floor and estimand frozen BEFORE execution** (`../KR-ZOOM-03-PREREGISTRATION-2026-09.md`).
2 000 cases × 2 independent splits · paired seeds · binding budget 5 · calibration gate **passed**.

---

## 1. Adjudicated verdict — on the frozen estimand

$$\Delta = P(\text{ZERO-FLIP} \mid \text{root-cause dim}) - P(\text{ZERO-FLIP} \mid \text{decoy dim})$$

| | train | test |
|---|---|---|
| base `Determine` rate (Option 3) | **0.3395** | **0.3585** | calibration gate 0.30–0.80 ✅ |
| flip · root-cause | 0.3245 | 0.3395 |
| flip · decoy | 0.2675 | 0.2803 |
| **Δ stratified (MH)** | **0.0577** | **0.0597** |

> ### `[EXP]` **VERDICT: BORDERLINE — reported, NOT claimed.**
> Δ = 0.058 / 0.060 sits in the frozen borderline band (0.05–0.10) and **below the 0.10 material
> floor**. Per the pre-registration, **H3a is not supported.**

**The floor was frozen before execution and is not moved.**

---

## 2. Why it came out borderline — control `Z-D` failed

| control | expectation | result |
|---|---|---|
| **Z-F** null intervention | flip = 0 | **0/2000** ✅ ⚠️ definitional |
| **Z-A** untouched dimensions | flip ≈ 0 | **0/6000** ✅ ⚠️ definitional |
| **Z-D** decoys flip materially below root | large gap | **FAILED — 0.267 vs 0.324** |
| **Z-E** paired seeds | identical branches | ✅ enforced in `zero()` |
| **Z-G** binding budget | no saturation | ✅ base rate 0.34 |
| **H3c** attribution | separates focus- from evidence-induced | ⚠️ **DEGENERATE — see §3** |

> ### `[EXP]` **Removing a deliberately irrelevant dimension changed determination almost as often as removing the true cause.**
> The instrument is largely measuring **perturbation**, not **relevance**. That is the finding,
> and it is a finding about the instrument.

---

## 3. ⚠️ DEGENERATE METRICS *(mandatory section — `EPISTEMIC-STATUS-VOCABULARY.md` §3a)*

| metric | value | mechanism that forces it |
|---|---|---|
| **`H3c` focus-induced** | **0.3395 / 0.3585 — exactly the base `Determine` rate** | narrowing the view to the anchor dimension destroys determination in precisely the cases where determination existed, because determination always involves dimensions beyond the anchor. **`H3c` equals `base_determine` by construction and separated nothing.** The load-bearing attribution control **did not work.** |
| `flip_untouched = 0/6000` | 0.000 | removing a dimension absent from the case cannot change any link |
| `Z_F` null intervention `= 0/2000` | 0.000 | eliminating a non-existent dimension is the identity. **This is the point of the control, and it is `[DEF]`** |
| `base_determine` under Option 1 | **1.0000** | see §4 |

---

## 4. `P-Z3` — my pre-registered prediction: **CONFIRMED**

> Predicted before execution: *under Option 1, base `Determine` > 0.95 and $|\Delta| < 0.05$.*

| | train | test |
|---|---|---|
| base `Determine` (Option 1) | **1.0000** | **1.0000** |
| Δ (Option 1) | **0.0037** | **0.0256** |

`[EXP]` **Both clauses hold.** Option 1 saturates completely and its Δ is negligible. Under Option 1
this experiment would have reported *"relevance has no effect"* — a **false negative produced by
the contract**, not by the domain.

> **The owner's revision from Option 1 to Option 3 is vindicated by measurement.** Had Option 1
> remained frozen, the run would have been uninformative and would have looked like a result.

---

## 5. ⚠️ DIAGNOSTIC — **NOT ADJUDICATED, NOT THE FROZEN ESTIMAND**

The frozen estimand counts **undirected** flips. Splitting by direction:

| | DESTROYED | CREATED | changed | unchanged |
|---|---|---|---|---|
| **root-cause removed** (train / test) | **0.241 / 0.260** | 0.098 / 0.092 | 0.042 / 0.039 | 0.620 / 0.609 |
| **decoy removed** (train / test) | 0.118 / 0.126 | 0.131 / 0.131 | 0.028 / 0.032 | 0.723 / 0.710 |

$$\Delta_{\text{directed}} = P(\text{DESTROYED}\mid\text{root}) - P(\text{DESTROYED}\mid\text{decoy}) = \mathbf{+0.123 \,/\, +0.134}$$

**Removing the true cause is net destructive (0.24 destroyed vs 0.10 created). Removing a decoy is
symmetric (0.12 vs 0.13) — no direction at all.**

> ### ⚠️ **This clears the 0.10 floor on both splits. It is NOT the verdict.**
> The estimand was frozen as the **undirected** flip rate before execution. **Switching estimand
> after seeing results is exactly the failure the pre-registration exists to prevent**, and it is
> not done here. The directed quantity is reported as a **diagnostic** and carried forward as a
> **pre-registered estimand for the next experiment**, where it can be earned rather than
> discovered.

`[REC]` The undirected estimand was **the wrong choice, and it was my choice.** Decoy removal
changes determination in *both* directions and roughly symmetrically, so an undirected count
cancels the signal. The direction is the relevance information.

---

## 6. Register

| statement | status |
|---|---|
| `H3a` — root-cause removal changes determination more than decoy removal (**undirected**) | **`[EXP]` BORDERLINE — not supported at the frozen floor** |
| Control `Z-D` (decoys materially below root) | **FAILED** — 0.267 vs 0.324 |
| `H3c` attribution control | **`[DEFECT]` degenerate — equals the base rate by construction** |
| Option 1 saturates and hides the effect (`P-Z3`) | **`[EXP]` CONFIRMED** — 1.0000, Δ ≤ 0.026 |
| Directed destruction asymmetry (+0.12 / +0.13) | **`[OPEN]` diagnostic — NOT adjudicated** |
| `Zero` is inquiry-relative | **`[OPEN]`** — this run does not establish it |

## 7. Recommendation `[REC]`

1. **`KR-ZOOM-04`**: pre-register the **directed** estimand $P(\text{DESTROYED}\mid\text{root}) - P(\text{DESTROYED}\mid\text{decoy})$ **before** running.
2. **Repair `H3c`.** The attribution control must vary the view *without* being a proxy for the
   determination itself. A view that removes the anchor's own descendants is not a control.
3. **No promotion.** Theory v1.2 unchanged, kernel unchanged, `P1`–`P3` unchanged, `Zoom`
   definition still a research candidate.
