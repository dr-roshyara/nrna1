# EG-5 / contract v2.8: ONE bounded decision package

| | |
|---|---|
| **Kind** | ⚠ authority: generated. Decision package for a human governance act. Nothing in it is applied |
| **Decision requested** | **APPROVE the EG-5/v2.8 bounded change as specified below**, or **REJECT / REVISE** it (name the section). Revised 2026-09-29: it now also carries part (d) EG-6 (governed re-run + pre-registered retry policy; §14) part (e) EG-8 (EP-01 provenance; §15), part (f) EG-3 (hub reading protocol; §16) and part (g) EG-9 + EG-10 (AUDITED/acceptance under R7 + the runbook state fix; §17) |
| **Loop provenance** | Subagent A (builder): spec v1 → v2 → v3. Subagent B (independent reviewer, a different model): review v1, then review v2, then review of this package. The orchestrator reproduced the critical probes. All work used synthetic fixtures plus a counts-only production preflight |
| **Working papers** | `audit-p3b/eg5/`: EG5-SPEC-A{,-v2,-v3}.md, EG5-REVIEW-B{,-v2,-v3}.md, probes/ |

## 1. The problem (facts)

**EG-5: no governed assembly.** Runbook step 6 has no tool and no rule. The verifier needs one object per planned label, in the assembly `ledger-p3b-r2/<B>-R7/{objects,register,p1-gap-capture}.jsonl`. An EMPTY label (8 in the frame) has no run, so its object has no producer.

**EG-5b: record identity blocks every batch, including the canary.**
- rev3 item (3): "`<run_id>` is the run id you are dispatched with; every path, reader call and record uses it."
- G-07 (`p3b_s5_verify.py:511`, `:929`, `:987`) requires `run_id = <B>-R7` and `rs_id`/`gap_id` of the form `<B>-R7:<B>:<n>` / `:G<n>`, unique across the batch.
- The R7 addendum never resolves this.
- So every compliant final run fails, and independent per-run numbering collides.
- The synthetic positive control passed only because the fixture rewrites every id.

**EG-5c: a residual weakness in the accepted verifier.** The verifier accepts evidence-bearing register or P1-gap records attached to an EMPTY label: the positive control carries 3 + 3 such records and passes T38.

**Preflight (counts only).**
- 8 EMPTY labels.
- 2 of them are in-checklist by the slice test that G-09 reads (OB0110 L04, OB0225 L03).
- 1 is a hub label (OB0386 L05, in a hub batch, so EG-3-gated).
- The canary's EMPTY label (OB0114 L04) is not in-checklist.
- Hazard: the manifest entry's `in_checklist` is a dict label→bool, so a membership test is always true.

## 2. The change: addendum v2.8 (normative text to be applied)

**§2.6 Record identity (material: it changes what agents write).**
- In every object record, research-register record and P1-gap record an agent writes, `run_id` is the assembly id `<batch>-R7`.
- `rs_id` is `<batch>-R7:<batch>:<n>` and `gap_id` is `<batch>-R7:<batch>:G<n>`, with `n = 1000·L + k`:
  - `L` is the label's 1-based manifest position;
  - `k` (1…999) is the record's position among that label's records of the same kind in the run's file.
- This replaces rev3 item (3) **for these three fields only**. The run's ledger directory, the reader's `--run`, the READ-LOG, the `run` of every claim-evidence reference, I(run) and the binding line all keep the dispatched run id.
- The dispatch prompt states the concrete values (the RECORD IDS block).
- An `n` outside the label's range fails the batch; the assembly refuses it.

**§2.7 Assembly and the EMPTY object (EG-5; changes no agent behaviour and nothing the verifier accepts).**
- **Where it runs.** The orchestrator tool writes `<batch>-R7` inside the `ASSEMBLED` marker command (§5.6). It re-derives the assembly without writing inside the `FINAL-VALIDATED` marker command. Both print `sha256  path` for every input and output.
- **What it produces:**
  - `objects.jsonl`: one object per manifest label, in manifest order;
  - `register.jsonl` and `p1-gap-capture.jsonl`: the final runs' `register.jsonl` and `p1-gap.jsonl` records, in manifest-label blocks, file order kept;
  - canonical JSON lines throughout.
- **What it never does:** edit, filter, renumber or repair a record.
- **When it refuses (the batch FAILS):** a missing or multiple object; a record naming another label; an unparsable or duplicate-key line; an `n` outside its range; an existing assembly that differs.
- claim-evidence and S3-LINT stay in the final runs' directories.
- **EMPTY object.** For a label whose plan path is EMPTY (no run), the assembly writes the object of Annex E (`EMPTY-OBJECT v1`):
  - every birth and `current_lifecycle` is NOT-EVIDENCED-IN-CAPTURE;
  - every absence is ESCALATED, with the stage-1 `negative_label`;
  - one escalation `OTHER` names EMPTY-REQUIRED-SET, plus a `LOAD` escalation per hit-bearing dimension of a hub label;
  - the status constants are UNTYPED / UNDECIDABLE-FROM-CORPUS / LAYER-UNRESOLVED;
  - it has no source, quote, claim, timeline point, edge, disposition, register record or P1-gap record.
- **Who proposes it.** The object is proposed by the orchestrator agent:
  - `model_id` is the orchestrator's served model, which must equal the batch's agents' model;
  - `proposed_by` is AI-AGENT;
  - `generation_parameters` names the tool, its sha256, the rule `EMPTY-OBJECT v1` and the mode `DETERMINISTIC-NO-MODEL-CALL`.
  Its content is fixed by this rule.
- **θ_D.** The rule is part of the protocol, so an independent same-protocol re-analysis (θ_D) of an EMPTY label applies it.
- **In-checklist EMPTY labels.** An EMPTY label whose slice has `in_checklist: true` carries `checklist_examined = [1…23]`. Each §9C question is posed over steps 1–8 as this rule fixes them. §9C records only positive findings, and none can exist over an empty required set, so the object records the examination and nothing else (no register or P1-gap record). `generation_parameters.checklist` is `VACUOUS-OVER-EMPTY-REQUIRED-SET`. Any other EMPTY label carries no `checklist_examined`.

**Annex E** is the full field table (spec v1 §3, with the v2 `author_role` and `generation_parameters` values and the v3 checklist rows). The tool references it as `EMPTY_OBJECT_V1`.

## 3. Semantics, and why they are safe

| Concern | Resolution | Evidence |
|---|---|---|
| Manufactured evidence | The EMPTY object asserts only provenance constants, plan/manifest identifiers and NOT-EVIDENCED/ESCALATED markers. It carries no S-id, quote, birth or claim, and the verifier rejects source-requiring claims on EMPTY (`p3b_s5_r7_reconstruction.py:176-179`) | spec v1 §3–4; B v1 "checked clean" |
| Absence vs non-observation | "Empty required set" ≠ not-read ≠ not-found ≠ GENUINELY-UNDEFINED. The escalation names EMPTY-REQUIRED-SET (R17) | spec v1; B v1 |
| Provenance | The orchestrator AI agent (the same served model as the agents; tool refusal R10) proposes the object through a disclosed, hash-bound deterministic rule. The record's run is the assembly, which the orchestrator performs, so this is consistent with rev3 §18. No verifier exemption | spec v2 §3; B v2 (C-02: R10 proves consistency, not independent truth) |
| Witnessing | The tool runs inside the witnessed `: S5-ORCH ASSEMBLED …;` marker command, so its printed hashes land in WITNESS.jsonl. `freeze-final` re-checks the hashes on the tool side | spec v1/v2; B v1 (ORCH regex checked) |
| Determinism | Canonical JSON, manifest order, write-once, byte-identical across cwd, TZ, locale and hash seed (T121) | spec v2 §7 |
| Checklist | `checklist_examined` records which questions were posed, never answers (§9C). Vacuous examination over ∅ is truthful and passes G-09 unchanged | spec v3; probe `probe_eg5_checklist.py` |

## 4. Statistical implications (no frozen object changes)

- **Unchanged:** the population, labels, strata, frame `d0c972bf…`, seed, sample `e3f3ed76…`, θ_D/θ_A/θ_E definitions, H1–H5, Model 0, estimands, α and the multiplicity rules.
- **Interpretation recorded, not changed:**
  - On the EMPTY stratum (census of 7) and for the hub-EMPTY label inside the HUB stratum, θ_D is a **rule-determinism check**. Disagreement can come only from a software or application error. It is reported separately under that name.
  - The 2 in-checklist EMPTY labels yield no research signal. They are reported as "2 checklist labels vacuous (EMPTY)".

## 5. Alternatives considered and rejected

| Alternative | Reason |
|---|---|
| (b) Verifier change: accept the dispatched final-run id | Reopens the independently audited verifier (G-LOG-0092) and needs a new audit. Touches G-07/G-12 and the acceptance fixtures. A rebind is needed for the §2.7 text anyway |
| A prompt-only record-id rule | Contradicts rev3 item (3) |
| The assembly rewriting ids | Breaks the S3-LINT `records_sha256` binding |
| Non-numeric ids | Fail the verifier's `\d+` |
| A hand assembly by the orchestrator session | An unwitnessed, unspecified write |
| Refusing in-checklist EMPTY labels | Their batches could never pass, so PROGRAM-ACCEPTED becomes unreachable |
| An agent run for EMPTY labels | Changes the frozen plans |
| Removing labels from the checklist population | Changes the frozen sampling |
| A null + SCHEMA-LIMITATION status route | Needs an S-id-anchored register record, which is impossible for EMPTY |
| Sanitizing the fixture to hide EG-5c | Hides a real verifier property |

## 6. Adversarial review record

**B v1: SPEC-NEEDS-REVISION.**
- B-01 (MATERIAL): EG-5b is universal and blocks both canary batches.
- B-02 (MATERIAL): the provenance conflicts with rev3 §18.
- B-03: EMPTY θ_D is tautological.
- All three were resolved in v2.

**B v2: SPEC-NEEDS-REVISION (minor).**
- C-01 (MATERIAL): EG-5c lies in the accepted verifier. Recorded as a residual; the fixture is not sanitized.
- C-02: R10 proves consistency only. Recorded.
- C-03: the θ_D note must also cover the hub-EMPTY label. Adopted in §4.
- B also showed that an out-of-range `n` without a collision passes the verifier, so assembly refusal R11 is necessary.

**B v3 (this package):** see §11.

## 7. Synthetic evidence (production verifier, synthetic fixture)

**Record identity** (`probe_eg5b_options.py`; re-run by the orchestrator and by B):

| Variant | Result |
|---|---|
| Compliant (a) | BATCH-PASS |
| Dispatched id on an object | BATCH-FAIL, G-07 |
| Dispatched id on the register | BATCH-FAIL, G-07 ×10 |
| Per-run numbering from 1 | BATCH-FAIL, repeated ids |
| Claim reference `run = <B>-R7` | BATCH-FAIL, R7-E |
| Colliding overflow | BATCH-FAIL |
| Non-colliding out-of-range `n` | BATCH-PASS (so the verifier cannot catch it; R11 required). Evidence: `probes/probe_overflow_novacuous.py` (B's probe, re-run by the orchestrator: BATCH-PASS, 0 failures). `probe_eg5b_options.py` tests only the colliding case (D-01) |

**Checklist** (`probe_eg5_checklist.py`):

| Variant | Result |
|---|---|
| Vacuous examination | BATCH-PASS |
| Field absent, or 1..22 | BATCH-FAIL, G-09 |
| Field on a non-checklist EMPTY label | BATCH-FAIL, SCHEMA |
| Not assembled | BATCH-FAIL, G-02 / R7-R |

## 8. Implementation, tests first

**Code changes:**
- `scripts/p3b_s5_r7_orchestrate.py`:
  - `empty_object(...)` (pure; Annex E);
  - `assemble(batch, root, analysis_date, model_id, check)`, with the CLI `assemble B --date YYYY-MM-DD --model-id <served id> [--check]`;
  - the RECORD IDS block in `render_prompt`;
  - `freeze-final` checks the ASSEMBLED printed hashes.
- **Refusals** (nothing is written on any of them):

  | Id | Condition |
  |---|---|
  | R1 | context |
  | R2 | missing final objects |
  | R3 | object count or owner |
  | R3′ | a register or gap record naming a non-owner label |
  | R4 | a malformed line |
  | R5 | a run directory for an EMPTY label |
  | R6 | a mechanical rule outside ROW-0/3/4 |
  | R7 | a self-check failure |
  | R8 | an existing output differs |
  | R9 | `--check` finds a difference |
  | R10 | model id |
  | R11 | `n` out of range |
  | R12 | date format |
  | R13 | entry `in_checklist[label]` ≠ slice `in_checklist` |

- The in-checklist test uses **`slice["in_checklist"] is True`**, cross-checked against the entry dict value, never against dict membership.

**Tests** (new `tests/test_p3b_s5_r7_assemble.py`, synthetic only):
- T110–T122 and T-01…T-12: spec v2 §7.
- T123–T129: spec v3 §3.
- Changes from spec v2:
  - **T-09 is superseded by T123** (vacuous examination, not refusal).
  - **T-13 (fixture sanitation) is DROPPED.** It is replaced by **T-13′**, an EG-5c residual regression: the unchanged verifier alone still accepts evidence records on an EMPTY label, and the tool refuses them.
- Addendum §11 matrix rows are added for T110–T129. The static checks must stay green.
- Full suite; A/B implementation review.

## 9. Exact production transition (after the implementation review)

1. Freeze addendum v2.8 and compute its new sha256. Update `U.ADDENDUM_SHA256`; the history stays in comments.
2. `p3b_s5_r7_activate.py --rebind-contract --reason <G-LOG> --staging <new dir outside the repo>`: the EG-4 procedure. It is exercised for EG-4's historical transition, and (D-02) for a **fresh target**: `test_p3b_s5_r7_contract_rebind.FreshTargetRebind` covers v2.7 → a freshly computed sha, a byte-identical body, the provenance record and a refused second rebind. It passes on the unchanged code (coverage, not new behaviour). Header line only; the 396 entry lines are byte-identical; one MANIFEST-REVISION entry in the state; every batch must be PREPARED at R7.
3. Post-checks:
   - `prepare --check` IDENTICAL;
   - H-19 SEALED;
   - the full suite;
   - the canary pre-check re-run: the OB0012/OB0114 prompts carry the RECORD IDS block, and the OB0114 EMPTY object is derivable in a temporary copy.
4. Commit and STOP at the S5 canary authorization.

**No batch has committed prepare-time artifacts yet.** There are no views, I(run), run directories or assembly in production (canary pre-check: 0 R7 ledger directories; 0 production views). §2.6 therefore touches no committed artifact: only the rendered prompts change.

## 10. Rollback / rejection boundary

- **Before step 9.2**, nothing in production changes. A rejection leaves v2.7 bound, with EG-5/5b open, and the canary cannot run.
- **After step 9.2**, rollback is one more rebind back to the v2.7 sha. The tool refuses a no-op, so the reverse rebind would be its own governed act. Batches are untouched either way.
- EG-5c stays a residual in both cases. Closing it in the verifier is a later, separate decision.

## 11. Independent review of this package (B v3)

**B v3: PACKAGE-NEEDS-REVISION** (`EG5-REVIEW-B-v3.md`). Two MATERIAL, cheap-to-fix evidence/testing gaps; no design problem:
- **D-01.** The out-of-range claim cited a probe that tests only collisions. **Fixed:** the citation now points to B's committed non-colliding probe, re-run by the orchestrator (BATCH-PASS).
- **D-02.** "Already tested" overstated rebind coverage. **Fixed:** new test `FreshTargetRebind` (v2.7 → fresh target), 8/8 OK.
- **Non-blocking, adopted:**
  - the separability note (§13);
  - the hub-EMPTY vs EG-3 scope sentence (§13);
  - "no committed prepare-time artifacts" (§9).
- **Confirmed clean by B:**
  - all v1/v2 findings carried faithfully (C-01: T-13 dropped, T-13′ added);
  - the checklist probe is non-vacuous;
  - G-09 is the only checklist gate;
  - vacuous examination is truthful under §9C: a real agent with nothing to report writes the byte-identical record;
  - no hidden change to the invariants.
  - Caveat: EP-01 was outside B's read set.

**Orchestrator status after the fixes: READY FOR THE HUMAN DECISION.** Both MATERIAL items are closed by evidence; no design element changed.

## 12. Scientific invariants

Confirmed unchanged by the whole change: corpus boundary · historical reconstruction · Freeze 1 · Freeze 2 · population · labels · strata · sample · seed · θ_D/θ_A/θ_E definitions · H1–H5 · Model 0 · competing models · estimands · multiple-testing rules.

## 13. What the approval covers, and what it does not

**Separability.** The approval is one act, but its three parts are separable if the human prefers:
- (a) §2.6 record identity: required by **every** batch, including OB0012;
- (b) §2.7 assembly + EMPTY object: required by every batch (assembly) and by OB0114 (EMPTY);
- (c) the in-checklist vacuous-examination rule: needed only by 2 non-canary batches.

- (d) EG-6: the governed re-run of a failed attempt + the pre-registered retry policy (§14). Needed before the canary, because a failed canary batch otherwise has no re-run path, and the policy must be declared before any S5 output;
- (e) EG-8: the EP-01 provenance remedy (§15). A governance act by the file's owner; no code.

- (f) EG-3: the hub required-display rule §5.9a (§16). Needed only before the 14 hub batches; the canary has no hub. It may join the same rebind, or wait for a later one;
- (g) EG-9 + EG-10: §21 audit + H-06 acceptance under R7 (§17). No addendum text, so no rebind. **The EG-10 runbook fix inside (g) blocks the canary.**

(a), (b), (d) and optionally (f) share one addendum revision and one rebind, so approving them separately means more rebinds.

**Covers:**
- addendum v2.8 as in §2;
- the `assemble` tool slice;
- the production rebind as in §9;
- part (d): `orchestrate retire`, state `new_attempt`, the §2.8 text + policy RR-1..RR-7, classes, M = 4, W-SYS, tests first;
- part (e): the EP-01 commit and sha binding, performed by its owner or on the owner's instruction;
- part (f): §5.9a text + prompt rendering of R(run) + RI-1b coverage reporting, tests first;
- part (g): the audit tool for `<B>-R7` + `check_tranche` for H-06 + the G-LOG rulings, tests first; the EG-10 runbook step 7b;
- the canary pre-check re-run.

**Does not cover:**
- the S5 canary or any dispatch;
- sampled-content reads;
- EG-3 (hub batches). §2.7's hub-EMPTY LOAD-escalation text only defines the EMPTY object's form for a hub label. It authorizes no hub batch execution, which still waits for EG-3;
- closing EG-5c in the verifier;
- RC-02;
- executing any §21 audit or H-06 acceptance (the tools only, per part (g));
- hash-chaining the governance log (a separate governance-integrity backlog item).


## 14. Part (d): EG-6, governed re-run of a failed R7 batch (reviewed; SPEC-ACCEPTABLE after 3 rounds)

**Working papers:** `audit-p3b/eg6/` (EG6-SPEC-A v1–v3, EG6-REVIEW-B v1–v3, probes).

**Problem.** A failed R7 batch has no re-run path: `new_run` refuses at revision 7, and Legacy(B) is digest-frozen. The canary stop rule anticipates failures.

**Mechanism: option (b), retire.** No reader or verifier change. Plans, slices, views, I(run) and prompts are unchanged.
- `orchestrate retire B` moves attempt m's run directories and assembly unaltered into `ledger-p3b-r2/<B>-R7.A<m>/`, and the archive to `<archive>/<B>.A<m>/`.
- It is crash-safe and write-once:
  - `RETIREMENT-INTENT.json` is written first, with every sha256; `RETIREMENT-COMPLETE.json` last;
  - every step is resumable; every moved byte is verified;
  - a per-rename device rule: EXDEV or an `st_dev` mismatch is refused as class X and resumes cleanly;
  - copy-and-delete is never used.
- The manifest's `legacy_dirs` and `legacy_ledger_sha256` are re-frozen through the existing `rebind_manifest`.
- The state gets `new_attempt` (attempt-aware history). It refuses once any attempt reached VERIFIED, AUDITED or ACCEPTED, and it enforces sequential attempts, at most M.
- The guard `may_start_attempt` blocks work while a retirement is PENDING or the archive path is non-empty.
- Attempt m+1 runs in a fresh orchestrator session (W2).

**Probes** (orchestrator and B):
- retire + re-frozen digest → BATCH-PASS;
- an unlisted, un-re-frozen, edited or deleted retired attempt → R7-U;
- the retired name is rejected as a reader `--run`;
- 10/10 crash points resume to a byte-identical state; 3 EXDEV/`st_dev` cases pass.

**Pre-registered retry policy.** It must be declared **before** the canary. It is sample-blind, audit-blind and mechanical.
- **RR-1:** only a BATCH-FAIL or an INCOMPLETE dispatch triggers an attempt. A BATCH-PASS is never re-run, and audit results never trigger one.
- **RR-2:** the tools take no sample input.
- **RR-3:** the estimation object is the batch's first attempt that passes and is accepted.
- **RR-4:** failed attempts are never estimated or shown to auditors or adjudicators, and are counted in E-3.
- **RR-5:** at most **M = 4** attempts. M is a bounded-cost cap, not an inferential parameter. With per-attempt failure rate q = 0.10, P(all 396 pass) is 0.96 at M = 4 against 0.67 at M = 3. One NOT-ASSESSABLE sampled SINGLE label raises the primary bound from 77 to about 109.
- **RR-6:** instrument repairs only at the canary gate.
- **RR-7:** an identical failure signature on two consecutive attempts counts as systematic (class D) and stops.

**Failure classes** (mechanical from the verifier's failure tags; precedence S > D > H > X > A1 > A2; unrecognized tags default to D; an UNVERIFIABLE-WITNESS / BATCH-UNDETERMINED outcome specifically is class U):

| Class | Meaning | Re-run? |
|---|---|---|
| X | instrument / environment / runbook | re-run after repair |
| A1 | no admissible measurement (tool-use / protocol / W-violations, incomplete) | re-runnable |
| A2 | a complete object rejected by a content gate | re-runnable within M, reported separately |
| H | audit- or acceptance-informed | never re-run automatically |
| D | design defect | STOP, human |
| S | seal / tamper / replay | STOP, human |
| U | undetermined: the witness is unverifiable (e.g. the archive is absent) | no re-run: restore the archive and re-verify |

Residual non-mechanical cases are listed in spec v2 §3.

**Named limitation** (pre-registered text):
- Difficulty can raise both the failure rate and discordance, and retried readings may be more conservative.
- So θ_D and θ_A describe the **retry-surviving accepted population**. This is a stated limitation, not an estimator bias. The design-based unbiasedness proof covers accepted objects.

**Reporting:**
- the accepted attempt per label;
- an attempt × audit-class cross-tab (exploratory, BH family);
- θ_D and θ_A for the domain "accepted at attempt 1" (exploratory, reported only beside the primary quantities, never standalone or as a corrected value);
- E-3 per class.

**Programme watchdog W-SYS** (execution-only stopping rule, no inferential role):
- After every K = 20 completed post-canary attempts, stop if P(Bin(n, 0.12771) ≥ f) ≤ α_w = 0.01. **α_w is a per-look trigger constant, not the cumulative false-stop probability.**
- f counts classes X, A1 and A2. Attempts that trigger RR-7 are class D and **not** counted.
- Stop thresholds: n = 20 → f ≥ 7; 100 → 22; 200 → 38; 400 → 68; 800 → 126. Verified independently by the orchestrator and by B with exact arithmetic.
- Simulated false-stop rate across all looks: 0.006 at q = 0.10 (0.0043 at 6,000 replicates, per B). P(stop) = 0.977 at q = 0.20.
- **Dispatch order** is the frozen P3B-STATE `batch_order`. It was fixed before any S5 output and is independent of every S5 result, so an early stop leaves an outcome-blind set of batches NOT-ASSESSABLE, worst-cased under EP-01 §7. It is *outcome-blind*, not content-blind: the order comes from pre-output packing metadata. So the unprocessed remainder after an early stop is not a random subsample, but the worst-case bound stays valid whatever the reason labels are missing.

**Materiality.** It is governance-material. The §2.3/§2.4 text changes, so it shares the v2.8 rebind. No verifier or reader change.

**Frozen objects: none change.** The estimands keep their definitions. The retry policy fills the "authorized re-run" rule that EP-01 leaves open. It must be pre-registered before the canary.

## 15. Part (e): EG-8, EP-01 provenance (a governance act)

**Finding.**
- B1 v2 (Freeze 1, G-LOG-0094) declares EP-01 (`audit-p3b/20260926_EP-01-STATISTICAL-SPECIFICATION.md`) as its base ("consumed, not repeated").
- EP-01 is **untracked**, and **no artifact binds its sha256**:
  - G-LOG-0094 sha-binds B1 v2 `d6b23886…` and `prereg_v1.py` only;
  - the Freeze 2 proposal names EP-01 three times (including in its traceability) but never gives it a sha;
  - the Freeze 2 record JSON does not reference it.

  The gap is the missing sha binding, not a missing citation.
- So an executed freeze depends on an unversioned document. Current sha256: `f43504f4a331efa85c5cb9dff8d55b3164acf314340109e94e7a62af428ec0aa`.

**Remedy.**
- The owner commits EP-01 unchanged.
- A G-LOG binds that sha and records that Freeze 1's content includes EP-01 by reference. This notes the citation gap; it does not rewrite history.
- It must happen **before the canary**, so that the frozen design is fully hash-bound before any S5 output exists.
- The orchestrator does not commit files that are not its own. It will do so only on the owner's explicit instruction.

**Recorded process deviation.** During EG-6, Subagent A read EP-01 despite the "no untracked files" constraint (it is B1's declared base). B was then explicitly permitted to read it.


## 16. Part (f): EG-3, the hub reading protocol (reviewed; SPEC-ACCEPTABLE after 2 rounds)

**Working papers:** `audit-p3b/eg3/` (EG3-SPEC-A v1–v2, EG3-REVIEW-B v1–v2, probe, `hub_view_profile.json`, `hub_required_counts.json`).

**Problem.** Every run gets a lossless view and pages it at 25 lines per Read. For the 66 hub labels (HUB is 33 of the 231 sampled labels: Freeze 2 record `strata.HUB` N = 66, n = 33), the full view needs median 318, p90 1,735 and max 5,950 Reads per label: about 8.8M characters at the max, which exceeds a context. Hub slices are dominated by `search_records` (66.9%) and `source_meta` (28.5%) of characters (orchestrator counts, `audit-p3b/eg3/hub_path_split_and_shares.json`).

**Contract facts** (cited, re-verified by B3):
- hub runs have no stage 2;
- every hub absence is mechanically ESCALATED/LOAD with `hub_record` (NOT-FOUND-LOAD-ESCALATED "asserts nothing about the hits");
- `negative_label` is per label;
- no contract text requires reading all of `search_records` / `source_meta`;
- the hit entries serve only the verifier and the hub-list builder;
- the prompt's "until the whole file has been displayed" sentence has no contract anchor, for any run. The view is non-evidentiary input; the witnessed corpus reader grounds FOUND and births.

**Rule §5.9a, HUB-REQUIRED view lines.** A hub run must display R(run), computed from the frozen view's JSON paths:
- every key except `search_records` and `source_meta`;
- `search_records[i ≥ 1]` (the DIMENSION records);
- `search_records[0]` without any leaf under `ledger_hits` / `raw_hits`;
- `source_meta[S]` for S ∈ T(L), the citable S-ids of the slice outside `search_records`, `stage2_files` and `source_meta`.

The prompt lists R(run) as line ranges. RI-1b reports, but does not fail, whether the witnessed displayed ranges cover R(run), naming uncovered paths.

**Exact production counts** (orchestrator, counts only): R(run) needs median **41**, p90 **88**, max **127** Reads per hub label. Total hub reads fall 49,934 → 3,349 (−93%). The hub labels split into 64 SINGLE, 1 DECOMPOSED and 1 EMPTY (orchestrator counts, `hub_path_split_and_shares.json`, computed after EG3-SPEC-A v1–v2 marked the split PENDING; this closes that PENDING item).

**Why report-only is safe.** The verifier checks hub objects for exact equality with the frozen slice, whatever was displayed (`p3b_s5_verify.py:375, 756-759, 777, 781-790`; `hub_rec_eq :1123-1130`, exact structural equality of parsed JSON).

**Probe.** Rule-3-conforming; RED test E2 passes; a negative control rejects the superseded first-leaf variant.

**Materiality.** Contract-material: it changes what hub agents must display, so it needs addendum text and a rebind. It is not verdict- or science-material: no change to gates, evidence, absences, statistics, plans, slices, views, strata or the sample. θ_D on hub absences is a rule-determinism check, parallel to EMPTY.

**Sub-decisions with recommendations:**
- coverage report-only rather than a verifier gate;
- T(L) = citable S-ids (+97 reads in total over the narrow variant; R(run) is a minimum, so this affects only false NOT-COVERED noise);
- the same R(run) for every role of the one hub-DECOMPOSED label.

**Residual (recorded).** An agent could copy the mechanical hub absences without displaying its input. The answers would still be exact (verifier equality), but the evidence that the agent *saw* its input is only reported (RI-1b), never enforced.

## 17. Part (g): EG-9 (§21 + H-06 under R7) and EG-10 (runbook), reviewed; SPEC-ACCEPTABLE after 3 rounds

**Working papers:** `audit-p3b/eg9/` (EG9-SPEC-A v1–v3, EG9-REVIEW-B v1–v3).

**Problem.**
- The state machine requires VERIFIED → AUDITED (§21, `p3b_s5_audit_record.py`, which refuses `<B>-R7` at :53) → ACCEPTED (H-06, `p3b_s5_accept.py`).
- So no R7 batch can reach ACCEPTED. PROGRAM-ACCEPTED and EG-6 RR-3 would be unreachable, and every label would end NOT-ASSESSABLE.

**Facts.**
- §21 (protocol v1.7, rev3 §16.5; re-derived by B2) is still required under R7; no entry supersedes it.
- Its re-derivation is blind, but the disposition step compares against the S5 record.
- The R7 verifier is a composer of predicates with no epistemic rule of its own, so it cannot evaluate the audit halves of G-06/G-08/G-09/G-12. EP-01 gates nothing.

**Recommendation: option (a), keep AUDITED under R7.** No addendum text, so no rebind.
- **Engineering:**
  - the audit tool accepts exactly `<B>-R7`, with findings bound to the sample, the verified assembly bytes and the VERIFIED state (tests E9-01…E9-12);
  - H-06 acceptance is bound to a committed, sha-anchored tranche file `audit-p3b/H06-TRANCHE-<nnnn>.json`, via `check_tranche` in `p3b_s5_accept.py`, required at revision 7 (tests T-01…T-17);
  - `check_tranche` requires a unique G-LOG heading and captures the section sha and the commits at acceptance;
  - recommended grouping: 80 tranches (79 of 5 batches, 1 of 1), i.e. 80 human acts.
- **G-LOG rulings:**
  - a firewall: §21 tools take no Freeze-2 input; the §21 auditor is never the S5 reader or a θ_D/θ_A auditor; §21 material never enters θ_D/θ_A inputs;
  - a §21 FAIL or an H-06 rejection is **class H**: the batch is FAILED with no automatic re-run. Its labels become NOT-ASSESSABLE (worst case, EP-01 §7). The loss is disclosed as batch-wide and non-retryable, beside EG-6's retry-survival limitation, per class in E-3.

**Correction record: a HUMAN decision (H-EG9-6).** §21 item 2 makes AUDIT-UPHELD produce a correction record (the rev3 §12.3 mechanism, P3B-CORRECTIONS.jsonl). Which object is then the S5 outcome?

| Reading | Meaning |
|---|---|
| **R-I, verified bytes (recommended)** | The estimation object is always the witness-verified assembly. Corrections are stored and reported. A correction is applied only by a separate human act. *(Clarification from B2's EG9-REVIEW-B-v3 recommendation, adopted by the orchestrator, not yet in a builder revision: that act authorizes a **fresh witnessed S5 run** for the excepted content, never a bypass write.)* |
| R-II, corrected object | The estimation object is the record after applying accepted corrections |
| R-III, exception | The excepted content is quarantined and stays PROPOSED |

- **Why R-I:**
  - E-1 then measures the S5 reading;
  - θ_D stays blind with uniform review intensity;
  - the estimation object stays witness-bound;
  - it follows the precedent: G-LOG-0025 accepted PB05 **conditional**, "correction recorded as pending and not applied", with the content quarantined (R-III form). G-LOG-0024 is only the audit's recommendation.
- **Why not R-II:** it would let a same-family auditor's judgment enter the measured object for only the ~10% of records §21 samples, biasing θ_D toward agreement and breaking witness binding.

**EG-10: a runbook defect that BLOCKS THE CANARY** (confirmed by the orchestrator, B2 and A4). The runbook never writes DISPATCHED → PROPOSED, and the orchestrator tool calls no state transition.
- **New step 7b** (after `freeze-final`, before `verify`): `python3 -B scripts/p3b_s5_state.py transition B PROPOSED --reason "assembly + freeze-final complete; WITNESS-DIGESTS.json sha256 <h>"`. On a freeze or digest failure, `transition B FAILED --reason "<cause>"`; on an interrupted dispatch, `transition B INCOMPLETE`.
- **Step 9 restated:** BATCH-PASS → `transition B VERIFIED --evidence audit-p3b/S5-VERIFY-<B>-R7.json`; BATCH-FAIL → FAILED with its tags; BATCH-UNDETERMINED → no transition (restore the archive, re-verify; class U).

**Residuals** (recorded, honest):
- `check_tranche` is a point-in-time check. Its anchor, P3B-GOVERNANCE-LOG.md, has no code-level append-only enforcement. The capture-at-acceptance fields make a later rewrite detectable on re-verification, but authorship stays unproven.
- Hash-chaining the log is a separate governance-integrity item.
- The tranche `attempt` field is always 1 until EG-6 part (d) lands.
