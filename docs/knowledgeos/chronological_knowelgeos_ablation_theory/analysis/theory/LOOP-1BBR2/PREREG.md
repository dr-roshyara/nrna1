# 1bb-r2: targeted repair (the last round before a human stop). Frozen before the drafter runs

- **Scope:** only the r1 FAIL items (D1/D2/D5/D8/D9 partials; N1–N4; blocking R0; SC-1..5). A **symmetry requirement** for N1–N3; executable scripts for the declared environment.
- **Environment facts** are given to the drafter (metadata only; no content), so that r1's executability failures (a bare clone; host refs) cannot recur. Host access is out of scope, so completeness is not shown.
- **Rule:** PASS or PASS_WITH_LIMITATIONS **without blocking issues** → the main analyst runs both scripts **verbatim** (after a separate human-safety read of the script: read-only commands only), hashes the output, then a reviewer audits the outputs. **Otherwise STOP for the human.** No r3.
