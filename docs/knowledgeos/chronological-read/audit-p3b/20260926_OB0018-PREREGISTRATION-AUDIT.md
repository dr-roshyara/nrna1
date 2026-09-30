# OB0018 pre-registration: independent audit and MATERIAL-only repair record

**Audited freeze:** `6f67392b3fcc77e712a00f143dbd63fdc6878da6`.

**Auditor:**
- an independent agent with no context from the drafting session;
- read-only; no corpus content read (the slice was only hashed);
- adversarial brief covering items 1–10 of the human instruction (research question, bounds, blindness, provenance, namespace, hold-out, R19, ML, statistical scope, executability).

**Stopping rule** (G-LOG-0078; pre-registration §R.1): exactly one audit; repair only MATERIAL findings; no new audit cycle unless the human decides otherwise.

## 1. What the audit verified without defect

- **Frozen hashes:** all frozen hashes match; every artifact and dependency equals the commit.
- **Tests:** selftest PASS; 45 tests OK.
- **Design:** the plan is internally consistent (34 = 31 + 8 − 5; units 4/12/18; 4 adjacencies). H0/H1/H2, the arms and the decision mapping match brief §D.
- **Outcome logic:** `decision()` never maps INVALID or UNDETERMINED to a success.
- **Namespace:** every run id matches the reader's pilot pattern, so logs go to `pilot-s5-decomp/`; there is no PX0018 collision; `validate` writes only to temporary copies.
- **Statistics:** 1 − 0.05^(1/1) = 0.95 and n ≥ ln 0.05 / ln 0.8 = 13.43 → 14 are correct, and the mandatory scope sentences are present.
- **Invariants:** no S5 substitution; no learned model; no hold-out content routed to agents by design.

**Verdict:** NEEDS-REVISION. The design is sound; 8 MATERIAL implementation and contract-wiring defects.

## 2. MATERIAL findings and repairs (all verified by regression tests)

| ID | Finding (audit) | Repair | Regression test(s) |
|---|---|---|---|
| **M-1** | the auditor writes `pilot-s5-decomp/PX0018-A01/AUDIT.jsonl`, but `result`/`freeze` read `pilot-s5-decomp/ob0018/AUDIT.jsonl`, and a missing file read as `[]` would make every arm UNDETERMINED | freeze and result read the auditor's own directory (`stage_path('@PX0018-A01/…')`); `freeze` **refuses** any missing listed file; `result` verifies the audit file against its frozen hash | `M1AuditPath` (3) |
| **M-2** | stage-2 dispositions were collapsed per (file, dimension), which is order-dependent and could mask or invent differences in the judgment comparison, metric (ii) and metric (iii) | keyed per distinct hit: (source, hit kind, hit key, term index, dimension). Field names carry the key. Metric (iii) uses an explicit file-level aggregate (FOUND if any hit is FOUND) | `M2PerHitKey` (5) |
| **M-3** | arm C escalating leftover conflicts with **LOAD** fails the production verifier on a non-hub label, a schema clash producing a false NOT-FAITHFUL and revealing arm C | contract: reason **OTHER**, detail "re-read budget exhausted: <S-id>" | `M3NoLoadEscalation` |
| **M-4** | a PROTOCOL-VIOLATION on an adjacency row never reached the outcome rule | `arm_rows()` adds **every** unequal adjacency row with its actual disposition (judgment field) | `M4AdjacencyViolation` (3) |
| **M-5** | arm C's mandatory adjacency re-reads were not enforced, so a C result could credit re-reads that never happened | `arm_c_skipped()` → a violation (arm C INVALID) if any adjacency file is not completely re-read | `M5ArmCMandatory` |
| **M-6** | reads under a foreign run or batch were invisible, and a production run id could write the production ledger undetected | (a) the scan flags reader calls with a foreign `--run`/`--batch` or a production run id, and any reader call in arms A/B; (b) `coverage` counts every log entry, and foreign entries fail level 1 (units, baseline) or invalidate an arm; (c) a production-ledger and state **fingerprint** is taken at authorization and compared at `check`; a change stops the pilot | `M6ReaderDiscipline` (6) |
| **M-7** | blindness was not sealed: the key and the unblinded arm files were reachable, and access went undetected | enforced **by detection**: the contract forbids the arm directories, key and orchestrator files; the auditor's scan flags them, which invalidates the audit. The §L disclosure is extended (no on-disk location is unreachable for a shell agent; a residual wildcard route is stated) | `M7BlindnessDetection` (3) |
| **M-8** | quote text inside shell commands could raise false scan alarms, including a false SEAL-BREACH | contract: outputs are written **only with the Write/Edit tool**, never quote text in a shell command. A match is then a genuine contract breach | `M8WriteToolOnly` |

**Consistency correction found during repair** (the implementer's own, recorded for transparency):
- **Before:** `result` required the baseline to read **all** of R(L) completely.
- **Why that was wrong:** the frozen §G defines baseline completeness by the **baseline's own verifier READ-COVERAGE** (the production procedure may use Stage 2A/2B).
- **Fix:** the code now matches the frozen text. This is not a design change.

**Interface updates to three pre-audit tests of this pilot** (not historical frozen suites): 5-part disposition keys, the 4-field scan result, and reader calls now required to carry `--batch PX0018`.

**Suite after repair:** **68 tests OK**; selftest PASS.

## 3. MINOR findings: dispositions (not repaired, per the stopping rule)

| ID | Finding | Disposition |
|---|---|---|
| m-1 | scan false negatives (glob, `git grep`/`log -p`, `cat-file` by blob sha, direct resolver import) | **DISCLOSED here** (D-2 accepted outcome-based integrity). Hold-out files remain refused by the resolver; raw-git hold-out access by glob is not detected |
| m-2 | `result` re-hashes only the key and (now) the audit file, not every frozen file; stage order not enforced | **ACCEPTED**: detectable through the per-stage commits; `freeze` now refuses missing files (M-1) |
| m-3 | a run missing from `DISPATCH.json` goes unscanned; parse errors ignored | **ACCEPTED**: the orchestrator records all 8 runs (runbook); parse errors are recorded in `SCAN*.json` for the report |
| m-4 | the auditor's 600 KB budget is not enforced | **ACCEPTED**: the budget protects the auditor's context, not validity; reported in the result |
| m-5 | prompts not in a freeze stage; first-prompt equality unchecked | **ACCEPTED**: the prompts are committed and hashed in the pre-registration table; D-2 dropped the V1.x first-prompt equality regime |
| m-6 | the result paraphrased the scope sentences | **addressed in passing**: `result` now also carries the verbatim sentences (`SCOPE_STATEMENTS`); no logic change |
| m-7 | "rule of three ≈ 3/n" at n = 14 gives 0.214 | **NOTED**: the exact claim n ≥ 14 (bound 0.193) governs; the parenthetical is a heuristic |
| m-8 | unit-level checks missing (record count, files outside unit, unit-record quotes) | **ACCEPTED/DISCLOSED**: `files_outside_unit` and record counts are reported in `CHECK-UNITS.json`; unit-record quotes are checked indirectly through each arm object's quote check |
| m-9 | arm B "every flagged item resolved" is unchecked; arm A inherits unit conflicts | **DISCLOSED**: metric (ii) measures the residual in the arm object directly. For arm A, (ii) by construction measures the conflicts inherited from the units: that is the arm-A hypothesis |
| m-10 | `result`/`check`/`validate` are untested end to end | **ACCEPTED**: the decision logic is factored into tested pure functions (`outcome_of`, `decision`, `arm_rows`, `arm_c_skipped`, `transcript_scan`) |

## 4. State

- **Repaired freeze:** see the commit carrying this record. The pre-registration hash table is updated.
- **Status:** NOT AUTHORIZED · NOT EXECUTED · no corpus read.
- **Production batch OB0018:** PREPARED and untouched.
- **Next:** a human authorization of the repaired freeze. No further audit cycle unless the human decides otherwise.

**OB0018 PRE-REGISTRATION READY — AUDIT COMPLETE — NO EXECUTION**
