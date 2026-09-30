# F-SERIES v1.3 → GOVERNING PHASE-1 PROTOCOL — H-0 VERIFICATION AND REFACTOR MAP

**Status:** research and conformance investigation only.
- Nothing was implemented, renamed, migrated or deleted.
- H-1…H-8 stay open; this map recommends and decides nothing.
- v1.2 stays frozen and not approved; v1.3 stays design-only; F3082 stays READ-COMPLETE.

**Commission:** the human's task of 2026-09-25, "H-0 governing-protocol verification → F-Series refactor map".

**Output location — a deviation from the commission, and why.**
- The commission names `docs/knowledgeos/chronological-read/prompts/F-SERIES-v1.3-MASTER-PROTOCOL-REFACTOR-MAP.md`. That directory is:
  1. the S-Series lane, which is read-only under the human's standing F-Series isolation rule;
  2. the frozen P3A baseline, which the governing Phase-1 protocol says "must never be written into or mixed with" (`knowledge_os_protocoll.md` L4290).
- The human's lane rule is "Dont write anywhere else", so the map is written at `docs/knowledgeos/chronological_knowelgeos_ablation_theory/prompts/F-SERIES-v1.3-MASTER-PROTOCOL-REFACTOR-MAP.md`.
- Moving it is a human choice.

**Evidence classes used throughout:**

| Tag | Meaning |
|---|---|
| **[F]** | SOURCE FACT: stated in the cited file and lines |
| **[D]** | DERIVED ANALYSIS: a conclusion drawn by comparing source facts |
| **[O]** | OPEN QUESTION: not resolvable from the inspected material |

## 0. Reading record

HEAD was `d3629aa5e` at the start of the investigation and `4c1946181` at the end. Another session committed in between, touching none of the files below.

| Document | Path | sha256 (first 16) | Lines | Read |
|---|---|---|---|---|
| Frozen research architecture (ARCH) | `docs/knowledgeos/knowledgeos_theory_chronological_extraction/prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md` | `e3bbf29269346cde` | 492 | **1–492 (complete)** |
| Phase-1 protocol (MP) | `docs/knowledgeos/knowledgeos_theory_chronological_extraction/prompts/knowledge_os_protocoll.md` | `579476883de691fa` | 4,721 | **1–4,721 (complete)** |
| S-Series protocol v3.5 (M35) | `docs/knowledgeos/chronological-read/prompts/20260911_0221_prompt3-optimized.md` | `508b9f99edaea870` | 515 | **1–515 (complete)** |
| Research State (STATE) | `…/knowledgeos_theory_chronological_extraction/KNOWLEDGEOS-RESEARCH-STATE.md` | — | 103 | 1–103 |
| Execution instruction (README-P) | `…/knowledgeos_theory_chronological_extraction/prompts/readme.md` | — | 427 | 1–200 (complete); 200–427 by grep only |
| Control plane (GOV) | `…/governance/README.md` (1–80), `gates.yaml` (1–200, 460–500, plus a grep of every gate id), `governance-state.yaml` (1–66, complete), `L0-DECISION-RECORD-01.md` (1–40; L281–284) | — | — | as stated |
| P3A closure record | `docs/knowledgeos/chronological-read/audit-p3a/P3A-FROZEN-BASELINE.md` | — | 195 | 1–60 |
| Step-2 protocol | `…/prompts/knowledge_os_step2_theory_construction_protocol.md` | — | 2,510 | L391–407 (ladder), L230–233 |
| F-Series lane | all 77 tracked files (§A) | — | 15,066 | contracts C01–C15 read in full this session; other artifacts by structure and inventory |

**Change check [F].** All three governing files were re-hashed after reading and matched the hashes taken before. None is dirty in git. No file changed while it was being read.

**Not relied upon:** earlier agent or session summaries. Every citation below was re-read in this investigation.

---

## 1. H-0 — Governing Protocol

### 1.1 Source facts

| # | Fact | Where |
|---|---|---|
| F1 | ARCH: *"The Phase-1 and Phase-2 protocols **reference** this document. ⛔ They must not duplicate or redefine it."* | ARCH L8 |
| F2 | ARCH cites *"the Master Protocol's own mission statement (§397) requires Phase 1 to 'maintain a provenance-preserving Theory Object Registry (§19A)'"* | ARCH L461, L478 |
| F3 | MP §0B contains that mission text verbatim: *"…maintain a provenance-preserving Theory Object Registry (§19A)…"* | MP L468 |
| F4 | MP: *"Before executing this protocol, read `KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md`"*; *"This protocol governs the HISTORICAL RECONSTRUCTION & EVIDENCE CONTEXT (Phase 1)"* | MP L11–17 |
| F5 | STATE: *"Phase-1 protocol · `prompts/knowledge_os_protocoll.md`"* | STATE L14 |
| F6 | README-P: *"Use: 1. **Master Protocol** as the authoritative protocol for Phase 1."* The file sits in the same `prompts/` directory as MP | README-P L7 |
| F7 | GOV: Phase-1 and checkpoint gates cite `knowledge_os_protocoll.md` as their source (KOS-G-002, 003, 010–012) | `gates.yaml` L49, L67, L98 |
| F8 | ARCH contains **0** occurrences of `v3.5`, `prompt3` or `chronological-read` | grep, this session |
| F9 | M35 states its purpose as *"construct the KnowledgeOS theory from the complete historical corpus"*, with phases P1…P7 ending in theory synthesis | M35 L6; A2 |
| F10 | MP §1: *"The previous P3A investigation is now a **frozen baseline** … must not be treated as the canonical historical derivation graph … remains available as candidate evidence …"* | MP L1001–1028 |
| F11 | MP §41: the reconstruction area is *"Deliberately outside `docs/knowledgeos/chronological-read/`, which already holds the frozen P3A baseline"* | MP L4284–4290 |

### 1.2 Derived analysis

- **[D1]** F2 plus F3 identify the architecture's "Master Protocol" as `knowledge_os_protocoll.md` by content. The cited "§397" is not a section number; it is most likely a line number from an earlier revision of MP. That is an **identification, not an assumption**: the quoted mission text is unique to MP §0B.
- **[D2]** F1 and F4–F7 are five independent pointers from four different artifact kinds (architecture, protocol, state, control plane). All name `knowledge_os_protocoll.md` as the Phase-1 protocol.
- **[D3]** No inspected document names M35 as governing Phase 1 under the frozen architecture (F8). F9 gives M35 a purpose ARCH forbids to Phase 1 (ARCH L75: *"Phase 1 does not produce 'the theory'"*; ARCH §7 L283 *Candidate Theory: Phase 1 ⛔ no*). M35 therefore **cannot** be the Phase-1 protocol of the frozen architecture without contradicting it.
- **[D4]** F10 and F11, with §2 below, give M35's *output* a defined role: the frozen P3A baseline. M35 is the **historical predecessor protocol that produced P3A, whose output is Phase-1 input evidence**. F-Series inherited its methodology by the human's bootstrap instruction; that does not make it governing.

### 1.3 Ambiguities found (none contradicts D1–D4)

| # | Ambiguity | Where | Class |
|---|---|---|---|
| A1 | MP says the architecture is *"FROZEN v1.0"*; it is v1.2 | MP L13 vs ARCH L1 | stale reference [F] |
| A2 | STATE says *"v1.1 — FROZEN"* | STATE L13 | stale reference [F] |
| A3 | README-P lists `architecture_phase_1_phase_2.md` as governing architectural context; ARCH says it is *"the superseded chat proposal … ⛔ Do not review, cite or amend it as the architecture"* | README-P L13 vs ARCH L492 | conflicting instruction [F]. [O]: governance to resolve |
| A4 | MP puts a **Candidate Theory Registry** (§19C, CT-####) in Phase-1 scope (MP L493–495, L2961–3084). ARCH §7 L283 says *Candidate Theory: Phase 1 ⛔ no* | MP vs ARCH | [D] probably a term collision (RA-9): MP's CT is a "provisional, retractable consolidation", while ARCH's Candidate Theory is the Phase-2 product. [O]: not decided here |
| A5 | MP §0D lists a "Validation Context" among five ownership areas (L734). ARCH §8B rejects a validation *bounded context* (L355–361) | MP vs ARCH | [D] MP §0D calls these "areas of ownership", not DDD bounded contexts. [O]: wording to reconcile |
| A6 | M35's S-Series continuation (P3B protocols v1.x, 2026-09-24/25) is still running in `chronological-read/` under M35 | `chronological-read/prompts/` listing | [O]: whether the S-Series is itself governed by ARCH is outside this map |

### 1.4 Verdict

> ## **H-0 = CONFIRMED (a).** `docs/knowledgeos/knowledgeos_theory_chronological_extraction/prompts/knowledge_os_protocoll.md` is the binding Phase-1 protocol.
>
> **S-Series/v3.5 is inherited methodology and historical input, not the Phase-1 governing protocol.** Its output is the frozen P3A baseline, which MP consumes as candidate evidence only.
>
> Ambiguities A1–A6 are real, but none is a normative statement that another protocol governs Phase 1. **H-0 is not CONFLICT.**

---

## 2. P3A — what it is and what it imposes

| # | Finding | Evidence | Class |
|---|---|---|---|
| P1 | P3A is the output of the S-Series M35 pipeline through P3a: P1 extraction, P2 label/family normalisation, P3a pairwise reconciliation. Scale: 70/70 batches, 2,779 files, 27,906 contributions, 1,895 labels, 1,793 judged pairs | `P3A-FROZEN-BASELINE.md` L1–30 | [F] |
| P2 | Frozen 2026-09-22 at `125cfe8371`; closure record `c9e76918b`. It is **"paused and frozen, not abandoned and not canonicalized"** | same, L1–13; git log | [F] |
| P3 | The closure record names MP's phase as its successor: *"the next phase (file-level historical reconstruction) is a separate, differently-scoped effort that treats P3A as one evidence source among several, never as ground truth"* | same, L12–13 | [F] |
| P4 | MP §1: P3A is candidate evidence, a navigation aid, a prior hypothesis, a relationship-candidate source, a comparison baseline and a recall benchmark. **"Do consult it … Never adopt it."** Every P3A field becomes a `p3a_*` cross-reference, verified against the complete file | MP L1007–1041 | [F] |
| P5 | MP §42 requires a **P3A comparison layer** (`p3a_candidate`, `p3a_relationship`, agreement/disagreement) and `P3A-COMPARISON.jsonl` (Tier 2) | MP L4409–4437, L4305, L4371 | [F] |
| P6 | MP: P3A's `best_historical_date` is **wrong for 3 of 10 pilot files** and must not be inherited; it is kept only as `p3a_best_historical_date` | MP L4716 | [F] |
| P7 | P3A's reliability is audited as **YELLOW**: 20.9 % exact / 75.3 % coarse agreement on blind re-derivation. `row_brief()` dropped 7,407 contributions' dependency/lineage/invariant/assumption fields | `P3A-FROZEN-BASELINE.md` L18–30; `KSME-20-FORK-DECISION.md` | [F] |

**Role [D]:** P3A is **evidence and a comparison baseline, not a protocol**. F-Series (as an MP execution layer) would operate **after** P3A and **alongside** it as a comparison source. It would never operate on it or repair it.

**P3A mechanisms that F-Series re-implemented [D]:**

| P3A / M35 mechanism | F-Series re-implementation | Evidence |
|---|---|---|
| the S reader's page model | reused by compiling the committed S blob | F-Series C01 v1.1 header |
| duplicate handling (R3) | inherited | protocol v1.0 §F-3 row 4 |
| order evidence (R1a) | inherited | row 7 |
| contribution schema (XC) | inherited | rows 12–13 |
| self-audit (A8) | adapted | row 22 |

This is inheritance of the *method that produced P3A*, not duplication of P3A data.

**Constraints P3A imposes on F-Series:**
- **[F]** MP §1 requires consulting P3A's per-file metadata (L1038).
- **[D]** F-Series' S-identifier guard (C15 §5 forbids `S####`, `P3a`, `chronological-read/` in analyst fields) **blocks the `p3a_*` comparison fields that MP §1/§42 require.** Under R-A the guard must be narrowed: forbid S identifiers in interpretive fields, allow them in `p3a_*` cross-reference fields. That is an F-side change, not an MP amendment.
- **[F]** The human's F-isolation rule (S-Series read-only) is compatible, because MP §1 consults P3A and never writes to it.

**Can F-Series become an execution/assurance layer without changing the P3A contract? [D]: yes.** P3A's contract (frozen, consult-never-adopt) binds only reads. Nothing in R-A writes to P3A.

---

## A. F-Series artifact inventory (77 tracked lane files + 1 external input)

**Verdict vocabulary:**

| Verdict | Meaning |
|---|---|
| **KEEP** | stays in an active role unchanged |
| **KEEP-H** | kept as an append-only historical record, with no active role (never deleted: RA-2 spirit, MP §5A) |
| **TRANSFORM** | the mechanism or content is kept; its schema, vocabulary or authority changes |
| **SPLIT** | part transforms into Phase-1 form, part moves to Phase 2 |
| **MOVE-P2** | belongs to Phase 2 (Step 2) |
| **GOV** | the governance decision named |

**MP amendment?** yes / no / uncertain. "MP equivalent" gives section and line numbers in `knowledge_os_protocoll.md`.

### A.1 Protocols and specifications (`prompts/`)

| # | Artifact | Ver. | Function | MP equivalent | Verdict | Reason · replacement | MP amend.? |
|---|---|---|---|---|---|---|---|
| 1 | `20260925_1239_F-SERIES-RESEARCH-PROTOCOL-v1.0.md` | 1.0 | first F protocol; M35 inheritance matrix §F-3 (L72–107) | none (parallel protocol) | KEEP-H | superseded by v1.1/v1.2; frozen record (F-LOG-0002) | no |
| 2 | `…_1239_F-SERIES-AGENT-CONTRACT-v1.0.md` | 1.0 | per-file reading-agent procedure | MP §9 L1746–2054 | KEEP-H | superseded | no |
| 3 | `…_1333_F-SERIES-RESEARCH-PROTOCOL-v1.1.md` | 1.1 | research-discovery programme: 3 levels, 14 contracts | none | KEEP-H | superseded; its theory-directed purpose conflicts with the MP role binding (MP L140–151) | no |
| 4 | `…_1333_F-SERIES-AGENT-CONTRACT-v1.1.md` | 1.1 | per-file procedure v1.1 | MP §9 | KEEP-H | superseded | no |
| 5 | `…_1412_F-SERIES-RESEARCH-PROTOCOL-v1.2.md` | 1.2 (frozen, unapproved) | current protocol | MP whole; §9, §9A, §36, §36A | TRANSFORM | → an **MP execution & assurance specification** that references ARCH + MP (ARCH L8) and keeps reading, sealing, CAS and audit rules; its Phase-2 parts go to Step 2 | uncertain (only if assurance rules become binding, §G) |
| 6 | `…_1412_F-SERIES-AGENT-CONTRACT-v1.2.md` | 1.2 | per-file procedure v1.2 | MP §9 FRR L1924–1977; §6A; P1-Q1 L38–60 | TRANSFORM | → procedure emitting the File Reconstruction Record, Evidence Objects (`E-####`) and a TDI entry | no |
| 7 | `…_1412_F-SERIES-v1.2-REMEDIATION-MATRIX.md` | 1.2 | v1.1-audit remediation map | none | KEEP-H | audit trail | no |
| 8 | `…_1523_F-SERIES-v1.3-DESIGN-FOR-REVIEW.md` | 1.3 design | EvidenceObject, SignedDecision, CAS, lineage, DD-1…DD-4 | §5A L1318–1379; header L68–76 | GOV (H-5…H-8) | the DDs are open human decisions; its mechanisms transform once decided | no |
| 9 | `…_1528_F-SERIES-v1.3-INVARIANTS-AND-THREAT-MODEL.md` | 1.3 r2 | four invariants, threat model, T1–T20 state table | §36/§36A reproducibility L3936–4026 | TRANSFORM | GIT-INTEGRITY / EVIDENCE-INTEGRITY / PROVENANCE survive as assurance invariants; AUTHORIZATION is rebased on GOV (RA-16); T1–T20 is rebased on MP §9A gates | no |
| 10 | `F-SERIES-v1.3-DD1-DD4-ARCHITECTURE-REVIEW.md` | review | DD-1…DD-4 architecture review (F-LOG-0013) | — | KEEP-H | review record. ⚠ **Two claims are corrected by this map:** §F (identity) and §C (hypotheses) | no |
| 11 | `prompt_2.md` | human | conformance-audit commission | — | KEEP-H | commission record | no |
| 12 | `prompt_v1.0.md` | human | new-architecture commission (superseded) | — | KEEP-H | commission record | no |

### A.2 Contracts (`prompts/contracts-v1.1/` 14 files · `prompts/contracts-v1.2/` 16 files)

Each v1.2 contract is a delta on its v1.1 base, except C13 v1.2 (full text) and C15 (new). The verdict applies to the base–delta pair.

| # | Contract (files) | Function (read in full this session) | MP equivalent | Verdict | Reason · replacement | MP amend.? |
|---|---|---|---|---|---|---|
| 13 | `00-CONTRACTS-IN-FORCE.md` (1) | hash index of contracts in force; approval target | — | TRANSFORM | index of the assurance specification | no |
| 14–15 | **C01** reading integrity (2) | sole sanctioned reader, 20,000-char pages, page hashes, digests, READ-COMPLETE gate | §2 L1045–1071; §9 step 2 L1765; §9A `FILE_READ` L2063 | **KEEP** | mechanically enforces a rule MP states (complete-file reading) | no |
| 16–17 | **C02** complete content extraction (2) | deterministic units, 26-category inventory, dispositions, coverage floor | §9 completeness check L1835–1843; §9A `EXTRACTION_COMPLETE`; "empty is valid" L1997–2004 | TRANSFORM | keep the unit frame and floor as assurance; items become MP records (§9 FRR fields L1943–1951; `E-####` §6A L1476–1558) | no |
| 18–19 | **C03** concept/term/definition (2) | verbatim DEFINITION/TERM items; term keys | §17 L2628–2701 (`D-####`, `definition_relationship`); §4A collision check L1270–1290 | TRANSFORM | → Definition records. Drop the theory-priming example terms "kernel/invariant/fixed point" (ACL-2 risk) | no |
| 20–21 | **C04** structural reconstruction + structural lens (2) | XC STEPS 2–12; structural analysis; lens checklist | §9 Layer 1 steps 6–10 L1763–1772; §6 dossier L1383–1472; §0E.4 Level 2 L894–934 | TRANSFORM | XC contributions → FRR fields; structural lens → Level-2 documentation-quality dimensions; lens checklist → the MP `NOT_APPLICABLE`-vs-`UNCERTAIN` discipline (L917–919) | no |
| 22–23 | **C05** cross-file relationship discovery (2) | term-key candidates vs earlier AUDITED files; checkpoint cross-file pass | §8 candidate→verified pipeline L1650–1705; §7 L1562–1646; §17 drift | TRANSFORM | this is **Phase 1A**, not "Level 2": candidates → `CANDIDATE-EDGES`, verified → `VERIFIED-EDGES`. No-look-ahead conflicts with §0E.1–0E.3 (H-4) | no |
| 24–25 | **C06** mathematical analysis (2) | well-definedness, typing, proof status; `CORRECTNESS-FINDING` | §0A statuses L389–412; §0E.4–0E.5 L888–948; §14 L2437–2469 | **SPLIT** | P1: MATH-QUESTION flags, documentation quality, and gaps demonstrable from the source's own rules (`ALGEBRAIC_INCONSISTENCY`…) or `POTENTIAL_VALIDATION_ISSUE` (L2465–2469). P2: mathematical validation (ARCH §7 L279) | no |
| 26–27 | **C07** logical analysis (2) | validity, circularity, internal tensions | §14 `LOGICAL_INCONSISTENCY`, `UNJUSTIFIED_INFERENCE`, `CONCLUSION_EXCEEDS_PREMISES`; §22 L3179–3214 | TRANSFORM | → Gap and Contradiction records | no |
| 28–29 | **C08** DDD/domain analysis (2) | domain concepts, invariants, bounded contexts named by the file | §0C HA-###/AR-### L569–721 (Layer 2 steps 11–14); §21 non-collapse invariants L3151–3175 | TRANSFORM | → Emergent Historical Architecture records; a "proposed model for KnowledgeOS" → `[E]` record (L147) | no |
| 30–31 | **C09** statistical/ML (2) | per-file STAT flags; checkpoint EXPLORATORY and CONFIRMATORY | §14 STATISTICAL_* gap types; scope binding L148; §27 ML policy L3400–3431 | **SPLIT** | P1: flags, and exploratory statistics as labelled discovery signals. P2: confirmatory tests (MP §0B out-of-scope item 10 "Testing" L519) | no |
| 32–33 | **C10** hypothesis generation (2) | Level-3 records, falsification, pre-registration | scope binding item 8 L105–114, L147 (`[E]` record, nine fields, status `HYPOTHESIS`/`NOT_YET_ASSESSED`) | **SPLIT** | P1: the hypothesis *record* in MP `[E]` form, handed over in the RRP. P2: pre-registration and test design | no |
| 34–35 | **C11** hypothesis testing (2) | checkpoint tests A–D, SUPPORTED/UNSUPPORTED | MP §0B out-of-scope items 2, 3, 10 L509–519; ARCH §7 L279–282 | **MOVE-P2** | testing and validation are Phase 2 (2C) | no |
| 36–37 | **C12** evidence and provenance (2) | chain F-ID→page→unit→item→contribution→analysis→research | §6A three provenance layers L1517–1536; RA-14 ARCH L371–390 | TRANSFORM | keep the chain; map it to MP namespaces (`E-####`, §4A L1245–1266); anti-projection is an H-4 question | no |
| 38–39 | **C13** state machine and audit (2) | hash-chained state ledger, transitions, independent L1 audit, checkpoints | §5 statuses L1294–1314; §9A L2058–2083; §36/§36A L3903–4026; §37 L4030–4074 | TRANSFORM | states → §9A gates and §36A state; hash chain keep (fills §49 item 13); independent audit keep as assurance; checkpoint *tests* → P2 | uncertain (audit, §G) |
| 40–41 | **C14** human governance and change control (2) | F-GOVERNANCE-LOG authority, versioning, cadence gates | GOV (RA-16, ARCH L428–449); MP §49 item 5 L4706 | GOV (H-2) | a second decision register beside GOV (§E) | no |
| 42 | **C15** isolation (1) | extractor/auditor allow- and deny-lists, attestation, S-identifier guard | none in MP or ARCH | TRANSFORM | **conflicts with MP if applied to the whole per-file pass.** MP requires stateful processing (L2006–2054 "Never start from zero"), comparison with earlier records (§0E.2 L827–851), the Reference Architecture (Layer 2 step 12) and P3A consultation (§1). C15 denies all of these. **Limit isolation to the Layer-1 extractor and the independent auditor**; narrow the S guard (§2) | no if limited; **yes** if applied to all layers |

### A.3 Execution, state and governance records (lane root)

| # | Artifact | Function | MP / ARCH equivalent | Verdict | Reason · replacement | MP amend.? |
|---|---|---|---|---|---|---|
| 43 | `F-MANIFEST.jsonl` (1,523 rows) | per-file identity, content sha256, pages, duplicates | §4 L1192–1240; §5 L1294–1314; §36A `corpus_manifest_hash` L4006–4026 | TRANSFORM | → an execution-layer file table keyed by the canonical `file_id` (1,496 rows already canonical, §F). The 27 new IDs → H-3 | no |
| 44 | `F-SERIES-STATE.jsonl` (4,556 legacy lines + chain) | per-file state events | STATE (RA-11 ARCH L334); MP §36A `RECONSTRUCTION-STATE.json` | TRANSFORM | the hash-chain mechanism stays; execution position is reported into STATE / §36A. Authority → H-2 | no |
| 45 | `F-STATE-ANCHOR.json` | seals the 4,556 legacy lines | §36 "no loss … without provenance" L3936–3945 | KEEP | integrity of history | no |
| 46 | `F-READ-INTEGRITY.jsonl` | READ-COMPLETE integrity record | §9A `FILE_READ` | KEEP | evidence for an MP gate | no |
| 47 | `F-DECISIONS.json` | HDR switches `temporal_semantics`, `multiplicity_rules` (both null) | none; testing is out of Phase-1 scope | GOV (HDR-2 → H-4; HDR-3 → Phase 2) | multiplicity is a Phase-2 testing question | no |
| 48 | `F-GOVERNANCE-LOG.md` | F-LOG-0001…0013 decisions and observations | GOV `L0-DECISION-RECORD-01.md`; `governance-state.yaml` | GOV (H-2) | history is kept append-only; future authority records follow H-2 | no |
| 49 | `F-SESSION-LOG.md` | lane session log | — | KEEP-H | history | no |
| 50 | `F-BOOTSTRAP-REPORT.md` | bootstrap report | — | KEEP-H | history | no |
| 51 | `F3082-READINESS-GATE.md` | readiness gate for F3082 | — | KEEP-H | history | no |
| 52 | `F-BASELINE-GIT-STATUS.txt` | working-tree baseline at bootstrap | — | KEEP-H | history | no |

### A.4 Evidence, quarantine, audits

| # | Artifact | Function | Verdict | Reason |
|---|---|---|---|---|
| 53–54 | `ledger/F3082/READ-LOG.jsonl`, `PAGE-DIGESTS.jsonl` | F3082 read evidence (2/2 pages; one disclosed refused line) | KEEP | evidence for MP `FILE_READ`. ⚠ This read was performed by the orchestrating session (§H) |
| 55–59 | `quarantine/README.md`, `quarantine/F3082-draft-v1.0/{files,contributions,index-proposals}.jsonl`, `p1.py` | parked pre-v1.1 draft | KEEP-H | MP §45 Gate 0 calls pilot artifacts "explicitly disposable" (L4564). Disposal is permitted, not required |
| 60–64 | `audits/…v1.1-INDEPENDENT-AUDIT.md`, `…v1.2-INDEPENDENT-REAUDIT.md`, `…v1.2-ADVERSARIAL-TEST-REPORT.md`, `audits/…auditor-experiments/exp.py`, `exp2.py` | audit records | KEEP-H | audit trail |

### A.5 Executable components (`scripts/`, `tests/`)

| # | Artifact (lines) | Function | MP equivalent | Verdict | Reason |
|---|---|---|---|---|---|
| 65 | `f_read_source.py` (102) | controlled reader; look-ahead and approval refusal | §2; §9A `FILE_READ` | KEEP | the look-ahead refusal is subject to H-4; the approval refusal to H-2 |
| 66 | `f_units.py` (67) | deterministic unit segmentation | §9 completeness | KEEP | assurance |
| 67 | `f_compare_inventory.py` (162) | recomputed independent-audit comparison | none (assurance) | KEEP | assurance |
| 68 | `f_common.py` (188) | paths, GOVERNING_DOCS, `ALLOWED_FROM`, `record_event`, `next_fid` | §9A; §36A; RA-12 | TRANSFORM | states → MP gates; `next_fid` → recomputed from STATE and registry order |
| 69 | `f_integrity.py` (220) | chain, anchor, freezes, `require_approval`, `human_ref_ok` | §36/§36A; GOV | TRANSFORM | chain, anchor and freezes keep; approval and human-reference functions rebase on GOV (H-2) |
| 70 | `f_checks.py` (837) | inventory, attestation, reconstruction, analysis, research, cross-file checks | §9, §9A, §8, §13–§14 | TRANSFORM | inventory checks keep; analysis → Gap/`[E]` records; research → `[E]` form or P2 |
| 71 | `f_transition.py` (170) | gated transitions | §9A | TRANSFORM | gate sequence → §9A order |
| 72 | `f_audit.py` (121) | mechanical audit | §37 batch validation L4030–4049 | TRANSFORM | mechanical checks align with §37 |
| 73 | `f_status.py` (40) | restart point | RA-11/RA-12; §36A | TRANSFORM | report into STATE |
| 74 | `f_crossfile.py` (27) | cross-file candidates | §8 | TRANSFORM | → candidate edges |
| 75 | `f_register.py` (169) | F-P0 registration | §4 import L1220–1237; §45 Gate 3 L4576–4582 | TRANSFORM | import from the canonical registry |
| 76 | `f_checkpoint.py` (140) | checkpoint open/run (run blocked) | none in Phase 1 | MOVE-P2 | populations and hypotheses for tests are Phase 2 |
| 77 | `tests/test_f_pipeline.py` (888; 84 tests) | tests | — | TRANSFORM | tests follow the code |

### A.6 External input (outside the lane, not tracked in it)

| # | Artifact | Function | MP rule | Verdict | Reason |
|---|---|---|---|---|---|
| 78 | `docs/knowledgeos/20260925_1206_list_of_files_to_read.log` (1,523 rows, committed `abc0a9153`) | F population and order | MP §3 L1159: *"This log is the single starting registry … Do not derive a second, competing inventory"*; L1101: registry row order is the read order | GOV (H-3, H-11) | a second inventory with a different order (§F) |

---

## B. Mechanism-by-mechanism map

**Columns:**
- **Req?** — does MP (or ARCH) already require it, and where?
- **F does** — IMPL (implements the requirement) · STR (strengthens it) · NEW (no MP counterpart) · CONFL (conflicts) · —
- **Conf.** — does the strengthening stay conformant?
- **New rule?** — does it add a normative rule?
- **Assur.?** — can it live as an implementation/assurance mechanism?
- **Amend?** — is an MP amendment required?
- **Phase** — 1, 2 or GOV.

| # | Mechanism | Req? (where) | F does | Conf. | New rule? | Assur.? | Amend? | Phase |
|---|---|---|---|---|---|---|---|---|
| 1 | controlled reader | yes · §2 L1045–1071; §9 step 2 | STR | yes | no | yes | no | 1 |
| 2 | whole-file reading | yes · §2; §0E loop L773 | IMPL | yes | no | — | no | 1 |
| 3 | page coverage | complete reading required, not measured | STR (makes it measurable) | yes | no | yes | no | 1 |
| 4 | unit frame | yes · §9 "nothing is silently omitted" L1841 | STR | yes | operationalises an existing rule | yes | no | 1 |
| 5 | inventory floor | same; §9 "empty is a valid result" L1997 | STR | yes (floor = coverage, not invention) | no | yes | no | 1 |
| 6 | extraction contract (XC schema) | MP has its own FRR schema L1924–1977 | CONFL (competing schema, ARCH L8) | no, as-is | — | — | no (transform F) | 1 |
| 7 | append-only recording | yes · §5A L1318–1379; RA-2 | IMPL | yes | no | — | no | 1 |
| 8 | source identity | yes · §4 `file_id`; §36A hashes | IMPL + STR (content sha256) | yes for 1,496 IDs; 27 → H-3 | no | yes | no | 1 |
| 9 | hashes (corpus/protocol/schema) | yes · §36A L4006–4026 — **"specified but unimplemented"** (§49 item 13 L4717) | fills an MP gap | yes | no | yes | no | 1 |
| 10 | evidence sealing (EvidenceObject, freezes) | reproducibility required §36 L3936–3945; no sealing mechanism | NEW (mechanism) | yes. ⚠ **the name "Evidence Object" collides with MP §6A `E-####`** | no | yes | no (rename F) | 1 |
| 11 | Git CAS commit | MP §49 item 4 OPERATIONAL: "which store hosts this state, and which agent/session boundary owns writing it" L4705 | NEW (an operational answer) | yes | no | yes | no | 1 (operational) |
| 12 | concurrency protection | §36A crash safety L3992–4004 | STR | yes | no | yes | no | 1 |
| 13 | lane ownership | MP §41 fixes the reconstruction area L4284–4290 | CONFL (F writes to a different lane) | [O] | — | — | no — **GOV (H-10)** | GOV |
| 14 | independent audit | none per file (§37 is a batch self-check; STATE lists B-12 "INDEPENDENT AUDIT" as backlog) | NEW | yes as evidence | **yes if it gates** `dossier_status`/§9A | yes (non-gating) | **uncertain** — required only to make it binding | 1 |
| 15 | audit cadence (first + every 5th) | none | NEW (from M35 A8) | yes | same as 14 | yes | uncertain | 1 |
| 16 | quarantine | §45 Gate 0 pilot artifacts disposable L4564 | IMPL-compatible | yes | no | yes | no | 1 |
| 17 | isolation (C15) | none | NEW, and **CONFL** if applied to Layers 2–5 (L2006–2054; §0E.2; Layer 2 step 12; §1) | only if limited | yes | yes, if Layer 1 + auditor only | no if limited | 1 |
| 18 | re-extraction | §5A case 1 L1356; §33 L3805–3839; header L68–76 | TRANSFORM target | yes via §5A | no | — | no | 1 |
| 19 | version lineage | §5A fields L1324–1332 | IMPL (other vocabulary) | yes | no | — | no | 1 |
| 20 | human-decision binding (hash-bound) | GOV: activation is human-only (`governance-state.yaml` L1–10; KOS-G-060 L464–481); MP §49 item 5 L4706 | duplicate | [O] | — | — | no — **GOV (H-2)** | GOV |
| 21 | approval / reference lines | GOV activation pins (`governance-state.yaml` L44–49) | duplicate | [O] | — | — | no — GOV (H-2) | GOV |
| 22 | correction / revision | §5A three cases L1352–1358; §16; RA-5/RA-7 | TRANSFORM target | yes via §5A | no | — | no | 1 |
| 23 | chronology handling | §3 `date_events[]` → `historical_sequence_date` L1105–1158; `registry_timestamp_semantics` L1093 | partial (R1a `order_evidence` only) | partial | no | — | no (adopt §3) | 1 |
| 24 | source status | §9 `source_self_declared_status` L1981–1995; §6A `source_status` | partial (provenance class only) | partial | no | — | no | 1 |
| 25 | identity status | §19A identity test L2872–2885 (records candidates; resolution out of scope §0B item 1) | — (none recorded) | yes (records less) | no | — | no — but Theory Objects (Tier 1) must be added | 1 |
| 26 | relationship status | §5 `relationship_status`; §7; §8 | misplaced as "L2 observation" | no, as placed | no | — | no (transform F) | 1 |
| 27 | type compatibility | §21 L3120–3149 (`TYPE_MISMATCH`) | partial (TYPE-QUESTION flag) | yes | no | — | no | 1 |
| 28 | mathematical status | §0A L389–412; §0E.4; §14 | CONFL in part (`CORRECTNESS-FINDING` beyond "demonstrable") | split | — | — | no | 1 / 2 |
| 29 | validation status | out of scope §0B L509–510; only `VALIDATION_DOCUMENTED_IN_CORPUS` | none per file (C11 at checkpoints) | — | — | — | no | 2 |
| 30 | governance status (source's governance acts) | §9 `declared_status`/`declared_authority` L1987–1995 | — (missing) | — | no | — | no | 1 |
| 31 | Theory Discovery Index | **yes · P1-Q1 L38–60; gates KOS-G-010/011/012 active** | **missing** | — | no | — | no (add it) | 1 (1B) |
| 32 | hypothesis records | scope binding item 8 L147 (`[E]` record) | TRANSFORM target | yes as `[E]` | no | — | no | 1 (record) |
| 33 | pre-registration / confirmatory tests / checkpoint tests | out of scope §0B item 10 L519; ARCH §7 L281 | — | — | — | — | — | 2 |
| 34 | exploratory statistics / ML | scope binding L148; §27 L3400–3431 | IMPL-compatible if labelled a research signal | yes | no | — | no | 1 |
| 35 | no look-ahead | §0E.1–0E.3 L813–886: forward and backward investigation *permitted*; §15 Levels 6–7 require later and whole-corpus search before `UNDERIVED_BY_CORPUS` | CONFL (stricter) | [D] no textual contradiction ("may"), but `UNDERIVED_BY_CORPUS` becomes unreachable | — | — | no — **GOV (H-4)** | GOV |
| 36 | sequential gate (next file only after previous complete) | §9 L1752 "complete and validate the current file's record before proceeding" | IMPL (strict) | yes | no | — | no | 1 |
| 37 | S-identifier guard | MP §1/§42 require `p3a_*` cross-references | CONFL | no, as-is | — | — | no (narrow the F guard) | 1 |
| 38 | Track-A/B | §26 L3328–3396 (honest `UNKNOWN` allowed L3382) | — (missing) | — | no | — | no | 1 |
| 39 | ID namespaces | §4A L1243–1290 | F ids FCI/FAN/FRS/FXC outside MP namespaces | no, as-is | — | — | no (transform) | 1 |
| 40 | Reference-Architecture alignment (Layer 2) | §0C; §49 item 1 **BLOCKING** L4702; `UNAVAILABLE_NO_REFERENCE_ARCHITECTURE` rule L645–651 | — (missing) | — | no | — | no | 1 |
| 41 | Phase-0 corpus index | §8A L1708–1742; Tier 1 `CORPUS-INDEX.jsonl` L4354 | — (missing) | — | no | — | no | 1 |
| 42 | duplicate pointer instead of re-reading (RL-03) | MP §2 requires every file be read completely; **no duplicate exemption found** | NEW | [O] | yes | — | **uncertain — candidate amendment** or GOV | 1 |
| 43 | READ-PARTIAL (capacity, RL-06) | no partial-read state; `FILE_READ` simply not satisfied; `UNDERSTANDING_UNCERTAIN` L2079 | NEW state | yes if it maps to "`FILE_READ` not satisfied" | no | yes | no | 1 |

**Mechanism tally (43):**

| Outcome | Count |
|---|---|
| MP already requires it, and F implements or strengthens it conformantly (1–5, 7–9, 12, 16, 18, 19, 22, 27, 34, 36) | 16 |
| NEW, but can live as assurance without an amendment (10, 11, 43) | 3 |
| NEW, needing an amendment only if made binding (14, 15, 17*, 42) | 4 |
| Partial or misplaced → transform (6, 23, 24, 26, 28, 32, 37, 39) | 8 |
| Missing in F and required by MP (25, 30, 31, 38, 40, 41) | 6 |
| Phase 2 (29, 33) | 2 |
| Governance (13, 20, 21, 35) | 4 |
| **Mandatory MP amendments** | **0** |

\*17 needs no amendment if limited to Layer 1 and the auditor.

---

## C. Phase-boundary audit

| Capability | Classification | Basis |
|---|---|---|
| controlled reading, coverage, unit frame, inventory | **Phase 1** (1A) | MP §2, §9 |
| source claims, contributions, definitions, assumptions, premises, derivations, conclusions | **Phase 1** (1A) | MP §9 L1943–1951; ARCH §2A L95 |
| provenance chain, hashes, sealing, CAS | **Phase 1** (assurance) | MP §6A, §36A |
| chronology evidence (`date_events`) | **Phase 1** (missing in F) | MP §3 |
| source-declared status | **Phase 1** (missing in F) | MP §9 L1981 |
| cross-file candidate and verified relationships | **Phase 1** (1A) — ⚠ F placed them at "Level 2" | MP §7, §8 |
| theory-bearing indexing (TDI) | **Phase 1** (1B) — missing in F | MP P1-Q1 |
| math/logic/DDD lenses | **SPLIT:** documentation quality and source-demonstrable gaps = Phase 1; validation = Phase 2 | MP §0E.4, §14 L2469; ARCH §7 L278–279 |
| domain interpretation / external-theory comparison | **Phase 1 as a labelled `[E]` or observation record only**; construction = Phase 2 | MP L146–147; ARCH §7 L277 |
| researcher-generated hypotheses | **SPLIT:** the `[E]` record (status `HYPOTHESIS`/`NOT_YET_ASSESSED`) = Phase 1; pre-registration and testing = Phase 2 | MP L147; §0B L519 |
| pre-registration, confirmatory tests, multiplicity, checkpoint tests | **Phase 2** (2C) | ARCH §7 L279–282 |
| exploratory statistics / ML | **Phase 1** as a discovery instrument (labelled signal); **Phase 2** as validation | MP L148; §27 |
| "structure *for KnowledgeOS*", theory candidate at scale CORPUS | **Phase 2** (2A/2B) | ARCH §7 L275–283 |
| human decisions, approvals, GO, acceptance, reopening authorization, scope | **Governance / control plane** | ARCH RA-16 L428–449; GOV README L60–80 |

**Correction to the DD-1…DD-4 review (#10) [D].** The review said F-Series L3 hypotheses belong to Phase 2. MP's scope binding (L147) admits hypothesis **records** in Phase 1 in the `[E]` form, with nine fields and status `HYPOTHESIS`/`NOT_YET_ASSESSED`. Only *constructing on*, adopting, pre-registering and testing them is Phase 2. C10 is therefore SPLIT, not MOVE-P2.

---

## D. Epistemic-level collision audit (no renaming performed)

**Three ladders exist [F]:**

| Ladder | Levels | Where |
|---|---|---|
| ARCH / Step 2 | L0 historical evidence · L1 reconstruction · L2 hypothesis · L3 derivation · L4 validation · L5 canonical | Step-2 L391–396; ARCH standing chain L373 |
| MP §0E.4 | Level 1 historical reconstruction · Level 2 reasoning documentation quality · Level 3 validation | MP L888–938 |
| F-Series | L1 source extraction/reconstruction · L2 analysis · L3 research/hypothesis | protocol v1.1–v1.2; C06 §2 `level: 2`; C10 `level: 3` |

| Collision | F meaning | Governing meaning of the same label | Consequence | Proposed disposition | Human? |
|---|---|---|---|---|---|
| "L1" | extracted source content | ARCH L1 = reconstruction; MP Level 1 = historical reconstruction | [D] roughly aligned: F L1 ⊂ ARCH L1 | keep the concept; qualify the name | no |
| "L2" | analysis: observations, correctness findings, domain interpretation, external comparison | ARCH **L2 = hypothesis**; MP **Level 2 = documentation quality** | a correctness finding labelled "L2" reads as a hypothesis (ARCH) or as a documentation judgment (MP). Three meanings, one token | dissolve: documentation quality → MP Level 2 fields; demonstrable defects → Gap records; interpretation → `[E]` records | no |
| "L3" | hypotheses and research | ARCH **L3 = derivation**; MP **Level 3 = validation** | a Phase-1 hypothesis reads as a derivation or a validation | → `[E]` record (P1) plus Phase-2 L2 hypothesis | no |
| "Evidence Object" | v1.3 sealed L1 bundle `ev:F:L1:vk` | MP §6A `E-####`, one piece of evidence | two first-class objects share a name inside Phase 1 (the MP §4A collision rule applies) | rename the F object (e.g. "sealed extraction bundle") | no |
| **L1-ACCEPTED** (v1.3 T11) | one state making v_k consumable, with `acceptance_basis` | not in ARCH or MP | see below | **do not introduce as a state** | **yes — H-6** |

**L1-ACCEPTED collapse analysis:**

| Notion | Governing home | Inside L1-ACCEPTED? |
|---|---|---|
| mechanically sealed | F assurance (no MP field) | yes (`SEALED-ONLY`) |
| structurally valid | MP §9A `RECONSTRUCTION_RECORD_VALIDATED` L2077 | implied |
| integrity verified | MP §36A hashes | implied |
| source-supported | ARCH RA-15 **A** L398 | implied, not separated |
| reconstruction-valid | ARCH RA-15 **B** L399 (MP §9/§9A) | implied, not separated |
| independently audited | none (F assurance) | only when due |
| human accepted | GOV KOS-G-060 L464–481 | only when due |
| downstream-eligible (later Phase-1 files) | MP §0E.1–0E.2: consulting earlier records needs no acceptance | **required by L1-ACCEPTED — stricter than MP** |
| Phase-2 eligible | ARCH §4.1 RRP membership; ACL-1 L168 | conflated with the above |
| theory-consistent | ARCH RA-15 **C** (Phase 2) | no |
| scientifically validated | ARCH L4 / MP Level 3 (Phase 2) | no, but a consumer reading "ACCEPTED" can infer it |

**[D]** L1-ACCEPTED collapses at least seven of the eleven notions into one state, against ARCH RA-15 (L392–394: *"A single `status` field cannot carry these four"*). It also makes downstream Phase-1 consultation depend on acceptance, which MP does not require. **Disposition:** MP §5/§9A statuses + RA-15 A/B fields + a GOV acceptance record + RRP membership. **The decision is H-6.**

---

## E. Control-plane duplication audit (nothing deleted)

| Capability | F-Series | Existing mechanism | Classification |
|---|---|---|---|
| governance state | `F-DECISIONS.json`, `F-GOVERNANCE-LOG.md` | `governance-state.yaml` (human-only activation, L1–10) | **duplicate** |
| gate definitions | gate logic in `f_transition.py` / `f_checks.py` | `gates.yaml` (25 gates; none for per-file reading integrity) | **legitimate extension** (reading-integrity gates GOV lacks) + **duplicate** (approval gate) |
| gate runner | `f_transition.py`, `f_audit.py` | `gate-runner.py` (780 lines, self-test) | **implementation adapter** possible [O] |
| research state | `F-SERIES-STATE.jsonl`, `f_status.py` | STATE (RA-11) | **duplicate** |
| canonical registry | `F-MANIFEST.jsonl` + the 1206 list | `list_of_files_to_read.log` (L0-DEC-17, `L0-DECISION-RECORD-01.md` L281–284); `FILE-REGISTRY.jsonl` | **duplicate** for ordering and inventory; **not** for the 1,496 IDs (§F) |
| decision records | F-LOG entries | `L0-DECISION-RECORD-01.md` (`L0-DEC-nn`) | **duplicate** |
| human acceptance | `--human-ref` CONFIRMED | KOS-G-060 `acceptance-of-own-work` (HUMAN, active) | **duplicate** |
| execution position | `f_status.py` | STATE; RA-12 recompute | **duplicate** |
| artifact lifecycle | F states REGISTERED…AUDITED | MP §5 `read_status`/`dossier_status`; §9A gates | **duplicate** (transform) |
| GOV coverage of the F lane | — | `gates.yaml` L12 `scope: knowledgeos_theory_chronological_extraction`; KOS-G-001/003 scan "the workspace" (GOV README L198) | **missing capability**: GOV does not see the F lane at all [F]. Either F records move into the MP workspace or GOV scope changes — **H-10** |

---

## F. Identity / registry audit

| # | Finding | Class |
|---|---|---|
| I1 | The canonical registry `docs/knowledgeos/list_of_files_to_read.log` holds **F0001–F3081** (3,081 ids; last change `7698c99b4`) | [F] |
| I2 | Of the 1,523 F-Series rows, **1,496 carry canonical `file_id`s with byte-identical paths** (0 mismatches). **27 (F3082–F3108) are not in the canonical registry** | [F] computed this session |
| I3 | The MP lane's `FILE-REGISTRY.jsonl` covers F0001–F0040; its overlap with the F population is **0** | [F] |
| I4 | GOV KOS-G-003's known F-id set is the canonical list by L0 decision; any `F3082+` cited in a `.jsonl` in the MP workspace would be a **dangling reference** (BLOCK) | [F] `gates.yaml` L57–85; L0-DEC-17 |
| I5 | The F population is a **selection** (canonical rows absent from S `02-FILES.jsonl`, plus 27 new files) in a **different order** (F3082…F3108 first, then F0109…). MP: one registry, registry row order as the read order, no second inventory (L1101, L1159) | [F] |
| I6 | MP §4's bootstrap-once rule (L1224–1237) covers regenerating the registry. **Appending new files to it is not addressed** | [F]; [O] |

**Classification [D]:**
- The **1,496 IDs are canonical identities**, reused verbatim, which conforms to MP §4 ("import and preserve these IDs exactly").
- The **27 IDs are a second-registry extension**: minted in the canonical format, outside the canonical registry.
- The **1206 list is a second inventory and ordering**.
- Run ids (`FR-F####-NNN`) are **execution identifiers** and are unproblematic.

**This corrects the DD review (#10).** Its claim that "F-MANIFEST acts as a second registry" holds for ordering, selection and the 27 new IDs, not for the 1,496 IDs.

**Can F identity be retained as an execution-layer identifier without competing? [D]: yes.**
- The file identity *is* the canonical `file_id`; F-Series needs no identity scheme of its own.
- What must be resolved is (a) the 27 new files (H-3) and (b) the population and order (H-11).

---

## G. R-A feasibility

> ## **R-A: CONDITIONAL.**
>
> R-A can be implemented without changing the frozen architecture and with **zero mandatory amendments** to MP. It requires the governance decisions and F-side transformations below. One MP amendment is *optional*: making the independent audit or isolation binding. One MP amendment is a *candidate*: duplicate exemption.

| # | Condition | Kind | Evidence |
|---|---|---|---|
| G-1 | the human chooses R-A | human decision (H-1) | — |
| G-2 | **execution authorization.** STATE: *"RESEARCH STOPPED — no next target … F0041+ HOLD … No further implementation, correction, B-12 or F0041+ without explicit new authorization."* Executing MP on any canonical file above F0040 requires it. MP §49 item 5 (governance freeze) is also DECISION_REQUIRED | human decision via GOV (RA-16) | STATE L70–81; MP L4706 |
| G-3 | **population and order** reconciled with MP §3 (single inventory, registry read order) | human decision (H-11) | MP L1101, L1159 |
| G-4 | **the 27 new IDs:** extend the canonical registry, or exclude them | human decision (H-3) | §F |
| G-5 | **output location and GOV scope:** MP §41 fixes the reconstruction area; GOV sees only that workspace; the human's lane rule restricts F writes to the F lane | human decision (H-10) | MP L4284–4290; `gates.yaml` L12 |
| G-6 | F records re-expressed as MP records: FRR, `E-####`, D/P/A, `T-####`, `G-####`, verified edges, **TDI**, §3 date events, §9 source self-declared status, §26 track, §4A namespaces, §41 Tier-1 artifacts | protocol requirement (F side) | §A, §B |
| G-7 | the look-ahead rule | human decision (H-4) | MP §0E.1–0E.3 |
| G-8 | the S-identifier guard narrowed to allow `p3a_*` fields | protocol requirement (F side) | MP §1, §42 |
| G-9 | isolation limited to the Layer-1 extractor and the auditor | protocol requirement (F side) | MP L2006–2054, §0E.2 |
| G-10 | the independent audit and sealing as non-gating assurance; binding only via an MP amendment | optional amendment | §B #14–15 |
| G-11 | the duplicate pointer (RL-03): an MP exemption or a full read of every duplicate | human decision / candidate amendment | §B #42 |
| G-12 | terminology: F L1/L2/L3, "Evidence Object" and L1-ACCEPTED resolved | protocol requirement; H-6 | §D |
| G-13 | the Reference Architecture (MP §49 item 1 BLOCKING) — an MP-level blocker for Layer 2, not an F one. §0C L645–651 lets `HA-###` extraction proceed without it | [O]: status in the MP lane not inspected | MP L4702 |
| G-14 | the standing F3082 read evidence (orchestrator read, §H) | human decision | §H |
| G-15 | Phase-2 content (C11, `f_checkpoint.py`, confirmatory C09, pre-registration) handed to Step 2 | protocol requirement | §C |

**Why not NO [D].** No condition requires changing an ARCH element (contexts, layers, ACL, RA-1…RA-16). Every conflict found is resolved on the F side or by a human act, and MP's text does not have to change for R-A to conform.

**Why not YES [D].** G-2 alone blocks execution today, and G-3, G-4, G-5 and G-7 are human decisions the map may not take.

---

## 14. Retained vs transformed vs discarded — actual counts

**By artifact (78 inspected = 77 lane files + 1 external input):**

| Verdict | Count | Items (§A #) |
|---|---|---|
| KEEP (active, unchanged) | **9** | 14–15, 45, 46, 53–54, 65, 66, 67 |
| KEEP-H (historical record) | **22** | 1–4, 7, 10–12, 49–52, 55–64 |
| TRANSFORM | **32** | 5, 6, 9, 13, 16–23, 26–29, 36–39, 42, 43, 44, 68–75, 77 |
| SPLIT (Transform + Move to Phase 2) | **6** | 24–25, 30–33 |
| MOVE-P2 | **3** | 34–35, 76 |
| GOVERNANCE DECISION | **6** | 8, 40–41, 47, 48, 78 |
| DELETE | **0** | nothing needs deleting: conformance concerns what governs execution, not what exists as history |
| UNRESOLVED | **0** | — |
| **Total** | **78** | |

**Percentages (unlike metrics reported separately):**

| Metric | Value |
|---|---|
| Retained in an active role (KEEP + TRANSFORM + SPLIT) / all 78 | 47 / 78 = **60.3 %** |
| Same, over active-role artifacts only (78 − 22 KEEP-H = 56) | 47 / 56 = **83.9 %** |
| **Retained unchanged** (KEEP) / active-role artifacts | 9 / 56 = **16.1 %** |
| Moved to Phase 2 / active-role | 3 / 56 = 5.4 % |
| Pending a governance decision / active-role | 6 / 56 = 10.7 % |

**Executable code (`scripts/`, 2,243 lines, 12 files):**

| Verdict | Lines | Share | Files |
|---|---|---|---|
| KEEP | 331 | **14.8 %** | 3 |
| TRANSFORM | 1,772 | **79.0 %** | 8 |
| MOVE-P2 | 140 | **6.2 %** | 1 |

The tests (888 lines) follow the code.

**Mechanisms (§B, 43):**

| Outcome | Count | Share |
|---|---|---|
| conformant as-is | 16 | 37.2 % |
| assurance without amendment | 3 | 7.0 % |
| need an amendment only if binding | 4 | 9.3 % |
| transform | 8 | 18.6 % |
| missing and required | 6 | 14.0 % |
| Phase 2 | 2 | 4.7 % |
| governance | 4 | 9.3 % |

**Test of the "80–90 % survives" intuition [D].** It is **supported only in the weak sense**: 83.9 % of active-role artifacts survive *in some role*. It is **not supported in the strong sense**: only 16.1 % of active-role artifacts, and 14.8 % of executable lines, survive *unchanged*. Most of the work survives as mechanism, but needs re-expression in MP's record schema, vocabulary and authority. The six missing MP requirements (§B #25, 30, 31, 38, 40, 41) are **new work** that no retention figure covers.

---

## 15. Unmapped / No Existing Master-Protocol Home

| # | F capability | Why it has no home | Classification |
|---|---|---|---|
| U1 | per-page digests + verbatim quotes (RL-08) | MP requires reading, not proof of consumption | implementation detail (assurance) |
| U2 | independent L1 audit + recomputed comparison | MP has batch self-validation (§37) only | **candidate protocol amendment** (if binding), else assurance |
| U3 | evidence sealing / stage freezes | MP requires reproducibility, not a sealing mechanism | implementation detail |
| U4 | Git CAS commit protocol | MP leaves the store and writer open (§49 item 4) | implementation detail |
| U5 | isolation attestation (C15) | no isolation concept in MP or ARCH | assurance; **candidate amendment** only if binding. Must be limited (§B #17) |
| U6 | quarantine of drafts | MP treats pilot artifacts as disposable | implementation detail |
| U7 | checkpoints (N=50), populations, hypotheses registry | testing is out of Phase-1 scope | **Phase 2** |
| U8 | pre-registration, confirmatory tests, multiplicity, temporal switches | same | **Phase 2** |
| U9 | the research-discovery purpose ("potential theoretical contributions", "what could this imply for KnowledgeOS") | conflicts with the MP role binding L140–151 and the one-line test L151 | **unnecessary** (wording, not an artifact) |
| U10 | strict no-look-ahead | MP permits targeted forward and backward investigation | **governance matter** (H-4) |
| U11 | S-identifier guard in its current breadth | MP requires P3A cross-references | implementation detail; must be narrowed |
| U12 | hash-bound HumanDecision records, APPROVED-FOR-EXECUTION lines | GOV already holds activation and L0 decisions | **governance matter** (H-2) |
| U13 | the 1206 population list | MP forbids a second inventory | **governance matter** (H-3, H-11) |
| U14 | duplicate content pointer (RL-03) | MP has no duplicate exemption from complete reading | **candidate protocol amendment** or governance |
| U15 | `CONTENT-IDENTICAL-TO-S` marking (RL-04) | MP has P3A comparison, not content-identity marking | implementation detail (it can live as a `p3a_*`-adjacent field) — [O] |
| U16 | per-file `F-OBSERVATION-ON-INHERITED-METHOD` routing to the S-Series | no S-review channel in MP | governance matter; [O] |

No home was invented for any item to make R-A succeed.

---

## H. R14 deviation — verification

| # | Finding | Class |
|---|---|---|
| R1 | M35 R14: *"The orchestrator never reads a corpus file or a full ledger."* | [F] M35 L76 |
| R2 | The F-Series v1.0 inheritance matrix enumerates 30 rows (§F-3 L72–107). **R14 appears in none**: neither inherited, adapted nor declared not-applicable. Rows 2 and 22 refer to "one reader" and a "fresh agent" | [F] |
| R3 | F-Series v1.0 itself names the per-file procedure as the one *"the reading agent runs"* | [F] protocol v1.0 L8 |
| R4 | F-Series C15 (v1.2, written after the F3082 read) presupposes an orchestrator/agent separation (*"The orchestrator's prompt to the agent states this override"*) | [F] C15 §1 |
| R5 | F3082's read is recorded under run `FR-F3082-001` in the session's own completed-work log. The READ-LOG has **no reader-identity field** | [F] `F-SESSION-LOG.md` L34–39, L48; `ledger/F3082/READ-LOG.jsonl` |
| R6 | The read was executed by the orchestrating session itself (this Claude session). No agent was dispatched for it | [F] this session's own execution; not recorded in any lane artifact |
| R7 | No waiver, adaptation or human ruling mentioning R14 exists in the lane: 0 hits across protocols, contracts and logs. RL-10 approved "the six adapted §F-3 rules" only | [F] grep; `F-GOVERNANCE-LOG.md` L49 |
| R8 | MP has **no orchestrator rule** (0 occurrences of "orchestrator") | [F] |

**Derived analysis:**
- **[D]** It **happened** (R5, R6).
- **[D]** R14 is an M35 rule, and M35 does not govern Phase 1 (H-0). **Under MP it is not a violation** (R8).
- **[D]** It **is a deviation from F-Series' own claimed method**: F-Series claimed to inherit M35 methodology, silently omitted R14 from its enumerated inheritance, and described the reader as an agent (R2, R3).
- **[D]** It is **unwaived** (R7).
- **[D] Consequence:** page coverage and page hashes are mechanical and unaffected. But **extractor isolation cannot be claimed for run `FR-F3082-001`**, because the orchestrating context held architecture and theory documents. Any extraction that reuses this read run would inherit that.

**Whether it must be recorded:** no inspected rule *requires* a deviation entry. C14 §1 records decisions; C14 §3 routes findings on S methodology; a grep finds no deviation-recording rule. As instructed, **no governance record was written.**

**Where it would belong (for the human):**
- F-GOVERNANCE-LOG as an F-OBSERVATION, if F-Series stays as it is;
- or, under R-A, the GOV/STATE backlog, since that is where Phase-1 incidents are recorded (`L0-DECISION-RECORD-01.md` pattern).

---

## Executive Finding

### H-0
**CONFIRMED (a).** `docs/knowledgeos/knowledgeos_theory_chronological_extraction/prompts/knowledge_os_protocoll.md` governs Phase 1:
- ARCH L8, L461/L478 ↔ MP L468;
- MP L11–17;
- STATE L14;
- README-P L7;
- `gates.yaml` L49/67/98.

S-Series v3.5 is inherited methodology and historical input, whose output is the frozen P3A baseline. Six ambiguities (A1–A6) are recorded; none is a competing authority claim.

### P3A
The frozen, non-canonical output of the S-Series v3.5 pipeline through P3a (`125cfe837`/`c9e76918b`; YELLOW reliability). In MP it is **candidate evidence and a comparison baseline** ("consult, never adopt", §1; comparison layer §42). F-Series would run after it and alongside it, never on it. R-A leaves the P3A contract untouched. It does require narrowing F-Series' S-identifier guard so that the `p3a_*` fields MP demands can exist.

### R-A
**CONDITIONAL.** No change to the architecture is needed, and there are **zero mandatory MP amendments**. Fifteen conditions apply (G-1…G-15), of which seven are human decisions.

### Retention

| Measure | Value |
|---|---|
| Artifacts inspected (77 lane files + 1 external) | 78 |
| KEEP | 9 |
| KEEP-H | 22 |
| TRANSFORM | 32 |
| SPLIT | 6 |
| MOVE-P2 | 3 |
| GOVERNANCE | 6 |
| DELETE | 0 |
| UNRESOLVED | 0 |
| Active-role artifacts retained in some role | **83.9 %** |
| Active-role artifacts retained unchanged | **16.1 %** |
| Executable lines retained unchanged | **14.8 %** (79.0 % transform) |

### Critical blockers (evidence-backed only)
1. **Execution is on hold in the governing lane:** STATE L70–81 ("RESEARCH STOPPED … F0041+ HOLD … without explicit new authorization").
2. **27 IDs are outside the canonical registry.** They would fail KOS-G-003 in the MP workspace (L0-DEC-17).
3. **The F population is a second inventory in a different order** (MP L1101, L1159).
4. **The GOV control plane does not cover the F lane** (`gates.yaml` L12), while MP §41 fixes the reconstruction area elsewhere.
5. **F-Series lacks six MP-required mechanisms:** TDI (P1-Q1, with active gates), Theory Objects, §3 date events, source self-declared status, track, Phase-0 index.

### Required human decisions (genuinely open)

| Id | Decision | Minimum information |
|---|---|---|
| **H-1** | F-Series role: R-A / R-B / R-C | R-A is CONDITIONAL-feasible with 0 mandatory MP amendments (§G) |
| **H-2** | authority model: whether F decisions, approvals and acceptance move into GOV | GOV duplicates listed in §E; the research session may not write `governance-state.yaml` |
| **H-3** | the 27 new files F3082–F3108: extend the canonical registry, or exclude them | MP §4 does not address appending (I6); KOS-G-003 would block them |
| **H-4** | look-ahead: strict F rule vs MP §0E | the strict rule makes `UNDERIVED_BY_CORPUS` unreachable (§B #35) |
| **H-5…H-8** | DD-1…DD-4 as reframed in the DD review | unchanged. H-6 is sharpened by §D |
| **H-10** *(new)* | output location / GOV scope for MP records produced by F-Series | MP §41 vs the human lane rule vs GOV scope |
| **H-11** *(new)* | F population and order vs MP's single registry and read order | §F I5 |
| **H-12** *(new)* | the F3082 read evidence (orchestrator read, non-isolated): keep, or re-read by an isolated reader; and whether to record the R14 deviation, and where | §H |
| **H-13** *(new)* | duplicate handling (RL-03) under MP's complete-reading rule | §B #42 |
| carried | HDR-1 (isolation residual), HDR-6 (signed vs procedural decisions) | unchanged |

H-9 is deliberately unused, to avoid colliding with any earlier numbering.

### Recommended next step
The smallest safe action is a **human decision on H-1**, taken with this map. **If R-A is chosen,** decide G-2 (authorization under GOV) and H-3/H-11 (identity and population) before any v1.3-R design, because those three decide *what* a redesigned F-Series would execute on. Nothing else should proceed until then.

---

*Stop. No v1.3-R design, implementation, migration, protocol amendment or H-1 decision follows from this document.*
