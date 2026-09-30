---
source_track: LANE-B
input_artifacts: [KSME-14-ADMISSIBILITY-DECISION, KSME-14-LANE-B-FINDINGS]
derived_from: [research/knowledgeos-sim/kos12/comp.py, fde/evaluation.py, fde/boundary.py, fde/scenarios.py]
cross_track_dependency: LANE-B only, not Track-A historical evidence
---

# KSME-14 Phase D — Bounded Executable Regime `R0`

**Correction to prior grounding, found this pass**: `Boundary` in the actual `kos12/fde/boundary.py` code is
`Boundary(facet: Optional[str], condition: Optional[str])` — a **2-field** dataclass — not the 4-field
`(reason, provenance, context, condition)` structure reported earlier from the narrative FDE writeup
document (`20260902-175306`, `mathematical_ideas_that_can_be_implemented/`). "Reason" is not a field on
`Boundary` at all — it is a *derived representation* computed from `Boundary` by one of two competing
adapters, `FlatReason.of(b)→(b.condition,)` or `LocusModalityReason.of(b)→(b.facet, modality(b.condition))`.
The narrative writeup's 4-field description does not match this actual implementation; both are `LANE-B`
material, and the discrepancy is disclosed, not silently resolved in either direction.

**Also directly confirmed in code, strengthening the Symbol Identity discipline already established**:
`fde_conflict_detector()`'s own docstring — *"deliberately NOT named `Contr`... whether `FDEConflict(p) ==
Contr(p)` is TESTED, never assumed."* This package's own authors independently arrived at exactly the
"distinct until proven related" discipline this investigation has been applying since `⊕`/`Conflict`/
`Authorize`.

## `R0`, as this lane actually builds and runs it (not a new toy regime — the existing one, reused)

Per this investigation's own "search for existing implementations before writing new ones" discipline, `R0`
below is `kos12/comp.py`'s own already-built, already-run experiment — not invented for KSME-14.

| Element | Type | Signature | Source | Evidence tier | Executable? | In `R0`? |
|---|---|---|---|---|---|---|
| `E_0` (evidence item) | dataclass | `Ev(polarity, time, context, ...)` | `fde/scenarios.py` | LANE-B, source-defined | ✅ | ✅ |
| `Standing` | dataclass | `(positive_support: bool, negative_support: bool)` | `fde/evaluation.py` | LANE-B, source-defined | ✅ | ✅ |
| `Boundary` | dataclass | `(facet: str\|None, condition: str\|None)` | `fde/boundary.py` | LANE-B, source-defined | ✅ | ✅ |
| `φ` (frame qualifier) | function | `frame_key(e,φ)→tuple`, 6 named variants (`PHIS`) | `comp.py` | LANE-B, source-defined | ✅ | ✅ |
| `𝒯_0` (composition rules) | functions | `agg_union/majority/last_wins/strict/intraframe_only: [Standing]→Standing` | `comp.py` | LANE-B, source-defined, exact code | ✅ | ✅ (5 candidates) |
| `𝒪_0` (observations) | functions | `conflicting(res)→bool`, `Standing.configuration()→str` (4-valued) | `comp.py`, `fde/evaluation.py` | LANE-B, source-defined | ✅ | ✅ |
| `C_0` (constraints) | predicates | `C1`–`C5` (the commission's own 5 criteria) + `C6`/`C7` (this lane's own, `[PROP]`) | `comp.py` | LANE-B, source-defined, exact code | ✅ | ✅ |
| status policy | function | `filter`/`keep`/`demote` | `comp.py` | LANE-B, source-defined | ✅ | ✅ |

## What this regime is NOT

Not `(A,R)`. Not the historical Track-A `K_t`. Not a test of `Contr_step292` or the historical `Conflict`
family — no carrier here resembles those objects' types, and no citation bridges them. Testing Track-A's
own historical projections against this regime (Phase I) would be a category error — `R0` has no `K`-typed
object for `π_{A,R}` to act on. This is flagged explicitly rather than forced.

## Symbol Identity discipline applied

`Standing` (this lane) ≠ `Standing` in any other sense used elsewhere in this investigation (none found).
`Boundary` (this lane, 2-field) ≠ `Boundary` (the FDE writeup's narrative 4-field description) — same name,
two representations, disclosed as distinct until reconciled. `fde_conflict_detector` ≠ `Contr` (explicit,
source-stated, this lane's own discipline).
