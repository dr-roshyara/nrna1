# Experiment ID Registry — v1.2 lane

**Purpose:** one canonical ID per experiment, collisions **eliminated** rather than documented.
**Rule:** an ID is never reused. A superseded ID survives as a **legacy alias**, never as a live name.

| canonical ID | scope | artifact | aliases |
|---|---|---|---|
| **`KR-CONTR-MODELS-2026-09`** | contradiction-**model** separation — *which of three models* | [`V-contr-experiment.md`](V-contr-experiment.md) | **`KR-CONTR-2026-09` (legacy)** |
| **`KR-CONTR-EVAL-2026-09`** | **structured evaluation / adequacy** — *what evaluation structure is minimally required* | [`W-KR-CONTR-EVAL-2026-09.md`](W-KR-CONTR-EVAL-2026-09.md) | `KR-CONTR-2026-09` (as issued in the commissioning protocol) |
| `KR-HILBERT-2026-09` | spectral representation | [`T-hilbert-space-representation.md`](T-hilbert-space-representation.md) | — |
| `KR-DIST-2026-09` | distinguishability → **`FR-001`** | [`R-distinguishability-and-effective-complexity.md`](R-distinguishability-and-effective-complexity.md) | — |
| `KR-NEFF-2026-09` | effective hypothesis complexity | [`Q-effective-hypothesis-complexity.md`](Q-effective-hypothesis-complexity.md) | — |
| `KR-M2O-2026-09` | candidate multiplicity, selection, channels | [`P-candidate-multiplicity-and-selection.md`](P-candidate-multiplicity-and-selection.md) | — |
| `KR-EXTREME-2026-09` | spectral structure & extreme-value multiplicity | **DESIGNED, NOT RUN** | — |
| **`KR-CONTR-FDE-2026-09`** | **representation-comparison harness** — Classical / K3 / FDE / Structured | `research/knowledgeos-sim/kos12/fde/` · verdict `results/fde/verdict.md` | — |
| **`KR-COMP-SEP-2026-09`** | **the separating witness** — eliminates `last-wins`, separates the remaining two | [`Y-KR-COMP-SEP-2026-09.md`](Y-KR-COMP-SEP-2026-09.md) | — |
| **`KR-COMP-2026-09`** | **composition × frame qualification × standing aggregation** | [`X-KR-COMP-2026-09.md`](X-KR-COMP-2026-09.md) | — |

## Provenance graph

```
KR-CONTR-MODELS-2026-09  →  KR-CONTR-EVAL-2026-09  →  KR-CONTR-FDE-2026-09  →  KR-COMP-2026-09
   model separation            structured evaluation       representation harness    composition
   (Experiment G)              (programme protocol)        (FDE mapping directive)   (RUN)
                                                                                    ↓
                                                                          KR-COMP-SEP-2026-09
                                                                          the separating witness
```

## ⚠️ A naming conflict inside the review, preserved

Its **§9** proposed `KR-CONTR-EVAL-2026-09` *"if your naming convention permits a scope suffix"*; its
**§19** proposed keeping the bare `KR-CONTR-2026-09`. **The convention does permit a suffix**, and §9
itself observes that a suffix **eliminates** the collision where keeping the bare ID merely
**documents** it. The suffixed form is used. **Recorded so the choice is visible and reversible.**

## Why `KR-CONTR-2026-09` is retired as a live ID

It was issued twice on 2026-09-02: once by Experiment G's narrower commission, once by the research
programme's 2 184-line protocol. **Both uses were legitimate; neither is at fault.** Rather than let a
reader guess which artifact a citation means, the bare ID is **retired**. Both experiments keep their
original provenance — nothing in either artifact's history is rewritten — and each now carries a
scope-suffixed canonical ID.

**Any prior citation of `KR-CONTR-2026-09` is ambiguous and must be re-resolved against this table.**

---

## Zero-algebra lane — IDs registered 2026-09-04

The table above covered the contradiction/composition lane only. The zero-algebra lane ran
in parallel and was **unregistered**, which is exactly the collision risk this registry exists
to prevent. Its IDs are recorded here. *(Nothing above is altered; this is an addition.)*

| canonical ID | scope | artifact | aliases |
|---|---|---|---|
| `KR-ZERO-ALGEBRA-2026-09` | Zero under composition — algebraic structure | `verification/zero-algebra/KR-ZERO-ALGEBRA-2026-09/` | — |
| `KR-ZERO-GROUP-2026-09` | group-elimination Zero | `verification/zero-algebra/KR-ZERO-GROUP-2026-09/` | — |
| `KR-ZERO-ORDER-2026-09` | order sensitivity of Zero | `verification/zero-algebra/KR-ZERO-ORDER-2026-09/` | — |
| `KR-REP-REDUCTION-2026-09` | representation reduction under preservation — boundary `R5 → R4` | `verification/zero-algebra/KR-REP-REDUCTION-2026-09/` | — |
| `KR-BRIDGE-01-ZERO-PRESERVATION-2026-09` | is Zero related to preservation? **one Π, one Q** → OUTCOME A | `verification/zero-algebra/KR-BRIDGE-01-RESULTS-2026-09.md` | `KR-BRIDGE-01` (short form) |
| **`KR-BRIDGE-02-ZERO-PRESERVATION-PI-Q-2026-09`** | the same question with **Π and Q varied** (5 × 5, gated) → **H1 NOT REFUTED** | `verification/zero-algebra/KR-BRIDGE-02-RESULTS-2026-09.md` | `KR-BRIDGE-02` (short form) |
| **`KR-BRIDGE-03-GENERATOR-REGIME-2026-09`** | generator regime × informative-stratum power (9 regimes calibrated, 2 run) → **H1 NOT REFUTED at 13× power** | `verification/zero-algebra/KR-BRIDGE-03-RESULTS-2026-09.md` | `KR-BRIDGE-03` (short form) |
| **`KR-STATE-01-EPISTEMIC-STATE-STRUCTURE-2026-09`** | is the epistemic state multidimensional, and is present neutrality a safe deletion criterion? | `verification/zero-algebra/KR-STATE-01-DESIGN-2026-09.md` | `KR-STATE-01` (short form) · **`KR-STATE-TRANSITION-01` (corpus working title — NOT a separate experiment)** |
| **`KR-ZOOM-01-RECURSIVE-EPISTEMIC-ZOOM-2026-09`** | can an observed value become a new epistemic substrate at finer resolution? **RUN** — H1/H2/H4/H5 supported in tested regime; H3 inconclusive; H6 not supported; 7 degenerate metrics | `verification/zero-algebra/KR-ZOOM-01-2026-09/` | `KR-ZOOM-01` (short form) |
| **`KR-ZOOM-02-INQUIRY-ZOOM-2026-09`** | **inquiry-zoom**: focus without deletion — *inquiry focus ≠ knowledge boundary*. **RUN** — restriction plateaus at 7.9 % at any budget while inquiry reaches 100 % | `verification/zero-algebra/KR-ZOOM-02-2026-09/` | `KR-ZOOM-02` (short form) |
| **`KR-ZOOM-03-INQUIRY-ZERO-COUNTERFACTUAL-2026-09`** | does inquiry evidence change `Zero` status? — real `E^-` intervention. **RUN** — Δ = 0.058/0.060, **BORDERLINE, not claimed**; control `Z-D` failed; `H3c` degenerate; `P-Z3` confirmed | `verification/zero-algebra/KR-ZOOM-03-2026-09/` · pre-reg `KR-ZOOM-03-PREREGISTRATION-2026-09.md` | `KR-ZOOM-03` (short form) |
| **`KR-ZOOM-OUT-03-CALIBRATED-CONTEXT-RETURN-2026-09`** | zoom-out re-run on a grid built from **measured-sensitive** generator parameters. **FROZEN · GATE MET · EXECUTED 2026-09-05.** Primary Δ_loss **`[NEG]` NON-IDENTIFIABLE / DEGENERATE** — not "negligible" (`A5` = 0 exactly). **`M1` artifact ELIMINATED by stratification (0.000)**; `M0` 0.497/0.520 replicates as a *descriptive* phenotype; **no single winner**; `FR-004` prediction **not met**; **PRINCIPAL RESULT: `O-F` insufficient — class reachability ⇏ estimand variability.** Repair `O-F*` specified and retrospectively validated (`METHODOLOGY-2026-09/`): **it FAILS on this experiment — it would not have been authorized** | `verification/zero-algebra/KR-ZOOM-OUT-03-PREREGISTRATION-DRAFT-2026-09.md` · `KR-ZOOM-OUT-03-2026-09/` | `KR-ZOOM-OUT-03` (short form) |
| **`KR-ZOOM-OUT-02-STRATIFIED-CONTEXT-RETURN-2026-09`** | stratified re-run of the zoom-out question. **FROZEN 2026-09-05 · STOPPED BY ITS OWN CALIBRATION GATE** — base `Determine(Q_broad)` in [0.24667, 0.2875] at all 27 grid points vs the [0.30, 0.80] band. Protocol unmodified and reusable | `verification/zero-algebra/KR-ZOOM-OUT-02-PREREGISTRATION-2026-09.md` · `KR-ZOOM-OUT-02-2026-09/` | `KR-ZOOM-OUT-02` (short form) |
| **`KR-ZOOM-OUT-01-CONTEXT-RETURN-2026-09`** | what does Zoom-**out** do? **five** rival meanings (loss / restoration / abstraction / integration / revision) plus a first-class `MX`, to be **discriminated, not assumed**. **RUN 2026-09-05** — `E5` = 0.067/0.068 **BORDERLINE**; `M1` passed the 0.60 rule and was **WITHDRAWN as an artifact**; **calibration gate FAILED** (0.294/0.265) · **STATUS: FAILED AS PRIMARY CONFIRMATORY / DIAGNOSTICALLY INFORMATIVE** (ratified) | `verification/zero-algebra/KR-ZOOM-OUT-01-2026-09/` · design + pre-reg alongside | `KR-ZOOM-OUT-01` (short form) |

```
KR-ZERO-ALGEBRA → KR-ZERO-GROUP → KR-ZERO-ORDER → KR-REP-REDUCTION
                                                        ↓
                                        KR-BRIDGE-01  (Π, Q fixed)   OUTCOME A
                                                        ↓
                                        KR-BRIDGE-02  (Π × Q varied) H1 NOT REFUTED
                                                        ↓
                                        KR-BRIDGE-03  (generator regime) H1 NOT REFUTED, 13× power
                                                        ↓
                                        KR-STATE-01   (state structure)  DESIGN ONLY — not run
                                                        ↓
                                        KR-ZOOM-01    (recursive zoom)   RUN · audited · non-adjudicated
                                                        ↓  operator misidentified (owner correction)
                                        KR-ZOOM-02    (inquiry zoom)     RUN · non-adjudicated
                                                        ↓  H3 well-posed at last
                                        KR-ZOOM-03    (Zero counterfactual) RUN · BORDERLINE · non-adjudicated
                                                        ↓  zoom-out is a NEW question
                                        KR-ZOOM-OUT-01 (context return)     RUN · BORDERLINE · non-adjudicated
```

**Zoom-out scope note.** `KR-ZOOM-01/02/03` studied **Zoom-IN as inquiry/focus**. **Nothing about
Zoom-out is established by them.** `KR-ZOOM-OUT-01` treats it as a new research question with three
rival meanings to be discriminated. A paired `KR-ZOOM-INOUT-01` lifecycle experiment is a separate,
later artifact and is **not** designed yet.

**`KR-ZOOM-01` operator amendment (2026-09-04).** `KR-ZOOM-01` implemented **restriction-zoom
(descent)**, not inquiry-zoom: it made the anchor the information boundary. Its numbers stand;
their **scope** is corrected to descent operators (`KR-ZOOM-01-2026-09/RESULTS.md` §9).
`KR-ZOOM-02` implements the corrected operator.

**`KR-STATE-01` naming note.** The corpus carries the working titles `KR-STATE-01` and
`KR-STATE-TRANSITION-01` for what is **one** experiment with two hypothesis families (Q-A
structure, Q-B retention). The collision is **eliminated here rather than documented**: the
canonical ID is `KR-STATE-01-EPISTEMIC-STATE-STRUCTURE-2026-09`; `KR-STATE-TRANSITION-01` is a
**legacy alias and never a live name.**

**Governance unchanged:** `KR-ZERO ⊥ KR-REP-REDUCTION` · `EXPERIMENT → AUDIT → ADJUDICATION →
THEORY v1.3` · Theory **v1.2 FROZEN** · **kernel NOT SELECTED**.
