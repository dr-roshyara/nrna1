# EG-7 implementation review (Subagent B)

| | |
|---|---|
| Kind | Independent implementation review of the one-file, one-logical-change diff to `scripts/p3b_s5_state.py` plus the new `scripts/tests/test_p3b_s5_state_r7_evidence.py`. Read-only. |
| Method | Read `git diff` directly, read the new test file line by line, ran the exact specified test commands myself, then independently traced the code (without editing anything) to predict which tests would fail if the fix were reverted, and compared that prediction against the orchestrator's reported RED signature. |

## 0. The change, confirmed minimal and isolated

`git diff --stat` confirms exactly one file touched, 11 insertions / 2 deletions, matching the diff shown — no hidden changes elsewhere. I grepped the whole file for every other `"PASS"` / `"BATCH-PASS"` literal: the one site that used to hard-code `"PASS"` is the only one that existed and the only one changed; there is no second stray check that should also have been made revision-aware but wasn't.

```python
R7_BATCH_RUN_RE = re.compile(r"OB\d{4}-R7")

def passing_result(to, run):
    return "BATCH-PASS" if to == "VERIFIED" and R7_BATCH_RUN_RE.fullmatch(run or "") else "PASS"
```

used in `check_evidence` as `want = passing_result(to, run); if body.get("result") != want: raise StateError(...)`.

## 1. Can anything else now pass VERIFIED that should not?

I traced every case the task named directly against the regex and against `check_evidence`'s actual call sites (there are exactly two in production code — `transition()` at `run = b["run_id"]` and `comparison()` at `run = cr["run_id"]`, confirmed by grepping the whole `scripts/` tree for `check_evidence`/`passing_result`: no other caller exists anywhere).

- **A comparison run** (`<B>-R2S`, or a hypothetical R7-era comparison run): `R7_BATCH_RUN_RE.fullmatch("OB0006-R2S")` doesn't match (`-R2S` ≠ `-R7`) → falls to `"PASS"`, unchanged historical behavior. Confirmed by the `test_comparison_run_unchanged` test, which I reran and which passes.
- **An R7 unit or synthesis run id** (`<B>-R7-L01`, `<B>-R7-L01S`): these never actually reach `passing_result` as the `run` argument in production, because `check_evidence`'s `run` parameter always comes from the *state's own tracked run id* for the batch (`b["run_id"]`), which for an activated R7 batch is always the assembly id `<B>-R7` — never a per-label run — confirmed directly in `r7_state()`'s own setup assertion (`self.assertEqual(self.st["batches"]["OB0004"]["run_id"], "OB0004-R7")`). Even if a unit/synthesis id were somehow passed, `fullmatch` on `r"OB\d{4}-R7"` correctly rejects it (extra trailing characters after `-R7` fail the anchored fullmatch).
- **A regex edge like `"OB0012-R7"`:** matches exactly as intended — 4 digits, then literally `-R7`, nothing else. I don't see a way to construct a string that fullmatches this pattern without being exactly `OB####-R7`.
- **EG-6 option (b) future attempt naming:** I checked this specifically since it's the most forward-looking risk. EG-6 option (b) (which I reviewed independently across two prior rounds) keeps the **same** run id (`<B>-R7`) across every attempt of a batch — only the *retired* ledger directory gets an attempt suffix (`<B>-R7.A<m>`), never the batch's own active `run_id`. So `passing_result`'s regex needs **no change at all** when EG-6 ships: the run id it checks against never varies by attempt under the option that's actually recommended. EG-6's own proposed additions (attempt-aware history, the "at most one PASS ever" guard, evidence-sha-reuse rejection) are separate, additional checks layered on top of `check_evidence`, not modifications to `passing_result`'s own logic — this is a clean, well-isolated interaction, confirmed by inspection of both diffs.

**Is R2/R2.n/R2S behaviour byte-for-byte unchanged?** Confirmed both by code tracing and by test execution: `passing_result` returns `"PASS"` for every `(to, run)` pair except exactly `(VERIFIED, <fullmatch of OB\d{4}-R7>)`; for `to == "AUDITED"` it *always* returns `"PASS"` regardless of run, matching the docstring's claim that AUDITED is untouched for every revision. `test_r2_pass_accepted_batch_pass_refused` and the full pre-existing `test_p3b_s5_ops_state` suite (24 tests, all passing) confirm no regression.

## 2. Are the tests non-vacuous? (independently reasoned, not just re-run)

I reran the exact specified command:

```
cd scripts/tests && PYTHONPATH=.:.. python3 -B -m unittest test_p3b_s5_ops_state test_p3b_s5_state_r7_evidence test_p3b_s5_audit_record
```

Result: **39 tests, all pass, 0 failures, 0 errors** (a handful of unrelated `ResourceWarning`s from pre-existing, unclosed-file patterns in `test_p3b_s5_audit_record.py`, not from anything this diff touches).

Per the task's request, I then reasoned through what would happen with the fix reverted **without actually editing the file** — tracing each new test method's calls against the literal old code (`if body.get("result") != "PASS": raise`):

- `test_r7_batch_pass_accepted`: calls `stm.check_evidence(..., result="BATCH-PASS" default)` **unwrapped** in `assertIsNotNone`. Old code: `"BATCH-PASS" != "PASS"` → raises `StateError`, uncaught → **ERROR**.
- `test_r7_refuses_every_other_result`: loops `("PASS", "BATCH-FAIL", "BATCH-UNDETERMINED", "FAIL", None)` inside `assertRaises`, expecting every one — including literal `"PASS"` itself — to raise. Old code accepts `"PASS"` (no raise), so `assertRaises` fails to observe an exception for that iteration → **FAILURE**.
- `test_end_to_end_real_header_proposed_to_verified`: calls `stm.transition(...)` **unwrapped**, with a `"BATCH-PASS"` report. Old code raises, uncaught → **ERROR**.
- The remaining four new tests (`test_r7_other_checks_still_apply`, `test_audited_unchanged_for_r7`, and both `NonR7Unchanged` tests) do **not** discriminate old vs. new code — I traced this explicitly rather than assuming it. `test_r7_other_checks_still_apply`'s bad-report cases all default to `result="BATCH-PASS"`; under old code every one of them would still raise `StateError`, just from the *result* check firing first instead of the batch/run/header/hash check the test nominally targets — since the test only asserts "raises `S5Error`" (not which message), it passes either way and provides no discriminating signal for this specific bug. The `AUDITED`/R2/R2S tests are regression guards by design (both old and new code behave identically for those paths), correctly non-discriminating.

**Predicted outcome on reverted code: 1 failure + 2 errors** — which is exactly what the task reports A found (*"A reported RED 1 fail + 2 errors"*). I reached this independently, by tracing the code paths rather than re-deriving it from A's own report, and it matches exactly. This is strong, corroborated confirmation that the test suite is real, non-vacuous, and discriminates precisely the three cases that actually exercise the bug — no more, no fewer.

## 3. Does the end-to-end test use the real header function?

Yes: `real_header_report` builds its report with `c.header(VERIFY_SCRIPT, [], {"batch": batch, "run": run}, body_text, {"artifact": name, "result": result})` — the same production `p3b_s5_common.header` function used by the real verifier and by `p3b_s5_audit_record.py` (`hdr = c.header(__file__, [], {"batch_id": bid, "run_id": run}, txt, {...})`, confirmed by direct comparison of the two call sites) — not a hand-rolled fake header dict. This is a genuine end-to-end check of the shape a real verify report would actually have, not a shortcut.

## 4. Caller audit

Grepped the entire `scripts/` tree (production and tests) for `check_evidence` and `passing_result`: exactly two production call sites (`transition`, `comparison`, both in `p3b_s5_state.py` itself), both passing a state-tracked `run` value the evidence file's own content cannot influence (the file's own advertised `batch_id`/`run_id` are checked *separately*, after the result check, and mismatches there are unaffected by this change). No other script in the repository reads `result` from a verify/audit report or depends on the old literal `"PASS"` behavior — confirmed this holds for `p3b_s5_audit_record.py` (its own `PASS`/`FAIL` vocabulary is independent and untouched) and `p3b_s5_accept.py` (doesn't inspect `result` at all, only the `evidenced` set built from `to`/`evidence.sha256` presence) from my prior EG-6 review round's direct reading of both files.

## Verdict

**IMPL-ACCEPTABLE.**

The change is minimal (one function, one two-line substitution, no other file touched), correctly and narrowly scoped (matches exactly `(VERIFIED, <B>-R7)`, nothing broader), forward-compatible with EG-6's recommended design (no rework needed when attempts ship, since the run id never varies by attempt under option (b)), and its test suite is genuinely non-vacuous — I independently predicted the exact "1 failure + 2 errors" signature the reverted code would produce, purely by tracing the diff, before comparing against what was reported, and the two matched exactly. I found no case where the new logic would accept evidence it should not, and confirmed byte-for-byte-unchanged behavior for every non-R7-VERIFIED path both by code tracing and by rerunning the full existing `test_p3b_s5_ops_state` suite (24/24 passing) alongside the new and audit-record suites (39/39 total, 0 failures).

One observation, out of scope for this diff and not a defect in it: `add_comparison()` names every §K comparison run `<B>-R2S` unconditionally, regardless of the batch's revision — so a comparison rerun of an R7-revision batch would not receive an R7-shaped run id at all. This is a pre-existing property of `add_comparison`, untouched by EG-7's change, and not something this review's scope covers; noting it only so it isn't mistaken for something EG-7 should have addressed.
