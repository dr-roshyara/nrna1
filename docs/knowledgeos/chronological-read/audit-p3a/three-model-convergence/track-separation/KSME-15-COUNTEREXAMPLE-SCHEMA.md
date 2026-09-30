---
source_track: SEMANTIC-NEUTRAL
derived_from: [.claude/scripts/knowledgeos-ksme/bse.py]
cross_track_dependency: none
---

# KSME-15 — Counterexample Certificate: Formal Schema

```python
CounterexampleCertificate {
    abstraction: str            # name of the abstraction/rule being tested
    state_x: Any                # first witness state
    state_y: Any                # second witness state
    abstraction_x: Any          # F(state_x) or equivalent grouping key
    abstraction_y: Any          # F(state_y)
    operation_sequence: tuple   # the exact operation+context sequence applied
    observation: str            # which observation detected the divergence
    output_x: Any               # observed output for state_x's trace
    output_y: Any               # observed output for state_y's trace
    violated_property: str      # "congruence" | "behavioral sufficiency" |
                                 # "necessity (over-distinguishes)" |
                                 # "behavioral equivalence" | caller-defined
}
```

Every certificate is a first-class, inspectable artifact — never a bare boolean. Four instances produced
and independently verified this pass:

1. `last-wins` fails `C6` (order invariance) — Lane-B, via `ksme15_laneb_validation.py`.
2. `majority` fails `C7` (frame-refinement invariance) — Lane-B, via the same script.
3. Naive coarse observation fails congruence in synthetic system `S3` — exact witness `A` vs `B`,
   diverging to `X`/`Y` after `step`.
4. `π_b_only` fails behavioral sufficiency in the minimality validation — exact witness `(0,0,0)` vs
   `(1,0,0)`, both `π_b_only=0` but `observe_a` outputs `0` vs `1`.

No certificate in this pass was asserted without the corresponding code producing it as a return value —
every "fails" claim above is backed by a `to_dict()`-serializable object, reproducible by re-running the
named script.
