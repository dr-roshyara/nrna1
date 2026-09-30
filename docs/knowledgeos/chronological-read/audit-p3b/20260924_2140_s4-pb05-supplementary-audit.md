# S4 PB05 supplementary correction: mechanical check and §21 audit

| Field | Value |
|---|---|
| Run | S4-PILOT-R2-003 (supplements S4-PILOT-R2-002), batch PB05, label step-verify-programme |
| Output | `ledger-p3b-r2/S4-R22-SUPP/PB05/step-verify-correction.json`; reader log `ledger-p3b-r2/S4-PILOT-R2-003/READ-LOG.jsonl` (19 entries, 0 refused, only this label) |
| Authorization | G-LOG-0025, G-LOG-0026, G-LOG-0027 (F1) |

## Mechanical check (orchestrator, script only)
- All 12 files are in the reader log.
- Read proofs: 12/12 exact quotes of 15–28 words, located at 96–100% of the file length.
- All 22 quotes in the output match the file text.
- Nothing is written beyond the output and the log.

## Correction (agent)
- **Births:**

| Birth | Value | Change |
|---|---|---|
| lexical | BIRTH-UNRESOLVED-MTIME-ONLY[S1513] | corrected (was [S1441], chosen by MTIME) |
| conceptual | NOT-EVIDENCED-IN-CAPTURE | corrected (was [S1441]) |
| formal | BIRTH-UNRESOLVED-MTIME-ONLY[S1525] | unchanged |
| governance | BIRTH-UNRESOLVED-MTIME-ONLY[S1513] | unchanged |
| operational | NOT-EVIDENCED-IN-CAPTURE | unchanged |

- **Timeline:** 12 supplementary points.
  - 10 are CONFIRMED with an EXPLICIT date of 2026-08-30.
  - S1538 and S1544 are NOT-CONFIRMED (F1 form).
  - The 22 accepted points keep their order.
- **Unchanged:** absences, type_status, mathematical_status and primary_layer.

## §21 audit (independent, read-only)

| Item | Verdict |
|---|---|
| 1 Timeline (9/12 sampled) | PASS WITH NOTES. **N1**: S1551 belongs after S1544 and before S1566, not last; the agent compared local in-text times with a UTC mtime. It is provenance only, and no accepted point moves. **N2**: the S1516/S1513 order rests on mtime alone; the TV-F argument is overstated. The outcome is unaffected |
| 2 S1538 date | CORRECT: NOT-CONFIRMED under §14.2 rule 1, because the dates 08-25 to 08-28 belong to material the file cites |
| 3 Births | CORRECT: none of the 12 files carries an earlier dated birth. The S1535 and S1539 arguments are sound; S1516 is partly overstated. Lexical [S1513] and conceptual NOT-EVIDENCED are correct |
| 4 Operational | NOT-EVIDENCED-IN-CAPTURE is CORRECT: the P1 candidate is null. The rows are a gap in P1's candidate assignment, for a later P1-gap/register observation |
| 5 S1544 escalation | Substantive, judgment call. S1544's "fundamental historical hypothesis" plausibly supplies the `assumptions` dimension, which is currently GENUINELY-UNDEFINED-AFTER-CENSUS. Stage 1 missed it (term coverage). No §11.4 rule covers material found in a non-hit file when the census had hits. The escalation without a rewrite is protocol-correct. It does not block the lift |
| 6 Isolation / conformance | PASS |

**Recommendation (the human's decision): LIFT-WITH-NOTES.** The corrected births and the supplementary timeline stand. Notes N1 and N2 are recorded; the S1544 escalation goes to the human, with absences unchanged; the operational observation and S1544 go to the P1-gap backlog.

Method note: the resolver needs `GIT_DIR`/`GIT_WORK_TREE` when run from outside the repository, because the S3 module calls `git rev-parse`.
