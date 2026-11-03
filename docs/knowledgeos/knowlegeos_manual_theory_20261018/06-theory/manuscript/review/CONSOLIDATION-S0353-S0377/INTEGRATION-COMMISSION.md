# Consolidation S0353–S0377: integration review (commission)

*2026-11-03. Rulebook v1.4 §12. You are the integration reviewer. Reviewers A, B, C, D, E and F reviewed the frozen copy independently; their reports are in this folder.*

## Files you may open in this folder: only these
`REVIEWER-NOTE.md` (binding; read first), `SCOPE.md`, `REPORT-A.md`, `REPORT-B.md`, `REPORT-C.md`, `REPORT-D.md`, `REPORT-E.md`, `REPORT-F.md`, and this file. Open no other file in this folder, and nothing in `00-access/`.

## Tasks (verify and integrate; do not start another full review)
1. **Each finding:** check it against the copy and the cited sources; **confirmed / rejected / modified**, with severity. Merge duplicates.
2. **R3, every line:** for each confirmed correction, give **each inbound line** a disposition: stays true / needs the same change / needs a different change (say which) / not affected. Where the tool cannot resolve a target, grep the copy and say so.
3. **References to missing claims** (REVIEWER-NOTE item 6): for each such finding, check whether content is missing or the pointer is stale, before confirming a remedy.
4. **Conflicts between reviewers:** resolve each with a reason, including pairs of corrections that touch the same text.
5. **Defects in the rotated chapters:** list the confirmed ones by reviewer; add any new defect you find while checking.
6. **Coverage:** list any section in scope that no reviewer read in full, and the concepts left without a verdict. Do not fill the gap.
7. **Access deviations**: disclosed by the reviewers; weigh them, do not re-run them: A: three tools run once with `--help` and no `--root`, so on the working book (output not used), and one R3 output saved by the harness outside A's scratch. B: `ls` on the copy's `chapter_09/` folder and on `TODO.md`. C: (1) two tools run once with `--help` and no `--root` (working book; output not used); (2) C read the lane's corpus index `02-FILES.jsonl` to locate sources (the Principal has ruled it recorded; its contents are not yours to check). D: two tools run with `--help` and no `--root` (working book; output not used). E: `ls` with glob patterns on wrong paths (nothing read), `ls` of the copy's `tmp/tools` and `chapter_01/`, one tool run with `--help` and no `--root` (rerun on the copy). F: one tool run with `--help` and no `--root` (rerun on the copy). Check a sample of the findings of any reviewer who read outside the copy on the copy, and say whether any rests on such a read.
8. **Decisions in scope**: the decision record's text for each, quoted: the Principal's confirmation, decision record addendum 2026-11-01T17:32:50Z, verbatim: "I confirm my rulings on the Req/A turn (2026-11-01), for the research record: U5: Req's inputs are read as (a) EF. U6: O1 is (a) one requirement per act kind; D clauses for the five kinds outside D to follow in a later authorised turn (TODO T9-21). U2 (G7): Q4's grade is (a) g1's class, in the kernel. U1: T9-02 is taken up (b) later, after reading resumes. U3: granularity and batching are (b) kept unfixed; results stay scoped to (A, Req). U4: the rule version (E1) is (d) deferred until §9.5 C1 (a) has a reference definition."; and the Principal's ruling of 2026-11-02 on T9-02, verbatim: "Retype T9-02's deliverable to the entry kinds and their fields plus a derivation for each item attribute; this decides neither F13 nor F37, and the rows §9.20.7 marks Open stay open." Against this text, say for each finding about them what the record decides, what the copy treats as decided beyond it, and what therefore needs a question to the Principal (state it in one sentence, with the options), rather than an edit.

## Additional rules (the Principal, 2026-11-02 and 2026-11-03; beyond the fixed template)
- **Files you may copy to scratch:** none. Run every tool directly on the frozen copy with `--root <copy>`; write tool outputs only to your own scratch directory, named in your dispatch.
- **No globs:** every search, listing or read names its files one by one by full path; no wildcard (`*`, `?`, `[...]`), no recursive search (`grep -r`, `find`), and no directory listing over corpus or book folders. A file you may not open, you may not search either.
- **The allowed list** is `/home/d0f38614-c3a6-41d3-9952-7f59ad699b2d/roshyara/personal/nrna1/docs/knowledgeos/knowledgeos-s-series-theory/corpus-access/CORPUS-ALLOWED.tsv`. Locate sources only through it.

## Rules (the Principal)
- A finding is not resolved because code or tests pass. State what the evidence establishes and what remains unproven.
- Keep verified facts, reported results, interpretations and recommendations apart.
- Edit nothing in the repository. No corpus or book text to any outside service. Do not reproduce any private person's name or any outside author's name.

## Report
- Tables: findings (ID(s) · verdict · severity · chapter · evidence · correction); R3 (correction · inbound line · disposition); conflicts (pair · resolution · reason); rotated-chapter defects; coverage gaps.
- A summary of at most 10 lines, and one line on process independence.
- End with every file you opened and every command and search you ran.
- Deliver it once, beginning "CONSOLIDATION S0353–S0377: INTEGRATION REPORT".
