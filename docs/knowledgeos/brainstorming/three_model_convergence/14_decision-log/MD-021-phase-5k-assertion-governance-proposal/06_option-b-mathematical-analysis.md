# Phase 5K — Option B: Preserve D2/D4

$$Assertion = \{Proposition, Entity, Observation, id, c, t, \Pi\}$$

## Mathematical evaluation

- **Primitive set**: 3 conceptual + 4 technical fields (7 total, largest of the named options).
- **Domain/codomain**: `id` has a stated derivation ($id=H(P,e,c,t,\Pi)$, `e_equality.py`, though
  D285-6's own prose does not itself state this — Phase 5I `10`'s own "compatible" finding applies).
- **Operators**: `eq_struct`/`eq_semantic`/`eq_obs`/`eq_prov` (`e_equality.py`) are genuinely executed
  against **this schema's own shape** (a state-set of assertion IDs) — the most operationally exercised
  of any option, though `e_equality.py`'s own field list (`P,e,c,t,Pi`) is narrower than D2/D4's own
  prose list (lacking `Entity`/`Observation` as independent top-level names).
- **Invariants**: none formally stated beyond the hash-based identity function.
- **Equality relation**: the richest of any option — 4 distinct, independently defined and tested
  relations (`e_equality.py`, Phase 5I `05`), plus the 3-level structural/semantic/observational test
  (`t285_equality.py`, Phase 5H `05`).
- **Projection**: this is exactly $\pi_2$ (Phase 5I `09`) — `Observation` present directly;
  `Entity/Proposition/Relation` preserved; `State` undefined target (same gap as Option A);
  `Event/Policy/Action` dropped (with `Policy`'s own status made ambiguous by the `Π` conflict, `09` of
  Phase 5J).
- **Information preservation**: retains `Observation` as a direct field, at the cost of not naming
  `Evidence`/`Context`/`Provenance` as independent English-labeled concepts (they are folded into
  `c`/`t`/`Π`, unresolved which is which).
- **Consistency**: internally consistent as prose+`t285_equality.py` (modulo that script's own hardcoded
  Test-3, Phase 5I `04`); `e_equality.py`'s own narrower field list is a **further, not-fully-reconciled
  variant even within this option** — Option B itself is not perfectly internally unified.
- **Completeness**: incomplete — same `State`/operator gaps as Option A.
- **Closure**: not established.
- **Computability**: `id`'s own derivation is fully computable and demonstrated (the strongest single
  computability result of any option); `Qualify`'s own output (populating `Observation` in the first
  place) remains uncomputed regardless.

## What is lost under Option B

`Evidence`/`Context`/`Provenance` as independently named concepts (folded into ambiguous technical
symbols); the direct, legible naming Option A retains.

## What is preserved

`Observation` as a first-class, directly addressable field (matching the D285-6/`t285_equality.py`
version of the K-1→K-2 projection this reconstruction's own Phase 5F–5I already did the most work
against); the richest available equality-relation apparatus; a demonstrated, computable `id` function.
