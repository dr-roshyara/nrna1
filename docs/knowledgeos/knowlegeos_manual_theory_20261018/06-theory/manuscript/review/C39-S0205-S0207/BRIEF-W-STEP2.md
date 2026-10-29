# C39, the window commission: steps 2–3

*2026-10-29. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the second window after the S0182–S0200 consolidation.*

## New
- **§8.32:** `06-theory/manuscript/chapter_08/08.32-what-the-estate-cannot-evaluate-the-book-takes-as-input.md` (C1–C6). No new concept.
- **The step-8 file:** `review/C39-S0205-S0207/STEP8-AND-CHECKS.md`.

## Changed theory since the last window review (v1.4 §2 step 9)
- **`b626c1fba`, the C38 settlement.** Read `review/C38-S0201-S0204/SETTLEMENT.md` and its report, then `git show b626c1fba -- 06-theory/manuscript/chapter_08/` for §8.31. Does each settlement row match the text it claims to change? §8.32 C4 corrects §8.31 C3: is that correction right?

## Questions on §8.32
- The checks of REVIEWER-INSTRUCTIONS step 3; coverage against your inventory; whether the recorded challenges are adequate.
- **C1:** is the correspondence (the two permanently UNKNOWN facts = CE*'s two inputs) exact against §9.13.2? Is "Q1 refuses, since EKS grants have no holder field" right under D's field rule?
- **C2:** is the homonym real (S0206 L342, L1150 against §9.18 and §9.10 O2)? Does it narrow §8.31 C1 as stated?
- **C3:** is the parallel with §9.16 R3a (prevent or detect) fair, or does it read more into S0206 L1453 than it says?
- **C4:** is "plausible, unattestable" the right strength for 216 → 218, given S0206 L601–607 against L1409?
- **C5:** are (a) and (b) contradictions against Ch. 9 as stated (§9.10 O2, §9.18; §9.16 R1)? Is the "lattice" contrast with §9.13 R2 right?
- **C6:** is "INV-KOS-002 has no owner in the book's design" right under T10-10 (1) and §9.10 L33?
- **Your items** the draft does not carry (for example S0205's entropy tension, items 7 and 14; S0207's classification tensions, items 8 and 9).

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history; the review folders C37 and C39; the four sources; the files the questions above name.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open.
- **Never:** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them; `docs/knowledge_tranfer/*`; the Professor's Ch. 0–2 basis files (the chapters themselves are allowed); `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `review-architecture/`, `review-implementation/`, `00-access/`, and `03-methodology/` beyond REVIEWER-INSTRUCTIONS.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Report
- About two pages, in tables: findings (W-nn · finding · severity · evidence · correction · resolved when); claim verdicts; coverage; a summary of at most 10 lines; one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C39 STEP 2-3: REVIEW REPORT".
