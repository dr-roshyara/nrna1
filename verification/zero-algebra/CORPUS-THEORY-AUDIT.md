# CORPUS–THEORY AUDIT
## Old KR-ZERO evidence vs the new Representation-Reduction theory

**Mandate:** `…/mathematical_ideas_that_can_be_implemented/20260903-090000_master-handoff-…md` §37–40.
**§40 is explicit: AUDIT ONLY. No implementation.** Nothing was implemented; the new experiment is
**designed, not built**.

**Discipline followed:** `INSPECT → CLASSIFY → VERIFY → MAP → DESIGN`. Never `ASSUME → ADAPT → FABRICATE`.

---

# 1. What the old KR-ZERO corpus actually contains

Three experiments under `verification/zero-algebra/`:

| experiment | asks | artifacts |
|---|---|---|
| `KR-ZERO-ALGEBRA-2026-09` | does `L` have the claimed algebraic properties? | 5 md · 2 json · 12 witness json · 4 code |
| `KR-ZERO-GROUP-2026-09` | structure of `Zero(S;D)` over subsets | 3 md · 2 json · 6 witness json · 5 code |
| `KR-ZERO-ORDER-2026-09` | interaction order `k` | 5 md · 5 json · 3 witness json · 9 code · **`corpus/`** |

**Case-level data exists only in `KR-ZERO-ORDER-2026-09/corpus/`** — and only because it was
**reconstructed** after an earlier audit found it had never been persisted.

# 2. What it does NOT contain

> **No `Q`, no `C`, no `O`, no `R5..R1` chain, no `N_viol`, no `H(Q|R)`, no `H(R|Q)`, no adequacy, no
> realization, no fiber analysis, no held-out split, no encoding/storage measure.**

**Grepped, not assumed:** `Q(D)` · `R5` · `C(Rn)` · `O(Rn)` · `representation_hierarchy` ·
`reduction_level` → **0 occurrences across all artifacts.**

# 3–5. Verification of the 1 395 population, the aggregates, and the reconstruction

**Three independent parse paths agree:**

| source | n | distribution |
|---|---|---|
| `corpus/cases.jsonl` | **1 395** | `{1:1252, 2:25, 3:3, irreducible:115}` |
| `corpus/cases.csv` | **1 395** | identical |
| `property-results.json` | **1 395** | identical |

**✔ All match the published aggregates exactly.** `corpus/subsets.jsonl` holds **14 194** subset rows.

**One apparent discrepancy, explained rather than glossed:** `cases.jsonl` covers **685** contexts
while `subsets.jsonl` covers **1 034**. The 349 missing are **all `n = 2`**: their only level is
`m = 2`, which has `C(2,2)=1` subset — fewer than 2 to compare, hence *incomparable* and skipped.
**The grains differ by design; this is not a data loss.**

---

# 6. Exact meaning of old `R1`–`R4` — **PROVEN, two ways**

## 6.1 From the generator source

```python
cls = rng.choice(["R1","R2","R3","R4"])        # INDEPENDENT RANDOM DRAW per case
def mk(tok):
    if cls == "R1": return Item(tok)                                   # token only
    if cls == "R2": return Item(tok, source=…, uncertainty=…)          # + metadata
    if cls == "R3": return Item(tok, node=…, edge_to=…)                # graph
    return               Item(tok, source=…, uncertainty=…, scope=…, polarity=…)  # R4
```

**They are drawn at random and independently.** No transformation maps `R_n → R_{n-1}`. **There is no
chain, and nothing in the code could produce one.**

## 6.2 From the data — they are **not even totally ordered**

`R3` populates `node`/`edge_to`, which **no other class has**, and lacks fields `R2`/`R4` carry.

> **`R3` vs `R2` comparable by inclusion: NO. `R3` vs `R4`: NO. Strict inclusions found: NONE.**
>
> ## `[NEG]` A set of pairwise-incomparable categories cannot form a reduction chain. `R1..R4` are **parallel representation CLASSES**, not levels.

## 6.3 ⚠️ And a DEFECT the audit found in the generator

**`R1` should be token-only. 103 of 256 `R1` contexts (40.2 %) carry metadata.**

**Cause, located in source:** the shapes `meta_conflict`, `same_value_diff_source` and `contradiction`
construct `Item(...)` **directly**, bypassing `mk()` and therefore **ignoring `cls` entirely**.
44/44, 30/30 and 29/29 respectively.

> `[DEFECT]` **The representation-class factor is not faithfully realized.** 3 of 11 generator shapes
> ignore it.
>
> **Consequence:** the earlier observation *"representation class is flat — not a driver"* (129/111/
> 131/110) **is not interpretable as a clean factor effect** and must not be cited as one. It is
> withdrawn pending a corrected generator.
>
> **This makes the `R1..R4` ≠ `R5..R1` conclusion stronger, not weaker** — they are not merely
> unordered, they are **not clean categories.**

---

# 7. Exact definition of the NEW `R⁵→R²` concept

From the handoff, and **absent from the repository**:

```
R5 = T5(D)     R4 = T4(R5)     R3 = T3(R4)     R2 = T2(R3)     R1 = T1(R2)
```

A **sequential chain of transformations**, each applied to the previous *output*, with adequacy
measured at every stage against a contract `Π = (Q, C, O)`. **Reduction direction explicit.**

---

# 8. Theory-to-artifact gap matrix

| theoretical construct | repository artifact | evidence | status |
|---|---|---|---|
| `Zero_{T,Π}(S;D)` preservation predicate | `zero_algebra.zero()` | 1 395 + 14 194 rows | **EMPIRICALLY TESTED** |
| Zero is not element-wise | `G5`, case I/J | 267/746 robust | **EMPIRICALLY TESTED** |
| interaction order `k` | `interaction_order.py` | `{1:1252,2:25,3:3,irr:115}` | **EMPIRICALLY TESTED** |
| context/transformation/contract dependence | `KR-ZERO-GROUP`, `MECHANISM.md` | 2×2, 0/33 840 cell | **EMPIRICALLY TESTED** |
| `Π` as a single predicate | `CONTRACTS` | 9 contracts | **PARTIAL** |
| **`Π = (Q, C, O)` decomposition** | — | none | **ABSENT** |
| **inquiry `Q`** | — | none | **ABSENT** |
| **constraints `C` / admissibility `A_n`** | — | none | **ABSENT** |
| **decoder `O` / realization `F_n`** | — | none | **ABSENT** |
| **adequacy `H(Q\|T)=0`** | — | none | **ABSENT** |
| **fiber separation `N_viol`** | — | none | **ABSENT** |
| **`H(R\|Q)` inquiry-extraneous** | — | none | **ABSENT** |
| **`R5→R1` chain** | — | none | **ABSENT** |
| **metric vector `M(Rn)`** | — | none | **ABSENT** |
| Theorems 1–3 | — | — | **THEORETICAL ONLY** |
| **`E_S(D) = D \ S`** | `Rep.without()` — **assumed** | — | **⚠️ CONFLICTING** — handoff §10 says *do NOT assume* this unless the representation makes it valid. **The implementation assumes it.** |
| `L` not a projection | `KR-ZERO-ALGEBRA` H1 | non-idempotent for `L_simultaneous` | **EMPIRICALLY TESTED** — agrees with §28 |
| `⊕` decomposition | never assumed | — | consistent with §27 |
| carrier | — | — | **THEORETICAL ONLY / OPEN** |

**Count: 4 EMPIRICALLY TESTED · 1 PARTIAL · 10 ABSENT · 1 CONFLICTING · 2 THEORETICAL ONLY.**

# 9. Missing experimental infrastructure

Everything the new theory measures. **There is no inquiry, no decoder, no admissibility predicate, no
entropy estimator, no fiber counter, no reduction chain, and no held-out split anywhere in the
repository.** The new experiment is **not an extension of the old one; it shares only the `Zero`
predicate.**

# 10. Proposed new experiment — **DESIGN ONLY, NOT BUILT**

`KR-REP-REDUCTION-2026-09`, per §32's fifteen required definitions:

| # | element | proposal |
|---|---|---|
| 1–2 | carrier `X`, source `D` | a **structured record** with redundant encodings, so reduction is meaningful |
| 3 | inquiry `Q` | an explicit function `Q : X → Answers` — **declared before any transformation is written** |
| 4 | constraints `C` | admissibility predicate on `T(D)` |
| 5 | decoder `O` | a **fixed, specified** operator — kept distinct from the optimal `O*` |
| 6–7 | `T5..T1`, `R5..R1` | a genuine chain, each applied to the previous **output** |
| 8 | encoding | alphabet and encoding **declared**, since §25 warns digit count ≠ capacity |
| 9–11 | generation, seed, N | deterministic, seed recorded, **full population persisted (§33)** |
| 12 | held-out | train/test split, because `Ĥ(Q\|R)=0` ≠ `H(Q\|R)=0` (§24) |
| 13 | metrics | the full `M(Rn)` vector — `A_n, F_n, Ĥ(Q\|Rn), Ĥ(Rn\|Q), Ĥ(Rn), N_viol, n` |
| 14 | failure criteria | the **three distinct boundaries** of §23 — representation / contract / operator |
| 15 | persistence | `cases.jsonl` (one row per `(D,Q,Π,chain)`) + nested level records + subset records |

**Two design commitments carried from the audit:**

- **Persist the population from the start.** The old experiment saved only the tail and was rescued
  only by determinism.
- **The generator must realize its declared factors.** §6.3 is exactly the failure to avoid.

# 11. Risks of conceptual contamination

| risk | mitigation |
|---|---|
| **`R1..R4` read as `R5..R1`** | §6 — proven false twice, and the labels differ in *direction*, *ordering* and *cleanliness* |
| old aggregates cited as evidence for the new theory | §8 — 10 constructs ABSENT |
| `Ĥ` promoted silently to `H` | §12 |
| adequacy conflated with realization | separate columns `A_n`, `F_n` mandated |
| **theory documents stored in evidence directories** | **A copy of this handoff was found inside `KR-ZERO-ORDER-2026-09/witnesses/`. Moved to `_theory-handoffs/`.** An evidence directory must contain evidence only |

# 12. Open questions

- Whether `E_S(D) = D \ S` is valid for the new carrier — **the current implementation assumes it and
  the handoff forbids assuming it.**
- The carrier itself (§26) — **undetermined, and the audit adds no evidence.**
- Whether the representation-class defect (§6.3) affects any *published* KR-ZERO conclusion beyond the
  withdrawn flatness claim. **Not yet re-run.**
- Whether adequacy is monotone along a chain — **§21D warns it may not be; must not be assumed.**

---

## Stop conditions (§39) — status

| condition | fired? |
|---|---|
| claimed dataset does not exist | **YES — the R⁵→R² dataset does not exist.** Reported, not fabricated |
| field exists by name but unpopulated | **YES — `R1..R4` name-collision.** Reported |
| `R5–R2` hierarchy cannot be demonstrated | **YES.** Reported |
| aggregate mistaken for case-level | previously fired; now resolved by reconstruction |
| **field not faithfully generated** | **YES — new: the class defect in §6.3** |

**No stop condition was silently repaired.**
