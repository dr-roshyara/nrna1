# Targeted re-review of Protocol C r2 (frozen)

A researcher repaired r1 per TASK-r2.md (SCOPE-1, NB-1, NB-5); CHANGELOG-C-r2.md describes it. Use only the files here; read the script line by line.

Check **only**:
1. **SCOPE-1:** is any signature, verification or gpg field still read or output anywhere? Quote every occurrence of `verif`, `signat` and `gpg`.
2. **NB-1:** trace the guard when (a) grep exits 2, (b) ids.txt is missing, (c) a raw login appears in OUT. Is every case fail-closed?
3. **NB-5:** trace `inside()` for (a) a normal non-repo directory, (b) a distrusted work tree (rev-parse 128 with "dubious ownership"), (c) a repo work tree.
4. **Regression:** GET only (list every call); the bound; calls ≤ 40; the status-gating rule (NA on non-200; no failure-as-absence); identity policy; neutrality; human-scope compliance (HUMAN-SCOPE.md).

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** · **FAIL**, with every blocking issue.

Write **REVIEW.md** and **REVIEW.json** ({verdict, scope1_occurrences: [...], fixed: {SCOPE1, NB1, NB5}, guard_traces: [...], inside_traces: [...], calls: [...], regressions: [...], blocking: [...], nonblocking: [...]}).
