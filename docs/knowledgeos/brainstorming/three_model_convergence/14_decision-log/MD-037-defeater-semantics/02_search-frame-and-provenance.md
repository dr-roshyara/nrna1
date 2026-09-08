# Search Frame and Provenance

## Population census (17 files, complete)

| File | Lines | `defeater`-family hits | Read depth |
|---|---:|---:|---|
| `00-INDEX.md` | 103 | 1 | full |
| `01-research-question.md` | 64 | 0 | full |
| `02-evidence-matrix.md` | 142 | 1 | full |
| `05-operator-capability-matrix.md` | 48 | 0 | full |
| `07-ablation-design.md` | 72 | 0 | full |
| `08-scenario-suite.md` | 37 | 0 | full |
| `09-simulation-design.md` | 55 | 0 | full |
| `10-ablation-results.md` | 131 | 0 | full |
| `11-pairwise-results.md` | 54 | 1 | full |
| `13-ddd-analysis.md` | 72 | 1 | full |
| `14-alternative-kernels.md` | 102 | 0 | full |
| `15-falsification.md` | 22 | 6 | full |
| `16-negative-results.md` | 91 | 0 | full |
| `17-open-questions.md` | 43 | 2 | full |
| `18-audit-response-and-protocol-audit.md` | 376 | 5 | full |
| `19-directive-adoption-and-research-restructure.md` | 226 | 2 | full |
| `FINAL-kernel-reduction-report.md` | 405 | 5 | full |

**Total: 2,043 lines, all read in full, no sampling.** Zero-hit files (`01`, `05`, `07`, `08`, `09`,
`10`, `14`, `16`) — full read, not merely grep-confirmed, per the user's own tightened cold-read
requirement; confirmed genuinely absent, not merely un-searched.

## Provenance (uniform across the series, already established, re-confirmed here)

`git log --diff-filter=A` for every file in this series returns the identical commit
(`70fee73c8bcce04b18606adb007fa45ab5297787`, 2026-09-06) already found for `03`/`04`/`06`/`12`.

## A directly source-stated provenance finding, new to this study

`18-audit-response-and-protocol-audit.md` §0 (a "provenance ledger" the document itself supplies,
quoted in full in `03`) states explicitly that **the same author-layer ("Execution — ... Claude Code
CLI") produced both the narrative directory and the executable directory**: *"Execution — simulator
design and implementation, all experiments, results, the 19 artifacts | Claude Code CLI |
`docs/knowledgeos/research/kernel-reduction/`, `research/kernel-reduction/`"* — both paths named
together, one author. **This is direct textual confirmation of what MD-031 could previously only
infer from mtime proximity and the absence of cross-citation.** MD-031's own classification
(`CONVERGENCE WITH COMMON-CAUSE PROVENANCE`) is not merely unchallenged by this — it is now
independently corroborated by the corpus's own explicit self-description, found in a file this
study is characterizing, not (yet) admitting.

## Also newly found: this series' own three-layer authorship structure

`18` §0 additionally names a Layer 1 (Protocol, authored by "ChatGPT" — the original research
prompt) and Layer 3 (Audit, also "ChatGPT" — an external adversarial review responding to the
execution). This means the whole kernel-reduction document series is itself the record of a
multi-party AI collaboration (prompt-author, executor, auditor), not a single author's unreviewed
output — relevant context for weighing the series' own internal self-corrections (§1 of `18`, "seven
corrections ACCEPTED") as a real quality-control process, not merely self-report.
