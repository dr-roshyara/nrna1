# V1 instrument validation: pre-registration (frozen before any V1 agent runs)

**Authority:** human decision "S-SERIES — HUMAN DECISION / NEXT MOVE" (2026-09-25), recorded as G-LOG-0060:
- `S-SERIES-NEXT-PILOT-DESIGN-v2.md` is accepted as the design of record, and the v1 pilot design is superseded;
- V1 **pre-registration only** is authorized. **Execution is NOT authorized.**

**Isolation:**
- Namespace: batch `PX0106`. Runs `PX0106-U01…U06` (extractors), `PX0106-A01…A02` (matchers). Artifacts go to `pilot-s5-decomp/v1/`.
- Non-production: no manifest, contract, state or verifier change.
- Not touched: H-19 (SEALED), S5c (PROHIBITED), OB0018, and the v2 four-label pilot (namespace `PX0105`, reserved and not used here).
- Unchanged: Gate C evidence and G-LOG-0058.
- S-Series only. No F-Series material is used.

**Tooling (frozen with this document):**
- `scripts/p3b_v1_instrument.py` implements §5, §6, §9, §11–§17 mechanically. Its execution sub-commands refuse unless `pilot-s5-decomp/v1/V1-AUTHORIZATION.json` names this document's commit and sha256.
- `scripts/tests/test_p3b_v1_instrument.py` (16 synthetic tests; no corpus).
- **Nothing in this document, the tool or the tests was produced by reading corpus content.** The file facts in §2 come from committed read logs (metadata only).

**Threshold status:** every numeric decision value below is an **ARBITRARY engineering decision rule**, not a scientifically established threshold, except the two hard integrity requirements (segment coverage = 1.0; quote verification).

---

## 1. Objective question

Are the two measurement instruments proposed in design v2 reliable enough to use in the v2 pilot?
- **(a) The segment-level proposition inventory (§II.12).** Can complete segment coverage and verbatim quote verification actually be achieved on real files? Does the inventory capture what two independent open-ended extractors agree on?
- **(b) The matching procedure (§II.13).** Do two independent matchers agree sufficiently?

V1 returns exactly one of **INSTRUMENTS-VALID**, **NEEDS-REVISION** or **NOT-USABLE** (§17).

## 2. The six files (fixed) and their provenance

**Selection rule** (metadata only; applied to committed read logs):
- For each pilot label, take the files that the decomposition-pilot reading unit read completely with page proof: `PX0004-U01` (constitution) and `PX0004-U02` (step-verify).
- Exclude the one file read under both labels.
- Sort by decoded character length, and take the **smallest, the middle (3rd of 5) and the largest**.

| Source | Label | content sha256 | Pages | Chars | Read with page proof in |
|---|---|---|---|---|---|
| S1022 | knowledgeos-architecture-constitution-v01 | `2ccee923f1350a7e199689d442514f5e69797ccd33bf142bd9036817dadae0e2` | 1 | 15,541 | PX0004-U01 |
| S1021 | knowledgeos-architecture-constitution-v01 | `afca77203bf2429dc8179db857bc13f25c6844c32988ff86065931c3133fabbf` | 2 | 20,086 | PX0004-U01 |
| S1484 | knowledgeos-architecture-constitution-v01 | `2e5ee4495c2f84c079d76242583d0153c719e9c79864f0f5c070721c9f1fecba` | 3 | 59,434 | PX0004-U01 |
| S1413 | step-verify-programme | `05a3b0100444bb07c2392c5563b8e4ed8acf7f11f91ab16a5964ddcacabdf639` | 2 | 24,219 | PX0004-U02 |
| S1521 | step-verify-programme | `25decfad7a09927f1a6a28f8dedd6f6422d4808c0db1e9b66dbdd0b6d0420bbe` | 2 | 30,455 | PX0004-U02 |
| S1541 | step-verify-programme | `0695b63b877d1cab182c99c8d8d9f27af1398c871568c727b8392dda930a3d25` | 9 | 174,052 | PX0004-U02 |

- **Total:** 323,787 characters, 19 pages.
- **Read-log provenance:**
  - `PX0004-U01/READ-LOG.jsonl` sha256 `800237aa…a8ae`;
  - `PX0004-U02/READ-LOG.jsonl` sha256 `6ae9c400…be99`;
  - committed in `3645fbca2` (G-LOG-0053).
- **Tool check:** `p3b_v1_instrument.py` refuses to run if a file's manifest content hash or decoded length differs from this table.
- **Disclosed overlaps** (not a reuse of results):
  - Gate C's R2 unit read S1021, S1022 and S1484;
  - Gate C's auditor read S1413, S1484 and S1521;
  - the orchestrator (this author) has seen pilot outputs citing these files.

  **V1 uses no Gate C or pilot result, register or verdict.** It only re-reads files already exposed.
- The selection rule was chosen after the file sizes were visible (metadata). This is disclosed as an investigator degree of freedom.

## 3. Models (exact)

A **genuinely different-vendor model is NOT available** in this environment. The only agent models reachable are the Agent-tool aliases `opus`, `sonnet`, `haiku` and `fable`, all Anthropic. **No substitute is invented.** The limitation is recorded in §4 and §21.

| Run | Role | Label scope | Model alias | Model id (environment) |
|---|---|---|---|---|
| PX0106-U01 / U02 | **SEG**: segment-inventory extractor | constitution / step-verify | `opus` | claude-opus-5-5 |
| PX0106-U03 / U04 | **E1**: open-ended extractor | constitution / step-verify | `fable` | claude-fable-5-1 |
| PX0106-U05 / U06 | **E2**: open-ended extractor | constitution / step-verify | `haiku` | claude-haiku-4-5-20251001 |
| PX0106-A01 | **M1**: primary matcher (clustering + all decisions) | all | `sonnet` | claude-sonnet-5 |
| PX0106-A02 | **M2**: second matcher (sample) | all | `fable` | claude-fable-5-1 |

- Each agent reports its model id in its output header. **A mismatch with this table is a stop condition (§18).**
- The orchestrator is claude-opus-5-5 and runs **no** extraction or matching judgment.

## 4. Independence assumptions and limitations

**Assumed:**
- the extractors do not see each other's outputs (enforced by prompt, by parallel dispatch, and detected by canaries §15);
- the matchers do not know which list is which source (structure-equalized and letter-blinded, §11);
- M2 does not see M1's decisions.

**Not assumed:**
- **model independence:** all models are the same vendor, so errors may be correlated;
- that the matchers are free of source-recognition from style.

**Known violations:**
- **M2 = the E1 model:** M2 may favour E1-authored items;
- **E2 (haiku) is a smaller model:** it may lower agreement and the size of the agreed set;
- **Agent file access** is restricted by prompt and self-report. The read logs prove corpus reads only. A non-corpus file opened with a generic tool is not logged; the canaries detect only copied content.

## 5. Deterministic segmentation algorithm

The algorithm is `segment()` in the frozen tool. It runs on the decoded content: UTF-8 with replacement, identical to the paged reader.

1. **Split** the content into lines, keeping line ends; character offsets are cumulative.
2. **Fences:** a line whose left-stripped form (≤ 3 leading spaces) starts with ```` ``` ```` or `~~~` toggles fence state. Fence lines and lines inside a fence are never headings or cut points.
3. **Headings:** a line matching `^#{1,6}[ \t]+\S` outside a fence is a heading. Segment boundaries fall at offset 0 and at every heading line start. The first segment is `PREAMBLE` unless the file starts with a heading.
4. **Long segments:** any segment longer than **MAX_SEG = 6,000** characters (**ARBITRARY engineering value**) is subdivided greedily. The cut point is the last paragraph cut point (the start of a non-fenced line that follows a blank line) within `(cur, cur + 6000]`. If there is none, the hard cut is at `cur + 6000`. The pieces after the first are `SPLIT`.
5. **Whitespace:** a whitespace-only segment is merged into the previous segment, or into the next if it is first.
6. **Invariant (checked):** segments tile `[0, len)` contiguously. An empty file gives one `EMPTY` segment.
7. **Ids:** `<S-id>-g<NNN>`, 1-based, in order.

## 6. Segment schema (`v1/SEGMENT-MAP.json`)

`{segment_id, source_id, char_start, char_end, page_first, page_last, kind ∈ {PREAMBLE, HEADING, SPLIT, EMPTY}, heading_level, segment_sha256, open_text}`

- Pages are 20,000-character reader pages.
- `open_text` is the first 80 characters of the whitespace-normalized segment. It exists only to let the SEG extractor align segments with reader pages.
- The map is produced at execution (checker read via the resolver, not an agent read) and **committed before any agent runs**.

## 7. Proposition schema (SEG output `v1/OUT-PX0106-U0{1,2}.jsonl`)

**Line 1:** header `{run_id, model_self_report, canary}`.

**One record per segment:**
`{segment_id, source_id, result ∈ {PROPOSITIONS, NO-SUBSTANTIVE-PROPOSITION}, reason (required if NO-SUBSTANTIVE), propositions: [...]}`

**Proposition:**
- `proposition_id`
- `proposition_type` ∈ {DEFINITION, NOTATION, CLAIM, RULE, FORMULA, THEOREM-OR-RESULT, ALGORITHM, EXAMPLE, HYPOTHESIS, OPEN-QUESTION, CORRECTION, CONTRADICTION, RELATION, LINEAGE, GOVERNANCE, METHOD, SCOPE}
- `statement` (own words)
- `quote` (verbatim, inside the segment)
- `status` ∈ {ASSERTED, EXAMPLE, HYPOTHETICAL, RETRACTED}
- `parent_proposition` (id or null)
- `relation_target` (for RELATION)
- `explicit` (boolean, required for RELATION)
- `first_occurrence` (id of an earlier proposition it repeats, or null)
- `register_decision` ∈ {PROMOTE, DECLINE}
- `decline_reason` ∈ {RESTATEMENT, EXAMPLE-ONLY, NON-MATERIAL, OUT-OF-LABEL-SCOPE} (required for DECLINE)

There is **no "other" class**: off-scale values are schema violations.

**Open-ended output (E1/E2 `v1/OUT-PX0106-U0{3..6}.jsonl`):** header line as above, then `{item_id, source_id, statement, quote}`.

## 8. Extraction instructions (verbatim prompts)

- Placeholders in `<>` are filled mechanically: run id, label, files, canary.
- The canary is `canary(commit, run)` from the tool: `CANARY-` plus 12 hex characters derived from this document's commit hash and the run id.

**Prompt P-SEG (runs U01, U02):**
```
You are an extraction agent in a non-production instrument-validation experiment (run <RUN>, batch PX0106, label <LABEL>).
Canary: <CANARY>. Write it in your output header exactly; never write any other CANARY- string.
Files (read each file completely, every page, only through the paged reader):
  python3 scripts/p3b_read_source.py --run <RUN> --batch PX0106 --label <LABEL> --step 10 --page K S####
  <S-id list with page counts>
You are given pilot-s5-decomp/v1/SEGMENT-MAP.json (segments of these files; char offsets, pages, open_text).
Do not open any other file or directory under docs/knowledgeos except that map and your own output file; do not read
other outputs in pilot-s5-decomp/, the governance log, audit-p3b/, registers, or any Gate C or pilot material.
Task: for EVERY segment of your files, write exactly one record to pilot-s5-decomp/v1/OUT-<RUN>.jsonl:
  either result PROPOSITIONS with every proposition the segment asserts, defines, exemplifies, hypothesises, asks,
  corrects, contradicts, relates, cites or decides, or result NO-SUBSTANTIVE-PROPOSITION with a reason.
Record schema, types, statuses and decline reasons: <§7 of the pre-registration, pasted verbatim>.
Rules: quote verbatim from inside the segment; quote formulas and notation exactly; link a repeated definition to its
first occurrence; express nesting with parent_proposition; an implicit relation only as RELATION with explicit=false
and the quote that implies it; multi-file claims are out of scope. Line 1 of the output: {"run_id": "<RUN>",
"model_self_report": "<your exact model id>", "canary": "<CANARY>"}.
Finally report: pages read, segments written, any file you opened other than those allowed.
```

**Prompt P-OPEN (runs U03–U06):**
```
You are an extraction agent in a non-production experiment (run <RUN>, batch PX0106, label <LABEL>).
Canary: <CANARY>. Write it in your output header exactly; never write any other CANARY- string.
Files (read each completely, every page, only through the paged reader):
  python3 scripts/p3b_read_source.py --run <RUN> --batch PX0106 --label <LABEL> --step 10 --page K S####
  <S-id list with page counts>
Do not open any other file or directory under docs/knowledgeos except your own output file.
Task: extract every distinct substantive proposition these files contain: what they claim, define, decide, propose,
question or relate, including small ones. Use your own judgement of what counts; no categories are given.
Write pilot-s5-decomp/v1/OUT-<RUN>.jsonl: line 1 {"run_id": "<RUN>", "model_self_report": "<your exact model id>",
"canary": "<CANARY>"}; then one line per proposition {"item_id", "source_id", "statement" (your words),
"quote" (verbatim from that file)}. Finally report pages read, items written, any other file opened.
```

**Repair prompt (one round per run, §9):**
```
The checker found these failures in pilot-s5-decomp/v1/OUT-<RUN>.jsonl: <list of segment_id / item_id with failure class
only: MISSING-SEGMENT | SCHEMA:<field> | QUOTE-MISS | QUOTE-OUT-OF-SEGMENT>. Re-read the relevant pages through the
paged reader if needed and write replacement records (same key) to pilot-s5-decomp/v1/OUT-<RUN>.repair.jsonl, or
{"<key>": "...", "withdrawn": true} to withdraw a record. Change nothing else.
```

**Prompt P-M1 (run A01):**
```
You are the primary matcher (run PX0106-A01). Canary: <CANARY>; write it in your output header; no other CANARY- string.
Input: pilot-s5-decomp/v1/MATCH-LISTS.json only: three lists X, Y, Z of propositions {item_id, source_id, statement,
quote} extracted independently from the same six files. You are not told how the lists were produced; do not open
SOURCE-KEY.json or any other file under pilot-s5-decomp/, audit-p3b/, or the governance log. You may read the six
files via the paged reader: --run PX0106-A01 --batch PX0106 --label <file's label> --step 10 --page K S####.
Step 1 (clusters): within each list and each source_id, group items stating the same proposition into clusters.
  Write pilot-s5-decomp/v1/CLUSTERS-A01.jsonl: header {"run_id","model_self_report","canary"}; then
  {"cluster_id", "list", "source_id", "members": [item_id,...]}.
Step 2 (decisions): for every cluster and each of the two other lists, find the best-matching cluster of the SAME
  source_id in that list and decide MATCH (same proposition), PARTIAL (overlapping: one is narrower, broader, or
  covers part of the other), or NONE. Judge meaning and evidence, not wording.
  Write pilot-s5-decomp/v1/DECISIONS-A01.jsonl: header line; then {"cluster_id","other_list","status",
  "target_cluster" (null if NONE),"note"}.
```

**Prompt P-M2 (run A02):**
```
You are an independent second matcher (run PX0106-A02). Canary: <CANARY>; header as usual; no other CANARY- string.
Input: pilot-s5-decomp/v1/MATCH-LISTS.json, pilot-s5-decomp/v1/CLUSTERS-A01.jsonl (clusters are given; do not
re-cluster), pilot-s5-decomp/v1/SAMPLE-A02.json (the cluster ids you must decide). Do not open DECISIONS-A01.jsonl,
SOURCE-KEY.json, or anything else under pilot-s5-decomp/, audit-p3b/, or the governance log. You may read the six
files via the paged reader with --run PX0106-A02.
For every sampled cluster and each of the two other lists: best-matching cluster of the same source_id, and
MATCH / PARTIAL / NONE as defined: MATCH same proposition; PARTIAL overlapping (narrower, broader, part); NONE.
Write pilot-s5-decomp/v1/DECISIONS-A02.jsonl: header line; then {"cluster_id","other_list","status",
"target_cluster","note"}. Optionally list clustering objections in the note ("SPLIT" or "MERGE-WITH:<id>").
```

## 9. Quote-verification procedure

This is `quote_status()` in the tool. Every quote is checked against the manifest content via the resolver:
- EXACT, or WHITESPACE (after collapsing whitespace) = verified;
- for SEG, the quote must lie **inside its segment's range**, else OUT-OF-RANGE;
- MISS = not in the file.

**Hard integrity requirement:** zero OUT-OF-RANGE and zero MISS after at most **one repair round** per run. The repair gives failure ids and classes only. Pre-repair counts are reported.

## 10. Matching unit

A **cluster**: M1's grouping of the items of one list (source) and one file that state the same proposition. Matching is file-local: same `source_id` only.

The **decision unit** for κ is the pair (sampled cluster, other list). Each has a status ∈ {MATCH, PARTIAL, NONE} and one target cluster.

## 11. Matching protocol

1. **Equalize (tool `equalize`):** all three outputs are reduced to `{item_id, list, source_id, statement, quote}`.
   - SEG's type, status, segment and decision fields are dropped.
   - Item ids are hashed from (commit, source, original id).
   - List letters are a seeded permutation of X/Y/Z.
   - Items are sorted by (source_id, item_id), which removes segment order.
2. **Seal the key:** `SOURCE-KEY.json` is written but **not committed** until M2 has finished. Only its sha256 is committed with the lists.
3. **M1** clusters and decides all clusters (P-M1). The output is committed.
4. **Sample** (tool `sample`, §12). **M2** decides the sample (P-M2), blind to M1's decisions. The output is committed.
5. The key is committed, then `result` runs.

## 12. Second-matcher sampling rule

- **Seed:** `int(first 8 hex digits of this document's commit hash, 16)`.
- **Strata:** (source_id, list) over M1's clusters.
- **Per stratum:** n = min(N, max(5, ⌈0.30·N⌉)). The values 5 and 30% are **ARBITRARY engineering values**.
- **Draw:** `random.Random(seed).sample(sorted cluster ids, n)`, strata in sorted order (tool `draw_sample`).

## 13. κ calculation

The tool `kappas` computes Cohen's κ over the sampled decision units.

- **κ_target (primary):** label = `NONE`, or `STATUS:target_cluster`. Agreement requires the same status **and** the same target.
- **κ_status (diagnostic):** status only.
- **Formula:** κ = (p_o − p_e)/(1 − p_e), with p_e = Σ_c p₁(c)·p₂(c) over the labels used; κ = 1 if p_e = 1.
- Units missing in M2 are counted and reported. They are excluded from κ.
- **κ measures matcher agreement conditional on M1's clustering.** It is **not** evidence of semantic extraction completeness.

## 14. Structural coverage calculation

- **Per file:** coverage = (segments with exactly one SEG record) / (segments in the map).
- Unknown segment ids and duplicate records are reported as schema violations.
- **Hard integrity requirement:** coverage = 1.0 for all six files after at most one repair round.

## 15. Hard integrity conditions

A breach of any of these makes the run **invalid**:
- **Access:**
  - each extractor's read log shows every page 1..N of each file of its label;
  - no read outside the six files;
  - no refused read;
  - every logged page hash recomputes.
- **Canaries:** each output header carries its own canary; **no output contains another run's canary**.
- **Model identity:** matches §3.
- **Content identity:** file content hashes and lengths match §2.
- **Frozen inputs:** the segment map was committed before any extractor ran; the lists and key hash were committed before M1; M1 was committed before the sample and M2.

The two **hard instrument requirements** are separate from run validity: segment coverage = 1.0, and quote verification with 0 misses. They decide between INSTRUMENTS-VALID and NEEDS-REVISION or NOT-USABLE (§17).

## 16. Diagnostic metrics (reported; none decisive except as named in §17)

- clusters per source and file;
- pairwise and three-way overlap tables;
- **capture of the E1∩E2-agreed set by SEG:** strict (MATCH) and lenient (MATCH or PARTIAL), where "agreed" = E1 clusters that M1 matched (MATCH) to an E2 cluster;
- **Chapman two-source estimate E1–E2**, which is **diagnostic only**: its independence assumption is not established (same vendor);
- κ_status and raw target agreement;
- M2 clustering objections;
- SEG NO-SUBSTANTIVE rate;
- type, status and PROMOTE/DECLINE distributions;
- repairs per run;
- pages and bytes read per run;
- wall time.

## 17. V1 decision rules (engineering rules; every number ARBITRARY except the hard requirements)

The tool `decide` applies them in this order:
1. **Integrity breach (§15):** → **NEEDS-REVISION**, flagged **RUN-INVALID**. No instrument conclusion is drawn, and a re-run needs a new authorization.
2. **NOT-USABLE** if any of:
   - min segment coverage < 0.95 after repair (complete coverage is impractical);
   - a quote miss rate > 5% for any run after repair;
   - κ_target < 0.40 or not computable;
   - capture_lenient < 0.50 or not computable.
3. **INSTRUMENTS-VALID** iff all of:
   - schema clean;
   - coverage = 1.0 (hard);
   - quote misses = 0 (hard);
   - κ_target ≥ 0.60 (ARBITRARY engineering gate);
   - capture_lenient ≥ 0.80 (ARBITRARY).
4. **Otherwise NEEDS-REVISION**, listing the failed conditions.

**What the outcomes mean:**
- **INSTRUMENTS-VALID** means only that the instruments met pre-set engineering rules on six files. It is **not** a scientific validation, and it proves no extraction completeness.
- Overlap and capture–recapture remain **diagnostic**.

## 18. Stop conditions (stop immediately, record, report; no further agent)

- any hold-out refusal or content-hash mismatch;
- any read outside the six files;
- canary leakage;
- a model-identity mismatch;
- an agent's self-report of opening a forbidden path;
- an agent unable to complete (e.g. context exhaustion). This is recorded, and the outcome is NEEDS-REVISION with the reason.

No automatic continuation to the v2 pilot.

## 19. Provenance and hashes

- **This document:** its commit hash is the root of the chain. It seeds the sample, the list permutation and the canaries, and `V1-AUTHORIZATION.json` must name it together with this file's sha256.
- **Tool and tests:** committed in the same commit. Their git blob hashes are recorded in the V1 report.
- **Every V1 output:** committed with its sha256 at each freeze point of §11.
- **Read logs:** `pilot-s5-decomp/PX0106-*/READ-LOG.jsonl`, written by the reader itself.

## 20. Reproducibility requirements

- **Deterministic and re-runnable:** segmentation, equalization, the sample, κ, capture, the decision, and the checks, all from the committed tool and inputs.
- **Stochastic:** the LLM stages. The prompts are fixed verbatim here, and the inputs are frozen and committed. Outputs are not expected to reproduce exactly.
- Any deviation from §5–§17 during execution is a **protocol deviation**. It is recorded, and it voids an INSTRUMENTS-VALID outcome.

## 21. Explicit limitations

- **Scale:** six files, two labels, previously exposed files.
- **Vendor:** a single vendor. No different-vendor auditor or extractor is available, so the limitation is preserved rather than substituted.
- **Model overlaps:**
  - M2 shares a model with E1;
  - E2 is a smaller model.
- **κ scope:** conditional on M1's clustering.
- **Capture measures only what both open-ended extractors found.** Propositions all three sources miss are invisible.
- **Arbitrary values:** the segment size, the sample fraction and all gates.
- **Access enforcement** beyond the read logs relies on prompts and self-report.
- **The file selection rule was chosen after file sizes were visible.**

## 22. What V1 does not test

- V1 does **not** compare A0 and B2 or any strategy.
- It does **not** measure discovery, trigger or candidate-generation loss.
- It does **not** establish corpus-wide research-extraction completeness, or completeness for any label.
- It does **not** establish model independence.
- It does **not** authorize or imply the v2 pilot, OB0018, production, H-19 access or S5c.

---

## Next-step authorization required

For a human to execute V1, the authorization should state, for example: **"AUTHORIZE V1 EXECUTION under pre-registration `<commit>`"**. It may also confirm the §3 models or instruct otherwise.

**On authorization, the orchestrator:**
1. writes and commits `pilot-s5-decomp/v1/V1-AUTHORIZATION.json` `{prereg_commit, prereg_sha256, authority}`;
2. runs `segment` and commits the map;
3. dispatches U01–U06 in parallel;
4. runs `check`, with one repair round;
5. runs `equalize` and commits the lists and the key hash;
6. runs M1 and commits;
7. runs `sample`, then M2, and commits;
8. commits the key, runs `result`, writes the V1 report, and stops.
