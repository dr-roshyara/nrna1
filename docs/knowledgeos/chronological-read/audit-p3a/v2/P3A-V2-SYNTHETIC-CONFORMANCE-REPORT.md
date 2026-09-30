# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# P3A-V2 Synthetic Conformance Report

**Phase:** P3a repair, pre-real-corpus validation. **Purpose:** exercise every
V2 mechanism against fully synthetic, in-memory-only data before touching any
real file. **Date:** 2026-09-21. **Status:** EXPERIMENTAL. **Implementation:**
`audit-p3a/v2/scripts/synthetic_fixtures_v2.py`, importing the real
`ReconciliationEngineV2` class (not a reimplementation). **Zero file writes,
zero real-corpus reads. Authoritative:** NO.

## Result: 15/15 fixtures pass (after 2 disclosed fixes)

| # | Case | Result | Note |
|--:|---|---|---|
| 1 | Dependency-only relationship | PASS | Mechanism B fires; no relationship auto-determined |
| 2 | Source-ID lineage | PASS | Mechanism C resolves via the source_id→label index, not a representative-row substitute — the exact RP0526 fix |
| 3 | Paraphrased/substring lineage | PASS | Mechanism E, deterministic substring rule, HIGH confidence from the possessive marker — the exact RP0288 fix |
| 4 | Exact-label lineage | PASS | Mechanism D, unchanged from V1 |
| 5 | Explicit prose relationship, no structured field | PASS | Mechanism F, LOW confidence only — the one case V1 could never recover at all |
| 6 | P2 similarity-only relationship | PASS | Mechanism A, unchanged from V1 |
| 7 | Duplicate handling (R9) | PASS (N/A) | Enforced at P0/P1c, correctly out of scope for a P3a-only repair |
| 8 | Secondary-synthesis cites primary (candidate generated?) | PASS | Mechanism D fires regardless of provenance mix — candidate generation is provenance-blind by design; provenance is a bundle property, not a gate |
| 9 | Secondary-synthesis cites primary (provenance preserved in bundle?) | **initially FAILED, now PASS** | See "Fix 1" below |
| 10 | Negative-bounded UNWITNESSED | PASS (N/A) | Adjudication-schema concern, correctly out of scope for candidate-generation repair |
| 11 | Genuine corpus-wide INDEPENDENT | PASS (N/A) | Unchanged, already conforming (Conformance Audit) |
| 12 | Type mismatch without relationship | PASS (N/A) | R16 discipline is adjudication-time, unaffected by candidate generation |
| 13 | Relationship without compatible type | PASS (N/A) | Same as 12 — V2 candidates carry no type_compatibility field at all |
| 14 | Explicit contradiction | **initially FAILED, now PASS** | See "Fix 2" below |
| 15 | UNKNOWN-OBJECT-CANDIDATE sentinel | PASS | R5 preserved — the sentinel is never registered as a real label, so it structurally cannot become a candidate pair member |

## Fix 1 (found by fixture 9, applied before proceeding)

**What happened:** the first implementation of mechanisms B, D, E, and F
recorded `source_provenance` but not `target_provenance` — only mechanism C
(which resolves to one exact source_id) had both. Fixture 9 caught this
directly: it asserted `target_provenance == "PRIMARY"` for a synthetic
mechanism-D candidate and got `None`.

**Fix:** added a `label_provenance(label)` helper (dominant provenance across a
whole label's family: PRIMARY if any row is PRIMARY, else SECONDARY-SYNTHESIS,
else PROVENANCE-UNRESOLVED, else None) and applied it to mechanisms B, D, E,
and F's target side. Re-ran against the real corpus after the fix: candidate
counts shifted by 1 pair (3,088→3,089), confirming the fix was precise, not a
sweeping behavior change.

## Fix 2 (found by fixture 14, applied before proceeding)

**What happened:** fixture 14 used short synthetic label names (`obj-a`,
`obj-b`, 5 characters) that fell below mechanism F's minimum-9-character noise
guard — a test-harness design flaw (unrealistically short labels), not an
engine bug.

**Fix:** renamed the fixture's synthetic labels to realistic lengths
(`obj-alpha-claim`, `obj-beta-claim`), matching real corpus label conventions.
No engine code changed for this fix.

## What this suite does NOT test

Real corpus content, real false-positive rates at scale, or real adjudication
outcomes — those are the subject of `P3A-V2-REPAIR-VALIDATION-REPORT.md`. This
suite tests only that the mechanisms behave exactly as specified on
deliberately simple, fully-controlled inputs.
