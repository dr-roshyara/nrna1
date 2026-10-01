# REVIEW — Protocol A sealed output (WP-4C-1 evidence capture)

**Verdict: PASS_WITH_LIMITATIONS**

The package is internally consistent. Its counts match the per-commit table. Each of the four reviewed commits gets the class that PROTOCOL-A's rules assign to it. The earliest EXECUTION-TRACE (`e4724a4`) is correct by both dates. The limitations: one of the three EXECUTION-TRACE commits (`cc1a339`) gets that class mechanically but is not WP-4C-1 work in substance. A large share of the matching commits (21 of 51) are artefacts of this research programme itself. Neither limitation changes the result or the earliest trace.

Sources used: `PROTOCOL-A.md`, `capture_a.sh`, `TRACE-COMMITS.txt`, and every file in `PACKAGE/`. No code was run.

---

## 1. Integrity

| Check | Result |
|---|---|
| `13_hitset.txt` lines vs `matching_commits` | 51 = 51 ✓ |
| `20_commits.tsv` data rows vs `20_commits.jsonl` lines | 51 = 51; same SHAs, classes, dates, `matched_in`, `matched_paths` and `rule_id` (all rows checked) ✓ |
| Hit set = union of the SHAs in `10_`/`11_`/`12_` | ✓ (the three message-only commits `24de0f4`, `d4437ac` and `ff99b01` have `matched_paths = -`, as expected) |
| Class counts in `30_summary.tsv` vs `20_commits.tsv` | EXECUTION-TRACE 3 ✓ · DOC-OR-GOVERNANCE 47 ✓ · MENTION-ONLY 0 ✓ · MERGE 0 ✓ · UNCLASSIFIABLE 1 ✓ (sum 51) |
| `commits_scanned` | 4020 = lines in `02_commits_scanned.txt` ✓ |
| Ref counts | 25 refs = 7 heads + 16 remotes + 1 tag + 1 other (`refs/stash`) ✓ |
| Tips | 21 unique tips: duplicates removed and the annotated tag peeled to `228740a` ✓ |
| `matched_in` tokens vs raw hit files | spot-checked on 10 rows (e.g. `cc1a339` message:T1+T2, `9158ef1` T1/T2/T1+T2, `e6a6aaf` T1+T2 only) ✓ |
| UTC conversion | `e4724a4` 02:30:25+02:00 → 00:30:25Z; `939e35d` 17:19:21+02:00 → 15:19:21Z; `cc1a339` 20:38:51+02:00 → 18:38:51Z; `a72528b` 18:49:03+02:00 → 16:49:03Z ✓ |
| Errors | `00_errors.tsv` and `00_stderr.log` are empty (the empty-file SHA-256 `e3b0…b855` in `SHA256SUMS`); `capture_errors 0` ✓ |
| `SHA256SUMS` | Lists all 14 other outputs. **Not verifiable here** (no hashing possible); `script_sha256` vs `capture_a.sh` is not verifiable either |

**Earliest EXECUTION-TRACE.** The three ET commits by committer date are `e4724a4` (2026-08-04T00:30:25Z), `939e35d` (15:19:21Z) and `cc1a339` (18:38:51Z). Author date equals committer date for all three, so the order is the same by author date. `e4724a4` is correctly reported for both orderings, each labelled RECORDING_ORDER. ✓

## 2. Classification of the four commits

| SHA | Class | Follows from rules? | Genuine WP-4C-1 code/test work? |
|---|---|---|---|
| `e4724a4` | EXECUTION-TRACE (C2) | **Yes.** K = 3 `tests/*.php` (P-TEST) with added-line T1/T2 hits (*kref*), plus a message T1 hit. The plan file under `docs/` is P-DOC and is irrelevant to C2. | **Yes.** Subject "test(adjudication): WP-4C-1 batch B1 — RED for K1..K4". It adds the target's own failing tests (event, hydrator, registry wiring). |
| `939e35d` | EXECUTION-TRACE (C2) | **Yes.** K = 4 `app/**.php` (P-CODE) + 2 `tests/**.php` (P-TEST); *kref* in 2 CODE files and 1 TEST file, plus a message T1 hit. | **Yes.** "feat(adjudication): WP-4C-1 B2+B3": event, hydrator, writer, registration, and a round-trip test. The message also names `WP-4C-2` as held; that is a sibling mention, but it does not create the match, which comes from the genuine T1 references. |
| `cc1a339` | EXECUTION-TRACE (C2) | **Yes, mechanically.** K = 2 `tests/**.php`; *kref* (T1/T2) in `AdjudicationExpiredHydratorTest.php`, plus message T1/T2 hits. | **No — a mention inside a test commit.** The subject is "close **WP-6**'s AdjudicationExpiredHydrator coverage gap". WP-4C-1 appears only as context ("found it while writing WP-4C-1's equivalents") and in a traceability pointer to `2026-08-04-wp4c1-acceptance-evidence.md`. The test change concerns a WP-6 hydrator. C2 has no test that the K change is *about* the target, so a target mention in any code-bearing commit qualifies. This does not affect the earliest trace (`cc1a339` is the latest of the three). |
| `a72528b` | UNCLASSIFIABLE (C5) | **Yes.** About 8,900 changed paths, including P-CODE (`app/Console/Commands/VerifyUserEmail.php`, `app/Services/NewsletterService.php`, …), P-TEST (`tests/Unit/Cohesion/*`) and P-OTHER (`.gitignore`, `.codex/config.toml`, `scripts/**`, `storage/**`, one C-quoted path). Every T1/T2 hit is an added line in a P-DOC path. No message hit and no *kref*, so C2 fails; not all paths are P-DOC, so C3 fails; no message hit, so C4 fails. C5 applies. | n/a (not counted as trace) |

## 3. Anomalies (recorded only)

**(a) Plan-file name vs. recording time.** `e4724a4` changes `docs/plans/20260804-1900-wp4c1-adjudicationfailuredeclared-plan.md`. The name begins with `20260804-1900`, which reads as 2026-08-04 19:00, with no time zone. The commit is recorded at 2026-08-04T00:30:25Z (local offset +02:00, i.e. 02:30:25). The same path also appears in the capture in earlier and later commits: `c147913` at 00:16:04Z, `df3464c` (the parent of `e4724a4`) at 00:21:44Z, `777bbbb` at 15:01:41Z and `c89c043` at 15:29:41Z. Every recording of the path in the capture is therefore earlier than 19:00 on that date in either UTC or +02:00. Under M-TIME a file name is not a timestamp source. Commit dates are client-written and unvalidated, and recording ≠ decision ≠ entry into force. **No conclusion is drawn** about when the plan was decided or written, or about any clock state.

**(b) Self-generated research artefacts in the evidence.** `a72528b` (2026-09-30) owes all its hits to files of this research programme (`docs/knowledgeos/chronological_knowelgeos_ablation_theory/analysis/**`, e.g. `LOCATE-2026-09-27/F-SEARCH-06.json`, `GATE1*/…/transcript.jsonl`, `LOOP-1A*/…`, `m1_check.py`, `docs/knowledgeos/reviews/2026-09-29-…`). The one exception is `.claude/CONTEXT-ARCHIVE-2026-07-08.md`. The pattern is broader than the one commit: **21 of the 51 matching commits** (20 DOC-OR-GOVERNANCE plus `a72528b`) are dated 2026-09-30 or 2026-10-01, and their matched paths lie under `docs/knowledgeos/`. The exceptions are `44812f9`, which also has a hit in `.claude/F-series-todos.md`, and `a72528b`. They include `cf2f6b9`, the current local tip of `knowelegeos-modelling`, which commits `LOOP-1BC/A/PROTOCOL-A.md` and `capture_a.sh` themselves, and `0122e0e`, which commits the LOOP-1BC task files. Without them, the historical DOC-OR-GOVERNANCE count would be 27, not 47. The ET set and the earliest trace are unaffected.

**Recommendation (not applied): yes, add an explicit rule fixed in advance.**
> **R-SELF.** Before classification, any changed path under a declared *programme-artefact root* (initially `docs/knowledgeos/`, listed exhaustively in a versioned `SELF-ROOTS.txt`) is assigned path class **P-SELF**. Hits in P-SELF paths are recorded in the raw hit files but are ignored by C2–C5. P-SELF paths are excluded from the C3 "all paths P-DOC" test. A commit whose *only* hits are in P-SELF paths is assigned the class **SELF-ARTEFACT**. It is listed and counted separately in `30_summary.tsv` and never contributes to any other class or to the earliest-trace fields.

Rationale: the rule is path-based, not date-based, because M-TIME treats dates as unvalidated, and it does not depend on branches, because research branches also carry historical commits. It is transparent (nothing is deleted, only reclassified), and it is deterministic. Applied hypothetically, it would move the 20 DOC-OR-GOVERNANCE self-artefact commits to SELF-ARTEFACT. `a72528b` would stay UNCLASSIFIABLE, because its `.claude/CONTEXT-ARCHIVE-2026-07-08.md` hit is outside the programme root. The three ET commits would be unchanged. A capture run from a state that already contains the protocol's own commit should also disclose that fact.

## 4. Neutrality

**OK.** The package files contain only class labels, rule IDs, hit locations, dates labelled RECORDING_ORDER, counts, the coverage statement and a result string. No file refers to conformance, norms, hypotheses, falsifiers or scores. (Path names such as `CONFORMANCE-CASE.json` appear in `14_changed_paths.tsv` as data, not as interpretation.) One minor gap: the positive result string `EXECUTION-TRACE-EVIDENCED-IN-CAPTURE` is emitted by the script but is not defined in PROTOCOL-A §5, which defines only `NOT-EVIDENCED-IN-CAPTURE` and `UNDETERMINED (CAPTURE_ERROR)`. The wording is still descriptive.

## 5. Coverage and absence semantics

**OK.** Both `00_meta.tsv` and `30_summary.tsv` state `capture LOCAL_REF_SET` and `completeness_vs_host NOT SHOWN`. The scope is the 25 local refs, including remote-tracking refs as last fetched and `refs/stash`, with 4020 commits walked. The absence rule is not triggered (3 ET, 0 errors). If it were, the script would emit `NOT-EVIDENCED-IN-CAPTURE`, never "no execution". Two minor notes: `refs/stash` (local, unpublished WIP) is walked as an ordinary ref without specific disclosure, and commits reachable only via reflogs or other worktrees' detached HEADs are outside the capture. Both are consistent with §4 as written.

## 6. Issues (none changes the result)

1. **`cc1a339` is a mention in a code-bearing commit, not WP-4C-1 execution** (WP-6 coverage work). C2 is over-inclusive: it has no subject test. *Medium*; no effect on the earliest trace.
2. **Self-contamination:** 21/51 matching commits are artefacts of this research programme, including the protocol's own commit `cf2f6b9`. This inflates DOC-OR-GOVERNANCE (47 vs 27 historical) and is the sole hit source of `a72528b`'s match apart from one `.claude` file. *Medium*; see R-SELF.
3. **Review inputs incomplete:** `TRACE-COMMITS.txt` has no `--stat` block for the 3 ET commits, and `a72528b`'s stat is truncated (53 of about 8,900 paths, no summary line). Changed paths were cross-checked against `14_changed_paths.tsv` instead. *Low.*
4. **`939e35d`:** its message mentions "4 production files + 4 test files" under *compile*. `14_changed_paths.tsv` shows 4 P-CODE + 2 P-TEST changed paths. Recorded only; the message may refer to files checked, not files changed. *Info.*
5. **`a72528b`:** the subject "docs: exclude large derived files from history" sits alongside about 8,900 changed paths spanning code, tests, storage and docs. Recorded only. *Info.*
6. **Result string** `EXECUTION-TRACE-EVIDENCED-IN-CAPTURE` is not defined in PROTOCOL-A §5. *Low*, spec/implementation gap.
7. **Not verifiable without execution:** `SHA256SUMS` contents, `script_sha256`, and the claim that the run from a subdirectory was byte-identical. *Info.*
8. **`refs/stash`** is walked as an ordinary ref without specific disclosure. *Info.*
