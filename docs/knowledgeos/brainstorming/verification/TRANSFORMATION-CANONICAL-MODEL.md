---
artifact: E · TRANSFORMATION-CANONICAL-MODEL
mandate: 20260830_1918 §8
date: 2026-08-30
status: DERIVED · one fully instantiated transformation, no placeholders
---

# Transformation — Canonical Model

## 1. The signature

```
T : 𝕂 × Op × Policy × Authority  ⇀  𝕂 × Outcome                    PARTIAL
Outcome = ok | REJECTED(structure) | REJECTED(policy) | REJECTED(authority)
```

**`T : K → K` is REFUTED** — it cannot express rejection, and rejection is observed in the executed run
three times. **`T : K × I ⇀ K` is insufficient** — same `(K,I)` yields different results under different
policy and authority, executed below. **The eight-parameter form is unnecessary** — actor, evidence, context
and time were each eliminated by a distinguishability test (prior phase).

**Three rejection kinds, not one.** A single `⊥` would conflate *"the input was malformed"* with *"you were
not allowed"* — and governance must distinguish them. **Executed: all three fire.**

## 2. Per-operation specification

| Op | Precondition | State effect | Evidence effect | Provenance effect | Authority | Policy | Assessment effect | Failure | Det | Idem |
|---|---|---|---|---|---|---|---|---|---|---|
| `assert` | `WellFormed(P)`; policy satisfied | `𝒜 ∪ {a}` | `e` carried in | `Π` carried in | per policy | **yes** | none — `Σ` recomputed on read | structure, policy | yes | **yes** |
| `relate` | endpoints ∈ `𝒜`; acyclicity for DAG families | `ℛ ∪ {r}` | none | none | **yes** for `supersedes` | yes | may change derived `Σ` of neighbours | structure, authority | yes | **yes** |
| `retract` | `a ∈ 𝒜` | `𝒜 ∖ {a}`, **`ℛ` cascade** | drops `e` with `a` | lost | yes | yes | changes `Σ` | structure | yes | yes |
| `merge` | both structurally valid | `∪` on both | union | preserved | yes | yes | may create contested assertions | none | yes | **yes** |
| `noop` | — | none | none | none | no | no | none | none | yes | yes |

**`assert` and `relate` are idempotent because `𝒜` and `ℛ` are sets** — re-executing a history is safe.
**Executed: `F(H1) = F(H3)` where `H3` re-asserts `A1`.**

## 3. ONE FULLY INSTANTIATED TRANSFORMATION — every value concrete

```
INPUT STATE
  K = ( { A1 = ((Nexus, Version, "3.69"),
                {Evidence(ref=c73b3d27, polarity=supports, state=active)},
                Context("production"),
                [2026-01-02, ∞),
                "nexus-api")                                    id = e2c73b5500 ,
          A2 = ((Nexus, Version, "3.70"),
                {Evidence(ref=…, polarity=supports, state=active)},
                Context("production"),
                [2026-06-01, ∞),
                "changelog")                                    id = 8663022439 ,
          A3 (staging, id=ec1a3ecb46),  A4 (3.68, id=126ee23104) },
        ∅ )

OPERATION   relate( from = 8663022439, to = e2c73b5500, type = "supersedes" )
POLICY      "evidence-required"
AUTHORITY   "architect"

GUARD EVALUATION
  endpoints ∈ ids(K)                    8663022439 ✓   e2c73b5500 ✓
  type ∈ DAG families                   supersedes ✓ → acyclicity required
  authority check (supersedes)          "architect" == "architect" ✓
  acyclic(K ∪ {r}, supersedes)          ✓

OUTPUT      K' = ( same 𝒜 , { (8663022439, e2c73b5500, supersedes) } )
            Outcome = ok
POSTCONDITIONS  |𝒜'| = 4 (unchanged — A1 RETAINED, not deleted: 218.21 honoured)
                |ℛ'| = 1
                StructuralValid(K')   = (True,'ok')
                SemanticallyValid(K') = (True,'ok')
                contradicts(K', A1, A2) = False   ← the supersession EXPLAINS the difference
                contradicts(K', A1, A4) = True    ← unexplained, still a contradiction
```

**Three executed rejections against the same state:**
```
relate(…, supersedes) with authority="intern"        ->  REJECTED(authority)
relate(e2c73b5500, 8663022439, supersedes)           ->  REJECTED(structure): cycle in supersedes
assert ((Nexus, Version, "9.99"), …)                 ->  REJECTED(structure): V∉V_D
```

> **The third rejection is only possible because `P = (E,D,V)` is defined.** Before CB-2 closed, an
> ill-formed proposition could not be detected, because "ill-formed" had no meaning. **Closing the
> proposition gap directly strengthened the transformation guard** — a concrete downstream payoff.

## 4. Properties

| Property | Verdict | Basis |
|---|---|---|
| deterministic | **YES** | executed |
| closed | **YES** | `T(K,·) ∈ 𝕂` by construction |
| total | **NO** — deliberately partial | 3 rejection kinds executed |
| composable | **YES**, associatively | `Replay = fold(T, ∅, H)` |
| identity element | **YES** — `noop` | |
| idempotent (`assert`, `relate`, `merge`) | **YES** | set semantics; executed |
| monotone | **NO** | `retract` shrinks `𝒜` |
| invertible | **NO** | `retract` cascade is lossy |
| **congruent w.r.t. history** | **YES, structurally** | `T`'s domain contains no History — artifact F |

## 5. Transformation ≠ assessment ≠ decision ≠ governance action

```
transformation      𝕂 × Op × Policy × Authority ⇀ 𝕂 × Outcome     CHANGES K,  appends History
assessment          P × Evidence × Context × Policy → Σ            K UNCHANGED, no History
decision            Determination × Authority → commitment          K UNCHANGED, appends History
governance action   Policy × Transformation → Admissible            K UNCHANGED, appends History
```

**Executed proof that assessment is not a transformation:** `Assess` was run four times against the same
`K` and returned four different values without altering `|𝒜|` or `|ℛ|`. **A function that returns different
answers while the state is fixed cannot be a state transition.**

**Executed proof that Policy is a genuine argument of assessment:**
```
Assess(A2, policy="default")            = (Supporting, Weak)
Assess(A2, policy="strict-provenance")  = (Neutral,    None)     SAME EVIDENCE, DIFFERENT Σ
```
> **Therefore `Σ` is not a function of `(P, e)`.** This refutes any model that derives status from the
> assertion alone — and it is the formal reason `Assessment` needs four arguments.
