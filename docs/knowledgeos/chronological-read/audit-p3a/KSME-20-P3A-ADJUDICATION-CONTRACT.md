---
task: KSME-20 (P3a Reliability Diagnosis, per user commission)
scope: procedural contract for any future P3a re-adjudication pass; not itself an adjudication
derived_from: [KSME-20-P3A-QUALITY-REPRODUCTION, KSME-20-P3A-ERROR-TAXONOMY, KSME-20-P3A-TRACK-PROVENANCE-AUDIT]
---

# KSME-20 — P3a Adjudication Contract (proposal, pending governance decision — not adopted by writing it here)

This formalizes the decision procedure the master protocol left unconstrained, per the diagnosis in the two
prior documents. It is a **proposal**, not an adopted change to the master protocol — adoption is a
governance decision, named explicitly at the end.

## 1. Fix order (cheapest, most mechanical, least judgment-dependent first)

1. **Repair the evidence-visibility bug before touching any verdict.** `row_brief()` in
   `derive_reconciliation.py` must stop stripping `dependencies[]`, `lineage_claims[]`, `invariants[]`,
   `assumptions[]`, and file-level provenance. This requires zero re-adjudication — it is a code fix,
   already root-caused and reproduced via 8 synthetic fixtures (`P3A-IMPLEMENTATION-CONFORMANCE-AUDIT.md`).
   **Reuse `audit-p3a/v2/scripts/derive_reconciliation_v2.py` and `evidence_bundle_v2.py` as the starting
   point** — they already solve exactly this (candidate generation + evidence-bundle completeness),
   validated against real corpus data (3,089 real candidate rows, 99.93% recall of previously-invisible
   signal pairs). v2 does not adjudicate verdicts and must not be treated as doing so (see
   `KSME-20-FORK-DECISION.md` §2) — it is the correct *input layer* for step 2.
2. **Enforce the NEGATIVE-BOUNDED/NEGATIVE-CENSUS distinction mechanically.** Every UNWITNESSED verdict
   must carry `negative_verdict_label` ∈ {NEGATIVE-BOUNDED, NEGATIVE-CENSUS}, set automatically from
   whether the search that produced it was corpus-wide (INDEPENDENT-eligible) or group/batch-bounded
   (UNWITNESSED-only). `verify_batch.py`'s successor must reject any UNWITNESSED row missing this label.
   This alone lets a script separate categories A/B/D of §4 in the Error Taxonomy without re-reading a
   single source document.
3. **Re-run adjudication only on the pairs actually affected by (1)+(2)** — the 65+ evidence-loss pairs
   (§3 of the Error Taxonomy), the 257 already-judged pairs among the 1,466 newly-surfaced-evidence
   label-pairs, and the 158 high-stakes slice. **Do not re-adjudicate all 1,793 pairs** — most were never
   shown to be unreliable; only a bounded, nameable subset is affected.

## 2. Evaluation procedure for any pair requiring re-adjudication

Evaluate the dimensions in `KSME-20-P3A-ERROR-TAXONOMY.md` §6 **in this fixed order**, stopping at the
first that applies (this is the "tie-breaking rule to appeal to" that RP0647's disagreement showed is
currently missing):

1. Identity/semantic equivalence → SAME
2. Chronological continuation (same thread, same object family, sequential) → CONTINUATION
3. Refinement/specialization (narrows or sharpens, same claim) → REFINEMENT or SPECIALIZATION
4. Extension (adds capability/constraint, does not narrow) → EXTENSION
5. Derivation (a distinct consequence follows from the source) → DERIVED-FROM
6. Redefinition/replacement (changes or supersedes meaning/role) → REDEFINITION or REPLACEMENT
7. Homonymy (same name, unrelated referents) → HOMONYM
8. Search the full corpus (not just the current group/batch) for a connection → if empty, INDEPENDENT
   (NEGATIVE-CENSUS)
9. If only a group/batch-bounded search was performed and found nothing → UNWITNESSED (NEGATIVE-BOUNDED)
10. If a real, specific, quoted relationship is found that fits none of 1-7 → **do not force a value.**
    Record `relationship: PENDING-ONTOLOGY-DECISION` with the quoted evidence, and route to the governance
    decision named in §3. **Never silently coerce into the nearest available enum value** — this is exactly
    the mechanism that produced the RP0440-style disagreements.

## 3. Named governance decision, not resolved here

Whether to add new enum values for the two confirmed gaps (`KSME-20-P3A-ERROR-TAXONOMY.md` §6 — an
"operationally-connected-but-architecturally-independent" value, and a "symmetric/co-integrative sibling"
value) or to instead add a sub-typed status field layered on the existing 11 values. Recommend the sub-typed
field (lower migration cost, does not invalidate 1,793 existing judgments), but this is a recommendation,
not a decision this pass is authorized to make.

## 4. What this contract does NOT do

Does not re-adjudicate any pair. Does not modify `31-RECONCILIATION-PAIRS.jsonl` or any other pipeline
artifact. Does not adopt v2. Does not add a 12th/13th enum value. All of these require an explicit human
decision, named as pending throughout.
