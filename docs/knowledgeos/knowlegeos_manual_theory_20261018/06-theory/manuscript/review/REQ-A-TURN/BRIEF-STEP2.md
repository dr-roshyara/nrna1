# The Req/A theory turn: commission, steps 2 and 3

*Given to reviewers A (kos-lane-reviewer, Opus), B (kos-lane-reviewer, Sonnet) and C (kos-theory-reviewer) after each step-1 answer was saved with its sha256 (`SHA256-STEP1.txt` in this folder). Mode: topic review (`03-methodology/REVIEWER-INSTRUCTIONS.md`). W = `docs/knowledgeos/knowlegeos_manual_theory_20261018/`; use absolute paths under the repository root. **Re-read your own saved step-1 answer first** (`STEP1-ANSWER-<your letter>.md`).*

## The question and the deliverable

- **Commission:** `03-methodology/COMMISSION-REQ-A-THEORY-TURN-20261101.md` (issued, `96407e3bc`): the deliverables (§3), the limits (§4) and the review conditions (§6).
- **Research question and disproof conditions:** `review/REQ-A-TURN/RESEARCH-QUESTION.md` (`13bce8cd6`), including its process disclosure.
- **Deliverable (draft):** `06-theory/manuscript/chapter_09/09.19-req-and-a-when-a-minimal-kernel-is-well-posed.md`, new, **in full**. Commits `42d353c4e`, `6ae633b7d`, `c5fd16563`, `c3c1b6b09` (`git show <hash>` lists every changed line).
- **Also changed:** `06-theory/manuscript/BOOK-OUTLINE.md` L44 and L179; `chapter_09/TODO.md` T9-01, T9-03, T9-04; `chapter_09/09.00-index.md` row 9.19; `chapter_09/09.99-change-log.md` last row.
- **Model:** `tmp/tools/req_a_model.py`, run `tmp/tools/req_a_model-run-20261101.txt`.
- **No corpus source was read for this turn.**

## Mechanical checks at the draft (the Professor's runs; re-run what you rely on)

- Quote check (`corpus_quote_check.py` on §9.19): 1 found, 0 not found.
- R1 (`register_home_check.py`): 241 rows with a home; 1 flag, CON-0133, inherited, outside this turn.
- R2 (`undefined_terms.py --scope 09.19`): tier A in §9.19: `appender`, now defined in §9.19.4 (G-kind). Judge every remaining in-scope candidate.
- `proof_step_check.py` on §9.19: 0 lemmas scanned (it recognises only "Lemma/Theorem/Proposition" headings); §9.19.10 says so.

## Questions

1. **REVIEWER-INSTRUCTIONS checks** for a topic review: each claim against the cited book lines; labels (Src, Ded, Int, Hyp, Ext, and EA, the labelled engineering assumption); homes; levels; R1; R2; the quote check; R3 for any correction; a reference to a missing claim checked as possible lost content.
2. **Completeness against your step-1 answer:** what did §9.19 leave out that you found? Where do you and §9.19 differ, and on the evidence, which is right?
3. **Task 1 (§9.19.2):** is each class (answered / in part / new capability) right on the evidence? Does the L179 change follow from that evidence only, without marking the item resolved because §9.10 exists?
4. **Disproof conditions:** does §9.19 violate any of F-ins, F-min, F-vac, F-A or F-str as fixed in RESEARCH-QUESTION.md? Does the disclosed order (the model ran before the question was committed) weaken any result?
5. **Req v3 (§9.19.3):** the relation table; Q4 conditional on G7; E1 and N1: are the three boundary analyses kept separate and sound?
6. **A (§9.19.4):** are the boundary B and the granularity G stated separately and soundly? Is the T9-02 escalation right (Req does not need it; the pool does)? The two minimality notions; the observer axis.
7. **Candidates (§9.19.5):** is the status anywhere used as evidence of necessity? Is each removal model or countermodel valid, with the right label?
8. **Proofs (§9.19.6):** for T1–T8, judge each step of the proof-step table **adequate or inadequate**, with your own proof-step reasoning where you disagree. Are the hypotheses (H-down, H-mono, K1, the premise on s₀) stated where used? Are T5's conditions sufficient? Does T6 show what it claims, and no more? Run `req_a_model.py` **on a scratch copy** and probe it (other pools, other invariants).
9. **Minimality (§9.19.7–§9.19.8):** is anything stated more strongly than its evidence? Is a uniqueness claim or a selection among minimal candidates made anywhere, explicitly or implicitly?
10. **U1–U4 (§9.19.9):** well posed, options fair, recommendations justified?
11. **The commission's limits:** engineering knowledge only as a labelled assumption; RESEARCH-PURPOSE unchanged; T9-02 not absorbed; the primitive set not finalised; no selection criterion adopted.
12. **The outline, TODO and index changes:** accurate, and no stronger than §9.19?
13. *(Reviewer C in particular)* mathematical rigour throughout, and the prior-work note's fairness.

## Files you may open

- This brief; your own saved step-1 answer; `RESEARCH-QUESTION.md` and `BRIEF-STEP1.md` in this folder; `03-methodology/REVIEWER-INSTRUCTIONS.md`; `03-methodology/COMMISSION-REQ-A-THEORY-TURN-20261101.md`.
- The working book: `06-theory/manuscript/chapter_*/` (any section), `06-theory/manuscript/BOOK-OUTLINE.md`, `06-theory/manuscript/TARGET-TOC.md`, `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/THEORY-MAP.md`, `06-theory/RESEARCH-PURPOSE.md`, `06-theory/EXTERNAL-SOURCES.md`.
- W's tools (`tmp/tools/`, read-only; run them on a scratch copy only); `git log` and `git show` for the commits named above.
- Corpus sources only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`; check each path there first, and list every one you open.
- **Never:** any other reviewer's answer or report (`STEP1-ANSWER-*` of another letter, `REPORT-*`, `SHA256-*`); `00-access/`; any other review folder (restrict every search to `chapter_*` directories and the files above; do not search `06-theory/manuscript/` recursively); any other file under `03-methodology/`; `review-architecture/`, `review-implementation/`; S0155, S0156, S0157, S0161, S0170, S0174, S0233, S0249; `docs/knowledge_tranfer/*`; sealed, hold-out or quarantine files; `_batch_input*`; `docs/knowledgeos/chronological-read/*`; `.env*`; any application code. If a file is refused or not allowed, record it and continue; do not inspect it another way.

## Rules (the Principal)

- A finding is not resolved because code or a model run passes. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart; never state more than the evidence supports.
- Edit nothing in the repository. Use only your scratch directory, outside the repository, with `python3 -I`. No corpus or book text to any outside service.
- **The outside-authors rule:** persons named as people in prose are not reproduced (refer to them by role); eponymous technical terms and bibliographic citations of external works (EXTERNAL-SOURCES.md, EXT-nnn) stay unchanged; paths and filenames are quoted unchanged. Do not reproduce any private person's name.

## Report

- Tables: findings (ID · finding · severity: critical / high / normal · evidence · correction · R3 lines · resolved when); a verdict per part (§9.19.2, §9.19.3, §9.19.4, §9.19.5, each of T1–T8, §9.19.8, §9.19.9, the model, the outline/TODO/index changes), using REVIEWER-INSTRUCTIONS' three verdicts.
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened and every command and search you ran.
- Deliver it once, beginning "REQ-A REVIEW REPORT <A, B or C>".
