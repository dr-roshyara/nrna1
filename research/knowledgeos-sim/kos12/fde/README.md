# `KR-CONTR-FDE-2026-09` — representation-comparison harness

**EXPERIMENTAL PACKAGE. Nothing here is a KnowledgeOS primitive.**

## Where this lives, and why

The specification was written in Java records. **This repository has no Java.** Production domain is
**PHP under `app/`**; the research lane is **Python under `research/knowledgeos-sim/`**. This package
therefore sits inside the **existing** research structure rather than inventing a parallel
architecture, and Java `record` maps to a frozen dataclass.

## Production boundary — FROZEN

```
app/                          ← PRODUCTION. NOT TOUCHED BY THIS EXPERIMENT.
research/knowledgeos-sim/
  kos12/fde/                  ← this package. Experimental only.
```

## What is implemented

```
evaluation.py   Support · Polarity · Standing · Evaluation · EvaluationAdapter
                fde_conflict_detector          (a DETECTOR, deliberately not named Contr)
boundary.py     Boundary(facet, condition) · FlatReason · LocusModalityReason
                StructuredBoundary            (alternative representations, not one hard-coded)
models.py       ClassicalModel · K3Model · FDEModel · StructuredModel
scenarios.py    18 RequiredDistinctionScenarios + the required-distinction set
harness.py      distinction-preservation engine · witness generator · R1 ≈_{ℛreq} R2
tests.py        T1–T5 · E12 guard · E13 equivalence · composition probe
```

**Central object**, per the E12/E13 evidence:

```
Evaluation Representation  =  Status/Polarity  ×  Typed Boundary/Reason
```

FDE's two-channel `Standing` is **one candidate for the first factor** — not the model.
`Standing` carries **no** reason, by construction: mixing them would merge the two dimensions this
experiment exists to keep apart.

## NOT IMPLEMENTED — a hard boundary

```
KnowledgeOS Contr primitive
KnowledgeOS FDE logic
four-valued KnowledgeState
paraconsistent kernel
Zero primitive
Truth semantics
factivity
Knowledge attribution changes
kernel operators
Theory v1.3 changes
production persistence schema
```

The four combinations of `(S+, S-)` are **derived configurations**, never enum members. The boundary
facet and condition names are **experimental vocabulary carrying no authority**.

## Run

```bash
cd research/knowledgeos-sim && python run_fde.py     # deterministic, no seed
```

Results: `results/fde/*.json` (8). Verdict: `results/fde/verdict.md`.
