# OB0018 decomposition-fidelity pilot (PX0018): result report — FROZEN RESULT UNDETERMINED

**Authorization:** G-LOG-0079. **Pre-registration:** `audit-p3b/20260926_1800_ob0018-decomposition-fidelity-preregistration.md` @ `773a40ffc5a7cf218aeea6f6399ba4636065533c` (sha256 `279e510a…77df2`). **Namespace:** PX0018 / `pilot-s5-decomp/ob0018/`. **Result file:** `pilot-s5-decomp/ob0018/OB0018-RESULT.json`.

**Mandatory scope statements:**
- "OB0018 can provide evidence about decomposition fidelity on the selected few-unit control label. It does not by itself establish population-wide equivalence of decomposition and single-context analysis."
- "A successful OB0018 result does not eliminate the need for the S5 reconstruction floor."

This result was not a success, and it changes nothing about the S5 floor (R19).

## Frozen result (§I, computed by `result`; not reinterpreted)

| | |
|---|---|
| **Feasibility (level 1)** | **FAILED**. Sole cause: a transcript-scan finding in unit PX0018-U02, a `p3b_read_source.py --help` invocation (no `--run`, `--batch` or S-id; it read nothing). The frozen scan classifies any reader invocation whose run and batch do not match the dispatch as a PROTOCOL-VIOLATION, and §G makes "no transcript finding in units" a level-1 condition. The tool's generic reason string ("a unit is incomplete, has hash failures or NOT-CONSUMED files") is imprecise: every unit is otherwise complete |
| **Outcomes** | A **UNDETERMINED** · B **UNDETERMINED** · C **UNDETERMINED** |
| **Decision** | **UNDETERMINED**. No architecture decision is licensed by the frozen rules |

## A. Execution integrity

- **Runs:** all 8 completed.
- **Model identity:** every transcript shows `claude-opus-5-5`, with no parse errors.
- **Stop conditions:** neither fired. The production ledger and state fingerprint is unchanged, and the transcript scans found no SEAL-BREACH.
- **Freeze stages:** all 8 are frozen (`authorization`, `plan`, `units`, `invariants`, `syntheses`, `blind`, `audit`, `unseal`). The key hash matched its blind-stage freeze, and the audit file matched its audit-stage freeze.

**Transcript findings:**

| Run | Finding |
|---|---|
| U02 | 1 (the `--help` call above) |
| all other 7 runs, including the auditor's blindness scan | 0 |

**Verifier QUARANTINE (recorded; needs your review):**
- **What:** arm A (1 count) and arm C (6 counts) each wrote **one S-id range** in free text: arm A in one register statement, arm C in one `status_basis` text.
- **Why it counts:** the verifier's quarantine rule (G-LOG-0045 item 3) counts hold-out ids implied inside a range. **No hold-out id was written explicitly, and there were 0 reads of hold-out files.**
- **Classification:** the verifier labels this "seal breach → P3B-ESC (counts only)". Within the pilot it is a level-2 failure. **Whether a P3B-ESC record is required is a human decision.**

## B. Engineering feasibility (FL-0)

- **Units:** U01–U03 completely read **34/34** required files (771,717 bytes). 0 missing pages, 0 hash failures, 0 refusals, 0 foreign log entries, one record per file, all CONSUMED.
- **Baseline:** S00 completely read all 34 files in **one context** (57 pages).
- **Arm reading discipline:** arms A and B made 0 reader calls. Arm C completely re-read all 5 mandatory adjacency files (70,594 of 300,000 bytes).

**Mechanically, decomposition and the single-context procedure both worked.** FL-0 fails formally only through the U02 classification.

## C. Extraction fidelity (level 2)

| Run | Verifier | Quote check |
|---|---|---|
| baseline | PASS | 71 exact, 0 misses |
| A | **FAIL**: QUARANTINE (range) + G-12 (a register pointer inside the layer-A object) | 80 exact, 0 misses |
| B | PASS | 75 exact + 1 whitespace-normalized, 0 misses |
| C | **FAIL**: QUARANTINE (range) | 80 exact, 0 misses |

**Every quote in every object was found verbatim in its source.** The level-2 failures are **contract-compliance** defects (S-id range notation; a pointer placed in the wrong layer), not extraction errors.

## D. Semantic fidelity (level 3; descriptive only, since the frozen result is UNDETERMINED)

**Blind audit:** 80 dispositions, 0 UNADJUDICATED, 0 PROTOCOL-VIOLATION. The key: A→P, B→Q, C→R.

| Arm | JUDGMENT-CALL | DECOMPOSED-UPHELD | BASELINE-UPHELD (fields) |
|---|---|---|---|
| A (records only) | 22 | 3 | 1: `escalations` (not a judgment field) |
| B (+ invariants) | 21 | 3 | 3: `births.conceptual`, `births.operational` (judgment fields), `escalations` |
| C (+ re-reads) | 21 | 3 | 3: the same as B |

**Upheld for all arms against the baseline:**
- **`births.lexical`:** the baseline's MOVED[S1618] breaks §11.2 (S1618 carries no row of this label) and §14.2 rule 2. **The single-context baseline is not ground truth.**
- **`adjacency.S1629`** and the S1629 `invariants` disposition.

## E. Reconstruction fidelity (level 4)

**All arms:**
- (i) 0 adjacency classes BASELINE-UPHELD (1 unequal adjacency; the arms were upheld);
- (ii) 0 residual conflicts;
- (iii) 0 residual calibration pairs;
- (iv) edge endpoint validity 100%.

Edge-set Jaccard vs baseline: R1 0.50, R2 1.00 (reported only; D-4).

## F. Arm comparison (counterfactual; NOT the result)

The frozen rules applied as if feasibility had held give **NO-ARM-SHOWN-FAITHFUL** (A, B and C all NOT-FAITHFUL). The reasons differ sharply:

| Arm | Why not faithful | Nature |
|---|---|---|
| A | level 2 only (range notation; G-12 pointer) | **compliance**; on judgment fields arm A had **0 BASELINE-UPHELD** |
| B | level 3: two judgment births | **synthesis judgment** |
| C | level 2 (range) + level 3: the same two births | compliance + synthesis judgment |

**On this label the added machinery of B and C did not improve fidelity. Records-only synthesis (A) matched or beat the baseline on every judgment field.** This is an observation, n = 1.

## G. Failure mechanisms observed

**The four brief mechanisms:**

| # | Mechanism | Observed? | Detail |
|---|---|---|---|
| 1 | cross-unit change classes | **not observed** | 1 unequal adjacency (S1629), upheld for the decomposed arms |
| 2 | disposition-vs-finding conflicts | **not observed** | 0 in unit records and in all arm objects |
| 3 | cross-unit calibration | **not observed** | 0 normalized-equal quote pairs across units |
| 4 | dependency-edge endpoints | **present in unit records, contained at synthesis** | 5 non-label targets flagged; every arm excluded them (endpoint validity 100%) |

**Two mechanisms newly observed** (hypotheses from one label):

- **5. Pair-context loss.**
  - **What happened:** every arm missed the baseline's VERDICT-EVIDENCE-CONFLICT escalation on pair RP1619. S1629 never cites the claimed source, so the pair verdict rests on the P1 row statement.
  - **Why:** reading units do not see P3a pair records, and arm C's frozen re-read rule targets adjacencies, not pair-evidence files. So even C, which re-read S1629, did not check it against the pair claim.
  - **Why it is decomposition-related:** single-context reading has the pair and the file in one view.
- **6. Out-of-row birth-candidate promotion.**
  - **What happened:** unit records surface birth candidates from files that carry **no row of the label** (S1618, S1691). Arms B and C promoted them to births (BASELINE-UPHELD). Arm A kept them as P1-gap records, correctly.
  - **Why it is decomposition-related:** units read files without row context, so their candidates need a row-membership filter at synthesis.

**Recommended in the brief, now evidenced:** a mechanical pre-verifier lint (S-id ranges, layer placement) is needed at synthesis. Both level-2 failures would have been caught before submission.

## H. Statistical interpretation

- **FL-0 / FL-1 only; FL-2 not addressed.** Formally the pilot yields **no FL-1 decision** (UNDETERMINED).
- **Descriptively**, "no decomposition-fidelity failure on **judgment fields** was observed for records-only synthesis (arm A) on this 3-unit label"; "the B/C additions introduced, rather than prevented, two judgment errors on this label".
- **With n = 1 these are observations, not rates.** The exact 95% upper bound on a per-label failure probability after one clean label is 0.95.
- **Agreement:** the auditor is same-family, so agreement is not independent corroboration.
- **Population inference:** none. No inference to 10–13-unit labels.

## I. Implications for S5 (for the human ruling; nothing changed)

- **The frozen result licenses no architecture choice.** The descriptive evidence suggests, as hypotheses to weigh with brief §H:
  - (a) option 1 (records-only synthesis) is not refuted by this label;
  - (b) a **synthesis-time pre-verifier lint** (ranges, G-12, quarantine) and a **row-membership filter for birth candidates** are cheap, mechanical safeguards;
  - (c) **pair-evidence checks** need pair context at synthesis: either pair records delivered to synthesis, or re-reads targeted at pair-evidence files rather than adjacencies.
- **These would be changes to a future design, never to this pilot.**
- **The S5 floor (396 batches, 1,975 labels) is unchanged.** S5 is not authorized.

## J. Limitations

- n = 1 label, 3 units, 4 adjacencies;
- same-family agents and auditor;
- partial blindness (content cues);
- scan false-negative classes (pre-registration audit m-1);
- the baseline is itself fallible (births.lexical);
- the arms' S-id range notation is a compliance, not a fidelity, signal;
- the U02 classification shows that **conservative integrity rules can void a run on a benign act**, the second such occurrence after E0 (G-LOG-0076). This is an operational observation, not promoted to methodology (ES-006.1).

## K. Unresolved questions (for human decision)

1. **Range quarantine:** do the S-id range quarantine hits require a P3B-ESC record, or are they a pilot-level compliance defect only?
2. **Following up the UNDETERMINED result:**
   - (i) accept the descriptive evidence and proceed to the S5 load-architecture ruling;
   - (ii) re-run the pilot unchanged in a new namespace. The U02 finding came from a helper invocation; its recurrence probability is unknown;
   - (iii) a narrow scan-rule clarification (a reader invocation without an S-id is not a read) before any re-run.
3. **Mechanisms 5 and 6:** should they enter the S5 load-architecture ruling as design requirements?

**OB0018 EXECUTION COMPLETE — RESULT SEALED — S5 NOT AUTHORIZED**
