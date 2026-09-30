# Batch B0029 summary

40 files, all under `docs/knowledgeos/reviews/synthesis/book/` (Parts I, II, III, IV
per-chapter claims/evidence-map/chapter/unresolved artifacts) plus three book-level
artifacts: `provenance-index.md`, `book-review-acceptance-packet.md` (GN-37 producer
self-review), and `book-independent-review.md` (GN-38 independent review).

All content is SECONDARY-SYNTHESIS (a book reconstructing/ratifying KnowledgeOS
architecture from the brainstorming corpus), dated/mtime'd 2026-08-28.

## Key finding worth flagging to governance/next batch

`book-independent-review.md` (S1225, GN-38) is a genuinely adversarial, independent
review that found four real, named defects (D-1..D-4) in the already-produced book,
including one (D-2/B-1) alleging silently altered ratified grade annotations in
`part-3-architecture/03-10-the-invariants/chapter.md` (read in this same batch as
S1226) and one (D-3) quoting a sentence in
`part-2-reconstruction/02-03-falsification-under-authority/chapter.md` (read in this
same batch as S1227) that does **not** match the chapter's current text (grep-verified:
current line 15 reads "caught and corrected the same day", not the review's quoted
"caught within the hour by the human side"). This is recorded as a CONTRADICTION-typed
contribution without resolving it -- it may mean the defect was corrected after GN-38
ran, or that the review's quotation was itself inaccurate.

## On the standing "BOOK PRODUCTION AUTHORIZATION" question (from the batch brief)

This batch did **not** encounter a document titled "BOOK PRODUCTION AUTHORIZATION".
It did encounter strong indirect evidence that book production was governed: GN-34
("BA ratified, 6/6 banners"), GN-35 ("production ruled AND executed"), GN-36 (a
stale-instruction discrepancy discharged by HPA confirmation), GN-37 (this book's own
acceptance-review packet), and GN-38 (the independent review). These are all *cited by
GN-number* in book-review-acceptance-packet.md and book-independent-review.md; the
underlying GN-34/GN-35 ratification records themselves were not opened in this batch
(they live in `../analysis/governance-notes.md` per the provenance-index, S1223) and
so their content is not verified here -- only their existence-by-reference.

## New objects proposed (14, see index-proposals.jsonl)

One book chapter per proposal, since prior batches (B0028) already established this
convention (one label per book chapter/object): archaeology-method-three-eras-five-regimes
(II.1), epistemic-grading-system-final-rule (II.2), falsification-pass-under-authority
(II.3), conformance-pass-3c-boundary-vs-formal (II.4), charioteer-lord-collision-oq7
(I.5), ratified-invariant-catalog-i1-i12 (III.10), engineering-with-knowledgeos-chapter
(IV.1), ai-agents-epistemic-governance-chapter (IV.2), parallel-lanes-future-intake-v11
(IV.3), open-questions-registry-oq1-12 (IV.4), book-evidence-reading-contract (IV.5),
book-provenance-index, book-acceptance-review-packet-gn37, book-independent-review-gn38.

One file (S1189, chapter I.6's evidence-map) reused the existing label
`step-series-formalization-attacks-itself` (B0028) since I.6 is literally titled
"Formalization Attacks Itself" -- an exact match, not a merge guess.

## Self-checks

All four mandatory self-check scripts were run; results below.

```
Check 1 (types closed-list):        TOTAL INVALID ROWS: 0
Check 2 (labels registered):        TOTAL UNREGISTERED LABELS: 0
Check 3 (JSON validity):            valid lines: 115 (of 115)
Check 4 (unknown_candidate/labels): TOTAL INCONSISTENT ROWS: 0
Extra:  40/40 files processed (S1188..S1227); 0 null anchors.
```
