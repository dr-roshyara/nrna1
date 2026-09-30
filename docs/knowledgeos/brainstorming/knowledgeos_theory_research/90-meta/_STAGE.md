# 90-meta — runs, changelog, self-audit

**`[DEF]` · written 2026-09-11.** Registered in [`../00-INDEX.md`](../00-INDEX.md) §F.

> ## The one question this folder answers
> ### *What did we do — and did we do it as recorded?*

| | |
|---|---|
| **Contains** | `RUNS.md` — one line per working session · `CHANGELOG.md` — append-only · self-audit records |
| **May be written here** | run lines · change entries · self-audit records |
| **Must NOT be written here** | a repair of an earlier record |

## The rule that governs this folder

**A self-audit records a discrepancy. It does not fix a record** (§C8).

If an audit finds that a record says something the evidence does not support, the audit **writes
that finding down** and the correction is a **new record**. The discrepancy is history too — a record
that was wrong for a while and was then corrected is different from one that was never wrong, and
the difference matters.

**If the same discrepancy pattern recurs, re-audit backward from its first appearance** — the first
appearance is where the cause is.

## The three files

| file | format | rule |
|---|---|---|
| `RUNS.md` | table, one row per session | append-only · one line |
| `CHANGELOG.md` | table, newest last | append-only · a change to a record is a **new entry naming the record changed** |
| self-audit records | one file per audit | records, never repairs |
