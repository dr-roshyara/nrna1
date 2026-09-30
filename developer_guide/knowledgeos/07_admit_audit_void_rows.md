# 07 — `admit.py --audit` and the VOID receipt-stream row (L0-DEC-18)

> **A receipt stream that holds two kinds of record must be read as two kinds
> of record, and a third kind must be reported, not guessed at.**

## Purpose

This guide covers the narrow repair of the evidence-binding audit in
`docs/knowledgeos/knowledgeos_theory_chronological_extraction/evidence/admit.py`,
authorized by **L0-DEC-18** (governance session, tests-first, independent
review afterward). The finding it answers is
`governance/audits/2026-09-23-ADMIT-AUDIT-CRASH-FINDING.md` (A-1…A-5).

## What was wrong

Row 8 of `evidence/READ-RECEIPTS.jsonl` is a **VOID annotation**: F0047 was
admitted but never read. It has no `sha256_at_read` and no `manifest_hash`.
`cmd_audit` treated every row as a receipt. The row was therefore reported as
bound to a different manifest, and then `r["sha256_at_read"]` raised a
`KeyError`. That has been true since `cd845b6ab`.

## How it works now

`classify_receipt_rows()` splits the stream into three groups:

| Shape | Rule | Audit treatment |
|---|---|---|
| **receipt** | every key in `RECEIPT_FIELDS` is present, and there is no `status` | stale and drift checks, as before |
| **VOID** | `status` starts with `VOID`; `correction_of` and `file_id` are present; there is **no** `sha256_at_read` / `manifest_hash`; an **earlier** receipt exists for the same `file_id` | counted and named. **The voided receipt itself stays in the checks** (fail-closed) |
| **unrecognised** | anything else | **a binding failure**, reported with its line numbers. Never skipped, never a crash |

A receipt cannot be hidden by adding a VOID status. A VOID may not carry
receipt fields, and a receipt may not carry a `status`.

## ⛔ Completes ≠ passes

At HEAD the audit now **completes** with `STATUS: BINDING_FAILURES_PRESENT`,
exit 3. The only failure is the known nine-ID registry divergence (F0031–F0038,
F0040; RC-H-04). Two separate things are true, and neither is "good":

| | State |
|---|---|
| instrument correctness | demonstrated by the regression battery, **pending independent review** |
| audit completion | demonstrated |
| audit PASS | **NO**: a valid STOP condition, known and bounded |
| known evidence divergence | **YES**, until C-5 |
| research release | governed by L0-DEC-19, not by this tool |

L0-DEC-19 item 6 requires the divergence to be **reported explicitly and never
treated as PASS**.

## Testing

```bash
cd docs/knowledgeos/knowledgeos_theory_chronological_extraction
python3 governance/regression/admit_regression.py                      # 9/9, repaired admit.py
python3 governance/regression/admit_regression.py --control rev --rev e6087b615   # 1/9, pre-repair (RED)
```

The cases run on `git archive` copies only. V9 removes the registry **in the
copy** to show that the VOID row alone causes no failure. That is a test
condition, not a production verdict.

## IR-A1 / IR-A2 (L0-DEC-21)

`classify_receipt_rows()` now takes the **raw** lines and parses each one itself. A line that is not valid JSON, or a row whose `file_id` is not a string, is an **unrecognised** row: a reported binding failure, not a traceback (V10, V11). The regression derives its expected receipt count from the snapshot (`genuine_receipts()`: rows with `sha256_at_read` and no `status`, counted before the mutation), so it reproduces at HEAD: **11/11** (27 receipts at `348d322f4`). **L0-DEC-24:** the receipt file is read as **bytes** and each line is decoded inside the same guard, so an invalid UTF-8 line is also unrecognised (V12; 12/12). ⚠️ Still open, recorded: line numbers count non-blank lines (V-A1b), and pathologically deep JSON raises `RecursionError` (V-A1c). ⚠️ The registry section still parses `FILE-REGISTRY.jsonl` without a guard. That lies outside IR-A1 and is recorded, not changed.

## Pitfalls

- The design issue recorded in A-5 is unchanged: `--receipt` still admits a
  file and writes a read receipt in one step. It is outside L0-DEC-18.
- Do not add new receipt-stream shapes without extending
  `classify_receipt_rows()` **and** a regression case. Until then an
  unrecognised row fails the audit, by design.

*Traceability:* L0-DEC-18/19 · finding A-1…A-5 · commits `f5067fd31` (RED) and
the repair commit · `evidence/admit.py` `classify_receipt_rows`, `cmd_audit`.
