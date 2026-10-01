# Targeted re-review of Protocol C r2 (`PROTOCOL-C.md`, `scope_c.sh`)

Basis: `REVIEW-TASK.md`, `TASK-r2.md`, `CHANGELOG-C-r2.md`, `HUMAN-SCOPE.md`, `PREREG.md`, `ENVIRONMENT.md`, `REVIEW-r1.md`, `CHANGELOG-C-r1.md`, `PROTOCOL-C.md` and `scope_c.sh` (all 231 lines, read line by line). Nothing was run. Line numbers refer to `scope_c.sh` r2.

## Verdict: **PASS_WITH_LIMITATIONS**

SCOPE-1, NB-1 and NB-5 are fixed as TASK-r2 specifies. No signature, verification or gpg field is read into a derived value or written to OUT. The guard and `inside()` now fail closed in every case I traced. I found no regression in GET-only, the bound, the call count, the status-gating rule, the identity policy or neutrality, and nothing that breaks HUMAN-SCOPE.md. There are no blocking issues. There are five non-blocking points (NB-r2-1 to NB-r2-5 below). Two are residuals of the repairs themselves: the signature-rule count can still be worked out from the output, and a failed harvest `jq` is not detected because there is no `pipefail`.

---

## 1. SCOPE-1: every occurrence of `verif`, `signat`, `gpg` (case-insensitive)

### scope_c.sh
| Line | Text | Kind |
|---|---|---|
| L4 | `# Signature / verification / gpg fields are out of scope by human decision (H2 = NO) and are never read.` | comment |
| L173 | `"types\t\(map(select(.type \| tostring \| test("signature\|verif\|gpg"; "i") \| not)) \| tally(.type))"` | exclusion filter on branch_rules rule **types** |

Nothing else in the script matches. In particular:
- **ch_commit (L84–85):** only `identity_mapping_available`, `author_eq_committer`, `author_date` and `committer_date` are left. `.commit.verification` is not referenced.
- **ch_protection (L166–169):** `required_signatures` is gone. The remaining keys are `scope`, `n_distinct_bases`, `required_pull_request_reviews`, `required_approving_review_count`, `enforce_admins`, `required_status_checks`, `push_restrictions` and `allow_force_pushes`.
- **timeline (L108–113):** reads only `.event`, `.author.date`, `.submitted_at` and `.created_at`.
- **checks, status, runs, rulesets and events:** read no such field. The tallies cover `conclusion`, run `event` and event `type`, and none of these can hold a signature value.
- **Identity-guard harvest (L216):** `.. | objects` walks every raw body, including `commit.verification`, but extracts only `.login` and `.name` from objects that have `email`. A verification object has neither, so nothing from it reaches `ids.txt`, and `ids.txt` never reaches OUT anyway.

### PROTOCOL-C.md
- L25: "**Signature and verification fields: out of scope by human decision.** The human declined signer verification (H2 = NO) … No `verification`, `signature` or `gpg` field is read or output:"
- L26: "not `commit.verification` (verified, reason, signature, payload) … `signature_verified` and `verification_reason` are removed from `ch_commit.tsv`;"
- L27: "not protection's `required_signatures`;"
- L28: "branch_rules: rule types matching `signature`, `verif` or `gpg` are left out of the `types` tally. They still count in `n_active_rules` …"

**Result: fixed.** §1 states that these fields are out of scope by human decision, as TASK-r2 item 1 requires. The one remaining read is L173, which looks at a rule's `type` value only in order to drop it. See NB-r2-1 for what can still be worked out from the output.

## 2. NB-1: guard traces (L216–224)

The ids.txt write (L216–218) is `{ jq … ; printf slug; } | awk | sort -u > ids.txt || exit 5`. Without `pipefail`, the status that counts is `sort`'s, including its redirect. `guard()` (L219–223) does three things:
1. requires `[ -f ] && [ -s ]` on ids.txt;
2. runs the fixed-pattern grep, and continues only on `rc == 1`;
3. runs the ids grep (`-wF -f ids.txt`), and passes only on `rc == 1`.

| Case | Trace | Fail-closed? |
|---|---|---|
| (a) grep exits 2 | Either grep sets `rc=2`, so `[ 2 -eq 1 ]` is false and the function returns 1. On the staged run (L224) the script prints "IDENTITY GUARD FAILED", exits 5, and OUT is never created (the `mkdir` is at L225). On the OUT run (L227) it exits 5 with "quarantine by hand". This also holds when grep finds a match and errors in the same run (still rc 2). | **Yes** |
| (b) ids.txt missing | If the write fails (the redirect cannot open the file, or `sort` fails), L218 exits 5 with "ids.txt not written; nothing written to OUT". If `awk` fails, the file is written empty, and `[ -s ]` makes guard return 1, so exit 5. If the file is missing or empty when guard runs, `[ -f ] && [ -s ] \|\| return 1`, so exit 5. | **Yes** |
| (c) raw login in OUT | Say a login `abc` (≥ 3 chars) appears in a staged file, for example in a base_ref `abc-patch-1`. With `-w`, `-` is a word boundary, so the ids grep matches, `rc=0`, guard returns 1, and the script exits 5 with nothing copied. An e-mail is caught by `@` in the first grep. | **Yes**, for any login or git name of ≥ 3 chars that the harvest extracted (see NB-r2-2 and NB-r2-4) |

**Result: fixed.** The `! grep` idiom is gone. Each grep is checked for exactly status 1, and the ids.txt write and its presence are both checked.

## 3. NB-5: `inside()` traces (L21–26)

`local a e` is declared before `e="$(git -C "$a" rev-parse --is-inside-work-tree 2>&1 >/dev/null)"`, so `&&` sees git's real exit status. The redirection order captures stderr and discards stdout. `LC_ALL=C` (L8) keeps git's messages in English and makes gettext ignore `LANGUAGE`.

| Case | Trace | Result |
|---|---|---|
| (a) normal non-repo dir, e.g. OUT=/tmp/x/out | Not under `$WTREE`. The loop walks up to the nearest existing dir, `/tmp`. git exits 128 with "fatal: not a git repository (or any of the parent directories): .git". `&&` is skipped, the `case` matches `*"not a git repository"*`, and the function returns 1 (outside). The script proceeds. | outside, as intended |
| (b) distrusted work tree (128, dubious ownership) | git exits 128 with "fatal: detected dubious ownership in repository at '…'" plus the safe.directory hint. That message does not contain "not a git repository", so the `*)` branch returns 0 (inside). The script prints "refused: OUT is inside a work tree (or git could not tell)" and exits 3. The same holds for TMP (L31). | refused, fail-closed |
| (c) repo work tree | git exits 0 and prints `true`, so `&& return 0` (inside) and the script exits 3. A path under WORKTREE_DIR is refused earlier, by the `case` at L23. Exit 0 with `false` (inside a `.git` dir, or with `GIT_DIR` set) also counts as inside, which errs on the safe side. | refused |

A directory git cannot enter ("cannot change to …") is also refused. **Result: fixed.** The residual (a work tree that git reports as "not a git repository") is disclosed in §4. See NB-r2-5.

## 4. Regression checks

**GET only: OK.** The network is reached only through `get()` (L36–48), as `gh api -X GET -i` (L41) or `curl -sS -X GET` (L44). There is no `-f`/`-F`, no `--paginate`, and no mutating subcommand. Every call:

| Key | Endpoint | When |
|---|---|---|
| probe_gh | `rate_limit` | gh present |
| probe_curl | `rate_limit` | curl mode |
| repo | `repos/REPO` | always |
| commit | `repos/REPO/commits/{sha}` | repo 200 |
| pulls | `repos/REPO/commits/{sha}/pulls?per_page=5` | repo 200 |
| pr{n} | `repos/REPO/pulls/{n}` | per PR, ≤ 5 |
| rv{n} | `repos/REPO/pulls/{n}/reviews?per_page=100` | per PR, ≤ 5 |
| tl{n} | `repos/REPO/issues/{n}/timeline?per_page=100` | per PR, ≤ 5 |
| checks | `repos/REPO/commits/{sha}/check-runs?per_page=100` | repo 200 |
| status | `repos/REPO/commits/{sha}/status?per_page=100` | repo 200 |
| runs | `repos/REPO/actions/runs?head_sha={sha}&per_page=100` | repo 200 |
| rsets | `repos/REPO/rulesets?per_page=100&includes_parents=true` | repo 200, always |
| prot | `repos/REPO/branches/{base}/protection` | base derivable |
| brules | `repos/REPO/rules/branches/{base}?per_page=100` | base derivable |
| ev{1..3} | `repos/REPO/events?per_page=100&page={p}` | repo 200; stops on a short page, a non-200, or reaching W's start |

The endpoint set is unchanged from r1, and the commit GET stays in it.

**Bound: OK.**
- The repo, the SHA and the window are constants (L9–10).
- PRs are capped by `per_page=5` and `.[:5]`.
- There is one base branch, from `PRS[0]`.
- The window is used only as a filter.

**Calls ≤ 40: OK.**
- `BUDGET=40` is checked before `N++` (L38).
- The most calls possible is 2 + 1 + 1 + 1 + 15 + 3 + 1 + 2 + 3 = **29**.

**Status gating: OK.**
- `kv` (L63–65) turns every value into NA unless the status is 200 (only `scope` and `n_distinct_bases` are exempt, as in r1).
- `lab` gives `NOT_EVIDENCED_ON_HOST` only on a 200.
- The no-PR branch is taken only on 200 `[]`.
- The commit-author relations are gated on `$cs`.

The SCOPE-1 changes keep this intact:
- Dropping keys from ch_commit and ch_protection does not affect their labels: crow commit uses count 1, and protection uses its own status.
- branch_rules takes its existence from `n_active_rules`, which still counts every rule. A 200 whose only rule is `required_signatures` is labelled `EXISTS`, not `NOT_EVIDENCED_ON_HOST`, so no failure is read as absence. (See NB-r2-3 for one latent case on a jq build without regex.)

**Identity policy: OK.**
- The removed keys held no identity.
- OUT still carries equality relations and counts only.
- Roles stay in `CA`/`PA` and the temp dir (N8 disclosed).
- The guard is now fail-closed (§2 above).

**Neutrality: OK.**
- Norm state and independence are still constants in `row()`.
- The new texts ("out of scope by human decision", "(or git could not tell)") are factual and interpret nothing.

**Human scope (HUMAN-SCOPE.md): OK.**
- Signer investigation is removed (SCOPE-1).
- There is no search, no other commits, no logs, and no endpoint outside the frozen set.
- Identity appears only as equality relations, which PREREG allows.
- rulesets and events are repository-scoped calls. They stay within PREREG's bound (one repository, one window), output only aggregate counts, and look at no unrelated commit or PR individually. See NB-r2-5 for a note for the human gate.

## Blocking issues

None.

## Non-blocking issues

- **NB-r2-1. The signature-rule count can be worked out from the output (L172–173).**
  - **Defect:** `n_active_rules` counts every rule, while `types` drops `required_signatures`. So `n_active_rules − Σ types` is the number of signature/verification rules on the base branch.
  - **Assessment:** this is branch configuration, not signer verification, and §1 L28 discloses it ("still count in `n_active_rules`"). It was kept on purpose to avoid a false absence.
  - **Ask:** the human or main analyst should confirm that this residual is acceptable under H2 = NO.
- **NB-r2-2. A failed harvest `jq` is not detected (L216–218).**
  - **Defect:** there is no `set -o pipefail`, and jq's stderr goes to `/dev/null`. If jq fails, the `printf` still writes the owner slug, so ids.txt is non-empty and passes `[ -s ]`, but its login/name list is incomplete. A bare login in OUT would then match neither grep.
  - **Likelihood:** low, because every raw `.json` is validated or replaced with `{}` in `get()`. NB-1 as specified (grep errors, ids.txt missing or not written) is fully met.
  - **Fix:** check jq's status, for example with `PIPESTATUS` or `pipefail`.
- **NB-r2-3. The L173 `test()` depends on jq's regex support (new in r2).**
  - **Defect:** on a jq built without oniguruma, `test/2` is undefined, which is a compile error. The filter would then output nothing, `n_active_rules` would be empty, and `lab 200 ""` would give `NOT_EVIDENCED_ON_HOST`. That is a failure read as absence.
  - **Likelihood:** low; standard distro jq includes regex.
  - **Fix:** use `ascii_downcase | contains(…)`, or add a startup probe.
- **NB-r2-4. Inherited identity-guard gaps (not regressions).**
  - `awk 'length >= 3'` drops logins of 1–2 chars.
  - `-F` without `-i` misses case variants of a login.
  - A failure on the second, post-copy guard leaves data in OUT for manual quarantine. This is unlikely, because the staged copy already passed.
- **NB-r2-5. Residuals to state at the human gate.**
  - **`inside()`:** "not a git repository" also covers a broken linked work tree ("not a git repository: …/.git/worktrees/x"), which reads as outside. This falls under the general §4 residual; matching "not a git repository (or any" would narrow it.
  - **Human scope:** the events call fetches up to 300 repository events, unrelated ones included, before the window filter. The output is aggregates only and is within PREREG's bound, but HUMAN-SCOPE excludes "repository-wide host research", so the human should explicitly confirm it at the gate.
- The code comment at L215 ("cannot trip it") still overstates, as NB-4 does. It is disclosed in §6, and the task left it unchanged.
