# L0-DEC-21 correction slice — independent verification (02)

## 1 · Header

| | |
|---|---|
| **Kind** | verification of the L0-DEC-21 correction slice. ⛔ **Not an L0 decision and not an acceptance.** It supplies evidence and a recommendation; the L0 human decides |
| **Verifier** | a governance/control-plane review session. It did **not** implement the slice |
| ⚠️ **Independence limitation** | **This is the same session that wrote the 1a.6 review** (`f21b3e6e3`), whose findings the slice answers. L0-DEC-21 asks for *"a fresh independent verification"*. This verifier is independent of the implementation, but it is **not fresh** with respect to the findings, and it is the same model family as every other session. **Whether this satisfies L0-DEC-21 is for L0 to decide** (§8, Q-1). The verifier had said beforehand that a fresh session should do this. It proceeded because the human commissioned it |
| **Commits evaluated** | `6e52ba73a` (L0-DEC-20/21 recorded) · `348d322f4` (tests, RED) · `48e3e0f9e` (fixes, GREEN) · `e4c412e8f` (doc wording). HEAD at verification: `70a948c96`. The later commits `18133be5b`, `fb76e9099` and `70a948c96` were checked **only** for side effects on instrument files (none; §2) |
| **Method** | read the decisions → read the full diff `6e52ba73a..e4c412e8f` → tests-first check → reproduce every reported number from `git archive` copies → adversarial probes on `git archive` copies of `e4c412e8f` (control plane from the archive, `--control rev`) → two read-only live runs (`gate-runner.py`, `admit.py --audit`) |
| **Modified** | **this file only**, uncommitted. No runner, `gates.yaml`, schema, state, door, fixture, regression suite, `admit.py`, evidence, receipt, registry, manifest or protocol file was touched. No pin was written. No corpus file was read |
| **Output path** | the commission's path was truncated (`W/governance/…IFICATION-02.md`). This file is placed beside the 1a.6 review in `W/governance/audits/` |

---

## 2 · Diff scope (`git diff 6e52ba73a e4c412e8f`)

`6e52ba73a` itself touches only `L0-DECISION-RECORD-01.md` (+17) and the session log (+6). Both are decision recording.

| File | Hunk | Item | Traced? |
|---|---|---|---|
| `governance/gate-runner.py` `operability()` +9 | `tier != "A"` on an activated gate ⇒ reason | IR-G1 | ✅ |
| same | `class == "AUT" and check not in CHECKS` ⇒ reason | IR-G4 | ✅ |
| `gate-runner.py` `main()` `--pin-lines` | refuses to print a pin for `tier != "A"`; the message names the tier | IR-G1 | ✅ |
| `gate-runner.py` `main()` +12 | `--stage`/`--timing` checked against the `gate-schema.yaml` enums before evaluation; outside ⇒ INOPERATIVE | IR-G2 | ✅ |
| `.claude/hooks/governance-preflight.sh` +4/−1 | no-control message: *"NO ACTIVATED CONTROL WAS EXERCISED (none is activated, or none applies to the requested stage/timing)"* | IR-G2 | ✅ |
| `evidence/admit.py` +11/−3 | `classify_receipt_rows` takes raw lines and parses each inside `try`; a non-dict row or a non-`str` `file_id` ⇒ unrecognised. Call site passes raw non-blank lines | IR-A1 | ✅ |
| `governance/regression/run_regression.py` +24/−4 | R1–R6; `run_case` passes stage/timing to the runner and the door; `door_forbid` | tests | ✅ |
| `governance/regression/admit_regression.py` +30/−4 | V10, V11; `RCOUNT` + `genuine_receipts()` replace the hard-coded `17` | IR-A1 tests; IR-A2 | ✅ |
| `governance/README.md` +5/−1 | door text; §6 "also INOPERATIVE since L0-DEC-21" | doc wording | ✅ |
| `developer_guide/…/06_…extended_1a.md` +5/−1 | slice section; 66/66 | doc wording | ✅ |
| `developer_guide/…/07_admit_audit_void_rows.md` +4 | IR-A1/A2 section; states that the registry section stays unguarded | doc wording | ✅ |
| `docs/plans/20260923-0212-…-plan.md` +5 | progress | plan | ✅ |
| `.claude/sessions/2026-09-23.md` +1 | session log | log | ✅ |

**Untraced hunks: none.**

**Three confirmations the commission required:**

1. **`gates.yaml` is unchanged.** It is absent from the diff, and `git log 0952af01a..HEAD` on `gates.yaml`, `gate-schema.yaml`, `governance-state.yaml` and `fixtures/` is empty. The verifier recomputed the pins independently (its own script: sha256 of canonical JSON, first 16 hex) at `6e52ba73a`, `e4c412e8f` and HEAD. They are identical at all three and match the runner's own `--pin-lines`:

   | Gate | Pin | Tier |
   |---|---|---|
   | KOS-G-001 | `485a327da2ca8f9e` | A |
   | KOS-G-002 | `7084bc523256ccb3` | A |
   | KOS-G-003 | `9327509ad713a267` | A |
   | KOS-G-010 | `45e060f20216d64d` | A |
   | KOS-G-022 | `9c5d3802a2ff6942` | A |

   ⚠️ The commission's pin list arrived garbled (two values run together, two missing). The five full values above are the verifier's own measurement; the visible fragments agree with them.

2. **`admit.py`: only the receipt-row classification and its call site changed.** `--resolve`, `--receipt`, `check()`, `load_manifest()` and the registry section have no hunks.

3. **No research evidence, receipt, registry, manifest or protocol file** appears in `6e52ba73a..e4c412e8f`. The only `evidence/` path in the range is `evidence/admit.py`. The later commits (`18133be5b`, `fb76e9099`, `70a948c96`) touch no instrument file: the `git log` above is empty.

---

## 3 · Tests-first finding

- `348d322f4` contains R1–R6 (gate), V10, V11 and the derived count (`RCOUNT`, `genuine_receipts`). It **precedes** `48e3e0f9e`, and it touches only the two suites.
- **Neither suite changed after the RED commit.** `git diff --stat 348d322f4 HEAD -- governance/regression` is empty.
- **No expectation was weakened.** In `run_regression.py` the RED commit only *adds* cases and harness plumbing (stage/timing arguments, `door_text`, `door_forbid`); none of the 60 original expectations changed. In `admit_regression.py`, V1, V2 and V9's `"read receipts    : 17"` became `RCOUNT`. That is the IR-A2 fix itself, and it is the only change to an existing expectation.
- ⚠️ Weak spot, not a weakening: R5 *forbids* the old door phrase but does not *require* the new one (see V-G2b).

---

## 4 · Reproduced measurements

All regression runs used `git archive` snapshots, per the suites' own design.

| Run | Reported | Reproduced | Match |
|---|---|---|---|
| `gate-runner.py --self-test` | 55/55 | **55/55**, exit 0 | ✅ |
| `run_regression.py` (worktree, HEAD `70a948c96`) | 66/66 | **66/66**, exit 0 | ✅ |
| `run_regression.py --control rev --rev e4c412e8f` | — | **66/66**, exit 0 | ✅ |
| `run_regression.py --control rev --rev 6e52ba73a` | R1–R5 RED; A0 and R6 match (new cases 2/7) | **61/66.** R1–R5 RED; A0 and R6 OK; all 60 original cases OK | ✅. **Full count established: 61/66** |
| `run_regression.py --control rev --rev 9abd4ad95` (pre-1a) | 26/60 on the original cases | **27/66 = 26 of the original 60 + R6**; R1–R5 RED | ✅ |
| `admit_regression.py` (worktree, HEAD) | 11/11, derived count | **11/11**; derived N = 27 | ✅ |
| `admit_regression.py --control rev --rev e4c412e8f` | — | **11/11** | ✅ |
| `admit_regression.py --control rev --rev 348d322f4` | 9/11, V10 and V11 RED | **9/11**; V10 and V11 RED (Traceback, exit 1) | ✅ |
| `admit_regression.py --control rev --rev e6087b615` (pre-repair) | 1/11 | **1/11** (only V2 matches) | ✅ |
| **live** `gate-runner.py` | exit 3, unpinned | **exit 3**, five "unpinned activation" reasons. With `--stage PHASE_9` it also lists `--stage 'PHASE_9' is not in the schema enum` | ✅ |
| **live** `admit.py --audit` | exit 3, only the nine IDs | **exit 3**, `BINDING_FAILURES_PRESENT`, 27 receipts, 1 VOID (F0047). The only failure line is the nine IDs: `F0031 F0032 F0033 F0034 F0035 F0036 F0037 F0038 F0040` | ✅ |

**Old wording:** `grep "NO BLOCKING CONTROLS"` over `governance/`, `.claude/` and the developer guides (excluding `audits/`) finds only R5's forbid-string in `run_regression.py`.

---

## 5 · Probe results

Gate probes ran on a snapshot of `e4c412e8f` (`--control rev`), with the five gates pinned by the harness `pin_of`. Admit probes ran on a snapshot of `e4c412e8f`, where the derived count is 27.

### IR-G1

| Probe | Result |
|---|---|
| tier-B KOS-G-025 alone, pinned and activated | runner **3**, door **3**: `tier B may not be activated (L0-DEC-21, IR-G1)` |
| five gates + KOS-G-025 + KOS-G-026, pinned | 3; both named |
| KOS-G-010 re-tiered A→B and re-pinned (the only route to CLEAR under the old code) | 3 |
| tier `a` (lowercase) / tier `C` / tier line removed, each re-pinned | 3 each. A schema-enum violation **and** the IR-G1 reason (missing tier: also `missing field 'tier'`) |
| tier `"A"` (quoted) | CLEAR, pin unchanged. The parsed definition is identical, so this is correct |
| `--pin-lines` for tier B / `a` / `C` / missing | refused every time: `not pinnable (… tier=…)` |
| **CLEAR reachable with an activated non-A tier?** | **No path found** |

### IR-G2

| Probe | Runner | Door |
|---|---|---|
| `--stage ""` | 3 (enum) | door passes nothing for an empty positional ⇒ **unfiltered** run, CLEAR (V-G2a) |
| `--stage phase_1` / `" PHASE_1"` | 3 / 3 | `phase_1`: 3 |
| `--timing later` only | 3 | `'' LATER`: 3 |
| `--stage PHASE_1 --timing Post` | 3 | — |
| valid `--timing POST` / `--stage PHASE_1` | CLEAR / CLEAR | `CHECKPOINT`: CLEAR |
| valid stage selecting no activated gate (`EXPERIMENT`; `PHASE_2 PRE`) | `NO_ACTIVE_GOVERNANCE_CONTROLS`, 0 | 0: *"NO ACTIVATED CONTROL WAS EXERCISED (none is activated, or none applies to the requested stage/timing)"*. **Accurate** |
| nothing activated | — | 0, same message. **Accurate** |
| three positionals `PHASE_1 POST EXTRA` | — | the third is ignored silently; CLEAR on `PHASE_1 POST` (V-G2a) |
| wording that claims "nothing activated" while gates are activated | — | **none left** (§4 grep) |

### IR-G4

| Probe | Result |
|---|---|
| unknown check (`index_coverage_v2`), re-pinned | 3: `check index_coverage_v2 not implemented (L0-DEC-21, IR-G4)`. Before the slice: BLOCK (R2 RED at `6e52ba73a`) |
| `Index_Coverage` · `" index_coverage"` · `"index_coverage "` (quoted), re-pinned | 3 each. ⚠️ The reason prints the value unquoted, so the surrounding whitespace is invisible: `check  index_coverage not implemented` (V-G4a) |
| `check: null`, re-pinned | 3: schema (`deterministic without pass_condition.check`) and IR-G4 (`check None not implemented`) |
| class REVIEW with an implemented check / with a bogus check, re-pinned | 3: the only reason is L0-DEC-14 (`class REVIEW may not be activated`). IR-G4 is restricted to AUT, so no misleading "not implemented" reason is added. **The reason is accurate** |

### IR-A1 (each appended at physical line 29 unless stated)

| Probe | Result |
|---|---|
| truncated JSON · bare string · number · `null` · `true` · list · `NaN` | exit 3, `BINDING_FAILURES_PRESENT`, `unrecognised … line(s) [29]`, receipts stay 27 |
| `file_id` int · float · bool · null · list · dict · missing | same: unrecognised at [29], receipts 27 |
| `file_id: ""` | counted as a receipt (28), reported as `file changed since reading: ['']`. Fail-closed |
| BOM at file start | row 1 unrecognised, receipts 26. Fail-closed |
| bad row inserted at physical line 5 | reported `[5]` ✅ |
| blank line, then bad row at physical line 6 | reported **`[5]`**: numbers count non-blank lines, not physical lines (V-A1b; behaviour predates the slice) |
| ⚠️ **invalid UTF-8 line** (`{"file_id": "F\xff\xfe"}`) | **exit 1, no STATUS, `UnicodeDecodeError`** (V-A1a) |
| deeply nested JSON (10⁵ levels) | exit 1, `RecursionError` (V-A1c) |
| **counted as a receipt, or skipped silently?** | none of the malformed rows was counted as a receipt. None was skipped silently. Two inputs still crash |

### IR-A2

| Question | Finding |
|---|---|
| independent of `admit.py`? | **yes.** `genuine_receipts()` is harness code with its own rule (has `sha256_at_read`, no `status`). It does not import or call `admit.py` |
| computed before the mutation? | **yes.** `admit_regression.py` L134 (`need = …genuine_receipts(t)`) precedes L135 (`if mut: mut(t)`) |
| can it agree vacuously? | **partly.** On a snapshot whose receipt stream is empty, N = 0 and `admit.py` prints `read receipts    : 0`, so the RCOUNT check agrees (probe: V2's must-contain set all present). The battery as a whole would not go green: V1 requires a VOID annotation, and V3/V4 index `L[0]`. With the receipts file **absent**, the harness raises `FileNotFoundError` (V-A2a) |
| substring match (`"…: 2"` ⊂ `"…: 27"`) | not exploitable today. `admit.py`'s rule is strictly narrower (it additionally needs all six fields and a `str` `file_id`), so its count is ≤ N before the mutation, and no mutation adds genuine receipts. Safety rests on that inequality (V-A2b) |

### Regression from the slice

- **Same reason as before?** Checked with `--json` at `6e52ba73a` against HEAD for D05, D08, D10, D11, D14, D16, D17 and U2. Every original case still carries its **original** reason. The slice **adds** reasons: D05 +IR-G4, D10 +IR-G1, D17 +IR-G4. No case now matches **only** because of a new reason.
- **Legitimate configurations still operable?**
  - the five pinned tier-A gates: CLEAR (A0);
  - all **12** `active` tier-A AUT gates pinned together: runner **exit 1, BLOCK**. That is evaluated, not INOPERATIVE;
  - the new refusals made no legitimate configuration inoperable.

---

## 6 · New findings

None of these meets a rejection criterion in the verifier's judgment. Findings are not ranked.

| ID | Finding | Purpose | Concerns | Smallest change that would close it |
|---|---|---|---|---|
| **V-A1a** | An invalid UTF-8 receipt line crashes `--audit` (exit 1, traceback, no STATUS). The file is decoded in `cmd_audit` before `classify_receipt_rows` sees the line. The behaviour predates the slice and was not made worse by it | **within.** L0-DEC-21 IR-A1 says *"an unparseable receipt line … becomes an unrecognised-row binding failure, not a traceback"* | L0-DEC-21 IR-A1. ⚠️ **If L0 reads "unparseable" to include byte-level encoding errors, IR-A1 is not closed for this input** (§8, Q-2). Loud, not a PASS | read the file as bytes and decode each line inside the existing `try`. `UnicodeDecodeError` is already a `ValueError` |
| V-A1b | Line numbers count non-blank lines, not physical lines. With a blank line before a bad row, the reported number is one low | within | IR-A1 ("line numbers"); predates the slice | `enumerate` over all lines and skip blanks inside the loop |
| V-A1c | Pathologically deep JSON raises `RecursionError` (not a `ValueError`) and crashes | beyond | — | also catch `RecursionError` |
| V-A2a | The derived count agrees vacuously on an empty receipt stream; an absent file crashes the harness | within (IR-A2), minor | L0-DEC-21 IR-A2 | assert N > 0 before the cases run |
| V-A2b | `RCOUNT` is a substring match. It is safe only because `admit.py`'s rule is narrower than the harness rule | beyond | — | match the full line, anchored at end of line |
| V-G2a | The door turns an empty positional into "no filter" (full run) and silently ignores positionals after the second. Neither gives a vacuous result: the run is a complete evaluation | within (IR-G2), minor | L0-DEC-21 IR-G2 | refuse more than two positionals, or an empty one |
| V-G2b | R5 forbids the old door phrase but does not assert the new one | within (tests), minor | L0-DEC-21 IR-G2 | add the new phrase as a must-contain |
| V-G4a | The IR-G4 reason prints `check` unquoted, so whitespace-only differences are invisible | within (IR-G4), minor | reason accuracy | format the value with `!r` |
| V-G1a | For a tier that is not `B` (e.g. `a`, `C`, missing), the IR-G1 reason still says *"an advisory gate never blocks"*. The schema-enum reason beside it is accurate | within, cosmetic | — | word the reason as "tier ≠ A" |

**Residuals left open by L0-DEC-21** (IR-G3, IR-G5, IR-G6, IR-A3…A6, R-1, R-2, IR-B1…B4): the slice made **none** of them worse.

- IR-A3's silent registry skip still exists, and V9 still depends on it.
- The unguarded registry parse is disclosed in guide 07.

---

## 7 · Verdicts

| Item | Verdict | Reason |
|---|---|---|
| **IR-G1** | **CLOSED** | Every non-A tier activation is INOPERATIVE: B, `a`, `C` and missing, alone or with the five gates. `--pin-lines` refuses every non-A tier. The review's CLEAR path (tier-B-only, and A→B re-pinned) now gives exit 3. R1 is RED before the fix and GREEN after. V-G1a is cosmetic |
| **IR-G2** | **CLOSED_WITH_FINDINGS** | Invalid, empty, differently-cased, padded and single-flag values are refused by the runner (exit 3). The door message is accurate for the unactivated case and for the filtered-to-empty case, and no "nothing activated" wording remains. R3–R5 are RED before, GREEN after. Findings: V-G2a, V-G2b |
| **IR-G4** | **CLOSED_WITH_FINDINGS** | An unknown, wrongly-cased, whitespace-padded or null check on an activated AUT gate is INOPERATIVE, not BLOCK. Non-AUT gates get only the accurate L0-DEC-14 reason. R2 is RED before, GREEN after. Finding: V-G4a (the reason is less legible) |
| **IR-A1** | **CLOSED_WITH_FINDINGS** | Both inputs named in the finding (a non-JSON line; a non-string `file_id`), plus 12 more variants, now give a visible unrecognised-row failure with no traceback. None is counted as a receipt or skipped. V10 and V11 are RED before, GREEN after. **Material finding V-A1a:** invalid UTF-8 still crashes. It is loud, not a false PASS, and was not introduced by the slice. Its classification depends on L0's reading of "unparseable" (Q-2) |
| **IR-A2** | **CLOSED_WITH_FINDINGS** | The count is derived independently of `admit.py`, before the mutation. 11/11 reproduces at HEAD, at `e4c412e8f` and at every other state tested. Findings: V-A2a, V-A2b |
| **Slice as a whole** | **ACCEPT_WITH_FINDINGS** | All five items were implemented exactly as authorized, and every hunk traces to an item. No untraced change. No change to `gates.yaml` (the pins stay valid). No evidence or registry file was touched, and no original expectation was weakened. Tests-first is demonstrated. All reported numbers reproduce. **No rejection criterion is met, with one qualification:** if L0 reads IR-A1's *"unparseable receipt line"* as including invalid UTF-8, IR-A1 is NOT_CLOSED for that input, and the first rejection criterion ("fails to close its finding") would apply to that one input class |

---

## 8 · Unresolved questions for L0

- **Q-1.** Does this verification satisfy L0-DEC-21's *"fresh independent verification"*, given that the verifier also wrote the 1a.6 review (header)?
- **Q-2.** Does IR-A1's *"unparseable receipt line"* include byte-level encoding errors (V-A1a)? If yes, IR-A1 is not closed for that input.
- **Q-3.** Attestation: L0-DEC-20/21 are marked *UNVERIFIED (AI transcription)* in the record. **NOT VERIFIED** by this verifier.
- **Q-4.** Session identity under L0-DEC-15 (that the governance session made the slice's commits): **NOT VERIFIED**. Git does not record session identity.

---

## 9 · Outside this verification and this recommendation

This verification does **not** decide or recommend, and nothing in it should be read as deciding or recommending:

- **1a.7** acceptance (D-1, D-2);
- **pin installation**;
- the **live release run** under L0-DEC-19;
- **batch 4** release;
- the treatment of the nine RC-H-04 IDs (F0031–F0038, F0040);
- the standing of batches 2 and 3 (L0-DEC-20);
- the L0-DEC-22 protocol amendments, or the KOS-G-044 wording they note;
- the Candidate Theory.

It does not re-review extended 1a or the L0-DEC-18 repair as a whole; the 1a.6 review stands as written. These remain L0 decisions.

*Traceability:* L0-DEC-20/21 (`governance/L0-DECISION-RECORD-01.md` §"L0-DEC-20 · L0-DEC-21") · `audits/2026-09-23-GIA-1a6-INDEPENDENT-REVIEW.md` §7 · commits `6e52ba73a` `348d322f4` `48e3e0f9e` `e4c412e8f` · HEAD `70a948c96` · probe scripts `probe_g.py`, `probe_a.py` and `pins.py` in the verifier's session scratchpad (**not committed, not durable**; mutations described in §5).
