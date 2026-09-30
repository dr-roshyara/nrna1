# Commission — Independent verification of the L0-DEC-21 correction slice

| | |
|---|---|
| **Kind** | ⭐ **commission (prompt) for an independent verifier.** ⛔ Not a verification, not a verdict, not an L0 decision |
| **Written by** | the governance/control-plane session, which **implemented the slice**. ⚠️ So the commission's author is not independent. The verifier must reproduce the evidence rather than trust this text |
| **Recorded on** | L0 instruction, 2026-09-23 (*"commit the verification prompt as a governance record"*) |
| **To be run by** | a **fresh session** with no context from the implementing session or the 1a.6 review session |
| **Expected output** | `governance/audits/2026-09-23-GIA-1a6-VERIFICATION-02.md`, **uncommitted** |
| **Governing decisions** | L0-DEC-20, L0-DEC-21 (`governance/L0-DECISION-RECORD-01.md`) · finding source: `audits/2026-09-23-GIA-1a6-INDEPENDENT-REVIEW.md` §7 |

⚠️ **One number deliberately left to the verifier:** the implementer measured only the new cases (2/7 RED) against `6e52ba73a`, not the full battery. The verifier establishes the full count.

---

## PROMPT — Independent verification of the L0-DEC-21 correction slice

**Role.** You are the **independent governance/control-plane verifier** for the correction slice authorized by **L0-DEC-21**. The workspace `W` is `docs/knowledgeos/knowledgeos_theory_chronological_extraction/`. You did not implement the slice and you are not its accepting authority. You supply **evidence and a recommendation**, and the L0 human decides.

**What you verify.** Did the slice implement exactly the five items L0-DEC-21 authorized, close each finding it targets, and introduce nothing else?

| Item | Finding (in `W/governance/audits/2026-09-23-GIA-1a6-INDEPENDENT-REVIEW.md` §7) | Authorized fix (L0-DEC-21) |
|---|---|---|
| **IR-G1** | an activated tier-B gate that fails yields `CLEAR` | activating a tier-B gate ⇒ `GOVERNANCE_INOPERATIVE` |
| **IR-G2** | a stage/timing filter can select nothing and the door says "NO BLOCKING CONTROLS HAVE BEEN ACTIVATED" | stage/timing values outside the schema enums are refused; the door's no-control message is accurate |
| **IR-G4** | an activated gate with an unimplemented check reports BLOCK | ⇒ `GOVERNANCE_INOPERATIVE` |
| **IR-A1** | `admit.py --audit` crashes on a non-JSON line or a non-string `file_id` | ⇒ an unrecognised-row binding failure, no traceback. **An explicit new authorization, not an expansion of L0-DEC-18** |
| **IR-A2** | the admit regression hard-codes 17 receipts (6/9 at HEAD) | the expected count is derived, not hard-coded |

**Commits:**
- `6e52ba73a`: the decisions (L0-DEC-20/21);
- `348d322f4`: the tests, committed before the fixes;
- `48e3e0f9e`: the fixes;
- `e4c412e8f`: doc wording.

Later commits (`18133be5b`, `fb76e9099`, `70a948c96`) are **outside scope**: research records and protocol text. You need them only for the side-effect check below.

### Hard constraints
- ⛔ **Modify nothing and commit nothing.** The only exception is your own file, `W/governance/audits/2026-09-23-GIA-1a6-VERIFICATION-02.md`, which you create but **do not commit**.
- ⛔ Do not touch the runner, `gates.yaml`, `gate-schema.yaml`, `governance-state.yaml`, the door, fixtures, the regression suites, `admit.py`, or any research evidence, receipt, registry, manifest or protocol file.
- ⛔ Do not write pins. Do not fix anything you find; record it. Do not read corpus files, advance research, or judge the theory.
- ⛔ Do not decide or recommend 1a.7, pinning, Batch 4 release, or the treatment of the nine RC-H-04 IDs (F0031–F0038, F0040).
- ⛔ **Do not re-review Extended 1a or the L0-DEC-18 repair as a whole.** The earlier review covers them. Re-examine them only where the slice changed them.
- Run experiments only on `git archive` copies in your scratchpad. Two things may run on the live workspace because they are read-only: `gate-runner.py` and `admit.py --audit`.
- Keep facts, conclusions and recommendations separate. Don't trust reported numbers; reproduce them. If you cannot verify something, write `NOT VERIFIED`.

### Method
1. **Decisions first.** Read L0-DEC-20 and L0-DEC-21 (`W/governance/L0-DECISION-RECORD-01.md`), then review §7 (IR-G1, IR-G2, IR-G4, IR-A1, IR-A2).
2. **Scope of the diff.** Run `git diff 6e52ba73a e4c412e8f --stat` and read the full diff. Every hunk must trace to one of the five items, to its tests, or to doc wording. Confirm three things:
   - `gates.yaml` is **unchanged**, so the five pins stay valid (`485a327da2ca8f9e`, `7084bc523256ccb3`, `9327509ad713a267`, `45e060f20216d64d`, `9c5d3802a2ff6942`). Recompute them yourself.
   - In `admit.py`, only the receipt-row classification and its call site changed. `--resolve`, `--receipt` and the registry section must be unchanged.
   - No research evidence, receipt, registry or manifest file appears in `6e52ba73a..e4c412e8f`.
3. **Tests first.** In `348d322f4`, the gate cases R1–R6 and the admit cases V10, V11 plus the derived count must exist **before** `48e3e0f9e`. Diff both suites between `348d322f4` and `e4c412e8f`. Was any expectation weakened after the RED commit?
4. **Reproduce the results.** From clean archives, run:
   - `python3 governance/gate-runner.py --self-test` (reported 55/55);
   - `python3 governance/regression/run_regression.py` (reported 66/66);
   - `run_regression.py --control rev --rev 6e52ba73a`: R1–R5 must be RED, and A0 and R6 must match (the implementer measured the new cases at 2/7);
   - `run_regression.py --control rev --rev 9abd4ad95`, the pre-1a runner (reported 26/60 on the original cases);
   - `python3 governance/regression/admit_regression.py` (reported 11/11 at HEAD, derived count 27);
   - `admit_regression.py --control rev --rev 348d322f4`: V10 and V11 must be RED (reported 9/11);
   - `admit_regression.py --control rev --rev e6087b615`, the pre-repair baseline (reported 1/11).
5. **Live runs.** `gate-runner.py` should exit 3, because the activations are unpinned. `admit.py --audit` should exit 3 with `BINDING_FAILURES_PRESENT`, and its only failure should be the nine IDs, named.

### Adversarial probes (on copies; report, don't fix)
- **IR-G1:**
  - Pin and activate a tier-B gate (for example KOS-G-025), and try to reach CLEAR with a failing tier-B gate.
  - Try a gate whose `tier` is missing, lowercase `a`, or another value.
  - Does `--pin-lines` refuse every non-A tier?
- **IR-G2:**
  - Stage and timing that are invalid, empty-string, differently cased, or given only one of the two.
  - A valid stage that selects no activated gate. Is the door's wording accurate, and is any wording left that claims "nothing activated" when gates are activated?
  - Check the runner and the door both directly and through the door's positional arguments.
- **IR-G4:**
  - A re-pinned gate with an unknown check.
  - A check named with different case or surrounding whitespace.
  - A non-AUT gate (these are refused by IC-8 anyway). Confirm the reason given is accurate.
- **IR-A1:**
  - Lines of truncated JSON, a bare string, a number, `null`, a non-string `file_id` of each type (number, list, object, null), and invalid UTF-8.
  - Do any of them crash, get skipped silently, or get counted as receipts?
  - Do the reported line numbers point at the right rows?
- **IR-A2:** Is the derived count computed independently of `admit.py`, and before the mutation? Can it be made to agree vacuously?
- **Regression from the slice:**
  - Does any of the original 60 gate cases or 9 admit cases now pass for a different reason than before?
  - Did the new refusals make any legitimate configuration inoperable, such as the five activated tier-A gates with pins?

### Acceptance standard
This is a **fit-for-purpose control mechanism for one research program**. Reject an item only if the fix:
- fails to close its finding;
- creates false assurance (a PASS or CLEAR the evidence does not support);
- violates an approved constraint (L0-DEC-12…21, IC-1…IC-8);
- introduces an unexplained correctness defect.

Do not reject for incompleteness, lack of generality, or lack of production strength. Record those as findings, marked *within purpose* or *beyond purpose*. Do not recommend new governance machinery. Name a gap and the smallest change that would close it; the decision belongs to L0. The residuals the slice deliberately left open (IR-G3, IR-G5, IR-G6, IR-A3…A6, R-1, R-2, IR-B1…B4) are **not** rejection grounds unless the slice made one of them worse.

### Output (the single uncommitted file)
1. Header: verifier, commits evaluated, method, and what you modified (this file only).
2. A diff-scope table: every changed file and hunk, mapped to an item, or flagged as untraced.
3. The tests-first finding.
4. Reproduced measurements, set against the reported ones.
5. Probe results, per item.
6. New findings, each marked within or beyond purpose and tied to the IC or L0 item it concerns.
7. **A verdict for each item:**
   ```text
   IR-G1:  CLOSED / CLOSED_WITH_FINDINGS / NOT_CLOSED
   IR-G2:  CLOSED / CLOSED_WITH_FINDINGS / NOT_CLOSED
   IR-G4:  CLOSED / CLOSED_WITH_FINDINGS / NOT_CLOSED
   IR-A1:  CLOSED / CLOSED_WITH_FINDINGS / NOT_CLOSED
   IR-A2:  CLOSED / CLOSED_WITH_FINDINGS / NOT_CLOSED
   Slice as a whole:  ACCEPT / ACCEPT_WITH_FINDINGS / REJECT
   ```
   Give the reason for each.
8. A statement that 1a.7, pins, the live release run, Batch 4, the nine IDs and the Critical Attack Pass are **not** part of your recommendation.

**Then STOP.** Report only:
- the file path;
- the five item verdicts and the slice verdict;
- any NOT_CLOSED item or rejection criterion met;
- whether the verification is ready for L0.
