# F-SERIES v1.3-R — MASTER-PROTOCOL-CONFORMANT DESIGN (FOR REVIEW)

**Status: DESIGN ONLY.** Nothing is implemented. F3082 is not run and no extraction is authorized. v1.2, the architecture and the Master Protocol are unchanged. No human decision is taken here.

**Commission:** "F-SERIES v1.3-R — MASTER-PROTOCOL-CONFORMANT DESIGN" (human, 2026-09-25).
**Predecessor:** `prompts/F-SERIES-v1.3-MASTER-PROTOCOL-REFACTOR-MAP.md` (commit `fce30354e`, "the Map").

> ⚠️ **Precondition stated, not assumed:** this design describes **R-A** (F-Series as an execution/assurance layer for the existing Phase-1 protocol). **H-1 — whether to choose R-A — is still open.** The design is the input to H-1, not the result of it.

**Tags, as in the Map:**
- **[F]** source fact, cited by file and line;
- **[D]** derived design reasoning;
- **[O]** open question.

**Abbreviations:**

| Short | Document |
|---|---|
| **ARCH** | `…/prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md` (v1.2 FROZEN) |
| **MP** | `…/prompts/knowledge_os_protocoll.md` |
| **S2** | `…/prompts/knowledge_os_step2_theory_construction_protocol.md` |
| **STATE** | `…/KNOWLEDGEOS-RESEARCH-STATE.md` |
| **GOV** | `…/governance/` |
| **EVB** | `…/evidence/` (the existing evidence-binding proposal) |

All paths are under `docs/knowledgeos/knowledgeos_theory_chronological_extraction/` unless stated.

---

## 1. Executive summary

**v1.3-R is an execution integrity and assurance layer for MP, and nothing more.**
- It owns **how** MP is executed: reading, measuring, hashing, sealing, committing, checking and auditing.
- MP owns **what** is recorded and what it means.
- v1.3-R has no records, statuses, levels, gates, registry, state authority or approval authority of its own. Wherever it needs a technical state or field, that state or field is named as execution metadata and kept outside every MP semantic field.

**Six findings shape the design.** Three are new since the Map.

1. **[F] The governing lane already contains an evidence-binding mechanism** (EVB): a canonical manifest, fail-closed identity admission and read receipts (`evidence/README.md` L37–47).
   - It is marked *"RESEARCH-PRODUCED PROPOSAL … NOT AUTHORITATIVE GOVERNANCE"* (L3).
   - The governance plan assigns evidence-binding implementation to the **governance session**, not to research. That separation question is **B-15, "RECORDED, NOT ADJUDICATED"** (`SESSION-RECORD-RCI.md` L75–97; STATE L76).
   - **[D]** v1.3-R *is* an evidence-binding and assurance mechanism, so **B-15 applies to v1.3-R itself.** Whether a research session may build it at all is a governance question. It is recorded below as a **new blocker, H-14**.
   - v1.3-R is designed to **consume and extend EVB, never duplicate it**.
2. **[F] The governing lane records per-file gates in its own format** (`reconstruction-records/F####.gates.json`, with gates such as `FILE_READ`, `IDENTITY_ADMITTED`, `SELF_DECLARED_STATUS_CAPTURED`…).
   - These differ in name from the MP §9A list (MP L2063–2074).
   - v1.3-R reports into that record rather than creating a gate vocabulary. Which list is authoritative is **[O] P-5**.
3. **The ORIGIN axis `[C]/[S]/[E]/[T]` is a Phase-2 axis** [F] (S2 §5C.2 L1560–1572).
   - Phase 1 has its own provenance vocabulary [F]: MP §6A's three provenance layers (L1517–1536), the `[E]` record (L147), and the six distinctions of role item 12 (L118–124).
   - **[D]** Stamping Phase-2 ORIGIN labels onto Phase-1 records would apply a Phase-2 category to Phase-1 artifacts, which ACL-2 (ARCH L169) and RA-8 (ARCH L299, own UL per context) forbid without a recorded translation.
   - **Design:** Phase-1 records carry MP's provenance-layer field. ORIGIN is assigned **by the handoff translation** (RA-3, ARCH L294) in a recorded mapping table (§18).
   - This deviates from the commission's literal wording ("use the governing origin model"). It is exposed here rather than hidden: the governing model **is** MP §6A in Phase 1, and ORIGIN at the boundary.
4. **Semantics stay with MP; v1.3-R measures them.**
   - The F-Series 26-category taxonomy, the L1/L2/L3 ladder and `L1-ACCEPTED` are **retired as semantic constructs**.
   - The unit frame survives as a **measurement frame**: units are covered by MP records or by a disposition, never classified by F.
5. **Execution is blocked on governance, not on design.** Blocking today:
   - STATE L70–81 (*"RESEARCH STOPPED … F0041+ HOLD … without explicit new authorization"*);
   - MP §49 item 5 (governance freeze, DECISION_REQUIRED, L4706);
   - B-15 / H-14;
   - H-3, H-10 and H-11.
6. **Zero mandatory MP amendments: re-verified.** The design needs none. It exposes **ten protocol questions** where MP is silent or insufficient (§23). Three of those are candidate amendments; none is required for the design to conform.

---

## 2. Governing-source declaration

| Rank | Source | Role in v1.3-R | Read (this conversation) |
|---|---|---|---|
| 1 | ARCH v1.2 | boundaries, invariants RA-1…RA-16, ACL-1…4, RRP | L1–492 complete |
| 2 | MP | **all Phase-1 semantics**; the execution model (§4) | L1–4721 complete |
| 3 | GOV · STATE · `RECONSTRUCTION-STATE.json` · EVB | authority, execution position, evidence binding | GOV README L1–80, `gates.yaml` (all gate ids; L1–200, L460–500), `governance-state.yaml` L1–66, `L0-DECISION-RECORD-01.md` L1–40 and L281–284, STATE L1–103, EVB README L1–105, `SESSION-RECORD-RCI.md` L75–97, `F0001.gates.json` L1–40, `RECONSTRUCTION-STATE.json` keys |
| 4 | P3A (`chronological-read/`) | evidence and comparison source only | `P3A-FROZEN-BASELINE.md` L1–60 |
| 5 | F-Series v1.2/v1.3 | a mechanism source only | contracts C01–C15 complete; the Map |
| 6 | S-Series v3.5 | methodology, only where compatible | L1–515 complete |

**Rule [D]:** no lower rank may override a higher one. Where rank 5 conflicts with rank 2, rank 5 changes. Where rank 2 appears insufficient, the insufficiency is recorded as a protocol question (§23), **never fixed inside F**.

---

## 3. Target architecture

```text
Frozen Research Architecture (ARCH v1.2)
          │  referenced, never restated (ARCH L8, §9)
          ▼
GOVERNING MASTER PROTOCOL (MP) ─── owns ALL Phase-1 semantics
          │
          ▼
PHASE 1 · Historical Reconstruction & Evidence  (MP L17–80)
          ├── 1A Evidence Reconstruction   → MP §9 records
          └── 1B Theory Discovery Index    → THEORY-DISCOVERY-INDEX.jsonl (P1-Q1)
          │
          ▼
RESEARCH RECONSTRUCTION PACKAGE  (ARCH §4.1; MP L62–66) ── translated at the ACL (RA-3)
          │
          ▼
PHASE 2 · Theory Recovery, Construction & Validation (S2)
```

v1.3-R sits **beside** the MP pipeline, never inside its semantics:

```text
        CONTROL PLANE (GOV) ── read before every unit (RA-16) ── authority only
                │
                ▼
      ┌──────────────── F-SERIES v1.3-R · EXECUTION / ASSURANCE LAYER ─────────────────┐
      │  controlled execution     integrity / sealing        audit / assurance         │
      │  • admit (EVB)            • content/schema/protocol  • mechanical checks       │
      │  • paged reader           •   hashes (MP §36A)       • independent L1 audit    │
      │  • unit frame             • seals · Git-CAS          • discrepancy records     │
      │  • consultation log       • crash-safe unit state    • quarantine              │
      └──────────────────────────────────┬─────────────────────────────────────────────┘
                                         │ writes only EXECUTION METADATA;
                                         │ MP records are authored by the extractor
                                         ▼
                             MASTER PROTOCOL RECORDS
     (FILE-RECONSTRUCTION, EVIDENCE, THEORY-OBJECTS, GAPS, VERIFIED-EDGES, TDI, …;
      per-file gate record; RECONSTRUCTION-STATE.json)
```

**[D] No second semantic architecture exists.** The layer's three boxes are execution capabilities, not bounded contexts (§11).

---

## 4. Master Protocol execution model (reconstructed from MP, with the lines used)

| Step | MP rule | Lines |
|---|---|---|
| E0 | Read ARCH first; Phase-1 scope binding; one-line test *"what the corpus developed, or what the researcher thinks should follow?"* | L11–17, L140–151 |
| E1 | Registry = `list_of_files_to_read.log`; one row per file; `file_id` = column 1; **registry row order = read order**; **no second competing inventory** | L1081–1101, L1159 |
| E2 | Registry normalization: `registry_timestamp_semantics`; typed `date_events[]` → derived `historical_sequence_date` (only `applies_to: THIS_FILE`; `UNCERTAIN` over arbitrary) | L1091–1158 |
| E3 | Identity: import `file_id` verbatim; no second ID scheme; bootstrap once; hash and freeze | L1192–1240 |
| E4 | Namespaces `F E AR T TH RO DI CT D P A S R G C B M I`; zero-padding; corpus identifiers quoted, never minted | L1243–1290 |
| E5 | Registry fields `read_status`, `dossier_status`, `relationship_status`, `continuity_status`, `gap_status` | L1294–1314 |
| E6 | Append-only versioning (`record_version`, `previous_record_hash`, `new_record_hash`, `changed_by_file`, `change_reason`, `change_evidence`, `changed_at`); three revision cases; `intra_file_revisions[]` | L1318–1379 |
| E7 | P3A: **consult, never adopt**; `p3a_*` cross-references; comparison layer | L1001–1041, L4409–4437 |
| E8 | Atomic unit = **complete file** | L1045–1071 |
| E9 | Per file: five layers in order, 30 steps as a completeness check; the File Reconstruction Record schema; `source_self_declared_status`; "empty is valid"; **stateful** (*"Never start from zero"*) | L1746–2054 |
| E10 | Per-file gates `FILE_READ … RECONSTRUCTION_RECORD_VALIDATED`; `UNDERSTANDING_UNCERTAIN`; `dossier_status: COMPLETE` only when every gate is satisfied | L2058–2083 |
| E11 | Evidence Objects `E-####`; three provenance layers; negative evidence; multi-dimensional confidence | L1476–1558 |
| E12 | Candidate → verified edge pipeline; `VERIFIED` = evidence-verified, never validated | L1562–1705 |
| E13 | Phase 0 corpus index (Layer 1 only, never an exclusion) | L1708–1742 |
| E14 | Behavior loop: targeted backward/forward investigation permitted; chronology ≠ consultation boundary; Research Obligations; three assessment levels | L741–998 |
| E15 | Gap records and taxonomy; the **demonstrability gate** on `ALGEBRAIC/LOGICAL/STATISTICAL_METHOD_INCONSISTENCY`, `MATHEMATICAL_CONDITION_MISSING` | L2274–2485 (gate at L2469) |
| E16 | Definitions (§17), claims (§18), assumptions (§19), Theory Objects (§19A, statuses limited to `CANDIDATE`/`RECONSTRUCTED` now), threads (§19B), candidate theory (§19C) | L2628–3084 |
| E17 | Scope (§20), type (§21), contradictions (§22), branches and merges (§23–24), independence (§25), Track A/B (§26), ML policy (§27), no silent repair (§28–29) | L3088–3501 |
| E18 | Revisiting (§33); quality states (§35) | L3805–3899 |
| E19 | Reproducibility; `RECONSTRUCTION-STATE.json`; crash safety; corpus/registry/protocol/schema hashes | L3936–4026 |
| E20 | Batch validation (14 checks) and completion (`BATCH_/CORPUS_PROCESSING_COMPLETE`; no `THEORY_COMPLETE`) | L4030–4074 |
| E21 | Output area = **this lane**; Tier 1/2/3 artifacts; absence reasons; Tier 3 never hand-authored | L4239–4405 |
| E22 | Gate 0 pilot artifacts disposable; Gates 1–7 | L4552–4598 |
| E23 | Open blockers: Reference Architecture (**BLOCKING**), schemas, chronology, Track registers, namespaces, P3A dates, hashing **unimplemented** | L4696–4717 |

**[D] Where v1.3-R attaches:**
- E1–E4: identity and admission;
- E8–E10: reading, coverage and gate evidence;
- E6 and E18: version lineage;
- E19–E20: hashing, state and validation checks;
- E22: quarantine;
- E23 item 13: it implements the missing hashing.

It attaches **nowhere** in E9 Layers 2–5 semantics, E11 semantics, E12 verification judgment, E15 classification, or E16–E17.

---

## 5. F-Series responsibility boundary

| MP owns (semantics) — v1.3-R never defines these | v1.3-R may own (execution assurance) |
|---|---|
| record meaning, schemas, epistemic categories | controlled execution order of an execution unit |
| `file_id` identity, namespaces | the reader implementation and page model |
| contribution, definition, claim, gap and edge semantics | the deterministic unit frame (a measurement, not a classification) |
| chronology (`date_events`, `historical_sequence_date`) | coverage measurement against the frame |
| relationships and their verification | quote-verbatim verification (a byte check) |
| type compatibility, math/logic finding classes | hashing of content, schema and protocol (MP §36A) |
| status fields (§5, §9A, §19A, §35) | sealing (immutability of an execution output) |
| Evidence Object semantics (§6A) | Git-CAS commit and crash-safe unit state |
| TDI semantics (P1-Q1) | mechanical checking of MP rules that are machine-checkable |
| Theory Objects, threads, candidate theory | independent-audit *execution* and discrepancy recording |
| Phase-1/Phase-2 boundary | quarantine of provisional output |
| the governance meaning of any act | producing **evidence** for GOV decisions — never authority |

**Rule [D]:** when v1.3-R needs a state or field MP lacks, it is prefixed `exec_` or held in an `execution` block. It is explicitly marked `EXECUTION-METADATA`, and **no MP-schema field may be derived from it silently** (invariant R1).

---

## 6. Record mapping

`NO-HOME` means there is no legitimate MP home and none is invented.

| Current F-Series record | Target v1.3-R representation | MP artifact / field | Notes |
|---|---|---|---|
| **F-ID** (`F####`) | **the canonical `file_id`**, consumed verbatim | registry column 1 (L1194–1203); `FILE-REGISTRY.jsonl` | 1,496 of 1,523 already canonical (Map §F). **F3082–F3108: GOVERNANCE-BLOCKED (H-3)** |
| run id `FR-F####-NNN` | kept as an **execution identifier** `exec_run_id` | none (execution metadata) | never in an MP `id` field |
| **F-MANIFEST** | **no F manifest.** Consume EVB `CORPUS-MANIFEST.jsonl` + `MANIFEST-HASH.txt` (built from the canonical log); an F-side page model is kept as an execution cache | MP §36A `corpus_manifest_hash` (L4011); EVB L35–36 | the F manifest becomes KEEP-H. Extension of EVB is subject to H-14 |
| **F population list** (`20260925_1206_…log`) | **not used as an inventory.** Execution windows are selected *from* the canonical registry in registry order | MP L1101, L1159 | KEEP-H; H-11 |
| **F-SERIES-STATE** | a **NON-AUTHORITATIVE DERIVED EXECUTION CACHE** (hash-chained), reconciled against STATE + `RECONSTRUCTION-STATE.json` (§15) | MP §36A (L3949–4026); ARCH RA-11/12 | the legacy 4,556 lines become KEEP-H |
| **F-READ-INTEGRITY** | an execution evidence record `exec_read_integrity`, **referenced by hash** from the `FILE_READ` gate entry | per-file gate record `FILE_READ`; EVB `READ-RECEIPTS.jsonl` | the receipt is EVB's; page coverage is the F extension |
| **F-DECISIONS** | none. Decisions live in GOV / L0 decision records | `L0-DECISION-RECORD-01.md`; `governance-state.yaml` | HDR-2 → H-4; HDR-3 → Phase 2 |
| **F-GOVERNANCE-LOG** | KEEP-H for history. Future authority records go to GOV; F records only `exec_` events | GOV | H-2 |
| **F "EvidenceObject"** (v1.3 sealed L1 bundle) | renamed **`exec_seal`**: a sealed execution output bundle. **Not** an MP Evidence Object | — (execution) | the MP `E-####` (§6A) stays the only "Evidence Object" (collision removed) |
| **F inventory items** (`FCI-…`, 26 categories) | **retired as a classification.** Replaced by *unit coverage*: each unit maps to ≥ 1 MP record id **or** to a disposition | FRR fields `definitions[] … conclusions[]`, `evidence[]` (L1943–1973); `E-####` | the categories were an F epistemic taxonomy (§7-B) |
| **F contributions** (XC schema) | **retired.** Content goes into FRR fields and `E-####` | FRR (L1924–1977) | XC schema = KEEP-H |
| **F analysis records** (`FAN-…`, L2) | split by kind (§9): source-demonstrable → **Gap/Contradiction** records or FRR `reasoning_documentation_quality[]`; interpretation/proposal → **`[E]` record** (nine fields) or a researcher observation | §13–§14, §22, §0E.4 L894–934, L146–147 | "L2" retired |
| **F research records** (`FRS-…`, L3) | the hypothesis *record* → **`[E]` record**, status `HYPOTHESIS`/`NOT_YET_ASSESSED`; test design → Phase 2 | L147 | "L3" retired |
| **F relationship candidates** (`FXC-…`) | **candidate edges** → verified edges | `CANDIDATE-EDGES.jsonl`, `VERIFIED-EDGES.jsonl` (§7–§8) | Phase 1A, not "analysis" |
| **F audit records** (`AUDIT.json`, `INDEPENDENT-AUDIT-L1.json`) | **execution/assurance evidence** `exec_audit`, referenced from the gate record; **advisory** (§16) | none (MP §37 is batch self-validation) | making them gating = candidate amendment P-10 |
| **F status** (REGISTERED…AUDITED) | **retired as authority.** MP `read_status`/`dossier_status` + gate record; F keeps `exec_unit_state` ∈ {STARTED, IN_PROGRESS, COMPLETED, FAILED} **aligned with MP §36A** | L1307–1308; L3997–4004 | no F lifecycle |
| **F gates** (READ-COMPLETE, CONTENT-EXTRACTED, …) | each becomes a **mechanical check producing evidence** for an MP gate entry | per-file gate record | no F gate names |
| **F page digests** (RL-08) | `exec_page_digest`, supplementary | none | **NO-HOME** as semantics; kept as assurance |
| **F consumption records** (v1.3) | `exec_consultation` log (§12) | MP `previous_related_files[]`, `resolution_source` | assurance only |
| **F checkpoints** (CP, N = 50) | removed from Phase 1 | — | MOVE-P2 |
| **F `CONTENT-IDENTICAL-TO-S` marker** | kept as a `p3a_*`-adjacent cross-reference, P3A comparison only | §42 | [O] P-3 area |
| **F duplicate pointer** (RL-03) | **NO-HOME.** MP requires complete reading of every file | §2 | H-13; candidate amendment P-3 |

---

## 7. Mechanism mapping

Every mechanism below is stated as: invariant · input · output · authority · failure condition · whether mechanical enforcement is possible · MP record affected · audit evidence · whether a human is needed.

### A — Controlled reader

| | |
|---|---|
| **Invariant** | a unit's extraction may cite only text the reader delivered for this `file_id` at the admitted sha256; complete coverage of pages 1…N is a precondition for `FILE_READ` |
| **Input** | canonical `file_id`; EVB `admit --resolve` = ADMIT |
| **Output** | EVB receipt (`admit --receipt`); `exec_read_integrity {run, pages, page_sha256[], content_sha256, coverage}` |
| **Authority** | none. It measures |
| **Failure** | STOP on non-admission, identity drift, or a missing page; `FILE_READ` stays unsatisfied (a partial read is recorded, never claimed whole) |
| **Mechanical?** | yes, for delivery and coverage. **Not for consumption** (a residual) |
| **MP record** | gate `FILE_READ` (L2063); FRR `path`/`file_id` |
| **Audit evidence** | receipt + page hashes recomputed |
| **Human?** | no. But whether this extends EVB is **H-14** |

Also: no receipts are backfilled for earlier reads (EVB L87).

### B — Deterministic unit frame

| | |
|---|---|
| **Invariant** | segmentation is a pure function of the bytes (non-blank line; a fenced block or math block = one unit). The frame **classifies nothing**; unit "kinds" are syntactic only (HEADING, LINE, LIST-ITEM, TABLE-ROW, QUOTE, MATH, CODE, MARKUP) |
| **Input** | the admitted bytes |
| **Output** | `exec_units` with a hash of the frame |
| **Authority** | none |
| **Failure** | a frame hash that does not match its recomputation |
| **Mechanical?** | yes |
| **MP record** | none directly; it is the denominator for C |
| **Audit evidence** | a recomputed frame hash |
| **Human?** | no |

### C — Inventory floor (coverage)

| | |
|---|---|
| **Invariant** | every non-MARKUP unit is referenced by ≥ 1 MP record (FRR field entry, `E-####`, Gap, Contradiction, TDI `quoted_signal`, `intra_file_revisions[]`, `source_self_declared_status`, `date_events[]`) **or** by a disposition ∈ {`RESTATES:<unit>`, `NO-SUBSTANTIVE-CONTENT:<reason>`} |
| **Implementation constraint (stricter than MP, labelled as such)** | MATH/CODE units cannot take `NO-SUBSTANTIVE-CONTENT` |
| **Input** | the frame + MP records |
| **Output** | `exec_coverage` report |
| **Authority** | none |
| **Failure** | an uncovered unit ⇒ the check fails ⇒ the `EXTRACTION_COMPLETE` evidence is missing |
| **Mechanical?** | yes |
| **MP record** | gate `EXTRACTION_COMPLETE` (L2064). It operationalises "nothing is silently omitted" (L1841) |
| **Audit evidence** | the coverage report; the auditor reviews every `NO-SUBSTANTIVE-CONTENT` |
| **Human?** | no |

**Coverage ≠ correctness [D].** A unit covered by a wrong record passes C. That is what G is for.

### D — Hash integrity

| | |
|---|---|
| **Invariant** | every checkpoint and every seal binds `corpus_manifest_hash`, `registry_hash`, `protocol_hash`, `schema_version` (MP L4011–4014), plus the file's content sha256 |
| **Input** | the EVB manifest hash; the sha256 of MP/ARCH; schema version ids |
| **Output** | the hash block in `RECONSTRUCTION-STATE.json` `last_checkpoint`/`checkpoint_hash` |
| **Authority** | none |
| **Failure** | a mismatch ⇒ STOP (L4026 "a hash mismatch surfaces it immediately") |
| **Mechanical?** | yes. It **closes MP §49 item 13** (L4717) |
| **MP record** | §36A |
| **Audit evidence** | recomputation |
| **Human?** | no |

### E — Evidence sealing (`exec_seal`)

| | |
|---|---|
| **Invariant** | a seal proves that **these bytes existed unchanged since time t**. **Sealed ≠ structurally valid ≠ accepted.** A seal carries no MP status |
| **Input** | the artifact set of one execution step |
| **Output** | `exec_seal {artifacts: {path: sha256}, seal_hash, run, utc}` |
| **Authority** | none |
| **Failure** | any byte change to a sealed artifact ⇒ integrity failure, reported |
| **Mechanical?** | yes |
| **MP record** | none; referenced from the gate record's evidence field |
| **Audit evidence** | re-hash |
| **Human?** | no |

### F — Git-CAS

| | |
|---|---|
| **Invariant** | a unit's outputs land atomically (`update-ref <branch> <new> <old>` with a temporary index) or not at all; the commit scope is limited to the paths the unit owns |
| **Input** | the unit's output paths |
| **Output** | a commit id recorded in `exec_unit_state` |
| **Authority** | none |
| **Failure** | ref moved (a concurrent session) ⇒ retry or STOP, never merge silently; out-of-scope staged paths ⇒ STOP |
| **Mechanical?** | yes |
| **MP record** | §36A crash safety (L3992–4004) |
| **Audit evidence** | commit ids |
| **Human?** | **yes, for the scope:** which paths the layer may commit (H-10). **[O] I-3:** a shared branch with other sessions |

### G — Independent audit (see §16)

| | |
|---|---|
| **Invariant** | the auditor's records are produced without access to the extractor's records, and the comparison is **computed**, never asserted. **Audit evidence ≠ truth** |
| **Input** | the file via A, the frame B, the MP schema |
| **Output** | `exec_audit` + discrepancies |
| **Authority** | none (advisory) |
| **Failure** | a discrepancy ⇒ recorded, and the file's gate evidence is flagged |
| **Mechanical?** | only the comparison is mechanical. **Independence is procedural** |
| **MP record** | none; it is evidence for `RECONSTRUCTION_RECORD_VALIDATED`'s review, not the gate itself |
| **Audit evidence** | the audit artifacts + the recomputed comparison |
| **Human?** | **yes, for acceptance:** acceptance is KOS-G-060, a human act. Gating = P-10 |

### H — Quarantine

| | |
|---|---|
| **Invariant** | provisional output (drafts, pilot artifacts, runs that failed gates, runs affected by a discovered defect) lives only under `quarantine/` and **is never referenced** by an MP record |
| **Input** | a failed or aborted unit |
| **Output** | a quarantine entry {reason, run, hashes} |
| **Authority** | none |
| **Failure** | an MP record citing a quarantined path ⇒ check fails |
| **Mechanical?** | yes (a path scan) |
| **MP record** | §45 Gate 0: pilot artifacts disposable (L4564) |
| **Audit evidence** | the scan result |
| **Human?** | release from quarantine is a human act |

### I — Isolation (limited)

| | |
|---|---|
| **Invariant** | isolation applies **only** to (a) the Layer-1 extraction step and (b) the independent auditor |
| **Layer-1 extractor** | may not read earlier reconstruction records, P3A, theory documents or other files. MP Layer 1 asks only *what is present in this file* (L1849) |
| **Layers 2–5** | **must** read the reconstruction state, active threads, P3A metadata and the Reference Architecture (L1030–1041, L1850–1853, L2006–2054). Isolation there would contradict MP |
| **Output** | attestation {received, read_paths, denied_material_consulted} |
| **Authority** | none |
| **Failure** | a path outside the allow-list; denied material consulted |
| **Mechanical?** | partly (the reader allow-list, the write guard). **Not preventable:** general-purpose tool access, injected project instructions, model priors (C15 §6) |
| **MP record** | none |
| **Audit evidence** | the attestation + detection by G |
| **Human?** | accepting the residual = HDR-1 |

### J — Version lineage

| | |
|---|---|
| **Invariant** | a revision is a new record version (`record_version`, `previous_record_hash`, `new_record_hash`, `changed_by_file`, `change_reason`, `change_evidence`, `changed_at`). The prior version stays byte-identical; its seals and audits stay attached to it |
| **Input** | a correction request or a backward discovery |
| **Output** | a new version + an `exec_seal` for it |
| **Authority** | per GOV (H-5, H-2) |
| **Failure** | an in-place edit ⇒ a seal break ⇒ STOP |
| **Mechanical?** | yes, for immutability |
| **MP record** | §5A (L1318–1379) |
| **Audit evidence** | the chain of hashes |
| **Human?** | per H-5 |

---

## 8. Epistemic model

### 8.1 No F ladder

The words "L1/L2/L3" are **retired**. The only ladder is ARCH/S2's **L0–L5** (S2 L391–396; ARCH L373), and v1.3-R assigns no ladder level at all.

Where F needs a technical **processing phase** name, it uses:

| Execution phase | Meaning | MP counterpart |
|---|---|---|
| `EXTRACTION` | producing Layer-1 content from one file | MP §9 Layer 1 |
| `RECONSTRUCTION` | Layers 2–5 records | MP §9 Layers 2–5 |
| `ANALYSIS-OBSERVATION` | producing researcher observations and `[E]` records | MP L146–147 |
| `AUDIT` | the independent audit | — |

These are **execution phases, not epistemic levels**.

### 8.2 Separated statuses — no `L1-ACCEPTED`

`L1-ACCEPTED` does not exist in v1.3-R. The eleven notions are separate fields or events:

| # | Notion | Carrier | Set by |
|---|---|---|---|
| 1 | integrity | `exec_seal` valid · hash block (§7-D, §7-E) | mechanism |
| 2 | structural validity | gate `RECONSTRUCTION_RECORD_VALIDATED` (MP L2077) | extractor + mechanical check |
| 3 | reconstruction status | `dossier_status` (L1308), each §9A gate, `UNDERSTANDING_UNCERTAIN` | extractor |
| 4 | source support | RA-15 **A** `SOURCE-SUPPORTED`: verbatim quote verified against the bytes | mechanism (byte check) |
| 5 | independent audit | `exec_audit.verdict` ∈ {`NO-DISCREPANCY`, `DISCREPANCY`} + discrepancies | auditor + computation |
| 6 | human governance | a GOV record (KOS-G-060 acceptance; L0 decision) | **human only** |
| 7 | downstream (Phase-1) eligibility | **no field**: MP lets later files consult earlier records (L813–851) without acceptance | — |
| 8 | RRP membership | the handoff manifest (§18) | handoff translation |
| 9 | Phase-2 eligibility | **not decided by Phase 1** (ACL-1, ARCH L168) | Phase 2 |
| 10 | theory consistency | RA-15 **C** | Phase 2 |
| 11 | scientific validation | L4 / MP Level 3; `mathematical_validation_status` stays `NOT_YET_ASSESSED` (L403–410) | Phase 2 |

RA-15 **B** (`RECONSTRUCTION-VALID`) is established by MP §9/§9A. It is represented by notions 2 + 3 + the review, never by 1 or 5 alone.

### 8.3 Source vs extractor vs researcher vs experiment

| Voice | Phase-1 carrier (MP) | ORIGIN at the handoff (§18) |
|---|---|---|
| **Source says** | FRR content fields; `E-####` with `excerpt_or_reference`; `source_self_declared_status`; provenance layer **SOURCE PROVENANCE** (L1522) | `[C]` |
| **Extractor observes** | `interpretation_basis` / `interpretation_method` / `interpreter` / `interpretation_confidence` (L1531–1536); provenance layer **RECONSTRUCTION INTERPRETATION**; documentation-quality dimensions (L894–934); Gap records | `[C]` only for the cited content; the interpretation is carried as **reconstruction metadata**, never as `[C]` content. `[S]` where it synthesizes across files (Theory Objects, threads) |
| **Researcher proposes** | `[E]` record, nine fields, status `HYPOTHESIS`/`NOT_YET_ASSESSED` (L105–114, L147); "researcher observation or later-validation candidate" (L146, L978) | `[E]` |
| **Experiment demonstrates** | **does not occur in Phase 1.** Only `VALIDATION_DOCUMENTED_IN_CORPUS` when the *source* documents a result (L399) | `[T]` only arises in Phase 2 |

**Execution metadata** (`exec_*`) carries **no voice**. It is never serialized inside a content field. It is excluded from the handoff's content set and travels only as a provenance attachment (R4, T-EP-4).

---

## 9. Mathematical / logical boundary

**Decision test [F → D]:**

> *"Can this finding be demonstrated directly from the source and its own stated rules, definitions, equations or conditions, without importing an external theory?"*

This operationalises MP L2469.
- **Yes** → Phase-1 record.
- **No** → `POTENTIAL_VALIDATION_ISSUE` (L2465–2467), an `[E]` record, or Phase 2.

| Finding | Phase 1 (record) | Phase 2 |
|---|---|---|
| source-stated definitions, axioms, invariants, theorems | FRR `definitions[]`, `mathematical_objects[]`; §18 `claim_type` from what is demonstrated, never from the label (L2750); §21 non-collapse candidate invariants | — |
| undefined symbol / term | Gap `UNDEFINED_SYMBOL` / `MISSING_DEFINITION` | — |
| missing premise / assumption | Gap `MISSING_PREMISE` / `MISSING_ASSUMPTION` | — |
| type shift | Gap `TYPE_MISMATCH` (§21) | — |
| algebraic step that does not follow from the preceding line; inference that fails under standard logic | `ALGEBRAIC_INCONSISTENCY` / `LOGICAL_INCONSISTENCY` **only if demonstrable** (L2469) | — |
| suspected but not source-demonstrable | `POTENTIAL_VALIDATION_ISSUE` | independent validation |
| the source's own contradictions | §22 (source-framed); an intra-file revision §5A case 3 | — |
| documentation quality | `reasoning_documentation_quality[]` (L1962); never a truth score (L934) | — |
| missing derivation | `UNDERIVED` + Gap; `mathematical_validation_status: NOT_YET_ASSESSED` (L962–976) | proving it correct or incorrect |
| a better formulation seen | a researcher observation / `[E]` record (L146, L978) | constructing it (2B) |
| proof ≠ assertion | a source's "proves / PASS / validated" → `SOURCE_ASSERTED_CONCLUSION` (L3483–3487) | validation |
| theory comparison, model selection, external theory | `[E]` record at most | 2A/2B/2C |

The F C06 `CORRECTNESS-FINDING` kind is **retired**. Its content lands in one of the rows above.

---

## 10. Statistical / ML boundary

```text
measurement ──► exploratory signal ──► hypothesis ─╳─► preregistration ──► confirmatory test ──► validation
└──────────── PHASE 1 (MP L148; §27; §40) ────────┘ ╳ └───────────────── PHASE 2 (2C) ─────────────────┘
                                         ([E] record only)
```

| Stage | Phase-1 form | Guard |
|---|---|---|
| **measurement** | process indicators (MP §40 L4219–4235): coverage, counts, gate pass rates, extraction-quality statistics, audit discrepancy rates | *"measure process completeness, never theory quality"* (L4235) |
| **exploratory signal** | candidate-edge discovery, similarity, clustering, duplicate and anomaly detection (§27 L3402–3409) labelled `exec_signal {method, inputs, parameters, limits}` | ML may not establish a relationship, continuity, validity, identity or gap resolution (L3411–3417). Workflow: ML candidate → complete-file reading → evidence → verification (L3421–3429) |
| **hypothesis** | an `[E]` record at most, status `HYPOTHESIS` | no test is attached in Phase 1 |
| **preregistration → confirmatory test → validation** | **none** | MOVE-P2 (C11, confirmatory C09, `f_checkpoint.py`, HDR-3) |

**Forbidden in Phase 1 output:** p-values, significance, confidence intervals *as claims about the theory*, causal language, "confirmed", "validated". Descriptive intervals over **process** metrics (e.g. an audit discrepancy rate) are allowed if labelled as process measurement (T-ST-1…4).

---

## 11. DDD boundary

| Object | Domain object? | Research artifact? | Execution artifact? | Governance artifact? | Infrastructure? | Serialization only? |
|---|---|---|---|---|---|---|
| File, Evidence (`E-####`), Theory Object, Gap, Research Obligation | ✅ MP first-class (L4250–4258) | — | — | — | — | — |
| Definition / Assumption / Proposition | ✅ MP child entities under a Theory Object (L2887–2901) | — | — | — | — | — |
| Theory Thread | MP: first-class, **deliberately not** an aggregate root (L4260) | — | — | — | — | — |
| TDI entry | — | ✅ MP Phase-1B artifact | — | — | — | — |
| Candidate/verified edge, continuity | — | ✅ MP analytics / read models (L4262–4282) | — | — | — | — |
| `[E]` record | — | ✅ | — | — | — | — |
| HA-### (Emergent Historical Architecture) | — | ✅ **discovered in the corpus** (§0C) | — | — | — | — |
| AR-### (Reference Architecture) | — | ✅ a **hypothesis about KnowledgeOS**, frozen for consistency, *"not historical truth"* (L592–601) | — | — | — | — |
| researcher's proposed KnowledgeOS model | — | ✅ `[E]` / Phase 2 only | — | — | — | — |
| `exec_seal`, `exec_audit`, `exec_units`, `exec_coverage`, `exec_run_id`, `exec_consultation` | ⛔ | ⛔ | ✅ | — | — | — |
| `exec_unit_state` cache | ⛔ | ⛔ | ✅ | — | ✅ | — |
| GOV activation, L0 decision, KOS-G-060 acceptance | ⛔ | — | — | ✅ | — | — |
| JSONL files, the Git commit, the manifest file | ⛔ | — | — | — | ✅ | ✅ |

**[D] v1.3-R names no Aggregate, Entity, Value Object or Bounded Context.** The v1.3 design's "EvidenceObject aggregate root", "AuditRecord entity" and "Consumption value object" (v1.3 design L82–86) are **withdrawn as DDD claims**. They become execution artifacts.

**Three architectures, never merged:**
1. the **historical architecture discovered** in the corpus (HA);
2. the **researcher's proposed** architecture (`[E]` / Phase 2; AR is only a frozen comparison hypothesis);
3. **F-Series' execution architecture** (§3), which is infrastructure and never enters (1) or (2).

---

## 12. Chronology and look-ahead model

**Six concepts, never conflated:**

| Concept | Definition | Source | Owner |
|---|---|---|---|
| **registry order** | the row order of `list_of_files_to_read.log` | MP L1081–1089 | MP / canonical log |
| **read order** | the order files are processed = registry row order (it guarantees completeness) | MP L1101 | MP |
| **historical_sequence_date** | derived from `date_events[]` with `applies_to: THIS_FILE`; `UNCERTAIN` allowed | MP L1141–1158 | extractor per MP |
| **historical sequence** | the order used for §0A / §0E.2 "preceding file" / `State(Fᵢ)` when `registry_timestamp_semantics ≠ AUTHORSHIP_CHRONOLOGY` | MP L1101 | derived |
| **investigation order** | which files an investigator consults first for a research question (immediate context → threads → §15 levels) | MP §0E.2, §15 L2489–2535 | extractor |
| **consultation boundary (traversal policy)** | the *set* of files that may be consulted for a given question | MP §0E.1 L813–825 (**chronology ≠ consultation boundary**) | **policy — H-4** |
| **P3A consultation** | always permitted as `p3a_*` cross-reference, never adopted | MP L1030–1041 | MP |

**Design [D]:**
- Every consultation of a file other than the unit's own is logged as `exec_consultation {unit, consulted_file_id, direction: BACKWARD|FORWARD|P3A, question, result}`.
- The MP-visible consequences go into MP fields: `previous_related_files[]`, `successor_candidates[]`, `resolution_source`, Research Obligation status.
- The consultation log makes forward investigation **auditable** (anti-hindsight) instead of forbidden.

**⛔ BLOCKED BY H-4.** The traversal-policy value (strict no-look-ahead vs MP §0E targeted forward investigation) is a human decision.

Consequence stated for the decision: under a strict policy, `UNDERIVED_BY_CORPUS` (which requires §15 Level 7, the entire corpus, L2527–2533) and forward Research Obligation resolution (L857–886) are **unreachable**. Gaps then stay `OPEN`/`OUT_OF_WINDOW`.

---

## 13. P3A integration

| Rule | Design |
|---|---|
| consult, never adopt (MP L1038–1039) | Layers 2–5 may read P3A per-file metadata; every value is recorded **only** as a `p3a_*` field, and verified against the complete file before use |
| comparison layer (§42) | `P3A-COMPARISON.jsonl` rows `{file_id, p3a_candidate, p3a_relationship, new_relationship, agreement, disagreement}`, **written by the extractor**; v1.3-R checks that every `p3a_*` value is paired with an independently derived value |
| P3A dates | `p3a_best_historical_date` is kept as a cross-reference only; it **never** feeds `historical_sequence_date` (MP L4716) |
| **the redesigned S-identifier guard** | the old guard (C15 §5) forbade S identifiers everywhere. **New rule:** S-lane identifiers (`S####`, `P3a`, …) are **allowed only** in fields whose name starts with `p3a_` and in `P3A-COMPARISON.jsonl`. **Forbidden** in content, interpretation, `[E]`, Gap and edge `evidence` fields, and in any `id` field (T-P3-1…3) |
| Layer-1 isolation | the Layer-1 extractor does not read P3A (§7-I); Layers 2–5 do |
| no write | P3A is read-only (MP L4290; the human's S isolation rule) |

---

## 14. TDI integration

**No new schema.** The artifact is MP's `THEORY-DISCOVERY-INDEX.jsonl` (P1-Q1, L42–56). Its live form in the lane is `{file_id, candidate_theory_bearing, signals_found[], confidence, quoted_signal, why?, note?}` (TDI row F0001, read this session).

| Aspect | Design |
|---|---|
| **entry produced** | exactly one per `file_id` read (P1-Q1: no `READ_COMPLETE` without an entry, L58) |
| **producing record** | the extractor, at Layer 1 completion, from the same reading as the FRR. Not derived from F categories (retired) |
| **provenance** | `quoted_signal` is verbatim from the file; v1.3-R checks the bytes (the quote must lie inside the admitted content and inside covered units) |
| **origin** | reconstruction interpretation (§8.3). At the handoff it is an **index, never a relevance filter** (ACL-1) |
| **scope** | one file; the TDI entry carries no corpus claim |
| **source file** | the canonical `file_id` |
| **relationship to P3A** | none. The TDI is keyed to content and **must not be seeded from P3A labels** (T-TDI-5) |
| **lifecycle** | written once per read. **[O] P-2:** revision of a TDI entry is not covered by MP §5A's list (L1322). Until decided, a changed judgment is a new append-only line carrying a reference to the prior one; nothing is edited |
| **gate** | existing GOV gates **KOS-G-010/011/012** (active; KOS-G-010 activated). v1.3-R adds a **mechanical quote check** as evidence; it creates no gate |
| **policy** | the lane's TDI policy is biased toward `true`: never `false` from a thin read (`THEORY-DISCOVERY-INDEX-POLICY.md` §2). v1.3-R encodes the inadmissible document-kind reasons as a check (it mirrors KOS-G-012) |
| **downstream** | the RRP carries the TDI as an index (§18). Phase 2 treats it as *"where might theory be?"*, never as *"what matters"* |

**Known limitation carried forward [F]:** the discriminator has never returned `false` (STATE "C2 measurable ≠ validated"; KOS-G-013 REVIEW). v1.3-R measures this; it does not claim to fix it.

---

## 15. STATE and GOV integration

### 15.1 Before every execution unit (RA-16)

1. Run the lane's `governance-preflight.sh` / `gate-runner.py`. Exit **0** = CLEAR, **2** = BLOCK, **3** = GOVERNANCE_INOPERATIVE (hook L15–21). Anything other than 0 means **STOP**.
2. Read `governance-state.yaml` (activations), the L0 decision records, STATE (execution position and holds) and `RECONSTRUCTION-STATE.json`.
3. **Refuse** if STATE carries a HOLD covering the unit. **Today it does:** STATE L70–81.
4. **Refuse** if the unit's authorization is not recorded in GOV (§22: H-2, G-2 of the Map).

### 15.2 Authority vocabulary — never conflated

```text
verification        (mechanical check passed)            ← v1.3-R may produce
  ≠ authorization   (GOV permits this unit/scope)         ← human via GOV
  ≠ human acceptance(KOS-G-060, a human records it)       ← human only
  ≠ adoption        (a governance act on content)         ← human, Layer 5 path (RA-4)
  ≠ scientific validation (Phase 2, 2C)                   ← never Phase 1
```

**v1.3-R writes nothing to** `governance-state.yaml`, `gates.yaml`, the hook, or the L0 records. The research session is forbidden to do so (`governance-state.yaml` L1–10). Proposed new gates, if any, go to GOV as proposals (the `PROPOSED-GATES-RCI.yaml` pattern). Activation stays human.

### 15.3 State

- **Authoritative:** STATE (RA-11) and `RECONSTRUCTION-STATE.json` (MP §36A).
- **v1.3-R's local ledger** is a **NON-AUTHORITATIVE DERIVED EXECUTION CACHE**:
  - hash-chained (the v1.2 mechanism);
  - holds `exec_unit_state` + `exec_run_id` + commit ids.
- **Reconciliation, at unit start and unit end:**
  - recompute the next unit **from the authoritative state and registry order** (RA-12), never from the cache;
  - if the cache disagrees with `RECONSTRUCTION-STATE.json` (`last_processed_file_id`, `current_processing_*`), then **STOP and report**. The cache never wins.
- At unit end: write `RECONSTRUCTION-STATE.json` fields per MP §36A (in-flight → `COMPLETED`/`FAILED`) and propose the STATE update.
- STATE is Markdown maintained by the research session under RA-12. **[O] I-4:** whether the layer writes STATE directly or proposes the update.

---

## 16. Audit model

| Aspect | Design |
|---|---|
| **scope** | Layer-1 content only: definitions, assumptions, premises, claims, mathematical and statistical objects, conclusions, dependencies, `date_events`, `source_self_declared_status`, `intra_file_revisions`, and the TDI entry |
| **independent method** | a separate agent run under isolation (§7-I) reads the file through the reader under its own run id and authors its own Layer-1 record set **in MP schema** |
| **independent execution** | a separate context and scratch location; no access to the extractor's records, earlier audits, quarantine or P3A |
| **independent measurement** | its own coverage over the **shared deterministic frame**. The frame is shared on purpose (§7-B): it makes the comparison computable without sharing judgments |
| **allowed to know** | the file (through the reader), the frame, the MP schema, the lane's execution spec |
| **must not know** | the extractor's records, dispositions, TDI entry, gate record, earlier audits, theory documents, other files |
| **disagreement** | computed per unit: (a) a unit the auditor covers with a definitional or mathematical record that the extractor does not; (b) a unit the extractor dispositioned `NO-SUBSTANTIVE-CONTENT` that the auditor covers; (c) a DEFINITION term missing on one side; (d) a verbatim-quote failure; (e) a TDI `candidate_theory_bearing` mismatch; (f) a `source_self_declared_status` mismatch |
| **recording** | `exec_audit {run, auditor_run, frame_hash, extractor_seal, auditor_seal, discrepancies[], verdict: NO-DISCREPANCY \| DISCREPANCY}`, sealed and referenced from the gate record's evidence field. **Discrepancies are recorded, never fixed by the auditor** |
| **advisory or gating** | **advisory** in v1.3-R. A `DISCREPANCY` flags the gate evidence and routes the file to review; it does not change an MP status by itself. **Making it gating requires an MP amendment (P-10)** |
| **cadence** | [O] I-5: the first file of a window and every fifth (inherited). The cadence is an execution parameter, not an MP rule |
| **human approval** | acceptance of a reconstruction remains KOS-G-060 (a human act). v1.3-R supplies evidence only |
| **what it is not** | ⛔ **audit evidence ≠ truth.** `NO-DISCREPANCY` means two procedurally independent extractions agreed on the measured dimensions. Both can be wrong in the same way (the same model family; "procedural, not statistical" independence, C13 v1.2 §3) |

---

## 17. Version and correction model

```text
original record vk  (sealed; gate evidence; audit attached)
      │
      ▼
correction request   ← from Phase 2 (RA-5, MP L68–76) or a backward discovery (MP §33)
      │                 ⚠ MP defines no correction-request schema → [O] P-1
      ▼
new reconstruction version vk+1   (MP §5A fields: record_version, previous_record_hash = hash(vk),
      │                            new_record_hash, changed_by_file, change_reason,
      │                            change_evidence ← carries the request reference, changed_at)
      ▼
lineage binding       exec_seal(vk+1) references seal(vk); consultation log records the trigger
      ▼
old version preserved byte-identical; its gate evidence and exec_audit stay attached to vk
      ▼
audit status preserved   vk's audit certifies vk only; vk+1 is audited under the same cadence rule
```

- **No `REOPENED` state.** MP has none. The file's lifecycle does not regress; a revision is an event on the record (§5A L1334–1344).
- **Hindsight guard [D]:** the vk+1 extractor receives the request's *scope* (file, section, question type), **not** the Phase-2 hypothesis that prompted it. This is recorded in its attestation (T-VC-4).
- **Authority:** whether a correction needs a human act is **H-5 / H-2**, not assumed.
- **Intra-file revisions** (the source revises itself) are MP §5A case 3 and are **not** corrections (L1352–1358).

---

## 18. RRP handoff

**Package contents (MP L64; ARCH §4.1 L147–154):**
- the corpus registry reference (canonical log + EVB manifest hash);
- `E-####` Evidence Objects;
- File Reconstruction Records;
- the **TDI**;
- Theory Objects and threads;
- definitions, assumptions, claims, derivations (`DI-####`);
- candidate and verified edges;
- contradictions, branches, merges, gaps;
- `[E]` records and researcher observations;
- Research Obligations;
- scope/regime (§20);
- provenance (§6A layers);
- historical ordering (`date_events`, `historical_sequence_date`);
- reconstruction status (`dossier_status`, gates, `UNDERSTANDING_UNCERTAIN`) and documentation-quality assessments;
- `p3a_*` comparisons.

**Translation table (RA-3: every crossing is translated and recorded):**

| Phase-1 item | Translated as | ORIGIN |
|---|---|---|
| source content with a verbatim `E-####` | a statement with a locator | `[C]` |
| Theory Object / thread / candidate consolidation (cross-file) | a synthesized construct | `[S]` |
| `[E]` record | an expert-derived candidate (`HYPOTHESIS`/`NOT_YET_ASSESSED`) | `[E]` |
| extractor interpretation metadata | provenance of the above, not content | — |
| `exec_*` metadata | a provenance attachment (seals, receipts, coverage, audit) | — (never content) |

**Each record carries its Phase-1 scope** (ACL-4; gate Q55 on the Phase-2 side).

**Phase 2 receives but does NOT inherit as truth:**
- TDI `true` ≠ relevant (ACL-1);
- `VERIFIED` edge = evidence-verified ≠ validated (MP L1686);
- `RECONSTRUCTION_RECORD_VALIDATED` ≠ correct mathematics (L2077);
- `NO-DISCREPANCY` audit ≠ truth;
- coverage ≠ correctness;
- a `SOURCE_ASSERTED_CONCLUSION` ≠ proven;
- `[E]` ≠ `[C]` (S2 L1570);
- a `p3a_*` value ≠ a reconstruction value;
- repetition ≠ corroboration (RA-15 **D**);
- a candidate consolidation (§19C) ≠ equivalence.

**v1.3-R does not produce the RRP's content.** It supplies the attachments and runs the mechanical completeness check of the package manifest (every item resolves; every non-`[C]` item has its origin).

---

## 19. Formal invariants (v1.3-R)

| # | Invariant | Basis |
|---|---|---|
| **R1** | **MP authority.** v1.3-R defines no Phase-1 semantic rule absent from MP unless it is marked **IMPLEMENTATION CONSTRAINT** (stricter, never looser) or is an approved MP amendment | ARCH L8, §9 |
| **R2** | **Canonical identity.** No corpus identity other than the canonical `file_id`; `exec_run_id` never appears in an MP `id` field | MP L1203 |
| **R3** | **Epistemic separation.** Phase-1 execution assigns no L4/L5 status; `mathematical/statistical_validation_status` ≠ `NOT_YET_ASSESSED` only when the **source** documents validation | MP L403–412; ARCH §7 |
| **R4** | **Provenance.** Every non-SOURCE-layer statement carries its provenance layer and interpreter; every `[E]` carries its nine fields; `exec_*` metadata never occupies a content field | MP §6A L1517–1536; L147 |
| **R5** | **Historical immutability.** No later evidence modifies an earlier record version; revisions are appended per §5A | MP §5A; RA-2 |
| **R6** | **Governance separation.** No mechanical result creates authorization, acceptance or adoption; the layer writes no GOV file | ARCH RA-16; GOV README L60–80 |
| **R7** | **Audit independence.** Audit output is a separate, sealed artifact authored without access to the extractor's records; it is distinguishable from extraction output by path, run id and seal | C13 v1.2 §3 (mechanism) |
| **R8** | **P3A non-adoption.** P3A-derived values exist only in `p3a_*` fields / `P3A-COMPARISON.jsonl` | MP L1030–1041, §42 |
| **R9** | **Phase boundary.** No hypothesis test, pre-registration, confirmatory statistic or validation runs in the layer | MP §0B L509–519; ARCH §7 |
| **R10** | **State authority.** The local cache never overrides STATE / `RECONSTRUCTION-STATE.json` / GOV; a disagreement means STOP | ARCH RA-11/12/16 |
| **R11** | **Fail-closed identity.** Admission is ADMIT or STOP (no "closest match"); ambiguity = STOP | EVB L37–47 |
| **R12** | **No backfilled evidence.** No receipt, seal or audit is issued for a read that happened before the mechanism existed | EVB L87 |
| **R13** | **Consultation provenance.** Every consultation beyond the unit's own file is logged with direction and question | MP §0E.1–0E.3 (implementation constraint) |
| **R14** | **Governance read first.** No unit starts without CLEAR preflight and a GOV-recorded authorization for its scope | ARCH RA-16; STATE L81 |
| **R15** | **Quarantine non-reference.** No MP record references a quarantined path | MP §45 Gate 0 (implementation constraint) |

---

## 20. Conformance-test matrix (to be written RED before any implementation)

| Id | Area | Property tested | Method | Pass = |
|---|---|---|---|---|
| T-AR-1 | Architecture | no second protocol | the execution spec cites ARCH + MP; a grep finds no F semantic schema (no FCI/FAN/FRS/L1/L2/L3 in the spec or code) | 0 hits |
| T-AR-2 | Architecture | no second control plane | the layer never writes under `governance/`; the write guard refuses | refused |
| T-AR-3 | Architecture | no second registry | the layer's file table is derived from the canonical log / EVB; any `file_id` not in the canonical log is refused | refused |
| T-AR-4 | Architecture | no second inventory | a window request must be a registry-order slice; a list file as input is refused | refused |
| T-EP-1 | Epistemic | source vs observation vs `[E]` | a fixture with a content field carrying an interpretation marker, or an `[E]` lacking any of its nine fields | refused |
| T-EP-2 | Epistemic | Phase-1 vs Phase-2 | a record with `mathematical_validation_status: VALIDATED` without source validation, or any `[T]`, or a test outcome | refused |
| T-EP-3 | Epistemic | no L1-ACCEPTED collapse | the schema has no single consumability state; a consumer cannot read "accepted" from a seal or an audit | property holds |
| T-EP-4 | Epistemic | `exec_*` never in content | a fixture placing `exec_` data in a content field | refused |
| T-EP-5 | Epistemic | no level collision | the strings `L1`/`L2`/`L3` are absent as level labels in the spec and records | 0 hits |
| T-PV-1 | Provenance | source → record → revision chain resolves | a traversal from `E-####` to bytes | all resolve |
| T-PV-2 | Provenance | no silent rewrite | mutate a sealed record | seal break → STOP |
| T-PV-3 | Provenance | hash binding | change the MP/ARCH/manifest hash between units | mismatch → STOP |
| T-CH-1 | Chronology | a date from `A_CITED_ARTIFACT` never sets `historical_sequence_date` | fixture | refused (mirrors MP §37 check 14) |
| T-CH-2 | Chronology | read order = registry order | an out-of-order unit request | refused |
| T-CH-3 | Chronology | a consultation is logged | a consultation without a log entry | refused |
| T-CH-4 | Chronology | the look-ahead policy is applied as configured | **blocked until H-4** | — |
| T-MA-1 | Mathematics | a demonstrability-gated gap type requires a source-internal basis | `ALGEBRAIC_INCONSISTENCY` without cited source lines | refused (routed to `POTENTIAL_VALIDATION_ISSUE`) |
| T-MA-2 | Mathematics | proof ≠ assertion | a source "PASS" recorded as a `THEOREM` without a demonstrated proof | flagged |
| T-MA-3 | Mathematics | no accidental validation | `UNDERIVED` with `mathematically_invalid` | refused (MP L3501) |
| T-ST-1 | Statistics | descriptive vs confirmatory | a Phase-1 output containing p-value / significance / "confirmed" | refused |
| T-ST-2 | Statistics | an ML signal is labelled | an `exec_signal` without method/inputs/limits | refused |
| T-ST-3 | Statistics | no hidden test | any pre-registration or test object in the Phase-1 tree | refused |
| T-ST-4 | Statistics | ML never establishes a relationship | a verified edge whose only evidence is an ML signal | refused (§27) |
| T-DD-1 | DDD | HA vs `[E]` architecture | a researcher-proposed component recorded as HA | refused |
| T-DD-2 | DDD | domain vs execution artifact | `exec_*` referenced as a Theory Object / `E-####` | refused |
| T-AU-1 | Audit | independence | the auditor attempts to read the extractor's records | refused + attestation |
| T-AU-2 | Audit | complete frame coverage by the auditor | an auditor below full frame coverage | audit invalid |
| T-AU-3 | Audit | disagreement is computed and recorded, not asserted | a stored verdict ≠ recomputed | refused |
| T-AU-4 | Audit | audit ≠ acceptance | `NO-DISCREPANCY` changes no MP status | property holds |
| T-GV-1 | Governance | authority from GOV | a unit without preflight CLEAR / GOV authorization | refused |
| T-GV-2 | Governance | no local approval | an F approval line present ⇒ ignored as authority | property holds |
| T-GV-3 | Governance | no automatic acceptance | no code path writes an acceptance | property holds |
| T-GV-4 | Governance | a HOLD is respected | STATE HOLD covering the unit | refused |
| T-P3-1 | P3A | consultable | a `p3a_*` field accepted with S identifiers | accepted |
| T-P3-2 | P3A | traceable | a `p3a_*` value without a paired independent value | refused |
| T-P3-3 | P3A | never silently adopted | an S identifier in a content/`[E]`/edge-evidence/id field | refused |
| T-TDI-1 | TDI | one entry per read file | a read file without an entry | refused (P1-Q1) |
| T-TDI-2 | TDI | `false` ⇒ `why` | fixture | refused |
| T-TDI-3 | TDI | `why` is not document kind | fixture ("governance document") | refused |
| T-TDI-4 | TDI | `quoted_signal` verbatim | a mutated quote | refused |
| T-TDI-5 | TDI | not seeded from P3A | TDI signals equal P3A labels with no quote | flagged |
| T-ID-1 | Identity | F3082–F3108 are not processable | a unit request for any of them | refused (**until H-3**) |
| T-QU-1 | Quarantine | non-reference | an MP record citing `quarantine/` | refused |
| T-VC-1 | Versioning | an append-only revision | a revision without `previous_record_hash` | refused |
| T-VC-2 | Versioning | the old version is preserved | a vk byte change after vk+1 | seal break |
| T-VC-3 | Versioning | the audit is bound to its version | a vk audit presented for vk+1 | refused |
| T-VC-4 | Versioning | the hindsight guard | the vk+1 extractor received the triggering hypothesis | attestation failure |
| T-ST8-1 | State | the cache never wins | cache ≠ `RECONSTRUCTION-STATE.json` | STOP |
| T-ST8-2 | State | crash safety | kill mid-unit ⇒ resume discards partial work and restarts at Layer 1 (MP L4004) | holds |

---

## 21. Migration map

The Map's verdicts are carried forward; no deletion. **GB** = GOVERNANCE-BLOCKED.

| Current F-Series | Target v1.3-R | MP artifact | Phase | Authority | Test | Verdict |
|---|---|---|---|---|---|---|
| protocol v1.0 / v1.1, agent contracts v1.0 / v1.1, remediation matrix, audits, quarantine, bootstrap report, session log, readiness gate, baseline, DD review, commission prompts | historical record | — | — | — | — | KEEP-H |
| protocol v1.2 | **execution & assurance spec v1.3-R**: references ARCH + MP, no semantics | MP whole | 1 | human approval of the spec (H-2 path) | T-AR-1 | TRANSFORM |
| agent contract v1.2 | the extractor/auditor **run procedure**, emitting MP records | FRR, `E-####`, TDI, gate record | 1 | — | T-EP-1, T-TDI-* | TRANSFORM |
| v1.3 design | DD decisions → §17, §8.2, §15 | §5A; GOV | 1 / GOV | H-5…H-8 | T-VC-*, T-EP-3 | GB |
| v1.3 invariants and threat model | R1–R15 (§19); T1–T20 replaced by MP §9A + `exec_unit_state` | §9A, §36A | 1 | — | all | TRANSFORM |
| contracts index | index of the v1.3-R spec | — | 1 | — | T-AR-1 | TRANSFORM |
| C01 reading integrity | §7-A (+ EVB receipts) | `FILE_READ` | 1 | H-14 | T-PV-3 | KEEP |
| C02 content extraction | §7-B/C; categories retired | `EXTRACTION_COMPLETE`; FRR | 1 | — | coverage tests | TRANSFORM |
| C03 definitions | extractor procedure → §17 `D-####`; neutral examples | §17 | 1 | — | T-EP-1 | TRANSFORM |
| C04 structural | → FRR Layer 1 + §0E.4 Level 2 | §9, §0E.4 | 1 | — | T-EP-5 | TRANSFORM |
| C05 cross-file | → candidate/verified edges + `exec_consultation` | §7–§8 | 1 | H-4 for the policy | T-CH-3, T-ST-4 | TRANSFORM |
| C06 mathematics | §9 table | §13–§14, §0A | 1 / 2 | — | T-MA-* | SPLIT |
| C07 logic | → Gap / Contradiction | §14, §22 | 1 | — | T-MA-1 | TRANSFORM |
| C08 DDD | → HA/AR records, §21; proposals → `[E]` | §0C, §21 | 1 | — | T-DD-* | TRANSFORM |
| C09 statistics / ML | §10 | §27, §40 | 1 / 2 | — | T-ST-* | SPLIT |
| C10 hypotheses | `[E]` record | L147 | 1 / 2 | — | T-EP-1 | SPLIT |
| C11 testing | Step 2 | — | 2 | Phase-2 protocol | T-ST-3 | MOVE-P2 |
| C12 provenance | → §6A layers + MP namespaces | §6A, §4A | 1 | — | T-PV-1 | TRANSFORM |
| C13 state and audit | §7-D/E/F/G, §15.3, §16 | §36A; gate record | 1 | P-10 for gating | T-AU-*, T-ST8-* | TRANSFORM |
| C14 governance | removed as authority; → GOV | GOV | GOV | H-2 | T-GV-* | GB |
| C15 isolation | §7-I (limited) + the §13 guard redesign | — | 1 | HDR-1 | T-AU-1, T-P3-* | TRANSFORM |
| F-MANIFEST | EVB manifest consumption + page-model cache | §36A | 1 | H-14 | T-AR-3 | TRANSFORM |
| F-SERIES-STATE | non-authoritative cache (legacy lines KEEP-H) | §36A | 1 | — | T-ST8-1 | TRANSFORM |
| F-STATE-ANCHOR · F-READ-INTEGRITY · `ledger/F3082/*` | preserved evidence | `FILE_READ` evidence | 1 | H-12 (F3082 standing) | — | KEEP |
| F-DECISIONS · F-GOVERNANCE-LOG | GOV | GOV | GOV | H-2, H-4 | T-GV-2 | GB |
| 1206 population list | not an input | — | — | H-11 | T-AR-4 | KEEP-H (+ GB) |
| `f_read_source.py`, `f_units.py`, `f_compare_inventory.py` | reader / frame / audit comparison | — | 1 | H-14 | T-AU-3 | KEEP |
| `f_common`, `f_integrity`, `f_checks`, `f_transition`, `f_audit`, `f_status`, `f_crossfile`, `f_register`, tests | re-targeted onto MP records, GOV and state | — | 1 | — | the §20 matrix | TRANSFORM |
| `f_checkpoint.py` | Step 2 | — | 2 | — | T-ST-3 | MOVE-P2 |

---

## 22. Human-decision blockers

| Id | Decision | Design points blocked | Status |
|---|---|---|---|
| **H-1** | F-Series role (R-A / R-B / R-C) | the whole design | **open** |
| **H-2** | authority model: which unit and correction acts need GOV authorization; the path for approving the v1.3-R spec | §15.1 step 4, §17 authority, C14 | **open** |
| **H-3** | F3082–F3108 canonical membership | T-ID-1; any unit on those files | **open** |
| **H-4** | traversal policy (strict no-look-ahead vs MP §0E) | §12 policy value; T-CH-4 | **open** |
| **H-5** | DD-1: correction model (this design proposes §17: the §5A revision, no `REOPENED`) | §17 | **open** |
| **H-6** | DD-2: no `L1-ACCEPTED` (this design proposes §8.2) | §8.2 | **open** |
| **H-7** | DD-3: re-binding of prior conclusions (this design leaves it to Phase 2 for `[E]` content, §17) | §17, §18 | **open** |
| **H-8** | DD-4: GO for a named scope, recorded in GOV | §15.1 | **open** |
| **H-10** | lane and output location: MP records must live in the governing lane (MP L4284–4290), but the human's rule restricts F writes to the F lane; GOV scans only the governing lane | §3, §7-F commit scope, §15 | **open** |
| **H-11** | population / order vs the canonical registry order | T-AR-4, T-CH-2, window selection | **open** |
| **H-12** | standing of F3082's existing read (orchestrator-read, non-isolated) and where to record the R14 deviation | reuse of `ledger/F3082/*` | **open** |
| **H-13** | duplicate handling under MP complete reading | §6 duplicate row | **open** |
| **H-14** *(new)* | **B-15 applied to v1.3-R:** may a research session build evidence-binding/assurance mechanisms that the governance plan allocates to the governance session? And does v1.3-R extend EVB, or is EVB itself to be adjudicated first? | §7-A, §7-D, the F-MANIFEST row, all code | **open** (B-15 "RECORDED, NOT ADJUDICATED") |
| carried | HDR-1 (isolation residual), HDR-6 (signed vs procedural decisions) | §7-I; GOV record form | open |

**Governance-state blockers that are not new decisions [F]:**
- STATE HOLD (L70–81);
- MP §49 item 5, governance freeze (L4706);
- B-12, B-13 (9 divergent ids; F0035/F0040 never read, EVB L86), B-14.

These bound any RRP built from F0031–F0040.

---

## 23. Open questions

### 23.1 Protocol questions — MP silent or insufficient; recorded, not fixed inside F

| Id | Question | Where |
|---|---|---|
| **P-1** | MP says corrections "arrive as requests" but defines **no correction-request record** (trigger, scope, method, files examined, found/not found) | MP L68–76 |
| **P-2** | TDI entries are not in §5A's revisable-record list; the TDI revision model is undefined | MP L1322 |
| **P-3** | there is no exemption from complete reading for byte-identical duplicates. **Candidate amendment** | MP §2 |
| **P-4** | there is no partial-read state (capacity limits); `FILE_READ` simply stays unsatisfied | MP §9A |
| **P-5** | the lane's per-file gate record names (`IDENTITY_ADMITTED`, …) differ from the MP §9A list; which is authoritative? | MP L2063–2074 vs `F0001.gates.json` |
| **P-6** | the ORIGIN axis is not defined for Phase-1 records; is translation at the handoff (§18) the intended reading? | S2 L1560–1572; ARCH RA-3 |
| **P-7** | the Candidate Theory Registry (MP §19C) vs ARCH §7 "Candidate Theory: Phase 1 no" | Map A4 |
| **P-8** | the Reference Architecture (§49 item 1 BLOCKING) vs the `UNAVAILABLE_NO_REFERENCE_ARCHITECTURE` rule — may Layer 2 run without it? | MP L645–651, L4702 |
| **P-9** | stale version references (MP "FROZEN v1.0"; STATE "v1.1"); README-P cites the superseded architecture proposal | Map A1–A3 |
| **P-10** | may the independent audit (and isolation) become **gating** for `dossier_status`? **Candidate amendment** | MP §9A |

### 23.2 Implementation questions

| Id | Question |
|---|---|
| I-1 | where v1.3-R code lives (F lane `scripts/` vs governing lane); depends on H-10 / H-14 |
| I-2 | the reader: extend EVB `admit.py` with paging, or wrap it? (H-14) |
| I-3 | Git-CAS on a branch shared with concurrent sessions (HEAD moved during this work: `d3629aa5e` → `4c1946181`) |
| I-4 | whether the layer writes STATE or proposes the STATE update |
| I-5 | audit cadence value |
| I-6 | enforcing agent isolation in this harness (the residual stays; C15 §6) |
| I-7 | schema version ids for §36A `schema_version` |
| I-8 | whether the legacy `F-SERIES-STATE` lines need migration (proposed: none, KEEP-H) |

---

## 24. Implementation prerequisites (all must hold before any implementation)

1. H-1 = R-A, recorded in GOV.
2. H-14 / B-15 adjudicated by governance (who may build evidence binding; the EVB status).
3. H-2 and H-8 recorded (authority model; GO scope); H-10 and H-11 decided.
4. STATE HOLD lifted, or a scoped authorization recorded; MP §49 item 5 decided.
5. Protocol questions P-1, P-5 and P-8 answered or explicitly deferred by governance (they change schemas the layer checks).
6. The v1.3-R execution spec written from this design and approved (a human act, not a self-approval).
7. The §20 test matrix implemented **RED first**, then GREEN; tests pass before any real-file run.
8. H-3 before any unit on F3082–F3108; H-12 before any reuse of F3082's read; H-4 before T-CH-4 or any forward consultation.
9. A diagnostic pilot per MP §45 Gate 0, on a small registry-order window, with disposable artifacts in quarantine.

---

## 25. Final conformance assessment

- **Against ARCH [D]:** the design holds no semantics, level, gate, registry, state authority or control plane of its own. It routes authority through GOV (RA-16) and state through STATE (RA-11/12). It carries Phase-1 content only in MP forms, keeps Phase-2 work out (§7 table ARCH L267–286), and translates at the boundary (RA-3).
  - It is **conditionally** conformant because four decisions change *whether* the design may exist or where it writes: H-1, H-14, H-10 and H-2.
- **Against MP [D]:** all semantics are MP's. Each F-added rule is labelled an implementation constraint and is stricter, never looser: the MATH/CODE coverage rule, consultation logging, the quarantine non-reference rule, and the Layer-1 isolation limit. No mandatory amendment is needed (re-verified in §6, §7, §16).
  - It is **conditionally** conformant because P-1, P-5 and P-8 leave schemas the layer checks under-specified, and H-4 / H-11 / H-3 fix execution parameters MP leaves to governance.
- **Not demonstrated:** that the mechanisms work. No test exists yet, and the discriminator and extraction-quality questions (KOS-G-013, B-12) remain as open as before.

```text
v1.3-R DESIGN STATUS
--------------------

Architecture conformity:
CONDITIONALLY CONFORMANT

Master Protocol conformity:
CONDITIONALLY CONFORMANT

Implementation authorization:
BLOCKED

F3082 authorization:
BLOCKED

Outstanding human decisions:
H-1 (F-Series role) · H-2 (authority model) · H-3 (F3082–F3108 identity) · H-4 (traversal policy) ·
H-5…H-8 (DD-1…DD-4 as reframed in §8.2, §15, §17) · H-10 (lane/output location) · H-11 (population/order) ·
H-12 (F3082 read standing; R14 record) · H-13 (duplicates) · H-14 NEW (B-15 applied to v1.3-R: may research
build evidence binding; EVB status) · carried HDR-1, HDR-6 · plus existing governance state: STATE HOLD,
MP §49 item 5, B-12/B-13/B-14

Outstanding protocol questions:
P-1 correction-request schema · P-2 TDI revision · P-3 duplicate exemption (candidate amendment) ·
P-4 partial-read state · P-5 gate-name authority · P-6 ORIGIN for Phase-1 records · P-7 Candidate Theory
Registry vs ARCH §7 · P-8 Reference Architecture vs Layer 2 · P-9 stale version references ·
P-10 gating audit/isolation (candidate amendment)

Outstanding implementation questions:
I-1 code location · I-2 EVB admit.py extension vs wrapper · I-3 CAS on a shared branch · I-4 STATE write vs
proposal · I-5 audit cadence · I-6 isolation enforcement residual · I-7 schema version ids ·
I-8 legacy state migration

Next safe step:
Human review of this design together with the Map, then H-1; if R-A, route H-14 (B-15) to the governance
session before any other decision, because it determines whether a research session may build this layer at all.
```
