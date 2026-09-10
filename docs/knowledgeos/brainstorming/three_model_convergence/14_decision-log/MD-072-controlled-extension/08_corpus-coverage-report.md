# MD-072 §08 — Corpus Coverage Report

## Level-1 census results, all nine batches

| Batch | Scope | Files | T0 | T1 | T2 | T3 |
|---|---|---|---|---|---|---|
| 01 | pre-2026-09-01 misc root files | 125 | 22 | 39 | 36 | 28 |
| 02 | `kernel/` (part a) | 88 | 0 | 2 | 16 | 70 |
| 03 | `kernel/` (part b) | 93 | 12 | 4 | 11 | 66 |
| 04 | `phase_measure_theory/` (part a) | 163 | 0 | 6 | 47 | 110 |
| 05 | `phase_measure_theory/` (part b) | 148 | 1 | 16 | 10 | 121 |
| 06 | `phase_measure_theory/` (part c) | 156 | 2 | ~120 | ~19 | ~15 |
| 07 | `phase_measure_theory/` (part d) | 150 | 0 | 9 | 13 | 128 |
| 08 | `phase_measure_theory/` (part e) | 151 | 0 | 0 | 0 | 151 |
| 09 | `phase_measure_theory/` (part f, latest-dated) | 126 | 0 | 1 | ~30 | ~95 |
| **Total** | | **1,200** | **37** | **~197** | **~182** | **~784** |

(Batch 06's T1/T2/T3 split is approximate — its own worker read the majority of a long, structurally
repetitive "step-76–184" DDD/architecture-conformance sub-range at partial depth after confirming, via
direct full reads of a representative sample, that the pattern held; every file in that sub-range still
received an individual classification and one-line reason, per the mission's own Level-1 minimum-
inspection requirement — no file was silently skipped.)

## Coverage categories, per the mission's §22 requirement

**Already reconstructed** (F4's own `TheoryState`, MD-057–071): 876 files (math lane, `mathematical_
ideas_that_can_be_implemented/`, queue lines 5123–5998), covered at full depth via the `TheoryState(t)`
object-tracking method.

**Newly reconstructed, this phase** (Level-1 census depth, not full `TheoryState` depth): 1,200 files
(`kernel/`, `phase_measure_theory/`, ~125 pre-2026-09-01 misc root files) — every file individually
classified T0–T3, with full structured evidence packets produced for all ~966 T2/T3 files.

**Inspected but theory-neutral**: 37 T0 files (empty placeholders, unrelated business/product content,
duplicate markers) + a large share of the ~197 T1 files (terminological occurrence only — a tracked
term's *name* appears with no new definition, e.g. "Zero-trust principle" as a cybersecurity term, or
"Decision" as a generic pipeline-stage label).

**Not yet inspected, explicitly excluded from this phase's scope (§00_index.md's own scoping decision,
disclosed there)**:
- `three_model_convergence/` (3,440 files) — this reconstruction's own scaffolding, not independent
  corpus evidence; excluded as circular, not as unknown.
- `verification/` (482 files) — the K-1/K2 Assertion-governance track; excluded per this session's own
  standing "K-1/K2 untouched" boundary, carried since MD-057.
- `docs/knowledgeos/theory-extraction/` — permanently firewalled, per MD-021's own absolute standing
  prohibition; never read by this or any prior phase in this reconstruction.

**Genuinely not yet inspected, no exclusion decision applies**: none identified within the originally-
scoped ~5,100 remaining queue positions — Population B' (1,200 files, §00_index.md) was constructed to
be exhaustive of the non-excluded remainder, and all 1,200 were processed.

## What Level-1 depth does and does not establish

Level-1 census (per the mission's own §4–§5) is a **minimum-necessary-inspection** standard — full
reads for files under ~500 lines, targeted structural reads (headers, boxed formulas, definition
sections) for longer files. This is **shallower** than the full `TheoryState`-tracking depth MD-057–071
applied to the 876-file F4 population. **Consequence, stated plainly**: the ~784 T3 files now carry a
verified classification and a structured evidence packet each, but **not** the same level of cross-
file consistency-checking, duplicate-detection-by-content, or theorem-verification MD-067–071 applied
to F4's own material. Several individual packets above (§01 Batch 06's grep-only entries) are
explicitly flagged by their own workers as needing a fuller read before being treated as confirmed.

## No frozen artifact modified; both consistency scripts to be re-verified before closure (§09)
