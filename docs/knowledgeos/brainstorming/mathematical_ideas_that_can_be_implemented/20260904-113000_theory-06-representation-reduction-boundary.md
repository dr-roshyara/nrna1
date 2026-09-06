# Theory 06 — **The Representation-Reduction Boundary**

**Document 06 of 15** · 2026-09-04 · **Source:** `KR-REP-REDUCTION-2026-09` + `AUDIT-2026-09.md`

---

## 1. The result

40 000 cases per split, independent seeds, full population persisted. Seven falsification tests
(`FT-1..FT-7`) ran **before** any result and all passed. The positive control passed
($N_{\text{viol}} > 0$ at three levels), so the experiment was not blind.

| level | transformation | $F$ | $\hat H(Q\mid R)$ | $N_{\text{viol}}$ | adequate? |
|---|---|---|---|---|---|
| **`R5`** | drop timestamps | 1.00 | **0.0000** | **0** | **YES** |
| **`R4`** | round to 3 s.f. | 1.00 | 0.4299 | 35 532 | no |

> ### `[EXP]` Last adequate stage: **`R5`**. First inadequate: **`R4`**. Crossing transformation: **`T4` — rounding to 3 significant digits.**

`[EXP]` **TEST reproduces TRAIN closely** — `R4`: $\hat H$ 0.4408 vs 0.4299; $N_{\text{viol}}$
36 445 vs 35 532. The $\hat H(Q\mid R_5) = 0$ observation is **not** a one-sample artifact.

---

## 2. ⚠️ The scope bound — this is the discipline the result exists to teach

**We may NOT say:**

> ~~"Three significant digits is the universal preservation limit."~~

**We may say:**

> `[EXP]` **For this carrier, this inquiry $Q$, this transformation chain, this contract and
> this value domain, rounding to three significant digits crossed the observed preservation
> boundary.**

The carrier was $r = (\text{value},\ \text{source},\ \text{timestamp})$; the inquiry was
$Q = (\arg\max_{\text{source}},\ \mathrm{decile}(\text{total}))$. Change the decile width and
the boundary moves. Change $Q$ and it moves. **The boundary is a property of the frame, not of
the number 3.**

---

## 3. What the boundary is a boundary *of*

`[EXP]` It is a boundary of **adequacy** — of $\hat H(Q\mid R)$ crossing zero. It is:

- **not** a boundary of eliminability (§4),
- **not** a boundary of realization (document 05 — never exhibited),
- **not** a boundary of "size" (document 04 §4 — bytes were flat across it).

`[COR]` By the DPI corollary (document 04 §3), **the boundary is unique along the sequential
chain**: once crossed it cannot be re-crossed back into adequacy. This is a corollary, not an
observation — it follows from the theorem and does not need the data.

---

## 4. `[NEG]` `Zero` does not mark the boundary

Hypothesis `H-RR2`, tested directly:

| step | | cases | Zero rate |
|---|---|---|---|
| `R5` → `R4` | **the crossing** | 12 000 | **0.0377** |
| `R4` → `R3` | not a crossing | 12 000 | **0.0426** |
| `R3` → … | | | Zero vanishes entirely |

> `[NEG]` **The Zero rate at the boundary crossing is not distinguishable from the rate at a
> non-crossing step, and is in fact lower.** Eliminability carries no signal about where
> preservation fails.

This is the first of the four independent negatives that make up document 07.

---

## 5. The rank-convention finding, and the claim the audit withdrew

`[EXP]` A rank *direction* (ascending vs descending) carries **no information about $D$**. It
is a pure encoding convention.

The load-bearing question was whether the map between conventions is **invertible**. It was
tested rather than assumed, and:

> ### `[NEG]` **Rank conventions are NOT invertible.** $\mathrm{desc} \neq (n{+}1) - \mathrm{asc}$ when values tie — **21 369 violations**; the induced partitions differ.

`[NEG]` **Therefore this is NOT an instance of `DECISION-01`** (non-evidential invariance).
That reading required an invertible recoding, and the audit refuted invertibility. **The
headline built on it was withdrawn — both my wording and the review's replacement wording.**

`[EXP]` What survives: the sensitivity is *located precisely*. Adequacy is nearly invariant
across the convention change where realization is not, and the gap **measures the separation**
between them — which is the one place in the corpus where the adequacy/realization distinction
was visible at all, even though it was not exhibited in the clean form document 05 requires.

---

## 6. Falsification scorecard

| test | target | outcome |
|---|---|---|
| **A** | adequacy | **survives** — `R5` adequate, $N_{\text{viol}}(R_5) = 0$ on **both** splits |
| `H-RR1`/`H-A` | the boundary exists | **CONFIRMED** |
| `H-RR2` | Zero marks the boundary | **`[NEG]`** |
| `H-B` | adequacy/realization separation | **`[DEFECT]`** — design could not exhibit it |
| `H-C` | non-monotone adequacy | **structurally inapplicable** — not refuted |
| `H-D` | reduction is multi-dimensional | **CONFIRMED** |
| `H-E` | $\hat H(R) \ge \hat H(Q)$ | **holds at every level** |

---

## 7. Register

| Statement | Status |
|---|---|
| A preservation boundary exists in this chain | `[EXP]` |
| It lies at `R5 → R4` | `[EXP]` — **scope-bound to that carrier, $Q$, chain, contract, value domain** |
| "3 significant digits" is a universal limit | **`[NEG]`** — never claimed, explicitly forbidden |
| The boundary is unique along the sequential chain | **`[COR]`** of the DPI |
| `Zero` marks the boundary | **`[NEG]`** |
| Rank conventions are invertible recodings | **`[NEG]`** — 21 369 tie violations |
| The rank finding instantiates `DECISION-01` | **`[NEG]`** — withdrawn by audit |
| Reduction is multi-dimensional | `[EXP]` |
