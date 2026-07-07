# Contestation — Developer Guide

**Audience:** engineers working in the Contestation bounded context (the greenfield Core, with Adjudication).

Contestation owns the **`Challenge`** aggregate — a standing-holder's formal, time-bounded contest of a **`ContestedOutcome`** (an election result or a prior determination). It runs the challenge lifecycle (`Raised → Admitted → Routed → Adjudicated → Resolved`, plus `Dismissed`/`Lapsed`) and publishes challenge domain events. It carries **no voter↔vote linkage** (ADR-T11): the raiser is a standing actor, and what is contested is referenced, never the vote content.

## Guides (per step)
| Guide | Covers |
|-------|--------|
| [`01_contested_outcome.md`](./01_contested_outcome.md) | The **`ContestedOutcome`** concept + **`ContestedOutcomeRef`** Value Object (replacing the former `TargetRef:string`) — what a Challenge contests, and why it is election-scoped. (ADR-UL-01) |

## Authoritative architecture
- **Ubiquitous language / concept:** `docs/adr/ADR-UL-01-ContestedOutcome.md` (concept, glossary, ownership).
- **Published language:** `docs/adr/ADR-PL-01-DeterminationIssued-v2.md` (how the concept crosses to Adjudication/Election).
- **Aggregate / lifecycle:** Round 50-07 v1.2 · ADR-T20 (`Adjudicated ≠ Resolved`).

## Invariants you must preserve
- **Anonymity (ADR-T11):** nothing here may link a voter to a vote. A `ContestedOutcome` references a Result or Determination — never a vote or voter.
- **Illegal-transition policy:** forbidden state transitions throw with **no mutation**; the caller audits the attempt.
- **Injected time (Principle 9):** no `now()`/`Carbon` in the domain — time is passed in.
