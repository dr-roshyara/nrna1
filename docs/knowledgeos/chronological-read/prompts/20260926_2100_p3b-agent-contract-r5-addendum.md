# P3b S5 AGENT CONTRACT — REVISION 5 ADDENDUM (§26 change control; G-LOG-0082)

**Status: DRAFTED, IMPLEMENTED BEHIND THE REVISION-5 GATE, NOT ACTIVATED.**
- **Activation**, meaning regeneration of the production manifest with this revision's contract hash, writing the production plan, the binary decision records and the audit sample, is a **separate human act**, after ONE independent audit and after the preparation, test and guard gates.
- **This document authorizes nothing.** S5 execution, dispatch and corpus reading are **NOT AUTHORIZED**.

**Relation to revision 3:**
- the production contract revision 3 (`prompts/20260925_1204_p3b-agent-contract-r2.md`) **governs everything not changed here**, including every reading rule, closed value, schema E, §11, §14 and Appendix A.10;
- the schema-4 delta (NOT-CONSUMED-ESCALATED, G-LOG-0052; pilot only until now) is **adopted into production** by item 10;
- **unchanged:** the population, the 396-batch composition, the 1,975 labels, the hubs, K = 64, A'1, R0/R2/R19, the §14 / A.10 historical chronology, G-04, R17, Decision A, P4+.

**Machinery:** `scripts/p3b_s5_r5.py` (the deterministic functions; the item numbers below match its docstring) and `scripts/p3b_read_source.py --mode bytes` (the versioned reader mode).

**Evidence status of this revision:** ENGINEERING-ONLY / EMPIRICALLY-UNVALIDATED (A, B, H); logically derived from the frozen rules (all others). No item is a fidelity claim.

---

## 1–3. Dispatch and partition (decisions A, B)

1. **Two-path dispatch, per label** (the dispatch unit is the label; the batch remains the governance grouping):
   - **SINGLE:** R(L) ≤ 600,000 bytes. One agent (`OB####-R5-L##`) reads R(L) and produces the label object under the revision-3 procedure plus this addendum.
   - **DECOMPOSED:** R(L) > 600,000 bytes. Reading units (`OB####-R5-L##U##`) produce file-reading records. One synthesis agent (`OB####-R5-L##S`) forms the object **from records only**, under S1–S3.
   - **EMPTY:** see item 16.
2. **Decomposition applies only above the budget.** R(L) = the label's stage-2 files ∪ all its row sources; no thinning; hubs have no stage 2.
3. **Partition (label-local)**, `partition()`:
   - row sources are packed **next-fit** in packing-key order (item 5), contiguously;
   - the remaining files go **first-fit decreasing** (ties by S-id) into existing units, else new units;
   - files are never split; no cross-label mixing.
   - **Engineering facts** (D4; not fidelity): at 600 KB, row-source adjacencies fall to the lower bound (7, in 5 labels); 28 pair-evidence sets are newly split by row placement and are covered by S1.

## 4. Sizing

All blob types participate: GIT-OBJECT (git object size) and **PRESERVED-AUDIT-COPY** (the preserved file's size). An unsized required file is an **error**, never a zero (`all_blob_sizes`, `label_plan`).

## 5. Packing key: NO chronological standing (decision B′)

`packing_key()`:
- **Group 0:** file-level dated-position candidates (EXPLICIT date basis ∧ `order_evidence` not exactly SOURCE_ID ∧ no BULK block), by that date.
- **Group 1:** all other files.
- **Within a group:** by S-id.

**Rules for its use:**
- **Its only use is to decide which row sources share a reading unit.**
- It is **never** written into any record, never cited as chronology, and never used as historical evidence.
- **The historical timeline remains the frozen partial order of §14 / A.10 and v3.5 R1** (dates only, never `source_id` order; undated and BULK sources unordered; STEP-NUMBER only within its series), formed at synthesis.

## 6–7. Binary files (decision C)

6. **No automatic extraction.**
   - For each of the 12 required binary files, `binary_preclassify()` produces: the format or signature; for each stage-2 hit, whether its offset lies in text bytes or in compressed/non-text bytes; and a **candidate** (FALSE-HIT when every hit lies in non-text bytes, else HUMAN-REVIEW).
   - **A per-file decision record**, a governed artifact decided by the human, sets one of FALSE-HIT · NOT-CONSUMED-ESCALATED · EXTRACT.
   - **FALSE-HIT for a hit inside non-text bytes** extends F2 (the hit's relation to the label is not content). The record cites the offset and the region.
   - **NOT-CONSUMED-ESCALATED** preserves the unread state (item 10); G-04 still applies (never thinned).
   - Pre-classification is a checker read of the file's bytes through the seal-aware resolver. **It runs only at activation, under authorization.**
7. **EXTRACT only when all of the following hold** (`extraction_permitted`):
   - the specific file is explicitly authorized;
   - the extractor is deterministic and validated;
   - provenance is defined;
   - **for archive/container formats, a member-level seal check** (`archive_member_seal`) finds no hold-out member.

   **Archive members never bypass the sealed resolver.**

## 8–9. Byte-bounded reader mode (decision D)

8. **The mode:** `p3b_read_source.py --mode bytes --page K`, versioned `bytes-r5`.
   - Pages of at most 24,000 bytes on UTF-8 code-point boundaries, deterministic and tiling the content exactly.
   - The page record carries {byte_start, byte_end, page_sha256, content_sha256, ack_token, mode}.
   - **Binary content is refused** (`BINARY-CONTENT`) and never paged as text.
   - Revision-5 run ids require byte mode; all other run ids use the unchanged character mode.
   - **Historical character-paged logs are immutable.** The verifier supports both modes by the log's `mode` field (`byte_page_coverage` for `bytes-r5`; the existing `page_coverage` otherwise).
9. **Acknowledgement token:** `ACK-<last 12 hex of the page sha256>`, printed in the page trailer. Every record lists, per relied-upon file, the tokens of **every** page of that file (`ack_violations`).
   - It makes "the page end was delivered to and copied by the agent" checkable.
   - **It does not prove cognition.**

## 10. Reading state (decision E)

- **The field:** every **file-reading record** (units and single-context agents alike) carries `reading_state` ∈ {WHOLE-FILE, READ-PARTIAL, READ-FAILED, NOT-CONSUMED}. Stage-2 dispositions keep schema E; their `method` shows the state.
- **The verifier binds the field to evidence:** WHOLE-FILE requires complete byte-mode page coverage in the label's own reading runs. *(Clarified in Blocker 1, before audit and activation: the state is carried in the records, not added to schema E.)*
- **The disposition form of any non-WHOLE-FILE state is NOT-CONSUMED-ESCALATED:**
  - every dimension ESCALATED;
  - an `escalations` entry with reason CONTRACT-DEVIATION naming the S-id.
- **One-directional rule** (`reading_state_violations`): **only WHOLE-FILE** may support FOUND, GENUINELY-UNDEFINED-AFTER-CENSUS, births and change points. **G-04 unchanged. R17 preserved:** a census with any non-WHOLE-FILE required file is incomplete.

## 11. Edge classes (decision F)

`dependency_edges[].edge_class` ∈ {**R1-STRUCTURAL**, **R2-EVIDENCED**}:
- **R1-STRUCTURAL:** co-label or row structure; `source_id` must be a row source of the label.
- **R2-EVIDENCED:** an agent-recorded **quote that explicitly states the dependency**, with `source_id`. The quote's existence is verified mechanically (production quote checker).
- **The OB0018 naming proxy is not the R2 definition.** Neither class is historical fact. Every downstream graph operation declares which classes it admits.

## 12. Scan taxonomy (decision G)

`scan_level()` and `log_level()`:

| Level | Meaning | Source |
|---|---|---|
| 1 | reader invocation (no S-id, e.g. `--help`) | transcript scan |
| 2 | source-access attempt: a reader call with an S-id (foreign run/batch is a violation), or access outside the reader (git show / cat-file / log -p / grep, corpus-path globs, direct resolver imports, named corpus paths or blob specs) | transcript scan |
| 3 | successful source read of a permitted file | read log |
| 4 | corpus-content exposure: a successful read of a non-permitted file, or any hold-out file | read log |

**The resolver's seal refusal is the primary hold-out protection.** The scan is secondary and is **not** a guarantee of complete detection.

## 13–15. Safeguards (decision I)

13. **S1: FILE CONTENT ↔ PAIR CLAIM** (`required_pair_checks`, `s1_violations`).
    - **Scope:** every P3a pair touching the label whose basis cites a file in R(L). The reading agent (unit or single-context) records in that file's record `pair_evidence_checks: [{pair_id, supports: YES | NO | NOT-DETERMINABLE, quote}]`. YES and NO need a quote.
    - **On NO (contract §11.5):** a `VERDICT-EVIDENCE-CONFLICT` register record citing the pair, **and** an object escalation (reason OTHER, detail `VERDICT-EVIDENCE-CONFLICT: <pair_id>`, for H-02). **The roll-up still uses the frozen verdict.**
    - **Why delivering pair records is not enough:** the pilot already delivered them.
14. **S2: row membership** (`s2_violations`). Every S-id cited in `births` must be a row source of the label; otherwise the candidate is a P1-gap record. **Applies to all paths**, including single-context (enforcing §11.2).
15. **S3: output lint** (`s3_lint`) on **every submitted final object** (synthesis and single-context alike). It rejects S-id ranges, layer-A register ids or pointers, and quarantine hits.
    - **It runs before submission**, and a failure blocks submission.
    - **The verifier repeats it** after submission.

## 16. Empty required set (R17)

A label with **no required file** (8 labels) takes path EMPTY. Its object must have:
- every birth NOT-EVIDENCED-IN-CAPTURE;
- no FOUND and no GENUINELY-UNDEFINED-AFTER-CENSUS;
- no stage-2 dispositions;
- an escalation whose detail names `EMPTY-REQUIRED-SET`.

**Checked by:** `empty_label_violations`.

**Distinct from:** "not read" (item 10) and "not found".

## 17–18. Pre-registered probability audit (decision H)

17. **Drawing the sample:** at activation, **before any S5 output exists**, a stratified sample is drawn with a **frozen seed** (`draw_audit_sample`; inclusion probability π = n/N per stratum).

    | Stratum | Treatment |
    |---|---|
    | the 5 multi-row-unit labels | **census** |
    | other DECOMPOSED labels | high rate |
    | hubs | separate |
    | SINGLE labels | simple random sample |

    **The audit and its estimand:**
    - **Audit instrument:** an independent single-context re-analysis, **blind to the S5 output**.
    - **Estimand: ADJUDICATED DISCORDANCE** (not "error rate"). Same-family agreement is not independent corroboration.
    - **Estimator:** stratified Horvitz–Thompson (`ht_estimate`), with Wilson intervals; zero-discordance bounds by `zero_bound` (binomial, or hypergeometric for finite strata).
    - **Timing:** completed **before the P4 hand-off**.
    - **The S5 floor (all 396 batches, 1,975 labels) is never replaced by sampling.**
    - **The sampling parameters** (rates, target bounds, the human-review subsample) are fixed in the activation act.
18. **ML separation:** any ML-prioritized review queue is **separate** from the probability sample. `ht_estimate` **refuses** discordant labels outside the sample. ML may later assist candidate retrieval, review prioritization, anomaly detection and uncertainty-ordered review, only after adjudicated S5 data exist, and **never** decides partition membership, identity, births, edges, canonical identity, theory or truth.

---

## Batch assembly and verification (Blocker 1)

A revision-5 batch is verified through its **assembled** run `OB####-R5` (runbook step 13).

**`p3b_s5_verify.verify`:**
- runs all historical gates;
- adds gate **R5** (`p3b_s5_r5_verify.verify_r5`) **only** when the manifest's contract revision ≥ 5.

**Per-label source of truth:** R(L), row sources and P3a pairs come from the hash-verified **slice**. `R5-BATCH.json` is cross-checked against it and never trusted for them.

**Revision boundary:**
- revisions < 5 are verified byte-identically to before (tested against the pre-change verifier);
- a `-R5` run on a revision < 5 manifest fails, as does a non-`-R5` run on a revision-5 manifest.

**Runbook:** `prompts/20260926_2200_p3b-s5-r5-batch-runbook.md` (specification only).

## Activation checklist (all human acts or gates; NOT performed by this revision)

1. One independent audit of this revision; repair of MATERIAL findings only.
2. The per-file binary decision records (12), after pre-classification under authorization.
3. The audit-sample parameters and seed.
4. Manifest regeneration with the revision-5 contract hash; `prepare --check` against the new manifest; production plan written.
5. The human **S5 EXECUTION AUTHORIZATION**.
