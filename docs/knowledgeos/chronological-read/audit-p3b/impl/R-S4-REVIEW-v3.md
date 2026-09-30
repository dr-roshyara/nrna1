# R-S4 Independent Adversarial Re-Review (v3, final check) — id-allocation lock fix

**Scope:** delta in `scripts/p3b_s5_audit_record.py` (+ its tests) since v2. Tests run: **134/134
pass** (same set as v2 plus 4 new: crash/idempotence and concurrency tests).

## Verdict: **IMPL-ACCEPTABLE**

The round-2 MATERIAL finding (unsynchronized `next_cor_number` id race) is genuinely fixed, and I
verified this destructively, not just by reading: neutering `_cor_lock` to a no-op makes
`test_concurrent_appenders_get_unique_contiguous_ids` (4 processes × 8 rounds × 3 records, `fork`,
with an injected sleep seam widening the race window) **fail**, tripping the code's own post-write
"a new cor_id does not occur exactly once" check — confirms the lock is load-bearing and the test is
not a placebo. With the real lock in place, all 134 tests pass, including id-uniqueness/contiguity
across the 4 processes and correct run-grouping.

## Attack findings

**Lock file placement vs. allowlist/check_paths — fine, not a gap.** The lock sidecar
(`cor_ap + ".lock"`) is created via raw `os.open`, not through `ops.check_paths`. But `main_r7`
calls `ops.check_paths([cor_ap])` (audit_record.py:262) *before* `append_corrections`/`_cor_lock` is
ever reached, and `P3B-CORRECTIONS.jsonl` is an **exact-match** allowlist entry (not a pattern) — so
`--corrections` can only ever resolve to that one path inside the repo; the lock file's name is then
deterministically `<that path>.lock`, not operator- or attacker-influenceable beyond what's already
gated. It is also never read as data (comment: "never read"), purely a mutex. No new attack surface.

**NFS/other-FS caveats — disclosed only partially.** The docstring/comment says "Linux/POSIX only"
(kernel-API availability) but does not call out that `fcntl.flock` is unreliable for cross-host
mutual exclusion on NFS (many exports silently no-op or only lock within one client). If
`chronological-read/` or the CR root is ever hosted on a shared network filesystem accessed from
multiple hosts, this lock would not actually serialize writers, silently reopening the exact race
this patch closes. **Minor, not blocking** — nothing in this repo's tooling suggests a multi-host
NFS deployment (the addendum's trust model treats "the orchestrator" as a single actor), but the
caveat should be one sentence in the docstring, not silently assumed.

**Idempotence comparison vs. a different date — correctly handled, verified in code.**
`append_corrections`'s idempotent branch recomputes `would = build(first, mine[0].get("date"))`,
i.e. it reuses the **already-recorded** date from the log, not "today," specifically so a re-run on
a later calendar day compares like-for-like instead of spuriously "differing" (or worse, spuriously
matching by coincidence). Confirmed the audit report's own canonical text (`made["txt"]`, whose hash
becomes `audit_report_output_sha256`) does not embed `date` at all, so report-sha determinism is
unaffected by which date is used.

**Idempotence comparison vs. a different model/content — correctly refused, not reused.** `build()`
closes over the *current* invocation's `findings` (hence `auditor_model_id`, dispositions, notes).
If a "re-run" is actually a different audit (different model id, different note text, different
disposition set), the recomputed `would` entries differ from the stored `mine` entries at the
`ops.canon()` comparison, and `append_corrections` raises "...already exist and differ from this
run's (append-only)" — ids are not reused for genuinely different content. Verified by
`test_crash_then_rerun_with_different_findings_refused`.

**`fsync` writer line format vs. `ops.append_jsonl` — identical.** Both use `open(path, "a",
encoding="utf-8")` and write `canon(r) + "\n"` per record (`ops.canon` is the same function in both
places); the manual writer only adds `flush()` + `os.fsync()` for durability. No format drift.

## Residual (assessed as acceptable, but should be disclosed): the same-run report race

The correction-log append is now correctly locked and ordered before the write-once report
(crash-safety fix confirmed by `test_crash_after_corrections_append_then_rerun_completes`: kill
between append and report write, re-run completes idempotently with identical ids, no duplication).
But `ops.write_new(out_ap, ...)` itself runs **outside** `_cor_lock`, and the `n_up == 0` fast path
(no AUDIT-UPHELD dispositions) skips the lock entirely, only doing an unlocked `_read_log` existence
check. Two concurrent invocations of the **exact same (batch, run)**:
- with **identical** findings (a genuine retry): both converge on identical correction-record
  content under the lock (harmless, proven safe); the report write race only affects which
  process's `run_timestamp_utc`-bearing header physically survives `os.replace` (headers differ,
  bodies are identical) — cosmetic, not a correctness issue, and whichever file is actually on disk
  is what `check_evidence`/acceptance will hash and use.
- with **genuinely different** findings (one producing `n_up=0`, one `n_up>0`) racing on the exact
  same run: the `n_up=0` path could win `write_new` first, leaving the other process's *already
  durably appended* correction records referencing a report sha that never lands on disk (that
  process instead gets a "refusing to overwrite" refusal at the very end, after its corrections were
  already committed).

This second sub-case is a genuine inconsistency, but only reachable by auditing the *same* run twice
concurrently with materially different findings — an operationally dubious scenario in its own
right (not the "many different batches in flight" scenario that motivated the original, now-fixed
finding), and it is detectable after the fact (a correction record's `audit_report_output_sha256`
would not match the sha of whatever `S5-AUDIT-<run>.json` actually exists) before any H-06
acceptance. **Assessed acceptable to ship as-is**; recommend, as a follow-up not a blocker: either
extend `_cor_lock` to also cover the `n_up == 0` existence check and the final `write_new` call, or
at minimum add one sentence disclosing this residual the way the module already discloses others
("Residual (recorded, EG-9): ...").

## Bottom line

The concurrency fix is real, proven by a genuine multi-process test that fails when the lock is
removed, and it correctly handles both of the specific idempotence traps asked about (a differing
date, a differing model/content). No MATERIAL finding remains. Two minor, non-blocking disclosure
recommendations (NFS caveat one-liner; same-run report-write race one-liner) — neither changes the
verdict.
