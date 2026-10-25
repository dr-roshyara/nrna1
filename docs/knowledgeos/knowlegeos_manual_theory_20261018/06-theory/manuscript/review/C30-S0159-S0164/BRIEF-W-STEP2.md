# C30, the window commission: steps 2–3

*2026-10-25. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). No kernel section changed in this window. The draft is commit `7d5066676`.*

## New
- **§8.23:** `06-theory/manuscript/chapter_08/08.23-authority-is-assigned-not-looked-up-and-storage-is-not-identity.md` (C1–C12). No new concept. The index row, TODO rows and map line are added only at settlement.
- **The step-8 file:** `review/C30-S0159-S0164/STEP8-AND-CHECKS.md`. It holds the measures, the challenge records, the dependencies, and the R1, R2, R3 and quote outputs.

## Changed theory since your last review (v1.4 §2 step 9: in full)
Review both commits in full, with `git show <commit>` for each file in the theory:

1. **`57785a947`, the Principal's turn-8 decision (2026-10-25), applied while the C29 review was running. It has not been reviewed since.** The decision: "a designation becomes a recordable kind in D. Order across designations comes from citation (the switch cites the old store's last entry, CON-0251), not from recording time."
   - It touches §9.13.2 (the kind designate), §9.15 (the K1 cell, the K2 row, the L11 row), §9.17 (R1, R2, R3 (c)), the register rows CON-0261 and CON-0264, THEORY-MAP, T9-08, and `tmp/tools/ce_model.py` v16 with its run `tmp/tools/ce_model-run-20261025-v16.txt`, check 28.
   - **Questions:**
     - Is the decision applied as worded, and does any changed text claim more than it says?
     - Does check 28 test the rule the text states? Check in particular: a designation must close the old carrier's last entry and cite it; only the root designation closes nothing; the designation is the new carrier's first append.
     - Do the earlier checks still give the outputs that `57785a947` says are unchanged?
2. **`70e0c24f5`, the C29 settlement:** §8.22 (settled form), §9.17 W-11/W-12, §9.13, T0-03a, the 8.22 index row, the register follow-up on CON-0257, and the map. Check that each settlement row in `review/C29-S0151-S0158/SETTLEMENT.md` matches the text it claims to change.

## Questions on §8.23
- The checks of REVIEWER-INSTRUCTIONS step 3, coverage against your inventory, and whether the recorded challenges are adequate.
- **C5:** is "not a technical lookup" correctly read as evidence for CON-0261, and is the carrier-only scope right?
- **C4:** is "storage is not identity, in both directions" a valid deduction from §9.17 R1 and S0160?
- **C2:** is "the kernel's record is event-sourced, by construction" a valid deduction from CE* being defined over the record?
- **C6 and C10:** your inventory calls these contradictions, and the draft does not (working model vs final contexts; S0150's text earlier than S0163's by filename time). Which reading holds?
- **C11:** is "already in the book" true for each listed home? Has the in-order read missed a kernel claim that the topic reads did not cover?
- **C12:** is L1636 labelled correctly under the C23 rule? Does C12 stay within n = 1 practice, and is T8-04 rightly a normative question, not one the book can answer by deduction?
- **Your findings without a home in §8.23** (for example S0162 L56 on Delivery and authorization; the scenario-test overclaim in S0159): should any of them be in the section?

## Rules for findings (the Principal, 2026-10-25)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history, this review folder, and the five sources.
- **Further corpus files:** only from `CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open. **Never open a file in `docs/knowledgeos/` that is not on that list.** S0161 is not on it.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- **No Ch. 0–2 basis files.** Never `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, or `docs/knowledgeos/chronological-read/*`.
- No corpus or book text to any outside service. Edit nothing. You may run `tmp/tools/ce_model.py` read-only (it writes nothing unless told to).

## Report
- About two pages, in tables:
  - findings (W-nn · finding · severity · evidence · correction · resolved when);
  - coverage;
  - a summary of at most 10 lines;
  - one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C30 STEP 2-3: REVIEW REPORT".
