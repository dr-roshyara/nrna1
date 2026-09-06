# J — Boundary Separation Experiment · `KR-ZERO-2026-09-02-H`

**Commissioned by** `20260902-110900_kr-zero-2026-09-02-h-boundary-separation-experiment.md`
**Status** `[EXP]` — research only. Zero is a **lens** here: not a state, not a value, not a primitive.

## Research question

> Can the Zero Lens represent all boundary conditions encountered in Experiment G **without
> requiring Zero itself to adopt the evaluation codomain?**

**Acceptance:** `Boundary_i ≠ Boundary_j` wherever the theory requires the conditions to stay distinct.
**Critical criterion:** `Contradiction ≠ Absence ≠ EvidenceInsufficiency ≠ Unobservable ≠
Underdetermined ≠ TheoryIncomplete`, preserved **without assigning them different truth values**.
**Not asked:** `Zero(S_i) = T/F/U/C`. **No evaluator invented.**

---

## The nine states and their boundaries

Boundary structure per §26 — eleven **facets**, not a partition; one condition may span several.

| state | boundary facets |
|---|---|
| `S1` contradiction `{p,¬p}` | `G_value`, `G_conflict` |
| `S2` absence `∅` | `G_value` |
| `S3` evidence insufficient | `G_value`, `G_evidence`, `G_assessment` |
| `S4` unobservable | `G_value`, `G_observability` |
| `S5` underdetermined | `G_value`, `G_evidence`, `G_assessment` |
| `S6` theory incomplete | `G_model` |
| `S7` agent-remediable | `G_value`, `G_evidence`, `G_observability` |
| `S8` not applicable | `G_scope` |
| `S9` not assessed | `G_evidence`, `G_assessment` |

---

## Result 1 — the escape from the codomain trap is **real** `[EXP]`

| | |
|---|---|
| all nine boundaries pairwise distinct | **✔ 0 collisions / 36 pairs** |
| the critical six distinct | **✔** |
| **all nine project to the same value under `π`** | **✔ — every one is `U`** |

> **Nine epistemic conditions that are indistinguishable in the evaluation codomain remain fully
> distinguishable at the boundary.** That is precisely the escape the Zero Lens was proposed to
> provide, and it is demonstrated rather than asserted.

---

## Result 2 — but the **facet structure alone does not achieve it** `[NEG]`

I authored the nine boundary descriptions, so the separation could be an artifact of my prose rather
than of the structure. Anti-rigging test: strip the free-text conditions and separate on **facet keys
alone**.

| separated on | collisions / 36 | critical-six collisions |
|---|---|---|
| facets **+ free text** (as authored) | 0 | 0 |
| **facet keys only** | **1** | **1** |

The collision:

```
S3_evidence_insufficient  ==  S5_underdetermined      both = (G_assessment, G_evidence, G_value)
```

**and it lies inside the critical six** — `EvidenceInsufficiency` vs `Underdetermined`.

Worse, exhaustive search over all `2¹¹` facet subsets:

> **NO subset of facet keys separates the critical six.**

> `[NEG]` **Part of the separation in Result 1 came from the experimenter, not from §26's
> structure.** The candidate boundary structure, taken as a set of facet *names*, **fails the
> experiment's critical success criterion.**

### Diagnosis

`S3` and `S5` touch the **same facets**; they differ in what those facets *say*:

| | `G_evidence` | condition type |
|---|---|---|
| `S3` | present but **below the admissibility threshold** | insufficient **magnitude** |
| `S5` | present but **non-discriminating** | insufficient **discrimination** |

> The distinction is not *which* facet is touched but the **condition type inside it**. §26 leaves
> those conditions as free text, and free text is where the separating work was silently being done.

---

## Result 3 — the minimal repair, and its exact cost `[PROP]`

Giving **one** facet a typed vocabulary of **three** values:

```
G_assessment ∈ { insufficient-magnitude , insufficient-discrimination , not-assessed }
```

| | collisions / 36 | critical-six |
|---|---|---|
| facet keys + typed `G_assessment` | **0** | **0** |

> **Repaired.** The cost is one typed facet vocabulary of three values — and the finding generalizes:
> **the boundary must carry typed conditions per facet, not merely facet membership.** Specifying
> that type vocabulary is the next piece of work, and §26 does not contain it.

> ### CORRECTED — *a* minimal repair, not *the* minimal repair
> `KR-ZERO-2026-09-02-I` specified the vocabulary and searched exhaustively over all `2¹¹` facet
> subsets. **Two minimal sufficient typing sets exist, both of size three:**
> `{G_value, G_assessment, G_model}` and `{G_assessment, G_evidence, G_model}`.
> So `G_assessment` typing is **mandatory** (this section was right about that) but **not
> sufficient** — `G_model` is equally mandatory and a third facet is required. Moreover the specific
> `S3 ~ S5` collision diagnosed above is **redundantly separated**, by `G_assessment` *and* by
> `G_value`: untyping either alone leaves them distinct. See
> [`K-typed-facet-vocabulary.md`](K-typed-facet-vocabulary.md) §3.

---

## Result 4 — coverage limits of the nine-state suite `[NEG]`

**Four of eleven facets are never exercised:** `G_dimension`, `G_interpretation`, `G_assumption`,
`G_temporal`. The suite cannot say whether they are necessary, redundant, or ill-defined.

Of the ten Zero invariants, only **three** are testable with these nine states:

| invariant | testable | result |
|---|---|---|
| `ZI-01 Unknown ≠ Absent` | ✔ | **distinct** (`S5` vs `S2`) |
| `ZI-02 NotAssessed ≠ LowConfidence` | ✔ | **distinct** (`S9` vs `S3`) |
| `ZI-03 NotApplicable ≠ Unknown` | ✔ | **distinct** (`S8` vs `S5`) |
| `ZI-04 NoEvidence ≠ InvalidEvidence` | ✘ | the suite has **no invalid-evidence state** |
| `ZI-05 Unresolved ≠ False` | ✘ | requires a truth-value comparison this experiment does not ask |
| `ZI-06 UnknownDimension ≠ UnknownValue` | ✘ | no dimension-level state |
| `ZI-07 Representation ≠ Reality` | ✘ | no state pair for it |
| `ZI-08 PreviouslyUnknown ≠ PreviouslyAbsent` | ✘ | no temporal state |
| `ZI-09 Conflict ≠ Invalidity` | ✘ | requires an invalidity state |
| `ZI-10 NoKnownGap ≠ Complete` | ✘ | no complete-state case |

> **Seven of ten Zero invariants are untestable against the specified suite.** That is a property of
> the suite, not evidence against the invariants — but it means the experiment cannot support
> `ZI-04`…`ZI-10` in either direction.

---

## Answer to the research question

> **Qualified yes.**
>
> The Zero Lens **can** represent all nine boundary conditions without adopting the evaluation
> codomain — all nine project to the same `U` while remaining pairwise distinct. **But it cannot do
> so on the strength of §26's facet list alone**: facet membership collapses `EvidenceInsufficiency`
> into `Underdetermined`, inside the critical six, and **no facet subset avoids this**. The boundary
> needs **typed conditions per facet**; the minimal demonstrated repair is a three-value vocabulary
> for `G_assessment`.

## What this does NOT establish

Per §19's caution, and confirmed here:

* **not** that `Boundary` is formally complete — 4 of 11 facets untested, 7 of 10 invariants untestable;
* **not** that the facet categories are exhaustive — §26 itself says they are facets, not a partition;
* **not** a solution to `Sat`, `Contr`, `⪰` or factivity — none was touched;
* **not** `Zero ∈ 𝒦` — membership was not tested, and the kernel remains **NOT TESTED**.

## Status register

| finding | status |
|---|---|
| Nine conditions distinct at the boundary, identical under `π` | `[EXP]` |
| The codomain trap is escapable in principle | `[EXP]` |
| **Facet membership alone fails the critical criterion** | `[NEG]` |
| No facet subset separates the critical six | `[EXP]` exhaustive over `2¹¹` |
| Boundary requires typed conditions per facet | `[PROP]` |
| Minimal repair: 3-value `G_assessment` vocabulary | `[PROP]` |
| 4 of 11 facets unexercised; 7 of 10 `ZI` untestable | `[NEG]` coverage |
| `Boundary` complete / categories exhaustive | `[OPEN]` |
| `Zero ∈ 𝒦` | **NOT TESTED** |

## Next experiment

> **Specify the typed condition vocabulary for each facet — then re-run this experiment.**

Result 3 shows the separation lives in the condition types, not the facet names. The next step is the
smallest one that makes the boundary structure carry its own weight: a declared type vocabulary per
facet, tested against a **state suite extended to exercise the four unused facets and the seven
untestable invariants** — in particular an invalid-evidence state (`ZI-04`, `ZI-09`), a
dimension-level state (`ZI-06`) and a temporal state (`ZI-08`).

**Do not** promote `Zero` to a primitive, adopt a closure reading, or treat Result 1 as a separation
proof — Result 2 shows how much of Result 1 was the experimenter's prose.
