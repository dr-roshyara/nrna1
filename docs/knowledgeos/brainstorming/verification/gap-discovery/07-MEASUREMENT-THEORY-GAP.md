# 07 — Measurement Theory Gap

**Mandate §12.** Executed evidence: `exec/exp_measurement.py` (transcript: `exec/OUT-measurement.txt`).

Reference standard: Roberts, *Measurement Theory* — present in the corpus at
`kernel/20260825-184234`. A numerical statement is **meaningful** iff its truth value is invariant
under every admissible transformation of the scale.

---

## 1. The quantity inventory

Every quantitative quantity this session found in the corpus, with the mandate's required columns.

| Quantity | What is measured | Scale type | Admissible ops | Unit | Dimension | Value space | Uncertainty | Estimand | Estimator | Probability model |
|---|---|---|---|---|---|---|---|---|---|---|
| `σ` epistemic status | epistemic standing | **ordinal** (a ladder) | order, min, max, median | none | none | finite enum | — | — | — | **none** |
| `EvidenceStrength s` | support of one item | **declared** `[0,1]`; **actual** ordinal | unstated | none | none | `[0,1]` | none | undefined | undefined | **none** |
| `AggregateSupport` | support of a set | undeclared | — | inherits `s` | — | `[0,∞)` | none | undefined | a chosen formula | **none** |
| `IndependenceFactor` | "independence" | undeclared | — | none | none | `(0,1]` | none | statistical independence | `1/(1+depth)` | **none** |
| `Confidence` | belief | **declared** `[0,1]` | treated as ratio | none | none | `[0,1]` | none | undefined | undefined | **none** |
| `Relevance` | fit to a proposition | undeclared | — | none | none | `[0,1]` | none | undefined | **no decision procedure** (Step 266 class C) | **none** |
| `Zero / gap count` | unmet requirements | **ordinal at best** | count is nominal-aggregation | none | none | `ℕ` | none | — | — | **none** |
| `Distance d(K₁,K₂)` | state difference | **regime-relative** (correctly noted 2026-08-25) | depends on `d` | none | none | `[0,∞]` | none | — | — | only under a declared regime |

**The `Probability model` column is empty for every row.** This is not an omission in my table; it
is the state of the corpus.

---

## 2. Where the probability triple is — and is not

Grep over the whole primary corpus: 179 files mention *probability*; 16 mention *probability space*.
Of the six terminal steps (262–267), **exactly two** mention it, and both mention it to say it is
**absent**:

- Step 264 §264.17: *"That claim was explicitly contested in the audit because no underlying
  probability space was established in the corpus."*
- Step 266 §266.25: *"We need a probability space `(Ω,𝓕,P)`. Without that, the number may be merely
  an assigned confidence score."*

**MT-1 (`CORPUS ESTABLISHES`).** The corpus knows it has no `(Ω,𝓕,P)`. The only place one was ever
declared is the measure-theory regime of 2026-08-25, which was then externalized (ARC A).

**MT-2 (`EXECUTED`) — the consequence, which the corpus does not draw.** Without a shared sample
space, per-assertion confidences are not probabilities and need not be coherent:

```
conf(Nexus.version = 3.69) = 0.8
conf(Nexus.version = 3.70) = 0.7      (mutually exclusive values of one dimension)
sum = 1.5      -- exceeds 1
```

Nothing in the theory or in the running system forbids this. `uncertainty ≠ probability` is not a
caution to be repeated; it is a **live defect**: the model admits incoherent belief assignments over
mutually exclusive values of the same dimension, and no invariant rules them out.

---

## 3. EXECUTED: the ordinal-arithmetic violation

Step 264 §264.23 states the rule correctly — *"no averaging of ordinal epistemic strength"*. This
session tested whether the corpus's *own decision rules* obey it.

```
ladder: Candidate < Supported < Accepted        portfolio: [Candidate, Candidate, Accepted]
rule under test:  mean(σ) >= 2  ->  PROCEED

encoding A {1, 2,  3}:  mean=1.667  PROCEED=False
encoding B {1, 2, 10}:  mean=4.000  PROCEED=True
encoding C {0, 5,  6}:  mean=2.000  PROCEED=True

decision flips across admissible re-encodings? True
```

All three encodings are **strictly increasing**, hence all three are admissible for an ordinal scale.
The decision flips. Controls that *are* admissible — `min`, `max`, `median` — are invariant across all
three.

**MT-3 (`EXECUTED`, CRITICAL).** Any KnowledgeOS rule of the form *"the portfolio is ready if average
status ≥ X"*, *"confidence-weighted support exceeds a threshold"*, or *"95 % of gates passed"* is
**meaningless** in Roberts' precise sense. Step 264 forbids exactly this and the corpus never audits
the rest of itself for it. The pre-existing `ladder_dc_reference.py` found the same class of defect
from the Decision-Contract side (*"a 95 %-admissible decision is inadmissible"*, `no-averaging
(42.10)`), which is the same rule applied to conjunction rather than to averaging.

---

## 4. EXECUTED: the two named formulas are not measurements

### `AggregateSupport = Σsᵢ / (1 + log n)` (Step 270 §270.28)

```
 1 item  @1.0  ->   1.0000
 2 items @1.0  ->   1.1812
10 items @1.0  ->   3.0279
100 items@1.0  ->  17.8407
```

- **Unbounded.** 100 unit-strength items yield 17.84. Whatever that is, it is not a degree of support.
- **Not idempotent.** Duplicating the *same* evidence item raises support by **18.1 %**. Evidence
  duplication is precisely what the corpus's dependency-normalization work (`025c-2`, `025c-3`,
  `exp01_recheck.py`) exists to prevent, and this formula rewards it.
- **Dimensionally undefined.** `Σsᵢ` carries the unit of `s`; `log n` is dimensionless; the quotient
  has no interpretation as a probability, a likelihood ratio, or a degree of belief.
- It is a **plurality discount**, not an aggregation rule.

### `IndependenceFactor = 1/(1 + depth)` (Step 270 §270.1)

```
e1  vendor API,               depth 0 -> 1.000
e2  a mirror that VERBATIM COPIES e1, depth 1 -> 0.500
e3  an INDEPENDENT scanner,   depth 1 -> 0.500
total "independent weight" of {e1,e2} = 1.500   (true independent observations: 1)
```

**MT-4 (`REFUTED`, HIGH).** The formula assigns a verbatim copy and a genuinely independent
observation **the same weight**, because depth is a graph property and independence is a statistical
one. It is a path-length discount. Naming it *independence* is a category error — Step 270 predicts
this in prose; this executes the demonstration.

Both formulas remain perfectly usable as **declared engineering heuristics**. Neither is a
measurement, and neither may appear in a claim about epistemic quantity.

---

## 5. What survives

Not everything fails. Three quantitative moves in the corpus are sound and should be kept:

1. **The scale-type discipline itself** (Step 264 §§264.3–264.10, 264.20–264.23). It is correctly
   stated, correctly derived from Roberts, and it is what makes MT-3 findable.
2. **`Distance` is regime-relative** (`kernel/20260825-181719` §9): TV / Wasserstein / JS / KL are not
   interchangeable; `d_R(Π_A, Π_B)` must carry its regime. Correct, and never violated afterwards
   because distance was never used again.
3. **The refusal to define a single "knowledge score"** — maintained from `kernel/20260825-181038` §19
   through Step 264. `CORPUS ESTABLISHES`, and the corpus deserves credit for holding this line for
   six days under repeated temptation.

---

## 6. The measurement question the corpus never asked

Roberts' framework is `EmpiricalStructure → Representation → Scale → MeaningfulOperations`. The
corpus imports the *third and fourth* stages (scale types, admissible transformations) and never does
the **first**.

**MT-5 (`UNRESOLVED`, CRITICAL).** No empirical relational structure is ever specified for any
epistemic quantity. For `σ` that would mean: *what is the observable relation `a ≿ b` ("a is at least
as well supported as b") and does it satisfy the axioms (weak order, and for higher scales
solvability/Archimedean) that license a numerical representation at all?*

Without that, the scale-type discussion is **assigning a scale type to a quantity that has not been
shown to be measurable in the first place**. This is the deepest measurement gap, and it is
upstream of everything in §§3–4: `MT-3` shows the ladder is at most ordinal; `MT-5` observes that
even ordinality has not been *established*, only assumed.

This is also, precisely, the gap the corpus's day-one critique warned about
(`kernel/20260825-184755` §12): *"Roberts answers **when is a numerical quantity legitimately
measurable?**"* — and the representation question was never returned to.

---

## 7. Findings

| ID | Finding | Class | Severity |
|---|---|---|---|
| **MT-1** | No probability space `(Ω,𝓕,P)` is declared anywhere for any epistemic quantity; the corpus states this itself in Steps 264 and 266. | `CORPUS ESTABLISHES` | HIGH |
| **MT-2** | Consequence, executed: per-assertion confidences over mutually exclusive values of one dimension can sum above 1 and no invariant forbids it. `uncertainty ≠ probability` is a live defect, not a caution. | `EXECUTED` | HIGH |
| **MT-3** | Averaging over the ordinal status ladder flips the decision across three admissible re-encodings. Any average/percentage/weighted-threshold rule over `σ` is meaningless in Roberts' sense. | `EXECUTED` | **CRITICAL** |
| **MT-4** | `AggregateSupport` is unbounded, non-idempotent (rewards duplicate evidence by 18.1 %) and dimensionally undefined. `IndependenceFactor` gives a verbatim copy the same weight as an independent observation. Neither is a measurement. | `REFUTED` | HIGH |
| **MT-5** | No empirical relational structure is specified for any epistemic quantity. Roberts' representation stage — *is this measurable at all?* — was imported as vocabulary and never executed. | `UNRESOLVED` | **CRITICAL** |
| **MT-6** | *Positive:* scale-type discipline, regime-relative distance, and the six-day refusal to define a single knowledge score are sound and should be preserved. | `CORPUS ESTABLISHES` | — |

---

**Next:** `08-EVIDENCE-GAP.md`.
