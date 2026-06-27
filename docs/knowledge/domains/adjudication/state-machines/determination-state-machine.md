---
knowledge_id: ADJ-SM-DETERMINATION
title: Determination State Machine
knowledge_type: state-machine
bounded_context: adjudication
status: approved
authority: authoritative
audience: [developer, architect, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [adjudication, determination, state-machine]
related_to: [ADJ-MODEL-DETERMINATION]
documents: []
code_refs:
  - app/Contexts/Adjudication/Domain/Determination/DeterminationState.php
  - app/Contexts/Adjudication/Domain/Determination/Exception/IllegalDeterminationTransition.php
---

# Determination State Machine

> Lifecycle: `Draft → Issued → Final`. `Final` is terminal and immutable (override only via constitutional amendment). Source: [`DeterminationState.php`](../../../../../app/Contexts/Adjudication/Domain/Determination/DeterminationState.php).

```mermaid
stateDiagram-v2
    [*] --> Draft: prepare()
    Draft --> Issued: issue() / emits DeterminationIssued
    Issued --> Final: finalize() / no event
    Final --> [*]
    note right of Final
      Terminal & immutable.
      No transition or event after Final.
    end note
```

## Transition table

| State | Legal command | Next | Event | Illegal commands |
|---|---|---|---|---|
| `Draft` | `issue()` | `Issued` | `DeterminationIssued` | `finalize()` |
| `Issued` | `finalize()` | `Final` | _(none)_ | `issue()` (already issued) |
| `Final` | _(none)_ | — | — | `issue()`, `finalize()` |

## Discipline

Any illegal command throws [`IllegalDeterminationTransition`](../../../../../app/Contexts/Adjudication/Domain/Determination/Exception/IllegalDeterminationTransition.php) via the aggregate's `guard()` — **with no mutation and no event**. `DeterminationState::isTerminal()` returns true only for `Final`.
