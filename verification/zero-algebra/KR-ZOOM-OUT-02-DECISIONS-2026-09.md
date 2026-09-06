# `KR-ZOOM-OUT-02` — the five open decisions, worked

**Status:** **`[REVIEW]` ALL THREE BLOCKING CORRECTIONS APPLIED — awaiting the owner's review of the
resulting protocol. NOT frozen. NOT authorized. NOT run.**

**Correction round 2026-09-05 (owner):** ① the $n<60$ post-generation stratifier-dropping rule
**withdrawn** (§ Decision 5) · ② the effect floor **re-targeted** from the `A5` proportion to the
$\Delta_{\text{loss}}$ contrast, **with no $\theta_0$ invented** (§ Decision 2) · ③ the `S`/`S'`
comparison **reframed** as changing stratum composition, with the prediction decomposed
(§ Decision 4).

$$\boxed{\text{recommended design} \;\neq\; \text{ratified pre-registration} \;\neq\; \text{executed experiment}}$$

> ### ⚠️ **All five decisions remain GENUINELY OPEN.** Nothing here is ratified. **"`A5` is primary" is a RECOMMENDATION**, and must not become a ratified choice by repetition.

---

## 0. Estimand TYPES — labelled, so they cannot be misread as effects

| | estimand | **type** |
|---|---|---|
| `A1` | class distribution over `M0/M1/M2/M3a/M3b/MX` in $\mathcal P_{\text{det}}$ | **descriptive classification** |
| `A2` | the same in $\mathcal P_{\neg\text{det}}$ | **descriptive classification** |
| `A3` | stratum sizes | **population composition** |
| `A4` | determination **gained** at $Q_{\text{broad}}$ | **directed effect estimand** |
| `A5` | **answerability loss** at $Q_{\text{broad}}$ | **effect estimand** |

> `[REC]` **`A1`, `A2` and `A3` are descriptions, not evidence of a Zoom-out effect.** Labelling
> them prevents a distribution or a population count from being read as a finding.

### The two effect estimands, defined unambiguously

$$A_5 \;=\; P\!\left(\neg\,\mathrm{Avail}\!\left(Q_{\text{broad}}, K_{\text{post}}\right) \;\middle|\; \mathrm{Determine}_{\text{preZO}}\!\left(Q_{\text{broad}}\right) = 1\right)$$

$$A_4 \;=\; P\!\left(\mathrm{Determine}_{\text{postZO}}\!\left(Q_{\text{broad}}\right) = 1 \;\middle|\; \mathrm{Determine}_{\text{preZO}}\!\left(Q_{\text{broad}}\right) = 0\right) \;-\; \left[\text{same, null round-trip}\right]$$

`[REC]` **`preZO` and `postZO` are written explicitly in every formula.** *"M0 rate"* was a vague
label covering several different loss phenomena; it is retired in favour of the expression above.

---

## Decision 1 — the primary estimand

**`[REC]` `A5` primary.** Rationale, stated at the strength the evidence supports:

> **`A5` is recommended as primary because `M0` was the most stable diagnostic phenotype in
> `KR-ZOOM-OUT-01`, and its crude rate was essentially unchanged under the tested stratification
> (0.4945 → 0.4977; 0.4475 → 0.4476). This does NOT constitute confirmatory evidence, because
> `KR-ZOOM-OUT-01`'s calibration gate failed.**

⚠️ **Correction to my own earlier wording.** I wrote that `M0` *"survived the selection audit"*.
That reads as though the scientific effect was validated. **It was not.** The audit tested one
structural proxy against a run whose gate had failed. **The phrasing above replaces it.**

> **`[REC]` `A4` remains NOT recommended as primary.** Its conditional value (0.182/0.185) was seen
> after the fact. **Pre-registered secondary, earning its interpretation on fresh data.**

---

## Decision 2 — the effect floor · **RESOLVED without inventing $\theta_0$**

`A5` as defined is a **proportion**; the 0.10 floor is defined on a **difference**. My `2-c` still
required a declared $\theta_0$ — and **nothing in the domain fixes one**. Inventing a number to
complete the table would be worse than leaving the decision open.

### The resolution: two roles, both declared

| | quantity | role |
|---|---|---|
| **`A5`** | $P\!\left(\neg\mathrm{Avail}(Q_b, K_{\text{post}}) \mid \mathrm{Determine}_{\text{preZO}}(Q_b)=1\right)$ | **primary REPORTED quantity** — *what proportion of previously-determined states lose answerability?* **No floor. No $\theta_0$.** |
| **$\Delta_{\text{loss}}$** | $P\!\left(\text{Loss} \mid \text{TrueCause},\, \mathrm{Det}_{\text{pre}}{=}1\right) - P\!\left(\text{Loss} \mid \text{Decoy},\, \mathrm{Det}_{\text{pre}}{=}1\right)$ | **primary ADJUDICATED effect** — the floor attaches **here** |

$$\left|\Delta_{\text{loss}}\right| \ge 0.10 \;\Rightarrow\; \textbf{material} \qquad 0.05 \le \left|\Delta_{\text{loss}}\right| < 0.10 \;\Rightarrow\; \textbf{borderline} \qquad < 0.05 \;\Rightarrow\; \textbf{negligible}$$

> ### The existing floor now has a **non-arbitrary target**, and `A5` keeps its plain scientific meaning without a made-up threshold.

`O-C` supplies the decoy comparator (0.114 / 0.113 of cases in `KR-ZOOM-OUT-01`).

The floor has held under pressure **twice** — `KR-ZOOM-03` (0.058), `KR-ZOOM-OUT-01` (0.067) — and
was not moved either time. **Changing it after two runs failed to clear it would be the clearest
possible breach of the safeguard it exists to enforce.**

---

## Decision 3 — the single-winner threshold

**`[REC]` 0.60, within $\mathcal P_{\text{det}}$ only, `MX` first-class.**

### Expect NO winner — and that is a legitimate result

`KR-ZOOM-OUT-01`'s conditional distribution: `M0` ≈ 0.47 · `M3b` ≈ 0.26 · `MX` ≈ 0.16.
**Nothing approaches 0.60.** Lowering the threshold *after* seeing that 0.47 is the likely maximum
would be precisely the post-hoc move the programme forbids. **Keep 0.60 and accept "no single
meaning describes Zoom-out" as the honest and probable outcome.**

---

## Decision 4 — the second epistemic standard $S'$

**`[REC]` $S' = (\tau_s = 1,\ \tau_m = 2)$**, uniqueness unchanged, **chosen and frozen BEFORE
calibration and execution.**

### ⚠️ INTERPRETIVE CORRECTION — $S$ changes **who is in the stratum**

$$\boxed{\;S \longrightarrow \mathcal P_{\text{det},S} \qquad\text{and in general}\qquad \mathcal P_{\text{det},S} \neq \mathcal P_{\text{det},S'}\;}$$

A stricter determination standard changes **membership** of the determined stratum. The `S`/`S'`
comparison is therefore **a contract-relative stratified analysis, NOT a pure intervention on an
otherwise identical population.** It must never be read as a causal effect of $S$ holding the
population fixed.

**Stated correctly, the question is:** *how does the composition of the pre-Zoom-out determined
population change when the determination contract changes, and how does the subsequent Zoom-out
phenotype differ?*

### The prediction, decomposed so the two are separable

⚠️ *"Stricter margin ⟹ fewer determined cases"* is near-certain by construction and tests nothing.

$$\textbf{(i) total shift} \quad \left|P\!\left(M_0 \mid \mathcal P_{\text{det},S}\right) - P\!\left(M_0 \mid \mathcal P_{\text{det},S'}\right)\right| \;\ge\; 0.10$$

$$\textbf{(ii) phenotype component} \quad \left|P\!\left(M_0 \mid \mathcal P_{\text{det},S} \cap \mathcal P_{\text{det},S'},\, S\right) - P\!\left(M_0 \mid \mathcal P_{\text{det},S} \cap \mathcal P_{\text{det},S'},\, S'\right)\right|$$

**On the INTERSECTION — the cases determined under both standards — membership is held constant.**

| | reading |
|---|---|
| (i) large, (ii) ≈ 0 | the shift is **pure composition** — different cases, same behaviour |
| (i) large, (ii) large | the standard changes **the phenotype itself** — the stronger claim |
| (i) ≈ 0 | no contract sensitivity detected at this margin |

**Both declared now**, so neither can be selected after the fact.

---

## Decision 5 — structural strata · ⚠️ **my earlier recommendation was the anti-pattern**

I proposed adding root depth and detail count *"because they were not stratified"*. **That is
outcome-blind in the wrong way**: it creates a menu of candidate strata from which an informative
one could later be selected. **Withdrawn.**

### `[REC]` A deterministic, design-based inclusion rule, fixed **before data generation**

$$\boxed{\text{Include } X \text{ as a stratification variable} \iff X \text{ is a plausible COMMON CAUSE of both determination and the outcome, per the generator's declared causal structure, and } X \text{ is not on the causal path.}}$$

Applying the rule to the four candidates — **from the generator's structure, not from any result**:

| candidate | causes determination? | causes the outcome? | verdict |
|---|---|---|---|
| **evidence-graph size** | yes | yes (via root identity → detail claims) | **INCLUDE** — confounder; also the one variable already examined in the audit |
| **root depth** | yes (position in the graph) | yes (root identity fixes which detail claims exist) | **INCLUDE** — confounder by the rule |
| **detail count** | **no** — drawn independently of the evidence graph | yes | **EXCLUDE as a confounder.** A cause of the outcome only is a **precision covariate**, not required for validity. May be reported as precision **only if declared now**. |
| **coverage** | no | yes — **and it IS the mechanism** | **EXCLUDE — MEDIATOR** |

### Why coverage must not be a stratum

$$\text{determination} \longrightarrow \text{coverage} \longrightarrow M_0$$
$$\text{covered} \Rightarrow \text{consolidated (}M2\text{)} \qquad \text{uncovered} \Rightarrow \text{retracted (}M0\text{)}$$

> **Stratifying on coverage would block the very effect being measured.** It lies *on* the causal
> path. Conditioning on a mediator would drive the `M0` contrast toward zero **by construction** —
> and the result would look like a clean negative.

### ⚠️ MANDATORY CORRECTION — the $n < 60$ rule is withdrawn

My wording was **incoherent**: it said a stratifier would be dropped for sparse cells *"before the
run"* — but **cell sizes are not known until after data generation.** The rule as written creates

$$\text{generate data} \to \text{inspect cell sizes} \to \text{drop stratifier} \to \text{analyse}$$

**The design would then have depended on generated data**, which violates the frozen protocol even
though no analysis had run.

$$\boxed{\text{All declared stratifiers remain fixed once pre-registered. No stratifier may be dropped after data generation. Sparse cells are reported as sparse and do NOT trigger redesign.}}$$

`[REC]` Sparse strata are reported **with their uncertainty**, and a stratum-level estimate resting
on a thin cell is labelled as such — **never removed.**

---

## Control `O-G` — phrasing corrected

$$\left|P(M_i) - P(\mathcal P_s)\right| \le 0.005 \;\Longrightarrow\; \textbf{reported as a stratum artifact under } O\text{-}G$$

⚠️ **Not** *"is an artifact."* The coincidence **triggers a protocol classification**; it does not
establish a causal explanation. `[REC]` The distinction is kept in the results wording.

---

## Summary — all still OPEN

| # | decision | recommendation |
|---|---|---|
| 1 | primary estimand | **`A5`**, with the rationale stated at diagnostic strength only |
| 2 | effect floor | **RESOLVED** — floor attaches to $\Delta_{\text{loss}}$ (true-cause vs decoy); `A5` reported without a floor; **no $\theta_0$ invented** |
| 3 | winner threshold | 0.60 within $\mathcal P_{\text{det}}$; **expect no winner** |
| 4 | second standard $S'$ | $\tau_m = 2$; **framed as changing stratum COMPOSITION**; prediction decomposed into total shift + intersection phenotype |
| 5 | structural strata | common-cause rule → graph size + root depth; coverage EXCLUDED as mediator; **NO post-generation dropping — sparse cells reported as sparse** |

**Theory v1.2 FROZEN · Kernel UNTOUCHED · `FR-004` CANDIDATE · `P1`–`P3` UNTOUCHED · Zoom-out UNDEFINED · `KR-ZOOM-OUT-02` NOT AUTHORIZED.**
