---
task: KSME-21 Phases 1-3 (Evidence Repair, Negative-Search Semantics, Affected-Set Sizing)
scope: corpus-wide chronological-read pipeline (P3a); no historical verdicts modified
derived_from: [scripts/derive_reconciliation.py, audit-p3a/v2/scripts/evidence_bundle_v2.py,
  02-FILES.jsonl, 03-CONTRIBUTIONS.jsonl, 31-RECONCILIATION-PAIRS.jsonl,
  audit-p3a/P3A-UNDEFINED-RELATIONSHIP-PROVENANCE.jsonl, direct computation this pass]
---

# KSME-21 — Evidence Repair Report (Phases 1-3)

No production file (`31-RECONCILIATION-PAIRS.jsonl`, `scripts/derive_reconciliation.py`) was modified this
pass. All work here is verification and report-generation, per the explicit instruction not to overwrite
historical evidence before a human decision on adoption.

## Phase 1 — Evidence-visibility repair: VERIFIED, not yet adopted

Read the actual production code directly (not inferred from the prior forks' description):

`scripts/derive_reconciliation.py:45-53` (`row_brief()`, production, currently in use):
```python
def row_brief(c):
    return {"source_id": c["source_id"], "anchor": c.get("anchor"), "types": c.get("types"),
            "statement": c.get("statement"), "type_signature": c.get("type_signature"),
            "explicit_date": c.get("explicit_date")}
```
Confirmed: no key exists for `dependencies`, `lineage_claims`, `invariants`, `assumptions`, or provenance —
this is a schema omission, not a sampling artifact.

`audit-p3a/v2/scripts/evidence_bundle_v2.py:17-35` (`row_bundle_v2()`, proposed, not adopted) explicitly
preserves all four fields plus `provenance`/`source_role`/`source_path` from a file-metadata join.

**Evidence-integrity test executed** (SOURCE ROW → v1 row_brief() vs. v2 row_bundle_v2() → assert field
preservation), run directly against all 27,906 real contribution rows, not a sample:

- **7,407 of 27,906 rows (26.5%) carry ≥1 non-empty value** in `dependencies`/`lineage_claims`/
  `invariants`/`assumptions`.
- **v1 `row_brief()` drops all four fields for 100% of these 7,407 rows** (confirmed at the schema level:
  the keys do not exist in its output at all).
- **v2 `row_bundle_v2()` exactly preserves all four fields for 100% of these 7,407 rows, 0 mismatches**,
  and the file-metadata join (`provenance`/`source_path`) succeeds for all 7,407 (0 missing joins).

**Test result: PASS.** v2's evidence-bundle layer is a correct, complete, verified fix for the exact defect
diagnosed in KSME-20. This is a stronger, corpus-wide confirmation than the prior forks' fixture-based
reproduction (8 synthetic cases) — this ran the real check against the real, complete dataset.

**Not yet done**: adopting `row_bundle_v2()` in the production `derive_reconciliation.py` path, and
re-running any pair's reconciliation from the repaired evidence. That is a governance/adoption decision
(named, not made here) followed by the bounded re-adjudication in Phase 3 below.

## Phase 2 — Negative-search semantics: backfilled as a report, not applied

Computed `negative_verdict_label` for all 684 UNWITNESSED pairs, per protocol default (NEGATIVE-CENSUS
requires an explicit, stated corpus-wide search; everything else is NEGATIVE-BOUNDED):

- **656 pairs → NEGATIVE-BOUNDED** (no corpus-wide search language found in their evidence text).
- **28 pairs → NEGATIVE-CENSUS flagged** (evidence text contains corpus-wide/exhaustive-search language) —
  **these are possible latent-INDEPENDENT candidates currently sitting under UNWITNESSED**, per the
  protocol's own bar ("only a search stated as corpus-wide and empty yields INDEPENDENT"). Flagged for
  human/agent review, **not auto-reclassified** — a keyword match is a screen, not an adjudication.
  Full list: RP0002, RP0014, RP0023, RP0068, RP0130, RP0143, RP0214, RP0229, RP0248, RP0307, RP0525,
  RP0702, RP0746, RP0963, RP0974, RP1005, RP1048, RP1073, RP1128, RP1269, plus 8 more (34 total candidates
  before dedup against Phase 3's set — see below; 28 unique after dedup).

This is a pure metadata-computation exercise over existing text — no re-reading of source files was
required, and no verdict was changed.

## Phase 3 — Exact bounded affected-set (the "do not re-adjudicate all 1,793" scope)

Three criteria, computed precisely, not estimated:

| Criterion | Method | Count |
|---|---|--:|
| A. Evidence-loss-affected, already-judged pairs | Cross-referenced `P3A-UNDEFINED-RELATIONSHIP-PROVENANCE.jsonl`'s 1,467 distinct candidate label-pairs against existing `31-RECONCILIATION-PAIRS.jsonl` by (label_a, label_b) — exact match, not fuzzy | **257** |
| C. High-stakes slice (SAME+REPLACEMENT+DERIVED-FROM) | Direct filter of `31-RECONCILIATION-PAIRS.jsonl` | **158** |
| B. NEGATIVE-CENSUS anomaly (from Phase 2) | Keyword screen, flagged not adjudicated | **28** |
| **Union (A ∪ B ∪ C)** | | **414 (23.1% of 1,793)** |

Overlap detail: only 24 of the 257 evidence-loss-affected pairs are also in the 158 high-stakes slice —
**233 evidence-loss-affected pairs sit entirely outside the audited high-stakes tier**, meaning the defect
diagnosed in KSME-20 is broader than what the quality-gate memo's own sample covered. Relationship
distribution of the 257 evidence-loss-affected pairs: EXTENSION 108, UNWITNESSED 63, REFINEMENT 25,
SPECIALIZATION 13, DERIVED-FROM 16, CONTINUATION 16, REPLACEMENT 5, REDEFINITION 2, SAME 3 — spans every
relationship tier, confirming this is an input-visibility defect, not something specific to one verdict
type. Item D of the commission (pairs matching the two confirmed ontology-gap patterns) is **not
mechanically enumerable from what exists** — only 20 of the 158 high-stakes pairs were hand-classified
against the 7-cause taxonomy in KSME-20; a full classification of the remaining 138 is a real, bounded,
but not-yet-done task, named here rather than estimated.

**Bottom line for Phase 3: the bounded re-adjudication scope is 414 pairs (23.1% of 1,793), not all 1,793
and not merely the 158 originally audited.** This number, not 1,793 or 158, is the correct size for any
future re-adjudication pass.

## What remains before Phase 4+ (multidimensional ontology test, derivation graph, propositions, regimes)

1. A human/governance decision to adopt `row_bundle_v2()` as the production evidence layer (Phase 1 is
   verified-ready; adoption itself is not executed here).
2. Actual re-adjudication of the 414-pair bounded set using the repaired evidence bundle — this requires
   reading each pair's now-visible `dependencies`/`lineage_claims`/`invariants`/`assumptions` and producing
   a `repaired_verdict` alongside the preserved `original_verdict`, per the schema the commission specifies
   (`original_verdict`, `repaired_verdict`, `reason_for_change`, `evidence_used`, `confidence`,
   `review_status`). This is real, substantive work (414 pairs) — not executed this pass; sizing it
   precisely was the point of Phase 3, deliberately separated from doing it.
3. Only after (2) does it make sense to build the derivation graph (Phase 5) from corrected edges — building
   it now would bake in the same ~21-31% reliability problem KSME-20 diagnosed, for at least these 414 pairs.

No new document beyond this one was produced this pass, per the instruction not to jump ahead to Phases
4-13 before the affected set is fixed. `31-RECONCILIATION-PAIRS.jsonl` and `scripts/derive_reconciliation.py`
remain untouched; the negative-verdict-label backfill and the affected-pair-id lists exist only as this
report's own tables plus scratch working files, not as new pipeline artifacts, pending the adoption decision
in item 1.
