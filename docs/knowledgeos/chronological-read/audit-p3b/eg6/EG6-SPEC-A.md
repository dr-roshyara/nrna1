# EG-6 design specification (Subagent A): governed re-runs ("attempts") of a failed R7 batch

| | |
|---|---|
| Kind | DESIGN ONLY. authority: generated. No repository file is changed and nothing is dispatched. The v2.8 text in §6 is a **proposal, not applied**. |
| Problem | A failed R7 batch cannot be re-run today. That stalls the programme at the first canary failure, because PROGRAM-ACCEPTED := ∀b BATCH-PASS (A §0). |
| Evidence read | the code and contract files named in the brief · the v2.8 package `audit-p3b/eg5/EG5-V2.8-DECISION-PACKAGE.md` (§2.6, §13) · B1 (`audit-p3b/20260927_B1-…PROPOSAL.md`) · Freeze 2 (`audit-p3b/20260928_FREEZE-2-PROPOSAL.md`, F2) · EP-01 (`audit-p3b/20260926_EP-01-STATISTICAL-SPECIFICATION.md`), which B1:7 consumes for "failed units". |
| Probes | `probes/probe_eg6_retire.py`: a synthetic `tests/r7_full_fixture`, run through the **production** verifier, universe and reader grammar. It prints no S-id and no label name. There was also an in-memory `p3b_s5_state.new_run` probe. |

Citation key: as in EG-5 (A = addendum, R3 = rev3 contract, V, V7, UN, WI, OR, R6, FX), plus ST = `p3b_s5_state.py` and RS = `p3b_read_source.py`. **[F]** = fact · **[T]** = probe evidence · **[D]** = design · **[H]** = human decision.

---

## 1. Where "one run per batch" and the R7 run grammar are assumed (Q1) [F]

| Place | What it assumes | Cite |
|---|---|---|
| Contract rev3 §20 | on failure: output kept, marked FAILED, "a new run (`OB####-R2.2`) is planned with the cause recorded"; "No partial acceptance of a failed batch" | R3:1352-1356; the title line R3:1 |
| Addendum §2 | run grammar `OB####-R7-L##[U##\|S]`, assembly `OB####-R7` (§2.1) · plan hash-bound (§2.2) · namespace totality, **Legacy(B) digest "frozen at activation"** (§2.3) · "**A rerun needs a new governed run, a new plan and a human act**" (§2.4) | A:115-121 |
| State: `run_id` | re-run names exist only for R2: `B-R2`, `B-R2.<n>` | ST:67-68 |
| State: `new_run` | **refuses at revision 7**: "R7 re-run naming needs a human act, G-LOG-0102". [T] Refusal reproduced in memory | ST:194-206 (refusal at ST:200-201) |
| State: `TRANSITIONS` | FAILED and INCOMPLETE are terminal for a run; the NEXT_ACTION of FAILED is "new run (re-run from the same frozen slice)" | ST:44-57 |
| State: `revision_transition` | a one-time R2→R7 identity move (`B-R2` → `B-R7`); "NOT a general run-id remap" | ST:267-306 |
| State: `rebind_manifest` | allowed iff the COMPOSITION is identical. The composition keys (batch_id, run_id, tier, hub_batch, labels, checklist, in_checklist, weights, predicted) **exclude `legacy_dirs` and `legacy_ledger_sha256`** | ST:240, 247-264 |
| State: `check_evidence` | the report's `batch_id`/`run_id` = the current run; **`result == "PASS"`** (see §7, EG-7) | ST:111-137 |
| Reader | `R7_RUN = OB\d{4}-R7-L\d{2}(?:U\d{2}\|S)?`. An activated batch refuses every non-R7 run id ("a rerun needs a new plan"). An R7 read needs the verifier-path plan, hash-equal to the manifest, and `reader_plan_check` (a planned reading run, permitted file, owner label). The READ-LOG is written to `<run>/READ-LOG.jsonl` | RS:38-41, 150, 169-188, 100-105 |
| Universe | the plan embeds run ids `<B>-R7-L##…` (`plan_derive` builds `-R6-L`, `plan_derive_v7` rewrites them to `-R7-L`) · the assembly name is fixed `f"{batch}-R7"` · `legacy_digest` walks every `<B>-*` dir that is neither planned nor the assembly · `canonical_reader` hard-codes the R7 run grammar · `permitted_writes` = `ledger-p3b-r2/<run>/…` | R6:90-103, UN:440-449, 463-488, 694-702 |
| Witness | `BIND` is generic (`run=(\S+)`, then `run ∈ owners`) · **two effectful dispatches for one run → W2 FAIL** · a planned run never dispatched → W1 · the canary token = sha256(commit ‖ run)[:16], so it is attempt-independent | WI:41, 109-131, 228-235 |
| Verifier | batch-level `run` fixed: the regex accepts only `B-R2[.n\|S]`, `B-R5/6/7` (V:307); `run.endswith("-R7") == rev7` (V:337-338); `run0 = B-R7` (V:339) · the slices must carry `run_id = run0` (V:367-368) · entry `run_id ∈ {None, run0}` (V:340-341) · G-07 compares every record with `run` (V:511, 929, 955, 988-992) · `out_path`: `S5-VERIFY-<run>.json`, **never overwritten** | V:300-341, 367-368, 1145-1175 |
| R7 verifier | the archive is read from `<archive>/<batch>/` · WITNESS files are in `<B>-R7/` · namespace plus legacy digest from the entry | V7:94, 122-150 |
| Orchestrator tool | `prepare` requires state PREPARED at `B-R7` · `archive` refuses if an archived file is not a byte-prefix of the new session, or if archived files vanished · `freeze-final` is write-once · `verify` runs `--run B-R7` | OR:153-157, 190-216, 285-297, 329-331 |
| v2.8 §2.6 (pending) | record `run_id = <B>-R7`, `rs_id`/`gap_id` `<B>-R7:<B>:…` | package l.32-33 |

**[F] The plan and slices embed run ids.**
- The plan's `runs` keys are the literal run ids (R6:91-103; UN:440-443).
- The slices carry `run_id = <B>-R7` (V:367-368). Both are hash-bound in the manifest (`r7_plan_sha256`, `slice_sha256`).
- **Any new run id therefore means a new plan hash** and, if the batch-level id changes, new slices.

## 2. Design options (Q2)

### (a) Attempt suffix: `<B>-R7.<m>` with runs `<B>-R7.<m>-L##…`

**Changes needed:**
- **Reader** `R7_RUN` (RS:150) and its default-deny (RS:173-177).
- **Universe** `canonical_reader` (UN:699-702) and `plan_derive_v7` (it must be parameterised by attempt, UN:440-443). Also the assembly name (UN:477).
- **Verifier** run regex (V:307), the `-R7` suffix check (V:337-338), `run0` and the slice-id rule (V:339-341, 367-368), G-07 and `out_path`.
- A **new plan per attempt** (new `r7_plan_sha256`) and the v2.8 §2.6 id `<B>-R7.<m>`.
- The state's `run_id` and `new_run`, and the orchestrator's `_context`/`prepare`.

[T] Today an attempt-suffixed directory is simply an unplanned namespace violation (`R7-U namespace … <B>-R7.2-L01 …`).

**Materiality:** it changes the **production-audited reader and the ACCEPTED verifier** (G-LOG-0092 → a new independent audit, HD-9), plus the plan/manifest binding. The attempts would be distinguishable by name, but the cost and audit risk are high.

### (b) Same ids; retire the failed attempt into a governed Legacy(B) member — RECOMMENDED

**Mechanism.**
1. Before attempt m+1, a new tool step `orchestrate retire B` **moves** attempt m's entire ledger footprint into one directory `ledger-p3b-r2/<B>-R7.A<m>/`, keeping the original names inside. The footprint is every planned run directory plus the assembly `<B>-R7/` (INPUT-MANIFESTS, WITNESS*, R7-PROVENANCE, assembly files).
2. The move is committed as a pure rename. The tool writes `RETIREMENT.json` inside it: per-file `old path → new path, sha256`, attempt m's archive digests, the cause class, the verify report path and sha, and a `pre_digest == post_digest` proof.
3. It moves the external archive `<archive>/<B>/` → `<archive>/<B>.A<m>/` (outside git, FD-4′). Its digests are recorded in `RETIREMENT.json`.
4. The manifest entry gets `legacy_dirs += ["<B>-R7.A<m>"]` and `legacy_ledger_sha256 = legacy_digest(…)`, re-frozen. The manifest is regenerated and the state is rebound through the existing `rebind_manifest`: the composition is identical because the legacy fields are not composition keys (ST:240).
5. The state appends attempt m+1 of run `<B>-R7` (PREPARED), with the cause class and the retirement record sha, in the hash-chained history.
6. Attempt m+1 runs the **unchanged runbook in a fresh orchestrator session**. A reused session would contain two effectful dispatches per run and fail W2 (WI:228-230).

**[T] Probes** (production verifier, synthetic fixture; the retired tree is a synthetic stand-in of all planned run directories plus the assembly):

| Variant | Verdict | Failure tags |
|---|---|---|
| baseline | BATCH-PASS | — |
| **attempt 2 + retired `<B>-R7.A1` listed, digest re-frozen** | **BATCH-PASS** | — |
| retired directory present but not listed (no governed act) | BATCH-FAIL | `R7-U namespace: directory … is neither a planned run, the assembly nor a frozen legacy directory` + `legacy directories differ from the digest` |
| listed, digest not re-frozen | BATCH-FAIL | `R7-U namespace: legacy … differ` |
| retired evidence **edited** after the act | BATCH-FAIL | `R7-U namespace: legacy … differ` |
| retired evidence **partly deleted** | BATCH-FAIL | `R7-U namespace: legacy … differ` |
| reader grammar / `canonical_reader` given the retired name as `--run` | refused (no match) | — |

Consequences:
- The failed attempt's evidence is **preserved, integrity-bound by the existing legacy digest, and tamper-evident**.
- No reader call can ever be attributed to a retired directory.
- **The verifier, the reader, the plan, the slices, the views, the I(run) derivation and the prompts are all unchanged.** Attempt m+1's prompts are byte-identical to attempt m's: same binding line, same canary.

**What changes:**
- *Contract text:* §2.3 "frozen at activation" becomes "frozen at activation and re-frozen only by a governed retirement". §2.4's rerun sentence is replaced by the attempt rule (§6).
- *State:* `new_attempt` for revision 7, replacing the refusal at ST:200-201, plus attempt-aware history and evidence (a report's sha is never reused across attempts; a per-attempt report path `S5-VERIFY-<B>-R7.A<m>.json` through `--out`, because `out_path` never overwrites, V:1171-1175).
- *Orchestrator:* the `retire` command. `freeze-final` additionally refuses if any archived transcript digest of the new attempt equals a retired attempt's digest (anti-replay).
- **No change to the reader or to any verifier module.**

**How attempts are distinguished:**
- *Witness:* each attempt has its own archive and session, and its WITNESS files live in its own assembly directory.
- *State:* an attempt number on each history entry.
- *Git:* the retirement commit.
- *Ledger:* the retired prefix `.A<m>`. This is not an R7 run id, so it is never readable or writable by an agent.

### (c) Other options: all REJECTED

| Option | Why rejected |
|---|---|
| (c1) Overwrite or append in the same run directories | destroys or blends evidence (rev3 §20 "never deleted"). The W5 last-write check (WI:338-341) and W6 would blend attempts |
| (c2) Re-run only the failed labels of a batch | the verifier's unit is the batch; mixing attempts inside one assembly breaks the witness freeze; and rev3 §20 says "No partial acceptance" |
| (c3) A new batch id for the re-run | it changes the manifest composition, i.e. the frame |
| (c4) A new plan with other `L##` | `L##` = the manifest position, which is frozen (A:115) |

## 3. Statistics (Q3)

**[F] What the frozen and approved design says:**
- **The unit outcome is the label's single final object.** "each with exactly one final S5 object"; "Its S5 object is the unit's outcome" (EP-01:16-18).
- **Labels without an accepted object** ("a FAILED label with no authorized re-run, or one still FAILED after it") stay in the frame as NOT-ASSESSABLE, **worst case** in the primary bound. They are never dropped, and there is **no replacement draw** (EP-01:20, 118-128; B1:95).
- **E-3 (census):** the "protocol failure rate: labels or batches that FAILED verification, **and re-runs**", observed exactly (EP-01:54).
- **The sample:** drawn and frozen **before any S5 output**; "A sampled label is never swapped out, whatever its S5 outcome"; audited **after acceptance**. The auditor never sees the S5 object, records or read log (EP-01:78-80). F2 §2 fixes 231 units (F2:46-55). B1 uses the same objects for θ_A (B1:28).
- **Canary** (B1:96): stop at ≥ 50% FAIL or a systematic pattern; the repair goes to the runbook or prompt, not the verifier.
- **F2 contains no re-run rule** (grep: none).

**Conclusion [D].**
- **Unbiasedness.** HT/exact inference is **design-based**: D is a fixed population quantity of the final objects, and the sample is drawn independently of S5. A re-run policy therefore leaves the estimators **unbiased for D as defined on final objects** provided it is:
  - (i) **sample-blind:** it never consults the sample and is applied identically to every batch;
  - (ii) **audit-blind:** no audit or adjudication result can trigger or select an attempt;
  - (iii) **mechanical and pre-declared** before any S5 output, i.e. before the canary. The canary batches are part of the population (RB §3).
- **Interpretation.** Re-running shifts **what θ describes**: disagreement among verifier-accepted objects of the S5 process under the declared retry policy. It does not bias the estimate of that quantity.
- **The rejection history is not lost:** it is E-3, reported alongside θ.
- **The sampled label whose first attempt failed** is audited on its final accepted object, exactly like every other label. If no attempt passes within the cap, it is NOT-ASSESSABLE (worst case). It is never replaced.

**Rules that keep θ_D, θ_A and θ_E well-defined and unbiased (RR-1…RR-7):**

| Rule | Content |
|---|---|
| RR-1 | **Triggers:** only the batch verifier verdict (BATCH-FAIL, or an INCOMPLETE dispatch) with a mechanically classified cause (§4). A BATCH-PASS batch is **never** re-run, whatever its content, its audit outcome or how it looks |
| RR-2 | **Sample-blind:** the retire/attempt tools take no sample input and read no sample artifact (a static test) |
| RR-3 | **Estimation object** = the object of the batch's **first** attempt that reaches BATCH-PASS and ACCEPTED. There is never a choice among passing attempts, and no further attempt after a pass |
| RR-4 | **Failed attempts** never enter estimation. They are never shown to the auditor, re-analyst or adjudicator (EP-01:80 blindness extends to **all** attempts). They are preserved as Legacy(B) and counted in E-3 (attempts and causes per batch and class) |
| RR-5 | **Cap:** at most **M attempts** per batch (proposed M = 3), fixed before the canary. Beyond the cap the batch's labels are NOT-ASSESSABLE (EP-01 §7) |
| RR-6 | **Instrument invariance:** all attempts use the same frozen contract, plan, slices and tools. An instrument repair (runbook or prompt) is admissible only at the canary gate (B1:96). After the canary the instrument is frozen, and any further change is a STOP and a human gate. The state records the prompt bundle sha per attempt |
| RR-7 | **Stop rule preserved:** it is evaluated on every attempt set, and a batch whose attempts m and m+1 fail with an identical failure-tag signature is presumed systematic (class D) and stops. Retries never mask the stop rule |

**[H] Rules that would require changing a freeze or an approved design** (identified, not proposed):
- estimating from failed attempts, or from "the best" attempt (changes E-1's object, EP-01:16-18);
- replacement draws for NOT-ASSESSABLE labels (EP-01:78, 128);
- re-running an ACCEPTED batch (EP-01:79 audits the accepted object);
- declaring the policy after S5 outputs exist. That is not a numeric freeze change, but it breaks pre-registration. **The policy must be approved before the canary.**
- The cap M and the classes **fill a slot EP-01 left open** ("authorized re-run"). They are not a change to a frozen value, provided they are declared before any output.

## 4. Failure classification (Q4) [D]

Diagnosis follows the stop-rule order: **runbook → prompt → agent behaviour** (RB §3; B1:96). Classes:

| Class | Meaning | Re-run? | Examples (verifier tags) |
|---|---|---|---|
| **X** instrument / environment / runbook | the failure is not a measurement: harness, storage or the orchestrator's own artifacts | **yes**, after repair (repairs to the runbook or prompt only at the canary gate, RR-6) | `W1-TRANSCRIPT-MISSING`, `W1-HARNESS-COUNT-MISSING`, `W1-NOTIFICATION-ABSENT` (freeze before completion), `W4-DENIED` (a harness permission), `W1-PARSE`/`W1-CHAIN` from storage, `R7-U inputs … missing`/`≠ frozen` from orchestrator-written artifacts (views, I(run), directories), a planned run never dispatched, `ASSEMBLY-REFUSED` from a tool defect, W8 calls traced to a prompt defect |
| **A** agent protocol violation | the agent broke the contract; its output is **not an admissible measurement** (it cannot pass acceptance) | **yes**, with the same instrument; it counts toward M | `W1-ZERO-TOOL`, `W1-DECLINED`, W8 unauthorized calls with a correct prompt, R7-U object Σ/typing/META, R7-E anchoring/coverage/claim/READ-LOG, R7-R S1-S3/summary/lifecycle, the historical G-xx content gates, v2.8 §2.6 id violations |
| **D** design / frozen-artifact defect | a deterministic failure that any attempt would repeat (a frozen plan/slice/contract rule is unsatisfiable) | **no**: STOP, human (a contract/plan change) | plan ≠ derivation, revision binding, a rule that no conforming output can meet, an identical signature on two attempts (RR-7) |
| **S** security / integrity | SEAL exposure, archive tampering (W7 mismatch without a storage explanation), a replayed transcript | **no**: STOP, P3B-ESC, human | SEAL stop (A §5.5), W7 digest mismatch, retired-digest reuse |
| (U) undetermined | BATCH-UNDETERMINED (the archive is absent) | **no re-run**: restore the archive and re-verify | `UNVERIFIABLE-WITNESS` |

**What must stand** (never re-run): any BATCH-PASS output, including every reading judgment the verifier accepts. That is precisely what θ_D measures. Audit discordance **never** triggers a re-run (RR-1).

The classification is recorded mechanically by the tool from the verify report's tags (X is set only with a recorded runbook or prompt diagnosis). An ambiguous classification defaults to **D** (stop, human).

## 5. Recommendation (Q5)

**Adopt option (b)** (retire into a governed Legacy(B) member, same ids, fresh session) with RR-1…RR-7 and the §4 classes.
- It needs **no reader and no verifier change** ([T]: BATCH-PASS with a listed and re-frozen retired attempt; fail-closed otherwise).
- It reuses the plan, slices, views and prompts unchanged.
- It preserves failed evidence under the existing legacy digest.
- It keeps the estimands well-defined (§3).
- Code changes: `p3b_s5_state.py` (`new_attempt`, attempt-aware history and evidence) and `p3b_s5_r7_orchestrate.py` (`retire`; `freeze-final` anti-replay). Tests first.

**Materiality.**
- Execution-material: **no**. Agents, the reader and the verifier accept exactly what they accept today.
- Governance-material: **yes**. It replaces the frozen addendum sentences §2.3 ("frozen at activation") and §2.4 ("a new plan"), and it declares a statistical policy.
- It is addendum text, so it **can share the v2.8 rebind** as a separable part (d). It must be approved **before the canary**, because a policy declared afterwards is not pre-registered.

## 6. Normative text: v2.8 §2.8 (PROPOSAL, not applied)

> **§2.8 Attempts of a failed batch (v2.8, EG-6).** A batch whose current attempt ends FAILED or INCOMPLETE may be
> run again as **attempt m+1 of the same runs**: same plan, slices, views, run ids, prompts and record identity
> (§2.6). Before it, the orchestrator **retires** attempt m: every planned run directory and the assembly move,
> unaltered, into `ledger-p3b-r2/<batch>-R7.A<m>/` with a `RETIREMENT.json` (per-file old path, new path and
> sha256; attempt m's transcript digests; cause class; verify report), the transcript archive moves to
> `<archive>/<batch>.A<m>/`, and the manifest entry's `legacy_dirs` and `legacy_ledger_sha256` are re-frozen
> (§2.3: Legacy(B) is frozen at activation and re-frozen only by a retirement). Nothing is deleted. Each attempt
> uses a fresh orchestrator session; its transcripts must not reuse a retired digest. **Policy (pre-registered):**
> an attempt is triggered only by a verifier BATCH-FAIL or an INCOMPLETE dispatch whose cause is class X
> (instrument, environment, runbook) or A (agent protocol violation); class D (design) and S (security, SEAL)
> stop S5 for a human act; a BATCH-PASS batch is never run again. At most M = 3 attempts per batch. The rule never
> consults the audit sample or any audit result. The estimation object of a label is its batch's first attempt that
> reaches BATCH-PASS and is accepted; failed attempts never enter estimation, are never shown to an auditor or
> adjudicator, and are reported in E-3. Instrument repairs (runbook, prompt) are admissible only at the canary
> gate. This replaces §2.4's sentence "A rerun needs a new governed run, a new plan and a human act"; the reader's
> default-deny is unchanged.

**RED tests** (synthetic; T130+ are acceptance ids, T-nn are tool/state unit tests):

| # | Test | Expected |
|---|---|---|
| T130 | attempt 2 after retirement: A1 listed, digest re-frozen | BATCH-PASS ([T] green today) |
| T131 | the retired directory unlisted / listed but not re-frozen / edited / partly deleted | BATCH-FAIL R7-U namespace ([T] each) |
| T132 | a reader call with `--run <B>-R7.A1…` | refused (default-deny; [T] regex) |
| T133 | attempt 2 dispatched in the attempt-1 session (two effectful dispatches per run) | BATCH-FAIL W2 (WI:228-230) |
| T134 | attempt 2's archive reuses an attempt-1 subagent transcript digest | `freeze-final` REFUSED |
| T-01 | `new_attempt` at revision 7: only from FAILED or INCOMPLETE; needs a class ∈ {X, A} and a retirement record sha; refuses D/S; refuses attempt > M; appends history with an attempt number; the hash chain is intact | pass |
| T-02 | `new_attempt` on a BATCH-PASS / VERIFIED / ACCEPTED batch | refused (RR-1/RR-3) |
| T-03 | `retire`: pre_digest = post_digest; every file sha unchanged; refuses if attempt m's archive is absent or attempt m is not terminal; never deletes | pass |
| T-04 | `rebind_manifest` with only the legacy fields changed | accepted (composition identical) |
| T-05 | evidence: a VERIFIED transition with attempt 1's report while attempt 2 is current | refused (the sha was already used) |
| T-06 | static: `retire`, `new_attempt` and the classifier read no sample or audit artifact | pass (RR-2) |
| T-07 | classifier: the tag set → class table (§4); ambiguous → D; an identical signature on attempts m and m+1 → D | pass (RR-7) |
| T-08 | prompts of attempt m+1 are byte-identical to attempt m's | pass |
| T-09 | `S5-VERIFY-<B>-R7.A<m>.json` naming; attempt 1's report is never overwritten | pass |

## 7. Adjacent finding outside EG-6 (reported, not solved): EG-7

**[F]**
- `check_evidence` requires the verify report's `body.result == "PASS"` (ST:131-132).
- The R7 verifier emits **only** `BATCH-PASS | BATCH-FAIL | BATCH-UNDETERMINED` and never "PASS". This is asserted by T80 (`test_p3b_s5_r7_full.py`:113-114; positive control :98).
- **So `state transition B VERIFIED` (runbook step 9) is refused for every R7 batch**, including a passing canary.
- Fix: revision-aware evidence, i.e. accept `BATCH-PASS` for revision 7. That is a state-module change, tests first. It changes no verifier and no contract. I recommend a backlog item and bundling it with the EG-6 state slice.

## 8. Human decisions

| # | Decision | Recommended answer |
|---|---|---|
| **HD-6.1** | Adopt option (b) and v2.8 §2.8 as separable part (d) of the same rebind, with the state/orchestrator slice, tests first | **Yes** |
| **HD-6.2** | Declare the re-run policy (RR-1…RR-7, classes X/A/D/S) **before the canary** as the pre-registered "authorized re-run" rule that EP-01 §1 leaves open | **Yes**: before any dispatch |
| **HD-6.3** | The cap M | **M = 3 attempts** |
| **HD-6.4** | Class A (agent protocol violations) re-runnable, versus only class X | **Re-runnable.** Otherwise one agent's violation makes every label of its batch NOT-ASSESSABLE and PROGRAM-ACCEPTED unreachable. Admissibility is kept by RR-1…RR-4, and the violation stays visible in E-3 |
| **HD-6.5** | Open EG-7 (state evidence `PASS` vs `BATCH-PASS`) as a backlog item | **Yes**: it blocks runbook step 9 independently |

**Traceability:** A §2.1-2.4 · R3 §20 · ST:44-68, 111-137, 194-306 · RS:38-41, 150-188 · UN:440-488, 694-702 · WI:41, 109-131, 228-235 · V:300-341, 367-368, 1145-1175 · V7:94, 122-150 · OR:153-157, 190-216, 285-297 · v2.8 package §2.6, §13 · EP-01 §1, §3, §4, §7 · B1:7, 27-31, 95-96 · F2 §2 · probe `probes/probe_eg6_retire.py`.
