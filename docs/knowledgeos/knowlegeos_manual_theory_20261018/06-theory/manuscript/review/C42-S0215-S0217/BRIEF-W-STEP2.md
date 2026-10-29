# C42, the window commission: steps 2–3

*2026-10-29. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). This is the third window after the S0182–S0200 consolidation.*

## New
- **§8.35:** `06-theory/manuscript/chapter_08/08.35-revision-keeps-history-contradiction-stays-visible-and-a-position-moved-by-assertion.md` (C1–C6). No new concept.
- **The step-8 file:** `review/C42-S0215-S0217/STEP8-AND-CHECKS.md`.

## Changed theory since the last window review (v1.4 §2 step 9)
- **`52084fb98`, the C41 settlement.** Read `review/C41-S0212-S0214/SETTLEMENT.md` and its report, then `git show 52084fb98 -- 06-theory/manuscript/chapter_08/`. Does each row match the text it claims to change (§8.34)?

## Questions on §8.35
- The checks of REVIEWER-INSTRUCTIONS step 3; coverage against your inventory; whether the recorded challenges are adequate.
- **C1:** is "a root act cannot be annulled" (§9.13 Θ_f; check 16) read correctly, and does it make S0217 (B)'s absolute false in the book while (C)'s weakened form agrees? Is "I was believed at time T" fairly mapped to Q1 at an earlier cut?
- **C2:** is the book's coupling of authority to cited grants (§9.13 Θ_f P) stated correctly, and is the refusal of coupling to evidence (§9.18 (c1)–(c2)) the book's? Is reading the sources' "supporting knowledge" as mixing grants and evidence fair?
- **C3:** agreement with CON-0141; is the stated gap (reasons published for U only; §9.13 L166) right?
- **C4:** (i) TMS consistency against U-with-reason; (ii) "earlier entries only" implies acyclicity and is stricter than D_acyclic; (iii) cognitive worlds as queried scope. Fair to S0215/S0216, and to the book?
- **C5:** is "a position moved by assertion" fair (your S0216 item 15 calls it a refinement the source states)? Your reading that "KnowledgeOS" is wider than "kernel" there: should the claim carry it?
- **C6:** "Established" without measurement; the duplicate. Anything more?
- **Your items** the draft does not carry (for example S0215's "research consensus" from one preprint, item 6, and its AGM/modal glosses, items 4–5; S0216 Belief/Core Separation moved onto the authority axis, item 8; confidence kept out yet changing, items 6 and 9; S0217's "impossible-only" kernel trivially satisfied by refusal, item 15; the three different "six" lists; decision coupling graded three ways).

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history; the review folders C41 and C42; the three sources; the files the questions above name.
- **Further corpus files:** only from `docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open.
- **Never:** S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them; `docs/knowledge_tranfer/*`; the Professor's Ch. 0–2 basis files (the chapters themselves are allowed); `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`; `review-architecture/`, `review-implementation/`, `00-access/`, and `03-methodology/` beyond REVIEWER-INSTRUCTIONS.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Report
- About two pages, in tables: findings (W-nn · finding · severity · evidence · correction · resolved when); claim verdicts; coverage; a summary of at most 10 lines; one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C42 STEP 2-3: REVIEW REPORT".
