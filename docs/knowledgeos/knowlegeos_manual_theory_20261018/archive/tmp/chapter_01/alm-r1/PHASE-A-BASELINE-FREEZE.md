# ALM-r1 Phase A: baseline freeze and inspection (2026-10-18)

**Authorization:** the Principal, 2026-10-18: "Proceed with ALM-r1: TD-001 core only". This is a bounded research revision. It is **not** TD-001 r1.2, **not** a manuscript change, and **not** a full §1.14.1 model.

## A1. Baseline verified

**Command:**
```
cd tmp/chapter_01/alm
sha256sum -c <(manifest block of BASELINE-MANIFEST.md)
```
**Result:** all 19 entries OK.

| Item | Value |
|---|---|
| repository HEAD | `1b45cc5033b133e711b4532aa38290382cd6cc81`. The `tmp/` folder is untracked, so hashes, not git, identify the baseline |
| Python | 3.13.2. No third-party packages are needed for the model; scikit-learn 1.9.0 is used only for discovery aids |
| `python3 -B tests_alm.py` | 32/32 (baseline self-checks; not proofs) |
| `python3 -B tests_regression_review1.py` | 0/13 (known defects) |
| `python3 -B tests_v1cases.py` | 7 agree · 1 partial · 1 diverges · 1 hidden policy · 7 not expressible |

**The baseline folder `tmp/chapter_01/alm/` is frozen.** ALM-r1 lives in `tmp/chapter_01/alm-r1/`, and no baseline file is edited.

## A2. Inspection of the 13 regression tests

**Question:** does each test encode the intended counterexample, rather than merely an interpretation of it?

| Test | Encodes | Verdict | Required re-encoding for r1 |
|---|---|---|---|
| REG_CE1 | cascade must use the **authorizing** grant | **behavioural, faithful** to C-1 | keep the expectation (not current) **under RD 1 = cascade**. Under persist the expectation is *current*, so the test is **policy-dependent** and must be split |
| REG_CE2 | an END before its ADOPT must not end the claim | behavioural, faithful to C-4 / r1.1 "later" | keep |
| REG_CE3 | δ must be a parameter | **placeholder (string), not behavioural** | re-encode: δ = independent → valid; δ = dependent → invalid |
| REG_CE4 | target-specific REVOKE scope | behavioural, faithful | keep |
| REG_CE5 | bar any/all must be a parameter | **placeholder** | re-encode as two behavioural tests, one per mode |
| REG_CE6 | a recorded root act is positive under r1.1 | behavioural. The expectation is **an interpretation** (F-1: r1.1 has no horizon on positives) | keep, labelled *r1.1-conformance*, and add the λ⁺ explanation |
| REG_CE7 | Theorem 3: recognition = validity under completeness | behavioural, faithful to P-1 | keep |
| REG_CE8 | the result must not depend on an unrecorded tie order | behavioural, but its **expectation (False = False) is ill-posed**: `[g,r,w]` and `[g,w,r]` are different *histories* once the order is a fact | re-encode: with the order **unrecorded**, recognition must be UNDETERMINED (both orders are possible); validity requires a recorded order or the C10 rule |
| REG_CE10 | ill-formed grant (`from < τ`) not accepted | behavioural, faithful to P-10 | keep |
| REG_CE12 | separate interval conventions for grants and claims | **placeholder** | re-encode behaviourally (C14 for grants; a separate claim convention) |
| REG_V1_CASE12 | same-moment ADOPT/END ordering (v1 case 12) | behavioural, faithful to v1 | keep. Note v1 uses **record position** as the tie-break; r1 uses a recorded act order |
| REG_IND1 | IND-1 is authority | **placeholder** | re-encode: ACCEPT by a producer of the work is NOT VALID (not merely ineligible) |
| REG_IND2 | IND-2 is relational | **placeholder** | re-encode: an implementer of work w is ineligible for independent verification of **w**, and eligible for w′ ≠ w |

**Finding:** 5 of the 13 regression tests were placeholders that compared labels, not behaviour. They could never "pass for the intended reason". They are re-encoded in `alm-r1/tests_r1_regression.py` from the **reviewer's text**, not from the r1 implementation. **A failing baseline test shows only that the baseline exhibits the defect.** It does not show that any repair is correct.

## A3. Scope boundary (from the Principal's decision)

**In scope (TD-001 core):**
- r1.1 clauses (1)–(3), recognition, and C1 as a parameter;
- endings: revocation, claim ending, expiry;
- delegation (δ), conditional grants (S0052), and eligibility IND-1/2/5 (IND-3 as a disclosure flag);
- record time, position, horizons, and the date→position question.

**Out of scope, kept visible** (the 7 v1 rows that cannot be expressed): citations (C2), suspension, supersession with a successor (C5), annulment with an effective time (RD 2 late values), consumption (C13), unverifiable endings (RD 6 υ), joint authority (C4). Also IND-4/6/7 as mechanisms, beyond grade labels.
