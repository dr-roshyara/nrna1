# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# P3A-V2 Field Preservation Matrix

**Phase:** P3a repair diagnosis. **Purpose:** trace every P1 contribution field
through P1c→P2a→P2b→P3a(V1)→P3a(V2), answering the 9 questions in §5 of the
repair task. **Date:** 2026-09-21. **Status:** EXPERIMENTAL diagnostic document.
**Input:** `03-CONTRIBUTIONS.jsonl` schema, `derive_families.py`,
`derive_reconciliation.py` (V1), `derive_reconciliation_v2.py`/
`evidence_bundle_v2.py` (V2). **Authoritative:** NO.

For every field: (1) created, (2) stored, (3) transformed, (4) consumed,
(5) discarded, (6) discard allowed by v3.5?, (7) affects discovery?,
(8) affects adjudication?, (9) affects provenance?

| Field | (1) Created | (2) Stored | (3) Transformed | (4) Consumed (P2) | (5) Discarded (V1) | (6) Allowed by v3.5? | (7) Affects discovery? | (8) Affects adjudication? | (9) Affects provenance? |
|---|---|---|---|---|---|---|---|---|---|
| `source_id` | P0 (`validate_roadmap.py`, ingestion order) | every ledger | never | citation key everywhere | never | N/A | Used throughout | Used throughout | Is the join key for provenance lookup |
| `anchor` | P1 | `03-CONTRIBUTIONS.jsonl` | never | not by P2 | Not discarded — kept by `row_brief()` in both V1/V2 | N/A | No | Yes — re-read location for the reviewer | No |
| `explicit_date` | P1 | `03-CONTRIBUTIONS.jsonl` | best_historical_date roll-up (P1c) | order evidence | Not discarded | N/A | No | Minor (temporal context) | No |
| `scope` | P1 | `03-CONTRIBUTIONS.jsonl` | never | used by `build_scope_collections.py` for label-less rows | **Discarded by both V1 and V2's candidate generation** (neither reads `scope`) | **Yes** — R0 does not require every field to feed relationship discovery; `scope` answers a different question (label-less coverage), already served by its own P2b artifact | No | No | No |
| `labels[]` | P1 | `03-CONTRIBUTIONS.jsonl` | P2a clustering input | YES — WITHIN-GROUP membership | Not discarded | N/A | Central to mechanism A | No | No |
| `label_confidence` | P1 | `03-CONTRIBUTIONS.jsonl` | never | not read by P2a clustering or P3a candidate generation (either version) | **Discarded by both V1 and V2** | **Ambiguous** — R6 implies confidence should travel with a claim; neither version currently surfaces it to a reviewer. **Flagged as a remaining gap, not fixed in this repair** (out of the surgical scope: §7's evidence-bundle spec did not list it, so V2 does not add it either — see EVIDENCE-BUNDLE-SPEC.md's own disclosed limitation) | No | Potentially — a reviewer cannot currently see whether a row's own label attribution was UNCERTAIN | No |
| `unknown_object_candidate` | P1 | `03-CONTRIBUTIONS.jsonl` | resolved by `build_unresolved_objects.py` | routes to `_UNRESOLVED-OBJECTS.md`, never a real label | correctly never enters candidate generation (R5) | **Yes, required** — R5 forbids resolving the sentinel to a real object | Correctly excluded (verified, fixture 15) | N/A | N/A |
| `types[]` | P1 | `03-CONTRIBUTIONS.jsonl` | never | `CONTRADICTION` flag only (`a_has_contradiction_type_row`) | **V1 row_brief() keeps `types`; V2 keeps it too.** Neither version's candidate-generation logic *interprets* the semantic meaning of types beyond the one boolean flag | Yes — R16 requires type and relationship to be decided separately; deeper type-based filtering in candidate generation would itself risk violating R16, so this is a correct, deliberate non-use, not an oversight | No | Yes — visible to reviewer in both versions | No |
| `statement` | P1 | `03-CONTRIBUTIONS.jsonl` | never | rationale extraction (P2b) | Not discarded | N/A | **Only V2 uses this for mechanism F (prose-trigger)** | Central — the primary text a reviewer reads | No |
| `type_signature` | P1 | `03-CONTRIBUTIONS.jsonl` | never | not by P2a/P2b clustering | Not discarded — kept by `row_brief()`/bundle in both | N/A | No | Yes (Q2 type_compatibility input) | No |
| `completeness` | P1 | `03-CONTRIBUTIONS.jsonl` | rolled up into P2b's 12-dimension completeness | P2b roll-up | **Discarded by V1's row_brief(); ADDED by V2's evidence bundle (per-row `assumptions`/`invariants` presence contributes to this, though the roll-up itself remains a P2b/P3b concern, not P3a pair-level)** | Partially fixed in V2 | No (candidate generation) | Minor | No |
| `missing[]` | P1 | `03-CONTRIBUTIONS.jsonl` | rolled into P2b completeness | P2b roll-up | Discarded by both V1 and V2's per-pair bundles (this is a P2b/P3b-level field, not naturally a P3a pair-evidence field) | Yes — this field answers "what does THIS row not cover," which is a P2b family-level question, correctly out of P3a's pair-adjudication scope | No | No | No |
| **`dependencies[]`** | P1 | `03-CONTRIBUTIONS.jsonl` | never | **not consumed by P2a/P2b at all** | **V1: discarded entirely (never read anywhere in `derive_reconciliation.py`). V2: consumed by mechanism B, preserved in every bundle.** | **No — this is the single clearest violation of R0 in the whole pipeline**: a field P1 faithfully captured (per R0) is never used for anything downstream in V1 | **YES — the single largest recovery in this repair (1,319 of 3,089 V2 candidate pairs carry a B_DEPENDENCY signal)** | Yes, once surfaced | Carries `target_provenance` in V2 |
| `invariants[]` | P1 | `03-CONTRIBUTIONS.jsonl` | never | not by P2a/P2b clustering (used only in P2b's `rationale_evidence`/family display) | **V1: discarded by row_brief(). V2: included in the bundle.** | Debatable — not itself a relationship signal, but potentially relevant corroborating context for Q2/basis | No | Minor-to-moderate — corroborating context V1 hid | No |
| `assumptions[]` | P1 | `03-CONTRIBUTIONS.jsonl` | rolled into P2b's `assumption_register` | P2b roll-up | **V1: discarded by row_brief(). V2: included.** | Same as `invariants[]` | No | Minor-to-moderate | No |
| **`lineage_claims[]`** | P1 | `03-CONTRIBUTIONS.jsonl` | never | not by P2a/P2b clustering | **V1: only the exact-string-match subset survives into `lineage_claims_a_to_b/b_to_a`; everything else (source_id targets, substring/paraphrase targets) is discarded at candidate-generation time, AND even the surviving exact matches are never shown in `row_brief()`'s per-row output (they live in a separate structured field, so they DO reach the reviewer, just not attached to the row itself).** **V2: mechanisms C/D/E capture ALL forms; the evidence bundle includes the full `lineage_claims[]` array per row.** | **No — R7 explicitly requires a SOURCE-CLAIMED-* statement to be preservable and testable by P3; V1 makes roughly 18% of real lineage_claims (277 of ~1,540 corpus-wide non-trivial claims, per the provenance-trace report) structurally untestable** | **YES — the second-largest recovery mechanism (C: 8 pairs, E: 211 pairs)** | Central | `source_provenance`/`target_provenance` now attached |
| `version_ref` | P1 | `03-CONTRIBUTIONS.jsonl` | never | not consumed anywhere downstream of P1 | Discarded by both | Yes — no protocol rule ties this to relationship discovery or adjudication | No | No | No |
| `experiment{}` | P1 | `03-CONTRIBUTIONS.jsonl` | never | not consumed by P2/P3a candidate generation (used narratively in some P2b family write-ups) | Discarded by both V1/V2's default bundle | Yes, per the same reasoning as `version_ref` — available on request via the full ledger, not needed for the pair-level decision | No | Potentially minor (experimental corroboration) — not added in this repair, disclosed as a limitation | No |
| `review_flag` | P1 | `03-CONTRIBUTIONS.jsonl` | never | not consumed | Discarded by both | Yes | No | No | No |
| **File-level `provenance`** (PRIMARY/SECONDARY-SYNTHESIS/PROVENANCE-UNRESOLVED) | P1 (`02-FILES.jsonl`) | `02-FILES.jsonl` | never (never overwritten downstream — verified, R8/R10) | not consumed by P2a/P2b at all | **V1: completely absent from `row_brief()` and from any pair-level field — the single most significant provenance loss in the pipeline.** **V2: attached to every signal and every row in the bundle.** | **No — directly contradicts the protocol's own citation discipline (§B4: "EVERY statement carries [S-id §anchor] † if SECONDARY-SYNTHESIS")** | No (doesn't affect whether a pair is found) | **Yes, materially — a reviewer cannot currently tell PRIMARY from SECONDARY-SYNTHESIS evidence at all in V1** | **This IS the provenance field — its loss is total in V1, fully repaired in V2** |

## Summary

Of 20 P1 contribution fields plus the file-level provenance field: **4 fields
(`dependencies[]`, `lineage_claims[]` non-exact forms, `invariants[]`,
`assumptions[]`) plus the file-level provenance marker are discarded by V1 in a
way this matrix assesses as NOT clearly allowed by Master Protocol v3.5** (R0's
information-preservation mandate, R7's SOURCE-CLAIMED-* testability requirement,
and §B4's citation discipline, respectively). **V2 repairs all 5.** Two fields
(`label_confidence`, `experiment{}`) are flagged as open, smaller, genuinely
debatable gaps this repair did **not** address — disclosed, not hidden, per
§13's "known limitations" requirement.
