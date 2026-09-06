# `KR-BRIDGE-01-ZERO-PRESERVATION-2026-09` — RESULTS

> **Read [`KR-BRIDGE-01-AUDIT-2026-09.md`](KR-BRIDGE-01-AUDIT-2026-09.md) FIRST.** It records two
> material limitations and one label mismatch that qualify everything below.

**Theory v1.2 FROZEN · no v1.3 · no algebra · no kernel operator · KR-ZERO and KR-REP-REDUCTION
historical artifacts unmodified.**

---

# 1. Executive verdict

> ## **OUTCOME A — NO RELATIONSHIP OBSERVED**
>
> **After controls and stratification, Zero and preservation show no stable relationship in the
> tested regime.** `[EXP]`
>
> **This does NOT prove universal independence** (§22).

**The association collapses under stratification — a textbook confounding structure:**

```
POOLED                          RD = −0.363      "Zero is negatively associated with adequacy"
STRATIFIED by transformation    RD = −0.291      survives  (within T_C_dedup)
STRATIFIED by redundancy too    RD =  0.000      VANISHES  exactly
```

> **`H3` CONFIRMED: the apparent relationship is explained by a confounder — REDUNDANCY, acting as a
> COMMON CAUSE of both.**
>
> ```
> redundancy ──▶ S more likely to be a duplicate  ──▶ Zero
>            └──▶ dedup collapses more → larger fiber ──▶ inadequate
> ```

# 2. Experimental question

Is there a reproducible relationship between elimination Zero and preservation/adequacy under
representation transformation — **without assuming Zero is a preservation criterion**?

# 3–4. Design and the carrier actually used

**PARALLEL family** (§3): every `R = T(D)` is computed from `D` **directly**; no chain, so no
data-processing constraint is inherited.

**Experimental carrier:** records `(value, source, tag, time)`, `n = 4`, values from a 4-element set
(calibrated so fibers collide), 3 sources, 2 tags, 3 times.
**No mathematical carrier is declared** (§4). **This is not a KnowledgeOS knowledge representation.**

# 5. `Q` and `Π` — read-disjoint by construction

```
Q(D)  = ( argmax_source , #distinct tags )        reads SOURCE and TAG
Π(R)  = sorted value multiset                     reads VALUE only
```

> **They share no field.** The bridge cannot be built into the definitions (§9).

# 6–7. Transformation family and contract

Seven, all declared with expected effect before execution: `T_A` preserving · `T_B` drop source ·
`T_C` dedup · `T_D` rank-within-tag (relational) · `T_E` dedup+drop-source · `T_F` destroying
(control) · `T_G` +100 recoding (invertible control).

# 8–9. Zero and adequacy definitions

```
Zero(T,Π,S;D)   iff   Π(T(D)) = Π(T(E_S(D)))        elimination at SOURCE, then transform
Adequate(D,T)   iff   D's R-fiber is Q-HOMOGENEOUS over the population
```

**Computed by disjoint code paths.** `E_S` recomputes ranks at the relational level; `D∖S` is never
used where invalid.

# 10. Four-way Zero × Adequacy table

**TRAIN** (25 000 cases × 7 transformations × `|S| ∈ {1,2}`):

| | Adequate | Inadequate |
|---|---|---|
| **Zero** | **12 046** | **164 158** |
| **Non-Zero** | **679 604** | **894 192** |

**TEST**: 11 274 · 164 670 · 678 436 · 895 620 — **all four cells populated on both splits.**

> **`Q1` — can Zero occur while preservation fails?** **YES — 164 158.** Zero is **not sufficient**.
> **`Q2` — can preservation hold while Zero fails?** **YES — 679 604.** Zero is **not necessary**.

# 11–12. Stratified analysis — where the association goes

## By transformation

| `T` | zero rate | adequacy | `P(A\|Z)` | `P(A\|NZ)` | `RD` |
|---|---|---|---|---|---|
| `T_A_preserving` | 0.0000 | 1.0000 | — | 1.000 | undefined |
| `T_B_lossy` | 0.0000 | 0.0916 | — | 0.092 | undefined |
| **`T_C_dedup`** | 0.3524 | 0.3254 | **0.1367** | **0.4281** | **−0.2914** |
| `T_D_relational` | 0.0000 | 0.3470 | — | 0.347 | undefined |
| **`T_E_dedup_lossy`** | 0.3524 | 0.0026 | 0.0000 | 0.0040 | −0.0040 |
| `T_F_destroying` | 0.0000 | 0.0000 | — | 0.000 | undefined |
| `T_G_recoding` | 0.0000 | 1.0000 | — | 1.000 | undefined |

> **Zero fires under only 2 of 7 transformations.** Five contribute **no `Z` cells at all** and cannot
> inform the association. **Most of the pooled effect is the composition of the transformation mix**,
> not a Zero-preservation relationship.

## By transformation **and** redundancy — where it vanishes

**`T_C_dedup`**, stratified by `redundancy = n_records − n_distinct_values`:

| redundancy | n | `P(A\|Z)` | `P(A\|NZ)` | `RD` | informative? |
|---|---|---|---|---|---|
| 0 | 22 370 | — | 1.000 | — | no — Zero impossible without duplicates |
| **1** | **142 050** | **0.41309** | **0.41309** | **0.00000** | **YES** |
| 2 | 81 920 | 0.000 | 0.000 | 0.00000 | no — adequacy uniformly 0 |
| 3 | 3 660 | 0.085 | — | — | no — Zero-saturated |

> ## `[EXP]` `RD = 0.000` **exactly** in every stratum where the comparison is defined.

**And the exactness has two DIFFERENT causes** (audit §7):

- **r = 1** — the Zero-count per case is **constant (= 2)**, so Zero-status carries no case-level
  information and the 2×2 **factorises exactly**.
- **r = 2** — the Zero-count **varies** (6 or 8), so factorisation does *not* apply; `RD = 0` because
  **adequacy is uniformly 0**, leaving nothing to correlate with.

> **A single-mechanism explanation was proposed, tested, and withdrawn.** It fitted `r = 1` and failed
> `r = 2`.

# 13. Determination order

**Not equated with the preservation boundary** (§14). `|S| ∈ {1,2}` was swept; the case-I structure
(`Zero{0}` ∧ `Zero{1}` ∧ ¬`Zero{0,1}`) reproduces under `T_C`, **consistent with KR-ZERO and used here
only as a sanity check — no KR-ZERO law is re-derived or extended.**

# 14. Decoder controls

Adequacy is computed **without any decoder** — as fiber homogeneity of `Q`. **`T_G` (+100 on every
value) is an invertible recoding and yields adequacy identical to `T_A`**, so no observed effect is
attributable to decoder sensitivity.

# 15. Reduction dimensions

Reported separately, never combined: distinct representations per transformation
(`T_A` 250 000 → `T_C` collapses substantially → `T_F` 1). **No single "reduction score" was
constructed.**

# 16. Falsification scorecard

| | question | answer |
|---|---|---|
| Q1 | Zero while preservation fails? | **YES** — 164 158 |
| Q2 | Preservation while Zero fails? | **YES** — 679 604 |
| Q3 | Survives changes in `T`? | **NO** — undefined for 5 of 7; present only under dedup |
| Q4 | Survives changes in `Π`? | **untested** — one `Π` in this experiment `[OPEN]` |
| Q5 | Survives contract changes? | **untested** `[OPEN]` |
| Q6 | Survives changes in redundancy? | **NO — this is where it vanishes** |
| Q7 | Determination order predicts preservation independently? | **no evidence** |
| Q8 | Survives an invertible recoding? | `T_G` ≡ `T_A`; **no Zero observations under either**, so undefined |
| Q9 | Explained by transformation class? | **partly — 5 of 7 contribute no `Z` cells** |
| Q10 | Explained by `Q`? | **untested** — one `Q` `[OPEN]` |

# 17. Threats to validity

| threat | status |
|---|---|
| **only ONE informative stratum** (`r = 1`) | **material, reported not corrected** (amendment A2) |
| `T_B` label mismatch (0.0916 vs `< 0.05`) | **recorded as a design finding**, transformation not redefined |
| one `Π`, one `Q` | **Q4, Q5, Q10 untestable here** — a genuine scope limit |
| small carrier (4 values, 4 records) | calibrated for fiber collision; **transfer not claimed** |
| adequacy is population-relative | a case's adequacy depends on the sampled population; **stable across TRAIN/TEST but not a per-case intrinsic** |

# 18. What the experiment establishes

`[EXP]` **Zero is neither necessary nor sufficient for preservation** in the tested regime — both
off-diagonal cells are heavily populated on both splits.
`[EXP]` **The pooled negative association is confounded**, and **vanishes exactly** under joint
stratification by transformation and redundancy.
`[EXP]` **Within the tested deduplication regime, redundancy behaves as a common cause of Zero occurrence and inadequacy.** *(Tightened on review: one informative stratum, one carrier, one `Π`, one `Q`, one principal Zero-producing transformation — the scope constraints forbid stating this as a general causal law.)*
`[EXP]` **Zero fires under only 2 of 7 transformations** — Zero-occurrence is strongly
transformation-specific, consistent with (but not re-deriving) KR-ZERO.

# 19. What the experiment does NOT establish

`[NEG]` **Not** that Zero and preservation are universally independent — **one `Π`, one `Q`, one
carrier, one informative stratum.**
**Not** any KR-ZERO law, and **not** any KR-REP-REDUCTION law. **Neither family's historical results
were modified** (§26).
**Not** a carrier, **not** an algebra, **not** a bridge hypothesis. §22 permits a formal bridge
hypothesis **only under OUTCOME C**, and this is **OUTCOME A**.

# 20. Open questions

Does the independence survive a **different `Π`**? A **different `Q`**? A **contract** with more than
one clause? · Why does Zero never fire under the **relational** transformation `T_D` — a structural
property, or a property of this `Π`? · Can a generator produce **more than one informative
redundancy stratum**?

# 21. Reproduction

```bash
cd verification/zero-algebra/KR-BRIDGE-01-2026-09/code
python3 run_bridge.py ; python3 stratify.py
```
Seeds `20260904` / `88020260904` · 25 000 cases per split · deterministic.

# 22. Epistemic status

**`[EXP]`** the four-way counts, the stratified rates, the exact `RD = 0`.
**`[NEG]`** Zero is not sufficient; Zero is not necessary.
**`[OPEN]`** everything in §20.
**`[DESIGN]`** the `T_B` label mismatch; the single-informative-stratum limitation.
**No `[PROP]` is advanced, and no `[THEORY]` is touched.**

---

# A · What the experiment establishes
**Zero and preservation are analytically independent in this regime; the apparent association is
confounded by redundancy.**

# B · What it does not establish
**Universal independence. Any law of either parent family. Any carrier, algebra or bridge.**

# C · What the next experiment should test
**Vary `Π` and `Q`.** This experiment held both fixed, so `Q4`, `Q5` and `Q10` are unanswered — and
those are precisely the dimensions along which a conditional relationship (`H2`) could still hide.
**A second `Π` would cost little and closes the largest open gap.**
