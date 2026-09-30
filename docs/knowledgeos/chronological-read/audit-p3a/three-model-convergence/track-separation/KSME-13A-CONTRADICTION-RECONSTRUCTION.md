---
source_track: TRACK-A-PHASE-MEASURE
derived_from: [Step 32, Step 60, step-292/04, theory-08, KR-CONTR-FDE writeup]
cross_track_dependency: none
---

# KSME-13A — Contradiction Reconstruction

Consolidates KSME-12's contradiction matrix with KSME-13A's deeper signature-level findings. Supersedes
no prior finding; sharpens several.

## The contradiction apparatus is five disjoint object families, not two competing models

1. **`Conflict_Step32algebra`** — named, never specified (§32.76).
2. **`ConflictSet`/`ConflictRecord`/`Conflicted`/`A+¬A`** — Step 32's own state-level treatment, internally
   fragmented into 4 further disjoint symbols (see `KSME-13A-NOTATION-COLLISION-REGISTRY.md`).
3. **`Conflict_Step60predicate`** — fully specified, `Support⁺(p)>0∧Support⁻(p)>0`, and its own temporal
   variant `Conflict(p,t)` (different arity, unrelated).
4. **`Contr_step292`** — a named prerequisite for gating δ, no signature or algorithm ever given.
5. **`Contr_theory08`/`Contr≠Satisfied`** — a value in an evaluation codomain, not a checkable predicate.

None of these five families cross-reference each other anywhere in the admissible corpus, confirmed by
direct full reads of the primary sources plus targeted bidirectional searches.

## The naive reconciliation is refuted; the careful one is a disclosed hypothesis, not a fact

A blanket `Contr`-style gate on every transition is refuted by Step 60's own worked example: `Merge({p},
{¬p})→{p,¬p}` was checked by the document's author against `Conflict(p)=Support⁺(p)>0∧Support⁻(p)>0` and
marked "PASS" — **this is a hand-verified narrative example, not machine-executed code** (no `exec/`
folder exists for Step 60; this corrects KSME-11/12's "EXECUTED" tier to "source-asserted worked
example"). The logical force of the refutation stands regardless: blanket gating would forbid exactly the
behavior Step 60's own architecture calls legitimate ("Merge ≠ Resolve," §60.22-24).

A scoped reconciliation via Step 60.73-74's Operational/Epistemic split remains plausible and
source-groundable in its pieces, but is not stated by the corpus as a unified design — carry forward only
as a disclosed candidate architectural decision.

## `Resolve` cannot be implemented without inventing its algorithm

`Resolve(K_M,E)→K_R` (§60.23) names four input kinds (`AdditionalEvidence`, `DomainPolicy`,
`HumanAdjudication`, `StatisticalModel`) with zero procedure. It depends on `Policy`, which Step 277
itself explicitly discloses as unformalized (§277.36: "has not fully formalized `Policy` or `Authority`").
No forward-thread continuation exists anywhere in the admissible corpus (checked against the D-series and
the full `step-27X`-`29X` research subtree — only document-metadata "revision:" headers found, not the
epistemic operation).

## What survives as genuinely usable

`Standing`/`Boundary`/`Evaluation` (the FDE apparatus, `20260902-175306`/`20260902-163000`) is real,
implementation-ready, executable — the one part of the whole contradiction apparatus that is not
underspecified. `DECISION-02` governs whether `φ`'s frame-boundary carries semantic weight, not this
apparatus's internal structure — see `KSME-13A-DECISION-02-RECONSTRUCTION.md`.

## Verdict

The contradiction apparatus, as a whole, is `PARTIALLY GROUNDED` — one real executable component
(`Standing`/`Boundary`/`Evaluation`), five disjoint named-but-unspecified families, and one confirmed
absent dependency (`Policy`/`Authority`) blocking `Resolve`. Any KSME-13B implementation must scope
`Resolve`, `Revision`, `⊕`, and both `Contr` senses out of a first bounded regime, or disclose new,
labeled definitions for them as decisions, never as recovered facts.
