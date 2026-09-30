# H-04 DECISION BRIEF — evidence presentation for P3b per-label bundles

**Status:** decision brief for the human. **Nothing executed or modified**; no S3 script written. The proposed
governance entry (§12) is **not** appended to the log.
**Frozen basis:** P3b core protocol v1.6.4 (`20260924_1242_…-v1.6.4.md`, sha256 `08f10e74…1dec06`, G-LOG-0001).
**Evidence:** read-only inspection of the code and data named in each section (2026-09-24).

---

## 1. The exact frozen H-04 decision statement

- §17, H-04: *"May `row_bundle_v2` serve as a read-only evidence-preparation layer for P3b, with no frozen verdict
  changed? (§11.5, OMQ-01)"*. Blocks: **S3**.
- §27, OMQ-01: *"May `row_bundle_v2` be used as a read-only evidence-preparation layer for P3b (not 'adopt P3A-V2')?
  (H-04)"*. Options: *"use `row_bundle_v2` / V1 bundle plus direct row access / both, compared on the pilot"*.
- §28: *"Before S1–S4: … H-04 (`row_bundle_v2` or V1-plus-rows)"*.
- §18 records the choice per object record: `evidence_presentation: V1-PLUS-ROWS | ROW-BUNDLE-V2`.

## 2. Constraints imposed by §11.5 (and related text)

1. **The P3a verdict** (relationship, basis, type_compatibility) is **frozen and consumed as-is**. No presentation
   choice may change, re-derive or override it.
2. **The P3a evidence presentation** (V1 `row_brief()`) is **known defective**. P3b is **not required to reproduce
   it**.
3. If shown evidence bears against a consumed verdict, the agent records `VERDICT-EVIDENCE-CONFLICT` and the label is
   escalated for H-02. **The roll-up still uses the frozen verdict.**
4. §9.2: only `row_bundle_v2` may be used from V2, never `candidate_bundle_v2` or `derive_reconciliation_v2.py`.
5. §15: `row_bundle_v2` presentation is classed AUTOMATICALLY SAFE ("copies fields, decides nothing").
6. The decline alternative: *"agents receive V1-style bundles **plus** direct read access to the full rows in
   `03-CONTRIBUTIONS.jsonl` (no information is withheld either way; only the presentation differs)"*.

## 3. Exactly what `row_bundle_v2` contains (code: `audit-p3a/v2/scripts/evidence_bundle_v2.py`)

**14 fields per row:** 11 copied from the row (`source_id`, `anchor`, `statement`, `labels`, `types`,
`type_signature`, `dependencies`, `lineage_claims`, `invariants`, `assumptions`, `explicit_date`) plus 3 from
`02-FILES.jsonl` (`provenance`, `status` → `source_role`, `path` → `source_path`).

**Design intent (its own docstring):** *"Evidence reduction is allowed only when it is lossless **with respect to the
decision being made**. version_ref/experiment{}/review_flag remain excluded … (no protocol rule ties them to a
**relationship** decision)"*. V2 was built and validated to be lossless for **P3a pair-relationship** decisions. The
V2 field matrix also records `missing[]` as *"Discarded by both V1 and V2 … a P2b/P3b-level field"*, and
`review_flag` as *"Discarded by both"*.

## 4. Exactly what the "V1-style bundle" is for P3b

V1's `row_brief()` (6 fields: `source_id`, `anchor`, `types`, `statement`, `type_signature`, `explicit_date`) was the
**P3a pair** digest. **P3b's per-label bundle is a different object**: the `_derived.json["reconciliation_objects"]`
entry (`derive_reconciliation_objects.py`). Its fields are aliases, `assumption_register`, `candidate_births`,
family-level `completeness` (12 dimensions), `completeness_absences`, `files_touching`, `group_ids`, `last_seen`,
`lifecycle_candidate`/`evidence`, notations, `pair_count`, `pairs_touching`, provisional layer/roles,
`rationale_evidence`, `row_count` and `working_label`.
**It contains no contribution rows at all.** Under the decline option the rows come only from `03-CONTRIBUTIONS.jsonl`.

## 5. Information available only through the full rows (`03-CONTRIBUTIONS.jsonl`, 27,906 rows)

Row fields present in the data but **absent from `row_bundle_v2`**:

| Field | Rows non-empty | Needed by P3b? |
|---|--:|---|
| `review_flag` (MATH/STAT/TYPE) | 325 | **yes**: §9.8 step 4 derives mathematical_status "from formal rows; STAT-/MATH-QUESTION review_flags" |
| `missing[]` | 1,504 | **yes**: per-row incompleteness bears on absence resolution (§9.8 step 7, §11.4) |
| `completeness` (per row) | ~27,824 present | **yes**: per-row basis of the family roll-up the agent must resolve |
| `experiment` | 580 | later (P5 validation evidence); P3b research records may cite it |
| `version_ref` | 27,899 | P4 membership evidence |
| `scope`, `label_confidence`, `unknown_candidate` | all rows | P1 agent observations (context; see §7) |
| `_batch_id`, `path`, `review_ref`, `unknown_confidence` | — | provenance / rare |

**`02-FILES.jsonl` fields needed by the frozen A.10 temporal-scope algorithm but absent from V2's file metadata:**
`best_historical_date`, `best_historical_date_basis`, `order_evidence`, `mtime_block`.

**Consequence (finding).** As the **sole** presenter, `row_bundle_v2` would **withhold** information that P3b's
own procedure uses. Transferred from P3a to P3b, it would violate its own principle ("lossless with respect to the
decision being made"). **The frozen text is also inaccurate here:** §9.2 says `row_bundle_v2` presents "the fields
V1's `row_brief()` dropped", and §11.5 lists these as including completeness and `missing[]`, but the function copies
neither.

## 6. Researcher-selected filtering or transformation

| Option | Selection or transformation |
|---|---|
| **A: `row_bundle_v2`** | a **fixed field selection** (11 of ~21 row fields) chosen for a different decision (P3a pairs), plus a join of 3 file fields. Deterministic, but it bakes a P3a-oriented researcher choice into P3b inputs |
| **B: label bundle + full rows** | no field selection. The only selection is **mechanical row membership** (rows whose `labels[]` contain the label). If "direct read access" means the agent opens the file ad hoc, **what it read is not recorded**, which is a reproducibility gap (§8) |

## 7. Blinding and hindsight

- Per-label S5 work is **not blinded** by design (blinding applies to S5a cross-object sets, §9E.2). H-04 does not
  change blinding. The PARTIAL-blinding test uses the resolved row `statement`, `type_signature` and anchor quote, which
  are present under both options.
- **Hindsight:** neither option adds later-period material. Both expose `explicit_date`. B also exposes
  `version_ref`, which is source-derived (the version a source refers to), not a later classification.
- **Epistemic labelling (B only):** B exposes **P1 agent observations** (`label_confidence`, `unknown_candidate`,
  `completeness`, `missing[]`, `review_flag`). Under v3.5 R6 and §11.1 these are **assessments, not SOURCE**. The
  agent contract must say so, and they may inform a status only through the evidence they point to.

## 8. Reproducibility

- A: deterministic, given the function version and row hashes.
- B as "ad hoc read access": **not reproducible**. Different agents may read different rows or fields, and nothing
  records it.
- **B realised as a verbatim per-label slice** (every row whose `labels[]` contains the label, all fields, byte-exact
  canonical JSON, plus the `02-FILES.jsonl` record of each cited source verbatim; hashed per batch in
  `P3B-BATCH-SNAPSHOT.json`): **deterministic and complete**. No field is selected and nothing is transformed.
- Measured: for 2,496 of 2,497 labels, P2's `row_count` equals the number of distinct rows listing the label. The
  exception, `open-by-commission-negative-history-category`, has `row_count` 18 but **12 distinct rows**, because some
  rows list it more than once in `labels[]`. A builder must include distinct rows, and record (not "fix") this
  discrepancy.

## 9. Provenance and audit

- Both options can record the choice (§18 `evidence_presentation`) and hash the bundles (§19.5).
- B-verbatim gives the stronger audit: every field an agent saw is the frozen row itself, so a citation
  `[S#### §anchor]` resolves to identical bytes in `03-CONTRIBUTIONS.jsonl`.
- A adds a derived layer whose field set must be justified. That justification exists only for P3a.

## 10. Which later steps the choice affects

| Step | Effect |
|---|---|
| **S3** | the bundle builder (the per-label input slices) and its hash |
| **S3b** | none in substance; the H-19 addendum filters bundles either way |
| **H-19** | none |
| **S4 / S5** | the agent contract (§19.2) must describe the presentation and label P1 assessment fields (B) |
| **S5a/S5b** | indirect only (they read object records and timelines, not bundles) |
| `VERDICT-EVIDENCE-CONFLICT` | expected under **both** (both expose dependencies and lineage claims) |

## 11. Acceptance and rejection conditions

**Option A is acceptable only if** the P3b decisions needing `review_flag`, `missing[]`, per-row completeness and the
A.10 file fields get them another way. That means A plus full-row access, which duplicates B with two
representations that could diverge. **As a sole presenter, A fails §11.5's own constraint** that "no information is
withheld", for P3b's decisions.

**Option B is acceptable if:**
- the bundle is a **verbatim** slice (distinct rows by `labels[]` membership, all fields; `02-FILES` records
  verbatim), with no field selection and no transformation;
- it is deterministic, sorted by `source_id` then `anchor`, and hashed into `P3B-BATCH-SNAPSHOT.json`;
- the row-membership cross-check against P2 `row_count` records the one known duplicate-listing discrepancy;
- the agent contract labels P1 assessment fields as assessments (§11.1);
- `evidence_presentation: V1-PLUS-ROWS` is recorded;
- the P3b label bundle (the `reconciliation_objects` entry) is passed unchanged.

**Either option is rejected if** it alters, re-derives or hides a P3a verdict, or if what the agent saw cannot be
reconstructed from recorded hashes.

## 12. Proposed governance entry (NOT appended; for the human's decision)

> **G-LOG-00NN — H-04 decided: evidence presentation = V1-PLUS-ROWS, realised as a verbatim per-label row slice**
> Decider: the human. **Decision:** `row_bundle_v2` is **not** used as P3b's evidence presenter. S3 builds per-label
> bundles as the unchanged `reconciliation_objects` entry **plus a verbatim slice**:
> - every distinct `03-CONTRIBUTIONS.jsonl` row whose `labels[]` contains the label, with all fields, byte-exact;
> - the verbatim `02-FILES.jsonl` record of each cited source;
> - no field selection and no transformation;
> - sorted by `source_id`, `anchor`, and hashed per batch.
> **Reason:** `row_bundle_v2` is lossless only for P3a pair decisions. It omits `review_flag`, `missing[]`, per-row
> completeness and the A.10 file fields that P3b uses (brief §5), and ad hoc file access would not be reproducible
> (brief §8).
> **Conditions:** P1 assessment fields are labelled as assessments in the agent contract; the one known
> `row_count` duplicate-listing discrepancy is recorded; `evidence_presentation: V1-PLUS-ROWS`.
> **Observation (not a reopening under §26 item 7):** frozen §9.2 and §11.5 overstate `row_bundle_v2`'s coverage. It
> does not copy completeness or `missing[]`. This affects no conclusion under the chosen option. It is recorded for a
> future protocol version.
> **Authorizes:** writing the S3 script for review. **Not** its execution.

**My recommendation:** the entry above (B, realised verbatim). The main alternative is OMQ-01's third option, "both,
compared on the pilot". It is feasible, but it would carry two presentations through S4, and A is already shown to be
incomplete for P3b by inspection, so the comparison would test a known deficiency.

---

**Traceability:** code inspected: `scripts/derive_reconciliation.py` (`row_brief`),
`scripts/derive_reconciliation_objects.py`, `audit-p3a/v2/scripts/evidence_bundle_v2.py` (`row_bundle_v2`),
`audit-p3a/v2/P3A-V2-FIELD-PRESERVATION-MATRIX.md`. Data measured read-only: `03-CONTRIBUTIONS.jsonl` (27,906 rows;
field presence), `20-FAMILIES/_derived.json` (row_count cross-check), `02-FILES.jsonl` (fields).
