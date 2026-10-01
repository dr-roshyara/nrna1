# Review — PROTOCOL-B.md + scope_b.sh

**Verdict: FAIL.** The script must not run as written.

Sources used: REVIEW-TASK.md, TASK.md, QUESTIONS.md, ENVIRONMENT.md, ROOTS.txt, REFERENCE-r2-scoping-script.sh, PROTOCOL-B.md, scope_b.sh (read line by line). Nothing was executed.

The two known defects are fixed as far as they go. The self-path is made absolute and hashed before the only `cd` (l.15–17, 36). The executor set E no longer includes the channel's own *current* paths (l.71–74). The independence computation still has construction artefacts, though. They produce negatives that come from how the sets are built, not from observed cross-role authorship. That breaks QUESTIONS.md's "never a forced negative" rule and the protocol's own §3 guarantee. One more blocking defect makes the output depend on the checked-out branch.

---

## Blocking issues

**B1: Channel writer set W and commit count are computed from `HEAD` only, but E and N use all refs (l.91–92 vs l.74, 79, 102).**
`git log --no-merges … -- ":(literal)$p" < /dev/null` has no `--stdin` and no tips, so it walks `HEAD` only. Paths are found at *any* tip (l.53). E and N are computed over every tip.
- Rerunning on the same ref set gives different `nc`, `nw`, `overlap_*` and Q7 values depending on which branch is checked out. HEAD is not recorded in the output. This breaks determinism and makes Q8 `YES_LOCAL(… rerunnable on same ref set)` false.
- A path that exists only on a non-HEAD branch gets `W=∅`. It is then reported as `IND[writer_overlap=0…]`, which claims zero overlap when the writers were never looked up. `CONTROLLED_BY_WRITERS[n=0]` with `paths>0` is also reachable.

**B2: K-STATUS is forced to `NORM_SOURCE_NOT_INDEPENDENT` by construction (l.77–81, 97, 129).**
N is the writer set of a *subset of K-STATUS's own paths* (rulings/register/decision/norm…). W(K-STATUS) is the writer set of all K-STATUS paths, so W ⊇ N, and `overlap_norm = |N| > 0` whenever any register-type path has a writer. The whole channel, including non-register records such as `docs/implementation/PROGRAM_STATUS.md` (TASK.md's own example, listed separately from the rulings register), can never reach `IND` once a register path exists. This mirrors the known defect: the channel's writers are norm-makers by construction. The same overlap appears for any path that matches both K-DEPLOY and the N regex (e.g. `release/decisions.md`).
*Fix direction:* split the norm source (register/rulings) from the other status records. Compute W for a channel over paths not in N's path set. Compare W against N computed over the *other* channels' paths only.

**B3: The channel's own writers still leak into E through historic paths, which brings back the known defect (l.53–54, 71–74).**
The path universe holds tip paths only, so E excludes only the *current* names of candidate files. Any commit that touched a status/register/CI/deploy file under an earlier name (renamed, moved or deleted before the tips) counts as "executor" work. A rename commit `docs/STATUS.md → docs/implementation/PROGRAM_STATUS.md` changes the old path, which is not excluded, and the new path, which is excluded. Its author therefore lands in both E and W from one channel-only commit, giving `overlap_exec ≥ 1` → `NOT_INDEPENDENT[overlap_exec]` with no executor work involved. W uses no `--follow`, so this is asymmetric. (`docs/knowledgeos/**` is excluded by glob over all history, so the leak does not affect that directory.)

**B4: E does not measure "executor of the work", so `NOT_INDEPENDENT[overlap_exec]` is close to forced (l.74, 91).**
- E and W both include **committer** e-mails (`%ce`). On a GitHub remote (ENVIRONMENT.md), squash merges, web edits and UI rebases get a shared platform committer identity. That identity appears in E and in W for every channel touched through the UI, which yields `overlap_exec ≥ 1` (and `overlap_norm ≥ 1`) from the host mechanism rather than from authorship. Integrators and reviewers who commit or rebase others' work count as executors by construction, even though they are a candidate independent control.
- E covers *every* non-candidate path, including documentation and governance text outside the narrow K-STATUS vocabulary (`docs/`, `developer_guide/`, `engineering/`, `.claude/`, `claude/`, READMEs; see ROOTS.txt). Norm-makers who write any such document count as executors. The reference script had at least excluded doc trees and markup from E.
- Result: for K-STATUS (when N=∅) and K-DEPLOY, `NOT_INDEPENDENT[overlap_exec]` is close to certain whenever the channel has writers. PROTOCOL-B §3's claim that a NOT_INDEPENDENT verdict "arises only from genuinely observed cross-role authorship" does not hold.

**B5: Q5 (and Q2) assert a negative for K-DEPLOY that existence does not decide (l.125–127).**
K-DEPLOY is grouped with the configuration rules (`cfg=…||ch=="K-DEPLOY"`). It therefore gets Q2 = `CONFIG_RULE` and Q5 = `NO_BY_TYPE(configuration rule, not a norm-state record)`. TASK.md defines this channel as deployment or configuration **state** files, and the pattern also matches release notes and `tfstate`. Without reading content, whether such a record can show what was in force at t is `NOT_DETERMINABLE_BY_EXISTENCE_CHECK`, not `NO`. This is a forced negative. (The same type-argument for K-CI/HOOK/CODEOWNERS/K-SIG is weaker but defensible; see N1.)

---

## Non-blocking issues / limitations

- **N1:** Q5 `NO_BY_TYPE` for K-CI, K-HOOK, K-CODEOWNERS, K-SIG and K-LOCALHOOK is a type argument made without reading content. For example, a CI gate at commit t *is* the enforced rule at t. Prefer `ND`, or state the argument explicitly as a declared assumption.
- **N2:** `INDEPENDENT` (positive) cannot be reached for any channel in Q7. Q6 has one outcome (`ND`). The headline (l.154) is a constant written before any data is seen. Under existence-only scoping this is defensible, since QUESTIONS.md says to use IND when independence cannot be established. The protocol should still say plainly that a positive cannot be reached locally, and that a constant headline is not a finding.
- **N3:** The path universe holds tip paths only, so a channel that was deleted or renamed before the tips is reported as `NOT_EVIDENCED_IN_CAPTURE` even though it is in the captured history. The label should say "at tips", or the universe should be built from history (as in the reference).
- **N4:** Config isolation covers only system and global config. Repo-local config is read: `log.follow=true` would change l.91–92, which use a single pathspec. Also, `GIT_CONFIG_PARAMETERS`/`GIT_CONFIG_COUNT` are not unset, and the reference's `core.fsmonitor=false`/`log.decorate`/`diff.renames` overrides were dropped. The commands used do not refresh the index, so fsmonitor is not triggered.
- **N5:** The peeled tag type is not checked (l.50). A tag that points to a tree or blob becomes a "tip", and `git log --stdin` then fails, leaving E and N empty. The failure is logged (exit 1), but every overlap silently becomes 0.
- **N6:** The identity guard fails open: a `grep` error (rc 2) prints `PASS` (l.162–166). The protocol promises fail-closed.
- **N7:** Errors from `cat-file` in the signature loops (l.105, 109) are not checked, and failures count as unsigned. Tags are counted per ref, not per object. Tags carrying x509 `SIGNED MESSAGE` signatures are missed.
- **N8:** In a linked worktree, OUT is checked against the per-worktree gitdir but not against `git rev-parse --git-common-dir`.
- **N9:** `ls-tree` runs without `-z`. Paths containing `"`, `\`, tab or newline are C-quoted, so the `:(literal)` pathspec misses them and the ID is computed over the quoted string.
- **N10:** Path IDs are unsalted SHA-256 prefixes of predictable strings (e.g. `.github/workflows/ci.yml`), so they can be reversed with a dictionary. This is not an identity leak, but the claim "no path strings leave" is weaker than stated.
- **N11:** Writes outside OUT: `mktemp -d` in `$TMPDIR` holds plaintext e-mails. It is removed by the EXIT trap, but not on SIGKILL. `mkdir -p` can create missing parents of OUT. Both are declared and acceptable, but should be documented.
- **N12:** The "local ref set" includes `refs/stash` and `refs/notes/*`, so results depend on the local stash. Notes trees add hex-named paths to the universe.
- **N13:** Protocol and script disagree in places:
  - Q4 drops the `content timestamps = ND` qualifier given in §2.
  - K-SIG gets no overlap counts, although §3 says "with the overlap counts attached".
  - K-LOCALHOOK Q4 = `NONE` is not in the §2 vocabulary.
  - Q8 `YES_LOCAL` covers reproducibility only; *independent verification* is not shown.
  - The `ND` branch for Q2 (l.126) is dead code.
  - The N token `authoriz` is reachable only if another K-STATUS token also matches.
  - The script is 167 lines (TASK.md: under ~150).
- **N14:** Neutrality: the headline token `NIF_A_…` is not defined in any input file and refers to an outside hypothesis label. Rename it to a neutral token, or define it.
- **N15:** Self-contamination: the exclusion covers top-level `docs/knowledgeos/` only, which meets the spec. ROOTS.txt also lists `.claude` and `claude`. If those hold research-programme artefacts, they become candidates. Confirm, or declare.

---

## Audit by criterion

| # | Criterion | Result |
|---|---|---|
| 1 | Read-only safety | **OK.** Only read-only git plumbing and porcelain is used: `rev-parse`, `config --get`, `for-each-ref`, `ls-tree`, `log`, `rev-list`, `cat-file commit/tag`, `--version`. There is no fetch (`GIT_NO_LAZY_FETCH=1`, partial clone refused), no config change and no index or ref write. The only writes are OUT, `$TMP` (removed) and possibly parents of OUT created by `mkdir -p`. |
| 2 | Determinism | **FAIL (B1).** `LC_ALL=C`, `TZ=UTC`, C-locale sort, system/global config isolation, self-path resolved before `cd`, and OUT refused inside the work tree and `.git`: all OK. However, the output depends on the checked-out HEAD, and repo-local config is not isolated (N4). |
| 3 | Boundedness | **OK.** Existence, counts and writer sets only. No file blob is read. Commit and tag headers are read for signature presence, which is justified for K-SIG Q1/Q2/Q7. |
| 4 | Reachability | **FAIL (B2, B5).** Q7 is forced to NORM_SOURCE for K-STATUS. Q5 `NO` for K-DEPLOY is asserted without content. Q7 `NOT_INDEPENDENT[overlap_exec]` is close to forced (B3, B4). Q7 `INDEPENDENT` cannot be reached (by design; N2). |
| 5 | Independence rule | **FAIL (B2, B3, B4).** Current channel paths are correctly kept out of E, but writers leak into E through historic paths, committers and the doc trees. N ⊆ W for K-STATUS. A self-attested record is **not** accepted because of a timestamp: Q4 = `NO_CLIENT_SET`, and Q7 never uses timestamps. That part is OK. |
| 6 | Self-contamination | **OK.** `docs/knowledgeos/` is excluded case-insensitively from candidates, from E (over all history) and therefore from N. See N15. |
| 7 | Identities | **OK.** Only counts, IDs, labels, the git version and hashes are written. Identities stay in `$TMP`, and the guard greps for `@` and signature markers (but see N6, N10). |
| 8 | Neutrality | **OK, with caveats.** There is no scoring or conformance judgement. The constant headline uses the undefined token `NIF_A` (N14, N2). |

## Command inventory (scope_b.sh)

- **Shell built-ins:** `set`, `export`, `unset`, `printf`, `read`, `mapfile`, `cd` (once, l.36), `trap`, `[`/test, `case`, `:` (truncating files in OUT and TMP).
- **Coreutils and related:** `realpath` (l.16, 28, 29, 32), `sha256sum` (l.17, 43, 89), `cut`, `ls -A` (l.31), `mktemp -d` (l.32), `rm -rf "$TMP"` (l.33, trap), `mkdir -p "$OUT"` (l.35), `mv` within TMP (l.54), `sort`, `tr`, `comm`, `awk`, `grep` (l.109, 162), and `find | sort | xargs sha256sum` in OUT (l.43).
- **git** (each call wrapped with `-c core.quotepath=off -c log.showsignature=false -c log.mailmap=false -c color.ui=never`):
  - `rev-parse --is-inside-work-tree`, `--is-bare-repository`, `--show-toplevel`, `--absolute-git-dir` and `--is-shallow-repository`
  - `config --get extensions.partialClone` and `config --get core.hooksPath` (read only)
  - `--version`
  - `for-each-ref --format=…`
  - `ls-tree -r --name-only --full-tree <tip>`
  - `log --stdin --no-merges --format=%ae%n%ce -- . <excludes>` (E)
  - `log --stdin --no-merges --format=%ae%n%ce -- <N paths>` (N)
  - `log --no-merges --format=%ae%n%ce -- :(literal)p` and `log --no-merges --format=%H -- :(literal)p` (**HEAD only**, B1)
  - `rev-list --stdin`
  - `cat-file commit <c>` and `cat-file tag <o>`
- **File test:** `-e "$GD/hooks/<name>"`.

Nothing writes outside OUT except `$TMP` and possible parents created by `mkdir -p`. Nothing fetches. Nothing changes config.
