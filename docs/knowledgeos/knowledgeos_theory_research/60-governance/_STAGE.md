# 60-governance — what is governed, and by which acts

**`[DEF]` stage contract · P6.** Written 2026-09-11. Registered in [`../00-INDEX.md`](../00-INDEX.md) §D.

> ## The one question this stage answers
> ### *What is governed, and by which acts?*

| | |
|---|---|
| **Entry criteria** | `50-validation/` holds validation records |
| **Exit criteria** (terminal predicate) | **one field per act**, each carrying `YES[<record>, <date>, <body>]` or `NOT-EVIDENCED-IN-CAPTURE` — no act left unstated |
| **May be written here** | decision records · governance status |
| **Must NOT be written here** | an inference from validation to governance · an inference from governance to truth · a promotion with no recorded act |
| **Source phase** | protocol P6 |
| **Templates** | [`TEMPLATE-decision`](../templates/TEMPLATE-decision.md) · [`TEMPLATE-record`](../templates/TEMPLATE-record.md) |

## The rule that governs this stage

**Governance is a vector, one field per act** (§C4 applied at scale):

`reviewed` · `recommended` · `selected` · `adopted` · `ratified` · `rejected` · `withdrawn`

> **Governance ≠ truth.** **Not ratified ≠ false.**

**This is the only stage where a quarantined derived proposal may be promoted** — and only by a
recorded act. A proposal that was never acted on stays a proposal, however good it looks.

**A validation result does not carry governance.** Something can be proven and never adopted;
something can be adopted and never proven. Both stay recorded, side by side.

## What is deliberately absent here

No JSONL records and no script. Added only if this stage needs machine-parsing.
