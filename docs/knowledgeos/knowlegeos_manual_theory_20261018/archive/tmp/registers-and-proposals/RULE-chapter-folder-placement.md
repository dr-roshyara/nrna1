# Rule: chapter documents live in their chapter's folder (working practice)

**Origin:** the Principal's instruction of 2026-10-18 ("all documents related to a particular chapter should be saved in the same chapter folder, e.g. chapter 1 related documents must be saved in chapter_01").

**Status:**
- **in force as working practice for new documents** from 2026-10-18;
- its text is to be added to `03-methodology/M-04-working-practice-20261018.md` **when the repository freeze is lifted for that file**;
- **not** an amendment of M-04 v1.0.

## Rule

1. **Placement.** Every document that belongs to exactly one chapter is saved in that chapter's folder:
   - `06-theory/manuscript/chapter_00/` for Chapter 0;
   - `…/chapter_01/` for Chapter 1;
   - `…/chapter_02/` for Chapter 2;
   - and so on for new chapters.

   "Belongs to" covers drafts and revised sections, review packets and review records, correction registers, proofs and tests, worked examples, decision briefs and evidence registers **about that chapter**.

2. **One section, one file** *(the Principal's earlier rule, future sections only)*. A new or revised section is its own file inside its chapter folder, named by its stable identifier. Example: `chapter_01/1.14.1-state-not-authority-M2.md`.

3. **Supporting material.** Inside a chapter folder, supporting documents may sit in one sub-folder per topic. Example: `chapter_01/1.14.1-repair/` for the review packets, proofs and tests of §1.14.1.

4. **Cross-chapter documents stay at the manuscript or theory level.** These are documents that genuinely span several chapters:
   - the glossary;
   - `BASELINE-REVISIONS.md` (it records revisions to several chapters);
   - batch checkpoints and S-batch reports;
   - the idea register;
   - the concept-evolution record;
   - the concept validation register.

   **A document is not split just to satisfy the rule.** Where a cross-chapter document has a chapter-specific part, it cites the chapter folder.

5. **Identifiers do not change.** Folder placement never changes a section identifier (§1.14.1 stays §1.14.1), a citation, or a baseline hash.

6. **Existing documents are preserved.** Chapter-specific documents that already sit at the manuscript root are **not moved** without separate authorization. Moving is a structure-only change and follows the preservation procedure: record hashes, move without editing, verify references. They are:
   - `CH-01-final-review.md`;
   - `CH-01-step1-correction-and-review.md`;
   - `REVIEW-ch01-findings-note.md`;
   - the earlier round reviews.

7. **Scratchpad mirror.** While the repository is frozen, chapter-specific drafts in the scratchpad use the same layout, so they can later be copied into place unchanged.

   Example: the §1.14.1 / M2 package belongs to `chapter_01/1.14.1-repair/`.

8. **Relation to the placement script.** `scripts/doc-placement.php` decides the **documentation root**. This rule only orders documents **inside** the already-authorized theory workspace. It adds no new root.

## Effect on current work

- **New chapter-specific documents** from now on follow rules 1–3.
- **Copying the existing scratchpad §1.14.1 package** into a `chapter_01/1.14.1-repair/` layout in the scratchpad is mechanical. I propose doing it once review 2 has reported, so that the review packet and the reviewer's paths stay stable meanwhile.
- **Existing repository files** stay where they are unless their move is authorized (rule 6).
