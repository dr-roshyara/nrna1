# C44, the window commission: steps 2–3

*2026-10-29. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the fifth window after the S0182–S0200 consolidation.*

## New
- **§8.37:** `06-theory/manuscript/chapter_08/08.37-status-from-a-warrant-would-be-standing-from-evidence-and-a-verdict-engine-returns-to-the-kernel.md` (C1–C6). No new concept.
- **The step-8 file:** `review/C44-S0221-S0223/STEP8-AND-CHECKS.md`.
- **Corrected before your review:** `8864c11e7` (C1 (i) "(c2) proposed"; C4 content vs authority conflict, from the C42 settlement) and `cf10a2236` (C2: acceptance is an authorised actor's recorded act, from the C43 settlement, R3). Judge the corrected text.

## Changed theory since the last window review (v1.4 §2 step 9)
- **`cf10a2236`, the C43 settlement.** Read `review/C43-S0218-S0220/SETTLEMENT.md` and its report, then `git show cf10a2236 -- 06-theory/manuscript/chapter_08/ 06-theory/manuscript/chapter_10/`. Does each row match the text it claims to change (§8.36; §8.35 C4 (i-a); §8.37 C2; §8.38; §10.2 G7)?
- **`ed29e5957`, the Principal's decision T9-16 (b)** (§9.18.10, ce_model v23, check 37). Read only as it bears on §8.37.

## Questions on §8.37
- The checks of REVIEWER-INSTRUCTIONS step 3; coverage against your inventory; whether the recorded challenges are adequate.
- **C1:** is the split of H-KOS-Vyapti-001 into "record the relation" and "status from the relation" right, and is reading (ii) (display, "not attested") consistent with promise (b)? Your S0223 item 2 calls it the source's most precise status rule.
- **C2:** is "a verdict engine returns to the kernel" fair to S0223 (your items 4, 12) against S0218–S0220? Your five-valued reading (item 4): does C2 need it?
- **C3:** relations and delimiters "already in the record": fair against S0221's Relation-001 and Context-002 (your S0221 items 2–4, 9, 11)? Note C43's fork: links between items are not admitted (CON-0188).
- **C4:** challenge without deletion; the two glosses of satpratipakṣa (your S0221 item 8, S0222 item 9).
- **C5:** pseudo-evidence inside and outside the record; your S0223 item 8 (two meanings merged in "authenticity").
- **C6:** relays, the reversed correction, the technology choice. Anything more?
- **Your items** the draft does not carry (for example S0221's order anomaly, item 15, and the lineage note; Relations as primitive / invariant / means, S0221 item 11 and S0222 item 11; the H-KOS / INV-KOS prefixes, S0222 item 14; S0223's three-conditions defect, item 9; S0223's "preservation is sufficient for truth", item 13; S0222's Pramāṇa tension, item 5).

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history; the review folders C43 and C44; the three sources; the files the questions above name.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open.
- **Never:** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them; `docs/knowledge_tranfer/*`; the Professor's Ch. 0–2 basis files (the chapters themselves are allowed); `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `review-architecture/`, `review-implementation/`, `00-access/`, and `03-methodology/` beyond REVIEWER-INSTRUCTIONS.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Report
- About two pages, in tables: findings (W-nn · finding · severity · evidence · correction · resolved when); claim verdicts; coverage; a summary of at most 10 lines; one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C44 STEP 2-3: REVIEW REPORT".
