# S-Series Revision 7: plan (design repair → design freeze → implementation → verification)

| | |
|---|---|
| **Status** | **FROZEN (G-LOG-0088, v2.3 `cdbf53cb…`) · IMPLEMENTED (`ef9bdf7ba`) · ENGINEERING-VERIFIED · NOT ACTIVATED · NOT INDEPENDENTLY AUDITED.** Open: NDB-1, DC-1, DC-2 (implementation record §6) |
| **Authority** | G-LOG-0085 (HD-1…HD-9; Model D adopted) · G-LOG-0086 (PF-1…PF-8 accepted; one design-repair slice) |
| **Contract** | `docs/knowledgeos/chronological-read/prompts/20260926_2400_p3b-agent-contract-r7-addendum.md`: **candidate v2 (design-repaired)**; v1 sha `4f31d393…` at `c453b1b2b` |
| **ADR** | `docs/knowledgeos/chronological-read/audit-p3b/20260926_ADR-R7-EXECUTION-TRUST-ROOT.md` (ADR-R7-01, accepted by HD-2; updated for W7a, W8, PF-9/11/12) |
| **Review / repair records** | `audit-p3b/20260926_R7-PREFREEZE-DESIGN-REVIEW.md` · `audit-p3b/20260926_R7-DESIGN-REPAIR-IMPLEMENTATION-RECORD.md` |

## Objective

Make "PASS = faithfully observed ∧ completely typed ∧ witnessed ∧ mathematically valid ∧ internally consistent" mechanically defensible within the declared trust boundary. The mechanism: Batch PASS = UniverseValid ∧ WitnessValid ∧ EvidenceValid ∧ ReconstructionValid, and programme acceptance adds StatisticsValid. The verifier composes predicates and holds no rule of its own.

## Background

- R6 → re-audit NEEDS-REVISION → RC-1/2/3 → Model-D readiness READY (conditional) → HD-1…HD-9.
- The R7 v1 design → pre-freeze review (8 MATERIAL) → accepted as design-repair input → R7 v2 (design-repaired).
- The review and the repair were authored by the same agent as the design, so an independent R7 audit is still required (HD-9).

## Scope

**In scope (after the freeze):** the R7 bounded contexts below, the reader changes, the fresh adversarial suite, the implementation record and the developer guide.

**Out of scope:** R6 (immutable), F-Series, application code, the production ledger, P3B-STATE, the manifest, the Master Protocol, the corpus, H-19, binary pre-classification, S5, S5c, ML.

## Implementation boundaries: one module per bounded context (PF-13, DR-13)

| # | Context / boundary | Module (new unless noted) | Depends on |
|---|---|---|---|
| B0 | neutral transcript syntax decoder (PF-9): records, blocks, tool_use/tool_result pairs, `uuid`/`parentUuid`, timestamps, `meta.json`, queue notifications, persisted outputs. **S5-agnostic**; no dependency on `p3b_v1_2_4_instrument` | `scripts/p3b_transcript_syntax.py` | — |
| B1 | **Universe:** plan derivation and hash; namespace totality and legacy digest; revision and contract binding; closed schema S (validation B); discovery (A); the typing registry (F1) and consumer read sets (F2), loaded from the addendum's tables; W8 allowed capabilities; permitted write paths | `scripts/p3b_s5_r7_universe.py` | — |
| B2 | **Witness:** dispatch binding; W1 decision table; W2–W5, W7, W7a, W8 evaluation; derived stages; `WITNESS.jsonl` / `WITNESS-DIGESTS.json`; freeze | `scripts/p3b_s5_r7_witness.py` | B0, B1 |
| B3 | **Evidence:** coverage from the witness; reading state; byte-exact anchoring with N-WS offset map and cross-page contiguity; claim → fact resolution; W6 reconciliation of READ-LOG, records, facts and claim-evidence | `scripts/p3b_s5_r7_evidence.py` | B1, B2 |
| B4 | **Reconstruction:** summary relations (membership, inheritance, one-directional constraints, lateness gate); contradiction relation + computed precedence (A.10 dated positions only); lifecycle and derived position; S1–S3 (canonical `SID_RANGE`); S2; EMPTY; edges | `scripts/p3b_s5_r7_reconstruction.py` | B1, B3 |
| B5 | **Statistics:** `estimate_v7` (REJECT / NOT-ESTIMABLE / Estimate), domain D, frozen-record anchor, realized n_h; exact-enumeration verification | `scripts/p3b_s5_r7_stats.py` | B1 |
| B6 | **Verifier:** composition only (the conjunction of context predicates; failure tags); the gate edit routing revision 7 | `scripts/p3b_s5_r7_verify.py`, `scripts/p3b_s5_verify.py` (gate edit) | B1–B4 |
| B7 | **Reader:** R7 grammar; default-deny for activated batches; plan path and hash; self-describing header with the reader-generated `inv` | `scripts/p3b_read_source.py` (edit), `scripts/p3b_s5_common.py` (allowlist) | B1 (plan check) |
| B8 | **Tests:** T01–T76 + the re-audit's MATERIAL reproductions in R7 form + combinations; fresh fixtures, including a synthetic transcript generator in the harness format observed by the readiness experiment | `scripts/tests/test_p3b_s5_r7_*.py`, `scripts/tests/test_p3b_transcript_syntax.py` | — |
| B9 | implementation record; developer guide; TODO; session log | `audit-p3b/…_REVISION-7-IMPLEMENTATION-RECORD.md`, `developer_guide/…` | — |

The dependency graph is acyclic (§0 of the addendum; checked statically).

## Task checklist

- [x] Human decisions recorded (G-LOG-0085)
- [x] R7 v1 design package (`c453b1b2b`)
- [x] Pre-freeze design review (8 MATERIAL)
- [x] PF-1…PF-8 accepted; design-repair slice authorized (G-LOG-0086)
- [x] **R7 v2 design-repaired:** addendum, ADR, plan; static consistency checks 32/32 PASS; test matrix T01–T76
- [x] **HUMAN DESIGN FREEZE** (G-LOG-0088; `05569322d`)
- [x] B0–B7 implementation (B0, B2–B7 RED-first; B1 tests written after the module, disclosed)
- [x] B8: 9 new suites all green; full suite 38 files / 1,022 tests, 1 expected failure (DC-2); `prepare --check` IDENTICAL; H-19 0 violations; baselines unchanged
- [x] B9: developer guide `developer_guide/knowledgeos/s5_r7/`; record `audit-p3b/20260927_REVISION-7-IMPLEMENTATION-RECORD.md`
- [x] v2.4 decision package (2026-09-27): semantic impact (no conflict; INV-LEX), proposal (4 META rows; 241 → 0 typing failures; claims identical), non-interference tests (23 OK), DC-2 diff prepared (not applied), Read integrity CHARACTERIZED (+ RI-1). Decision note `audit-p3b/20260927_R7-v2.4-DECISION-NOTE.md`
- [x] G-LOG-0089: v2.4 approved; DC-2 approved; RI-1 deferred. v2.4 implemented (addendum `fa837177…`; Universe only) + engineering-verified; audit authorization package written
- [ ] HUMAN: authorize the ONE independent R7 audit (HD-9)
- [ ] STOP → human: authorize ONE independent R7 audit, or request design/repair

## Progress

2026-09-27: design freeze (G-LOG-0088); implementation B0–B9 (`ef9bdf7ba`); engineering verification; NDB-1/DC-1/DC-2 reported; stopped before the audit.

2026-09-26:
- decisions (G-LOG-0085);
- v1 package;
- pre-freeze review;
- G-LOG-0086;
- v2 design repair (DR-01…DR-14);
- stopped for the design freeze.

## Risks

| Risk | Mitigation |
|---|---|
| Harness transcript format drift | the decoder and extractor are versioned by sha; an unknown structure is a FAIL |
| Archive loss (`~/.claude/projects` cleanup) | archive at the unit-validation freeze (FD-4′) |
| **W8 strictness vs agent behaviour** (the readiness experiment showed exploratory `ls`, reading the wrapper, `mkdir`) | strict dispatch prompts; pre-created run directories; expect FAILED units in early batches. This is a runbook and operations matter, never a relaxation of W8 |
| Harness classifier denials; agent refusals | witnessed; the unit is FAILED; a new run needs a human act |
| `later_*` lateness tightening (FD-1′c) could reject agent outputs valid under rev3 | an explicit human decision at the freeze |
| EXTENDS placement (FD-1′b) is a semantic interpretation | an explicit human decision; no default |
| The self-authored design chain (v1, review, v2) | ONE independent R7 audit before acceptance (HD-9) |

## Open questions

- FD-1′a/b/c, FD-2′…FD-8 (addendum §12).
- The FD-4′ archive location.

## Next actions

1. The human design freeze.
2. Then B0 → B7, RED → GREEN per context; B8; B9; STOP.
