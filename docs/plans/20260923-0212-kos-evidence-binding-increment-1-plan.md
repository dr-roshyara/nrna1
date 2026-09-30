# Plan — KOS Evidence Binding, Increment 1 (1a instrument trust · 1b evidence binding)

| | |
|---|---|
| **Status** | ⚠️ **AWAITING HUMAN APPROVAL (EP-01).** Nothing in this plan has been implemented |
| **Date** | 2026-09-23 02:12 |
| **Scope** | only the KnowledgeOS theory-extraction programme (`docs/knowledgeos/knowledgeos_theory_chronological_extraction/`) |
| **Architecture it implements** | `architecture/research-control-architecture.md` (PROPOSED), increments 1a and 1b · invariants RCI-001..006 and RCI-014 |
| **Preceded by** | `architecture/INCREMENT-0-ROOT-CAUSE-CLOSURE.md` (analysis, done) · independent audit `docs/knowledgeos/governance/audits/2026-09-23-…` · research forensic report `ID-ROOTCAUSE-01.md` |

---

## Objective

Make the governance **instrument** trustworthy (1a). Then **bind every read to canonical evidence** so that the F0031–F0040 failure class becomes detectable at the moment of reading (1b).

**Explicitly not an objective:** repairing the research, correcting IDs, re-reading files, changing theory v0.9, or resuming F0041+.

## Background: Engineering Readiness (EP-03), derived from the repository

| Question | Answer |
|---|---|
| **Business need** | a reconstruction whose value is traceable provenance currently has 105 references in 10 artifacts that resolve to the wrong files, and a gate suite that reports CLEAR over them (audit §6) |
| **Owning context** | the temporary research governance mechanism, `governance/` (`governance/README.md`: research-scoped, discard by default). **No platform context is affected**, and nothing becomes a registry asset |
| **Architecture decision** | the RCA proposal (§§3–7, 11). ⚠️ Still PROPOSED. This plan implements only parts that add **no research rule** (MECHANISM / NO in RCA §3) |
| **Canonical discovery** | reuse `gate-runner.py`, `gates.yaml`, `gate-schema.yaml`, the fixtures, `governance-preflight.sh` and the P2P §0.3 outcomes. **No second runner, and no second gate namespace** |
| **TDD** | fixtures first. Every new or changed check gets a **violation fixture that FAILs today** (RED), then the implementation (GREEN) |
| **Impact** | `governance/` code and fixtures, plus new files in `governance/` and `architecture/`. **Zero research artifacts** |
| **Verification** | `gate-runner.py --self-test` at 100%; a historical back-test (the current registry must FAIL RCI-002); independent review by another session |

## Scope

### 1a · Instrument trust (RCI-014)

| # | Task | RED (must fail before the change) | GREEN |
|---|---|---|---|
| 1a-1 | **A missing input is never PASS.** `_jsonl()` distinguishes *missing* from *empty*. Checks return `PASS / FAIL / INCONCLUSIVE` instead of a bool | a fixture with `FILE-REGISTRY.jsonl` and `THEORY-DISCOVERY-INDEX.jsonl` absent: KOS-G-010 must be INCONCLUSIVE (it is **0/0 PASS** today) | tri-state results; `0 items examined ⇒ INCONCLUSIVE` |
| 1a-2 | Same for the seed and theory inputs of KOS-G-022/023/024 | seed absent ⇒ KOS-G-022 must be INCONCLUSIVE (**0/0 PASS** today) | as above |
| 1a-3 | **INCONCLUSIVE on an activated gate blocks** (P2P §3A.6: "never silently treated as a pass") | an activated gate with a missing input ⇒ preflight exit 2 | runner status mapping; preflight exit codes unchanged |
| 1a-4 | **A failing self-test makes every verdict INCONCLUSIVE(INSTRUMENT)** | the self-test is 30/31 today | the runner runs the self-test first; with any mismeasure, status is `GOVERNANCE_INOPERATIVE` |
| 1a-5 | Fix the `theory_doc_exists` MISMEASURED case, **after determining whether the check or the fixture is wrong**, and record which | self-test 30/31 | 31/31 |
| 1a-6 | Add a `scope_class` field (`INTERNAL_CONSISTENCY` / `CORPUS_CORRESPONDENCE`) to `gate-schema.yaml`, and set it on every existing gate | schema validation fails on a gate without it | metadata only; **no status or activation change** |

### 1b · Evidence binding (RCI-001..006)

| # | Task | Notes |
|---|---|---|
| 1b-1 | **`governance/build-manifest.py`**: reads `docs/knowledgeos/list_of_files_to_read.log` and writes `governance/canonical-manifest.json` = `{file_id, path, sha256, bytes, present}` plus a manifest hash and the source commit | deterministic, so anyone can regenerate it; a file missing on disk is recorded `present:false`, not dropped. ⛔ **The baseline takes effect only after human approval (RC-H-02)** |
| 1b-2 | **`governance/source-admission.py <file_id>…`**: resolves each ID in the manifest, verifies the file exists and its sha256 matches, prints the path the agent may read, and appends a **read receipt** | refuses an unknown ID or a bare path (`MISSING_CANONICAL_SOURCE`), and refuses on a hash mismatch (`HASH_MISMATCH`). It is **not** part of the gate suite (RC-H-01 recommended resolution) |
| 1b-3 | New runner checks, registered in `gates.yaml` as **`status: proposed`** (the human activates), `scope_class: CORPUS_CORRESPONDENCE`: | IDs are allocated at registration, from free `KOS-G-0nn` ranges |
| | · `registry-path-equals-canonical` (RCI-002) | **the historical back-test must FAIL on today's registry (9 rows)** |
| | · `registry-file-id-unique` (RCI-001) | KOS-G-002 does not cover the registry |
| | · `corpus-hash-unchanged` for registered files (RCI-003) | against the approved manifest |
| | · `receipt-covers-registered` (RCI-005) | **INCONCLUSIVE for legacy rows** (no receipts existed); never FAIL retroactively |
| | · `declared-window-complete` (RCI-006): no canonical ID inside a window claimed as processed is unread | must FAIL on F0031–F0040 (4 unread) |
| | · `state-next-target-resolves` (RCI-006) | **INCONCLUSIVE until the state carries machine-readable `next_targets` / `research_traversal` fields.** Adding those fields is a **research-unit change, not governance** (see open questions) |
| 1b-4 | Fixtures for every 1b check: pass, fail, missing-input, plus the historical back-test | fixtures first (RED) |
| 1b-5 | Documentation: the `governance/README.md` catalogue section; a developer guide `developer_guide/knowledgeos_research_governance/` (00_index + one file per step, per the DoD); the coverage matrix updated from `PROSE_ONLY` to `MEASURED` | — |
| 1b-6 | **Independent review** of 1a+1b by a separate session: the card-vs-implementation check and the fixtures | the reviewer must not be the implementer |

### Out of scope, explicitly

- Activating any gate: a human act in `governance-state.yaml`.
- Wiring the preflight or source admission into `settings.json` hooks.
- CI, CODEOWNERS, branch protection.
- The F0031–F0040 correction (RC-H-04).
- Re-reading the 4 unread canonical files.
- Changing theory v0.9 or any research artifact.
- F0041+.
- Increments 2 and 3.
- Changing P1P, P2P or ARCH.

## Design decisions

| # | Decision | Reason |
|---|---|---|
| D1 | Implemented by the **governance session**, never the research session | RCI-011: the governed party does not build or repair its own controls. Today `governance/` was written by the research session (`governance/README.md` §5 says so); this plan does not repeat that |
| D2 | New checks register as `proposed`, never `active`. Activation stays in `governance-state.yaml` | activation is an act, not an edit (`governance/README.md` §4) |
| D3 | Source admission sits **outside** the gate suite | keeps v3.2's two-boundary gate rule intact (RCA §6) |
| D4 | Legacy data yields **INCONCLUSIVE**, not FAIL, where the control did not exist at the time (receipts). It yields **FAIL** where the rule did exist (P1P §4 ⇒ `registry-path-equals-canonical`) | never convict retroactively under a new mechanism; never excuse a breach of an existing rule |
| D5 | Manifest = canonical list + hashes only. **No completeness claim** over `docs/knowledgeos/` (3,081 listed vs 9,163 md files) | a separate, open L0 question |
| D6 | No change to verdict vocabulary beyond adding `INCONCLUSIVE` to check results | the runner already has PASS/FAIL/REVIEW_REQUIRED/NOT_ACTIVATED/ERROR; `INCONCLUSIVE` comes from P2P §3A.6, and nothing is invented |

## Task checklist

- [ ] **RC-H-01, RC-H-02, RC-H-03, RC-H-07 decided by the human** (see Open questions)
- [ ] Research session told not to modify `governance/` while this runs (coordination; RCI-011)
- [ ] 1a-1 … 1a-6: RED fixtures committed, then GREEN, self-test 100%
- [ ] 1b-1 manifest builder + generated manifest (unapproved until RC-H-02)
- [ ] 1b-2 source admission + receipts
- [ ] 1b-3/1b-4 checks + fixtures + back-test (must FAIL on today's registry)
- [ ] 1b-5 documentation + developer guide + coverage-matrix update
- [ ] 1b-6 independent review
- [ ] Commit per step, labelled with no story ID (research governance; say so explicitly)

## Progress

- 2026-09-23 02:12: plan written. Increment 0 complete (analysis only). Nothing implemented.

## Risks

| Risk | Mitigation |
|---|---|
| **Concurrent edits** to `governance/` by the research session, which built it | explicit human instruction before starting; the runner's integrity digests show any unrecorded change |
| Activating `registry-path-equals-canonical` would **BLOCK the research immediately** (9 rows fail) | intended, but it is the human's choice (activation). The check lands as `proposed` |
| Fail-closed semantics could make today's CLEAR runs become BLOCK/INOPERATIVE | yes. That is the finding (E-3), not a regression. Announce it before merging |
| Receipts are written by a script the agent runs, so they are tamper-evident, not tamper-proof | stated in RCA §5; real enforcement needs git/CI (increment 3) |
| Hashing 3,081 files at every run is slow | hash only registered/admitted files per run; build the full manifest once per baseline |
| The mechanism grows beyond "temporary" | everything stays under `governance/`; the README's discard-by-default rule applies |

## Open questions (for the human)

1. **RC-H-01**: confirm the recommended split (per-source admission control plus boundary gates), or keep boundary-only.
2. **RC-H-02**: approve generating the canonical manifest, and name its baseline commit.
3. **RC-H-03**: adopt fail-closed semantics, knowing that CLEAR may turn into BLOCK/INOPERATIVE on today's data.
4. **RC-H-07**: where `research_agent_contract.md` lives (`architecture/` or `prompts/`).
5. **Receipts location**: `governance/admission/READ-RECEIPTS.jsonl` (written by the script) is proposed.
6. **State fields**: should a research unit add machine-readable `next_targets` / `research_traversal` to `KNOWLEDGEOS-RESEARCH-STATE.md`? Until then `state-next-target-resolves` stays INCONCLUSIVE.

## Next actions

1. The human answers the open questions and approves or rejects this plan.
2. On approval: 1a first, and 1b only after the self-test is at 100%.
3. After 1b: an independent review, then the human decides activation.
4. Only then: the F0031–F0040 correction unit (RC-H-04), then re-reading the unread canonical files, then F0041+.

---

*Traceability:*
- Implements RCA increments 1a/1b.
- Consumes `governance/{gate-runner.py (\_jsonl l.44–56, c_index_coverage l.126–132, \_seed_and_traced l.176–184), gates.yaml, gate-schema.yaml, README.md}` and P2P §0.3/§3A.6.
- Evidence: RCA §0 (E-1..E-8), `INCREMENT-0-ROOT-CAUSE-CLOSURE.md`.

---

## Progress — extended Increment 1a (2026-09-23, appended; the plan above stands as written)

**Authorization:** `L0-DEC-13` approved **extended 1a = IC-1…IC-8** (`governance/GIA-DECISION-PACKAGE-01.md` §7). This supersedes tasks 1a-1…1a-5 above, which are its subset. Related decisions: `L0-DEC-14` (IC-8), `L0-DEC-15` (governance session only), `L0-DEC-16` (pin), `L0-DEC-17` (canonical list for KOS-G-003).

| Step | State | Evidence |
|---|---|---|
| 1a.1 baseline | ✅ no drift at `9abd4ad95` (runner `fcafa9cd27976ff2` · gates `7a01bac1a45ef634` · state `e20b4d67ab71caf8` · schema `4ebde2c923ede3a6` · door `4b53c0b30e0851a8`) | recomputed sha256 |
| 1a.2 regression fixtures first | ✅ `4746093a8`: 60 cases with expected post-correction verdicts, plus the IC-7 case fixtures. **RED: 26/60** against the unchanged runner | `governance/regression/run_regression.py --control rev --rev HEAD` |
| 1a.3 implement IC-1…IC-8 + GIA-4 + GIA-5 | ✅ runner, `gates.yaml` (KOS-G-001/003 declared scope), door | the 1a.3 commit |
| 1a.4 self-test | ✅ **55/55** (31 shared + 24 IC-7 cases). The former 30/31 was a **fixture defect**: `_per_check/theory_doc_exists/` was an untracked empty directory, absent in every clone. **The check was right** | `--self-test` |
| 1a.5 all 54 valid cases (+6 new) | ✅ **60/60**. ⚠️ **Stated per 1a.5:** C1 (swapped registry paths) is still **CLEAR**, since that is 1b's job. B12–B14, B17, B18, B21, E1–E4, F1 and F2 are also still CLEAR: no activated gate covers them, and the regression file records why | `run_regression.py` |
| 1a.6 independent review | ⏳ **NEXT**: a separate governance session. **The implementer does not accept** | — |
| 1a.7 L0 acceptance | ⏳ | — |

**Decisions made inside the authorized scope (governance, recorded for the 1a.6 reviewer):**
- **IC-5, KOS-G-003:** implemented the *narrow-the-claim* branch that IC-5 offers. Its scope is now every `.jsonl` recursively (same set as KOS-G-001), excluding top-level `governance/`. **Markdown is out of scope and declared so** (B17 stays CLEAR by design).
- **IC-1:** `theory_doc_exists` keeps FAIL on an absent document, because absence *is* its violation. Every other check reports INCONCLUSIVE on a missing required input.
- **Pins** are quoted strings, because YAML reads an unquoted all-digit hex pin as an integer. A non-string pin is a mismatch (fail closed).
- **Any runner crash** exits 3 (INOPERATIVE), not 1 (BLOCK). The door maps 1 → 2 (BLOCK) and anything else non-zero → 3.
- **Not done (outside IC-1…IC-8):** 1a-6 `scope_class`; the B21 malformed seed id; appendix-marker absence stays FAIL.

**Residuals for the reviewer (not fixed; outside 1a):**
- (R-1) a pin detects definition drift but **does not authenticate** the activator;
- (R-2) runner code changes are **not pinned** (only the self-test guards them);
- (R-3) the IC-7 near-miss fixtures cover five activated checks, not all 14.

**Consequence at landing:** the committed activations are **bare**, so the live run reports **`GOVERNANCE_INOPERATIVE` (exit 3)** until the human re-activates with pins (`--pin-lines`). **Intended (L0-DEC-16 note).** Research stays **BLOCKED** until 1a.7.

### Progress — 1a.6 review and correction slice (2026-09-23, appended)
- **1a.6 review** `f21b3e6e3`: ACCEPT_WITH_FINDINGS for both parts, not rewritten. **L0-DEC-21** holds IR-G1 blocking and authorizes the slice IR-G1/G2/G4/A1/A2. **L0-DEC-20**: batches 2/3 are recorded as pre-acceptance, not certified.
- RED `348d322f4`: gate R1–R6 2/7; admit V10/V11 9/11 (IR-A2 was a test-side fix, closed at that commit). GREEN: self-test 55/55, gate battery **66/66**, admit **11/11** at HEAD; pre-1a / pre-repair controls still RED (26/60, 1/11).
- **Next:** a fresh independent verification of the slice, then L0 1a.7 (D-1/D-2), then pins, then a fresh live run and the batch-4 release question under L0-DEC-19.

### Progress — 1a.6 and 1a.7 closed (2026-09-23, appended)
- Fresh verification VERIFICATION-03 (`cb1c6f847`, L0-DEC-28): ACCEPT_WITH_FINDINGS. **1a.7: ACCEPTED (L0-DEC-29).** Extended 1a is complete.
- Next: the human installs the pins → Research Release Check (L0-DEC-27) → L0 release. **1b is not started and not authorized.**
