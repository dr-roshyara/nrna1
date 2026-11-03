# Consolidation S0378–S0402: reviewer B (commission)

*2026-11-03. Rulebook v1.4 §12. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (consolidation review). Working instructions in force: `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`.*

## Files you may open in this folder: only these
`REVIEWER-NOTE.md` (binding; read first), `SCOPE.md`, and this file. Open no other file in this folder, and nothing in `00-access/`. Other reviewers' reports are not given to you, so that your verdicts are independent.

## The object
The frozen copy `BOOK-UNDER-REVIEW/` only. You may read any part of the copy that the sections in your commission cite.

## Tasks, in this order
1. **Ch. 10 `10.04`–`10.05` (SCOPE §2.6, reviewer B)**, read in full, section by section: each claim against its cited source lines (allowed list only), its label (Src/Int/Ded), its homes, and the checks of REVIEWER-INSTRUCTIONS step 3. Defects of fact, attribution, label, derivation, scope, stale text, contradictions between sections, SEQ order.
2. **Ch. 10 SEQ order** across `10.00`–`10.05`: is anything used before it is introduced, given the turn order the index states?
3. **Coverage:** check every pointer from your sections to Ch. 9, Ch. 8 and the rest of Ch. 10 against the copy: still valid, resolved elsewhere (where), or stale. Is any row of `10.04`–`10.05` stale against a decided Ch. 9 result in the copy (U2, U6, T9-02's retyped deliverable)?
4. **`SUMMARIES/10.SUMMARY.md`:** does each statement restate a settled section of Ch. 10, without adding a result or a stronger claim? Name the section each statement restates.
5. **Concepts cited** in your sections: ID, status and meaning as in the copy's register (SCOPE §2.2).
6. **R2** with `--scope` on your sections: every candidate judged.
7. **R3** for each correction you propose; give each inbound line a disposition.

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
- Tables: findings (ID · section · finding · severity: critical / high / normal · evidence · correction · R3 lines · resolved when); coverage (item · status · home); R2 (word · judgement); coverage of sections (read in full / not).
- A summary of at most 15 lines, and one line on process independence.
- End with every file you opened and every command and search you ran (REVIEWER-NOTE item 8).
- Deliver it once, beginning "CONSOLIDATION S0378–S0402: REPORT B".
