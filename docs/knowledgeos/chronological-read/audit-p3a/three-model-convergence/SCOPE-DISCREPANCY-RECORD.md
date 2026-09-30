# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# Scope Discrepancy Record: 2,320 vs 2,926

**Purpose:** time-boxed investigation of the corpus-scope discrepancy between the prior
`three_model_convergence` program's admissible file count (2,320) and this session's own P1
roadmap (2,926 lines). **Date:** 2026-09-20 (artifact-creation date; corrected from an earlier mislabel of 2026-09-21 — not a source-chronology date). **Status:** EXPERIMENTAL. **Authoritative:** NO.
**Time-box:** ~15 minutes of direct verification, per the task's explicit instruction not to let
this block the rest of the research program.

## Statement types used throughout (per the task's provenance discipline)

- **[SOURCE FACT]** — directly read from a file.
- **[ANALYTICAL INFERENCE]** — this investigation's own reasoning from source facts.

## Facts gathered

1. **[SOURCE FACT]** `three_model_convergence/00_control/corpus-validation-report.md` (produced
   2026-09-01) states its authoritative reading order is
   `docs/knowledgeos/brainstorming/files_to_read_one_by_one.log`, an `ls -la`-format log with
   **2,320 lines / 2,320 parsed entries / 0 duplicates / 0 missing files**.
2. **[SOURCE FACT]** That exact file, `docs/knowledgeos/brainstorming/files_to_read_one_by_one.log`
   (undated name), **does not exist in the current working tree** — confirmed by direct `ls`.
3. **[SOURCE FACT]** Two *dated* snapshots of the same roadmap concept exist on disk today:
   `20260902-185001_files-to-read-one-by-one.log.md` (**1,228 lines**) and
   `20260909-185001_files-to-read-one-by-one.log.md` (**2,926 lines** — the file this session's own
   P1 pipeline uses).
4. **[SOURCE FACT]** `three_model_convergence/00_control/classification-register.tsv` (the program's
   own working classification of every file it processed) has **2,377 rows (2,376 data rows)**.
5. **[SOURCE FACT]** A path-set comparison of the two dated snapshots shows **1,227 of 1,228** paths
   in the 09-02 snapshot also appear verbatim in the 09-09 snapshot (99.9% forward-compatible; the
   1 exception is consistent with the kind of single path-transcription fix already documented
   elsewhere in this session's own P1c work, not investigated further here as out of scope).

## Analysis

**[ANALYTICAL INFERENCE]** The 09-02 (1,228) and 09-09 (2,926) snapshots demonstrate that the
roadmap grew **monotonically** over at least one seven-day window, with the earlier snapshot's
contents surviving almost unchanged into the later one (addition, not replacement or
re-filtering). This is direct, positive evidence for the "scope change / additional files over
time" explanation named as a candidate in the task's own list — not a competing or invented
explanation.

**[ANALYTICAL INFERENCE]** The prior program's own figure (2,320, dated as of a 2026-09-01 report)
sits numerically between the two dated snapshots we can inspect (1,228 on 09-02, 2,926 on 09-09) —
but *earlier* than the 09-02 snapshot chronologically (report dated 09-01, snapshot dated 09-02),
which is at first glance inconsistent (a 09-01 count of 2,320 exceeding a 09-02 count of 1,228).
**This inconsistency is not resolved by the evidence gathered in this time-boxed pass.** Two
non-exclusive candidate explanations, neither confirmed:
  - The 09-02 snapshot may represent a *filtered or re-scoped* subset (e.g., a corpus reset,
    re-organization, or narrower re-definition of "the roadmap") rather than a strict continuation
    of the file the 2,320-count report used.
  - The corpus-validation-report's stated "2026-09-01" production date may not exactly match when
    `files_to_read_one_by_one.log` was last written (a report can be authored referencing a log that
    was updated shortly before or after).
3. **[SOURCE FACT]** The classification register's own 2,377 rows are within 57 of the reported
   2,320 — plausibly the same undated log plus a small number of later additions or non-content
   rows, but this was not further decomposed within the time-box.

## Disposition

**RESOLVED-CATEGORY: temporal corpus growth**, evidenced directly (the 09-02→09-09 snapshot
comparison). This explains, in principle, why *any* comparison between a `three_model_convergence`-
era snapshot and this session's 2,926-line roadmap would show a difference — the corpus was still
growing throughout the collection period, and neither program's snapshot was a final, frozen census
at the time it was taken.

**`UNRESOLVED-SCOPE`, precisely**: the exact file `files_to_read_one_by_one.log` (2,320 entries)
that `three_model_convergence` actually used no longer exists on disk in this working tree, so a
byte-for-byte diff against it is not possible within this time-box. The specific relationship
between 2,320 and 2,377 (classification-register rows) is likewise not fully decomposed.

## Consequence for the rest of this investigation

Per the task's explicit instruction, **this does not block anything else in this research pass.**
No correspondence or coverage claim elsewhere in this document set depends on precisely
reconciling 2,320 with 2,926 — every specific concept examined (Kernel, K_t/Δ_t, Zero, C1/C2
load-bearing concepts) is independently anchored to specific, identifiable source paths and
`02-FILES.jsonl`/roadmap entries, not to an aggregate corpus-size claim. Where a future pass makes
a corpus-*wide* statistical claim (e.g., "X% of the corpus shows Y"), it must first re-derive its
own population definition rather than borrow either count uncritically.
