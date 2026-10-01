# Targeted re-review of Protocol B r3 (frozen)

A researcher repaired PROTOCOL-B.md / scope_b.sh against BLK-2 (blocking), NB-11 and NB-12 (REVIEW-r2). CHANGELOG-B-r3.md describes the repair. Use only the files here; read the script line by line.

Check **only**:
1. **BLK-2 path tests.** Give the channel each path gets:
   - engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md;
   - docs/implementation/PROGRAM_STATUS.md;
   - docs/approvals/x.md;
   - register.md;
   - docs/decision-log.md;
   - app/Models/Registration.php;
   - config/statuses.php;
   - resources/js/normalize.js;
   - docs/release-notes.md;
   - deploy/prod.yml.

   For each one: is the channel correct? Is the E exclusion consistent with the regex, i.e. is a candidate path never an executor path, and vice versa? Is a repository-specific path hard-coded anywhere?
2. **NB-11, NB-12:** fixed or declared?
3. **Reachability:** every outcome per question; the headline computed from the data; forced negatives.
4. **Regression:**
   - read-only safety (every command), determinism, config isolation;
   - the B1–B5 and BLK-1 fixes still intact;
   - self path before `cd`, OUT refusal;
   - no identities (the guard fails closed);
   - boundedness, docs/knowledgeos/ exclusion, neutrality.

Verdict: **PASS** · **PASS_WITH_LIMITATIONS** (no blocking issue) · **FAIL**, with every blocking issue.

Write **REVIEW.md** and **REVIEW.json** ({verdict, path_tests: [{path, channel, correct, e_consistent}], fixed: {BLK2, NB11, NB12}, reachability: {...}, regressions: [...], blocking: [...], nonblocking: [...], independence_rule_ok, identity_ok}).
