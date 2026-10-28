# C36, the window commission: steps 2–3

*2026-10-28. Sent after your blind inventory was saved (`STEP1-INVENTORY.md`, sha256 in `SHA256-STEP1.txt`). Re-read it first. The instruction is `03-methodology/REVIEWER-INSTRUCTIONS.md` (transition review).*

## New
- **§8.29:** `06-theory/manuscript/chapter_08/08.29-a-missing-act-is-not-supplied-and-a-tool-scoped-gate-is-bypassable.md` (C1–C7). No new concept.
- **The step-8 file:** `review/C36-S0192-S0196/STEP8-AND-CHECKS.md`.

## Changed theory since the last review (v1.4 §2 step 9: in full)
1. **`028a24599`, the C35 settlement.** Read `review/C35-S0187-S0191/SETTLEMENT.md` and the C35 review report, then `git show 028a24599` for each theory file and for `tmp/tools/ce_model.py`.
   - Does each settlement row match the text it claims to change?
   - Is W-04's "reasoned exclusion" (kernel candidates listed for T0-03b, not assessed) an adequate disposition, or does it leave a gap?
   - Are the two v20 limits (owner constant along a chain; detection resting on a declared application) stated where they are needed, and is "no new model version" justified?
   - Is §8.28 C8's restatement (two senses of "durable") right against S0188 L630 and S0190 L176?

## Questions on §8.29
- The checks of REVIEWER-INSTRUCTIONS step 3, coverage against your inventory, and whether the recorded challenges are adequate.
- **C1:** is "an artifact's name can itself assert an act" a fair reading of S0192 L6, and is §8.26 C3 the right home?
- **C2:** does CON-0120 fit S0194 L42, given its hedges?
- **C3:** the draft notes that the text read is not the text reviewed (S0196 carries the erratum). Is that handled correctly? And does the erratum overstate S0195's conditional (your inventory, S0196 item 16)?
- **C5:** is the link between the tool-scoped gate (S0196 L494) and §9.17's bypass entries sound, or an analogy?
- **C6:** does the draft handle the M4 axes (authority ⊥ status ⊥ maturity ⊥ adoption) correctly against §3.2's dimension test?
- **Your items** that the draft does not carry, for example: the safety-without-liveness point (S0192); the 0-occurrence measurement resting on an unattestable identity (S0193); the documented purpose classed **A** in S0196 L109 and L149; the two evidence hierarchies (8 tiers against 10); S0196 L548 listing the gate scripts as PKS integration.

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.

## Access (binding)
- **Allowed now:**
  - the working book (W/06-theory), the register, the map, W's tools and git history;
  - the review folders C35 and C36;
  - the five sources.
- **Further corpus files:** only from `CORPUS-ALLOWED.tsv`. Check each path before opening it, and list any you open.
- **Never:**
  - S0155, S0156, S0157, S0161, S0170 or S0174, and do not rely on what a source says about them;
  - `docs/knowledge_tranfer/*`, and do not rely on what S0196 quotes from those files;
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
- Deliver it once, beginning "C36 STEP 2-3: REVIEW REPORT".
