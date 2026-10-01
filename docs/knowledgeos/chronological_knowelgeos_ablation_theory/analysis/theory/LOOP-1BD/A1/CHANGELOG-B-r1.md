# CHANGELOG — Protocol B r0 → r1 (targeted repair of REVIEW-r0 FAIL)

Only the defects listed in TASK.md were changed. Nothing was executed.

## Blocking

- **B1 — one tip set for every history query.** The per-path W and commit-count queries (r0 l.91–92, HEAD only) now use `git log --stdin < tips`, the same as E, N_c and `rev-list`. The commit count comes from the same call as W (one `%ae` line per non-merge commit). Output no longer depends on the checked-out HEAD. *(scope_b.sh: tip block, per-channel loop; PROTOCOL-B §1 "The one tip set")*
- **B2 — N never derived from the candidate's own paths.** The global N was replaced by a per-candidate **N_c** = authors over the norm-register paths **minus c's own paths** (`comm -23 npaths cp`). If N_c's path set is empty → `IND[no_norm_source_distinct_from_candidate]`. K-STATUS (the norm register / in-force record channel) → `SELF_ATTESTED[norm_register;overlap_exec=…;overlap_norm=…]`. `NORM_SOURCE_NOT_INDEPENDENT` was removed. New metrics columns: `norm_paths_c`, `norm_writers_c`. *(§3; answers awk)*
- **B3 — E defined by repository ROOT over full history.** E now uses the Protocol A r1 CODE/TEST root pathspecs (`app routes database resources config bootstrap lang public tests`), so historic, renamed and deleted paths are covered. The exclusions are the candidate-channel **glob patterns** (a `:(exclude,glob,icase)` translation of the classifier) plus `docs/knowledgeos/**`. The r0 `:(exclude,literal)<tip path>` list was removed.
- **B4 — E measures authorship of executor work.** Every set uses `%ae` only (`%ce` dropped from W, E and N). Doc and governance roots are no longer executor paths. Disclosed: identity aliases are not resolved, and a rename commit spanning a code root and a channel path is still counted. *(§3 "Disclosed limits")*
- **B5 — K-DEPLOY no longer a config rule.** K-DEPLOY was removed from the `cfg` group. Q2 = `STATE_RECORD_BY_TYPE(content not read)`, Q5 = `ND`. This also removes the r0 dead `ND` branch for Q2.

## Non-blocking (fixed)

- **N1 — Q5.** `NO_BY_TYPE` was removed everywhere. Q5 = `NOT_DETERMINABLE_BY_EXISTENCE_CHECK` for every channel (K-CI, K-HOOK, K-CODEOWNERS, K-STATUS, K-DEPLOY, K-SIG, K-LOCALHOOK), because no content is read.
- **N2 — INDEPENDENT reachable; headline computed.**
  - Q7 `INDEPENDENT_AT_IDENTITY_COUNT_LEVEL[aliases_unresolved]` is now reachable. Condition: the mechanism is observable, W_c ≠ ∅, N_c ≠ ∅, E ≠ ∅, `overlap_norm=0` and `overlap_exec=0`.
  - Unobservable mechanisms keep `IND`. If any of the sets is empty, the result is `IND[empty_set;…]`.
  - The protocol now states that Q6 has a single outcome by design.
  - The `71_overall.tsv` headline is computed from the per-channel Q7 values, and per-outcome Q7 counts were added.
- **N3 — Q1 negative label.** `NOT_EVIDENCED_IN_CAPTURE` → `NOT_EVIDENCED_AT_TIPS` (path channels and K-SIG).
- **N4 — config isolation.**
  - `GIT_CONFIG_PARAMETERS` and `GIT_CONFIG_COUNT` are now unset.
  - The `git()` wrapper passes `-c log.follow=false -c diff.renames=false -c log.decorate=false -c core.fsmonitor=false -c log.showSignature=false` to every call.
  - The repo-local config remaining readable is disclosed in PROTOCOL-B and in `00_env.tsv`.
- **N5 — commit-type tips only.** Tags are peeled with `rev-parse --verify -q <oid>^{commit}`, which handles nested tags and drops tags pointing to trees or blobs. Only commit-type tips are kept.
- **N6 — identity guard fails closed.** grep rc 0 → `FAIL(n files)`, rc 1 → `PASS`, rc ≥ 2 → `FAIL(grep_rc=…)`. Both FAIL cases exit 1.
- **N9 — `ls-tree -r -z`.** Paths are read NUL-delimited. An embedded newline is carried internally as `\001` and restored before any pathspec or hash use. Tab-safe channel/path splitting replaces `cut -f2` / `$2`.
- **N12 — ref scope.** `for-each-ref` is limited to `refs/heads`, `refs/remotes` and `refs/tags`, so `refs/stash` and `refs/notes/**` are excluded.
- **N14 — headline token.** `NIF_A_INDEPENDENCE_NOT_DETERMINABLE_BY_EXISTENCE_CHECK` was replaced by four tokens defined in PROTOCOL-B §5: `LOCAL_INDEPENDENT_CANDIDATE_AT_IDENTITY_COUNT_LEVEL`, `LOCAL_INDEPENDENCE_NOT_DETERMINABLE`, `LOCAL_NO_INDEPENDENT_CANDIDATE_OBSERVED` and `LOCAL_NO_CANDIDATE_EVIDENCED`.

## Non-blocking (left as disclosed limitations, PROTOCOL-B §6)

- **N7, N8, N10, N11, N13, N15.** Each is listed in one line and not fixed. From N13, the "dead Q2 ND branch" item no longer applies after B5.

## Unchanged (by requirement)

- The script is read-only and writes no identities to the output.
- The self path is resolved and hashed before the one `cd`.
- OUT is refused inside the work tree or `.git`.
- No content is read beyond signature-header presence, and no file blob is read.
- The protocol stays neutral.
- The script is ~169 lines (r0: 167).
