# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# P3A-V2 Evidence-Bundle Specification

**Phase:** P3a evidence serialization (what a reviewer is shown). **Purpose:**
replace `row_brief()`'s 6-field summary with a bundle that preserves everything
a relationship decision could depend on. **Date:** 2026-09-21. **Status:**
EXPERIMENTAL. **Implementation:** `audit-p3a/v2/scripts/evidence_bundle_v2.py`.
**Authoritative:** NO.

## Governing principle (§7, restated)

> Evidence reduction is allowed only when it is lossless with respect to the
> decision being made.

## What V1's `row_brief()` provided (6 fields)

`source_id, anchor, types, statement, type_signature, explicit_date` — and
nothing else, for every row, regardless of truncation status.

## What V2's bundle provides, per row (`row_bundle_v2`)

`source_id, anchor, statement, labels, types, type_signature, dependencies,
lineage_claims (full: kind/target/quote), invariants, assumptions,
explicit_date, provenance, source_role, source_path`.

**Rationale per field beyond V1's set:**
- `labels` — a reviewer could not previously see which OTHER labels the same
  row was tagged with (relevant for dual-labeled-row cases, a recurring finding
  across this whole audit chain, e.g. RP0526's S1347 carrying 3 labels).
- `dependencies` — the field mechanism B reads; shown so the reviewer can
  verify it themselves, not just trust the mechanism's summary.
- `lineage_claims` (full array, not just the one matched claim) — a row can
  carry multiple claims; showing only the one that triggered candidate
  generation would itself be a lossy reduction.
- `invariants`, `assumptions` — corroborating context for `basis` (CORROBORATED
  requires demonstrated continuity; these fields are exactly where continuity
  evidence often lives).
- `provenance`, `source_role` — the fix for the total provenance loss found in
  V1 (Conformance Audit §16, fixture case 8/9).
- `source_path` — lets a reviewer independently re-verify against the raw file,
  exactly as this whole audit chain repeatedly did by hand.

## What remains deliberately excluded (disclosed, not silently dropped)

- `version_ref`, `experiment{}`, `review_flag` — no protocol rule ties these to
  a relationship decision; available one lookup away in the full ledger.
- `label_confidence`, `completeness`, `missing[]`, `unknown_object_candidate` —
  these answer P2b/P1-level questions (is this row's OWN label attribution
  certain? what does this row not cover?) distinct from "does this row relate
  to that other row" — including them would be volume without decision-
  relevance, which §7 explicitly forbids ("Do not expose irrelevant corpus
  material merely to increase volume"). **Disclosed limitation**: a future
  repair could reconsider `label_confidence` specifically, since R6 arguably
  implies it should travel with any claim derived from an UNCERTAIN-labeled
  row — not fixed here, flagged in the Field Preservation Matrix.

## Candidate-level bundle (`candidate_bundle_v2`)

Beyond the per-row bundles for both sides, the candidate-level structure adds:
- `mechanisms` — every mechanism (A-F) that independently proposed this pair
  (a pair triple-corroborated by A+B+E, like RP0288, is visibly stronger
  evidence than a pair found only by F).
- `relationship_signal_origin` — the exact quote/dependency-value/verb that
  triggered each mechanism, so the reviewer can trace the claim to its source
  without re-deriving it.
- `provenance_summary` — `{a_has_primary, a_has_secondary_synthesis,
  b_has_primary, b_has_secondary_synthesis}` — a quick, honest,
  non-flattening summary of provenance mix across each side's whole family
  (not just the specific row a mechanism happened to cite), so a reviewer sees
  immediately if either side rests partly or wholly on SECONDARY-SYNTHESIS
  material.

## What this specification does NOT change

The evidence bundle is read-only input to an unchanged adjudication process.
No verdict schema, no relationship enum, no basis enum, no verify script is
modified by this specification. A reviewer using this bundle is still solely
responsible for the Q1/Q2 judgment, per R13.
