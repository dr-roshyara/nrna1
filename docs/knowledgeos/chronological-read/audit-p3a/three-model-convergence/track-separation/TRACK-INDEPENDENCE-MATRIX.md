---
source_track: CROSS-TRACK-COMPARISON
input_artifacts: [TRACK-REGISTRY.md]
derived_from: []
cross_track_dependency: none
---

# Track Independence Matrix

Quick-reference table for future work in this investigation. Check this
table before reading any "previously uninspected" folder under
`docs/knowledgeos/brainstorming/verification/` or `phase_measure_theory/`.

| Path | Track | Status |
|---|---|---|
| `phase_measure_theory/` (Step series) | A | Admissible, primary |
| `verification/canonical-construction/` | A | Admissible (`MD-043`-confirmed) |
| `verification/consolidation/` | A | Admissible (`MD-043`-confirmed) |
| `verification/step-272/` (standalone, top-level) | A | Admissible (`MD-043`-confirmed) — **verify this is not actually `gap-discovery/step-272/`, a same-named but different path** |
| `verification/handoff/`, `verification/witnesses/` | A | Admissible (`MD-043`-confirmed) |
| `verification/gap-discovery/` (all subfolders) | **B** | Admissible **only as Track B**, never as a Track-A input |
| `three_model_convergence/` | — | **Permanently firewalled**, both tracks, unrelated to this matrix |
| `knowledgeos_theory_research/` | — | **Permanently firewalled**, both tracks, unrelated to this matrix |
| `verification/` — remaining ~445 files not named above | — | `PLAUSIBLE/UNRESOLVED` per `MD-043` — **check before use in either track**; not pre-cleared |

## The one rule that matters

**Before reading any file under `verification/` for the first time in this
BC-02.x line, grep `.claude/CONTEXT.md` and `.claude/sessions/*.md` for its
path first.** This is the check that was skipped before `KSME-04`, and
skipping it is what caused the fusion this correction repairs. Do not repeat
it.
