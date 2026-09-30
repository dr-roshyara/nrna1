# Evidence-phase schema and derivation rules — r2 (execution-ready; NO corpus read)

| | |
|---|---|
| Kind | design and tooling. ⚠ authority: generated. Fixed **before** any L0 corpus release |
| Files | `SOURCE-MANIFEST.json` · `propositions.json` · `derive_constraints.py` · `test_derive.py` (40 unit checks) · `test_cli.py` (13 CLI contract checks) · `formal_basis.json` · question template `prompts/KNOWLEDGEOS-TEMPORAL-EVIDENCE-MATRIX-TEMPLATE.md` |
| Principle | historical evidence **constrains** formal models; it never selects one. The strongest mechanical outputs are `INCONSISTENT-WITH` and `MODEL-FAMILY-INCOMPLETE-CANDIDATE`; `MODEL-FAMILY-INCOMPLETE` is an L0 research decision, never a machine output |

## 1. Epistemic layers (a closed field `layer`; never merged)

| Layer | Meaning | Counts toward an outcome? |
|---|---|---|
| HISTORICAL-EVIDENCE | exact wording at a byte-verified anchor of a released, hash-pinned source | **yes** |
| RECONSTRUCTION | an assembly of several passages into a sequence or state | no (annotation) |
| INFERENCE | a conclusion beyond the wording | no |
| FORMAL-CONSEQUENCE | what a frozen model implies | no |
| RESEARCH-HYPOTHESIS | a candidate explanation | no |
| GOVERNANCE-DECISION | an L0 act | no (it is recorded, never derived) |

## 2. Source-location manifest (`SOURCE-MANIFEST.json`)

- Built from file names and byte hashes only; **no content was opened**.
- **S-1 "CAP-001 §9":** one filename candidate.
- **S-2 "EPIC-004":** 15 filename candidates.
- Both are `UNRESOLVED-IDENTITY`. **L0 designates the exact file (and, for S-1, the section) in the release act.** A candidate is not a source.

## 3. Evidence graph (minimum fields for auditability)

```
SOURCE (path + sha256) ─► CLAIM (one passage: claim_id = the anchor + exact wording) ─► CELL (one reader's assessment) ─► PROPOSITION ─► MODEL CONSTRAINT
SOURCE ─► TEMPORAL EVENT (top-level `events[]`, own anchor) ─► CONTEXT / SCOPE (`context.scope`)
```

- **Input:** `{"cells": [...], "events": [...]}`. Unknown keys → REJECT.
- **Cell, required fields:**
  - `id`, `claim_id`, `proposition`;
  - `source_slot`, `source_path`, `source_sha256`, `anchor{start_line,end_line}`, `exact_wording`;
  - `historical_date{value,precision}`;
  - `source_claim` and `assessment`, kept separate;
  - `interpretation_confidence∈HIGH|MEDIUM|LOW`: **confidence in the reader's reading of the passage** ("this sentence says X"); **never** confidence that the proposition is historically true, and never a truth score; `layer`, `reader_class`, `reader_id`, `l0_release_id`.
- **Cell, optional fields:** `context{scope,…}`, `events[]`, `precedence[]`, `notes`.
- **Event, required fields:**
  - `id`, `type∈AUTHORIZATION|PROMOTION|ADOPTION|REVOCATION|REASSESSMENT|BAR_CHANGE|EVIDENCE_CHANGE|OTHER`;
  - `source_path`, `source_sha256`, `anchor`, `exact_wording`, `historical_date`, `l0_release_id`.
- **Event, optional fields:** `context`, `precedence`, `notes`.
- **Integrity:**
  - byte-exact anchoring (hash plus quote inside the span) for cells **and** events;
  - one `claim_id` = exactly one passage;
  - one reader assesses a (claim, proposition) pair at most once;
  - `precedence` needs a `basis`, and file or list order is never precedence.

## 4. Outcomes, conflict semantics and diagnostics

- **Per cell:** SUPPORTED · REFUTED · AMBIGUOUS · SILENT · OUT-OF-SCOPE.
- **Per proposition** (only HISTORICAL-EVIDENCE cells count):

| Outcome | Meaning | Constraint |
|---|---|---|
| SILENT | no relevant evidence | none |
| SUPPORTED | positive support, no refutation | positive (per model: CONSISTENT / INCONSISTENT) |
| REFUTED | refutation, no support | negative (the requirement flips) |
| AMBIGUOUS | wording cannot decide | no decisive constraint (open) |
| **CONFLICTING** | both supporting and refuting evidence | **both directional constraints are preserved** (`if_supported`, `if_refuted`; models get `conditionally_inconsistent_with`). The proposition stays **unresolved (open)**. **CONFLICTING ≠ SILENT.** No elimination follows until L0 resolves it |

**Diagnostics** explain an outcome, never change it, and block the INCOMPLETE guard:

| Diagnostic | Mechanical trigger |
|---|---|
| READER-DISAGREEMENT | the same claim (passage) assessed differently by different readers |
| SOURCE-CONFLICT | different claims (passages) point in opposite decisive directions |
| TEMPORAL-CONFLICT | a SOURCE-CONFLICT whose supporting and refuting claims carry disjoint known dates (possible rule change over time; recency never overrides) |
| SCOPE-CONFLICT | a SOURCE-CONFLICT whose sides declare different `context.scope`, or an OUT-OF-SCOPE cell on the proposition |
| SEMANTIC-AMBIGUITY | any counted AMBIGUOUS cell |

The cells stay individually preserved and are listed per proposition.

## 5. Propositions (`propositions.json`, 19 EP ids for EQ-1…13; unchanged)

- A claim is tied to a frozen SPEC-G25-r1 property and its required value, or `property = null` with its gap, or marked structural (EP-12).
- This is the machine form of Appendix A.

## 6. Mechanical derivation and the family guard

| Direction | Per model |
|---|---|
| SUPPORTED (property) | CONSISTENT iff value = required (VACUOUS = HOLDS); INSTANCE-DEPENDENT is flagged |
| REFUTED (property) | CONSISTENT iff value ≠ required |
| SUPPORTED (null property) | CANNOT-EXPRESS |
| REFUTED (null property) | NO-CONSTRAINT |

**When no primary model fits a direction:**
- **MODEL-FAMILY-INCOMPLETE-CANDIDATE** requires **all** of the following (the guard):
  1. a decided outcome (SUPPORTED or REFUTED), not CONFLICTING;
  2. **no diagnostic** (no reader, source, temporal or scope conflict, no semantic ambiguity);
  3. every decisive cell has `interpretation_confidence` **HIGH**;
  4. a corroborated reading: ≥ 2 distinct agreeing readers, or ≥ 1 INDEPENDENT reader.
- Otherwise **MODEL-FAMILY-INCONCLUSIVE**. A CONFLICTING direction can only ever give INCONCLUSIVE.
- **Three levels:**
  - INCONCLUSIVE and INCOMPLETE-CANDIDATE are computational results; a candidate carries `l0_research_decision_required: true`.
  - **MODEL-FAMILY-INCOMPLETE** exists only as an **L0 research decision** confirming a candidate, after ruling out missing context, wrong operation typing, an unreleased predecessor source, a missing variable, a scope boundary or a mis-specified proposition.
  - The engine never emits it (test X11).
- The control MT0 never rescues. Ambiguity is never turned into family failure.
- **Model status:** `INCONSISTENT-WITH <EP ids>` (decided propositions only) or `NOT-ELIMINATED`, plus `conditionally_inconsistent_with`. Nothing is ever selected.

## 7. Formal basis (Gate 1 stays asynchronous)

- `formal_basis.json` pins the model results (sha256 checked at run time) and a status ∈ SECONDARY-REPRODUCED · INDEPENDENT-REPRODUCED · NOT-REPRODUCED. It is currently **SECONDARY-REPRODUCED** (F-LOG-0075).
- Every proposition and flag carries the **full stamp**: status, results path and results sha256.
- A basis change re-evaluates **only the model-constraint layer**; the evidence is never rewritten (tests X12 and C6).
- After Gate 1, the status is changed by a new F-LOG act and the engine is re-run; the re-evaluation is deterministic (test X9).

## 8. Verification of the tooling (synthetic only)

- **Engine integrity:** the committed `derive_constraints.py` has exactly one `validate`, one `derive(evidence, spec, results, basis)` and one CLI path (`main`). This was confirmed against `git show HEAD` (F-LOG-0079).
- `test_derive.py`, **40 unit checks**: 14 validation / REJECT paths, including the legacy `confidence` key being rejected; the 11 required cases; 12 invariants, including that the engine never emits INCOMPLETE and never rewrites evidence.
- `test_cli.py`, **13 contract checks through the real CLI** (subprocess):
  - valid → exit 0 and exactly one JSON document;
  - malformed → exit 2 REJECT;
  - basis hash mismatch or a bad status → non-zero exit;
  - two runs byte-identical;
  - zero evidence → all SILENT;
  - the evidence file is unchanged;
  - bare-list input; non-JSON input rejected;
  - no corpus file in the synthetic root;
  - the real defaults with zero evidence → exit 0, all SILENT, all NOT-ELIMINATED.
- Real zero-evidence output sha256: `f83481209558f699fed328be771f41c9977ee30f249ff088bc26559b2205295c`. Command: `python3 derive_constraints.py <{"cells":[],"events":[]}>`.
