# Revision 6: implementation record (repair slice under G-LOG-0083)

| | |
|---|---|
| **Kind** | Engineering evidence. ⚠ authority: generated. **Engineering supplies evidence and never accepts its own work** (EP-02, R-34) |
| **Authority** | G-LOG-0083: human authorization of ONE bounded Revision-6 repair slice closing MATERIAL R5-01…R5-08 |
| **Input** | the ONE independent audit, `audit-p3b/20260926_REVISION-5-INDEPENDENT-AUDIT.md` (NEEDS-REVISION), and its scripts in `audit-p3b/r5-audit/`. **Both are immutable and unmodified** |
| **Causal analysis** | `audit-p3b/20260926_REVISION-6-CAUSAL-ANALYSIS.md` (`92428e944`), committed **before** any repair code |
| **Contract** | `prompts/20260926_2300_p3b-agent-contract-r6-addendum.md` (revision-6 addendum, with the frozen statistical specification and the ML boundary) |
| **State** | revision 6 **IMPLEMENTED BEHIND ITS GATE, NOT ACTIVATED** · revision 5 **WITHDRAWN** · manifest contract revision **3** · S5 execution **NOT AUTHORIZED** · H-19 **SEALED** · S5c **PROHIBITED** · floor **0 / 396** |

## 1. Scope

**Performed:**
- rule and verification code for revision 6;
- the gate in the production verifier;
- the reader's run grammar and plan check;
- the allowlist pattern;
- permanent regressions;
- the contract addendum;
- this record.

**Not performed:**
- activation;
- manifest regeneration;
- binary pre-classification;
- S5 dispatch;
- any production corpus read;
- H-19 access;
- S5c;
- ML;
- a second audit.

**Not modified:**
- Master Protocol v3.5;
- the P3b frozen architecture;
- the rev3 contract;
- the H-19 seal;
- Decision A;
- the S5 authorization state;
- the production ledger;
- the corpus;
- F-Series material;
- application code.

## 2. Findings addressed

**Core invariant:** PASS ⇒ P ∧ E ∧ R ∧ T ∧ L ∧ S. Every state is derived by the verifier; declarations are assertions checked against the derivation.

| Finding | Root cause (causal analysis) | Revision-6 repair (where) | Permanent regression(s) |
|---|---|---|---|
| **R5-01** provenance | records carried no facts; claims were never enumerated by the verifier | fact schema with byte-verified quotes; the verifier's own claim enumeration; `claim-evidence.json` resolution to a fact of the right kind in a reading run of the label (`r6.fact_violations`, `enumerate_claims`, `claim_evidence_violations`) | FP-1, J1, FP-6, `claim_cannot_cite_fact_of_wrong_kind_or_unknown_fact`, `fact_quote_must_be_in_bytes` |
| **R5-02** temporal | lexicographic string comparison of declared times | strict RFC 3339 → aware UTC; reads from the reader-written logs; stages from `R6-PROVENANCE.jsonl`; record files bound by hash (`parse_utc`, `temporal_violations`) | C1, C2, C3, C4, `record_changed_after_validation`, `strict_time_parser` |
| **R5-03** execution | one batch log; run ids trusted; no synthesis prohibition at log level | per-run logs; planned run set from the derived plan; undeclared and `-R5` directories fail; synthesis zero-read; refused entries fail; the reader refuses unplanned run/file pairs and synthesis reads (`verify_r6` E section; `reader_plan_check`) | E1, E2, E3, E4, FP-4, `withdrawn_revision_5_run_directory`, `reader_plan_check`, `scan_all_reader_calls` |
| **R5-04** label ownership | coverage grouped by the agent-supplied `working_label` | run → owner from the plan (`L##` = the label's position); coverage per owning run only | D2 (both forms), E6 |
| **R5-05** plan integrity | the plan was an input, not a derivation | `plan_derive` from label order, hash-verified slices, resolver bytes and 02-FILES; the frozen plan must equal it; its sha256 must equal the manifest entry's `r6_plan_sha256` | B1, B1b, B2, B3 |
| **R5-06** reading rule | the rule checked only `CHANGES-*` classes | default-deny: every class outside {FIRST, RESTATES, EXTENDS, NOT-COMPARABLE} needs a WHOLE-FILE source; WHOLE-FILE ⇔ complete byte coverage in the owning run | D1 (both), `whole_file_without_coverage` |
| **R5-07** S1 quote | the quote was only tested for being present | YES/NO quotes are verified in the file's bytes; exact pair tokens; NO needs a register record and an OTHER escalation naming the exact pair | F1, FP-5, F3, F4, `exact_tokens_unit` |
| **R5-08** EMPTY | EMPTY was treated as an exemption | EMPTY gets S3, edges and claim enumeration, and must carry no timeline and no edge (`empty_violations_v6`) | H2 |

**Minor findings folded in:**

| Finding | Repair | Regression(s) |
|---|---|---|
| R5-09 | range and pointer variants; P1-gap records in the lint and its hash; **layer-A binding by position** (see §4, added after H3) | H1 ×3, H3 |
| R5-10 | exact tokens | F3 |
| R5-11 | ack token **multiset** equality against the expected per-page tokens | D3 |
| R5-12 | an entry without a page fails | D4 |
| R5-13 | refused entries fail; the reader-call scan continues past the first call | E4, `scan_all_reader_calls` |
| R5-14 | CONTRACT-DEVIATION for non-WHOLE-FILE files | D5, D5b |
| R5-15 | revision-6 coverage re-derived immediately before READ-COVERAGE; the historical path is untouched | — |
| R5-16 | statistical specification and code | `Statistics` class (5 tests) |
| R5-17 | the old verifier is written to a temp directory | `test_p3b_s5_r5_verify` |
| R5-18 | covered by R5-04 | — |

## 3. Architectural changes

| Area | Change |
|---|---|
| New module `scripts/p3b_s5_r6.py` | pure rules: time, plan derivation, facts, claims, reading, S1, S3, EMPTY, acks, temporal, statistics. It has no I/O except what the caller passes |
| New module `scripts/p3b_s5_r6_verify.py` | batch verification; reads only authoritative inputs; content comes only through the seal-aware resolver |
| `scripts/p3b_s5_verify.py` | gate: revision 5 is rejected as withdrawn; revision ≥ 6 routes to `verify_r6` on run `<batch>-R6`; gate `R6` is reported. **The historical branch (revisions < 5) is unchanged** |
| `scripts/p3b_read_source.py` | `R6_RUN` grammar; byte mode is coupled to it; `reader_plan_check` refuses with `refusal: "PLAN"`. `R5_RUN` stays as a named constant, withdrawn and not accepted |
| `scripts/p3b_s5_common.py` | one allowlist pattern for `ledger-p3b-r2/OB####-R6…/*.json(l)` |
| Record schema | `facts` on file-reading records; the new artifacts `claim-evidence.json`, `S3-LINT.json` (bound to the records' hash), `RUN-MANIFEST.json` and `R6-PROVENANCE.jsonl` |

**No change beyond the bounded slice was needed.** No STOP was triggered.

## 4. Tests

**Full suite:**
- 29 test files, **0 failing**, 873 tests and checks (867 before the six traceability regressions were added);
- `prepare --check` IDENTICAL;
- H-19 guard SEALED, 58 files, 0 violations.

**New: `scripts/tests/test_p3b_s5_r6_verify.py`, 53 tests.**

- **Positive control:** a synthetic revision-6 batch (OB9005: 2 DECOMPOSED labels with 2 and 20 units, 3 SINGLE labels; fake resolver; genuine byte sizes) **PASSES the full production verifier** with gate R6 PASS, deterministically.
- **Each** former false PASS is a named regression that now yields NOT-PASS **through the full verifier** (except the pure-rule tests H1-variants, H2, the statistics and the time parser).
- **`test_pass_requires_every_invariant`:** breaking any single one of P, E, R, T, L or S turns PASS into FAIL.
- **FP-6 composite:** caught independently by L, T, E and P. **It is a permanent test**, per the authorization.

**Affected: `scripts/tests/test_p3b_s5_r5_verify.py`, 37 OK.**
- The three HistoricalIsolation tests now assert **withdrawal** semantics (revision 5 → FAIL with "revision 5 is withdrawn").
- This is an intentional contract change under G-LOG-0083, not a weakened test.
- `old_verifier()` now writes into a tempdir (R5-17).

**Historical: `test_p3b_s5_verify` 55 OK and `test_p3b_s5_r5` 42 OK.** Historical byte-identity is preserved: `prepare --check` is IDENTICAL, and the historical verifier branch is unchanged.

**Change made during verification (H3):**
- Writing the named H3 regression showed that `s3_lint_v6` identified the layer-A object by its keys. An object stripped of `timeline`/`births`/`absences` escaped the pointer check.
- The full verifier's schema gate also catches that case, but the rule should not depend on it. `s3_lint_v6` gained `layer_a=` and the verifier passes the object's position (`{0}`).
- This is a two-line change within R5-09. It is recorded here because it came after the first full-suite run.

## 5. Attack results

**The audit's own scripts, run unchanged after the repair:**

| Script | Result | Interpretation |
|---|---|---|
| `full_path.py` (builds **revision-5** manifests) | BASE: **FAIL** (expected PASS). FP-1, FP-4, FP-5, FP-6: **FAIL** (expected FAIL) | every revision-5 batch is now rejected as withdrawn. **BASE failing is the intended consequence of the withdrawal, not a defect.** FP-2 had no target in the fixture (as in the audit) |
| `attacks.py` (calls the **revision-5 rule module** `verify_r5` directly) | 25 of 53 lines still show "FALSE PASS" | these are rule-level calls into the withdrawn `p3b_s5_r5_verify.py`, which **the production verifier no longer reaches** (revision 5 is rejected at the gate). The module is kept byte-identical as audited evidence. **The revision-6 equivalent of every one of these attacks is a named regression in §4, and each FAILs** |

**Revision-6 equivalents (all in `test_p3b_s5_r6_verify.py`, all NOT-PASS):**
- B1, B1b, B2, B3;
- C1, C2, C3, C4;
- D1, D2, D3, D4, D5, D5b;
- E1, E2, E3, E4, E6;
- F1, F3, F4;
- H1, H2, H3;
- J1;
- FP-1, FP-4, FP-5, FP-6.

**F2 (blanket NOT-DETERMINABLE)** is not machine-detectable. It remains a limitation of the S1 honesty check, as the audit stated. Revision 6 does not claim to close it.

## 6. Stated disagreement with the audit (for the re-audit to judge)

**"/" between S-ids (`S0957/S2361`) is not treated as a range connector.** The audit lists `/` among the missed range variants, and the causal analysis first adopted it.

**Reasons:**
- A slash is an enumeration: it asserts two ids and none in between.
- Treating it as a range produced false positives on legitimate enumerations.

The regression `test_H1_range_variants` pins this behaviour. **This is a deviation from the audit's finding, not a closure of it.**

## 7. Hashes

**Changed and new files (sha256):**

| File | Before (HEAD) | After |
|---|---|---|
| `scripts/p3b_s5_r6.py` | — (new) | `7d3c9b6810408b453b3b968b6ad1d3fa442c4be2600ea0630cd60f45dce92e04` |
| `scripts/p3b_s5_r6_verify.py` | — (new) | `5e70184e1d3485c42ee03dfd6b1cbe7909cef1a796ebf08224d855ca74355b7a` |
| `scripts/tests/test_p3b_s5_r6_verify.py` | — (new) | `6d1b3b27ade608c9ee89f95ab0367bec596ea155a684f53ad57094e8a571a138` |
| `scripts/p3b_s5_verify.py` | `d3804d21…4fc7` | `b3c5353808794d6a622eca55e20d7218f341d07a52de558ff27ea30b33c8ca35` |
| `scripts/p3b_read_source.py` | `3ca0197c…3b16` | `3b634653d8a77b0cfec163ce8c38f5787a70990f14ce8867e6251e8950b31fea` |
| `scripts/p3b_s5_common.py` | `3bed523a…2871` | `9ac0426a4364ed0ec5cfcca52ce4e0f72c229a727e3e0945f0edc31201d2090e` |
| `scripts/tests/test_p3b_s5_r5_verify.py` | (HEAD) | `728286c537967eb2a65fcd662e4d21662ed9cf216c833fdd50eef9072fe23585` |
| `prompts/20260926_2300_p3b-agent-contract-r6-addendum.md` | — (new) | `b164539e0e88e60652f381a0743d7607314acc16b500c8ce9b8349711b0d4a92` |

**Unchanged artifacts, verified:**

| Artifact | Value |
|---|---|
| rev3 contract `prompts/20260925_1204_p3b-agent-contract-r2.md` | `b8eef043f3b2ea4bbeb8be03fb1487787c6b1b6712455eaf15286299393809da` |
| `P3B-STATE.json` | `db52ac7a6fc5e653fd87d1b4bca900c69ba1c8a4fa45fc448835792ec134c21a` |
| ledger fingerprint | `4fde15fc42513725af23da039d154afb3a688018f039f20b05626a98bdad05e5` |
| `_batch_manifest_p3b_r2.jsonl` | `1b383fbc6ff720ebd2f893a74362ce73745dcf99243bc00ced1bcae3a5475682`; header contract revision **3**; 396 batches / 1,975 labels |
| `P3B-HOLDOUT-SEAL.json` | `9b99169d30d57b2ecc1d331838652aa6b765ce722059b30095a03df077d589c0` (SEALED, HS-3d32dd44d162) |
| withdrawn revision-5 code, kept as audited evidence | `p3b_s5_r5.py` `9a466d64…a6ea`; `p3b_s5_r5_verify.py` `1b3092ce…6ccc6` |
| revision-5 addendum and runbook | `86caedf6…df46b` and `fb17c235…d28f` |
| audit report | `6b61814b164792b4f5370ed926b09f7396ac9daf10c444650f45feb5e97c7e5c` |
| audit scripts | `attacks.py` `955fc570…a738`; `full_path.py` `46ebcad6…2036`; `scan.py` `e2616271…47fe`; `stats.py` `8bda6a5a…bedc` |
| audit `.out` files | unchanged |
| causal analysis | `94c10ef7…a544` |

`git diff HEAD` is empty for:
- the rev3 contract;
- `P3B-STATE.json`, the seal and the manifest;
- `ledger-p3b-r2/`;
- the audit report and `audit-p3b/r5-audit/`.

`ledger-p3b-r2/` contains **no** `-R5` or `-R6` directory. No READ-LOG was changed.

## 8. Corpus-read status

- **No agent read and no production corpus read** was made in this slice.
- **The new revision-6 tests are fully synthetic:** a fake resolver serves generated bytes and no corpus file is opened.
- **The historical verifier tests** (`test_p3b_s5_verify` and the historical parts of `test_p3b_s5_r5_verify`) perform **checker reads** of fixture sources through the seal-aware resolver. This is unchanged behaviour since before this slice, and those reads are neither agent reads nor S5 reads.
- The H-19 guard reports 0 violations.

## 9. Authorization status and next act

| Item | Status |
|---|---|
| Revision 6 | **NOT ACTIVATED** |
| S5 execution | **NOT AUTHORIZED** |
| Binary pre-classification | not run |
| Manifest | not regenerated |
| Re-audit | **not started.** No second audit is authorized by G-LOG-0083 |

**Next act (human only):** decide whether a **narrow independent re-audit** of revision 6 is required. The recommended scope:
- the eight MATERIAL findings;
- the §6 disagreement;
- the H3 change;
- whether the "every invariant" test is a sufficient reading of PASS ⇒ P∧E∧R∧T∧L∧S.

After that come the activation-checklist items in the addendum. **Engineering stops here.**
