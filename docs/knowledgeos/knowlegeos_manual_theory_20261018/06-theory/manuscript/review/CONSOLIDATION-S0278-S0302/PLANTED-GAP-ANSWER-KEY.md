# Sealed answer key: planted-gap test, consolidation S0278–S0302 (do not show to the reviewers or the Professor)

Prepared by the Governance Engineer (session `57ddce41…`), 2026-10-31, under Rulebook v1.4 §12 and mandate §8.3; the Principal's instruction item 5 (research record, addendum 2026-10-31T15:53:39Z). Base: commit `94183183a` (the briefs and freeze). Book under review: `06-theory/manuscript/review/CONSOLIDATION-S0278-S0302/BOOK-UNDER-REVIEW/` (188 files: `06-theory/` top files; `manuscript/` outline, plan, TOC, chapters 00–06 and 08–10 without review subfolders; `tmp/tools/book_search.py`, `book_synonyms.tsv`). **Four files differ from the base, one plant each; every plant is a change inside one line, so all line numbers equal the base's** (SCOPE needs no adjustment).

## Plants

| ID | Class (aimed at errors that recurred in windows, not yet planted) | Reviewer's scope | File, line | Base | Planted | Mechanical route |
|---|---|---|---|---|---|---|
| Q1 | a label overstated: a hypothesis presented as a source finding (the "reading stated as certain" family) | A (Ch. 2) | `02.10` L20 | "**Prediction (Hyp, testable).**" | "**Prediction (Src, measured).**" | the label against its own body: the sentence still ends "Behavioural evidence (violations per rule class) would test this."; task 1 checks every claim's label (Src/Int/Ded) |
| Q2 | a concept's status in the text contradicts the register | B (Ch. 3) | `03.01` L17 | "`[CON-0003 · invalid]`" | "`[CON-0003 · validated]`" | **R1 (`register_home_check.py --root <copy>`) prints "CON-0003 \| H3 status disagrees \| chapter_03/03.01 … register INVALID; text ['validated']"**; CON-0003 is in SCOPE §2.2's INVALID list; `03.01` L52 still tags it invalid |
| Q3 | several readings collapsed into one stated as certain (the Professor's most frequent error in C63–C68, per the Professor's report) | C (§8.56) | `08.56` L40 | "*The sources do not say which.*" | "*The sources settle it: reading (b), the parser decides.*" | the title and index row say "three readings"; the same bullet says "fits (a) and (b) alike"; plain-language item 1 reads it as a proposal; no source line is cited for "settle" |
| Q4 | a stale pointer to a claim that does not exist (the **reverse** of S0252's Q2: here the content exists and the pointer is wrong) | B (Ch. 3) | `03.05` L46 | "(compare §3.10 C7, idempotence)" | "(compare §3.10 C11, idempotence)" | §3.10 has C1–C8 only; C7 is "'Projection' needs care", with idempotence (`03.10` L122–123); **R3 on "§3.10 C11"** lists the line; REVIEWER-NOTE item 6 (missing content or stale pointer?) |

## Found when … / correct treatment (stage 1, on the copy and the sources)

| ID | Found when a reviewer reports … | Correct treatment | Wrong treatment |
|---|---|---|---|
| Q1 | the label of §2.10's prediction does not match its body (nothing measured; "would test") | restore "(Hyp, testable)" | keeping "Src" and adding a citation |
| Q2 | CON-0003's tag in §3.1 contradicts the register (or L52) | restore "invalid" | changing the register to VALIDATED |
| Q3 | §8.56 C1 states one reading as settled against its own title, index and bullet | restore "The sources do not say which." | adopting reading (b) |
| Q4 | "§3.10 C11" points at a claim §3.10 lacks, **diagnosed as a stale pointer** | restore "§3.10 C7" | writing a new §3.10 C11 ("missing content") |

## Scoring

Counts, not rates; four items from four plants; per reviewer and combined; treatment checked in stage 1 (remedies chosen from the copy and the sources only). Blindness from the file lists and the Professor's transcript audit. Questions answered: is an overstated label caught (Q1)? does R1's status line lead to a finding (Q2)? is a collapse of readings caught in a window section (Q3)? is a reference to a missing claim diagnosed correctly when the pointer, not the content, is wrong (Q4)?

## Stage 2 (the working book)

None of Q1–Q4 exists in the working book: no stage-1 remedy for them is applied there.
