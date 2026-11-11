# Theory turn 2 (the derivation chain): commission, step 2

*Given to reviewers A (kos-lane-reviewer, Opus), B (kos-lane-reviewer, Sonnet) and C (kos-theory-reviewer) after each step-1 answer was saved with its sha256 (`SHA256-STEP1.txt` in this folder).*
- *Mode: topic review (`03-methodology/REVIEWER-INSTRUCTIONS.md`).*
- *Working instructions in force: `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`. Where it differs from the Principal's decision record, the record wins.*
- *W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`. Every file you may open is named below.*

## The question and the deliverable

- **Commission:** `03-methodology/COMMISSIONS-CHAIN-R2-R1-THEORY-TURNS-20261103.md`, turn 2 and the common method. Dispatched 2026-11-11, with the origin rule (§9.1 L96) as a premise, not decided, and Question O (c) decided.
- **Research question, criteria K1–K4 and disproof conditions DC-1–DC-8:** `review/DERIVATION-CHAIN-TURN/RESEARCH-QUESTION.md`, committed before any draft.
- **Deliverable (draft):** `06-theory/manuscript/chapter_09/09.22-the-derivation-chain-unit-of-derivation-and-the-laws.md`, new, **in full**.
- **Also changed:** `chapter_09/09.00-index.md` row 9.22; `chapter_09/09.99-change-log.md`, last row.
- **No model was written or run, and no corpus source was read.** Read every file as it is on disk; **no git history**.

## Questions

1. **REVIEWER-INSTRUCTIONS checks** for a topic review: each claim against the cited book lines; labels; homes; R2; the quote check by hand; R3 for any correction.
2. **Completeness against your step-1 answer:** what did §9.22 leave out that you found? Where you differ, which is right on the evidence?
3. **The unit (§9.22.3):**
   - Are K1–K4 applied correctly to each candidate?
   - Is the pair (U-k, U-r) right, or does one candidate suffice, or none?
   - Is (U-m)'s rejection sound (K3 by §9.21)?
   - Is (U-c)'s rejection fair to the concepts that are decided (authority ✓, relationships ✓)?
4. **The four senses of "primitive":** is each sense real, are their tests right, and is P-axiom needed?
5. **Law 1 end to end (§9.22.4):**
   - Judge each proof step **adequate or inadequate**.
   - Is step 7 (a chain to a recorded root) right under §9.13 L120–125 and R1–R2, including delegated grants, plural bases and annulments?
   - Is the law forced by Req?
   - Is the falsifier right? You may run `ce_model.py` on a scratch copy to probe it (form only).
6. **The marks (§9.22.5):**
   - For each row, is F / C / C-D / I right?
   - Is any law marked F that a system meeting Req can break (DC-5), or marked I that follows from Req (DC-6)?
   - Is the W / K / App classification right (DC-7)?
   - Check in particular rows 2, 7, 8, 10a, 21, 23, 26 and 30.
7. **Consequences (§9.22.6):** stated, not settled? Is anything decided that is the Principal's (the primitive set; deliverable 1's form)?
8. **The disproof conditions DC-1–DC-8,** and the commission's limits (no primitive fixed; primitive minimality kept apart from mechanism minimality; no T9-21; the origin rule used only as a premise).
9. *(Reviewer C in particular)* rigour: is "the unit of derivation" a well-posed notion here, and is the F / C / I trichotomy exhaustive and well defined?

## Files you may open (each by its full path under W)

- This brief; your own saved step-1 answer (`STEP1-ANSWER-<your letter>.md` in this folder); `RESEARCH-QUESTION.md` and `BRIEF-STEP1.md` in this folder.
- `03-methodology/REVIEWER-INSTRUCTIONS.md`; `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`; `03-methodology/COMMISSIONS-CHAIN-R2-R1-THEORY-TURNS-20261103.md`.
- `06-theory/RESEARCH-PURPOSE.md`; `06-theory/THEORY-MAP.md`; `06-theory/CONCEPT-REGISTER.tsv`; `06-theory/manuscript/BOOK-OUTLINE.md`; `06-theory/manuscript/TARGET-TOC.md`.
- `06-theory/manuscript/chapter_09/`:
  - 09.00-index.md, 09.01-kernel-scaffold.md, 09.02-parts-and-whole.md, 09.03-say-no-and-learn.md, 09.04-invariants-that-cannot-fail.md;
  - 09.05-a-recorded-rule-must-reach-the-actor.md, 09.06-boundary-test-authority-recording.md, 09.07-boundary-test-the-evaluator.md;
  - 09.08-is-history-a-primitive.md, 09.09-is-trust-a-primitive.md, 09.10-required-capabilities.md, 09.11-kernel-type-from-req.md;
  - 09.12-evaluator-inputs.md, 09.13-the-evaluator.md, 09.14-when-pairs-suffice.md, 09.15-the-trust-boundary.md, 09.16-reassessment.md;
  - 09.17-the-designated-record-and-its-anchors.md, 09.18-evidence-in-the-record.md;
  - 09.19-req-and-a-when-a-minimal-kernel-is-well-posed.md, 09.20-the-state-space.md, 09.21-the-interface-granularity-batching-and-output-forms.md;
  - **09.22-the-derivation-chain-unit-of-derivation-and-the-laws.md**;
  - 09.99-change-log.md, TODO.md.
- `06-theory/manuscript/chapter_00/00.05-the-invariants.md`.
- `06-theory/manuscript/chapter_01/`: 01.06-nothing-but-an-act-confers.md, 01.07-visibility-derivation-may-narrow-never.md, 01.12-a-record-of-an-act.md, 01.13-authority-as-a-recorded-reference.md, 01.14-state-is-not-authority-roles.md, 01.16-consistency-boundaries-time-and-reproducibility.md.
- `06-theory/manuscript/chapter_03/`: 03.06-relationships.md, 03.08-four-orders.md.
- `06-theory/manuscript/chapter_05/`: 05.05-who-executed-who-authorized.md, 05.06-what-an-act-covers.md.
- `06-theory/manuscript/chapter_10/10.03-ownership-of-invariants.md` (for §10.3 L113).
- W's tools in `tmp/tools/` (read-only; run them on a scratch copy only).
- **Never:**
  - any other reviewer's answer or report (`STEP1-ANSWER-*` of another letter, `REPORT-*`, `SHA256-*`);
  - `00-access/`; `02-FILES.jsonl`;
  - any other review folder; any other file under `03-methodology/`;
  - S0155, S0156, S0157, S0161, S0170, S0174, S0233, S0249, S2444; `docs/knowledge_tranfer/*`;
  - sealed, hold-out or quarantine files; `_batch_input*`; `docs/knowledgeos/chronological-read/*`; `.env*`;
  - application code.
- The allowed list is `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. No corpus source is needed.

## Files you may copy to your scratch directory

The book files above, **each by its own path**; `tmp/tools/*.py`, `tmp/tools/*.tsv` and `tmp/tools/QUOTE-BASELINE.txt`, each by its own path. No directory copy. Run `undefined_terms.py` with `--no-corpus`.

## Rules (the Principal)

- A finding is not resolved because a model run passes. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- **No git history; no globs, listings or probes:** every name you need is above.
- Edit nothing in the repository; use only your scratch directory, with `python3 -I`. No corpus or book text to any outside service.
- **The outside-authors rule:** persons by role; eponymous terms and citations unchanged.

## Report

- **A findings table:** ID · finding · severity (critical / high / normal) · evidence · correction · R3 lines · resolved when.
- **A verdict per part,** using REVIEWER-INSTRUCTIONS' three verdicts: §9.22.2; §9.22.3 (the unit; the senses); §9.22.4 (law 1); §9.22.5 (the marks); §9.22.6; the index and change-log rows.
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened, every file you copied, and every command and search you ran.
- Deliver it once, beginning "DERIVATION REVIEW REPORT <A, B or C>".
