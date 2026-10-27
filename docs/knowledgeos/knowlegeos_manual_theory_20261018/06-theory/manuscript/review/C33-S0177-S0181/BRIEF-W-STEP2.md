# C33, the window commission: steps 2–3

*2026-10-27. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). The draft is commit `f0fb39c29`.*

## New
- **§8.26:** `06-theory/manuscript/chapter_08/08.26-a-summary-is-not-evidence-and-an-artifacts-own-status-is-not-the-act.md` (C1–C6). No new concept.
- **The step-8 file:** `review/C33-S0177-S0181/STEP8-AND-CHECKS.md`.

## Changed theory since the last review (v1.4 §2 step 9: in full)
- **`167e5a7b8`, the C32 settlement.** Read `review/C32-S0171-S0176/SETTLEMENT.md`, then `git show 167e5a7b8` for each theory file and for `tmp/tools/ce_model.py` (v18), with its run `tmp/tools/ce_model-run-20261027-v18.txt`.
- **The Professor's technical completion of the decision `7ecf5a024`, marked for review.** It adds three things:
  - the old store must be the carrier in force;
  - the kernel records each entry's store as a claim;
  - maximality is taken over the entries the kernel accepted.

  D then admits one chain of designations, at most one per store, and no unknown store.
- **Questions:**
  - Does the completion close the fork and stale cases without changing what the decision record says? Does it add a condition the record does not imply?
  - Does check 30 test each claim made for it? Are checks 1–28 really unchanged? You may run the model read-only (about 30 s).
  - Does each settlement row match the text it claims to change?
  - Is any line left that still speaks of the old store's "last entry" as current rule, or quotes the decision in other words than the record's?

## Questions on §8.26
- The checks of REVIEWER-INSTRUCTIONS step 3, coverage against your inventory, and whether the recorded challenges are adequate.
- **C1:** do S0179's "ESTABLISHED" tags breach its own criterion? Is the rival reading answered?
- **C2:** S0181's summary of S0154 against the book's record of S0154 (§8.22 C7). S0154 is on the allowed list; you may open it to check.
- **C3:** an artifact's own status versus the act. Is "no contradiction to resolve" right? Your item S0179-13 (DC-2's "execute the approved plan" against a PROPOSED plan) bears on it.
- **C6:** "kernel" as a built component versus the theoretical core: a homonym, or a real claim against the book?
- **Your items without a home in §8.26** (for example S0177's tension between the established-tagged candidate invariant and the warn-only checker; S0178's grant counts; S0180's attribution drift).

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history, the review folders C30–C33, the five sources, and S0154.
- **Further corpus files:** only from `CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open. **Never open a file in `docs/knowledgeos/` that is not on that list.**
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- **No Ch. 0–2 basis files.** Never `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, or `docs/knowledgeos/chronological-read/*`.
- No corpus or book text to any outside service. Edit nothing.

## Report
- About two pages, in tables:
  - findings (W-nn · finding · severity · evidence · correction · resolved when);
  - claim verdicts;
  - coverage;
  - a summary of at most 10 lines;
  - one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C33 STEP 2-3: REVIEW REPORT".
