# Provenance Ledger

| Claim | Evidence chain | Status |
|---|---|---|
| MD-010/MD-011 define the corpus boundary by directory location, dated 2026-09-01 | Direct read of both entries in `model-boundary-decisions.md` | `[DIRECT EVIDENCE]` |
| `docs/knowledgeos/research/` first tracked in git 2026-09-06 | `git log --diff-filter=A` on the directory and on M0030 | `[MACHINE-OBSERVABLE FACT]` |
| The check-in commit distinguishes "brainstorming corpus" from "research lanes" | Direct read of commit `70fee73c`'s own message | `[DIRECT EVIDENCE]` |
| `kernel-reduction/`'s own content is dated 2026-09-01 (same day as M0030) | Direct read of `00-INDEX.md`'s own "Experiment ID KR-2026-09-01" | `[DIRECT EVIDENCE]` (self-dating) |
| M0030 cites `research/kernel-reduction/` as its own output path | Direct grep + read of M0030's raw text, lines 1443/1488 | `[DIRECT EVIDENCE]` (already known from MD-025) |
| The directory is the actual execution output of M0030's protocol | Matching dates + matching trial counts (~150,000) + cited path | `[RECONSTRUCTED PROVENANCE]`, not stronger |
| No admissible B file cites `theory-v1.1-simulation/` by path | Targeted search of M0032/M0033/M0036 | `[MACHINE-OBSERVABLE FACT]` (absence within searched set) |
| `04-operator-contracts.md` is qualified, not superseded, by the directory's own later `§19` | Direct read of `00-INDEX.md` and `19`'s own index description | `[DIRECT EVIDENCE]` |
| `ConflictRecord` elaboration found in `reviews/kernel/` is self-disqualified as a provenance loop | Direct read of `S2-R-F028`'s own text | `[DIRECT EVIDENCE]` (the disqualification is itself source-stated, not this study's inference) |
| No `Θ` elaboration found anywhere in the 6 newly-searched sibling directories | Exhaustive `grep -rli`, `06` | `[MACHINE-OBSERVABLE FACT]` (absence within searched frame) |
