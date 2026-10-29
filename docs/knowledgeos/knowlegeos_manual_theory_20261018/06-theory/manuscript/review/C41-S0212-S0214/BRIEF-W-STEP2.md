# C41, the window commission: steps 2–3

*2026-10-29. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the second window after the S0182–S0200 consolidation.*

## New
- **§8.34:** `06-theory/manuscript/chapter_08/08.34-failure-does-not-fall-back-and-a-homonym-grows-by-relay.md` (C1–C6). No new concept.
- **The step-8 file:** `review/C41-S0212-S0214/STEP8-AND-CHECKS.md`.

## Changed theory since the last window review (v1.4 §2 step 9)
- **`3e7e8c598`, the C40 settlement.** Read `review/C40-S0208-S0211/SETTLEMENT.md` and its report, then `git show 3e7e8c598 -- 06-theory/manuscript/chapter_08/`. Does each row match the text it claims to change (§8.33; §8.32 C5's wording)?

## Questions on §8.34
- The checks of REVIEWER-INSTRUCTIONS step 3; coverage against your inventory; whether the recorded challenges are adequate.
- **C1:** is the reading of §9.13.2 ("availability"; refusal per record) and §9.13 v9 (membership on the whole record, also for a cut) right? Does it contradict S0212's INV-KOS-005 only on merged or bypass records, as stated?
- **C2:** is the relay chain fair to S0213/S0214 (your items S0213 4, S0214 3–4)? Are the S0206 facts cited (L404, L405, L718, L733) relied on correctly, as the book records them at §8.32?
- **C3:** is the tension "Assessment → Authority" (allowed) against INV-003 real, and is its resolution (a person's act; §10.4 R1) the book's?
- **C4–C5:** fair against §9.10 Q1, §9.16 R1/R3a and §10.5?
- **C6:** your items S0212 16 and S0214 6 agree; anything more?
- **Your items** the draft does not carry (for example S0212's "adopted" against "nothing adopted", item 11; publication as a kernel concept against "not a dimension", item 8; S0213's three pipeline variants, item 9; the six-dimension list's omissions, item 14; S0214 ending on S0213 Part A's eight-item list, item 8).

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history; the review folders C37 and C41; the four sources; the files the questions above name.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open.
- **Never:** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them; `docs/knowledge_tranfer/*`; the Professor's Ch. 0–2 basis files (the chapters themselves are allowed); `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `review-architecture/`, `review-implementation/`, `00-access/`, and `03-methodology/` beyond REVIEWER-INSTRUCTIONS.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Report
- About two pages, in tables: findings (W-nn · finding · severity · evidence · correction · resolved when); claim verdicts; coverage; a summary of at most 10 lines; one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C41 STEP 2-3: REVIEW REPORT".
