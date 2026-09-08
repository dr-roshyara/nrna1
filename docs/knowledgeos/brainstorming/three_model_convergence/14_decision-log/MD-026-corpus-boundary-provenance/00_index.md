# MD-026 — Corpus Boundary, Provenance, and Admissibility Adjudication

**Status: COMPLETE.** Executed under separate, explicit authorization (2026-09-08). **Not Stage 07.
Not Phase 5O. Does not admit any external directory into the Model B evidence base — establishes
evidence for that future decision only.** P-series not consulted.

## Central question

Was `docs/knowledgeos/research/` genuinely omitted from the Phase-0 corpus boundary by accident, or is
it a genuinely separate research context that was never meant to be inside it?

## Answer, stated up front (full evidence in `03`–`06`)

**Neither cleanly.** Two independent, decisive pieces of evidence converge on **Outcome C (separate
research context)**, not Outcome A (incorrectly omitted):

1. **MD-010/MD-011 (2026-09-01) defined the corpus boundary by directory location**
   (`docs/knowledgeos/brainstorming/` only), a full **5 days before** `docs/knowledgeos/research/` and
   the rest of the brainstorming corpus were ever checked into this git repository (2026-09-06,
   confirmed by direct `git log` inspection). The boundary decision could not have "omitted" a
   directory that, from the repository's own point of view, did not yet exist to omit.
2. **The single commit that checked in all of this material (2026-09-06) itself linguistically
   distinguishes "the brainstorming corpus" from "research lanes"** — its own subject line reads
   *"check in the brainstorming corpus **and** research lanes,"* and its own body separately lists
   `research/theory-v1.1-simulation/` and `research/kernel-reduction/` alongside, but not folded into,
   the enumerated `brainstorming/` subdirectories. **The person who archived this material already
   treated `research/` as adjacent-but-distinct from the corpus MD-010/MD-011 bounded.**

A third, independently-discovered finding **strengthens the negative `ConflictRecord` result from
MD-025**, rather than weakening it: a promising-looking `ConflictRecord` elaboration was found in yet
another external directory (`docs/knowledgeos/reviews/kernel/`), but that same reviewing lane's own
internal self-audit (`S2-R-F028`) explicitly disqualifies it as a **"provenance loop"** — its own
earlier adjudication-track reasoning, saved as a document and mistaken for independent corpus support —
and refuses to count it as evidence. This study applies the same refusal.

## Frozen inputs

`00_control/protocol.md`; MD-010, MD-011 (read in full, verbatim, this study); complete MD-023, MD-024,
MD-025; Stage 06; `02_model-a_gita/`, `03_model-b_mathematical/`, `04_model-c_kernel-ddd/`,
`05_cross-model/`, Phase 6. Phase 5A–5N, the handover, MD-022 are an external frozen boundary, not
touched (one incidental "HPA" mention was found while searching `reviews/kernel/` for `ConflictRecord`
— noted only insofar as relevant to that search, not used to reopen K-1/K-2 governance). The P-series
was not consulted.

## Artifact map

| File | Contents |
|---|---|
| `00_index.md` | This file. |
| `01_control-protocol-and-historical-boundary.md` | MD-010/MD-011 read in full, verbatim quotes. |
| `02_population-and-search-frame.md` | The census scope for this study. |
| `03_kernel-reduction-provenance.md` | Git check-in evidence; the commit-message distinction. |
| `04_operator-contract-temporal-audit.md` | M0030's citation re-examined; the internal supersession chain. |
| `05_theory-v11-simulation-provenance.md` | Same analysis for `S^epi`'s external typing. |
| `06_excluded-tree-conflictrecord-theta-census.md` | The `reviews/kernel/` finding and its self-disqualification. |
| `07_ddd-boundary-analysis.md` | Bounded-context relationship between `brainstorming/`, `research/`, `reviews/`. |
| `08_cross-source-and-temporal-conflict-register.md` | None found beyond the provenance-loop case. |
| `09_provenance-ledger.md` | Full source-to-claim traceability. |
| `10-admissibility-matrix.md` | The required master matrix. |
| `11-adversarial-falsification.md` | Falsifiers for every finding. |
| `12-resolution-status.md` | The 12 required decision-tree questions. |
| `13-verification-and-completion-report.md` | Verification suite; final report (ESTABLISHED / NOT ESTABLISHED / NEXT AUTHORIZED DECISION). |

## What this study does NOT do

Does not admit `docs/knowledgeos/research/` or `reviews/` into the Model B evidence base. Does not
reclassify any file. Does not perform composition testing or retest MD-024's pairs. Does not open Stage
07. Does not modify any frozen artifact. Does not reopen K-1/K-2 governance.
