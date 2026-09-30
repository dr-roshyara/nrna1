---
source_track: TRACK-A-CONSTRUCTION
input_artifacts: [KSME-17-HISTORICAL-FRAMEWORK, KSME-17-CONSTRUCTION-GAPS-AND-CANDIDATES]
derived_from: [.claude/scripts/knowledgeos-ksme/ess.py, ksme17_materiality.py, bse.py]
cross_track_dependency: none — every BSE regime here carries semantic_status="MIXED", never "SOURCE_GROUNDED"
---

# KSME-17 — Executable Semantic Seed (ESS) and Behavioral Materiality Results

**This executable regime is a new construction derived from historical framework material. It is not a
recovered historical implementation.**

## The seed

`K=(A,R,rollback_marker)` — `A`: a set of `Assertion(id,P,Σ,E,τ,Π,retired)`; `R`: a set of typed relation
triples. 5 operations implemented (`assertion_created` plus the two ambiguous ones each with 2 candidate
completions): `AssertionCreated`, `EvidenceAdded` (`C1`/`C2`), `ValueRevised`, `ConflictResolved`
(`C1`/`C2`), `RollbackPerformed` (`C1`/`C2`). Every field and rule tagged SOURCE or CONSTRUCTION inline in
`ess.py`'s own docstrings — see `KSME-17-CONSTRUCTION-GAPS-AND-CANDIDATES.md` for the full list.

## Materiality experiment — exact results (independently computed, not asserted)

Ran `ksme17_materiality.py`, using BSE's `Regime.behaviorally_equivalent()` (exhaustive search up to
horizon 3) with a disclosed observation set (`assertion_values`, `relations`, `active_count`,
`epistemic_strength`) and disclosed downstream continuation operations.

| Experiment | Result | Interpretation |
|---|---|---|
| `EvidenceAdded` C1 vs C2 | **MATERIAL** (counterexample at sequence length 0) | The two candidates immediately produce different `Sigma` values, visible to any observation that inspects epistemic status. The "may" ambiguity is confirmed Kernel-material under this plausible, disclosed observation regime — not cosmetic. |
| `ConflictResolved` C1 vs C2 | **MATERIAL** (counterexample at sequence length 0) | Same pattern: "record only" vs "evidence-based retraction" immediately diverge on the loser assertion's `Sigma`. |
| `Rollback` C1 vs C2, under the **base** observation set (no provenance inspection) | **IMMATERIAL** — no distinguishing sequence found up to horizon 3, across all 4 base observations | **The decisive result of this pass.** The historical source's own claim ("different provenance") is completely unwitnessed by any observation that doesn't specifically look for it. |
| `Rollback` C1 vs C2, under an **augmented** observation set (adds a direct provenance-marker inspection) | **MATERIAL** (counterexample at sequence length 0) | Confirms the distinction is representable — but only once a new, disclosed observation capability is constructed. It was never free. |

## The methodological finding this pass surfaced (disclosed, not hidden)

Materiality is **observation-set-relative**, not an absolute property of a construction choice. `G1`'s
two materiality verdicts are unsurprising given the observation set directly inspects the field the
candidates differ on — this confirms the ambiguity is real and consequential for at least one plausible
consumer (anything checking epistemic strength), not that it is "deeply" material in some universal sense.
`G3`'s result is sharper precisely because it holds across TWO different, both-disclosed observation
regimes with opposite verdicts — this is the genuinely informative comparison, and it computationally
confirms KSME-16's own qualitative flag ("the representation does not contain enough information to
witness the difference") rather than merely restating it.

## What this experiment does not establish

Does not select `C1` or `C2` as canonical for any operation. Does not claim the historical corpus
specifies any of these rules — every rule is CONSTRUCTION, disclosed in `ess.py`. Does not extend to
nondeterministic/relational semantics for G1 (BSE v1 is deterministic-only, per `KSME-15-ADDENDUM-
HARDENING.md`) — a real, out-of-scope alternative reading of "may," named not built.
