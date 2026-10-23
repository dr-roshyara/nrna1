# Consolidation S0101–S0130: brief for reviewers A (Opus) and B (Sonnet)

*Read first, and follow: `03-methodology/REVIEWER-INSTRUCTIONS.md` (consolidation mode), and `review/CONSOLIDATION-S0101-S0130/SCOPE.md` (the object). W = the book's workspace.*

## What to deliver

1. **Concept verdicts** for CON-0184–CON-0237, per SCOPE:
   - a second, independent verdict on the six VALIDATED;
   - a verdict on each PROVISIONAL in scope;
   - a check of the INVALID cores as restated.
2. **The rotated chapters, Ch. 0 and Ch. 1, in full.** Definitions, derivations, assumptions, quotations (spot-check against the sources), cross-references, and stale text after S0100. **Count the defects you find in them separately** (entry 58 records this).
3. **The sequence check (SEQ)** of the sections in scope: is a concept used before it is defined, or never defined? Is a citation stale?
4. **The architecture view:**
   - Does the derivation run from the primitives through the laws to the results?
   - What did C19–C24 add to the primitives, the laws or the kernel/application boundary, and what is only observation of one estate?
   - Are any parts isolated, circular or unsupported?
   - Kernel or application, minimal, stable, for each concept.
5. **The previous settlements' changes** (C24's settlement): spot-check that its rows hold.
6. **Pattern checks**, recurring in C19–C24:
   - an authority's decision read through a producer's or registrar's gloss;
   - a source's self-description adopted as shown;
   - universal claims drawn from a search.

   Find any remaining instance.

## How

- Corpus files: only those on `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`.
- **No Ch. 0–2 basis files** (purpose "basis:" in `W/archive/00-access/READ-LOG.tsv`); the book's chapter files are fine.
- Never `.env*`, `_batch_input*`, P3B-HOLDOUT, sealed or quarantine files, or `docs/knowledgeos/chronological-read/*`.
- No corpus or book text outside. Edit nothing.
- Do not read the other reviewer's report (`REPORT-A.md` / `REPORT-B.md`).
- Scratch scripts only in the scratchpad with your prefix (`cons_a_` / `cons_b_`), run with `python3 -I`.

## Report

Deliver once, through the hand-back, beginning with "CONSOLIDATION S0101-S0130 REPORT A" (or "B"). In tables:
- verdicts;
- findings (ID A-nn / B-nn · finding · severity · type · evidence · correction · resolved when);
- the rotated-chapter defect count;
- the SEQ result;
- the architecture view;
- coverage;
- a summary of at most 12 lines;
- one line on process independence.

For every correction you propose to a concept or section, **list its R3 dependents** (the items that cite it), so the integration review can check them.
