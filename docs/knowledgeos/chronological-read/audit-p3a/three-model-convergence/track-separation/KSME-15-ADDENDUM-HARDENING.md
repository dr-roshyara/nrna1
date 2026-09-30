---
source_track: SEMANTIC-NEUTRAL
input_artifacts: [KSME-15-BEHAVIORAL-SEMANTICS-ENGINE, KSME-15-TRACK-A-READINESS]
derived_from: [.claude/scripts/knowledgeos-ksme/bse.py]
cross_track_dependency: none
---

# KSME-15 — Correctness Audit Addendum (Hardening)

Four fixes made directly to `bse.py` and this record, per the user's KSME-15 audit.

## 1. `H` semantics formalized as two distinct relations, not one overloaded `None`

`H=integer` → finite-horizon `x∼_{B,H}y` (always well-defined, no extra assumptions). `H=None` →
unbounded `x∼_By`, computed exactly via fixed-point partition refinement **only** under four disclosed
assumptions: finite `E`; every `Tᵢ` a deterministic total function (not `E×C→𝒫(E)`, not a relation); a
finite operation+context alphabet; every `Oⱼ` deterministic. All four held in every KSME-15 validation
this pass — not automatically true in general, and BSE does not check them for the caller. Documented in
`bse.py`'s module docstring.

## 2. BSE v1 declared deterministic-only, by design not oversight

Nondeterministic (`T:E×C→𝒫(E)`) and relational (`R⊆E×C×E`) transitions are an explicitly deferred v2
extension — this investigation's own Track-A research (KSME-10/11/12/13A) found live candidates for
exactly this shape, and BSE v1 deliberately does not attempt to support them rather than support them
incorrectly. Documented in `bse.py`.

## 3. `RegimeManifest` added — provenance-completeness gate

New dataclass in `bse.py`: `regime_id`, `source_track`, `semantic_status`, `state_sources`,
`operation_sources`, `observation_sources`, `constraint_sources`, `horizon`, `finite_state`,
`determinism`, `provenance_complete`. `validate(regime)` checks every operation and observation the
`Regime` declares has a corresponding declared source — it cannot verify citations are *true* (a human/
source-verification job), only that none are silently missing. **Binding rule going forward: no BSE
result from real (non-purely-synthetic) material is admissible without a validated manifest.**

## 4. Terminology correction: `T_A = ∅` → `T_A^{SG} = ∅`

The precise claim is **`T_A^{source-grounded-executable} = ∅`**, not "no transitions exist at all."
Named operations exist (22 catalogued, Step 277's registry); typed operations exist (8 of 22 have
signatures); worked examples exist (Step 60's Merge→Conflict). What is absent, specifically, is any
operation with a **source-grounded, executable transition body**. `KSME-15-TRACK-A-READINESS.md`'s prose
already made this distinction ("Named-but-bodyless throughout"); this addendum makes the formal notation
explicit for future KSME passes to reuse without re-deriving: `T_A^{named} ≠ ∅`, `T_A^{typed} ≠ ∅`,
`T_A^{worked-example} ≠ ∅`, `T_A^{SG-executable} = ∅`.

## 5. Conceptual chain corrected

`bse.py`'s docstring now states the chain as `Source Corpus → R=(E,T,O,C,H) → BSE → ∼_{B,H} → Π_{B,H} →
K_{B,H} → K_min` — BSE is execution/analysis infrastructure, never the source of `E`/`T`/`O`/`C`/`H`
themselves. The prior phrasing ("BSE → (E_A,T_A,O_A) → ...") is superseded by this correction, not left
standing alongside it.

No new computation performed in this addendum; all four items are documentation/API-contract corrections
to already-validated code and reports.
