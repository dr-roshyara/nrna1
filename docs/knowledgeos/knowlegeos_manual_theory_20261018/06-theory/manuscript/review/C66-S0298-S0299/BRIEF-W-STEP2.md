# C66, the window commission: steps 2–3

*2026-10-31. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the eighth window after the consolidation S0253–S0277.*

## New
- **§8.59:** `06-theory/manuscript/chapter_08/08.59-a-summary-repaired-in-place-and-a-repair-that-re-measures-its-review.md` (C1–C5). No new concept. Drafted before the C62–C65 settlements; re-read against them (two lines corrected).
- **The step-8 file:** `review/C66-S0298-S0299/STEP8-AND-CHECKS.md`.

## Changed theory since the last window review (v1.4 §2 step 9)
- **`2f9c57114`, the C65 settlement** (§8.58 restated; R3 §8.50 C2; the C64 settlement's W-03 row annotated; index row 8.58; the C65 step-8 record). Read `review/C65-S0297/SETTLEMENT.md` and its report, then `git show 2f9c57114 -- 06-theory/`. Does each row match the text it claims to change? Check plain-language lines by hand. Also the latest commit touching §8.59.

## Questions on §8.59
- The checks of REVIEWER-INSTRUCTIONS step 3; coverage against your inventory; whether the recorded challenges are adequate.
- **C1:** the summary repaired in place: two versions under one path; the header's two readings; the book's entry identity.
- **C2:** the repair that re-measures its review; the self-check that passed on the wrong text.
- **C3:** three objects on one carrier; "a row, not a rule"; Premise 2's count.
- **C4:** a closed comparison object belongs to one run; the TODO topic.
- **C5:** the agreements and qualifications; the scope words and the next actor.
- **Your items** the draft does not carry.
- *Patterns found in earlier windows, to check here too:* "no home" for a required but not admitted kind; a form the register marks INVALID cited as an agreement or a test; a record's own sentence taken for the quoted act; one reading stated as certain; a settled multi-reading result reverting to one reading; entry identity compared with item identity; a register pointer not checked against the row's own list; a source's earlier use in the book not disclosed (and a source's subject that is itself a later corpus source); a mapping across lines that does not match the stage of each check; a word's sense not checked in both texts before an opposition is claimed; a cited home not checked for the very words claimed; a draft not re-read as a whole against a settlement made after it.

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history; the review folders C65 and C66; the two sources of your inventory; the files the questions above name; and, for the lines §8.59 cites: S0253, S0296 and S0300 (check each path on the allowed list first). Limit searches to the chapter folders, the register, the map and the C65 and C66 review folders.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open. The file S0276 cites as the raw commission text is not opened unless it is on the list under that exact path.
- **Never:** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them; `docs/knowledge_tranfer/*`; the Professor's Ch. 0–2 basis files (the chapters themselves are allowed); `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `review-architecture/`, `review-implementation/`, `00-access/`, and `03-methodology/` beyond REVIEWER-INSTRUCTIONS.
- Run every tool with `--root` pointing at W from your first call, where a tool takes it.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- If you write a scratch file, name it `c66-w-*.txt`.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name or any outside author's name.

## Report
- About two pages, in tables: findings (W-nn · finding · severity · evidence · correction · resolved when); claim verdicts; coverage; a summary of at most 10 lines; one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C66 STEP 2-3: REVIEW REPORT".
