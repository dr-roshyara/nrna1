# Cross-check pre-registration, addendum 01 (r5; fixed before any return exists)

**Status:** PROPOSED. It takes effect only through a human act (§18 of the control prompt). Until then `PREREG.md` alone governs.

**Review history:** r1, r2, r3 and r4 FAILED review (`ADDENDUM-01-ROUNDS/`). r5 implements REVIEW-r4 in full.

**Normative text:** `compare_xcheck.py` (sha256 `a5d900f9…999bf`), validated by `selftest_compare_xcheck.py` (sha256 `2feac34e…f9d`). This prose only summarizes the script. Where the two differ, the script governs, and the difference is a defect to report.

**Human act (2026-10-07): bundle option N.**
- **Bundle:** `F-XCHECK-BUNDLE-02.tar` (sha256 `4dff1351…dd08`) is the bundle handed over.
- **Edits:** 12 label removals, recorded exactly in `BUNDLE-02/EDIT-LIST.md`. README, TASK and R5-SPEC are byte-identical to bundle-01.
- **Waiver:** §6 is waived only for the R5 claim statements that are built into the task.
- **Disclosed:** README:2 ("no expected answers") and V1B-SPEC:1 ("NO predictions") are inaccurate where they touch R5-SPEC, and they are kept unchanged by the terms of option N.

## 1. Exposure
The checker can see outcomes in three places:
- R5-SPEC §§3–5 assert the theorems that the checks test;
- §7.2 states each check as a claim;
- several planted histories coincide with stated examples: H_c, H_g⁺ and H_U5h are Lem 3.5's examples, and W7 is K8.7's h′.

The details are in r2 §1 and REVIEW-r2/r3.

## 2. Tiers (`exposed_v1b`, `build_units`)
| Tier | Units | Licenses |
|---|---|---|
| **EXPOSED** | every R5 truth value; R5 null witnesses; K12 rows other than the fine splits, and the K12.2 coarse `A7-any`/`A9-any` units; V1b R0/U (all forms); R0/U5 and R-d/U (i)-S and (i)-O; `member(·)` at H_c, H_g⁺, W7, I7 (every reading); `iS` at H_g⁺, W7, I7 under R0/U; `iiO_c` at every named history under R0/U (it depends on H only through O(H), and every planted history has O = (0, c1)); `K4b` at H_g⁺, W7, I7 in U (every reading); `O(W7)`, `O(I7)`; `iiiL(I7)` R0/U | consistency only |
| **NONLICENSING** | R5 composite witnesses (role dicts, which TASK's single-history format cannot carry), matched by membership in the role set | nothing |
| **BLIND** | `n_frame_histories`; K8.1's per-o difference set; the K12.1 con-only/d-only rows; the K12.2 A7 +act/+work/+ref and A9 con-only/d-only rows; every other V1b unit, including the six SUPP values | cross-family reproduction |

**Units are not independent observations.** For example, `iiO_c` is one value per (reading, frame, O), and `K4b` does not depend on the reading. Unit counts are bookkeeping, not evidence weights.

## 3. Fail-closed parsing
- **Keys** pass only through closed alias tables: readings, frames, forms, histories, fields, the R5 check keys (exact), and K12 rows and groups.
  - `norm()` is closed: a character outside an explicit whitelist raises. It is never dropped.
  - **Collisions:** a duplicate JSON key makes the whole file MALFORMED. Two raw keys that map to one canonical id are recorded, and the id becomes MALFORMED.
  - **Extra keys:** the top-level R5 keys, each check's extra keys, form-body keys and named-history fields are checked against whitelists. Whitelisted keys are ignored: `_meta`, `detail`, `notes`, `images`, `diffs`, `class_sizes`, `n_counterexamples`, `scope_size`.
  - **Anything unknown** is recorded under `unrecognized_checker_keys` and **blocks the licence**. It can only be mapped by a reviewed, separately committed, syntax-only adapter.
- **Values** must match strict grammars:
  - booleans: true/false or "TRUE"/"FALSE";
  - n: an integer;
  - O: `{r, c}`, `[r, c]`, `(r=…,c=…)` or `(r, c)`, where r and c may be keyed by ρ1;
  - histories: exactly nine coordinates. Slot kinds come from a whitelist; an iss slot needs d, v, prep and f; an adp slot needs d, with an optional obj. Cap, Ref and con are whitelisted, and w1 is `{t}`, t or `t=…`;
  - K12: exact labels. Generic "A7"/"A9" labels are MALFORMED.
- **Anything else** is MALFORMED, which gives INCOMPLETE.
- **Claude-side values** are parsed separately, with Claude's own annotation keys (`tuple_R1`, `h_v_exists`, `h_c_exists`) stripped explicitly. A failure there crashes the comparator; it is never scored.
- **K12.2 coarse labels.** The spec names "A7 restricted to …" and "A9 (con-only or d-only …)" as single properties, so a literal checker may report only "A7" or "A9".
  - In that case the row's fine-split units are NOT_A_UNIT, flagged `coarse-label`, and listed under `coarse_label_rows_not_scored`.
  - The coarse units `A7-any` and `A9-any` (EXPOSED) are scored.

## 4. Units
- **R5:** the 34 keys; K12 compared row × group.
- **V1b:** 12 readings × 2 frames × 5 forms.
- **Counterexamples:** a required unit wherever both sides report holds = false. If the checker omits one there, it is INCOMPLETE.
- **Named fields:** the Def P0.3 fields at every named history the Claude side reports.
  - **Required tuples:** those of V1B-SPEC §C. The common controls C-U5 and C-O1 define a frame and an observation, not a named history, so they produce no named units. The images they would need (V1B-SPEC §D.2) are not part of TASK's output.
- **Extras:** `exists(H-D+, ≈σf H_d2)`, `O(W7)` and `O(I7)`, compared at every (reading, frame) and required with their tuple.
- **SUPP:** the six SUPP values, all required.
- **iiiL:** three-valued (true, false, null = out of scope) and compared exactly. **Pre-declared exception:** the spec types iiiL as {true, false}. If Claude reports null with `iiiL_in_scope = false` and the checker reports `false`, the unit is AGREEMENT only if that tuple's `member(H-D)` and `member(R_B)` units are both AGREEMENT. Otherwise it is DISAGREEMENT. Null vs false in the other direction is always DISAGREEMENT.
- **Not scored, listed as `NOT_A_UNIT`:** a non-required unit the checker does not report, whatever Claude has; counterexamples where either side has holds ≠ false; coarse-label fine splits.

## 5. Classes (`classify`), exactly one per unit
| Class | When |
|---|---|
| INCOMPLETE | missing or malformed file or value, or a required unit absent and not UNTESTABLE |
| UNTESTABLE | the unit is named in NOTES (prefix match only at a `/` boundary); its value is still compared and recorded as a flag; a compared DISAGREEMENT still goes to the gate list |
| NOT_APPLICABLE | both sides null |
| DISAGREEMENT | the two values differ |
| AGREEMENT | the two values are equal |
| NOT_A_UNIT | listed only |

AMBIGUOUS is a **flag**, listed under that label. It deviates from §7's list, so that a disagreement cannot be parked outside the gate. This is disclosed for the human act.

## 6. Licence, gate, seal, reviewer
- **Licence.** "Cross-family reproduction of the finite results, BLIND tier only" requires all of:
  - every scored unit AGREEMENT or NOT_APPLICABLE;
  - no AMBIGUOUS flag;
  - no unrecognized key.

  This is P:12, kept.
- **Gate.** Every DISAGREEMENT is classified (a)–(d) by a reviewer who has both programs. It counts as (a) only on a concrete history where the checker's program departs from the literal spec. **Any non-(a), including (c), goes to the human.**
- **Known (c) candidate:** `V1b/R-d/U/(i)-S/holds` (F-1).
- **Seal.**
  1. Hash every file, check that each JSON parses, and check that all six required files are present.
  2. Commit.
  3. Only then extract the metadata.
- **Reviewer.** A fresh, read-only Claude subagent, disclosed as Claude family. It answers the eight questions of §7.

## 7. Comparator validation (before any return): `selftest_compare_xcheck.py`, PASS
- **P0:** Claude's results re-encoded with different syntax and aliases.
  - 0 DISAGREEMENT, 0 INCOMPLETE, 0 unrecognized keys.
  - The licence holds.
  - BLIND: 1,516 AGREEMENT, 242 NOT_APPLICABLE (mostly out-of-scope iiiL).
- **N1–N6** (planted defects) and **T1–T11** (adversarial encodings from REVIEW-r3) are each caught or parsed correctly as specified:
  - record-keyed r/c;
  - `issue`/`adopt` words;
  - boolean con;
  - a missing f;
  - an omitted iiiL;
  - null vs false;
  - dict-of-boolean K12;
  - a generic A9 label;
  - an unknown reading;
  - `"665,920"`;
  - an omitted counterexample;
  - subscript and typographic aliases.
- **A1–A11** (REVIEW-r4) are also caught or parsed correctly:
  - colliding reading, history, form and check aliases with a wrong value first;
  - a duplicate JSON key;
  - an unknown character (U+02BC);
  - prefix-boundary matching;
  - an UNTESTABLE disagreement routed to the gate;
  - the iiiL out-of-scope rule, both directions;
  - ignored versus unknown keys.
- **Limit:** a syntax outside the grammars, or an unrecognized key, gives INCOMPLETE or blocks the licence. It is never scored as agreement in any case tested. Plausible spec-literal encodings that are loud and need a reviewed post-return adapter: Ref keyed by record; a slot carrying `referenced`; `C-U5` reported as a named history. The adapter therefore carries weight, which is why it is syntax-only, separately committed and reviewed.

## 8. Changes relative to `PREREG.md`
**Narrowings:**
- the licence is limited to the BLIND tier;
- AMBIGUOUS and unrecognized keys block the licence;
- (c) also goes to the gate;
- NOT_REPORTED is split into INCOMPLETE and UNTESTABLE;
- fail-closed parsing.

**Loosenings, disclosed:**
- NOT_APPLICABLE (both null) is licence-compatible;
- R5 composite witnesses are matched by membership in the role set, but they are NONLICENSING;
- these are not units: Claude-only extra fields; the §D.2 images and diffs; the common-control frames as named units; counterexamples where either side has holds = true (the disagreement shows up in `holds`);
- named-history fields at non-designated (reading, history) pairs are not required (PREREG: "each named-history field"). They are scored only if the checker reports them;
- K12.2 fine splits are not scored when the checker reports only the coarse property;
- iiiL `false` against Claude's out-of-scope null counts as agreement, under the pre-declared condition in §4;
- the bundle is bundle-02 (human act, option N).
