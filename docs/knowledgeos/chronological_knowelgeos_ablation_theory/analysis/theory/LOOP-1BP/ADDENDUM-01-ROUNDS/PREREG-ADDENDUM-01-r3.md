# Cross-check pre-registration, addendum 01 (r3; fixed before any return exists)

**Status:** PROPOSED. It takes effect only through a human act, under §18 of the control prompt. Until then, `PREREG.md` alone governs.

**Review history:** r1 and r2 both failed review (`ADDENDUM-01-ROUNDS/`).

**What makes r3 different:** the rules are executable. The normative text is
`compare_xcheck.py` (sha256 `350a8379…196e`). The prose below only summarizes it. Where the two differ, the script governs, and the difference is a defect to report.

**Human act already taken (2026-10-07): bundle option N.** The §6 conflict is resolved this way:
- `F-XCHECK-BUNDLE-02.tar` (sha256 `4dff1351…dd08`) replaces bundle-01 as the bundle handed over;
- V1B-SPEC and V1B-SUPP-SPEC lose 12 hint labels (listed in `BUNDLE-02/`);
- README, TASK and R5-SPEC are byte-identical to bundle-01;
- §6 is waived only for the R5 claim statements, which are inherent to the task (E1, E2 in r2 §1).

The Claude-side runs used bundle-01's V1B texts. The labels carry no definitional content.

## 1. Exposure finding (summary of r2 §1, with REVIEW-r2 additions)
- **Where outcomes are exposed:**
  - R5-SPEC asserts the theorems that the checks test (§§3–5).
  - §7.2 states every check as a claim.
  - Planted histories H_c, H_g⁺ and H_U5h coincide with Lem 3.5's examples.
  - W7 is K8.7's h′.
- **Still false after N:** README:2 ("no expected answers") and V1B-SPEC:1 ("NO predictions"), as far as R5-SPEC is concerned. They are kept byte-identical by the terms of option N, and that is disclosed here.

## 2. Tiers (`exposed_v1b`, `build_units`)
| Tier | Units | What they license |
|---|---|---|
| **EXPOSED** | every R5 truth value; R5 null witnesses; K12 rows other than the fine splits; V1b R0/U (all forms); R0/U5 and R-d/U (i)-S and (i)-O; `member(·)` at H_c, H_g⁺, W7, I7 (every reading); `O(W7)`, `O(I7)`; `iiiL(I7)` R0/U | consistency only |
| **NONLICENSING** | R5 composite witnesses. Every non-null R5 witness is a role dict, which TASK's single-history format cannot carry. They are compared by membership in the role set. | nothing |
| **BLIND** | `n_frame_histories`; K8.1's per-o difference set; K12.1 con-only and d-only rows; K12.2 A7 +act/+work/+ref and A9 con-only/d-only rows; every other V1b unit, including the six SUPP values | cross-family reproduction |

R5 contributes almost nothing BLIND. The independent evidence is essentially V1b, minus R0/U and the listed units.

## 3. Units, aliases and canonicalization
These are all in the script:
- the 34 R5 keys;
- 12 readings × 2 frames × 5 forms;
- counterexamples, which are units only where both sides report one;
- the Def P0.3 fields `member(R_B)`, `member(H-D)`, `member(H-B)`, `iS`, `K4b`, `iiO_c`, `iiiL` at every named history the Claude side reports;
- the extras `exists(H-D+,≈σf H_d2)`, `O(W7)`, `O(I7)`;
- the 6 SUPP values;
- the K12 group universes.

The required tuples are those of V1B-SPEC §C. Other tuples are compared if the checker reports them, and are not a unit if it does not.

Specific rules:
- **iiiL is symmetric:** true vs. {false, null, absent} = "no certified h′".
- **Malformed values or files** are INCOMPLETE and are never repaired.
- **A flat K8.1 list** is compared as a union and flagged AMBIGUOUS.
- **Adapters:** a checker-format adapter added after the return may map syntax only. It is committed separately and reviewed.

## 4. Classes (`classify`), exactly one per unit
- **INCOMPLETE:** missing or malformed; or a required unit that is absent and not declared UNTESTABLE.
- **UNTESTABLE:** named in NOTES. The value is still compared, and the result is recorded as a flag.
- **NOT_APPLICABLE:** both sides null.
- **DISAGREEMENT**
- **AGREEMENT**

AMBIGUOUS is a **flag**, and flagged units are listed under that label. This deviates from the list in §7 of the control prompt, so that a disagreement cannot be parked outside the gate. It is disclosed for the human act.

The UNTESTABLE and AMBIGUOUS inputs are JSON prefix lists built after the seal: from NOTES, and from the reviewer for AMBIGUOUS. Both are reviewed.

## 5. Licence and gate
- **Licence:** "cross-family reproduction of the finite results, BLIND tier only" requires every unit to be AGREEMENT or NOT_APPLICABLE, with no AMBIGUOUS flag (P:12, kept).
- **Gate:** every DISAGREEMENT is classified (a)–(d) by a reviewer with both programs. It is (a) only on a concrete history where the checker's program departs from the literal spec. **Any non-(a), including (c), goes to the human.**
- **Known (c) candidate, pre-registered:** `V1b/R-d/U/(i)-S/holds` (F-1, the wording of Lem 3.7).

## 6. Seal (Step A) and reviewer
- **Seal:**
  1. Hash every file, check that the JSON parses, and check that all six required files are present.
  2. Commit.
  3. Only then extract the metadata from PROVENANCE and NOTES.
- **Reviewer:** a fresh, read-only Claude subagent (Claude family, disclosed). It answers the eight questions of §7 of the control prompt for each DISAGREEMENT and each AMBIGUOUS flag.

## 7. Validation of the comparator (before any return)
`selftest_compare_xcheck.py` (sha256 `a09183a0…dfb`): **PASS.**
- **P0 (positive control):** Claude's own results re-encoded in TASK format, with different syntax and alias names. Result: 2,256 units; 0 DISAGREEMENT, 0 INCOMPLETE; the licence holds.
- **N1–N6 (planted defects), each caught as specified:**
  - N1: a flipped BLIND holds → DISAGREEMENT;
  - N2: a missing required field → INCOMPLETE;
  - N3: the same field declared in NOTES → UNTESTABLE;
  - N4: a wrong K12.2 sub-kind → DISAGREEMENT;
  - N5: a malformed history → INCOMPLETE;
  - N6a: a missing file → all INCOMPLETE;
  - N6b: an AMBIGUOUS flag → no licence.
- **Limit:** P0 shares the Claude side's own encoding choices, so it cannot show that the parser accepts every legitimate syntax a checker might use. That is what the syntax-only adapter rule (§3) is for.

## 8. Changes relative to `PREREG.md`
**Narrowings:**
- the licence is limited to the BLIND tier;
- any AMBIGUOUS flag blocks the licence;
- (c) also goes to the gate;
- NOT_REPORTED is split into INCOMPLETE and UNTESTABLE.

**Loosenings, disclosed:**
- NOT_APPLICABLE (both sides null) counts as licence-compatible;
- R5 composite witnesses are matched by membership in the role set, but they are NONLICENSING;
- Claude-only extra fields and the V1B-SPEC §D.2 images and diffs are not units;
- the bundle changes from bundle-01 to bundle-02 (human act, option N);
- iiiL false, null and absent are merged.
