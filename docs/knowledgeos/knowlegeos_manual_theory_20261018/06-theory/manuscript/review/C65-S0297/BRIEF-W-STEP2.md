# C65, the window commission: steps 2–3

*2026-10-31. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the seventh window after the consolidation S0253–S0277.*

## New
- **§8.58:** `06-theory/manuscript/chapter_08/08.58-an-assistants-words-returning-as-a-documents-finding.md` (C1–C5). No new concept. Drafted before the C61–C64 settlements; their R3 annotations applied.
- **The step-8 file:** `review/C65-S0297/STEP8-AND-CHECKS.md`.

## Changed theory since the last window review (v1.4 §2 step 9)
- **`209347211`, the C64 settlement** (§8.57 restated; R3 annotations at §8.54 C1, §8.55 C1, C3, C5, §8.56, §8.58 C1, C3, §8.60 C4, §8.61 C4; index row 8.57; the C64 step-8 record). Read `review/C64-S0295-S0296/SETTLEMENT.md` and its report, then `git show 209347211 -- 06-theory/`. Does each row match the text it claims to change? Check plain-language lines by hand. Also the latest commit touching §8.58.

## Questions on §8.58
- The checks of REVIEWER-INSTRUCTIONS step 3; coverage against your inventory; whether the recorded challenges are adequate.
- **C1:** an assistant's words returning as a document's finding; the readings of "[User's analysis]" after the C64 settlement.
- **C2:** Article 4 called "Mechanism".
- **C3:** the semantic layer, confidence and "violates Gödel" against §8.56 C1 and §8.57 C2, C3 as settled.
- **C4:** "semantic correctness ≠ truth".
- **C5:** the agreements and qualifications.
- **Your items** the draft does not carry.
- *Patterns found in earlier windows, to check here too:* "no home" for a required but not admitted kind; a form the register marks INVALID cited as an agreement or a test; a record's own sentence taken for the quoted act; one reading stated as certain; a settled multi-reading result reverting to one reading; entry identity compared with item identity; a register pointer not checked against the row's own list; a source's earlier use in the book not disclosed (and a source's subject that is itself a later corpus source); a mapping across lines that does not match the stage of each check; a word's sense not checked in both texts before an opposition is claimed; a cited home not checked for the very words claimed; a draft not re-read as a whole against a settlement made after it.

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history; the review folders C64 and C65; the source of your inventory; the files the questions above name; and, for the lines §8.58 cites: S0241 (`docs/knowledgeos/architecture/20260822-0951-KOS-EP01-Constitution-v1.0.md`) and S0295 (check each path on the allowed list first). Limit searches to the chapter folders, the register, the map and the C64 and C65 review folders.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open. The file S0276 cites as the raw commission text is not opened unless it is on the list under that exact path.
- **Never:** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them; `docs/knowledge_tranfer/*`; the Professor's Ch. 0–2 basis files (the chapters themselves are allowed); `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `review-architecture/`, `review-implementation/`, `00-access/`, and `03-methodology/` beyond REVIEWER-INSTRUCTIONS.
- Run every tool with `--root` pointing at W from your first call, where a tool takes it.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- If you write a scratch file, name it `c65-w-*.txt`.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name or any outside author's name.

## Report
- About two pages, in tables: findings (W-nn · finding · severity · evidence · correction · resolved when); claim verdicts; coverage; a summary of at most 10 lines; one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C65 STEP 2-3: REVIEW REPORT".
