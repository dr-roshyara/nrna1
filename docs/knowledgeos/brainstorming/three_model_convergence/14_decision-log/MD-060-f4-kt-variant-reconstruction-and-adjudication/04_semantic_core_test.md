# MD-060 §04 — Semantic-Core Falsification Test (Phase D)

## Hypothesis under test

> The competing `K_t` variants are different representations of one underlying semantic state.

**Not assumed. Attempted to falsify, per the authorizing prompt's own instruction.**

## Falsification attempt

**Candidate counterexample**: V1/V3 (Cluster P, probabilistic: `K_t` as a credence function/set of
(sentence,probability) pairs) vs. V7 (Cluster T, the census's most-typed tuple member, via `Σ_t=
(A,S,R,V,C)`, all categorical fields).

**Constructed test**: is there a map `φ: V1-object → V7-object` (or its inverse) that preserves the
information either side actually carries?

- **V7 → V1 direction**: `Σ_t`'s `Support∈{None,Weak,Moderate,Strong,VeryStrong}` is an ordinal
  category. No corpus source states a function converting this five-value ordinal into a `[0,1]`
  credence — and even if one were invented (e.g. an arbitrary five-point scale), the choice of scale
  would itself be exactly the kind of ungrounded modelling decision this phase must not make.
- **V1 → V7 direction**: a credence value `cr_t(P)=0.73` does not determine `Σ_t`'s other four
  fields (`Acquisition`, `Resolution`, `Validity`, `Conflict`) at all — these track provenance/
  process facts a bare probability number cannot encode (was `P` observed or inferred? is it stale?
  is it in conflict with another claim?).

**Result: FALSIFIED for the strong form of the hypothesis** (full mutual information-preserving
inter-translatability) — a **specific, demonstrated obstruction** exists between at least two
variants in the census, not a vague sense of "they look different." This is exactly the kind of
concrete counterexample the authorizing prompt's own Phase D asks for, not an inferred impossibility.

## What survives, precisely bounded

A **weak common core** is not falsified by the above, and is genuinely supported across the census:

- **Time-indexing**: every one of the 12 variants is written `K_t` (or `K` with an implicit/explicit
  time argument) — universal across the entire family, no exception found.
- **Purpose/intent as an "epistemic state" referent**: every variant's own surrounding prose
  describes `K_t` as representing *some participant's knowledge/belief/epistemic condition at time
  t* — a shared INTENTIONAL claim, stated in every source, though never formalized as a shared
  mathematical property (it is a claim about what the symbol is *for*, not what structure it *has*).

**Neither of these two survivors is a mathematical equivalence** — they are shared conventions
(a notational habit, and a shared informal gloss), not a demonstrated formal core. **This is the
honest, bounded result**: the hypothesis is neither fully confirmed nor fully refuted — it is
**falsified in its strong form, and reduced to a weak, non-formal residue in its surviving form.**

## Smallest demonstrated obstruction, stated for the record

> **A categorical provenance/status field (`Σ_t`'s own components, V7) and a probabilistic credence
> assignment (V1/V3) cannot be inter-derived without an invented conversion — this is the concrete,
> minimal counterexample blocking the strong semantic-core hypothesis.**

This does not prove NO common core could ever be constructed (a sufficiently rich, invented framework
might subsume both) — it proves the corpus, as it stands, does not supply one, and inventing one is
explicitly out of scope for this phase.
