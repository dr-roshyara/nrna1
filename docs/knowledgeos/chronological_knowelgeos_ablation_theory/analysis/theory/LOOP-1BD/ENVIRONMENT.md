# Execution environment facts (metadata, collected by the main analyst; no content)
- git version 2.55.0. The search runs in the **ordinary working clone**, NOT a bare or mirror clone. `git rev-parse --is-bare-repository` = false.
- 7 local branches, 16 remote-tracking refs, 1 tag, 4002 commits reachable from all local refs.
- Remote: GitHub (origin). **Hosted pull-request refs (`refs/pull/*`), server-side audit logs and push times are NOT in scope.** Fetching or querying the host is not authorized for rank-0. Declared consequence: the capture is **the local ref set only**, and completeness vs the host is NOT shown.
- A `.github` directory exists (CI configuration may exist). Its **existence** is a fact; its content and any CI run history are NOT inspected by rank-0.
- The commands must be **read-only** (`git log`, `git show --stat`, `git for-each-ref`, `git rev-list`, `ls`), **deterministic**, with their output written to files and hashed. No `set -e` abort on expected non-zero exits; handle them explicitly.
