# Blind slice, within-family result (Sonnet 5.5 vs the sealed Opus 5.5 side)

**Label: WITHIN-FAMILY, cross-model.** The comparator field `licence_cross_family_reproduction_of_computational_core = true` is **NOT** cross-family evidence here (human act F-LOG-0239). The correct reading is: **"the computational core is reproduced across two Claude models on values that appear in no bundle file"**.

## Result (frozen comparator `4e444591…`)
- **BLIND:** 118/118 AGREEMENT, i.e. **67/67 distinct blind quantities**.
- **EXPOSED:** 300/300 AGREEMENT.
- 0 disagreements, 0 unrecognized keys, 0 ambiguity flags.

## The byte-identity check
- The Sonnet `SLICE-RESULTS.json` is **byte-identical** to the sealed Opus file (`cc6e635f…`), though the programs differ (Sonnet `c5eae43c…`, Opus `ee7d5510…`).
- **Copying ruled out:**
  1. The tool-path audit shows no access to the Opus folder.
  2. The Sonnet program's only file access is writing its own output.
  3. **The analyst re-ran the Sonnet program in a fresh directory containing only the frozen bundle.** It reproduced `cc6e635f…` in 53 s.
- **Why the bytes match:** both write `json.dump(…, indent=1)` with TASK's fixed key order. Identical values therefore give identical bytes.

## What this shows / does not show
- **Shows:** two independently written Claude implementations, from different models (Opus 5.5 and Sonnet 5.5), compute the same class sizes, scope sizes, counterexample counts and least counterexamples in U_P2. That is about 3 × 10⁶ histories × 12 readings.
- **Does not show:** cross-family reproduction; absence of errors shared by Claude models (e.g. a common misreading of the spec); the generic theorems; empirical validity.
