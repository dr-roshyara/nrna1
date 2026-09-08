# Phase 5A — C1/C2 Classification-Boundary Audit

**Status: IN PROGRESS.** Executed under the user's explicit authorization of Phase 5A only
(2026-09-07), issued after Phase 4's formal acceptance and the separate Phase-5 planning-analysis
document (`.claude/plans/purring-tinkering-graham.md`). **Phase 5B, 5C, any four-model work, and any
reclassification remain unauthorized and untouched.**

## Directory-placement note (a judgment call, disclosed)

protocol.md's own `NN_*` stage-gate numbering (`02` Model A → `03` Model B → `04` Model C1/C2 → `05`
cross-model) has no reserved slot for a classification-boundary *audit* — this is not a model
reconstruction stage. Rather than invent a new top-level numbered stage (which would imply Phase 5A
sits in the same sequence as the model-reconstruction phases), this audit is placed under
`14_decision-log/MD-021-phase-5a-classification-boundary-audit/` — adjacent to the decision log that
governs it, since an audit of the classification *process* is itself a governance activity, not a
fifth model reconstruction. This placement is a disclosed judgment call, not dictated by protocol.md;
a future phase may relocate it if governance determines a better home.

## Purpose

Determine, **descriptively and without reclassifying anything**, how reliably and consistently the
`engineering_knowledgeos`/`epistemic_knowledgeos` boundary was applied across the corpus — testing,
not assuming, the working hypothesis: *is the observed C1/C2 distinction an actual model/context
distinction, or primarily a historical/classification distinction?*

## Binding principles (restated from the authorization, honored throughout)

The current C1/C2 classification is an object of audit, not ground truth. C2's n=1 is never treated
as representative of an epistemic model. The 8 C2-adjacent candidates Phase 4 found are not promoted
into C2. No classification is changed anywhere in this phase. No directory membership is used as
model membership. No C1↔C2 relationship is adjudicated. No Kernel equivalence is established. No
A/B/Phase-3 cross-model adjudication occurs. No unified theory or canonical Kernel is created. No
implementation occurs.

## The three mandatory levels, maintained throughout every artifact in this phase

1. **Existing classification** — the `initial_primary`/`model.primary` value a file already carries,
   quoted verbatim, never altered.
2. **Machine-observable corpus fact** — a value already recorded in a per-file YAML field
   (`kernel_content`, `mathematical_content`, `gita_content`, `date`, `tier2_triggered`, path) or a
   mechanical text-pattern match over already-extracted structured fields — requires no new
   interpretation, and is tabulated as a **census** across the complete population wherever the
   underlying field exists corpus-wide.
3. **Independent descriptive content observation** — a judgment this phase makes, on a **pre-defined
   sample only**, about whether a file's actual content matches protocol.md's own C1/C2 criteria.
   **This level never silently becomes a reclassification** — any apparent mismatch between level 1
   and level 3 is recorded as an audit finding, with the file's original classification stated
   alongside it, not changed.

## Methodology — census vs. sample, per the authorization's statistical guardrail

**Census (complete population, no sampling)**: every cross-tabulation of classification against a
machine-observable field (§ `01_corpus-wide-census.md`) covers the **complete governed population** —
all 1,185 `PRIMARY`-tier main-corpus rows plus all 401 math-lane rows (1,586 rows total) — not a
sample. A text-pattern census for Kernel-definition markers likewise covers the complete 1,185-row
main-corpus population.

**Sample (only where content-level judgment is genuinely required), defined before inspection**: two
samples were used, each with its frame/strata/unit/selection procedure fixed in advance and disclosed
in `02_kernel-candidate-typing-and-completeness.md` / `03_boundary-observations-and-open-questions.md`
— (a) a **census-complete stratum** of 19 files (the full population matching a specific text-pattern
search outside the already-examined C1/C2/Model-A populations — small enough to read in full, so no
sub-sampling was needed within it); (b) a **systematic sample of 10** from the remaining 292-file
`meta_research` ∩ `kernel_content=true` population, selected by fixed-interval systematic sampling
(every 29th file starting at a fixed offset) — never a convenience sample, and never reported as if it
covered the full 292-file population.

## Artifact map

| File | Contents |
|---|---|
| `00_index.md` | This file. |
| `01_corpus-wide-census.md` | Complete-population cross-tabulations: classification × `kernel_content`/`mathematical_content`/`gita_content`; classification × date-bucket (pre/post MD-006's 2026-09-01); a schema-consistency finding in the `kernel_content` field itself. |
| `02_kernel-candidate-typing-and-completeness.md` | Object-typing of Phase 4's 8 C1 Kernel candidates by category; the corpus-wide Kernel-definition-marker census; the census-complete 19-file stratum's findings, including a major previously-uncounted sub-thread. |
| `03_boundary-observations-and-open-questions.md` | The defined 10-file systematic sample's findings; classification inconsistencies and ambiguities found; a data-completeness observation bearing on (but not modifying) Model A's own evidence population; open questions. |
| `04_verification-and-completion-report.md` | Full verification suite results; the completion report. |

## What this phase does NOT do

Does not reclassify any file. Does not expand Model C2's evidence population. Does not adjudicate the
C1↔C2 relationship. Does not establish any Kernel equivalence. Does not perform cross-model
adjudication against Model A or Model B. Does not modify `02_model-a_gita/`, `03_model-b_mathematical/`,
`05_cross-model/`, `04_model-c_kernel-ddd/`, or `classification-register.tsv`. Does not begin Phase 5B,
5C, or any four-model work.

## Current phase status

**COMPLETE.** Full verification suite passed (see `04_verification-and-completion-report.md`); no
classification changed; no frozen artifact modified. All artifacts written:
`01_corpus-wide-census.md` (complete-population census: classification × content-flags, temporal
split, schema-drift finding); `02_kernel-candidate-typing-and-completeness.md` (the 8 C1 candidates
typed into 4 object categories; a corpus-wide 116-hit Kernel-definition-marker census; a
census-complete 19-file stratum revealing a previously-uncounted 237-file subdirectory,
`phase_measure_theory/knowledgeos_kernel/`, self-reporting 13 internally-tracked competing Kernel
tuples); `03_boundary-observations-and-open-questions.md` (a defined 10-file systematic sample
confirming the pattern recurs; a Gita-content-under-`meta_research` boundary observation; 4
classification inconsistencies; 5 open questions). The verification suite and completion report
follow next.
