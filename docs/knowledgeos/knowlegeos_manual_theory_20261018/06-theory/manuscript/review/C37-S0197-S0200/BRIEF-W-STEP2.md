# C37, the window commission: steps 2–3

*2026-10-28. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the last window before the S0200 consolidation.*

## New
- **§8.30:** `06-theory/manuscript/chapter_08/08.30-completeness-is-established-not-asserted-and-a-representation-does-not-make-a-concept.md` (C1–C5). No new concept.
- **The step-8 file:** `review/C37-S0197-S0200/STEP8-AND-CHECKS.md`.

## Changed theory since the last review (v1.4 §2 step 9: in full)
1. **`2a532de22`, the C36 settlement.** Read `review/C36-S0192-S0196/SETTLEMENT.md` and the C36 review report, then `git show 2a532de22` for each theory file.
   - Does each settlement row match the text it claims to change?
   - Is C5's restatement (an analogy to §9.17; homes §1.5 and §1.9) right?
   - Is the single-commit fact (`999ede26b`) stated at the right strength?
   - Is §3.2 applied to M4's axes correctly?

## Questions on §8.30
- The checks of REVIEWER-INSTRUCTIONS step 3, coverage against your inventory, and whether the recorded challenges are adequate.
- **C1:** is reading "not needed" as an absence claim (CON-0120) sound? Does the case support the book's whole-reading rule, or only agree with it?
- **C2:** the draft uses S0199's relay of the migration plan, labelled as a relay. Is the agreement with §8.18 C4 (a) stated at the right strength, given the shared lineage?
- **C4:** is "the review was wrong in both directions" fair to S0198, given S0199's own findings (your inventory, S0199 items 9 and 11)?
- **C5:** is the attack valid against S0200 as written? Your inventory says nothing complex-specific is used: does the attack then hit a form the source does not use? Is reading authority as CE*'s value (T, F or U) a fair test of S0200's "A", which it shows as approved, proposed or rejected?
- **Your items** that the draft does not carry, for example: S0200's own architecture before its ordering rule (items 9–10, 13); its undecidable falsifier (item 12); the narrowed P3-F1 remedy (S0199 item 11); the file-name residue of v1 (S0197 item 13).

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:**
  - the working book (W/06-theory), the register, the map, W's tools and git history;
  - the review folders C36 and C37;
  - the four sources.
- **Further corpus files:** only from `CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open.
- **Never:**
  - S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them;
  - `docs/knowledge_tranfer/*`;
  - Ch. 0–2 basis files; `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Report
- About two pages, in tables:
  - findings (W-nn · finding · severity · evidence · correction · resolved when);
  - claim verdicts;
  - coverage;
  - a summary of at most 10 lines;
  - one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C37 STEP 2-3: REVIEW REPORT".
