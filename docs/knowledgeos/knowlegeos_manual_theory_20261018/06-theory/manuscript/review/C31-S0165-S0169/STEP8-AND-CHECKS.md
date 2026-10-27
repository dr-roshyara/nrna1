# Window C31: measures, challenge records, step 8, checks

*2026-10-27. Sources: S0165, S0166, S0167, S0168, S0169, read in index order; all on the allowed list. S0170 is not on it. New: §8.24 (C1–C12). No new concept. No kernel section changed.*

## Measures (per window; v1.4 §10)

| Measure | Value |
|---|---|
| Sources, lines | S0165 818 · S0166 1,332 · S0167 2,711 · S0168 1,612 · S0169 352 = 6,825 lines (counted with the last line; `wc -l` gives 1,331 and 1,611 for S0166 and S0168) |
| Depth | all five full. The Professor's and W's blind judgements agree |
| Professor, reading (start/stop records) | S0165 16 s · S0166 19 s · S0167 26 s · S0168 18 s · S0169 21 s. Total 17:13:41Z–17:15:21Z, 1 min 40 s. These are the times between the tool's start and close records; they cover the read calls, not the later drafting |
| Professor, drafting §8.24 and checks | about 17:15Z–17:40Z (session clock) |
| Reviewer W, step 1 | 131.3 s (harness), 13 tool uses. Transcript audit: 7 files, the instructions, the brief and the five sources; no search, listing or Bash |
| Findings by severity | after step 2 (see `SETTLEMENT.md`) |

## A reading defect, found by the reviewer's inventory

- **What happened.** S0166 and S0168 have no final newline. Their last lines, S0166 L1332 and S0168 L1612, were not read when the sources were closed. The reads were sized by `wc -l`, which counts newline bytes.
- **Effect.** The S0168 close note "ends mid-sentence at L1610" was wrong: L1612 completes the question. S0166 L1332 holds the verdict's "necessary" overclaim, now in §8.24 C6.
- **Repair.** Both lines were read before §8.24 was written. `tmp/tools/close_source.py` now counts a last line without a newline (L219). Its two test suites pass.
- **Not established.** Whether C30's S0162 L470, the last line of a 470-line file, was read in C30. That read was before a context reset, so it cannot be shown. The line was read on 2026-10-27. It is the open question the C30 reviewer's inventory already lists, and §8.23 does not rely on it.

## Challenge records (v1.4 §2 step 2)

- **C7 (Ded):**
  - counterexample tried: a store rebound while the old store is kept read-only and the new one holds projections only;
  - boundary: then the rebound store is not the carrier, and C7's scope excludes it;
  - rival: the designation could be made by the configuration change itself, if the configuration were a recorded act in D. That is a designation by another name, and C7 then holds trivially;
  - falsifier: a carrier change that leaves K2-w and the configuration agreeing without any designation entry. By R1 nothing in the entries can fix the carrier, so none exists.
- **C1 (Int):**
  - counterexample tried: a terminal bound one-to-one to an assignment by the record itself;
  - boundary: then the role is still read from the record, and the terminal is only the locus.
- **C4 (Int, homonym):**
  - rival: S0165 means that state grants authority;
  - answer: L675 in the same list puts creation of authority with human acts. The rival reading leaves the list self-contradictory.
- **C9 (Int):** the homonym was checked against §8.23 C4 (W-08 at C30): entry identity, not carrier identity.

## Step 8

- **Dependencies:**
  - C1 rests on CON-0239 and §9.17 R1;
  - C2 on §1.14.3-2 and §6.5 C2;
  - C3, C4 and C6 on §1.14.2-1 and §8.06 C6;
  - C7 on §9.17 R1, R3 (c), §9.13.2 and CON-0261 (PROVISIONAL);
  - C8 on T0-03 and §9.17 R3 (c);
  - C9 on CON-0264 (PROVISIONAL), §9.17 R2 and CON-0240;
  - C10 on §8.06 C1, C3 and §8.15 C4;
  - C11 on §1.14.5-1, §8.22 C3 and §9.05;
  - C12 on §8.22 C1.
- **R1:** no new register rows. 241 rows with a section home, 1 flag (CON-0133 H4, information; it predates this window).
- **R2 (tier D in §8.24):** all ordinary words: activate, publication, query, drift, adviser, homonym, refinement, standard, delivery, contradiction, produced, separation.
- **Quote check:** §8.24 8 found, 0 not found.
- **R3:** no existing claim changed. Disposition: none needed.

## Decisions received during this window (not applied in C31)

The Principal's decisions on C30 (T9-09 (a), (b); T8-04), received 2026-10-27 and refined the same day, are recorded in `sessions/2026-10-27.md`. They are to be applied after this window settles, as changed theory for C32, with seven model checks. §8.24 C7 does not use them.
