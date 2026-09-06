# K — Typed Facet Vocabulary and Re-run · `KR-ZERO-2026-09-02-I`

**Status** `[EXP]` `[PROP]` — research only. The vocabulary below is a **candidate**, not canon.

Experiment J showed §26's facet *names* cannot separate the critical six, and that the separating
work was being done by my free text. This specifies the missing layer — **typed conditions per
facet** — and re-runs against an extended suite, with a parsimony test to detect over-specification.

> **The rigging risk here is severe.** With enough types, any set of states separates. So every type
> carries a **provenance** — the corpus item, invariant or prior result that *forces* the distinction
> — and a parsimony test removes each type to check whether separation actually needed it.

---

## 1. The typed vocabulary — 11 facets, 35 types, each with provenance

| facet | types | provenance |
|---|---|---|
| `G_value` | `none` · `single` · `rivals` · `contradictory` | `\|A_t\| = 0/1/>1` from the v1.1 `Determine`; the fourth from Experiment G PB-2 (`{p,¬p}` has no value in `{T,F,U}`) |
| `G_dimension` | `known` · `unknown` | `ZI-06`; corpus `Z2` "unknown dimension" gap boundary |
| `G_assessment` | `not-assessed` · `insufficient-magnitude` · `insufficient-discrimination` · `sufficient` | `ZI-02`; the middle two from Experiment J's collision diagnosis |
| `G_evidence` | `none` · `present` · `invalid` · `not-yet-gathered` | `ZI-04` forces `none ≠ invalid`; the last from the four-way unknown taxonomy |
| `G_interpretation` | `resolved` · `ambiguous` · `uninterpreted` | v1.1 run `AD-9`; `UNINTERPRETED` from the four-way taxonomy |
| `G_conflict` | `none` · `unresolved` · `resolved-by-supersession` | `ZI-09`; supersession from `CE-3` |
| `G_assumption` | `explicit` · `implicit` · `unknown` | v1.1 `P17`; Zero Concept §17 |
| `G_temporal` | `current` · `stale` · `no-temporal-semantics` | `ZI-08`; `CE-3`; Experiment G A4 |
| `G_scope` | `in-scope` · `out-of-scope` | `ZI-03`; Zero Concept §20 |
| `G_model` | `available` · `no-evaluator` · `delta-undefined` · `no-ordering` | the three theory-blockers established in Experiment G |
| `G_observability` | `observed` · `channel-exists-unqueried` · `no-channel` | four-way taxonomy: `UNOBSERVED ≠ UNOBSERVABLE` |

**Two `ZI` invariants have no facet to live in** `[NEG]`:

| invariant | why |
|---|---|
| `ZI-07 Representation ≠ Reality` | §26's eleven facets contain **no representation/reality facet**. A twelfth would be required; this experiment does not invent one |
| `ZI-10 NoKnownGap ≠ Complete` | a property of the boundary **set** (`B = ∅ ≠ complete`), not of any facet — a meta-level invariant, not expressible facet-wise |

---

## 2. Extended suite — 18 states, 153 pairs

The nine from `KR-ZERO-H` plus nine added to exercise the four unused facets and the untestable
invariants: `S10` invalid evidence · `S11` unknown dimension · `S12` stale · `S13` ambiguous ·
`S14` implicit assumption · `S15` no known gap · `S16` uninterpreted · `S17` no ordering ·
`S18` δ undefined.

| | result |
|---|---|
| all 18 pairwise distinct | **✔ 0 collisions / 153** |
| critical six distinct | **✔** |
| facets exercised | **11 of 11** — no unused facet remains |

---

## 3. Parsimony — the vocabulary is **massively over-specified** `[NEG]`

My first parsimony test reported "0 of 35 types load-bearing", which was **the wrong test**: merging
one type cannot collide states that also differ in facet *membership*. Corrected with two tests.

### T1 — facet-untyping: collapse a facet to membership only

| facet | typing needed? | what collides without it |
|---|---|---|
| **`G_assessment`** | **YES** | `S3 ~ S15` |
| **`G_model`** | **YES** | `S6 ~ S17`, `S6 ~ S18`, `S17 ~ S18` |
| the other **nine** | **no** | — |

> **Nine of eleven facets separate on membership alone.** Only `G_assessment` and `G_model` require
> typing at all.

### T2 — pairwise type merges: which *distinctions* are load-bearing?

**4 of 41** within-facet distinctions do any work on this suite:

| distinction | collides if merged |
|---|---|
| `G_assessment: insufficient-magnitude ~ sufficient` | `S3 ~ S15` |
| `G_model: no-evaluator ~ delta-undefined` | `S6 ~ S18` |
| `G_model: no-evaluator ~ no-ordering` | `S6 ~ S17` |
| `G_model: delta-undefined ~ no-ordering` | `S17 ~ S18` |

> **37 of 41 declared distinctions are unnecessary on this suite.** They may be justified by their
> provenance, but this experiment provides **no evidence** for them. Reported as over-specification,
> not repaired — removing them would destroy invariant coverage the suite cannot yet test.

### Minimal sufficient typing sets — exhaustive over all `2¹¹` facet subsets

```
{ G_value , G_assessment , G_model }          and          { G_assessment , G_evidence , G_model }
```

> **Only 3 of 11 facets need typing.** `G_assessment` and `G_model` are in **both** sets and are
> therefore **mandatory**; the third is a free choice between `G_value` and `G_evidence`.

### 3.1 This corrects Experiment J `[NEG]`

J reported *"the minimal repair costs one typed facet vocabulary of three values —
`G_assessment`."* Corrected:

* `G_assessment` typing is **mandatory** — J was right about that;
* but it is **not sufficient**: `G_model` is equally mandatory, and a third facet is needed;
* and the specific `S3 ~ S5` collision J diagnosed is **redundantly separated** — by `G_assessment`
  *and* by `G_value`. Untyping either alone leaves them distinct; only untyping **both** collides them.

**J presented *a* minimal repair as *the* minimal repair.** There are two, both of size three.

---

## 4. Implementing artifact `I` §7 — Phase D over the typed boundary `[EXP]`

Artifact `I` §7 asked for Phase D re-run with closure defined over `𝓑` rather than `value`, to
confirm that the contradiction-model choice is unnecessary for closure. Implemented with the typed
vocabulary; who-can-act is derived **from the type**, not from free text.

| case | `Zero_weak` | `Zero_reasoned` | **`Zero_boundary`** | typed boundary |
|---|---|---|---|---|
| B1 factivity | true | true | **true** | 4 × theory-blocked |
| **B2 revision** | true | true | **false** | `G_conflict:unresolved`, `G_value:contradictory`, + theory |
| B3 no-source | false | false | **false** | `G_assessment:insufficient-magnitude`, `G_evidence:none/not-yet-gathered`, + theory |
| B4 blocked | true | true | **true** | 4 × theory-blocked |
| **S1 contradiction** | true | true | **false** | `G_conflict:unresolved`, `G_value:contradictory`, + theory |
| S2 δ defined | true | true | **true** | 3 × theory-blocked |
| S3 κ operational | true | true | **true** | 4 × theory-blocked |

> **Boundary closure is identical across all three contradiction models — CONFIRMED.**
> Choosing among three-valued / four-valued / delegated is **unnecessary for closure**, which removes
> a blocker Experiment G had declared load-bearing.

**B1 still closes.** The typed boundary repairs contradiction blindness; it does **not** repair
`Truth ⟂ Closure`, and must not be read as doing so.

---

## 5. Status register

| finding | status |
|---|---|
| Typed vocabulary, 35 types, each with provenance | `[PROP]` candidate |
| 18 states, 153 pairs, all distinct; critical six distinct | `[EXP]` |
| All 11 facets now exercised | `[EXP]` |
| **Only `G_assessment` and `G_model` require typing** | `[EXP]` |
| **4 of 41 distinctions load-bearing; 37 unsupported by this suite** | `[NEG]` over-specification |
| Two minimal typing sets, both size 3 | `[EXP]` exhaustive over `2¹¹` |
| **J's "minimal repair" was *a* repair, not *the* repair** | `[NEG]` correction |
| Boundary closure invariant across contradiction models | `[EXP]` confirms artifact `I` §2 |
| `ZI-07`, `ZI-10` have no facet to live in | `[NEG]` |
| Boundary completeness / facet exhaustiveness | `[OPEN]` |
| `Zero ∈ 𝒦` | **NOT TESTED** |

## 6. Next

> **Test the 37 unsupported distinctions, or drop them.**

Each has a provenance but no experimental support. The suite that would test them must contain a
state per distinction — and building it is the honest way to find out whether the provenances are
load-bearing or merely plausible. Where a distinction cannot be given a state, it should be recorded
as **unsupported**, not carried silently.

Second: `ZI-07` needs a twelfth facet and `ZI-10` needs a boundary-set-level predicate. **Neither is
invented here.**

**Do not** promote `Zero`, adopt a contradiction model (§4 shows closure does not need one), or treat
the typed vocabulary as canonical — 37 of its 41 distinctions currently rest on provenance alone.
