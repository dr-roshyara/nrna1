# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# P3A-V1 vs P3A-V2 — Measured Diff

**Date:** 2026-09-21. **Status:** EXPERIMENTAL comparison document.
**Authoritative:** NO. **V1** = `31-RECONCILIATION-PAIRS.jsonl` (production,
frozen, untouched). **V2** = `audit-p3a/v2/output/P3A-V2-CANDIDATES.jsonl`
(experimental candidate list only — **not** a relationship ledger; V2 has made
zero relationship/basis/type_compatibility decisions).

## Change log (§19 format)

```
CHANGE: candidate generation now reads dependencies[] (mechanism B)
WHY: R0 requires P1 information preservation; dependencies[] was captured by
     P1 and never consumed by any downstream script
PROTOCOL RULE: R0, A11 (candidate discovery)
OLD BEHAVIOR: dependencies[] never referenced anywhere in
     derive_reconciliation.py
NEW BEHAVIOR: an exact dependencies[] match to a real label generates a
     MECHANICAL-EXACT candidate signal (never a relationship)
RISK: none identified — this is a structured field with a closed, exact
     matching rule
TEST: synthetic fixture 1 (PASS); real-corpus regression (1,319 pairs, 0
     regressions against V1's 1,793)
RESULT: 1,319 new candidate pairs surfaced for future adjudication
```

```
CHANGE: candidate generation now resolves bare-source_id lineage_claims
     targets (mechanism C)
WHY: R7 requires a SOURCE-CLAIMED-* statement to be testable by P3; a claim
     whose target is a source_id was structurally invisible to V1's exact
     label-string matcher
PROTOCOL RULE: R7, A11
OLD BEHAVIOR: lineage_claims[].target values matching ^S\d{4}$ were silently
     dropped
NEW BEHAVIOR: resolved via a direct source_id -> owning-label index; ambiguous
     cases (source_id shared by >1 label) are recorded as AMBIGUOUS, never
     auto-resolved
RISK: an earlier draft of this exact mechanism (built for the unrelated
     provenance-tracing investigation) substituted a "representative row" for
     the wrong source_id -- this repair's mechanism C never does that; it
     always uses the exact cited source_id
TEST: synthetic fixture 2 (PASS); named regression RP0526 (correctly resolves
     to S1345, not S1273)
RESULT: 8 new candidate pairs (this pattern is corpus-rare -- only 8
     bare-source_id lineage_claims exist that resolve unambiguously)
```

```
CHANGE: candidate generation now resolves paraphrase/substring lineage_claims
     targets (mechanism E)
WHY: R7, same reasoning as mechanism C, for the more common paraphrase pattern
     ("label's X (Step N)") rather than the bare-source_id pattern
PROTOCOL RULE: R7, A11; PROTOCOL-AMBIGUITY-001 (A11's "target string matches"
     is ambiguous between exact and substring forms -- resolved by making both
     an explicit, separately-labeled mechanism rather than silently picking one)
OLD BEHAVIOR: only exact string equality checked
NEW BEHAVIOR: deterministic substring containment, longest-match tie-break,
     confidence graded HIGH (possessive marker present) or MEDIUM (absent) --
     no fuzzy/edit-distance/embedding matching
RISK: an initial version broke on the first-encountered match in arbitrary set
     order rather than the longest; fixed before real-corpus use (disclosed in
     the Validation Report)
TEST: synthetic fixture 3 (PASS); named regression RP0288 (correctly resolves
     the possessive-marker case)
RESULT: 211 new candidate pairs
```

```
CHANGE: candidate generation now flags explicit relationship-verb prose
     (mechanism F)
WHY: A11's evidence list implies prose-stated relationships should be
     discoverable; V1 had zero mechanism for unstructured text at all
PROTOCOL RULE: A11; explicitly bounded per R13 (a script may derive a signal,
     never a relationship)
OLD BEHAVIOR: no prose-scanning mechanism existed
NEW BEHAVIOR: a closed, 16-phrase verb vocabulary co-occurring with a real
     label's exact string in the same statement generates a LOW-confidence
     signal only, skipped entirely when a structured field already covers the
     same pair (no double-signaling)
RISK: MATERIAL, DISCLOSED, PARTIALLY MITIGATED -- initial version produced 538
     pairs, 74.1% driven by 2 generic single-word labels (KnowledgeOS,
     provenance) coincidentally appearing in unrelated prose. Fixed by
     excluding the corpus's 8 single-word labels from this mechanism only,
     reducing output to 102 pairs (81% cut). Residual false-positive rate not
     precisely measured at scale (only an n=8 spot-check exists) -- flagged as
     the single largest open risk in this repair
TEST: synthetic fixtures 5, 14 (PASS); real-corpus spot-check (Validation
     Report §E)
RESULT: 102 new candidate pairs, LOW confidence, explicitly requiring
     individual adjudication before any use
```

```
CHANGE: every candidate signal now carries source and target file-level
     provenance (PRIMARY/SECONDARY-SYNTHESIS/PROVENANCE-UNRESOLVED)
WHY: the protocol's own citation discipline (prompt3 §B4: every statement
     carries a dagger if SECONDARY-SYNTHESIS) was completely lost by V1's
     row_brief(), which has no provenance field at all
PROTOCOL RULE: §B4 citation discipline; R8 (own artifacts DERIVED, never
     flattened into primary evidence -- the closely related discipline of
     never flattening SECONDARY-SYNTHESIS into PRIMARY either)
OLD BEHAVIOR: zero provenance information anywhere in a P3a pair record or its
     evidence sample
NEW BEHAVIOR: every signal and every bundled row states its own file's
     provenance; a candidate-level provenance_summary flags any mix
RISK: none identified -- this is a pure addition, reading an already-existing,
     never-modified field
TEST: synthetic fixture 9 (initially FAILED -- 4 of 5 mechanisms were missing
     this field entirely; fixed before real-corpus use); named regression
     RP0526 (correctly shows source=PRIMARY, target=SECONDARY-SYNTHESIS)
RESULT: the single most consequential fix for reviewer defensibility -- a
     reviewer can now see, for every piece of evidence, whether it is original
     historical material or a later reconstruction of it
```

```
CHANGE: evidence bundle now includes dependencies[], lineage_claims[] (full),
     invariants[], assumptions[], labels[], and provenance per row, replacing
     row_brief()'s 6-field summary
WHY: R0 -- these fields were captured and preserved through P1c/P2, then
     discarded specifically at the point of building what a reviewer sees
PROTOCOL RULE: R0
OLD BEHAVIOR: a_rows_sample/b_rows_sample carried only source_id, anchor,
     types, statement, type_signature, explicit_date -- for every row,
     regardless of truncation
NEW BEHAVIOR: row_bundle_v2() carries all of the above
RISK: none identified -- larger bundles, no interpretive change
TEST: fixture 9 (provenance specifically); manual inspection of
     evidence_bundle_v2.py's field list against the Field Preservation Matrix
RESULT: an adjudicator now has, for the first time, everything the matching
     mechanisms themselves used, rather than having to independently
     re-fetch raw files to verify a claim (as every human/agent reviewer in
     this entire audit chain had to do by hand, repeatedly, this session)
```

## What did NOT change (explicitly, per §19's scope discipline)

- No relationship, basis, or type_compatibility value was computed, proposed,
  or written for any pair, existing or new.
- No P3a adjudication dispatch prompt, verify script, or ledger schema was
  modified.
- The NEGATIVE-BOUNDED enforcement gap (Conformance Audit finding) is
  unchanged.
- R16 (relationship/type independence) discipline is unchanged — already
  conforming, not touched.
- P3b, P4, P5, P6, P7 — untouched, not started, not referenced beyond citation.
- No historical source file, `02-FILES.jsonl` record, `03-CONTRIBUTIONS.jsonl`
  record, or `_derived.json` field was modified.
- `31-RECONCILIATION-PAIRS.jsonl` — byte-for-byte unchanged.

## Net numeric summary

| | V1 | V2 | Δ |
|---|--:|--:|--:|
| Candidate pairs | 1,793 | 3,089 | +1,296 |
| Mechanisms | 2 (WITHIN-GROUP, exact-match CROSS-GROUP) | 6 (A-F) | +4 |
| Fields consumed per row for candidate generation | 2 (`labels`, `lineage_claims.target` exact-match only) | 5 (+ `dependencies`, non-exact `lineage_claims.target`, `statement`) | +3 |
| Fields in the evidence bundle | 6 | 15 | +9 |
| Provenance visible to reviewer | 0 fields | 3 fields (source, target, summary) | +3 |
| Recall against the known 1,467-pair evidence-loss population | 0% (by definition — this was the invisible population) | 99.93% | — |
| Regression against V1's existing 1,793 | — | 0 | — |
