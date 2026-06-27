---
knowledge_id: ADJ-README
title: Adjudication — Bounded Context
knowledge_type: domain-model
bounded_context: adjudication
status: approved
authority: authoritative
audience: [developer, architect, ai]
owner: nab.raj.sharma
version: 1.0
schema_version: 1
tags: [adjudication, determination, challenge, ddd]
related_to: [HUB-ADJUDICATION]
implements: []
adr: []
documents:
  - ADJ-DISC-BOUNDARY
  - ADJ-MODEL-DETERMINATION
  - ADJ-SM-DETERMINATION
code_refs:
  - app/Contexts/Adjudication
test_refs:
  - tests/Unit/Contexts/Adjudication
  - tests/Feature/Contexts/Adjudication
---

# Adjudication — Bounded Context

> **Pilot domain** for the Engineering Knowledge Platform: a self-contained example of how every bounded context will be documented. Chosen because it is small, clean, greenfield, and Deptrac-enforced.

## Purpose

Given a **challenge** (raised in the Contestation context) and an **evidence envelope**, an **issuing authority decides the outcome** — recorded as a **Determination**. The decision is issued once and becomes **Final** and immutable.

## Ubiquitous language

| Term | Meaning |
|---|---|
| Determination | The aggregate: the recorded ruling on a challenge |
| Outcome | `Upheld` (challenge succeeds → correction follows) or `Dismissed` (fails → no correction) |
| Legitimacy | Value object qualifying the ruling's standing |
| Issuing authority | Who issued the determination (value object) |
| Jurisdiction | The scope under which it was issued |
| Evidence envelope (ref) | Reference to evidence held in the Evidence context |
| Challenge (ref) | Reference to the challenge in the Contestation context |

## Boundary (what this context owns)

Owns **only**: identity, constitutional outcome, legitimacy, issuing authority, jurisdiction, evidence references, finality state.

Does **not** own: challenge/election workflow, correction execution, replay, notification, or persistence semantics. Cross-context collaboration is **by reference and domain events only** (enforced by [`deptrac.yaml`](../../../../deptrac.yaml)).

## Map of this domain folder

| Sub-topic | Document | Code |
|---|---|---|
| Boundary discovery | [discovery/determination-boundary.md](discovery/determination-boundary.md) | — |
| Aggregate | [model/determination-aggregate.md](model/determination-aggregate.md) | [`Determination.php`](../../../../app/Contexts/Adjudication/Domain/Determination/Determination.php) |
| State machine | [state-machines/determination-state-machine.md](state-machines/determination-state-machine.md) | [`DeterminationState.php`](../../../../app/Contexts/Adjudication/Domain/Determination/DeterminationState.php) |
| Wiring / implementation | [implementation/wiring.md](implementation/wiring.md) | [`AdjudicationServiceProvider.php`](../../../../app/Contexts/Adjudication/Infrastructure/Providers/AdjudicationServiceProvider.php) |
| Tests | [tests/README.md](tests/README.md) | [`tests/Unit/Contexts/Adjudication`](../../../../tests/Unit/Contexts/Adjudication) |
| Roadmap | [roadmap.md](roadmap.md) | — |

## Related

- Decision: [ADR-T-LOG — Tactical Implementation](../../../adr/ADR-T-LOG-Tactical-Implementation.md)
- Hub: [Adjudication topic hub](../../portal/hubs/adjudication.md)
- Upstream: Contestation (challenge) · Evidence (envelope) · downstream: Election correction
