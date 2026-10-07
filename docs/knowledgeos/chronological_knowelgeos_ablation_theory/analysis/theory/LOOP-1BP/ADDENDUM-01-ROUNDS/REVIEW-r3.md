# Review of addendum r3, comparator and bundle-02 (independent subagent, read-only; Claude family)

**Verdict: FAIL.**

**Reviewer disclosure:** the reviewer read sealed values. This review must never be routed to the checker.

## Findings
- **A. Bundle-02: OK.**
  - The hash is correct: one folder, five files.
  - README, TASK and R5-SPEC are byte-identical to bundle-01.
  - The 12 removals are labels only, and every coordinate is unchanged.
  - No outcome hint remains, apart from the "(R4 Lem 3.7)" pointer, whose unit is already EXPOSED.
  - Nonblocking: no edit list was recorded.
- **B. Earlier review changes.** REVIEW-r1 1–7 and REVIEW-r2 3–7 are addressed in substance. Still open: commit before handover; the canonicalization is defective.
- **C. Comparator: blocking.** Silent false AGREEMENTs were found:
  - **T1:** r and c keyed by record (`{"ρ1":1}`) were mis-parsed.
  - **T2:** Cap words `issue`/`adopt` were dropped.
  - **T3:** con given as booleans was read as none.
  - **T4:** a slot without f defaulted to ∞.
  - **T5:** an omitted required iiiL, or a null-vs-false scope disagreement, counted as agreement.
  - **T6:** in K12 dict-of-boolean rows, false counted as present.
  - **T7:** a generic A9 label agreed with both fine-split rows.

  Also loud but defective: subscript collisions; typographic apostrophes; looseness in `to_obs`; w1 `{"ref":1,"t":0}`; a crash on `"665,920"`. The selftest had no adversarial-syntax controls.
- **D. Tiers.** These are derivable from EXPOSED facts, so they must be EXPOSED:
  - iS and iiO_c under R0/U at H_g⁺, W7 and I7;
  - K4b at H_g⁺, W7 and I7 in U, for all readings.
- **E. §8 is incomplete.** Not disclosed:
  - counterexamples omitted where both sides have holds = false were NOT_A_UNIT;
  - `exists(…H_d2)` was not required;
  - NOT_A_UNIT rows were not listed;
  - the required tuples are limited to designated histories (the "Common controls" yield none);
  - the iiiL merge hides scope disagreements.
- **F. Leakage: OK.**
- **G. Other:**
  - the EXTRAS comment says "once" but the code compares them at every (R, F);
  - the K12 branch crashes on a non-dict result.

## Reconciliation (main analyst)
Every finding is accepted and implemented in r4 / `compare_xcheck.py`:
- **Fail-closed.**
  - Closed alias tables for every key; unknown keys are recorded and **block the licence**.
  - Strict grammars for every value: r/c as a scalar or a dict keyed exactly by ρ1; whitelisted Cap and kind words; con keyed by a1/a2 with whitelisted values; a mandatory `f`; w1 `{t}` only; integers only for n.
  - Exact K12 label tables; generic A7/A9 labels → MALFORMED; dict-of-boolean rows supported.
- **iiiL** is 3-valued (True/False/None) and compared exactly. Required iiiL units are never defaulted.
- **Counterexamples** are a required unit wherever both sides say holds = false.
- **Extras** are required with their tuple.
- **NOT_A_UNIT rows** are listed.
- **Claude-side parsing** is separated, and a failure there crashes the comparator.
- **D units** moved to EXPOSED.
- **Selftest:** T1–T11 adversarial controls added; all pass.
- **The bundle-02 edit list** is recorded.
