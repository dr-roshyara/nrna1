# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# Preserved-Alternatives Schema

**Purpose:** define how all candidate formulations are preserved without
canonicalization (§11). **Date:** 2026-09-21. **Status:** EXPERIMENTAL
specification — not implemented as code, not written to any ledger.
**Authoritative:** NO.

## Schema (research representation, per §11 — explicitly NOT a canonical-
theory representation)

```yaml
concept_candidate: knowledgeos-kernel-concept
canonical_status: NOT-YET-SELECTED   # never anything else, produced by this phase

historical_formulations:
  - candidate_id: F1
    source_id: S0167
    chronological_position: "2026-08-19T22:41"
    original_statement: "Kernel = minimal system for {create, identify, relate, govern, preserve} knowledge objects"
    dependencies: []
    validation_status: UNVALIDATED
  - candidate_id: F2
    source_id: S0593a
    chronological_position: "2026-08-21T20:32"
    original_statement: "Kernel = deterministic runtime for {authority, evidence, lifecycle, provenance}"
    dependencies: []
    validation_status: UNVALIDATED
  # ... F3 through F9 (Wave 1) omitted here for brevity, same structure ...
  - candidate_id: F10
    source_id: S0675
    chronological_position: null   # UNSTATED, recorded as such, not guessed
    original_statement: "Kernel = the smallest domain-independent substrate that owns identity, history, provenance and relational semantics..."
    dependencies: []
    validation_status: UNVALIDATED
  # ... F11 through F16 (Wave 3) omitted here for brevity, same structure ...

relationships:
  # every edge from the Relationship Matrix document, unabridged
  - {from: F1, to: F2, relationship: INDEPENDENT, basis: NONE}
  - {from: F2, to: F3, relationship: REPLACEMENT, basis: SOURCE-CLAIMED}
  - {from: F4, to: F5, relationship: CONTRADICTORY, basis: SOURCE-CLAIMED, caveat: "shared prompt lineage, not independently arrived"}
  - {from: F5, to: F6, relationship: SAME, basis: CORROBORATED, note: "claimed CONTRADICTORY, dissolves to vocabulary collision on inspection (S2-F017)"}
  - {from: F7, to: F8, relationship: DERIVED-FROM, basis: SOURCE-CLAIMED}
  - {from: F8, to: F9, relationship: DERIVED-FROM, basis: SOURCE-CLAIMED, note: "methodology only -- F9 does not apply F8's substantive conclusion; unresolved internal tension, not flagged by the corpus itself"}
  # ... remainder per the full matrix ...

dependency_overlap:
  wave_1_common_mode:
    members: [F4, F6, F7_model_a, F9]
    shared_premise: "INFERRED-UNSTATED: Kernel membership justified by ONE co-location/consistency-boundary argument"
    consequence: "these four are NOT four independent confirmations of a consistency-boundary Kernel"
  wave_3_common_mode:
    members: [F12, F13, F14, F15, F16]
    shared_premise: "INFERRED-UNSTATED (partially SELF-FLAGGED-UNSTATED in S2221's own type_signature field): K_t is well-typed at every t"
  shared_prompt_lineage:
    - {members: [F4, F5], source: "F5's own provenance note"}
    - {members: [F12, F13, F14, F15, F16], source: "one continuous ~1h session, 2026-09-01"}

validation:
  F1: UNVALIDATED
  F8: {status: EXPERIMENTALLY-SUPPORTED, means: "pair-wise atomicity falsification test, 5/5 pairs FALSIFIED", source_id: S0370}
  # every other candidate: UNVALIDATED (default; never populated speculatively)

provenance:
  every_candidate: "complete -- source_id, path, file-level PRIMARY/SECONDARY-SYNTHESIS provenance all carried, per the P3A-V2 evidence-bundle discipline"

corpus_self_awareness:
  - "S1-F013 (2026-08-23): 'the corpus now holds eight distinct Kernel formulations'"
  - "S2-F017 (Session 2 review): re-audits that claim, reduces to 'six positions defensible against eight reported'"
  - "S2-FINAL-KERNEL-REVIEW.md: 'nine formulations proposed... zero adversarial passes over eight Kernel formulations in four days'"
```

## Design notes

- **`canonical_status: NOT-YET-SELECTED` is the only permitted value this
  phase may ever write.** `winner`/`best`/`optimal`/`preferred`/`canonical`
  do not appear anywhere in this schema, per §12's explicit prohibition.
- **Losers are never deleted.** Every one of the 9 kernel formulations, 5 Zero
  signatures, and 5 step-verify-programme status schemes found in the
  Discovery Audit is retained in full in this representation, whether or not
  a future P4 process ever selects it.
- **`corpus_self_awareness` is a new, disclosed field this schema adds** —
  not present in any existing P1/P2/P3 contract. It exists because the
  Discovery Audit found the corpus repeatedly, explicitly documents its own
  multiplicity (the kernel Session-1/Session-2 reviews, `formal-zero-
  algebra`'s S1433 register, `step-verify-programme`'s charter item 6
  supersession). Discarding this self-documentation would itself be an R0
  violation — the corpus already did significant relevant work that a
  preserved-alternatives structure should carry forward, not silently drop.
  **Flagged as a `PROTOCOL-EXTENSION-CANDIDATE`**, not silently added to P2b's
  or P3a's existing schemas.

## What is deliberately absent from this schema

A `score` field, a `rank` field, a `recommended` field, or any numeric
aggregation of the 12-criteria evaluation dimensions proposed earlier in this
conversation — all of that is explicitly P4/P5 territory (see the companion
Protocol Extension Candidates document) and appears nowhere here.
