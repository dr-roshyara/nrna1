# Cross-check pre-registration, addendum 01 (r9; fixed before any return exists)

**Status:** PROPOSED. It takes effect only through a human act (§18 of the control prompt). Until then `PREREG.md` alone governs.

**Review history:** r1–r8 FAILED review (`ADDENDUM-01-ROUNDS/`). r9 implements REVIEW-r8 in full.

**Normative text:** `compare_xcheck.py` (sha256 `e79f9434…ee9cd33f`), validated by `selftest_compare_xcheck.py` (sha256 `c195b5a9…277f26`). This prose only summarizes the script. Where the two differ, the script governs, and the difference is a defect to report.

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
**The final rule (r9, after REVIEW-r8).** Named-history fields at planted histories turned out to be pervasively derivable, because the checker is given the coordinates:
- K4b(H) ⇔ H ∈ R_B;
- `member(R_B)` is syntactic;
- `iS` and `iiO_c` follow from the exposed forms and from O;
- U5 = U for c1 histories.

Each round found a further class. **So every named-history, extra and SUPP field is now EXPOSED.** BLIND keeps only quantities the checker must compute and cannot read off the spec.

| Tier | Units | Licenses |
|---|---|---|
| **EXPOSED** | every R5 truth value and null witness; K12 rows other than the fine splits; the K12.2 coarse units; **every named-history, extra and SUPP field**; V1b form units that the closure rules below expose | consistency only |
| **NONLICENSING** | R5 composite witnesses (role dicts), matched by membership in the role set | nothing |
| **BLIND (185 units in P0)** | `n_frame_histories`; K8.1's per-o difference set; the K12.1 con-only/d-only rows and the K12.2 A7 +act/+work/+ref and A9 con-only/d-only rows (38 R5 units); **V1b form verdicts (`holds`, 87) and least counterexamples (60) not exposed by the closure rules** | cross-family reproduction |

**Closure rules for V1b form units:**
- **R0:** all of U. In U5, (i)-S and (i)-O.
- **R-d:** in U, (i)-S and (i)-O.
- **R-b ≡ R0:** B-recW forces w1 present.
- **Monotonicity in U** (R-c, R-e, R-f, R-i, R-i′: H-D∩H-B ⊆ H-D^R∩H-B^R ⊆ H-B^R ⊆ R_B^R): (i)-O. For R-e, also (i)-S, (ii)-S and (ii)-O.
- **R-c/U and R-f/U:** (ii)-S and (ii)-O.
- **R-h/U:** all forms. For (i) and (ii), via the K4/K6 witnesses (Ref preserved). For **(iii)-L, this over-exposes:** its `holds` is derivable only through K8.7 and the U7 equivalence, and its least counterexample is a computed value. It is EXPOSED conservatively, and this is disclosed.

**Residual uncertainty, stated plainly:** BLIND means *"not shown to be derivable from what the checker saw"*, not *"proven underivable"*. Over-exposure only narrows what may be claimed. **Units are not independent observations:** for example, the 87 form verdicts share classes across forms. Unit counts are bookkeeping, not evidence weights.

## 3. Fail-closed parsing
- **Keys** pass only through closed alias tables: readings, frames, forms, histories, fields, the R5 check keys (exact), and K12 rows and groups.
  - `norm()` is closed: a character outside an explicit whitelist raises. It is never dropped.
  - **Collisions:** a duplicate JSON key makes the whole file MALFORMED. Two raw keys that map to one canonical id make that id MALFORMED, at every level. For readings, frames, forms, histories and fields, the collision is also recorded under `unrecognized_checker_keys`. For K12 rows and labels, the whole K12 check becomes MALFORMED. For K8.1 o-keys, the `difference_sigma_values` unit becomes MALFORMED. The levels are: readings, frames, forms, histories, fields, K12 rows, K12 labels inside a dict-of-boolean row, and K8.1 o-keys.
  - **Extra keys:** the top-level R5 keys, each check's extra keys, form-body keys, keys inside each form dict (`holds`, `least_counterexample`) and named-history fields are checked against whitelists. Whitelisted keys are ignored: `_meta`, `detail`, `notes`, `images`, `diffs`, `class_sizes`, `n_counterexamples`, `scope_size`.
  - **Anything unknown** is recorded under `unrecognized_checker_keys` and **blocks the licence**. It can only be mapped by a reviewed, separately committed, syntax-only adapter.
- **Values** must match the grammars below. Any checker-side parse failure, including a type error, gives MALFORMED → INCOMPLETE, never a crash. The only defaults are disclosed in §8: `f: null` → ∞, and absent-slot con ignored:
  - booleans: true/false or "TRUE"/"FALSE";
  - n: an integer;
  - O: `{r, c}`, `[r, c]`, `(r=…,c=…)` or `(r, c)`, where r and c may be keyed by ρ1;
  - histories: exactly nine coordinates. Slot kinds come from a whitelist; an iss slot needs d, v, prep and f; an adp slot needs d, with an optional obj. Cap, Ref and con are whitelisted, and w1 is `{t}`, t or `t=…`;
  - K12: exact labels from closed tables. The coarse "A7"/"A9" labels are handled as described below;
  - K8.1: per-o values must be lists of σ pairs, or dicts whose every value is literally `true`.
- **Anything else** is MALFORMED, which gives INCOMPLETE.
- **Claude-side values** are parsed separately, with Claude's own annotation keys (`tuple_R1`, `h_v_exists`, `h_c_exists`) stripped explicitly. A failure there crashes the comparator; it is never scored.
- **K12.2 coarse labels.** The spec names "A7 restricted to …" and "A9 (con-only or d-only …)" as single properties, so a literal checker may report only "A7" or "A9".
  - A row counts as coarse only if **no** fine label of that property is mentioned at all; an explicit fine `false` is scored. In that case the row's fine-split units are NOT_A_UNIT, flagged `coarse-label`, and listed under `coarse_label_rows_not_scored`.
  - The coarse units `A7-any` and `A9-any` (EXPOSED) are scored.

## 4. Units
- **R5:** the 34 keys; K12 compared row × group.
- **V1b:** 12 readings × 2 frames × 5 forms.
- **Counterexamples:** a required unit wherever both sides report holds = false. If the checker omits one there, it is INCOMPLETE.
- **Named fields:** the Def P0.3 fields at every named history the Claude side reports.
  - **Required tuples:** those of V1B-SPEC §C. The common controls C-U5 and C-O1 define a frame and an observation, not a named history, so they produce no named units. The images they would need (V1B-SPEC §D.2) are not part of TASK's output.
- **Extras:** `exists(H-D+, ≈σf H_d2)`, `O(W7)` and `O(I7)`, compared at every (reading, frame) and required with their tuple.
- **SUPP:** the six SUPP values, all required.
- **iiiL:** three-valued (true, false, null = out of scope) and compared exactly. **Pre-declared exception:** the spec types iiiL as {true, false}. If Claude reports null with `iiiL_in_scope = false` and the checker reports `false`, there are two cases.
  - **(a) Claude's out-of-scope follows from membership,** i.e. `member(H-D)` ∧ `member(R_B)` is false. The unit is AGREEMENT only if both membership units are AGREEMENT; otherwise it is DISAGREEMENT.
  - **(b) It follows from the coordinates** (a2 present, or w1 not violating), which no unit verifies. The unit is flagged AMBIGUOUS, listed under `iiiL_scope_unverifiable`, and **blocks the licence**. Null vs false in the other direction is always DISAGREEMENT.
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
  - BLIND: 185 AGREEMENT; EXPOSED: 1,805 AGREEMENT and 256 NOT_APPLICABLE (242 of them out-of-scope iiiL).
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
- **Y5** (REVIEW-r8): every named and SUPP unit is EXPOSED. A dict adp slot carrying iss attributes is now rejected.
- **Y1–Y4** (REVIEW-r7) are also caught or correct: con `null` rejected; a string adp whose obj is its own slot rejected; the semantic alias `exists(H-D+, H_d2)` removed (now an unrecognized key); and a spot-check of the closure tiers.
- **X1, X1b, X2, X6, X7** (REVIEW-r6) are also caught: a K8.1 dict of `false`; a K8.1 dict of `true` whose keys are quoted JSON (loud INCOMPLETE); a K8.1 `null`; the string "COARSE" as a field value; an unhashable Ref entry. None crashes.
- **A9b and B1–B6** (REVIEW-r5) are also caught: a coordinate-decided iiiL scope; K12 row collisions (K12.1, K12.2); a label collision inside a dict-of-boolean row; a coarse label plus an explicit fine `false`; a K8.1 o-key collision; an unknown key inside a form dict.
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
- the bundle is bundle-02 (human act, option N);
- (i)-O has no counterexample unit (TASK's format has `holds` only). A checker's `least_counterexample` under (i)-O is accepted and ignored. Claude reports 9;
- the con of an absent slot is not compared, and repeated entries inside K12 and K8.1 lists are tolerated;
- a membership-decided iiiL scope where Claude's `iiiL_exists_ignoring_scope` is true (52 units): a checker's `false` there cannot be told apart from an existence bug. The pre-declared exception (§4) accepts it;
- a slot's `f: null` (or a missing `f` in the Claude-side string form) is read as ∞ ("never in force"). This is a disclosed reading choice.
