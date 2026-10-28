# Consolidation S0182–S0200: access note

*2026-10-28. The Professor. Facts from the reviewers' transcripts (tool calls audited by script) and their own file lists.*

## Facts
- **Reviewer A (Opus)** ran `cat FREEZE.md README-PROFESSOR.md` in this folder early in its review, and so read `README-PROFESSOR.md`. That file states that the copy "may differ from the working book in some places; that is the planted-gap test". A disclosed this in its file list ("opened: it mentions a planted-gap test; it is not in item 4's list; I did not use it"). A also ran one `grep` over `00-access/ACCESS-RECORD.md` (for "allowed").
- **Reviewer B (Sonnet)** saw the names `FREEZE.md` and `README-PROFESSOR.md` in a `wc -c` listing and did not open them (B's list; the audit finds no read of either).
- **Neither reviewer** opened a file of REVIEWER-NOTE item 4 (working chapters, map, register, outline, BASELINE-REVISIONS, manuscript git history). The audit's matches on chapter paths are inside the copy or inside regular expressions.
- **Cause (the Professor's):** the brief sent reviewers to this folder for `REVIEWER-NOTE.md`, `BRIEF.md` and `SCOPE.md` without listing which files in it they may open. `README-PROFESSOR.md` lies in the same folder. The Professor did not tell any reviewer that a test is running.

## Consequence (for the Principal and the Rule Reviewer, not decided here)
- Reviewer A knew a planted-gap test exists before reviewing. Whether A's report counts for the score, or counts separately, is for the Rule Reviewer and the Principal.
- **For the integration review:** the commission names every file it may open in this folder (`REPORT-A.md`, `REPORT-B.md`, `REVIEWER-NOTE.md`, `SCOPE.md`, `INTEGRATION-COMMISSION.md`) and forbids the rest.
