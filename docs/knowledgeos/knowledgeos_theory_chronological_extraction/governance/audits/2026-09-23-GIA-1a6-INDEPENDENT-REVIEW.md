# GIA 1a.6 — Independent Review: extended Increment 1a and the `admit.py` repair

| | |
|---|---|
| **Kind** | independent governance review (L0-DEC-13 step 1a.6; L0-DEC-18 "followed by independent review"). ⛔ **Not an L0 decision.** It recommends nothing to L0 about acceptance and decides nothing |
| **Reviewer** | a separate review session. ⚠️ **Same model family as the implementing and research sessions.** Its independence is session-level only, not organisational |
| **Date** | 2026-09-23 |
| **Repository state reviewed** | branch `knowelegeos-modelling`. HEAD was `b1844ab70` at review start and **moved to `c360b89cb` during the review**, when a concurrent session committed. Instrument files are byte-identical across both (§2.3) |
| **Acceptance standard** | the fit-for-purpose standard given in the review commission. ⚠️ It is **review-specific guidance, not an L0 decision**, and is not treated here as L0-DEC-20 |
| **Modified by this review** | this file only. Uncommitted. All mutations ran on `git archive` copies in the reviewer's scratchpad. No implementation, evidence, registry, activation or governance record was touched |

---

## 1 · Review identity and scope

**In scope**

1. Extended Increment 1a, the research-gate instrument: `governance/gate-runner.py`, `gates.yaml`, `gate-schema.yaml`, `governance-state.yaml` as read by the runner, `fixtures/`, `.claude/hooks/governance-preflight.sh`, `regression/run_regression.py`.
2. The `admit.py --audit` repair under L0-DEC-18: `evidence/admit.py` `classify_receipt_rows()` and `cmd_audit()`, and `regression/admit_regression.py`.
3. The actual regression evidence, re-run by the reviewer.
4. The fit-for-purpose yardsticks:
   - **Pins:** does a change to an activated gate's definition, made without human re-activation, always give `GOVERNANCE_INOPERATIVE`?
   - **`admit.py`:** does it correctly tell apart a valid receipt, a VOID annotation and an unrecognised row, without crashing and without silently ignoring malformed evidence?

**Out of scope:** see §11.

---

## 2 · Evidence inspected

### 2.1 Governance records (read)

| Record | What was taken from it |
|---|---|
| `governance/L0-DECISION-RECORD-01.md` L270–L305 | L0-DEC-12…17 (1a authorization, pins, canonical list, governance-session-only edits), L0-DEC-18 (narrow `admit.py` repair), L0-DEC-19 (release condition, 7 items). All marked *Attestation: UNVERIFIED (AI transcription)* in the record itself |
| `governance/GIA-DECISION-PACKAGE-01.md` §7 L98–L105 | IC-1…IC-8 text and the defect IDs each one closes |
| `governance/audits/2026-09-23-GATE-INTEGRITY-AUDIT-02.md` | origin of D01–D18, B01–B25, G01/G02 |
| `governance/audits/2026-09-23-ADMIT-AUDIT-CRASH-FINDING.md` | A-1…A-5 |
| `developer_guide/knowledgeos/06_…extended_1a.md`, `07_admit_audit_void_rows.md` | the implementer's claims, checked against code and runs below |
| `docs/plans/20260923-0212-kos-evidence-binding-increment-1-plan.md` L140–L162 | 1a.1–1a.5 progress, in-scope decisions, residuals R-1…R-3 |
| `governance/REVIEW-EVIDENCE.md` | read, but **not relied on**: it predates 1a (its integrity hashes are pre-1a) |

### 2.2 Commits inspected

| Commit | Content | Checked |
|---|---|---|
| `4746093a8` | 1a.2 regression fixtures + IC-7 case fixtures (tests first) | files touched: fixtures and regression only |
| `0952af01a` | 1a.3–1a.5 implementation | files touched: runner, `gates.yaml`, door, `README.md`, regression, guide, plan, session log. **`governance-state.yaml` untouched** (L0-DEC-16 note honoured) |
| `f5067fd31` | `admit_regression.py` (tests first) | one file |
| `dccc218e1` | `admit.py` repair | `admit.py` (+45/−2), guide, index, session log. **No evidence or registry file touched** (L0-DEC-18 ⛔ clause honoured) |
| `74c789df4` | guide 07 wording | docs only |

**Post-RED change to the battery.** `0952af01a` edited `run_regression.py` after the RED commit. The diff shows two kinds of change. (a) Reason substrings were tightened (`"pin"` → `"KOS-G-010: pin mismatch"` in D05, D10 and U2). (b) The harness now writes pins **quoted**. No `result` or `door` expectation was changed. The edits make the battery stricter, not looser.

### 2.3 Instrument stability during the review

`git log 0952af01a..HEAD` on the runner, `gates.yaml`, schema, state, fixtures, regression and hook lists **only `f5067fd31`** (the admit regression file). `git log dccc218e1..HEAD -- evidence/admit.py` is **empty**. The HEAD move to `c360b89cb` therefore changed research evidence, not the instrument.

### 2.4 Commands run by the reviewer

All runs were made from `docs/knowledgeos/knowledgeos_theory_chronological_extraction/`:

| # | Command | Result |
|---|---|---|
| C1 | `python3 governance/gate-runner.py --self-test` | **55/55**, exit 0 |
| C2 | `python3 governance/gate-runner.py` (live) | **`GOVERNANCE_INOPERATIVE`**, exit 3: all five activations unpinned |
| C3 | `bash .claude/hooks/governance-preflight.sh` (live) | door exit **3** |
| C4 | `run_regression.py` (worktree control, `--rev HEAD`=`b1844ab70`) | **60/60**, exit 0 |
| C5 | `run_regression.py --rev c360b89cb` | **60/60**, exit 0 |
| C6 | `run_regression.py --control rev --rev 9abd4ad95` (pre-1a runner) | **26/60 match, 34 RED**, exit 1 |
| C7 | `admit_regression.py --control rev --rev e6087b615` | **1/9** (only V2 matches), exit 1 |
| C8 | `admit_regression.py --control rev --rev f5067fd31` | **1/9** |
| C9 | `admit_regression.py --rev dccc218e1` (repaired `admit.py`) | **9/9**, exit 0 |
| C10 | `admit_regression.py` (default `--rev HEAD`, at `b1844ab70` and at `c360b89cb`) | ⚠️ **6/9**, exit 1. V1, V2 and V9 fail on `lacks 'read receipts    : 17'` (see IR-A2) |
| C11 | `python3 evidence/admit.py --audit` (live) | completes: `STATUS: BINDING_FAILURES_PRESENT`, exit 3. 27 receipts (working tree at run time), 1 VOID (F0047), 9 divergent IDs, **no other failure line** |
| C12 | reviewer probes `probe_gates.py`, `probe2.py`, `probe_admit.py` | §6. ⚠️ The probe scripts live in the reviewer's session scratchpad. They are **not committed and not durable**. Their mutations are described in §6 so that they can be reproduced |

---

## 3 · Extended Increment 1a assessment

### A1 — Gate-definition pins

- **Algorithm** (`gate-runner.py` L389–L395): sha256 over `json.dumps(gate, sort_keys=True, ensure_ascii=False, separators=(",", ":"), default=str)` of the **parsed** gate mapping, first 16 hex. It hashes the whole mapping, not a chosen subset of fields.
- **Check** (L467–L473): unpinned ⇒ reason; `not isinstance(pin, str) or pin != pin_of(g)` ⇒ reason. Both are evaluated for every activated gate, inside `operability()`, **before** `evaluate()` (L703–L706).
- **Field coverage, measured.** Each field below was changed on a pinned copy without re-activation:

| Field changed | Evidence | Result |
|---|---|---|
| `id` | probe P-id | `GOVERNANCE_INOPERATIVE`: activated id names no gate (`validate_activation`, L377) |
| `status` | battery D08, D09, U6 | INOPERATIVE |
| `tier` | D10 | INOPERATIVE (`KOS-G-002: pin mismatch`) |
| `class` | D11 | INOPERATIVE |
| `pass_condition.check` | D05 | INOPERATIVE (`KOS-G-010: pin mismatch`) |
| `purpose` | probe P-purpose | INOPERATIVE (pin mismatch) |
| `name`, `pass_condition.rule`, `source.rule`, `failure.effect`, `stage`, `timing`, `human_decision_required`, an added field | probes P-name, P-rule, P-source, P-effect, P-stage, P-timing, P-hdr, P-newfield | INOPERATIVE (pin mismatch) in every case |

- **Edits that leave the parsed definition unchanged** (a comment, `active` → `"active"`, a duplicate YAML key whose last value is the original) do not change the pin, and the run stays CLEAR (probes P-comment, P-fmt, P-dupkey-same-final). This is correct behaviour: the definition the runner acts on is the same.

**Answer to the yardstick:** in every case tested, an activated gate's definition could **not** change without human re-activation while still appearing operational.

### A2 — Human re-activation

- The runner never writes activation. `--pin-lines` only prints (L688–L701). It refuses to print a pin for non-`active` or REVIEW/HUMAN gates (verified: `KOS-G-040`, `KOS-G-047` printed as `not pinnable`).
- After drift, the run is `GOVERNANCE_INOPERATIVE` (exit 3; door exit 3). It is **not** reinterpreted as FAIL/BLOCK or PASS/CLEAR. C6 shows the pre-1a runner turned the same mutations into BLOCK (D04–D17) or CLEAR (D09, U6).
- Re-activation round trip: U4 (`--pin-lines` output pasted) returns to A0's CLEAR.
- A malformed pin fails closed. An unquoted all-digit pin parses as an integer and gives a pin mismatch (probe, exit 3). Wrong pin: U2.
- **Live state:** `governance-state.yaml` holds five **bare** ids, so the live run is INOPERATIVE (C2, C3). The L0-DEC-16 implementation note says this is intended.

### A3 — Activation validity

| Condition | Where handled | Evidence | Outcome |
|---|---|---|---|
| activation file missing or unparseable | `_load_yaml` → `RuleBookError` | D02b | INOPERATIVE |
| `gates.yaml` unparseable | same | D01 | INOPERATIVE |
| activated id unknown | `validate_activation` | D03 | INOPERATIVE |
| duplicate gate id | `operability` L443 | D04 | INOPERATIVE |
| schema failure | `schema_violations` | D07 | INOPERATIVE |
| self-test < 100% | `operability` L450 | D06, U5 | INOPERATIVE |
| activated gate not `active` | L459 | D08, D09, D16, D17, U6 | INOPERATIVE |
| activated REVIEW/HUMAN | L463 (L0-DEC-14) | D14, D15 | INOPERATIVE |
| unpinned activation | L467 | D12, U1 | INOPERATIVE |
| pin mismatch | L470 | D05, D10, U2 | INOPERATIVE |
| runner crash | `__main__` handler, exit 3 | stray file in `fixtures/cases/` and bogus case dir (probe2): exit 3 | INOPERATIVE |
| runner absent | door | D18 | door exit 3 |
| activated gate whose check is not implemented (`status: active`) | `evaluate` L518 ⇒ ERROR | probe P-tierA-badcheck-reactivated | tier A: **BLOCK** (exit 1 → door 2), not INOPERATIVE. Tier B: **CLEAR** — see IR-G1 |

Every condition on the commission's list except "activated gate not implemented" prevents a trusted verdict via INOPERATIVE. "Not implemented" is reachable only after a human re-pins the changed definition. On tier A it fails closed as BLOCK. On tier B it does not (IR-G1).

### A4 — Regression evidence (gate side)

- The mechanism is genuine. Each case takes a `git archive` copy, pins the five first-group gates with the pre-mutation definitions, applies one mutation, and runs the runner (`--json`) **and** the door. Result, door exit, per-gate verdicts and a reason substring are all compared (`judge()`).
- RED → GREEN is reproduced: 26/60 against `9abd4ad95` (C6), 60/60 against the working tree at two HEADs (C4, C5). The plan's figures (26/60 RED, 60/60 GREEN) match.
- Coverage of the declared failure modes: negative controls (B-series BLOCK), self-test (D06, U5), pin drift (D05, D10, U2), activated/inoperative (D08–D17, U1, U6), missing input (B01–B09, U3), schema and duplicates (D04, D07).
- **Limits of the battery:**
  1. Its "independent" `pin_of` is a verbatim copy of the runner's algorithm. It is not an independent statement. U4 does exercise the runner's own `--pin-lines`.
  2. `id` and `purpose` drift are **not** in the battery. They were verified by this review's probes only.
  3. A0 expects CLEAR from research data at the chosen `--rev`, so A0 is state-coupled. It held at both HEADs reviewed.

### A5 — Scope

The instrument declares itself temporary and research-scoped (`gates.yaml` `catalogue.temporary: true`, `scope:`; `governance-state.yaml` `state.temporary: true`; guide 06 ⚠️ Scope). The 1a commit touches only governance-owned files, the guide and the plan. No platform wiring was found: the door is not in any `settings.json`, per `REVIEW-EVIDENCE.md` item 11, and nothing in the 1a diff changes that. **Within approved scope.**

### A6 — Silent-pass / false-assurance search

| Attempt | Result |
|---|---|
| changed gate, no re-activation | INOPERATIVE (A1). **No path found** |
| changed gate combined with a stage filter (`--stage PHASE_9`) | INOPERATIVE: operability runs before the filter (probe) |
| stale activation (old pin) | INOPERATIVE (D05, U2) |
| missing or empty required input | INCONCLUSIVE ⇒ BLOCK on activated tier A (B01–B09, U3) |
| malformed configuration | INOPERATIVE (D01, D02b, D07) |
| failed self-test | INOPERATIVE (U5, D06) |
| **activated tier-B gate returning ERROR/FAIL/INCONCLUSIVE** | ⚠️ **`STATUS: CLEAR`**, exit 0. The door prints *"CLEAR — every applicable ACTIVATED gate passed."* (probe2 tier-B-only) → **IR-G1** |
| **invalid or filtering `--stage`/`--timing` with five gates activated** | ⚠️ `NO_ACTIVE_GOVERNANCE_CONTROLS`, door exit 0. The door prints *"Research may continue because NO BLOCKING CONTROLS HAVE BEEN ACTIVATED."* → **IR-G2** |
| deleting one check's IC-7 case directory | self-test 48/48 = 100%, run CLEAR → **IR-G3** |

---

## 4 · `admit.py` repair assessment

**Diff scope** (`git diff e6087b615 dccc218e1 -- evidence/admit.py`): it adds `RECEIPT_FIELDS` and `classify_receipt_rows()`, and in `cmd_audit` it routes the receipt stream through the classifier and reports VOID counts and unrecognised rows. The stale, drift, registry-correspondence, `--resolve` and `--receipt` code is **unchanged**. The repair stays within L0-DEC-18's narrow scope: it handles the declared VOID shape and adds regression coverage. No general redesign was found.

**Classification rules** (L176–L201):

| Shape | Rule in code | Verified |
|---|---|---|
| valid receipt | all six `RECEIPT_FIELDS` present **and** no `status` key | extra fields are allowed and the checks still apply (X1). An added `status` ⇒ unrecognised (X2). A missing field ⇒ unrecognised (S4) |
| VOID | `status` starts with `"VOID"`, `correction_of` and `file_id` truthy, **neither** `sha256_at_read` **nor** `manifest_hash`, and an **earlier** receipt for the same `file_id` | live row 8 (F0047) is classified VOID (C11). V5 (no `correction_of`), V6 (VOID carrying receipt fields), V7 (orphan), O1 (VOID before its receipt) and O2 (lowercase `void`) are all ⇒ unrecognised |
| unrecognised | anything else, including non-dict rows | `42`, `null` and `[1,2]` ⇒ unrecognised, reported with line numbers, `failures += 1` (U1–U3) |

**A-1 condition (commission §10):**

- No crash on the VOID row: C9 V1, and C11 live.
- The VOID is **not** treated as a receipt. The receipt count excludes it (17 at `dccc218e1`), and no false stale finding appears (V1 forbids `DIFFERENT manifest hash`).
- The underlying receipt stays checked. The VOID does not remove any receipt from `receipts`, and a drifted receipt **with** a VOID for the same file is still reported (probe H1).
- Unrecognised row *shapes* are an explicit, visible binding failure, not an exception (V5–V8, U1–U3). ⚠️ **Exception:** a line that is not valid JSON, or a receipt whose `file_id` is unhashable, still aborts with a traceback (IR-A1).

**A-5 (`--receipt` writes a receipt for a file never opened by the researcher):**

| Question | Answer |
|---|---|
| affects the declared purpose of L0-DEC-18? | **No.** L0-DEC-18 is limited to the VOID-shape audit defect. A-5 concerns what a receipt *means*, in `cmd_receipt`, which the repair did not touch |
| correctness defect of the repair? | **No** |
| false assurance within this programme? | **Possible at the level of interpretation, not of the audit verdict.** `cmd_receipt` hashes the file itself, so a receipt proves admission against the manifest and the sha at receipt time. It does not prove reading. The module docstring (L30–L32) says a receipt is what proves a file was read, which overstates it. The F0047 VOID exists because of exactly this gap |
| classification | **outside the declared purpose; residual finding** (IR-A6). Already recorded as A-5 and left unchanged by design (guide 07 Pitfalls) |

---

## 5 · Regression evidence assessment

| Claim | Reviewer's verification | Verdict |
|---|---|---|
| control `--control rev --rev e6087b615` ⇒ 1/9 RED | C7: 1/9. V1 and V3–V9 show `Traceback`, exit 1, no STATUS. V2 (no VOID row) matches | **VERIFIED** (genuine run, not a copied assertion) |
| normal run ⇒ 9/9 GREEN | C9: 9/9 at `--rev dccc218e1` | **VERIFIED at `dccc218e1`** |
| normal run ⇒ 9/9 GREEN as documented (`admit_regression.py` with no `--rev`, guide 07) | C10: **6/9 at `b1844ab70` and at `c360b89cb`** | ⚠️ **NOT REPRODUCIBLE at current HEAD.** Cause: V1, V2 and V9 hard-code `"read receipts    : 17"`, and later research commits added receipts. **The failure is a stale expectation, not an instrument change**: `admit.py` is unchanged since `dccc218e1` (§2.3), and the other six cases pass. See IR-A2 |
| tests-first | `f5067fd31` (tests) precedes `dccc218e1` (repair). RED at `f5067fd31` = 1/9 (C8) | **VERIFIED** |
| mutations touch copies only | `snapshot()` uses `git archive` into `tempfile.mkdtemp`; `admit.py` resolves every path from its own location, which is inside the copy | **VERIFIED by reading.** The live workspace showed no change from the runs (the `git status` of the workspace before and after differed only by the concurrent research commit) |
| gate battery 60/60; RED 26/60 | C4, C5, C6 | **VERIFIED** |
| self-test 55/55 | C1 | **VERIFIED** |

---

## 6 · Adversarial test results

Each case below was run by this reviewer on a `git archive` copy of `dccc218e1`, with the repaired `admit.py` (probe_admit), unless noted.

| # | Commission case | Mutation | Outcome | Fail-closed? |
|---|---|---|---|---|
| 1 | hide a real receipt with a VOID row | H1: drift receipt F0043, add a declared VOID for F0043 | drift still reported; `BINDING_FAILURES_PRESENT` | **yes** |
| 1b | … by carrying receipt fields in the VOID | V6 (battery) | unrecognised ⇒ failure | **yes** |
| 1c | … by rewriting a receipt row into VOID shape | H2: a duplicate receipt stripped of `sha256_at_read`/`manifest_hash` and given a VOID status | accepted as VOID, and the rewritten row leaves the checks | the earlier genuine receipt stays checked. Rewriting a row has the same effect as **deleting** it, and `admit.py` does not guard against deletion (IR-A5, beyond purpose) |
| 2 | stale or drifted receipt appears clean | V3 (stale hash), V4/S1 (drift), S2 (`sha256_at_read: null`) | each reported | **yes** |
| 2b | receipt for a file not in the manifest | S3 | reported, but under the label *"file changed since reading"* | **yes**, mislabelled (IR-A4) |
| 3 | VOID pointing to no valid earlier receipt | V7 (orphan), O1 (VOID precedes its receipt) | unrecognised ⇒ failure | **yes** |
| 4 | unknown row shape | V8; U1 `42`; U2 `null`; U3 `[1,2]` | unrecognised ⇒ failure, with line numbers | **yes** |
| 4b | unparseable line | U4 `{"file_id": "F0001", ` | ⚠️ **`JSONDecodeError`, exit 1, no STATUS line** | no PASS and no BOUND, but an **ambiguous abort** (IR-A1) |
| 4c | unhashable `file_id` | U5 `file_id: ["F0001"]` | ⚠️ **`TypeError`, exit 1, no STATUS** | same (IR-A1) |
| 5 | extra fields on a genuine receipt | X1 | treated as a receipt; the checks apply | **yes** |
| 5b | extra `status` on a genuine receipt | X2 | unrecognised ⇒ failure | **yes** (strict) |
| — | registry file absent | Z3 (and battery V9) | ⚠️ **`STATUS: BOUND`, exit 0**: the correspondence section is skipped without a line | **no.** Pre-existing, outside the repair (IR-A3) |
| — | receipts file empty or absent | Z1, Z2 | reported (`0` / `NONE`); verdict driven by the other sections | the design does not require receipts |

Gate-side probes (probe_gates, probe2; copies of HEAD with the working-tree control plane):

| Mutation | Outcome |
|---|---|
| change `id` / `purpose` / `name` / `rule` / `source` / `effect` / `stage` / `timing` / `human_decision_required` / add a field | INOPERATIVE in every case |
| comment, re-quoting, or a duplicate key with the same final value | CLEAR (definition unchanged; correct) |
| duplicate activation entries, bare last | INOPERATIVE. Pinned last: CLEAR (last entry wins; fail-closed when the last entry is bare) |
| tier-A gate re-pinned with an unimplemented check | BLOCK (ERROR) |
| **only a tier-B gate with an unimplemented check, pinned and activated** | **`STATUS: CLEAR`**, "Applicable activated gates: 1" (IR-G1) |
| door `PHASE_9` / `EXPERIMENT` with five gates activated | `NO_ACTIVE_GOVERNANCE_CONTROLS`, door exit 0, "Research may continue because NO BLOCKING CONTROLS HAVE BEEN ACTIVATED" (IR-G2) |
| drift + door `PHASE_9` | INOPERATIVE, exit 3 |
| remove `fixtures/cases/no_dangling_refs/` | self-test 48/48, run CLEAR (IR-G3) |
| remove `fixtures/fail/_per_check/theory_doc_exists/` | self-test 54/55 ⇒ INOPERATIVE |
| stray file or unknown check name under `fixtures/cases/` | runner exit 3 (INOPERATIVE) |

---

## 7 · Findings

Findings are not ranked. Each is tagged **within purpose** or **beyond purpose**, and states whether it (1) affects the declared purpose, (2) creates false assurance within this programme, or (3) violates an approved constraint. Where a gap would need closing, the smallest change is named. **The decision belongs to L0.**

### 7.1 Within purpose

**IR-G1 — an activated tier-B gate that does not pass yields CLEAR.** `evaluate()` counts ERROR, FAIL and INCONCLUSIVE rows as applicable (L715), but only tier-A rows block (L539). With a tier-B gate activated and failing, the result is `CLEAR` and the door prints *"every applicable ACTIVATED gate passed"*, which is false.
- (1) Does not affect pin-drift detection: the path requires the human to pin the gate.
- (2) **Latent false assurance.** No tier-B gate is activated today; all five activated gates are tier A. The path becomes real the moment any tier-B gate is activated.
- (3) Violates no L0-DEC or IC as written. `gate-schema.yaml` defines tier B as *"advisory; reports, never blocks"*, but also defines PASS/CLEAR as *"the applicable automated precondition passed"*.
- Smallest change: either stop counting non-passing tier-B rows as applicable, or have `operability()` refuse tier-B activation, or change the door and runner wording to "every applicable activated **tier-A** gate passed; N advisory not passing".

**IR-G2 — a stage or timing filter that removes every activated gate gives "Research may continue", with a false reason.** `--stage`/`--timing` values are not validated. Operability is still enforced, so pin drift still gives INOPERATIVE. The runner comment L721–L726 documents the result token as intended. But the door text *"NO BLOCKING CONTROLS HAVE BEEN ACTIVATED"* is untrue when five are activated.
- (1) No. (2) No CLEAR or PASS token, but a permission sentence whose stated reason is false. (3) No.
- Smallest change: validate `--stage`/`--timing` against the schema enums (an unknown value ⇒ usage error ⇒ door 3), and word the door message as "no activated control applies to this stage/timing".

**IR-G3 — a check's IC-7 case directory can be deleted without detection.** The self-test iterates whatever case directories exist and has no minimum per check. Removing `cases/no_dangling_refs/` leaves 48/48 = 100% and a CLEAR run. Each check is still exercised by the shared `pass/` and `fail/` fixtures. IC-4's "missing fixture" traces to D06 (whole tree deleted, `GIA-DECISION-PACKAGE-01.md` L33, L101), which **is** enforced.
- Guide 06's sentence *"deleting fixtures … stops the instrument instead of passing silently"* is true of the whole tree and of `_per_check` directories, but not of `cases/<check>/`.
- (1) Weakens the self-test, the only guard named by R-2. (2) No false verdict on the research data. (3) Not a violation of IC-4 as traced. It is close to R-3 (IC-7 coverage), which the implementer has already disclosed.
- Smallest change: require a non-empty `cases/<check>/` for the check of every activated gate.

**IR-G4 — an activated, re-pinned gate with an unimplemented check reports BLOCK, not INOPERATIVE (tier A).** It fails closed, but an instrument defect is presented as a research BLOCK (IC-6 separates the two). (1) No. (2) No. (3) Arguably at odds with the spirit of IC-6. It is not listed in IC-4.
- Smallest change: an activated gate whose `check` is not in `CHECKS` ⇒ an `operability()` reason.

**IR-G5 — the battery's "independent" pin algorithm is a copy of the runner's.** A defect common to both would not show as INOPERATIVE on A0. U4 exercises the runner's `--pin-lines`. `id` and `purpose` drift are verified only by this review, not by the battery. (1) No. (2) No. (3) No. A residual in the test evidence.

**IR-G6 — the header of `governance-state.yaml` still teaches bare-id activation.** It tells the human to paste ids from `--list-eligible`, which also lists REVIEW/HUMAN gates. Following it produces INOPERATIVE, so the instruction fails closed. The file is human-owned, and L0-DEC-15 does not cover it. (1) No. (2) No. (3) No. Documentation only.

**IR-A1 — two crash paths remain in `--audit`.** A receipt line that is not valid JSON, and a receipt with an unhashable `file_id`, both abort with a traceback, exit 1 and no STATUS. The docstring of `classify_receipt_rows` and guide 07 say *"never a crash"*.
- (1) Outside L0-DEC-18's literal scope (the declared VOID shape), which the repair meets. It sits on the commission's yardstick "without crashing".
- (2) **No false assurance**: no BOUND, and a non-zero exit. It is an ambiguous abort of the same class as A-1.
- (3) No. It is pre-existing and was not introduced by the repair.
- Smallest change: parse each line inside `try` and route decode errors and non-string `file_id` values into `unknown`.

**IR-A2 — the admit regression is state-coupled and currently shows 6/9 at HEAD.** V1, V2 and V9 hard-code 17 receipts, so the documented command (guide 07, no `--rev`) no longer reproduces 9/9 after later research commits.
- (1) No: the instrument is unchanged, and the failure direction is a false RED, not a false GREEN.
- (2) No. (3) No.
- It does weaken the regression as standing evidence. The 9/9 claim holds **at `dccc218e1` only**.
- Smallest change: derive the expected count from the snapshot, or document `--rev dccc218e1` as the reference run.

**IR-A3 — an absent `FILE-REGISTRY.jsonl` silently skips the correspondence section and gives `BOUND`, exit 0.** Battery V9 relies on this as a test condition. This is pre-existing code, not touched by the repair.
- (1) Not within L0-DEC-18. (2) **Yes, potentially**: a missing input produces BOUND with no line saying the check was skipped. This bears on L0-DEC-19 item 6 ("divergence explicitly reported, never treated as PASS") if the registry were ever absent. It is not the current state: C11 reports the nine IDs.
- (3) No current violation.
- Smallest change: print and count "registry absent — correspondence not evaluated" as a failure.

**IR-A4 — a receipt for a `file_id` absent from the manifest is reported as "file changed since reading".** The row fails correctly but under the wrong label. (1) No. (2) No. (3) No.

### 7.2 Beyond purpose (residual limitations; not rejection grounds)

| ID | Limitation | Affects purpose? | False assurance in programme? | Violates constraint? |
|---|---|---|---|---|
| **R-1** | a pin detects drift but does not authenticate who pasted it (disclosed; guide 06) | no | no: drift detection is intact | no |
| **R-2** | runner code (`CHECKS`, `evaluate`) is not pinned; a weakened check is guarded only by the self-test (disclosed) | no | residual. IR-G3 narrows the self-test further | no |
| **IR-B1** | fixtures are not pinned; an `EXPECT.json` edited consistently with its inputs is undetectable | no | residual | no |
| **IR-B2** | `gate-schema.yaml` is not pinned; loosening an enum is not drift of a gate definition | no | residual | no |
| **IR-A5** | the receipt stream is not append-only protected. Deleting a row, or rewriting it into VOID shape, removes it from the checks | no: L0-DEC-18 is about shape handling | residual | no |
| **IR-A6 (= A-5)** | a receipt proves admission and the sha at receipt time, not reading. The `admit.py` module docstring (L30–L32) overstates this | no | possible at the interpretive level, not in the audit verdict | no |
| **IR-B3** | `load_activated()` keeps the last of duplicate activation entries | no | no: fail-closed when the last entry is bare | no |
| **IR-B4** | the door's `"${args[@]}"` under `set -u` fails on bash < 4.4 (not the local environment) | no | no: it would surface as door 2 or 3, not 0 | no |

---

## 8 · Acceptance decision — Extended Increment 1a

### **ACCEPT_WITH_FINDINGS**

The reviewed evidence supports ACCEPT_WITH_FINDINGS under the stated acceptance standard:

- **Declared purpose met.** In every case tested (19 field or identity mutations across the battery and this review's probes), a change to an activated gate's definition made without human re-activation gave `GOVERNANCE_INOPERATIVE` (runner exit 3, door exit 3). No path to PASS/CLEAR under drift was found.
- **No reachable false assurance in the reviewed or authorized configuration.** IR-G1 is a **latent** false-assurance path. It needs a human to activate a tier-B gate, and none is activated. It meets the second rejection criterion only if that happens.
- **No violated L0-DEC-12…17 or IC-1…IC-8** was found. IR-G4 sits at the edge of IC-6's intent and is fail-closed.
- **No unexplained correctness defect** was found. IR-G2 and IR-G3 are explained, documented or bounded behaviours.

## 9 · Acceptance decision — `admit.py` repair

### **ACCEPT_WITH_FINDINGS**

The reviewed evidence supports ACCEPT_WITH_FINDINGS under the stated acceptance standard:

- **Declared purpose met.** The declared VOID row is classified correctly. It is not counted as a receipt, the voided receipt stays checked, and every malformed or near-miss **row shape** tested became a visible binding failure with line numbers, not a crash and not a skip.
- **Scope held** (L0-DEC-18): the diff is limited to classification and reporting, and no evidence or registry file was modified.
- **Regression genuine**: RED 1/9 at `e6087b615` and `f5067fd31`; GREEN 9/9 at `dccc218e1`. ⚠️ It is **not** 9/9 at current HEAD, because of stale hard-coded counts (IR-A2).
- **No false assurance introduced by the repair.** IR-A1 is a loud abort, not a pass. IR-A3 is a silent-skip path that predates the repair and lies outside its scope.

---

## 10 · Unresolved questions (for L0; not answered here)

1. **IR-G1:** is tier-B activation intended to be possible? If it is, is CLEAR with a non-passing activated tier-B gate acceptable under L0-DEC-12's meaning of CLEAR?
2. **IR-A1:** does "unrecognised row" in L0-DEC-18 include lines that are not parseable as JSON? If it does, the repair is incomplete on that point. If not, the docstring and guide overstate it.
3. **IR-A2:** is the reference run for the admit regression `--rev dccc218e1`, or must it reproduce at HEAD?
4. **IR-A3:** is a silently skipped registry correspondence an acceptable input to the L0-DEC-19 item 6 determination?
5. **IR-G3:** does IC-4 "missing fixture" extend to per-check IC-7 case sets, or only to the fixture tree (as D06 tested)?
6. **Attestation:** the L0 record marks every L0-DEC as *UNVERIFIED (AI transcription)*. This review took the decision texts as recorded. **NOT VERIFIED** that they reflect the human's acts.
7. **L0-DEC-15 session identity:** that only the governance session modified the runner, `gates.yaml` and the door is **NOT VERIFIED**. All commits share one author identity, and session identity is not recorded in git.

---

## 11 · Explicit exclusions from this review

This review did **not** assess, and states no position on:

- KnowledgeOS theory, Candidate Theory v0.9, or the Theory Registry;
- the substantive findings of batch 1, batch 2 or batch 3. As a **fact only**: commits `b1844ab70` (batch 2) and `c360b89cb` (batch 3, committed during this review) exist on the branch. Their governance standing under L0-DEC-19 is **outside this review** and is neither certified nor judged here;
- the treatment of RC-H-04 or the nine divergent IDs F0031–F0038 and F0040. `admit.py --audit` **reports** them (C11), which is an evidence-state fact, not an instrument defect;
- 1b, C-5, or any research action;
- pin installation. No pin was written, and the live state stays unpinned and INOPERATIVE;
- L0-DEC-19 items (2)–(4). They are **not met** at review time: no L0 1a.7 acceptance, no pins, and the live runner is INOPERATIVE. This is a fact, not a recommendation;
- the `sed -i` deviation recorded in the `0952af01a` message (repository edit policy), except to note that it is disclosed.

---

## 12 · Reviewer conclusion

Both parts do what they were built to do, and the regression evidence behind that is genuine:

- **Extended 1a:** a drifted activated gate definition does not produce a trusted verdict. Every tested drift made the run `GOVERNANCE_INOPERATIVE`, and every broken control surface or failed self-test did the same.
- **`admit.py`:** the declared VOID row no longer crashes the audit and cannot hide a receipt. Every malformed row shape tested is reported as a failure.

The findings that matter most for L0's consideration are:

- **IR-G1:** a latent CLEAR if a tier-B gate is ever activated.
- **IR-A1:** two remaining crash paths on unparseable or ill-typed rows. They are loud, not passes.
- **IR-A2:** the admit regression's 9/9 reproduces only at `dccc218e1`, not at HEAD.
- **IR-A3:** a pre-existing silent skip of the registry correspondence when the registry is absent.

None of these meets a rejection criterion in the reviewed configuration.

> **The reviewed evidence supports ACCEPT_WITH_FINDINGS for extended Increment 1a and ACCEPT_WITH_FINDINGS for the `admit.py` repair, under the stated acceptance standard.** The governance decision (1a.7, and anything under L0-DEC-19) remains with L0.

*Traceability:* L0-DEC-12…19 (`governance/L0-DECISION-RECORD-01.md` L270–L305) · IC-1…IC-8 (`governance/GIA-DECISION-PACKAGE-01.md` L98–L105) · `audits/2026-09-23-GATE-INTEGRITY-AUDIT-02.md` · `audits/2026-09-23-ADMIT-AUDIT-CRASH-FINDING.md` · commits `4746093a8` `0952af01a` `f5067fd31` `dccc218e1` `74c789df4` · review HEADs `b1844ab70` → `c360b89cb` · commands C1–C12 (§2.4).
