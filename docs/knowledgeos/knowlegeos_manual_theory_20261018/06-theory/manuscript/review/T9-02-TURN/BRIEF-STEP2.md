# The T9-02 theory turn (the state space): commission, steps 2 and 3

*Given to reviewers A (kos-lane-reviewer, Opus), B (kos-lane-reviewer, Sonnet) and C (kos-theory-reviewer) after each step-1 answer was saved with its sha256 (`SHA256-STEP1.txt` in this folder). Mode: topic review (`03-methodology/REVIEWER-INSTRUCTIONS.md`). W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/` (repository-relative).*

## The question and the deliverable

- **Commission:** `03-methodology/COMMISSION-T9-02-STATE-SPACE-THEORY-TURN-20261102.md` (ISSUED, reviewers (b)): Part 1 (the Principal's six steps), Part 2.2 (method), 2.3 (limits), 2.4 (review).
- **Research question and disproof conditions DC-1–DC-7:** `review/T9-02-TURN/RESEARCH-QUESTION.md`, committed before any model or draft, with its process disclosure.
- **Deliverable (draft):** `06-theory/manuscript/chapter_09/09.20-the-state-space.md`, new, **in full**.
- **Also changed:** `chapter_09/09.00-index.md` row 9.20; `chapter_09/09.99-change-log.md` last row. TODO and outline changes are **proposed** in §9.20.8, not yet applied.
- **Model:** `tmp/tools/t902_state_model.py` (imports `ce_model.py`, unchanged); run `tmp/tools/t902_state_model-run-20261102.txt`.
- **No corpus source was read for this turn.** Read every file as it is on disk; **no git history** of any kind (the Principal's ruling, 2026-11-02).

## Mechanical checks at the draft (the Professor's runs; re-run what you rely on)

- Quote check (`qc.py`, the Professor's script, against TODO, §9.10, §9.13, §9.16, §9.18, §9.19, §1.3): every book quotation matched after two were corrected; the remaining misses are the Professor's own proposed wording in §9.20.8 and words quoted from §9.20 itself.
- R2 (`undefined_terms.py --scope 09.20`): tiers A–C none in scope; tier D: above, addressed, remember, vector (ordinary English). Judge every in-scope candidate.
- `proof_step_check.py` recognises only "Lemma/Theorem/Proposition" headings; §9.20's Th1–Th4 are not scanned by it. The proof-step table for Th1 is in §9.20.4.

## Questions

1. **REVIEWER-INSTRUCTIONS checks** for a topic review: each claim against the cited book lines; labels (Src, Ded, Int, Hyp, Ext, EA); homes; levels; R2; the quote check; R3 for any correction.
2. **Completeness against your step-1 answer:** what did §9.20 leave out that you found? Where do you and §9.20 differ, and on the evidence, which is right?
3. **Step 1 (§9.20.2):** is the current position stated accurately, with the right lines, and are the decisions in force the right ones?
4. **Step 2 (§9.20.3):** for each distinction, are the five answers right? Is any distinction counted necessary merely because it appears in the current formulation? Is any required distinction missing?
5. **Th1–Th4 (§9.20.4):** judge each proof step **adequate or inadequate**, with your own proof-step reasoning where you disagree. Are the premises (C1 = constitute; Req over D; Q3's reference; the inputs; the kinds outside D) stated where used? Is Th1's scope right? Does M1 refute V as stated, and is the step "rebuilds the record's information" justified? Run `t902_state_model.py` **on a scratch copy** and probe it (other pairs, other extensions, W's kinds).
6. **Minimality (§9.20.5, DC-2):** is the claim about Q2 sound? Is anything stated more strongly than its evidence? Can you exhibit two records differing in an entry about no item that no observer, guard or extension distinguishes, or show there is none?
7. **The outcome (§9.20.6):** is "partially resolved" right on the evidence, and are the reasons the right ones?
8. **The table (§9.20.7):** is each attribute's kind right? Is any row decided beyond what the book decides (F13, F37, F6, T9-14, G7's F13 part, T0-07, F13a)?
9. **Consequences (§9.20.8):** is anything an implication treated as a decision, or a TODO closed by implication? Are the F37 branch implications right, and do they decide nothing?
10. **The disproof conditions:** does §9.20 meet any of DC-1–DC-7 as fixed in RESEARCH-QUESTION.md?
11. **The commission's limits (Part 2.3):** state space not treated as settled; no primitive accepted; existence kept apart from primitive minimality; no fork resolved beyond existing decisions; no derivation-chain turn, no T9-21.
12. *(Reviewer C in particular)* mathematical rigour throughout: the use of sufficiency, observational equivalence and minimality; whether a Nerode-style quotient is the right frame for DC-2.

## Files you may open

- This brief; your own saved step-1 answer; `RESEARCH-QUESTION.md` and `BRIEF-STEP1.md` in this folder; `03-methodology/REVIEWER-INSTRUCTIONS.md`; `03-methodology/COMMISSION-T9-02-STATE-SPACE-THEORY-TURN-20261102.md`.
- The working book: `06-theory/manuscript/chapter_*/` top-level `.md` files (any section, including `chapter_09/09.20-*`, `09.00-index.md`, `09.99-change-log.md`, `TODO.md`), `06-theory/manuscript/BOOK-OUTLINE.md`, `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/RESEARCH-PURPOSE.md`.
- W's tools (`tmp/tools/`, read-only; run them on a scratch copy only), including `t902_state_model.py` and its run file.
- **Never:** any other reviewer's answer or report (`STEP1-ANSWER-*` of another letter, `REPORT-*`, `SHA256-*`); `00-access/`; any review folder other than this one (restrict every search to `chapter_*` top-level files and the files above; do not search `06-theory/manuscript/` recursively); any other file under `03-methodology/`; `review-architecture/`, `review-implementation/`; S0155, S0156, S0157, S0161, S0170, S0174, S0233, S0249; `docs/knowledge_tranfer/*`; sealed, hold-out or quarantine files; `_batch_input*`; `docs/knowledgeos/chronological-read/*`; `.env*`; any application code. If a file is refused or not allowed, record it and continue; do not inspect it another way.

## Files you may copy to your scratch directory (only these; the Principal's rule of 2026-11-02)

- book files from the list above, **each named individually** (no directory copy: no `cp -r`, `rsync` or glob of `06-theory/` or `manuscript/`);
- `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/RESEARCH-PURPOSE.md`, `06-theory/manuscript/BOOK-OUTLINE.md`;
- `tmp/tools/*.py`, `tmp/tools/*.tsv`, `tmp/tools/QUOTE-BASELINE.txt`, `tmp/tools/t902_state_model-run-20261102.txt`.

Copy nothing else. If a tool needs a file not on this list, do not run it; record that.

## Rules (the Principal)

- A finding is not resolved because a model run passes. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart; never state more than the evidence supports.
- **No git history:** no `git log`, `git show`, `git diff`, `git blame`, historical versions or commit listings of any file.
- Edit nothing in the repository. Use only your scratch directory, outside the repository, with `python3 -I`. No corpus or book text to any outside service.
- **The outside-authors rule:** persons named as people in prose are not reproduced (refer to them by role); eponymous technical terms and bibliographic citations of external works stay unchanged; paths and filenames are quoted unchanged. Do not reproduce any private person's name.

## Report

- Tables: findings (ID · finding · severity: critical / high / normal · evidence · correction · R3 lines · resolved when); a verdict per part (§9.20.2, §9.20.3, each of Th1–Th4, §9.20.5, §9.20.6, §9.20.7, §9.20.8, the model, the index and change-log rows), using REVIEWER-INSTRUCTIONS' three verdicts.
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened, every file you copied, and every command and search you ran.
- Deliver it once, beginning "T9-02 REVIEW REPORT <A, B or C>".
