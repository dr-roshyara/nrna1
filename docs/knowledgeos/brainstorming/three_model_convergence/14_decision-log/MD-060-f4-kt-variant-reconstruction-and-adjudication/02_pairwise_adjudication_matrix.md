# MD-060 §02 — Pairwise Semantic Adjudication (Phase B)

**Ladder**: IDENTICAL / FORMALLY EQUIVALENT / STRUCTURAL CORRESPONDENCE / REFINEMENT-PROJECTION /
FUNCTIONAL ANALOGY / PARTIAL CORRESPONDENCE / INCOMPATIBLE / UNRESOLVED. Default: UNRESOLVED. Per
the authorizing prompt's own instruction, not every one of the `C(12,2)=66` pairs is forced — grouped
first by structural type (a DDD/mathematical typing move consistent with MD-058's own discipline),
then adjudicated within and across clusters at the pairs that are actually decidable or informative.

## Structural clusters (typing pass, before any equivalence claim)

| Cluster | Members | Shared shape |
|---|---|---|
| **P — Probabilistic** | V1, V3 | a function/graph from a proposition space to `[0,1]` |
| **T — Flat tuple** | V2a/b, V4b, V5, V6a/b, V7 | fixed-arity tuples of named, mostly-untyped sub-objects, arity 4–11 |
| **A — Deliberately abstract** | V4a | `K_t∈𝕂`, no fixed type by design |
| **G — Relational/graph** | V8 | dimensions + relations, not a flat list |
| **M — Meta-distinction** | V6-meta | not a structure of `K_t` itself — a claim about whether `K_t` is primary or derived |

**No pair across clusters P/T/A/G reaches even STRUCTURAL CORRESPONDENCE without an invented bridge**
— this is the census's own central adjudication finding, detailed below.

## Within-cluster pairs

### V1 ↔ V3 (both Cluster P)

`K_t={cr_t(P):P∈ℒ}` (V1) vs. `K_t={(s_i,p_i)}, p_i=P(s_i|E_t)` (V3).

A credence function's graph IS, by definition, a set of (argument, value) pairs — `cr_t` restricted
to a finite/enumerable `ℒ` and `{(s_i,p_i)}` with `s_i` ranging over the same `ℒ` are extensionally
the same mathematical object, **conditional on**: (a) `ℒ` and `{s_1,...,s_n}` denoting the same
proposition set — asserted by context, never formally shown; (b) `cr_t(P)` and `p_i=P(s_i|E_t)`
denoting the same value — V1 never conditions on evidence explicitly, V3 does (`|E_t`), so the two
are only equal if `cr_t` is itself understood as already-conditioned. **Verdict: FORMALLY EQUIVALENT,
CONDITIONAL** on two disclosed, unverified assumptions — not full identity, and not merely
"functional analogy," since the equivalence (if the assumptions hold) is exact, not approximate.

### V2a ↔ V2b (same file, Cluster T)

`KnowledgeState(O,S,E,C,G,t)` vs. `(k_{1,t},...,k_{n,t})`. **Verdict: STRUCTURAL CORRESPONDENCE** —
same author, same file, presented in immediate succession as restatements of one intention (a
function-application form and a positional-tuple form) — genuine evidence of intended sameness,
though never formally proven (no stated bijection between the 6 named arguments and the `n` indexed
positions — `n` is not even fixed at 6 in the tuple form). This is the same notation-drift pattern
already found for step-261 (MD-057/059) — recurring for a third time in this reconstruction, now in
a different candidate family.

### V6a ↔ V6b (same file, Cluster T)

`(Claims_t,Evidence_t,Arguments_t,Standing_t)` vs. `(C_t,E_t,A_t,H_t,S_t,R_t)`. The author explicitly
states the second is a refinement ("I would go one level deeper") of the first — **Verdict:
REFINEMENT/PROJECTION, ASSERTED** — a directional claim (6-component refines 4-component) stated by
the same author in the same breath, but with no explicit component-to-component map shown (which of
the six new components refines which of the original four is not stated).

### V7's `Σ_t` vs. any other variant's components

`Σ_t=(A,S,R,V,C)` is the only fully-typed component in the entire census. No other variant names a
component that is stated to correspond to it. **Verdict: UNRESOLVED** for every cross-comparison —
this is not a gap in this adjudication, it is the corpus's own gap.

## Cross-cluster pairs (the informative negative result)

### V1/V3 (Cluster P) ↔ V7 (Cluster T, most-typed tuple member)

Attempted bridge: does V7's `Σ_t=(A,S,R,V,C)` determine, or is it determined by, V1/V3's probability
assignment `cr_t`/`p_i`? **No.** `Σ_t`'s own fields are **categorical** (`Acquisition∈{Observed,
Reported,Inferred,Calculated,Assumed,Hypothesized,Unknown}`, `Support∈{None,Weak,Moderate,Strong,
VeryStrong}`, etc.) — nominal/ordinal values, not probabilities. No stated map converts a categorical
`Support` value into a `[0,1]` credence, or vice versa. **Verdict: INCOMPATIBLE at the tested
component level** (not "unresolved due to missing information" — a specific, named type mismatch is
demonstrated: categorical vs. probabilistic value domains, with no corpus-stated conversion). This is
the concrete obstruction Phase D (`04`) uses to partially falsify the semantic-core hypothesis.

### V4a (Cluster A) ↔ everything

V4a is **deliberately** not committed to a structure — by its own text, it "resolves the earlier
conflict" precisely by declining to choose. It cannot be adjudicated FORMALLY EQUIVALENT or
STRUCTURAL CORRESPONDENCE to any concrete variant (there is no structure to compare), but it also
cannot be called INCOMPATIBLE (it imposes no constraint any concrete variant could violate).
**Verdict: UNRESOLVED, by design** — the corpus's own most honest position in the census, not a
finding this adjudication should try to sharpen further.

### V8 (Cluster G) ↔ Cluster T (any flat tuple)

V8 is explicitly proposed as a *refinement of the flat-tuple family in general*
(`K=(d_1,...,d_n)→K=(D,R)`) — a genuine, stated directional claim, but pitched at the level of "flat
tuples in general," not any *specific* named variant (V2b/V5/V6a/V6b/V7 are never individually
cited). **Verdict: FUNCTIONAL ANALOGY** — the methodological *move* (add relations to a flat
component list) is exactly the kind of refinement any Cluster-T variant *could* undergo, but none is
shown to have actually undergone it. Applying V8's own relational upgrade to, say, V7's 11 components
would be a **new construction this census does not perform** (it would itself be an uninvited
modelling choice).

### V6-meta ↔ everything

V6-meta's own claim (`K_t` is *extracted from* a richer `E_t`, not primary) is **orthogonal to every
other variant's own stance** — none of V1–V5/V7/V8 addresses whether `K_t` is primary or derived; all
simply write `K_t=(...)` as if `K_t` is the ground object. **Verdict: UNRESOLVED for all pairs**, but
flagged as a **structural question no other variant even poses**, not merely an unadjudicated
similarity.

## Summary tally

- 1 pair **FORMALLY EQUIVALENT, CONDITIONAL** (V1↔V3).
- 2 pairs **STRUCTURAL CORRESPONDENCE / REFINEMENT-PROJECTION, ASSERTED not proven** (V2a↔V2b,
  V6a↔V6b).
- 1 pair **FUNCTIONAL ANALOGY** (V8 ↔ Cluster T, generically).
- 1 demonstrated **INCOMPATIBLE** finding at the component level (V1/V3 ↔ V7's `Σ_t`) — the single
  most consequential result of this phase's adjudication.
- **All remaining pairs default to UNRESOLVED** — not a failure of this adjudication, but the
  corpus's own honest state: **no variant anywhere claims, let alone demonstrates, equivalence with
  any variant from a different structural cluster.**
