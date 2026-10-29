# C45, the window commission: steps 2–3

*2026-10-29. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the sixth and last window before the S0201–S0225 consolidation after the S0182–S0200 consolidation.*

## New
- **§8.38:** `06-theory/manuscript/chapter_08/08.38-reliability-is-not-standing-one-chain-carries-four-axes-and-authority-has-three-meanings.md` (C1–C7). No new concept.
- **The step-8 file:** `review/C45-S0224-S0225/STEP8-AND-CHECKS.md`.
- **Corrected before your review:** `cf10a2236` (homes row and C1: acceptance is an authorised actor's recorded act; R3 of the C43 settlement) and `977cf8038` (§8.38.3, the pseudo-evidence row; R3 of the C44 settlement). Judge the corrected text.

## Changed theory since the last window review (v1.4 §2 step 9)
- **`977cf8038`, the C44 settlement.** Read `review/C44-S0221-S0223/SETTLEMENT.md` and its report, then `git show 977cf8038 -- 06-theory/manuscript/chapter_08/`. Does each row match the text it claims to change (§8.37; §8.38.3)?

## Questions on §8.38
- The checks of REVIEWER-INSTRUCTIONS step 3; coverage against your inventory; whether the recorded challenges are adequate.
- **C1:** reliability of the producing process is not standing: right against A1 and §9.18? Is the reading of S0224's "testimony depends_on authority" as authority used for content reliability fair (your S0224 item 14)?
- **C2:** the state chain as four overloaded axes (your S0224 item 9); the verdict field against CON-0141.
- **C3:** default trust against the closed world, per axis (cf. §8.36 C4 (iii) as settled); the tension with C1's always-check (your S0224 item 8).
- **C4:** non-overwrite agreement; item links and supersession have no home (CON-0188; Θ_f σ).
- **C5:** "authority" with three meanings (your S0225 item 13); three kernels; the "truth engine".
- **C6:** meaning bounded by context: agreement, with DDD as a benchmark only.
- **C7:** ADR candidates; the EOS reversal; finality; analogy dropped (your S0224 item 5).
- **Your items** the draft does not carry (for example S0224's "Fake Source" label against the reported point, item 6; S0225's śabda placement conflict, item 12, and its two accounts of error; the two record shapes, KnowledgeEvent and KnowledgeTransmission; the "second book" contradiction).

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history; the review folders C44 and C45; the two sources; the files the questions above name.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open.
- **Never:** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them; `docs/knowledge_tranfer/*`; the Professor's Ch. 0–2 basis files (the chapters themselves are allowed); `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `review-architecture/`, `review-implementation/`, `00-access/`, and `03-methodology/` beyond REVIEWER-INSTRUCTIONS.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Report
- About two pages, in tables: findings (W-nn · finding · severity · evidence · correction · resolved when); claim verdicts; coverage; a summary of at most 10 lines; one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C45 STEP 2-3: REVIEW REPORT".
