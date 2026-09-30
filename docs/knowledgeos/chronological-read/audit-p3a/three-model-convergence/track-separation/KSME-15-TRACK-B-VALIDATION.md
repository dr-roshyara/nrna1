---
source_track: CROSS-TRACK-COMPARISON (Track-B used strictly as validation benchmark, per explicit user authorization this turn)
input_artifacts: [TRACK-B-INDEPENDENT-BASELINE, KSME-15-BEHAVIORAL-SEMANTICS-ENGINE]
derived_from: [verification/gap-discovery/second-order/exec/so_model.py, .claude/scripts/knowledgeos-ksme/ksme04.py, ksme05.py]
cross_track_dependency: Track-B numbers used as a comparison/validation target only, never as a definitional input to Track-A or as a resolved Track-A fact
---

# KSME-15 — Track-B Benchmark Validation

**Authorization note**: the user explicitly authorized using `verification/gap-discovery/` this turn as a
validation benchmark for the generic engine — the established Track-B firewall's own carve-out
(comparison across independently-constructed material is fine; fusion into Track-A is not). No Track-B
semantic object is promoted into a Track-A conclusion anywhere in this document.

## Established baseline figures (already computed, this session, prior to KSME-15)

From `TRACK-B-INDEPENDENT-BASELINE.md`, produced by `so_model.py` (Track-B's own real, executable, self-
disclosed-non-authoritative state/operation model) extended by this investigation's `ksme04.py`/`ksme05.py`:

- `|E| = 41,820` reachable states — exact BFS closure.
- Nominal model space `2^8=256` → exactly `2^4=16` behaviorally distinct models (empirically proven: 4 of
  8 operations are variant-insensitive, 0 mismatches across all 208 seed states/arguments).
- `K_R` (robust quotient across all 16 models): F2/F3 → 17,129 classes; F4 (`K=(𝒜,ℛ)`) → 27,398 (already
  fully robust). `F4` refines `K_R(F2)` with 0 violations over all 41,820 states — sufficient, not minimal.

## Reproducibility attempt this pass

A full independent re-execution of `ksme05.py` was attempted (the same discipline successfully applied to
Lane-B's `kos12/comp.py` — byte-identical reproduction). **The full computation did not complete within a
practical time** (41,820 states × 16 models; the script's own documented history notes an earlier,
unoptimized draft of this exact computation took ~46 minutes before being optimized — the optimized
version still did not finish inside this pass's practical window). Per this task's own allowed fallback,
the code's logic was instead verified directly, not merely cited: `reachable_closure()` genuinely performs
BFS-to-closure (confirmed matching the baseline's own disclosed bug-fix history — the 208-state seed is
not closed under `Add`/`Merge`, and the function's own docstring documents exactly this); `refine_to_
fixpoint()` genuinely implements fixed-point partition refinement over `T*`; `F_content_status`/`F_AR`
are exactly the cited F2/F4 abstractions.

## Honest verdict

**Tier: code-logic verified this pass; full-computation independent re-execution not completed.** This is
weaker than the Lane-B validation (which achieved byte-identical re-execution) and weaker than the
original baseline's own claim (which states it was independently re-verified byte-identical at the time
it was first produced). Do not read this document as confirming the 41,820/17,129/27,398 figures a second
time computationally — it confirms the code that produced them does what the baseline document says it
does, which is a real but different (weaker) form of verification.

## What BSE itself was NOT validated against here

Because the full Track-B computation did not complete, **BSE's own generic machinery was not run against
Track-B's real 41,820-state space this pass** (contrast with Lane-B, where it was). This is disclosed as
an honest gap, not papered over. A future pass could attempt this on a bounded sub-region of Track-B's
state space (e.g. a single seed's reachable closure under one model) rather than the full 16-model sweep,
if BSE-vs-Track-B validation specifically becomes a priority.
