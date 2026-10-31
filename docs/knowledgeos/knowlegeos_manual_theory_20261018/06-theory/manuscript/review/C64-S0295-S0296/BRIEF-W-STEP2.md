# C64, the window commission: steps 2–3

*2026-10-31. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the sixth window after the consolidation S0253–S0277.*

## New
- **§8.57:** `06-theory/manuscript/chapter_08/08.57-the-chat-behind-two-hpa-extractions-and-a-grant-that-points-at-its-record.md` (C1–C5). No new concept. Drafted before the C61–C63 settlements; R3 annotations from C62 and C63 applied; re-read against them.
- **The step-8 file:** `review/C64-S0295-S0296/STEP8-AND-CHECKS.md`.

## Changed theory since the last window review (v1.4 §2 step 9)
- **`1170579e9`, the C63 settlement** (§8.56 restated; R3 annotations in §8.46 C1 (d), §8.57 C2, C3, §8.58 C3; index row 8.56; the C63 step-8 record). Read `review/C63-S0293-S0294/SETTLEMENT.md` and its report, then `git show 1170579e9 -- 06-theory/`. Does each row match the text it claims to change? Check plain-language lines by hand. Also the latest commit touching §8.57.

## Questions on §8.57
- The checks of REVIEWER-INSTRUCTIONS step 3; coverage against your inventory; whether the recorded challenges are adequate.
- **C1:** the chat behind two "HPA extractions": the three readings and what the window excludes.
- **C2:** confidence as a single number; "would violate Gödel"; "semantic operating system" after the C63 settlement.
- **C3:** the semantic language, against §8.56 C1 as settled.
- **C4:** the grant whose human act is referenced by its record; the R3 note from C62 (W-01).
- **C5:** the agreements and qualifications, including the R3 note from C62 (W-04).
- **Your items** the draft does not carry.
- *Patterns found in earlier windows, to check here too:* "no home" for a required but not admitted kind; a form the register marks INVALID cited as an agreement or a test; a record's own sentence taken for the quoted act; one reading stated as certain; a settled multi-reading result reverting to one reading; entry identity compared with item identity; a register pointer not checked against the row's own list; a source's earlier use in the book not disclosed (and a source's subject that is itself a later corpus source); a mapping across lines that does not match the stage of each check; a word's sense not checked in both texts before an opposition is claimed; a cited home not checked for the very words claimed; a draft not re-read as a whole against a settlement made after it.

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history; the review folders C63 and C64; the two sources of your inventory; the files the questions above name; and, for the lines §8.57 cites: S0241 (`docs/knowledgeos/architecture/20260822-0951-KOS-EP01-Constitution-v1.0.md`), S0288, S0289, S0291, S0292, S0293 and S0232 (check each path on the allowed list first). Limit searches to the chapter folders, the register, the map and the C63 and C64 review folders.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open. The file S0276 cites as the raw commission text is not opened unless it is on the list under that exact path.
- **Never:** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them; `docs/knowledge_tranfer/*`; the Professor's Ch. 0–2 basis files (the chapters themselves are allowed); `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `review-architecture/`, `review-implementation/`, `00-access/`, and `03-methodology/` beyond REVIEWER-INSTRUCTIONS.
- Run every tool with `--root` pointing at W from your first call, where a tool takes it.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- If you write a scratch file, name it `c64-w-*.txt`.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name or any outside author's name.

## Report
- About two pages, in tables: findings (W-nn · finding · severity · evidence · correction · resolved when); claim verdicts; coverage; a summary of at most 10 lines; one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C64 STEP 2-3: REVIEW REPORT".
