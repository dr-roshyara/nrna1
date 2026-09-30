# S5 technical decision review: recommendations and adversarial verification of the provisional rulings

**Kind:** technical review for the human. **Decides nothing, authorizes nothing, modifies nothing** in production, contracts or governance records.

**Inputs:**
- `20260926_S5-DECISION-PACKAGE.md`;
- `D4-VALIDATION-ROW-FIRST.md`;
- the OB0018 result report;
- the frozen production contract revision 3 (`prompts/20260925_1204_p3b-agent-contract-r2.md`, §11.2, §14, Appendix A.10);
- Master Protocol v3.5 (R1, R17, R19);
- the human's provisional rulings A–I and B′.

## 0. New facts established for this review (metadata only)

| # | Fact | Consequence |
|---|---|---|
| F1 | The frozen contract **already defines chronology** (§14.2–14.3, A.10): *"Temporal scope uses dates only, never `source_id` order (v3.5 R1: ingestion order is not argument order)"*. A dated position exists only for EXPLICIT date basis ∧ `order_evidence` ≠ SOURCE_ID ∧ no BULK block ∧ agent-confirmed `date_applies_to_file`. MTIME-only files (1,591 of 2,746) and BULK blocks are **unordered by design**. STEP-NUMBER orders only within its own series | the frozen chronology is a **partial order**. A *total* chronological order with S-id as a historical tie-breaker would contradict R1 and A.10 |
| F2 | In the OB0018 pilot, synthesis arms **already received the pair records** (via the slice, contract §3 inputs), yet every arm missed the RP1619 VERDICT-EVIDENCE-CONFLICT. Arm C re-read S1629 and still missed it | delivering pair records (package D8 S1 option a) is **insufficient**. The loss is the missing *check of the file against the pair's claim* |
| F3 | 7 required files are PRESERVED-AUDIT-COPY blobs (1–61 KB) that the size function skips. They affect 23 labels, one of which crosses 600 KB | a small undercount in all load figures (the D4 and package numbers stand as published; this is an erratum-level disclosure) |
| F4 | 8 S5 labels have an **empty required set** (6 Tier U, 2 Tier Z, 1 hub) | S5 needs an explicit rule for evidence-less labels (R17: NOT-EVIDENCED-IN-CAPTURE) |
| F5 | The production S5 contract (revision 3) is **one agent per batch**. Decomposition exists only as pilot machinery (G-LOG-0052, G-LOG-0079) | adopting any decomposition architecture **is itself a §26 revision** |

## 1. Agreement summary (my recommendation vs the provisional rulings)

| Decision | Provisional ruling | My recommendation | Agreement |
|---|---|---|---|
| A | architecture 1 + S1–S3 | **architecture 1 + S1–S3, applied through a two-path dispatch** (§2-A) | agree, with a scope amendment |
| B | row-first + FFD fill, 600 KB | same | **agree** |
| B′ | production timeline order as a total deterministic order | **a packing key with no chronological standing**; the historical timeline stays the frozen partial order (§14 / A.10) | **disagree** (material, F1) |
| C | binary-specific extraction by default; escalate unsupported formats | **per-file human decision (12 files) on a mechanical pre-classification**; extraction only per file, after a seal check | **disagree** (material, §2-C) |
| D | byte pages + binary refusal + acknowledgement token | same, with a versioned reader mode and immutable old logs | agree, with amendment |
| E | separate read-state field | same | **agree** |
| F | dual R1/R2 | same, with R2 defined properly in production (not the naming proxy) | agree, with amendment |
| G | four-level scan taxonomy | same, plus git-level and resolver-import patterns at level 2 | agree, with amendment |
| H | monitor during S5 with stratified random audit | same, with a **pre-registered probability sample**, a census of the tail strata, and the estimand defined as *adjudicated discordance* (not "error") | agree, with amendment |
| I | S1 + S2 + S3 | same, with **S1 redefined** as unit-level pair-evidence checks (F2) | agree, with amendment |

## 2. Decision-by-decision audit

### A. Synthesis architecture: **ACCEPT WITH AMENDMENT**

**Evidence:**
- OB0018 descriptive: arm A had 0 BASELINE-UPHELD on judgment fields; arms B and C each had 2.
- Formally UNDETERMINED; n = 1; a same-family audit.
- Evidence class: **ENGINEERING-ONLY / EMPIRICALLY-UNVALIDATED**.

**Contradictions:** none with R0–R19. The whole-file rule, READ-COVERAGE and G-04 all hold. Provenance SOURCE → PAGE → RECORD → FIELD is preserved, and records carry verbatim quotes that are verified mechanically.

**Hidden risk 1: scope ambiguity.**
- Architecture 1 is defined for multi-unit labels. At 600 KB, **1,790 labels are single-unit**: one agent reads the whole R(L) and could produce the object directly. That is the existing production procedure at label granularity, with no records layer.
- **Amendment:** a **two-path dispatch**, with the dispatch unit being the label:
  - (i) R(L) ≤ budget → a single-context label agent under the existing production procedure;
  - (ii) R(L) > budget → units + records-only synthesis + S1–S3.
- **Why:** this confines the new machinery to the labels that need it (177 labels at 600 KB). It also replaces the batch-level capacity failure (OB0004: 1.91 MB in one agent) with a label-level budget.

**Hidden risk 2: does "S1–S3 make it a different architecture"?**
- With S1 as amended (unit-level pair checks, see I), synthesis **remains records-only**. The pair-check result is a unit record.
- With S1 option (b) instead (re-reads by the synthesizer), the design becomes architecture 3 with a different re-read target.
- **The amendment keeps it architecture 1.**

**Required contract change:** a §26 revision adopting decomposition for R(L) > budget (F5), the two-path dispatch, and the empty-label rule (F4).

### B. Partition: **ACCEPT**

**Evidence:** D4, reproduced exactly: 253 → 7 row adjacencies at an equal unit count of 2,333, and 7 equals the lower bound. The 28 new pair-evidence splits are forced (covered by amended S1). The binary file exceeds any budget (governed by C). The partition is label-local, so there is no shared-source coupling. Evidence class: **ENGINEERING-ONLY** (exposure, not fidelity).

**Hidden risks:**
- (a) **600 KB context pressure** is within the demonstrated range: pilot units of 599 KB completed; one context completed about 1.09–1.3 MB in R2.3. There is no evidence that 600 KB harms reading. Its effect on the quality of records written late in a long context is **unmeasured**.
- (b) F3: one label moves across 600 KB once preserved copies are sized. The implementation must size **all** blob types.

**Required change:** the partition rule and budget in the §26 text; the size function must cover PRESERVED-AUDIT-COPY blobs.

### B′. Order key: **HOLD as proposed / ACCEPT WITH AMENDMENT as re-specified**

- **Contradiction (MATERIAL, F1):** a "production timeline order … total deterministic ordering" that places undated or MTIME files by S-id as a *chronological* claim **contradicts v3.5 R1 and contract A.10**. These say ingestion order is not argument order, and that undated sources have no dated position.
- **Resolution: separate two concepts that the proposal merges.**
  1. **Historical order** (layer A, synthesis): **unchanged**. It is the frozen partial order of §14 / A.10, formed at synthesis from confirmed dated positions, UNORDERED-BLOCK and within-series STEP-NUMBER. **No new chronology is invented.**
  2. **Packing key** (engineering, partition only): a total deterministic order used **only** to decide which row sources share a unit. **It carries no chronological standing and is never written into any record.**

**Minimal contract definition (proposed text):**
- *"Packing key (partition only; no chronological meaning; v3.5 R1):"*
  - (1) files with a file-level dated position candidate (EXPLICIT basis ∧ order_evidence ≠ SOURCE_ID ∧ no BULK), by that date;
  - then (2) all other files;
  - within each group, by S-id.
- **Why S-id may appear here without contradicting R1:** it is a deterministic tie-breaker for packing, not a claim of order. The dated-position condition cannot use the agent's `date_applies_to_file` confirmation, which exists only after reading, so the key is a pre-reading heuristic by necessity.
- **Scope:** it only matters for labels whose row sources exceed one unit: **5 at 600 KB** (28 at 300 KB). For all others every order packs identically.

**Evaluation of the listed cases under the amended key:**

| Case | Treatment |
|---|---|
| validated date | group 1 by date |
| no date / MTIME / BULK | group 2 |
| conflicting dates | the file-level `best_historical_date` is the key; agent confirmation stays a synthesis matter |
| ties | S-id |
| later date but earlier S-id | follows the date in group 1; that is the point of the key |
| ordering claims | none in either group; the timeline is built at synthesis per A.10 |

### C. Binary files: **HOLD as proposed (default extraction) / ACCEPT the amended policy**

**Evidence:** 12 required binary files (2 PNG, 3 ZIP, 7 with NUL bytes; 1.73 MB), all stage-2-only, touching 41 labels (post-pilot brief §E). The S2276 "hit" is almost certainly a byte coincidence in compressed data.

**Contradictions and risks of *default* extraction:**
1. **A new evidence type outside the frozen methodology** (brief §E.1). Every extractor needs its own validation (determinism, completeness, provenance), a full method-validation obligation for 12 files.
2. **Hold-out risk (MATERIAL).**
   - Archives (the 3 ZIP files) may contain copies of other corpus files, including hold-out content.
   - Extraction would deliver that content **around the seal-aware resolver**. The resolver checks the archive's S-id, not the identity of its members.
   - Seal integrity (H-19 SEALED) cannot be guaranteed without a member-level seal check.
3. **Stage-2 hits in binary data** are more often byte coincidences than content, so extraction would do much work to confirm FALSE-HITs.

**Amended policy (minimal):**
- (1) **Mechanical pre-classification** per file: format signature; whether each stage-2 hit offset lies inside compressed or non-text bytes.
- (2) **Per-file human decision** (12 files) among: FALSE-HIT (hit in non-text bytes; needs a §26 F2 extension) · NOT-CONSUMED-ESCALATED (honest gap; G-04 then fails that label, never thinned) · extraction for that specific file, only after a member-level seal check and with a declared, validated extractor.
- (3) **Absence of extraction never becomes absence of evidence:** the unread state is recorded (E).

**Required change:** §26: the F2 extension for hits inside binary bytes; the per-file decision list as a governed artifact; the extraction rule (if any file is chosen).

### D. Page layer: **ACCEPT WITH AMENDMENT**

- **Compatibility:** 24 KB byte pages are a **new, versioned reader mode**. The current reader pages by 20,000 characters (max 25,041 B).
- **Determinism:** UTF-8 boundary cuts are a pure function of the content bytes, so page spans and hashes are deterministic. Old character-paged logs stay valid and **immutable** for historical runs, and the verifier supports both modes by log version.
- **Acknowledgement token:** it goes in the page trailer, is derived from the page hash, and every page the agent relies on is listed in its record. The verifier checks it. It makes "saw the page end" checkable, **not** cognition.
- **Amendments:** a mode flag in every log entry; a verifier for both modes; binary refusal (`BINARY-CONTENT`) routes files to C.
- **Evidence class:** ENGINEERING-ONLY (it removes an *observed* failure mode: S2276 delivery ≠ reading).

### E. Read-state field: **ACCEPT**

- **The field:** a separate `reading_state` ∈ {WHOLE-FILE, READ-PARTIAL, READ-FAILED, NOT-CONSUMED}. The disposition form of any non-WHOLE-FILE state is NOT-CONSUMED-ESCALATED (every dimension ESCALATED plus a CONTRACT-DEVIATION naming the S-id).
- **The one-directional rule:** only WHOLE-FILE supports FOUND, GENUINELY-UNDEFINED-AFTER-CENSUS, births or change points.
- **G-04:** unchanged (never thinned).
- **R17:** compatible. An absence stays NOT-EVIDENCED-IN-CAPTURE until the census is complete, and a non-WHOLE-FILE state makes the census incomplete by definition.
- **The pilot permission is not promoted:** a §26 revision plus verifier tests are required, proving that no non-WHOLE-FILE state yields FOUND or GENUINELY-UNDEFINED.

### F. Co-label edges: **ACCEPT WITH AMENDMENT**

- **The two edge classes:** `edge_class` ∈ {R1-STRUCTURAL (co-label rows), R2-EVIDENCED (a quote stating the dependency)}.
- **Amendment:** in production, R2 means **an agent-recorded quote stating the dependency, whose existence is verified mechanically**. The OB0018 naming proxy (the quote names the target) was only a measurement device.
- **Neither class is historical fact.** P4+ graph operations must declare which class they admit. Both edge sets are reproducible from records.

### G. Scan taxonomy: **ACCEPT WITH AMENDMENT**

- **The levels:** (1) reader invocation · (2) source-access attempt · (3) successful source read · (4) corpus-content exposure.
- **This resolves the U02 `--help` case**, which is level 1 only.
- **Amendment:** level 2 must also classify, as access **outside** the reader:
  - `git show` / `git cat-file` / `git log -p` / `git grep` naming corpus paths or blob shas;
  - glob patterns over corpus directories;
  - direct import of the resolver (`p3b_discovery_io`, `read_many`).
- **Residual false negatives** (pre-registration audit m-1) stay disclosed.
- **Hold-out protection rests primarily on the resolver refusing sealed files.** The scan is secondary and must not be presented as a guarantee.

### H. Tail assurance: **ACCEPT WITH AMENDMENT**

The floor is untouched: every label is processed; sampling only audits.

**Amendment (the minimum for a defensible estimate):**
1. **A pre-registered probability sample**, drawn with a frozen seed **before** any S5 output exists, with known inclusion probabilities.
2. **Strata** (all known before sampling):

   | Stratum | Size | Treatment |
   |---|---|---|
   | multi-row-unit labels | 5 at 600 KB | **census** |
   | other multi-unit labels | 172 | sampled at a high rate |
   | hubs | 66 | separate scope (ESCALATED LOAD) |
   | single-unit labels | 1,790 | simple random sample |

3. **Estimand = adjudicated discordance, not "error".**
   - The audit instrument is an independent single-context re-analysis, blind to the S5 output. Discordant judgment fields are adjudicated per the OB0018 audit classes, and a human reviews BASELINE-UPHELD and DECOMPOSED-UPHELD disputes on a subsample.
   - Same-family agents make agreement ≠ corroboration. **Circularity is avoided only if the estimand stays "discordance with an adjudicated independent re-analysis".**
4. **Estimator:** stratified Horvitz–Thompson (design-based; valid under the known inclusion probabilities).
   - Rates are reported per stratum with Wilson or Clopper–Pearson intervals.
   - In a zero-discordance stratum, the one-sided bound is 1 − α^(1/n), finite-population-corrected (hypergeometric) where n is a large fraction of N.
5. **Size examples** (assumptions: independence across labels; binary per-label discordance; α = 0.05):
   - bounding at 10% with 0 observed discordances needs n ≈ 29 per stratum (fewer under FPC for the 172-label stratum);
   - estimating ±0.10 needs n ≈ 97 (worst case p = 0.5), times the design effect 1 + (m − 1)ρ if field-level outcomes are used; ρ is **unknown**.
6. **ML separation:** any ML-prioritized review queue is a **separate** queue and is **never** included in the HT estimate.
7. **Timing:** audits complete **before the P4 hand-off**, so discordances can be repaired before membership work.

### I. Safeguards: **ACCEPT WITH AMENDMENT**

| | S1 pair-evidence check (**amended**, F2) | S2 row-membership filter | S3 synthesis-output lint |
|---|---|---|---|
| Invariant | every P3a pair whose basis cites a file in R(L) has a recorded check of that file against the pair's claim | a birth cites only a row source of the label | no synthesized record contains an S-id range, a layer-A register pointer, or a quarantine hit |
| Implementation point | the **unit** (and the single-context agent): the unit receives the pair claims citing its files and records `pair_evidence_checks: [{pair_id, supports: YES/NO/NOT-DETERMINABLE, quote}]`. Synthesis consumes these records and escalates VERDICT-EVIDENCE-CONFLICT on NO | the verifier (a new G-rule; mechanical enforcement of the existing §11.2 rule) | the synthesis tool, pre-submit; the verifier re-checks |
| Verifier | every such pair has a check record; every NO has an escalation | `birth.source_id ∈ row_sources(L)` | `quarantine_hits`, the G-12 check, the S-id range regex |
| Failure state | missing check → level-2 FAIL; NO without escalation → FAIL | a birth on a non-row file → FAIL (the candidate belongs in a P1-gap record) | any hit → blocked submission |
| False-positive risk | a conservative NO on ambiguous evidence (escalated, not lost) | a genuine early occurrence in a non-row file (the designed route is P1-gap) | "Step 253–254"-style text: restrict the regex to `S\d{4}` |
| False-negative risk | evidence spread across files not cited in the basis | none for the stated rule | ids in another notation |
| Audit evidence | check records with quotes | the verifier report | the lint report hash in provenance |

**S2 also constrains single-context agents:** the OB0018 baseline broke §11.2 on births.lexical.

## 3. Global epistemic classification

| Ruling | Class |
|---|---|
| A, B, H | **ENGINEERING-ONLY / EMPIRICALLY-UNVALIDATED** (D4 is exposure, not fidelity; OB0018 is n = 1 and UNDETERMINED; a same-family audit) |
| B′ (amended), D, E, F, G, I | **ENGINEERING-ONLY**, each logically required by or consistent with existing frozen rules (R1, §11.2, §14/A.10, R17, G-04). No empirical claim |
| C (amended) | **ENGINEERING-ONLY**; per-file decisions are human acts |
| none | SOURCE-SUPPORTED, RECONSTRUCTION-VALID, THEORY-CONSISTENT or INDEPENDENTLY-CORROBORATED. **No ruling carries these; none is upgraded** |

## 4. ML and computational review

| Method | Decision it changes | Cost it reduces | Independent validation | Stays deterministic | False-evidence risk |
|---|---|---|---|---|---|
| exact/normalized quote and anchor retrieval | which passages a reviewer sees together | reviewer search time | none needed (deterministic) | everything | low |
| MinHash/LSH (fixed seed) | candidate near-duplicate pairs (calibration, §C-3) | pairwise comparison cost | precision/recall against adjudicated pairs | the thresholds; the decision stays human | medium: similarity ≠ identity |
| embeddings (ranking only) | review order | time to first finding | the randomized-queue comparison (package E6) | membership, dispositions | medium |
| graph anomaly detection over adjudicated edges | audit prioritization | undirected audit effort | the HT random audit as ground truth | graph construction (R1/R2 declared) | medium: structure ≠ fact |
| calibrated uncertainty / split-conformal | review depth | review minutes | calibration on adjudicated data; coverage check | acceptance | medium |
| active learning (after adjudicated labels) | which items are adjudicated next | labelling cost | frozen test set + held-out audit | no self-training | high if leaked |
| randomized residual audit (HT) | the error estimate of the unreviewed remainder | nothing (it *adds* cost) but it makes the other methods safe | none (design-based) | sampling | low |

**Rejected for now:** any ML in the partition, the dispositions, births, edges or the synthesis. **None improves evidence quality before adjudicated S5 data exist.**

## 5. Minimal contract changes (for internal coherence of the provisional set as amended)

1. **Decomposition adoption** for R(L) > budget; the two-path dispatch; label-local partition; row-first + FFD fill at 600 KB; sizing of all blob types (F3).
2. **The packing key**, with an explicit "no chronological standing" clause; §14 / A.10 unchanged.
3. **Binary:** the F2 extension (hit inside non-text bytes); the per-file decision list; a member-level seal check before any extraction.
4. **The byte-bounded reader mode** (versioned), binary refusal, acknowledgement tokens; the verifier supports both modes.
5. **`reading_state`** field + NOT-CONSUMED-ESCALATED in production; one-directional verifier tests.
6. **`edge_class`** R1/R2, with the production R2 definition.
7. **The four-level scan taxonomy** with the extended level-2 patterns.
8. **S1** (unit-level pair-evidence checks), **S2** (the G-rule for §11.2), **S3** (the pre-submit lint).
9. **The empty-required-set rule** (8 labels; R17).
10. **The audit-sampling design** (H) as a pre-registered annex.

## 6. Open human decisions (only those that are genuinely human)

1. Accept or reject the **B′ re-specification** (packing key ≠ chronology).
2. **The binary per-file list** (12 decisions), and whether any extraction is permitted at all.
3. Whether the **acknowledgement token** is required (D) or deferred.
4. **The audit-sampling parameters** (H): the target bound or precision per stratum; the human-review subsample size.
5. Confirmation of the **two-path dispatch** (A amendment).
6. The treatment of the **8 empty-required-set labels**.

## 7. S5 execution readiness (what remains)

1. The human rulings (the provisional set, with the amendments above accepted or rejected).
2. **One** P3b §26 revision drafting the changes of §5.
3. One independent audit of that revision; repair of MATERIAL findings only.
4. Implementation and tests: reader byte mode; verifier rules (S1, S2, S3, read state, edges, both page modes); partition tool; scan taxonomy; the sampling annex.
5. `prepare --check`, guard and test gates.
6. **A human S5 authorization act.**
7. Then the 396-batch, 1,975-label floor under R19.

## 8. Stop condition

- **MATERIAL contradictions** were found in **B′ as proposed** (a total chronological order with S-id ties contradicts v3.5 R1 and A.10) and in **C as proposed** (default extraction: a new evidence type and a hold-out bypass risk for archives). Neither is implemented. Both have minimal re-specifications (§2-B′, §2-C) for the human to accept or reject.
- **No other ruling contradicts the frozen architecture.**
- **S5 is not authorized.**

TECHNICAL DECISION REVIEW COMPLETE — HUMAN AUTHORIZATION STILL REQUIRED
