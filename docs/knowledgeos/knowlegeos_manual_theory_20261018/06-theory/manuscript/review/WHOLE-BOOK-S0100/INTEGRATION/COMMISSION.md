# Commission: cross-chapter integration review (whole-book review S0100, Rulebook v1.3 §12c)

You are the integration reviewer. Ten independent part reviews of the KnowledgeOS theory book are done (five parts, two reviewers each, Opus "A" and Sonnet "B"). Your task is the final cross-chapter check across the parts. You judge; you never edit any file (read-only: no writes anywhere, no moves, no state-changing commands).

Repository root: `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1`. Lane root (L): `docs/knowledgeos/knowlegeos_manual_theory_20261018/`. Review folder (R): `L/06-theory/manuscript/review/WHOLE-BOOK-S0100/`. The book: `R/BOOK-UNDER-REVIEW/` (a frozen copy; read the book only there).

## What you read (only these)
- The ten part reports: `R/P1/REPORT-A.md`, `R/P1/REPORT-B.md`, … `R/P5/REPORT-B.md`. Parts: P1 = Ch. 0–1, P2 = Ch. 2–3, P3 = Ch. 4–5, P4 = Ch. 6 and Ch. 9, P5 = Ch. 8.
- The copy's outline `R/BOOK-UNDER-REVIEW/06-theory/manuscript/BOOK-OUTLINE.md` and chapter plan `R/BOOK-UNDER-REVIEW/06-theory/manuscript/CHAPTER-PLAN.md`.
- Any file of the copy `R/BOOK-UNDER-REVIEW/` you need to verify a point (sections, `06-theory/THEORY-MAP.md`, `06-theory/CONCEPT-REGISTER.tsv`, `06-theory/RESEARCH-PURPOSE.md`). Book search only with `python3 -I R/BOOK-UNDER-REVIEW/tmp/tools/book_search.py …`.
- Corpus sources only from the allowed list `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`, and only where a cross-chapter point needs a source line.

## Never open
Anything else under `R/` (the reviewers' inventories and commissions, the organisers' files, folders other than those listed); the working book (`L/06-theory/manuscript/chapter_*`, `L/06-theory/THEORY-MAP.md`, `L/06-theory/CONCEPT-REGISTER.tsv`, the working `BOOK-OUTLINE.md`), `BASELINE-REVISIONS.md`, `*-OLD-*` and `*-HEADS-OLD-NEW*` files, the chapter `review/` folders inside the copy, git history of the manuscript; `L/00-access/`, `L/03-methodology/`, `L/archive/`; any `.env*` file; `docs/knowledgeos/chronological-read/_batch_input*`; anything sealed, quarantined, hold-out or `P3B-HOLDOUT`; files not on the allowed list. Always `cd` into, or name, a path inside `R/BOOK-UNDER-REVIEW/` when you read the book: never a path under `L/06-theory/manuscript/chapter_*`. Searches scoped to the copy or to named allowed files; no repository-wide searches; no `git` except `git log --format=… -- <allowed source file>`. Nothing to outside services.

## The checks (Rulebook §12c, across the parts)
1. **The same term with different meanings in different chapters.** Collect the homonyms the ten reports name (for example: authority, valid, standing, independent, gap, Zero, decision, enforced, knowledge space, kernel, I-n labels) and check across chapters, in the copy, which senses are in use where, and where an inference slides between senses across a chapter boundary.
2. **A law in one chapter that rests on a concept another chapter rejects** (INVALID, or refuted in a "what does not hold" section, or contradicted by a later source the book reads). Name the law, its home, the rejected concept and its home.
3. **The derivation chain from the primitives to the laws, across all chapters.** Is there a stated primitive set? Which chapter states it? Do the laws of Ch. 1, 2, 5, 8 and 9 derive from it, or are they added as cases? Where is the chain cut (undefined Req, CE not a function, kernel type, conflict definition)? Is the primitive set complete and minimal as far as the reports and the book show?
4. **Duplicates: one law (or concept) with two homes**, including a restated concept that lives in two sections with different wording.
5. **Dangling references to Chapter 7.** CHAPTER-PLAN lists Ch. 7 ("The KnowledgeOS loop and its evidence") as planned; the copy has no `chapter_07/`. List every reference in the copy to Ch. 7 or to sections of it, and whether each is a forward pointer, a deferral that leaves a claim unsupported, or a broken citation.
6. **Agreement and conflict between the part reports:** where two parts' reviewers judge the same cross-chapter item differently, say which judgement the copy supports.

For each finding give severity (critical: could invalidate a foundational concept, derivation or chapter conclusion / high: a material defect / normal: local), with a one-line reason; the chapters and locations; the evidence (copy lines or source lines); the consequence; the correction or investigation; and whether you verified it in the copy or source, or rely on a report only. **Refutation adequacy** (entry 55): where you assess a falsification attempt, judge it adequate or inadequate with the reason, and give the strongest counterexample or rival you find. Keep facts, deductions, hypotheses and open questions apart; never let a reviewer's claim pass as yours without checking it.

## Report (tables; up to about 2,000 words before an appendix)
1. Assessment in 3 lines. 2. Cross-chapter findings, ranked. 3. Homonym table (term · senses · chapters · where an inference slides). 4. Rejected-concept dependencies. 5. The derivation chain across chapters (primitives → laws → results), with its cuts. 6. Duplicates. 7. Chapter 7 references. 8. Conflicts between part reports. 9. Next steps in dependency order with completion conditions; a question for the Principal only where the theory cannot proceed without one. 10. Limits. 11. **The list of every file you opened and every search you ran**, and whether you reached any forbidden file.

You are an AI session independent of the author as a process, not as a person; say so in one line. Deliver the report once, through the hand-back.
