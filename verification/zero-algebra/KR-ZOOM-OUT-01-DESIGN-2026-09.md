# `KR-ZOOM-OUT-01` — **DESIGN**
## What does Zoom-**out** actually do? FIVE rival meanings — to be discriminated, or to fail

**Experiment ID:** `KR-ZOOM-OUT-01-CONTEXT-RETURN-2026-09`
**Status:** `[DESIGN]` — **NOT RUN, NOT AUTHORIZED, NOT PRE-REGISTERED.**
**Date:** 2026-09-05 · Theory **v1.2 FROZEN** · kernel NOT SELECTED · no carrier declared

> ### ⚠️ **NOTHING about Zoom-out is established by `KR-ZOOM-01/02/03`.**
> Those experiments studied **Zoom-in as inquiry/focus**. Zoom-out is a **new research question**
> and is treated as one here. **No definition of Zoom-out is added to the theory by this design.**

---

## 0. The canonical scenario — **Nexus 70 GB/day → GitLab Runner**

```
Whole Nexus state
      │  Observe
      ▼
"Egress = 70 GB/day"          ← a SYSTEM-LEVEL observation
      │  ZOOM IN  (resolution of the INQUIRY rises; context is retained)
      ▼
GitLab Runner · repositories · CI/CD · backup · network · configuration · …
      │  Explore — evidence accumulates wherever it leads
      ▼
Determine: "GitLab Runner is causing the unusual usage"
      │  ZOOM OUT
      ▼
Whole Nexus state, UPDATED with the new knowledge
```

> ### `[REC]` **Zoom-in is NOT graph traversal into the `egress` node.**
> It is: **increase the resolution of the inquiry while preserving the surrounding epistemic
> context.** The answer turned out to live in CI/CD — **and that could not be known in advance**,
> so the investigation had to be able to leave the observation's own dimension.
>
> *(That property is already `[EXP]`: `KR-ZOOM-02` measured cross-ontology discovery at 23–26 %.
> It is not re-litigated here.)*

> ### ⚠️ **The Nexus/GitLab Runner scenario is a MOTIVATING SCENARIO, never a definition.**
> The experiment must remain capable of returning `M1`, `M2`, `M3a`, `M3b`, **information loss**,
> **or a result showing that none of them adequately describes Zoom-out.** Forcing the phenomenon
> into a box because the scenario is vivid is the failure this note exists to prevent.

$$\boxed{ZoomOut(\text{focused investigation}) \;\neq\; Restore(\text{old state})}$$
$$ZoomOut(K_t, Q, E_{\text{new}}) \to K_{t+1}$$

---

## 1. The asymmetry that motivates it

$$\boxed{\text{Zoom-in changes the inquiry scale} \qquad \text{Zoom-out restores the broader inquiry context}}$$

**Neither should inherently mean deletion** — that was the lesson of the `KR-ZOOM-01` amendment.
But *"restores"* is doing unexamined work, and it hides at least three different operations.

---

## 2. The rival meanings — **hypotheses, not a definition**

$$K_t \xrightarrow{\;\text{zoom-in inquiry}\;} K_t' \xrightarrow{\;\text{zoom-out}\;} K_{t+1}$$

### 2.1 ⚠️ The comparison relation is **observable availability**, not set-theoretic

**Correction 1, 2026-09-05.** The first draft compared states by literal set inclusion
$K_t \subseteq K_{t+1}$. **That would decide the Knowledge-State carrier inside a Zoom-out
experiment** — and the carrier is `OQ-1`, **open**.

**Correction 2, 2026-09-05 (owner).** The replacement first said *"$o$ determinate on $K$"*, which
is dangerous: it could be read as requiring the **same answer**, or as invoking the still-open
determination semantics. Replaced by an explicit **availability** predicate.

$$\mathrm{Avail}(o, K) \;\equiv\; o \text{ can still be EVALUATED on } K \text{ under the declared contract}$$
$$\mathrm{Value}(o, K) \;\equiv\; \text{the value returned, when } \mathrm{Avail}(o,K)$$

$$\boxed{\;K \preceq_{\mathcal O} K' \iff \forall o \in \mathcal O:\; \mathrm{Avail}(o,K) \Rightarrow \mathrm{Avail}(o,K')\;}$$

$$K \cong_{\mathcal O} K' \iff K \preceq_{\mathcal O} K' \;\wedge\; K' \preceq_{\mathcal O} K \;\wedge\; \forall o \text{ with } \mathrm{Avail}(o,K):\; \mathrm{Value}(o,K) = \mathrm{Value}(o,K')$$

> ### `[REC]` **Answerability and answer are measured SEPARATELY.**
> $\preceq_{\mathcal O}$ constrains **only** whether the question can still be asked.
> $\mathrm{Value}$ is reported independently, because **preserving answerability does not mean
> preserving the same answer** — revision (`M3b`) is precisely the case where the question stays
> askable and the answer changes. **Collapsing the two would make `M3b` unobservable.**

**No claim about the carrier is made or needed.**

### 2.2 The categories — **five, not three**

The instruction *"lower detail count does not necessarily mean abstraction — it may be information
loss"* forces a category the first draft did not have. **Abstraction and loss both reduce detail;
they differ in whether the relevant distinctions survive.**

| | meaning | $\preceq_{\mathcal O}$ | $\cong_{\mathcal O}$ | detail | $\Delta^\circ$ |
|---|---|---|---|---|---|
| **M0** | **INFORMATION LOSS** — answerability destroyed | **fails** | — | any | — |
| **M1** | **restoration** — return to the broader state | holds | **yes** | equal | ∅ |
| **M2** | **abstraction** — detail replaced, distinctions kept | holds | no | **falls** | — |
| **M3a** | **integration** — the determination folded back in | holds | no | rises | **∅** |
| **M3b** | **revision** — existing understanding changed | holds | no | rises | **≠ ∅** |
| **MX** | **MIXED / NONE** — no category fits, or several do | — | — | — | — |

`[REC]` **Detail count is a DIAGNOSTIC, never a definition.** It is consulted **only inside the
$\preceq_{\mathcal O}$-preserving branch**, to separate `M2` from `M3`. Outside that branch a
falling detail count is `M0`, not abstraction. This is the corpus's own finding applied to itself:

$$\boxed{\text{representation size} \;\neq\; \text{semantic adequacy}}$$

`$\Delta^\circ$` is `KR-STATE-01`'s transition triple $\Delta_t = (\Delta^-,\Delta^\circ,\Delta^+)$
— reused, not reinvented (`ES-005.4`). Its $\Delta^\circ$ component *is* "transformed or qualified
material", which is exactly the `M3a`/`M3b` discriminator.

### 2.3 Two boundaries stated explicitly

$$\boxed{\text{Zoom-out} \;\neq\; \text{representation reduction}} \qquad \boxed{\text{Zoom-out} \;\neq\; \text{knowledge deletion}}$$

Without these, `M2` silently degrades into *"whatever has fewer fields"* — and the experiment
would rediscover `KR-REP-REDUCTION` under a new name.

**The real question:** *what happens to the broader epistemic state after a focused investigation
has produced new evidence or a determination?*

### 2.4 `MX` is a first-class outcome

`[REC]` **`MX` — "none of the categories adequately describes Zoom-out" — is a permitted and
reportable result**, not a residual bucket. A high `MX` share would be a **more interesting**
finding than a clean winner, and the design must not be able to avoid it.

---

## 3. The eight questions, made measurable

| # | question | measurement |
|---|---|---|
| **Q1** | Does zoom-in preserve the broader context? | $K_t \subseteq K_t'$ ⚠️ **expected definitional** under the `KR-ZOOM-02` operator — label it |
| **Q2** | Can investigation cross the initial focus boundary? | **already `[EXP]`** — 23–26 % (`KR-ZOOM-02`). **Not re-litigated.** |
| **Q3** | Does zoom-out preserve discoveries made during focus? | are the discovered dimensions present in $K_{t+1}$? |
| **Q4** | Is zoom-out merely navigation, or does it transform $K_t$? | the §2 witness. **Navigation ⟹ M1** |
| **Q5** | Does in→out recover the original context **plus** new knowledge? | $K_t \subsetneq K_{t+1}$ **and** the determination is retained |
| **Q6** | Does repeated in/out lose information? | **round-trip fidelity over $n$ cycles**: $\lvert K_0 \setminus K_n\rvert$ |
| **Q7** | Does the **order** of zooming change the resulting state? | order-swap over multiple anomalies — state **and** observable **separately** |
| **Q8** | **Can a focused investigation change a determination at the broader level?** | $\mathrm{Determine}(Q_{\text{broad}} \mid K_t)$ vs $\mathrm{Determine}(Q_{\text{broad}} \mid K_{t+1})$ |

> ### **Q8 is the most powerful question in the set** — and the one closest to the Nexus case. Investigating 70 GB/day of egress, and thereby changing what the organisation can conclude *about Nexus as a whole*, is a different and stronger claim than merely explaining the egress.

---

## 4. Pre-registration requirements — carried from `KR-ZOOM-03`'s failure

Three lessons, each of which cost a result:

### 4.1 `[REC]` **The estimand must be DIRECTED.**

`KR-ZOOM-03` froze an **undirected** flip rate. Decoy removal changed determination in *both*
directions roughly symmetrically (0.118 destroyed / 0.131 created), so the undirected count
**cancelled the signal** — Δ = 0.058, borderline, not claimed. The directed quantity was
+0.123 / +0.134 and would have cleared the floor. **It could not be adopted, because switching
estimand after seeing results is precisely what pre-registration forbids.**

**For `KR-ZOOM-OUT-01`, every estimand is declared with its direction before execution.**

### 4.2 `[REC]` **The attribution control must not be a proxy for the outcome.**

`KR-ZOOM-03`'s `H3c` returned **exactly** the base `Determine` rate (0.3395 / 0.3585) — degenerate
by construction, and the load-bearing control did nothing. **Any control here must be shown, in
advance, to be capable of taking a value different from the quantity it is controlling for.**

### 4.3 `[REC]` **Contract choice must be validated, not assumed.**

`P-Z3` was confirmed: the existence contract saturated at **1.0000** and would have produced a
false negative. **The `Determine` contract for `Q_broad` must pass a calibration gate before any
analysis**, exactly as in `KR-ZOOM-03`.

---

## 5. Controls declared in advance

| | | expected |
|---|---|---|
| **O-A** | zoom-out with **no** investigation between in and out | ⚠️ **not "exact equality"** — see §5.1 |
| **O-B** | zoom-out after an investigation that **determined nothing** | discoveries empty; $K_{t+1} = K_t$ |
| **O-C** | zoom-out after an investigation that determined a **decoy** | must be distinguishable from determining the true cause |
| **O-D** | $n$-cycle round trip with **no new evidence** | fidelity must stay **1.000**; any decay is a harness defect, not information loss |
| **O-E** | paired seeds across in/out orders | any divergence is the order, not the sample |
| **O-F** | **degenerate-metric pre-check** — for each metric, name a case that would give a different value | a metric with no such case is dropped **before** the run |

`O-F` is new, and it is the direct institutionalisation of the `H3c` failure.

### 5.1 ⚠️ `O-A` must not test "exact equality"

**Correction, 2026-09-05.** A single equality test conflates four different things, and a
legitimate representation change would be misclassified as information loss. `O-A` therefore
reports **four equalities separately**, never collapsed:

| # | dimension | measured |
|---|---|---|
| **1** | **Structural** | identical representation |
| **2** | **Observable** | $K_t \cong_{\mathcal O} K_{t+1}$ — availability **and** value |
| **3** | **Contract-semantic** | the determinations agree under the frozen contract |
| **4** | **Historical / provenance** | $\Delta_t = (\varnothing, \varnothing, \varnothing)$ |

**Only dimensions 2 (Observable) and 3 (Contract-semantic) may participate in an `M0` verdict.** A
structural difference with observable equivalence is **not** information loss — that distinction
is the whole point of `\preceq_{\mathcal O}`.

---

## 6. What this experiment cannot do

- **Cannot** add a definition of Zoom-out to Theory v1.2. **The three meanings are rivals to be
  discriminated; the winner is an `[EXP]` finding about the tested regime, not a definition.**
- **Cannot** promote `P1`–`P3`, adopt the `Zoom` research definition, or touch the kernel.
- **Cannot** settle `OQ-1` (carrier) or `DECISION-02`.
- **Cannot** establish a **lifecycle** claim — the paired `KR-ZOOM-INOUT-01` is a **separate,
  later** experiment and is not designed here.

## 7. Governance

$$\text{EXPERIMENT} \to \text{AUDIT} \to \text{ADJUDICATION} \to \text{THEORY v1.3}$$

**DESIGN only.** Before it can run, and **none may be settled afterwards**: the three estimands
**with their directions**, the effect floor, the `Determine(Q_broad)` contract, and its calibration
band. A separate pre-registration artifact — as `KR-ZOOM-03` had — is **required**, not optional.
