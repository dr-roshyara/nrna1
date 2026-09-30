---
source_track: SEMANTIC-NEUTRAL
derived_from: [.claude/scripts/knowledgeos-ksme/bse.py, ksme15_synthetic_validation.py]
cross_track_dependency: none
---

# KSME-15 — Partition/Refinement Engine: Exact Validation

## Algorithm

`Regime.behavioral_partition()`: start from the observational partition `Π₀`; at each round, split every
class whose members disagree on their operation-signature (for every `(T,c)`, which target class the
post-state falls into); repeat until a round produces no split (`Πₙ₊₁=Πₙ`). Terminates because `E` is
finite (partitions strictly refine or stop).

## Validation against three independent synthetic systems with known ground truth

| System | Design | Expected | Computed | Match |
|---|---|---|---|---|
| **S1** — no aliasing | `Z/6`, `+1 mod 6`, identity observation | observational = behavioral = 6 classes | 6 = 6 | ✅ |
| **S2b** — genuine observational aliasing | `{0,1,2,3}`, no-op transition, `parity` observation only | structural 4 classes → behavioral 2 classes (`{0,2}`,`{1,3}`) | exactly `{0,2}`,`{1,3}` | ✅ |
| **S3** — observational equivalence is NOT a congruence | `{A,B,C,D}`, `A,B` share a coarse observation pre-transition, diverge post-transition (`step:A→C,B→D`, `C,D` have different coarse observations) | naive coarse `F` is `NOT` a congruence (real counterexample); true behavioral partition is strictly finer (4 classes, not 3) | `naive_is_congruence=False`, 4 classes | ✅ |

All three reproduced exactly via `ksme15_synthetic_validation.py`, re-run this pass, matching hand-derived
ground truth in every field. S3's construction required one iteration to fix: an initial design
accidentally gave `C` and `D` the same coarse image post-transition, which trivially passed the congruence
check — a real, disclosed bug caught by checking the actual computed output against ground truth rather
than assuming the design was correct.

## Validation against Lane-B (independent code path, not re-execution)

`ksme15_laneb_validation.py` wraps `kos12/comp.py`'s real `agg_union/majority/last_wins/strict/
intraframe_only` functions as BSE observations over two 2-state regimes (`{W2,W4}` for `C6`; `{coarse,
fine}` for `C7`), then uses only `Regime.observational_partition()` — never calling `comp.compose()`,
`comp.SEP2_order_invariance()`, or `comp.SEP4_frame_refinement_invariance()` directly. Result: **exact
match** — `last-wins` is the only rule BSE finds order-dependent (`C6`); `majority` is the only rule BSE
finds refinement-dependent (`C7`) — identical to Lane-B's own bespoke findings, but reached through
completely different code. One real bug was found and fixed during this validation: a classic Python
closure late-binding error (the loop variable `rfn` was captured by reference, not value, causing every
wrapped observation to silently call the *last* rule in the loop) — caught because the first run's output
was implausible (every rule returning "unsupported"), not assumed correct.

## Minimality machinery validation

`ksme15_minimality_validation.py`: a synthetic 3-bit state `(a,b,c)` where only `a` is behaviorally
relevant (`b`,`c` are flipped by no-op-equivalent transitions that never touch `a` or the observation).
Ground truth: minimal component set `{a}`, `|S*|=1`; 2 behavioral classes (by `a`'s value); representation
minimality correctly selects `π_a` (cost 1) over `π_ab`/`π_full` (higher cost, also sufficient) and
correctly *rejects* `π_b_only` (not sufficient, with an exact counterexample: `(0,0,0)` vs `(1,0,0)` agree
under `π_b_only` but disagree under `observe_a`). All three minimality notions matched ground truth
exactly.
