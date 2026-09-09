# MD-061 §01 — Variant Selection, Scored Against Explicit Criteria

## The six criteria, applied to every serious candidate from MD-060's own 12-variant census

Scoring only variants with a plausible claim (probabilistic variants V1/V3 and the deliberately
abstract V4a are excluded up front — V1/V3 have no stated relationship to `Sat`/`Req` at all; V4a
has no components for any `Sat` to consult).

| Variant | Source-defined component semantics | Rel. to requirements | Rel. to `Sat` | Typing completeness | Provenance quality | Testability |
|---|---|---|---|---|---|---|
| **V2a/b** (M0006) | names only | none stated | none stated | none | single-author, standalone | LOW |
| **V4b** (M0043, 10-comp.) | names only | **HIGH** — same document defines `Req(EC_t)` a few sections later | **HIGH** — same document, same continuous derivation, `Sat(K,EC_t)` at `[DEF-20]` right after §14's own component list | **NONE** — no component typed | HIGH — single primary-authored, `[DEF]`-tagged document | LOW-MODERATE — every predicate would need an invented threshold, since nothing is typed |
| **V5** (M0048, 5-comp.) | names only | MODERATE — reuses `EC_t`/`Req` without redefining them | MODERATE — proposes its own `Sat(K,r)`, but §00 §2's finding shows this is a same-day *review* of M0043, not an independent definition | NONE | MODERATE — a review document, not the origin | LOW |
| **V6a/b** (M0076) | names only | none stated | none stated | none | single-author, standalone | LOW |
| **V7** (`Σ_t`, M0125/M0126) | **the only fully enumerated component in the entire census** | MODERATE — connects to `Δ_t`/`Sat` only via M0132, a third, later file | MODERATE — same reason | **HIGH, for `Σ_t` specifically** — `A/S/R/V/C` each carry a stated enumerated domain | MODERATE — a three-file chain (define → ratify), not one continuous document | **HIGH** — the only variant where a predicate can be written without inventing a value domain |
| **V8** (M0287) | relation-typed (`R(d_i,d_j\|Q)`) but no concrete domain given | none stated | none stated | LOW | single-author, standalone | LOW |

## The decisive trade-off

**V4b wins on textual/provenance proximity to `Sat`'s own definition** (same document, same
continuous derivation) — a real, disclosed strength. **V7 wins on typing completeness** — the one
criterion that most directly determines whether a `Sat*` predicate can be *written* at all without
inventing a value domain from nothing.

**Selected: V7, via its `Σ_t` sub-structure.** Justification, weighed explicitly against the
authorizing prompt's own instruction not to select "because it appears closest to a desired theory":
the selection is driven by **testability and typing completeness**, not by which variant is
textually closer to `Sat`'s own definition. A `Sat*` built on V4b would have to invent a value domain
for every one of its ten components before writing a single predicate — that is not a defensible
minimum threshold for a *construction* task (as opposed to a *signature* task, which V4a/V4b already
satisfy without this phase's help). V7's `Σ_t` is the one place in the whole 12-variant census where
this phase can write a predicate using **only** corpus-stated structure.

## Explicit disclosure of what this selection does NOT establish

- Does not claim V7 is F4's canonical `K_t`.
- Does not claim `Σ_t` is representative of `K_t`'s other ten V7 components, or of any other
  variant's own components.
- Does not resolve MD-060's own finding that V7 and V4b/V4a are never shown related — this
  construction uses V7 **as the test carrier only**, per the authorizing prompt's own §1 instruction.
