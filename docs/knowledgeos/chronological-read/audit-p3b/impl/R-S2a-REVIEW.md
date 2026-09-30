# R-S2a — Adversarial review: p3b_s5_state.py EG-6 attempts/W-SYS slice

Scope: `git diff -- scripts/p3b_s5_state.py` (uncommitted) + new `scripts/tests/test_p3b_s5_state_attempts.py`.
Tests run: `cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_state_attempts test_p3b_s5_ops_state test_p3b_s5_state_r7_evidence test_p3b_s5_audit_record` → **80/80 OK** (only ResourceWarnings, pre-existing/unrelated). `test_p3b_s5_r7_contract_rebind` not run per instructions (known addendum-mid-edit failure).

## Verdict: **IMPL-NEEDS-REVISION**

One MATERIAL correctness bug (non-monotonic W-SYS denominator) and one MATERIAL spec-conformance gap (W-SYS stop is never recorded as a class-D event, and the spec's own T146 is unimplemented). Both are narrowly scoped, one-area fixes; everything else in the diff is sound.

---

## MATERIAL-1: W-SYS `n` is non-monotonic — a later audit-stage FAILED(H) retroactively erases an already-counted PASS

`p3b_s5_state.py:481-497` (`wsys_status`):

```python
481   seen, final = [], {}
482   for e in st["history"]:
...
487       if e["to"] in ("VERIFIED", "FAILED", "INCOMPLETE"):
488           if key not in final:
489               seen.append(key)
490           if e["to"] == "VERIFIED" and key not in final:
491               final[key] = "PASS"
492           elif e["to"] in ("FAILED", "INCOMPLETE"):
493               cls = e.get("failure_class") or "D"
494               ...
497               final[key] = cls
```

When an attempt reaches VERIFIED and is *later* FAILED from VERIFIED/AUDITED (class H, an audit FAIL or H-06 rejection — `_failure_fields` default at `p3b_s5_state.py:221-243`), line 497 unconditionally overwrites `final[key]` from `"PASS"` to `"H"`. In the counting loop (`p3b_s5_state.py:500-506`), `cls == "H"` is neither `"PASS"` nor in `WSYS_COUNTED`, so the whole attempt is dropped — not counted as a pass, not counted as a failure. It simply disappears from `n`.

**Reproduced** (sandbox copy of the diff, `probe_wsys_bug.py`, not committed):
```
before H-fail: n,f = 1 0
after  H-fail: n,f = 0 0
```
Calling `wsys_status` again after a purely audit-stage event (§21 FAIL / H-06 rejection) on a batch that already passed the S5 verifier changes `n` from 1 to 0. `wsys_status` is re-derived from the full history on every call (correctly, per its own "Pure" contract and `test_pure_no_mutation`), so this isn't a one-off glitch — the reported `n`/`f` for the *same* underlying S5-verify outcome differ depending on whether the (unrelated, later, audit-stage) §21/H-06 disposition has landed yet.

**Why this is wrong against the spec, not just surprising:**
- W-SYS's own contract (`EG6-SPEC-A-v3.md` §2 "Role"): "It reads only per-attempt class records of the state history, never a sample, audit or content artifact." VERIFIED is the S5 verifier's verdict; the M=4 cap and q* are calibrated against "P(all 396 batches pass)" at the *S5 dispatch/verify* stage (RR-1: "audit results never trigger [an attempt]"). An attempt that reached VERIFIED already succeeded at the stage W-SYS is modelling; whatever the audit later decides is a separate process and should not be able to retroactively change whether that attempt counts in `n`.
- Per EG6-SPEC-A-v2 §4 item 2 / the addendum §2.8, "at most one PASS" is enforced by `retirable`/`new_attempt` precisely so a VERIFIED attempt is locked in forever; `wsys_status` should treat it the same way.

**Fix** (one line, `p3b_s5_state.py:492`): once an attempt key is `"PASS"`, a later FAILED/INCOMPLETE for the same key must not overwrite it:
```python
elif e["to"] in ("FAILED", "INCOMPLETE") and final.get(key) != "PASS":
```
Verified in the sandbox that this one-line change gives `n,f = 1,0` both before and after the H-fail (stable, monotonic).

**Test fallout:** `test_p3b_s5_state_attempts.py:414-420` (`WsysStatus.test_d_s_h_u_not_counted`, the last two lines) currently asserts `(n, f) == (0, 0)` for exactly this scenario and encodes the bug as intended behaviour. It must be changed to `(1, 0)` (or split into its own test) once the fix lands — it is not vacuous against the *old* code (both old and new code define `wsys_status` at all, so the test only distinguishes behaviour, not presence), but it currently locks in the wrong number.

---

## MATERIAL-2: a W-SYS stop is never recorded as a class-D event; spec's own T146 is unimplemented

Addendum §2.8 (`prompts/20260926_2400_p3b-agent-contract-r7-addendum.md:166-207`, the paragraph being frozen): "on a stop, no new attempt is dispatched; attempts in flight are verified normally; **the event is class D**, and resumption needs a human act." `EG6-SPEC-A-v3.md` §2 test table names this explicitly:

> T146 | a synthetic history with f at the threshold − 1 / at the threshold at n = 100 → continue / STOP (**class D recorded**, no new attempt admitted)

Current implementation: `new_attempt` (`p3b_s5_state.py:261-262`) only does
```python
if wsys_status(st)["stop"]:
    raise StateError("W-SYS has stopped S5 (class D, systemic): no new attempt until a human act")
```
— it blocks the call but writes **no history entry**. `wsys_status` itself is intentionally pure/read-only (`test_pure_no_mutation`). There is no code path anywhere in this diff that appends a class-D record when W-SYS trips, and no test exercises "class D recorded" (`test_stop_blocks_new_attempts` only checks that `new_attempt` raises; `test_stop_found_at_an_earlier_look_persists` only checks `stop`/`stop_at_n`). T146 as specified is simply absent.

This may be legitimately out of *this* module's scope — the file's own header says it "records states; it dispatches, verifies, audits and accepts nothing," and `may_start_attempt`/escalation live in the not-yet-built orchestrator (`p3b_s5_r7_orchestrate`, referenced at `p3b_s5_state.py:354-360`). If that's the intended division of labour, it needs to be said explicitly (a comment plus a tracked gap, e.g. against the retire/orchestrator slice), because right now:
1. the spec's own RED-test list (T146) is silently unimplemented rather than deferred with a note, and
2. `wsys_status()`'s return value is the only thing available for an orchestrator to act on — confirm it gives enough (`stop`, `stop_at_n`, `looks`) to let a future orchestrator write the class-D record and that no state-level durable record is otherwise expected.

**Recommendation:** either (a) add a minimal durable marker here (e.g. `new_attempt`'s refusal path, or a new `wsys` no-op transition, appends a `MANIFEST-REVISION`-style `kind="WSYS-STOP"` history entry the first time `stop` flips true for a given `stop_at_n`), with a T146-equivalent test, or (b) if recording is deliberately deferred to the orchestrator slice, say so in the module docstring/EG-6 comment block and add a tracking note so T146 isn't quietly dropped. Either is acceptable; silence is not.

---

## Builder's seven deviations — judged

1. **Mechanical defaults `INCOMPLETE→A1`, post-VERIFIED `FAILED→H` — ACCEPTABLE.** Both are not shortcuts but the *only* classes the classifier table (`EG6-SPEC-A-v2.md` §3) can ever produce in those exact transitions: INCOMPLETE has no verifier tags to classify (`R7-W W1 <run>: planned run was never dispatched … | A1` / "INCOMPLETE (a dispatch interrupted) | A1"), and any FAILED reached from VERIFIED/AUDITED is by definition the §21 FAIL / H-06 path ("a §21 FAIL or an H-06 rejection is class H", decision package §17). Both remain overridable (`fail(class="D"/"S")` still works when `post_verified`; explicit `--failure-class` still works for INCOMPLETE) — defaults don't remove caller judgement, they just fill in the one class that's always mechanically correct.

2. **`new_attempt` requires `cause_class == recorded class` — ACCEPTABLE.** Not contradicted by spec; a sound extra guard against silently relabelling a recorded failure (`test_cause_class_must_match_the_recorded_class` — a recorded D can never be laundered into an X to sneak past the "D/S never re-run" gate).

3. **"At most one PASS" scoped to `<B>-R7` entries only — ACCEPTABLE, in fact correct.** All EG-6 attempts share run id `<B>-R7` (spec E-04 fact 1); R2-era history predates the whole attempt/retry framework and uses a different PASS vocabulary (`"PASS"` vs R7's `"BATCH-PASS"`, EG-7). Scoping to `_r7_entries` (`p3b_s5_state.py:326-327, 365`) is the correct read of "at most one PASS per batch [under this policy]," not a narrowing that lets a second PASS through — confirmed no path to two PASS entries exists (see below).

4. **W-SYS excludes D/S/H/U from n and f — PARTIALLY a defect.** Excluding D/S (self-stopping) and genuine pre-verified H/U from `f` is correct and spec-supported ("Classes D and S already stop S5 on their own… class H and U outcomes are not counted"). But see **MATERIAL-1**: excluding a *post-VERIFIED* H from `n` too, by letting it overwrite an already-locked-in PASS, is the bug. "Unclassified failure → D" and "sticky stop" are both correct and tested.

5. **`may_start` guard mandatory — ACCEPTABLE, matches spec exactly.** EG6-SPEC-A-v2 §1: "`state new-attempt` also requires it… without it the call fails closed." Fully intentional; the CLI is inert until the orchestrator slice ships, which is expected for this slice.

6. **W-SYS stop not recorded as a class-D event — see MATERIAL-2 above** (elevated from "acceptable, plausibly deferred" to a flagged gap because the spec names an explicit test, T146, for exactly this, and it's currently just missing rather than deferred-with-a-note).

7. **`failure_signature` optional on INCOMPLETE (only required for `FAILED` + rerunnable class, `p3b_s5_state.py:241-242`) — ACCEPTABLE with a recommendation, not a blocker.** Confirmed the gap is real and broader than the mechanical-default case: *any* INCOMPLETE, even one explicitly given class X, is exempt from the signature requirement (the check is `if to == "FAILED" and …`), so RR-7 can never fire on repeated timeouts. This is a genuine hole in RR-7's per-batch early-stop coverage for the "always times out" failure mode. It is bounded, though: M=4 caps the damage to one batch regardless, and every counted INCOMPLETE(A1/X) still feeds W-SYS's `f` (`WSYS_COUNTED = ("X","A1","A2")`), so a *systemic* timeout defect is still caught at the programme level even if RR-7 never trips for one batch. Recommend (not required for this slice): require `--failure-signature` whenever `to in ("FAILED","INCOMPLETE") and failure_class in RERUNNABLE_CLASSES`, using e.g. a hash of the interruption diagnostic, to close the per-batch gap too.

---

## Attack results (all clean except as noted above)

- **Two PASS entries:** no path found. `TRANSITIONS` only allows `VERIFIED` from `PROPOSED`←`DISPATCHED`←`PREPARED`; the only way back to `PREPARED` for an R7 batch is `new_attempt`, which refuses unconditionally once any `_r7_entries` PASS exists (`p3b_s5_state.py:365-366`); `new_run` refuses R7 outright (`:277-278`); `revision_transition` refuses batches already at revision 7 (`:566`). Airtight.
- **Attempt numbering gaps/reuse:** `current_attempt` reads `runs[-1]`; `new_attempt` always appends `attempt=m+1`, `retirable` requires the live state to be FAILED/INCOMPLETE before the next call, so no concurrent/duplicate `new_attempt` can happen on one in-memory `st`. No gap/reuse path found.
- **Hash chain integrity with new fields:** `_append` (`p3b_s5_state.py:180-192`) adds `attempt`/`extra` (cause_class, retirement, failure_class, failure_signature) *before* computing `entry_sha256`, so the chain covers them. `_entry_hash` excludes only `entry_sha256` itself. Confirmed via `verify_chain` round-trip in `NewAttempt.test_happy_path`.
- **Backward compatibility (revision < 7):** `_failure_fields` raises if class/signature are passed outside R7 FAILED/INCOMPLETE (`p3b_s5_state.py:226-229`); `check_evidence` defaults `attempt=1, used_shas=()` so the new A<m>-naming and reuse checks are no-ops for pre-R7 callers. `test_class_is_refused_outside_r7_and_outside_failures` and the full pre-existing `test_p3b_s5_ops_state.py` (24 tests) pass unchanged.
- **W-SYS arithmetic exactness:** `Fraction("0.12771")` gives an exact rational; `_binom_tail_scaled`/`wsys_triggers`/`wsys_threshold` use only `math.comb` and integer cross-multiplication (`p3b_s5_state.py:280-306`), no floats. `test_threshold_table_exact` checks the boundary is exact (`triggers(n,f)` true, `triggers(n,f-1)` false) at all six spec'd (n,f) pairs — non-vacuous, would catch an off-by-one.
- **Canary exclusion:** `if bid in CANARY_BATCHES: continue` (`:484`) — correct, tested (`test_canary_attempts_excluded`).
- **`check_retirement` validation:** exact-key check (`set(ref) != {"path","sha256"}`), sha256 regex, file existence, `RETIREMENT-COMPLETE.json` basename + `<B>-R7.A<m>` parent-dir shape, byte-for-byte sha match, JSON parse, `state=="COMPLETE"`/`batch`/`attempt` match, and `intent_sha256` binding to the sibling `RETIREMENT-INTENT.json` (`p3b_s5_state.py:375-403`) — thorough; `test_retirement_record_is_validated` exercises 13 negative cases plus the wrong-basename/dirname case, all pass.
- **q\* precision (0.1277 in `EG6-SPEC-A-v3.md` vs 0.12771 in the decision package and code):** not a code defect — the addendum text being frozen (`prompts/…addendum.md:200`, §2.8) also says "q\* = 0.12771", so the code matches the two more authoritative/current sources; v3's boxed "0.1277" was an earlier-draft rounding. Both values happen to produce the identical threshold table at the tested n's, so this is a documentation-consistency note for governance, not an engineering finding.
- **Tests non-vacuous vs. old code:** the whole file is new functionality (`new_attempt`, `wsys_status`, attempt-aware `check_evidence`, `_failure_fields`) that doesn't exist pre-diff, so every test in it is trivially non-vacuous against the pre-diff code. Spot-checked several for non-vacuity against a *plausible near-miss* implementation too (see MATERIAL-1: `test_d_s_h_u_not_counted` is the one case where the test is non-vacuous but asserts the wrong value).

## Minor / non-blocking notes
- `batch_evidence_shas` (`p3b_s5_state.py:330-333`) scopes evidence-reuse checking to the *whole* batch history (any run_id, including R2 and COMPARISON runs), asymmetric with `_r7_entries`'s R7-only scoping used everywhere else. This is over-broad rather than under-broad (stricter reuse refusal, not a hole), so not a defect — just worth a one-line comment noting the deliberate asymmetry so a future reader doesn't "fix" it into an inconsistency.

## Fix list (in priority order)
1. `p3b_s5_state.py:492` — guard the FAILED/INCOMPLETE branch with `final.get(key) != "PASS"` so a post-VERIFIED failure never uncounts an already-locked-in pass. Update `test_p3b_s5_state_attempts.py:414-420` to expect `(n,f) == (1,0)` after the H-fail (or split it into its own assertion).
2. Either implement a durable class-D record on a W-SYS stop plus a T146-equivalent test, or add an explicit scope note (module docstring / EG-6 comment block) deferring it to the orchestrator slice with a tracked gap — currently it's silently absent.
3. Optional/recommended: require `--failure-signature` for INCOMPLETE too when an explicit rerunnable class is given, to close RR-7's blind spot on repeated timeouts.
