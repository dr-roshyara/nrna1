# Window C30: measures, challenge records, step 8, checks

*2026-10-25. Sources: S0159, S0160, S0162, S0163, S0164, read in index order among allowed sources. S0161 is not on the allowed list. The tool skipped it and it was not opened. New: §8.23 (C1–C12). No new concept. No kernel section changed.*

## Measures (per window; v1.4 §10)

| Measure | Value |
|---|---|
| Sources, lines | S0159 887 · S0160 1,165 · S0162 469 (470 by the reviewer's count; the file has no final newline) · S0163 1,241 · S0164 3,328 = 7,090 lines |
| Depth | all five full. The Professor's and W's blind judgements agree. W and the Professor both treat S0164 L917–1155 (an operational listing) as skimmed inside a full read |
| Professor | Window start recorded at 2026-10-25T01:54:25Z; §8.23 drafted by 02:03Z. The start/stop pairs in the read log for S0159–S0163 span seconds each, so they do not measure reading time: those four were read before their start records were taken, in the same session. S0164 was re-read in full, from 01:55:34Z. The Professor's harness time is not available to the Professor |
| Reviewer W, step 1 | 138.6 s (harness), 13 tool uses |
| Reviewer W, step 1, files opened (transcript audit) | 7: the instructions, the brief and the five sources. No search, glob or listing |
| Findings by severity | after step 2 (see `SETTLEMENT.md`) |

## Challenge records (v1.4 §2 step 2)

- **C4 (Int, "storage is not identity, in both directions"):**
  - counterexample tried: a store whose content names its own context, such as a header line;
  - boundary: a header is content, so by §9.17 R1 a byte-identical copy carries it too, and it cannot fix designation;
  - rival: identity by location (a path);
  - the rival's answer: a path is a resolution, not a property of the record (§9.17 R1, scope).
- **C5 (Int):**
  - counterexample tried: S0160's "authoritative artifact" read as an evidence item, not a carrier;
  - boundary: the claim was scoped to the carrier (stated in C5);
  - falsifier: a case in which a lookup over entries alone selects the carrier correctly whatever copies exist. R1 rules this out.
- **C2 (Int, "the kernel's record is event-sourced"):**
  - counterexample tried: a kernel that stores state and keeps the record only for audit;
  - boundary: CE* is defined over the record (§9.13), so stored state in such a kernel is a projection, whatever it is called;
  - rival: none.
- **C12 (Src; Int):**
  - counterexample tried: L1636 read as a recorded act;
  - boundary: the source does not quote an act, so the line is labelled as a reply in the log (C23 rule);
  - the claim is limited to practice, n = 1.
- **C6, "not a contradiction" (the Professor's reading):** this differs from the step-1 inventory, which calls it a contradiction. It is left for step 2.
- **C10, "change over time, not a contradiction":** this rests on filename times only (S0150 22:53, S0163 22:58). This differs from the step-1 inventory.

## Step 8

- **Dependencies:**
  - C4, C5 and C8 rest on §9.17 R1 and CON-0261 (PROVISIONAL: unverified) and on the Principal's decision of 2026-10-25;
  - C1 and C7 rest on §1.16.1;
  - C2 rests on §9.13 and §8.17; C3 on §8.22 C11;
  - C6 rests on §0.6; C9 on §1.14 and §8.17 C8;
  - C10 rests on §8.22 C1 and §1.9; C11 on its listed homes; C12 on §8.22 C7.
- **R1:** no new register rows. The run gives 241 rows with a section home and 1 flag, CON-0133 H4 (information). That flag predates this window.
- **R2 (tier D in §8.23):** all ordinary words: role-and-cost, sooner, idempotent, parsing, storage, consumer, refined, topic, identical, approve, reconstruction, adviser, problem, qualified, contradiction, admitted, truth, cost.
- **Quote check:** §8.23: 14 found, 0 not found.
- **R3:** no existing claim changed. Inbound lines for "§8.22 C7" (5 claim, 10 concept, 12 section-only) and "§8.21 C13" (8 claim): the targets are unchanged, so every line keeps its meaning and needs no update. The new lines in §8.23 cite the targets as they stand. *Disposition: all lines, no change.*

## Instructions received during this window (the Principal, 2026-10-25)

1. If the tool refuses a source, do not bypass the refusal or inspect the file another way; record it and continue.
2. A finding is not resolved because code or tests pass; state what the evidence establishes and what remains unproven.
3. In every report, keep verified facts, reported results, interpretations and recommendations apart, and claim no commit, test or record update without evidence.
