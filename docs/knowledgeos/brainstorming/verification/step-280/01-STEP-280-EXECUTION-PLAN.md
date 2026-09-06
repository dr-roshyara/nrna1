# 01 — Step 280 Execution Plan
**Executed 2026-08-30T23:24:02+02:00 · Python 3.13.2 · repo 57d93b0e**

| Phase | Action | Artifact |
|---|---|---|
| 1 | Implement Step 279 components 1–15 faithfully | `exec/kos279.py` |
| 2 | Implement the K/Σ/T layer under test | `exec/kosmodel.py` |
| 3 | Build ≥36 stratified cases, ≥20% negative | `exec/corpus.py` |
| 4 | Execute E1–E24 | `exec/run_e_tests.py`, `OUT-E-*` |
| 5 | Execute F1–F13 (+F4b) | `exec/run_f_tests.py`, `OUT-F-*` |
| 6 | Probe the REAL EKP; compute statistics; check the 10 critical conditions | `exec/run_real_and_stats.py` |
| 7 | Populate the closure matrix with observed evidence only | `10-…-COMPLETE-REPORT.md` |

**Discipline applied:** no fallbacks · no silent repair · no implicit boolean conversion (the rule
evaluator raises on any verdict outside `{PASS,DENY,CONDITIONAL,UNKNOWN,CONFLICT}`) · failures reported as
failures · `Level 4 ≠ Level 5` maintained throughout.
