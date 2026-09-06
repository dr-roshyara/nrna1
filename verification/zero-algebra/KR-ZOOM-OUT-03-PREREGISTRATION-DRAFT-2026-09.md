# `KR-ZOOM-OUT-03` — **PRE-REGISTRATION DRAFT**

**Status:** **`[FROZEN — PRE-REGISTERED]` 2026-09-05.** Grid-adequacy criterion ratified by the research owner;
`KR-ZOOM-OUT-02` §6.2 **amended on the record** (§6.2a there). **Calibration gate RUN and MET, 2026-09-05 (§7). Analysis NOT RUN.**
**Date:** 2026-09-05 · Theory v1.2 FROZEN · kernel UNTOUCHED · Zoom-out UNDEFINED · `FR-004` CANDIDATE
**Predecessor:** `KR-ZOOM-OUT-02` — frozen, then **stopped by its own calibration gate**.

---

## 1. What is inherited **verbatim**, and what is replaced

`KR-ZOOM-OUT-02`'s gate stop invalidated **only the generator's parameterisation.** Everything else
is re-used unchanged, which is the point of having frozen it separately:

**PROVENANCE — "verbatim" means the exact frozen text, not the conceptual intent.**

| | |
|---|---|
| inherited artifact | `KR-ZOOM-OUT-02-PREREGISTRATION-2026-09.md`, **§1–§5 as frozen 2026-09-05** |
| content hash | `sha256[:16] = 0624d5fb58e6c87f` |
| **NOT inherited** | **§6 and §6.2a** — post-stop additions, including the amendment itself. They are **change record**, not inherited protocol. |

`[REC]` **No later correction is silently propagated into the "inherited verbatim" column.** Any
sentence that has changed since the `KR-ZOOM-OUT-02` freeze belongs in the change record, not here.

| inherited **verbatim** | replaced |
|---|---|
| `A5` · $\Delta_{\text{loss}}$ · `A4` · `A1`–`A3` and their **role table** | the **calibration grid** |
| the layer separation *Population ≠ Stratification ≠ Contract ≠ Phenotype ≠ Effect* | |
| stratify-don't-filter · immutable stratifiers · no post-generation dropping | |
| floor 0.10 / 0.05 on $\Delta_{\text{loss}}$ only; `A5` reported with **no** $\theta_0$ | |
| winner rule 0.60 within $\mathcal P_{\text{det}}$, `MX` first-class | |
| `S`/`S'` as **contract-relative stratified analysis**, prediction decomposed | |
| controls `O-A`…`O-G` | |

---

## 2. The sensitivity demonstration — **required before freeze**

`code/sensitivity.py` · dedicated seed `77720260905`, disjoint from calibration **and** from
train/test · one parameter at a time, all others at `KR-ZOOM-OUT-01` values.

**Gated quantity:** base $\mathrm{Determine}(Q_{\text{broad}})$ under $S$. **Gate** $[0.30, 0.80]$.

| parameter | span | reaches the band? |
|---|---|---|
| **`subjects`** (per dimension) | **0.2775** | ✅ at (1,1), (1,2), (3,4), (4,5) |
| **`n_dims`** | **0.1458** | ✅ at 3, 4, 14 |
| **`value_alphabet`** | **0.1183** | ✅ at 2, 3 |
| `n_detail` | 0.0367 | ✗ |
| `p_prior_wrong` | 0.0050 | ✗ |
| **`p_cover`** | **0.0000** | ✗ |

### `[EXP]` The `KR-ZOOM-OUT-02` diagnosis is confirmed by independent measurement

**`p_cover`, `p_prior_wrong` and `n_detail` WERE the entire KR-ZOOM-OUT-02 grid.** Their spans sum
to ≈ 0.042 — and the observed joint span at calibration was **0.0408**. **`p_cover` cannot move the
gated quantity at all: span exactly 0.000.**

> **`[EXP]` The `KR-ZOOM-OUT-02` calibration failure is independently diagnosed as a
> GENERATOR-PARAMETERISATION failure.**
>
> ⚠️ **Scope, stated precisely.** *Not* "the experiment could never have worked." The supported
> claim is: **within the tested parameter space and the tested one-at-a-time sensitivity design,
> those three parameters could not move the gated quantity into the required band.**

---

## 3. ⚠️ A discrepancy in my own criterion — surfaced, not resolved

The rule frozen in `KR-ZOOM-OUT-02` §6.2 reads:

> *"A grid whose span on the gated quantity is smaller than the gate's own width is not a grid."*

**Literally, that demands span $\ge 0.50$.** My `sensitivity.py` applied a **softened** per-parameter
criterion (span $\ge$ **half** the gate width) and reported `RULE SATISFIED: True`. **Those are not
the same test, and the softening was mine.**

| reading | test | proposed grid |
|---|---|---|
| **strict** (as frozen) | joint span $\ge 0.50$ | **FAILS** — joint span **0.2767** |
| **operational** | the grid contains points **inside the band** | **PASSES** — **24 of 27** points in band, range 0.266–0.542 |

`[REC]` **The operational reading is the right one and the frozen wording should be amended** —
what matters is whether the grid can *reach* the band, not whether it spans a range as wide as the
band. A quantity bounded in $[0,1]$ with a 0.50-wide gate can be perfectly well-behaved and never
span 0.50.

> ### **RESOLVED 2026-09-05.** The owner ratified the **operational** reading and directed that `KR-ZOOM-OUT-02` §6.2 be **amended on the record**. Done — original text preserved and struck through, amendment §6.2a added with date, decider and reason. **The proposed grid is therefore ADEQUATE: 24 of 27 points inside the band.**

---

## 4. The proposed grid — built **only** from parameters shown sensitive

$$\texttt{n\_dims} \in \{4, 6, 8\} \;\times\; \texttt{subjects} \in \{(1{,}2), (2{,}3), (3{,}4)\} \;\times\; \texttt{value\_alphabet} \in \{3, 4, 6\}$$

27 points · joint span **0.2767** (0.2656 → 0.5422) · **24 in band.**

| adequacy check (amended criterion) | |
|---|---|
| at least one admissible point | ✅ |
| not a lone preselected boundary hit | ✅ — 24 admissible points, spread across all three parameters |
| **in-band count (reported diagnostic)** | **24 / 27** |
| post-hoc grid expansion | **prohibited** |

All other generator parameters are **fixed at their `KR-ZOOM-OUT-01` values and are not part of the
grid** — including `p_cover`, which is demonstrably inert on the gate.

---

## 5. Decisions — all resolved 2026-09-05

| # | decision | resolution |
|---|---|---|
| 1 | grid-adequacy criterion | **OPERATIONAL** — a grid is adequate iff it contains points inside the band. **Ratified by the owner.** |
| 2 | the grid | **as proposed in §4** — it follows from criterion 1: 24 of 27 points in band |
| 3 | amend `KR-ZOOM-OUT-02` §6.2 | **DONE, on the record** — §6.2a there; original struck through, not deleted |
| 4 | verbatim inheritance | **confirmed** — every item in §1's left column was already reviewed and frozen in `KR-ZOOM-OUT-02`; re-review would be redundant, and only the grid was ever invalidated |

**Nothing in §1's left column is re-opened. Only the grid is new.**

## 6. Standing

$$\boxed{\text{Theory v1.2 FROZEN}} \quad \boxed{\text{Kernel UNTOUCHED}} \quad \boxed{\text{Zoom-out UNDEFINED}} \quad \boxed{\texttt{FR-004} \text{ CANDIDATE}}$$

**No experiment is authorized. `KR-ZOOM-04` remains unexecuted and still requires its own
pre-registration.**


---

# 7. CALIBRATION GATE OUTCOME — **MET**

`code/calibrate3.py` · dedicated seed `66620260905` (disjoint from the sensitivity seed **and** from
train/test) · n = 1200 · all 27 frozen grid points probed · **grid not expanded.**

| | |
|---|---|
| grid range on the gated quantity | **0.2625 → 0.5208** (span 0.2583) |
| **in-band count — reported diagnostic** | **24 of 27** |
| lone preselected boundary hit? | **No** |
| `O-F` all six classes reachable | **Yes** |
| **GATE** | **MET** |

**Selected point** (declared rule: admissible point closest to the band centre, ties by parameter string):

$$\texttt{n\_dims}=4 \quad \texttt{subjects}=(1,2) \quad \texttt{value\_alphabet}=6$$

$$\mathrm{Determine}(Q_{\text{broad}}) \mid S = \mathbf{0.5208} \qquad \mathrm{Determine}(Q_{\text{focus}}) = 0.3792 \qquad \text{decoy share (O-C comparator)} = 0.3363$$

---

## 7.1 ⚠️ A LIMITATION SURFACED AT CALIBRATION — recorded **before** execution

$$\mathrm{Determine}(Q_{\text{broad}}) \mid S' = \mathbf{0.0683}$$

Under the stricter standard $S' = (\tau_s{=}1, \tau_m{=}2)$, determination at the broad level is
**rare**. Therefore:

$$\left|\mathcal P_{\text{det},S} \cap \mathcal P_{\text{det},S'}\right| \;\le\; 0.068 \times n$$

> ### **Decision 4's phenotype component (ii) — the intersection analysis where membership is held constant — will be UNDERPOWERED.** At n = 1500 per split the intersection holds ≈ 100 cases.

`[REC]` **The protocol is NOT changed.** $S'$ is frozen. What is recorded is that **component (ii)
is expected to be underpowered, and this was known before execution rather than discovered after.**
Component **(i)**, the total shift, is unaffected.

**This is exactly what a separate calibration pass is for**: the limitation is visible while it can
still be *declared*, and no longer available to be *explained*.

## 7.2 Sparse classes at the chosen point

`M2` = 12 and `M3a` = 4 of 1200. **Reported as sparse. Per the frozen rule, sparse cells do NOT
trigger redesign** — they are reported with their uncertainty, and no stratifier or class is dropped.

## 7.3 Status

$$\boxed{\text{FREEZE} \;\checkmark\; \to\; \text{CALIBRATION} \;\checkmark\; \to\; \text{GATE} \;\checkmark\; \to\; \text{EXECUTION (not run)} \to \text{ANALYSIS}}$$

**Theory v1.2 FROZEN · kernel UNTOUCHED · Zoom-out UNDEFINED · `FR-004` CANDIDATE.**
