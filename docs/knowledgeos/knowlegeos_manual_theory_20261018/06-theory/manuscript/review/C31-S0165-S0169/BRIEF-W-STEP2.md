# C31, the window commission: steps 2–3

*2026-10-27. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review). No kernel section changed in this window. The draft is commit `e95dfeb0a`.*

## New
- **§8.24:** `06-theory/manuscript/chapter_08/08.24-a-role-is-read-from-the-record-and-a-store-change-is-not-a-provider-swap.md` (C1–C12). No new concept. The index row, TODO rows and map line are added only at settlement.
- **The step-8 file:** `review/C31-S0165-S0169/STEP8-AND-CHECKS.md`. It holds the measures, a reading defect your inventory exposed (S0166 L1332, S0168 L1612), the challenge records, the dependencies and the checks.

## Changed theory since the last review (v1.4 §2 step 9: in full)
- **`68b90a77e`, the C30 settlement.** Read `review/C30-S0159-S0164/SETTLEMENT.md`, then `git show 68b90a77e` for each theory file:
  - §8.23 (settled form);
  - §8.22 C2 (scoped);
  - §8.19 L236;
  - §9.13.2 (designation);
  - §9.15 K2 cell;
  - §9.17 R3 (c) and K2-w;
  - register CON-0264 and CON-0261;
  - T8-04, T9-09, T0-03;
  - THEORY-MAP.
- **Questions on it:**
  - Does each settlement row match the text it claims to change?
  - Does the restated R3 (c) claim more than the evidence (C30 probe P1, check 28's stated scope)?
  - Is the C30 settlement's rejection of citation down-closure as the membership rule (row W-01) correct?
- *For your information, not for review:* the Principal has since ruled on T9-09 and T8-04. Those rulings are applied only after this review, so judge the text as it stands at `68b90a77e`.

## Questions on §8.24
- The checks of REVIEWER-INSTRUCTIONS step 3, coverage against your inventory, and whether the recorded challenges are adequate.
- **C7 (the one deduction):** is "a change of the store that carries the record is a designation, not a provider swap" valid from §9.17 R1, R3 (c) and the kind designate? Is its scope right? Does step 4 (K2-w against the configuration) hold?
- **C1:** is a terminal-resolved role correctly an instance of CON-0239?
- **C4:** is "Workflow state authorizes" a homonym, as the draft says, or a contradiction of ARCH-INV-1, as a plain reading suggests?
- **C9:** is the homonym (entry identity, not carrier identity) handled correctly?
- **C12 and lineage:** are S0169 and S0163 rightly one source? Is S0166/S0167/S0169 one line?
- **Your items without a home in §8.24** (for example the S0167 roadmap arithmetic; the S0169 moat tension; the five-actor versus six-role taxonomies): should any of them be in the section?

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:** the working book (W/06-theory), the register, the map, W's tools and git history, the review folders C30 and C31, and the five sources.
- **Further corpus files:** only from `CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open. **Never open a file in `docs/knowledgeos/` that is not on that list.** S0170 is not on it.
- If a file is refused or not allowed, do not inspect it another way. Record it and continue.
- **No Ch. 0–2 basis files.** Never `.env*`, `_batch_input*`, sealed, hold-out or quarantine files, or `docs/knowledgeos/chronological-read/*`.
- No corpus or book text to any outside service. Edit nothing. You may run `tmp/tools/ce_model.py` and the check tools read-only.

## Report
- About two pages, in tables:
  - findings (W-nn · finding · severity · evidence · correction · resolved when);
  - claim verdicts;
  - coverage;
  - a summary of at most 10 lines;
  - one line on process independence.
- End with the files you opened and the commands you ran.
- Deliver it once, beginning "C31 STEP 2-3: REVIEW REPORT".
