# 1ay-n: controlled-vocabulary normalization (a fix for the 1ay r1 instrument defect). Frozen before the worker runs

- **Inputs:** the free-text values of the 1ax coding (worker CODING.json; the reviewer corrections are applied in 1ay itself) and their spans.
- **The vocabularies are fixed in TASK.md.** Over-merging is the main risk, because it would manufacture contrasts; the reviewer checks it explicitly.
- **Then 1ay r2:** `support_1ay.py`'s logic unchanged, reading `mapped` values (reviewer-corrected) in place of the free text; frozen before it runs. **R06/R07 merged** into one record (§1).
- **Sealed expectation:** after normalization, NORM gains strict pairs on target (e.g. Slice 7B / 7C / WP-8 PROHIBITED vs WP-7B-R1 / Slice 7C AUTHORIZED under START-EXECUTION); ACT stays NOT-DEMONSTRATED (only 2 negatives). Moderate confidence.
