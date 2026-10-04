# Task: an independent enumeration under alternative readings (V1b)

You are an independent verifier. You have ONLY:
- `R5-SPEC.md`: the base definitions and universe U (§7.1);
- `V1B-SPEC.md`: the readings, check forms, output fields and named histories.

**No expected outcomes are given. Compute; do not assume.**

1. Write your own Python 3 program, **`verify_v1b.py`** (standard library only). It must:
   - enumerate U and U₅ exactly as R5-SPEC §7.1 specifies (frame F1 ∧ F2, plus the U₅ axiom, in the §7.1 order);
   - for every reading listed in V1B-SPEC (R0, R-b, R-c, R-d, R-e, R-f, R-g, R-g+, R-h, R-i, R-i', R-j), build H-D^R, H-B^R and R_B^R literally from the clause definitions;
   - compute everything in V1B-SPEC §D (check forms with least counterexamples; images and diffs; every Def P0.3 field at every named history).
2. Run it: `python3 verify_v1b.py`. Save **`V1B-RESULTS.json`**, structured as {reading: {frame: {checks, images, diffs, named: {history: {field: value}}}}}.
3. Write **`NOTES.md`**:
   - every ambiguity in the spec, and the literal reading you chose;
   - anything you could not compute;
   - your runtime.

**Rules:**
- Use only files in this directory; read nothing outside it.
- Write scratch files only inside this directory.
- No network. Run only `python3`.
- **Do not tune any reading to obtain a particular result.**
