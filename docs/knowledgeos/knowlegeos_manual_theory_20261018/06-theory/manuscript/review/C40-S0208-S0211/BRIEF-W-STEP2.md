# C40, the window commission: steps 2–3

*2026-10-29. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the second window after the S0182–S0200 consolidation.*

## New
- **§8.33:** `06-theory/manuscript/chapter_08/08.33-a-new-item-is-not-unknown-and-lenses-from-one-line-are-one-voice.md` (C1–C6). No new concept.
- **The step-8 file:** `review/C40-S0208-S0211/STEP8-AND-CHECKS.md`.

## Changed theory since the last window review (v1.4 §2 step 9)
- **`257af9be2`, the C39 settlement.** Read `review/C39-S0205-S0207/SETTLEMENT.md` and its report, then `git show 257af9be2 -- 06-theory/manuscript/chapter_08/`. Does each settlement row match the text it claims to change (§8.32 and the pointer at §8.31 C3)?

## Questions on §8.33
- The checks of REVIEWER-INSTRUCTIONS step 3; coverage against your inventory; whether the recorded challenges are adequate.
- **C1:** is "a new item is F (not recognized), not U" right against §9.13 R2/R2a? Is the mapping of the estate's single UNKNOWN onto F, U and "not attested" fair?
- **C2:** is "compute" a real homonym, or does S0208 L598 genuinely reject an evaluator?
- **C3:** is the prompt-led reading fair (S0208 L99–109, L420–427, L437–439 against L506, L742–751, L767)? Your item 15 agrees; does your item 3 (co-change vs causation) bear on C4?
- **C4:** is §9.18's fault containment a fair "checked instance" of dimension independence? Your items S0208 3 and S0210 3 distinguish co-change, causation and "silently": does C4 state which one it checks?
- **C5:** are (i) and (ii) right against §9.15 G1, G4, G6 and §9.16 R3a?
- **C6:** fair to S0211 §20.2?
- **Your items** the draft does not carry (for example S0208's internal contradiction on origin preservation, item 10; S0211's mapping merges, item 5; the H- to Candidate step, item 7; the trivial-solution risk, S0208 item 15).

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history; the review folders C37 and C40; the four sources; the files the questions above name.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open.
- **Never:** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them; `docs/knowledge_tranfer/*`; the Professor's Ch. 0–2 basis files (the chapters themselves are allowed); `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `review-architecture/`, `review-implementation/`, `00-access/`, and `03-methodology/` beyond REVIEWER-INSTRUCTIONS.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Report
- About two pages, in tables: findings (W-nn · finding · severity · evidence · correction · resolved when); claim verdicts; coverage; a summary of at most 10 lines; one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C40 STEP 2-3: REVIEW REPORT".
