# MD-075 §05 — GAP-007 Adjudication: `Req(r)⊆Witness(r)` vs. F4 `Req(EC_t)`/`r`

## Method

A narrow, direct comparison per the mission's §6 — `step_186_mathematical-validation-of-the-knowledge-
state-model.md` (`phase_measure_theory/`, 2026-08-29 02:12) read in full (1,428 lines), against F4's
own canonical `Req(EC_t)`/`r` (`[00-47]` `[DEF-21]`, T5; `[00-51]` T7's own 7-field structured `r`).

## What `step_186` actually says (§186.21–186.24, verbatim structure)

`step_186` is developing a **transition-admissibility** formalism, not a **requirement-satisfaction**
formalism. Its own chain of definitions:

- `KS_i → KS_j` is a **transition** between two knowledge-state snapshots.
- `Witness(KS_i,KS_j)` is the **evidence/authority/temporal/governance witness** supporting that
  specific transition — later typed `W=(W_E,W_T,W_A,W_G)`.
- For a transition `r` (the letter `r` here denotes **the transition itself**, e.g. "Observation,"
  "Correction," "Determination," "Governance Decision," "Recommendation" — §186.22 lists five named
  transition *kinds*, each requiring a different witness subset), `Req(r)` is defined as **the
  required witness set for admitting that transition**: §186.23, boxed, *"Let transition `r` have
  required witness set `Req(r)`. Then: `TransitionAllowed(r) ⟺ Req(r)⊆Witness(r)`."*
- The payoff claim, §186.24: *"This begins to explain why the Kernel could be small"* — `Req(r)⊆
  Witness(r)` is being used to argue for a **minimal Kernel boundary** (an admission gate that checks
  witness-sufficiency, not semantic truth), not to define requirement-satisfaction for an epistemic
  contract.

## Direct comparison against F4

| | `step_186` | F4 (`[00-47]`, `[00-51]`) |
|---|---|---|
| What `r` denotes | a **transition** (an event/state-change being validated) | a **requirement** (an element of `Req(EC_t)`, a thing a knowledge state must satisfy) |
| What `Req(...)` maps FROM | a transition | an epistemic contract `EC_t` |
| What `Req(...)` maps TO | a set of **required witness types** (`W_E`, `W_T`, `W_A`, `W_G` — evidence/temporal/authority/governance witness *kinds*) | a set of **requirements** (each itself a structured object, per T7's own 7-field `r`) |
| What the containment test decides | whether a **transition** has enough **witnesses** to be admitted | whether a **knowledge state `K`** satisfies a **requirement `r`** (via `Sat(K,r)`, a wholly separate predicate `step_186` never mentions) |
| Codomain of the overall test | `Allowed`/`Rejected` (a transition-admission boolean) | `Δ_t={r∈Req(EC_t):¬Sat(K_t,r)}` (a set of unsatisfied requirements, not a single boolean) |

## Adjudication (six-level ladder)

1. **Lexical**: yes — `Req(`, `r`, and a containment/subset test all appear in both.
2. **Conceptual**: weak — both formulas express "an admission gate defined by containment of a
   required set within an available set," a very general pattern (this is, in fact, the shape of any
   precondition-checking formalism — closer to a design pattern than a shared domain concept).
3. **Functional**: **demonstrated distinct, not merely unresolved**. `step_186`'s `r` is the *subject
   being validated* (a transition); F4's `r` is *what the subject is validated against* (a
   requirement, consumed by `Sat`). These are not interchangeable roles — swapping one lane's `r` into
   the other's formula produces a type error, not a substitution.
4. **Structural**: distinct codomains (`Req(r)⊆2^{WitnessTypes}` vs. `Req(EC_t)⊆2^{Requirements}`),
   distinct downstream consumers (`Witness(r)`, an availability check, vs. `Sat(K,r)`, a satisfaction
   predicate over a knowledge state).
5. **Formal equivalence**: not established, and the functional/structural mismatch makes it unlikely
   any such equivalence exists without inventing a conversion — which this adjudication does not do,
   per the mission's own explicit prohibition (§6: *"Do not invent a conversion function... record
   `BRIDGE REQUIRED — NOT CORPUS-DEFINED`"*).
6. **Demonstrated identity**: no.

## Verdict

**`HOMONYM` — evidence-grounded, not merely a name-match flag.** Closer reading *weakens* rather than
strengthens the apparent connection MD-072's own Level-1 census surfaced: the two `Req`/`r` usages
occupy structurally different grammatical positions in their respective formalisms (subject vs.
predicate-argument). If a genuine relationship were later found (e.g., a document explicitly deriving
one from the other), it would require an explicit `BRIDGE REQUIRED — NOT CORPUS-DEFINED` construction
step — none exists in the corpus today, and this adjudication does not supply one.

## What this does not do

Does not merge `step_186`'s `Req`/`r`/`Witness` with F4's `Req(EC_t)`/`r`/`Sat`. Does not assert
`step_186`'s own formalism is wrong or inferior — it is a coherent, self-contained transition-
admissibility argument that happens to reuse common mathematical-logic letter choices (`Req`, `r`, a
containment test) also used elsewhere in the corpus for an unrelated purpose. Does not close `EKS-45`
(the broader bare-notation-collision pattern) — it resolves one of the three specific instances that
pattern names, with a sharper verdict than the other two currently carry.
