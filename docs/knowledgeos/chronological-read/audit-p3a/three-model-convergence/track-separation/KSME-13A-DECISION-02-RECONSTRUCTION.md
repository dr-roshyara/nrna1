---
source_track: TRACK-A-PHASE-MEASURE
derived_from: [mathematical_ideas_that_can_be_implemented/20260902-170000, theory-08]
cross_track_dependency: none
---

# KSME-13A — DECISION-02 Reconstruction

## Corrected question

`DECISION-02` (`20260902-170000` §B.2, confirmed by direct read, supersedes an earlier same-day proposal,
itself superseded by nothing later found in-scope): **"Does `φ` identify semantically meaningful
evaluation frames (Option B), or does it merely organize/chunk evidence for recording purposes
(Option A)?"**

This is **not** a choice between two competing state-carrier representations. It is a normative/semantic
status question about a single frame-qualifier `φ⊇{time,context}`, governing whether the `majority`
composition rule violates a non-evidential-invariance property (`C7`, frame-refinement invariance).

| | Option A — representational partition | Option B — semantic context |
|---|---|---|
| commits to | chunking is a recording artefact | frame individuation is evidential content |
| `majority` under `C7` | **violates** | does not violate |
| what it owes | — | a semantics for frame relation/weighting |

Explicit corpus caveat: *"every experimental result so far is compatible with all six candidate
readings"* — Option A/B are two poles of a 6-reading space, not a binary. `theory-08 §5` and `170000`
Part B both frame this as a **decision**, not an experiment — structurally identical to the `T_B`-
membership "governance, not derivation" pattern already found elsewhere in this investigation.

## Why the originally-proposed `M_φ` vs `M_Π` experiment does not target this

There is no data-structure difference to implement — both options use the same `φ` qualifier under two
different *claims about what it means*. Building two carriers and comparing behavioral preservation would
answer a different question than the one the corpus actually poses.

## The correctly-targeted experiment (named, not yet executed)

Tag evidence with `φ` once. Test whether `majority`'s output is invariant under a frame-refinement
transformation (`C7`). A **PASS** supports Option B (semantic); a **FAIL** supports Option A (bookkeeping).
This is implementable with what's already in-scope for `Standing`/`Boundary`/`Evaluation`, but the exact
formulas for `majority` and `C7` itself currently live in `docs/knowledgeos/research/` — see
`KSME-13A-CORPUS-ADMISSIBILITY.md` for the pending authorization question this depends on.

## What this reconstruction does not do

Does not resolve `DECISION-02`. Does not implement the `C7` test (blocked on the admissibility question).
Does not select Option A or B. Records the corrected question and the corrected experiment design only.
