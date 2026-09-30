# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# Derivation Dependency Model

**Purpose:** define how dependency sets and shared/hidden dependencies are
represented (§8, §16). **Date:** 2026-09-21. **Status:** EXPERIMENTAL,
specification only — no computation was run against the full corpus; all
examples are drawn from the 12-concept discovery sample. **Authoritative:** NO.

## The model

For every candidate derivation D_i, define `Dep(D_i)` as the union of:
1. **Stated dependencies** — the row's own `dependencies[]` field (verbatim).
2. **Stated assumptions** — the row's own `assumptions[]` field, each tagged
   `EXPLICIT` or `USED-UNSTATED` per the existing P1 contract (R6).
3. **Inferred unstated premises** — assumptions this analysis identifies as
   necessary for the derivation to go through, but which no field states.
   **These must be labeled `INFERRED-UNSTATED` and never merged into category
   1 or 2** — this is the single most important discipline this model adds,
   because it is exactly where the Discovery Audit found the most consequential
   findings (see below).

For two derivations D_i, D_j of the same candidate object:

```
Dep(D_i) ∩ Dep(D_j)   — shared premises (stated or inferred)
Dep(D_i) \ Dep(D_j)   — premises unique to D_i
Dep(D_j) \ Dep(D_i)   — premises unique to D_j
```

**A non-empty intersection, especially in category 3 (inferred-unstated),
downgrades the evidentiary weight of treating D_i and D_j as independent
confirmations of anything** — per §16's explicit warning and R9 (duplicate ≠
independent evidence), extended here from "duplicate source" to "shared hidden
premise."

## Worked examples from the audit (real, not hypothetical)

### `knowledgeos-kernel-concept`, Wave 1 common-mode structure

```
Dep(F4) ∩ Dep(F6) ∩ Dep(F7-Model-A) ∩ Dep(F9)  ⊇
   { INFERRED-UNSTATED: "Kernel membership must be justified by ONE
     co-location/consistency-boundary argument" }
```

This shared premise is never stated by any of the four documents. It is
exactly what F8's own falsification result attacks ("relatedness ≠
atomicity"). **Consequence per this model**: F4, F6, F7-Model-A, and F9 must
NOT be counted as four independent confirmations that "the Kernel is a
consistency boundary" — they are four expressions of one untested assumption,
and F8's falsification undermines all four simultaneously, not just the one
document it names.

### `knowledgeos-kernel-concept`, Wave 3 common-mode structure

```
Dep(F12) ∩ Dep(F13) ∩ Dep(F14) ∩ Dep(F15) ∩ Dep(F16) ⊇
   { INFERRED-UNSTATED: "K_t is a well-typed, existing object at every t" }
```

**Semi-explicit case**: one row's own `type_signature` field records
`"total": "UNSTATED", "deterministic": "UNSTATED"` for the central operator Ω
— the corpus flags the hole in a structured field without closing it. This is
an intermediate case between fully-stated and fully-hidden: **recorded here as
`SELF-FLAGGED-UNSTATED`**, a third label alongside `EXPLICIT` and
`USED-UNSTATED`/`INFERRED-UNSTATED`, since it carries different evidentiary
weight (the author knew the gap existed) than a silently-unstated assumption.

### `step-verify-programme`'s five status-classification schemes

```
Dep(E) ∩ Dep(F) ∩ Dep(G) ∩ Dep(H) ∩ Dep(I) ⊇
   { INFERRED-UNSTATED: "verification status is expressible as a finite,
     mutually-exclusive, discretely-enumerable taxonomy" }
```

**Ironic finding, worth restating here**: the label's own audit-output rows
(e.g. S1513, cited in the agent's report) find this exact assumption fails
for the corpus under review — the programme never turns this scrutiny on its
own five schemes.

### Explicit prompt-lineage sharing (a stronger, stated form of common-mode)

F4/F5 and F12–F16 are not merely assumption-sharing — the corpus's own
provenance notes state they arose from **one shared prompt/session**. This is
recorded as a distinct, stronger flag: `SHARED-PROMPT-LINEAGE` — stronger than
`INFERRED-UNSTATED` because it is itself SOURCE-CLAIMED, not inferred.

## Confidence/strength implication (not itself a new field — a usage note)

A future validation pass (P5, out of scope here) that wants to weigh "how many
independent lines of evidence support conclusion X" must compute this
dependency-overlap first. **Naive counting of surface-distinct documents as
independent confirmations, without this step, would overstate robustness** —
demonstrated concretely by Wave 1 (4 documents, 1 real independent argument
once the shared premise is discounted) and Wave 3 (5 documents, 1 continuous
derivation).

## What this model does NOT do

Does not compute `Dep(D_i)` automatically at scale — every example above was
hand-derived by a careful reader, not a script. A future implementation could
mechanize category 1/2 (stated fields already exist and are structured);
category 3 (inferred-unstated) inherently requires judgment and cannot be
fully automated without risking exactly the kind of false precision this whole
audit chain has repeatedly warned against.
