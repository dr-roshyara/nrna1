# Track Registry — KnowledgeOS Independent Theory Construction Lines

**Purpose:** the user built (at least) two deliberately independent KnowledgeOS
theory constructions, specifically so they would not copy from each other, so
that a later comparison would be genuine convergent-validation evidence rather
than one line reproducing the other. This registry makes that separation
explicit and machine-checkable for every future artifact.

**Trigger:** KSME-04/05 (`BC-02.21`/`BC-02.22`) were built by importing and
extending `verification/gap-discovery/`'s own executable code directly into
what had been, until then, an unbroken `phase_measure_theory`-based
reconstruction lineage (KSME-01→02→03). This fused two tracks the user
intended to keep separate. Caught after execution, not before — see the
session log for the full account. This registry, and the accompanying
baseline/comparison documents, are the correction.

---

## TRACK-A-PHASE-MEASURE

| Field | Value |
|---|---|
| Track name | Phase/Measure Theory reconstruction lineage |
| Corpus roots | `docs/knowledgeos/brainstorming/phase_measure_theory/` (the Step series), plus `docs/knowledgeos/brainstorming/verification/canonical-construction/`, `.../verification/consolidation/`, `.../verification/step-272/`, `.../verification/handoff/`, `.../verification/witnesses/` — the cluster independently confirmed ADMITTED (not part of the gap-discovery exclusion) per the separate `MD-043` provenance adjudication (shared first-commit `70fee73c8`, admitted per that commit's own message separating `verification/`, `phase_measure_theory/`, `three_model_convergence/` as distinct bullets) |
| Forbidden inputs | `docs/knowledgeos/brainstorming/verification/gap-discovery/` (any file, any subfolder — `exec/`, `second-order/`, `readiness/`, `step-272/` **within gap-discovery** is still gap-discovery, distinct from the standalone `verification/step-272/` admitted above if they differ — verify per-path, not per-name); `three_model_convergence/`; `knowledgeos_theory_research/` (both permanently firewalled, unrelated to this track-separation question) |
| Construction lineage | BC-02.14 → BC-02.15 → BC-02.15-KERNEL → BC-02.16 → BC-02.17 → BC-02.18-R1 → BC-02.19/KSME-01 → KSME-02 → BC-02.20/KSME-03 |
| Current mathematical objects | `KO-001`…`KO-013` (K-object registry, BC-02.14); Step 259's congruence-testing methodology (never executed with concrete bodies, source-side); S0881's real named observations (`Coverage`, `EpistemicDebt_set`, `Ready`, `CriticalGaps_set`) |
| Current executable objects | `ksme.py`/`ksme02.py`/`ksme03.py` (`BC-02.19-KSME-exec/`) — `ksme02.py`'s S0881 oracle-minimal quotient (SOURCE-grounded observations); `ksme03.py`'s HYPOTHESIS-tier `T` (built ONLY from `oderive.py`'s FORCES table, itself `canonical-construction/`-sourced — admissible) |
| Current evidence level | `SOURCE-ESTABLISHED` observations exist (S0881); `SOURCE-ESTABLISHED` transition semantics do **not** exist (KSME-03's central negative finding, exhaustively searched); `HYPOTHESIS`-tier transition semantics exist (KSME-03's own construction) |
| Current unresolved questions | Whether any as-yet-unread `phase_measure_theory`/admitted-`verification`-cluster file supplies real transition effects (KSME-03 recommended, not yet done, a bounded search of Step 260+ specifically for effects — separate from the gap-discovery detour) |

## TRACK-B-GAP-DISCOVERY

| Field | Value |
|---|---|
| Track name | Independent Gap-Discovery session |
| Corpus roots | `docs/knowledgeos/brainstorming/verification/gap-discovery/` (all subfolders: `exec/`, `second-order/`, `readiness/`, `step-272/` *as it exists inside gap-discovery*, `gap-update-2026-09-02/`, `oq4-witness/`, etc.) |
| Forbidden inputs | Any `phase_measure_theory/` file; any `TRACK-A-PHASE-MEASURE` executable code or derived construction (`ksme.py`/`ksme02.py`/`ksme03.py`, their K-object registry entries as definitional inputs — they may be *compared against*, never *imported into* Track B's own construction) |
| Construction lineage | Self-contained; self-describes as *"Independent gap-discovery session"* (`kos_kernel.py`'s own docstring); not part of the BC-02.x numbering by the user's original design |
| Current mathematical objects | `K=(𝒜,ℛ)` over `Assertion=(id,P,e,c,t,Π)` (`kos_kernel.py`); `Obj`/`State` over `(id,content,origin,status,sup,merged)` (`so_model.py`) — **two different concrete state representations within Track B itself**, not yet reconciled with each other |
| Current executable objects | `kos_kernel.py`, `exp_congruence.py`, `exp_identity.py`, `exp_measurement.py`, `exp_ontology.py`, `exp_assertion.py`, `exp_sigma.py`, `exp_provenance.py` (`exec/`); `so_model.py`, `so_exp01_congruence_matrix.py`, `so_exp02–06` (`second-order/exec/`); `sufficiency.py`, `premise_audit.py`, etc. (`step-272/exec/`, gap-discovery's own copy); `minimum_implementable.py`, `verify_readiness_claims.py` (`readiness/exec/`) |
| Current evidence level | `EXECUTED` (self-disclosed, non-authoritative: *"Nothing here is architecture... it is a witness"*) for 6+4 named operations, each with disclosed multi-variant construction; **not** `SOURCE-ESTABLISHED` in the strict sense used elsewhere in this investigation — the state SHAPE cites real corpus sections (256.x/257.x/259.x/262/263/265/267/269), the EFFECT bodies are this track's own disclosed construction |
| Current unresolved questions | Raw-field-level relevance lattice (KSME-05 §5, carried into this registry as a Track-B TODO); whether a differently-constructed `𝔐_T` changes `K_R^B`; the 10 named operations this track never implements (`Validate, Promote, Reintroduce, Replay, Assess, Authorize, Determine, Derive, Qualify, Split`) |

---

## Artifact reclassification (existing work, relabeled not deleted)

| Artifact | Prior label | Corrected `source_track` |
|---|---|---|
| `BC-02.21-KSME04-...md` + `ksme04.py` + `results04.json` | "BC-02.x continuation" | **`TRACK-B-GAP-DISCOVERY`** (extension of `so_model.py`/`so_exp01`, not a Track-A derivation step) |
| `BC-02.22-KSME05-...md` + `ksme05.py`/`ksme05_refinement_check.py` + `results05*.json` | "BC-02.x continuation" | **`TRACK-B-GAP-DISCOVERY`** (same reason) |
| `BC-02.14`–`BC-02.20` (K-object registry through KSME-03) | — | **`TRACK-A-PHASE-MEASURE`** (unaffected, confirmed clean — no gap-discovery import found in any of these on review) |

## Rule for all future artifacts

Every new computational or documentary artifact in this investigation must
declare, at the top:

```
source_track: TRACK-A-PHASE-MEASURE | TRACK-B-GAP-DISCOVERY | CROSS-TRACK-COMPARISON
input_artifacts: [...]
derived_from: [...]
cross_track_dependency: none | <named, with justification>
```

A `cross_track_dependency` other than `none` is only permitted for documents
explicitly of kind `CROSS-TRACK-COMPARISON`, and even there only as a
*comparison target*, never as a *definitional input*.
