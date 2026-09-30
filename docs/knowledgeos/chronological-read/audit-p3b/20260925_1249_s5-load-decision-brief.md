# S-Series Load Decision Brief (after OB0004-R2.3)

**Scope:** S-Series (P3b S5) only; no F-Series material inspected or used.

**Nature:** decision evidence for the human; **no option is recommended or selected here**. Nothing is re-run and no batch is dispatched.

**Sources (repository artifacts):**
- `P3B-GOVERNANCE-LOG.md` G-LOG-0047…0051;
- `audit-p3b/S5-VERIFY-OB0004-R2.3.json` and `S5-QUOTES-OB0004-R2.3.json`;
- `ledger-p3b-r2/OB0004-R2.3/READ-LOG.jsonl`;
- contract revision 3 `prompts/20260925_1204_p3b-agent-contract-r2.md`;
- `scripts/p3b_read_source.py` (paged mode) and `scripts/p3b_s5_verify.py` (READ-COVERAGE);
- the root-cause report `audit-p3b/20260925_1138_…`;
- the S5 plan v2.3.2 and the manifest.

**Population load figures** were computed for this brief from the frozen inputs (`p3b_s5_prepare.Context`). A label's required whole-file load is the size of its stage-2 hit files (none for hubs; §11.4 hub exception) plus its OMQ-14 sources (row types CONTRADICTION, CORRECTION or RETRACTION).

## A. What we now know (measured)

| Quantity | Value | Source |
|---|---|---|
| OB0004 required whole-file set (distinct files, all labels) | 43 files, 1,908,151 bytes | G-LOG-0051 |
| Completely read in R2.3, page-proven | 18 files, 1,088,267 bytes (57 %) | G-LOG-0051; READ-LOG |
| Page bytes consumed, including repeats | 1,279,351 | READ-LOG |
| Agent tokens reported | about 865k (one context, claude-opus-5-5[1m]) | run completion |
| Labels completed | 4 of 5 | G-LOG-0051 |
| step-verify-programme | 1,709,096 bytes required; 889,212 read (11 of 36 files) | G-LOG-0051 |
| Labels needing more than 1.09 MB (the demonstrated completed reading) | **85 of 1,975 (4.3 %)** | computed |
| Labels needing more than 1.91 MB (OB0004's whole requirement) | 39 | computed |
| Label requirement: median / p90 / p99 / max | 38,975 / 525,987 / 3,169,408 / 8,586,148 bytes | computed |
| Batches needing more than 1.09 MB (label sums) | **129 of 396 (33 %)** | computed |
| Batch requirement: median / p90 / max | 604,482 / 2,991,285 / 8,695,198 bytes | computed |
| Largest single required file | **1,516,994 bytes** (18 labels have a required file over 600 KB) | computed |
| Registered batch limits (manifest packing) | stage 1 ≤ 1,801,465 bytes, stage 2 ≤ 8,633,127 bytes per batch | `p3b_s5_prepare.py` |

**Reading the table:**
- The packing limits were set on bytes, far above what one agent context completed in R2.3.
- The per-batch limits, rather than the methodology, are where the architecture assumption fails.
- **A single label can exceed one context** (85 labels do), and so can **a single file** (the largest is 1.52 MB). Decomposition by batch alone cannot remove either.

## B. What failed, kept separate

**Problem A: capacity.**
- One agent context did not complete OB0004's required whole-file reading: 57 % of the bytes were completed, and the rest was honestly escalated.
- The demonstrated completed reading in one context is about 1.09 MB of required files (1.28 MB of pages including repeats, plus the 1.29 MB of slices and the contract).
- This is **one measurement at about the 87th percentile of batch load**. It establishes that the current one-agent-per-batch architecture cannot execute at least that load. It does not establish a precise per-context ceiling.

**Problem B: contract, schema and agent.**
- **(B1) Schema gap.** A stage-2 hit file that was not read and was escalated as CONTRACT-DEVIATION has no legal disposition `method`: the closed set is WHOLE-FILE and STAGE-2A-2B. This produced 21 G-05 failures. The verifier treats `method` strictly, with no null-plus-SCHEMA-LIMITATION exception, unlike the object fields covered by G-LOG-0023 (b). This gap was exposed by the CONTRACT-DEVIATION route that revision 3 made explicit.
- **(B2) Agent omission, not a schema defect.** The `type_status` null lacked the SCHEMA-LIMITATION escalation that existing rule G-LOG-0023 (b) requires (the verifier message cites it). The rule exists; the agent omitted it.
- **(B3) Policy consequence (frozen rule, not a defect).** Under §19.4 ("the absence procedure is never thinned"), a non-hub label with an escalated stage-2 shortfall fails G-04, so the batch fails even when the shortfall is honestly declared. This is the frozen rule working as written. It connects Problem A to batch outcomes.

**Problem C: tooling.**
- The reader's redirect refusal (stdout is a regular file) is incompatible with the harness, which captures stdout into a file. It refused a legitimate call (1 refusal), and the agent used `| cat`.
- It cannot detect pipes either. The page-hash ledger proves that every page was delivered to the agent's process. It does not prove the agent consumed it; that residual has been recorded since G-LOG-0050.

## C. What did not fail

- **READ-COVERAGE worked.** 0 READ-COVERAGE failures, 0 page-hash failures and 0 partially read files: every whole-file claim was page-proven, and no delivered-only file was claimed.
- **Quotes PASS:** 88 exact, 0 misses.
- **Honest escalation:** 25 unread files were declared as CONTRACT-DEVIATION, not claimed.
- **Population unchanged:** 396 batches, 1,975 labels, 66 hubs, K = 64, A'1; the manifest composition is identical across revisions 1–3.
- **H-19 protected:** SEALED, never accessed; output quarantine 0/0; S5c PROHIBITED.
- **Earlier failures stay on record, unchanged:** R2 (contract defects) and R2.2 (execution violations).

## D. Remaining architectural choices (neutral)

**Option A: a larger context.**
- R2.3 already used the largest variant listed for the frozen model (claude-opus-5-5[1m], 1 million tokens).
- The plan freezes claude-opus-5-5 for every AI role (plan v2.3.2, line 21), so any other model is a §26 change.
- **Not verified here:** whether any larger context exists in this environment. None is known to the AI session.
- Even a larger context has to cover the 8.59 MB maximum label, so it must be sized against the p99 and maximum labels, not against OB0004.

**Option B: sequential continuation across sessions (one label, several agent sessions).**
- **Mechanically feasible today.** Page coverage is already counted per run across entries. A continuation needs a persisted per-label reading state (which files and pages are done) and a hand-off record.
- **Epistemic difference.** A later session does not have the earlier pages in view. It works from notes, and scratch notes are not verified evidence. §9.8 assumes one analyst performing the per-label procedure.
- **Required for integrity:**
  - structured, verifiable per-file reading records (dispositions, timeline facts, verbatim quotes), not free notes;
  - deterministic continuation order;
  - a final session that forms the label-level judgments;
  - READ-COVERAGE over the union of sessions.
- **Consequence:** with those requirements, B converges on C at file granularity.
- **Unique to B:** B can also continue **inside one file** across sessions, the only route for a file larger than a context (the largest is 1.52 MB). That is sub-file reading, which the whole-file rule does not currently contemplate.

**Option C: deterministic decomposition of a label into reading units.**
- **Units.** A label's required files (stage-2 files, OMQ-14 sources and any other mandatory evidence) are packed deterministically into units under a byte budget. **A file is never split.** Each unit is read by one agent, which reads every assigned file whole with the paged reader. It writes **file-reading records**: the stage-2 disposition for every hit key in that file; timeline facts from that file (date, basis, change class with verbatim quote); and OMQ-14 content.
- **Formal coverage property (demonstrable).** Let R(L) be the label's required files and U₁…Uₖ the deterministic partition of R(L). If every file in each Uᵢ has complete, hash-verified page coverage in sub-run i, then the union covers R(L) exactly. That is mechanically checkable, and duplicates and omissions are detectable because the partition is deterministic.
- **What stays identical to the frozen rule.** Every FOUND, WHOLE-FILE and GENUINELY-UNDEFINED basis is still made by an agent that read that file whole (§11.4, §A.6), and OMQ-14 files are read whole.
- **What changes (a §26 question).** Cross-file judgments are made by a synthesis agent from verified file-reading records, plus bounded, page-proven targeted re-reads, not by one analyst who read all files. These judgments are births across files, timeline order, D4 contradiction evidence, semantic status, and the dependency and status basis. Whether this preserves the epistemic guarantee **cannot be shown formally**; it is an empirical fidelity question (§E).
- **Limit.** C cannot handle a single file larger than one unit's capacity (the largest file is 1.52 MB) without B-style sub-file continuation.

**Option D: other architecture.** Not analysed. A–C leave a defined, testable question (§E).

| Option | What changes | What stays frozen | Evidence required | Risk | Operational cost |
|---|---|---|---|---|---|
| A. Larger context | Model or context variant (plan freezes claude-opus-5-5 → §26) | methodology, gates, population, whole-file rule | a verified larger context; a run on a p99 or maximum label | not available or unverified; capacity must cover an 8.59 MB label; model change affects comparability | low process change; unknown availability |
| B. Sequential continuation | per-label multi-session execution; persisted reading state; hand-off records; possibly sub-file continuation | gates, READ-COVERAGE (union), population | continuation integrity (no gaps or duplicates); fidelity of judgments made from records; sub-file reading legitimacy for files over one context | judgments from notes, not direct reading; sub-file reading is outside the current whole-file rule | more sessions per heavy label; state management |
| C. Label decomposition | label = deterministic units of whole files + a synthesis step; new record type (file-reading record); §26 annex | whole-file rule per file, READ-COVERAGE (union), gates, population, K, A'1 | coverage-union proof (mechanical); **fidelity of synthesis versus single-analyst results** (empirical); a unit budget | cross-file judgment from records may lose information; files over a unit's capacity still need B | k + 1 agents for heavy labels (only 85 labels exceed 1.09 MB); new tooling and tests |

## E. Smallest discriminating experiment (a proposal for the human; not launched)

**Question it answers.** Does label decomposition (C) satisfy the frozen epistemic requirement? Specifically: are the synthesized label records as good as single-analyst records where both exist, and does decomposition complete a label that one context cannot?

**Population:** two labels from OB0004, run in a separate, non-production experiment namespace. The results are never accepted as P3b records (like COMPARISON runs).
- **Control:** `knowledgeos-architecture-constitution-v01`. Its 3 required files (249,219 bytes) were read completely in R2.3, so the single-analyst object exists.
- **Target:** `step-verify-programme`: 36 required files, 1,709,096 bytes, not completable in R2.3.

**Input:** the revision-3 slices of the two labels; the contract text; the paged reader.

**Procedure:**
1. Deterministic unit partition per label under a declared budget, for example ≤ 600,000 bytes per unit. That is about half the demonstrated completed reading; files are never split.
2. One agent per unit writes file-reading records with page-proven coverage.
3. One synthesis agent per label writes the label object from the records, with bounded, page-proven re-reads.
4. The verifier checks the union, followed by an independent §21-style audit.

**Output:** file-reading records per unit, the synthesized objects, a coverage-union report, and for the control label a field-by-field comparison with R2.3's single-analyst object (exact / coarse / different, each difference dispositioned by an independent auditor).

**Success criteria (all must hold):**
- every required file of both labels is covered completely in the union (0 missing pages, 0 hash failures);
- the synthesized objects pass the verifier;
- the independent audit finds no PROTOCOL-VIOLATION;
- on the control label, no difference is dispositioned AUDIT-UPHELD against the synthesized object on a status, birth, absence or semantic field.

**Failure criteria (any):**
- a unit cannot complete its files within budget, which indicates file-level or unit-level capacity limits and points toward B or sub-file reading;
- the control label's synthesis is AUDIT-UPHELD-wrong on a judgment field, which indicates information loss in synthesis from records;
- the coverage union has gaps or duplicates.

**Decision it enables.** It shows whether C can be adopted through a §26 annex as preserving the guarantees, or whether B (including sub-file continuation) or A, where available, must be considered. It tests nothing about the 1.52 MB-file case. If the human wants that answered too, a second, smaller probe is one unit agent reading the largest required file alone.

## F. Human decisions required

1. **Load architecture:** A, B, C, D, or authorize the §E experiment first.
2. **Schema gap B1:** how an escalated, unread hit file is represented, either a new `method` value or the G-LOG-0023 (b) null-plus-SCHEMA-LIMITATION rule extended to `method`. Either is a contract change (§26).
3. **Tooling C:** withdraw or replace the redirect refusal, keeping the page-hash ledger. A tooling change, but it alters contract §A.2 text.
4. **§19.4 consequence (B3):** confirm that an honestly escalated stage-2 shortfall on a non-hub label remains a batch failure (the frozen reading), or rule otherwise under §26.
5. **Whether the 1.52 MB single-file case** needs its own probe.

## G. S-Series state (from `P3B-STATE.json` and the governance log)

- **OB0004:** FAILED. Runs R2 (revision 1), R2.2 (revision 2) and R2.3 (revision 3) are all FAILED and immutable.
- **Other batches:** 395 PREPARED, **none authorized**.
- **Manifest:** revision 3 (contract `prompts/20260925_1204…`, output `d3e2a29e…`), composition identical to revision 1. The state has 2 recorded manifest revisions.
- **Authorization:** none open. G-LOG-0050 authorized OB0004-R2.3 only, and it has run.
- **H-19:** SEALED (HS-3d32dd44d162), never accessed. **S5c:** PROHIBITED (P3B-ESC-0001).
- **Another production batch authorized:** **no**.
