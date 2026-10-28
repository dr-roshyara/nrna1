# C34, the window commission: steps 2–3

*2026-10-28. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review).*

## New
- **§8.27:** `06-theory/manuscript/chapter_08/08.27-eligibility-is-read-from-the-record-and-a-registered-act-that-is-not-live-is-false.md` (C1–C7). No new concept.
- **The step-8 file:** `review/C34-S0182-S0186/STEP8-AND-CHECKS.md`.

## Changed theory since the last review (v1.4 §2 step 9: in full)
1. **`4e50725e2`, the consolidation S0151–S0181 settlement.**
   - Read `review/CONSOLIDATION-S0151-S0181/SETTLEMENT.md`, then `git show 4e50725e2` for each theory file and for `tmp/tools/ce_model.py` (v19), with its run `tmp/tools/ce_model-run-20261028-v19.txt`.
   - Ask whether each settlement row matches the text it claims to change. Spot-check at least ten rows, all from §1 (the designation block) and the Ch. 4 and Ch. 5 rows.
   - Ask whether v19's premise (kernel attestation, fail-closed) is stated with its limits, and whether checks 1–29 really are unchanged against v18 (you may run the model read-only).
2. **`d149ebd07`, the Principal's decision of 2026-10-28.** It is in §9.17 (c′), T9-10, T9-11 and the map.
   - Ask whether the decision is quoted as given and whether the Professor's reading is labelled.
   - Ask whether "Not modelled: the owner check for designations" is the right scope.

## Questions on §8.27
- The checks of REVIEWER-INSTRUCTIONS step 3, coverage against your inventory, and whether the recorded challenges are adequate.
- **C1:** is T8-03 the right home? Is n = 2 within one chain correctly bounded?
- **C2:** does "a registered act that is not live is a false record" follow as the book states it, and is §8.19 C7 the right link?
- **C6:** is "the repository is the system of record" rightly read as a statement of the record (a relay), and is the CON-0239 link to "processes see different states" justified by a reported, unshown observation?
- **Your items without a home** (for example S0183's invariant without a discharging command, OPEN-M7; the conditional vs absolute exclusion of `870305e0`; S0186's internal tension between "executable outranks prose" and its prose-based system of record).

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:**
  - the working book (W/06-theory), the register, the map, W's tools and git history;
  - the review folders C33, C34 and CONSOLIDATION-S0151-S0181 (its BOOK-UNDER-REVIEW copy only if needed);
  - the five sources.
- **Further corpus files:** only from `CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open.
- **Never:**
  - S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them;
  - Ch. 0–2 basis files; `.env*`, `_batch_input*`, sealed, hold-out or quarantine files; `docs/knowledgeos/chronological-read/*`.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- No corpus or book text to any outside service. Edit nothing. Do not reproduce any private person's name.

## Report
- About two pages, in tables:
  - findings (W-nn · finding · severity · evidence · correction · resolved when);
  - claim verdicts;
  - coverage;
  - a summary of at most 10 lines;
  - one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C34 STEP 2-3: REVIEW REPORT".
