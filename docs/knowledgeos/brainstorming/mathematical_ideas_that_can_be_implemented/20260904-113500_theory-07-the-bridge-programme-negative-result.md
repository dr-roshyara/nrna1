# Theory 07 — **The Bridge Programme: a Stable Negative**

**Document 07 of 15** · 2026-09-04
**Sources:** `KR-BRIDGE-01/02/03-RESULTS-2026-09.md` + `KR-BRIDGE-01-AUDIT-2026-09.md`

> Three experiments asked one question: **does `Zero` predict preservation?** The answer did not
> move under a 13-fold increase in power. This document records how the negative was hardened,
> because a negative is only worth what its methodology is worth.

---

## 1. The question

$$\text{Does } \mathrm{Zero}_{T,\Pi}(S;D) \text{ carry information about } \mathrm{Adequate}(T,Q)\,?$$

**H1** no bridge · **H2** a conditional bridge for some $(\Pi,Q)$ · **H3** if H2, `Zero` predicts
**in**adequacy where $T$ is redundancy-removing.

---

## 2. KR-BRIDGE-01 — one $\Pi$, one $Q$ · **OUTCOME A**

25 000 cases × 2 splits, 7 parallel transformations, $|S| \in \{1,2\}$, 6/6 controls passed.

The **marginal** association was large:

| | $P(A\mid Z)$ | $P(A\mid \neg Z)$ | RD |
|---|---|---|---|
| `T_C_dedup` | 0.1367 | 0.4281 | **−0.291** |
| pooled | 0.0684 | 0.4319 | **−0.363** |

**And it dissolved under stratification.**

> ### `[EXP]` Within the tested deduplication regime, **redundancy behaves as a common cause** of `Zero` occurrence and of inadequacy.
> *(Tightened on review: one informative stratum, one carrier, one $\Pi$, one $Q$, one principal
> Zero-producing transformation — the scope constraints forbid stating this as a general causal law.)*

`[DEFECT]` **Its audit found a label mismatch on `T_B`** — the pre-registered classification
"lossy" did not match observed behaviour — and a **single-informative-stratum limit**. Both were
recorded rather than repaired, per the rule that a pre-registered label is a *classification to
be tested*, never a definition to be adjusted after the fact.

`[NEG]` **A single-mechanism explanation was withdrawn**: the Zero-count is constant at $r=1$ but
**varies** at $r=2$ (6 or 8). The exact $RD = 0$ has **two different causes**, not one.

---

## 3. KR-BRIDGE-02 — 5 $\Pi$ × 5 $Q$ × 7 $T$ · **the instrument failed twice**

The point of this experiment is not its verdict. It is that **the experiment falsified its own
instrument twice before it was allowed to report anything.**

### 3.1 Wrong adjudication target

The runner first adjudicated on the **marginal** RD and returned *"H2 SUPPORTED, 27/27 cells
replicate."*

> `[NEG]` **That is a false positive by construction.** KR-BRIDGE-01 had already observed the
> marginal association and explained it as confounding by redundancy. Twenty-seven relabelled
> cells re-found the confounder, not a bridge.

`[REC]` **A conditional bridge requires $RD \ne 0$ *within* a stratum**, replicating
out-of-sample. Adjudication moved to the **Mantel–Haenszel** within-stratum RD.

### 3.2 The read-disjointness gate was unsound — and it mattered

> ### `[EXP]` The one substantial effect in the entire grid was an **artifact of shared reads**.

`Pi_value_set | Q_argmax_ntags | T_D_relational` carried $MH\text{-}RD = -0.155$, replicating on
test at $-0.152$ across 4 strata. **Inadmissible:** `T_D` ranks *within tag group*, so
$\mathrm{rank} = f(v, \mathrm{tag})$; $\Pi$ falls back to `rank`; $Q$ reads `tag`.

**Under the naive gate this experiment would have reported a conditional bridge.**

### 3.3 The corrected gate was *still* unsound

Found by **perturbation test, not by code review**: `T_C_dedup` selects survivors by `v`, so the
surviving `src`/`tag`/`t` columns *and the record count* depend on `v`. Hence
$\Pi_{\text{arity}}$ — which declares **no field reads at all** — effectively reads `v`.
Ten further exclusions.

$$\Rightarrow\quad \boxed{\text{Admissibility is a property of the TRIPLE } (\Pi, Q, T), \text{ never of the pair.}}$$
$$\boxed{\text{Declaration is not verification.}}$$

### 3.4 Result

```
112 cells · 14 excluded by gate · 67 no informative stratum · 15 RD_within = 0 · 2 non-replicating
SURVIVING 14 → substantive-on-both-splits 0 · borderline 1 · negligible 13
CELLS ACTUALLY SEARCHED: 31 of 112
```

`[EXP]` **H1 not refuted.** 12 of the 14 survivors had **no stratum whose 95 % CI excluded zero**.

---

## 4. KR-BRIDGE-03 — the generator, and a commissioned lever that did not work

**Commission:** *change the generator's redundancy distribution and re-run with more informative
strata.* Nine regimes calibrated by measurement.

### `[NEG]` Flattening the redundancy distribution does **not** increase informative strata

$73 \to 75$. The reason is exact:

| $r$ | behaviour |
|---|---|
| $r = 0$ | all values distinct → **`Zero` never fires** → $(a+b) = 0$ |
| $r = 3$ | all values equal → **`Zero` always fires** → $(c+d) = 0$ |

**Flattening moves mass into the deterministic extremes.** It buys strata that cannot be
informative *by construction*.

### `[EXP]` The effective lever is the **record count**

$n_{\text{rec}}: 4 \to 6$ gives $73 \to 233$ — more records, more multiplicity partitions
($5 \to 9$), more genuinely mixed strata.

`[NEG]` **Population size is not a lever either** (73 / 74 / 70 at $n = 1500/4000/8000$). Checked
rather than assumed, because adequacy is population-dependent by construction.

### Result at 13× power

| | BRIDGE-02 | R1 uniform | R8 **flattened** |
|---|---|---|---|
| informative strata | ~30 | **388** | **396** |
| surviving cells | 14 | **1** | **1** |
| **substantive on both splits** | 0 | **0** | **0** |
| borderline | 1 | **0** | **0** |

`[NEG]` **The BRIDGE-02 borderline cell is closed as noise.** `Pi_value_set | Q_argmax_ntags |
T_B_lossy` carried $MH\text{-}RD$ −0.0159/−0.0091 on 2 strata; at 12 strata it returns **exactly
0.000000 on both splits in both regimes.**

---

## 5. What no amount of sampling could fix

`[EXP]` **67 dead cells, in every one of the nine regimes. The count did not move by one.**

| $Q$ | dead cells |
|---|---|
| `Q_source_set` | 16 |
| `Q_tag_set` | 16 |
| `Q_max_value` | 15 |
| `Q_argmax_ntags` | 10 |
| `Q_majtag_total` | 10 |

Cause: **adequacy saturates.** For most $(T,Q)$ pairs the transformation either fully preserves
$Q$ or fully destroys it, leaving no Adequate/Inadequate variation. 25–31 of 35 $(T,Q)$
combinations sit at 0.00 or 1.00.

> `[EXP]` **This is a property of the adequacy DEFINITION, not of the generator.** Nine regimes
> are the evidence. The remaining lever is the $(T,Q)$ design: transformations that
> **partially** preserve each $Q$.

---

## 6. The standing conjecture

`[CONJ]` **No `Zero`/preservation bridge exists for read-disjoint $(\Pi, Q)$.**

**Refutable by:** a single substantive, replicating, gate-clean cell — i.e. $|MH\text{-}RD| \ge
0.01$ on **both** splits, with the level-aware gate passed and at least one stratum CI excluding
zero.

Stated as a conjecture, with its refutation condition attached, so it can be attacked rather
than hardening into an assumption. **It is not a law and must not be cited as one:** three
experiments, one carrier family, 31 of 112 cells searchable.

---

## 7. Register

| Statement | Status |
|---|---|
| `Zero` predicts preservation | **`[NEG]`** — 4 independent tests |
| The marginal `Zero`↔inadequacy association is real | `[EXP]` — and **confounded by redundancy** |
| Redundancy is a common cause, within the tested dedup regime | `[EXP]` — scope-bound |
| Admissibility is a property of $(\Pi,Q)$ | **`[NEG]`** — it is a property of $(\Pi,Q,T)$ |
| Read-disjointness can be established by declaration | **`[NEG]`** — must be verified empirically |
| Flattening the redundancy distribution raises informative strata | **`[NEG]`** |
| Record count raises informative strata | `[EXP]` |
| Population size raises informative strata | **`[NEG]`** |
| The 67 dead cells are a generator problem | **`[NEG]`** — invariant across 9 regimes |
| The 67 dead cells are an adequacy-definition problem | `[EXP]` |
| No bridge exists for read-disjoint $(\Pi,Q)$ | `[CONJ]` — refutation condition stated |
