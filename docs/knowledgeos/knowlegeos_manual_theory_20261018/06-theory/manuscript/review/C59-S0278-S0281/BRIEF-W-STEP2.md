# C59, the window commission: steps 2–3

*2026-10-31. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the first window after the consolidation S0253–S0277.*

## New
- **§8.52:** `06-theory/manuscript/chapter_08/08.52-zero-as-self-limitation-six-families-from-one-lineage-and-a-gate-passed-by-the-producers-sentence.md` (C1–C5). No new concept. Drafted after the C58 settlement and the consolidation.
- **The step-8 file:** `review/C59-S0278-S0281/STEP8-AND-CHECKS.md`.

## Changed theory since the last window review (v1.4 §2 step 9)
- **`b86b76773`, the C58 settlement** (§8.51 restated; §8.50.3). Read `review/C58-S0274-S0277/SETTLEMENT.md` and its report, then `git show b86b76773 -- 06-theory/`. Does each row match the text it claims to change?
- **The consolidation S0253–S0277** (`3c7a18f31`, `e8e787e00`): read `review/CONSOLIDATION-S0253-S0277/SETTLEMENT.md` only (not the reports). Check only the changes that bear on §8.46–§8.52 and on the homes §8.52 cites (§8.46 C1, C3; §8.47 C2; §8.48 C5; §8.51 C1, C4, C5; §9.18 g1 and (c2); §9.13 R2a); `git show` those two commits limited to those files.

## Questions on §8.52
- The checks of REVIEWER-INSTRUCTIONS step 3; coverage against your inventory; whether the recorded challenges are adequate.
- **C1:** Zero's third reading against Z-KOS-001's statement and §8.51 C1, C4: are the readings kept, and is each word's sense checked in both texts (S0279 L16, L44, L68; S0278 L415–457; S0277 L108–109)?
- **C2:** "six independent families" against §8.31 C4; the EOS reversal against §8.35 C5 and §8.41.
- **C3:** "knows" and "currently justified by evidence", per axis, against A1, (c2) and g1 as adopted (Q-B).
- **C4:** what S0280 and S0281 show about the template gate, and what is only the producer's sentence.
- **C5:** the agreements and qualifications.
- **Your items** the draft does not carry.
- *Patterns found in earlier windows, to check here too:* "no home" for a required but not admitted kind; a form the register marks INVALID cited as an agreement or a test; a record's own sentence taken for the quoted act; one reading stated as certain; a settled multi-reading result reverting to one reading; entry identity compared with item identity; a register pointer not checked against the row's own list; a source's earlier use in the book not disclosed (and a source's subject that is itself a later corpus source); a mapping across lines that does not match the stage of each check; a word's sense not checked in both texts before an opposition is claimed; a cited home not checked for the very words claimed; a draft not re-read as a whole against a settlement made after it.

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history; the review folders C58 and C59, and `review/CONSOLIDATION-S0253-S0277/SETTLEMENT.md` only from that folder (not its frozen copy, reports or commissions); the four sources of your inventory; the files the questions above name; and, for the lines §8.52 cites: S0241 (`docs/knowledgeos/architecture/20260822-0951-KOS-EP01-Constitution-v1.0.md`), S0254, S0262, S0276 and S0277 (check each path on the allowed list first). Limit searches to the chapter folders, the register, the map and the C58 and C59 review folders.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open. The file S0276 cites as the raw commission text is not opened unless it is on the list under that exact path.
- **Never:** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them; `docs/knowledge_tranfer/*`; the Professor's Ch. 0–2 basis files (the chapters themselves are allowed); `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `review-architecture/`, `review-implementation/`, `00-access/`, and `03-methodology/` beyond REVIEWER-INSTRUCTIONS.
- Run every tool with `--root` pointing at W from your first call, where a tool takes it.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- If you write a scratch file, name it `c59-w-*.txt`.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name or any outside author's name.

## Report
- About two pages, in tables: findings (W-nn · finding · severity · evidence · correction · resolved when); claim verdicts; coverage; a summary of at most 10 lines; one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C59 STEP 2-3: REVIEW REPORT".
