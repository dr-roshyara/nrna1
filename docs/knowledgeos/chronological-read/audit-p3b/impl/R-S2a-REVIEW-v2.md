# R-S2a — Re-review v2: p3b_s5_state.py EG-6 attempts/W-SYS slice (fixes for MATERIAL-1/2)

Scope: full `git diff -- docs/knowledgeos/chronological-read/scripts/p3b_s5_state.py` (uncommitted, re-read hunk by hunk
end to end because of the reported process deviation) + `scripts/tests/test_p3b_s5_state_attempts.py` (now 633 lines,
up from 526).

Tests run: `cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_state_attempts test_p3b_s5_ops_state test_p3b_s5_state_r7_evidence test_p3b_s5_audit_record` → **87/87 OK** (was 80; 7 new tests, only pre-existing ResourceWarnings). `test_p3b_s5_r7_contract_rebind` not run, per instructions.

## Verdict: **IMPL-NEEDS-REVISION** (narrow — governance record, not code)

Both MATERIAL findings from the v1 review are correctly fixed and non-vacuously tested. The closed INCOMPLETE
RR-7 gap is real but only partially effective, which is acceptable given M = 4. The one remaining blocker is that
the resume mechanism silently introduces a new rule — **W-SYS recounts from zero after a human resume** — that is
not in any approved spec text and has a real, disclosed-nowhere statistical consequence. That needs a governance
record (a G-LOG / pre-registration-deviation entry) before this ships, not a code change. Everything else is sound.

---

## Process-deviation check: full hunk-by-hunk read for unintended changes

`git diff` between the committed baseline and the current working tree shows every changed line (nothing outside a
shown hunk differs), so this sweep is complete by construction, not just spot-checked.

- `python3 -c "import ast; ast.parse(...)"` → syntax OK.
- `grep -n "^def \|^class "` → exactly one definition of every function/class (`check_evidence`, `_append`,
  `transition`, `wsys_status`, etc.) — no stray duplicate from a partial/overlapping string replacement.
- Diffed hunk by hunk against the v1 diff I reviewed: the module docstring, imports, constants block, `check_evidence`,
  `_append`, `run_history`, `current_attempt`/`_r7_entries`/`batch_evidence_shas`/`_attempt_failure`/`rr7_reason`/
  `retirable`/`check_retirement`/`new_attempt`/`_binom_tail_scaled`/`wsys_triggers`/`wsys_threshold`/`load_guard` are
  **byte-identical** to v1 except `new_attempt`'s W-SYS check line (below) — confirmed by direct comparison, not
  inference.
- No hunk touches `revision_transition`, `rebind_manifest`, `resume`, `add_comparison`, `comparison_transition`,
  `init`, `load`/`save`/`verify_chain` — all untouched, as expected.
- **Conclusion: the sed-style edit did not corrupt anything outside its intended targets.** The process violation
  (not using Read/Edit) is a real repository-rule breach worth recording as a process finding on its own, but it
  produced no latent defect in this instance — I verified that claim rather than taking it on trust.

---

## MATERIAL-1 (v1): W-SYS non-monotonicity — **FIXED, correctly, non-vacuously tested**

`p3b_s5_state.py:507`:
```python
elif e["to"] in ("FAILED", "INCOMPLETE") and final.get(key) != "PASS":   # a pass is never uncounted
```
Exactly the fix recommended in v1. `wsys_status`'s docstring now states the invariant explicitly (`:490-492`,
"Monotone and audit-independent… a later audit-stage FAILED … never removes or re-classes it").

**Test check — non-vacuous, not just present:**
- `test_d_s_h_u_not_counted` (`test_p3b_s5_state_attempts.py:442-448`) now asserts `(n, f) == (1, 0)` after the
  H-after-VERIFIED case (was `(0, 0)` in v1 — correctly updated, not left stale).
- New `test_n_f_monotone_under_later_audit_stage_entries` (`:450-465`) is the strong version: takes a `wsys_state(13,7)`
  scenario that is *already at the n=20 stop threshold* (`n,f,stop == 20,7,True`), then appends H/D/S audit-stage
  FAILED entries on top of the 13 already-VERIFIED batches and re-checks `wsys_status` is unchanged. This would fail
  hard on the pre-fix code (those 13 batches would be dropped from `n`, `n` would fall to 7, `f/n` would look far
  worse and, depending on which ones are touched, `stop` could flip in either direction) — i.e. it directly exercises
  the exact mechanism of the bug, not just the earlier minimal repro. Non-vacuous confirmed by re-running the fixed
  suite (87/87 green) and by my own earlier sandboxed repro of the pre-fix behaviour (documented in v1).

No residual concern here.

---

## MATERIAL-2 (v1): durable WSYS-STOP recording — **FIXED**, well engineered, but introduces an unregistered rule

**What was added** (`p3b_s5_state.py:533-574`):
- `_last_seq(st, kind)` — last history `seq` of a given `run_kind`.
- `wsys_stopped(st)` — true iff a `WSYS-STOP` entry exists with no later `WSYS-RESUME`.
- `wsys_stop(st, ...)` — idempotent: appends one `kind="WSYS-STOP"`, `failure_class="D"` history entry with a
  `{n, f, threshold, look}` snapshot, only if `wsys_status(st)["stop"]` and not already recorded; returns whether it
  appended.
- `wsys_resume(st, glog, actor, reason)` — requires an active stop, a `G-LOG-\d{4}` reference and a reason; appends a
  `WSYS-RESUME` entry.
- `new_attempt` now checks `wsys_stopped(st) or wsys_status(st)["stop"]` (`:434`) — the durable record is authoritative
  even if a hand-built/edge-case history would make the recomputed status say "not stopped"
  (`test_recorded_stop_blocks_even_if_status_would_not`, `:509-515` — a genuine defense-in-depth test, not a tautology).
- CLI: `wsys --record` / `wsys-resume --glog … --reason … --actor …`, both wired to `save()` only when something was
  actually appended.

This closes T146 (`EG6-SPEC-A-v3.md` §2) exactly as specified: "STOP … class D recorded" — verified by
`test_stop_record_t146` (`:489-507`), which checks the exact entry (`run_kind`, `batch_id="*"`, `failure_class="D"`,
the `{n,f,threshold,look}` snapshot) and idempotence. Hash-chain integrity holds (`_append` computes `entry_sha256`
over the WSYS-STOP/RESUME entries the same as any other; `verify_chain(st)` is asserted in the test).

### Open item: "count afresh from entries after the last resume" is engineering-necessary but statistically undisclosed

`wsys_status` (`:493-497`): `start = _last_seq(st, "WSYS-RESUME")`; every history entry with `seq <= start` is skipped.
So after a resume, `n` and `f` restart at 0 and W-SYS needs a fresh K = 20 before its next look.

**Why this exists, and why it's not arbitrary:** the stop rule is deliberately *sticky* — once a look at n = 20 trips,
`stop_at` never clears even if a later look (n = 40, f below that look's own threshold) would not have tripped
(`test_stop_found_at_an_earlier_look_persists`, unchanged from v1). Without *some* mechanism to drop the pre-resume
evidence from consideration, `wsys_resume` would be close to a no-op: the very next `wsys_status()` call would
recompute the same historical `looks` list and immediately re-report `stop = True`, since nothing about "we resumed"
is otherwise visible to a pure, history-derived recomputation. `test_resume_requires_a_stop_and_a_glog_and_reopens_attempts`
(`:517-535`) demonstrates this is exactly what makes resume functional: post-resume `(n, f, stop) == (0, 0, False)`
and a `new_attempt` that was previously refused now succeeds.

**Why it still needs a governance record, not just code:**
1. **It is not in any approved spec text.** The decision package §14, `EG6-SPEC-A-v3.md` §2 and the addendum §2.8
   text all say only "on a stop … the event is class D, and resumption needs a human act" — none of the three
   describes *how counting behaves across a stop/resume cycle*. This is a new parameter of the pre-registered rule,
   invented in code, not derived from an approved document.
2. **It has a real, disclosed-nowhere statistical cost.** W-SYS's whole design gets *more* sensitive to a persistent
   elevated failure rate as `n` grows (the required q̂ to trigger drops from 0.35 at n=20 to 0.158 at n=800,
   `EG6-SPEC-A-v3.md` §2 table) — that is the entire point of the sequential K=20 cadence over the *whole* programme.
   Resetting to `n = 0` after every resume throws away that accumulated sensitivity and restarts the weakest test
   (n=20, needs f≥7) every time. If the underlying cause was *not* actually fixed — and per the pre-registered rule
   itself, **"Repairs. Instrument repairs (runbook, prompt) are admissible only at the canary gate"** (decision
   package §14 / addendum §2.8), i.e. a mid-programme resume cannot involve a real instrument fix — then the
   post-resume test has deliberately weaker power against exactly the persistent problem W-SYS exists to catch, for
   as long as it takes to reaccumulate 20 attempts. That is a substantive, not cosmetic, change to the pre-registered
   operating characteristics that were reviewed and approved (EG6-SPEC-A-v3 §2's simulated false-stop-rate /
   power table did not model any reset).
3. **This repository's own governance discipline requires the decision before the code**, not after: "a test encodes
   a decision; the decision must exist first" / "Finding → Architecture Decision → RED → GREEN → Certification. Never
   `Finding → Implementation → Certification`" (project `.claude/CLAUDE.md`, "Development Discipline" standing rule).
   The reset rule was implemented and tested without an antecedent decision record.

**This is not a request to change the code.** The reset-after-resume behaviour is almost certainly the right
engineering answer given the sticky-stop design (an alternative — e.g. re-arm only the `stop_at` sticky flag on
resume while keeping cumulative `n`/`f` — would need its own justification for why the old evidence should still
count toward a rule meant to detect a *current* elevated rate). What's missing is the paper trail: a G-LOG /
pre-registration-deviation entry that states the reset rule explicitly, justifies it against the sticky-stop
mechanics, and discloses that post-resume sensitivity is n=0-restarted (so anyone reading the eventual E-3 report
knows a resume, if the cause wasn't fixed, buys the systemic problem another ~20 attempts of runway before W-SYS can
catch it again). **Recommend:** this governance record lands before first production use of `wsys-resume` (it does not
block landing the code/tests, since nothing here is pre-canary-frozen yet — but per HD-6.2/HD-6.3's own "fixed before
the canary" discipline for the sibling constants, this should get the same treatment before the canary, not
retroactively).

---

## The closed INCOMPLETE RR-7 gap (v1 finding #7): derived signature closes the letter, not fully the spirit

`p3b_s5_state.py:221-224, 274-275`:
```python
def incomplete_signature(cause):
    return c.sha256_bytes(("INCOMPLETE:" + str(cause)).encode("utf-8"))
...
if to == "INCOMPLETE" and failure_signature is None:
    failure_signature = incomplete_signature(reason)
```
Every R7 INCOMPLETE now unconditionally carries a signature (explicit or derived) — confirmed by
`test_incomplete_always_carries_a_signature` (`:121-133`), which also confirms an explicit signature still overrides
the derived one and is still hex64-validated.

**Verdict: acceptable, not a defect, but the protection it buys is narrower than "RR-7 now covers INCOMPLETE" might
suggest.** The derived signature is `sha256("INCOMPLETE:" + reason)`, so two INCOMPLETEs trigger RR-7 (→ class D,
stop) only if their `--reason` text is **byte-identical**. The builder's own new test demonstrates both sides of this
directly (`test_two_identical_incompletes_are_rr7_class_d`, `:135-147`): the *same* cause string on two consecutive
attempts → RR-7 fires; a *different* cause string ("interrupted" vs "session limit") for what could be the identical
underlying defect → RR-7 does not fire. This isn't an adversarial-evasion concern (nobody is gaming it on purpose);
it's that real interruption diagnostics in an orchestrator very often carry incidental variable detail (elapsed time,
timestamps, session/PID, retry counters), which would silently prevent RR-7 from ever tripping on INCOMPLETE even when
the *same* root cause repeats. Given:
- M = 4 still bounds the per-batch cost regardless (RR-5, unconditional cap),
- every counted INCOMPLETE(A1) still feeds W-SYS's programme-level `f` (`WSYS_COUNTED`), so a genuinely systemic
  timeout defect is still caught by W-SYS even if RR-7 never trips for any one batch,

this is **acceptable as shipped**, but I'd recommend (non-blocking) that the runbook/orchestrator be told explicitly
that `--reason` on an INCOMPLETE is not free text for RR-7's purposes — it should be (or start with) a small, fixed
diagnostic code if repeated-cause detection is meant to work in practice, since nothing in `p3b_s5_state.py` enforces
that discipline.

---

## No revision < 7 behaviour change — confirmed

`_failure_fields`'s `if not r7 or to not in ("FAILED","INCOMPLETE"): … return {}` early-return (`:230-233`) still
precedes the signature-derivation step, so a revision < 7 batch never gets a derived signature or any new field.
`test_class_is_refused_outside_r7_and_outside_failures` is unchanged and green; the full pre-existing
`test_p3b_s5_ops_state.py` (24 tests, unrelated to EG-6) is unchanged and green (part of the 87/87 run).

## Everything else re-checked from the v1 attack list

Re-verified against the new diff: at-most-one-PASS path (unchanged, still airtight — the new W-SYS check is additive,
doesn't touch the PASS gate), attempt numbering (unchanged), hash-chain coverage now also covers WSYS-STOP/RESUME
entries (`_append` treats them uniformly), `check_retirement` (byte-identical to v1). No new findings there.

---

## Fix list
1. **Governance:** record the post-resume "W-SYS recounts from zero" rule as an explicit pre-registration-deviation /
   G-LOG entry before `wsys-resume` is used in production, with the disclosed consequence (reduced near-term power
   against a persistent, un-repaired cause immediately after resume). No code change requested.
2. **Recommended, non-blocking:** document operationally that `--reason` for an RR-7-meaningful INCOMPLETE must be a
   stable diagnostic code, not free text, or RR-7 will rarely trip on repeated timeouts in practice.
3. **Recommended, non-blocking:** note the process deviation (exact-string-replacement instead of Read/Edit) for the
   record; no defect resulted this time, but it is exactly the failure mode ("the change succeeds without the author
   understanding it… a slightly-too-broad pattern corrupts look-alike sites silently") the repository's own Source
   Code Editing Policy exists to prevent, and I would not assume the same luck on a future pass.
