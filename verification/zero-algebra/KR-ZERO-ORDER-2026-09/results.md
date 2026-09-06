# Results

**1 034 contexts · 1 395 size-tests · seed `20260902` · `n ≤ 6` · vacuous pairings excluded.**

`sigma_k(S)` = the Zero-status of every **proper** subset of `S` of size ≤ k, canonically relabelled
to positions so subsets of equal size are comparable. A context is **k-determined at size m** iff no
two size-`m` subsets share `sigma_k` while differing in `Zero(S)`. Since subsets are proper,
`k` ranges `1..m−1`; failure at `k = m−1` means **irreducible** — the group's eliminability is not a
function of *any* of its parts' eliminability.

---

# 1. The ladder

| `k` | count | share |
|---|---|---|
| **1** — singletons suffice | 1 252 | **89.7 %** |
| **2** — pairs required | 25 | 1.8 % |
| **3** — triples required | 3 | 0.2 % |
| **irreducible** | **115** | **8.2 %** |

**By subset size:**

| m | k=1 | k=2 | k=3 | irreducible |
|---|---|---|---|---|
| 2 | 600 | — | — | **85** |
| 3 | 407 | 19 | — | **28** |
| 4 | 207 | 5 | **3** | 2 |
| 5 | 38 | 1 | — | 0 |

> `[EXP]` **DEMONSTRATED EMPIRICALLY within the tested generator and design, for `n ≤ 6`:** the
> ladder reaches at least `k = 3` — not merely pairwise-insufficient, but triple-requiring.
>
> ⚠️ **Not a proof of a general phenomenon.** `k > 3` remains `[OPEN]`, and `n ≤ 6` is a **design
> bound**, not a property of eliminability. The word *proves* is not available here.

---

> ## ⚠️ SEE `AUDIT.md` BEFORE CITING ANYTHING BELOW
> A pre-freeze audit found **two defects in this experiment's design**:
> 1. **`O2` was NOT paired** — different contract pools desynchronize the RNG, so the samples differ
>    in TRANSFORMATION mix too. Since irreducibility is transformation-driven, that comparison could
>    not support the claim I made from it. **A paired re-analysis replaces it; the conclusion holds.**
> 2. **`P1_result`, `P2_len`, `P3_required` are EXTENSIONALLY IDENTICAL** (0 disagreements in 81 440
>    tests). The contract factor has **7 levels, not 9**.
>
> **And the headline is narrowed:** the phenomenon is **transformation-specific** — `T2_dedup`
> 28.2 %, `T8_interacting` 22.9 %, `T4_context` 11.2 %, everything else ≤ 0.8 %, `T7` exactly 0.
> **Transformations that read RELATIONS between elements produce higher order; element-wise ones do
> not.**

# 2. Robustness — the methodological invariant, applied

> **Every structural finding must be tested against the provenance of the contracts,
> transformations, generators and assumptions that make the phenomenon observable.**

Applied here because it changed the previous experiment's headline.

| | all contracts | **excluding the cancelling contract** |
|---|---|---|
| size-tests | 1 475 | 1 496 |
| k = 1 | 1 327 | 1 337 |
| k = 2 | 25 | **17** |
| **k = 3** | 6 | **15** |
| **irreducible** | 117 (**7.93 %**) | **127 (8.49 %)** |

> `[EXP]` **The interaction-order phenomenon SURVIVES the provenance check, and strengthens under
> it.** Irreducibility is slightly *higher* without the cancelling contract, and `k = 3` is **more**
> frequent.
>
> **This is the opposite of what happened to case J**, and the contrast is the point: the same check
> that dissolved one finding confirms another. **The invariant discriminates; it does not merely
> deflate.**

---

# 3. What irreducibility actually looks like

```
D = [ a , a , b , b ]      T = T2_dedup      Π = P1_result

all four singletons:  Zero            (each duplicate is individually removable)
{0,1} = both a's   :  NOT Zero        (removing both destroys the surviving token)
{0,2} = one a,one b:  Zero
```

`{0,1}` and `{0,2}` have **identical singleton signatures** — every element of each is individually
Zero — and **differ in group Zero**. No `k = 1` data distinguishes them, and `m − 1 = 1`, so the
size-2 level is **irreducible**.

> **The mechanism is not exotic.** *Which* pair you take matters, not how its members behave alone.
> **Eliminability of a group is a property of the group's internal relation, not an aggregate of its
> members' properties.**

---

# 4. `k` is not monotone in `m`

**387 monotone · 4 non-monotone.** `[NEG]` **Requiring order 2 at size 3 does not imply requiring
≥ 2 at size 4.** The interaction order is a property of *each level*, not a growing budget.

---

# 5. `O4` — cross-context determination

| `k` | signatures | ambiguous | determined |
|---|---|---|---|
| 1 | 66 | 17 | no |
| 2 | 120 | 10 | no |
| 3 | 117 | **2** | no |

**Ambiguity falls sharply with `k` but never vanishes** — as expected, since context-dependence is
already established. **Reported to keep the two questions apart**, not as a new finding.

---

# 6. Integration — three kinds of Zero, now with measured shares

The commission proposed a three-way typology. The experiments support it, with quantities:

| type | definition | evidence |
|---|---|---|
| **Type 1 — Redundancy Zero** | removable because something equivalent remains | the `k = 1` majority, **≈ 90 %** |
| **Type 2 — Contextual Zero** | `Zero(x;D)` but `¬Zero(x;D′)` | `KR-ZERO-GROUP` case I, 267/746 |
| **Type 3 — Cancellation Zero** | a group is Zero though no member is | **contract-conditional** — 0 occurrences without a cancelling contract |

> ⚠️ **NOT a research finding.** An earlier draft called Type 2 *"the centre"*. **The three types are
> HYPOTHESIS VOCABULARY, not established ontological categories** — only Type 1's prevalence (≈ 90 %)
> and Type 3's contract-conditionality are separately measured, and §6.1 shows Type 2 conflates two
> dimensions that have not been shown identical. **This vocabulary must not enter kernel or theory
> language.**

## 6.1 ⚠️ Two DIMENSIONS, not one type — the taxonomy is narrowed

**An earlier draft filed the 8 % irreducible under Type 2. That conflated two things that have not
been shown identical:**

| | dimension | statement |
|---|---|---|
| **A** | **context dependence** | `Zero(x \| D₁) ≠ Zero(x \| D₂)` |
| **B** | **determination order** | `Zero(S)` cannot be derived from the Zero-status of its proper subsets |

**`KR-ZERO-GROUP` case I measures A. The 8 % irreducible here measures B.**

> **They may be related. They may share a mechanism. The experiments have NOT demonstrated that they
> are the same phenomenon**, and the taxonomy must not assert it. **The three "types" are a useful
> descriptive vocabulary, not three empirically established kinds** — only Type 1's prevalence
> (≈ 90 %) and Type 3's contract-conditionality are separately measured.

---

# 7. Integration — the carrier question

A corpus document read alongside this experiment argues for **a many-sorted relational structure** as
the mathematical core — with measure, metric, topology, probability and causal models as **regimes**
rather than as the ontology.

**These results are consistent with that direction and do not establish it.** What they establish is
narrower and is a constraint on any carrier:

> `[EXP]` **NARROWED, per review.** The safe statement is:
>
> ### A representation of eliminability based SOLELY on independently assigned proper-subset Zero-status cannot, in general, determine group Zero-status.
>
> In ~8 % of tested levels, group eliminability is not a function of any proper-subset information —
> so no encoding that stores **per-element, per-pair or per-triple eliminability alone** can
> reconstruct it.
>
> ⚠️ **WITHDRAWN:** *"the carrier cannot be a set with an element-wise eliminability predicate."*
> **"Set" itself is NOT ruled out.** A carrier of the form
> `elements + relations + context + transformation + contract` remains entirely available, and could
> represent the phenomenon. **What is ruled out is `set + independent element-wise eliminability` as
> the COMPLETE representation** — a materially weaker and more accurate claim.

**Consequence for terminology, adopted here:** the object is a **candidate information unit relative
to `(R, T, Π)`** — **not** an atomic knowledge element. **Atomicity is itself representation- and
transformation-dependent**, and this experiment measures the dependence rather than assuming it away.

---

# 8. The repeated obstruction pattern — recorded, not generalized

```
pairwise / local information  →  insufficient  →  higher-order structure
```

**Three independent occurrences now:** `FR-001` (pairwise distinguishability vs family complexity) ·
`KR-ZERO-GROUP` `G5` (pairwise Zero vs group Zero) · this experiment (the `k ≥ 2` and irreducible
strata).

> `[EXP]`/`[PROP]` **A repeated obstruction pattern is recorded. It is NOT a universal theorem, the
> three are NOT merged, and `FR-001` is NOT extended.** They concern different objects —
> distinguishability, eliminability, and determination order. **What is notable is only that the same
> shape has now appeared three times from three independent constructions.**

---

# 9. Status

**Not concluded:** that Zero is inherently higher-order — **90 % of levels are `k = 1`.** **Not
promoted:** any closure-system, matroid, hypergraph, lattice or rewriting reading. **Not created:**
Theory v1.3. **Not selected:** a kernel operator. **Not introduced:** an algebra.

## `[OPEN]`

- Whether `k > 3` occurs. **`n ≤ 6` bounds what this experiment could see** — `k = 3` at `m = 4` is
  the largest observed, and the bound is a limit of the design, not of the phenomenon.
- What distinguishes an irreducible level from a `k = 1` level, structurally. **`O3` gives per-factor
  rates but no mechanism.**
- Whether the three occurrences of the obstruction pattern share a cause.
- The carrier itself. **This experiment constrains it; it does not determine it.**
