# MD-034 — Targeted Characterization of `12-randomized-results.md`

## Authorization

User authorization, 2026-09-08, following the same "write your disagreement first" pattern. No
blocking disagreement was raised — a clean, appropriately narrow characterization study, directly
following MD-033's own named next step. This is a **characterization/admissibility-recommendation
study only** — it does not admit anything.

## Central, load-bearing finding

`12-randomized-results.md` — the file `03-capability-model.md` and `04-operator-contracts.md`
themselves cite as "§12" — **contains an extensive, explicit treatment of `V6`.** This materially
changes the picture MD-031/MD-033 established for the *admissible* population (which correctly found
`V6` absent from `03`/`04`/`06`): `V6` is not merely an executable-lane artifact — it is a
deliberately-designed, named, and partially-justified robustness variant in the same numbered
narrative document series, sitting exactly where `03`/`04`'s own citations pointed, just not yet
admitted. MD-031's and MD-033's own findings remain **factually correct as scoped** (the three files
they examined genuinely do not mention V6) — this study does not revise them, it extends the picture
to a file neither of them was authorized to read.

`12`'s own table (line 144): **`V6` — "a `Verdict` requires a surviving-defeater step" — makes
`Discriminate`, `DetectGap` derivable** — an exact match to the executable lane's own `variants.py`
description (MD-030). More consequentially, `12`'s own "Causal / model-criticism check" section
(lines 186–205) gives a **substantive, source-stated argument that the currently-admitted baseline
rule (`V0`, already admitted via `06`) has a known defect that `V6` alone corrects**: *"The baseline
model permits the failure mode `fit ⇒ validation`. Only V6 blocks it structurally. This is a defect
of the baseline capability model, not of any operator."*

## Non-goals (restated, binding)

Does not admit `12-randomized-results.md`. Does not execute code. Does not run a composition test.
Does not resolve V6 by inference. Does not modify `classification-register.tsv`, MD-024–033, any
source file, or any frozen artifact. Does not select a model, canonicalize anything, open Stage 07,
or touch K-1/K-2.

## Artifact map

| File | Content |
|---|---|
| `00_index.md` | this file |
| `01_authorization-and-scope.md` | scope, disagreement check |
| `02_file-identity-and-provenance.md` | git/mtime/citation evidence |
| `03_complete-variant-census.md` | all 8 variants (V0–V7), quoted exactly |
| `04_v6-assessment.md` | the central V6 finding, in full, including the causal-criticism argument |
| `05_validate-specification-findings.md` | precondition/postcondition/failure census against this file |
| `06_mathematical-audit.md` | does this alter MD-033's minimal Validate object |
| `07_ddd-audit.md` | domain-concept vs. label classification for this file's content |
| `08_admissibility-assessment.md` | the required A–D recommendation, not a decision |
| `09_claim-and-counterclaim-audit.md` | re-examines MD-031/033's own V6 findings against this new evidence |
| `10_verification-and-completion.md` | verification suite + required closing statement |
