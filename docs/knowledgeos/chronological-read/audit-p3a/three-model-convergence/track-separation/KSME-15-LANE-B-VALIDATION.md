---
source_track: LANE-B (test data only; BSE itself is semantic-neutral)
input_artifacts: [KSME-15-BEHAVIORAL-SEMANTICS-ENGINE, KSME-14-COUNTEREXAMPLE-CATALOG]
derived_from: [research/knowledgeos-sim/kos12/comp.py]
cross_track_dependency: LANE-B used strictly as validation test data; no LANE-B semantics promoted
---

# KSME-15 — Lane-B Validation Suite

**Method**: `kos12.comp`'s real `agg_union/majority/last_wins/strict/intraframe_only` functions and its
real `WITNESSES`/`frame_key` data were wrapped as BSE *observations* over a 2-element state space
(`{"W2","W4"}` for `C6`, `{"coarse","fine"}` for `C7`). BSE's own generic `observational_partition()` was
then used to detect distinguishing rules — **not** `comp.compose()`, `comp.SEP2_order_invariance()`, or
`comp.SEP4_frame_refinement_invariance()` directly. This is independent reproduction through different
code, not re-execution of the same code (contrast with `KSME-14-COUNTEREXAMPLE-CATALOG.md`'s byte-
identical re-execution of Lane-B's *own* functions).

## Result

| Test | BSE-detected distinguishing rule(s) | Lane-B's own SEP2/SEP4 finding | Match |
|---|---|---|---|
| `C6` (order invariance, `W2` vs `W4`) | `last-wins` (outputs `negative-support` vs `positive-support`) | `last-wins` | ✅ |
| `C7` (frame-refinement invariance, `coarse` vs `fine`) | `majority` (outputs `unsupported` vs `positive-support`) | `majority` | ✅ |

**One real bug found and fixed during construction**: a classic Python closure late-binding error — the
loop variable `rfn` was captured by reference, not as a default argument, so every wrapped observation
function silently called the *last* rule in the iteration order (`agg_intraframe_only`) regardless of its
nominal name. This produced uniformly wrong results ("unsupported" for everything) on the first run.
Caught immediately because the output was implausible (identical across all 5 rules), fixed by binding
`rfn` and the evidence dict as default arguments, re-run, confirmed correct. Disclosed here rather than
silently corrected, per this investigation's standing discipline.

## Verdict

**BSE independently reproduces both of Lane-B's own headline counterexamples**, using only its generic
`observational_partition` machinery fed real Lane-B functions as test data. This validates the engine's
core distinguishing-power on a second, independent, real (not synthetic) dataset — the commission's own
explicit instruction is honored: *"Do NOT conclude that `intraframe-only` is a KnowledgeOS Kernel rule.
The purpose is to demonstrate that BSE can reproduce independent experimental semantics."* No Lane-B
object is treated as a Track-A or Track-B object anywhere in this validation.
