# Admissibility Preparation

**No admission decision is made here** — this classifies what a future decision would weigh, exactly
as MD-030 did for the executable lane.

| Artifact | Classification | Reasoning |
|---|---|---|
| `docs/knowledgeos/research/kernel-reduction/06-composition-rules.md` | **SCIENTIFICALLY RELEVANT — ADMISSION MAY BE NEEDED** | Supplies, via a narrative-only chain with the two already-admitted files, the exact input-carrier specification MD-029 found missing (`08`). Provenance is *identical* to the two already-admitted files (same 2026-09-06 commit — `02`/`06`), not weaker. Not classified `ADMISSION NECESSARY FOR NEXT TEST` because no test is being proposed by this study, and because the mtime evidence (`06`) suggests common-cause with the executable rule, which is a relevant consideration for whoever makes the actual admission decision, not a blocking one on its own |
| `nrna1/research/kernel-reduction/kr/carriers.py` (the specific derivation-rule lines) | **PROVENANCE BLOCK**, unchanged from MD-030 | Zero git history, self-declared `[EXP]`/non-canonical, internally contested via `V6` — none of these three qualifications is affected by this study's findings (`12`, claim B) |
| The `V6` alternative rule (`variants.py`) | **CONTRADICTION REQUIRES ADJUDICATION, IF EITHER SOURCE IS EVER ADMITTED FOR A COMPOSITION TEST** | Not a contradiction between `06` and the executable baseline (they agree) — but a genuine, unresolved choice between two tested candidates for the same derivation step that no source read by this study resolves. Flagged here so a future admission decision does not accidentally admit only the baseline rule as if it were uncontested |
| `06`'s own explanatory prose (the `Claim`/`entailment`/DEDUCTIVE restriction; the `Qualify`/`Evidence` dependency) | **SCIENTIFICALLY RELEVANT — ADMISSION MAY BE NEEDED, SAME BASIS AS THE TABLE ITSELF** | Not classified separately from the table — it is part of the same document, same provenance |
| `results/baseline.json`, `results/minimal_kernels.json` (the computed C0-vs-C0-plus consequence) | **PROVENANCE BLOCK, unchanged from MD-030** — still executable-lane, zero git history | `06` does not restate this as a *computed* fact (it states the underlying rule that would produce it, in prose, but not the reachability computation itself) |

## Distinguishing relevance, admissibility, and Model-B membership — restated per this study's own
requirement

Exactly as MD-030 established: a "yes" on relevance does not imply a "yes" on admissibility, and
admission (should it happen) would not automatically confer Model-B membership on `06` any more than
it did for the two already-admitted files (MD-028-DQ-1's own condition, restated in MD-030 `08`).
This study does not revisit or weaken that distinction.

## What is genuinely new here, admissibility-wise

`06` is a materially *stronger* admission candidate than the executable lane was — not because its
content is more certain (it isn't; `06` doesn't even acknowledge `V6`'s existence), but because its
**provenance is identical to material already admitted**, removing the one qualification (weak/zero
git history) that was decisive against the executable lane in MD-030. This study surfaces that fact;
it does not act on it.
