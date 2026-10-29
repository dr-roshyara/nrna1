# Consolidation S0182–S0200: reviewer C (commission)

*2026-10-29. Rulebook v1.4 §12. The Principal's instruction (2026-10-29): an extra reviewer for Ch. 8 §8.07–§8.26 of the frozen copy. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (consolidation review).*

## Files you may open in this folder: only these
`REVIEWER-NOTE.md` (binding; read first) and this file. Open no other file in this folder, and nothing in `00-access/`. Other reviewers' reports are not given to you, so that your verdicts are independent.

## The object
The frozen copy `BOOK-UNDER-REVIEW/` only. You may read any part of the copy that the sections cite, including Ch. 9, the register and the map.

## Tasks, in this order
1. **Read Ch. 8 §8.07–§8.26 of the copy in full**, section by section (files `chapter_08/08.07-…` to `08.26-…`). For each claim, check it against its cited source lines (corpus files from the allowed list only), and check its label (Src/Int/Ded), its homes and the checks of REVIEWER-INSTRUCTIONS step 3. Report defects of fact, attribution, label, derivation, scope, stale text, and contradictions between sections.
2. **Concept verdicts** from the text, for the 76 concepts homed in these sections. The rule: a verdict for each PROVISIONAL; a second, independent verdict for each VALIDATED; each INVALID core checked as restated (not re-litigated).
   - VALIDATED (11): CON-0129, 0153, 0219, 0220, 0226, 0232, 0239, 0240, 0242, 0245, 0253.
   - PROVISIONAL (49): CON-0130, 0141, 0150, 0151, 0152, 0154, 0156, 0158, 0178, 0179, 0180, 0181, 0195, 0196, 0199, 0200, 0201, 0203, 0204, 0206, 0207, 0211, 0212, 0215, 0216, 0217, 0221, 0222, 0223, 0224, 0225, 0228, 0229, 0230, 0231, 0233, 0236, 0237, 0238, 0241, 0243, 0244, 0248, 0249, 0250, 0251, 0254, 0257, 0259.
   - INVALID (16): CON-0155, 0157, 0159, 0197, 0198, 0202, 0205, 0218, 0227, 0234, 0235, 0246, 0247, 0252, 0255, 0256.
3. **R3** (`python3 -I tmp/tools/inbound_citations.py --root 06-theory/manuscript/review/CONSOLIDATION-S0182-S0200/BOOK-UNDER-REVIEW "<claim>"`) for each correction you propose. Give each inbound line a disposition.

Nothing outside these sections is in scope. Note out-of-scope observations as one line each. If you cannot cover something, say so rather than skimming it.

## Rules (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- Edit nothing in the repository. No corpus or book text to any outside service. Do not reproduce any private person's name.

## Report
- Tables: findings (ID · section · finding · severity: critical / high / normal · evidence · correction · R3 lines · resolved when); concept verdicts (ID · verdict · reason); coverage (sections read in full, sections not).
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened and every command and search you ran (REVIEWER-NOTE item 6).
- Deliver it once, beginning "CONSOLIDATION S0182–S0200: REPORT C".
