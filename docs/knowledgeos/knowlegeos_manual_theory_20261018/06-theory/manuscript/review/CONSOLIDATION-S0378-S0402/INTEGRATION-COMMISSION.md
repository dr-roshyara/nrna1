# Consolidation S0378–S0402: integration review (commission)

*2026-11-03. Rulebook v1.4 §12. Working instructions in force: `03-methodology/WORKING-INSTRUCTIONS-IN-FORCE.md`. You are the integration reviewer. Reviewers A, B, C and D reviewed the frozen copy independently; their reports are in this folder.*

## Files you may open in this folder: only these
`REVIEWER-NOTE.md` (binding; read first), `SCOPE.md`, `REPORT-A.md`, `REPORT-B.md`, `REPORT-C.md`, `REPORT-D.md`, and this file. Open no other file in this folder, and nothing in `00-access/`.

## Tasks (verify and integrate; do not start another full review)
1. **Each finding:** check it against the copy and the cited sources; **confirmed / rejected / modified**, with severity. Merge duplicates.
2. **R3, every line:** for each confirmed correction, give **each inbound line** a disposition: stays true / needs the same change / needs a different change (say which) / not affected. Where the tool cannot resolve a target, search the copy and say so.
3. **References to missing claims** (REVIEWER-NOTE item 7): for each such finding, check whether content is missing or the pointer is stale, before confirming a remedy.
4. **Conflicts between reviewers:** resolve each with a reason, including pairs of corrections that touch the same text.
5. **Defects in the rotated chapter (Ch. 10):** list the confirmed ones by reviewer; add any new defect you find while checking.
6. **Per-finding fields for §8.100–§8.105** (SCOPE §2.7): for each confirmed finding of C and D, confirm or correct the window, the source and the settlement-or-draft classification.
7. **Coverage:** list any section in scope that no reviewer read in full, and any summary statement left unchecked. Do not fill the gap.
8. **Access deviations** disclosed by the reviewers: weigh them, do not re-run them. Check a sample of the findings of any reviewer who read outside the copy, on the copy, and say whether any rests on such a read.
9. **Decisions in scope:** for each finding about the Ch. 10 changes of 2026-11-03, quote the decision the copy cites for it (U2, U6, T9-21, the closure of T10-20), as the copy records it. Say what the decision decides, what the copy treats as decided beyond it, and what therefore needs a question to the Principal rather than an edit. State any such question in one sentence, with the options.

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
- Tables: findings (ID(s) · verdict · severity · chapter · evidence · correction · window · source · settlement or draft text, the last three for §8.100–§8.105 only); R3 (correction · inbound line · disposition); conflicts (pair · resolution · reason); rotated-chapter defects; coverage gaps.
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened and every command and search you ran.
- Deliver it once, beginning "CONSOLIDATION S0378–S0402: INTEGRATION REPORT".
