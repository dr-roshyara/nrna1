# Consolidation S0182–S0200: integration review (commission)

*2026-10-28. Rulebook v1.4 §12. You are the integration reviewer. Two reviewers, A (Opus) and B (Sonnet), reviewed the frozen copy independently; their reports are in this folder.*

## Files you may open in this folder: only these
`REVIEWER-NOTE.md` (binding; read first), `SCOPE.md`, `REPORT-A.md`, `REPORT-B.md`, and this file. Open no other file in this folder or in `00-access/`.

## Tasks (keep it short: verify and integrate; do not start a third full review)
1. **Each finding of A and B:** check it against the copy and the cited sources, then give **confirmed / rejected / modified**, with severity. Merge duplicates, where A and B report the same defect.
2. **R3, every line:** for each confirmed correction, run `python3 -I tmp/tools/inbound_citations.py --root 06-theory/manuscript/review/CONSOLIDATION-S0182-S0200/BOOK-UNDER-REVIEW "<claim>"`. Give **each inbound line** a disposition: stays true / needs the same change / needs a different change (say which) / not affected. Where the tool cannot resolve a target (e.g. "R3 (c)", "§9.13.2"), grep the copy and say so.
3. **Conflicts between A and B:** resolve each one with a reason. Examples are CON-0121, 0127, 0128 and 0059; B-06 against A-11 on S0200's magnitudes; and B-11 against §8.28 C2. Also resolve each pair of corrections that touch the same text.
4. **Defects in the rotated chapters (Ch. 6, Ch. 8):** list the confirmed ones as A only / B only / both. Add any new defect you find while checking.
5. **Coverage gap:** neither reviewer read Ch. 8 §8.07–§8.26 in full. Do not fill the gap. List the concepts left without a second verdict, and any confirmed defect that lies in those sections.

## Rules (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- Edit nothing in the repository. No corpus or book text to any outside service. Do not reproduce any private person's name.

## Report
- Tables: findings (ID(s) · verdict · severity · chapter · evidence · correction); R3 (correction · inbound line · disposition); conflicts (pair · resolution · reason); rotated-chapter defects; the coverage gap.
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened and every command and search you ran.
- Deliver it once, beginning "CONSOLIDATION S0182–S0200: INTEGRATION REPORT".
