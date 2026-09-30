# Semantic-observation schema r2 (A′): span individuation + a two-level ACT / NORM representation

| | |
|---|---|
| **Authority** | **Human act** (2026-09-30): "Authorize A′ … adopt A′ as the next observation schema". Choice of option E before it: F-LOG-0170 |
| **Status** | **OBSERVATION REPRESENTATION, NOT A THEORY.** It does not assert that sources have two ontological layers, that H-DEONTIC holds, or that governed work is out of scope |
| Supersedes | v1 (`SEMOBS/SCHEMA.md`) for new coding. **v1 records are kept unchanged.** Lineage: v1 event → 1aw span → r2 record |
| Source identity | `SOURCE-IDENTITY.txt`: git blob ids of every source file at the freeze commit |

## 1. Unit: the individuated span record
- One record = one **verbatim source span**, the smallest sufficient span (1aw).
- **Fields:** `record_id` · `v1_event_ids` (lineage) · `source` (file + blob id) · `section` · `span` (verbatim; markdown emphasis markers may be stripped, and that must be disclosed).
- **Uniqueness:** (span, operation, target) must be unique. Events resolving to one span are **one record**. A span carrying an act clause and a norm clause stays **one record with both components**.

## 2. Two independent components per record

**ACT: does the span record an act that actually happened?**
- `act.state` ∈ {ACT_OCCURRED, ACT_ATTEMPTED, ACT_REFUSED, ACT_WITHDRAWN, ACT_REVERSED, NONE, UNKNOWN}.
- `act.operation`: what act (open vocabulary; the v1 operation list is preferred).
- `act.actor` · `act.target` · `act.quote`.

**NORM: what normative / deontic content does the span state?**
- `norm.deontic` ∈ {PERMITTED, REQUIRED, PROHIBITED, AUTHORIZED, UNAUTHORIZED, ELIGIBLE, INELIGIBLE, DELEGATED, STATUS_CONSTRAINT, NONE, UNKNOWN}.
- `norm.about_operation` · `norm.about_actor` · `norm.about_target` (the *governed* act).
- `norm.stated_by`: the act or actor stating it, if identifiable.
- `norm.quote`.

**Both components may be non-NONE for the same record.** They are coded **independently**.

## 3. Epistemic status per coded field
Each coded field carries a status ∈ {SOURCE_FACT, INFERENCE, UNKNOWN, NOT_EVIDENCED}. An INFERENCE states its premise. No silent defaults.

## 4. Critical rules
- **R-A1.** **Never infer an ACT from a NORM.** "X is authorized to begin" is a NORM (AUTHORIZED); it is not evidence that X began. "Implementation does not begin" is a NORM (PROHIBITED / a status constraint); it is **not** an ACT_REFUSED unless an attempt is recorded.
- **R-A2.** ACT_REFUSED / ACT_WITHDRAWN require an **identified proposal or attempt** in the span or its section.
- **R-A3.** A register row **that itself performs** a norm-making act (adopts, authorizes, registers, supersedes, annotates) is an ACT_OCCURRED of that operation. Its stated content is additionally a NORM.
- **R-A4.** UNKNOWN when the span does not decide. It is counted, and excluded from support counts.

## 5. Datasets (derived, never mixed)
- **DATASET A (ACT):** records with `act.state` ∉ {NONE, UNKNOWN}.
- **DATASET N (NORM):** records with `norm.deontic` ∉ {NONE, UNKNOWN}.

One record may be in both; they share provenance. **Support is computed separately per dataset and never aggregated.**

## 6. Support vocabulary
ACT-EMPIRICALLY-SUPPORTED · NORM-EMPIRICALLY-SUPPORTED · WEAK · NOT-DEMONSTRATED · UNTESTABLE · UNKNOWN. Each is reported with: spans · independent decision clusters · repetition · breadth · unanchored · ambiguity.
