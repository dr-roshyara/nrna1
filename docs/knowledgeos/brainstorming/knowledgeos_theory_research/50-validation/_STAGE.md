# 50-validation — what is validated, and by which means

**`[DEF]` stage contract · P5.** Written 2026-09-11. Registered in [`../00-INDEX.md`](../00-INDEX.md) §D.

> ## The one question this stage answers
> ### *What is validated, and by which means?*

| | |
|---|---|
| **Entry criteria** | `40-scope/` holds membership records |
| **Exit criteria** (terminal predicate) | **one field per means**, each carrying evidence or `NOT-EVIDENCED-IN-CAPTURE` — no means left unstated |
| **May be written here** | validation records · experiment records with hypothesis → result → interpretation → limitations |
| **Must NOT be written here** | a claim that one means substitutes for another · a validation with no stated means |
| **Source phase** | protocol P5 |
| **Template** | [`TEMPLATE-record`](../templates/TEMPLATE-record.md) |

## The rule that governs this stage

**Validation is a vector, one field per means** — never one grade (this is §C4 applied at scale):

`proof` · `experiment` · `simulation` · `implementation` · `test` · `independent_validation`

Each field is `YES[<record>]` · `PARTIAL[<record>]` · `NOT-EVIDENCED-IN-CAPTURE` · `FAILED[<record>]`.

> **`experiment` ≠ `proof`.** **`implementation` ≠ `definition`.** **`test` ≠ `independent
> validation`.**

Each experiment cited keeps its full shape — **hypothesis → result → interpretation →
limitations**. An experiment recorded without its limitations is recorded as `PARTIAL`.

## What is deliberately absent here

No JSONL records and no script. Added only if this stage needs machine-parsing.
