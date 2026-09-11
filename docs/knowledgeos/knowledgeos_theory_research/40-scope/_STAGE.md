# 40-scope — what belongs

**`[DEF]` stage contract · P4.** Written 2026-09-11. Registered in [`../00-INDEX.md`](../00-INDEX.md) §D.

> ## The one question this stage answers
> ### *What belongs?*

| | |
|---|---|
| **Entry criteria** | `30-reconciliation/` holds a per-object roll-up |
| **Exit criteria** (terminal predicate) | every object has a membership value, and **each membership field is evidenced separately** — no field inferred from another |
| **May be written here** | membership records · scope boundaries |
| **Must NOT be written here** | an inference from one membership field to another · a membership decided by recency, elegance, length or repetition |
| **Source phase** | protocol P4 |
| **Template** | [`TEMPLATE-record`](../templates/TEMPLATE-record.md) |

## The rule that governs this stage

**Each field is answered on its own evidence**, with `YES[<record>]` or `NOT-EVIDENCED-IN-CAPTURE`:

`existed-before` · `defined-in` · `referenced-by` · `used-by` · `required-by` · `proposed-for` ·
`adopted-in` · `ratified-in` · `created-after`

**Never infer one from another.** An object referenced by a document did not thereby exist before
it; an object proposed for a version was not thereby adopted in it.

> *Recency, elegance, length and repetition are not criteria.*

## What is deliberately absent here

No JSONL records and no script. Added only if this stage needs machine-parsing — this is the stage
most likely to want it, since membership is naturally tabular.
