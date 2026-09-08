# DDD Interpretation

Applied only after source facts are established (per the authorization's own instruction), and only
as an interpretive lens — not as a governance determination.

## Is `nrna1/research/kernel-reduction/` a DDD bounded context?

**No — not evidenced, and this study does not call it one.** A bounded context requires evidenced
boundary *authority* (who decides what belongs) and a stable ubiquitous language enforced across a
body of work. This directory has: no governance record (zero git history — nothing to even locate
an authority claim in); a single, internally-declared purpose statement (the README); and, per
`variants.py`, an explicitly *unsettled* internal vocabulary for at least one of its own central
rules (the `V0` vs. `V6` Verdict-derivation choice). These are the marks of an experimental
instrument's internal design space, not a bounded context's stable ubiquitous language.

## What classification does the evidence support?

**An executable research instrument** — exactly the README's own self-description, which this
study treats as source evidence about intended role (per the authorization's instruction), not as
proof either way of governance status. This matches the classification this project has already
used for the sibling narrative lane's own self-description in MD-026/027 ("a separately organized
research lane," DDD bounded-context authority not established there either) — the executable lane
inherits the same classification, for the same reasons, independently re-derived here rather than
assumed from the narrative lane's own finding.

## Aggregate / value-object / domain-service reading of the `Verdict`-derivation rule itself

If one were to read `carriers.py`'s rule DDD-wise (a further, narrower question than the bounded-
context question above): `Verdict` behaves, in this code, as a **derived value object** — produced
by a pure structural function of its inputs (`Claim`/`Hypothesis` + `Evidence`) and an atom (a
"power" marker), with no identity, no mutable state, and no lifecycle beyond being computed. This
matches, at the role-description level only, MD-029's own `03_p3-source-contract.md` classification
of P-3's `Confidence` property as a "DERIVED VALUE OBJECT... derived from evidence and justification"
— a structural echo worth naming, but explicitly **not** re-opened or re-tested as a composition
claim here (that would be a new Pair-1 composition test, out of scope for this study, per `00`).

## Command binding?

**Not established, and not tested here.** `reach.py`'s composition engine applies operators as
steps in a fixpoint search, not as DDD application-layer commands with an aggregate root — the same
distinction MD-029's own `06_ddd-analysis.md` already drew for the narrative-only version of this
question ("functional correspondence... explicitly not a demonstrated command binding"). This
study's own reading of the executable mechanism does not change that finding; if anything it
reinforces it — `reach.py`'s own docstring states the engine is "independent of operator NAMES,"
which is the opposite of what a DDD command-dispatch mechanism (name-addressed) would require.
