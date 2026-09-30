---
source_track: SEMANTIC-NEUTRAL
derived_from: [.claude/scripts/knowledgeos-ksme/bse.py]
cross_track_dependency: none
---

# KSME-15 — BSE Architecture and Implementation

## Module layout

`.claude/scripts/knowledgeos-ksme/bse.py` — the engine itself, one file, no external dependencies beyond
the Python standard library (`itertools`, `dataclasses`, `typing`). Deliberately dependency-free so it can
be imported by any future KSME script without setup.

- `CounterexampleCertificate` (dataclass): the formal falsification-witness object (see
  `KSME-15-COUNTEREXAMPLE-SCHEMA.md`).
- `Regime` (class): holds `(E,T,O,H)`, exposes `observational_partition`, `behavioral_partition`,
  `behaviorally_equivalent`, `congruence_test`, `sufficiency_test`, `necessity_test`,
  `complexity_report`.
- Module-level functions `component_minimality`, `partition_minimality`, `representation_minimality` —
  free functions rather than `Regime` methods, since they operate on a `Regime` plus caller-supplied
  extraction/cost functions that are lane-specific, not engine-internal.

## Design decisions (disclosed, not silent)

- **Hashability requirement on `E`**: states must be hashable (tuples, frozen dataclasses, strings) —
  `_safe()` falls back to `repr()` for unhashable observation outputs, disclosed as a fallback, not a
  silent correctness assumption.
- **No constraint (`C`) enforcement**: constraints are documentation-only in this version. The commission's
  `C` slot exists in the formal contract but BSE does not interpret or check it — every KSME-15 validation
  pass supplied constraints as external, disclosed test logic (e.g. Lane-B's `C1`–`C7` remain Lane-B's own
  functions, never re-implemented inside BSE).
- **No horizon-truncation warning surfaced to the caller by default**: if `H` truncates before a genuine
  fixed point, `behavioral_partition()`'s `rounds` field lets the caller detect this, but BSE does not
  raise an error — exhaustive systems (all validations this pass) reach a fixed point well before any
  externally-imposed `H`.

## Reuse discipline applied

Per this investigation's standing "search for existing implementations before writing new ones" rule:
`ksme04.py`/`ksme05.py`/`ksme08_relation_separation.py`'s own fixed-point partition-refinement pattern was
read first and is the direct ancestor of `Regime.behavioral_partition()` — not reinvented from scratch,
generalized into reusable form.

## What remains unimplemented, disclosed

Model-checking and symbolic execution (mentioned in the commission's §13 as allowed techniques) were not
implemented — every validation system this pass was small enough for pure exhaustive enumeration, which
the commission itself prefers whenever feasible (§20: "Do not use ML to replace exact behavioral
computation"). If a future regime's `|E|` grows too large for exhaustive `behaviorally_equivalent()`
pairwise checks, `component_minimality`'s subset search would need a smarter bound (e.g. partition-based
pruning) — named as a real scaling limit, not silently worked around.
