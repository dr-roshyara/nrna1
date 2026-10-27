# C32, the window commission: steps 2–3

*2026-10-27. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). The draft is commit `019ed5b47`.*

## New
- **§8.25:** `06-theory/manuscript/chapter_08/08.25-a-robot-acts-under-grants-a-translation-is-a-projection-a-status-tag-is-a-claim.md` (C1–C8). No new concept. The index row, TODO rows and map line are added only at settlement.
- **The step-8 file:** `review/C32-S0171-S0176/STEP8-AND-CHECKS.md`.

## Changed theory since the last review (v1.4 §2 step 9: in full)

1. **`9260e6d3a`, the Principal's ruling (decision record `7ecf5a024`, 2026-10-27), applied as changed theory for this window.**
   - **The ruling, in the Principal's words:**
     - T9-09 (a): a designation cites every maximal entry of the old store, and is refused at append if it omits one; the old store's end is fixed by citation alone, whatever the merge order; entries that bypass the kernel are not seen by this check and stay with the reference-list loss check (CON-0243).
     - T9-09 (b): a designation whose authority is F or U is refused at append, in the same kernel step (K6).
     - T8-04: an old system's behaviour is evidence of one possible choice, not a specification; an open choice is decided by a recorded act that adopts it explicitly, or stays U.
   - **The text it touches:**
     - §9.13.2 (kinds designate and observe);
     - §9.17 R1 and R3 (c);
     - §8.22 C7 and §8.22.3;
     - the §8.23 T8-04 line;
     - register rows CON-0264 and CON-0257;
     - T9-09 and T8-04 (resolved);
     - THEORY-MAP;
     - `tmp/tools/ce_model.py` v17 with its run `tmp/tools/ce_model-run-20261027-v17.txt` (checks 28–29).
   - **Questions:**
     - Is the ruling applied as worded? Where does the application go beyond the words, and is that labelled as the Professor's?
     - **The Professor's premise, marked for review:** each entry records the store the kernel appended it to. Is it needed, sufficient and stated in the right place? Does it answer the C31 W-01 probe, in which the old store's entries were derived from position?
     - **Check 29:** do its seven parts test what the text claims? Check in particular:
       - whether (1) covers merge orders with a designation and a later new-store entry that does not cite it;
       - whether (4) builds a genuine U;
       - whether (6) passes only by construction.
     - Are checks 1–27 really unchanged against v16? You may run the model read-only.
     - The kind `observe` (the Professor's modelling choice): is it the right way to model "an old system's behaviour"?
2. **`a7dc2b83d`, the C31 settlement:** §8.24 settled, the §9.17 R1 label split, and the CON-0264 self-citation. Check that each row of `review/C31-S0165-S0169/SETTLEMENT.md` matches the text it claims to change.

## Questions on §8.25
- The checks of REVIEWER-INSTRUCTIONS step 3, coverage against your inventory, and whether the recorded challenges are adequate.
- **C2:** is "an autonomous action is legitimate only as an act under a grant that covers it" a valid reading from §9.13 and §1.14.2-1? Is the attack on S0171's worked example fair?
- **C6:** is the deduction "a decision taken on a translation must cite the governed state" valid, and is it rightly limited to a mapping that loses information?
- **C7:** are the "ESTABLISHED" tags in S0175 and S0176 rightly read as claims, and is "a synthesis is one source with its inputs" right?
- **Your contradictions without a home in §8.25:**
  - S0171 L129 against S0172 L156 (does KnowledgeOS supply authority?);
  - S0172 L160 against S0175 L46 (do grants carry time?);
  - the three template variants in S0173.

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history, the review folders C30–C32, and the five sources.
- **Further corpus files:** only from `CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open. **Never open a file in `docs/knowledgeos/` that is not on that list.** S0174 is not on it.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- **No Ch. 0–2 basis files.** Never `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, or `docs/knowledgeos/chronological-read/*`.
- No corpus or book text to any outside service. Edit nothing. You may run `tmp/tools/ce_model.py` (it takes about 30 s) and the check tools read-only.

## Report
- About two pages, in tables:
  - findings (W-nn · finding · severity · evidence · correction · resolved when);
  - claim verdicts;
  - coverage;
  - a summary of at most 10 lines;
  - one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C32 STEP 2-3: REVIEW REPORT".
