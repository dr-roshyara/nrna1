# `KR-ZOOM-OUT-02` — **PRE-REGISTRATION · FROZEN**

**Experiment ID:** `KR-ZOOM-OUT-02-STRATIFIED-CONTEXT-RETURN-2026-09`
**Status:** **`[FROZEN]` 2026-09-05**, approved for freeze by the research owner.
**Execution:** **STOPPED BY THE CALIBRATION GATE, 2026-09-05.** See §6. The frozen protocol is UNMODIFIED.
Theory v1.2 FROZEN · kernel UNTOUCHED · Zoom-out UNDEFINED · `FR-004` CANDIDATE

$$\boxed{\;\text{Freeze} \to \text{Calibration} \to \text{Gate} \to \text{Execution} \to \text{Analysis}\;}$$

---

## 1. The layer separation this protocol exists to protect

$$\boxed{\;\text{Population} \;\neq\; \text{Stratification} \;\neq\; \text{Contract} \;\neq\; \text{Phenotype} \;\neq\; \text{Effect}\;}$$

`KR-ZOOM-OUT-01` demonstrated how easily these collapse into one another: its "winner" `M1` was a
**population** artifact wearing a **phenotype** label.

## 2. Roles — a declared quantity can be descriptive **without** being an adjudicated effect

| quantity | role |
|---|---|
| `A1` / `A2` | **descriptive stratum distributions** |
| `A3` | **population composition** |
| `A4` | **directed exploratory / secondary effect** |
| **`A5`** | **primary descriptive phenotype** |
| **$\Delta_{\text{loss}}$** | **primary ADJUDICATED comparative effect** |
| `S`/`S'` intersection analysis | **contract-sensitivity diagnostic** |

> `[REC]` **Nothing outside the $\Delta_{\text{loss}}$ row is a causal claim.** This table blocks
> every measured quantity from silently becoming one.

## 3. Frozen definitions

$$A_5 = P\!\left(\neg\,\mathrm{Avail}(Q_b, K_{\text{post}}) \;\middle|\; \mathrm{Determine}_{\text{preZO}}(Q_b) = 1\right) \quad\textbf{no floor, no } \theta_0$$

$$\Delta_{\text{loss}} = P\!\left(\text{Loss} \mid \text{TrueCause},\, \mathrm{Det}_{\text{pre}}{=}1\right) - P\!\left(\text{Loss} \mid \text{Decoy},\, \mathrm{Det}_{\text{pre}}{=}1\right)$$

$$A_4 = P\!\left(\mathrm{Determine}_{\text{postZO}}(Q_b){=}1 \mid \mathrm{Determine}_{\text{preZO}}(Q_b){=}0\right) - \left[\text{same, null round-trip}\right]$$

**Floor** (on $\Delta_{\text{loss}}$ only): $\ge 0.10$ material · $[0.05, 0.10)$ borderline · $< 0.05$ negligible · **both splits**.

**Population:** $\mathcal P = \mathcal P_{\text{det}} \cup \mathcal P_{\neg\text{det}}$ — **stratify, never filter.** Both strata reported; **stratum sizes are `A3`, a first-class quantity.**

**Winner rule:** a class must exceed **0.60 within $\mathcal P_{\text{det}}$** on **both** splits. `MX` first-class. **No winner is a legitimate and expected outcome.**

**Determine contract:** `KR-ZOOM-03` Option 3 **verbatim**, for both $Q_{\text{focus}}$ and $Q_{\text{broad}}$. $S = (\tau_s{=}1, \tau_m{=}1)$ · $S' = (\tau_s{=}1, \tau_m{=}2)$.

**Contract sensitivity:** report **(i)** the total shift across $\mathcal P_{\text{det},S}$ vs $\mathcal P_{\text{det},S'}$ **and (ii)** the phenotype component on the **intersection**. $S \to \mathcal P_{\text{det},S}$: this is a **contract-relative stratified analysis, not an intervention on a fixed population.**

**Strata (immutable):** evidence-graph size · root depth. *Common-cause rule.* Detail count = precision only. **Coverage EXCLUDED — mediator.**

$$\boxed{\text{No stratifier may be dropped after data generation. Sparse cells are reported as sparse and do NOT trigger redesign.}}$$

**Controls:** `O-A` (four equalities: Structural · Observable · Contract-semantic · Historical/provenance) · `O-B` · `O-C` (supplies the decoy comparator) · `O-D` · `O-E` paired seeds · `O-F` degeneracy pre-check · **`O-G`**: $|P(M_i) - P(\mathcal P_s)| \le 0.005 \Rightarrow$ *reported as a stratum artifact under `O-G`* — a **protocol classification**, not a causal explanation.

## 4. The calibration gate — authority to **STOP**, not to silently modify

**Gate:** base $\mathrm{Determine}(Q_{\text{broad}})$ under $S$ must land in $[0.30, 0.80]$.

`[REC]` **Calibration runs on a DEDICATED SEED, disjoint from train and test**, so nothing seen
during calibration is information about the analysis data.

| calibration MAY | calibration MAY NOT |
|---|---|
| adjust **generator parameters**, every adjustment **logged** | touch estimands, populations, thresholds, strata, contracts or controls |
| declare the gate **unmeetable** and **STOP the experiment** | silently modify the protocol |

> **`KR-ZOOM-OUT-01`'s gate failed at 0.294 / 0.265. There is no guarantee this one passes. If the
> declared parameter grid cannot meet it, the correct outcome is that the experiment STOPS** — and
> a new, separately frozen design round follows.

## 5. After freeze — prohibited without a new pre-registration

No new primary estimand · no post-hoc stratifier removal · no threshold adjustment · no post-hoc
winner selection · **no promotion from convergence to theory.**

$$\boxed{\text{Observed diagnostic} \not\Rightarrow \text{new primary estimand}} \qquad \boxed{\text{Observed convergence} \not\Rightarrow \text{theory promotion}}$$


---

# 6. CALIBRATION GATE OUTCOME — **THE EXPERIMENT STOPS**

**Run 2026-09-05** · `code/calibrate.py` · dedicated seed `55520260905` · n = 1200 · **27 of 27
declared grid points attempted.**

$$\text{base } \mathrm{Determine}(Q_{\text{broad}}) \mid S \;\in\; [0.24667,\ 0.2875] \qquad\text{gate } [0.30,\ 0.80]$$

> ### **GATE NOT MET AT ANY GRID POINT. THE EXPERIMENT STOPS.**
> Per §4, calibration has authority to **stop**, not to silently modify. **No estimand, population,
> threshold, stratum, contract or control has been changed. The protocol above stands frozen and
> unexecuted.**

## 6.1 Diagnosis — the grid was mis-specified

The span across **all 27 points is 0.0408**. The declared parameters — `p_prior_wrong`,
`p_cover`, `n_detail` — govern the **anomaly attribution and the detail claims**.
$\mathrm{Determine}(Q_{\text{broad}})$ is governed instead by the **claim structure** of $K_0$: the
number of dimensions, subjects per dimension, and the size of the value alphabet, which decide how
often a unique $(\text{dim}, \text{value})$ pair beats every rival by $\tau_m$.

> ### `[EXP]` **The `KR-ZOOM-OUT-02` calibration failure is independently diagnosed as a GENERATOR-PARAMETERISATION failure.**
>
> ⚠️ **Wording corrected 2026-09-05.** This section originally read *"the grid could not have met
> the gate, at any size."* **That overreaches.** The supported claim is narrower and is the one
> that stands: **within the tested parameter space and the tested one-at-a-time sensitivity design,
> the three parameters used by `KR-ZOOM-OUT-02` could not move the gated quantity into the required
> band.**

## 6.2 `[REC]` The rule this failure earns

$$\boxed{\text{A calibration grid MUST contain at least one parameter the GATED QUANTITY is demonstrably sensitive to.}}$$

Sensitivity is shown **before freezing**, by a one-parameter sweep on a dedicated seed.

> ### ~~A grid whose span on the gated quantity is smaller than the gate's own width is not a grid.~~
> **AMENDED 2026-09-05 — see §6.2a. Original text preserved above, struck through, not deleted.**

### 6.2a AMENDMENT — the **operational** reading, ratified 2026-09-05

**Decided by the research owner**, on a discrepancy I surfaced against myself: `sensitivity.py`
silently applied *half* the gate width per parameter while §6.2 as frozen demanded a **joint span
≥ the full gate width (0.50)**. Those are not the same test.

> **AMENDMENT (ratified wording):** *§6.2 is replaced by the operational gate-reachability
> criterion. The former span ≥ gate-width criterion is **withdrawn**, because it tests **interval
> traversal** rather than whether the calibration grid can **reach the admissible calibration
> region**.*

$$\boxed{\exists\,\theta \in \Theta_{\text{grid}} : \; Y(\theta) \in [L, U]}$$

### The criterion, stated so it cannot become permissive

$$\boxed{\text{Grid Adequacy} \iff \begin{cases}\text{at least one admissible point exists, and}\\ \text{the admissible region is not represented solely by a preselected boundary hit.}\end{cases}}$$

| | |
|---|---|
| gate **reachability** | **required** |
| **number of in-band points** | **REPORTED as a diagnostic** — deliberately *not* another universal threshold |
| full gate-width span | **not required** |
| **post-hoc grid expansion** | **PROHIBITED** |
| **grid redesign after calibration** | **PROHIBITED** unless the experiment is explicitly returned to design status |

**Reason for the withdrawal.** A quantity bounded in $[0,1]$ with a 0.50-wide gate can be perfectly
well-behaved and never span 0.50. The strict rule rejected adequate grids for a property of **the
gate's width**, not of the grid.

**What the amendment does NOT relax.** The *sensitivity* requirement stands unchanged: a grid built
only from parameters demonstrably inert on the gated quantity — `p_cover` at span **0.0000** being
the measured case — remains **not a grid**. **`KR-ZOOM-OUT-02` would still have been stopped under
the amended rule.**

**Recorded as an amendment, with its date, its decider and its reason — never applied silently.**

## 6.3 What happens next — and what must not

**Required:** a **new, separately frozen design round**, whose grid includes claim-structure
parameters and demonstrates sensitivity before freeze.

**Prohibited without that fresh freeze:** widening this grid · relaxing the band · substituting
another gated quantity · proceeding to execution. **Each would be exactly the silent modification
the gate exists to prevent.**

## 6.4 What the gate did NOT do

It did not invalidate the protocol. **`A5`, $\Delta_{\text{loss}}$, the stratification design, the
role table, `O-G` and the layer separation are untouched and reusable verbatim** in the next round.
`O-F` reachability passed at **every** grid point — all six classes reachable.
**Only the generator's parameterisation failed.**
