# KNOWLEDGEOS PHASE-1 CONTINUATION PROTOCOL — P3b OPERATING PROTOCOL

**Status: PROPOSED — pending independent review and human approval. Not executable until §26 approval is recorded.**
**Class: operating annex to Master Protocol v3.5. v3.5 is not modified by this document.**
**Written:** 2026-09-24 · repository HEAD at writing: `3bb068e7b` · P3A freeze: `125cfe8371`, `c9e76918b`

> Reading rule. This document is self-contained: a researcher who has never seen the conversation
> that produced it must be able to execute it from the repository alone. Every factual statement
> about existing state carries its source. Every methodological decision carries
> **Decision · Evidence · Alternatives · Reason · Remaining uncertainty** (§ "Decision register", D-01…D-20).
> Every question the evidence does not settle is an **OPEN METHODOLOGICAL QUESTION** (OMQ-01…OMQ-12, §27)
> and is not silently resolved anywhere in the text.

---

## Table of contents

1 Purpose · 2 Scope · 3 Corpus boundary · 4 Identity model · 5 Existing-state assumptions ·
6 Relationship to Master Protocol v3.5 · 7 Relationship to existing post-P3a methodology ·
8 Phase/state model · 9 P3b definition · 10 Units of analysis · 11 Evidence model ·
12 Relationship model · 13 Research-signal model · 14 Temporal/hindsight controls ·
15 Automation boundary · 16 AI interpretation boundary · 17 Human governance boundary ·
18 Provenance requirements · 19 Batch protocol · 20 Quality gates · 21 Audit protocol ·
22 Failure/recovery rules · 23 Stopping rules · 24 Output artifacts · 25 Acceptance criteria ·
26 Change control · 27 Open methodological questions · Decision register · 28 Execution readiness

---

# 1. PURPOSE

Complete Phase 3 of Master Protocol v3.5 over the already-admitted Phase-1 corpus, by executing the
**per-object roll-up (P3b)** as a reproducible, auditable method, so that every one of the 2,497
object labels produced by P2 carries evidence-backed per-object statuses (semantic, type, mathematical),
inspected births, a settled layer, resolved absences and source-supported dependency edges.

P3b is not a historical summary. Its output is **evidence infrastructure for theory reconstruction**:
the input on which v3.5's P4 (v1.2 membership), P5 (validation), P6 (governance) and P7 (synthesis) operate.

Governing order (v3.5 headline, unchanged):

```
RECONSTRUCT FIRST → RECONCILE LATER → CANONICALIZE LAST → SYNTHESIZE WITH PROVENANCE
```

P3b is **reconciliation**. It canonicalizes nothing and synthesizes nothing.

---

# 2. SCOPE

**In scope**
- The P3b per-object roll-up for the 2,497 labels in `20-FAMILIES/_derived.json["reconciliation_objects"]`.
- Targeted absence searches over the Phase-1 corpus (§3) for completeness dimensions recorded as
  `NOT-EVIDENCED-IN-CAPTURE`.
- A research-signal register (§13) running alongside P3b, which never changes P3b verdicts.
- The P3 close (v3.5 A11 TERMINAL) and the hand-over to P4.

**Out of scope for this protocol**
- Re-running or regenerating P1, P1c, P2a, P2b, or P3a (§4 of the commission; D-04).
- Re-adjudicating P3a pairs. Whether the 414-pair affected set is re-adjudicated is a human decision
  (OMQ-02); if approved, it is governed by a separate commission, not by this document.
- P4–P7 execution. This protocol ends at the P3 close and the P4 hand-over package (§8, §24).
- Any file outside `02-FILES.jsonl` (§3).
- Theory conclusions, canonical adoption, or methodology changes (§17).

---

# 3. CORPUS BOUNDARY

## 3.1 The rule

> **`docs/knowledgeos/chronological-read/02-FILES.jsonl` is the authoritative Phase-1 inclusion registry.**
> A file is in the Phase-1 corpus if and only if it has a record in that file.

Hierarchy: **`02-FILES.jsonl` → authoritative corpus · `S####` → source identity within it ·
F-manifest (`docs/knowledgeos/list_of_files_to_read.log`) → excluded secondary population.**

## 3.2 Measured facts (2026-09-24, from direct computation over the committed files)

| Fact | Value | Source |
|---|--:|---|
| Records in `02-FILES.jsonl` | 2,779 | line count |
| S-id range / positions / gaps | S0001–S2926 / 2,926 / 147 | computed; the gaps are roadmap positions with no record |
| `status = CONTENT` / `FIREWALL-LIMITED` | 2,767 / 12 | field count |
| `provenance = PRIMARY` / `SECONDARY-SYNTHESIS` / `PROVENANCE-UNRESOLVED` | 2,026 / 739 / 14 | field count |
| Contribution rows (`03-CONTRIBUTIONS.jsonl`) | 27,906 | line count |
| F-manifest rows not represented in `02-FILES.jsonl` | 1,496 (1,491 distinct path strings) | path join |

Consequence: the S-range `S0001–S2926` is **not** an inclusion list. A range-based enumeration would
fabricate 147 files. Every enumeration step reads `02-FILES.jsonl`, never a range.

## 3.3 Explicitly outside Phase 1

- the 1,496 F-manifest rows not represented in `02-FILES.jsonl`;
- F-only `theory-extraction/reconstruction/` material (all 43 such paths are F-only);
- the 18 truncated F-manifest paths (filenames cut at the first space; not resolvable to a file);
- the F-manifest's self-entry (`F3081`);
- every other file not represented in `02-FILES.jsonl`, regardless of whether it exists on disk.

## 3.4 Firewalled and secondary files inside the boundary

- **`FIREWALL-LIMITED` (12):** inside the registry, never read, never quoted, never inferred (v3.5 A0).
  An absence search that would require reading one records `FIREWALL-BLOCKED` for that file (§11.4).
- **`SECONDARY-SYNTHESIS` (739):** inside the corpus and citable, but carry the † mark (v3.5 A13) and are
  subject to the hindsight rules of §14 (D-11).
- **`PROVENANCE-UNRESOLVED` (14):** citable with † and a `provenance-unresolved` flag. They cannot be
  the sole basis of any `ESTABLISHED-*` or `FOUND` verdict (D-11).

## 3.5 Boundary enforcement

Gate **G-01** (§20) rejects any output record that cites an S-id absent from `02-FILES.jsonl`, any F-id,
or any path not in `02-FILES.jsonl`. Boundary changes are human decisions (§17, H-08).

---

# 4. IDENTITY MODEL

| Namespace | Form | Meaning | Owner | Status in this protocol |
|---|---|---|---|---|
| Source | `S####` | one Phase-1 source file; identity = `source_id + commit + sha256` (v3.5 R11) | P0/P1 | immutable, consumed |
| Contribution | row in `03-CONTRIBUTIONS.jsonl`, anchored by `source_id + anchor` | one extracted contribution | P1 | immutable, consumed |
| Object label | `working_label` string | a P2 candidate object handle; **a handle, not an identity claim** (v3.5 R5) | P2 | immutable, consumed |
| Group | `group_id` | P2a candidate grouping; not an identity claim | P2a | immutable, consumed |
| Pair | `RP####` (`pair_id`) | one P3a pair verdict | P3a | frozen, consumed |
| P3b batch (first run) | `OB0001`–`OB0030` | per the committed manifest | P3b run 1 | see §5.4 |
| P3b batch (this protocol) | `OB####-R2` | a batch under this protocol's persisted contract | this protocol | new |
| Research signal | `P3B-RS-#####` | one research-signal record (§13) | this protocol | new |
| Escalation | `P3B-ESC-####` | one escalation record (§22) | this protocol | new |
| Correction | `P3B-COR-####` | one correction/supersession record (§12.3) | this protocol | new |

Rules:
1. **F-ids never appear** in any artifact produced under this protocol (D-03).
2. IDs carry no semantics; they are never reused; they are never renumbered.
3. The `P3B-` prefix exists because the separate F-lane already mints `RO-`, `T-`, `TH-` and `CMP-` ids
   (`knowledgeos_theory_chronological_extraction/`). Namespace collision is a recorded failure mode in this
   repository (session log 2026-09-22, "naming collision").
4. A label is never merged, renamed or split by P3b. A suspected identity is recorded as a research
   signal (`POSSIBLE-DUPLICATE`, `HIDDEN-DISTINCTION`), never enacted (v3.5 R5, R16).

---

# 5. EXISTING-STATE ASSUMPTIONS

Each assumption is checked mechanically by the pre-flight step (§19.1, `S0-PREFLIGHT`). If any check fails,
execution does not begin.

## 5.1 Frozen and complete (consumed, never regenerated)

| Phase | Artifact(s) | Terminal evidence |
|---|---|---|
| P0/P0.5 | `00-CORPUS-SNAPSHOT.txt`, `00-ROADMAP-VALIDATED.jsonl`, `01-BATCH-MANIFEST.jsonl` | committed `125cfe8371` |
| P1/P1c | `ledger/`, `02-FILES.jsonl`, `03-CONTRIBUTIONS.jsonl`, `08-OVERLAP-REGISTER.jsonl`, `09-READ-STATUS.jsonl`, `11-*`, `12-SOURCE-REGISTER.md` | `.claude/CONTEXT.md` 2026-09-21: "PHASE 1 + 1c COMPLETE" |
| P2a/P2b | `20-FAMILIES/` (2,497 labels, 2,497 families) | same entry: "PHASE 2 … COMPLETE, TERMINAL" |
| P3a | `31-RECONCILIATION-PAIRS.jsonl` (1,793 pairs), `ledger-p3a/RP0001–RP0030`, `_batch_manifest_p3a.jsonl` | `09-ORCHESTRATOR-FLAGS.md` "Final tally … TERMINAL-asserted" |
| P3a audit | `audit-p3a/**` including `P3A-FROZEN-BASELINE.md` | frozen `125cfe8371`, `c9e76918b` |

## 5.2 P3a verdict distribution (input to P3b)

`relationship`: UNWITNESSED 684 · EXTENSION 450 · CONTINUATION 199 · REFINEMENT 140 · DERIVED-FROM 94 ·
SPECIALIZATION 80 · HOMONYM 61 · SAME 45 · REPLACEMENT 19 · REDEFINITION 17 · INDEPENDENT 4.
1,120 pairs carry `notes_for_p3b`. (Computed from `31-RECONCILIATION-PAIRS.jsonl`.)

## 5.3 P3a reliability — measured limitations that P3b inherits

From the frozen audit trail:
- Mechanical integrity of P3a: **PASS** (`P3A-QUALITY-GATE-MEMO.md` §7).
- Semantic validity on the 158-pair high-stakes slice (SAME + REPLACEMENT + DERIVED-FROM): **20.9% exact /
  75.3% coarse** agreement on blind re-derivation (`P3A-QUALITY-GATE-MEMO.md` §4, §9).
- Root cause: `row_brief()` in `derive_reconciliation.py` stripped `dependencies[]`, `lineage_claims[]`,
  `invariants[]`, `assumptions[]` and file provenance from the evidence bundle
  (`P3A-IMPLEMENTATION-CONFORMANCE-AUDIT.md`; `KSME-20-P3A-ADJUDICATION-CONTRACT.md` §1).
- `UNWITNESSED` does double duty (bounded negative vs. no search), and no UNWITNESSED verdict carries the
  `negative_verdict_label` v3.5 A11 implies: 684 / 0 (`KSME-20-P3A-ERROR-TAXONOMY.md` §4).
- Bounded affected set: **414 pairs** = 257 evidence-loss-affected (A) ∪ 158 high-stakes (C) ∪ 28
  NEGATIVE-CENSUS anomalies (B) (`KSME-21-EVIDENCE-REPAIR-REPORT.md` Phase 3).
- A repaired candidate-generation and evidence-bundle engine (**P3A-V2**, `audit-p3a/v2/`) exists and is
  validated for candidate generation only, **not adopted** (`KSME-20-FORK-DECISION.md`).

**Measured on 2026-09-24 for this protocol (reproducible computation, recorded in the pre-flight):**

| Set | Pairs | Labels touching the set |
|---|--:|--:|
| C (high-stakes) | 158 | **260** — matches the quality-gate memo §8 |
| A ∪ C | 391 | **477** |
| B (28 pairs) | not persisted as a list | **not computable** — the keyword screen's output was never written to disk |
| A ∪ B ∪ C | 414 | ≥ 477 (exact value unknown until B is persisted) |

Consequence: the quality-gate memo's "2,237 unaffected labels" was computed against C only. Against the later,
broader affected set, at least 477 labels (19.1%) are exposed. This is a **finding**, not a correction of
the memo; the memo was correct for the set it measured.

## 5.4 P3b first run (OB0001–OB0003)

| Fact | Value | Source |
|---|---|---|
| Planned batches / labels | 30 / 2,497 | `20-FAMILIES/_batch_manifest_p3b.jsonl` |
| Batches executed | OB0001 (80), OB0002 (80), OB0003 (81) = 241 labels | `ledger-p3b/OB000{1,2,3}/objects.jsonl` |
| Manifest status of all 30 | `PENDING` (none marked DONE) | manifest |
| Mechanical verification | **PASS ×3** (`verify_reconciliation_objects_batch.py`, run read-only 2026-09-24; working tree unchanged afterwards) | this protocol's discovery |
| Agent instructions used | **not persisted** — given inline by the orchestrator (`P3A-QUALITY-GATE-MEMO.md` §8: "My own P3b roll-up instructions (given to OB0001-0003 before this pause)") | memo |
| Exposure | 25 of 241 touch C; 45 of 241 touch A ∪ C | computed |
| Why stopped | paused by the human to investigate P3a reliability; "PAUSED, not abandoned" | `.claude/CONTEXT.md`; session log 2026-09-20 |

## 5.5 Gate state for OB0004+

No gate record authorizes OB0004+. Recorded human-decision points still open
(`KSME-20-FORK-DECISION.md` "Next step is a human decision on (a)(b)(c)"; `P3A-QUALITY-GATE-MEMO.md` §8):
(a) implement the Adjudication Contract fix order; (b) adopt P3A-V2 as the input layer; (c) close the two
confirmed relationship-ontology gaps; plus the memo's tiered-resumption recommendation. Those are OMQ-01…OMQ-04.

## 5.6 Scale facts that shape the method

- 20,107 `NOT-EVIDENCED-IN-CAPTURE` completeness dimensions across the 2,497 labels need an absence search
  (v3.5 A11 "absences"). This number rules out AI-only searching (D-08).
- 1,120 labels have no P3a pair; 33 labels have zero contribution rows (DORMANT in capture).

---

# 6. RELATIONSHIP TO MASTER PROTOCOL v3.5

**Decision D-01:** this document is an **operating annex** under v3.5, not a successor master protocol and
not a competing one.

| | |
|---|---|
| **Inherited unchanged from v3.5** | R0–R20 in full; A11 per-object roll-up semantics and all its closed lists; B3 layers; B4 status vector; B6 worked cases; FINAL RULES; the P4–P7 definitions (A12–A13) and the GATE |
| **Changed** | nothing in v3.5's text. Where this annex is stricter than v3.5 (for example, persisted agent contracts, mandatory negative-search labels, SECONDARY-SYNTHESIS hindsight rule), the stricter rule applies to P3b only |
| **Newly introduced** | the P3a→P3b input-exposure model (§9.3); the persisted agent contract (§19.2); a two-stage absence search (§11.4); a research-signal register (§13); hindsight controls (§14); the automation boundary (§15); per-batch quality gates and blind audit (§20–21); correction and supersession records (§12.3); explicit human decision points (§17) |
| **Postponed** | re-adjudication of the 414-pair set (OMQ-02); relationship-enum extension (OMQ-03); the P4/P5 extension candidates in `audit-p3a/derivation-discovery/P4-P5-PROTOCOL-EXTENSION-CANDIDATES.md` (OMQ-10) |
| **Valid from P4–P7** | all of A12–A13 as written; P3b's P4 hand-over package (§24) is shaped to v3.5 A12's input needs |
| **Transition back** | P3b terminal (§23) → v3.5 A11 TERMINAL → `30-RECONCILIATION.md` → v3.5 A12 P4. Nothing in this annex governs P4+. |

**Historical attribution (unambiguous):** P0–P3a and the first P3b run (OB0001–OB0003) were governed by
v3.5 alone. Work performed after this annex's approval is governed by v3.5 **plus** this annex.

Methodology freeze note: `.claude/CLAUDE.md` freezes methodology "unless PublicDigit implementation exposes a
genuine deficiency." This annex responds to a measured deficiency: P3b cannot resume reproducibly because v3.5
has no P3 decision procedure (`KSME-20-P3A-ERROR-TAXONOMY.md` §5), P3b's first-run instructions were never
persisted (§5.4), and its inputs carry a measured, bounded defect (§5.3). Whether that justifies an annex is
itself a human decision (H-07).

---

# 7. RELATIONSHIP TO EXISTING POST-P3a METHODOLOGY

Two post-P3a methodologies exist in the repository. Neither governs P3b.

| Methodology | Location | Why it does not govern this phase |
|---|---|---|
| F-lane: *KnowledgeOS Master Protocol — Chronological File-Level Derivation* (Phase-1) and *Step-2 Theory Construction* (Phase-2), under Research Architecture v1.1 (frozen) | `docs/knowledgeos/knowledgeos_theory_chronological_extraction/prompts/` | Keyed on the F namespace and the F-manifest; the unit is the complete file, not the P2 object label; it does not reference v3.5, P3b, or `02-FILES.jsonl` as its registry (searched 2026-09-24: 0 occurrences of "v3.5", "prompt3-optimized", "P3b"). Currently stopped: F0031–F0040 "PROVENANCE-COMPROMISED", F0041+ HOLD (`KNOWLEDGEOS-RESEARCH-STATE.md`) |
| `audit-p3a/` proposals (KSME-20 Adjudication Contract, KSME-21 repair, P3A-V2, derivation-discovery) | `chronological-read/audit-p3a/` | All explicitly *proposals, not adopted* (each document says so). This annex **consumes** them as evidence and routes their adoption to human decisions (OMQ-01…04) |

**Interaction rules (D-18):**
1. No IDs are exchanged. The F-lane's F0001–F0040 all map to S-ids in `02-FILES.jsonl` (measured), so there is
   no scope collision today; there is also no licence to import F-lane conclusions.
2. An F-lane finding may enter P3b only as a research signal with `origin: EXTERNAL-LANE` and `epistemic_class:
   DERIVED` (v3.5 R8). It never changes a P3b status.
3. The F-lane protocol's §1 finding — P3A's `best_historical_date` wrong for 3 of 10 files because it takes the
   minimum explicit date, which for a synthesis document is a date it *cites* — is adopted here as **evidence**
   for the hindsight rule of §14.2, not as a rule imported from that lane.

---

# 8. PHASE / STATE MODEL

```
v3.5  P0 ─ P0.5 ─ P1 ─ P1c ─ P2a ─ P2b ─ P3a ══ P3A FREEZE (125cfe8371, c9e76918b)
                                                  │
                                    ┌─────────────┴──────────────┐
                                    │  P3b first run OB0001–0003  │  (v3.5 alone; preserved, §5.4)
                                    └─────────────┬──────────────┘
                                                  ▼
                         THIS ANNEX (after approval, §26)
   S0 PREFLIGHT → S1 INPUT FREEZE → S2 TIERING → S3 MECHANICAL PREP → S4 PILOT-R2
        → S5 BATCHES (Tier U) → S6 TIER-X DISPOSITION (per H-02) → S7 CLOSE
                                                  ▼
                           v3.5 A11 TERMINAL → 30-RECONCILIATION.md
                                                  ▼
                                   v3.5 A12 P4 → P5 → P6 → P7 → GATE
```

State of each label (field `p3b_state`, one value at a time):

```
NOT-STARTED → PREPARED → ASSIGNED → ROLLED-UP → VERIFIED → AUDITED → ACCEPTED
                                        │            │          │
                                        └──── FAILED ◄┘──────────┘ → (re-run in a new batch run)
PROVISIONAL-HELD   (Tier X, pending H-02)          BLOCKED (escalated, §22)
```

**P3b ↔ re-adjudication ordering (answers commission §6).** P3b for **Tier U** labels (§9.3) runs first and
does not wait for any re-adjudication, because those labels do not consume any affected pair. P3b for **Tier X**
labels is **re-entered** only after H-02 is decided: either after a separately-commissioned re-adjudication of
the affected pairs produces new verdicts, or after an explicit human decision to accept the frozen verdicts with
the limitation recorded. P3b is therefore **partly sequential, partly deferred — never parallel with a
re-adjudication of the pairs it consumes.**

---

# 9. P3b DEFINITION

## 9.1 Objective

For every label in scope, produce one roll-up record answering v3.5 A11 "PER OBJECT": semantic_status,
type_status, mathematical_status, births inspection, primary_layer + secondary_roles, absence resolution,
and typed dependency edges, each with its basis — **without deciding any pair verdict** (pairs are P3a's).

## 9.2 Input corpus and input artifacts

Corpus: §3. Artifacts (all read-only):

| Artifact | Used for |
|---|---|
| `20-FAMILIES/_derived.json["reconciliation_objects"]` | per-label bundle: candidate_births, lifecycle, completeness + absences, provisional layer, rationale, assumptions, files_touching, pairs_touching, notations, aliases, group_ids |
| `31-RECONCILIATION-PAIRS.jsonl` | the pair verdicts rolled up (never re-decided) |
| `03-CONTRIBUTIONS.jsonl` | the label's rows; absence stage 1 |
| `02-FILES.jsonl` | boundary, provenance, dates, date basis, `order_evidence` |
| Corpus files named in `02-FILES.jsonl` | absence stage 2 (§11.4); births inspection against source (v3.5 A11) |
| `20-FAMILIES/<label>.md` | the P2b family narrative |
| `09-ORCHESTRATOR-FLAGS.md` | known corpus failure modes (false-cognate short codes, truncation, shared registries) |
| `audit-p3a/**` | exposure sets; reliability evidence |

## 9.3 Input-exposure model (tiering)

A label's roll-up is only as reliable as the pair verdicts it consumes (`P3A-QUALITY-GATE-MEMO.md` §8: the
roll-up is "a direct, undiluted lookup"). Therefore:

- **Tier U (unexposed):** the label touches no pair in the persisted affected set `AFFECTED.jsonl` (§19.1 S1).
- **Tier X (exposed):** the label touches ≥ 1 affected pair. Its roll-up is `PROVISIONAL-HELD` until H-02.
- **Tier Z (pairless):** the label touches no pair at all (1,120 labels). Its semantic_status follows v3.5's
  "trivially RECONCILED" reading **only if** OMQ-05 confirms that reading (the first run applied it:
  `ledger-p3b/OB0002` "pairs_touching is empty … trivially RECONCILED per protocol").

Tier assignment is mechanical and recorded per label with the affected pair ids that caused it.

## 9.4 Pair and batch construction

- P3b constructs **no new pairs**. A connection P3b notices between two labels that share no P3a pair is a
  research signal (`UNRESOLVED-RELATIONSHIP`), never a pair verdict.
- Batches: a label is never split across batches. Batches are bin-packed by the existing weight
  (`row_count + 3·pair_count + 2·|absences|`, `plan_reconciliation_objects_batches.py`), **within a tier**.
- Batch size is fixed at plan time and recorded in the manifest (OMQ-09 on the value).

## 9.5 Relationship taxonomy consumed and produced

Consumed: P3a's 11 relationship values, 4 basis values, 4 type-compatibility values (v3.5 A11, B4).
Produced: per-object statuses (B4 closed lists) and dependency edges with kind ∈ {DEFINITIONAL, DERIVATIONAL,
USAGE, EXPLANATORY, VALIDATION, GOVERNANCE} (v3.5 A11). See §12.

## 9.6 Evidence requirements — §11. Ambiguity — §12.4, §13. Escalation — §22. Stopping — §23.
## 9.7 Quality gates — §20. Audit — §21. Provenance — §18. Outputs — §24. Acceptance — §25.

## 9.8 Per-label procedure (the agent's fixed order)

```
FOR label IN batch (sorted by working_label):
  1  read the label bundle, its family .md, and every row the bundle cites
  2  semantic_status   ← roll-up of pairs_touching ONLY (rule table §12.2); no pair is re-judged
  3  type_status       ← from completeness.type_signature / formal_definition evidence
  4  mathematical_status ← from formal rows; STAT-/MATH-QUESTION review_flags; NOT-APPLICABLE only with reason
  5  births            ← inspect each CANDIDATE-*-BIRTH against its SOURCE ROW AND FILE (§14.2):
                          ESTABLISHED-*-BIRTH[S] | MOVED[S_earlier, quote] | UNORDERED-BLOCK[block] |
                          NOT-EVIDENCED-IN-CAPTURE
  6  layer             ← settle primary_layer + secondary_roles (B3), citing rows
  7  absences          ← consume the stage-1 search record (§11.4); do stage-2 whole-file reads where
                          stage 1 produced hits; resolve each dimension to FOUND[S §anchor] |
                          GENUINELY-UNDEFINED-AFTER-CENSUS | FIREWALL-BLOCKED | ESCALATED
  8  dependency edges  ← only with a source row that states the dependency; co-occurrence ≠ edge
  9  what_says_this / what_would_make_this_wrong ← one line per status
 10  research signals  ← record any §13 signal observed; never alter 2–8 because of a signal
 11  self-check        ← §20 per-record checks; then NEXT label
```

---

# 10. UNITS OF ANALYSIS

| Unit | Role |
|---|---|
| **Object label** (`working_label`) | the unit of roll-up, batching, verification and acceptance |
| Contribution row | the unit of evidence citation |
| Source file (`S####`) | the unit of provenance, date, track, and of stage-2 absence reading (always read whole, v3.5 R2 and the KSME-22D finding) |
| P3a pair | consumed input only |
| Research signal | the unit of research discovery (§13), separate from roll-up |

Why the label and not the file: P3b answers v3.5 A11's per-object questions; the file-level unit belongs to the
F-lane and to stage-2 reading. Using files as the P3b unit would restart P2 (D-04).

---

# 11. EVIDENCE MODEL

## 11.1 Epistemic classes (every claim in every output carries exactly one)

| Class | Meaning | May set a P3b status? |
|---|---|---|
| `SOURCE` | stated in a corpus file, cited `[S#### §anchor]` with a verbatim quote | yes |
| `INFERENCE` | follows from cited SOURCE by a stated reasoning step | yes, with basis `INFERRED` and the step written out |
| `DOMAIN-INTERPRETATION` | uses outside knowledge (mathematics, logic, DDD, statistics …) to read a source | no — may only annotate |
| `HYPOTHESIS` | a proposed, testable claim not yet supported | no — research-signal register only |
| `THEORY-CANDIDATE` | a proposed theoretical structure | no — research-signal register only |
| `VERIFIED` | a mechanical check or a completed test produced it | only for mechanical fields |
| `GOVERNANCE` | a recorded human act | only via §17 |

v3.5 R6 is applied literally: source claim ≠ our assessment ≠ agent observation, kept in separate fields.

## 11.2 Minimum evidence per status (mandatory)

| Output | Minimum evidence |
|---|---|
| semantic_status | the list of consumed `pair_id`s with their verdicts; rule applied (§12.2) |
| type_status = CLOSED | a cited row with a complete type signature |
| mathematical_status ≠ NOT-APPLICABLE | cited formal row(s); for INCONSISTENT, the two conflicting statements quoted |
| ESTABLISHED-*-BIRTH | the birth row, its file's date basis (§14.2), and a statement that no earlier row in the label's history matches |
| MOVED | the earlier row with a verbatim quote |
| FOUND (absence) | `[S#### §anchor]` + quote, read in the whole file (stage 2) |
| GENUINELY-UNDEFINED-AFTER-CENSUS | a persisted stage-1 search record with `negative_label = NEGATIVE-CENSUS` and, where stage 1 had hits, the stage-2 disposition of every hit |
| dependency edge | a cited row stating the dependency, and the edge kind |

## 11.3 Insufficient alone (inherited from v3.5 A11, extended by the KSME-22D finding)

Same symbol · same name · same short code · similar wording · temporal proximity · same document ·
embedding or lexical similarity · a P3a relationship on a different pair · a mechanical clue alone. KSME-22D
measured the last point: in 6 of 15 cases the mechanical clue was a false cognate, yet whole-file reading found
the real relationship (`audit-p3a/KSME-22D-L2-PILOT-RESULT.md`).

## 11.4 Absence search — two stages (D-08)

**Stage 1 — mechanical (AUTOMATICALLY SAFE).** For every `NOT-EVIDENCED-IN-CAPTURE` dimension, a script
searches (a) `03-CONTRIBUTIONS.jsonl` and (b) the raw text of every `CONTENT` file in `02-FILES.jsonl`, for the
label, its notations (Unicode, LaTeX and ASCII variants), aliases, and group co-members' notations. It writes one
search record: `{label, dimension, terms[], scope: CORPUS-WIDE|LEDGER-ONLY, files_searched, firewall_skipped[],
hits[{source_id, anchor_or_offset, matched_term}], negative_label}`. `negative_label = NEGATIVE-CENSUS` only if
both (a) and (b) ran corpus-wide and found nothing; otherwise, when nothing was found, `NEGATIVE-BOUNDED`.

**Stage 2 — AI whole-file reading (AI REVIEW).** For every hit, the agent reads the **whole** hit file (never
the snippet alone) and decides: `FOUND` (the file supplies the missing dimension for this object),
`FALSE-HIT` (term matched, referent differs, with reason), or `ESCALATED`. A dimension becomes
`GENUINELY-UNDEFINED-AFTER-CENSUS` only when stage 1 is NEGATIVE-CENSUS, or every stage-1 hit is FALSE-HIT.

**Found material** that P1 missed is recorded as a `P1-GAP` correction candidate (§12.3); P1 artifacts are
not edited (D-12). v3.5 A11 says "found → back to P1-style capture"; this annex performs that capture into an
append-only `P3B-P1-GAP-CAPTURE.jsonl`, never into `03-CONTRIBUTIONS.jsonl`.

---

# 12. RELATIONSHIP MODEL

## 12.1 What P3b may and may not decide

P3b **may** decide: per-object statuses; births; layers; absences; dependency edges.
P3b **may not** decide: any pair relationship, basis or type-compatibility; identity/merge of labels; a new enum
value; canonical form.

## 12.2 semantic_status roll-up rule table

v3.5 A11 names the four values but gives no roll-up rule; the first run used orchestrator-inline rules
(`P3A-QUALITY-GATE-MEMO.md` §8). This annex writes a rule table down so that it is reproducible.
**The table is itself a proposal (D-09, OMQ-06).**

| Precedence | Condition over the label's consumed pairs (and own rows) | semantic_status |
|---|---|---|
| 1 | any pair with relationship HOMONYM, or a SOURCE-CLAIMED-SEPARATION row on this label | `HOMONYM-SPLIT` |
| 2 | any pair REPLACEMENT or REDEFINITION with basis CORROBORATED, or a CONTRADICTION-type row on this label naming another form of it | `CONTESTED` |
| 3 | at least one pair, and every consumed pair is UNWITNESSED or has basis NONE | `IDENTITY-UNWITNESSED` |
| 4 | otherwise (every consumed pair has a positive relationship with basis ≠ NONE), or no pair (Tier Z, subject to OMQ-05) | `RECONCILED` |

Precedence is applied top-down; the first matching row wins, and the record names the rule number and the pair
ids that triggered it.

## 12.3 Corrections, supersession, append-only history

- No existing record is edited or deleted. A different interpretation produces a **new** record plus a
  `P3B-COR-####` entry: `{target_record, target_artifact, reason, evidence, new_record_ref, author_role, date}`.
- Supersession is represented by the new record's `supersedes` field and the COR entry, never by removal.
- File classes are in §24.2.

## 12.4 Ambiguity

A relationship that fits none of the closed values is **never coerced** (KSME-20 Adjudication Contract §2 item 10).
P3b records it as a research signal of type `ONTOLOGY-GAP` with the quote, and the P3b status proceeds on the
closed values only.

---

# 13. RESEARCH-SIGNAL MODEL

## 13.1 Definition

A **research signal** is evidence, observed during P3b, that may reveal theoretical structure the closed
reconciliation vocabulary cannot express. Signals are recorded in `P3B-RESEARCH-SIGNALS.jsonl`.
**A signal never changes a P3b status, a P3a verdict, or any frozen artifact** (D-10).

## 13.2 Controlled vocabulary (each type grounded in a documented occurrence in this corpus)

| Type | Meaning | Grounding |
|---|---|---|
| `CONTRADICTION` | two sources assert incompatible claims about one form | v3.5 B2 type; P3a CONTRADICTION rows |
| `AMBIGUITY` | one source statement supports ≥ 2 readings | P3a notes_for_p3b |
| `HIDDEN-DISTINCTION` | one label appears to carry ≥ 2 distinct objects | "≥4 mutually incompatible K formulations" in `knowledgeos-kernel-concept` (`.claude/CONTEXT.md`) |
| `POSSIBLE-DUPLICATE` | two labels appear to denote one object | P2a design ("K-state", "knowledge-state", "K*") |
| `TERMINOLOGY-INSTABILITY` | the name changes while the role appears stable, or the reverse | v3.5 B6 (K_t / K*_t / S_t) |
| `TYPE-DRIFT` | type signature changes while the semantic role appears stable | v3.5 B6 (δ_A → δ_B) |
| `INVARIANT-CANDIDATE` | a statement that looks like a non-collapse rule or invariant | v3.5 B6 ("This distinction must never be collapsed") |
| `TRANSITION-RULE-CANDIDATE` | a statement about how a state or status changes | recurring in the kernel family |
| `MATHEMATICAL-STRUCTURE` | an unexplained order, algebra, measure or other mathematical relationship | "four partial orders", authority algebra (F-lane state file, EXTERNAL-LANE) |
| `MEASUREMENT-PROBLEM` | a quantity named without a population, sample or valid test | v3.5 B6 ("P(A)=1.2 called a probability") |
| `COUNTEREXAMPLE` | a source case that breaks a stated claim | v3.5 B2 type COUNTEREXAMPLE |
| `ONTOLOGY-GAP` | a real relationship that fits no closed value | the two confirmed gaps (`KSME-20-P3A-ERROR-TAXONOMY.md` §6) |
| `UNRESOLVED-RELATIONSHIP` | a connection between labels that share no P3a pair | §9.4 |
| `IMPORTANT-ABSENCE` | a load-bearing dimension that is GENUINELY-UNDEFINED-AFTER-CENSUS | v3.5 B6 last case |
| `HINDSIGHT-RISK` | a reading that depends on a later source | §14 |
| `CROSS-TRACK` | the evidence for an object spans Track A and Track B | `KSME-20-P3A-TRACK-PROVENANCE-AUDIT.md` |

A new type is added only by change control (§26).

## 13.3 Lifecycle

```
OBSERVED → ANALYSED → HYPOTHESIS-STATED → TEST-DEFINED → TESTED → STATUS
```
- `OBSERVED`: quote(s) + S-ids + the P3b record that surfaced it. (AI)
- `ANALYSED`: competing explanations listed, at least two when two exist. (AI)
- `HYPOTHESIS-STATED`: one falsifiable statement, epistemic class HYPOTHESIS. (AI)
- `TEST-DEFINED`: what evidence would refute it, and where to look. (AI proposes; research-interesting)
- `TESTED`: the test is run — mechanical search, whole-file reading, or formal check. (AI or script)
- `STATUS` ∈ {`SUPPORTED`, `REFUTED`, `UNDETERMINED-FROM-CORPUS`, `ROUTED-TO-GOVERNANCE`}. (AI records;
  consequential routing is human, §17)

P3b execution obliges only `OBSERVED` for every signal noticed. Later stages are optional during P3b and never
block acceptance (D-10); they are the input to P4–P7 research.

---

# 14. TEMPORAL / HINDSIGHT CONTROLS

## 14.1 Principle

Chronological position is evidence infrastructure, not causality (v3.5 R1). A later document never rewrites what an
earlier document meant (v3.5 R10).

## 14.2 Birth and date rules (D-11)

1. Only a date that applies **to the file itself** can position the file. A date the file cites for another
   artifact or event never sets its position. (Evidence: F-lane §1 measured P3A's minimum-explicit-date rule wrong
   for 3 of 10 files, all synthesis documents citing older dates.)
2. Every births inspection records the birth file's `best_historical_date_basis` from `02-FILES.jsonl`, and the
   agent confirms from the whole file that the basis applies to the file itself. Otherwise the birth is
   `UNORDERED-BLOCK` or escalated as `TIMESTAMP-ANOMALY`.
3. A `SECONDARY-SYNTHESIS` or `PROVENANCE-UNRESOLVED` file **cannot alone establish** a birth, a FOUND, or a
   CONTESTED status. It may corroborate a PRIMARY source, or it produces a `HINDSIGHT-RISK` signal.
4. Inside a BULK/mtime block, order is UNORDERED (v3.5 R1).

## 14.3 Timeline fields per object (mandatory where evidence exists; otherwise NOT-EVIDENCED-IN-CAPTURE)

`first_lexical` · `first_conceptual` · `first_formal` · `first_operational` · `first_governance`
(= v3.5's five birth kinds) · `later_support[]` · `later_refinement[]` · `contradicted_by[]` ·
`rejected_by[]` · `current_lifecycle` (v3.5 B4 lifecycle, still SOURCE-CLAIMED-* until corroborated).

## 14.4 Anti-projection test (per record, AI self-check, audited §21)

For every status that cites a source later than the object's first appearance, the agent answers in the record:
*"Would this status be the same if only sources up to the cited early source existed?"* If not, the status carries
`hindsight_dependency: [S-ids]` and a `HINDSIGHT-RISK` signal.

## 14.5 Track separation (D-13)

Every cited S-id carries a mechanically computed track tag, from the directory mapping in
`KSME-20-P3A-TRACK-PROVENANCE-AUDIT.md`: TRACK-A-PHASE-MEASURE · TRACK-A-VERIFICATION-CLUSTER-UNCONFIRMED ·
TRACK-B-GAP-DISCOVERY · UNCLASSIFIED. Each object record carries `track_composition` (counts per tag).
An object whose evidence spans A and B is flagged MIXED and raises a `CROSS-TRACK` signal. A Track-B-only source
cannot establish a birth or FOUND for an object whose earliest evidence is Track A (hindsight). Whether it may
contribute at all is OMQ-07.

---

# 15. AUTOMATION BOUNDARY

Categorical, auditable status only. **No numerical confidence scores** are produced; no evidence in this
repository shows such scores are calibrated (P3a's measured 20.9% exact agreement argues against trusting
unvalidated judgment summaries) (D-14).

| Operation | Class |
|---|---|
| Corpus enumeration from `02-FILES.jsonl`; boundary check | AUTOMATICALLY SAFE |
| Input-artifact hashing; frozen-artifact integrity check | AUTOMATICALLY SAFE |
| Exposure computation and tiering | AUTOMATICALLY SAFE |
| Batch planning (bin-packing) | AUTOMATICALLY SAFE |
| Stage-1 absence search; negative-label assignment | AUTOMATICALLY SAFE |
| Track tagging from directory | AUTOMATICALLY SAFE (the mapping itself: HUMAN REVIEW, OMQ-07) |
| Enum/closed-list validation; citation reality; coverage | AUTOMATICALLY SAFE |
| semantic_status via the §12.2 rule table | AUTOMATICALLY SAFE **once OMQ-06 approves the table**; until then AI REVIEW |
| Near-duplicate detection among labels | AUTOMATICALLY SAFE as a candidate generator → RESEARCH-INTERESTING |
| type_status, mathematical_status, layer | AI REVIEW |
| Births inspection against source | AI REVIEW |
| Stage-2 whole-file reading of hits | AI REVIEW |
| Dependency edges | AI REVIEW |
| Research signals OBSERVED → TESTED | AI REVIEW / RESEARCH-INTERESTING |
| Anything touching a Tier-X label | HUMAN REVIEW (H-02) |
| CONTESTED or HOMONYM-SPLIT on a label with ≥ 50 rows | HUMAN REVIEW (sampled, §21) — the threshold is OMQ-09 |
| ONTOLOGY-GAP, cross-track conflict, timestamp anomaly unresolved after stage 2 | UNRESOLVED → escalation |

---

# 16. AI INTERPRETATION BOUNDARY

1. AI output is always a **recommendation** with its evidence (v3.5 R20). It enters the ledger with
   `author_role: AI-AGENT` and never with a governance status.
2. AI may not: decide pairs; merge, rename or split labels; add enum values; write to frozen artifacts; set
   `ACCEPTED`; promote a research signal beyond `STATUS`; read F-only or firewalled files; cite its own earlier
   output as SOURCE (v3.5 R8).
3. DOMAIN-INTERPRETATION is labelled as such and is never the basis of a status.
4. Every agent works from the persisted, hashed contract (§19.2). An agent that cannot follow the contract for a
   label records `ESCALATED` for that label and continues with the next.

---

# 17. HUMAN GOVERNANCE BOUNDARY

Human acts are recorded in `P3B-GOVERNANCE-LOG.md` as `{decision_id, date, decider, decision, evidence
consulted, scope}`. **Engineering never accepts its own work** (EP-02 / R-34).

| ID | Decision | Blocks |
|---|---|---|
| H-01 | Approve this annex (or return it) | everything |
| H-02 | Tier-X disposition: re-adjudicate the affected pairs first / accept frozen verdicts with the limitation / hold indefinitely (OMQ-02) | S6 |
| H-03 | Treatment of OB0001–OB0003 (OMQ-04) | S4 comparison use |
| H-04 | Adopt P3A-V2's evidence bundle as P3b's input layer or not (OMQ-01) | S3 |
| H-05 | Relationship-ontology gaps (OMQ-03) | nothing in P3b (recorded as signals meanwhile) |
| H-06 | Batch acceptance (§25) | each batch |
| H-07 | Whether the methodology freeze admits this annex | H-01 |
| H-08 | Any corpus-boundary change | — |
| H-09 | Resolution of any escalation marked HUMAN | the affected labels |
| H-10 | Consequential classification: any output a later phase would treat as canonical | P4+ |

---

# 18. PROVENANCE REQUIREMENTS

**Mandatory on every P3b object record:** `working_label` · `batch_id` · `run_id` · `contract_sha256` ·
`input_manifest_sha256` · `tier` + causing `pair_id`s · every status with its cited `S####` and anchor or
quote, and the rule or reasoning used · `epistemic_class` per claim · `author_role` · `analysis_date` ·
`negative_label` for every absence · `track_composition` · `hindsight_dependency` (may be empty) ·
`what_says_this` / `what_would_make_this_wrong` per status.

**Mandatory on every research signal:** `signal_id` · type · quotes with S-ids · originating record ·
`epistemic_class` · lifecycle stage · `origin` (P3B | EXTERNAL-LANE) · author_role · date.

**Optional:** source path (derivable from S-id); free-text reviewer notes; cross-references to F-lane records
(EXTERNAL-LANE only).

Source path, commit and sha256 resolve through `02-FILES.jsonl` (identity per v3.5 R11); line numbers are never
anchors.

---

# 19. BATCH PROTOCOL

## 19.1 Steps

| Step | Kind | Action | Output |
|---|---|---|---|
| **S0 PREFLIGHT** | script | verify §5 assumptions: frozen artifacts' sha256 unchanged since `c9e76918b`; 2,779 records; 2,497 labels; 1,793 pairs; OB0001–3 files unchanged; working tree clean for `chronological-read/` | `P3B-PREFLIGHT.json` |
| **S1 INPUT FREEZE** | script | persist the affected set as `AFFECTED.jsonl` (A and C recomputed from their documented method; B reproduced from `KSME-21` Phase 2 or marked unreproducible, OMQ-08); hash every input | `AFFECTED.jsonl`, `P3B-INPUT-MANIFEST.json` |
| **S2 TIERING** | script | assign Tier U / X / Z per label | `P3B-TIERS.jsonl` |
| **S3 MECHANICAL PREP** | script | stage-1 absence search for every label; track tags; per-label bundle (V1 or V2 per H-04) | `P3B-ABSENCE-SEARCH.jsonl`, `_batch_input_r2/` |
| **S4 PILOT-R2** | AI + audit | run the persisted contract on a pilot batch; compare with OB0001–3 where labels overlap (per H-03) | pilot report |
| **S5 BATCHES** | AI + verify + audit | Tier-U and Tier-Z batches (Tier Z subject to OMQ-05) | `ledger-p3b-r2/OB####-R2/objects.jsonl` |
| **S6 TIER-X** | per H-02 | re-entered only after H-02 | same ledger |
| **S7 CLOSE** | script + AI narrative | merge, TERMINAL assertions (§23), write `32-RECONCILIATION-OBJECTS.jsonl` and `30-RECONCILIATION.md` | §24 |

## 19.2 Persisted agent contract (D-06)

Before any AI batch, the full agent instruction text is written to
`prompts/<timestamp>_p3b-agent-contract-r2.md`, committed, and its sha256 recorded in every output record.
An agent receives only: the contract, its batch input slice, and read access to the corpus files of §3.
This closes the reproducibility gap of §5.4.

## 19.3 Dispatch

- One agent per batch; parallel batches allowed (v3.5 R12: no cross-batch identity resolution happens in P3b).
- The orchestrator never reads a corpus file or a full ledger (v3.5 R14).
- After each batch: verifier (§20) → audit sample (§21) → human acceptance (§25).

---

# 20. QUALITY GATES

| Gate | Checks | Mechanism |
|---|---|---|
| G-01 Corpus integrity | every cited S-id ∈ `02-FILES.jsonl`; no F-id; no firewalled file quoted | script |
| G-02 Identity integrity | exactly the assigned labels, each once; no unknown label; no renamed or merged label | script |
| G-03 Duplicate handling | a duplicate file (`08-OVERLAP-REGISTER.jsonl`) is never counted as independent corroboration (v3.5 R9) | script |
| G-04 Evidence completeness | every status meets §11.2 minimum evidence; every absence has a search record and negative label | script |
| G-05 Relationship classification | closed lists only (existing verifier's enums); semantic_status consistent with the §12.2 table given the consumed pairs | script |
| G-06 Semantic interpretation | blind audit sample (§21) — disagreements individually dispositioned | AI audit + human |
| G-07 Provenance | all §18 mandatory fields present; contract hash matches | script |
| G-08 Hindsight | no birth, FOUND or CONTESTED rests only on SECONDARY-SYNTHESIS, PROVENANCE-UNRESOLVED or Track-B-over-Track-A sources; anti-projection answer present | script + audit |
| G-09 Signal promotion | no signal altered a status; no signal beyond STATUS | script |
| G-10 Reproducibility | re-running the scripts on the recorded input manifest reproduces the mechanical fields byte-for-byte | script |

**Batch fails** if any of G-01…G-05, G-07…G-10 fails, or if G-06 finds a disagreement dispositioned as a
protocol violation (not a judgment call).
**On failure:** the batch output is kept (never deleted), marked `FAILED` in the manifest with the failing gate,
and a new run (`OB####-R2.2`) is planned with the cause recorded. No partial acceptance of a failed batch.
**A batch can be accepted** when all gates pass and H-06 is recorded.

---

# 21. AUDIT PROTOCOL

1. **Per batch:** an independent agent, given the same contract and inputs but not the batch's output,
   re-derives a stratified sample: every CONTESTED and HOMONYM-SPLIT label in the batch, plus a fixed number of
   others drawn with a recorded seed (sample size OMQ-09). Agreement is reported categorically per field (exact
   match / same coarse class / different), never as a confidence score.
2. **Every disagreement** is dispositioned individually: `ORIGINAL-UPHELD`, `AUDIT-UPHELD`
   (→ correction record), `JUDGMENT-CALL` (both defensible; recorded as an AMBIGUITY signal), or
   `PROTOCOL-VIOLATION` (→ batch fails).
3. **Every 5 batches** (v3.5 AUDIT_EVERY): a cross-batch audit checking whether the §12.2 table and the absence
   procedure are applied consistently between batches (KSME-20 found inconsistent adjudication between passes as
   a real cause).
4. Audit reports go to `audit-p3b/` and are append-only.

---

# 22. FAILURE / RECOVERY RULES

| Condition | Action | Owner |
|---|---|---|
| Ambiguous relationship | ONTOLOGY-GAP or AMBIGUITY signal; status on closed values only | AI |
| Contradictory evidence | CONTRADICTION signal; CONTESTED only if §12.2 row 2 applies | AI |
| Missing file (an S-id whose path no longer resolves) | label `BLOCKED`; `P3B-ESC` with the S-id; resolve through `02-FILES.jsonl` commit + sha256 (`git show <commit>:<path>`) before escalating | script → human |
| Duplicate files | treat as one source (v3.5 R9); cite the first occurrence | script |
| Timestamp anomaly | birth → UNORDERED-BLOCK; TIMESTAMP-ANOMALY escalation if it changes a birth | AI → human |
| Cross-track conflict | CROSS-TRACK signal; §14.5 rule; OMQ-07 | AI → human |
| Possible hindsight contamination | HINDSIGHT-RISK signal; `hindsight_dependency` set | AI |
| Frozen-artifact hash mismatch at S0 | **stop all execution**; human escalation | script → human |
| Agent contract deviation | label ESCALATED; batch continues; ≥ 1 PROTOCOL-VIOLATION fails the batch | AI → audit |
| Session interruption | resume from `P3B-STATE.json` and the manifest only, never from conversation memory (v3.5 A3) | orchestrator |

---

# 23. STOPPING RULES

**Terminal predicate for P3b** (extends v3.5 A11 TERMINAL). All must hold:
1. every Tier-U and Tier-Z label is `ACCEPTED` or `BLOCKED` with an open escalation;
2. every Tier-X label is `ACCEPTED`, or `PROVISIONAL-HELD` under a recorded H-02 decision to hold;
3. every absence dimension of every accepted label is FOUND, GENUINELY-UNDEFINED-AFTER-CENSUS,
   FIREWALL-BLOCKED, or ESCALATED;
4. `32-RECONCILIATION-OBJECTS.jsonl` and `30-RECONCILIATION.md` exist and pass G-01…G-10 corpus-wide.

**Not stop conditions** (v3.5 R18): a batch completing, an interesting finding, a contradiction found, a commit.

**Stop-the-line conditions:** frozen-artifact mismatch; two consecutive failed batches with the same gate
(indicating the method, not the batch, is wrong → change control §26); any human instruction.

`UNWITNESSED`, `IDENTITY-UNWITNESSED` and `GENUINELY-UNDEFINED-AFTER-CENSUS` are legitimate final results.
No status is forced to close the phase.

---

# 24. OUTPUT ARTIFACTS

## 24.1 New artifacts (all under `docs/knowledgeos/chronological-read/`)

| Artifact | Format | Class |
|---|---|---|
| `P3B-PREFLIGHT.json`, `P3B-INPUT-MANIFEST.json`, `AFFECTED.jsonl`, `P3B-TIERS.jsonl` | JSON/JSONL | regenerable (deterministic from frozen inputs) |
| `P3B-ABSENCE-SEARCH.jsonl` | JSONL | regenerable |
| `_batch_manifest_p3b_r2.jsonl`, `_batch_input_r2/` | JSON/JSONL | regenerable before dispatch; frozen once a batch is dispatched |
| `ledger-p3b-r2/OB####-R2/objects.jsonl` | JSONL | append-only per run; never edited |
| `P3B-RESEARCH-SIGNALS.jsonl` | JSONL | append-only (a lifecycle advance is a new line referencing the signal id) |
| `P3B-P1-GAP-CAPTURE.jsonl` | JSONL | append-only |
| `P3B-CORRECTIONS.jsonl` (`P3B-COR-####`) | JSONL | append-only |
| `P3B-ESCALATIONS.jsonl` (`P3B-ESC-####`) | JSONL | append-only |
| `P3B-GOVERNANCE-LOG.md` | Markdown | append-only, human entries |
| `P3B-STATE.json` | JSON | mutable execution position (holds no rules) |
| `audit-p3b/` | MD/JSON | append-only |
| `32-RECONCILIATION-OBJECTS.jsonl` | JSONL | written once at S7; a later version is a new file with a version suffix |
| `30-RECONCILIATION.md` | Markdown | v3.5 B1 P3 narrative; written at S7 |
| P4 hand-over package: per-object statuses + births + edges + open signals + escalations | inside `30-RECONCILIATION.md` §"Hand-over" | — |

## 24.2 Existing artifacts — classes

| Class | Artifacts |
|---|---|
| **Immutable (frozen)** | everything committed in `125cfe8371`/`c9e76918b` under `chronological-read/`, including `02-FILES.jsonl`, `03-CONTRIBUTIONS.jsonl`, `20-FAMILIES/**`, `31-RECONCILIATION-PAIRS.jsonl`, `ledger/`, `ledger-p3a/`, `ledger-p3b/OB0001–3`, `_batch_manifest_p3b.jsonl`, `audit-p3a/**`; Master Protocol v3.5; the agent-extraction contract |
| **Append-only** | `09-ORCHESTRATOR-FLAGS.md` (existing convention); all new append-only files above |
| **Regenerable** | only the new regenerable artifacts above |
| **New version required** | any change to this annex, the agent contract, the §12.2 table, the signal vocabulary, or a merged P3b output |

---

# 25. ACCEPTANCE CRITERIA

**Batch acceptance** (H-06): all gates pass (§20); audit dispositions complete (§21); no open PROTOCOL-VIOLATION;
the human acceptance entry is recorded. Engineering supplies the evidence and a recommendation; the human decides.

**Phase acceptance:** the terminal predicate (§23) holds; a P3b completion report states per tier: labels accepted /
held / blocked, the status distributions, signals by type and stage, escalations open, audit agreement tables,
and **what P3b does not establish** (in the P3A-FROZEN-BASELINE style: roll-ups on accepted-as-is Tier-X verdicts
are not revalidated; IDENTITY-UNWITNESSED ≠ distinct; GENUINELY-UNDEFINED-AFTER-CENSUS is relative to the
Phase-1 corpus only). The output label is **"P3 RECONCILIATION — PENDING GOVERNANCE REVIEW"**, never final (v3.5 R20).

---

# 26. CHANGE-CONTROL MECHANISM

1. This annex is versioned by filename timestamp. A change is a new file citing the file it supersedes, plus an
   entry in `P3B-GOVERNANCE-LOG.md` with the evidence that motivated it.
2. Changes require a human decision (H-01 class). A mid-execution change applies only to batches dispatched after
   it; earlier batches keep their contract hash and are never silently re-interpreted.
3. **If execution invalidates this annex, stop, explain why, present the revision, and wait for approval.**
   Never change direction silently (repository EP-01 rule).
4. Master Protocol v3.5 is never edited. A defect in v3.5 is recorded as an observation for governance.

---

# 27. OPEN METHODOLOGICAL QUESTIONS

| ID | Question | Evidence so far | Options | Blocks |
|---|---|---|---|---|
| **OMQ-01** | Adopt P3A-V2's evidence bundle (15 fields vs 6) as P3b's input layer? | V2 validated for candidate generation, 99.93% recovery of the known gap, not adopted (`KSME-20-FORK-DECISION.md`) | adopt / V1 bundle plus direct row access / both, compared on the pilot | S3 |
| **OMQ-02** | Tier-X disposition (H-02) | memo §8 recommends holding; KSME-21 recommends bounded re-adjudication of 414 pairs; 477+ labels exposed | re-adjudicate first / accept with limitation / hold | S6 |
| **OMQ-03** | Relationship-ontology gaps: two new enum values or a sub-typed field? | two confirmed gaps; the contract recommends a sub-typed field | enum / sub-type / defer | nothing in P3b |
| **OMQ-04** | OB0001–OB0003: accept, re-run, or keep as a pilot baseline? | mechanically PASS; instructions not persisted; 45/241 exposed | accept Tier-U subset after audit / re-run all 241 under R2 and compare / preserve only | S4 |
| **OMQ-05** | Is "no pairs ⇒ RECONCILED" a correct reading of v3.5 for 1,120 pairless labels? | v3.5 A11 lists RECONCILED without a rule; the first run applied "trivially RECONCILED" | keep / new value such as `NO-PAIR-EVIDENCE` / IDENTITY-UNWITNESSED | Tier Z |
| **OMQ-06** | Approve the §12.2 roll-up rule table? | reconstructed from the first-run inline rules (memo §8) and v3.5 A11 | approve / amend | automating semantic_status |
| **OMQ-07** | Track mapping and cross-track evidence rule | mapping partly UNCONFIRMED (`KSME-20-P3A-TRACK-PROVENANCE-AUDIT.md` notes the MD-043 subcluster doubt) | confirm the mapping / refine it; allow Track-B corroboration or not | G-08 |
| **OMQ-08** | Affected-set B (28 pairs) was never persisted — reproduce or declare unreproducible? | `KSME-21` describes a keyword screen; no pair list on disk | reproduce from the described method / exclude B, record the gap | S1 |
| **OMQ-09** | Numbers: batch size, audit sample size, "large label" threshold for human review | v3.5 BATCH_SIZE 40 (files, P1); P3b first run 30 batches of about 83 labels; no calibration evidence for audit sizes | set by the human; the pilot measures cost | S4 |
| **OMQ-10** | Do the derivation-discovery P4/P5 extension candidates (generality, parsimony, falsifiability …) enter the P4 hand-over? | marked EXPERIMENTAL, not adopted | defer to P4 commission / include as annotations | hand-over format |
| **OMQ-11** | Stage-2 reading load: 20,107 absence dimensions — the expected hit volume is unknown | only stage 1 can measure it | measure in S3 before approving S5 dispatch | S5 sizing |
| **OMQ-12** | Does the methodology freeze admit this annex (H-07)? | `.claude/CLAUDE.md` freeze clause; deficiency evidence in §6 | admit / reject | H-01 |

---

# DECISION REGISTER

Each entry: **Decision · Evidence · Alternatives · Reason · Remaining uncertainty.**

**D-01 Protocol class = operating annex under v3.5.** Evidence: v3.5 defines P3 semantics fully but no operating
procedure (KSME-20 §5); the F-lane protocol governs a different namespace and unit (§7). Alternatives: no new
protocol (resume P3b as-is); a successor master protocol; adopt the F-lane protocol. Reason: resuming as-is repeats
the unpersisted-instruction and exposure problems; a successor would compete with v3.5; the F-lane is a different
unit and registry. Uncertainty: H-07/OMQ-12.

**D-02 Corpus = `02-FILES.jsonl` records, not the S-range.** Evidence: 147 S-range gaps. Alternatives: S-range; the
F-manifest. Reason: the human's boundary decision plus measured gaps. Uncertainty: none.

**D-03 No F-ids; `P3B-` prefixes for new ids.** Evidence: F-lane mints `RO-`/`T-`/`TH-`/`CMP-`; recorded collision
failure mode. Alternatives: unprefixed ids. Reason: collision prevention. Uncertainty: none.

**D-04 P1/P2/P3a consumed, never regenerated.** Evidence: all TERMINAL and frozen; commission §4. Alternatives:
regenerate with V2. Reason: regeneration is re-adjudication, which is H-02's scope. Uncertainty: OMQ-01 may change
the *bundle*, never the frozen verdicts.

**D-05 Tiering by exposure to the persisted affected set.** Evidence: memo §8 (direct lookup propagates errors);
A ∪ C touches 477 labels. Alternatives: pause all P3b; ignore exposure. Reason: follows the actual dependency graph.
Uncertainty: B unpersisted (OMQ-08).

**D-06 Persisted, hashed agent contract.** Evidence: first-run instructions exist nowhere on disk. Alternatives:
inline instructions. Reason: reproducibility. Uncertainty: none.

**D-07 Label is the unit of roll-up; the file is the unit of reading.** Evidence: v3.5 A11; KSME-22D. Alternatives:
file unit. Reason: the file unit would restart P2. Uncertainty: none.

**D-08 Two-stage absence search.** Evidence: 20,107 dimensions; KSME-22D false cognates; v3.5 R17. Alternatives:
AI-only; ledger-only; mechanical-only. Reason: mechanical recall at scale, whole-file precision on hits.
Uncertainty: stage-2 volume (OMQ-11).

**D-09 Written semantic_status rule table.** Evidence: v3.5 has no roll-up rule; the first run used inline rules.
Alternatives: leave to agent judgment. Reason: KSME-20 showed unconstrained judgment diverges between passes.
Uncertainty: OMQ-06.

**D-10 Research signals are separate and never change statuses.** Evidence: commission §7; v3.5 R5/R6.
Alternatives: signals as status modifiers. Reason: prevents premature theory. Uncertainty: vocabulary completeness
(change control).

**D-11 Hindsight rules (file-applicable dates; SECONDARY-SYNTHESIS cannot establish).** Evidence: 3/10 P3A date
errors (F-lane §1); 739 SECONDARY-SYNTHESIS files; v3.5 R8/R10. Alternatives: allow with a flag only.
Reason: synthesis documents cite older dates and material by construction. Uncertainty: may raise UNORDERED-BLOCK
frequency.

**D-12 P1 gaps captured append-only outside P1 artifacts.** Evidence: v3.5 A11 "back to P1-style capture"; the
freeze. Alternatives: edit `03-CONTRIBUTIONS.jsonl`. Reason: the freeze. Uncertainty: none.

**D-13 Mechanical track tagging, MIXED flagged.** Evidence: chronological-read has no track concept (KSME-20 track
audit). Alternatives: ignore tracks. Reason: prevents Track-B hindsight on Track-A. Uncertainty: OMQ-07.

**D-14 No numerical confidence scores.** Evidence: no calibration exists; 20.9% exact agreement on judged pairs.
Alternatives: scores. Reason: commission §10. Uncertainty: none.

**D-15 Per-batch blind audit with individual disposition.** Evidence: the KSME-20 method found real errors.
Alternatives: end-of-phase audit only. Reason: catches method drift early. Uncertainty: sample size (OMQ-09).

**D-16 Failed batches kept and re-run; never partially accepted.** Evidence: commission §12. Alternatives:
patch in place. Reason: audit history. Uncertainty: none.

**D-17 New outputs in new paths (`ledger-p3b-r2/`, `32-…`).** Evidence: frozen tree; v3.5 B1 reserves `30-`.
Alternatives: write into `ledger-p3b/`. Reason: preserves OB0001–3 unmodified. Uncertainty: none.

**D-18 No ID or conclusion exchange with the F-lane; EXTERNAL-LANE signals only.** Evidence: §7.
Alternatives: merge lanes. Reason: namespace separation and the human's boundary. Uncertainty: none.

**D-19 Tier-X re-entry, not parallel re-adjudication.** Evidence: P3b consumes pair verdicts directly. Alternatives:
run in parallel. Reason: parallel work would roll up verdicts that are being changed. Uncertainty: H-02.

**D-20 Pilot before scale.** Evidence: KSME-22D as proof-of-concept practice; stage-2 volume unknown.
Alternatives: dispatch all at once. Reason: measure cost and agreement first. Uncertainty: pilot size (OMQ-09).

---

# 28. EXECUTION READINESS

| Question | Status |
|---|---|
| Corpus boundary fixed? | **Yes** — §3; measured |
| Identity model fixed? | **Yes** — §4 |
| Existing P1/P2 preserved? | **Yes** — §5.1, §24.2 |
| P3a treatment defined? | **Yes** — consumed and frozen; exposure tiering §9.3 |
| P3b operationally defined? | **Yes, pending OMQ-05/06** — §9, §12.2 |
| Evidence model defined? | **Yes** — §11 |
| Relationship model defined? | **Yes** — §12; ontology gaps deferred (OMQ-03) |
| Research-signal model defined? | **Yes** — §13 |
| Hindsight control defined? | **Yes** — §14; track rule pending OMQ-07 |
| Automation boundary defined? | **Yes** — §15 |
| AI/human boundary defined? | **Yes** — §16, §17 |
| Provenance defined? | **Yes** — §18 |
| Quality gates defined? | **Yes** — §20; audit sample size pending OMQ-09 |
| Stopping rules defined? | **Yes** — §23 |
| Failure/recovery defined? | **Yes** — §22 |
| Outputs defined? | **Yes** — §24 |
| Acceptance criteria defined? | **Yes** — §25 |
| Open methodological questions identified? | **Yes** — 12, §27 |

### PROTOCOL STATUS

**REQUIRES METHODOLOGICAL DECISION.**

The text is complete enough for independent review now. Execution cannot begin until the human decides at least
**H-07/OMQ-12** (freeze admissibility), **H-01** (approval), **OMQ-01** (input layer), **OMQ-04** (OB0001–3),
**OMQ-05/06** (roll-up rules) and **OMQ-08/09** (affected-set B; the numbers). Tier-X work additionally waits on
**H-02**. No execution has been performed under this document.

---

**Traceability:** commissioned 2026-09-24 ("Design the method first — Phase-1 KnowledgeOS continuation protocol");
corpus boundary from the human decision of 2026-09-24 (`02-FILES.jsonl` authoritative). Inputs: Master Protocol
v3.5 (`prompts/20260911_0221_prompt3-optimized.md`), `P3A-FROZEN-BASELINE.md`, `P3A-QUALITY-GATE-MEMO.md`,
`KSME-20-*`, `KSME-21-EVIDENCE-REPAIR-REPORT.md`, `KSME-22D-L2-PILOT-RESULT.md`, `09-ORCHESTRATOR-FLAGS.md`, the
P3b scripts, `.claude/CONTEXT.md`, session logs 2026-09-20…22, and the F-lane protocol (as evidence only).
Supersedes: none.
