# Replication-Feasibility Scoping — PBDIGIT / WP / EPIC Families

**Date:** 2026-09-29. Cheap reconnaissance only. **Reviewed independently (`kos-theory-reviewer`)
before publication — verdict `PASS_WITH_LIMITATIONS`, 5 findings, all fixed below.** The version
this replaces existed only as a local, uncommitted file — this is a pre-publication revision, not a
forward erratum.

## Method

Direct checks: `.claude/runtime/workflow/` listing, `docs/publicdigit/backlog/` file counts,
session-log cross-reference, real commit-message samples per family
(`git log --all --grep=<pattern> -i`).

## Correction: PBDIGIT/EPIC and WP are two separate findings, not one family

**The prior version wrongly treated all three prefixes as one mechanism "confirmed below" —
nothing confirmed it, and the evidence points the other way**: 0 of 224 `WP-`-matching commit
subjects mention `PBDIGIT` (checked directly). `WP-` commits belong to a distinct track (`PB003`,
adjudication, `R-96`..`R-100` rulings) with its own artifacts. `EPIC` matches are themselves a mix
of `PBDIGIT-EPIC-nn` backlog files and separate `EPIC-*_ARB_*` records. Split below.

### PBDIGIT / EPIC (backlog-based)

| # | Question | Answer |
|---|---|---|
| 1 | Grant/authorization ledger? | **NO** — confirmed, `.claude/runtime/workflow/` holds 22 files, all `KOS-*.json` |
| 2 | Equivalent governance record? | **Thin, corrected from the prior overstated version**: of `docs/publicdigit/backlog/`'s 44 `PBDIGIT-nn` tickets + 6 `EPIC` files (exact counts, not "58" as a work-item count — the remaining 7–8 files are README/findings/discovery notes), only **11** carry a bold `**Status` line, only **7** say `IMPLEMENTED — NOT VERIFIED`, only **7** say `Gated by` — real, but far from population-wide |
| 3 | Work-item/ticket concept? | **YES**, real, narrative-markdown shape |
| 4 | Temporal mechanism? | **PARTIAL** — `Created` appears in 46 files, `Baseline` in only 11 |
| 5 | Session/execution record? | **YES, but narrow**: 11 session-log files mention `PBDIGIT-` — **spanning only 2026-08-06 to 2026-08-18**, while real `PBDIGIT-` commits run 2026-08-05 through 2026-09-29. The session-log corroboration mechanism does not currently cover most of this family's actual commit history |
| 6 | Provenance? | Same `INV-ATTR-1/2` caveat as KOS |
| 7 | Reproduce `M1`–`M4` as-is? | **NO** |
| 8 | Fundamental difference? | No JSON ledger; thin, partial status tracking; **not "evidently older"** — corrected: `PBDIGIT` commits (from 2026-08-05) run in parallel with the KOS work, not before it |

**Classification: `PARTIALLY_REPLICABLE`, weaker than previously stated** — the session-log
mechanism only covers a 2-week window of this family's much longer commit history.

### WP (a genuinely separate mechanism — new finding)

Real registries exist that the prior version missed entirely: `docs/implementation/PROGRAM_STATUS.md`
mentions `R-nn` rulings 16 times (13 unique IDs, e.g. "R-98 closes the WP-4 engineering commission"),
plus `docs/implementation/PKS_Phase_I_ARB_Rulings.md`, `docs/architecture/ARB_Decision_Record.md`,
and a real ARB certification report (`2026-08-05-pb003-arb-certification-report.md`). Checked
directly this round: `PROGRAM_STATUS.md`'s `R-nn` references are **prose-embedded, not a structured
table** — closer in shape to KOS's mechanism (c) (informal narrative record) than mechanism (a)
(JSON ledger). No dedicated `WP-*.md` plan files were found at the path initially guessed
(`docs/publicdigit/`) — not located this round, not asserted to exist elsewhere.

**Classification: `INSUFFICIENT_EVIDENCE`** — real governance artifacts exist (`PROGRAM_STATUS.md`,
ARB rulings) that were never checked in enough depth this cheap scoping pass to classify further.
This is the most promising unexplored thread, not a dead end — but classifying it properly is
already past the scope of a cheap reconnaissance check.

## Corrected interpretation — withdrawing the overclaim

**The prior version's claim that this "strengthens the mechanism-plurality finding" is withdrawn as
untested.** A missing JSON ledger shows the mechanism is *different*, not that it is a *coherent
separate mechanism* in the sense the KOS 3-mechanism finding used. The "record/substance distinction
generalizes" claim is similarly withdrawn as asserted-not-tested. What remains defensible: PBDIGIT/
EPIC and WP are two more real, distinct, unexplored governance surfaces in this repository, at least
one of which (WP, via `PROGRAM_STATUS.md`) has a real narrative-ruling record worth a dedicated,
non-cheap look — not run here.

## Status

`REPLICATION_UNATTEMPTED` for PBDIGIT/EPIC (scoped, `PARTIALLY_REPLICABLE`). `INSUFFICIENT_EVIDENCE`
for WP — its own scoping check was not completed to the same depth this round.

---

**Traceability:** `2026-09-29-KOS-EVIDENCE-PROPOSITION-MATRIX.md` (source of the replication-
availability correction that motivated this scoping) · `2026-09-29-KOS-EVIDENCE-DETERMINATION-FAILURE-MODES.md`
§3 (the 3-mechanism finding this was tested against, not confirmed to extend) ·
`docs/implementation/PROGRAM_STATUS.md`, `docs/implementation/PKS_Phase_I_ARB_Rulings.md`,
`docs/architecture/ARB_Decision_Record.md` (WP's candidate registries, found this round) ·
`docs/publicdigit/backlog/` (exact counts: 44 `PBDIGIT-nn` + 6 `EPIC` files) · `.claude/sessions/*.md`
(11 files, window 2026-08-06 to 2026-08-18).
