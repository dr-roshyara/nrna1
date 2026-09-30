# KNOWLEDGEOS PHASE-1 CONTINUATION PROTOCOL — P3b OPERATING PROTOCOL · v1.5

**Status: PROPOSED v1.5 — pending final review. NOT frozen. Not executable until §26 approval is recorded.**
**Class: operating annex to Master Protocol v3.5. v3.5 is not modified by this document.**
**Written:** 2026-09-24 · repository HEAD at writing: `3bb068e7b` · P3A freeze: `125cfe8371`, `c9e76918b`
**Supersedes:** `prompts/20260924_1145_p3b-phase1-continuation-protocol-v1.4.md` (v1.4); earlier versions
`…1118…-v1.3.md`, `…1109…-v1.2.md`, `…1056…-v1.1.md`, `…1045….md` (v1.0). All are kept unchanged.
**What changed:** §35 "Change log v1.4 → v1.5": targeted corrections from the v1.4 adversarial review
(`prompts/20260924_1157_p3b-protocol-v1.4-adversarial-review.md`, V-C1…V-C5, V-I1…V-I11), including a freeze
integrity check (§26 item 6, Appendix A.9). The architecture is unchanged. §34, §32, §30 and §29 keep the earlier
logs.

> ## RESEARCH-FIRST PRINCIPLE (v1.2)
>
> **P3b is a controlled research and reconciliation phase. It does not merely construct documentation.
> Chronological reconstruction remains primary; mathematical, statistical, logical and DDD analysis is actively
> encouraged; discoveries, gaps and better interpretations may be proposed; but historical evidence, research
> observations, hypotheses and canonical theory are always kept separate.**
>
> We are not asking the researcher merely to report what is already known. We are asking it to read the corpus
> faithfully enough to discover what the corpus contains that we do not yet know. Every discovery carries its
> provenance and epistemic status, so that **evidence remains evidence, observation remains observation,
> hypothesis remains hypothesis, mathematical interpretation remains interpretation, and theory becomes theory
> only after validation and governance.**
>
> **P3b researches at three scales — the object, across objects, and across the corpus (§9D).** A theory may
> emerge only from relationships between many objects, so the cross-object and corpus-level passes are part of P3b,
> not an afterthought.
>
> **Think broadly. Search for disconfirmation, not only confirmation. Record discoveries. Test where appropriate.
> Canonicalize nothing prematurely.**
>
> **Pre-registration (v1.5).** A researcher may discover the question, propose the hypothesis and design the test. Once
> the test is defined, its population, temporal scope, selection rule, comparison rule and stopping rule are frozen
> before the outcome is seen (§13.10a).
> **Discovery is allowed during P3b. Canonicalization is not.** (§1D)

> Reading rule. This document is self-contained: a researcher who has never seen the conversation
> that produced it must be able to execute it from the repository alone. Every factual statement
> about existing state carries its source. Every methodological decision carries
> **Decision · Evidence · Alternatives · Reason · Remaining uncertainty** (§ "Decision register", D-01…D-58).
> Every question the evidence does not settle is an **OPEN METHODOLOGICAL QUESTION** (OMQ-01…OMQ-21, §27)
> and is not silently resolved anywhere in the text.

---

## Table of contents

1 Purpose (1A research-first · 1B three output layers · 1C historical vs research time · 1D discovery vs
canonicalization · 1E openness) · 2 Scope · 3 Corpus boundary · 4 Identity model · 5 Existing-state assumptions ·
6 Relationship to Master Protocol v3.5 · 7 Relationship to existing post-P3a methodology ·
8 Phase/state model · 9 P3b definition (9A researcher role · 9B research discovery loop · 9C research checklist and sampling ·
9D research scales · 9E cross-object and corpus-level passes, incl. 9E.1 generator register, 9E.2 control design,
9E.3 independence and recurrence) ·
10 Units of analysis · 11 Evidence model ·
12 Relationship model · 13 Research register (discovery channel) · 14 Temporal/hindsight controls ·
15 Automation boundary · 16 AI interpretation boundary · 17 Human governance boundary ·
18 Provenance requirements · 19 Batch protocol · 20 Quality gates · 21 Audit protocol ·
22 Failure/recovery rules · 23 Stopping rules · 24 Output artifacts · 25 Acceptance criteria ·
26 Change control · 27 Open methodological questions · Decision register · 28 Execution readiness · 29 Change log v1.0→v1.1 ·
30 Change log v1.1→v1.2 · 31 How the protocol supports theory research ·
32 Change log v1.2→v1.3 · 33 Appendix A: script and generator specifications ·
34 Change log v1.3→v1.4 · 35 Change log v1.4→v1.5

---

# 1. PURPOSE

Use the admitted Phase-1 corpus to **reconstruct what the KnowledgeOS theory actually says and how it develops**,
and to discover what it contains that is not yet known. P3b reads each object's evidence chronologically. It
reconstructs each stage of development, and identifies mathematical, statistical, logical and domain structures.
It finds contradictions, gaps and missing concepts, and formulates testable research hypotheses. All of this is
preserved with strict separation between historical evidence, interpretation, hypothesis and later theory.

Inside that research purpose, P3b completes Phase 3 of Master Protocol v3.5. It executes the **per-object roll-up**
as a reproducible, auditable method, so that every one of the 2,497 object labels produced by P2 carries
evidence-backed per-object statuses (semantic, type, mathematical), inspected births, a settled layer, resolved
absences and source-supported dependency edges. **The roll-up is the controlled floor of P3b, not its ceiling**
(D-27).

P3b is not a historical summary. It produces two things for v3.5's P4 (v1.2 membership), P5 (validation), P6
(governance) and P7 (synthesis):
- the **reconciliation output**: accepted object records (§24); and
- the **research register**: observations, suggestions, gaps, hypotheses, structure candidates and
  methodological deficiencies (§13).

Governing order (v3.5 headline, unchanged):

```
RECONSTRUCT FIRST → RECONCILE LATER → CANONICALIZE LAST → SYNTHESIZE WITH PROVENANCE
```

**Reconstruction and reconciliation are active research activities, not passive documentation.** P3b reconstructs,
reconciles and **discovers**, at the object, cross-object and corpus scales (§9D). It canonicalizes nothing and
does not write the theory (that is v3.5 P7). It **may propose theory candidates** (`THEORY-CANDIDATE`, layer C),
which remain hypotheses.

## 1A Research-first principle (D-27)

Stated at the head of this document. Operationally it means:
1. the per-label procedure (§9.8) is the controlled core, and the research discovery loop (§9B) runs around it;
2. the researcher is a senior multidisciplinary analyst, not an extractor or a classification engine (§9A);
3. the research register is the **primary discovery channel**, not an exception report (§13.1);
4. the existing schema and vocabularies are **operational constraints, not a claim of completeness** (§1E);
5. research runs at **three scales**: object, cross-object and corpus (§9D, §9E);
6. every serious hypothesis is exposed to **disconfirmation** (§13.10), and every structure candidate meets a
   formal standard (§13.11).

## 1B Three research output layers — never collapsed (D-28)

| Layer | What it holds | Epistemic classes allowed | Where it lives | Example |
|---|---|---|---|---|
| **A. Historical reconstruction** | what the corpus states or demonstrates at its chronological point | `SOURCE`, `INFERENCE` (step written out) | object records: statuses, births, the per-label `timeline` (§14.3) | "S0472 defines K as …" |
| **B. Research observation / discovery** | what the researcher notices while analysing the evidence | `RESEARCH-OBSERVATION`, `DOMAIN-INTERPRETATION`, `EXTERNAL-THEORY-COMPARISON` | research register (§13) | "S0472 and S0618 appear to use K in incompatible ways." |
| **C. Research hypothesis / proposal** | a possible explanation or improved structure; provisional and testable | `RESEARCH-SUGGESTION`, `HYPOTHESIS`, `THEORY-CANDIDATE` | research register (§13) | "Hypothesis: K may denote two distinct objects that were historically conflated." |

Rules:
1. Layer-B and layer-C content **never** enters a layer-A field. A hypothesis is never written into historical
   reconstruction as though the earlier source contained it (enforced by G-12).
2. Layer A may be **informed** by B/C only through a new, source-grounded layer-A record, never by copying.
   Example: a hypothesis prompts a search, and the search finds a SOURCE.
3. Every register record points to the layer-A records and S-ids it rests on. Layer A never depends on the register.

## 1C Historical time vs research time (D-29)

| | Meaning | Recorded as |
|---|---|---|
| **Historical time** | when evidence actually appears in the corpus | the S-id and its historical position with date basis (§14.2) |
| **Research time** | when the researcher recognized a pattern | `research_time` (UTC timestamp) + `run_id` + `contract_sha256` on every register record |

"S0400 contains the first evidence of X" is historical. "During analysis on 2026-…, the researcher recognized that
X and Y may form a partial order" is research-time. **A research-time statement is never backdated to the S-id it
cites.** The register records both, in separate fields.

## 1D Discovery is allowed; canonicalization is not (D-30)

| Allowed in P3b (register, labelled) | Not allowed in P3b |
|---|---|
| "There appears to be a lattice structure over these statuses." (HYPOTHESIS) | "KnowledgeOS theory contains a lattice." |
| "This looks like an aggregate boundary." (DDD HYPOTHESIS) | "This is the canonical aggregate boundary." |
| "Label X may conflate identity and state." (RESEARCH-SUGGESTION) | splitting, merging or renaming label X |
| "The schema cannot express this relation." (SCHEMA-LIMITATION) | adding an enum value |
| "The roll-up rule mishandles this case." (METHODOLOGICAL-DEFICIENCY) | changing the rule mid-run |

Canonical adoption belongs to v3.5 P5 (validation) and P6 (governance), through recorded human acts.

## 1E Openness — the corpus may challenge the method (D-31)

The protocol must remain open to theory structures that are not present in its initial methodology, terminology or
ontology. Therefore:
- existing enums are **operational constraints** on the reconciliation output, not the vocabulary of the theory;
- existing DDD models, mathematical interpretations and object boundaries are **hypotheses and candidates**;
- existing terminology is **evidence**, not necessarily canonical terminology;
- the research register's topic vocabulary is **seeded, not complete** (§13.3);
- when the evidence does not fit the method, the researcher reports it (`SCHEMA-LIMITATION`,
  `METHODOLOGICAL-DEFICIENCY`) **instead of adapting the evidence to fit the method**. The method changes only
  through change control (§26).

---

# 2. SCOPE

**In scope**
- The P3b per-object roll-up for the 2,497 labels in `20-FAMILIES/_derived.json["reconciliation_objects"]`.
- Targeted absence searches over the Phase-1 corpus (§3) for completeness dimensions recorded as
  `NOT-EVIDENCED-IN-CAPTURE`.
- Chronological reconstruction of each label's evidence (the per-label timeline, §14.3), within the reading scope
  of §9B and OMQ-14.
- Active research over that evidence (§9A–9C) and a **research register** (§13), the primary discovery channel.
  It never changes a P3b status or a P3a verdict.
- **Cross-object and corpus-level research passes** (§9E) over the reconstructed object records and the register.
  Findings go to the register only.
- Tests of research hypotheses **within the admitted corpus** (search, whole-file reading, formal or mathematical
  checks), where appropriate and within the budget set under H-12 and OMQ-16.
- The P3 close (v3.5 A11 TERMINAL) and the hand-over to P4.

**Out of scope for this protocol**
- Re-running or regenerating P1, P1c, P2a, P2b, or P3a (§4 of the commission; D-04).
- Re-adjudicating P3a pairs. Whether the 414-pair affected set is re-adjudicated is a human decision
  (OMQ-02); if approved, it is governed by a separate commission, not by this document.
- P4–P7 execution. This protocol ends at the P3 close and the P4 hand-over package (§8, §24).
- Any file outside `02-FILES.jsonl` (§3).
- Theory **conclusions**, canonical adoption, or methodology **changes** (§17). Discovering, hypothesising and
  suggesting are in scope (§1D); concluding and adopting are not.

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
| Research record | `P3B-RS-#####` | one research-register record (§13); "research signal" in v1.0/v1.1 | this protocol | new |
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
- **Source-file degree** (distinct labels per source file, measured 2026-09-24 over `03-CONTRIBUTIONS.jsonl`):
  2,681 files with ≥ 1 label; median 2, p75 3, p90 6, p95 8, p99 14, max 42. The documented registry/census files
  (`09-ORCHESTRATOR-FLAGS.md` "shared large-registry-row" pattern) are at the top: S0239 = 42 and S0237 = 37 (both
  SECONDARY-SYNTHESIS), S0240 = 19, S0241 = 15, S2523 = 11, S2528 = 11, S2524 = 10.
- **Lineage-claim kinds outside v3.5's closed list** (a fact, not decided here): P1 rows carry
  `SOURCE-CLAIMED-*` kinds that v3.5 B5 does not list: CORRECTION (on 166 labels), CONFIRMATION (9),
  DISTINCTION (3), VALIDATION (3), REVISION (2), CORROBORATION (1). v1.4 uses only the v3.5-listed kinds in
  mechanical criteria. The out-of-list kinds are recorded here for a SCHEMA-LIMITATION record at S3 (§13.5); P1 is
  not edited.

---

# 6. RELATIONSHIP TO MASTER PROTOCOL v3.5

**Decision D-01:** this document is an **operating annex** under v3.5, not a successor master protocol and
not a competing one.

| | |
|---|---|
| **Inherited unchanged from v3.5** | R0–R20 in full; A11 per-object roll-up semantics and all its closed lists; B3 layers; B4 status vector; B6 worked cases; FINAL RULES; the P4–P7 definitions (A12–A13) and the GATE |
| **Changed** | nothing in v3.5's text. Where this annex is stricter than v3.5 (for example, persisted agent contracts, mandatory negative-search labels, SECONDARY-SYNTHESIS hindsight rule), the stricter rule applies to P3b only |
| **Newly introduced** | the P3a→P3b input-exposure model (§9.3); the persisted agent contract (§19.2); a two-stage absence search (§11.4); a research register as the primary discovery channel with a researcher role, discovery loop and checklist (v1.2: §1A–1E, §9A–9C, §13) (§13); hindsight controls (§14); the automation boundary (§15); per-batch quality gates and blind audit (§20–21); correction and supersession records (§12.3); explicit human decision points (§17) |
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
        → S5 BATCHES (Tier U, Z) → S5a CROSS-OBJECT PASS → S5b CORPUS PASS
        → [S6 TIER-X DISPOSITION (per H-02) → S6a cross-object re-pass for new participants] → S7 CLOSE
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

Two linked objectives per label, in this order of dependence:

1. **Reconstruct and reconcile (controlled floor).** Produce one roll-up record answering v3.5 A11 "PER OBJECT":
   the per-label chronological timeline (§14.3), semantic_status, type_status, mathematical_status, births
   inspection, primary_layer + secondary_roles, absence resolution, and typed dependency edges. Each carries its
   basis. **No pair verdict is decided**; pairs are P3a's.
2. **Research (§9A–9C).** Analyse the reconstructed evidence mathematically, statistically, logically and as a
   domain model. Record what is discovered (observations, gaps, suggestions, hypotheses, structure candidates,
   methodological deficiencies) in the research register (§13), labelled by output layer (§1B) and epistemic class.

3. **Cross-object and corpus research (§9D–9E).** Compare timelines, states, transitions, invariants,
   dependencies, terminology, types and structures **across** objects, and look for structures that recur across
   the corpus. Findings are recorded in the register at scale `CROSS-OBJECT` or `CORPUS`.

Objectives 2 and 3 never alter objective 1's fields. Objective 1 is never reduced to make room for them.

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
  1  read the label bundle, its family .md, and every row the bundle cites, IN HISTORICAL ORDER (§14.2 date
     rules; BULK blocks unordered); read whole every source file that carries a birth, a change of definition,
     type or meaning, or a contradiction (reading scope §9B / OMQ-14); build the per-label timeline (§14.3)
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
 10  research         ← run the discovery loop (§9B) and the checklist (§9C) over steps 1–8; record every
                          observation, gap, suggestion, hypothesis or deficiency in the research register (§13);
                          never alter 1–8 because of a register record
 11  self-check        ← §20 per-record checks, including G-12 layer separation; then NEXT label
```

## 9A Researcher role — senior multidisciplinary analysis (D-32)

The agent works as a combination of **senior mathematician · senior statistician · DDD/domain architect ·
computer-logic/theory researcher · epistemic/provenance analyst**. It is not a document extractor or a
classification engine. It is expected to make substantive research observations, and doing so is part of the job.

While reading, it actively investigates whether the evidence contains:

| Lens | Structures to look for (non-exhaustive) |
|---|---|
| **Mathematical** | sets/subsets · equivalence relations · partial orders · lattices · graphs · state spaces · transitions · functions/mappings · invariants · algebraic structures · measures · probabilities · compositional structures · fixed points · monotonicity · conservation / non-collapse rules · dependency structures · counterexamples · undefined or indeterminate states |
| **Statistical** | populations · samples · observations · variables · distributions · conditional relationships · independence/dependence · missingness · measurement definitions · uncertainty · sampling problems · selection effects · false positives/negatives · calibration · reproducibility · falsifiability |
| **DDD / domain** | bounded contexts · aggregates · entities · value objects · identities · invariants · commands · events · policies · capabilities · state transitions · domain services · ownership · authority · terminology boundaries · contextual meanings · identity vs similarity · domain dependencies |
| **Logic / theory** | definitions · axioms · propositions · implications · contradictions · necessary vs sufficient conditions · invariants · exceptions · counterexamples · state-transition rules · hidden assumptions · undefined terms · type incompatibilities · circular definitions · non-equivalent formulations |

Discipline that goes with the role:
- Domain knowledge **informs the research register**. It never sets a layer-A status (§11.1). A lens reading of a
  source is `DOMAIN-INTERPRETATION` or `RESEARCH-OBSERVATION`, never `SOURCE`.
- **No discovery is forced into an existing enum** (§12.4, §13.5). If nothing fits, record a `SCHEMA-LIMITATION`.
- A lens that finds nothing records nothing. Absence of a positive finding needs no entry.

## 9B Research discovery loop (around the per-label procedure)

```
READ CHRONOLOGICALLY (the label's evidence in historical order; whole files per the reading scope)
  → RECONSTRUCT HISTORICAL STATE at each timeline point               [layer A]
  → COMPARE WITH THE PREVIOUS STATE
  → IDENTIFY CHANGE / CONTINUITY / CONTRADICTION                      [layer A: descriptive; layer B: meaning]
  → MATHEMATICAL · STATISTICAL · DDD/DOMAIN · LOGIC/THEORY ANALYSIS   (§9A lenses)
  → RESEARCH OBSERVATION                                             [layer B]
  → RESEARCH SUGGESTION / HYPOTHESIS / GAP                            [layer C / gap record]
  → TEST, OR DEFINE WHAT WOULD TEST IT                                (within the admitted corpus; §13.4)
  → RECORD WITH PROVENANCE                                           (§13.6 schema; §1C research time)
  → DO NOT CANONICALIZE                                              (§1D)
```

The per-object procedure (§9.8) runs **underneath** this loop, unchanged in its controls.

**Chronology is both an evidence constraint and a research instrument.** It is not reduced to computing
`best_historical_date`. The researcher:
1. reads a label's evidence in historical order (§14.2), never in `source_id` order (v3.5 R1: ingestion ≠ argument
   order);
2. reconstructs what is present at each stage;
3. separates what was expressed at time t from what becomes visible only later;
4. identifies changes, continuities, contradictions, refinements, rejected ideas, terminology changes,
   mathematical changes and domain-boundary changes;
5. never uses a later interpretation to rewrite an earlier state (v3.5 R10; §14.4);
6. **remains free to recognize, from later evidence, that an earlier interpretation (the corpus's own, P2's, P3a's,
   or a previous agent's) was incomplete, ambiguous or wrong**; and
7. records that recognition as a **later research observation or hypothesis**, carrying research time (§1C),
   never as a silent rewrite of the historical record.

**Reading scope (OMQ-14).** "Reading chronologically" means reading **each label's evidence** chronologically: its
rows, and in full the source files named in step 1 of §9.8. It does **not** mean re-reading all 2,779 files in
sequence. That would restart P1, which D-04 forbids and the human's boundary decision excludes ("do not restart the
corpus read"). Whether a wider reading scope is wanted is OMQ-14.

## 9C Research checklist — what the researcher must think about

**The checklist is a cognitive control mechanism, not a documentation obligation.** It exists so that the
researcher asks the right questions, not so that 2,497 × 23 answers get written down. No object is required to
yield positive answers, and a "no" is never recorded. **Only positive findings are recorded, as research records or
timeline content.**

It is applied **in full, with a short record of which questions were examined**, to the labels in the **checklist
population** defined by the sampling rule below. For every other label the researcher still considers it, and
records only positive findings.

```
 1 What exactly does the source claim?               13 Is there a DDD/domain interpretation?
 2 What changed from the previous chronological state?14 Is there an invariant?
 3 What remained invariant?                          15 Is there a transition rule?
 4 Is the terminology stable?                        16 Is there a missing definition?
 5 Is the identity stable?                           17 Is there a missing relationship?
 6 Is the type stable?                               18 Is there a missing measurement or test?
 7 Is the mathematical meaning stable?               19 Is there a counterexample?
 8 Is the operational meaning stable?                20 Does a later document clarify, or merely reinterpret, an earlier concept?
 9 Is there a contradiction?                         21 Would the interpretation survive without the later document?
10 Is there a hidden distinction?                    22 What would falsify the current interpretation?
11 Is there a possible mathematical structure?       23 What research question should be carried forward?
12 Is there a statistical interpretation?
```

Questions 1–3 feed layer A (the timeline). Questions 20–21 are the anti-projection test (§14.4). All others feed
the register.

### 9C.1 Checklist population — a sampling decision, not an importance judgment (D-36, corrected v1.4)

Choosing which objects get the full analysis is a **sampling decision**. If only objects already believed to be
important get full analysis, the method systematically misses obscure structures, low-frequency invariants, rare
counterexamples, unexpected boundaries and weak but fundamental relationships. The researcher therefore **never**
chooses the population. It is computed mechanically by the sample-plan script (Appendix A.5) and recorded in
`P3B-SAMPLE-PLAN.jsonl`.

```
checklist population (per tier run)  =  PURPOSIVE set  ∪  STRATIFIED RANDOM sample
```

**Purposive set — mechanical definitions (v1.4).** "Formal row" := a contribution row with a non-empty
`type_signature`. The v1.3 criteria are replaced, because they selected ≥ 56.7% of labels (audit report `20260924_1135_p3b-protocol-v1.3-adversarial-audit.md` §0) and so were not
a high-signal minority. The **recommended** criteria (OMQ-15 decides), with sizes measured 2026-09-24 over all 2,497
labels (Tier-X labels among them are sampled in their own S6 run):

| Criterion | Labels |
|---|--:|
| a CONTRADICTION-type row | 245 |
| ≥ 2 formal rows (non-empty `type_signature`) | 193 |
| a MATH-/STAT-/TYPE-QUESTION review_flag | 190 |
| a **strong** lineage claim: `SOURCE-CLAIMED-` REPLACEMENT, REDEFINITION, RETRACTION, CONTRADICTION or SEPARATION (v3.5-listed kinds only) | 217 |
| **Union** | **589 (23.6%)** |

Alternatives measured: without strong lineage, 501 (20.1%); v1.3 criteria without Tier X, 1,326. MIXED track is
**not** a purposive criterion until OMQ-07 is decided (C5). **Tier X is not a purposive criterion for the S5 run**:
Tier-X labels are held until H-02, so they are sampled by the same rule in their own run (S6).

**Stratified random sample.** Drawn only from labels **not** in the purposive set, so it includes ordinary labels on
purpose:
- **Strata (v1.4, three dimensions, so strata stay populated):** `row_count` band (1 · 2–3 · 4–9 · ≥ 10) ×
  `pair_count` band (0 · 1–2 · ≥ 3) × provenance mix (PRIMARY-only · any non-PRIMARY). Other variables (layer,
  birth count, track) are recorded per label for analysis, not used to stratify. The cut-points are proposed; OMQ-15
  decides.
- **Allocation:** proportional to stratum size, with a floor of `f` labels per non-empty stratum and a total size of
  `n`. `f` and `n` are OMQ-09/OMQ-15.
- **Draw:** Appendix A.5 (labels sorted by `working_label`, `random.Random(seed)`, seed recorded).
- **Inclusion probabilities** are recorded per sampled label, as stratum sample size ÷ stratum size.

**Statistical rules (RC-9):**
1. **Pre-registered outcome.** Before S4, the outcome metric is fixed in `P3B-SAMPLE-PLAN.jsonl`:
   *label yields ≥ 1 record, passing G-09 and G-12, of kind HYPOTHESIS, STRUCTURE-CANDIDATE, SCHEMA-LIMITATION or
   GAP-with-NOT-FOUND-AFTER-CENSUS*; secondary: count of such records. (v1.5: the same mechanical criterion for every
   kind. The v1.4 "audit-confirmed" wording measured GAPs unevenly, because the audit only samples them.)
2. **Weighting.** Every corpus-level rate estimated from the random component is weighted by inverse inclusion
   probability. Unweighted rates are reported only as sample descriptives.
3. **Purposive vs random comparison.** The phase report gives the outcome rate in the purposive set and the
   weighted rate in the non-purposive population. The second estimates what the purposive criteria miss.
4. **Blinding (partial).** The agent is told only that a label is **in the population**, never why (purposive or
   random), and never sees the sample plan. The label's own evidence may reveal purposive signals (a CONTRADICTION row,
   a review flag), so this blinding is **partial** and is reported as such.
5. **Design frozen before S4.** Criteria, strata, allocation, `n`, `f` and seed are fixed before the pilot. A later
   change needs change control, a **new seed**, and preservation of the old plan. Both analyses are reported. Sample
   sizes may be informed by S3 **population counts**, never by observed research outcomes.

Labels outside the population still get the full per-label procedure (§9.8) and positive-only research.

## 9D Research scales (D-34)

| Scale | Unit | Typical questions | When |
|---|---|---|---|
| **OBJECT** | one label's evidence and timeline | What does this object mean; how did its meaning change; does its type change; does it carry an invariant or a transition; is identity confused with state? | per label, S5 (§9.8, §9B) |
| **CROSS-OBJECT** | a set of labels | Do several objects share a transition structure? Are there equivalence classes, partial orders or dependency graphs? Do several objects obey the same invariant? Are two apparently different concepts isomorphic? Are bounded contexts emerging? Does A change when B changes; does B constrain C; does C measure A? | S5a / S6a (§9E) |
| **CORPUS** | the whole accepted reconstruction plus the register | Does a mathematical structure recur across domains? Is there a general state-transition algebra, a common epistemic structure, universal invariants, a general measure? Is there a recurring relation between evidence, determination, state and validation? | S5b (§9E) |

The reviewer's working names "R1/R2/R3" are **not** used, because `P3B-R1` and `-R2` already name P3b runs (§4, §5.4).
The scale is recorded in the register field `scale` (§13.6).

Corpus-scale findings are where the theory may emerge. **They are still hypotheses.** "A recurring partial-order
structure may underlie …" is a `THEORY-CANDIDATE`. "KnowledgeOS theory is a partial order" is canonicalization,
and forbidden (§1D).

The phases the reviewer named map onto the scales: **A** per-label chronological reconstruction = OBJECT;
**B** cross-label comparison and **C** cross-object mathematical/statistical/DDD analysis = CROSS-OBJECT;
**D** corpus-level candidate theory = CORPUS.

## 9E Cross-object and corpus-level research passes (D-35, corrected v1.4)

**Inputs.** Object records and timelines produced in S5, the research register, the P3a pairs (frozen, consumed),
`03-CONTRIBUTIONS.jsonl`, and whole-file reading of the admitted sources a candidate needs. **This is not a corpus
re-read** (D-04): whole files are read only to check a specific candidate. At pass start, the object records,
timelines and register are **snapshotted and hashed** (`P3B-PASS-SNAPSHOT.json`, §19.5). The pass reads only the
snapshot.

Whether the passes may use PROPOSED records or only ACCEPTED ones is **OMQ-17**. Whatever the answer, every finding
records the acceptance state of each participating record **at research time**. **Re-examination is transitive**
(RC-6): when any object record, timeline point or register record is corrected or rejected, every record listing it in
`derived_from_records[]`, directly or through a chain, is re-examined, and a new register line records the outcome.

**Step 1 — comparison candidates (mechanical)** → `P3B-CROSS-CANDIDATES.jsonl`. Sets of labels are proposed by the
generators in §9E.1. Each candidate records `generator`, `generator_version`, `parameters`, `defining_property`,
`inputs_ai_produced` (yes/no), its member labels, and matched controls (§9E.2). Candidates are hypotheses for
investigation, never findings.

**Step 2 — cross-object analysis (AI REVIEW, blinded).** Candidate sets and their matched controls reach the analyst
**interleaved and unlabelled** (§9E.2). For each set, compare timelines, states, transitions, invariants, dependencies,
terminology, types and structures through the §9A lenses. **Labels that share a P3a pair are not re-judged for
identity**: any sameness or identity claim between them is recorded only as `VERDICT-EVIDENCE-CONFLICT` (RC-13).

**Step 3 — corpus pass (AI REVIEW; after S5a).** Look across the CROSS-OBJECT findings and the register for
structures that **recur** across independent object sets (§9E.3). A CORPUS record states what the structure
**explains or predicts** that could be checked in the corpus (RC-13). **A recurring pattern is not a theory candidate
merely because it appears many times.**

**Mandatory content of every CROSS-OBJECT finding** (checked by G-13):
- `participating_objects[]`, each with its own source evidence (S-id + anchor), acceptance state at research time,
  and tier;
- `derived_from_records[]` (object records, timeline points, register records it rests on);
- `common_structure`, stated per §13.11 where it is a structure;
- `differences`: where participants do **not** fit the structure;
- `competing_explanation` (at least one);
- the disconfirmation record (§13.10);
- `falsification_condition`;
- `temporal_scope` (§14.4c);
- `generator_basis`: the generator that proposed the set, and whether the finding is **DESCRIPTIVE** (restates the
  generator's defining property, §9E.1) or **DISCOVERY**. The classification is **taken from the pre-registered
  (generator × structure class) mapping** in the H-15 pass plan, never decided after the reveal. Findings on control sets
  carry `generator_basis: CONTROL` (§9E.2 item 9);
- `control_comparison`: the pooled generator × structure-class comparison and its decision (§9E.2 items 5–7), or
  `NOT-APPLICABLE` for a DESCRIPTIVE finding;
- `research_status` (§13.9, §13.9a).

**Mandatory content of every CORPUS finding** (checked by G-13): everything above, plus:
- `participating_findings[]`: the CROSS-OBJECT records it aggregates (RC-6);
- `underlying_objects[]`: the union of their participants, resolvable to S-ids;
- `independent_occurrences`: the count and list under §9E.3, with excluded occurrences and reasons;
- `exceptions`: participant sets where the structure fails;
- `explanatory_content`: what it explains or predicts, and how that could be checked;
- `control_comparison`: **derived** from the pooled S5a comparisons for the structure class across the participating
  findings' generators (§9E.2 item 8), never generated anew and never inherited without that derivation.

**Linking, not duplicating.** A higher-scale record that restates a lower-scale record's structure links it in
`derived_from_records[]` and does not count as a separate occurrence.

**Discipline.** Disconfirmation (§13.10) and, for DISCOVERY findings, the control comparison are mandatory for every
CROSS-OBJECT or CORPUS HYPOTHESIS, STRUCTURE-CANDIDATE or THEORY-CANDIDATE. A finding with a Tier-X participant
carries `exposure: TIER-X` and cannot reach `SUPPORTED-IN-CORPUS` until H-02 is decided. Findings never alter object
records or P3a verdicts.

**Completion.** A pass is complete when every comparison candidate in the plan approved under **H-15** has been
dispositioned: `FINDING-RECORDED`, `NO-COMMON-STRUCTURE-FOUND` (with what was compared), or `DEFERRED` (with the
reason). "No structure found" is a legitimate, recorded result.

### 9E.1 Generator register — population, information, circularity, correction (RC-2)

Every generator is specified in Appendix A.6 (algorithm, parameters, version). Its **defining property** is the
property it selects on. **A finding whose structure is the generator's defining property is DESCRIPTIVE**: it restates
the selection, is recorded as such, and never counts as discovery or recurrence.

| Generator | Population | Information used | Defining property (→ DESCRIPTIVE if "found") | False-structure risk | Correction in v1.4 |
|---|---|---|---|---|---|
| G-SHARED-GROUP | labels in a P2a group | `_derived.json` groups | shared group membership / similarity | CO-OCCURRENCE groups inflated by registry co-listing | CO-OCCURRENCE and STRING-SIMILARITY groups used only if every member has ≥ 1 PRIMARY row not from a file above the co-change cap (H-18); "equivalence class" among group members = DESCRIPTIVE; pairs already judged by P3a are used **only for non-identity structure classes** (the candidate space nearly coincides with P3a's: 1,982 vs 1,793 pairs) |
| G-DEPENDENCY | labels with P3b dependency edges | S5 object records (**AI-produced**) | edge existence / connectivity | "dependency graph" or "partial order" among edge-selected sets is tautological | graph shape = DESCRIPTIVE; discovery must concern a property **not** implied by the edges (for example a shared invariant or transition), tested against controls |
| G-NOTATION | labels sharing notation or alias | `_derived.json` notations/aliases | shared symbol | false-cognate short codes (KSME-22D: 6/15) | sameness never inferred from symbol; each member needs whole-file evidence of referent (§11.3) |
| G-COCHANGE | labels whose timelines change at the same source | S5 timelines (**AI-produced**) + `02-FILES.jsonl` | co-occurrence of change at one source | registry/census co-listing; synthesis restatement (also a hindsight route) | **only PRIMARY sources with degree ≤ cap (H-18)**; excluded sources listed per candidate |
| G-TIMELINE-SIM | labels with similar change sequences | S5 timelines (**AI-produced**) | sequence similarity ≥ θ | shared authoring sessions (mtime blocks) mimic structure | metric and θ fixed in Appendix A.6 and recorded; members from one mtime block flagged; controls matched on historical span |
| G-TYPE-SIM | labels with similar type signatures | contribution rows | normalized-signature similarity | notational convention mimics structure | metric fixed in Appendix A.6; controls matched on has-formal-rows |
| G-TOPIC / G-HYP (**SELF-DERIVED**) | labels sharing register topics, or appearing jointly in HYPOTHESIS/STRUCTURE records | the researcher's own register | the researcher's prior hypothesis | self-reinforcing loop into corpus recurrence | labelled `SELF-DERIVED`; may be used **to test** a hypothesis, **never counted** as an occurrence (§9E.3) |

Which generators run is **OMQ-18 / H-15**. Adding a generator requires this table's row (change control).

### 9E.2 Control design (RC-1)

Controls exist to detect "structure everywhere" artefacts, so they must be **comparable, blind and measured**:

1. **Eligibility.** Controls for a candidate from generator g are drawn from the **same eligible population** as g's
   candidates, **excluding only g's defining link** among members. They are not required to lack the other
   generators' links. Controls apply only to **DISCOVERY** claims, where the tested property is not g's defining
   property. They do not apply to DESCRIPTIVE findings.
2. **Matching.** Each control set is matched to its candidate on **exact arity** and on the matching variables fixed
   by **H-17**. Recommended: `row_count` band, `pair_count` band, provenance mix, historical-span band, and max
   source-file degree band of the members.
3. **Ratio and arity.** `k` controls per candidate and the candidate arities (for example 2 and 3; transitivity
   claims need triples) are fixed by **H-17**. They are recorded per pass.
4. **Blinding (v1.5).** The analysis input interleaves candidates and controls in a seeded random order, **without**
   `generator`, candidate/control labels or defining property. Fields that directly encode a defining link (P2a
   `group_ids`, shared notation/alias lists, dependency-edge lists) are **stripped** from the blinded input. Where the
   evidence text itself still reveals the link (for example a shared symbol in a quote), the pass records
   `blinding_level: PARTIAL`, otherwise `FULL`. The phase report states the level per generator. A reveal script joins
   dispositions back after **all** sets in the pass are dispositioned (Appendix A.7). Disconfirmation and testing after
   the reveal are not blind, and the report says so.
5. **Pre-registered outcome and classes.** Before analysis, the H-15 pass plan fixes: (a) the **structure-class
   vocabulary** used for recording and pooling; (b) the **(generator × structure class) → DESCRIPTIVE/DISCOVERY
   mapping**; (c) the outcome: for each (generator × DISCOVERY class), **the proportion of sets whose analysis records
   that class at ≥ ANALYSED**, among candidates and among their matched controls.
6. **Decision rule (pooled; pre-registered).** Comparisons are **pooled per generator × structure class** over the whole
   pass, never per candidate. A class is **DIFFERENTIATED** for a generator only if the pre-registered rule fixed under
   **H-17** holds: either a minimum difference δ between candidate and control proportions with a minimum number of
   candidate sets `m`, or a named test at level α (for example one-sided Fisher exact). Everything else is
   `CONTROL-UNDIFFERENTIATED`, including: the rule not met; a comparison absent or not pre-registered; the class not in
   the vocabulary; `NO-CONTROL-AVAILABLE` (Appendix A.7). **More frequent than controls is evidence of
   discrimination, not proof of a theory.**
7. **Reporting.** Every comparison reports candidate count, control count, the two proportions, the difference, the
   number of controls actually available, `blinding_level`, and the test result if a test was pre-registered.
8. **CORPUS baseline.** A CORPUS finding's `control_comparison` is derived from the pooled S5a comparisons of its
   structure class for the generators behind its participating findings. It is DIFFERENTIATED only if the class is
   DIFFERENTIATED for every contributing DISCOVERY generator. S5b generates no new controls.
9. **Findings on control sets.** Analysis of a control set is recorded like any finding, with `generator_basis: CONTROL`.
   It feeds the control proportion, is never an occurrence (§9E.3), and never reaches SUPPORTED-IN-CORPUS.
10. **Persistence.** Seed, generator version, parameters, candidate list, control list, matching values, the class
   vocabulary and the DESCRIPTIVE/DISCOVERY mapping are written to `P3B-CROSS-CANDIDATES.jsonl` and the pass plan
   before analysis, and frozen.

### 9E.3 Independence and recurrence at every scale (RC-2; v3.5 R9 carried upward)

v3.5 R9 ("duplicate ≠ independent evidence; repetition is not confirmation") applies at **every** scale. An
**independent occurrence** of a structure is a participant set that:
- has, **for every participant**, at least one PRIMARY source for that participant's role in the structure
  (SECONDARY-SYNTHESIS and PROVENANCE-UNRESOLVED sources do not create occurrences; they may corroborate one);
- shares **no** source with another counted occurrence, and no source that is a duplicate of one
  (`08-OVERLAP-REGISTER.jsonl`);
- shares **no** member label with another counted occurrence (overlapping candidate sets count once);
- was **not** proposed by a SELF-DERIVED generator;
- is not a DESCRIPTIVE finding for the structure in question, and not a finding on a control set.

A CORPUS record reports `independent_occurrences` with every excluded occurrence and the rule that excluded it.
**Recurrence claims use only independent occurrences.**

# 10. UNITS OF ANALYSIS

| Unit | Role |
|---|---|
| **Object label** (`working_label`) | the unit of roll-up, batching, verification and acceptance |
| Contribution row | the unit of evidence citation |
| Source file (`S####`) | the unit of provenance, date, track, and of stage-2 absence reading (always read whole, v3.5 R2 and the KSME-22D finding) |
| P3a pair | consumed input only |
| Research record (`P3B-RS-#####`) | the unit of research discovery (§13); may span labels and files; never part of the roll-up |
| Timeline point | one dated state of a label's evidence (§14.3); the unit of chronological comparison |
| Independent occurrence | a participant set counted toward recurrence under §9E.3 |
| Comparison candidate | a mechanically proposed set of labels for cross-object research (§9E); a hypothesis for investigation, never a finding |

Why the label and not the file: P3b answers v3.5 A11's per-object questions; the file-level unit belongs to the
F-lane and to stage-2 reading. Using files as the P3b unit would restart P2 (D-04).

---

# 11. EVIDENCE MODEL

## 11.1 Epistemic classes (every claim in every output carries exactly one)

| Class | Meaning | May set a P3b status? |
|---|---|---|
| `SOURCE` | stated in a corpus file, cited `[S#### §anchor]` with a verbatim quote | yes |
| `INFERENCE` | follows from cited SOURCE by a stated reasoning step | yes, with basis `INFERRED` and the step written out |
| `RESEARCH-OBSERVATION` | something the researcher notices in the evidence (layer B) | no — research register only |
| `DOMAIN-INTERPRETATION` | uses outside knowledge (mathematics, logic, DDD, statistics …) to read a source | no — annotates, or feeds the register |
| `RESEARCH-SUGGESTION` | a proposed better interpretation, structure or method (layer C) | no — research register only |
| `HYPOTHESIS` | a proposed, testable claim not yet supported (layer C) | no — research register only |
| `THEORY-CANDIDATE` | a proposed theoretical structure (layer C); typically CORPUS scale | no — research register only |
| `EXTERNAL-THEORY-COMPARISON` | correspondence of an observed structure to a known external structure (§13.12) | no — research register only; never historical evidence |
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
research record (topic in §13.3) citing the pair id and the rows, and the label is escalated for H-02 consideration.
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
P3b records it as a research record (kind `SCHEMA-LIMITATION`, topic `ONTOLOGY-GAP`) with the quote, and the P3b
status proceeds on the closed values only. The same applies to anything that semantic_status, type_status,
mathematical_status, layer, dependency kinds, or the seeded topic vocabulary cannot represent (§13.5).

---

# 13. RESEARCH REGISTER — THE PRIMARY DISCOVERY CHANNEL

## 13.1 Definition and standing (D-10, amended in v1.2)

The research register (`P3B-RESEARCH-REGISTER.jsonl`) holds every research record produced during P3b.
**Research records are not merely exception reports. They are the primary discovery channel through which P3b can
expose structures that the reconciliation model did not anticipate.** They hold layers B and C (§1B).

Invariants, unchanged from v1.1 and binding:
- **A research record never changes a P3b status, a P3a verdict, or any frozen artifact.**
- A research record is never a canonical theory element (§1D).
- ("Research signal", as used in v1.0/v1.1, is any research record. Ids stay `P3B-RS-#####`.)

## 13.2 Record kinds (closed; small; operational)

| Kind | Output layer | Purpose |
|---|---|---|
| `OBSERVATION` | B | something noticed in the evidence |
| `GAP` | B | something that appears to be missing (§13.4) |
| `SUGGESTION-RESEARCH` | C | a better interpretation, a missing concept or structure (§13.7) |
| `SUGGESTION-METHOD` | C | a better methodological solution (§13.7) |
| `HYPOTHESIS` | C | a falsifiable explanatory claim |
| `STRUCTURE-CANDIDATE` | C | a candidate mathematical / statistical / DDD / logical structure (with `lens`) |
| `SCHEMA-LIMITATION` | B | the existing vocabulary cannot represent what the evidence shows (§13.5) |
| `METHODOLOGICAL-DEFICIENCY` | B | the current P3b model, schema or procedure is inadequate for the evidence (§13.8) |

Kinds are closed because the gates, the lifecycle and the audit are defined per kind. Adding a kind is change
control (§26).

**The research ontology is not closed merely because the storage schema is closed** (D-37). A discovery that
cannot be faithfully represented by any existing kind is a legitimate discovery. It is recorded as
`SCHEMA-LIMITATION`, states which epistemic type of finding it is and why no kind fits, and is proposed for a new
record kind through change control (H-14). It is never squeezed into the nearest kind.

**THEORY-CANDIDATE is not a record kind (v1.5, V-I1).** A theory candidate is a `HYPOTHESIS` (or `STRUCTURE-CANDIDATE`)
record at `scale: CORPUS` with `epistemic_class: THEORY-CANDIDATE`. Wherever this protocol says "THEORY-CANDIDATE
record", it means that. Its obligations are those of its kind plus the CORPUS mandatory content (§9E).

## 13.3 Topic vocabulary (seeded, extensible — D-33)

Every record carries one or more **topics**. The seed below is the v1.1 signal vocabulary, each topic grounded in a
documented occurrence in this corpus. **The seed is not assumed complete** (§1E).

| Topic | Meaning | Grounding |
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

**Extensibility.** A researcher may use a topic not in the seed by writing it as `PROPOSED:<NAME>` together with a
one-sentence definition and the record that first needed it. It is registered in `P3B-TOPIC-PROPOSALS.jsonl` and
**usable immediately** in the register. Proposed topics enter the seed only by change control (§26), after review.
Examples the researcher may need: `STATE-MACHINE-CANDIDATE`, `ORDER-OR-LATTICE-CANDIDATE`,
`COMPOSITIONAL-STRUCTURE`, `PROBABILISTIC-STRUCTURE`, `DDD-BOUNDARY-CANDIDATE`, `IDENTITY-STATE-CONFLATION`,
`MISSING-TRANSITION`, `MISSING-INVARIANT`. **They are examples, not pre-approved topics**; each needs its
definition when first used. This keeps discovery open while the vocabulary stays governed. Topic tags never
touch the closed enums of the object records (G-05).

## 13.4 Research gaps (kind `GAP`)

For every gap the record states:
- **what** is missing (definition, operation, transition, invariant, measurement definition, population/sample,
  dependency, domain boundary, identity distinction, evidence, test, counterexample, terminology, historical
  transition …);
- **where** the gap becomes visible (S-ids, timeline point);
- **status**, distinguishing clearly:
  - `NOT-FOUND-IN-CAPTURE`: not in the ledger, and no corpus search performed;
  - `NOT-FOUND-BOUNDED`: a search was performed but not corpus-wide;
  - `NOT-FOUND-AFTER-CENSUS`: corpus-wide stage-1 search empty, and every hit resolved false by stage 2 (§11.4);
  - **never** "does not exist" or "never existed". The corpus is a finite capture; `NOT-FOUND-AFTER-CENSUS` is the
    strongest negative P3b may assert (v3.5 R17);
- the search record, if a search was performed (the stage-1 schema of §11.4);
- **what evidence would resolve it.**

Where a gap concerns a completeness dimension of the label itself, the absence procedure of §11.4 governs, and the
gap record references its result rather than duplicating it.

## 13.5 Schema limitation (kind `SCHEMA-LIMITATION`)

Used when the evidence shows something that semantic_status, type_status, mathematical_status, layer, dependency
kinds or the seeded topics cannot represent. **The observation is never forced into the nearest category.** The
record states: source evidence · the exact observation · why the existing vocabulary is insufficient · candidate
interpretation · possible mathematical or domain significance · what evidence would confirm or refute it. A
schema limitation may propose a new concept, relation, structure, invariant, transition rule or domain distinction
as a research discovery. **It never becomes a canonical enum or theory element automatically.**

## 13.6 Record schema — core plus profiles (corrected v1.4)

**Core (every record):** `rs_id` · `kind` · `topics[]` · `lens` (MATHEMATICAL | STATISTICAL | DDD | LOGIC |
EPISTEMIC | CHRONOLOGICAL | MIXED) · `scale` (OBJECT | CROSS-OBJECT | CORPUS) · `statement` · `epistemic_class`
(§11.1) · `output_layer` (B | C) · `supporting_evidence[]` (S-id + anchor/quote, each with `evidence_kind`: CORPUS |
EXTERNAL-THEORY, §13.12) · `historical_anchor` (the S-ids' historical positions with date basis) · `research_time` ·
`run_id` · `contract_sha256` · `model_id` (§18) · `origin` (P3B | EXTERNAL-LANE) · `related_labels[]` ·
`derived_from_records[]` · `lifecycle_stage` (§13.9) · `author_role`.

**Profiles by kind** (only these add obligations):

| Kind | Adds |
|---|---|
| **OBSERVATION** | nothing. `contradicting_evidence[]` is **optional** (RC-10): an observation may remain an observation, cheaply |
| GAP | the §13.4 fields |
| SUGGESTION-RESEARCH / SUGGESTION-METHOD | the §13.7 fields |
| SCHEMA-LIMITATION | the §13.5 fields |
| METHODOLOGICAL-DEFICIENCY | the §13.8 fields |
| **HYPOTHESIS / STRUCTURE-CANDIDATE** (incl. class THEORY-CANDIDATE) | `falsification_condition` · `validation_question` · `competing_hypotheses[]` · the disconfirmation record (§13.10, `contradicting_evidence[]` **mandatory with its search record**) · `temporal_scope` (§14.4c) · STRUCTURE-CANDIDATE additionally §13.11 |
| any record at scale CROSS-OBJECT or CORPUS | the §9E mandatory content for that scale |

## 13.7 Suggestions (kinds `SUGGESTION-RESEARCH`, `SUGGESTION-METHOD`)

When the researcher believes there is a better interpretation, better architecture, missing concept, missing
mathematical structure or better method, it says so as a suggestion. **It never silently modifies the protocol or
the historical interpretation.** Each suggestion contains:

1 observation · 2 evidence · 3 current interpretation or treatment · 4 suggested alternative · 5 reason ·
6 problem it solves · 7 possible risks · 8 evidence needed to validate it ·
9 `execution_impact`: `NONE` (record only) | `DEFER-TO-P4+` | `AFFECTS-METHOD` (→ §13.8 path).

Worked example (format only, not a finding):
> SUGGESTION-RESEARCH · topic `PROPOSED:IDENTITY-STATE-CONFLATION` (definition given) · The object model may be
> conflating state and identity. Evidence: Sxxxx, Syyyy. Current treatment: one working_label. Alternative:
> distinguish object identity from state representation. Reason: the same label occurs with incompatible formal
> definitions. Validation: inspect the chronological transitions and counterexamples. Execution impact: NONE.

## 13.8 Methodological deficiency (kind `METHODOLOGICAL-DEFICIENCY`)

If the evidence demonstrates that the P3b model, ontology, terminology, schema, aggregation rule or procedure is
inadequate, the researcher reports it **rather than adapting the evidence to fit the method**. It may suggest a
missing field, relation, topic or kind; an inadequate classification; a problematic aggregation rule; a
mathematical model that better explains the evidence; a different DDD boundary; a statistical test; or a missing
validation experiment.

Fields: the evidence · the component affected (section/field/rule) · how the evidence conflicts with it ·
suggested remedy · `execution_impact` ∈ {`NONE`, `AFFECTS-CURRENT-BATCH`, `AFFECTS-METHOD`}.

Routing: `AFFECTS-CURRENT-BATCH` or `AFFECTS-METHOD` → escalation (§22) to the human. **The method changes only
through change control (§26).** Execution continues under the current contract unless the human stops it, or the
deficiency makes a gate unpassable. In that case the stop-the-line rule applies (§23).

## 13.9 Lifecycle

```
OBSERVED → ANALYSED → HYPOTHESIS-STATED → TEST-DEFINED → TESTED → STATUS
```
- `OBSERVED`: quote(s) + S-ids + the record that surfaced it. (AI)
- `ANALYSED`: competing explanations listed, at least two when two exist. (AI)
- `HYPOTHESIS-STATED`: one falsifiable statement, epistemic class HYPOTHESIS. (AI)
- `TEST-DEFINED`: what evidence would refute it, and where to look. (AI)
- `TESTED`: the test is run **within the admitted corpus**: stage-1-style search, whole-file reading, or a formal or
  mathematical check. (AI or script)
- `STATUS` ∈ {`SUPPORTED-IN-CORPUS`, `REFUTED-IN-CORPUS`, `UNDETERMINED-FROM-CORPUS`, `ROUTED-TO-GOVERNANCE`}. (AI
  records; consequential routing is human, §17). `SUPPORTED-IN-CORPUS` is not validation in the v3.5 P5 sense.

**Obligation in P3b.** Every record noticed must reach at least `OBSERVED`. **An observation may legitimately
remain an observation** (D-38). Not every observation leads to a hypothesis, and the protocol **must not** be
satisfied by manufacturing hypotheses. A hypothesis is stated only when the researcher has a genuine explanatory
claim that could turn out false. The register audit checks for manufactured hypotheses (§21 item 5).

Every `HYPOTHESIS`, `STRUCTURE-CANDIDATE` and `THEORY-CANDIDATE` must reach at least `TEST-DEFINED`, with a
falsification condition **and** the disconfirmation plan of §13.10. `TESTED` and `STATUS` are performed within the budget fixed under H-12 / OMQ-16, on records selected **by rule, not by agent
choice** (RC-9g): every CORPUS-scale record, plus a seeded random share of the others (share fixed under OMQ-16). This
prevents testing only likely winners. **When `TESTED` is recorded, all four disconfirmation searches must have been
performed.** Testing never blocks batch acceptance, and untested records carry forward to
P4–P7 as research input.

## 13.9a STATUS assignment rules (RC-3, new in v1.4)

A STATUS is assigned **only** by these rules. Anything not meeting them is `UNDETERMINED-FROM-CORPUS`.

| STATUS | Required |
|---|---|
| **SUPPORTED-IN-CORPUS** | (1) **A**: positive support from at least **N_support** independent PRIMARY sources (independence per §9E.3; **N_support is fixed by H-16**); for CROSS-OBJECT, at least N_support participants whose evidence for their role comes from pairwise-independent PRIMARY sources; for CORPUS, at least N_support independent occurrences (§9E.3); **and** (2) **B** and **D** performed at **census scope** (§13.10a: LEXICAL over the full Appendix A.4 corpus lists with `NEGATIVE-CENSUS`; STRUCTURAL-ENUMERATION over the structure's full X), on the population and temporal scope **frozen at TEST-DEFINED**, with no unresolved contradiction or counterexample; **and** (3) **C**: at least one recorded item of corpus evidence that favours H over each listed competing hypothesis; **and** (4) for DISCOVERY findings, a pooled control comparison that is **DIFFERENTIATED** under the pre-registered rule (§9E.2 item 6), and for CORPUS findings the derived comparison (§9E.2 item 8). An absent, unregistered or `NO-CONTROL-AVAILABLE` comparison counts as `CONTROL-UNDIFFERENTIATED` (§9E.2); **and** (5) no Tier-X participant before H-02; **and** (6) every item in the basis is `evidence_kind: CORPUS` |
| **REFUTED-IN-CORPUS** | a **verified** contradiction or counterexample from a PRIMARY source **within H's temporal scope** (§14.4c), quoted, with the check that it applies to H as stated (not to a variant) |
| **UNDETERMINED-FROM-CORPUS** | everything else, including: support below N_support; B or D not exhaustive; a competing hypothesis not discriminated; controls undifferentiated |
| **ROUTED-TO-GOVERNANCE** | a human-review routing (§17); never a truth value |

Explicit prohibitions:
- **Absence of contradiction is never confirmation.** An empty B, even exhaustive, satisfies only condition (2).
- **"Not found" is never "false".** Failing to find support gives UNDETERMINED, never REFUTED.
- **External theory never sets a corpus STATUS** (condition 6; §13.12).
- **Recurrence alone never supports.** Frequency counts only through independent occurrences, and still needs
  (2)–(4).

G-09 checks every STATUS against this table.

## 13.10 Disconfirmation discipline (D-38)

Do not search only for confirmation. For every serious hypothesis H (every HYPOTHESIS, STRUCTURE-CANDIDATE and
THEORY-CANDIDATE):

| Search | Question | Recorded as |
|---|---|---|
| **A — support** | What evidence supports H? | `supporting_evidence[]` |
| **B — contradiction** | What evidence contradicts H? | `contradicting_evidence[]`, **with the search record** (terms, scope, negative label) even when empty |
| **C — discrimination** | What evidence would distinguish H from a competing H2? Where would it be found? | `competing_hypotheses[]` + `discriminating_evidence` |
| **D — counterexample** | Can a counterexample destroy H? Where would one be? | `counterexample_search` (scope, result, negative label) |

**Every operation A–D records** (RC-8, v1.4):
`population` (the enumerated set of labels/objects/sources, or the corpus scope with its file list reference) ·
`method` ∈ {`LEXICAL` (stage-1-style search, Appendix A.4), `STRUCTURAL-ENUMERATION` (check the claimed property on
every tuple of a finite enumerated X), `WHOLE-FILE-READING` (read the listed files whole)} · `completeness` ∈
{`EXHAUSTIVE`, `SAMPLED` (with seed and size)} · `termination_bound` (the size of the population or tuple space; for
STRUCTURAL-ENUMERATION |X|ᵏ for a k-ary property) · `result` · `negative_label` where nothing was found
(NEGATIVE-BOUNDED | NEGATIVE-CENSUS). A counterexample search over a finite X is exhaustive enumeration, which
terminates. A lexical search is bounded by the corpus file list. **An unbounded search is not a valid operation.**

At `TEST-DEFINED`, B–D are **planned** (what, where). At `TESTED`, A–D are **performed**. A hypothesis with an empty
B that has no search record is **confirmation-only** and fails G-09. Elegant patterns found through selective
reading are the specific failure this rule exists to catch.

## 13.10a Pre-registration and freezing of tests (V-C1, new in v1.5)

At `TEST-DEFINED` the record fixes, and hashes, the **test plan**: the hypothesis statement; `competing_hypotheses[]`;
the population and method for A–D; `temporal_scope` (computed, §14.4c); for structural claims, the full X; the decision
rule for STATUS (§13.9a); and the stopping rule (the termination bound). **After TEST-DEFINED none of these may change
in that record.**

- **Census scope for B and D.** A LEXICAL B or D covers the full Appendix A.4 corpus lists and records `NEGATIVE-CENSUS`
  when empty. A STRUCTURAL-ENUMERATION covers the structure's full X. A narrower search is recorded as
  `NEGATIVE-BOUNDED` and can never satisfy §13.9a condition (2).
- **Narrowing is a new record.** If, after seeing results, the researcher wants a narrower population, scope or X, that
  is a **new** hypothesis record. It cites the original record and every counterexample or contradiction its narrowing
  excludes. The original record keeps its result (for example REFUTED-IN-CORPUS). Narrowing never rescues a hypothesis
  silently.
- **Plan hash.** `test_plan_sha256` is recorded at TEST-DEFINED, and G-09 checks that the TESTED record's plan matches.

## 13.11 Formal standard for structure candidates (D-39, corrected v1.4)

"There appears to be a lattice" is not enough for serious mathematical research, and **resemblance is never a
structure claim**. A `STRUCTURE-CANDIDATE` states its structure formally, **where applicable**, for its lens. Any field
it cannot fill is written as `NOT-DETERMINED`, never omitted, because the unknowns are part of the finding.

**Relation source (RC-7a).** Every relation or operation carries `relation_source`:
- `CORPUS-STATED [S-ids]`: a source defines or asserts the relation;
- `RESEARCHER-CONSTRUCTED`: the researcher defines the relation from the data (its definition is written out).

Only `CORPUS-STATED` may be described as "the corpus defines …". A `RESEARCHER-CONSTRUCTED` structure is always
described as "a structure the researcher constructed over …" (hindsight test T-H1).

**Property status (RC-7b).** Each claimed property carries exactly one of:
`STATED-BY-SOURCE [S-ids]` · `VERIFIED-ON-ALL-ENUMERATED-INSTANCES (n, over the finite X)` ·
`INSTANCES-CONSISTENT (m of n checked; SAMPLED)` · `NOT-DETERMINED` · `VIOLATED [S-ids / tuple]`.
A universal property is never "observed". It is stated by a source, verified on a finite enumeration, or open.

**Weakest-structure rule (RC-7c).** The candidate is **named by the weakest structure its STATED/VERIFIED properties
establish**. Example: reflexive + transitive verified, antisymmetry NOT-DETERMINED → *preorder*, not partial order;
partial order with joins and meets NOT-DETERMINED → *partial order*, not lattice. Stronger structures appear only in
`competing_structures[]` (RC-7d), with the properties that would have to hold.

| Lens | Required specification |
|---|---|
| **Mathematical** | underlying set X and its elements (as corpus objects) · relation(s)/operation(s) with `relation_source` and definition · each claimed property with its RC-7b status (for example reflexive, antisymmetric, transitive, closure, identity, associativity, commutativity, joins/meets, monotonicity, fixed points) · the name by the weakest-structure rule · `competing_structures[]` · invariants · counterexamples · mapping to corpus objects · evidence for and against the mapping |
| **Statistical** | population · unit of observation · variables and measurement definitions · sampling or selection mechanism · claimed dependence or distribution · missingness · the test that would assess it, and whether the corpus can support it |
| **DDD / domain** | candidate bounded context and its language · aggregate root, entities, value objects · invariants and who enforces them · commands, events, policies · ownership and authority · boundary evidence (terminology shifts, meaning changes) · counter-evidence · `relation_source` for each boundary claim |
| **Logic / theory** | terms and definitions · axioms or premises (with `relation_source`) · the inference claimed · consistency concerns (contradictions, circularity) · necessary vs sufficient conditions · counterexamples |

Example shape (mathematical):
```
Candidate  (X, ≤)  — named: PREORDER (weakest-structure rule)
X          = { … corpus objects … }
≤          relation_source: RESEARCHER-CONSTRUCTED; defined as: …
Properties reflexive  VERIFIED-ON-ALL-ENUMERATED-INSTANCES (n=…)
           transitive VERIFIED-ON-ALL-ENUMERATED-INSTANCES (n=…)
           antisymmetric NOT-DETERMINED
Competing  partial order (needs antisymmetry) · lattice (needs antisymmetry + joins + meets)
Temporal   ACROSS-TIME (§14.4c)
Status     UNDETERMINED-FROM-CORPUS (§13.9a)
```

## 13.12 Corpus evidence vs external theoretical comparison (D-40)

Two different questions, kept apart:

| | Question | Class | May support |
|---|---|---|---|
| **Corpus evidence** | "Does the admitted corpus contain evidence for this?" | SOURCE / INFERENCE / RESEARCH-OBSERVATION | layer-A claims (SOURCE/INFERENCE only); register records |
| **External theoretical comparison** | "Does this observed structure correspond to a known mathematical, statistical, logical or DDD structure (a known lattice, algebra, statistical model, transition system, logic, pattern)?" | `EXTERNAL-THEORY-COMPARISON` | register records only |

Rules:
1. External comparison is **never historical evidence** and never supports a layer-A field (G-12).
2. It names what it compares against (standard definition, theorem, model, pattern) and states the correspondence
   as a mapping, with any mismatch.
3. Correspondence to a known structure is not evidence that the corpus intended it, which would be hindsight.
4. External comparison uses general domain knowledge. It never uses F-only or F-lane material (§3, §7).
5. Register lifecycle status for an external comparison is recorded separately as `external_correspondence` ∈
   {`CORRESPONDS`, `CORRESPONDS-PARTIALLY`, `DOES-NOT-CORRESPOND`, `NOT-DETERMINED`}. It never changes the
   corpus-evidence status.
6. Correspondence **never** supports "KnowledgeOS *is* structure S", and never enters the basis of any corpus STATUS
   (§13.9a condition 6).

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

## 14.3 Per-label timeline (layer A; mandatory where evidence exists; otherwise NOT-EVIDENCED-IN-CAPTURE)

**Timeline points** (new in v1.2, D-28): an ordered list, in historical order (§14.2), one entry per source that
states something about the label: `{source_id, historical_position, date_basis, order: ORDERED | UNORDERED-BLOCK,
states (SOURCE quote or INFERENCE with step), change_vs_previous}`. `change_vs_previous` ∈ {`FIRST`, `RESTATES`,
`EXTENDS`, `NARROWS`, `CHANGES-DEFINITION`, `CHANGES-TYPE`, `CHANGES-TERM`, `CONTRADICTS`, `RETRACTS`,
`NOT-COMPARABLE`}. These classes are **descriptive**: they say what the text does relative to the previous point.
What the change *means* (for example "these are two different objects") is a layer-B/C research record, not a
timeline value.

**Summary fields:** `first_lexical` · `first_conceptual` · `first_formal` · `first_operational` · `first_governance`
(= v3.5's five birth kinds) · `later_support[]` · `later_refinement[]` · `contradicted_by[]` · `rejected_by[]` ·
`current_lifecycle` (v3.5 B4 lifecycle, still SOURCE-CLAIMED-* until corroborated).

## 14.4 Anti-projection test (per record, AI self-check, audited §21)

For every status that cites a source later than the object's first appearance, the agent answers in the record:
*"Would this status be the same if only sources up to the cited early source existed?"* If not, the status carries
`hindsight_dependency: [S-ids]` and a `HINDSIGHT-RISK` signal.

## 14.4a Later recognition of earlier inadequacy (v1.2)

When later evidence shows that an earlier interpretation was incomplete, ambiguous or wrong, the researcher records
a research record (OBSERVATION or HYPOTHESIS) with `historical_anchor` set to the earlier point and `research_time`
set to now. This covers the source's own interpretation, P2's labelling, P3a's verdict, or an earlier agent's
record. **The earlier timeline point is not edited.** If a layer-A field is itself wrong, correction follows §12.3
(new record + `P3B-COR`). §14.4's anti-projection test still applies to every layer-A status.

## 14.4b Historical time vs research time — enforcement

§1C defines the distinction. G-12 checks that no register record's `research_time` claim appears in a layer-A field,
and that no layer-A timeline point cites a register record as its basis.

## 14.4c Temporal scope of structures (RC-4; corrected in v1.5, V-C2)

§1C separates research time from historical time for **statements**. Structures need the same protection. Every
HYPOTHESIS and STRUCTURE-CANDIDATE, and every CROSS-OBJECT/CORPUS finding, carries `temporal_scope`, **computed
mechanically from the participants' timelines** (§14.3), never chosen by the researcher (Appendix A.10).

**Definitions.** For each participant p: `birth(p)` = the historical position of its first timeline point with
`order: ORDERED`; `end(p)` = the position of its first point with `change_vs_previous = RETRACTS`, or of a
corroborated SUPERSEDED lifecycle, or +∞. For the relation: `evidence(R)` = the historical positions of the sources
cited as supporting the relation.

| Value | Condition (computed) | How it may be described |
|---|---|---|
| `UNORDERED` | any participant, or any relation-evidence source, lies in a BULK/mtime block without order evidence (§14.2) | no temporal claim at all |
| `WITHIN-SLICE [t]` | there is a historical point t with: every participant born at or before t (`birth(p) ≤ t`); none ended at or before t (`end(p) > t`); **and** every relation-evidence source dated at or before t, or all relation evidence in one source. Recorded t = the **earliest** such point | "at [t], the corpus shows …"; the structure **coexisted** historically |
| `ACROSS-TIME` | otherwise | "**across** [periods], the researcher constructed …". **Never** "the corpus had …" at any historical point |

Rules:
1. `WITHIN-SLICE` means **coexistence at a point**, not "inside some interval the researcher chose". An interval
   spanning all participants is **not** a slice (this closes the v1.4 loophole, V-C2).
2. An ACROSS-TIME structure is a **research-time construct**. It may never be described, in any field or report, as
   having existed at a historical point (hindsight test T-H6).
3. **REFUTED within scope.** For WITHIN-SLICE [t], a counterexample must be a source dated at or before t, or a
   participant state alive at t. For ACROSS-TIME, any PRIMARY counterexample among the participants' timelines counts.
   UNORDERED hypotheses can be refuted only by a counterexample that does not depend on order.
4. `temporal_scope` is computed at TEST-DEFINED and frozen with the test plan (§13.10a).
5. G-09 and G-13 check the field against the recomputation, and flag historical-point wording ("at S####", "the corpus
   had", "in August …") on ACROSS-TIME records for audit.

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
| Per-label timeline ordering and date-basis lookup | AUTOMATICALLY SAFE (ordering); AI REVIEW (confirming a date applies to the file, §14.2) |
| Timeline `change_vs_previous` classification | AI REVIEW |
| Research records: observation, gap, suggestion, hypothesis, structure candidate, schema limitation, deficiency | AI REVIEW / RESEARCH-INTERESTING |
| Stage-1-style searches that test a hypothesis or gap | AUTOMATICALLY SAFE |
| Proposed topics (`PROPOSED:*`) | AI proposes; promotion to the seed = HUMAN REVIEW (change control) |
| Checklist population (purposive ∪ stratified random, seed recorded) | AUTOMATICALLY SAFE (the design is OMQ-15) |
| Comparison-candidate generation incl. random control sets | AUTOMATICALLY SAFE (the generators are OMQ-18) |
| Cross-object and corpus analysis; external theoretical comparison | AI REVIEW / RESEARCH-INTERESTING |
| Disconfirmation searches B/D (search part) | AUTOMATICALLY SAFE; interpreting hits = AI REVIEW |
| Pass snapshot hashing; blinded interleaving; reveal join after disposition (Appendix A.7) | AUTOMATICALLY SAFE |
| DESCRIPTIVE vs DISCOVERY classification of a finding | AI REVIEW (audited, §21 item 6) |
| Independent-occurrence counting (§9E.3) | AUTOMATICALLY SAFE (from recorded sources and sets) |
| `METHODOLOGICAL-DEFICIENCY` with impact ≠ NONE | HUMAN REVIEW (escalation §22) |
| Anything touching a Tier-X label | HUMAN REVIEW (H-02) |
| CONTESTED or HOMONYM-SPLIT on a label with ≥ 50 rows | HUMAN REVIEW (sampled, §21) — the threshold is OMQ-09 |
| ONTOLOGY-GAP, cross-track conflict, timestamp anomaly unresolved after stage 2 | UNRESOLVED → escalation |

---

# 16. AI INTERPRETATION BOUNDARY

1. AI output is always a **recommendation** with its evidence (v3.5 R20). It enters the ledger with
   `author_role: AI-AGENT` and never with a governance status.
1a. **AI is expected to do substantive research** (§9A): to notice, compare, abstract, apply domain knowledge,
   propose alternatives, hypothesise and challenge the method. Reticence is not a virtue here. Mislabelling is
   the failure mode, not boldness (G-12).
2. AI may not: decide pairs; merge, rename or split labels; add enum values or record kinds; promote a proposed
   topic into the seed; write to frozen artifacts; set `ACCEPTED`; set a research record to a governance outcome
   (only `ROUTED-TO-GOVERNANCE`); present a research record as SOURCE or place it in a layer-A field; read F-only
   or firewalled files; cite its own earlier output as SOURCE (v3.5 R8); change the method (§13.8 routes it).
3. DOMAIN-INTERPRETATION is labelled as such and is never the basis of a layer-A status. It is a normal input to
   the research register.
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
| H-12 | Authorize S5 dispatch and its batching, on the S3 workload measurements and the S4 pilot results (§19.4), **including the research-testing budget (OMQ-16) and the timeline-reading load (OMQ-14)** | S5 |
| H-14 | Promotion of proposed topics or new record kinds into the controlled vocabulary (§13.3, §26) | nothing in execution (proposed topics are usable meanwhile) |
| H-15 | Approve the cross-object and corpus pass plan: generators, candidate budget, the structure-class list for the pre-registered outcome, and whether inputs are PROPOSED or only ACCEPTED records (OMQ-17, OMQ-18); control design is H-17 | S5a, S5b, S6a |
| H-16 | **N_support**: the minimum number of independent PRIMARY sources (or independent occurrences) for SUPPORTED-IN-CORPUS (§13.9a; OMQ-19) | STATUS assignment in S5, S5a, S5b |
| H-17 | **Control design and decision rule**: matching variables, control ratio k, candidate arities, and the pre-registered decision rule (δ with minimum m, or a named test at α) (§9E.2 items 2, 3, 6; OMQ-20) | S5a |
| H-18 | **Co-change file-degree cap** (§9E.1; OMQ-21). The PRIMARY-only restriction is fixed by §9E.1 and is not part of this decision | S5a (G-COCHANGE, G-SHARED-GROUP filter) |
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
**AI provenance (v1.4, RC-11), on every object record, research record and audit record:** `model_id` (exact model
identifier and version), `generation_parameters` (as exposed by the runtime), `contract_sha256`, `run_id`. The same
contract on a different model is a **different experiment**, recorded as a different run.
**Additionally in `32-RECONCILIATION-OBJECTS.jsonl` only:** `acceptance_ref`, `verifier_ref`, `audit_ref`.

**Mandatory on every research record:** the §13.6 schema, including `historical_anchor` and `research_time`
kept in separate fields (§1C). **Mandatory on every object record (v1.2 addition):** the per-label `timeline`
(§14.3).

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
| **S5a CROSS-OBJECT PASS** | script + AI | **only after H-15.** Generate comparison candidates incl. control sets (§9E step 1); cross-object analysis (step 2) | `P3B-CROSS-CANDIDATES.jsonl`, register records `scale: CROSS-OBJECT` |
| **S5b CORPUS PASS** | AI | recurrence analysis across the cross-object findings and the register (§9E step 3); topic consolidation; THEORY-CANDIDATE records where evidenced | register records `scale: CORPUS` |
| **S6 TIER-X** | per H-02 | re-entered only after H-02 | same ledger |
| **S6a CROSS-OBJECT RE-PASS** | script + AI | after S6: comparison candidates that gained Tier-X participants are re-examined; CORPUS findings they affect get a new register line | register |
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

**The contract must contain (v1.2, extended v1.3):** the research-first principle; the three output layers and
their fields (§1B); the historical/research time rule (§1C); the discovery/canonicalization table (§1D); the
researcher role and lenses (§9A); the discovery loop and reading scope (§9B); the research checklist as a cognitive
control, and whether the label is in the checklist population (§9C, §9C.1 — the population is given, never chosen by
the agent); the register kinds, the ontology-not-closed rule, seed topics, proposed-topic rule and record schema
(§13.1–13.9); disconfirmation (§13.10); the formal structure standard (§13.11); corpus vs external comparison
(§13.12); and the per-label procedure (§9.8). **The S5a/S5b passes get their own persisted contract**, `prompts/<timestamp>_p3b-pass-contract.md`, covering
§9D–9E (incl. 9E.1–9E.3), §13.9a–13.11, §14.4c and G-13. It states the blinding rule: the analyst never receives
`generator`, candidate/control labels or defining properties. A contract that omits the research layer is non-conformant (G-07).

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
| whole-file reads added by chronological timeline reading (§9.8 step 1), per label | S4 pilot |
| research records per label (by kind); share of HYPOTHESIS/STRUCTURE-CANDIDATE reaching TESTED; cost of testing | S4 pilot |
| register audit result: layer-separation and labelling correctness (G-12, §21 item 5) | S4 pilot |
| checklist population size per stratum; discoveries per stratum on the pilot (purposive vs random) | S3 plan / S4 pilot |
| comparison candidates per generator, incl. control sets; estimated cross-object analysis cost | S3: **only G-SHARED-GROUP and G-NOTATION** (from P2 artifacts) can be previewed; G-DEPENDENCY only from `P3B-R1` `dependency_edges`. `P3B-R1` has **no timelines**, so G-COCHANGE and G-TIMELINE-SIM cannot be previewed before S5 (v1.4 correction of a v1.3 error) |

The human (H-12) then authorizes S5 and chooses the batching, for example by tier, by weight, or by capping
stage-2 reads per batch. **If the measured stage-2 load is not feasible, the stop-the-line rule applies (§23) and
a revised annex is presented. The absence procedure is never quietly thinned to fit.**

---

## 19.5 Run persistence (RC-11, new in v1.4)

Every scripted step writes, into its output artifact's header record: `script_name`, `script_version` (git blob sha
of the script), `parameters`, `seed` (where randomness is used), `input_hashes` (sha256 of each input artifact) and
`output_sha256` = the sha256 of the artifact **body excluding the header record**, so the hash is not self-referential;
`python_version` and `platform` (Python's `random` sequences are stable only within a version). Before any S5a/S5b pass, `P3B-PASS-SNAPSHOT.json` records the sha256 of every object record,
timeline and register file the pass reads. Appendix A specifies each script. **A step whose header record is missing
or mismatched fails G-10.**

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
| G-09 Register non-interference and status integrity | no research record altered a status or verdict; no record set to a governance outcome; every HYPOTHESIS/STRUCTURE-CANDIDATE/THEORY-CANDIDATE has a falsification condition, a disconfirmation plan, and `temporal_scope`; every A–D operation records population, method, completeness, termination bound and result (§13.10); **every STATUS satisfies §13.9a** (no confirmation by absence; no REFUTED from not-found; no EXTERNAL-THEORY item in a STATUS basis; no SUPPORTED with `CONTROL-UNDIFFERENTIATED`); every STRUCTURE-CANDIDATE has `relation_source`, RC-7b property statuses, a weakest-structure name and `competing_structures[]` (§13.11); every GAP has a §13.4 status; every SUGGESTION/DEFICIENCY has `execution_impact` ; `test_plan_sha256` unchanged between TEST-DEFINED and TESTED (§13.10a); `temporal_scope` equals its recomputation (§14.4c) | **script** for presence, structure and recomputable fields; **audit** (§21 item 6) for the judgment conditions: §13.9a (3) "favours H", a "verified" counterexample, and fidelity to the DESCRIPTIVE/DISCOVERY mapping |
| G-12 Layer and time separation | no layer-B/C content (register ids, `research_time`, RESEARCH-OBSERVATION/SUGGESTION/HYPOTHESIS/THEORY-CANDIDATE text) in a layer-A field; no timeline point cites a register record as basis; every register record has both `historical_anchor` and `research_time`; `PROPOSED:*` topics each have a definition | script + audit (§21 item 5) |
| G-13 Cross-scale findings | every CROSS-OBJECT/CORPUS record carries the §9E mandatory content for its scale (participants with their own evidence, acceptance state and tier; `derived_from_records`; for CORPUS `participating_findings`, `underlying_objects`, `independent_occurrences` with exclusions, `exceptions`, `explanatory_content`); `generator_basis` DESCRIPTIVE/DISCOVERY; control comparison for DISCOVERY; recurrence counts use only §9E.3 independent occurrences (no SELF-DERIVED, no non-PRIMARY, no overlapping sets); `temporal_scope` present and historical-point wording flagged on ACROSS-TIME records; Tier-X participants → `exposure: TIER-X`; `P3B-CROSS-CANDIDATES.jsonl` and `P3B-PASS-SNAPSHOT.json` hashes match the pass record; every candidate in the approved plan dispositioned ; **no DESCRIPTIVE or CONTROL set counted as an occurrence** (Appendix A.8); control comparisons pooled per pre-registered generator × class, with the decision per §9E.2 item 6; `blinding_level` recorded | script |
| G-10 Reproducibility | re-running the scripts on the recorded input manifest reproduces the mechanical fields byte-for-byte | script |
| G-11 Acceptance integrity | every ledger record is `record_status: PROPOSED`; every record in `32-RECONCILIATION-OBJECTS.jsonl` has resolvable `acceptance_ref`, `verifier_ref`, `audit_ref`; no downstream artifact cites a P3b status lacking them; no `P3B-R1` record is cited as a P3b result | script |

**Batch fails** if any of G-01…G-05 or G-07…G-12 fails (G-13 applies to the S5a/S5b/S6a passes, which fail and re-run on the same terms), or if G-06 finds a disagreement dispositioned as a
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
5. **Research-register audit (v1.2):** per batch, the auditor reviews every HYPOTHESIS, STRUCTURE-CANDIDATE,
   SCHEMA-LIMITATION and METHODOLOGICAL-DEFICIENCY, plus a seeded sample of the other kinds, for **form, not
   truth**: correct output layer and epistemic class; evidence actually supports the stated observation; no
   hindsight backdating; falsification condition present and genuine (it could come out false). The auditor also
   reports, as its own research records, significant structures the batch agent visibly **missed** in the audited
   labels. That measures under-discovery, which the other gates cannot see. Under-discovery is **reported, not a
batch-failure condition**: no evidence exists yet for a threshold. The S4 pilot measures it first (§19.4).
   **Added in v1.3:** the auditor also checks (a) **manufactured hypotheses**: a HYPOTHESIS with no genuine
   observation behind it, or one that could not come out false; (b) **confirmation-only** records (§13.10);
   (c) STRUCTURE-CANDIDATE formal completeness (§13.11); (d) external comparison presented as corpus evidence.
6. **Cross-scale audit (v1.3, extended v1.4):** for S5a/S5b, an independent agent re-examines every CORPUS-scale
   THEORY-CANDIDATE and STRUCTURE-CANDIDATE, plus a seeded sample of CROSS-OBJECT findings. It re-derives the common
   structure from the participants' own evidence; checks differences, independence exclusions (§9E.3),
   DESCRIPTIVE/DISCOVERY classification, the control comparison, `relation_source`, the weakest-structure name and
   `temporal_scope`; and dispositions each as in item 2. **v1.5:** it also re-examines a seeded sample of OBJECT-scale
   SUPPORTED-IN-CORPUS and REFUTED-IN-CORPUS records for the judgment conditions of §13.9a, so that truth, not only form,
   is audited at every scale.
7. **Sampling audit:** the phase report states the pre-registered outcome per stratum (purposive vs weighted random,
   §9C.1), so the selection rule itself is audited.
8. **Audit independence disclosure (v1.4):** every audit record carries the auditor's `model_id`. When auditor and
   batch agent share a model family, their blind spots may correlate, so **under-discovery measured by item 5 is a
   lower bound** and is reported as such.

---

# 22. FAILURE / RECOVERY RULES

| Condition | Action | Owner |
|---|---|---|
| Ambiguous relationship | ONTOLOGY-GAP or AMBIGUITY signal; status on closed values only | AI |
| Contradictory evidence | research record with topic CONTRADICTION; CONTESTED only if D4 holds (§12.2.2; row 1 of §12.2.4) | AI |
| Missing file (an S-id whose path no longer resolves) | label `BLOCKED`; `P3B-ESC` with the S-id; resolve through `02-FILES.jsonl` commit + sha256 (`git show <commit>:<path>`) before escalating | script → human |
| Duplicate files | treat as one source (v3.5 R9); cite the first occurrence | script |
| Timestamp anomaly | birth → UNORDERED-BLOCK; TIMESTAMP-ANOMALY escalation if it changes a birth | AI → human |
| Cross-track conflict | CROSS-TRACK signal; §14.5 rule; OMQ-07 | AI → human |
| Possible hindsight contamination | HINDSIGHT-RISK signal; `hindsight_dependency` set | AI |
| Frozen-artifact hash mismatch at S0 | **stop all execution**; human escalation | script → human |
| Agent contract deviation | label ESCALATED; batch continues; ≥ 1 PROTOCOL-VIOLATION fails the batch | AI → audit |
| Methodological deficiency reported (§13.8) with impact ≠ NONE | escalation `P3B-ESC`; execution continues under the current contract unless the human stops it or a gate becomes unpassable (then §23 stop-the-line) | AI → human |
| Research record found in a layer-A field | G-12 fails the batch; the record is moved by a correction (§12.3), never deleted | script → re-run |
| Session interruption | resume from `P3B-STATE.json` and the manifest only, never from conversation memory (v3.5 A3) | orchestrator |

---

# 23. STOPPING RULES

**Terminal predicate for P3b** (extends v3.5 A11 TERMINAL). All must hold:
1. every Tier-U and Tier-Z label is `ACCEPTED` or `BLOCKED` with an open escalation;
1a. the S5a cross-object pass and the S5b corpus pass are complete per §9E "Completion" against the H-15 plan
   (and S6a if S6 ran);
2. every Tier-X label is `ACCEPTED`, or `PROVISIONAL-HELD` under a recorded H-02 decision to hold;
3. every absence dimension of every accepted label is FOUND, GENUINELY-UNDEFINED-AFTER-CENSUS,
   FIREWALL-BLOCKED, or ESCALATED;
3a. every Tier-Z label's semantic_status is either the H-11a value or `null` + NO-PAIR-EVIDENCE under a recorded
   H-11a decision to leave it out of the domain (option iii). A still-undecided H-11a does **not** block acceptance
   of the pair-independent fields, but it does block the terminal predicate;
4. `32-RECONCILIATION-OBJECTS.jsonl` and `30-RECONCILIATION.md` exist and pass every script gate (G-01…G-05,
   G-07…G-13) corpus-wide. *(v1.3 fix: v1.1/v1.2 still said "G-01…G-10" after G-11/G-12 were added.)*

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
| `P3B-RESEARCH-REGISTER.jsonl` | JSONL | append-only (a lifecycle advance is a new line referencing the `rs_id`); **a primary P3b output**, handed to P4–P7 as research input |
| `P3B-TOPIC-PROPOSALS.jsonl` | JSONL | append-only (proposed topics with definitions; promotion only by change control) |
| `P3B-SAMPLE-PLAN.jsonl` | JSONL | regenerable before dispatch (checklist population: purposive criteria met, stratum, seed); frozen once dispatched |
| `P3B-PASS-SNAPSHOT.json` | JSON | written once per pass before analysis (hashes of every input the pass reads, §19.5); never edited |
| `P3B-CROSS-BLIND.jsonl` / `P3B-CROSS-REVEAL.jsonl` | JSONL | blinded analysis input and sealed reveal key (Appendix A.7); the reveal hash is recorded before analysis |
| `P3B-CROSS-CANDIDATES.jsonl` | JSONL | regenerable before the pass; frozen once the pass starts; each candidate's disposition appended |
| `P3B-P1-GAP-CAPTURE.jsonl` | JSONL | append-only |
| `P3B-CORRECTIONS.jsonl` (`P3B-COR-####`) | JSONL | append-only |
| `P3B-ESCALATIONS.jsonl` (`P3B-ESC-####`) | JSONL | append-only |
| `P3B-GOVERNANCE-LOG.md` | Markdown | append-only, human entries |
| `P3B-STATE.json` | JSON | mutable execution position (holds no rules) |
| `audit-p3b/` | MD/JSON | append-only |
| `32-RECONCILIATION-OBJECTS.jsonl` | JSONL | **the only place a P3b record is ACCEPTED**; built from accepted batches (§16.5); written once at S7; a later version is a new file with a version suffix |
| `30-RECONCILIATION.md` | Markdown | v3.5 B1 P3 narrative; written at S7 |
| P4 hand-over package: per-object statuses + timelines + births + edges + the research register summary and carried-forward questions + escalations | inside `30-RECONCILIATION.md` §"Hand-over" | — |

## 24.2 Existing artifacts — classes

| Class | Artifacts |
|---|---|
| **Immutable (frozen)** | everything committed in `125cfe8371`/`c9e76918b` under `chronological-read/`, including `02-FILES.jsonl`, `03-CONTRIBUTIONS.jsonl`, `20-FAMILIES/**`, `31-RECONCILIATION-PAIRS.jsonl`, `ledger/`, `ledger-p3a/`, `ledger-p3b/OB0001–3`, `_batch_manifest_p3b.jsonl`, `audit-p3a/**`; Master Protocol v3.5; the agent-extraction contract |
| **Append-only** | `09-ORCHESTRATOR-FLAGS.md` (existing convention); all new append-only files above |
| **Regenerable** | only the new regenerable artifacts above |
| **New version required** | any change to this annex, the agent contract, the §12.2 rules or H-11 choices, the record kinds or seed topics (§13.2–13.3), or a merged P3b output |

---

# 25. ACCEPTANCE CRITERIA

**Batch acceptance** (H-06): all gates pass (§20); audit dispositions complete (§21); no open PROTOCOL-VIOLATION;
the human acceptance entry is recorded. Engineering supplies the evidence and a recommendation; the human decides.
Acceptance of a batch accepts its **object records** as reconciliation output. For its **research records**,
acceptance means only that they are correctly formed, labelled and evidenced (§21 item 5). It is **never**
acceptance of their truth. Their truth is a matter for v3.5 P5/P6.

**Phase acceptance:** the terminal predicate (§23) holds; a P3b completion report states per tier: labels accepted /
held / blocked, the status distributions, escalations open, audit agreement tables; a **research register
summary**: records by kind, lens and topic; hypotheses by lifecycle stage and status; gaps by negative status;
schema limitations and methodological deficiencies with their dispositions; proposed topics; **records by scale
(OBJECT / CROSS-OBJECT / CORPUS); THEORY-CANDIDATEs with their disconfirmation and control results; discoveries per
sampling stratum (purposive vs random)**; and the research questions carried forward to P4–P7;
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
5. **Research-driven change (v1.2).** SUGGESTION-METHOD and METHODOLOGICAL-DEFICIENCY records are the normal inputs
   to change control. They are collected in the phase report and, where `execution_impact = AFFECTS-METHOD`, raised
   at once through escalation. A research record never changes the method by itself. Promotion of a proposed topic
   into the seed, or of a new record kind, is a change under this section.
6. **Freeze integrity check (v1.5, V-C5).** No version of this annex may be approved or frozen until the freeze
   integrity check of Appendix A.9 passes on the exact file, and its output (including the file's sha256) is recorded in
   `P3B-GOVERNANCE-LOG.md` with the approval. The check exists because v1.4 shipped literal regex backreferences and a
   stale version string past a reference-only check.

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
| **OMQ-07** | Track directory mapping only (the rule itself is fixed by D-23) | mapping partly UNCONFIRMED (MD-043 subclusters, `KSME-20-P3A-TRACK-PROVENANCE-AUDIT.md`). **v1.4: now needed before S2/S3** only if track enters the sample plan; the v1.4 sample plan does **not** use track, so OMQ-07 is needed before S5 (G-08) as before (C5 resolved by removal) | confirm / refine the mapping | G-08; any later use of track in sampling |
| **OMQ-08** | Affected-set B | not reproducible exactly on 2026-09-24 (§5.3); approximate reconstruction forbidden (§19.1a) | B-REPRODUCED if a procedure is found / B-UNREPRODUCIBLE with (i) A∪C or (ii) A∪C∪B-documented (H-13) | S1 |
| **OMQ-09** | Numbers: batch size, audit sample size, "large label" threshold for human review | v3.5 BATCH_SIZE 40 (files, P1); P3b first run 30 batches of about 83 labels; no calibration evidence for audit sizes | set by the human; the pilot measures cost | S4 |
| **OMQ-10** | Do the derivation-discovery P4/P5 extension candidates (generality, parsimony, falsifiability …) enter the P4 hand-over? | marked EXPERIMENTAL, not adopted | defer to P4 commission / include as annotations | hand-over format |
| **OMQ-11** | Stage-2 reading load | **Converted into a gate** (§19.4, H-12): S3 measures and S4 calibrates before S5 | per the measurements | S5 |
| **OMQ-12** | Does the methodology freeze admit this annex (H-07)? | `.claude/CLAUDE.md` freeze clause; deficiency evidence in §6 | admit / reject | H-01 |
| **OMQ-13** | Is v3.5's "object" in A11 "PER OBJECT" the **label** (the first run's and this annex's unit) or the **candidate group**? | A11 loops "FOR each CANDIDATE GROUP … PER OBJECT (roll-up of its pairs)"; TERMINAL says "every group and every load-bearing object". The two readings differ mainly on HOMONYM-SPLIT (H-11b). The label unit is kept (architecture unchanged); this OMQ records the ambiguity so a later reviewer can revisit it | label (kept) / group | interpretation of H-11b |
| **OMQ-14** | Reading scope of "read chronologically" | v1.2 defines it per label: rows in historical order, and whole-file reading of the sources carrying a birth, a change of definition/type/meaning, or a contradiction. A corpus-wide sequential re-read would restart P1 (D-04; the human's "do not restart the corpus read") | per-label (recommended) / per-label plus whole-file reading of **every** source the label cites / corpus-wide sequential re-read | S4 cost; S5 sizing |
| **OMQ-15** | Checklist-population design (§9C.1): purposive criteria, strata cut-points, allocation floor f | principle fixed by D-36; **v1.4 recommendation measured**: narrow criteria = 589 labels (23.6%) vs v1.3's ≥ 56.7%; three-dimension strata; proportional allocation with a floor; formal row := non-empty `type_signature` | narrow (recommended) / minimal 501 (20.1%) / other | S3 plan, S4 contract |
| **OMQ-16** | Testing depth for **non-CORPUS** records (beyond the mandatory TEST-DEFINED). **CORPUS-scale records are always TESTED** (§13.9); v1.5 removes the v1.4 option "defer all TESTED", which contradicted §13.9 | testing improves the register but competes for budget; cost unknown until S4 | seeded share of CROSS-OBJECT and OBJECT records, sized after S4 (recommended) / within-label tests only / larger share | S5 budget (H-12) |
| **OMQ-17** | Do the cross-object and corpus passes run on PROPOSED object records, or only ACCEPTED ones? | ACCEPTED-only is safer but delays research until acceptance; PROPOSED allows earlier passes but findings may rest on records later corrected. Either way, acceptance state at research time is recorded (§9E) | ACCEPTED only (recommended for CORPUS) / PROPOSED allowed for CROSS-OBJECT with re-examination on correction / PROPOSED for both | S5a, S5b (H-15) |
| **OMQ-18** | Which generators run in the passes (§9E.1)? | the register lists each generator's circularity and corrections; SELF-DERIVED generators may test, never count | all with corrections (recommended) / exclude AI-input generators (G-DEPENDENCY, G-COCHANGE, G-TIMELINE-SIM) in the first pass / a subset | S5a (H-15) |
| **OMQ-19** | N_support for SUPPORTED-IN-CORPUS (H-16) | no calibration evidence; P3a showed single-pass judgment unreliable (20.9% exact agreement); independence rules (§9E.3) already exclude duplicates and synthesis | **2** (recommended: the smallest value that requires independent replication) / 1 for OBJECT-scale with 2 for CROSS/CORPUS / 3 | STATUS |
| **OMQ-20** | Control matching, ratio, arity **and decision rule** (H-17) | no calibration; the population per stratum is known only after S5; with k = 2 a raw "exceeds" rule is trivially met (v1.4 review V-C3) | match on arity + row_count band + pair_count band + provenance mix + historical-span band + max-file-degree band; **k = 2** where the pool allows; arities 2 and 3; decision rule **one-sided Fisher exact at α = 0.05 per generator × class, with at least m = 10 candidate sets**, else CONTROL-UNDIFFERENTIATED (recommended) / δ ≥ 0.2 with m ≥ 10 / other | S5a |
| **OMQ-21** | Co-change file-degree cap (H-18) | measured degree: p90 6, p95 8, p99 14, max 42. Excluded above cap: 5 → 275 files (10.3%), **10 → 50 (1.9%)**, 15 → 15 (0.6%), 20 → 7 (0.3%). The documented registry files are S0239 (42), S0237 (37), S0240 (19), S0241 (15), S2523/S2528 (11), S2524 (10) | **cap 10 and exclude non-PRIMARY sources** (recommended: removes the documented registries except S2524 at exactly 10, and keeps 98% of files) / cap 15 / cap 5 | S5a |

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

**D-10 (amended v1.2) Research records are separate, never change statuses, and are the primary discovery
channel.** Evidence: commission §7; v3.5 R5/R6; the v1.2 research-first correction. Alternatives: records as status
modifiers; records as exception reports only (the v1.1 framing). Reason: prevents premature theory without
suppressing discovery. Uncertainty: vocabulary completeness, now handled by the extensible topics (D-33)
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

**D-27 (new v1.2) Research-first principle: the roll-up is the floor, not the ceiling.** Evidence: the human's v1.2
commission; v3.5's purpose ("construct the KnowledgeOS theory from the complete historical corpus", v3.5 header);
the kernel-concept case showing labels can hide several formulations. Alternatives: the v1.1 framing (classification
plus exception signals); a separate research phase after P3b. Reason: P3b is the phase that actually reads each
object's evidence, so discovery that is not captured there is lost or has to be re-read later. Uncertainty: the
cost (OMQ-14–16).

**D-28 (new) Three output layers, with a per-label timeline in layer A.** Evidence: v3.5 R6 (source ≠ assessment ≠
observation); hindsight risks (§14). Alternatives: one mixed record with epistemic tags only. Reason: physical
separation (object record vs register) is checkable by G-12. Tags alone are not. Uncertainty: the boundary between
descriptive `change_vs_previous` (layer A) and meaning (layer B) takes judgment and is audited (§21 item 5).

**D-29 (new) Historical time and research time are separate fields.** Evidence: v3.5 R10; the anti-projection rule.
Alternatives: a narrative note. Reason: backdating becomes mechanically detectable. Uncertainty: none.

**D-30 (new) Discovery allowed, canonicalization not.** Evidence: v3.5 R20 and P5/P6 ownership of validation and
governance. Alternatives: forbid theory-level observations in P3b (v1.1 was close to this in tone). Reason: resolves
the apparent tension between research freedom and epistemic discipline. Uncertainty: none.

**D-31 (new) Openness: schema limitations and methodological deficiencies are first-class records.** Evidence: the
confirmed ontology gaps (KSME-20 §6) and the notes_for_p3b cases that fit no enum. Alternatives: force into the
nearest value (the documented failure mode, KSME-20 contract §2 item 10). Reason: the corpus may challenge the
method. The method changes only through change control. Uncertainty: none.

**D-32 (new) Senior multidisciplinary researcher role with explicit lenses and checklist.** Evidence: the human's
commission; P3a's measured weakness under unconstrained, under-informed judgment (KSME-20). Alternatives: an
extraction-only agent. Reason: the lenses make the analysis systematic, and G-12 plus the register audit keep it
honest. Uncertainty: under-discovery is measurable only through the audit (§21 item 5).

**D-33 (new) Record kinds closed; topics seeded and extensible (`PROPOSED:*`, usable immediately, promoted by
change control).** Evidence: the human's requirement for an evolving vocabulary; the need for gates and an audit
defined per kind. Alternatives: fully open tags (ungovernable); fully closed topics (v1.1; suppresses discovery).
Reason: discovery stays open while the vocabulary stays governed. Uncertainty: proposal volume (H-14 cadence).

**D-34 (new v1.3) Three research scales: OBJECT, CROSS-OBJECT, CORPUS.** Evidence: a theory may emerge only from
relationships between objects (partial orders, state machines, conservation laws, dependency algebras, invariants
across objects, bounded contexts); v1.2's procedure iterated `FOR label IN batch`. Alternatives: object-level only
(v1.2); leave cross-object work to P4–P7. Reason: without an explicit cross-object mechanism, P3b could produce a
rigorous object-level reconstruction and never take the inductive step to the general theory. Uncertainty: cost
(H-15). Naming: the reviewer's "R1/R2/R3" would collide with run ids `P3B-R1`/`-R2`.

**D-35 (new) Cross-object and corpus passes as explicit steps (S5a, S5b, S6a) with mechanical candidate generation,
random controls, mandatory finding content and a completion rule.** Evidence: v1.2 allowed records to span labels
but gave no procedure. Alternatives: ad-hoc cross-label noticing during per-label work. Reason: systematic,
auditable and checkable (G-13). Uncertainty: generators and control size (OMQ-18); inputs (OMQ-17).

**D-36 (new) Checklist population = purposive ∪ stratified random, computed mechanically with a recorded seed.**
Evidence: choosing "significant" objects is a sampling decision; importance-based selection biases against rare
structures and counterexamples. Alternatives: researcher judgment; a numeric row threshold only; all labels. Reason:
purposive discovery plus unbiased coverage, and the random strata measure what the purposive criteria miss.
Uncertainty: strata and sizes (OMQ-15, OMQ-09).

**D-37 (new) The research ontology is not closed because the storage schema is.** A finding that fits no kind is
recorded as SCHEMA-LIMITATION and proposed as a new kind (H-14). Evidence: the closed-kinds vs open-topics tension.
Alternatives: open kinds. Reason: closes the loophole without losing per-kind gates. Uncertainty: none.

**D-38 (new) Observations may stay observations; disconfirmation searches A–D are mandatory for serious hypotheses.**
Evidence: the risk of manufactured hypotheses; the risk of elegant patterns from selective reading. Alternatives:
require a hypothesis per observation; supporting evidence only. Reason: falsifiability without artificial output.
Uncertainty: none; the testing budget is OMQ-16.

**D-39 (new) Formal standard for structure candidates per lens, with NOT-DETERMINED for unknowns.** Evidence: "there
appears to be a lattice" cannot feed later mathematical work. Alternatives: free text. Reason: makes the register
directly usable in P4–P7 formalization, and exposes what is unknown. Uncertainty: none.

**D-40 (new) Corpus evidence and external theoretical comparison are separate classes.** Evidence: domain knowledge
was already allowed (DOMAIN-INTERPRETATION) but not distinguished as a different question. Alternatives: fold it into
DOMAIN-INTERPRETATION. Reason: "the corpus contains X" and "X resembles a known structure" must never be confused,
and correspondence is never evidence of intent. Uncertainty: none.

**D-41 (v1.4, RC-1) Controls are eligible-population, matched, blinded, with a pre-registered outcome, and apply only
to DISCOVERY claims.** Evidence: audit C1. Alternatives: v1.3's unlinked tuples. Reason: only matched, blind controls
with a measured outcome can detect "structure everywhere". Uncertainty: matching variables, k and arity (H-17).

**D-42 (RC-2) Generator register with defining properties; DESCRIPTIVE vs DISCOVERY; SELF-DERIVED generators may test,
never count; co-change restricted to PRIMARY sources under a degree cap.** Evidence: audit C2;
`09-ORCHESTRATOR-FLAGS.md` registry pattern; KSME-22D false cognates; measured degree distribution. Alternatives: drop
generators; keep them unrestricted. Reason: keeps candidate generation useful while blocking manufactured structure.
Uncertainty: cap (H-18), generator set (OMQ-18).

**D-43 (RC-2) Independent occurrence defined; v3.5 R9 applies at every scale.** Evidence: audit C2 and T-H3.
Alternatives: count records. Reason: recurrence must mean independent replication. Uncertainty: none.

**D-44 (RC-3) STATUS assignment rules; everything else UNDETERMINED.** Evidence: audit C3. Alternatives: agent
judgment. Reason: stops confirmation by absence and refutation by not-found. Uncertainty: N_support (H-16).

**D-45 (RC-4) Temporal scope of structures (WITHIN-SLICE / ACROSS-TIME / UNORDERED).** Evidence: audit C4 and T-H6.
Alternatives: rely on §1C. Reason: §1C protects statements, not structures. Uncertainty: none.

**D-46 (RC-5) Sample plan without track; formal row := non-empty type_signature; Tier X sampled in its own run;
narrower purposive criteria recommended.** Evidence: audit C5; measured 56.7% vs 23.6%. Alternatives: move OMQ-07
earlier. Reason: removes the ordering dependency and restores a high-signal minority. Uncertainty: OMQ-15.

**D-47 (RC-6) Traceability chain (`derived_from_records`, `participating_findings`) with transitive re-examination;
link, don't duplicate.** Evidence: audit I-1. Alternatives: direct participants only. Reason: every corpus finding must
resolve to source evidence, and corrections must propagate. Uncertainty: none.

**D-48 (RC-7, RC-8) Relation source, five-value property status, weakest-structure rule, competing structures;
executable disconfirmation operations.** Evidence: audit I-2, I-3, T-H1. Alternatives: v1.3 text. Reason: resemblance
must never read as a structure claim, and searches must be bounded and auditable. Uncertainty: none.

**D-49 (RC-9, RC-10) Statistical rules for sampling (pre-registered outcome, weighting, blinding, design frozen before
S4, rule-based TESTED selection); a light OBSERVATION profile.** Evidence: audit I-4, I-5. Alternatives: v1.3.
Reason: separates sample design from outcomes and removes bureaucracy from observations. Uncertainty: `n`, `f`
(OMQ-09/15).

**D-50 (RC-11, RC-12, RC-13) Run persistence and AI provenance (model_id); script specification appendix; §19.4
preview corrected; audit-independence disclosure; P3a-paired identity claims only as VERDICT-EVIDENCE-CONFLICT;
explanatory content for THEORY-CANDIDATE.** Evidence: audit I-6…I-11 and the clean-room list. Alternatives: leave to
implementation. Reason: a new researcher must be able to reproduce the procedure from the repository alone.
Uncertainty: script review before S0.

**D-51 (v1.5, V-C1) Pre-registration: the test plan (populations, scope, X, decision and stopping rules) is frozen and
hashed at TEST-DEFINED; B/D at census scope; narrowing is a new record citing what it excludes.** Evidence: the v1.4
review found that "EXHAUSTIVE over the stated population" and a movable scope let SUPPORTED be engineered and REFUTED
avoided. Alternatives: trust the auditor. Reason: removes researcher degrees of freedom after outcomes are seen.
Uncertainty: none.

**D-52 (V-C2) WITHIN-SLICE = computed coexistence at a point; UNORDERED dominates.** Evidence: v1.4 let any span count
as a slice. Alternatives: a researcher-chosen interval (v1.4). Reason: hindsight protection must not be satisfiable by
choice. Uncertainty: depends on timeline accuracy, which is audited.

**D-53 (V-C3) Pooled, pre-registered control decision (generator × class; δ/m or test α under H-17); absent or
unregistered = UNDIFFERENTIATED; CORPUS baseline derived from S5a; DESCRIPTIVE/DISCOVERY mapping pre-registered;
control-set findings recorded as CONTROL.** Evidence: v1.4 review V-C3, V-I5. Alternatives: per-candidate
comparison. Reason: a single candidate against two controls cannot discriminate anything. Uncertainty: rule parameters
(H-17).

**D-54 (V-C4) Independence counter drops DESCRIPTIVE and control sets; deterministic ordering by sorted member tuples.**
Evidence: A.8 omitted the §9E.3 rule. Alternatives: none. Reason: the specification must implement the rule.
Uncertainty: none.

**D-55 (V-C5) Freeze integrity check (A.9) required before any approval; D-40 and §31 restored; §28 corrected.**
Evidence: literal `\1` and `approve v1.2` passed v1.4's reference-only check. Alternatives: manual proofreading.
Reason: the protocol is a research instrument, so its integrity must be checked mechanically. Uncertainty: none.

**D-56 (V-I1) THEORY-CANDIDATE is a class carried by HYPOTHESIS/STRUCTURE-CANDIDATE at CORPUS scale, not a kind.**
Evidence: it had a profile and gates but was not a kind. Alternatives: add a ninth kind. Reason: avoids duplicating
HYPOTHESIS obligations. Uncertainty: none.

**D-57 (V-I2, V-I3) G-09 split into script-checkable and audit-judged conditions; OBJECT-scale STATUS audited for truth
by sample; CORPUS records always TESTED; the CROSS-OBJECT support condition defined per participant.** Evidence: the
v1.4 review. Alternatives: v1.4 text. Reason: gates must claim only what they can check. Uncertainty: sample sizes
(OMQ-09).

**D-58 (V-I4…V-I11) Partial-blinding declaration and field stripping; seeded budget sub-sampling; a uniform sampling
outcome; Appendix A ambiguities resolved; consistency fixes; G-SHARED-GROUP limited to non-identity classes for
P3a-judged pairs; 217 shown.** Evidence: the v1.4 review and the measured overlap (1,982 vs 1,793). Alternatives:
leave them to implementation. Reason: two implementers must produce the same outputs. Uncertainty: none.

---

# 28. EXECUTION READINESS

| Question | Status |
|---|---|
| Corpus boundary fixed? | **Yes** — §3; measured; unchanged in v1.2 |
| Identity model fixed? | **Yes** — §4 |
| Existing P1/P2 preserved? | **Yes** — §5.1, §24.2; chronological reading is per label, not a corpus re-read (§9B) |
| P3a treatment defined? | **Yes** — verdicts frozen and consumed; evidence presentation separable (§11.5); exposure tiering §9.3 |
| P3b operationally defined? | **Yes** — §9 (controlled core), §9A–9E (research layer at three scales). Pairless semantic_status withheld by derivation (D2); permanent value H-11a |
| Evidence model defined? | **Yes** — §11; three output layers §1B |
| Relationship model defined? | **Yes, with four named human choices** — §12.2.3 (H-11a–d); ontology gaps deferred (OMQ-03) and recordable as SCHEMA-LIMITATION |
| Research-signal model defined? | **Yes** — §13: kinds (ontology not closed, D-37), extensible topics, core + profiles (§13.6), STATUS rules (§13.9a), executable disconfirmation (§13.10), formal structure standard with relation source and weakest-structure rule (§13.11), external comparison (§13.12); scales with generator register, controls and independence (§9E) |
| Hindsight control defined? | **Yes** — §14; historical vs research time (§1C, §14.4b); later recognition (§14.4a); **temporal scope computed as coexistence (§14.4c, A.10)**; relation source (§13.11); Track-B rule (D-23); directory mapping open (OMQ-07) |
| Automation boundary defined? | **Yes** — §15 (research operations added) |
| AI/human boundary defined? | **Yes** — §16 (incl. 1a research expectation; §16.5 proposal vs acceptance), §17 |
| Provenance defined? | **Yes** — §18, §13.6 |
| Quality gates defined? | **Yes** — §20 (G-01…G-13; G-09 split into script and audit conditions; G-13 extended); audit items 1–8 (§21); run persistence §19.5; **freeze integrity check** (§26 item 6, A.9) |
| Stopping rules defined? | **Yes** — §23; S5 behind a measured-workload gate (§19.4); deficiency routing §13.8 |
| Failure/recovery defined? | **Yes** — §22 |
| Outputs defined? | **Yes** — §24: reconciliation output **and** research register |
| Acceptance criteria defined? | **Yes** — §25 (research records accepted for form, never for truth), §16.5 |
| Open methodological questions identified? | **Yes** — 21, §27 (v1.4: OMQ-19 N_support, OMQ-20 control design, OMQ-21 co-change cap; OMQ-07/15/18 updated) |

### PROTOCOL STATUS

**REQUIRES METHODOLOGICAL DECISION. Not frozen.**

**To freeze the protocol (no execution):** H-07 (freeze admissibility) → H-01 (approve v1.5), after the §26 freeze integrity check passes.

**Before S0–S3 (scripted steps):** the scripts must be written to **Appendix A** and reviewed (a script review, not
a protocol change).

**Before S1–S4 (mechanical prep and pilot):** H-13 (definition of B) · H-04 (`row_bundle_v2` or V1-plus-rows) ·
H-03 (confirm `P3B-R1` as baseline) · OMQ-09 (pilot and audit sizes, `n`, `f`) · **OMQ-14** (reading scope) ·
**OMQ-15** (sampling design). The pilot excludes TESTED/STATUS until H-16 is decided.

**Before S5 (production batches):** H-12 (on measured workload, including testing budget **OMQ-16**) · H-11a–d
(semantic_status choices; pair-independent fields may proceed without them) · OMQ-07 (track mapping, for G-08) ·
**H-16 / OMQ-19** (N_support; needed before any STATUS is recorded).

**Before S5a/S5b (cross-object and corpus passes):** H-15, including **OMQ-17** (inputs) and **OMQ-18** (generators);
**H-17 / OMQ-20** (control design); **H-18 / OMQ-21** (co-change cap).

**Before S6 (Tier X):** H-02. **Anytime, non-blocking:** H-14 (topic promotion).

No execution has been performed under v1.0, v1.1, v1.2, v1.3, v1.4 or v1.5.

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

# 30. CHANGE LOG v1.1 → v1.2

v1.1 (`20260924_1056_p3b-phase1-continuation-protocol-v1.1.md`) is kept unchanged. **Architecture and controls are
unchanged:** corpus boundary, identity model, frozen P3a verdicts, F-lane isolation, hindsight rules, AI/human
separation, gates G-01…G-11, acceptance and change control are all retained. Every change **adds** research
capability or clarifies wording. None relaxes a control.

## 30.1 What changed

Class: **E** = epistemic (what counts as what) · **M** = methodological (how the work is done) · **O** = operational
(artifacts, gates, steps).

| # | Change | Why | Evidence | Class | Undecided |
|---|---|---|---|---|---|
| 1 | Research-first principle at the head; Purpose rewritten; "roll-up = floor, not ceiling" | the human's correction: P3b is theory research, not documentation | commission; v3.5 header purpose | E/M | — |
| 2 | Three output layers A/B/C, physically separated (object record vs register) | "never silently collapse" | v3.5 R6 | E | — |
| 3 | Historical time vs research time as separate fields | avoid backdating discoveries | v3.5 R10 | E | — |
| 4 | "Discovery allowed; canonicalization not" table | resolves research freedom vs discipline | v3.5 R20, P5/P6 | E | — |
| 5 | Openness clause: enums are operational; models are hypotheses; the corpus may challenge the method | theory not known in advance | KSME-20 ontology gaps | E/M | — |
| 6 | Researcher role with four lenses (§9A) | active multidisciplinary analysis | commission | M | — |
| 7 | Research discovery loop around the per-label procedure (§9B); chronology as research instrument | chronological reading as first-class research | v3.5 R1/R10 | M | reading scope **OMQ-14** |
| 8 | Research checklist (23 questions) (§9C) | systematic thinking | commission | M | significance criteria **OMQ-15** |
| 9 | Per-label **timeline** in layer A, with descriptive `change_vs_previous` | reconstruct each stage and compare | commission item 1 | O/E | — |
| 10 | Research register replaces the signal file: 8 closed kinds, seeded topics, `PROPOSED:*` extensibility, full record schema | primary discovery channel; evolving vocabulary | commission items 4–7, 14 | O/M | topic promotion **H-14** |
| 11 | GAP rules with negative statuses, never "does not exist" | "not found ≠ does not exist" | v3.5 R17 | E | — |
| 12 | SUGGESTION-RESEARCH / SUGGESTION-METHOD with the nine fields and `execution_impact` | suggestions first-class, never silent | commission item 5 | O/M | — |
| 13 | SCHEMA-LIMITATION and METHODOLOGICAL-DEFICIENCY with routing to change control | the corpus may challenge the method | commission items 4, 9 | M | — |
| 14 | Lifecycle: TEST-DEFINED mandatory for hypotheses and structures; TESTED optional within budget; STATUS values say "IN-CORPUS" | falsifiability without overloading P3b | commission item 15 | M | testing depth **OMQ-16** |
| 15 | Epistemic classes RESEARCH-OBSERVATION and RESEARCH-SUGGESTION added | label layer B/C | commission item 15 | E | — |
| 16 | §16 1a: AI is expected to research; §16 item 2 extended | remove the "avoid substantive observation" implication | commission item 16 | E | — |
| 17 | Gates: G-09 rewritten (register non-interference); **G-12 new** (layer and time separation) | enforce 2, 3, 11, 14 mechanically | — | O | — |
| 18 | Audit item 5: register audited for form, not truth; auditor reports missed structures (not a failure condition) | measure mislabelling and under-discovery | — | O | threshold later |
| 19 | Contract must include the research layer (§19.2); workload list extended (§19.4) | reproducibility and cost | — | O | — |
| 20 | Acceptance: research records accepted for form only; phase report includes a register summary; outputs add the register and topic proposals | output model | commission item 14 | O/E | — |
| 21 | New decisions D-27…D-33; D-10 amended; new OMQ-14…16; new H-14; H-12 extended | Decision · Evidence · Alternatives · Reason · Uncertainty rule | — | — | as listed |

## 30.2 Consistency review — contradictions created by v1.2, and their resolution

| # | Tension | Resolution in v1.2 | Residual |
|---|---|---|---|
| C1 | "Read the corpus chronologically" vs D-04 / the human's "do not restart the corpus read" | chronological reading is per label (§9B); a wider scope is OMQ-14 | cost to be measured (S4) |
| C2 | Extensible topics vs closed-list validation (G-05) | topics live only in the register; object-record enums untouched; G-12 checks proposed-topic definitions | none |
| C3 | "Test where appropriate" vs the measured-workload gate | TESTED is optional and budgeted under H-12 / OMQ-16; TEST-DEFINED is mandatory and cheap | budget unknown until S4 |
| C4 | Batch "acceptance" vs research truth | research records accepted for form only (§25) | none |
| C5 | METHODOLOGICAL-DEFICIENCY vs "execution continues" | §13.8 routing; stop-the-line only if a gate becomes unpassable or the human stops | none |
| C6 | "Domain analysis encouraged" vs "DOMAIN-INTERPRETATION never the basis of a status" | domain analysis feeds layer B/C; layer-A statuses stay source-grounded (§9A, §16 item 3) | none |
| C7 | Descriptive timeline change classes (layer A) vs meaning (layer B) | boundary stated (§14.3); audited (§21 item 5) | **genuine judgment boundary; the audit measures it, the text cannot remove it** |
| C8 | Auditor "missed structures" vs pass/fail gates | reported, not a failure condition, until the pilot gives evidence for a threshold | threshold later |
| C9 (pre-existing, found in this review) | §22 said "CONTESTED only if §12.2 row 2 applies", stale since v1.1 rewrote §12.2 | now "only if D4 holds (row 1 of §12.2.4)" | none |

Wording corrected where v1.1 implied that P3b is only classification, that research signals are exceptions, that the
schema is sufficient, or that discovery belongs only to P4–P7: §1, §2, §9.1, §9.8 steps 1/10, §10, §11.1, §12.4,
§13 (all), §16, §24, §25, §26, D-10.

## 30.3 Execution impact

| Step | Impact of v1.2 |
|---|---|
| **S0** Preflight | **none** |
| **S1** Input freeze | **none** |
| **S2** Tiering | **none** |
| **S3** Mechanical prep | **small**: bundles must carry each row's historical position and date basis so agents can build timelines; the workload report gains the timeline-reading estimate |
| **S4** Pilot | **material**: the contract must include the research layer (§19.2); OMQ-14 and OMQ-15 must be decided first; the pilot also measures the timeline-reading load, register volume, testing cost, G-12 and register-audit results |
| **S5** Batches | **material**: more reading and output per label, and one new gate (G-12); authorization H-12 now includes the research-testing budget (OMQ-16) |
| **S6** Tier X | same as S5, once H-02 is decided |
| **S7** Close | **small**: the phase report adds the register summary; the register is handed to P4 |
| **Existing human decisions** | H-01…H-13 unchanged in substance; H-12 extended; **H-14 new**; OMQ-14/15 join the pre-S4 list and OMQ-16 the pre-S5 list |

Adding research capability does **not** change the corpus, the inputs, the tiering, the frozen verdicts, or any
acceptance rule for object records.

---

# 31. HOW THE PROTOCOL SUPPORTS ACTUAL THEORY RESEARCH RATHER THAN DOCUMENTATION CONSTRUCTION

In v1.1, P3b's job was to fill 2,497 object records correctly, and anything unexpected was an exception signal. In
v1.2, filling the records correctly is the **floor**. The job is to read each object's evidence in historical order
and reconstruct how it actually developed. The researcher analyses it as a mathematician, statistician, logician and
domain architect, and records what it finds: structures, contradictions, hidden distinctions, gaps, better
interpretations, and deficiencies in our own method. It records each as an observation, suggestion, gap, hypothesis,
structure candidate, schema limitation or methodological deficiency, with a falsification condition wherever it
proposes something.

What keeps this research and not speculation:
- the three layers are physically separate, and G-12 checks it;
- a discovery carries its research time and is never backdated;
- "not found" is never "does not exist";
- nothing is canonicalized in P3b;
- the method can be challenged by the evidence, but it changes only through change control.

The register is a primary output. It goes to P4–P7 as the research agenda for reconstructing the theory, alongside
the reconciled object records that supply its evidence base.

**v1.3 adds the inductive step.** v1.2 researched each object well but one at a time. v1.3 adds explicit
cross-object and corpus passes, where structures spanning many objects can appear: shared transitions, orders,
invariants across objects, bounded contexts, recurring algebras. Mechanical candidate generation and **random
control sets** keep those passes honest. Every serious hypothesis must survive a search for contradiction, a
competing explanation and a counterexample. Every structure candidate states its set, relation, claimed properties
and unknowns formally. Correspondence to known mathematics is recorded, but never as corpus evidence. Which objects
get the deepest analysis is decided by a stratified sample, not by what we already think matters, so the method can
still find what we did not expect.

**v1.4 makes the inductive step trustworthy.** Candidates are generated by registered generators whose defining
property is known, so restating the selection cannot pass as discovery. Controls are matched and blind. Recurrence
counts only independent PRIMARY occurrences. A status is earned by explicit rules, never by the absence of
contradiction. Every structure says whether the corpus stated it or the researcher constructed it, names itself by the
weakest structure the evidence supports, and says whether it lived within one historical slice or only across time.

---

# 32. CHANGE LOG v1.2 → v1.3

v1.2 (`20260924_1109_p3b-phase1-continuation-protocol-v1.2.md`) is kept unchanged. **All v1.2 controls are
retained.** v1.3 is a targeted methodological correction from the human-relayed review of v1.2.

## 32.1 Changes

| # | Change | Why | Class | Undecided |
|---|---|---|---|---|
| 1 | Research scales OBJECT / CROSS-OBJECT / CORPUS (§9D); objective 3 (§9.1); principle box and §1A updated | theory may emerge only across objects | M | — |
| 2 | Cross-object and corpus passes S5a/S5b/S6a with candidate generators, random control sets, mandatory finding content and a completion rule (§9E, §19.1) | an explicit inductive step | M/O | H-15, OMQ-17, OMQ-18 |
| 3 | G-13 and audit items 6 (cross-scale) and 7 (sampling) | enforce 2 and 5 | O | — |
| 4 | "The research ontology is not closed because the storage schema is" + new-kind route via SCHEMA-LIMITATION (§13.2) | closes the kinds loophole | E | H-14 |
| 5 | "Observation may remain observation"; manufactured-hypothesis check in the audit (§13.9, §21) | avoid artificial hypotheses | E/M | — |
| 6 | Disconfirmation searches A–D (§13.10); G-09 extended; confirmation-only records fail | guard against selective reading | M | testing depth OMQ-16 |
| 7 | Formal standard for structure candidates per lens (§13.11) | usable for later formalization | M | — |
| 8 | Corpus evidence vs external theoretical comparison; new class `EXTERNAL-THEORY-COMPARISON` (§13.12, §11.1) | external theory must never become history | E | — |
| 9 | Checklist restated as a cognitive control, not a documentation obligation (§9C) | avoid bureaucracy | M | — |
| 10 | Checklist population = purposive ∪ stratified random, seed recorded (§9C.1); OMQ-15 reframed | selection is a sampling decision | M | OMQ-15 sizes |
| 11 | Schema: `scale`, `evidence_kind`, `competing_hypotheses`, disconfirmation fields (§13.6) | support 1, 6, 8 | O | — |
| 12 | Outputs `P3B-SAMPLE-PLAN.jsonl`, `P3B-CROSS-CANDIDATES.jsonl`; phase report by scale and stratum (§24, §25) | output model | O | — |
| 13 | §1: "synthesizes no theory" → "does not write the theory (P7); may propose THEORY-CANDIDATEs" | consistency with the corpus scale | E | — |
| 14 | D-34…D-40; OMQ-17, OMQ-18; H-15 | decision discipline | — | as listed |

## 32.2 Consistency review — tensions created or found in v1.3

| # | Tension | Resolution | Residual |
|---|---|---|---|
| T1 | "R1/R2/R3" research scales vs run ids `P3B-R1`/`-R2` | scales named OBJECT / CROSS-OBJECT / CORPUS; field `scale` | none |
| T2 | Corpus-level THEORY-CANDIDATEs vs v1.2 §1 "synthesizes no theory" | reworded: P3b does not *write* the theory (P7) but may *propose* candidates | none |
| T3 | Cross-object passes reading files vs D-04 no corpus re-read | whole files read only to check a specific candidate (§9E) | cost (H-15) |
| T4 | Findings on PROPOSED records vs acceptance rule §16.5 | acceptance state recorded per participant; re-examination on correction; input policy OMQ-17 | depends on OMQ-17 |
| T5 | Tier-X labels held vs cross-object participation | participants flagged `exposure: TIER-X`, capped below SUPPORTED-IN-CORPUS; S6a re-pass | none |
| T6 | Stratified sampling needs "chronological changes", which are known only after reading | purposive/strata use mechanical proxies at S2/S3; post-hoc strata by timeline reported in analysis | proxies imperfect; the random strata measure the miss rate |
| T7 | Mandatory disconfirmation vs optional TESTED | B–D *planned* at TEST-DEFINED (mandatory, cheap); *performed* at TESTED (budgeted) | budget OMQ-16 |
| T8 (pre-existing, found now) | §23 terminal predicate said "G-01…G-10", stale since v1.1/v1.2 added G-11/G-12 | now "every script gate G-01…G-05, G-07…G-13" | none |
| T9 | External comparison vs "DOMAIN-INTERPRETATION feeds the register" | both register-only; external comparison is its own class, with `external_correspondence` kept apart from corpus status | none |

## 32.3 Execution impact

| Step | Impact of v1.3 |
|---|---|
| S0, S1, S2 | **none**, except S2/S3 also compute the checklist population (`P3B-SAMPLE-PLAN.jsonl`) |
| S3 | **small**: sample plan; a rough preview of comparison candidates from the R1-baseline timelines only |
| S4 | **moderate**: contract adds scales, disconfirmation, formal standard, external-comparison rule; pilot measures discoveries per stratum |
| S5 | **small**: disconfirmation fields and structure standard; no new per-label steps |
| **S5a, S5b, S6a** | **new steps**, behind H-15 |
| S6 | unchanged |
| S7 | **small**: terminal predicate includes pass completion; report adds scales and strata |
| Human decisions | new **H-15**; OMQ-15 reframed; new **OMQ-17**, **OMQ-18** |

The corpus, inputs, tiering, frozen P3a verdicts, F-lane isolation and object-record acceptance are unchanged.

---

# 33. APPENDIX A — SCRIPT AND GENERATOR SPECIFICATIONS (RC-11, new in v1.4)

Each script is written to this specification, versioned by git blob sha, and writes the §19.5 header record.
Deterministic ordering everywhere: labels by `working_label`, sources by `source_id`, pairs by `pair_id`. Randomness
only through `random.Random(seed)` with the seed recorded. **These specifications must be reviewed before S0.**

**A.1 S0 PREFLIGHT.** Reference list = every path returned by `git ls-tree -r --name-only c9e76918b --
docs/knowledgeos/chronological-read`, excluding `prompts/` and every `P3B-*`, `AFFECTED.jsonl`, `B-REPRODUCED.jsonl`,
`_batch_manifest_p3b_r2.jsonl`, `_batch_input_r2/`, `ledger-p3b-r2/`, `audit-p3b/`, `30-*`, `32-*` path. For each:
sha256 of `git show c9e76918b:<path>` must equal the sha256 of the working-tree file. Counts: 2,779 records in
`02-FILES.jsonl`; 27,906 in `03-CONTRIBUTIONS.jsonl`; 2,497 labels in `_derived.json["reconciliation_objects"]`; 1,793
pairs; OB0001/2/3 with 80/80/81 records. Output `P3B-PREFLIGHT.json`. Any mismatch → stop (§22).

**A.2 S1 INPUT FREEZE.**
- **A** = pairs in `31-RECONCILIATION-PAIRS.jsonl` whose unordered `{a, b}` equals the unordered
  `{label_a, label_b}` of any row of `audit-p3a/P3A-UNDEFINED-RELATIONSHIP-PROVENANCE.jsonl` (exact string match). The
  audit reproduced 257.
- **C** = pairs with `relationship ∈ {SAME, REPLACEMENT, DERIVED-FROM}` (158).
- **B** = per §19.1a / H-13.
- Each pair is tagged with its criteria. Output `AFFECTED.jsonl` + `P3B-INPUT-MANIFEST.json`.

**A.3 S2 TIERING.** Tier X = labels appearing as `a` or `b` in any AFFECTED pair; Tier Z = labels with
`pair_count = 0`; Tier U = the rest. Output `P3B-TIERS.jsonl` with the causing pair ids.

**A.4 S3 STAGE-1 SEARCH.**
- **Terms** per label: the `working_label` with hyphens kept and with hyphens → spaces; every notation and alias.
- **Normalization** of terms and text: Unicode NFKC, casefold. For LaTeX, strip `\` commands to their argument
  (`\mathcal{K}` → `K`) and map a fixed table of commands to Unicode (`\le` → `≤`, `\in` → `∈`, `\Delta` → `Δ`, …).
  The table is versioned inside the script and its hash recorded. For ASCII, a fixed transliteration table (Greek →
  Latin names, `≤` → `<=`).
- **Engine and boundaries (v1.5):** Python `re` (the interpreter version is recorded). For terms that start and end
  with a word character, match `\b<term>\b`. For terms containing non-word symbols (for example `≤`, `K*`), match with
  the lookarounds `(?<![\w])<escaped term>(?![\w])`. Single-character notations are matched case-sensitively after NFKC
  only (no casefold), to avoid "K" matching "k".
- **Matching:** a word-boundary regular expression over (a) `statement` and `type_signature` fields of
  `03-CONTRIBUTIONS.jsonl`, and (b) the raw text of every `CONTENT` file in `02-FILES.jsonl`, read at its recorded
  `commit`. FIREWALL-LIMITED files are skipped and listed.
- **Output:** per dimension, the §11.4 search record with hit offsets. `negative_label = NEGATIVE-CENSUS` only if (a)
  and (b) both ran over the full lists.

**A.5 SAMPLE PLAN.**
- **Purposive:** by the OMQ-15 criteria, computed from `03-CONTRIBUTIONS.jsonl` rows whose `labels` contain the
  label. "Formal row" := non-empty `type_signature` (a dict with ≥ 1 non-empty value, or a non-empty string).
  "Strong lineage" := a `lineage_claims[].kind` in {SOURCE-CLAIMED-REPLACEMENT, -REDEFINITION, -RETRACTION,
  -CONTRADICTION, -SEPARATION}.
- **Strata and draw:** strata per §9C.1 bands. For each stratum, the non-purposive labels sorted by `working_label`,
  then `rng.sample(list, size)` with sizes from proportional allocation with floor `f`, total `n`. Allocation
  remainders are resolved by largest fractional part, then by stratum key. **v1.5:** one `random.Random(seed)`
  instance is consumed by strata in ascending stratum-key order; a stratum smaller than `f` is taken whole; if the
  floors alone exceed `n`, `n` is raised to the sum of floors and the change is recorded.
- **Recorded per label:** purposive criteria met, stratum, inclusion probability, seed. Pre-registered outcome
  (§9C.1 rule 1). Output `P3B-SAMPLE-PLAN.jsonl`.

**A.6 GENERATORS** (run at S5a on `P3B-PASS-SNAPSHOT.json` inputs; each outputs candidate sets of the H-17 arities).

| Generator | Algorithm | Parameters |
|---|---|---|
| G-SHARED-GROUP | all k-subsets of members of each P2a group, filtered per §9E.1 | k; group kinds admitted |
| G-DEPENDENCY | connected k-subsets of the dependency-edge graph built from the object records admitted by OMQ-17 (ACCEPTED only, or PROPOSED as decided), undirected for selection | k; OMQ-17 input policy |
| G-NOTATION | k-subsets of labels sharing a normalized notation or alias (A.4 normalization) | k |
| G-COCHANGE | for each PRIMARY source with degree ≤ cap: the labels whose timeline has a point at that source with `change_vs_previous ≠ RESTATES`; k-subsets | k; cap (H-18) |
| G-TIMELINE-SIM | Jaccard similarity of the sets of consecutive `change_vs_previous` bigrams; pairs with J ≥ θ, extended to k-sets by clique; **θ is fixed in the H-15 pass plan** | θ; k |
| G-TYPE-SIM | equality of the normalized signature shape: arity, plus each domain/codomain token mapped to a class ∈ {SET, FUNCTION, RELATION, SCALAR, STATE, OTHER} by a fixed, versioned keyword table in the script (for example `→`/`↦` → FUNCTION, `×`/`⊆`/`2^` → RELATION or SET, `ℝ`/`[0,1]` → SCALAR) | normalization and table version; k |
| G-TOPIC / G-HYP | k-subsets of labels sharing a topic, or co-listed in `related_labels` of one HYPOTHESIS/STRUCTURE record; tagged SELF-DERIVED | k |

Each generator writes a per-candidate record: generator, version, parameters, defining property, AI-input flag,
members, and source exclusions (G-COCHANGE).

**Budget sub-sampling (v1.5, V-I6).** If a generator's full candidate list exceeds its budget in the H-15 plan, the full
list is written first (sorted by sorted member tuple, lexicographically). The analysed subset is drawn with
`random.Random(seed).sample` of the budgeted size, and both lists are persisted. No other selection is permitted.

**A.7 CONTROLS, BLINDING, REVEAL.**
- **Draw:** for each candidate, draw k control sets from the generator's eligible population in which **no pair of
  members** has the defining link (for k-ary sets, k ≥ 3), matched on the H-17 variables (exact band match). If the
  pool is empty, relax matching variables in the fixed reverse order of the H-17 list, one at a time, recording each
  relaxation. If still empty after all relaxations, record `NO-CONTROL-AVAILABLE` for that candidate, which counts
  toward CONTROL-UNDIFFERENTIATED (§9E.2 item 6). Seeded.
- **Blind file** (`P3B-CROSS-BLIND.jsonl`): the union of candidates and controls, shuffled with a seed, with ids re-keyed. The analyst input
  contains only members and their evidence pointers, with the defining-link fields stripped (§9E.2 item 4).
- **Reveal file** (`P3B-CROSS-REVEAL.jsonl`): the key → {candidate/control, generator, defining property}, sealed (hash recorded) until every
  set is dispositioned; then joined by the reveal script.
- **Outcome table** per structure class: candidate vs control proportions at ≥ ANALYSED; the optional pre-registered
  test.

**A.8 INDEPENDENCE COUNTER.** Given a structure class (from the pre-registered vocabulary) and its participant sets:
1. drop sets proposed by SELF-DERIVED generators;
2. **drop sets whose finding is DESCRIPTIVE for this class, and sets that are controls** (v1.5, V-C4);
3. drop sets in which any participant lacks a PRIMARY source for its role (§9E.3);
4. order the remaining sets by their **sorted member tuples, lexicographically**; greedily keep sets in that order,
   skipping any that shares a label or a source (or a duplicate source per `08-OVERLAP-REGISTER.jsonl`) with a kept
   set.

Output the kept list and every exclusion with its rule. The greedy order is fixed so the count is reproducible. It is
a lower bound on the maximum independent family, and is reported as such.

**A.9 FREEZE INTEGRITY CHECK (v1.5, V-C5).** Run on the exact protocol file before any approval or freeze. It fails if
any of the following hold:
1. any literal regex backreference or placeholder remains: `\1`–`\9` outside code spans, `__SEED__`-style
   tokens, `TODO`, `TBD`, `XXX`;
2. outside code spans, any "approve vX.Y" or status line names a version other than the file's own;
3. any `§` reference does not resolve to a heading in the file (references to named external documents excepted);
4. any H-, OMQ-, D- or G- identifier is referenced but not defined, or any id is defined twice;
5. any S-step is referenced but not defined in §19.1;
6. any artifact named in backticks with a `P3B-` prefix is absent from §24;
7. the headings sequence has a gap or duplicate in its top-level numbering.
Output: pass/fail per rule, counts of headings, H-, OMQ-, D-, G- ids, and the sha256 of the file. The output is
recorded with the approval (§26 item 6).

**A.10 TEMPORAL SCOPE (v1.5, V-C2).** Inputs: participants' timelines from the pass snapshot, the relation-evidence
S-ids, and `02-FILES.jsonl` date bases. Compute `birth`, `end` and `evidence(R)` per §14.4c. If any is in an unordered
block → UNORDERED. Otherwise let t* = max(max birth, max evidence position). If every `end(p) > t*` → WITHIN-SLICE
[t*]; else ACROSS-TIME. (t* is the earliest point satisfying the conditions, because any valid t must be ≥ every birth
and every evidence date.) Output per record, with the inputs used.

---

# 34. CHANGE LOG v1.3 → v1.4

v1.3 (`20260924_1118_p3b-phase1-continuation-protocol-v1.3.md`) is kept unchanged. Source of the changes: the v1.3
adversarial audit (`20260924_1135_p3b-protocol-v1.3-adversarial-audit.md`). **Architecture unchanged.**

## 34.1 Corrections

| RC | Audit defect | Change | Sections |
|---|---|---|---|
| RC-1 | C1 controls | eligible-population, matched, blinded controls with a pre-registered outcome; DISCOVERY-only; `CONTROL-UNDIFFERENTIATED` | §9E.2, A.7, G-13, H-17/OMQ-20, D-41 |
| RC-2 | C2 circularity | generator register with defining properties; DESCRIPTIVE vs DISCOVERY; SELF-DERIVED; co-change PRIMARY + degree cap; independent occurrence; R9 at all scales | §9E.1, §9E.3, A.6, A.8, H-18/OMQ-21, D-42, D-43 |
| RC-3 | C3 status | STATUS assignment table and prohibitions | §13.9a, G-09, H-16/OMQ-19, D-44 |
| RC-4 | C4 temporal | `temporal_scope` with description rules | §14.4c, §13.6, §9E, G-12/G-13, D-45 |
| RC-5 | C5 ordering | sample plan without track; formal row defined; Tier X in its own run; narrower purposive criteria recommended (measured) | §9C.1, OMQ-07/15, D-46 |
| RC-6 | I-1 | `derived_from_records`, `participating_findings`, transitive re-examination, link-not-duplicate | §9E, §13.6, D-47 |
| RC-7 | I-2 | relation source; five-value property status; weakest-structure rule; competing structures | §13.11, D-48 |
| RC-8 | I-3 | population/method/completeness/termination per operation | §13.10, D-48 |
| RC-9 | I-4 | allocation, weighting, pre-registered outcome, blinding, freeze before S4, rule-based TESTED selection | §9C.1, §13.9, D-49 |
| RC-10 | I-5 | core + profiles; OBSERVATION light | §13.6, D-49 |
| RC-11 | I-6 | model_id; pass contract filename; pass snapshot; run persistence; Appendix A | §18, §19.2, §19.5, §33, D-50 |
| RC-12 | I-7 | §19.4 preview row corrected | §19.4 |
| RC-13 | I-8, I-9, I-10, I-11 | audit-independence disclosure; P3a-paired identity claims only as VERDICT-EVIDENCE-CONFLICT; external correspondence never "is"; explanatory content for CORPUS | §21 item 8, §9E step 2, §13.12 rule 6, §9E step 3 |

New facts recorded (§5.6): the source-file degree distribution; lineage-claim kinds outside v3.5's closed list
(CORRECTION on 166 labels, and others). The latter is flagged for a SCHEMA-LIMITATION record, not decided.

## 34.2 Consistency review of v1.4

| # | Check | Result |
|---|---|---|
| K1 | Tier X as a purposive criterion (v1.3) vs Tier X held until H-02 | **contradiction found and fixed**: Tier X sampled in its own S6 run by the same rule |
| K2 | OMQ-07 before S2/S3 (C5) | resolved by removing track from the sample plan; OMQ-07 stays before S5 |
| K3 | Controls for DESCRIPTIVE findings would be meaningless | controls apply to DISCOVERY only (§9E.2 item 1) |
| K4 | STATUS in the S4 pilot before H-16 | the pilot excludes TESTED/STATUS until H-16 (§28) |
| K5 | "Strong lineage" vs out-of-list lineage kinds in P1 data | only v3.5-listed kinds count; the out-of-list kinds are recorded (§5.6) |
| K6 | Independence counter is greedy (not maximum) | stated as a lower bound (A.8) |
| K7 | G-COCHANGE and G-TIMELINE-SIM depend on AI-produced timelines | flagged `inputs_ai_produced`; the snapshot hash freezes those inputs; OMQ-18 allows excluding them in a first pass |
| K8 | Section, decision, gate, OMQ and H references after the edits | checked mechanically after writing (see session log) |

## 34.3 Execution impact

| Step | Impact |
|---|---|
| S0–S3 | scripts must follow Appendix A (script review before S0); the sample plan no longer needs OMQ-07 |
| S4 | pilot excludes TESTED/STATUS until H-16; the contract adds §13.9a, §13.11, §14.4c |
| S5 | STATUS needs H-16; the core + profiles schema is lighter for observations |
| S5a/S5b/S6a | need H-15, H-17, H-18; blinded analysis; independence counting |
| S7 | report adds weighted sampling estimates, the control outcome tables and independence exclusions |
| Human decisions | new **H-16, H-17, H-18** with **OMQ-19, 20, 21** (options and recommendations given, values not fixed) |

---

# 35. CHANGE LOG v1.4 → v1.5

v1.4 (`20260924_1145_p3b-phase1-continuation-protocol-v1.4.md`) is kept unchanged. Source of the changes: the v1.4
adversarial review (`20260924_1157_p3b-protocol-v1.4-adversarial-review.md`), which combined the author's review with an
independent fresh-context review. **Architecture unchanged.** v1.5 was produced with plain string replacement only
(no regex substitution), so the v1.4 corruption mechanism cannot recur.

## 35.1 Corrections

| Finding | Change | Sections |
|---|---|---|
| V-C1 | pre-registration and freezing of the test plan; B/D at census scope; narrowing = new record citing exclusions; plan hash | §13.10a, §13.9a (2), G-09, principle box, D-51 |
| V-C2 | temporal scope computed as coexistence at a point; UNORDERED dominates; REFUTED within scope defined | §14.4c, A.10, D-52 |
| V-C3 | pooled generator × class comparison; pre-registered class vocabulary, DESCRIPTIVE/DISCOVERY mapping and decision rule (H-17); absent/unregistered/no-control = UNDIFFERENTIATED; CORPUS baseline derived; full reporting | §9E.2 items 4–10, §9E mandatory content, §13.9a (4), H-17, OMQ-20, D-53 |
| V-C4 | independence counter drops DESCRIPTIVE and control sets; ordering by sorted member tuples | A.8, G-13, §9E.3, D-54 |
| V-C5 | D-40 and §31 restored; §28 "approve v1.5"; freeze integrity check | §26 item 6, A.9, D-55 |
| V-I1 | THEORY-CANDIDATE = class on HYPOTHESIS/STRUCTURE-CANDIDATE at CORPUS scale | §13.2, §13.6, D-56 |
| V-I2 | G-09 split script vs audit; OBJECT-scale STATUS sampled for truth | G-09, §21 item 6, D-57 |
| V-I3 | CORPUS always TESTED (OMQ-16 option removed); CROSS-OBJECT support defined per participant | OMQ-16, §13.9a (1), D-57 |
| V-I4 | blinding declared partial where content leaks; defining-link fields stripped; `blinding_level` | §9E.2 item 4, §9C.1 rule 4, A.7 |
| V-I5 | control-set findings: `generator_basis: CONTROL`, never occurrences | §9E.2 item 9, §9E.3 |
| V-I6 | seeded budget sub-sampling | A.6 |
| V-I7 | sampling outcome uses G-09/G-12 for all kinds | §9C.1 rule 1 |
| V-I8 | output hash excludes header; Python/platform recorded; regex engine and symbol boundaries; RNG order and small strata; θ owned by H-15; type token classes; OMQ-17 input policy for G-DEPENDENCY; "no pair linked"; NO-CONTROL-AVAILABLE | §19.5, A.4–A.7 |
| V-I9 | H-18 no longer re-opens PRIMARY-only; A.8 and §9E.3 PRIMARY wording aligned. **Erratum to the historical §34.1:** its RC-4 row names "G-12/G-13"; the checks are G-09/G-13 (§14.4c) | H-18, §9E.3, A.8 |
| V-I10 | G-SHARED-GROUP on P3a-judged pairs only for non-identity classes | §9E.1 |
| V-I11 | strong-lineage row shows 217 | §9C.1 |

## 35.2 Consistency and integrity

The freeze integrity check (A.9) and the reference check were run on this file after writing. Their results are
recorded in the session log for 2026-09-24. The **authoritative** run is the one recorded with the approval (§26
item 6).

## 35.3 Execution impact

| Step | Impact |
|---|---|
| S0–S3 | scripts follow the refined Appendix A (A.4, A.5, A.9, A.10); still a script review before S0 |
| S4 | the contract adds §13.10a and computed temporal scope; the pilot still excludes STATUS until H-16 |
| S5 | STATUS needs census-scope B/D and a frozen plan; OBJECT-scale STATUS audited by sample |
| S5a/S5b/S6a | the H-15 plan must pre-register the class vocabulary, the DESCRIPTIVE/DISCOVERY mapping, θ and budgets; H-17 adds the decision rule |
| Approval | H-01 now requires the A.9 output to be recorded (§26 item 6) |

---

**Traceability:** commissioned 2026-09-24 ("Design the method first — Phase-1 KnowledgeOS continuation protocol");
corpus boundary from the human decision of 2026-09-24 (`02-FILES.jsonl` authoritative); v1.1–v1.4 as recorded in §29,
§30, §32, §34; **v1.5 from the v1.4 adversarial review (`20260924_1157_p3b-protocol-v1.4-adversarial-review.md`) and the
human's instruction to write the targeted corrections (same day)**. Supersedes:
`prompts/20260924_1145_p3b-phase1-continuation-protocol-v1.4.md` (v1.4).
