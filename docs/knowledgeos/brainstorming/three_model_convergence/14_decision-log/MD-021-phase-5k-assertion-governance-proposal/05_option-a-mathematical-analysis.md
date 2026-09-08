# Phase 5K — Option A: Preserve D1/D3

$$Assertion = \{Proposition, Entity, Evidence, Context, Time, Provenance\}$$

## Mathematical evaluation

- **Primitive set**: 6 named conceptual fields.
- **Domain/codomain**: not stated by the source; this option inherits that absence.
- **Operators**: none defined for `Assertion` itself under this option (K-2's own `𝒪_sem`, from Step
  272A, remains a separate, unresolved question regardless of which Assertion schema is chosen).
- **Invariants**: none stated.
- **Equality relation**: `t285_reconcile.py`'s own machinery (structural/semantic/observational,
  Phase 5H `05`) already tests this schema against K-1, so an equality apparatus already exists for
  this option specifically.
- **Projection**: this is exactly $\pi_1$ (Phase 5I `09`) — **already reconstructed**: `Entity`,
  `Proposition`, `Relation` preserved; `State` undefined target; `Event/Policy/Action` dropped;
  **`Observation` has NO target field at all under this option** — not merely blocked on `Qualify`,
  genuinely absent from the schema.
- **Information preservation**: worse than Option B specifically regarding `Observation` — under
  Option A, there is no field to eventually receive a qualified observation at all, meaning `Qualify`'s
  own output type (`Evidence`, per seq 0630 §49.76) would need to map onto this schema's own existing
  `Evidence` field — **this is actually a point in Option A's favor**: its own `Evidence` field is
  already named, so `Qualify`'s conceptual output (`Evidence=QualifiedObservation`) has a natural home.
- **Consistency**: internally consistent (matches its own paired executable script, `t285_reconcile.py`
  — modulo `03`'s own Phase-5I-confirmed intra-script T-A/T-C inconsistency, which is a property of the
  *script*, not of this schema definition itself).
- **Completeness**: incomplete — no operator set, no `State` target, no `Observation` field.
- **Closure**: not established.
- **Computability**: no algorithm for `Evidence`'s own population (no `Qualify` body exists regardless
  of schema choice).

## What is lost under Option A

`Observation` as an addressable field (though its *output*, `Evidence`, is retained); `id` (D1/D3 never
mentions an identifier at all — `e_equality.py`'s own hash-based `id`, Phase 5I `10`, has no home under
this schema); the `c`/`t`/`Π` technical-field framing D2/D4 supplies.

## What is preserved

The clean conceptual naming (`Evidence`, `Context`, `Time`, `Provenance` as directly legible English
terms, not abbreviated symbols); the `π_1` projection's own already-analyzed structure; consistency
with `t285_reconcile.py`'s own genuinely-computed set arithmetic (setting aside that script's own
separate internal defect).
