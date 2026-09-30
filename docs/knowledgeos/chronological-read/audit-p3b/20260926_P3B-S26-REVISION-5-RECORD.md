# P3b §26 contract revision 5: implementation and verification record; brief for the ONE independent audit

**Authority:** G-LOG-0082 (human authorization of the S5 technical decisions and ONE §26 revision).

**Governance state:** **TECHNICAL DECISIONS AUTHORIZED · §26 REVISION AUTHORIZED · S5 EXECUTION NOT AUTHORIZED · CORPUS READ NOT AUTHORIZED**.

**Revision commit:** `2d12e96cb874357cc30bf6762bf869b8fba9d079`.

## 1. Artifacts

| Artifact | sha256 | Role |
|---|---|---|
| `prompts/20260926_2100_p3b-agent-contract-r5-addendum.md` | `7920dc93bafb341f62d60b894676f431ea86bf6ac8091ff1220a5cfd6dfd457e` | the revision-5 contract addendum: items 1–18; revision 3 governs everything else |
| `scripts/p3b_s5_r5.py` | `9a466d64c1b281ae23f3d9504cdc62f7b63e21e1f3c9f716f4b8d4febc48a6ea` | deterministic machinery for items 1–18; the `r5_checks` gate aggregator; metadata-only `plan --dry-run` |
| `scripts/tests/test_p3b_s5_r5.py` | `2d7564c964a3bdd981ba242d51537a8996d0948e8949a893123ce02042a8ed7a` | 42 tests (synthetic + read-only checks on sealed OB0018 objects) |
| `scripts/p3b_read_source.py` (modified) | `3ca0197c88d7791f43cc486a03f1a9436a58a62a0de31b3e5552852895283b16` | versioned `--mode bytes` (binary refusal, 24 KB UTF-8 pages, acknowledgement token); revision-5 run-id grammar; the default character mode is unchanged (+48/−3 lines) |

**Design decisions made in implementation** (for the audit to check):
- **Revision number 5, not 4.** The verifier's revision-4 gate already exists for the pilot-only schema-4 delta (used by pilot validation copies). Numbering this revision 5 keeps historical pilot re-verification byte-stable. Revision 5 adopts the schema-4 delta into production (item 10).
- **Not activated.** Activation means regenerating the production manifest with the revision-5 contract hash, which is production state. It is left to the post-audit human act, so the manifest, the state and `prepare --check` stay IDENTICAL.
- **Revision-5 run ids require byte mode:**
  - `OB####-R5-L##` single-context label;
  - `OB####-R5-L##U##` reading unit;
  - `OB####-R5-L##S` synthesis.
  Pilot and revision-2/3 run ids keep character mode.
- **S1 uses the §11.5 form:** on NO, a VERDICT-EVIDENCE-CONFLICT register record plus an escalation for H-02. The frozen verdict still feeds the roll-up.

## 2. Verification (the act's required sequence)

| # | Check | Result |
|---|---|---|
| 1 | deterministic tests | `test_p3b_s5_r5.py` **42 OK** (the partition, packing key, byte pages, binary pre-classification, archive seal check, reading state, empty labels, edges, scan levels, S1/S2/S3 and the audit sampler / HT / zero bounds) |
| 2 | artifact hashes | §1 |
| 3 | no production ledger or state mutation | ledger fingerprint `4fde15fc…05e5` = the value frozen at OB0018 authorization; `P3B-STATE.json`, manifest, revision-3 contract, `ledger-p3b-r2/`: no tracked change |
| 4 | no S5 dispatch | no revision-5 run id exists in any log; state unchanged |
| 5 | no corpus read | no reader invocation during this work; no READ-LOG file changed. Binary pre-classification is implemented and **not run** (it would be a checker read of 12 files' bytes, deferred to activation) |
| 6 | `prepare --check` | **S5 PREP CHECK: IDENTICAL** |
| 7 | all existing suites | **29/29 pass**: 25 unittest suites (742 tests; OB0018 68, S5 verify 55, V1.x 465, S5 ops 111, r5 42, others) + 5 script suites (s5a: 41 checks) |
| 8 | floor unchanged | manifest: **396 batches / 1,975 labels** |
| 9 | §14 / A.10 unchanged | revision-3 contract sha256 `b8eef043…09da` = the manifest's contract hash; not modified. The packing key has no chronological standing and is never emitted into records (tested) |
| 10 | sealed hold-out protection | `p3b_s5_h19_guard.py seal` → SEALED (`HS-3d32dd44d162`); `grep` → **0 violations** across 53 files, including the new module, its tests and the modified reader (15 pre-existing pre-seal-module hits reported, not counted) |
| 11 | all blob types in sizing | `all_blob_sizes` includes PRESERVED-AUDIT-COPY (tested). `plan --dry-run`: 178 DECOMPOSED (one more label crosses 600 KB once preserved copies are sized) |
| 12 | empty-required-set handling | `plan --dry-run`: **8 EMPTY**; `empty_label_violations` tested |
| 13 | S1/S2/S3 mechanically | tested synthetically, **and against the sealed OB0018 objects**: S2 flags exactly the birth errors the blind audit upheld (baseline MOVED[S1618]; arms B and C births.operational on S1691; arm A clean). S3 flags exactly the S-id ranges the verifier found (arms A and C; arm B clean) |

**Revision-5 configuration, metadata-only dry run** (packing key + all-blob sizing):

| Quantity | Value |
|---|---|
| paths | SINGLE 1,789 · DECOMPOSED 178 · EMPTY 8 |
| units | 2,336 in total (547 in decomposed labels) |
| row adjacencies | **7 (5 labels)**, the D4 optimum preserved |
| max unit | 1.52 MB (the single binary file; decision C) |

The +3 units vs D4's 2,333 come from all-blob sizing.

## 3. Remaining blockers before S5 authorization (exact)

1. **Verifier and runbook integration (implementation, not yet done).** Wire `r5_checks`, `byte_page_coverage` and `ack_violations` into the production verifier behind a `revision >= 5` gate. Write the revision-5 batch runbook: per-label dispatch per path; unit records, then synthesis; S3 pre-submit; plan and records in provenance. Earlier revisions must keep verifying byte-identically (the existing 55 verify tests stay green).
2. **ONE independent audit** of revision 5 (this record, the addendum, the module, the reader change and the integration from blocker 1). Only MATERIAL findings are repaired.
3. **Activation inputs (human acts):**
   - the binary pre-classification run (a checker read of 12 files, under authorization) and the **12 per-file decision records**;
   - the **audit-sample parameters and seed**, drawn before any S5 output exists.
4. **Activation:** regenerate the manifest with the revision-5 contract hash; `prepare --check` against it; write the production plan (2-path, units).
5. **The human S5 EXECUTION AUTHORIZATION.**

## 4. Brief for the ONE independent audit (prepared; not run)

**Scope (read-only; no corpus content; no dispatch):**
- **Fidelity to G-LOG-0082:** check items 1–18 and each amendment of the technical review against the addendum and the code.
- **Non-contradiction with frozen rules:** v3.5 R1 (packing key ≠ chronology), R17 (empty labels, reading state), R19; revision-3 contract §11.2 (S2), §11.5 (S1 form), §14 / A.10 (unchanged); G-04 (never thinned).
- **Byte mode:** determinism of the spans; exact tiling; no split code points; binary refusal before paging; old logs verifiable unchanged; the acknowledgement token's derivation and its verifier.
- **Hold-out safety:**
  - the archive member seal check cannot be bypassed;
  - pre-classification reads only via the resolver;
  - the scan taxonomy's false negatives are disclosed, not claimed away.
- **Statistics:** the HT estimator under the stated design; the ML exclusion enforced; the zero bounds (binomial and hypergeometric) correct.
- **The integration from blocker 1,** once implemented.

**Stopping rule:** one audit; repair only MATERIAL findings; no further audit cycle unless the human decides otherwise.



---

## 5. Blocker 1: verifier integration and batch runbook (completed)

**Commit:** `20c0edebc15ec923cfa17f7f8c89e869ca5eaea2`. **Authority:** G-LOG-0082.

**Changed or new files:**

| File | sha256 | Change |
|---|---|---|
| `scripts/p3b_s5_verify.py` | `d3804d2185636ddf79dc5f9431770b16baf4163f406e7ea07f7100fbc1624fc7` | gate `rev5` (+37/−9): `-R5` run grammar; manifest and slice run-id expectation; the batch's revision-5 log set; per-label byte coverage replacing character coverage; R5 failures; gate `R5` present **only** in revision-5 reports |
| `scripts/p3b_s5_common.py` | `3bed523acfcd55be9f37428fe70e1bed38dd8edb0d6275aaafe4fa98338e2871` | +1 additive allowlist pattern for revision-5 run directories (`.jsonl`, `R5-BATCH.json`) |
| `scripts/p3b_s5_r5_verify.py` (new) | `1b3092ce0f56ed980017984736ebd5bc307a7e404cb706a759bff2e68216ccc6` | batch-level revision-5 verification (below) |
| `scripts/tests/test_p3b_s5_r5_verify.py` (new) | `eb5c355df7d9c432a93654d7eda2bc0eb65dcf622e2cf4abd96f037287979fae` | 37 tests; synthetic S95xx fixtures; **fake resolver**; no corpus content |
| `prompts/20260926_2200_p3b-s5-r5-batch-runbook.md` (new) | `fb17c235f6e9e285c4f862ced4c7887dd64dcb21913604ab525d0e1b1c4bd28f` | the 16-step runbook, **specification only** |
| `prompts/20260926_2100_p3b-agent-contract-r5-addendum.md` (amended) | `86caedf6bfbf377968e18bdda6482f9e3f67ab35ff22bcb864f82e297c3df46b` | clarifications: `reading_state` carried in file-reading records (schema E unchanged); S3 on every submitted final object; the batch-assembly section |

**Verifier integration status: COMPLETE (gated).** Revision-5 path, via the assembled run `OB####-R5` (runbook step 13):
- **structure:**
  - the declared path equals the plan path (the 600 KB boundary);
  - SINGLE has no decomposition runs; DECOMPOSED has valid unit and synthesis runs; EMPTY has no runs;
  - the units equal the row-first + FFD-fill recomputation from the plan's packing order (an engineering order; no chronological meaning);
  - unsized files fail;
- **R19 ordering:** `units_validated_utc` < `synthesis_dispatched_utc`;
- **byte mode mandatory:** a character-mode page in a revision-5 run fails (mode is read from log metadata, never inferred from content);
- **reading state:** WHOLE-FILE requires complete byte coverage in the label's own runs;
- **acknowledgement tokens;**
- **exposure:** level 4 (a synthesis read, a unit reading outside its files) fails;
- **records:** exactly one file-reading record per required file, and per unit;
- **S3:** a pre-submit lint report bound to the submitted records' hash, plus an independent re-lint;
- **`r5_checks`:** reading state, R1/R2 edges, S1, S2, S3, EMPTY;
- **source of truth:** R(L), rows and pairs from the **hash-verified slice**. `R5-BATCH.json` is cross-checked and never trusted.

**Tests** (all 28 test files; 820 tests/checks; 0 failing):

| Suite | Result |
|---|---|
| revision-5 integration (`test_p3b_s5_r5_verify`) | **37 OK** |
| revision-5 unit (`test_p3b_s5_r5`) | 42 OK |
| historical verifier (`test_p3b_s5_verify`) | **55 OK** (unchanged tests) |
| V1.x, OB0018, S5 ops, s5a and other suites | OK |

**Historical-revision results:**
- For revisions **none/1, 2, 3 and 4**, on three historical fixtures each (OB9002, OB9003, the hub OB9101), the patched verifier's report is **byte-identical** to the pre-change verifier's (`p3b_s5_verify.py` at `2d12e96cb`, loaded from git). No `R5` gate key appears.
- The revision-5 module is **never loaded** for historical revisions (import guard test).
- A `-R5` run on a revision-3 manifest fails, as does an `-R2` run on a revision-5 manifest.

**Gates:**

| Gate | Result |
|---|---|
| `prepare --check` | **IDENTICAL** |
| H-19 guard | seal **SEALED** (`HS-3d32dd44d162`); grep: **55 files, 0 violations** |
| ledger fingerprint | `4fde15fc42513725af23da039d154afb3a688018f039f20b05626a98bdad05e5` (unchanged) |
| production-state fingerprint | `P3B-STATE.json` sha256 `db52ac7a6fc5e653fd87d1b4bca900c69ba1c8a4fa45fc448835792ec134c21a` (no tracked change; last changed at `b0f2a7a08`) |
| historical contract hash | revision 3 `b8eef043…09da` (unchanged) |
| floor | 396 batches / 1,975 labels |
| manifest contract revision | **3**, so revision 5 is **NOT ACTIVATED** |

**Corpus access (precise statement):**
- **zero agent reads and zero reader invocations;** zero READ-LOG files modified; zero dispatch;
- binary pre-classification was **not** run;
- the **pre-existing** historical verifier tests (`test_p3b_s5_verify`) perform in-memory **checker reads** of discovery files through the seal-aware resolver to recompute page hashes, as they always have. Nothing is printed or logged, and no hold-out file is read. The new revision-5 tests use only synthetic bytes through a fake resolver.
- **Correction:** the phrase "no corpus read" in §2 of this record should be read with this qualification.

**Defects found and fixed during Blocker 1** (none MATERIAL to the design):
- (1) the S5 input allowlist lacked revision-5 run directories: an additive pattern was added;
- (2) passing a directory to `logical()` tripped the path check: the directory is now resolved from the allowlisted file path;
- (3) a revision-5 manifest verified under an `-R2` run probed a forbidden path: it now fails as an R5 run mismatch without probing;
- (4) two new-test expectation errors in revision-5 unit tests: corrected;
- (5) the addendum said `reading_state` sits on dispositions: clarified to records, which keeps schema E unchanged.

**Open MATERIAL issue:** none known to the implementer. **The independent audit decides.**

## 6. Exact scope for the ONE independent audit (updated)

Everything in §4, plus the Blocker 1 integration:
- (a) the byte-identity and isolation proofs for revisions < 5;
- (b) the completeness of the revision-5 structural rules (SINGLE / DECOMPOSED / EMPTY, R19 ordering, the partition recomputation);
- (c) slice-as-authority versus `R5-BATCH.json`;
- (d) exposure-level logic, given that the hold-out lists are deliberately not referenced (the resolver's refusal is primary);
- (e) the S3 lint-report binding to the submitted records' hash;
- (f) the runbook's consistency with the verifier;
- (g) the allowlist addition.

**Stopping rule:** one audit; repair MATERIAL findings only; no automatic second audit.

## 7. Remaining blockers (exact)

1. **ONE independent audit** of revision 5, including Blocker 1.
2. **Activation inputs (human acts):** authorize the binary pre-classification run and make the 12 per-file decisions; set the audit-sample parameters and seed.
3. **Activation:** manifest regeneration with the revision-5 contract hash; the production plan; `prepare --check` against it.
4. **The human S5 EXECUTION AUTHORIZATION.**

BLOCKER 1 COMPLETE — REVISION 5 INTEGRATED AND VERIFIED — S5 STILL NOT AUTHORIZED
