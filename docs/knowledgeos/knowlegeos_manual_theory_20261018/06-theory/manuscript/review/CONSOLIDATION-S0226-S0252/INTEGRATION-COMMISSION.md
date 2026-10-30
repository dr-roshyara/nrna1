# Consolidation S0226–S0252: integration review (commission)

*2026-10-30. Rulebook v1.4 §12. You are the integration reviewer. Reviewers A and B reviewed the frozen copy independently; their reports are in this folder.*

## Files you may open in this folder: only these
`REVIEWER-NOTE.md` (binding; read first), `SCOPE.md`, `REPORT-A.md`, `REPORT-B.md`, and this file. Open no other file in this folder, and nothing in `00-access/`.

## Tasks (verify and integrate; do not start another full review)
1. **Each finding:** check it against the copy and the cited sources; **confirmed / rejected / modified**, with severity. Merge duplicates.
2. **R3, every line:** for each confirmed correction, give **each inbound line** a disposition: stays true / needs the same change / needs a different change (say which) / not affected. Where the tool cannot resolve a target, grep the copy and say so.
3. **References to missing claims** (REVIEWER-NOTE item 6): for each such finding, check whether content is missing or the pointer is stale, before confirming a remedy.
4. **Conflicts between reviewers:** resolve each with a reason, including pairs of corrections that touch the same text.
5. **Defects in the rotated chapters:** list the confirmed ones by reviewer; add any new defect you find while checking.
6. **Coverage:** list any section in scope that no reviewer read in full, and the concepts left without a verdict. Do not fill the gap.

## Rules (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- Edit nothing in the repository. No corpus or book text to any outside service. Do not reproduce any private person's name.

## Report
- Tables: findings (ID(s) · verdict · severity · chapter · evidence · correction); R3 (correction · inbound line · disposition); conflicts (pair · resolution · reason); rotated-chapter defects; coverage gaps.
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened and every command and search you ran.
- Deliver it once, beginning "CONSOLIDATION S0226–S0252: INTEGRATION REPORT".
