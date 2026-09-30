# S5 canary evaluation gate: diagnosis and decision package

| | |
|---|---|
| **Kind** | ⚠ authority: generated. One decision package for the human at the canary-evaluation gate (G-LOG-0108). It authorizes nothing by itself |
| **Evidence** | `audit-p3b/20260930_S5-CANARY-RESTART-EVALUATION.md`; the verify reports `S5-VERIFY-OB0012-R7.A2.json` and `S5-VERIFY-OB0114-R7.json`; diagnosis A `canary-diag/DIAG-A.md`; independent adversarial review B `canary-diag/DIAG-REVIEW-B.md` (verdict NEEDS-REVISION; all 6 material findings are accepted and folded in here); package review C (verdict ACCEPT-WITH-FIXES; findings 1–10 are applied in this version) |
| **Read discipline** | Neither A nor B opened a run output, slice, view, corpus file or the archive. The counts come from the verifier's failure strings; the requirements come from the contracts and the code |
| **Scientific status** | **0 labels measured.** Nothing here is evidence for or against H1–H5 |

## 1. Finding

**Root cause (instrument/contract, confirmed by A and B independently).**
- R7 restricts the agent to its I(run) inputs, which are the rev3 contract, the R7 addendum, the plan and the slice, and it makes every other read a W8 violation.
- R7 nevertheless carries the R5/R6 output schemas over **by reference** (R7:13, R7:892), and R5/R6 are not in I(run).
- As a result, fields the verifier requires appear in **no text the agent may read**. `ack_tokens`, `pair_evidence_checks`, `records_sha256`, `edge_class` and `R1-STRUCTURAL` each have 0 occurrences in rev3 and in R7. R7 names `claim-evidence.json`, `file-reading-records.jsonl` and `S3-LINT.json` without ever giving their shape.
- **One required value cannot be computed at all:** `S3-LINT.records_sha256` is a sha256 of canonical JSON, and the prompt allows no Bash.

**Why no test caught it.**
- The fixtures build "agent output" from the verifier's own field list, so they prove only that the verifier accepts its own schema.
- Every review checked verifier ⇐ contract. None checked the reverse: that the verifier requires nothing the agent cannot see.
- R5 and R6 were run by an orchestrator that had Bash and repository access, so the gap opened only when R7 introduced W8 and I(run). The restart canary was the first test of agent reachability.

**Quantification (revised per B M2).**

| Figure | Strings | Share of 340 | Basis |
|---|---|---|---|
| **Robust floor: provably unreachable** (F2 ack tokens, F4 pair checks, F5 S3-LINT, F6 edge_class) | 107 | **31.5%** | the field or value appears in no I(run) text |
| Upper estimate, adding F1 (claim → evidence path, 170) and F8 (`status_basis` ⊆ "verified cited sources", 17) | 294 | 86.5% | inferred; separating these from agent error would need the run outputs |
| Remainder, classed A by DIAG-A: F3, F7, F9, F10–F13 | 46 | 13.5% | the rule is stated in I(run), except the F9 phrase cases (4 of F9's 6 strings, see below). The strict A count is 42 (12.4%) |

Two qualifications from B:
- **The agents partly guessed the schema.** The F3 strings show `facts` carrying `fact_id` and `quote`, in id styles that differ between runs. So the repair must pin exact names and shapes; stating that the fields exist is not enough.
- **Two of the three F9 cases are under-specified, not agent errors.** The phrases 'register entry' and 'research register' are forbidden only by a code regex (`scripts/p3b_s5_verify.py:108`), never in the contract.

**The id serializer is also unspecified (B M3).**
- The grammar is `stage2:<S>:<hit_kind>:<json(hit_key)>:<term_index>:<dim>`, and `hit_kind`, `hit_key` and `term_index` are not defined (R7:339).
- The verifier serializes with Python `json.dumps` defaults, which emit `\uXXXX` escapes (`scripts/p3b_s5_r7_universe.py:360`). No agent could derive that.

**The recorded classes stay as recorded.** The frozen mechanical mapping classed OB0012 attempt 2 as A1 and OB0114 attempt 1 as A2. Those records are append-only and are not rewritten.

**Recommended adjudication (it takes effect only when the human approves it; the evaluation left X versus A undecided):**
- The dominant cause is an **instrument/contract defect**. It is not an agent defect, and it is not a property of the corpus.
- Most of the fix lies **outside** what RR-6 admits at the gate (runbook and prompt only). The contract and verifier changes are therefore design changes that need a human act. That act is decision D1.

## 2. Decisions for the human

Recommended path: approve **D1 + D2 + D3 as one act**. Execution then stops before any counted dispatch.

### D1: repair the instrument (a contract amendment to v2.9 plus a verifier change; TDD; A/B review; rebind)
**(a) Remove agent-attested values that are a function of witnessed data (B M1).**
- **F2:** the tool derives the ack tokens, or the verifier drops its `ack_tokens` equality check (`scripts/p3b_s5_r7_evidence.py:138-141`). R7 W3 already says that ack = f(page sha) is "a consistency check only, never separate evidence".
- **F5:** the agent no longer attests the S3 lint. The verifier already runs the S3 checks itself (`scripts/p3b_s5_r7_verify.py:176`, `C.s3_violations`). The binding check at `:205-207` is what gets dropped.
  - Recommended: drop the `records_sha256` binding and remove `S3-LINT.json` from the permitted agent writes, which is the lowest surface.
  - Alternative: the orchestrator produces the S3-LINT file.
  - Either way this changes the permitted writes (`universe.py:56`, R7:143, R7:492 and the trust table at R7:92). The W5 watch on the file (F13) and every test that references it change with it. The R5/R6 verifiers (`p3b_s5_r6_verify.py`) are out of scope and unchanged.

**(b) A self-contained agent-output schema annex in R7, generated from the verifier code.** It must state exactly:
- the `file-reading-records.jsonl` record: `source_id`; the `reading_state` enum; `facts[{fact_id, kind, quote, fields}]` with the value domains (`birth_kind`, `finding: "DEFINES"`, `target_label`); and `pair_evidence_checks[{pair_id, supports, quote}]` with its scope and scale;
- the shape of `claim-evidence.json`: `{claim_id: [{run, source_id, fact_id}]}`;
- the stage-2 id components. Recommended: make the verifier match on the parsed tuple rather than the serialized string, which removes the `\uXXXX` trap (a verifier change);
- `edge_class` and its rules;
- a definition of "verified cited sources" (F8);
- the forbidden POINTER phrases in layer A (F9).

Rejected alternative: adding R5 and R6 to I(run). It would change the audited universe, import superseded rules (RANGE5/6, the R5 edge-class text) and widen the W8 read surface.

**(c) One prompt line: "Edit/MultiEdit are forbidden; to correct a file, Write it again in full" (F12/F13).** This falls under RR-6. The rule itself is stated in the annex, and the prompt only points to it ("this prompt restates no rule"). `render_prompt` (`orchestrate.py:255-260`) is a re-audited path, and its golden prompt hashes change.

**Consequences.**
- **New addendum sha.** `rebind_contract` accepts only `U.ADDENDUM_SHA256`, a code constant in `p3b_s5_r7_universe.py`. Its update (with its tests) is part of D1, followed by the rebind, which admits FAILED batches.
- **Re-audit** of the changed verifier paths.
- Freeze 1, Freeze 2, the frame, the sample, the estimands, M = 4, W-SYS and the stop threshold are **unchanged**. The evidence for that is the rebind's check that the 396 body lines are byte-identical (so the body `output_sha256` is unchanged); it is not just asserted.

### D2: a new instrument gate before any retry (B M5)
1. **Static agent-reachability test (TDD, it fails first on today's code).** Every key the verifier reads on an agent-authored artifact must appear in the I(run) text, and every derived value (hash, serialized id) must be either derivable from I(run) or not produced by the agent.
2. **Fixtures regenerated from the annex text**, not from the verifier.
3. **A non-counting schema rehearsal**, run in an **isolated copy**, never in production.
   - **Mechanism, to be built and reviewed under D1.** Use the pattern of `audit-p3b/impl/canary_precheck_v28.py`: a temporary root outside the repository holding copies of the contract, the addendum and a synthetic plan and slice, reached through a throw-away git repository whose CR path links to the copy. The rehearsal has its own state file and ledger.
   - **Production is untouched:** the manifest, `P3B-STATE.json`, `ledger-p3b-r2/`, the frame and the attempt counters (so R7 namespace totality, R7:117, is not affected). A fresh session dispatches it with its working directory in the throw-away repository, using the real reader, witness and verifier code. It uses no corpus source and no hold-out.
   - **Frozen before dispatch** (in the rehearsal brief, sha recorded):
     - (i) one regex per failure family, taken from the verifier's string templates;
     - (ii) the synthetic material, which must exercise every family: a multi-page WHOLE-FILE read (F2), a stage-2 FOUND with a non-ASCII `hit_key` (F1 stage-2), timeline, absence and dependency claims (F1), a P3a pair citing a file (F4), a dependency edge (F6), a register record (F7), and a DECOMPOSED label with a SYNTHESIS run (F8). This needs about 4 runs (2 SINGLE + 2 UNIT + 1 SYNTHESIS at most); one run on one label could pass vacuously.
   - **Pass criterion:**
     - 0 strings matching the unreachable-schema families F2, F4, F5 and F6 (F2 and F5 cannot occur once D1a lands);
     - every family's check was reached, meaning each exercise in (ii) produced at least one evaluated item;
     - F1, F8 and every other string is reported with the frozen regex attribution. It is classed X only when the string shows a shape or serializer mismatch (an absent or unparseable file, an unresolvable reference form), and A otherwise.
   - **Outcome:** any X means a return to D1. A strings are information for D3 and do not block the rehearsal.

### D3: the retry plan (B M6), pre-registered now, before any new data
- After D2 passes, run a **counted canary restart** on the same two batches: retire the FAILED attempts, then OB0012 attempt 3 and OB0114 attempt 2. That stays within M = 4 (OB0012 would reach 3 of 4).
- **The frozen stop threshold is unchanged.** The canary stops at ≥ 6 FAILED of the same 11 runs (the plans are frozen: OB0012 5 SINGLE; OB0114 3 SINGLE + 2 UNIT + 1 SYNTHESIS), or when the same W-code occurs in ≥ 2 runs. It is not loosened after seeing results.
- **One additional stop condition, which needs its own approval inside this act.** Any string that the frozen D2 regexes attribute to F2, F4, F5 or F6, or that is an X-attributed F1/F8 string, stops the canary for the human with an X diagnosis. It can only stop earlier, never later.
- **W-SYS.** R7 §2.8 counts "completed post-canary attempts". Under that text, the retried canary attempts and the rehearsal are **not counted** in n or f. This package asks the human to confirm that reading.
- **Stated risk, not a prediction.**
  - The A strings already touched 7 of 11 runs.
  - The X failures kept whole layers from ever being exercised: stage-2 anchoring, pair checks and the S3 layer-A phrases.
  - The counted canary can therefore still stop on A. The rehearsal in D2 is the cheap place to see that first.
- The counted canary needs its own operator brief and its own human act after D2 passes.

### Alternatives to the whole package
- **(ii) Minimal fix:** only D1(c) plus a prompt pointer. Not recommended: the prompt has nothing reachable to point to, so this would reproduce the X failures.
- **(iii) Stop S5 at R7.** This is legitimate. It would record that the instrument could not be made agent-reachable, and the programme would end with no measured labels.

## 3. What an approval of D1 + D2 + D3 authorizes, and what it does not
- **Authorizes:**
  - tests-first changes to the verifier, the universe, the orchestrator prompt and the addendum (v2.9), each with independent A/B review and re-audit of the changed verifier paths;
  - the contract rebind;
  - building the reachability test and the synthetic rehearsal harness;
  - an operator brief for the rehearsal.
- **Does not authorize:**
  - dispatching the rehearsal (a separate one-line act, from a fresh nrna1 session);
  - retiring the FAILED attempts or starting new ones;
  - the counted canary; full S5; §21 or H-06;
  - R-5 cleanup;
  - any change to Freeze 1 or Freeze 2.
