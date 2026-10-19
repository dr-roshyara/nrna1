# Rule ML-1: manuscript layout — chapter folders, section files, references at end of file

| Field | Value |
|---|---|
| Status | **APPROVED** by the Principal, 2026-10-18 (in-session, ≈22:03Z) |
| Decision (verbatim) | "then add rule each chapter has its own folder . each section is in seperate file in future. references should be written at end of files. what already done is exception." |
| Register | `METHOD-VERSIONS.md`, entry 13 |
| Relation to M-04 v1.0 | **adds** a layout rule for the book. It removes no obligation of M-04 v1.0 and changes no access, freeze or review rule |

## The rule

1. **One folder per chapter.** Every chapter lives in its own folder under `06-theory/manuscript/` (e.g. `chapter_03/`).
2. **One file per section, from now on.** Every new section of a chapter is written in its own file inside that chapter's folder.
3. **References at the end of each file.** Every section file ends with a **References** list naming the source files the section draws on.
   - **Required:** the source file path, and its S-ID where it has one.
   - **Recommended:** the line range and the extraction record ID (e.g. `E-S0011`), so each reference can be traced back to the record.

## Exception: work already done

- The existing chapter files `chapter_00/CH-00-foundations.md`, `chapter_01/CH-01-knowledge-authority-execution.md` and `chapter_02/CH-02-rules-and-conflict.md` stay as they are. They are **not** split into section files, and no end-of-file reference list is added to them retroactively.
- Their existing inline references remain valid.
- Chapters 0–2 already have their own folders, so rule 1 is already met for them.

## What this rule does not change

- **The chapter freeze.** L-6 and L-9 still apply, and D-R5 (writing into Chapters 0–2) is still open. This rule says *how* new text is laid out, not *whether* it may be written into a frozen chapter.
- **Baselines.** Reviewed chapters keep their date-and-hash baselines (L-4). A chapter made of section files is baselined as its folder's set of files with their hashes.
- **Source traceability inside the text.** The end-of-file list does not replace the source labels of rule 1 ("the document says", "I verified", "I deduced", "hypothesis").

## Open points (not decided by this rule)

- **Naming of section files.** Not fixed. Suggested: `NN.MM-<short-name>.md` (e.g. `chapter_03/03.01-scope.md`), so files sort in reading order.
- **New sections for Chapters 0–2.** Under rule 2 a new section of an existing chapter would be a new file in that chapter's folder. Whether writing it is allowed while the freeze holds depends on D-R5.
- **Chapter index.** Whether each chapter folder keeps an index file listing its sections in order is not decided.

---

# Rule ML-2: each chapter folder has a review folder, and reviews are written there

| Field | Value |
|---|---|
| Status | **APPROVED** by the Principal, 2026-10-18 (in-session, ≈22:09Z) |
| Decision (verbatim) | "professor is writing review in tmp file like this : docs/knowledgeos/knowlegeos_manual_theory_20261018/tmp/review/C1-S0006-S0010/PHASE2-REVIEW-REPORT.md. make rule that each chapter folder has a review folder and the review should be written there." |
| Register | `METHOD-VERSIONS.md`, entry 16 |
| Relation to ML-1 | adds to ML-1; ML-1's text above is unchanged |

## The rule

1. **Every chapter folder has a `review/` subfolder** (e.g. `06-theory/manuscript/chapter_03/review/`). A new chapter folder is created with it.
2. **Reviews of a chapter are written in that chapter's `review/` folder, not in `tmp/`.** This covers every review artifact about the chapter: the reviewer's blind extraction, the comparison or review report, and the Professor's disposition of the findings.

## Already in place

- `chapter_00/review/`, `chapter_01/review/` and `chapter_02/review/` exist (empty when this rule was recorded).

## What this rule does not change

- **Who may review and what they may see.** Reviewer access stays governed by AR-11 and its standing approval in `00-access/ACCESS-RECORD.md`. The review folder is where results are written, not an access grant.
- **Blind-first order.** The reviewer's blind extraction is still written and frozen before the reviewer sees the Professor's records.

## Open points (not decided by this rule)

- **Reviews covering several chapters** (e.g. review C1, S0006–S0010, touched Chapters 0–2). Not decided. *AI recommendation:* write the review once, in the `review/` folder of the chapter most affected, and put a one-line pointer file in the `review/` folder of each other affected chapter, so nothing is duplicated.
- **Existing review files.** `tmp/review/C1-S0006-S0010/` (Phase 1 and the Phase 2 report, which may still be in progress) and the review files at the root of `06-theory/manuscript/` (`REVIEW-*.md`, `AUDIT-*`, checkpoints) were written before this rule. Whether they are moved, copied, or left in place as an exception is the Principal's decision. *Note:* the Phase 1 file's path and hash are cited in `00-access/ACCESS-RECORD.md`, so a move must keep the hash and leave a pointer.

---

# Rule ML-2a: reviews of all chapters may be written outside the chapter folders

| Field | Value |
|---|---|
| Status | **APPROVED** by the Principal, 2026-10-18 (in-session, ≈22:10Z) |
| Decision (verbatim) | "if the review report is for all chapter then it can write outside the chapter folders" |
| Register | `METHOD-VERSIONS.md`, entry 17 |
| Relation to ML-2 | answers ML-2's first open point, for reviews of **all** chapters; ML-2's text above is unchanged |

## The rule

- A review report that covers **all** chapters (the whole book) may be written outside the chapter folders.
- A review of **one** chapter stays in that chapter's `review/` folder (ML-2).

## Open points (not decided by this rule)

- **Where outside.** Not fixed. *AI recommendation:* one folder, `06-theory/manuscript/review/`, so whole-book reviews are not scattered across the manuscript root or `tmp/`.
- **Reviews of several, but not all, chapters.** Not covered by this rule. ML-2's recommendation still stands as a suggestion: write it once in the folder of the chapter most affected, with pointer files in the others.
- **Review C1 (S0006–S0010).** It touched Chapters 0–2, which were all the chapters existing at the time, so it reads as a review of all chapters. Whether it is moved out of `tmp/` stays with ML-2's open point on existing review files.

---

# Rule ML-2b: location of all-chapter reviews

| Field | Value |
|---|---|
| Status | **APPROVED** by the Principal, 2026-10-18 (in-session) |
| Decision (verbatim) | "use 06-theory/manuscript/review/ for all-chapter reviews" |
| Register | `METHOD-VERSIONS.md`, entry 18 |
| Relation to ML-2a | answers ML-2a's open point "where outside"; ML-2a's text above is unchanged |

## The rule

- Review reports that cover **all** chapters are written in **`06-theory/manuscript/review/`**.

## Still open (not decided by this rule)

- Reviews of several, but not all, chapters.
- Whether existing review files (`tmp/review/`, the `REVIEW-*`, `AUDIT-*` and checkpoint files at the manuscript root) are moved here or stay where they are as an exception.
