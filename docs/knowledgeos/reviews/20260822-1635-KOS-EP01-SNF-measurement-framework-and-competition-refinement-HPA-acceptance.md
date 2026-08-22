# KnowledgeOS — SNF measurement framework + mechanism competition refined: Coverage · Risk · Calibration · 2×2 abstention taxonomy · Pareto — HPA acceptance (2026-08-22)

> **Source:** Human Principal Architect (HPA), 2026-08-22 — recorded **verbatim-in-substance**. The HPA's message is the authoritative act; this instrument is the chain's record of it.
> **Position:** after the **SNF classification/freeze** (instrument `20260822-1621`, commit `bcdf6053`) and after reading the **uploaded research** — the Senior Mathematical Review of the SNF measurement framework (`docs/knowledgeos/brainstorming/20260822-154923-snf-measurement-framework-mathematical-review.md`, untracked, main checkout; status **ARCHITECTURALLY SOUND — MATHEMATICALLY UNDERSPECIFIED**). The HPA now **keeps the strategic conclusion** (compare mechanisms under one invariant measurement framework, not "find the best formula"), **corrects the premature ranking** (`C > E > B > A > D` = pre-experiment **prior**, not a measured result · **not** a convex optimization problem), and **extends the measurement framework** (Coverage · Conditional Risk · Calibration · 2×2 abstention taxonomy · risk–coverage curves · Pareto analysis · adversarial testing).
> **One sentence (HPA):** *"Which semantic mechanism provides the best trade-off between semantic convergence, non-collapse, transformation stability, coverage, and calibrated abstention—while minimizing false acceptance?"*
> **Status:** ✅ **REFINEMENT RECORDED — all research-design, all GATED, nothing authorized.** Measurement vector **EXTENDED** to `M = (C, NC, FC, T, K, R, A, H, Cal)` · abstention quality **redefined** as selective prediction (risk–coverage) — **Good Abstention ≠ High Abstention · Prudence ≠ Inability** · **Coverage K** added to prevent **abstention inflation** · **Calibration** added so Bayesian confidence ≠ correctness · the **2×2 matrix** (correct resolution · false acceptance · correct abstention · blind abstention) — false acceptance = the most dangerous error · SNF-C / SNF-E **demoted to hypotheses** (H-C1…H-C4 · arbitration hypothesis) · competition = **constrained multi-objective mechanism-selection** (hard constraints FC ≤ ε · FalseAccept ≤ ε; objectives C · T · K · Cal) with **Pareto analysis**, not a single winner · **disagreement-as-information** · **semantic adversarial testing** · research program **Port → Measurement → Mechanisms (A/B/C/D/E/F/G) → Benchmark → Pareto** · SNF = mechanism/interface, measurements describe its behaviour (`SNF(M) → vector`, not `SNF = 0.87`). **Architecture UNCHANGED — the mechanism competition stays OUTSIDE the kernel, behind the port.** Register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · v0.4 / OQ-4 **unauthorized** · freeze **holds**.**

---

## 0 · The act (HPA, in substance)

> *"Freeze the architecture → ratify the Port Contract → later reopen SNF research → add Coverage/Risk/Calibration to the existing measurement framework → run the A/B/C/D/E competition."*

The HPA **accepts the strategic conclusion** — the strongest part is not the ranking of SNF-C, but the move from *"find the best formula"* to *"compare competing semantic mechanisms under one invariant measurement framework."* The uploaded research supports that separation and keeps the formula frozen as a research candidate. Two refinements in one act:

1. **Keep the strategic conclusion** (the mechanism competition; the port is architectural; the formula stays frozen).
2. **Correct the proposed ranking and framing** — `P(M=C) > P(M=E) > P(M=B) > P(M=A) > P(M=D)` is a **pre-experiment prior**, not a scientific conclusion; and the problem is **not** a convex optimization over semantic space, but a **constrained multi-objective mechanism-selection problem**. What should be frozen is not "SNF-C wins" but *"the geometry of the problem is multi-objective, constraint-based, and abstention-aware."*

The HPA's recommendation stands: **do not modify the KnowledgeOS architecture because of this** — the current architectural separation is exactly what allows experimenting with these mechanisms without contaminating the kernel.

---

## 1 · What is kept (already recorded, now HPA-affirmed)

| Already recorded | HPA affirmation |
|---|---|
| **The mechanism competition framing** — SNF-A…E compared under one invariant measurement framework | **kept** — *"the strongest part is not the ranking of SNF-C. The strongest part is the move from 'find the best formula' to 'compare competing semantic mechanisms under one invariant measurement framework.'"* |
| **SNF formula = SNF Measurement Research Model v0.x** (research, not an architecture formula) | **kept** — the uploaded research *"says the formula should remain frozen as a research candidate."* |
| **The freeze** — current formula frozen, revisit after the port contract is ratified | **kept** — the freeze holds. |
| **The port is architectural, SNF is a mechanism** | **kept** — *"SNF remains a mechanism, the port remains architectural, and the mechanism competition comes later."* |
| **The architecture separation** (mechanism competition outside the kernel) | **kept** — *"I would not modify the KnowledgeOS architecture because of this."* |

---

## 2 · The correction: a constrained multi-objective mechanism-selection problem

The HPA **rejects** describing the problem as a *"convex optimization problem over semantic space."* We do not know the semantic-mechanism space is convex, continuous, differentiable, or even a vector space. SNF-A…G, LLM, and human annotation are fundamentally different **mechanisms**, not points on a known manifold. Formally:

```
M* ∈ M = {A, B, C, D, E, F, G}

each mechanism M produces a measurement vector:

Φ(M) = (C, NC, FC, T, K, R, A, H, Cal)
```

And a further distinction — **hard constraints ≠ optimization objectives**. Not all metrics are equal for KnowledgeOS:

- **Hard safety constraints** (constitutional): `FC < ε` and `FalseAccept < ε` — *false convergence is constitutionally dangerous* (consistent with r4 non-collapse gate · collision = evidence, never admission).
- **Objectives**: maximize `(C, T, K, Cal)` **subject to** those constraints.

```
max_M  (C, T, K, Cal)
  subject to  FC ≤ ε
              FalseAccept ≤ ε
              Identity(M(E₁), M(E₂)) is NEVER inferred merely from similarity
```

This is much closer to KnowledgeOS's constitutional character: a mechanism may not violate the non-collapse / no-false-acceptance safety rails regardless of its convergence.

---

## 3 · The measurement vector — extended (research altitude, gated)

The HPA's measurement framework now has **nine** dimensions (previously five):

```
M = (C, NC, FC, T, K, R, A, H, Cal)
```

| Dim | Name | Note |
|---|---|---|
| `C` | **Convergence** | equivalent expressions converge |
| `NC` | **Non-collapse** | different meanings remain different |
| `FC` | **False-collapse rate** | hard safety constraint — false convergence is constitutionally dangerous |
| `T` | **Transformation stability** | meaning survives legitimate transformations |
| `K` | **Coverage** | **ADDED** — cases where the mechanism produces an admissible candidate / total cases |
| `R` | **Conditional risk** | `P(wrong \| answered)` — the risk–coverage curve |
| `A` | **Abstention behaviour** | redefined via the 2×2 taxonomy (§6), not a threshold score |
| `H` | **Semantic uncertainty** | ambiguity remains visible |
| `Cal` | **Calibration** | **ADDED** — among cases reported at confidence p, is the mechanism correct ≈ p of the time? |

**The measurements remain separate** — consistent with the architecture's refusal of one giant composite score. This is the "Measurement Contract" phase of the research program (§12).

---

## 4 · Coverage K — the abstention-inflation guard

A mechanism can game the experiment by abstaining on difficult cases. The HPA's counter-example:

| Mechanism | Correct | Wrong | Abstain |
|---|---:|---:|---:|
| **A** | 900 | 100 | 0 |
| **C** | 700 | 20 | 280 |

C has a spectacularly low error rate — but it may simply be refusing to solve 28% of the corpus. Therefore:

```
K = cases where mechanism produces an admissible candidate / total cases
```

Then three mechanism profiles become distinguishable:

- **Conservative** — high abstention + high correctness when it answers.
- **Capable** — high coverage + high correctness.
- **Weak** — low coverage + mediocre correctness.

This prevents **abstention inflation**.

---

## 5 · Abstention quality — selective prediction, not a threshold score

Instead of `A_snf = (1/n) Σ I(H_sem > τ)`, measure **selective prediction quality**:

```
Risk(κ) = P(wrong | answered)      where κ is the coverage level
```

and construct a **risk–coverage curve**:

```
Error
  │
  │\
  │ \          A
  │  \______
  │
  │      C
  │       \____
  └──────────────────────── Coverage
       20  40  60  80  100%
```

Two distinctions carry the load:

```
Good Abstention  ≠  High Abstention
Prudence         ≠  Inability
```

The question answered is no longer *"C abstained 25% of the time"* but *"when C chooses to answer, how often is it wrong, and how much semantic coverage did it sacrifice to achieve that?"* — directly answering *"is abstention prudence or blindness?"*

---

## 6 · The most important experiment — a 2×2 matrix

Every case is classified on **two independent dimensions** — ground truth (human/reference assessment: unambiguous vs ambiguous) × mechanism behaviour (answers vs abstains):

| | Mechanism **answers** | Mechanism **abstains** |
|---|---|---|
| **Actually unambiguous** | **Correct resolution** | **Blind abstention** |
| **Actually ambiguous** | **False acceptance** | **Correct abstention** |

Four quantities:

1. **Correct resolution** — mechanism answers an unambiguous case correctly.
2. **False acceptance** — mechanism answers an ambiguous case. **"This is probably the most dangerous error for KnowledgeOS."**
3. **Correct abstention** — mechanism refuses an actually ambiguous case.
4. **Blind abstention** — mechanism refuses a case that another mechanism/reference can resolve.

This replaces one generic "abstention score" with a behaviour taxonomy — and makes **blind abstention** visible (the counterweight to the SNF-C structural-role argument in §9: a Pāṇinian/Kāraka mechanism may abstain on expressions outside its grammatical assumptions even when another mechanism can resolve them).

---

## 7 · Calibration — Bayesian confidence ≠ correctness

The mechanism's posterior is not evidence of correctness by itself. `P(Candidate₁|E) = 0.97` (SNF-C) vs `0.65` (SNF-A) does **not** mean C is correct — the Bayesian model itself needs calibration:

```
Among cases where the mechanism reports 0.9 confidence,
is it actually correct approximately 90% of the time?
```

Otherwise `P = 0.99` could mean *"the mechanism is very confident"* rather than *"the mechanism is very reliable."* Measurement: **calibration error** (consistent with the uploaded research's calibration vocabulary — calibration error · Brier score · log loss · reliability curves, and the SNF formula file's preference for precision/recall at a calibrated threshold).

---

## 8 · Disagreement as information

The mechanism ensemble's disagreement is itself an observable phenomenon — not a tie to break by majority:

```
       A ──┐
       B ──┤
       C ──┼──> Candidate interpretations
       D ──┤
       E ──┘
                │
                ▼
        Agreement structure
                │
        ┌───────┼────────┐
        ▼       ▼        ▼
    Agreement Partial  Conflict
       │       │        │
       ▼       ▼        ▼
    stronger  report   UNKNOWN
    candidate divergence
```

A mechanism-level **disagreement measurement** (e.g. `D_mech(E) = Entropy(M_A(E), …, M_G(E))` — recorded as a framing, not a final formula): low disagreement → candidate convergence; high disagreement → semantic uncertainty → **UNKNOWN / REVIEW**. The naive "4 against 1 → X wins" is rejected — instead, *why did D disagree?* This is much closer to the Gaṇeśa + Tarka + Zero principles.

**SNF-E = arbitration hypothesis** (disagreement-analysis mechanism), not a validated architecture — the uploaded research classifies hybrid-mechanism performance as requiring empirical validation.

---

## 9 · SNF-C and SNF-E become testable hypotheses, not conclusions

The HPA **does not** freeze SNF-C as the preferred mechanism, and **does not** accept `C > E > B > A > D` as a result. The uploaded research's conclusion is explicit: **no single semantic mechanism currently maximizes all criteria.**

**SNF-C** remains a *very interesting candidate* — the structural hypothesis (Kāraka relationships provide explicit relational constraints rather than relying primarily on similarity) is testable, but not yet an architectural fact:

| Hypothesis | Claim |
|---|---|
| **H-C1** | SNF-C has lower **false-acceptance** risk than SNF-A/B/D at equivalent coverage |
| **H-C2** | SNF-C has better **ambiguity detection** than SNF-A/B/D |
| **H-C3** | SNF-C has **lower transformation stability** outside its native linguistic assumptions — *"important because it could reveal its weakness"* |
| **H-C4** | SNF-E improves **coverage** while preserving the **false-acceptance** characteristics of SNF-C |

**SNF-E** = arbitration hypothesis (§8), pending empirical validation.

These are now **scientific hypotheses**, not architectural preferences — and they exist to be **falsified**.

---

## 10 · Semantic adversarial testing

Beyond "does A preserve meaning?", deliberately construct cases designed to **fool each mechanism**:

```
Same words          · different context
Same roles          · different identity
Same graph shape    · different temporal qualification
Same canonical form · different provenance
High similarity     · different meaning
Different syntax    · same meaning
```

Measured: `FC_adv` and `FalseAccept_adv`. The HPA: *"This is probably more valuable to KnowledgeOS than another 1% improvement in ordinary semantic parsing accuracy."*

---

## 11 · The mechanism competition — revised structure + Pareto analysis

```
                  ┌───────────────┐
                  │ Common Corpus │
                  └───────┬───────┘
                          │
       ┌──────────────────┼──────────────────┐
       │        │         │        │         │
       ▼        ▼         ▼        ▼         ▼
      SNF-A   SNF-B     SNF-C    SNF-D     SNF-E
       │        │         │        │         │
       └────────┴─────────┴────────┴─────────┘
                          │
                          ▼
              Common Port Contract
                          │
                          ▼
              Common Measurement
                          │
       ┌──────────────────┼────────────────────┐
       ▼                  ▼                    ▼
   Convergence       Non-collapse          Stability
       │                  │                    │
       ├──────────┬───────┴─────────┬──────────┤
       ▼          ▼                 ▼          ▼
   Coverage   False Collapse   Abstention   Uncertainty
                          │
                          ▼
                  Risk–Coverage Curve
```

The comparison is **not** "find the winner" but **find the Pareto frontier**:

```
False Collapse
     ↑
     │       A
     │
     │   B
     │
     │             C
     │       E
     │
     │                  F
     └────────────────────────→ Coverage
```

A mechanism is **Pareto-dominated** if another is at least as good on every important dimension and strictly better on at least one — a mathematically meaningful comparison without inventing arbitrary weights.

---

## 12 · The research program phases (future · gated)

Instead of "find the SNF formula":

```
Phase 1 — Port          define exactly what the Expression↔Meaning boundary guarantees
Phase 2 — Measurement   freeze Φ = (C, NC, FC, T, K, R, A, H, Cal)
Phase 3 — Mechanisms    implement experimental candidates:
                          SNF-A Symbolic · SNF-B Graph · SNF-C Kāraka · SNF-D SAIT/NSID
                          SNF-E Hybrid · SNF-F LLM · SNF-G Human
Phase 4 — Benchmark     same corpus · same transformations · same ground truth · same metrics
Phase 5 — Pareto        Pareto-frontier analysis, not a single winner
```

**SNF naming correction:** SNF should represent the **semantic normalization mechanism/interface**, while the measurements describe its behaviour:

```
SNF ≠ 0.87                     (there is no single SNF score)
SNF(M) → [C, NC, FC, T, K, R, A, H, Cal]ᵀ   (mechanism → measurement vector)
```

This preserves the **mechanism vs measurement-of-mechanism** distinction — exactly aligned with the uploaded research's warning against collapsing dimensions into one composite score.

---

## 13 · The final research question

> **"What semantic mechanism produces the safest and most useful behaviour under the KnowledgeOS constitutional constraints?"**

equivalently — *"which mechanism provides the best trade-off between semantic convergence, non-collapse, transformation stability, coverage, and calibrated abstention—while minimizing false acceptance?"* That is the research problem for the future SNF experiment.

---

## 14 · What this act does NOT do

It does **not** rank the mechanisms (`C > E > B > A > D` is a **prior**, explicitly not a result) · does **not** freeze SNF-C as preferred · does **not** authorize any SNF experiment or simulation (**v0.4** — Bayesian Calibration & Semantic Collision — and the A/B/C/D/E/F/G competition both remain **gated**) · does **not** change the frozen formula · does **not** define the final measurement formulas (§3–§7 record the HPA's framing, gated) · does **not** modify the KnowledgeOS architecture, the Port Contract, or Reference Architecture v1.1 · does **not** move the mechanism competition into the kernel (it stays behind the port) · does **not** change the register (**25+4 unchanged**) · does **not** touch the Constitution (**FROZEN**) · does **not** resolve OQ-2 · OQ-3 · OQ-5 (still the HPA's, in the review step) · does **not** dispose F-1…F-5.

---

## Chain state at this act

```
Research                                   CLOSED
Constitution v1.0                          FROZEN
Reference Architecture v1.1 r3 + r4        CONSOLIDATED · UNCHANGED
Expression↔Meaning Port Contract           DELIVERED — first Logical-Architecture deliverable
KOS Logical Architecture Review 01         DELIVERED — verdict PASS
SNF formula                                CLASSIFIED as SNF Measurement Research Model v0.x — FROZEN as a research candidate
Research framing                           REFINED — measurement vector M=(C,NC,FC,T,K,R,A,H,Cal) · risk–coverage · 2×2 abstention
                                           taxonomy · calibration · Pareto · adversarial testing · disagreement-as-information
  ⟶ SNF-C / SNF-E                         demoted from ranking to hypotheses (H-C1…H-C4 · arbitration hypothesis)
Competing mechanisms SNF-A…G               FRAMED for the later reopening — GATED, not authorized
KOS-SNF-ME v0.4                            NOT AUTHORIZED (gated behind the ratified boundary)
KOS-SCB v0.2 experiment (OQ-4)             NOT AUTHORIZED (unchanged)
OQ-1 … OQ-5 · F-1…F-5                     ruling the HPA's, in the review step
```

---

## Traceability

- **Act:** HPA refinement, 2026-08-22 — keep the strategic conclusion (mechanism competition under one invariant measurement framework); **correct** the premature ranking (`C > E > B > A > D` = pre-experiment **prior**, not a result) and the convex-optimization framing (**constrained multi-objective mechanism-selection**: hard constraints FC ≤ ε · FalseAccept ≤ ε; objectives C · T · K · Cal); **extend** the measurement vector to `M = (C, NC, FC, T, K, R, A, H, Cal)`; add **Coverage K** (abstention-inflation guard) · **Conditional Risk R** (`P(wrong|answered)`, risk–coverage curve) · **Calibration** (confidence ≠ correctness); **redefine** abstention quality via the **2×2 taxonomy** (correct resolution · false acceptance [most dangerous] · correct abstention · blind abstention); **demote** SNF-C / SNF-E to hypotheses (H-C1…H-C4 · arbitration hypothesis); **add** disagreement-as-information (`D_mech`), **semantic adversarial testing** (`FC_adv` · `FalseAccept_adv`), and **Pareto-frontier analysis**; state the research program (**Port → Measurement → Mechanisms SNF-A/B/C/D/E/F/G → Benchmark → Pareto**) and the SNF naming correction (`SNF(M) → vector`, not `SNF = 0.87`). Freeze holds · architecture unchanged · recorded verbatim-in-substance above.
- **Objects:** the prior classification/freeze instrument (`20260822-1621`) · the uploaded research — Senior Mathematical Review (`docs/knowledgeos/brainstorming/20260822-154923-snf-measurement-framework-mathematical-review.md`, untracked, main checkout — mechanism/port separation · collision as HARD GATE / safety objective (`CR_collision → 0`) · v0.4 as Bayesian Calibration & Semantic Collision Experiment · calibration vocabulary (error · Brier · log loss · reliability curves)) · the SNF formula artifact (`# Semantic Normal Form (SNF) Formula: Re`, untracked, main checkout — calibration preferred over entropy thresholding; variants incl. hybrid) — **referenced, not evaluated** · the Expression↔Meaning Port Contract · LA Review 01 · Reference Architecture v1.1 r3+r4 (all unchanged).
- **Discipline honored:** the act is **recorded, not invented** · the ranking is recorded as a **prior, not a result** · the mechanism competition and measurement formulas remain **gated, not authorized** · the architecture is **unchanged** (the mechanism competition stays behind the port, never in the kernel) · nothing is promoted · the register stays **25+4 unchanged** · the Constitution stays **FROZEN** · the strongest statement never exceeds the evidence.
- **Status:** ✅ **REFINEMENT RECORDED — all research-design, all GATED.** Strategic conclusion **kept** · premature ranking **corrected to a prior** · convex framing **rejected** (constrained multi-objective) · measurement vector **extended** (C · NC · FC · T · K · R · A · H · Cal) · abstention quality **redefined** (Good Abstention ≠ High Abstention · Prudence ≠ Inability) · 2×2 taxonomy · calibration · disagreement-as-information · Pareto · adversarial testing · SNF-C/SNF-E as **hypotheses** · research program **Port → Measurement → Mechanisms A…G → Benchmark → Pareto** · **next step unchanged = the HPA's review** of the Port Contract + LA Review 01 (OQ-2 · OQ-3 · OQ-5 · F-1…F-5) · register **25+4 unchanged** · Constitution **FROZEN** · research **CLOSED** · v0.4 / OQ-4 **unauthorized** · SNF encoding (DEF-4) **undecided**.
