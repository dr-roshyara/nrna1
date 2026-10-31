# C55, the window commission: steps 2–3

*2026-10-31. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the third window after the S0226–S0252 consolidation.*

## New
- **§8.48:** `06-theory/manuscript/chapter_08/08.48-a-truth-layer-proposed-and-withdrawn-and-a-bounded-context-that-is-a-subject.md` (C1–C5). No new concept.
- **The step-8 file:** `review/C55-S0262-S0266/STEP8-AND-CHECKS.md`.

## Changed theory since the last window review (v1.4 §2 step 9)
- **`244a2af9a`, the C54 settlement** (§8.47 restated; leftovers of T9-19 and T10-21 fixed in the map, 09.00, §9.18, §10.2, §10.3.6, §10.5, 10.00 and §8.35). Read `review/C54-S0257-S0261/SETTLEMENT.md` and its report, then `git show 244a2af9a -- 06-theory/`. Does each row match the text it claims to change, at every site it names?
- **`8a9134da1`, the Principal's decision T9-20** (no guard on adoption; clause c2 kept): applied at §9.18 (c2), §8.37 C1, §8.42 C2, the map fork and the Ch. 9 TODO. Is it applied faithfully and only where it bears?

## Questions on §8.48
- The checks of REVIEWER-INSTRUCTIONS step 3; coverage against your inventory; whether the recorded challenges are adequate.
- **C1:** the "truth layer" and universal ontology against S0241 IV.3, S0254 L125, promise (b) and Q1; the user's correction (S0265 L1–7); the multiplication of meta-principles. Is "completeness is not promise (b)" right?
- **C2:** "bounded context" as a subject (S0265) against the book's sense (rule (B), §10.3.6); "Component exists" against the record holding entries.
- **C3:** Zero as "the state where distortion is minimized" against S0254 L11 and L109: a seventh sense, or a reading of the frame sense? Your S0262 item 3 lists the senses.
- **C4:** S0266 as an analogy across lines; the contrast with the research records' handling of the closure (§8.45 C2; §8.47 C5 as restated: three ways).
- **C5:** the agreements and qualifications; per axis where it bears.
- **Your items** the draft does not carry (e.g. your S0262 items 12, 14; S0263 items 2, 4, 6; S0264 items 3, 4, 6; S0265 items 2–4, 8; S0266 items 4, 11, 13, 14).
- *Patterns found in earlier windows, to check here too:* "no home" for a required but not admitted kind; a form the register marks INVALID cited as an agreement or a test; a record's own sentence taken for the quoted act; one reading stated as certain; a settled multi-reading result reverting to one reading; entry identity compared with item identity; a register pointer not checked against the row's own list; a source's earlier use in the book not disclosed; a mapping across lines that does not match the stage of each check; a word's sense not checked in both texts before an opposition is claimed.

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history; the review folders C54 and C55; the five sources; the files the questions above name; S0241 (`docs/knowledgeos/architecture/20260822-0951-KOS-EP01-Constitution-v1.0.md`), S0250 (`docs/knowledgeos/reviews/20260822-1028-KOS-EP01-Research-Phase-Closure-Ratification.md`) and S0254 (`docs/knowledgeos/reviews/20260822-1115-KOS-EP01-Zero-Z-KOS-001-Ratification.md`) for the lines §8.48 cites. Limit searches to the chapter folders, the register, the map and the C54 and C55 review folders.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open.
- **Never:** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them; `docs/knowledge_tranfer/*`; the Professor's Ch. 0–2 basis files (the chapters themselves are allowed); `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `review-architecture/`, `review-implementation/`, `00-access/`, and `03-methodology/` beyond REVIEWER-INSTRUCTIONS.
- Run every tool with `--root` pointing at W from your first call, where a tool takes it.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Report
- About two pages, in tables: findings (W-nn · finding · severity · evidence · correction · resolved when); claim verdicts; coverage; a summary of at most 10 lines; one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C55 STEP 2-3: REVIEW REPORT".
