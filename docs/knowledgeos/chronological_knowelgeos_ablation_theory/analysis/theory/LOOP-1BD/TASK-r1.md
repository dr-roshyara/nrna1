# Task (frozen): a targeted repair of Protocol B (r1)

Use ONLY the files here:
- `PROTOCOL-B.md`, `scope_b.sh`: the version to repair;
- `REVIEW-r0.md` / `.json`: its review, verdict FAIL;
- `QUESTIONS.md`, `ENVIRONMENT.md`, `ROOTS.txt` (metadata only).

No commands, no web. Repair **only** the following; change nothing else.

1. **B1 (blocking): every git history query uses the same tip set.** All of W, the commit counts, E and N are computed with `git log --stdin` (or `git rev-list --stdin`) over the one tip file. **Tip set:** `refs/heads/**`, `refs/remotes/**` and `refs/tags/**` only; exclude `refs/stash` and `refs/notes/**` (N12). Peel tags; keep **only commit-type tips** (N5).
2. **B3 + B4 (blocking): define the executor set E by repository ROOT, over the full history.**
   - E = the **author** e-mails (`%ae` only, never `%ce`) of non-merge commits touching the CODE / TEST roots of Protocol A r1 (app/, routes/, database/, resources/, config/, bootstrap/, lang/, public/, tests/).
   - Use the root pathspecs, so historic, renamed and deleted paths are covered.
   - Exclude every candidate-channel **glob pattern** (not tip path names) and docs/knowledgeos/**.
   - Doc and governance roots are **not** executor paths.
   - W and N also use `%ae` only. Disclose the remaining limits:
     - identity aliases are not resolved;
     - a rename commit spanning a code root and a channel path is still counted.
3. **B2 (blocking): N must never be derived from the candidate's own paths.**
   - For each candidate c: N_c = authors of commits touching the norm-register paths **minus c's own path(s)**.
   - If N_c's path set is empty → Q7 = INDEPENDENCE_NOT_DETERMINABLE[no_norm_source_distinct_from_candidate].
   - A candidate that **is** a norm register is a self-attested record: report it as SELF_ATTESTED (with the overlap counts). **Not** a construction-forced NOT_INDEPENDENT.
4. **B5 (blocking) + N1:** K-DEPLOY's Q2 is STATE_RECORD_BY_TYPE. Q5 is **NOT_DETERMINABLE_BY_EXISTENCE_CHECK for every channel** whose content is not read. Remove NO_BY_TYPE.
5. **N2: make Q7 INDEPENDENT reachable**, in qualified form.
   - Outcome: INDEPENDENT_AT_IDENTITY_COUNT_LEVEL[aliases_unresolved].
   - Condition: the mechanism is observable, W ≠ ∅, N_c ≠ ∅, E ≠ ∅, and |W∩N_c| = 0 and |W∩E| = 0.
   - Keep IND where the mechanism is unobservable.
   - State explicitly that Q6 has a single outcome by design (existence-only).
   - The headline in 71_overall.tsv must be **computed from the per-channel Q7 values**, not a constant. Rename it from `NIF_A_…` to a token defined in the protocol (N14).
6. **N3:** label Q1 negatives NOT_EVIDENCED_AT_TIPS. Paths come from `ls-tree -r -z` (N9).
7. **N4: config isolation.**
   - Unset GIT_CONFIG_PARAMETERS and GIT_CONFIG_COUNT.
   - Pass `-c log.follow=false -c diff.renames=false -c log.decorate=false -c core.fsmonitor=false -c log.showSignature=false` to every git call.
   - Disclose that the repo-local config stays readable.
8. **N6:** the identity guard fails **closed** on any grep error (rc ≥ 2 → FAIL, exit 1).

Leave N7, N8, N10, N11, N13 and N15 as **listed disclosed limitations** in PROTOCOL-B.md (one line each). Do not fix them.

Keep these unchanged:
- read-only;
- no identities in output;
- the self path resolved before `cd`;
- OUT refused inside the work tree / .git;
- no content read beyond signature-header presence;
- neutrality.

Write the repaired **PROTOCOL-B.md** and **scope_b.sh** (still under ~170 lines), plus **CHANGELOG-B-r1.md** listing each change by defect id.
