# DDD Composition Analysis

For each pair, the aggregate-side and operator-side DDD fields, and whether the operator can act on the
aggregate without violating either side's own stated semantics.

## Pair 1 (`Validate` ↔ P-3)

| DDD field | Aggregate side (P-3) | Operator side (`Validate`) |
|---|---|---|
| Entity identity | implied, one per claim | n/a |
| Aggregate boundary | 6 named fields | n/a |
| Invariant | "one confidence value per claim" (tested, breaks under shared evidence) | none stated |
| Command semantics | none | `Validate` itself, unspecified effect |
| Consistency boundary | the aggregate itself | none stated |

**Terminology-level compatibility only** — `Validate`'s name suggests it could plausibly check P-3's
own invariant, but this study explicitly declines to treat "the name suggests a check" as evidence of
an actual DDD command relationship, per the authorization's own explicit prohibition against
terminology-based equivalence (§18).

## Pair 2 (`S^epi` ↔ A's tuple)

| DDD field | Aggregate side (A) | Operator side (`S^epi`) |
|---|---|---|
| Entity identity | none stated | n/a |
| Fields available as command input | `Evidence`, `Question` (literal name match to `S^epi`'s `E`, `Q`) | `E`, `C`, `Q` (stated signature) |
| Command output | `Assessment` (literal name match) | `A` (stated output) |
| Missing | a `Context` field | — |

**This pair reaches beyond terminology-level compatibility** — the field *names themselves*, not
merely a plausible-sounding verb, align with `S^epi`'s own stated argument names. This is still not a
demonstrated DDD command relationship (A's own evidence never states that its `Assessment` field is
*produced by* an operation resembling `S^epi` — the alignment is structural resemblance, not a stated
command binding), but it is the closest this study's evidence comes to a genuine candidate.

## Pair 4 (B's contradiction line ↔ `ConflictRecord`)

| DDD field | Aggregate side (P-5) | Process side (B's §J) |
|---|---|---|
| Field/output | `ConflictRecord`, single unstructured slot | Standing+Boundary, 4-component tested structure |
| Consistency requirement | none stated | tested: flat representations provably lose required distinctions (M0120, 7/12 score) |

**A genuine DDD-relevant constraint, not merely an analogy**: B's own tested result constrains what
*any* adequate `ConflictRecord`-like field could be — it cannot remain single/flat if it must preserve
the same distinctions B's own criterion checks for. This is the one pair where the operator/process
side's own evidence has direct, evidence-based bearing on the aggregate side's own design adequacy,
without requiring either side to be reconstructed.

## Summary

Two of four pairs (1, 3) remain terminology-level or under-specified. Two (2, 4) reach a genuine, if
partial, DDD-relevant finding: Pair 2 shows a real (if unconfirmed) structural field-name alignment;
Pair 4 shows B's own tested result directly constrains what an adequate aggregate-side field would need
to look like. **Neither constitutes a demonstrated command binding or coherent composition** — both are
evidence *consistent with* the complementarity hypothesis without confirming it.
