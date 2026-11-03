# Consolidation S0353–S0377: reviewer A (commission)

*2026-11-03. Rulebook v1.4 §12. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (consolidation review).*

## Files you may open in this folder: only these
`REVIEWER-NOTE.md` (binding; read first), `SCOPE.md`, and this file. Open no other file in this folder, and nothing in `00-access/`. Other reviewers' reports are not given to you, so that your verdicts are independent.

## The object
The frozen copy `BOOK-UNDER-REVIEW/` only. You may read any part of the copy that the sections in your commission cite.

## Tasks, in this order
1. **Ch. 9 `09.00`–`09.09`, `09.99`, `TODO.md` (SCOPE §2.6, reviewer A)**, read in full, section by section: each claim against its cited source lines (allowed list only), its label (Src/Int/Ded), its homes, and the checks of REVIEWER-INSTRUCTIONS step 3. Defects of fact, attribution, label, derivation, scope, stale text, contradictions between sections, SEQ order.
2. **Concept verdicts** for the concepts in SCOPE §2.2 homed in your sections, by its rule: CON-0099, 0100, 0183 (09.01); 0105 (09.02); 0110, 0111 (09.03); 0114, 0115, 0116, 0167 (09.04); 0117, 0118 (09.05); 0135, 0136, 0137, 0138, 0169, 0170, 0171 (09.06); 0142, 0143, 0173 (09.07); 0148, 0149, 0177 (09.08); 0160, 0161, 0182 (09.09).
3. **Coverage:** Ch. 9 was last reviewed in full at the consolidation S0201–S0225; no window inventory covers it. Instead, check every pointer from your sections to later chapters, and every TODO row in Ch. 9's `TODO.md`, against the copy: still open, resolved elsewhere (where), or stale.
4. **R2** with `--scope` on your sections: every candidate judged.
5. **R3** for each correction you propose; give each inbound line a disposition.

Nothing outside your sections is in scope; note out-of-scope observations as one line each. If you cannot cover something, say so rather than skimming it.

## Additional rules (the Principal, 2026-11-02 and 2026-11-03; beyond the fixed template)
- **Files you may copy to scratch:** none. Run every tool directly on the frozen copy with `--root <copy>`; write tool outputs only to your own scratch directory, named in your dispatch.
- **No globs:** every search, listing or read names its files one by one by full path; no wildcard (`*`, `?`, `[...]`), no recursive search (`grep -r`, `find`), and no directory listing over corpus or book folders. A file you may not open, you may not search either.

## Rules (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- Edit nothing in the repository. No corpus or book text to any outside service. Do not reproduce any private person's name or any outside author's name.

## Report
- Tables: findings (ID · section · finding · severity: critical / high / normal · evidence · correction · R3 lines · resolved when); concept verdicts (ID · verdict · reason); coverage (item · status · home); R2 (word · judgement); coverage of sections (read in full / not).
- A summary of at most 15 lines, and one line on process independence.
- End with every file you opened and every command and search you ran (REVIEWER-NOTE item 7).
- Deliver it once, beginning "CONSOLIDATION S0353–S0377: REPORT A".
