# C43, the window commission: steps 2–3

*2026-10-29. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the fourth window after the S0182–S0200 consolidation.*

## New
- **§8.36:** `06-theory/manuscript/chapter_08/08.36-a-validation-layer-is-not-acceptance-and-a-strong-candidate-dropped-without-reason.md` (C1–C6). No new concept.
- **The step-8 file:** `review/C43-S0218-S0220/STEP8-AND-CHECKS.md`.
- **Corrected before your review** (`8864c11e7`): C2's "(c1)–(c2)" qualified ((c2) is proposed) and C4 (ii) split into content and authority conflict, both carried from the C42 settlement (W-04, W-02). Judge the corrected text.

## Changed theory since the last window review (v1.4 §2 step 9)
- **`fcc602a18`, the C42 settlement.** Read `review/C42-S0215-S0217/SETTLEMENT.md` and its report, then `git show fcc602a18 -- 06-theory/manuscript/chapter_08/`. Does each row match the text it claims to change (§8.35)?

## Questions on §8.36
- The checks of REVIEWER-INSTRUCTIONS step 3; coverage against your inventory; whether the recorded challenges are adequate.
- **C1:** is "a validation layer is not acceptance" right against §10.4 R1 and §8.34 C3? Is the self-inconsistency in S0218 (C) (your S0218 item 2) fairly stated?
- **C2:** is TARKA-005's grading a non sequitur, as claimed (your S0219 item 6)? Is "a refutation is recordable and changes no standing" the book's (§9.18)?
- **C3:** "prevented, not detected" for citation cycles (§9.13.2); "every chain ends at a root"; content circularity outside the kernel. Right? Note C42 W-03/C4 (ii): the content justification graph is not in the record.
- **C4:** (i)–(iii) against §9.13.3, §8.33 C1 and the corrected (ii). Your S0218 item 7 reads CONFLICTED/Unknown as a four-valued status: does C4 need it?
- **C5:** agency and K3; "weight by role" against standing; G7.
- **C6:** the dropped TARKA-005; relayed wording; the CI on ten examples (your S0219 item 10). Anything more?
- **Your items** the draft does not carry (for example S0219's internal three/four pramāṇa tension, item 3; the fallacy-list defect, item 8; S0220's "preserves the validity of reasoning", item 12; vyāpti weakened from establish to record, item 7; the four lifecycle vocabularies; "validation" as a homonym, S0220 item 10).

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history; the review folders C42 and C43; the three sources; the files the questions above name.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open.
- **Never:** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them; `docs/knowledge_tranfer/*`; the Professor's Ch. 0–2 basis files (the chapters themselves are allowed); `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `review-architecture/`, `review-implementation/`, `00-access/`, and `03-methodology/` beyond REVIEWER-INSTRUCTIONS.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Report
- About two pages, in tables: findings (W-nn · finding · severity · evidence · correction · resolved when); claim verdicts; coverage; a summary of at most 10 lines; one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C43 STEP 2-3: REVIEW REPORT".
