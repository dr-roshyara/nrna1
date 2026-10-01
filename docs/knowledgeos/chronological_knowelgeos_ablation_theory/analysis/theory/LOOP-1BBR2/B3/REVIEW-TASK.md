# Targeted review of repair r2 (frozen)

A researcher repaired SPEC-r1 against its FAIL review (REVIEW-r1). The output is SPEC-r2.md / SPEC-r2.json / CHANGELOG-r2.md. Use only the files here.

Check **only** the items in scope (TASK.md), plus any **new** defect the repair introduces:
1. **D1, D2, D5, D8, D9, N1–N4:** FIXED / PARTIAL / NOT_FIXED, with the reason.
2. **Symmetry:** check the symmetry table. Can **any** falsifier of either hypothesis still fire on a premise that the other hypothesis explains, or that presupposes a hypothesis? Recheck every falsifier, not only those listed.
3. **Rank-0 script:**
   - read it line by line;
   - would it run to completion in the environment in ENVIRONMENT.md (a working clone, git 2.55, read-only, local refs)?
   - are non-zero exits handled?
   - is the output deterministic and hashed?
   - is the target matching precise?
   - is trace vs mention operational (including merges)?
   - is M-TIME respected?
   - is absence = NOT-EVIDENCED-IN-CAPTURE?
4. **Scoping script:** bounded; existence-only; all outcomes reachable or labelled NOT_DETERMINABLE; history paths covered; no personal identities in the output.
5. Is anything outside the repair scope changed?

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** (no blocking issue) · **FAIL**. List every blocking issue. **A FAIL means neither script may run.**

Write **REVIEW.md** and **REVIEW.json** ({verdict, repair_status: {...}, symmetry_violations: [...], rank0: {executable, blocking: [...], nonblocking: [...]}, scoping: {executable, blocking: [...], nonblocking: [...]}, out_of_scope_changes: [...], new_defects: [...]}).

**Additional note (disclosed by the main analyst):** the drafter was interrupted by an external rate limit after writing SPEC-r2.md and SPEC-r2.json. **CHANGELOG-r2.md was never written.** First judge whether SPEC-r2 is **complete**, i.e. covers every in-scope item. **Treat any missing item as NOT_FIXED.** Use only files in this folder.
