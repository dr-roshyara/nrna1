# `KR-ZOOM-OUT-02` — **PRE-REGISTRATION DESIGN**

**Status:** `[DESIGN]` · **NOT pre-registered · NOT authorized · NOT run.**
**Date:** 2026-09-05 · Theory v1.2 FROZEN · kernel NOT SELECTED · no Zoom-out definition exists
**Predecessors:** `KR-ZOOM-OUT-01` (**failed as primary confirmatory / diagnostically informative**)
· `KR-ZOOM-OUT-02-SELECTION-AUDIT-2026-09.md`

> This artifact is the **design of a pre-registration**, per the owner's instruction that the next
> step be a pre-registration design rather than execution. **It ratifies nothing.**

---

## 1. The protocol this run must obey

$$\boxed{\;\text{Design} \to \text{Pre-register} \to \text{Calibration} \to \text{Freeze} \to \text{Analysis}\;}$$

**Frozen 2026-09-05** (theory doc 10 §7a). **No analysis-dependent choice of estimand or
population.**

### 1.1 Calibration is a **separate executable**, gated

`[REC]` `calibrate.py` runs alone, writes `data/calibration.json`, and **exits**. Its output is
inspected and the gate decided **before `analyse.py` exists in a runnable state**.

**`KR-ZOOM-OUT-01`'s gate failure (0.294 / 0.265 vs 0.30–0.80) was invisible until every outcome
had been seen**, because calibration and analysis ran in one pass. That is a **process defect** and
this is its repair.

---

## 2. ⚠️ The population correction — **stratify, do not filter**

$$\mathcal P = \mathcal P_{\text{det}} \;\cup\; \mathcal P_{\neg\text{det}}$$

`Determine` reached ∈ {yes, no} is a **declared stratification variable**, **never** a population
filter.

| | filtering | **stratifying** |
|---|---|---|
| can hide the stratum that explains the result | **yes — and it did** | no |
| permits a post-hoc population switch | yes | **no** |
| `M1`-as-artifact detectable | only by audit | **immediately** |

> ### The failure this repairs
> `KR-ZOOM-OUT-01` reported `M1` = 0.634 as the single winner. **`M1` ≡ the no-determination rate**
> (0.6333), to three decimals. The null stratum became the "winner". **Reporting stratum sizes as a
> first-class quantity makes that impossible.**

### 2.1 Why conditioning is defensible here, and why we still don't do it

The audit established that $D$ is an **ancestor** of the outcome ($\mathrm{zoom\_out}$ consumes it),
not a collider; the structural backdoor via evidence-graph size is empirically negligible
(10.755 vs 11.052); and the `M0` inversion **survives stratification unchanged** (0.4945 → 0.4977).

**So filtering would probably not have been fatal. Stratifying is still better**, because it
removes the *possibility* of the error rather than arguing it away — and because
**root depth, detail count and coverage were never stratified.**

---

## 3. Every estimand is labelled **contract-relative**

`[REC]` `FR-004` **candidate**: $\mathrm{Determine} = \mathrm{Determine}(K, Q, C, E_C, S, R)$.

$\mathcal P_{\text{det}}$ is a property of **(domain × contract)**, not of the domain. **No estimand
computed on it may be reported as "what Zoom-out does"** — only as *"what Zoom-out does under this
contract, at this standard, in this stratum."*

`[REC]` **Report the stratum sizes under at least two epistemic standards $S$** (e.g.
$\tau_m = 1$ and $\tau_m = 2$). If the strata move materially, `FR-004` gains a seventh instance —
**declared now, so it is a prediction rather than a discovery.**

---

## 4. Estimands — to be proposed with directions, then frozen

| | within stratum | direction |
|---|---|---|
| **A1** | $\mathcal P_{\text{det}}$ | class distribution over `M0/M1/M2/M3a/M3b/MX` |
| **A2** | $\mathcal P_{\neg\text{det}}$ | the same distribution, **reported in full, never dropped** |
| **A3** | both | **stratum sizes**, as a first-class reported quantity |
| **A4** | $\mathcal P_{\text{det}}$ | $P(\text{determination GAINED at } Q_{\text{broad}})$ — **directed**, vs the null round-trip |
| **A5** | $\mathcal P_{\text{det}}$ | $P(\mathrm{Avail} \text{ lost})$ — the `M0` rate, **reported first** |

**The primary must be nominated and frozen before the run.** Recommendation: **`A5`**, because
`M0` — *zoom-out destroying answerability* — is the only candidate that both replicated in
`KR-ZOOM-OUT-01` and survived the selection audit.

`[REC]` **`A4` is NOT recommended as primary.** Its conditional value (0.182 / 0.185) was seen
after the fact; nominating it now would be adopting a diagnostic because it cleared a floor.
**It should be a pre-registered secondary and earn its place on fresh data.**

---

## 5. Controls carried forward

`O-A` null round-trip (four equalities: **Structural · Observable · Contract-semantic ·
Historical/provenance**, separate) · `O-B` determined nothing · `O-C` determined a decoy ·
`O-D` $n$-cycle fidelity · `O-E` paired seeds · **`O-F` degeneracy pre-check, run before the run.**

`[REC]` **New: `O-G` — stratum-artifact check.** For every classification rate, test whether it
equals a stratum size to within 0.005. **If it does, it is reported as a stratum artifact, not a
finding.** This is the `M1` failure, institutionalised.

---

## 6. What must be decided before freezing

1. The **primary estimand** (recommendation: `A5`).
2. The **effect floor** (recommendation: carry 0.10 / 0.05 forward unchanged).
3. The **single-winner threshold** and whether it applies **within stratum** (recommendation: yes,
   within $\mathcal P_{\text{det}}$ only, with `MX` first-class).
4. The **second epistemic standard** $S'$ for the `FR-004` prediction of §3.
5. Whether root depth, detail count and coverage join **evidence-graph size** as declared strata.

## 7. What this experiment still cannot do

**Cannot** add a definition of Zoom-out · promote `FR-004`, `P1`–`P3`, or 7b · touch Theory v1.2 or
the kernel · settle `OQ-1` or `DECISION-02`.

$$\boxed{\text{Observed phenotype} \;\neq\; \text{definition} \;\neq\; \text{Theory amendment}}$$
