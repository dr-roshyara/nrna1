# Consolidation S0278–S0302: reviewer A (commission)

*2026-10-31. Rulebook v1.4 §12. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (consolidation review).*

## Files you may open in this folder: only these
`REVIEWER-NOTE.md` (binding; read first), `SCOPE.md`, and this file. Open no other file in this folder, and nothing in `00-access/`. Other reviewers' reports are not given to you, so that your verdicts are independent.

## The object
The frozen copy `BOOK-UNDER-REVIEW/` only. You may read any part of the copy that the sections in your commission cite.

## Tasks, in this order
1. **Ch. 2 `02.00`–`02.11` (with `02.00a`), `02.99` and `TODO.md` of the copy (SCOPE §2.6, reviewer A)**, read in full, section by section: each claim against its cited source lines (allowed list only), its label (Src/Int/Ded), its homes, and the checks of REVIEWER-INSTRUCTIONS step 3. Defects of fact, attribution, label, derivation, scope, stale text, contradictions between sections, SEQ order.
2. **Concept verdicts** for the concepts in SCOPE §2.2 homed in your sections, by its rule.
3. **Coverage:** Ch. 2 was last reviewed in full at the consolidation S0131–S0150; no window inventory covers it. Instead, check every pointer from your sections to later chapters, and every TODO row in Ch. 2's `TODO.md`, against the copy: still open, resolved elsewhere (where), or stale.
4. **R2** with `--scope` on your sections: every candidate judged.
5. **R3** for each correction you propose; give each inbound line a disposition.

Nothing outside your sections is in scope; note out-of-scope observations as one line each. If you cannot cover something, say so rather than skimming it.

## Rules (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- Edit nothing in the repository. No corpus or book text to any outside service. Do not reproduce any private person's name or any outside author's name.

## Report
- Tables: findings (ID · section · finding · severity: critical / high / normal · evidence · correction · R3 lines · resolved when); concept verdicts (ID · verdict · reason); coverage (item · status · home); R2 (word · judgement); coverage of sections (read in full / not).
- A summary of at most 15 lines, and one line on process independence.
- End with every file you opened and every command and search you ran (REVIEWER-NOTE item 7).
- Deliver it once, beginning "CONSOLIDATION S0278–S0302: REPORT A".
