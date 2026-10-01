# S5 canary evaluation gate: diagnosis and decision package (v4)

> **v4 change note.** Review G of v3 (`canary-diag/PKG-REVIEW-G.md`) returned ACCEPT-WITH-FIXES, with 13 MATERIAL findings, all accepted. The orchestrator spot-checked G1 (R7:141), G4 (W:263-266), G7 (R7:186) and G12 (orch:57) against the sources.
> - **Where the fixes live.** They are applied in place where a statement was wrong (§1, §2, H1, H3, §4, §5, §6). They also appear as binding engineering constraints in **§3a**.
> - **Independence caveat.** Review G ran in an existing session, not a fresh one. Its findings were re-derived from the sources, so they are used.
> - **Read-discipline note.** The §1 figures for rows 41 and 23 and for F3 rest on count-only scans that protocol §22 now forbids. They are **provenance-limited**: usable as orientation, and not to be re-run without a human act.

| | |
|---|---|
| **Kind** | ⚠ authority: generated. One decision package for the human at the canary-evaluation gate (G-LOG-0108). **It authorizes nothing by itself** |
| **Version** | v3 supersedes v2 (`ce35975a6`) and v1 (`52c1ba687`). v3 incorporates: review E of v2 (ACCEPT-WITH-FIXES, 6 MATERIAL); the complete field inventory; and review F of that inventory (RELIABLE-WITH-CORRECTIONS). All material findings are accepted |
| **Evidence** | `audit-p3b/20260930_S5-CANARY-RESTART-EVALUATION.md`; verify reports `S5-VERIFY-OB0012-R7.A2.json`, `S5-VERIFY-OB0114-R7.json`; `canary-diag/`: DIAG-A, DIAG-REVIEW-B, PKG-REVIEW-C, -D, -E, FIELD-INVENTORY, INVENTORY-REVIEW-F |
| **Read discipline** | No reviewer or builder opened a run output, slice, view or the archive, and none printed an identifier. **Deviation, recorded:** the inventory builder and reviewer F ran count-only probes over corpus-derived data (ledger hit anchors; hold-out-name and pointer-phrase occurrence in corpus file bytes) through the verifier's own helpers. This went beyond the orchestrator's "no corpus reads" instruction. No content or identifier was printed; only the counts are used here |
| **Scientific status** | **No S5 label has become measured data (0 of 1,975).** Nothing here is evidence for or against H1–H5 |

## 1. Finding

**Root cause.** The diagnosis was re-derived by reviews B and D (reviews of the same documents and code, not independent replications).
- R7 restricts the agent to I(run): rev3, R7, the plan and the slice.
- R7 nevertheless takes the agent-output schemas from R5/R6 **by reference** (R7:13, R7:892), and R5/R6 are not in I(run).
- Fields the verifier requires therefore appear in no text the agent may read.

**Canary quantification** (B and D; D's 13 regexes classify all 340 strings, 0 unclassified):

| Figure | Strings | Share |
|---|---|---|
| Provably unreachable (F2 ack tokens, F4 pair checks, F5 S3-LINT, F6 edge_class) | 107 | **31.5%** |
| + F1 stage-2 ids with `\u` escapes | 140 | 41.2% |
| Upper bound (all of F1 and F8) | 294 | 86.5% |
| Remainder classed A: lenient / strict | 46 / 42 | 13.5% / 12.4% |

- **Agent-error spread.** A strings touch 7 of 11 runs (lenient) or 5 of 11 (strict); the stop threshold is 6.
- **F1 cannot be split further.** One template covers eight conditions (`evidence.py:163-177`).
- **Not supported by the evidence** (discriminating evidence, not a test; D §14): line truncation, context pressure, and model behaviour as the X cause.
- **Not yet measured:** contract read coverage (computable from the witness by an authorized session).

**Inventory** (`FIELD-INVENTORY.md`, corrected by F). It covers 85 fields and rules on agent-authored artifacts along the R7 verifier path (call graph confirmed by F). Status: **48 SPECIFIED / 19 NAME-ONLY / 10 ABSENT / 8 CONTRADICTORY**, so **37 not fully specified**. F adds one missing row: the file-reading-records layout as a whole.

**New defect classes** beyond the canary's families (inventory rows and F findings):

| Class | Rows | Nature | Curable by text? |
|---|---|---|---|
| Uncomputable values under W8 | 74 (`test_plan_sha256` for TEST-DEFINED, i.e. the pre-registered outcome kinds), 67 (lint sha) | the agent has no hashing tool; the verifier checks only the format, so **only a fabricated hash passes** | no |
| Mislabelled or derivable bindings | 3 (`contract_sha256` = rev3 sha, while R7:122 says the addendum), 44/45 (precedence and `position` need `order_evidence`, which the slice lacks; 167 of 1,167 EXPLICIT files are affected), 14, 51, 64–66 | values a tool already knows | no; derive them |
| Typing traps | 22 (S-ids in rev3 free-text fields have no registry row, so they become R7-U), 18 (every S-id in a births value becomes a BIRTH claim), 23 (17 must-copy hit anchors contain an S-id; this affects 2 non-hub S5 labels, where R7-U is **unavoidable**) | closed-world typing versus fields that rev3 lets carry S-ids | partly |
| Seal asymmetry (F3, new) | W8 SEAL ignores the per-label allowed-mention lists that S3 honours. For 15 S5 labels, writing an allowed token passes S3 but **stops the batch** under W8 | verifier inconsistency | no |
| Contract contradictions | 28/29 (rev3's method enum vs R7: `NOT-CONSUMED-ESCALATED` is missing from §D; STAGE-2A-2B needs a scanner that W8 forbids), 83 (rev3 scratch dir), 66 (the prompt tells the synthesis run to use its own run id, which fails) | contract and prompt disagree with the code | yes, with a code alignment |
| Robustness | 68: malformed agent JSON raises an exception instead of BATCH-FAIL | verifier defect | no |
| Unstated rules | 81 (Read past EOF → W8), 84 (a reader call after the last Write → W5), 21, 22 | the code enforces them, the contract never states them | yes |
| Reverse direction | R-1…R-10: contract rules the code never checks (e.g. `summary:` claims, S1 quote anchoring, duplicate claim ids last-wins) | the code is weaker than the contract | adjudicate |

- **Corrected by F.** Row 41 ("verbatim quotes trip SEAL on hold-out names") is **refuted by counts**: 0 of 2,734 non-hold-out files contain a hold-out name or hold-out S-id. No reader masking is needed.
- **Neutralised in practice.** Rows 77 and 83 are neutralised by the prompt's exact write list.
- **Residual trap.** A quote that spans a pointer phrase (31 of 2,734 files contain one) is an unavoidable S3 layer-A hit (row 38).

**Recommended adjudication** (it takes effect only when the human approves; the evaluation left X versus A undecided):
- **Dominant cause:** an instrument/contract defect (X).
- **Recorded classes stay:** the mechanical classes A1/A2 are append-only.
- **Repair exceeds RR-6,** which admits only runbook and prompt repairs, so it needs human acts.

## 2. The strategic fork (the human's choice)

| Option | What | For | Against |
|---|---|---|---|
| **P: patch** | Annex the about 20 EXPOSE rows into R7; keep the agent-attested values | smallest code change; keeps the 130+ R7 acceptance tests | cannot cure the uncomputable values (74, 67), the mislabelled bindings (3), the seal asymmetry, or the typing traps (23); the agent still has to copy and attest tool-known values; the agent-side rule surface grows (rev3 1,358 + R7 954 lines) |
| **H: hybrid (recommended)** | **Derive** every tool-knowable value; **expose** the remaining rules in a contract-first annex; **fix** the code-level defects (H3, now 6 items) | removes most of the "agent attests what a tool knows" class (rows 14, 51, 64–67, 74; precedence only in part: `date_applies_to_file` and later_* membership stay agent assertions, see H1); every row text cannot cure gets a code fix | touches the evidence and witness code paths, so their tests and golden hashes change; needs re-audit |
| **R: redesign** | The agent emits a minimal observation record (quotes, readings, dispositions); tools derive ids, hashes, claims, precedence, typing | removes the cross-artifact rule class at its root (F: comparing visible text with code keeps finding such rules) | reopens witness, evidence and typing code with 130+ numbered tests; the largest re-audit; delays everything most |

**Why H.**
- About 20 of the 37 rows are plain missing text, which an annex fixes.
- About 10 rows are values a tool computes better.
- 5 rows need code under any option.
- R's extra benefit over H is the residual typing-trap class (rows 18 and 22). Within H, this needs **code**, not registry rows: row 18 is claim enumeration (U:340-343). It is therefore added as H3 item 6.

## 3. Repair design under H

**Principles.**
- Agents attest nothing a tool can derive.
- The contract stays the sole authority (`universe.py:4-6`): the code loads the annex, and the annex is never generated from the code.

**H1. Derive or remove** (verifier, orchestrator and contract):

| Item | Change |
|---|---|
| ack tokens (51) | drop the equality check (`evidence.py:138-141`); reword R7:630 |
| S3-LINT (67) | drop the binding (`p3b_s5_r7_verify.py:205-207`; S3 already runs at `:176`); remove the file from `FINAL_WRITES` (`universe.py:55-56`, `:696`). The prompt write list (`orchestrate.py:255-256`) is rendered from `permitted_writes`, so the entry and the golden prompt hash follow automatically. A residual Write becomes W8 |
| claim-evidence (64–66) | the verifier searches the facts for a qualifying fact itself |
| contract/manifest shas (3) | **EXPOSE, not stamped.** Assembly never edits a record (R7:141, orch:622-623). The annex states "copy both values from the slice fields", and fixes the R7:122 wording. Alternative: remove the fields from the exact key set by an annex row (then listed under "Changed", §4) |
| precedence and `position` (44, 45) | **verifier-side** derivation from `files_meta` (V:310; C:39-59), with no plan or slice change (the rebind requires both byte-identical, act:205-208). **Agent residual, stated in the annex:** `date_applies_to_file` stays attested (C:45); later_* lists are candidates, which the verifier filters by derived precedence |
| `edge_class` (14) | derived by the verifier from the edge's own quote and source (R2 needs a quote; R1 needs a plan row source) |
| `test_plan_sha256` (74) | the **verifier hashes the canonical JSON of the test-plan fields already in the register record** (inventory remedy, row 74). No new file and no new permitted write. Disclosed: a post-run hash attests consistency, not pre-registration order |

**Claim-evidence search (H1, E findings 1–2).**
- **Deterministic match:** all qualifying facts are counted, and the minimal (run, source, fact_id) is reported under a total order (the canonical-JSON sort key; `fact_id` may be non-string). When candidates fail differently, the reported code follows a fixed precedence. The choice rule is tested (H4).
- **Per-claim codes, unconditional:** NO-FACT-OF-KIND, FIELD-MISMATCH, UNANCHORED, NOT-WHOLE, RUN-NOT-READING, plus a "candidates examined" count.
- **Known weak spot, disclosed:** timeline and d4/d5 claims have `fields = {}`, so any fact of that kind at that source qualifies. This existed before; the agent's ref only made it visible.
- **Consequences, listed:**
  - `claim-evidence.json` is removed from `FINAL_WRITES` and from the prompt;
  - the K-key (R7:247), the W6 wording (R7:69), R7:130 and R7:492 are amended;
  - T17 (R7:743) is re-expressed as "a fact on an unwitnessed page cannot satisfy a claim";
  - a residual Write becomes W8.

**H2. Contract-first annex** (E finding 3, a finite procedure):

1. **Draft.** A drafter who has not authored or reviewed the verifier code, and is not the inventory builder, receives the rev3/R5/R6/R7 rule texts plus the inventory's list of row names (keys only, without the code behaviour) and writes the annex.
2. **Extract.** A static key extraction is run with the inventory's AST tool, **committed as an artifact** and frozen. Its declared inputs add the dynamic key sources (U:76-110, pr:88-158, f-string keys), which the AST alone misses.
3. **Diff, both directions:** keys in the code but not the annex (unreachable), and rules in the annex but not the code (unchecked; R-1…R-10).
4. **Human table.** The human receives **one closed table**: one row per disagreement, giving the contract citation, the code behaviour (file:line) and the options **CONTRACT-WINS** (code changes), **CODE-WINS** (the contract gains the rule) or **DROP**.
   - Where no contract text exists (`ensure_ascii`, `SUPERSEDED`, the pointer phrases), the row is marked "authoring": the human chooses a value, not a reading.
5. **Bounds:** one draft, one diff, one human pass, then the two-way reachability test as the termination check. Rows the human adds go through the same test.
6. **Format and location.** The annex is a machine-read table between markers **inside the R7 addendum**, so the existing `ADDENDUM_SHA256` binding covers it (act:218-219). The verifier loads it like the REGISTRY. No new I(run) category is introduced (R7 §5.8 is a closed enum).
7. **Predicate effect.** CONTRACT-WINS rows can change the acceptance predicate (for example the two live POINTER regexes, `p3b_s5_verify.py:108` and `p3b_s5_r7_reconstruction.py:34`). These are listed under "Changed" (§4).

**H3. Code-level fixes** (curable only in code):

| Defect | Fix |
|---|---|
| Seal asymmetry (F3) | W8 SEAL becomes label-aware **only for the content of a Write to a permitted path**. Bash, Read and every other tool input keep `label=None` (W:263-266), and an unknown owner stays strict (W:256). Hold-out label names stay strict everywhere (cm:213-217). RED tests: an allowed token in a Write → no stop; the same token in a reader command → STOP; a non-allowed hold-out S-id or any hold-out label name in a Write → STOP |
| Must-copy anchor typing (23) | `hit_key` is exempt from typing **iff** it is byte-equal to a stage-1 key of the same label's slice (G-04 equality, V:698-700); otherwise it stays R7-U. The S3 quarantine (VR7:176) and W8 SEAL (W:265) stay untouched. RED test: a non-copied `hit_key` carrying an S-id → R7-U |
| Parse robustness (68) | malformed **agent-authored** JSON → BATCH-FAIL with a code, never an exception (`_jl`/`_js`, VR7:55-66; V:1188). **Tool artifacts** (plan, INPUT-MANIFESTS, R7-PROVENANCE, WITNESS-DIGESTS, READ-LOG) stay Refused/class S, so tool corruption never becomes a retirable failure |
| Birth-claim enumeration (18) and untyped free-text fields (22) | claims are enumerated only from `birth_basis()`, not from every S-id token in a births value (U:340-343); META registry rows are added for the row-22 free-text fields |
| Cause mapping (G7) | every new failure code (per-claim codes, parse code, changed W8 strings) gets a **pre-registered cause class** in the frozen mapping, in the v2.9 text. Otherwise "unrecognized → D" (R7:186) would stop the counted canary |
| Method enum (28/29) | under R7, `STAGE-2A-2B` is removed from the admissible methods and `NOT-CONSUMED-ESCALATED` is added to the rev3 §D enum by the annex |
| Synthesis run id (66) | the prompt line at `orchestrate.py:126` is corrected (moot if claim-evidence is removed) |

**H4. Testability.**
- Per-check evaluated-item counters in the verify report.
- A **two-way reachability test:** every verifier key and enum ⊆ annex, and every annex row has a check that a mutation test proves.
- Fixtures regenerated from the annex, not from the verifier.
- RED tests first, for example: "ack field absent → no R7-E"; "S3-LINT written → W8"; "malformed claim file → BATCH-FAIL, not an exception"; "allowed mention in a Write → no SEAL stop".

**H5. Prompt.** Pointer lines only, with each rule in the annex: "Edit/MultiEdit forbidden; re-Write the whole file"; Read only within EOF; no reader call after your last Write (SINGLE).

## 3a. Binding engineering constraints for G1 (review G)
These conditions bind any engineering authorized under G1:
1. **No production paths.** Every command passes explicit non-production `--root`, `--archive` and scratch paths. `DEFAULT_ARCHIVE` (orch:57) and the fixed `SCRATCH_ROOT` (pr:53) are never used. The production `ADDENDUM_SHA256`, `P3B-STATE.json`, the manifest and the ledger stay untouched.
2. **Synthetic data only.** Builders and reviewers use synthetic fixtures only. They do not read corpus bytes, 02-FILES or the hold-out sets without a separate human act (protocol §22).
3. **H1/H3 as written.** Each item is implemented exactly as specified in v4: no stamping at assembly, no plan or slice change, no new permitted write, and the seal change limited to Write content.
4. **TDD.** Every H3 item and each new failure code starts with a RED test, and the code's cause class is in the mapping.

## 4. Attempt semantics: a disclosed re-registration (E finding 4, G finding 8)

**Two distinct parts:**
- **(a) Canary retry exception.** OB0012 and OB0114 only, once.
- **(b) S5-wide predicate re-registration.** The changed acceptance predicate, the annex and the new cause-mapping entries govern **every** post-canary attempt. q\* (R7:200-201) is **kept**: W-SYS is execution-only (R7:199), so keeping it is defensible. This is stated, not assumed, and it is revisited at G5.
- **Also amended:** R7:168 ("same … prompts"), for (a).
- **Annotation:** the exploratory "accepted at attempt 1" domain and the attempt × audit cross-tab are annotated where canary labels were excluded for instrument reasons.

**This is a post-results change to the retry rule** (11 of 11 canary runs failed). It is defensible only because:
- the canary is the designated repair gate;
- no label is measured, and the audit was never consulted (R7 Blindness);
- the estimation object stays "the first accepted attempt";
- every failure stays in E-3.

**Framing.** It is a disclosed re-registration before S5 proper, not a neutral amendment. v2.9 supersedes v2.8 for the canary.

**Text in v2.9:**
- **Scope.** For OB0012 and OB0114 only, once: the next attempt may run under the re-bound v2.9 contract and prompts, bound to the reviewed v2.9 sha and the adopting G-LOG entry.
- **Other bullets amended explicitly:** "Repairs (runbook, prompt)" (R7:193) and "Policy (pre-registered, fixed before the canary)" (R7:184).
- **M = 4 still holds:** OB0012 → attempt 3 (attempt 4 remaining); OB0114 → attempt 2 (attempts 3–4 remaining).
- **RR-7** (identical failure sets → D) compares only consecutive attempts under the **same** bound contract sha.
- **W-SYS:** retried canary attempts and the rehearsal are not "post-canary" (R7:200). This is declared as a canary exception. Disclosed: q\* = 0.12771 was not calibrated for a population accepted under the v2.9 predicate.
- **Estimation:** a canary batch's later accepted attempt enters estimation as its first accepted attempt, with the contract version recorded in E-3. Disclosed: under the changed predicate and annex, the accepted population is not comparable with the canary attempts.

**What changes and what does not:**
- **Unchanged:** frame and sample hashes (shown by the rebind's body-identity check); estimand text (EP-01); M; the stop threshold; Freeze 1 and Freeze 2.
- **Changed:** the **acceptance predicate**, through H1, H3, H4 and the CONTRACT-WINS rows. θ_D and θ_A describe the population accepted under v2.9. No batch has been accepted, so no data is affected.

## 5. Rehearsal (E finding 5)

**What it is.** The real code except the content source, through a declared seam.
- **Why a seam:** the reader hard-codes `dio.discovery_resolver()` (`p3b_read_source.py:130`, `:222`) and cannot serve synthetic sources.
- **The seam** is an injectable resolver in a harness copy of the reader. A one-fixture parity test is **not possible** without a corpus read, so parity is shown in three parts (G finding 9):
  - (i) a byte diff of the harness copy against `p3b_read_source.py`, limited to the resolver line;
  - (ii) both resolver code paths exercised over a synthetic store;
  - (iii) the harness reader path in the prompt (orch:248) and in the witness classifier (W:276-279) declared as **not validated**.
- **Isolation:** a synthetic manifest and plan (hashing to their own `r7_plan_sha256`), a copied state, its own archive root, a throw-away repository and an isolated root.

**What it validates:**
- the agent's comprehension of the contract and annex;
- witness decoding;
- the verifier's checks over consistent bytes.

**What it does not validate:**
- the production reader, the seal and hold-out refusal;
- the identity-manifest pin;
- the production plan hash;
- the rebind;
- behaviour on real texts;
- the real hold-out sets (the rehearsal uses a synthetic holder, V:115-117);
- retire and attempt mechanics on production state;
- the production golden prompts;
- the LOAD prediction;
- **the residual A rate on real material** (synthetic A rates do not transfer).

**Coverage, frozen before dispatch:**
- every canary family, plus BIRTH, CONTRADICTION, LIFECYCLE, d4/d5 and CENSUS claims;
- S1 YES and S1 NO;
- a decided binary file;
- a hub run;
- a non-WHOLE-FILE state with CONTRACT-DEVIATION;
- layer-A pointer bait;
- an allowed-mention token;
- a TEST-DEFINED register record;
- non-ASCII hit keys and a spread of sizes;
- a DECOMPOSED label with a SYNTHESIS run, including `status_basis` (F8);
- a `hit_key` carrying an S-id (23);
- NOT-CONSUMED-ESCALATED (28);
- a file with `order_evidence = SOURCE_ID` (44/45);
- R1 and R2 edges (14);
- negative seal controls (H3);
- an EMPTY label.

**Run count:** derived from that list when the brief is written (not estimated here).

**Pass criterion** (pre-registered in the rehearsal brief, decidable through H4 and the cause mapping; G finding 10):
- **(i) No X failures.** No failure code whose pre-registered class is X. The X families are restated as code sets of the **new** verifier: the old F2/F5 regexes cannot match once their checks are removed.
- **(ii) Every check exercised:** each check's evaluated-item count is above 0.
- **(iii) At least one batch passes end to end:** at least one synthetic batch reaches BATCH-PASS, so the pipeline is shown to accept a correct output, not only to reject.
- **(iv) Residual A disclosed:** residual A strings are reported by code. There is **no A threshold**. The brief states explicitly that **G4 may still fail on A** and spend attempts under M = 4.

**Outcome.** Any X means a return to design under a bounded re-entry: at most one redesign round, then a new G1.5/G2 decision.

## 6. Staged human acts (E finding 6: rehearsal **before** rebind)

| Act | When | Decides |
|---|---|---|
| **G1 (now)** | this gate | (1) the X adjudication; (2) **fork P / H / R** (rec. H); (3) authorize design and engineering **on a branch or worktree under the §3a constraints** (the reader seam lives only in the harness copy); (4) the §4 re-registration text as a draft for v2.9; (5) the W-SYS treatment and the estimation note **as policy in principle**, binding only at G3 |
| G1.5 | after the annex draft and diff | the human adjudication table (H2) |
| G2 | after engineering, the A/B review and the **frozen rehearsal brief** | approve the brief **by its sha** (coverage, run count, pass criterion) and dispatch the rehearsal |
| G3 | after the rehearsal evaluation | approve **exactly the v2.9 text sha and code commit the rehearsal ran**; any later diff requires a re-rehearsal; then the production rebind |
| G4 | after G3 | the counted canary under §4: retire; OB0012 attempt 3, OB0114 attempt 2; frozen threshold (≥ 6 of 11 FAILED, or the same W-code in ≥ 2 runs) plus an early stop on any unreachable-family string |
| G5 | after the canary evaluation | full S5 |

**Risk.** Once the X layers are removed, previously masked checks run for the first time. The rehearsal shows whether those checks work. It does **not** measure the residual A rate on real material (§5), so G4 may still fail on agent errors.

**Alternative to the whole package:** stop S5 at R7, with no measured labels.

## 7. Hygiene
- The verify reports embed verbatim `hit_key` source text in their stage-2 claim ids. Packages cite masked templates only.
- This package and the canary-diag papers pass the quarantine scan (0, 0).
