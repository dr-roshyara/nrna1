# Are the remaining Zoom-out questions **formulable** under `O-F*`?

**Status:** `[EXP]` assessment · **NOT an experiment design. NOT a pre-registration.**
**Date:** 2026-09-05 · Theory v1.2 FROZEN · kernel UNTOUCHED · Zoom-out UNDEFINED

> **The task is not to design `KR-ZOOM-OUT-04`.** It is to ask, before any design: **can each
> remaining Zoom-out question be given an estimand that is (a) non-degenerate under `O-F*` and
> (b) adequate to the question?** A question with no such estimand should not become an experiment.

---

## 1. Evidence available without a new run

The `O-F*` sweep already measured variability across `KR-ZOOM-OUT-03`'s frozen 27-point grid. Those
counts are **reusable as formulability evidence** — no new experiment is needed to know which
estimands can move.

| candidate estimand | distinct values / 27 | non-degenerate? |
|---|---|---|
| `A4_gain` — broader determination gained | **27** | ✅ strongest available |
| `S_Sprime_i_shift` — contract-relative composition shift | **27** | ✅ |
| `A1_M0` / `A1_MX` — classification rates | **18** / 18 | ✅ |
| `A3_P_det` — population composition | 12 | ✅ |
| `A1_M1` | 7 | ✅ |
| **`A5` whole-inquiry applicability loss** | **1** | ❌ empirical |
| **`Δ_loss`** | **1** | ❌ empirical |
| **`S/S'` (ii) intersection phenotype** | **1** | ❌ **structural** |

---

## 2. Question-by-question

| # | question | estimand available? | `O-F*` | adequate? | verdict |
|---|---|---|---|---|---|
| **Q8** | **can focused investigation change what is determinable at the broader level?** | `A4_gain`, directed, vs null round-trip | ✅ **27 distinct** | ✅ directly answers it | **FORMULABLE — the strongest remaining question** |
| **Q4** | is zoom-out navigation or transformation? | the `M0/M1/M2/M3a/M3b/MX` distribution | ✅ 18 distinct | ⚠️ **descriptive only** — cannot become a causal claim under the role table | **FORMULABLE as description, NOT as effect** |
| **Q7** | does traversal order change the resulting state? | state/observable equalities, measured separately | ✅ (KR-ZOOM-01/02: 0.000 vs ~0.92) | ✅ | **FORMULABLE — already `[EXP]`, needs no new run** |
| **Q3** | does zoom-out preserve discoveries made during focus? | ❌ `A5` failed | ❌ empirical | — | **NOT FORMULABLE as written** — see §3 |
| **Q6** | does repeated in/out lose information? | `O-D` fidelity, currently a declared **constant** 1.000 | ❌ | — | **NOT FORMULABLE** — no lossy regime exists in the carrier |
| **4(ii)** | does the epistemic standard change the phenotype? | ❌ classification is not a function of $S$ | ❌ **structural** | — | **NOT FORMULABLE** — see §4 |
| Q1 | does zoom-in preserve context? | — | — | ❌ **definitional** under the operator | already excluded |
| Q2 | can investigation cross the focus boundary? | — | — | — | **settled `[EXP]`**, 23–26 %; not re-litigated |

---

## 3. Why `Q3` failed, and what would fix it

`A5` used **whole-inquiry applicability**: $\mathrm{Avail}(Q_b,K) \iff \lvert\{(\text{dim},\text{value})\}\rvert \ge 2$.
That is a **near-catastrophic** event — the entire contract becomes inapplicable — and it never
occurs at any grid point.

`[REC]` **The repair is a GRADED measure, not a threshold one.** The natural candidate already
exists and is non-degenerate: **per-observable availability**, which is what `M0` measures
(18 distinct values).

$$\text{loss}_{\text{graded}}(K_0, K_{\text{post}}) = \frac{\left|\{o : \mathrm{Avail}(o,K_0) \wedge \neg\mathrm{Avail}(o,K_{\text{post}})\}\right|}{\left|\{o : \mathrm{Avail}(o,K_0)\}\right|}$$

⚠️ **But `M0` is currently `A1` — descriptive.** Turning graded loss into an **adjudicated effect**
requires a **contrast**, and the natural one is the true-cause / decoy split that `Δ_loss` already
specified. **`Δ_loss` was not the wrong idea; `A5`'s threshold was the wrong measure inside it.**

`[REC]` **This is a hypothesis about formulability, not a result.** It must pass `O-F*` **before**
any freeze — it has not been tested.

---

## 4. Why `4(ii)` failed **structurally**, and what would fix it

The classification depends on $\mathrm{Determine}(Q_{\text{focus}})$ with $\tau_m$ **fixed at 1**.
$S$ governs $Q_{\text{broad}}$. **The phenotype has no dependence on the manipulated dimension**, so
no grid, carrier or sample size can rescue it.

`[REC]` A contract-sensitivity experiment must **manipulate the standard that the phenotype actually
depends on** — i.e. vary $\tau_m$ for $Q_{\text{focus}}$, not for $Q_{\text{broad}}$ — **or** define
a phenotype that reads $Q_{\text{broad}}$'s determination.

$$\boxed{\text{Manipulate the standard the PHENOTYPE depends on, not merely a standard.}}$$

---

## 5. Assessment

| | |
|---|---|
| **formulable now, non-degenerate, adequate** | **`Q8`** |
| formulable as description only | `Q4` |
| already settled, no run needed | `Q2`, `Q7` |
| **not formulable without a new measure** | `Q3` (graded loss — untested), `4(ii)` (structural) |
| **not formulable at all in this carrier** | `Q6` — no lossy regime exists |

> ### `[REC]` **Exactly one Zoom-out question is currently formulable as a genuine adjudicated effect: `Q8`.**
> A `KR-ZOOM-OUT-04` built around `Q8` alone would be small, single-estimand, and could pass `O-F*`
> on evidence already in hand (`A4_gain`: 27 distinct values).

**And the honest alternative deserves equal weight:** the accumulated result may be that **the
Zoom-out question, as currently posed, is not answerable in this carrier** — three experiments,
two stopped or degenerate. **That would itself be a finding**, and it is not yet excluded.

## 6. Standing

**No experiment is designed or authorized.** `Q6`'s non-formulability and `4(ii)`'s structural
failure are **carrier and protocol facts, not results about Zoom-out.**

**Theory v1.2 FROZEN · kernel UNTOUCHED · Zoom-out UNDEFINED · `FR-004` CANDIDATE, prediction failed.**
