# R-S2a — Re-review v3 (delta since v2): cumulative W-SYS counting after resume

Scope: delta since v2 in `p3b_s5_state.py` (`wsys_status`, `wsys_resume`, CLI `wsys-resume --count-from`) and the new
`WsysResumeCounting` tests in `test_p3b_s5_state_attempts.py` (now 704 lines, +71 vs. v2's 633). Edits made with
Read/Edit this round (not the sed-style tool from v2's process deviation).

Tests: `cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_state_attempts test_p3b_s5_ops_state test_p3b_s5_state_r7_evidence test_p3b_s5_audit_record` → **92/92 OK** (was 87; +5 new tests). `test_p3b_s5_r7_contract_rebind` not run, per instructions.

## Verdict: **IMPL-ACCEPTABLE**

The v2 governance concern is resolved by an architecture change, not a documentation patch: the **default** is now
cumulative (faithful to "§2.8 pre-registers no reset"), and the reset behaviour from v2 survives only as an explicit,
recorded, opt-in human deviation (`count_from="resume"`, required at the `wsys_resume` call site, stored durably on
the WSYS-RESUME entry). No unintended changes found elsewhere in the file; `new_attempt` is byte-identical to v2.

## Correctness of "a look before the resume point was spent by the stop"

`p3b_s5_state.py:519-531`. Each key in `seen` carries `(key, seq)` — the seq of the history entry that first made that
attempt terminal (VERIFIED/FAILED/INCOMPLETE); `seen` is built by iterating `st["history"]` in append order, so these
seqs are strictly increasing as `n` grows. A look at a K-boundary is `live` iff `rseq is None or seq > rseq`, where
`seq` is the seq of the specific entry that completed *that* look and `rseq` is the most recent WSYS-RESUME's own seq.
`stop_at` is only set from a live, triggering look.

This is sound:
1. **Necessary, not arbitrary.** Without it, `wsys_resume` would be close to a no-op under default cumulative
   counting: the same historical look that originally triggered would still be present in `seen` on every
   recomputation and would immediately re-set `stop_at` (the first-hit-wins `stop_at is None` guard doesn't care how
   old the evidence is). Marking it "spent" is what makes resume do anything.
2. **The partition is well-defined and race-free.** History is append-only and `seq` monotonic by construction
   (`_append` sets `seq = len(history)` before appending, so `st["history"][seq]` always round-trips to that exact
   entry). Since `seen`'s entries are seq-increasing, the live/spent boundary flips exactly once, at the first look
   whose completing entry has `seq > rseq` — there's no way for a later-computed look to be misclassified relative to
   an earlier one.
3. **"The next look re-tests the whole record" is correctly cumulative, not windowed.** Under the default
   `count_from="all"`, nothing is pruned from `seen`/`n`/`f` — the next K-boundary look evaluates `wsys_triggers(n, f)`
   on the true cumulative totals since programme start, exactly as the docstring claims and as
   `test_cumulative_restops_at_the_next_failing_look` (`:565-572`) demonstrates concretely: pre-resume n=20,f=7
   (stop); resume; 20 more attempts (13 pass, 7 fail) push cumulative n=40, f=14; threshold at n=40 is 11 (matches the
   spec's own table); `14 ≥ 11` → re-stops, with the exact `{n:40,f:14,threshold:11,look:40}` snapshot recorded. This
   is the **conservative** direction called for by "§2.8 pre-registers no reset": a persistent, un-repaired cause
   gets at most ~20 attempts of runway before the watchdog re-fires using all the evidence, not a fresh, reset-blind
   20. `test_cumulative_continues_when_the_next_look_passes` (`:574-579`) is the needed complementary case — cumulative
   mode does *not* spuriously re-trigger when the post-resume evidence genuinely brings the ratio back under
   threshold (n=40, f=7 < 11) — so the "spent" mechanism isn't just a one-way ratchet; it correctly lets the test
   pass too.
4. **The opt-in reset path is properly gated.** `count_from="resume"` is never a tool default (CLI default is `"all"`,
   `wsys_resume`'s own default is `"all"`); choosing it requires the same G-LOG-referenced, reasoned human act as any
   resume, and it is stored durably (`e["count_from"]`) so it's auditable after the fact — this is exactly the
   "recorded pre-registration deviation" I asked for in v2, now structural rather than a request. Boundary-tested at
   the reset threshold itself (`test_count_from_resume_restarts_the_count` n=20,f=6→continue vs.
   `test_count_from_resume_restops_on_its_own_look` n=20,f=7→stop).
5. **CLI wiring correct**: `wr.add_argument("--count-from", choices=WSYS_COUNT_FROM, default="all", ...)` →
   `wsys_resume(st, a.glog, a.actor, a.reason, count_from=a.count_from)` (`:712-713, 763`), covered by
   `test_cli_count_from` (`:596-606`).

Tests are non-vacuous: `test_cumulative_restops_at_the_next_failing_look` would fail under v2's hard-reset-always
code (it would see fresh n=20 not cumulative n=40) and would fail under a hypothetical "always cumulative, no live
gate" implementation too (an unguarded loop would already have re-flagged `stop=True` immediately post-resume from
the *original* n=20 look, before the 20 new attempts even run — the test's setup asserting the pre-resume state
implicitly, and the distinct n=40/f=14 numbers, pin down the live-gate behaviour specifically).

## Minor, non-blocking note

Multi-resume composability: if resume #1 uses `count_from="resume"` (an explicit reset) and a *later* resume #2 (after
a new stop) uses the default `count_from="all"`, counting reverts to cumulative-since-programme-start, silently
un-doing resume #1's reset (only the *most recent* resume's `count_from` governs, by design — `rseq`/`count_from` are
read from `_last_seq(st, "WSYS-RESUME")` alone, `p3b_s5_state.py:497-498`). This is a narrow, multi-cycle edge case,
not exercised by any test, and arguably a reasonable reading of "each resume is its own independent human decision" —
but it means a genuine prior repair's reset point can be silently re-absorbed by a later default resume. Worth a
one-line callout in the eventual G-LOG record (recommended, not blocking).

## Unrelated note (acknowledged, out of scope)

The retirement-record allowlist pattern added to `p3b_s5_common.py` by another slice is outside this file's diff and
this review's scope; `check_retirement`'s own logic in `p3b_s5_state.py` is unchanged since v2 and unaffected.

## Carried‑forward items from v1/v2 (unchanged, still non-blocking)
- Derived INCOMPLETE signature (`incomplete_signature`) still only matches byte-identical `--reason` text, so RR-7 on
  INCOMPLETE remains evadable by ordinary free-text variation — acceptable given M=4 + W-SYS backstop, recommend
  operational discipline (fixed diagnostic codes) rather than a code change.
- The v2 process-deviation note is now moot: this round's edits used Read/Edit as required.
