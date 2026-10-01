# Task (frozen): design a minimal, neutral scoping protocol (Protocol B)

Use ONLY:
- `QUESTIONS.md`;
- `ENVIRONMENT.md`;
- `ROOTS.txt`: the top-level directories; metadata;
- `REFERENCE-r2-scoping-script.sh`: an earlier script that failed review, to be used as **raw material only**. Its known defects: it hashed a relative BASH_SOURCE after `cd`, and it counted a channel's own writers as executors, which forced a negative.

No commands, no web.

**Purpose:** answer the 8 questions in QUESTIONS.md for a **bounded, explicit list of candidate channel types that can be checked locally and read-only**. For example:
- CI definitions (`.github/workflows/*`);
- git hooks shipped in the repo;
- CODEOWNERS / branch-protection hints in the repo;
- signed commits or tags (signature **presence** only);
- status registers or files maintained outside docs/knowledgeos/ (e.g. docs/implementation/PROGRAM_STATUS.md, the rulings register): **existence and writer-count only**;
- deployment or configuration state files.

**Host-side channels** (server audit logs, branch-protection settings, PR approvals) are **out of scope**: list them as NOT_CHECKABLE_LOCALLY.

Write two files.

## PROTOCOL-B.md (at most ~1.5 pages)
1. The candidate list, each with the exact path patterns or commands.
2. For each of the 8 questions, a **deterministic answer rule** with **every outcome reachable**. Use NOT_DETERMINABLE_BY_EXISTENCE_CHECK and INDEPENDENCE_NOT_DETERMINABLE wherever existence does not decide.
3. **The independence assessment rule:**
   - compare the channel's controlling mechanism or writer set with the norm-makers' and the executors' writer sets, **by count overlap only**;
   - **a channel's own writers are not executors by construction**;
   - if the overlap is unknown or the mechanism unobservable → INDEPENDENCE_NOT_DETERMINABLE.
4. **Completeness rule:** what is checkable locally, and what is not.
5. **Output:** a TSV / JSON per candidate × question, plus a summary and SHA256SUMS.

## scope_b.sh: a small bash script (under ~150 lines)
It must be:
- read-only (git ls-files / log / for-each-ref / cat-file -t, ls, test);
- deterministic (LC_ALL=C, TZ=UTC, config isolation);
- no `set -e`, with explicit exits;
- it resolves its own absolute path **before** any `cd`;
- it refuses an OUT directory inside the work tree or .git;
- it emits **no personal identities** (counts only; no names or emails);
- it **reads no content** beyond a file's existence, size, commit count and writer **count**, except where a question strictly requires it (justify each).

Write **PROTOCOL-B.md** and **scope_b.sh**. Nothing else.
