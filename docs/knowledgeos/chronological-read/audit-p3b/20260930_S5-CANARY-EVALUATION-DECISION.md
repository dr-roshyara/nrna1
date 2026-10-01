# S5 canary evaluation gate: diagnosis and decision package (v2)

| | |
|---|---|
| **Kind** | ⚠ authority: generated. One decision package for the human at the canary-evaluation gate (G-LOG-0108). It authorizes nothing by itself |
| **Version** | v2 replaces v1 (`52c1ba687`). v1 asked for D1 + D2 + D3 as one act. Review D showed that as written, D2 could not run, D3 broke R7 §2.8, and the rebind would have been approved before its text existed. v2 therefore splits the decision into staged acts |
| **Evidence** | the evaluation `audit-p3b/20260930_S5-CANARY-RESTART-EVALUATION.md`; the verify reports `S5-VERIFY-OB0012-R7.A2.json` and `S5-VERIFY-OB0114-R7.json`; under `canary-diag/`: diagnosis A, review B (NEEDS-REVISION), package review C (ACCEPT-WITH-FIXES) and package review D (ACCEPT-WITH-FIXES). All material findings of B, C and D are accepted. The orchestrator spot-checked D's findings 2, 3, 8 and 10 against the code and contract |
| **Read discipline** | No reviewer opened a run output, slice, view, corpus file or the archive |
| **Scientific status** | **0 labels measured.** Nothing here is evidence for or against H1–H5 |

## 1. Finding

**Root cause (confirmed by A, B, C and D independently).**
- R7 restricts the agent to its I(run) inputs (rev3, R7, plan, slice). It still takes the output schemas from R5/R6 **by reference** (R7:13, R7:892), and R5/R6 are not in I(run).
- So fields the verifier requires appear in **no text the agent may read**: `ack_tokens`, `pair_evidence_checks`, `records_sha256` and `edge_class` each have 0 occurrences in rev3 and R7.
- One required value cannot be computed at all without Bash: the S3-LINT `records_sha256`.

**D's completeness sweep found more unreachable rules** (review D, last section):
- the S1 "NO" path (an OTHER escalation with a `VERDICT-EVIDENCE-CONFLICT` detail, plus a register topic);
- `lifecycle == "SUPERSEDED"`;
- the `contradicts_source` and census `dimension` equalities;
- truthy-only fields (`change_candidate`, `concepts`, `type`);
- the synthesis claim `run`, which must be the owning UNIT run (R7:130 suggests the opposite);
- a second, divergent POINTER regex (`reconstruction.py:34`);
- the contract claim `summary:<field>:<S>` (R7:335), which the code never generates.

**Why no test caught it.**
- The fixtures were built from the verifier's own schema, so they could only prove that the verifier accepts that schema.
- Every review checked verifier ⇐ contract, and never the reverse.
- R5 and R6 were run by an orchestrator with Bash, so the gap only opened when R7 introduced W8 and I(run).

**Quantification** (re-derived independently by B and D; D's 13 regexes classify all 340 strings, 0 unclassified):

| Figure | Strings | Share |
|---|---|---|
| **Provably unreachable** (F2, F4, F5, F6) | 107 | **31.5%** |
| plus F1 stage-2 ids with `\u` escapes (the key can match only by writing a literal `\uXXXX`) | +33 | 41.2% (140) |
| Upper estimate (all of F1 and F8) | 294 | 86.5% |
| Remainder classed A: lenient / strict (strict excludes the 4 F9 phrase strings) | 46 / 42 | 13.5% / 12.4% |

**F1 cannot be split further from its strings.** One message template covers eight causes (`evidence.py:163-177`), and a missing `claim-evidence.json` silently becomes `{}` (`p3b_s5_r7_verify.py:193`).

**A strings touch 7 of 11 runs (lenient) or 5 of 11 (strict).** The threshold is 6.

**Ruled out** (D §14, no outputs needed):
- line truncation (the longest line is 1,537 characters, under the 2,000 cutoff);
- context pressure (small and large runs show the same X families);
- model behaviour, which can explain the guessed shapes but not fields that appear in no text.

**What remains open.** Whether the agents read the contract fully. That can be measured from the witness (contract read-input coverage) by an authorized session.

**Recommended adjudication** (it takes effect only on the human's approval; the evaluation left X versus A open):
- The dominant cause is an **instrument/contract defect (X)**, not an agent or corpus property.
- The recorded mechanical classes (A1, A2) are append-only and stay as they are.
- The fix goes beyond RR-6 (runbook and prompt only), so it needs human acts.

## 2. Repair design (v2)

**Principle.**
- Agents attest nothing a tool can derive.
- The contract stays the sole authority (`universe.py:4-6`).
- The code reads the schema from the contract; the contract is never generated from the code.

**R1. Remove agent-attested derived values** (verifier and contract change):

| Item | Change | Removes |
|---|---|---|
| (a) ack tokens | drop the equality check (`evidence.py:138-141`) and reword R7:630 | F2 |
| (b) S3-LINT | drop the binding (`verify.py:205-207`) and remove `S3-LINT.json` from the permitted writes (`universe.py:55-56`, `:696`; prompt `orchestrate.py:250-251`; golden prompt hash). A residual write becomes W8. The verifier already runs S3 (`verify.py:176`) | F5 |
| (c) **claim-evidence.json** | the verifier searches the facts for any valid fact itself (D finding 6): run ∈ reading, kind, source, fields, anchor and whole-file. It prints the match for audit. The agent's claim map adds no constraint, because the agent may cite any fact it has. **Recommended over parsed-tuple matching** | the F1 claim-map and serializer traps, and the synthesis-ref ambiguity |

**R2. Self-contained schema annex in R7 (v2.9), contract-first** (D finding 3):
- **(a) Drafting.** An independent drafter writes it from the rev3/R5/R6/R7 rule texts without reading the code.
- **(b) Reconciliation.** The draft is diffed against a static key extraction from the code, and **the human adjudicates every disagreement**: the three POINTER regexes, `summary:` claims, the silent `or {}`.
- **(c) Format.** It is a machine-read table between markers, which the verifier loads like the REGISTRY.
- **(d) Coverage.** It includes every key in D's list and the §2.8 amendment (R4).

**R3. Instrument testability:**
- one F1 sub-cause code per condition, and an explicit failure when `claim-evidence.json` is absent or unparseable (only if R1(c) is not adopted);
- per-check evaluated-item counters in the verify report;
- a **two-way reachability test**: every verifier key and enum ⊆ annex, and every annex row has a check that a mutation test proves;
- RED tests first: "ack field absent → no R7-E" and "S3-LINT written → W8".

**R4. §2.8 amendment, in the same v2.9 text.** A canary batch failed under an earlier contract version may run its next attempt under the re-bound contract and prompts.
- The attempt counts toward M = 4: OB0012 → attempt 3, OB0114 → attempt 2.
- The estimation object stays "the first accepted attempt".
- Without this, D3 is not an attempt in the §2.8 sense (R7:168, D finding 10).

**R5. Prompt.** One pointer line, "Edit/MultiEdit forbidden; re-Write the whole file", with the rule itself in the annex.

**What changes and what does not** (D finding 12):
- **Unchanged:** the frame and sample hashes (shown by the rebind's body-identity check), the estimand text (EP-01), M, the stop threshold, Freeze 1 and Freeze 2.
- **Changed:** the **acceptance predicate**, through R1, R3 and the annex. So θ_D and θ_A describe the population accepted under v2.9. No batch has been accepted yet, so no data is affected.

**Rejected alternatives:**
- **Add R5/R6, or excerpts from them, to I(run).** The decisive reason (D finding 7): R5, R6 and the runbook do not contain `ensure_ascii`/`json(`, `hit_kind`/`term_index`, "verified cited", `SUPERSEDED` or the pointer phrases, and `ack_tokens`/`records_sha256` appear only in the runbook. They could not close F1, F2, F5, F8, F9 or LIFECYCLE. An excerpt would also mean authoring an annex anyway.
- **Prompt-only fix.** There is nothing reachable to point to.

## 3. Rehearsal design (D2, revised per D findings 8 and 9)

**The real reader cannot serve synthetic sources.** It reads git blobs pinned by the identity manifest and needs the seal (`p3b_s3_mechanical_prep.py:69-76`, `:234`; `p3b_discovery_io.py:45-55`).

**Recommended: a declared test resolver.**
- The rehearsal runs the real dispatch, witness, prompt and verifier code against a resolver that serves synthetic sources.
- It uses an isolated root, a copied state file and its own archive root, so there is no corpus or seal exposure.
- **Stated limitation:** it does not test the reader itself. The canary already showed the reader working: 0 refusals in 11 of 11 runs.

**Alternative:** real non-frame, non-hold-out sources via object alternates. This is closer to production, but it brings back corpus and seal exposure and needs a seal analysis. Not recommended.

**Coverage, frozen before dispatch:**
- the families of v1;
- BIRTH, CONTRADICTION, LIFECYCLE, d4/d5 and CENSUS claims;
- an S1 YES check and an S1 **NO** check;
- a decided binary file;
- a hub run;
- a non-WHOLE-FILE state with CONTRACT-DEVIATION;
- layer-A pointer bait;
- a DECOMPOSED label with a SYNTHESIS run.

That is about 5–6 runs.

**Pass criterion** (decidable through R3):
- 0 strings matching the frozen regexes for the unreachable families;
- every check has an evaluated-item count above 0;
- every residual string is reported with its sub-cause code.

**Outcome:** any X means a return to design. A strings are information only and do not stop the rehearsal.

## 4. Decisions, as staged acts

| Act | When | What the human decides | Recommendation |
|---|---|---|---|
| **G1 (now)** | this gate | the adjudication (X); **authorize the design and engineering of R1–R5 and the rehearsal harness**, tests-first with independent review, on tests and copies only, with no production mutation. Choices inside G1: R1(c) verifier-search (rec.) vs parsed tuple; test resolver (rec.) vs object alternates; the W-SYS reading (retried canary attempts and the rehearsal are not "post-canary", so they are not counted); whether the canary batches' later accepted attempts enter estimation (rec. yes, as the first accepted attempt, with the version noted in E-3) | approve |
| G2 | after R2's human adjudication list and the A/B review | approve the **reviewed v2.9 text by its sha** and rebind production to it | — |
| G3 | after G2 and the rehearsal harness review | dispatch the rehearsal | — |
| G4 | after the rehearsal evaluation | the counted canary restart under v2.9 §2.8 (amended): retire, then OB0012 attempt 3 and OB0114 attempt 2; the frozen threshold (≥ 6 of 11, or the same W-code in ≥ 2 runs) plus the additional early stop on any unreachable-family string | — |
| G5 | after the canary evaluation | full S5 | — |

**Stated risk.** Once the X layers are removed, previously masked checks run for the first time: stage-2 anchoring, pair checks and S3 layer-A. Agent errors already touch 5–7 of 11 runs. The rehearsal is the cheap place to learn the residual A rate before G4.

**Alternative to the whole package:** stop S5 at R7, with no measured labels.

## 5. Hygiene
- The stage-2 claim ids in the verify reports embed verbatim `hit_key` source text (D finding 15). Future packages cite masked templates only.
- This package and the canary-diag papers quote no such text (quarantine scan (0, 0)).
