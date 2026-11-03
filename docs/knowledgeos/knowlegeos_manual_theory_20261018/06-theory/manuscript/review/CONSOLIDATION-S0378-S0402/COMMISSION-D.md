# Consolidation S0378–S0402: reviewer D (commission)

*2026-11-03. Rulebook v1.4 §12. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (consolidation review). Working instructions in force: `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`.*

## Files you may open in this folder: only these
`REVIEWER-NOTE.md` (binding; read first), `SCOPE.md`, and this file. Open no other file in this folder, and nothing in `00-access/`. Other reviewers' reports are not given to you, so that your verdicts are independent.

## The object
The frozen copy `BOOK-UNDER-REVIEW/` only. You may read any part of the copy that the sections in your commission cite.

## Tasks, in this order
1. **Ch. 8 `08.103`–`08.105` and their index rows (SCOPE §2.6, reviewer D)**, read in full, section by section: each claim against its cited source lines (allowed list only), its label (Src/Int/Ded), its homes, and the checks of REVIEWER-INSTRUCTIONS step 3. Defects of fact, attribution, label, derivation, scope, stale text, contradictions between sections, SEQ order.
2. **Change log** `08.99` L141–143, and **`THEORY-MAP.md`** L274–279 (the lines for C106–C111, with the R3 note on L277) and L455–456 (position and counts): does each match the text it describes?
3. **`SUMMARIES/08.SUMMARY.md`, block 4:** does each statement restate a settled section of §8.100–§8.105, without adding a result or a stronger claim? Name the section each statement restates.
4. **Three-digit section numbers** (SCOPE §2.1): check that the index rows from 8.100 and the files sort numerically; and check every citation of §8.100–§8.105 in the copy (use `inbound_citations.py` and `book_search.py`) for an existing section and claim, and for confusion with §8.10–§8.15.
5. **Coverage against the window inventories** (SCOPE §2.3: C109, C110, C111): for each inventory item, covered / distorted / missing / justifiably excluded, with the home.
6. **Per-finding fields** (SCOPE §2.7): for every finding, the window, the source (S-ID) and whether the text is settlement text or draft text.
7. **Concepts cited** in your sections: ID, status and meaning as in the copy's register (SCOPE §2.2).
8. **R2** with `--scope` on your sections: every candidate judged.
9. **R3** for each correction you propose; give each inbound line a disposition.

Nothing outside your sections is in scope; note out-of-scope observations as one line each. If you cannot cover something, say so rather than skimming it.

## Additional rules (the Principal, 2026-11-02 and 2026-11-03; beyond the fixed template)
- **Working instructions in force:** `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`; the decision record wins where they differ (REVIEWER-NOTE item 2).
- **Files you may copy to scratch:** none. Run every tool directly on the frozen copy with `--root <copy>`; write tool outputs only to your own scratch directory, named in your dispatch.
- **No globs:** every search, listing or read names its files one by one by full path; no wildcard (`*`, `?`, `[...]`), no recursive search (`grep -r`, `find`), and no directory listing over corpus or book folders. A file you may not open, you may not search either.
- **The allowed list** is `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Locate sources only through it.

## Rules (the Principal)
- A finding is not resolved because code or its checks pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- Edit nothing in the repository. No corpus or book text to any outside service. Do not reproduce any private person's name or any outside author's name; persons named as people in the sources are referred to by role.

## Report
- Tables: findings (ID · section · finding · severity: critical / high / normal · evidence · correction · R3 lines · resolved when · window · source · settlement or draft text); coverage (item · status · home); R2 (word · judgement); coverage of sections (read in full / not).
- A summary of at most 15 lines, and one line on process independence.
- End with every file you opened and every command and search you ran (REVIEWER-NOTE item 8).
- Deliver it once, beginning "CONSOLIDATION S0378–S0402: REPORT D".
