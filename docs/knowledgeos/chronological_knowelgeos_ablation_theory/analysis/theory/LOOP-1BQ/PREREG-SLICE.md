# Blind slice cross-check: pre-registration (fixed before either side has run)

**Authority:** human act, 2026-10-07: "Hand over + blind slice", answering F-LOG-0236.

**Why this exists.** In the R5/V1b cross-check (LOOP-1BP), only 91 of about 2,264 units were blind. R5-SPEC states the claims under test, and the V1b planted histories coincide with stated examples. This slice asks for quantities that **appear nowhere in any bundle file and follow from no stated claim**: class sizes, scope sizes, counterexample counts and least counterexamples. It asks for them in the universe U_P2 (V = {p, q, r}, P = {p, q}), which R5-SPEC §7.3 lists as untestable in U.

**What it tests.** Whether two implementations from different model families, written from the same definitions, compute the same objects. This is **cross-family reproduction of the computational core**: the frame, the clause definitions under 12 readings, and the check forms.

**What it does not test.**
- The generic theorems: U_P2 is one more finite instance.
- Empirical validity.
- Generalization beyond finite universes.

## 1. The bundle
`LOOP-1BQ/BUNDLE/`: README, TASK, SLICE-SPEC, and R5-SPEC and V1B-SPEC byte-identical to bundle-02. It is to be tarred as `F-XCHECK-SLICE-01.tar` and its hash committed before handover.

## 2. Claude side (run before handover, sealed)
- **Who:** a fresh, isolated headless Claude session (`claude -p`), in a directory outside the repository. Tools: Read, Write and Bash restricted to python3. Web, search and agents are disallowed.
- **Input:** the bundle only. The worker sees no earlier results.
- **Sealing:** its outputs are hash-sealed and committed **before** the non-Claude handover.
- **Calibration (within-family regression, decided before reading the U_P2 values).** The worker's U values are compared with the sealed V1b/R5 U values. Fields compared:
  - `n_frame_histories` (665,920);
  - V1b `class_sizes` (R_B, H-D, H-B, H-D&H-B, H-D&R_B);
  - `holds` for the five forms;
  - (iii)-L `scope_size` and `n_counterexamples`;
  - least counterexamples where V1b has them.
- **Any calibration mismatch:**
  - is classified, like (a)–(d) in LOOP-1BP/PREREG.md, as fresh-worker error, sealed-V1b error, spec ambiguity, or theory-level;
  - **goes to the human before handover;**
  - is never repaired silently.

## 3. Comparison units (non-Claude return vs the sealed Claude side)
For each frame ∈ {U, U_P2} and each of the 12 readings:
- `sizes` (5);
- for (i)-S, (ii)-S and (iii)-L: `holds`, `scope_size`, `n_counterexamples`, and `least_counterexample`. The last is compared only where both sides give holds = false;
- `holds` for (i)-O and (ii)-O.

Plus `n_frame_histories` per frame.

**Tiers:**
- **EXPOSED** (consistency only):
  - every `holds` in both frames. The generic Prop 4.1, Cor 4.1b and Lem 3.7 statements, and the monotonicity arguments of LOOP-1BP r7–r10, may decide them;
  - every U-frame unit. The U frame is a calibration anchor: its stated claims and planted controls are in R5-SPEC and V1B-SPEC.
- **BLIND:** U_P2 `n_frame_histories`; U_P2 `sizes`, `scope_size` and `n_counterexamples`; and U_P2 least counterexamples.
- **Residual uncertainty:** BLIND means "no short derivation from the bundle is known". Counts over about 3 × 10⁶ histories are not plausibly derivable by hand, but this is not proven.

## 4. Classes, licence, gate
The rules are the same as LOOP-1BP addendum r10 §§4–6:
- classes are INCOMPLETE > UNTESTABLE > NOT_APPLICABLE / DISAGREEMENT / AGREEMENT, with AMBIGUOUS as a flag;
- parsing is fail-closed;
- the reviewer has both programs;
- any DISAGREEMENT that is not (a) goes to the human.

**Licence:** if every BLIND unit is AGREEMENT, with no flags and no unrecognized keys, the claim is **"the computational core is reproduced across model families on a universe whose values appear in no bundle file"**. Agreement is not proof, and disagreement is not falsification.

The comparator `compare_slice.py` reuses LOOP-1BP's fail-closed parser. It is written and self-tested against the sealed Claude-side results, with planted defects, before handover.

## 5. Human gates
1. A calibration mismatch (§2).
2. Handover of both bundles. bundle-02 goes after the final r10 review; the slice bundle after the Claude side is sealed and the comparator is self-tested.
3. Any non-(a) DISAGREEMENT after the return.
