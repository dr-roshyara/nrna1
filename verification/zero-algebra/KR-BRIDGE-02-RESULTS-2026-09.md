# KR-BRIDGE-02 — Zero vs Preservation, with **Π and Q varied**

**Experiment ID:** `KR-BRIDGE-02-ZERO-PRESERVATION-PI-Q-2026-09`
**Date:** 2026-09-04 · **Status:** `[EXP]` complete · audit run and folded in · `[OPEN]` pending adjudication
**Extends:** `KR-BRIDGE-01-ZERO-PRESERVATION-2026-09` (OUTCOME A)
**Closes:** `Q4` / `Q5` / `Q10` of `KR-BRIDGE-01-RESULTS-2026-09.md` §C
**Theory:** v1.2 **FROZEN** · **Kernel: NOT SELECTED** · **No algebra declared** · **No carrier declared**

---

## 0. Why this experiment exists

KR-BRIDGE-01 held **one Π** and **one Q** fixed. Its own §C named that as the largest
remaining gap: a **conditional bridge** — a relationship between `Zero` and preservation
that exists *for some particular* `(Π, Q)` — was untested, not excluded.

This experiment varies both, under an **enforced read-disjointness gate**.

---

## 1. Design

| Axis | Count | Members |
|---|---|---|
| **Π** (Zero's observable) | 5 | value multiset · value **set** · tag multiset · source multiset · **arity** (degenerate control) |
| **Q** (preservation target) | 5 | argmax-source + #tags · source set · majority-tag + total · max value · tag set |
| **T** (transformations) | 7 | unchanged from KR-BRIDGE-01 (`T_A`…`T_G`), **parallel** family |
| Elimination `S` | 10 | `\|S\| ∈ {1,2}` over 4 records |
| Cases | 8 000 × 2 splits | seeds `20260904` (train) / `88020260904` (test) — same as KR-BRIDGE-01 |

**Read-disjointness gate.** `Π` and `Q` must read **disjoint fields**. If they share one,
`Zero` and preservation are no longer independently defined and part of the bridge is built
into the definitions. Of the 25 `(Π,Q)` pairs, **9 were blocked** and **16 admitted** →
16 × 7 = **112 cells**.

---

## 2. THE CENTRAL FINDING — the gate was unsound, twice, and it mattered

### 2.1 First unsoundness: Π reads `T(D)`, not `D`

The first gate compared **declared** field reads on the **original** record. That is wrong:
a transformation can *derive* an output field from sources `Π` never declared.

```
T_D_relational ranks records WITHIN THEIR TAG GROUP   ⟹   rank = f(v, tag)
Pi_value_set falls back to `rank` when `v is None`    ⟸   exactly the T_D case
∴ on T_D output, Pi_value_set EFFECTIVELY READS {v, tag}
  Q_argmax_ntags reads {src, tag}                     ⟹   they SHARE `tag`
```

> ⚠️ **`Pi_value_set | Q_argmax_ntags | T_D_relational` carried the ONE substantial effect
> in the whole grid** — `MH_RD = −0.155`, replicating on test at `−0.152` across 4 strata.
> **Under the naive gate this experiment would have reported a conditional bridge.**
> It is an artifact of shared reads.

### 2.2 Second unsoundness: caught by the audit, not by inspection

The corrected map was still wrong, and **reading the code did not reveal it — a perturbation
test did** (`code/audit02.py`, check `A1`):

```
T_C_dedup decides WHICH RECORDS SURVIVE using `v`
  ⟹ the surviving src / tag / t columns depend on v
  ⟹ the record COUNT depends on v
  ⟹ Pi_source_multiset and even Pi_arity effectively read {v} after dedup
```

`Π_arity` reads *no field at all* by declaration, and still leaks: **arity is a function of
which records survive, and membership is decided by a field.** Ten further cells were
excluded. `A1` now **PASSES**.

`[EXP]` **A read-disjointness condition stated on source fields is not sufficient. It must be
discharged on the representation the observable actually reads, and it must be *verified
empirically*, not by declaration. Admissibility is a property of the triple `(Π, Q, T)`,
never of the pair `(Π, Q)`.**

---

## 3. The other correction — the adjudication target was wrong

The runner first adjudicated on the **marginal** risk difference and returned
"H2 SUPPORTED, 27/27 cells replicate." **That is a false positive by construction.**
KR-BRIDGE-01 *already observed* a large marginal association —

```
KR-BRIDGE-01 TRAIN:  T_C_dedup  RD = −0.29141      pooled  RD = −0.36346
```

— and OUTCOME A was precisely that this marginal association is **confounded by redundancy**.
Re-finding it under 27 relabelled cells re-finds KR-BRIDGE-01's confounder, not a bridge.

**A conditional bridge requires `RD ≠ 0` *within* a redundancy stratum**, replicating
out-of-sample. Adjudication uses the **Mantel–Haenszel** within-stratum risk difference
(`code/adjudicate02.py`), strata = redundancy `r = |D| − |{v}|`, minimum stratum `n = 200`
declared before inspection.

---

## 4. Result

```
cells                                        112
  EXCLUDED by the level-aware gate             14
  no informative stratum                       67
  within-stratum RD == 0                       15
  non-replicating                               2
  SURVIVING                                    14
      substantive on BOTH splits                0
      borderline (one split only)               1
      negligible (|RD| < 0.01)                 13
  distinct mechanisms after Π-collapse          3
  CELLS ACTUALLY SEARCHED                      31   of 112
```

### `[EXP]` VERDICT — **H1 NOT REFUTED**

> **No admissible `(Π, Q, T)` shows a within-stratum risk difference above the substantive
> floor on both splits. Every marginal association is accounted for by redundancy, and the
> one substantial candidate was excluded by the level-aware read-disjointness gate.**

**The sole borderline cell**, reported and *not* claimed:

| Cell | `MH_RD` train | `MH_RD` test | strata | per-stratum RD |
|---|---|---|---|---|
| `Pi_value_set \| Q_argmax_ntags \| T_B_lossy` | **−0.01590** | **−0.00914** | 2 | `[0.000, −0.037]` |

It clears the 0.01 floor on train and falls below it on test, and **one of its two strata has
RD exactly 0**. **Sign replication alone certifies nothing about magnitude**, so this is
`[OPEN]`, not a finding.

*(The floor was declared after first inspection; that is recorded here rather than concealed.
It is a reporting tier, not a filter — all 14 survivors are in `results/adjudication02.json`.)*

**Audit `A2`/`A3`:** of the 14 survivors, **0** have per-stratum signs that disagree, and
**12 have no stratum whose 95 % CI excludes zero**. The surviving set is, on its own evidence,
almost entirely noise that passed a sign-only replication rule.

---

## 5. Π-collapse — 14 surviving cells are 3 mechanisms

Different `Π` induce the **identical** `Zero` predicate under a given `T` (Zero rate, TRAIN):

| `T` | `Π_value_multiset` | `Π_value_set` | `Π_tag_multiset` | `Π_source_multiset` | `Π_arity` |
|---|---|---|---|---|---|
| `T_A_preserving` | 0.000 | 0.351 | 0.000 | 0.000 | 0.000 |
| `T_B_lossy` | 0.000 | 0.351 | 0.000 | **1.000** | 0.000 |
| `T_C_dedup` | **0.351** | **0.351** | 0.255 | 0.225 | **0.351** |
| `T_D_relational` | 0.000 | 0.280 | 0.000 | 0.000 | 0.000 |
| `T_E_dedup_lossy` | **0.351** | **0.351** | 0.255 | **1.000** | **0.351** |
| `T_F_destroying` | 0.000 | 1.000 | 1.000 | 1.000 | 0.000 |
| `T_G_recoding` | 0.000 | 0.351 | 0.000 | 0.000 | 0.000 |

After `T_C`, the value multiset, the value set and the **arity** are mutually determined —
dedup makes them the same observable. Counting them as three findings triple-counts one
mechanism.

`[EXP]` **The cardinality of a Π-family is not the cardinality of the mechanisms it can
distinguish; a transformation can collapse an observable family.** *(Compare `FR-001`:
pairwise distinguishability cannot carry family-level complexity. Same shape, different level.)*

`Π_source_multiset` reaching **1.000** under `T_B`/`T_E` and `Π_arity` reaching 0.000 under
every non-dedup `T` are **degenerate by construction** and were retained as controls: they
show the design can produce both saturating and vacuous observables.

---

## 6. Controls — 7/7 PASS · Audit `A1` PASS after two corrections

| Control | Result |
|---|---|
| `G` the disjointness gate blocks something | **PASS** — 9 pairs + 14 triples |
| `H` Π varies Zero (Π not inert) | **PASS** |
| `I` Q varies adequacy (Q not inert) | **PASS** |
| `A` preserving transformation is adequate | **PASS** (1.000) |
| `B` destroying transformation is inadequate | **PASS** (0.000) |
| `C` invertible recoding matches `T_A` exactly | **PASS** |
| `E` the test separates `T_A` from `T_F` | **PASS** |
| `A1` provenance map verified by perturbation | **PASS** *(FAILED twice first)* |

---

## 7. What is now closed, and what is not

| KR-BRIDGE-01 question | Status |
|---|---|
| `Q4` does the result depend on the choice of `Π`? | **`[EXP]` CLOSED** — 5 Π. Π changes `Zero`'s *rate* enormously (0.000 → 1.000) but not its *relationship* to preservation |
| `Q5` does it depend on the choice of `Q`? | **`[EXP]` CLOSED** — 5 Q, same |
| `Q10` could a conditional bridge exist? | **`[EXP]` NOT FOUND**; **not proven impossible** |

**`[OPEN]` The honest limit.** 67 cells had no informative stratum and 14 were excluded by the
gate. **This experiment searched 31 of 112 cells.** That is evidence of absence *only over the
region actually searched* — and the region shrank as the gate got more correct, which is the
right direction but leaves less power, not more.

---

## 8. Recommendations `[PROP]` — not decisions

1. **Do not declare a no-bridge law.** Two experiments, one carrier, one generator, 31 informative cells.
2. **`[PROP]` `FR-003` candidate — the level-aware, empirically-verified read-disjointness gate.**
   It caught a false positive that would otherwise have entered the record, and the version of it
   I wrote *by inspection* was itself wrong. **Declaration is not verification.**
3. **Adjudicate marginal vs. within-stratum explicitly in every future design.** The runner's
   first verdict was wrong for a reason that will recur.
4. **The borderline `T_B` cell deserves one dedicated experiment**, powered for `|RD| ≈ 0.01`,
   with more than 2 informative strata. Not a claim; a target.
5. **Power, not breadth, is the binding constraint now.** A wider Π×Q grid will not help while
   two-thirds of cells are uninformative. Change the **generator's redundancy distribution** to
   create more informative strata.
6. **Governance unchanged:** `EXPERIMENT → AUDIT → ADJUDICATION → THEORY v1.3`.
   This document is the EXPERIMENT + AUDIT stage. **Nothing here is adjudicated.**

---

## 9. Artifacts

| Path | Contents |
|---|---|
| `KR-BRIDGE-02-2026-09/code/bridge.py` | carrier, transformations, typed `E_S` (copied from KR-BRIDGE-01, **unmodified**) |
| `KR-BRIDGE-02-2026-09/code/observables.py` | the 5 Π, the 5 Q, the pair-level gate |
| `KR-BRIDGE-02-2026-09/code/provenance.py` | **the level-aware `(Π,Q,T)` gate** + provenance map |
| `KR-BRIDGE-02-2026-09/code/run_bridge02.py` | runner, controls, marginal tables |
| `KR-BRIDGE-02-2026-09/code/adjudicate02.py` | Mantel–Haenszel stratified adjudication |
| `KR-BRIDGE-02-2026-09/code/audit02.py` | adversarial audit `A1`–`A4` |
| `KR-BRIDGE-02-2026-09/results/bridge02.json` | 112 cells × 2 splits, marginal + by-redundancy |
| `KR-BRIDGE-02-2026-09/results/adjudication02.json` | per-cell MH statistics, exclusions, verdict |
| `KR-BRIDGE-02-2026-09/results/audit02.json` | provenance verification, homogeneity, CIs |
