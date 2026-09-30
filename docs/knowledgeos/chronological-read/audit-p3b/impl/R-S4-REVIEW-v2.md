# R-S4 Independent Adversarial Re-Review (v2) — EG-9 (audit_record R7 / accept tranche)

**Scope:** `git diff -- scripts/p3b_s5_common.py scripts/p3b_s5_audit_record.py scripts/p3b_s5_accept.py`
(uncommitted) + `scripts/tests/test_p3b_s5_audit_record_r7.py`, `scripts/tests/test_p3b_s5_accept_tranche.py`.
Tests run: **130/130 pass** (`test_p3b_s5_audit_record_r7`, `test_p3b_s5_accept_tranche`,
`test_p3b_s5_audit_record`, `test_p3b_s5_ops_state`, `test_p3b_s5_ops_audit_sample`,
`test_p3b_s5_ops_guard`, `test_p3b_s5_r7_properties`). `test_p3b_s5_r7_universe` not run, per
orchestrator note (addendum mid-edit).

## Verdict: **IMPL-NEEDS-REVISION** (narrow — one new finding; both v1 findings solidly closed)

## Prior findings — disposition

**M1 (round 1, correction artifact/id namespace) — CLOSED, faithfully.** Corrections now go to
`P3B-CORRECTIONS.jsonl` at CR root (`CORRECTIONS = "P3B-CORRECTIONS.jsonl"`, audit_record.py:51),
ids `P3B-COR-####` via `next_cor_number()` (audit_record.py:145-158), and each record carries
**exactly** the rev3 §12.3 field set — `{target_record, target_artifact, reason, evidence,
new_record_ref, author_role, date}` — plus `cor_id`/`status`/`batch_id`/`run_id`/`attempt`
(audit_record.py:161-175). Richer material (field, disposition, audit_report,
audit_report_output_sha256, assembly_sha256, auditor_model_id) is correctly nested *inside*
`evidence`, one of the §12.3 fields, rather than added as sibling top-level keys — a clean way to
stay faithful to "exactly the §12.3 fields" while keeping the extra detail. `new_record_ref` is
always `null` (R-I: never a bypass write). Verified against `EG5-V2.8-DECISION-PACKAGE.md:402`
("the rev3 §12.3 mechanism, P3B-CORRECTIONS.jsonl") — now matches literally.
`test_default_corrections_log_is_the_rev3_artifact` pins both the filename and that the string
`"S5-AUDIT-CORRECTIONS"` no longer appears in the source; `test_audit_upheld_corrections_recorded_
apart_from_assembly` pins the exact field set via `COR_FIELDS`. Non-vacuous, correct.

**M2 (round 1, missing `check_paths` on tranche/glog reads) — CLOSED.** `check_tranche` now calls
`ops.check_paths([tp])` and `ops.check_paths([gp])` (accept.py:93-94) before opening either file.
`p3b_s5_common.py` gained the matching pattern `r"audit-p3b/H06-TRANCHE-\d{4}\.json"`. New test class
`M2AllowlistGate` exercises both the positive path (allowlisted names pass; a non-existent-but-
allowlisted path fails later, on existence, not on the gate) and the negative path (non-allowlisted
tranche/glog names refused with "allowlist" in the message; a forbidden-census-name scratch file
outside the repo is still refused via the pre-existing forbidden-name check). Confirmed by re-running
`ops.check_paths` directly: `audit-p3b/H06-TRANCHE-0001.json` and `P3B-GOVERNANCE-LOG.md` both pass.

**T-16 (attempt-aware tranche binding) — landed and genuinely exercised.** `T16AttemptRebinding`
drives OB0004 through a real `stm.transition(... "FAILED", failure_class="X", ...)` →
`stm.new_attempt(...)` → a second DISPATCHED/PROPOSED/VERIFIED/AUDITED cycle, confirms
`current_attempt == 2`, then shows a tranche listing attempt 1 is refused ("attempt 1, not the
current OB0004-R7 attempt 2") and a tranche listing attempt 2 is accepted, reaching ACCEPTED. This is
the real `new_attempt`, not a stub — good, no longer vacuous.

## Firewall attack on the three new allowlist entries (specifically requested)

Verified empirically against the live `p3b_s5_common.ALLOWLIST`/`ALLOW_PATTERNS`
(`re.fullmatch`, as `check_inputs` uses it) with adversarial probes:

1. **`P3B-CORRECTIONS.jsonl`** — added as an **exact-match** allowlist entry, not a pattern. No
   widening possible (no wildcard, no directory prefix).
2. **`audit-p3b/H06-TRANCHE-\d{4}\.json`** — `fullmatch`-anchored, `\d{4}` and a literal `.json`
   suffix. Probed `H06-TRANCHE-1.json`, `H06-TRANCHE-00011.json`, `H06-TRANCHE-0001.json.evil` →
   all correctly denied. No traversal, no suffix/prefix slack.
3. **`ledger-p3b-r2/OB\d{4}-R7\.A\d+/RETIREMENT-(INTENT|COMPLETE)\.json`** — the one flagged for
   attack. Probed within a synthetic retirement directory `OB0004-R7.A2/`:
   `RETIREMENT-INTENT.json` and `RETIREMENT-COMPLETE.json` → **allowed** (as intended);
   `objects.jsonl`, `register.jsonl`, `p1-gap-capture.jsonl`, `READ-LOG.jsonl`, `WITNESS.jsonl`,
   a subdirectory variant, and a `.bak`/`-extra` filename variant → **all denied**. No existing
   pattern accidentally covers the `.A\d+` attempt directory either (every other
   `ledger-p3b-r2/OB\d{4}-R7...` pattern requires the directory name to be exactly `OB####-R7`
   optionally followed by `-L\d\d...`; a literal `.A2` breaks that match, confirmed empirically). So
   **the retired attempt's actual record content stays outside the allowlist** — the pattern admits
   exactly the two retirement-marker files, as the orchestrator's question assumed it should. (Who
   reads this pattern: `p3b_s5_state.check_retirement`, part of EG-6 (d), out of this slice's direct
   scope but confirmed to call `ops.check_paths` only on the COMPLETE file; the sibling INTENT file's
   path is derived from the already-validated COMPLETE path's directory plus a hardcoded literal
   filename, so it needs no separate gate call.)

**Is `P3B-CORRECTIONS.jsonl` a leak path for §21 material into θ_D/θ_A inputs?** No code-level
pathway found. `p3b_s5_common.ALLOWLIST`/`check_inputs` is the gate for the **S5-operations tool
family** (`p3b_s5_verify.py`, `p3b_s5_audit_record.py`, `p3b_s5_accept.py`, `p3b_s5_state.py`,
`p3b_s5_prepare.py`, etc.) — a search of `scripts/*.py` for any θ_D/θ_A/re-analyst machinery found
**none**; EP-01's blind re-analysis is a separately dispatched process (its own contract/prompt,
reading `32-RECONCILIATION-OBJECTS.jsonl` and a Freeze-2/I(run)-scoped slice) that does not consult
this allowlist at all. Nothing in the diff makes any tool *read* `P3B-CORRECTIONS.jsonl` except
`p3b_s5_audit_record.py` itself, for its own idempotency check (`next_cor_number`) and append — a
closed loop within the §21 tool, not an input to anything else. The allowlist entry is necessary
only so that this internal read/write is disciplined the same way every other S5-tool I/O is
(consistent with the M2 fix's own rationale). The firewall rule "no §21 material in θ_D/θ_A inputs"
is enforced elsewhere (by not naming this file in EP-01's own reading contract), not by this
allowlist — correctly so, since the allowlist's role is "don't let an S5 tool read outside its
declared inputs," not "declare what every tool must read." No leak found.

**The R2 verifier (`p3b_s5_verify.py`) itself**: its documented inputs (docstring) do not name
`P3B-CORRECTIONS.jsonl`, `H06-TRANCHE-*`, or the retirement files, and nothing in this diff adds
such a read. Being allowlisted ≠ being read; confirmed no code change causes the verifier to open
any of the three new paths.

## NEW finding (MATERIAL): `next_cor_number` id allocation is not safe against concurrent/interleaved invocation

`audit_record.py:145-158`:
```python
def next_cor_number(cor_ap, run):
    if not os.path.exists(cor_ap):
        return 1
    nums = []
    for r in ops.read_jsonl(cor_ap):
        ...
        nums.append(int(m.group(1)))
    return max(nums, default=0) + 1
```
called once in `main_r7` (line ~207) as `first = next_cor_number(cor_ap, run)`, then later
`ops.append_jsonl(cor_ap, correction_records(...))` (line ~231) — a **read-compute-append** sequence
with no lock. `ops.append_jsonl` opens in `"a"` mode with no `fcntl`/`flock` — confirmed by grep,
**no locking primitive exists anywhere in `scripts/*.py`** for this file or any other append-only
artifact. I reproduced the race directly:

```
first_a = ar.next_cor_number(cor, 'OB0004-R7')   # -> 1
first_b = ar.next_cor_number(cor, 'OB0005-R7')   # -> 1   (both read the same, still-empty file)
```
Both calls return `1`. If both invocations then proceed to append (as two concurrently-running
`p3b_s5_audit_record.py --run OB0004-R7 ...` and `--run OB0005-R7 ...` processes each producing an
AUDIT-UPHELD disposition would), `P3B-CORRECTIONS.jsonl` ends up with **two different records both
named `P3B-COR-0001`**, for two different batches/target_records. That breaks the log's own
identifier-uniqueness invariant (`cor_id` is meant to be a stable, citable reference — S7's future
consumption of these records, and any human audit trail, depends on `cor_id` being unique) and is
silent: nothing in `next_cor_number`, `correction_records`, or the write path detects or refuses it.
No test exercises concurrent/interleaved invocation (`test_next_free_id_append_only` only checks
strictly sequential, single-process allocation with a pre-seeded log).

Nothing in the runbook, the addendum, or the EG-9/EG-6 working papers states that §21 audits across
different batches are serialized through a single writer (I grepped for "concurrent"/"parallel" in
the runbook and decision package — no statement either way). Given 396 batches with a stated cadence
of cross-batch audits "every 5 batches" (P17 item 3) and independent per-batch orchestrator sessions
(EG-6: "a fresh orchestrator session" per retry), concurrent or closely-overlapping `p3b_s5_
audit_record.py` invocations across different batches are a realistic operating mode, not a
contrived edge case.

**Secondary, same-root-cause hazard — write ordering is not crash-safe.** `main_r7` writes the
immutable audit report (`ops.write_new(out_ap, ...)`, which already embeds the assigned
`corrections.ids`) **before** appending the correction records themselves
(`ops.append_jsonl(cor_ap, ...)`). A crash/kill between those two lines leaves a **write-once**
report on disk that references `cor_id`s never actually written to `P3B-CORRECTIONS.jsonl` — and
because the report's existence is what makes AR refuse a re-run for that `run` ("the audit report
exists (append-only)"), there is no retry path to complete the missing append. (Reversing the order
does not eliminate the hazard, it only moves it: writing corrections first, then crashing before the
report, would make a retry blocked by `next_cor_number`'s "correction records for this run already
exist" check while never getting a report written either.) This is architecture-level — not a
one-line fix — but worth recording alongside the concurrency finding since both stem from treating a
two-file side effect as if it were atomic.

**Fix, minimal and local (does not require a redesign):** serialize `next_cor_number` + the
corresponding append with a simple advisory lock (e.g. `fcntl.flock` on `cor_ap` or a sibling
`.lock` file, held across read-compute-append), and/or verify-after-write (re-read the log
immediately after appending and confirm no duplicate `cor_id` exists, raising if so — cheap and
catches the race even without a lock, since only the loser would ever observe the duplicate). Given
this codebase already treats `P3B-STATE.json` as hash-chained/tamper-evident for exactly this kind
of integrity concern, extending the same care to `P3B-CORRECTIONS.jsonl`'s id space is consistent
with its own stated discipline, not a new standard being imported.

## Everything else re-checked from round 1 — still holds

- R2/pre-R7 behaviour byte-identical (`test_r2_forms_unchanged`, `T12RevisionBelow7` both pass).
- No path found to reach ACCEPTED without a genuine, bound §21 audit and a listed tranche; checks
  (a)-(f) all still gate on live state/evidence, not on tranche-declared values.
- Class-H / state-interplay with the concurrently-landed EG-6 `_failure_fields` mechanics: correct
  (`frm in PASS_STATES` → default class H; `new_attempt` correctly refused afterward).
- Static Freeze-2 firewall scan (`E910Firewall.test_static_no_freeze2_reference`) still present and
  passing for `p3b_s5_audit_sample.py`/`p3b_s5_audit_record.py`.
- Tests are non-vacuous throughout — concrete expected hashes/ids/state values, not smoke tests.

## Bottom line

M1, M2 and T-16 are all faithfully and non-vacuously closed — good work, and matches the approved
package text exactly for M1 now. The one new, ship-blocking issue is the unsynchronized
`next_cor_number` id allocator: it is a genuine, empirically-reproduced collision risk on a shared,
append-only audit-trail artifact, unaddressed by any lock or post-write check and untested under
concurrency. Recommend a small locking or verify-after-write patch to `next_cor_number`/the append
call before this ships, then re-run the suite. Nothing else found rises to MATERIAL.
