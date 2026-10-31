# C61, the window commission: steps 2–3

*2026-10-31. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the third window after the consolidation S0253–S0277.*

## New
- **§8.54:** `06-theory/manuscript/chapter_08/08.54-an-answer-with-tool-citations-recorded-as-the-hpas-and-zero-as-the-regress-stopper.md` (C1–C5). No new concept. Drafted before the C59 and C60 settlements; R3 from both applied and the section re-read.
- **The step-8 file:** `review/C61-S0285-S0287/STEP8-AND-CHECKS.md`.

## Changed theory since the last window review (v1.4 §2 step 9)
- **`cf077b06f`, the C60 settlement** (§8.53 restated; R3 to §8.54). Read `review/C60-S0282-S0284/SETTLEMENT.md` and its report, then `git show cf077b06f -- 06-theory/`. Does each row match the text it claims to change, at every site it names? Check plain-language lines by hand: the tools do not see them.

## Questions on §8.54
- The checks of REVIEWER-INSTRUCTIONS step 3; coverage against your inventory; whether the recorded challenges are adequate.
- **C1:** the attribution of S0286 to the HPA; the voice evidence; the admission count.
- **C2:** Zero as the regress stopper: are the readings consistent with the senses as settled at §8.52 C1 and §8.53 C2? The book's two regresses, by axis.
- **C3:** context as part of identity (S0241 Art 1.4) and the record's pointer to Art 2.
- **C4:** local acceptance and global consistency against §9.14, §9.18.6 and §9.13 R3.
- **C5:** S0285's two layers (the article, and S0283 pasted at its end); the relays checked against the article; the benchmark rival for the root (outside literature, benchmark only).
- **Your items** the draft does not carry.
- *Patterns found in earlier windows, to check here too:* "no home" for a required but not admitted kind; a form the register marks INVALID cited as an agreement or a test; a record's own sentence taken for the quoted act; one reading stated as certain; a settled multi-reading result reverting to one reading; entry identity compared with item identity; a register pointer not checked against the row's own list; a source's earlier use in the book not disclosed (and a source's subject that is itself a later corpus source); a mapping across lines that does not match the stage of each check; a word's sense not checked in both texts before an opposition is claimed; a cited home not checked for the very words claimed; a draft not re-read as a whole against a settlement made after it.

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history; the review folders C60 and C61; the three sources of your inventory; the files the questions above name; and, for the lines §8.54 cites: S0241 (`docs/knowledgeos/architecture/20260822-0951-KOS-EP01-Constitution-v1.0.md`), S0254, S0278 and S0283 (check each path on the allowed list first). Limit searches to the chapter folders, the register, the map and the C60 and C61 review folders.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open. The file S0276 cites as the raw commission text is not opened unless it is on the list under that exact path.
- **Never:** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them; `docs/knowledge_tranfer/*`; the Professor's Ch. 0–2 basis files (the chapters themselves are allowed); `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `review-architecture/`, `review-implementation/`, `00-access/`, and `03-methodology/` beyond REVIEWER-INSTRUCTIONS.
- Run every tool with `--root` pointing at W from your first call, where a tool takes it.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- If you write a scratch file, name it `c61-w-*.txt`.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name or any outside author's name.

## Report
- About two pages, in tables: findings (W-nn · finding · severity · evidence · correction · resolved when); claim verdicts; coverage; a summary of at most 10 lines; one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C61 STEP 2-3: REVIEW REPORT".
