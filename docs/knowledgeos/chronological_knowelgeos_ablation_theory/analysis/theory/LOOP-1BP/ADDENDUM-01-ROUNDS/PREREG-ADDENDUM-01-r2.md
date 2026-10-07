# Cross-check pre-registration, addendum 01 (r2; fixed before any return exists)

**Status: PROPOSED — r2 FAILED its independent review** (`ADDENDUM-01-ROUNDS/REVIEW-r2.md`). It is **not in force**. Until a human act adopts a passing revision, `PREREG.md` alone governs. r3 waits for the human's §8 decision.

**Written:** 2026-10-07. `~/F-XCHECK-RETURN-01/` does not exist, and the bundle has not been handed over.
**Trigger:** the human's control prompt "R6 Independence Cross-Check → Validation Decision Gate":
- §6: no expected outcomes;
- §7: five result classes, a seal record and eight reviewer questions.

**Review history:**
- r1 was reviewed with verdict FAIL (`ADDENDUM-01-ROUNDS/REVIEW-r1.md`).
- r2 implements that review's required changes 1–8.

**Unchanged from `PREREG.md`:**
- the sealed Claude result files it names;
- the classification (a)–(d);
- "(b) or (d) → human gate";
- the all-or-nothing licence (P:12);
- "Not claimed".

**Precedence:** where a rule here and a rule in `PREREG.md` govern the same thing, the rule here applies. Every such rule is a narrowing, as listed in §9.

## 1. Exposure finding: the bundle states outcomes
The checker receives R5-SPEC, which is a stripped theory draft. Its statements assert results that the checks test. Exposure therefore has three sources.

**(E1) Theorem statements, R5-SPEC §§3–5**

| Statement | Check it exposes |
|---|---|
| Lem 3.3 | K2 |
| Prop 3.4 | K3 |
| Lem 3.5 (explicit histories) | K1 witnesses |
| Lem 3.6 | K1 |
| Lem 3.7 | K4b; R-d/U (i)-O |
| Cor 4.1b and Lem 4.2 | K4–K7; R0/U, all five V1b forms |
| Lem 5.2 | K10.1 |
| Prop 5.4(a) | K10.2 |
| Prop 5.4(b) | the form of K10.4's H-B violator |
| Lem 1.12, Lem 4.0a, and the "¬Ax" labels with the pairwise-independence statement (:202) | most of K12.1/K12.2 |

**(E2) Assertion-form checks with supplied detail, R5-SPEC §7.2**
- all 32 boolean checks are stated as claims;
- detail is supplied in:
  - K8.2: the value and the parenthetical;
  - K8.3: the region;
  - K8.4: "(iii) fails at I7 …";
  - K8.5: the O-values, which also expose R0/U5 (i)-O;
  - K8.6;
  - K8.7: h′ = W7, the I7 verdicts in U and U₇, and O(W7) = O(I7);
  - K9.1–K9.8: the separating values and witnesses;
  - K10.5: the example;
  - K10.7.

**(E3) Labels in V1B-SPEC and V1B-SUPP-SPEC**
- :69 "(A5 broken)";
- :71 "O₁ sees σ";
- :73 "the W = ∅ break" and "CM-A3a instance in U";
- :83 "the CM-A7(b)-form witness";
- SUPP:1, the extraction-error header.

Labels carry no definitional content.

## 2. Unit set (fixed now, enumerated from the sealed key sets)

**R5 (`LOOP-1BL/RETURN/RESULTS.json`).** Two kinds of unit:
- `n_frame_histories`;
- for each of the 34 keys (K1, K2, K3, K4, K4b, K5, K6, K7, K8.1–K8.7, K9.1–K9.8, K10.1–K10.7, K11.1, K11.2, K12.1, K12.2), two units:
  - `result`: a boolean for 32 keys, and the reported set for K12.1 and K12.2;
  - `witness`: the checker's `least_witness_or_counterexample`.

Two further units:
- K8.1's `difference_sigma_values`;
- the K12.1 and K12.2 sets, compared row by row: one unit per (observation or frame, change group).

**V1b (`LOOP-1BN/V1B-RETURN/V1B-RESULTS.json` + `SUPP-RESULTS.json`).** For each of the 12 readings × frames {U, U5} × forms {(i)-S, (i)-O, (ii)-S, (ii)-O, (iii)-L}, two units:
- `holds`;
- `least_counterexample`.

**Named-field units.**
- **Fields:** only the Def P0.3 fields, i.e. `member(R_B)`, `member(H-D)`, `member(H-B)`, `iS`, `K4b`, `iiO_c`, `iiiL`, `O`.
- **Extras:** `exists(H-D+, ≈σf H_d2)`, `O(W7)`, `O(I7)`, and the five SUPP fields.
- **Required tuples** (reading, frame, history), each in both frames:
  - H_c under R-c;
  - H_d and H_d2 under R-d;
  - H_g and H_g′ under R-g;
  - H_g under R-j and R-g⁺;
  - I7 and W7 under R-h and R0;
  - H_i and H_∅ under R-i and R-i′;
  - H_j under R-j;
  - H_g⁺ under R-g⁺;
  - the SUPP tuples, as listed in V1B-SUPP-SPEC.
- **Optional tuples:** any other (reading, frame, history) that both sides report. These are compared and are not counted as INCOMPLETE if missing.
- **Not units:** Claude-only extra fields (`designated_*`, `in_frame`, `K4b_with_reading_classes`, `iiiL_exists_ignoring_scope`, `iiiL_least_witness`, `iiiL_in_scope`, the `exists(H-D^R …)` variant, `class_sizes`), and the images and diffs of V1B-SPEC §D.2, which TASK.md does not request. If the checker reports units outside this set, they are listed as **EXTRA** and not scored.

## 3. Canonicalization (fixed now)
- `"TRUE"`/`"FALSE"` (in any case) ≡ `true`/`false`.
- A history is compared on all nine keys (sigma, a1, a2, Cap, w1, Ref, con, r, c), with Cap and Ref as sets, and act and work fields per R5-SPEC §7.1. A key the spec makes irrelevant (e.g. an absent slot's attributes) is ignored.
- **R5 witness against a composite Claude dict:**
  - AGREEMENT if the checker's history equals one of Claude's non-null sub-witnesses for that key, or if both are null.
  - Otherwise the values differ and the result is DISAGREEMENT (§4).
- **`iiiL` out of scope:** if Claude reports `null` because the history is outside the (iii)-L scope, and the checker reports `false` or omits the field, the unit is **NOT APPLICABLE** and is excluded. If the checker reports `true` there, the result is DISAGREEMENT.
- A V1b `least_counterexample` is compared only where both sides report `holds = false`. Otherwise it is implied by `holds`, and is not a separate unit.

## 4. Classes: exactly one per unit, with precedence in this order
1. **INCOMPLETE:**
   - the containing file is missing, malformed or truncated (never repaired); or
   - a required unit is absent and NOTES says nothing specific about it.
2. **UNTESTABLE:** NOTES names this unit, or a group that contains it (a check key, reading, frame or history), as not computed. Any reported value for it is ignored. A general, unnamed "could not compute" statement does not qualify, and the result is INCOMPLETE.
3. **DISAGREEMENT:** both values are present and differ after §3. Each is classified (a)–(d) per `PREREG.md`.
   - It is (a) only if the reviewer, running both programs, shows a concrete history on which the checker's program departs from the literal spec text.
   - **Every DISAGREEMENT that is not (a) goes to the human gate**, including (c).
4. **AGREEMENT:** both values are present and equal after §3.

**AMBIGUOUS is a flag, not a class.** It is set on a unit when NOTES records a reading choice touching that unit, or when the reviewer finds that the spec admits more than one reading for it. A flagged AGREEMENT is not counted toward reproduction. A flagged DISAGREEMENT keeps its class and its route to the gate.

## 5. Exposure tiers and what may be claimed
- **EXPOSED units:**
  - every R5 `result`;
  - every R5 `witness` that is null for a universal check (implied by its result);
  - the K1 witnesses;
  - the K10.4 H-B violator;
  - K12.1/K12.2 rows, except the BLIND ones listed below;
  - the units of §1 (E2);
  - V1b R0/U, all forms;
  - V1b R0/U5 (i)-O;
  - V1b R-d/U (i)-O;
  - `member(H-B, W7)` under R0, in both frames;
  - `O(W7)`, `O(I7)`;
  - `iiiL(I7)` under R0/U.
  - **Under bundle-01 only (§8, option W):** all R-c units at H_c, all (i)-O units in frame U5, and the W7 and I7 fields under R-h. These are the units the E3 labels hint at.
- **BLIND units:** every other unit, notably:
  - `n_frame_histories`;
  - non-null R5 witnesses not listed above;
  - K8.1's difference set (weakly hinted by Lem 3.5 and the K8.2 parenthetical; footnoted);
  - the K12.2 A7 sub-kinds (+act/+work/+ref) and the con-only/d-only split in K12.1/K12.2;
  - all remaining V1b units.
- **Licence:**
  - "Cross-family reproduction" (PREREG P:12) is claimed only if **every** unit is AGREEMENT or NOT APPLICABLE, with no INCOMPLETE, no UNTESTABLE and no AMBIGUOUS flag. Even then it is claimed **only for the BLIND tier**.
  - EXPOSED agreement is reported as "consistent across model families, claim visible to the checker".
  - A DISAGREEMENT on an EXPOSED unit is not discounted.
  - Unit-level agreements may be reported as unit-level facts. They never upgrade R5 or V1b on their own.

## 6. Seal record (Step A)
1. **Before reading any content:**
   - list every file in `~/F-XCHECK-RETURN-01/` with its size and sha256;
   - check whether each `.json` parses, recording yes or no only;
   - check completeness: R5-RESULTS.json, V1B-RESULTS.json, NOTES.md, ≥ 1 program file, TRANSCRIPT.txt, PROVENANCE.txt.

   Commit this seal.
2. **Then** extract the metadata into the seal's second section, without editing any file:
   - PROVENANCE.txt verbatim (verifier, model version, date, bundle sha checked, upload form, environment, files returned, session, memory, web and retries);
   - the language, runtime and ambiguities stated in NOTES.

## 7. Comparison and review
- The comparison is done by a script written to §§2–4, committed before it runs.
- **The reviewer** is a fresh, read-only Claude subagent. It is **Claude family**: a disclosed limitation, since no non-Claude reviewer is available without the human.
- The reviewer gets both programs, both result sets and the bundle, and runs both programs on every DISAGREEMENT. It answers, per DISAGREEMENT and per AMBIGUOUS flag:
  1. Is it real (reproduced from both programs)?
  2. Is it caused by implementation?
  3. Is it caused by interpretation?
  4. Is it caused by an underspecified definition?
  5. Does it affect an R4 statement?
  6. Does it affect only a finite check?
  7. Does it affect an empirical conclusion (R1/R3)?
  8. Does it require reopening R4?
- **Stop:** any DISAGREEMENT that is not (a) goes to the human, with:
  - the exact disagreement;
  - a minimal reproducing history;
  - the affected statement and check;
  - the causes;
  - alternatives for resolving it.

  Nothing is repaired automatically. Otherwise, report and stop. V2, E-A5, V5, V6 and R7 are not started.

## 8. Human decision required: the §6 conflict
§6 forbids expected outcomes in the handover. Sources E1 and E2 are inherent to R5's task, which is to verify stated claims. E3 is not inherent.
- **Option N (recommended): issue bundle-02.**
  - V1B-SPEC and V1B-SUPP-SPEC have the E3 labels removed. The reviewed edit list is to be prepared after this act.
  - README, TASK and R5-SPEC stay byte-identical.
  - §6 is waived only for E1 and E2, which are covered by the EXPOSED tier.
- **Option W: hand over bundle-01 unchanged.** §6 is waived for E1, E2 and E3. The extra EXPOSED units of §5 apply.
- **Option F: issue a fully neutral bundle-02.** R5-SPEC is rewritten without theorem assertions, the checks are restated as "determine whether", and a reviewed semantic-equivalence argument is required. This means more blind units, and risk of semantic drift against the spec the Claude side ran on.

## 9. Narrowings relative to `PREREG.md`
- The licence is restricted to the BLIND tier, and any AMBIGUOUS flag blocks it.
- NOT_REPORTED is split into INCOMPLETE and UNTESTABLE; neither counts as agreement.
- (c) also goes to the gate.
- The unit set, canonicalization and seal order are fixed in advance.
