# Theory turn 1 (T9-22: A and the output forms): commission, step 2

*Given to reviewers A (kos-lane-reviewer, Opus), B (kos-lane-reviewer, Sonnet) and C (kos-theory-reviewer) after each step-1 answer was saved with its sha256 (`SHA256-STEP1.txt` in this folder).*
- *Mode: topic review (`03-methodology/REVIEWER-INSTRUCTIONS.md`).*
- *Working instructions in force: `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`. Where it differs from the Principal's decision record, the record wins.*
- *W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/` (repository-relative).*

## The question and the deliverable

- **Commission:** `03-methodology/COMMISSIONS-CHAIN-R2-R1-THEORY-TURNS-20261103.md`, turn 1 and the common method. Dispatched 2026-11-11 (index T2).
- **Research question and disproof conditions DC-1–DC-8:** `review/T9-22-TURN/RESEARCH-QUESTION.md`, committed before any draft.
- **Deliverable (draft):** `06-theory/manuscript/chapter_09/09.21-the-interface-granularity-batching-and-output-forms.md`, new, **in full**.
- **Also changed:** `chapter_09/09.00-index.md` row 9.21; `chapter_09/09.99-change-log.md`, last row. TODO and outline changes are **proposed** in §9.21.6, not yet applied.
- **No model was written or run, and no corpus source was read.** Read every file as it is on disk; **no git history** of any kind.

## Questions

1. **REVIEWER-INSTRUCTIONS checks** for a topic review: each claim against the cited book lines; labels (Src, Ded, Int, Hyp, Ext, EA); homes; R2; the quote check by hand; R3 for any correction.
2. **Completeness against your step-1 answer:** what did §9.21 leave out that you found? Where do you and §9.21 differ, and on the evidence, which is right?
3. **(1) G-kind = G-act:** is the derivation sound, and is its scope (the kinds admitted to D and W) right?
4. **(2) and (3), composites and batching:**
   - Does §9.1 C6's input-origin condition, as written, exclude them?
   - Is "one input, one step" in the book, or is it assumed?
   - Is the unobservability argument sound (per-entry guards, evaluable prefixes, cuts as prefixes), including for all-or-nothing batches and for removals?
   - Does any guard read more than the record before the entry (for example the single-use guard under a batch)?
5. **(4) G-guard:** is "no observer separates them" right, and is leaving the choice to T9-03 within the commission's limits?
6. **(5) the output forms:**
   - Is Q2's form derived from its text, and its scope from D's kinds (only `adopt`, `revoke` and evidence entries name an item)?
   - Is the R2 check right (the merge rule makes whole-entry output order-independent)?
   - Is the claim that identity and whole-entry forms differ **exactly** in the recorder, the status and content beyond the required fields right, including the grant's owner and recording application (§9.16 L93 N1)?
   - Does Q1's act list include the root and the grant (Int)?
7. **Minimality (§9.21.4):** is each row of the table right? Is anything stated more strongly than its evidence? In particular:
   - an entry no output returns (a grant never cited);
   - annulments, endings and removals that target an entry about k: are they "about k" for Q2?
8. **T9-22 and the question (§9.21.5):**
   - Is "reduced to one decision" right, or is Q1's or Q3's form derivable after all?
   - Is the question well formed, with the right options?
   - Are the consequences for T9-03 and T9-04 stated, not settled?
9. **The disproof conditions:** does §9.21 meet any of DC-1–DC-8?
10. **The commission's limits:**
    - G is fixed only by derivation;
    - no primitive fixed; no selection criterion;
    - no open fork (F13, F37, F6, T9-14, T9-21, G7's F13 part) treated as decided;
    - no T9-21, no Ch. 10 change.
11. *(Reviewer C in particular)* rigour: the use of observational equivalence and quotients in §9.21.4; whether "excluded as written" is a derivation or a reading.

## Files you may open

- This brief; your own saved step-1 answer (`STEP1-ANSWER-<your letter>.md`); `RESEARCH-QUESTION.md` and `BRIEF-STEP1.md` in this folder.
- `03-methodology/REVIEWER-INSTRUCTIONS.md`; `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`; `03-methodology/COMMISSIONS-CHAIN-R2-R1-THEORY-TURNS-20261103.md`.
- The working book:
  - `06-theory/manuscript/chapter_*/` top-level `.md` files: any section, including `chapter_09/09.21-*`, `09.00-index.md`, `09.99-change-log.md` and `TODO.md`;
  - `06-theory/manuscript/BOOK-OUTLINE.md`, `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/RESEARCH-PURPOSE.md`.
- W's tools (`tmp/tools/`, read-only; run them on a scratch copy only).
- **Never:**
  - any other reviewer's answer or report (`STEP1-ANSWER-*` of another letter, `REPORT-*`, `SHA256-*`);
  - `00-access/`; `02-FILES.jsonl`;
  - any review folder other than this one (restrict every search to `chapter_*` top-level files and the files above; do not search `06-theory/manuscript/` recursively);
  - any other file under `03-methodology/`; `review-architecture/`, `review-implementation/`;
  - S0155, S0156, S0157, S0161, S0170, S0174, S0233, S0249, S2444; `docs/knowledge_tranfer/*`;
  - sealed, hold-out or quarantine files; `_batch_input*`; `docs/knowledgeos/chronological-read/*`; `.env*`;
  - any application code.
- The allowed list is `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. No corpus source is needed. If you consult one, it must be on that list, and you say so.
- If a file is refused or not allowed, record it and continue; do not inspect it another way.

## Files you may copy to your scratch directory (only these)

- Book files from the list above, **each named individually**. No directory copy: no `cp -r`, `rsync` or glob of `06-theory/` or `manuscript/`.
- `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/RESEARCH-PURPOSE.md`, `06-theory/manuscript/BOOK-OUTLINE.md`.
- `tmp/tools/*.py`, `tmp/tools/*.tsv` and `tmp/tools/QUOTE-BASELINE.txt`, each by its own path.

Copy nothing else. If a tool needs a file not on this list, do not run it; record that. Run `undefined_terms.py` with `--no-corpus`.

## Rules (the Principal)

- A finding is not resolved because a model run passes. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart; never state more than the evidence supports.
- **No git history:** no `git log`, `git show`, `git diff`, `git blame`, historical versions or commit listings of any file.
- **No globs:** every search, listing, read or copy names its files one by one by full path.
- Edit nothing in the repository. Use only your scratch directory, outside the repository, with `python3 -I`. No corpus or book text to any outside service.
- **The outside-authors rule:** persons named as people in prose are not reproduced (refer to them by role). Eponymous technical terms and bibliographic citations of external works stay unchanged; paths and filenames are quoted unchanged.

## Report

- **A findings table:** ID · finding · severity (critical / high / normal) · evidence · correction · R3 lines · resolved when.
- **A verdict per part,** using REVIEWER-INSTRUCTIONS' three verdicts: §9.21.2; (1); (2); (3); (4); (5); §9.21.4; §9.21.5; §9.21.6; the index and change-log rows.
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened, every file you copied, and every command and search you ran.
- Deliver it once, beginning "T9-22 REVIEW REPORT <A, B or C>".
