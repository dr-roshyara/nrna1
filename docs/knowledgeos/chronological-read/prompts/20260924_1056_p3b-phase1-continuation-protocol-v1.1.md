# KNOWLEDGEOS PHASE-1 CONTINUATION PROTOCOL — P3b OPERATING PROTOCOL · v1.1

**Status: PROPOSED v1.1 — pending review and freeze. Not executable until §26 approval is recorded.**
**Class: operating annex to Master Protocol v3.5. v3.5 is not modified by this document.**
**Written:** 2026-09-24 · repository HEAD at writing: `3bb068e7b` · P3A freeze: `125cfe8371`, `c9e76918b`
**Supersedes:** `prompts/20260924_1045_p3b-phase1-continuation-protocol.md` (v1.0, kept unchanged).
**What changed:** see §29 "Change log v1.0 → v1.1". The architecture is unchanged; seven reviewed issues and
the internal contradictions found while addressing them are corrected.

> Reading rule. This document is self-contained: a researcher who has never seen the conversation
> that produced it must be able to execute it from the repository alone. Every factual statement
> about existing state carries its source. Every methodological decision carries
> **Decision · Evidence · Alternatives · Reason · Remaining uncertainty** (§ "Decision register", D-01…D-26).
> Every question the evidence does not settle is an **OPEN METHODOLOGICAL QUESTION** (OMQ-01…OMQ-13, §27)
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
26 Change control · 27 Open methodological questions · Decision register · 28 Execution readiness · 29 Change log

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
| B, documented part: the 20 pair ids listed verbatim in `KSME-21-EVIDENCE-REPAIR-REPORT.md` Phase 2 (all UNWITNESSED; 4 already in A ∪ C) | 16 new beyond A ∪ C | — |
| A ∪ C ∪ B-documented | 407 | **497** |
| B, undocumented part | 414 − 407 = 7 pairs not identifiable | **not computable** |
| A ∪ B ∪ C (the historical 414) | 414 | ≥ 497 (exact value unknown) |

**Status of B (verified 2026-09-24):** B was produced by a keyword screen over the evidence text of the 684
UNWITNESSED pairs ("evidence text contains corpus-wide/exhaustive-search language"). The report does not record
the keyword list, and no script that computes it is persisted: searching `audit-p3a/**` and `scripts/` for
`NEGATIVE-CENSUS` finds only enum declarations (`verify_reconciliation_pairs_batch.py`,
`synthetic_fixtures_v2.py`). The report's own counts are also internally loose ("34 total candidates before dedup
… 28 unique after dedup"). **B therefore cannot currently be reproduced exactly.** Treatment: §19.1 S1 and OMQ-08.

Consequence: the quality-gate memo's "2,237 unaffected labels" was computed against C only. Against the later,
broader affected set, at least 477 labels (19.1%) are exposed, or 497 (19.9%) if documented B is included. This
is a **finding**, not a correction of the memo; the memo was correct for the set it measured.

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
| Pairless labels rolled up | 110 → RECONCILED, 4 → CONTESTED | computed from the three ledgers |
| Labels with a CORROBORATED REPLACEMENT/REDEFINITION pair | 3 → CONTESTED | computed — **contradicts v3.5 B4**, whose worked example is `RECONCILED(REPLACEMENT/CORROBORATED, …)` (§12.2) |
| Labels with a HOMONYM pair | 7 → HOMONYM-SPLIT, 4 → CONTESTED | computed — the same input condition received two different statuses **within one run** |

**Disposition of the first run (D-21):** OB0001–OB0003 are a **historical P3b baseline (`P3B-R1`), not accepted
production output.** Reasons: pre-protocol; instructions not persisted; 45 of 241 labels exposed; one rule
contradicts v3.5 B4; internally inconsistent on HOMONYM. They are preserved unmodified and used as the comparison
baseline in S4 (§19.1). Nothing downstream may consume them as P3b results. Human confirmation: H-03.

## 5.5 Gate state for OB0004+

No gate record authorizes OB0004+. Recorded human-decision points still open
(`KSME-20-FORK-DECISION.md` "Next step is a human decision on (a)(b)(c)"; `P3A-QUALITY-GATE-MEMO.md` §8):
(a) implement the Adjudication Contract fix order; (b) adopt P3A-V2 as the input layer; (c) close the two
confirmed relationship-ontology gaps; plus the memo's tiered-resumption recommendation. Those are OMQ-01…OMQ-04.

## 5.6 Scale facts that shape the method

- 20,107 `NOT-EVIDENCED-IN-CAPTURE` completeness dimensions across the 2,497 labels need an absence search
  (v3.5 A11 "absences"). This number rules out AI-only searching (D-08).
- 1,120 labels have no P3a pair: 1,116 are in no P2a group (`_derived.json["ungrouped_labels"]` has 1,119 entries),
  and 4 sit in a 2-member POSSIBLY-RELATION group whose other member is a POSSIBLY-target that never became a label
  (for example G0086, G0174, G0175, G0626), so no pair could be formed. 525 of the 1,120 have exactly one
  contribution row; 143 have ten or more.
- 33 labels have zero contribution rows (DORMANT in capture).

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
NOT-STARTED → PREPARED → ASSIGNED → PROPOSED → VERIFIED → AUDITED → ACCEPTED
                                       │           │          │
                                       └─── FAILED ◄┘──────────┘ → (re-run in a new batch run)
PROVISIONAL-HELD   (Tier X, pending H-02)          BLOCKED (escalated, §22)
```

`PROPOSED` is what an AI agent writes. Only `ACCEPTED` is a P3b result (§16.5). A record's state never advances
by editing it: each transition is evidenced by a separate artifact (verifier output, audit report, governance-log
entry), and `ACCEPTED` exists only in the merged file `32-RECONCILIATION-OBJECTS.jsonl` (§24).

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
| `audit-p3a/v2/scripts/evidence_bundle_v2.py` → `row_bundle_v2(row, file_meta)` | **only if H-04 approves**: read-only presentation of each contribution row with the fields V1's `row_brief()` dropped (§11.5). Never `candidate_bundle_v2` (pair-level), never `derive_reconciliation_v2.py` (candidate generation) |

## 9.3 Input-exposure model (tiering)

A label's roll-up is only as reliable as the pair verdicts it consumes (`P3A-QUALITY-GATE-MEMO.md` §8: the
roll-up is "a direct, undiluted lookup"). Therefore:

- **Tier U (unexposed):** the label touches no pair in the persisted affected set `AFFECTED.jsonl` (§19.1 S1).
- **Tier X (exposed):** the label touches ≥ 1 affected pair. Its roll-up is `PROVISIONAL-HELD` until H-02.
- **Tier Z (pairless):** the label touches no pair at all (1,120 labels). **Its semantic_status is withheld**: the
  field is written as `null` with `semantic_status_note: "NO-PAIR-EVIDENCE — withheld pending H-11a"`.
  `NO-PAIR-EVIDENCE` is a note, not a fifth status value (§12.2 D1–D2 give the derivation). **All other per-object
  fields** (type, mathematical, births, layer, absences, edges) are pair-independent and are produced for Tier Z
  as for Tier U. The first run's "trivially RECONCILED" reading (`ledger-p3b/OB0002`: "pairs_touching is empty …
  trivially RECONCILED per protocol") is **not** carried forward (§12.2).

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
  2  semantic_status   ← roll-up of pairs_touching ONLY (§12.2: derived constraints D1–D5 plus the options
                          chosen under H-11); no pair is re-judged; Tier Z → null + NO-PAIR-EVIDENCE note;
                          always write pair_breakdown (counts by relationship × basis)
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

## 11.5 Frozen verdict ≠ frozen evidence presentation (D-22)

Two different things are frozen in P3a, and they are treated differently:

| | What it is | Status in P3b |
|---|---|---|
| **P3a verdict** | a pair's relationship, basis and type_compatibility in `31-RECONCILIATION-PAIRS.jsonl` | **frozen and consumed as-is.** P3b never changes, re-derives or overrides it |
| **P3a evidence presentation** | the row digest P3a reviewers were shown, built by V1's `row_brief()` | **known defective** (it dropped `dependencies[]`, `lineage_claims[]`, `invariants[]`, `assumptions[]`, completeness, `missing[]` and file provenance). P3b is not required to reproduce it |

The question behind H-04 is therefore **not** "adopt P3A-V2". It is:

> **May V2's row-level presenter, `row_bundle_v2`, be used as a read-only evidence-preparation layer for P3b's
> per-label bundles, without changing any frozen P3a verdict?**

Scope of what V2 has been shown to do: the V2 validation covers candidate generation and evidence-bundle field
completeness (`P3A-V2-REPAIR-VALIDATION-REPORT.md`, `P3A-V2-FIELD-PRESERVATION-MATRIX.md`). It covers **no
adjudication**. `row_bundle_v2` is a pure function of one contribution row and its `02-FILES.jsonl` metadata. It
copies fields and decides nothing, which is why it is the only V2 component this annex proposes to use.

**Consequence.** P3b agents may now see evidence the P3a reviewers did not. If that evidence bears against a
consumed pair verdict, the agent **does not** adjust the roll-up. It records a `VERDICT-EVIDENCE-CONFLICT`
research signal (§13.2) citing the pair id and the rows, and the label is escalated for H-02 consideration.
The roll-up still uses the frozen verdict.

**Alternative if H-04 declines:** agents receive V1-style bundles **plus** direct read access to the full rows in
`03-CONTRIBUTIONS.jsonl` (no information is withheld either way; only the presentation differs).

---

# 12. RELATIONSHIP MODEL

## 12.1 What P3b may and may not decide

P3b **may** decide: per-object statuses; births; layers; absences; dependency edges.
P3b **may not** decide: any pair relationship, basis or type-compatibility; identity/merge of labels; a new enum
value; canonical form.

## 12.2 semantic_status — what v3.5 determines, and what it leaves open (D-09, rewritten in v1.1)

v1.0 proposed a four-row rule table reconstructed from the first run's inline rules. Checking that table against
v3.5's text showed that **one row contradicts v3.5** and **one row is not derivable from it**. v1.1 therefore
splits the rule into **derived constraints**, which are binding because v3.5's text determines them, and
**undetermined choices**, which are human decisions H-11a…d and are not resolved here.

### 12.2.1 The complete v3.5 text on semantic_status (searched 2026-09-24 across v3.5, the extraction contract, prompt1 and prompt2)

1. A11: *"Two questions per pair, answered independently, then a per-object roll-up."*
2. A11: *"PER OBJECT (roll-up of its pairs): semantic_status ∈ { RECONCILED, IDENTITY-UNWITNESSED, HOMONYM-SPLIT,
   CONTESTED }"*.
3. A11 TERMINAL: *"every group and every load-bearing object has pair records, the three per-object statuses …"*.
4. B4 table: `semantic_status` · P3 · the same four values (group "Identity").
5. B4 compact line, the **only worked example**: `RECONCILED(REPLACEMENT/CORROBORATED, type INCOMPATIBLE)`.
6. Related, not the same field: lifecycle `CONTESTED (CONTRADICTION rows)` (A10, B4).
7. A11 Q1: *"default: UNWITNESSED / NONE"*; HOMONYM *"requires positive evidence of two different concepts"*.
8. B4 footer: *"'Uncontradicted' ≠ true"*.

No v3.5 text defines any of the four values in prose, or gives a roll-up rule.

### 12.2.2 Derived constraints (binding)

| # | Constraint | Derived from |
|---|---|---|
| **D1** | `RECONCILED` requires at least one consumed pair. It is written as `RECONCILED(<relationship>/<basis>…)`, which has no content without a pair | texts 2 and 5 |
| **D2** | A label with no pair receives **none** of the four values from P3b. The empty roll-up carries no identity evidence. v3.5's defaults are conservative (text 7), and "uncontradicted ≠ true" (text 8). **Pairless ≠ reconciled** | texts 2, 7, 8 |
| **D3** | A CORROBORATED REPLACEMENT (and, by the same reading, REDEFINITION) is **compatible with RECONCILED** and does not by itself make a label CONTESTED. **v1.0 row 2 and the first run's rule contradicted this** | text 5 |
| **D4** | `CONTESTED` requires contradiction evidence: a CONTRADICTION-type row on the label, or a SOURCE-CLAIMED-CONTRADICTION lineage claim, bearing on the label's identity or meaning | texts 6, 7 (by analogy with the lifecycle field) |
| **D5** | `HOMONYM-SPLIT` requires positive evidence of two different concepts | text 7 |

**What D2 means for execution:** Tier Z labels get `semantic_status: null` + note `NO-PAIR-EVIDENCE` (§9.3).
Choosing a permanent value for them is H-11a.

### 12.2.3 Undetermined choices (human decisions; not resolved here)

| ID | Question | Options | Recommendation and why | What turns on it |
|---|---|---|---|---|
| **H-11a** | Permanent semantic_status for the 1,120 pairless labels | (i) `IDENTITY-UNWITNESSED` — nothing witnesses the label's identity relation to any other form; (ii) a new value such as `NO-PAIR-EVIDENCE` — changes a v3.5 closed list, so it needs a governance act at v3.5 level; (iii) out of the semantic_status domain — interpret v3.5 TERMINAL's "every load-bearing object has pair records" as meaning semantic_status applies only to paired objects | **(iii) with the note kept**, because it adds no value to a closed list and matches TERMINAL's wording. **Uncertainty:** "load-bearing" is undefined for objects in v3.5. And a pairless label may still hide several forms in its own rows: 143 pairless labels have ≥ 10 rows, and `knowledgeos-kernel-concept` shows multi-form labels exist. Those cases go to `HIDDEN-DISTINCTION` signals whichever option is chosen | 1,120 labels |
| **H-11b** | Does a pair verdict of HOMONYM between labels A and B make **A itself** HOMONYM-SPLIT? | (i) yes (first run, 7 of 11 cases); (ii) no — the HOMONYM verdict separates A from B, so it is a reconciliation outcome for A (`RECONCILED(HOMONYM/…)` is well-formed under D1). HOMONYM-SPLIT is then reserved for label-internal evidence that A's own rows carry two concepts | **(ii)**, because a split of the P2a *group* is not a split of the *label*, and (i) would mark both members of every HOMONYM pair as split. **Uncertainty:** under the reading that v3.5's "object" is the candidate group (OMQ-13), (i) is the natural reading | labels touching 61 HOMONYM pairs |
| **H-11c** | Aggregation when a label's pairs are mixed (some positive, some UNWITNESSED) | (i) all pairs must be positive for RECONCILED, otherwise IDENTITY-UNWITNESSED; (ii) any CORROBORATED positive pair suffices; (iii) aggregate only over pairs from identity-bearing group kinds (EXACT-STRING-REUSE, SHARED-NOTATION, SHARED-ALIAS, POSSIBLY-RELATION), leaving out CO-OCCURRENCE and STRING-SIMILARITY | **(i)**, because it follows v3.5's conservative default. Its known cost: 832 CO-OCCURRENCE groups (the weakest P2a signal, `09-ORCHESTRATOR-FLAGS.md`) will pull many labels to IDENTITY-UNWITNESSED. That cost is visible because `pair_breakdown` is mandatory, so the choice can be revisited without re-running agents | most paired labels |
| **H-11d** | Which bases count as positive for D1/H-11c? | (i) CORROBORATED and INFERRED; (ii) CORROBORATED only; (iii) all except NONE, including SOURCE-CLAIMED-ONLY | **(i)**. SOURCE-CLAIMED-ONLY is excluded because v3.5 R7 says *"A source's lineage statement is SOURCE-CLAIMED-\*, never the fact"*. INFERRED is included because v3.5 defines it as continuity shown, with the inference stated. **Uncertainty:** R7 says *"a fact needs corroboration"*, which argues for (ii) | 209 SOURCE-CLAIMED-ONLY and 246 INFERRED pairs |

### 12.2.4 Resulting rule (applied only after H-11a…d are decided; precedence top-down; the record names the rule and the pair ids)

```
0  no pair                                      → null + NO-PAIR-EVIDENCE        (D2; permanent value per H-11a)
1  D4 contradiction evidence present            → CONTESTED
2  D5 positive two-concept evidence on the label→ HOMONYM-SPLIT                  (scope per H-11b)
3  pairs positive per H-11c/H-11d               → RECONCILED(<relationship>/<basis>, type <tc>) per pair, listed
4  otherwise                                    → IDENTITY-UNWITNESSED
```

Rows 0, 3 and 4 are mechanical once H-11 is decided. Rows 1 and 2 need AI reading of the label's rows (§15).

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
| `VERDICT-EVIDENCE-CONFLICT` | evidence visible to P3b (for example through `row_bundle_v2`) bears against a frozen P3a pair verdict the roll-up consumes | §11.5; the `row_brief()` defect (`P3A-IMPLEMENTATION-CONFORMANCE-AUDIT.md`) |

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
An object whose evidence spans A and B is flagged MIXED and raises a `CROSS-TRACK` signal.

**Track-B rule (D-23, tightened in v1.1).** For an object whose earliest evidence is Track A, a Track-B source:

| May be cited as | May NOT be cited as |
|---|---|
| evidence of **later** discovery, extension or refinement: `later_support[]`, `later_refinement[]` (§14.3) | an `ESTABLISHED-*-BIRTH` or `MOVED` for the object |
| corroboration of a Track-A source, with its track tag shown | the **sole** basis of any status, FOUND, or dependency edge |
| contradiction: `contradicted_by[]`, feeding D4 only together with the Track-A statement it contradicts | the meaning the Track-A form *had* (the anti-projection test, §14.4) |
| a research signal of any type | |

A Track-B source may resolve an absence dimension only as `FOUND[S####]` with its track tag and
`relative_timing: LATER-THAN-FIRST-APPEARANCE` recorded, meaning "the corpus later supplied this", never "the object
always had this." What remains open under OMQ-07 is only the **directory mapping** (the MD-043 subcluster doubt),
not the rule.

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
| semantic_status rows 0, 3, 4 of §12.2.4 (pure functions of pair verdicts) | AUTOMATICALLY SAFE **once H-11a…d are decided**; before that, not executed |
| semantic_status rows 1, 2 of §12.2.4 (contradiction or two-concept evidence in the label's rows) | AI REVIEW |
| `row_bundle_v2` evidence presentation (if H-04 approves) | AUTOMATICALLY SAFE (copies fields, decides nothing) |
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

## 16.5 Proposal vs acceptance (D-24, new in v1.1)

> **AI may propose a classification. A P3b record becomes ACCEPTED only through the defined sequence:
> verifier PASS (§20) → audit disposition complete (§21) → human acceptance entry (H-06, §25).**

Mechanics that make this unambiguous later:
1. Every record an agent writes to `ledger-p3b-r2/**/objects.jsonl` carries `record_status: PROPOSED`, and every
   AI-derived field carries `proposed_by: AI-AGENT`. That applies in particular to type_status,
   mathematical_status, layer, births, absence FOUND, dependency edges, and semantic_status rows 1–2. **These
   files are never authoritative**, however complete they look.
2. `ACCEPTED` exists in exactly one place: `32-RECONCILIATION-OBJECTS.jsonl`. Every record there carries
   `acceptance_ref` pointing to the `P3B-GOVERNANCE-LOG.md` entry, `verifier_ref` and `audit_ref`.
3. Nothing downstream (P4–P7, reports, the F-lane, research-signal testing that claims a P3b status) may consume
   a record that lacks `acceptance_ref`. Gate G-11 checks this (§20).
4. A human may accept a batch with named exceptions. Excepted labels stay PROPOSED and are listed in the log.

---

# 17. HUMAN GOVERNANCE BOUNDARY

Human acts are recorded in `P3B-GOVERNANCE-LOG.md` as `{decision_id, date, decider, decision, evidence
consulted, scope}`. **Engineering never accepts its own work** (EP-02 / R-34).

| ID | Decision | Blocks |
|---|---|---|
| H-01 | Approve this annex (or return it) | everything |
| H-02 | Tier-X disposition: re-adjudicate the affected pairs first / accept frozen verdicts with the limitation / hold indefinitely (OMQ-02) | S6 |
| H-03 | **Confirm** D-21: OB0001–OB0003 = historical baseline `P3B-R1`, not production output (OMQ-04) | S4 comparison use |
| H-04 | May `row_bundle_v2` serve as a read-only evidence-preparation layer for P3b, with no frozen verdict changed? (§11.5, OMQ-01) | S3 |
| H-05 | Relationship-ontology gaps (OMQ-03) | nothing in P3b (recorded as signals meanwhile) |
| H-06 | Batch acceptance (§25) | each batch |
| H-07 | Whether the methodology freeze admits this annex | H-01 |
| H-08 | Any corpus-boundary change | — |
| H-09 | Resolution of any escalation marked HUMAN | the affected labels |
| H-10 | Consequential classification: any output a later phase would treat as canonical | P4+ |
| H-11a–d | semantic_status choices left open by v3.5 (§12.2.3): pairless value · HOMONYM scope · mixed-pair aggregation · positive bases | semantic_status rows 0, 3, 4 (other fields may proceed) |
| H-12 | Authorize S5 dispatch and its batching, on the S3 workload measurements and the S4 pilot results (§19.4) | S5 |
| H-13 | Affected-set B: accept exact reproduction (if a reproducible procedure is found), or declare B unreproducible and choose the affected-set definition (§19.1 S1, OMQ-08) | S1, and therefore tiering |

---

# 18. PROVENANCE REQUIREMENTS

**Mandatory on every P3b object record:** `working_label` · `batch_id` · `run_id` · `contract_sha256` ·
`input_manifest_sha256` · `tier` + causing `pair_id`s · every status with its cited `S####` and anchor or
quote, and the rule or reasoning used · `epistemic_class` per claim · `author_role` · `analysis_date` ·
`negative_label` for every absence · `track_composition` · `hindsight_dependency` (may be empty) ·
`what_says_this` / `what_would_make_this_wrong` per status · `record_status` (PROPOSED in the ledger) ·
`proposed_by` per AI-derived field · `pair_breakdown` (counts by relationship × basis; empty for Tier Z) ·
`semantic_status_note` (Tier Z) · `evidence_presentation: V1-PLUS-ROWS | ROW-BUNDLE-V2` (per H-04).
**Additionally in `32-RECONCILIATION-OBJECTS.jsonl` only:** `acceptance_ref`, `verifier_ref`, `audit_ref`.

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
| **S1 INPUT FREEZE** | script | persist the affected set as `AFFECTED.jsonl`, each pair tagged with its source criterion (A / B / C): A and C recomputed by their documented, exact methods; **B per §19.1a**; hash every input | `AFFECTED.jsonl`, `P3B-INPUT-MANIFEST.json` |
| **S2 TIERING** | script | assign Tier U / X / Z per label | `P3B-TIERS.jsonl` |
| **S3 MECHANICAL PREP** | script | stage-1 absence search for every label; track tags; per-label bundle (per H-04); **workload measurements (§19.4)** | `P3B-ABSENCE-SEARCH.jsonl`, `P3B-WORKLOAD.json`, `_batch_input_r2/` |
| **S4 PILOT-R2** | AI + audit | run the persisted contract on a pilot drawn from the 241 `P3B-R1` labels (Tier U and Tier Z only), so every pilot label has a baseline; field-by-field comparison R1 vs R2; measure the stage-2 false-hit rate and the real reading cost | pilot report |
| **S5 BATCHES** | AI + verify + audit | **only after H-12.** Tier-U and Tier-Z batches (Tier Z with semantic_status withheld until H-11a) | `ledger-p3b-r2/OB####-R2/objects.jsonl` |
| **S6 TIER-X** | per H-02 | re-entered only after H-02 | same ledger |
| **S7 CLOSE** | script + AI narrative | merge, TERMINAL assertions (§23), write `32-RECONCILIATION-OBJECTS.jsonl` and `30-RECONCILIATION.md` | §24 |

## 19.1a Affected-set B (D-25, new in v1.1)

**Forbidden:** reconstructing B approximately, whether from memory, from a new keyword list, or from any procedure
not documented for the original screen, and presenting the result as the historical B.

Only two outcomes are legitimate:

| Outcome | Condition | Artifact | Affected set |
|---|---|---|---|
| **B-REPRODUCED** | a procedure is found (a script, keyword list, or answer key in the repository or its history) that regenerates B exactly; exactness is checked against the 20 pair ids listed verbatim in `KSME-21` Phase 2 and the stated count of 28 | `B-REPRODUCED.jsonl` + the procedure | A ∪ B ∪ C = 414 |
| **B-UNREPRODUCIBLE** | no such procedure exists (the state on 2026-09-24, §5.3) | a `B-UNREPRODUCIBLE` record in `AFFECTED.jsonl`'s header, with the search performed | per H-13 (below) |

Under B-UNREPRODUCIBLE, H-13 chooses the affected-set definition:
- **(i) A ∪ C only** (391 pairs, 477 labels), with the whole historical B recorded as a limitation; or
- **(ii) A ∪ C ∪ B-DOCUMENTED** (407 pairs, 497 labels). The 20 listed ids are historical record, copied verbatim
  and not reconstructed. The 7 unidentifiable pairs are recorded as a limitation.

Recommendation: **(ii)**. The documented ids are part of the historical B, not an approximation of it, and
including them only makes tiering more cautious. This is still H-13's decision; it is not assumed.
Either way, every B-derived exposure carries its criterion tag so it can be separated later.

## 19.2 Persisted agent contract (D-06)

Before any AI batch, the full agent instruction text is written to
`prompts/<timestamp>_p3b-agent-contract-r2.md`, committed, and its sha256 recorded in every output record.
An agent receives only: the contract, its batch input slice, and read access to the corpus files of §3.
This closes the reproducibility gap of §5.4.

## 19.3 Dispatch

- One agent per batch; parallel batches allowed (v3.5 R12: no cross-batch identity resolution happens in P3b).
- The orchestrator never reads a corpus file or a full ledger (v3.5 R14).
- After each batch: verifier (§20) → audit sample (§21) → human acceptance (§25).

## 19.4 S3 → S5 authorization gate (D-26, new in v1.1)

S5 cannot be dispatched on estimates. **S3 must measure, and S4 must calibrate, the real workload first.** These
are written to `P3B-WORKLOAD.json` and the pilot report:

| Measurement | From |
|---|---|
| absence dimensions to search (expected 20,107; the recount must match) | S3 |
| stage-1 hits in total; hits per dimension (distribution, not only the mean); dimensions with zero hits | S3 |
| unique files hit; per-file hit concentration (a few very large files can dominate) | S3 |
| total characters of whole-file reading that stage 2 would require | S3 |
| stage-2 false-hit rate (FALSE-HIT ÷ hits read) and FOUND rate | S4 pilot |
| AI reading cost per label and per hit | S4 pilot |
| R1 vs R2 agreement per field on the pilot labels (categorical, §21) | S4 pilot |

The human (H-12) then authorizes S5 and chooses the batching, for example by tier, by weight, or by capping
stage-2 reads per batch. **If the measured stage-2 load is not feasible, the stop-the-line rule applies (§23) and
a revised annex is presented. The absence procedure is never quietly thinned to fit.**

---

# 20. QUALITY GATES

| Gate | Checks | Mechanism |
|---|---|---|
| G-01 Corpus integrity | every cited S-id ∈ `02-FILES.jsonl`; no F-id; no firewalled file quoted | script |
| G-02 Identity integrity | exactly the assigned labels, each once; no unknown label; no renamed or merged label | script |
| G-03 Duplicate handling | a duplicate file (`08-OVERLAP-REGISTER.jsonl`) is never counted as independent corroboration (v3.5 R9) | script |
| G-04 Evidence completeness | every status meets §11.2 minimum evidence; every absence has a search record and negative label | script |
| G-05 Relationship classification | closed lists only (existing verifier's enums); semantic_status satisfies D1–D5 (§12.2.2) and, once decided, the H-11 choices; Tier Z records have `semantic_status: null` + note; `pair_breakdown` matches the consumed pairs exactly | script |
| G-06 Semantic interpretation | blind audit sample (§21) — disagreements individually dispositioned | AI audit + human |
| G-07 Provenance | all §18 mandatory fields present; contract hash matches | script |
| G-08 Hindsight | no birth, FOUND or CONTESTED rests only on SECONDARY-SYNTHESIS, PROVENANCE-UNRESOLVED or Track-B-over-Track-A sources; anti-projection answer present | script + audit |
| G-09 Signal promotion | no signal altered a status; no signal beyond STATUS | script |
| G-10 Reproducibility | re-running the scripts on the recorded input manifest reproduces the mechanical fields byte-for-byte | script |
| G-11 Acceptance integrity | every ledger record is `record_status: PROPOSED`; every record in `32-RECONCILIATION-OBJECTS.jsonl` has resolvable `acceptance_ref`, `verifier_ref`, `audit_ref`; no downstream artifact cites a P3b status lacking them; no `P3B-R1` record is cited as a P3b result | script |

**Batch fails** if any of G-01…G-05 or G-07…G-11 fails, or if G-06 finds a disagreement dispositioned as a
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
3. **Every 5 batches** (v3.5 AUDIT_EVERY): a cross-batch audit checking whether the §12.2 rules (D1–D5 and the H-11 choices) and the absence
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
3a. every Tier-Z label's semantic_status is either the H-11a value or `null` + NO-PAIR-EVIDENCE under a recorded
   H-11a decision to leave it out of the domain (option iii). A still-undecided H-11a does **not** block acceptance
   of the pair-independent fields, but it does block the terminal predicate;
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
| `ledger-p3b-r2/OB####-R2/objects.jsonl` | JSONL | append-only per run; never edited; **PROPOSED records only, never authoritative** (§16.5) |
| `P3B-WORKLOAD.json` | JSON | regenerable (S3 measurements, §19.4) |
| `B-REPRODUCED.jsonl` (only if §19.1a outcome B-REPRODUCED) | JSONL | regenerable from the found procedure |
| `P3B-RESEARCH-SIGNALS.jsonl` | JSONL | append-only (a lifecycle advance is a new line referencing the signal id) |
| `P3B-P1-GAP-CAPTURE.jsonl` | JSONL | append-only |
| `P3B-CORRECTIONS.jsonl` (`P3B-COR-####`) | JSONL | append-only |
| `P3B-ESCALATIONS.jsonl` (`P3B-ESC-####`) | JSONL | append-only |
| `P3B-GOVERNANCE-LOG.md` | Markdown | append-only, human entries |
| `P3B-STATE.json` | JSON | mutable execution position (holds no rules) |
| `audit-p3b/` | MD/JSON | append-only |
| `32-RECONCILIATION-OBJECTS.jsonl` | JSONL | **the only place a P3b record is ACCEPTED**; built from accepted batches (§16.5); written once at S7; a later version is a new file with a version suffix |
| `30-RECONCILIATION.md` | Markdown | v3.5 B1 P3 narrative; written at S7 |
| P4 hand-over package: per-object statuses + births + edges + open signals + escalations | inside `30-RECONCILIATION.md` §"Hand-over" | — |

## 24.2 Existing artifacts — classes

| Class | Artifacts |
|---|---|
| **Immutable (frozen)** | everything committed in `125cfe8371`/`c9e76918b` under `chronological-read/`, including `02-FILES.jsonl`, `03-CONTRIBUTIONS.jsonl`, `20-FAMILIES/**`, `31-RECONCILIATION-PAIRS.jsonl`, `ledger/`, `ledger-p3a/`, `ledger-p3b/OB0001–3`, `_batch_manifest_p3b.jsonl`, `audit-p3a/**`; Master Protocol v3.5; the agent-extraction contract |
| **Append-only** | `09-ORCHESTRATOR-FLAGS.md` (existing convention); all new append-only files above |
| **Regenerable** | only the new regenerable artifacts above |
| **New version required** | any change to this annex, the agent contract, the §12.2 rules or H-11 choices, the signal vocabulary, or a merged P3b output |

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
| **OMQ-01** | May `row_bundle_v2` be used as a read-only evidence-preparation layer for P3b (not "adopt P3A-V2")? (H-04) | V2 validated for candidate generation and bundle field completeness, not for adjudication; `row_bundle_v2` copies fields and decides nothing (§11.5) | use `row_bundle_v2` / V1 bundle plus direct row access / both, compared on the pilot | S3 |
| **OMQ-02** | Tier-X disposition (H-02) | memo §8 recommends holding; KSME-21 recommends bounded re-adjudication of the affected pairs; 477–497+ labels exposed | re-adjudicate first / accept with limitation / hold | S6 |
| **OMQ-03** | Relationship-ontology gaps: two new enum values or a sub-typed field? | two confirmed gaps; the contract recommends a sub-typed field | enum / sub-type / defer | nothing in P3b |
| **OMQ-04** | OB0001–OB0003 | **Resolved in the protocol by D-21** (historical baseline `P3B-R1`, not production); **human confirmation H-03 pending** | confirm / override | S4 |
| **OMQ-05** | Semantic status of the 1,120 pairless labels | **Partly resolved by derivation**: v3.5 does not support RECONCILED for pairless labels (§12.2.2 D1–D2). The permanent value is H-11a | IDENTITY-UNWITNESSED / new value (needs a v3.5-level act) / out of domain with note (recommended) | Tier Z semantic_status only |
| **OMQ-06** | semantic_status roll-up | **Rewritten**: derived constraints D1–D5 are binding; the remaining choices are H-11a–d (§12.2.3) | per H-11 | semantic_status rows 0, 3, 4 |
| **OMQ-07** | Track directory mapping only (the rule itself is fixed by D-23) | mapping partly UNCONFIRMED (MD-043 subclusters, `KSME-20-P3A-TRACK-PROVENANCE-AUDIT.md`) | confirm / refine the mapping | G-08 |
| **OMQ-08** | Affected-set B | not reproducible exactly on 2026-09-24 (§5.3); approximate reconstruction forbidden (§19.1a) | B-REPRODUCED if a procedure is found / B-UNREPRODUCIBLE with (i) A∪C or (ii) A∪C∪B-documented (H-13) | S1 |
| **OMQ-09** | Numbers: batch size, audit sample size, "large label" threshold for human review | v3.5 BATCH_SIZE 40 (files, P1); P3b first run 30 batches of about 83 labels; no calibration evidence for audit sizes | set by the human; the pilot measures cost | S4 |
| **OMQ-10** | Do the derivation-discovery P4/P5 extension candidates (generality, parsimony, falsifiability …) enter the P4 hand-over? | marked EXPERIMENTAL, not adopted | defer to P4 commission / include as annotations | hand-over format |
| **OMQ-11** | Stage-2 reading load | **Converted into a gate** (§19.4, H-12): S3 measures and S4 calibrates before S5 | per the measurements | S5 |
| **OMQ-12** | Does the methodology freeze admit this annex (H-07)? | `.claude/CLAUDE.md` freeze clause; deficiency evidence in §6 | admit / reject | H-01 |
| **OMQ-13** | Is v3.5's "object" in A11 "PER OBJECT" the **label** (the first run's and this annex's unit) or the **candidate group**? | A11 loops "FOR each CANDIDATE GROUP … PER OBJECT (roll-up of its pairs)"; TERMINAL says "every group and every load-bearing object". The two readings differ mainly on HOMONYM-SPLIT (H-11b). The label unit is kept (architecture unchanged); this OMQ records the ambiguity so a later reviewer can revisit it | label (kept) / group | interpretation of H-11b |

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
A ∪ C touches 477 labels; A ∪ C ∪ B-documented touches 497. Alternatives: pause all P3b; ignore exposure.
Reason: follows the actual dependency graph. Uncertainty: B not exactly reproducible (§19.1a, H-13).

**D-06 Persisted, hashed agent contract.** Evidence: first-run instructions exist nowhere on disk. Alternatives:
inline instructions. Reason: reproducibility. Uncertainty: none.

**D-07 Label is the unit of roll-up; the file is the unit of reading.** Evidence: v3.5 A11; KSME-22D. Alternatives:
file unit. Reason: the file unit would restart P2. Uncertainty: none.

**D-08 Two-stage absence search.** Evidence: 20,107 dimensions; KSME-22D false cognates; v3.5 R17. Alternatives:
AI-only; ledger-only; mechanical-only. Reason: mechanical recall at scale, whole-file precision on hits.
Uncertainty: stage-2 volume (OMQ-11).

**D-09 (rewritten in v1.1) semantic_status = derived constraints D1–D5 + human choices H-11a–d.** Evidence: the
complete v3.5 text on the field (§12.2.1), whose B4 worked example `RECONCILED(REPLACEMENT/CORROBORATED, …)`
contradicts v1.0's row 2; and the first run's inconsistency (HOMONYM pairs → 7 HOMONYM-SPLIT and 4 CONTESTED).
Alternatives: v1.0's reconstructed table; leave it to agent judgment. Reason: bind only what v3.5's text
determines, and surface the rest as decisions. KSME-20 showed that unconstrained judgment diverges between passes.
Uncertainty: H-11a–d; OMQ-13.

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

**D-21 (new) OB0001–OB0003 = historical baseline `P3B-R1`, not production output.** Evidence: pre-protocol; unpersisted
instructions; 45/241 exposed; a rule contradicting v3.5 B4; inconsistent on HOMONYM (§5.4). Alternatives: accept
them; accept the Tier-U subset after audit; delete them. Reason: preservation plus a clean R1 → R2 comparison is
scientifically stronger than silent acceptance or deletion. Uncertainty: H-03 confirmation.

**D-22 (new) Frozen verdict ≠ frozen evidence presentation; V2 is used only as the row presenter `row_bundle_v2`.**
Evidence: the `row_brief()` defect; V2's validation scope (candidate generation and bundle completeness, not
adjudication). Alternatives: "adopt V2" wholesale; ignore V2. Reason: repairs what reviewers see without touching
any verdict. Conflicts surface as `VERDICT-EVIDENCE-CONFLICT` signals. Uncertainty: H-04.

**D-23 (new) Track-B rule: later-discovery, corroboration, contradiction or signal — never a retroactive birth or
earlier meaning.** Evidence: v3.5 R10; the hindsight principle; `KSME-20-P3A-TRACK-PROVENANCE-AUDIT.md`.
Alternatives: exclude Track B; treat all tracks alike. Reason: keeps Track-B evidence usable without projecting it
backward. Uncertainty: the directory mapping (OMQ-07).

**D-24 (new) Proposal vs acceptance is structural.** PROPOSED in the ledger; ACCEPTED only in
`32-RECONCILIATION-OBJECTS.jsonl`, with verifier, audit and human references; enforced by G-11. Evidence: v3.5 R20;
EP-02. Alternatives: a status field inside the ledger records. Reason: an AI-written file can never be mistaken for
an accepted result. Uncertainty: none.

**D-25 (new) B is reproduced exactly or declared unreproducible — never approximated.** Evidence: no persisted
procedure; loose counts in the source report (§5.3). Alternatives: rebuild B with a new keyword screen. Reason: an
approximate B presented as the historical B would be fabricated provenance. Uncertainty: H-13 on (i)/(ii).

**D-26 (new) S5 requires measured workload and human authorization.** Evidence: 20,107 dimensions, with an unknown
hit volume and false-hit rate. Alternatives: size S5 from estimates. Reason: prevents quietly thinning the absence
procedure under load. Uncertainty: none about the rule; the numbers come from S3/S4.

---

# 28. EXECUTION READINESS

| Question | Status |
|---|---|
| Corpus boundary fixed? | **Yes** — §3; measured |
| Identity model fixed? | **Yes** — §4 |
| Existing P1/P2 preserved? | **Yes** — §5.1, §24.2 |
| P3a treatment defined? | **Yes** — verdicts frozen and consumed; evidence presentation separable (§11.5); exposure tiering §9.3 |
| P3b operationally defined? | **Yes** — §9, §12.2. Pairless labels: semantic_status withheld by derivation (D2); permanent value H-11a |
| Evidence model defined? | **Yes** — §11 |
| Relationship model defined? | **Yes, with four named human choices** — §12.2.3 (H-11a–d); ontology gaps deferred (OMQ-03) |
| Research-signal model defined? | **Yes** — §13 (+ `VERDICT-EVIDENCE-CONFLICT`) |
| Hindsight control defined? | **Yes** — §14; the Track-B rule is fixed (D-23); only the directory mapping is open (OMQ-07) |
| Automation boundary defined? | **Yes** — §15 |
| AI/human boundary defined? | **Yes** — §16 (incl. §16.5 proposal vs acceptance), §17 |
| Provenance defined? | **Yes** — §18 |
| Quality gates defined? | **Yes** — §20 (G-01…G-11); audit sample size pending OMQ-09 |
| Stopping rules defined? | **Yes** — §23; S5 behind a measured-workload gate (§19.4) |
| Failure/recovery defined? | **Yes** — §22 |
| Outputs defined? | **Yes** — §24 |
| Acceptance criteria defined? | **Yes** — §25, §16.5 |
| Open methodological questions identified? | **Yes** — 13, §27 (OMQ-04 resolved in text pending confirmation; OMQ-05/06 partly resolved by derivation; OMQ-11 converted into a gate) |

### PROTOCOL STATUS

**REQUIRES METHODOLOGICAL DECISION** — narrowed from v1.0. The design questions the review raised are resolved in
the text wherever v3.5 or the repository evidence determines them. What remains is a short list of human decisions.

**To freeze the protocol (no execution):** H-07 (freeze admissibility) → H-01 (approve v1.1).

**Before S1–S4 (mechanical prep and pilot):** H-13 (definition of B) · H-04 (`row_bundle_v2` or V1-plus-rows) ·
H-03 (confirm `P3B-R1` as baseline) · OMQ-09 (pilot and audit sizes).

**Before S5 (production batches):** H-12 (on measured workload) · H-11a–d (semantic_status choices; pair-independent
fields may proceed without them) · OMQ-07 (track mapping).

**Before S6 (Tier X):** H-02.

No execution has been performed under v1.0 or v1.1.

---

# 29. CHANGE LOG v1.0 → v1.1

v1.0 (`20260924_1045_p3b-phase1-continuation-protocol.md`) is kept unchanged. The architecture is unchanged:
corpus boundary, identity model, units, evidence and hindsight model, automation/AI/human boundaries, batch
structure, gates, audit and outputs are all as in v1.0 except where listed.

## Reviewed issues

| # | Review issue | v1.1 change | Sections |
|---|---|---|---|
| 1 | Pairless labels → RECONCILED must not be frozen | Checked against the complete v3.5 text. Derived: RECONCILED presupposes a pair (D1); pairless labels receive none of the four values (D2). Tier Z semantic_status is withheld with a `NO-PAIR-EVIDENCE` note (a note, not a new value); the permanent treatment is H-11a, with options and a recommendation | §9.3, §12.2.2–3, §23 3a, OMQ-05 |
| 2 | §12.2 roll-up table must be decided before execution | Table rewritten as derived constraints D1–D5 (binding) + human choices H-11a–d. **Found while doing this: v1.0 row 2 contradicted v3.5 B4**. The first run did too (3 labels) | §12.2, D-09, §15, G-05 |
| 3 | P3A-V2 question too broad | Reframed: "frozen verdict ≠ frozen evidence presentation". The question is whether `row_bundle_v2` (row presenter only) may serve read-only; `VERDICT-EVIDENCE-CONFLICT` signal added so better evidence never silently overrides a verdict | §9.2, §11.5, §13.2, H-04, OMQ-01, D-22 |
| 4 | OB0001–0003 must not be accepted as production | Fixed as historical baseline `P3B-R1`; the S4 pilot is drawn from those labels for R1 → R2 comparison; G-11 blocks citing them as results | §5.4, §19.1 S4, G-11, D-21, H-03 |
| 5 | B = 28 must be reproduced exactly or excluded explicitly | Approximate reconstruction forbidden; only B-REPRODUCED or B-UNREPRODUCIBLE. Verified today: no procedure persisted. Documented B (20 ids) sized: A∪C∪B-doc = 407 pairs / 497 labels; H-13 chooses (i) or (ii) | §5.3, §19.1a, OMQ-08, D-25, H-13 |
| 6 | AI recommendation vs acceptance ambiguous | Structural rule: ledger = PROPOSED only; ACCEPTED only in `32-…` with verifier/audit/human refs; G-11 enforces it; `ROLLED-UP` state renamed `PROPOSED` | §8, §16.5, §18, §20, §24, D-24 |
| 7 | 20,107 absence searches: measure before scaling | S3 → S5 gate: fixed measurement list (hits, hits/dimension, unique files, reading volume, false-hit rate, cost, R1/R2 agreement); S5 only after H-12; no quiet thinning | §19.1, §19.4, OMQ-11, D-26, H-12 |
| 8 | Track-B rule vague | Rule fixed: Track B may evidence later discovery, corroboration, contradiction or signals; never a retroactive birth, earlier meaning or sole basis. Track-B FOUND carries `relative_timing`. Only the directory mapping stays open | §14.5, OMQ-07, D-23 |

## Internal contradictions found and fixed

| Contradiction in v1.0 | Fix |
|---|---|
| §12.2 row 2 (CORROBORATED REPLACEMENT → CONTESTED) vs v3.5 B4's worked example (→ RECONCILED) | row removed; D3 |
| §9.3 made Tier Z's RECONCILED conditional on OMQ-05, while §12.2 row 4 assigned it unconditionally | both replaced by D2 + H-11a |
| §15 called the whole table "AUTOMATICALLY SAFE once approved", but rows 1–2 need AI reading of rows | split: rows 0/3/4 mechanical, rows 1/2 AI REVIEW |
| §16 "AI output is a recommendation" vs ledger records that looked final | §16.5, G-11, PROPOSED state |
| §19.1 S1 allowed "B reproduced … or marked unreproducible" without forbidding approximation | §19.1a |
| §5.3 "B not computable" without checking the report's listed ids | documented B sized (16 new pairs, 497 labels) |

## New findings recorded (facts, not decisions)

- The first run gave the same input condition two different statuses (HOMONYM pairs → 7 HOMONYM-SPLIT, 4 CONTESTED).
- Of the 1,120 pairless labels, 1,116 are ungrouped; 4 are in groups whose partner never became a label.
- v3.5's "object" is ambiguous between label and candidate group (new OMQ-13); the label unit is kept.

---

**Traceability:** commissioned 2026-09-24 ("Design the method first — Phase-1 KnowledgeOS continuation protocol");
corpus boundary from the human decision of 2026-09-24 (`02-FILES.jsonl` authoritative); v1.1 from the human-relayed
review of v1.0 (same day). Inputs: Master Protocol v3.5 (`prompts/20260911_0221_prompt3-optimized.md`),
`P3A-FROZEN-BASELINE.md`, `P3A-QUALITY-GATE-MEMO.md`, `KSME-20-*`, `KSME-21-EVIDENCE-REPAIR-REPORT.md`,
`KSME-22D-L2-PILOT-RESULT.md`, `audit-p3a/v2/scripts/evidence_bundle_v2.py`, `09-ORCHESTRATOR-FLAGS.md`, the P3b
scripts and ledgers, `.claude/CONTEXT.md`, session logs 2026-09-20…22, and the F-lane protocol (as evidence only).
Supersedes: `prompts/20260924_1045_p3b-phase1-continuation-protocol.md` (v1.0).
