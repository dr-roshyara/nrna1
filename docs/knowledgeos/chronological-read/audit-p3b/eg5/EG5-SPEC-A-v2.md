# EG-5 / EG-5b formal specification, v2 (Subagent A, revision after review B)

| | |
|---|---|
| Kind | Engineering specification DRAFT v2. authority: generated. It supersedes `EG5-SPEC-A.md` (v1, kept unchanged). No repository file is changed. The v2.8 amendment text in §6 is a **proposal, not applied**. |
| Responds to | `EG5-REVIEW-B.md` (SPEC-NEEDS-REVISION): B-01 (the EG-5b blast radius), B-02 (provenance vs rev3 §18), B-03 (θ_D on the EMPTY stratum). |
| Unchanged from v1 | §1-§4 of v1: EMPTY semantics, the gate table, the field table and the no-evidence argument. Review B confirmed them independently. v1 §6.3, §9 (pass-through list) and §12 are **replaced** here. |
| New evidence | The probe `scratchpad/eg5/probes/probe_eg5b_options.py`: a read-only import of the synthetic `tests/r7_full_fixture`, run through the **production** verifier. It prints no S-ids. Additional reads: `audit-p3b/20260927_B1-…PROPOSAL.md` (B1) and `audit-p3b/20260928_FREEZE-2-PROPOSAL.md` (F2), for §5. |

The citation key is as in v1 (A, R3, V, V7, UN, WI, RC, R6, OR, FX). **[F]** = fact · **[D]** = design · **[H]** = human decision · **[T]** = probe or test evidence.

---

## 1. EG-5b restated, with its full blast radius (task 1; B-01)

**[F] The defect.**
- For revision 7, `verify` fixes **one** batch-level `run = <B>-R7` (V:331-333). G-07 compares every record with that value:
  - objects: `run_id` (V:511-513);
  - register: `rs_id` form `<B>-R7:<B>:<n>` and `run_id` (V:929-930, 955-957);
  - P1-gap: `gap_id` form `<B>-R7:<B>:G<n>` and `run_id` (V:988-992);
  - batch-wide uniqueness of `rs_id` and `gap_id` (V:978-979, 998-999).
- rev3 item (3) (R3:7) instructs: "`<run_id>` is the run id you are dispatched with; every path, reader call and **record** uses it". Under R7 the dispatched id is always `<B>-R7-L##` or `<B>-R7-L##S` (A:115). That id never equals `<B>-R7`.
- The addendum contains no `run_id` / `rs_id` / `gap_id` text (grep: zero matches; confirmed by B). **No contract text resolves the conflict.**
- The rendered prompt (OR:104-132) is silent too.

**[F] Blast radius.** It is wider than B-01's "register-bearing" wording:
- Every SINGLE and every SYNTHESIS run writes an **object**. The object's `run_id` alone already fails G-07.
- So **every label that has a final run fails**, whether or not it writes register or P1-gap records. That is every non-EMPTY label, and hence every R7 batch that is not entirely EMPTY: **both canary batches** (OB0012: 5 SINGLE; OB0114: 3 SINGLE + 1 DECOMPOSED), and in practice all 396 batches.
- Independent of EMPTY, an agent that obeys rev3 (3) literally cannot produce BATCH-PASS.

**[F] A second, independent failure mode.** Once `run_id = <B>-R7` is used, isolated final runs that number `rs_id` / `gap_id` from 1 collide. These collisions give `G-07 repeated …` failures.

**[T] Probe results** (production verifier, synthetic fixture: 5 labels = 1 DECOMPOSED + 1 EMPTY + 3 SINGLE; 23 register and 19 gap records; at most 6 register records per label):

| Variant | Verdict | Failures |
|---|---|---|
| baseline FX (it already uses `<B>-R7` everywhere; FX:155) | BATCH-PASS | — |
| dispatched id in **one** object only | BATCH-FAIL | `G-07 <label>: provenance ids` ×1 |
| dispatched id in one label's register records only | BATCH-FAIL | `rs_id not <run_id>:<batch_id>:<n>` ×5, `provenance ids` ×5 |
| `<B>-R7` ids but per-run numbering from 1 | BATCH-FAIL | `G-07 repeated rs_id values`, `G-07 repeated gap_id values` |

**Priority signal for governance.** EG-5b is an **R7-programme blocker**. Neither canary can reach BATCH-PASS until it is resolved. It is not an EMPTY corollary.

## 2. EG-5b resolution options (task 2)

### 2.1 (a) Addendum rule, verifier UNCHANGED (orchestrator candidate)

**Rule.**
- The **record fields** `run_id`, `rs_id` and `gap_id` of objects, register records and P1-gap records carry the assembly id `<B>-R7`.
- `rs_id = <B>-R7:<B>:<n>` and `gap_id = <B>-R7:<B>:G<n>`, with **n = 1000·L + k**:
  - L = the label's 1-based manifest position;
  - k = 1..999, the record's 1-based position among that label's records of that kind in its final run.
- Everything else keeps the **dispatched** run id.

**[T] Result.** With the label-scoped numbering applied to every register and gap record (with `derived_from_records` remapped consistently), the verdict is **BATCH-PASS**. This covers 5 register-bearing labels, well over the two the brief asked for. The verifier regexes `re.escape(run)+":"+re.escape(batch)+r":\d+"` / `…r":G\d+"` (V:929, 988) accept `n = 1000·L + k`.

**[F] Other consumers of a run identity, checked one by one.** They all key on the **dispatched** id, which must therefore stay dispatched:

| Consumer | Keys on | Cite | Under (a) |
|---|---|---|---|
| ledger paths, W8 permitted writes | `ledger-p3b-r2/<dispatched>/…` | UN:694-697; WI:320-330 | unchanged |
| reader `--run`, canonical invocation, W2 binding | dispatched run | A §5.3-5.4; UN:699-702; WI:109-131 | unchanged |
| READ-LOG (reader-written, not agent-written) | `run_id = --run` | `p3b_read_source.py`:102-105 | unchanged; W6 reconciles `(run_id, S, page)` with the witness (`p3b_s5_r7_evidence.py`:276-283) |
| claim-evidence refs `run` | a **reading** run of the label | evidence:158-170 (`run not in reading_runs` → invalid) | **must stay dispatched**. [T] Setting a ref's `run` to `<B>-R7` gives BATCH-FAIL `R7-E … claim … has no valid evidence path` |
| file-reading records | no run field (keyed by file path) | FX:190-191; evidence:125-150 | unaffected |
| S3-LINT `records_sha256` | a hash of `[obj]+reg+gap` as assembled | V7:205-207 | the agent hashes its own records, which already carry `<B>-R7`; consistent |
| S3 register-id detector | `(?:<final>:)?<B>:\d+` | RC:155-160; V7:176 | still detects `…<B>:<n>` inside layer A, since the prefix is optional |
| G-12 pointer detector | `(?:<B>-R7:)?<B>:\d+` over the object text | V:610-614 | `run_id` / `batch_id` values do not match; unaffected |
| I(run), `input_manifest_sha256` (object) | the P3b slice field, not a run id | V:512 | unaffected |

**[F] Capacity.**
- No cap on register or P1-gap records per label exists in rev3 or the addendum (grep for per-label or record caps: none; the only cap in the contract is the step-10 byte cap, R3:62). The fixture maximum is 6 per label.
- `L##` is two digits (A:115), so `L ≤ 99` and `n ≤ 99,999`, which fits `\d+`.
- **[T] Overflow is detected only if it causes a collision.** Writing `n = 1000·(L+1) + 1` in label L gives `G-07 repeated rs_id` only because label L+1 also uses that id. An out-of-range id **without** a collision passes the verifier.
- [D] The assembly therefore refuses an `n` outside `[1000·L+1, 1000·L+999]` (refusal R11, §7). Agents with more than 999 records of one kind are an execution failure (the batch FAILS with cause `RECORD-ID-CAPACITY`). This is a theoretical risk at observed volumes.

**[D] Dispatch prompt.** It should carry the concrete values. It restates no rule and only instantiates the v2.8 rule:
```
RECORD IDS: every object / register / P1-gap record you write carries run_id=<B>-R7; rs_id=<B>-R7:<B>:<n>,
gap_id=<B>-R7:<B>:G<n>, n = <1000·L+1> … <1000·L+999> in the order you write them. Every path, reader call
and claim-evidence ref uses YOUR run id <dispatched>.
```
This is a tool change to `render_prompt` (OR:104-132), under tests. Without the contract rule (v2.8 §2.6) the prompt line would contradict rev3 (3), which a prompt must not do (OR:105-106 "restates no contract rule").

**Cost and risk.**
- An addendum text change changes the addendum sha256, which forces a **rebind** (the EG-4 procedure; UN:490-500; the I(run) ADDENDUM entries).
- The accepted verifier is **untouched** (G-LOG-0092).
- The positive control already models (a) (FX:155).
- Residual cost: a record's `run_id` no longer names the producing agent run. That run is recoverable from the per-run file the assembly consumed, whose sha256 is printed under ASSEMBLED and frozen in WITNESS.jsonl (§4 of v1, §8), and from the witnessed `write` events (WI:320-326).

### 2.2 (b) Verifier change: accept the label's dispatched final-run id

**Exact change set:**
1. V:331-341: keep the batch run `<B>-R7` for the assembly path, but derive `expected_run(lab)` = the label's final run from the frozen plan (`<B>-R7` for EMPTY).
2. G-07: objects (V:511-513) compare with `expected_run(lab)`; register (V:929-930, 955-957) and gap (V:988-992) use the pattern and the `run_id` of the **record's label**.
3. The uniqueness checks (V:978-979, 998-999) can stay global, because the prefixes differ.
4. G-12 `rsid` (V:610-611): widen it to any planned run prefix, so pointer detection does not weaken.
5. Gate the change on `rev7` so that revisions < 5 keep their historical output (A §10).
6. Add fixture and test changes: FX:155 no longer holds.
7. Re-run T01-T109; add the new tests.

**Why this reopens acceptance.**
- The R7 verifier was **independently audited and ACCEPTED** (G-LOG-0090/0091/0092). Any change to what it accepts needs a new independent audit (HD-9, A:10).
- The package stop rule allows a verifier change "only for a demonstrated verifier defect" (RB §3). One could argue that the verifier deviates from rev3 (3). The fixture and the accepted test matrix, however, **encode** `<B>-R7` as the intended record identity. So this is a contract/verifier inconsistency, not a pure verifier bug.

**Cost:** a verifier change, a re-audit, fixture churn across the acceptance matrix, and new tool sha values (the precheck records `p3b_s5_verify.py` `0d9e7c6c…`). An addendum rebind is still needed for EG-5 text anyway (§6).

**Benefit:** each record names its producing run, and rev3 (3) stays literally true.

### 2.3 (c) Other options considered

| Option | Verdict |
|---|---|
| (c1) Prompt-only: the rendered prompt dictates the ids, with no contract text | **Rejected.** The prompt would override rev3 (3). The package forbids a prompt that states rules (RB §2; OR:105-106). |
| (c2) The assembly rewrites or renumbers ids | **Rejected.** It changes agent records after the last witnessed write. It breaks the S3-LINT binding (V7:205-207) unless the orchestrator recomputes an agent-authored lint. It makes the assembly non-mechanical and needs `derived_from_records` remapping. |
| (c3) Non-numeric label scoping, e.g. `<B>-R7:<B>:L03-1` | **Rejected.** It fails `\d+` / `G\d+` (V:929, 988), so it would need a verifier change, i.e. (b) with extra steps. |
| (c4) A single register-writing run per batch | **Rejected.** It changes the run universe and the plan (frozen). |

### 2.4 Recommendation: (a)

1. **Tested:** it passes the production verifier unchanged (§2.1 [T]).
2. It keeps the **accepted** verifier closed (G-LOG-0092). (b) reopens it and needs a new independent audit.
3. It matches the intended identity that the acceptance matrix already encodes (FX:155).
4. Its only cost, a rebind, is shared with EG-5's own v2.8 text (§6), so there is one rebind for both.
5. Its failure modes are covered. An out-of-range `n` is refused by the assembly (R11). A dispatched id in a record field, or a collision, fails the verifier (§1 [T]).

## 3. Provenance: the reframing (task 3; H-1, B-02)

**The reframing under evaluation.**
- The EMPTY object is **proposed by the orchestrator AI agent** (served model claude-opus-5-5) through a deterministic, hash-bound rule.
- `model_id` = the orchestrator's served model and `proposed_by = AI-AGENT`.
- `generation_parameters = {producer: "p3b_s5_r7_orchestrate.assemble", tool_sha256, rule: "EMPTY-OBJECT v1", mode: "DETERMINISTIC-NO-MODEL-CALL"}`.

**[F] What R3 §18 says** (R3:1317-1319):
- `model_id` = "exact model identifier and version";
- `generation_parameters` "as exposed by the runtime";
- "The same contract on a different model is a different experiment, recorded as a different run."

R3 §16 (R3:1276-1277): AI output "enters the ledger with `author_role: AI-AGENT`". R3:1296-1298: every AI-derived field carries `proposed_by: AI-AGENT`.

**Assessment: consistent with §18, under two conditions.**
1. **The model↔run binding holds.** Under EG-5b option (a), **every** record of the batch carries `run = <B>-R7`, and the orchestrator performs that run.
   - The orchestrator and all dispatched agents are claude-opus-5-5. Its context variant counts as the same model (R3 §B, l.93-94).
   - So `<B>-R7` is a single-model run, and the EMPTY object's `model_id` names the model of the agent that performed its run. No record of the run has a different model.
   - If the orchestrator ever ran another model, the EMPTY object would carry that other `model_id` and the run would correctly be a different experiment. The binding is therefore **conservative**, never erased.
   - B-02's concern ("different experiment ⇒ different run" erased) is answered once the run *is* the assembly.
   - [D] Enforce it: the tool refuses (R10) unless `--model-id` ∈ MODEL_IDS (V:111) **and** it equals the `model_id` of every agent-produced object in the assembly.
2. **The contract says what `model_id` means for this record.**
   - The residual truth gap is B-02's point: no model computes the *content*.
   - §18 does not say "the model computed every value". It fixes accountability and experiment identity. The disclosure belongs in `generation_parameters` ("as exposed by the runtime": the runtime here is the tool) with `mode: DETERMINISTIC-NO-MODEL-CALL` and the tool sha.
   - For this to be a documented interpretation, not a gate-forced footnote, the v2.8 text (§6 (ii)) must state it explicitly.
   - `proposed_by = AI-AGENT` is then literally true: the orchestrator agent proposes, and the record stays PROPOSED (G-11). `author_role = "AI-AGENT (S5 orchestrator; EMPTY-OBJECT v1 rule)"` replaces v1's `ORCHESTRATOR-TOOL` for coherence with §16.

**When it would NOT be consistent:**
- under option (b): records would carry per-label run ids, and the EMPTY object would be the only record of run `<B>-R7`. That is still a single-model run, so still consistent. What is lost is the parallel with the agents' records;
- or if the orchestrator's model differed from the agents' models within one batch. R10 forbids that.

**Conclusion:** the reframing is truthful and consistent with §18 **given option (a) plus the v2.8 paragraph**. **No verifier change is needed**, and the H-1 material path is not required.

[D] `--model-id` is an explicit argument, so the output stays byte-deterministic. [H-minor, default] Optionally, `freeze-final` also checks the argument against the model recorded in the archived orchestrator transcript, if the decoder exposes it (not verified here).

## 4. θ_D on the EMPTY stratum (task 4; B-03)

**[F] The frozen text:**
- θ_D = "share of labels whose S5 object differs substantively, in a decision field (EP-01 §3), from an **independent same-protocol re-analysis**" (B1:27).
- B1 classifies θ_D as **reproducibility**, apart from correctness (θ_A, θ_E): B1:9 "observation (R7) · reproducibility (θ_D, R1, R2) · correctness (θ_A, θ_E)"; B1:31 "reproducibility (θ_D) ≠ agreement (θ_A) ≠ truth".
- Strata precedence HUB > EMPTY > … (B1:91, D1).
- F2 §2 (F2:46-51): EMPTY N_h = 7, a census, n_h = 7, π_h = 1, exact U_h at d = 0 is 0. A census contributes variance 0 exactly (A §8). EMPTY-path labels number 8 (F2:15); **1 hub label is EMPTY and is sampled in HUB** (F2:57).

**[D] What θ_D means for EMPTY under a deterministic producer.** If the v2.8 EMPTY rule is part of the protocol:
- A same-protocol re-analysis of an EMPTY label derives the same empty required set (the plan is hash-bound and re-derived by the verifier: UN:440-456) and applies the same rule to the same slice.
- d_EMPTY therefore counts only (i) an **implementation defect** of the rule, or (ii) an **application** error (the rule applied to a non-EMPTY label, or not applied). It is **structurally 0 for correct software**.
- θ_D restricted to EMPTY is a **rule-determinism check**, not evidence of reading reproducibility.
- The same holds for the one hub EMPTY label if it is sampled in HUB.

**Caveat.** If the re-analysis protocol lacked the v2.8 rule, a re-analyst could legitimately choose other status values (for example type_status INCOMPLETE). d_EMPTY would then measure whether the constants are protocol-implied. **The v2.8 text must therefore make the EMPTY constants part of the protocol.**

**[F/D] Does this change a frozen estimand?** **No.** The definition (B1:27), the frame, N_h, n_h, π_h, the census treatment and the combined bound (F2 §2: U = 77 including U_EMPTY = 0) are unchanged. θ_D was always defined as reproducibility, and that is still what it measures. Only the **interpretation** of the EMPTY-stratum contribution is affected.

[D] **Reporting rule, proposed for the governance act and not a freeze change:** report d_EMPTY separately with the label "rule-determinism check (deterministic EMPTY-OBJECT v1)". Never cite it as corroboration of reading reliability. No freeze is changed.

## 5. Correction to v1 (found while probing): register and P1-gap records on EMPTY labels (EG-5c)

**[F]**
- The FX positive control's EMPTY label carries **3 register and 3 P1-gap records** inherited from the historical agent object (the probe counts them: EMPTY reg 3, gap 3). **T38 still passes.**
- The verifier therefore does **not** forbid research or P1-gap records for an EMPTY label. It checks S3 and G-02 membership only (V7:175-181; V:468-470). P1-gap records carry `source_id` + `quote` (R3:320-335), i.e. corpus-quote-bearing records for a label that has no reading run.
- v1 §9 listed register/gap `working_label` as "pass-through (the verifier catches it via S3-LINT)". **That is false for an EMPTY target label**: the EMPTY path skips S3-LINT (V7:179-181). A record misfiled from another run under an EMPTY label's name would escape.

**[D] Fixes, with no verifier change:**
- (1) The assembly refuses any register or gap record whose `working_label` ≠ its run's plan owner (R3′, now a refusal for all records).
- (2) Because EMPTY has no run, the assembly guarantees reg = gap = ∅ for EMPTY labels by construction.
- (3) Fixture realism: `make_empty` also drops the EMPTY label's register and gap records. This is a test-helper change inside the EG-5 test slice. T38 must still pass.

[H-optional, default "no"] A verifier rule "EMPTY ⇒ no register or P1-gap records" would be material.

## 6. Normative text: contract amendment v2.8 (PROPOSAL, not applied) (task 5)

> **§2.6 Record identity (v2.8, EG-5b).** In every object record, research-register record and P1-gap record an
> agent writes, `run_id` is the assembly id `<batch>-R7`; `rs_id` is `<batch>-R7:<batch>:<n>` and `gap_id` is
> `<batch>-R7:<batch>:G<n>`, with `n = 1000·L + k`, where `L` is the label's 1-based manifest position and `k`
> (1…999) is the record's position among that label's records of the same kind in the run's file. This replaces
> rev3 item (3) **for these three fields only**: the run's ledger directory, the reader's `--run`, the READ-LOG,
> the `run` of every claim-evidence reference, I(run) and the binding line use the dispatched run id. The dispatch
> prompt states the concrete values. An `n` outside the label's range fails the batch (the assembly refuses).
> *Tests:* T110 two register-bearing labels with compliant ids → BATCH-PASS · T111 dispatched run id in an
> object's `run_id` → BATCH-FAIL (G-07) · T112 dispatched run id in `rs_id` → BATCH-FAIL · T113 per-run numbering
> from 1 (collision) → BATCH-FAIL (G-07 repeated) · T114 claim-evidence `run` = `<batch>-R7` → BATCH-FAIL (R7-E) ·
> T115 `n` out of range → assembly REFUSED.
>
> **§2.7 Assembly and the EMPTY object (v2.8, EG-5).** The assembly `<batch>-R7` is written by the orchestrator
> tool **inside the `ASSEMBLED` marker command** (§5.6) and re-derived without writing inside the
> `FINAL-VALIDATED` marker command; both print `sha256  path` for every input and output. It is mechanical:
> `objects.jsonl` holds one object per manifest label in manifest order; `register.jsonl` and
> `p1-gap-capture.jsonl` hold the final runs' `register.jsonl` and `p1-gap.jsonl` records, in manifest-label
> blocks, file order kept; canonical JSON lines. It never edits, filters, renumbers or repairs a record, and it
> refuses (the batch FAILS) on a missing or multiple object, a record naming another label, an unparsable or
> duplicate-key line, an `n` outside its range, or an existing assembly that differs. claim-evidence and S3-LINT
> stay in the final runs' directories. **EMPTY object.** For a label whose plan path is EMPTY (no run), the
> assembly writes the object of Annex E: every birth and `current_lifecycle` NOT-EVIDENCED-IN-CAPTURE; every
> absence ESCALATED with the stage-1 `negative_label`; one escalation `OTHER` naming EMPTY-REQUIRED-SET, plus a
> `LOAD` escalation per hit-bearing dimension of a hub label; the Annex E status constants; no source, quote,
> claim, timeline point, edge, disposition, register or P1-gap record. It is proposed by the orchestrator agent:
> `model_id` is the orchestrator's served model (equal to the batch's agents' model), `proposed_by` AI-AGENT,
> `generation_parameters` names the tool, its sha256, the rule `EMPTY-OBJECT v1` and the mode
> `DETERMINISTIC-NO-MODEL-CALL`; its content is fixed by this rule. The rule is part of the protocol, so an
> independent same-protocol re-analysis (θ_D) of an EMPTY label applies it; the EMPTY stratum's θ_D is a
> rule-determinism check. An `in_checklist` EMPTY label is not assembled; the orchestrator escalates it.
> *Tests:* T116 T38 re-expressed through the tool → BATCH-PASS · T117 EMPTY object = Annex E golden (Tier Z, Tier
> U ROW-0/3/4) · T118 hub EMPTY label → LOAD escalations with `hub_record`, BATCH-PASS · T119 a record naming the
> EMPTY label in another run's file → REFUSED · T120 ASSEMBLED printed hashes = the output files; `freeze-final`
> refuses after a change · T121 byte determinism across runs, cwd, TZ, locale and hash seed · T122 `--model-id` ≠
> the agents' model → REFUSED.

**Annex E** is the v1 §3 field table with v2's `author_role` and `generation_parameters.rule` values. It is referenced by the tool as `EMPTY_OBJECT_V1`.

**Materiality.**
- §2.6 is **material**: it changes what an agent writes. §2.7 changes no agent behaviour and nothing the verifier accepts.
- Both enter one addendum revision → **one rebind** (EG-4 procedure: the new addendum sha256 in the manifest header and in the I(run) ADDENDUM entries; the prompts re-rendered). This is a **human gate**.

## 7. Tool interface and RED tests (task 6)

```python
EMPTY_OBJECT_V1 = "EMPTY-OBJECT v1"
def empty_object(batch, label, entry, slice_obj, analysis_date, model_id, tool_sha256) -> dict      # pure; Annex E
def assemble(batch, root=CR, analysis_date=None, model_id=None, check=False, resolver_factory=None) -> dict
    # -> {"labels", "empty_objects", "objects", "register", "p1_gap", "absent_inputs", "ignored_capture_files",
    #     "printed": [(sha256, relpath), ...]}   (counts + hashes only)
```

**CLI:** `assemble B --date YYYY-MM-DD --model-id <served id> [--check] [--root DIR]`. It sits under `verify_frozen` / `assert_sealed` (OR:314-316).

**Refusals** (exit 2; nothing written):

| Id | Condition |
|---|---|
| R1 | a `_context` refusal (plan or slice hash) |
| R2 | an absent final `objects.jsonl` |
| R3 | an `objects.jsonl` with ≠ 1 object, or `working_label` ≠ the owner |
| R3′ | any register or gap record whose `working_label` ≠ the owner (v2, §5) |
| R4 | an unparsable, non-object, duplicate-key or NaN line |
| R5 | a `<B>-R7-L##*` directory for an EMPTY label |
| R6 | an EMPTY slice that is `in_checklist`, or a mechanical semantic rule ∉ {ROW-0, ROW-3, ROW-4} |
| R7 | the self-check fails (`validation`, `typing`, `meta`, `C.empty_violations`, `C.s3_violations` on constructed objects) |
| R8 | an existing output ≠ the re-derivation |
| R9 | `--check` finds a difference |
| R10 | `--model-id` ∉ MODEL_IDS, or ≠ the `model_id` of any agent object in the batch |
| R11 | a record `n` outside `[1000·L+1, 1000·L+999]` |
| R12 | `--date` not `YYYY-MM-DD` |

**Pass-through:** only the record content that the verifier owns (every gate except the identity preconditions above).

`render_prompt` gains the §2.1 RECORD IDS block with the concrete `<B>-R7`, the `n` range and the dispatched id.

**RED tests** (new `tests/test_p3b_s5_r7_assemble.py`; synthetic only; T-nn = tool unit tests, T110+ = acceptance ids from §6):

| # | Test | Expected |
|---|---|---|
| T110 | FX with (a) numbering over ≥ 2 register-bearing labels (the probe's `renumber_a`) | BATCH-PASS ([T] already green on the current verifier; a regression guard) |
| T111-T114 | the dispatched id in an object / an rs_id; the collision; a claim ref `run = <B>-R7` | BATCH-FAIL with the tags of §1/§2.1 ([T] each observed) |
| T115 / T-11 | an out-of-range `n` (no collision), and an overflow colliding with the next label | assembly REFUSED (R11). The colliding variant also fails the verifier without the tool ([T]) |
| T116 | the assembly produced by `assemble` from `run_outputs`, not by `T.write` | BATCH-PASS |
| T117 | `empty_object` golden: Tier Z; Tier U ROW-0/3/4; mech rule outside → R6 | exact dict |
| T118 | hub EMPTY slice with hit-bearing dimensions | LOAD escalations with `hub_record`; verifier HUB gates pass |
| T119 | a gap record naming the EMPTY label inside a SINGLE run's `p1-gap.jsonl` | REFUSED (R3′) |
| T120 | a synthetic transcript: the ASSEMBLED marker runs the tool | printed hashes recorded; `freeze-final` refuses after an output changes |
| T121 | two runs, varied cwd, TZ, locale, PYTHONHASHSEED | byte-identical outputs and stdout |
| T122 | `--model-id` mismatch or not in MODEL_IDS | REFUSED (R10) |
| T-01 | idempotent re-run | no write, same printed lines |
| T-02 | an existing output altered | REFUSED (R8), files untouched |
| T-03 | an absent / empty / multi-record register or p1-gap file | 0 / 0 / order kept; manifest-order blocks |
| T-04 | a stray `p1-gap-capture.jsonl` in a run directory | ignored and counted |
| T-05 | a permuted register block in the assembly | the verifier gives R7-R S3 fail (order is load-bearing) |
| T-06 | the resolver raises on any call | `assemble` succeeds (no corpus read) |
| T-07 | a planted `<B>-R7-L##` directory for the EMPTY label | REFUSED (R5) |
| T-08 | per-constant negatives through the verifier (a birth, an absence, one timeline point, the escalation removed, an S-id in the detail) | BATCH-FAIL |
| T-09 | an `in_checklist` EMPTY label | REFUSED (R6) |
| T-10 | a static scan of the constants | no S-id, no pointer word, no `B:<n>` |
| T-12 | `--check` | writes nothing |
| T-13 | fixture realism: `make_empty` without inherited register or gap records | T38 still PASS |

## 8. Remaining human decisions (task 7)

The spec defaults are adopted **without** separate decisions and bundled into HD-1's approval:
- `analysis_date` = `--date` (the UTC date of the assembly);
- `tier_causing_pair_ids = []`;
- an absent register or p1-gap file = zero records;
- no verifier check of the ASSEMBLED hashes (the tool-side check in `freeze-final` instead);
- no verifier rule for EMPTY register or gap records (the tool invariant instead);
- runbook step 6 corrected: claim-evidence and S3-LINT are not assembled; the §2.7 marker placement is added.
- **Hub EMPTY is supported, not a decision:** the frame contains one hub EMPTY label (F2:57), so refusing it would block a batch.

| # | Decision | Recommended answer |
|---|---|---|
| **HD-1** | Authorise addendum **v2.8** = §2.6 (EG-5b, option (a)) + §2.7 (EG-5, Annex E) with **one rebind** (EG-4 procedure), tests first, and the tool slice (`assemble`, the `render_prompt` RECORD IDS block, `freeze-final` check) | **Yes.** Option (a) over (b): tested, and the accepted verifier stays closed |
| **HD-2** | Accept the provenance interpretation of §3 (orchestrator-proposed, rule-fixed, disclosed in `generation_parameters`; `model_id` = the orchestrator's served model = the agents' model) as the §18 reading for EMPTY objects | **Yes.** No verifier exemption |
| **HD-3** | EMPTY status constants `UNTYPED` / `UNDECIDABLE-FROM-CORPUS` / `LAYER-UNRESOLVED` (null is infeasible, v1 H-3) | **Yes** |
| **HD-4** | An `in_checklist` EMPTY label: refuse and escalate. Run a counts-only preflight over the frame (the number of EMPTY-path labels with in_checklist) before the canary | **Refuse; run the preflight.** If the count is 0 the question is moot |
| **HD-5** | Record in the approving act that the EMPTY stratum's θ_D is a rule-determinism check, reported separately (§4); no freeze changes | **Yes** |

**Traceability:** EG-5 / EG-5b / EG-5c · v1 `EG5-SPEC-A.md` · review `EG5-REVIEW-B.md` (B-01, B-02, B-03) · probe `probes/probe_eg5b_options.py` · A §2, §5.5-5.8, §8, §10 · R3 (3), §16, §18 · B1 §1, D1 · F2 §1-§2 · G-LOG-0092 (accepted verifier) · EG-4 rebind precedent (G-LOG-0105).
