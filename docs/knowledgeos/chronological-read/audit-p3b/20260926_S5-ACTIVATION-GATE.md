# S5 activation gate: from the repaired revision 5 to an authorized S5 execution (PROPOSAL)

| | |
|---|---|
| **Kind** | EP-01 planning artifact. ⚠ authority: generated. **A sequence, not an authorization.** No step below is performed by this document |
| **State at writing** | revision 5 **NOT ACTIVATED** (manifest contract revision 3) · the audit is **NEEDS-REVISION** and **unresolved** · S5 execution **NOT AUTHORIZED** · H-19 **SEALED** · S5c **PROHIBITED** · floor 0 of 396 batches |
| **Supersedes** | nothing. It refines the addendum's activation checklist, the revision-5 record §3 and Stages 1–2 of `.claude/S-SERIES-TODO.md` into one ordered sequence |
| **Companions** | `20260926_R5-MATERIAL-FINDINGS-REPAIR-PLAN.md` · `20260926_EP-01-STATISTICAL-SPECIFICATION.md` |

**Kinds of step, never merged:**
- **H**: a human authorization or decision, recorded as a G-LOG entry.
- **E**: an engineering gate (tests, verifiers, guards), with no corpus content.
- **R**: a corpus-reading action, a checker read through the seal-aware resolver, and only under a prior H.
- **A**: production activation, a change to production state.

## The sequence

| # | Step | Kind | Precondition | Output / evidence | Blocks |
|---|---|---|---|---|---|
| **1** | **Approve the R5 repair plan** and EP-01, and answer Q-1…Q-6 and Q-S1…Q-S5 | **H** | this proposal | a G-LOG entry naming the approved plan hashes and the answers | 2 |
| **2** | **Implement** the approved repair slice: RED tests, then GREEN, in slice order W2 → W1 → rules → W3; addendum and runbook amendments; MINORs per the approval; the EP-01 code consequences (R5-16) | E (implementation) | 1 | commits; an implementation record in `audit-p3b/` with file hashes | 3 |
| **3** | **Tests and gates:** all suites green; the auditor's `attacks.py` and `full_path.py` **re-run unmodified** (every attack FAIL, BASE PASS); rev none/1–4 byte-identical; `prepare --check` IDENTICAL; H-19 SEALED, grep 0; ledger `4fde15fc…05e5`, `P3B-STATE.json` `db52ac7a…c21a`, manifest revision 3 unchanged | **E** | 2 | the gate table in the implementation record | 4 |
| **4** | **Decide whether a further independent audit is required** (the stopping rule: never automatic) | **H** | 3 | a G-LOG entry. If yes: one audit, and a MATERIAL finding returns to step 1 | 5 |
| **5** | **Authorize the binary read:** a checker read of the 12 required binary files' bytes through the seal-aware resolver, for `binary_preclassify` only | **H** | 4 closed clean | a G-LOG entry naming the 12 S-ids and the command | 6 |
| **6** | **Binary pre-classification run** | **R** | 5 | per file: format/signature, the region of each hit offset, and a candidate (FALSE-HIT / HUMAN-REVIEW). No content printed; the resolver refuses hold-out files | 7 |
| **7** | **12 per-file binary decisions**: FALSE-HIT / NOT-CONSUMED-ESCALATED / EXTRACT. EXTRACT only if `extraction_permitted` holds, including the archive member seal check | **H** | 6 | 12 decision records (governed artifacts). **If any EXTRACT:** the EP-01 conditional census stratum applies | 8 |
| **8** | **Freeze the statistical parameters and seed:** frame hash, stratum assignment, sizes, α, decision fields, human subsample, auditor instrument per stratum (Q-S1); seed from the CSPRNG; the drawn label list hashed and committed | **H** (the freeze act) + E (the draw) | 7, and the step-2 fixes to `draw_audit_sample` | a parameter file + sample file (hashes in the G-LOG) | 9 |
| **9** | **Revision-5 activation:** regenerate the manifest with the revision-5 contract hash (**as amended in step 2**); write the **frozen production plan** (per label: path, files, rows, sizes, packing order, units, run ids) and record its `plan_sha256` in the activation record | **A** | 8 | the new manifest; the plan file + its hash; the activation record | 10 |
| **10** | **Production verification:** `prepare --check` against the new manifest; the plan re-derived from metadata equals the frozen plan (PLAN_BOUND, PATH_DERIVED); floor 396 / 1,975 unchanged; guards; ledger fingerprint unchanged; no revision-5 run exists yet | **E** | 9 | the verification table | 11 |
| **11** | **Separate S5 EXECUTION AUTHORIZATION** | **H** | 10 | a G-LOG entry: scope (batches), the runbook hash, the stop conditions, the audit timing (before P4) | S5 dispatch |

## Grouped by kind

| Kind | Steps |
|---|---|
| **Human authorization / decision** | 1 · 4 · 5 · 7 · 8 (freeze) · 11 |
| **Engineering gate** | 2 · 3 · 8 (draw) · 10 |
| **Corpus-reading action** | 6 (only this one before S5, and only after 5) |
| **Production activation** | 9 |

## Invariants of the sequence

- **Order is strict.** No step starts before its precondition's evidence exists. In particular:
  - **step 8 (seed) precedes step 9 (activation)**, and both precede any S5 output (EP-01 §4);
  - **step 6 is the only corpus read before step 11.**
- **Any MATERIAL finding** at step 3 or step 4 **returns the sequence to step 1**. It is never repaired inside a later step.
- **No step changes:** v3.5, P3b v1.7 outside §26, the revision-3 contract, the historical ledgers, the H-19 seal, S5c's PROHIBITED status, or the floor (396 / 1,975).
- **Activation (9) makes nothing executable by itself.** S5 dispatch needs step 11.
- **ML** appears at no step.

## After step 11 (for orientation only; not part of this gate)

S5 per the runbook (396 batches) → the probability audit on the frozen sample, completed before P4 → S5a / S5b → the P3b terminal predicate (§23) → `30-RECONCILIATION.md` → P4.

**Traceability:** addendum "Activation checklist" · revision-5 record §3 · audit §1–§8 · `.claude/S-SERIES-TODO.md` Stages 1–3 · runbook steps 1, 13, 14, 16.
