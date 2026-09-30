# Revision 5: ONE independent adversarial audit (report recorded verbatim; no repair)

**Recorder's note (orchestrator):**
- **Provenance:** this is the report of the ONE independent audit authorized under G-LOG-0082. It was produced by an independent subagent with no drafting context, working read-only, and is recorded **without edits to its findings**.
- **Artifacts:** its reproducible attack scripts and outputs are preserved in `audit-p3b/r5-audit/`. The sha256 values below are identical to the report's. The scripts use a fake resolver with synthetic bytes, and the scan script passes reader commands only as strings to the scanner. Quarantine scan: 0/0.
- **Correction:** the implementer's Blocker 1 statement "Open MATERIAL issue: none known" (revision-5 record §5) is **superseded by this audit**.
- **Nothing was repaired.** Under the stopping rule, a MATERIAL finding reopens implementation **only by a new human decision**.

| Artifact | sha256 |
|---|---|
| `r5-audit/attacks.py` | `955fc570cd316af48269adadd9f21e757c896309c6599a065c6e6f9d2497a738` |
| `r5-audit/full_path.py` | `46ebcad6e0716012076b013576fc9538a31bde7ff80836cb692f5544523d2036` |
| `r5-audit/scan.py` | `e2616271d848e2421579160ade3c23195ea3a1d191cac12294ab70958b5447fe` |
| `r5-audit/stats.py` | `8bda6a5a57a86ada96df37c58f340aabbadccdb316e8f64f50b77ec6aa51bedc` |

---

## 1. Executive verdict: **NEEDS-REVISION**

Revision 5 can produce a false PASS. An end-to-end synthetic batch that breaks R19, record-only synthesis, plan integrity and provenance **all at once** gets `result: PASS` and gate `R5: PASS` from the full production verifier `p3b_s5_verify.verify` (FP-6 below). I found 8 MATERIAL findings, 9 MINOR and 1 COSMETIC. Nothing was repaired.

## 2. Findings table

Scripts are under `/tmp/r5-audit/`, preserved in `audit-p3b/r5-audit/`. Outputs are in `attacks.out`, `full_path.out`, `stats.out` and `scan.out`.

| ID | Sev | Level | Attack | Expected | Observed | Reproducible? | Consequence |
|---|---|---|---|---|---|---|---|
| R5-01 | **MATERIAL** | III | **I/J provenance.** The unit records are valid but contain no facts. Synthesis then adds a CHANGES-DEFINITION point (as INFERENCE), a FOUND with a FOUND disposition, and an R2 edge. | FAIL | **PASS** (verify_r5 J1; full verifier FP-1, FP-6) | attacks.py J1; full_path.py FP-1/FP-6 | The verifier checks only `source_id`, `reading_state`, `ack_tokens` and `pair_evidence_checks` on records. The addendum defines no evidence schema for file-reading records (the pilot's `timeline_facts`/`quote` format was not carried over). No invariant links an object claim to a record. |
| R5-02 | **MATERIAL** | II (R19) | **C.** `units_validated_utc < synthesis_dispatched_utc` is compared as plain strings. Tried: timezone offset (synthesis 06:00Z against validation 10:00Z), non-ISO strings (`"2026-10-01" < "later"`), fractional seconds, and unit reads whose log `utc` falls after synthesis dispatch. | FAIL | **PASS** in all 4 (C1–C4; also inside FP-6 on the full path) | attacks.py C1–C4 | The timestamps are self-declared in R5-BATCH.json and never checked against the read log's `utc`. R19 is not enforced. |
| R5-03 | **MATERIAL** | II (no synthesis reader, exposure) | **E.** (a) The synthesis agent reads a file using a unit's run id, after dispatch. (b) Reads under undeclared R5-grammar runs (`L02U09`, `L09`, `L06S`). (c) A read with a foreign `--batch`. | FAIL | **PASS**. Case (b) gives only the alert "READ-LOG … ignored" on the full path (FP-4). | attacks.py E1–E3; full_path.py FP-4/FP-6 | The reader accepts any run id matching the R5 grammar and any `--label` (p3b_read_source.py:135–149). Exposure is only judged for runs declared in R5-BATCH.json. The verifier reads only the assembled log and never the per-run `READ-LOG.jsonl` files. |
| R5-04 | **MATERIAL** | I/II | **D/J label ownership.** Label B's own run never reads shared file S9502. Label A's agent logs S9502's pages with `--label B`. B's record claims WHOLE-FILE and a FOUND rests on it. Also: two labels declaring one run id (E6). | FAIL | **PASS** (D2, E6) | attacks.py D2, E6 | `coverage_by_label` groups by the agent-supplied `working_label`, not by "run belongs to this label". The historical `whole` set uses the same key. The addendum's "the label's own reading runs" is not enforced, and the `L##` in a run id is not bound to its label. |
| R5-05 | **MATERIAL** | I | **B plan integrity.** (a) SINGLE label with 702,000 real bytes, declared 32,000. (b) The committed "valid" fixture itself: declared 1.0 MB DECOMPOSED, real content 20 KB. (c) Packing order permuted, which changes unit membership. (d) Arbitrary `plan_sha256`. | FAIL | **PASS** (B1, B1b, B2, B3; FP-6 declares 11.7 MB for 117 KB of content) | attacks.py B1–B3; full_path.py FP-6 | `plan_sha256` is never read. `sizes` are never compared with the content bytes the verifier already fetched. `packing_order` is never recomputed with `packing_key` from the slice's `source_meta`. Dispatch-path and partition checks are only checked against inputs the batch file supplies itself, not against the slice. |
| R5-06 | **MATERIAL** | II (item 10) | **D.** A file with full page coverage whose record says READ-PARTIAL is used as a NARROWS, CONTRADICTS or RETRACTS change point. | FAIL | **PASS** (D1). Control: CHANGES-DEFINITION is caught (D1b). | attacks.py D1/D1b | `reading_state_violations` only tests the `"CHANGES-"` prefix. The historical READ-COVERAGE check needs page coverage but ignores `reading_state`. This breaks the one-directional rule. (The PB05 full-path fixture had no eligible timeline point, so this is shown at rule level.) |
| R5-07 | **MATERIAL** | III (S1) | **F.** A YES check whose quote is not in the file. | FAIL | **PASS** (F1; full path FP-5) | attacks.py F1; full_path.py FP-5 | The quote is only tested for being present. `p3b_s5_quotes.py` checks only objects, register and P1-gap records, never R5-BATCH records. A dishonest YES, or blanket NOT-DETERMINABLE (F2), cannot be detected by any machine. Only the probability audit can catch it. |
| R5-08 | **MATERIAL** | III / II (S3) | **H/J EMPTY.** An EMPTY object carries an S-id range, a dependency edge with no `edge_class`, and a timeline point from another label's file. | FAIL | **PASS** (H2) | attacks.py H2 | `r5_checks` returns `empty_label_violations` only, skipping S3, edge-class and reading-state checks, despite addendum item 15 ("every submitted final object"). The historical AUDIT check accepts any S-id cited anywhere in the batch's slices. An EMPTY label can gain content that no record supports. |
| R5-09 | MINOR | II (S3) | **H.** S-id range variants: `through`, `…`, `−`, `‒`, `―`, `S9511-13`, `/`, `bis`, `~`, `until` (10 missed). Register-pointer variants: `See the Register record` (the S3 regex has no `re.I`), `cf. register`, `research-register`, `OB9501#3`. | caught | missed | attacks.py H1 | The historical G-12 check catches the case-only variant, but range variants survive the full path. P1-gap records are outside both the S3 lint and its hash. "Failure blocks submission" is unprovable, because the lint report can be computed after the fact. An object with no `timeline` key skips the pointer check (H3; the historical SCHEMA check compensates). |
| R5-10 | MINOR | II (S1) | **F.** A NO on RP0751 is satisfied by a register record and escalation that are about RP0752 and merely mention RP0751. The escalation reason is not checked (F4). | FAIL | PASS | attacks.py F3, F4 | Pair ids are matched as substrings anywhere in the text. |
| R5-11 | MINOR | I | **D.** Extra, duplicated or foreign ack tokens. | FAIL | PASS (D3) | attacks.py D3 | The check is a superset test. The tokens also appear in the read log, so an orchestrator could copy them. Proves delivery, not cognition (the addendum says so). |
| R5-12 | MINOR | II | **D.** An R5-run read-log entry with no `page` field (a whole-content read). | FAIL | PASS (D4) | attacks.py D4 | `mode_failures` only inspects pages that are dicts. The reader currently refuses non-paged R5 reads, so the stop condition depends on the reader, not the verifier. |
| R5-13 | MINOR | II (exposure) | **E.** A refused access attempt by the synthesis run (a possible SEAL-BREACH-ATTEMPT). `scan_level` returns after the first valid reader call, so a second foreign-run call in the same command is missed. Python `open()`, `cat` and `$G show` are not flagged. | FAIL | PASS (E4); scan misses | attacks.py E4; scan.py | The resolver still refuses hold-out content, so no hold-out bytes are exposed. But runbook stop condition 16 is not enforced by the verifier, and the transcript scan leaves no artifact the verifier could check. |
| R5-14 | MINOR | III | **D.** A NOT-CONSUMED or READ-PARTIAL row source with no CONTRACT-DEVIATION escalation in the object. | FAIL | PASS (D5, D5b) | attacks.py D5/D5b | The escalation rule is checked only for stage-2 dispositions (historical rev-4 check). The unread state survives only in R5-BATCH records, not in the final object (addendum item 10 is ambiguous here). |
| R5-15 | MINOR | I (liveness) | A pre-existing variable-shadowing bug: `logged, cov = …` (p3b_s5_verify.py ~l.706; l.682 at `2d12e96cb`) overwrites the per-label rev-5 `cov` inside any label with STAGE-2A-2B dispositions. | PASS | false READ-COVERAGE FAIL (seen on converted PB02, glyph label) | full_path.py with `PB=PB02` | False FAIL, not false PASS. Inherited from rev 3, where the overwrite also carries over to later labels. |
| R5-16 | MINOR | stat | Overlapping strata accepted: HT total 2.5 against a true 1. The 8 EMPTY labels have no stratum. `zero_bound(0)` raises ZeroDivisionError. A rate of 0 still draws n=1. No stratified variance or CI. | reject | accepted | stats.py | The design is not yet fully specified (see §6). |
| R5-17 | MINOR | hygiene | `HistoricalIsolation.old_verifier()` temporarily writes `scripts/_old_p3b_s5_verify_for_test.py` into the repo (removed in `finally`). | — | observed | test code l.347 | If a run is interrupted, a tracked tree could be left dirty. |
| R5-18 | COSMETIC | I | Run-id grammar only: `L##` is never compared with the label's position. | — | — | code | Part of R5-04. |

What I tried that the machinery correctly stopped: SINGLE with unit runs; DECOMPOSED without units or synthesis; unit set ≠ partition; packing order ≠ rows; unsized file; batch file misstating the slice's R(L); character-mode page dicts; synthesis reading under its declared run (level 4); a unit reading outside its files; missing S1 check; S1 under the wrong pair id; NO without register or escalation; S2 on single and decomposed paths; lint hash not matching the submission; CHANGES-* on a non-WHOLE file; rev < 5 with `-R5` and rev 5 with `-R2`; rev 1–4 reports byte-identical (tested). The real S4 outputs also tripped S2 (a birth on a non-row source) and S3 (ranges), so those two checks have real effect.

## 3. False-PASS analysis

**Can an invalid reconstruction pass the current Revision-5 verification path? YES.**

**Smallest counterexample (rule level):** `/tmp/r5-audit/attacks.py` J1.
- Take the committed valid Fixture. In the DECOMPOSED object: set `timeline[S9512].change_vs_previous = "CHANGES-DEFINITION"`; add an absence resolved FOUND with `supplied_by` S9514; set a FOUND disposition; add an R2-EVIDENCED edge to an invented label.
- Recompute the lint.
- `verify_r5` returns `[]`. The unit records are only `{"source_id", "reading_state": "WHOLE-FILE", "ack_tokens", "pair_evidence_checks": []}`, so none of these claims can come from them.

**Composite through the full production verifier:** `/tmp/r5-audit/full_path.py` FP-6. Observed: `FP-6 expected FAIL observed PASS R5-gate PASS`.
- **Fixture:** historical OB9005, rewritten to the rev-5 layout. A fake resolver serves synthetic bytes; hold-out sets are the synthetic ones.
- **What was manipulated in one label:**
  - declared 11.7 MB against 117 KB of real content (R5-05);
  - 20 units, with the synthesis dispatch stamp 4 h *before* unit validation, via a `+05:00` offset (R5-02);
  - every unit read logged *after* synthesis dispatch (R5-02);
  - one synthesis read disguised under a unit run id (R5-03);
  - a CHANGES-DEFINITION claim made only by synthesis, marked INFERENCE (R5-01).

## 4. Runbook/verifier matrix

| # | Step | Implementation/verifier enforcement | Test | Status |
|---|---|---|---|---|
| 1 | Batch preparation | Slice hashes: enforced (historical SLICES check). Plan hash: never checked. | slices yes; plan no | partially enforced |
| 2 | Path classification | Recomputed, but only from **declared** sizes | 600 KB boundary tested | partially |
| 3 | SINGLE dispatch | Byte mode (dict pages, declared runs); one record per file. "One agent" not checkable. | yes | partially |
| 4 | Partition | Recomputed from the declared packing order; `packing_key` not recomputed | yes | partially |
| 5 | Unit dispatch | Own files only, but only for declared runs; run authenticity not checked | yes | partially |
| 6 | Evidence records | Exactly one per file. "No label-level judgments" and the record content schema are prose only. | count only | partially |
| 7 | Unit validation | Coverage per label, not per unit; acks; level 4 for declared runs. Transcript scan and `units_validated_utc` are prose or self-declared. | partial | partially |
| 8 | S1 | Presence and escalation checked; quote not verified; substring pair-id match | yes | partially |
| 9 | S2 | Enforced | yes | enforced |
| 10 | S3 | Verifier re-lint. Pre-submit blocking is prose. Regex gaps; P1-gap excluded; EMPTY skipped. | yes | partially |
| 11 | Records-only synthesis | Order check is lexicographic; zero reads only under the declared synthesis id | yes | partially |
| 12 | Synthesis validation | `r5_checks` (EMPTY exempt) | yes | enforced (except EMPTY) |
| 13 | Freezing + assembly | Frozen hashes not verified; log-union completeness not verified | no | **documentation-only** |
| 14 | Final verification | Verifier yes; the quote checker is separate and excludes R5-BATCH records | partial | partially |
| 15 | Failure handling / no repair in place | None (a rerun in place under the same run id is indistinguishable) | no | **documentation-only** |
| 16 | Stop conditions | Character-mode page and level 4 are detected afterwards. Hold-out refusal, ledger/state change and guard failure are not. | partial | partially |

## 5. Epistemic boundary assessment

As things stand, SOURCE → RECORD is guarded for delivery (page hashes, acks) but not for content. For DECOMPOSED labels, RECORD → RECONSTRUCTION has no machine link at all, so the path can quietly become SOURCE → THEORY.

| Level III invariant | Enforcing check |
|---|---|
| Source evidence ↛ unsupported historical fact | Partial: the quote checker (object quotes), G-04 FOUND needs anchor+quote, and whole-file coverage for change points. **INFERENCE timeline points and non-change points need nothing.** |
| Decomposition does not create evidence | Partition/coverage checks only. **Records carry no facts, so nothing to check. Documentation-only.** |
| Synthesis does not create unsupported births | S2 (row membership) plus historical G-08/G-04 grammar. Record support for the birth: **documentation-only.** |
| Pair verdict not silently treated as source evidence | `pair_breakdown` = slice (historical G-05); the S1 NO escalation. A dishonest YES or NOT-DETERMINABLE: **none**; quote unverified. |
| Packing order has no chronological meaning | Not written into records (convention). The order is not recomputed, and **no check forbids `packing_order` from being used as timeline order.** Documentation-only. |
| Derived synthesis claim has a validated record | **None** (R5-01) |
| Partial read not represented as complete | WHOLE-FILE bound to byte coverage, **but the label-ownership key is spoofable (R5-04), NARROWS/CONTRADICTS/RETRACTS are exempt (R5-06), and the object does not have to carry the unread state (R5-14).** |

**Recommendations. These are minimal machine-checkable invariants, not repairs:**
1. Define a record fact schema, for example the pilot contract §1.3 fact list. Require every DECOMPOSED object claim to cite `(record run, source_id, fact index)`, where the record is WHOLE-FILE and its quote passes the quote checker. Then verify that the object's claims are contained in what the records support.
2. Parse RFC 3339 timestamps to UTC-aware datetimes and reject anything else. Require: max(unit-run log `utc`) ≤ `units_validated_utc` < `synthesis_dispatched_utc` ≤ min(synthesis output time).
3. Freeze the plan (run → label → files) and verify its hash. The reader should refuse run/file pairs outside the plan and take `working_label` from the plan. The verifier should read each run's `READ-LOG.jsonl` directly and FAIL on any batch entry outside the plan.
4. Compute coverage from the runs that belong to the label, and require each entry's `working_label` to equal its run's label.
5. Check `sizes[s] == len(content)`. Recompute `packing_key` from `source_meta`. Check `plan_sha256`.
6. Use `READ_CHANGE_POINTS` in the item-10 rule. Require a CONTRACT-DEVIATION escalation for any required file that is not WHOLE-FILE.
7. Quote-check S1 quotes against the content already fetched. Match pair ids as exact tokens. Require reason OTHER.
8. For EMPTY: run S3 and the edge-class check, and require no timeline and no edges.

## 6. Statistical readiness

**Not yet well-defined.**

What holds up:
- HT with π = n/N under SRSWOR is unbiased (full enumeration gives E = 2.0 against a true 2).
- `zero_bound` has the correct direction: the largest D with P(0 | D) ≥ α, which is conservative. The hypergeometric bound is ≤ the binomial one in every case checked; a census with 0 discordant gives 0.
- `wilson` is correct, but it is two-sided, whereas `zero_bound` is one-sided.
- The ML exclusion works: `ht_estimate` raises on discordant labels outside the sample.

Open items:
- **Frame and strata:** disjointness and completeness are not enforced (overlap gives HT 2.5 against a true 1). The 8 EMPTY labels have no stratum. The hubs stratum can overlap DECOMPOSED.
- **Estimand:** the unit of "adjudicated discordance" is undefined (per label? per field? any difference?). It is not a fidelity rate, which the addendum states honestly.
- **CI:** no variance or CI for the stratified total or share, and no rule for combining zero-discordance bounds across strata.
- **Robustness:** `zero_bound(0)` crashes; a rate of 0 still draws n=1. The seed should be frozen together with the drawn label list and the Python version.
- **Independence:** the audit instrument is a same-family re-analysis (acknowledged), so a zero observed discordance bounds disagreement between two same-family readings, not error.

Treatment of DECOMPOSED/SINGLE follows item 17 (census, high rate, SRS), but the rates are left to activation.

## 7. ML readiness

- **Current state:** no ML, embedding or similarity component is on the rev-5 path. `p3b_s5_r5*.py` imports only hashlib, json, math, os, random and re. The verifier and reader have none either. `near_dup_scan.py` (Jaccard, R13) and `build_source_register.py` belong to P2 and are not called by S5.
- **Decisions are rule-based:** equivalence, identity, births, relationships, canonicalization and truth are decided only by agent output plus deterministic rules. The random audit cannot be replaced, because `ht_estimate` refuses labels outside the sample.
- **Permissible later, only after adjudicated S5 data exist:** candidate retrieval, review prioritization, anomaly detection and uncertainty-ordered review, all kept separate from the probability sample.
- **Must stay forbidden:** deciding partition membership, identity or equivalence, births, edges, canonical identity, theory or truth, and replacing or weighting the probability audit.
- **Guard is prose only:** no code path prevents a future ML queue from feeding labels into `discordant` as long as they are also in the sample. Adjudication intensity could then differ for ML-flagged labels, which is a measurement bias.

## 8. Audit artifacts

- **HEAD:** `ddd5805b8` at the start. At the end it was `4588463bf`: concurrent commits by another session (`7be4459d4`, `4801482d3`, `4464d80a8`, `4588463bf`) touched `.claude/` and T-A/R-2 files only. `git diff ddd5805b8 HEAD` on the CR `scripts/` and `prompts/` is empty.
- **Files inspected:** p3b_s5_r5.py (9a466d64…), p3b_s5_r5_verify.py (1b3092ce…), p3b_s5_verify.py (d3804d21…), p3b_s5_common.py (3bed523a…), p3b_read_source.py (3ca0197c…), test_p3b_s5_r5.py (2d7564c9…), test_p3b_s5_r5_verify.py (eb5c355d…), the addendum (86caedf6…), the runbook (fb17c235…), p3b_s5_quotes.py, the decomposition-pilot contract §1.3, and the revision-5 record §§1–7. The hashes match the revision-5 record.
- **Tests:** `cd CR/scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_r5 test_p3b_s5_r5_verify test_p3b_s5_verify` → **Ran 134 tests, OK** (42 + 37 + 55).
  - The historical suite does in-memory checker reads of discovery files through the seal-aware resolver; that is existing behaviour, not S5 corpus execution and not hold-out access.
  - The isolation test transiently wrote and removed `_old_p3b_s5_verify_for_test.py` (R5-17).
- **Gates:** `prepare --check` IDENTICAL. H-19 seal SEALED (HS-3d32dd44d162). H-19 grep: 55 files, 0 violations (exit 1 comes from the reported, non-counted pre-seal module notes).
- **Fingerprints:** ledger fingerprint `4fde15fc42513725af23da039d154afb3a688018f039f20b05626a98bdad05e5`. P3B-STATE.json sha256 `db52ac7a6fc5e653fd87d1b4bca900c69ba1c8a4fa45fc448835792ec134c21a`. Manifest contract revision **3**.
- **Scripts (sha256):**
  - attacks.py `955fc570cd316af48269adadd9f21e757c896309c6599a065c6e6f9d2497a738`
  - full_path.py `46ebcad6e0716012076b013576fc9538a31bde7ff80836cb692f5544523d2036`
  - scan.py `e2616271d848e2421579160ade3c23195ea3a1d191cac12294ab70958b5447fe`
  - stats.py `8bda6a5a57a86ada96df37c58f340aabbadccdb316e8f64f50b77ec6aa51bedc`
- **Run commands:**
  - attacks.py and full_path.py: `cd CR/scripts/tests && PYTHONPATH=.:.. python3 -B <script>` (full_path.py takes an optional `PB=PB05`).
  - stats.py and scan.py: `cd CR/scripts && python3 -B <script>`.
- **git status:** unchanged by me. The only difference from the start snapshot is `.claude/F-series-todos.md`, which the concurrent commit `4588463bf` (not mine) committed.
- **full_path.py safety:** it monkeypatches `c.dio.discovery_resolver` with a fake that returns synthetic bytes, uses the testlib synthetic hold-out sets, stubs `qs.count_holdout_sids` to 0, and writes only to temp directories under `/tmp/r5-audit`.
- **Confirmations:**
  - no activation;
  - no manifest, state, ledger or seal change;
  - p3b_read_source.py never run;
  - no binary pre-classification on real files;
  - no agents dispatched;
  - no real-resolver `read_many` in my scripts;
  - no hold-out file read and no hold-out identifiers printed;
  - no repo modification, staging or commit.

REVISION 5 INDEPENDENT AUDIT COMPLETE — NO ACTIVATION — NO S5 DISPATCH
