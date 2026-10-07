# Review of PREREG-ADDENDUM-01 r1 (independent subagent, read-only; Claude family)

**Verdict: FAIL.** Every defect is fixable before handover.

**Reviewer disclosure:** the reviewer read the sealed Claude values in order to audit exposure. This review must never be routed to the checker.

## Findings
1. **Exposure audit (blocking).** "V1b is exposure-free" is false.
   - R5-SPEC Cor 4.1b and Lem 4.2 state R0/U (i), (i′) and (iii) (and (ii) when P ≠ V), so R0/U, all five forms, are exposed.
   - K8.5 states the failure of R0/U5 (i)-O, including the O-value.
   - Lem 3.7 exposes R-d/U (i)-O.
   - K8.7 exposes the W7 fields, O(I7) and iiiL(I7) under R0/U.
   - V1B-SPEC labels hint at outcomes: :69 "(A5 broken)", :71 "O₁ sees σ", :73 "the W = ∅ break" and "CM-A3a instance", :83 "CM-A7(b)-form witness".
   - SUPP:1 reveals an earlier pipeline, a mild hint.
   - Exposure is not limited to §7.2. §§3–5 state the theorems the checks test: Lem 3.3/K2, Prop 3.4/K3, Lem 3.5/K1, Lem 3.6/K1, Lem 3.7/K4b, Cor 4.1b/K4–K7, Lem 5.2/K10.1, Prop 5.4(a)/K10.2, Prop 5.4(b)/K10.4's H-B violator.
   - Units wrongly listed BLIND:
     - most of K12.1/K12.2, via the ¬A labels and Lem 1.12 / Lem 4.0a;
     - null counterexamples implied by exposed truth values;
     - the K10.4 H-B violator;
     - the K1 witnesses;
     - the K8.5 values and the K8.3 region;
     - the stated values of K9.x and K10.7.
   - The count is 34 checks (32 booleans + 2 sets), not 33.
2. **Not purely narrowing (must fix).**
   - The per-unit licence relaxes PREREG's all-or-nothing rule (P:12).
   - The named-field unit set is not pre-fixed.
   - "Substantive AMBIGUOUS" is an undefined discretionary gate.
3. **Self-adoption (blocking).** §18 has no carve-out for changes made before the return. The addendum would also self-waive §6. A human act is needed.
4. **Classes (blocking).**
   - AGREEMENT and AMBIGUOUS overlap.
   - DISAGREEMENT parked as AMBIGUOUS could bypass the gate.
   - UNTESTABLE and INCOMPLETE have no precedence between them.
   - There are no canonicalization rules: "TRUE" vs true, null vs false, composite witness dicts vs a single history, extra units, units with no Claude value.
5. **Bundle not re-issued (blocking as process).**
   - The decision is defensible only in part: the cost was underestimated, because exposure sits in §§3–5, and the cost of asymmetry was overstated.
   - A cheap middle option exists: neutralize the V1B labels.
   - Only the human can waive §6.
6. **Operator-guide edit: OK.** Nothing reaches the checker. Minor: the completeness check omits TRANSCRIPT.txt and PROVENANCE.txt.
7. **Other.**
   - The addendum is uncommitted, so "fixed before return" cannot be shown.
   - The seal order contradicts itself (it reads NOTES while "before any file is read").
   - "The stricter rule applies" is not operational.
   - The reviewer is not specified.

## Required changes
1. Retract "V1b exposure-free" and extend EXPOSED.
2. Extend the R5 EXPOSED list and cite §§3–5.
3. Use 34 checks; enumerate by key; pre-fix the V1b tuple set, the canonicalization and the extra-unit rule.
4. Exclusive classes, precedence INCOMPLETE > UNTESTABLE > DISAGREEMENT > AMBIGUOUS-flag > AGREEMENT; every non-(a) DISAGREEMENT goes to the gate.
5. Keep the all-or-nothing rule.
6. Seal: hash first, then read metadata; include TRANSCRIPT and PROVENANCE.
7. Name the reviewer and its family.
8. Commit before handover.
9. Obtain a human act that adopts the addendum and decides §6 (waive, or issue bundle-02).

## Reconciliation (main analyst)
All nine changes are accepted. I independently confirmed:
- Cor 4.1b at R5-SPEC:245-247;
- Prop 5.4(b) at :279;
- 34 keys in `LOOP-1BL/RETURN/RESULTS.json`.

There is no disagreement left to preserve. r2 implements changes 1–8. Change 9 is the human gate.
