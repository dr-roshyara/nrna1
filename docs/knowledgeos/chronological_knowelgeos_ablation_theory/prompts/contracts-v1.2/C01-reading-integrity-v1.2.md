# C01 — Reading integrity · v1.2 (delta)

**Base (in force except where changed):** `contracts-v1.1/C01-reading-integrity.md`, sha256 `18840bd6ec993b57e9e8a84321f804eb4ced18677e250d5dbff4a3455c1827bf`.

## Changes
1. **Approval (F-20).** The reader refuses every call (`PROTOCOL-NOT-APPROVED`, logged) unless `F-GOVERNANCE-LOG.md` carries an `APPROVED-FOR-EXECUTION` line for each governing document with its current sha256. Code: `f_read_source.py` → `f_integrity.require_approval`.
2. **Processing-order eligibility (F-13).** The reader serves an F-ID only if it is the next F-ID (the first non-AUDITED one in list order), or if it is already AUDITED. Checkpoint re-reads are therefore always of AUDITED files. Any other call is refused and logged as `NOT-ELIGIBLE-LOOK-AHEAD`.
3. **Auditor reads (C13 §3).** The independent auditor reads under its own run `FR-F####-9NN` and writes `AUDITOR-PAGE-DIGESTS.jsonl`; coverage is computed with `f_checks.coverage(fid, run, "AUDITOR-PAGE-DIGESTS.jsonl")`.
4. **READ-COMPLETE freezes `PAGE-DIGESTS.jsonl` (F-01).**
5. **The redirect refusal is not an integrity control (F-18).** It is kept (harmless), and the runbook's `| cat` defeats it. Integrity rests on pages + hashes + coverage (+ digests as supplementary semantic evidence). The pin to the committed S blob `b7e7fc856` is kept for reproducibility; at `52fbe3c3f` and HEAD the three reused definitions are identical (auditor's AST check).

## Tests
`Reading.*`, `Unapproved.*`, `Reading.test_F13_reader_refuses_look_ahead`, `IndependentAudit.test_F06_auditor_units_index_needs_auditor_read`.
