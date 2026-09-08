# 04_model-c_kernel-ddd — Phase 4: Model C1/C2 Independent Reconstruction

**Status: IN PROGRESS.** This is the Phase 4 output of **MD-021**'s phased plan
(`14_decision-log/model-boundary-decisions.md`), executed under an explicit, separately-scoped
authorization (2026-09-07, quoted in full in `.claude/plans/purring-tinkering-graham.md` under
"Plan: Phase 4 — Model C1/C2 Independent Reconstruction"), issued only after Model A (Phase 1),
Model B (Phase 2), and the cross-model adjudication (Phase 3) were each independently completed,
audited, and formally accepted. The user's authorization carried one additional binding guardrail:
*resemblance to the protocol's C2 definition must never by itself expand Model-C2 evidence
membership* — honored throughout this document set. **Phase 5+ (final classification, gap-analysis,
formalization, kernel unification, dynamics, computational theory, validation, canonical theory) are
NOT authorized and are not touched by this work.**

## Directory-numbering note (this is the one case where phase and directory numbers align)

Unlike Phase 3 (which targeted `05_cross-model/`, not `04_model-c_kernel-ddd/`), MD-021's Phase 4
correctly targets `04_model-c_kernel-ddd/` per protocol.md's own stage-gate sequence (`02` Model A →
`03` Model B → `04` Model C1/C2 → `05` cross-model). Confirmed empty before this phase began.

## Purpose

Independently reconstruct **Model C1** (`engineering_knowledgeos`) and investigate **Model C2**
(`epistemic_knowledgeos`)'s actual reconstructable evidence population, per protocol.md's own
canonical definitions (lines 44–60) and the prior governance history already establishing the split
(MD-006, MD-007, MD-009–MD-011) — none of which this phase restates as new, only inherits and applies.

## The central finding, stated up front, not buried

**Model C2's evidence population, under current classification and across both corpora, is exactly
one file** (seq 2330). This is not a data-quality defect to be corrected here — the Term-Collision
Rule (protocol.md) explicitly forbids classifying a document C2 merely because it discusses
"Kernel"/"KnowledgeOS"/"Knowledge Space." A dedicated, evidence-driven boundary investigation (per the
authorization's guardrail — see `04_boundary-observations.md`) examined the specific corpus region
surrounding the sole C2 file and found substantial epistemic-Kernel-adjacent content classified
`engineering_knowledgeos` or `meta_research` instead — **reported as bounded observations with exact
provenance, never reclassified.** No file's classification was changed anywhere in this phase.

## Scope: evidence population, independently re-derived (not copied from the approved plan)

Re-verified at execution time by joining `classification-register.tsv`'s `initial_primary`/
`initial_secondary` against `reading-manifest.tsv`'s `corpus_tier` (main corpus, `PRIMARY` tier only)
and the math lane's per-file-mathematical `model.primary`/`secondary` fields:

| | Main corpus (`PRIMARY` tier) | Math lane (401 files) | Total |
|---|---:|---:|---:|
| C1 (`engineering_knowledgeos`/`c1`) primary | 719 | 13 | **732** |
| C1 secondary (different primary) | 2 | 37 | **39** |
| C2 (`epistemic_knowledgeos`/`c2`) primary | 1 | 0 | **1** |
| C2 secondary (different primary) | 0 | 0 | **0** |

The three `kernel_ddd`-tagged rows (seq 0001, 0005, 0009) — the literal evidentiary origin of the
C1/C2 split (MD-006) — are `OUT_OF_SCOPE_ROOT` per MD-011's own prior correction and are **not**
primary evidence for this phase; cited only as historical provenance.

## Methodology

1. **Sequence-ordered digest-then-read**, mirroring Phase 1/2's proven approach, scaled for a
   population roughly 4–5× larger than Model B's: a compact per-file digest (title/date/importance/
   `tier2_triggered`/introduces/defines/hypotheses/contradicts/refines/open-questions, long fields
   truncated) over all 732 C1 candidates, read sequentially. Given the corpus's own two-tier native
   discipline (`MD-008`: concise-by-default, Tier 2 on genuine theoretical significance), and given
   this population's own tier2-triggered rate (600/719 main-corpus rows, ≈83%) is far higher than
   anticipated, **evidentiary clusters are organized at a coarser grain than Model A/B's own
   file-by-file registers** — representative depth per cluster and per major named formalism, not an
   entry for every one of 732 files. This is a deliberate, disclosed scaling decision, not an omission.
2. **The sole C2 candidate (seq 2330) was read in full from its raw per-file record**, not digested —
   its population is far too small to compress, and it is the single most consequential file in this
   entire phase.
3. **The C2-population investigation** (authorization guardrail) targeted the specific corpus region
   the sole C2 file itself cites as its own lineage (`docs/knowledgeos/brainstorming/kernel/`, seq
   ≈2296–2354) — a bounded, citation-driven investigation, not an unbounded search over all 732 C1
   files or the wider corpus. Findings recorded in `04_boundary-observations.md` as adjacent material
   with original classification preserved throughout.
4. **Vocabulary reused, not invented**: protocol.md §3's evidence-status tags, the Maturity scale,
   MD-017's six-way relationship taxonomy, and the four(+one)-state kernel-candidate discipline
   Model B's own authorization established (ESTABLISHED / TESTED→REJECTED / PROPOSED→UNTESTED /
   UNRESOLVED / a named fifth "tested-survives-not-established" state).
5. **No importing Model-A/Model-B/Phase-3 conclusions as evidence** — every claim in this document set
   traces to a C1/C2 source `seq`, never to `02_model-a_gita/`, `03_model-b_mathematical/`, or
   `05_cross-model/`.
6. **C1↔C2 relationship work stays within this phase** (MD-007's own three-way framework — evolution /
   independence / gap-motivated transition — applied to the fuller evidence) and is explicitly not
   cross-model adjudication against Model A/B (that remains a later, separately-authorized phase).

## Artifact map

| File | Contents |
|---|---|
| `00_index.md` | This file. |
| `01_evidence-base.md` | The 732 C1 candidates organized into evidentiary clusters, sequence-ordered; the sole C2 candidate read in full; traceability tables. |
| `02_concept-register.md` | Named C1 concepts/formalisms and the sole C2 concept, kept in visibly separate sections; the required kernel-candidate table. |
| `03_contradictions-and-open-questions.md` | C1-internal and (where population permits) C2-internal contradictions/UEs/OQs; the dedicated C1↔C2 relationship section (MD-007's three-way framework, tested against the fuller population). |
| `04_boundary-observations.md` | The 39 secondary-tagged boundary rows; the 3 historical `kernel_ddd`/`OUT_OF_SCOPE_ROOT` rows; the C2-population investigation and its bounded findings; data-quality anomalies found and corrected in this phase's own understanding (not in the corpus). |

## What this phase does NOT do

- Does not assign `final_primary`/`final_secondary`/`classification_change`/`reason_for_change` for
  any register row — every row remains `PENDING_GLOBAL_RECLASS`/`PENDING`.
- Does not reclassify any file, including every C2-adjacent candidate the boundary investigation
  names — original classification is preserved throughout, per the authorization's explicit guardrail.
- Does not compare Model C1/C2 against Model A, Model B, or Phase 3's findings, or extend Phase 3's
  adjudication to include C1/C2 — that is later, separately-authorized work.
- Does not assume a kernel exists, that any candidate kernel is unique, or that C1 evolved into C2 —
  each is investigated from C1/C2's own evidence alone.
- Does not touch `02_model-a_gita/`, `03_model-b_mathematical/`, `05_cross-model/`,
  `classification-register.tsv`, any per-file source record, or the mathematical manifests.

## Current phase status

**Complete, pending verification and completion report.** All five artifacts are written:
`00_index.md` (this file), `01_evidence-base.md` (732 C1 candidates in 8 evidentiary clusters, the
sole C2 candidate read in full), `02_concept-register.md` (12 named C1 concepts §A–§L, the sole C2
concept §M with full raw-source treatment, a 12-row kernel-candidate table §N covering the C1
population's own eight-member Kernel-definition proliferation), `03_contradictions-and-open-questions.md`
(4 contradictions, 3 unresolved equivalences, 8 open questions, plus the dedicated C1↔C2 relationship
section — MD-007's own watch-status finding tested against the fuller population and found falsified,
verdict UNRESOLVED for a more specific reason than insufficient evidence), `04_boundary-observations.md`
(39 secondary-tagged rows, 3 historical `kernel_ddd` rows, the bounded C2-population investigation with
8 named adjacent rows, 3 rows with no per-file record, and one self-correction of this reconstruction's
own earlier false-positive anomaly claim). The verification suite and the completion report follow
next.
