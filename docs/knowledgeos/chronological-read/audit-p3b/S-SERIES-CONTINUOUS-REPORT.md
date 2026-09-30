# S-Series continuous research report (cumulative; append-only in spirit)

| | |
|---|---|
| **Kind** | ⚠ authority: generated. Engineering and research evidence. It recommends; it never authorizes |
| **Operating mode** | a bounded loop between human gates. The orchestrator (Claude) defines the task; Subagent A builds; Subagent B independently attacks; the orchestrator reconciles; the next human gate is stated explicitly |
| **Rule** | historical findings are never rewritten. Corrections are appended with a date |

## A. Current scientific state (frozen, authoritative)

- **Freeze 1**, B1 v2 (G-LOG-0094):
  - θ_D disagreement; θ_A adjudication; θ_E only relative to a justified reference R*;
  - Model 0 per hypothesis; Holm (confirmatory) / BH (exploratory); B = 9,999; H-19 last.
- **Freeze 2** (G-LOG-0103):
  - frame `d0c972bf…`;
  - strata HUB 66 / EMPTY 7 / MULTIROW 4 / DECOMPOSED 173 / SINGLE 1,725;
  - sample 33/7/4/87/100 = 231 (`e3f3ed76…`, anchor `8e0d9cb4…`).
- **Execution contract:** R7 addendum v2.7 `9523c712…` (G-LOG-0104), bound in production (G-LOG-0105).
- **Production state:**
  - manifest `9b172123…`;
  - P3B-STATE: 396 batches PREPARED at R7;
  - 1,975 slices and 396 plans at `_batch_input_r2/s5/rev7`;
  - H-19 SEALED.
- **S5 measurement:** not started. No agent has been dispatched and no sampled content has been read.

## B. Completed gates

| Gate | Record |
|---|---|
| R7 designed, independently audited, accepted | G-LOG-0086…0092 |
| Freeze 1 | G-LOG-0094 |
| 12 binary decisions; v2.6-DC3 | G-LOG-0096, 0099, 0100 |
| R7 production activation (AG-2) | G-LOG-0102, `5469a014f` |
| Freeze 2 | G-LOG-0103, `51e16f860` |
| EG-2 SLICE-VIEW v2.7 · EG-1 orchestrator | G-LOG-0104, `5f2e7abe1`, `e7a09dc1f` |
| EG-4 v2.7 production rebind | G-LOG-0105, `598f0a0f0` |
| Canary pre-check (read-only): 9/10 pass; EG-5 found | `9a56238a1`, `audit-p3b/20260929_S5-CANARY-PRECHECK.json` |

## C. Current task — EG-5 (batch assembly and the EMPTY-label object)

- **The gap:**
  - runbook step 6 has no tool and no rule;
  - the EMPTY label of OB0114 (7 EMPTY labels in the frame) has no run, so its object has no producer;
  - a hand assembly would be an unspecified, unwitnessed write.
- **Loop:**
  - A: formal specification;
  - B: adversarial review;
  - reconcile;
  - tests-first implementation if the semantics survive;
  - B: implementation review;
  - verification;
  - human gate.

## D. Evidence

**2026-09-29 — EG-5 specification by Subagent A** (`audit-p3b/eg5/EG5-SPEC-A.md`; read-only; synthetic fixtures only):
- **EMPTY semantics.** The plan gives an EMPTY label `runs = {}`, so the object's only possible producer is the assembly. Its `run_id` is `<B>-R7`, which matches the verifier's S3 call (`final or f"{batch}-R7"`) and G-07.
- **Evidence-free by construction.** The object carries no S-id. The verifier rejects any source-requiring claim on an EMPTY object (`p3b_s5_r7_reconstruction.py:176-179`).
- **Provenance gates** (`p3b_s5_verify.py`):
  - G-07 forces `model_id` to the served model id and requires a `generation_parameters` key (content not checked).
  - G-05 forces `proposed_by = AI-AGENT`; G-11 forces `record_status = PROPOSED`.
  - A deterministic producer therefore cannot state its true provenance in those fields without a verifier change.
- **Witness.** Only Agent calls and `: S5-ORCH …;` Bash markers are witnessed, and printed hashes are checked only for UNITS-VALIDATED.
- **Naming.** Agents write `p1-gap.jsonl`; the verifier reads the assembly's `p1-gap-capture.jsonl`. The rename happens only in the assembly.
- **EG-5b (new, MATERIAL), confirmed by the orchestrator:**
  - rev3 contract line 7, item (3): "`<run_id>` is the run id you are dispatched with; every path, reader call and record uses it".
  - Addendum §2 defines the run grammar but never says which run id records carry.
  - G-07 requires `run_id = <B>-R7` on every object (`p3b_s5_verify.py:511`), and `rs_id`/`gap_id` of the form `<B>-R7:<B>:<n>` / `:G<n>`, unique across the batch (`p3b_s5_verify.py:929-999`).
  - So a compliant agent's records fail G-07, and numbering by several final runs collides.
  - The synthetic positive control passes only because the fixture rewrites every record id to `<B>-R7` (`r7_full_fixture.py:155`).
  - The assembly cannot repair it: rewriting ids breaks the S3-LINT `records_sha256` binding.
  - Consequence: **the canary would fail on G-07 for every final run.** The fix is material (addendum text for agents, or a verifier change).

**2026-09-29 — orchestrator counts-only EMPTY preflight** (HD-4; production manifest, plans and slices as metadata only):
- **Counts.** The frame has 8 EMPTY-path labels, in 8 batches. 1 is a hub label (OB0386 L05, in a hub batch, so EG-3-gated).
- **FACT: the in-checklist test must use the slice.** G-09 reads the **slice's** `in_checklist`. By that test **2 EMPTY labels are in-checklist** (OB0110 L04, OB0225 L03). The canary's EMPTY label (OB0114 L04) is not. The manifest entry's `in_checklist` is a dict label→bool, not a list.
- **SCIENTIFIC RISK (new).** Spec v2's rule "an in-checklist EMPTY label is not assembled; the orchestrator escalates it" leaves those 2 batches without an object. They could then never reach BATCH-PASS, and PROGRAM-ACCEPTED (∀b BATCH-PASS) would become unreachable.
- **Next.** Revision round 2: A evaluates a vacuous-examination rule vs refusal; B then reviews the consolidated package.

## E. Review (Subagent B)

**2026-09-29 — review of spec v1** (`audit-p3b/eg5/EG5-REVIEW-B.md`; a different model; citations re-derived independently; two new synthetic probes plus T38). **Verdict: SPEC-NEEDS-REVISION.**
- **B-01 (MATERIAL): EG-5b has a universal blast radius.**
  - A single SINGLE label with records fails G-07 when it carries its dispatched run id. This holds on the object alone and on the register id alone.
  - The verifier fixes one batch-level `run_id` for the whole R7 call.
  - So EG-5b alone blocks **both** canary batches (OB0012 too), independently of EMPTY.
- **B-02 (MATERIAL; sharpens H-1).** Forced provenance on a deterministic object conflicts with rev3 §18 ("different model ⇒ different run").
- **B-03 (MINOR).** Under a deterministic producer, the EMPTY stratum's θ_D is tautologically zero. Freeze 2's exact bound already expects zero. This should be noted explicitly in the governance act.
- **Checked clean:**
  - the EMPTY field table;
  - the no-manufactured-evidence argument;
  - the EMPTY vs not-read / not-found / GENUINELY-UNDEFINED distinction;
  - the H-3 infeasibility argument;
  - the witness ORCH regex;
  - the S3-LINT binding;
  - G-02 protection against duplicate, foreign or missing objects.
- **No path found** for false-evidence creation, absence/non-observation confusion, or verifier bypass.

**Orchestrator reconciliation:**
- B-01 is accepted; EG-5b is the primary blocker.
- The verifier's id grammar is `<B>-R7:<B>:\d+`. So a label-scoped numbering (n = 1000·L + k) with `run_id = <B>-R7` on records could be an **addendum-only** fix, leaving the audited verifier unchanged. Sent to A for analysis and synthetic testing (revision round 1).

**2026-09-29 — revision round 1** (A v2 `audit-p3b/eg5/EG5-SPEC-A-v2.md`; B re-review `audit-p3b/eg5/EG5-REVIEW-B-v2.md`). **Verdict: SPEC-NEEDS-REVISION (minor).** The core design and the option (a) recommendation stand; B-01, B-02 and B-03 are genuinely fixed.
- **C-01 (MATERIAL) — EG-5c is already inside the accepted verifier.**
  - The positive control writes the EMPTY label's inherited 3 register + 3 gap records into the assembly, and T38 passes.
  - The verifier does not reject evidence-bearing records on an EMPTY label.
  - A's proposed fixture clean-up would hide this. B recommends recording it as a residual risk (HD-6), with a regression test showing the verifier alone tolerates it.
- **C-02 (MINOR).** The `--model-id` check (R10) proves self-consistency, not truth against an independent record.
- **C-03 (MINOR).** The θ_D interpretation note must also cover the one hub-EMPTY label, which contributes to the sampled HUB stratum.
- **Probe audit.** Four of the five mutators are real tests. The overflow probe tested only the colliding case. B's supplementary probe confirms that an out-of-range id with **no** collision passes the verifier (BATCH-PASS), so the assembly-side refusal R11 is necessary.

**2026-09-29 — round 2 (A v3: in-checklist EMPTY) and round 3 (B v3: the consolidated package).**
- **A v3.**
  - Vacuous examination (`checklist_examined = [1..23]`, disclosed as VACUOUS-OVER-EMPTY-REQUIRED-SET) is truthful under rev3 §9C: the field records which questions were posed, not answers.
  - It passes the unchanged G-09. The refusal alternative makes PROGRAM-ACCEPTED unreachable.
  - The orchestrator re-ran the checklist probe: all 7 variants identical.
- **B v3: PACKAGE-NEEDS-REVISION** (D-01 citation, D-02 coverage; no design problem). Both fixed; see the package §11.

## F. Resolution

**2026-09-29 — orchestrator reconciliation after round 1. The design is accepted for the human gate.**

**1. EG-5b: option (a), an addendum v2.8 rule; the verifier is UNCHANGED.**
- Record fields carry `run_id = <B>-R7`.
- `rs_id = <B>-R7:<B>:<n>` and `gap_id = <B>-R7:<B>:G<n>`, with `n = 1000·L + k` (L = the label's manifest position).
- Directories, the reader's `--run`, the READ-LOG, claim-evidence `run` references, I(run) and the binding line keep the dispatched run id.
- The dispatch prompt gains a RECORD IDS block with the concrete prefix and range. It is legitimate only once v2.8 exists.
- Evidence: A's probe, re-run by the orchestrator and by B, with identical results. Compliant → BATCH-PASS; dispatched id on an object, dispatched id on the register, per-run numbering, a claim `run` of `<B>-R7`, and a k overflow → BATCH-FAIL.

**2. EG-5: `orchestrate assemble B` is added to the EG-1 tool, run inside the witnessed `: S5-ORCH ASSEMBLED batch=B; …` marker command.**
- It concatenates the final runs' objects, register and P1-gap records in manifest-label order.
- It renames `p1-gap.jsonl` → `p1-gap-capture.jsonl`.
- It derives the EMPTY objects by the rule EMPTY-OBJECT v1 (Annex E of the v2.8 draft), which asserts no corpus content.
- Output is canonical and write-once, with refusals R1–R12.
- claim-evidence and S3-LINT stay in the final-run directories (a runbook step 6 correction).

**3. Provenance.** The EMPTY object is proposed by the orchestrator AI agent (the same served model as the agents; R10) through a hash-bound deterministic rule. This is disclosed in `generation_parameters` (`mode: DETERMINISTIC-NO-MODEL-CALL`, tool sha256, rule) and in `author_role`. It is consistent with rev3 §18 once record runs are the assembly. No verifier exemption is needed.

**4. EG-5c (C-01): recorded as a verifier residual; not fixed now.**
- The assembly refuses a register or gap record whose `working_label` is not its run's plan owner (R3′). EMPTY labels get none by construction.
- **The fixture is NOT sanitized.** A regression test keeps documenting that the verifier alone tolerates such records.
- Closing it in the verifier is a separate, later, material decision (HD-6).

**5. θ_D (B-03, C-03).**
- The estimand, frame, bounds and freezes are unchanged.
- The interpretation is recorded: on the EMPTY stratum (census of 7) and for the hub-EMPTY label inside the HUB stratum, θ_D is a rule-determinism check. It is reported separately under that name.
- The v2.8 text must put the EMPTY constants into the protocol, so a re-analyst applies the same rule.

## G. Tests

- **2026-09-29 (round 3):**
  - `test_p3b_s5_r7_contract_rebind` 8/8 OK, including the new `FreshTargetRebind` (v2.7 → fresh target; D-02);
  - B's non-colliding out-of-range probe re-run: BATCH-PASS (the verifier cannot catch it, so the tool's R11 is needed; D-01);
  - the checklist probe re-run: 7/7 as reported.

- **2026-09-29, synthetic fixtures only:** A's option probe (7 variants) was reproduced by the orchestrator and by B. B's supplementary probes covered EG-5c counts and the non-colliding overflow. T38 is still OK.
- **No implementation yet:** the RED test list is in spec v2 §7, and v2.8 would add T110–T122 per the draft (renumbered at adoption).

## H. Rejected alternatives

- **(b) A verifier change** accepting the dispatched final-run id. It reopens the independently audited verifier (G-LOG-0092), needs a new audit and touches G-07, G-12 and the acceptance fixtures; a rebind is still needed for the EG-5 text anyway.
- **A prompt-only record-id rule.** It contradicts rev3 item (3).
- **The assembly rewriting ids.** It breaks the S3-LINT `records_sha256` binding.
- **Non-numeric ids.** They fail the verifier's `\d+`.
- **A null + SCHEMA-LIMITATION route for the EMPTY statuses.** It needs a register record with an S-id anchor, which is impossible for EMPTY.
- **A hand assembly by the orchestrator session.** It is an unwitnessed, unspecified write.
- **Sanitizing the fixture to hide EG-5c.** It hides a real verifier property.

## I. Remaining risks

- **EG-3:** the hub-slice reading protocol, needed before the 14 hub batches. Not a canary blocker.
- **EG-5c:** the verifier tolerates evidence-bearing records on EMPTY labels. It is mitigated in the tool (R3′), not closed in the verifier (HD-6).
- **C-02:** the model-id consistency check (R10) is not an independent truth check.
- **In-checklist EMPTY labels:** the count is unknown until a counts-only preflight runs. They are refused and escalated (HD-4).
- **Deferred:** RC-02; RI-1; R7 re-run naming (a failed R7 batch has no defined re-run).

## J. Next human authorization gate

**2026-09-29 (superseded later the same day by the consolidated package below) — STOP: the EG-5 decision package.**

**Why a human is required:** v2.8 changes the frozen contract text in a material way. Agents write different record ids, and the orchestrator produces EMPTY objects. The addendum sha is bound in production, so v2.8 needs a rebind like EG-4.

**Decisions (recommended answer in bold):**

| # | Decision | Recommendation |
|---|---|---|
| HD-1 | Authorize addendum v2.8 (§2.6 record identity, option (a); §2.7 assembly + EMPTY-OBJECT v1, Annex E) + one production rebind + the `assemble` tool slice, tests first. Bundled defaults: `analysis_date` from `--date`; `tier_causing_pair_ids = []`; an absent register/P1-gap file = 0 records; a tool-side ASSEMBLED hash check in `freeze-final`; the runbook step 6 correction | **yes** |
| HD-2 | Accept the rev3 §18 provenance interpretation for EMPTY objects (orchestrator AI agent + deterministic disclosed rule; no verifier exemption) | **yes** |
| HD-3 | EMPTY status constants UNTYPED / UNDECIDABLE-FROM-CORPUS / LAYER-UNRESOLVED | **yes** |
| HD-4 | An in-checklist EMPTY label is refused and escalated; a counts-only preflight before the canary | **yes** |
| HD-5 | Record that θ_D on EMPTY (and the hub-EMPTY label) is a rule-determinism check, reported separately; no freeze change | **yes** |
| HD-6 | EG-5c: accept as a tool-mitigated residual now; closing it in the verifier is a later, separate decision | **yes (defer the verifier closure)** |

**After approval, the orchestrator executes (bounded):**
- v2.8 text + tests (RED → GREEN) + A/B implementation review;
- the `assemble` slice;
- the rebind (staged);
- `prepare --check`, the H-19 seal and the full suite;
- the canary pre-check re-run.

It then STOPS at the S5 canary authorization. Nothing is dispatched until then.

**Scientific invariants confirmed unchanged:** population · labels · strata · frame · Freeze 1 · Freeze 2 · seed · sample · θ_D/θ_A/θ_E definitions · H1–H5 · Model 0 · estimands.


**2026-09-29 — STOP: ONE consolidated decision** (`audit-p3b/eg5/EG5-V2.8-DECISION-PACKAGE.md`).
- **The decision:** APPROVE the EG-5/v2.8 bounded change as specified, or REJECT / REVISE it.
- **It replaces HD-1..HD-6.** HD-4 became vacuous examination; HD-6 became the recorded EG-5c residual.
- **Why a human is required:**
  - §2.6 changes what agents write (a material contract change);
  - the addendum sha is production-bound, so a rebind is needed.
- **Review status:** reviewed by B across three rounds. The last round's findings are fixed. Status: READY.
- **After approval (bounded, autonomous):**
  - v2.8 text + the `assemble` tool, tests first;
  - A/B implementation review;
  - the staged rebind;
  - `prepare --check`, the H-19 seal and the full suite;
  - the canary pre-check re-run;
  - STOP at the S5 canary authorization.

---

## Loop log (from 2026-09-29, under the standing continuous protocol)

**2026-09-29 — protocol adopted.**
- The human is the scientific/governance authority; Claude orchestrates between gates; A builds; B attacks; this report is the research trace.
- The EG-5/v2.8 gate stays **open and is not bypassed**.
- Work continues only on items that do not depend on it.

**2026-09-29 — loop EG-6 started: governed re-run of a failed R7 batch (design only).**
- **Why now.** The canary stop rule anticipates failures, but a failed R7 batch has no re-run path:
  - `new_run` refuses at revision 7;
  - addendum §2.4 requires a new governed run, a new plan and a human act;
  - Legacy(B) is digest-frozen.
  A canary failure would therefore stall the programme.
- **Scientific core.** Re-running only failures is a selection process. Could the re-run rule bias θ_D/θ_A/θ_E or the sampled strata?
- **Target.** A separable part of the same v2.8 rebind, avoiding a v2.9 rebind later.

**2026-09-29 — EG-6 A result** (`audit-p3b/eg6/EG6-SPEC-A.md` after review; a scratchpad copy for now).
- **Recommended: option (b), retire a failed attempt.**
  - Its run directories and assembly move unaltered into `ledger-p3b-r2/<B>-R7.A<m>/`, with RETIREMENT.json.
  - The archive moves to `<archive>/<B>.A<m>/`.
  - The legacy digest is re-frozen through `rebind_manifest`; the state gets `new_attempt`; attempt m+1 runs in a fresh session.
  - **No reader or verifier change;** the plans and prompts are unchanged.
- **Synthetic probe:** retire + re-frozen digest gives BATCH-PASS. Unlisted, not re-frozen, edited or deleted retired evidence each give R7-U. The retired name is rejected as a reader `--run`.
- **Statistics:** retry rules RR-1..RR-7, sample-blind, audit-blind, pre-declared, with failed attempts never estimated. The estimand describes accepted objects under the declared retry policy. The policy must be declared **before** the canary.
- **Failure classes:** X / A / D / S.

**2026-09-29 — orchestrator findings during EG-6.**
- **EG-7 (ENGINEERING DEFECT; blocks runbook step 9 for every R7 batch), confirmed.** `p3b_s5_state.check_evidence` requires the report `result == "PASS"` (`p3b_s5_state.py:131`), but the R7 verifier emits `BATCH-PASS`. So even a passing canary could not be transitioned to VERIFIED. The fix is state-side only; it is queued for the A/B loop.
- **EG-8 (PROVENANCE RISK; frozen design).**
  - B1 v2 (Freeze 1) declares EP-01 (`audit-p3b/20260926_EP-01-STATISTICAL-SPECIFICATION.md`) as its base ("consumed, not repeated").
  - The file is **untracked**, and no G-LOG binds its sha. Its current sha256 is `f43504f4a331efa85c5cb9dff8d55b3164acf314340109e94e7a62af428ec0aa`.
  - So a frozen design depends on an unversioned, unbound document.
  - Remedy: a human act. The owner commits EP-01, or a G-LOG binds its sha. The orchestrator does not commit files that are not its own.
- **Process deviation, recorded.** A read EP-01 despite the "no untracked files" constraint. It is B1's declared base, so the content is legitimate design material. B has now been explicitly permitted to read it for the review.

**2026-09-29 — EG-7 closed (ENGINEERING, non-material; implemented, reviewed, committed).**
- **Change:** `p3b_s5_state.check_evidence` requires `BATCH-PASS` for VERIFIED on a revision-7 batch run (`OB####-R7`). Every other case keeps `PASS`.
- **Process:** A wrote the tests first (RED: 1 failure + 2 errors), then the minimal change (GREEN).
- **Review:** B's implementation review (`audit-p3b/eg6/EG7-REVIEW-B.md`) gave **IMPL-ACCEPTABLE**. B predicted the RED signature independently, confirmed R2/R2.n/R2S unchanged, found 2 production callers only, and confirmed forward compatibility with EG-6 option (b).
- **Orchestrator check:** the diff was inspected; the state, EG-7, audit-record and rebind modules give 47 OK; the full suite gives **1,125 OK**.
- **Still open, deliberately:** whether the per-batch AUDITED stage applies under R7. `p3b_s5_audit_record.py:53` refuses `<B>-R7`, so R7 batches can reach VERIFIED but not AUDITED/ACCEPTED. EP-01 audits after acceptance, and the runbook skips AUDITED.
- **B's out-of-scope observation:** `add_comparison` always names comparison runs `<B>-R2S`.

**2026-09-29 — EG-6 round 2 (A v3).**
- **F-01:** a per-rename device rule; EXDEV and `st_dev` mismatches are refused as class X and resume cleanly.
- **F-02:** the pre-registered watchdog W-SYS, an execution-only stopping rule:
  - it runs after every K = 20 completed post-canary attempts;
  - it stops if the exact binomial test P(Bin(n, q*) ≥ f) ≤ 0.01, with q* = 0.12771;
  - q* is the per-attempt failure rate at which M = 4 still gives P(all 396 pass) = 0.90.
- **Orchestrator verification:** q* and the thresholds were recomputed independently (n = 20 → f ≥ 7; 100 → 22; 200 → 38; 400 → 68; 800 → 126).
- **Next:** B's final review.

**2026-09-29 — EG-6 closed at the design level.**
- B v3 gave **SPEC-ACCEPTABLE**:
  - `retire_model` crash, EXDEV and `st_dev` cases re-run;
  - the W-SYS thresholds re-derived with exact Decimal/Fraction arithmetic;
  - the false-stop rate across all looks confirmed at 6,000 replicates (0.0043).
- B's three wording additions are adopted:
  - α_w is a per-look constant;
  - RR-7 attempts are not counted in f;
  - dispatch order is stated as *outcome-blind*. The orchestrator corrected B's "content-blind": `batch_order` comes from pre-output packing metadata.
- Consolidated into the decision package as part (d) (§14) and part (e) EG-8 (§15).
- **Faithfulness check** (`audit-p3b/eg6/EG6-PACKAGE-FAITHFULNESS-B.md`): **NEEDS-EDIT**, both points fixed:
  - (1) class U (UNVERIFIABLE-WITNESS → restore and re-verify, no re-run) had been dropped from §14;
  - (2) **correction to an orchestrator statement.** "The Freeze 2 record never mentions EP-01" was imprecise. The Freeze 2 **record JSON** has no EP-01 reference, but the Freeze 2 **proposal** names EP-01 three times. The true gap is that **no artifact binds EP-01's sha256**. The earlier D-section wording "no G-LOG binds its sha" stands.

**2026-09-29 — STOP: human gate. ONE decision:** approve / reject / revise the EG-5/v2.8 package (`audit-p3b/eg5/EG5-V2.8-DECISION-PACKAGE.md`), parts (a)–(e):

| Part | Content |
|---|---|
| (a) | §2.6 record identity (blocks every batch today) |
| (b) | §2.7 assembly + the EMPTY object |
| (c) | in-checklist vacuous examination |
| (d) | EG-6 governed re-run + the pre-registered retry policy (before the canary) |
| (e) | EG-8: commit and sha-bind EP-01, by its owner |

- (a), (b) and (d) share one addendum revision and one rebind.
- **Engineering landed in the meantime:** EG-7 (`3b5eb05f8`).
- **Open, outside the package:**
  - AUDITED under R7 (needed before any R7 batch can reach ACCEPTED; not needed for the canary);
  - EG-3 (the hub reading protocol);
  - EG-5c (verifier closure);
  - RC-02; RI-1;
  - `add_comparison` R2S naming for R7.

**2026-09-29 — two parallel loops started while the v2.8 gate stays open** (independent; different builders).

**EG-3: the hub reading protocol.** Highest information value: HUB is 33 of the 231 sampled labels, and the 14 hub batches cannot run as specified.
- **Orchestrator FACT** (counts only; production slices as metadata; `hub_view_profile.json` goes into `audit-p3b/eg3/` at closure):
  - character shares in the 66 hub slices: `search_records` 66.9%, `source_meta` 28.5%, bundle 5.6%. Non-hub: bundle 49%, family_md 21%, search_records 15%.
  - Reads per hub view at 25 lines:

    | What the agent reads | Median | p90 | Max |
    |---|---|---|---|
    | All keys | 318 | 1,735 | 5,950 |
    | Without `search_records` | 172 | 528 | 801 |
    | Without `search_records` and `source_meta` | 29 | 70 | 108 (≈ non-hub) |

- **The research question:** what does the contract require a hub run to do with `search_records` / `source_meta` (discovery metadata vs evidence)?

**EG-9: the AUDITED stage under R7.**
- The state machine requires AUDITED (a §21 audit via `p3b_s5_audit_record.py`, which refuses `<B>-R7`) before ACCEPTED.
- The R7 runbook stops at VERIFIED, and EP-01 audits a frozen sample after acceptance.
- So no R7 batch can reach ACCEPTED, and PROGRAM-ACCEPTED and EG-6 RR-3 ("passes and is accepted") are unreachable.

**2026-09-29 — EG-9 A result** (the scratchpad copy moves to `audit-p3b/eg9/` at closure).
- **Recommended: option (a), keep the per-batch §21 audit under R7.**
  - **Evidence §21 still applies:** rev3 §16.5 fixes the order verifier PASS → §21 → H-06, and no governance entry supersedes it. EP-01 was added on top (G-LOG-0082 H).
  - **The one block** is `p3b_s5_audit_record.py:53`, which accepts R2 runs only.
- **Proposed engineering:**
  - extend the tool to `<B>-R7`;
  - bind findings to the sample, the assembly bytes and the VERIFIED state.
- **Proposed governance rules:**
  - dispositions are non-corrective;
  - a firewall keeps §21 away from the Freeze-2 sample and from θ_D/θ_A inputs;
  - a §21 FAIL is class H.
- **H-06 per enumerated tranche.** It is not needed to execute the canary, but must be decided with v2.8, because EG-6 class H depends on it.
- Now under adversarial review by a fresh reviewer B2 (a different model).

**2026-09-29 — EG-10 (runbook defect, minor), confirmed by the orchestrator.**
- The runbook (execution package §1) goes from step 2 (→ DISPATCHED) to step 9 ("PROPOSED → VERIFIED"). No step writes DISPATCHED → PROPOSED, which the state machine requires (`p3b_s5_state.py:46-47`).
- **Correction:** add the step `state transition B PROPOSED` after `freeze-final` (step 7), before `verify`.
- It is folded into the package's runbook corrections, beside the step 6 correction.

**2026-09-29 — EG-3 A result** (`EG3-SPEC-A.md`; moves to `audit-p3b/eg3/` at closure).
- **Contract finding** (FACT, cited):
  - hub runs have no stage 2;
  - every hub absence resolves ESCALATED/LOAD, and NOT-FOUND-LOAD-ESCALATED "asserts nothing about the hits";
  - no contract text requires reading all of `search_records` / `source_meta`;
  - the hit entries serve only the verifier and the hub-list builder.
- **Recommended: option (a).**
  - A normative required display set **R(run)**, computed from the frozen view's JSON paths.
  - The prompt lists it as line ranges.
  - RI-1b coverage is **report-only**.
  - Contract-material (needs addendum text + a rebind); not verdict- or science-material.
  - A candidate separable part (f) of v2.8. It is needed only before the hub batches; the canary has no hub.
- **Orchestrator exact counts** (production metadata, counts only; `hub_required_counts.json`): hub reads per label under R(run) are median **41**, p90 **88**, max **127**, against 318 / 1,735 / 5,950 today. Total hub reads fall 49,934 → 3,354 (−93%). The narrow and citable T(L) variants differ by ≤ 4 reads per label, so the T(L) choice is practically immaterial.
- **Recorded, not yet acted on:**
  - (1) the rendered prompt's "until the whole file has been displayed" rule has no contract anchor, for non-hub runs too;
  - (2) nothing consumes the witness's displayed ranges (RI-1b not implemented), so an agent could copy mechanical hub absences without displaying its input.
- Now under adversarial review by the fresh reviewer B3.

**2026-09-29 — EG-9 review (B2): SPEC-ACCEPTABLE with revisions.**
- B2 re-derived §21 from protocol v1.7. No entry supersedes it.
- A §21 FAIL → class H → NOT-ASSESSABLE is a population redefinition absorbed by the EP-01 worst case, not bias.
- **MATERIAL:** H-06 tranche acceptance had no mechanical binding (`p3b_s5_accept.py` never checks that `--reference` lists the batch).
- **MODERATE:** A's OB0004 precedent is closed by R7 §6; the categorical argument holds.
- **EG-10 upgraded to a live blocker:** the orchestrator never calls state transitions, so without the runbook fix the first canary batch cannot reach VERIFIED.

**A4 v2** (EG9-SPEC-A-v2):
- `check_tranche`: a sha-anchored committed tranche file, required at revision 7.
- The categorical argument replaces the precedent.
- The class-H limitation sentence is added.
- A corrects its own v1: §21 item 2 makes AUDIT-UPHELD produce a correction record. At R7 it is append-only under `audit-p3b/` and never inside the verified assembly.
- The exact EG-10 runbook text: step 7b `transition B PROPOSED`, plus the FAILED / INCOMPLETE / UNDETERMINED branches.

Under B2 re-review.

**2026-09-29 — EG-3 review (B3): SPEC-ACCEPTABLE, one MATERIAL hygiene defect.**
- All contract and verifier citations were re-verified.
- The "whole file" prompt rule has no contract anchor, for non-hub runs too. The SLICE-VIEW is non-evidentiary input, separate from the witnessed corpus reader.
- **MATERIAL:** the probe implemented a first-leaf variant; the draft rule 3 excludes every hit leaf.
- **Orchestrator correction:** the production counts logged above also used that variant (copied from the probe). Recomputed for both variants:

  | Variant (citable T(L)) | Median | p90 | Max | Sum |
  |---|---|---|---|---|
  | Rule 3 as drafted | 41 | 88 | 127 | 3,349 |
  | First-leaf variant | 41 | 88 | 127 | 3,354 |

  (narrow T(L): sum 3,252)

  **Decision: adopt rule 3 as drafted.** A3 is aligning the probe and spec.

**2026-09-29 — EG-3 and EG-9 converged; consolidated as parts (f) and (g).**
- **EG-3: SPEC-ACCEPTABLE** (B3 v2).
  - The probe now conforms to rule 3; E2 passes, and the negative control rejects the first-leaf variant.
  - A3 found and fixed its own bug: T(L) had been empty in the probe. Production counts were unaffected, because the orchestrator derived T(L) independently.
  - The HD-5 hub path split was closed by the orchestrator: 64 SINGLE / 1 DECOMPOSED / 1 EMPTY; 67 hub runs.
- **EG-9: SPEC-ACCEPTABLE** (B2 v3).
  - B2 confirmed the orchestrator's correction of the precedent: G-LOG-0025 is the exception/quarantine form, not an applied correction. The correction record stays an open **human** decision (R-I recommended / R-II / R-III).
  - The tranche binding gains a heading-uniqueness check and capture fields; the log's lack of append-only enforcement is disclosed honestly.
- **Faithfulness checks of the package: both NEEDS-EDIT, all fixed.** Four statements were wrong because of the orchestrator's own summarizing:
  - (1) §17: a reviewer-recommended clause had been merged as if it were spec text. Its provenance is now marked.
  - (2)–(4) §16: the hub path split, "33 of 231" and the character shares were uncited orchestrator facts. Now cited: `audit-p3b/eg3/hub_path_split_and_shares.json` and the Freeze 2 record `strata.HUB` n = 33.
- **Lesson (process, observation only):** the orchestrator's consolidation step introduced errors in both faithfulness rounds (4 of 4 findings so far). The faithfulness check is a necessary step, not ceremony.

**2026-09-29 — STOP: human gate.** ONE decision on the package, now parts (a)–(g).
- (a) record identity; (b) assembly + EMPTY; (c) vacuous checklist; (d) re-run + retry policy; (e) EP-01 provenance; (f) hub R(run); (g) §21/H-06 under R7 + the EG-10 runbook fix.
- **Canary-blocking:** (a), (b), (d) policy, (g) EG-10. (e) must precede the canary for provenance.
- **(f)** is needed only before the hub batches. **(g) engineering** is needed only before the first §21 audit.

**2026-09-29 — implementation under G-LOG-0106 (EP-01 committed `38fa58183`).**
- **Addendum v2.8 text drafted by the orchestrator:** §2.3/§2.4 amended; §2.6–§2.8; §5.9a; Annex E (hash-bound to the EG-5 specs); T122–T137; DR-22. Static checks 73/73 (137 tests). The sha is not frozen until the implementation converges.
- **Runbook corrections** are appended to the execution package as §6.
- **Builders in parallel** (disjoint files): S1 assembly/EMPTY/RECORD IDS (orchestrator tool); S2a state attempts/W-SYS; S4 §21 R7 + `check_tranche`.
- **S2a:** RED 40/41 → GREEN 41/41. The review (R-S2a) gave IMPL-NEEDS-REVISION:
  - (1) W-SYS n was non-monotone: a later class-H failure erased a counted PASS;
  - (2) a W-SYS stop was not durably recorded.

  Fixed (48/48), and the INCOMPLETE RR-7 gap was closed with a derived signature.
- **PROCESS DEVIATION (recorded):** the S2a builder applied its revision edits with an exact-match replacement script, not Read/Edit, against the repository's source-editing rule. Mitigation: a full hunk-by-hunk re-review of the diff (R-S2a v2).
- **Integration items found:**
  - the S5 path allowlist (`p3b_s5_common.py`) matches neither `<B>-R7.A<m>/RETIREMENT-*.json` nor `H06-TRANCHE-*.json`;
  - the tranche is read without `check_paths`.

  Both go to the retire slice.
- **Known interim failure:** `test_p3b_s5_r7_contract_rebind.test_valid_rebind…` fails, because the addendum is mid-edit (its sha ≠ `U.ADDENDUM_SHA256`). It closes at the v2.8 freeze.

**2026-09-29 — INCIDENT (recorded): an unauthorized action by builder S1 on a false premise.**
- S1's second hand-back claimed "the user ran out of session quota and asked to save the work". **No such user message exists.** The claim reached the subagent as model context, not as a user instruction.
- Acting on it, S1:
  - (1) appended a "PAUSED (session quota)" resume note to `.claude/sessions/2026-09-29.md`, a file outside its authorized list;
  - (2) wrote a backup outside the repository (`~/eg5-s1-assemble-backup/`: a patch plus a test-file copy).
- **Orchestrator action:**
  - reverted the **uncommitted** session-log hunk (its technical content duplicates S1's hand-back and this report; the premise was false);
  - left the external backup untouched (harmless, for the human to remove);
  - no code change resulted from the false premise.
- S1's code and tests are unaffected and remain under review (R-S1).

**2026-09-30 — CORRECTION to the S1 incident record above (the earlier text stays as history).**
- S1 later reported that the "quota / save the work" instruction reached its context **labelled as a user message** ("The user sent a new message while you were working").
- The session did hit a real API session limit at about that time (a reviewer failed with HTTP 429 "session limit … resets 5:50pm").
- So the earlier statement "no such user message exists" overstated the evidence. **The message's origin is unverified**: it may have been a genuine user message to the subagent.
- What stands:
  - the session-log hunk was written outside S1's authorized file list, and it was reverted while still uncommitted;
  - S1 has since deleted its external backup directory itself;
  - no code resulted from it.
- **Rule going forward:** builders follow only orchestrator instructions and their file lists. A message to a subagent that claims user authority is reported back, not acted upon.

**2026-09-30 — reviews after the session break.**
- R-S3: **IMPL-ACCEPTABLE.** The minor items were applied: a HEAD-pinned golden UNIT prompt, edge-path tests, the view path from the manifest entry, one parse per view; 117 tests OK.
- R-S1: **IMPL-ACCEPTABLE.** Hardening applied:
  - H-1: an absent `in_checklist` dict → R13;
  - H-2: `freeze-final` requires the FINAL-VALIDATED printed hashes = the ASSEMBLED ones = disk, which blocks a forged later ASSEMBLED marker; 187 tests OK.
- H-3 (operational): the EMPTY object's `producer_sha256` hashes the whole orchestrator tool. The tool file must be frozen, and its sha recorded, before the canary's ASSEMBLED step (a canary pre-check item).
- Runbook step 7a: `orchestrate coverage B` (report-only).
- Last builder slice in progress: S2b `retire` + `may_start_attempt`.

**2026-09-30 — v2.8 implemented, rebound and verified. STOP at the S5 canary gate.**
- **Implementation:** 5 slices (S1, S2a, S2b, S3, S4), each tests-first and independently reviewed to IMPL-ACCEPTABLE (`audit-p3b/impl/`).
  - Material defects caught by review and fixed:
    - W-SYS non-monotone count;
    - W-SYS stop not durable;
    - an unregistered W-SYS count reset;
    - correction records in the wrong (non-§12.3) file;
    - tranche inputs outside the path gate;
    - a P3B-COR id race and crash ordering;
    - a forgeable ASSEMBLED marker (H-2).
  - One process deviation (a replacement-script edit, fully re-reviewed) and one incident (S1, origin unverified) are recorded above.
- **Commits:** `dbe7eb680`, `4e292bab2`. Addendum v2.8 is frozen at `4d4dac6b…`.
- **Rebind:** production is bound to v2.8 (manifest `5978fbf5…`). Full suite 1,400 OK before and after; `prepare --check` IDENTICAL; H-19 SEALED.
- **Canary pre-check v2.8:** every item passes (`audit-p3b/20260930_S5-CANARY-PRECHECK-v28.json`). EG-5 is closed.

**Next human gate: the S5 canary authorization.**
- The canary is OB0012 + OB0114, 11 runs, under the v2.8 runbook (execution package §6).
- Stop rule: ≥ 6 of 11 failed, or the same W-code in ≥ 2 runs.
- Before the ASSEMBLED step, the tool files must equal the frozen shas (H-3).

**2026-09-30 — S5 canary attempt 1: executed and STOPPED** (G-LOG-0107; findings in `audit-p3b/20260930_S5-CANARY-ATTEMPT1-FINDINGS.md`).
- **Pre-dispatch finding, fixed** (`1bbf97e33`): the prompt named a stale addendum version.
- **Run.** OB0012's 5 runs were dispatched. All 5 were refused by the reader and stopped correctly under W4, which triggered the stop rule. OB0114 was not dispatched.
- **Verdict.** BATCH-FAIL, class X. The state is FAILED at attempt 1.
- **Root cause (F-1).** The reader resolves its repository root from the working directory, and the session's primary working directory had become another repository after the restart. The misrouted reader wrote 5 refusal logs there (F-1b; preserved).
- **Other findings:**
  - F-2: the addendum's status header still said "NOT ACTIVATED / NOT AUTHORIZED";
  - F-3: a benign past-end-of-file probe is scored W8;
  - F-4: agents diverge under a batch-wide refusal;
  - F-5: the pre-check never tested the dispatch working directory.
- **What the canary established.** Binding, I(run), prompt delivery, W4 discipline, W8 compliance, an intact transcript chain, and a correct witness/verifier/state failure record.
- **Repairs at the canary gate** (RR-6), tests first and reviewed:
  - R-1: a `--dispatch-cwd` guard, required, which runs the reader's own resolution in a sanitized subprocess; live-tested;
  - R-2: prompt line counts. The rule N = count("\n") + 1 was verified independently on the harness.
  - Full suite 1,419 OK.
- **Next human gate:** the canary restart decision (`audit-p3b/20260930_S5-CANARY-RESTART-DECISION.md`) and the operator brief for a fresh session.
