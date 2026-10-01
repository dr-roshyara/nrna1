# Main-analyst read-only safety check of `A3/scope_b.sh` (r3), done before execution

- **Every git invocation reads only:** rev-parse, config --get, for-each-ref, ls-tree, log, rev-list, cat-file.
  - No fetch, push, checkout, reset, config write, gc or update-ref.
  - `GIT_OPTIONAL_LOCKS=0` is set.
- **Writes:** only `$OUT` (an empty directory outside the work tree and .git, enforced at l.35–36) and a `mktemp -d` directory outside the repo and OUT (l.37–38).
- **Removal:** only `rm -rf -- "$TMP"`, the realpath of the mktemp result, on refusal and on EXIT.
- **Self path** is resolved and hashed at l.19–21, before the single `cd` at l.41.
- **No blob is read.** Only commit and tag object headers are read, for signature presence.
- **Identity guard:** fails closed (l.177–180).
- **Disclosed run-time expectations, not defects:**
  - E uses about 1,600 exclude globs → possibly slow;
  - one `cat-file` per commit (about 4k spawns).
- **Verdict: SAFE TO RUN** (read-only) under reviewer verdict B3 = PASS_WITH_LIMITATIONS.
- **Run plan:**
  - the same script file runs twice: from the repo root and from `app/`;
  - OUT lives in the session scratchpad;
  - HEAD is recorded;
  - SHA256SUMS of the two runs are compared.
