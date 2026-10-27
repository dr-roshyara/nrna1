# Window C33: measures, challenge records, step 8, checks

*2026-10-27. Sources: S0177, S0178, S0179, S0180, S0181, read in index order; all on the allowed list. New: §8.26 (C1–C6). No new concept. No kernel section changed. This window completes 25 allowed sources since S0150: the tool now refuses the next source until the consolidation is settled.*

## Measures (per window; v1.4 §10)

| Measure | Value |
|---|---|
| Sources, lines | S0177 174 · S0178 192 · S0179 289 · S0180 126 · S0181 131 = 912 lines; each file ends with a newline (checked by its last byte) |
| Depth | all five full. The Professor's and W's blind judgements agree |
| Professor, reading (start/stop records) | S0177 14 s · S0178 10 s · S0179 13 s · S0180 9 s · S0181 12 s. Total 17:43:11Z–17:44:10Z, 59 s |
| Reviewer W, step 1 | 119.1 s (harness), 9 tool uses. Transcript audit: 7 files (the instructions, the brief, the five sources) and one `wc -l` on the five sources by path |
| Findings by severity | after step 2 (see `SETTLEMENT.md`) |

## Challenge records (v1.4 §2 step 2)

- **C1 (Int):**
  - rival: S0179's "ESTABLISHED (position, corpus-wide)" may mean "established as the corpus's position", not as a current fact;
  - answer: S0179's own criterion admits only "current fact or decision" (L16–20). Under the rival reading, the tag uses a status the criterion does not define.
- **C2 (Int):**
  - counterexample tried: S0154 may also discuss KnowledgeOS components in several languages;
  - boundary: the book's record of S0154 (§8.22 C7) does not show it, and S0154 was not reopened for this window. C2 is therefore labelled the Professor's reading against the book's record, open to correction by the reviewer.
- **C3 (Int):**
  - counterexample tried: an ADR whose status line is updated to DECIDED by the act itself;
  - boundary: then the status line copies the act, and the act still decides. C3 holds.
- **C6 (Int, homonym):** "kernel" as a built component (S0179) versus the theoretical core (the book). Rival: S0179 means the theoretical core and denies it exists. Its list is a list of current-state implementation facts (L91–122), which supports the homonym reading.

## Step 8

- **Dependencies:**
  - C1 rests on §8.25 C7, §8.15 C11 and §8.17 C1;
  - C2 on §8.22 C7;
  - C3 on §8.17 C8, §1.13 and the C23 rule;
  - C4 on §9.17 R4 and §9.15 K1;
  - C5 on §8.23 C1 and C2;
  - C6 on T0-03 and §8.24 C8.
- **R1:** no new register rows. 241 rows with a section home, 1 flag (CON-0133 H4, information; it predates this window).
- **R2 (tier D in §8.26):** all ordinary words: tags, reconcile, plus, repository.
- **Quote check:** §8.26 has no direct quotes (0 found, 0 not found). All citations are "in meaning".
- **R3:** no existing claim changed. Disposition: none needed.
