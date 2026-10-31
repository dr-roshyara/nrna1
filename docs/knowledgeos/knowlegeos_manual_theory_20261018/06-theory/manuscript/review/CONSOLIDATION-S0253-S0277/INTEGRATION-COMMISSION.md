# Consolidation S0253–S0277: integration review (commission)

*2026-10-31. Rulebook v1.4 §12. You are the integration reviewer. Reviewers A, B, C and D reviewed the frozen copy independently; their reports are in this folder.*

## Files you may open in this folder: only these
`REVIEWER-NOTE.md` (binding; read first), `SCOPE.md`, `REPORT-A.md`, `REPORT-B.md`, `REPORT-C.md`, `REPORT-D.md`, and this file. Open no other file in this folder, and nothing in `00-access/`.

## Tasks (verify and integrate; do not start another full review)
1. **Each finding:** check it against the copy and the cited sources; **confirmed / rejected / modified**, with severity. Merge duplicates.
2. **R3, every line:** for each confirmed correction, give **each inbound line** a disposition: stays true / needs the same change / needs a different change (say which) / not affected. Where the tool cannot resolve a target, grep the copy and say so.
3. **References to missing claims** (REVIEWER-NOTE item 6): for each such finding, check whether content is missing or the pointer is stale, before confirming a remedy.
4. **Conflicts between reviewers:** resolve each with a reason, including pairs of corrections that touch the same text.
5. **Defects in the rotated chapters:** list the confirmed ones by reviewer; add any new defect you find while checking.
6. **Coverage:** list any section in scope that no reviewer read in full, and the concepts left without a verdict. Do not fill the gap.
7. **Access deviations (disclosed by the reviewers; weigh them, do not re-run them):**
   - **A** ran some reads on the working book instead of the copy (the working register, map, ten chapter or outline files, and a recursive grep over the working manuscript that printed lines from old review folders). A states it re-read every fact on the copy with identical results. Check a sample of A's findings on the copy and say whether any rests on a working-book read.
   - **B**'s first `undefined_terms.py` call ran without `--root` (output discarded). B and D saw lines of the copy's `BASELINE-REVISIONS.md` and chapter-review notes in grep output (not opened).
   - **C**'s scratch file was overwritten by another reviewer's run; C re-ran R2.
8. **The scope of T9-19 (D-02).** The decision record's addendum (2026-10-31, recorded by the Governance Engineer) reads, for T9-19: "**T9-19 (b) — adopt all three evidence kinds** (evid, ewdr, erst) in the one record; applied at [the commit] (§9.18, §9.15 G1, §10.2, §10.3.6, §10.4, map). At S0. Candidate 2 admitted **for review** (not yet reviewed). Open: T9-14 (item identity), G7 (source kind), the U-case statement." For T9-20: "no guard on adoption; clause (c2) kept: standing comes from a recorded act by a holder of a grant, not from evidence … Open: the display fork (a warrant field, or 'supported, warrant not recorded'), with G7." For T10-21: "'disputed ownership' for §10.3's ownership status; Q1's F shown as 'not recognized as current authority', never 'refuted'; 'accepted by the kernel' for the kernel's seal; the evidence classes keep refuted / contested / not attested / supported … Open: the area name 'Standing' (with T10-15)." Against this text, say for D-01, D-02, D-03 and D-06 (c) what the record decides, what the copy treats as decided beyond it, and what therefore needs a question to the Principal (state it in one sentence, with the options), rather than an edit.

## Rules (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- Edit nothing in the repository. No corpus or book text to any outside service. Do not reproduce any private person's name or any outside author's name.

## Report
- Tables: findings (ID(s) · verdict · severity · chapter · evidence · correction); R3 (correction · inbound line · disposition); conflicts (pair · resolution · reason); rotated-chapter defects; coverage gaps.
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened and every command and search you ran.
- Deliver it once, beginning "CONSOLIDATION S0253–S0277: INTEGRATION REPORT".
