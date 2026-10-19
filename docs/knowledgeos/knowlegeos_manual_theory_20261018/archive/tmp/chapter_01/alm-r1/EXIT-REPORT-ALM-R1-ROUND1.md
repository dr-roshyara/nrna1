# ALM-r1 exit report, round 1 (2026-10-18)

## Verdict: **FAIL** against the Phase H exit criteria

ALM-r1 remains a research candidate. It is not validated, not canonical, and no TD-001 r1.2 exists.

| # | Exit criterion (Phase H) | Status | Evidence |
|---|---|---|---|
| 1 | every in-scope review-1 finding has a traceable disposition | **met in form, not in substance**: 8 "repaired" dispositions are **partial** (P-2, P-3, P-5, C-5, C-6, F-1, F-2, F-3) | `review-2/REVIEW-R2-REPORT-verbatim.md`, review-1 status table |
| 2 | the 13 regressions pass for the intended reasons | **met** (28/28 scenarios incl. 13 regressions). But R2-01 shows `REG_CE8`'s repair is incomplete in a case the test does not cover | `run-scenarios.txt`; probe N1 |
| 3 | theorems restated and proved, or marked unproved | **not met**: Th-3 false as stated (R2-04); Th-5 characterization false (R2-05); S-8/Th-8 rest on an unsound K(d) (R2-06); Pr-9 inherits Th-3 | review-2 |
| 4 | the independent oracle and bounded checks support the claims | **not met**: the oracle is not independent (R2-09). A divergence was found (C1 = a with C6). The Th-5 bounded check was circular | review-2 |
| 5 | TD-001 cases reconciled; divergences explained | **partly**: v1 case 12 explained; r1.1 fidelity overstated (R2-03) | `TRACEABILITY-R1.md` (unreviewed draft) |
| 6 | no reserved policy silently embedded | **not met**: C7 π (R2-10), RD 6 υ (R2-03), RD 1 per ending kind (R2-11), C6 conflict = same subject (R2-16), BAR root exemption (R2-13), C11 (R2-12) | review-2 |
| 7 | second review finds no BLOCKER/MAJOR | **not met**: 0 BLOCKER, **12 MAJOR** (13 with R2-14 raised) | review-2 |
| 8 | reproduction, hashes, limits and TODOs recorded | **met** | `MANIFEST-R1.md`, review-2 records |

## 1. Executive summary (business language)

The repaired model reliably answers "**was this decision validly made by someone with authority at the time?**". Two independent checks confirm this, and its proofs survived review.

It does **not** yet reliably answer "**what can we conclude from our records?**". When records are incomplete or partly unknown, it sometimes says "authorized" where it should say "cannot determine". Several of my claims about that part were wrong or overstated. The tests I wrote to check them shared my blind spots: this is the 7th time an author's own encoding hid a defect. The reviewer found all of this and I have reproduced every case.

**The review also improved the theory itself.** Whether TD-001's "cannot determine" rule applies to a *cancellation* whose own authority is unknown is not separate from C1. If the organisation reads TD-001 uniformly:
- under "acts establish authority" (C1-b), such a cancellation makes the decision "cannot determine";
- under "only recorded acts count" (C1-a), the cancellation simply does not count.

## 2. Findings and dispositions (summary; the records are verbatim)

| | Count | Disposition |
|---|---|---|
| MAJOR | 12 (+ R2-14 raised) | all accepted by the author. The reviewer: 2 ACCEPTED, 10 ACCEPTED-WITH-CONDITION (the conditions are binding on round 2) |
| MINOR | 8 | all accepted |
| NOTE | 6 | all accepted |
| Disagreements | **0**. One author claim (R2-03 "structural fact") was **corrected by the reviewer** and the correction is accepted | `review-2/REVIEW-R2-DISPOSITIONS.md` (addendum) |

## 3. Proof status after review 2

| Claim | Status |
|---|---|
| Th-1 existence and uniqueness of validity; Th-2 locality | **proved**; reviewer-verified; 6,000-trial differential with 0 disagreements |
| Th-4 non-monotone recognition | **proved** (construction reproduces). The RESULTS wording about the minimal example is wrong (R2-18) |
| "never 'not authorized'" under λ⁻ = ∞ | **proved** |
| Pr-10 C1 options incomparable | **proved** (examples = proofs of non-implication). One example is mislabelled (R2-19) |
| Th-7 determinism and termination | proved mathematically. The code is not total (R2-17) |
| Th-3, Pr-9 | **false as stated**; re-proof needed with premises over records (R2-04) |
| Th-5 | the existence part stands; **the characterization is false**; the corollary must be re-derived (R2-05) |
| Th-6 | "if" holds; "only if" false (R2-22) |
| Th-8 / S-8 | conditional on AX-AppendOnly. **K(d) is unsound** (R2-06); with lower-bound witnesses only, dated questions are almost always "cannot determine" |
| r1.1 = (λ⁺ = 0, λ⁻ = ∞) | **withdrawn as a deduction.** It is a reading that also needs υ (which C1 settles under a uniform reading) |

**Tests (reproducible, `MANIFEST-R1.md`):**
- scenarios 28/28;
- the exhaustive run, with counts reproduced exactly by the reviewer;
- these are bounded evidence, and the review showed **the bounds excluded ⊥ fields and most act kinds in the completion search.**

## 4. Traceability gaps and unresolved assumptions

**Gaps:**
- the R-layer rows of `TRACEABILITY-R1.md` (TD-001 #10, #11) are **contradicted** by review 2;
- `TRACEABILITY-R1.md`, `DECISION-BRIEFS-R1.md` and `ML-DISCOVERY-NOTE.md` are **unreviewed drafts** written after the manifest. They enter round 2's manifest.

**Assumptions with no source:**
- the object-level wildcard (now also inconsistent: R2-15);
- claim identity;
- the END collapse;
- unstated period = open-ended (**actually policy C7**: R2-10);
- target precedence;
- single-grant coverage;
- AX-AppendOnly;
- AX-Truth.

**Evidence gap:** governance-side revocation has never been observed (E-8).

## 5. Decisions for the Principal

### 5a. Needed now (it blocks round 2's specification)

**Scope: may round 2 bring υ (RD 6, "does an unverifiable cancellation count?") into the TD-001 core, as a parameter?**

- **Why it is needed:** the reviewer showed that TD-001's recognition clause cannot be modelled faithfully without it. Phase A excluded RD 6, so changing that is your call.
- **Options:**
  - **(A) Include υ as a parameter** (recommended). Round 2 models both values and shows that under a uniform reading C1 determines it. **Nothing is decided.** Cost: the model shows more "cannot determine" answers, and with υ = undetermined, any unverifiable cancellation can freeze a decision (v1 safeguard 6).
  - **(B) Keep RD 6 out of scope.** Round 2 must then state that the recognition clause is modelled only for records with **no** recorded endings of unestablished authority, a narrower result.

### 5b. Still reserved (briefs in `DECISION-BRIEFS-R1.md`; drafts, unreviewed)

C1 · RD 1 (per claim kind **and per ending kind**, including δ) · T1-20 (λ⁻) · C10 · C6 · C7 (π) · C12 · C14.

Candidate parameters, not minted: claim-period convention, BAR (with root exemption as an alternative), BASIS.

## 6. Recommended next step

**Round 2 of ALM-r1** (your instruction: "Repair it, preserve the evidence, and review it again"), once 5a is answered.

**Order:**
1. Specification first:
   - world semantics for every ⊥ field (R2-02);
   - premises over records (R2-04);
   - Auth for ill-formed acts (R2-07);
   - the C1 = a evaluation instant as **two stated variants** (R2-08);
   - π and per-ending-kind RD 1 (R2-10, R2-11);
   - K(d) (R2-06).
2. **Oracle written by a separate agent from the specification alone** (R2-09).
3. Implementation, then the differential tests. The run report states how many answers each repair turns into "cannot determine".
4. Re-proofs: Th-3, Pr-9, Th-5 corollary, Th-8.
5. A new manifest including the three drafts.
6. Review 3, by a fresh reviewer.

**Expected size:** comparable to round 1.

**Risk:** the honest result may be that, on today's records, the recognition layer mostly answers "cannot determine". That would itself be a valid research result about what the records can support.

## 7. Artefacts

All under `tmp/chapter_01/alm-r1/`:
- `ALM-R1-SPEC.md`, `alm_r1.py`, `oracle_r1.py`, tests and runs: the frozen subject (`MANIFEST-R1.md`);
- `review-2/`: the verbatim report, probes, reproduction, author responses, and the reviewer's final dispositions (verbatim);
- the baseline in `../alm/`: untouched.

**Repository writes outside `tmp/`:** `06-theory/manuscript/chapter_01/TODO.md` (T1-03 status line only). No manuscript, frozen record, CONTEXT or session log was changed, and there was no git activity.
