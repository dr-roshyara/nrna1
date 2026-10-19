# Consolidated report: the ALM review gate and TD-001 traceability (2026-10-18)

**Status:** research report. **ALM is NOT validated, NOT canonical, NOT for publication.** TD-001 (the original, r1 and r1.1) and the manuscript are unchanged.

## 1. Executive summary (business language)

We built a precise, testable model of "when does a decision count?" (ALM). It separates three questions:
1. Was it properly made?
2. Can our records show it?
3. Can it be used yet?

An independent reviewer then tried to break it, and succeeded in important places:
- **The model's guarantee "if our records are complete, they give the right answer" does not hold** for cancellations of decisions. A cancellation can be judged unauthorized merely because the decision it cancels was entered later (BLOCKER).
- **It cannot reproduce TD-001's promise** "we say *cannot determine*, never *not authorized*" with a single setting. It either breaks the promise or never says anything positive (BLOCKER).
- **It made several policy choices silently.** One example: a person's delegate keeps authority after the person loses theirs.

**What remains useful:**
- the three-question separation;
- a proof that the "was it properly made?" question always has exactly one answer, once the order of acts is known;
- the demonstration that recognition from records can legitimately change as late records arrive.

**ALM is a sound framework, not a sound candidate.** A defined, bounded repair would make it one. **That repair needs authorization**; it has not been started.

## 2. Verified, and by what evidence

| Item | Evidence |
|---|---|
| The working artefacts are secured in project `tmp/` | 116 files copied; sha256 of every file equals its scratchpad source (0 mismatches). The baseline files are re-verified individually (`alm/BASELINE-MANIFEST.md`) |
| The baseline test suite passes 32/32 | `alm/run-20261018.txt`; re-run in `tmp/` |
| Every reviewer counterexample reproduces | `alm/review-1/ce_reproduction_output_20261018.txt` (CE1–8, 10–12). P-5, E-1, E-4 and E-8 were reproduced by hand |
| ALM against the v1 cases: 7 agree, 1 partial, 1 diverges, 1 hidden policy, 7 not expressible | `alm/run-v1cases-20261018.txt` |
| 13 named regression tests encode the accepted defects; 0/13 pass on the baseline | `alm/run-regression-review1-20261018.txt` |
| Source quotations: R-72, R-77, R-80, R-34, ADR-001, A-1.4/A-4.3/A-5.2, P-1…P-6, v3.1 §0.2/§10 | verbatim, verified by both author and reviewer (review E-1…E-7). Two status claims corrected (§7) |

## 3. Derived mathematically

**Survives:**
- **Theorem 1:** given a strict well-founded order on acts in which each act depends only on its predecessors, validity exists and is unique. Circular delegation without a root is invalid. *Premise:* the order must be known. If it is not recorded, the answer can depend on it (CE8).
- **Theorem 4 (with the added premise that negatives from absence are allowed after a finite horizon):** recognition from records is **not monotone**. A verdict can change in either direction as late records arrive (72 down-flips and 2,198 up-flips found in the bounded space).
- **Theorem 6, core:** under an open world, **every** records-based "not authorized" can be overturned by a later-recorded grant. So TD-001's "never 'not authorized'" is **forced**, not merely cautious, unless a completeness policy is adopted.
- **Same-instant non-uniqueness:** mutual revocations at the same instant have two consistent outcomes unless a tie-break rule is fixed.

**Withdrawn after review:**
- Lemma 2 and Theorem 3, as stated;
- Theorem 5's consequence;
- Theorem 6's stated consequence;
- Proposition 8.

## 4. Assumed or untested

**Assumptions:**
- a known act order (A0);
- truthful records;
- a root actor with perpetual scope (C12);
- append-only records, which BC-7 states only as **Proposed** (T-3) and which cannot be verified (C-3);
- the date → position map μ, which **does not exist**.

**Untested or not expressible:**
- citations (C2);
- suspension;
- supersession with a successor (C5);
- annulment with an effective time (RD 2);
- consumption (C13);
- unverifiable endings (RD 6);
- joint authority (C4);
- IND-3, IND-4, IND-6 and IND-7;
- governance-side revocation, which has no observed instance (47/47 grants AUTHORIZED).

**ML/terminology results are discovery aids only:**
- revocation vocabulary is near-absent (4 hits in 103 sources);
- "writes authority" vs "records authority" drift appears in 5 vs 2 sources.

## 5. Independent review findings and dispositions

`alm/review-1/REVIEW-ALM-1-REPORT-verbatim.md` holds the reviewer's report; `alm/review-1/REVIEW-ALM-1-DISPOSITIONS.md` holds the author's side.

- **Findings:** 2 BLOCKER, about 24 MAJOR, about 14 MINOR/NOTE.
- **All accepted.** One partial disagreement (D-1, the labelling of a policy-consequence test) is recorded, not hidden.
- **Classes:** mathematical defects (P-1, P-3…P-9); implementation defects (C-3, C-6, C-9, P-10); unsupported premises (P-2, E-1, E-2, E-4, E-7, E-8); semantic mismatches (F-1, C-4, A-6, E-5); missing scenarios (T-1, T-2, T-3); policy questions (C-1, C-2, C-5, C-10, C-11, F-2, F-3).
- **This review is kept separate from the M2 review.** One cross-link: M2's G-16 pattern (the author's encodings hide defects) **recurred for the fifth time.**

## 6. TD-001 / v1 / M2 traceability

Full matrix: `TRACEABILITY-TD001-V1-M2-ALM.md`. Headline:
- **TD-001 #6** (authority at the time of the act): **covered**.
- **#8** ("later" ending), **#10** (recognition as the records stood) and **#11** ("never 'not authorized'"): **contradicted** by reproduced counterexamples.
- **#4/#5 (C1):** decided, not parameterized.
- **v1 defects:** 4, 5 and 6 are not repaired, or were resolved silently.
- **v1 safeguards 4–6:** not expressible.
- **M2 G-5** (late-filed grant permanently undeterminable): **addressed in kind.**
- **M2 G-3, G-4, G-1 and G-9:** not expressible.

## 7. Remaining defects, counterexamples, and corrections to earlier records

**Defects:** 13 named known-defect regressions (`alm/tests_regression_review1.py`).

**Corrections to my own earlier records** (reported, not silently fixed):
- **Checkpoint Q4:** the claim that conditional acts become operative by a later act "in both cases" is **overstated**. Direct evidence is n = 1 (S0052); R-72 → R-86 was an inference.
- **Checkpoint §2:** "Approved ≠ Currently authoritative" was **not new**. r1 already cited it as V3 L313–323; only its status and scope verification were new.
- **TD-001 Addendum 3 / checkpoint:** "the records as they stood cannot be reconstructed" is **imprecise**. Positions are recoverable if append-only held (unverifiable); what is missing is integrity evidence and a date→position map.
- **ALM vs checkpoint:** IND-1 is modelled as eligibility, while the checkpoint says it is authority.
- **ALM's symbols κ, ι and λ collide with the register** (RD 1/C2, RD 4, C11). Mapping: `TRACEABILITY…` §H.
- **`alm/DECISION-BRIEFS-ALM.md` uses the colliding symbols.** It is **not** re-issued here. Its mapping:
  - P-κ → **RD 1**;
  - P-λ → **T1-20** (nearest register items C1/C2);
  - P-A0 → **C10** / v1 case 12;
  - P-C1-wording → **C1**.

## 8. Policy decisions still reserved for the Principal

None of these blocks *modelling*; each can be carried as a parameter. They block a final r1.2 **text**.

| Register ID | Question | Status |
|---|---|---|
| **C1** | do records establish authority, or evidence it? | open. Evidence leans C1-b for validity and C1-a for effect. C1-a in its strict form ("recorded at the time of the act") is a live alternative that ALM failed to represent |
| **RD 1** | does an ending of authority carry over to decisions, **per kind**, including delegates (δ)? | open. The evidence is weaker than ALM claimed: ADR-001 is partly superseded; Stress Test 8 is per-type; v3.1 §10.1 "its authority lapsed" leans cascade |
| **T1-20** (with C1/C2) | after what horizon, if any, may a missing record mean "not authorized"? | open. Theorem 6 shows r1.1's answer ("never") is the only sound one without a completeness policy |
| **C10** / T1-21 | simultaneous conflicting acts | open. No source |
| C6, C12, C14 | uniqueness ("later prevails"), root change, interval conventions | open, as in the register |

*Not policy, so resolvable by research:* the theorem repairs, the act-order premise, IND-1 and IND-2 encoding, the holder/performer split, target-specific scopes, register alignment, and test coverage.

## 9. Recommended next research step

**First, a research-planning choice (no policy content), which I can make or you can direct.** Fix **ALM's scope**:

| Option | Scope | Effect |
|---|---|---|
| **(a) TD-001 core** | r1.1 clauses + recognition only | §1.14.1 stays governed by v1/M2; the 7 v1 cases that cannot be expressed are declared out of scope |
| (b) full §1.14.1 | everything above | ALM must absorb citations, suspension, C5, annulment, C13 and RD 6, i.e. become M3 |

**Recommendation: (a).** It is the smaller repair. It answers the TD-001 question directly, and it avoids a second competitor to M2.

**Then the minimum corrective revision** (`alm/review-1/REVIEW-ALM-1-DISPOSITIONS.md`, last section):
- explicit act order and claim identity;
- two recognition horizons;
- authorizing-grant-specific and parameterized carry-over;
- relational IND-2, and IND-1 as authority;
- holder/performer split;
- register IDs;
- a widened exhaustive alphabet with an independent oracle;
- conformance tests separated from policy tests.

**Exit criterion:** the 13 regressions pass, the restated theorems are proved, and a **second independent review** finds no BLOCKER/MAJOR.

**⇒ Authorization requested** for the scope choice (a or b) and for one ALM revision (call it ALM-r1). This is **not r1.2 of TD-001**. Nothing has been started. The pre-review draft that anticipated two of the repairs is parked, unused, at `alm/parked/alm_v2_DRAFT_not-authorized_20261018.py`.

**Readiness for an r1.2 proposal: NOT READY.** r1.2 should wait for ALM-r1 to pass review, because r1.1's recognition clause (#11) is what the evidence currently contradicts in formal form.

## 10. Artefact paths and reproducibility

**Root:** `docs/knowledgeos/knowlegeos_manual_theory_20261018/tmp/` (absolute: `/home/nab-raj.roshyara@dg-nexolution.de/roshyara/personal/nrna1/docs/knowledgeos/knowlegeos_manual_theory_20261018/tmp/`).

| Path (under `tmp/chapter_01/`) | Role | Status |
|---|---|---|
| `alm/ALM-FORMAL-MODEL.md` | reviewed baseline model | **baseline, NOT SOUND as stated**; unchanged |
| `alm/ALM-FORMAL-MODEL.v1.md`, `alm/alm_v1.py`, `alm/tests_alm_v1.py` | historical copies of the baseline | historical evidence |
| `alm/alm.py`, `alm/tests_alm.py` | working copy = baseline | working |
| `alm/tests_v1cases.py`, `alm/tests_regression_review1.py` | new evidence tests (no model change) | working |
| `alm/run-*.txt` | test outputs | evidence |
| `alm/review-1/*` | reviewer report (verbatim), reviewer script, reproduction, dispositions | evidence |
| `alm/MODEL-COMPARISON.md`, `alm/DECISION-BRIEFS-ALM.md`, `alm/ml_terms.py`, `alm/ml_terms-result.json` | pre-review analyses | historical. Comparison I3 and symbol use are superseded per §7 |
| `alm/parked/alm_v2_DRAFT_not-authorized_20261018.py` | pre-review draft | **parked, not authorized, not used** |
| `TRACEABILITY-TD001-V1-M2-ALM.md` | traceability matrix | working |
| `PHASE-1-CHECKPOINT-20261018.md`, `TD-001-RESEARCH-REPORT.md` | earlier records | historical. Corrections listed in §7, not edited in |

**Commands:**
```
cd docs/knowledgeos/knowlegeos_manual_theory_20261018/tmp/chapter_01/alm
python3 -B tests_alm.py                      # 32/32 (baseline self-checks; not proofs)
python3 -B tests_v1cases.py                  # v1 case coverage
python3 -B tests_regression_review1.py       # 0/13 pass = known defects
python3 -B review-1/ce_reproduced_on_tmp_baseline.py
python3 ml_terms.py > ml_terms-result.json   # discovery aid only
```

**Bounds of the exhaustive checks:**
- 8 templates (GRANT, REVOKE, PERFORM, ADOPT); actors {R, a, b}; 1 subject; τ ∈ {1, 2, 3};
- at most 4 acts for Thm 1 and Lemma 2, at most 3 for the rest; 7,458 histories;
- only κ = persist, ι = closed, λ = 0.

**Exhaustive within these bounds; not a proof.**

**Repository writes this round:**
- **in `tmp/`:** the files above;
- **outside `tmp/`:** only `06-theory/manuscript/chapter_01/TODO.md` (T1-20 and T1-21 added; T1-03 and T1-19 updated, within the TODO authorization).

No manuscript, frozen record, CONTEXT, session log or git operation was touched. **The sync gap is unchanged**: CONTEXT.md and the session log are still frozen.

**Remaining only in the external scratchpad** (not part of this book's research):
- earlier manuscript-review and verification work: `rev-ms-*`, `worker*`, `po1`, `m1b`, `ch1-model`, and others;
- the reviewer's original probe at `scratchpad/review_ce/ce.py`, copied into `alm/review-1/`.

The scratchpad originals are kept; nothing was deleted.
