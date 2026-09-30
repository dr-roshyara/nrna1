# EXPERIMENTAL — DOES NOT ALTER KNOWLEDGEOS PRODUCTION TRUTH
# P3A-V2 Candidate-Generation Specification

**Phase:** P3a candidate generation. **Purpose:** formal rules for every
mechanism that may propose a candidate pair for adjudication. **Date:**
2026-09-21. **Status:** EXPERIMENTAL. **Input:** `_derived.json[families,groups,
nodes]`, `02-FILES.jsonl`. **Output:** `P3A-V2-CANDIDATES.jsonl`.
**Implementation:** `audit-p3a/v2/scripts/derive_reconciliation_v2.py`.
**Authoritative:** NO.

## Governing principle (R13, restated for this spec)

Every mechanism below **derives a candidate signal**. **None of them determine
a relationship, a basis, or a type_compatibility.** That remains exclusively a
P3a adjudication decision (unchanged, out of scope for this repair). A
mechanism's "confidence" grades how mechanically reliable the SIGNAL is — never
how likely the eventual relationship verdict is.

## Mechanism A — WITHIN-GROUP (unchanged from V1)

**Rule:** every pair of members inside a P2a candidate group with ≥2 members.
**Confidence:** `GROUP` (inherits whichever of P2a's 7 signal types produced the
group — not independently re-graded here). **Evidence carried:** `group_id`,
`group_kind`, `why_grouped`. **Provenance:** not computed per-pair for this
mechanism (a group can span many rows across many files; V2 does not attempt a
group-level provenance summary in this repair — a disclosed limitation).

## Mechanism B — DEPENDENCY

**Rule:** a row's `dependencies[]` array contains a string that is an exact
match to another real, registered label. **Confidence:** `MECHANICAL-EXACT`
(the highest — this is a structured field, not free text). **Evidence carried:**
`source_id`, `row_label`, `dependency_value`, the row's `statement`.
**Provenance:** `source_provenance` (citing row's file), `target_provenance`
(dominant provenance across the target label's whole family — PRIMARY if any
row is PRIMARY, else SECONDARY-SYNTHESIS, else PROVENANCE-UNRESOLVED).
**Never present in V1.**

## Mechanism C — LINEAGE_SOURCEID

**Rule:** a `lineage_claims[].target` value matches the bare source_id pattern
(`^S\d{4}$`) and that source_id is owned by exactly one other label (via a
direct `source_id → owning label(s)` index built from `_derived.json`, never a
"representative row" substitute for a different source_id — this is the exact
mechanism that produces the correct resolution for RP0526). **Confidence:**
`HIGH` (unambiguous ownership) or `AMBIGUOUS` (the source_id is shared by ≥2
labels — recorded, never auto-resolved to one). **Evidence carried:**
`lineage_target_sid`, `lineage_kind`, `lineage_quote`. **Provenance:**
`source_provenance` + `target_provenance`, the latter read directly from the
exact target source_id's own file (not a label-wide summary, since this
mechanism identifies one specific row). **Never present in V1.**

## Mechanism D — LINEAGE_EXACT (unchanged from V1)

**Rule:** a `lineage_claims[].target` value is an exact string match to another
label's own working_label, a notation, or an alias. **Confidence:** `HIGH`.
**Evidence carried:** `lineage_target_label`, `lineage_kind`, `lineage_quote`.
**Provenance:** now added in V2 (absent in V1).

## Mechanism E — LINEAGE_SUBSTRING

**Rule:** a `lineage_claims[].target` value is not an exact match to any label
(mechanism D) and is not a bare source_id (mechanism C), but **contains a real,
registered label as a contiguous substring** (`tl in target`, plain Python
substring test — never edit-distance, embeddings, or any probabilistic
technique). Where more than one real label is a substring of the same target
string, **the longest matching label wins** (deterministic tie-break, disclosed
and fixed during this repair's own testing — see VALIDATION-REPORT.md).
**Confidence:** `HIGH` if the matched substring is immediately followed by a
possessive/descriptive marker (`'s`) — e.g. `"champion-challenger-model-
promotion-lifecycle's governed promotion workflow (Step 45)"` — else `MEDIUM`.
**Minimum label length 9 characters**, to avoid trivial short-token false
matches. **Evidence carried:** `lineage_target_string` (the full, un-truncated
target), `matched_label`, `lineage_kind`, `lineage_quote`. **Provenance:**
source + target (label-wide dominant provenance, as in mechanism B). **Never
present in V1.**

## Mechanism F — PROSE_TRIGGER

**Rule:** a row's `statement` field contains one of a **fixed, closed
vocabulary** of 16 relationship-verb phrases (`extends`, `extending`,
`extension of`, `corrects`, `correcting`, `correction of`, `refines`,
`refining`, `refinement of`, `replaces`, `replacing`, `replacement of`,
`derives from`, `derived from`, `continues`, `continuing`, `continuation of`,
`specializes`, `specializing`, `specialization of`, `redefines`,
`redefining`, `redefinition of`) **and** another real label's exact string
(≥9 characters) appears **in that same statement**. **This mechanism is a
safety net only** — it is skipped entirely for any row that already has a
structured `dependencies[]` or `lineage_claims[]` entry pointing at the same
target label, to avoid double-signaling. **Confidence:** always `LOW` — this
is the only mechanism operating on unstructured prose rather than a
P1-structured field, and is explicitly, permanently the lowest-trust signal
in the system. **Exclusion (disclosed mid-repair fix):** the 8 single-word
(no-hyphen) labels in the whole corpus (`pks`, `capability`, `mission`,
`provenance`, `KnowledgeOS`, `evidence`, `viewpoint`, `ontology`) are excluded
from serving as this mechanism's matched target, because they are common
generic vocabulary that produced a 74.1%-false-positive-dominated candidate set
before this exclusion (538→102 pairs, an 81% reduction, verified in the
VALIDATION-REPORT). **Evidence carried:** `matched_verbs` (the exact phrase(s)
found), the full `statement`. **Never present in V1; never determines a
relationship — an adjudicator must independently verify the prose actually
supports one.**

## Mechanism G — cross-group support (not a separate mechanism; a property of B-F)

A11 requires cross-group pairs where "a lineage_claim links them." **In V1,
only mechanism D supported this. In V2, mechanisms B, C, D, E, and F all
naturally generate cross-group candidates** (none of them require or check P2a
group co-membership) — this satisfies A11's cross-group requirement far more
completely than V1's single, narrow implementation of it.

## Mechanism H — provenance-awareness (a property of every mechanism, not separate)

Every signal from every mechanism (except A, a disclosed limitation) carries
`source_provenance` and `target_provenance`, computed from `02-FILES.jsonl`'s
existing, unmodified `provenance` field — **never inferred, never guessed,
never flattened**. A candidate whose evidence spans PRIMARY and
SECONDARY-SYNTHESIS material is visibly marked as such (see RP0526's regression
test in the VALIDATION-REPORT: `source_provenance=PRIMARY`,
`target_provenance=SECONDARY-SYNTHESIS`, exactly correct and exactly what V1
could never show).

## What is deliberately NOT implemented

- **Semantic-similarity or embedding-based matching** — would violate the
  "deterministic matching rules" requirement (§6E) and introduce exactly the
  "uncontrolled fuzzy matching" the task explicitly forbids.
- **Chronology-based candidate generation** — A11 does not require it, and
  R1/R1a explicitly warn that source_id/mtime order is ingestion order, not
  argument order; using raw chronology to *generate* candidates (as opposed to
  informing an already-generated candidate's adjudication) would risk exactly
  the R1 violation the protocol warns against.
- **Automatic promotion of any candidate to a relationship** — verified absent
  in all 8 mechanisms by direct code inspection and by the synthetic fixture
  suite (fixtures 1-6, 8, 14 all assert a candidate is generated with no
  relationship/basis/type_compatibility field ever set).
