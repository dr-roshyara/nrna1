# Targeted re-review — Protocol B r1 (PROTOCOL-B.md + scope_b.sh, frozen)

**Verdict: FAIL.** One blocking issue is left: B2 is relabelled, not repaired. The script must not run as written.

Sources: REVIEW-TASK.md, REVIEW-r0.md, CHANGELOG-B-r1.md, PROTOCOL-B.md, scope_b.sh (170 lines, read line by line). Nothing was executed.

Most repairs hold. B1, B3, B4, B5, N1, N2, N3, N6, N9, N12 and N14 are fixed. N4 is fixed for what it claims, with small residual gaps in the environment hardening. The independence rule still gives a data-independent outcome for the whole K-STATUS channel, and the headline treats that outcome as a negative.

---

## Blocking

**BLK-1 (B2 residual): K-STATUS gets `SELF_ATTESTED` by construction, for every K-STATUS path including non-register records (scope_b.sh l.136; PROTOCOL §3 rule 1).**
- Rule 1 depends only on the channel id: `if (ch=="K-STATUS") q7="SELF_ATTESTED[norm_register;…]"`. It runs before any set is consulted. Once `np>0`, no data can give K-STATUS any other Q7.
- `npaths` (l.89) is a subset of the K-STATUS paths, and `cp` for K-STATUS is all K-STATUS paths. So `ncp = comm -23 npaths cp` is always empty for K-STATUS: `nnp=0`, and `overlap_norm` is always `NA`. The one comparison that could separate the record from the norm-maker can never be computed.
- **Trace, `docs/implementation/PROGRAM_STATUS.md`:** l.72 matches the token `program_status.` with ext `md`, so the file is K-STATUS. l.89 does not match it (no register token), so it is not a norm-register path. Its row is:
  `Q1 YES_IN_CAPTURE · Q2 STATUS/NORM_RECORD(by type; content not read) · Q3 CONTROLLED_BY_WRITERS[n=k] · Q4 NO_CLIENT_SET(…) · Q5 ND · Q6 ND[COMPLETE_VS_HOST=NOT_SHOWN] · Q7 SELF_ATTESTED[norm_register;overlap_exec=x;overlap_norm=NA] · Q8 YES_LOCAL(…)`.
  This outcome is **forced**. It is the same whether the file's writers are disjoint from every other set or identical to them. It holds even when the repo has no register path at all, which means the label `norm_register` contradicts the script's own register subset.
- The channel also covers `approval(s)`, `signoff(s)`, `adoption(s)` and `progress`. These are the records most likely to be written by someone other than the norm-maker. Criterion (c), "the ruling asserting its own effect", fits a rulings register. It does not fit a sign-off or status record.
- l.161 puts `SELF_ATTESTED` in the same headline bucket as `NOT_INDEPENDENT` (`LOCAL_NO_INDEPENDENT_CANDIDATE_OBSERVED`). In effect it is a forced negative, which QUESTIONS.md forbids ("never a forced negative"). PROTOCOL §3 rule 1 also contradicts itself when it says this is "not a construction-forced NOT_INDEPENDENT".
- r0 already raised exactly this case (PROGRAM_STATUS.md as a non-register record). r1 made it unconditional: in r0 it applied only when a register path existed.
- *Fix direction:* split K-STATUS into two disjoint channels.
  - `K-NORMREG` = the l.89 subset. `SELF_ATTESTED` is defensible here as a declared definitional outcome, or it can be `IND`.
  - `K-STATUS` = the other status/progress/approval/sign-off records. Send these through rules 2/4/5/6 as an observable channel, with `N_c = K-NORMREG paths − own paths` and `E`.
  - Count `SELF_ATTESTED` separately in the headline, not as an observed negative.

---

## 1. B1: one tip set — **FIXED**
- Tips (l.52–56) come from `for-each-ref refs/heads refs/remotes refs/tags` only. A commit-type ref is kept as is. A tag object is peeled with `rev-parse --verify -q <oid>^{commit}`, which drops tree/blob tags and peels nested tags. The list is `sort -u`'d, and an empty set gives `CAPTURE_INVALID`.
- Every history query reads that one file: path universe `ls-tree` per tip (l.59), E (l.86), W and commit count from the same call (l.99), N_c (l.105), `rev-list` for K-SIG (l.113). Tag signatures use the same filtered `refs` file (l.116).
- No query uses `HEAD`. `--stdin` with a non-empty tip file stops git from defaulting to HEAD. The result does not depend on the checked-out branch. It depends only on the ref set, plus the per-worktree `.git/hooks` (K-LOCALHOOK) and the repo-local config, both declared.

## 2. B2: own paths in N_c — **NOT FIXED (BLK-1)**
- Can a candidate's own paths enter its N_c? **No.** `comm -23 npaths cp` (l.103) removes them by exact tip-path string, and both files are C-sorted. This also fixes the r0 K-DEPLOY case: `release/decisions.md` is in K-DEPLOY's `cp`, so it is removed from K-DEPLOY's N_c.
- PROGRAM_STATUS.md gets Q7 = `SELF_ATTESTED[norm_register;overlap_exec=x;overlap_norm=NA]`. This is **forced by construction**; see BLK-1.

## 3. B3/B4: executor set E — **FIXED**, with disclosed limits
- **Root-based:** yes. `:(literal)app … tests` are directory-prefix pathspecs, so historic, renamed and deleted files under the roots count.
- **Author-only:** yes. `%ae` is used for E, W_c and N_c (l.86, 99, 105). `%ce` is gone, and so is the shared platform-committer artefact.
- **Full-history:** E walks the whole history reachable from every tip, but **without `--full-history`**. git's default history simplification applies. A commit whose change to the pathspec did not survive a merge (TREESAME pruning) is dropped. The same pruning hits W_c and N_c. It can only *lower* overlaps, so it cannot cause a forced negative. It can, though, produce an unqualified `INDEPENDENT…` (NB-1).
- **Can a channel file's historic or renamed versions put its own writers into E?**
  - A historic name that matches the channel vocabulary: **no**. It is excluded by the `:(exclude,glob,icase)` translation (l.79–85), which holds over all history, including deleted paths.
  - I checked the glob translation against the classifier token by token. The token boundary `[._/-]|$`, the doc-ext rule, the `**/` root match, `.woodpecker*` and `*.tfstate` all match the classifier.
  - Remaining leak: a channel file renamed **from a non-vocabulary name inside a code root** (e.g. `config/notes.yml → deploy/notes.yml`). The rename commit's author lands in both E and W_c. This is disclosed (§3 "Disclosed limits"). Earlier editors of the old name did edit a code root, so their E membership counts as observed.
- **Doc/governance roots excluded:** yes. E is an inclusion list of code/test roots, so `docs/`, `developer_guide/`, `engineering/`, `.claude/`, `claude/`, `scripts/` and `storage/` are not in it. `docs/knowledgeos/**` is excluded on top. Residual: READMEs and other markdown *inside* the code roots (e.g. `tests/README.md`) still count as executor work, although §3 says READMEs are not executor paths (NB-4).

## 4. B5/N1: Q5 — **FIXED**
No Q5 = NO is left. Every evidenced row writes `ND`:
- awk column 6 (l.143)
- K-SIG `%s` = `$ND` (l.147)
- K-LOCALHOOK `%s` = `$ND` (l.151)

Otherwise Q5 is `NOT_APPLICABLE(Q1)` or `NOT_CHECKABLE_LOCALLY`. K-DEPLOY is out of `cfg` and gets Q2 = `STATE_RECORD_BY_TYPE`. The format and argument counts for the K-SIG and K-LOCALHOOK printf lines match (9 columns).

## 5. Reachability

| Q | Reachable outcomes |
|---|---|
| Q1 | `YES_IN_CAPTURE`, `NOT_EVIDENCED_AT_TIPS` (path channels, K-SIG); `YES_LOCAL_ONLY`, `NOT_EVIDENCED_LOCALLY` (K-LOCALHOOK); `NOT_CHECKABLE_LOCALLY` (host rows, constant) |
| Q2 | `CONFIG_RULE` (K-CI/HOOK/CODEOWNERS/LOCALHOOK), `STATUS/NORM_RECORD` (K-STATUS), `STATE_RECORD_BY_TYPE` (K-DEPLOY), `IDENTITY_ATTESTATION` (K-SIG): descriptive, one per type; `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` |
| Q3 | `CONTROLLED_BY_WRITERS[n=k]` (k ≥ 0; `n=0` with paths>0 is reachable through merge-only introduction, empty `%ae` or simplification); `ND(keyring not consulted)` (K-SIG); `LOCAL_MACHINE`; `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` |
| Q4 | `NO_CLIENT_SET(…)` (all evidenced git-backed rows: a structural fact about client-set git times, not a content claim); `NONE` (K-LOCALHOOK, N13); `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` |
| Q5 | `ND`; `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY`. Single substantive outcome by design (no content read) |
| Q6 | `ND[COMPLETE_VS_HOST=NOT_SHOWN]`; `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY`. Single outcome by design, declared |
| Q7 | K-STATUS: **only** `SELF_ATTESTED[…]` (forced, BLK-1). K-CI/K-HOOK/K-CODEOWNERS: only `IND[no_norm_source…]` or `IND[mechanism_host_enforced_unobservable…]`. K-SIG, K-LOCALHOOK: only `IND`. **K-DEPLOY:** `IND[no_norm_source…]`, `IND[empty_set…]`, `NOT_INDEPENDENT[overlap_norm=…]`, `NOT_INDEPENDENT[overlap_exec=…]`, `INDEPENDENT_AT_IDENTITY_COUNT_LEVEL[aliases_unresolved]`. Also `NOT_APPLICABLE(Q1)`, `NOT_CHECKABLE_LOCALLY` |
| Q8 | `YES_LOCAL(…)`; `NO_LOCAL_ONLY` (K-LOCALHOOK); `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` |

**Can `INDEPENDENT_AT_IDENTITY_COUNT_LEVEL` be produced? Yes, but only for K-DEPLOY.** A concrete configuration:
- `deploy/production.yml` is authored only by `ops@x`. It is K-DEPLOY via `deploy/` and not K-STATUS.
- `docs/rulings/R-001.md` is authored only by `chair@x`. It is K-STATUS and in `npaths` via `rulings/`, and it is not a K-DEPLOY path.
- `app/Foo.php` is authored only by `dev@x`.

For K-DEPLOY this gives:
- `np=1` and `nnp=1`: `ncp = {docs/rulings/R-001.md}`
- W = {ops}, N_c = {chair}, E = {dev}, so `nw=nn=nE=1`
- `on=oe=0`

Rule 6 then applies, Q7 = `INDEPENDENT_AT_IDENTITY_COUNT_LEVEL[aliases_unresolved]`, and the headline is `LOCAL_INDEPENDENT_CANDIDATE_AT_IDENTITY_COUNT_LEVEL`.

**Is the headline computed from the data? Yes.** `q7n` (l.158–161) counts column 8 of `70_answers.tsv` over the local rows. The anchored patterns are distinct, so `^INDEPENDENT_AT…`, `^INDEPENDENCE_NOT…`, `^NOT_INDEPENDENT` and `^SELF_ATTESTED` never collide with each other or with `NOT_APPLICABLE` or `NOT_CHECKABLE`. First match wins, as §5 says.

**Forced negatives left: one.** K-STATUS `SELF_ATTESTED`, which the headline counts as a negative (BLK-1). No other negative is construction-forced:
- `NOT_INDEPENDENT` needs an observed shared author.
- Q4 `NO_CLIENT_SET` is a structural git fact that QUESTIONS.md endorses ("a self-attested timestamp is not independence").
- Q8 `NO_LOCAL_ONLY` is factual.

## 6. Regression check

| Item | Result |
|---|---|
| Read-only safety | **OK.** Commands used: `realpath`, `sha256sum`, `cut`, `ls -A`, `mktemp -d`, `rm -rf $TMP` (l.35 and the trap), `mkdir -p $OUT`, `cd` once, `mv` within TMP, `sort/tr/comm/awk/grep`, and `find/sort/xargs sha256sum` in OUT. git calls are all read-only: `rev-parse` (inside-work-tree, bare, toplevel, absolute-git-dir, is-shallow, `--verify -q`), `config --get` ×2, `--version`, `for-each-ref`, `ls-tree`, `log`, `rev-list`, `cat-file commit/tag`. No fetch (partial clone refused, `GIT_NO_LAZY_FETCH=1`), no index refresh (`core.fsmonitor=false`, `GIT_OPTIONAL_LOCKS=0`), no ref or config write, no diff or textconv. Every loop git call has its stdin redirected. |
| Determinism | **OK.** `LC_ALL=C TZ=UTC`, one C-sorted tip file, C-sorted sets, fixed channel order, sorted SHA256SUMS. No HEAD dependence. Residual: repo-local config readable (declared). `GIT_CONFIG`, `GIT_COMMON_DIR` and `GIT_GRAFT_FILE` are not unset, and `.git/info/grafts` is not neutralized (NB-2). |
| Config isolation (N4) | **OK as claimed.** NOSYSTEM, GLOBAL=/dev/null, `GIT_CONFIG_PARAMETERS`/`COUNT` unset. The wrapper is defined before the first git call (l.20 vs l.26), and every call, including those in subshells and pipelines, passes the eight `-c` overrides. `GIT_PAGER` beats `core.pager`. Residual: NB-2. |
| Self path before `cd` | **OK.** Resolved to absolute, `realpath`'d and hashed (l.16–18) before the one `cd` (l.38). |
| OUT refusal | **OK.** Required, absolute, `realpath -m`, refused when equal to or under the toplevel or the git dir (`"$d"/*` also matches `$d/`), must be empty or absent. An empty OUT cannot be an ancestor of the repo. TMP is refused inside the repo or OUT. N8 (common dir) is disclosed. |
| No identities / guard fails closed | **OK.** Every emitted file holds only counts, 16-hex ids, labels, the git version and hashes. git stderr stays in `$TMP/e`. Guard: rc 1 → `PASS`, rc 0 → `FAIL(n files)`, rc ≥ 2 → `FAIL(grep_rc)`, and both FAILs exit 1. Note: on FAIL the output stays in OUT, so the consumer must honour the exit code (NB-6). |
| Boundedness | **OK.** No file blob is read. Commit headers only (awk stops at the first blank line). Tag objects are read in full to find the trailing signature, which is a tag message rather than a header. That is minor and covered by the signature-presence justification. Cost is one full-history `log` per candidate path plus one `cat-file` per commit, with no cap (NB-8; performance, not safety). |
| `docs/knowledgeos/` exclusion | **OK.** Classifier (l.66, case-insensitive), E (`exclude,glob,icase`), and therefore `npaths` and W, which come from `cand`. N15 is disclosed. |
| Neutrality | **OK.** Headline tokens are neutral and defined in §5. No scoring. The by-type `SELF_ATTESTED` is the only substantive judgement embedded (BLK-1). |
| N9 / N12 / N6 / N3 / N14 | All verified as described. `-z` with `\001` carry, restored before pathspec and hashing. Tab-safe `index/substr` splitting. Ref scope limited. |

---

## Non-blocking

- **NB-1:** W, E and N_c run `git log` without `--full-history`. Default simplification prunes commits whose changes did not survive a merge. This can only reduce overlaps, so it can give an `INDEPENDENT…` that the `[aliases_unresolved]` qualifier does not cover. Add `--full-history`; with `--no-merges` the merge output does not change.
- **NB-2:** Environment hardening residuals:
  - `GIT_CONFIG` redirects `git config --get`. It could hide `extensions.partialClone`, which would bypass the capture-validity check, and it could misreport `core.hooksPath`.
  - `GIT_COMMON_DIR` redirects objects and refs.
  - `GIT_GRAFT_FILE` and `.git/info/grafts` rewrite history.

  Add the env vars to the `unset` list, and declare or refuse grafts.
- **NB-3:** The norm-register regex (l.89) has no trailing token boundary. `registration/`, `norms`, `normal…` and `decisionsupport…` match as long as some other K-STATUS token qualifies the path. `authoriz` alone is still unreachable (N13).
- **NB-4:** READMEs and other markdown inside the code roots count as executor work, which contradicts §3 ("READMEs … are not executor paths").
- **NB-5:** `cat-file commit | awk '…exit…'` under `pipefail`: for a commit object larger than the pipe buffer (~64 KiB), awk exits early, `cat-file` gets SIGPIPE, the pipeline fails and the signed commit is counted as unsigned. This extends N7.
- **NB-6:** When the identity guard fails, the files stay in OUT, and only the exit code and the `FAIL` line signal the problem. Declare this, or move OUT's content aside on FAIL.
- **NB-7:** In a linked worktree, K-LOCALHOOK checks `$GD/hooks` (per worktree), but the hooks live in the common dir. This is the N8 family.
- **NB-8:** There is no cap on per-path `git log` passes or per-commit `cat-file` spawns. A K-DEPLOY match on a large directory (e.g. `app/Services/Deploy/**`, `infra/**`) multiplies the runtime. Consider `cat-file --batch` and one `log --name-only` pass.
- **NB-9:** A single sweeping commit (repo-wide reformat or move) puts its author into W_c, N_c and E at once. That overlap is observed, not forced, but it should be disclosed next to the alias limit.
- **NB-10:** The positive Q7 can only be reached for K-DEPLOY, so the positive rests on one channel type. State this in §3. Also, CHANGELOG says ~169 lines; the script has 170 (trivial).
