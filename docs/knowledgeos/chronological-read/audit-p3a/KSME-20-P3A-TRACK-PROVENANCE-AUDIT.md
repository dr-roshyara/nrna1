---
task: KSME-20 (P3a Reliability Diagnosis, per user commission)
scope: corpus-wide chronological-read pipeline; cross-referenced against this session's Track-A/Track-B/Lane-B protocol
derived_from: [03-CONTRIBUTIONS.jsonl, 11-OBJECT-INDEX.jsonl, 11-UNRESOLVED-CANDIDATES.jsonl,
  31-RECONCILIATION-PAIRS.jsonl, validate_roadmap.py, direct computation this pass]
---

# KSME-20 — P3a Track-Provenance Audit

## 1. Method

Chronological-read has no concept of this session's Track-A/Track-B/Lane-B/firewall taxonomy at all — both
`phase_measure_theory/` and `verification/gap-discovery/` are fully included and mixed freely into the same
object index and reconciliation pairs. Before treating any of chronological-read's output as canonical
Track-A evidence, every object and pair needs a source-directory tag. Computed directly (mechanical join,
not requiring a fork): for each of 27,906 contributions, resolve `path` to a track bucket; for each of 2,515
labels (1,895 object-index + 620 unresolved-PROPOSAL candidates — confirmed via direct check that
reconciliation pairs reference BOTH populations, 780 + 598 of 1,377 distinct labels, 0 unmatched), aggregate
its contributions' bucket counts; for each of 1,793 reconciliation pairs, derive a and b's dominant bucket.

Classifier (directory-prefix rules, explicit about uncertainty rather than forcing every path into a track):

| Bucket | Path prefix |
|---|---|
| TRACK-A-PHASE-MEASURE | `docs/knowledgeos/brainstorming/phase_measure_theory/` |
| TRACK-A-VERIFICATION-CLUSTER-UNCONFIRMED-MD043 | `docs/knowledgeos/brainstorming/verification/` (excl. gap-discovery) — **named UNCONFIRMED because this pass did not verify MD-043 admits every subcluster (prompts/spec/findings/reports/plan/witnesses/independent/canonical-construction/step-280/281/282/consolidation/handoff), only that the session's Track-A description mentions "MD-043-admitted verification/ clusters" generically** |
| TRACK-B-GAP-DISCOVERY | `docs/knowledgeos/brainstorming/verification/gap-discovery/` |
| LANE-B-SIM | `research/knowledgeos-sim/` |
| FIREWALLED-THREE-MODEL-CONVERGENCE | `docs/knowledgeos/brainstorming/three_model_convergence/` |
| FIREWALLED-THEORY-RESEARCH | `docs/knowledgeos/brainstorming/knowledgeos_theory_research/` |
| SELF-REFERENTIAL-PIPELINE-OUTPUT | `docs/knowledgeos/chronological-read/` |
| **UNCLASSIFIED-NEEDS-DECISION** | everything else — `kernel/`, `mathematical_ideas_that_can_be_implemented/`, `docs/knowledgeos/research/`, `docs/knowledgeos/reviews/`, `docs/knowledgeos/architecture/`, `theory-extraction/`, `governance/`, `backlog/`, `developer_guide/`, `how_far_we_are/`, `what_is_knowlegeos_theory/`, `corpus/`, `classification/`, `falsification/`, `synthesis/`, `where_we_are_now/`, `_misc/`, and loose top-level files |

## 2. Object-level results

- 2,464 of 2,515 labels have ≥1 matching contribution (33 label-mismatch/alias residue, ~1.3%, not chased
  this pass — likely alias/normalization drift between `working_label` and `labels[]`).
- **216-242 of 2,464 objects (≈9-10%) span more than one track bucket** ("MIXED") — a single named object's
  own evidence is not always single-track.
- Dominant-bucket distribution across all 2,464 objects: **UNCLASSIFIED-NEEDS-DECISION 1,263 (51.3%)**,
  TRACK-A-PHASE-MEASURE 929 (37.7%), TRACK-A-VERIFICATION-CLUSTER-UNCONFIRMED-MD043 150 (6.1%),
  TRACK-B-GAP-DISCOVERY 122 (5.0%).

**The majority of chronological-read's own object universe sits outside this session's declared
Track-A/Track-B/Lane-B scope entirely.** This is a real scope mismatch between "the chronological-read
pipeline's admissible corpus" and "this session's Track Separation Protocol's admissible corpus," not
something to resolve unilaterally here.

## 3. Pair-level results

| | All 1,793 pairs | High-stakes 158 (SAME+REPLACEMENT+DERIVED-FROM) |
|---|--:|--:|
| Cross-dominant-track | 325 (18.1%) | 38 (24.1%) |
| Involves a MIXED object | 564 (31.5%) | 65 (41.1%) |
| Involves an UNCLASSIFIED-dominant object | 1,054 (58.8%) | 97 (61.4%) |
| Missing profile | 38 (2.1%) | 2 |

**Cross-reference against the disputed 158-pair slice (the user's specific hypothesis)**: cross-track and
mixed-object involvement are both **moderately elevated** in the disputed slice relative to the corpus-wide
baseline (cross-track: 24.1% vs 18.1%, a ~1.3x rate; mixed: 41.1% vs 31.5%, a ~1.3x rate), while
unclassified-involvement is **not meaningfully elevated** (61.4% vs 58.8%, corpus-wide baseline already high).

**Honest interpretation**: track/scope mismatch is a real, measurable, secondary contributing factor to the
disputed-slice disagreement rate — it is NOT the dominant cause. The dominant cause, per
`KSME-20-P3A-QUALITY-REPRODUCTION.md` and `KSME-20-P3A-ERROR-TAXONOMY.md`, remains the `row_brief()`
evidence-stripping implementation defect and the ~7-10% genuine ontology-coverage gap. This partially, not
fully, confirms the "context loss" hypothesis in its track-crossing form — the more consequential context
loss is the *evidence-field-stripping* bug, which affects same-track and cross-track pairs alike.

## 4. Cross-dominant-track pair breakdown (all 1,793, unordered bucket pairs)

| Bucket pair | Count |
|---|--:|
| TRACK-A-PHASE-MEASURE ↔ UNCLASSIFIED-NEEDS-DECISION | 145 |
| TRACK-A-PHASE-MEASURE ↔ TRACK-A-VERIFICATION-CLUSTER-UNCONFIRMED-MD043 | 99 |
| TRACK-B-GAP-DISCOVERY ↔ UNCLASSIFIED-NEEDS-DECISION | 25 |
| TRACK-A-VERIFICATION-CLUSTER-UNCONFIRMED-MD043 ↔ UNCLASSIFIED-NEEDS-DECISION | 22 |
| TRACK-A-PHASE-MEASURE ↔ TRACK-B-GAP-DISCOVERY | 19 |
| TRACK-A-VERIFICATION-CLUSTER-UNCONFIRMED-MD043 ↔ TRACK-B-GAP-DISCOVERY | 15 |

Only 19 pairs directly cross Track-A-PHASE-MEASURE ↔ Track-B-GAP-DISCOVERY, this session's own most
carefully firewalled boundary — a small, nameable set that should be flagged for explicit review before
any future pass treats their verdicts as informing Track-A conclusions.

## 5. Recommendation (not a resolution — this is a naming/tagging task, not a judgment call)

Add `source_track` (from the classifier above) and `source_track_mixed` (bool) as first-class fields on
every object-index/unresolved-candidate entry and every reconciliation pair, computed mechanically as done
here — this requires no new corpus reading, only a script change to `derive_families.py`/
`derive_reconciliation*.py` to emit the field going forward, and a one-time backfill pass (the join already
run for this audit) for existing data. Until that is adopted, any future Track-A-scoped KSME work drawing on
chronological-read's index must re-derive this tagging itself rather than assume chronological-read's output
is pre-filtered to Track-A.
