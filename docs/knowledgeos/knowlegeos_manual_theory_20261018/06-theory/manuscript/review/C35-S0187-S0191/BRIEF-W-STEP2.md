# C35, the window commission: steps 2–3

*2026-10-28. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review).*

## New
- **§8.28:** `06-theory/manuscript/chapter_08/08.28-the-kernel-is-a-mechanism-not-a-meaning-and-nothing-in-the-estate-attests-identity.md` (C1–C8). No new concept.
- **The step-8 file:** `review/C35-S0187-S0191/STEP8-AND-CHECKS.md`.

## Changed theory since the last review (v1.4 §2 step 9: in full)
1. **`756b31612`, the C34 settlement.**
   - Read `review/C34-S0182-S0186/SETTLEMENT.md` and the C34 review report, then `git show 756b31612` for each theory file and for `tmp/tools/ce_model.py` (v20), with its run `tmp/tools/ce_model-run-20261028-v20.txt`.
   - Ask whether each settlement row matches the text it claims to change.
   - **v20 (the owner check for designations):** is the owner rule now enforced at append and in D? Is `store_owner` read from the record soundly, for example when a store is named by a designation that is not attested, or by two designations? Are the premises (the moving application trusted as declared) and the limits (presence, not validity; one kernel id; no owner transfer) stated? Are checks 1–31 really unchanged against v19? You may run the model read-only and probe it on a scratch copy.
   - **§9.17 (c′):** is the decision record now quoted as given, with the chat message labelled? Is "T9-10's completion condition met by check 32" justified?
   - **§8.27 restated:** C1 (record over relay; T8-03's instance), C2, C3, C4 (partial conformance), C6.

## Questions on §8.28
- The checks of REVIEWER-INSTRUCTIONS step 3, coverage against your inventory, and whether the recorded challenges are adequate.
- **Lineage:** the draft reads S0187 as relying on S0186 (an allowed source you were not given), by title and content (S0187 L178 ≈ S0186 L309; S0187 L433 ≈ S0186 L443–455). You may open S0186 (`docs/knowledgeos/architecture/20260821_2032_EKS Current Architecture Baseline.md`, on the allowed list) to check this.
- **C1:** are criteria 3–5 rightly kept apart from the boundary questions of BOOK-OUTLINE §3?
- **C2:** is the distinction between attestation of acceptance and attestation of identity sound? Is "no instance in the estate" the right strength for T9-11?
- **C4, C8:** are S0188's figures rightly treated as one line with §1.13 (AMS)? Is the durability contradiction (S0188 L1014 against S0190 L176) stated at the right strength?
- **Your items** that the draft does not carry, for example: S0188's second mechanism (unnamed); S0189's single demonstrated run; the two meanings of "KnowledgeOS" in S0189; S0190's monotone narrowing as a liveness question.

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:**
  - the working book (W/06-theory), the register, the map, W's tools and git history;
  - the review folders C34 and C35;
  - the five sources, and S0186.
- **Further corpus files:** only from `CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open.
- **Never:**
  - S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them;
  - `docs/knowledge_tranfer/*`;
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
- Deliver it once, beginning "C35 STEP 2-3: REVIEW REPORT".
