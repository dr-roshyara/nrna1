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
- **Mapping (fixed now).**
  - V1b `class_sizes` keys `R_B`, `H-D`, `H-B`, `H-D&H-B` and `H-D&R_B` map one-to-one to the slice `sizes` keys.
  - V1b least counterexamples are parsed with LOOP-1BP's `claude_hist` and compared with the worker's histories through the same fail-closed `hist`.
  - V1b (iii)-L `scope_size` and `n_counterexamples` use V1B-SPEC Def P0.2's scope, which is the scope of SLICE-SPEC §3.3.
  - **Not calibrated, because V1b did not report them:** U (i)-S and (ii)-S `scope_size` and `n_counterexamples`.
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
- **BLIND:** U_P2 `n_frame_histories`, `sizes` and `scope_size`; plus `n_counterexamples` and the least counterexample, **only where the Claude-side `holds` is false**. Where `holds` is true, a count of 0 and a null counterexample follow from the EXPOSED verdict, so they are EXPOSED.
- **De-duplication (REVIEW-slice-r1).** The licence reports **distinct** BLIND quantities. These are counted once:
  - **R_B:** one quantity across R0, R-b, R-d, R-e, R-g, R-g+ and R-j, which leave B-rec unchanged;
  - **H-D:** one quantity across R0, R-b, R-c, R-f, R-g, R-g+, R-h, R-i and R-i′, which leave the D clauses unchanged;
  - **R_B^{R-i′}:** equals n, so it is EXPOSED;
  - **(i)-S scope_size:** equals |R_B^𝐑|, so it is a duplicate;
  - **H-D&R_B:** equals |H-D| under R-c, R-f, R-i and R-i′ (D-rec ⇒ B-rec^𝐑), so it is a duplicate there.

  Duplicates are still compared (a disagreement on any of them goes to the gate), but they count once.
- **Residual uncertainty:** BLIND means "no short derivation from the bundle is known". Counts over about 3 × 10⁶ histories are not plausibly derivable by hand, but this is not proven.

## 4. Classes, licence, gate
The rules are the same as LOOP-1BP addendum r10 §§4–6:
- classes are INCOMPLETE > UNTESTABLE > NOT_APPLICABLE / DISAGREEMENT / AGREEMENT, with AMBIGUOUS as a flag;
- parsing is fail-closed;
- the reviewer has both programs;
- any DISAGREEMENT that is not (a) goes to the human.

**Licence:** if every BLIND unit is AGREEMENT, with no flags and no unrecognized keys, the claim is **"the computational core is reproduced across model families on a universe whose values appear in no bundle file"**. Agreement is not proof, and disagreement is not falsification.

The comparator `compare_slice.py` reuses LOOP-1BP's fail-closed parser. It is written and self-tested against the sealed Claude-side results, with planted defects, before handover.

## 5. Freeze and fallback rules
- **Freeze.** The tar hash is committed **before** the Claude worker runs. Any later change to the bundle voids the slice: it then needs a new bundle, a new Claude run and a new handover.
- **Worker context.** A headless `claude -p` may load the user-level `~/.claude/CLAUDE.md`, which is generic and holds no project content. This is recorded in PROVENANCE.
- **Sandbox fallback.** If the non-Claude model cannot run the enumeration in its sandbox (timeout or memory), it may return only its program. The human operator then runs that program **unmodified** with local `python3` and records this as a deviation. The implementation is still non-Claude; only the execution environment changes. The run is valid with the deviation noted. Any edit to the program voids that return.

## 6. Human gates
1. A calibration mismatch (§2).
2. Handover of both bundles. bundle-02 goes after the final r10 review; the slice bundle after the Claude side is sealed and the comparator is self-tested.
3. Any non-(a) DISAGREEMENT after the return.
