---
source_track: LANE-B
input_artifacts: [KSME-14-ADMISSIBILITY-DECISION, KSME-13A-UNDERIVED-TERM-QUEUE]
derived_from: [research/knowledgeos-sim/kos12/comp.py, contr.py, contr2.py, __init__.py]
cross_track_dependency: LANE-B only, not Track-A historical evidence
---

# KSME-14 Phase B — LANE-B Findings Closing KSME-13A's High-Leverage Queue Items

All findings below are `LANE-B / knowledgeos-sim, not Track-A historical evidence`. Direct reads of
`kos12/comp.py`, `kos12/contr.py`, `kos12/contr2.py`, `kos12/__init__.py`.

## `C6` and `C7` — exact, executable definitions found (closes the queue item)

Both are **this lane's own criteria**, explicitly labeled as such in the code — not the historical
corpus's own formulas, and not claimed to be:

- **`C6` (order invariance)**: "a composition rule must be a function of the evidence SET, not of its
  enumeration order" (`comp.py::SEP2_order_invariance`). Tested via permutation witnesses (`W2` vs. `W4`,
  identical multiset, different order). Confirms the already-known finding that `last-wins` fails this
  (order-dependent), independently reconfirmed here via direct execution rather than narrative citation.
- **`C7` (frame-refinement invariance)** `[PROP]`: "Refining the frame partition — recording a frame
  feature at finer resolution, without adding/removing/altering evidence — must not change the verdict"
  (`comp.py::SEP4_frame_refinement_invariance`). Tested via coarse-vs-fine timestamp resolution on
  identical evidence content. **Computed result** (traced through the code's own logic, not just cited):
  `majority` is refinement-**dependent** (fails `C7` — coarse gives `(False,False)`, fine gives
  `(True,False)` for the same evidence content under finer timestamp resolution); `intraframe-only` is
  refinement-**invariant** (passes `C7` — both resolutions give `(False,False)`). The code's own caveat:
  "this is a COST, measured. Whether refinement-dependence is DISQUALIFYING is a judgement, not an
  experimental result."

## `majority` / `last-wins` / `intraframe-only` / `union` / `strict` — exact algorithms found

```python
def agg_majority(frames):
    p = sum(1 for f in frames if f.positive_support and not f.negative_support)
    n = sum(1 for f in frames if f.negative_support and not f.positive_support)
    c = any(f.positive_support and f.negative_support for f in frames)
    if c: return Standing(True, True)
    return Standing(p > n, n > p)

def agg_last_wins(frames):
    return frames[-1] if frames else Standing(False, False)

def agg_intraframe_only(frames):
    # conflict reported ONLY within a single frame; cross-frame divergence
    # collapses to (0,0) -- an openly-disclosed collision with "no evidence"
    if any(f.positive_support and f.negative_support for f in frames):
        return Standing(True, True)
    p = any(f.positive_support for f in frames)
    n = any(f.negative_support for f in frames)
    if p and n: return Standing(False, False)
    return Standing(p, n)
```

`SURVIVORS = ["majority", "last-wins", "intraframe-only"]` — the three rules that pass this lane's own
5-criterion sweep (`C1`–`C5`), before the additional `C6`/`C7` tests further separate them.

## `Contr` — genuinely confirmed undefined, not resolved by this lane (does NOT close the queue item)

This is the single most important finding. Lane-B's own code **explicitly treats "`Contr` is undefined"
as an inherited, unresolved fact from the historical corpus**, and studies the *consequences* of that gap
rather than resolving it:

```python
"NO_EVALUATOR": (BUCKET["NO_EVALUATOR"],
   "corpus: Contr is undefined -> Sat_consistency has no evaluator; Experiment F"),
```

The `m_delegated` model explicitly *loses* the fact that an input was contradictory because it "routes
contradictory input through `Sat_consistency`, which is undefined because `Contr` is undefined." Lane-B's
own rigorous conclusion (`C10_composition_algebra`): *"M3 and M4 separate IFF the composition rule is
token-sensitive. The corpus supplies no such rule, and composition is itself OPEN. So the fourth-value
question is NOT independent: it is a COROLLARY of the composition question."* — a genuinely new, precise,
executed result, but one that **reinforces and sharpens** the `Contr`-undefined finding rather than
closing it. **Do not treat this as resolving `Contr_step292` or `Contr_theory08`** — Lane-B never claims
to define `Contr`; it works around its absence.

## `Reason` cardinality — likely resolution found, one step short of definitive confirmation

`contr2.py`'s Candidate D (`R_D`) has a `reason` field with exactly these values: `absent`,
`theory-incomplete`, `not-applicable`, `unobservable`, `unobserved`, `uninterpreted`, `not-assessed`,
`insufficient`, `underdetermined`, plus `None` (no gap) — **10 total distinct outputs**, matching the
"10 distinct values" figure cited by `theory-08`/`170000`. This is `LANE-B`'s own `Reason` field, tested
against 21 named conditions (`E1`–`E13`'s `CONDITIONS` dict has exactly 21 entries) — matching the
"21 conditions" figure exactly. **This strongly suggests `contr2.py`'s `R_D` is the primary source
`theory-08`/`170000` cite as `KR-CONTR-EVAL`/"W".** Not yet independently confirmed by reading `fde/
models.py`/`fde/boundary.py` directly (the FDE writeup's own `Standing`/`Boundary` dataclasses) — flagged
`NOT-YET-TRAVERSED` for full confirmation, but the 10-value/21-condition match is strong circumstantial
evidence, not yet a citation-confirmed identity.

## Self-description confirms non-canonical status

`kos12/__init__.py`: *"v1.2 is a DELIBERATE WEAKENING of v1.1... Observation reopened as an OPEN CONCEPT...
Zero demoted from primitive to CANDIDATE epistemic-boundary construct... the transformation equation
demoted to MODEL / HYPOTHESIS."* Confirms `LANE-B`'s own self-classification as exploratory, consistent
with the admissibility decision.

## Updated UNDERIVED queue status (amends `KSME-13A-UNDERIVED-TERM-QUEUE.md`)

| Item | Prior status | Updated status |
|---|---|---|
| `C6`, `C7` exact formulas | `NOT-YET-TRAVERSED (scope-blocked)` | **CLOSED** — found, executable, `LANE-B`-tagged |
| `majority`/`last-wins`/`intraframe-only` | scope-blocked | **CLOSED** — found, executable, `LANE-B`-tagged |
| `Reason` cardinality (8 vs 10) | `SOURCE-CLAIMED-VIA-CITATION` | **LIKELY-RESOLVED** — 10-value/21-condition match found in `LANE-B`; pending one confirmatory read of `fde/models.py` |
| `Contr_step292` algorithm | `NO-SOURCE-FOUND-AFTER-EXHAUSTIVE-SEARCH` | **UNCHANGED, reinforced** — `LANE-B` confirms and works around the same absence, does not supply an algorithm |
| `BC-02.14`–`20` admissibility | `GOVERNANCE-DEPENDENT` | **CLOSED** — classified `LANE-B` per `KSME-14-ADMISSIBILITY-DECISION.md` |

Remaining genuinely open: `Resolve`/`Revision`/`Policy`/`Authority`/`⊕_Step32` (unaffected by this lane —
no citation bridge found or expected), one confirmatory read of `fde/models.py` for the `Reason` field.
