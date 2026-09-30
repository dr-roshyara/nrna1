# Commission — FRESH independent verification of the L0-DEC-21 slice and the L0-DEC-24 fix

| | |
|---|---|
| **Kind** | ⭐ **commission (prompt).** ⛔ Not a verification, not a verdict, not an L0 decision |
| **Why a third verification** | **L0-DEC-25:** VERIFICATION-02 (`audits/2026-09-23-L0-DEC-21-SLICE-VERIFICATION-02.md`) is independent of the implementation, but its verifier wrote the 1a.6 review, so it is **not the "fresh" verification L0-DEC-21 requires** |
| **Written by** | the governance session that **implemented** the slice and the fix. ⚠️ The author is not independent; the verifier reproduces everything |
| **Run it how** | ⭐ **in a new session with no prior context: *"Execute the commission in `docs/knowledgeos/knowledgeos_theory_chronological_extraction/governance/audits/2026-09-23-GIA-1a6-VERIFICATION-03-COMMISSION.md`."*** Read this file directly. **Do not paste it**: the previous commission reached its verifier with a truncated path and garbled pin values |
| **Expected output** | `docs/knowledgeos/knowledgeos_theory_chronological_extraction/governance/audits/2026-09-23-L0-DEC-21-SLICE-VERIFICATION-03.md`, **uncommitted** |

---

## PROMPT

**Role.** You are a **fresh, independent governance/control-plane verifier**. You did not implement anything under review, did not write the 1a.6 review, and did not write VERIFICATION-02. `W` = `docs/knowledgeos/knowledgeos_theory_chronological_extraction/`. You supply **evidence and a recommendation**, and the L0 human decides.

**Freshness declaration (required first line of your output).** State whether this session has **any** prior context from the implementation, the 1a.6 review (`f21b3e6e3`) or VERIFICATION-02. You must **reproduce** every claim you rely on, and never adopt a claim from them.

⛔ **Reading order (L0-DEC-25: "no context from … VERIFICATION-02").** Until you have **finished your own measurements and probes** (Method steps 2–5), read only three things: the L0 decisions, the **finding texts** in the 1a.6 review §7, and this commission. **Do not open** `audits/2026-09-23-L0-DEC-21-SLICE-VERIFICATION-02.md`, or the 1a.6 review's reasoning, probe tables or verdicts. Once your measurements are written down you may consult them, and you must report any point where your result differs from theirs.

**What you verify.** That these six items are implemented exactly as authorized and close their findings, and that nothing else changed:

| Item | Authorized by | Fix |
|---|---|---|
| IR-G1 | L0-DEC-21 | activating a tier-B (any non-`A`) gate ⇒ `GOVERNANCE_INOPERATIVE`; `--pin-lines` refuses non-A tiers |
| IR-G2 | L0-DEC-21 | `--stage`/`--timing` outside the `gate-schema.yaml` enums ⇒ INOPERATIVE; the door's no-control message is accurate |
| IR-G4 | L0-DEC-21 | an activated AUT gate with an unimplemented check ⇒ INOPERATIVE, not BLOCK |
| IR-A1 | L0-DEC-21 | `admit.py --audit`: a non-JSON receipt line or a non-string `file_id` ⇒ unrecognised-row failure, no traceback |
| **IR-A1 / UTF-8** | **L0-DEC-24** | an **invalid UTF-8** receipt line ⇒ the same unrecognised-row failure. ⛔ Nothing else in `admit.py` changes |
| IR-A2 | L0-DEC-21 | the admit regression derives its expected receipt count |

**Commits in scope:**
- `6e52ba73a`: L0-DEC-20/21 recorded;
- `348d322f4`: tests, RED;
- `48e3e0f9e`: slice fixes, GREEN;
- `e4c412e8f`: doc wording;
- `d0ef5b506`: L0-DEC-24/25/26 recorded, VERIFICATION-02 committed;
- `29b2dbe9c`: V12, RED;
- `cf8d2f4d0`: the L0-DEC-24 fix;
- `1c5c5bea3`: **added after this commission was written.** A text-only change to `gates.yaml` **KOS-G-044** (a REVIEW gate: not activated, not activatable), so that its origin-label wording matches Q51/Q52 (`[T]`). Verify only two things: the diff touches no other gate, and the five activated pins are unchanged. The implementer measured `485a327da2ca8f9e` · `7084bc523256ccb3` · `9327509ad713a267` · `45e060f20216d64d` · `9c5d3802a2ff6942`; recompute them.

Other commits in the range (`18133be5b`, `fb76e9099`, `70a948c96`, `628d02169`, `2f09c9f41`) are research or protocol text, or records. **Check them only for side effects on instrument files.**

### Hard constraints
- ⛔ **Modify nothing and commit nothing,** except your output file (uncommitted).
- ⛔ Do not touch the runner, `gates.yaml`, `gate-schema.yaml`, `governance-state.yaml`, the door, fixtures, regression suites, `admit.py`, or any research evidence, receipt, registry, manifest or protocol file.
- ⛔ Do not write pins. Do not fix anything; record it. Do not read corpus files or judge the theory.
- ⛔ Do not decide or recommend 1a.7, pinning, the live release run, Batch 4, the nine RC-H-04 IDs (F0031–F0038, F0040), or the Critical Attack Pass.
- ⛔ Do not re-review Extended 1a or the L0-DEC-18 repair as a whole.
- Experiments only on `git archive` copies. On the live workspace you may run only `gate-runner.py` and `admit.py --audit`, which are read-only.
- Facts, conclusions and recommendations stay separate. If something could not be verified, write `NOT VERIFIED`.

### Method
1. **Decisions:** read L0-DEC-20, 21, 24 and 25 in `W/governance/L0-DECISION-RECORD-01.md`, and **only the finding texts** in `W/governance/audits/2026-09-23-GIA-1a6-INDEPENDENT-REVIEW.md` §7 (see *Reading order* above).
2. **Diff scope:** read `git diff 6e52ba73a cf8d2f4d0` in full, and `git show 1c5c5bea3`. Every hunk must trace to one of the six items, its tests, doc wording or decision recording. Confirm:
   - `gates.yaml` is unchanged **within `6e52ba73a..cf8d2f4d0`**, and its only later change is `1c5c5bea3` (KOS-G-044 text). Recompute the five pins, at `cf8d2f4d0` and at HEAD, yourself (sha256 of canonical JSON of each parsed gate mapping, first 16 hex) for KOS-G-001, 002, 003, 010 and 022, and compare with `python3 W/governance/gate-runner.py --pin-lines`.
   - In `admit.py`, only receipt-row classification, its call site and its docstring changed.
   - No research evidence, receipt, registry or manifest file changed in any in-scope commit.
3. **Tests first:** cases R1–R6, V10, V11 and the derived count exist in `348d322f4` before `48e3e0f9e`; V12 exists in `29b2dbe9c` before `cf8d2f4d0`. Diff the two suites across the range. Was any expectation weakened after its RED commit?
4. **Reproduce the results.** Record what **you** measure. Expected values are given so you can compare, not so you can adopt them:

   | Command (from `W`) | Implementer reports |
   |---|---|
   | `python3 governance/gate-runner.py --self-test` | 55/55 |
   | `python3 governance/regression/run_regression.py` | 66/66 |
   | `… run_regression.py --control rev --rev 6e52ba73a` | 61/66 (R1–R5 RED) |
   | `… run_regression.py --control rev --rev 9abd4ad95` | 27/66 (pre-1a) |
   | `python3 governance/regression/admit_regression.py` | 12/12, derived count 27 |
   | `… admit_regression.py --control rev --rev 29b2dbe9c` | 11/12 (V12 RED) |
   | `… admit_regression.py --control rev --rev 348d322f4` | V10, V11, V12 RED |
   | `… admit_regression.py --control rev --rev e6087b615` | pre-repair; most cases RED |
   | live `python3 governance/gate-runner.py` | exit 3 (unpinned activations) |
   | live `python3 evidence/admit.py --audit` | exit 3, `BINDING_FAILURES_PRESENT`; only failure = the nine IDs |

5. **Adversarial probes (on copies):**
   - **IR-G1:** reach CLEAR with any activated non-A tier.
   - **IR-G2:** invalid, empty, differently-cased or padded stage/timing values, through the runner and through the door. Does any wording claim "nothing activated" while gates are activated?
   - **IR-G4:** a re-pinned unknown check, in its variants.
   - **IR-A1 and UTF-8:** invalid UTF-8 (several byte patterns, at start, middle and end of the file), a BOM, CRLF line endings, truncated JSON, non-dict rows, and every non-string `file_id` type. Does anything crash, get skipped silently, or get counted as a receipt?
   - **IR-A2:** can the derived count agree vacuously?
   - **The slice as a whole:**
     - Does any original case now pass for a different reason than before?
     - Are the five pinned tier-A gates still operable?
     - Did the byte-level read change the handling of the live 27 receipts or the VOID row?

### Acceptance standard
This is a fit-for-purpose control mechanism for one research program. **Reject an item only if** the fix:
- fails to close its finding;
- creates false assurance;
- violates L0-DEC-12…25 or IC-1…IC-8;
- introduces an unexplained correctness defect.

Record everything else as findings, marked within or beyond purpose. Do not recommend new machinery. Name the gap and the smallest change that would close it.

The residuals recorded by L0-DEC-21 and L0-DEC-24 are **not** rejection grounds unless the changes made one worse. They are: IR-G3, IR-G5, IR-G6, IR-A3–A6, R-1, R-2, IR-B1–B4, V-A1b, V-A1c, V-A2a, V-A2b, V-G1a, V-G2a, V-G2b, V-G4a.

### Output
1. The **freshness declaration**, then a header: verifier, commits evaluated, method, and what you modified (only this file).
2. The diff-scope table, with any untraced hunks flagged.
3. The tests-first finding.
4. Reproduced measurements, set against the reported ones.
5. Probe results, per item.
6. New findings, each marked within or beyond purpose and tied to the IC or L0 item it concerns.
7. Verdicts:
   ```text
   IR-G1 · IR-G2 · IR-G4 · IR-A1 (incl. L0-DEC-24 UTF-8) · IR-A2:  CLOSED / CLOSED_WITH_FINDINGS / NOT_CLOSED
   Slice + fix as a whole:  ACCEPT / ACCEPT_WITH_FINDINGS / REJECT
   ```
   Give the reason for each.
8. State that 1a.7, pins, the release run, Batch 4, the nine IDs and the Critical Attack Pass are not part of your recommendation.

**Then STOP.** Report only:
- the output path;
- the freshness declaration;
- the verdicts;
- any NOT_CLOSED item or rejection criterion met;
- whether the verification is ready for L0.
