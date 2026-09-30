# F-SERIES GOVERNANCE LOG

Append-only record of human decisions and acts for the F-Series lane (protocol §F-13). Entries are never edited or
removed; a later decision that changes an earlier one is a new entry citing it. Observations about inherited S-Series
methodology are recorded here as `F-OBSERVATION-ON-INHERITED-METHOD` and never applied to the S artifacts.

---

## F-LOG-0001 — Lane commissioned; protocol v1.0 submitted (planning entry; no decision recorded)

| Field | Value |
|---|---|
| Commission | human instructions of 2026-09-25: the F-Series session bootstrap prompt; "write prompts in `prompts/`"; the three-layer / two-ledger / state-machine prompt; the isolation, inheritance and change-control rule; the writable folder is this lane folder only |
| Recorded | 2026-09-25 by the AI session |
| Submitted | `prompts/20260925_1239_F-SERIES-RESEARCH-PROTOCOL-v1.0.md` · `prompts/20260925_1239_F-SERIES-AGENT-CONTRACT-v1.0.md` · `F-BOOTSTRAP-REPORT.md` |
| Infrastructure | `scripts/` (7 scripts) · `tests/test_f_pipeline.py` 14/14 PASS on a synthetic fixture outside the repository |
| Open for decision | FD-01 … FD-10 (protocol §F-14; bootstrap report §12) |
| State | no F-file read; no F-ID registered (F-P0 waits for FD-01); no S-Series artifact modified (baseline `F-BASELINE-GIT-STATUS.txt`, 0 lines under `chronological-read/`) |

## F-OBSERVATION-ON-INHERITED-METHOD-0001 — naming of the P3b protocol files

The P3b operating protocol files are named `…p3b-phase1-continuation-protocol…`, while their content (§1, §8) places
P3b in v3.5 **Phase 3**. A reader looking for "Phase 2" may confuse the two (this produced FD-02). Recorded for a future
S-Series review; the S artifacts are not changed.

## F-OBSERVATION-ON-INHERITED-METHOD-0002 — population by path exclusion

`20260925_1206_list_of_files_to_read.log` was built by excluding paths listed in `02-FILES.jsonl`. As a result, 175 F-files
are byte-identical to S files that sit under other paths (renames and copies). v3.5 R11 makes content identity sha256,
not path. This is recorded as a property of the F population (FD-04); nothing is changed.

---

## F-LOG-0002 — FD-01 APPROVED with rulings RL-01…RL-10; population list, protocol and contract FROZEN

| Field | Value |
|---|---|
| Decider | the human project owner, 2026-09-25 ("Approved to proceed with the F-Series bootstrap and freeze the F-Series protocol/manifest. Apply the 10 rulings above. Then register the population and process F3082 only. Do not proceed to F3083 until F3082 has passed the complete read/reconstruction/research/audit gate.") |
| Recorded | 2026-09-25 by the AI session |
| RL-01 | protocol + contract v1.0 **APPROVED** |
| RL-02 | "Phase 2" = v3.5's actual Phase 2 (corpus/family grouping, after the whole population); P3b v1.7 for per-file research where applicable; **P3b is not relabelled Phase 2** |
| RL-03 | the 123 F-internal exact duplicates get a **content pointer** to the first F-copy with hash verification; not re-read; the duplicate F-ID and its path provenance stay in the population |
| RL-04 | the 175 F-files byte-identical to S files are read and processed as F evidence, marked `CONTENT-IDENTICAL-TO-S`; F provenance kept; no S interpretation or result imported — *reuse content identity, not epistemic conclusions* |
| RL-05 | the 3 deterministic repairs (F1264, F2795, F2796) approved; the 15 unresolved entries are RESOLUTION-FAILED; filenames are never guessed |
| RL-06 | the capacity files are registered, never silently excluded; explicit `READ-PARTIAL` with reason `CAPACITY`; **F3026 is a controlled capacity test** |
| RL-07 | session logs stay inside the lane folder |
| RL-08 | the per-page summary + quote is **supplementary semantic reading evidence, not proof of attention**; page hash + complete coverage remains the primary integrity proof (evidence hierarchy written into §F-7.3) |
| RL-09 | the population list is committed and becomes an immutable, versioned input |
| RL-10 | the six adapted §F-3 rules are approved as F-specific adaptations that do not alter the inherited S methodology |
| Sequence ordered | F-LIST frozen → F-PROTOCOL v1.0 frozen → F-CONTRACT v1.0 frozen → F-STATE initialized → F3082 → read → reconstruct → research → audit → F3083. No F-ID REGISTERED before the freeze commit. |

**Text changes made to v1.0 between submission (F-LOG-0001) and this freeze.** The submitted protocol sha256 was
`86180864fd9e0d810f3212bc0b0c9455b2dc661f69762b9a5e9553fc258e5c04` and the submitted contract sha256 was
`1522ce7dbe8f2dca9d704afb24162bdad3f9448694dda942fc2899374fbee205`.
1. The rulings RL-01…RL-10 were written into the protocol (§F-0, §F-3 #4 #15 #20, §F-4, §F-5, §F-6, §F-7.3, §F-7.5, §F-8,
   §F-9, §F-12.1, §F-14) and into the contract (§C-3, §C-4, §C-5 header). Code change: `files.jsonl` must carry
   `content_identical_to_s` equal to the manifest fact (RL-04); `EXACT-DUPLICATE` re-verifies both files' bytes and
   records the pointer, sha256 and path, and is subject to the list-order gate (RL-03).
2. **Two implementation changes forced by an observation, disclosed here and not silent.** During the re-test, the S reader
   `chronological-read/scripts/p3b_read_source.py` had an **uncommitted modification in the working tree** made by
   another session (sha256 `bc705bc9…`, with `p3b_s5_common.py`, `p3b_s5_verify.py` and a test also modified). The pin
   refused it, as designed.
   - (a) The lane now compiles the three verified definitions from the **committed blob** at `b7e7fc856` (sha256
     `0d48829a…05902`, unchanged at HEAD) instead of the working tree (§F-0, §F-3 #19, §F-7.1).
   - (b) The isolation check can no longer attribute outside changes. Own writes are now confined **by construction**
     (write guard `f_common.guard`, tested), and outside changes are **recorded** in each `AUDIT.json` with attribution
     UNKNOWN instead of failing the audit (§F-11).

   No inherited methodological rule is changed by (a) or (b); both are execution rules of this lane.
3. Tests: 17/17 PASS (3 added: the duplicate pointer, the write guard, and the pinned page model).

**Frozen artifacts (this entry's commit)**

| Artifact | sha256 |
|---|---|
| `docs/knowledgeos/20260925_1206_list_of_files_to_read.log` (population, 1,523 entries) | `cb701ae9a6322fe45b9dfbb3c2fe82697b11596746b57ece8516ba46e2d76859` |
| `prompts/20260925_1239_F-SERIES-RESEARCH-PROTOCOL-v1.0.md` | `fee9c63684f043605346d4c4adff2f911847079375bb66025d5a5bdae66436f5` |
| `prompts/20260925_1239_F-SERIES-AGENT-CONTRACT-v1.0.md` | `83a6a40656e445ebc5fd56d530bfea3ae298b425f0e7f292f87910b1bac82a61` |
| `scripts/f_common.py` · `f_register.py` · `f_read_source.py` · `f_transition.py` · `f_checks.py` · `f_audit.py` · `f_status.py` | `163787c7…` · `399bb984…` · `db88116c…` · `c41c4ed3…` · `01cb14cf…` · `8c29662e…` · `09bb4465…` |
| `tests/test_f_pipeline.py` | `904325d3…` |

The protocol and contract files keep their text as frozen; their status lines already say APPROVED/frozen by this
entry. The commit id of the freeze is recorded in F-LOG-0003, because a commit cannot contain its own id.

## F-OBSERVATION-ON-INHERITED-METHOD-0003 — concurrent edits to verified S infrastructure

Verified S infrastructure is edited in place in the shared working tree before being committed and reviewed. A consumer
that loads it from the working tree would silently execute unverified code. Recorded for a future S-Series review; the
S artifacts are not changed.

---

## F-LOG-0003 — Freeze commit recorded; F-STATE initialized (F-P0)

| Field | Value |
|---|---|
| Freeze commit (F-LOG-0002) | `abc0a9153` |
| F-P0 run | `python3 scripts/f_register.py` after the freeze commit; `--check` → IDENTICAL |
| `F-MANIFEST.jsonl` | 1,523 rows · sha256 `90cba97f589e06da606c1afb8e0539830286172535f098de4a4adb0df30d9ea2` |
| `F-SERIES-STATE.jsonl` | 4,554 events · states: IDENTIFIED 1,492 · EMPTY 15 · BINARY 1 · RESOLUTION-FAILED 15 |
| Counts | RESOLVED 1,505 · REPAIRED 3 (F1264, F2795, F2796, RL-05) · UNRESOLVABLE 15 · exact duplicates within F 123 · content identical to S 175 · untracked 80 · 5,120 pages · 87,513,762 characters |
| Next | F3082 only (RL-01 authorization); F3083 is not opened until F3082 is AUDITED |

---

## F-LOG-0004 — Protocol v1.1 (research-discovery programme, complete content extraction, 14 contracts) FROZEN FOR REVIEW

| Field | Value |
|---|---|
| Human instructions (2026-09-25, quoted) | (1) content preservation: *"READ-COMPLETE ≠ CONTENT-COMPLETE … A file should only receive `CONTENT-EXTRACTED` after the extractor has systematically checked the entire file for the required content categories … make now, before F3082"*, reinforced *"please strongly follow this suggestion"*; (2) research programme: *"Do not simply implement S-Series P3b per-file research. Design F-Series as a research-oriented corpus discovery programme, using S-Series' verified reading, provenance, audit and methodological controls as the foundation"*, with three output levels and fourteen contracts; (3) *"reading chronological way file by file listed and then follow this"* |
| Human choices (selector) | cross-file discovery **Both** (incremental + checkpoints) · lenses **Checklist, where warranted** (statistical/ML at checkpoints) · testing **Pre-register per file, test at checkpoints** · pacing **Freeze v1.1 first, review** (confirmed by the architect: "choose 3 now … after your review: choose 1") |
| Architect requirement | an explicit S-inheritance / F-extension matrix → protocol v1.1 §F-3.1 |
| Recorded | 2026-09-25 by the AI session |
| Effect on F3082 | stopped at READ-COMPLETE (run FR-F3082-001; reading valid under v1.1, reading rules unchanged). The ungated Phase-1 draft written before the instruction arrived is parked in `ledger/F3082/_draft-v1.0/` (not evidence; never a RECONSTRUCTED event) |
| Not changed | population, manifest `90cba97f…`, F-P0 events, rulings RL-01…RL-10, reading integrity, isolation, S artifacts |

**Frozen for review (this entry's commit)**

| Artifact | sha256 (prefix) |
|---|---|
| `prompts/20260925_1333_F-SERIES-RESEARCH-PROTOCOL-v1.1.md` | `6d9176cc50bb9239…` |
| `prompts/20260925_1333_F-SERIES-AGENT-CONTRACT-v1.1.md` (runbook) | `97036ba829ecd012…` |
| `prompts/contracts-v1.1/C01…C14` | `18840bd6…` `073514ee…` `e1622dc7…` `edcbaf07…` `ad0f9c57…` `36dfecd5…` `11ce80cd…` `b62088b3…` `a9fe614a…` `208726f3…` `762b3b45…` `f8d10b51…` `3bacf684…` `2abe7d02…` |
| scripts `f_common` · `f_checks` · `f_transition` · `f_audit` · `f_units` (new) · `f_crossfile` (new) | `94ea512a…` · `3ee2c9e8…` · `f203b43a…` · `0b4da3ca…` · `dd0d0d11…` · `ef2b1f09…` |
| scripts unchanged since v1.0 | `f_read_source` `db88116c…` · `f_register` `399bb984…` · `f_status` `09bb4465…` |
| `tests/test_f_pipeline.py` | `801fc43c…` — **32/32 PASS** (15 new: units, coverage, dispositions, math/definition-cue rules, definition fields, category checklist, carry-every-item, no skipping CONTENT-EXTRACTED, lens checklist, deferral rule, cross-file dispositions, Level-2 kinds refused in research, pre-registration, no per-file outcome) |

**Open for the review (protocol v1.1 §F-14):** FD-11 checkpoint interval (proposed 50) · FD-12 re-pin the reader to the
S withdrawal of the redirect refusal (`52fbe3c3f`, G-LOG-0052) or keep `b7e7fc856` · FD-13 admitted statistical/ML
methods (C09 §3) · FD-14 independent-audit cadence.

**Next:** F-LOG-0005 records the review. Only then does F3082 continue at D1 under v1.1, then the independent audit,
then a report. F3083 is not opened without a further go-ahead.

## F-OBSERVATION-ON-INHERITED-METHOD-0004 — the stdout-redirect refusal

The F lane hit the refusal on its first page read (the harness captures stdout into a file) and read through a pipe.
The S lane independently withdrew the refusal for the same reason (G-LOG-0052). Recorded as corroborating evidence for
that S decision; the F lane's pinned reader is unchanged pending FD-12.

---

## F-LOG-0005 — v1.1 architect review IN PROGRESS (review notes; no decision recorded)

| Field | Value |
|---|---|
| Reviewer | the human architect, 2026-09-25 |
| Scope so far | protocol v1.1; contracts C02, C06, C07. Still to inspect: C04, C08, C09, C10, C11 (then GO / MODIFY / HOLD for F3082) |
| Standing instruction | *"F3082 should remain at `READ-COMPLETE` while we do this"*; *"I would not ask Claude to rewrite v1.1 yet"* |
| Assessment recorded | C02 strong, suitable for F3082 · C06 strong, clarify internal vs external verification · C07 strong |

**Review notes (candidate deltas for a v1.2; none applied — v1.1 stays frozen)**

| Id | Note (reviewer) | Where it would land |
|---|---|---|
| RN-01 | The checkpoint cadence of 50 is an operational number, not research-derived: it is "the initial cadence for CP-01, subject to empirical review at CP-01", not a methodological constant | protocol §F-8B, §F-14 FD-11; C13 §4 |
| RN-02 | C09 must be shown to distinguish descriptive statistics, exploratory pattern detection, hypothesis generation, confirmatory testing, multiple comparisons, dependence between observations, corpus-size effects, repeated evidence, duplicate/near-duplicate documents, and ML-generated candidate patterns | C09 |
| RN-03 | The L2/L3 boundary: DOMAIN-INTERPRETATION must not become a hidden theory-generation channel. "This document defines X as a transformation between two structures" is L1/L2; "This suggests KnowledgeOS should model X as …" is L3 | C04, C06, C07, C08, C10 (+ a gate) |
| RN-04 | C06: "known mathematical result invoked correctly?" ≠ "I recognize this as a known result". Uncertainty is recorded as UNRESOLVED / REQUIRES EXTERNAL VERIFICATION, never as a CORRECTNESS-FINDING; a CORRECTNESS-FINDING must establish the defect from the stated assumptions | C06 |
| RN-05 | Correctness findings get a three-way epistemic basis: INTERNAL verification (from the file's own definitions and assumptions) · EXTERNAL verification (against established mathematics, with the external source and provenance) · UNRESOLVED; outcome CONFIRMED-DEFECT / UNRESOLVED / NO-DEFECT-FOUND | C06 (C07 by analogy), schema + gate |
| RN-06 | Every mathematical claim carries two separate fields. **Source status:** asserted / proof sketch / proved / cited / undefined-unclear. **Analytical status:** internally consistent / internally inconsistent / externally verified / externally contradicted / unresolved. "The author did not prove it" must never become "the theorem is false" | C02 THEOREM fields, C06 |

**AI self-check against the frozen text (facts, for the reviewer)**
- RN-02: C09 currently covers descriptive vs confirmatory modes, the collapse of exact duplicates, marking of
  CONTENT-IDENTICAL-TO-S, out-of-sample tests and R9. It does **not** yet address multiple comparisons, near-duplicates
  (only exact sha256 duplicates are detected), corpus-size effects, dependence beyond duplicates, or the status of
  ML-generated patterns (which would be Level-2 candidates, never evidence).
- RN-03: only C08 states the boundary, in one sentence ("A proposed model *for KnowledgeOS* is Level 3"). C04, C06
  and C07 do not state it, and no gate enforces it.

**State:** F3082 READ-COMPLETE (run FR-F3082-001); no F-file processing performed after F-LOG-0004.

---

## F-LOG-0006 — v1.1 architect review IN PROGRESS: C08, C09 (review notes; no decision recorded)

| Field | Value |
|---|---|
| Reviewer | the human architect, 2026-09-25 |
| Assessment | C08 ready, minor clarification · C09 strong foundation; exploratory vs confirmatory separation correct; duplicate handling, similarity signals and multiple testing to be made explicit · "I would not ask Claude to redesign C08/C09" |
| Next in review | C10, C11 ("the critical ones now") |

| Id | Note (reviewer) | Where it would land |
|---|---|---|
| RN-07 | C08: "the source itself contains a DDD-like concept" (e.g. source says "Order owns the lifecycle of Payment" → Level 2 observation) ≠ "I would model this in DDD for KnowledgeOS" (e.g. "therefore Order should be the aggregate root in KnowledgeOS" → Level 3 `STRUCTURE-CANDIDATE`); make it explicit | C08 (and the RN-03 boundary rule) |
| RN-08 | Duplicates: **collapse duplicate content for independence calculations, preserve all provenance identities.** Three populations: **F-ID population** (provenance / corpus inventory) · **unique-content population** (independent statistical observations) · **F-specific-content population** (content not identical to any S file; discoveries originating in the F corpus). CONTENT-IDENTICAL-TO-S files are not removed automatically from every analysis | C09 §2, C10 `population_rule`, C11 §1.2, C05 §3 |
| RN-09 | Statistical/ML similarity (Jaccard, cosine, clustering, embeddings) is a candidate signal for investigation, **not** an epistemic determination of identity, equivalence, causality or conceptual relationship; this protects C05 and C08 from statistical leakage | C09 §3–4, C05 |
| RN-10 | Multiple testing: "Multiple-testing handling must be specified in the pre-registration whenever more than one confirmatory comparison is performed" (C09 requires it; C11 implements it, e.g. a pre-specified primary test or a multiplicity correction) | C09, C10 pre-registration, C11 |

**AI self-check against the frozen text (facts, for the reviewer)**
- RN-08: the C10 pre-registration example says "duplicates collapsed" without naming which of the three populations is
  meant. C11 §1.2 records the in-sample vs out-of-sample split but not the population type. C05 §3 marks duplicates in
  cross-file counts, but defines no populations.
- RN-10: neither C10's pre-registration block nor C11 has a multiple-testing field or rule. The C10 fields are
  `population_rule, temporal_scope, selection_rule, comparison_rule, stopping_rule, prediction, registered_after_f`.
- RN-09: C09 §3 admits the similarity methods but does not state their epistemic status. C05 does not mention
  statistical similarity.

**State:** unchanged — v1.1 frozen; F3082 READ-COMPLETE.

---

## F-LOG-0007 — Independent audit of v1.1 delivered: GO WITH CONDITIONS (evidence; no decision recorded)

| Field | Value |
|---|---|
| Commission | human instruction 2026-09-25 ("F-SERIES — INDEPENDENT ARCHITECTURE & METHODOLOGY AUDIT"); executed by a fresh agent with a read-only mandate, dispatched by the orchestrator because the orchestrator authored the audited artifacts |
| Report | `audits/20260925_1500_F-SERIES-v1.1-INDEPENDENT-AUDIT.md` (persisted verbatim) · sha256 `be7e048c60765f301e673686185ba5389dbebac2bfcec79512706e7f1544dd67` |
| Auditor's verdict | **GO WITH CONDITIONS**, scoped to D1 up to and including CONTENT-EXTRACTED; F3082 must not reach RESEARCHED or AUDITED under v1.1 as it stands (AUDITED is irreversible) |
| Findings | 2 BLOCKING (F-01 L1 not frozen and hypotheses not immutable, before CONTENT-EXTRACTED; F-02 context contamination of the extractor, before D1) · 14 MATERIAL (F-03 … F-16) · 4 MINOR (F-17 … F-20) · 2 OBSERVATION (F-21, F-22) |
| Human decisions it lists | H-1 formal GO/MODIFY for D1 · H-2 CLAUDE.md session workflow vs F isolation for F agents · H-3 meaning of "no look-ahead" and "out-of-sample" when list order ≠ historical order · H-4 human sign-off for PLACEHOLDER and *-UNRESOLVED terminals · H-5 v1.2 scope or interim controls · H-6 FD-12: keep the pin |
| Auditor's repository check | lane unchanged by the auditor (`git status` on the lane: 0 lines); other checkout changes come from the concurrent session |
| Facts it records about the orchestrator's own artifacts | the draft generator `p1.py` sits in the orchestrator's session scratchpad (outside the repository) at `…/scratchpad/f3082/p1.py`; it is recorded here, as F-02 asks. It has not been moved, pending H-2/H-5 |
| State | unchanged — v1.1 frozen; F3082 READ-COMPLETE; no finding acted on |

---

## F-LOG-0008 — F-Series v1.2 remediation implemented and FROZEN FOR INDEPENDENT RE-AUDIT; NOT APPROVED FOR EXECUTION

| Field | Value |
|---|---|
| Commission | human instruction 2026-09-25 "F-SERIES — v1.2 REMEDIATION AND RE-AUDIT BEFORE F3082" (remediation session, not research extraction) |
| Recorded | 2026-09-25 by the AI session |
| Input | independent audit (F-LOG-0007); review notes RN-01…RN-10 (F-LOG-0005, F-LOG-0006) |
| Decisions per finding | `prompts/20260925_1412_F-SERIES-v1.2-REMEDIATION-MATRIX.md` — F-01…F-20, F-21, F-22, RN-01…RN-10: ACCEPT / MODIFY / REJECT (F-18 kept as-is) / DEFER-to-human (F-12) |
| Blocking findings | **F-01** closed in code (stage freezes; re-verification at every later gate and in the audit). **F-02** closed as far as the harness allows (C15 isolation contract; extractor/auditor attestations; quarantine); the residual is **HDR-1** |
| Tests | 84/84 PASS (adversarial report `audits/20260925_1412_F-SERIES-v1.2-ADVERSARIAL-TEST-REPORT.md`) |
| One-time adoption steps on the real lane | (1) `F-STATE-ANCHOR.json` sealed the 4,556 pre-v1.2 ledger lines (sha256 `488e66c4f100eed2608ed637d40203951450468beb93d459382d1b530b934f2e`); chain VERIFIED; all legacy events legal under the v1.2 table. (2) `F-DECISIONS.json` created with HDR-2/HDR-3 null. (3) `ledger/F3082/_draft-v1.0/` moved to `quarantine/F3082-draft-v1.0/`; the draft generator `p1.py` moved there from the orchestrator's scratchpad (sha256 `8cd3a604…3d`), and the scratchpad original deleted. (4) The first auditor's experiment scripts are archived in `audits/20260925_1500-auditor-experiments/` for reproducibility |
| **Incident (disclosed)** | while verifying that the real lane refuses unapproved execution, the orchestrator ran `f_read_source.py --run FR-F3082-002 --info F3082`. The reader refused (`PROTOCOL-NOT-APPROVED`) and, by design, logged the refusal: **one line was appended to `ledger/F3082/READ-LOG.jsonl`** (`git diff --numstat`: 1 added, 0 removed). F3082's reading evidence (run FR-F3082-001 pages, PAGE-DIGESTS, integrity record) is unchanged and its coverage recomputes complete. The line is kept (append-only), not removed |
| Human decisions REQUIRED (stop conditions) | **HDR-1** accept the isolation residual (protocol v1.2 §F-11, C15 §6) · **HDR-2** temporal semantics: processing-order vs historical-time hold-outs (proposal in protocol v1.2 §F-14) — hypothesis registration and checkpoint tests are mechanically blocked meanwhile · **HDR-3** multiplicity / repeated looks (proposal §F-14) — same block · **HDR-4** execution approval lines (below) · **HDR-5** GO for F3082 D1 |
| State | F3082 READ-COMPLETE; no F-file content processed; no S artifact touched |

**Frozen for re-audit (this entry's commit)**

| Artifact | sha256 |
|---|---|
| `prompts/20260925_1412_F-SERIES-RESEARCH-PROTOCOL-v1.2.md` | `378f20a3b219717a9aaf45f4d8ae6e9f1ff0fd18abe1853de10ce03337ab235d` |
| `prompts/20260925_1412_F-SERIES-AGENT-CONTRACT-v1.2.md` (runbook) | `42bc3be19d557a54d63e7c35afaaa00af589acf99f05558236169abc281e40ea` |
| `prompts/contracts-v1.2/00-CONTRACTS-IN-FORCE.md` (pins C01–C15 v1.2) | `20fef7fcb3d1f6aec618591c621332c47d51963d179061e71a7cb3dfe3235020` |
| `prompts/20260925_1412_F-SERIES-v1.2-REMEDIATION-MATRIX.md` | `2cf94fe0a7d62b518e745f2e5068c08597dbc07dfa2c45e4a75b4311fcbc0239` |
| scripts: `f_common` · `f_integrity` · `f_checks` · `f_transition` · `f_audit` · `f_compare_inventory` · `f_checkpoint` · `f_units` · `f_read_source` · `f_status` · `f_crossfile` · `f_register` | `11d130aa…` · `24faf8d3…` · `40086a43…` · `ac11a077…` · `ec02b3bc…` · `d4856903…` · `48dd30fd…` · `b8e1131d…` · `4848a2a3…` · `dc7f4507…` · `ef2b1f09…` · `399bb984…` |
| `tests/test_f_pipeline.py` | `cbbc485a137792e9098b0b5d16158dda673e9862ddcb80fe69b53164ea950458` |
| `F3082-READINESS-GATE.md` | in this commit |

**Execution approval (HDR-4) — to be written by the human, after the re-audit.** Template (indented here so that it is
*not* an approval; the human writes the three lines unindented, at column 0, outside any code block):

    APPROVED-FOR-EXECUTION: docs/knowledgeos/chronological_knowelgeos_ablation_theory/prompts/20260925_1412_F-SERIES-RESEARCH-PROTOCOL-v1.2.md 378f20a3b219717a9aaf45f4d8ae6e9f1ff0fd18abe1853de10ce03337ab235d
    APPROVED-FOR-EXECUTION: docs/knowledgeos/chronological_knowelgeos_ablation_theory/prompts/20260925_1412_F-SERIES-AGENT-CONTRACT-v1.2.md 42bc3be19d557a54d63e7c35afaaa00af589acf99f05558236169abc281e40ea
    APPROVED-FOR-EXECUTION: docs/knowledgeos/chronological_knowelgeos_ablation_theory/prompts/contracts-v1.2/00-CONTRACTS-IN-FORCE.md 20fef7fcb3d1f6aec618591c621332c47d51963d179061e71a7cb3dfe3235020

**Correction made before this entry was committed (disclosed):** a first draft of this entry placed the template at
column 0 inside a fenced code block, with the claim that the fence made it inert. It did not: the approval check matched
the lines, so the draft would have made the AI's own entry an execution approval (forbidden by C14 v1.2 §4). Caught by
the orchestrator's own verification before commit. Fixed twice: the template is indented, and `require_approval` now
ignores fenced code blocks (test `Unapproved.test_F20_approval_inside_code_block_is_not_approval`).

## F-OBSERVATION-ON-INHERITED-METHOD-0005 — logging refused reads

The S reader and the F reader both log refused calls into the file's own read log (a deliberate design: every attempt
is recorded). A pure verification call therefore changes a ledger file of the F-ID. Recorded; no change to either reader.

---

## F-LOG-0009 — Independent re-audit of v1.2 delivered (evidence; no decision recorded)

| Field | Value |
|---|---|
| Commission | human remediation commission 2026-09-25 §24 (a new independent auditor, read-only, separate scratch) |
| Report | `audits/20260925_1540_F-SERIES-v1.2-INDEPENDENT-REAUDIT.md` (persisted verbatim) · sha256 `dff9c88f194f477debcf9dc841423c45ac01ef24e7a710d92dc1620f6c3b05eb` |
| Re-auditor's verdict | **GO WITH CONDITIONS** for F3082 D1 up to and including CONTENT-EXTRACTED (conditions in its §H); **HOLD** for anything beyond CONTENT-EXTRACTED until N-01, N-02, N-03 (and before AUDITED N-04, N-06, N-08) are fixed |
| Closure of the v1.1 findings | CLOSED: F-03, F-11, F-17, F-18, F-19 · PARTIALLY CLOSED: F-01, F-02, F-04–F-10, F-13–F-16, F-20 · DEFERRED to HDR-2: F-12 · NOT CLOSED as claimed: F-21 · unchanged: F-22 |
| New findings | N-01 MATERIAL (blocking every human-ref-gated step: any F-LOG entry naming the F-ID counts as a human reference; the AI's own entries F-LOG-0004…0008 qualify for F3082) · N-02 MATERIAL (in-lane chain not tamper-evident against truncation or recomputation; make R-COMMIT mechanical) · N-03 MATERIAL (no completeness floor for the auditor's inventory) · N-04 MATERIAL (hindsight re-entry after a self-induced AUDIT-FAILED) · N-05 MATERIAL (approval-parser holes: HTML comments, nested fences; scripts not pinned) · N-06 MATERIAL (AUDITED artifacts never re-verified) · N-07 MINOR · N-08 MINOR · N-09 MINOR · N-10 OBSERVATION (the matrix claims more than the code does) |
| Additional human decision it names | bind "human reference" and "L1 acceptance" to a dedicated decision line plus the artifact hash (N-01) |
| Proportionality | it recommends replacing the hand-rolled chain with a git-prefix check, treating lexical guards as warnings, and deferring the checkpoint population machinery |
| Auditor's repository check | lane unchanged (0 lines); HEAD moved to `83aa88956` by the concurrent session; lane identical to `2d43cb34a` |
| Orchestrator's note (fact) | the matrix's F-21 row and the C15 §6 "Mechanical" row overstate the code — both confirmed by the report; the protocol text stays frozen, the correction belongs to the next version |
| State | unchanged — v1.2 frozen, not approved; F3082 READ-COMPLETE; no finding acted on |

---

## F-LOG-0010 — Senior reviews of the v1.2 re-audit; v1.3 DESIGN submitted for review (no implementation, no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-25) | two senior reviews of the re-audit, forwarded by the human with the instruction "read the analysis and if you agree follow them and if you dont, then write your disagreement" |
| What the reviews conclude | v1.3 before any F3082 D1; N-01, N-02, N-03, N-04, N-06 are the core (N-04 a hard blocker before RECONSTRUCTED); YES to a dedicated human decision bound to F-ID + action + run + artifact hash; YES to a mechanical git boundary; controls to be modelled as domain invariants, not textual guards (DETECTION ≠ VALIDATION ≠ AUTHORIZATION ≠ EVIDENCE); N-09 must not be called minor if completeness is claimed; HDR-1…HDR-5 stay open; HDR-4 waits for repaired authorization semantics; the v1.3 re-audit must be adversarial; **review the v1.3 design before it is implemented** |
| AI response | agrees and follows; records three disagreements in the design §1: **D-1** no in-repository record can establish that the actor is human (the AI commits as the human, and no signing key is configured), so the actor binding needs a key the agent cannot use (new **HDR-6**) or is labelled procedural; **D-2** a git-prefix check guarantees consistency with committed history, not its authenticity; **D-3** an auditor completeness floor reduces independence, and is reported as such |
| Submitted | `prompts/20260925_1523_F-SERIES-v1.3-DESIGN-FOR-REVIEW.md` · sha256 `8b4532cd8038a21b9deeff90f858644559cbde2b5d3dce0b8354709481f80d83` |
| State | unchanged — v1.2 frozen, not approved; F3082 READ-COMPLETE; nothing implemented; HDR-1…HDR-6 open |

---

## F-LOG-0011 — Senior review of the v1.3 design; Formal Invariants & Threat Model submitted (no implementation, no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-25) | a senior review of the v1.3 design: approve the design phase, not implementation; six points (SignedDecision vs ProceduralDecisionRecord; the gate must not make its own commit; git ≠ authority, four invariants; auditor with an independent method and a minimum coverage; a reproducible EvidenceObject identity; no hardening regress); requested artifact "F-SERIES v1.3 — Formal Invariants & Threat Model Review" for seven areas |
| AI response | agrees with all six points; adds two qualifications: **Q-1** the Gate/Commit-boundary split gives auditability and fail-closed behaviour, not actor separation (only a SignedDecision gives that); **Q-2** the auditor shares the unit frame deliberately (comparability), and its independence lies in extraction, categorisation, reading and prompt |
| Submitted | `prompts/20260925_1528_F-SERIES-v1.3-INVARIANTS-AND-THREAT-MODEL.md` · sha256 `e7882cb4c2a10546fcda264c2ecfe6f0acb47955d061672b8f822a33c406dbe7` |
| State | unchanged — nothing implemented; v1.2 frozen, not approved; F3082 READ-COMPLETE; HDR-1…HDR-6 open |

---

## F-LOG-0012 — Senior review of the invariants & threat model: APPROVE WITH THREE PRE-IMPLEMENTATION CLARIFICATIONS; r2 submitted (no implementation, no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-25) | the senior review verdict "APPROVE WITH THREE PRE-IMPLEMENTATION CLARIFICATIONS": (1) a HEAD compare-and-swap / concurrency invariant; (2) no L2/L3 derived from EvidenceObject v1 consumed as evidence for v2 without an explicit revalidation event; (3) a formal state-transition table. Also: execution-environment identity is not reproducibility; HDR-6 does not block the architecture, only the assurance level; "do not implement yet" |
| AI response | all three are adopted, with no disagreement. Two refinements: the CAS is on the shared branch ref, via `git update-ref <new> <expected-old>`, and the tree is built in a temporary index so the concurrent session's staged files can never enter an F commit; and a LANE-OWNERSHIP invariant — a foreign commit touching a lane path is a violation, not a tolerated change. **An r1 inconsistency found while drafting the table:** "accepted = authorization + AuditRecord" (§2) combined with the audit being due only for the first and every fifth file would make most files unconsumable. Resolution proposed as DD-2 (an explicit L1-ACCEPTED state with `acceptance_basis`) |
| Submitted | `prompts/20260925_1528_F-SERIES-v1.3-INVARIANTS-AND-THREAT-MODEL.md` revision r2 · sha256 `30c91ee8c1c824122c2ce710bd3145348efb769591394fb81bdca63dea709111` (r1 = `4c4d4c2a3`) |
| Design decisions opened | DD-1 AUDITED → REOPENED · DD-2 the L1-ACCEPTED state + acceptance basis · DD-3 revalidation without a human decision · DD-4 GO-D1 scope |
| State | unchanged — v1.2 code untouched; F3082 READ-COMPLETE; no extraction; no v1.3 code; v1.3 is neither approved nor implemented |

---

## F-LOG-0013 — DD-1…DD-4 architecture review against the frozen KnowledgeOS Research Architecture v1.2: IMPLEMENTATION BLOCKED (no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-25) | the instruction "senior architecture and epistemic review of DD-1 through DD-4 … design review only", plus the human-placed files `prompts/prompt_2.md` (sha256 `33f650f3c084211a079dffe77f2418ae650243d40b68ee95d0ffc4f3eb76caee`, conformance audit) and `prompts/prompt_v1.0.md` (sha256 `bb6393ead08013db106c309dcf8976bf1d6cd372b261c63927675da2f4fdd28a`, a request for a new architecture), with "read" instructions for both |
| Sources read (read-only) | `knowledgeos_theory_chronological_extraction/prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md` (FROZEN v1.2), the Phase-1 Master Protocol `knowledge_os_protocoll.md` (governing-architecture block, role and scope binding, §4, §4A, §5A, §9A outline), the Step-2 protocol (ladder §1, origins §5C.2), `KNOWLEDGEOS-RESEARCH-STATE.md`, the `governance/` listing, the Phase-1 FILE-REGISTRY / TDI (40 files; no overlap with the F-Series population) |
| `prompt_v1.0.md` | superseded by `prompt_2.md` (later, and explicit: "DO NOT CREATE A NEW RESEARCH ARCHITECTURE") and by the instruction; its deliverable is **not** produced |
| Output | `prompts/F-SERIES-v1.3-DD1-DD4-ARCHITECTURE-REVIEW.md` · sha256 `cbb58339ce8cb785872f75a18cfedfb43dc03f495c3eba3165619c1d39c9a613` (includes Annex A = the `prompt_2.md` audit A–N) |
| Principal finding | F-Series v1.0–v1.3 is a **second Phase-1 protocol** (own records, gates, registry, state, control plane; no reference to the architecture or the Master Protocol) that performs **Phase-2 work** (hypotheses, test planning) inside Phase 1; its level names collide with the architecture's L0…L5 ladder (RA-9); it duplicates the control plane (RA-16 names this an ACL-3 violation) and the execution-position record (RA-11); F3082–F3108 are outside the canonical F-ID registry (MP §4); TDI / P1-Q1, scope (ACL-4), origin labels and RA-15 statuses are missing. The integrity mechanisms are judged valuable and conformant in spirit |
| DD analysis (not resolved) | DD-1 → recommend a correction as an MP §5A revision triggered by a request, with a blind re-extractor, not a REOPENED state · DD-2 → L1-ACCEPTED as a state is an epistemic category error (collapses RA-15 statuses); recommend status fields + RRP membership · DD-3 → the r2 assumption does not hold (new-version authorization ≠ reuse of prior conclusions); L2/L3 re-binding is Phase-2 work · DD-4 → GO as a GOV record (RA-16) for a named scope |
| Decisions opened for the human | H-1 F-Series role (R-A integrity layer for the existing Phase-1 protocol / R-B separate protocol via architecture change / R-C retire) · H-2 authority model · H-3 identity of F3082–F3108 · H-4 look-ahead vs MP §0E · H-5…H-8 DD-1…DD-4 · carried: HDR-1, HDR-6 |
| State | unchanged — no code, no v1.3 implementation, v1.2 frozen and not approved, F3082 READ-COMPLETE; the frozen architecture, the Master Protocol and Step 2 are untouched |

---

## F-LOG-0014 — H-0 verification and Master-Protocol refactor map (evidence; no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-25) | commission "H-0 GOVERNING-PROTOCOL VERIFICATION → F-SERIES REFACTOR MAP", following a senior review that agreed with the F-LOG-0013 diagnosis and asked for the mapping before H-1. Instruction: research/design audit only; no implementation; H-1…H-8 stay open |
| Sources read in full | ARCH v1.2 (492 lines), the Phase-1 Master Protocol `knowledge_os_protocoll.md` (4,721 lines), S-Series v3.5 `prompt3-optimized.md` (515 lines). Hashes were checked before and after reading; unchanged |
| Output | `prompts/F-SERIES-v1.3-MASTER-PROTOCOL-REFACTOR-MAP.md` · sha256 `3ff6d89cdfbf1525439ebc672cd06c04b3e06455bf1d983372a790980cc7b037` · commit `fce30354e` |
| Output location | written in the lane, not at the commissioned `chronological-read/prompts/` path. That path is the read-only S lane, and MP L4290 forbids writing there |
| Findings | **H-0 = CONFIRMED (a):** `knowledge_os_protocoll.md` governs Phase 1. v3.5 is inherited methodology; its output is the frozen P3A baseline. **R-A: CONDITIONAL** (0 mandatory MP amendments). 78 artifacts mapped. **1,496 of 1,523 F-IDs are canonical `file_id`s;** only F3082–F3108 are new. This corrects F-LOG-0013's "second registry" claim |
| R14 | the orchestrator read F3082 directly. This is not a violation under MP (which has no orchestrator rule), but it is an unwaived omission from the F-Series v1.0 inheritance matrix. **No deviation record was written**, because no existing rule requires one; its standing is **H-12** |
| New human decisions opened | H-10 (output location) · H-11 (population/order) · H-12 (F3082 read standing; R14 record) · H-13 (duplicates) |
| State | unchanged. No code; v1.2 frozen and not approved; F3082 READ-COMPLETE |

---

## F-LOG-0015 — v1.3-R Master-Protocol-conformant design submitted for review (design only; no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-25) | commission "F-SERIES v1.3-R — MASTER-PROTOCOL-CONFORMANT DESIGN": re-express F-Series as an execution/assurance layer for MP; design only |
| Output | `prompts/F-SERIES-v1.3-R-DESIGN-FOR-REVIEW.md` · sha256 `60bb3b8710f759458e849c44ea3aa723799587feddc29df50f62553b75dc28ba` · commit `d41c68439` |
| Content | MP owns the semantics; F owns execution assurance. No `L1-ACCEPTED`, no F L1/L2/L3 ladder, no F registry, state authority or control plane. 15 invariants; the conformance test matrix; the migration map |
| Findings | the governing lane's own evidence binding (`evidence/`) exists but is non-authoritative (B-15), so a new blocker **H-14** applies to v1.3-R itself. The ORIGIN axis `[C]/[S]/[E]/[T]` is Phase-2; the design assigned it at the handoff. Gate names differ from MP §9A (P-5) |
| Verdict in the document | architecture and MP conformity **CONDITIONALLY CONFORMANT**; implementation and F3082 **BLOCKED** |
| State | unchanged |

---

## F-LOG-0016 — Authority, Ownership & Conformance Review, with the four-authority matrix (evidence; no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-25) | (1) *"yes, write the authority and ownership review"*, with ten added items; (2) *"yes, add the four-role columns to the matrix"*; (3) *"Complete the ownership matrix before any human governance decision … evidence-preparation task only"* (DEFINE / CHANGE / APPROVE / EXECUTE, evidence tags, UNRESOLVED where unsupported) |
| Output | `prompts/F-SERIES-v1.3-R-AUTHORITY-OWNERSHIP-CONFORMANCE-REVIEW.md` · sha256 `9e8860374657308a5fce75770889abc13713db51c8e43ac372f174a14edd1e81` · commits `aef07a856`, `a8da402ab`, `73484b055` |
| Sources newly read | the governing lane's `architecture/research-control-architecture.md` (RCA, **PROPOSED**), `governance/L0-DECISION-RECORD-01.md` (in full), the GIA decision package, `SESSION-RECORD-RCI.md`, `evidence/README.md` |
| Findings | central question answered **YES for 15 of 31 items only**. The other 16: 3 semantic, 6 operational needing a decision, 7 governance-owned or governance-proposed (RCA increments 1b/2/3; binding L0-DEC-15/18). The disposition vocabulary fails the protocol-inside-protocol test. **No source gives F-Series DEFINE or APPROVE authority;** the definer is UNRESOLVED on 13 rows |
| Corrections to earlier F documents | **GI-1:** no L0 act approves ARCH v1.2 Addendum B (RA-13…16), nor v1.0 or v1.1, so RA-13…16 citations must carry GI-1. **The operative block is L0-DEC-30**, not the STATE HOLD. The §5A mechanics m1/m2 are reserved for L0 (GI-3). F3082 is not the next file (H-3, H-11); the canonical next candidate is F0041 |
| New human decisions opened | H-14a (DEFINE / CHANGE / APPROVE / EXECUTE of execution-assurance machinery) · H-14b = GIA-9 · H-15 (transcribe F-LOG rulings into the L0 record) · H-16 (coverage granularity; operational items) · H-17 (TDI reference set) · H-18 (translation-contract ownership) |
| State | unchanged |

---

## F-LOG-0017 — Governance decision dependency and sequencing package (evidence; no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-25) | commission `prompts/F-SERIES-GOVERNANCE-DECISION-DEPENDENCY-AND-SEQUENCING.md`: *"What must the human/governance lane decide, in what order, and which later decisions depend on those decisions?"* Evidence only; no recommendation |
| Output | `prompts/F-SERIES-GOVERNANCE-DECISION-DEPENDENCY-AND-SEQUENCING.md` · sha256 `dcf51433dca282a96f04942ded6fa97a69366439ef66152fe8c3b7db575a3bc4` · commit `8a593b39a` |
| Findings | **1b is a binding prerequisite for C-5**, and so for chronological F0041+ progression (L0-DEC-05 C4-b; SAFE-RESEARCH-EXCEPTION-01). Whether 1b is required before **any** new read is **not established**. **The H-14a / RC-H-05 order is not established;** three orders are shown and none selected. 1b starts with an independent review (GIA §8) whose performer is unnamed. The RCA's "H-1" / "H-10" (assessment items) collide with the F-Series H-numbers |
| Evidence gaps (not decisions) | E-1 who writes `RECONSTRUCTION-STATE.json` · E-2 who owns an audit of research output · E-3 who performs the 1b review · E-4 who decides H-16 / H-17 · E-5 the H-14a / RC-H-05 order · the unattested L0 channel |
| Senior review (human-relayed, 2026-09-25) | stage treated as **complete**; the next activity moves to the human governance lane. *"Do not let E-1–E-5 silently become decisions by implementation convention."* AI note recorded: closing E-3 is itself an authority assignment for the governance lane; E-4 concerns increment 2's coverage granularity, not 1b |
| Also written (human instruction *"note them as F-series-todos.md in .claude/ and link them to your session"*) | `.claude/F-series-todos.md`, linked from `.claude/sessions/2026-09-25.md` (commits `3fad2811f`, `8a593b39a`) |
| State | unchanged. Nothing implemented; no corpus file read; every H-item open |

---

## F-LOG-0018 — Human governance decision session package (evidence-preparation artifact only; no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-25) | commission "F-SERIES — HUMAN GOVERNANCE DECISION SESSION PACKAGE": prepare the existing evidence so the human can hold the next governance decision session without Claude, F-Series, implementation convention or an unstated assumption making a governance decision. No decision, recommendation, ranking or ownership choice |
| Output | `prompts/F-SERIES-HUMAN-GOVERNANCE-DECISION-SESSION-PACKAGE.md` · sha256 `b6bbd69a98d8e8b88eccd4694e6d8d46f212bf059f7d19cc965b02cf7cb11eab` · commit `1c2b45d5f` |
| Kind | **evidence-preparation artifact only.** No new analysis; no new source read |
| Content | executive status (S1–S11) · decision agenda (unanswered; includes HDR-2…HDR-5, still open per F-LOG-0010/0011) · H-1 options R-A/R-B/R-C · H-14a by DEFINE/CHANGE/APPROVE/EXECUTE · the five documented branches · RC-H-05 ordering UNRESOLVED · the eight 1b states · evidence gaps E-1…E-5 + attestation · prerequisites · what can be placed before the human · what must wait · a blank session agenda |
| Decisions | **NONE** |
| State | unchanged. Nothing implemented; no corpus read; F0041 and F3082 blocked; the Research Release Check (L0-DEC-27) remains the release mechanism |

---

## F-LOG-0019 — KnowledgeOS scientific research architecture optimization (design only; no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-25) | commission "KNOWLEDGEOS — SCIENTIFIC RESEARCH ARCHITECTURE OPTIMIZATION": the minimum coherent scientific architecture from corpus to falsifiable, validated theory candidates. Design only; no F-Series redesign; no governance decision |
| Output | `prompts/KNOWLEDGEOS-SCIENTIFIC-RESEARCH-ARCHITECTURE-OPTIMIZATION.md` · sha256 `3a00af9d2678a92502b8b1c88f3fd34afd278a100593ffc34b1e927305f50c95` · commit `294d22b31` |
| Status of the output | **PROPOSED, authority: generated.** Subordinate to ARCH; adds no bounded context, level or authority |
| Deviations from the commission, exposed | (1) the commissioned L0–L6 would collide with S2's L0–L5 (RA-9 / Q56), so the layers are mapped as **SA-0…SA-6** onto the existing axes (level, F-stage, maturity, origin, provenance class) and never stored as a level. (2) The eight "contexts" are modules inside ARCH's two bounded contexts plus the control plane, because ARCH §8B rejected a Validation context. (3) The commissioned relationship labels are S-Series v3.5 vocabulary and are mapped to the MP §7 / §17 vocabularies. (4) The "existing synthetic benchmark research" is **corpus material** and was **not read** (L0-DEC-30; OQ-11) |
| Findings | most of the commissioned architecture already exists in S2/MP (ACL-3 search, §0 of the document). Genuinely missing: hold-outs, the hypothesis-freeze record, active learning, the multi-clock temporal model, the logic pathway, multiplicity / repeated-look control, benchmark design rules. The central question is answered: MP + S2 + freeze + one disjoint hold-out + an executed falsifier test with an independent reader. No hypothesis has reached F2 yet (S2 L1759) |
| Decisions | **NONE.** All S2 / MP additions are candidate protocol changes for L0 |
| State | unchanged. No implementation; no corpus read; F0041 / F3082 blocked |

---

## F-LOG-0020 — KnowledgeOS minimum scientific research cycle (design minimization; no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-25) | commission "KNOWLEDGEOS — MINIMUM SCIENTIFIC RESEARCH CYCLE": review the architecture-optimization document and find the smallest architecture that can demonstrate one complete falsifiable cycle. No new ladder, context, gate, control plane or protocol |
| Output | `prompts/KNOWLEDGEOS-MINIMUM-SCIENTIFIC-RESEARCH-CYCLE.md` · sha256 `69ea52a41bedd672a7234a50a11df6ef2f7184ebdde49ddb6b6882a609a8da8f` · commit `df9a29476` |
| Findings | **Two first cycles exist.** C-M, the method-validation cycle on synthetic worlds, is designable now: no corpus, no amendment required, blocked only by the release scope (L0-DEC-30 / L0-DEC-27). C-H, a real-hypothesis cycle, is blocked: no F2 hypothesis exists, the temporal hold-out needs corpus reading, and an independent reader is needed. Of the 20 OPT mechanisms, 9 are needed for C-M and 11 are deferred. First model: a lexical baseline. No logic tool is needed for C-M. The HMAC(freeze_hash) seed commitment makes the test worlds unknowable before the freeze |
| Amendments identified (not made) | mandatory freeze fields in S2; the seed-commitment rule as an S2 rule. Both `AMENDMENT_REQUIRED — HUMAN/GOVERNANCE DECISION`, and optional for C-M |
| Decisions | **NONE** |
| State | unchanged. No implementation; no corpus read |

---

## F-LOG-0021 — C-M cycle design corrected after senior review (design only; no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-26) | a relayed senior review of `KNOWLEDGEOS-MINIMUM-SCIENTIFIC-RESEARCH-CYCLE.md`: minimization accepted; "freeze the architecture-minimization discussion here"; three corrections requested — (1) recall is undefined in null worlds, so repair H0 and the metrics per world type; (2) METHOD_VALIDATED must carry its scope, with no new vocabulary; (3) C-M tests the composite procedure, not the detector |
| Output | the same file, revised · sha256 `5cd788388a17f0ba26db0b5c4cb236d47067fe4c01bd6db4e611a21547d83d38` · commit `139b0bae8` (previous `df9a29476`) |
| Changes | H0 split into a matched-null comparison for positive worlds and a p-value-uniformity / reported-edge-rate check for null worlds; metrics listed per world type (positive / null / confounded / corrupted); a `validation_scope` inseparable from METHOD_VALIDATED; the composite-procedure reading; **added:** thresholds must be justified by a stated requirement, not by dev performance; independent verification reproduces the computation, not the adequacy of the specification |
| Not done | the C-M Registration & Falsification Review (a new artifact; not commissioned) |
| Decisions | **NONE** |
| State | unchanged. No implementation; no corpus read |

---

## F-LOG-0022 — C-M Registration & Falsification Review (PROPOSED registration; not frozen, not executed; no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-26) | *"write the C-M Registration & Falsification Review"* |
| Output | `prompts/KNOWLEDGEOS-C-M-REGISTRATION-AND-FALSIFICATION-REVIEW.md` · sha256 `a6929e550cca9ff75ca57dd79544169a6ee35d5595d7802532d69aa4ae237d88` · commits `a5306c8da`, `facd9cc55` |
| Content | the stated use requirement R-USE (recall ≥ 0.80; FDR ≤ 0.20; null P(any edge) ≤ q\* = 0.10; no unidentifiable direction), set before any run · generator G_θ and the unseen K = 50 setting · the hypergeometric + BH detector and scorer · 8 cells × N = 400 worlds (worst-case precision, not D-based) · HMAC(freeze_hash) test seeds · a 13-criterion Bonferroni family with a three-way decision rule · verifier requirements V-min / V-gen with the METHOD_VALIDATED rule · 12 attacks on the registration · outcome templates fixed in advance |
| Corrections found | X-1: the Design's confounded pair was not Markov-equivalent. **Corrected in the Design** to chain u→v→w vs fork u←v→w. X-2: permutation p-values cannot pass BH at a practical B (they need B ≥ 4,349 for K = 30), so hypergeometric p-values are used. X-3: the null criterion is derived from BH's own claim. X-4: date corruption is removed from C-M |
| Still needed before the freeze | human review of the R-USE numbers and parameters; a release scope covering C-M (L0-DEC-27/30); implementation of G, D and the scorer, with the generator self-tests; the verifier class; a hypothesis-id namespace |
| Decisions | **NONE** |
| State | nothing frozen, generated, coded or read |

---

## F-LOG-0023 — C-M final adversarial review (design only; supersedes the C-M registration in part; no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-26) | commission "KNOWLEDGEOS — FINAL C-M SCIENTIFIC ADVERSARIAL REVIEW": shrink C-M to the smallest defensible method-validation experiment; no extension, implementation or execution |
| Output | `prompts/KNOWLEDGEOS-C-M-FINAL-ADVERSARIAL-REVIEW.md` · sha256 `5bb7054aaa43a56b46936f2c90e9d59f61c557595ea36774a520a8025b7c2c55` · commit `1380be1cc`; supersession pointers added to the registration and the Design |
| Findings (generator arithmetic only; detector never run) | **K-1:** the registered positive regime has per-edge z ≈ 2.47, below the BH first-threshold boundary z ≈ 3.50 (m = 435), so it would REFUTE for regime reasons. **K-2:** chain vs fork is not observationally equivalent under the child-centred renderer (marginals 1.7 / 1.7 / 1 vs 1 / 2.4 / 1). The earlier Markov-equivalence correction was wrong |
| Replacements | a skeleton renderer (the orientation enters only through lineage); an NI cell with s = 0, non-identifiable **by construction**; an exact fixed-margin independent-placement null (hypergeometric exact; BH-under-dependence tested empirically); θ\* set by a stated signal target (design z ≈ 5.06, declared easy) |
| Shrink | 13 criteria → **3 claims** (C1 calibration, C2 recovery, C3 robustness; 5 components) + a deterministic NI check; survival by intersection-union (no adjustment), refutation Bonferroni over 5; N 3,200 → **850** (200 / 200 / 400 / 50), derived from δ = 0.05 at power 0.8 with a pre-freeze σ check; the U cells dropped (D0 has no fitted parameters) |
| Bottleneck restated | a genuine F2 KnowledgeOS hypothesis. C-M is optional method infrastructure, not a prerequisite for theory discovery |
| Decisions | **NONE** |
| State | nothing frozen, generated, coded, run or read |

---

## F-LOG-0024 — F2 acceleration pass (analysis only; no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-26) | *"read the analysis and follow the prompt written in middle"*: the "KNOWLEDGEOS — F2 ACCELERATION PASS" prompt; then *"Continue to work for next step as senior researcher and mathematician, statistician and ddd architect and expert of computer logic and theory of logic."* |
| Output | `prompts/KNOWLEDGEOS-F2-ACCELERATION-PASS.md` · sha256 `395a5c2aa83bb693827e5bcba89f8eb204c31a0bcb3a867200c691a6aec75ce0` · commit `34844193b` |
| Material read | recorded research artifacts only: SENIOR-RESEARCHER-BASELINE-01, SENIOR-RESEARCHER-CRITICAL-ATTACK-01, THEORY-OBJECTS rows, the F0018 reconstruction record, S2 §9.1. **No corpus file** |
| Findings | CAP-01 was already executed (`ab9bcdd04`), so the L0-DEC-30 scope is spent and further scope needs a new release. [E]-03/04 are registry claims, not KnowledgeOS. **H-F2-1**, a bar-agnostic formalization of T-0013 × T-0014 × T-0056 (axioms A0–A5, derived D1–D4, typed falsifiers), has the shortest documented F2 closure path. The CAP-01 minimum independence definition admits common-cause dependence (deductive counterexample); the ancestry-disjointness closure is proposed |
| Blockers | recording needs a new release; test T-A (fidelity, re-read of F0018 and the schemas) needs a corpus-read release; test T-B (out-of-sample) needs corpus reading beyond current state (C-7 for chronological progression). C-M is **not** required |
| Decisions | **NONE.** H-F2-1 is an analysis-level [E] proposal, not a Phase-2 record, not an F-stage promotion |
| State | no corpus read; nothing written to the governing lane |

---

## F-LOG-0025 — H-F2-1 formal logic & minimality attack (analysis with a finite-state instrument; no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-26) | *"read the analysis and follow the prompt"*: the "KNOWLEDGEOS — H-F2-1 FORMAL LOGIC & MINIMALITY ATTACK" prompt, plus the instruction to continue as senior researcher, mathematician, statistician, DDD architect and logic expert |
| Output | `prompts/KNOWLEDGEOS-H-F2-1-FORMAL-LOGIC-MINIMALITY-ATTACK.md` · sha256 `d73cbe0cde69ad36610dee03da771deb7d862c94f0b08566fcfea30984a9d6fa` · commit `8a180ffd7`. Instrument: `analysis/h_f2_1/model.py` (sha256 `ed931840430c1a27286baa3d81e68f4d031d2a0461cee31e53dc92b0abc0510f`); output `analysis/h_f2_1/results.json` (sha256 `f7b758483726810695639adcbec547528df1aba425277d59ac6c240deb408660`) |
| Findings | exhaustive finite-state verification on chain3 / V / diamond / antichain2: no countermodel under the full axiom set, and non-vacuous except the antichain control. **Minimal sets:** D1 {A2e, A6}; D2 {A3g, A5g}; D3 {A2e, A3g, A4, A5e, A5g, A6}; D3+ and D5 add A0 and A3m; D6 {A1}. **A0 is not needed for the core claim.** A hidden **bar-constancy** assumption (A6) is necessary. Standing locality (A3s, A5s) is inert. D6 ≡ A1. The F2 pass's "36 states" is corrected to 288 once the bar is part of the state |
| Classification | **F2-FORMAL-CANDIDATE** for the revised **H-F2-1-R** (core H-F2-1a; stability extension; separate authority candidate H-F2-1b), with low deductive depth explicitly stated. Wording corrected: an [E]-derived candidate formalization, not an established corpus claim |
| Open, not decided | H-6 (promotion as state vs event); g ≡ s identification; A6's two-level (object vs rule-change) reading |
| Decisions | **NONE.** No release requested; no Phase-2 record; no corpus read; F0018 not re-read |

## F-LOG-0026 — H-F2-1-R scientific closure pass (verification + soundness audit; no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-26) | the "H-F2-1-R SCIENTIFIC CLOSURE PASS" prompt (deliverables A–D), plus the instruction to continue as senior researcher / mathematician / logician, *"also use your expertise in machine learning techniques. Keep optimizing the final goal, architecture and todos"* |
| Output | `prompts/KNOWLEDGEOS-H-F2-1-R-SCIENTIFIC-CLOSURE-PASS.md` · sha256 `58a629f5abf5e542a1a5ecaba2170e6b4663f52c7accd93eb00bd304b76f2e78` |
| Verifier | `analysis/h_f2_1_verifier/verifier.py` (sha256 `54d321003a2e3680d4b65f265bd3cbd29a31e68d5726c4f143bb9d38600cbdc7`) → `verifier_results.json` (sha256 `372cf3e745950ab527dacb5e4356d526e005a2dcfd9805ee5f57ccb91d913c41`). Built from the results-free `SPEC.md` only. Independence class: **SECONDARY_REVIEW** |
| Findings | **Mechanical agreement** on every truth value, single-removal result and minimal set. Antichain D3 has two minimal sets: a degenerate (vacuous) control. **State-count correction:** 144/180/288 admissible under A0, not 288/288/768; a correction note was appended to the attack document (sha256 now `b0cc92d1…`). "R_max is exact" is **proved** (Lemmas 1–3) under C-1…C-5. Necessity is computed on 3 instances and sketched in general |
| Status | LOGICAL: **QUALIFIED** · COMPUTATIONAL: **REPRODUCED** (secondary) · EMPIRICAL: **UNTESTED** · H-F2-1a: F2-FORMAL-CANDIDATE · H-F2-1b: CONDITIONAL |
| Proposed (not applied) | an axiom → proposition map and the "NV must hold" rule, to be folded into the T-A pre-registration **at HD-1** |
| Decisions | **NONE.** No release requested; no corpus read; H-F2-1-R and the pre-registration unchanged |

## F-LOG-0027 — closure pass re-issued: conformance check and T-A pre-registration REVIEW CANDIDATE r1 (no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-26) | a senior assessment plus the same "H-F2-1-R SCIENTIFIC CLOSURE PASS" prompt: *"analyse and follow the prompt. Continue to work for next step as senior researcher …"* |
| Action | a clause-by-clause conformance check of the existing deliverables (closure report §7). No deliverable redone. Pre-registration revised r0 → **r1**, additive only: §5.1 (axiom → proposition map; UNSUPPORTED vs FALSIFIED; NV re-check rule), §4 (two fields), §6 (R4 LLM-assisted retrieval + recording rule), §9 (revision record) |
| Outputs | pre-registration r1 sha256 `194beacb933ce2dafe5e355807ebe018e24a4c06e8161c3a5934c9c8d0c30ecd` · closure report sha256 `baee595abc01088eceed7b73fba37f368062ab7556ab31db10a73be36bfbb5f2` |
| Recorded deviation | the verifier was built from the results-free `SPEC.md`, not from the attack document (which contains the results). This is stricter than the prompt's §2 |
| Not done, by authority | "Claude freezes the T-A preregistration" (senior assessment): freezing is HD-1. Claude only fixed the content and recorded the hash |
| Decisions | **NONE.** No corpus read; no release requested; H-F2-1-R unchanged |

## F-LOG-0028 — T-A execution readiness & countermodel safety pass (NOT READY; STOP reported; no decision recorded)

| Field | Value |
|---|---|
| Human input (2026-09-26) | a senior assessment plus the "T-A EXECUTION READINESS & COUNTERMODEL SAFETY PASS" prompt; *"update the F-SESSION-LOG and F-GOVERNANCE-LOG … analyse the discussion and follow the prompt"* |
| Output | `prompts/KNOWLEDGEOS-T-A-EXECUTION-READINESS-SAFETY-PASS.md` · sha256 `e91d774b19f859a066bf2a2a963b843ac903f30b65b3d519d55f01ecc32dd95d`. Pre-registration r1 → **r2** sha256 `a1640c3a657c370efa7814d8240f9f4861f9926f543fbe9700cecd8424ce23dc`. `analysis/t_a/aggregate.py` (sha256 `f0d836e896dfee58b0809300044d3662ef54de32aff70fe323fe34d9ee64ef71`) + `test_aggregate.py` (sha256 `906e049455dfa5250f087561a1b2646fae6ec1f059e6a62a806f6e59e579e601`); 18/18 pass on synthetic records |
| **STOP reported** | axiom **scope** and **kind assignment** are undefined. §3 mixes "universal" with "for that mechanism"; there is no kind-assignment rule; H-1 grants a splitting licence. Counterexamples could be absorbed by relabelling or splitting, so the axioms would be unfalsifiable. **Not fixed**; flagged PENDING HD-S. Options S-U/S-C/S-N × K-A/K-D × SP-S/SP-R. Recommendation (advisory only): S-U + K-A + SP-S |
| Corrections (r2) | A6 consequence: "two-level model required" → "A6 refuted as formulated; replacement UNRESOLVED" (commissioned). §5.0 precedence verdict (the r1 rows overlapped; "dominant" was undefined). PARTIALLY UNTESTED row (a gap). A0/A3m row aligned with UNSUPPORTED ≠ false. Prediction match computed after classification. M-1 manifest-pin rule. Resolved versions. Schema fields. §10 checklist |
| Metadata used | git commit ids and dates per path; git status; blob ids; the byte hash of F0018 against the manifest pin. **No content viewed.** Fact: ES-006's 7-commit history precedes F0018; 0 post-date commits for M-2…M-4 |
| Status | T-A **NOT READY** · pre-registration REVISED · A6 RULE CORRECTED · logical safety ISSUE · temporal / ML / DDD PASS |
| Decisions | **NONE.** No freeze, no release request, no corpus read; H-F2-1-R unchanged. Next human decision: **HD-S** |

## F-LOG-0029 — T-A operation-typing methodology review (HD-S partial; no decision record written by Claude)

| Field | Value |
|---|---|
| Human input (2026-09-26) | *"S-U + K-A + SP-S for all six"*, together with a senior assessment and prompt (*"do NOT immediately freeze … K-A"*) and *"analyse the discussion and follow the prompt"* |
| Reading | the human's own words both state K-A and direct following the prompt that defers it. Logged: **S-U and SP-S stated by the human; K-A stated, not frozen.** Claude wrote no HUMAN-DECISION record |
| Output | `prompts/KNOWLEDGEOS-T-A-OPERATION-TYPING-METHODOLOGY-REVIEW.md` · sha256 `e18509724292af3aa12565dc0df6a82a73376b234ffe8d7a90afb1477c89dc64` |
| Findings | K-A biases toward refutation (actor ≠ operation type). K-S is preferable **only with O-1**: the object never decides the kind when it is e/g/u/s. UNKNOWN is epistemic, so no formal change and no three-valued logic. A0, A1 and A6 are kind-free, so F-A6 is immune to typing failure. D3 and D3+ are conditional on typing totality (H-1′), read from the existing −A4 ablation. The senior's counterexample clause (4) is **unsound as phrased** (an escape clause) and is replaced by the procedural (4′). UNKNOWN is resolved by enumeration inside the existing AMBIGUOUS class, with no new outcome |
| Proposed (not applied) | r3 changes D-1…D-7 (including a pre-registered action lexicon) and a declarative note on the attack document |
| Status | T-A NOT READY · HD-S PARTIAL · pre-registration revision YES · formal revision YES (declarative only) · corpus reading NO |
| Decisions | **NONE.** Next human decision: kind basis K-A vs K-S + O-1, plus the lexicon |

## F-LOG-0030 — HD-S closure: pre-registration r3 (S-U + K-S + O-1 + SP-S; advisory lexicon); T-A content-final for HD-1 (no decision record written by Claude)

| Field | Value |
|---|---|
| Human input (2026-09-26) | *"K-S + O-1, lexicon as proposed"*, with a senior prompt (the lexicon is advisory only, never verb → type) and *"follow the prompt instructions"*. Earlier the same day: *"S-U + K-A + SP-S for all six"* (K-A superseded by this input) |
| Reading | "lexicon as proposed" = the §D-3 list, used as **advisory candidate generation** per the endorsed prompt; not a typing basis |
| Output | pre-registration **r3** sha256 `be16deb7af88d1c87072566c0e1c1feb43258cf2e84e6d707107bedc6a617133` (§3.0 operation typing and scope; §4 operation/effect blocks; §5 S-U wording; §10 steps 3/6/8/9a; §9 r3 rows). `analysis/t_a/aggregate.py` sha256 `13532a5bd4123514ab1cf92d2a057a64c8bab20bdca5b2cf59e43febc2d8c7b9`; `test_aggregate.py` sha256 `567e965c809aff6d183e76f2c3e56180b31b56ba5ae52637dee7a1e746fb6b45`. **32/32 synthetic tests pass.** Attack-document declarative note (H-1′; kind-free axioms), attack document now sha256 `f85162a9219f2f9ae2e7b41edef21259030f02004047f218291cf466bd49b2fe` |
| Formal impact | none. `model.py` → `results.json` and `verifier.py` → `verifier_results.json` are byte-identical on re-run |
| Quality gate | no effect, actor, object or lexicon typing basis (rejected in code) · no reader splitting (rejected) · UNKNOWN never auto-support or auto-counterexample (tested) · A6 testable on UNKNOWN operations (tested) · counterexample requires a matching kind and a stated transition (tested) · no ML · no inferential statistics · no corpus content read |
| Process note | one docstring line of `aggregate.py` was changed with `sed -i` (exact-match, verified by grep). That breaks the repository's manual-editing rule for source code; recorded here rather than hidden |
| Status | T-A **READY for HD-1** (content-final; not frozen) |
| Decisions | **NONE** by Claude. Next human decision: **HD-1** — freeze r3 at sha256 `be16deb7…7133` |

## F-LOG-0031 — HD-1 given by the human (r3 frozen); release preparation for HD-2/HD-3/HD-4 (prepared, not requested)

| Field | Value |
|---|---|
| **Human act (verbatim, 2026-09-26)** | *"Freeze r3 at be16deb7…7133."* — **HD-1**. It freezes `prompts/KNOWLEDGEOS-H-F2-1-R-T-A-PREREGISTRATION.md` at sha256 `be16deb7af88d1c87072566c0e1c1feb43258cf2e84e6d707107bedc6a617133` (commit `cfcee7bb0`). Logged here as a governance **event**, quoting the human; Claude authored no decision record and set no decision switch |
| Verification | working tree = HEAD = frozen hash; lane clean; instruments byte-identical on re-run; 32/32 synthetic tests |
| Output | `prompts/KNOWLEDGEOS-T-A-RELEASE-PREPARATION.md` · sha256 `df2870ec0c3048adc05e6750f7a82e0faddfb1c6155a99130c3e4a21815a0a63` |
| **Erratum (frozen text not changed)** | r3 §2.1 names ES-006's history as `aee484e9c`…`668cc7b22` (7 commits). The true first commit is **`d63202b8c`** (path `engineering/governance/ES-006-Knowledge.md`; renamed R071 at `aee484e9c`). Cause: `git log --follow --reverse` truncation. The binding §2.1 rule ("full revision history") covers all 7. **For HD-3 to confirm** |
| M-4 objects (full) | d63202b8c 2b19210931de42a2e86e16afbf2354fcdb23f2a4 7205c52d8b13090abbb51b788d08516bd4b1e2c514c2b5ab8cdde79f87327530 · aee484e9c d8422b5abe6ccf2980812b0c2456e1cbc461b3d0 d23d8b53865c9c5bdde412274c3f09b3e34d1bf8ecf69beb7e2396f8b795af9c · da565a213 23941312d063af7a87fee078eefa51632739dd0f 25fcd0048e45cc43b396e895f7646d403041172d9d9f62315e19bd2e5f5fffb0 · c71f7d689 791ad506742647df8064845884163192c2e5abb1 facb576d3637df66c2e464a5e54affe7666e39e15e38e99132e1e0a6d0b97bda · 8d1df4b1d b07ec342c3372e605e6c1527e85df60caaaa0a56 170f344d6eb0efe52faa729fc28fcc06a19cd73ced0a63f3a8cbe6007c4065b2 · 43682264d 71e8a451810109c4b1d0200aefeb7b12a9e6ff7f 12287296507530a600724018d1d8177053f967fbfe61d596ed8130fb240b6e6c · 668cc7b22 a68a2eee838e08a3cec35fd743a80d1bc2f1e598 349b7d5d5e2df4ffea052c06c35e2c2d5503c22ffd79654946ad671d2f0c42a0 |
| M-1 object | d61bf5e84 71edaebc344acd6f47181b8a97c0cced501b6fe6; sha256 b685f5991338f24df135a50bd7d004999d62401bdac10f9c0ef8241ecbbed7bc = the manifest pin |
| Process exception | the `sed -i` docstring hunk: reconciled by record (located, explained, diff shown, verified). No file change; Edit only from now on |
| HD-4 preparation | Claude = SELF; a Claude-commissioned subagent = SECONDARY_REVIEW; INDEPENDENT = commissioned by the human, with a reader packet (r3 minus §3.2) |
| Decisions | **HD-1 (human).** No release requested; no corpus read. Remaining: HD-2, HD-3 (+ erratum confirmation), HD-4 |

## F-LOG-0032 — HD-2/HD-3/HD-4 recorded as given; execution gated on the Research Release Check; blind reader packet built (no corpus read)

| Field | Value |
|---|---|
| Human input (2026-09-26) | *"Continue to work for next step … follow the prompts."* It adopts a senior prompt ("HUMAN DECISIONS — RECORD EXACTLY") |
| Recorded as given | **HD-2:** *"RELEASE M-1 / F0018 through the Research Release Check"* (release intent, conditional on the check). **HD-3:** erratum confirmed (*"full revision history" governs*, so the history starts at `d63202b8c`); M-2 and M-3 not released (Q-GS, Q-D4 NOT_RUN). The **OQ-6 scope decision is NOT given**: the prompt says *"If scope is IN"*; the senior commentary only recommends IN. **HD-4:** *"SELF + INDEPENDENT READER"*, the independent reader to be commissioned by the human |
| Why no read | L0-DEC-27: the Research Release Check is run by the governance session and *"L0 decides the release on that result"*. No check exists for T-A; L0-DEC-30's scope is spent. The research session may not certify its own release or write in the governance lane |
| Output | `prompts/KNOWLEDGEOS-T-A-EXECUTION-GATE-STATUS.md` · sha256 `c0a514bb9374b7e9c13897388d43917cc330d56ce8fbbdfc6cd6236463785c29` (with lane-side input for the check). Reader packet: `analysis/t_a/build_reader_packet.py` (sha256 `bc2fa3bd422eeaaebab9085a634018ff666a9dabdecd04250b9a0a9d1b4a911c`) → `reader_packet/`: PREREGISTRATION-r3-BLIND.md `104defaad9092f87638b798d814b4d87551b0ddbe10387b006446ce38db59eb0` · aggregate_blind.py `75a5f0d2c230020ad5d9c6affc81d2d1d36fe1019f7cc33ff1883ca6565482da` · README.md `475c42b86bf3c66f87c6e03221fbaddb5b7e4e378c9ba48a25af42ee0b9bee37` |
| Finding | a prediction leak outside §3.2 (the §5 PARTIALLY UNTESTED row names the A5g prediction). Redacted in the packet; the frozen r3 is untouched |
| Decisions | none by Claude. Open: the Research Release Check (governance session) · the OQ-6 scope for M-4 (L0) · the release record (L0) · commissioning the INDEPENDENT reader (human) |

## F-LOG-0033 — Research Release Check MEASUREMENTS for T-A (read-only; not the check; no decision)

| Field | Value |
|---|---|
| Human input (2026-09-26) | *"run the Research Release Check measurements read-only"* |
| Output | `prompts/KNOWLEDGEOS-T-A-RRC-MEASUREMENTS.md` · sha256 `85b870298942a6281b4954bbed52ce5a6e3905238a2379efa77055ed4395e111` |
| Read-only proof | governance-lane git status delta: none; /tmp residue 0; only non-writing modes used |
| Results | manifest **STALE_OR_CORPUS_CHANGED** (1 entry: **F2800**, untracked, sha256 e140e172… → a3df1871…; F0018 unchanged) · gate-runner CLEAR (KOS-G-020 FAIL, not activated; includes **T-0056**, a source of H-F2-1) · self-test 55/55 · pins 5/5 identical · preflight CLEAR · admit audit: only RC-H-04 · regressions 66/66 and 12/12 · control hashes identical · no commit to the governance-lane prompts/ since 628d02169, but **4 untracked files** there |
| Not assigned | no GREEN / YELLOW / RED. OBS-1 (F2800) and OBS-3 (untracked prompts) are **unclassified**, for the governance session. OBS-2 (T-0056 without a relation record) is for L0's scope decision |
| Independence | measured by the requesting session; the governance session re-runs or accepts |
| Decisions | none |

## F-LOG-0034 — RRC evidence package RRC-T1/T2/T3 for T-A (governance input; no class, no RAG, no option selected)

| Field | Value |
|---|---|
| Human input (2026-09-26) | the pasted "Continue from F-LOG-0033 …" prompt (sent as the answer to Claude's question whether to run it), plus *"Continue to work for next step …"* |
| Output | `prompts/KNOWLEDGEOS-T-A-RRC-EVIDENCE-PACKAGE.md` · sha256 `d8798a46dc92cf6fbfd40546d55b2da032b6d416f7c9e634afa1fb3bc62f6b5e` |
| RRC-T1 | F2800: untracked; e140e172… (2960 B, 2026-09-21) → a3df1871… (3010 B, 2026-09-26 01:12). Outside the T-A scope; 0 receipts; no T-A dependency. Excludable without changing T-A, **if the manifest is not rebuilt**. Draft containment statement provided. Also an **F-Series baseline drift** (F-MANIFEST row 1291) |
| RRC-T2 | T-0056: sources ['F0018'] only; 0 formal relation rows; an untyped `related` list names T-0013 and T-0014; frozen r3 does not name T-0056. A/B/C kept apart. **Fact:** L0-DEC-20 — batch 3 (F0014–F0018) not retroactively certified; all three H-F2-1 sources cite F0018. Options A and B drafted; neither selected |
| RRC-T3 | 4 untracked files (created 2026-09-24, after RRC-01): not release criteria (control files identical); not the reader packet (hash-locked builder); not the committed methodology text. Whether they are proposed methodology changes **requires governance content review** |
| Decisions | none. No corpus or T-0056 statement read; nothing rebuilt or altered |

## F-LOG-0035 — human/L0 governance positions for the T-A release, recorded (not a release; T-A not started)

| Field | Value |
|---|---|
| Human input (2026-09-26) | the pasted "Proceed to the next step using the following HUMAN/L0 GOVERNANCE INPUTS" block (following the human's choice "Wait for the release") |
| Positions, as given | **RRC-T1:** F2800 to be treated as contained R2 *"subject to the governance session recording the exact classification"*, with the containment text as given (no manifest rebuild; no change to F2800 or to F0018's pin). **RRC-T2: Option A**, with a five-point caveat (T-0056 is not a T-A input; T-A tests F0018 and ES-006 directly; no formal relation row; L0-DEC-20 batch 3 uncertified; the limitation is not evidence for or against H-F2-1-R). **RRC-T3:** contain the files as operational *"if governance confirms"* they alter neither r3, the criteria, the packet nor the committed methodology. **OQ-6: ES-006 = IN for T-A**; the 7 objects; the erratum preserved. **RAG:** *"Use the governance session's recorded RRC result verbatim"*; YELLOW only with explicit authorization; RED means STOP. **L0-DEC-31:** *"The human must record the final L0 release decision."* **Readers:** SELF + an INDEPENDENT reader commissioned by the human |
| Verified state (read-only, HEAD `37dd4fe11`) | governance lane: **no L0-DEC-31**; **no Research Release Check after RRC-01** (2026-09-23); last governance-lane commit `40aa72590` |
| Consequence | By the positions' own terms, T-A still needs (1) the governance session's recorded check result, (2) the governance confirmations for RRC-T1 and RRC-T3, and (3) the human-recorded L0-DEC-31. Claude executes nothing until then |
| Decisions | the positions above are the human's. Claude recorded no release and read no corpus |

## F-LOG-0036 — drafts of Research Release Check 02 and L0-DEC-31 for T-A (AI-authored drafts; nothing released or decided)

| Field | Value |
|---|---|
| Human input (2026-09-26) | the pasted "KnowledgeOS — Record Research Release Check and L0-DEC-31" prompt |
| Output | `prompts/KNOWLEDGEOS-T-A-DRAFT-RRC-02-AND-L0-DEC-31.md` · sha256 `350cf0dc0cb21e942980920a487758bc17190c621518d8c995c1341e75021b12` |
| Placement | a **draft in the F-lane**. The AI may not write governance decision records, and the lane-only rule applies. The prompt's path `docs/knowledgeos/governance/L0-DECISION-RECORD-01.md` would have created a second L0 record file (that folder exists but is platform-level and holds no L0 record), forking L0 authority. The drafts name the correct targets in `knowledgeos_theory_chronological_extraction/governance/` |
| State verification | no RRC after RRC-01; no L0-DEC-31; every release hash matches (r3, aggregate.py, packet, F0018, 7 ES-006 objects); F2800 and T-0056 unchanged; control files unchanged |
| Unresolved fields | RRC_RESULT; the five-question governance answers; F2800 class; T-0056 acceptance; the four files' content review and classification; independent reader; L0 name, date and approval reference; T-A AUTHORIZE / DO NOT AUTHORIZE |
| State | **RELEASE PENDING GOVERNANCE DECISION · L0 DECISION PENDING.** T-A not started |

## F-LOG-0037 — T-A governance closure packet: five-layer decision table and independent-reader handoff (preparation; nothing decided)

| Field | Value |
|---|---|
| Human input (2026-09-26) | the pasted "Governance Closure and Controlled T-A Launch" prompt |
| Output | `prompts/KNOWLEDGEOS-T-A-GOVERNANCE-CLOSURE-PACKET.md` · sha256 `5a5e0868037e216e5e191586fac42548c7502a9eee522147a9e8dd36ec48241f` |
| State | RRC-02 absent; L0-DEC-31 absent (HEAD `2f0e2c2f2`) → release pending; §F/§G (integrity check, T-A) not run |
| Finding | **blindness:** the senior reviewer who offered to be the independent reader has seen Claude's reports, including the predictions. Independent in lineage, **not blind**. The handoff requires a **fresh-context** reader |
| Prepared | Q1–Q5 and T1–T3 in five layers; a T3 content-review template (for the governance session; the F-lane did not read the files); the handoff (items by hash, export without the working tree, reader record, sealing protocol) |
| Decisions | none |

## F-LOG-0038 — L0-DEC-31 decision information prepared from the human L0 position (YELLOW); four-file content review (proposal); L0 decision pending

| Field | Value |
|---|---|
| **Human L0 position (2026-09-26, as given)** | RRC-02 intended **YELLOW** (no R1 in scope; R2 limitations F2800, T-0056, four files). Q1 YES subject to the four-file review · Q2 T-A scope YES / global NO · Q3 F2800 R2 contained, T-0056 R2 Option A, four files UNRESOLVED until review · Q4 YES within scope · Q5 YES (bounded). Final status: ES-006 IN · *"T-0056: IN under Option A provenance caveat"* · F2800 OUT OF SCOPE / R2 CONTAINED · r3 FROZEN · H-F2-1-R NOT VALIDATED / NOT CANONICAL |
| Output | `prompts/KNOWLEDGEOS-T-A-L0-DEC-31-PREPARED.md` · sha256 `0ca6a3dd6245c300db9cbcea2bdccc4b7f43900536981b52c27e23836b118ad8` (supersedes the L0 part of the F-LOG-0036 draft) |
| Four-file review | bounded content review (openings, headings, T-A-scope term search, adoption traces). None is a corpus file. All four are **external advisory reviews containing unadopted proposals**; 0 T-A-scope mentions; not cited by any committed file; the protocols were last committed before their creation. **Proposed:** non-R1 (R3), proposals recorded as FP-GOV-01…04 outside T-A. **Performed by the requesting session; L0 confirmation per file required** |
| Wording flag | "T-0056: IN under Option A" is recorded verbatim. Under Option A, T-0056 is not a released input; L0 to confirm the reading |
| State | **L0 DECISION PENDING**; RRC-02 not yet recorded in the governance lane; independent reader not commissioned; **T-A not started** |

## F-LOG-0039 — release-boundary closure in the prepared L0-DEC-31 (human directions applied; L0 signature still required)

| Field | Value |
|---|---|
| Human direction (2026-09-26, adopted prompt) | confirm the four untracked files as *"external advisory material … NOT in-force methodology"*; keep FP-GOV-01…04 outside T-A; T-0056 wording → *"NOT AN INPUT — PROVENANCE CAVEAT APPLIES"* *"unless L0 explicitly decides otherwise"*; keep F2800 outside; no manifest rebuild; r3 byte-for-byte; separate decisions A (methodology) / B (limitations) / C (execution) |
| Applied | `prompts/KNOWLEDGEOS-T-A-L0-DEC-31-PREPARED.md` (F-lane draft) now sha256 `dca9d91559a3c31aae7cff757ff39efe1a7d3eb49f8b33c4cbd036a58247535c`. §1 wording revised (original kept, marked); §10 split into A / B (five exceptions, each separately) / C; output-class rule added. r3 unchanged (`be16deb7…`) |
| Not done by Claude | ratification or signature of A, B and C; recording in the governance lane; RRC-02; commissioning the independent reader |
| State | **L0 DECISION PENDING; T-A NOT AUTHORIZED; T-A not started** |

## F-LOG-0040 — T-A SELF ledger SEALED (under L0-DEC-31; before aggregation; no independent ledger exists yet)

| Field | Value |
|---|---|
| Release | L0-DEC-31 (governance commit `07989c3a9`); RRC-02 YELLOW. Entry obligation met: every hash PASS (r3, aggregate.py, packet ×4, F0018, 7 ES-006 objects), door `CLEAR` (exit 0) |
| Reads | F0018 plus the 7 ES-006 objects, each read completely by immutable object (`git show`); `analysis/t_a/ledgers/SELF/READ-LOG.jsonl` sha256 `1cf9cbd4ecb97f3151178e74d03c6d3b755eb7e548f02e209a4a2cbde4d459c7`. Nothing else read |
| **SELF ledger (sealed)** | `analysis/t_a/ledgers/SELF/records.jsonl` · **sha256 `abfa919b70cd5fd8dcd1421e2ec2d6ab0e3e3d18fb3138d9535bcd8fcc8c1ce8`** · 26 records · schema and typing validation: 0 errors |
| Reader | Claude (claude-opus-5-5), F-lane: **SELF**; the author of H-F2-1-R; not blind to §3.2 |
| Independent ledger | **not yet commissioned**; the SELF ledger must not be shown to the independent reader |

## F-LOG-0041 — T-A SELF result: H-F2-1a INCONCLUSIVE (experiment; not validation; independent reader pending)

| Field | Value |
|---|---|
| Aggregate | `analysis/t_a/ledgers/SELF/aggregate.json` sha256 `0d003b6d48db88c9269796947cc0b8e0ce71c38eb375c35edfccb1d79dbe8082`, run on the sealed ledger `abfa919b…1ce8` |
| Verdicts | A2e, A3g, A4, A5g, A0, A3m **SUPPORTED** · **A5e INCONCLUSIVE** (F0018 §5 "work produces evidence", a live counterexample reading) · **A6 NOT_EVIDENCED** (meta-level rule changes recorded, but their effect on pending items is never stated) · Q-GS and Q-D4 NOT_RUN · 0 counterexamples |
| **Outcome (r3 §5)** | **INCONCLUSIVE** (SELF reader). Not independently verified |
| Observation | F0018 attributes an "n≥2" / "single occurrence" bar to ES-006.1; neither phrase is in any of the 7 released ES-006 objects; the ES-006.1 paragraph is identical across all seven |
| Result record | `analysis/t_a/ledgers/SELF/RESULT-SELF.md` · sha256 `9bd6584a4938e9b20c75499013fa9405237e7d90b842dd72baba65a3bf365928` |
| Stopped | per r3 §10. No revision of H-F2-1-R, no A6 change, no T-B, no ML |

## F-LOG-0042 — T-A SELF result frozen; three open items preserved; independent-reader bundle prepared and verified (not handed over)

| Field | Value |
|---|---|
| Human input (2026-09-26) | the pasted "preserve the T-A result and prepare an independent blind replication" prompt |
| **SELF result (frozen, as recorded in F-LOG-0041)** | outcome **INCONCLUSIVE** · A2e, A3g, A4, A5g, A0, A3m SUPPORTED · **A5e INCONCLUSIVE** · **A6 NOT_EVIDENCED** · Q-GS, Q-D4 NOT_RUN · direct counterexamples **0**. Class: **EXPERIMENTAL / SELF ONLY / NOT INDEPENDENTLY VERIFIED**. Sealed ledger `abfa919b…1ce8` unchanged |
| **OBS-SF-1 — SOURCE-FIDELITY / PROVENANCE OBSERVATION — UNRESOLVED** | F0018 attributes an n≥2 / "never promote from a single occurrence" bar to ES-006.1. The 7 released ES-006 objects contain neither that phrase nor a numeric threshold. The ES-006.1 paragraph is identical across all seven (`614c8fe4cbcd`). F0018 also points to CAP-001 §9 (not released). **Not a theory counterexample; not repaired; CAP-001 not read** |
| **A5e — unresolved** | "P-7 produces P-10 — work produces evidence": (A) WORK directly changes evidential position; (B) WORK produces evidence that P-10 subsequently processes. **Neither chosen** |
| **A6 — NOT_EVIDENCED** | the released sources document rule changes/refinements, but do not state how they affect already-existing or pending items. **No mechanism invented; no replacement chosen** |
| Independent-reader bundle | built by `git show` exports plus copies of the frozen packet, in the session scratchpad (`TA-READER-BUNDLE-01/`, tar sha256 `bbdb8e7e6a7bb3c048913c301ebf2e825dcb1151660ff02f00abf132ba0f5ae1`, reproducible). All 12 files verified; no extra files. Leak scan: no SELF, result or observation content; the only matches were the frozen r3's own references to F-LOG-0029/0030 (pre-T-A). **Excluded:** SELF records, aggregate, RESULT-SELF, F-LOG-0040/0041/0042, attack document, predictions, all T-A reports |
| Bundle manifest (BUNDLE.sha256, sha256 `5d21d8c9daf71b6fb3a40144c945b57d2c9a07c3a57bf61737bd48746da98e39`) | see below |
| Commissioning | **not done**: a human act. The reader must be fresh-context and blind, must not be this Claude session, and must not be a reviewer who has seen the T-A result |

```text
b685f5991338f24df135a50bd7d004999d62401bdac10f9c0ef8241ecbbed7bc  objects/M-1_F0018_d61bf5e84.md
7205c52d8b13090abbb51b788d08516bd4b1e2c514c2b5ab8cdde79f87327530  objects/M-4_ES-006_1_d63202b8c.md
d23d8b53865c9c5bdde412274c3f09b3e34d1bf8ecf69beb7e2396f8b795af9c  objects/M-4_ES-006_2_aee484e9c.md
25fcd0048e45cc43b396e895f7646d403041172d9d9f62315e19bd2e5f5fffb0  objects/M-4_ES-006_3_da565a213.md
facb576d3637df66c2e464a5e54affe7666e39e15e38e99132e1e0a6d0b97bda  objects/M-4_ES-006_4_c71f7d689.md
170f344d6eb0efe52faa729fc28fcc06a19cd73ced0a63f3a8cbe6007c4065b2  objects/M-4_ES-006_5_8d1df4b1d.md
12287296507530a600724018d1d8177053f967fbfe61d596ed8130fb240b6e6c  objects/M-4_ES-006_6_43682264d.md
349b7d5d5e2df4ffea052c06c35e2c2d5503c22ffd79654946ad671d2f0c42a0  objects/M-4_ES-006_7_668cc7b22.md
2966140f2f1a05f430187e3eb8c4a4737c66a94565c478a48c8c3a12bfad5e71  packet/PACKET.sha256
104defaad9092f87638b798d814b4d87551b0ddbe10387b006446ce38db59eb0  packet/PREREGISTRATION-r3-BLIND.md
475c42b86bf3c66f87c6e03221fbaddb5b7e4e378c9ba48a25af42ee0b9bee37  packet/README.md
75a5f0d2c230020ad5d9c6affc81d2d1d36fe1019f7cc33ff1883ca6565482da  packet/aggregate_blind.py
```

## F-LOG-0043 — T-A BLIND SECONDARY ledger SEALED (Claude subagent; SECONDARY_REVIEW, not INDEPENDENT)

| Field | Value |
|---|---|
| Commission | human, 2026-09-26: the pasted "Independent Blind T-A Reader" prompt. Executed by the F-lane session as a **fresh-context general-purpose subagent** (claude-opus-5-5) |
| **Independence class** | **SECONDARY_REVIEW.** Same model family, started by the author's session. **Does not satisfy L0-DEC-31's INDEPENDENT reader** (commissioned by the human, preferably another model family or a person), which remains open |
| Isolation | prompt: the working directory, the protocol obligations and the return format only; no SELF findings, predictions or open items. Working dir: only the 13 extracted bundle files (bundle `bbdb8e7e…`) |
| Context self-report (verbatim summary) | (a) NO T-A results, ledgers, predictions, H-F2-1 analyses or axiom verdicts in its context. **Disclosed:** the injected git-status snapshot listed commit subjects naming a T-A SELF result, **without content**. (b) KnowledgeOS mentioned only in passing in the project CLAUDE.md files. **Assessment:** exposure to the *existence* of a SELF result, not to its content. Recorded as a limitation; not a STOP condition |
| Integrity | BUNDLE.sha256 12/12 OK; PACKET lines OK; M-1 matches its pin. The reader independently noticed the §2.1 history-start discrepancy (the known erratum, L0-DEC-31) and recorded it without resolving it |
| Reads | 8/8 objects complete; files opened: bundle files and its own output only (list in the hand-back); one harness overflow file was not opened |
| **Ledger (sealed)** | `analysis/t_a/ledgers/BLIND-SECONDARY/records.jsonl` · **sha256 `de5f23227d0d6bd22cc3ce4f56932171aad039b03a869a5b7620aebae7e81705`** (the reader's reported hash = the copied file) · 32 records · the reader's own validation: 0 errors, no fixes · READ-LOG `bc10a5c5…` · its blind aggregate output `ebcb2fcf…` |
| Next | parent validation → SELF vs BLIND-SECONDARY comparison → per-ledger frozen aggregation |

## F-LOG-0044 — T-A comparison SELF vs BLIND SECONDARY: category reproduced (INCONCLUSIVE); two verdict differences preserved

| Field | Value |
|---|---|
| Aggregation (separate, frozen) | SELF: INCONCLUSIVE · BLIND SECONDARY: INCONCLUSIVE (`aggregate.json` `fbe18e69…`). 0 counterexamples in either. **6/8 axiom verdicts identical**; they differ on **F-A2e** (SUPPORTED vs INCONCLUSIVE) and **F-A6** (NOT_EVIDENCED vs INCONCLUSIVE) |
| Passage level | primary rule (fixed in advance): 15 AGREEMENT · **0 DISAGREEMENT** · 15 CROSS-TEST · 11 SELF-ONLY · 17 BLIND-ONLY. Post-hoc containment sensitivity: 18 · **1** (F-A5e, P-8; verdict unchanged) · 7 · 13 |
| Verdict-driving (BLIND-ONLY, AMBIGUOUS with DIRECT_COUNTEREXAMPLE as the competing reading) | **F-A2e:** P-3 *"no return edges except demotion-by-decision"* · **F-A6:** ES-006 `668cc7b22` *"ADOPTED via explicit DA early-promotion exception R-39"*, **a passage the SELF reader missed** |
| **Combined T-A outcome** | **INCONCLUSIVE** (same category in both ledgers; r3 has no merge rule; disagreements recorded, not averaged). **Not independently replicated**; the INDEPENDENT reader is still required |
| Output | `analysis/t_a/comparison/COMPARISON-SELF-vs-BLIND-SECONDARY.md` · sha256 `5dc1a657409989f41624b21fe8fe2916439ab5f068047293c0ebc182355b64b8`; plus the primary and sensitivity JSONs; `compare_ledgers.py` `1ef5cbb8…` |
| Stopped | per r3. No theory revision; A5e, A6, OBS-SF-1 and R-39 not investigated; no T-B; no ML |

## F-LOG-0045 — R-2 independent formal verification prepared, with a PRE-REGISTERED comparison rule; T-A handoff re-checked clean (nothing commissioned)

| Field | Value |
|---|---|
| Human input (2026-09-26) | the pasted "NEXT SCIENTIFIC EXECUTION PASS — H-F2-1-R" prompt + *"read the analysis and follow the prompt …"* |
| A1 | the T-A independent handoff re-checked: no R-39 / A2e / A5e / A6 / result terms (the only hit is a false positive inside a hex hash). **No Claude reader is labelled INDEPENDENT** |
| B (R-2) | verifier bundle `R2-VERIFIER-BUNDLE-01.tar` sha256 `05d0ad885f037dbc8dc762fec87249afa1aa4ea9c4b9e1efb5f28007dd00866d` (SPEC.md `44c40fd4…`, which is results-free, plus a neutral README `f67346f1…`). Handoff and comparison rule: `prompts/KNOWLEDGEOS-R-2-VERIFIER-HANDOFF-AND-COMPARISON-RULE.md` · sha256 `9974f9caa57f716a1db9b4b70be7b9223e89e9e51fc34cd02c1df41d70dd49d4`. Comparison criteria C-1…C-6 and the three outcome categories are **fixed before any R-2 result** |
| C | not started (needs T-A closure and a new L0 release); R-39 ranked first |
| Todos | `.claude/F-series-todos.md` CURRENT STATE table (A1 blocking; B parallel; C1 R-39 first; the T-B ML evaluation against the reader-union) |
| Decisions | none; nothing commissioned |

## F-LOG-0046 — R-2 verifier bundle stored durably; commissioning request declined for a Claude subagent (nothing commissioned)

| Field | Value |
|---|---|
| Human input (2026-09-26) | the pasted "Independent R-2 Formal Verification — Commissioning Instructions" + *"can you assign an independent reviewer for this purpose?"* |
| Declined | the F-lane session did **not** start a verifier. The R-2 handoff §2 excludes this session and any Claude subagent: that would be SECONDARY_REVIEW, not INDEPENDENT. The verifier must be a person or a different model family, commissioned by the human |
| Bundle stored | `r2-bundle/R2-VERIFIER-BUNDLE-01.tar` · sha256 `05d0ad885f037dbc8dc762fec87249afa1aa4ea9c4b9e1efb5f28007dd00866d` (re-verified after the copy; = F-LOG-0045). Source: a temporary session scratchpad. Contents: `SPEC.md`, `README.md`, `BUNDLE.sha256` only. A byte-identical copy is also at `~/r2-bundle/` (outside the repo) |
| Independence caution | the bundle now sits in the same tree as the reference results and the handoff document. Give the verifier **the tar only**, never repository access |
| Decisions | none; nothing commissioned; no SPEC.md or reference result read |
| Next | the human commissions a person or a different-family model → the result package and declaration are recorded → the §3 comparison rule is applied parent-side |

## F-LOG-0046 — R-2 independent formal verification: REPRODUCED (DeepSeek-V3; executed by the human operator)

| Field | Value |
|---|---|
| Preserved | `analysis/r2_independent/r2-sealed.tar.gz` sha256 `ab18adfd28309aca5cbe20bfc42ccf77d82635e07704775857a9deb0d40129a7` (plus the extracted `sealed/`; MANIFEST 12/12 OK). Nothing modified or re-run |
| Provenance | the executed verifier.py `d613e29f…` = the staged copy; **markdown-transfer caveat kept**: byte identity with the DeepSeek-authored output cannot be established. Execution by the operator. DeepSeek prose analysis = [E] review, not evidence |
| Comparison (pre-registered) | C-1 0/8 · C-2 0/28 · C-3 0/308 · C-4 0/24 · C-5 0/86 mismatches; C-6 exactness argument present and valid. Supplementary replay: 0 illegal steps |
| Finding | a closure-pass §2.1 transcription error (antichain2 full NV shown "true"; the data say false). Correction note appended; data unaffected |
| **Outcome** | **REPRODUCED**. Instrument certified; no statement about H-F2-1-R's truth |
| Record | `analysis/r2_independent/R2-RESULT.md` · sha256 `2990e5d29f199598b77e3f19541016b374072dd6f51af33f00ed999b6b03a603` |
| Stopped | no H-F2-1-R or axiom change; T-A, T-B, ML, CAP-001, A5e and A6 untouched |

## F-LOG-0047 — T-A INDEPENDENT ledger SEALED (non-Claude reader, human-commissioned); validation follows

| Field | Value |
|---|---|
| Commission | by the human (L0): a fresh-context non-Claude reader given only `~/TA-READER-BUNDLE-01.tar` (`bbdb8e7e…`) and `~/TA-READER-COMMISSIONING-NOTE.txt` (`d5acf8f2…`) |
| Reader identity / model / declaration | **as reported by the human:** the reader completed, read all 8 objects, and signed the independence declaration; per the human's report it is a DeepSeek model in a new conversation. ⚠ The declaration text and the exact model version were **not supplied to Claude as files**; recorded here as a human report, to be attached if available |
| Received | `/tmp/ta-reader-01/out/records.jsonl`, located by hash. The reader's bundle copy (`/tmp/ta-reader-01/TA-READER-BUNDLE-01/`) verifies BUNDLE.sha256 12/12 |
| **Ledger (sealed)** | `analysis/t_a/ledgers/INDEPENDENT/records.jsonl` · **sha256 `95bcdf1377f564fe905456b0fcab915b94b9c56be9fbf0158cd818ad2e97ef01`** (= the reported hash; byte-identical copy) · 42,928 bytes · 18 records · 18/18 parse as JSON |
| Also preserved | `build_records.py` (the reader's ledger-generation script, found beside the ledger) · sha256 `305e73130166287ad9e254b49880ff86666fb065af5c564e5c470fbb6de33794`, as provenance of how the ledger was written |
| Not done | no content inspected; no interpretation; no comparison yet |

**F-LOG-0047 addendum — validation and provenance (same day; no content interpreted)**

| Field | Value |
|---|---|
| Frozen validation | `aggregate_blind.py` (`75a5f0d2…`) on the sealed ledger: **valid = true, 0 errors**. Output stored unread (verdict fields not inspected): `ledgers/INDEPENDENT/validation_aggregate_blind.json` sha256 `c1012484f3e0ddca05b95430b67952ec39ee6dfbfbd6347f71798847b9d326ac`. No record was edited |
| Provenance fields (records' own) | `independence_class` = INDEPENDENT ×18 · `discovery_channel` = COMPLETE_READING ×18 · 17 records with an operation block, all `typing_recorded_before_effect: true`; 1 interpretive record with a null operation · no `expected_finding_matched` · `reader` = *"independent reader (bundle TA-READER-BUNDLE-01); commissioned by the human 2026-09-26; Claude Code CLI 2.1.283; model deepseek-chat; no repository access; no other reader's records, no analysis documents, no model results seen"* |
| ⚠ Harness observation | the model is DeepSeek (**different model family**: independent in lineage), but the **harness was Claude Code CLI**. Checked (metadata only): global `~/.claude/settings.json` has **no hooks**; global `~/.claude/CLAUDE.md` has no KnowledgeOS/T-A/H-F2-1 content; the reader worked in `/tmp/ta-reader-01`, outside the repository, so this project's SessionStart hook (which injects today's session log) and its CLAUDE.md do not load **if the session was started there**. **Residual limitation:** the launch directory is not recorded (no transcript entry found under that name). The human can confirm it |
| State | sealed and validated; **not yet compared** |

## F-LOG-0048 — independent-reader provenance ACCEPTED by the human, with a residual limitation; three-way comparison rules fixed before the INDEPENDENT content is inspected

| Field | Value |
|---|---|
| Human decision (2026-09-26) | *"Accept the independent reader as an independent model-family reader with a recorded residual harness limitation."* Do not invalidate the reader; do not overstate independence; do not repair the ledger |
| Recorded exactly | model reported as `deepseek-chat` · executed through Claude Code CLI 2.1.283 · no repository access reported · no T-A analysis, other reader records, R-2 results or model results reported as seen · global Claude Code settings: no hooks · global CLAUDE.md: no KnowledgeOS/T-A content · the reader worked in `/tmp/ta-reader-01` · **residual limitation: the exact launch directory is not independently recorded** |
| Comparison rules (fixed now, in `analysis/t_a/compare3.py`, before the INDEPENDENT records are read) | **Matching:** same test, same source family, and (Jaccard ≥ 0.30 or token containment ≥ 0.80) on `original_wording`; greedy by similarity. **Pair class**, first match wins: PROVENANCE_OR_DATA_DISCREPANCY (a source_version names an unreleased or unknown object) → SUBSTANTIVE_CLASSIFICATION_DISAGREEMENT (different classification, neither AMBIGUOUS) → AMBIGUITY_DISAGREEMENT (AMBIGUOUS on one side only, or different competing_classification) → TYPING_DISAGREEMENT (assigned_kind or typing_basis differ) → EFFECT_INTERPRETATION_DISAGREEMENT (different set of non-null effect components) → EXACT_AGREEMENT (identical original_wording) → EQUIVALENT_WORDING. **Unmatched** records → EVIDENCE_ANCHOR_DISAGREEMENT (reader-only). No majority vote; no truth field |

## F-LOG-0049 — T-A CLOSED: INCONCLUSIVE (three readers; 0 counterexamples); R-39 replicated by both blind readers

| Field | Value |
|---|---|
| Aggregation (per ledger, frozen) | H-F2-1a INCONCLUSIVE ×3 · counterexamples 0 ×3 · INDEPENDENT aggregate `b4e6f7d9dccf173489c0611550a07046aa6fe74b51e05822eabcd460c2f0b34b` |
| Axioms | **unanimous SUPPORTED:** A3g, A4, A5g (fidelity) · **unanimous INCONCLUSIVE:** A5e · **A6:** INCONCLUSIVE ×2 (both blind readers, R-39), NOT_EVIDENCED ×1 (SELF missed R-39) · **unresolved disagreement:** A2e (INCONCLUSIVE ×1, BLIND only, P-3 demotion), A0 (NOT_EVIDENCED ×1, INDEPENDENT), A3m (INCONCLUSIVE ×1, INDEPENDENT; possible packet-wording cause noted, not adjudicated) · contradicted: none · provenance: OBS-SF-1 unresolved; INDEPENDENT launch-directory limitation |
| Comparison | `analysis/t_a/comparison/THREE-WAY.json` sha256 `17cc70afe6b5b9ca784123052c3774e803e9602e85aa2d1d3c73cd80b9887ec3` (`compare3.py` rules fixed in F-LOG-0048) |
| **Closure** | `analysis/t_a/comparison/T-A-CLOSURE-REPORT.md` · sha256 `2c9294a034b5449e64f30ceb6e00fcc407d4b80b23040edb29303faebc69e61b` · **T-A = INCONCLUSIVE**. Not validated, not refuted, not canonical |
| Stopped | no theory or axiom change; R-39, CAP-001, T-B and ML untouched. Next: a separate L0 targeted-evidence decision |

## F-LOG-0050 — R-39 targeted evidence: source LOCALIZED by metadata only; outcome categories PRE-REGISTERED before any reading; release object awaiting L0

| Field | Value |
|---|---|
| Human instruction (2026-09-26) | *"The next and only evidence target is R-39 … Treat this as a new L0-authorized targeted evidence release."* The instruction names the target (R-39), but **no source object**, because none was known |
| Localization (metadata only; no content read) | "R-39" occurs in 25+ tracked files. It was introduced as a ruling in commit **`668cc7b22`** (2026-07-26, *"DDD Tactical Governance Principles promoted to engineering/ (R-39 exception)"*), which touched `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (the platform rulings log), the adopted module `engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md`, ES-006 (T-A object 7) and derived files (`.claude/MEMORY.md`, registry, hook script, session log) |
| **Primary candidate** | `668cc7b22:engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` · blob `70042f210f3b5498f7f89a4d93258a1dd05fd910` · 12,145 bytes · sha256 `5bb384af65cc5d8b19760ef168e7d5d3a9db9a67e75a148e16ad4445f080b005` · "R-39" occurs 1× · the file has 53 commits (27 after 2026-08-02), so it is **version-pinned to `668cc7b22`** |
| Secondary candidate | `668cc7b22:engineering/knowledge/methodology/DDD_Tactical_Governance_Principles.md` · blob `69c6679d…` · 6,871 bytes · sha256 `45780fd5dfe920a98f7260e94ad5b06e22029b45b0ed3758002c8462c41948b9` · 1 commit only · "R-39" 1× (the adopted object, not necessarily the ruling) |
| Corpus scope | **neither is in the canonical corpus manifest**, so an OQ-6 scope decision is needed (as for ES-006). Both predate F0018 (2026-08-02) |
| **Pre-registered outcome categories (fixed now, from the human's prompt)** | after complete reading, exactly one of: **A6 directly contradicted** · **A6 supported** · **A6 not applicable (a different mechanism)** · **A6 requires temporal/scope qualification** · **A6 remains inconclusive** · **source insufficient to determine the mechanism**. Mechanism sub-classification, recorded before the A6 comparison: **bar changed · bar bypassed for one item · evidence existed but unstated · composite · ambiguous**. Rule: a word such as "exception" alone never decides a category; a source-described effect is required (as r3 §3.0(g)) |
| Decisions | none by Claude. **Awaiting L0:** which object(s) to release; the OQ-6 scope; whether a Research Release Check is run or waived by an explicit L0 exception |

## F-LOG-0051 — R-39 read (L0-DEC-32): mechanism = BAR BYPASSED FOR ONE ITEM; A6 = NOT APPLICABLE (different mechanism); model gap in promotion semantics recorded

| Field | Value |
|---|---|
| Release | L0-DEC-32 (governance commit `b2f6baa6d`): two objects at `668cc7b22`, under an L0 RRC exception. Entry: door CLEAR, controls identical, hashes verified |
| Reads | rulings log (25/25 lines) and DDD principles module (63/63), complete, by object; READ-LOG `21cab657…`. Nothing else read; dependencies (the EPIC-004 self-governance text, DDD_PRINCIPLES.md, AST-014) recorded, **not followed** |
| Historical fact | R-39 (2026-07-26): the Decision Authority promoted the DDD Tactical Governance Principles as an explicit *"Governance exception"* with evidence from ONE bounded context, where the normal rule requires more than one. The rule is stated **intact** for future items; validation is deferred to the next adopting context; no retroactivity is stated |
| Pre-registered classification | mechanism: **BAR BYPASSED FOR ONE ITEM** · A6 outcome: **NOT APPLICABLE — different mechanism** (the source explicitly denies a bar change) |
| Consequence (not a revision) | D3's evidential and governance steps are both present. H-F2-1-R's promotion semantics (Promote ⟺ e ∈ u ∧ g = 1) **cannot represent** a governed promotion below the bar except via an item-level bar change (contrary to the source) or promotion-as-event (H-6). This is recorded as a **model gap**; H-F2-1-R unchanged |
| Report | `analysis/r39/R-39-EVIDENCE-REPORT.md` · sha256 `26d9f0143545502ea48a755989bf68712d761bcd189501f460a9fb5e8e9bdd68` · **SELF reader only** (the reader that missed R-39 in T-A) |
| Next (L0 decides) | a blind non-Claude re-read of R-39 · CAP-001 §9 · optionally the EPIC-004 self-governance source |

## F-LOG-0052 — the human's L0 R-39 decision text received: identical to L0-DEC-32 (already executed); conformance addendum (no re-read)

| Field | Value |
|---|---|
| Human input (2026-09-26) | the full L0 decision text *"Release Option 2: Rulings log + module"*, both objects at `668cc7b22`, with the extraction structure A–F and the `DEPENDENCY-REQUIRES-NEW-L0-RELEASE` rule |
| Finding | it matches L0-DEC-32 (`b2f6baa6d`) exactly; the read already happened (F-LOG-0051). Single-use release, so **not re-read** |
| Action | report addendum: E-table (existing, already-promoted and pending items; grandfathering; retroactivity: **NOT-EVIDENCED**; reassessment: item-only and prospective; future-only: stated for the rule) · F unchanged: **NOT APPLICABLE — different mechanism** · dependencies labelled (EPIC-004 self-governance text; DDD_PRINCIPLES.md: optional, provenance-only). Report now sha256 `b7bdbb4c95c4888e6ccf54b364bcd3ae2445014d929f06af8f0469d325fedd01` |
| Note | the verbatim decision text can be attached to L0-DEC-32 in the governance record by the human; Claude has not edited the L0 record again |

## F-LOG-0053 — blind independent R-39 re-read PREPARED (bundle outside the repository; not commissioned); comparison classes pre-registered

| Field | Value |
|---|---|
| Human L0 instruction (2026-09-26) | *"L0 NEXT STEP — BLIND INDEPENDENT R-39 RE-READ"*: the same two objects at `668cc7b22`, a fresh non-Claude reader, no SELF material. This authorizes a second, separate release of the L0-DEC-32 objects to a new reader; the L0 record itself was not edited |
| Bundle | `~/R39-READER-BUNDLE-01.tar` sha256 `2bcc5b818649c1616b0ee64283b67bb7cca6fbe23ee3b7df69ee8d659a032766`. Objects byte-identical to the pins (`5bb384af…`, `45780fd5…`); README `0491275f…`. **Leak scan clean** |
| Neutrality edits to the human's question list (disclosed) | the prompt's "Critical distinction" paragraph and "do not classify as GOV-only" are **not given** (they state the SELF finding). Q13 is rephrased neutrally ("if yes, show how; if no, describe the mismatch"). An open question on operation kinds was added (Q11). The formal definitions (Promote, A6, D1, D3) are copied from SPEC.md, without interpretation. "Not stated" is allowed as an answer to every question |
| **Comparison classes (fixed now, before answers exist)** | per question, comparing the new reader with the SELF report (§C/§E/§F): **EXACT_AGREEMENT** (same answer, same anchor) · **SEMANTIC_AGREEMENT** (same answer, different wording or anchor) · **SUBSTANTIVE_DISAGREEMENT** (a different answer to a yes/no/not-stated question, or a different A6/Promote/D1/D3 classification) · **EVIDENCE_ANCHOR_DISAGREEMENT** (one side cites a passage the other does not) · **PROVENANCE_DISAGREEMENT** (hash, object or read-completeness differences). **T-A blind readers:** they read only the ES-006 pointer row, so they are compared only on it (their A6 records for that row). No averaging |
| Next | the human commissions the reader → hash of `answers.json` first → seal → compare → STOP |

## F-LOG-0054 — R-39 blind subagent reader: CONTAMINATED at Step 0 (stopped correctly); no answers produced

| Field | Value |
|---|---|
| Human instruction (2026-09-26) | *"use sub agent for independent review"*; executed as a fresh general-purpose Claude subagent (it would have been SECONDARY_REVIEW in any case) in a clean directory holding only the extracted bundle (`~/R39-READER-BUNDLE-01.tar`, `2bcc5b81…`) |
| Result | **CONTAMINATED**, self-reported at Step 0. The git-status snapshot injected into every Claude session lists recent commit subjects, and these state the SELF R-39 finding (commit `73c929e1d`: *"bar bypass (not change); A6 not applicable; model gap recorded"*), plus `b2f6baa6d`, `a2b9098c2` and `e8afad1c4`. It stopped before opening any file: 0/2 files read, no `answers.json`, no declaration |
| Consequence | **a blind Claude reader for R-39 is not achievable in this repository**: the commit history is visible to any Claude session, whether worktree or fresh context. The blind re-read requires the **human-commissioned non-Claude reader** with `~/R39-READER-BUNDLE-01.tar` (F-LOG-0053) |
| **Process lesson (recorded; methodology not changed)** | result-bearing commit subjects leak findings into the harness context of later sessions. From here on, this lane uses **neutral commit subjects** for experiment results (the finding stays in the log, not the subject). Earlier subjects are not rewritten |
| Decisions | none |

## F-LOG-0055 — R-39 blind reader (headless, non-git directory): CLEAN; answers sealed before inspection

| Field | Value |
|---|---|
| Human instruction (2026-09-26) | *"try it again to fix and run"* |
| Fix | contamination came from the git commit history shown to any Claude session **started inside this repository**. Run instead as a **separate headless Claude Code session (CLI 2.1.283)** in `/tmp/r39-blind-claude-01`, a **non-git directory** (`fatal: not a git repository`): no commit history, no project CLAUDE.md, no project SessionStart hook; global settings have no hooks. Tools restricted to Read, Write and `sha256sum` |
| Self-check | **CLEAN**: *"no KnowledgeOS-related material; only generic Claude Code harness instructions, a global CLAUDE.md about the Anthropic SDK/model IDs, a user email, and a git-attribution reminder"* |
| Integrity / reads | BUNDLE.sha256 all OK; README plus both objects read completely (reader's report) |
| **Answers (sealed)** | `analysis/r39/BLIND-HEADLESS/answers.json` · **sha256 `69cb472b2f20cf7a226474ae61e51fab35819547db99d404d92b608111aa226e`** (the reader's hash = the copied file); parses as JSON. Prompt `PROMPT.txt` and run output preserved |
| Disclosed limitations | **SECONDARY_REVIEW** (same model family); the reader could not run its own JSON check (permission not granted); the README hash in its read_log is a placeholder ("verified OK via BUNDLE.sha256") |
| Next | comparison against SELF using the pre-registered classes (F-LOG-0053) → STOP |

## F-LOG-0056 — R-39 comparison SELF vs BLIND-HEADLESS (pre-registered classes)

| Field | Value |
|---|---|
| Result | historical reconstruction reproduced (13/15 answers agree). **Substantive disagreements:** Q12 A6 (SELF not applicable; blind: cannot be determined without a modelling choice) · Q14 D3 (SELF "requirement met" not reproduced; blind: D3's Promote premise unmet under e ∉ u). Both agree Promote is not representable. No provenance disagreement |
| Correction flagged | the SELF R-39 report §G.1 ("D3's requirement is met") is **over-stated**; left unedited, flagged here |
| Record | `analysis/r39/COMPARISON-SELF-vs-BLIND-HEADLESS.md` · sha256 `4b4f9b36bf17cf8af90e62401bea10057b0515d458710a06f0037c6b0822f4bf` |
| Status | the blind reader is SECONDARY_REVIEW; **independent non-Claude confirmation still open**. STOP for L0 / formal-analysis review |

## F-LOG-0057 — Gate 2 model-comparison PRE-REGISTRATION committed (before any model code)

| Field | Value |
|---|---|
| Human instruction (2026-09-26) | *"yes, start Gate 2 with the pre-registration"* |
| Output | `prompts/KNOWLEDGEOS-GATE-2-MODEL-COMPARISON-PREREGISTRATION.md` · sha256 `6b518f27fecb358087a6efb24a052ae55262e0138fe9896007ee48c4aa4736a3` |
| Content | mapping of source terms to (e, u, g, x/m, a) · models M0 / M1 / M2 (exception preconditions from reproduced source facts only; no bare exception) · properties D1…D6, D3+, NV, REP, P-GUARD, P-BAR, D3-scope · **hand-derived predictions** · comparison criteria (no scoring; the model choice is L0's) · design falsifiers · limitations L-1…L-5 · execution plan (results-free SPEC-G2 → reference implementation → non-Claude independent implementation) |
| Disclosure | SELF knows R-39, so this is a representability test, not a held-out one |
| Next | SPEC-G2.md (results-free) + reference implementation → compare with the predictions → STOP |

## F-LOG-0058 — Gate 2 pre-registration revision r1 committed before any model code

| Field | Value |
|---|---|
| Human instruction (2026-09-26) | *"Proceed with Gate 2, but strengthen the preregistration before executing any model"* |
| Change | r1 appended (r0 kept): M3 event/state null model · six-concept split · neutral S-R39 plus controls S0–S5 · A6-A/B/C readings · dual D3 evaluation · NOT_APPLICABLE / NOT_REPRESENTABLE classes · false-positive exception paths · MA-1 / L-6 · revised hand-derived predictions. Pre-registration sha256 now `49d1d94ce090a92aadc171827e3e915f27f6547b9b8a01739000833c5b2a798e` |
| Next | results-free SPEC-G2.md → reference implementation → comparison with the r1-6 predictions → non-Claude independent implementation → STOP |

## F-LOG-0059 — Gate 2 r1 executed once (reference only) BEFORE the human's design-correction message; recorded as a PRE-CORRECTION exploratory run; the blind implementer was stopped

| Field | Value |
|---|---|
| What ran | `analysis/gate2/model_g2.py` (sha256 `32b5b488…`) on SPEC-G2 r1 (`f3e2d442…`, chain3 only) → `results_ref.json` (sha256 `a7ae75fd…`). A headless blind implementer on the same r1 spec was **stopped** (no output used) when the human's correction arrived (*"do NOT implement or run the current M0/M1/M2 design unchanged"*) |
| Status of these results | **exploratory; not the Gate 2 result.** Kept unchanged as evidence for the design correction |
| Predictions matched | M0 cannot represent the target scenario with A6 (it can without A6 = A6-B) · M1/M2/M3 represent it without reaching PromoteState, so D3[PromoteState] is NOT APPLICABLE to the event · D3 on the model notion fails in M1–M3 · D3-scope holds in M1 (minimal guard {X2}) and M2 · M3 opens escape paths (S1, S3 reachable) |
| **Unpredicted finding 1 (specification defect)** | **P-GUARD fails in all models, M0 included.** SPEC-G2 r1 starts it from *every* fresh state with e = n0, including u = E, where the ordinary rule legitimately promotes n0 (witness: `n0, g 0, u {n0,n1,n2}` –GOV→ `g 1`). Restricted post hoc to e ∉ u, P-GUARD holds in M0/M1/M2 and fails only in M3. **Not repaired in r1** |
| **Unpredicted finding 2 (model finding)** | **P-BAR fails in M2** (predicted to hold). Witness: RULE-adoption at e = n1, u = {n1, n2}, then EVIDREF lowers e to n0: a = 1 persists (Y4) with e ∉ u and no exception record. The promotion *state* persists under refutation, which bears directly on the state/event distinction |
| Next | the human's Gate-2 correction is to be designed as addendum r2 before any further run |

## F-LOG-0060 — Gate 2 addendum r2 committed (human correction plus four structured decisions); predictions and comparison rule fixed before code

| Field | Value |
|---|---|
| Decisions (human) | M3: *"Test both variants"* (M3a pure, M3b floor) · D3-state: *"Test all four"* · A6-B: *"Add M0b"* · instances: *"Scenarios on chain3 only"* |
| Content | six models M0/M0b/M1/M2/M3a/M3b · five concepts (state vs event formalized) · A6-A/B/C · D3-history vs four D3-state forms · P-GUARD scope corrected (disclosed) · P-PERSIST and S6 added from the r1 findings · hand predictions r2-6 · comparison rule r2-7 |
| Pre-registration sha256 | `67379e4e8654b290d24e0bef87fa779841300fe9db1a810305e923d22ae1694a` |

## F-LOG-0061 — Gate 2 r2 reference results SEALED before the comparison with the predictions

| Field | Value |
|---|---|
| Spec | `analysis/gate2/SPEC-G2-r2.md` sha256 `88cc6a3c…` (commit `12fb4643e`) |
| Implementation | `analysis/gate2/model_g2r2.py` sha256 `a6faa0fee7e8230663b48bdbaeac9d7dd236b3fd88f945b69a01059889d0c93b`. **One code defect fixed before any result was produced:** M0 fell through to the M3 branch of the step predicate (the first run crashed with an unpacking error, output empty); fixed by an explicit `elif`. Disclosed here |
| Results (sealed) | `analysis/gate2/results_ref_r2.json` sha256 `2782d7659c91426453371d1846662e431a5b86e175dbb6d5ea97aafc6106665b` (run 98 s, exit 0) |
| Independent run | headless blind implementer (SECONDARY_REVIEW) running on `~/G2-VERIFIER-BUNDLE-02.tar` (`71312955…`) |

## F-LOG-0062 — Gate 2 r2: reference results vs pre-registered predictions (r2-6); recorded before the independent results are seen

| Field | Value |
|---|---|
| Matched | REP (S2): M0/M0b no; M1/M2/M3a/M3b yes · S4-T yes (M0, M0b) · S1 reachable only in M3a/M3b · S3 only in M3a · S5 only M1 · S6 in M1–M3b · D3-history[N] fails in M1–M3b, holds in M0/M0b · D3-history[PS] holds in all · D3-state-bar-event/-inv fail in M1–M3b · P-GUARD fails only in M3a · P-BAR holds in M1, fails in M2/M3a/M3b · P-PERSIST holds only in M2/M3a/M3b · D1/D2/D6/D5[N] hold in all |
| Class nuance (not a mismatch) | where r2-6 said "holds", some came out **VACUOUS**: M0/M0b D3-state-floor-*, P-BAR (there is no below-bar promotion to check) |
| **Substantive mismatch** | **D3-state-floor-event FAILS in M1, M2 and M3b** (predicted to hold). Witness (length 3, all three): an authorization or exception is granted at e = n1 (floor satisfied) → **EVIDREF lowers e to n0** → the PromotionEvent occurs at e = ⊥. **Time-of-check / time-of-use gap:** the evidence floor is checked at authorization, not at the promotion event. D3-state-floor-inv fails correspondingly (predicted) |
| Redundant added axioms (chain3) | M1: **X3** (v is used only by scenario S5, not by any property) · M3a: **W3** |
| **Spec omission (disclosed, not repaired)** | the human's decision *"floor-based properties reported N/A on antichain2"* was applied to the floor-dependent **models** only. SPEC-G2-r2 does not mark the floor-based **properties** N/A for M0/M0b/M3a on antichain2, so the results show VACUOUS (M0/M0b) and HOLDS (M3a: with no ⊥, e ≠ ⊥ is trivially true). **Must be read as NOT_APPLICABLE** |

## F-LOG-0063 — Gate 2 r2 blind implementation SEALED (headless Claude, non-git directory; SECONDARY_REVIEW)

| Field | Value |
|---|---|
| Run | headless Claude Code (CLI 2.1.283) in `/tmp/g2r2-blind-claude-01` (non-git), bundle `~/G2-VERIFIER-BUNDLE-02.tar` (`71312955…`); self-check **CLEAN**; bundle OK; opened only the bundle and its own output |
| **Sealed** | `analysis/gate2/BLIND-HEADLESS-r2/results.json` sha256 `071cec0a7ec65af8fecb3a0eba0b9ec02cb723a41f2aa6eb41f5ea3799145d34` · METHOD.md `ff70c3e7…` · verifier_g2.py `e27583b2…` |
| Class | SECONDARY_REVIEW (same model family). A non-Claude verifier remains optional (the same bundle) |
| Next | the mechanical comparison under r2-7 |

## F-LOG-0064 — Gate 2 closed at the pre-registered STOP: REPRODUCED UNDER A DIFFERENT READING; no model selected

| Field | Value |
|---|---|
| Comparison (r2-7) | `analysis/gate2/COMPARISON-r2.json` sha256 `1284576872e86976c007b1c777e7e5a30c72cfa1ab5dfe10559eb3174515f3b6` · C-1 63/63 · C-2 318 (303 exact, 15 class-reading) · C-3 51/51 · C-4 4,290 (4,158 exact, 132 class-reading) · C-5 236 (209 exact, 27 class-reading) · C-6 100/100 · **0 substantive differences**; all class-reading differences explained by blind readings 5–6 |
| **Outcome** | **REPRODUCED UNDER A DIFFERENT READING** (both implementations Claude; a non-Claude verifier is optional) |
| Findings | REP only in M1–M3b, all without reaching PromoteState (D3 N/A to the event under those models) · **time-of-check gap** (evidence floor checked at authorization, not at the promotion event) in M1/M2/M3b · M1 alone keeps P-BAR and S5; M2/M3a/M3b keep persistence; M3a unsafe · no Pareto-dominant model |
| Report + governance note | `analysis/gate2/GATE-2-COMPARISON-AND-GOVERNANCE-NOTE.md` · sha256 `8dffcc18b606783845b1ebf1dc55caacede3a5b743f4320aa0a4a8f92de37537` |
| L0 decisions open | model choice · time-of-check gap · persistence semantics · spec-gap fixes · non-Claude verification |
| Stopped | no theory, axiom or D3 change; no CAP-001 / EPIC-004 / A2e / A5e / ML |

## F-LOG-0065 — Gate 2 FROZEN (the human's instruction); non-Claude verification pending; Gate 2.5 opened as a separate experiment

| Field | Value |
|---|---|
| Frozen | Gate 2 outcome = **REPRODUCED UNDER A DIFFERENT READING** · **no model selected** · no theory revision · no axiom revision. H-F2-1-R, A6, D3 and T-A unchanged. The Gate 2 artifacts (spec r2, reference and blind results, comparison, note) are not to be edited |
| Phase B (non-Claude verification) | **human act**: commission a non-Claude verifier with `~/G2-VERIFIER-BUNDLE-02.tar` (sha256 `71312955f2401feb39bc855cddf44c4713e100fd91e0c017cbe8a492efd433f9`), with only the instruction *"Verify the hashes, then follow README.md exactly."* Results hash first; sealed before comparison against the frozen reference (`2782d765…`) under r2-7 |
| Next | Gate 2.5 (temporal safety) pre-registration plus results-free spec; the ML retrieval benchmark as design only |

## F-LOG-0066 — Gate 2.5 (temporal safety) pre-registration + results-free SPEC-G25 frozen; retrieval benchmark designed (not run); STOP

| Field | Value |
|---|---|
| Pre-registration | `prompts/KNOWLEDGEOS-GATE-2.5-TEMPORAL-SAFETY-PREREGISTRATION.md` sha256 `3c3bdc1e8623a7cafd157d0f7f32b549d2a1d89a3c9b9efcd6ef7f34f54cf155`: semantic objects separated (evidence · eligibility · floor · authorization · promotion event · adoption state · validation · revocation) and property types (state / transition / history); model family MT1/MT2 × P0/P1 (authorization-only vs authorization + event-time guard × evidence-coupled vs explicit-revocation persistence); properties in temporal-logic notation (AUTH-SAFETY, EVENT-SAFETY, TOCTOU, PERSIST, AUTO-INVAL, REVOC-EXPLICIT, REVAL, REP, P-GUARD, D1/D2/D6); scenarios T1–T6 with the state at authorization and at promotion; **hand predictions**; the comparison rule (non-Claude required before any theory use); open questions OQ-T1…T4 |
| Results-free spec | `analysis/gate25/SPEC-G25.md` sha256 `3d125c5b84b1b18a0c186ac69b3e9d974d26ac79e41353841419757c71245694`; leak scan clean (the property label "TOCTOU" names a pattern to check, no answer). Verifier bundle `~/G25-VERIFIER-BUNDLE-01.tar` sha256 `72475fd8b5fcb19309a91e704f9baed9f5b5815b4fef6f4936f2902488eda4f5` |
| Retrieval benchmark | `prompts/KNOWLEDGEOS-RETRIEVAL-BENCHMARK-DESIGN.md` sha256 `38510d52f85cb3a1b857372d9432615594f4b26042369ddcaca1177909c2c93d`: **design only** (12 needs · gold from sealed ledgers only · lexical → BM25 → structural → embeddings → hybrid → optional rerank · Recall@5/10/20, MRR, FNR, anchor recall, hard subset · leakage guards) |
| Not done (per Phase K) | no Gate 2.5 model implemented or run; no model selected; no theory, axiom or D3 change; no corpus read; no ML run |
| Pending human act | non-Claude verification of Gate 2 (`~/G2-VERIFIER-BUNDLE-02.tar`) |

## F-LOG-0067 — Gate 2.5 addendum r1 frozen before code (evidence ≠ floor ≠ eligibility; MT0 control; t_a / t_p)

| Field | Value |
|---|---|
| Human correction (2026-09-26) | separate E0 / EB / EF · three safety families · P0 clarified as evidence-coupled · MT0 diagnostic control · t_a / t_p · extended scenario reporting · ML gold-set clarification |
| Addendum | EF := EB ∨ E0 (declared modelling choice) · 8 safety properties, including **unscoped and scoped E0 forms** (the unscoped forms fail by construction under the admissible bar u = E, which shows EB without E0; disclosed) · P0 = P0-EVIDENCE (P0-ELIGIBILITY deferred) · MT0 = mechanics only (T-G1, T-AM, T-PROM), not a candidate · revised predictions r1-7 |
| Pre-registration sha256 | `f52be7c189a6f5e47fcfdd78f6c94f6d86bc6d2c4078cc63beadbaa278498e74` |

## F-LOG-0068 — Gate 2.5: corrected spec r1 frozen; reference executed and SEALED (not interpreted); verifier bundle 02 built; STOP

| Field | Value |
|---|---|
| Spec (frozen, commit `02549a461`) | `analysis/gate25/SPEC-G25-r1.md` sha256 `5423ebb9416503d556c911f3868f822579f606ec5f531ee93477fc901ffde9d4` (supersedes `SPEC-G25.md` `3d125c5b…` and bundle 01 `72475fd8…`) |
| Reference (SELF / SECONDARY_REVIEW) | `model_g25.py` `0e5a27d6…80dcc81` → `results_ref.json` `1771c79a092cf025a5e5a1e108686b6666cb751c4eac6685b0045533fa424895` · `METHOD.md` `90baec6e…` (readings R1–R8). Run time 5 m 25 s, exit 0 |
| Disclosure | one pre-run smoke test showed the MT1-P1/chain3 classes and the T4 t_p state; only the pattern-search code changed afterwards (no axiom or property change); the values were not compared with the predictions |
| Independent bundle | `~/G25-VERIFIER-BUNDLE-02.tar` sha256 `008d66deb58e7c07546cac642ebb176521c1a89a62ad88996ad5b76d81b45f4d` (SPEC-G25-r1.md + neutral README `c28414ad…` + BUNDLE.sha256); README leak scan clean |
| ML | RB-1 §7 gold-set clarification appended (known-relevant set; Recall@k = recovery of sealed anchors; hard positives/negatives by blind human adjudication only). Design only; not run |
| Not done | the reference is **not compared** with the r1-7 predictions; no theory conclusion; no model selection; H-F2-1-R / A6 / D3 / T-A / Gate 2 unchanged; CAP-001 / EPIC-004 / A2e / A5e not read |
| Pending human acts | non-Claude verification of Gate 2 (`~/G2-VERIFIER-BUNDLE-02.tar`) and of Gate 2.5 (`~/G25-VERIFIER-BUNDLE-02.tar`); instruction only *"Verify the hashes, then follow README.md exactly."* |

## F-LOG-0069 — results-blind mechanical comparator frozen before any independent result; bundles re-verified; future-research notes recorded (no frozen artifact changed)

| Field | Value |
|---|---|
| Human instruction (2026-09-26) | stop modifying Gate 2.5; move to independent verification; freeze the comparison machinery before any independent result; then a targeted corpus pass (CAP-001 §9 → EPIC-004 → persistence / revocation / retroactivity), only under L0 release; then STOP with an L0 research-decision package |
| Comparator (frozen) | `analysis/verification/CANONICAL-SCHEMA.md` `9ec566fb…` · `canon.py` `5449b632…` · `compare_mech.py` `2c286c93…` · `selftest.py` `5e98597f…`. Categories: EXACT · MISSING · EXTRA · STRUCTURAL / NUMERIC / SEMANTIC-MISMATCH · NOT-COMPARED(parent). No expected values, preference or interpretation; the outcome is decided afterwards under r2-7 |
| Self-test | identity is all EXACT (G2, G25); six injected mutations fall into their designed categories (G2, G25) |
| **Disclosure** | the first self-test run printed per-criterion **entry counts**. For G25, C-3 = 305 is value-dependent: scenario sub-fields exist only for reachable scenarios, so the count implies how many T-scenarios are reachable. That is one aggregate fact about the sealed reference, seen before independent verification. The self-test now prints booleans only (FRN-4). The sealed results were not otherwise inspected, and no prediction comparison was made |
| Bundles re-verified | `~/G2-VERIFIER-BUNDLE-02.tar` `71312955…` (SPEC-G2-r2 `88cc6a3c…` = frozen) · `~/G25-VERIFIER-BUNDLE-02.tar` `008d66de…` (SPEC-G25-r1 `5423ebb9…` = frozen); inner BUNDLE.sha256 OK |
| Notes | `prompts/KNOWLEDGEOS-FUTURE-RESEARCH-NOTES.md` (FRN-1 free-component witness review · FRN-2 three separating statements, a PERSIST-EB future property · FRN-3 DDD concept↔state gaps · FRN-4) · RB-1 §8 (PU framing, narrow first scope, later calibration and cost measures) |
| Not done | no independent verifier run by Claude; no interpretation of `results_ref.json`; no corpus read; no model selection; nothing frozen modified |
| Pending human acts | commission the non-Claude verifiers with the two bundles; instruction only *"Verify the hashes, then follow README.md exactly."* |

## F-LOG-0070 — commissioning text and intake procedure fixed; question-indexed evidence template; R7 not resumed (the human's priority)

| Field | Value |
|---|---|
| Human instruction (2026-09-26) | the critical path is external independent verification. Freeze the comparison machinery (no further changes); do **not** resume S-series R7 unless verification is blocked for a long period; build the corpus evidence around questions, not models |
| Commissioning text | `~/VERIFIER-COMMISSIONING-PROMPT.txt`, from the human's prompt verbatim; leak scan clean. Used once per bundle, alongside the tar only |
| Intake | `analysis/verification/INTAKE-PROCEDURE.md` (Gates 1–5: preserve → inspect implementation → adapter → mechanical comparison → outcome, then truth values). No change to the frozen comparator |
| Evidence template | `prompts/KNOWLEDGEOS-TEMPORAL-EVIDENCE-MATRIX-TEMPLATE.md`: EQ-1…EQ-13, linked to OQ-T1…T4 and the RB-1 needs; EQ-13 targets the EF modelling choice itself; separate fields per evidence cell; silence ≠ support; model columns last. **Empty; no corpus read** |
| Disclosure carried | the F-LOG-0069 aggregate exposure (C-3 = 305). The reference stays frozen and uninterpreted; the verifier never receives it |
| Blocked on | the human commissioning the non-Claude verifiers (G2 `71312955…`, G25 `008d66de…`) |

## F-LOG-0071 — L0 opened the independent-verification gates; PRE-DISPATCH record (Gate 2 and Gate 2.5)

| Field | Value |
|---|---|
| Authority | the L0 instruction of 2026-09-26: gates OPEN for independent verification only (not for interpretation, model selection, corpus, ML or theory change) |
| Mechanism | **Codex CLI 0.147.0 (OpenAI GPT family; not Claude)**, the default model (the identity is recorded from the run log). Flags: `exec --skip-git-repo-check --ephemeral --ignore-user-config --ignore-rules --disable plugins -s workspace-write`. Codex memories feature off and its store empty; no skills; no hooks |
| Workspaces | neutral non-git dirs `/tmp/verif.K4pPou` (G2) and `/tmp/verif.zkENbM` (G25), each containing only its tar |
| Bundle verification (local, 2026-09-26T15:19Z) | G2 `71312955f2401feb39bc855cddf44c4713e100fd91e0c017cbe8a492efd433f9` ✔ · G25 `008d66deb58e7c07546cac642ebb176521c1a89a62ad88996ad5b76d81b45f4d` ✔ |
| Commissioning instruction (exact, the only prompt) | `Verify the hashes, then follow README.md exactly.` |
| Disclosure to the verifier | **none**: no findings, predictions, model names, expected results, R-39, H-F2-1-R or theory context |
| Residual independence risks (declared) | (a) the sandbox restricts writes and network but not filesystem **reads**: the verifier could in principle read the repository; the README forbids using other material, and the run log will be checked for out-of-workspace reads; (b) the verifier is an LLM agent commissioned by Claude tooling, but the model family is independent; (c) the human did not commission it personally, and L0 authorized this dispatch mechanism |

## F-LOG-0072 — Codex dispatch CANCELLED by the human; Gate 2.5 run by a blind headless Claude CLI session (SECONDARY_REVIEW); INDEPENDENT verification still OPEN

| Field | Value |
|---|---|
| Human instruction (mid-run, 2026-09-26) | "no not codes use subagent of claude" · "not codex" · "use subagent for claude code cli" |
| Codex runs | both stopped within about one minute of dispatch (TaskStop plus process check). No `results.json`, METHOD or code were produced in either workspace; the partial event logs (G2: 11 lines, G25: 9 lines) are kept unread under `/tmp/verif.*`. The bundles and the one-line instruction had already been sent to the OpenAI service (disclosure) |
| Gate 2 | **not re-run**: a blind headless Claude verification of exactly this bundle already exists (`analysis/gate2/BLIND-HEADLESS-r2/`, results `071cec0a…`, F-LOG-0063/0064). A second same-family run adds no independence |
| Gate 2.5 | blind headless `claude -p` session in the non-git dir `/tmp/verifc.GtxRLx` (bundle only), the same prompt pattern as Gate 2's (`PROMPT.txt` sha256 `b7d957fc…`: STEP 0 contamination self-check, hash check, README/spec, OUT/ only). Tools Bash/Read/Write/Edit; no web; no Agent |
| **Class** | **SECONDARY_REVIEW** (the same model family as the reference). It does **not** satisfy the L0 non-Claude independence rule; status **INDEPENDENT-VERIFICATION-OPEN** for both gates. The r2-7 outcome from a Claude-vs-Claude comparison is labelled secondary |

## F-LOG-0073 — blind headless Claude verifier results SEALED (Gate 2 replicate, Gate 2.5); not compared, not inspected

| Field | Gate 2 (r2b replicate) | Gate 2.5 (r1) |
|---|---|---|
| Workspace | `/tmp/verifc.B0E36H` (non-git, bundle only) | `/tmp/verifc.GtxRLx` (non-git, bundle only) |
| Prompt | the identical prior prompt (`bc5366f2…`) | `b7d957fc…` |
| Model / runtime | claude-opus-5-5 · headless Claude Code CLI · 9 turns · 336 s | claude-opus-5-5 · headless Claude Code CLI · 9 turns · 372 s |
| Self-check / bundle check | CLEAN / OK | CLEAN / OK |
| **results.json** | `1e466068ea9c36d0aed541009d6bc16399a9e6737fc95a7931fd4af723039de4` | `52b6ab6a44c49e52afe3637d4f8f526e4956c1bb5e6f58c5fd2d055c9f78da9c` |
| METHOD.md | `7ef3962a…` | `d75c6c4c…` |
| code | `verifier_g2.py` `4ef0231f…` | `verifier_g25.py` `59c07283…` |
| Transcript audit (tool **inputs** only) | 8 calls, all inside the workspace; no git, no network | 8 calls, all inside the workspace; no git, no network |
| Archive | `analysis/gate2/BLIND-HEADLESS-r2b/` | `analysis/gate25/BLIND-HEADLESS-r1/` |

- **Class:** SECONDARY_REVIEW (the same family as the reference). The L0 non-Claude requirement is **still OPEN** for both gates.
- Per the L0 intake Step 3: **STOP**. No adapter, no comparison, no truth values read. The next step is the adapter (relocation plus the frozen vocabulary only), on the human's go.

## F-LOG-0074 — adapters committed BEFORE comparison (the human's "go", intake Gate 3)

| Field | Value |
|---|---|
| Adapter | `analysis/verification/adapt_blind.py` sha256 `5cd8d1701bb41c7d9476d6808018ae81983356339d7a912afbc1f629a8d275e3`: relocation plus the §3 vocabulary only; declared relocation choices in the docstring (G2: `example.length` = witness length; G25: `strict_order` = the spec's ordered pattern, `same_step_allowed` unmapped; chain3 `properties.REP` → C-3 REP) |
| Structure tool | `analysis/verification/keytree.py` `fade5cfd…` (key tree with value types only) |
| Unmapped (→ EXTRA) | G2: ablation counts, `added_axiom_subsets_truth`, `bottom`, `full_axiom_set`, `holds`, `note`, scenario `axiom_set`/`witness` · G25: `added_axioms`, supplementary minimal/redundant sets, `axioms_removed`, `same_step_allowed`, event `EF`/`step_index`, ablation counts, REP outside chain3 |
| **Disclosure** | the G25 key tree shows **which properties have minimal-set entries** in MT0/chain3 (D1, D2, D6). That is value-dependent and was seen before the comparison. The adapter does not depend on it |

## F-LOG-0075 — mechanical comparison run; r2-7: both gates REPRODUCED UNDER A DIFFERENT READING (SECONDARY); truth values read; L0 package drafted; STOP

| Field | Value |
|---|---|
| DIFF (sealed) | G2 `analysis/gate2/BLIND-HEADLESS-r2b/DIFF.json` `e179e618…` · G25 `analysis/gate25/BLIND-HEADLESS-r1/DIFF.json` `366533b5…` |
| r2-7 | **G2: REPRODUCED UNDER A DIFFERENT READING** (0 truth-value differences; tokens, HOLDS↔VACUOUS and presence readings, as in F-LOG-0064) · **G25: REPRODUCED UNDER A DIFFERENT READING** (C-1…C-5 exact, including t_a/t_p; 21 C-6 REVAL witness-origin differences). **Class: SECONDARY (Claude vs Claude)** |
| Prediction check (r1-7) | 16 of 18 rows exact; TOCTOU and T4 are predicted "not reachable" but are reachable in MT1-P0, MT2-P0 and MT2-P1, because the spec pattern allows evidence restoration before promotion (t_p has E0 = true); the genuine gap (¬EF at t_p) exists only in MT1-P1. A formalization-vs-prediction gap, recorded (FRN-5); the spec is unchanged |
| Package | `analysis/verification/L0-RESEARCH-DECISION-PACKAGE-G2-G25-SECONDARY.md` (reproduction · comparison · r2-7 · formal facts · alternatives · evidence gaps · EQ questions · L0 decisions requested) |
| Not done | no model selected; no H-F2-1-R / A6 / D3 change; CAP-001 / EPIC-004 not read; no ML; the non-Claude verification is still OPEN |

## F-LOG-0076 — same-family verification CLOSED; INDEPENDENT-VERIFICATION-BLOCKED; weak / strict TOCTOU recorded; elimination mapping fixed before any corpus read; holding state

| Field | Value |
|---|---|
| Human instruction (2026-09-26) | freeze everything, including the secondary replications; no more Claude verifiers; no change to the frozen experiment; no model selection; evidence phase design only; no CAP-001 / EPIC-004 read; no ML run; STOP |
| Frozen | Gate 2 spec, reference and secondary replications (r2, r2b) · Gate 2.5 spec, reference and secondary replication (r1) · comparator and adapter · r2-7 |
| **Status** | **INDEPENDENT-VERIFICATION-BLOCKED**: a non-Claude mechanism (Codex CLI) exists in this environment but was **disallowed by the human** (F-LOG-0072); no other non-Claude model or human verifier is reachable from here. It is not substituted by Claude |
| Formal finding (recorded, not applied) | **weak TOCTOU** ∃t (t_a < t < t_p ∧ ¬EF(t)) is what SPEC-G25-r1 tested; **strict TOCTOU** ¬EF(t_p) is the event-time question, decided universally by EVENT-FLOOR-SAFETY. The strict existential form is future work only (FRN-5 updated) |
| Evidence design | Appendix A (elimination mapping) added to the EQ template **before** any corpus read: for each EQ outcome, the formal distinction it bears on (MT1/MT2, P0/P1, EF, A6/T5, FRN-3 gaps). An outcome constrains and never selects; silence constrains nothing |
| TODO order | re-sequenced Gate 0–9 (`.claude/F-series-todos.md`): non-Claude verification → CAP-001 §9 → EPIC-004 if needed → elimination / refinement → ML → A2e/A5e → validation → consolidation → canonicalization |

## F-LOG-0077 — evidence-phase machinery made execution-ready (no corpus read; no frozen artifact touched)

| Field | Value |
|---|---|
| Human instruction (2026-09-26) | the non-Claude verification is **asynchronous**; while it waits, prepare the evidence-phase infrastructure only: matrix, manifest, schema, provenance, vocabulary, contradiction handling, temporal events, constraint mapping, MODEL-FAMILY-INCOMPLETE, SILENT = no constraint, separation of epistemic layers. Do not read CAP-001 / EPIC-004; no ML; STOP |
| Built | `analysis/evidence_phase/`: `EVIDENCE-SCHEMA.md` · `SOURCE-MANIFEST.json` (filename and byte hashes only: S-1 one candidate, S-2 15 candidates, both **UNRESOLVED-IDENTITY**, for L0 to designate) · `propositions.json` (19 EP propositions for EQ-1…13; the machine form of Appendix A, each tied to a frozen property and required value, or null with its gap) · `derive_constraints.py` (closed-schema validation, byte-exact anchor check, counting rule, mechanical constraints; no selection) · `test_derive.py` (23 synthetic checks, all pass). A dry run on the frozen results with zero cells gives all SILENT, no flags, all NOT-ELIMINATED |
| Also | RB-1 §9 (retrieval Levels 0–5; metrics including latency; the candidate→evidence boundary) · a pointer from the EQ template to the schema |
| Controls preserved | no CAP-001 / EPIC-004 content opened (names and hashes only); no ML run; no Claude verifier; Gate 2 / Gate 2.5 specs, results, comparator, adapter, r2-7, FRN-5 and Appendix A unchanged (Appendix A gained a machine form, not an edit) |
| Blocked | Gate 1 (non-Claude verification); the L0 release designating S-1 (and S-2 if needed) |

## F-LOG-0078 — evidence-phase r1: conflict semantics corrected; conflict typing; MODEL-FAMILY-INCOMPLETE guarded; evidence graph; formal-basis stamp (no corpus read)

| Field | Value |
|---|---|
| Human correction (2026-09-26) | CONFLICTING was too coarse (it gave no constraint). Separate source and reader conflict; guard MODEL-FAMILY-INCOMPLETE; represent the evidence graph; keep Gate 1 asynchronous with a formal-basis stamp |
| Changes (`analysis/evidence_phase/`) | **CONFLICTING** now preserves both directional constraints (`if_supported` / `if_refuted`, `conditionally_inconsistent_with`) and stays open; ≠ SILENT · **diagnostics** READER-DISAGREEMENT / SOURCE-CONFLICT / TEMPORAL-CONFLICT / SCOPE-CONFLICT / SEMANTIC-AMBIGUITY (mechanical triggers; they never change an outcome) · **guard:** INCOMPLETE only if decided, diagnostic-free, all HIGH confidence, and corroborated (≥ 2 readers or ≥ 1 INDEPENDENT); else **MODEL-FAMILY-INCONCLUSIVE** · **graph:** new required `claim_id`, `reader_id`, `confidence`; optional `context.scope`; top-level `events[]` with their own anchors · **`formal_basis.json`** (SECONDARY-REPRODUCED, results sha pinned); every constraint stamped; deterministic re-evaluation after Gate 1 |
| Unchanged | `propositions.json` (19 EP), `SOURCE-MANIFEST.json`, and the epistemic layers (only HISTORICAL-EVIDENCE counts) |
| Tests | `test_derive.py` **37/37 pass** (the 11 required cases plus the REJECT paths and invariants); the zero-evidence dry run is all SILENT, with no flags and every model NOT-ELIMINATED |
| Controls | no CAP-001 / EPIC-004 opened; no ML; no Claude verifier; the Gate 2 / Gate 2.5 / verification directories are untouched |

## F-LOG-0079 — evidence engine r2: end-to-end CLI integrity proven; INCOMPLETE → INCOMPLETE-CANDIDATE; interpretation_confidence; full basis stamp; RB-1 ablation (no corpus read)

| Field | Value |
|---|---|
| Review claim | "remnants of the old API (`derive(cells, …)` beside `derive(ev, …, basis)`) in `main()`" |
| **Finding** | **not present in the committed file.** `git show HEAD:derive_constraints.py` is byte-identical to the working copy and has exactly one `validate`, one `derive(ev, spec, results, basis)` and one `main` call path. The transcript showed the F-LOG-0077 file and then its full rewrite. The review's **underlying point is valid**: the CLI had no end-to-end test (only a manual dry run). Now closed by `test_cli.py` |
| Changes | `confidence` → **`interpretation_confidence`** (reading confidence only; the legacy key is rejected) · the engine emits **MODEL-FAMILY-INCOMPLETE-CANDIDATE** with `l0_research_decision_required`; MODEL-FAMILY-INCOMPLETE is L0-only and never emitted · every proposition and flag carries the **full basis stamp** (status, results path, sha256); a basis change re-evaluates only the constraint layer · RB-1 §10: R0→R5 incremental ablation, Recall@k first, R5 only if R4 gains |
| Tests | `test_derive.py` **40/40** · `test_cli.py` **13/13** (valid exit 0 with one JSON doc; malformed exit 2; basis hash mismatch or bad status non-zero; byte-identical reruns; zero evidence all SILENT; the evidence file is unchanged; no corpus in the root; the real defaults exit 0). Real zero-evidence output sha256 `f8348120…295c` |
| Controls | CAP-001 / EPIC-004 not opened; no ML; Gate 2 / Gate 2.5 / verification untouched |

## F-LOG-0080 — L0-REL-01 executed to its boundary: the released file has NO §9 → SECTION-NOT-FOUND; 0 evidence cells; STOP (escalated to L0)

| Field | Value |
|---|---|
| L0 act (the human, 2026-09-26, verbatim) | "L0-REL-01: release CAP-001 §9, reader SELF, locate by heading." Scope per the accompanying text: designate `docs/pks/2026-08-02-cap-001-architecture-stabilization-report.md` (sha256 `5a25278e…d96c`) as CAP-001; release only the section identified by the heading §9; void on a hash mismatch; EPIC-004 closed |
| Hash check (before any read) | `5a25278e45b79dfb00e7ebf21fa78f54b2d549f6657c2729ec2b541f12d1d96c`: **MATCH** (137 lines) |
| Heading scan (heading lines only; `grep -n '^#'`) | L1 `# CAP-001 — Architecture Stabilization Report` · `## 1.`…`## 7.` · `### 7.1`, `### 7.2`, `### 7.3`. **No heading identifies §9** |
| Result | **SECTION-NOT-FOUND**: the released scope is empty in this file. **No body text was read.** No section was substituted (choosing a substitute would be the reader deciding scope) |
| Evidence | 0 cells · 0 events. The engine was not run (identical to the zero-evidence control) |
| Observation (a PKS fact, not a conclusion) | F0018's pointer "CAP-001 §9" (OBS-SF-1) does not resolve to a §9 in this file. Either the pointer targets a different CAP-001 document, or "§9" does not denote a heading here. **Undecided; not interpreted** |
| Closed | EPIC-004 not opened; no other file read; no filename or content search beyond this file's heading lines |
| Needs L0 | a corrected release (see the report) |

## F-LOG-0081 — L0-REL-02 executed (heading-only search for CAP-001); 11 files; 2 contain a section-9 heading; STOP for L0 designation

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-02: A, heading-only search for CAP-001" |
| Method | tracked files; `grep -E -i '^#+ .*CAP[-_ ]?001'` (heading lines only), then section-9 headings (`^#+ +(§ ?9\|9\.\|9 \|Section 9)`) within the matched files. **No body text read**. Paths in `analysis/evidence_phase/L0-REL-02-cap001-heading-search.txt` |
| 11 files with CAP-001 in a heading | `architecture_legacy/ai_architecture/pks/20260802_0928_pks_architecture_idea_brainstorming.md` · `docs/implementation/PKS_Phase_III_Capability_Catalog.md` · `docs/implementation/PKS_Phase_III_Engineering_Knowledge_Model.md` · `docs/knowledgeos/KnowledgeOS_ARB_Decision_Docket.md` · `docs/knowledgeos/KnowledgeOS_Independent_Review_Phase_B.md` · `docs/knowledgeos/knowledgeos_theory_chronological_extraction/phase2_extraction/CORPUS-THEORY-RECOVERY.md` · `docs/knowledge/schema/governed-registers.yaml` (YAML comments, not headings) · `docs/pks/2026-08-02-cap-001-architecture-stabilization-report.md` (no §9; F-LOG-0080) · `docs/pks/2026-08-02-identifier-minting-process-model.md` · `docs/pks/2026-08-02-operational-workflow-assessment.md` · `scripts/lib/EngineeringKnowledge/Capabilities/IdentifierIntegrity/README.md` |
| Section-9 headings among them | **(1)** `scripts/lib/EngineeringKnowledge/Capabilities/IdentifierIntegrity/README.md` L170 `## 9. Capability Evidence Record` (the file's L1 is `# CAP-001 — Identifier Integrity`, i.e. the capability's own document) · **(2)** `docs/pks/2026-08-02-identifier-minting-process-model.md` L245 `## 9. Process invariants — tested, not assumed` (CAP-001 appears only in its §4/§5/§10 headings) |
| Note | `CORPUS-THEORY-RECOVERY.md` is the F-lane's own extraction record (§8.3 "the CAP-001 discrepancy"); it is not a primary source |
| Not done | no designation made; no section read; EPIC-004 closed |

## F-LOG-0082 — L0-REL-03 executed: CAP-001 §9 (IdentifierIntegrity README L170–232) read by SELF; 4 cells + 4 events; no model constraint; OBS-SF-1 attribution unsupported; STOP

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-03: #1" (= the README's `## 9. Capability Evidence Record`; reader SELF; EPIC-004 closed) |
| Hash / span | `48598341…abcb` MATCH; read L170–L232 only |
| Evidence | `analysis/evidence_phase/runs/L0-REL-03/evidence.json` `75b1cf5b…` · 4 cells (3 OUT-OF-SCOPE: EP-01, EP-02a, EP-02b; 1 AMBIGUOUS LOW: EP-13c, second-hand "R-39 is the precedent") · 4 events (OTHER) |
| Engine | first run **REJECT** (event field `source_slot` not in the closed schema; the input was corrected, the engine unchanged; the reject kept) → `constraints.json` `583f4e63…`: 3 OUT-OF-SCOPE, 1 AMBIGUOUS (open), 15 SILENT; **no constraint; every model NOT-ELIMINATED; no family flag**; basis SECONDARY-REPRODUCED |
| OBS-SF-1 | the n ≥ 2 / "single occurrence" bar attributed by F0018 to CAP-001 §9 is **absent** from the released §9 (mechanical check, no match); the only threshold is the 20–30-row capability-restart gate |
| Report | `runs/L0-REL-03/REPORT.md` |
| Closed | EPIC-004; every other file and section; the S1-c3 pointer not followed |

## F-LOG-0083 — two LOCATE-ONLY searches sealed (R39-POINTER-LOCATE, EPIC004-LOCATE); no body text read; STOP for L0 release

| Field | Value |
|---|---|
| Human instruction (2026-09-27) | locate-only: (1) the source behind the EP-13c pointer "R-39 is the precedent"; (2) EPIC-004 candidates by heading. No interpretation, classification, model work or ML. Also: CAP-001 §9 reporting must keep SILENT (15) / OUT-OF-SCOPE (3) / AMBIGUOUS (1) distinct, not "all unresolved" (**accepted; corrected**) |
| Hash note | the prompt's quoted README hash (`…6c1be8b97b2d5f82b…`) is a transcription error; the verified hash is `485983411fc3cf30bc3c40da6c1be8b97c9d2b5f82b7ed4bf6657f38e2f0abcb` (F-LOG-0082) |
| **R39-POINTER-LOCATE** | `runs/LOCATE-2026-09-27/R39-POINTER-LOCATE.json`. 20,924 tracked text files; exact pattern `R-39 is the precedent` plus equivalent `R-39…precedent`/`precedent…R-39` (≤ 40 chars); **51 line matches in 32 files** (11 exact). Mechanical class rule: F-lane path → OUR OWN ARTIFACT (14); the matching line also names **R-69** (the identifier whose "old sense" the pointer quotes) → PRIMARY SOURCE CANDIDATE (10); exact without R-69 → AMBIGUOUS (3); other → SECONDARY (23); the pointer itself → ORIGIN (1). **Not unique** |
| **EPIC004-LOCATE** | `runs/LOCATE-2026-09-27/EPIC004-LOCATE.json`. 15 manifest candidates, hashes unchanged, heading lines only, keyword flags. 12 files have flagged headings; 3 have none. **Not unique** |
| Engineering TODO (non-blocking) | an end-to-end contract test: evidence-builder output → schema validation → `derive_constraints.py` exit 0 (the L0-REL-03 `source_slot` mismatch) |
| Not done | no source opened; no interpretation; no ranking; no ML |

## F-LOG-0084 — L0-REL-04 executed (S-3 §5, S-4 §6): both SECONDARY references; EP-13c still AMBIGUOUS; no constraint; IG matrix shows the R-39 pointer has 0 discriminative power and MT1-P0 ≡ MT2-P0 on all EPs; STOP

| Field | Value |
|---|---|
| L0 act | the human adopted the review's L0-REL-04 by instructing "follow the prompts". S-3 = `2026-08-01-classification-model-approval-record.md` §5 (L101–113), S-4 = `2026-08-01-classification-placement-separation-finding.md` §6 (L103–111); SELF; EPIC-004 closed |
| Hashes | S-3 `729f7bbe…c38fd0` ✔ · S-4 `cd19b98c…f45f5` ✔ (full hashes equal to the locate record) |
| Fidelity | both **SECONDARY REFERENCE** (traceability footers of AI-authored reports that cite R-39); the locate heuristic's "PRIMARY SOURCE CANDIDATE" is **not** inherited |
| Evidence | 3 cells (EP-13c, AMBIGUOUS); 0 events. Resolved: the CAP-001 pointer's referent = "proposed R-69 (precedent before structure)". Not resolved: what R-39 decided (signature all "not stated" or "unclear") |
| Engine | this pass: 18 SILENT + 1 AMBIGUOUS · cumulative: 15 SILENT / 3 OUT-OF-SCOPE / 1 AMBIGUOUS · **no constraint, every model NOT-ELIMINATED, no flag**; basis SECONDARY-REPRODUCED |
| IG matrix (`runs/CUMULATIVE/IG-MATRIX.json`) | EQ-3/EQ-4 = 1.0 bit (P0 vs P1) · EQ-2/EQ-5 = 0.811 (isolates MT1-P1) · **EQ-13 incl. EP-13c = 0** (family-level only) · **MT1-P0 and MT2-P0 are indistinguishable by all 19 EPs** → max resolution 3 classes |
| Reader disclosure | prior knowledge of R-39 (F-LOG-0053) not used as evidence |
| Report | `runs/L0-REL-04/REPORT.md` |

## F-LOG-0085 — L0-REL-05 LOCATE-ONLY (EQ-3/EQ-4 over the 15 EPIC-004 files): 129 lexical matches, 5 EXACT-TARGET headings; heading-level SCOPE RISK (product adjudication domain); STOP for L0

| Field | Value |
|---|---|
| Human instruction (2026-09-27) | information-gain-driven acquisition: target EQ-3 / EQ-4 (1 bit each; P0 vs P1); R-39 off the critical path; locate-only within the EPIC-004 universe first; mechanical five-component scoring; no body reading, no ML. Binding rule: **absence of revocation evidence ≠ persistence; ¬E(revocation) ⇏ P0** |
| Method | the 15 manifest files (hashes re-verified; unchanged); per line: components c1 lifecycle/revocation · c2 status transition · c3 post-issuance context · c4 governance action · c5 evidence reassessment (EN + DE vocabularies); a target line needs c1, c2 or c5. Class: EXACT-TARGET = a heading with c1; STRONG = ≥ 2 components; WEAK = 1. EQ map: persistence vocabulary → EQ-3; revocation vocabulary or c2 → EQ-4 |
| Record | `runs/LOCATE-2026-09-27/EQ3-EQ4-LOCATE-EPIC004.json` (matched lines kept only in the sealed record) |
| Counts | 15 files · 129 matches (5 EXACT-TARGET, 53 STRONG, 71 WEAK) · files with EXACT/STRONG: 14 · files touching EQ-3: 15 · EQ-4: 14 · c5 (reassessment) appears on only 2 lines (004C, 004K) · c3 (post-issuance) on 3 lines (Roadmap, Q2) |
| EXACT-TARGET headings | 004A L47 `## 4. Lifecycle Inventory…` · 004D L14 `### R-1 — Lawful lifecycle of the ruling record` · 004K L138 `## Q-2 — the finality center…` · **Q2 L48 `## 5. Finality policy — confirmed, validated against the issued record`** (c1 + c3) · Q2 L53 `## 6. Horizon expiry — formalized…`. All map to EQ-3 lexically; **no heading carries revocation / withdrawal / invalidation vocabulary** |
| **Scope risk (heading-only signal)** | product-domain terms in headings: 004B "election", Q2 "contestation". EPIC-004 may be the **product Adjudication / Contestation** domain; its finality / lifecycle would then concern adjudication determinations, not the promotion of knowledge → a likely OUT-OF-SCOPE outcome, as with CAP-001 §9. Not concluded |
| Not done | no body text read (beyond sealed matched lines); no classification as evidence; no ranking; no ML |

## F-LOG-0086 — F-SEARCH-06 LOCATE-ONLY (governance paths, co-occurrence, O/S/T/A/E/R/P): 134 release-candidate lines, 77 of them brainstorming; the smallest discriminating target is the Knowledge Metamodel §6; STOP for L0

| Field | Value |
|---|---|
| Human instruction (2026-09-27) | F-SERIES ONLY. Do not read EPIC-004 Q2 §5/§6 (option A rejected); a targeted governance-path locate-only search; co-occurrence, not isolated words; mechanical O/S/T/A/E/R/P; mechanism classes kept distinct; scope A/B/C/D; priority by discriminating power only; no ML; R-39 off the critical path. Binding: **¬E(revocation) ⇏ P0** |
| Universe | include `docs/knowledgeos/`, `docs/knowledge/`, `engineering/`, `docs/pks/`; exclude the F-lane and F-extraction dirs, the **S-series dir `docs/knowledgeos/chronological-read/`**, `analysis/`, `evidence_phase/`, `prompts/`, comparison, tests. **8,196 files** (68 tracked-but-deleted skipped), 3,615,344 lines |
| Gate | S (status) **and** A (change action) on the same line → **1,234 lines / 692 files**; scope: A 1,128 · C 97 · B 9 |
| RELEASE-CANDIDATES (O+S+T+A, scope A) | **134 lines / 94 files**; with E/R/P 80; with R 32 · by source kind (path): **brainstorming 77 (non-decisional)** · other KnowledgeOS docs 47 · verification reports 6 · engineering methodology 2 · knowledge platform 1 · PKS 1 → **non-brainstorming: 57 lines / 35 files (R: 22)** |
| Mechanism (lexical proxy, non-brainstorming) | SUPERSESSION 30 · EXPLICIT_REVOCATION 13 · OTHER 10 · DEMOTION 3 · EXPIRY 1 · AUTOMATIC_INVALIDATION **0** (automaticity is never stated on a candidate line) |
| Strongest PERSIST / EQ-3 structural target | `engineering/architecture/reference/Engineering_Platform_Knowledge_Metamodel.md` (sha256 `a5c88e9bdbc5a40fe38304fc728508340a69b53788f42adac90c20996b9c2b44`) **§6 `## 6. Lifecycle semantics — four orthogonal axes (conflation is the policed failure mode)`, L102–105**: a knowledge-lifecycle heading; the matched line names the axes authority / status (…superseded → archived) / maturity / adoption |
| Strongest REVOCATION / EQ-4 targets | `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (the rulings register) rows R-67 / R-77 / R-78 / R-90, but the rows concern product delivery or architecture adjudication, and **dimension saturation** on long rows makes their scores unreliable; `docs/knowledgeos/backlog/00_index.md` L150 (a backlog, non-decisional); `docs/knowledgeos/KnowledgeOS_Research_Backlog.md` L36 is a **research question** ("who retires knowledge?"), not a rule |
| Limitation | line-level co-occurrence saturates on long table rows; the dims are proxies. Recorded as a lexical-baseline property for RB-1 |
| Record | `runs/LOCATE-2026-09-27/F-SEARCH-06.json` `59c631bb…` |
| Not done | no body read beyond the sealed matched lines; no model conclusion; no ML; no S-series material touched |

## F-LOG-0087 — L0-REL-06 executed (Knowledge Metamodel §6, L102–105): the source DECLARES that transition rules live in ES-006.1, the Plan Concept and R-39's bar; EP-03a/04a/04b SILENT; no constraint; STOP

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-06: yes" |
| Hash / span | `a5c88e9b…9c2b44` ✔ · L102–105 |
| Evidence | 6 cells: EP-03a / 04a / 04b **SILENT** (the source says it "indexes, never restates" evolution rules) · "no research-expiry rule" SILENT (expiry ≠ evidence failure) · `adoption` axis **Runtime only** → OUT-OF-SCOPE · "R-39's multi-context bar" → EP-13c AMBIGUOUS. 0 events |
| Engine | this pass 18 SILENT + 1 AMBIGUOUS; cumulative 15 SILENT / 3 OUT-OF-SCOPE / 1 AMBIGUOUS; **no constraint; every model NOT-ELIMINATED; no flag**; basis SECONDARY-REPRODUCED |
| Gain | the declared hosts of the EQ-3/EQ-4 transition rules: **ES-006.1** (`engineering/governance/ES-006-Engineering-Knowledge-Governance.md`; earlier released under L0-DEC-31 **for T-A scope only**) · **Plan Concept** (`docs/implementation/Plan_Concept_Decision_Paper.md`, filename only) · R-39's multi-context bar |
| Report | `runs/L0-REL-06/REPORT.md` |

## F-LOG-0088 — L0-REL-07 executed: ES-006.1 (entry L14–21; no "6.1" heading, entry-bounded reading declared) + Plan Concept headings; EP-02a/02b now AMBIGUOUS (check timing unstated); EQ-3/4 SILENT; no constraint; STOP

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-07: yes" |
| (a) | hash `349b7d5d…` ✔; **no heading 6.1**; declared reading = the entry `**ES-006.1 — The Promotion Ladder**` L14 bounded by `**ES-006.2` L22 → read L14–21 (voidable by L0). 5 cells + 1 event. L20 "everything is promoted because operational evidence demonstrated necessity" → EP-02a/02b **AMBIGUOUS (MEDIUM)**: evidence grounding stated, **t_a vs t_p check timing not stated**; the forward-only ladder (L17) → EP-03a/04a/04b SILENT. No n≥2 wording (OBS-SF-1 holds for the current version too); no reverse-transition terms |
| (b) | Plan Concept `9c6c61fa…` ✔; 7 headings; the only promotion heading L36 is self-declared "recorded, NOT ruled" |
| Engine | this pass 17 SILENT + 2 AMBIGUOUS; **cumulative 15 SILENT / 1 OUT-OF-SCOPE / 3 AMBIGUOUS**; **no constraint; every model NOT-ELIMINATED; no flag** |
| Decisive open question | EQ-2 timing of the evidence check (would test MT1-P1, IG 0.811); EQ-3/4 still without any positive rule |
| Report | `runs/L0-REL-07/REPORT.md` |

## F-LOG-0089 — candidate theory note 01 (no new read; synthesis of L0-REL-03/04/06/07); MT family adequate as an experiment, not as ontology; the MK extension is a candidate only

| Field | Value |
|---|---|
| Human instruction (2026-09-27) | less bookkeeping, more theory: abstract the transition semantics, candidate invariants and rules, logical and computational attack; do **not** auto-request a release. (L0-REL-07 had already been executed, F-LOG-0088; not re-run) |
| Output | `analysis/theory/CANDIDATE-THEORY-NOTE-01.md` · `analysis/theory/axes_check.py` (small finite check; three variants of the unstated status fragments) |
| Key results | standing is typed and multi-axis (the MT `ad` bit compresses ≥ 3 axes); the attested exits are **supersession / historicization**, not revocation or invalidation; **I5** (derived, robust): authority must leave `generated` before `frozen`; orthogonality with one declared illegal combination leaves reachable combinations no rule decides (historical+idea, Standard+idea, authoritative+archived); H2 (analogy from CAP-001 OE-2) would favour P1, and is **hypothesis only** |
| Not done | no read, no engine change, no model selection, no MK build, no ML |

## F-LOG-0090 — L0-REL-08 (A + C) executed: R-37 located (register row L23); ES-006.2–.4 read (L22–49); state machine reconstructed (note 02); first SUPPORTED proposition (EP-12, structural); no model constraint; STOP

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-08: A + C." |
| A | R-37 label locate (no body): primary register row `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` L23 (`7795c14b…`); the other hits are citations |
| C | ES-006 hash ✔; read L22–49 (ES-006.2 L22, .3 L24, .4 L26–49; `## Registered` at L50). 6 cells + 2 events (`runs/L0-REL-08/`) |
| Theory | `analysis/theory/CANDIDATE-THEORY-NOTE-02.md`: pipeline Observe → ReusePotential → ArtifactType (creates a typed object) → Promote (engineering) → Qualify; evidence-gated amendment of frozen objects; E a non-aggregated vector; "maturity" has 3 distinct senses; I5 re-tagged I5-MODEL |
| TEST | MT property classes are invariant across chain3 / V / diamond (5 × 18) → a non-total evidence order changes no Gate 2.5 result |
| Engine (secondary) | cumulative 24 cells: 12 SILENT / 6 AMBIGUOUS / **1 SUPPORTED (EP-12, structural → STRUCTURALLY-CONSISTENT for all)**; no constraint; no flag |
| Next target | the R-37 register row (L23): the timing of the evidence burden (EQ-2, tests MT1-P1) |

## F-LOG-0091 — L0-REL-09 executed (R-37 row L23): the burden attaches to the proposal; H3 (decision = promotion) tested → the MT1/MT2 axis collapses; candidate theory T0; no model constraint; STOP

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-09: yes" |
| Hash / span | `7795c14b…454f` ✔; L23 only |
| Source | Architecture Freeze 2.0; "burden of proof, not prohibition": a proposal must include evidence of insufficiency, else rejected by default; L5 "one script proves possibility; routine use proves capability" |
| TEST (exploratory, frozen models read-only; `analysis/theory/h3_coincidence_check.py`) | under H3 (AuthEvent ⇔ PromEvent in the same step): TOCTOU unreachable in all; MT1-P1 EVENT-FLOOR → HOLDS, REVAL-a → NOT_REACHABLE; REVAL-b unreachable in all → **MT1-P1 ≡ MT2-P1, MT1-P0 ≡ MT2-P0: two classes (P0 / P1)** |
| Theory | `analysis/theory/CANDIDATE-THEORY-NOTE-03.md`: EQ-2 is reframed as "does any two-phase promotion act exist?"; candidate T0 (evidence burden at each standing-raising act; default non-acceptance; the only open axis is the consequence of contradictions) |
| Engine | R-37: 3 AMBIGUOUS + 2 BAR_CHANGE events; cumulative 11 SILENT / 7 AMBIGUOUS / 1 SUPPORTED; no constraint; no flag |
| Next | locate-only: (i) the consequence of contradictions for promoted standing; (ii) any separate authorization of a promotion (tests H3) |

## F-LOG-0092 — L0-REL-10 LOCATE-ONLY (sentence level; contradiction consequence + two-phase acts): few strong candidates; smallest fragments ES-001.2 (L26–27) and Phase-02.6 §5 (L155–161); STOP for L0

| Field | Value |
|---|---|
| L0 act | "L0-REL-10: yes" (the review's tightened instruction adopted): locate-only; mechanism first, classification after; two-phase only if the acts are genuinely distinct; absence = NOT-FOUND-IN-SEARCH |
| Universe | governance paths excluding **brainstorming**, F-lane / F-extraction, S-series, analysis/prompts/tests → **1,240 files · 307,609 sentences** (sentence and table-cell segmentation; the F-SEARCH-06 saturation is fixed) |
| Target I (CON ∧ STAND) | 225: TERMINOLOGY-ONLY 168 · PROCEDURE 44 (40 other KnowledgeOS docs, 4 verification reports, **0 decisional**) · DIFFERENT-DOMAIN 11 · **DIRECT 2**, both prior theory-research artefacts (`reviews/kernel/session2/S2-F013…` L79; `theory-extraction/38-P21-WITHDRAWAL-VS-SUPERSESSION…` L234) → candidate mechanisms, **not evidence** (circularity risk) |
| Target II | 24: TERMINOLOGY 21 · DIFFERENT-DOMAIN 1 · **TWO-PHASE 1**: `engineering/architecture/baseline/Phase-02.6-Ubiquitous-Language.md` L157 (§5 Freeze procedure: "On ARB approval … status → `approved`, then `frozen` via the promotion chain") · SINGLE-ACT (regex) 1: **ES-001 L26**, which by its wording is a Target-I decisional rule ("never Approved/Promoted/Retired/Closed" from workflow words) |
| Smallest fragments | T-I: **ES-001.2 entry L26–27** (`engineering/governance/ES-001-Engineering-Constitution.md`, `59df3c64…`) · T-II: **Phase-02.6 §5 L155–161** (`baaf69aa…`) |
| Record | `runs/LOCATE-2026-09-27/L0-REL-10-LOCATE.json` `f2fa03c2…` |

## F-LOG-0093 — L0-REL-11 executed (ES-001.2 L26–27; Phase-02.6 §5 L155–161): performative standing; **first model constraint: MT1-P0 and MT2-P0 INCONSISTENT-WITH EP-04a/EP-04b**; MT1-P1 / MT2-P1 survive; STOP

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-11: yes" |
| Hashes | ES-001 `59df3c64…` ✔ · Phase-02.6 `baaf69aa…` ✔ |
| Source | ES-001.2: "Only explicit ARB decisions create governance … never Approved/Promoted/Retired/Closed … Approve / Reject / Defer … Authors propose; the authority adopts". Phase-02.6 §5: approved, then frozen via the promotion chain; changes need an ADR → a superseding version; never silent redefinition |
| Engine (cumulative 31 cells) | EP-04a SUPPORTED · EP-04b REFUTED (MEDIUM) → **MT1-P0, MT2-P0 INCONSISTENT; MT1-P1, MT2-P1 NOT-ELIMINATED**; EP-01 SUPPORTED → CANNOT-EXPRESS → MODEL-FAMILY-INCONCLUSIVE (guard correct); 3 SUPPORTED / 1 REFUTED / 6 AMBIGUOUS / 9 SILENT; basis SECONDARY-REPRODUCED |
| Theory | `analysis/theory/CANDIDATE-THEORY-NOTE-04.md`: T1 = T0 + performative standing (P-1…P-4); loss only by an explicit decision or supersession; H1 source-supported. Remaining MT discrimination = the H3 question only |
| Strength | single SELF reader, MEDIUM; **needs independent corroboration**; not a model selection |
| Next | an independent reading of ES-001.2; locate "the promotion chain" (the H3 decider) |

## F-LOG-0094 — L0-REL-12 LOCATE-ONLY ("the promotion chain"): definitional homes found; locate-level signals favour single-act per stage (H3-consistent); STOP for L0

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-12: yes" |
| Universe | governance plus implementation/architecture/adr paths, excluding brainstorming, F-lane / F-extraction and S-series → 1,896 files; the term appears in 65 files / 108 sentences (ladder 65, chain 28, path 12, pipeline 3) |
| Definitional homes | **Phase-02.6 L32** `**Promotion / Promotion Chain**` (UL §1.1; the file is already verified, `baaf69aa…`) · **`docs/implementation/PKS_ARB_Review_Discipline.md` L216–221** `### The promotion chain, with owners` (Authority 2026-07-30; `e42ef5d9…`) · `PKS_Phase_II_Retrospective_Review.md` §13.2 L311 |
| Locate-level signals (matched sentences; not evidence) | `2026-07-27-knowledge-domain-model.md` §5 "Evolution Rules … when may X become Y" L227–244 (`25035fe1…`): "one Human Decision Event per stage", "stages are never skipped" · `PKS_Phase_II_Execution_Record.md` L389/395: "promotion-ready recorded as a derived state rather than a decision"; readiness ≠ decision · `ADR-AIP-01` L49: "requires a Human Decision Event to move the corpus beyond generated/draft" → pattern: **derived readiness (a predicate) + one decision act per stage** = H3-consistent, **pending reading** |
| Record | `runs/LOCATE-2026-09-27/L0-REL-12-LOCATE.json` `72e12f96…` |

## F-LOG-0095 — L0-REL-13 executed (UL "Promotion Chain" L32; PKS chain with owners L216–221): promotion is one owned act from a derived ready state; **MT1-P1 INCONSISTENT; MT2-P1 the only survivor, robust in all sensitivity codings**; T1 consolidated (candidate); STOP

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-13: yes" |
| Hashes | Phase-02.6 `baaf69aa…` ✔ · PKS_ARB_Review_Discipline `e42ef5d9…` ✔ |
| Source | "Each stage requires its own Human Decision Event. Stages are never skipped" · "Promotion Ready (a state DERIVED BY VERIFICATION, not a decision by anyone) → Authority Promotion Decision (the act)" · the non-collapse family |
| Engine (cumulative 35 cells) | EP-02a SUPPORTED / EP-02c REFUTED (MEDIUM) → MT1-P1 INCONSISTENT; with ES-001.2 → **only MT2-P1 NOT-ELIMINATED**; flag INCONCLUSIVE (EP-01) |
| Sensitivity (`runs/CUMULATIVE/SENSITIVITY.json`) | **MT2-P1 survives in all 4 codings**; each elimination depends on exactly one MEDIUM SELF reading → a robust survivor, **not a selection** |
| Theory | `analysis/theory/CANDIDATE-THEORY-NOTE-05.md`: H3 refined (H3′: one owned promotion act, a derived precondition, no durable grant); I5 upgraded to SOURCE (Frozen ⇒ Authoritative); **T1** axioms A1–A7, falsifiers F1–F6; T1 projects to MT2-P1 |
| Next | independent corroboration of ES-001 L26 and PKS L218; a pre-registered T1 spec; R-39 as the T1 falsification test |

## F-LOG-0096 — the reviewer's pre-L0-REL-13 prompt applied to the already-read fragments (no new read): structured reconstruction (note 06); two corrections (A1 scope; MT framing)

| Field | Value |
|---|---|
| Human instruction (2026-09-27) | "this is old analysis: review and follow prompts". The prompt predates L0-REL-13; its reading was already executed (F-LOG-0095) and is not repeated |
| Output | `analysis/theory/CANDIDATE-THEORY-NOTE-06.md`: the 12-item report; SINGLE-ACT vs TWO-ACT = **sequential states, one act per transition** (L32) / **single-act promotion from a derived ready state** (PKS) → H3′ holds per transition; supersession is an independent, decision-triggered version transition; re-promotion NOT STATED (versioning route HYP; matches the note 03 model result); T1 re-stated as B + A + C (D conceptual only) |
| Corrections | **C-1:** ES-001.2 decision-mediation is SOURCE-supported for governance status only; generalization is HYP. **C-2:** "MT1-P1 INCONSISTENT" means the corpus excludes the behaviour distinguishing MT1-P1 from MT2-P1; it does not choose between their mechanisms. **No model selected** |
| New tension | in the platform-artifact chain, authority and status are coordinates of the chain position (not independent dynamics) vs the Metamodel's ⊥: recorded, not resolved |
| Not used | the reviewer's Phase-02.7 quotations (not L0-released) → a candidate release for corroborating A1 |

## F-LOG-0097 — reviewer downgrades accepted (note 07 erratum); T1 transition-ontology PRE-REGISTRATION r0 frozen before any R-39 re-read; blind evidence-verifier bundle for the two MEDIUM readings

| Field | Value |
|---|---|
| Human instruction (2026-09-27) | downgrade five over-strong claims; T1 is a candidate *ontology*; order = (1) independent verification of the fragments, (2) freeze T1 with a formal READY **before** R-39, (3) then attack T1 with R-39 |
| Erratum | `analysis/theory/CANDIDATE-THEORY-NOTE-07-ERRATUM.md`: E-1 no *specified* persistent grant (scope-limited) · E-2 READY ⇒ EF/EB **open** · E-3/E-4 MT eliminations are "under the declared mapping" · E-5 A1′ governance status only · **E-6 A5′ + A5-dict** (the released L157 does say "produces a superseding version" for the dictionary; generalization is HYP) · E-7 A6′ scope-limited · E-8 MT2-P1 a research result, not a verdict · E-9 T1 = ontology |
| Pre-registration | `prompts/KNOWLEDGEOS-T1-TRANSITION-ONTOLOGY-PREREGISTRATION.md` **r0 frozen at this commit**: primitive state; **READY(x) := kind = PKS ∧ RC ∧ FD ∧ RV** (chain completion only; READY ⇒ EF / EB open; BAR undefined); events; axioms A1′–A7; falsifiers F1–F6; outcomes TRIGGERED / NOT-TRIGGERED / UNDETERMINABLE; the R-39 decision rule; no amendment after the first case. **Circularity disclosure:** prior R-39 exposure (F-LOG-0053); no R-39 text consulted |
| Verifier bundle | `~/F-EVIDENCE-VERIFIER-BUNDLE-01.tar` sha256 `c8bb0ae835293211246ac65e503eb6a0099813ab2b19b207e0f2bf8095f64824`: 4 released fragments (ES-001 L26; PKS L216–221; Phase-02.6 L32, L155–161), byte-exact with fragment hashes; 8 neutral questions (YES / NO / NOT-STATED / AMBIGUOUS + quote); **no codings or conclusions included**; leak scan clean. "Surrounding context" beyond released lines needs an L0 release (not included) |

## F-LOG-0098 — (1) independent evidence verification PENDING (human act; no permitted non-Claude mechanism); (2) L0-REL-14 executed: T1 r0 vs R-39 → OUT-OF-SCOPE for F2 (kind mismatch); T1 untested on F2; R-39 burned as a test for any new READY

| Field | Value |
|---|---|
| L0 act | "L0-REL-14 = YES", strictly bounded to the frozen T1 test; do not alter READY; classify only |
| (1) Verification | `~/F-EVIDENCE-VERIFIER-BUNDLE-01.tar` `c8bb0ae8…4824` → **PENDING**: Codex disallowed (F-LOG-0072), no Claude substitute. Status file `analysis/theory/EVIDENCE-VERIFICATION-01-STATUS.json`. The R-39 test cannot contaminate it (the bundle is sealed; the reader is blind) |
| (2) Source | the rulings register `7795c14b…` ✔, **L25 only** (the R-39 row, 2026-07-26) |
| Classification | kind PKS **NO** (engineering methodology under ES-006.1) · RC / FD / RV **UNKNOWN** · READY **UNDETERMINED** (defined for PKS only) · promotion **YES** |
| Outcome | **OUT-OF-SCOPE for F2** per frozen §6 → T1 r0 neither falsified nor supported; T1 r0 **not amended** |
| Consequence | T1 r0's READY is too narrow for ES-006.1 promotion; any READY for that kind written now is contaminated by R-39 → **the next test needs a different, unread engineering-knowledge promotion case** |
| Source facts (not MT-interpreted) | normal bar: evidence from > 1 bounded context; the DA approved with ONE; validation prospective ("confirms … or produces the amendment evidence"); "this exception does not weaken the rule"; "promotion rule intact" |
| Record | `analysis/theory/T1-TEST-01/RESULT.json` |

## F-LOG-0099 — evidence verification 01 COMPLETED by a blind agent (SECONDARY_REVIEW, accepted by L0 as sufficient): 7/8 agree; the constraint-driving text facts are corroborated; Q5 (object kind) disagrees

| Field | Value |
|---|---|
| L0 act (verbatim, mid-run) | "use a agent to authorize verification. that is enough" |
| Method | headless Claude CLI in the non-git `/tmp/fevb.2bEYpb`, bundle `c8bb0ae8…4824` only; tools Bash/Read/Write; no web, no Agent; self-check **CLEAN**; hashes OK; transcript audit: 5 tool calls, all inside the workspace. **SELF coding sealed first** (`SELF-CODING.json`), with the equivalence rule NO ≡ NOT-STATED fixed before opening the answers |
| Answers | `EVIDENCE-VERIFICATION-01/ANSWERS.json` sha256 `dae305be575a2427f0844fb79f1db9eb7194dbbcfcf5ed318724f424dda5ae1f` · class **SECONDARY_REVIEW** (same family; not labelled INDEPENDENT) |
| Comparison (`COMPARISON.json`) | Q1 EXACT (explicit decision, gain + loss) · Q2 EXACT (Ready = derived, not a decision) · Q3 EXACT (the decision follows Ready) · Q4 EXACT (no skipping) · Q6 EQUIVALENT (no persistent grant) · Q7 EXACT (ADR → superseding version, **this dictionary only**) · Q8 EXACT (re-check NOT-STATED) · **Q5 DISAGREE**: the fragments do not each state their object kind |
| Consequence | the textual facts behind the G1 (ES-001.2) and C1 (PKS chain) codings are **corroborated**; the **mapping bridge to MT variables remains MEDIUM** (the agent's answers are not added as engine cells, which would launder the mapping); Q5 confirms the object-kind scope caution |

## F-LOG-0100 — L0-REL-15: locate (4 candidates) → generic ES-006.1 process → **T1 r1 frozen** (`136706d79`) → unread case B (Layer Verification Rule) tested: **Case C, readiness not sufficient** (conditional); F8 non-collapse strongly supported; "frozen" overloaded (Case E)

| Field | Value |
|---|---|
| Human instruction (2026-09-27) | the agent check is recorded as **SECONDARY SAME-FAMILY CORROBORATION** (not independent); build T1 r1 from the generic ES-006.1 process, **not from R-39**; freeze before reading an unread case; no ML, no MK, no MT verdict |
| Locate (titles and commit metadata only) | A: rulings register L22 R-36 "AI Architecture Promotion Review ADOPTED" (2026-07-09, **predates** the ES-006 consolidation → reserve) · **B: `engineering/knowledge/methodology/Layer_Verification_Rule.md`** (commits `f3f9ff31e` "PROPOSED, not adopted", `ec5c2ef76` "FROZEN (not adopted)") · C: `b222e53a6` (the same file) · D: `f63015cd1` (rule text). Selected B (same kind as R-39, post-ES-006, unread) |
| T1 r1 | `prompts/KNOWLEDGEOS-T1-R1-PREREGISTRATION.md` `2ec4d883…`, frozen at `136706d79` before reading B: READY_E := E1 (necessity evidence cited) ∧ E2 (explicit proposal) ∧ E3 (qualification, if target ≥ Engineering Standard); BAR undefined (R-39's multi-context bar **excluded**); the relations Ready→Promote and Promote→Ready are testable; F2-E, F7, F8 |
| Case B read | header L1–11, §4 L142–165, §6 L170–182 (`8acc1f0a…`) |
| Result | kind in scope · E1 YES · E2 YES · E3 / rungs UNKNOWN · decision NONE · PROMOTED NO · FROZEN YES → **F7 TRIGGERED (Case C: READY_E ∧ ¬PROMOTE, conditional on target < Engineering Standard)** → H-R3 (Ready ⇒ Promote) refuted for this case; T1 r1 survives · F2-E not triggered · **F8: "Frozen ≠ adopted … load-bearing"** |
| Case E | "frozen" = author-freeze here vs a governance stage after Authoritative in C_platform → **split FROZEN** (HYP, r2) |
| Record | `analysis/theory/T1-TEST-02/RESULT.json` |

## F-LOG-0101 — L0-REL-16: an adopted post-consolidation case (P1 = R-41 / ES-004.3) tested against the frozen T1 r1 → **N3: untested on necessity** (E2, E3 UNKNOWN); near-counterexample identified; four ontology findings

| Field | Value |
|---|---|
| Human instruction (2026-09-27) | the necessity test on a genuinely adopted ES-006.1 case; T1 r1 frozen; no r2 / MK / ML / MT verdict; R-36 in reserve (predates the consolidation) |
| Locate (titles and commit metadata) | P1 R-41 (2026-07-30, "adopted as a permanent documentation standard"; commit `7632b5685` ES-004.3) · P2 ES-001.3 (2026-08-16, commit `298db4609`) · P3 R-100 (a governance rule; kind unclear) · R-73–75 excluded (product architecture) · R-36 reserve |
| Read | register L27 (R-41; `7795c14b…`) · ES-004 L29–64 (ES-004.3; `4d500f41…`) |
| Result | target Engineering Standard · **E1 YES** (the WP-1 closure inconsistency) · **E2 UNKNOWN** (origin = "Principal Architect instruction") · **E3 UNKNOWN** (all dated 2026-07-30; "refined … the same day") · decision YES · PROMOTE YES → **READY_E UNKNOWN → N3** |
| Near-counterexample | E2 = NO ∧ E3 = NO would make it N2 (PROMOTE ∧ ¬READY_E). Minimum missing evidence: the commit `7632b5685` message; the 2026-07-30 session log (R-41 section) |
| Ontology findings | authority-initiated adoption (PA / ARB / DA: authority is a parameter; tension with ES-001.2) · **kind-dependent two-phase** (work plan: Authorized → Executing) · **two-layer record** ("decision text immutable; status annotations may evolve": strong support for H1) · state uniqueness |
| Record | `analysis/theory/T1-TEST-03/RESULT.json` |

## F-LOG-0102 — L0-REL-17 executed (commit `7632b5685` message; session log 2026-07-30 L1–41): P1 → **N2 CONDITIONAL, the first direct counterexample to T1 r1 necessity, under two MEDIUM readings**; the trace checker built

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-17: yes" |
| Read | the commit message (no proposal or qualification mentioned) · `.claude/sessions/2026-07-30.md` L1–41 (`744b7d67…`): adoption "by explicit PA instruction"; the rule "generalizes the WP-1 status-line correction"; then "REFINED ROLE-BASED"; then "**VALIDATION EXECUTED — VALIDATED UNCHANGED**" (all appended after the adoption) |
| Classification | **E2 UNKNOWN** (origin = instruction; absence not converted) · **E3 NO (MEDIUM)** (the only validation is recorded after adoption; same-day genesis) · READY_E NO · PROMOTE YES |
| Outcome | **N2 CONDITIONAL**: PROMOTE ∧ ¬READY_E, provided (i) E3 = NO and (ii) the ES-006.1 ladder governs a *documentation* standard (R-41 never cites ES-006.1). Otherwise N3 / OUT-OF-SCOPE. **Needs corroboration and an L0 scope ruling** |
| Theory signal | R-39 and R-41 both show **adopt-then-validate**; R-39 recorded an exception, R-41 none → a scope boundary or norm/practice divergence; HYP (r2): separate norm and practice layers; *provisional adoption with a validation obligation* as a transition type |
| Computation | `analysis/theory/trace_check.py` → `TRACE-CHECK-RESULT.json`: I-a (evidence before promotion) HOLDS · I-d (explicit named authority) HOLDS · I-e (frozen ≠ adopted) HOLDS (B) · I-b (proposal before) **NOT-RECORDED** (absence ≠ violation; a checker defect was fixed before sealing) · **I-c (qualification before, ≥ Engineering Standard) and I-f (a norm gap needs a recorded exception) VIOLATED in P1 only, depending on READING-labelled facts** |

## F-LOG-0103 — P1 resolved by evidence, not by L0 preference: scope located in the primary source (ES-006 L5 "standards candidates"; **ES-006 L3 Status: PROPOSED**); temporal order corroborated blind (2/2 EXACT) → **P1 = N2 against T1 r1 as a claim about practice**; conformance: **DEVIATES-NORM-NOT-IN-FORCE**

| Field | Value |
|---|---|
| Human instruction (2026-09-27) | do not ask L0 for a scope YES/NO; locate the scope evidence first; blind temporal corroboration; classify; formalize the norm / observed / explanation layers; no r2 yet |
| Scope (locate only) | 22 co-occurrence sentences / 14 files; primary ES-006 (hash ✔) matched lines: **L3 "Status: PROPOSED"**, **L5 "Scope: engineering knowledge only (patterns, evidence, research artifacts, standards candidates) … OUT of scope: Project Knowledge"**; ES-004 L3 PROPOSED / L5 all engineering records; an explicit "ES-006.1 governs ES-004.3": **NOT-FOUND-IN-SEARCH**; R-39 calls ES-006.1 "the normal promotion rule" |
| Temporal corroboration | bundle `~/F-EVIDENCE-VERIFIER-BUNDLE-02.tar` `f2cd4134…4078` (session log L1–41; 2 neutral questions; leak scan clean; a README defect was fixed **before** any run); blind headless agent CLEAN; `ANSWERS.json` `7494a0cf…7152`; SELF coding sealed first; **Q1 EXACT (adoption before validation) · Q2 EXACT (no qualification or validation before adoption)**; SECONDARY same-family |
| P1 | in scope (SOURCE-DERIVED, MEDIUM: standards candidate) · E3 NO (corroborated) · PROMOTE YES → **N2**: T1 r1's necessity claim fails **as a claim about practice**. The norm it was built from is itself **PROPOSED** → the failure exposes T1 r1's conflation of a proposed norm with observed practice |
| Pattern | R-39 and R-41: adopt-then-validate vs the norm evidence → qualification → promotion; R-39 records an exception; R-41 none (the norm PROPOSED) |
| Computation | `analysis/theory/conformance_check.py` → `CONFORMANCE-RESULT.json`: **P1 DEVIATES-NORM-NOT-IN-FORCE** · R-39 UNDETERMINED (rung) with the exception recorded · B VACUOUS; each result carries its SOURCE / READING dependencies; absence is never a deviation |
| Not done | T1 r1 unmodified; **no r2 drafted**; no MT verdict; no MK; no ML |

## F-LOG-0104 — L0-REL-18 (ES-006 status history, metadata plus Status lines only): PROPOSED in all 7 versions; no ratification located; **force attaches per hosted rule** → P1's explanation corrected from "norm not in force" to **UNDETERMINED (force ambiguous)**

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-18: yes" |
| History | `git log --follow` of ES-006 (7 commits: `d63202b8c` 2026-07-11 "PROPOSED, STOP for ARB" … `668cc7b22` 2026-07-26); the **Status line is PROPOSED in every version** [SOURCE] |
| Ratification | register row titles: none ratify ES-001…006 (R-44/R-45 concern A-1/A-2) · Standards Index L3 "PROPOSED — awaiting ARB review" · commit `aee484e9c` "(ratification-ready)" → **ratification NOT-FOUND-IN-SEARCH** (absence is not proof) |
| Finding | hosted rules carry their own provenance: ES-006.1 "(ARB, refined 2026-07-11)", ES-001.2 "(ARB 2026-07-11)", ES-004.3 "(PA instruction … R-41)" → **normative force is rule-granular; the document status is container-level** [DERIVED from SOURCE]. It explains practice citing rules as operative inside PROPOSED documents |
| **Self-correction** | F-LOG-0103's P1 explanation "DEVIATES-NORM-NOT-IN-FORCE" was too strong → **P1 = DEVIATES, explanation UNDETERMINED (norm force AMBIGUOUS: document PROPOSED, rule ARB-dated)**. `conformance_check.py` now carries both force readings. P1 remains N2 against T1 r1 as a claim about practice |
| Theory | add **Force(rule, t)** as distinct from **Status(document, t)**. For r2: the normative layer is indexed by *rule*, not by document |

## F-LOG-0105 — T1 r2 (norm / observed / explanation) PRE-REGISTERED and frozen together with its instrument, before P2 is read

| Field | Value |
|---|---|
| Human act (verbatim) | "T1 r2: draft" (an earlier write was rejected, then "continue") |
| Pre-registration | `prompts/KNOWLEDGEOS-T1-R2-PREREGISTRATION.md`: primitives (object; rule with its own provenance; document status; authority set 𝒜 = {ARB, DA, PA, Authority}) · normative layer per rule, **Force(ρ, t) ≠ Status(D, t)** (IN-FORCE / AMBIGUOUS / NOT-IN-FORCE / UNKNOWN) · Applicability · ES-006.1 requirements R-ev, R-q, R-dec, R-prop · observed traces with provenance and order basis · FreezeAuthor ≠ FreezeGovernance · ProvisionalPromote · conformance classes (OUT-OF-SCOPE / VACUOUS / **NOT-RECORDED** / UNDETERMINED / DEVIATES / CONFORMS) · explanation order (EXCEPTION-RECORDED → FORCE-NOT-IN-FORCE → FORCE-AMBIGUOUS → VERSION-DIFFERENCE → UNEXPLAINED) · claims C1 decision mediation, C2 non-collapse, **C3 governed deviation** (IN-FORCE deviation ⇒ recorded exception), C4 authority parameter · r1's universal necessity **dropped** |
| Instrument | `analysis/theory/t1r2_check.py` (implements §2, §4–§6; case = a JSON trace) |
| Development cases (never tests) | R-39, B, P1: sanity run as designed (R-39 provisional; B vacuous; P1 R-q DEVIATES → FORCE-AMBIGUOUS; no claim triggered) |
| Test protocol | unread cases only; the first is **P2 = ES-001.3**; encode τ before running; no amendment after the first test read |

## F-LOG-0106 — L0-REL-19: the first genuine test of T1 r2 (P2 = ES-001.3) → **survives (C1–C4 NOT-TRIGGERED), but a weak test** (no deviation observable); a norm-text vs norm-as-practised gap found

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-19: yes" |
| Read | commit `298db4609` message · ES-001 L28–82 (ES-001.3, the last entry; `59df3c64…`) |
| Protocol | trace encoded, quotes verified, and **committed before the checker ran** (`c6ccad4a0`; `P2-trace.json` `fa0a9331…`); instrument hash `0e1bbc39…` = frozen |
| Result (`T1R2-TEST-01/RESULT.json`) | applicability **SOURCE-STATED** (the rule cites ES-006.1) · force AMBIGUOUS (rule ARB 2026-07-11; doc PROPOSED) · R-ev CONFORMS · R-dec CONFORMS (ARB) · **R-q NOT-RECORDED · R-prop NOT-RECORDED** · no deviation · **C1–C4 NOT-TRIGGERED** · the only READING = the rung |
| Strength | weak: C3 not exercised (no deviation); the absence-based requirements are unresolved by design |
| Findings | (1) the **"repeated pattern, not a single occurrence" bar is attributed to ES-006.1 in practice** (P2; F0018; CLAUDE.md), but is absent from the released ES-006.1 text (L14–21) and from CAP-001 §9 → a norm has text, provenance and **interpretation-as-practised** forms (HYP, r3: Interpretation(ρ, t)) · (2) non-collapse extended: recommendation ≠ decision ≠ authorization ≠ execution; two-phase for governed work (supports kind-dependent H3′) · (3) a self-declared R-37 / ES-006.1 conformance record |

## F-LOG-0107 — L0-REL-20 (b) LOCATE-ONLY: the "repeated evidence / single occurrence" bar is **never in ES-006.1's text**; it originates as a track-local ARB verdict and drifts into ES-006.1's *interpretation* (citation drift; explains OBS-SF-1)

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-20: b" |
| Method | matched phrases and headings in all tracked docs plus `.claude/` memory and instructions (F-lane, F-extraction, S-series, brainstorming excluded); origins via `git log -S` (metadata only); **no body read** |
| Counts | 228 hits / 131 files: rule text 6 (R-39 L25 ×4; ES-001.3 L82 ×2) · memory/instructions 54 · verification reports 10 · research artefacts 31 · other 127 · methodology modules 0 |
| Genealogy | **2026-07-26** chair's closing verdict (session log L175; `82805689b` "ARB closing assessment", EPIC-004 track self-governance) → **2026-07-26 R-39**: "ES-006.1 **+** the EPIC-004 track's own self-governance" → **2026-08-01** `.claude/MEMORY.md` L389 "Engineering promotion rule (adopted 2026-08-01)" (`5af8111cf`; register adoption NOT-FOUND-IN-SEARCH) and `.claude/CLAUDE.md` L581 "Never promote methodology from a single occurrence" → ES-006.1 (`15468d825`) → **2026-08-16 ES-001.3** L82 cites it as ES-006.1's requirement · **ES-006.1 text: absent** |
| Finding | **citation drift** (locate-level): a local principle migrates into a general rule's interpretation while its text is unchanged; **explains OBS-SF-1** (F0018's attribution) |
| Hypothesis (r3) | norm = **Text(ρ, t) · Force(ρ, t) · Interpretation(ρ, t)**; conformance can be tested against text or interpretation, and they may disagree (P1 departs from the text order; P2 claims the interpreted bar) |
| Record | `runs/LOCATE-2026-09-27/L0-REL-20-BAR-LOCATE.json` |

## F-LOG-0108 — L0-REL-21: citation drift **confirmed by reading**, with semantic sharpening; normative pluralism (two competing ladders); host legitimacy; the memory "adopted" heading lacks a recorded adopting act → r3 justified (HYP)

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-21: yes" |
| Read | session log 2026-07-26 L173–180 (`5c579678…`) · MEMORY L387–395 (current `d80abcbd…`) · commit `5af8111cf` message |
| Confirmed | (1) origin = a **descriptive, track-local** verdict on the EPIC-004 method ("principles promoted only on repeated evidence — and explicitly NOT promoted when evidence is domain-specific"); no "across contexts" · (2) R-39 sharpens it to "more than one bounded context" and conjoins it with ES-006.1 · (3) MEMORY "Engineering promotion rule (adopted 2026-08-01)" = a **different ladder** (… Repeated Evidence → Operational Validation → ARB Approval → Canon) + five criteria ("across contexts, not merely across instances"); **its commit records no adopting act** ("No promotion was performed"; "MEMORY gains the promotion rule"; "referred rather than decided") · (4)–(5) attribution to ES-006.1 alone (CLAUDE.md 08-01; ES-001.3 08-16) · **ES-006.1 text unchanged throughout** |
| Findings | **citation drift with semantic sharpening** (Interpretation strengthened; Text unchanged) · **normative pluralism** (two ladders for one kind; the memory ladder puts validation *before* approval, the reverse of R-39 / R-41 practice) · **host legitimacy** (the bar's only full text host is declared non-authoritative) · **candidate C1-type issue**: "(adopted …)" asserted without a recorded act → not a proven violation |
| Decision input | **r3 justified (HYP):** norm = Text · Force · Interpretation · HostLegitimacy; plural norms per kind. Not drafted (awaiting "T1 r3: draft") |
| Record | `analysis/theory/DRIFT-CONFIRM-01/RESULT.json` |

## F-LOG-0109 — T1 r3 (Rule / Interpretation / Practice) pre-registered and frozen with its instrument; F-LOG-0108 corrected (the drift is plural and non-monotone)

| Field | Value |
|---|---|
| Human instruction (2026-09-27) | the interpretation is a **separate epistemic object**, not a norm component; host legitimacy is a source / admissibility property; block the circularity; conformance against text vs interpretation; minimal administration |
| Pre-registration | `prompts/KNOWLEDGEOS-T1-R3-PREREGISTRATION.md`: Rule (Text, provenance, Force, Applicability) · host admissibility (NORMATIVE / NON-NORMATIVE) · **Interpretation ι** (requirements, date, source, authority, host, **its own applicability and date**) · **eligibility gate** (a ∈ 𝒜 ∧ normative host ∧ explicit provenance → may judge; otherwise descriptive) · requirement DSL (before, before_if_high, decision, attr_min) · classes CONFORMS / DEVIATES / NOT-RECORDED / UNKNOWN / OUT-OF-SCOPE / VACUOUS + EXCEPTION / FORCE-AMBIGUOUS / FORCE-NOT-IN-FORCE / UNEXPLAINED · **divergence matrix** (ΔTI, ΔTO, ΔIO; pattern labels) · claims C1–C4 (C3 extended to eligible, applicable, prior interpretations) · **dropped:** monotone drift, "no decision grounded in a non-eligible interpretation" (→ descriptive H-circ) |
| Instrument | `analysis/theory/t1r3_check.py`; sanity on development cases: P2 → "practice follows interpretation drift" (instance bars met; context bars NOT-RECORDED); P1 → text DEVIATES (q) FORCE-AMBIGUOUS, no interpretation judges it (R-39 scope UNKNOWN; the others postdate it); no claim triggered |
| **Correction** | F-LOG-0108 said "monotone strengthening". Graded, the bar readings are 2 (07-26) → 3 (R-39) → 3 (MEMORY) and **1 (CLAUDE.md, the same day)** → 1 (ES-001.3) → **plural and non-monotone** |
| Next test (highest information) | **R-100** (register L86, 2026-08-04, a rule adoption after R-39; unread; one row). It can exercise **C3** via R-39's eligible context bar, if in its scope. Backup: the 2026-08-23 session-log "Non-actions … single occurrence" (a rejection grounded in the bar) |

## F-LOG-0110 — L0-REL-22: r3 tested on R-100 → **C3 UNDETERMINABLE** (nothing decidable in the row); an **instrument defect found** (vacuous "conforms"), not patched; r3.1 fix scheduled

| Field | Value |
|---|---|
| L0 act (verbatim) | "L0-REL-22:yes" |
| Protocol | register `7795c14b…` ✔; L86 read; case encoded, quotes verified, **committed before the run** (`f3f3baa4e`); instrument `d7277064…` = frozen |
| Instrument output | force AMBIGUOUS · text ev NOT-RECORDED / q UNKNOWN / dec CONFORMS · I-R39 (eligible), I-MEMORY, I-CLAUDE judge → bar NOT-RECORDED · I-ES0013 postdates · pattern "O ⊨ I" · C1–C4 NOT-TRIGGERED |
| **Correct classification** | the pattern and the C3 result are **vacuous** (no decidable requirement) → **C3 UNDETERMINABLE**, pattern UNDETERMINED; C1 / C2 / C4 not triggered; **NEW-AUTHORITY "ARB CHIEF"** |
| Defect | the instrument treats "no DEVIATES" as conformance. **Not patched** (no post-read amendment); **r3.1 instrument fix to be frozen before the next test**: CONFORMS requires ≥ 1 MET; undecidable-only → UNDETERMINABLE |
| Findings | the authority **sets the kind in the act** ("ENGINEERING GOVERNANCE PRINCIPLE — NOT a constitutional one") · **non-inheritance of authority** ("No stage inherits the next one's authority"; "completing discovery grants NO mandate to model"): completion ≠ mandate; two-phase governed work · generalized from **one context** (WP-4) with no recorded evidence or exception |
| Minimum missing evidence | the R-100 necessity evidence: commit `6c6d5866c` message / the 2026-08-04 session-log section |

## F-LOG-0111 — r3.1 instrument frozen (three-layer epistemic semantics; the vacuous-conformance defect fixed); r3 left frozen; regression passed; before L0-REL-23

| Field | Value |
|---|---|
| Human instruction (2026-09-27) | correct the r3.1 logic formally: requirement / aggregate / claim layers never conflated; CONFORMS iff all MET; DEVIATES iff ≥ 1; UNDETERMINABLE otherwise; claims SUPPORTED / REFUTED / UNDETERMINABLE / NOT-TRIGGERED; keep r3 frozen; regression before new evidence |
| **Adopted with one correction** | the reviewer's "C3 REFUTED iff … DEVIATES" omitted the exception condition; frozen as **REFUTED iff a decisive DEVIATES (Text IN-FORCE, or an eligible / applicable / prior interpretation) lacks a recorded exception**; SUPPORTED iff it has one; NOT-TRIGGERED iff every decisive set CONFORMS; UNDETERMINABLE otherwise (incl. a Text deviation under AMBIGUOUS / UNKNOWN force) |
| Instrument | `analysis/theory/t1r3_1_check.py` `e9a6365b…` (imports the frozen r3 `d7277064…` read-only; replaces only aggregation and claims) |
| Selftest | 10/10 (MET+MET=CONFORMS · MET+UNKNOWN=UNDETERMINABLE · NOT-RECORDED+UNKNOWN≠CONFORMS · MET+DEVIATES=DEVIATES · none=OUT-OF-SCOPE · the C3 REFUTED / SUPPORTED / NOT-TRIGGERED / UNDETERMINABLE cases) |
| Regression (development only) | R-100 → text UNDETERMINABLE, C3 UNDETERMINABLE, pattern UNDETERMINED (r3's vacuous label corrected) · **dev P2's r3 label "practice follows drift" was the same defect → UNDETERMINED** · P1 → text DEVIATES, C3 UNDETERMINABLE (AMBIGUOUS force) · NEW-AUTHORITY "ARB CHIEF" (R-100) · files `T1R3-TEST-01/R31-REGRESSION-*.json` |
| History | the F-LOG-0110 R-100 result stays as recorded (immutable) |

## F-LOG-0112 — L0-REL-23 executed to its boundary: (a) commit `6c6d5866c` read, recording **no necessity evidence and no exception**; (b) **SECTION-NOT-FOUND-BY-HEADING**; C3 for R-100 stays **UNDETERMINABLE** under r3.1; STOP for L0 designation

| Field | Value |
|---|---|
| L0 act | "r3.1 + L0-REL-23: yes" (via the reviewer's instruction; r3.1 frozen first, F-LOG-0111) |
| (a) | commit `6c6d5866c` (a PROGRAM_STATUS rewrite): "R-100 ADOPTED — the new-commission rule, generalized beyond WP-4 …"; its consequence restated ("completing discovery grants no mandate to model …"); **no evidence of necessity, no exception, no bounded-context count** |
| (b) | `.claude/sessions/2026-08-04.md` (`6309c2e3…`): **no heading names R-100 or the new-commission rule**. The most plausible section, L576 "WP-4C-2 — commission closed, handed to the domain owner", was **not read**: choosing it would be the reader choosing scope (the L0-REL-01 precedent) |
| C3 (R-100) | two records (ruling row + commit) record the adoption without evidence or exception → the bar requirement **NOT-RECORDED, not DEVIATES** → **UNDETERMINABLE** (r3.1); no new trace event, so the case JSON is unchanged |
| Candidate invariants (supported again by (a); not canonical) | completion ≠ mandate ("completing discovery grants no mandate to model, and completing a model grants no mandate to implement") · non-inheritance of authority between stages |
| Needs L0 | designate the (b) section (e.g. L576) or close R-100 as UNDETERMINABLE and move to a different C3 case |

## F-LOG-0113 — L0-REL-23b executed once: `.claude/sessions/2026-08-04.md` L576 section read; **no R-100 evidence** → **R-100 CLOSED, C3 UNDETERMINABLE**; hypotheses H1–H5 formalized and a next case ranked by EIG; STOP for L0

| Field | Value |
|---|---|
| L0 act | "L0-REL-23b" (via the reviewer's instruction: read L576 once, bounded by the next heading; close R-100 whatever the result) |
| Read | `6309c2e3…`, L576–585 (true bound: the next heading is **L586** "Next Steps", not L614 as planned). L586–613 were seen incidentally in the same read; they are **not used as evidence** (outside the release) |
| Content | WP-4C-2 discovery closed and handed over; R-89 number collision; **R-90 withdrawn before adoption, because it was operational acceptance, not a constitutional decision; "the register holds constitutional decisions"; the number RETIRED, not recycled** (SOURCE: a register admissibility rule, recorded as an observation); claims narrowed at the ARB's direction. **No mention of R-100; no necessity evidence, context count or exception** |
| R-100 | C3 **UNDETERMINABLE** (r3.1; bar NOT-RECORDED, never DEVIATES). **CLOSED**; no further release searches for it |
| Hypotheses | `analysis/theory/HYPOTHESIS-DISCRIMINATION-01.md` + `model_discrimination.py` (`50450c9f…`), output `2d8d61ac…`. H1 evidence-first · H2 authorization-before-validation · H3 kind-dependent · H4 force/applicability · H5 provisional adoption + obligation. P1 (development) excludes H1, and H3 under the knowledge reading. **Structural (MODEL-DERIVED):** H4 is unfalsifiable on ES-006.1-text cases (force always AMB); under IN force, H1–H4 are observationally equivalent on low-rung knowledge cases |
| Next case (MODEL-DERIVED, maximin EIG, invariant in u) | 2026-08-04 **L216 "Discovery Freeze v1.0 adopted"**; runner-up L82. The IN-force cases (L493 / L205) are needed later to test H4 |
| Needs L0 | L0-REL-24: release L216 (bounded by its next heading), or choose otherwise |

## F-LOG-0114 — instrument r2 (Ev / Qual / Val separated; H3a / H3b; EIG → MDS-U) frozen with the case spec; L0-REL-24 executed (L216–L224): **SILENT**; correction to F-LOG-0113; STOP for L0

| Field | Value |
|---|---|
| L0 act | "continue with L0-REL-24, but repair the discrimination instrument first" (reviewer instruction relayed by L0) |
| Freeze | `181d78e7e`: `model_discrimination_r2.py` `d14d79af…` (selftest 7/7) + `L216-CASE/CASE-SPEC.json` `1c1aa887…`, **before** the read; kind / high UNKNOWN as source facts |
| Read | `.claude/sessions/2026-08-04.md` `6309c2e3…` L216–L224 (next heading L225), nothing else |
| Result | "Discovery Freeze v1.0 adopted" (passive; no adopter). Every ordering component NOT-RECORDED; AUTH NOT-RECORDED; SEM UNKNOWN; force AMB → **SILENT, no hypothesis eliminated**. C4 UNDETERMINABLE |
| Correction (F-LOG-0113) | P1 refutes H1 **only under the VAL / QUAL∧VAL norm readings**; under QUAL / QUAL∨VAL (the r3 encoding) H1 survives (P1's qualification NOT-RECORDED). r1's exclusion was an artefact of merging Qual and Val. H3's exclusion depended on an assumed kind; both H3a and H3b survive |
| Observations (one occurrence each; not theory) | evidence-gated reopening criterion → HYP **H-asym** (entry vs exit burden) · refinement / exception ≠ discovery · freezes ordered by strength (a third sense of "freeze") · the corpus practises open-world empty cells · an adoption acting as a process phase transition (a candidate regime/phase kind) |
| Method lesson (2 occurrences) | session-log bullets are silent on ordering; the per-source silence probability dominates → weight **observability** in case selection |
| Next (MODEL-DERIVED) | an IN-force knowledge case whose heading signals an evidence count: **2026-08-15 L493 vocabulary ruling ("single occurrence")** — the only candidate that can refute H4 and is likely non-silent on EV |
| Record | `analysis/theory/HYPOTHESIS-DISCRIMINATION-02.md`, `L216-CASE/{OBSERVATION,RESULT}.json` (`a7ade762…`, `db87d142…`) |

## F-LOG-0115 — L0-REL-25 executed (2026-08-15 L483–L495): no ordering elimination, H4 untested; a **minimal pair** (binding ruling vs refused promotion) → HYP **H6 (two routes to normativity)**; STOP for L0

| Field | Value |
|---|---|
| L0 act | "L0-REL-25: yes" |
| Freeze | `9e610e412`: `L493-CASE/CASE-SPEC.json` `e516e174…` before the read; instrument r2 `d14d79af…` unchanged |
| Read | `.claude/sessions/2026-08-15.md` `b5c2487c…` L483–L495 (L493 lies inside the "Ninth" section; next heading L496), nothing else |
| Result | Act A (PO/ARB binding vocabulary ruling, "applies to ALL future documentation"; no evidence basis stated): AUTH PRESENT, the rest NOT-RECORDED, force UNKNOWN → **no hypothesis eliminated**. Act B: an observation "NOT promoted to methodology — single occurrence, and ES-006.1 forbids promoting from one" = a Reject (every hypothesis permits it). **H4 untested** (no deviation under IN force) |
| Interpretation | Act B cites ES-006.1 for a bar its text lacks (F-LOG-0107); the wording matches I-CLAUDE (non-eligible) → T ≠ I ∧ O ⊨ I; a new genealogy point (08-15); an H-circ instance |
| HYP H6 | route ∈ {RULING, PROMOTION}: the evidence bar binds PROMOTION only. Retrodictively consistent with P1 ("by explicit PA instruction"), R-100, L493-A/B and P2; tension with R-39 (a ruling recording an exception). **Post-hoc, not tested.** Confound: route = object type in the pair. Falsifiers: a one-occurrence PROMOTION without exception; a RULING deferred for insufficient evidence |
| Other observations | **H-planes**: state is a vector over planes (execution / governance lifecycle / findings; "not one state machine", PO/ARB) · prospective rule applied retroactively · non-mandate family, third occurrence |
| Next (MODEL-DERIVED) | **2026-08-04 L205 "Meta-principle freeze adopted"**: tests H4 (IN via I-R39 if in scope) and H6 (PROMOTION-route prediction) at once |
| Record | `analysis/theory/HYPOTHESIS-DISCRIMINATION-03.md`, `L493-CASE/{OBSERVATION,RESULT}.json` (`ca015569…`, `e1b579e4…`) |

## F-LOG-0116 — L0-REL-26 executed (2026-08-04 L205–L215): H4 untested (force UNKNOWN, third attempt); H6 family confounded again; **H-PLANES supported (factoring transitions)**; lattice: **B1 single lifecycle and C5 authority-alone ELIMINATED**; STOP for L0

| Field | Value |
|---|---|
| L0 act | reviewer instruction relayed by L0 ("L0-REL-26: L205"; force UNKNOWN unless source-supported) |
| Freeze | `d0daa60c6`: `L205-CASE/CASE-SPEC.json` `6276589a…` before the read |
| Read | `6309c2e3…` L205–L215 (next heading L216), nothing else |
| Source facts | A1 meta-principle freeze by an unnamed "directive" ("counting and evidence intake continue; discovery of meta-things stops"); A2 protected sentence preserved verbatim; A3 "reclassified as a KNOWLEDGE LIFECYCLE … identity upgrade, same gate"; A4 "REVERSIBLE (precision earns; lapse removes)"; A5 "designed, not exercised" |
| H4 | untested: Applicability(I-R39) not source-supported → force UNKNOWN; r2 no elimination |
| H6 family | A1 confounded (route/kind/operation/effect aligned); A3 weakly disfavours strict H6-K; R-39 (development) is the one known route≠operation case and weakly favours H6-O |
| H-PLANES | four factoring acts (L493 + L205-A1/A3/A4): some coordinates change, others stated unchanged; the named dimensions differ between sources — not unified |
| Lattice (MODEL-DERIVED) | **B1 (single linear lifecycle) ELIMINATED** (L493) · **C5 (authority alone decides evidence) ELIMINATED** (L493 minimal pair) · C3 weakly disfavoured · C4 weakly favoured · A6 force untestable so far |
| Next | stop corpus archaeology; pre-register the minimal model M = (O,K,R,F,A,S,E,δ) with frames per operation; next falsifier = a route≠operation case, best sought in the rulings register |
| Record | `analysis/theory/HYPOTHESIS-DISCRIMINATION-04.md`, `L205-CASE/{OBSERVATION,RESULT}.json` (`44f918e0…`, `399f6a9d…`) |

## F-LOG-0117 — M0 pre-registered (`1760c863e`) then verified (development data only): FREEZE acts on a process coordinate; **object state is non-Markov, restored by a finite tombstone**; force-sensitive guards reason-inconsistent with L493-B; conjecture G-R/force-insensitive; next = register completeness convention; STOP

| Field | Value |
|---|---|
| L0 act | reviewer instruction relayed by L0 ("1m": pre-register the minimal model, build the verifier, stop) |
| Lattice wording corrected | B1: *single linear lifecycle order* REFUTED; scalar encoding irrelevant/untested; product and multi-plane state = candidates. C5: *authority alone is not a sufficient discriminator* of the evidence requirement (not "authority never matters") |
| Instrument | pre-registration `fde7995f…`; `m0_check.py` `42cfef42…` (BFS, 8 guard models = {route, kind, operation, effect} × {force-insensitive, force-sensitive}; selftest 6/6 with mutation); output `M0-RESULT.json` `25c31558…` |
| Results (MODEL-DERIVED) | frame axiom holds · FREEZE frame {standing} cannot express L205-A1, {regime} can · governance-closed ∧ execution-open reachable · adopted ∧ ¬validated reachable · lapse lowers standing, reopening needs counter-evidence (two non-conflicting exits) · only G-K is gate-sensitive to RECLASSIFY · **R-90 "retired, not recycled" makes object state non-Markov; a 3-valued registry coordinate restores it** · all force-sensitive models REASON-INCONSISTENT with L493-B · every model pair separable by one invocation |
| Conjecture | G-R / force-insensitive is the only model consistent with P1 under every reading and with L493-B's stated reason (conditional on P1's exception being absent) |
| Next | L0-REL-27: locate-only in the rulings register header for an exception-completeness convention; it decides whether P1 separates G-R from G-O |
| Record | `analysis/theory/M0-REPORT.md` |

## F-LOG-0118 — L0-REL-27 locate-only (rulings register structure): **no exception field and no completeness convention** → closed OPEN-WORLD; P1's exception stays NOT-RECORDED; G-R vs G-O **not** discriminated; STOP

| Field | Value |
|---|---|
| L0 act | "L0-REL-27: YES" (locate-only; strict stop; M0 / verifier / lattice untouched) |
| Source | `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` `7795c14b…` (unchanged since F-LOG-0103), 86 lines |
| Structure (locate) | headings L1 (title), L8 (historical index), L12 "Rulings (living, append-only)"; table header L14 **`# | Date | Ruling | Effect`**; rows L16–L86; **no heading, footer or prose after L15**. Matched lines above the rows: L6 (why the file exists: "this log records ongoing rulings"), L8/L10 (historical index), all on "record" in the structural sense. **No ruling row was read** |
| Finding | the register's schema has **no exception field**; exceptions (as in R-39) can only appear inside free-text Ruling/Effect cells. No completeness convention exists → an unmentioned exception is NOT-RECORDED, not ABSENT, for every row |
| Effect on P1 / models | P1's exception stays NOT-RECORDED; the M0 conditional (§5) stays conditional; **G-R vs G-O not discriminated**; the conjecture is unchanged, not strengthened |
| Structural consequence (MODEL-DERIVED) | positive absence of an exception is **unobservable in this register** by schema. Discriminating observations must therefore be **positively recorded**: a *refusal/deferral with a stated reason* (as L493-B) is observable where an absence is not. A bar-citing refusal on the RULING route, or on a CREATE-NORM operation, would be REASON-INCONSISTENT with G-R or G-O respectively |
| Next (highest information) | the already-located 2026-08-23 session-log section "Non-actions … from a single occurrence" (a refusal citing the single-occurrence bar): bounded read by heading, spec frozen first; code route and operation of the refused act |

## F-LOG-0119 — L0-REL-28 stopped at locate: the released pointer is **AMBIGUOUS** (two matches), and both matched lines are **blanket self-declared non-actions**, not a refusal of a specific act → not discriminating by locate; no section read; STOP for L0

| Field | Value |
|---|---|
| L0 act | "L0-REL-28: yes" (+ reviewer protocol: controlled discrimination experiment) |
| Source | `.claude/sessions/2026-08-23.md` `8b4db5c5…`, 690 lines |
| Locate | "Non-actions" is a heading 6× (L48, 413, 464, 565, 612, 680) and an inline label 7×. "single occurrence" matches L410 (in "Open items", not a non-action), **L515** (inline "Non-actions:" line inside "Next Steps" L511–L520) and **L682** (inside "## Non-actions" L680–L683). Two locations satisfy the pointer → the reader may not choose (L0-REL-01 precedent). No case spec frozen; no section read |
| Matched lines (locate-level SOURCE-FACT) | L515 "… no promotion from single occurrences (`ES-006.1`) …"; L682 "… no promotion from a single occurrence (`ES-006.1`)." Both are items in a session's list of its own non-actions, beside "no adoption", "no authorization", "no new … vocabulary (`ES-005.4`)" |
| Assessment | neither line refuses a **specific** object: no object identity, route or operation, and the actor is the session itself, not an authority. Per the protocol ("if route and operation are not both source-grounded, the case does NOT discriminate G-R vs G-O"), a bounded read of either section is unlikely to supply them → **expected discrimination ≈ 0**. Incidental (locate-level): the bar is again attributed to ES-006.1 (twice, 08-23), extending the citation-drift genealogy; promotion / adoption / authorization listed as separate non-actions (non-collapse family) |
| Needs L0 | designate L515 or L680 for a bounded read, **or** (recommended) close L0-REL-28 as NON-DISCRIMINATING and proceed to 1n (a register RULING that raises standing with a stated basis) |

## F-LOG-0120 — L0-REL-28 CLOSED (non-discriminating); 1n locate-only over the rulings register rows: 40 rows match term classes; deterministic ranking → **R-88 (L74)** top candidate; STOP for L0

| Field | Value |
|---|---|
| L0 act | "close L0-REL-28; 1n: go" |
| L0-REL-28 | CLOSED as NON-DISCRIMINATING (F-LOG-0119): both pointer matches are blanket self-declared non-actions |
| Locate method | register `7795c14b…` rows L16–L86; per row only **ID, date and which term classes matched** were printed (no row text). Classes: standing-raise {principle, standard, methodology, promot, adopt, canonical, ratif}; basis {evidence, instance, repeated, occurrence, single, two, second, context, pattern}; the word "exception" |
| Result | 40 rows match both classes ("evidence"/"adopt" are generic). Ranking rule (deterministic): standing-target term {principle, methodology, standard} present, then number of count-type basis terms {instance, occurrence, single, second, two}; exclude already-used rows (R-39 development, R-41 = P1, R-90 withdrawn/retired) |
| Ranked | **R-88 L74** (2026-08-04; principle; instance, occurrence, second, single) · R-89 L75 (principle; occurrence, second, single, two; **identity risk: the R-89 number collision**, F-LOG-0113) · R-64 L50 (2026-08-01; methodology; second, single) · R-42 L28 / R-62 L48 (principle / methodology; "two") |
| Route | RULING for every row is SOURCE-DERIVED from the host (the register records rulings); **operation (RAISE vs CREATE-NORM) must be grounded in the row text**, never assumed from the term "principle" |
| Next | L0-REL-29: freeze a case spec (all fields UNKNOWN except route = RULING, SOURCE-DERIVED), then read **one row, R-88 L74**; reason-level test of G-R vs G-O under frozen M0 |

## F-LOG-0121 — L0-REL-29 executed (register L74, R-88): **OUT-OF-SCOPE** for the guard models (a work-package SUBDIVISION, not RAISE / CREATE-NORM) → G-R vs G-O not discriminated; but the row states **frame conditions explicitly** ("WHAT IT DELIBERATELY DOES NOT CHANGE"); STOP for L0

| Field | Value |
|---|---|
| L0 act | "L0-REL-29: yes" |
| Freeze | `a5bbdf83c`: `R88-CASE/CASE-SPEC.json` before the read |
| Read | `7795c14b…` L74 only |
| Coding | route RULING (ARB CHIEF prepared → Decision Authority adopted, single act) · operation **SUBDIVIDE** + status annotation · kind governed work · stated reason **ownership** ("a work package must have one primary bounded-context owner"; precedent R-68; "No new principle is introduced") · count words were **false positives** ("single act", "second consumer") · exception NOT-RECORDED. Record `R88-CASE/OBSERVATION.json` |
| Model result | OUT-OF-SCOPE for all 8 guard models; nothing falsified or reason-inconsistent; the G-R conjecture unchanged |
| Structural (SOURCE, explicit) | **frame statements in the corpus itself:** adoption changes status only ("the DECISION TEXT above is unchanged"; the provenance note kept as historical record) · subdivision ≠ authorization (R-80) · adoption creates no delegation (R-86/R-87 position "REMAINS INTACT") · recording ownership ≠ resolving the open question · a "DOES NOT CHANGE" list of preserved coordinates · **explicit anti-path-dependence clause**: a future ARB-Chief ruling is "again PREPARED, NOT ADOPTED" · an ambiguous message is not taken as adoption |
| Consequences | (1) frames are **positively observable** in register rows (unlike exceptions) → P-frame-fit can now be tested on real rulings; (2) M0 lacks a ruling-status coordinate {PREPARED, ADOPTED}, a delegation coordinate, and a work-structure operation — **M1 candidates, M0 unchanged**; (3) lexical count-term retrieval: 1/1 inspected hit was a false positive → first labelled negative for the retrieval benchmark (a precision observation, not a recall gap) |
| Next | two options: (a) **frame test** — locate-only for rows with "does not change" / "is not" clauses and check them against M0/M1 frames; (b) G-R vs G-O — R-64 (L50), only after a count-context check (term precision is low) |

## F-LOG-0122 — 1q frame experiment (5 register rows, spec frozen `e772eb53d`): **5/5 exhibit frame structure with stated guards; no M0 frame contradiction**; M0 under-specified (vocabulary + scope/target indexing); decision-text immutability in 4 rows; M1 justified as a pre-registered hypothesis only; STOP

| Field | Value |
|---|---|
| L0 act | "1q: GO" (reviewer protocol: small diverse sample, frame experiment, M0 unchanged) |
| Locate | 51 register rows carry ≥ 1 frame-phrase class; the "WHAT IT CHANGES / DOES NOT CHANGE" template occurs in every row R-81..R-91 and none earlier (locate-level) |
| Sample (frozen rule) | R-47 · R-53 · R-66 (pre-template) · R-81 · R-91 (template); used rows excluded |
| Read | `7795c14b…` L33, L39, L52, L67, L77 only |
| Results (MODEL-DERIVED; sample selected on frame phrases) | frame structure 5/5 (+R-88) · guards stated explicitly in every row · M0 fit: R-53 PARTIAL-FIT (counter-evidence recorded, **standing preserved** = M0 CONTRA frame), R-66 PARTIAL-FIT, R-81 PARTIAL-FIT (freeze lifted for one scope; M0 regime global), R-47 / R-91 NOT-MODELLED · **no FRAME-CONTRADICTION** |
| Recurrent source facts | decision text immutable, annotation-only change: R-53, R-81, R-91 (+R-88) · authorization scope-bounded: R-47, R-66, R-81, R-91 (+R-88) · counter-evidence does not by itself change standing: R-53, R-91 · HELD ≠ WITHDRAWN (R-91 vs R-90) |
| Computation | `frame_fit.py` `3534445e…` (selftest 4/4 with mutation); ruling status: "adopted?" alone non-Markov, 5-valued {unused, prepared, adopted, held, withdrawn} Markov → second finite-summary witness |
| Retrieval | frame-phrase lexical precision 19/20 vs count-term 0/1 → no ML needed for frame retrieval; recall unmeasured |
| Verdict | M0 **not contradicted, under-specified**; guard theory unchanged; M1 (status + registry, scope-indexed authorization relation and freeze, target-indexed counter-evidence, two-layer record invariant, 7 operations) justified **as a pre-registered hypothesis** |
| Next | pre-register M1 with predictions P1–P5, test on unread template rows R-82..R-87, R-89 |
| Record | `analysis/theory/FRAME-1Q/{SPEC,RESULT}.json`, `REPORT.md` |

## F-LOG-0123 — 1s: modular M1 pre-registered (`27a09b5fd`) and tested on 7 prospective template-era hold-out rows: P1, P2, P3a, P3b, P5 survive; **P4 falsifies Frame⁺(ADOPT) = {status}** (one act, R-86, seen in 5 rows); 2 operations missing; STOP

| Field | Value |
|---|---|
| L0 act | "1s: GO" (reviewer protocol: modular M1, hold-out ≠ independent set, P3 split, Frame⁺/Frame⁻, anti-overfitting) |
| Freeze | `prompts/KNOWLEDGEOS-M1-MODULAR-PREREGISTRATION.md` `4544310b…` before any hold-out read |
| Read | `7795c14b…` L68–L73, L75 (R-82…R-87, R-89), once each |
| Checker | `m1_check.py` `345dd506…` (selftest 6/6; the first run's failing test assertion was wrong, not the checker, and only the test was corrected); `M1-HOLDOUT/RESULT.json` `960e22b2…` |
| Ledger | P1 7/7 · P2 5/5 (+2 untestable) · P3a 6/6 · P3b 7/7 · P5 7/7 · **P4 1 supported / 5 violated / 1 not-modelled**. Effective evidence units are smaller (R-82…R-85 share one pasted annotation) |
| Falsified | **Frame⁺(ADOPT) = {status}**: adoption also turns the provenance annotation into HISTORICAL RECORD / "condition discharged" (R-86, recorded in R-82…R-85). One cause. M2 candidate: {status, annotation-role} (not adopted) |
| Not modelled | ALLOCATE (R-84), PERMIT-CONSIDERATION (R-87, "consideration is not authorization") |
| New source facts | operation-specific frames stated by the source ("advances by acceptance — never by authorization"; "only by SUPERSEDING, not SCOPING") · catalog "version, never mutate" · intent-vs-mechanism target split · open world in the corpus (ENG-012) · reopening needs new evidence (2nd, after L216) · "not implicitly and not by adjacency" · path-independence of authority stated 3 more times · new authority name "ACCEPTING AUTHORITY" |
| Scope of claims | one template regime, one day, largely one issuer and one adoption act; no IID claim |
| Next | generalization test on the pre-template, human-authority regime (R-42…R-80): 5 rows by a frozen diversity rule; P1, P2, P4 (M2 ADOPT frame), P5, P3′ (no PREPARED phase) |
| Record | `analysis/theory/M1-HOLDOUT/REPORT.md` |

## F-LOG-0124 — 1t: M2 delta pre-registered (`7339f3f01`) and tested on 5 pre-template, human-authority rows: record invariant, frame structure (5/5, selected on category), no-PREPARED-phase (5/5) and evidence persistence survive; **Frame⁺(ACCEPT) = {acceptance} falsified** (acceptance closes work); STOP

| Field | Value |
|---|---|
| L0 act | "1t: go" |
| Sample rule | earliest row of each operation category absent from 1s (Approval, Ratification, Directive, Adjudication of a characterization) + the qualified-authority row; used rows excluded → R-43, R-44, R-72, R-77, R-79 |
| Read | `7795c14b…` L29, L30, L58, L63, L65, once each |
| Checker | `m2_check.py` `609d63a3…` (imports m1 read-only; only the frozen deltas applied; selftest 6/6); `M2-GENERAL/RESULT.json` `524fcbea…` |
| Ledger | P1 5/5 · P1-R43 cross-row 1/1 · P2 1/1 (+4 untestable) · **P3′ 5/5** · **P4 2 supported / 1 violated / 2 not modelled** · P5 4/4 · **P6 5/5** |
| Falsified | **Frame⁺(ACCEPT)**: R-62 (annotation in R-43) "completed work is accepted and closed … one transition"; corroborated by R-72 ("closure is an ACCEPTANCE OUTCOME"), R-87, R-66 → the ACCEPT / CLOSE-WORK split is refuted; M3 candidate {acceptance, work-lifecycle} |
| Refinement (not applied retroactively) | a derived consequence ("WP-7 entry conditions satisfied") is guard re-evaluation, not a direct effect → M3 rule: the frame axiom covers direct effects only |
| Reading-dependent | R-77 rejection: if a rejected proposal carries a status, Frame⁺(REJECT) = ∅ also fails |
| Cross-regime | record invariant, frame structure and evidence persistence survive both regimes; the status model is regime-parametric (issuer); specific frame tables are being learned, not confirmed (ADOPT, ACCEPT corrected) |
| New source facts | permission ≠ authorization ≠ commissioning ≠ execution (R-79) · conditional authorization with an unmet proviso (R-72) · evidence guard on AUTHORIZE ("from IMPLEMENTATION EVIDENCE rather than roadmap intent") · "normative on paper ≠ governs implementation" (R-77) · supersession only by explicit act, never inference · clause-granular adoption · part-acceptance ≠ whole · third target pair (invariant vs mechanism) · prediction ≠ executed result |
| Next | stop sampling: pre-register M3 (frame corrections, direct vs derived, conditional authorization, PERMIT(activity), four-act separation, issuer-parametric status) → explicit-state attack with shortest counterexamples → one targeted read from a different source family (breaking the single-register dependence) |
| Record | `analysis/theory/M2-GENERAL/REPORT.md` |

## F-LOG-0125 — 1u: M3 pre-registered (`3e1e32f65`) and attacked: 12 invariants jointly satisfiable, no frame violations; **R-79's affirmed delivery sequence is derived from local guards**; relaxation counterexamples give 12 observation templates; next read = I-B4 via git history of the register; STOP

| Field | Value |
|---|---|
| L0 act | "1u:go" |
| Instrument | pre-registration `1ea8669e…`; `m3_check.py` `d212d24f…` (BFS over three sub-models: work acts 84 states, rulings 250, evidence 9; selftest 4/4 incl. frame mutation); `M3-ATTACK/RESULT.json` `6696388c…` |
| Results (MODEL-DERIVED; development basis) | all 12 invariants hold (by construction) and are **jointly satisfiable** (START(8), ACCEPT(§4) and a permission-without-authorization state all reachable) · **every START(8) state has board acts + 4B, 4C, 4D, §4 accepted: R-79's "SEQUENCE AFFIRMED" follows from the local guards** · every relaxation yields a shortest counterexample → 12 observation templates |
| Observability ranking | rank 1 (mechanical, cross-source): I-A1, I-A2 (git × register), **I-B4** (git history of the register), I-B5 |
| Open | composition of the sub-models; the evidence-bar question (widened by R-72's AUTHORIZE evidence guard); unbounded history (none needed so far) |
| Next | L0-REL-30: I-B4 test on the register's git history (per-row column-3/column-4 change classification; counts and IDs only, no text; method frozen first). Then I-A1 on WP-4B |
| Record | `analysis/theory/M3-ATTACK/REPORT.md` |

## F-LOG-0126 — L0-REL-30: I-B4 tested on the register's git history (census: 71 rows × 54 commits): **64/71 decision cells unchanged; none changed after the day of first record; strict I-B4 FALSIFIED by in-place same-day edits (R-96, R-98, R-99)**; instrument defect r1 disclosed; STOP

| Field | Value |
|---|---|
| L0 act | "L0-REL-30: yes" |
| Freeze | `f338d94ee` (spec + script, before the first run) |
| Defect | r1 read 1/54 commits (git ignores `--follow` with `--reverse`); fixed in `bcbeca945` (listing only, method unchanged); r1 output retained |
| Result | `REGISTER-HISTORY/RESULT.json` `c22ecf9b…`: column 3 — 64 UNCHANGED, 3 APPEND-ONLY, 1 INSERT-ONLY, 2 MODIFIED, 1 REPLACED; column 4 — 49 UNCHANGED, 17 APPEND-ONLY, 2 INSERT-ONLY, 3 REPLACED; no deletions, no duplicate IDs |
| Post-hoc (labelled) | all 7 candidate changes on the **same day** as first record → **0/71 decision cells changed on a later day** · R-37 / R-38 / R-100 additions carry register annotation markers (the frozen regex missed them) · **R-96, R-98, R-99: deletions/replacements in the decision cell on 2026-08-04** · R-51 a 12-char unmarked insertion |
| Verdict | **strict I-B4 falsified**; census support for "no decision-text change after the first day; later change annotation-only". Competing refinements: H-status (amendable while PREPARED; source-motivated by the R-81…R-85 annotation "a correction amends them before engineering builds on them") · H-window (drafting window) · H-strict-with-exceptions |
| Significance | first **census** (not sample) result in the programme |
| Next | L0-REL-31: bounded read of the decision-cell diff hunks for R-96, R-98, R-99 (and optionally R-51) + the edit commits' subjects, with predictions frozen first (H-status: each edit occurred while PREPARED / pre-adoption) |
| Record | `analysis/theory/REGISTER-HISTORY/REPORT.md`, `POSTHOC.json` |

## F-LOG-0127 — L0-REL-31: the same-day edits discriminated (spec frozen `75ce9526c`): **H-status (PREPARED-only) FALSIFIED; H-window SURVIVES; H-strict-with-exceptions not supported**; I-B4′ proposed; STOP

| Field | Value |
|---|---|
| L0 act | "L0-REL-31: yes" |
| Read | column-3 diff hunks + edit-commit subjects for R-96, R-98, R-99, R-51 only (`REGISTER-HISTORY/REL31-EXTRACT.txt`) |
| Coding | 3 LABELLED-CORRECTIONs in place (R-96; R-98 after 1 citation; R-99) · 2 annotation inserts · **1 SUBSTANTIVE unlabelled status rewrite (R-99: "HELD FOR A REFERENT" → "CLOSED … takes effect UNAMENDED"), 0 citations, pre-edit HELD** · 1 STRUCTURAL (R-51) |
| Results | H-status falsified (the substantive edit was on a HELD row; H-status′ "non-governing" consistent, post-hoc) · **H-window survives**: the source itself licenses in-place correction "SAME DAY, BEFORE ANY ENTRY WAS CREATED" (R-96) · no unlabelled substantive edit on a cited ruling |
| New | a second record practice for status: an in-place headline rewrite (R-99) beside annotation-based status changes (R-81…R-91) · the PREPARED mechanism possibly act-type-specific (Chief-level acts R-96…R-99 carry no PREPARED marker) |
| Instrument gaps (disclosed) | no HELD class in the status regex; no ANNOTATION class in the kind list; handled conservatively |
| Candidate | **I-B4′**: decision text immutable after the drafting window; in-window edits labelled, annotations, or a status rewrite of an uncited, non-governing ruling. Falsifier: any later-day change, or an unlabelled substantive edit of a cited or governing ruling |
| Next | 1w (I-A1 on WP-4B: implementation commit dates × proviso satisfaction), or consolidate M4 (I-B4′, act-type-parametric status) |

## F-LOG-0128 — 1w: I-A1 on WP-4B (spec frozen `a6533817d`): P-a, P-b SUPPORTED (first RED 42 min after R-77); P-c VIOLATED under the frozen rule but an operationalization artefact (subjects say RED / batch 6); **decisive open question: RED began 16:10, 17 min after R-80, while R-79 put two Board acts first and R-86/87 say they remained OPEN**; STOP

| Field | Value |
|---|---|
| L0 act | "1w:go" |
| Instrument | `ia1_check.py` `00cb88f6…`; `IA1-WP4B/RESULT.json` `abfbbc8d…`; `LOCATE-R73-R80.json` (term presence only) |
| Bounds | T_R72 08-02 15:02 · T_R77 08-03 15:28 · T_R86 08-04 00:29 · 8 implementation commits, 08-03 16:10 → 08-04 20:47 |
| Results | P-a SUPPORTED · P-b SUPPORTED · P-c VIOLATED (frozen keyword rule) — post-hoc: both commits are labelled "RED" / "batch 6", no subject names batch 7 → precision + recall problem of the operationalization, P-c unresolved in substance |
| Decisive | R-79 (15:47) "(1) the two remaining Board acts — PROMOTION … ALLOCATION · (2) WP-4B RED"; R-86/R-87 "PROMOTION and ALLOCATION remain OPEN … not on batch 7's execution"; RED 16:10; R-80 (Directive, 15:53) contains PROMOTION + ALLOCATION + proviso terms → H-resequenced (R-80 licenses RED; I-A1 holds) vs H-deviation (I-A1 violated; M3 work sub-model falsified for WP-4B) |
| Next | L0-REL-32: bounded read of R-80 (+R-78), predictions frozen first |
| Record | `analysis/theory/IA1-WP4B/REPORT.md` |

## F-LOG-0129 — L0-REL-32 (R-78, R-80 read; spec `7fc394b27`): SUPPORT(H-deviation), **I-A1 UNDETERMINED**, **M3 work-gate falsified (sequence ≠ guard)**; T-min (`fa1ccea9e`) minimality: authority, kind, prior state NECESSARY; route, operation, evidence, exception UNDETERMINED; **variable set insufficient** (R-86 vs R-91); Markov: finite summaries suffice; STOP

| Field | Value |
|---|---|
| L0 act | "L0-REL-32: yes" + reviewer instruction (resolve R-80, then consolidate a minimal theory, no M-accretion) |
| R-80 / R-78 facts | R-80: the four-term execution-governance vocabulary is closed; new terms need "DEMONSTRATED, not ANTICIPATED" ambiguity; "claims no universality" · R-78: architecture → delivery; "decides no transport, no ownership, no allocation"; "reopening requires MATERIALLY NEW EVIDENCE" (third occurrence of the reopening bar) |
| Gate result | neither row licenses RED before PROMOTION/ALLOCATION (frozen: SUPPORT H-deviation); the source does not establish that WP-4B authorization was absent (R-72's proviso vs R-79's ambiguous "gate"; R-86 relocates the acts to §WP-4 closure) → I-A1 UNDETERMINED; M3's choice of the Board acts as the WP-4B guard falsified by practice + R-87's acceptance |
| T-min | `tmin_check.py` `ac1cb86d…` (selftest 3/3); `TMIN/RESULT.json` `e4e81d49…`; 21 coded events |
| Minimality | a NECESSARY (Chief vs Authority adoption) · k NECESSARY (register admission) · σ NECESSARY (START WP-4B before/after proviso; WP-8) · o, r, ε, x UNDETERMINED (no single-variable pair) |
| Sufficiency | R-86 vs R-91 identical on all coded variables, opposite outcomes → missing variable: **procedural conformance / role separation of the act's content** (R-91 "collapsed" evidence submission and review) |
| Markov | 3 pairs; 2 need a finite fix (status + registry; authorization + proviso state), 1 path-independent; no unbounded history needed |
| Next | locate-only: a performed supersession by the same authority/kind as the refused ones (R-77, R-83) → makes ε NECESSARY or keeps it UNDETERMINED; then a route-only pair |
| Record | `analysis/theory/TMIN/REPORT.md` |

## F-LOG-0130 — 1y: evidence-pair search (locate `d0ee57bab`, pair spec `eac99ae8e`): only R-94 and R-33 contain a positive supersession term; **R-94 declines supersession ("would blur chronology") and adopts ANNOTATE → NO ε-pair; ε UNDETERMINED**; rerun gives an operation-only pair that is CHOICE-grounded, not legality → **legality ≠ choice** refinement; STOP

| Field | Value |
|---|---|
| L0 act | "1y:go" |
| Locate | across all register rows except R-77/R-83, positive supersession terms occur only in R-94 (ARB CHIEF, Disposition) and R-33 (no triple): explicit supersession is almost absent |
| R-94 (read, L80) | remedy choice retain / annotate / supersede → **ANNOTATE adopted**: "superseding would blur chronology … Annotation preserves both truths with minimal governance change"; the row keeps ✅ and 100%; nothing reopened |
| Result | NO-PAIR (same refusal outcome as R-83; different kind) → **ε UNDETERMINED**; supersession declined at ε = 0 (R-83), insufficient (R-77) and **present** (R-94, on a record principle) → evidence not sufficient for supersession |
| T-min rerun | `tmin_check.py` `7da8cf57…` (logic unchanged; +2 events); `EPS-PAIR/TMIN-RERUN.json` `dd102a6a…`: o NECESSARY via SUPERSEDE-declined vs ANNOTATE-performed (R-94), **but choice-grounded**; a, k, σ pairs are rule-grounded |
| Refinement | decision = **legality** (guards) + **choice** among legal options by stated principles (minimal change; preserve chronology; reopening needs materially new evidence); coding must mark refusals RULE-GROUNDED vs CHOICE-GROUNDED |
| Next | locate-only over ADR **Status** lines ("Superseded by …") — a structured source family — for a performed supersession to complete an ε-pair; optional bounded read of R-33 |
| Record | `analysis/theory/EPS-PAIR/REPORT.md` |

## F-LOG-0131 — 1y′ locate-only over ADR Status lines (spec `ff6dadaa9`): 21 ADR files; **1 performed supersession**: ADR-MP "supersedes the bundled Decision-Log entry D-12 (now a pointer)"; STOP before any bounded read

| Field | Value |
|---|---|
| L0 act | "1y:go", read as the go for 1y′ (1y was complete; the interpretation is stated to L0) |
| Run note | first run failed on filenames with spaces (whitespace split); the listing was re-run NUL-separated; spec unchanged |
| Result | `EPS-PAIR/ADR-LOCATE.json`: 21 tracked ADR files (register excluded) · 11 with another status · **1 SUPERSEDED** · 9 without a status line in the first 40 lines |
| The one case (locate-level SOURCE) | `docs/adr/ADR-MP-Messaging-Platform.md`: "**Status:** Accepted · **2026-07-07** · supersedes the bundled Decision-Log entry D-12 (now a pointer)" |
| Immediate observation | the superseded entry is **kept as a pointer**, not deleted: the record invariant (old text kept, forward pointer) in a **second source family** (ADR files, not the register) |
| Pair prospects (MODEL-DERIVED, before reading) | for an ε-pair with R-83 (ARB-CHIEF, design-rule, ε = 0) or R-77 (ARB, ADR, insufficient) the superseding act needs the same authority and kind; authority UNKNOWN, kind = a Decision-Log entry → **low prior probability of a minimal pair**; the read would still give the first **performed** supersession's recorded basis (evidence present or not) |
| Needs L0 | a bounded read of ADR-MP's status and context section (pair criteria frozen first), or stop the ε search here (supersession is rare: 1 performed in 21 ADRs + 0 in the register) |

## F-LOG-0132 — 1y″ (a + b): ADR-MP read (spec `a98eaa9ad`): **a performed, rule-grounded supersession (split), superseded text kept as a pointer** → NO ε-pair; legality/choice recoding (`c4799ba0f`): **legality needs a, k, σ (+ the missing procedural-conformance variable); route, operation, evidence, exception UNDETERMINED; operation NECESSARY only for choice**; STOP

| Field | Value |
|---|---|
| L0 act | "1y″: a or 1y″: b" → both executed in order (clear scopes, stated to L0) |
| (a) read | `docs/adr/ADR-MP-Messaging-Platform.md` `8a63c7a5…` L1–L9, L80–L81 only |
| (a) result | performed supersession (split: "D-12 was too large"); ground "one architectural question per ADR" (RULE); a NR, ε NR → NO-PAIR, ε UNDETERMINED; **record invariant supported in a second source family** (D-12 "retained as a pointer"; authority moves to the new records → candidate coordinate *authoritative location*); across 4 supersession cases evidence is never the stated ground of a performed one |
| (b) | `tmin2_check.py` `ca5f2687…` (tmin_check imported read-only); `TMIN/RESULT-LEGALITY-CHOICE.json` `f5493cd2…`; 24 events; 9 RULE / 2 CHOICE refusals |
| (b) result | LEGALITY: a, k, σ NECESSARY; o, r, ε, x UNDETERMINED; the sufficiency counterexample (R-86 vs R-91) persists → procedural conformance missing · CHOICE: o NECESSARY (R-94) · evidence-bar refusals all concern standing-changing operations |
| Assessment | this corpus rarely varies a single factor; further minimal-pair searches have diminishing returns |
| Next (L0) | (i) one locate-only search for a route-only pair (G-R vs G-O), or (ii) consolidate T-min v1: Legal = f(a, k, σ, procedural conformance), frames, finite history, two-layer decision; then the event-level dataset (1z) and independent verification (Gate 1) |

## F-LOG-0133 — 1aa(i) route-only pair locate (spec `03df0f60c`): **no candidate**. Register: only R-39 (already coded; differs from L493-B in authority, exception and kind); session logs: 20 lexical hits, 2 name an authority in the line, neither is a raise decision (locate level). **Structural finding: route is confounded with host family** → G-R vs G-O is not decidable from this corpus region; STOP

| Field | Value |
|---|---|
| L0 act | "1aa:i" |
| Output | `analysis/theory/ROUTE-PAIR/LOCATE.json` (register: IDs, triples and cue classes; logs: file:line + matched line ≤ 160 chars) |
| Register | 1 row with a one-occurrence cue and a raise cue: R-39 (RAISE · RULING · DA · principle · exception recorded) — not a route-only partner of L493-B (PROMOTION · PO/ARB · observation · no exception) |
| Session logs | 20 matching lines; 2 with an authority in the line (07-26:164 on ADR validity until supersession; 08-21:322 on a dangling reference — the latter was not pursued, as it belongs to another track's material); 18 without an authority |
| Structural finding (MODEL-DERIVED) | the register is RULING-route by construction; PROMOTION-route dispositions appear in session logs (a non-normative host), as refusals or self-restraints. **Route ≡ host family in this corpus**, so route cannot be varied alone; the route-only minimal pair needed to separate G-R from G-O does not exist here |
| Consequence | G-R vs G-O stays UNDETERMINED — not for lack of search effort but by the corpus's design; settling it needs either a source where one authority uses both routes, or a **prospective, designed observation** (a future promotion decision recorded with route, authority, kind, evidence and grounds) |
| Next | 1aa(ii): consolidate T-min v1 (legality = f(authority, kind, prior state, procedural conformance); frames; finite history; two-layer decision; record = drafting window + annotation / pointer), re-run the checker, then the event-level dataset (1z) and Gate 1 |

## F-LOG-0134 — 1aa(ii): T-min v1 consolidated (frozen `b2f1d5d76`) and checked: **23/24 coded outcomes reproduced (G-R, G-K), 22 + 1 open (G-O, P1)**; the one unexplained event is an internal coding conflict (AUTHORIZE(4C)@R-72: RULE vs CHOICE), disclosed and not fixed silently; event-level dataset exported (24 events, copies collapsed); STOP

| Field | Value |
|---|---|
| L0 act | "1aa:ii" |
| Theory | `analysis/theory/T-MIN-V1-CANDIDATE-THEORY.md` `a7e04491…`: ontology, legality guards (authority, kind, prior state, fitted conformance, evidence bar with undetermined activation, four-act separation, sequence ≠ guard), choice principles, record (I-B4′, pointer supersession), undetermined items, 4 falsifiers; every claim carries an evidence class S / R / D / F / U |
| Check | `tminv1_check.py` `7419653f…` (selftest 3/3); `TMIN-V1/RESULT.json` `df948b41…` |
| Result | G-R 23/1 · G-K 23/1 · G-O 22/1 open (P1)/1 · G-E undetermined; Markov: all 3 pairs handled by the finite summary |
| Unexplained | AUTHORIZE(4C)@R-72: v1 guard ("after its predecessor is accepted") vs CHOICE coding (F-LOG-0132); the act states and applies its own rule → general coding question: rule-creating-and-applying acts; resolution needs an L0 coding decision |
| Dataset | 24 events (the R-81…R-85 annotation = one event); families: register 15, session-log 4, git 2, register rule 1, standards 1, ADR 1; statistics NOT READY (selected, clustered, unbalanced) |
| Next | out-of-sample: code the ~40 uncoded register rows blind and test v1's predictions (the first genuine validation) · Gate 1 on v1 · a designed observation for route vs operation |
| Record | `analysis/theory/TMIN-V1/REPORT.md` |

## F-LOG-0135 — 1ab: T-min v1 out-of-sample test (pre-registered with a seeded sample, `d35011d2e`): 15 of 41 never-coded rows (first random sample) → **no guard contradicted; V6 authority-naming falsified in 2/15 (early regime); vocabulary coverage 29% [15–49%]; evidence bar reaches beyond RAISE/SUPERSEDE (3rd occurrence)**; STOP

| Field | Value |
|---|---|
| L0 act | "1lab:go" read as "1ab: go"; scope reduced from ~40 to a seeded 15 (stated) |
| Sample | R-30, R-32, R-34, R-35, R-40, R-45, R-52, R-56, R-62, R-63, R-64, R-65, R-68, R-75, R-97 (sha256 seed "F-1ab-2026-09-28") |
| Results (Wilson 95%) | V1 5/5 [0.57, 1] (+1 ambiguous R-64) · V2 4/4 [0.51, 1] (+1 ambiguous R-40) · V3 5/5 · V4 6/6 [0.61, 1] · V5 1/1 · **V6 13/15 = 0.87 [0.62, 0.96]: R-30, R-32 violated** · V7 2/2 · **coverage 7/24 = 0.29 [0.15, 0.49]** |
| Main findings | v1 guards not contradicted (weak n) · authority-naming holds only from the triple convention on (a regime effect) · **v1 vocabulary fails to generalize**; top missing ADOPT-DECISION (an overload of "adopt"), APPROVE, DEFER · evidence bars on norm-creation / authorization (R-64; with R-72, R-80) → v1's bar scope likely too narrow |
| New source facts | opened ≠ delivered · plan approval ≠ execution authorization · authorization ≠ release · no partial acceptance under a package's own name · "none are invented here" (NOT-RECORDED ≠ FALSE in the corpus) · "a scope boundary, not a model defect" (R-63) · Chief confirmation without a PREPARED marker (2nd witness of act-type-specific status) |
| Next | freeze the extended vocabulary + operation-specific evidence bars (v1.1) → held-out test on the next seeded sample of the remaining 26 rows (coverage threshold pre-set) · Gate 1 as inter-coder agreement (κ) on these 15 rows |
| Record | `analysis/theory/OOS-1AB/{SAMPLE,CODING,RESULT,INTERVALS}.json`, `REPORT.md` |

## F-LOG-0136 — 1ae: T-min v1.1 held-out test (pre-registered with a seeded held-out sample, `2dfe9eea2`): **P-COV SUPPORTED, barely: 17/24 = 0.71 [0.51, 0.85] vs v1 0.46**; no guard or frame violated (V1′ 12/12, V3′ 12/12, V6′ 10/10); ACCEPT-closes confirmed on unseen rows; **first evidence-only minimal pair (R-36) → evidence NECESSARY**; STOP

| Field | Value |
|---|---|
| L0 act | "1ae:go" |
| Held-out | R-31, R-36, R-38, R-46, R-49, R-50, R-55, R-58, R-61, R-67, R-73, R-92, R-93 (seed order 16–28); reserve of 13 untouched |
| Coverage | v1.1 17/24 = 0.708 [0.508, 0.851] ≥ 0.70 → SUPPORTED (the lower bound is below the threshold); v1 11/24 = 0.458; the new operations ADOPT-DECISION (×3), APPROVE (×2) and DEFER recurred; gaps: queue-item creation (×3), "NOT promoted", RENAME, DEPRECATE, ASSESS |
| Predictions | V1′ 12/12 · V2′ 4/4 · V3′ 12/12 · V4′ 6/6 · V6′ (R-43+) 10/10 · V5′, V7′ untestable · no violations |
| Evidence | R-36: promoted "evidence-based (PB-004·005·006)" vs "NOT promoted … (1 slice)" → secondary T-min rerun: **ε NECESSARY** (caveat: partly a scope reason); route vs operation still undetermined |
| Source facts | 4th evidence bar on concept creation (R-38) · the operation determines the authority (R-46) · "an approval admits a proposal while an acceptance admits delivered work" (R-55) · outcomes pre-registered inside the corpus (R-50) · explicit guard verification (R-58) · evidence collection separate from repair (R-49) · Chief review-adoption without a PREPARED marker (3rd witness) · R-93 partial acceptance borderline vs R-68 |
| Next (L0) | reserve test (v1.1 frozen; v1.2 variant scored separately) · Gate 1 κ on 28 coded rows · or stop exposure and write up the candidate theory |
| Record | `analysis/theory/HELDOUT-1AE/{SAMPLE,RESULT}.json`, `REPORT.md` |

## F-LOG-0137 — Gate 1 (SECONDARY same-family) blind recoding of 28 rows: operations reproducible (Jaccard 0.93, κ 0.65–1.0), authority κ 1.0, V2 κ 0.79; V1/V3/V4/V5/V7 κ 0.19–0.47 with one-directional disagreement; **0 violations by both coders**; **main-coder bias (cross-row knowledge) found; coverage coder-dependent (0.89 vs 0.75)**; STOP before the reserve

| Field | Value |
|---|---|
| Design | main coding sealed `080dc6d7b`; bundle manifest `ac1ad57b8`; headless Claude, Read/Write only, in a non-git scratch directory; transcript audit clean (3 reads, 2 writes, 0 outside paths) |
| Results | operations Jaccard 0.933, per-op κ 0.65–1.00 · authority κ 1.00 · V6 κ 1.00 · V2 κ 0.79 · V1 0.19 · V3 0.23 · V4 0.28 · V5 0.30 · V7 0.47 (raw 0.68–0.93; a prevalence effect) · coverage main 0.89 vs blind 0.75 · VIOLATED: 0 vs 0 |
| Disagreement sources | (1) main-coder bias: cross-row knowledge (R-58, R-65) → SUPPORTED counts in F-LOG-0135/0136 inflated · (2) coding rules: applicability of V4/V5; act segmentation → coverage not reproducible · (3) ontology: "adopt" from a prepared package (R-73, R-75, R-92) · (4) source ambiguity (R-38, R-45, R-61, R-97) · (5) stricter use of the v1.1 new-category bar (R-34, R-40) |
| Status | SECONDARY only; Gate 1 proper (non-Claude) open; the bundle is reusable as is |
| Next | freeze coding manual r2 (record-only, segmentation, applicability; theory unchanged) → double-coded reserve test |
| Record | `analysis/theory/GATE1/` |

## F-LOG-0138 — Reserve test (13 rows; manual r2 `7be5f02e3`; main sealed `629b31215`; blind SECONDARY coder): **P-COV FAILS for both coders (0.62, 0.61)**, coverage now coder-robust; κ improved (V3 0.84, V1 0.49); **0 guard violations by both coders across 41 rows**; one coder-dependent frame case (R-76, "confirm" overload); STOP

| Field | Value |
|---|---|
| L0 act | reviewer instruction (Gate 1 → reserve → ablation), relayed by L0 |
| Coverage | main 16/26 = 0.615 [0.425, 0.776] · blind 14/23 = 0.609 [0.408, 0.778] → below 0.70; the held-out 0.708 does not replicate; coverage ≈ 0.6 and coder-robust under r2 |
| Agreement | operations Jaccard 0.846 · V6 1.00 · V3 0.84 · V7 0.63 · authority 0.63 · V1 0.49 · V2 0.38 · V4 0.35 · V5 0.00 (prevalence) |
| Violations | main 1 (R-76 V3: CONFIRM frame ∅ vs "the scope ambiguity … IS CLOSED") · blind 0 (AMBIGUOUS) → verb overload, coder-dependent |
| Across 41 rows | 0 legality-guard violations by either coder; coverage 0.29 / 0.71 / 0.61 → a vocabulary diagnostic, not a quality metric |
| New source facts | "A recording note cannot open a work package" (R-60) · no retroactive redefinition of acceptance criteria (R-59) · "Engineering does not self-certify it" (R-71; 2nd role-separation statement) · Chief planning authorization without PREPARED (R-95) vs a PREPARED implementation authorization (R-89) → HYP: PREPARED by act type |
| Record | `analysis/theory/RESERVE/` |

## F-LOG-0139 — Ablation and convergence (`ablation.py` committed `23d117878` before its run): **no nested model K0…K8 is adequate** (K8 fails the choice and conformance observations); every component is witnessed, with very unequal strength; route and exception UNDETERMINED; finite history sufficient in every case examined (bounded claim); STOP (stop condition reached)

| Field | Value |
|---|---|
| Nested | K0 13 fails · K1 13 · K2 11 · K3 9 · K4 8 · K5 7 · K6 6 · K7 4 · **K8 2** (Ch-choice, C-conformance) |
| Single-component | S, O, G, F, E, A, K, T, H, Ch, C all necessary for ≥ 1 witnessed observation; A, K, E, Ch rest on one witness each; C is fitted |
| Unwitnessed | route (≡ host family), exception → metadata, UNDETERMINED |
| Candidate theory | K\* = (S, O, G, F, E, A, K, T, H) + choice layer Ch + conformance C; S′ = δ(S, e); Δ⁺ ⊆ Frame⁺(o); Legal = G_o(S, E, A, K, T, H, C) |
| Downgrades applied | A / K / E "decide legality" → candidate variables; "finite history always enough" → sufficient in all cases examined; two-step decision → candidate architecture |
| Next | Gate 1 proper (non-Claude, the bundles unchanged) · a second witness for C, A, K, E · cross-family test · then event-level statistics |
| Record | `analysis/theory/ABLATION/REPORT.md` (the consolidated stop-condition report) |

## F-LOG-0140 — Convergence phase 2: Gate 1 proper PREPARED (tarball `08cf04b2…`, external coder needed) · non-circular ablation (`a41c9887f`): **minimal representable set {o, a, k, s, t, e, h, c}; route and exception not required**; complexity r2 (disclosed defect fix): **parsimony core {e, k, s, t} at λ = 1** · choice layer representationally unnecessary · second witnesses A +1, K +1, E +1 cluster, RS none · **cross-family: operationalization does NOT transfer (κ ≤ 0.29 except V7; coverage 0.64 vs 0.48); no guard/frame violation**; STOP

| Field | Value |
|---|---|
| Gate 1 proper | `~/F-GATE1-PROPER-BUNDLE-01.tar` sha256 `08cf04b2784d00047b9941a7b43a9f51186ad3f87f6b59c3ce4e7f4d1ff0278a`; both bundles verified against their frozen manifests; no non-Claude coder reachable here → human commissions |
| Earlier ablation | reclassified as a representational-consistency diagnostic (the requirement tags were circular) |
| Non-circular ablation | `ablation2.py` `fca3e719…` → `ABLATION2/RESULT.json` `39f8dcfa…`; removing each of o, a, k, s, t, e, h, c yields data counterexamples; r and x removable; frozen complexity measure degenerate (components) → `ablation2_r2.py` (minimum vertex cover): e 3 · a, k, s, t 2 · o, h, c 1 · r, x 0; optimum λ 0.5 all 8 · λ 1 {e, k, s, t} · λ 2 {e} |
| Choice | representable by a nondeterministic legal set; Select is explanatory only (1 witness) |
| Witnesses | A: + R-70 vs R-89 · K: + R-60 note vs ruling · E: + R-36 matrix (12 contrasts, one decision cluster; evidence counted in independent slices / contexts; per-candidate thresholds) · RS: none (4 principle statements) → RS-HYPOTHESIS |
| Cross-family | 8 seeded session-log sections (spec `f65517a90`), double-coded: V1 κ −0.19 · V3 0.00 · V4 0.16 · V6 0.29 · V7 1.00 · Jaccard 0.74 · coverage 0.64 / 0.48 · blind: 0 guard / frame violations, V6 VIOLATED 2/8 (passive-voice reports) · genre: reported acts, self-restraint, **claims** as a new object kind · main-coder V1 leniency persists |
| Next | Gate 1 proper (external) · manual r3 (genre-aware) + a new cross-family sample · a second independent decision for e and second witnesses for h, o, c (else drop from the core) · route vs operation only by a designed observation |
| Record | `analysis/theory/CONVERGENCE-2-REPORT.md`, `ABLATION2/`, `WITNESS2/SPEC-E.json`, `XFAM/` |

## F-LOG-0141 — Semantic-observation layer v1 (`0122c823f`, committed before its analysis): 31 observations / 20 independence clusters, no silent inference → **authority and kind EMPIRICALLY SUPPORTED (2 independent witnesses each); operation, state, target, evidence WEAK (1); history NOT DEMONSTRATED (possible); role conformance, route, exception NOT DEMONSTRATED**; the h / c necessity of F-LOG-0140 depended on inferred defaults; STOP-E

| Field | Value |
|---|---|
| L0 act | reviewer instruction (semantic layer + minimal-pair laboratory; Gate 1 proper first and pending externally) |
| Schema | `SEMOBS/SCHEMA.md`: semantic vs realization layers; applicability by operation; identity rule; STRICT vs POSSIBLE witnesses; independence = distinct cluster-pairs; verdicts EMPIRICALLY SUPPORTED / WEAK / NOT DEMONSTRATED (never "not necessary") |
| Result | `SEMOBS/RESULT.json` `67e5e948…` (`semobs.py` `13247dd3…`, selftest 3/3) |
| Correction | F-LOG-0140's representational necessity for h and c rested on defaults (c = ok, route / exception) → under strict coding c has no witness, h is only possible; the eight-field set is representational for a leniently coded table only |
| Status | STOP-E: the current data cannot separate the competing models on the weak variables; no new theory added |
| Next (ranked) | conformance pair (register "HELD" / "self-certify" locate) · history pair with the route stated · evidence pair outside the R-36 cluster · second operation pair · route / exception need a designed record · Gate 1 proper external |
| Record | `analysis/theory/SEMOBS/` |

## F-LOG-0142 — 1ak strict-witness hunt: evidence outside R-36 NOT FOUND (locate `a15325f47`) · **disclosed strict-coding correction** (R-95 status and WP-4B state were silent inferences → UNK) · state pairs from already-read R-56/R-58/R-65 → **state EMPIRICALLY SUPPORTED (R4) but WEAK by disjoint clusters; target absorbed as an index on state; operation NOT DEMONSTRATED**; authority and kind survive the disjoint test; STOP

| Field | Value |
|---|---|
| L0 act | reviewer instruction (narrow 1ak while Gate 1 is external) |
| A | 7 locate hits, 5 with an outcome; no enclosing section holds a promoted counterpart → no strict evidence witness · L185 (matched line): "three occurrences in one subsystem … do not constitutionalize" → HYP: evidence measured in contexts, not instances (against v1.1's ≥ 2-instance bar) |
| Correction | `semobs_r2.py` `543ac3a1…`: R-95 outcome UNK (absence of a PREPARED marker ≠ in force); WP-4B 16:10 state UNK (an interpretation) → operation and state NOT DEMONSTRATED at r2 |
| r3 | `semobs_r3.py` `b9f4849d…` → `RESULT-r3.json` `76e4780e…`: s = target-indexed authorization state (declared); + R-56 / R-58 / R-65 events (already read) → state 3 distinct cluster-pairs, all sharing R-58 (disjoint: 1) · target NOT DEMONSTRATED (4 possible) → **scope acts as an index on state** (candidate state-minimization result) |
| Standing | authority SUPPORTED (2 disjoint) · kind SUPPORTED (2 disjoint) · state SUPPORTED (R4) / WEAK (disjoint) · evidence WEAK · operation, target, history, conformance, route, exception NOT DEMONSTRATED |
| Next | a state pair not involving R-58 · an operation pair with both statuses stated · evidence outside R-36 (in contexts) · a falsifier for scope-as-index · Gate 1 proper external |
| Record | `analysis/theory/WITNESS3/REPORT.md`, `SEMOBS/RESULT-r2.json`, `RESULT-r3.json` |

## F-LOG-0143 — 1ak-2: disjoint state witness from already-read R-81 / R-86 (`239314171`, committed before its run) → **authority, kind and target-indexed state each have 2 disjoint strict witnesses**; evidence WEAK; target, history possible only; operation, conformance, route, exception NOT DEMONSTRATED; STOP (stop rule met)

| Field | Value |
|---|---|
| L0 act | "1ak-2:go" |
| Pair | START(§12 reconcile): R-81 "all other Batch-7 work still frozen" vs R-86 "AUTHORIZED to execute … R-84's §12 reconcile"; the Batch-7 membership is stated in R-86 → the state at R-81 is DERIVED |
| Method | `semobs_r4.py` adds a disjoint-cluster count (exhaustive) to the R4 count; `RESULT-r4.json` |
| Result | authority 2 disjoint · kind 2 · **state 2 ({R-58, R-65}, {R-81, R-86})** · evidence 1 · the rest 0 |
| Not done this round (the stop rule) | operation pair with both statuses stated (locate in R-96 / R-98 status words) · evidence outside R-36 in contexts · the scope-as-index falsifier |
| Record | `analysis/theory/WITNESS3/REPORT.md` (round 2) |

## F-LOG-0144 — 1al competing formal models (`8b1f54c1e`; loader fix `1e1627c63` before the re-run): **guard signatures are operation-specific** (START {s} · OPEN-WORK / REGISTER {k} · AUTHORIZE-IMPL {a} · ASSIGN-ID {h}); **RAISE: {a, e} ≡ {e, t}, evidence alone refuted by P1**; **SUPERSEDE: {k} ≡ {r}** (the first formal appearance of route); ADOPT undetermined beyond authority; bisimulation 250 → 80, **history absorbed into status**; STOP-D (models distinguishable by named observations)

| Field | Value |
|---|---|
| L0 act | "1al:go" |
| Results | `MODELS-1AL/RESULT.json` `cd2534bc…`; secondary (post-hoc, labelled) `RESULT-secondary.json`, `RESULT-secondary-r2.json` (undetermined vs vacuous; identity tokens UNK against other values) |
| Formal | per-operation guard families; evidence-only RAISE refuted; RAISE and SUPERSEDE observational equivalences; ruling LTS: status + supersession behaviour-relevant, registry a function of status → history absorbed (second absorption after scope) |
| Distinguishing observations | RAISE at 1 instance: the same authority as P1 on a methodology target, or the ARB on a documentation-standard target · SUPERSEDE via ADR acceptance of an ADR, or via ruling of a decision-log entry (route vs kind) · ADOPT pair with equal kind / target class · WP-4B 16:10 state |
| Record | `analysis/theory/MODELS-1AL/REPORT.md` |

## F-LOG-0145 — 1am-1: the SUPERSEDE tie resolved with already-read R-44 / R-57 (spec `217e8e63d`): **{route} FALSIFIED** (R-77 RULING refused vs R-44 RULING performed), {authority} falsified, **{kind} survives** (mechanism supersedable; ADR / design rule not); STOP-C

| Field | Value |
|---|---|
| L0 act | "1am:go" |
| Event | SUPERSEDE of a mechanism by R-44 (reported in R-57: "a mechanism R-44 superseded"; R-44: "a mechanism, never an architectural decision … the mechanism is substitutable") |
| Result | `MODELS-1AL/RESULT-1AM-SUPERSEDE.json`: {r} and {a} inconsistent after the event; {k} consistent; {e}, {x} vacuous (UNK) |
| Consequence | route is not required for SUPERSEDE on current data; it coincided with kind in ADR-MP; the supersession guard follows object kind (mechanism vs decision / ADR / design rule), a source-stated distinction |
| Caveat | one discriminating event; evidence unresolved as a second SUPERSEDE variable |
| Next | RAISE {a, e} vs {e, t} (1am-2) · ADOPT equal-kind pair · WP-4B 16:10 state · Gate 1 proper external |

## F-LOG-0146 — 1am-2 / 1am-2b: RAISE determinant. **M_E FALSIFIED · M_T FALSIFIED (conditionally) · M_A, M_AT survive untested**; the only below-threshold promotion (R-39) is an authorized *exception* (H-X, post hoc); first run under the continuous subagent loop

| Field | Value |
|---|---|
| L0 act | continuous-loop instruction ("Use subagent to verify every step and ask to write review document") |
| Freezes | `09db5eac0` (task + bundle + sealed expectation) · `931ad666c` (A output, unread; review task) · `466b4a22c` (review B; the 1am-2b revision + sealed expectation) |
| Records | `analysis/theory/LOOP-1AM2/` {TASK, SOURCES, A/, B/, R2/A, R2/B, REVISION-NOTE, RECONCILIATION} |
| Independence | SECONDARY (Claude subagents, blind, audited Read/Write only) |
| Disagreements | E25 legality vs pending-decision (source interpretation; both survive) · ES-004.3 evidence (coding) · home vs item (evidence scope) · cross-unit comparison (formal reasoning) |
| Bias check | R1 sealed expectation failed; R2 matched (the S7 expansion was chosen after R1: disclosed) |
| Next | 1am-2c: the actor of E02 (2026-08-15, three-planes disposition) discriminates M_A / M_AT vs H-X |

## F-LOG-0147 — 1am-2c: E02's actor is **UNK** (A and reviewer agree); the pre-registered consequence is undetermined → the 1am-2 line is closed pending a designed observation (a DA below-threshold raise without an exception)

| Field | Value |
|---|---|
| Freeze | `c386e4f02` |
| Records | `analysis/theory/LOOP-1AM2C/` {PREREG, TASK, SOURCES, A/, B/, RECONCILIATION} |
| Independence | SECONDARY (blind Claude subagents; audited Read/Write only) |
| Surviving | M_A · M_AT · H-X |
| Next | 1ak-3 (evidence unit: contexts vs slices; the 1am-2 cross-unit disagreement) · 1am-3 |

## F-LOG-0148 — 1ak-3a: the parked → adopted Execution-Contract pair is **NOT an evidence witness** (kind and target differ; A and the reviewer agree); evidence stays WEAK; a post hoc facet observation (operating vs document standing) is recorded, not promoted

| Field | Value |
|---|---|
| Freeze | `3b76c1235` |
| Records | `analysis/theory/LOOP-1AK3/` {PREREG, TASK, SOURCES, A/, B/, RECONCILIATION} |
| Independence | SECONDARY (blind Claude subagents; audited Read/Write only) |
| Next | 1ak-3b: the slice-ledger candidate (parked 2026-07-26 L305): locate its later disposition, same home |

## F-LOG-0149 — 1ak-3b: U (contexts-unit witness) **UNRESOLVED** (A none vs reviewer E1, the substantive disagreement preserved); X: H-X survives with no new cluster; **observation: promotion thresholds are stated in three units (slices · occurrences · contexts); `e` is an overloaded dimension** → H-E2 (post hoc)

| Field | Value |
|---|---|
| Freeze | `d0cdaa44a` |
| Records | `analysis/theory/LOOP-1AK3B/` {PREREG, TASK, SOURCES, A/, B/, RECONCILIATION} |
| Independence | SECONDARY |
| Bias control | the reviewer's U = sealed expectation → not adopted |
| Next | 1ak-3c: formal re-analysis of the 1am-2b events with e = (repetition, breadth) · then 1am-3 |

## F-LOG-0150 — 1ak-3c: evidence-as-vector re-test. **Frozen result: evidence-only models falsified in all variants; reviewer: every strict-variant falsifier depends on E01 = 1 instance (sourced 1–2) → with E01 UNK, NOT FALSIFIED.** Correction of how F-LOG-0146 is read: its M_E falsification counts R-39's *exception* as an ordinary promotion. Surviving candidate: **G_RAISE = evidence ≥ θ ∨ authorized recorded exception**; M_A not required, not falsified

| Field | Value |
|---|---|
| Freeze | `9ca25087c` (script) · `01e6e6c96` (result + review task) |
| Records | `analysis/theory/LOOP-1AK3C/` {evector.py, RESULT.json, REVIEW-TASK, B/, RECONCILIATION} |
| Review | rule fidelity OK · recomputation matches · 12 disagreements classified (D1 E01 value: source interpretation, critical; D5 axis pooling: substantive) |
| Next | 1ak-3d: the temporal order of the ES-004.3 adoption vs the checklist's first execution (git + S1/S2); decides G_RAISE vs M_A |

## F-LOG-0151 — 1ak-3d: ES-004.3 decision-time evidence = **1** (the second instance was caught after the decision; A and the reviewer agree; **inference strength**) → **G_RAISE (single threshold ∨ recorded exception) FALSIFIED**; survivors M_A+X · M_T+X · M_AT+X · **M_K+X (extension vs new item, post hoc)**, confounded in every remaining pair

| Field | Value |
|---|---|
| Freeze | `550469df8` |
| Records | `analysis/theory/LOOP-1AK3D/` {PREREG, TASK, SOURCES, A/, B/, RECONCILIATION} |
| Independence | SECONDARY; the source corroboration is non-independent (one author, one commit) |
| Next | 1am-2d: locate a no-exception raise that breaks the actor / target / raise-kind confound (other PA-instruction rows; ARB/DA rulings extending an existing ES) |

## F-LOG-0152 — 1am-2d: **the confound is not broken** (A and the reviewer agree); M_A+X, M_T+X, M_K+X and M_AT+X are **observationally equivalent** on this slice → **the RAISE branch STOPS** (the evidence cannot discriminate); the reviewer's missed event R41-2 → H-K-choice (raise-kind as a placement *choice*), post hoc

| Field | Value |
|---|---|
| Freeze | `3ff922f74` |
| Records | `analysis/theory/LOOP-1AM2D/` {PREREG, TASK, SOURCES, A/, B/, RECONCILIATION} |
| Designed observations | the R-41 parsimony ground · a PA NEW raise at 1 · an ARB/DA EXTENSION raise at 1 |
| Next | 1am-3 (ADOPT role-conformance: a different variable, not demonstrated) |

## F-LOG-0153 — 1am-3: R-86 vs R-91 is a **STRICT conformance witness** (A and the reviewer agree; the sealed expectation failed); **conformance NOT DEMONSTRATED → WEAK**; kind NONE at the coarse grain; fragile under D1 (P's conformance partly stated) and D9 (finer-grain kind)

| Field | Value |
|---|---|
| Freeze | `58b9e0bc6` |
| Records | `analysis/theory/LOOP-1AM3/` {PREREG, TASK, SOURCES, A/, B/, RECONCILIATION} |
| Scope note | a check found that **no F-series commit touched `developer_guide/knowledgeos/s5_r7/`**: those commits are S-series (G-LOG), and the working-tree change there is not F-series. Left untouched, excluded from reasoning |
| Next | 1am-3b: the typed-header kind check (D9) · a second conformance cluster |

## F-LOG-0154 — 1am-3b: the typed headers differ (R-91 "Determination on submitted evidence" vs R-81..85 Authorization / Adoption / Adjudication) → the 1am-3 conformance witness is **STRICT only at the coarse kind grain**; conformance **WEAK, grain-conditional**; H-KC not supported (separation is tied to Event D). **Bundle defect disclosed** (R-86 row omitted → the issuer-vs-actor comparison is void)

| Field | Value |
|---|---|
| Freeze | `5706bd8ac` |
| Records | `analysis/theory/LOOP-1AM3B/` {PREREG, TASK, SOURCES, V/, RECONCILIATION} |
| Next | a second conformance cluster: a same-Y pair differing only in stated role separation |

## F-LOG-0155 — 1am-3d: **no second conformance cluster** (A and the reviewer agree): conformance is never stated on a not-accepted act in the Acceptance family → **untestable, not falsified**; non-acceptance is stated through *state* ("NOT OFFERED", "DEFERRED"); **the conformance branch STOPS** (conformance stays WEAK, grain-conditional); a designed observation is recorded

| Field | Value |
|---|---|
| Freeze | `31b20c781` |
| Records | `analysis/theory/LOOP-1AM3D/` {PREREG, TASK, SOURCES, A/, B/, RECONCILIATION} |
| Next | formal minimization (per-operation guards over the strictly supported dimensions + bisimulation), no corpus reading |

## F-LOG-0156 — 1an formal minimization (r1→r3, two independent formal reviews): per-operation guard family, union {a,c,e,h,k,s,t} (**model-relative**); r, x model-conditionally redundant; **operation UNTESTABLE**; bisimulation 250→80 (st, sup, ann behaviour-relevant). **Key result: formal minimality ≠ generalization**: only START{s} generalizes across clusters (the reviewer's LOCO-style criterion, by hand); t is a lookup / required-by-ignorance

| Field | Value |
|---|---|
| Freezes | `3d767bb07` (r1) · `0526dc464` (r2 fix + review task) · `61a0b38c5` (r3) · `a5ac38968` (r3 result + review task) |
| Disclosed defects | r1 bisimulation labels (fixed r2; no family class changed) · r2 consistency-by-ignorance (D1), half-applied C2 (D3) → r3 · r3 operation-test rule ≠ docstring (D-N2; no verdict change) · SUPERSEDE scope (r4 data lacks R-44) |
| Records | `analysis/theory/LOOP-1AN/` {minimize_1an.py, minimize_1an_r3.py, RESULT*, B-r2/, B-r3/, RECONCILIATION} |
| Next | 1an-r4: a frozen generalization instrument (cross-cluster recurrence + LOCO) + the R-44 scope fix + the D-N2 fix |

## F-LOG-0157 — 1an-r4 (reviewed): **no guard is yet shown to generalize by prediction**; START-s has *formal cross-cluster recurrence only* (2 disjoint clusters; LOCO uninformative) → UNCLEAR; RAISE, ADOPT, SUPERSEDE DO NOT GENERALIZE (RAISE LOCO at chance, below the majority baseline); four operations INSUFFICIENT; the operation verdict is corrected to **UNDETERMINED** (the MCR label withdrawn)

| Field | Value |
|---|---|
| Freeze | `35a756123` · `17650885e` |
| Review | B-r4: fidelity OK · recomputation matches · criterion NOT faithful (disjoint counts, contrary recurrence) · 16 disagreements |
| Records | `analysis/theory/LOOP-1AN/` {minimize_1an_r4.py, PREREG-r4, RESULT-r4, B-r4/, RECONCILIATION-r4} |
| Next | 1an-r5: START {s}-only diagnostic (reviewer-justified, frozen first; with a majority baseline; not a rescue) |

## F-LOG-0158 — 1an-r5 (reviewed): START {s} "diagnostically predictive" is **coding consistency, not discovery**: a near-definitional guard (the untrained rule scores 10/10); the frozen baseline was structurally capped (**instrument flaw, disclosed**); knife-edge under the circularity filter; 2 decision episodes. **The formal-generalization branch STOPS**; H-AS (analytic vs synthetic guards) recorded post hoc

| Field | Value |
|---|---|
| Freeze | `b3dc5348c` · `a37488aa5` |
| Review | B-r5: recomputation matches; D-1 baseline defect; D-10 definitional; D-11 knife-edge; D-16 pooling |
| Records | `analysis/theory/LOOP-1AN/` {diag_1an_r5.py, PREREG-r5, RESULT-r5, B-r5/, RECONCILIATION-r5} |
| Next | H-AS classification of guards (analytic / synthetic) · evidence split · kind split · clusters via Gate 1 / releases (human) |

## F-LOG-0159 — 1ag run B2 (a fresh Claude coder; SECONDARY, **not Gate 1 proper**): intra-family stability high but with correlated priors; the main-coder bias is **real but bidirectional** (MAIN right on V3 in 4–5 rows; the blind coders share an AMBIGUOUS inflation); the coverage gap is an enumeration habit; **V1/V4 instability is mostly the manual** (the vacuous case explains 15/20); violation detection untested; MAIN's Gate 1 baseline was not cleanly sealed. **A human decision is required: manual r3 (a frozen-protocol change)**

| Field | Value |
|---|---|
| Human act | "for 1ag, Gate 1 proper: give ~/F-GATE1-PROPER-BUNDLE-01.tar, start a new fresh subagent" |
| Freezes | `717f0e238` · `e515f9b35` · `bf3050926` |
| Records | `analysis/theory/GATE1-B2/` {COMMISSIONING-NOTE, UNPACKED-MANIFEST, RETURNED-SEAL, codings, SCORING/, REVIEW/, RECONCILIATION} |
| Next | human: authorize (or not) manual r3 · a non-Claude coder is still required for Gate 1 proper |

## F-LOG-0160 — 1ao (H-AS): **ANALYTIC**: START/s, START/t (derivative), ADOPT/c · **SYNTHETIC**: ADOPT/a, AUTHORIZE-IMPL/a, OPEN-WORK/k, RAISE/e, ASSIGN-ID/h (REGISTER/k disputed) · **UNKNOWN**: RAISE/t, SUPERSEDE/k (A and the reviewer agree 10/11; the sealed expectation was partly reversed). The analytic START premise is independently established in only 2/11 events; every synthetic contrast is weak; a hidden status variable is found in AUTHORIZE-IMPL (a ⇒ status ⇒ in force)

| Field | Value |
|---|---|
| Freeze | `c38ee20c1` |
| Records | `analysis/theory/LOOP-1AO/` {PREREG, TASK, EVENTS, CODING-LINES, A/, B/, RECONCILIATION} |
| Next | 1ap: the AUTHORIZE-IMPL authorizing-ruling status (direct authority vs status-mediated); pending: the human decision on manual r3 |

## F-LOG-0161 — 1ap: ruling force. **M_issuer FALSIFIED** (non-circular: the same Chief-issued R-81..85 go from NOT-IN-FORCE to IN-FORCE via the DA's adoption, R-86); M_adopter FALSIFIED; **M_status UNDETERMINED** (consistent; the R-89 value was circular → UNK); the chain is attributed but not established. Authority is not a direct guard of force

| Field | Value |
|---|---|
| Freeze | `58f202115` |
| Records | `analysis/theory/LOOP-1AP/` |

## F-LOG-0162 — 1aq: START premise independence. **0 INDEPENDENT; 10/11 START legality events are ACT-NOT-ATTESTED** (ruling permission statements, not acts). **Target-indexed state is downgraded**: supported as a property of *permission statements*, not demonstrated on observed acts. An act-attestation audit of all legality events is required

| Field | Value |
|---|---|
| Freeze | `544d69232` · defect correction `78619e9de` |
| Bias check | the sealed expectation (3–5 INDEPENDENT) failed: 0 |
| Records | `analysis/theory/LOOP-1AQ/` |
| Next | 1ar: an act-attestation audit of all 36 strict legality events; recount the strict witnesses |

## F-LOG-0163 — 1ar act-attestation audit: **the empirical core shrinks to {authority}** on attested acts (2 surviving strict pairs). **Kind and target-indexed state → NOT DEMONSTRATED on attested acts** (their witnesses are permission statements / generic practice); e WEAK (1 cluster); c WEAK (coarse). **H-DEONTIC supported**: norm-making operations are attested; execution and register-meta operations are norm statements coded as acts (a coding artifact)

| Field | Value |
|---|---|
| Freeze | `34c685c85` |
| Disclosed | task self-conflict (mine): the worker recomputed pairs; the reconciliation uses the recorded witness list |
| Records | `analysis/theory/LOOP-1AR/` |
| Next | a human decision brief (schema r2: norm statements vs act observations; the reframed theory target) · D10 ASSIGN-ID occupancy contrast |

## F-LOG-0164 — manual r3 effect (SECONDARY): **pre-registered TRADE-OFF**. Gate 1 V1 +0.46 and V4 +0.18 (mostly genuine; R-31 a convergent error), but **V2 −0.14 (G7 wording) and V3 −0.11 (G6 unconstrained readings + a V3 gap)**; the derived prediction was falsified; ops agreement reached 1.0 with no ops rule → **shared priors**: "within-family reproducibility", not reliability. The r4 recommendations await the human; upstream, the schema's norm/act conflation (F-LOG-0163) matters more

| Field | Value |
|---|---|
| Human act | manual r3 adoption, 2026-09-30 |
| Freezes | `34d874221` · `ce0410fc3` · `3efdd68d0` · `fc42a7305` |
| Records | `analysis/theory/GATE1-R3/` |

## F-LOG-0165 — 1as: the R-90 assignment contrast is **POSSIBLE** (1 cluster); its variable is not distinguishable from retirement → h stays NOT DEMONSTRATED

## F-LOG-0166 — 1at: RAISE evidence **CONFOUNDED**: 0 clean pairs; non-promotion reasons are scope / domain / prior ruling; mixed units; **the coded sample omitted items not promoted at ≥ 2–3 slices (a selection artifact)** → **e: WEAK → NOT DEMONSTRATED**; evidence is not sufficient for promotion in R-36

## F-LOG-0167 — 1au: schema-r2 decision package, review **PASS_WITH_LIMITATIONS**: necessity is narrow (only kind depends solely on it); the dual-nature problem is unhandled (both authority pairs sit on it); a minimal v1-internal alternative exists; a "defer + blind pilot" option was added. **STOP: human decision required**

| Field | Value |
|---|---|
| Freezes | `ed231ccde` (1as, 1at) · `974bd7f3f` (1au) |
| Records | `analysis/theory/LOOP-1AS`, `LOOP-1AT`, `LOOP-1AU` |

## F-LOG-0168 — 1av norm/act pilot: type agreement 1.000 is **degenerate** (≈ 88% quote overlap, identical invented verbs, 6/6 hard cases on the same side) and **cue-driven** (event ids carried audit-side interpretations: **my design defect**, disclosed). Reliability (F3) **not established**. The two-level form is codable, and `source_act` is needed in a norm register (B records no stater); B is untested. R-60 note: the audit is right (k stays 0). Next: a de-cued pilot (1av-b)

| Field | Value |
|---|---|
| Human act | "D: pilot, then decide" |
| Freezes | `7cae5e12f` · `f3cc2265f` · `84c6d048f` |
| Records | `analysis/theory/LOOP-1AV/` |

## F-LOG-0169 — 1av-b de-cued pilot: **individuation precedes classification**. 11/36 events are individuated only by the coder's description (+ latent span collisions; an E22 anchor-coverage defect, **mine**); kind's "strict" pairs differ in zero recorded fields (a single sentence stating a distinction; not two occurrences); e's pair is also description-individuated; **authority must be re-verified** (E01 quote shift). Span-level individuating anchors are **required under both schema options A and B**. Agreement is still degenerate; cue sensitivity is in the input

| Field | Value |
|---|---|
| Freezes | `5e1dde247` · `43c4eab4e` · `13539bdf0` |
| Records | `analysis/theory/LOOP-1AVB/` |
| Next | a human decision on the schema, now including span-level individuation |
