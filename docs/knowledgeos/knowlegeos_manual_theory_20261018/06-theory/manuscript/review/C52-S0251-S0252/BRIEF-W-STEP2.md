# C52, the window commission: steps 2–3

*2026-10-30. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the seventh window after the S0201–S0225 consolidation, and the last before the next.*

## New
- **§8.45:** `06-theory/manuscript/chapter_08/08.45-the-assistants-words-recorded-as-the-hpas-and-a-closure-passed-by-reclassification.md` (C1–C5). No new concept.
- **The step-8 file:** `review/C52-S0251-S0252/STEP8-AND-CHECKS.md`.

## Changed theory since the last window review (v1.4 §2 step 9)
- **`175b2bc36`, the C51 settlement** (§8.44 restated; §8.43 C5, the supersession pointer). Read `review/C51-S0246-S0250/SETTLEMENT.md` and its report, then `git show 175b2bc36 -- 06-theory/manuscript/chapter_08/`. Does each row match the text it claims to change, at every site it names? Is W-10's partial acceptance (outside literature not opened) correctly applied?

## Questions on §8.45
- The checks of REVIEWER-INSTRUCTIONS step 3; coverage against your inventory; whether the recorded challenges are adequate.
- **C1:** is the identity of S0252 L3's quote with S0251 L31 correctly used, and does the claim stay within what it shows (words vs act)?
- **C2:** the closure passed by reclassification; is it fair to S0252 L11–16? Your S0252 item 8 (Zero's two senses) is not carried: should it be?
- **C3:** "not who said it, but what evidence", per axis, against A1 and S0241 Art 3.2, Art 10; the source/actor merger.
- **C4:** Zero as a name for the conjunction of the prohibitions, against §8.42 C1 and the dimension test CON-0162; your S0251 items 6, 12 (Zero as thing vs frame) and S0252 item 2 (a non-reasoning gate can still distort).
- **C5:** the agreements and qualifications; the frame placed in Art 2 (and your S0252 item 9, Art 3 vs Art 6); the truth baseline.
- **Your items** the draft does not carry (e.g. the two diagrams, the dimension count, "Faith" dropped, "substrate" → "system").
- *Patterns found in earlier windows, to check here too:* "no home" for a required but not admitted kind (C46 W-02; C47 W-03); a form the register marks INVALID cited as an agreement or a test (C47 W-02; C48 W-02); a record's own sentence taken for the quoted act (C48 W-04; C49 W-04; C50 W-05; C51 W-05); one reading stated as certain (C50 W-01; C51 W-01); entry identity compared with item identity (C46 W-01; C50 W-07); a register pointer not checked against the row's own list (C51).

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history; the review folders C51 and C52; the two sources; the files the questions above name; S0241 (`docs/knowledgeos/architecture/20260822-0951-KOS-EP01-Constitution-v1.0.md`) and S0250 (`docs/knowledgeos/reviews/20260822-1028-KOS-EP01-Research-Phase-Closure-Ratification.md`) for the lines C2–C5 cite. Limit searches to the chapter folders, the register, the map and these two review folders.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open.
- **Never:** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them; `docs/knowledge_tranfer/*`; the Professor's Ch. 0–2 basis files (the chapters themselves are allowed); `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `review-architecture/`, `review-implementation/`, `00-access/`, and `03-methodology/` beyond REVIEWER-INSTRUCTIONS.
- Run every tool with `--root` pointing at W from your first call, where a tool takes it.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Report
- About two pages, in tables: findings (W-nn · finding · severity · evidence · correction · resolved when); claim verdicts; coverage; a summary of at most 10 lines; one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C52 STEP 2-3: REVIEW REPORT".
