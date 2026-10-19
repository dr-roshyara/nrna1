# Settlement of the window C10 review (S0051–S0055)

*2026-10-19. Report: `REVIEW-REPORT.md`; blind inventory: `STEP1-INVENTORY.md`. One commission, one step-2 message. The S0055 sample count (13 rows, 2 failures) and the §1.17.2 home were re-checked; both hold. All 10 findings are accepted.*

**Reviewer time:**

| Step | Tool clock (reviewer's `date -u`) | Harness |
|---|---|---|
| Step 1 | 29 s (did not bracket the reading) | ≈ 80 s |
| Step 2 | first call before reading: 14:14:38 → 14:19:02, 4 min 24 s | ≈ 5 min 40 s (420 s total minus step 1) |

**Running measure:** 10 findings · 0 critical · 5 high · 5 material omissions · 0 defects found later in reviewed work.
- **Mine:** I relied on a source's own count ("11 of 12") and put a binomial interval on a purposive sample. The meaning search had also ranked §1.17.2 highly, and I did not read it before writing §5.6.

## Verdicts applied

| CON | Verdict | Valid core / scope |
|---|---|---|
| 0121 | VALIDATED | the practice of one line; a second method can catch shared systematic errors; generality Hyp |
| 0122 | INVALID as stated | a wrong high grade weakens reliance on that grade in its document; no rate estimable from a purposive sample |
| 0123 | PROVISIONAL | refines §1.17.2: the object of an act may include its argument |
| 0124 | INVALID as a law of acts | an acceptance of a deliverable covers the delivered, pinned version (one case) |

## Changes made

| Findings | Change |
|---|---|
| 1 | §8.5 C3: interval removed; 13 rows; sample purposive; source slip recorded |
| 2 | §5.6 C3 restricted; counterexamples (grants, Q-C1, conditional approvals); its must-do half; home §1.16.6 |
| 3 | §5.6 C1 qualified ("correct" relative to a chosen premise, F19); C1a the interpretive act; fork F21 |
| 4 | **§5.5 C2** (earlier section): S0055's code-verified engine gaps (`recordedBy` a free string) |
| 5 | entry 45 applied: CON-0122's scope aligned; §8.5 C6, §5.6 C2, C4, C5 marked Hyp or scoped |
| 6 | §8.5 C2: reproduction vs replication; the better example (D-2); method independence not sufficient in one case |
| 7 | **§9.4 C7** (earlier section): the assurance model's trivial case |
| 8 | homes added: §1.17.2, §1.16.8 RC-22 |
| 9 | §8.5 C3: unknowns are calibrated too; two ways a grade can be wrong |
| 10 | §8.5 C6: "unverifiable by third parties after the fact" |
| appendix | §8.5 C5 re-glossed: materiality relative to reliance |

## Source defects
- S0055 "11 of 12" against 13 rows with 2 failures (L7, L57–73); the book relied on it.

## Not done
- Resolver vs fold, name-based boundaries, the evidence hierarchy, and count-with-method are in the map but not yet in the book.
