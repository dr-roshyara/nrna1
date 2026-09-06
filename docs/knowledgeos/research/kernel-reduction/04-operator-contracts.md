# 04 — Operator Contracts (Part V)

## The anti-circularity device

An operator **is** its declared set of *semantic atoms* — the content it is permitted to introduce.
The reach engine (§06) consults **only** the atom set. Operator names are never consulted, so no
operator can privately own a carrier kind. Consequences:

* "reconstruct `X` from the remaining operators" is **decidable**;
* "redefine `Y` so that `Y` also does `X`" is **mechanically detectable** — it is a change to `Y`'s
  atom set, i.e. a change to the model, not a composition within it → `SMUGGLING = TRUE`.

**Atoms are deliberately allowed to be shared.** A 1:1 operator→atom assignment would make every
operator irreducible by construction and the experiment vacuous. Two atoms are shared (below), and
that sharing is where all the interesting results live.

## The atom vocabulary (14 productive atoms)

Derived from the *capability* analysis of §03, not from the operator names.

| Atom | Meaning |
|---|---|
| `world-contact` | the only channel by which new empirical content enters the system |
| `meaning-assignment` | signal → context-relative semantic content (many-to-one, context-indexed) |
| `symbolic-encoding` | commit semantic content to a manipulable structure |
| `relational-linking` | establish a typed relation between two structures |
| `difference-decision` | a verdict over presented alternatives, including `UNDECIDED` |
| `content-generation` | produce content **not** entailed by current content |
| `entailment` | produce content **entailed** under a declared rule |
| `norm-comparison` | compare actual state against a normative target |
| `adversarial-negation` | produce a defeater for a claim |
| `warrant-assessment` | assign a warrant/verdict given evidence + assumptions |
| `state-mutation` | commit to `K_t`, history-preserving |
| `closure-judgment` | inquiry-relative adequacy declaration |
| `preference-over-actions` | order/choose among actions under cost |
| `evidential-qualification` | admit an observation *as evidence* under a policy |

Structural concerns (context, time, uncertainty, assumption, provenance, alternatives) are modelled
as **invariants checked over the reach closure** (§03 C14–C22), *not* as ownable powers. Making them
ownable would have handed one operator a monopoly on, e.g., provenance, and rigged the result.

## Contracts

Common to all: **Input** = carriers required by some derivation rule; **Output** = the derived
carrier; **State effects** = none, except `Revise`; **Information effects** = governed by the data
processing inequality (§ `information_causal`); **Dependencies** = whatever the type rules demand.

| Operator | Atoms | Non-reducible responsibility (claimed) | Corpus support |
|---|---|---|---|
| `Observe` | `world-contact` | the sole channel for empirical content; nothing downstream may create it | STRONG |
| `Interpret` | `meaning-assignment` | attach a **context index** to a signal; the many-to-one step | MODERATE |
| `Represent` | `symbolic-encoding` | make semantic content manipulable without asserting it | WEAK |
| `Relate` | `relational-linking` | typed link between two structures at *either* the representation or the semantic layer | WEAK–MOD |
| `Discriminate` | `difference-decision` | render a verdict over alternatives, incl. `UNDECIDED` (*Buddhi*) | STRONG (as family) |
| `Hypothesize` | `content-generation` | produce content evidence does not entail | WEAK |
| `Infer` | `entailment` | produce content evidence **does** entail, under a declared rule | MODERATE |
| `DetectGap` | `norm-comparison`, `difference-decision` | **two atoms, neither exclusive** | MODERATE |
| `Challenge` | `adversarial-negation` | produce a defeater; the only source of adversarial pressure | MODERATE |
| `Validate` | `warrant-assessment` | assign warrant given evidence + assumptions | STRONG |
| `Revise` | `state-mutation` | the only commit to `K_t`; history preserving | STRONG |
| `Determine` | `norm-comparison`, `closure-judgment` | declare an inquiry adequately resolved | MODERATE |
| `Select` | `preference-over-actions` | choose among actions under an objective/cost | WEAK |
| **`Qualify`** | `evidential-qualification` | admit an observation as evidence under a policy | STRONG **(not in C0)** |

### Shared atoms — the structural heart of the experiment

```
difference-decision  ∈  { Discriminate , DetectGap }
norm-comparison      ∈  { DetectGap    , Determine }
```

`DetectGap` holds **no atom exclusively**. Every other operator in `C0 ∪ {Qualify}` holds at least
one atom that nothing else holds. This is a *declared modelling consequence* of the corpus
definitions (`DetectGap(K_t, I_t)` compares to the Ideal State and decides a difference), not a
result assumed in advance — but it is the assumption most responsible for the headline result, and it
is therefore attacked directly by robustness variant **V4** (§12).
