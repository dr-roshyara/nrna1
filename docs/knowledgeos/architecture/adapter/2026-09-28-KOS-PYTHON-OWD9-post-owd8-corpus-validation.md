# OWD-9 — post-OWD-8 full-corpus re-validation

**Context:** OWD-8 follow-up, mirroring OWD-6's role after OWD-3/OWD-5 · **Date:** 2026-09-28
**Phase:** validation only. **No production code changed.**

---

## Method

Identical to OWD-6: full L3→L4→L5 pipeline (`PythonSemanticFactProvider` →
`AnalyseCohesion::observe()`), all 153 top-level CPython 3.13.2 stdlib files, run now
that OWD-8's combined correction is in place.

## Result 1 — robustness holds

**0 crashes, 153/153 files, 679/679 analysed units** — unchanged from OWD-6.

## Result 2 — a real class's `LCOM4` actually changed at scale, not just in the fixture

```
LCOM4 distribution, OWD-6 (pre-OWD-8) → OWD-9 (post-OWD-8):
  1 => 269 → 270   (+1)
  2 => 114 → 113   (−1)
  (every other value unchanged)
```
One real unit moved from an incorrect to a correct value. `calendar.Calendar` — the
exact class OWD-7's fix was grounded in — now reports `LCOM4=1` (14 nodes, 18 edges),
consistent with the corrected prediction. This is the first time in the whole
investigation a real corpus re-run (not a modeled fixture) has directly shown a
distribution-level shift attributable to a specific, named correction.

## Result 3 — every file using `property(...)` call-form scanned directly

Confirmed by direct grep + full-pipeline execution across `ast.py`, `calendar.py`,
`enum.py` (the three files identified in OWD-7): all classes in these files were
re-examined; none crashed, none produced an implausible value. `enum.py`'s `EnumType`
(LCOM4=10, 22 nodes) and `Enum` (LCOM4=6, 16 nodes) are large, low-cohesion metaclass/
base-class facades — plausible for that role, not flagged as anomalous.

## Classification

**A — confirmatory.** Validates OWD-8 at scale; does not discover a new gap.

## Production changes

**None.**

## Consequence

OWD-6 answered "does the pipeline survive real code" for the OWD-3/OWD-5 fixes; this
answers the same question for OWD-8, and additionally shows the fix's material effect
directly in the aggregate distribution, not only in the two fixtures used to build and
test it. Both HIGH-priority OWD-1 findings and both audit-identified open items are now
validated at scale, not only characterized and unit-tested.

## Remaining named-but-unaddressed items, unchanged

PHP arrow functions (`fn() =>`, named out-of-scope in OWD-5), `DeclaredUnit::declaredName`
(unconfirmed consumption status since the original dependency matrix). Neither pursued
here; neither clearly higher-priority than the other.

**Traceability:** same corpus and method as
`2026-09-27-KOS-PYTHON-OWD6-full-pipeline-corpus-validation.md`, re-run after
`2026-09-28-KOS-PYTHON-OWD8-implementation.md`.
