---
source_track: TRACK-A-CONSTRUCTION
input_artifacts: [KSME-17-EXECUTABLE-SEED-AND-MATERIALITY]
derived_from: [.claude/scripts/knowledgeos-ksme/ess.py (fixed), ksme17_minimality.py]
cross_track_dependency: none
---

# KSME-17 — Minimality Computation (completed, closure bug fixed)

## The two bugs found and fixed

1. **Non-deterministic identity**: `fresh_id()` was a global auto-incrementing counter, so a
   conceptually identical assertion reached by two different exploration orders got two different IDs —
   the reachable state space was never actually well-defined. **Fixed**: `fresh_id()` is now
   content-addressed (a deterministic hash of `(P,Σ,E,τ,Π)`) — a disclosed `CONSTRUCTION` decision, not a
   claim the historical corpus specifies content-addressed identity (`G2` remains `UNDERIVED`).
2. **Unbounded closure**: the illustrative `revise` operation used `tau=a.tau+1`, which — since `tau` feeds
   the identity hash — produces infinitely many distinct states (`0,1,2,3,...`), not a large-but-finite
   space. First BFS run confirmed this directly (exceeded 2000 states, aborted rather than silently
   truncated). **Fixed**: `tau` is fixed at a constant for this bounded, disclosed regime, so repeated
   revision to the same value becomes idempotent and the closure genuinely terminates.

## The bounded regime

`E` = the exact BFS-reachable closure from one seed assertion (`P="p0", Σ="Weak", E={"e0"}`) under 3
operations (`add_evidence` with a 1-item alphabet, `revise` with a 1-item alphabet, `rollback_to_seed`),
using the `C1` (conservative/"no epistemic update") candidate for the ambiguous operations. **`|E|=12`,
finite, genuinely closed** (confirmed: the BFS loop terminated with no exception, unlike the first attempt).

## Results (exact, reproducible via `ksme17_minimality.py`)

- **Partition minimality**: 4 behavioral classes, sizes `[1,1,2,8]`, reached in 1 refinement round beyond
  the observational partition (i.e., the observational partition was already the fixed point — no
  operation-signature split was needed for this particular regime/observation-set pairing).
- **Component minimality**: exhaustive subset search over `{P,Σ,E,τ,Π}` found the minimal sufficient set
  is **`{P,E}`, size 2** — `{P}` alone, `{E}` alone, `{Σ}` alone, `{τ}` alone, `{Π}` alone, and `{P,Σ}` are
  all individually insufficient; `{P,E}` is the first sufficient set found (smallest-first search).
- **Representation minimality**: among 4 disclosed candidates, `F_no_tau_pi` (keeps `P,Σ,E`, cost 3) is
  the cheapest sufficient one; `F_P_Sigma_only` and `F_P_only` are both insufficient (missing `E`).

## Interpretation, disclosed precisely

`Σ` (epistemic status) is **completely behaviorally inert in this bounded regime** — not because it is
inherently unnecessary, but because the `C1` candidates chosen for this regime were specifically
constructed to never modify it (`evidence_added_C1` doesn't touch `Σ`; `value_revised` carries `Σ`
forward unchanged; `rollback_C1_no_marker` resets fully). `Σ` stays frozen at `"Weak"` across all 12
reachable states, so it carries zero information beyond what `(P,E)` already determines *within this
closure*. **This is a genuine confirmation of a real property of the C1 regime, not evidence that Σ is
universally Kernel-irrelevant** — under the `C2` candidates (which do update `Σ`), this result would very
likely differ; that comparison is named, not run, in this pass.

`τ` and `Π` are droppable in both computations, consistent with each other — `τ` was fixed constant by
construction (carries no information at all in this closure), and `Π` was never varied by any of the 3
operations tested.

**Component minimality and representation minimality are not forced to agree, and here they don't
exactly**: component minimality's exhaustive search found `{P,E}` (dropping `Σ` too) sufficient, while
representation minimality's *best* result among its own disclosed candidate menu was `F_no_tau_pi`
(`P,Σ,E`) — because no `P,E`-only candidate was included in that menu. This is a real, computed
illustration of exactly why the commission insisted on keeping these two notions distinct: representation
minimality is bounded by which candidates you think to disclose; component minimality's subset search is
exhaustive over the full field list and found a strictly smaller sufficient set.

## What this does not establish

Does not claim `{P,E}` is the minimal sufficient representation for the real (unbounded) `ESS`, still less
for any historical KnowledgeOS Kernel. This is an exact result for one small, disclosed, bounded regime
built on the `C1` candidates only. Extending to `C2`, to a larger evidence/revision alphabet, or to the
full `ConflictResolved`/`Rollback` operations is named as future work, not attempted here.
