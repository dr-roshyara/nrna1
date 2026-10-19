# Trial T1 (topic first) compared with C6 (chronological)

*2026-10-19. T1 covers fork F11 from S0043, S0166, S0889, S1052 and S1524. C6 covers S0031–S0035 in index order. Counts come from the saved reports (`REVIEW-REPORT.md` and `REVIEW-REPORT-2.md` for T1; `C6-S0031-S0035/REVIEW-REPORT.md` and `SETTLEMENT.md` for C6). Times are session clock times for the whole turn, reasoning included. **The Principal decides.***

| Measure | T1 | C6 |
|---|---|---|
| **Theory gained** | 6 concepts (CON-0090 … 0095). Derived results: running is not sufficient for enforcing; a specification is never evidence of a run; four criteria for evidence of execution, answering three different questions; I-11 as a classification and its testable counterpart. **Forks:** F11 mapped source by source, but **not settled**; F12 opened | 7 concepts (CON-0084 … 0089, CON-0040). Derived results: bounded-context admission is a necessary condition with no sufficiency condition; the two reuse tests are not equivalent; context type vs instance; protocol vs records; core/supporting relative to a product. **Forks:** F1 narrowed (dissolved for the vision only) |
| **New topics / open questions** | 13 in the theory map, plus F12 (one concerns the method: filename dates may be export times) | 8 (T6-04 … T6-11), from 20 coverage items |
| **Review findings, by type** | report 1, 17 findings: 6 overstatement, 4 logic (1 critical), 3 citation, 4 omission. Report 2, 15 findings: 6 overstatement, 3 logic (2 critical), 2 citation, 4 omission. The two overlap heavily | about 7 overstatement, 6 logic, 1 citation; omissions given as 20 coverage items (6 necessary) |
| **Reviewer verdicts** | VALIDATED 2 (0092, 0093) · INVALID 1 (0091), plus the fork resolution as stated · PROVISIONAL 1 (0090) · 0094 and 0095 not yet reviewed | VALIDATE 2 (0087, 0089) · KEEP 5 (0040, 0084, 0085, 0086, 0088), the old vocabulary, close to PROVISIONAL · INVALID 1 claim (the falsifier contradiction) |
| **Time for the research** (topic to finished section) | ≈ 6 min (first search 10:21:53 → section 10:27:55); ≈ 9 min with step 0 | ≈ 18 min (09:41 → S0035 closed 09:59:36), an upper bound; the start of S0031 was not logged |
| **Time for review plus settlement** | ≈ 19 min (10:28 → 10:47), including a reviewer restart and two reports | ≈ 19 min (09:59:55 → 10:19:09) |

## What the comparison shows

- **Development across time is T1's gain.** One concept was followed through four weeks of the corpus, from "knowledge is an executable asset" (a check) to execution as an authorized act and to a machine-produced run. Reading in index order would not have reached these sources for hundreds of files.
- **Validity did not improve.** Both groups had most new concepts downgraded. T1 had more critical logic errors (3 against none flagged critical in C6). Both mostly overstated scope.
- **Selection is T1's new risk.** The sources were picked by the Professor's search and judgment. Both T1 reports found their largest gaps inside the chosen sources. Nothing measured what lay outside them.
- **The effort was about equal per group.** T1's research was faster, but it read four times as many lines and needed a second correction round. Review plus settlement took the same time in both.

## One known limit of T1

T1 used only **unread** sources. §3.9 is therefore not built on all the evidence on F11:
- the earlier sources already in the book (S0008, S0021, S0022, S0026, S0029) enter only as its "before";
- the searches found 62 allowed files for the synonym terms alone (more for the broader terms), and the trial read five.

## Recommendation: run one more topic trial

- **Why:** T1 shows a gain in theory development and no gain in validity. One definitional fork cannot separate the two effects.
- **The trial:** take a **factual** fork, for example F8 (are knowledge spaces nested or adjacent?).
- **Three conditions:**
  1. Choose the sources by a rule fixed before reading, for example the earliest, the latest and evenly spaced hits, **including already-read sources**.
  2. Give the reviewer the chapters the section depends on.
  3. Commission the reviewer once.
- **Then decide** between chronology first and topic first, on two trials' evidence.

## Process events in T1 (merged from the earlier draft note)

- The first reviewer was launched without `REVIEWER-INSTRUCTIONS.md`, which came into force a minute earlier. It was stopped and then resumed, and its blind reading was kept.
- After reading the instructions, and before seeing the section, it re-expressed step 1 as a ranked inventory.
- Two step-2 messages produced two reports. The second reviewed the first version of the section. Both are kept.
- The T1 grant excluded the "before" chapters, and both reports name this as their main limit.
- **Chronology protects precedence; topic first protects development.** C6's five sources came from one day and showed no development. T1 traced development but read late sources without the material between them.
- **For the Principal:** some sources are ordered by filename datestamp, which can be an export time (S1524 L760). Should these be flagged when read?
