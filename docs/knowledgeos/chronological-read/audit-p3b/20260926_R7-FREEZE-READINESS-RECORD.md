# R7 design-freeze readiness check: record

| | |
|---|---|
| **Kind** | ONE final, bounded design-freeze readiness check. ⚠ authority: generated. **Not an audit, not an implementation, not a redesign** |
| **Authority** | the human instruction "FINAL, BOUNDED DESIGN-FREEZE READINESS CHECK" (2026-09-26), under G-LOG-0085 / G-LOG-0086 |
| **Result** | **DESIGN FREEZE BLOCKED: one genuine logical inconsistency (FR-1), not repaired**, as instructed. The requested PASS-terminology clarification (DR-15) and a W1 reason-code clarification (DR-16) are applied. 57/57 static checks pass |
| **Independence limit** | the check is performed by the author of the R7 design. The static checker tests text and table consistency. It cannot prove semantic adequacy |

## 1. Checks performed

**Design-only static checker** `r7_freeze_check.py` (session scratchpad; sha256 `ee16b834…572e`). It is a superset of the design-repair checker. Output sha256 `db04566d…1153`.

**Result: 57/57 PASS** (registry 35 rows, consumers 8, tests 83).

| Area | Checks | Result |
|---|---|---|
| Registry, coverage, F2 (carried over) | R1–R9, C1–C2, F2 | PASS |
| Tests | T1–T5; ≥ 1 negative per invariant group (now including **V**); positive boundary controls; the 33 mandated cases mapped; **T72–T83 present** (V12) | PASS |
| **PASS terminology (item 1)** | V1 closed verdict vocabulary in all 83 expectations · V2 **no bare PASS** · V3 BATCH-PASS / STATISTICS-VALID / PROGRAM-ACCEPTED defined · V4 strong-Kleene composition · V5 failure tags = predicate letters, no stale tags · V6 schema written Σ (no clash with S) · V7 governance acceptance is a human act · V9 the verifier emits only a batch verdict · V10 any undefined deliverable component ⇒ NOT-ESTIMABLE · V11 archive absent ⇒ U, mismatch ⇒ F | PASS |
| **Logical model (item 2)** | V8 observation ≠ evidence ≠ reconstruction ≠ statistics ≠ governance, stated; S5 execution truth ≠ semantic truth (carried over) | PASS |
| **PF-1/PF-2 (item 3)** | P1 one-directional, no converse, no equality · P2 `later_*` needs ESTABLISHED · P3 STEP-NUMBER and UNORDERED-BLOCK position never precedence; S3 array order never precedence | PASS |
| **PF-3/PF-4 (item 4)** | P4 discovery cannot detect absence; unknown discriminator fails; F2 machine-checked | PASS |
| **PF-5/W8 (item 5)** | P5 the allowed set of three; everything else fails; cat, grep, python, redirection, Read, mkdir covered; SEAL stop | PASS **as text, but see FR-1** |
| **PF-6/W1 (item 6)** | W1 ten distinct reason codes; truncation mapped to observables; W1b no first/last-count heuristic | PASS (after DR-16) |
| **PF-7/W7a (item 7)** | P6 byte-identical inclusion | PASS |
| **PF-8 (item 8)** | P8 exact offset, witnessed interval, ≥ 1 occurrence, offset map, same run, verifier computes; S7 no minimum quote length | PASS |
| **DDD (item 9)** | DDD1 plan B0…B9 · DDD2 acyclic; the verifier (B6) depends only on B1–B4 · DDD3 the verifier is composition only · D1 the context graph is acyclic | PASS |
| **Withdrawn rules (item 11)** | no `RANGE6` except as replaced; R6-PROVENANCE only as dropped or declared; no R5 run grammar; no V1.2.4 dependency | PASS |

The first DDD2 run failed on a **checker** defect: the "B1–B4" range notation was not expanded. The checker was fixed; no design change was involved.

## 2. Clarifications applied (the design gains no new scope)

**DR-15, layered verdicts** (§0, §8, §10; FD-10):

| Layer | Definition / values |
|---|---|
| Batch | **BATCH-PASS := U ∧ W ∧ E ∧ R.** Strong-Kleene, three-valued: BATCH-FAIL if any F; otherwise BATCH-UNDETERMINED if any U; otherwise BATCH-PASS |
| Statistics | **STATISTICS-VALID := S**, with the values VALID / NOT-ESTIMABLE / REJECTED. Any undefined component of the frozen deliverable (τ̂, V̂, the CI) ⇒ NOT-ESTIMABLE |
| Programme | **PROGRAM-ACCEPTED := ∀b BATCH-PASS(b) ∧ STATISTICS-VALID.** A mechanical precondition for, never the act of, governance acceptance |

- There is no bare PASS. The verifier emits only a batch verdict. The failure tags are `R7-U/W/E/R/S`, and the object schema is renamed Σ.
- New tests T77–T83: partial layers never imply acceptance; UNDETERMINED is neither PASS nor FAIL; F dominates U; the static output schema; an archive mismatch is F.
- **Why strong-Kleene is safe.** Deleting the witness archive can only move a batch from PASS to UNDETERMINED. It never produces PASS, and PROGRAM-ACCEPTED requires PASS for every batch. So U cannot be used to launder a failure into acceptance.
- **One interpretive choice is stated for the freeze, not left implicit.** With n_h = 1, the point estimate is defined but V̂ and the CI are not. The result class is set to **STATISTICS-NOT-ESTIMABLE**, with the point estimate reported as information only. This is the fail-closed reading of HD-6 ("variance/CI NOT-ESTIMABLE"). The human may overrule it at the freeze.

**DR-16, W1 reason codes** (§5.2), with ten distinct codes:
- `W1-ZERO-TOOL`, `W1-DECLINED`;
- `W1-TRANSCRIPT-MISSING`, `W1-TRANSCRIPT-DUPLICATE`;
- `W1-HARNESS-COUNT-MISSING`, `W1-COUNT-MISMATCH`;
- `W1-CHAIN`, `W1-PARSE`;
- `W1-NOTIFICATION-ABSENT`, `W1-NOTIFICATION-MULTIPLE`;
- plus `W4-DENIED` and `W3-*`.

**A logical note:** "truncated transcript" is a *cause*, not an observable state. A clean truncation is observed as COUNT-MISMATCH; a mid-line truncation as PARSE. The codes make item 6's "distinct failure states" machine-testable.

## 3. Genuine logical inconsistency found: DESIGN FREEZE BLOCKED

### FR-1: W8 execution-capability closure is inconsistent with the agents' required input channel

| | |
|---|---|
| **Invariant affected** | W8 (§5.5; FD-8): AllowedToolCalls(run) = {canonical reader · Write to a run-owned path · handback}; "Read, Grep, Glob … Agent spawning" are violations |
| **Conflicting part of the same design** | the carried-over runbook and the agents' task definitions. Runbook step 11 (carried over through R6/R7): *records-only synthesis* "Inputs: the revision-3 contract, the addendum, the slice, **all unit records**, the plan". Units and SINGLE runs need their slice and the plan. Established practice gives agents these inputs **as files to Read**: the OB0018 prompt says "Read completely … the pilot contract … the frozen plan", and the V1.2.x `audit_calls` allowed Read of `inputs_of(run)` + `outputs_of(run)` + the agent's **persisted tool outputs** |
| **Minimal counterexample** | a DECOMPOSED label L02 with units U01 and U02. Synthesis run `OB####-R7-L02S` must read `ledger-p3b-r2/OB####-R7-L02U01/file-reading-records.jsonl` to synthesize. Its only permitted capabilities are Write and handback. **(a)** If it Reads the file, W8 ⇒ BATCH-FAIL `R7-W`. **(b)** If it does not, it cannot perform records-only synthesis. So every faithful DECOMPOSED synthesis is BATCH-FAIL, and there is no execution path to BATCH-PASS: a systemic false FAIL. The same holds for any unit that must Read its slice, and for any agent whose reader output the harness persisted to a tool-results file (the agent would have to Read it to see the page) |
| **Why this is an inconsistency, not a preference** | two requirements of the frozen-candidate design cannot both be satisfied by any execution: the task demands the input, and W8 forbids every channel to it. The contract does not specify inline delivery as an alternative |
| **Smallest repair (not applied)** | **Option A (recommended; it follows the V1.2.x precedent):** add a fourth W8 capability, **Read of I(run)**. I(run) is an enumerated, per-run input set, owned by Universe and frozen at dispatch: the rev3 contract, the R7 addendum, the label's slice, the plan file; for synthesis, the label's unit record files; and the agent's own persisted tool-output files. I(run) never contains a corpus or hold-out path (hold-out-safe by construction of prepare/slices). Each Read is a witnessed `read-input` event with the file's sha256. Any Read ∉ I(run) is `R7-W`, and a SEAL stop if hold-out-bearing. **Option B:** inline delivery. The orchestrator puts every input into the dispatch prompt (hashes recorded in the dispatch witness record) and Read stays forbidden. **Weakness:** prompt size, and it still requires a guarantee that no tool output is ever persisted |
| **FD affected** | **FD-8** (the W8 allowed set) changes. It needs one added test pair: a Read ∈ I(run) → BATCH-PASS path; a Read ∉ I(run) → BATCH-FAIL; plus a persisted-output read. Universe gains I(run) as an owned set |

No other genuine inconsistency was found.

## 4. The two human decisions (reported, not decided)

| | Decision | Status |
|---|---|---|
| **A** | **FD-1′b: where does EXTENDS belong?** Current proposal: `later_refinement` (alternatives: `later_support`; either) | **HUMAN SEMANTIC DECISION REQUIRED** |
| **B** | **FD-4′: the durable transcript archive location.** Requirements: outside the git repository; durable; retained for re-verification; location explicitly frozen; digest-checked against the committed freeze; preservation, not trust | **HUMAN OPERATIONAL DECISION REQUIRED** |

**Plus, because of FR-1:** choose Option A or B for the W8 input channel (FD-8′).

## 5. Final design state

**R7 v2.1: DESIGN-REPAIRED + READINESS-CLARIFIED (DR-15, DR-16) · DESIGN FREEZE BLOCKED by FR-1 · NOT IMPLEMENTED · NOT ACTIVATED.**

Unblocking needs a human decision on the FR-1 option, then a minimal repair of §5.5 plus its tests, then the design freeze (including decisions A and B).

## 6. Safety

| Check | Result |
|---|---|
| corpus read / resolver call / agent dispatch / S5 / binary pre-classification | none |
| H-19 | SEALED (HS-3d32dd44d162); guard 58 files, 0 violations |
| production ledger (fingerprint `4fde15fc42513725…`), P3B-STATE (`db52ac7a…`), manifest (`1b383fbc…`, revision 3) | unchanged |
| R6, F-Series, application code, Master Protocol | unchanged |
| repository `git status`, before vs after this check | differs only in the R7 addendum (+ this record, TODO, session log) |

## 7. Files changed

| File | sha256 |
|---|---|
| `prompts/20260926_2400_p3b-agent-contract-r7-addendum.md` (v2.1) | `f7c6c1377860104215dac9ddff7defcfdb2b9980c6e29469f7bcee99f8b5ad6a` (v2: `e0212573…e68d6`) |
| `audit-p3b/20260926_R7-FREEZE-READINESS-RECORD.md` | this file |
| `.claude/S-SERIES-TODO.md`, `.claude/sessions/2026-09-26.md` | state lines only |

The ADR and the plan are unchanged. The plan's B0–B9 and the ADR's W-list remain consistent with v2.1; FR-1's repair would add I(run) to Universe (B1) and one ADR line.
