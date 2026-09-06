# `KR-BRIDGE-01-AUDIT-2026-09`
## Audit of the Zero × Preservation bridge experiment

**Audit this BEFORE `KR-BRIDGE-01-RESULTS-2026-09.md`.** Every empirical claim in the results
document traces to persisted data listed here.

---

# 1. Two amendments applied BEFORE execution

| | amendment | where |
|---|---|---|
| **A1** | the labels *preserving / lossy / redundancy-removing* are **pre-registered design classifications, not established facts**; verified after execution, and a mismatch is recorded as a design finding rather than silently redefined | `run_bridge.py :: PREREGISTERED`, `verify_labels()` |
| **A2** | the generator is **not tuned to balance the four cells**; natural imbalance is evidence and is reported with its structural cause | `run_bridge.py :: imbalance` block |

**Both were written into the code before the first run.** No result had been seen.

# 2. Design-rule compliance

| spec | requirement | how satisfied |
|---|---|---|
| §3 | **PARALLEL family, not a chain** | every `R = T(D)` computed from `D` **directly**; no `T` consumes another's output. `T_E` is declared as `T_B ∘ T_C` **as a composite defined on `D`**, not as a chain step |
| §9 | **Zero must NEVER be defined using `Q`** | `Q` reads **source and tag**; `Π` reads the **value multiset**. **Read-disjoint by construction** — verified by source inspection, §4 |
| §8 | typed elimination; `D∖S` only where valid | plain removal at the **source** level (records carry absolute values — valid); **rank recomputation** at `T_D_relational`, where the field is a within-tag rank and set subtraction is invalid |
| §4 | **no carrier declared** | the experimental carrier is stated as such; **no claim that it is the mathematical carrier or a KnowledgeOS representation** |
| §19 | independent TRAIN/TEST | seeds `20260904` / `88020260904`, 25 000 cases each, generated before any analysis |
| §26 | no historical artifact modified | **verified**: no file under `KR-ZERO-*` or `KR-REP-REDUCTION-*` was written |

# 3. Controls — §18, all executed before interpretation

| control | result | verdict |
|---|---|---|
| **A** preserving representation is adequate | `T_A` adequacy **1.0000** | **PASS** |
| **B** destroying representation is inadequate | `T_F` adequacy **0.0000** | **PASS** |
| **C** invertible recoding matches | `T_G` (+100 on every value) adequacy **1.0000**, identical to `T_A` | **PASS** |
| **D** Zero varies independently of the target | all four cells non-empty | **PASS** |
| **E** preservation test detects loss | `1.0000 − 0.0000` separation | **PASS** |
| **F** Zero test detects elimination differences | Zero fires for `T_C`/`T_E`, never for the other five | **PASS** |

**6 / 6 pass.**

# 4. Read-disjointness of `Q` and `Π` — the anti-circularity check

```python
def Q(D):   #  reads  r.src , r.tag            -- never the bare value multiset
def Pi(R):  #  reads  r.v  (or r.rank)          -- never src or tag
```

> **`Q` and `Π` share no field.** The bridge cannot be built into the definitions, which is the
> failure mode §9 exists to prevent.

# 5. ⚠️ Label verification (A1) — one MISMATCH, recorded not repaired

| transformation | observed adequacy | pre-registered | observed Zero rate | holds? |
|---|---|---|---|---|
| `T_A_preserving` | 1.0000 | high | 0.0000 | ✔ |
| **`T_B_lossy`** | **0.0916** | **low (<0.05)** | 0.0000 | **✘ MISMATCH** |
| `T_C_dedup` | 0.3254 | varies | 0.3524 | ✔ |
| `T_D_relational` | 0.3470 | varies | 0.0000 | ✔ |
| `T_E_dedup_lossy` | 0.0026 | low | 0.3524 | ✔ |
| `T_F_destroying` | 0.0000 | low | 0.0000 | ✔ |
| `T_G_recoding` | 1.0000 | high | 0.0000 | ✔ |

> `[DESIGN]` **`T_B_lossy` is less lossy than pre-registered.** Dropping `source` leaves `Q`'s
> *tag-count* component fully recoverable and its *argmax-source* component recoverable whenever the
> maximum is unique and its source is inferable from context — hence 9.16 % adequacy rather than
> < 5 %.
>
> **Recorded as a design finding. The transformation was NOT redefined to match its label**, which is
> precisely what amendment A1 exists to force.

# 6. ⚠️ A structural limit the audit must state — only ONE stratum is informative

**Zero fires under exactly 2 of 7 transformations.** Within the informative one (`T_C_dedup`), after
stratifying by redundancy:

| redundancy | cells present | informative? |
|---|---|---|
| 0 | `NZ` only — **no duplicates, so Zero is impossible** | **no** |
| **1** | all four | **YES — the only genuinely informative stratum** |
| 2 | all `+I` — **adequacy is uniformly 0** | **no** — nothing to correlate with |
| 3 | `Z` only — **Zero-saturated** | **no** |

> **The negative result therefore rests on ONE informative stratum.** This is a real limitation of the
> generator, **reported rather than corrected**, per amendment A2 (natural imbalance is evidence).

# 7. ⚠️ The exact `RD = 0.000` has TWO causes, not one

An earlier reading proposed a single mechanism — *the Zero-count per case is constant within a
redundancy stratum, so the 2×2 factorises*. **Tested, and it is only half right:**

```
Zero-counts per case, by redundancy:   r=0 → {0}   r=1 → {2}   r=2 → {6,8}   r=3 → {10}
```

| stratum | why `RD = 0` exactly |
|---|---|
| **r = 1** | **Zero-count IS constant (= 2)** → Zero-status carries no case-level information → the 2×2 factorises → `P(A\|Z) = P(A\|NZ) = ` the stratum's adequacy rate, `0.41309` |
| **r = 2** | **Zero-count VARIES (6 or 8)** — the factorisation argument fails. `RD = 0` because **adequacy is uniformly 0** in the stratum, so there is nothing for Zero to correlate with |

> **The single-mechanism explanation was wrong and is withdrawn.** Recorded because a clean story
> would have been more quotable and less true.

# 8. Reproducibility

```bash
cd verification/zero-algebra/KR-BRIDGE-01-2026-09/code
python3 run_bridge.py      # controls, four-way table, label verification
python3 stratify.py        # §12 confounding analysis
```

**Persisted:** `corpus/cases.jsonl` (50 000 rows, both splits, `D` + `Q(D)` + `Π(D)`) ·
`corpus/rows.jsonl` (per-observation sample with `S`, Zero, adequacy, cell) ·
`results/bridge.json` · `results/stratified.json`.

**No post-hoc filtering.** Sweeps (`|S| ∈ {1,2}`, 7 transformations, 4 redundancy strata) were
declared in code before execution.

# 9. Audit verdict

> **The experiment is sound and its controls hold. Two limitations are material and are carried into
> the results document: the `T_B` label mismatch, and the single-informative-stratum constraint.**
>
> **No claim in the results document exceeds what these data support.**
