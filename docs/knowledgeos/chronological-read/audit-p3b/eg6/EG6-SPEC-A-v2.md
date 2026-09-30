# EG-6 specification v2 (delta on `EG6-SPEC-A.md`; v1 kept)

| | |
|---|---|
| Kind | DESIGN ONLY; delta. authority: generated. No repository file is changed. The v2.8 text is a **proposal, not applied**. |
| Responds to | `EG6-REVIEW-B.md` (SPEC-NEEDS-REVISION): E-01 … E-06 |
| Unchanged from v1 | option (b) (retire into a governed Legacy(B) member, same run ids, fresh session); no reader or verifier change; the v1 §2 probe table |
| New probes | `probes/retire_model.py`: a crash-safe `retire` reference model run on synthetic trees, using the production `U.legacy_digest` / `U.namespace_violations` · `probes/m_cap_table.py`: M arithmetic and exact bounds. Its U(d=0) reproduces F2 §2 (HUB 5, DECOMPOSED 5, SINGLE 67) |
| Evidence read this round | EP-01 in full (design use only) · `p3b_s5_audit_record.py` · `p3b_s5_accept.py` · a grep of every non-test script for `result` / `"PASS"` consumers · B1 §5 |

Citation key as in v1. EP = `audit-p3b/20260926_EP-01-STATISTICAL-SPECIFICATION.md`, AR = `scripts/p3b_s5_audit_record.py`, AC = `scripts/p3b_s5_accept.py`. **[F]** fact · **[T]** probe · **[D]** design · **[H]** human.

---

## 1. E-01: crash-safe, resumable, write-once `retire`

**[D] Records.** There are two write-once records in `ledger-p3b-r2/<B>-R7.A<m>/`, each written as tmp → fsync → rename:
- **`RETIREMENT-INTENT.json`** (written first, before any move): batch, attempt m, cause class (§3), the verify report path and sha256, the **sha256 of every file** of every active run directory and the assembly (`<B>-R7/`), and the sha256 of every file of `<archive>/<B>/`, with source and target names.
- **`RETIREMENT-COMPLETE.json`** (written last): the intent's sha256 and the verified file count.

A retirement is **PENDING** ⇔ INTENT exists and COMPLETE does not. There is no mutable state field. Both records lie inside the retired directory, so the re-frozen legacy digest binds them.

**Step order** (each step idempotent on re-invocation):

| # | Step | On resume |
|---|---|---|
| S0 | Preconditions: attempt m is FAILED or INCOMPLETE in the state; **no VERIFIED entry in any attempt of B** (§4); `<archive>/<B>/` exists (run `archive B` first); archive and ledger on one filesystem (else REFUSED); no other PENDING retirement of B; the target does not exist, or holds only INTENT or a stale INTENT `.tmp` (the tool's own scratch, discarded) | — |
| S1 | Write INTENT (the only step that inspects the live footprint) | if INTENT exists: use it, never re-inspect |
| S2 | `os.rename` each listed run or assembly directory → `<B>-R7.A<m>/<name>` (atomic per directory) | source only → move · target only → already moved · **both → REFUSED** (class S) · **neither → REFUSED** (evidence lost, class S) |
| S3 | Verify every moved byte against INTENT: exact file set, same sha256 | any mismatch → REFUSED (class S) |
| S4 | `os.rename` `<archive>/<B>/` → `<archive>/<B>.A<m>/` | the same four cases |
| S5 | Verify the archive bytes against INTENT | mismatch → REFUSED |
| S6 | Write COMPLETE; print the new `legacy_digest` | exists identical → `ALREADY-COMPLETE` |

The two follow-on acts stay separate and governed:
- the manifest re-freeze (`legacy_dirs`, `legacy_ledger_sha256` = the printed digest) followed by `rebind_manifest`;
- `state new-attempt`.

**Guard for attempt m+1: `may_start_attempt(B)`.** It must hold before `prepare B`, `archive B` and any dispatch of attempt m+1, and `state new-attempt` also requires it. It refuses if any of these holds:
- (i) a retirement of B is PENDING;
- (ii) `<archive>/<B>/` exists and is non-empty;
- (iii) any planned run directory or `<B>-R7/` exists;
- (iv) the manifest's `legacy_ledger_sha256` ≠ `U.legacy_digest` of the current tree.

`archive B` for attempt m+1 writes `<archive>/<B>/ATTEMPT` (the attempt number and session id) on first use, and later refreshes must match it.

**[T] Reference model** (`retire_model.py`: 5 planned runs incl. a DECOMPOSED label, assembly, archive):
- clean run → COMPLETE. The namespace violations are 0 once listed and re-frozen, and 2 without the governed act.
- **A crash after each of the 10 steps** (after INTENT, after each of the 6 directory moves, after the ledger verify, the archive move and the archive verify):
  - in every case `may_start_attempt` = **blocked** ("PENDING");
  - resuming gives COMPLETE with a **byte-identical final state**;
  - a re-run gives ALREADY-COMPLETE. **10/10.**
- **Adversarial:** bytes altered between INTENT and the move → REFUSED ("moved bytes ≠ the intent record"). A source directory re-created after its move (e.g. by a late reader call) → REFUSED ("both source and target exist").
- Before any retirement, the guard blocks attempt 2 ("archive path … non-empty").

## 2. E-02: statistics, named mechanism, reporting, M

**[F] Context.**
- Design-based unbiasedness for D over final objects holds under RR-1…RR-7 (review B agrees; EP:18-20, 78-80, 118-128).
- The sensitivity analyses below are **estimation, not tests**. B1 §5 has the confirmatory family H1–H5 (Holm), the exploratory family (BH, q = 0.05: "individual implication pairs, transition cells, **covariate splits**, anything not in §3"; B1:81-82) and the statistical audit θ_D/θ_A (exact bounds; B1:83).

**[D] Named mechanism.** This is pre-registered text for §2.8; see §6.
- **Difficulty × retry survival:** a label's difficulty can raise both its first-attempt failure probability and its discordance.
- Accepted objects are, by construction, attempts that survived the verifier. If a later attempt on a hard label is systematically more conservative (more escalations, fewer committal assertions), the accepted population's disagreement can be **lower** than an unfiltered first-attempt population's, concentrated on hard labels.
- θ_D and θ_A are therefore defined and reported **for the retry-surviving (accepted) population**. This is a named, accepted limitation, not an unbiasedness defect.

**[D] Reporting requirements** (they add no confirmatory test):
1. **Attempts per label** (= the batch's accepted attempt number) is recorded for every frame label. It is free, from the state history.
2. **Cross-tab** over the audited sample: accepted attempt (1 / 2 / ≥ 3) × audit class (CONCORDANT / DISCORDANT / AUDIT-FAILED / NOT-ASSESSABLE), per stratum and pooled. Any test of association on it belongs to the **exploratory BH family** (a covariate split, B1:82) and is never cited as confirmation.
3. **Sensitivity (exploratory):** θ_D and θ_A are reported (a) for all accepted labels (the primary, frozen quantity) and (b) for the **domain** "batch accepted at attempt 1".
   - (b) is a design-based **domain estimate**: HT over the sampled labels in the domain, D̂_dom = Σ_h N_h/n_h · d_h,dom. It is unbiased for the domain total because the sample is independent of attempts.
   - Its exact bound is informational. It is not a new estimand, not a test, and it changes no freeze.
4. **E-3 per class:** attempts and failures by class X / A1 / A2 / D / S / H (§3), per batch and per stratum.

**[F/T] M is a bounded-cost cap, not an inferential parameter.** With per-attempt batch failure probability q and **independent** attempts (an optimistic assumption, since hard batches correlate), `m_cap_table.py` gives:

| q | M = 2: P(all 396 pass) | M = 3 | M = 4 | M = 5 | E[never-passing batches] at M = 3 / 4 |
|---|---:|---:|---:|---:|---|
| 0.02 | 0.854 | 0.997 | 1.000 | 1.000 | 0.00 / 0.00 |
| 0.05 | 0.371 | 0.952 | 0.998 | 1.000 | 0.05 / 0.00 |
| 0.10 | 0.019 | 0.673 | 0.961 | 0.996 | 0.40 / 0.04 |
| 0.20 | 0.000 | 0.042 | 0.530 | 0.881 | 3.17 / 0.63 |
| 0.40 | 0.000 | 0.000 | 0.000 | 0.017 | 25.3 / 10.1 |

**Effect on the primary bound.** A NOT-ASSESSABLE sampled label counts as discordant (EP §7). The exact per-stratum U_h(d) at α/H′ = 0.05/3 is:

| Stratum | d = 0 | d = 1 | d = 2 | d = 3 |
|---|---:|---:|---:|---:|
| HUB | 5 | 8 | 11 | 13 |
| DECOMPOSED | 5 | 8 | 11 | 14 |
| SINGLE | 67 | 99 | 127 | 153 |

The census strata give U = d.
- **One** NOT-ASSESSABLE SINGLE label raises U from 77 to ≈ 109 (3.9% → 5.5% of 1,975).
- Expected NOT-ASSESSABLE sampled labels ≈ 231·q^M.
- The expected attempt cost ≈ 1/(1 − q) batches per batch, so a larger M is cheap in expectation and binds only in the tail. RR-7 (an identical signature twice → D) stops deterministic failures independently of M.

**[D] Recommendation: M = 4.** It keeps P(PROGRAM-ACCEPTED) ≥ 0.96 and E[NOT-ASSESSABLE sampled] ≤ 0.03 for q ≤ 0.10, at negligible expected cost. The survival mechanism grows with M, but it is made observable by items 2 and 3. M is fixed **before the canary**. After the canary, the observed q (E-3) is reported against this table. No change of M after outputs exist.

## 3. E-03: class A split; mechanical classification

**[D] Classes** (precedence **S > D > H > X > A1 > A2**: the attempt's class is the highest class among its failure strings, so the most upstream cause wins, per the diagnosis order runbook → prompt → agent):

| Class | Meaning | Re-run |
|---|---|---|
| **S** security / integrity | SEAL, tamper, replay | **no**: STOP, P3B-ESC, human |
| **D** design / frozen artifact | a deterministic failure every attempt repeats | **no**: STOP, human |
| **H** human/audit-stage | the §21 audit FAIL or an H-06 rejection (after VERIFIED) | **no** automatic re-run (audit-informed selection, RR-1). The labels become NOT-ASSESSABLE unless a human act decides otherwise |
| **X** instrument / environment | harness, storage, orchestrator artifacts | **yes** |
| **A1** no admissible measurement | the agent did not complete a valid execution (tool/protocol/witness) | **yes** (no measurement exists to discard) |
| **A2** measurement rejected | a complete object was produced but failed a content/consistency gate | **yes, within the same M**, with the §2 named mechanism, reported separately in E-3, the cross-tab and the sensitivity domain |

**Why A2 is re-runnable.** "Never" makes every batch with one A2 label wholly NOT-ASSESSABLE: the verification unit is the batch, so it would discard the siblings' valid readings. It also makes PROGRAM-ACCEPTED practically unreachable: at per-attempt batch A2 rate q₂, P(all pass) = (1 − q₂)^396, which is 0.019 at q₂ = 0.01 (the M = 1 row logic). Each lost SINGLE sample label adds about +32 to U (§2). Re-running keeps the frame whole. The cost is the named survival mechanism, which is disclosed and measured.

**[D] Mechanical mapping** (from the verifier failure strings; W-level strings carry `R7-W <run> call <i> (<tool>): …`, WI:264). The first matching row in precedence order wins:

| Tag pattern | Class |
|---|---|
| `QUARANTINE …` · any string containing `SEAL` · `R7-W W7…` / `R7-W W7a…` (a freeze/archive digest mismatch) · `R7-E W6` (READ-LOG ≠ witness: the reader-written log vs the harness) · a retired transcript digest reused | **S** |
| `R7-U binding…` · `R7-U contract…` · `R7-U plan…` · `R7-U namespace…` · `R7-U binary decisions…` · `SLICES …` · `HUB <label>: slice …` · an identical failure-string set on attempts m and m+1 (RR-7) · any R7-R **EMPTY** failure (the EMPTY object is tool-made: a defect of the rule or tool) | **D** |
| the §21 audit FAIL · an H-06 rejection | **H** |
| `R7-W W1-TRANSCRIPT-MISSING` · `W1-TRANSCRIPT-DUPLICATE` · `W1-HARNESS-COUNT-MISSING` · `W1-COUNT-MISMATCH` · `W1-PARSE` · `W1-CHAIN` · `W1-NOTIFICATION-ABSENT` · `R7-W W1 <run>: planned run was never dispatched` · `R7-W W2 dispatch …` (a binding line or canary: orchestrator-written) · `R7-W W5 batch: …` (the marker order) · `R7-U inputs …` · `R7-U slice view …` · `R7-U: the SLICE-VIEW …` · `ASSEMBLY-REFUSED` with a tool-side cause | **X** |
| `R7-W W1-ZERO-TOOL` · `W1-DECLINED` · `W1-NOTIFICATION-MULTIPLE` · `R7-W W2 <run>: … dispatches with tool effects` · the W3/W4/W8 strings under `R7-W <run> call …` (non-canonical/refused/denied/unauthorized calls, Read outside I(run), Write outside the permitted paths) · `R7-W W5 <label>: …` (reads after production, an output changed after its last Write) · INCOMPLETE (a dispatch interrupted) · `ASSEMBLY-REFUSED` with an agent-record cause (R2–R4, R3′, R11) | **A1** |
| `R7-U <label>: …` (object Σ/typing/META) · `R7-E …` except W6 · `R7-R …` except EMPTY · `G-01…G-12`, `SCHEMA`, `SEMANTIC`, `F1`, `F3`, `D-23`, `READ-COVERAGE`, `AUDIT`, `HUB <label>:` object checks | **A2** |
| no matching row, or `R7-W UNVERIFIABLE-WITNESS` alone | **no re-run**: restore the archive and re-verify (U); an unknown string → **D** (fail closed) |

**Residual non-mechanical cases** (stated; each defaults to its fail-closed class):
- (1) A W8 or W4-DENIED call caused by a **prompt or harness-permission defect** is A1 by default. It becomes X only by a recorded engineering diagnosis, and only at the canary gate (RR-6).
- (2) `R7-E W6` may be a reader crash rather than tampering. It defaults to S (a human decides).
- (3) Class H is human by definition.

Nothing else needs judgment after seeing outputs. The classifier takes no sample, audit or content input (RR-2).

## 4. E-04: at most one PASS; attempt ordering; attempt-aware evidence (design)

**[F]** Attempts share run id `<B>-R7`, so `check_evidence`'s run check (ST:133-134), `run_history` (ST:236-237) and AC:51-56 (evidenced set; "already has a recorded H-06 decision", keyed on `(batch, run_id)`) are **attempt-blind**. The existing `_append` stores evidence `{path, sha256, kind}` in every history entry (ST:139-146).

**[D] Enforcement (state module):**
1. History entries gain `attempt` (new entries only; the hash-chain format is unchanged). `run_history(st, b, run, attempt=None)` filters by attempt, and AC uses the current attempt.
2. **At most one PASS per batch:** `new_attempt` refuses if **any** history entry of B has `to ∈ {VERIFIED, AUDITED, ACCEPTED}`. Only a pre-VERIFIED failure (class X/A1/A2) is retirable, and class H is never retired automatically. So at most one BATCH-PASS report can ever be admitted per batch, and RR-3's "first passing attempt" holds mechanically.
3. **Ordering:** `new_attempt` requires `attempt = previous + 1 ≤ M`, a COMPLETE retirement record of attempt m (its sha stored in the entry) and `may_start_attempt` (§1).
4. **Evidence:** `check_evidence` refuses a report whose sha256 equals any `evidence.sha256` already in B's history (any attempt). The attempt's report is written to `S5-VERIFY-<B>-R7.A<m>.json` for m ≥ 2 (`--out`; V:1171-1175 never overwrites), and the state requires that name for m ≥ 2.

## 5. E-05 / EG-7: every consumer that rejects BATCH-PASS, and the minimal fix

**[F] The grep** over non-test `scripts/*.py` for verify-report `result` consumers:

| Place | Behaviour under R7 | Blocks |
|---|---|---|
| ST:131-132 `check_evidence` | `body.result != "PASS"` → refuse. The R7 verifier emits only BATCH-PASS/-FAIL/-UNDETERMINED (V:1102; T80 `test_p3b_s5_r7_full.py`:113-114) | **PROPOSED → VERIFIED, for every R7 batch** |
| AR:53 | refuses any `--run` not `<B>-R2(.n\|S)`, so **no AUDITED evidence can be produced for `<B>-R7`**. AR's own vocabulary (`result: PASS\|FAIL`, AR:62-63) is compatible with `check_evidence` | **VERIFIED → AUDITED** (and so ACCEPTED) |
| AC:51-56 | vocabulary-neutral, but attempt-blind (§4) | only with attempts |
| V:1198 (`main` exit code) | already accepts `("PASS", "BATCH-PASS")` | — |
| `p3b_s5_pilot.py`:205, `p3b_ob0018_pilot.py`:824-846 | historical pilots (R2/R5-era), not on the R7 path | — |
| the other `== "PASS"` hits (S0–S3, S4 prep, S5a gates) | their own artifacts, not verify reports | — |

**[D] Minimal state-side fix** (non-material engineering; it can land before v2.8, tests first):
- (a) `check_evidence` takes the batch's revision. For **VERIFIED at revision 7** it requires `result == "BATCH-PASS"` and rejects every other value, including "PASS". For revision < 7 it keeps "PASS" exactly.
- (b) AR accepts `--run <B>-R7` (exactly; not a unit, synthesis or `.A<m>` id).
- **[H] Open:** whether the per-batch §21 audit (AUDITED) is part of the R7 lifecycle at all. RB §1 goes PROPOSED → VERIFIED and does not mention AUDITED, and EP/B1 audit **after** acceptance. Fix (b) only removes the grammar block. The semantics are a governance question.

**EG-7 tests** (separate list; synthetic reports built with `c.header` over a canonical body, temp state files):

| # | Test | Expected |
|---|---|---|
| S-01 | rev-7 PROPOSED → VERIFIED with BATCH-PASS, correct `script_name`, `output_sha256`, batch and `<B>-R7` | accepted; evidence recorded |
| S-02 | rev-7 with BATCH-FAIL, BATCH-UNDETERMINED | refused |
| S-03 | rev-7 with an R2-style `"PASS"` body | refused (vocabulary bound to the revision) |
| S-04 | rev < 7 regression | "PASS" accepted, "BATCH-PASS" refused, all existing state tests green |
| S-05 | rev-7 with a wrong `run_id` / `batch_id` / `output_sha256` / `script_name` | refused (existing checks) |
| S-06 | end-to-end: a report written by `p3b_s5_verify.main --out <tmp>` on the synthetic R7 fixture | `check_evidence` VERIFIED accepts |
| S-07 | AR with `--run <B>-R7` | a report is written. `<B>-R7-L01`, `<B>-R7-L01S`, `<B>-R7.A1` are refused; the R2 forms are unchanged |
| S-08 | VERIFIED → AUDITED with an AR report for `<B>-R7` | accepted |

## 6. §2.8 text: replacement of the v1 policy paragraph (PROPOSAL)

> **Policy (pre-registered, fixed before the canary).** An attempt is triggered only by a verifier BATCH-FAIL or an
> INCOMPLETE dispatch **before** any VERIFIED entry of the batch. Its cause class is derived mechanically from the
> verifier's failure strings by the frozen mapping (precedence S > D > H > X > A1 > A2; unknown → D): X
> (instrument, environment), A1 (no admissible measurement) and A2 (a measurement rejected by a content or
> consistency gate) may be re-run; D, S and H stop for a human act. At most **M = 4** attempts per batch; two
> consecutive attempts with an identical failure-string set are class D. The rule never consults the audit sample
> or any audit result. The estimation object of a label is its batch's first attempt that reaches BATCH-PASS and is
> accepted; failed attempts never enter estimation and are never shown to an auditor or adjudicator. **Named
> limitation:** a label's difficulty may raise both its failure probability and its discordance, and a retried
> reading may be more conservative; θ_D and θ_A therefore describe the retry-surviving (accepted) population. The
> attempt number is recorded per label; the audit report gives the attempt × audit-class cross-tab (any association
> test is exploratory, BH family) and θ_D, θ_A also for the domain "accepted at attempt 1" (exploratory domain
> estimate); E-3 reports attempts and failures per class. A retirement is crash-safe: an intent record precedes
> every move, every moved byte is verified against it, and no attempt starts while a retirement is PENDING or
> `<archive>/<batch>/` is non-empty.

**RED tests added to v1 §6:**

| # | Test |
|---|---|
| T135 | a crash after every retire step → blocked, resumes to a byte-identical state, idempotent (the reference model is green 10/10) |
| T136 | tamper between intent and move → REFUSED; a source re-created → REFUSED |
| T137 | `prepare` / `archive` / `new-attempt` refused while PENDING or while `<archive>/<B>/` is non-empty |
| T138 | the classifier: one fixture string per mapping row → its class; precedence on mixed sets; unknown → D |
| T139 | `new_attempt` refused after any VERIFIED entry (at most one PASS) |
| T140 | evidence sha reuse across attempts refused; AC decides per attempt |
| T141 | the cross-tab and domain estimate computed from a synthetic audit table: HT domain estimator unbiased by exact enumeration on a small population (as `estimate_v7`'s ST checks) |

## 7. E-06 / EG-8: minimal governance remedy (text only; nothing modified)

**[F]**
- `audit-p3b/20260926_EP-01-STATISTICAL-SPECIFICATION.md` is **untracked** (`git status`: `??`). Its current sha256 is `f43504f4a331efa85c5cb9dff8d55b3164acf314340109e94e7a62af428ec0aa` (recomputed here; it matches the brief).
- B1 names it as its **Base** (B1:7), and Freeze-1's scope includes "failed / NOT-ASSESSABLE handling; the decision fields" (B1:106), which are EP-01 §3 and §7 content.
- F2's traceability ("Rests on Freeze 1 (G-LOG-0094; B1 v2 `d6b23886…`, `prereg_v1.py` `eb6291ab…`)", F2:5) binds **no** EP-01 hash.

> **Proposed G-LOG entry (for the owner; not written).** *EP-01 binding (EG-8).* The owner commits
> `audit-p3b/20260926_EP-01-STATISTICAL-SPECIFICATION.md` unchanged and records its sha256
> `f43504f4a331efa85c5cb9dff8d55b3164acf314340109e94e7a62af428ec0aa` as the bound EP-01 text that B1 v2 names as its
> Base. Citation gap noted: Freeze 1 (G-LOG-0094) and the Freeze-2 record bind B1 v2 and `prereg_v1.py` but not EP-01,
> although Freeze 1 fixes EP-01 content (decision fields; failed / NOT-ASSESSABLE handling). This entry closes the gap
> by reference; it changes no frozen value. Any later difference between EP-01's bytes and this hash is a design
> deviation requiring a human act.

## 8. Human decisions (updated; consolidatable as separable part (d) of the v2.8 package)

| # | Decision | Recommended |
|---|---|---|
| HD-6.1 | Option (b) + §2.8 (v1 §6 with §6 above) in the v2.8 rebind; the state/orchestrator slice tests first | **yes** |
| HD-6.2 | Pre-register before the canary: the §6 policy text incl. the **named difficulty × retry-survival limitation**, the attempt × audit cross-tab, the attempt-1 domain sensitivity (exploratory) and the per-class E-3 | **yes** |
| HD-6.3 | M | **4** (bounded-cost cap; §2 table); not revisable after outputs exist |
| HD-6.4 | A1 and A2 re-runnable within M, with A2 reported separately; H not automatically re-runnable | **yes** |
| HD-6.5 | EG-7 fix (a) + (b) as separate non-material engineering before v2.8, tests S-01…S-08; decide separately whether the §21 AUDITED stage applies under R7 | **yes**; the AUDITED semantics are **open** (a governance question) |
| HD-6.6 | EG-8: commit EP-01 and bind its sha in a G-LOG (§7 text) | **yes, before the canary** (every later document depends on it) |

**Traceability:** v1 `EG6-SPEC-A.md` · `EG6-REVIEW-B.md` E-01…E-06 · EP §1, §3, §4, §7 · B1:7, 81-83, 95-96, 106 · F2:5, §2 · ST:111-146, 194-264 · AR:46-72 · AC:51-59 · V:1099-1102, 1171-1198 · WI:264 · probes `retire_model.py`, `m_cap_table.py`, `probe_eg6_retire.py`.
