# 1ay: per-variable support on the A′ datasets (instrument frozen before the 1ax coding exists)

- The instrument is `support_1ay.py` (the docstring is the specification).
- Reviewer corrections from 1ax are applied **only** where machine-readable (`act.f` / `norm.f`); the others are reported, never guessed.
- **Support is never aggregated across the ACT and NORM datasets.**
- The v1 fields reached through lineage are flagged **V1-CODED**: a verdict resting on them inherits v1's epistemic limits.

**Sealed expectation:**
- ACT dataset: every variable NOT-DEMONSTRATED or UNTESTABLE (consistent with F-LOG-0170); at most a WEAK actor;
- NORM dataset: target (about_target) and actor (about_actor) show strict witnesses within the 7-series / START rows (permitted vs prohibited per target), likely **NORM-EMPIRICALLY-SUPPORTED for target** if ≥ 2 disjoint rows exist;
- stated_by is UNTESTABLE or WEAK.

Moderate confidence.
