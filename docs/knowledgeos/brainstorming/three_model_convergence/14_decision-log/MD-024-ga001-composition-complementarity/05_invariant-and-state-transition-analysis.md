# Invariant and State-Transition Analysis

Applicable only where testable per `03`/`04`.

## Pair 1

P-3's own tested invariant ("one confidence value per claim") is known to **break** under a specific
condition (evidence shared across multiple claims, seq 0157). Since `Validate`'s own effect is
unspecified, whether `Validate` would *detect* this break, *prevent* it, or be *unaffected* by it
cannot be determined. **Untestable — not a negative result, an absence-of-specification result.**

## Pair 2

No invariant is stated for A's own 8-field tuple anywhere in A's register — there is nothing to test
preservation against, even though the input/output field-name alignment (`04`) is genuinely present.
**Untestable for invariant preservation specifically; testable, and positive, only for the narrower
field-name-alignment claim already reported in `03`/`04`.**

## Pair 4

The one closure-relevant fact available: B's own M0120 result is itself an invariant-preservation
result **within B's own evidence** (structured evaluation scores 12/12 on required-distinction
preservation; flat representations score 7/12, failing identically on the same 7). This does not test
whether composing B's structure *into* C1's own aggregate preserves anything — it establishes only that
B's own structure, standing alone, preserves more than a flat field would. **Genuinely testable and
positive, but for a within-Model-B claim, not a cross-model composition claim** — recorded precisely to
avoid the exact error the authorization warns against (letting an intra-model result be smuggled in as
a cross-model composition finding).

## Determinism / non-determinism

Not statable for any pair — no operator's own evidence specifies whether its effect is deterministic.

## Well-definedness

Fails by default for Pairs 1 and 3 (no defined result for any input). Pair 2 is well-defined only for
the subset of inputs where `C` (context) can be supplied — which this study does not supply. Pair 4
is well-defined only within B's own evidence, not as a cross-model composition.
