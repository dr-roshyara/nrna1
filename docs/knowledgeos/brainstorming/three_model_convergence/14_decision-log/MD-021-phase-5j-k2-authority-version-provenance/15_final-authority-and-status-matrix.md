# Phase 5J — Final Authority and Status Matrix (required, per the authorization's §21)

| Object | Definition | Provenance | Authority | Version | Supersedes | Superseded by | Compatibility | Status |
|---|---|---|---|---|---|---|---|---|
| K-2 top-level | `K=(𝒜,ℛ)` | Undetermined single origin | None | Single, stable | — | — | N/A (undisputed) | **CURRENT / RESEARCH** |
| D1/D3 Assertion | 6-field conceptual list | Undetermined | None | Unclear | — | — | Not compatible with D2/D4 | **COMPETING** |
| D2/D4 Assertion | 3+4-field mixed list | Undetermined | None | Unclear | — | — | Not compatible with D1/D3 | **COMPETING / CONTRADICTED** (self-contradicted by its own implementing script's docstring) |
| D5 Assertion (`e_equality.py`) | 5-param + derived `id` | Cites Step 246 | None | Unclear | — | — | Narrower scope, not directly comparable | **IMPLEMENTATION / RESEARCH** |
| `Qualify` (4 variants) | 4 incompatible arities | Step 049, Step 170, D285-6, Step 272A | Step 272A's own status label only | Unclear | — | — | Not mutually compatible | **COMPETING** |
| `id` | Peer field (prose) / derived hash (code) | — | None | — | — | — | **Compatible** (`10`) | **IMPLEMENTATION (compatible refinement of prose)** |
| `Π`/`π` | Policy (2 sources) vs. provenance-shaped (1 source) | — | None | — | — | — | Not resolved | **UNRESOLVED** |

## Twelve required answers

1. **Is there one authoritative K-2 definition?** No.
2. **Is there a demonstrated K-2 source of truth?** No, for `Assertion`'s own field structure (`14`).
   Yes, for K-2's top-level `(𝒜,ℛ)` shape, in the weak sense of unanimous, undisputed agreement — but
   not in the strong sense of a ratified governance act (K-2 as a whole remains `NOT RATIFIED` per
   D285-1's own K-1..K-7 table).
3. **Are D285-1 and D285-6 versions of one object?** Not demonstrated — no revision, migration, or
   supersession language connects them.
4. **Does either supersede the other?** No.
5. **Is either merely a research branch?** Both are best characterized as independent research
   branches, per `08`'s own "competing hypotheses" finding — neither is demoted below the other.
6. **Is either executable artifact authoritative?** No — neither carries a governance marker, and one
   (`t285_reconcile.py`) is shown internally inconsistent with its own nominal source document.
7. **Is `Π` resolvable?** No — remains `UNRESOLVED` with 3 sources now considered (`09`).
8. **Is `id` resolvable?** **Yes** — the one genuinely resolved sub-question in this phase (`10`).
9. **Is `Qualify` versioned or competing?** Competing — 4 incompatible arities, no version relation
   demonstrated (`11`).
10. **Can the two K-1→K-2 projections now be related?** No — `12`'s own mathematical-compatibility
    testing found no bijection, injection, surjection, embedding, quotient, or lossless encoding
    connecting the two underlying Assertion characterizations that the two projections target.
11. **Does any stronger K-1→K-2 equivalence become justified?** **No** — if anything, this phase
    further narrows confidence, since the two most plausible reconciling documents (Step 272A/272B)
    turned out not to address the conflict at all.
12. **What remains genuinely unresolved?** The Assertion field-set conflict itself; `Qualify`'s
    arity/algorithm; `State`'s carrier; the `Π` denotation; whether $\pi_1$/$\pi_2$ are reconcilable.
