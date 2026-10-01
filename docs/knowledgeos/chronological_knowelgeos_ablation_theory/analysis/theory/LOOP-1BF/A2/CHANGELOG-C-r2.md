# CHANGELOG — Protocol C r1 → r2

Scope: the three repairs listed in TASK.md (SCOPE-1, NB-1, NB-5), and nothing else. Basis: REVIEW-r1.md/.json (verdict PASS_WITH_LIMITATIONS), the main analyst's safety check (SCOPE-1) and HUMAN-SCOPE.md. Nothing was run. Line numbers refer to `scope_c.sh` r1.
Unchanged: the bound, GET-only, the endpoint set and call count (≤ 40, planned max 29), the status-gating rule (§2), the identity policy (§3), M-TIME, neutrality, the norm-state/independence constants, exit codes, and every output file and column not named below.
Revision markers: the title, the script header and `summary.json.revision` now read `r2`.

## SCOPE-1 (blocking): signature and verification data out of scope by human decision

Basis: H2 (signer verification) = NO, and "signer investigation" is excluded from H1 (HUMAN-SCOPE.md).

scope_c.sh:
- **Commit (L82–83):** the `signature_verified` (`.commit.verification.verified`) and `verification_reason` (`.commit.verification.reason`) keys are removed. `ch_commit.tsv` now holds only `http`, `identity_mapping_available`, `author_eq_committer`, `author_date` and `committer_date`.
- **Protection (L166):** the `required_signatures` key (`.required_signatures.enabled`) is removed from `ch_protection.tsv`.
- **branch_rules (L171):** rule types matching `signature`, `verif` or `gpg` (case-insensitive; on the host this is `required_signatures`) are dropped from the `types` tally. `n_active_rules` still counts every rule. Otherwise a base branch whose only rule is a signature rule would read `NOT_EVIDENCED_ON_HOST` from a non-empty 200, which is a false absence.
- No other `verification`, `signature` or `gpg` field was read anywhere in r1. That covers timeline `committed` events, runs, checks, status, rulesets and events, which were all checked against the r1 filters.
- The script header states the exclusion.

PROTOCOL-C §1: a new paragraph states that signature and verification fields are **out of scope by human decision** and are not read. It lists what is excluded. It also states that the host still returns those fields inside the commit and timeline bodies, that they stay unread in the temp dir, and that the guard's harvest walks the raw bodies but extracts only `login` and the `name` next to an `email`.

Not changed: the commit GET itself stays in the set, because it is the only source of the e-mail→account mapping (§1, channel 2). Its response body unavoidably contains `commit.verification`. That body is not read for that object and never reaches OUT.

## NB-1: the identity guard fails closed on grep errors and on a missing ids.txt

scope_c.sh L214–216 → `guard()` and the `ids.txt` write; PROTOCOL-C §5.
- The `ids.txt` write is now status-checked. If the pipeline's write fails, the script exits 5 with "ids.txt not written; nothing written to OUT".
- `guard()` first requires `ids.txt` to exist and be non-empty (`[ -f ] && [ -s ]`). It always holds at least the owner slug, so an empty file means a failure.
- The `! grep …` idiom is replaced by explicit exit-status handling: each grep must return **exactly 1** (no match). A status of 0 (hit) or ≥ 2 (error, even alongside a match) makes `guard` return 1, which means exit 5, nothing copied (staged run) or "quarantine by hand" (run on OUT).
- §5 now describes this behaviour. It replaces the inaccurate r1 claim that the unchanged `! grep` guard "still fails closed".

## NB-5: inside() fails closed on git errors

scope_c.sh L20–23; PROTOCOL-C §4 (Paths).
- `inside()` captures `git rev-parse --is-inside-work-tree` stderr. Exit 0 means inside, as before. A failure whose message contains "not a git repository" means outside. **Any other failure** (e.g. "dubious ownership" / `safe.directory`, or a directory git cannot enter) means **inside**, so OUT or TMP is refused with exit 3. `LC_ALL=C` (already set) keeps the message in English.
- The refusal messages now read "…inside a work tree (or git could not tell)".
- Residual, stated in §4: a work tree that git itself reports as "not a git repository" (hidden by `GIT_CEILING_DIRECTORIES` or a filesystem boundary) still reads as outside under the rule TASK.md specifies. The WORKTREE_DIR check still applies.

## NB-2, NB-3, NB-4, NB-6, NB-7

These are not repaired. Each is disclosed in one line in PROTOCOL-C §6. The §5 sentence "cannot be tripped by them" is kept as written and qualified by the NB-4 line.
