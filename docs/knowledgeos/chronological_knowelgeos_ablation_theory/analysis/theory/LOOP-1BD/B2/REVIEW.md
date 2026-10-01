# Targeted re-review — Protocol B r2 (PROTOCOL-B.md + scope_b.sh, frozen)

**Verdict: FAIL.** One blocking issue: **BLK-2**. The BLK-1 split itself is correct. K-NORMREG and K-STATUS are disjoint, K-STATUS is now decided by data, and SELF_ATTESTED has its own headline bucket. But the register regex that now defines the **only** norm source does not match the repo's own rulings register, `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`. That file falls into **no** channel. NB-1, NB-2, NB-3 and NB-10 are fixed. No regression was found in the r1 guarantees.

Sources: REVIEW-TASK.md, REVIEW-r1.md, CHANGELOG-B-r2.md, PROTOCOL-B.md, scope_b.sh (174 lines, read line by line). Nothing was executed.

---

## 1. BLK-1

### Disjointness: **YES**
- scope_b.sh l.73–74: `if (docx && <register regex>) print K-NORMREG; else if (docx && <status regex>) print K-STATUS`.
- One path is one awk record, so it takes at most one of the two branches.
- `npaths` (l.92) is built from the K-NORMREG rows only. For K-STATUS, `ncp = comm -23 npaths cp` (l.105) is therefore all of `npaths`, so `nnp` = |K-NORMREG|. For K-NORMREG, `ncp` is empty by construction.
- The register tokens left in the K-STATUS list are dead code, as CHANGELOG says. Each one is also in the register regex, with the same boundaries.

### Path traces (q = lower-cased path; both regexes need `(^|/)` *before* the token and `([._/-]|$)` *after* it)

| Path | Register regex | Status regex | Channel | Q7 |
|---|---|---|---|---|
| `docs/implementation/PROGRAM_STATUS.md` | no (no segment starts with a register token) | `/program_status.` + ext md → yes | **K-STATUS** (no K-DEPLOY token either) | Depends on the data. With no K-NORMREG path anywhere: `IND[no_norm_source_distinct_from_candidate;overlap_exec=x;overlap_norm=NA]`. With ≥1 K-NORMREG path: rules 4–6 (`IND[empty_set…]` / `NOT_INDEPENDENT[overlap_norm…]` / `NOT_INDEPENDENT[overlap_exec…]` / `INDEPENDENT_AT_IDENTITY_COUNT_LEVEL[aliases_unresolved]`) |
| `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` | **no**: `rulings` follows `-`, not `^` or `/` | **no** (same reason; no other token) | **none** (also not K-DEPLOY) | **none**: not a candidate, and not in N_c for anyone → **BLK-2** |
| `docs/approvals/x.md` | no (`approvals` is not a register token) | `/approvals/` → yes | **K-STATUS** | Depends on the data, as for PROGRAM_STATUS.md |
| `register.md` (any `…/register.md`) | `^register.` / `/register.` → yes | (else branch skipped) | **K-NORMREG** | `SELF_ATTESTED[norm_register;overlap_exec=x]`. This is definitional (§3 rule 1). `nnp=0` by construction |

### Is any K-STATUS outcome forced by the channel id? **No.**
- K-STATUS is not in `cfg` (l.135) and is not caught by the `ch=="K-NORMREG"` test (l.138).
- It passes only through the data tests: `nnp==0` (l.139), the empty-set test (l.141), `on>0` (l.142), `oe>0` (l.143), and otherwise the positive (l.144).
- The only outcome fixed by channel id is `SELF_ATTESTED` for K-NORMREG. That is the declared definitional outcome the r1 fix direction allowed.

### Is SELF_ATTESTED kept out of the negative bucket? **Yes.**
- `nA` is counted separately (l.161). It is tested 4th (l.164), after `nX`, and maps to `LOCAL_ONLY_SELF_ATTESTED_RECORDS`.
- `not_independent_by_overlap` = `nX` only.
- The anchored patterns cannot collide.

### Concrete configuration in which K-STATUS reaches INDEPENDENT_AT_IDENTITY_COUNT_LEVEL
- `docs/approvals/x.md`: authored only by `approver@x`. It is K-STATUS.
- `docs/rulings/R-001.md` (or `register.md`): authored only by `chair@x`. It is K-NORMREG.
- `app/Foo.php`: authored only by `dev@x`. It is in E and matches no exclusion glob.

For K-STATUS this gives:
- `np=1`, `ncp={docs/rulings/R-001.md}`, `nnp=1`
- W={approver}, N_c={chair}, E={dev}, so `nw=nn=nE=1` and `on=oe=0`

Then l.144 gives `INDEPENDENT_AT_IDENTITY_COUNT_LEVEL[aliases_unresolved]`. `nI=1`, so the headline is `LOCAL_INDEPENDENT_CANDIDATE_AT_IDENTITY_COUNT_LEVEL`. In the same run, K-NORMREG gets `SELF_ATTESTED[norm_register;overlap_exec=0]`.

---

## Blocking

**BLK-2 (new; inside the BLK-1 repair): the register regex misses the repo's rulings register, so the norm-source set N is wrong** (scope_b.sh l.73, l.85; PROTOCOL §1 "leading `(^|/)`", §3 "Norm-register paths").
- `ADR-AIP-LOG-Platform-Rulings.md` puts its register token after a hyphen. Both regexes, and the E glob translation (`**/$t…`), need the token at the start of a segment, so the file matches no channel.
- After the BLK-1 split, K-NORMREG is the **only** source of N_c for K-STATUS and K-DEPLOY, so this miss changes the outcome:
  - **The repo holds no other register-regex path:**
    - K-NORMREG gets Q1 = `NOT_EVIDENCED_AT_TIPS`, although a rulings register *is* in the tip trees. That is a false existence negative, and it comes from the file name, not from the data.
    - K-STATUS and K-DEPLOY get `IND[no_norm_source_distinct_from_candidate…]`. That label is factually false here, and the positive the BLK-1 repair was meant to enable cannot be reached on this repo.
  - **Some other register-regex path exists** (e.g. a `decisions.json`):
    - N_c is computed without the actual rulings register. Its authors (the norm-maker) never enter N_c, so `overlap_norm` is undercounted.
    - That undercount can produce `INDEPENDENT_AT_IDENTITY_COUNT_LEVEL`, and the `[aliases_unresolved]` qualifier does not cover it.
- *Fix direction:*
  - Allow an infix leading boundary for the register tokens, e.g. `(^|[/._-])(rulings?|register|…)([._/-]|$)`, or add an explicit ADR/rulings rule.
  - Widen the E-exclusion glob translation the same way (`**/*[._-]$t.$x`, `**/*[._-]$t[._-]*.$x`, and the directory forms), so that the translation stays complete.
  - Re-trace the four paths after the change. The ADR file should become K-NORMREG.
- *If the analyst decides* that infix register names are out of scope by design, this must be declared in §1 and §6. The `no_norm_source_distinct_from_candidate` label must then be qualified, e.g. "by vocabulary". BLK-2 would then become a limitation, and the verdict PASS_WITH_LIMITATIONS.

---

## 2. Non-blocking items from REVIEW-r1

| Item | Status | Evidence |
|---|---|---|
| NB-1 | **FIXED** | `--full-history` is present in all three `git log` calls: E l.89, W l.101, N_c l.107. With `--no-merges`, merge output does not change. |
| NB-2 | **FIXED** | `GIT_CONFIG` and `GIT_COMMON_DIR` are in the `unset` list (l.11–12). `GIT_GRAFT_FILE=/dev/null` is exported (l.10), and an empty graft file means no grafts. `GIT_NO_REPLACE_OBJECTS=1` is kept. |
| NB-3 | **FIXED** | The register regex has the trailing boundary `([._/-]|$)` (l.73). `registration`, `norms`, `normal…` and `decisionsupport…` no longer match. `authoriz(ation|ations)?` can match on its own. The E glob loop (l.83–85) now includes `authoriz authorization authorizations`. I checked the doc-ext, `[._-]*`, `/**/` and `**/` forms against the regex token by token: complete. |
| NB-10 | **FIXED** | §3 names K-STATUS and K-DEPLOY as the only channels that can reach a positive, and only with a K-NORMREG path outside their own paths. This is correct: K-DEPLOY can share paths with K-NORMREG (e.g. `release/decisions.md`), and `comm -23` removes them. The line count is now right: r2 has 174 lines. |

## 3. Reachability

| Q | Reachable outcomes |
|---|---|
| Q1 | `YES_IN_CAPTURE`, `NOT_EVIDENCED_AT_TIPS` (path channels, K-SIG); `YES_LOCAL_ONLY`, `NOT_EVIDENCED_LOCALLY` (K-LOCALHOOK); `NOT_CHECKABLE_LOCALLY` (host) |
| Q2 | `CONFIG_RULE` (K-CI/HOOK/CODEOWNERS/LOCALHOOK), `NORM_REGISTER_RECORD` (K-NORMREG), `STATUS_RECORD` (K-STATUS), `STATE_RECORD_BY_TYPE` (K-DEPLOY), `IDENTITY_ATTESTATION` (K-SIG); `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` |
| Q3 | `CONTROLLED_BY_WRITERS[n=k]`; `ND(keyring not consulted)`; `LOCAL_MACHINE`; `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` |
| Q4 | `NO_CLIENT_SET(…)`; `NONE` (K-LOCALHOOK, N13); `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` |
| Q5 | `ND`; `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` (one substantive outcome by design) |
| Q6 | `ND[COMPLETE_VS_HOST=NOT_SHOWN]`; `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` (one outcome by design) |
| Q7 | **K-STATUS and K-DEPLOY:** `IND[no_norm_source…]`, `IND[empty_set…]`, `NOT_INDEPENDENT[overlap_norm=…]`, `NOT_INDEPENDENT[overlap_exec=…]`, `INDEPENDENT_AT_IDENTITY_COUNT_LEVEL[aliases_unresolved]`. **K-NORMREG:** `SELF_ATTESTED[…]` only (definitional, declared). **K-CI/HOOK/CODEOWNERS:** `IND[no_norm_source…]` or `IND[mechanism_host_enforced_unobservable…]`. **K-SIG, K-LOCALHOOK:** `IND`. Also `NOT_APPLICABLE(Q1)` and `NOT_CHECKABLE_LOCALLY`. |
| Q8 | `YES_LOCAL(…)`; `NO_LOCAL_ONLY` (K-LOCALHOOK); `NOT_APPLICABLE(Q1)`; `NOT_CHECKABLE_LOCALLY` |

**Headline: computed from the data** (`q7n` over column 8 of `70_answers.tsv`, local rows; host rows cannot match). All five tokens can be reached:
1. The configuration above.
2. Any K-CI, K-HOOK or K-CODEOWNERS path, any signature, or any local hook.
3. K-NORMREG plus K-STATUS with an observed overlap, and no IND channel.
4. Only K-NORMREG evidenced (no CI, hooks, signatures, local hooks, K-STATUS or K-DEPLOY).
5. Nothing evidenced.

Bucket 3 needs the absence of every IND channel, which is rare in practice. That is a design choice, and it is not a forced negative.

**Forced negatives left:** none in the Q7 or headline logic. `NOT_INDEPENDENT` always needs an observed shared author, and `SELF_ATTESTED` is outside the negative bucket. The only construction-driven negative left is the BLK-2 effect: K-NORMREG can show `NOT_EVIDENCED_AT_TIPS` although a rulings register is present.

## 4. Regression check

| Item | Result |
|---|---|
| Read-only safety | **OK.** Every command is unchanged from r1: `realpath`, `sha256sum`, `cut`, `ls -A`, `mktemp -d`, `rm -rf $TMP` (l.35 and the trap), `mkdir -p $OUT`, one `cd`, `mv` within TMP, `sort/tr/comm/awk/grep/cat`, and `find/xargs sha256sum` in OUT. The git calls are `rev-parse` ×6 kinds, `config --get` ×2, `--version`, `for-each-ref`, `ls-tree`, `log`, `rev-list` and `cat-file commit/tag`, all read-only. There is no fetch, no index refresh, and no ref or config write. Every loop git call has its stdin redirected. |
| Determinism | **OK** with a clean environment: `LC_ALL=C TZ=UTC`, one C-sorted tip file, C-sorted sets, a fixed channel order (now 6 channels), sorted SHA256SUMS, no HEAD dependence. Residual: see NB-11. |
| Config isolation | **OK as claimed** (N4 + NB-2). The wrapper with eight `-c` overrides is defined before the first git call. The repo-local config is readable, and this is declared. |
| B1–B5 | **Intact.** B1: the tip set (l.52–57) is unchanged. B2: own paths are removed from N_c by `comm -23` (l.105). B3/B4: E uses the roots, `%ae` only, all channel globs excluded plus `docs/knowledgeos/**`, and now `--full-history`. B5: Q5 is `ND` on every evidenced row (awk col 6, K-SIG and K-LOCALHOOK printf). The column mapping metrics→answers (`$2,$4,$5,$6,$7,$8`) matches the printf at l.111. The printf `%s` and argument counts at l.149/153 match (9 columns). |
| Self path before `cd` | **OK.** l.16–18 resolve and hash it before the single `cd` at l.38. If the path cannot be resolved, the script fails closed with exit 2. |
| OUT refusal | **OK** (unchanged): OUT is required, absolute, `realpath -m`, outside the toplevel and the git dir, and empty or absent. TMP is refused inside the repo or OUT. N8 is disclosed. |
| No identities / guard fails closed | **OK.** Emitted files hold only counts, 16-hex ids, labels, the git version and hashes. Error labels hold ids, never paths. The guard is rc 1 → PASS, rc 0 → FAIL(n), rc ≥ 2 → FAIL(grep_rc), and both FAILs exit 1. NB-6 is disclosed. |
| Boundedness | **OK.** Object headers only. No blob is read. NB-8 (no cap) is disclosed. |
| `docs/knowledgeos/` exclusion | **OK.** Classifier l.67 `next` (case-insensitive), the E `exclude,glob,icase` pathspec, and through `cand` also `npaths` and W. |
| Neutrality | **OK.** The new token `LOCAL_ONLY_SELF_ATTESTED_RECORDS` is descriptive and defined in §5. SELF_ATTESTED is declared as definitional. See NB-12 for its scope. |

---

## Non-blocking (new)

- **NB-11 (determinism, isolation; existed before r2):** the pathspec env vars and the trace env vars are not unset.
  - `GIT_LITERAL_PATHSPECS=1` turns off `:(…)` magic, so `:(literal)app` and `:(exclude,glob,icase)…` become literal names. E and every W would be silently empty, giving `IND[empty_set]` everywhere.
  - `GIT_ICASE_PATHSPECS` changes what W matches.
  - `GIT_GLOB_PATHSPECS` and `GIT_NOGLOB_PATHSPECS` raise a magic conflict. That is recorded by `ok()` (exit 1).
  - `GIT_TRACE*` and `GIT_TRACE2*` can write git command lines, including literal path pathspecs, to a file or to stderr outside OUT.
  - These settings come only from the operator's environment, not from the repo. Add them to the `unset` list.
- **NB-12 (precision, neutrality):** the register and status vocabularies also catch data files in the code roots. Examples: `config/decisions.yml` or `database/seeders/norm.json` (→ K-NORMREG, labelled `SELF_ATTESTED[norm_register]` "by declared definition") and `lang/*/status.json` (→ K-STATUS, whose developer writers can give an observed `overlap_exec`). Neither case is a forced outcome, but the definitional label is applied to files that are not registers. Declare this in §6.
- The r1 limitations (N7, N8, N10, N11, N13, N15, NB-4 to NB-9) are still listed in §6 as disclosed. They were not re-examined here, since that is out of scope.
