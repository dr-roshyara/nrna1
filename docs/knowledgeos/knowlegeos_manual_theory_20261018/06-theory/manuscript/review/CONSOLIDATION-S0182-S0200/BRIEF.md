# Consolidation S0182–S0200: the commission (reviewers A and B)

*2026-10-28. Rulebook v1.4 §12. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (consolidation review). Read `REVIEWER-NOTE.md` (in this folder) first; it is binding. The scope is `SCOPE.md` (in this folder); keep to it.*

## What to review, in this order
1. **§8.27–§8.30** of the copy: each claim against its cited source lines (the sources are on the allowed list), its labels (Src/Int/Ded), its homes, and the checks of REVIEWER-INSTRUCTIONS step 3.
2. **The passages changed in the C34–C37 settlements** (`SCOPE.md`): §9.17 R3 (a), R3 (c), (c′); §9.13.2; T9-10, T9-11, T0-03b; model v20 (read-only; you may run it and probe it on a scratch copy).
3. **Ch. 6 in full**, then **Ch. 8 in full** (§8.00–§8.26 as well): defects of fact, attribution, label, derivation, scope, stale text, contradictions between sections, and the SEQ order.
4. **Concept verdicts** for the concepts listed in `SCOPE.md`, by its rule.
5. **Coverage:** against the four window inventories named in `SCOPE.md`; rank the items the book misses.

Ch. 8 is long (about 4,100 lines). Work section by section and keep your own notes in a scratch directory outside the repository. Report what you could not cover rather than skimming it.

## Rules for findings (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- For each correction you propose, run R3 (`inbound_citations.py --root …/BOOK-UNDER-REVIEW`) and give the inbound lines.

## Report
- Tables: findings (ID · section · chapter · finding · severity: critical / high / normal · evidence · correction · R3 lines · resolved when); concept verdicts (ID · verdict · reason); coverage (item · status · home); SEQ order (chapter · problem).
- A summary of at most 15 lines, and one line on process independence.
- End with every file you opened and every search and command you ran (REVIEWER-NOTE item 6).
- Edit nothing in the repository. No corpus or book text to any outside service. Do not reproduce any private person's name.
- Deliver it once, beginning "CONSOLIDATION S0182–S0200: REPORT <A or B>".
