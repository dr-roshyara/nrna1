# Narrative Source Census

## Population

`docs/knowledgeos/research/kernel-reduction/` contains **21 files** (per `find -maxdepth 1 -type f`),
a numbered document series `00-INDEX.md` through `19-directive-adoption-and-research-restructure.md`
plus `FINAL-kernel-reduction-report.md` and one anomalous entry (below). Two files
(`03-capability-model.md`, `04-operator-contracts.md`) were admitted by MD-028-DQ-1. This study reads
one more (`06-composition-rules.md`) as its own primary source, per this study's own authorization —
characterization, not admission.

## Timestamps (mtime, weaker than Git — recorded per this study's own discipline, not treated as proof of authorship date)

19 of the 21 files carry a 2026-09-01 mtime, in near-continuous sequence (22:38:08 through 23:10:19);
two later files (`02-evidence-matrix.md`, `18-audit-response-and-protocol-audit.md`) carry a
2026-09-02 mtime, both at the identical second — a second, later editing pass. `04-operator-
contracts.md` (22:40:10.9229...) and `06-composition-rules.md` (22:40:10.9279...) are **five
milliseconds apart** — the tightest gap between any two files in this directory, far tighter than the
tens-of-seconds-to-minutes gaps typical elsewhere in the same sequence. Recorded here; interpreted in
`06_provenance-and-temporal-analysis.md`.

## Anomaly, noted not chased

One filename is not part of the numbered series: `Yes. I read the full attached document,` (mtime
2026-09-01 23:36:51) — apparently a chat response accidentally saved as a filename. Not read, not
used as evidence; flagged as a data-quality anomaly consistent with this whole research programme's
already-established messiness (per MD-026/027's own prior findings about this lane), not
investigated further — out of this study's own narrow scope.

## Git provenance (all 21 files, established once, applies uniformly)

`git log --diff-filter=A -- docs/knowledgeos/research/kernel-reduction/06-composition-rules.md`
returns exactly one commit: `70fee73c8bcce04b18606adb007fa45ab5297787`, 2026-09-06, the same bulk
check-in commit MD-026 already established for this entire directory (including the two files
MD-028-DQ-1 admitted). **`06-composition-rules.md` carries the identical Git-provenance status to the
two already-admitted files — not a weaker one.** This is a materially different provenance picture
than MD-030 found for the executable lane (zero Git history there).

## What was actually read for this study

`06-composition-rules.md` — full, cold read (`03`). `04-operator-contracts.md` — re-read for its own
explicit "§06" cross-reference (already known from MD-029, re-verified here with exact quote in
`03`). `00-INDEX.md`, `01-research-question.md`, `05-operator-capability-matrix.md`, and the rest of
the numbered series were **not** read for this study — their titles were noted from the directory
listing only, and none was found necessary to answer the central question. This is stated explicitly
per this study's own instruction not to expand scope silently.
