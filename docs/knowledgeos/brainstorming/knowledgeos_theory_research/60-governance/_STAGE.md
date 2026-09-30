# 60-governance — what is governed, and by which acts

**`[DEF]` stage contract · P6.** Written 2026-09-11 · amended 2026-09-11 (A3). Registered in
[`../00-INDEX.md`](../00-INDEX.md) §D.

> ## The one question this stage answers
> ### *What is governed, and by which acts?*

| | |
|---|---|
| **Entry criteria** | `50-validation/` holds validation records |
| **Exit criteria** (terminal predicate) | **one field per act**, each carrying `YES[<record>, <date>, <body>]` or `NOT-EVIDENCED-IN-CAPTURE` — no act left unstated |
| **May be written here** | decision records · governance status |
| **Must NOT be written here** | an inference from validation to governance · an inference from governance to truth · a promotion with no recorded act · **a decision recorded as a step above a finding** |
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

## Governance is orthogonal to epistemic status *(A3)*

**A decision is not the rung above a finding.** It is a **different kind of record** (§C4.4).

| | |
|---|---|
| A `FINDING` answers | *what the evidence supports* |
| A decision answers | *what has been agreed* |

Neither follows from the other, and neither grades the other. Concretely:

- A decision record carries **`claim_layer: N/A`** — an act is not a claim. Recording it as the top
  of an epistemic ladder would make one axis do two unrelated jobs.
- A governance act may attach to a record at **any** `claim_layer`, including `UTTERANCE`. *Something
  said in a discussion can be adopted as a decision without ever becoming our finding.*
- A governance act **never changes** a record's `claim_layer`, `provenance` or `evidence_status`.
- Adopting a hypothesis does not make it a finding. A finding needs evidence, not a vote; a decision
  needs an act, not evidence. **The two are recorded separately and both remain.**

## What is deliberately absent here

No JSONL records and no script. Added only if this stage needs machine-parsing.
