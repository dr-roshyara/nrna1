# KR-BRIDGE-03 — the generator changed, the power rose 13×, the answer did not move

**Experiment ID:** `KR-BRIDGE-03-GENERATOR-REGIME-2026-09`
**Date:** 2026-09-04 · **Extends:** `KR-BRIDGE-02-ZERO-PRESERVATION-PI-Q-2026-09`
**Theory:** v1.2 **FROZEN** · **Kernel: NOT SELECTED** · nothing adjudicated

> **First document written under `EPISTEMIC-STATUS-VOCABULARY.md` (2026-09-04).** Every
> mathematical statement below carries its status. `[PROP]` is not used: it is retired as
> ambiguous, split into `[PRP]` (proved proposition) and `[REC]` (recommendation).

---

## 0. The commission

> *"change the generator's redundancy distribution and re-run with more informative strata"*

Done — and **the requested lever is not the one that worked.** That is the headline.

---

## 1. Why KR-BRIDGE-02 had only ~30 informative strata `[EXP]`

Structural, not accidental. With `r = |D| − |{v}|`, `n_rec = 4`, 4 uniform values:

| `r` | share | behaviour |
|---|---|---|
| 0 | 9.6 % | all values distinct → **Zero never fires** → `(a+b) = 0` |
| 1 | 55.2 % | informative |
| 2 | 33.6 % | informative |
| 3 | 1.6 % | all values equal → **Zero always fires** → `(c+d) = 0` |

**The extremes are deterministic and contribute nothing.** A stratum is informative only if
both Zero and non-Zero occur in it.

---

## 2. Calibration — the levers were measured, not argued `[EXP]`

Nine generator regimes, informativeness measured directly (`code/calibrate.py`,
`results/calibration.json`). Stratum = **(multiplicity partition, `|S|`)**, finer than the
redundancy count.

| regime | `n_rec` | redundancy law | informative (cell, stratum) pairs |
|---|---|---|---|
| `R0_baseline` (= KR-BRIDGE-02) | 4 | uniform | **73** |
| **`R5_balanced_n4`** | 4 | **FLATTENED** | **75** |
| `R1_wider_records` | 6 | uniform | **233** |
| `R8_balanced_n6v4` | 6 | **FLATTENED** | **204** |
| `R6_balanced_n5` | 5 | FLATTENED | 155 |
| `R2_6x6` / `R3_zipf` / `R4_zipf_wide` | 6 | uniform / Zipf | 108 / 235 / 185 |

### `[NEG]` **Flattening the redundancy distribution does not increase informative strata.**

`R0 → R5` holds `n_rec = 4` and flattens `r` from `(0.10, 0.55, 0.34, 0.02)` to
`(0.25, 0.24, 0.26, 0.25)`. Informative pairs: **73 → 75.** Nothing.

**The reason is exact:** flattening moves mass *into* `r = 0` and `r = 3`, which are
**deterministic** — `Zero` never fires in one and always fires in the other. Redundancy
balancing buys strata that cannot be informative **by construction**.

### `[EXP]` **The effective lever is the record count.**

`n_rec: 4 → 6` gives **73 → 233**. More records ⇒ more multiplicity partitions
(5 → 9) ⇒ more strata that are genuinely mixed.

### `[NEG]` Population size is not a lever either.

| regime | n = 1500 | n = 4000 | n = 8000 |
|---|---|---|---|
| `R0_baseline` | 73 | 74 | **70** |
| `R1_wider_records` | 233 | 266 | **258** |

Flat. *(Worth noting because adequacy is defined as fiber `Q`-homogeneity **over the
population** and is therefore n-dependent by construction — so this had to be checked, not
assumed.)*

---

## 3. The full re-run — two regimes, so the comparison is direct

Both at `n_rec = 6`, 8 000 cases × 2 splits, stratum = (partition, `|S|`).
**The adjudication, floor and gate are unchanged from KR-BRIDGE-02. Only the generator moved.**

| | KR-BRIDGE-02 | `R1` uniform | `R8` **flattened** |
|---|---|---|---|
| records per case | 4 | 6 | 6 |
| redundancy distribution | uniform | uniform | **FLATTENED** |
| stratum variable | redundancy count | (partition, `\|S\|`) | (partition, `\|S\|`) |
| **informative strata (train)** | **~30** | **388** | **396** |
| cells excluded by gate | 14 | 14 | 14 |
| cells with no informative stratum | 67 | 67 | 67 |
| **surviving cells** | 14 | **1** | **1** |
| **substantive on both splits** | 0 | **0** | **0** |
| borderline | 1 | **0** | **0** |

### `[EXP]` VERDICT — **H1 NOT REFUTED, at 13× the power**

> **No admissible `(Π, Q, T)` shows a within-stratum risk difference above the substantive
> floor on both splits.**

The single surviving cell in each regime is noise on its own evidence:

| regime | cell | `MH_RD` train | test | strata | strata whose CI excludes 0 |
|---|---|---|---|---|---|
| `R1` | `Pi_tag_multiset \| Q_source_set \| T_C_dedup` | −0.0014 | −0.0005 | 16 | **0 of 16** |
| `R8` | `Pi_source_multiset \| Q_tag_set \| T_C_dedup` | −0.0035 | −0.0024 | 18 | **1 of 18** |

### `[NEG]` The KR-BRIDGE-02 borderline cell is resolved — it was noise

`Pi_value_set | Q_argmax_ntags | T_B_lossy` carried `MH_RD` = −0.0159 (train) / −0.0091 (test)
on 2 strata. At 12 informative strata it returns **exactly 0.000000 on both splits, in both
regimes.** The open item from KR-BRIDGE-02 §4 is **closed as noise**, not carried forward.

---

## 4. What no generator change touched `[EXP]`

**67 dead cells in every one of the nine regimes.** The count did not move by one.

| `Q` | dead cells |
|---|---|
| `Q_source_set` | 16 |
| `Q_tag_set` | 16 |
| `Q_max_value` | 15 |
| `Q_argmax_ntags` | 10 |
| `Q_majtag_total` | 10 |

These are dead because **adequacy saturates**: for most `(T, Q)` pairs the transformation
either fully preserves `Q` or fully destroys it, so there is no Adequate/Inadequate variation
to relate `Zero` to. 25–31 of the 35 `(T, Q)` combinations sit at 0.00 or 1.00.

`[EXP]` **This is a property of the adequacy DEFINITION (fiber `Q`-homogeneity), not of the
generator.** No sampling change can fix it, and nine regimes are the evidence. The remaining
lever is the `(T, Q)` design itself — transformations that *partially* preserve each `Q`.

---

## 5. Register updates

| Statement | Status |
|---|---|
| Flattening the redundancy distribution increases informative strata | **`[NEG]`** — §2; 73 → 75 |
| Population size increases informative strata | **`[NEG]`** — §2 |
| Record count increases informative strata | **`[EXP]`** — §2; 73 → 233 at `n_rec` 4 → 6 |
| The 67 dead cells are caused by the generator | **`[NEG]`** — §4; invariant across 9 regimes |
| The 67 dead cells are caused by adequacy saturation | **`[EXP]`** — §4 |
| `Pi_value_set\|Q_argmax_ntags\|T_B_lossy` carries a conditional bridge | **`[NEG]`** — §3; exactly 0 at 6× the strata |
| `Zero` predicts preservation | **`[NEG]`** — now three experiments |
| A conditional bridge exists for some `(Π, Q)` | **`[OPEN]`** — searched 31 of 112 cells; **the unsearched 67 are unsearchable by sampling** |
| Eliminability ≠ Preservation ≠ Realization | **`[EXP]`** — **not `[THM]`**: no proof they must differ in general |

---

## 6. Recommendations `[REC]` — not decisions

1. **`[REC]` Stop tuning the generator.** Nine regimes, one lever that worked, and it is
   exhausted at `n_rec = 6`. The binding constraint has moved to the **`(T, Q)` design**.
2. **`[REC]` Build transformations that PARTIALLY preserve each `Q`.** That is the only thing
   that can revive the 67 dead cells, and it is a design change, not a sampling change.
3. **`[REC]` Do not declare a no-bridge law.** Three experiments, one carrier family.
   `[CONJ]` *No `Zero`/preservation bridge exists for read-disjoint `(Π, Q)`* — **refutable by
   a single substantive, replicating, gate-clean cell.** Stated as a conjecture so it can be
   attacked, not assumed.
4. **`[REC]` `FR-003`** (level-aware, empirically-verified read-disjointness gate) still needs
   a second independent adopter before promotion (`ES-006.1`).
5. **Governance unchanged:** `EXPERIMENT → AUDIT → ADJUDICATION → THEORY v1.3`.

---

## 7. Artifacts

| Path | Contents |
|---|---|
| `KR-BRIDGE-03-2026-09/code/generator.py` | 9 parametrized regimes incl. redundancy-balanced sampling |
| `KR-BRIDGE-03-2026-09/code/calibrate.py` | the informativeness sweep |
| `KR-BRIDGE-03-2026-09/code/run03.py` | full run at 2 regimes, finer strata |
| `KR-BRIDGE-03-2026-09/code/{bridge,observables,provenance}.py` | copied **unmodified** from KR-BRIDGE-02 |
| `KR-BRIDGE-03-2026-09/results/calibration.json` | 9 regimes measured |
| `KR-BRIDGE-03-2026-09/results/bridge03.json` | 112 cells × 2 splits × 2 regimes |
| `KR-BRIDGE-03-2026-09/results/run03.log` | run transcript |
