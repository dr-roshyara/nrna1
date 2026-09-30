# S-Series TODOs and decision sheet (KnowledgeOS chronological-read, S-lane)

**Kind:** session-state, the single S-Series working list (placement: `php scripts/doc-placement.php --scope=session-state` → `.claude/`).

**Status:** current as of 2026-09-27. R7 v2.3 frozen (G-LOG-0088) → **v2.4 approved + implemented (G-LOG-0089)** → ONE independent audit (G-LOG-0090): NEEDS-REVISION, F-01 MATERIAL → **F-01 repaired (G-LOG-0091, `47b3d3b5b`; 1,051 tests, 0 failures)** → **focused independent re-check ACCEPTABLE (0 MATERIAL)**. R7 NOT yet accepted; S5 NOT activated; RI-1 DEFERRED; H-19 SEALED. **Researcher's rule:** observe the corpus as brainstorming material and derive a robust theory; "no coherent theory found" is a valid outcome.

**Authority:** none. Governing documents are Master Protocol v3.5 (`docs/knowledgeos/chronological-read/prompts/20260911_0221_prompt3-optimized.md`) and P3b v1.7 (frozen, G-LOG-0034). Decisions live in `docs/knowledgeos/chronological-read/P3B-GOVERNANCE-LOG.md`. **This file decides nothing.**

**Governing principle (human-set, 2026-09-26):**
> **Every experiment must either reduce uncertainty about the corpus or reduce the cost of obtaining reliable evidence.** An experiment that does neither is not run.

**Immutable conceptual boundary:**
- never SOURCE → ML → THEORY;
- never SOURCE → similarity → CANONICAL.

ML produces candidates, diagnostics and prioritization only, always with full provenance, and every one of them passes human adjudication. The S5 reconstruction floor (R19) is never substituted.


## MASTER ROADMAP AND TODOS (entry point; supersedes the older roadmap views below where they differ)

**Where we are:**
- **Research infrastructure:** ~85–90% (directional estimate, not a measured KPI).
- **Master Protocol P1, P2 and P3a:** DONE (discovery; 2,497 P2 labels; P3a pair verdicts frozen).
- **P3b S5 per-label floor:** **0 of 396 production batches** (OB0004 attempts FAILED; OB0018 was a pilot, UNDETERMINED).
- **Theory:** not yet reconstructed; not yet implementable.

**Ultimate milestone:** one coherent, evidence-linked KnowledgeOS Theory document that can be read end to end, with every principle traceable to evidence, its mathematical and DDD foundations explicit, and implementable without guessing.

**Critical path:** Audit → Activate → S5 → S5a/S5b + P3b terminal → P4–P7 → mathematics + DDD (+ logic) → theory candidates → attack/validation (incl. H-19 out-of-sample, by governance) → governance GATE → theory specification → implementation.

### Stage 1: machinery safe (NOW)
- [x] Revision 5 drafted, implemented, verified (`2d12e96cb`); Blocker 1 verifier + runbook integrated (`20c0edebc`); 820 tests OK; manifest still revision 3.
- [x] **ONE independent adversarial audit of revision 5**: DONE. **Verdict NEEDS-REVISION**: 8 MATERIAL (R5-01…R5-08), 9 MINOR, 1 COSMETIC; composite false PASS through the full verifier (FP-6). Report `audit-p3b/20260926_REVISION-5-INDEPENDENT-AUDIT.md`; scripts `audit-p3b/r5-audit/`. Nothing repaired.
  - **MATERIAL:**
    - R5-01 no record→claim provenance link (records carry no facts);
    - R5-02 R19 timestamps compared as strings and self-declared;
    - R5-03 undeclared R5 runs and synthesis reading under a unit id;
    - R5-04 label ownership spoofable via `--label`;
    - R5-05 plan sizes, packing order and plan hash not checked against content, slice or hash;
    - R5-06 NARROWS/CONTRADICTS/RETRACTS exempt from the read-state rule;
    - R5-07 S1 quotes unverified;
    - R5-08 EMPTY objects skip S3/edges/content checks.
  - **Statistical design** not yet well-defined (strata disjointness, EMPTY stratum, estimand unit, CI/variance).
- [x] **HUMAN DECISION on the audit result**: repair slice authorized (G-LOG-0083); revision 5 withdrawn.
- [x] **Revision-6 repair slice**:
  - causal analysis first (`92428e944`);
  - implementation: `p3b_s5_r6.py`, `p3b_s5_r6_verify.py`, the verifier gate, the reader plan check;
  - addendum `prompts/20260926_2300_p3b-agent-contract-r6-addendum.md` (frozen statistical specification + ML boundary);
  - record `audit-p3b/20260926_REVISION-6-IMPLEMENTATION-RECORD.md`;
  - every preserved attack is a permanent full-path regression; positive control PASS; PASS requires every one of P/E/R/T/L/S; 29 files / 873 tests OK; `prepare --check` IDENTICAL; H-19 0 violations.
  - Stated disagreement: "/" is not a range connector.
- [x] **Narrow independent re-audit of revision 6** (G-LOG-0084; fresh-context subagent, same model family): **NEEDS-REVISION**.
  - Evidence: `audit-p3b/20260926_REVISION-6-INDEPENDENT-RE-AUDIT.md`, scripts in `audit-p3b/r6-audit/`.
  - Counts: 234 scored cases; 19 MATERIAL, 12 MINOR, 1 COSMETIC.
  - Every R5 attack pattern is closed and the PASS conjunction held, but gaps in coverage remain:
    - M1: a timeline point with a null `source_id` is not enumerated;
    - M2: legacy `-R2*` run ids bypass the R6 run set;
    - M3: `timeline_summary.contradicted_by` and `superseded_by_sources` are outside the claim list;
    - M4: the statistics functions accept invalid frames (TIER-X as a stratum, n=0 or n=1 variance, a vacuous frozen-sample guard, overlap).
  - Trust boundary: the logs and provenance files are writable, with no hash chain.
  - `/` ruled conformant (G-LOG-0045).
- [x] **Root-cause architecture analysis:** `audit-p3b/20260926_REVISION-6-REAUDIT-ROOT-CAUSE-ARCHITECTURE.md` (32 findings → RC-1 universe mismatch, RC-2 unwitnessed execution authority, RC-3 unvalidated estimator domain).
- [x] **Model-D readiness experiment:** `audit-p3b/20260926_MODEL-D-READINESS-RESULT.md`: READY, conditional on C1–C7. Real harness subagents, the real reader, synthetic bytes; 17/18 attacks detected; F2 is within the preservation boundary. **Evidence that the proposed trust root is observable.**
- [x] **Human decisions HD-1…HD-9 (G-LOG-0085):** Model D adopted (the harness transcript = the execution witness within the declared trust boundary); the trust boundary is MUST-FIX before S5; M3 MATERIAL; Revision 7 (R6 not amended); one narrow independent R7 audit afterwards.
- [x] **R7 design package drafted:**
  - addendum `prompts/20260926_2400_p3b-agent-contract-r7-addendum.md`: HD-1 mapping, typing registry, W1–W7, `WITNESS.jsonl`, statistics domain, 38-case matrix;
  - ADR `audit-p3b/20260926_ADR-R7-EXECUTION-TRUST-ROOT.md`;
  - plan `docs/plans/20260926-1548-s-series-revision-7-plan.md`.
- [x] **Pre-freeze design review:** `audit-p3b/20260926_R7-PREFREEZE-DESIGN-REVIEW.md`, NEEDS-PREFREEZE-REPAIR (PF-1…PF-8 material). A self-review by the design author; not independent.
- [x] **Design repair (G-LOG-0086):**
  - R7 addendum v2 with DR-01…DR-14: object-level summary relations; contradiction ≠ precedence (A.10 dated order only); discovery ≠ validation; registry sovereignty F1/F2; W8 execution-capability closure + SEAL; the W1 completed-dispatch table (no resumption); W7a witness monotonicity; byte-exact anchoring; a neutral decoder; `inv`; preservation ≠ trust; five bounded contexts with the verifier as composer; REJECT vs NOT-ESTIMABLE.
  - Static design checks 32/32 PASS; tests T01–T76.
  - Record: `audit-p3b/20260926_R7-DESIGN-REPAIR-IMPLEMENTATION-RECORD.md`.
- [x] **Freeze-readiness check:** `audit-p3b/20260926_R7-FREEZE-READINESS-RECORD.md`.
  - DR-15 layered verdicts (BATCH-PASS := U∧W∧E∧R, strong-Kleene; STATISTICS-VALID := S; PROGRAM-ACCEPTED := ∀b BATCH-PASS ∧ STATISTICS-VALID; no bare PASS).
  - DR-16 W1 reason codes.
  - Static checks 57/57; tests T01–T83.
  - **DESIGN FREEZE BLOCKED by FR-1:** W8 forbids Read, but agents must receive inputs (synthesis needs its unit records; slices, plan, contract; persisted outputs). Not repaired.
- [x] **HUMAN:** FR-1 → **Option A** (G-LOG-0087)
- [x] **FR-1 repair:**
  - R7 v2.2 (DR-17): W8 gains a 4th disjoint capability, *Read of p ∈ I(run)*;
  - `RunInputManifest` (Universe) frozen at dispatch and hash-bound, with closed categories, prohibitions → R7-U, and persisted outputs as a witnessed class;
  - a `read-input` witness event; input reads never evidence;
  - T84–T97; static checks 73/73; T01–T83 unchanged;
  - record `audit-p3b/20260926_R7-FR1-REPAIR-RECORD.md`.
- [x] **HUMAN DESIGN FREEZE (G-LOG-0088):** v2.3 `cdbf53cb…`; EXTENDS → later_refinement; archive `~/knowledgeos-witness-archive/` (`05569322d`)
- [x] **R7 implementation B0–B9** (`ef9bdf7ba`):
  - bounded contexts, the harness witness, layered verdicts, I(run), the reader default-deny;
  - 9 new suites; the acceptance matrix through the production verifier;
  - full suite 38 files / 1,022 tests, 1 expected failure (DC-2);
  - static checks 73/73; `prepare --check` IDENTICAL; H-19 0 violations;
  - record `audit-p3b/20260927_REVISION-7-IMPLEMENTATION-RECORD.md`; guide `developer_guide/knowledgeos/s5_r7/`.
- [x] **v2.4 decision package** (2026-09-27; nothing adopted or implemented; v2.3 untouched):
  - semantic analysis `audit-p3b/20260927_R7-NDB-DC-SEMANTIC-IMPACT.md`: no conflict; INV-LEX (lexical occurrence ≠ evidentiary support);
  - proposal `audit-p3b/20260927_R7-v2.4-CONTRACT-AMENDMENT-PROPOSAL.md`: 4 META rows (DC-1 with an own-object self-reference rule); corpus typing failures 241 → 0; claims identical 20/20;
  - non-interference tests `scripts/tests/test_p3b_s5_r7_v24_proposal.py` (23 OK, non-vacuous);
  - DC-2 retarget prepared (53/53 in place), NOT applied;
  - Read-integrity characterization (synthetic).
- [x] **G-LOG-0089:** v2.4 approved as proposed; DC-2 approved; RI-1 DEFERRED (not accepted)
- [x] **v2.4 implemented + engineering-verified:** addendum sha `fa837177…`; one code change (Universe: sha, DC-1 rule); fixture de-sanitized (63 real META mentions, BATCH-PASS); INV-LEX suite; DC-2 applied. Audit package `audit-p3b/20260927_R7-INDEPENDENT-AUDIT-AUTHORIZATION-PACKAGE.md`
- [x] **Independent R7 audit (G-LOG-0090; Sonnet 5, fresh context): NEEDS-REVISION.** 1 MATERIAL: F-01 (I3/W8) persisted-output authorization by basename only (`p3b_s5_r7_witness.py:294–298`); a Read outside I(run) gives BATCH-PASS (reproduced). 2 OBSERVATIONS. Report `audit-p3b/20260927_R7-INDEPENDENT-AUDIT.md`
- [x] **G-LOG-0091:** audit accepted; F-01 repaired (Witness only: exact announced-path identity + displayed-content match; RED 5/6 → GREEN; auditor reproducer → BATCH-FAIL); full suite 39 files / 1,051 tests, 0 failures; contract unchanged
- [x] Track B pass 0 (`research/RESEARCH-LEDGER.md`): H1–H5 on the 20 committed S4 objects are method artefacts, vacuous or not significant; pre-registered for S5

- [x] **2026-09-28:** Freeze 1 (G-LOG-0094) · frame reconciled 12/41 · IV-1a re-basing · binary read → 12 decisions (G-LOG-0096) · IV-2 resolved · Freeze 2 proposal approved, ORD-1 (b) (G-LOG-0097) · DC-3 found → **v2.6-DC3 implemented** (G-LOG-0099; addendum `870a5952…`; 1,096 tests, 0 failures)
- [x] **R7 PRODUCTION ACTIVATED (G-LOG-0102, `5469a014f`)**: manifest `c7f3b257…` (rev 7, v2.6; rev3 base recorded); AG-2 R2→R7 state transition (396 PREPARED at R7; OB0004 R2 history kept); 396 frozen plans; strata 66/7/4/173/1,725 = approved; frame `d0c972bf…`; prepare --check IDENTICAL (rev3 base); H-19 SEALED; full suite green. AF-1 fixed before activation
- [x] **rev7 slices committed (`36ba7f578`) · FREEZE 2 (G-LOG-0103)**: seed `1285…0354` (OS CSPRNG); 231 = 33/7/4/87/100; sample `e3f3ed76…`; anchor `8e0d9cb4…`; θ_A + double-adjudication subsamples (separate seed); record `audit-p3b/20260928_FREEZE-2-RECORD.json`
- [x] **G-LOG-0104:** package approved; **EG-2 v2.7 SLICE-VIEW implemented** (addendum `9523c712…`; 1,975/1,975 production views lossless) · **EG-1 orchestrator** `scripts/p3b_s5_r7_orchestrate.py` (artifacts byte-identical to the fixture; verifier BATCH-PASS). Record `audit-p3b/20260928_EG-1-EG-2-IMPLEMENTATION-RECORD.md`
- [x] **EG-4 (G-LOG-0105):** production rebound to v2.7 (contract `49169328…` → `9523c712…`; manifest `c7f3b257…` → `9b172123…`, body byte-identical; state rebind, 396 PREPARED at R7); prepare --check IDENTICAL; H-19 SEALED; OB0012 dry-run prepare ACCEPTED
- [x] **Canary pre-check (read-only)** `audit-p3b/20260929_S5-CANARY-PRECHECK.json`: OB0012 (5 SINGLE) + OB0114 (3 SINGLE + DECOMPOSED 2U+S + EMPTY) = 11 runs; bindings, views, I(run), prompts, W8 grammar, stop rule all pass
- [x] **EG-5 design loop** (A spec → B review → A v2 → B re-review; `audit-p3b/eg5/`): EG-5b found (record `run_id`/`rs_id` vs G-07 — blocks EVERY batch incl. OB0012); option (a) addendum-only, verifier unchanged, probe-verified; EG-5c recorded (verifier residual)
- [x] **EG-7 fixed** (`3b5eb05f8`): state accepts BATCH-PASS for R7 VERIFIED
- [x] **EG-6 designed** (3 A/B rounds, SPEC-ACCEPTABLE; `audit-p3b/eg6/`): retire + retry policy RR-1..7, classes, M = 4, W-SYS
- [x] **EG-3 designed** (hub R(run): reads median 41 / max 127 vs 318 / 5,950; `audit-p3b/eg3/`) · **EG-9 designed** (§21 + H-06 under R7; `audit-p3b/eg9/`) · **EG-10 found** (runbook lacks → PROPOSED; canary-blocking)
- [x] **v2.8 approved (G-LOG-0106, R-I), implemented (5 A/B-reviewed slices), rebound** (contract `4d4dac6b…`, manifest `5978fbf5…`); full suite 1,400 OK; canary pre-check v2.8 all green (`audit-p3b/20260930_S5-CANARY-PRECHECK-v28.json`); EP-01 committed + sha-bound
- [ ] backlog: hash-chain P3B-GOVERNANCE-LOG.md (governance integrity) · `add_comparison` R2S naming under R7 · orchestrator prompt 'whole file' sentence has no contract anchor
- [x] **S5 canary attempt 1** (G-LOG-0107): STOPPED — class X environment (reader resolves root from cwd); findings F-1..F-5; repairs R-1/R-2 committed
- [ ] **NEXT (HUMAN): canary restart decision** `audit-p3b/20260930_S5-CANARY-RESTART-DECISION.md` (v2.8.1 status text + rebind; OB0012 attempt 2; fresh session in nrna1 via the operator brief; the 5 stray logs in the ansible repo) → canary OB0012 + OB0114 (stop: ≥ 6/11 runs failed) → S5 (396 / 1,975)

**Critical path from here (in order; researcher's rule: the corpus is brainstorming material; derive a ROBUST theory or report none):**
- [x] **A1** Focused independent re-check: **ACCEPTABLE**. F-01 CLOSED (6 shipped + 6 new adversarial reproducers); I1: 16,000 seeded trials, 0 findings; I7: no MATERIAL counterexample. 0 MATERIAL; 2 MINOR (RC-02 empty persisted file → false FAIL; RC-03 missing manifest-entry file skips its hash check, `p3b_s5_r7_verify.py:100`, not exploitable per ADR-R7-01). Report `audit-p3b/20260927_R7-FOCUSED-RECHECK.md`
- [x] **A2 (G-LOG-0092):** R7 ACCEPTED (v2.4 + F-01); RC-02 deferred; **RC-03 fixed** (missing I(run) file → frozen-integrity FAIL; RED showed a real BATCH-PASS → GREEN; 1,052 tests, 0 failures)
- [x] **B1 proposal written** (`audit-p3b/20260927_B1-EXPERIMENT-DESIGN-FREEZE-PROPOSAL.md`, on top of EP-01): D1 stratum precedence; **D2 the normal CI under-covers (SINGLE, D = 10: 45.5%) → a minimal v2.5-S exact-bound amendment recommended**; D3 θ_D ≠ θ_E; P1 the audit re-analyses = the replicate subsample; P2/P3 pre-registration with Model 0; P5 H-19 = corroboration set; canary rule
- [x] **G-LOG-0093:** B1 approved conceptually → **v2.5-S implemented** (exact hypergeometric bound primary, Bonferroni over strata; normal CI only if d ≥ 5 ∧ n − d ≥ 5; addendum v2.5 `63fdda65…`; T105–T109, coverage checked exhaustively) → **B1 v2 freeze candidate** (θ_D/θ_A/θ_E with reference-standard criteria; R1/R2; operational Model 0 per H in `research/prereg_v1.py`; Holm confirmatory, BH exploratory; H-19 last). Pass 0b: nothing in the pilot survives Model 0; H4's pass-0 support was all ties (corrected)
- [ ] **B1 HUMAN: approve B1 v2, then (after the binary read and the 12 decisions) the freeze act** — (before any S5 data exists): population, sampling, seeds; the error-audit design (θ_E) kept separate from the disagreement rate (θ_D); a **replicate subsample** (θ_D + invariance under reconstruction choices); **pre-registered hypotheses incl. a null model (Model 0: method + topic frequency)** and a multiple-testing procedure; failed / NOT-ASSESSABLE handling; **H-19 designated as the sealed corroboration set** (opened only against a frozen theory)
- [ ] **B2 HUMAN:** the required binary decisions (the 12 binary pre-classifications) and the statistical freeze
- [ ] **B3 HUMAN:** S5 activation, **staged**: canary batch(es) first (stop on systematic W8/agent failures) → 396 batches / 1,975 labels
- [ ] **B4** P3b terminal reconstruction
- [ ] **C1** Research dataset G = (V, E, τ, ρ, p) plus an explicit model of the observation process
- [ ] **C2** Track B pass 1: pre-registered H1–H5 on the S5 population (null models, permutation baselines, BH correction); search for invariants, not frequencies
- [ ] **C3** Formalize the surviving structures with the simplest adequate mathematics (relations → graphs → orders/closure → only then lattice/algebra/logic/category)
- [ ] **C4** Competing theories (including Model 0) + computer counterexample search (∃ observed x: ¬H(x))
- [ ] **C5** Statistical validation (descriptive → statistical → structural → theoretical → corroborated)
- [ ] **C6** Open H-19 against the FROZEN theory → independent corroboration
- [ ] **C7** Candidate KnowledgeOS theory, or "no coherent theory found"; canonicalization only after C6
- [ ] ML (only after adjudicated S5 data): lexical/TF-IDF → graph → embedding baselines, for candidate generation and review prioritization only; never truth, verdicts or weights
- [ ] RI-1 (deferred): reopen only on its G-LOG-0089 conditions
- [x] R7 implementation → engineering verification → STOP (done; see above)
- [ ] HUMAN: authorize ONE narrow independent R7 audit (never automatic)
- [ ] HUMAN: acceptance of R7

**Critical path (updated 2026-09-26):**
R6 → independent re-audit → NEEDS-REVISION → RC-1 / RC-2 / RC-3 → Model-D readiness (READY, conditional) → human adoption of Model D → **R7 design freeze** → R7 implementation → independent R7 audit → human acceptance → activation → binary 12-file classification → frozen audit parameters/seed → **S5: 396 batches / 1,975 labels** → S5a/S5b + 66 hubs / H-02 → P3b terminal + probability audit → P4 → P5 → P6 → P7 → mathematical/logic/DDD theory recovery → theory attack (H-19 out-of-sample, by governance) → governance decision → canonical KnowledgeOS Theory.

### Stage 2: activation (after a clean audit; each item a human act or gate)
- [ ] Authorize the **binary pre-classification run** (a checker read of 12 files' bytes through the seal-aware resolver).
- [ ] Run it; **decide the 12 binary files** (FALSE-HIT / NOT-CONSUMED-ESCALATED / EXTRACT with member-level seal check).
- [ ] Freeze the **audit-sample parameters and seed** (drawn before any S5 output exists).
- [ ] Activate revision 7 (R5 withdrawn; R6 superseded): regenerate the manifest with revision 7, the R7 addendum sha, `r7_plan_sha256` and `legacy_ledger_sha256`; write the production plan (SINGLE 1,789 / DECOMPOSED 178 / EMPTY 8); `prepare --check` against it; final production verification.
- [ ] **Human S5 EXECUTION AUTHORIZATION.**

### Stage 3: S5 evidence reconstruction (the floor; never replaced by sampling, R19)
- [ ] 396 batches / 1,975 labels under the revision-5 runbook (two-path dispatch; byte mode; S1–S3); complete provenance.
- [ ] Hubs (66) ESCALATED (LOAD), with scoped absence claims; H-02 handling for Tier-X labels; empty-set labels per R17.
- [ ] **Pre-registered probability audit** (adjudicated discordance; stratified HT; blind; ML queues excluded), completed **before the P4 hand-off**.

### Stage 4: reconciliation (what did the corpus actually develop?)
- [ ] **S5a / S5b passes** → **P3b terminal predicate (§23)** → `30-RECONCILIATION.md` → **P4 hand-off**.

### Stage 5: recovery (after the evidence base is stable)
- [ ] **Decision A** (and A2: where P4 maps; A3: theory-verification ownership): must be decided before this stage.
- [ ] P4 membership → P5 validation vector → P6 governance vector → P7 synthesis ("RECONSTRUCTED, PENDING GOVERNANCE REVIEW", never FINAL).
- [ ] Mathematical reconstruction (only structures the corpus supports) · DDD reconstruction (from evidence, not imposed) · logical reconstruction · cross-domain reconciliation · theory candidates with explicit provenance.

### Stage 6: attack and validate
- [ ] Internal consistency; mathematical and logical correctness; domain consistency; counterexamples; edge cases; reproducibility; implementation feasibility.
- [ ] **H-19 hold-out as out-of-sample test**: unsealing only by a separate governance decision; S5c stays PROHIBITED until then.
- [ ] Canonicalization **only by a governance act** (GATE).

### Final
- [ ] KnowledgeOS Theory Specification → implementation architecture derived from the validated theory → TDD/verification model → prototype → full implementation.

**Human role by stage:** governance decision-maker (now) → research sponsor / quality controller (S5: exceptions, audit results) → senior scientific reviewer (reconstruction) → theory co-designer / critical reviewer → product/architecture decision-maker (before implementation).

**ML:** only after adjudicated S5 data exist, for candidate retrieval, review prioritization, anomaly detection and uncertainty ordering; never identity, equivalence, births, edges, canonicalization, truth, or a replacement for the random audit.

---

**Four kinds of work, never collapsed into one another:**

| Kind | Examples | What it may produce |
|---|---|---|
| **Instrument validation** | V1.2.1 | a reliability verdict on instruments only (V1.2.1 §22) |
| **Strategy comparison** | Gate C (done); a future v2 pilot | an experimental comparison only; never a protocol change by itself |
| **Experimental research** | register, hypotheses, discovery | layer-B/C records (P3b §1B); never layer-A evidence (G-12) |
| **Production reconstruction** | S5 per-label floor → P4 → P5–P7 | protocol outputs under v3.5 + P3b |

---

## DONE

- **Conformance mapping:** G-LOG-0068, `audit-p3b/20260925-S-SERIES-CONFORMANCE-MAPPING.md`.
- **Governance decision analysis:** G-LOG-0069, `audit-p3b/20260925-S-SERIES-GOVERNANCE-DECISION-ANALYSIS.md`.
- **Architecture decision package:** `2509dd7e9`, `audit-p3b/20260925-S-SERIES-ARCHITECTURE-DECISION-PACKAGE.md`. It records no human act, so it has no G-LOG entry (P3b §17: the log records human acts).
- **V1.2.1 instrumentation repair and readiness audit:** frozen at `51e04f8cb621cee1b8083245d8068418ac80b6c5` (G-LOG-0066); READY-FOR-HUMAN-AUTHORIZATION (G-LOG-0067).
- **Housekeeping:** session log and CONTEXT caught up (2026-09-25).

## HUMAN DECISIONS PENDING (see the decision sheet below)

- **Decision A:** DEFER / A / B / C.
- **A2, A3, A4:** only if B or C.
- **A5:** terminology clarification (F-02, F-03).
- **A6:** disposition of the untracked v1.2 copy.
- **A7:** formalize the augmentation/substitution boundary (F-14).
- **Decision B (instrument E0):**
  - **History:**
    - V1.2.1 was authorized (G-LOG-0070) and then stopped, RUN-INVALID (G-LOG-0071);
    - V1.2.2 (G-LOG-0072) and V1.2.3 (G-LOG-0073) each drew an audit of NEEDS-REVISION;
    - **V1.2.4** (G-LOG-0074, A-1 transcript classification) is frozen at **`c9cfd8f568a250a06563fe9b8c3b6496b4d3ac18`**, namespace PX0109 / `pilot-s5-decomp/v1-r3/`.
  - **The narrow re-audit** (`audit-p3b/20260926_V1.2.4-NARROW-INDEPENDENT-REAUDIT.md`) found no new material defect. **INSTRUMENT HARDENING CLOSED.**
  - **Step 2/3 readiness:** PASS, SCOPE-SAFE (`audit-p3b/20260926_E0-AUTHORIZATION-READINESS-AND-POST-E0-DECISION-TREE.md`).
  - **E0 executed** (G-LOG-0075 authorization → **G-LOG-0076: stopped under §18, NEEDS-REVISION / RUN-INVALID**). The M1 agent ran a Bash command outside the allowlist. An independent second cause: harness recovery texts (the accepted conservative class). Report: `audit-p3b/20260926_V1.2.4-E0-RUN-STOP-REPORT.md`.
  - **O1 decided (G-LOG-0077):** the E0 line is closed and the instruments remain **unvalidated** (E0 terminated before its statistical decision stage). No O2, no O3, no V1.2.5. The O3 hypothesis (chunked M1 matching) is preserved, untested.
- **OB0018 track (next):** readiness done (`audit-p3b/20260926_OB0018-READINESS-REPORT.md`).
  - **Scope:** the brief §D multi-unit equivalence experiment on one control label of production batch OB0018. The batch itself stays PREPARED.
  - **Pending human decisions:** **D-1** run §D with amendments M-a…M-f; **D-2** integrity regime (pilot-style outcome-based, recommended); **D-3** auditor family; **D-4** co-label edge rule (optional).
  - **D-1…D-4 approved (G-LOG-0078).**
  - **Pre-registration:** frozen `6f67392b3`; one independent audit found 8 MATERIAL defects (the design was sound); repaired and re-frozen at **`773a40ffc`** (68 tests). Records: `audit-p3b/20260926_1800_ob0018-decomposition-fidelity-preregistration.md`, `audit-p3b/20260926_OB0018-PREREGISTRATION-AUDIT.md`.
  - **Executed** (G-LOG-0079 → **G-LOG-0080**). **Frozen result: UNDETERMINED.** Level 1 failed on a transcript classification: a U02 `--help` reader call, which read nothing.
  - **Descriptive only:** counterfactual NO-ARM-SHOWN-FAITHFUL. Arm A failed on compliance only; arms B and C failed on two judgment births. New mechanisms observed: pair-context loss; out-of-row birth promotion.
  - **Report:** `audit-p3b/20260926_OB0018-PILOT-RESULT-REPORT.md`.
  - **Pending human decisions:**
    1. P3B-ESC for the range quarantine hits (A, C)?
    2. Follow-up: proceed to the S5 load ruling on the descriptive evidence / re-run unchanged / narrow scan clarification before a re-run.
    3. Adopt mechanisms 5–6 as S5 design requirements?
  - **Decided (G-LOG-0081):** range quarantine = compliance defect (no P3B-ESC); option (i) (no re-run); mechanisms 5–6 + lint = proposed S5 requirements.
  - **S5 Decision Package READY** (`audit-p3b/20260926_S5-DECISION-PACKAGE.md`): D1–D11, analysis only. New measurement: row-first packing cuts row-source adjacencies 253 → 7 at 600 KB (equal unit count).
  - **D4 validation** done; **technical review** done (B′ and C re-specified).
  - **G-LOG-0082: technical decisions + ONE §26 revision AUTHORIZED; S5 EXECUTION NOT AUTHORIZED.**
  - **Revision 5** drafted, implemented and verified behind its gate, NOT activated (`2d12e96cb`; record `audit-p3b/20260926_P3B-S26-REVISION-5-RECORD.md`).
  - **Remaining blockers:**
    1. ~~verifier/runbook integration~~ **DONE (Blocker 1, `20c0edebc`)**;
    2. ONE independent audit;
    3. activation inputs: binary pre-classification run + 12 per-file decisions; audit-sample parameters and seed;
    4. activation (manifest regeneration, plan);
    5. human S5 EXECUTION AUTHORIZATION.
  - **FL-2 population robustness** (≥ 14 labels per 20% bound; stratified by boundaries B, D, S, C) is future work, only if decision-relevant. It remains independent of Decision A.
  - **No further hardening rounds** unless an executed run exposes a material defect (hard stopping rule).

## IMPLEMENTATION CONTINGENT ON DECISION A = B or C (not before)

- **One P3b §26 revision**, with the §26.6 freeze-integrity check. No v3.5 edit, no v1.2 edit.
- **Phase/context mapping** (v3.5 P1–P3 ↔ Phase 1; P5–P7 ↔ Phase 2; **P4 per A2**).
- **Theory-verification ownership** (P3b §1 priorities; H-19 §9F) per A3.
- **Theory Discovery Index** (RA-10): build it, or declare a compliance backlog, which v1.2 itself permits.
- **Published Language:** declare the P3b §24 hand-over as the PL, map it to the v1.2 §4.1 list, and add a per-crossing translation record (RA-3).
- **ACL gates** per A4. v1.2's Q38/Q53/Q54/Q55 are defined only in an unadopted F-lane draft, and P1-Q1 only in the F-lane Phase-1 protocol.
- **Research-State equivalence:** `P3B-STATE.json` ("holds no rules", P3b §24) ↔ v1.2 RA-11.
- **Epistemic-status mapping:** RA-15 ↔ v3.5 R15 / P3b §1B.
- **Normative glossary**, an **S/F isolation clause** (architecture only; no F-lane protocols, gates or evidence), and the **F-04…F-07 dispositions**.

**If Decision A = A or DEFER:** do **not** modify v3.5 or P3b to resemble v1.2. Record the decision (for A) and the terminology clarification only. Optionally add notes on F-10 (P3b v1.7 header still says "not frozen") and F-11 (P3b §7 cites "v1.1").

## EXECUTION PATH (two independent branches)

- **Branch B:** Decision B → V1.2.1 execution (per its pre-registration's next-step list) → **instrument reliability analysis** (segment coverage, quote verification, κ, capture, run integrity).
  - **V1.2.1 does not test whether the methodology improves reconstruction.** That claim needs a separate pre-registered experiment.
- **Branch A:** Decision A → governance action if required (the contingent list above).

## RESEARCH ROADMAP E0–E11 (each stage separately pre-registered and authorized; none executed)

**Every stage states which uncertainty it reduces or which cost it lowers (the governing principle).**

| Stage | What it is | Uncertainty reduced / cost lowered | Gate to enter |
|---|---|---|---|
| **E0** | V1.2.4 instrument run (PX0109), 6 files, 8 runs | **uncertainty:** are the segment inventory and blinded matching reliable enough as Phase-1 instruments? (coverage, quote fidelity, κ, relative capture) | human authorization |
| **E1** | reproducibility: r ≥ 3 repeats per extractor, same inputs | **uncertainty:** extractor variance; interval widths for E0 metrics | E0 valid |
| **E2** | multi-source capture–recapture (k ≥ 3 diverse sources, including a human subsample) | **uncertainty:** extraction completeness, as a lower bound with an interval | E1 |
| **E3** | human gold set for semantic equivalence (≥ 2 adjudicators) | **uncertainty:** matcher precision and recall (not only κ) | E0 |
| **E4** | timed human-adjudication baseline | **cost:** minutes per item, detection rate (via error injection) | E3 |
| **E5** | ML diagnostics, read-only (near-duplicates, omission flags, calibrated uncertainty) | **cost:** candidate review load | E3 gold available |
| **E6** | randomized review-queue trial (uncertainty-ordered vs random) | **cost:** errors found per review minute | E4, E5 |
| **E7** | relation-classifier proposals vs adjudication | **cost:** relation typing; confusion matrix | E3 |
| **E8** | F-14 formalized (A7) → optional v2 augmentation-strategy pilot | **uncertainty:** does augmentation improve reconstruction? | human A7 |
| **E9** | **return to S5:** load handling → OB0018 → S5 authorization → the 396-batch floor | **production** (not an experiment) | human acts |
| **E10** | S5a/S5b → P3b terminal → `30-RECONCILIATION.md` → P4 | production | E9 |
| **E11** | P5–P7 theory recovery → pre-registered attack → governance GATE | the final research goal | E10 |

**Correction (2026-09-26, E0 readiness report §4):**
- E0 is **not** a gate on S5, and a valid E0 does **not** open S5 (pre-registration §22; F-13).
- E0 informs exactly one S5 prerequisite, **load handling** (whether the instruments are proposed for P3b adoption via §26).
- E1–E8 are **triggered** by empirical results under the **decision-relevance test**: run Eₖ only if some outcome could change a pending decision.

**Rules:**
- E1–E8 are **augmentation only**. They may make E9 cheaper or more reliable, but never replace it (R19).
- The critical path to the research goal is **E0 → E9 → E10 → E11**. E1–E8 are pursued only where they pay for themselves under the governing principle.

## FUTURE RESEARCH

- **F-14 clarification (A7)** must be formalized **before any v2 strategy-comparison pilot**:
  - **Allowed augmentation (Discovery-Augmented Reconstruction):** option C, and D-all (every label still floored). Discovery channels, registers, targeted search, correction requests, hypotheses.
  - **Prohibited substitution under R19:** option B, and D-selected (only some labels floored).
  - **"Research-first" is not a synonym for substitution.** P3b's frozen research-first principle (D-27) is legitimate augmentation around the floor.
  - **Triggers may decide what is additionally investigated or read, never what the mandatory floor skips.**
  - Research outputs never become reconstruction evidence automatically (P3b §1B, G-12).
- **v2 pilot:** a separate pre-registered experiment, and only if still justified after F-14. **Do not start it now.**
- **Discovery-Augmented Reconstruction, empirical evaluation**, within existing governance (P3b §1, §9B, §13, §9E; v3.5 A11, R10). **No second architecture.**
- **Theory Discovery Index:** under A/DEFER, test whether it is needed and useful. Under B/C it is binding (backlog); the questions become usefulness and timing.
- **Do not promote V1.2.1's experimental taxonomy into P3b** automatically. Adoption requires a §26 revision that maps it to v3.5 B2.

## PRODUCTION RECONSTRUCTION: the return-to-S5 path (not started; nothing here is authorized)

> **No research experiment can substitute for the S5 reconstruction floor under R19** ("Never execute Phase N+1 as a substitute for an unfinished Phase N").

**Dependency chain (preparation only):**
1. **Heavy-label/load handling:** labels exceeding one context. The decomposition pilot (G-LOG-0053) showed feasibility with four documented cross-unit losses.
2. **OB0018 decomposition-fidelity track:** a separate authorization and execution. Not executed.
3. **S5 execution authorization:** a human act, with the §18 gate and the plan/contract already in place (S5 plan v2.3.2; contract revision 3).
4. **The 396-batch per-label reconstruction floor** (v3.5 A11 roll-up via P3b §9.8), covering 1,975 labels.
5. **The 66 hub labels** (`P3B-S5-HUBS.jsonl`) stay **ESCALATED (`LOAD`)** until the required evidence exists. Claims about absences are scoped per P3b §23 3b.
6. **H-02 handling** for Tier X (`PROVISIONAL-HELD`) labels.
7. **S5a, S5b and P3b terminal** (§23) → `30-RECONCILIATION.md` → **P4** (v3.5 A12).
8. **P5 → P6 → P7 → GATE**, per v3.5 A12–A13 ("RECONSTRUCTED, PENDING GOVERNANCE REVIEW", never FINAL).

**Standing states:** H-19 **SEALED**; S5c **PROHIBITED** (P3B-ESC-0001); S5 production **frozen**.

---

## HUMAN DECISION SHEET (unranked; not decided)

**Decision A: should the S-Series have a declared architecture above v3.5 + P3b?**
- [ ] DEFER: no change; current governance stays in force; F-01 stays open.
- [ ] A: keep v3.5 + P3b, by record.
- [ ] B: adopt Research Architecture v1.2.
- [ ] C: controlled adoption (v1.2 = boundaries and invariants; v3.5 = procedure; P3b = annex).

**If B or C:**
- **A2:** where does v3.5 **P4** map? Phase-1 context / Phase-2 context / boundary.
- **A3:** where does P3b's **theory-verification** activity belong? Remains in P3b / Phase-2 context (early under RA-13).
- **A4:** **ACL gates:** v1.2 gates directly (F-lane-defined) / S-Series equivalents.

**Other decisions:**
- **A5:** approve the terminology clarification (F-02/F-03; path/version citation plus a glossary; no rename).
- **A6:** untracked v1.2 copy (`docs/knowledgeos/chronological-read/prompts/KNOWLEDGEOS-RESEARCH-ARCHITECTURE.md`): keep temporarily / remove / move / commit after the decision.
- **A7:** formalize the augmentation/substitution boundary (C and D-all allowed; B and D-selected prohibited under R19).

**Decision B (separate from Decision A):**
- [ ] Authorize V1.2.1 execution under pre-registration `51e04f8cb621cee1b8083245d8068418ac80b6c5`, with the four F-13 statements (checklist item 12).

---

## V1.2.4 AUTHORIZATION CHECKLIST (for the human act; do not create the authorization file)

1. **Commit:** `c9cfd8f568a250a06563fe9b8c3b6496b4d3ac18`.
2. **Pre-registration sha256:** `4d899d867dc762205a7e5eab74ab9d1f534730c772e984416f1010aa33e19650`.
3. **Namespace:** PX0109 / `pilot-s5-decomp/v1-r3/` / `V1.2.4-AUTHORIZATION.json`.
4. **Prompts:** committed (`333b9a79e`); `PROMPT-CHECK.json` sha `1f62f654…`; gate: 101 commands, 0 violations.
5. **Tests:** V1.2.4 110 · V1.2.3 96 · V1.2.2 80 · V1.2.1 67 · V1.2 51 · V1.1 45 · V1 16, all OK.
6. **Prior runs:** PX0106 was RUN-INVALID; PX0107 and PX0108 were prepared only. PX0109 is independent evidence.
7. **The four F-13 statements** (instrument-validation only; experimental taxonomy; no v3.5/P3b obligation changes; adoption only via a P3b §26 revision).
8. **A-2 accepted:** MATERIAL-UNTESTED / CONSERVATIVE-RUN-INVALID. An unobserved message form invalidates the run; it never passes.
9. **Documented conservative false-FAIL exposure** (harness recovery texts, non-dispatch attachments) acknowledged.
10. **Residual trust assumptions** (as in G-LOG-0067 §6) acknowledged.
11. **Standing states:** H-19 SEALED; S5c PROHIBITED; S5 frozen.
12. **Independence:** authorization neither requires nor implies Decision A.

*(The superseded V1.2.1 checklist is kept in git history, at `2509dd7e9` and later.)*
