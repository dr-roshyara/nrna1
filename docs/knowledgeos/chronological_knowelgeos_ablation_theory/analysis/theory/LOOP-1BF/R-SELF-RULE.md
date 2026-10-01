# R-SELF rule (ADOPTED by human act H3, 2026-10-01; applies to captures designed from now on; earlier sealed packages are not re-run)

**Rule** (the text recommended by the Protocol A output review, `LOOP-1BC/OUTPUT-REVIEW/REVIEW.md` l.45, applied as adopted):
- Before classification, any changed path under a declared programme-artefact root (listed exhaustively in the versioned `SELF-ROOTS.txt`) is assigned path class **P-SELF**.
- Hits in P-SELF paths are recorded but ignored by the classifier.
- A commit whose *only* hits are in P-SELF paths is classed **SELF-ARTEFACT**. It is counted separately and never contributes to any other class, nor to any earliest-trace or overlap field.
- **Channel captures (Protocol B type):** P-SELF paths are never candidate channels, and they are excluded from E and N.

**Root list, v1:** `docs/knowledgeos/` plus the two F/S-series-only TODO files.

**Deviation from the decision option's wording (disclosed):** the AskUserQuestion option text said to exclude `.claude/` and `claude/`. They are **not** excluded wholesale, because both hold **corpus sources** (e.g. `.claude/sessions/2026-08-04.md`, `claude/plans/swirling-jingling-blossom.md`), and a blanket exclusion would remove evidence.

**Residual limitation:** a mixed file such as a shared session log (`.claude/sessions/2026-10-01.md`) cannot be split by path. Such files are flagged per capture, not excluded. **The human may widen `SELF-ROOTS.txt` by a further decision.**
