---
source_track: LANE-B
input_artifacts: [KSME-14-BOUNDED-REGIME]
derived_from: [research/knowledgeos-sim/kos12/comp.py, results/comp/*.json]
cross_track_dependency: LANE-B only, not Track-A historical evidence
---

# KSME-14 Phases F/G — Counterexample Catalog (exact, independently re-executed)

All results below were independently recomputed this pass (not merely cited) by importing `kos12.comp`
directly and calling its functions fresh, then diffing against the committed `results/comp/*.json` files.
**Byte-identical match (excluding the `_meta` timestamp) on both `SEP2_order_invariance` and
`SEP4_frame_refinement_invariance`** — genuine, reproducible, deterministic computation, not narrative.

## Counterexample certificate 1 — `C6` (order invariance): `last-wins` fails

```
CounterexampleCertificate {
  criterion: C6 (order invariance, this lane's own)
  rule: last-wins
  state_x: W2_asymmetric_divergence = [+(t=1,c=C1), +(t=2,c=C1), -(t=3,c=C1)]
  state_y: W4_W2_reordered        = [-(t=3,c=C1), +(t=1,c=C1), +(t=2,c=C1)]
  relation: same multiset, permuted order
  operation: agg_last_wins (φ=phi_time_context, status_policy=filter)
  output_x: "negative-support"
  output_y: "positive-support"
  verdict: DIFFERENT — last-wins is not a function of the evidence set, only of its
           enumeration order. FAILS C6.
}
```
All other tested rules (`union`, `majority`, `strict`, `intraframe-only`) are order-invariant on this
witness pair — confirmed exactly, not just `last-wins` asserted to fail in isolation.

## Counterexample certificate 2 — `C7` (frame-refinement invariance): `majority` fails

```
CounterexampleCertificate {
  criterion: C7 (frame-refinement invariance, [PROP], this lane's own)
  rule: majority
  state_coarse: [+(t=1,c=C1), +(t=1,c=C1), -(t=2,c=C1)]   (2 positives share a timestamp -> 2 frames)
  state_fine:   [+(t=1,c=C1), +(t=2,c=C1), -(t=3,c=C1)]   (same 3 items, finer time resolution -> 3 frames)
  content: IDENTICAL evidence, only recording resolution of `time` differs
  operation: agg_majority (φ=phi_time_context, status_policy=filter)
  output_coarse: "unsupported"   (n_frames=2, tie)
  output_fine:   "positive-support"   (n_frames=3, 2 positive frames outvote 1 negative)
  verdict: DIFFERENT — majority's output depends on recording resolution, not evidence
           content alone. FAILS C7. Interpretation offered by the source itself: "reporting
           a property of the CLOCK, not of the evidence."
}
```
`last-wins` and `intraframe-only` are refinement-invariant on this witness (both unaffected); `majority`
uniquely fails among the 3 rules that survive `C1`–`C5`.

## Joint result: only `intraframe-only` survives `C1`–`C7` all together

| Rule | C1–C5 (18/90 combos) | C6 (order) | C7 (refinement) | Survives all 7 |
|---|---|---|---|---|
| `majority` | ✅ (6 of 18 combos) | ✅ | ❌ | No |
| `last-wins` | ✅ (6 of 18 combos) | ❌ | ✅ | No |
| `intraframe-only` | ✅ (6 of 18 combos) | ✅ | ✅ | **Yes** |
| `union`, `strict` | ❌ (0 combos pass all five) | — | — | No |

This is a genuine, exact, computed result — not asserted. It required actually reproducing the sweep, not
merely reading the fork's narrative summary.

## Additional exact facts from the fresh `sweep()`/`criterion_pressure()`/`coupling_evidence()` run

- 90 `(rule, φ, status_policy)` combinations tested exhaustively; exactly 18 satisfy all five base criteria
  (`C1`–`C5`), spanning exactly 3 rules × 2 `φ` variants (`phi_time_context`, `phi_time_context_layer`) ×
  3 status policies.
- `C3_preserves_boundary` is the binding constraint: only 18 of 90 combinations satisfy it, identical to
  the 18 that satisfy all five — every other criterion is satisfied by strictly more combinations
  (`C1`: 72, `C2`: 75, `C4`: 90 — always, `C5`: 63).
- No criterion pair is jointly unsatisfiable (`impossible_pairs: []`).
- `rule_ranking_depends_on_phi = True` — composition and frame qualification are coupled: you cannot choose
  the aggregation rule independently of the frame qualifier (`E-FDE-5`'s own claim, reconfirmed exactly).
- `φ` is non-additive: `phi_time` alone and `phi_context` alone both pass zero combinations; only their
  conjunction `phi_time_context` passes any — neither feature alone suffices.

## What this catalog does not do

Does not select a canonical composition rule for KnowledgeOS. Does not test these constructs against any
Track-A historical object (no bridge exists). Does not claim `intraframe-only`'s survival of `C1`–`C7`
makes it "the" answer — the source's own caveat stands: *"whether refinement-dependence is DISQUALIFYING is
a judgement, not an experimental result."*
