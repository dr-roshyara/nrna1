# Consolidation S0378–S0402: reviewer A (commission)

*2026-11-03. Rulebook v1.4 §12. Instruction: `03-methodology/REVIEWER-INSTRUCTIONS.md` (consolidation review). Working instructions in force: `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`.*

## Files you may open in this folder: only these
`REVIEWER-NOTE.md` (binding; read first), `SCOPE.md`, and this file. Open no other file in this folder, and nothing in `00-access/`. Other reviewers' reports are not given to you, so that your verdicts are independent.

## The object
The frozen copy `BOOK-UNDER-REVIEW/` only. You may read any part of the copy that the sections in your commission cite.

## Tasks, in this order
1. **Ch. 10 `10.00`–`10.03`, `10.99`, `TODO.md` (SCOPE §2.6, reviewer A)**, read in full, section by section: each claim against its cited source lines (allowed list only), its label (Src/Int/Ded), its homes, and the checks of REVIEWER-INSTRUCTIONS step 3. Defects of fact, attribution, label, derivation, scope, stale text, contradictions between sections, SEQ order.
2. **The Ch. 10 changes of 2026-11-03 (SCOPE §2.1):** for each (consequences 1–6; step 2; T10-20 closed and T10-21 item (5)), does it follow from the decision it cites (U2, U6, T9-21, the Principal's closure of T10-20), quoted from the copy, and does the in-line note keep the earlier wording? Is any row of `10.00`–`10.03` still stale against a decided Ch. 9 result in the copy?
3. **Coverage:** Ch. 10 was last reviewed in full at the consolidation S0226–S0252; no window inventory covers it. Check every pointer from your sections to Ch. 9 and Ch. 8, and every TODO row in Ch. 10's `TODO.md`, against the copy: still open, resolved elsewhere (where), or stale.
4. **`BOOK-OUTLINE.md`, the rotation section (L186–212):** does the table match the consolidations the copy records?
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
- Deliver it once, beginning "CONSOLIDATION S0378–S0402: REPORT A".
