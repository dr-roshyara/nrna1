# `KR-ZOOM-OUT-01` — RESULTS

> ## `[EXP]` **STATUS (ratified by the research owner, 2026-09-05):**
> ## **FAILED AS A PRIMARY CONFIRMATORY RUN · DIAGNOSTICALLY INFORMATIVE**
>
> Calibration gate failed · `M1` claim withdrawn · `E5` not claimed · conditional results remain
> diagnostics · **no Zoom-out definition established** · no Theory v1.2 modification · no kernel
> modification. **The run is not useless; it is not confirmatory.**

Run **strictly against** `../KR-ZOOM-OUT-01-PREREGISTRATION-2026-09.md` (ratified, frozen 2026-09-05).
**Nothing was tuned after seeing outcomes** — no estimand, direction, threshold, contract,
observable family, classification rule or control.
n = 1500 × 2 independent splits · budget 5 · seeds `20260904` / `88020260904`.

---

## 0. ⚠️ THE CALIBRATION GATE FAILED — read this first

$$\text{base } \mathrm{Determine}(Q_{\text{broad}}) = \mathbf{0.294 \,/\, 0.265} \qquad \text{gate} = [0.30,\ 0.80]$$

**Below the band on both splits.** The pre-registration required the gate to be met *before any
analysis*.

`[DEFECT]` **Process error, mine.** I ran calibration and analysis in a single pass, so by the time
the gate failure was visible I had already seen every outcome. **Re-tuning the generator now would
be contaminated**, and is not done. The gate failure therefore stands as a **stated limitation on
every number below**, and the correct repair — a separate pre-analysis calibration pass — is
recorded for the next run.

---

## 1. `M0` first, as required

> ### `[EXP]` **Zoom-out DESTROYED answerability in 18.1 % / 16.5 % of all cases** — and in **49.5 % / 44.8 %** of the cases where the investigation actually determined something *(diagnostic, §5)*.

$$E_1 = P\!\left(K_t \preceq_{\mathcal O} K_{t+1}\right) = 0.819 \,/\, 0.835$$

Determination at the broader level was **lost** in 2.5 % / 2.6 % of all cases.

**This dominates every classification question**, exactly as the pre-registration required it to.

---

## 2. Adjudicated result — the frozen estimands

| | train | test | |
|---|---|---|---|
| `E1` answerability retained | 0.819 | 0.835 | |
| `E2` observably unchanged | 0.634 | 0.635 | ⚠️ degenerate, §4 |
| `E2b` value changed given $\preceq_{\mathcal O}$ | **0.220** | **0.235** | `M3b`'s observable signature |
| `E3` detail falls given $\preceq_{\mathcal O}$ | 0.040 | 0.047 | |
| `E4` $\Delta^\circ \neq \varnothing$ given `M3` | **0.960** | **0.965** | ⚠️ generator-influenced, §4 |
| **`E5` gain** | 0.0667 | 0.0680 | **PRIMARY** |
| `E5` gain, null round-trip | 0.000 | 0.000 | ⚠️ definitional, §4 |
| **`E5` Δ** | **0.0667** | **0.0680** | floor 0.10 |

> ### `[EXP]` **PRIMARY VERDICT: BORDERLINE — reported, NOT claimed.**
> $\Delta_{E5}$ = 0.067 / 0.068 sits in the frozen 0.05–0.10 borderline band, **below the 0.10
> material floor.** The floor was frozen before execution and **is not moved.**

### Classification distribution — all cases

| class | train | test |
|---|---|---|
| `M0` loss | 0.181 | 0.165 |
| **`M1` restoration** | **0.634** | **0.635** |
| `M2` abstraction | 0.033 | 0.039 |
| `M3a` integration | 0.004 | 0.003 |
| `M3b` revision | 0.095 | 0.093 |
| `MX` none fits | 0.053 | 0.065 |

**Single-winner rule (> 0.60 on both splits): `M1` qualifies — and §4 shows the win is an artifact.**

---

## 3. Controls

| | train | test | |
|---|---|---|---|
| **`O-A` 1 Structural** | 1.000 | 1.000 | ⚠️ definitional |
| **`O-A` 2 Observable** | 1.000 | 1.000 | ⚠️ definitional |
| **`O-A` 3 Contract-semantic** | 1.000 | 1.000 | ⚠️ definitional |
| **`O-A` 4 Historical/provenance** | 1.000 | 1.000 | ⚠️ definitional |
| `O-B` determined nothing | 0.633 | 0.632 | |
| `O-C` determined a decoy | 0.114 | 0.113 | |
| `O-D` 3-cycle round-trip fidelity | **1.000** | **1.000** | no decay ✅ |
| **`O-F` degeneracy pre-check** | **PASS** | | all six classes reachable before the run |

`O-F` was run **before** execution and all six classes — including `M0` and `MX` — were shown
reachable. **No metric was reported that could not have varied.**

---

## 4. ⚠️ DEGENERATE METRICS *(mandatory — `EPISTEMIC-STATUS-VOCABULARY.md` §3a)*

| metric | value | mechanism that forces it |
|---|---|---|
| **`M1` = 0.634 / 0.635** | **the single "winner"** | **`M1` rate ≡ the no-determination rate** (0.6333 / 0.6320 — agreement to 3 decimals). When nothing is determined, zoom-out returns $K_t$ untouched and `M1` follows **by construction**. **`M1` did not win; the investigation failed to determine anything in 63 % of cases and `M1` inherited those.** |
| `E2` observably unchanged | 0.634 / 0.635 | identical to `M1` by definition |
| `O-A` all four equalities | 1.000 | the null round-trip returns $K_t$ **identically**; this is the control working, and it is `[DEF]` |
| `E5` null-round-trip gain | 0.000 | no determination ⟹ no change ⟹ no gain. **Forced** — the control confirms the harness manufactures no spurious gain |
| `E4` = 0.960 / 0.965 | `M3b` ≫ `M3a` | the generator seeds a **conflicting prior attribution in ~50 %** of cases; a determination that contradicts it necessarily produces $\Delta^\circ \neq \varnothing$. **The `M3a`/`M3b` split is substantially generator-determined and must not be read as a property of zoom-out.** |

> ### `[NEG]` **The single-winner verdict `M1` is withdrawn as substantive.** It is the no-determination rate wearing a category label.

---

## 5. ⚠️ DIAGNOSTIC — **NOT ADJUDICATED, NOT THE FROZEN ESTIMAND**

Conditioning on the cases where the investigation **actually determined something** — the only
population in which zoom-out has anything to carry back:

| class | determined (train / test) | **not determined** |
|---|---|---|
| **`M0` loss** | **0.495 / 0.448** | 0.000 |
| `M1` restoration | **0.002 / 0.007** | **1.000** |
| `M2` abstraction | 0.089 / 0.107 | 0.000 |
| `M3a` integration | 0.011 / 0.009 | 0.000 |
| `M3b` revision | 0.260 / 0.252 | 0.000 |
| `MX` none fits | **0.144 / 0.178** | 0.000 |
| **`E5` gain** | **0.182 / 0.185** | 0.000 |

> ### The picture inverts. **`M1` collapses from 0.634 to 0.002. `M0` becomes the largest class at ~0.47. `MX` reaches ~0.16. And `E5` reaches 0.182 / 0.185 — above the 0.10 floor.**

⚠️ **This is NOT the verdict.** The estimand was frozen **unconditionally** before execution.
Switching to the conditional population after seeing results is precisely what pre-registration
forbids — the same discipline applied to `KR-ZOOM-03`'s directed diagnostic.

`[REC]` **The unconditional estimand was the wrong choice, and it was mine.** 63 % of cases had
nothing to carry back, and those cases diluted every rate. **`KR-ZOOM-OUT-02` must pre-register the
population as *cases in which a determination was reached*.**

---

## 6. Register

| statement | status |
|---|---|
| `E5` — focused investigation gains broader determination | **`[EXP]` BORDERLINE — not supported at the frozen floor** |
| Zoom-out destroys answerability at a material rate | **`[EXP]`** — 0.18 / 0.17 unconditional |
| `M1` (restoration) is what zoom-out does | **`[NEG]`** — withdrawn; it is the no-determination rate |
| Any single category describes zoom-out | **`[NEG]`** at the frozen 0.60 rule, once `M1` is withdrawn |
| `MX` is a non-trivial share | **`[EXP]`** — 0.05 / 0.07 unconditional, ~0.16 conditional |
| `M3a` vs `M3b` split | **`[DEF]`** — substantially generator-determined |
| Conditional-population inversion | **`[OPEN]` diagnostic — NOT adjudicated** |
| Calibration gate met | **`[NEG]`** — 0.294 / 0.265, **below band**; `[DEFECT]` in my process |
| Zoom-out definition | **UNCHANGED** — no definition is added by this run |

## 7. Governance

**Theory v1.2 FROZEN. Kernel NOT SELECTED. `P1`–`P3` unchanged. The `Zoom` research definition is
unchanged. No definition of Zoom-out is added.** `KR-ZOOM-04` was **not** executed as part of this
run; it still requires its own pre-registration.

$$\boxed{\text{Observed phenotype} \;\neq\; \text{definition} \;\neq\; \text{Theory amendment}}$$
