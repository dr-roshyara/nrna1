**Freshness declaration.** This session has **no prior context** from the implementation, from the 1a.6 review (`f21b3e6e3`) or from VERIFICATION-02. It was **launched as a subagent by the governance session that implemented the work under review.** That session passed nothing beyond a short launch message naming this commission and restating its constraints. So this session is **fresh in context, but not organisationally independent** of the implementing session. Before measuring I read only L0-DEC-20/21/24/25 (plus the L0-DEC-22/23/26/27 entries next to them in the same record), the **finding texts** of the 1a.6 review §7, and the commission. VERIFICATION-02 §5–§7 was opened **only after** every measurement and probe below had been recorded (see §5.7). I did not consult the 1a.6 review's reasoning, probe tables or verdicts. ⚠️ The scratchpad directory assigned to this session already held files I did not create (for example `gia/`, `red.txt`, `green.jsonl`). Most likely they belong to the launching session. I did not open them.

# L0-DEC-21 correction slice + L0-DEC-24 fix: fresh verification (03)

## 1 · Header

| | |
|---|---|
| **Verifier** | Claude (Opus 5.5), a subagent session: fresh in context, not organisationally independent (see declaration) |
| **Commission** | `governance/audits/2026-09-23-GIA-1a6-VERIFICATION-03-COMMISSION.md` (read directly from disk at HEAD `0b406173e`) |
| **Commits evaluated** | `6e52ba73a` · `348d322f4` · `48e3e0f9e` · `e4c412e8f` · `d0ef5b506` · `29b2dbe9c` · `cf8d2f4d0` · `1c5c5bea3`. Checked for side effects only: `18133be5b` · `fb76e9099` · `70a948c96` · `628d02169` · `2f09c9f41`, plus the later record-only commits `dbae2f01f` · `b2d5e6097` · `d4290e486` · `bbd5bfe25` · `f3498c454` · `0b406173e` |
| **Method** | (1) decisions and finding texts; (2) full diff `6e52ba73a..cf8d2f4d0`, `git show 1c5c5bea3`, and pins recomputed by my own script; (3) tests-first ordering and suite diffs; (4) every command in the commission table; (5) my own adversarial probe scripts, run **only on `git archive` copies** in the scratchpad. On the live workspace I ran only `gate-runner.py` (incl. `--self-test`, `--pin-lines`) and `admit.py --audit`. The regression batteries also work on archive copies |
| **Modified** | **only this file**, uncommitted. The workspace `W` was confirmed clean (`git status --short` over `W`: empty) before and after |

## 2 · Diff scope

`git diff 6e52ba73a cf8d2f4d0`: 17 files, +1093/−20. `6e52ba73a` itself (the base) was read with `git show`: session log + L0-DECISION-RECORD only. History `6e52ba73a..HEAD` is linear, with no merges.

| File / hunk | Commit(s) | Traces to | Status |
|---|---|---|---|
| `governance/gate-runner.py`, `operability()`: `tier != "A"` ⇒ reason | `48e3e0f9e` | IR-G1 | ✅ |
| `gate-runner.py`, `operability()`: AUT gate with `check not in CHECKS` ⇒ reason | `48e3e0f9e` | IR-G4 | ✅ |
| `gate-runner.py`, `--pin-lines`: `tier != "A"` ⇒ "not pinnable" | `48e3e0f9e` | IR-G1 | ✅ |
| `gate-runner.py`, `main()`: `--stage`/`--timing` checked against the `gate-schema.yaml` `field_rules` enums | `48e3e0f9e` | IR-G2 | ✅ |
| `.claude/hooks/governance-preflight.sh`: no-control message reworded (2 lines + comment) | `48e3e0f9e` | IR-G2 (door wording) | ✅ |
| `evidence/admit.py` `classify_receipt_rows()`: parse inside `try`; non-dict or non-str `file_id` ⇒ unknown; docstring | `48e3e0f9e` | IR-A1 | ✅ |
| `admit.py`: `line.decode("utf-8")` inside the same `try`; docstring line | `cf8d2f4d0` | L0-DEC-24 | ✅ |
| `admit.py` `cmd_audit()` call site: raw lines, then (`cf8d2f4d0`) `open(RECEIPTS, "rb")` | `48e3e0f9e`, `cf8d2f4d0` | IR-A1, L0-DEC-24 | ✅ |
| `governance/regression/run_regression.py`: R1–R6; harness passes stage/timing to runner and door; `door_forbid` | `348d322f4` | IR-G1/G2/G4 tests | ✅ |
| `governance/regression/admit_regression.py`: `genuine_receipts()`/`RCOUNT`; V10, V11 | `348d322f4` | IR-A2, IR-A1 tests | ✅ |
| `admit_regression.py`: V12 | `29b2dbe9c` | L0-DEC-24 test | ✅ |
| `governance/README.md` (2 hunks); `developer_guide/knowledgeos/06_…`, `07_…` | `48e3e0f9e`, `e4c412e8f`, `cf8d2f4d0` | doc wording | ✅ |
| `docs/plans/20260923-0212-…-plan.md` progress; `.claude/sessions/2026-09-23.md` | several | records | ✅ |
| `L0-DECISION-RECORD-01.md` | `6e52ba73a`, `fb76e9099`, `70a948c96`, `d0ef5b506` | decision recording | ✅ |
| `…-VERIFICATION-02-COMMISSION.md`, `…-SLICE-VERIFICATION-02.md` | `2f09c9f41`, `d0ef5b506` | records | ✅ |
| `prompts/knowledge_os_protocoll.md`, `prompts/…step2…protocol.md`, `PROTOCOL-CHANGE-RECORD-01.md`, `SENIOR-RESEARCHER-BASELINE-01.md` | `18133be5b`, `fb76e9099`, `628d02169` | research/protocol text (L0-DEC-22/23/26). **No instrument side effect:** all are `.md`. The runner, `admit.py` and the door do not reference `prompts/` or these files (grep). No `.jsonl` was added, so KOS-G-001/003 scan scope is unchanged | ✅ |
| `governance/gates.yaml` | `1c5c5bea3` only | KOS-G-044 text | ✅ (below) |

**Flagged untraced hunks:** none.

**Confirmations requested by the commission (facts):**
- `gates.yaml`: `git diff 6e52ba73a cf8d2f4d0 -- gates.yaml` is **empty**. `git log cf8d2f4d0..HEAD -- gates.yaml` shows **only `1c5c5bea3`**.
- `1c5c5bea3`: one hunk, inside the KOS-G-044 block. Parsed comparison `cf8d2f4d0` vs HEAD: 25 gates, the same ids. **Only KOS-G-044 differs**, and only in the fields `purpose` and `pass_condition` (`rule` text). Its class is REVIEW, so `operability()` refuses to activate it and `--pin-lines` prints `not pinnable (status=active, class=REVIEW, tier=A)`. It is not in `governance-state.yaml`.
- **Pins, recomputed by my own script** (PyYAML parse → `json.dumps(sort_keys, compact, ensure_ascii=False, default=str)` → sha256 → first 16 hex):

  | Gate | `6e52ba73a` | `cf8d2f4d0` | HEAD | `--pin-lines` at `cf8d2f4d0` (archive copy) | `--pin-lines` at HEAD (live) | implementer |
  |---|---|---|---|---|---|---|
  | KOS-G-001 | 485a327da2ca8f9e | 485a327da2ca8f9e | 485a327da2ca8f9e | 485a327da2ca8f9e | 485a327da2ca8f9e | 485a327da2ca8f9e |
  | KOS-G-002 | 7084bc523256ccb3 | 7084bc523256ccb3 | 7084bc523256ccb3 | 7084bc523256ccb3 | 7084bc523256ccb3 | 7084bc523256ccb3 |
  | KOS-G-003 | 9327509ad713a267 | 9327509ad713a267 | 9327509ad713a267 | 9327509ad713a267 | 9327509ad713a267 | 9327509ad713a267 |
  | KOS-G-010 | 45e060f20216d64d | 45e060f20216d64d | 45e060f20216d64d | 45e060f20216d64d | 45e060f20216d64d | 45e060f20216d64d |
  | KOS-G-022 | 9c5d3802a2ff6942 | 9c5d3802a2ff6942 | 9c5d3802a2ff6942 | 9c5d3802a2ff6942 | 9c5d3802a2ff6942 | 9c5d3802a2ff6942 |

  All agree. ⚠️ My script uses the same algorithm and the same YAML library as the runner. It is a separately written copy, so it is not independent of a defect common to that algorithm or library (cf. IR-G5).
- `admit.py`: the only hunks are `classify_receipt_rows()` (body and docstring) and its call site in `cmd_audit()`. `admit.py` at `cf8d2f4d0` is byte-identical to HEAD.
- No research evidence, receipt, registry or manifest file changed in any commit `6e52ba73a^..HEAD` (the name-only list contains no `.jsonl`, manifest, registry, fixture, schema or state file). The live `READ-RECEIPTS.jsonl` equals HEAD.

## 3 · Tests-first finding

- **Order (fact):** the linear history runs `348d322f4` (R1–R6, V10, V11, the derived count) → `48e3e0f9e` (fix), and `29b2dbe9c` (V12) → `cf8d2f4d0` (fix).
- **The fixes touch no tests:** `git diff 348d322f4 48e3e0f9e -- governance/regression` is empty, and so is `git diff 29b2dbe9c cf8d2f4d0 -- governance/regression`.
- **Suite changes across the range:** `run_regression.py` is unchanged from `348d322f4` to HEAD. `admit_regression.py` changes from `348d322f4` to HEAD only by adding `invalid_utf8_line` and V12. Neither suite changes after `cf8d2f4d0`.
- **Weakening:** none. No expectation written in a RED commit was relaxed later. `348d322f4` replaced the hard-coded `17` in V1/V2/V9 with the derived `RCOUNT`. That is the authorized IR-A2 change, and the count is still asserted. Its residual imprecision is covered in §5.5.
- **RED confirmed by my own runs:** R1–R5 are RED at `6e52ba73a` (§4). V10, V11 and V12 are RED at `348d322f4`, and V12 alone is RED at `29b2dbe9c`.

## 4 · Reproduced measurements

| Command (from `W`) | Implementer reports | **I measured** | Match |
|---|---|---|---|
| `gate-runner.py --self-test` | 55/55 | **55/55**, exit 0 | ✅ |
| `run_regression.py` | 66/66 | **66/66**, exit 0 | ✅ |
| `… --control rev --rev 6e52ba73a` | 61/66 (R1–R5 RED) | **61/66**. RED: R1 (CLEAR), R2 (BLOCK), R3/R4 (NO_ACTIVE…), R5 (old door phrase). All 60 original cases plus R6 are OK | ✅ |
| `… --control rev --rev 9abd4ad95` | 27/66 | **27/66**, exit 1 | ✅ |
| `admit_regression.py` | 12/12, derived count 27 | **12/12**, exit 0. RCOUNT resolves to `read receipts    : 27` (visible in the RED output below) | ✅ |
| `… --control rev --rev 29b2dbe9c` | 11/12 (V12 RED) | **11/12**. V12: exit 1, `Traceback`, no STATUS | ✅ |
| `… --control rev --rev 348d322f4` | V10, V11, V12 RED | **9/12**. V10, V11, V12 RED (exit 1, traceback) | ✅ |
| `… --control rev --rev e6087b615` | pre-repair; most cases RED | **1/12** (only V2 OK) | ✅ |
| live `gate-runner.py` | exit 3 (unpinned activations) | **exit 3**, `GOVERNANCE_INOPERATIVE`: 5 × `unpinned activation (L0-DEC-16)` | ✅ |
| live `admit.py --audit` | exit 3, `BINDING_FAILURES_PRESENT`; only failure = the nine IDs | **exit 3**. 27 receipts, 1 VOID (F0047), 18 defective manifest rows (recorded). The **only ⛔ line** is the registry divergence of F0031–F0038 and F0040 | ✅ |

**Differences from the reported values:** none.

## 5 · Probe results (git-archive copies only)

Scripts: `scratchpad/gprobe.py` (runner and door) and `scratchpad/aprobe.py` (admit). Unless stated, each gate probe starts from HEAD with the five gates pinned (A0 = CLEAR).

### 5.1 IR-G1: reach CLEAR with an activated non-A tier

| Probe | HEAD | Pre-slice (`6e52ba73a`) |
|---|---|---|
| **KOS-G-025 (tier B) re-pointed at a check that FAILS on the data (`relations_coverage`), pinned + activated** | **runner 3 / door 3**, `tier B may not be activated` | **CLEAR / door 0 with KOS-G-025 = FAIL.** The review's false-assurance path, reproduced |
| same, KOS-G-025 not activated (control) | CLEAR (025 counted in `failing` only) | same |
| pinned KOS-G-025 / 026 (AUT, B) / 013 / 046 (REVIEW, B) | 3 each (REVIEW ones also carry the L0-DEC-14 reason) | — |
| KOS-G-025 bare | 3 (tier + unpinned) | — |
| KOS-G-025 pinned + `--stage PHASE_1` (filter excludes it) | 3. Operability applies before filtering | — |
| KOS-G-001 re-tiered to `B` / `a` / `'A '` / `null` / line removed, each re-pinned | 3 each | — |
| schema tier enum widened to `[A, B, C]`, 001 → `C`, re-pinned | 3 (IR-G1 reason alone; the schema no longer objects) | — |
| KOS-G-025 activated twice (duplicate entry) | 3 | — |
| KOS-G-025 re-tiered B → A and re-pinned (a legitimate human act) | CLEAR, 6 applicable. Correct: it is now tier A | — |
| `--pin-lines` for 025, 026, 013, and for 001 with `tier: null` | `not pinnable (… tier=B / None)` | — |

**Result:** no path to CLEAR with an activated non-A gate was found.

### 5.2 IR-G2: stage/timing values; door wording

| Value | Runner (`--stage`/`--timing`) | Door (positional) |
|---|---|---|
| `PHASE_9` · `phase_1` · `Phase_1` · `' PHASE_1'` · `'PHASE_1 '` · `'PHASE_1\n'` | 3 each, reason names the enum | 3 each |
| timing `LATER` · `pre` · `'PRE '` | 3 | 3 |
| stage `''` (also `--stage=`) · timing `''` | **3** (`'' is not in the schema enum`) | **0, CLEAR.** The door drops an empty positional, so the run is unfiltered (stage `''`) or stage-only (timing `''`). The CLEAR is a real evaluation of every gate it selected |
| door `'' LATER` / `'' POST` | — | 3 / CLEAR over 3 applicable gates |
| valid values selecting activated gates (`PHASE_1`, `PHASE_2`, `CHECKPOINT`, `PHASE_1 POST`) | CLEAR, 1–3 applicable | CLEAR |
| valid values selecting none (`EXPERIMENT`, `COMMIT`, `PHASE_1 PRE`, `PHASE_1 CHECKPOINT`) | 0, `NO_ACTIVE_GOVERNANCE_CONTROLS`, "Applicable activated gates: 0" | 0: *"NO ACTIVATED CONTROL WAS EXERCISED (none is activated, or none applies to the requested stage/timing)"* |
| nothing activated (no stage / `PHASE_1`) · nothing activated + `PHASE_9` | 0 NO_ACTIVE · 3 | same message · 3 |
| stage enum deleted from the schema copy | 3 (`not in the schema enum []`). Fails closed | 3 |

**Wording:** the door no longer claims that nothing is activated. ⚠️ **The runner's result token is still `NO_ACTIVE_GOVERNANCE_CONTROLS`** when five gates are activated and a filter excludes them all (N-4). That line sits beside "Applicable activated gates: 0", and the door text is accurate.

### 5.3 IR-G4: re-pinned unknown check, variants

| Variant (KOS-G-010, re-pinned) | HEAD | `6e52ba73a` |
|---|---|---|
| `index_coverage_v2` · `Index_Coverage` · `'index_coverage '` · `7` (int) | **3**, `check … not implemented (IR-G4)` | **BLOCK, door 2** |
| same, not re-pinned | 3 (IR-G4 + pin mismatch) | 3 (pin mismatch) |
| `''` · `null` | 3 (schema + IR-G4) | 3 (schema) |
| `type: review`, check key removed | 3 (IR-G4) | BLOCK |
| `[index_coverage]` (list) · `{a: 1}` (mapping) | 3 **via the top-level crash handler** (`runner crashed: TypeError: unhashable type`). `--json` prints non-JSON text (N-3) | the same crash (pre-existing) |
| unimplemented check on a **non-activated** AUT gate | CLEAR (the row is ERROR, listed in `failing`, not blocking). As designed | same |

**Result:** every unimplemented-check variant I tried on an activated gate gives INOPERATIVE, never BLOCK.

### 5.4 IR-A1 incl. L0-DEC-24 UTF-8

The probes ran with the registry removed from the copy, so the baseline is `BOUND`, exit 0, and every added failure is visible. They also ran with the registry present, with the same classification. Rows are appended at physical line 29 unless stated.

| Input | HEAD (`cf8d2f4d0` = HEAD) | `e4c412e8f` (IR-A1, text mode) | `348d322f4` (pre-slice) |
|---|---|---|---|
| invalid UTF-8: `\xff\xfe` line at **start** / `\xff` row in the **middle** (line 11) / `\x80` at **end without newline** / `\xff` inside an otherwise valid receipt / lone `\x80` / overlong `\xc0\xaf` / surrogate `\xed\xa0\x80` / truncated `\xe2\x82` at EOF / latin-1 `\xe9` | **exit 3, unrecognised at [1] / [11] / [29]…, receipts 27, no traceback** | exit 1, `UnicodeDecodeError` | exit 1, `UnicodeDecodeError` |
| UTF-8 BOM at file start | exit 3, row 1 unrecognised, receipts 26 | same | exit 1 (`JSONDecodeError`) |
| CRLF throughout | BOUND, 27 | BOUND, 27 | BOUND, 27 |
| **bare CR throughout** | **exit 3, receipts 0, one unrecognised row [1]** | BOUND, 27 | BOUND, 27 |
| **one bare CR joining rows 3 and 4** | **exit 3, receipts 25, unrecognised [3]** | BOUND, 27 | BOUND, 27 |
| **NBSP-only line / `\x1c`-only line** | **exit 3, unrecognised [29]** | **BOUND (skipped silently)** | **BOUND (skipped silently)** |
| form-feed-only line | BOUND (skipped as blank) | same | same |
| truncated JSON · two objects on one line | exit 3, unrecognised | same | exit 1 |
| non-dict rows: `[]` `"str"` `42` `null` `true` `NaN` | exit 3, unrecognised | same | exit 3, unrecognised |
| `file_id` int / float / bool / null / list / dict / missing; VOID with int `file_id` | exit 3, unrecognised, receipts 27 | same | int/float/bool/null: **counted as receipt (28)**, drift failure; list/dict: traceback |
| `file_id: ""` · receipt with list `sha256_at_read` · with dict `manifest_hash` | counted as a receipt (28) **and** a visible failure (drift / drift / stale) | same | same |
| 10⁵-deep nesting | exit 1, `RecursionError` (V-A1c, recorded residual) | same | same |

**Result:** no probe crashes, except the recorded residual V-A1c. No probe is skipped silently. No non-string `file_id` or undecodable line is counted as a receipt. Three **string**-`file_id` rows are counted as receipts, and each also raises a visible failure (a string `file_id` is outside IR-A1's scope). The byte-level read **changed** the handling of bare-CR terminators and of lines that contain only non-ASCII whitespace. The change is stricter and visible (N-1).

### 5.5 IR-A2: can the derived count agree vacuously?

- **Independence (fact):** `genuine_receipts()` counts rows with `sha256_at_read` and no `status`. It runs before the mutation and does not import `admit.py`.
- **Probe:** I patched `admit.py` in a copy and matched its output against the harness's own `RCOUNT` (N = 27).

  | Patched output | Expected | Result |
  |---|---|---|
  | `28` (VOIDs counted as receipts) | RED | **no match**: detected |
  | `270` (×10) | RED | **match** |
  | `277` (+250) | RED | **match** |

- **Conclusion:** the check is a non-anchored substring match. It agrees vacuously whenever the printed count begins with the digits of N. It would also agree on an empty stream (N = 0), and an absent file crashes the harness instead (not green). This is the same as VERIFICATION-02's V-A2a/V-A2b, which I read afterwards. **Not exploitable by any current case:** no mutation adds genuine receipts, and `admit.py`'s rule is narrower. So it is a residual in test thoroughness, not a present false GREEN.

### 5.6 The slice as a whole

- **Different reason?** `run_regression.py --json` at HEAD vs `--control rev --rev 6e52ba73a`, all 66 cases. The 60 original cases have identical results and door exits. Four differ only in the reason text: D05 (+IR-G4), D10 (+IR-G1), D17 (+IR-G4), and D07. D07 now prints the **same schema-parse error twice**, because the new IR-G2 block reloads `gate-schema.yaml` (N-2, cosmetic). In each of the four, the original reason is still present. **No original case now passes only because of a new reason.**
- **Five pinned tier-A gates operable:** yes. A0 and U4 are CLEAR, and my own A0 is CLEAR with all five PASS.
- **Byte-level read vs the live 27 receipts and the VOID:**
  - The live file is 10,671 bytes: 28 lines, 0 CR, no BOM, valid UTF-8, no blank lines.
  - `classify_receipt_rows()` at `6e52ba73a`, `e4c412e8f`, `cf8d2f4d0` and HEAD gives **identical** receipts (27), VOIDs (1, F0047) and unknown ([]) on the live file (read-only import).
  - The full `--audit` output and exit code on an archive copy are **byte-identical** between `admit.py` at `6e52ba73a` and at HEAD.
  - **No change for the live data.**

### 5.7 Comparison with VERIFICATION-02, consulted after the above

- **Agreement:** all reproduced numbers. The IR-G1, IR-G2 and IR-G4 outcomes. V-G2a (empty positional), V-G4a (unquoted value), V-A1b, V-A1c, V-A2a, V-A2b. The IR-A1 results except UTF-8.
- **Differences:**
  - (a) VERIFICATION-02's **V-A1a** (invalid UTF-8 crashes) **no longer reproduces**. It is fixed by `cf8d2f4d0`, which postdates that report.
  - (b) VERIFICATION-02 says no "nothing activated" wording remains. I agree **for the door**, and I additionally record the **runner token** (N-4).
  - (c) New items not in VERIFICATION-02: N-1 (a consequence of the L0-DEC-24 fix, so it could not appear there), N-2, N-3.
  - (d) VERIFICATION-02 found the 1a.6 review's pre-1a figure "26/60" as 27/66. I measured 27/66 independently.
  - (e) I did not repeat VERIFICATION-02's "all 12 active tier-A AUT gates pinned together ⇒ BLOCK": NOT VERIFIED by me.

## 6 · New findings

Each finding is marked **within** or **beyond** purpose. The last column is a **proposed** L0-DEC-27 class. It is a recommendation; the classification is L0's.

| ID | Fact | Purpose | Ties to | Smallest change | Proposed class |
|---|---|---|---|---|---|
| **N-1** | Since `cf8d2f4d0`, `--audit` splits the receipt file on `\n` only and strips ASCII whitespace only. (a) A file with bare-CR terminators, or one bare CR between two rows, now fails as one unrecognised row: receipts 0, or 25 instead of 27. Before, it was accepted as 27. (b) A line containing only NBSP or `\x1c` is now an unrecognised row. Before, it was **skipped silently**. Both changes are stricter and visible (exit 3). The live data is unaffected | within (IR-A1 / L0-DEC-24 side effect) | L0-DEC-24 ("nothing else in `admit.py` changes": the read mode is the authorized means, and this is its consequence). No IC. Not false assurance: (b) removes a silent skip | none required. Optionally note in guide 07 that the stream must be `\n`-terminated UTF-8 | R3 |
| **N-2** | When `gate-schema.yaml` does not parse, the INOPERATIVE reason repeats the same parse error twice (D07). `main()` reloads the schema for the IR-G2 check after `operability()` has already reported it | within (IR-G2), cosmetic | IC-4 (still INOPERATIVE, correct reason present) | skip the enum check when the schema load already failed, or reuse one load | R3 |
| **N-3** | An activated gate whose `check` is a YAML list or mapping crashes the runner (`TypeError: unhashable`). The top-level handler turns this into `GOVERNANCE_INOPERATIVE`, exit 3, door 3. But `--json` then prints non-JSON text and the reason is a crash, not the IR-G4 reason. **Pre-existing**, identical at `6e52ba73a`; not made worse | beyond (not an input named in IR-G4) | IC-6 (still exit 3) | `isinstance(check, str)` before the `CHECKS` lookup, or a schema type rule | R3 |
| **N-4** | With gates activated and a valid filter that selects none of them, the runner still prints `STATUS: NO_ACTIVE_GOVERNANCE_CONTROLS`. The door text beside it is accurate, and so is the line "Applicable activated gates: 0". L0-DEC-21 asked only that the **door's** message be accurate | within (IR-G2 wording), minor | L0-DEC-21 IR-G2 (met for the door) | none required for the door. Optionally document the token as "no activated control applied" in README §5A | R3 |

Recorded residuals re-observed and **not made worse**: V-A1b, V-A1c, V-A2a, V-A2b, V-G2a, V-G2b (R5 still only forbids the old phrase), V-G4a, V-G1a, IR-G5 (pin algorithm copied), IR-A4 (`file_id: ""` reported as "file changed").

## 7 · Verdicts

```text
IR-G1:                              CLOSED
IR-G2:                              CLOSED_WITH_FINDINGS
IR-G4:                              CLOSED_WITH_FINDINGS
IR-A1 (incl. L0-DEC-24 UTF-8):      CLOSED_WITH_FINDINGS
IR-A2:                              CLOSED_WITH_FINDINGS
Slice + fix as a whole:             ACCEPT_WITH_FINDINGS
```

- **IR-G1: CLOSED.** The review's false-assurance path (an activated tier-B gate that FAILs ⇒ CLEAR) reproduces at `6e52ba73a` and is INOPERATIVE at HEAD. No variant (B, `a`, `'A '`, null, missing, widened enum, filtered, duplicated) reaches CLEAR. `--pin-lines` refuses every non-A tier. R1 was RED before the fix and GREEN after. V-G1a is a residual only.
- **IR-G2: CLOSED_WITH_FINDINGS.** Every invalid, cased, padded or empty value is refused by the runner, and a schema without an enum fails closed. The door's no-control message is accurate in both the unactivated and the filtered-to-empty case. R3–R5 were RED before and GREEN after. Findings: V-G2a (the door treats an empty positional as "no filter": a full evaluation, not a vacuous one), V-G2b, N-2, N-4.
- **IR-G4: CLOSED_WITH_FINDINGS.** Every string, int or null unknown check on an activated AUT gate gives INOPERATIVE, not BLOCK. R2 was RED before and GREEN after. Findings: V-G4a, and N-3 (pre-existing; fails closed through the crash handler).
- **IR-A1 (incl. L0-DEC-24): CLOSED_WITH_FINDINGS.** The finding's inputs (non-JSON line, non-string `file_id`) and the L0-DEC-24 input (invalid UTF-8, nine byte patterns at the start, middle and end, with and without a trailing newline) all give a visible unrecognised-row failure, with no traceback and no receipt counted. Only receipt classification, its call site and its docstring changed. The live 27 receipts and the VOID are classified identically. Findings: N-1, and the residuals V-A1b and V-A1c (unchanged).
- **IR-A2: CLOSED_WITH_FINDINGS.** The count is derived independently, before the mutation, and reproduces at HEAD and at `29b2dbe9c` and `348d322f4`. The finding (a hard-coded 17 that fails at HEAD) is closed. The residual substring or vacuous agreement (V-A2a, V-A2b) is demonstrated, but no current case can trigger it.
- **Whole: ACCEPT_WITH_FINDINGS.** Every hunk traces to the six items, their tests, doc wording or decision records. `gates.yaml` is unchanged within `6e52ba73a..cf8d2f4d0`. Its only later change is the KOS-G-044 text (`1c5c5bea3`), and the five activated pins are unchanged (recomputed). No evidence, receipt, registry or manifest file changed. Tests-first is demonstrated, with no weakening. All ten reported measurements reproduce exactly. **No rejection criterion is met:**
  - every finding is closed;
  - no new false assurance was found;
  - no violation of L0-DEC-12…25 or IC-1…IC-8 was found;
  - no unexplained correctness defect was found. N-1 is an explained, stricter consequence of the L0-DEC-24 means.

## 8 · Outside this recommendation

This verification does **not** recommend, and makes no statement about, any of the following: 1a.7 acceptance (D-1/D-2), pin installation, the live release run or the Research Release Check, Batch 4, the nine RC-H-04 IDs (F0031–F0038, F0040), or the Critical Attack Pass. Extended 1a and the L0-DEC-18 repair were not re-reviewed as a whole. No corpus file was read, and the theory was not judged. **The L0 human decides.**
