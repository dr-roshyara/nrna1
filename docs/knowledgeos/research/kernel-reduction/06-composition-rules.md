# 06 — Composition Rules and `Reach(S)` (Parts VI & XIX)

## Typed carriers

**Ambient** (supplied by the situation, not by knowledge — no operator produces them):

```
World · Context · Inquiry · IdealState · Policy · Rule · Objective · ActionSet · EpistemicState(K_t)
```

**Derived:**

```
Observation · SemanticContent · Representation · Evidence · Relation · Discrimination ·
Hypothesis · Claim · Defeater · Verdict · NormDelta · Gap · Determination · Decision
```

`K_t` is ambient and `Kernel 𝒦` is the operator set — the two are never conflated (prior constraint 3).

## Carrier derivation — the second anti-circularity device

A carrier kind is **not** stamped by the operator that produced it. It is derived from

```
( multiset of input kinds ,  atoms introduced by this step )  →  kind
```

so **any** operator set able to introduce the required atoms over the required inputs produces the
carrier. Otherwise "only `DetectGap` can make a `Gap`" would be true by fiat and the ablation rigged.

| Inputs | Atoms introduced | → Kind |
|---|---|---|
| `World` | `world-contact` | `Observation` |
| `Observation, Context` | `meaning-assignment` | `SemanticContent` |
| `SemanticContent` | `symbolic-encoding` | `Representation` |
| `Observation, Policy` | `evidential-qualification` | `Evidence` |
| `Representation` \| `SemanticContent` | `relational-linking` | `Relation` |
| `Representation` \| `SemanticContent` | `content-generation` | `Hypothesis` |
| `Representation, Rule` \| `Claim, Rule` \| `Hypothesis, Rule` | `entailment` | `Claim` |
| `Claim` \| `Hypothesis` | `adversarial-negation` | `Defeater` |
| `Claim, Evidence` \| `Hypothesis, Evidence` | `warrant-assessment` | `Verdict` |
| `EpistemicState, IdealState` | `norm-comparison` | `NormDelta` |
| `NormDelta` | `difference-decision` | `Gap` |
| `NormDelta, Inquiry` | `closure-judgment` | `Determination` |
| `ActionSet, Objective` | `preference-over-actions` | `Decision` |
| `EpistemicState` | `state-mutation` | `EpistemicState′` |
| any of {Representation, SemanticContent, Hypothesis, Claim, Evidence, Observation, Verdict, Relation, NormDelta} | `difference-decision` | `Discrimination` |

Note the type discipline that carries the epistemic content:

* `Claim` is producible **only** via `entailment` — so its warrant kind is DEDUCTIVE by construction.
  A `Verdict` produced by generate-and-test is *not* a `Claim`. This is what keeps
  `Infer ≟ Validate ∘ Hypothesize` an honest question rather than a definitional trick.
* `Verdict` requires `Evidence`. This is where the absence of `Qualify` from `C0` becomes fatal.
* `Determination` requires **both** `NormDelta` (hence `IdealState`) **and** `Inquiry` — encoding
  prior constraint 5, that the Ideal State is inquiry-relative.

## `Reach(S)`

A **step** applies exactly one operator `o ∈ S` to the currently available carriers and introduces
exactly `o.atoms`. `Reach(S)` is the least fixpoint of the step relation, starting from the ambient
carriers.

```
Reach(S) = μ A . AMBIENT ∪ { k | ∃ o ∈ S : k ∈ derive(A, o.atoms) }
```

Monotone, terminating (finite carrier set), and independent of operator names.
A capability `c` is **achieved** iff `c.kinds ⊆ Reach(S)` and `c.atoms ⊆ atom_pool(S)`.

## What `Reach` deliberately does NOT include

* No "obvious" closures added by hand. If a composition is not derivable by the table above, it is
  not in `Reach`.
* No helper functions. Per Part XI every function is classified; the engine contains **only**
  domain-operator applications plus data-structure operations (set union, fixpoint). No helper
  performs the work of a removed operator — a claim that is mechanically checkable, since helpers
  cannot introduce atoms.
