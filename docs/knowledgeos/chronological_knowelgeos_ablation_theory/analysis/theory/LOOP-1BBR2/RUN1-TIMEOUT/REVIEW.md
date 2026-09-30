# REVIEW of repair r2 (targeted)

## Verdict: **FAIL**. The artefacts under review are absent. **Neither script may run.**

### What was checked
REVIEW-TASK.md says to review **SPEC-r2.md, SPEC-r2.json and CHANGELOG-r2.md**. None of these files exists in the review directory. I tried to read each one at its exact path and got "File does not exist" every time. I also tried these variants, which do not exist either:
- `SPEC-1bb-r2.md`
- `spec-r2.md`
- `../SPEC-r2.md`
- `../A/SPEC-r2.md`

These files were present and were read:
- REVIEW-TASK.md
- TASK.md
- ENVIRONMENT.md
- REVIEW-r1.md / REVIEW-r1.json
- STATE.md
- SPEC-r1.md (first part)

No command was run and nothing was collected.

I had no directory-listing tool. So I cannot rule out that the r2 output exists under some other file name. If it does, this review should be rerun against it.

### Why the verdict is FAIL and not "not reviewed"
- REVIEW-TASK.md allows only three verdicts: PASS, PASS_WITH_LIMITATIONS or FAIL.
- A PASS of either kind means a script may run. That would authorise a rank-0 script and a scoping script that do not exist and that no reviewer has read.
- The only safe verdict is FAIL. In substance it means **NOT REVIEWED: INPUT MISSING**. It is not a finding that the repair is wrong on its merits.

### Blocking issues
1. **B-0 (input missing).** SPEC-r2.md, SPEC-r2.json and CHANGELOG-r2.md are absent. None of checks 1–5 can be carried out.
2. **B-R0 (rank-0 script).** No rank-0 script is available to read. So none of these can be shown:
   - that it runs in the ENVIRONMENT.md setting (working clone, git 2.55, read-only, local refs);
   - that it handles non-zero exits;
   - that its output is deterministic and hashed;
   - that the target matching is precise;
   - that trace vs mention is operational, including merges;
   - that M-TIME is respected;
   - that absence is recorded as NOT-EVIDENCED-IN-CAPTURE.

   REVIEW-r1 marked the r1 rank-0 protocol "must not be executed" (R0-1, R0-2). That prohibition stands.
3. **B-SC (scoping script).** No scoping script is available to read. None of these can be shown:
   - that it is bounded and existence-only;
   - that every outcome is reachable or labelled NOT_DETERMINABLE (SC-1, SC-2);
   - that history paths are covered (SC-3);
   - that no personal identities appear in the output (SC-5).

   REVIEW-r1 let the r1 scoping protocol run with limitations. That permission does **not** carry over to an r2 script that has not been seen.

### 1. Repair status

| Item | Status | Reason |
|---|---|---|
| D1, D2, D5, D8, D9 | NOT_FIXED (not assessable) | No r2 artefact. The r1 PARTIAL status in REVIEW-r1 stands. |
| N1–N4 | NOT_FIXED (not assessable) | No r2 artefact. The r1 defects stand as REVIEW-r1 describes them. |

### 2. Symmetry
No r2 symmetry table exists. So none of the falsifiers can be checked. The known r1 violations remain open until an r2 artefact shows otherwise:
- **N1:** third-party application (NIF-B) fires P4-HD.
- **N2:** "no permitting act ⇒ not permitted" presupposes H-D P1 and is used against H-B.
- **N3:** the maker's-records fallback is offered against H-B only.
- **N4:** "already governs" is frozen as non-adoption.

### 3–4. Scripts
Not present, so not reviewed. Both are non-executable for the purpose of this review.

### 5. Out-of-scope changes
Not assessable. There is no r2 text to compare with r1.

### New defects
None can be assessed. The only issue found is the missing input (B-0).

### What is needed
Place SPEC-r2.md, SPEC-r2.json and CHANGELOG-r2.md in this directory, then rerun this review.
