# Protocol B (r3) — minimal, neutral scoping of candidate norm-provenance channels

**Goal (from QUESTIONS.md):** decide, for a *bounded* list of locally checkable, read-only
channel types, whether any could be an **independent** record of *which norm was in force at
time t*. "Independent" = controlled by someone/something other than (a) the norm-maker,
(b) the executor of the work, (c) the ruling asserting its own effect. Existence/scoping only —
**no archaeology**, no evidence acquisition, **never a forced negative**.

The research programme's own artefacts (`docs/knowledgeos/**`) are **never** candidates
(self-contamination) and are excluded by the classifier and from E.

## 1. Candidate channels (exact patterns)

Doc-like ext = `md,markdown,txt,rst,json,ya?ml,toml,csv,tsv`. Every token match below (K-NORMREG,
K-STATUS, K-DEPLOY) uses the leading boundary `(^|[/._-])` and the trailing token boundary `([._/-]|$)`
(BLK-2). A token can therefore start a path segment, or it can follow `.`, `_` or `-` inside a name
(e.g. `ADR-0007-Rulings.md`, `PROGRAM_STATUS.md`). It must end at `.`, `_`, `/`, `-` or the end of the path.

| Id | What | Path pattern / source (case-insensitive) |
|----|------|-------------------------------------------|
| `K-CI` | CI definitions | `.github/workflows/**`, `.gitlab-ci.yml`, `Jenkinsfile`, `.circleci/**`, `azure-pipelines.yml`, `.drone.yml`, `bitbucket-pipelines.yml`, `.woodpecker*` |
| `K-HOOK` | repo-shipped git hooks | `.githooks/**`, `.husky/**`, `.pre-commit-config.yaml`, `lefthook.yml`, `.lefthook.yml` |
| `K-CODEOWNERS` | ownership / branch-protection hints in repo | path basename `CODEOWNERS` |
| `K-NORMREG` | norm register / rulings register / in-force record (**outside** `docs/knowledgeos/`) | doc-like ext and path matching the **register regex** `(^|[/._-])(rulings?|register|registry|decisions?|norm|inforce|authoriz(ation|ations)?)([._/-]|$)` |
| `K-STATUS` | the **remaining** status / progress / approval / sign-off records (**outside** `docs/knowledgeos/`); **disjoint** from `K-NORMREG` | doc-like ext, **not** `K-NORMREG`, path matching `(^|[/._-])`+token+`([._/-]|$)` for a token in {`program_status,status,ruling(s),register,registry,decision(s),adoption(s),approval(s),signoff(s),progress,inforce,norm`}, e.g. `docs/implementation/PROGRAM_STATUS.md` |
| `K-DEPLOY` | deployment / config **state** | path token (`(^|[/._-])`+token+`([._/-]|$)`) in {`deploy,deployment,release,environment,env,infra,terraform`} or ext `tfstate` |
| `K-SIG` | signed commits / tags | **presence** of `gpgsig` header / `-----BEGIN (PGP\|SSH) SIGNATURE-----`; keyring **not** consulted, signatures **not** verified |
| `K-LOCALHOOK` | local hooks (`LOCAL_ONLY`) | `.git/hooks/<standard-name>` present, or `core.hooksPath` set |

**E-exclusion globs = classifier regex, by construction (BLK-2).** For each doc token t (the union
of the K-NORMREG and K-STATUS tokens, with `authoriz`, `authorization`, `authorizations` written out) and
each doc ext x, E excludes (`:(exclude,glob,icase)`):
- token at segment start: `**/$t.$x`, `**/$t[._-]*.$x`, `**/$t/**/*.$x`, `**/$t[._-]*/**/*.$x`;
- token after `.`/`_`/`-`: `**/*[._-]$t.$x`, `**/*[._-]$t[._-]*.$x`, `**/*[._-]$t/**/*.$x`, `**/*[._-]$t[._-]*/**/*.$x`.

For each K-DEPLOY token t it excludes `**/$t`, `**/$t/**`, `**/$t[._-]*`, `**/$t[._-]*/**`, the same four
forms with `*[._-]` in front of t, and also `**/*.tfstate`.

The glob set and the regex are **equivalent by construction**. Each glob form is exactly one case of
the regex:
- the leading boundary is either `^`/`/` (→ `**/`) or `.`/`_`/`-` (→ `**/*[._-]`);
- the trailing boundary is one of: the extension dot of the basename (→ `.$x`); `.`/`_`/`-` inside the
  basename (→ `[._-]*.$x`); `/` (→ `/**/*.$x`); `.`/`_`/`-` inside a directory segment (→ `[._-]*/**/*.$x`).

For doc tokens, the path-end case `$` cannot occur: the path must end in `.$x`, and no token is a doc
extension. For K-DEPLOY, the path-end case is the bare `$t` form. The construction relies on these git
wildmatch semantics: `*` does not cross `/`; `**/` and `/**/` match zero or more directories; `icase`
is ASCII case folding, like awk `tolower` under `LC_ALL=C`. **No residual mismatch is known at the
pattern level.** The equivalence is argued, not executed. By design (§3), the globs are applied to
historic paths and the regex to tip paths.

**Host-side channels — `NOT_CHECKABLE_LOCALLY`** (host not authorized): `K-HOST-AUDIT`
(server audit logs), `K-HOST-BRANCHPROT` (branch-protection *enforcement*), `K-HOST-PR-APPROVALS`
(PR approvals), `K-CI-RUNS` (CI run history), `K-HOST-MEMBERS` (org/team membership).

**The one tip set (B1, N5, N12).** Every history query (paths, W, commit counts, E, N_c, K-SIG)
runs over **one** tip file via `git log --stdin` / `git rev-list --stdin`. Tips = refs under
`refs/heads/**`, `refs/remotes/**`, `refs/tags/**` only (`refs/stash`, `refs/notes/**` excluded);
tags are peeled (`^{commit}`), and **only commit-type tips** are kept. The result does not depend
on the checked-out HEAD. Paths are read with `ls-tree -r -z` (N9).

## 2. Deterministic answer rules

Scope = the **tip set only**; completeness vs the host is never shown. `ND` =
`NOT_DETERMINABLE_BY_EXISTENCE_CHECK`, `IND` = `INDEPENDENCE_NOT_DETERMINABLE`. If a path channel
has **0** paths in the tip trees → Q1 = `NOT_EVIDENCED_AT_TIPS` (it may still exist in history
behind the tips) and Q2..Q8 = `NOT_APPLICABLE(Q1)`; same label for `K-SIG` when no signature is
present in commits reachable from the tips or in tag objects at the tips; `K-LOCALHOOK` uses
`NOT_EVIDENCED_LOCALLY`. Host rows are `NOT_CHECKABLE_LOCALLY` for all eight.

- **Q1 exists** → `YES_IN_CAPTURE` | `YES_LOCAL_ONLY` (`K-LOCALHOOK`) | `NOT_EVIDENCED_AT_TIPS` | `NOT_EVIDENCED_LOCALLY` | `NOT_CHECKABLE_LOCALLY`.
- **Q2 records** → by **type** only: `CONFIG_RULE` (K-CI, K-HOOK, K-CODEOWNERS, K-LOCALHOOK), `NORM_REGISTER_RECORD` (K-NORMREG), `STATUS_RECORD` (K-STATUS), `STATE_RECORD_BY_TYPE` (K-DEPLOY), `IDENTITY_ATTESTATION` (K-SIG); content not read.
- **Q3 controls** → `CONTROLLED_BY_WRITERS[n=…]` (count only) | for `K-SIG` `ND(keyring not consulted)` | `LOCAL_MACHINE` (`K-LOCALHOOK`).
- **Q4 timestamps independent** → `NO_CLIENT_SET` (git recording times are client-generated; a self-attested timestamp is **not** independence). Content timestamps = `ND`.
- **Q5 norm-in-force at t** → `ND` for **every** channel: no channel's content is read, and existence never decides this. (`NO_BY_TYPE` removed.)
- **Q6 completeness** → `ND[COMPLETE_VS_HOST=NOT_SHOWN]`. **Q6 has a single outcome by design**: the check is existence-only over the tip set, so completeness vs the host can never be shown.
- **Q7 independence** → see §3. Reachable: `INDEPENDENT_AT_IDENTITY_COUNT_LEVEL[aliases_unresolved]`, `IND[…]`, `NOT_INDEPENDENT[…]`, `SELF_ATTESTED[…]`, `NOT_APPLICABLE(Q1)`, `NOT_CHECKABLE_LOCALLY`.
- **Q8 reproducible** → `YES_LOCAL` (content-addressed, rerunnable on the same tip set) | `NO_LOCAL_ONLY` (`K-LOCALHOOK`, unversioned) | `NOT_CHECKABLE_LOCALLY`.

## 3. Independence rule (count overlap only)

All sets use the **author** e-mail `%ae` only (never `%ce`), lower-cased, over non-merge commits
reachable from the one tip set. Every W, E and N_c `git log` call uses `--full-history` (NB-1), so
no history simplification prunes commits. Identities never leave a private temp dir; only counts do.

- **W_c** = authors of commits touching candidate c's own paths.
- **E** (executors) = authors of commits touching the **CODE / TEST roots of Protocol A r1**:
  `app/ routes/ database/ resources/ config/ bootstrap/ lang/ public/ tests/`. Root pathspecs are
  used, so historic, renamed and deleted paths under those roots count. **Excluded** (as
  `:(exclude,glob,icase)` pathspecs): every candidate-channel **glob pattern** of §1 (a glob
  translation of the classifier, not the tip path names) and `docs/knowledgeos/**`. Doc and
  governance roots (`docs/`, `developer_guide/`, `engineering/`, `.claude/`, `claude/`,
  `scripts/`, `storage/`, READMEs) are **not** executor paths.
- **Norm-register paths** = the `K-NORMREG` paths (register regex, §1).
- **N_c** = authors of commits touching the norm-register paths **minus c's own paths**. N is
  never derived from the candidate's own paths. For `K-STATUS` (disjoint from `K-NORMREG` by
  construction) N_c = all `K-NORMREG` paths; for `K-NORMREG` N_c's path set is empty.
- `overlap_exec = |W_c ∩ E|`, `overlap_norm = |W_c ∩ N_c|` (`NA` when N_c's path set is empty).

Mapping, applied in this order:
1. `K-NORMREG` → `SELF_ATTESTED[norm_register;overlap_exec=…]`. This is a **definitional outcome,
   not an observed negative**: by declared definition a register records its own rulings, so it is
   criterion (c), "the ruling asserting its own effect" (QUESTIONS.md: a self-attested record is not
   independent merely for having a timestamp). No data is consulted for this outcome; it is not a
   `NOT_INDEPENDENT` and is never counted as one (§5).
2. N_c's path set is empty → `IND[no_norm_source_distinct_from_candidate;…]`.
3. Mechanism **unobservable locally** (`K-CI`, `K-HOOK`, `K-CODEOWNERS`: enforcement is host/client-side;
   `K-SIG`: signatures unverified; `K-LOCALHOOK`: local, unversioned) → `IND` with the overlap counts where computed.
4. Mechanism observable (`K-STATUS`, `K-DEPLOY`: writer set in repo) and any of W_c, N_c, E empty → `IND[empty_set;W=…;N_c=…;E=…]`.
5. Observable, `overlap_norm>0` → `NOT_INDEPENDENT[overlap_norm]`; else `overlap_exec>0` → `NOT_INDEPENDENT[overlap_exec]`
   (an **observed** cross-role authorship).
6. Observable, W_c ≠ ∅, N_c ≠ ∅, E ≠ ∅ and both overlaps `0` →
   **`INDEPENDENT_AT_IDENTITY_COUNT_LEVEL[aliases_unresolved]`** (qualified positive: no shared author
   identity is observed; it does not show who controls the channel on the host).

**Channels that can reach a positive Q7 (NB-10):** only `K-STATUS` and `K-DEPLOY` (the observable
channels, rules 4–6), and only when at least one `K-NORMREG` path exists outside their own paths.
`K-NORMREG` is always `SELF_ATTESTED`; `K-CI`, `K-HOOK`, `K-CODEOWNERS`, `K-SIG` and `K-LOCALHOOK` are
always `IND`. The positive therefore rests on two channel types.

**Disclosed limits of §3:** identity aliases (one person, several e-mails) are **not** resolved, so
overlaps may be undercounted and the positive is qualified accordingly; a rename commit spanning a
code root and a channel path is still counted in E (and in W_c).

## 4. Completeness rule

**Checkable locally:** existence of the paths/hooks above in the tip set; per-channel commit count,
writer **count** and signature **presence**; writer-overlap counts. **Not checkable locally
(declared):** branch-protection enforcement, PR approvals, server audit logs, CI run history, org
membership, host push times, and whether the local ref set is complete vs the host. All of these
are `NOT_CHECKABLE_LOCALLY` / `COMPLETE_VS_HOST=NOT_SHOWN`; the content of any file (what a rule
or record *says*) is `ND` because content is not read.

## 5. Output (written by `scope_b.sh` into an empty `$OUT` outside the repo)

- `60_channel_metrics.tsv` — per channel (`K-CI, K-HOOK, K-CODEOWNERS, K-NORMREG, K-STATUS, K-DEPLOY`): `channel, paths, commits, writers, overlap_exec, overlap_norm, norm_paths_c, norm_writers_c` (counts only).
- `61_path_ids.tsv` — `16-hex-id → channel` (no path strings leave the repo).
- `58_signatures.tsv` — `commits_total, commits_signed, tags_signed`.
- `56_local_hooks.tsv` — `standard_hooks_present, core_hooksPath`.
- `70_answers.tsv` — candidate × Q1..Q8 (the §2/§3 mapping), incl. `K-SIG`, `K-LOCALHOOK`, host rows.
- `71_overall.tsv` — `headline` **computed from the per-channel Q7 values** (local rows), first match wins:
  1. `LOCAL_INDEPENDENT_CANDIDATE_AT_IDENTITY_COUNT_LEVEL` — ≥1 Q7 = `INDEPENDENT_AT_IDENTITY_COUNT_LEVEL…`;
  2. `LOCAL_INDEPENDENCE_NOT_DETERMINABLE` — else ≥1 Q7 = `IND…`;
  3. `LOCAL_NO_INDEPENDENT_CANDIDATE_OBSERVED` — else ≥1 Q7 = `NOT_INDEPENDENT…` (observed overlap only);
  4. `LOCAL_ONLY_SELF_ATTESTED_RECORDS` — else ≥1 Q7 = `SELF_ATTESTED…`: the only evidenced local
     candidates are self-attested records (definitional, §3 rule 1); **no** independence outcome was
     observed, neither positive nor negative. `SELF_ATTESTED` is counted in this bucket only, never in
     `LOCAL_NO_INDEPENDENT_CANDIDATE_OBSERVED`;
  5. `LOCAL_NO_CANDIDATE_EVIDENCED` — else (all local rows `NOT_APPLICABLE(Q1)`).

  The headline covers local channels only; host channels stay `NOT_CHECKABLE_LOCALLY`. Also:
  `complete_vs_host=NOT_SHOWN`, per-outcome Q7 counts (incl. `self_attested`), `not_evidenced`, `host_channels`, `identity_guard`.
- `00_script.sha256`, `00_env.tsv` (incl. `repo_local_config=READABLE`), `00_errors.tsv`, and `SHA256SUMS` over every emitted file.

**Guarantees:** read-only; `LC_ALL=C TZ=UTC`; config isolation (N4, NB-2): system/global config off,
`GIT_CONFIG`, `GIT_CONFIG_PARAMETERS`, `GIT_CONFIG_COUNT` and `GIT_COMMON_DIR` unset,
`GIT_GRAFT_FILE=/dev/null` (neutralizes `.git/info/grafts`), and every git call gets `-c log.follow=false
-c diff.renames=false -c log.decorate=false -c core.fsmonitor=false -c log.showSignature=false`
(plus `core.quotepath=off`, `log.mailmap=false`, `color.ui=never`); **the repo-local config stays
readable** (declared). Pathspec and trace isolation (NB-11): `GIT_LITERAL_PATHSPECS`,
`GIT_ICASE_PATHSPECS`, `GIT_GLOB_PATHSPECS`, `GIT_NOGLOB_PATHSPECS` and every `GIT_TRACE*` /
`GIT_TRACE2*` variable are unset. The trace variables are unset by name (`GIT_TRACE`, `GIT_TRACE2`,
`GIT_TRACE2_EVENT`, `GIT_TRACE2_PERF`, `GIT_TRACE_PACKET`, `GIT_TRACE_PERFORMANCE`, `GIT_TRACE_SETUP`,
`GIT_TRACE_CURL`, `GIT_TRACE_PACK_ACCESS`) and also by prefix, so any other `GIT_TRACE…` variable is
removed too. No `set -e` (explicit exits 0/1/2/3); own absolute path resolved+hashed
**before** the one `cd`; refuses any `$OUT` inside the work tree or `.git`; emits **counts only** —
no names, emails, ref names, URLs, owners or path strings. A final `identity_guard` greps `$OUT` for
`@`/signature markers and fails **closed**: a hit → `FAIL`, any grep error (rc ≥ 2) → `FAIL(grep_rc)`,
both exit 1. The **only** content read beyond existence/size/counts is commit/tag object headers for
**signature presence** (Q1/Q2/Q7 for `K-SIG` strictly require it); no file blob is read.

## 6. Disclosed limitations (not fixed)

- **N7:** `cat-file` errors in the signature loops are not checked (failures count as unsigned); tags are counted per ref, not per object; x509 `SIGNED MESSAGE` tag signatures are missed.
- **N8:** in a linked worktree, OUT is checked against the per-worktree gitdir only, not `git rev-parse --git-common-dir`.
- **N10:** path IDs are unsalted SHA-256 prefixes, so predictable paths can be reversed by dictionary (no identity leak).
- **N11:** `$TMP` holds plaintext e-mails (removed on EXIT, not on SIGKILL); `mkdir -p` may create missing parents of OUT.
- **N13:** remaining protocol/script mismatches: Q4 omits the content-timestamp qualifier in the output; K-SIG carries no overlap counts; K-LOCALHOOK Q4 = `NONE`; Q8 shows reproducibility, not independent verification; script length.
- **N15:** only top-level `docs/knowledgeos/` is excluded as self-contamination; whether `.claude/` / `claude/` hold programme artefacts is not checked.
- **NB-4:** READMEs and other markdown *inside* the code roots (e.g. `tests/README.md`) still count as executor work in E.
- **NB-5:** under `pipefail`, a commit object larger than the pipe buffer can make `cat-file | awk` fail on SIGPIPE, so a signed commit counts as unsigned (N7 family).
- **NB-6:** on an identity-guard `FAIL` the files stay in OUT; only the exit code and the `FAIL` line signal it, so the consumer must honour the exit code.
- **NB-7:** in a linked worktree, K-LOCALHOOK checks the per-worktree `$GD/hooks`, not the common dir's hooks (N8 family).
- **NB-8:** no cap on per-path `git log` passes or per-commit `cat-file` spawns; a large K-DEPLOY match multiplies the runtime (performance, not safety).
- **NB-9:** one sweeping commit (repo-wide reformat/move) puts its author into W_c, N_c and E at once; that overlap is observed, not forced, but it can dominate the counts.
- **NB-12:** the token vocabularies also catch data files that are not registers or status records (e.g. `config/decisions.yml` → K-NORMREG, labelled `SELF_ATTESTED[norm_register]` by definition; `lang/*/status.json` → K-STATUS), and the r3 infix boundary (BLK-2) widens this imprecision (e.g. `http_status.json`, `x-norm.md`, `.env`, `pre-release.md`).
