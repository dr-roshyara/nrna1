# Phase 5F — Shared-Primitive Semantic Audit (Entity, Proposition, Relation)

Per the authorization's §7, each of the three names D285-1 found "shared by name" between K-1 (ratified
`K_t`) and K-2 (verification lane) is audited **independently**, using the corpus's own D285-1/D285-6
raw text (both read in full this phase) plus seq 0630's own derived-structure definitions.

## Entity

| Dimension | K-1 (ratified) | K-2 (verification lane) | Evidence |
|---|---|---|---|
| Lexical name | `Entity` (`E`) | `Entity` (inside `Assertion`'s expansion) | D285-1 §2; seq 0630 §49.30 |
| Definition | One of 8 core primitives; `Identity: E→ID` (a derived function over it) | A field *inside* the `Assertion` composite, not a top-level primitive of `(𝒜,ℛ)` itself | D285-6 §3 ("`Entity` → `E` inside `P`" — i.e. inside Proposition, itself inside Assertion) |
| Type/category | Top-level primitive | Nested field, two levels down (`Assertion` → `Proposition` → `Entity`) | D285-6 §3 |
| Role | Foundational, load-bearing across the whole ratified state | Present only via unpacking; not independently addressable in `(𝒜,ℛ)`'s own surface API | D285-1 §2, D285-6 §3 |
| Provenance | seq 0630 | Inherited via the projection `π_K`, not independently sourced | — |
| Context | Ratified governance layer | Assertion-composite, verification-lane research track | — |
| **Semantic verdict** | **STRUCTURALLY DISTINCT POSITION, SAME LEXICAL LABEL** — the name is shared, but `Entity` occupies a top-level role in K-1 and a nested, nominal role in K-2 | | **PARTIAL** — not `IDENTICAL`, not `DISTINCT` either (the corpus's own semantic-equality result in D285-6 §5 treats it as correctly mapped under `π_K`, just at a different structural depth) |

## Proposition

| Dimension | K-1 (ratified) | K-2 (verification lane) | Evidence |
|---|---|---|---|
| Lexical name | `Proposition` (`P`) | `Proposition` (a direct field of `Assertion`) | D285-1 §2; D285-6 §3 |
| Definition | One of 8 core primitives; `Claim ⊆ P` (a derived subset) | A direct, first-level field of the Assertion composite | seq 0630 §49.31; D285-6 §3 |
| Role | Foundational; the carrier for `Claim` | The semantic payload of an assertion | — |
| **Semantic verdict** | **CLOSEST CORRESPONDENCE OF THE THREE** — `Proposition` maps directly (one unpacking step, not two) and plays a structurally similar carrier-of-claims role in both | | **STRUCTURAL CORRESPONDENCE** — the strongest of the three shared names, still not confirmed as formal equivalence (no explicit isomorphism proof for `Proposition` specifically was found, only its membership in the overall projection map) |

## Relation

| Dimension | K-1 (ratified) | K-2 (verification lane) | Evidence |
|---|---|---|---|
| Lexical name | `Relation` (`R`) | `Relation` (`ℛ`, a direct top-level component of the 2-tuple itself) | D285-1 §2; D285-1 §1 (`K=(𝒜,ℛ)`) |
| Definition | Typed relations; `Prov ⊆ R`, `Cause ⊆ R` (derived subsets) | The second of only two top-level components — carries the entire relational burden of the verification lane's own model | seq 0630 §49.31; D285-1 §1 |
| Role | One of 8 co-equal primitives | **One of only 2** — structurally far more load-bearing in K-2 than in K-1, since K-2 has nothing else to carry relational content | — |
| **Semantic verdict** | **NAME-IDENTICAL, ROLE-ASYMMETRIC** — `Relation` is a minor player among 8 in K-1 but half of the entire model in K-2 | | **PARTIAL CORRESPONDENCE** — same lexical name, structurally very different weight; this is the primitive where "same name ≠ same concept" is most visible, since a name carrying 1/8 of one model's content and 1/2 of another's cannot be treated as playing "the same role" without qualification |

## Conclusion

**All three shared names resolve to PARTIAL or STRUCTURAL correspondence — none to formal equivalence
or demonstrated identity, and none to full lexical-only coincidence either.** This confirms, primitive
by primitive, D285-1's own Section 5 finding: *"the matrix establishes vocabulary disjointness, not
conceptual disjointness... conceptual identity is a separate, untested claim."* This phase's own
independent, per-primitive audit reaches the same qualified conclusion the corpus's own research
already reached, via a finer-grained (per-primitive, not per-model) analysis than D285-1/D285-6
performed.
