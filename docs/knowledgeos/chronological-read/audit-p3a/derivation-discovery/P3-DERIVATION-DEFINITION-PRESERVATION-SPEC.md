# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# Derivation & Definition Preservation Specification

**Phase mapping (no new phase invented, per §2):** discovery = P1/P2 (done);
relationship analysis + alternative preservation = P3/P3a/P3b (this spec
extends P3b's scope, does not create a new phase); validation-readiness = P5
(not performed, only scoped); membership decision = P4 (explicitly deferred);
canonical synthesis = P7 (explicitly deferred). **Date:** 2026-09-21.
**Status:** EXPERIMENTAL specification, not implemented, not authorized for
implementation by this document alone. **Authoritative:** NO.

## The governing separation (§1), restated as a data-flow

```
P1/P2   WHAT THE CORPUS PRODUCED           (every row, every label, unmerged)
  ↓
P3a     PAIR-LEVEL RELATIONSHIPS BETWEEN LABELS (existing, V1 + V2 repair)
  ↓
P3b-EXT INTRA-LABEL RELATIONSHIPS BETWEEN A LABEL'S OWN CANDIDATE FORMULATIONS
        (NEW scope this spec proposes — see below; not yet built)
  ↓
[STOP — this task ends here]
  ↓
P5      VALIDATION READINESS (what evidence WOULD be needed — scoped, not run)
  ↓
P4      MEMBERSHIP — which formulation(s) belong to the version under
        construction (NOT decided by this spec)
  ↓
P7      CANONICAL SYNTHESIS WITH PROVENANCE (NOT decided by this spec)
```

## Why this is a P3b extension, not a new phase

P3b already exists to answer "what does this object's roll-up look like" per
label (`derive_reconciliation_objects.py`, `semantic_status`/`type_status`/
`mathematical_status`/`candidate_births`/`lifecycle_candidate`). The Discovery
Audit found P3b's **current** design collapses multiplicity by picking one
`candidate_births` row per kind and one `lifecycle_candidate` value — it does
not yet have a *preserved-alternatives* concept. This spec proposes P3b's
scope grow to include that, using the SAME relationship vocabulary P3a already
established (no new phase, no new enum family beyond the two disclosed
extension candidates in the companion Relationship Matrix document).

## The four independence axes (§3, operationalized)

For any two candidate formulations D_i, D_j of what appears to be one concept,
determine **all four**, independently, never inferring one from another:

| Axis | Question | Evidence source |
|---|---|---|
| Object identity | Are D_i, D_j about the same candidate object at all? | shared label, or a lineage_claim/dependency linking them (P3a's existing machinery) |
| Definition identity | Do they define the same semantic object the same way? | `statement`/`type_signature` comparison |
| Derivation identity | Do they use the same derivational path/method? | `assumptions`/`invariants`/prose reasoning comparison — **not inferable from definition identity alone**, per the worked case below |
| Conclusion identity | Do they establish the same conclusion? | the row's own stated result/consequence |
| Dependency identity | Do they rely on the same premises? | `dependencies[]` + inferred shared unstated assumptions (see Dependency Model doc) |

**Worked case demonstrating non-implication** (from the Discovery Audit):
`knowledgeos-kernel-concept`'s F12→F14 pair has near-identical CONCLUSIONS
(near-verbatim restatement) yet the audit classified it REFINEMENT precisely
because the DERIVATION sharpened (notation `𝒦≠K_t≠ℳ` added) — conclusion
similarity did not by itself establish derivation identity; they were checked
separately, as this spec requires.

## Candidate-level evidence model (§17), as actually populated in this audit

Every candidate formulation carries (fields drawn directly from `03-
CONTRIBUTIONS.jsonl`'s existing schema — no new field invented at the P1
level):

```
candidate_id            (assigned during this analysis, e.g. "F1".."F9")
object_candidate_id     (the shared label, e.g. knowledgeos-kernel-concept)
source_id, anchor, original_statement   (verbatim from P1)
derivation_description  (this analysis's own synthesis of HOW it's derived —
                          new, since P1 does not extract this as a discrete field)
conclusion              (this analysis's synthesis of WHAT it concludes)
dependencies, lineage_claims, invariants, assumptions, type_signature
                        (verbatim from P1 — when empty, recorded as empty,
                         never invented, per the Discovery Audit's own finding
                         that these fields are frequently, genuinely blank)
provenance              (from 02-FILES.jsonl, unmodified)
chronological_position  (best_historical_date / explicit_date)
relationship_to_other_candidates, relationship_basis
                        (per the Relationship Matrix document — SOURCE-CLAIMED
                         wherever the row itself claims it, else INFERRED with
                         the inference stated, never silently upgraded)
validation_status       UNVALIDATED by default; never populated speculatively
review_status           NOT-YET-REVIEWED (this whole exercise is discovery,
                         not review, per §12)
```

**Explicit unknown states used throughout this audit** (per §17's "use
explicit unknown/uncertain states" instruction): `UNWITNESSED` (no defensible
relationship established), `NONE` (basis), `UNVALIDATED` (validation_status).
None of these were silently upgraded to a stronger claim anywhere in the audit.

## What this specification explicitly does NOT do

- Does not rank, score, or select a canonical formulation for any of the 12
  sampled concepts, or any other.
- Does not introduce a numeric evaluation function `Score(D_i | C)` — per §12,
  explicitly deferred to a future, separate P4/P5 decision.
- Does not modify `derive_reconciliation_objects.py`, `31-RECONCILIATION-
  PAIRS.jsonl`, or any P3b ledger.
- Does not resolve `knowledgeos-kernel-concept`'s 9-way multiplicity, or any
  other case found — it only demonstrates that the multiplicity can be
  discovered, classified, and preserved without collapsing it.
