# Task (frozen): draft the Empirical Theory Record (R3), PROVISIONAL pending R2

Use ONLY the files in this directory. No commands, no web. **Consolidate; do not create new evidence, codings or interpretations.**

## Inputs

| File | Content |
|---|---|
| `SCHEMA-r2-Aprime.md` | the observation schema (A′) |
| `HYPOTHESES.md` | H-D / H-B, frozen |
| `RECORDS.json`, `CODING.json`, `CODING-REVIEW.json` | the A′ span records, their same-family coding, and its review |
| `SUPPORT-RECONCILIATION.md` | support analysis (1ay) |
| `CONFORMANCE-RECONCILIATION.md`, `CONFORMANCE-ANSWER.md`, `CONFORMANCE-REVIEW.md` | the WP-4B / C2 conformance analysis (1ba) |
| `STATE.md`, `IDENTIFIABILITY.json`, `CONFORMANCE-CASE.json` | the reviewed state and identifiability (1bb) |
| `PROTOCOL-A-RECONCILIATION.md`, `PROTOCOL-B-RECONCILIATION.md`, `READINESS-RECONCILIATION.md` | sealed captures and readiness |
| `H5-ADOPTED.md` | the adopted H5 result and its gap list |
| `CONTINUOUS-REPORT.md` | the compact chronological task report (context only, never a source of facts) |

## Write EMPIRICAL-THEORY-RECORD.md (at most ~4 pages) and EMPIRICAL-THEORY-RECORD.json

### Sections
- **A Observed** (L0): what sources directly record.
- **B Reconstructed** (L1): established by the disciplined method (coding, captures).
- **C Regularities:** **only** patterns that recur across **independent decision clusters**. State the clusters and the count. If none qualify, say so and leave C empty or near-empty. **Prefer A/B over C.**
- **D Non-identifiability results:** H5 with its scope qualifier and its waiver, D1 (a necessary, not sufficient, condition), and P1–P8.
- **E Counterexamples / anomalies:** WP-4B RED, R-89/WP-4C-1, R-94, P4. Give each one's **direction**, n, confound and alternative reading, symmetrically.
- **F Explicit unknowns:** including the H5 §3 gaps.
- **G Scope and provenance:** the corpus boundary, same-family status, and R-SELF.
- **H What a later formal theory (R4) may and may not assume:** derived strictly from A–G. Hypotheses are never axioms.

### Fields for every item
- `id`
- `statement`
- `level` (L0 / L1 / L2)
- `section`
- `sources` (file + location)
- `R2_DEPENDENT` (yes / no), with one-line reasoning
- `confidence` (as stated in the source, never raised)

### Prohibitions
- No item stronger than its source.
- No "did not occur" from absence: use NOT-EVIDENCED-IN-CAPTURE.
- No norm → act inference.
- Recording time ≠ decision time ≠ entry into force.
- No DDD, architecture or formal model.
