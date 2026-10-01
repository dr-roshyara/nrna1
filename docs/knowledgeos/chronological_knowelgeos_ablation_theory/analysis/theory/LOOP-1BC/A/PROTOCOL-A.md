# PROTOCOL A — evidence capture for WP-4C-1 (fixed in advance; implemented by `capture_a.sh`)

**Purpose (descriptive only).** Find out whether the local git history of the ordinary working clone contains WP-4C-1 execution work, and find the earliest reproducible trace that the system recorded. This protocol **captures evidence only**. It does not judge whether any work conformed to any norm, and it contains no hypothesis, falsifier or score.

## 1. Target
**Normalisation (before any test).** Each Unicode hyphen or dash is replaced by an ASCII `-`. The replaced characters are U+2010, U+2011, U+2012, U+2013, U+2014, U+2015, U+2212, U+FE58, U+FE63, U+FF0D and U+00AD (soft hyphen). The text is padded with one space on each side. Tests run byte-wise under `LC_ALL=C`.

| Rule | Matches (case-insensitive letters) | Left boundary | Right boundary |
|---|---|---|---|
| **T1** | `WP-4C-1` | previous char not `[0-9A-Za-z]` | next char not `[0-9A-Za-z]`, **and** the next two chars are not `-<digit>` or `.<digit>` |
| **T2** (alias) | `wp4c1-` | previous char not `[0-9A-Za-z]` | none (the hyphen is the boundary) |

`_`, `/`, `.`, spaces and punctuation count as boundaries. So `WP-4C-1.`, `(WP-4C-1)`, `x_wp4c1-evidence.md` and `WP-4C-1-acceptance` all match.

**Excluded near-matches:**
- `WP-4C-2`, `WP-4B`, `WP-4D`: these are sibling items, not the target.
- `WP-4C` with no `-1` after it: this is the parent item. It names a scope, not this item.
- `WP-4C-10`, `WP-4C-1a`: the identifier continues with an alphanumeric character, so it names a different item.
- `WP-4C-1.2`, `WP-4C-1-2`: these read as a sub-item or a range. They are a longer identifier, so the protocol does not treat them as WP-4C-1.
- `xWP-4C-1`, `swp4c1-`: there is no left boundary.
- Unattested spellings (`WP4C1`, `WP-4C1`, `wp_4c_1`, `wp4c1_`, `WP 4C 1`): neither the register nor the facts give these spellings. Matching them would add false positives without any stated basis.

**Where the protocol looks.** It searches every commit reachable from the captured refs, with no date or ancestry filter, in four places:
- `message`: the full raw message (`%B`).
- `trailer`: the parsed trailers (`%(trailers:only,unfold)`). A trailer hit is also a `message` hit.
- `path`: changed file paths (`--name-only`).
- `added_line`: added diff lines only (`-p -U0`; lines starting with `+`).

Renames are not detected (`--no-renames`), and external diff and textconv are off. A **merge's own changes** come from the combined diff (`--diff-merges=combined`): only paths that differ from *every* parent, and only lines added relative to *every* parent (all `+` columns). The side branch's changes are therefore never attributed to the merge. Side-branch commits are scanned separately, as ordinary commits.

## 2. Path classes and commit classification
Each changed path is lower-cased. The first rule that matches assigns its class:
- **P-DOC (documentation / governance):**
  - a directory segment is one of `doc docs governance ruling rulings decision decisions adr adrs plan plans planning evidence minutes notes report reports register registers`; or
  - the extension is one of `.md .markdown .rst .txt .adoc .org .pdf .doc .docx .odt`; or
  - the basename starts with `register|registers|ruling|rulings|decision|decisions|plan|plans|acceptance|adr`, followed by `-`, `_`, `.` or the end of the name.
- **P-TEST:**
  - a directory segment is one of `test tests __tests__ spec specs e2e`; or
  - the basename matches `^test_`, `[._]test\.`, `[._]spec\.` or `^conftest\.py$`.
- **P-CODE:**
  - the extension is one of `sh bash py js mjs cjs ts tsx jsx go rs java kt rb php c h cc cpp hpp cs swift scala sql lua pl r ps1`; or
  - the basename is `makefile`, `dockerfile` or `justfile`.
- **P-OTHER:** everything else, including config, data, lockfiles, binaries and `.github/**` YAML. The protocol does not interpret CI content.

"Code/test path" means P-TEST ∪ P-CODE. P-DOC takes precedence, so `docs/x.py` is P-DOC.

**A matching commit** has at least one T1/T2 hit in at least one of the four places. Notation: K = its code/test paths; *msg* = a message or trailer hit; *kref* = a hit in a K path name or in a line added to a K path. The first rule that fires assigns the class:

| Rule | Condition | Class |
|---|---|---|
| C1 | ≥ 2 parents | **MERGE** |
| C2 | K ≠ ∅ and (*msg* or *kref*) | **EXECUTION-TRACE** |
| C3 | ≥ 1 changed path and all changed paths are P-DOC | **DOC-OR-GOVERNANCE** |
| C4 | *msg*, no path or added-line hit, ≥ 1 changed path (none of them K) | **MENTION-ONLY** |
| C5 | anything else (for example an empty commit, or hits only in P-OTHER, or a P-DOC/P-OTHER mix) | **UNCLASSIFIABLE** |

For a merge, C2–C5 are also applied to the merge's own changes and recorded as `rule_id = C1>Cn`. This note is for information only. A merge never counts as EXECUTION-TRACE.

## 3. Timestamps (rule M-TIME)
The protocol records the author date and the committer date **separately**, in UTC (`--date=format-local:%Y-%m-%dT%H:%M:%SZ`, `TZ=UTC`). Each is labelled **RECORDING_ORDER** only, because recording ≠ decision ≠ entry into force. No other timestamp source is available (no CI, no host data). Dates are copied as written by the client. They are not validated.

## 4. Coverage
- **Captured:** every ref printed by `git for-each-ref` (`01_refs.tsv`). Annotated tags are peeled to their commits; refs that point to non-commit objects are listed but not walked.
- **Scanned:** every commit reachable from those tips (`02_commits_scanned.txt`, with the count in the summary).
- **Out of scope:** hosted PR refs, server audit logs, push times and CI history.
- **Fixed statement:** "completeness vs the host NOT shown".

## 5. Absence
If no commit is classed EXECUTION-TRACE and the capture had no errors, the result is **`NOT-EVIDENCED-IN-CAPTURE`**. The protocol never concludes that no execution occurred. If any command exits unexpectedly (`00_errors.tsv` is non-empty), the result is `UNDETERMINED (CAPTURE_ERROR)` and absence is not asserted.

## 6. Outputs (in the directory passed as the argument)
- `00_meta.tsv`: script SHA-256 and git version.
- `00_errors.tsv`, `00_stderr.log`.
- `01_refs.tsv`, `01_tips.txt`, `02_commits_scanned.txt`.
- `10_message_hits.tsv`, `11_path_hits.tsv`, `12_line_hits.tsv`, `13_hitset.txt`, `14_changed_paths.tsv`: the raw hits and the path class of each changed path of the matching commits. No line text is stored.
- **`20_commits.tsv` / `20_commits.jsonl`**: one row per matching commit. Fields:
  - `sha`, `author_date_utc`, `committer_date_utc`, `class`;
  - `matched_in`: `source:rule` tokens, for example `message:T1`;
  - `matched_paths`: joined with `;`;
  - `rule_id`, `time_label=RECORDING_ORDER`.
- **`30_summary.tsv`**:
  - ref counts and commits scanned;
  - counts per class, all five shown, including zeros;
  - the earliest EXECUTION-TRACE by committer date and, separately, by author date (ties broken by sha), each labelled RECORDING_ORDER;
  - the result from §5 and the coverage statement.
- **`SHA256SUMS`**: the SHA-256 of every other output file.

The outputs contain no author or committer names and no email addresses.
