# Protocol B — minimal, neutral scoping of candidate norm-provenance channels

**Goal (from QUESTIONS.md):** decide, for a *bounded* list of locally checkable, read-only
channel types, whether any could be an **independent** record of *which norm was in force at
time t*. "Independent" = controlled by someone/something other than (a) the norm-maker,
(b) the executor of the work, (c) the ruling asserting its own effect. Existence/scoping only —
**no archaeology**, no evidence acquisition, **never a forced negative**.

The research programme's own artefacts (`docs/knowledgeos/**`) are **never** candidates
(self-contamination) and are excluded by the classifier.

## 1. Candidate channels (exact patterns)

| Id | What | Path pattern / source (case-insensitive) |
|----|------|-------------------------------------------|
| `K-CI` | CI definitions | `.github/workflows/**`, `.gitlab-ci.yml`, `Jenkinsfile`, `.circleci/**`, `azure-pipelines.yml`, `.drone.yml`, `bitbucket-pipelines.yml`, `.woodpecker*` |
| `K-HOOK` | repo-shipped git hooks | `.githooks/**`, `.husky/**`, `.pre-commit-config.yaml`, `lefthook.yml`, `.lefthook.yml` |
| `K-CODEOWNERS` | ownership / branch-protection hints in repo | path basename `CODEOWNERS` |
| `K-STATUS` | status register / rulings register / in-force record (**outside** `docs/knowledgeos/`) | doc-like ext (`md,markdown,txt,rst,json,ya?ml,toml,csv,tsv`) whose path matches a token in {`program_status,status,ruling(s),register,registry,decision(s),adoption,approval(s),signoff(s),progress,inforce,norm`}, e.g. `docs/implementation/PROGRAM_STATUS.md` |
| `K-DEPLOY` | deployment / config state | path token in {`deploy,deployment,release,environment,env,infra,terraform`} or ext `tfstate` |
| `K-SIG` | signed commits / tags | **presence** of `gpgsig` header / `-----BEGIN (PGP\|SSH) SIGNATURE-----`; keyring **not** consulted, signatures **not** verified |
| `K-LOCALHOOK` | local hooks (`LOCAL_ONLY`) | `.git/hooks/<standard-name>` present, or `core.hooksPath` set |

**Host-side channels — `NOT_CHECKABLE_LOCALLY`** (host not authorized): `K-HOST-AUDIT`
(server audit logs), `K-HOST-BRANCHPROT` (branch-protection *enforcement*), `K-HOST-PR-APPROVALS`
(PR approvals), `K-CI-RUNS` (CI run history), `K-HOST-MEMBERS` (org/team membership).

## 2. Deterministic answer rules (every outcome reachable)

Scope = the **local ref set only**; completeness vs the host is never shown. `ND` =
`NOT_DETERMINABLE_BY_EXISTENCE_CHECK`, `IND` = `INDEPENDENCE_NOT_DETERMINABLE`. If a channel
has **0** located paths → every answer is `NOT_APPLICABLE(Q1)` (`NOT_EVIDENCED_IN_CAPTURE` /
`NOT_EVIDENCED_LOCALLY`). Host rows are `NOT_CHECKABLE_LOCALLY` for all eight.

- **Q1 exists** → `YES_IN_CAPTURE` | `YES_LOCAL_ONLY` (`K-LOCALHOOK`) | `NOT_EVIDENCED_IN_CAPTURE` | `NOT_CHECKABLE_LOCALLY`.
- **Q2 records** → by **type** only (`CONFIG_RULE`, `STATUS/NORM_RECORD`, `IDENTITY_ATTESTATION`); content not read → otherwise `ND`.
- **Q3 controls** → `CONTROLLED_BY_WRITERS[n=…]` (count only) | for `K-SIG` `ND(keyring not consulted)` | `LOCAL_MACHINE` (`K-LOCALHOOK`).
- **Q4 timestamps independent** → `NO_CLIENT_SET` (git recording times are client-generated; a self-attested timestamp is **not** independence). Content timestamps = `ND`.
- **Q5 norm-in-force at t** → `NO_BY_TYPE` for config/attestation channels (a rule/attestation is not a norm-state record); else `ND` — existence never decides this.
- **Q6 completeness** → `ND[COMPLETE_VS_HOST=NOT_SHOWN]` always (local ref set only).
- **Q7 independence** → see §3. Reachable: `IND[…]`, `NOT_INDEPENDENT[…]`, `NORM_SOURCE_NOT_INDEPENDENT[self…]`, `NOT_APPLICABLE(Q1)`.
- **Q8 reproducible** → `YES_LOCAL` (content-addressed, rerunnable on same ref set) | `NO_LOCAL_ONLY` (`K-LOCALHOOK`, unversioned) | `NOT_CHECKABLE_LOCALLY`.

## 3. Independence rule (count overlap only)

For each channel compute, over the local ref set, by **count only** (identities never leave a
private temp dir):

- **W** = the channel's own writer set (author+committer emails over its paths);
- **E** = *executor* writers = writers over all tracked paths **excluding every candidate
  channel path and `docs/knowledgeos/**`** — so **a channel's own writers are not executors by
  construction** (corrects the earlier defect);
- **N** = *norm-maker* writers = writers over the rulings/register subset of `K-STATUS`;
- `overlap_exec = |W ∩ E|`, `overlap_norm = |W ∩ N|`.

Mapping:
- Control mechanism **unobservable locally** (`K-CI`, `K-HOOK`, `K-CODEOWNERS` — enforcement is
  host/client-side; `K-SIG` — signatures unverified) → **`IND`** with the overlap counts attached.
- Mechanism observable (writer-set in repo) and `overlap_norm>0` → `NORM_SOURCE_NOT_INDEPENDENT`
  for `K-STATUS` (self: it *is* the norm source) else `NOT_INDEPENDENT[overlap_norm]`.
- Observable and `overlap_exec>0` → `NOT_INDEPENDENT[overlap_exec]` (an **observed** overlap, not a construction artefact).
- Observable and both overlaps `0` → **`IND`** `[writer_overlap=0; control_mechanism_unobservable]`
  (existence + zero overlap cannot *confirm* a positive).

A `NOT_INDEPENDENT` verdict therefore arises only from genuinely observed cross-role authorship;
where overlap is unknown or the mechanism unobservable the verdict is `IND` — **never a forced negative.**

## 4. Completeness rule

**Checkable locally:** existence of the paths/hooks above in the local ref set; per-channel
commit-count, writer-**count**, and signature **presence**; writer-overlap counts. **Not
checkable locally (declared):** branch-protection enforcement, PR approvals, server audit logs,
CI run history, org membership, host push times, and whether the local ref set is complete vs
the host. All of these are reported as `NOT_CHECKABLE_LOCALLY` / `COMPLETE_VS_HOST=NOT_SHOWN`;
content of any file (what a rule or record *says*) is `ND` because content is not read.

## 5. Output (written by `scope_b.sh` into an empty `$OUT` outside the repo)

- `60_channel_metrics.tsv` — per channel: `channel, paths, commits, writers, overlap_exec, overlap_norm` (counts only).
- `61_path_ids.tsv` — `16-hex-id → channel` (no path strings leave the repo).
- `58_signatures.tsv` — `commits_total, commits_signed, tags_signed`.
- `56_local_hooks.tsv` — `standard_hooks_present, core_hooksPath`.
- `70_answers.tsv` — candidate × Q1..Q8 (the §2/§3 mapping), incl. `K-SIG`, `K-LOCALHOOK`, host rows.
- `71_overall.tsv` — headline `NIF_A_INDEPENDENCE_NOT_DETERMINABLE_BY_EXISTENCE_CHECK`,
  `complete_vs_host=NOT_SHOWN`, open/excluded counts, host-channel count, `identity_guard`.
- `00_script.sha256`, `00_env.tsv`, `00_errors.tsv`, and `SHA256SUMS` over every emitted file.

**Guarantees:** read-only; `LC_ALL=C TZ=UTC` + config isolation (deterministic); no `set -e`
(explicit exits 0/1/2/3); own absolute path resolved+hashed **before** the one `cd`; refuses any
`$OUT` inside the work tree or `.git`; emits **counts only** — no names, emails, ref names, URLs,
owners or path strings; a final `identity_guard` greps `$OUT` for `@`/signature markers and fails
closed (exit 1). The **only** content read beyond existence/size/counts is commit/tag object
headers for **signature presence** (Q1/Q2/Q7 for `K-SIG` strictly require it); no file blob is read.
