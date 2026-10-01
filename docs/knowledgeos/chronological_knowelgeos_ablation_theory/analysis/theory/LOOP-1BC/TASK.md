# Task (frozen): design a minimal, neutral evidence-capture protocol (Protocol A)

Use ONLY:
- `ENVIRONMENT.md`: the execution environment;
- `TARGET-FACTS.md`;
- `REFERENCE-r2-rank0-script.sh`: an earlier, much larger script, to be used as **raw material only**. It failed review for reasons outside this task, and is not to be copied wholesale.

No commands, no web.

**Purpose (descriptive only):** determine whether **WP-4C-1 execution work** exists in the local git history of this repository, and identify the **earliest reproducible system-recorded trace**. **This protocol must NOT** decide whether any work conformed to any norm, and must NOT contain any hypothesis, falsifier or scoring. It **captures evidence only**.

Write two files.

## PROTOCOL-A.md (at most ~1.5 pages), defining in advance:
1. **The target:**
   - the exact match rule for WP-4C-1 and its alias `wp4c1-`;
   - Unicode hyphen variants;
   - the boundary rules;
   - the excluded near-matches, with reasons;
   - where matches are looked for: commit messages, trailers, changed file paths, diff added lines.
2. **Classification of each matching commit**, by a deterministic rule, into exactly one of:
   - EXECUTION-TRACE (a changed **code or test** path, outside documentation and governance paths, whose commit message or changed lines reference the target);
   - DOC-OR-GOVERNANCE (only documentation / governance / plan / ruling files change);
   - MENTION-ONLY (the target appears only in the message, while the changed files are unrelated);
   - MERGE (merge commits: classify by their own changes, and never promote the side branch);
   - UNCLASSIFIABLE.

   **Define "code / test path" vs "documentation / governance path" by explicit path patterns.**
3. **Timestamps:** record author date and committer date separately (UTC), each labelled RECORDING_ORDER only (rule M-TIME: recording ≠ decision ≠ entry into force). No other timestamp sources are available (no CI, no host).
4. **Coverage:** the local refs captured (`git for-each-ref`), the number of commits scanned, and the statement "completeness vs the host NOT shown".
5. **Absence:** if no EXECUTION-TRACE exists → `NOT-EVIDENCED-IN-CAPTURE` (never "no execution occurred").
6. **Output:** a TSV / JSON per matching commit (sha, author_date_utc, committer_date_utc, class, matched_in, matched_paths, rule_id) plus a summary (counts per class, the earliest EXECUTION-TRACE by committer date and by author date, separately, labelled RECORDING_ORDER). Then a SHA256SUMS of all outputs.

## capture_a.sh: a small bash script
It must be:
- **read-only** (git log / show / for-each-ref / rev-list only);
- **deterministic** (LC_ALL=C, TZ=UTC, fixed sort, `--no-pager`, `-c core.quotepath=false`; ignore the global / system config);
- **no `set -e`**, with explicit exit handling;
- it **resolves its own absolute path before any `cd`** (the r2 self-hash defect), and works when invoked from the repository root **and** from a subdirectory;
- it writes all output to a directory given as an argument, then SHA256SUMS.

Keep it under ~150 lines. No personal identities in the output (no author names or emails).

Write **PROTOCOL-A.md** and **capture_a.sh**. Nothing else.
